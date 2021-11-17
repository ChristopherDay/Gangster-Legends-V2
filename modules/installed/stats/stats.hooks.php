<?php

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=stats", 
            "text" => "Game Stats", 
            "sort" => 100
        );
    });

    // Shortens a number and attaches K, M, B, etc. accordingly
    function number_shorten($number, $precision = 3, $divisors = null) {

        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );    
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }


        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format($number / $divisor, $precision) . $shorthand;
    }

    new hook("adminWidget-html", function ($user) {

        global $page, $db;

        $page->registerTemplateFile("modules/installed/stats/widgetStyles.css");

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

        $html = '
<!-- thanks https://bootsnipp.com/snippets/rljEW -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<div class="row">
    <div class="col-md-3">
        <div class="card-counter info">
            <i class="fa fa-users"></i>
            <span class="count-numbers">'.number_format($stats["alive"]).'</span>
            <span class="count-name">Users Alive</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fa-money"></i>
            <span class="count-numbers">'.number_shorten($stats["cash"], 2) .'</span>
            <span class="count-name">Money</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <i class="fa fa-shield"></i>
            <span class="count-numbers">'.number_shorten($stats["bullets"], 0) .'</span>
            <span class="count-name">Bullets</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter success">
            <i class="fa fa-ticket"></i>
            <span class="count-numbers">'.number_shorten($stats["points"], 0) .'</span>
            <span class="count-name">Points</span>
        </div>
    </div>

</div>
        ';

        return array(
            "sort" => 0,
            "size" => 12, 
            "html" => $html,
            "type" => "html", 
            "title" => "Game Statistics"
        );
    });
