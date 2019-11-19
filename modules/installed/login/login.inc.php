<?php

    class login extends module {
        
        public $allowedMethods = array(
            'logout'=>array('type'=>'get'), 
            'email'=>array('type'=>'post'), 
            'password'=>array('type'=>'post')
        );
        
        public function constructModule() {

            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("loginSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("loginPostfix"));

            $this->html .= $this->page->buildElement('loginForm');

            
        }
        
        public function method_logout() {
            $this->error('You have been logged out!');
        }
        
        public function method_login() {
                
            $userExists = @$this->db->prepare("
                SELECT * FROM users WHERE U_email = :email ORDER BY U_id DESC LIMIT 0, 1
            ");
            $userExists->bindParam(":email", $this->methodData->email);
            $userExists->execute();
            $userExists = $userExists->fetch(PDO::FETCH_ASSOC);

            
            if (isset($userExists["U_id"])) {
                $user = new User($userExists["U_id"]);
                if ($user->info->U_password == $user->encrypt($user->info->U_id . $this->methodData->password)) {
                    $_SESSION['userID'] = $user->info->U_id;
                    header("Location:?page=" . $this->page->landingPage);
                } else {
                    $this->error('You have entered a wrong email/password!');
                }
            } else {
                $this->error('You have entered a wrong email/password!');    
            }
            
        }
        
    }

?>
