<?php

    class travelTemplate extends template {
    
        public $locationHolder = '

            <div class="panel panel-default">
                <div class="panel-heading">Travel</div>
                <div class="panel-body">
                    {#each locations}
                    <div class="crime-holder">
                        <p>
                            <span class="action">
                                {location} 
                            </span>
                            <span class="cooldown">
                                ({cooldown})&nbsp;&nbsp;&nbsp;&nbsp;{#money cost} 
                            </span>
                            <span class="commit">
                                <a href="?page=travel&action=fly&location={id}">Travel</a>
                            </span>
                            </p>
                    </div>
                    {/each}
                </div>
            </div>
        ';

        public $locationsHolder = '
        {#each locations}
        <div class="crime-holder">
            <p>
                <span class="action">
                    {name} 
                </span>

        </div>
        {/each}';
        

        public $locationsList = '
            <table class="table table-condensed table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Locations</th>
                        <th width="120px">Cost ($)</th>
                        <th width="120px">Bullets</th>
                        <th width="120px">Cost per Bullet ($)</th>
                        <th width="120px">Cooldown (sec)</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each locations}
                        <tr>
                            <td>{name}</td>
                            <td>{#money cost}</td>
                            <td>{bullets}</td>
                            <td>${bulletCost}</td>
                            <td>{cooldown} seconds</td>
                            <td>
                                [<a href="?page=admin&module=travel&action=edit&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=travel&action=delete&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $locationsDelete = '
            <form method="post" action="?page=admin&module=travel&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this location?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this location</button>
                </div>
            </form>
        
        ';
        public $locationsForm = '
            <form method="post" action="?page=admin&module=travel&action={editType}&id={id}">
                <div class="form-group">
                    <label class="pull-left">Location Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Cost ($)</label>
                    <input type="number" class="form-control" name="cost" value="{cost}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Bullets</label>
                    <input type="number" class="form-control" name="bullets" value="{bullets}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Bullet Cost ($)</label>
                    <input type="number" class="form-control" name="bulletCost" value="{bulletCost}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Cooldown (sec)</label>
                    <input type="number" class="form-control" name="cooldown" value="{cooldown}">
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
        
    }
