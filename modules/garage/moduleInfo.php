<?php
	$info = array(
		"name" => "Garage", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to buy bullets",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Garage",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("locationMenu", function () {
		return array(
			"url" => "?page=garage", 
			"text" => "Garage"
		);
	});
?>