<?php

    class search extends module {
            
        public $allowedMethods = array(
            'user'=>array('type'=>'post')
        );
    
        public $pageName = '';
        
        public function constructModule() {

            $this->construct = false;

            $results = array();

            if (isset($this->methodData->user)) {

                if (strlen($this->methodData->user) < 2) {
                    $this->error("Please enter atleast 2 characters");
                } else {
                    
                    $users = $this->db->selectAll("
                        SELECT * FROM users WHERE U_name LIKE :user
                    ", array(
                        ":user" => "%" . $this->methodData->user . "%"
                    ));

                    foreach ($users as $key => $value) {
                        $user = new User($value["U_id"]);
                        $results[] = array(
                            "user" => $user->user, 
                            "status" => $user->info->U_status == 0?"Dead":"Alive",
                        );
                    }

                }
            }
                
            $this->html .= $this->page->buildElement("userSearch", array(
                "results" => $results
            ));

        }
        
    }

?>