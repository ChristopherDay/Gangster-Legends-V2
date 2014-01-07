<?php

    class theftTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $theftHolder = '<div class="crime-holder">
            <p>{var1} <span class="commit"><a href="?page=theft&commit={var2}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{var3}%;"></div>
            </div>
        </div>';
        
    }

?>