<?php

	
	class custom_error_handler {
		
		private $user, $db, $page, $minorLog = true, $majorLog = true;
		
		public function __construct() {
			
			ini_set('display_errors', 0);
		
			set_error_handler(array($this, 'handler'), E_ALL);
			
			register_shutdown_function(array($this, 'shutdown'));
			
		}
		
		public function handler($number,$string,$file,$line,$context,$major = false) {
			
			global $config;
			
			switch ($number) {
				case E_ERROR: $type ='E_ERROR';
				case E_WARNING: $type ='E_WARNING';
				case E_PARSE: $type ='E_PARSE';
				case E_NOTICE: $type ='E_NOTICE';
				case E_CORE_ERROR: $type ='E_CORE_ERROR';
				case E_CORE_WARNING: $type ='E_CORE_WARNING';
				case E_COMPILE_ERROR: $type ='E_COMPILE_ERROR';
				case E_COMPILE_WARNING: $type ='E_COMPILE_WARNING';
				case E_USER_ERROR: $type ='E_USER_ERROR';
				case E_USER_WARNING: $type ='E_USER_WARNING';
				case E_USER_NOTICE: $type ='E_USER_NOTICE';
				case E_USER_DEPECTED: $type ='E_USER_DEPECTED';
				case E_DEPECTED: $type ='E_DEPECTED';
				case E_STRICT: $type ='E_STRICT';
				case E_RECOVERABLE_ERROR: $type ='E_RECOVERABLE_ERROR';
				case E_ALL: $type ='E_ALL';
			}
			
			$errorArray = array(
				$type,
				$string,
				$file,
				$line,
				date('Y-m-d H:i:s')
			);
			
			$return = '';
			$return .= '<div class="well" style="color:#000;">';
			$return .= '<h1>There was an error!</h1>';
			$return .= '<p style="font-family: monospace;">';
			$return .= '<strong>File:</strong> '.$file;
			$return .= '<br /><strong>Line:</strong> '.$line;
			$return .= '<br /><strong>Error: '.$type.'</strong> '.$string;
			$return .= '</p>';
			
			$return .= '</div>';
			
				
			if (!$config->debug && $major) {
			
				echo '<h1>Sorry something went very wrong!</h1>';
				echo 'The error has been logged and waiting for a developer to review this issue.';
				$this->log(json_encode($errorArray), $major);
				exit;
				
			} else if ($config->debug) {
			
				echo $return;
				$this->log(json_encode($errorArray), $major);
				
			} else {
				
				$this->log(json_encode($errorArray), $major);
			
			}
			
			
		}
		
		public function shutdown() {
			
			global $page;
			
			if (!$page->success) {
					
				$error = error_get_last();
				
				$this->handler($error['type'], $error['message'], $error['file'], $error['line'], NULL, true);
				
			}
			
		}
		
		private function log($text, $major = false) {
		
			if ($major) {
				$file = dirname( __FILE__ ).'/../logs/errors/major.txt';
			} else {
				$file = dirname( __FILE__ ).'/../logs/errors/minor.txt';
			}
			
			if (is_writable($file)) {
				
				$logFile = fopen($file, 'a');
				
				fwrite($logFile, $text.PHP_EOL);
				
				fclose($logFile);
				
			} else {
				
				if ($major) {
					if ($this->majorLog) {
						$file = 'major.txt';
						$return = '<pre>The log file '.$file.' is not writable!</pre>';	
						$this->majorLog = false;
					}
				} else {
					if ($this->minorLog) {
						$file = 'minor.txt';
						$return = '<pre>The log file '.$file.' is not writable!</pre>';	
						$this->minorLog = false;
					}
				}
				if ($config->debug) {
					echo $return;
				}	
			}
		
		}
		
	}

	function debug ($error) {
		echo "<pre>"; 
		var_dump($error);
		echo "</pre>";
	}

?>