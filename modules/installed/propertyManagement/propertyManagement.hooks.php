<?php
    new hook("userKilled", function ($users) {
        $shooter = $users["shooter"];
        $killed = $users["killed"];

        $properties = $shooter->db->selectAll("SELECT * FROM properties WHERE PR_user = :killed", array(
            ":killed" => $killed->info->U_id
        ));

        $properties = count($properties);

        if ($properties) {

            $update = $shooter->db->update("
                UPDATE properties SET PR_user = :shooter WHERE PR_user = :killed
            ", array(
                ":shooter" => $shooter->info->U_id,
                ":killed" => $killed->info->U_id
            ));

            $shooter->newNotification("You took over $properties properties after shooting " . $killed->info->U_name);

        }

    });
