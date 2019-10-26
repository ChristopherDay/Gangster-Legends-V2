<?php
	
	session_start();

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require "../class/hooks.php";
	require "../class/settings.php";
	require "../class/image.php";
	require "../class/user.php";

	function heading($step, $text) {
		echo '
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong class="text-danger">
						<i class="glyphicon glyphicon-remove-circle"></i>
						Step '.$step.'
					</strong> - '.$text.'
		
				</div>
			</div>';
	}

	function success($step, $text) {
		echo '
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong class="text-success">
						<i class="glyphicon glyphicon-ok-circle"></i>
						Step '.$step.'
					</strong> - '.$text.'
		
				</div>
			</div>';
	}

	function failed($step, $text) {
		echo '
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong class="text-danger">
						<i class="glyphicon glyphicon-remove-circle"></i>
						Step '.$step.'
					</strong> - '.$text.'
				</div>
			</div>';
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gangster Legends V2 - Installer</title>
		<link href="../themes/default/css/bootstrap.min.css" rel="stylesheet" />
	</head>
	<body>
		<div class="container">
			<h1 class="text-center">
				Gangster Legends V2 Installer
			</h1>
			<?php
				include "step/1.php";
				include "step/2.php";
				include "step/3.php";
				include "step/4.php";
			?>
		</div>
	</body>
</html>