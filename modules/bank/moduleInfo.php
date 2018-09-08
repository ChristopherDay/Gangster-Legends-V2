<?php
	$info = array(
		"name" => "Bank", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to deposit and withdraw money from a bank",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Bank",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("locationMenu", function () {
		return array(
			"url" => "?page=bank", 
			"text" => "Bank"
		);
	});
?>