<?php
    new hook("accountMenu", function () {
        return array(
            "url" => "?page=logout", 
            "text" => "Logout", 
            "sort" => 1000
        );
    });
?>
