<?php

	new hook("gangMenu", function ($user) {
		return array(
			"url" => "?page=gang", 
			"text" => "View Gangs", 
			"sort" => 100
		);
	});
?>