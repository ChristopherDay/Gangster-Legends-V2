<?php
	if (!class_exists("mainTemplate")) {

	class mainTemplate {

		public $pageMain =  '
<!DOCTYPE html>
<html>
	<head>
	   <link href="themes/{_theme}/css/bootstrap.min.css" rel="stylesheet" />
	   <link href="themes/{_theme}/css/admin.css" rel="stylesheet" />
	   <link rel="shortcut icon" href="themes/{_theme}/images/icon.png" />
	   <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/jquery.mCustomScrollbar.min.css" />
	   <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom.css">
	   <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom-themes.css">
	   <link rel="stylesheet" href="themes/{_theme}/3rdparty/datatables/datatables.min.css">
	   <link rel="stylesheet" href="themes/{_theme}/3rdparty/summernote/summernote.css">
	   <link rel="stylesheet" href="themes/{_theme}/css/styles.css">
	   {#if moduleCSSFile}
            <link href="{moduleCSSFile}" rel="stylesheet" />
        {/if}
        {#each CSSFiles}
            <link href="{.}" rel="stylesheet" />
        {/each}
	   <title>{game_name} - {page}</title>
	</head>
	<body>
		
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="?page=admin&module=admin" class="navbar-brand">
						<img src="themes/{_setting "theme"}/images/logo.png" /> {game_name} ACP 
					</a>
				</div>
				<form class="navbar-form navbar-left" role="search" method="post" action="?page=admin&module=users&action=view">
					<div class="form-group">
						<input type="text" name="user" class="form-control" placeholder="Find User">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="?page=admin&module=admin">ACP Dashboard</a></li>
						<li><a href="?">Back To Game</a></li>
						{#if adminModule}
							<li><a href="?page={adminModule}">View Module</a></li>
						{/if}
						<li><a href="?page=logout">Logout</a></li>
				 	</ul>
				</div>
		   </div>
		</nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 col-lg-2 navigation">
                    {#each menus}

                        <div class="list-group">
                            <div class="list-group-item heading">
                                {title}
                            </div>
                            {#each items}
                                {#unless seperator}
                                    {#unless hide}
                                        <a href="{url}" class="list-group-item {#if active} active{/if}">
                                        	{text}
                                        </a>
                                    {/unless}
                                {/unless}
                            {/each}
                        </div>
                    {/each}

				</div>
				<div class="col-md-9 col-lg-10">
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
							<{alerts}>
							<{game}>
						</div>
					</div>
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
		{#each JSFiles}
		    <script src="{.}"></script>
		{/each}
		

	</body>
</html>';

	}
}

?>