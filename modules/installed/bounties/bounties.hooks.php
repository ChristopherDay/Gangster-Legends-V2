<?php

	new hook("killMenu", function () {
		return array(
			"url" => "?page=bounties", 
			"text" => "Bounties", 
			"sort" => 150
		);
	});

?>