<?php
	session_start();

	if ($_POST['username'] && $_POST['password']) {
    
		include 'dbconn.php';
    	include 'class/user.php';
    	include 'class/page.php';
		
        $page = new page();
        
		$user = new user(NULL, $_POST['username']);
		
		if (isset($user->info->U_id)) {
			if ($user->info->U_password == $user->encrypt($_POST['password'])) {
				
				$_SESSION['userID'] = $user->info->U_id;
				
				header("Location:index.php");
				
			} else {
				
				echo 'You have entered a wrong password!';
				
			}
		} else {
			echo 'Invalid username!';	
		}
	}
?>