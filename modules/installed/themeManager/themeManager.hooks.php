<?php 

    new hook("adminWidget-alerts", function ($user) {
        global $db, $page;
        $gameName = _setting("game_name");
       	if ($gameName == "Game Name") return array( 
            "type" => "info", 
            "text" => "The game name is not set, To update it please go to the <a href='?page=admin&module=themeManager'>theme manager</a>!"
        );
       	return false;
    });