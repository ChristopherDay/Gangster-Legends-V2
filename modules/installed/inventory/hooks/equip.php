<?php

	new Hook("validateItem", function ($data) {
		global $db;

		$id = $data["item"]["id"];

		$rank = $db->select("SELECT * FROM ranks where R_id = :id", array(
			":id" => $data["item"]["equipLevel"]
		));

		if ($rank["R_exp"] > $data["user"]->info->US_exp) {
			return 'You need to be a ' . $rank["R_name"] . ' to use this!';
		}
		
		return true;
	});

	new Hook("validateItem", function ($data) {
		global $db;

		if ($data["slot"]) {
			$valid = false;
			foreach ($data["slot"]["types"] as $type) {
				if ($type["id"] == $data["item"]["type"]) {
					$valid = true;
				}
			}

			if (!$valid) {
				return 'You cant equip this item in this slot!';
			}
		}

		
		return true;
	});