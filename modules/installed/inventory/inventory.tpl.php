<?php

	function _qty($qty) {
		if ($qty > 1) return number_format($qty); 
		return "";
	}

    class inventoryTemplate extends template {

		public $inventory = '
			<div class="row">
				<div class="col-md-7">
					<div class="panel panel-default">
						<div class="panel-heading">
							Inventory
						</div>
						<div class="panel-body">
							{#unless inventory}
								<p class="text-center">
									<em>You don\'t have any items!</em>
								</p>
							{/unless}
							{#each inventory}
								{>inventoryItem}
							{/each}
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="panel panel-default">
						<div class="panel-heading">
							Equiped
						</div>
						<div class="panel-body">
							{#each equipSlots}
								{>equipSlot}
							{/each}
						</div>
					</div>
				</div>
			</div>
		';

		public $equipSlot = '
			<strong>{name}</strong>
			{>equipedItem}
		';

		public $equipedItem = '
    		<div class="crime-holder inventory-item">
                <p data-toggle="dropdown">
                    <span class="action">
                    	{item.name}
                		{#unless item}
                			Not equiped
                		{/unless}
                    </span> 
            		{#unless item}
                    	<span class="cooldown">&nbsp;</span>
            		{/unless}
                    {#if item}
	                    <a href="#" class="commit">
	                    	Remove
	                    </a>
                    {/if}
                </p>
            </div>
		';

		public $inventoryItem = '
            <div class="crime-holder inventory-item">
                <p data-toggle="dropdown">
                    <span class="action">
                    	{name}
                    </span> 
                    <span class="cooldown">
                    	{_qty qty}
                    </span> 
                    <a href="#" class="commit show-options">
                    	Actions
                    </a>
                </p>
				<ul class="list-group inventory-actions">
					{#each links}
						<li class="list-group-item">
							<a href="{link}">
								{name}
							</a>
						</li>
					{/each}
				</ul>
            </div>
		';

        public $itemList = '
            <table class="table table-condensed table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th width="70px">Damage</th>
                        <th width="120px">Type</th>
                        <th width="170px">Cost</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each items}
                        <tr>
                            <td>{name}</td>
                            <td>{damage}</td>
                            <td>{typeDesc}</td>
                            <td>${cost} + {points} {_setting "pointsName"}</td>
                            <td>
                                [<a href="?page=admin&module=inventory&action=edit&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=inventory&action=delete&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $itemDelete = '
            <form method="post" action="?page=admin&module=inventory&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this item?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this item</button>
                </div>
            </form>
        
        ';
        public $calculator = '

            <h2>Using a {weapon} (x{damage})</h2>

            <div style="width: 100%; overflow-x: auto">
                <table class="table table-striped no-dt table-bordered table-condensed text-center">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">
                                Rank
                            </th>
                            <th class="text-center" colspan="{colCount}">
                                Bullets needed to kill
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Base Health</th>
                            {#each cols}
                                <th class="text-center">{name} (x{damage})</th>
                            {/each}
                        </tr>
                    </thead>
                    <tbody>
                        {#each rows}
                            <tr>
                                {#each cols}
                                    <td>
                                        {#if header}<strong>{/if}
                                        {data}
                                        {#if header}</strong>{/if}
                                    </td>
                                {/each}
                            </tr>
                        {/each}
                </table>
            </div>
        ';
        public $weaponSelect = '
            <form method="post" action="?page=admin&module=inventory&action=calculator">
                <div class="form-group">
                    <label class="pull-left">What weapon is the user shooting with</label>
                    <select class="form-control" name="weapon">
                        <option disabled selected value="0">Select a weapon</option>
                        {#each weapons}
                            <option value="{id}">{name}</option>
                        {/each}
                    </select>
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">View</button>
                </div>
            </form>
        ';
        
        public $itemForm = '
            <form method="post" action="?page=admin&module=inventory&action={editType}&id={id}">
                <div class="form-group">
                    <label class="pull-left">Item Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Cost to buy item ($)</label>
                            <input type="number" class="form-control" name="cost" value="{cost}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Cost to buy item ({_setting "pointsName"})</label>
                            <input type="number" class="form-control" name="points" value="{points}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Damage per bullet</label>
                            <input type="text" class="form-control" name="damage" value="{damage}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="pull-left">Minimum rank to buy weapon</label>
                            <input type="number" class="form-control" name="rank" value="{rank}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="pull-left">Type of item (1 = weapon, 2 = armor)</label>
                            <select class="form-control" name="type" data-value="{type}">
                            	{#each itemTypes}
                            		<option value="{id}">{name}</option>
                            	{/each}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';

	}