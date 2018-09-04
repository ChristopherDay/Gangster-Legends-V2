<?php

    class theftTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $theftHolder = '
        {#each theft}
        <div class="crime-holder">
            <p>{name} <span class="commit"><a href="?page=theft&action=commit&id={id}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}';
        
    }

?>