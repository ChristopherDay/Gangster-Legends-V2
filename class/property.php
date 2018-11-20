<?php

	class Property {

		private $user, $db, $module;

		public function __construct($user, $module) {
			$this ->db = $user->db;
			$this->user = $user;
			$this->module = $module;
		}

		public function getOwnership() {

			$property = $this->db->prepare("
				SELECT
					PR_id as 'id', 
					PR_location as 'location',
					PR_module as 'module', 
					PR_user as 'user', 
					PR_profit as 'profit', 
					PR_cost as 'cost'
				FROM 
					properties
				WHERE 
					PR_location = :location AND 
					PR_module = :module
			");

			$property->bindParam(":location", $this->user->info->US_location);
			$property->bindParam(":module", $this->module);
			$property->execute();

			$property = $property->fetch(PDO::FETCH_ASSOC);

			if (!isset($property["id"])) {
				return array(
					"id" => 0, 
					"location" => $this->user->info->US_location, 
					"module" => $this->module, 
					"user" => 0, 
					"profit" => 0,
					"userOwnsThis" => false
				);
			}

			if ($property["user"]) {
				$user = new User($property["user"]);
				$property["user"] = $user->user;
				$property["userOwnsThis"] = $user->id == $this->user->id;
			}

			$property["profit"] = "$" . number_format($property["profit"]);

			return $property;

		}

		public function updateProfit($money) {
			$update = $this->db->prepare("
				UPDATE  
					properties 
				SET 
					PR_profit = PR_profit + :cash 
				WHERE 
					PR_location = :location AND 
					PR_module = :module;
			");
			$update->bindParam(":cash", $money);
			$update->bindParam(":location", $this->user->info->US_location);
			$update->bindParam(":module", $this->module);
			$update->execute();
		}

		public function transfer($newOwner) {

			$currentOwner = $this->getOwnership();

			if ($currentOwner["user"]) {
				$query = "UPDATE  properties SET PR_user = :user WHERE PR_location = :location AND PR_module = :module;";
			} else {
				$query = "INSERT INTO properties (PR_location, PR_module, PR_user) VALUES (:location, :module, :user);";
			}

			$transfer = $this->db->prepare($query);
			$transfer->bindParam(":location", $this->user->info->US_location);
			$transfer->bindParam(":module", $this->module);
			$transfer->bindParam(":user", $this->user->id);
			$transfer->execute();

		}

	}

?>