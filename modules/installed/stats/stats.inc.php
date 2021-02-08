<?php

    class stats extends module {
        
        public $allowedMethods = array();
        
        public $pageName = 'Game Statistics';

        public function getUsers($alive) {

            if ($alive) {
                $sql = "
                    SELECT * FROM users INNER JOIN userTimers ON (UT_user = U_id AND UT_desc = 'signup') WHERE U_userLevel = 1 AND U_status != 0 ORDER BY UT_time DESC LIMIT 0, 20
                ";
                $timer = "signup";
            } else {
                $sql = "
                    SELECT * FROM users INNER JOIN userTimers ON (UT_user = U_id AND UT_desc = 'killed') WHERE U_userLevel = 1 AND U_status = 0 ORDER BY UT_time DESC LIMIT 0, 20
                ";
                $timer = "killed";
            }

            $allUsers = $this->db->selectAll($sql);

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

            $stats = $this->db->select("
                SELECT 
                    SUM(US_bullets) as 'bullets',
                    SUM(US_points) as 'points',
                    SUM(US_money) + SUM(US_bank) as 'cash', 
                    COUNT(U_id) as 'alive'
                FROM users INNER JOIN userStats ON (US_id = U_id) 
                WHERE U_status != 0 AND U_userLevel = 1
                ORDER BY U_id DESC LIMIT 0, 20
            ");

            $deadStats = $this->db->select("
                SELECT 
                    SUM(US_bullets) as 'bullets',
                    SUM(US_money) + SUM(US_bank) as 'cash', 
                    COUNT(U_id) as 'dead'
                FROM users INNER JOIN userStats ON (US_id = U_id) 
                WHERE U_status = 0 
                ORDER BY U_id DESC LIMIT 0, 20
            ");

            $this->html .= $this->page->buildElement("stats", array(
                "newUsers" => $this->getUsers(true), 
                "deadUsers" => $this->getUsers(false), 
                "dead" => $deadStats["dead"],
                "alive" => $stats["alive"],
                "points" => (int) $stats["points"],
                "cash" => (int) $stats["cash"],
                "bullets" => (int) $stats["bullets"]
            ));
        }
        
    }

