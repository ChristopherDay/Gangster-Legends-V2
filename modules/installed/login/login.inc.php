<?php

    class login extends module {
        
        public $allowedMethods = array(
            'logout'=>array('type'=>'get'), 
            'username'=>array('type'=>'post'), 
            'password'=>array('type'=>'post')
        );
        
        public function constructModule() {

            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("loginSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("loginPostfix"));

            $usersOnline = $this->db->prepare("
                SELECT COUNT(*) as 'count' FROM users
            ");
            $usersOnline->execute();
            $users = $this->db->prepare("
                SELECT COUNT(*) as 'count' FROM userTimers WHERE UT_desc = 'laston' AND UT_time > ".(time()-900)."
            ");
            $users->execute();

            $this->page->addToTemplate("usersOnline", number_format($usersOnline->fetch(PDO::FETCH_ASSOC)["count"]));
            $this->page->addToTemplate("users", number_format($users->fetch(PDO::FETCH_ASSOC)["count"]));
            
            $this->html .= $this->page->buildElement('loginForm', array("text" => (isset($this->loginError)?$this->loginError:'')));
            
        }
        
        public function method_logout() {
            
            $this->loginError = $this->page->buildElement('info', array("text" => 'You have been logged out!'));
        
        }
        
        public function method_login() {
                
            $user = @new user(NULL, $this->methodData->username);
            
            if (isset($user->info->U_id)) {
                if ($user->info->U_password == $user->encrypt($user->info->U_id . $this->methodData->password)) {
                    $_SESSION['userID'] = $user->info->U_id;
                    header("Location:?page=" . $this->page->landingPage);
                } else {
                    $this->loginError = $this->page->buildElement('error', array("text" => 'You have entered a wrong password!'));;
                }
            } else {
                $this->loginError = $this->page->buildElement('error', array("text" => 'Invalid username!'));	
            }
            
        }
        
    }

?>
