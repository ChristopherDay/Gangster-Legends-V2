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

    new hook("locationMenu", function () {
        return array(
            "url" => "?page=bullets", 
            "text" => "Bullet Factory"
        );
    });
?>