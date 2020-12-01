<?php

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=profile", 
            "text" => "My Profile", 
            "sort" => 900
        );
    });
    new hook("profileLink", function ($profile) {
    	global $user;
    	if ($user->id == $profile->info->U_id) {
	        return array(
	            "url" => "?page=profile&action=edit", 
	            "text" => "Edit Profile"
	        );
    	}
    });
