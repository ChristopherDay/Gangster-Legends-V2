<?php

    class garageTemplate extends template {
    
        public $garage = '


            <div class="panel panel-default">
                <div class="panel-heading">Garage</div>
                <div class="panel-body">

                    <table class="table table-condensed table-responsive table-bordered table-striped garage-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width:100px">Damage</th>
                                <th style="width:100px">Value</th>
                                <th style="width:150px">Location</th>
                                <th style="width:160px">Links</th>
                            </tr>
                        </thead>
                        <tbody>
                            {#unless cars}
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center">
                                            <em> You have no cars</em>
                                        </div>
                                    </td>
                                </tr>
                            {/unless}
                            {#each cars}
                                <tr>
                                    <td>{name}</td>
                                    <td>{damage}</td>
                                    <td>{#money value}</td>
                                    <td>{location}</td>
                                    <td class="text-center">
                                        <a href="?page=garage&action=sell&id={id}">Sell</a> &nbsp;&nbsp; 
                                        <a href="?page=garage&action=crush&id={id}">Crush</a> &nbsp;&nbsp; 
                                        <a href="?page=garage&action=repair&id={id}">Repair</a>
                                    </td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            </div>
        ';
        
    }

