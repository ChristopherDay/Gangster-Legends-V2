<?php

    class mainTemplate {

        public function __construct() {
            global $db, $page;


            $usersOnline = $db->prepare("
                SELECT COUNT(*) as 'count' FROM userTimers WHERE UT_desc = 'laston' AND UT_time > ".(time()-900)."
            ");
            $usersOnline->execute();
            $users = $db->prepare("
                SELECT COUNT(*) as 'count' FROM users
            ");
            $users->execute();

            $page->addToTemplate("usersOnlineNow", number_format($usersOnline->fetch(PDO::FETCH_ASSOC)["count"]));
            $page->addToTemplate("registeredUsers", number_format($users->fetch(PDO::FETCH_ASSOC)["count"]));

        }
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page}</title>
                <link rel="stylesheet" href="themes/{_theme}/css/bootstrap.min.css">
                <link rel="stylesheet" href="themes/{_theme}/css/style.css">
                <link rel="shortcut icon" href="themes/{_theme}/images/icon.png" />
                <meta name="viewport" content="width=device-width, initial-scale=1">
            </head>
            
            <body>


                <div class="login-logo">
                    <img src="themes/{_theme}/images/logo.png" alt="Ganger Legends" />
                </div>
            
                {#if loginSuffix}
                    <div class="panel panel-default login-form">
                        <div class="panel-body">
                            <{loginSuffix}>
                        </div>
                    </div>
                {/if}

                <div class="panel panel-default login-form">
                    <div class="panel-heading">
                        {page}
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-justified">
                            <li>
                                <a href="?page=login">Login</a>
                            </li>
                            <li>
                                <a href="?page=register">Register</a>
                            </li>
                            <li>
                                <a href="?page=news">News</a>
                            </li>
                        </ul>
                        <{alerts}>
                        <{game}>
                    </div>
                </div>

                <div class="panel panel-default login-form">
                    <div class="panel-body">
                        {#if loginPostfix}
                            <{loginPostfix}>
                            <hr />
                        {/if}
                        <div class="row text-center">
                            <div class="col-md-6">
                                Gangsters: {registeredUsers}
                            </div>
                            <div class="col-md-6">
                                Gangsters Online: {usersOnlineNow}
                            </div>
                        </div>
                    </div>
                </div>

                <script src="themes/{_theme}/js/jquery.js"></script>
                <!--<script src="themes/{_theme}/js/bootstrap.min.js"></script>-->
                <script src="themes/{_theme}/js/timer.js"></script>
                <script src="themes/{_theme}/js/mobile.js"></script>
            </body>
        </html>';
    
    }

?>