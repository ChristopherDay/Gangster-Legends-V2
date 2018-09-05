<?php

    class leaderboardsTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
		
		public $leaderboard = '
			<a href="?page=leaderboards&top10=money" class="btn btn-default">Richest</a>
			<a href="?page=leaderboards&top10=rank" class="btn btn-default">Rank</a>
			<h3>{title}</h3>
			<table class="table">
				<thead>
					<tr>
						<th width="100px">Rank</th>
						<th>User</th>
					</tr>
				</thead>
				<tbody>
					{#each users}
						<tr>
							<td>{rank}</td>
							<td><a href="?page=profile&view={id}">{name}</a></td>
						</tr>
					{/each}
				</tbody>
			</table>';
        
    }

?>