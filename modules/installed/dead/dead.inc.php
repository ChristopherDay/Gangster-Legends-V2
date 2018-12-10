<?php

    class dead extends module {
        
        public $allowedMethods = array();
		
		public $pageName = '';
        
        public function constructModule() {
            $this->alerts[] = $this->page->buildElement("error", array("text" => "You have been shot and killed!"));

            $killer = new User($this->user->info->US_shotBy);

            $this->html .= $this->page->buildElement("newAccount", array(
            	"user" => $killer->user
            ));
        }
        
    }

?>