<?php

class page {
    
    public $theme, $template, $success = false, $loginPages = array('login', 'register'), $jailPages = array(), $loginPage, $jailPage, $dontRun = false, $modules = array();
    private $pageHTML, $pageItems, $pageReplace;
    
    public function loadModuleMetaData() {
        $moduleDirectories = scandir("modules/");
        foreach ($moduleDirectories as $moduleName) {
            if ($moduleName[0] == ".") continue;
            $moduleInfoFile = "modules/" . $moduleName . "/moduleInfo.php";
            if (file_exists($moduleInfoFile)) {
                include $moduleInfoFile;
                $this->modules[$moduleName] = $info;
            }
        }
    }

    public function loadPage($page, $dontRun = false) {

        $this->dontRun = $dontRun;
        
        if (ctype_alpha($page)) {
            
            return $this->load($page);
            
        } else {
            
            die("Invalid page name");
            
        }
        
    }
    
    private function load($page) {
        
        global $user;
        
        $moduleInfo = $this->modules[$page];
        $this->moduleController = 'modules/' . $page . '/' . $page . '.inc.php';
        $this->moduleView = 'modules/' . $page . '/' . $page . '.tpl.php';

        if (file_exists($this->moduleController)) {
            if (file_exists($this->moduleView)) {
                
                include_once 'class/template.php';
                include_once $this->moduleView;
                
                $templateMethod = $page . 'Template';
                
                $this->template = new $templateMethod($page);
                
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
                    
                }
                
                $pageName = $page;

                if (isset($moduleInfo["pageName"])) {
                    
                    $pageName = $moduleInfo["pageName"];
                    
                }
                
                $this->addToTemplate('page', $pageName);

                $actionMenu = new hook("actionMenu");
                $locationMenu = new hook("locationMenu");
                $accountMenu = new hook("accountMenu");
                $customMenu = new hook("customMenus");

                $menus = array(
                    "actions" => array(
                        "title" => "Actions", 
                        "items" => $this->sortArray($actionMenu->run($user)), 
                        "sort" => 100
                    ), 
                    "location" => array(
                        "title" => "{location}", 
                        "items" => $this->sortArray($locationMenu->run($user)), 
                        "sort" => 200
                    ),
                    "account" => array(
                        "title" => "Account", 
                        "items" => $this->sortArray($accountMenu->run($user)), 
                        "sort" => 300
                    )
                );

                foreach ($customMenu->run($user) as $key => $menu) {
                    if ($menu) $menus[$key] = $menu;
                }
    
                $allMenus = new hook("menus", function ($menus) {
                    return $this->sortArray($menus);
                });

                $allMenus = $allMenus->run($menus, true);


                $this->addToTemplate('menus', $this->setActiveLinks($allMenus));

                $this->pageHTML = $this->template->mainTemplate->pageMain;
                
            } else {
                die("Module template not found!" . 'template/modules/' . $page . '.php');
            }
            
        } else {
            die("404 The page $page was not found!");
        }
        
    }

    public function setActiveLinks($menus) {
        if ($_SERVER["QUERY_STRING"]) {
            $queryString = "?" . $_SERVER["QUERY_STRING"];
        } else {
            $queryString = "?page=loggedin";
        }

        foreach ($menus as $key => $menu) {
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
        uasort($arr, array($this, "cmp"));
        return $arr;

    }
    
    public function addToTemplate($find, $replace) {
        $this->pageItems[$find] = $replace;
    }
    
    private function replaceVars() {
        $template = new pageElement($this->pageItems);
        $this->pageHTML = $template->parse($this->pageHTML);
        
    }
    
    public function printPage() {
        
        $this->replaceVars();
        
        echo $this->pageHTML;
        
    }

    public function buildElement($templateName, $vars = array()) {
        $template = new pageElement($vars, $this->template, $templateName);
        return $template->parse();        
    }

    public function redirectTo($page, $vars = array()) {
        
        $get = '';
        
        foreach ($vars as $key => $val) {
            $get .= '&' . $key . '=' . $val;
        }
        
        $redirect = '?page=' . $page . '';
        
        header("Location:" . $redirect . $get);
        
        exit;
        
    }
    
}

$page = new page();


class pageElement {
    
    public function __construct($items, $template = false, $templateName = false) {
        $this->items = $items;
        $this->template = $template;
        $this->templateName = $templateName;
    }

    public function each($matches) {
        $var = $matches[1];


        $items = $this->items;
        $item = $this->stringToArrayConversion($var, $items);
        $template = $matches[2];
        $rtn = "";
        if (!$item) return "";
        foreach ($item as $key => $items) {
            $html = new pageElement($items, $this->template, $this->templateName);
            $rtn .= $html->parse($template);
        }
        return $rtn;
    }
    
    public function if($matches) {
        $var = $matches[1];
        $template = $matches[2];
        $items = $this->items;
        $item = $this->stringToArrayConversion($var, $items);
        if (is_array($item)) $item = count($item);
        if ($item && $item != "0") {
            $html = new pageElement($items, $this->template, $this->templateName);
            $rtn = $html->parse($template);
            return $rtn;
        }
        return "";
    }
    
    public function unless($matches) {
        $var = $matches[1];
        $template = $matches[2];
        $items = $this->items;
        $item = $this->stringToArrayConversion($var, $items);
        if (is_array($item)) $item = count($item);
        if (!$item || $item == "0") {
            $html = new pageElement($items, $this->template, $this->templateName);
            $rtn = $html->parse($template);
            return $rtn;
        }
        return "";
    }
    
    public function replace($matches) {
        $var = $matches[1];
        $items = $this->items;
        return $this->stringToArrayConversion($var, $this->items);  
    }

    public function parse($html = false) {
        $find = array();
        $replace = array();

        $templateName = $this->templateName;

        if ($templateName && !isset($this->template->$templateName)) {

            global $page;

            $html = $page->buildElement("error", array(
                "text" => "Template '" . $templateName . "' does not exist in '" . $page->moduleView . "'"
            ));

        } else {
            if (!$html) $html = $this->template->$templateName;
            
            // process each blocks
            $html = preg_replace_callback(
                '#\{\#each (.+)\}(((?R)|.+)+)\{\/each}#iUs', 
                array($this, "each"), 
                $html
            );
            // process if blocks
            $html = preg_replace_callback(
                '#\{\#if (.+)\}(((?R)|.+)+)\{\/if}#iUs', 
                array($this, "if"), 
                $html
            );
            // process unless blocks
            $html = preg_replace_callback(
                '#\{\#unless (.+)\}(((?R)|.+)+)\{\/unless}#iUs', 
                array($this, "unless"), 
                $html
            );
            // replace variables
            $html = preg_replace_callback(
                '#\{(.+)\}#iUs', 
                array($this, "replace"), 
                $html
            );

        }

        return $html;
    }

    public function stringToArrayConversion ($string, $arr) {
        $parts = explode(".", $string);
        foreach ($parts as $part) {
            if (!isset($arr[$part])) return "";
            $arr = $arr[$part];
        }
        return $arr;
    }

}

?>