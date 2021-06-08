<?php

    class bountiesTemplate extends template {
        
        public $bounties = '
            <div class="row">
                <div class="col-md-5">

                    <div class="panel panel-default">
                        <div class="panel-heading">New Bounty</div>
                        <div class="panel-body">
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
                                    <button class="btn btn-default" name="submit" value="1">Open Bounty</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">

                    <div class="panel panel-default">
                        <div class="panel-heading">Open Bounties</div>
                        <div class="panel-body">

                            <table class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th width="90px">Payout</th>
                                        <th width="65px" class="text-center">Action</th>
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
                                                    {#money cost}
                                                </td>
                                                <td class="bounty-header text-center">
                                                    <a href="#" onclick="toggleBounty(this, {uid})">View</a>
                                                </td>
                                            </tr>
                                            {#each bounties}
                                                <tr data-id="{userToKill}" style="display: none">
                                                    <td class="bounty-content">
                                                        {#if uid}
                                                            {>userName}
                                                        {/if}
                                                        {#unless uid}
                                                            Anonymous
                                                        {/unless}
                                                    </td>
                                                    <td class="bounty-content">
                                                        {#money cost}
                                                    </td>
                                                    <td class="bounty-content text-center">
                                                        <a href="?page=bounties&action=remove&id={id}">Buy Off</a>
                                                    </td>
                                                </tr>
                                            {/each}
                                        {/each}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        ';

    }

