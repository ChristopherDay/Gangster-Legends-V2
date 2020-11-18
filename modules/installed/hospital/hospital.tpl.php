<?php

    class hospitalTemplate extends template {

         public $options = '

            <form method="post" action="?page=admin&module=hospital&action=options">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="pull-left">Time untill 100% health (seconds)</label>
                            <input type="number" class="form-control" name="hospitalTimeUntillFull" value="{hospitalTimeUntillFull}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="pull-left">Cost to get 100% health ($)</label>
                            <input type="number" class="form-control" name="hospitalmoneyUntillFull" value="{hospitalmoneyUntillFull}" />
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>

            </form>
        ';

        public $hospital = '

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">{location} hospital admissions</div>
                        <div class="panel-body">

                            {#unless health}
                                You are healthy, you dont need to check in to A&E!
                            {/unless}

                            {#if health}

                                <p>
                                    You currently have {healthPerc}% health!
                                </p>
                                <p>
                                    To heal it will take {time} 
                                    {#if money}
                                        and cost ${number_format money}
                                    {/if}!
                                </p>
                                <p>
                                    <a href="?page=hospital&action=checkin" class="btn btn-default">
                                        Check In
                                    </a>
                                </p>
                            {/if}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Patients</div>
                        <div class="panel-body">
                            <ul class="list-group text-left">
                                {#unless users} 
                                    <div class="text-center">
                                        <em>There is no one in hoispital</em>
                                    </div>
                                {/unless}
                                {#each users} 
                                    <li class="list-group-item"> 
                                        {>userName}
                                        <span data-remove-when-done="" data-timer-type="inline" data-timer="{time}" class="timer-active pull-right"></span>
                                    </li>
                                {/each}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        ';
        public $inHospital = '

            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Patients</div>
                        <div class="panel-body">
                            <ul class="list-group text-left">
                                {#unless users} 
                                    <div class="text-center">
                                        <em>There is no one in hoispital</em>
                                    </div>
                                {/unless}
                                {#each users} 
                                    <li class="list-group-item"> 
                                        {>userName}
                                        <span data-remove-when-done="" data-timer-type="inline" data-timer="{time}" class="timer-active pull-right"></span>
                                    </li>
                                {/each}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        ';
        
    }

?>