<?php

    new hook("accountMenu", function ($user) {

    	global $page;

        $online = array();
        $crew = array();
        $onlineGroups = array();
        $currentGroup = array();

        if ($user) {
    	    $online = $user->db->prepare("
                SELECT * FROM userTimers 
                INNER JOIN users on U_id = UT_user
                INNER JOIN userStats on US_id = UT_user
                LEFT OUTER JOIN gangs on G_id = US_gang
                WHERE UT_desc = 'laston' AND UT_time > ".(time()-1800)." 
                ORDER BY UT_time DESC
            ");
            $online->execute();
            $online = $online->fetchAll(PDO::FETCH_ASSOC);

            $lastRank = 0;
            foreach ($online as $onlineUser) {
            	$onlineUser["isUser"] = $user->id == $onlineUser["U_id"];
            	if ($onlineUser["US_gang"] && $onlineUser["US_gang"] == $user->info->US_gang) {
            		if ($onlineUser["G_boss"] == $onlineUser["U_id"]) {
            			$onlineUser["isCrewOwner"] = true;
            		}
            		$onlineUser["isCrew"] = true;
            		$crew[] = $onlineUser;
            	}

            	if ($lastRank != $user->info->US_rank && count($currentGroup)) {
            		$onlineGroups[] = array("users" => $currentGroup);
            		$currentGroup = array();
            	}

            	$currentGroup[] = $onlineUser;

            }
            
            $onlineGroups[] = array("users" => $currentGroup);

        }

        foreach ($onlineGroups as $key => $value) {
        	$onlineGroups[$key]["users"][count($onlineGroups[$key]["users"])-1]["last"] = true;
        }

        $page->addTotemplate("usersOnline", count($online));
        $page->addTotemplate("crewOnline", count($crew));
        $page->addTotemplate("onlineGroups", $onlineGroups);

        return array(
            "url" => "?page=usersOnline", 
            "text" => "Users Online"
        );
    });
?>
