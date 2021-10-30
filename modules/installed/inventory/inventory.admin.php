<?php

    class adminModule {

        private function getItem($itemID = "all") {
            if ($itemID == "all") {
                $add = "";
            } else {
                $add = " WHERE I_id = :id";
            }
            
            $item = $this->db->prepare("
                SELECT
                    I_id as 'id',  
                    I_name as 'name',  
                    I_type as 'type'
                FROM items" . $add
            );

            if ($itemID == "all") {
                $item->execute();
                return $item->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $item->bindParam(":id", $itemID);
                $item->execute();
                $data = $item->fetch(PDO::FETCH_ASSOC);
                
                $effects = $this->db->selectAll("
                    SELECT IE_effect as 'id', IE_value as 'value', IE_desc as 'desc'
                    FROM itemEffects WHERE IE_item = :item;
                ", array(
                    ":item" => $itemID
                ));
                $data["effect"] = $effects;

                $metaData = $this->db->selectAll("
                    SELECT * FROM itemMeta WHERE IM_item = :item;
                ", array(
                    ":item" => $itemID
                ));

                $meta = array();
                foreach ($metaData as $key => $value) {
                    $meta[$value["IM_meta"]] = $value["IM_value"];
                }

                $data["meta"] = $meta;
                return $data;
            }
        }

        private function validateItem($item) {
            $errors = array();

            if (strlen($item["name"]) < 3) {
                $errors[] = "Item name is to short, this must be atleast 5 characters";
            }
            if (!intval($item["type"])) {
                $errors[] = "No type specified";
            }

            return $errors;
            
        }

        public function saveMeta ($data, $item) {
            $this->db->delete("DELETE FROM itemMeta WHERE IM_item = :item", array(
                ":item" => $item
            ));
            foreach ($data as $meta => $value) {
                $this->db->insert("INSERT INTO itemMeta (IM_item, IM_meta, IM_value) VALUES (:item, :meta, :value)", array(
                    ":item" => $item,
                    ":meta" => trim($meta),
                    ":value" => $value
                ));
            }
            return array();
        }

        public function saveEffects ($item) {
            $this->db->delete("DELETE FROM itemEffects WHERE IE_item = :item", array(
                ":item" => $item
            ));

            if (!isset($this->methodData->effect)) return;

            $data = $this->methodData->effect;

            foreach ($data as $effect) {
                $this->db->insert("INSERT INTO itemEffects (IE_item, IE_effect, IE_value, IE_desc) VALUES (:item, :meta, :value, :desc)", array(
                    ":item" => $item,
                    ":meta" => $effect["id"],
                    ":value" => $effect["value"],
                    ":desc" => $effect["desc"]
                ));
            }
            return array();
        }

        public function getInputs ($data) {

            $hook = new Hook("itemMetaData");
            $inputs = $hook->run();

            $html = "";

            if (isset($data["meta"])) {
                foreach ($data["meta"] as $key => $value) {
                    $data["meta"][trim($key)] = $value;
                }
            }

            foreach ($inputs as $input) {

                if (isset($data["meta"]) && isset($data["meta"][$input["id"]])) {
                    $input["value"] = $data["meta"][$input["id"]];
                }

                switch ($input["type"]) {
                    case "text":
                        $html .= $this->page->buildElement("formText", $input);
                    break;
                    case "textarea":
                        $data["rows"] = $input["rows"];
                        $html .= $this->page->buildElement("formTextarea", $input);
                    break;
                    case "number":
                        $html .= $this->page->buildElement("formNumber", $input);
                    break;
                    case "select": 
                        $data["options"] = $input["options"];
                        $html .= $this->page->buildElement("formSelect", $input);
                    break;
                }
            }
            return $html;
        }

        public function method_new () {

            $item = array();

            if (isset($this->methodData->submit)) {
                $item = (array) $this->methodData;
                $errors = $this->validateItem($item);
                
                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array(
                            "text" => $error
                        ));
                    }
                } else {

                    $insertID = $this->db->insert("
                        INSERT INTO items (I_name, I_type)  VALUES (:name, :type);
                    ", array(
                        ":name" => $this->methodData->name,
                        ":type" => $this->methodData->type
                    ));

                    $this->saveMeta($this->methodData->meta, $insertID);
                    $this->saveEffects($insertID);

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This item has been created"
                    ));

                }

            }
            
            $items = new Items();
            $item["inputs"] = $this->getInputs($item);
            $item["effectTypes"] = $items->getEffects();
            $item["editType"] = "new";
            $item["itemTypes"] = $items->types;
            $this->html .= $this->page->buildElement("itemForm", $item);
        }

        public function method_edit () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array(
                    "text" => "No item ID specified"
                ));
            }

            $item = $this->getItem($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $item = (array) $this->methodData;
                $errors = $this->validateItem($item);

                if (count($errors)) {
                    foreach ($errors as $error) {
                        $this->html .= $this->page->buildElement("error", array("text" => $error));
                    }
                } else {
                    $update = $this->db->update("
                        UPDATE items SET I_name = :name, I_type = :type WHERE I_id = :id
                    ", array(
                        ":name" => $this->methodData->name,
                        ":type" => $this->methodData->type,
                        ":id" => $this->methodData->id
                    ));

                    $this->saveMeta($this->methodData->meta, $this->methodData->id);
                    $this->saveEffects($this->methodData->id);

                    $this->html .= $this->page->buildElement("success", array(
                        "text" => "This item has been updated"
                    ));

                }

            }

            $items = new Items();
            $item["editType"] = "edit";
            $item["itemTypes"] = $items->types;
            $item["inputs"] = $this->getInputs($item);
            $item["effectTypes"] = $items->getEffects();
            $this->html .= $this->page->buildElement("itemForm", $item);
        }

        public function method_delete () {

            if (!isset($this->methodData->id)) {
                return $this->html = $this->page->buildElement("error", array("text" => "No item ID specified"));
            }

            $item = $this->getItem($this->methodData->id);

            if (!isset($item["id"])) {
                return $this->html = $this->page->buildElement("error", array("text" => "This item does not exist"));
            }

            if (isset($this->methodData->commit)) {
                
                $this->db->delete("
                    DELETE FROM items WHERE I_id = :id;
                ", array(
                    ":id" => $this->methodData->id
                ));

                $this->db->delete("
                    DELETE FROM userInventory WHERE UI_item = :id;
                ", array(
                    ":id" => $this->methodData->id
                ));

                $this->db->delete("
                    DELETE FROM itemMeta WHERE IM_item = :id;
                ", array(
                    ":id" => $this->methodData->id
                ));

                header("Location: ?page=admin&module=inventory");

            }


            $this->html .= $this->page->buildElement("itemDelete", $item);
        }

        public function method_view () {
            
            $this->html .= $this->page->buildElement("itemList", array(
                "items" => $this->getItem()
            ));

        }

    }