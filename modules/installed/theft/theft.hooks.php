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
            "templateTimer" => "theft_timer",
            "sort" => 200
        );
    });

    new Hook("membershipBenefit", function () {
        return array(
            "title" => "Slide Hammer", 
            "description" => "You use a slide hammer to increase your chances of stealing a car by 10%"
        );
    });

    new Hook("alterModuleData", function ($data) {
        if ($data["module"] == "theft" && !$data["user"]->checkTimer("membership")) {
            $data["data"]["T_chance"] = floor($data["data"]["T_chance"] * 1.1);
            if ($data["data"]["T_chance"] > 100) $data["data"]["T_chance"] = 100;
        }
        return $data;
    });