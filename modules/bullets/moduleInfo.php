<?php
	$info = array(
		"name" => "Bullets", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to buy bullets",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Bullet Factory",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("locationMenu", function () {
		return array(
			"url" => "?page=bullets", 
			"text" => "Bullet Factory"
		);
	});
?>