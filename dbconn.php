<?php

	$db = NEW PDO("mysql:host=localhost;dbname=GLV2", "root", "");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
?>