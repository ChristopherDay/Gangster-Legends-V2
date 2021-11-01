<?php
    new hook("locationMenu", function () {
        return array(
            "url" => "?page=garage", 
            "text" => "Garage"
        );
    });

    new Hook("clearRound", function () {
        global $db, $page;
        $db->delete("TRUNCATE TABLE garage;");
        $page->alert("Garage cleared", "info");
    });