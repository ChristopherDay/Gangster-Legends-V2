<?php
	$info  = array(
		"name" => "Travel", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Travel",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("locationMenu", function () {
		return array(
			"url" => "?page=travel", 
			"text" => "Travel"
		);
	});
?>
