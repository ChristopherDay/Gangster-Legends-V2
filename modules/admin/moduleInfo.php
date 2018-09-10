<?php
	$info = array(
		"name" => "Admin", 
		"version" => "1.0.0", 
		"description" => "This module allows a user to deposit and withdraw money from a bank",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Admin",
		"accessInJail" => true, 
		"requireLogin" => true
	);


	new hook("customMenus", function ($user) {
		if ($user && $user->info->U_userLevel != 1) {
			return array(
				"title" => "Admin", 
	            "items" => array(
					array(
						"url" => "?page=admin", 
						"text" => "Admin"
					)
				),
				"sort" => 1000
			);
		} else {
			return false;
		}
	});
?>