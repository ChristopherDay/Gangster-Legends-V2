<?php

    class adminModule {

        private function getForums($forumID = "all") {
            if ($forumID == "all") {
                $add = "";
            } else {
                $add = " WHERE F_id = :id";
            }
            
            $forums = $this->db->prepare("
                SELECT
                    F_id as 'id',  
                    F_sort as 'sort',  
                    F_name as 'name'
                FROM forums" . $add . "
                ORDER BY F_name"
            );

            if ($forumID == "all") {
                $forums->execute();
                return $forums->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $forums->bindParam(":id", $forumID);
                $forums->execute();
                return $forums->fetch(PDO::FETCH_ASSOC);
            }
        }

        private function validateForum($forum) {
            $errors = array();

            if (strlen($forum["name"]) < 6) {
                $errors[] = "Forum name is to short, this must be at least 5 characters";
            }

            if (!ctype_digit($forum["sort"])) {
                $errors[] = "Forum order needs to be a number!";
            }

            return $errors;
            
        }

        public function method_newForum() {

            $forums = array();

            if (isset($this->methodData->submit)) {
                $forums = (array) $this->methodData;
                $errors = $this->validateForum($forums);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $insert = $this->db->prepare("
                        INSERT INTO forums (F_name, F_sort)  VALUES (:name, :sort);
                    ");
                    $insert->bindParam(":name", $this->methodData->name);
                    $insert->bindParam(":sort", $this->methodData->sort);    
                    $insert->execute();

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This forum has been created"
                    ));

                }

            }

            $forums["editType"] = "new";
            $this->html .= $this->page->buildElement("forumForm", $forums);
        }

        public function method_editForum() {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No forum ID specified"
                ));
            }

            $forums = $this->getForums($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $forums = (array) $this->methodData;
                $errors = $this->validateForum($forums);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array(
                            "text" => $error
                        ));
                    }
                } else {
                    $update = $this->db->prepare("
                        UPDATE forums SET F_name = :name, F_sort = :sort WHERE F_id = :id
                    ");
                    $update->bindParam(":name", $this->methodData->name);
                    $update->bindParam(":sort", $this->methodData->sort);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This forum has been updated"
                    ));

                }

            }

            $forums["editType"] = "edit";
            $this->html .= $this->page->buildElement("forumForm", $forums);
        }

        public function method_deleteForum() {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No forum ID specified"
                ));
            }

            $forum = $this->getForums($this->methodData->id);

            if (!isset($forum["id"])) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "This forum does not exist"
                ));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM forums WHERE F_id = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=forum");

            }


            $this->html .= $this->page->buildElement("forumsDelete", $forum);
        }

        public function method_viewForum() {
            
            $this->html .= $this->page->buildElement("forumsList", array(
                "forums" => $this->getForums()
            ));

        }

    }
