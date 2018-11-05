<?php

    class garage extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
		
		public $pageName = 'Garage';
        
        public function constructModule() {
            
            $garage = $this->db->prepare("SELECT * from garage WHERE GA_uid = :uid");
            $garage->bindParam(':uid', $this->user->info->US_id);
            $garage->execute();
            
            $cars = array();
            
            while ($car = $garage->fetchObject()) {
            
                $loc = $this->db->prepare("SELECT * FROM locations WHERE L_id = ".$car->GA_location);
                $loc->execute();
                $loc = $loc->fetchObject();
            
                $carInfo = $this->db->prepare("SELECT * from cars WHERE CA_id = ".$car->GA_car);
                $carInfo->execute();
                $carInfo = $carInfo->fetchObject();
                
                $multi = (100 - $car->GA_damage) /100;
                $value = round(($carInfo->CA_value * $multi));   
                
                $cars[] = array(
                    "name" => $carInfo->CA_name, 
                    "location" => $loc->L_name, 
                    "damage" => $car->GA_damage.'%', 
                    "id" => $car->GA_id, 
                    "value" => number_format($value)
                );
                
            }
            
            $this->html .= $this->page->buildElement('garage', array("cars" => $cars));
            
        }
        
        public function method_sell() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->prepare("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car");
            $car->bindParam(':car', $id);
            $car->execute();
            $car = $car->fetchObject();
            
            if (empty($car) || $car->GA_uid != $this->user->id) {
                
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You dont own this car or it does not exist!'));
            
            } else {
                
                $this->db->query("DELETE FROM garage WHERE GA_id = ".$car->GA_id);
                $multi = (100 - $car->GA_damage) /100;
                $value = round(($car->CA_value * $multi));   
                
                $this->alerts[] = $this->page->buildElement('success', array("text"=>'You sold your car for $'.number_format($value).'!'));
                
                $this->db->query("UPDATE userStats SET US_money = US_money + $value WHERE US_id = ".$this->user->id);
            
            }
            
        }
        
        public function method_crush() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->prepare("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car");
            $car->bindParam(':car', $id);
            $car->execute();
            $car = $car->fetchObject();
            
            if (empty($car) || $car->GA_uid != $this->user->id) {
                
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You dont own this car or it does not exist!'));
            
            } else {
                
                $this->db->query("DELETE FROM garage WHERE GA_id = ".$car->GA_id);
                $multi = (100 - $car->GA_damage) /100;
                $value = round(($car->CA_value * $multi))/15;   
                
                $this->alerts[] = $this->page->buildElement('success', array("text"=>'You crushed your car for '.number_format($value).' bullets!'));
                
                $this->db->query("UPDATE userStats SET US_bullets = US_bullets + $value WHERE US_id = ".$this->user->id);
            
            }
            
        }
        
        public function method_repair() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->prepare("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car");
            $car->bindParam(':car', $id);
            $car->execute();
            $car = $car->fetchObject();
            
            if (empty($car) || $car->GA_uid != $this->user->id) {
                
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You dont own this car or it does not exist!'));
            
            } else {
                
                $multi = $car->GA_damage / 100;
                if ($multi > 0.2) {
                    $multi = $multi - 0.1;
                }
                
                $value = round(($car->CA_value * $multi)); 
                
                if ($value < $this->user->info->US_money) {
                
                    $this->alerts[] = $this->page->buildElement('success', array("text"=>'You repaired your car for $'.number_format($value).'!'));
                    $this->db->query("UPDATE garage SET GA_damage = 0 WHERE GA_id = ".$car->GA_id);
                    $this->db->query("UPDATE userStats SET US_money = US_money - $value WHERE US_id = ".$this->user->id);
                    
                } else {
                
                    $this->alerts[] = $this->page->buildElement('error', array("text"=>'You do not have enough money to do this, you need $'.number_format($value).'!'));
                    
                }
            
            }
            
        }
        
    }

?>