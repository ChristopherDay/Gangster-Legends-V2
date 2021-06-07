<?php

    class blackmarketTemplate extends template {
        

        public $blackMarket = '


            <div class="panel panel-default">
                <div class="panel-heading">{location} Black Market</div>
                <div class="panel-body">
            

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Weapons</h4>
                            {#each weapons}
                                <div class="crime-holder">
                                    <p>
                                        <span class="action">
                                            {name}
                                        </span> 
                                        <span class="cooldown">
                                            {#if cost} {#money cost} {/if}
                                            {#if points}
                                                {#if cost} + {/if}
                                                {number_format points} {_setting "pointsName"}
                                            {/if}
                                        </span> 
                                        {#unless cantBuy}
                                            <span class="commit">
                                                {#if owned}
                                                    Owned
                                                {/if}
                                                {#unless owned}
                                                    <a href="?page=blackmarket&action=buy&item={id}">
                                                        Buy
                                                    </a>
                                                {/unless}
                                            </span>
                                        {/unless}
                                    </p>
                                </div>
                            {/each}
                            {#unless weapons}
                                <div class="text-center">
                                    <em>There are no weapons for you to buy</em>
                                </div>
                            {/unless}
                        </div>
                        <div class="col-md-6">
                            <h4>Armor</h4>
                            {#each armor}
                                <div class="crime-holder">
                                    <p>
                                        <span class="action">
                                            {name} 
                                        </span> 
                                        <span class="cooldown">
                                            {#if cost} {#money cost} {/if}
                                            {#if points}
                                                {#if cost} + {/if}
                                                {number_format points} {_setting "pointsName"}
                                            {/if}
                                        </span> 
                                        {#unless cantBuy}
                                            <span class="commit">
                                                {#if owned}
                                                    Owned
                                                {/if}
                                                {#unless owned}
                                                    <a href="?page=blackmarket&action=buy&item={id}">
                                                        Buy
                                                    </a>
                                                {/unless}
                                            </span>
                                        {/unless}
                                    </p>
                                </div>
                            {/each}
                            {#unless armor}
                                <div class="text-center">
                                    <em>There is no armor to buy</em>
                                </div>
                            {/unless}

                        </div>
                    </div>
                </div>
            </div>

        ';
        
    }

