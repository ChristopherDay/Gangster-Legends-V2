<?php

    class jail extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));

        public $pageName = 'Jail';
        
        public function getJailUsers($id = false) {

            $add = "";
            $params = array();

            if ($id) {
                $add = " AND `jail`.UT_user = :id";
                $params[":id"] = $id;
            }

            $inJail = !$this->user->checkTimer("jail");

            $inSuperMax = $inJail && $this->user->getTimer("jail") == $this->user->getTimer("superMax");

            if ($inSuperMax) {
                $divide = 0;
            } else if ($inJail) {
                $divide = 2;
            } else {
                $divide = 1;
            }

            $usersInJail = $this->db->prepare("
                SELECT DISTINCT 
                    `U_id` as 'id', 
                    (`U_id` = ".$this->user->id.") as 'currentUser',
                    `jail`.`UT_time` as 'time', 
                    (`max`.`UT_time` = `jail`.`UT_time`) as 'superMax',  
                    CASE 
                        WHEN `max`.`UT_time` = `jail`.`UT_time` THEN 0
                        WHEN US_rank > 16 THEN 10 / ".$divide."
                        ELSE (95 - (US_rank * 5)) / ".$divide."
                    END as 'percent'
                FROM 
                    `userTimers` `jail`
                    INNER JOIN `users` ON (U_id = UT_user) 
                    INNER JOIN `userStats` ON (US_id = UT_user)
                    LEFT OUTER JOIN `userTimers` as `max` ON (`max`.`UT_desc` = 'superMax' AND `max`.`UT_user` = `jail`.`UT_user`)
                WHERE 
                    `US_location` = :location AND 
                    `jail`.`UT_desc` = 'jail' AND 
                    `jail`.`UT_time` > UNIX_TIMESTAMP()
                    ".$add."
                ORDER BY US_rank ASC, U_name
            ");

            $usersInJail->bindParam(":location", $this->user->info->US_location);
            foreach ($params as $key => $value) {
                $usersInJail->bindParam($key, $value);
            }
            $usersInJail->execute();
            $usersInJail = $usersInJail->fetchAll(PDO::FETCH_ASSOC);

            foreach ($usersInJail as $key => $value) {
                $u = new User($value["id"]);
                $usersInJail[$key]["user"] = $u->user;
            }

            if ($id) {
                if (!count($usersInJail)) return false;
                return $usersInJail[0];
            } else {
                return $usersInJail;
            }

        }

        public function method_breakout() {
            $id = abs(intval($this->methodData->id));

            $jailUser = $this->getJailUsers($id);

            if (!$jailUser["time"]) {
                return $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "This user is not in this jail"
                ));
            }

            if ((int) $jailUser["superMax"]) {
                return $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "This user is in super max"
                ));
            }

            $chance = mt_rand(1, 100);

            $inJail = !$this->user->checkTimer("jail");
            $inSuperMax = $inJail && $this->user->getTimer("jail") == $this->user->getTimer("superMax");


            if ($inSuperMax) {
                return $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "You can't bust anyone out while you are in super max"
                ));
            }

            if ($id == $this->user->id) {
                $jailUser["name"] = "yourself";
            }

            if ($jailUser["percent"] >= $chance) {
                $user = new user($id);
                $user->updateTimer("jail", time()-1);

                $u = $this->db->prepare("
                    UPDATE 
                        userStats 
                    SET 
                        US_exp = US_exp + 1 
                    WHERE 
                        US_id = ".$this->user->id);
                $u->execute();

                return $this->alerts[] = $this->page->buildElement('success', array(
                    "text" => "You broke " . $jailUser["name"] . " out of jail"
                ));
            } else {


                if ($inJail) {
                    $jailTime = $this->user->getTimer("jail") + 90;
                    $this->user->updateTimer("superMax", $jailTime);
                } else {
                    $jailTime = time() + 90;
                }
                $this->user->updateTimer("jail", $jailTime);
                return $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "You failed to break " . $jailUser["name"] . " out of jail"
                ));
            }

        }

        public function constructModule() {
            

            if (!$this->user->checkTimer("jail")) {
                $inSuperMax = $this->user->getTimer("jail") == $this->user->getTimer("superMax")?"super max":"jail";
            	$this->alerts[] = $this->page->buildElement('error', array(
                    "text" => 'You are in '.$inSuperMax.' for <span data-reload-when-done data-timer-type="inline" data-timer="'.$this->user->getTimer("jail").'"></span>!'
                ));
            }   

            $users = $this->getJailUsers();

            $this->html .= $this->page->buildElement("jailUsers", 
	        	array(
	        		"users" => $users, 
                    "location" => $this->user->getLocation()
		        )
	        );
        }
        
    }

?>