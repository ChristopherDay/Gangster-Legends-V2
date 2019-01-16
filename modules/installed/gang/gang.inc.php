<?php

    class gang extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "name" => array( "type" => "POST" ),
            "location" => array( "type" => "POST" )
        );
		
		public $pageName = '';

        public function getGang($id) {
            $gang = $this->db->prepare("
                SELECT 
                    G_id as 'id',
                    G_name as 'name',
                    G_desc as 'desc',
                    G_boss as 'boss',
                    G_underboss as 'underboss',
                    G_location as 'location'
                FROM
                    gangs
                    INNER JOIN locations ON (G_location = L_id)
                WHERE 
                    G_id = :id
            ");

            $gang->bindParam(":id", $id);
            $gang->execute();

            $gang = $gang->fetch(PDO::FETCH_ASSOC);

            $gang["members"] = $this->getGangMembers($gang);

            return $gang;
        }

        public function getGangMembers($gang) {
            
            $members = array();

            $users = $this->db->prepare("
                SELECT * FROM users INNER JOIN userStats ON (US_id = U_id) WHERE US_gang = :gang 
            ");
            $users->bindParam(":gang", $gang["id"]);
            $users->execute();

            $users = $users->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $u = new User($user["U_id"]);

                $gangRank = "Member";

                if ($gang["boss"] == $u->id) {
                    $gangRank = "Boss";
                }

                if ($gang["underboss"] == $u->id) {
                    $gangRank = "Underboss";
                }

                $members[] = array(
                    "user" => $u->user, 
                    "gangRank" => $gangRank
                );
            }

            return $members;
        }

        public function method_home() {
            $this->construct = false;
            $gang = $this->getGang($this->user->info->US_gang);
            $this->html .= $this->page->buildElement("gangHome", $gang);
        }

        public function method_view() {
            $this->construct = false;
            $id = $this->methodData->id;
            $gang = $this->getGang($id);
            $this->html .= $this->page->buildElement("gangOverview", $gang);
        }

        public function method_new() {

            if (
                !isset($this->methodData->name) ||
                !isset($this->methodData->location)
            ) {
                return;
            }

            $name = $this->methodData->name;
            $location = $this->methodData->location;
            $settings = new Settings();
            $cost = $settings->loadSetting("gangCost", true, 1000000);

            if ($this->user->info->US_gang) {
                return $this->error("You are already part of a gang, please leave that before starting a new gang!");
            }

            if ($this->user->info->US_money < $cost) {
                return $this->error("You need $" . number_format($cost) . " to start a gang!");
            }

            if (strlen($name) < 4 || strlen($name) > 24) {
                return $this->error("Your gang name needs to be between 4-24 characters!");
            }

            $gangExists = $this->db->prepare("
                SELECT * FROM gangs WHERE G_location = :location
            ");
            $gangExists->bindParam(":location", $location);
            $gangExists->execute();

            $gangExists = $gangExists->fetch(PDO::FETCH_ASSOC);

            if (isset($gangExists["G_id"])) {
                return $this->error("A gang already exists in this location");
            }

            $this->user->set("US_money", $this->user->info->US_money - $cost);

            $insert = $this->db->prepare("
                INSERT INTO gangs (
                    G_name, 
                    G_boss,
                    G_location
                ) VALUES (
                    :name, 
                    :boss, 
                    :location
                );
            ");
            $insert->bindParam(":name", $name);
            $insert->bindParam(":location", $location);
            $insert->bindParam(":boss", $this->user->id);
            $insert->execute();

            $id = $this->db->lastInsertId();

            $this->user->set("US_gang", $id);

        }

        public function constructModule() {

            $settings = new Settings();

            $locations = $this->db->prepare("
                SELECT
                    L_id as 'locationID', 
                    L_name as 'locationName', 
                    G_id as 'gangID',
                    G_boss as 'boss',
                    G_name as 'gangName', 
                    G_level * 5 as 'maxMembers', 
                    COUNT(US_gang) as 'members'
                FROM locations
                LEFT OUTER JOIN gangs ON (G_location = L_id)
                LEFT OUTER JOIN userStats ON (US_gang = G_id)
                WHERE G_id IS NOT NULL
                GROUP BY L_id 
                ORDER BY COUNT(US_gang)
            ");
            $locations->execute();

            $allLocations = $locations->fetchAll(PDO::FETCH_ASSOC);

            $available = $this->db->prepare("
                SELECT
                    L_id as 'locationID', 
                    L_name as 'locationName'
                FROM locations
                LEFT OUTER JOIN gangs ON (G_location = L_id)
                WHERE G_id IS NULL
                GROUP BY L_id 
            ");
            $available->execute();

            $availableLocations = $available->fetchAll(PDO::FETCH_ASSOC);

            foreach ($allLocations as $key => $value) {
                $user = new User($value["boss"]);
                $allLocations[$key]["user"] = $user->user;
                $allLocations[$key]["percent"] = $value["members"] / $value["maxMembers"] * 100;
            }

            $this->html .= $this->page->buildElement("gangs", array(
                "user" => $this->user->user, 
                "availableLocations" => $availableLocations, 
            	"locations" => $allLocations, 
                "gangCost" => $settings->loadSetting("gangCost", true, 1000000)
            ));
        }
        
        public function error($text, $type = "error") {
            $this->alerts[] = $this->page->buildElement($type, array(
                "text" => $text
            ));
        }
        
    }

?>