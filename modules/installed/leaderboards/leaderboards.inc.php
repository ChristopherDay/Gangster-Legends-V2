<?php

    class leaderboards extends module {
        
        public $allowedMethods = array("top10"=>array("type"=>"GET"));
        
        public $pageName = 'Leaderboards';
        
        public function constructModule() {
            
            if (!isset($this->methodData->top10)) {
                $this->methodData->top10 = "";
            }

            $round = new Round();

            switch (@$this->methodData->top10) {
                case 'rank': 
                    $type = "rank";
                    $order = 'US_exp'; 
                    $title = "Top 10 Players"; 
                break;
                default: 
                    $type = "money";
                    $order = '(US_money + US_bank)'; 
                    $title = "Top 10 Richest Players"; 
                break;
            }

            $select = $this->db->selectAll("
                SELECT 
                    * 
                FROM 
                    userStats
                    INNER JOIN users ON (US_id = U_id) 
                WHERE 
                    U_userLevel = 1 AND 
                    U_status != 0 AND
                    U_round = :round
                ORDER BY ".$order." DESC LIMIT 0, 10
            ", array(
                ":round" => $round->id
            ));
            
            $i = 1;
            $users = array();
            
            foreach ($select as $row) {
                $u = new User($row['U_id']);

                if ($type == 'rank') {
                    $rank = $u->getRank()->R_name;
                } else if ($type == 'money') {
                    $rank = $u->getMoneyRank()->MR_desc;
                }

                $users[] = array(
                    "number" => $i, 
                    "user" => $u->user, 
                    "id" => $row['U_id'], 
                    "rank" => $rank
                ); 
                $i++;
            }
            
            $this->html .= $this->page->buildElement('leaderboard', array(
                "title" => $title, 
                "users" => $users
            ));
            
        }
        
    }

