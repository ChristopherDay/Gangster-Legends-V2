<?php

    class travelTemplate extends template {
    
        public $locationHolder = '

            <div class="panel panel-default">
                <div class="panel-heading">Travel</div>
                <div class="panel-body">
                    {#each locations}
                    <div class="crime-holder">
                        <p>
                            <span class="action">
                                {location} 
                            </span>
                            <span class="cooldown">
                                ({cooldown})&nbsp;&nbsp;&nbsp;&nbsp;${cost} 
                            </span>
                            <span class="commit">
                                <a href="?page=travel&action=fly&location={id}">Travel</a>
                            </span>
                            </p>
                    </div>
                    {/each}
                </div>
            </div>
        ';
        
    }

?>