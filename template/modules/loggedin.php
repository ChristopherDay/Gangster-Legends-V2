<?php

    class loggedinTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $newsArticle = '
        	{#each news}
	        	<h3>{title} <small>{author} at {date}</small></h3>
	        	<p>{text}</p>
        	{/each}
        ';
        
    }

?>