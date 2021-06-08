<?php

    class adminModule {

        private function getLocations($locationsID = "all") {
            if ($locationsID == "all") {
                $add = "";
            } else {
                $add = " WHERE L_id = :id";
            }
            
            $sql = "
                SELECT
                    L_id as 'id',  
                    L_name as 'name',
                    L_cost as 'cost',  
                    L_bullets as 'bullets',
                    L_bulletCost as 'bulletCost',
                    L_cooldown as 'cooldown'
                FROM locations" . $add . "
                ORDER BY L_name, L_cost";

            if ($locationsID == "all") {
                return $this->db->selectAll($sql);
            } else {
                return $this->db->select($sql, array(
                    ":id" => $locationsID
                ));
            }
        }

        private function validateLocations($locations) {
            $errors = array();

            if (strlen($locations["name"]) < 3) {
                $errors[] = "Location name is to short, this must be at least 5 characters";
            }
            
            
            if (!intval($locations["cost"])) {
                $errors[] = "No cost specified";
            }

            return $errors;
            
        }

        public function method_new () {

            $locations = array();

            if (isset($this->methodData->submit)) {
                $locations = (array) $this->methodData;
                $errors = $this->validateLocations($locations);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $insert = $this->db->insert("
                        INSERT INTO locations (L_name, L_cost, L_bullets, L_bulletCost, L_cooldown)  VALUES (:name, :cost, :bullets, :bulletCost, :cooldown);
                    ", array(
                        ":name" => $this->methodData->name,
                        ":cost" => $this->methodData->cost,
                        ":bullets" => $this->methodData->bullets,
                        ":bulletCost" => $this->methodData->bulletCost,
                        ":cooldown" => $this->methodData->cooldown
                    ));

                    $this->html .= $this->page->buildElement("success", array("text" => "This location has been created"));

                }

            }

            $locations["editType"] = "new";
            $this->html .= $this->page->buildElement("locationsForm", $locations);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No location ID specified"));
            }

            $locations = $this->getLocations($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $locations = (array) $this->methodData;
                $errors = $this->validateLocations($locations);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->update("
                        UPDATE locations SET L_name = :name, L_cost = :cost, L_bullets = :bullets, L_bulletCost = :bulletCost, L_cooldown = :cooldown WHERE L_id = :id
                    ", array(
                        ":name" => $this->methodData->name,
                        ":cost" => $this->methodData->cost,
                        ":bullets" => $this->methodData->bullets,
                        ":bulletCost" => $this->methodData->bulletCost,
                        ":cooldown" => $this->methodData->cooldown,
                        ":id" => $this->methodData->id
                    ));

                    $this->html .= $this->page->buildElement("success", array("text" => "This location has been updated"));

                }

            }

            $locations["editType"] = "edit";
            $this->html .= $this->page->buildElement("locationsForm", $locations);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No location ID specified"));
            }

            $locations = $this->getLocations($this->methodData->id);

            if (!isset($locations["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This location does not exist"));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->delete("
                    DELETE FROM locations WHERE L_id = :id;
                ", array(
                    ":id" => $this->methodData->id
                ));
                $delete->execute();

                header("Location: ?page=admin&module=locations");

            }


            $this->html .= $this->page->buildElement("locationsDelete", $locations);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("locationsList", array(
                "locations" => $this->getLocations()
            ));

        }

    }
