<?php

    new Hook("currencyFormat", function () {
        return function ($money) { 
            return "$" . number_format($money); 
        };
    });


    if (!class_exists("mainTemplate")) {
        class mainTemplate {

            public $globalTemplates = array();

            public function __construct() {

                global $page;

                $page->addToTemplate("gameTime", date("jS F Y, H:i:s"));
     
                $this->globalTemplates["success"] = '<div class="alert alert-success">
                    <button type="button" class="close">
                        <span>&times;</span>
                    </button>
                    <{text}>
                </div>';
                $this->globalTemplates["error"] = '<div class="alert alert-danger">
                    <button type="button" class="close">
                        <span>&times;</span>
                    </button>
                    <{text}>
                </div>';
                $this->globalTemplates["info"] = '<div class="alert alert-info">
                    <button type="button" class="close">
                        <span>&times;</span>
                    </button>
                    <{text}>
                </div>';
                $this->globalTemplates["warning"] = '<div class="alert alert-warning">
                    <button type="button" class="close">
                        <span>&times;</span>
                    </button>
                    <{text}>
                </div>';

            }
        
            public $pageMain =  '<!DOCTYPE html>
    <html>
        <head>
            <link href="themes/{_theme}/css/bootstrap.min.css" rel="stylesheet" />
            <link href="themes/{_theme}/css/style.css" rel="stylesheet" />
            {#if moduleCSSFile}
                <link href="{moduleCSSFile}" rel="stylesheet" />
            {/if}
            {#each CSSFiles}
                <link href="{.}" rel="stylesheet" />
            {/each}
            <link rel="shortcut icon" href="themes/{_theme}/images/icon.png" />
            <meta name="timestamp" content="{timestamp}">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>{game_name} - {page}</title>
        </head>
        <body class="user-status-{userStatus}">

            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 logo-container text-center">
                            <button type="button" class="navbar-toggle">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <img src="themes/{_theme}/images/logo.png" alt="Gangster Legends" /> 
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <small>
                        {gameTime} - 
                        {#if round}
                            {round.name}
                        {else}
                            {nextRound.name}
                        {/if}
                    </small>
                </div>
            </div>
                
            <div class="container">
                <div class="side-bar"> 

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            User Account
                        </div>
                        <div class="list-group">
                            <div class="list-group-item">
                                <strong>Username: </strong>
                                <small class="pull-right">
                                    {>userName}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>Money: </strong>
                                <small class="pull-right" data-ajax-element="money">
                                    {money}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>Bullets: </strong>
                                <small class="pull-right" data-ajax-element="bullets">
                                    {bullets}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>{_setting "pointsName"}: </strong>
                                <small class="pull-right" data-ajax-element="points">
                                    {points}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>{_setting "gangName"}: </strong>
                                <small class="pull-right"data-ajax-element="gang.name">
                                    {gang.name}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>Location: </strong>
                                <small class="pull-right" data-ajax-element="location">
                                    {location}
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>Health: </strong>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" style="width: {health}%" data-ajax-element="health" data-ajax-type="css" data-ajax-css="width" data-ajax-value-postfix="%"></div>
                                </div>
                                <small class="pull-right" data-ajax-element="health" data-ajax-value-postfix="%">
                                    {health}%
                                </small>
                            </div>
                            <div class="list-group-item">
                                <strong>Rank: </strong>

                                {#if maxRank}
                                    <small class="pull-right" data-ajax-element="rank">
                                        {rank} (Max Rank)
                                    </small>
                                {else}
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" style="width: {exp_perc}%" data-ajax-element="exp_perc" data-ajax-type="css" data-ajax-css="width" data-ajax-value-postfix="%"></div>
                                    </div>
                                    <small class="pull-right" data-ajax-element="rank">
                                        {rank}
                                    </small>
                                {/if}
                            </div>
                        </div>
                    </div>


                    {#each menus}
                        {#if items}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {title}
                                </div>
                                <div class="list-group">
                                    {#each items}
                                        {#unless hide}
                                            {#if url}
                                                <a href="{url}" class="list-group-item" {#if notAjax}data-not-ajax{/if}>
                                                    {text}
                                                    <small class="pull-right">
                                                        <span data-ajax-element="{extraID}">
                                                            {extra}
                                                        </span>
                                                        {#if timer}
                                                            <span data-timer-type="inline" data-timer="{timer}" data-ajax-element="{templateTimer}" data-ajax-type="attr" data-ajax-attr="data-timer"></span>
                                                        {/if}
                                                    </small>
                                                </a>
                                            {/if}
                                        {/unless}
                                    {/each}
                                </div>
                            </div>
                        {/if}
                    {/each}

                </div>
                    
                <div class="game-container text-center">
                    <div data-ajax-element="alerts" data-ajax-type="html">
                        <{alerts}>
                    </div>
                    <div data-ajax-element="game" data-ajax-type="html">
                        <{game}>
                    </div>
                </div>

            </div>

            <script src="themes/{_theme}/js/jquery.js"></script>
            <script src="themes/{_theme}/js/bootstrap.min.js"></script>
            <!--<script src="themes/{_theme}/js/ajax.js"></script>-->
            <script src="themes/{_theme}/js/timer.js"></script>
            <script src="themes/{_theme}/js/mobile.js"></script>
            {#if moduleJSFile}
                <script src="{moduleJSFile}"></script>
            {/if}
            {#each JSFiles}
                <script src="{.}"></script>
            {/each}
        </body>
    </html>';
            
        }
    }
?>
