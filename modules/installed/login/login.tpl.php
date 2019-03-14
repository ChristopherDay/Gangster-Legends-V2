<?php

    class loginTemplate extends template {
    
        public $loginForm = '
                <{text}>
                <form action="?page=login&action=login" method="post">
                    <input autocomplete="new-password" type="input" class="form-control" name="email" placeholder="Email" /><br />
                    <input autocomplete="new-password" type="password" class="form-control" name="password" placeholder="Password" /><br />
                    <div class="text-right">
                        <button type="submit" class="btn">Login</button>
                    </div>
                </form>
        ';

        public $loginOptions = '

            <form method="post" action="?page=admin&module=login&action=settings">

                <div class="row">
                    <div class="col-md-12">
                        <label class="">Login Suffix</label>
                        <div class="form-group">
                            <textarea type="text" class="form-control" data-editor="html" name="loginSuffix" rows="5">{loginSuffix}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="">Login Postfix</label>
                        <div class="form-group">
                            <textarea type="text" class="form-control" data-editor="html" name="loginPostfix" rows="5">{loginPostfix}</textarea>
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