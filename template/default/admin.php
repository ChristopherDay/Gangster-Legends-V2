<?php
    if (!class_exists("mainTemplate")) {

    class mainTemplate {

        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <link href="template/default/css/bootstrap.min.css" rel="stylesheet" />
                <link href="template/default/css/admin.css" rel="stylesheet" />
                <link rel="shortcut icon" href="template/default/images/icon.png" />
                <title>{game_name} - {page}</title>
            </head>
            <body>

                <nav class="navbar navbar-default">
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
                                {#each menus.homeLinks.items}
                                    <li><a href="{url}">{text}</a></li>
                                {/each}
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>

                <div class="admin-container">
                    <div class="admin-sidenav">
                        <ul class="nav nav-pills nav-stacked">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {menus.moduleLinks.title}
                                </div>
                                <div class="panel-body hidden-xs">
                                    {#each menus.moduleLinks.items}
                                        {#if seperator}
                                            <hr />
                                        {/if}
                                        {#unless seperator}
                                            {#unless hide}
                                                <a href="{url}">{text}</a> <br />
                                            {/unless}
                                        {/unless}
                                    {/each}
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div class="admin-page">
                        {#if menus.moduleActions.items}
                            <ul class="nav nav-tabs">
                                {#each menus.moduleActions.items}
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
            </body>
            </html>';
    }    
}

?>
