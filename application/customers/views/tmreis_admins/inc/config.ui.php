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
		"url" => URL."tmreis_mgmt/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"pa request" => array(
		"title" => "Request Pie",
		"url" => URL."tmreis_mgmt/monthly_request_charts",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"basic_dashboard" => array(
		"title" => "New Dashboard",
		"url" => URL."tmreis_mgmt/basic_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"sanitation_report" => array(
		"title" => "Sanitation Report PIE",
		"url" => URL."tmreis_mgmt/tmreis_sanitation_report",
		"icon" => "fa-pencil-square-o",
		"class" => "menu-links"
),
"sanitation_infrastructure" => array(
		"title" => "Sanitation Infrastructure",
		"url" => URL."tmreis_mgmt/tmreis_sanitation_infrastructure",
		"icon" => "fa-building",
		"class" => "menu-links"
),
"chronic_report_graph" => array(
		"title" => "Chronic Report Graph",
		"url" => URL."tmreis_mgmt/tmreis_chronic_report_graph",
		"icon" => "fa-bar-chart-o",
		"class" => "menu-links"
),
"pa field_officer" => array(
		"title" => "Field Officer Report",
		"url" => URL."tmreis_mgmt/tmreis_field_officer_report",
		"icon" => "fa-user",
		"class" => "menu-links"
),

/*"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."tmreis_mgmt/pie_export",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),*/
"tmreis_chronic_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."tmreis_mgmt/tmreis_chronic_pie_view",
		"icon" => "fa-user-md",
		"class" => "menu-links"
),
"bmi_pie" => array(
		"title" => "BMI PIE",
		"url" => URL."tmreis_mgmt/bmi_pie_view",
		"icon" => "fa-dashboard",
		"class" => "menu-links"
),
 "hb_pie" => array(
		"title" => "HB PIE",
		"url" => URL."tmreis_mgmt/hb_pie_view",
		"icon" => "fa-dashboard",
		"class" => "menu-links"
),
"request_status" => array(
		"title" => "Initiated Requests Status",
		"url" 	=> URL."tmreis_mgmt/requests_status",
		"icon"  => "fa fa-external-link",
		"class" => "menu-links"
),
		"pa mgmt" => array(
		"title" => 'Masters',
		"icon" => "fa-tachometer",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "Manage States",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "Manage District",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
				"title" => "Manage Diagnostic",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_diagnostic",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"schools" => array(
				"title" => "Manage Schools",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_schools",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Manage Classes",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Manage Sections",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."tmreis_mgmt/tmreis_mgmt_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Manage Health Supervisors",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospitals" => array(
				"title" => "Manage Hospitals",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_hospitals",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
				"title" => "Manage Doctors",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_doctors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"symptom" => array(
				"title" => "Manage Symptom",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_symptoms",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
				"title" => "Manage Employees",
				"url" => URL."tmreis_mgmt/tmreis_mgmt_emp",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"cc_users" => array(
					"title" => "Manage CC users",
					"url" => URL."tmreis_mgmt/tmreis_mgmt_cc",
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
				"url" => URL."tmreis_mgmt/tmreis_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."tmreis_mgmt/tmreis_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."tmreis_mgmt/tmreis_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."tmreis_mgmt/tmreis_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."tmreis_mgmt/tmreis_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."tmreis_mgmt/tmreis_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			),
			),
		
	//==tmreis imports=====================
		"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-download",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."tmreis_mgmt/tmreis_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."tmreis_mgmt/tmreis_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."tmreis_mgmt/tmreis_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."tmreis_mgmt/tmreis_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."tmreis_mgmt/tmreis_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						),

						"Screening file Imports" => array(
								"title" => "Screening file Import",
								"url" => URL."tmreis_mgmt/imports",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
		),
		
				"docs_comp" => array(
		"title" => "EHR Compare",
		"url" => URL."tmreis_mgmt/docs_comp_open",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
	
	//==pasasia menu =======================
	
		"news_feed" => array(
		"title" => "News Feeds",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"add_nf" => array(
						"title" => "Add News Feed",
						"url" => URL."tmreis_mgmt/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."tmreis_mgmt/manage_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
),
	
	

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