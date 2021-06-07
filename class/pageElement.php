<?php

    
    require __DIR__ . '/handlebars/Autoloader.php';
    Handlebars\Autoloader::register();

    use Handlebars\Handlebars;
    use Handlebars\Loader\FilesystemLoader;

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

        public $bbcode = false;

        /* 
            full bbcodes list available here 
            http://nbbc.sourceforge.net/readme.php?page=bbc
        */
        public function convertBBCodeToHTML($text) {


            if (!$this->bbcode) {
                $this->bbcode = new BBCode();
                $settings = new Settings();
                
                $dir = 'themes/' . $settings->loadSetting("theme") . '/images/smileys';
                $this->bbcode->smiley_dir = $dir;
                $this->bbcode->smiley_url = $dir;
                
                $h = new Hook("customSmiley");

                $smileys = $h->run();

                if (is_array($smileys)) {
                    foreach ($smileys as $smiley) {
                        $this->bbcode->AddSmiley($smiley["code"], $smiley["image"]);
                    }
                }

            }




            return $this->bbcode->Parse($text);
        }

        public $handlebars = false;

        public function templateToHTML($html, $templateItems) {

            if (!$this->handlebars) {
                $this->handlebars = new Handlebars(array(
                    "glTemplate" => $this->template
                ));
                $this->handlebars->addHelper("bbcode",
                    function($template, $context, $args, $source){
                        return $this->convertBBCodeToHTML($context->get($args));
                    }
                );
            }  

            /* convert old <{var}> to the new {{var} */
            $pattern = '#\<\{(.+)\}\>#siU';
            $replacement = '{{${1}}}';
            $html = preg_replace($pattern, $replacement, $html);

            /* convert old [{var}] to the new {#bbcode var} */
            $pattern = '#\[\{(.+)\}\]#siU';
            $replacement = '{#bbcode ${1}}';
            $html = preg_replace($pattern, $replacement, $html);

            $parsed = $this->handlebars->render($html, $templateItems);

            return $parsed;
        }

        public function parse($html = false, $subTemplateName = false, $elementItems = array()) {
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

                $html = $this->templateToHTML($html, $this->items);

            }

            return $html;
        }

        public $count = 0;

        public function getPregError($whatWentWorng) {
            $error = preg_last_error();
            if ($error) debug(array(
                $whatWentWorng,
                $error
            ));
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