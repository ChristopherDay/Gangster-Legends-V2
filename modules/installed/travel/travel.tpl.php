<?php

    class travelTemplate extends template {
    
        public $locationHolder = '<div class="crime-holder">
            <p>{location} - {cooldown} - ${cost} <span class="commit"><a href="?page=travel&action=fly&location={id}">Travel</a></span></p>
        </div>';
        
    }

?>