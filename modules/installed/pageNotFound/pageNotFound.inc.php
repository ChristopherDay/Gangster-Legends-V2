<?php

    class pageNotFound extends module {
        
        public $allowedMethods = array();
        
        public $pageName = '';
        
        public function constructModule() {

        	if (!isset($this->user->info->U_id)) {
        		return $this->page->redirectTo("login");
        	}

            $this->html .= $this->page->buildElement("pageNotFound");
        }
        
    }

