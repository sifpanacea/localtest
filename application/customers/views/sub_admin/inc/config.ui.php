<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => URL
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
"home" => array(
		"title" => "Dashboard",
		"url" => URL."sub_admin/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
"Getstarted" => array(
		"title" => "Get Started",
		"url" => URL."help/sub_admin",
		"icon" => "fa-thumbs-up",
		"class" => "menu-links"
	),
	"events" => array(
		"title" => "Events",
		"icon" => "fa-calendar",
		"class" => "menu-links",
		"sub" => array(
		"create_calendar" => array(
			"title" => "Create Events Forms",
			"url" => URL."sub_admin/create_event",
			"icon" => "fa-edit",
			"class" => "menu-links"
		),
		"manage_calendar" => array(
			"title" => "Manage Event Forms",
			"url" => URL."sub_admin/manage_event",
			"icon" => "fa-file-text-o",
			"class" => "menu-links"
		),
		"assign_calendar" => array(
			"title" => "Assign Events",
			"url" => URL."sub_admin_calendar",
			"icon" => "fa-calendar-o",
			"class" => "menu-links"
		),
		"user_reply" => array(
			"title" => "User Assigned Events",
			"url" => URL."sub_admin/manage_user_assigned_event",
			"icon" => "fa-table",
			"class" => "menu-links"
		)
	)),
				"feedback" => array(
						"title" => "Feedbacks",
						"icon" => "fa-briefcase",
						"class" => "menu-links",
						"sub" => array(
								"create_calendar" => array(
										"title" => "Create Feedback Forms",
										"url" => URL."sub_admin/create_feedback",
										"icon" => "fa-calendar-o",
										"class" => "menu-links"
								),
								"manage_calendar" => array(
										"title" => "Manage Feedbacks",
										"url" => URL."sub_admin/manage_feedback",
										"icon" => "fa-list-alt",
										"class" => "menu-links"
								),
								"user_reply" => array(
										"title" => "Assigned Feedbacks",
										"url" => URL."sub_admin/manage_user_assigned_feedbacks",
										"icon" => "fa-suitcase",
										"class" => "menu-links"
								)
						)),
						"notification" => array(
								"title" => "Notifications",
								"icon" => "fa-bell",
								"class" => "menu-links",
								"sub" => array(
										"create_notification" => array(
												"title" => "Create Notification Messages",
												"url" => URL."sub_admin/create_notification",
												"icon" => "fa-edit",
												"class" => "menu-links"
										),
										"manage_notification" => array(
												"title" => "Notification History",
												"url" => URL."sub_admin/manage_notification",
												"icon" => "fa-th-list",
												"class" => "menu-links"
										)
								)),
		"sms" => array(
				"title" => "SMS",
				"icon" => "fa-mobile",
				"class" => "menu-links",
				"sub" => array(
						"sms_dashboard" => array(
								"title" => "SMS Dashboard",
								"url" => URL."sub_admin/sms_dashboard",
								"icon" => "fa-inbox",
								"class" => "menu-links"
						),
						"sms_history" => array(
								"title" => "SMS History",
								"url" => URL."sub_admin/sms_history",
								"icon" => "fa-th-list",
								"class" => "menu-links"
						),
						// "third_party_sms" => array(
								// "title" => "Third Party SMS",
								// "url" => URL."sub_admin/third_party_sms",
								// "icon" => "fa-calendar-o",
								// "class" => "menu-links"
						// )
				)),
	"tools" => array(
		"title" => "Tools",
		"icon" => "fa-gear",
		"class" => "menu-links",
		"sub" => array(
		     "changepassword" => array(
		"title" => "Change Password",
		"url" => URL."auth/change_password_sub_admin",
		"icon" => "fa-exchange",
		"class" => "menu-links"
		
	)
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