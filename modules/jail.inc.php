<?php

    class jail extends module {
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('error', array('You are in jail for '.$this->timeLeft(($this->user->info->US_jailTimer - time())).'!'));
            
        }
        
    }

?>