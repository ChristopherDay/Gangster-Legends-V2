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

	$round->currentRound = $db->select("
		SELECT
			R_id as 'round', 
			R_name as 'name', 
			R_start as 'start', 
			R_end as 'end'
		FROM rounds 
		WHERE UNIX_TIMESTAMP() BETWEEN R_start AND R_end
	");

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
		$round->nextRound = $db->select("
			SELECT
				R_id as 'round', 
				R_name as 'name', 
				R_start as 'start', 
				R_end as 'end'
			FROM rounds 
			WHERE R_start > UNIX_TIMESTAMP()
			ORDER BY R_start ASC
		");

		$page->addToTemplate("nextRound", $round->nextRound);
	}

	return $module;

});

new hook("adminWidget-html", function ($user) {
    
    global $db, $page;

    return array(
        "size" => 4, 
        "title" => "Current Round",
        "type" => "html", 
        "html" => "<h1>test</h1>"
    );

});

