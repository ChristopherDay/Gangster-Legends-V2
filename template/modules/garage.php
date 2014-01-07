<?php

    class garageTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $garageTable = '<table class="table table-condensed table-responsive">
        <tr><th>Name</th><th style="width:100px">Damage</th><th style="width:100px">Value</th><th style="width:150px">Location</th><th style="width:150px">Links</th></tr>
        {var1}
        </table>';
        
        public $garageTableRow = '<tr><td>{var1}</td><td>{var3}</td><td>${var5}</td><td>{var2}</td><td>
        <a href="?page=garage&action=sell&id={var4}">Sell</a> ~
        <a href="?page=garage&action=crush&id={var4}">Crush</a> ~
        <a href="?page=garage&action=repair&id={var4}">Repair</a></td></tr>';
        
    }

?>