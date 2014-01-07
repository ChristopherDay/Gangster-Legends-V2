<?php

    class policeChaseTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $policeChase = '<div style="text-align:center;">
        <p>What direction do you want to travel?</p>
        <p><a class="btn" href="?page=policeChase&action=move">Foward</a><br />
        <a class="btn" href="?page=policeChase&action=move">Left</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn" href="?page=policeChase&action=move">Right</a><br />
        <a class="btn" href="?page=policeChase&action=move">U-Turn</a></p>
        </div>';
        
    }

?>