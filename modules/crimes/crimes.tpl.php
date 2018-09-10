<?php

    class crimesTemplate extends template {

        public $crimeHolder = '
        {#each crimes}
        <div class="crime-holder">
            <p>{name} ({cooldown}) <span class="commit"><a href="?page=crimes&action=commit&crime={id}">Commit</a></span></p>
            <div class="crime-perc">
                <div class="perc" style="width:{percent}%;"></div>
            </div>
        </div>
        {/each}
        {#unless crimes}
            <div class="text-center"><em>There are no crimes</em></div>
        {/unless}';

        public $crimeList = '
            <table class="table table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Crime</th>
                        <th>Cooldown</th>
                        <th>Reward</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    {#each crimes}
                        <tr>
                            <td>{C_name}</td>
                            <td>{C_cooldown} seconds</td>
                            <td>${C_money} - ${C_maxMoney}</td>
                            <td>{C_level}</td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        ';
    }

?>