<?php

    class login extends module {
        
        public function constructModule() {
            
            if (isset($_GET['logout'])) {
            
                $this->html .= $this->page->buildElement('info', array('You have been logged out!'));
            
            }
            
            if (isset($_POST['username'])) {
                
                $user = @new user(NULL, $_POST['username']);
                
                if (isset($user->info->U_id)) {
                    if ($user->info->U_password == $user->encrypt($_POST['password'])) {
                        
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
        
    }

?>