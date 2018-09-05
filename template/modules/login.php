<?php

    class loginTemplate extends template {
    
        public $loginPage = true; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $loginForm = '
		<div class="login-logo">
			<img src="template/default/images/logo.png" alt="Ganger Legends" />
		</div>
		<div class="panel panel-default">
            <div class="panel-heading">
                Login
            </div>
            <div class="panel-body">
				{text}
                <form action="?page=login&action=login" method="post">
                    <input autocomplete="off" type="input" class="form-control" name="username" placeholder="Username" /><br />
                    <input type="password" class="form-control" name="password" placeholder="Password" /><br />
                    <button type="submit" class="btn pull-right">Login</button>
                    <a class="btn btn-link pull-right" href="?page=register">Register</a>
                </form>
            </div>
        </div>';
        
    }

?>