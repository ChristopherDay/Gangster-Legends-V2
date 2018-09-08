<?php

    class bank extends module {
        
        public $allowedMethods = array(
			'deposit'=>array('type'=>'post'),
			'withdraw'=>array('type'=>'post'),
			'bank'=>array('type'=>'post')
		);
        
        public function constructModule() {
			
			$this->html .= $this->page->buildElement("bank", array(
				"deposit" => '$'.number_format($this->user->info->US_money),
				"withdraw" => '$'.number_format(@$this->user->info->US_bank)
			));
            
        }
		
		public function method_process() {
		
			if ($this->methodData->bank == 'withdraw') {
				
				$money = str_replace(array(',', '$'), array('', ''), $this->methodData->withdraw);
				
				if ($this->user->info->US_bank < $money) {
					
					$this->html .= $this->page->buildElement("error", array("text"=>"You dont have enough money in your bank for this transaction!"));
					
				} else {
					
					$update = $this->db->prepare("UPDATE userStats SET US_bank = US_bank - :w1, US_money = US_money + :w2 WHERE US_id = :id");
					$update->bindParam(":w1", $money);
					$update->bindParam(":w2", $money);
					$update->bindParam(":id", $this->user->info->US_id);
					$update->execute();
					
					$this->html .= $this->page->buildElement("success", array("text"=>"You have withdrawn $".number_format($money)."!"));
					
					$this->user->info->US_money += $money;
					$this->user->info->US_bank -= $money;
					
				}
				
			} else if ($this->methodData->bank == 'deposit') {
				
				$money = str_replace(array(',', '$'), array('', ''), $this->methodData->deposit);
				
				if ($this->user->info->US_money < $money) {
					
					$this->html .= $this->page->buildElement("error", array("text"=>"You dont have enough money for this transaction!"));
					
				} else {
					
					$bank = $money * 0.85;
					
					$update = $this->db->prepare("UPDATE userStats SET US_bank = US_bank + :w1, US_money = US_money - :w2 WHERE US_id = :id");
					$update->bindParam(":w1", $bank);
					$update->bindParam(":w2", $money);
					$update->bindParam(":id", $this->user->info->US_id);
					$update->execute();
					
					$this->html .= $this->page->buildElement("success", array("text"=>"You sent $".number_format($money)." to your money launder, he in return deposits $".number_format($bank)." into your bank account!"));
					
					$this->user->info->US_bank += $bank;
					$this->user->info->US_money -= $money;
					
				}
				
			}
			
		}
        
    }

?>