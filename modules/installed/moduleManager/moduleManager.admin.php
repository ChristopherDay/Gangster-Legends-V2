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

				$moduleFile = $_FILES["file"];

				$fileName = str_replace(".zip", "", $moduleFile["name"]);

				if ($fileName == $moduleFile["name"]) {
					return $this->page->buildElement("error", array(
						"text" => "Please provide a module in the correctFormat (moduleName.zip)"
					));
				} 

				$installDir = "modules/installing/";
				$installLocation = $installDir . $fileName . "/";

				$this->html .= debug(get_current_user(), 1, 1);

				// Lock down $installDir to read/write for the php user only
				chmod($installDir, 0660);

				//Remove previous install of this module
				if (file_exists($installLocation)) { 
					chmod($installLocation, 0660);
					array_map('unlink', glob("$installLocation/*"));
				} else {
					// Remake new directory
					mkdir($installLocation);
				}


				$this->html .= debug(glob("$installLocation/*"), 1, 1);

				// Extract module
				$zip = new ZipArchive;
				$res = $zip->open($moduleFile["tmp_name"]);

				if ($res === TRUE) {
					$zip->extractTo($installLocation);
					$zip->close();
					// Lock down the module to read only for the php user
					chmod($installLocation, 0440);
					$this->html .= debug(glob("$installLocation/*"), 1, 1);
				} else {
					return $this->page->buildElement("error", array(
						"text" => "Please provide a zipped module in the correctFormat (moduleName.zip)"
					));
				}


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