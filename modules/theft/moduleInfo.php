<?php
	$info  = array(
		"name" => "Theft", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Car Theft",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=theft", 
			"text" => "Car Theft", 
			"sort" => 200
		);
	});
?>
