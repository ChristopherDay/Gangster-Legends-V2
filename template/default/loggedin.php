<?php

    class template {
        
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
        
        public $newsArticle = '<h3>{var1} <small>{var2} at {var3}</small></h3><p>{var4}</p>';
        
        public $crimeHolder = '<div class="crime-holder">
            <p>{var1} ({var2}) <span class="commit"><a href="?page=crimes&commit={var4}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{var3}%;"></div>
            </div>
        </div>';
        
        public $theftHolder = '<div class="crime-holder">
            <p>{var1} <span class="commit"><a href="?page=theft&commit={var2}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{var3}%;"></div>
            </div>
        </div>';
        
        public $locationHolder = '<div class="crime-holder">
            <p>{var1} - {var4} - ${var2} <span class="commit"><a href="?page=travel&location={var3}">Travel</a></span></p>
        </div>';
        
        public $success = '<div class="alert alert-success">{var1}</div>';
        public $error = '<div class="alert alert-danger">{var1}</div>';
        public $info = '<div class="alert alert-info">{var1}</div>';
        public $warning = '<div class="alert alert-warning">{var1}</div>';
        
        public $garageTable = '<table class="table table-condensed table-responsive">
        <tr><th>Name</th><th style="width:100px">Damage</th><th style="width:100px">Value</th><th style="width:150px">Location</th><th style="width:150px">Links</th></tr>
        {var1}
        </table>';
        
        public $garageTableRow = '<tr><td>{var1}</td><td>{var3}</td><td>${var5}</td><td>{var2}</td><td>
        <a href="?page=garage&action=sell&id={var4}">Sell</a> ~
        <a href="?page=garage&action=crush&id={var4}">Crush</a> ~
        <a href="?page=garage&action=repair&id={var4}">Repair</a></td></tr>';
        
        public $policeChase = '<div style="text-align:center;">
        <p>What direction do you want to travel?</p>
        <p><a class="btn" href="?page=policeChase&move=f">Foward</a><br />
        <a class="btn" href="?page=policeChase&move=l">Left</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn" href="?page=policeChase&move=r">Right</a><br />
        <a class="btn" href="?page=policeChase&move=u">U-Turn</a><br /></p>
        </div>';
        
        public $bulletPage = '<p>Welcome to the {var1} bullet factory, currently it has {var2} bullets in stock at the cost of ${var3} each!</p>
        <p>
            You can buy up to {var4} at once!
        </p>
        <p>
            <form action="#" method="post">
                <input type="text" class="form-control" name="bullets" style="width:calc(97% - 100px); display:inline-block;" placeholder="Qty. to buy" />
                <input type="submit" class="btn btn-link" style="display:inline-block; width:100px;" value="Buy" />
            </form>
        </p>
        ';

    }

?>
