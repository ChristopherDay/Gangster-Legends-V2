<?php

    session_start();

	require 'class/error.php';

	$error = new error();

	include 'dbconn.php';
	
	require 'class/page.php';
	require 'class/user.php';
	require 'class/settings.php'; 

	$settings = new settings();
	
	if (!isset($_GET['page'])) {
		
		$_GET['page'] = 'loggedin';
		
	}
	
	if (!empty($_SESSION['userID'])) {
		
		$user = new user($_SESSION['userID']);
		
		$user->updateTimer('laston', time());
        
        $pageToLoad = $_GET['page'];
		
        if (!$user->checkTimer('jail')) {
            $jailPageCheck = $page->loadPage($pageToLoad, true);
            if ($jailPageCheck->jailPage) {
            	$page->loadPage($pageToLoad);
            } else {
            	$page->loadPage('jail');
            }
        } else {
            $page->loadPage($pageToLoad);
        }
            
	} else if (in_array($_GET['page'], $page->loginPages)) {
		
		$page->loadPage($_GET['page']);
		
	} else {
		
		$page->loadPage("login");
		
	}
	
	$page->printPage();

	$page->success = true;
	
?>