<?php

	class deadTemplate extends template {
		
		public $newAccount = "
			<h4>You have been shot by {>userName}!</h4>
			<p>
				To get revenge you can create a new account!
			</p>
		 
			<form method='post' action='#'>
				<input type='number' name='username' class='form-control form-control-inline' placeholder='New Username' /> 
				<button class='btn btn-default'>Create Account</button>
			</form>
		";
		
	}

?>