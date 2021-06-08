<?php

    class crimes extends module {
        
        public $allowedMethods = array('crime'=>array('type'=>'get'));
        
        public function constructModule() {
            
            
            $crimes = $this->db->selectAll("SELECT * FROM crimes WHERE C_level <= :level", array(
                ':level' => $this->user->info->US_rank
            ));
            
            $crimePercs = explode('-', $this->user->info->US_crimes);
            
            $crimeInfo = array();
            foreach ($crimes as $crime) {
                
                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "crimes",
                    "user" => $this->user,
                    "data" => $crime
                );
                $crime = $hook->run($hookData, 1)["data"];

                $crimeID = $crime["C_id"];
                
                $crimeInfo[] = array(
                    "name" => $crime["C_name"],
                    "cooldown" => $this->timeLeft($crime["C_cooldown"]),
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

            if (!$this->checkCSFRToken()) return;
            
            $id = abs(intval($this->methodData->crime)); 
            
            if ($this->user->checkTimer('crime')) {
                
                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $crimeID = $id;
                
                $crime = $this->db->select("SELECT * FROM crimes WHERE C_id = :crime", array(
                    ':crime' => $crimeID
                ));

                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "crimes",
                    "user" => $this->user,
                    "data" => $crime
                );
                $crimeInfo = $hook->run($hookData, 1)["data"];
             
                if (!$crimeInfo){ 
                    return $this->error("This crime does not exist!"); 
                }
                
                $userCrimeChance = explode('-', $this->user->info->US_crimes);
                $userChance = $userCrimeChance[($crimeInfo["C_id"] - 1)];
                $cashReward = mt_rand($crimeInfo["C_money"], $crimeInfo["C_maxMoney"]);
                $bulletReward = mt_rand($crimeInfo["C_bullets"], $crimeInfo["C_maxBullets"]);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $this->error("You failed to commit the crime, you were caught and sent to jail!");
                    $this->user->updateTimer('jail', ($crimeInfo["C_id"] * 15), true);
                    $add = 0;
     
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "crimes", 
                        "id" => $crimeID, 
                        "success" => false, 
                        "reward" => 0
                    );
                    $actionHook->run($action);

                } else if ($chance > $userChance) {
                    $this->error("You failed to commit the crime!");
                    $add = mt_rand(1, 2);
     
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "crimes", 
                        "id" => $crimeID, 
                        "success" => false, 
                        "reward" => 0
                    );
                    $actionHook->run($action);

                } else {

                    $rewards = array();

                    if ($cashReward) {
                        $rewards[] = $this->money($cashReward);
                    }
                    if ($bulletReward) {
                        $rewards[] = number_format($bulletReward) . ' bullets';
                    }

                    $this->error("You successfuly commited the crime and earned ". implode(" and ", $rewards) ."!", "success");
                    $this->user->set("US_money", $this->user->info->US_money + $cashReward);
                    $this->user->set("US_bullets", $this->user->info->US_bullets + $bulletReward);
                    $this->user->set("US_exp", $this->user->info->US_exp + $crimeInfo["C_exp"]);
     
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "crimes", 
                        "id" => $crimeID, 
                        "success" => true, 
                        "reward" => $cashReward
                    );
                    $actionHook->run($action);

                    $add = mt_rand(1, 4);
                }
                
                $this->user->updateTimer('crime', $crimeInfo["C_cooldown"], true);
                
                $userCrimeChance[($crimeID-1)] = $userCrimeChance[($crimeID-1)] + $add;
                
                if ($userCrimeChance[($crimeID-1)] > 100) {
                    $userCrimeChance[($crimeID-1)] = 100;
                }
                
                $newCrimePercentages = implode('-', $userCrimeChance);

                $this->user->set("US_crimes", $newCrimePercentages);
                
            }
        
        }
        
    }
