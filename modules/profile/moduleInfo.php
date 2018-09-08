<?php
	$info  = array(
		"name" => "Profile", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "View Profile",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("accountMenu", function () {
		return array(
			"url" => "?page=profile", 
			"text" => "My Profile", 
			"sort" => 900
		);
	});
?>
