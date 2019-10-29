<?php
    $info = array(
        "name" => "Game News", 
        "version" => "1.0.0", 
        "description" => "",
        "author" => array(
            "name" => "Chris Day", 
            "url" => "http://glscript.cdcoding.com"
        ), 
        "pageName" => "Game News",
        "accessInJail" => false, 
        "requireLogin" => true, 
        "adminGroup" => "Communication",
        "admin" => array(
            array(
                "text" => "View News", 
                "method" => "view",
            ),
            array(
                "text" => "Add News", 
                "method" => "new",
            ),
            array(
                "hide" => true,
                "text" => "Edit News", 
                "method" => "edit",
            ),
            array(
                "hide" => true,
                "text" => "Delete News", 
                "method" => "delete",
            )
        )
    );

    new hook("accountMenu", function () {
        return array(
            "url" => "?page=loggedin", 
            "text" => "Game News"
        );
    });
?>
