<?php

    class adminModule {

        private function getCrime($crimeID = "all") {
            if ($crimeID == "all") {
                $add = "";
            } else {
                $add = " WHERE C_id = :id";
            }
            
            $crime = $this->db->prepare("
                SELECT
                    C_id as 'id',  
                    C_name as 'name',  
                    C_exp as 'exp',  
                    C_cooldown as 'cooldown',  
                    C_money as 'money',  
                    C_maxMoney as 'maxMoney',  
                    C_bullets as 'bullets',  
                    C_maxBullets as 'maxBullets',  
                    C_level as 'level'
                FROM crimes" . $add . "
                ORDER BY C_level, C_money"
            );

            if ($crimeID == "all") {
                $crime->execute();
                return $crime->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $crime->bindParam(":id", $crimeID);
                $crime->execute();
                return $crime->fetch(PDO::FETCH_ASSOC);
            }
        }

        private function validateCrime($crime) {
            $errors = array();

            if (strlen($crime["name"]) < 6) {
                $errors[] = "Crime name is to short, this must be atleast 5 characters";
            }
            if (intval($crime["money"]) > intval($crime["maxMoney"])) {
                $errors[] = "The maximum reward is greater then the minimum reward";
            }
            if (!intval($crime["level"])) {
                $errors[] = "No level specified";
            } 
            if (!intval($crime["cooldown"])) {
                $errors[] = "No cooldown specified";
            }

            return $errors;
            
        }

        public function method_new () {

            $crime = array();

            if (isset($this->methodData->submit)) {
                $crime = (array) $this->methodData;
                $errors = $this->validateCrime($crime);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $insert = $this->db->prepare("
                        INSERT INTO crimes (C_name, C_cooldown, C_money, C_maxMoney, C_level, C_bullets, C_maxBullets, C_exp)  VALUES (:name, :cooldown, :money, :maxMoney, :level, :bullets, :maxBullets, :exp);
                    ");
                    $insert->bindParam(":name", $this->methodData->name);
                    $insert->bindParam(":cooldown", $this->methodData->cooldown);
                    $insert->bindParam(":money", $this->methodData->money);
                    $insert->bindParam(":maxMoney", $this->methodData->maxMoney);
                    $insert->bindParam(":level", $this->methodData->level);
                    $insert->bindParam(":bullets", $this->methodData->bullets);
                    $insert->bindParam(":maxBullets", $this->methodData->maxBullets);
                    $insert->bindParam(":exp", $this->methodData->exp);
                    $insert->execute();


                    $this->html .= $this->page->buildElement("success", array("text" => "This crime has been created"));

                }

            }

            $crime["editType"] = "new";
            $this->html .= $this->page->buildElement("crimeForm", $crime);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No crime ID specified"));
            }

            $crime = $this->getCrime($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $crime = (array) $this->methodData;
                $errors = $this->validateCrime($crime);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->prepare("
                        UPDATE crimes SET C_name = :name, C_cooldown = :cooldown, C_money = :money, C_maxMoney = :maxMoney, C_level = :level, C_bullets = :bullets, C_maxBullets = :maxBullets, C_exp = :exp WHERE C_id = :id
                    ");
                    $update->bindParam(":name", $this->methodData->name);
                    $update->bindParam(":cooldown", $this->methodData->cooldown);
                    $update->bindParam(":money", $this->methodData->money);
                    $update->bindParam(":maxMoney", $this->methodData->maxMoney);
                    $update->bindParam(":level", $this->methodData->level);
                    $update->bindParam(":bullets", $this->methodData->bullets);
                    $update->bindParam(":maxBullets", $this->methodData->maxBullets);
                    $update->bindParam(":exp", $this->methodData->exp);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    $this->html .= $this->page->buildElement("success", array("text" => "This crime has been updated"));

                }

            }

            $crime["editType"] = "edit";
            $this->html .= $this->page->buildElement("crimeForm", $crime);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No crime ID specified"));
            }

            $crime = $this->getCrime($this->methodData->id);

            if (!isset($crime["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This crime does not exist"));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM crimes WHERE C_id = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=crimes");

            }


            $this->html .= $this->page->buildElement("crimeDelete", $crime);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("crimeList", array(
                "crimes" => $this->getCrime()
            ));

        }

    }