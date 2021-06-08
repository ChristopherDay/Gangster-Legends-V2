<?php

    class membership extends module {
            
        public $allowedMethods = array(
            "id" => array("type" => "GET")
        );
        
        public function constructModule() {

            if (!$this->user->checkTimer('membership')) {
                $time = $this->user->getTimer('membership');
                $data = array(
                    "timer" => "membership",
                    "type" => "info",
                    "text"=>'Your membership expires in!',
                    "time" => $this->user->getTimer("membership")
                );
                $this->html .= $this->page->buildElement('timer', $data);
            }

        	$benefits = new Hook("membershipBenefit");

            $packages = $this->db->selectAll("
                SELECT
                    PM_id as 'id', 
                    PM_desc as 'desc', 
                    PM_seconds as 'seconds', 
                    PM_cost as 'cost'
                FROM premiumMembership
                ORDER BY PM_seconds ASC
            ");
        	
        	$this->html .= $this->page->buildElement("memberships", array(
        		"benefits" => $benefits->run(), 
                "packages" => $packages
        	));
        }

        public function method_buy () {

            $membership = $this->db->select("
                SELECT * FROM premiumMembership WHERE PM_id = :id
            ", array(
                ":id" => $this->methodData->id
            ));

            if (!$membership) {
                return $this->error("This membership does not exist!");
            }

            if ($this->user->info->US_points < $membership["PM_cost"]) {
                return $this->error("You can't afford this!");
            }

            $time = $this->user->getTimer("membership");

            if (time() > $time) $time = time();

            $newTime = $time + $membership["PM_seconds"];

            $this->user->updateTimer("membership", $newTime);

            $this->error("You bought a new membership!", "success");

            $this->user->set("US_points", $this->user->info->US_points - $membership["PM_cost"]);

        }

    }

