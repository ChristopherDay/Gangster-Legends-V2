<?php

    class deadTemplate extends template {
        
        public $newAccount = "

            <div class='panel panel-default'>
                <div class='panel-heading'>
                    You are dead
                </div>
                <div class='panel-body'>

                    <h4>You have been shot{#if user.name} by {>userName}{/if}!</h4>
                    <p>
                        To get revenge you can create a new account!
                    </p>
                 
                    <form method='post' action='?page=dead&action=new'>
                        <p>
                            <input autocomplete='new-password' type='text' name='username' class='form-control form-control-inline' placeholder='New Username' /> 
                        </p>
                        <p>
                            <input autocomplete='new-password' type='password' name='password' class='form-control form-control-inline' placeholder='Current Password' /> 
                        </p>
                        <button class='btn btn-default'>Create Account</button><br />
                        <small>
                            <a href='?page=logout'>Logout</a>
                        </small>
                    </form>
                </div>
            </div>
        ";
        
    }

