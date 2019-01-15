<?php

    class profile extends module {
        	
        public $allowedMethods = array(
			'view'=>array('type'=>'get'),
			'old'=>array('type'=>'post'),
			'new'=>array('type'=>'post'),
			'confirm'=>array('type'=>'post'),
			'bio'=>array('type'=>'post'),
			'pic'=>array('type'=>'post'), 
			'submit'=>array('type'=>'post')
		);
    
		public $pageName = '';
        
        public function constructModule() {
			
			if (isset($this->methodData->view) && $this->methodData->view != $this->user->id) {
				$user = $this->methodData->view;
				if (ctype_digit($user)) {
					$profile = new user($user);
				} else {
					$profile = new user(false, $user);
				}

				$this->pageName = 'Viewing '.$profile->info->U_name.'\'s Profile';
				$edit = false;
			} else {
				$profile = $this->user;
				$this->pageName = 'My Profile';
				$edit = true;
			}
			
			$bio =  ((strlen($profile->info->US_bio)>0)?($profile->info->US_bio):false);
			
			// Make sure it is an image
			$pic = (is_array(@getimagesize($profile->info->US_pic))?$profile->info->US_pic:"themes/default/images/default-profile-picture.png");

            $this->html .= $this->page->buildElement('profile', array(
				"picture" => $pic,
				"user" => $profile->user, 
				"moneyRank" => $profile->getMoneyRank()->MR_desc, 
				"rank" => $profile->getRank()->R_name, 
				"gangID" => $profile->getGang()["id"], 
				"gang" => $profile->getGang()["name"], 
				"dead" => $profile->info->U_status == 0, 
				"status" => $profile->getStatus(), 
				"bio" => $bio, 
				"role" => $profile->info->UR_desc,
				"showRole" => $profile->info->UR_id != 1,
				"edit" => $edit
			));
            
        }
		
		public function method_password() {
			$this->construct = false;

			if (!empty($this->methodData->submit)) {

				if (strlen($this->methodData->new) < 6) {
					$this->alerts[] = $this->page->buildElement("error", array(
						"text" => "The password you entered is too short, it must be atleast 6 characters."
					));
				} else if ($this->methodData->new != $this->methodData->confirm) {
					$this->alerts[] = $this->page->buildElement("error", array(
						"text" => "The passwords you entered do not match"
					));
				} else {
					$encrypt = $this->user->encrypt($this->user->info->U_id . $this->methodData->old);
					if ($encrypt != $this->user->info->U_password) {
						$this->alerts[] = $this->page->buildElement("error", array(
							"text" => "The password you entered is incorrect"
						));
					} else {
						$new = $this->user->encrypt($this->user->info->U_id . $this->methodData->new);

						$update = $this->db->prepare("
							UPDATE users SET U_password = :password WHERE U_id = :id
						");
						$update->bindParam(":password", $new);
						$update->bindParam(":id", $this->user->info->US_id);
						$update->execute();
						$this->alerts[] = $this->page->buildElement("success", array(
							"text" => "Your password has been updated"
						));
					}
				}
			}

			$this->html .= $this->page->buildElement("editPassword");
		}
		
		public function method_edit() {
			
			if (!empty($this->methodData->submit)) {
				
				$update = $this->db->prepare("
					UPDATE userStats SET US_bio = :bio, US_pic = :pic WHERE US_id = :id
				");
				$update->bindParam(":bio", $this->methodData->bio);
				$update->bindParam(":pic", $this->methodData->pic);
				$update->bindParam(":id", $this->user->info->US_id);
				$update->execute();
				
				$this->alerts[] = $this->page->buildElement("success", array("text" => 'Profile Updated, <a href="?page=profile">View</a>.'));
				
				$this->user->info->US_bio = $this->methodData->bio;
				$this->user->info->US_pic = $this->methodData->pic;
			
			}
			
			$this->construct = false;
			
			$this->pageName = 'Edit Profile';
		
			$this->html .= $this->page->buildElement("editProfile", array(
				"bio" => $this->user->info->US_bio, 
				"picture" => $this->user->info->US_pic
			));
			
		}
        
    }

?>