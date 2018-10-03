<?php

    class mail extends module {
        
        public $allowedMethods = array(
        	"view" => array( "type" => "GET" ),
        	"id" => array( "type" => "GET" ),
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
				FROM mail WHERE $add
			");
			$mail->bindParam(":user", $this->user->id);

			if ($id) {
				$mail->bindParam(":id", $id);
			}

			$mail->execute();
			$allMail = $mail->fetchAll(PDO::FETCH_ASSOC);

			foreach ($allMail as $key => $mail) {

				$receiver = new User($mail["receiver"]);
				$mail["receiver"] = (array) $receiver->info;

				$sender = new User($mail["sender"]);
				$mail["sender"] = (array) $sender->info;

				$mail["date"] = date("jS M H:i", $mail["time"]);

				$allMail[$key] = $mail;
			}

			return $allMail; 
		}

        public function constructModule() {

        	switch (@$this->methodData->view) {
        		case "reply": 
        			$this->reply();
        			$this->viewInbox();
        		break;
        		case "read": 
        			$this->readEmail();
        		break;
        		case "outbox": 
        			$this->viewOutbox();
        		break;
        		default:
        			$this->viewInbox();
        		break;
        	}

        }

        public function reply() {

            $replyTo = @$this->getMailList(false, $this->methodData->id)[0];

            if (!$replyTo) {
            	return $this->html .= $this->page->buildElement("error", array(
            		"text" => "This mail does not exist"
            	));
            }

            debug($this->);

        }

        public function readEmail() {

        	$read = $this->db->prepare("UPDATE mail SET M_read = 1 WHERE M_id = :id");
        	$read->bindParam(":id", $this->methodData->id);
        	$read->execute();

            $this->html .= $this->page->buildElement(
            	"mail", 
            	$this->getMailList(false, $this->methodData->id)[0]
            );
        }

        public function viewInbox() {
			$mail = $this->getMailList();
			foreach ($mail as $key => $value) {
				$mail[$key]["inbox"] = true;
			}
            $this->html .= $this->page->buildElement("mailInbox", array(
				"inbox" => true,
            	"mail" => $mail
            ));
        }

        public function viewOutbox() {
			$mail = $this->getMailList("M_sid");
			foreach ($mail as $key => $value) {
				$mail[$key]["inbox"] = false;
			}
            $this->html .= $this->page->buildElement("mailOutbox", array(
				"inbox" => false,
            	"mail" => $mail
            ));
        }
        
    }

?>