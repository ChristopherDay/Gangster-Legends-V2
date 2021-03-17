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

    new hook("locationMenu", function ($user) {
    	global $page;
        if ($user) return array(
            "url" => "?page=hospital", 
            "text" => "Hospital", 
            "timer" => $user->getTimer("hospital"),
            "sort" => 10
        );
    });
?>
