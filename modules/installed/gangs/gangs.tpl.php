<?php

    class gangsTemplate extends template {
        
        public $gangInvite = '
            <p>
                <a href="?page=profile&view={user}">{user}</a> has invited you to join <a href="?page=gangs&action=view&id={id}">{gang}</a>
                <a class="btn btn-default pull-right" href="?page=gangs">View Invites</a>
            </p>
        ';
        
        public $leaveSure = '
            <div class="panel panel-default gang-home">
                <div class="panel-heading text-center">
                    Are you sure?
                </div>
                <div class="panel-body">
                    <p>
                        Are you sure you want to leave this gang?
                    </p>

                    <a href="?page=gangs&action=leave&type=do" class="btn btn-danger">
                        Yes, Leave!
                    </a>
                    <a href="?page=gangs&action=home" class="btn btn-default">
                        No I want to stay!
                    </a>
                </div>
            </div>
        ';

        public $logs = '
            <div class="panel panel-default gang-home">
                <div class="panel-heading text-center">
                {_setting "gangName"} Logs
                </div>
                <div class="panel-body text-left">
                    <div class="row">
                        <div class="col-md-4 text-left">
                            <p>
                                <a href="?page=gangs&action=logs&time={prev.time}">&laquo;{prev.date}</a>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p>
                                <a href="?page=gangs&action=logs&time={today}">Today</a>
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <p>
                                <a href="?page=gangs&action=logs&time={next.time}">{next.date} &raquo;</a>
                            </p>
                        </div>
                    </div>


                    {#unless logs}
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="text-center">
                                    <em>There are no logs</em>
                                </div>
                            </div>
                        </div>
                    {/unless}
                    {#if logs}
                        <div class="list-group text-left">
                            {#each logs}
                                <div class="list-group-item">
                                    {>userName} {log} <small class="pull-right">{date}</small>
                                </div>
                            {/each}
                        </div>
                    {/if}

                </div>
            </div>
        ';

        public $gangPermission = '

            <div class="panel panel-default gang-home">
                <div class="panel-heading">Edit {user.name} permissions</div>
                <div class="text-left">
                    <div class="list-group">
                        <form method="POST" action="?page=gangs&action=manage">
                            <input type="hidden" name="user" value="{user}" />
                            {#each roles}
                                <div href="#" class="list-group-item active">
                                    <h4 class="list-group-item-heading">
                                        {name}
                                        {#if allowed}
                                            <button name="name" value="{key}" class="btn btn-danger pull-right">
                                                Remove Access
                                            </button>
                                        {/if}
                                        {#unless allowed}
                                            <button name="name" value="{key}" class="btn btn-success pull-right">
                                                Grant Access
                                            </button>
                                        {/unless}
                                    </h4>
                                    <p class="list-group-item-text">{description}</p>
                                </div>
                            {/each}
                        </form>
                    </div>
                </div>
            </div>
        ';

        public $editInfo = '
            <form action="?page=gangs&action=editInfo" method="post">

                <div class="panel panel-default gang-home">
                    <div class="panel-heading">Edit {_setting "gangName"} Information</div>
                    <div class="panel-body">
                        <textarea name="text" class="form-control" rows="15">{info}</textarea>
                        <p class="text-right">
                            <small class="pull-left">[BBCode] Supported</small>
                            <button class="btn btn-default">
                                Update
                            </button>
                        </p>
                    </div>
                </div>

            </form>
        ';

        public $editProfile = '
            <form action="?page=gangs&action=editProfile" method="post">

                <div class="panel panel-default gang-home">
                    <div class="panel-heading">Edit {_setting "gangName"} Profile</div>
                    <div class="panel-body">
                        <textarea name="text" class="form-control" rows="15">{desc}</textarea>
                        <p class="text-right">
                            <small class="pull-left">[BBCode] Supported</small>
                            <button class="btn btn-default">
                                Update
                            </button>
                        </p>
                    </div>
                </div>

            </form>
        ';

        public $gangHome = '
            <div class="row">
                <div class="col-md-7">
                    <div class="panel panel-default gang-home">
                        <div class="panel-heading">{_setting "gangName"} Information</div>
                        <div class="panel-body">
                            [{info}]
                        </div>
                    </div>
                    <div class="text-center">
                        {#if editInfo}
                            <a href="?page=gangs&action=editInfo" class="btn btn-default">
                                Edit {_setting "gangName"} Information
                            </a>
                        {/if}
                        {#if editProfile}
                            <a href="?page=gangs&action=editProfile" class="btn btn-default">
                                Edit {_setting "gangName"} Profile
                            </a>
                        {/if}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-default gang-home">
                        <div class="panel-heading">Members</div>
                        <ul class="list-group text-left">
                            {#each members}
                                <li class="list-group-item">
                                    {>userName}
                                    <span class="badge gang-rank">{gangRank}</span>
                                </li>
                            {/each}
                        </ul>
                        {#if canInvite}
                            <div class="panel-body">

                                <form action="?page=gangs&action=invite" method="post">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="text" name="name" class="form-control" placeholder="Username" />
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="btn btn-default btn-block">
                                                Invite
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        {/if}
                    </div>


                    {#if canEditUsers}

                        <div class="panel panel-default gang-home">
                            <div class="panel-heading">Edit Members</div>
                            <div class="panel-body">

                                <form action="?page=gangs" method="post">
                                    <p>
                                        <select name="user" class="form-control">
                                            {#each members}
                                                {#if canBeKicked}
                                                    <option value="{user.id}">{user.name}</option>
                                                {/if}
                                            {/each}
                                        </select>
                                    </p>
                                    {#if editPermissions}
                                        <p>
                                            <button name="action" value="manage" class="btn btn-block btn-default">
                                                Edit {_setting "gangName"} Permissions
                                            </button>
                                        </p>
                                    {/if}
                                    {#if canKick}
                                        <p>
                                            <button name="action" value="kick" class="btn btn-block btn-default">
                                                Kick
                                            </button>
                                        </p>
                                    {/if}
                                    {#if isBoss}
                                        <p>
                                            <button name="action" value="setUnderboss" class="btn btn-block btn-default">
                                                Set Underboss
                                            </button>
                                        </p>
                                    {/if}
                                </form>

                            </div>
                        </div>

                    {/if}

                    {#if canUpgrade}
                        <div class="panel panel-default gang-home">
                            <div class="panel-heading">Upgrade Capacity</div>
                            <div class="panel-body">
                                <p>
                                    Upgrade capacity to {nextCapacity} members for {#money upgradeCost}!
                                </p>
                                <p>
                                    <a href="?page=gangs&action=upgrade" class="btn btn-success">
                                        Upgrade {_setting "gangName"}
                                    </a>
                                </p>

                            </div>
                        </div>
                    {/if}

                    {#unless isBoss}
                        <div class="panel panel-default gang-home">
                            <div class="panel-heading">
                                Leave
                            </div>
                            <div class="panel-body">
                                <a class="btn btn-block btn-danger" href="?page=gangs&action=leave">
                                    Leave Gang
                                </a>
                            </div>
                        </div>
                    {/unless}

                    {#if canDisband}
                        <div class="panel panel-default gang-home">
                            <div class="panel-heading">Disband</div>
                            <div class="panel-body">

                                <form action="?page=gangs&action=disband" method="post">
                                    <button class="btn btn-danger">
                                        Disband {_setting "gangName"}
                                    </button>

                                </form>

                            </div>
                        </div>
                    {/if}

                </div>
            </div>
            
        ';
        
        public $disband = '
            <div class="panel panel-default">
                <div class="panel-heading">Disband Crew</div>
                <div class="panel-body">
                    <p>
                        Are you sure you want to do this?
                    </p>
                    <p>
                        You and all of your crew members will become crewless!
                    </p>
                    <p>
                        Enter your password to disband your crew!<br />
                        <form method="POST" action="#">
                            <input class="form-control form-control-inline" type="password" name="password" />
                            <button class="btn btn-default">
                                Disband Crew!
                            </button>
                        </form>
                    </p>

                </div>
            </div>
        ';

        public $gangOverview = '

            <div class="panel panel-default gang-overview">
                <div class="panel-heading">{name}</div>
                <div class="panel-body">
                    {#if desc}
                        [{desc}]
                    {/if}
                    {#unless desc}
                        <em>
                            This gang has no description
                        </em>
                    {/unless}
                    <h4>Members</h4>
                    {#each members}
                        <div class="crime-holder"> 
                            <p>
                                <span class="action">
                                    {>userName}
                                </span>
                                <span class="cooldown">
                                    {gangRank}
                                </span>
                            </p>
                        </div>
                    {/each}
                </div>
            </div>
        ';

        public $gangs = '
            <div class="gang-list">
                {#if invites}
                    <div class="panel panel-default">
                        <div class="panel-heading">{_setting "gangName"} Invites</div>
                        <div class="panel-body">
                            <p>
                                You have been invited to the folowing gangs!
                            </p>
                            {#each invites}
                                <div class="crime-holder"> 
                                    <p>
                                        <span class="action">
                                            <a href="?page=gangs&action=view&id={gangID}">
                                                {name}
                                            </a>
                                        </span>

                                        <span class="commit btn btn-success">
                                            <a href="?page=gangs&action=manageInvite&id={id}&type=accept">
                                                Accept
                                            </a>
                                        </span> 
                                        <span class="commit btn btn-danger">
                                            <a href="?page=gangs&action=manageInvite&id={id}&type=decline">
                                                Decline
                                            </a>
                                        </span> 
                                    </p>
                                </div>
                            {/each}
                        </div>
                    </div>
                {/if}

                {#unless locations}
                <div class="panel panel-default">
                    <div class="panel-heading">{_setting "gangName"}s</div>
                    <div class="panel-body">
                        <div class="text-center">
                            <em>There are no gangs to join</em>
                        </div>
                    </div>
                </div>
                {/unless}            

                <div class="panel panel-default">
                    <div class="panel-heading">{_setting "gangName"}s</div>
                    <div class="panel-body">
                        {#each locations}
                            {#if gangID}
                                <div class="crime-holder"> 
                                    <div class="crime-header text-left">
                                        {locationName}
                                        <span class="pull-right">
                                            {members} / {maxMembers} Members
                                        </span>
                                    </div>
                                    <p>
                                        <span class="action">
                                            {gangName}
                                        </span>
                                        <span class="cooldown">
                                            {>userName}
                                        </span>
                                        <span class="commit">
                                            <a href="?page=gangs&action=view&id={gangID}">
                                                view
                                            </a>
                                        </span> 
                                    </p>
                                </div>
                            {/if}
                        {/each}
                    </div>
                </div>

                <div class="clearfix"></div>

                {#unless user.gang}

                    {#if availableLocations}

                        <div class="panel panel-default">
                            <div class="panel-heading">Start a new gang</div>
                            <div class="panel-body">
                                <p>
                                    You will need to bribe a government official {#money gangCost} to start a gang in their country!
                                </p>
                                <form action="?page=gangs&action=new" method="post">

                                    <input type="text" name="name" class="form-control form-control-inline" placeholder="{_setting "gangName"} Name" />

                                    <select name="location" class="form-control form-control-inline">
                                        {#each availableLocations}
                                            <option value="{locationID}">{locationName}</option>
                                        {/each}
                                    </select>

                                    <button class="btn btn-default">
                                        Submit
                                    </button>

                                </form>
                            </div>
                        </div>

                    {/if}

                {/unless}
            </div>
        ';
        
    }

