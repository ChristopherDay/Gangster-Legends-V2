<?php
	$info  = array(
		"name" => "Police Chase", 
		"version" => "1.0.0", 
		"description" => "",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Police Chase",
		"accessInJail" => false, 
		"requireLogin" => true
	);

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=policeChase", 
			"text" => "Police Chase", 
			"sort" => 300
		);
	});
?>
