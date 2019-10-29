<?php
    class adminModule {

        public function method_view() {

            $users = $this->db->prepare("
                SELECT COUNT(*) as 'users' FROM users
            ");

            $users->execute();


            $this->html = $this->page->buildElement("dashboard", array(
                "users" => $users->fetch(PDO::FETCH_ASSOC)["users"], 
                "modules" => count($this->page->modules)
            ));
        }

    }
?>