<?php

    class adminModule {

        private function getPackage($premiumMembershipID = "all") {
            if ($premiumMembershipID == "all") {
                $add = "";
            } else {
                $add = " WHERE PM_id = :id";
            }
            
            $premiumMembership = $this->db->prepare("
                SELECT
                    PM_id as 'id',  
                    PM_desc as 'desc',  
                    PM_cost as 'cost',  
                    PM_seconds as 'seconds'
                FROM premiumMembership" . $add . "
                ORDER BY PM_desc, PM_cost"
            );

            if ($premiumMembershipID == "all") {
                $premiumMembership->execute();
                return $premiumMembership->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $premiumMembership->bindParam(":id", $premiumMembershipID);
                $premiumMembership->execute();
                return $premiumMembership->fetch(PDO::FETCH_ASSOC);
            }
        }

        private function validatePackage($premiumMembership) {
            $errors = array();

            if (strlen($premiumMembership["desc"]) < 3) {
                $errors[] = "Package description is to short, this must be atleast 5 characters";
            }
            
            if (intval($premiumMembership["cost"]) < 0) {
                $errors[] = "The cost must be greater than 0";
            }

            if (intval($premiumMembership["seconds"]) < 0) {
                $errors[] = "The time must be greater than 0";
            }

            return $errors;
            
        }

        public function method_new () {

            $premiumMembership = array();

            if (isset($this->methodData->submit)) {
                $premiumMembership = (array) $this->methodData;
                $errors = $this->validatePackage($premiumMembership);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array(
                            "text" => $error
                        ));
                    }
                } else {

                    $insert = $this->db->insert("
                        INSERT INTO premiumMembership (PM_desc, PM_cost, PM_seconds)  VALUES (:desc, :cost, :seconds);
                    ", array(
                        ":desc" =>  $this->methodData->desc,
                        ":cost" =>  $this->methodData->cost,
                        ":seconds" =>  $this->methodData->seconds
                    ));

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This package has been created"
                    ));

                }

            }

            $premiumMembership["editType"] = "new";
            $this->html .= $this->page->buildElement("premiumMembershipForm", $premiumMembership);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No package ID specified"));
            }

            $premiumMembership = $this->getPackage($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $premiumMembership = (array) $this->methodData;
                $errors = $this->validatePackage($premiumMembership);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->update("
                        UPDATE premiumMembership 
                        SET PM_desc = :desc,  PM_seconds = :seconds, PM_cost = :cost 
                        WHERE PM_id = :id
                    ", array(
                        ":desc" => $this->methodData->desc,
                        ":seconds" => $this->methodData->seconds,
                        ":cost" => $this->methodData->cost,
                        ":id" => $this->methodData->id
                    ));

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This package has been updated"
                    ));

                }

            }

            $premiumMembership["editType"] = "edit";
            $this->html .= $this->page->buildElement("premiumMembershipForm", $premiumMembership);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No package ID specified"));
            }

            $premiumMembership = $this->getPackage($this->methodData->id);

            if (!isset($premiumMembership["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This package does not exist"));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM premiumMembership WHERE PM_id = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=points");

            }

            $this->html .= $this->page->buildElement("premiumMembershipDelete", $premiumMembership);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("premiumMembershipList", array(
                "premiumMembership" => $this->getPackage()
            ));

        }

        public function method_transactions () {
            
            $this->html .= $this->page->buildElement("transactions", array(
                "transactions" => $this->getTransactions()
            ));

        }

        public function method_settings() {

            $settings = new settings();

            if (isset($this->methodData->submit)) {
                $settings->update("membershipLinkName", $this->methodData->membershipLinkName);
                $settings->update("membershipName", $this->methodData->membershipName);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "Settings updated!"
                ));
            }


            $output = array(
                "membershipLinkName" => $settings->loadSetting("membershipLinkName"),
                "membershipName" => $settings->loadSetting("membershipName")
            );

            $this->html .= $this->page->buildElement("settings", $output);


        }

    }
