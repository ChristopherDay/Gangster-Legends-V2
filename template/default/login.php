<?php

    class mainTemplate {
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page}</title>
                <link rel="stylesheet" href="template/default/css/bootstrap.min.css">
                <link rel="stylesheet" href="template/default/css/style.css">
        		<link rel="shortcut icon" href="template/default/images/icon.png" />
            </head>
            
            <body>
            
                <div class="row">
                    <div class="col-md-4"></div>
                        <div class="col-md-4">
                            {game}
                        </div>
                    <div class="col-md-4"></div>
                </div>
            </body>
        </html>';
    
    }

?>