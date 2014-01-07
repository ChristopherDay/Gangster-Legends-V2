<?php

    class policeChase extends module {
        
        public $pageName = 'Police Chase';
        public $allowedMethods = array('move'=>array('type'=>'get'));
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('policeChase', array());
            
        }
        
        public function method_move() {
            
            if (time() < $this->user->info->US_chaseTimer) {
                $time = $this->user->info->US_chaseTimer - time();
                $crimeError = array('You cant commit another police chase untill your timer is up! ('.$this->timeLeft($time).')');
                $this->html .= $this->page->buildElement('error', $crimeError);
                
            } else {
                
                $rand = mt_rand(1, 4);
                
                if ($rand == 1) {
                
                    $winnings = mt_rand(150, 850) * $this->user->info->US_rank;
                    
                    $u = $this->db->prepare("UPDATE userStats SET US_chaseTimer = ".(time()+300).", US_money = US_money + $winnings WHERE US_id = ".$this->user->id);
                    $u->execute();
                    
                    $this->html .= $this->page->buildElement('success', array('You got away, you were paid $'.number_format($winnings).'!'));
                    
                } else if ($rand == 3) {
                
                    $u = $this->db->prepare("UPDATE userStats SET US_chaseTimer = ".(time()+300).", US_jailTimer = ".(time() + 150)." WHERE US_id = ".$this->user->id);
                    $u->execute();
                    
                    $this->html .= $this->page->buildElement('error', array('You crashed and was sent to jail!'));
                    
                } else {
                    
                    $this->html .= $this->page->buildElement('info', array('You are still going, what dirrection do you want to go now?'));
                
                }
                
            }
            
        }
        
    }

?>