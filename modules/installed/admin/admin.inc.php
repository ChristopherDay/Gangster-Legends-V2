<?php

    class admin extends module {
        
        public $allowedMethods = "*";
        
        public function constructModule() {

            if (!isset($this->methodData->module)) $this->methodData->module = "admin";
            
            $adminModule = $this->methodData->module;

            if (!count($this->user->adminModules)) {
                header("Location:?page=" . $this->page->landingPage);
                exit;
            }

            if (
                !in_array($this->methodData->module, $this->user->adminModules) &&
                !in_array("*", $this->user->adminModules)
            ) {
                header("Location:?page=" . $this->page->landingPage);
                exit;
            }

            new hook("menus", function ($menus) {
                return array();
            });

            $this->viewModule();
            $this->viewModules();

        }

        private function viewModule() {

            $adminModule = $this->methodData->module;
            $this->moduleInfo = @$this->page->modules[$adminModule];

            if (!$this->moduleInfo || !$this->moduleInfo["admin"]) {

                return $this->html = $this->page->buildElement("error", array("text"=>"This module does not exits or have an admin panel"));
            }
            
            $items = array();
            foreach ($this->moduleInfo["admin"] as $adminLink) {
                if (!isset($adminLink["seperator"])) {
                    $adminLink["url"] = "?page=admin&module=".$this->methodData->module."&action=".$adminLink["method"];
                }
                $items[] = $adminLink;
            }

            $moduleActions = array(
                "title" => $this->moduleInfo["pageName"], 
                "items" => $items, 
                "sort" => 200
            );

            $this->page->addToTemplate("moduleActions", $this->page->setActiveLinks(array($moduleActions))[0]); 

            include_once "modules/installed/$adminModule/$adminModule.admin.php";

            if (isset($this->methodData->action)) {
                $action = "method_" . $this->methodData->action;
            } else {
                $action = "method_" . $this->moduleInfo["admin"][0]["method"];
            }

            $moduleViewFile = "modules/installed/$adminModule/$adminModule.tpl.php";

            if (file_exists($moduleViewFile)) {
                
                include_once 'class/template.php';
                include_once $moduleViewFile;
                
                $templateMethod = $adminModule . 'Template';
                
                $this->page->template = new $templateMethod("admin");
                $this->page->moduleView = $moduleViewFile;
            }

            $moduleJSFile = "modules/installed/" . $adminModule . "/" . $adminModule . ".admin.script.js";

            if (file_exists($moduleJSFile)) {
                $this->page->addToTemplate("moduleJSFile", $moduleJSFile);
            }

            $adminModule = new adminModule();
            $adminModule->db = $this->db;
            $adminModule->user = $this->user;
            $adminModule->html = $this->html;
            $adminModule->page = $this->page;
            $adminModule->methodData = $this->methodData;

            if (method_exists($adminModule, $action)) {
                $adminModule->$action();
                $this->html = $adminModule->html;
            }

        }

        private $moduleLinks = array();
            
        private function viewModules() {

            $this->moduleLinks = array();
            
            new hook("menus", function ($menus) {
            
                foreach ($this->page->modules as $moduleName => $moduleInfo) {
                    if (!isset($moduleInfo["adminGroup"])) continue;
                    if (
                        !in_array($moduleInfo["id"], $this->user->adminModules) &&
                        !in_array("*", $this->user->adminModules)
                    ) continue;

                    if (!isset($menus[$moduleInfo["adminGroup"]])) {
                        $menus[$moduleInfo["adminGroup"]] = array(
                            "title" => $moduleInfo["adminGroup"], 
                            "items" => array(), 
                            "sort" => 300
                        );
                    }
                    
                    $add = "";


                    if (!isset($this->methodData->action) || $this->methodData->module != $moduleName) {
                        //$add = "&action=" . $moduleInfo["admin"][0]["method"];
                    } else {
                        $add = "&action=" . $this->methodData->action;
                    }

                    $menus[$moduleInfo["adminGroup"]]["items"][] = array(
                        "url" => "?page=admin&module=".$moduleName . $add,
                        "text" => $moduleInfo["pageName"]
                    );

                }
                return $menus;
            });
            
        }
        
    }

?>
