<?php

    class registerTemplate extends template {
    
        public $loginPage = true; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $blankElement = '{var1} {var2} {var3}';
        
        public $registerForm = '<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Register</h3>
            </div>
            <div class="panel-body">
                <form action="?page=register&action=register" method="post">
                    <input class="form-control" type="text" name="username" placeholder="Username" /><br />
                    <input class="form-control" type="text" name="email" placeholder="EMail" /><br />
                    <input class="form-control" type="password" name="password" placeholder="Password" /><br />
                    <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password" /><br />
                    <input type="submit" value="Register" class="btn pull-right" />
                    <input type="button" value="Login" class="btn btn-link pull-right" onClick="document.location = \'?page=login\';" />
                </form>
            </div>
        </div>';
        
    }

?>