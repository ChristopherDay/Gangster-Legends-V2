<?php

    class mainTemplate {
    
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page} {all_notifications}</title>
                <link rel="stylesheet/less" href="template/default/css/bootstrap.min.css">
                <link rel="stylesheet/less" href="template/default/less/bootstrap.less">
                <script src="template/default/js/less.js"></script>
            </head>
            
            <body>
                
                <div class="game-logo">
                    <div class="user-info">
                        <div class="user-info-row">
                            <div class="user-info-col-main">Rank:</div>
                            <div class="user-info-col-stat">{rank} {exp_perc}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Gang:</div>
                            <div class="user-info-col-stat">{gang}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Health:</div>
                            <div class="user-info-col-stat">{health}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Weapon:</div>
                            <div class="user-info-col-stat">{weapon}</div>
                        </div>
                    </div>
                    <div class="logo">
                        {game_name}<br />
                        <small><span class="{mail_class}">Mail {mail} -</span> <span class="{notifications_class}">Notifications {notifications}</span></small>
                    </div>
                    <div class="user-info">
                        <div class="user-info-row">
                            <div class="user-info-col-main">Money:</div>
                            <div class="user-info-col-stat">{money}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Bullets:</div>
                            <div class="user-info-col-stat">{bullets}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Backfire:</div>
                            <div class="user-info-col-stat">{backfire}</div>
                        </div>
                        <div class="user-info-row">
                            <div class="user-info-col-main">Credits:</div>
                            <div class="user-info-col-stat">{credits}</div>
                        </div>
                    </div>
                </div>
            
                <div class="game">
                    <div class="left-menu">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Actions</h3>
                            </div>
                            <div class="panel-body">
                                <a href="?page=crimes">Crimes</a> <br />
                                <a href="?page=theft">Theft</a> <br />
                                <a href="?page=policeChase">Police Chase</a> <br />
                                <!--Organised Crime<br />
                                Murder-->
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{location}</h3>
                            </div>
                            <div class="panel-body">
                                <a href="?page=bullets">Bullet Factory</a><br />
                                <a href="?page=travel">Travel</a> <br />
                                <a href="?page=garage">Garage</a> <br />
                                <!--Search <br />
                                Jail-->
                            </div>
                        </div>
                        <!--<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Casinos</h3>
                            </div>
                            <div class="panel-body">
                                Blackjack<br />
                                Race Track<br />
                                Lottery
                            </div>
                        </div>-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Account</h3>
                            </div>
                            <div class="panel-body">
                                <!--My Profile<br />
                                Edit Profile<br />-->
                                <a href="?page=logout">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div class="game-main">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{page}</h3>
                            </div>
                            <div class="panel-body">
                                {game}
                            </div>
                        </div>
                    </div>
                    <div class="right-menu">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Chat</h3>
                            </div>
                            <div class="panel-body">
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                                Dayo: some text<br />
                            </div>
                        </div>
                    </div>
                </div>
                
                <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
                <script src="template/default/js/bootstrap.min.js"></script>
            </body>
        </html>';
        
    }

?>