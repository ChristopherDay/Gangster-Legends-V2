<?php

    new hook("loginMenu", function () {
        return array(
            "url" => "?page=news", 
            "text" => "News", 
            "sort" => 300
        );
    });