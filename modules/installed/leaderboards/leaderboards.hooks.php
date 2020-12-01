<?php
    $info = array(
        "name" => "Leaderboards", 
        "version" => "1.0.0", 
        "description" => "This module allows a user to view the leaderboards",
        "author" => array(
            "name" => "Chris Day", 
            "url" => "http://glscript.cdcoding.com"
        ), 
        "pageName" => "Leaderboards",
        "accessInJail" => true, 
        "requireLogin" => true
    );

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=leaderboards", 
            "text" => "Leaderboards"
        );
    });
