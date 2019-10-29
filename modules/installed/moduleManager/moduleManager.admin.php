<?php

    class adminModule {

        private function getModule($moduleName = false) {
            if ($moduleName) {
                return $this->page->modules[$moduleName];
            } 

            return $this->page->modules;
        }

        private function getOtherModules($dirType = "installing") {
            $dir = "modules/$dirType/";
            $moduleDirectories = scandir($dir);
            $modules = array();
            foreach ($moduleDirectories as $moduleName) {
                if ($moduleName[0] == ".") continue;
                $moduleInfoFile = $dir . $moduleName . "/module.json";

                if (file_exists($moduleInfoFile)) {
                    $info = json_decode(file_get_contents($moduleInfoFile), true);
                    $info["id"] = $moduleName;
                    $modules[$moduleName] = $info;
                }
            }
            return $modules;
        }

        private function viewInstall($moduleName) {
            $moduleFolder = "modules/installing/$moduleName/";

            chmod($moduleFolder, 0765);
            $info = json_decode(file_get_contents($moduleFolder . "module.json"), true);
            chmod($moduleFolder, 0440);
            $info["id"] = $moduleName;
            $info["_installing"] = true;
            $this->html .= $this->page->buildElement("moduleOverview", $info);
        }

        private function removeDir($dir) {
            if ($dir[0] == ".") return false;
            array_map('unlink', glob("$dir/*"));
            rmdir($dir);
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

            if (isset($this->methodData->remove)) {

                $this->removeDir("modules/installing/" . $this->methodData->remove);

                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Module removed successfully"
                ));

                $this->html .= $this->page->buildElement("moduleForm", array(
                    "modules" => $this->getOtherModules()
                ));
            } else if (isset($this->methodData->view)) {
                $this->viewInstall($this->methodData->view);
            } else if (isset($this->methodData->installModule)) {

                $moduleName = $this->methodData->installModule;
                $installDir = "modules/installing/";
                $installLocation = $installDir . $moduleName . "/";
                
                // Move files over
                $oldDir = "modules/installing/" . $moduleName;
                $newDir = "modules/installed/" . $moduleName;
                chmod($oldDir, 0765);

                $sqlFile = $installLocation . "schema.sql";
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    if (!$this->db->query($sql)) {
                        if (!isset($this->methodData->force)) {
                            return $this->html .= $this->page->buildElement("continueWithError", array(
                                "error" => array(
                                    "text" => "There was an error with the SQL when installing this module",
                                    "output" => debug($this->db->errorInfo(), true, true)
                                ),
                                "id" => $moduleName
                            ));
                        }
                    }
                }

                if (@rename($oldDir, $newDir)) {
                    return $this->html .= $this->page->buildElement("success", array(
                        "text" => "Module installed successfully"
                    ));
                } else {
                    return $this->html .= $this->page->buildElement("error", array(
                        "text" => "Module failed to install"
                    ));
                }
            } else if (isset($this->methodData->submit)) {

                $moduleFile = $_FILES["file"];

                $fileName = str_replace(".zip", "", $moduleFile["name"]);

                if ($fileName == $moduleFile["name"]) {
                    return $this->page->buildElement("error", array(
                        "text" => "Please provide a module in the correctFormat (moduleName.zip)"
                    ));
                } 

                $installDir = "modules/installing/";
                $installLocation = $installDir . $fileName . "/";

                // Lock down $installDir to read/write for the php user only
                chmod($installDir, 0765);

                //Remove previous install of this module
                if (file_exists($installLocation)) { 
                    chmod($installLocation, 0765);
                    $this->removeDir($installLocation);
                } else {
                    // Remake new directory
                    mkdir($installLocation);
                }

                // Extract module
                $zip = new ZipArchive;
                $res = $zip->open($moduleFile["tmp_name"]);

                if ($res === TRUE) {
                    $zip->extractTo($installLocation);
                    $zip->close();
                    // Lock down the module to read only for the php user
                    chmod($installLocation, 0440);
                    $this->viewInstall($fileName);
                } else {
                    return $this->page->buildElement("error", array(
                        "text" => "Please provide a zipped module in the correctFormat (moduleName.zip)"
                    ));
                }

            } else {
                $this->html .= $this->page->buildElement("moduleForm", array(
                    "modules" => $this->getOtherModules()
                ));
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

            if ($this->methodData->moduleName) {
                $info = $this->getModule($this->methodData->moduleName);
                $info["_activated"] = true;
                return $this->html .= $this->page->buildElement("moduleOverview", $info);
            }

            $this->html .= $this->page->buildElement("moduleList", array(
                "modules" => $this->getModule($this->methodData->moduleName, true)
            ));

        }

        public function method_deactivated () {
            if (!isset($this->methodData->moduleName)) {
                $this->methodData->moduleName = false;
            }
            if ($this->methodData->moduleName) {
                $info = $this->getOtherModules("disabled")[$this->methodData->moduleName];
                $info["_deactivated"] = true;
                return $this->html .= $this->page->buildElement("moduleOverview", $info);
            }
            $this->html .= $this->page->buildElement("deactivatedModuleList", array(
                "modules" => $this->getOtherModules("disabled")
            ));
        }

        public function method_deactivate () {

            $moduleName = @$this->methodData->moduleName;
            $info = @$this->page->modules[$moduleName];

            if (!$info) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This module does not exist"
                ));
            }

            if (isset($this->methodData->do)) {
                if (!@rename("modules/installed/$moduleName", "modules/disabled/$moduleName")) {
                    return $this->html .= $this->page->buildElement("error", array(
                        "text" => "This module cant be deactivated"
                    ));
                }
                return $this->html .= $this->page->buildElement("success", array(
                    "text" => "This module has been deactivated"
                ));
            }

            $this->html .= $this->page->buildElement("alterModuleConfirm", array(
                "type" => "deactivate", 
                "module" => $info
            ));
        }

        public function method_reactivate () {

            $moduleName = @$this->methodData->moduleName;
            $info = @$this->getOtherModules("disabled")[$moduleName];

            if (!$info) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This module does not exist"
                ));
            }

            if (isset($this->methodData->do)) {
                if (!@rename("modules/disabled/$moduleName", "modules/installed/$moduleName")) {
                    return $this->html .= $this->page->buildElement("error", array(
                        "text" => "This module cant be reactivated"
                    ));
                }
                return $this->html .= $this->page->buildElement("success", array(
                    "text" => "This module has been reactivated"
                ));
            }

            $this->html .= $this->page->buildElement("alterModuleConfirm", array(
                "type" => "reactivate", 
                "module" => $info
            ));
        }

    }