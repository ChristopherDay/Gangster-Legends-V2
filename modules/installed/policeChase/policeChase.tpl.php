<?php

    class policeChaseTemplate extends template {
    
        public $policeChase = '

            <div class="panel panel-default">
                <div class="panel-heading">What direction do you want to drive?</div>
                <div class="panel-body">
                    <div style="text-align:center;">
                        <a class="button-fixed-width btn" href="?page=policeChase&action=move">Foward</a><br />
                        <a class="button-fixed-width btn" href="?page=policeChase&action=move">Left</a>
                        <span class="button-fixed-width move-icon">
                            <i class="glyphicon glyphicon-fullscreen"></i>
                        </span>
                        <a class="button-fixed-width btn" href="?page=policeChase&action=move">Right</a><br />
                        <a class="button-fixed-width btn" href="?page=policeChase&action=move">U-Turn</a></p>
                    </div>
                </div>
            </div>
        ';
        
    }

?>