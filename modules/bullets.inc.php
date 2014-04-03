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
                    $loc->L_name, 
                    number_format($loc->L_bullets), 
                    number_format($loc->L_bulletCost),
                    ($this->user->info->US_rank * 25)
                )
            );
            
        }
        
        public function method_buy() {
            
            $qty = abs(intval($this->methodData->bullets));
            
            $location = $this->db->prepare("SELECT * FROM locations WHERE L_id = ?");
            $location->execute(array($this->user->info->US_location));
            $loc = $location->fetchObject();
            
            if (!$this->user->checkTimer('bullets')) {
                
				$timeLeft = $this->user->getTimer('bullets') - time();
				$timeLeft = $this->timeLeft($timeLeft);
				
                $this->html .= $this->page->buildElement('error', array('You can only buy bullets once every 60 seconds (<span data-timer-type="inline" data-timer="'.($this->user->getTimer("bullets") - time()).'"></span>).'));
                
            } else if ($qty > ($this->user->info->US_rank * 25)) {
            
                $this->html .= $this->page->buildElement('error', array('You can only buy '.number_format(($this->user->info->US_rank * 25)).' bullets at once'));
                
            } else if ($loc->L_bullets < $qty) {
            
                $this->html .= $this->page->buildElement('error', array('The bullet factory does not have enough stock to fufil this order!'));
            
            } else if (($qty*$loc->L_bulletCost) > $this->user->info->US_money) {
            
                $this->html .= $this->page->buildElement('error', array('You dont have enough money to buy this ammount of bullets'));
            
            } else {
            
                $this->html .= $this->page->buildElement('success', array('You brought '.$qty.' bullets for $'.number_format(($qty * $loc->L_bulletCost))));
                
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