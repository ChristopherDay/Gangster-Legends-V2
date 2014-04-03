<?php

    class usersOnlineTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = true; // True means you can view this page in prison
        
        public $usersOnlineHolder = '<div class="row">{var1}</div>';
        public $usersOnline = '<div class="col-md-6"><h4 class="text-left">{var2}</h4><p class="text-left">{var1}</p></div>';
		
		public $user = '<a href="?page=profile&view={var2}">{var1}</a>';
        
    }

?>