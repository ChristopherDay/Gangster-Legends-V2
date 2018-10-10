<?php

	$start = microtime();

    session_start();

	require 'class/hooks.php';
	require 'class/error.php';

	$error = new error();

	include 'dbconn.php';
	
	require 'class/settings.php'; 
	
	require 'class/template.php';
	require 'class/templateRender.php';
	require 'class/page.php';
	require 'class/user.php';

	$settings = new settings();

	$page->loadModuleMetaData();
	
	if (!isset($_GET['page'])) {
		$_GET['page'] = $page->landingPage;
	}

    $pageToLoad = $_GET['page'];
    $jailPageCheck = $page->modules[$pageToLoad];
	
	if (!empty($_SESSION['userID'])) {
		
		$user = new user($_SESSION['userID']);
		
		$user->updateTimer('laston', time());

        if ($user->info->U_status == 2) {
            $page->loadPage('users');
        } else if ($user->info->U_status == 0 && $_GET["page"] != "logout") {
            $page->loadPage('banned');
        } else if (!$user->checkTimer('jail')) {
            if ($jailPageCheck["accessInJail"]) {
            	$page->loadPage($pageToLoad);
            } else {
            	$page->loadPage('jail');
            }
        } else {
            $page->loadPage($pageToLoad);
        }
            
	} else if (!$jailPageCheck["requireLogin"]) {
		$page->loadPage($_GET['page']);
	} else {
		
		$page->loadPage("login");
		
	}

	$page->printPage();

	$page->success = true;
	
?>