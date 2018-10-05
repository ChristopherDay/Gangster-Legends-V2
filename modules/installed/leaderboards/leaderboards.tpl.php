<?php

    class leaderboardsTemplate extends template {
		
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
							<td>{>userName}</td>
						</tr>
					{/each}
				</tbody>
			</table>';
        
    }

?>