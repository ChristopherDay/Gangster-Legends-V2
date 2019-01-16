<?php

    class gangTemplate extends template {
        
        public $gangHome = '
        	<div class="row">
	        	<div class="col-md-7">
	        		<h4>Gang Events</h4>
	        	</div>
	        	<div class="col-md-5">
	        		<h4>Gang Members</h4>
					<ul class="list-group text-left">
						{#each members}
							<li class="list-group-item">
								{>userName}
								<span class="badge gang-rank">{gangRank}</span>
							</li>
						{/each}
					</ul>
	        	</div>
        	</div>
        ';

        public $gangOverview = '
        	<h3>{name}</h3>
        	{#if desc}
        		[{desc}]
        	{/if}
        	{#unless desc}
        		<em>
        			This gang has no description
        		</em>
        	{/unless}
        	<h4>Members</h4>
        	{#each members}
	        	<div class="crime-holder"> 
	        		<p>
	        			<span class="action">
	        				{>userName}
	        			</span>
	        			<span class="cooldown">
	        				{gangRank}
	        			</span>
	        		</p>
	        	</div>
        	{/each}
        ';

        public $gangs = '

			{#unless locations}
				<div class="text-center">
					<em>There are no gangs to join</em>
				</div>
			{/unless}        	

	        {#each locations}
	        	{#if gangID}
		        	<div class="crime-holder"> 
		        		<div class="crime-header">
			        		{locationName}
			        	</div>
		        		<p>
		        			<span class="action">
		        				{gangName}
		        			</span>
		        			<span class="cooldown">
			        			{>userName}
			        		</span>
		        			<span class="commit">
		        				<a href="?page=gang&action=view&id={gangID}">
		        					view
		        				</a>
		        			</span> 
		        		</p>
		                <div class="crime-perc gang-members" data-members="GANG MEMBERS: {members} / {maxMembers}">
		                    <div class="perc" style="width:{percent}%;"></div>
		                </div>
		        	</div>
		        {/if}
	        {/each}

	        <div class="clearfix"></div>

	        {#unless user.gang}

		        {#if availableLocations}

			        <h4>Start a new gang</h4>
			        <p>
			        	You will need to bribe a government official ${number_format gangCost} to start a gang in their country!
			        </p>
			        <form action="?page=gang&action=new" method="post">

			        	<input type="text" name="name" class="form-control form-control-inline" placeholder="Gang Name" />

			        	<select name="location" class="form-control form-control-inline">
			        		{#each availableLocations}
			        			<option value="{locationID}">{locationName}</option>
			        		{/each}
			        	</select>

			        	<button class="btn">
			        		Submit
			        	</button>

			        </form>

		        {/if}

	        {/unless}

        ';
        
    }

?>