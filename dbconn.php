<?php
	
	require 'class/db.php';
	require 'config.php';

    $db = NEW glPDO("mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["database"], $config["db"]["user"], $config["db"]["pass"]);

?>