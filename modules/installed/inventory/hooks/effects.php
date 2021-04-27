<?php

    new Hook("itemEffects", function () {
    	return array(
            "name" => "Increase % Attack",
    		"type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->attackPower;
                $user->attackPower = $base + ($base / 100 * $value);
                return $user;
            }
    	);
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Decrease % Attack",
            "type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->attackPower;
                $user->attackPower = $base - ($base / 100 * $value);
                return $user;
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Increase % Defense",
            "type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->defensePower;
                $user->defensePower = $base + ($base / 100 * $value);
                return $user;
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Decrease % Defence",
            "type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->defensePower;
                $user->defensePower = $base - ($base / 100 * $value);
                return $user;
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Increase Base HP",
            "type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->rank->R_health;
                $user->rank->R_health = $base + $value;
                return $user;
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Decrease Base HP",
            "type" => "equip", 
            "use" => function (&$user, $value) {
                $base = $user->rank->R_health;
                $user->rank->R_health = $base - ($base / 100 * $value);
                return $user;
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Heal X% Health",
            "type" => "use", 
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

    new Hook("itemEffects", function () {
        return array(
            "name" => "Reset Timer",
            "type" => "use", 
            "use" => function ($user, $value) {
                $user->updateTimer($value, -1, true);
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Add Money",
            "type" => "use", 
            "use" => function ($user, $value) {
                $user->add($value, "US_money");
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Add Bullets",
            "type" => "use", 
            "use" => function ($user, $value) {
                $user->add($value, "US_bullets");
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Reduce Timer",
            "type" => "use", 
            "use" => function ($user, $value) {
                $value = explode("-",$value);
                $timer = $user->getTimer($value[0]);
                $user->updateTimer($value[0], $timer - $value[1]);
            }
        );
    });

    new Hook("itemEffects", function () {
        return array(
            "name" => "Increase Timer",
            "type" => "use", 
            "use" => function ($user, $value) {
                $value = explode("-",$value);
                $timer = $user->getTimer($value[0]);

                if (time() > $timer) {
                    $newTime = time() + $value[1];
                } else {
                    $newTime = $timer + $value[1];
                }

                $user->updateTimer($value[0], $newTime);
            }
        );
    });