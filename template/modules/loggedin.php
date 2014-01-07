<?php

    class loggedinTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $newsArticle = '<h3>{var1} <small>{var2} at {var3}</small></h3><p>{var4}</p>';
        
    }

?>