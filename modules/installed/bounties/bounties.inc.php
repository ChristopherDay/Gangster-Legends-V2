<?php

    class bounties extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "submit" => array( "type" => "POST" ),
            "user" => array( "type" => "POST" ),
            "cost" => array( "type" => "POST" ),
            "anon" => array( "type" => "POST" )
        );
        
        public $pageName = '';

        public function method_remove() {
            $bounty = $this->db->prepare("
                SELECT * FROM bounties WHERE B_id = :id
            ");

            $bounty->bindParam(":id", $this->methodData->id);
            $bounty->execute();

            $bounty = $bounty->fetch(PDO::FETCH_ASSOC);

            if (!$bounty["B_id"]) {
                return $this->error("This bounty does not exist");
            }

            if ($bounty["B_cost"] > $this->user->info->US_money) {
                return $this->error("You dont have enough money to buy this bounty off!");
            }

            $del = $this->db->prepare("
                DELETE FROM bounties WHERE B_id = :id;
                UPDATE userStats SET US_money = US_money - :cost WHERE US_id = :uid;
            ");
     
            $actionHook = new hook("userAction");
            $action = array(
                "user" => $this->user->id, 
                "module" => "bounties.remove", 
                "id" => $bounty["B_userToKill"], 
                "success" => true, 
                "reward" => $bounty["B_cost"]
            );
            $actionHook->run($action);

            $del->bindParam(":id", $this->methodData->id);
            $del->bindParam(":cost", $bounty["B_cost"]);
            $del->bindParam(":uid", $this->user->id);
            $del->execute();

            $this->alerts[] = $this->page->buildElement("success", array(
                "text" => "Detective result has been removed"
            ));

        }

        public function method_new() {

            if (isset($this->methodData->submit)) {
                if (!strlen($this->methodData->user)) {
                    return $this->error("Who are you putting a bounty on?");
                }
                
                $user = new User(false, $this->methodData->user);
    
                if (!isset($user->info->U_id)) {
                    return $this->error("This user does not exist");
                }

                if ($user->info->U_id == $this->user->id) {
                    return $this->error("You cant look for yourself!");
                }

                if (!isset($this->methodData->cost)) {
                    return $this->error("You have not entered a cost");
                }

                if ($this->methodData->cost < 0) {
                    return $this->error("Please enter a cost above $0");
                }

                $cost = $this->methodData->cost;

                if ($cost > $this->user->info->US_money) {
                    return $this->error("You need " . $this->money($cost) . " for this bounty");
                }

                $insert = $this->db->prepare("
                    INSERT INTO bounties (
                        B_user, B_userToKill, B_cost
                    ) VALUES (
                        :user, :toKill, :cost
                    );
                    UPDATE userStats SET US_money = US_money - :cost WHERE US_id = :uid;
                ");

                $user->newNotification("Someone just put a " . $this->money($cost) . " bounty on you");

                $userID = $this->user->id;

                if (isset($this->methodData->anon) && $this->methodData->anon) {
                    $userID = 0;
                }

                $insert->bindParam(":uid", $this->user->id);
                $insert->bindParam(":user", $userID);
                $insert->bindParam(":toKill", $user->info->US_id);
                $insert->bindParam(":cost", $cost);
                $insert->execute();
     
                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "bounties.add", 
                    "id" => $user->info->US_id, 
                    "success" => true, 
                    "reward" => $cost
                );
                $actionHook->run($action);

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "You put a " . $this->money($cost) . " bounty on " . $user->info->U_name
                ));


            }             
            
        }
        
        public function constructModule() {

            $settings = new settings();

            $costPerDetective = $settings->loadSetting("detectiveCost", true, 125000);

            $user = "";

            if (isset($this->methodData->user)) {
                $user = $this->methodData->user;
            }

            $active = $this->db->prepare("
                SELECT 
                    B_userToKill as 'uid',
                    SUM(B_cost) as 'cost'
                FROM bounties
                GROUP BY B_userToKill
                ORDER BY SUM(B_cost) DESC
            ");

            $active->bindParam(":id", $this->user->id);
            $active->execute();

            $bounties = $active->fetchAll(PDO::FETCH_ASSOC);

            foreach ($bounties as $key => $value) {
                $u = new User($value["uid"]);
                $value["user"] = $u->user;

                $individualBounties = $this->db->prepare("
                    SELECT
                        B_id as 'id', 
                        B_user as 'uid',
                        B_userToKill as 'userToKill',
                        B_cost as 'cost'
                    FROM bounties WHERE B_userToKill = :id
                    ORDER BY B_cost DESC
                ");

                $individualBounties->bindParam(":id", $value["uid"]);
                $individualBounties->execute();

                $individualBounties = $individualBounties->fetchAll(PDO::FETCH_ASSOC);

                $value["bounties"] = array();

                foreach ($individualBounties as $k => $v) {
                    $bounty = $v;
                    $u = new User($v["uid"]);
                    $bounty["user"] = $u->user;
                    $value["bounties"][] = $bounty;
                }

                $bounties[$key] = $value;
            }

            $this->html .= $this->page->buildElement("bounties", array(
                "detectiveCost" => $costPerDetective, 
                "bounties" => $bounties,
                "user" => $user
            ));
        }

    }

