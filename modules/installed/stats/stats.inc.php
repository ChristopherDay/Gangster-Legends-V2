<?php

    class stats extends module {
        
        public $allowedMethods = array();
		
		public $pageName = 'Game Statistics';

        public function getUsers($alive) {

            if ($alive) {
                $sql = "SELECT * FROM users WHERE U_status != 0 ORDER BY U_id DESC LIMIT 0, 20";
                $timer = "signup";
            } else {
                $sql = "SELECT * FROM users WHERE U_status = 0 ORDER BY U_id DESC LIMIT 0, 20";
                $timer = "killed";
            }

            $users = $this->db->prepare($sql);
            $users->execute();
            $allUsers = $users->fetchAll(PDO::FETCH_ASSOC);

            $userObjects = array();

            foreach ($allUsers as $user) {
                $user = new User($user["U_id"]);
                $userObjects[] = array(
                    "user" => $user->user, 
                    "date" => $this->date($user->getTimer($timer))
                );
            }

            return $userObjects;

        }
        
        public function constructModule() {

        	$stats = $this->db->prepare("
        		SELECT 
                    SUM(US_bullets) as 'bullets',
        			SUM(US_points) as 'points',
        			SUM(US_money) + SUM(US_bank) as 'cash', 
        			COUNT(U_id) as 'alive'
        		FROM users INNER JOIN userStats ON (US_id = U_id) 
        		WHERE U_status != 0 
        		ORDER BY U_id DESC LIMIT 0, 20
        	");
        	$stats->execute();
        	$stats = $stats->fetch(PDO::FETCH_ASSOC);

        	$deadStats = $this->db->prepare("
        		SELECT 
        			SUM(US_bullets) as 'bullets',
        			SUM(US_money) + SUM(US_bank) as 'cash', 
        			COUNT(U_id) as 'dead'
        		FROM users INNER JOIN userStats ON (US_id = U_id) 
        		WHERE U_status = 0 
        		ORDER BY U_id DESC LIMIT 0, 20
        	");
        	$deadStats->execute();
        	$deadStats = $deadStats->fetch(PDO::FETCH_ASSOC);

            $this->html .= $this->page->buildElement("stats", array(
                "newUsers" => $this->getUsers(true), 
            	"deadUsers" => $this->getUsers(false), 
            	"dead" => $deadStats["dead"],
                "alive" => $stats["alive"],
            	"points" => $stats["points"],
            	"cash" => $stats["cash"],
            	"bullets" => $stats["bullets"]
            ));
        }
        
    }

?>