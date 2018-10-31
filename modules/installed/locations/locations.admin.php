<?php

	class adminModule {

		private function getLocations($locationsID = "all") {
			if ($locationsID == "all") {
				$add = "";
			} else {
				$add = " WHERE L_id = :id";
			}
			
			$locations = $this->db->prepare("
				SELECT
					L_id as 'id',  
					L_name as 'name',
					L_cost as 'cost',  
					L_bullets as 'bullets',
					L_bulletCost as 'bulletCost',
					L_cooldown as 'cooldown'
				FROM locations" . $add . "
				ORDER BY L_name, L_cost"
			);

			if ($locationsID == "all") {
				$locations->execute();
				return $locations->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$locations->bindParam(":id", $locationsID);
				$locations->execute();
				return $locations->fetch(PDO::FETCH_ASSOC);
			}
		}

		private function validateLocations($locations) {
			$errors = array();

			if (strlen($locations["name"]) < 6) {
				$errors[] = "Location name is to short, this must be at least 5 characters";
			}
			
			
			if (!intval($locations["cost"])) {
				$errors[] = "No cost specified";
			}

			return $errors;
			
		}

		public function method_new () {

			$locations = array();

			if (isset($this->methodData->submit)) {
				$locations = (array) $this->methodData;
				$errors = $this->validateLocations($locations);
				
				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$insert = $this->db->prepare("
						INSERT INTO locations (L_name, L_cost, L_bullets, L_bulletCost, L_cooldown)  VALUES (:name, :cost, :bullets, :bulletCost, :cooldown);
					");
					$insert->bindParam(":name", $this->methodData->name);
					$insert->bindParam(":cost", $this->methodData->cost);
					$insert->bindParam(":bullets", $this->methodData->bullets);
				    $insert->bindParam(":bulletCost", $this->methodData->bulletCost);
					$insert->bindParam(":cooldown", $this->methodData->cooldown);
					
					
					$insert->execute();


					$this->html .= $this->page->buildElement("success", array("text" => "This location has been created"));

				}

			}

			$locations["editType"] = "new";
			$this->html .= $this->page->buildElement("locationsForm", $locations);
		}

		public function method_edit () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No location ID specified"));
			}

			$locations = $this->getLocations($this->methodData->id);

			if (isset($this->methodData->submit)) {
				$locations = (array) $this->methodData;
				$errors = $this->validateLocations($locations);

				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$update = $this->db->prepare("
						UPDATE locations SET L_name = :name, L_cost = :cost, L_bullets = :bullets, L_bulletCost = :bulletCost, L_cooldown = :cooldown WHERE L_id = :id
					");
					$update->bindParam(":name", $this->methodData->name);
					$update->bindParam(":cost", $this->methodData->cost);
					$update->bindParam(":bullets", $this->methodData->bullets);
					$update->bindParam(":bulletCost", $this->methodData->bulletCost);
					$update->bindParam(":cooldown", $this->methodData->cooldown);
					
					$update->bindParam(":id", $this->methodData->id);
					$update->execute();

					$this->html .= $this->page->buildElement("success", array("text" => "This location has been updated"));

				}

			}

			$locations["editType"] = "edit";
			$this->html .= $this->page->buildElement("locationsForm", $locations);
		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No location ID specified"));
			}

			$locations = $this->getLocations($this->methodData->id);

			if (!isset($locations["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This location does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM locations WHERE L_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&module=locations");

			}


			$this->html .= $this->page->buildElement("locationsDelete", $locations);
		}

		public function method_view () {
			
			$this->html .= $this->page->buildElement("locationsList", array(
				"locations" => $this->getLocations()
			));

		}

	}
