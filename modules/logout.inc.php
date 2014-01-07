<?php

    class logout extends module {
        
        public $allowedMethods = array();
        
        public function constructModule() {
            
            $this->user->logout();
            
            $this->page->redirectTo('login', array('action'=>'logout'));
            
        }
        
    }

?>