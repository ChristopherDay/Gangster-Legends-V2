<?php

    class travel extends module {
        
        public $allowedMethods = array('location'=>array('type'=>'get'));
        
        public $pageName = 'Airport';
        
        public function constructModule() {

            if (!$this->user->checkTimer('travel')) {
                
                $time = ($this->user->getTimer('travel'));
                $this->html .= $this->page->buildElement('timer', array(
                    "timer" => "travel",
                    "text" => 'You cant travel yet!',
                    "time" => $this->user->getTimer("travel")
                ));
                
            } 
            
            $locations = $this->db->selectAll("SELECT * from locations WHERE L_id != :loc ORDER BY L_id", array(
                ":loc" => $this->user->info->US_location
            ));
            
            foreach ($locations as $location) {

                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "travel",
                    "user" => $this->user,
                    "data" => $location
                );
                $location = $hook->run($hookData, 1)["data"];

                $data[] = array(
                    "location" => $location["L_name"], 
                    "cost" => $location["L_cost"], 
                    "id" => $location["L_id"], 
                    "cooldown" => $this->timeLeft($location["L_cooldown"])
                );
                
            }

            $this->html .= $this->page->buildElement('locationHolder', array(
                "locations" => $data
            ));
            
        }
        
        public function method_fly() {
        
            $id = abs(intval($this->methodData->location));
            
            $location = $this->db->select("SELECT * from locations WHERE L_id = :id ORDER BY L_id", array(
                ':id'=>  $id
            ));

            $hook = new Hook("alterModuleData");
            $hookData = array(
                "module" => "travel",
                "user" => $this->user,
                "data" => $location
            );
            $location = $hook->run($hookData, 1)["data"];
           
            if (!$location){ 
                return $this->error("This location does not exist!");
            }

            if ($this->user->checkTimer('travel')) {
                if ($location["L_id"] == $this->user->info->US_location) {
                    
                    $this->alerts[] = $this->page->buildElement('error', array("text" => 'You are already in '.$location["L_name"].'!'));
                    
                } else if ($this->user->info->US_money < $location["L_cost"]) {
                
                    $this->alerts[] = $this->page->buildElement('error', array("text" => 'You cant afford to travel here!'));
                    
                } else {

                    $this->user->subtract("US_money", $location["L_cost"]);
                    $this->user->set("US_location", $location["L_id"]);
                    
                    $this->user->updateTimer('travel', $location["L_cooldown"], true);

                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "travel", 
                        "id" => $id, 
                        "success" => true, 
                        "reward" => 0
                    );
                    $actionHook->run($action);
                    
                    $this->alerts[] = $this->page->buildElement('success', array("text" => 'You traveled to '.$location["L_name"].' for '.$this->money($location["L_cost"]).'!'));
                    
                }
            } 

        }
        
    }


