<?php

class page {
    
    public $theme, $template, $success = false, $loginPages = array('login', 'register'), $jailPages = array(), $loginPage, $jailPage, $dontRun = false;
    private $pageHTML, $pageItems, $pageReplace;
    
    public function loadPage($page, $dontRun = false) {
        
        global $user;

        $this->dontRun = $dontRun;
        
        if (ctype_alpha($page)) {
            
            return $this->load($page);
            
        } else {
            
            die("Invalid page name");
            
        }
        
    }
    
    private function load($page) {
        
        if (file_exists('modules/' . $page . '.inc.php')) {
            if (file_exists('template/modules/' . $page . '.php')) {
                
                include_once 'class/template.php';
                include_once 'template/modules/' . $page . '.php';
                
                $templateMethod = $page . 'Template';
                
                $this->template = new $templateMethod($this->dontRun);
				
				$this->loginPage = $this->template->loginPage;
				$this->jailPage = $this->template->jailPage;
                
                if ($this->dontRun) {
                    return $this;
                }

                include 'class/module.php';
                include 'modules/' . $page . '.inc.php';
                
                $module = new $page();
                
                if (isset($module)) {
                    
                    $this->addToTemplate('game', $module->htmlOutput());
                    
                }
                
                $pageName = $page;
                
                if (isset($module->pageName)) {
                    
                    $pageName = $module->pageName;
                    
                }
                
                $this->addToTemplate('page', $pageName);
                
                $this->pageHTML = $this->template->mainTemplate->pageMain;
                
            } else {
                
                die("Module template not found!" . 'template/modules/' . $page . '.php');
                
            }
            
        } else {
            
            die("404 The page $page was not found!");
            
        }
        
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


    
    public function each($matches) {
        print_r($matches);
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
        $template = $matches[2];
        $rtn = "";
        if (!isset($this->items[$var])) return "";
        foreach ($this->items[$var] as $key => $items) {
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


        return str_replace($find, $replace, $html);
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