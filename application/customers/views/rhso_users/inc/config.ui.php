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
/*"pa home" => array(
		"title" => "Dashboard",
		"url" => URL."rhso_users/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),*/
"pa basic_dashboard" => array(
		"title" => "Dashboard",
		"url" => URL."rhso_users/basic_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"import_rhso_report_xl" => array(
		"title" => "Import XL Report",
		
		"icon" => "fa-bar-chart-o", 
		"class" => "menu-links",
		"sub" => array(
				/*"Single Application RHSO Submit" => array(
						"title" => "Single Application RHSO",
						"url" => URL."rhso_users/sigle_application_rhso_form",
						"icon" => "fa-star",
						"class" => "menu-links",
				),*/
				"import_XL_Report" => array(
						"title" => "Import XL Report",
						"url" => URL."rhso_users/import_rhso_report_xl",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
			),
),

"chronic_report_graph" => array(
		"title" => "Chronic Report",
		"url" => URL."rhso_users/chronic_report_graph",
		"icon" => "fa-bar-chart-o", 
		"class" => "menu-links"
),
"BMI_Graph" => array(
		"title" => "BMI Report",
		"url" => URL."rhso_users/show_bmi_graph",
		"icon" => "fa-tachometer",
		"class" => "menu-links"
),
/*
"HB_Pie" => array(
		"title" => "HB Submitted List",
		"url" => URL."rhso_users/show_hb_submitted_list",
		"icon" => "fa-tachometer",
		"class" => "menu-links"
),*/

/* "rhso_reports" => array(
		"title" => "RHSO Reports",
		"url" => URL."rhso_users/rhso_reports",
		"icon" => "fa-tachometer",
		"class" => "menu-links"
), */

"rhso_reports" => array(
		"title" => "RHSO Reports",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"sanitation_inspection" => array(
						"title" => "Sanitation Inspection",
						"url" => URL."rhso_users/sanitation_inspection_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),

				"civil_infrastructure" => array(
						"title" => "Civil and Infrastructure",
						"url" => URL."rhso_users/civil_and_infrastructure_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),

				"health_inspector_inspection" => array(
						"title" => "Health Inspector Inspection",
						"url" => URL."rhso_users/health_inspector_inspection_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"food_hygiene_inspection" => array(
						"title" => "Food and Hygiene Inspection",
						"url" => URL."rhso_users/food_hygiene_inspection_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
),

"Sanitation Inspection" => array(
		"title" => "Sanitation Inspection",
		"url" => URL."rhso_users/sanitation_inspection",
		"icon" => "fa-check-square",
		"class" => "menu-links"
),

/* "Civil Infrastructure" => array(
		"title" => "Civil And Infrastructure",
		"url" => URL."rhso_users/civil_infrastructure",
		"icon" => "fa-check-square-o", 
		"class" => "menu-links"
), */

"Civil Infrastructure" => array(
		"title" => "Civil And Infrastructure",
		"url" => URL."rhso_users/civil_infrastructure_form",
		"icon" => "fa-check-square-o", 
		"class" => "menu-links"
),

"HEALTH INSPECTION" => array(
		"title" => "Health Inspection",
		"url" => URL."rhso_users/checklist_inspectors",
		"icon" => "fa-h-square",
		"class" => "menu-links"
),
"Food_hygiene_inspection" => array(
		"title" => "Food And Hygiene Inspection",
		"url" => URL."rhso_users/foodHygieneInspection",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
/* "Food Hygiene Inspection" => array(
		"title" => "Food And Hygiene Inspection",
		"url" => URL."rhso_users/food_hygiene_inspection",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
), */

/* "attendance_report" => array(
		"title" => "Attendance Report",
		"url" => URL."rhso_users/attendance_report",
		"icon" => "fa-check-square",
		"class" => "menu-links"
), */
"change password" => array(
		"title" => "Change Password",
		"url" => URL."rhso_users/change_password",
		"icon" => "fa-exchange",
		"class" => "menu-links"
),
		/*"pa mgmt" => array(
		"title" => 'Masters',
		"icon" => "fa-tachometer",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "Manage States",
				"url" => URL."panacea_mgmt/panacea_mgmt_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "Manage District",
				"url" => URL."panacea_mgmt/panacea_mgmt_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
				"title" => "Manage Diagnostic",
				"url" => URL."panacea_mgmt/panacea_mgmt_diagnostic",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"schools" => array(
				"title" => "Manage Schools",
				"url" => URL."panacea_mgmt/panacea_mgmt_schools",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Manage Classes",
				"url" => URL."panacea_mgmt/panacea_mgmt_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Manage Sections",
				"url" => URL."panacea_mgmt/panacea_mgmt_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."panacea_mgmt/panacea_mgmt_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Manage Health Supervisors",
				"url" => URL."panacea_mgmt/panacea_mgmt_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospitals" => array(
				"title" => "Manage Hospitals",
				"url" => URL."panacea_mgmt/panacea_mgmt_hospitals",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
				"title" => "Manage Doctors",
				"url" => URL."panacea_mgmt/panacea_mgmt_doctors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"symptom" => array(
				"title" => "Manage Symptom",
				"url" => URL."panacea_mgmt/panacea_mgmt_symptoms",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
				"title" => "Manage Employees",
				"url" => URL."panacea_mgmt/panacea_mgmt_emp",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"cc_users" => array(
					"title" => "Manage CC users",
					"url" => URL."panacea_mgmt/panacea_mgmt_cc",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			),
			),
			
	//reports===============================
	"pa reports" => array(
		"title" => 'Reports',
		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			"school" => array(
				"title" => "School Reports",
				"url" => URL."panacea_mgmt/panacea_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."panacea_mgmt/panacea_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."panacea_mgmt/panacea_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."panacea_mgmt/panacea_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."panacea_mgmt/panacea_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."panacea_mgmt/panacea_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			),
			),
		
	//==panacea imports=====================
		"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-download",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."panacea_mgmt/panacea_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."panacea_mgmt/panacea_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."panacea_mgmt/panacea_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."panacea_mgmt/panacea_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."panacea_mgmt/panacea_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
		),
	
	//==pasasia menu =======================
		"chat" => array(
				"title" => "Messaging",
				"icon" => "fa-comment-o",
				"class" => "menu-links",
				"sub" => array(
						"create_group" => array(
								"title" => "Create group",
								"url" => URL."panacea_mgmt/panacea_create_group",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"group_msg" => array(
								"title" => "Group messaging",
								"url" => URL."panacea_mgmt/group_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"user_msg" => array(
								"title" => "Single user messaging",
								"url" => URL."panacea_mgmt/user_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"multi_msg" => array(
								"title" => "Multiple user messaging",
								"url" => URL."panacea_mgmt/multi_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
				),
		),
		
		"docs_comp" => array(
		"title" => "EHR Compare",
		"url" => URL."panacea_mgmt/docs_comp_open",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),

"news_feed" => array(
		"title" => "News Feeds",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"add_nf" => array(
						"title" => "Add News Feed",
						"url" => URL."panacea_mgmt/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."panacea_mgmt/manage_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
),*/

   
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
