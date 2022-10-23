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
		"url" => URL."panacea_mgmt/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
 "basic_dashboard" => array(
		"title" => "New Dashboard",
		"url" => URL."panacea_mgmt/basic_dashboard",
		"icon" => "fa-desktop",
		"class" => "menu-links"
),
 "pa sanitation" => array(
		"title" => "Sanitation Report",
		"url" => URL."panacea_mgmt/sanitation_report",
		"icon" => "fa-hand-o-right",
		"class" => "menu-links"
),
"pa schools status" => array(
		"title" => "Schools Status",
		"url" => URL."panacea_mgmt/get_schools_health_status",
		"icon" => "fa-indent",
		"class" => "menu-links"
),
"disease wise students list" => array(
		"title" => "Health Track Chart",
		"url" => URL."panacea_mgmt/disease_wise_students_list",
		"icon" => "fa-plus-circle",
		"class" => "menu-links"
),
/*"pa request" => array(
		"title" => "Request Pie",
		"url" => URL."panacea_mgmt/monthly_request_charts",
		"icon" => "fa-home",
		"class" => "menu-links"
),*/ 
"request_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."panacea_mgmt/chronic_pie_view",
		"icon" => "fa-heart",
		"class" => "menu-links"
),

"hospitalized_pie" => array(
		"title" => "Hospitalized PIE",
		"url" => URL."panacea_mgmt/hospitalized_pie_view",
		"icon" => "fa-user-md",
		"class" => "menu-links"
),
"pa bmi" => array(
		"title" => 'BMI PIE',
		"icon" => "fa-th-large",
		"class" => "menu-links",
		"sub" => array(
			 "bmi_monthly" => array(
					"title" => "BMI Monthly",
					"url" => URL."panacea_mgmt/bmi_pie_view",
					"icon" => "fa-star",
					"class" => "menu-links"
				     ),
			 "bmi_gender_wise" => array(
					"title" => "BMI Gender Wise",
					"url" => URL."panacea_mgmt/bmi_overall_dashboard",
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
					"url" => URL."panacea_mgmt/hb_pie_view",
					"icon" => "fa-star",
					"class" => "menu-links",
					), 
 			   "hb_gender_wise" => array(
					"title" => "HB Gender Wise",
					"url" => URL."panacea_mgmt/hb_overall_dashboard",
					"icon" => "fa-star",
					"class" => "menu-links",
					), 
 			   		),
					),

/*"rhso_reports" => array(
		"title" => "RHSO Reports",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"sanitation_inspection" => array(
						"title" => "Sanitation Inspection",
						"url" => URL."panacea_mgmt/sanitation_inspection_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),

				"civil_infrastructure" => array(
						"title" => "Civil and Infrastructure",
						"url" => URL."panacea_mgmt/civil_and_infrastructure_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),

				"health_inspector_inspection" => array(
						"title" => "Health Inspector Inspection",
						"url" => URL."panacea_mgmt/health_inspector_inspection_report",
						"icon" => "fa-star",
						"class" => "menu-links",
				),

				"food_hygiene_inspection" => array(
						"title" => "Food and Hygiene Inspection",
						"url" 	=> URL."panacea_mgmt/food_hygiene_inspection_report",
						"icon"	=> "fa-star",
						"class" => "menu-links",
				),
				"show_yearly_graph" => array(
					'title' => "Yearly HB",
					'url'	=> URL.'panacea_mgmt/show_yearly_hb_graph',
					'icon'  => "fa-star",
					'class' => 'menu-links',
				),
		),
),*/


"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."panacea_mgmt/pie_export",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
/*"request_status" => array(
		"title" => "Initiated Requests Status",
		"url" 	=> URL."panacea_mgmt/requests_status",
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
						
						"Screening file Imports" => array(
								"title" => "Screening file Import",
								"url" => URL."panacea_mgmt/imports",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						/*"Monthly BMI" => array(
								"title" => "Monthly BMI",
								"url" => URL."panacea_mgmt/panacea_imports_bmi_values",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"Monthly HB" => array(
								"title" => "Monthly HB",
								"url" => URL."panacea_mgmt/panacea_imports_hb_values",
								"icon" => "fa-star",
								"class" => "menu-links",
						),*/
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
						"sending_msg" => array(
								"title" => "SMS List",
								"url" => URL."panacea_mgmt/send_sms_list",
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
),

	"contact_numbers" => array(
		"title" => "Contact Numbers",
		"url" => URL."panacea_mgmt/contact_numbers",
		"icon" => "fa-phone",
		"class" => "menu-links"
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
