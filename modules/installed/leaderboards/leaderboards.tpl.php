<?php

    class leaderboardsTemplate extends template {
        
        public $leaderboard = '
            <div class="panel panel-default">
                <div class="panel-heading">{title}</div>
                <div class="panel-body">
                    <p class="text-center">
                        <a href="?page=leaderboards&top10=money" class="btn btn-default">Richest</a>
                        <a href="?page=leaderboards&top10=rank" class="btn btn-default">Rank</a>
                    </p>
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" width="35px">#</th>
                                <th>User</th>
                                <th width="220px">Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            {#each users}
                                <tr>
                                    <td class="text-center">{number}</td>
                                    <td>{>userName}</td>
                                    <td>{rank}</td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            </div>
            ';
        
    }

