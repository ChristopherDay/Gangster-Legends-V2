<?php

    new Hook("itemActionLink", function ($item) {

        $links = array();

        $slots = new Hook("equipSlot");
        $slots = $slots->run();

        $items = new Items();

        foreach ($slots as $slot) {
            foreach ($slot["types"] as $t) {
                if ($t["id"] == $item["type"]) {
                    $type = $items->getType(false, $item["type"]);
                    $links[] = array(
                        "name" => "Equip " . $slot["name"],
                        "sort" => 10,
                        "link" => "?page=inventory&action=equip&slot=" . $slot["name"]. "&item=" . $item["id"] 
                    );
                }
            }
        }


        return $links;
    });

    new Hook("itemActionLink", function ($item) {

        $links = array();

        $slots = new Hook("equipSlot");
        $slots = $slots->run();

        $items = new Items();

        $type = $items->getType(false, $item["type"]);
        
        if ($type["type"] == "use") {
            $links[] = array(
                "name" => "Use",
                "sort" => 10,
                "link" => "?page=inventory&action=use&item=" . $item["id"]
            );
        }

        return $links;
    });

    new Hook("itemActionLink", function ($item) {

        $links = array();

        $links[] = array(
            "name" => "Information", 
            "sort" => 50,
            "link" => "?page=inventory&action=information&item=" . $item["id"]
        );

        return $links;
    });