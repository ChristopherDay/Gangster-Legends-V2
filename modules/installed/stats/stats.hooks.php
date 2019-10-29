<?php

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=stats", 
            "text" => "Game Stats", 
            "sort" => 100
        );
    });

?>