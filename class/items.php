<?php

	class Items {

		public $settings, $types, $db;

		public function __construct() {
			global $db;
			$this->db = $db;
			$this->settings = new Settings();
			$types = $this->settings->loadSetting("itemTypes");
			$this->types = $types;

			if (!$this->types) $this->types = array();
		}

		public function getItem($id) {

			if (!$id) return false; 

			$item = $this->db->select("
				SELECT 
					I_id as 'id', 
					I_name as 'name', 
					I_damage as 'damage', 
					I_cost as 'cost', 
					I_points as 'points', 
					I_type as 'type', 
					I_rank as 'rank'
				FROM items 
				WHERE I_id = :id
			", array(
				":id" => $id
			));

			return $item;
		}

		public function getType($name, $id = false) {
				
			if ($id) {
				foreach ($this->types as $t) {
					if ($t["id"] == $id) {
						return $t;
					}
				}
				return false;
			}

			foreach ($this->types as $t) {
				if ($t["name"] == $name) {
					return $t;
				}
			}
			return false;
		}

		public function registerNewType($name, $type) {

			$exists = false;

			$id = 1;
			foreach ($this->types as $t) {
				if ($t["name"] == $name) {
					$exists = true;
				}
				$id++;
			}

			if (!$exists) {
				$this->types[] = array(
					"id" => $id, 
					"name" => $name, 
					"type" => $type
				);

				$this->settings->update("itemTypes", $this->types);
			}

		}
	}