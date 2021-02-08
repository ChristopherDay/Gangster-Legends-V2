<?php

    class forgotPasswordTemplate extends template {
    
        public $resetPasswordEmail = '
            <form action="?page=forgotPassword&action=reset" method="post">
                <p>
                    <input class="form-control" type="email" name="email" placeholder="Email Address" />
                </p>
                <p class="text-right">
                    <button type="submit" class="btn btn-default">Reset Password</button>
                </p>
            </form>
        ';

        public $resetPassword = '

            <p>
                Please enter a new password!
            </p>

            <form action="?page=forgotPassword&action=resetPassword&auth={auth}&id={id}" method="post">
                <input class="form-control" type="password" name="password" placeholder="Password" />
                <p>
                    <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password" />
                </p>
                <p class="text-right">
                    <button type="submit" class="btn btn-default">Reset Password</button>
                </p>
            </form>
        ';
        
    }

