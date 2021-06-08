<?php

    class blackmarketTemplate extends template {
        

        public $blackMarket = '
            {#each types}
                {#if items}
                    <div class="panel panel-default">
                        <div class="panel-heading">{ucfirst name}</div>
                        <div class="panel-body">
                            {#each items}
                                <div class="crime-holder">
                                    <p>
                                        <span class="action">
                                            {name} 
                                        </span> 
                                        <span class="cooldown">
                                            {#money cost}
                                        </span> 
                                        <a href="?page=inventory&action=information&item={id}" class="commit">View</a>
                                        &nbsp;
                                        <a href="?page=blackmarket&action=buy&item={id}&_CSFR={_CSFRToken}" class="commit">
                                            Buy
                                        </a>
                                    </p>
                                </div>
                            {/each}
                        </div>
                    </div>
                {/if}
            {/each}

        ';
        
    }

