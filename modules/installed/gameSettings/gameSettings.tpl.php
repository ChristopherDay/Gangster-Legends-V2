<?php

    class gameSettingsTemplate extends template {

        public $rankHolder = '
        {#each ranks}
        <div class="rank-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=ranks&action=commit&rank={id}">Commit</a></span></p>
            <div class="rank-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless ranks}
            <div class="text-center"><em>There are no ranks</em></div>
        {/unless}';

        public $rankList = '
            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th width="100px">EXP Needed</th>
                        <th width="80px">Limit</th>
                        <th width="170px"> eward</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each ranks}
                        <tr>
                            <td>{name}</td>
                            <td>{exp} EXP</td>
                            <td>{#if limit} {limit} {/if} {#unless limit}none{/unless}</td>
                            <td>${cash} + {bullets} bullets</td>
                            <td>
                                [<a href="?page=admin&module=gameSettings&action=editRank&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=gameSettings&action=deleteRank&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $rankDelete = '
            <form method="post" action="?page=admin&module=gameSettings&action=deleteRank&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this rank?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this rank</button>
                </div>
            </form>
        
        ';
        public $rankForm = '
            <form method="post" action="?page=admin&module=gameSettings&action={editType}Rank&id={id}">
                <div class="form-group">
                    <label class="pull-left">Rank Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Limit how many users can be at this rank (if there us no limit put 0)</label>
                    <input type="number" class="form-control" name="limit" value="{limit}">
                </div>
                <div class="form-group">
                    <label class="pull-left">EXP needed to get this rank</label>
                    <input type="number" class="form-control" name="exp" value="{exp}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Money reward for reaching this rank</label>
                    <input type="number" class="form-control" name="cash" value="{cash}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Bullet reward for reaching this rank</label>
                    <input type="number" class="form-control" name="bullets" value="{bullets}">
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

?>