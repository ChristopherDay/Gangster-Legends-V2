<?php

    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("crime");
        if (($time-time()) > 0) {
            $page->addToTemplate('crime_timer', $time);
        } else {
            $page->addToTemplate('crime_timer', 0);
        }
    });

    new hook("actionMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=crimes", 
            "text" => "Crimes", 
            "timer" => $user->getTimer("crime"),
            "templateTimer" => "crime_timer",
            "sort" => 100
        );
    });

    new Hook("membershipBenefit", function () {
        return array(
            "title" => "Getaway Driver", 
            "description" => "All crime timers are reduced by 25%"
        );
    });

    new Hook("alterModuleData", function ($data) {
        if ($data["module"] == "crimes" && !$data["user"]->checkTimer("membership")) {
            $data["data"]["C_cooldown"] = ceil($data["data"]["C_cooldown"] * 0.75);
        }
        return $data;
    });