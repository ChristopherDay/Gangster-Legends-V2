<?php

    class adminModule {

        private function getUserRole($roleID = false) {

            if ($roleID) {
                $add = " WHERE UR_id = :id";
            } else {
                $add = "";
            }
            
            $role = $this->db->prepare("
                SELECT
                    UR_id as 'id',  
                    UR_desc as 'name', 
                    UR_color as 'color'   
                FROM userRoles
                " . $add . "
                ORDER BY UR_desc"
            );

            if ($roleID) {
                $role->bindParam(":id", $roleID);
            }

            $role->execute();



            if ($roleID) {
                $r = $role->fetch(PDO::FETCH_ASSOC);

                $access = $this->db->prepare("
                    SELECT * FROM roleAccess WHERE RA_role = :id;
                ");
                $access->bindParam(":id", $r["id"]);
                $access->execute();
                $access = $access->fetchAll(PDO::FETCH_ASSOC);

                $r["access"] = array();

                foreach ($access as $key => $value) {
                    $r["access"][] = $value["RA_module"];
                }

                return $r;
            } else {
                return $role->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        private function validateUserRole($user) {
            $errors = array();

            if (strlen($user["name"]) < 3) {
                $errors[] = "User role name is to short, this must be atleast 3 characters";
            }

            return $errors;
            
        }

        public function method_new () {

            if (isset($this->methodData->submit)) {
                $role = (array) $this->methodData;
                $errors = $this->validateUserRole($role);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array(
                            "text" => $error
                        ));
                    }
                } else {

                    $insert = $this->db->prepare("
                        INSERT INTO userRoles (
                            UR_desc,
                            UR_color
                        ) VALUES (                         
                            :name, 
                            :color
                        );
                    ");
                    $insert->bindParam(":name", $this->methodData->name);
                    $insert->bindParam(":color", $this->methodData->color);
                    $insert->execute();

                    $id = $this->db->lastInsertId();

                    $this->updateAccess($id);

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This user role has been added"
                    ));

                }

            }

            $role["editType"] = "new";
            $role["modules"] = $this->page->modules;
            $role["canAlterModules"] = true;
            
            array_unshift($role["modules"], array(
                "admin" => true, 
                "name" => "All Modules", 
                "pageName" => "All Modules", 
                "id" => "*"
            ));

            $this->html .= $this->page->buildElement("roleForm", $role);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No user role ID specified"
                ));
            }

            $role = $this->getUserRole($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $role = (array) $this->methodData;
                $errors = $this->validateUserRole($role);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array(
                            "text" => $error
                        ));
                    }
                } else {

                    $update = $this->db->prepare("
                        UPDATE 
                            userRoles
                        SET 
                            UR_desc = :name, 
                            UR_color = :color
                        WHERE 
                            UR_id = :id;
                    ");
                    $update->bindParam(":name", $this->methodData->name);
                    $update->bindParam(":color", $this->methodData->color);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    $this->updateAccess($this->methodData->id);

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This user role has been updated"
                    ));

                }

            }

            $role["editType"] = "edit";
            $role["modules"] = $this->page->modules;
            $role["canAlterModules"] = true;//intval($role["id"] > 3);
            
            array_unshift($role["modules"], array(
                "admin" => true, 
                "pageName" => "All Modules", 
                "name" => "All Modules", 
                "id" => "*"
            ));

            if (!isset($role["access"])) $role["access"] = array();

            foreach ($role["modules"] as $key => $value) {
                $role["modules"][$key]["selected"] = in_array($value["id"], $role["access"]);
            }

            $this->html .= $this->page->buildElement("roleForm", $role);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No user role ID specified"
                ));
            }

            $user = $this->getUserRole($this->methodData->id);

            if (!isset($user["id"])) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "This user does not exist"
                ));
            }

            if ($user["id"] == 1 || $user["id"] == 2 || $user["id"] == 3) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "You can not delete this role"
                ));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM userRoles WHERE UR_id = :id;
                    DELETE FROM roleAccess WHERE RA_role = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=userRoles&action=view");

            }


            $this->html .= $this->page->buildElement("roleDelete", $user);
        }

        public function method_view () {
            
            if (!isset($this->methodData->user)) {
                $this->methodData->user = "";
            }

            $this->html .= $this->page->buildElement("roleList", array(
                "submit" => true,
                "userRoles" => $this->getUserRole()
            ));

        }

        public function updateAccess($id) {

            switch ((int) $id) {
                case 1: 
                    $this->methodData->access = array();
                break;
                case 2: 
                    $this->methodData->access = array("*");
                break;
                case 3: 
                    $this->methodData->access = array();
                break;
            }

            $remove = $this->db->prepare("
                DELETE FROM roleAccess WHERE RA_role = :id;
            ");
            $remove->bindParam(":id", $id);
            $remove->execute();            

            if (isset($this->methodData->access)) {
                foreach ($this->methodData->access as $module) {
                    $insert = $this->db->prepare("
                        INSERT INTO roleAccess (RA_role, RA_module) VALUES (:id, :module);
                    ");
                    $insert->bindParam(":id", $id);
                    $insert->bindParam(":module", $module);
                    $insert->execute();

                }
            } 

        }

    }