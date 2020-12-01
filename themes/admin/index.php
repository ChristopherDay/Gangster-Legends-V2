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
	   <title>{game_name} - {page}</title>
	</head>
	<body>
		
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="#" class="navbar-brand">
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
				<div class="col-md-3 col-lg-2">
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

	</body>
</html>';

		public $pageMainOLD =  '
<!DOCTYPE html>
<html>
<head>
  <title>{game_name} - ACP</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="themes/{_theme}/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="themes/{_theme}/css/adminlte.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom.css">
  <link rel="stylesheet" href="themes/{_theme}/3rdparty/sidebar/assets/css/custom-themes.css">
  <link rel="stylesheet" href="themes/{_theme}/3rdparty/datatables/datatables.min.css">
  <link rel="stylesheet" href="themes/{_theme}/3rdparty/summernote/summernote.css">
</head>
<body class="hold-transition sidebar-mini layout-boxed text-sm">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">

			<li class="nav-item d-none d-sm-inline-block">
			  <a href="?module=&action=" class="nav-link"></a>
			</li>
	</ul>

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
	  <li class="nav-item d-none d-sm-inline-block">
		<a href="?" class="nav-link">Back to the Game</a>
	  </li>
	  <li class="nav-item d-none d-sm-inline-block">
		<a href="index.php?page=logout" class="nav-link">Logout</a>
	  </li>
	</ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index.php" class="brand-link">
	  <img src="themes/{_setting "theme"}/images/logo.png" class="brand-image">
	  <span class="brand-text font-weight-light">&nbsp;</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">

	  <!-- Sidebar Menu -->
	  <nav class="mt-2">
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		  <!-- Add icons to the links using the .nav-icon class
			   with font-awesome or any other icon font library -->

				{#each menus}
				  <li class="nav-item has-treeview menu-open">
					  <a href="#" class="nav-link active">
						  <p>
							<span>{title}</span>
						  </p>
						  <i class="fas fa-angle-left right"></i>
					  </a>
					  <ul class="nav nav-treeview">
						  {#each items}
							  {#unless seperator}
								  {#unless hide}
									  <li class="nav-item">
										<a href="{url}" class="nav-link">
										  {text}
										</a>
									  </li>
								  {/unless}
							  {/unless}
						  {/each}
					  </ul>
				  </li>


			  {/each}

		</ul>
	  </nav>
	  <!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			<h1><?php echo $active["name"]; ?> </h1>
		  </div>
		</div>
	  </div><!-- /.container-fluid -->
	</section>

	<section class="content">
	  <div class="admin-page">
		  {#if moduleActions.items}
			  <ul class="nav nav-tabs">
				  {#each moduleActions.items}
					  {#unless hide}
						  <li>
							<a class="nav-link {#if active}active{/if}" href="{url}">{text}</a>
						  </li>
					  {/unless}
					  {#if hide}
						  {#if active}
							  <li role="presentation" class="active"><a href="#">{text}</a></li>
						  {/if}
					  {/if}
				  {/each}
			  </ul>
		  {/if}
		  <div class="card">
			<div class="card-body">
				<{game}>
			</div>
		  </div>
	  </div>
	</section>
	<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
	<div class="float-right d-none d-sm-block">
	  <b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2020 <a target="_blank" href="https://heatingsave.co.uk">HeatingSave</a>.</strong> All rights
	reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="themes/{_theme}/plugins/jquery/jquery.min.js"></script>

<!-- moment.js -->
<script src="themes/{_theme}/plugins/moment/moment.min.js"></script>

<!-- FLOT CHARTS -->
<script src="themes/{_theme}/plugins/flot/jquery.flot.js"></script>

<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="themes/{_theme}/plugins/flot-old/jquery.flot.resize.min.js"></script>

<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="themes/{_theme}/plugins/flot-old/jquery.flot.pie.min.js"></script>

<!-- Bootstrap 4 -->
<script src="themes/{_theme}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="themes/{_theme}/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="themes/{_theme}/js/demo.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="modules/<?php echo $active["dir"]; ?>/script.js"></script>

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

</body>
</html>
';
	}
}

?>