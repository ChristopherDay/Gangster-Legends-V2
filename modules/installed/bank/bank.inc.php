<?php

    class bank extends module {
        
        public $allowedMethods = array(
            'user'=>array('type'=>'post'),
            'money'=>array('type'=>'post'),
            'deposit'=>array('type'=>'post'),
            'withdraw'=>array('type'=>'post'),
            'bank'=>array('type'=>'post')
        );
        
        public function constructModule() {

            $tax = $this->_settings->loadSetting("bankTax", true, 15);
            
            $this->html .= $this->page->buildElement("bank", array(
                "tax" => $tax,
                "deposit" => $this->user->info->US_money,
                "withdraw" => $this->user->info->US_bank
            ));
            
        }

        public function method_transfer() {

            if (!isset($this->methodData->user)) {
                return $this->error("This user does not exist");
            }

            if (!isset($this->methodData->money)) {
                return $this->error("How much money do you want to send?");
            }

            $user = new User(null, $this->methodData->user);

            if (!isset($user->info->U_id)) {
                return $this->error("This user does not exist");
            }

            if ($user->info->U_id == $this->user->id) {
                return $this->error("You can't send money to yourself!");
            }

            $money = abs(intval($this->methodData->money));

            if (!$money) {
                return $this->error("How much money do you want to send?");
            }
            
            if ($money > $this->user->info->US_money) {
                return $this->error("You dont have that much money");
            }

            $this->user->set("US_money", $this->user->info->US_money - $money);
            $user->set("US_money", $user->info->US_money + $money);
            $user->newNotification(htmlentities($this->user->info->U_name) . " has sent you " . $this->money($money));

            $this->error("You have sent " . $this->money($money) . " to " . htmlentities($user->info->U_name), "success");

            $actionHook = new hook("userAction");
            $action = array(
                "user" => $this->user->id, 
                "module" => "bank.sendMoney", 
                "id" => $user->info->id, 
                "success" => true, 
                "reward" => $money
            );
            $actionHook->run($action);

        }
        
        public function method_process() {
        
            if ($this->methodData->bank == 'withdraw') {
                
                $money = abs(intval(str_replace(array(',', '$'), array('', ''), $this->methodData->withdraw)));
                
                if ($money < 0) {                
                    $this->alerts[] = $this->page->buildElement("error", array("text"=>"You cant withdraw negative cash"));
                } else if ($this->user->info->US_bank < $money) {
                    
                    $this->alerts[] = $this->page->buildElement("error", array("text"=>"You dont have enough money in your bank for this transaction!"));
                    
                } else {
                    
                    $update = $this->db->prepare("UPDATE userStats SET US_bank = US_bank - :w1, US_money = US_money + :w2 WHERE US_id = :id");
                    $update->bindParam(":w1", $money);
                    $update->bindParam(":w2", $money);
                    $update->bindParam(":id", $this->user->info->US_id);
                    $update->execute();
                    
                    $this->alerts[] = $this->page->buildElement("success", array("text"=>"You have withdrawn ".$this->money($money)."!"));
                    
                    $this->user->info->US_money += $money;
                    $this->user->info->US_bank -= $money;
                    
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "bank.withdraw", 
                        "id" => 0, 
                        "success" => true, 
                        "reward" => $money
                    );
                    $actionHook->run($action);
                    
                }
                
            } else if ($this->methodData->bank == 'deposit') {
                
                $money = abs(intval(str_replace(array(',', '$'), array('', ''), $this->methodData->deposit)));
                
                if ($money < 0) {                
                    $this->alerts[] = $this->page->buildElement("error", array("text"=>"You cant deposit negative cash"));
                } else if ($this->user->info->US_money < $money) {
                    
                    $this->alerts[] = $this->page->buildElement("error", array("text"=>"You dont have enough money for this transaction!"));
                    
                } else {

                    $tax = (100 - $this->_settings->loadSetting("bankTax", true, 15)) / 100;
                    
                    $bank = $money * $tax;
                    
                    $update = $this->db->prepare("UPDATE userStats SET US_bank = US_bank + :w1, US_money = US_money - :w2 WHERE US_id = :id");
                    $update->bindParam(":w1", $bank);
                    $update->bindParam(":w2", $money);
                    $update->bindParam(":id", $this->user->info->US_id);
                    $update->execute();
                    
                    $this->alerts[] = $this->page->buildElement("success", array("text"=>"You sent ".$this->money($money)." to your money launder, he in return deposits ".$this->money($bank)." into your bank account!"));
                    
                    $this->user->info->US_bank += $bank;
                    $this->user->info->US_money -= $money;
                    
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "bank.deposit", 
                        "id" => 0, 
                        "success" => true, 
                        "reward" => $money
                    );
                    $actionHook->run($action);
                    
                }
                
            }
            
        }
        
    }

