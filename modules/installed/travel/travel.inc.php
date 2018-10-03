<?php

    class travel extends module {
        
        public $allowedMethods = array('location'=>array('type'=>'get'));
		
		public $pageName = 'Airport';
        
        public function constructModule() {
            
            $locations = $this->db->prepare("SELECT * from locations WHERE L_id != :loc ORDER BY L_id");
			$locations->bindParam(":loc", $this->user->info->US_location);
            $locations->execute();
            
            while ($row = $locations->fetchObject()) {
            
                $this->html .= $this->page->buildElement('locationHolder', array(
                    "location" => $row->L_name, 
                    "cost" => number_format($row->L_cost), 
                    "id" => $row->L_id, 
                    "cooldown" => $this->timeLeft($row->L_cooldown))
                );
                
            }
            
        }
        
        public function method_fly() {
        
            $id = abs(intval($this->methodData->location));
            
            $location = $this->db->prepare("SELECT * from locations WHERE L_id = :id ORDER BY L_id");
            $location->bindParam(':id', $id);
            $location->execute();
            $location = $location->fetchObject();
            if ($location->L_id == $this->user->info->US_location) {
                
                $this->html .= $this->page->buildElement('error', array("text" => 'You are already in '.$location->L_name.'!'));
                
            } else if (!$this->user->checkTimer('travel')) {
                
                $time = ($this->user->getTimer('travel'));
                $this->html .= $this->page->buildElement('error', array("text" => 'You cant travel yet, the next flight is in <span data-reload-when-done data-timer-type="inline" data-timer="'.($this->user->getTimer("travel")).'"></span>)'));
                
            } else if ($this->user->info->US_money < $location->L_cost) {
            
                $this->html .= $this->page->buildElement('error', array("text" => 'You cant afford to travel here!'));
                
            } else {
            
                $travel = $this->db->prepare("UPDATE userStats SET US_money = US_money - :money, US_location = :lID WHERE US_id = :uID");
				$travel->bindParam(":money", $location->L_cost);
				$travel->bindParam(":lID", $location->L_id);
				$travel->bindParam(":uID", $this->user->id);
				$travel->execute();
				
				$this->user->updateTimer('travel', $location->L_cooldown, true);
				
                $this->html .= $this->page->buildElement('success', array("text" => 'You traveled to '.$location->L_name.' for $'.number_format($location->L_cost).'!'));
                
            }
            
        }
        
    }

?>