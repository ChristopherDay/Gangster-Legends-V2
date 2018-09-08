<?php
	$info = array(
		"name" => "Loggedin", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Game News",
		"accessInJail" => true, 
		"requireLogin" => true
	);

	new hook("accountMenu", function () {
		return array(
			"url" => "?page=loggedin", 
			"text" => "Game News"
		);
	});
?>
