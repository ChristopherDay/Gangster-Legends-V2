<?php

    class pageNotFoundTemplate extends template {
        public $pageNotFound = '

            <div class="panel panel-default">
                <div class="panel-heading">{errorTitle}</div>
                <div class="panel-body">
                    <p>{error}</p>
                </div>
            </div>
        ';

    }

?>
