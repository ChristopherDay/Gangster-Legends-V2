<?php

    class adminModule {



        private function getTheme($themeNameToLoad = false, $type="game") {

            $themes = array();

            $themeDirectories = scandir("themes/");
            foreach ($themeDirectories as $themeName) {
                if ($themeName[0] == ".") continue;
                $themeInfoFile = "themes/" . $themeName . "/theme.json";

                if (file_exists($themeInfoFile)) {
                    $info = json_decode(file_get_contents($themeInfoFile), true);
                    if ($type && $info["themeType"] != $type) continue;
                    $info["id"] = $themeName;
                    $themes[$themeName] = $info;
                    if ($themeNameToLoad && $themeNameToLoad == $themeName) return $info;
                }
            }

            return $themes;
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

        public function method_options() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("landingPage", $this->methodData->landingPage);
                $settings->update("game_name", $this->methodData->game_name);
                $settings->update("pointsName", $this->methodData->pointsName);
                $settings->update("theme", $this->methodData->theme);
                $settings->update("adminTheme", $this->methodData->adminTheme);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Theme options updated."
                ));
                $this->page->addToTemplate("game_name", $this->methodData->game_name);
                $this->page->addToTemplate("pointsName", $this->methodData->pointsName);
                $this->page->loadedTheme = $this->methodData->adminTheme;
            }


            $output = array(
                "landingPage" => $settings->loadSetting("landingPage"),
                "game_name" => $settings->loadSetting("game_name"),
                "pointsName" => $settings->loadSetting("pointsName"),
                "theme" => $settings->loadSetting("theme"),
                "adminTheme" => $settings->loadSetting("adminTheme"),
            );

            $output["modules"] = $this->page->modules;
            $output["themes"] = $this->getTheme(false, "game");
            $output["adminThemes"] = $this->getTheme(false, "admin");

            foreach ($output["modules"] as $key => $value) {
                if ($value["id"] == $output["landingPage"]) {
                    $output["modules"][$key]["selected"] = true;
                }
            }
            foreach ($output["themes"] as $key => $value) {
                if ($value["id"] == $output["theme"]) {
                    $output["themes"][$key]["selected"] = true;
                }
            }
            foreach ($output["adminThemes"] as $key => $value) {
                if ($value["id"] == $output["adminTheme"]) {
                    $output["adminThemes"][$key]["selected"] = true;
                }
            }

            $this->html .= $this->page->buildElement("themeOptions", $output);

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
                "themes" => $this->getTheme($this->methodData->themeName, false)
            ));

        }

    }