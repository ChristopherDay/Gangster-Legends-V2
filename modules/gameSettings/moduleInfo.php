<?php
	$info = array(
		"name" => "Game Settings", 
		"version" => "1.0.0", 
		"description" => "This module allows a admin to modify core game settings",
		"author" => array(
			"name" => "Chris Day", 
			"url" => "http://glscript.cdcoding.com"
		), 
		"pageName" => "Game Settings",
		"accessInJail" => false, 
		"requireLogin" => true, 
		"adminGroup" => "Game Mechanics",
		"admin" => array(
			array(
				"text" => "View Ranks", 
				"method" => "viewRank",
			),
			array(
				"text" => "New Rank", 
				"method" => "newRank",
			),
			array(
				"hide" => true,
				"text" => "Edit Rank", 
				"method" => "editRank",
			),
			array(
				"hide" => true,
				"text" => "Delete Rank", 
				"method" => "deleteRank",
			)
		)
	);

?>