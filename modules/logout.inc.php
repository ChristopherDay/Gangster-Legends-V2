<?php

    class logout extends module {
        
        public function constructModule() {
            
            $this->user->logout();
            
            $this->page->redirectTo('login', array('logout'=>'true'));
            
        }
        
    }

?>