<?php

    class profileTemplate extends template {
        
        public $online = "<strong class=\"text-success\">Online</strong>";
        public $offline = "<strong class=\"text-danger\">Offline</strong>";
        public $AFK = "<strong class=\"text-warning\">AFK</strong>";

        public $userSearch = '

            <div class="panel panel-default">
                <div class="panel-heading">Find User</div>
                <div class="panel-body">
                    <form method="post" action="#">
                        <input type="text" name="user" class="form-control form-control-inline" placeholder="Username ..." />
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Results</div>
                <div class="panel-body">
                    {#unless results}
                        <em> No users found </em>
                    {/unless}
                    {#each results}
                        <div class="crime-holder"> 
                            <p> 
                                <span class="action"> 
                                    {>userName}
                                </span> 
                                <span class="cooldown"> {status} </span> 
                            </p> 
                        </div>
                    {/each}
                </div>
            </div>
        ';

        public $editPassword = '


            <div class="panel panel-default">
                <div class="panel-heading">Edit Password</div>
                <div class="panel-body">

                    <ul class="nav nav-tabs nav-justified">
                        <li><a href="?page=profile&action=edit">Profile</a></li>
                        <li class="active"><a href="?page=profile&action=password">Change Password</a></li>
                    </ul>

                    <form action="#" method="post">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <strong>Old Password</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="password" name="old" class="form-control" value="" placeholder="******" />
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <strong>New Password</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="password" name="new" class="form-control" value="" placeholder="******" />
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <strong>Confirm Password</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="password" name="confirm" class="form-control" value="" placeholder="******" />
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" name="submit" value="true" class="btn btn-default">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            ';

        public $editProfile = '

            <div class="panel panel-default">
                <div class="panel-heading">Edit Profile</div>
                <div class="panel-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active"><a href="?page=profile&action=edit">Profile</a></li>
                        <li><a href="?page=profile&action=password">Change Password</a></li>
                    </ul>
                    <form action="#" method="post">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <strong>Picture</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="pic" class="form-control" value="{picture}" placeholder="e.g. http://www.someurl.com/picture.png" />
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <strong>Bio</strong>
                            </div>
                            <div class="col-md-9">
                                <textarea rows="15" name="bio" class="form-control" placeholder="A little bio about yourself ...">{bio}</textarea>
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" name="submit" value="true" class="btn btn-default">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            ';
        
        public $profile = '

            <div class="panel panel-default user-profile">
                <div class="panel-heading">Links</div>
                <div class="panel-body">
                    <ul class="nav nav-pills">
                        {#each profileLinks}
                            {#if url}<a class="btn btn-xs btn-default" href="{url}">{text}</a> &nbsp;{/if}
                        {/each}
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-lg-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">{user.name}</div>
                            <div class="panel-body">

                            <ul class="list-group text-left profile-user-stats">
                                <li class="list-group-item">
                                    <strong>Username</strong>
                                    <span class="pull-right">
                                        {>userName}
                                        <sup><{status}></sup>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Last Active</strong>
                                    <span class="pull-right">{_ago laston} ago</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Status</strong>
                                    <span class="pull-right">
                                        {#if dead}
                                            <strong style="color: #900;">DEAD</strong> <{killedBy}>
                                        {/if}
                                        {#unless dead}
                                            <strong style="color: #090;">Alive</strong>
                                        {/unless}
                                    </span>
                                </li>
                                {#if showRole}
                                    <li class="list-group-item">
                                        <strong>Role</strong>
                                        <span class="pull-right">
                                            {role}
                                        </span>
                                    </li>
                                {/if}
                                <li class="list-group-item">
                                    <strong>Rank</strong>
                                    <span class="pull-right">
                                        {rank}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Wealth</strong>
                                    <span class="pull-right">
                                        {moneyRank}
                                    </span>
                                </li>
                                {#each profileStats}
                                    <li class="list-group-item">
                                        <strong>{text}</strong>
                                        <span class="pull-right">
                                            <{stat}>
                                        </span>
                                    </li>
                                {/each}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Profile Picture</div>

                        <div class="panel-body profile-pic">
                            <img src="{picture}" style="max-height: 250px" class="img-responsive" alt="{user.name}\'s Profile" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Bio</div>
                <div class="panel-body">
                    {#if bio}
                        [{bio}]
                    {/if}
                    {#unless bio}
                        <em><small>The user has not set up their bio yet!</small></em>
                    {/unless}
                </div>
            </div>
        ';
        
    }


