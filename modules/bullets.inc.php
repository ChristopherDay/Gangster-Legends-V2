<?php

    class bullets extends module {
        
        public $pageName = 'Bullet Factory';
        
        public function constructModule() {
            
            if (isset($_POST['bullets'])) {
            
                $this->buy($_POST['bullets']);
                
            }
            
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
        
        private function buy($qty) {
            
            $qty = abs(intval($qty));
            
            $location = $this->db->prepare("SELECT * FROM locations WHERE L_id = ?");
            $location->execute(array($this->user->info->US_location));
            $loc = $location->fetchObject();
            
            if (time() < $this->user->info->US_bulletTimer) {
                
                $this->html .= $this->page->buildElement('error', array('You can only buy bullets once every 60 seconds ('.($this->timeLeft(($this->user->info->US_bulletTimer - time()))).').'));
                
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
                        US_money = US_money - ".($qty * $loc->L_bulletCost).", 
                        US_bulletTimer = ".(time()+60)."
                    WHERE
                        US_id = ".$this->user->id."
                "; 
                
                $this->db->query($query);
                
                $this->db->query("UPDATE locations SET L_bullets = L_bullets - $qty WHERE L_id=".$loc->L_id);
                
            }
            
        }
        
    }

?>