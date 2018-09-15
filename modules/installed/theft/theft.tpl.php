<?php

    class theftTemplate extends template {

        public $theftHolder = '
        {#each theft}
        <div class="crime-holder">
            <p>
                {name} 
                <span class="commit">
                    <a href="?page=theft&action=commit&id={id}">Steal</a>
                </span>
            </p>
            <div class="crime-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}';
        
    }

?>