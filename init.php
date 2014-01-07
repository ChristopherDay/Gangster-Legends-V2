<?php

    session_start();

	ini_set('display_errors',1); 
	error_reporting(E_ALL);
	
	include 'dbconn.php';
	
	require 'class/page.php';
	require 'class/user.php';
	require 'config.php';
	
	if (!isset($_GET['page'])) {
		
		$_GET['page'] = 'loggedin';
		
	}
	
	if (!empty($_SESSION['userID'])) {
		
		$user = new user($_SESSION['userID']);
        
        $pageToLoad = $_GET['page'];
		
        if ($user->info->US_jailTimer > time() && !in_array($pageToLoad, $page->jailPages)) {
		  
            $page->loadPage('jail');
        
        } else {
            
            $page->loadPage($pageToLoad);
        
        }
            
	} else if (in_array($_GET['page'], $page->loginPages)) {
		
		$page->loadPage($_GET['page']);
		
	} else {
		
		$page->loadPage("login");
		
	}
	
	$page->printPage();
	
?>