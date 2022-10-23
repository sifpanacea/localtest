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
		"url" => URL."dashboard/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
"Getstarted" => array(
		"title" => "Get Started",
		"url" => URL."help/index",
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
						),
				)
	)
	),
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
		// "3rd_party" => array(
			// "title" => "Third Party",
			// "url" => URL."api/index",
			// "icon" => "fa-cloud-upload",
			// "class" => "menu-links"
		// ),
		),
	),
	"designtemplate" => array(
		"title" => "Application Design",
		"url" => URL."dashboard/app_prop",
		"icon" => "fa-paste",
		"class" => "menu-links"
		
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
	"drafts" => array(
		"title" => "Drafts",
		"url" => URL."dashboard/drafts",
		"icon" => "fa-folder-open",
		"class" => "menu-links"
	),
	"calendar" => array(
		"title" => "Calendar",
		"url" => URL."calendar",
		"icon" => "fa-calendar-o",
		"class" => "menu-links"
	),
	"events" => array(
		"title" => "Events",
		"icon" => "fa-calendar",
		"class" => "menu-links",
		"label_htm" => ($event_edit_count_total > 0 ? '<span class="badge bg-color-greenLight inbox-badge" style="margin-left:5px">'.$event_edit_count_total.'</span>' : ''),

		"sub" => array(
		"eventrequests" => array(
		"title" => "Event Requests",
		"url" => URL."sub_app_builder/get_event_requests",
		"icon" => "fa-file-text",
		"label_htm" => ($event_new_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$event_new_count.'</span>' : ''),
		"class" => "menu-links"
		),
		"manageeventapp" => array(
			"title" => "Manage Events",
			"url" =>URL."sub_app_builder/manage_event_apps",
			"icon" => "fa-edit",
			"label_htm" => ($event_edit_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$event_edit_count.'</span>' : ''),
			"class" => "menu-links"
		),
		),
	),
	"feedbacks" => array(
		"title" => "Feedback",
		"icon" => "fa-comments-o",
		"class" => "menu-links",
		"label_htm" => ($feedback_edit_count_total > 0 ? '<span class="badge bg-color-greenLight inbox-badge" style="margin-left:5px">'.$feedback_edit_count_total.'</span>' : ''),

		"sub" => array(
		"feedbackrequests" => array(
		"title" => "Feedback Requests",
		"url" => URL."sub_app_builder/get_feedback_requests",
		"icon" => "fa-file-text",
		"label_htm" => ($feedback_new_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$feedback_new_count.'</span>' : ''),
		"class" => "menu-links"
		),
		"managefeedbackapp" => array(
			"title" => "Manage Feedbacks",
			"url" =>URL."sub_app_builder/manage_feedback_apps",
			"icon" => "fa-edit",
			"label_htm" => ($feedback_edit_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$feedback_edit_count.'</span>' : ''),
			"class" => "menu-links"
		),
		),
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