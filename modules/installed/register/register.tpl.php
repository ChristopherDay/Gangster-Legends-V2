<?php

    class registerTemplate extends template {
    
        public $registerForm = '
			<{text}>
            <form action="?page=register&action=register" method="post">
                <input class="form-control" type="text" name="username" placeholder="Username" /><br />
                <input class="form-control" type="text" autocomplete="off" name="email" placeholder="EMail" /><br />
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" type="password" name="password" placeholder="Password" />
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password" />
                    </div>
                </div>
                <br />
                <button type="submit" class="btn pull-right">Register</button>
            </form>
        ';

        public $loginOptions = '

            <form method="post" action="?page=admin&module=register&action=settings">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Register Suffix</label>
                            <textarea type="text" class="form-control" name="registerSuffix" rows="5">{registerSuffix}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="pull-left">Register Postfix</label>
                            <textarea type="text" class="form-control" name="registerPostfix" rows="5">{registerPostfix}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-default" name="submit" type="submit" value="1">Save</button>
                </div>
            </form>
        ';
        
    }

?>