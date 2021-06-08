<?php

    class blackmarket extends module {
        
        public $allowedMethods = array(
            "item" => array( "type" => "GET" )
        );
        
        public $pageName = '';

        public function constructModule() {
            $item = new Items();
            $types = $item->types;

            foreach ($types as $typeKey => $type) {

                $type["items"] = $this->db->selectAll("SELECT I_id FROM items WHERE I_type = :type", array(
                    ":type" => $type["id"]
                ));

                foreach ($type["items"] as $itemKey => $itemID) {
                    $type["items"][$itemKey] = $item->getItem($itemID["I_id"]);
                }

                $types[$typeKey] = $type;
            }


                
            $hook = new Hook("alterModuleData");
            $hookData = array(
                "module" => "blackMarket.list",
                "user" => $this->user,
                "data" => $types
            );
            $types = $hook->run($hookData, 1)["data"];

            $this->html .= $this->page->buildElement("blackMarket", array(
                "types" => $types
            ));
        }

        public function method_buy() {

            if (!$this->checkCSFRToken()) return;

            $items = new Items();

            $item = $items->getItem($this->methodData->item);

            $data = array(
                "user" => $this->user, 
                "item" => $item
            );

            $buyItemCheck = new Hook("buyItem");
            $errors = $buyItemCheck->run($data);

            if ($errors) {
                foreach ($errors as $error) {
                    if (is_string($error)) {
                        return $this->error($error);
                    }
                }
            }

            $hook = new Hook("alterModuleData");
            $hookData = array(
                "module" => "blackmarket.item",
                "user" => $this->user,
                "data" => $item
            );
            $item = $hook->run($hookData, 1)["data"];

            if (!$item["id"]) {
                return $this->error("This item does not exist");
            } 

            if ($item["cost"] > $this->user->info->US_money) {
                return $this->error("You dont have enough money to buy a " . $item["name"]);
            } 

            $this->error("You bought a " . $item["name"], "success");

            $this->user->subtract("US_money", $item["cost"]);
            $this->user->addItem($item["id"]);
            
            $actionHook = new hook("userAction");
            $action = array(
                "user" => $this->user->id, 
                "module" => "blackmarket", 
                "id" => $item["id"], 
                "success" => true, 
                "reward" => 0
            );
            $actionHook->run($action);

        }
    }

