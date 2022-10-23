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
"home" => array(
		"title" => lang('common_dashboard'),
		"url" => URL."dashboard/to_dashboard",
		"icon" => "fa-home",
		"class" => "menu-links"
	),
	
	
"Getstarted" => array(
		"title" => lang('common_help'),
		"url" => URL."help/index",
		"icon" => "fa-thumbs-up",
		"class" => "menu-links"
	),
	"group management" => array(
		"title" => lang('common_group_mgmt'),
		"icon" => "fa-group",
		"class" => "menu-links",
		"sub" => array(
			"groups" => array(
				"title" => lang('common_groups'),
				"url" => URL."dashboard/groups",
				"icon" => "fa-star",
				"class" => "menu-links",
			),
			"creategroup" => array(
				"title" => lang('common_create_group'),
				"url" =>URL."auth/create_group",
				"icon" => "fa-plus-circle",
				"class" => "menu-links",
			),
			"editgroup" => array(
				"title" => lang('common_edit_group'),
				"url" => URL."auth/pre_edit_group",
				"icon" => "fa-pencil-square-o",
				"class" => "menu-links",
			),
			),
			),
	"users" => array(
		"title" => lang('common_user_mgmt'),
		"icon" => "fa-edit",
		"class" => "menu-links",
		"sub" => array(
	"user management" => array(
		"title" => lang('common_user_mgmt'),
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
			"users" => array(
				"title" => lang('common_users'),
				"url" => URL."dashboard/user",
				"icon" => "fa-info",
				"class" => "menu-links"
			),
			"createuser" => array(
				"title" => lang('common_create_user'),
				"url" =>URL."auth/create_user",
				"icon" => "fa-male",
				"class" => "menu-links"
			),
			"userstatus" => array(
				"title" => lang('common_act_deact_user'),
				"url" => URL."auth/pre_activate",
				"icon" => "fa-ban",
				"class" => "menu-links"
			),
			"edituser" => array(
				"title" => lang('common_edit_user'),
				"url" => URL."auth/pre_edit_user",
				"icon" => "fa-edit ",
				"class" => "menu-links"
			),
			"deleteuser" => array(
				"title" => lang('common_delete_user'),
				"url" => URL."auth/pre_delete_user",
				"icon" => "fa-minus-circle",
				"class" => "menu-links"
			),
			"useapp" => array(
				"title" => lang('common_user_app'),
				"url" => URL."dashboard/pre_app_listing",
				"icon" => "fa-file",
				"class" => "menu-links"
			),
		),
	),
	"sub admin management" => array(
		"title" => lang('common_sub_mgmt'),
		"icon" => "fa-user",
		"class" => "menu-links",
		"sub" => array(
				"users" => array(
				"title" => lang('common_users'),
				"url" => URL."dashboard/sub_admin",
				"icon" => "fa-info",
				"class" => "menu-links"
		),
		"createuser" => array(
				"title" => lang('common_create_sub'),
				"url" =>URL."auth/create_sub_admin",
				"icon" => "fa-male",
				"class" => "menu-links"
		),
								"userstatus" => array(
										"title" => lang('common_act_deact_sub'),
										"url" => URL."auth/pre_activate_sub_admin",
										"icon" => "fa-ban",
										"class" => "menu-links"
								),
								"edituser" => array(
										"title" => lang('common_edit_sub'),
										"url" => URL."auth/pre_edit_sub_admin",
										"icon" => "fa-edit ",
										"class" => "menu-links"
								),
								"deleteuser" => array(
										"title" => lang('common_delete_sub'),
										"url" => URL."auth/pre_delete_sub_admin",
										"icon" => "fa-minus-circle",
										"class" => "menu-links"
								),
						),
				)
	)
	),
	"thirdparty" => array(
		"title" => lang('common_third_party'),
		"icon" => "fa-asterisk",
		"class" => "menu-links",

		"sub" => array(
		"api_users" => array(
		"title" => lang('common_api_users'),
		"url" => URL."api/api_users",
		"icon" => "fa-exchange",
		"class" => "menu-links"
		),
		"api_act_deact" => array(
			"title" => lang('common_act_deact_api'),
			"url" =>URL."api/pre_activate_api",
			"icon" => "fa-cloud-upload",
			"class" => "menu-links"
		),
		"new_api_users" => array(
			"title" => lang('common_new_api'),
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
		"title" => lang('common_app_design'),
		"url" => URL."dashboard/app_prop",
		"icon" => "fa-paste",
		"class" => "menu-links"
		
	),
	"application" => array(
		"title" => lang('common_app'),
		"icon" => "fa-edit",
		"class" => "menu-links",
		"sub" => array(
			"allapps" => array(
				"title" => lang('common_all_apps'),
				"url" => URL."dashboard/apps_allapps",
				"class" => "menu-links"
			),
			"sharedapps" => array(
				"title" => lang('common_shard_apps'),
				"url" =>URL."dashboard/apps_shared",
				"class" => "menu-links"
			),
			"myapps" => array(
				"title" => lang('common_my_apps'),
				"url" => URL."dashboard/apps_myapps",
				"class" => "menu-links"
			),
			"communityapps" => array(
				"title" => lang('common_comm_apps'),
				"class" => "menu-links",
						"sub" => array(
			"Accounting" => array(
				"title" => lang('common_comm_acc'),
				"url" => URL."dashboard/community_app_select/Accounting",
				"class" => "menu-links"
			),
			"Automotive" => array(
				"title" => lang('common_comm_auto'),
				"url" => URL."dashboard/community_app_select/Automotive",
				"class" => "menu-links"
			),
			"Banking" => array(
				"title" => lang('common_comm_bank'),
				"url" => URL."dashboard/community_app_select/Banking",
				"class" => "menu-links"
			),
			"Construction" => array(
				"title" => lang('common_comm_const'),
				"url" => URL."dashboard/community_app_select/Construction",
				"class" => "menu-links"
			),
			"Financial" => array(
				"title" => lang('common_comm_financial'),
				"url" => URL."dashboard/community_app_select/Financial",
				"class" => "menu-links"
			),
			"Healthcare" => array(
				"title" => lang('common_comm_health'),
				"url" => URL."dashboard/community_app_select/Healthcare",
				"class" => "menu-links"
			),
			"Manufacturing" => array(
				"title" => lang('common_comm_manufacturing'),
				"url" => URL."dashboard/community_app_select/Manufacturing",
				"class" => "menu-links"
			),
			"RealEstate" => array(
				"title" => lang('common_comm_real'),
				"url" => URL."dashboard/community_app_select/RealEstate",
				"class" => "menu-links"
			),
			"Others" => array(
				"title" => lang('common_comm_other'),
				"url" => URL."dashboard/community_app_select/Others",
				"class" => "menu-links"
			)
		  )
		)
	  )
	),
	"drafts" => array(
		"title" => lang('common_drafts'),
		"url" => URL."dashboard/drafts",
		"icon" => "fa-folder-open",
		"class" => "menu-links"
	),
	"calendar" => array(
		"title" => lang('common_calendar'),
		"url" => URL."calendar",
		"icon" => "fa-calendar-o",
		"class" => "menu-links"
	),
	"events" => array(
		"title" => lang('common_events'),
		"icon" => "fa-calendar",
		"class" => "menu-links",
		"label_htm" => ($event_edit_count_total > 0 ? '<span class="badge bg-color-greenLight inbox-badge" style="margin-left:5px">'.$event_edit_count_total.'</span>' : ''),

		"sub" => array(
		"eventrequests" => array(
		"title" => lang('common_event_req'),
		"url" => URL."sub_app_builder/get_event_requests",
		"icon" => "fa-file-text",
		"label_htm" => ($event_new_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$event_new_count.'</span>' : ''),
		"class" => "menu-links"
		),
		"manageeventapp" => array(
			"title" => lang('common_manage_events'),
			"url" =>URL."sub_app_builder/manage_event_apps",
			"icon" => "fa-edit",
			"label_htm" => ($event_edit_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$event_edit_count.'</span>' : ''),
			"class" => "menu-links"
		),
		),
	),
	"feedbacks" => array(
		"title" => lang('common_feedbacks'),
		"icon" => "fa-comments-o",
		"class" => "menu-links",
		"label_htm" => ($feedback_edit_count_total > 0 ? '<span class="badge bg-color-greenLight inbox-badge" style="margin-left:5px">'.$feedback_edit_count_total.'</span>' : ''),

		"sub" => array(
		"feedbackrequests" => array(
		"title" => lang('common_feedback_req'),
		"url" => URL."sub_app_builder/get_feedback_requests",
		"icon" => "fa-file-text",
		"label_htm" => ($feedback_new_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$feedback_new_count.'</span>' : ''),
		"class" => "menu-links"
		),
		"managefeedbackapp" => array(
			"title" => lang('common_manage_feedbacks'),
			"url" =>URL."sub_app_builder/manage_feedback_apps",
			"icon" => "fa-edit",
			"label_htm" => ($feedback_edit_count > 0 ? '<span class="badge pull-right inbox-badge" >'.$feedback_edit_count.'</span>' : ''),
			"class" => "menu-links"
		),
		),
	),
	"tools" => array(
		"title" => lang('common_tools'),
		"icon" => "fa-suitcase",
		"class" => "menu-links",
		"sub" => array(
		     "changepassword" => array(
		"title" => lang('common_change_psw'),
		"url" => URL."auth/change_password",
		"icon" => "fa-exchange",
		"class" => "menu-links"
		
	),
			"predefinedlists" => array(
				"title" => lang('common_pre_list'),
				"url" => URL."dashboard/predefine_list",
                "icon" => "fa-keyboard-o",
				"class" => "menu-links"
			),
				"predefinedtemplates" => array(
				"title" => lang('common_pre_temp'),
				"url" => URL."template_upload/predefined_templates",
				"icon" => "fa-keyboard-o",
                "class" => "menu-links"
			),
			"sqlimport" => array(
				"title" => lang('common_sql'),
				"url" =>URL."dashboard/sql_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
			),
			"nosqlimport" => array(
				"title" => lang('common_no_sql'),
				"url" => URL."dashboard/nosql_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
			),
			"documentimport" => array(
				"title" => lang('common_doc_imp'),
				"url" => URL."dashboard/document_import",
				"icon" => "fa-cloud-upload",
				"class" => "menu-links"
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