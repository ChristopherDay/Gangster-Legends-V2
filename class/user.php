<?php

    $__userCache = array();

    class user {
        
        public $id, $info, $name, $db, $loggedin = false, $nextRank, $user;
        
        // Pass the ID to the class
        function __construct($id = FALSE, $name = FALSE) {

            global $db, $__userCache;
            
            if ($id && isset($__userCache[$id])) {
                foreach(get_object_vars($__userCache[$id]) as $k => $v){
                    $this->{$k}=$v;
                }
                return;
            } 
            
            $this->db = $db;
            
            if (isset($id) || isset($name)) {
                $this->id = $id;
                $this->name = $name;
                $this->getInfo();    
                
                if (isset($_SESSION['userID']) && $_SESSION['userID'] == $this->id) {
                    $this->loggedin = true;
                }

                   $this->nextRank = $this->checkRank();

            }

            $__userCache[$id] = &$this;

        }    
        
        // This function will return all the information for the user
        public function getInfo($return = false) {
            
            if (!empty($this->name)) {
                $userInfo = $this->db->prepare("
                    SELECT 
                        *
                    FROM 
                        users 
                        LEFT OUTER JOIN userStats ON (U_id = US_id) 
                        LEFT OUTER JOIN userRoles ON (UR_id = U_userLevel)
                    WHERE 
                        U_name = :userName
                ");
                $userInfo->bindParam(':userName', $this->name);
            } else {
                $userInfo = $this->db->prepare("
                    SELECT 
                        *
                    FROM 
                        users 
                        LEFT OUTER JOIN userStats ON (U_id = US_id) 
                        LEFT OUTER JOIN userRoles ON (UR_id = U_userLevel)
                    WHERE 
                        U_id = :userID
                ");
                $userInfo->bindParam(':userID', $this->id);
            }
            
            $userInfo->execute();
            
            $this->info = $userInfo->fetchObject();

            $access = array();
            if (isset($this->info->U_userLevel)) {
                $adminModules = $this->db->prepare("
                    SELECT * FROM roleAccess WHERE RA_role = :id;
                ");
                $adminModules->bindParam(":id", $this->info->U_userLevel);
                $adminModules->execute();
                $adminModules = $adminModules->fetchAll(PDO::FETCH_ASSOC);

                foreach ($adminModules as $key => $value) {
                    $access[] = $value["RA_module"];
                }

            }

            $rank = $this->db->prepare("SELECT * FROM ranks WHERE R_id = :id");
            $rank->bindParam(":id", $this->info->US_rank);
            $rank->execute();
            $this->rank = (object) $rank->fetch(PDO::FETCH_ASSOC);

            $this->adminModules = $access;
            
            if (isset($user->info->U_name) || isset($user->info->U_id)) {
                $this->id = $this->info->U_id;
                $this->name = $this->info->U_name;
            }
	    $pic = "";
	    if (isset($this->info->US_pic)) $pic = $this->info->US_pic;
	    if (!$pic || str_replace("php", "", $pic) != $pic) {
		    $pic = "themes/default/images/default-profile-picture.png";
	    }

            if (isset($this->info->U_name)) {
                $this->user = array(
                    "name" => $this->info->U_name,
                    "id" => $this->info->U_id,
                    "userLevel" => $this->info->U_userLevel,
                    "status" => $this->info->U_status, 
                    "color" => $this->info->UR_color, 
                    "gang" => $this->info->US_gang, 
                    "profilePicture" => $pic, 
                    "onlineStatus" => $this->getStatus(false)
                );
            }
            
            if ($return) {
                return $this->info;
            }
            
        }
        
        public function hasAdminAccessTo($module) {
            return in_array($module, $this->adminModules) || in_array("*", $this->adminModules);
        }

        public function encrypt($var) {
            return hash('sha256', $var);
        }
        
        public function makeUser($username, $email, $password, $userLevel = 1, $userStatus = 1) {
            
            $settings = new settings();

            $check = $this->db->prepare("SELECT U_id FROM users WHERE U_name = :username OR (U_email = :email AND U_status = 1)");
            $check->bindParam(':username', $username);    
            $check->bindParam(':email', $email);    
            $check->execute();
            $checkInfo = $check->fetchObject();
            
            if (isset($checkInfo->U_id)) {
                
                return 'Username or EMail are in use!';
                
            } else {

                $validateUserEmail = !!$settings->loadSetting("validateUserEmail");
                
                if ($validateUserEmail) {
                    $userStatus = 2;
                }

                $addUser = $this->db->prepare("
                    INSERT INTO users (U_name, U_email, U_userLevel, U_status) 
                    VALUES (:username, :email, :userLevel, :userStatus)
                ");
                $addUser->bindParam(':username', $username);
                $addUser->bindParam(':email', $email);
                $addUser->bindParam(':userLevel', $userLevel);
                $addUser->bindParam(':userStatus', $userStatus);
                $addUser->execute();

                $id = $this->db->lastInsertId();
                $this->id = $id;
                
                $encryptedPassword = $this->encrypt($id . $password);

                $addUserPassword = $this->db->prepare("
                    UPDATE users SET U_password = :password WHERE U_id = :id
                ");
                $addUserPassword->bindParam(':id', $id);
                $addUserPassword->bindParam(':password', $encryptedPassword);
                $addUserPassword->execute();

                $this->db->query("INSERT INTO userStats (US_id) VALUES (" . $id . ")");

                $this->updateTimer("signup", time());

                if ($validateUserEmail) {
                    $this->sendActivationCode($email, $id, $username);
                }

                $hook = new Hook("newUser");
                $hook->run($id);
                
                return $id;
                
            }
            
        }
        
        public function sendActivationCode($email, $id, $username) {
            $settings = new settings();
            $gameName = $settings->loadSetting("game_name");
            $activationCode = $this->activationCode($id, $username);
            $subject = $gameName . " - Registration";
            $body = "$username your activation code for $gameName is $activationCode, after you have logged in please enter this when prompted.";
            mail($email, $subject, $body);
        }

        public function activationCode($id, $username) {
            return substr($this->encrypt($id . $username), 0, 6);
        }

        public function getNotificationCount($id, $type = 'all') {
                
            global $page;
            
            if ($type == 'all') {
                
                $notifications = $this->db->prepare("SELECT COUNT(M_id)+(SELECT COUNT(N_id) FROM notifications WHERE N_uid = :user1 AND N_read = 0) as count FROM mail WHERE M_uid = :user2 AND M_read = 0");
                $notifications->bindParam(':user1', $id);
                $notifications->bindParam(':user2', $id);
                $notifications->execute();
                $result = $notifications->fetchObject();
                
                $page->addToTemplate('all_notifications', $result->count); 
                return $result->count;
                
            } else if ($type == 'mail') {
                
                $notifications = $this->db->prepare("SELECT COUNT(M_id) as count FROM mail WHERE M_uid = :user1 AND M_read = 0");
                $notifications->bindParam(':user1', $id);
                $notifications->execute();
                $result = $notifications->fetchObject();
                
                $page->addToTemplate('mail', $result->count);
                
                return $result->count;
                
            } else if ($type == 'notifications') {
                
                $notifications = $this->db->prepare("SELECT COUNT(N_id) as count FROM notifications WHERE N_uid = :user1 AND N_read = 0");
                $notifications->bindParam(':user1', $id);
                $notifications->execute();
                $result = $notifications->fetchObject();
                
                $page->addToTemplate('notificationCount', $result->count);
                return $result->count;
                
            }

            return 0;
        }
        
        public function bindVarsToTemplate() {
            
            global $page;
            
            $this->getNotificationCount($this->info->U_id, 'mail'); 
            $this->getNotificationCount($this->info->U_id, 'notifications'); 

            $pic = (is_array(@getimagesize($this->info->US_pic))?$this->info->US_pic:"themes/default/images/default-profile-picture.png");

            $maxHealth = $this->rank->R_health;

            $health = ($maxHealth - $this->info->US_health) / $maxHealth * 100; 
            if ($health < 0) $health = 0;

            $page->addToTemplate('pic', $pic);
            $page->addToTemplate('money', '$'.number_format($this->info->US_money));
            $page->addToTemplate('bullets', number_format($this->info->US_bullets));
            $page->addToTemplate('backfire', number_format($this->info->US_backfire));
            $page->addToTemplate('points', $this->info->US_points);
            $page->addToTemplate('health', number_format($health, 2));
            $page->addToTemplate('location', $this->getLocation());
            $page->addToTemplate('username', $this->info->U_name);
            $page->addToTemplate('userStatus', $this->info->U_status);

            $page->addToTemplate('isAdmin', count($this->adminModules) != 0);
            
            $hook = new Hook("userInformation");

            $hook->run($this);
            
            $rank = $this->getRank();
            $gang = $this->getGang();
            $weapon = $this->getWeapon();
            $armor = $this->getArmor();

            
            $this->info->maxRank = true;
            if (isset($this->nextRank->R_exp)) {
                $expIntoNextRank =  $this->info->US_exp - $rank->R_exp;
                $expNeededForNextRank = $this->nextRank->R_exp - $rank->R_exp;
                $expperc = round($expIntoNextRank / $expNeededForNextRank * 100, 2);
                $this->info->maxRank = false;
            }
            
            $page->addToTemplate('maxRank', $this->info->maxRank);
            $page->addToTemplate('rank', $rank->R_name);
            @$page->addToTemplate('exp_perc', $expperc);
            $page->addToTemplate('gang', $gang);
            $page->addToTemplate('weapon', $weapon->I_name);
            $page->addToTemplate('armor', $armor->I_name);
            
        }
        
        public function getMoneyRank() {
            $query = $this->db->prepare("
                SELECT * FROM moneyRanks WHERE MR_money <= :money ORDER BY MR_money DESC LIMIT 0, 1
            ");
            $cash = $this->info->US_money + $this->info->US_bank;
            $query->bindParam(":money", $cash);
            $query->execute();
            $result = $query->fetchObject();
            return $result;
        }
        
        public function getRank() {
            
            $query = $this->db->prepare("SELECT * FROM ranks WHERE R_id = :rank");
            $query->bindParam(":rank", $this->info->US_rank);
            $query->execute();
            $result = $query->fetchObject();
            
            return $result;
            
        }
        
        public function getGang() {
            
            if (!$this->info->US_gang) {
                return array(
                    "id" => 0, 
                    "name" => "None"
                );
            }


            $query = $this->db->prepare("SELECT G_id as 'id', G_name as 'name' FROM gangs WHERE G_id = :gang");
            $query->bindParam(":gang", $this->info->US_gang);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            return $result;
            
        }
        
        public function getWeapon() {
            
            $query = $this->db->prepare("SELECT * FROM items WHERE I_id = :weapon");
            $query->bindParam(":weapon", $this->info->US_weapon);
            $query->execute();
            $result = $query->fetchObject();
            
            if (!$result) {
                return (object) array("I_name" => "None");
            } 

            return $result;
            
        }
        
        public function getArmor() {
            
            $query = $this->db->prepare("SELECT * FROM items WHERE I_id = :armor");
            $query->bindParam(":armor", $this->info->US_armor);
            $query->execute();
            $result = $query->fetchObject();
            
            if (!$result) {
                return (object) array("I_name" => "None");
            } 

            return $result;
            
        }
        
        public function set($stat, $value) {

            if ($stat[1] == "_") {
                $table = "users";
                $id = "U_id";
            } else {
                $table = "userStats";
                $id = "US_id";
            }

            $query = $this->db->prepare("UPDATE $table SET $stat = :value WHERE $id = :id");
            $query->bindParam(":id", $this->info->US_id);
            $query->bindParam(":value", $value);
            $query->execute();

            $this->info->$stat = $value;

        }
        
        public function checkRank() {
            
            global $page;
            $rank = $this->getRank();


            $nextRank = $this->db->prepare("SELECT * FROM ranks WHERE R_exp > :oldExp ORDER BY R_exp LIMIT 0, 1");
            $nextRank->bindParam(":oldExp", $rank->R_exp);
            $nextRank->execute();
            $newRank = (object) $nextRank->fetch(PDO::FETCH_ASSOC);

            if (isset($newRank->R_exp) && $newRank->R_exp <= $this->info->US_exp) {

                $this->db->query("
                    UPDATE userStats SET 
                        US_money = US_money + ".$newRank->R_cashReward.", 
                        US_bullets = US_bullets + ".$newRank->R_bulletReward.", 
                        US_rank = ".$newRank->R_id."
                    WHERE 
                        US_id = ".$this->info->US_id);

                $this->info->US_rank = $newRank->R_id;
                $this->info->US_bullets = $this->info->US_bullets + $newRank->R_bulletReward;
                $this->info->US_money = $this->info->US_money + $newRank->R_cashReward;

                $rewards = array();

                if ($newRank->R_bulletReward) $rewards[] = array( 
                    "name" => "Bullets",
                    "value" => number_format($newRank->R_bulletReward) 
                );
                
                if ($newRank->R_cashReward) $rewards[] = array( 
                    "name" => "Cash" ,
                    "value" => "$" . number_format($newRank->R_cashReward) 
                );

                $text = $page->buildElement("levelUpNotification", array(
                    "rankName" => $newRank->R_name,
                    "rewards" => $rewards
                ));

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->info->U_id, 
                    "module" => "rank", 
                    "id" => 0, 
                    "success" => true, 
                    "reward" => $newRank->R_id, 
                    "gt" => true
                );
                $actionHook->run($action);

                $hook = new Hook("rankUp");
                $info = array(
                    "user" => $this->info->U_id,
                    "rank" => $newRank->R_id
                );
                $hook->run($info);

                $this->newNotification($text);
                return $this->checkRank();
            } else {
                return $newRank;
            }

        }
        
        public function newNotification($text) {
            $notification = $this->db->prepare("
                INSERT INTO notifications (
                    N_uid, N_text, N_read, N_time
                ) VALUES (
                    :id, :text, 0, UNIX_TIMESTAMP()
                );
            ");
            $notification->bindParam(":id", $this->info->U_id);
            $notification->bindParam(":text", $text);
            $notification->execute();
        }

        public function getLocation() {
            
            $location = $this->db->prepare("SELECT L_name FROM locations WHERE L_id = :location");
            $location->bindParam(':location', $this->info->US_location);
            $location->execute();
            
            $return = $location->fetch(PDO::FETCH_ASSOC);
            
            return $return['L_name'];
        }
        
        public function checkTimer($timer) {
            $time = $this->getTimer($timer);
            return (time() > $time);
        }
        
        public function getTimer($timer) {
        
            $userID = $this->id;
            
            if (!$userID) $userID = $this->info->U_id;

            $select = $this->db->prepare("
                SELECT * FROM userTimers WHERE UT_desc = :desc AND UT_user = :user;
            ");
            $select->bindParam(':user', $userID);
            $select->bindParam(':desc', $timer);
            $select->execute();
            
            $array = $select->fetch(PDO::FETCH_ASSOC);
            
            // If the array is empty we make the user timer, this way the developer does not have to make any changes to the database to make a new timer.
            if (empty($array['UT_time'])) {
                
                $time = time()-1;
                $insert = $this->db->prepare("INSERT INTO userTimers (UT_user, UT_desc, UT_time) VALUES (:user, :desc, :time)");
                $insert->bindParam(':user', $userID);
                $insert->bindParam(':desc', $timer);
                $insert->bindParam(':time', $time);
                $insert->execute();
                return $time;
                
            } else {
                
                return $array['UT_time'];
                
            }
            
        }
        
        public function updateTimer($timer, $time, $add = false) {
        
            $user = $this->id;
            
            // Check that the timer exists, if it dosent this function will automaticly make it.
            // We do this so the user does not have to make any database changes to make a module.
            $oldTimer = $this->getTimer($timer);

            if ($add) {
                $time = time() + $time;
            }
            
            $update = $this->db->prepare("UPDATE userTimers SET UT_time = :time WHERE UT_user = :user AND UT_desc = :desc");
            $update->bindParam(':time', $time);
            $update->bindParam(':user', $user);
            $update->bindParam(':desc', $timer);
            $update->execute();
            
        }

        public function getStatus($returnElement = true) {
            
            $time =(time() - $this->getTimer("laston"));
            global $page;
            
            if ($time > 300 && $time <= 900) {
                if ($returnElement) {
                    return $page->buildElement("AFK", array());
                } else {
                    return 1;
                }
            } else if ($time > 900) {
                if ($returnElement) {
                    return $page->buildElement("offline", array());
                } else {
                    return 0;
                }
            } else {
                if ($returnElement) {
                    return $page->buildElement("online", array());
                } else {
                    return 2;
                }
            }
            
        }
        
        public function logout() {
        
            session_destroy();
            
        }
        
    }
    
?>
