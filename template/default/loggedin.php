<?php

    class mainTemplate {
    
        public $pageMain =  '<!DOCTYPE html>
<html>
	<head>
		<link href="template/default/css/bootstrap.min.css" rel="stylesheet" />
		<link href="template/default/css/style.css" rel="stylesheet" />
		<link rel="shortcut icon" href="template/default/images/icon.png" />
		<title>{game_name} - {page}</title>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 header text-center">
					<img src="template/default/images/logo.png" alt="Gangster Legends" />
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 sub-header hidden-xs">
					
					<div class="row">
						<div class="col-md-3 col-sm-3 sub-header"><strong>Money:</strong> {money}</div>
						<div class="col-md-3 col-sm-3 sub-header"><strong>Bullets:</strong> {bullets}</div>
						<div class="col-md-3 col-sm-3 sub-header"><strong>Rank:</strong> {rank} {exp_perc}</div>
						<div class="col-md-3 col-sm-3 sub-header"><strong>Family:</strong> {gang.name}</div>
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
					<div class="panel panel-danger">
						<div class="panel-heading">
							Actions
						</div>
						<div class="panel-body hidden-xs">
							<a href="?page=crimes">Crimes </a> <br />
							<a href="?page=theft">Theft</a><br />
							<a href="?page=policeChase">Police Chase</a><br />
							<!--Organised Crime<br />
							Murder-->
						</div>
					</div>
					<div class="panel panel-danger">
						<div class="panel-heading">
							{location}
						</div>
						<div class="panel-body hidden-xs">
							<a href="?page=bullets">Bullet Factory</a><br />
							<a href="?page=bank">Bank</a><br />
							<a href="?page=travel">Travel</a> <br />
							<a href="?page=garage">Garage</a> <br />
							<!--Search <br />
							Jail-->
						</div>
					</div>
					<!--<div class="panel panel-danger">
						<div class="panel-heading">
							Casinos
						</div>
						<div class="panel-body hidden-xs">
							Blackjack<br />
							Race Track<br />
							Lottery
						</div>
					</div>-->
					<div class="panel panel-danger">
						<div class="panel-heading">
							Account
						</div>
						<div class="panel-body hidden-xs">
                            {adminLink}
							<a href="?page=profile">My Profile</a><br />
							<a href="?page=usersOnline">Users Online</a><br />
							<a href="?page=leaderboards">Leaderboards</a><br />
							<a href="?page=logout">Logout</a>
						</div>
					</div>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Families
						</div>
						<div class="panel-body hidden-xs">
							{#if gang.id}
								<a href="?page=families">Other Families</a><br />	
								<a href="?page=family">Family</a><br />	
								<a href="?page=family_bank">Family Bank</a>	
								
							{/if}
							{#unless gang.id}
								<a href="?page=families">All Families</a><br />	
								<a href="?page=new_family">New Family</a>	
							{/unless}
						</div>
					</div>
				</div>
				
				<div class="col-md-9">
					<div class="panel panel-danger">
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
		<!--<script src="template/default/js/bootstrap.min.js"></script>-->
		<script src="template/default/js/timer.js"></script>
		<script src="template/default/js/mobile.js"></script>
	</body>
</html>';
        
    }

?>
