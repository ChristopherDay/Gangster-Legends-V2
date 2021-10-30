<?php

    class adminModule {

        private function getUser($userID, $search = false) {

            if ($userID === false) {
                return array();
            }

            if ($search) {
                $add = " WHERE U_id = :id OR U_name LIKE :search OR U_email LIKE :search";
            } else {
                $add = " WHERE U_id = :id";
            }
            
            $sql = ("
                SELECT
                    U_id as 'id',  
                    U_name as 'name', 
                    U_email as 'email', 
                    U_status as 'userStatus', 
                    U_userLevel as 'userLevel',   
                    US_money as 'money', 
                    US_exp as 'exp', 
                    US_bank as 'bank', 
                    US_points as 'points', 
                    US_bullets as 'bullets', 
                    R_name as 'round',
                    US_bio as 'bio', 
                    US_pic as 'pic'
                FROM users
                INNER JOIN userStats ON US_id = U_id
                LEFT OUTER JOIN rounds ON (R_id = U_round)
                " . $add . "
                ORDER BY U_name"
            );

            if ($search) {
                $searchTerm = "%".$userID."%";
                return $this->db->selectAll($sql, array(
                    ":id" => $userID,
                    ":search" => $searchTerm
                ));
            } else {
                return $this->db->select($sql, array(
                    ":id" => $userID
                ));
            }
        }

        private function validateUser($user) {
            $errors = array();

            if (strlen($user["name"]) < 2) {
                $errors[] = "User name is to short, this must be atleast 2 characters";
            }

            if ($user["id"] == 1 && $user["userLevel"] != 2) {
                $errors[] = "User ID 1 must be an admin";
            }

            return $errors;
            
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No user ID specified"));
            }

            $user = $this->getUser($this->methodData->id);

            $userRoles = $this->db->selectAll("
                SELECT 
                    UR_id as 'id', 
                    UR_desc as 'name'
                FROM 
                    userRoles 
                ORDER BY UR_desc
            ");

            if (isset($this->methodData->submit)) {
                $user = (array) $this->methodData;
                $errors = $this->validateUser($user);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->update("
                        UPDATE 
                            users 
                        SET 
                            U_name = :name, 
                            U_email = :email, 
                            U_status = :userStatus, 
                            U_userLevel = :userLevel 
                        WHERE 
                            U_id = :id;
                    ", array(
                        ":name" => $this->methodData->name,
                        ":email" => $this->methodData->email,
                        ":userStatus" => $this->methodData->userStatus,
                        ":userLevel" => $this->methodData->userLevel,
                        ":id" => $this->methodData->id
                    ));

                    $update = $this->db->update("
                        UPDATE 
                            userStats 
                        SET 
                            US_pic = :pic, 
                            US_bio = :bio, 
                            US_bullets = :bullets, 
                            US_points = :points, 
                            US_bank = :bank, 
                            US_exp = :exp, 
                            US_money = :money 
                        WHERE 
                            US_id = :id;
                    ", array(
                        ":pic" => $this->methodData->pic,
                        ":bio" => $this->methodData->bio,
                        ":bullets" => $this->methodData->bullets,
                        ":points" => $this->methodData->points,
                        ":bank" => $this->methodData->bank,
                        ":exp" => $this->methodData->exp,
                        ":money" => $this->methodData->money,
                        ":id" => $this->methodData->id
                    ));

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This user has been updated"
                    ));

                }

            }

            $user["editType"] = "edit";

            $user["userRoles"] = $userRoles;

            foreach ($user["userRoles"] as $key => $value) {
                $user["userRoles"][$key]["selected"] = $value["id"] == $user["userLevel"];
            }

            $this->html .= $this->page->buildElement("userForm", $user);
        }

        public function method_delete() {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No user ID specified"));
            }

            $user = $this->getUser($this->methodData->id);

            if (!isset($user["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This user does not exist"));
            }

            if (isset($this->methodData->commit)) {
                
                $delete = $this->db->delete("DELETE FROM users WHERE U_id = :id;", array(
                    ":id" => $this->methodData->id
                ));

                $delete = $this->db->delete("DELETE FROM userStats WHERE US_id = :id;", array(
                    ":id" => $this->methodData->id
                ));

                header("Location: ?page=admin&module=users");

            }


            $this->html .= $this->page->buildElement("userDelete", $user);
        }

        public function method_view () {
            
            if (!isset($this->methodData->user)) {
                $this->methodData->user = "";
            }

            $this->html .= $this->page->buildElement("userList", array(
                "submit" => true,
                "users" => $this->getUser($this->methodData->user, true)
            ));

        }

    }