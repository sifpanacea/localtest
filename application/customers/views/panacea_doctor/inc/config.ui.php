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
		"url" => URL."panacea_doctor/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
), 
 "request_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."panacea_doctor/chronic_pie_view",
		"icon" => "fa-user-md",
		"class" => "menu-links"
), */
 "pa int_req" => array(
		"title" => "Requests Doc's",
		"url" => URL."panacea_doctor/fetch_request_docs_from_hs_list",
		"icon" => "fa-star",
		"class" => "menu-links"
), 

"pa submited_req" => array(
		"title" => "Submited Doc's",
		"url" => URL."panacea_doctor/submitted_request_docs_doctor",
		"icon" => "fa-copy",
		"class" => "menu-links"
),

"edit_submitted_request" => array(
	"title" => "Edit Submitted Request",
	"url" => URL."panacea_doctor/edit_submitted_request_docs",
	"icon" => "fa-edit",
	"class" => "menu-links"
),

"submitted_parents" => array(
		"title" => "Parents Submitted Doc's",
		"url" => URL."panacea_doctor/get_parent_submitted_details",
		"icon" => "fa-group",
		"class" => "menu-links"
),

/*"testing" => array(
	"title" => "Analysis",
	"url" => URL."panacea_doctor/doctor_analysis",
	"icon" => "fa-edit",
	"class" => "menu-links"
),*/


/*"regular_followup" => array(
		"title" => "Regular Followups",
		"url" => URL."panacea_doctor/regular_followup_students",
		"icon" => "fa-file-text",
		"class" => "menu-links",
	),*/

// "pie_export" => array(
// 		"title" => "Pie Export",
// 		"url" => URL."panacea_doctor/pie_export",
// 		"icon" => "fa-dribbble",
// 		"class" => "menu-links"
// ),
/* "pa int_req" => array(
		"title" => "Initiate Req",
		"url" => URL."panacea_doctor/initiate_request",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"req_extend" => array(
		"title" => "Extend Req",
		"url" => URL."panacea_doctor/extend_request_view",
		"icon" => "fa-star",
		"class" => "menu-links"
), */
// "pa att_req" => array(
// 		"title" => "Attendance",
// 		"url" => URL."panacea_doctor/initiate_attendance",
// 		"icon" => "fa-edit",
// 		"class" => "menu-links"
// ),
// "pa sani_report" => array(
// 		"title" => "Sanitation Report",
// 		"url" => URL."panacea_doctor/initiate_sanitation_report",
// 		"icon" => "fa-copy",
// 		"class" => "menu-links"
// ),
"pa chronic_report" => array(
		"title" => "Chronic Case Report",
		"url" => URL."panacea_doctor/list_chronic_cases",
		"icon" => "fa-tags",
		"class" => "menu-links"
),
 // "pa feed_bmi" => array(
 // 		"title" => 'BMI',
 // 		"icon" => "fa-group",
	// 	"class" => "menu-links",
	// 	"sub" => array(
	// 			"feed_bmi" => array(
	// 			"title" => "Feed BMI",
	// 			"url" => URL."panacea_doctor/feed_bmi_student",
	// 			"icon" => "fa-user",
	// 				"class" => "menu-links",
	// 	),
	// 	"show_bmi_graph" => array(
	// 	"title" => "Show BMI Graph",
	// 	"url" => URL."panacea_doctor/show_bmi_student",
	// 	"icon" => "fa-user",
	// 	"class" => "menu-links",
	// 	)
	// 	),
	// 	),
// 		"pa mgmt" => array(
// 		"title" => 'Masters',
// 		"icon" => "fa-group",
// 		"class" => "menu-links",
// 		"sub" => array(
// 			"schools" => array(
// 				"title" => "Manage Schools",
// 				"url" => URL."panacea_doctor/panacea_cc_schools",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
			
// 			"hospitals" => array(
// 				"title" => "Manage Hospitals",
// 				"url" => URL."panacea_doctor/panacea_cc_hospitals",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
// 			"symptom" => array(
// 				"title" => "Manage Symptom",
// 				"url" => URL."panacea_doctor/panacea_cc_symptoms",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			)
// 			),
// 			),
			
	//reports===============================
	/*"pa reports" => array(
		"title" => 'Reports',
		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "State Reports",
				"url" => URL."panacea_doctor/panacea_cc_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "District Reports",
				"url" => URL."panacea_doctor/panacea_cc_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"school" => array(
				"title" => "School Reports",
				"url" => URL."panacea_doctor/panacea_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Class Reports",
				"url" => URL."panacea_doctor/panacea_cc_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Section Reports",
				"url" => URL."panacea_doctor/panacea_cc_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."panacea_doctor/panacea_cc_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Health Supervisors Reports",
				"url" => URL."panacea_doctor/panacea_cc_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
					"title" => "Diagnostic Reports",
					"url" => URL."panacea_doctor/panacea_cc_diagnostic",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."panacea_doctor/panacea_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."panacea_doctor/panacea_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."panacea_doctor/panacea_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."panacea_doctor/panacea_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."panacea_doctor/panacea_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
					"title" => "Employee Reports",
					"url" => URL."panacea_doctor/panacea_cc_emp",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			),
			),*/
		
	//==panacea imports=====================
	/*	"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-inbox",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."panacea_doctor/panacea_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."panacea_doctor/panacea_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."panacea_doctor/panacea_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."panacea_doctor/panacea_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						 "student" => array(
								"title" => "Students",
								"url" => URL."panacea_doctor/panacea_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						), 
						"update_student_info" => array(
								"title" => "Update Student Profile",
								"url" => URL."panacea_doctor/panacea_update_ehr",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
		),*/
	
	//==pasasia menu =======================
	
	/* "news_feed" => array(
		"title" => "News Feeds",
		"icon" => "fa-list-alt",
		"class" => "menu-links",
		"sub" => array(
				"add_nf" => array(
						"title" => "Add News Feed",
						"url" => URL."panacea_doctor/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."panacea_doctor/manage_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
), */
	
	

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
