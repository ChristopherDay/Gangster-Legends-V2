<?php

    new hook("customMenus", function ($user) {
        if ($user) {

            $forums = $user->db->prepare("SELECT * FROM forums ORDER BY F_sort ASC, F_name");
            $forums->execute();

            $allForums = $forums->fetchAll(PDO::FETCH_ASSOC);

            $items = array();

            foreach ($allForums as $forum) {
                $items[] = array(
                    "url" => "?page=forum&action=forum&id=" . $forum["F_id"], 
                    "text" => $forum["F_name"]
                );
            }
            
            if (!count($items)) return array();
            
            return array(
                "title" => "Forum", 
                "items" => $items,
                "sort" => 250
            );
        } else {
            return false;
        }
    });
?>