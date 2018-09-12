<?php

    class admin extends module {
        
        public $allowedMethods = "*";
        
        public function constructModule() {
            
            /* Redirect the user to the home page if they are a user */
            if ($this->user->info->U_userLevel != 2) {
                header("Location:?page=loggedin");
                exit;
            }

            $adminModule = @$this->methodData->module;
            
            new hook("menus", function ($menus) {
                return array(
                    "homeLinks" => array(
                        "title" => "Admin", 
                        "items" => array(
                            array(
                                "url" => "?page=admin", 
                                "text" => "Admin Home", 
                                "sort" => 100
                            ),
                            array(
                                "url" => "?page=loggedin", 
                                "text" => "Back To Game", 
                                "sort" => 100
                            ),
                            array(
                                "url" => "?page=logout", 
                                "text" => "Logout", 
                                "sort" => 100
                            )
                        ), 
                        "sort" => 100
                    ), 
                );
            });

            if ($adminModule) {
                $this->viewModule();
            }
            
            $this->viewModules();

        }

        private function viewModule() {
            $adminModule = $this->methodData->module;
            $this->moduleInfo = @$this->page->modules[$adminModule];

            if (!$this->moduleInfo || !$this->moduleInfo["admin"]) {
                return $this->html = $this->page->buildElement("error", array("text"=>"This module does not exits or have an admin panel"));
            }
            
            new hook("menus", function ($menus) {
                $items = array();
                foreach ($this->moduleInfo["admin"] as $adminLink) {
                    if (!isset($adminLink["seperator"])) {
                        $adminLink["url"] = "?page=admin&module=".$this->methodData->module."&action=".$adminLink["method"];
                    }
                    $items[] = $adminLink;
                }
                $menus["moduleActions"] = array(
                    "title" => $this->moduleInfo["pageName"], 
                    "items" => $items, 
                    "sort" => 200
                );

                return $menus;
            });

            include_once "modules/$adminModule/$adminModule.admin.php";

            if (isset($this->methodData->action)) {
                $action = "method_" . $this->methodData->action;
            } else {
                $action = "method_" . $this->moduleInfo["admin"][0]["method"];
            }

            $moduleViewFile = "modules/$adminModule/$adminModule.tpl.php";

            if (file_exists($moduleViewFile)) {
                
                include_once 'class/template.php';
                include_once $moduleViewFile;
                
                $templateMethod = $adminModule . 'Template';
                
                $this->page->template = new $templateMethod("admin");
                $this->page->moduleView = $moduleViewFile;
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
            foreach ($this->page->modules as $moduleName => $moduleInfo) {
                if (isset($moduleInfo["admin"])) {
                    $this->moduleLinks[] = array(
                        "url" => "?page=admin&module=".$moduleName, 
                        "text" => $moduleInfo["pageName"]
                    );
                } 
            }
            
            new hook("menus", function ($menus) {
                $menus["moduleLinks"] = array(
                    "title" => "Modules", 
                    "items" => $this->moduleLinks, 
                    "sort" => 300
                );
                return $menus;
            });
            
        }
        
    }

?>
