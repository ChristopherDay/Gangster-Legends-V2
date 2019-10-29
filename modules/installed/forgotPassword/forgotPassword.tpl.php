<?php

    class forgotPasswordTemplate extends template {
    
        public $resetPasswordEmail = '
            <form action="?page=forgotPassword&action=reset" method="post">
                <input class="form-control" type="email" name="email" placeholder="Email Address" />

                <div class="text-right">
                    <button type="submit" class="btn">Reset Password</button>
                </div>
            </form>
        ';

        public $resetPassword = '

            <p>
                Please enter a new password!
            </p>

            <form action="?page=forgotPassword&action=resetPassword&auth={auth}&id={id}" method="post">
                <input class="form-control" type="password" name="password" placeholder="Password" />
                <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password" />

                <div class="text-right">
                    <button type="submit" class="btn">Reset Password</button>
                </div>
            </form>
        ';
        
    }

?>