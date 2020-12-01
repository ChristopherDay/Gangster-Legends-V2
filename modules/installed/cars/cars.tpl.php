<?php

   class carsTemplate extends template {

        public $carsHolder = '
        {#each cars}
        <div class="crime-holder">
            <p>
                <span class="action">
                    {name} 
                </span>
       </div>
        {/each}';
        

        public $carsList = '
            <table class="table table-condensed table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Cars</th>
                        <th width="120px">Number of cars</th>
                        <th width="120px">Value ($)</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each cars}
                        <tr>
                            <td>{name}</td>
                            <td>{theftChance}</td>
                            <td>${value}</td>
                            <td>
                                [<a href="?page=admin&module=cars&action=edit&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=cars&action=delete&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $carsDelete = '
            <form method="post" action="?page=admin&module=cars&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this car?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this car</button>
                </div>
            </form>
        
        ';
        public $carsForm = '
            <form method="post" action="?page=admin&module=cars&action={editType}&id={id}">
                <div class="form-group">
                    <label class="pull-left">Car Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Number of cars available</label>
                    <input type="number" class="form-control" name="theftChance" value="{theftChance}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Value ($)</label>
                    <input type="number" class="form-control" name="value" value="{value}">
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

