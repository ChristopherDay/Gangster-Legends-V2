<?php


	if (!class_exists("mainTemplate")) {
		class mainTemplate {
	    
	        public $pageMain =  '<!DOCTYPE html>
	<html>
		<head>
			<link href="themes/default/css/bootstrap.min.css" rel="stylesheet" />
			<link href="themes/default/css/style.css" rel="stylesheet" />
			<link rel="shortcut icon" href="themes/default/images/icon.png" />
			<title>{game_name} - {page}</title>
		</head>
		<body>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 header text-center">
						<img src="themes/default/images/logo.png" alt="Gangster Legends" />
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 sub-header hidden-xs">
						
						<div class="row">
							<div class="col-md-3 col-sm-3 sub-header">
								<strong>Money:</strong> {money}
							</div>
							<div class="col-md-3 col-sm-3 sub-header">
								<strong>Bullets:</strong> {bullets}
							</div>
							<div class="col-md-3 col-sm-3 sub-header">
								<strong>Rank:</strong> {rank} {#unless maxRank}{exp_perc}{/unless}
							</div>
							<div class="col-md-3 col-sm-3 sub-header">
								<strong>Family:</strong> {gang.name}
							</div>
						</div>
						
					</div>
					<div class="col-md-12 sub-header timers hidden-xs">
						<div class="row">
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{crime_timer}">
								<a href="?page=crimes">
									<span>Crime</span><span></span>
								</a>
							</div>
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{theft_timer}">
								<a href="?page=theft">
									<span>Theft</span><span></span>
								</a>
							</div>
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{chase_timer}">
								<a href="?page=policeChase">
									<span>Police Chase</span><span></span>
								</a>
							</div>
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{jail_timer}">
								<a href="?page=jail">
									<span>Jail</span><span></span>
								</a>
							</div>
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{bullet_timer}">
								<a href="?page=bullets">
									<span>Bullet Factory</span><span></span>
								</a>
							</div>
							<div class="col-md-2 col-sm-2" data-timer-type="name" data-timer="{travel_timer}">
								<a href="?page=travel">
									<span>Travel</span><span></span>
								</a>
							</div>
						</div>
						
					</div>
				</div>
				
				<div class="row padding">
					
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
								{game}
							</div>
						</div>
					</div>
				</div>
			</div>

			<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
			<!--<script src="themes/default/js/bootstrap.min.js"></script>-->
			<script src="themes/default/js/timer.js"></script>
			<script src="themes/default/js/mobile.js"></script>
		</body>
	</html>';
	        
	    }
	}
?>
