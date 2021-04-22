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

        public function method_equip () {

            if (!$this->checkCSFRToken()) return;

            if (!$this->user->hasItem($this->methodData->item)) {
                return $this->error("You don't have this item!");
            }

            $items = new Items();
            $slots = $items->getSlots($this->user);

            foreach ($slots as $slot) {

                if ($slot["name"] == $this->methodData->slot) {
                    $slot["actions"]["remove"]($this->user);
                    $slot["actions"]["equip"]($this->user, $this->methodData->item);
                    $this->user->removeItem($this->methodData->item);
                    $this->error("You have equiped a " . $slot["name"], "success");
                }
            }

        }

    }
























