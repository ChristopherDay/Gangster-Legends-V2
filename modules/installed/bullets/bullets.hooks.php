<?php
    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("bullets");
        if (($time-time()) > 0) {
            $page->addToTemplate('bullet_timer', $time);
        } else {
            $page->addToTemplate('bullet_timer', 0);
        }
            
    });

    new hook("locationMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=bullets", 
            "timer" => $user->getTimer("bullets"),
            "templateTimer" => "bullet_timer",
            "sort" => 999,
            "text" => "Bullet Factory"
        );
    });
