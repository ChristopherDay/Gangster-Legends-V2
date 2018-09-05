<?php

    class usersOnlineTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $usersOnline = '<div class="row">
        	{#each durations}
        		<div class="col-md-6">
        			<h4 class="text-left">{title}</h4>
        			<p class="text-left">
        				{#each users}
							<a href="?page=profile&view={id}">{name}</a>
        				{/each}
        			</p>
        		</div>
        	{/each}
        </div>';
        
    }

?>