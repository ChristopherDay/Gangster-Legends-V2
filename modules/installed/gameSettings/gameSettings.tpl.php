<?php

    class gameSettingsTemplate extends template {

        public $rankList = '
            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th width="100px">EXP Needed</th>
                        <th width="80px">Max Health</th>
                        <th width="80px">Limit</th>
                        <th width="170px">Reward</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each ranks}
                        <tr>
                            <td>{name}</td>
                            <td>{exp}</td>
                            <td>{health}</td>
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
                <div class="form-group">
                    <label class="pull-left">Max Health</label>
                    <input type="number" class="form-control" name="health" value="{health}">
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';

        public $moneyRankList = '
            <table class="table table-condensed table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th width="100px">Money Needed</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each moneyRanks}
                        <tr>
                            <td>{name}</td>
                            <td>${money}</td>
                            <td>
                                [<a href="?page=admin&module=gameSettings&action=editMoneyRank&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=gameSettings&action=deleteMoneyRank&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $moneyRankDelete = '
            <form method="post" action="?page=admin&module=gameSettings&action=deleteMoneyRank&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this money rank?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this money rank</button>
                </div>
            </form>
        
        ';
        public $moneyRankForm = '
            <form method="post" action="?page=admin&module=gameSettings&action={editType}MoneyRank&id={id}">
                <div class="form-group">
                    <label class="pull-left">Rank Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Money needed to get this rank</label>
                    <input type="number" class="form-control" name="money" value="{money}">
                </div>
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
    }

