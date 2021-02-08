<?php

    class adminModule {

        private function getTheft($theftID = "all") {
            if ($theftID == "all") {
                $add = "";
            } else {
                $add = " WHERE T_id = :id";
            }
            
            $sql = "
                SELECT
                    T_id as 'id',  
                    T_name as 'name',  
                    T_chance as 'chance',  
                    T_worstCar as 'worstCar',  
                    T_maxDamage as 'maxDamage',  
                    T_bestCar as 'bestCar'
                FROM theft" . $add . "
                ORDER BY T_chance, T_maxDamage";

            if ($theftID == "all") {
                return $this->db->selectAll($sql);
            } else {
                return $this->db->select($sql, array(
                    ":id" => $theftID
                ));
            }
        }

        private function validateTheft($theft) {
            $errors = array();

            if ($theft["chance"] > 100) {
                $errors[] = "the chance must be below 100%";
            }
            if (strlen($theft["name"]) < 6) {
                $errors[] = "Theft name is to short, this must be atleast 5 characters";
            }
            if (intval($theft["worstCar"]) > intval($theft["bestCar"])) {
                $errors[] = "The minimum value is greater then the maximum value";
            }
            if (!intval($theft["bestCar"])) {
                $errors[] = "No maximum value specified";
            } 
            if (!intval($theft["worstCar"])) {
                $errors[] = "No minimum value specified";
            } 
            if (!intval($theft["chance"])) {
                $errors[] = "No chance specified";
            }

            return $errors;
            
        }

        public function method_new () {

            $theft = array();

            if (isset($this->methodData->submit)) {
                $theft = (array) $this->methodData;
                $errors = $this->validateTheft($theft);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $insert = $this->db->insert("
                        INSERT INTO theft (T_name, T_chance, T_worstCar, T_maxDamage, T_bestCar)  VALUES (:name, :chance, :worstCar, :maxDamage, :bestCar);
                    ", array(
                        ":name" => $this->methodData->name,
                        ":chance" => $this->methodData->chance,
                        ":worstCar" => $this->methodData->worstCar,
                        ":maxDamage" => $this->methodData->maxDamage,
                        ":bestCar" => $this->methodData->bestCar
                    ));

                    $this->html .= $this->page->buildElement("success", array("text" => "This theft has been created"));

                }

            }

            $theft["editType"] = "new";
            $this->html .= $this->page->buildElement("theftForm", $theft);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No theft ID specified"));
            }

            $theft = $this->getTheft($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $theft = (array) $this->methodData;
                $errors = $this->validateTheft($theft);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->update("
                        UPDATE theft SET T_name = :name, T_chance = :chance, T_worstCar = :worstCar, T_maxDamage = :maxDamage, T_bestCar = :bestCar WHERE T_id = :id
                    ", array(
                        ":name" => $this->methodData->name,
                        ":chance" => $this->methodData->chance,
                        ":worstCar" => $this->methodData->worstCar,
                        ":maxDamage" => $this->methodData->maxDamage,
                        ":bestCar" => $this->methodData->bestCar,
                        ":id" => $this->methodData->id
                    ));

                    $this->html .= $this->page->buildElement("success", array("text" => "This theft has been updated"));

                }

            }

            $theft["editType"] = "edit";
            $this->html .= $this->page->buildElement("theftForm", $theft);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No theft ID specified"));
            }

            $theft = $this->getTheft($this->methodData->id);

            if (!isset($theft["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This theft does not exist"));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->delete("DELETE FROM theft WHERE T_id = :id;", array(
                    ":id" => $this->methodData->id
                ));

                header("Location: ?page=admin&module=theft");
                exit;

            }


            $this->html .= $this->page->buildElement("theftDelete", $theft);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("theftList", array(
                "theft" => $this->getTheft()
            ));

        }

    }
