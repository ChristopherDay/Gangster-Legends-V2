<?php

    class crimes extends module {
        
        public $allowedMethods = array('crime'=>array('type'=>'get'));
		
		public $pageName = 'Crimes';
        
        public function constructModule() {
            
            $crimes = $this->db->prepare("SELECT * FROM crimes WHERE C_level <= :level");
            $crimes->bindParam(':level', $this->user->info->US_rank);
            $crimes->execute();
            
            $crimePercs = explode('-', $this->user->info->US_crimes);
            
            $crimeInfo = array();
            while ($crime = $crimes->fetchObject()) {
                
                $crimeID = $crime->C_id;
                
                $crimeInfo[] = array(
                    "name" => $crime->C_name,
                    "cooldown" => $this->timeLeft($crime->C_cooldown),
                    "percent" => $crimePercs[($crimeID-1)], 
                    "id" => $crimeID
                );
                
                
            }
            
            $this->html .= $this->page->buildElement('crimeHolder', array(
                "crimes" =>$crimeInfo
            ));
        }
        
        public function method_commit() {
            
            $id = abs(intval($this->methodData->crime)); 
			
            if (!$this->user->checkTimer('crime')) {
				
                $time = $this->user->getTimer('crime') - time();
                $crimeError = array("text"=>'You cant commit another crime untill your timer is up! (<span data-timer-type="inline" data-timer="'.($this->user->getTimer("crime") - time()).'"></span>)');
                $this->html .= $this->page->buildElement('error', $crimeError);
                
            } else {
            
                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $crimeID = $id;
                
                $crime = $this->db->prepare("SELECT * FROM crimes WHERE C_id = :crime");
                $crime->bindParam(':crime', $crimeID);
                $crime->execute();
                $crimeInfo = $crime->fetchObject();
                
                $userCrimeChance = explode('-', $this->user->info->US_crimes);
                $userChance = $userCrimeChance[($crimeInfo->C_id - 1)];
                $reward = mt_rand($crimeInfo->C_money, $crimeInfo->C_maxMoney);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $crimeError = array("text"=>'You failed to commit the crime, you were caught and sent to jail!');
                    $this->html .= $this->page->buildElement('error', $crimeError);
                    $query = "UPDATE userStats SET US_crimes = :crimes WHERE US_id = :user";
					$this->user->updateTimer('jail', ($crimeInfo->C_id * 15), true);
                    $add = 0;
                } else if ($chance > $userChance) {
                    $crimeError = array("text"=>'You failed to commit the crime!');
                    $this->html .= $this->page->buildElement('error', $crimeError);
                    $query = "UPDATE userStats SET US_crimes = :crimes WHERE US_id = :user";
                    $add = mt_rand(1, 2);
                } else {
                    $crimeError = array("text"=>'You successfuly commited the crime and earned $'.number_format($reward).'!');
                    $this->html .= $this->page->buildElement('success', $crimeError);
                    $query = "UPDATE userStats SET US_money = US_money + ".$reward.", US_exp = US_exp + 1, US_crimes = :crimes WHERE US_id = :user";
                    $add = mt_rand(1, 4);
                }
                
                $update = $this->db->prepare($query);
                $update->bindParam(':user', $this->user->info->US_id);
				
				$this->user->updateTimer('crime', $crimeInfo->C_cooldown, true);
				
                $userCrimeChance[($crimeID-1)] = $userCrimeChance[($crimeID-1)] + $add;
                
                if ($userCrimeChance[($crimeID-1)] > 100) {
                    $userCrimeChance[($crimeID-1)] = 100;
                }
                
                $newCrimePercentages = implode('-', $userCrimeChance);

                $update->bindParam(':crimes', $newCrimePercentages);
                $update->execute();
                
            }
        
        }
        
    }

?>