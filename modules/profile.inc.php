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
			
				$edit = '';
				
			} else {
			
				$profile = $this->user;
				
				$this->pageName = 'My Profile';
				
				$edit = $this->page->buildElement("edit", array());
			
				
			}
			
			$bio =  ((strlen($profile->info->US_bio)>0)?nl2br($profile->info->US_bio):'<em><small>The user has not set up there bio yet!</small></em>');
			
			$pic = (is_array(@getimagesize($profile->info->US_pic))?$profile->info->US_pic:"http://home/MobGame/template/default/images/default-profile-picture.png");
			
            $this->html .= $this->page->buildElement('profile', array(
				$pic,
				$profile->info->U_name, 
				$profile->getRank()->R_name, 
				$profile->getGang()->G_name, 
				$profile->getStatus(), 
				$bio
			));
			
			$this->html .= $edit;
            
        }
		
		public function method_edit() {
			
			if (!empty($this->methodData->submit)) {
				
				$update = $this->db->prepare("UPDATE userStats SET US_bio = :bio, US_pic = :pic WHERE US_id = :id");
				$update->bindParam(":bio", $this->methodData->bio);
				$update->bindParam(":pic", $this->methodData->pic);
				$update->bindParam(":id", $this->user->info->US_id);
				$update->execute();
				
				$this->html .= $this->page->buildElement("success", array('Profile Updated, <a href="?page=profile">View</a>.'));
				
				$this->user->info->US_bio = $this->methodData->bio;
				$this->user->info->US_pic - $this->methodData->pic;
			
			}
			
			$this->construct = false;
			
			$this->pageName = 'Edit Profile';
		
			$this->html .= $this->page->buildElement("editProfile", array(
				$this->user->info->US_bio, 
				$this->user->info->US_pic
			));
			
		}
        
    }

?>