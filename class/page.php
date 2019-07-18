<?php

class page {

    public function __construct() {
        $this->addToTemplate("timestamp", time());
    }
    
    public $printPage = true, $theme, $template, $success = false, $loginPages = array('login', 'register'), $jailPages = array(), $loginPage, $jailPage, $dontRun = false, $modules = array(), $moduleView, $loadedTheme;
    private $pageHTML, $pageItems, $pageReplace;
    
    public function loadModuleMetaData() {
        $moduleDirectories = scandir("modules/installed/");
        foreach ($moduleDirectories as $moduleName) {
            if ($moduleName[0] == ".") continue;
            $moduleInfoFile = "modules/installed/" . $moduleName . "/module.json";
            $moduleHooksFile = "modules/installed/" . $moduleName . "/" . $moduleName . ".hooks.php";

            if (file_exists($moduleInfoFile)) {
                $info = json_decode(file_get_contents($moduleInfoFile), true);
                $info["id"] = $moduleName;
                $this->modules[$moduleName] = $info;
            }
            if (file_exists($moduleHooksFile)) {
                include_once $moduleHooksFile;
            }
        }
    }

    public function username($user) {
        return $this->buildElement("userName", array(
            "user" => $user->user
        ));
    }

    public function loadPage($page, $dontRun = false) {

        $this->dontRun = $dontRun;
        $hook = new Hook("moduleLoad");
        $page = $hook->run($page, true);

        if (ctype_alpha($page)) {
            return $this->load($page);
        } else {
            die("Invalid page name");
        }
        
    }
    
    private function load($page) {
        
        global $user;
        
        $moduleInfo = $this->modules[$page];
        $this->moduleController = 'modules/installed/' . $page . '/' . $page . '.inc.php';
        $this->moduleView = 'modules/installed/' . $page . '/' . $page . '.tpl.php';

        if (file_exists($this->moduleController)) {
            if (file_exists($this->moduleView)) {
                
                include_once 'class/template.php';
                include_once $this->moduleView;
                
                $moduleCSSFile = "modules/installed/" . $page . "/" . $page . ".styles.css";

                if (file_exists($moduleCSSFile)) {
                    $this->addToTemplate("moduleCSSFile", $moduleCSSFile);
                }
                
                $moduleJSFile = "modules/installed/" . $page . "/" . $page . ".script.js";

                if (file_exists($moduleJSFile)) {
                    $this->addToTemplate("moduleJSFile", $moduleJSFile);
                }

                $templateMethod = $page . 'Template';
                
                $this->template = new $templateMethod($page);

                if (!$this->template) {
                    $this->template = new template();
                }

                $this->loginPage = $moduleInfo["requireLogin"];
                $this->jailPage = $moduleInfo["accessInJail"];
                
                if ($this->dontRun) {
                    return $this;
                }

                include 'class/module.php';
                include $this->moduleController;
                
                $module = new $page();

                
                if (isset($module)) {
                    $this->addToTemplate('game', $module->htmlOutput());
                    $this->addToTemplate('alerts', $module->alertsOutput());
                }
                
                $pageName = $page;

                if (isset($moduleInfo["pageName"])) {
                    
                    $pageName = $moduleInfo["pageName"];
                    
                }
                
                $this->addToTemplate('page', $pageName);

                $actionMenu = new hook("actionMenu");
                $locationMenu = new hook("locationMenu");
                $accountMenu = new hook("accountMenu");
                $killMenu = new hook("killMenu");
                $customMenu = new hook("customMenus");
                $gangMenu = new hook("gangMenu");

                $locationName = "";

                if (isset($this->pageItems["location"])) {
                    $locationName = $this->pageItems["location"];
                }

                $menus = array(
                    "actions" => array(
                        "title" => "Actions", 
                        "items" => $this->sortArray($actionMenu->run($user)), 
                        "sort" => 100
                    ), 
                    "location" => array(
                        "title" => $locationName, 
                        "items" => $this->sortArray($locationMenu->run($user)), 
                        "sort" => 200
                    ),
                    "gang" => array(
                        "title" => "Gangs", 
                        "items" => $this->sortArray($gangMenu->run($user)), 
                        "sort" => 205
                    ),
                    "kill" => array(
                        "title" => "Murder", 
                        "items" => $this->sortArray($killMenu->run($user)), 
                        "sort" => 210
                    ),
                    "account" => array(
                        "title" => "Account", 
                        "items" => $this->sortArray($accountMenu->run($user)), 
                        "sort" => 400
                    )
                );

                $customMenus = array();
                foreach ($customMenu->run($user) as $key => $menu) {
                    if ($menu) {
                        $menus[$key] = $menu;
                        $customMenus[$key] = $menu;
                    }
                }
    
                $allMenus = new hook("menus", function ($menus) {
                    return $this->sortArray($menus);
                });

                $allMenus = $allMenus->run($menus, true);

                $customMenus = $this->sortArray($customMenus);

                $this->addToTemplate('menus', $this->setActiveLinks($allMenus));
                $this->addToTemplate('customMenus', $this->setActiveLinks($customMenus));

                $this->pageHTML = $this->template->mainTemplate->pageMain;
                
            } else {
                die("Module template not found!" . 'modules/installed/' . $page . 'tpl..php');
            }
            
        } else {
            die("404 The page $page was not found!");
        }
        
    }

    public function setActiveLinks($menus) {
        if ($_SERVER["QUERY_STRING"]) {
            $queryString = "?" . $_SERVER["QUERY_STRING"];
        } else {
            $queryString = "?page=" . $this->landingPage;
        }

        if (!is_array($menus)) $menus = array();

        foreach ($menus as $key => $menu) {
            if (!is_array($menu["items"])) $menu["items"] = array();
            foreach ($menu["items"] as $k => $item) {
                //debug(array(
                //    strpos($item["url"], $queryString), $queryString, $item["url"]
                //));
                if ($item["url"] && strpos($queryString, $item["url"]) !== false) {
                    $menu["items"][$k]["active"] = true;
                    break;
                }
            }
            $menus[$key] = $menu;
        }
        return $menus;
    }

    public function cmp ($a, $b) {
        if (!isset($a["sort"])) $a["sort"] = 0;
        if (!isset($b["sort"])) $b["sort"] = 0;
        return $a["sort"] - $b["sort"];
    }

    public function sortArray($arr) {
        if (!$arr) return $arr;
        uasort($arr, array($this, "cmp"));
        return $arr;

    }
    
    public function addToTemplate($find, $replace) {
        $this->pageItems[$find] = $replace;
    }
    
    private function replaceVars() {
        $template = new pageElement($this->pageItems);

        if (isset($_SERVER["HTTP_RETURN_JSON"])) {
            header("content-type: application/json");
            echo json_encode($this->pageItems, JSON_PRETTY_PRINT);
            exit;
        }
        $this->pageHTML = $template->parse($this->pageHTML);
        
    }
    
    public function printPage() {
        
        $this->replaceVars();
        
        if (!$this->printPage) return;

        echo $this->pageHTML;
        
    }

    public function buildElement($templateName, $vars = array()) {

        $vars["_theme"] = $this->loadedTheme;
        $this->addToTemplate("_theme", $this->loadedTheme);

        $template = new pageElement($vars, $this->template, $templateName);
        return $template->parse();        
    }

    public function redirectTo($page, $vars = array()) {
        
        $get = '';

        $this->printPage = false;
        
        foreach ($vars as $key => $val) {
            $get .= '&' . $key . '=' . $val;
        }
        
        $redirect = '?page=' . $page . '';
        
        header("Location:" . $redirect . $get);
        
    }
    
}

$page = new page();

?>