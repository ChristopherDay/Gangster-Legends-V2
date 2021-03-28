<?php
	
	if (!empty($_SESSION['userID'])) {

		global $page;

		$module = $page->landingPage;

		if (isset($_GET["page"])) {
			$module = $_GET["page"];
		}


		if (isset($page->modules[$module])) {
	
			$pageModule = $page->modules[$module];

	        $u = new user($_SESSION['userID']);
			if (!$u->checkTimer("hospital")) {
				if (!$pageModule["accessInJail"]) $_GET["page"] = "hospital"; 
			}

		}
	}



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
