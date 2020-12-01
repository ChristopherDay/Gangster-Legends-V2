<?php

    class mail extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "name" => array( "type" => "REQUEST" ),
            "reply" => array( "type" => "GET" ),
            "message" => array( "type" => "POST" ),
            "subject" => array( "type" => "POST" )
        );
        
        public $pageName = '';
        
        public function getMailList($type = 'M_uid', $id = false) {
            $add = "$type = :user";
            
            if ($id) {
                $add = "M_id = :id AND (M_sid = :user OR M_uid = :user)";
            }

            $mail = $this->db->prepare("
                SELECT
                    M_id as 'id', 
                    M_time as 'time', 
                    M_sid as 'sender', 
                    M_uid as 'receiver', 
                    M_subject as 'subject', 
                    M_text as 'text', 
                    M_type as 'type', 
                    M_read as 'read' 
                FROM mail WHERE $add ORDER BY M_time DESC
            ");
            $mail->bindParam(":user", $this->user->id);

            if ($id) {
                $mail->bindParam(":id", $id);
            }

            $mail->execute();
            $allMail = $mail->fetchAll(PDO::FETCH_ASSOC);

            foreach ($allMail as $key => $mail) {

                if ($type == 'M_sid') {
                    $receiver = new User($mail["receiver"]);
                    $mail["user"] = (array) $receiver->user;
                } else {
                    $sender = new User($mail["sender"]);
                    $mail["user"] = (array) $sender->user;
                }


                $mail["date"] = date("jS M H:i", $mail["time"]);

                $allMail[$key] = $mail;
            }

            return $allMail; 
        }

        public function constructModule() {
            if (!isset($this->methodData->action)) $this->method_inbox();
        }

        public function method_read () {

            $read = $this->db->prepare("UPDATE mail SET M_read = 1 WHERE M_id = :id AND M_uid = :uid");
            $read->bindParam(":id", $this->methodData->id);
            $read->bindParam(":uid", $this->user->id);
            $read->execute();

            $opts = $this->getMailList(false, $this->methodData->id)[0];
            $opts["action"] = "reply";
            $this->html .= $this->page->buildElement(
                "mail", 
                $opts
            );
        }

        public function method_inbox() {
            $mail = $this->getMailList();
            foreach ($mail as $key => $value) {
                $mail[$key]["inbox"] = true;
            }
            $this->html .= $this->page->buildElement("mailInbox", array(
                "inbox" => true,
                "mail" => $mail
            ));
        }

        public function method_outbox() {
            $mail = $this->getMailList("M_sid");
            foreach ($mail as $key => $value) {
                $mail[$key]["inbox"] = false;
            }
            $this->html .= $this->page->buildElement("mailOutbox", array(
                "inbox" => false,
                "mail" => $mail
            ));
        }

        public function validateMail() {
            $error = false;

            if (isset($this->methodData->subject)) {
                if (strlen($this->methodData->subject) < 2) {
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text" => "The subject must be at least two characters"
                    ));
                    $error = true;
                }

                if (strlen($this->methodData->message) < 6) {
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text" => "The message must be at least six characters"
                    ));
                    $error = true;
                }
            } else {
                return true;
            }

            return $error;
            
        } 

        public function method_delete() {

            $mail = $this->db->select("
                SELECT
                    M_id as 'id', 
                    M_time as 'time', 
                    M_sid as 'sender', 
                    M_uid as 'receiver', 
                    M_subject as 'subject', 
                    M_text as 'text', 
                    M_type as 'type', 
                    M_read as 'read' 
                FROM mail WHERE 
                    M_id = :id
                ORDER BY M_time DESC
            ", array(
                ":id" => $this->methodData->id
            ));

            if ($mail["receiver"] != $this->user->id) {
                $this->error("You can't delete this mail!");
            } else {
                $this->db->delete("DELETE FROM mail WHERE M_id = :id", array(
                    ":id" => $this->methodData->id
                ));
            }

            $this->method_inbox();
        }

        public function method_new() {

            if (isset($this->methodData->name)) {

                $to = new User(null, $this->methodData->name);

                if (!isset($to->info->U_id)) {
                    return $this->error("This user does not exist");
                }

                if ($to->info->U_id == $this->user->id) {
                    return $this->error("You cant message yourself");
                }

            }

            if (!$this->user->checkTimer("sentMail")) {
                return $this->error("You can't sent another mail yet, please wait a little bit!");
            }

            $error = $this->validateMail();

            if (!$error) {
                $send = $this->db->prepare("INSERT INTO mail (
                    M_time, M_uid, M_sid, M_subject, M_text, M_parent, M_type
                ) VALUES(
                    UNIX_TIMESTAMP(), :to, :from, :subject, :message, 0, 0
                )");

                $send->bindParam(":to", $to->info->U_id);
                $send->bindParam(":from", $this->user->id);
                $send->bindParam(":subject", $this->methodData->subject);
                $send->bindParam(":message", $this->methodData->message);

                $send->execute();

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "Message Sent"
                ));

                $this->user->updateTimer("sentMail", 20, true);
            }

            $opts = array(
                "showUser" => true,
                "action" => "new"
            );

            if (isset($to)) {
                $opts["user"] = $to->user;
            }
            if (isset($this->methodData->name)) {
                $opts["name"] = $this->methodData->name;
            }

            $this->html .= $this->page->buildElement("newMail", $opts);

        }

        public function method_reply() {

            $replyTo = @$this->getMailList(false, $this->methodData->id)[0];

            if (!$replyTo) {
                return $this->alerts[] = $this->page->buildElement("error", array(
                    "text" => "This mail does not exist"
                ));
            }

            if ($replyTo["sender"] == $this->user->id) {
                return $this->alerts[] = $this->page->buildElement("error", array(
                    "text" => "You cant message yourself"
                ));
            }

            $error = $this->validateMail();

            if (!$error) {

                $send = $this->db->prepare("INSERT INTO mail (
                    M_time, M_uid, M_sid, M_subject, M_text  
                ) VALUES(
                    UNIX_TIMESTAMP(), :to, :from, :subject, :message
                )");

                $send->bindParam(":to", $replyTo["sender"]);
                $send->bindParam(":from", $this->user->id);
                $send->bindParam(":subject", $this->methodData->subject);
                $send->bindParam(":message", $this->methodData->message);

                $send->execute();

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "Reply Sent"
                ));
            }


            $this->method_read();
        }
        
    }

