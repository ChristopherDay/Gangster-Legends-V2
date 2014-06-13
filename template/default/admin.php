<?php

    class mainTemplate {
        
        public $pageMain =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <link href="template/default/css/bootstrap.min.css" rel="stylesheet" />
                <link href="template/default/css/admin.css" rel="stylesheet" />
                <link rel="shortcut icon" href="template/default/images/icon.png" />
                <title>{game_name} - {page}</title>
            </head>
            <body>

                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <h2>Navigation</h2>
                        <ul class="nav nav-pills nav-stacked">
                            {adminLinks}
                        </ul>
                    </div>
                    <div class="col-md-9 col-md-10">
                        <h2>Welcome</h2>
                        {game}
                    </div>
                </div>
            

                <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
                <script src="template/default/js/bootstrap.min.js"></script>
            </body>
        </html>';
    
    }

?>
