<?php

    class leaderboardsTemplate extends template {
		
		public $leaderboard = '
			<div class="text-center">
				<a href="?page=leaderboards&top10=money" class="btn btn-default">Richest</a>
				<a href="?page=leaderboards&top10=rank" class="btn btn-default">Rank</a>
			</div>
			<div class="panel panel-default">
                <div class="panel-heading">{title}</div>
                <div class="panel-body">
					<table class="table table-striped table-bordered table-condensed">
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
					</table>
                </div>
            </div>
			';
        
    }

?>