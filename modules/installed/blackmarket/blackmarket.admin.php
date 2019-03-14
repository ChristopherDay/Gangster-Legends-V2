<?php

    class adminModule {

        private function getItem($itemID = "all") {
            if ($itemID == "all") {
                $add = " WHERE I_type IN (1, 2)";
            } else {
                $add = " WHERE I_type IN (1, 2) AND I_id = :id";
            }
            
            $item = $this->db->prepare("
                SELECT
                    I_id as 'id',  
                    I_name as 'name',  
                    I_cost as 'cost',  
                    I_points as 'points',  
                    ROUND(I_damage / 100, 2) as 'damage',  
                    I_type as 'type',  
                    I_rank as 'rank', 
                    CASE I_type
                        WHEN 1 THEN 'Weapon'
                        WHEN 2 THEN 'Armor'
                        ELSE 'Unknown'
                    END as 'typeDesc'
                FROM items" . $add
            );

            if ($itemID == "all") {
                $item->execute();
                return $item->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $item->bindParam(":id", $itemID);
                $item->execute();
                return $item->fetch(PDO::FETCH_ASSOC);
            }
        }

        private function validateItem($item) {
            $errors = array();

            $item["damage"] *= 100;

            if (strlen($item["name"]) < 6) {
                $errors[] = "Item name is to short, this must be atleast 5 characters";
            }
            if (!intval($item["damage"])) {
                $errors[] = "No damage specified";
            }
            if (!intval($item["type"])) {
                $errors[] = "No type specified";
            }
            if (!intval($item["rank"])) {
                $errors[] = "No rank specified";
            } 

            return $errors;
            
        }

        public function method_new () {

            $item = array();

            if (isset($this->methodData->submit)) {
                $item = (array) $this->methodData;
                $errors = $this->validateItem($item);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {

                    $this->methodData->damage =  100;

                    $insert = $this->db->prepare("
                        INSERT INTO items (I_name, I_damage, I_cost, I_rank, I_type, I_points)  VALUES (:name, :damage, :cost, :rank, :type, :points);
                    ");
                    $insert->bindParam(":name", $this->methodData->name);
                    $insert->bindParam(":damage", $this->methodData->damage);
                    $insert->bindParam(":cost", $this->methodData->cost);
                    $insert->bindParam(":points", $this->methodData->points);
                    $insert->bindParam(":rank", $this->methodData->rank);
                    $insert->bindParam(":type", $this->methodData->type);
                    $insert->execute();


                    $this->html .= $this->page->buildElement("success", array("text" => "This item has been created"));

                }

            }

            $item["editType"] = "new";
            $this->html .= $this->page->buildElement("itemForm", $item);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No item ID specified"));
            }

            $item = $this->getItem($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $item = (array) $this->methodData;
                $errors = $this->validateItem($item);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $this->methodData->damage *= 100;
                    $update = $this->db->prepare("
                        UPDATE items SET I_name = :name, I_damage = :damage, I_cost = :cost, I_points = :points, I_rank = :rank, I_type = :type WHERE I_id = :id
                    ");
                    $update->bindParam(":name", $this->methodData->name);
                    $update->bindParam(":damage", $this->methodData->damage);
                    $update->bindParam(":cost", $this->methodData->cost);
                    $update->bindParam(":rank", $this->methodData->rank);
                    $update->bindParam(":points", $this->methodData->points);
                    $update->bindParam(":type", $this->methodData->type);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    $this->html .= $this->page->buildElement("success", array("text" => "This item has been updated"));

                }

            }

            $item["editType"] = "edit";
            $this->html .= $this->page->buildElement("itemForm", $item);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No item ID specified"));
            }

            $item = $this->getItem($this->methodData->id);

            if (!isset($item["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This item does not exist"));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM items WHERE I_id = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=blackmarket");

            }


            $this->html .= $this->page->buildElement("itemDelete", $item);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("itemList", array(
                "items" => $this->getItem()
            ));

        }

        public function method_calculator () {

            $weapons = $this->db->prepare("
                SELECT
                    I_id as 'id',  
                    I_name as 'name'
                FROM items
                WHERE I_type = 1
            ");
            $weapons->execute();

            $this->html .= $this->page->buildElement("weaponSelect", array(
                "weapons" => $weapons->fetchAll(PDO::FETCH_ASSOC)
            ));


            if (isset($this->methodData->weapon)) {
    
                $weapon = $this->getItem($this->methodData->weapon);

                $armor = $this->db->prepare("
                    SELECT * FROM items 
                    INNER JOIN ranks ON (R_id = I_rank)
                    WHERE I_type = 2 ORDER BY I_damage DESC
                ");
                $armor->execute();
                $armor = $armor->fetchAll(PDO::FETCH_ASSOC);

                $ranks = $this->db->prepare("SELECT * from ranks ORDER BY R_exp ASC");
                $ranks->execute();
                $ranks = $ranks->fetchAll(PDO::FETCH_ASSOC);

                $rows = array();
                $cols = array();

                array_unshift($armor, array(
                    "I_name" => "No armor", 
                    "I_damage" => 100, 
                    "R_exp" => 0
                ));

                foreach ($armor as $a) {
                    $cols[] = array( "name" => $a["I_name"], "damage" => round($a["I_damage"] / 100, 2) );
                }

                foreach ($ranks as $rank) {
                    $row = array("cols" => array());
                    $row["cols"][] = array( "header" => true, "data" => $rank["R_name"] );
                    $row["cols"][] = array( "header" => true, "data" => number_format($rank["R_health"]) );
                    foreach ($armor as $a) {
                        $bullets = $rank["R_health"]  / ($a["I_damage"] / 100) / ($weapon["damage"]);
                        $bulletsToKill = number_format($bullets);
                        //$this->html .= debug($a, 1, 1);
                        //$this->html .= debug($rank, 1, 1);
                        if ($a["R_exp"] > $rank["R_exp"]) {
                            $bulletsToKill = "--";
                        }
                        $row["cols"][] = array( 
                            "data" => $bulletsToKill 
                        );
                    }
                    $rows[] = $row;
                }

                $this->html .= $this->page->buildElement("calculator", array(
                    "colCount" => count($cols),
                    "weapon" => $weapon["name"],
                    "damage" => $weapon["damage"],
                    "rows" => $rows,
                    "cols" => $cols
                ));

            }
        }

    }