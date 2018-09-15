<?php
	$info = array(
		"name" => "Jail", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to see jail inmates",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Jail",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("locationMenu", function () {
		return array(
			"url" => "?page=jail", 
			"text" => "Jail"
		);
	});
?>