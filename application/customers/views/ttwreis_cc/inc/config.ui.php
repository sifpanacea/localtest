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
		"url" => URL."ttwreis_cc/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
 "field_officer_form" => array(
 		"title" => 'Field Officer',
 		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
				"field_officer" => array(
		"title" => "Field officer Report",
		"url" => URL."ttwreis_cc/field_officer",
		"icon" => "fa-star",
		"class" => "menu-links"
		),
		"field_officer_pie" => array(
		"title" => "Field officer pie",
		"url" => URL."ttwreis_cc/field_officer_chart",
		"icon" => "fa-star",
		"class" => "menu-links"
		),
		
		),
		),
/*"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."ttwreis_cc/pie_export",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),*/
/*"pa int_req" => array(
		"title" => "Initiate Req",
		"url" => URL."ttwreis_cc/initiate_request",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"req_extend" => array(
		"title" => "Extend Req",
		"url" => URL."ttwreis_cc/extend_request_view",
		"icon" => "fa-star",
		"class" => "menu-links"
),*/
"initiate_req" => array(
		"title" => "HS Request",
		"url" => URL."ttwreis_cc/hs_request",
		"icon" => "fa-medkit",
		"class" => "menu-links"
),
"pa submitted_requests" => array(
		"title" => "Raised Requests",
		"url" => URL."ttwreis_cc/fetch_submited_requests_docs",
		"icon" => "fa-star",
		"class" => "menu-links"
),
/*
"pa att_req" => array(
		"title" => "Attendance",
		"url" => URL."ttwreis_cc/initiate_attendance",
		"icon" => "fa-edit",
		"class" => "menu-links"
),*/
"pa att_hs_req" => array(
		"title" => "Attendance Report",
		"url" => URL."ttwreis_cc/initiateAttendanceReport",
		"icon" => "fa-edit",
		"class" => "menu-links"
),
	//==Screening Report Update and Create=======================
	
"screening_report" => array(
		"title" => "Screening Data",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
			/*	"create_screening" => array(
						"title" => "Create Screening Report",
						"url" => URL."panacea_cc/create_screening_info_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),*/
				"update_screening" => array(
						"title" => "Update Screening Report",
						"url" => URL."ttwreis_cc/update_student_info_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
),


/*"pa sani_report" => array(
		"title" => "Sanitation Report",
		"url" => URL."ttwreis_cc/initiate_sanitation_report",
		"icon" => "fa-copy",
		"class" => "menu-links"
),*/
"pa chronic_report" => array(
		"title" => "Chronic Case Report",
		"url" => URL."ttwreis_cc/list_chronic_cases",
		"icon" => "fa-tags",
		"class" => "menu-links"
),
"pa feed_bmi" => array(
 		"title" => 'BMI',
 		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
				/*"feed_bmi" => array(
				"title" => "Feed BMI",
				"url" => URL."ttwreis_cc/feed_bmi_student",
				"icon" => "fa-user",
				"class" => "menu-links",
		),
		"show_bmi_graph" => array(
		"title" => "Show BMI Graph",
		"url" => URL."ttwreis_cc/show_bmi_student",
		"icon" => "fa-user",
		"class" => "menu-links",
		),*/
		"feed_bmi_report" => array(
			"title" => "Feed BMI",
			"url" => URL."ttwreis_cc/initiateBmiReport",
			"icon" => "fa-tags",
			"class" => "menu-links",
			),
			"show_bmi_graph" => array(
			"title" => "Show BMI Graph",
			"url" => URL."ttwreis_cc/show_bmi_student",
			"icon" => "fa-user",
			"class" => "menu-links",
			),
			/*"Monthly BMI" => array(
			"title" => "Monthly BMI",
			"url" => URL."ttwreis_cc/ttwreis_imports_bmi_values",
			"icon" => "fa-star",
			"class" => "menu-links",
			),*/

		),
		),
  "hb_reports" => array(
			"title" => 'HB Report',
			"icon" => "fa-group",
			"class" => "menu-links",
			"sub" => array(
					"hemoglobin_view" => array(
						"title" => "Feed HB",
						"url" => URL."ttwreis_cc/initiateHemoglobinReport",
						"icon" => "fa-tags",
						"class" => "menu-links"
					),
					"hemoglobin_graph" => array(
						"title" => "Show HB Graph",
						"url" => URL."ttwreis_cc/show_hb_student",
						"icon" => "fa-file-text",
						"class" => "menu-links",
					),
					"Monthly HB" => array(
					"title" => "Monthly HB",
					"url" => URL."ttwreis_cc/ttwreis_imports_hb_values",
					"icon" => "fa-star",
					"class" => "menu-links",
					),
		)
	),
// 		"pa mgmt" => array(
// 		"title" => 'Masters',
// 		"icon" => "fa-group",
// 		"class" => "menu-links",
// 		"sub" => array(
// 			"schools" => array(
// 				"title" => "Manage Schools",
// 				"url" => URL."ttwreis_cc/ttwreis_cc_schools",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
			
// 			"hospitals" => array(
// 				"title" => "Manage Hospitals",
// 				"url" => URL."ttwreis_cc/ttwreis_cc_hospitals",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
// 			"symptom" => array(
// 				"title" => "Manage Symptom",
// 				"url" => URL."ttwreis_cc/ttwreis_cc_symptoms",
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
				"url" => URL."ttwreis_cc/ttwreis_cc_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "District Reports",
				"url" => URL."ttwreis_cc/ttwreis_cc_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"school" => array(
				"title" => "School Reports",
				"url" => URL."ttwreis_cc/ttwreis_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Class Reports",
				"url" => URL."ttwreis_cc/ttwreis_cc_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Section Reports",
				"url" => URL."ttwreis_cc/ttwreis_cc_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."ttwreis_cc/ttwreis_cc_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Health Supervisors Reports",
				"url" => URL."ttwreis_cc/ttwreis_cc_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
					"title" => "Diagnostic Reports",
					"url" => URL."ttwreis_cc/ttwreis_cc_diagnostic",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."ttwreis_cc/ttwreis_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."ttwreis_cc/ttwreis_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."ttwreis_cc/ttwreis_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."ttwreis_cc/ttwreis_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."ttwreis_cc/ttwreis_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
					"title" => "Employee Reports",
					"url" => URL."ttwreis_cc/ttwreis_cc_emp",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			),
			),
		
	//==ttwreis imports=====================
		"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-inbox",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."ttwreis_cc/ttwreis_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."ttwreis_cc/ttwreis_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."ttwreis_cc/ttwreis_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."ttwreis_cc/ttwreis_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."ttwreis_cc/ttwreis_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"update_student_info" => array(
								"title" => "Update Student Profile",
								"url" => URL."ttwreis_cc/ttwreis_update_ehr",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
		),
	
	//==pasasia menu =======================
	
	"news_feed" => array(
		"title" => "News Feeds",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"add_nf" => array(
						"title" => "Add News Feed",
						"url" => URL."ttwreis_cc/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."ttwreis_cc/manage_news_feed_view",
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