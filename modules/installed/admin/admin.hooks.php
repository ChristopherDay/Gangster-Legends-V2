<?php

    new hook("customMenus", function ($user) {
        global $page;

        if (isset($_GET["page"]) && $_GET["page"] == "admin" && isset($_GET["module"])) {
            $page->addToTemplate("adminModule", $_GET["module"]);
        }

        if ($user && count($user->adminModules)) {
            $items = array(
                array(
                    "url" => "?page=admin", 
                    "notAjax" => true,
                    "text" => "Admin"
                )
            );

            if (isset($page->loadedModule["admin"]) && $user->hasAdminAccessTo($page->loadedModule["id"])) {
                foreach ($page->loadedModule["admin"] as $k => $v) {
                    if (isset($v["hide"])) continue; 
                    $items[] = array(
                        "url" => "?page=admin&module=" . $page->loadedModule["id"] . "&action=" . $v["method"], 
                        "notAjax" => true,
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