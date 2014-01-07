<?php

    class login extends module {
        
        public $allowedMethods = array(
            'logout'=>array('type'=>'get'), 
            'username'=>array('type'=>'post'), 
            'password'=>array('type'=>'post')
        );
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('loginForm', array());
            
        }
        
        public function method_logout() {
            
            $this->html .= $this->page->buildElement('info', array('You have been logged out!'));
        
        }
        
        public function method_login() {
                
            $user = @new user(NULL, $this->methodData->username);
            
            if (isset($user->info->U_id)) {
                if ($user->info->U_password == $user->encrypt($this->methodData->password)) {
                    
                    $_SESSION['userID'] = $user->info->U_id;
                    
                    header("Location:?page=loggedin");
                    
                } else {
                    
                    $this->html .= $this->page->buildElement('error', array('You have entered a wrong password!'));;
                    
                }
            } else {
            
                $this->html .= $this->page->buildElement('error', array('Invalid username!'));	
            
            }
            
        }
        
    }

?>