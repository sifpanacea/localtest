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
		"url" => URL."tswreis_schools/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"pa Activities" => array(
		"title" => "Basic Dashboard",
		"url" => URL."tswreis_schools/all_activities",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"weekly_doctor_visit" => array(
		"title" => "Doctor visit",
		"url" => URL."tswreis_schools/doctor_visit",
		"icon" => "fa-medkit",
		"class" => "menu-links"
),
"treatment_advice" => array(
	"title" => "Treatment Advice(Eye)",
	"url"   => URL."tswreis_schools/get_treatment_advice_list",
	"icon"  => "fa fa-user",
	"class" => "menu-links"
),
/*"pa int_req" => array(
		"title" => "Initiate Req",
		"url" => URL."tswreis_schools/initiate_request",
		"icon" => "fa-star",
		"class" => "menu-links"
),*/
/* "extend_request" => array(
		"title" => "Extend Req",
		"url" => URL."tswreis_schools/extend_request",
		"icon" => "fa-star",
		"class" => "menu-links"
), */
/* "pa att_req" => array(
		"title" => "Attendance",
		"url" => URL."tswreis_schools/initiate_attendance",
		"icon" => "fa-edit",
		"class" => "menu-links"
), */


"initiate_req" => array(
		"title" => "HS Request",
		"url" => URL."tswreis_schools/hs_request",
		"icon" => "fa-medkit",
		"class" => "menu-links"
),

"pa submitted_requests" => array(
		"title" => "Raised Requests",
		"url" => URL."tswreis_schools/fetch_submited_requests_docs",
		"icon" => "fa-star",
		"class" => "menu-links"
),

"pa att_hs_req" => array(
		"title" => "Attendance Report",
		"url" => URL."tswreis_schools/initiateAttendanceReport",
		"icon" => "fa-edit",
		"class" => "menu-links"
), 

/*"pa sani_report" => array(
		"title" => "Sanitation Report",
		"url" => URL."tswreis_schools/initiate_sanitation_report",
		"icon" => "fa-copy",
		"class" => "menu-links"
),*/
 "pa sani_report_new" => array(
		"title" => "Sanitation Report New",
		"url" => URL."tswreis_schools/initiateSanitationReport",
		"icon" => "fa-copy",
		"class" => "menu-links"
), 
"pa chronic_report" => array(
		"title" => "Chronic Case Report",
		"url" => URL."tswreis_schools/list_chronic_cases",
		"icon" => "fa-tags",
		"class" => "menu-links"
),
	"pa feed_bmi" => array(
 		"title" => 'BMI Report',
 		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			/* "feed_bmi" => array(
				"title" => "Feed BMI",
				"url" => URL."tswreis_schools/feed_bmi_student",
				"icon" => "fa-user",
				"class" => "menu-links",
			), */
			"feed_bmi_report" => array(
				"title" => "Feed BMI",
				"url" => URL."tswreis_schools/initiateBmiReport",
				"icon" => "fa-tags",
				"class" => "menu-links",
			),
			
			"show_bmi_graph" => array(
				"title" => "Show BMI Graph",
				"url" => URL."tswreis_schools/show_bmi_student",
				"icon" => "fa fa-bar-chart-o",
				"class" => "menu-links",
			) ,
			"Monthly BMI" => array(
								"title" => "Monthly BMI",
								"url" => URL."tswreis_schools/panacea_imports_bmi_values",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
			"bmi_pie_view" => array(
				"title" => "BMI PIE",
				"url" => URL."tswreis_schools/bmi_pie_view",
				"icon" => "fa-dashboard",
				"class" => "menu-links"
			) 
			
						
		),
	),
		
"hb_reports" => array(
"title" => 'HB Report',
"icon" => "fa-group",
"class" => "menu-links",
"sub" => array(
		"hemoglobin_view" => array(
			"title" => "Feed HB",
			"url" => URL."tswreis_schools/initiateHemoglobinReport",
			"icon" => "fa-tags",
			"class" => "menu-links"
		),
		"hemoglobin_graph" => array(
			"title" => "Show HB Graph",
			"url" => URL."tswreis_schools/show_hb_student",
			"icon" => "fa fa-bar-chart-o",
			"class" => "menu-links",
		),
		"Monthly HB" => array(
			"title" => "Monthly HB",
			"url" => URL."tswreis_schools/panacea_imports_hb_values",
			"icon" => "fa-star",
			"class" => "menu-links",
		),
		"hb_pie_view" => array(
				"title" => "HB PIE",
				"url" => URL."tswreis_schools/hb_pie_view",
				"icon" => "fa-dashboard",
				"class" => "menu-links"
			),
	),
),

