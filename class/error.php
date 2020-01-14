<?php

    
    class ErrorHandler {
        
        private $user, $db, $page, $minorLog = true, $majorLog = true;
        
        public function __construct() {

            ini_set('display_errors', 0);
        
            set_error_handler(array($this, 'handler'), E_ALL);
            
            register_shutdown_function(array($this, 'shutdown'));
            
        }
        
        public function handler($number,$string,$file,$line,$context,$major = false) {
            
            global $config;

            if (error_reporting() === 0) return;

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
                case E_STRICT: $type ='E_STRICT';
                case E_RECOVERABLE_ERROR: $type ='E_RECOVERABLE_ERROR';
            }
            
            if (!isset($type)) {
                return;
            }

            $errorArray = array(
                $type,
                $string,
                $file,
                $line,
                date('Y-m-d H:i:s')
            );
            
            $return = '';
            $return .= '<div class="well error-well">';
            $return .= '<h1>There was an error!</h1>';
            $return .= '<p style="font-family: monospace;">';
            $return .= '<strong>File:</strong> '.$file;
            $return .= '<br /><strong>Line:</strong> '.$line;
            $return .= '<br /><strong>Error: </strong> '.$string;
            $return .= '<br /><strong>Type:</strong> '.$type;
            $return .= '</p>';
            
            $return .= '</div>';
            
            $this->log(json_encode($errorArray), $major);
                
            if (!$config["debug"] && $major) {
                echo '<h1>Sorry something went very wrong!</h1>';
                echo '<p>The error has been logged and waiting for a developer to review this issue.</p>';
                echo '<p>If you are the developer you can enable better dubuging by editing config.php and enabling debug</p>';
                exit;
                
            } else if ($config["debug"]) {
                echo $return;
            }
            
            
        }
        
        public function shutdown() {
            $error = error_get_last();

            if (!is_null($error)) {
                $this->handler($error['type'], $error['message'], $error['file'], $error['line'], NULL, true);
            }
        }
        
        private function log($text, $major = false) {
        
            global $config;

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
                if ($config["debug"]) {
                    echo $return;
                }    
            }
        
        }
        
    }

    $errorHandler = new ErrorHandler();

    function debug ($error, $usePrint = true, $returnString = false) {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        
        $e = "";

        $e .= "<pre>"; 
        if ($usePrint) {
            $e .= print_r($error, true);
        } else {
            var_dump($error);
        }
        $e .= "<strong>File:" . $caller['file'] . " Line:";
        $e .= $caller['line'] . "</strong>";
        $e .= "</pre>";

        if ($returnString) return $e;

        echo $e;

    }

?>
