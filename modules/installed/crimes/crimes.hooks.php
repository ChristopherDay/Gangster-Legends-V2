<?php

	new hook("userInformation", function ($user) {
		global $page;
		$time = $user->getTimer("crime");
		if (($time-time()) > 0) {
			$page->addToTemplate('crime_timer', $time);
		} else {
			$page->addToTemplate('crime_timer', 0);
		}
	});

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=crimes", 
			"text" => "Crimes", 
			"sort" => 100
		);
	});
?>