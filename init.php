<?php
    
    spl_autoload_register(function ($class) {
        $file = 'class/' . lcfirst($class) . '.php';
        if(file_exists($file)) {
            include $file;
        }
    });

    new ErrorHandler();

    $start = microtime();

    session_start();

    if (file_exists("install/index.php")) {
        header("Location: install/");
        exit;
    }

    include 'dbconn.php';

    $settings = new settings();

    $page = new Page();
    $page->loadModuleMetaData();

    if (!isset($_GET['page'])) {
        $_GET['page'] = $page->landingPage;
    }

    $pageToLoad = $_GET['page'];

    if (!isset($page->modules[$pageToLoad])) {
        if (!empty($_SESSION['userID'])) {
            $user = new user($_SESSION['userID']);
            $user->updateTimer('laston', time());
            $user->checkRank();
        }
        $page->loadPage("pageNotFound");
    } else {

        $jailPageCheck = $page->modules[$pageToLoad];

        $user = false;

        if (!empty($_SESSION['userID'])) {
            $user = new user($_SESSION['userID']);
            
            if (isset($user->info->U_id)) {
                $user->updateTimer('laston', time());
                $user->checkRank();
            } else {
                $user = false;
                unset($_SESSION["userID"]);
            }

        }

        if ($user) {
            
            if ($_GET["page"] == "logout") {
                $page->loadPage('logout');
            } else if ($user->info->U_status == 0) {
                $deadPage = "dead";
                $hook = new Hook("deadPage");
                $deadPage = $hook->run($deadPage, true);
                $page->loadPage($deadPage);
            } else if ($user->info->U_status == 2 && $jailPageCheck["requireLogin"]) {
                $page->loadPage('users');
            } else if ($user->info->U_userLevel == 3) {
                $bannedPage = "banned";
                $hook = new Hook("bannedPage");
                $bannedPage = $hook->run($bannedPage, true);
                $page->loadPage($bannedPage);
            } else if (!$user->checkTimer('jail')) {
                if ($jailPageCheck["accessInJail"]) {
                    $page->loadPage($pageToLoad);
                } else {
                    $jailPage = "jail";
                    $hook = new Hook("jailPage");
                    $jailPage = $hook->run($jailPage, true);
                    $page->loadPage($jailPage);
                }
            } else {
                $page->loadPage($pageToLoad);
            }
                
        } else if (!$jailPageCheck["requireLogin"]) {
            $page->loadPage($_GET['page']);
        } else {
            $loginPage = "login";
            $hook = new Hook("loginPage");
            $loginPage = $hook->run($loginPage, true);
            $page->loadPage($loginPage);
        }
    
    }

    $page->printPage();

    $page->success = true;
    
?>