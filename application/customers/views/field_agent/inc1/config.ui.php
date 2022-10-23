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
	"group management" => array(
		"title" => "Group Management",
		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			"groups" => array(
				"title" => "Groups",
				"url" => URL."dashboard/groups",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"creategroup" => array(
				"title" => "Create Group",
				"url" =>URL."auth/create_group",
				"icon" => "fa-plus-circle",
				"class" => "menu-links",
			),
			"editgroup" => array(
				"title" => "Edit Group",
				"url" => URL."auth/pre_edit_group",
				"icon" => "fa-pencil-square-o",
				"class" => "menu-links",
			),
			),
			),
	"users" => array(
		"title" => "Users Management",
		"icon" => "fa-edit",
		"class" => "menu-links",
		"sub" => array(
	"user management" => array(
		"title" => "User Management",
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
			"users" => array(
				"title" => "Users",
				"url" => URL."dashboard/user",
				"icon" => "fa-info",
				"class" => "menu-links"
			),
			"createuser" => array(
				"title" => "Create User",
				"url" =>URL."auth/create_user",
				"icon" => "fa-male",
				"class" => "menu-links"
			),
			"userstatus" => array(
				"title" => "Activate/Deactivate User",
				"url" => URL."auth/pre_activate",
				"icon" => "fa-ban",
				"class" => "menu-links"
			),
			"edituser" => array(
				"title" => "Edit User",
				"url" => URL."auth/pre_edit_user",
				"icon" => "fa-edit ",
				"class" => "menu-links"
			),
			"deleteuser" => array(
				"title" => "Delete User",
				"url" => URL."auth/pre_delete_user",
				"icon" => "fa-minus-circle",
				"class" => "menu-links"
			),
			"useapp" => array(
				"title" => "User Application",
				"url" => URL."dashboard/pre_app_listing",
				"icon" => "fa-file",
				"class" => "menu-links"
			),
		),
	),
	"sub admin management" => array(
		"title" => "Sub Admin Management",
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
				"users" => array(
				"title" => "Sub Admins",
				"url" => URL."dashboard/sub_admin",
				"icon" => "fa-info",
				"class" => "menu-links"
		),
		"createuser" => array(
				"title" => "Create Sub Admins",
				"url" =>URL."auth/create_sub_admin",
				"icon" => "fa-male",
				"class" => "menu-links"
		),
								"userstatus" => array(
										"title" => "Activate/Deactivate Sub Admins",
										"url" => URL."auth/pre_activate_sub_admin",
										"icon" => "fa-ban",
										"class" => "menu-links"
								),
								"edituser" => array(
										"title" => "Edit Sub Admins",
										"url" => URL."auth/pre_edit_sub_admin",
										"icon" => "fa-edit ",
										"class" => "menu-links"
								),
								"deleteuser" => array(
										"title" => "Delete Sub Admins",
										"url" => URL."auth/pre_delete_sub_admin",
										"icon" => "fa-minus-circle",
										"class" => "menu-links"
								),
								"useapp" => array(
										"title" => "Sub Admins Application",
										"url" => URL."dashboard/pre_app_listing",
										"icon" => "fa-file",
										"class" => "menu-links"
								),
						),
				)
	)
	),
	"events" => array(
		"title" => "Events",
		"icon" => "fa-suitcase",
		"class" => "menu-links",
		"sub" => array(
		"create_calendar" => array(
			"title" => "Create Events Forms",
			"url" => URL."sub_admin/create_event",
			"icon" => "fa-calendar-o",
			"class" => "menu-links"
		),
		"manage_calendar" => array(
			"title" => "Manage Events Forms",
			"url" => URL."sub_admin/manage_event",
			"icon" => "fa-calendar-o",
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
			"icon" => "fa-calendar-o",
			"class" => "menu-links"
		)
	)),
				"feedback" => array(
						"title" => "Feedbacks",
						"icon" => "fa-suitcase",
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
										"icon" => "fa-calendar-o",
										"class" => "menu-links"
								),
								"user_reply" => array(
										"title" => "Assigned Feedbacks",
										"url" => URL."sub_admin/manage_user_assigned_feedbacks",
										"icon" => "fa-calendar-o",
										"class" => "menu-links"
								)
						)),
						"notification" => array(
								"title" => "Notifications",
								"icon" => "fa-envelope",
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
												"icon" => "fa-calendar-o",
												"class" => "menu-links"
										)
								)),
		"sms" => array(
				"title" => "SMS",
				"icon" => "fa-suitcase",
				"class" => "menu-links",
				"sub" => array(
						"sms_dashboard" => array(
								"title" => "SMS Dashboard",
								"url" => URL."sub_admin/sms_dashboard",
								"icon" => "fa-calendar-o",
								"class" => "menu-links"
						),
						"sms_history" => array(
								"title" => "SMS History",
								"url" => URL."sub_admin/sms_history",
								"icon" => "fa-calendar-o",
								"class" => "menu-links"
						),
						"third_party_sms" => array(
								"title" => "Third Party SMS",
								"url" => URL."sub_admin/third_party_sms",
								"icon" => "fa-calendar-o",
								"class" => "menu-links"
						)
				)),
	"thirdparty" => array(
		"title" => "Third Party",
		"icon" => "fa-asterisk",
		"class" => "menu-links",

		"sub" => array(
		"api_users" => array(
		"title" => "API users",
		"url" => URL."api/api_users",
		"icon" => "fa-exchange",
		"class" => "menu-links"
		),
		"api_act_deact" => array(
			"title" => "Activate/Deactivate APIs",
			"url" =>URL."api/pre_activate_api",
			"icon" => "fa-cloud-upload",
			"class" => "menu-links"
		),
		"new_api_users" => array(
			"title" => "New API users",
			"url" => URL."api/new_api",
			"icon" => "fa-keyboard-o",
			"class" => "menu-links"
		),
		"3rd_party" => array(
			"title" => "Third Party",
			"url" => URL."api/index",
			"icon" => "fa-cloud-upload",
			"class" => "menu-links"
		),
		),
	),
	"application" => array(
		"title" => "Applications",
		"icon" => "fa-edit",
		"class" => "menu-links",
		"sub" => array(
			"allapps" => array(
				"title" => "All Apps",
				"url" => URL."dashboard/apps_allapps",
				"class" => "menu-links"
			),
			"sharedapps" => array(
				"title" => "Shared Apps",
				"url" =>URL."dashboard/apps_shared",
				"class" => "menu-links"
			),
			"myapps" => array(
				"title" => "My Apps",
				"url" => URL."dashboard/apps_myapps",
				"class" => "menu-links"
			),
			"communityapps" => array(
				"title" => "Community Apps",
				"class" => "menu-links",
						"sub" => array(
			"Accounting" => array(
				"title" => "Accounting",
				"url" => URL."dashboard/community_app_select/Accounting",
				"class" => "menu-links"
			),
			"Automotive" => array(
				"title" => "Automotive",
				"url" => URL."dashboard/community_app_select/Automotive",
				"class" => "menu-links"
			),
			"Banking" => array(
				"title" => "Banking",
				"url" => URL."dashboard/community_app_select/Banking",
				"class" => "menu-links"
			),
			"Construction" => array(
				"title" => "Construction",
				"url" => URL."dashboard/community_app_select/Construction",
				"class" => "menu-links"
			),
			"Financial" => array(
				"title" => "Financial",
				"url" => URL."dashboard/community_app_select/Financial",
				"class" => "menu-links"
			),
			"Healthcare" => array(
				"title" => "Healthcare",
				"url" => URL."dashboard/community_app_select/Healthcare",
				"class" => "menu-links"
			),
			"Manufacturing" => array(
				"title" => "Manufacturing",
				"url" => URL."dashboard/community_app_select/Manufacturing",
				"class" => "menu-links"
			),
			"RealEstate" => array(
				"title" => "Real Estate",
				"url" => URL."dashboard/community_app_select/RealEstate",
				"class" => "menu-links"
			),
			"Others" => array(
				"title" => "Others",
				"url" => URL."dashboard/community_app_select/Others",
				"class" => "menu-links"
			)
		  )
		)
	  )
	),
	"tools" => array(
		"title" => "Tools",
		"icon" => "fa-suitcase",
		"class" => "menu-links",
		"sub" => array(
		     "changepassword" => array(
		"title" => "Change Password",
		"url" => URL."auth/change_password",
		"icon" => "fa-exchange",
		"class" => "menu-links"
		
	),
			"predefinedlists" => array(
				"title" => "Predefined Lists",
				"url" => URL."dashboard/predefine_list",
                "icon" => "fa-keyboard-o",
				"class" => "menu-links"
			),
				"predefinedtemplates" => array(
				"title" => "Predefined Templates",
				"url" => URL."template_upload/predefined_templates",
				"icon" => "fa-keyboard-o",
                "class" => "menu-links"
			),
			"sqlimport" => array(
				"title" => "SQL Import",
				"url" =>URL."dashboard/sql_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
			),
			"nosqlimport" => array(
				"title" => "NoSQL Import",
				"url" => URL."dashboard/nosql_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
			),
			"documentimport" => array(
				"title" => "Document Import",
				"url" => URL."dashboard/document_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
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