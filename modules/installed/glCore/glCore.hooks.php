<?php

    new hook("adminWidget-html", function ($user) {
        global $page;
        return array(
            "sort" => 10000000,
            "size" => 12, 
            "html" => '<hr /><div class="text-center"><small>Gangster Legends V'.$page->modules["glCore"]["version"]."</small></div>",
            "type" => "html", 
            "title" => false
        );
    });
    
    new hook("adminWidget-html", function ($user) {
        
        global $db, $page;

        $posts = array();

        $data = @file_get_contents("https://glscript.net/wp-json/wp/v2/posts?per_page=3&categories=3");

        if (strlen($data)) {
            $posts = json_decode($data, true);
        }


        $html = '<div class="list-group">';
            foreach ($posts as $post) {
                $html .= '<div class="list-group-item">';
                $html .= '  <a href="'.$post["link"].'" target="_blank">'.$post["title"]["rendered"].'</a>';
                $html .= '  <small class="pull-right">'.date("jS M Y", strtotime($post["date"])).'</small>';
                $html .= $post["excerpt"]["rendered"];
                $html .= '</div>';
            }
        $html .= '</div>';

        return array(
            "sort" => 10,
            "size" => 7, 
            "html" => $html,
            "type" => "html", 
            "title" => "Gangster Legends News"
        );

    });

    new hook("adminWidget-html", function ($user) {
        
        global $db, $page;

        $post = array();

        $data = @file_get_contents("https://glscript.net/wp-json/wp/v2/posts/345");

        if (strlen($data)) {
            $post = json_decode($data, true);
        }

        $html = '<div class="list-group">';
            $html .= '<div class="list-group-item">';
                $html .= '<p>';
                    $html .= $post["content"]["rendered"];
                $html .= '</p>';
            $html .= '</div>';
        $html .= '</div>';

        return array(
            "sort" => 15,
            "size" => 5, 
            "html" => $html,
            "type" => "html", 
            "title" => "Support Gangster Legends"
        );

    });

    new hook("adminWidget-alerts", function ($user) {
        global $db, $page;
        
        $settings = new Settings();
        $savedHash = $settings->loadSetting("glCoreHash");
        $currentHash = hashDirectory("class/");

        if (!$savedHash) {
            $settings->update("glCoreHash", $currentHash);
            $savedHash = $currentHash;
        }

        if ($savedHash != $currentHash) return array( 
            "type" => "warning", 
            "text" => "The Gangster Legends core code has been altered, this can potentially break future upgrades!"
        );
        return false;
    });