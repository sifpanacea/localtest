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
"ehr" => array(
		"title" => "Electronic Health Record",
		"url"   => URL."schoolhealth_admin_portal/search_ehr",
		"icon"  => "fa-file-text",
		"class" => "menu-links"
),
"masters" => array(
"title" => 'Masters',
"icon" => "fa-tachometer",
"class" => "menu-links",
"sub" => array(
	"state" => array(
		"title" => "Manage State",
		"url" => URL."schoolhealth_admin_portal/manage_state",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	"district" => array(
		"title" => "Manage District",
		"url" => URL."schoolhealth_admin_portal/manage_district",
		"icon" => "fa-star",
		"class" => "menu-links",
	)
)
),
"sub admin management" => array(
"title" => 'Manage Sub Admins',
"icon" => "fa-user",
"class" => "menu-links",
"sub" => array(
	"sub admin list" => array(
		"title" => "List Sub Admin",
		"url" => URL."schoolhealth_admin_portal/list_sub_admin",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	"add_sub_admin" => array(
		"title" => "Add Sub Admin",
		"url" => URL."schoolhealth_admin_portal/add_sub_admin",
		"icon" => "fa-star",
		"class" => "menu-links",
	),

	"activate_subadmin" => array(
		"title" => "Activate/Deactivate Sub Admins",
		"url" => URL."schoolhealth_admin_portal/list_sub_admins_with_status_controls",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	
)
),

"school management" => array(
"title" => 'Manage Schools',
"icon" => "fa-building",
"class" => "menu-links",
"sub" => array(
	"list_school" => array(
		"title" => "List School",
		"url" => URL."schoolhealth_admin_portal/list_school",
		"icon" => "fa-flash",
		"class" => "menu-links",
	),
	"add_school" => array(
		"title" => "Add School",
		"url" => URL."schoolhealth_admin_portal/add_school",
		"icon" => "fa-flash",
		"class" => "menu-links",
	),
	"control_school" => array(
		"title" => "Activate/Deactivate School",
		"url" => URL."schoolhealth_admin_portal/list_schools_with_status_controls",
		"icon" => "fa-flash",
		"class" => "menu-links",
	)
)
),

"clinic management" => array(
"title" => 'Manage Clinics',
"icon" => "fa-hospital",
"class" => "menu-links",
"sub" => array(
	"list_clinic" => array(
		"title" => "List Clinic",
		"url" => URL."schoolhealth_admin_portal/list_clinic",
		"icon" => "fa-asterisk",
		"class" => "menu-links",
	),
	"create_clinic" => array(
		"title" => "Add Clinic",
		"url" => URL."schoolhealth_admin_portal/add_clinic",
		"icon" => "fa-asterisk",
		"class" => "menu-links",
	),
	"control_clinic" => array(
		"title" => "Activate/Deactivate Clinic",
		"url" => URL."schoolhealth_admin_portal/list_clinics_with_status_controls",
		"icon" => "fa-asterisk",
		"class" => "menu-links",
	)
)
),

"changepassword" => array(
		"title" => "Change Password",
		"url" => URL."schoolhealth_admin_portal/change_password",
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