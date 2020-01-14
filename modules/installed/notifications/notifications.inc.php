<?php

    class notifications extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "p" => array( "type" => "GET" )
        );
        
        public $pageName = '';

        public function method_delete() {

            $this->db->delete("
                DELETE FROM notifications WHERE N_id = :id AND N_uid = :uid
            ", array(
                ":id" => $this->methodData->id,
                ":uid" => $this->user->id
            ));

            $this->error("Notification deleted!", "success");

        }

        public function constructModule() {

            $perPage = 20;

            $maxPages = ceil($this->db->select("
                SELECT COUNT(*) as 'count' FROM notifications WHERE N_uid = :user
            ", array(
                ":user" => $this->user->id
            ))["count"] / $perPage);

            $page = 1;

            if (isset($this->methodData->p)) {
                $page = abs(intval($this->methodData->p));
            }

            $from = ($page - 1) * $perPage;

            $notifications = $this->db->prepare("
                SELECT
                    N_id as 'id', 
                    N_read as 'read', 
                    N_text as 'text', 
                    N_time as 'time'
                FROM notifications WHERE N_uid = :id
                ORDER BY N_time DESC
                LIMIT " . $from . ", " . $perPage . ";
                UPDATE notifications SET N_read = 1 WHERE N_uid = :id AND N_read = 0;
            ");

            $notifications->bindParam(":id", $this->user->id);
            $notifications->execute();
            $notifications = $notifications->fetchAll(PDO::FETCH_ASSOC);

            foreach ($notifications as $key => $value) {
                $notifications[$key]["date"] = date("jS M H:i", $value["time"]);
            }

            $pages = array();

            $i = 0;
            while ($i < $maxPages) {
                $p = $i + 1;
                $pages[] = array(
                    "page" => $p, 
                    "active" => $p == $page
                );
                $i++;
            }

            $this->html = $this->page->buildElement("notifications", array(
                "userNotifications" => $notifications, 
                "pages" => $pages
            ));

        }
        
    }

?>