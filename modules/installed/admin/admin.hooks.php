<?php

	new hook("customMenus", function ($user) {
		if ($user && $user->info->U_userLevel == 2) {
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