<?php

    class familiesTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $families = '
        	{#each gangs}
        		{name} ({members}) <br />
        	{/each}
        ';
        
    }

?>