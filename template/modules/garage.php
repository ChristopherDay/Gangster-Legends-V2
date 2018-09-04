<?php

    class garageTemplate extends template {
    
        public $loginPage = false; // Ture means you can access this page without being logged in
        public $jailPage = false; // True means you can view this page in prison
        
        public $garage = '<table class="table table-condensed table-responsive">
            <tr>
                <th>Name</th>
                <th style="width:100px">Damage</th>
                <th style="width:100px">Value</th>
                <th style="width:150px">Location</th>
                <th style="width:150px">Links</th>
            </tr>
            
            {#each cars}
                <tr>
                    <td>{name}</td>
                    <td>{damage}</td>
                    <td>${value}</td>
                    <td>{location}</td>
                    <td>
                        <a href="?page=garage&action=sell&id={id}">Sell</a> ~
                        <a href="?page=garage&action=crush&id={id}">Crush</a> ~
                        <a href="?page=garage&action=repair&id={id}">Repair</a>
                    </td>
                </tr>
            {/each}
        </table>';
        
    }

?>