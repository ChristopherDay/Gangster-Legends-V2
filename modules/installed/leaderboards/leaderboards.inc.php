<?php

    class leaderboards extends module {
        
        public $allowedMethods = array("top10"=>array("type"=>"GET"));
        
        public $pageName = 'Leaderboards';
        
        public function constructModule() {
            
            switch (@$this->methodData->top10) {
                case 'rank': 
                    $order = 'US_exp'; 
                    $title = "Top 10 Players"; 
                break;
                default: 
                    $order = '(US_money + US_bank)'; 
                    $title = "Top 10 Richest Players"; 
                break;
            }
            
            $select = $this->db->prepare("
                SELECT 
                    * 
                FROM 
                    userStats
                    INNER JOIN users ON (US_id = U_id) 
                WHERE 
                    U_userLevel = 1 AND 
                    U_status != 0
                ORDER BY ".$order." DESC LIMIT 0, 10
            ");
            $select->execute();
            
            $i = 1;
            $users = array();
            
            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                $u = new User($row['U_id']);
                $users[] = array(
                    "rank" => $i, 
                    "user" => $u->user, 
                    "id" => $row['U_id']
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