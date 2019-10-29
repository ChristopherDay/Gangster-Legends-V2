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
            
            $locations = $this->db->prepare("SELECT * from locations WHERE L_id != :loc ORDER BY L_id");
            $locations->bindParam(":loc", $this->user->info->US_location);
            $locations->execute();
            
            $data = array();
            
            while ($row = $locations->fetchObject()) {
                        
                $perk = 0;
                if (function_exists("getPercReward")) {
                    $perk = getPercReward(3, $this->user);
                } 

                $data[] = array(
                    "location" => $row->L_name, 
                    "cost" => number_format($row->L_cost), 
                    "id" => $row->L_id, 
                    "cooldown" => $this->timeLeft($row->L_cooldown - ($perk * 60))
                );
                
            }
                $this->html .= $this->page->buildElement('locationHolder', array(
                    "locations" => $data
                ));
            
        }
        
        public function method_fly() {
        
            $id = abs(intval($this->methodData->location));
            
            $location = $this->db->prepare("SELECT * from locations WHERE L_id = :id ORDER BY L_id");
            $location->bindParam(':id', $id);
            $location->execute();
            $location = $location->fetchObject();

            if ($this->user->checkTimer('travel')) {
                if ($location->L_id == $this->user->info->US_location) {
                    
                    $this->alerts[] = $this->page->buildElement('error', array("text" => 'You are already in '.$location->L_name.'!'));
                    
                } else if ($this->user->info->US_money < $location->L_cost) {
                
                    $this->alerts[] = $this->page->buildElement('error', array("text" => 'You cant afford to travel here!'));
                    
                } else {
                
                    $travel = $this->db->prepare("UPDATE userStats SET US_money = US_money - :money, US_location = :lID WHERE US_id = :uID");
                    $travel->bindParam(":money", $location->L_cost);
                    $travel->bindParam(":lID", $location->L_id);
                    $travel->bindParam(":uID", $this->user->id);
                    $travel->execute();
                        
                    $perk = 0;
                    if (function_exists("getPercReward")) {
                        $perk = getPercReward(3, $this->user);
                    } 
                    
                    $this->user->updateTimer('travel', $location->L_cooldown - ($perk * 60), true);

                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "travel", 
                        "id" => $id, 
                        "success" => true, 
                        "reward" => 0
                    );
                    $actionHook->run($action);
                    
                    $this->alerts[] = $this->page->buildElement('success', array("text" => 'You traveled to '.$location->L_name.' for $'.number_format($location->L_cost).'!'));
                    
                }
            } 

        }
        
    }

?>