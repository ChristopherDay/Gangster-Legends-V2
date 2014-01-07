<?php

    class register extends module {
        
        public $allowedMethods = array(
            'password'=>array('type'=>'post'),
            'cpassword'=>array('type'=>'post'),
            'username'=>array('type'=>'post'),
            'email'=>array('type'=>'post')
        );
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('registerForm', array());
            
        }
        
        public function method_register() {
            
            $user = @new user();
	
            if (!empty($this->methodData->password) && ($this->methodData->password == $this->methodData->cpassword)) {
                
                $makeUser = $user->makeUser($this->methodData->username, $this->methodData->email, $this->methodData->password);
                
                if ($makeUser != 'success') {
                    $this->html .= $this->page->buildElement('error', array($makeUser));
                } else {
                    $this->html .= $this->page->buildElement('success', array('You have registered successfuly, you can now log in!'));
                }
                
            } else if (isset($this->methodData->password)) {
                
                $this->html .= $this->page->buildElement('error', array('Your passwords do not match!'));	
            
            }
            
        }
        
    }

?>