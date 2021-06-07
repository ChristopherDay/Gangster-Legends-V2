<?php

    class blackmarket extends module {
        
        public $allowedMethods = array(
            "item" => array( "type" => "GET" )
        );
        
        public $pageName = '';
        
        public $weaponType = 1;
        public $armorType = 2;

        public function constructModule() {

            $sql = "
                SELECT
                    I_id as 'id',  
                    I_name as 'name',  
                    I_cost as 'cost',  
                    I_points as 'points',  
                    ROUND(I_damage / 100, 2) as 'damage',  
                    I_type as 'type',  
                    I_rank as 'rank', 
                    CASE I_type
                        WHEN ".$this->weaponType." THEN 'Weapon'
                        WHEN ".$this->armorType." THEN 'Armor'
                        ELSE 'Unknown'
                    END as 'typeDesc'
                FROM items
                INNER JOIN ranks ON R_id = I_rank
                WHERE R_exp <= :userEXP AND I_type = :type
                ORDER BY I_damage
            ";

            $weapons = $this->db->selectAll($sql . "ASC", array(
                ":type" => $this->weaponType,
                ":userEXP" => $this->user->info->US_exp
            ));

            $armor = $this->db->selectAll($sql . "DESC", array(
                ":type" => $this->armorType,
                ":userEXP" => $this->user->info->US_exp
            ));

            array_unshift($weapons, array(
                "id" => 0, 
                "name" => "No weapon", 
                "cantBuy" => true
            ));

            array_unshift($armor, array(
                "id" => 0, 
                "name" => "No armor", 
                "cantBuy" => true
            ));



            $this->html .= $this->page->buildElement("blackMarket", array(
                "location" => $this->user->getLocation(), 
                "weapons" => $this->userOwns($this->user->info->US_weapon, $weapons),
                "armor" => $this->userOwns($this->user->info->US_armor, $armor)
            ));
        }

        public function method_buy() {
            $item = $this->db->select("
                SELECT * FROM items INNER JOIN ranks ON I_rank = R_id WHERE I_id = :id
            ", array(
                ":id" => $this->methodData->item
            ));

            $hook = new Hook("alterModuleData");
            $hookData = array(
                "module" => "blackmarket",
                "user" => $this->user,
                "data" => $item
            );
            $item = $hook->run($hookData, 1)["data"];

            if (!$item["I_id"]) {
                $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "This item does not exist"
                ));
            } else if ($item["I_id"] == $this->user->info->US_weapon || $item["I_id"] == $this->user->info->US_armor) {
                $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "You already own this item"
                ));
            } else if ($item["R_exp"] > $this->user->info->US_exp) {
                $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "You can't buy this item yet"
                ));
            } else if ($item["I_cost"] > $this->user->info->US_money) {
                $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => "You dont have enough money to buy a " . $item["I_name"]
                ));
            } else if ($item["I_points"] > $this->user->info->US_points) {
                $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => 'You dont have enough {_setting "pointsName"} to buy a ' . $item["I_name"]
                ));
            } else {
                $this->alerts[] = $this->page->buildElement('success', array(
                    "text" => "You now own a " . $item["I_name"]
                ));

                switch ((int) $item["I_type"]) {
                    case 1:
                        $col = "US_weapon";
                    break;
                    case 2:
                        $col = "US_armor";
                    break;
                    default:
                        $col = false;
                    break;
                } 

                if ($col) {
                    $update = $this->db->prepare("
                        UPDATE 
                            userStats 
                        SET 
                            ".$col." = :itemID, 
                            US_money = US_money - :cost, 
                            US_points = US_points - :points 
                        WHERE US_id = :user
                    ");

                    $update->bindParam(":itemID", $item["I_id"]);
                    $update->bindParam(":cost", $item["I_cost"]);
                    $update->bindParam(":points", $item["I_points"]);
                    $update->bindParam(":user", $this->user->id);
                    $update->execute();

                    $this->user->info->$col = $item["I_id"];
                    $this->user->info->US_money -= $item["I_cost"];
                    $this->user->info->US_points -= $item["I_points"];


                    
                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $this->user->id, 
                        "module" => "blackmarket", 
                        "id" => $item["I_id"], 
                        "success" => true, 
                        "reward" => 0
                    );
                    $actionHook->run($action);

                }

            }

        }

        public function userOwns($itemID, $items) {
            foreach ($items as $key => $item) {
                $item["owned"] = ($item["id"] == $itemID);

                $hook = new Hook("alterModuleData");
                $hookData = array(
                    "module" => "blackmarket",
                    "user" => $this->user,
                    "data" => $item
                );
                $item = $hook->run($hookData, 1)["data"];

                $items[$key] = $item;
            }
            return $items;
        }
    }

