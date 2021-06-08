<?php

    new hook("pointsMenu", function () {

    	$settings = new Settings();

    	$link = $settings->loadSetting("membershipLinkName", true, "Premium Membership");
    	$name = $settings->loadSetting("membershipName", true, "Premium Member");

        return array(
            "url" => "?page=membership", 
            "text" => $link, 
            "sort" => 50
        );
    });