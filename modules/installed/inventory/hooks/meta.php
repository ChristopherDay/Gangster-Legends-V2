<?php

    new Hook("itemMetaData", function () {
    	global $db;
    	return array(
    		"id" => "description", 
            "sort" => 1,
            "width" => 12,
            "label" => "Item Description", 
    		"type" => "textarea",
            "rows" => 5, 
            "validate" => function ($value) { return true; }
    	);
    });

    new Hook("itemMetaData", function () {
        global $db;
        return array(
            "id" => "cost", 
            "sort" => 3,
            "width" => 6,
            "label" => "Item Value", 
            "type" => "number",
            "validate" => function ($value) { return true; }
        );
    });

    new Hook("itemMetaData", function () {
    	global $db;
    	return array(
    		"id" => "equipLevel",
    		"label" => "Minimum level to equip/use", 
            "sort" => 10,
            "width" => 6,
    		"type" => "select", 
    		"options" => $db->selectAll("
    			SELECT R_id as 'id', R_name as 'name' FROM ranks ORDER BY R_exp ASC
    		"),
            "validate" => function ($value) { return true; }
    	);
    });