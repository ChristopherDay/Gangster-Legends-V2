<?php

    class familiesTemplate extends template {
        
        public $families = '
        	{#each gangs}
        		{name} ({members}) <br />
        	{/each}
        ';
        
    }

?>