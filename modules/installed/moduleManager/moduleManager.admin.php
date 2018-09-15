<?php

	class adminModule {

		private function getModule($moduleName = false) {
			if ($moduleName) {
				return $this->page->modules[$moduleName];
			} 

			return $this->page->modules;
		}

		private function validateModule($module) {
			$errors = array();

			if (strlen($module["name"]) < 5) {
				$errors[] = "Module name is to short, this must be atleast 5 characters";
			}

			if ($module["id"] == 1 && $module["moduleLevel"] != 2) {
				$errors[] = "Module ID 1 must be an admin";
			}

			return $errors;
			
		}

		public function method_install () {

			if (isset($this->methodData->submit)) {
				$this->html .= debug($_FILES, 1, 1);
			} else {
				$this->html .= $this->page->buildElement("moduleForm");
			}

		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No module ID specified"));
			}

			$module = $this->getModule($this->methodData->id);

			if (!isset($module["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This module does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM modules WHERE C_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&module=modules");

			}


			$this->html .= $this->page->buildElement("moduleDelete", $module);
		}

		public function method_view () {
			
			if (!isset($this->methodData->moduleName)) {
				$this->methodData->moduleName = false;
			}

			$this->html .= $this->page->buildElement("moduleList", array(
				"modules" => $this->getModule($this->methodData->moduleName, true)
			));

		}

	}