<?php

    new hook("customMenus", function ($user) {

        global $page;

        if ($user && count($user->adminModules)) {
            $items = array(
                array(
                    "url" => "?page=admin", 
                    "text" => "Admin"
                )
            );

            if (isset($page->loadedModule["admin"]) && $user->hasAdminAccessTo($page->loadedModule["id"])) {
                foreach ($page->loadedModule["admin"] as $k => $v) {
                    if (isset($v["hide"])) continue; 
                    $items[] = array(
                        "url" => "?page=admin&module=" . $page->loadedModule["id"] . "&action=" . $v["method"], 
                        "text" => $v["text"]
                    );
                }

            }

            return array(
                "title" => "Admin", 
                "items" => $items,
                "sort" => 1000
            );
        } else {
            return false;
        }
    });