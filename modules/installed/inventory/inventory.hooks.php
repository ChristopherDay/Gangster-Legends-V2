<?php

    $items = new Items();
    $items->registerNewType("weapon", "equip");
    $items->registerNewType("armor", "equip");
    $items->registerNewType("consumable", "use");
    $items->registerNewType("handWeapon", "equip");

    new Hook("moneyMenu", function ($user) {
    	global $page;
        if ($user) return array(
            "url" => "?page=inventory", 
            "text" => "Inventory",
            "sort" => 10
        );
    });

    new Hook("equipSlot", function () {
        $items = new Items();
        return array(
            "name" => "Weapon", 
            "sort" => 1,
            "getItem" => function ($user) {
                $items = new Items();
                return $items->getItem($user->info->US_weapon);
            },
            "types" => array(
                $items->getType("weapon")
            ),
            "equip" => function ($user, $item) {
                $user->set("US_weapon", $item);
            }, 
            "remove" => function ($user) {
                $user->set("US_weapon", 0);
            }
        );
    });

    new Hook("equipSlot", function () {
        $items = new Items();
        return array(
            "name" => "Off Hand", 
            "sort" => 1,
            "getItem" => function ($user) {
                $items = new Items();
                return $items->getItem(0);
            },
            "types" => array(
                $items->getType("weapon"),
                $items->getType("knifes")
            ),
            "equip" => function ($user, $item) {
            }, 
            "remove" => function ($user) {
            }
        );
    });

    new Hook("equipSlot", function () {
    	$items = new Items();
    	return array(
    		"name" => "Armor", 
    		"sort" => 100,
    		"getItem" => function ($user) {
    			$items = new Items();
    			return $items->getItem($user->info->US_armor);
    		},
    		"types" => array(
    			$items->getType("armor")
    		),
    		"equip" => function ($user, $item) {
    			$user->set("US_armor", $item);
    		}, 
    		"remove" => function ($user) {
    			$user->set("US_armor", 0);
    		}
    	);
    });

    new Hook("itemEffects", function ($data) {
    	return array(
            "name" => "Increase % Attack",
    		"type" => "equip", 
            "use" => function ($base, $value) {
                return $base + ($base / 100 * $value);
            }
    	);
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Decrease % Attack",
            "type" => "equip", 
            "use" => function ($base, $value) {
                return $base - ($base / 100 * $value);
            }
        );
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Increase % Defense",
            "type" => "equip", 
            "use" => function ($base, $value) {
                return $base + ($base / 100 * $value);
            }
        );
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Decrease % Defence",
            "type" => "equip", 
            "use" => function ($base, $value) {
                return $base - ($base / 100 * $value);
            }
        );
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Increase Base HP",
            "type" => "equip", 
            "use" => function ($base, $value) {
                return $base + ($base / 100 * $value);
            }
        );
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Decrease Base HP",
            "type" => "equip", 
            "use" => function ($base, $value) {
                return $base - ($base / 100 * $value);
            }
        );
    });

    new Hook("itemEffects", function ($data) {
        return array(
            "name" => "Heal X% Health",
            "type" => "consumable", 
            "use" => function ($user, $value) {
                $subtract = $user->rank->R_health / 100 * $value;
                if ($user->info->US_health > $subtract) {
                    $user->set("US_health", 0);
                } else {
                    $user->subtract("US_health", $subtract);
                }
            }
        );
    });

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

        $links[] = array(
            "name" => "Information", 
            "sort" => 50,
            "link" => "?page=inventory&action=information&item=" . $item["id"]
        );

        return $links;
    });
























