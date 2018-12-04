<?php

    class detectivesTemplate extends template {
        
        public $detectives = '
        	<div class="row">
        		<div class="col-md-5">
        			<h4>Hire Detectives</h4>
        			<p>
        				Before killing a user you first need to find them! It will cost you ${number_format detectiveCost} p/h to hire a detective.
        			</p>
        			<form class="text-left detective-form" action="?page=detectives&action=hire" method="post" >
	        			<input type="hidden" name="cost" value="{detectiveCost}" />
        				<p>
	        				<strong>User to find</strong>
	        				<input type="text" class="form-control" name="user" value="{user}" />
	        			</p>
			        	<div class="row">
			        		<div class="col-md-5">
        						<p>
	        						<strong>For how long</strong>
			        				<select onChange="updateDetectiveCost()" class="form-control" name="hours">
			        					<option value="1">1 Hour</option>
			        					<option value="2">2 Hours</option>
			        					<option value="3">3 Hours</option>
			        					<option value="4">4 Hours</option>
			        					<option value="5">5 Hours</option>
			        				</select>
			        			</p>
			        		</div>
			        		<div class="col-md-7">
			        			<p>
	        						<strong>How many Detectives?</strong>
			        				<select onChange="updateDetectiveCost()" class="form-control" name="detectives">
			        					<option value="1">1 Detective</option>
			        					<option value="2">2 Detectives</option>
			        					<option value="3">3 Detectives</option>
			        					<option value="4">4 Detectives</option>
			        					<option value="5">5 Detectives</option>
			        				</select>
			        			</p>
			        		</div>
			        	</div>
			        	<p class="text-center">
			        		<button class="btn btn-primary" name="submit" value="1">Hire Detectives</button>
			        	</p>
        			</form>
        		</div>
        		<div class="col-md-7">
        			<h4>Your Detectives</h4>

        			<table class="table table-bordered table-condensed table-striped">
        				<thead>
        					<tr>
        						<th>User</th>
        						<th width="200px">Time Remaining/Location</th>
        						<th width="90px">Action</th>
        					</tr>
        				</thead>
        				<tbody>
		        			{#unless hiredDetectives}
		        				<tr>
			        				<td colspan="3" class="text-center">
			        					<em>You have not hired any detectives</em>
			        				</td>
		        				</tr>
		        			{/unless}
		        			{#each hiredDetectives}
		        				<tr>
			        				<td>
			        					{>userName}
			        				</td>
			        				<td>
			        					{#if isSearching}
			        						<span data-reload-when-done data-timer-type="inline" data-timer="{end}"></span>
			        					{/if}
			        					{#unless isSearching}
			        						{#if isExpired}
			        							<strong>EXPIRED</strong>
			        						{/if}
			        						{#unless isExpired}
			        							{#if success}
			        								{location}
			        							{/if}
			        							{#unless success}
			        								<strong>NOT FOUND</strong>
			        							{/unless}
			        						{/unless}
			        					{/unless}
			        				</td>
			        				<td>
			        					<a href="?page=detectives&action=remove&id={id}">Remove</a>
			        				</td>
		        				</tr>
		        			{/each}
        				</tbody>
        			</table>
        		</div>
        	</div>
        ';

         public $detectiveOptions = '

            <form method="post" action="?page=admin&module=detectives&action=options">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Cost of detective per hour</label>
                            <input type="text" class="form-control" name="detectiveCost" value="{detectiveCost}" />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>

            </form>
        ';
        
    }

?>