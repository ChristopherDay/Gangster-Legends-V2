<?php

    class loggedinTemplate extends template {
        
        public $newsArticle = '
        	{#each news}
	        	<h3>{title} <small>{author} at {date}</small></h3>
	        	<p>{text}</p>
        	{/each}
        ';
        
    }

?>