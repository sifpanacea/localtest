<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => URL."auth/index"
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
	"customers management" => array(
		"title" => "Cust. Management",
		"icon" => "fa-inbox",
		"class" => "menu-links",
		"sub" => array(
			"customer" => array(
				"title" => "Customers",
				"url" => URL."admin_dash/customers",
				"icon" => "fa-star",
				"class" => "menu-links",
				),
			"custstatus" => array(
				"title" => "Activate/Deactivate Customer",
				"url" => URL."admin_dash/pre_activate",
				"icon" => "fa-ban",
				"class" => "menu-links"
			),
			"new_cust" => array(
				"title" => "New Customer",
				"url" =>URL."admin_dash/new_customers",
				"icon" => "fa-plus-circle",
				"class" => "menu-links",
			),
		),
	),
	"customers usage" => array(
		"title" => "PAAS Usage",
		"icon" => "fa-inbox",
		"class" => "menu-links",
		"sub" => array(
			"customer" => array(
				"title" => "Customers",
				"url" => URL."admin_dash/customers/usage",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
		),
	),
	"support admin management" => array(
		"title" => "Support Admin",
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
			"customer" => array(
				"title" => "Support Admin",
				"url" => URL."admin_dash/create_support_admin",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
		),
	),
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>