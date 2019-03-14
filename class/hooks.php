<?php

    $hooks = array();

    class hook {
        private $hookName = null;
        public $args;
        public function __construct($hookName, $callback = false) {
            global $hooks;

            $this->args = func_get_args();

            $this->hookName = $hookName;

            if (!isset($hooks[$hookName])) {
                $hooks[$hookName] = array();
            }            

            if ($callback) $hooks[$hookName][] = $callback;

            return $this;

        }

        public function run(&$opts, $returnSingleItem = false) {
            global $hooks;
            if ($returnSingleItem) {
                $rtn = array();
            } else {
                $rtn = null;
            }

            foreach ($hooks[$this->hookName] as $hook) {
                if ($returnSingleItem) {
                    $opts = $hook($opts);
                    $rtn = $opts;
                } else {
                    $rtn[] = $hook($opts);
                }
            }
            return $rtn;
        }
    }