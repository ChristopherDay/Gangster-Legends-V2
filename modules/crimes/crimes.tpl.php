<?php

    class crimesTemplate extends template {

        public $crimeHolder = '
        {#each crimes}
        <div class="crime-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=crimes&action=commit&crime={id}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless crimes}
            <div class="text-center"><em>There are no crimes</em></div>
        {/unless}';

        public $crimeList = '
            <table class="table table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Crime</th>
                        <th width="120px">Cooldown</th>
                        <th width="120px">Reward</th>
                        <th width="70px">Level</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each crimes}
                        <tr>
                            <td>{name}</td>
                            <td>{cooldown} seconds</td>
                            <td>${money} - ${maxMoney}</td>
                            <td>{level}</td>
                            <td>
                                [<a href="?page=admin&module=crimes&action=edit&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=crimes&action=delete&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $crimeDelete = '
            <form method="post" action="?page=admin&module=crimes&action=delete&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this crime?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this crime</button>
                </div>
            </form>
        
        ';
        public $crimeForm = '
            <form method="post" action="?page=admin&module=crimes&action={editType}&id={id}">
                <div class="form-group">
                    <label class="pull-left">Crime Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Cooldown timer (seconds)</label>
                    <input type="number" class="form-control" name="cooldown" value="{cooldown}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Minimum money for successful crime</label>
                    <input type="number" class="form-control" name="money" value="{money}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Maximum monney for successful crime</label>
                    <input type="number" class="form-control" name="maxMoney" value="{maxMoney}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Min user level to comit this crime</label>
                    <input type="number" class="form-control" name="level" value="{level}">
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

?>