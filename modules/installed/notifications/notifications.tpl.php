<?php

    class notificationsTemplate extends template {
        
        public $notifications = '

            <div class="panel">
                <div class="panel-heading">
                    Notifications
                </div>
                <div class="panel-body">

                     <ul class="list-group text-left">
        
                        {#each userNotifications}
                            <li class="list-group-item"> 
                                <p class="tiny">
                                    {#unless read}
                                        <span class="new">*NEW*</span>
                                    {/unless}
                                    {date}

                                    <a class="pull-right" href="?page=notifications&action=delete&id={id}">
                                        Delete
                                    </a>

                                </p>
                                <p>
                                    <{text}>
                                </p>
                            </li>
                        {/each} 
                        {#unless userNotifications}
                            <li class="list-group-item">
                                <em> You have no notifications</em>
                            </li>
                        {/unless}

                    </ul>

                    <nav>
                        <ul class="pagination">
                            {#each pages}
                                <li {#if active}class="active"{/if}><a href="?page=notifications&p={page}">{page}</a></li>
                            {/each}
                        </ul>
                    </nav>

                </div>
            </div>

        ';
        
    }

?>