<?php

    class policeChaseTemplate extends template {
    
        public $policeChase = '

            <div class="alert alert-warning">
                The police have noticed that you\'re driving a stolen car, better get rid of them!
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">What direction do you want to drive?</div>
                <div class="panel-body">
                    <form method="post" action="?page=policeChase&action=move">
                        <input type="hidden" name="_CSFR" value="{_CSFRToken}" />
                        <div style="text-align:center;">
                            <input type="submit" class="button-fixed-width btn btn-default" value="Foward" />
                            <br />
                            <input type="submit" class="button-fixed-width btn btn-default" value="Left" />
                            <span class="button-fixed-width move-icon">
                                <i class="glyphicon glyphicon-fullscreen"></i>
                            </span>
                            <input type="submit" class="button-fixed-width btn btn-default" value="Right" />
                            <br />
                            <input type="submit" class="button-fixed-width btn btn-default" value="U-Turn" /></a></p>
                        </div>
                    </form>
                </div>
            </div>
        ';
        
    }

