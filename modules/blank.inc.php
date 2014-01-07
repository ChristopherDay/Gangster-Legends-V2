<?php

    class blank extends module {
        
        public $allowedMethods = array();
        
        public function constructModule() {
            
            $this->buildHTML();
            
        }
        
    }

?>