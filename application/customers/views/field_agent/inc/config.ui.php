<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"icon" => "fa-home"
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/
$page_nav = array(
"home" => array(
		"title" => "Dashboard",
		"url" => URL."field_agent/index",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
"Getstarted" => array(
		"title" => "Get Started",
		"url" => URL."help/field_agent",
		"icon" => "fa-thumbs-up",
		"class" => "menu-links"
	),
	"doc_match" => array(
			"title" => "Doc Match",
			"url" => URL."field_agent/doc_comp",
			"icon" => "fa-edit",
			"class" => "menu-links"
	),
    "logout" => array(
		"title" => "Logout",
		"url" => URL."auth/logout",
		"icon" => "fa-power-off",
		"class" => "menu-links"
	)       
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>