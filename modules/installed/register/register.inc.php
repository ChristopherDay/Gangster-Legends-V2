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

            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("registerSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("registerPostfix"));

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
            
            $this->regError = '';
            
            $user = @new user();
            $settings = new settings();
	
            if (!filter_var($this->methodData->email, FILTER_VALIDATE_EMAIL)) {
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
                    $_SESSION["userID"] = $makeUser;
                    header("Location:?");
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