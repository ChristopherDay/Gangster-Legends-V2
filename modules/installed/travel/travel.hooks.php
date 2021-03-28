<?php

    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("travel");
        if (($time-time()) > 0) {
            $page->addToTemplate('travel_timer', $time);
        } else {
            $page->addToTemplate('travel_timer', 0);
        }
    });

    new hook("locationMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=travel",
            "sort" => 1000, 
            "timer" => $user->getTimer("travel"),
            "templateTimer" => "travel_timer",
            "text" => "Travel"
        );
    });
