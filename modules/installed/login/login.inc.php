<?php

    class login extends module {
        
        public $allowedMethods = array(
            'logout'=>array('type'=>'get'), 
            'username'=>array('type'=>'post'), 
            'password'=>array('type'=>'post')
        );
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('loginForm', array("text" => (isset($this->loginError)?$this->loginError:'')));
            
        }
        
        public function method_logout() {
            
            $this->loginError = $this->page->buildElement('info', array("text" => 'You have been logged out!'));
        
        }
        
        public function method_login() {
                
            $user = @new user(NULL, $this->methodData->username);
            
            if (isset($user->info->U_id)) {
                if ($user->info->U_password == $user->encrypt($this->methodData->password)) {
                    
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
