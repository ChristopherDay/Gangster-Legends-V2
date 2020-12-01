<?php
	
    new hook("loginMenu", function () {
        return array(
            "url" => "?page=login", 
            "text" => "Login", 
            "sort" => 100
        );
    });