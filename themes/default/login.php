<?php

    class mainTemplate {
        
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
                    <img src="themes/default - grey/images/logo.png" alt="Ganger Legends" />
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
                                Gangsters: {usersOnline}
                            </div>
                            <div class="col-md-6">
                                Gangsters Online: {users}
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