<?php
	
//CONFIGURATION for SmartAdmin UI
	
	
$breadcrumbs = array(
	"Home" => URL
);
	
$page_nav = array(
    "home" => array(
		"title" => "DashBoard",
		"url" => URL."web/index",
		"icon" => "fa-home"
	),
	"Getstarted" => array(
		"title" => "Get Started",
		"url" => URL."help/user_index",
		"icon" => "fa-thumbs-up"
	),
	"apps" => array(
		"title" => "Apps",
		"url" => URL."web/apps",
		"icon" => "fa-folder-open-o",
	),
	"documents" => array(
		"title" => "Documents",
		"url" => URL."web/docs",
		"icon" => "fa-pencil",
	
	),
	"documentsearch" => array(
		"title" => "Document Search",
		"url" => URL."web/doc_search",
		"icon" => "fa-search",
	
	),
	"changepassword" => array(
		"title" => "Change Password",
		"url" => URL."web/change_password",
		"icon" => "fa-exchange",
	
	),
    "logout" => array(
		"title" => "Logout",
		"url" => URL."auth/logout",
		"icon" => "fa-sign-out"
	)
);
	
//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>