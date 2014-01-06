<?php

    class theft extends module {
        
        public function constructModule() {
            
            if (isset($_GET['commit'])) {
                
                $this->commitTheft($_GET['commit']);
            
            }
            
            $theft = $this->db->prepare("SELECT * FROM theft");
            $theft->execute();
            
            while ($row = $theft->fetchObject()) {
            
                $theftArray = array(
                    $row->T_name, 
                    $row->T_id, 
                    $row->T_chance + (@$this->user->info->US_rank*2)
                );
                
                $this->html .= $this->page->buildElement('theftHolder', $theftArray);
            
            }
        }
        
        public function timeLeft($ts) {
        
            return date('H:i:s', $ts);
        
        }
        
        public function commitTheft($id) {
            
            $id = abs(intval($id));
            
            if (time() < $this->user->info->US_theftTimer) {
                $time = $this->user->info->US_theftTimer - time();
                $crimeError = array('You cant commit another theft untill your timer is up! ('.$this->timeLeft($time).')');
                $this->html .= $this->page->buildElement('error', $crimeError);
                
            } else {
            
                $theftTime = 180;
                $theft = $this->db->prepare("SELECT * FROM theft WHERE T_id = :id");
                $theft->bindParam(':id', $id);
                $theft->execute();
                
                $theftInfo = $theft->fetchObject();
                
                $jailChance = mt_rand(1, 3);
                $chance = mt_rand(1, 100);
                $carDamage = mt_rand(1, $theftInfo->T_maxDamage);
                $userChance = $theftInfo->T_chance + ($this->user->info->US_rank * 2);
                
                $cars = $this->db->prepare("SELECT * FROM cars WHERE CA_id <= :maxCar AND CA_id >= :minCar");
                $cars->bindParam(':minCar', $theftInfo->T_worstCar);
                $cars->bindParam(':maxCar', $theftInfo->T_bestCar);
                $cars->execute();
                
                $cars = $cars->fetchAll(PDO::FETCH_ASSOC);
                
                $total = 0;
                
                foreach ($cars as $row) {
                
                    $total += $row['CA_theftChance'];
                    
                }
                
                $car = mt_rand(1, $total);
                
                $total2 = 0;
                
                foreach ($cars as $row) {
                
                    $total2 += $row['CA_theftChance'];
                    
                    if ($total2 > $car) {
                        
                        $car = $row['CA_id'];
                        $carName = $row['CA_name'];
                        
                        break;
                    }
                    
                }
                
                if ($chance > $userChance && $jailChance == 1) {
                    
                    $this->html .= $this->page->buildElement('error', array('You failed to steal a '.$carName.', you were caught and sent to jail'));
                    $query = "UPDATE userStats SET US_theftTimer = ".(time()+$theftTime).", US_jailTimer=".(time()+($id*35))." WHERE US_id = :uid";
                    
                } else if ($chance > $userChance) {
                    
                    $this->html .= $this->page->buildElement('error', array('You failed to steal a '.$carName.'.'));
                    $query = "UPDATE userStats SET US_theftTimer = ".(time()+$theftTime)." WHERE US_id = :uid";
                
                } else {
                    
                    $this->html .=$this->page->buildElement('success', array('You successfuly stole a '.$carName.' with '.$carDamage.'% damage.'));
                    $query = "UPDATE userStats SET US_theftTimer = ".(time()+$theftTime).", US_exp = US_exp + ".$car." WHERE US_id = :uid";
                    $insert = $this->db->prepare("INSERT INTO garage (GA_uid, GA_car, GA_damage, GA_location) VALUES (:uid, :car, :damage, :loc)");
                    $insert->bindParam(':uid', $this->user->info->US_id);
                    $insert->bindParam(':loc', $this->user->info->US_location);
                    $insert->bindParam(':car', $car);
                    $insert->bindParam(':damage', $carDamage);
                    $insert->execute();
                
                }
                
                $u = $this->db->prepare($query);
                $u->bindParam(':uid', $this->user->info->US_id);
                $u->execute();
                    
            }
        
        }
        
    }

?>