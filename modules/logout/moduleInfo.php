<?php
	$info  = array(
		"name" => "Logout", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Logout",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("accountMenu", function () {
		return array(
			"url" => "?page=logout", 
			"text" => "Logout", 
			"sort" => 1000
		);
	});
?>
