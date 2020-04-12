<?php

    new hook("gangMenu", function ($user) {
        if ($user && $user->info->US_gang) {
            return array(
                "url" => "?page=gangBank", 
                "text" => "Gang Bank"
            );
        }
    });

    new hook("gangPermission", function ($user) {
        return array(
            "name" => "Withdraw Cash", 
            "description" => "This gives this gang member the ability to withdraw cash from the gang bank", 
            "key" => "withdrawCash"
        );
    });

    new hook("gangPermission", function ($user) {
        return array(
            "name" => "Withdraw Bullets", 
            "description" => "This gives this gang member the ability to withdraw bullets from the gang bank", 
            "key" => "withdrawBullets"
        );
    });


?>