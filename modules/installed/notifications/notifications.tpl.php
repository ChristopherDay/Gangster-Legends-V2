<?php

    class notificationsTemplate extends template {
        
        public $notifications = '
            {#each userNotifications}
                <div class="well well-sm">
                    <p class="tiny">
                        {#unless read}
                            <span class="new">*NEW*</span>
                        {/unless}
                        {date}
                    </p>
                    <p>
                        <{text}>
                    </p>
                </div>
            {/each} 
            {#unless userNotifications}
                <div class="text-center">
                    <em> You have no notifications</em>
                </div>
            {/unless}
        ';
        
    }

?>