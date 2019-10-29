<?php

		global $db;

		if ($db) {
			$tables = $db->selectAll("SHOW TABLES;");
			
			success(2, "Create Database");

			if (!count($tables)) {
			
				$schema = file_get_contents("schema.sql");

				$queries = explode(";", $schema);

				foreach ($queries as $sql) {
					if (trim($sql)) {
						$db->query($sql);
					}
				}

				$data = file_get_contents("data.sql");

				$queries = explode(";", $data);

				foreach ($queries as $sql) {
					if (trim($sql)) {
						$db->query($sql);
					}
				}

			}

			echo '<ol>
				<li>Database schema created!</li>
				<li>Default data inserted!</li>
			</ol>';

		} else {
			heading(2, "Create Database");
		}