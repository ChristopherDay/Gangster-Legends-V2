<?php

    class gangBank extends module {
        
        public $allowedMethods = array(
            'deposit'=>array('type'=>'post'),
            'withdraw'=>array('type'=>'post'),
            'bank'=>array('type'=>'post')
        );
        
        public function constructModule() {

            if (!$this->user->info->US_gang) return $this->html .= $this->page->buildElement("error", array(
                "text" => "You are not in a gang!"
            ));

            $gang = new Gang($this->user->info->US_gang);

            $gangBank = $this->db->prepare("SELECT * FROM gangs WHERE G_id = :g");
            $gangBank->bindParam(":g", $this->user->info->US_gang);
            $gangBank->execute();
            $gangBank = $gangBank->fetch(PDO::FETCH_ASSOC);
            
            $this->html .= $this->page->buildElement("bank", array(
                "canWithdrawCash" => $gang->can("withdrawCash"),
                "canWithdrawBullets" => $gang->can("withdrawBullets"),
                "deposit" => '$'.number_format($this->user->info->US_money),
                "withdraw" => '$'.number_format($this->user->info->US_bank),
                "bullets" => number_format($this->user->info->US_bullets), 
                "cash" => $gangBank["G_money"], 
                "bullets" => $gangBank["G_bullets"] 
            ));
            
        }

        private $typeName = "cash";
        private $userType = "US_money";
        private $type = "G_money";
        private $tax = 0.85;
        
        public function method_processBullets() {
            $this->tax = 0.75;
            $this->userType = "US_bullets";
            $this->type = "G_bullets";
            $this->typeName = "bullets";
            $this->method_process();
        }

        public function method_process() {
        
            if (!$this->user->info->US_gang) return $this->html .= $this->page->buildElement("error", array(
                "text" => "You are not in a gang!"
            ));

            $gang = $this->db->prepare("SELECT * FROM gangs WHERE G_id = :g");
            $gang->bindParam(":g", $this->user->info->US_gang);
            $gang->execute();
            $gang = $gang->fetch(PDO::FETCH_ASSOC);

            $g = new Gang($this->user->info->US_gang);

            if ($this->methodData->bank == 'withdraw') {
                
                if (!$this->methodData->deposit) $this->methodData->deposit = "0";

                $money = str_replace(array(',', '$'), array('', ''), $this->methodData->deposit);
                
                if ($money < 0) {                
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text"=>"You cant withdraw negative " . $this->typeName
                    ));
                } else if ($gang[$this->type] < $money) {
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text"=>"Your gang does not have enough " . $this->typeName . " for this transaction!"
                    ));
                    
                } else {
                    
                    $update = $this->db->prepare("
                        UPDATE gangs SET ".$this->type." = ".$this->type." - :w WHERE G_id = :id");
                    $update->bindParam(":w", $money);
                    $update->bindParam(":id", $this->user->info->US_gang);
                    $update->execute();
                    
                    if ($this->type == "G_money") {
                        $this->alerts[] = $this->page->buildElement("success", array(
                            "text"=>"You have withdrawn $".number_format($money)."!"
                        ));
                        $g->log("withdrew $".number_format($money));
                    } else {
                        $this->alerts[] = $this->page->buildElement("success", array(
                            "text"=>"You have withdrawn ".number_format($money)." bullets!"
                        ));
                        $g->log("withdrew ".number_format($money) . " bullets");
                    }
                    
                    $this->user->set($this->userType, $this->user->info->{$this->userType} + $money);
                    
                }
                
            } else if ($this->methodData->bank == 'deposit') {

                if (!$this->methodData->deposit) $this->methodData->deposit = "0";
                
                $money = str_replace(array(',', '$'), array('', ''), $this->methodData->deposit);
                
                if ($money < 0) {                
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text"=>"You cant deposit negative " . $this->typeName
                    ));
                } else if ($this->user->info->{$this->userType} < $money) {
                    $this->alerts[] = $this->page->buildElement("error", array(
                        "text"=>"You dont have enough " . $this->typeName . " for this transaction!"
                    ));
                } else {
                    
                    $bank = $money * $this->tax;
                    
                    $update = $this->db->prepare("
                        UPDATE gangs SET " . $this->type . " = " . $this->type . " + :deposit WHERE G_id = :id
                    ");
                    $update->bindParam(":deposit", $bank);
                    $update->bindParam(":id", $this->user->info->US_gang);
                    $update->execute();
                    
                    if ($this->type == "G_money") {
                        $this->alerts[] = $this->page->buildElement("success", array(
                            "text"=>"You sent $".number_format($money)." to your money launder, he in return deposits $".number_format($bank)." into your gangs bank account!"
                        ));
                        $g->log("deposited $".number_format($money));
                    } else {
                        $this->alerts[] = $this->page->buildElement("success", array(
                            "text"=>"You deposited ".number_format($bank)." bullets into your gangs bank account!"
                        ));
                        $g->log("deposited ".number_format($money) . " bullets");
                    } 
                    
                    $this->user->set($this->userType, $this->user->info->{$this->userType} - $money);
                    
                }
                
            }
            
        }
        
    }

?>