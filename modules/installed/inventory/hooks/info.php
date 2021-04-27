<?php

    new Hook("itemInformation", function ($item) {
    	global $page;
    	return array(
            "label" => "Value",
            "sort" => 10,
            "value" => $page->money($item["cost"])
    	);
    });

    new Hook("itemInformation", function ($item) {
    	global $db, $page;
    	$rank = $db->select("SELECT * FROM ranks WHERE R_id = :id", array(
    		":id" => $item["equipLevel"]
    	));
    	
    	return array(
            "label" => "Min. Rank",
            "sort" => 20,
            "value" => $rank["R_name"]
    	);
    });