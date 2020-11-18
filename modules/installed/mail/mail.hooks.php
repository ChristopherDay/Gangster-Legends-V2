<?php
    new hook("accountMenu", function ($user) {
        
        if ($user) return array(
            "url" => "?page=mail", 
            "text" => "Mail", 
            "sort" => -10,
            "extra" => $user->getNotificationCount($user->info->U_id, 'mail')
        );
    });
    new hook("profileLink", function ($user) { 
        return array(
            "url" => "?page=mail&action=new&name=" . $user->info->U_name, 
            "text" => "Mail"
        );
    });
?>