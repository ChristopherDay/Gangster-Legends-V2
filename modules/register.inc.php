<?php

    class register extends module {
        
        public function constructModule() {
            
            $user = @new user();
	
            if (
                !empty($_POST['password']) && 
                ($_POST['password'] == $_POST['cpassword'])
            ) {
                
                $makeUser = $user->makeUser($_POST['username'], $_POST['email'], $_POST['password']);
                
                if ($makeUser != 'success') {
                    $this->html .= $this->page->buildElement('error', array($makeUser));
                } else {
                    $this->html .= $this->page->buildElement('success', array('You have registered successfuly, you can now log in!'));
                }
                
            } else if (isset($_POST['password'])) {
                
                $this->html .= $this->page->buildElement('error', array('Your passwords do not match!'));	
            
            }
            
        }
        
    }

?>