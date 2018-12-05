<?php

    class bountiesTemplate extends template {
        
        public $bounties = '
        	<div class="row">
        		<div class="col-md-5">
        			<h4>New Bounty</h4>
        			<form class="text-left detective-form" action="?page=bounties&action=new" method="post" >
	        			<input type="hidden" name="cost" value="{detectiveCost}" />
        				<p>
	        				<strong>User to kill</strong>
	        				<input type="text" class="form-control" name="user" value="{user}" />
	        			</p>
        				<p>
	        				<strong>Reward</strong>
	        				<input type="text" class="form-control" name="cost" value="{cost}" />
	        			</p>
						<p>
	        				<input type="checkbox" name="anon" value="1" />
    						<strong>Stay Anonymous</strong>
	        			</p>
			        	<p class="text-center">
			        		<button class="btn btn-primary" name="submit" value="1">Open Bounty</button>
			        	</p>
        			</form>
        		</div>
        		<div class="col-md-7">
        			<h4>Open Bounties</h4>

        			<table class="table table-bordered table-condensed table-striped">
        				<thead>
        					<tr>
        						<th>User</th>
        						<th width="130px">Payout</th>
        						<th width="90px">Action</th>
        					</tr>
        				</thead>
        				<tbody>
		        			{#unless bounties}
		        				<tr>
			        				<td colspan="3" class="text-center">
			        					<em>There are no open bounties</em>
			        				</td>
		        				</tr>
		        			{/unless}
			        			{#each bounties}
			        				<tr>
				        				<td class="bounty-header">
				        					{>userName}
				        				</td>
				        				<td class="bounty-header">
				        					${number_format cost}
				        				</td>
				        				<td class="bounty-header">
				        				</td>
			        				</tr>
				        			{#each bounties}
				        				<tr>
					        				<td class="bounty-content">
					        					{#if uid}
					        						{>userName}
					        					{/if}
					        					{#unless uid}
					        						Anonymous
					        					{/unless}
					        				</td>
					        				<td class="bounty-content">
					        					${number_format cost}
					        				</td>
					        				<td class="bounty-content">
					        					<a href="?page=bounties&action=remove&id={id}">Buy Off</a>
					        				</td>
				        				</tr>
				        			{/each}
			        			{/each}
        				</tbody>
        			</table>
        		</div>
        	</div>
        ';

    }

?>