<?php

    function __roundDate($ts) {
        return date("jS F Y H:i", $ts);
    }

    class mainTemplate {

        public function __construct() {
            global $db, $page;

            $round = new Round();

            $usersOnline = $db->select("
                SELECT COUNT(*) as 'count' FROM userTimers WHERE UT_desc = 'laston' AND UT_time > ".(time()-900)."
            ");
            $users = $db->select("
                SELECT COUNT(*) as 'count' FROM users WHERE U_round = :round
            ", array(
                ":round" => $round->id
            ));

            $page->addToTemplate("usersOnlineNow", number_format($usersOnline["count"]));
            $page->addToTemplate("registeredUsers", number_format($users["count"]));

        }
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page}</title>
                <link rel="stylesheet" href="themes/{_theme}/css/bootstrap.min.css">
                <link rel="stylesheet" href="themes/{_theme}/css/style.css">
                <link rel="shortcut icon" href="themes/{_theme}/images/icon.png" />
                <meta name="timestamp" content="{timestamp}">
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
                            {#each menus.login.items}
                                <li>
                                    <a href="{url}">{text}</a>
                                </li>
                            {/each}
                        </ul>
                        <{alerts}>

                        {#unless round}
                            <div class="alert alert-danger text-center">
                                {#if nextRound}
                                    {nextRound.name} starts in <span data-timer-type="inline" data-timer="{nextRound.start}"></span> <br />
                                    <small>{__roundDate nextRound.start}</small>
                                {else}
                                    {game_name} is currently closed!
                                {/if}
                            </div>
                        {/unless}
                        <{game}>
                    </div>
                </div>

                <div class="panel panel-default login-form">
                    <div class="panel-heading">
                        Gane Stats
                    </div>
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