<?php

    class crimesTemplate extends template {

        public $crimeHolder = '
        {#each crimes}
        <div class="crime-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=crimes&action=commit&crime={id}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless crimes}
            <div class="text-center"><em>There are no crimes</em></div>
        {/unless}';
        
    }

?>