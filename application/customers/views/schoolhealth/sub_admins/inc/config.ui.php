<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	lang('common_home') => URL
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
		"url"   => URL."schoolhealth_auth/dashboard",
		"icon"  => "fa-home",
		"class" => "menu-links"
),
"ehr" => array(
		"title" => "Electronic Health Record",
		"url"   => URL."schoolhealth_sub_admin_portal/search_ehr",
		"icon"  => "fa-file-text",
		"class" => "menu-links"
),
"refer_doctors" => array(
"title" => 'Referral Doctors',
"icon" => "fa-user-md",
"class" => "menu-links",
"sub" => array(
	"doctors" => array(
		"title" => "List Doctors",
		"url"   => URL."schoolhealth_sub_admin_portal/list_doctors",
		"icon"  => "fa-star",
		"class" => "menu-links",
	),
	"add_spec" => array(
		"title" => "Add Specialization",
		"url"   => URL."schoolhealth_sub_admin_portal/add_specialization_view",
		"icon"  => "fa-stethoscope",
		"class" => "menu-links",
	),
	"add_doctors" => array(
		"title" => "Add Doctors",
		"url"   => URL."schoolhealth_sub_admin_portal/create_doctor_view",
		"icon"  => "fa-user",
		"class" => "menu-links",
	),
)
),
"schools" => array(
		"title" => "Schools",
		"url"   => URL."schoolhealth_sub_admin_portal/schools",
		"icon"  => "fa-building",
		"class" => "menu-links"
),
"clinics" => array(
		"title" => "Clinics",
		"url"   => URL."schoolhealth_sub_admin_portal/clinics",
		"icon"  => "fa-hospital",
		"class" => "menu-links"
),
"imports" => array(
		"title" => "Imports",
		"url"   => URL."schoolhealth_sub_admin_portal/imports",
		"icon"  => "fa-upload",
		"class" => "menu-links"
),
/*"sickroom" => array(
		"title" => "Sick Room",
		"url"   => URL."schoolhealth_sub_admin_portal/sickroom",
		"icon"  => "fa-h-square",
		"class" => "menu-links"
),
"mediinventory" => array(
		"title" => "Medical Inventory",
		"url"   => URL."schoolhealth_sub_admin_portal/medical_inventory",
		"icon"  => "fa-medkit",
		"class" => "menu-links"
),*/
"changepassword" => array(
		"title" => "Change Password",
		"url"   => URL."schoolhealth_sub_admin_portal/change_password",
		"icon"  => "fa-refresh",
		"class" => "menu-links"
),
"logout" => array(
		"title" => "Logout",
		"url"   => URL."schoolhealth_auth/logout",
		"icon"  => "fa-power-off",
		"class" => "menu-links"
)
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>