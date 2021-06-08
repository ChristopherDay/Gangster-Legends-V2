<?php

    class kill extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "submit" => array( "type" => "POST" ),
            "detective" => array( "type" => "POST" ),
            "bullets" => array( "type" => "POST" )
        );

        public function getItem($id) {

            $item = $this->db->prepare("
                SELECT
                    I_id as 'id',  
                    I_name as 'name',
                    ROUND(I_damage / 100, 2) as 'damage'
                FROM items
                WHERE I_id = :id
            ");
            $item->bindParam(":id", $id);
            $item->execute();
            $item = $item->fetch(PDO::FETCH_ASSOC);

            if (!$item["id"]) {
                $item = array(
                    "id" => 0, 
                    "name" => "None", 
                    "damage" => 1
                );
            }

            return $item; 
        }

        public function method_shoot() {

            if (isset($this->methodData->submit)) {

                $expireTime = $this->_settings->loadSetting("detectiveExpire", true, 600);

                $detective = $this->db->prepare("SELECT * FROM detectives WHERE D_id = :id");
                $detective->bindParam(":id", $this->methodData->detective);
                $detective->execute();

                $detective = $detective->fetch(PDO::FETCH_ASSOC);

                if ($detective["D_user"] != $this->user->id) {
                    return $this->error("This detective report does not belong to you!");
                }

                if ($detective["D_end"] > time()) {
                    return $this->error("The detective does not know where this user is yet!");
                }

                if (($detective["D_end"] + $expireTime) < time()) {
                    return $this->error("The detective report has expired!");
                }

                if ($detective["D_success"] == 0) {
                    return $this->error("The detective report was unsuccessful!");
                }

                $userToKill = new User($detective["D_userToFind"]);

                if ($userToKill->info->U_status == 0) {
                    return $this->error("This user is already dead!");
                }

                if ($userToKill->info->US_location != $this->user->info->US_location) {
                    return $this->error("You need to be in " . $userToKill->getLocation() . " to kill this user!");
                }

                $bullets = $this->methodData->bullets;

                if ($bullets <= 0) {
                    return $this->error("Please enter 1 or more bullets to shoot");
                }

                if ($bullets > $this->user->info->US_bullets) {
                    return $this->error("You dont have " . number_format($bullets) . " bullets to shoot!");
                }


                $damage = floor($this->user->attackPower / $userToKill->defensePower * $bullets);
                $health = $userToKill->rank->R_health;

                $damageDonePercent = $damage / $health;

                $newUserHealth = ($userToKill->rank->R_health * $damageDonePercent) + $userToKill->info->US_health;

                $userToKill->set("US_health", $newUserHealth);
                $this->user->set("US_bullets", $this->user->info->US_bullets - $bullets);

                if ($userToKill->rank->R_health < $newUserHealth) {
                    $success = true;
                    $userToKill->set("U_status", 0);
                    $userToKill->set("US_shotBy", $this->user->id);
                    $userToKill->updateTimer("killed", time());
                    $this->success("You have killed " . $userToKill->info->U_name);
                    $killHook = new hook("userKilled");
                    $users = array(
                        "shooter" => $this->user, 
                        "killed" => $userToKill
                    );
                    $killHook->run($users);
                } else {
                    $success = false;
                    $this->error("You failed to kill " . $userToKill->info->U_name);
                    $userToKill->newNotification($this->user->info->U_name . " tried to shoot you!");
                }               

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "kill", 
                    "id" => 1, 
                    "success" => $success, 
                    "reward" => $bullets
                );
                $actionHook->run($action);

                $expireDetective = $this->db->prepare("
                    UPDATE detectives SET D_end = 0 WHERE D_id = :id 
                ");
                $expireDetective->bindParam(":id", $this->methodData->detective);
                $expireDetective->execute();

            }


        }

        public function constructModule() {

            $expireTime = $this->_settings->loadSetting("detectiveExpire", true, 600);

            $detectives = $this->db->selectAll("
                SELECT
                    D_id as 'id', 
                    D_userToFind as 'uid',
                    D_detectives as 'detectives', 
                    D_start as 'start',
                    D_end as 'end',
                    D_end + :expireTime as 'expires',
                    D_success as 'success'
                FROM detectives 
                WHERE 
                    D_user = :id AND 
                    D_end < UNIX_TIMESTAMP() AND 
                    (D_end + :expireTime) > UNIX_TIMESTAMP() AND 
                    D_success = 1
                ORDER BY D_start DESC
            ", array(
                ":expireTime" => $expireTime,
                ":id" => $this->user->id
            ));

            foreach ($detectives as $key => $value) {
                $u = new User($value["uid"]);
                $value["user"] = $u->user;
                $value["location"] = $u->getLocation();
                $detectives[$key] = $value;
            }

            $this->html .= $this->page->buildElement("killPage", array(
                "detectives" => $detectives
            ));
        }

    }

