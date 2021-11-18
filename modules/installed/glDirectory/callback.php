<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	include "../../../config.php";
	include "../../../dbconn.php";
	include "../../../class/settings.php";
	
	/* Include correct files for gl 2.2.x / 2.3.x */
	if (file_exists("../../../class/templateRender.php")) {
		include "../../../class/hooks.php";
		include "../../../class/image.php";
	} else {
		include "../../../class/hook.php";
		include "../../../class/items.php";
		include "../../../class/fastImage.php";
	}

	include "../../../class/user.php";

	if (isset($_REQUEST["user"]) && isset($_REQUEST["key"])) {

		$settings = new Settings();

		$keys = array(
			"request" => $settings->loadSetting("voteKey1"),
			"response" => $settings->loadSetting("voteKey2")
		);

		$todayUTC = gmdate("Y-m-d");
		$userID = $_REQUEST["user"];
		$responseKey = $_REQUEST["key"];

		$key = hash("sha256", $todayUTC . $userID . $keys["response"]);

		if ($key === $responseKey) {
			echo 'Valid vote!';
			$user = new User($userID);

			if ($user->checkTimer("glDirVote")) {
				$money = mt_rand($settings->loadSetting("voteMin"), $settings->loadSetting("voteMax"));
				$user->set("US_money", $user->info->US_money + $money);
				$user->newNotification(
					"Thank you for voting, as a thank you you have received $" . number_format($money)
				);
				$user->updateTimer('glDirVote', strtotime("midnight")+86400);
			}
		} else {
			http_response_code(401);
			echo 'Incorrect parameters!';
		}

	} else {
		http_response_code(400);
		echo 'Invalid parameters!';
	}

