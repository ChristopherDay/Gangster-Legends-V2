<?php

    class logout extends module {
        
        public $allowedMethods = array();
        
        public function constructModule() {
     
            $actionHook = new hook("userAction");
            $action = array(
                "user" => $this->user->id, 
                "module" => "logout", 
                "id" => $this->user->id, 
                "success" => true, 
                "reward" => 0
            );
            $actionHook->run($action);
            
            $this->user->logout();
            
            $this->page->redirectTo('login', array('action'=>'logout'));
            
        }
        
    }

