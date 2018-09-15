<?php

    class adminTemplate extends template {
        public $adminTemplate = '
        	<h2>Module</h2>
        	{#each modules}
        		<a href="?page=admin&module={moduleName}">{name}</a> <br />
        	{/each}
        ';

        public $moduleTemplate = '
        	<h2>{module}</h2>
        	{#each links}
        		<a href="?page=admin&module={moduleName}">{name}</a> <br />
        	{/each}
        ';
    }

?>