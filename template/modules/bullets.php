<?php

    class bulletsTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $bulletPage = '<p>Welcome to the {var1} bullet factory, currently it has {var2} bullets in stock at the cost of ${var3} each!</p>
        <p>
            You can buy up to {var4} at once!
        </p>
        <p>
            <form action="?page=bullets&action=buy" method="post">
                <input type="text" class="form-control" name="bullets" style="width:calc(97% - 100px); display:inline-block;" placeholder="Qty. to buy" />
                <input type="submit" class="btn btn-link" style="display:inline-block; width:100px;" value="Buy" />
            </form>
        </p>
        ';
        
    }

?>