"masters" => array(
"title" => 'Masters',
"icon" => "fa-tachometer",
"class" => "menu-links",
"sub" => array(
	"classes" => array(
		"title" => "Manage Classes",
		"url" => URL."tswreis_schools/classes",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	"sections" => array(
		"title" => "Manage Sections",
		"url" => URL."tswreis_schools/sections",
		"icon" => "fa-star",
		"class" => "menu-links",
	),
	"students" => array(
		"title" => "Create Student",
		"url" => URL."tswreis_schools/create_student",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	"update_student_info" => array(
		"title" => "Update Student Profile",
		"url" => URL."tswreis_schools/panacea_update_ehr",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	"import_medical_test" => array(
		"title" => "Import Medical Certificates",
		"url" => URL."tswreis_schools/medical_test_details",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	/*"staffs" => array(
		"title" => "Create Staff",
		"url" => URL."tswreis_schools/create_staff",
		"icon" => "fa-male",
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
		"url" => URL."tswreis_schools/student_reports",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	/*"staff" => array(
		"title" => "Staffs Report",
		"url" => URL."tswreis_schools/staff_reports",
		"icon" => "fa-magic",
		"class" => "menu-links",
	),*/
	"ehr" => array(
		"title" => "Electronic Health Record",
		"url" => URL."tswreis_schools/reports_ehr",
		"icon" => "fa-file-text",
		"class" => "menu-links",
	),
)
),

"medicine" => array(
"title" => 'Medicine Inventory',
"icon" => "fa-group",
"class" => "menu-links",
"sub" => array(
	"medicine_import" => array(
		"title" => "Medicines LIst Import",
		"url" => URL."tswreis_schools/medicine_inventory_import",
		"icon" => "fa-user",
		"class" => "menu-links",
	),
	"medicine_list" => array(
    	"title" => "Show Medicines List",
    	"url" => URL."tswreis_schools/medicine_inventory_list",
    	"icon" => "fa-user",
    	 "class" => "menu-links"
    ),
	)
	),

"news_feed" => array(
	"title" => 'News Feed',
	"icon" => "fa fa-list-ul",
	"class" => "menu-links",
	"sub" => array(
		"add_news" => array(
			"title" => "Add News Feed",
			"url" => URL."tswreis_schools/add_news_feed_view",
			"icon" => "fa-user",
			"class" => "menu-links"
		),
		"manage_news" => array(
			"title" => "Manage News Feed",
			"url" => URL."tswreis_schools/manage_news_feed_view",
			"icon" => "fa-user",
			"class" => "menu-links"
		),
	)
),



"changepassword" => array(
		"title" => "Change Password",
		"url" => URL."tswreis_schools/change_password",
		"icon" => "fa-refresh",
		"class" => "menu-links"
),

"help"=> array(
	"title"=>"Help",
	"url"=>URL."tswreis_schools/usermanual_view",
	"icon"=>"fa fa-lg fa-fw fa fa-question-circle",
	"class"=>"menu-links"
	),
	
	"contact_us"=> array(
	"title"=>"Contact Us",
	"url"=>URL."tswreis_schools/contact_us",
	"icon"=>"fas fa-phone-square",
	"class"=>"menu-links"
	),
"my_profile"=> array(
	"title"=>"My Profile",
	"url"=>URL."tswreis_schools/my_profile_hs",
	"icon"=>"fa fa-user",
	"class"=>"menu-links"
	),


"logout" => array(
		"title" => lang('common_logout'),
		"url" => URL."auth/logout",
		"icon" => "fa-power-off",
		"class" => "menu-links"
	)       
);

//configuration variables
$page_title 	= "";
$page_css 		= array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>
