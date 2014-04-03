<?php

    class leaderboards extends module {
        
        public $allowedMethods = array("top10"=>array("type"=>"GET"));
		
		public $pageName = 'Leaderboards';
        
        public function constructModule() {
			
			switch ($this->methodData->top10) {
				case 'money': $order = 'US_money'; $title = "Top 10 Richest Players"; break;
				case 'rank': $order = 'US_rank DESC, US_exp'; $title = "Top 10 Players"; break;
				default: $order = 'US_money'; $title = "Top 10 Richest Players"; break;
			}
            
			$select = $this->db->prepare("SELECT * FROM userStats ORDER BY ".$order." DESC LIMIT 0, 10");
			$select->execute();
			
			$i = 1;
			$rows = '';
			
			while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
				$username = new user($row['US_id']);
				$rows .= $this->page->buildElement('leaderboardRow', array($i, $username->info->U_name, $username->info->U_id)); 
				unset($username);
				$i++;
			}
			
            $this->html .= $this->page->buildElement('buttons', array());
            $this->html .= $this->page->buildElement('leaderboard', array($title, $rows));
            
        }
        
    }

?>