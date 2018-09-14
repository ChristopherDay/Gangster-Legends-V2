<?php
    if (!class_exists("mainTemplate")) {

    class mainTemplate {

        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <link href="template/default/css/bootstrap.min.css" rel="stylesheet" />
                <link href="template/default/css/admin.css" rel="stylesheet" />
                <link rel="shortcut icon" href="template/default/images/icon.png" />
                <link rel="stylesheet" href="template/default/3rdparty/sidebar/assets/css/jquery.mCustomScrollbar.min.css" />
                <link rel="stylesheet" href="template/default/3rdparty/sidebar/assets/css/custom.css">
                <link rel="stylesheet" href="template/default/3rdparty/sidebar/assets/css/custom-themes.css">
                <title>{game_name} - {page}</title>
            </head>
            <body>

                <nav class="navbar navbar-inverse navbar-static-top">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Administration Panel</a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <form class="navbar-form navbar-left" method="post" action="?page=admin&module=users&action=view">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="user" placeholder="Username or ID">
                                </div>
                                <button type="submit" class="btn btn-default">Find User</button>
                            </form>
                            <ul class="nav navbar-nav navbar-right">
                                    <li><a href="?page=loggedin">Back To The Game</a></li>
                                    <li><a href="?page=logout">Logout</a></li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>

                <div class="admin-container">
                    <div class="admin-sidenav chiller-theme">
                        <nav id="sidebar" class="sidebar-wrapper">
                            <div class="sidebar-content">
                                <div class="sidebar-header">
                                    <div class="user-pic">
                                        <img class="img-responsive img-rounded" src="{pic}" alt="User picture">
                                    </div>
                                    <div class="user-info">
                                        <span class="user-name">
                                            {username}
                                        </span>
                                        <span class="user-role">Administrator</span>
                                        <span class="user-status">
                                            <i class="fa fa-circle"></i>
                                            <span>Online</span>
                                        </span>
                                    </div>
                                </div>
                                <!-- sidebar-header  -->
                                <div class="sidebar-search">
                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control search-menu" placeholder="Search...">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- sidebar-search  -->
                                <div class="sidebar-menu">
                                    <ul>
                                        <li class="header-menu">
                                            <span>{menus.moduleLinks.title}</span>
                                        </li>

                                        {#each menus}
                                            <li class="sidebar-dropdown {#each items}{#if active}active{/if}{/each}"> 
                                                <a href="#">
                                                    <span>{title}</span>
                                                </a>
                                                <div class="sidebar-submenu">
                                                    <ul>
                                                        {#each items}
                                                            {#if seperator}
                                                                <hr />
                                                            {/if}
                                                            {#unless seperator}
                                                                {#unless hide}
                                                                    <li {#if active}class="active"{/if}><a href="{url}">{text}</a></li>
                                                                {/unless}
                                                            {/unless}
                                                        {/each}
                                                    </ul>
                                                </div>
                                            </li>

                                        {/each}
                                    </ul>
                                </div>
                                <!-- sidebar-menu  -->
                            </div>
                            <!-- sidebar-content  -->
                            <div class="sidebar-footer">
                                <a href="#">
                                    <i class="fa fa-bell"></i>
                                    <span class="label label-warning notification">3</span>
                                </a>
                                <a href="#">
                                    <i class="fa fa-envelope"></i>
                                    <span class="label label-success notification">7</span>
                                </a>
                                <a href="#">
                                    <i class="fa fa-gear"></i>
                                    <span class="badge-sonar"></span>
                                </a>
                                <a href="#">
                                    <i class="fa fa-power-off"></i>
                                </a>
                            </div>
                        </nav>
                    </div>
                    <div class="admin-page">
                        {#if moduleActions.items}
                            <ul class="nav nav-tabs">
                                {#each moduleActions.items}
                                    {#unless hide}
                                        <li role="presentation" class="{#if active}active{/if}"><a href="{url}">{text}</a></li>
                                    {/unless}
                                    {#if hide}
                                        {#if active}
                                            <li role="presentation" class="active"><a href="#">{text}</a></li>
                                        {/if}
                                    {/if}
                                {/each}
                            </ul>
                        {/if}
                        <div class="page">
                            {game}
                        </div>
                    </div>
                </div>
                

                <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
                <script src="template/default/js/bootstrap.min.js"></script>
                <script src="template/default/3rdparty/sidebar/assets/js//jquery.mCustomScrollbar.concat.min.js"></script>
                <script src="template/default/3rdparty/sidebar/assets/js/custom.js"></script>
            </body>
            </html>';
    }    
}

?>
