<?php
    new hook("accountMenu", function ($user) {
        if ($user) return array(
            "url" => "?page=notifications", 
            "text" => "Notifications", 
            "sort" => -5,
            "extra" => $user->getNotificationCount($user->info->U_id, 'notifications')
        );
    });