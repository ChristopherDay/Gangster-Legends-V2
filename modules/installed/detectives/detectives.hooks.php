<?php

    new hook("killMenu", function () {
        return array(
            "url" => "?page=detectives", 
            "text" => "Find User", 
            "sort" => 100
        );
    });

