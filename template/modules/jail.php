<?php

    class jailTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison

        public $jailUsers = '
        	<h4>{location} Inmates</h4>

        	{#unless users}
        		<div class="text-center">
        			<em>
	        			There is no one in jail
	        		</em>
        		</div>
        	{/unless}

        	{#each users}
	        <div class="crime-holder">
	            <p>
	            	<span data-timer="{time}" data-timer-type="inline"></span>  
	            	{name}
	            	<span class="commit">
	            		{#if percent}
	            			<a href="?page=jail&action=breakout&id={id}">
	            				{#if currentUser}
	            					Escape
	            				{/if}
	            				{#unless currentUser}
	            					Break Out
	            				{/unless}
	            			</a>
	            		{/if}
	            		{#unless percent}
	            			<span class="timer-active">In Super Max</span>
	            		{/unless}
	            	</span>
	            </p>
	            <div class="crime-perc">
	                <div class="perc" style="width:{percent}%;"></div>
	            </div>
	        </div>
	        {/each}
        ';
        
    }

?>