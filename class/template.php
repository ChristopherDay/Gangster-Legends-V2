<?php

    class template {
    
        public $page, $dontRun = false;
        
        public function __construct($moduleName) {
        
            global $page, $user;
            
            $this->page = $page;
			


            $moduleInfo = $page->modules[$moduleName];

			if ($moduleName == "admin") {
				$this->loadMainPage('admin');
			} else if (!$moduleInfo["requireLogin"]) {
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
