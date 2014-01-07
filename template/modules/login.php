<?php

    class loginTemplate extends template {
    
        public $loginPage = true; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $blankElement = '{var1} {var2} {var3}';
        
        public $loginForm = '<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Login</h3>
            </div>
            <div class="panel-body">
                <form action="?page=login&action=login" method="post">
                    <input type="input" class="form-control" name="username" placeholder="Username" /><br />
                    <input type="password" class="form-control" name="password" placeholder="Password" /><br />
                    <input type="submit" value="Login" class="btn pull-right" />
                    <input type="button" value="Register" class="btn btn-link pull-right" onClick="document.location = \'?page=register\';" />
                </form>
            </div>
        </div>';
        
    }

?>