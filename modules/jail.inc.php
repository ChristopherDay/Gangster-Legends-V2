<?php

    class jail extends module {
        
        public $allowedMethods = array();
		
		public $pageName = 'Jail';
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('error', array('You are in jail for <span data-timer-type="inline" data-timer="'.($this->user->getTimer("jail") - time()).'"></span>!'));
            
        }
        
    }

?>