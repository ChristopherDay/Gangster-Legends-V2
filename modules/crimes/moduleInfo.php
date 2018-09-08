<?php
	$info = array(
		"name" => "Crimes", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to comit crimes",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Crimes",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=crimes", 
			"text" => "Crimes", 
			"sort" => 100
		);
	});
?>