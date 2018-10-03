<?php

    class mainTemplate {
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page}</title>
                <link rel="stylesheet" href="themes/default - Grey/css/bootstrap.min.css">
                <link rel="stylesheet" href="themes/default - Grey/css/style.css">
        		<link rel="shortcut icon" href="themes/default - Grey/images/icon.png" />
                <meta name="viewport" content="width=device-width, initial-scale=1">
            </head>
            
            <body>
            
                <div class="container">
                    <div class="row">
                        <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <{game}>
                            </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>

                <script src="themes/default - Grey/js/jquery.js"></script>
                <!--<script src="themes/default - Grey/js/bootstrap.min.js"></script>-->
                <script src="themes/default - Grey/js/timer.js"></script>
                <script src="themes/default - Grey/js/mobile.js"></script>
            </body>
        </html>';
    
    }

?>