<?php

    class inventory extends module {
            
        public $allowedMethods = array(
            "slot" => array( "type" => "GET" ),
            "item" => array( "type" => "REQUEST" ),
        );

        public function constructModule () {

        	$items = new Items();

            $equipSlots = $items->getSlots($this->user);

            $userInventory = $this->db->selectAll("
                SELECT
                    UI_item as 'id',
                    UI_qty as 'qty', 
                    I_type as 'type', 
                    I_name as 'name'
                FROM userInventory
                INNER JOIN items ON I_id = UI_item
                WHERE UI_user = :id
                AND UI_qty > 0
            ", array(
                ":id" => $this->user->id
            ));

            foreach ($userInventory as $key => $item) {

                $h = new Hook("itemActionLink");
                $links = $h->run($item);
                $userInventory[$key] = $item;

                $itemLinks = array();
                foreach ($links as $link) {
                    $itemLinks = array_merge($itemLinks, $link);
                }

                $item["links"] = $this->page->sortArray($itemLinks);

                $userInventory[$key] = $item;

            }

        	$this->html .= $this->page->buildElement("inventory", array(
        		"equipSlots" => $equipSlots, 
                "inventory" => $userInventory
        	));	

        }

        public function method_information () {
            $this->construct = false;

            $items = new Items();
            $item = $items->getItem($this->methodData->item);

            $hook = new Hook("itemInformation");
            $item["information"] = $this->page->sortArray($hook->run($item));

            $this->html .= $this->page->buildElement("information", $item); 
        }

        public function method_remove () {

            if (!$this->checkCSFRToken()) return;

            $items = new Items();
            $slots = $items->getSlots($this->user);
            foreach ($slots as $slot) {
                if ($slot["name"] == $this->methodData->slot) {
                    $slot["actions"]["remove"]($this->user);
                    $this->error("You have removed your " . $slot["name"], "success");
                }
            }

        }

        public function method_use () {
            
            //if (!$this->checkCSFRToken()) return;

            if (!$this->user->hasItem($this->methodData->item)) {
                return $this->error("You don't have this item!");
            }

            $items = new Items();

            $item = $items->getItem($this->methodData->item);

            $type = $items->getType(false, $item["type"]);
            
            if ($type["type"] == "use") {

                $effects = $items->getEffects();

                foreach ($item["effects"] as $effect) {
                    $effectInfo = $items->findEffect($effects, $effect["effect"]);
                    $effectInfo["use"]($this->user, $effect["value"]);
                }

                $this->user->removeItem($item["id"]);
            } else {
                return $this->error("This item can't be used!");
            }




        }

        public function method_equip () {

            if (!$this->user->hasItem($this->methodData->item)) {
                return $this->error("You don't have this item!");
            }

            $items = new Items();
            $slots = $items->getSlots($this->user);

            if (!$this->checkCSFRToken()) return;

            foreach ($slots as $slot) {


                if ($slot["name"] == $this->methodData->slot) {
                    
                    $item = $items->getItem($this->methodData->item);
                    

                    $data = array(
                        "user" => $this->user, 
                        "item" => $item, 
                        "slot" => $slot
                    );                    

                    $equip = new Hook("validateItem");
                    $errors = $equip->run($data);

                    $valid = true;

                    foreach ($errors as $error) {
                        if (is_string($error)) {
                            $this->error($error);
                            $valid = false;
                        }
                    }


                    if ($valid) {
                        $slot["actions"]["remove"]($this->user);
                        $slot["actions"]["equip"]($this->user, $this->methodData->item);
                        $this->user->removeItem($this->methodData->item);
                        $this->error("You have equiped " . $item["name"], "success");
                    }
                }
            }

        }

    }
























