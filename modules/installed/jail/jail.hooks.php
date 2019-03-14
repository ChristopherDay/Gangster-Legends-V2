<?php

    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("jail");
        if (($time-time()) > 0) {
            $page->addToTemplate('jail_timer', $time);
        } else {
            $page->addToTemplate('jail_timer', 0);
        }
    });        
            
    new hook("locationMenu", function () {
        return array(
            "url" => "?page=jail", 
            "text" => "Jail"
        );
    });
?>