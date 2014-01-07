<?php

	class user {
		
		public $id, $info, $name;
		
		// Pass the ID to the class
		function __construct($id = FALSE, $name = FALSE) {
			if (isset($id) || isset($name)) {
				$this->id = $id;
				$this->name = $name;
				$this->getInfo();	
				
				if (@$this->info->U_id == $_SESSION['userID']) {
					while ($this->checkRank()){}
				}
			}
		}	
		
		// This function will return all the information for the user
		public function getInfo($return = false) {
			
			global $db;
			
			if (!empty($this->name)) {
				$userInfo = $db->prepare("SELECT * FROM users LEFT OUTER JOIN userStats ON (U_id = US_id) WHERE U_name = :userName");
				$userInfo->bindParam(':userName', $this->name);
			} else {
				$userInfo = $db->prepare("SELECT * FROM users LEFT OUTER JOIN userStats ON (U_id = US_id) WHERE U_id = :userID");
				$userInfo->bindParam(':userID', $this->id);
			}
			
			$userInfo->execute();
            
			$this->info = $userInfo->fetchObject();
            
            if (isset($user->info->U_name) || isset($user->info->U_id)) {
                $this->id = $this->info->U_id;
                $this->name = $this->info->U_name;
            }
			
            if ($return) {
				return $this->info;
			}
			
			
		}
		
		public function encrypt($var) {
			
			return sha1($var);
				
		}
		
		public function makeUser($username, $email, $password, $userLevel = 1, $userStatus = 1) {
			
			global $db;
			
			$check = $db->prepare("SELECT U_id FROM users WHERE U_name = :username OR (U_email = :email AND U_status = 1)");
			$check->bindParam(':username', $username);	
			$check->bindParam(':email', $email);	
			$check->execute();
			$checkInfo = $check->fetchObject();
			
			if (isset($checkInfo->U_id)) {
				
				return 'Username or EMail are in use!';
				
			} else {
				
				$addUser = $db->prepare("INSERT INTO users (U_name, U_email, U_password, U_userLevel, U_status) 
							VALUES (:username, :email, :password, :userLevel, :userStatus)");
				$addUser->bindParam(':username', $username);
				$addUser->bindParam(':email', $email);
				$addUser->bindParam(':password', $this->encrypt($password));
				$addUser->bindParam(':userLevel', $userLevel);
				$addUser->bindParam(':userStatus', $userStatus);
				$addUser->execute();
				
				$db->query("INSERT INTO userStats (US_id) VALUES (".$db->lastInsertId().")");
				
				return 'success';
				
			}
			
		}
		
		public function getNotificationCount($id, $type = 'all') {
				
			global $db, $page;
			
			if ($type == 'all') {
				
				$notifications = $db->prepare("SELECT COUNT(M_id)+(SELECT COUNT(N_id) FROM notifications WHERE N_uid = :user1 AND N_read = 0) as count FROM mail WHERE M_uid = :user2 AND M_read = 0");
				$notifications->bindParam(':user1', $id);
				$notifications->bindParam(':user2', $id);
				$notifications->execute();
				$result = $notifications->fetchObject();
				
				if ($result->count == 0) {
					$page->addToTemplate('all_notifications', ' '); 
				} else {
					$page->addToTemplate('all_notifications', ' ('.$result->count.')'); 
				}
				return $result->count;
				
			} else if ($type == 'mail') {
				
				$notifications = $db->prepare("SELECT COUNT(M_id) as count FROM mail WHERE M_uid = :user1 AND M_read = 0");
				$notifications->bindParam(':user1', $id);
				$notifications->execute();
				$result = $notifications->fetchObject();
				
				if ($result->count == 0) {
					$page->addToTemplate('mail', ' '); 
					$page->addToTemplate('mail_class', ' '); 
				} else {
					$page->addToTemplate('mail', ' ('.$result->count.')'); 
					$page->addToTemplate('mail_class', 'new'); 
				}
				
				return $result->count;
				
			} else if ($type == 'notifications') {
				
				$notifications = $db->prepare("SELECT COUNT(N_id) as count FROM notifications WHERE N_uid = :user1 AND N_read = 0");
				$notifications->bindParam(':user1', $id);
				$notifications->execute();
				$result = $notifications->fetchObject();
				
				if ($result->count == 0) {
					$page->addToTemplate('notifications', ' '); 
					$page->addToTemplate('notifications_class', ' '); 
				} else {
					$page->addToTemplate('notifications', ' ('.$result->count.')'); 
					$page->addToTemplate('notifications_class', 'new'); 
				}
				
				return $result->count;
				
			}
		}
		
		public function bindVarsToTemplate() {
			
			global $page;
			$this->getNotificationCount($this->info->U_id); 
			$this->getNotificationCount($this->info->U_id, 'mail'); 
			$this->getNotificationCount($this->info->U_id, 'notifications'); 
			$page->addToTemplate('money', '$'.number_format($this->info->US_money));
			$page->addToTemplate('bullets', number_format($this->info->US_bullets));
			$page->addToTemplate('backfire', number_format($this->info->US_backfire));
			$page->addToTemplate('credits', $this->info->US_credits);
			$page->addToTemplate('health', $this->info->US_health.'%');
			$page->addToTemplate('location', $this->getLocation());
			
			$rank = $this->getRank();
			$gang = $this->getGang();
			$weapon = $this->getWeapon();
			
			$expperc = round(
				( 
					(
						@$this->info->US_exp/$rank->R_exp 
					)*100 
				)
			, 2);
			
			$page->addToTemplate('rank', $rank->R_name);
			@$page->addToTemplate('exp_perc', '('.$expperc.'%)');
			$page->addToTemplate('gang', $gang->G_name);
			$page->addToTemplate('weapon', $weapon->W_name);
			
		}
		
		public function getRank() {
		
			global $db;
			
			$query = $db->prepare("SELECT * FROM ranks WHERE R_id = :rank");
			$query->bindParam(":rank", $this->info->US_rank);
			$query->execute();
			$result = $query->fetchObject();
			
			return $result;
			
		}
		
		public function getGang() {
		
			global $db;
			
			$query = $db->prepare("SELECT * FROM gangs WHERE G_id = :gang");
			$query->bindParam(":gang", $this->info->US_gang);
			$query->execute();
			$result = $query->fetchObject();
			
			return $result;
			
		}
		
		public function getWeapon() {
		
			global $db;
			
			$query = $db->prepare("SELECT * FROM weapons WHERE W_id = :weapon");
			$query->bindParam(":weapon", $this->info->US_weapon);
			$query->execute();
			$result = $query->fetchObject();
			
			return $result;
			
		}
		
		public function checkRank() {
			
			global $db;
			
			$rank = $this->getRank();
			
			if ($rank->R_exp < $this->info->US_exp || $rank->R_exp == $this->info->US_exp) {
				
				$db->query("UPDATE userStats SET US_money = US_money + ".$rank->R_cashReward.", US_bullets = US_bullets + ".$rank->R_bulletReward.", US_rank = US_rank + 1, US_exp = ".($this->info->US_exp - $rank->R_exp)." WHERE US_id = ".$this->info->US_id);
				
				$this->info->US_exp = ($this->info->US_exp - $rank->R_exp);
				$this->info->US_rank++;
				$this->info->US_bullets = $this->info->US_bullets + $rank->R_bulletReward;
				$this->info->US_money = $this->info->US_money + $rank->R_cashReward;
				
				return true;
				
			} else {
			
				return false;
				
			}
		
		}
        
        public function getLocation() {
        
            global $db;
            
            $location = $db->prepare("SELECT L_name FROM locations WHERE L_id = ?");
            $location->execute(array($this->info->US_location));
            $return = $location->fetch(PDO::FETCH_ASSOC);
            
            return $return['L_name'];
        }
        
        public function checkTimer($timer, $returnTime = false) {
        
            $timer = 'US_'.$timer.'Timer';
            
            if (isset($this->info->$timer)) {
                
                $time = $this->info->$timer;
                
                if ($returnTime) {
                    if ($time > time()) {
                        return $time - time();
                    } else {
                        return 0;
                    }
                } else {
                    
                    if ($time < time()) {
                    
                        return true;
                        
                    } else {
                    
                        return false;
                        
                    }
                    
                }
            
            } else {
                
                return 'Timer does not exists!';
                
            }
        
        }
        
        public function logout() {
        
            session_destroy();
            
        }
		
	}
	
?>