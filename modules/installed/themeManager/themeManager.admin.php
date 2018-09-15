<?php

	class adminModule {

		private function getTheme($themeName = false) {

			$themeDirectories = scandir("themes/");
	        foreach ($themeDirectories as $themeName) {
	            if ($themeName[0] == ".") continue;
	            $moduleInfoFile = "themes/" . $themeName . "/module.json";
	            $moduleHooksFile = "themes/" . $themeName . "/" . $themeName . ".hooks.php";

	            if (file_exists($moduleInfoFile)) {
	                $info = json_decode(file_get_contents($moduleInfoFile), true);
	                $info["id"] = $moduleName;
	                $this->modules[$moduleName] = $info;
	            }
	            if (file_exists($moduleHooksFile)) {
	                include_once $moduleHooksFile;
	            }
	        }

			return array();
		}

		private function validateTheme($theme) {
			$errors = array();

			if (strlen($theme["name"]) < 5) {
				$errors[] = "Theme name is to short, this must be atleast 5 characters";
			}

			if ($theme["id"] == 1 && $theme["themeLevel"] != 2) {
				$errors[] = "Theme ID 1 must be an admin";
			}

			return $errors;
			
		}

		public function method_install () {

			if (isset($this->methodData->submit)) {
				$this->html .= debug($_FILES, 1, 1);
			} else {
				$this->html .= $this->page->buildElement("themeForm");
			}

		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No theme ID specified"));
			}

			$theme = $this->getTheme($this->methodData->id);

			if (!isset($theme["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This theme does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM themes WHERE C_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&theme=themes");

			}


			$this->html .= $this->page->buildElement("themeDelete", $theme);
		}

		public function method_view () {
			
			if (!isset($this->methodData->themeName)) {
				$this->methodData->themeName = false;
			}

			$this->html .= $this->page->buildElement("themeList", array(
				"themes" => $this->getTheme($this->methodData->themeName, true)
			));

		}

	}