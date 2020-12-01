<?php

    class dead extends module {
        
        public $allowedMethods = array(
            "username" => array( "type" => "post" ),
            "password" => array( "type" => "post" )
        );
        
        public $pageName = '';

        public function method_new() {

            $user = new User(false, $this->methodData->username);

            if(preg_match("/^[a-zA-Z0-9]+$/", $this->methodData->username) != 1) {
                return $this->alerts[] = $this->page->buildElement('error', array(
                    "text" => 'Please enter a valid username'
                )); 
            }

            if (isset($user->info->U_id)) {
                return $this->alerts[] = $this->page->buildElement("error", array(
                    "text" => "Your chosen username is already in use!"
                ));
            }

            if ($this->user->encrypt($this->user->id . $this->methodData->password) != $this->user->info->U_password) {
                return $this->alerts[] = $this->page->buildElement("error", array(
                    "text" => "Incorrect password!"
                ));
            } 

            $addUser = $this->db->prepare("
                INSERT INTO users (U_name, U_email, U_userLevel, U_status) 
                VALUES (:username, :email, :userLevel, 1)
            ");
            $addUser->bindParam(':username', $this->methodData->username);
            $addUser->bindParam(':email', $this->user->info->U_email);
            $addUser->bindParam(':userLevel', $this->user->info->U_userLevel);
            $addUser->execute();

            $id = $this->db->lastInsertId();

            $encryptedPassword = $this->user->encrypt($id . $this->methodData->password);

            $addUserPassword = $this->db->prepare("
                UPDATE users SET U_password = :password WHERE U_id = :id
            ");
            $addUserPassword->bindParam(':id', $id);
            $addUserPassword->bindParam(':password', $encryptedPassword);
            $addUserPassword->execute();

            $this->db->query("INSERT INTO userStats (US_id) VALUES (" . $id . ")");

            $u = new User($id);

            $u->updateTimer("signup", time());

            $_SESSION['userID'] = $id;

            $hook = new Hook("newUserRevive");
            $hook->run(
                array(
                    "old" => $this->user->id,
                    "new" => $id
                )
            );

            header("Location:?");

        }
        
        public function constructModule() {
            
            $killer = new User($this->user->info->US_shotBy);

            if ($this->user->info->U_status != 0) {
                return $this->error("You're not dead ... yet!");
            }

            $this->html .= $this->page->buildElement("newAccount", array(
                "user" => $killer->user
            ));
        }
        
    }

