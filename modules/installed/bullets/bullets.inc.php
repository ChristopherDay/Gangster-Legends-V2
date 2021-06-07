<?php

    class bullets extends module {

        public $maxCost = 100000;
        public $bulletCost = 150; 
        
        public $pageName = 'Bullet Factory';
        public $allowedMethods = array('bullets'=>array('type'=>'post'));

        public function setCost($cost) {
            $this->bulletCost = $cost; 
            $settings = new Settings();
            $this->maxCost = $settings->loadSetting("maxBulletCost", true, 100000);
            if ($this->bulletCost > $this->maxCost) $this->bulletCost = $this->maxCost; 
        } 
        
        public function restock() {
            $settings = new Settings();
            $lastRestock = (int) $settings->loadSetting("lastBulletRestock");

            $thisHour = strtotime(date("Y-m-d H:00:00"));

            $hoursSinceLastRestock = floor(($thisHour - $lastRestock) / 3600);

            if ($hoursSinceLastRestock > 12) $hoursSinceLastRestock = 12;

            if (!$hoursSinceLastRestock) return;

            $allLocations = $this->db->selectAll("SELECT * FROM locations");

            foreach ($allLocations as $location) {
                $newBullets = $this->getNewBulletStock($hoursSinceLastRestock);
                $update = $this->db->update("
                    UPDATE locations SET L_bullets = L_bullets + :qty WHERE L_id = :id
                ", array(
                    ":qty" => $newBullets,
                    ":id" => $location["L_id"]
                ));
            }

            $max = abs(intval($settings->loadSetting("maxBulletStock", true, 40000)));

            $update = $this->db->update("
                UPDATE locations SET L_bullets = $max WHERE L_bullets > $max
            ");

            $lastRestock = (int) $settings->loadSetting("lastBulletRestock");

            $settings->update("lastBulletRestock", $thisHour);

        }

        public function getNewBulletStock($hours) {

            $setting = new Settings();
            $minPerHour = $setting->loadSetting("bulletsStockMinPerHour", true, 2250);
            $maxPerHour = $setting->loadSetting("bulletsStockMaxPerHour", true, 2750);

            $bullets = 0;

            $i = 0;
            while ($i < $hours) {
                $bullets += mt_rand($minPerHour, $maxPerHour);
                $i++;
            }

            return $bullets;

        }

        public function constructModule() {

            $this->restock();
            
            $location = $this->db->select("
                SELECT * FROM locations WHERE L_id = :id
            ", array(
                "id" => $this->user->info->US_location
            ));
                        
            $this->property = new Property($this->user, "bullets");
            $owner = $this->property->getOwnership();

            if (!!$owner["cost"]) {
                $this->setCost($owner["cost"]);
            }

            $settings = new Settings();
                        
            $perk = 0;
            if (function_exists("getPercReward")) {
                $perk = getPercReward(4, $this->user);
            } 

            $owner["locationName"] = $location["L_name"];
            $owner["stock"] = number_format($location["L_bullets"]);
            $owner["maxBuy"] = abs(intval($settings->loadSetting("maxBulletBuy", true, 250))) + $perk;
            $owner["cost"] = $this->money($this->bulletCost);

            if (!$this->user->checkTimer('bullets')) {
                
                $timeLeft = $this->user->getTimer('bullets');
                $timeLeft = $this->timeLeft($timeLeft);

                $error = array(
                    "timer" => "bullets",
                    "text"=>'You have to wait to buy more bullets!',
                    "time" => $this->user->getTimer("bullets")
                );
                $this->html .= $this->page->buildElement('timer', $error);
                
            }

            $this->html .= $this->page->buildElement('bulletPage', $owner);
            
        }
        
        public function method_own() {
            $this->property = new Property($this->user, "bullets");
            $owner = $this->property->getOwnership();

            if ($owner["user"]) {

                $user = $this->page->buildElement("userName", $owner);

                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This property is owned by " . $user
                ));
            }

            if ($this->user->info->US_money < 1000000) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "You need $1,000,000 to buy of this property."
                ));
            }

            $update = $this->db->update("
                UPDATE userStats SET US_money = US_money - 1000000 WHERE US_id = :id
            ", array(
                ":id" => $this->user->id
            ));

            $this->property->transfer($this->user->id);

            return $this->html .= $this->page->buildElement("success", array(
                "text" => "You paid $1,000,000 to buy this property."
            ));

        }
        
        public function method_buy() {
            
            $qty = abs(intval($this->methodData->bullets));
            $settings = new Settings();
            
            $location = $this->db->select("SELECT * FROM locations WHERE L_id = :id", array(
                ":id" =>$this->user->info->US_location
            ));
            
            $this->property = new Property($this->user, "bullets");
            $owner = $this->property->getOwnership();

            if (!!$owner["cost"]) {
                $this->setCost($owner["cost"]);
            }
            
            $cost = ($qty * $this->bulletCost);

            $perk = 0;
            if (function_exists("getPercReward")) {
                $perk = getPercReward(4, $this->user);
            } 
            
            $maxBuy = abs(intval($settings->loadSetting("maxBulletBuy", true, 250))) + $perk;
            
            if ($qty == 0) {
            
                $this->error('Please enter a value greater then 0!');
            
            } else if (!$this->user->checkTimer('bullets')) {
                
                $timeLeft = $this->user->getTimer('bullets');
                $timeLeft = $this->timeLeft($timeLeft);

                $this->error("You can't buy bullets yet!");
                
            } else if ($qty > $maxBuy) {
                $this->error('You can only buy '.number_format($maxBuy).' bullets at once');
            } else if ($location["L_bullets"] < $qty) {
                $this->error('The bullet factory does not have enough stock to fufil this order!');
            } else if ($cost > $this->user->info->US_money) {
                $this->error('You need ' . $this->money($cost) . " to buy " . $qty . " bullets"
                );
            } else {
            
                $this->error('You bought '.$qty.' bullets for '.$this->money($cost)
                , "success");
                
                $query = "
                    UPDATE userStats SET 
                        US_bullets = US_bullets + $qty, 
                        US_money = US_money - :money
                    WHERE
                        US_id = :id
                "; 
                
                $uUser = $this->db->update($query, array(
                    ":money" => $cost,
                    ":id" => $this->user->id
                ));

                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "bullets", 
                    "id" => 1, 
                    "success" => true, 
                    "reward" => $qty
                );
                $actionHook->run($action);
                
                if ($owner["user"]) {
                    $profit = $cost * 0.5;
                    $this->property->updateProfit($profit);
                    $uUser = $this->db->update("
                        UPDATE userStats SET US_bank = US_bank + :money WHERE US_id = :id
                    ", array(
                        ":id" => $owner["user"]["id"],
                        ":money" => $profit
                    ));
                }

                $this->user->updateTimer('bullets', 60, true);
                
                $uLoc = $this->db->update("
                    UPDATE locations SET L_bullets = L_bullets - $qty WHERE L_id= :loc
                ", array(
                    ":loc" => $location["L_id"]
                ));
            }
            
        }
        
    }

