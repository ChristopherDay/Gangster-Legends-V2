<?php

	new hook("userInformation", function ($user) {
		global $page;	
		$time = $user->getTimer("chase");
		if (($time-time()) > 0) {
			$page->addToTemplate('chase_timer', $time);
		} else {
			$page->addToTemplate('chase_timer', 0);
		}
	});

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=policeChase", 
			"text" => "Police Chase", 
			"sort" => 300
		);
	});
?>
