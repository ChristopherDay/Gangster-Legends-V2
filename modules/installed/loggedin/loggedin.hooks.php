<?php
    $info['loggedin'] = array(
        "name" => L::module_loggedin_title, 
        "version" => "1.0.0", 
        "description" => "",
        "author" => array(
            "name" => "Chris Day", 
            "url" => "http://glscript.cdcoding.com"
        ), 
        "pageName" => L::module_loggedin_pagename,
        "accessInJail" => false, 
        "requireLogin" => true, 
        "adminGroup" => L::module_loggedin_admin_news_group,
        "admin" => array(
            array(
                "text" => L::module_loggedin_admin_news_view, 
                "method" => "view",
            ),
            array(
                "text" => L::module_loggedin_admin_news_add, 
                "method" => "new",
            ),
            array(
                "hide" => true,
                "text" => L::module_loggedin_admin_news_edit, 
                "method" => "edit",
            ),
            array(
                "hide" => true,
                "text" => L::module_loggedin_admin_news_delete, 
                "method" => "delete",
            )
        )
    );

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=loggedin", 
            "text" => L::module_loggedin_title
        );
    });
?>
