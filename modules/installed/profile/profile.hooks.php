<?php

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=profile", 
            "text" => "My Profile", 
            "sort" => 900
        );
    });
?>
