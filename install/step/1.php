<?php
	
	if (
		isset($_POST["host"]) && 
		isset($_POST["user"]) && 
		isset($_POST["pass"]) && 
		isset($_POST["database"]) 
	) {

		try {
			$test = NEW PDO("mysql:host=" . $_POST["host"] . ";dbname=" . $_POST["database"], $_POST["user"], $_POST["pass"]);

			$configFile = '<?php

			    $config = array();

			    $config["debug"] = 0;

			    $config["db"] = array(
			        "host" => "'.addslashes($_POST["host"]).'", 
			        "database" => "'.addslashes($_POST["database"]).'",
			        "user" => "'.addslashes($_POST["user"]).'",
			        "pass" => "'.addslashes($_POST["pass"]).'"
			    );

			?>';

			file_put_contents("../config.php", str_replace("	", "", $configFile));

		} catch (Exception $e) {
			echo "failed";
		}

	}

	require "../config.php";

	try {
		include "../dbconn.php";
		success(1, "Database Login");
		echo "<ol><li>Config file created!</li></ol>";
	} catch (Exception $e) {
		failed(1, "Database Login");

		echo '
			<div class="panel panel-default">
				<div class="panel-body">
					<form method="post" action="#">
						<div class="form-group">
							<label for="exampleInputEmail1">Hostname</label>
							<input type="text" class="form-control" name="host" value="localhost">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Username</label>
							<input type="text" class="form-control" name="user">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Password</label>
							<input type="password" class="form-control" name="pass">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Database</label>
							<input type="text" class="form-control" name="database">
						</div>
						<div class="text-right">
							<button type="submit" class="btn btn-success">Test Connection</button>
						</div>

					</form>
				</div>
			</div>
		';

	}