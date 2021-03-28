<?php

    new hook("userInformation", function ($user) {
        global $page;
        $time = $user->getTimer("jail");
        if (($time-time()) > 0) {
            $page->addToTemplate('jail_timer', $time);
        } else {
            $page->addToTemplate('jail_timer', 0);
        }
    });        
            
    new hook("locationMenu", function ($user) {
        global $page;

        if ($user) {
            
            $usersInJail = $user->db->selectAll("
                    SELECT DISTINCT 
                        `U_id` as 'id', 
                        `U_name` as 'name', 
                        `US_rank` as 'rank'
                    FROM 
                        `userTimers` `jail`
                        INNER JOIN `users` ON (U_id = UT_user) 
                        INNER JOIN `userStats` ON (US_id = UT_user)
                        LEFT OUTER JOIN `userTimers` as `max` ON (`max`.`UT_desc` = 'superMax' AND `max`.`UT_user` = `jail`.`UT_user`)
                    WHERE 
                        `US_location` = :location AND 
                        `jail`.`UT_desc` = 'jail' AND 
                        `jail`.`UT_time` > UNIX_TIMESTAMP()
                    ORDER BY US_rank ASC, U_name
            ", array(
                ":location" => $user->info->US_location
            ));


            $page->addToTemplate("jailCount", count($usersInJail));
            return array(
                "url" => "?page=jail", 
                "extra" => count($usersInJail),
                "extraID" => "jailCount",
                "sort" => 998,
                "text" => "Jail"
            );
        }
    });
