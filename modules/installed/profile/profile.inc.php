<?php

    class profile extends module {
        	
        public $allowedMethods = array(
			'view'=>array('type'=>'get'),
			'bio'=>array('type'=>'post'),
			'pic'=>array('type'=>'post'), 
			'submit'=>array('type'=>'post')
		);
    
		
		
		public $pageName = '';
        
        public function constructModule() {
			
			if (isset($this->methodData->view) && $this->methodData->view != $this->user->id) {
				
				$profile = new user($this->methodData->view);
				
				$this->pageName = 'Viewing '.$profile->info->U_name.'\'s Profile';
			
				$edit = false;
				
			} else {
			
				$profile = $this->user;
				
				$this->pageName = 'My Profile';
				
				$edit = true;
			
				
			}
			
			$bio =  ((strlen($profile->info->US_bio)>0)?nl2br($profile->info->US_bio):'<em><small>The user has not set up there bio yet!</small></em>');
			
			// Make sure it is an image
			$pic = (is_array(@getimagesize($profile->info->US_pic))?$profile->info->US_pic:"themes/default/images/default-profile-picture.png");
			
            $this->html .= $this->page->buildElement('profile', array(
				"picture" => $pic,
				"name" => $profile->info->U_name, 
				"rank" => $profile->getRank()->R_name, 
				"family" => $profile->getGang()["name"], 
				"status" => $profile->getStatus(), 
				"bio" => $bio, 
				"edit" => $edit
			));
            
        }
		
		public function method_edit() {
			
			if (!empty($this->methodData->submit)) {
				
				$update = $this->db->prepare("UPDATE userStats SET US_bio = :bio, US_pic = :pic WHERE US_id = :id");
				$update->bindParam(":bio", $this->methodData->bio);
				$update->bindParam(":pic", $this->methodData->pic);
				$update->bindParam(":id", $this->user->info->US_id);
				$update->execute();
				
				$this->html .= $this->page->buildElement("success", array("text" => 'Profile Updated, <a href="?page=profile">View</a>.'));
				
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