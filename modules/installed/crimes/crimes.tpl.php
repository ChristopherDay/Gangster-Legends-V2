<?php

    class crimesTemplate extends template {

        public $crimeHolder = '

        <div class="panel panel-default">
            <div class="panel-heading">Crimes</div>
            <div class="panel-body">
                {#each crimes}
                    <div class="crime-holder">
                        <p>
                            <span class="action">
                            {name} 
                            </span> 
                            <span class="cooldown">
                                ({cooldown})
                            </span> 
                            <span class="commit">
                                <a href="?page=crimes&action=commit&crime={id}&_CSFR={_CSFRToken}">
                                    Commit
                                </a>
                            </span>
                        </p>
                        <div class="crime-perc">
                            <div class="perc" style="width:{percent}%;"></div>
                        </div>
                    </div>
                {/each}
                {#unless crimes}
                    <div class="text-center"><em>There are no crimes</em></div>
                {/unless}
            </div>
        </div>
        ';

        public $crimeList = '
            <table class="table table-condensed table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Crime</th>
                        <th width="120px">Cooldown</th>
                        <th width="120px">Reward</th>
                        <th width="70px">Level</th>
                        <th width="70px">EXP</th>
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
                            <td>{exp}</td>
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

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Min. money for successful crime</label>
                            <input type="number" class="form-control" name="money" value="{money}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Max. monney for successful crime</label>
                            <input type="number" class="form-control" name="maxMoney" value="{maxMoney}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Min. bullets for successful crime</label>
                            <input type="number" class="form-control" name="bullets" value="{bullets}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="pull-left">Max. bullets for successful crime</label>
                            <input type="number" class="form-control" name="maxBullets" value="{maxBullets}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Cooldown (Seconds)</label>
                            <input type="number" class="form-control" name="cooldown" value="{cooldown}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">EXP Gained</label>
                            <input type="number" class="form-control" name="exp" value="{exp}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="pull-left">Min user level to comit this crime</label>
                            <input type="number" class="form-control" name="level" value="{level}">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

