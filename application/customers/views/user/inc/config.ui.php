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
		"title" => "DashBoard",
		"url" => URL."web/index",
		"icon" => "fa-home"
	),
	"Getstarted" => array(
		"title" => "Get Started",
		"url" => URL."help/user_index",
		"icon" => "fa-thumbs-up"
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