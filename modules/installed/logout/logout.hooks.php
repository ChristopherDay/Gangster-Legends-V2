<?php
    new hook("accountMenu", function () {
        return array(
            "url" => "?page=logout", 
            "text" => "Logout", 
            "notAjax" => true,
            "sort" => 1000
        );
    });
