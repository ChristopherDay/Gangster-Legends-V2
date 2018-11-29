<?php

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

            $items = $this->items;
            $rtn = htmlspecialchars($this->stringToArrayConversion($var, $this->items));
            if (count($parts) == 2) {
                $rtn = $parts[0]($rtn);
            } 
            return $rtn;
        }
        
        public function subTemplate($matches) {
            $var = $matches[1];
            return $this->parse($this->template->$var);
        }

        /** 
        * A simple PHP BBCode Parser function
        *
        * @author Afsal Rahim
        * @link http://digitcodes.com/create-simple-php-bbcode-parser-function/
        **/
        public function convertBBCodeToHTML($text) {

            // BBcode array
            $find = array(
                '~\[left\](.*?)\[/left\]~s',
                '~\[right\](.*?)\[/right\]~s',
                '~\[center\](.*?)\[/center\]~s',
                '~\[b\](.*?)\[/b\]~s',
                '~\[i\](.*?)\[/i\]~s',
                '~\[u\](.*?)\[/u\]~s',
                '~\[quote\](.*?)\[/quote\]~s',
                '~\[quote=(.*?)\](.*?)\[/quote\]~s',
                '~\[size=(.*?)\](.*?)\[/size\]~s',
                '~\[color=(.*?)\](.*?)\[/color\]~s',
                '~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
                '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
            );

            // HTML tags to replace BBcode
            $replace = array(
                '<div style="text-align: left;">$1</div>',
                '<div style="text-align: right;">$1</div>',
                '<div style="text-align: center;">$1</div>',
                '<strong>$1</strong>',
                '<em>$1</em>',
                '<span style="text-decoration:underline;">$1</span>',
                '<div class="quote">$1</div>',
                '<div class="quote"><strong>$1</strong><br />$2</div>',
                '<span style="font-size:$1px;">$2</span>',
                '<span style="color:$1;">$2</span>',
                '<a href="$1">$1</a>',
                '<img class="img-responsive" src="$1" alt="" />'
            );

            // Replacing the BBcodes with corresponding HTML tags
            return preg_replace($find,$replace,nl2br($text));
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

                ini_set('pcre.jit', false);

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
                    array($this, "if"), 
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