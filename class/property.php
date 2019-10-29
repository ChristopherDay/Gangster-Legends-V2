<?php

    class Property {

        private $user, $db, $module;

        public function __construct($user, $module) {
            $this ->db = $user->db;
            $this->user = $user;
            $this->module = $module;
        }

        public function getOwnership($location = false) {

            if (!$location) $location = $this->user->info->US_location;

            $property = $this->db->select("
                SELECT
                    PR_id as 'id', 
                    PR_location as 'location',
                    PR_module as 'module', 
                    PR_user as 'user', 
                    PR_profit as 'profit', 
                    PR_cost as 'cost'
                FROM 
                    properties
                WHERE 
                    PR_location = :location AND 
                    PR_module = :module
            ", array(
                ":location" => $location,
                ":module" => $this->module
            ));

            if (!isset($property["id"])) {
                return array(
                    "id" => 0, 
                    "location" => $location, 
                    "module" => $this->module, 
                    "user" => 0, 
                    "profit" => 0,
                    "cost" => 0,
                    "userOwnsThis" => false
                );
            }

            $property["closed"] = false;

            if ($property["user"]) {
                if ($property["user"] == -1) {
                    $property["closed"] = true;
                } else {
                    $user = new User($property["user"]);
                    $property["user"] = $user->user;
                    $property["userOwnsThis"] = $user->id == $this->user->id;
                }
            }

            $property["_profit"] = $property["profit"];
            $property["profit"] = "$" . number_format($property["profit"]);

            return $property;

        }

        public function updateProfit($money) {
            $this->db->update("
                UPDATE  
                    properties 
                SET 
                    PR_profit = PR_profit + :cash 
                WHERE 
                    PR_location = :location AND 
                    PR_module = :module;
            ", array(
                ":cash" => $money,
                ":location" => $this->user->info->US_location,
                ":module" => $this->module
            ));
        }

        public function transfer($newOwner) {

            $currentOwner = $this->getOwnership();

            if ($currentOwner["user"]) {
                $this->db->update("UPDATE  properties SET PR_user = :user, PR_cost = 0 WHERE PR_location = :location AND PR_module = :module;", array(
                    ":location" => $this->user->info->US_location,
                    ":module" => $this->module,
                    ":user" => $newOwner
                ));
            } else {
                $this->db->insert("INSERT INTO properties (PR_location, PR_module, PR_user) VALUES (:location, :module, :user);", array(
                    ":location" => $this->user->info->US_location,
                    ":module" => $this->module,
                    ":user" => $newOwner
                ));
            }

        }

        public function setCost($newCost) {
            $update = $this->db->update("UPDATE properties SET PR_cost = :cost WHERE PR_location = :location AND PR_module = :module;", array(
                ":location" => $this->user->info->US_location,
                ":module" => $this->module,
                ":cost" => $newCost
            ));
        }

    }

?>