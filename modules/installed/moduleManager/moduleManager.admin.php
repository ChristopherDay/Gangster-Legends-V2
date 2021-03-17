<?php

    function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){
                delete_files( $file );      
            }
            @rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }

    class adminModule {

        private function getModule($moduleName = false) {
            if ($moduleName) {
                return $this->page->modules[$moduleName];
            } 

            return $this->page->modules;
        }

        public function getInfo($file, $moduleName, $dir) {
            $info = json_decode(file_get_contents($file), true);
            if (isset($info["bundle"])) {
                $modules = glob($dir . $moduleName . "/*.zip");
                $count = count($modules);   

                $info["name"] = $moduleName;
                $info["modules"] = array();

                foreach ($modules as $file) {

                    $name = pathinfo($file, PATHINFO_FILENAME);


                    $installedDir = "modules/installed/";
                    $installedLocation = $installedDir . $name . "/";

                    $installDir = "modules/installing/";
                    $installLocation = $installDir . $name . "/";
                    
                    $modInfo = array(
                        "id" => $moduleName, 
                        "path" => $file, 
                        "name" => $name, 
                        "installed" => file_exists($installedLocation), 
                        "extracted" => file_exists($installLocation)
                    );

                    if (
                        isset($this->methodData->extract) && 
                        ($this->methodData->extract == "*" || $this->methodData->extract == $name)
                    ) {
                        if (file_exists($installLocation)) { 
                            $this->removeDir($installLocation);
                        } else {
                            mkdir($installLocation);
                        }

                        $zip = new ZipArchive;
                        $res = $zip->open($file);

                        if ($res === TRUE) {
                            $zip->extractTo($installLocation);
                            $zip->close();
                        } 
                        $modInfo["extracted"] = true;
                    }


                    $info["modules"][] = $modInfo;
                }

            }
            $info["id"] = $moduleName;
            return $info;
        }

        private function getOtherModules($dirType = "installing") {
            $dir = "modules/$dirType/";
            $moduleDirectories = scandir($dir);
            $modules = array();

            foreach ($moduleDirectories as $moduleName) {
                if ($moduleName[0] == ".") continue;

                $moduleInfoFile = $dir . $moduleName . "/module.json";
                
                if (file_exists($moduleInfoFile)) {
                    $info = $this->getInfo($moduleInfoFile, $moduleName, $dir);
                    $modules[$moduleName] = $info;
                }
            }
            return $modules;
        }

        private function viewInstall($moduleName) {
            $moduleFolder = "modules/installing/$moduleName/";

            $info = $this->getInfo($moduleFolder . "module.json", $moduleName, "modules/installing/");

            if (isset($info["bundle"])) {
                //debug($info);
                
                if (isset($this->methodData->installBundleModule)) { 

                    if ($this->methodData->installBundleModule == "*") {
                        foreach ($info["modules"] as $module) {
                            if ($module["extracted"]) {
                                $this->installModuleCommit($module["name"]);
                            }
                        }
                    } else {
                        $this->installModuleCommit($this->methodData->installBundleModule);
                    }

                    $info = $this->getInfo($moduleFolder . "module.json", $moduleName, "modules/installing/");
                } 
                if (isset($this->methodData->deactivateBundleModule)) { 
                    $this->deactivateModule($this->methodData->deactivateBundleModule);

                    $info = $this->getInfo($moduleFolder . "module.json", $moduleName, "modules/installing/");
                } 


                $info["bundleInfo"] = "";

                foreach ($info["modules"] as $value) {
                    $info["bundleInfo"] .= $this->page->buildElement("mInfo", $value);
                }
                

                $this->html .= $this->page->buildElement("moduleOverview", $info);
            } else {
                $info["id"] = $moduleName;
                $info["_installing"] = true;
                $this->html .= $this->page->buildElement("moduleOverview", $info);
            }

        }

        private function removeDir($dir) {
            if ($dir[0] == ".") return false;
            delete_files($dir);
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
                $this->installModuleCommit($moduleName);
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

                if (file_exists($installLocation)) { 
                    $this->removeDir($installLocation);
                } else {
                    mkdir($installLocation);
                }

                $zip = new ZipArchive;
                $res = $zip->open($moduleFile["tmp_name"]);

                if ($res === TRUE) {
                    $zip->extractTo($installLocation);
                    $zip->close();

                    if (!file_exists($installLocation . "module.json")) {
                        $this->removeDir($installLocation);
                        return $this->html .= $this->page->buildElement("error", array(
                            "text" => "Please provide a zipped with a module.json file"
                        ));
                    }

                    $this->viewInstall($fileName);
                } else {
                    return $this->html .= $this->page->buildElement("error", array(
                        "text" => "Please provide a zipped module in the correctFormat (moduleName.zip)"
                    ));
                }

            } else {
                $this->html .= $this->page->buildElement("moduleForm", array(
                    "modules" => $this->getOtherModules()
                ));
            }

        }

        public function installModuleCommit($moduleName) {

            $installDir = "modules/installing/";
            $installLocation = $installDir . $moduleName . "/";
            
            // Move files over
            $oldDir = "modules/installing/" . $moduleName;
            $newDir = "modules/installed/" . $moduleName;

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

            $info = json_decode(file_get_contents($installLocation . "module.json"), true);

            if (@rename($oldDir, $newDir)) {
                return $this->html .= $this->page->buildElement("success", array(
                    "text" => $info["name"] . " installed successfully"
                ));
            } else {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => $info["name"] . " failed to install"
                ));
            }

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

        public function method_remove () {

            $moduleName = @$this->methodData->moduleName;
            $info = @json_decode(file_get_contents("modules/disabled/$moduleName/module.json"), 1);
            $info["id"] = $moduleName;

            if (!$info) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This module does not exist"
                ));
            }

            if (isset($this->methodData->do)) {
                if (!$this->removeDir("modules/disabled/$moduleName")) {
                    return $this->html .= $this->page->buildElement("error", array(
                        "text" => "This module cant be removed"
                    ));
                }
                return $this->html .= $this->page->buildElement("success", array(
                    "text" => "This module has been removed"
                ));
            }

            $this->html .= $this->page->buildElement("alterModuleConfirm", array(
                "type" => "remove", 
                "module" => $info
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
                $this->deactivateModule($moduleName);
            }

            $this->html .= $this->page->buildElement("alterModuleConfirm", array(
                "type" => "deactivate", 
                "module" => $info
            ));
        }

        public function deactivateModule($moduleName) {
            if (file_exists("modules/disabled/$moduleName/module.json")) {
                $this->removeDir("modules/disabled/$moduleName");
            }
            if (!@rename("modules/installed/$moduleName", "modules/disabled/$moduleName")) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This module cant be deactivated"
                ));
            }
            return $this->html .= $this->page->buildElement("success", array(
                "text" => "This module has been deactivated"
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