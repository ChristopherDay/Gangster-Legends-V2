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

    new hook("locationMenu", function () {
        return array(
            "url" => "?page=travel", 
            "text" => "Travel"
        );
    });
?>
