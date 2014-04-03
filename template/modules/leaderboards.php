<?php

    class leaderboardsTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $blankElement = '{var1} {var2} {var3}';
		
		public $leaderboard = "<h3>{var1}</h3><table class=\"table\"><thead><tr><th width=\"100px\">Rank</th><th>User</th></tr></thead><tbody>{var2}</tbody></table>";
		
		public $leaderboardRow = "<tr><td>{var1}</td><td><a href=\"?page=profile&view={var3}\">{var2}</a></td></tr>";
		
		public $buttons = '
			<a href="?page=leaderboards&top10=money" class="btn btn-default">Richest</a>
			<a href="?page=leaderboards&top10=rank" class="btn btn-default">Rank</a>
		';
        
    }

?>