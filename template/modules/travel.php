<?php

    class travelTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $locationHolder = '<div class="crime-holder">
            <p>{var1} - {var4} - ${var2} <span class="commit"><a href="?page=travel&action=fly&location={var3}">Travel</a></span></p>
        </div>';
        
    }

?>