<?php

    class register extends module {
        
        public $regError = "";

        public $allowedMethods = array(
            'ref'=>array('type'=>'get'),
            'password'=>array('type'=>'post'),
            'cpassword'=>array('type'=>'post'),
            'username'=>array('type'=>'post'),
            'email'=>array('type'=>'post')
        );
        
        public function constructModule() {
            
            global $regError;

            if(isset($this->user->loggedin)){
                $this->page->redirectTo($this->page->landingPage);
            }

            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("registerSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("registerPostfix"));
            
            $ref = false;
            if (isset($this->methodData->ref)) {
                $ref = $this->methodData->ref;
            }
            
            $this->html .= $this->page->buildElement('registerForm', array(
                "text" => $this->regError, 
                "ref" => $ref
            ));
            
        }
        
        public function method_register() {

            //if (!$this->checkCSFRToken()) return;
            
            $this->regError = '';
            
            $user = @new user();
            $round = new Round();
            $settings = new settings();
            
            if(preg_match("/^[a-zA-Z0-9]+$/", $this->methodData->username) != 1) {
                $this->regError =  $this->page->buildElement('error', array(
                    "text" => 'Please enter a valid username'
                )); 
            } else if (!filter_var($this->methodData->email, FILTER_VALIDATE_EMAIL)) {
                $this->regError =  $this->page->buildElement('error', array(
                    "text" => 'Please enter a valid email address'
                )); 
            } else if (strlen($this->methodData->username) < 3) {
                $this->regError =  $this->page->buildElement('error', array(
                    "text" => 'Your username should be atleast 3 characters long'
                )); 
            } else if (
                !empty($this->methodData->password) && ($this->methodData->password == $this->methodData->cpassword)
            ) {

                $makeUser = $user->makeUser(
                    $this->methodData->username, 
                    $this->methodData->email, 
                    $this->methodData->password
                );
                
                if (!ctype_digit($makeUser)) {
                    $this->regError = $this->page->buildElement('error', array(
                        "text" => $makeUser
                    ));
                } else {

                    $actionHook = new hook("userAction");
                    $action = array(
                        "user" => $makeUser, 
                        "module" => "register", 
                        "id" => $makeUser, 
                        "success" => true, 
                        "reward" => 0
                    );
                    $actionHook->run($action);

                    $_SESSION["userID"] = $makeUser;

                    if ($round->currentRound) {
                        header("Location:?");
                        $this->regError =  $this->page->buildElement('success', array(
                            "text" => 'You have registered successfuly, you can now log in!'
                        ));
                    } else {
                        $this->error("You have pre-registered for the next round!", "success");
                    }
                }
                
            } else if (isset($this->methodData->password)) {
                $this->regError =  $this->page->buildElement('error', array(
                    "text" => 'Your passwords do not match!'
                ));    
            }
            
        }
        
    }

