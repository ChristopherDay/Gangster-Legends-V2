<?php

    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("theft");
        if (($time-time()) > 0) {
            $page->addToTemplate('theft_timer', $time);
        } else {
            $page->addToTemplate('theft_timer', 0);
        }
    });
    new hook("actionMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=theft", 
            "text" => "Car Theft", 
            "timer" => $user->getTimer("theft"),
            "sort" => 200
        );
    });
?>
