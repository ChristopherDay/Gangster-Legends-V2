<?php

    new hook("customMenus", function ($user) {
        if ($user && count($user->adminModules)) {
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