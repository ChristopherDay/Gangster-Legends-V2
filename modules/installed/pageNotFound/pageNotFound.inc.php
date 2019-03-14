<?php

    class pageNotFound extends module {
        
        public $allowedMethods = array();
        
        public $pageName = '';
        
        public function constructModule() {
            $this->html .= $this->page->buildElement("pageNotFound");
        }
        
    }

?>