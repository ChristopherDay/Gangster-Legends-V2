<?php

    class adminTemplate extends template {
        public $widgettable = '
            <div class="col-md-{size}">
                <h4>{title}</h4>
                <table class="table table-condensed table-striped table-bordered no-dt">
                    <thead>
                        <tr>
                            {#each header.columns}
                                <th>{name}</th>
                            {/each}
                        </tr>
                    </thead>
                    <tbody>
                        {#each data}
                            <tr>
                                {#each columns}
                                    <th>{value}</th>
                                {/each}
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>

        ';

        public $widgets = '
            <div class="row">
                {#each widgets}
                    <{html}>
                {/each}
            </div>
        ';

    }

