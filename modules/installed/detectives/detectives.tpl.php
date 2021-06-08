<?php

    class detectivesTemplate extends template {
        
        public $detectives = '
            <div class="row">
                <div class="col-md-5">

                    <div class="panel panel-default">
                        <div class="panel-heading">Hire Detectives</div>
                        <div class="panel-body">
                            <p>
                                Before killing a user you first need to find them! It will cost you {#money detectiveCost} p/h to hire a detective.
                            </p>
                            <form class="text-left detective-form" action="?page=detectives&action=hire" method="post" >
                                <input type="hidden" name="cost" value="{detectiveCost}" />
                                <p>
                                    <strong>User to find</strong>
                                    <input type="text" class="form-control" name="user" value="{user}" />
                                </p>
                                <div class="row">
                                    <div class="col-md-5">
                                        <p>
                                            <strong>For how long</strong>
                                            <select onChange="updateDetectiveCost()" class="form-control" name="hours">
                                                {#each hours}
                                                    <option value="{duration}">{time}</option>
                                                {/each}
                                            </select>
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <p>
                                            <strong>How many Detectives?</strong>
                                            <select onChange="updateDetectiveCost()" class="form-control" name="detectives">
                                                <option value="1">1 Detective</option>
                                                <option value="2">2 Detectives</option>
                                                <option value="3">3 Detectives</option>
                                                <option value="4">4 Detectives</option>
                                                <option value="5">5 Detectives</option>
                                            </select>
                                        </p>
                                    </div>
                                </div>
                                <p class="text-center">
                                    <button class="btn btn-default" name="submit" value="1">Hire Detectives</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">Your Detectives</div>
                        <div class="panel-body">
                            {#each hiredDetectives}
                                <div class="crime-holder">
                                    <p>
                                        <span class="action">
                                            {>userName} 
                                        </span> 
                                        <span class="cooldown">
                                            {#if isSearching}
                                                <span data-reload-when-done data-timer-type="inline" data-timer="{end}"></span>
                                            {/if}
                                            {#unless isSearching}
                                                {#if isExpired}
                                                    <strong>EXPIRED</strong>
                                                {/if}
                                                {#unless isExpired}
                                                    {#if success}
                                                        {location}
                                                    {/if}
                                                    {#unless success}
                                                        <strong>NOT FOUND</strong>
                                                    {/unless}
                                                {/unless}
                                            {/unless}
                                        </span> 
                                        <span class="commit">
                                            <a href="?page=detectives&action=remove&id={id}">Remove</a>
                                        </span>
                                    </p>
                                </div>
                            {/each}
                            {#unless hiredDetectives}
                                <div class="text-center">
                                    <em>
                                        You dont have any active detectives
                                    </em>
                                </div>
                            {/unless}
                        </div>
                    </div>
                        </div>
                    </div>
        ';

         public $detectiveOptions = '

            <form method="post" action="?page=admin&module=detectives&action=options">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Cost of detective per interval</label>
                            <input type="text" class="form-control" name="detectiveCost" value="{detectiveCost}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Detective search duration (seconds)</label>
                            <input type="text" class="form-control" name="detectiveDuration" value="{detectiveDuration}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Detective expire time (seconds)</label>
                            <input type="text" class="form-control" name="detectiveExpire" value="{detectiveExpire}" />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>

            </form>
        ';
        
    }

