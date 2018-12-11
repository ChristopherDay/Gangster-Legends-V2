<?php

	class usersOnlineTemplate extends template {
	
		public $usersOnline = '<div class="row">
			{#each durations}
				<div class="col-md-6">
					<h4 class="text-left">{title}</h4>
					{#each users}
						<div class="crime-holder">
							<p>
								<span class="action">
								    {>userName} 
								</span> 
							</p>
						</div>
					{/each}
				</div>
			{/each}
		</div>';
		
	}

?>