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
                LEFT OUTER JOIN userRoles ON (UR_id = U_userLevel)
                LEFT OUTER JOIN gangs on G_id = US_gang
                WHERE UT_desc = 'laston' AND UT_time > ".(time()-1800)." 
                ORDER BY US_rank DESC
            ");
            $online->execute();
            $online = $online->fetchAll(PDO::FETCH_ASSOC);

            foreach ($online as $key => $value) {
            
                $pic = "";
                if (isset($value["US_pic"])) $pic = $value["US_pic"];
                if (!$pic || str_replace("php", "", $pic) != $pic) {
                    $pic = "themes/". _setting("theme")."/images/default-profile-picture.png";
                }
                $online[$key] = array();
                $online[$key]["user"] = array(
                    "name" => $value["U_name"],
                    "id" => $value["U_id"],
                    "rank" => $value["US_rank"],
                    "userLevel" => $value["U_userLevel"],
                    "status" => $value["U_status"], 
                    "color" => $value["UR_color"], 
                    "gang" => $value["US_gang"], 
                    "gangBoss" => $value["G_boss"], 
                    "profilePicture" => $pic,
                    "onlineStatus" => $user->getStatus(false, time() - $value["UT_time"])
                );
            }

            $lastRank = 0;
            foreach ($online as $onlineUser) {
            	$onlineUser["isUser"] = $user->id == $onlineUser["user"]["id"];
            	if ($onlineUser["user"]["gang"] && $onlineUser["user"]["gang"] == $user->info->US_gang) {
            		$onlineUser["isCrew"] = true;
            		$crew[] = $onlineUser;
            	}
        		
                if ($onlineUser["user"]["gangBoss"] == $onlineUser["user"]["id"]) {
        			$onlineUser["isCrewOwner"] = true;
        		}

            	if ($lastRank != $onlineUser["user"]["rank"] && count($currentGroup)) {
            		$onlineGroups[] = array("users" => $currentGroup);
            		$currentGroup = array();
                    $lastRank = $onlineUser["user"]["rank"];
            	}

            	$currentGroup[] = $onlineUser;

            }
            
            $onlineGroups[] = array("users" => $currentGroup);

        }

        foreach ($onlineGroups as $key => $value) {
        	$onlineGroups[$key]["users"][count($onlineGroups[$key]["users"])-1]["last"] = true;
        }

        $page->addTotemplate("onlineUsers", $online);
        $page->addTotemplate("crewUsers", $crew);
        $page->addTotemplate("usersOnline", count($online));
        $page->addTotemplate("crewOnline", count($crew));
        $page->addTotemplate("onlineGroups", $onlineGroups);


        return array(
            "url" => "?page=usersOnline", 
            "extraID" => "usersOnline", 
            "extra" => count($online),
            "text" => "Users Online"
        );
    });
