<?php

    /**
    * This module allows users to vote for your site on the GL Directory
    *
    * @package GL Directory
    * @author Chris Day
    * @version 1.0.0
    */

    new hook("actionMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=glDirectory", 
            "text" => "Vote for " . _setting("game_name"), 
            "sort" => 1000
        );
    });

    new hook("adminWidget-alerts", function ($user) {
        global $db, $page;
        $key = _setting("voteKey1");
        if (!$key) return array( 
            "type" => "info", 
            "text" => 'To set up your game in the Gangster Legends game directory please register your site <a href="https://directory.glscript.net/" target="_blank">here</a> once you have registered you can set up the setting <a href="?page=admin&module=glDirectory">here</a>. <br />If you do not want to be listed in the director you can remove this module <a href="?page=admin&module=moduleManager&action=deactivate&moduleName=glDirectory">here</a>.'
        );
        return false;
    });