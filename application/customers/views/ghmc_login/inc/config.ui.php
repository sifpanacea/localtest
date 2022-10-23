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
		"url" => URL."ghmc_login/ghmc_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
"user management" => array(
		"title" => lang('common_user_mgmt'),
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
// 			"users" => array(
// 				"title" => lang('common_users'),
// 				"url" => URL."dashboard/user",
// 				"icon" => "fa-info",
// 				"class" => "menu-links"
// 			),
			"createuser" => array(
				"title" => lang('common_create_user'),
				"url" =>URL."auth/create_ghmc_user",
				"icon" => "fa-male",
				"class" => "menu-links"
			),
// 			"userstatus" => array(
// 				"title" => lang('common_act_deact_user'),
// 				"url" => URL."auth/pre_activate",
// 				"icon" => "fa-ban",
// 				"class" => "menu-links"
// 			),
// 			"edituser" => array(
// 				"title" => lang('common_edit_user'),
// 				"url" => URL."auth/pre_edit_user",
// 				"icon" => "fa-edit ",
// 				"class" => "menu-links"
// 			),
// 			"deleteuser" => array(
// 				"title" => lang('common_delete_user'),
// 				"url" => URL."auth/pre_delete_user",
// 				"icon" => "fa-minus-circle",
// 				"class" => "menu-links"
// 			),
		),
	),
// 			"manage_notification" => array(
// 					"title" => "Notification History",
// 					"url" => URL."patient_login/manage_notification",
// 					"icon" => "fa-th-list",
// 					"class" => "menu-links"
// 			)
	
    "logout" => array(
		"title" => "Logout",
		"url" => URL."ghmc_login/logout",
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