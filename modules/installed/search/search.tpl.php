<?php

    class searchTemplate extends template {

        public $userSearch = '

            <div class="panel panel-default">
                <div class="panel-heading">Find User</div>
                <div class="panel-body">
                    <form method="post" action="#">
                        <input type="text" name="user" class="form-control form-control-inline" placeholder="Username ..." />
                        <button class="btn btn-default">Search</button>
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
        
    }

?>