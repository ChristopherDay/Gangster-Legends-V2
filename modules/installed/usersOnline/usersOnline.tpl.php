<?php

    class usersOnlineTemplate extends template {
    
        public $usersOnline = '
            <div class="row">
                {#each durations}
                    <div class="col-md-6">

                        <div class="panel panel-default">
                            <div class="panel-heading">{title}</div>
                            <div class="panel-body">
                                {#each users}
                                    <div class="crime-holder">
                                        <p>
                                            <span class="action">
                                                {>userName} 
                                            </span> 
                                            <span class="cooldown">
                                                {date}
                                            </span> 
                                        </p>
                                    </div>
                                {/each}
                            </div>
                        </div>
                    </div>
                {/each}
            </div>
        ';
        
    }
