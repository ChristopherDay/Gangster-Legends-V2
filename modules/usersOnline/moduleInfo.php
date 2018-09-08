<?php
	$info  = array(
		"name" => "Users Online", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Users Online",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("accountMenu", function () {
		return array(
			"url" => "?page=usersOnline", 
			"text" => "Users Online"
		);
	});
?>
