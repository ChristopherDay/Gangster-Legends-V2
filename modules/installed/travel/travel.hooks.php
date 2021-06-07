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

    new Hook("membershipBenefit", function () {
        return array(
            "title" => "Frequent Flyer Discount", 
            "description" => "All travel costs are reduced by 75%"
        );
    });

    new Hook("alterModuleData", function ($data) {
        if ($data["module"] == "travel" && !$data["user"]->checkTimer("membership")) {
            $data["data"]["L_cost"] = ceil($data["data"]["L_cost"] * 0.25);
        }
        return $data;
    });