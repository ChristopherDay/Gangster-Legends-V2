<?php

    /* Check if the leadership is killed */
    new hook("userKilled", function ($users) {
        $shooter = $users["shooter"];
        $killed = $users["killed"];

        if ($killed->info->US_gang) {
            $gang = new Gang($killed->info->US_gang);

            if ($gang->gang->underboss == $killed->id) {
                $update = $this->db->prepare("UPDATE gangs SET G_underboss = 0 WHERE G_id = :g");
                $update->bindParam(":g", $killed->ingo->US_gang);
                $update->execute();
                $boss = new User($gang->gang["boss"]);
                $boss->newNotification("Your underboss was killed!");
            }

            if ($gang->gang->boss == $killed->id) {
                if ($gang->gang["underboss"] != 0) {
                    $update = $this->db->prepare("UPDATE gangs SET G_boss = G_underboss, G_underboss = 0 WHERE G_id = :g");
                    $update->bindParam(":g", $killed->ingo->US_gang);
                    $update->execute();
                    $boss = new User($gang->gang["underboss"]);
                    $boss->newNotification("Your gang boss was killed, you are now the boss!");
                } else {
                    $update = $this->db->prepare("
                        DELETE FROM gangs WHERE G_id = :g;
                        UPDATE userStats SET US_gang = 0 WHERE US_gang = :g
                    ");
                    $update->bindParam(":g", $killed->info->US_gang);
                    $update->execute();
                    
                }
            }

        }


    });

    new Hook("profileStat", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        $gang = $user->getGang();
        return array(
            "text" => $name,
            "stat" => "<a href='?page=gangs&action=view&id=".$gang["id"]."'>" . $gang["name"] . "</a>"
        );
    });

    /* Gang Links */
    new hook("gangMenu", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "url" => "?page=gangs", 
            "text" => "All " . $name, 
            "sort" => 10
        );
    });

    new hook("gangMenu", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        if ($user && $user->info->US_gang) return array(
            "url" => "?page=gangs&action=home", 
            "text" => "My " . $name, 
            "sort" => 20
        );
    });

    new hook("gangMenu", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        $g = new Gang($user->info->US_gang);
        if ($user && $user->info->US_gang)  {
            if ($g->can("viewLogs", $user)) return array(
                "url" => "?page=gangs&action=logs", 
                "text" => "Logs", 
                "sort" => 150
            );
        }
    });


    /* Gang Permissions */
    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Invite New ".$name." Members", 
            "description" => "This gives this ".$name." member the ability to invite other members to your ".$name."", 
            "key" => "invite"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Kick ".$name." Members", 
            "description" => "This gives this ".$name." member the ability to kick members from your ".$name."", 
            "key" => "kick"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Upgrade " . $name . " Size", 
            "description" => "This gives this ".$name." member the ability to kick members from your ".$name."", 
            "key" => "upgrade"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "View " . $name . " Logs", 
            "description" => "This gives this ".$name." member the ability to view ".$name." logs", 
            "key" => "viewLogs"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Edit ".$name." Profile", 
            "description" => "This gives this ".$name." member the ability to edit the ".$name." profile", 
            "key" => "editProfile"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Edit ".$name." Permissions", 
            "description" => "This gives this ".$name." member the ability to edit a ".$name." member permissions", 
            "key" => "editPermissions"
        );
    });

    new hook("gangPermission", function ($user) {
        $s = new Settings();
        $name = $s->loadSetting("gangName");
        return array(
            "name" => "Edit ".$name." Information", 
            "description" => "This gives this ".$name." member the ability to edit the internal ".$name." information", 
            "key" => "editInfo"
        );
    });

    new Hook("clearRound", function () {
        global $db, $page;
        $db->delete("TRUNCATE TABLE gangs;");
        $page->alert("Gangs cleared", "info");
    });
    