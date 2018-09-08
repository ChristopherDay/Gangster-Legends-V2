<?php

    class leaderboards extends module {
        
        public $allowedMethods = array("top10"=>array("type"=>"GET"));
		
		public $pageName = 'Leaderboards';
        
        public function constructModule() {
			
			switch (@$this->methodData->top10) {
				case 'rank': 
					$order = 'US_rank DESC, US_exp'; 
					$title = "Top 10 Players"; 
				break;
				default: 
					$order = 'US_money'; 
					$title = "Top 10 Richest Players"; 
				break;
			}
            
			$select = $this->db->prepare("
				SELECT * FROM userStats ORDER BY ".$order." DESC LIMIT 0, 10
			");
			$select->execute();
			
			$i = 1;
			$users = array();
			
			while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
				$username = new user($row['US_id']);
				$users[] = array(
					"rank" => $i, 
					"name" => $username->info->U_name, 
					"id" => $username->info->U_id
				); 
				$i++;
			}
			
            $this->html .= $this->page->buildElement('leaderboard', array(
            	"title" => $title, 
            	"users" => $users
            ));
            
        }
        
    }

?>