<?php

    class bullets extends module {
        
        public $pageName = 'Bullet Factory';
        public $allowedMethods = array('bullets'=>array('type'=>'post'));
        
        public function constructModule() {
            
            $location = $this->db->prepare("SELECT * FROM locations WHERE L_id = ?");
            $location->execute(array($this->user->info->US_location));
            $loc = $location->fetchObject();
            
            $this->html .= $this->page->buildElement('bulletPage', 
                array(
                    "location" => $loc->L_name, 
                    "stock" => number_format($loc->L_bullets), 
                    "cost" => number_format($loc->L_bulletCost),
                    "maxBuy" => ($this->user->info->US_rank * 25)
                )
            );
            
        }
        
        public function method_buy() {
            
            $qty = abs(intval($this->methodData->bullets));
            
            $location = $this->db->prepare("SELECT * FROM locations WHERE L_id = ?");
            $location->execute(array($this->user->info->US_location));
            $loc = $location->fetchObject();
            
			if ($qty == 0) {
			
				$this->alerts[] = $this->page->buildElement('error', array("text"=>'Please enter a value greater then 0!'));
            
			} else if (!$this->user->checkTimer('bullets')) {
                
				$timeLeft = $this->user->getTimer('bullets');
				$timeLeft = $this->timeLeft($timeLeft);
				
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You can only buy bullets once every 60 seconds (<span data-reload-when-done data-timer-type="inline" data-timer="'.($this->user->getTimer("bullets")).'"></span>).'));
                
            } else if ($qty > ($this->user->info->US_rank * 25)) {
            
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You can only buy '.number_format(($this->user->info->US_rank * 25)).' bullets at once'));
                
            } else if ($loc->L_bullets < $qty) {
            
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'The bullet factory does not have enough stock to fufil this order!'));
            
            } else if (($qty*$loc->L_bulletCost) > $this->user->info->US_money) {
            
                $this->alerts[] = $this->page->buildElement('error', array("text"=>'You dont have enough money to buy this ammount of bullets'));
            
            } else {
            
                $this->alerts[] = $this->page->buildElement('success', array("text"=>'You bought '.$qty.' bullets for $'.number_format(($qty * $loc->L_bulletCost))));
                
                $query = "
                    UPDATE userStats SET 
                        US_bullets = US_bullets + $qty, 
                        US_money = US_money - :money
                    WHERE
                        US_id = :id"; 
                
				$money = ($qty * $loc->L_bulletCost);
				
                $uUser = $this->db->prepare($query);
                $uUser->bindParam(":money", $money);
                $uUser->bindParam(":id", $this->user->id);
                $uUser->execute();
				
				$this->user->updateTimer('bullets', 60, true);
				
                $uLoc = $this->db->prepare("UPDATE locations SET L_bullets = L_bullets - $qty WHERE L_id= :loc");
                $uLoc->bindParam(":loc", $loc->L_id);
				$uLoc->execute();
            }
            
        }
        
    }

?>