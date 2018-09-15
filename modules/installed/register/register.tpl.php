<?php

    class registerTemplate extends template {
    
        public $registerForm = '
		<div class="login-logo">
			<img src="template/default/images/logo.png" alt="Ganger Legends" />
		</div>
		<div class="panel panel-default">
            <div class="panel-heading">
                Register
            </div>
            <div class="panel-body">
				
			{text}
                <form action="?page=register&action=register" method="post">
                    <input class="form-control" type="text" name="username" placeholder="Username" /><br />
                    <input class="form-control" type="text" autocomplete="off" name="email" placeholder="EMail" /><br />
                    <input class="form-control" type="password" name="password" placeholder="Password" /><br />
                    <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password" /><br />
                    <button type="submit" class="btn pull-right">Register</button>
                    <a class="btn btn-link pull-right" href="?page=login">Login</a>
                </form>
            </div>
        </div>';
        
    }

?>