<?php

    class travel extends module {
        
        public function constructModule() {
            
            if (isset($_GET['location'])) {
             
                $this->fly($_GET['location']);
                
            }
            
            $locations = $this->db->prepare("SELECT * from locations WHERE L_id != ".$this->user->info->US_location." ORDER BY L_id");
            $locations->execute();
            
            while ($row = $locations->fetchObject()) {
            
                $this->html .= $this->page->buildElement('locationHolder', array(
                    $row->L_name, 
                    number_format($row->L_cost), 
                    $row->L_id, 
                    $this->timeLeft($row->L_cooldown))
                );
                
            }
            
        }
        
        private function fly($id) {
        
            $id = abs(intval($id));
            
            $location = $this->db->prepare("SELECT * from locations WHERE L_id = :id ORDER BY L_id");
            $location->bindParam(':id', $id);
            $location->execute();
            $location = $location->fetchObject();
            if ($location->L_id == $this->user->info->US_location) {
                
                $this->html .= $this->page->buildElement('error', array('You are already in '.$location->L_name.'!'));
                
            } else if ($this->user->info->US_travelTimer > time()) {
                
                $time = ($this->user->info->US_travelTimer - time());
                $this->html .= $this->page->buildElement('error', array('You cant travel yet, you have to wait for your timer to reach 0 ('.$this->timeLeft($time).')'));
                
            } else if ($this->user->info->US_money < $location->L_cost) {
            
                $this->html .= $this->page->buildElement('error', array('You cant afford to travel here!'));
                
            } else {
            
                $this->db->query("UPDATE userStats SET US_money = US_money - ".$location->L_cost.", US_location = ".$location->L_id.", US_travelTimer = ".(time() + $location->L_cooldown)." WHERE US_id = ".$this->user->id);
                $this->html .= $this->page->buildElement('success', array('You traveled to '.$location->L_name.' for $'.number_format($location->L_cost).'!'));
                
            }
            
        }
        
    }

?>