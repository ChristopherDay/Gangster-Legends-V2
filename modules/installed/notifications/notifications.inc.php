<?php

    class notifications extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "reply" => array( "type" => "GET" ),
            "message" => array( "type" => "POST" ),
            "subject" => array( "type" => "POST" )
        );
        
        public $pageName = '';

        public function constructModule() {

            $notifications = $this->db->prepare("
                SELECT
                    N_read as 'read', 
                    N_text as 'text', 
                    N_time as 'time'
                FROM notifications WHERE N_uid = :id
                ORDER BY N_time DESC;
                UPDATE notifications SET N_read = 1 WHERE N_uid = :id AND N_read = 0;
            ");

            $notifications->bindParam(":id", $this->user->id);
            $notifications->execute();
            $notifications = $notifications->fetchAll(PDO::FETCH_ASSOC);

            foreach ($notifications as $key => $value) {
                $notifications[$key]["date"] = date("jS M H:i", $value["time"]);
            }

            $this->html = $this->page->buildElement("notifications", array(
                "userNotifications" => $notifications
            ));

        }
        
    }

?>