<?php

    class login extends module {
        
        public $allowedMethods = array(
            'logout'=>array('type'=>'get'), 
            'email'=>array('type'=>'post'), 
            'password'=>array('type'=>'post')
        );
        
        public function constructModule() {

            if(isset($this->user->loggedin)){
                $this->page->redirectTo($this->page->landingPage);
            }
            
            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("loginSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("loginPostfix"));

            $this->html .= $this->page->buildElement('loginForm');

            
        }
        
        public function method_logout() {
            $this->error('You have been logged out!');
        }
        
        public function method_login() {

            //if (!$this->checkCSFRToken()) return;
            
            $round = new Round();

            $userExists = @$this->db->select("
                SELECT * 
                FROM users 
                WHERE U_email = :email 
                ORDER BY U_id DESC LIMIT 0, 1
            ", array(
                ":email" => $this->methodData->email
            ));

            if (!isset($userExists["U_id"])) {
                return $this->error('You have entered a wrong email/password!');    
            }

            $user = new User($userExists["U_id"]);

            if ($user->info->U_password != $user->encrypt($user->info->U_id . $this->methodData->password)) {
                return $this->error('You have entered a wrong email/password!');
            }

            if (!$round->currentRound || $round->currentRound["round"] != $userExists["U_round"]) {
                $u = new User($userExists["U_id"]);

                if (!$u->hasAdminAccessTo("rounds")) {
                    return $this->error("The round is over, please register again!");
                }

            } 

            $_SESSION['userID'] = $user->info->U_id;

            $actionHook = new hook("userAction");
            $action = array(
                "user" => $user->info->U_id, 
                "module" => "login", 
                "id" => $user->info->U_id, 
                "success" => true, 
                "reward" => 0
            );
            $actionHook->run($action);

            header("Location:?page=" . $this->page->landingPage);

            
        }
        
    }

