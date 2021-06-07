<?php

    class garage extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
        
        public $pageName = 'Garage';
        
        public function constructModule() {
            
            $garage = $this->db->selectAll("
                SELECT * from garage 
                INNER JOIN cars ON (CA_id = GA_car) 
                INNER JOIN locations ON (GA_location = L_id)
                WHERE GA_uid = :uid", array(
                ':uid' => $this->user->info->US_id
            ));
            
            $cars = array();
            
            foreach ($garage as $car) {
                        
                $multi = (100 - $car["GA_damage"]) /100;
                $value = round(($car["CA_value"] * $multi));   
                
                $cars[] = array(
                    "name" => $car["CA_name"], 
                    "location" => $car["L_name"], 
                    "damage" => $car["GA_damage"].'%', 
                    "id" => $car["GA_id"], 
                    "value" => $value
                );
                
            }
            
            $this->html .= $this->page->buildElement('garage', array("cars" => $cars));
            
        }
        
        public function method_sell() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->select("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car", array(
                ':car' => $id
            ));
            
            if (empty($car) || $car["GA_uid"] != $this->user->id) {
                
                $this->error('You dont own this car or it does not exist!');
            
            } else {
                
                $this->db->delete("DELETE FROM garage WHERE GA_id = :id", array(
                    ":id" => $car["GA_id"]
                ));
                $multi = (100 - $car["GA_damage"]) /100;
                $value = round(($car["CA_value"] * $multi));   
                
                $this->error('You sold your car for '.$this->money($value).'!', "success");
                $this->user->add("US_money", $value);
     
                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "garage.sell", 
                    "id" => $car["CA_id"], 
                    "success" => true, 
                    "reward" => $value
                );
                $actionHook->run($action);
            
            }
            
        }
        
        public function method_crush() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->select("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car", array(
                ':car' => $id
            ));
            
            if (empty($car) || $car["GA_uid"] != $this->user->id) {
                $this->error('You dont own this car or it does not exist!');
            } else {
                
                $this->db->query("DELETE FROM garage WHERE GA_id = ".$car["GA_id"]);
                $multi = (100 - $car["GA_damage"]) /100;
                $value = round(($car["CA_value"] * $multi))/15;   
                
                $this->error('You crushed your car for '.number_format($value).' bullets!', "success");
                $this->user->add("US_bullets", $value);

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "garage.crush", 
                    "id" => $car["CA_id"], 
                    "success" => true, 
                    "reward" => $value
                );
                $actionHook->run($action);
            
            }
            
        }
        
        public function method_repair() {
            
            $id = $this->methodData->id;
            
            $car = $this->db->select("SELECT * FROM garage INNER JOIN cars ON (CA_id = GA_car) WHERE GA_id = :car", array(
                ':car' => $id
            ));
            
            if (empty($car) || $car["GA_uid"] != $this->user->id) {
                $this->error('You dont own this car or it does not exist!');
            } else {
                
                $multi = $car["GA_damage"] / 100;
                if ($multi > 0.2) {
                    $multi = $multi - 0.1;
                }
                
                $value = round(($car["CA_value"] * $multi)); 
                
                if ($value < $this->user->info->US_money) {
                
                    $this->error('You repaired your car for '.$this->money($value).'!', "success");
                    $this->db->update("UPDATE garage SET GA_damage = 0 WHERE GA_id = :id", array(
                        ":id" => $car["GA_id"]
                    ));
                    $this->user->subtract("US_money", $value);
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "garage.repair", 
                        "id" => $car["CA_id"], 
                        "success" => true, 
                        "reward" => $value
                    );
                    $actionHook->run($action);
                    
                } else {
                    $this->error('You do not have enough money to do this, you need '.$this->money($value).'!');
                }
            
            }
            
        }
        
    }

