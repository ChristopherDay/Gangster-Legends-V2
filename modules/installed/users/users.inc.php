<?php

    class users extends module {
        
        public $allowedMethods = array(
        	"code" => array("type" => "post")
        );
		
		public $pageName = '';
        
        public function constructModule() {
        	$code = @$this->methodData->code;

        	if ($code) {
        		$settings = new settings();
        		$correctCode = $this->user->activationCode($this->user->info->U_id, $this->user->info->U_name);
        		if ($code == $correctCode) {
        			$u = $this->db->prepare("
        				UPDATE users SET U_status = 1 WHERE U_id = :id;
        			");
        			$u->bindParam(":id", $this->user->id);
        			$u->execute();
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

    }

   ?>