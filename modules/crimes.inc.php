<?php

    class crimes extends module {
        
        public function constructModule() {
            
            if (isset($_GET['commit'])) {
            
                $this->commitCrime($_GET['commit']);
                
            }
            
            $crimes = $this->db->prepare("SELECT * FROM crimes WHERE C_level <= :level");
            $crimes->bindParam(':level', $this->user->info->US_rank);
            $crimes->execute();
            
            $crimePercs = explode('-', $this->user->info->US_crimes);
            
            while ($crime = $crimes->fetchObject()) {
                
                $crimeID = $crime->C_id;
                
                $crimeInfo = array(
                    $crime->C_name,
                    $this->timeLeft($crime->C_cooldown),
                    $crimePercs[($crimeID-1)], 
                    $crimeID
                );
                
                $this->html .= $this->page->buildElement('crimeHolder', $crimeInfo);
                
            }
            
        }
        
        private function commitCrime($id) {
            
            if (time() < $this->user->info->US_crimeTimer) {
                $time = $this->user->info->US_crimeTimer - time();
                $crimeError = array('You cant commit another crime untill your timer is up! ('.$this->timeLeft($time).')');
                $this->html .= $this->page->buildElement('error', $crimeError);
                
            } else {
            
                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $crimeID = abs(intval($_GET['commit']));
                
                $crime = $this->db->prepare("SELECT * FROM crimes WHERE C_id = :crime");
                $crime->bindParam(':crime', $crimeID);
                $crime->execute();
                $crimeInfo = $crime->fetchObject();
                
                $userCrimeChance = explode('-', $this->user->info->US_crimes);
                $userChance = $userCrimeChance[($crimeInfo->C_id - 1)];
                $reward = mt_rand($crimeInfo->C_money, $crimeInfo->C_maxMoney);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $crimeError = array('You failed to commit the crime, you were caught and sent to jail!');
                    $this->html .= $this->page->buildElement('error', $crimeError);
                    $query = "UPDATE userStats SET US_jailTime = ".(time()+($crimeInfo->C_id * 15)).", US_crimeTimer = ".(time() + $crimeInfo->C_cooldown).", US_crimes = :crimes WHERE US_id = :user";
                    $add = 0;
                } else if ($chance > $userChance) {
                    $crimeError = array('You failed to commit the crime!');
                    $this->html .= $this->page->buildElement('error', $crimeError);
                    $query = "UPDATE userStats SET US_crimeTimer = ".(time() + $crimeInfo->C_cooldown).", US_crimes = :crimes WHERE US_id = :user";
                    $add = mt_rand(1, 2);
                } else {
                    $crimeError = array('You successfuly commited the crime and earned $'.number_format($reward).'!');
                    $this->html .= $this->page->buildElement('success', $crimeError);
                    $query = "UPDATE userStats SET US_money = US_money + ".$reward.", US_exp = US_exp + 1, US_crimeTimer = ".(time() + $crimeInfo->C_cooldown).", US_crimes = :crimes WHERE US_id = :user";
                    $add = mt_rand(1, 4);
                }
                
                $update = $this->db->prepare($query);
                $update->bindParam(':user', $this->user->info->US_id);
                $userCrimeChance[($crimeID-1)] = $userCrimeChance[($crimeID-1)] + $add;
                
                if ($userCrimeChance[($crimeID-1)] > 100) {
                    $userCrimeChance[($crimeID-1)] = 100;
                }
                
                $update->bindParam(':crimes', implode('-', $userCrimeChance));
                $update->execute();
                
            }
        
        }
        
    }

?>