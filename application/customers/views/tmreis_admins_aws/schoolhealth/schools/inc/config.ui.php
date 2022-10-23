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
		"url" => URL."schoolhealth_auth/dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"masters" => array(
"title" => 'Masters',
"icon" => "fa-tachometer",
"class" => "menu-links",
"sub" => array(
	"classes" => array(
		"title" => "Manage Classes",
		"url" => URL."schoolhealth_school_portal/classes",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	"sections" => array(
		"title" => "Manage Sections",
		"url" => URL."schoolhealth_school_portal/sections",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	/*"staffs" => array(
		"title" => "Manage Staffs",
		"url" => URL."schoolhealth_school_portal/staffs",
		"icon" => "fa-star",
		"class" => "menu-links",
	),*/
	"students" => array(
		"title" => "Create Student",
		"url" => URL."schoolhealth_school_portal/create_student",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
)
),
"imports" => array(
"title" => 'Imports',
"icon" => "fa-download",
"class" => "menu-links",
"sub" => array(
	"students" => array(
		"title" => "Students",
		"url" => URL."schoolhealth_school_portal/imports_option_students",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	/*"staff" => array(
		"title" => "Staffs",
		"url" => URL."schoolhealth_school_portal/imports_option_staffs",
		"icon" => "fa-star",
		"class" => "menu-links",
	),*/
)
),
"reports" => array(
"title" => 'Reports',
"icon" => "fa-group",
"class" => "menu-links",
"sub" => array(
	"student" => array(
		"title" => "Students Report",
		"url" => URL."schoolhealth_school_portal/student_reports",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	/*"staff" => array(
		"title" => "Staffs Report",
		"url" => URL."schoolhealth_school_portal/staff_reports",
		"icon" => "fa-male",
		"class" => "menu-links",
	),*/
	"ehr" => array(
		"title" => "Electronic Health Record",
		"url" => URL."schoolhealth_school_portal/reports_ehr",
		"icon" => "fa-file-text",
		"class" => "menu-links",
	),
)
),
/*"pieexport" => array(
		"title" => "Pie Export",
		"url" => URL."schoolhealth_school_portal/export_pie",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
"sickroom" => array(
		"title" => "Sick Room",
		"url" => URL."schoolhealth_school_portal/sickroom",
		"icon" => "fa-h-square",
		"class" => "menu-links"
),
"mediinventory" => array(
		"title" => "Medical Inventory",
		"url" => URL."schoolhealth_school_portal/medical_inventory",
		"icon" => "fa-medkit",
		"class" => "menu-links"
),*/
"changepassword" => array(
		"title" => "Change Password",
		"url" => URL."schoolhealth_school_portal/change_password",
		"icon" => "fa-refresh",
		"class" => "menu-links"
),
"logout" => array(
		"title" => "Logout",
		"url" => URL."schoolhealth_auth/logout",
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