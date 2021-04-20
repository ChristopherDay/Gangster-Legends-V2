<?php

    class inventory extends module {
            
        public $allowedMethods = array();

        public function constructModule () {

        	$hook = new Hook("equipSlot");
        	$slots = $this->page->sortArray($hook->run());

        	$equipSlots = array();
        	foreach ($slots as $slot) {
        		$equipSlots[] = array(
        			"name" => $slot["name"],
        			"item" => $slot["getItem"]($this->user)
        		);
        	}

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

    }