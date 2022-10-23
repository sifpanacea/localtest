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
		"url" => URL."bc_welfare_mgmt/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
 "basic_dashboard" => array(
		"title" => "Basic Dashboard",
		"url" => URL."bc_welfare_mgmt/basic_dashboard",
		"icon" => "fa-desktop",
		"class" => "menu-links"
), 
 "pa sanitation" => array(
		"title" => "Sanitation Report",
		"url" => URL."bc_welfare_mgmt/sanitation_report",
		"icon" => "fa-hand-o-right",
		"class" => "menu-links"
),
 "pa request" => array(
		"title" => "Monthly Health Track",
		"url" => URL."bc_welfare_mgmt/monthly_request_charts",
		"icon" => "fa-indent",
		"class" => "menu-links"
),
"request_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."bc_welfare_mgmt/chronic_pie_view",
		"icon" => "fa-heart",
		"class" => "menu-links"
),

"request_pie" => array(
		"title" => "Request Notes",
		"url" => URL."bc_welfare_mgmt/requests_notes",
		"icon" => "fa-list-alt",
		"class" => "menu-links"
),
"pa bmi" => array(
		"title" => 'BMI PIE',
		"icon" => "fa-male",
		"class" => "menu-links",
		"sub" => array(
			"bmi_monthly" => array(
					"title" => "BMI Monthly",
					"url" => URL."bc_welfare_mgmt/bmi_pie_view",
					"icon" => "fa-star",
					"class" => "menu-links"
			),
			"bmi_gender_wise" => array(
					"title" => "BMI Gender Wise",
					"url" => URL."bc_welfare_mgmt/bmi_overall_dashboard",
					"icon" => "fa-star",
					"class" => "menu-links",
					), 
 			   		),
                    ),

 "pa hb" => array(
		"title" => 'HB PIE',
		"icon" => "fa-dashboard",
		"class" => "menu-links",
		"sub" => array(
			"hb_monthly" => array(
					"title" => "HB Monthly",
					"url" => URL."bc_welfare_mgmt/hb_pie_view",
					"icon" => "fa-star",
					"class" => "menu-links"
				),			
 			   "hb_gender_wise" => array(
					"title" => "HB Gender Wise",
					"url" => URL."bc_welfare_mgmt/hb_overall_dashboard",
					"icon" => "fa-star",
					"class" => "menu-links",
					), 
 			   		),
					),
/*"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."bc_welfare_mgmt/pie_export",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),*/
/*"request_status" => array(
		"title" => "Initiated Requests Status",
		"url" 	=> URL."bc_welfare_mgmt/requests_status",
		"icon"  => "fa fa-external-link",
		"class" => "menu-links"
),*/
		"pa mgmt" => array(
		"title" => 'Masters',
		"icon" => "fa-th-large",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "Manage States",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "Manage District",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
				"title" => "Manage Diagnostic",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_diagnostic",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"schools" => array(
				"title" => "Manage Schools",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_schools",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Manage Classes",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Manage Sections",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Manage Health Supervisors",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospitals" => array(
				"title" => "Manage Hospitals",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_hospitals",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
				"title" => "Manage Doctors",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_doctors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"symptom" => array(
				"title" => "Manage Symptom",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_symptoms",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
				"title" => "Manage Employees",
				"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_emp",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"cc_users" => array(
					"title" => "Manage CC users",
					"url" => URL."bc_welfare_mgmt/bc_welfare_mgmt_cc",
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
				"url" => URL."bc_welfare_mgmt/bc_welfare_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."bc_welfare_mgmt/bc_welfare_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."bc_welfare_mgmt/bc_welfare_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."bc_welfare_mgmt/bc_welfare_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."bc_welfare_mgmt/bc_welfare_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."bc_welfare_mgmt/bc_welfare_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"DrnotRespond" => array(
				"title" => "Dr Not Respond",
				"url" => URL."bc_welfare_mgmt/bc_welfare_dr_not_respond",
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
								"url" => URL."bc_welfare_mgmt/bc_welfare_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."bc_welfare_mgmt/bc_welfare_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."bc_welfare_mgmt/bc_welfare_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."bc_welfare_mgmt/bc_welfare_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."bc_welfare_mgmt/bc_welfare_imports_students",
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
								"url" => URL."bc_welfare_mgmt/bc_welfare_create_group",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"group_msg" => array(
								"title" => "Group messaging",
								"url" => URL."bc_welfare_mgmt/group_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"user_msg" => array(
								"title" => "Single user messaging",
								"url" => URL."bc_welfare_mgmt/user_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"multi_msg" => array(
								"title" => "Multiple user messaging",
								"url" => URL."bc_welfare_mgmt/multi_msg",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
				),
		),
		
		"docs_comp" => array(
		"title" => "EHR Compare",
		"url" => URL."bc_welfare_mgmt/docs_comp_open",
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
						"url" => URL."bc_welfare_mgmt/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."bc_welfare_mgmt/manage_news_feed_view",
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
