<?php

    class register extends module {
        
        public $regError = "";

        public $allowedMethods = array(
            'password'=>array('type'=>'post'),
            'cpassword'=>array('type'=>'post'),
            'username'=>array('type'=>'post'),
            'email'=>array('type'=>'post')
        );
        
        public function constructModule() {
			
			global $regError;
            
            $this->html .= $this->page->buildElement('registerForm', array(
                "text" => $this->regError
            ));
            
        }
        
        public function method_register() {
			
			$this->regError = '';
            
            $user = @new user();
	
            if (!empty($this->methodData->password) && ($this->methodData->password == $this->methodData->cpassword)) {
                
                $makeUser = $user->makeUser(
                    $this->methodData->username, 
                    $this->methodData->email, 
                    $this->methodData->password
                );
                
                if ($makeUser != 'success') {
                    $this->regError = $this->page->buildElement('error', array(
                        "text" => $makeUser
                    ));
                } else {
                    $this->regError =  $this->page->buildElement('success', array(
                        "text" => 'You have registered successfuly, you can now log in!'
                    ));
                }
                
            } else if (isset($this->methodData->password)) {
                
                $this->regError =  $this->page->buildElement('error', array(
                    "text" => 'Your passwords do not match!'
                ));	
            
            }
            
        }
        
    }

?>