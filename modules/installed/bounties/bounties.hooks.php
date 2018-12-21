<?php

	new hook("killMenu", function () {
		return array(
			"url" => "?page=bounties", 
			"text" => "Bounties", 
			"sort" => 150
		);
	});


	new hook("userKilled", function ($users) {
		$shooter = $users["shooter"];
		$killed = $users["killed"];

		$bounties = $shooter->db->prepare("
			SELECT SUM(B_cost) as 'reward' FROM bounties WHERE B_userToKill = :killed
		");
		$bounties->bindParam(":killed", $killed->info->U_id);
		$bounties->execute();

		$bounty = $bounties->fetchAll(PDO::FETCH_ASSOC)["reward"];

		if ($bounty) {
			$update = $shooter->db->prepare("
				DELETE FROM bounties WHERE B_userToKill = :killed
			");
			$update->bindParam(":killed", $killed->info->U_id);
			$update->execute();

			$shooter->newNotification(
				"You collected a bounty of $" . number_format($bounty) . " after shooting " . $killed->info->U_name
			);

			$shooter->set("US_money", $shooter->info->US_money + $bounty);

		}

	});

?>