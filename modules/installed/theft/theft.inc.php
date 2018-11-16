<?php

    class theft extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
		
		public $pageName = 'Car Theft';
        
        public function constructModule() {
            
            $theft = $this->db->prepare("SELECT * FROM theft");
            $theft->execute();
            
            $theftArray = array();
            while ($row = $theft->fetchObject()) {
            
                $percent = $row->T_chance + (@$this->user->info->US_rank*2);

                if ($percent > 100) $percent = 100;

                $theftArray[] = array(
                    "name" => $row->T_name, 
                    "id" => $row->T_id, 
                    "percent" => $percent
                );
                

            }
            if (!$this->user->checkTimer('theft')) {
                $time = $this->user->getTimer('theft');
                $crimeError = array(
                    "text"=>"You cant attempt another theft untill your timer is up!",
                    "time" => $this->user->getTimer("theft")
                );
                $this->html .= $this->page->buildElement('timer', $crimeError);
            }

            $this->html .= $this->page->buildElement('theftHolder', array(
                "theft" => $theftArray
            ));
        }
        
        public function timeLeft($ts) {
        
            return date('H:i:s', $ts);
        
        }
        
        public function method_commit() {
            
            $id = abs(intval($this->methodData->id));
            
            if ($this->user->checkTimer('theft')) {
                
                $theftTime = 180;
                $theft = $this->db->prepare("SELECT * FROM theft WHERE T_id = :id");
                $theft->bindParam(':id', $id);
                $theft->execute();
                
                $theftInfo = $theft->fetchObject();
                
                $jailChance = mt_rand(1, 3);
                $chance = mt_rand(1, 100);
                $carDamage = mt_rand(1, $theftInfo->T_maxDamage);
                $userChance = $theftInfo->T_chance + ($this->user->info->US_rank * 2);

                if ($userChance > 100) {
                    $userChance = 100;
                }
                
                $cars = $this->db->prepare("SELECT * FROM cars WHERE CA_value <= :maxCar AND CA_value >= :minCar");
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

				$this->user->updateTimer('theft', $theftTime, true);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $this->alerts[] = $this->page->buildElement('error', array(
                        "text"=>'You failed to steal a '.$carName.', you were caught and sent to jail'
                    ));
                    $this->user->updateTimer('jail', ($id*35), true);
                } else if ($chance > $userChance) {
                    $this->alerts[] = $this->page->buildElement('error', array(
                        "text"=>'You failed to steal a '.$carName.'.'
                    ));
                } else {
                    $this->alerts[] = $this->page->buildElement('success', array(
                        "text" => 'You successfuly stole a '.$carName.' with '.$carDamage.'% damage.'
                    ));
                    $query = "UPDATE userStats SET US_exp = US_exp + 2 WHERE US_id = :uid";
                    $u = $this->db->prepare($query);
                    $u->bindParam(':uid', $this->user->info->US_id);
                    $u->execute();
                    
                    $insert = $this->db->prepare("INSERT INTO garage (GA_uid, GA_car, GA_damage, GA_location) VALUES (:uid, :car, :damage, :loc)");
                    $insert->bindParam(':uid', $this->user->info->US_id);
                    $insert->bindParam(':loc', $this->user->info->US_location);
                    $insert->bindParam(':car', $car);
                    $insert->bindParam(':damage', $carDamage);
                    $insert->execute();
                }
            }
        
        }
        
    }

?>