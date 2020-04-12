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
            
            if (!$this->user->checkTimer('crime')) {
                $time = $this->user->getTimer('crime');
                $crimeError = array(
                    "timer" => "crime",
                    "text"=>'You can\'t commit another crime untill your timer is up!',
                    "time" => $this->user->getTimer("crime")
                );
                $this->html .= $this->page->buildElement('timer', $crimeError);
            }

            $this->html .= $this->page->buildElement('crimeHolder', array(
                "crimes" => $crimeInfo
            ));
        }
        
        public function method_commit() {
            
            $id = abs(intval($this->methodData->crime)); 
            
            if ($this->user->checkTimer('crime')) {
                
                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $crimeID = $id;
                
                $crime = $this->db->prepare("SELECT * FROM crimes WHERE C_id = :crime");
                $crime->bindParam(':crime', $crimeID);
                $crime->execute();
                $crimeInfo = $crime->fetchObject();
             
                if (!$crimeInfo){ 
                    return $this->error("This crime does not exist!"); 
                }
                
                $userCrimeChance = explode('-', $this->user->info->US_crimes);
                $userChance = $userCrimeChance[($crimeInfo->C_id - 1)];
                $cashReward = mt_rand($crimeInfo->C_money, $crimeInfo->C_maxMoney);
                $bulletReward = mt_rand($crimeInfo->C_bullets, $crimeInfo->C_maxBullets);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $crimeError = array("text"=>'You failed to commit the crime, you were caught and sent to jail!');
                    $this->alerts[] = $this->page->buildElement('error', $crimeError);
                    $this->user->updateTimer('jail', ($crimeInfo->C_id * 15), true);
                    $add = 0;
                } else if ($chance > $userChance) {
                    $crimeError = array("text"=>'You failed to commit the crime!');
                    $this->alerts[] = $this->page->buildElement('error', $crimeError);
                    $add = mt_rand(1, 2);
                } else {

                    $rewards = array();

                    if ($cashReward) {
                        $rewards[] = '$'.number_format($cashReward);
                    }
                    if ($bulletReward) {
                        $rewards[] = number_format($bulletReward) . ' bullets';
                    }

                    $crimeError = array("text"=>'You successfuly commited the crime and earned '. implode(" and ", $rewards) .'!');
                    $this->alerts[] = $this->page->buildElement('success', $crimeError);
                    $this->user->set("US_money", $this->user->info->US_money + $cashReward);
                    $this->user->set("US_bullets", $this->user->info->US_bullets + $bulletReward);
                    $this->user->set("US_exp", $this->user->info->US_exp + $crimeInfo->C_exp);
                    $add = mt_rand(1, 4);
                }
                
                $this->user->updateTimer('crime', $crimeInfo->C_cooldown, true);
                
                $userCrimeChance[($crimeID-1)] = $userCrimeChance[($crimeID-1)] + $add;
                
                if ($userCrimeChance[($crimeID-1)] > 100) {
                    $userCrimeChance[($crimeID-1)] = 100;
                }
                
                $newCrimePercentages = implode('-', $userCrimeChance);

                $this->user->set("US_crimes", $newCrimePercentages);
                
            }
        
        }
        
    }

?>
