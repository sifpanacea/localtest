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
	//==pasasia menu =======================
"pa home" => array(
		"title" => "Dashboard",
		"url" => URL."tmreis_cc/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"pa int_req" => array(
		"title" => "Initiate Req",
		"url" => URL."tmreis_cc/initiate_request",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"pa att_req" => array(
		"title" => "Attendance",
		"url" => URL."tmreis_cc/initiate_attendance",
		"icon" => "fa-edit",
		"class" => "menu-links"
),
// 		"pa mgmt" => array(
// 		"title" => 'Masters',
// 		"icon" => "fa-group",
// 		"class" => "menu-links",
// 		"sub" => array(
// 			"schools" => array(
// 				"title" => "Manage Schools",
// 				"url" => URL."tmreis_cc/tmreis_cc_schools",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
			
// 			"hospitals" => array(
// 				"title" => "Manage Hospitals",
// 				"url" => URL."tmreis_cc/tmreis_cc_hospitals",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
// 			"symptom" => array(
// 				"title" => "Manage Symptom",
// 				"url" => URL."tmreis_cc/tmreis_cc_symptoms",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			)
// 			),
// 			),
			
	//reports===============================
	"pa reports" => array(
		"title" => 'Reports',
		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "State Reports",
				"url" => URL."tmreis_cc/tmreis_cc_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "District Reports",
				"url" => URL."tmreis_cc/tmreis_cc_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"school" => array(
				"title" => "School Reports",
				"url" => URL."tmreis_cc/tmreis_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Class Reports",
				"url" => URL."tmreis_cc/tmreis_cc_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Section Reports",
				"url" => URL."tmreis_cc/tmreis_cc_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."tmreis_cc/tmreis_cc_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Health Supervisors Reports",
				"url" => URL."tmreis_cc/tmreis_cc_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
					"title" => "Diagnostic Reports",
					"url" => URL."tmreis_cc/tmreis_cc_diagnostic",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."tmreis_cc/tmreis_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."tmreis_cc/tmreis_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."tmreis_cc/tmreis_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."tmreis_cc/tmreis_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."tmreis_cc/tmreis_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
					"title" => "Employee Reports",
					"url" => URL."tmreis_cc/tmreis_cc_emp",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			),
			),
		
	//==tmreis imports=====================
		"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-inbox",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."tmreis_cc/tmreis_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."tmreis_cc/tmreis_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."tmreis_cc/tmreis_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."tmreis_cc/tmreis_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."tmreis_cc/tmreis_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
		),
	
	//==pasasia menu =======================
	
	

    "logout" => array(
		"title" => lang('common_logout'),
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