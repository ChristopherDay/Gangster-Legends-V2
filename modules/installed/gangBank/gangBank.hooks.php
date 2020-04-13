<?php

    new hook("gangMenu", function ($user) {
        if ($user && $user->info->US_gang) {
            $s = new Settings();
            $name = $s->loadSetting("gangName");
            return array(
                "sort" => 30,
                "url" => "?page=gangBank", 
                "text" => $name . " Bank"
            );
        }
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Withdraw Cash", 
            "description" => "This gives this ".$name." member the ability to withdraw cash from the ".$name." bank", 
            "key" => "withdrawCash"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Withdraw Bullets", 
            "description" => "This gives this ".$name." member the ability to withdraw bullets from the ".$name." bank", 
            "key" => "withdrawBullets"
        );
    });
