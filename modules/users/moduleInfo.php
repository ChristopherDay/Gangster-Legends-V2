<?php
	$info = array(
		"name" => "Users", 
		"version" => "1.0.0", 
		"description" => "This module allows a admin to edit users",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "User Administration",
		"accessInJail" => false, 
		"requireLogin" => true, 
		"adminGroup" => "User Management",
		"admin" => array(
			array(
				"text" => "Find User", 
				"method" => "view",
			),
			array(
				"hide" => true,
				"text" => "Edit User", 
				"method" => "edit",
			),
			array(
				"hide" => true,
				"text" => "Delete User", 
				"method" => "delete",
			)
		)
	);

?>