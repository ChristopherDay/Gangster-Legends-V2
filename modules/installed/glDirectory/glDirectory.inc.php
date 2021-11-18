<?php

/**
* This module allows users to vote for your site on the GL Directory
*
* @package GL Directory
* @author Chris Day
* @version 1.0.0
*/


class glDirectory extends module {

	public $allowedMethods = array(
		"top10"=>array("type"=>"GET")
	);

	public function constructModule() {

        $settings = new settings();

		$this->html .= $this->page->buildElement("glDirectory", array(
            "voteUrl" => $settings->loadSetting("voteUrl"),
            "id" => $this->user->id, 
            "key" => hash("sha256", gmdate("Y-m-d") . $this->user->id . $settings->loadSetting("voteKey1"))
        ));
	}

	public function method_test() {
		
	}

}
