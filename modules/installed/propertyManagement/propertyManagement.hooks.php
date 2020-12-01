<?php
    new hook("userKilled", function ($users) {
        $shooter = $users["shooter"];
        $killed = $users["killed"];

        $properties = $shooter->db->prepare("SELECT * FROM properties WHERE PR_user = :killed");
        $properties->bindParam(":killed", $killed->info->U_id);
        $properties->execute();

        $properties = count($properties->fetchAll(PDO::FETCH_ASSOC));

        if ($properties) {

            $update = $shooter->db->prepare("
                UPDATE properties SET PR_user = :shooter WHERE PR_user = :killed
            ");

            $update->bindParam(":shooter", $shooter->info->U_id);
            $update->bindParam(":killed", $killed->info->U_id);
            $update->execute();

            $shooter->newNotification("You took over $properties properties after shooting " . $killed->info->U_name);

        }

    });
