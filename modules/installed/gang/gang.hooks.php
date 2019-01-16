<?php

	new hook("gangMenu", function ($user) {
		return array(
			"url" => "?page=gang", 
			"text" => "View Gangs", 
			"sort" => 100
		);
	});

	new hook("gangMenu", function ($user) {
		if ($user->info->US_gang) return array(
			"url" => "?page=gang&action=home", 
			"text" => "My Gang", 
			"sort" => 100
		);
	});
?>