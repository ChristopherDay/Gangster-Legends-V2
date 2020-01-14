<?php
    new hook("accountMenu", function () {
        return array(
            "url" => "?page=mail", 
            "text" => "Mail", 
            "sort" => -1
        );
    });
    new hook("profileLink", function ($user) {
        return array(
            "url" => "?page=mail&action=new&name=" . $user->info->U_name, 
            "text" => "Mail"
        );
    });
?>