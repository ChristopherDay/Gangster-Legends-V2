<?php

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=stats", 
            "text" => "Game Stats", 
            "sort" => 100
        );
    });

    new hook("adminWidget-table", function ($user) {
        
        global $db, $page;

        $stats = $db->select("
            SELECT 
                SUM(US_bullets) as 'bullets',
                SUM(US_points) as 'points',
                SUM(US_money) + SUM(US_bank) as 'cash', 
                COUNT(U_id) as 'alive'
            FROM users INNER JOIN userStats ON (US_id = U_id) 
            WHERE U_status != 0 AND U_userLevel = 1
            ORDER BY U_id DESC LIMIT 0, 20
        ");

        $deadStats = $db->select("
            SELECT 
                SUM(US_bullets) as 'bullets',
                SUM(US_money) + SUM(US_bank) as 'cash', 
                COUNT(U_id) as 'dead'
            FROM users INNER JOIN userStats ON (US_id = U_id) 
            WHERE U_status = 0 
            ORDER BY U_id DESC LIMIT 0, 20
        ");

        return array(
            "size" => 4, 
            "title" => "Statistics",
            "type" => "table", 
            "header" => array(
                "columns" => array(
                    array( "name" => "Stat"),
                    array( "name" => "#")
                )
            ),
            "data" => array(
                array(
                    "columns" => array(
                        array( "value" => "Alive Users" ),
                        array( "value" => number_format($stats["alive"]) ),
                    )
                ), 
                array(
                    "columns" => array(
                        array( "value" => "Dead Users" ),
                        array( "value" => number_format($deadStats["dead"]) ),
                    )
                ), 
                array(
                    "columns" => array(
                        array( "value" => "Money" ),
                        array( "value" => $page->money((int) $stats["cash"]) ),
                    )
                ), 
                array(
                    "columns" => array(
                        array( "value" => "Bullets" ),
                        array( "value" => number_format((int) $stats["bullets"]) ),
                    )
                ),
                array(
                    "columns" => array(
                        array( "value" => "points" ),
                        array( "value" => number_format((int) $stats["points"]) ),
                    )
                )
            )
        );

    });