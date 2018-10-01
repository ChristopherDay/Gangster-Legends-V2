<?php


	if (!class_exists("mainTemplate")) {
		class mainTemplate {
	    
	        public $pageMain =  '<!DOCTYPE html>
	<html>
		<head>
			<link href="themes/default - Grey/css/bootstrap.min.css" rel="stylesheet" />
			<link href="themes/default - Grey/css/style.css" rel="stylesheet" />
			<link rel="shortcut icon" href="themes/default - Grey/images/icon.png" />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>{game_name} - {page}</title>
		</head>
		<body>
			<div class="header">
				<div class="container">
					<div class="row">
						<div class="col-md-4 logo-container text-left">
							<img src="themes/default - Grey/images/logo.png" alt="Gangster Legends" />
						</div>
						<div class="col-md-2 col-xs-6 text-center">
							<a href="?page=mail">
								Mail {#if mail}({mail}){/if}<br />
							</a>
							<a href="?page=notifications">
								Notifications {#if notifications} ({notifications}){/if}
							</a>
						</div>
						<div class="col-md-3 col-xs-6 text-center">
								<strong>Money:</strong> {money} <br />
								<strong>Bullets:</strong> {bullets}
						</div>
						<div class="col-md-3 col-xs-6 text-center">
							<strong>Rank:</strong> {rank} {#unless maxRank}{exp_perc}{/unless}<br />
							<strong>Family:</strong> {gang.name}
						</div>
					</div>
				</div>
			</div>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 sub-header">
						<div class="row">
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{crime_timer}">
								<a href="?page=crimes">
									<span>Crime</span><span></span>
								</a>
							</div>
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{theft_timer}">
								<a href="?page=theft">
									<span>Theft</span><span></span>
								</a>
							</div>
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{chase_timer}">
								<a href="?page=policeChase">
									<span>Police Chase</span><span></span>
								</a>
							</div>
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{jail_timer}">
								<a href="?page=jail">
									<span>Jail</span><span></span>
								</a>
							</div>
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{bullet_timer}">
								<a href="?page=bullets">
									<span>Bullet Factory</span><span></span>
								</a>
							</div>
							<div class="col-xs-6 col-sm-2" data-timer-type="name" data-timer="{travel_timer}">
								<a href="?page=travel">
									<span>Travel</span><span></span>
								</a>
							</div>
						</div>
						
					</div>
				</div>
				
				<div class="container">
					<div class="row">
						<div class="col-md-3 navigation text-center"> 

							{#each menus}
								<div class="panel panel-default">
									<div class="panel-heading">
										{title}
									</div>
									<div class="panel-body hidden-xs">
										{#each items}
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
							{/each}

						</div>
						
						<div class="col-md-9">
							<div class="panel panel-default">
								<div class="panel-heading">
									{page}
								</div>
								<div class="panel-body text-center">
									<{game}>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script src="themes/default - Grey/js/jquery.js"></script>
			<!--<script src="themes/default - Grey/js/bootstrap.min.js"></script>-->
			<script src="themes/default - Grey/js/timer.js"></script>
			<script src="themes/default - Grey/js/mobile.js"></script>
		</body>
	</html>';
	        
	    }
	}
?>
