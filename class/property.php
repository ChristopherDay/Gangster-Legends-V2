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
					PR_user as 'user'
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
					"user" => 0
				);
			}

			if ($property["user"]) {
				$user = new User($property["user"]);
				$property["user"] = $user->user;
			}


			return $property;

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