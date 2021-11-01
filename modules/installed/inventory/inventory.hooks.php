<?php

    $items = new Items();
    $items->registerNewType("weapon", "equip");
    $items->registerNewType("armor", "equip");
    $items->registerNewType("consumable", "use");

    new Hook("moneyMenu", function ($user) {
    	global $page;
        if ($user) return array(
            "url" => "?page=inventory", 
            "text" => "Inventory",
            "sort" => 10
        );
    });

    require __DIR__ . "/hooks/slots.php";
    require __DIR__ . "/hooks/effects.php";
    require __DIR__ . "/hooks/links.php";
    require __DIR__ . "/hooks/meta.php";
    require __DIR__ . "/hooks/info.php";
    require __DIR__ . "/hooks/equip.php";

    new Hook("clearRound", function () {
        global $db, $page;
        $db->delete("TRUNCATE TABLE userInventory;");
        $page->alert("Inventory cleared", "info");
    });