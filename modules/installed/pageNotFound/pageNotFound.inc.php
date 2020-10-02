<?php

    class pageNotFound extends module {

        public $allowedMethods = array();

        public $pageName = '';

        public function constructModule() {

        	if (!isset($this->user->info->U_id)) {
        		return $this->page->redirectTo("login");
        	}
            $this->html .= $this->page->buildElement("pageNotFound", array("errorTitle" => L::page_errors_something_went_wrong, "error"=> L::page_errors_page_not_found($_GET['page'])));
        }

    }

?>
