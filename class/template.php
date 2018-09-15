<?php

    class template {
    
        public $page, $dontRun = false;
        
        public function __construct($moduleName) {
        
            global $page, $user;
            
            $this->page = $page;
			


            $moduleInfo = $page->modules[$moduleName];

            $this->page->loadedTheme = $this->page->theme;

            if ($moduleName == "admin") {
                $this->page->loadedTheme = $this->page->adminTheme;
                $this->loadMainPage();
            } else if (!$moduleInfo["requireLogin"]) {
                $this->loadMainPage('login');
            } else {
                $this->loadMainPage('loggedin');
            }
        
        }
        
        private function loadMainPage($pageType = "index") {
        
            if (file_exists('themes/'.$this->page->loadedTheme.'/'.$pageType.'.php')) {
            
                include 'themes/'.$this->page->loadedTheme.'/'.$pageType.'.php';
                
                $this->mainTemplate = new mainTemplate();
                
            } else {
            
                die("Main template '".$this->page->loadedTheme."' file not found!");
                
            }
            
        }
        
        /* Global elements */
        
        public $success = '<div class="alert alert-success">{text}</div>';
        public $error = '<div class="alert alert-danger">{text}</div>';
        public $info = '<div class="alert alert-info">{text}</div>';
        public $warning = '<div class="alert alert-warning">{text}</div>';
        
    }

?>
