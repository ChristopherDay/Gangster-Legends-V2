<?php

    class template {
    
        public $page;
        
        public function __construct() {
        
            global $page, $user;
            
            $this->page = $page;
			
			if (isset($this->adminPage) && $this->adminPage && $user->info->U_userLevel > 1) {
				
				$this->loadMainPage('admin');
			
			} else if ($this->loginPage) {
                
                $this->loadMainPage('login');
                
            } else {
                
                $this->loadMainPage('loggedin');
                
            }
        
        }
        
        private function loadMainPage($type) {
        
            if (file_exists('template/'.$this->page->theme.'/'.$type.'.php')) {
            
                include 'template/'.$this->page->theme.'/'.$type.'.php';
                
                $this->mainTemplate = new mainTemplate();
                
            } else {
            
                die('Main template file not found!'.$this->page->theme);
                
            }
            
        }
        
        /* Global elements */
        
        public $success = '<div class="alert alert-success">{text}</div>';
        public $error = '<div class="alert alert-danger">{text}</div>';
        public $info = '<div class="alert alert-info">{text}</div>';
        public $warning = '<div class="alert alert-warning">{text}</div>';
        
    }

?>
