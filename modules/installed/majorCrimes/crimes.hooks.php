<?php

	new hook("actionMenu", function () {
		return array(
			"url" => "?page=crimes", 
			"text" => "Crimes", 
			"sort" => 100
		);
	});
?>