<?php

    class adminModule {

        private function getNews($newsID = "all") {
            if ($newsID == "all") {
                $add = "";
            } else {
                $add = " WHERE GN_id = :id";
            }
            
            $news = $this->db->prepare("
                SELECT
                    GN_id as 'id',  
                    U_name as 'gnauthor',  
                    GN_title as 'gntitle',  
                    GN_text as 'gntext',  
                    from_unixtime(GN_date) as 'gndate'
                FROM gameNews
                LEFT OUTER JOIN users ON (U_id = GN_author)
                " . $add . "
                ORDER BY GN_id"
            );

            if ($newsID == "all") {
                $news->execute();
                return $news->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $news->bindParam(":id", $newsID);
                $news->execute();
                return $news->fetch(PDO::FETCH_ASSOC);
            }
        }

        private function validatenews($news) {
            $errors = array();
        
            if (strlen($news["gntitle"]) < 2) {
                $errors[] = L::module_loggedin_admin_news_errors_title_too_short;
            } 
            if (strlen($news["gntext"]) < 10) {
                $errors[] = L::module_loggedin_admin_news_errors_text_too_short;
            } 

            return $errors;
            
        }

        public function method_new () {

            $news = array();

            if (isset($this->methodData->submit)) {
                $news = (array) $this->methodData;
                $errors = $this->validatenews($news);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $insert = $this->db->prepare("
                        INSERT INTO gameNews (GN_author, GN_title, GN_text, GN_date)  VALUES (:gnauthor, :gntitle, :gntext, UNIX_TIMESTAMP());
                    ");
                    $insert->bindParam(":gnauthor", $this->user->id);
                    $insert->bindParam(":gntitle", $this->methodData->gntitle);
                    $insert->bindParam(":gntext", $this->methodData->gntext);
                    $insert->execute();

                    $this->html .= $this->page->buildElement("success", array("text" => L::module_loggedin_admin_news_success_post_created));

                }

            }

            $news["editType"] = "new";
            $this->html .= $this->page->buildElement("loggedinNewForm", $news);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => L::module_loggedin_admin_news_errors_empty_news_id));
            }

            $news = $this->getNews($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $news = (array) $this->methodData;
                $errors = $this->validatenews($news);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->prepare("
                        UPDATE gameNews SET GN_title = :gntitle, GN_text = :gntext WHERE GN_id = :id
                    ");
                    $update->bindParam(":gntitle", $this->methodData->gntitle);
                    $update->bindParam(":gntext", $this->methodData->gntext);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    $this->html .= $this->page->buildElement("success", array("text" => L::module_loggedin_admin_news_success_post_updated));

                }

            }

            $news["editType"] = "edit";
            $this->html .= $this->page->buildElement("loggedinNewForm", $news);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => L::module_loggedin_admin_news_errors_empty_news_id));
            }

            $news = $this->getNews($this->methodData->id);

            if (!isset($news["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => L::module_loggedin_admin_news_errors_post_not_found));
            }

            if (isset($this->methodData->commit)) {
                $delete = $this->db->prepare("
                    DELETE FROM gameNews WHERE GN_id = :id;
                ");
                $delete->bindParam(":id", $this->methodData->id);
                $delete->execute();

                header("Location: ?page=admin&module=loggedin");

            }


            $this->html .= $this->page->buildElement("loggedinDelete", $news);
        }

        public function method_view () {

            $this->html .= $this->page->buildElement("loggedinList", array(
                "loggedin" => $this->getNews()
            ));

        }

    }