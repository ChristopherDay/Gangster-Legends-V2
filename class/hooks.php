<?php

	$hooks = array();

	class hook {
		private $hookName = null;
		public function __construct($hookName, $callback = false) {
			global $hooks;

			$this->hookName = $hookName;

			if (!isset($hooks[$hookName])) {
				$hooks[$hookName] = array();
			}			

			if ($callback) $hooks[$hookName][] = $callback;

			return $this;

		}
		public function run() {
			global $hooks;
			$rtn = array();
			$args = func_get_args();
			array_shift($args);
			foreach ($hooks[$this->hookName] as $hook) {
				$rtn[] = $hook(...$args);
			}
			return $rtn;
		}
	}