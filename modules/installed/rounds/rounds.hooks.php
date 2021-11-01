<?php

/**
* This module allows admins to make rounds
*
* @package Rounds
* @author Chris Day
* @version 1.0.0.0
*/

new hook("moduleLoad", function ($module) {
	global $db, $page, $user;

	$round = new Round();

	$page->addToTemplate("round", $round->currentRound);

	if (!$round->currentRound && $page->modules[$module]["requireLogin"]) {
		if (!$user->hasAdminAccessTo("rounds")) {
			if ($user) $user->logout(); 
			return "login";
		} else {
			if ($module != "admin") {
				$page->alert("The current game round is over!", "info");
			}
		}
	}

	if (!$round->currentRound) {
		$page->addToTemplate("nextRound", $round->nextRound);
	}

	return $module;

});
