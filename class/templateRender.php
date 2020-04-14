<?php

    require("nbbc.php");

    class pageElement {
        
        public function __construct($items, $template = false, $templateName = false) {
            $this->items = $items;
            if ($template) {
                $this->template = $template;
            } else {
                $this->template = new template();
            }
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
        
        public function __if($matches) {
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
        
        public function replaceHTML($matches) {
            $var = $matches[1];
            $items = $this->items;
            return $this->stringToArrayConversion($var, $this->items);  
        }
        
        public function replaceBBCode($matches) {
            $var = $matches[1];
            $items = $this->items;
            return $this->convertBBCodeToHTML(htmlspecialchars($this->stringToArrayConversion($var, $this->items)));
        }
        
        public function replace($matches) {
            $var = $matches[1];

            $parts = explode(" ", $var);

            if (count($parts) == 2) {
                $var = $parts[1];
            } 
            
            if ($var[0] == '"') {
                //pass back the string
                $var = str_replace('"', "", $var);
                $rtn = $var;
            } else {
                $items = $this->items;
                $rtn = htmlspecialchars($this->stringToArrayConversion($var, $this->items));
            }
            
            if (count($parts) == 2) {
                $rtn = $parts[0]($rtn);
            } 

            return $rtn;
        }
        
        public function subTemplate($matches) {
            $var = $matches[1];
            return $this->parse($this->template->$var, $var);
        }

        /* 
            full bbcodes list available here 
            http://nbbc.sourceforge.net/readme.php?page=bbc
        */
        public function convertBBCodeToHTML($text) {

            $bbcode = new BBCode;
            $settings = new Settings();

            $dir = 'themes/' . $settings->loadSetting("theme") . '/images/smileys';
            $bbcode->smiley_dir = $dir;
            $bbcode->smiley_url = $dir;

            $h = new Hook("customSmiley");

            $smileys = $h->run();

            if (is_array($smileys)) {
                foreach ($smileys as $smiley) {
                    $bbcode->AddSmiley($smiley["code"], $smiley["image"]);
                }
            }

            return $bbcode->Parse($text);
        }

        public function parse($html = false, $subTemplateName = false) {
            $find = array();
            $replace = array();

            $templateName = $this->templateName;

            if ($templateName && !isset($this->template->$templateName)) {

                global $page;

                $html = $page->buildElement("error", array(
                    "text" => "Template '" . $templateName . "' does not exist in '" . $page->moduleView . "'"
                ));

            } else {

                $templateToLoad = false;

                if (!$html) {
                    $templateToLoad = ($templateName);
                    $html = $this->template->$templateName;
                } else {
                    $templateToLoad = ($subTemplateName);
                }

                if ($templateToLoad) {
                    $items = $this->items;
                    $hook = new Hook("alterModuleTemplate");
                    $data = array(
                        "templateName" => $templateToLoad, 
                        "items" => $items,
                        "html" => $html
                    );
                    $newTemplate = $hook->run($data, true);

                    if (isset($newTemplate["html"])) {
                        $html = $newTemplate["html"];
                    }

                    if (isset($newTemplate["items"])) {
                        $this->items = $newTemplate["items"];
                    }

                }

                //ini_set('pcre.jit', false);

                /* remove new lines ... not sure why but it stops nested ifs ... */
                $html = trim(preg_replace('/\s+/', ' ', $html));

                // process each blocks
                $html = preg_replace_callback(
                    '#\{\#each (.+)\}(((?R)|.+)+)\{\/each}#iUsS', 
                    array($this, "each"), 
                    $html
                );

                // process if blocks
                $html = preg_replace_callback(
                    '#\{\#if (.+)\}(((?R)|.+)+)\{\/if}#iUsS', 
                    array($this, "__if"), 
                    $html
                );
                // process unless blocks
                $html = preg_replace_callback(
                    '#\{\#unless (.+)\}(((?R)|.+)+)\{\/unless}#iUsS', 
                    array($this, "unless"), 
                    $html
                );

                // replace variables
                $html = preg_replace_callback(
                    '#\{\>(.+)\}#iUsS', 
                    array($this, "subTemplate"), 
                    $html
                );

                // replace variables
                $html = preg_replace_callback(
                    '#\<\{(.+)\}\>#iUsS', 
                    array($this, "replaceHTML"), 
                    $html
                );

                // replace variables
                $html = preg_replace_callback(
                    '#\[\{(.+)\}\]#iUsS', 
                    array($this, "replaceBBCode"), 
                    $html
                );

                // replace variables
                $html = preg_replace_callback(
                    '#\{(.+)\}#iUsS', 
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