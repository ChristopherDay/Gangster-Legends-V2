<?php

    class theft extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
        
        public $pageName = 'Car Theft';
        
        public function constructModule() {
            
            $theft = $this->db->selectAll("SELECT * FROM theft ORDER BY T_chance DESC");
            
            $theftArray = array();
            foreach ($theft as $t) {

                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "theft",
                    "user" => $this->user,
                    "data" => $t
                );
                $t = $hook->run($hookData, 1)["data"];
            
                $percent = $t["T_chance"];

                if ($percent > 100) $percent = 100;

                $theftArray[] = array(
                    "name" => $t["T_name"], 
                    "id" => $t["T_id"], 
                    "percent" => $percent
                );
                

            }
            if (!$this->user->checkTimer('theft')) {
                $time = $this->user->getTimer('theft');
                $crimeError = array(
                    "timer" => "theft",
                    "text"=>"You cant attempt another theft untill your timer is up!",
                    "time" => $this->user->getTimer("theft")
                );
                $this->html .= $this->page->buildElement('timer', $crimeError);
            }

            $this->html .= $this->page->buildElement('theftHolder', array(
                "theft" => $theftArray
            ));
        }
        
        public function method_commit() {

            if (!$this->checkCSFRToken()) return;
            
            $id = abs(intval($this->methodData->id));
            
            if ($this->user->checkTimer('theft')) {
                
                $theftTime = 180;
                $theftInfo = $this->db->select("SELECT * FROM theft WHERE T_id = :id", array(
                    ':id' => $id
                ));

                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "theft",
                    "user" => $this->user,
                    "data" => $theftInfo
                );
                $theftInfo = $hook->run($hookData, 1)["data"];
                
                $jailChance = mt_rand(1, 3);
                $chance = mt_rand(1, 100);
                $carDamage = mt_rand(0, $theftInfo["T_maxDamage"]);
                $userChance = $theftInfo["T_chance"];

                if ($userChance > 100) {
                    $userChance = 100;
                }
                
                $cars = $this->db->selectAll("SELECT * FROM cars WHERE CA_value <= :maxCar AND CA_value >= :minCar", array(
                    ':minCar' => $theftInfo["T_worstCar"],
                    ':maxCar' => $theftInfo["T_bestCar"]
                ));
                
                $total = 0;
                
                foreach ($cars as $row) {
                    $total += $row['CA_theftChance'];
                }
                
                $car = mt_rand(1, $total);
                
                $total2 = 0;
                
                foreach ($cars as $row) {
                    $total2 += $row['CA_theftChance'];
                    if ($total2 >= $car) {
                        $car = $row['CA_id'];
                        $carName = $row['CA_name'];
                        $rewardValue = $row['CA_value'] - intval($row['CA_value'] / 100 * $carDamage); 
                        break;
                    }
                    
                }

                $success = false;
                $carID = 0;

                $this->user->updateTimer('theft', $theftTime, true);
                
                if ($chance > $userChance && $jailChance == 1) {
                    $this->alerts[] = $this->page->buildElement('error', array(
                        "text"=>'You failed to steal a '.$carName.', you were caught and sent to jail'
                    ));
                    $this->user->updateTimer('jail', ($id*35), true);
                    $rewardValue = 0;
                } else if ($chance > $userChance) {
                    $this->alerts[] = $this->page->buildElement('error', array(
                        "text"=>'You failed to steal a '.$carName.'.'
                    ));
                    $rewardValue = 0;
                } else {
                    $success = true;
                    $this->alerts[] = $this->page->buildElement('success', array(
                        "text" => 'You successfuly stole a '.$carName.' with '.$carDamage.'% damage.'
                    ));

                    $this->user->add("US_exp", 2);

                    
                    $insert = $this->db->insert("INSERT INTO garage (GA_uid, GA_car, GA_damage, GA_location) VALUES (:uid, :car, :damage, :loc)", array(
                        ':uid' => $this->user->info->US_id,
                        ':loc' => $this->user->info->US_location,
                        ':car' => $car,
                        ':damage' => $carDamage
                    ));

                }

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "theft", 
                    "id" => $id, 
                    "success" => $success, 
                    "reward" => $rewardValue
                );
                $actionHook->run($action);

            }
        
        }
        
    }

