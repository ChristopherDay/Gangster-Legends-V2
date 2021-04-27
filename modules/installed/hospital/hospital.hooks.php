<?php


    new hook("moduleLoad", function ($module) {
		global $page, $user;
		if (isset($page->modules[$module]) && $user) {	
			$pageModule = $page->modules[$module];
			if (!$user->checkTimer("hospital")) {
				if (!$pageModule["accessInJail"]) return "hospital"; 
			}
		}
        return $module;
    });

    new hook("userInformation", function ($user) {
        global $page;    
        $time = $user->getTimer("hospital");
        if (($time-time()) > 0) {
            $page->addToTemplate('hospital_timer', $time);
        } else {
            $page->addToTemplate('hospital_timer', 0);
        }
    });

    new hook("locationMenu", function ($user) {
    	global $page;
        if ($user) return array(
            "url" => "?page=hospital", 
            "text" => "Hospital", 
            "timer" => $user->getTimer("hospital"),
            "templateTimer" => "hospital_timer",
            "sort" => 10
        );
    });
?>
