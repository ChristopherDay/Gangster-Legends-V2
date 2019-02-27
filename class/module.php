<?php

    class module {
        
        public $alerts = array(), $html = '', $page, $user, $db, $methodData, $construct = true;
        
        public function __construct() {
        
            global $page, $db, $user;
            
            $this->db = $db;
            $this->page = $page;
            $this->methodData = (object) array();
            
            if (isset($user->id)) {
                $this->user = $user;
            }
            
            $this->buildMethodData();
            
            if (isset($this->methodData->action)) {
                $methodAction = 'method_'.$this->methodData->action;
                if (method_exists($this, $methodAction)) {
                    $this->$methodAction();
                }
            }
            
            if ($this->construct) {
                $this->constructModule();
            }
            
            if (isset($user->info->U_id)) {
            
                // Update the user info after the module has run.
                $this->user->getInfo();
                
                // Bind the varibles to the template
                $this->user->bindVarsToTemplate();
                
            }
            
        }
    
        public function timeLeft($ts) {
            $oldTZ = date_default_timezone_get();
            date_default_timezone_set("UTC");
            if ($ts > 86400) {
                $ts = date('j \D\a\y H:i:s', $ts);
            } else {
                $ts = date('H:i:s', $ts);
            }
            date_default_timezone_set($oldTZ);
            return $ts; 
        }
        
        public function htmlOutput() {
            return $this->html;
        }

        public function alertsOutput() {
            $html = '';
            foreach ($this->alerts as $key => $value) {
                $html .= $value;
            }
            return $html;
        }       

        private function buildMethodData() {
        
            $data = $this->allowedMethods;
			
            if ($data == "*") {

                foreach ($_GET as $key => $value) {
                    $this->methodData->$key = $value;
                }
                foreach ($_POST as $key => $value) {
                    $this->methodData->$key = $value;
                }

                return;
            }

            foreach ($data as $key => $val) {
				
				if (strtolower($val['type']) == 'get') {
                    if (isset($_GET[$key])) {
                      @$this->methodData->$key = $_GET[$key];
                    }
                } else if (strtolower($val['type']) == 'post') {
                    if (isset($_POST[$key])) {
                        @$this->methodData->$key = $_POST[$key];
                    }
                } else if (strtolower($val['type']) == 'request') {
                    if (isset($_REQUEST[$key])) {
                        @$this->methodData->$key = $_REQUEST[$key];
                    }
                }
            }
            
            if (isset($_GET['action'])) {
            
                @$this->methodData->action = $_GET['action'];
                
            }
            
        }
        
        public function getLocation($locationID, $info = false) {
        
            $loc = $this->db->prepare("SELECT * FROM locations WHERE L_id = :location");
            $loc->bindParam(":location", $locationID);
            $loc->execute();
            
            $location = $loc->fetchObject();
            
            if (isset($location->L_id)) {
                
                if ($info) {
                    return $location;
                } else {
                    return $location->L_name;
                }
                
            } else {
                
                return 'Location dosent exist!';
                
            }
        }
        
        public function getCar($carID, $info = false) {
        
            $car = $this->db->prepare("SELECT * FROM cars WHERE CA_id = :car");
            $car->bindParam(":car", $carID);
            $car->execute();
            
            $carInfo = $car->fetchObject();
            
            if (isset($carInfo->CA_id)) {
                
                if ($info) {
                    return $carInfo;
                } else {
                    return $carInfo->CA_name;
                }
                
            } else {
                
                return 'Car dosent exist!';
                
            }
            
        }
        
        public function getWeapon($weaponID, $info = false) {
        
            $wep = $this->db->prepare("SELECT * FROM weapons WHERE W_id = :weapon");
            $wep->bindParam(":weapon", $weaponID);
            $wep->execute();
            
            $weapon = $wep->fetchObject();
            
            if (isset($weapon->W_id)) {
                
                if ($info) {
                    return $weapon;
                } else {
                    return $weapon->W_name;
                }
                
            } else {
                
                return 'Weapon dosent exist!';
                
            }
        
        }
        
        public function getRank($rankID, $info = false) {
        
            $rank = $this->db->prepare("SELECT * FROM ranks WHERE R_id = :rank");
            $rank->bindParam(":rank", $rankID);
            $rank->execute();
            
            $rankInfo = $rank->fetchObject();
            
            if (isset($rankInfo->R_id)) {
                
                if ($info) {
                    return $rankInfo;
                } else {
                    return $rankInfo->R_name;
                }
                
            } else {
                
                return 'Rank dosent exist!';
                
            }
        
        }
        
        public function money($cash) {
            return "$" . number_format($cash);
        }

        public function date($ts = '-1') {
            
            if ($ts == '-1') {
                $ts = time();
            }
            
            return date('l jS F H:i', $ts);
        }
        
        
        
        public function error($text, $type = "error") {
            $this->alerts[] = $this->page->buildElement($type, array(
                "text" => $text
            ));
        }

    }

?>