<?php

    new hook("killMenu", function () {
        return array(
            "url" => "?page=bounties", 
            "text" => "Bounties", 
            "sort" => 150
        );
    });


    new hook("userKilled", function ($users) {
        global $page;
        $shooter = $users["shooter"];
        $killed = $users["killed"];

        $bounties = $shooter->db->prepare("
            SELECT SUM(B_cost) as 'reward' FROM bounties WHERE B_userToKill = :killed
        ");
        $bounties->bindParam(":killed", $killed->info->U_id);
        $bounties->execute();

        $bounties = $bounties->fetch(PDO::FETCH_ASSOC);

        if (isset($bounties["reward"])) {
            $bounty = $bounties["reward"];
            $update = $shooter->db->prepare("
                DELETE FROM bounties WHERE B_userToKill = :killed
            ");
            $update->bindParam(":killed", $killed->info->U_id);
            $update->execute();

            $shooter->newNotification(
                "You collected a bounty of " . $page->money($bounty) . " after shooting " . $killed->info->U_name
            );

            $shooter->set("US_money", $shooter->info->US_money + $bounty);

        }

    });

