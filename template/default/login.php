<?php

    class mainTemplate {
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>{game_name} - {page}</title>
                <link rel="stylesheet/less" href="template/default/css/bootstrap.min.css">
                <link rel="stylesheet/less" href="template/default/less/bootstrap.less">
                <script src="template/default/js/less.js"></script>
            </head>
            
            <body>
            
                <div class="loginRow">
                    <div class="loginCol"></div>
                        <div class="loginHolder">
                            {game}
                        </div>
                    <div class="loginCol"></div>
                </div>
            </body>
        </html>';
    
    }

?>