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
		"url" => URL."panacea_cc/to_dashboard",
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
		"url" => URL."panacea_cc/field_officer",
		"icon" => "fa-star",
		"class" => "menu-links"
		),
		"field_officer_pie" => array(
		"title" => "Field officer pie",
		"url" => URL."panacea_cc/field_officer_chart",
		"icon" => "fa-star",
		"class" => "menu-links"
		),
		
		),
		),
"request_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."panacea_cc/chronic_pie_view",
		"icon" => "fa-user-md",
		"class" => "menu-links"
),
"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."panacea_cc/pie_export",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
"pa att_hs_req" => array(
		"title" => "Attendance Report",
		"url" => URL."panacea_cc/initiateAttendanceReport",
		"icon" => "fa-edit",
		"class" => "menu-links"
),/*
"pa int_req" => array(
		"title" => "Initiate Req",
		"url" => URL."panacea_cc/initiate_request",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"req_extend" => array(
		"title" => "Extend Req",
		"url" => URL."panacea_cc/extend_request_view",
		"icon" => "fa-star",
		"class" => "menu-links"
),*/
"initiate_req" => array(
		"title" => "HS Request",
		"url" => URL."panacea_cc/hs_request",
		"icon" => "fa-medkit",
		"class" => "menu-links"
),
"pa submitted_requests" => array(
		"title" => "Raised Requests",
		"url" => URL."panacea_cc/fetch_submited_requests_docs",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"pa regular_followups" => array(
		"title" => "Regular Followup",
		"url" => URL."panacea_cc/regular_followups_list",
		"icon" => "fa-star",
		"class" => "menu-links"
),
/*"pa notes_cc" => array(
		"title" => "Notes",
		"url" => URL."panacea_cc/notes_for_cc",
		"icon" => "fa-star",
		"class" => "menu-links"
),*/
 "pa notes_cc" => array(
 		"title" => 'Notes',
 		"icon" => "fa-group",
		"class" => "menu-links",
	"sub" => array(
			"raise_notes" => array(
			"title" => "Raise Notes",
			"url" => URL."panacea_cc/notes_for_cc",
			"icon" => "fa-star",
			"class" => "menu-links"
			),
			"saved_notes" => array(
			"title" => "Saved Notes",
			"url" => URL."panacea_cc/saved_notes_for_cc",
			"icon" => "fa-star",
			"class" => "menu-links"
			),
	
		),
),
"student_photo" => array(
		"title" => "Student Photo",
		"url" => URL."panacea_cc/get_student_photo_details",
		"icon" => "fa-star",
		"class" => "menu-links"
),
"other_classes" => array(
		"title" => "Other Classes",
		"url" => URL."panacea_cc/get_other_classes_details",
		"icon" => "fa-star",
		"class" => "menu-links"
),

/*
"pa att_req" => array(
		"title" => "Attendance",
		"url" => URL."panacea_cc/initiate_attendance",
		"icon" => "fa-edit",
		"class" => "menu-links"
),
"pa sani_report" => array(
		"title" => "Sanitation Report",
		"url" => URL."panacea_cc/initiate_sanitation_report",
		"icon" => "fa-copy",
		"class" => "menu-links"
),
"pa chronic_report" => array(
		"title" => "Chronic Case Report",
		"url" => URL."panacea_cc/list_chronic_cases",
		"icon" => "fa-tags",
		"class" => "menu-links"
),*/
 "pa feed_bmi" => array(
 		"title" => 'BMI',
 		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
				"feed_bmi_student" => array(
				"title" => "Feed BMI",
				"url" => URL."panacea_cc/initiateBmiReport",
				"icon" => "fa-user",
					"class" => "menu-links",
		),
		"show_bmi_graph" => array(
		"title" => "Show BMI Graph",
		"url" => URL."panacea_cc/show_bmi_student",
		"icon" => "fa-user",
		"class" => "menu-links",
		),
		"Monthly BMI" => array(
								"title" => "Monthly BMI",
								"url" => URL."panacea_cc/panacea_imports_bmi_values",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
		),
		),

 //
 //==========hb===form===
  "hb_reports" => array(
"title" => 'HB Report',
"icon" => "fa-group",
"class" => "menu-links",
"sub" => array(
		"hemoglobin_view" => array(
			"title" => "Feed HB",
			"url" => URL."panacea_cc/initiateHemoglobinReport",
			"icon" => "fa-tags",
			"class" => "menu-links"
		),
		"hemoglobin_graph" => array(
			"title" => "Show HB Graph",
			"url" => URL."panacea_cc/show_hb_student",
			"icon" => "fa-file-text",
			"class" => "menu-links",
		),
		"Monthly HB" => array(
					"title" => "Monthly HB",
					"url" => URL."panacea_cc/panacea_imports_hb_values",
					"icon" => "fa-star",
					"class" => "menu-links",
					),
	)
),



//    old-form version 1
 /*"pa feed_bmi" => array(
 		"title" => 'BMI',
 		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
				"feed_bmi" => array(
				"title" => "Feed BMI",
				"url" => URL."panacea_cc/feed_bmi_student",
				"icon" => "fa-user",
					"class" => "menu-links",
		),
		"show_bmi_graph" => array(
		"title" => "Show BMI Graph",
		"url" => URL."panacea_cc/show_bmi_student",
		"icon" => "fa-user",
		"class" => "menu-links",
		)
		),
		),*/

// 		"pa mgmt" => array(
// 		"title" => 'Masters',
// 		"icon" => "fa-group",
// 		"class" => "menu-links",
// 		"sub" => array(
// 			"schools" => array(
// 				"title" => "Manage Schools",
// 				"url" => URL."panacea_cc/panacea_cc_schools",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
			
// 			"hospitals" => array(
// 				"title" => "Manage Hospitals",
// 				"url" => URL."panacea_cc/panacea_cc_hospitals",
// 				"icon" => "fa-star",
// 				"class" => "menu-links",
// 			),
// 			"symptom" => array(
// 				"title" => "Manage Symptom",
// 				"url" => URL."panacea_cc/panacea_cc_symptoms",
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
				"url" => URL."panacea_cc/panacea_cc_states",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "District Reports",
				"url" => URL."panacea_cc/panacea_cc_district",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"school" => array(
				"title" => "School Reports",
				"url" => URL."panacea_cc/panacea_reports_school",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Class Reports",
				"url" => URL."panacea_cc/panacea_cc_classes",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Section Reports",
				"url" => URL."panacea_cc/panacea_cc_sections",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			// "doctors" => array(
				// "title" => "Manage Doctors",
				// "url" => URL."panacea_cc/panacea_cc_doctors",
				// "icon" => "fa-star",
				// "class" => "menu-links",
			// ),
			"health supervisors" => array(
				"title" => "Health Supervisors Reports",
				"url" => URL."panacea_cc/panacea_cc_health_supervisors",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
					"title" => "Diagnostic Reports",
					"url" => URL."panacea_cc/panacea_cc_diagnostic",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."panacea_cc/panacea_reports_hospital",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."panacea_cc/panacea_reports_doctors",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."panacea_cc/panacea_reports_symptom",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."panacea_cc/panacea_reports_students_filter",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."panacea_cc/panacea_reports_ehr",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
					"title" => "Employee Reports",
					"url" => URL."panacea_cc/panacea_cc_emp",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			),
			),
		
	//==panacea imports=====================
		"pa imports" => array(
				"title" => 'Imports',
				"icon" => "fa-inbox",
				"class" => "menu-links",
				"sub" => array(
						"diagnostic" => array(
								"title" => "Diagnostics",
								"url" => URL."panacea_cc/panacea_imports_diagnostic",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."panacea_cc/panacea_imports_hospital",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."panacea_cc/panacea_imports_school",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."panacea_cc/panacea_imports_health_supervisors",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						/* "student" => array(
								"title" => "Students",
								"url" => URL."panacea_cc/panacea_imports_students",
								"icon" => "fa-star",
								"class" => "menu-links",
						), */
						"update_student_info" => array(
								"title" => "Update Student Profile",
								"url" => URL."panacea_cc/panacea_update_ehr",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
				),
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
						"url" => URL."panacea_cc/update_student_info_view",
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
						"url" => URL."panacea_cc/add_news_feed_view",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."panacea_cc/manage_news_feed_view",
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
