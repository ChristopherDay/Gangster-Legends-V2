<?php

    class usersOnlineTemplate extends template {
    
        public $usersOnline = '<div class="row">
        	{#each durations}
        		<div class="col-md-6">
        			<h4 class="text-left">{title}</h4>
        			<ul class="text-left">
        				{#each users}
							<li><a href="?page=profile&view={id}">{name}</a></li>
        				{/each}
        			</ul>
        		</div>
        	{/each}
        </div>';
        
    }

?>