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
		"url" => URL."patient_login/patient_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
"notification" => array(
		"title" => "Notifications",
		"icon" => "fa-bell",
		"class" => "menu-links",
		"sub" => array(
			"create_notification" => array(
					"title" => "Send Notification",
					"url" => URL."patient_login/patient_message",
					"icon" => "fa-edit",
					"class" => "menu-links"
			),
// 			"manage_notification" => array(
// 					"title" => "Notification History",
// 					"url" => URL."patient_login/manage_notification",
// 					"icon" => "fa-th-list",
// 					"class" => "menu-links"
// 			)
	)),
"appointment" => array(
		"title" => "Appointments",
		"icon" => "fa-calendar",
		"class" => "menu-links",
		"sub" => array(
			"search_user" => array(
					"title" => "Search Doctors",
					"url" => URL."patient_login/display_all_users",
					"icon" => "fa-user-md",
					"class" => "menu-links"
			),
			"my_appointments" => array(
					"title" => "My Appointments",
					"url" => URL."patient_login/display_patient_appointments",
					"icon" => "fa-stethoscope",
					"class" => "menu-links"
			),
	)),
	
    "logout" => array(
		"title" => "Logout",
		"url" => URL."patient_login/logout",
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