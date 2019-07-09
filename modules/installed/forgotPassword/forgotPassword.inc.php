<?php

    class forgotPassword extends module {
        
        public $regError = "";

        public $allowedMethods = array(
            'id'=>array('type'=>'get'),
            'auth'=>array('type'=>'get'),
            'email'=>array('type'=>'post'),
            'password'=>array('type'=>'post'),
            'cpassword'=>array('type'=>'post')
        );
        
        public function constructModule() {
            
            global $regError;

            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("loginSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("loginPostfix"));
            
            $this->html .= $this->page->buildElement('resetPasswordEmail');
            
        }
        
        public function method_reset() {

            $this->error("A password reset email has been sent to you!", "success");

            $user = $this->db->select("SELECT * FROM users INNER JOIN userStats ON (US_id = U_id) WHERE U_email = :email ORDER BY U_id DESC", array(
                ":email" => $this->methodData->email
            ));

            if (isset($user["U_id"])) {

                $url = $_SERVER["HTTP_ORIGIN"] . $_SERVER["SCRIPT_NAME"] . "?page=forgotPassword&action=resetPassword&auth=" . $user["U_password"] . "&id=" . $user["U_id"] . "";

                $body = "To reset your password please follow the link below: \r\n " . $url;
                mail($user["U_email"], "Password Reset", $body);

            }

        }

        public function method_resetPassword() {
            

            if (!isset($this->methodData->id)) {
                return $this->error("Invalid reset link");
            }

            $user = new User($this->methodData->id);

            if (!isset($user->info->U_id)) {
                return $this->error("Invalid reset link");
            }

            if (!isset($this->methodData->auth)) {
                return $this->error("Invalid reset link");
            }

            if ($this->methodData->auth != $user->info->U_password) {
                return $this->error("Invalid reset link");
            }

            if (isset($this->methodData->password) && isset($this->methodData->cpassword)) {

                if ($this->methodData->password != $this->methodData->cpassword) {
                    $this->error("Passwords do not match");
                } else {
                    $new = $user->encrypt($user->info->U_id . $this->methodData->password);
                    $user->set("U_password", $new);
                    $this->error("Your password has been reset, you can now login!", "success");
                }

            }

            $this->construct = false;
            $settings = new settings();
            $this->page->addToTemplate("loginSuffix", $settings->loadSetting("loginSuffix"));
            $this->page->addToTemplate("loginPostfix", $settings->loadSetting("loginPostfix"));

            $this->html .= $this->page->buildElement('resetPassword', array(
                "id" => $this->methodData->id,
                "auth" => $this->methodData->auth
            ));
            
        }
        
    }

?>