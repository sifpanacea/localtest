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
		"url" => URL."auth/logout",
		"icon" => "fa-home",
		"class" => "menu-links"
),
"request_pie" => array(
		"title" => "Chronic PIE",
		"url" => URL."auth/logout",
		"icon" => "fa-user-md",
		"class" => "menu-links"
),
"pie_export" => array(
		"title" => "Pie Export",
		"url" => URL."auth/logout",
		"icon" => "fa-dribbble",
		"class" => "menu-links"
),
		"pa mgmt" => array(
		"title" => 'Masters',
		"icon" => "fa-tachometer",
		"class" => "menu-links",
		"sub" => array(
			"states" => array(
				"title" => "Manage States",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"district" => array(
				"title" => "Manage District",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"diagnostic" => array(
				"title" => "Manage Diagnostic",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"schools" => array(
				"title" => "Manage Schools",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"classes" => array(
				"title" => "Manage Classes",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"sections" => array(
				"title" => "Manage Sections",
				"url" => URL."auth/logout",
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
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospitals" => array(
				"title" => "Manage Hospitals",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
				"title" => "Manage Doctors",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"symptom" => array(
				"title" => "Manage Symptom",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"emp" => array(
				"title" => "Manage Employees",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"cc_users" => array(
					"title" => "Manage CC users",
					"url" => URL."auth/logout",
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
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"hospital" => array(
				"title" => "Hospital Reports",
				"url" => URL."auth/logout",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"doctors" => array(
					"title" => "Doctors Report",
					"url" => URL."auth/logout",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"student" => array(
					"title" => "Student Report",
					"url" => URL."auth/logout",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"symptom" => array(
					"title" => "Symptoms Report",
					"url" => URL."auth/logout",
					"icon" => "fa-star",
					"class" => "menu-links",
			),
			"ehr" => array(
				"title" => "Electronic Health Record",
				"url" => URL."auth/logout",
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
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"hospital" => array(
								"title" => "Hospitals",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"school" => array(
								"title" => "Schools",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"health supervisors" => array(
								"title" => "Health Supervisors",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
						"student" => array(
								"title" => "Students",
								"url" => URL."auth/logout",
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
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"group_msg" => array(
								"title" => "Group messaging",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"user_msg" => array(
								"title" => "Single user messaging",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						"multi_msg" => array(
								"title" => "Multiple user messaging",
								"url" => URL."auth/logout",
								"icon" => "fa-star",
								"class" => "menu-links",
						),
						
				),
		),
		
		"docs_comp" => array(
		"title" => "EHR Compare",
		"url" => URL."auth/logout",
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
						"url" => URL."auth/logout",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
				"view_nf" => array(
						"title" => "Manage News Feed",
						"url" => URL."auth/logout",
						"icon" => "fa-star",
						"class" => "menu-links",
				),
		),
),
	

    "logout" => array(
		"title" => "Logout",
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