<?php

	class adminModule {

		private function getCars($carsID = "all") {
			if ($carsID == "all") {
				$add = "";
			} else {
				$add = " WHERE CA_id = :id";
			}
			
			$cars = $this->db->prepare("
				SELECT
					CA_id as 'id',  
					CA_name as 'name',  
					CA_theftChance as 'theftChance',  
					CA_value as 'value'
				FROM cars" . $add . "
				ORDER BY CA_name, CA_value"
			);

			if ($carsID == "all") {
				$cars->execute();
				return $cars->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$cars->bindParam(":id", $carsID);
				$cars->execute();
				return $cars->fetch(PDO::FETCH_ASSOC);
			}
		}

		private function validateCars($cars) {
			$errors = array();

			if (strlen($cars["name"]) < 6) {
				$errors[] = "Cars name is to short, this must be atleast 5 characters";
			}
			if (intval($cars["value"]) < 1) {
				$errors[] = "The value must be greater than 1";
			}
			
			if (!intval($cars["theftChance"])) {
				$errors[] = "No chance specified";
			}

			return $errors;
			
		}

		public function method_new () {

			$cars = array();

			if (isset($this->methodData->submit)) {
				$cars = (array) $this->methodData;
				$errors = $this->validateCars($cars);
				
				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$insert = $this->db->prepare("
						INSERT INTO cars (CA_name, CA_theftChance, CA_value)  VALUES (:name, :theftChance, :value);
					");
					$insert->bindParam(":name", $this->methodData->name);
					$insert->bindParam(":theftChance", $this->methodData->theftChance);
					$insert->bindParam(":value", $this->methodData->value);
					
					$insert->execute();


					$this->html .= $this->page->buildElement("success", array("text" => "This car has been created"));

				}

			}

			$cars["editType"] = "new";
			$this->html .= $this->page->buildElement("carsForm", $cars);
		}

		public function method_edit () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No car ID specified"));
			}

			$cars = $this->getCars($this->methodData->id);

			if (isset($this->methodData->submit)) {
				$cars = (array) $this->methodData;
				$errors = $this->validateCars($cars);

				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$update = $this->db->prepare("
						UPDATE cars SET CA_name = :name, CA_theftChance = :theftChance, CA_value = :value WHERE CA_id = :id
					");
					$update->bindParam(":name", $this->methodData->name);
					$update->bindParam(":theftChance", $this->methodData->theftChance);
					$update->bindParam(":value", $this->methodData->value);
					$update->bindParam(":id", $this->methodData->id);
					$update->execute();

					$this->html .= $this->page->buildElement("success", array("text" => "This car has been updated"));

				}

			}

			$cars["editType"] = "edit";
			$this->html .= $this->page->buildElement("carsForm", $cars);
		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No car ID specified"));
			}

			$cars = $this->getCars($this->methodData->id);

			if (!isset($cars["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This car does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM cars WHERE CA_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&module=cars");

			}


			$this->html .= $this->page->buildElement("carsDelete", $cars);
		}

		public function method_view () {
			
			$this->html .= $this->page->buildElement("carsList", array(
				"cars" => $this->getCars()
			));

		}

	}
