<?php

    class module {
        
        public $html = '', $page, $user, $db;
        
        public function __construct() {
        
            global $page, $db, $user;
            
            $this->db = $db;
            $this->page = $page;
            
            if (isset($user->id)) {
                $this->user = $user;
            }
            
            $this->constructModule();
            
        }
    
        public function timeLeft($ts) {
            return date('H:i:s', $ts);
        }
        
        public function htmlOutput() {
            return $this->html;
        }
        
        public function getLocation($locationID, $info = false) {
        
            // Coming soon
            
        }
        
        public function getCar($carID, $info = false) {
        
            // Coming soon
            
        }
        
        public function getWeapon($weaponID, $info = false) {
        
            // Coming soon
        
        }
        
        public function getRank($rankID, $info = false) {
        
            // Coming soon
        
        }
        
        public function checkTimer($timer, $returnTime = false) {
        
            // Coming soon
        
        }
        
        public function date($ts = '-1') {
            
            if ($ts == '-1') {
                $ts = time();
            }
            
            return date('Y-m-d H:i:s', $ts);
        }
        
    }

?>