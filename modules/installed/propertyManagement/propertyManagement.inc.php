<?php

    class propertyManagement extends module {
        
        public $allowedMethods = array(
            "module" => array( "type" => "GET" ),
            "transfer" => array( "type" => "POST" ),
        	"cost" => array( "type" => "POST" )
        );
		
		public $pageName = '';
        
        public function constructModule() {
            if (!isset($this->methodData->module)) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "What property do you want to manage"
                ));
            }

            $this->property = new Property($this->user, $this->methodData->module);

            $info = $this->property->getOwnership();

            if ($info["user"]["id"] != $this->user->id) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "You do not own this property"
                ));
            }

            $this->html .= $this->page->buildElement("propertyManagement", $info);
        }

        public function method_reset() {
            $this->property = new Property($this->user, $this->methodData->module);
            $info = $this->property->getOwnership();
            if ($info["user"]["id"] == $this->user->id) {
                $this->property->updateProfit(0 - $info["_profit"]);

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "Profit / Loss reset to $0"
                ));
            }
        }

        public function method_cost() {

            if (!isset($this->methodData->cost)) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "Please provide a new Cost or Max Bet"
                ));
            }
            
            if ($this->methodData->cost < 100) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "Cost or Max Bet must be at least $100"
                ));
            }

            $this->property = new Property($this->user, $this->methodData->module);
            $info = $this->property->getOwnership();
            if ($info["user"]["id"] == $this->user->id) {


                $cost = $this->methodData->cost;
                $this->property->setCost($cost);
                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "Cost or Max Bet set to " . $this->money($cost)
                ));
            }
        }

        public function method_transfer() {

            if (!isset($this->methodData->transfer)) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "Please provide a user to transfer this property to"
                ));
            }

            $user = new User(null, $this->methodData->transfer);

            if (!isset($user->info->US_id)) {
                return $this->html .= $this->page->buildElement("error", array(
                    "text" => "This user does not exist"
                ));
            }

            $this->property = new Property($this->user, $this->methodData->module);
            $info = $this->property->getOwnership();

            if ($info["user"]["id"] == $this->user->id) {
                $this->property->transfer($user->info->US_id);
                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "Property transfered to " . $user->info->U_name
                ));
            }
        }
        
    }

?>