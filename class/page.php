<?php

	class page {
		
		public $loginPages = array('login', 'register'), $theme, $template, $jailPages = array('jail', 'loggedin', 'logout');
		private $pageHTML, $pageFind, $pageReplace;
	
		public function loadPage($page) {
            
            global $user;

			if (ctype_alpha($page)) {
                
                    $this->load($page);
                
            } else {
                
                die("Invalid page name");
                
            }
				
		}	
        
        private function load($page) {
            
            if (file_exists('modules/'.$page.'.inc.php')) {
                
                if (file_exists('template/modules/'.$page.'.php')) {
                    
                    include 'class/template.php';
                
                    include 'template/modules/'.$page.'.php';
                    
                    $templateMethod = $page.'Template';
                    
                    $this->template = new $templateMethod();
                    
                    include 'class/module.php';
                    include 'modules/'.$page.'.inc.php';
                    
                    $module = new $page();
                    
                    if (isset($module)) {
                    
                        $this->addToTemplate('game', $module->htmlOutput());
                        
                    }
                    
                    $pageName = $page;
                    
                    if (isset($module->pageName)) {
                    
                        $pageName = $module->pageName;
                        
                    }
                    
                    $this->addToTemplate('page', $pageName);
                    
                    $this->pageHTML = $this->template->mainTemplate->pageMain;
                
                } else {
                    
                    die("Module template not found!".'template/modules/'.$page.'.php');
                
                }
                
            } else {
            
                die("404 The page $page was not found!");
                    
            }   
        
        }
		
		public function addToTemplate($find, $replace) {
			
			$this->pageFind[] = '{'.$find.'}';
			$this->pageReplace[] = $replace;
				
		}
		
		private function replaceVars() {
			
			$this->pageHTML = str_replace($this->pageFind, $this->pageReplace, $this->pageHTML);
				
		}
		
		public function printPage() {
		
			$this->replaceVars();
			
			echo $this->pageHTML;
			
		}
        
        public function buildElement($element, $vars) {
            
            $find = array();
            $i = 1;
            
            foreach ($vars as $vars2) {
            
                $find[] = '{var'.$i.'}';
                $i++;
                
            }
            
            return str_replace($find, $vars, $this->template->$element);
        
        }
        
        public function redirectTo($page, $vars = array()) {
            
            $get = '';
            
            foreach($vars as $key => $val) {
                $get .= '&'.$key.'='.$val;    
            }
            
            $redirect = '?page='.$page.''; 
            
            header("Location:".$redirect.$get);
            
            exit;
            
        }
		
	}
	
	$page = new page();
	
?>