<?php

	$start = microtime();

    session_start();

	require 'class/hooks.php';
	require 'class/error.php';

	include 'dbconn.php';
	
	require 'class/settings.php'; 
	
	require 'class/template.php';
	require 'class/templateRender.php';
	require 'class/page.php';
	require 'class/user.php';
	require 'class/property.php';

	$settings = new settings();

	$page->loadModuleMetaData();
	
	if (!isset($_GET['page'])) {
		$_GET['page'] = $page->landingPage;
	}

    $pageToLoad = $_GET['page'];

    if (!isset($page->modules[$pageToLoad])) {
		if (!empty($_SESSION['userID'])) {
			$user = new user($_SESSION['userID']);
			$user->updateTimer('laston', time());
		}
    	$page->loadPage("pageNotFound");
    } else {

	    $jailPageCheck = $page->modules[$pageToLoad];

		if (!empty($_SESSION['userID'])) {
			
			$user = new user($_SESSION['userID']);
			
			$user->updateTimer('laston', time());

	        if ($_GET["page"] == "logout") {
	            $page->loadPage('logout');
	        } else if ($user->info->U_status == 0) {
	            $page->loadPage('dead');
	        } else if ($user->info->U_status == 2) {
	            $page->loadPage('users');
	        } else if ($user->info->U_userLevel == 3) {
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
    
    }

	$page->printPage();

	$page->success = true;
	
?>