<?php

    class forumTemplate extends template {
        
        public $blankElement = '';

        public $forumsList = '
            <table class="table table-condensed table-striped table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Forum</th>
                        <th width="100px">Order</th>
                        <th width="100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {#each forums}
                        <tr>
                            <td>{name}</td>
                            <td>{sort}</td>
                            <td>
                                [<a href="?page=admin&module=forum&action=editForum&id={id}">Edit</a>] 
                                [<a href="?page=admin&module=forum&action=deleteForum&id={id}">Delete</a>]
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';

        public $forumsDelete = '
            <form method="post" action="?page=admin&module=forum&action=deleteForum&id={id}&commit=1">
                <div class="text-center">
                    <p> Are you sure you want to delete this forum?</p>

                    <p><em>"{name}"</em></p>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">Yes delete this forum</button>
                </div>
            </form>
        
        ';
        public $forumForm = '
            <form method="post" action="?page=admin&module=forum&action={editType}Forum&id={id}">
                <div class="form-group">
                    <label class="pull-left">Forum Name</label>
                    <input type="text" class="form-control" name="name" value="{name}">
                </div>
                <div class="form-group">
                    <label class="pull-left">Forum Order</label>
                    <input type="text" class="form-control" name="sort" value="{sort}">
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';

        public $topics = '
            <h4 class="text-left">
                {forumInfo.name}
                <small class="pull-right">
                    <a href="?page=forum&action=new&id={forum}">
                        New Topic
                    </a>
                </small>
            </h4>

            {#unless topics}
                <p class="text-center">
                    <em>This forum has no topics.</em>
                </p>
            {/unless}

            {#if topics}
                <table class="table table-bordered table-striped forum-topics">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th width="150px">User</th>
                            <th width="230px">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        {#each topics}
                            <tr>
                                <td>
                                    {#if locked}
                                        <i class="glyphicon glyphicon-lock"></i>
                                    {/if}
                                    <a href="?page=forum&action=topic&id={id}">
                                        {type} {subject}
                                    </a>
                                </td>
                                <td>
                                    {>userName}
                                </td>
                                <td>
                                    {date}
                                </td>
                        {/each}
                    </tbody>
                </table>
            {/if}

        ';

        public $newTopic = '
            <form method="post" action="?page=forum&action=new&id={forum}">
                <div class="form-group">
                    <label class="pull-left">Subject</label>
                    <input type="text" class="form-control" name="subject" value="{subject}" />
                </div>
                <div class="form-group">
                    <label class="pull-left">Forum Topic</label>
                    <textarea class="form-control" name="body" rows="10">{body}</textarea>
                    <div class="text-right">
                        <small>[BBCode] enabled</small>
                    </div>
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">
                        Post Topic
                    </button>
                </div>
            </form>
        ';

        public $quote = '
            {#if quote}[quote={quote.user.name}]
                {quote.body}
            [/quote]{/if}
        ';

        public $topic = '
            <h4 class="text-left">
                {subject}
                <small class="pull-right">
                    <a href="?page=forum&action=forum&id={forum.id}">
                        {forum.name}
                    </a>
                </small>
            </h4>
            {#each posts}
                <div class="well well-sm">
                    <h5 class="text-left forum-header">
                        {>userName}
                        <small class="pull-right">
                            {date}
                            <div class="dropdown-toggle-hover">
                                <a href="#">
                                    <i class="glyphicon glyphicon-cog"></i>
                                </a>
                                <div class="dropdown">
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li>
                                            <a href="?page=forum&action=topic&id={topic}&quote={id}#reply">
                                                <i class="glyphicon glyphicon-comment"></i> Quote
                                            </a>
                                        </li>
                                        {#if canEdit}
                                            <li>
                                                <a href="?page=forum&action=edit&id={id}">
                                                    <i class="glyphicon glyphicon-pencil"></i> Edit
                                                </a>
                                            </li>
                                        {/if}
                                        {#if isAdmin}
                                            <li role="separator" class="divider"></li>
                                            {#if firstPost}
                                                <li>
                                                    <a href="?page=forum&action=type&id={topic}&type=2">
                                                        <i class="glyphicon glyphicon-flag"></i> Mark as Important
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?page=forum&action=type&id={topic}&type=1">
                                                        <i class="glyphicon glyphicon-bookmark"></i> Mark as Sticky
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?page=forum&action=type&id={topic}&type=0">
                                                        <i class="glyphicon glyphicon-book"></i> Mark as Normal
                                                    </a>
                                                </li>
                                                <li role="separator" class="divider"></li>
                                                <li>
                                                    <a href="?page=forum&action=deleteTopic&id={topic}">
                                                        <i class="glyphicon glyphicon-trash"></i> Delete Topic
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="?page=forum&action=lock&id={topic}">
                                                        <i class="glyphicon glyphicon-lock"></i> Toggle Lock
                                                    </a>
                                                </li>
                                            {/if}
                                            {#unless firstPost}
                                                <li>
                                                    <a href="?page=forum&action=delete&id={id}">
                                                        <i class="glyphicon glyphicon-trash"></i> Delete
                                                    </a>
                                                </li>
                                            {/unless}
                                            <li>
                                                <a href="?page=forum&action=mute&id={user.id}">
                                                    <i class="glyphicon glyphicon-volume-off"></i> Mute User
                                                </a>
                                            </li>
                                        {/if}
                                    </ul>
                                </div>
                            </div>
                        </small>
                    </h5>
                    [{body}]
                </div>
            {/each}

            {#if locked}
                <div class="text-center">
                    <em>
                        <i class="glyphicon glyphicon-lock"></i> This topic has been locked!
                    </em>
                </div>
            {/if}

            {#unless locked}
                <form method="post" action="?page=forum&action=topic&id={topic}">
                    <div class="form-group">
                        <a name="reply"></a> 
                        <label class="pull-left">Reply to {subject}</label>
                        <textarea class="form-control" name="body" rows="5">{>quote}</textarea>
                        <div class="text-right">
                            <small>[BBCode] enabled</small>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <button class="btn btn-default" name="submit" type="submit" value="1">
                            Reply
                        </button>
                    </div>
                </form>
            {/unless}
        ';
        public $delete = '
            <form method="post" action="?page=forum&action=delete&id={id}">
                <div class="text-center">
                    <p> Are you sure you want to delete this forum post?</p>

                    <div class="well well-sm">
                        <h5 class="text-left forum-header">
                            {>userName}
                            <small class="pull-right">
                                {date}
                            </small>
                        </h5>
                        [{body}]
                    </div>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">
                        Yes delete this post
                    </button>
                </div>
            </form>        
        ';
        public $deleteTopic = '
            <form method="post" action="?page=forum&action=deleteTopic&id={topic}">
                <div class="text-center">
                    <p> Are you sure you want to delete this forum topic?</p>

                    <div class="well well-sm">
                        <h5 class="text-left forum-header">
                            {>userName}
                            <small class="pull-right">
                                {date}
                            </small>
                        </h5>
                        [{body}]
                    </div>

                    <button class="btn btn-danger" name="submit" type="submit" value="1">
                        Yes delete this topic
                    </button>
                </div>
            </form>        
        ';

        public $edit = '
            <h4 class="text-left">
                Editing Post
            </h4>

            <form method="post" action="?page=forum&action=edit&id={id}">
                <div class="form-group">
                    <a name="reply"></a> 
                    <label class="pull-left">Message Body</label>
                    <textarea class="form-control" name="body" rows="5">{body}</textarea>
                    <div class="text-right">
                        <small>[BBCode] enabled</small>
                    </div>
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">
                        Edit
                    </button>
                </div>
            </form>
        ';

        public $mute = '
            <h4 class="text-left">
                Mute {>userName}
            </h4>

            <form method="post" action="?page=forum&action=mute&id={user.id}">
                <div class="form-group">
                    <label class="pull-left">For how long?</label>
                    <select class="form-control" name="time">
                        <option value="0">Unmute</option>
                        <option value="3600">1 Hour</option>
                        <option value="21600">6 Hours</option>
                        <option value="86400">1 Day</option>
                        <option value="172800">2 Days</option>
                        <option value="604800">1 Week</option>
                        <option value="1209600">2 Weeks</option>
                        <option value="2419200">1 Month</option>
                        <option value="4838400">2 Months</option>
                        <option value="14515200">6 Months</option>
                        <option value="29030400">1 Year</option>
                        <option value="2000000000">Forever</option>
                    </select>
                </div>
                
                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">
                        Mute
                    </button>
                </div>
            </form>
        ';
        
    }

?>