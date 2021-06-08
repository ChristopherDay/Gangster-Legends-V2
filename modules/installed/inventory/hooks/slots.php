<?php

    new Hook("equipSlot", function () {
        $items = new Items();
        return array(
            "name" => "Weapon", 
            "sort" => 50,
            "getItem" => function ($user) {
                $items = new Items();
                return $items->getItem($user->info->US_weapon);
            },
            "types" => array(
                $items->getType("weapon")
            ),
    		"equip" => function ($user, $item) {
                if ($user->hasItem($item)) {
    				$user->set("US_weapon", $item);
                }
    		}, 
    		"remove" => function ($user) {
    			if ($user->info->US_weapon) {
	                $user->addItem($user->info->US_weapon);
	    			$user->set("US_weapon", 0);
    			}
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
                if ($user->hasItem($item)) {
    				$user->set("US_armor", $item);
                }
    		}, 
    		"remove" => function ($user) {
    			if ($user->info->US_armor) {
	                $user->addItem($user->info->US_armor);
	    			$user->set("US_armor", 0);
    			}
    		}
    	);
    });