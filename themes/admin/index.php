<?php
    if (!class_exists("mainTemplate")) {

    class mainTemplate {

        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <link href="themes/{_theme}/css/bootstrap.min.css" rel="stylesheet" />
                <link href="themes/{_theme}/css/admin.css" rel="stylesheet" />
                <link rel="shortcut icon" href="themes/{_theme}/images/icon.png" />
                <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/jquery.mCustomScrollbar.min.css" />
                <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom.css">
                <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom-themes.css">
                <link rel="stylesheet" href="themes/{_theme}/3rdparty/datatables/datatables.min.css">
                <link rel="stylesheet" href="themes/{_theme}/3rdparty/summernote/summernote.css">

                <title>{game_name} - {page}</title>
            </head>
            <body>

                <nav class="navbar navbar-default navbar-fixed-top">
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
                            <ul class="nav navbar-nav navbar-right">
                                    <li><a href="?">Back To The Game</a></li>
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
                                            <i class="glyphicon glyphicon-certificate"></i>
                                            <span>Online</span>
                                        </span>
                                    </div>
                                </div>
                                <!-- sidebar-header  -->
                                <div class="sidebar-search">

                                    <form class="navbar-form navbar-left" method="post" action="?page=admin&module=users&action=view">
                                        <div>
                                            <div class="input-group">
                                                <input type="text" name="user" class="form-control search-menu" placeholder="Search username or ID ...">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- sidebar-search  -->
                                <div class="sidebar-menu">
                                    <ul>
                                        <li class="header-menu">
                                            <span>Actions</span>
                                        </li>

                                        {#each menus}
                                            <li class="sidebar-dropdown {#each items}{#if active}active{/if}{/each}">
                                                <a>
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
                                <a href="?page=notifications">
                                    <i class="glyphicon glyphicon-bell"></i>
                                    {#if notificationCount}
                                        <span class="label label-warning notification">{notificationCount}</span>
                                    {/if}
                                </a>
                                <a href="?page=mail">
                                    <i class="glyphicon glyphicon-envelope"></i>
                                    {#if mail}
                                        <span class="label label-success notification">{mail}</span>
                                    {/if}
                                </a>
                                <a href="?page=profile">
                                    <i class="glyphicon glyphicon-cog"></i>
                                </a>
                                <a href="?page=logout">
                                    <i class="glyphicon glyphicon-off"></i>
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
                            <{game}>
                        </div>
                    </div>
                </div>


                <script src="themes/{_theme}/js/jquery.js"></script>
                <script src="themes/{_theme}/3rdparty/datatables/datatables.min.js"></script>

                <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.html5.min.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.flash.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
                <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
                <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

                <script src="themes/{_theme}/js/bootstrap.min.js"></script>
                <script src="themes/{_theme}/3rdparty/summernote/summernote.js"></script>
                <script src="themes/{_theme}/3rdparty/sidebar/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
                <script src="themes/{_theme}/3rdparty/sidebar/assets/js/custom.js"></script>
                <script src="themes/{_theme}/js/admin.js"></script>
                {#if moduleJSFile}
                    <script src="{moduleJSFile}"></script>
                {/if}

            </body>
            </html>';
    }
}

?>
