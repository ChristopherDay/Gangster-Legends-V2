<?php

	
    new hook("loginMenu", function () {
        return array(
            "url" => "?page=register", 
            "text" => "Register", 
            "sort" => 200
        );
    });