<?php

	class adminModule {

		public function method_view () {
			
			$all = $this->db->prepare("SELECT * FROM crimes");

			$all->execute();

			$this->html .= $this->page->buildElement("crimeList", array(
				"crimes" => $all->fetchAll(PDO::FETCH_ASSOC)
			));

		}

	}