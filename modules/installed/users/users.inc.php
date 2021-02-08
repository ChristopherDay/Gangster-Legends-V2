<?php

    class users extends module {
        
        public $allowedMethods = array(
            "code" => array("type" => "post")
        );
        
        public $pageName = '';
        
        public function constructModule() {

            $code = "";

            if (isset($this->methodData->code)) {
                $code = $this->methodData->code;
                $settings = new settings();
                $correctCode = $this->user->activationCode($this->user->info->U_id, $this->user->info->U_name);
                if ($code == $correctCode) {
                    $this->user->set("U_status", 1);
                    header("Location: ?page=" . $settings->loadSetting("landingPage"));
                } else {
                    $this->html .= $this->page->buildElement("error", array(
                        "text" => "Invalid code"
                    ));
                }
            }

            $this->html .= $this->page->buildElement("validateAccount", array(
                "code" => $code
            ));
        }

        public function method_resend() {
            $this->user->sendActivationCode(
                $this->user->info->U_email, 
                $this->user->info->U_id, 
                $this->user->info->U_name
            );
            $this->html .= $this->page->buildElement("success", array(
                "text" => "Activation code resent"
            ));
        }

    }
