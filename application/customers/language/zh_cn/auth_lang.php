<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

//Common
$lang['common_copy_rights']					=	'&copy;2013, TLSTEC. All rights reserved.';
$lang['common_dash_heading']	           	=	'TLSTEC';
$lang['common_message']						=	'Message!';
$lang['common_create_user_link']			=	'<i class="icon-dash-large icon-user icon-white"></i><span class="hidden-tablet">  Create a new user </span>';
$lang['common_create_group_link'] 			=	'<i class="icon-dash-large icon-edit icon-white"></i><span class="hidden-tablet">  Create a new group </span>';
$lang['common_design_template_link']		=	'Design Template';
$lang['common_change_password_link']	    =	'<i class="icon-dash-large icon-folder-close-alt icon-white"></i><span class="hidden-tablet">  Change Password </span>';
$lang['common_design_template_link']		=	'<i class="icon-dash-large icon-font icon-white"></i><span class="hidden-tablet">  Design Template </span>';
$lang['common_logout_link']					=	'<i class="icon-dash-large icon-lock icon-white"></i><span class="hidden-tablet"> Logout </span>';
$lang['common_logout_link_small']			=	'<i class="icon-off"></i> Logout </span>';
$lang['common_scaffold_link']	        	=	'<i class="icon-dash-large icon-picture icon-white"></i><span class="hidden-tablet"> Scaffolding Main Page </span>';
$lang['common_workflow_link']        		=	'<i class="icon-dash-large icon-align-justify icon-white"></i><span class="hidden-tablet"> Workflow Tool';
$lang['common_userinfo_link']         		=	'User Information';
$lang['common_admin_dash_link']        		=	'<i class="icon-dash-large icon-home icon-white"></i><span class="hidden-tablet">  Dashboard';
$lang['admin_profile']                            =   '<i class="icon-user"></i> Profile </span>';
$lang['admin_settings']                            =  '<i class="icon-wrench icon-white"></i> Settings </span>';
$lang['common_apps'] = '<i class="icon-dash-large icon-picture icon-white"></i><span class="hidden-tablet">  Apps';

// Login
$lang['login_heading']         = '<img src= '.(IMG."logo-tlstec.png").' height="100px" width="277px">';
$lang['login_subheading']      = 'Please login with your email and password below.';
$lang['login_identity_label']  = 'Email/Username:';
$lang['login_password_label']  = 'Password:';
$lang['login_remember_label']  = 'Remember Me:';
$lang['login_submit_btn']      = 'Login';
$lang['login_forgot_password'] = 'Forgot your password?';

// UsersInfo
$lang['index_heading']           	= 'Users and Groups';
$lang['index_subheading']        	= 'Below is a list of the users.';
$lang['index_fname_th']          	= 'First Name';
$lang['index_lname_th']          	= 'Last Name';
$lang['index_email_th']          	= 'Email';
$lang['index_user_th']          	= 'Username';
$lang['index_groups_th']         	= 'Groups';
$lang['index_status_th']         	= 'Status';
$lang['index_action_th']         	= 'Action';
$lang['index_mobile_th']         	= 'Mobile';
$lang['index_company_th']           = 'Company';
$lang['index_company_website_th']   = 'Website';
$lang['index_company_address_th']           = 'Company Address';
$lang['index_contactp_th']           = 'Contact Person';
$lang['index_username_th']           = 'Username';
$lang['index_plan_th']              = 'Subscribed Plan';
$lang['index_registered_th']           = 'Subscribed On';
$lang['index_plan_expiry_th']           = 'Subscription Expiry';
$lang['index_active_link']       	= 'Active';
$lang['index_inactive_link']     	= 'Inactive';
$lang['index_activate_link']       	= '<button class="btn btn-warning btn-xs">Activate</button>';
$lang['index_deactivate_link']       	=  '<button class="btn btn-warning btn-xs">Deactivate</button>';
$lang['index_create_user_link']  	= 'Create a new user';
$lang['index_create_group_link'] 	= 'Create a new group';
$lang['index_design_template_link']	= 'Design Template';
$lang['index_change_password_link'] = 'Change Password';
$lang['index_design_template_link'] = 'Design Template';
$lang['index_logout_link']			= 'Logout';
$lang['index_scaffold_link']	    = 'Scaffolding Main Page';
$lang['index_workflow_link']        = 'Workflow Main Page';
$lang['index_app_th']          	= 'Applications Created';
$lang['index_app']          	= 'Applications';
$lang['index_app_created']          	= 'Created on';
$lang['index_app_des'] = 'App Description';
$lang['index_app_delete'] = 'Delete App';
$lang['index_app_continue'] = 'Continue From Draft';
$lang['index_app_edit'] = 'Edit App';
$lang['index_app_use'] = 'Use App';
$lang['index_app_properties']  = 'App Properties';
$lang['index_app_share'] = 'Share App';
$lang['index_app_unshare'] = 'Unshare App';
$lang['edit'] =  '<button class="btn bg-color-green txt-color-white btn-xs">Edit User</button>';
$lang['delete'] =  '<button class="btn bg-color-red txt-color-white btn-xs">Delete User</button>';

// Deactivate User
$lang['deactivate_user_nav']							   =	'Deactivate User';
$lang['deactivate_heading']                  = 'Deactivate User';
$lang['deactivate_subheading']               = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Yes:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_nav']							   =	'Create User';
$lang['create_user_heading']                           = 'Create User';
$lang['edit_profile']                           = 'Edit Profile';
$lang['create_user_subheading']                        = 'Please enter the users information below.';
$lang['create_user_device_unique_number_label']        = 'Device Unique Number:';
$lang['create_user_fname_label']                       = 'First Name:';
$lang['create_user_lname_label']                       = 'Last Name:';
$lang['create_user_company_label']                     = 'Company Name:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Phone:';
$lang['create_user_password_label']                    = 'Password:';
$lang['create_user_password_confirm_label']            = 'Confirm Password:';
$lang['create_user_submit_btn']                        = 'Create User';
$lang['create_user_validation_fname_label']            = 'First Name';
$lang['create_user_validation_lname_label']            = 'Last Name';
$lang['create_user_validation_email_label']            = 'Email Address';
$lang['create_user_validation_phone_label']            = 'Phone';
$lang['create_user_validation_phone1_label']           = 'First Part of Phone';
$lang['create_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['create_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['create_user_validation_company_label']          = 'Company Name';
$lang['create_user_validation_password_label']         = 'Password';
$lang['create_user_validation_password_confirm_label'] = 'Password Confirmation';

// Edit User
$lang['edit_user_nav']							   =	
$lang['edit_user_heading']                           = 'Edit User';
$lang['edit_user_subheading']                        = 'Please enter the users information below.';
$lang['edit_user_fname_label']                       = 'First Name:';
$lang['edit_user_lname_label']                       = 'Last Name:';
$lang['edit_user_company_label']                     = 'Company Name:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Phone:';
$lang['edit_user_password_label']                    = 'Password: (if changing password)';
$lang['edit_user_password_confirm_label']            = 'Confirm Password: (if changing password)';
$lang['edit_user_groups_heading']                    = 'Member of groups';
$lang['edit_user_submit_btn']                        = 'Save User';
$lang['edit_user_validation_fname_label']            = 'First Name';
$lang['edit_user_validation_lname_label']            = 'Last Name';
$lang['edit_user_validation_email_label']            = 'Email Address';
$lang['edit_user_validation_phone1_label']           = 'First Part of Phone';
$lang['edit_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['edit_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['edit_user_validation_company_label']          = 'Company Name';
$lang['edit_user_validation_groups_label']           = 'Groups';
$lang['edit_user_validation_password_label']         = 'Password';
$lang['edit_user_validation_password_confirm_label'] = 'Password Confirmation';

// Create Group
$lang['create_group_nav']					 =	'Create Group';
$lang['create_group_title']                  =	'Create Group';
$lang['create_group_heading']                =	'Create Group';
$lang['create_group_subheading']             =	'Please enter the group information below.';
$lang['create_group_name_label']             =	'Group Name:';
$lang['create_group_desc_label']             =	'Description:';
$lang['create_group_submit_btn']             =	'Create Group';
$lang['create_group_validation_name_label']  =	'Group Name';
$lang['create_group_validation_desc_label']  =	'Description';

// Edit Group
$lang['edit_group_nav']					 =	'<button class="btn bg-color-green txt-color-white btn-xs">Edit Group</button>';
$lang['edit_group_title']                  = 'Edit Group';
$lang['edit_group_saved']                  = 'Group Saved';
$lang['edit_group_heading']                = 'Edit Group';
$lang['edit_group_subheading']             = 'Please enter the group information below.';
$lang['edit_group_name_label']             = 'Group Name:';
$lang['edit_group_desc_label']             = 'Description:';
$lang['edit_group_submit_btn']             = 'Save Group';
$lang['edit_group_validation_name_label']  = 'Group Name';
$lang['edit_group_validation_desc_label']  = 'Description';

// Change Password
$lang['change_password_nav']						   		   =	'Change Password';
$lang['change_password_heading']                               =	'Change Password';
$lang['change_password_old_password_label']                    =	'Old Password:';
$lang['change_password_new_password_label']                    =	'New Password (at least %s characters long):';
$lang['change_password_new_password_confirm_label']            =	'Confirm New Password:';
$lang['change_password_submit_btn']                            =	'Change';
$lang['change_password_validation_old_password_label']         =	'Old Password';
$lang['change_password_validation_new_password_label']         =	'New Password';
$lang['change_password_validation_new_password_confirm_label'] =	'Confirm New Password';

// Forgot Password
$lang['forgot_password_heading']                 = 'Forgot Password';
$lang['forgot_password_subheading']              = 'Please enter your %s so we can send you an email to reset your password.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Submit';
$lang['forgot_password_validation_email_label']  = 'Email Address';
$lang['forgot_password_username_identity_label'] = 'Username';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';

// Reset Password
$lang['reset_password_heading']                               = 'Change Password';
$lang['reset_password_new_password_label']                    = 'New Password (at least %s characters long):';
$lang['reset_password_new_password_confirm_label']            = 'Confirm New Password:';
$lang['reset_password_submit_btn']                            = 'Change';
$lang['reset_password_validation_new_password_label']         = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Activation Email
$lang['email_activate_heading']    = 'Activate account for %s';
$lang['email_activate_subheading'] = 'Please click this link to %s.';
$lang['email_activate_link']       = 'Activate Your Account';

// Forgot Password Email
$lang['email_forgot_password_heading']    = 'Reset Password for %s';
$lang['email_forgot_password_subheading'] = 'Please click this link to %s.';
$lang['email_forgot_password_link']       = 'Reset Your Password';

// New Password Email
$lang['email_new_password_heading']    = 'New Password for %s';
$lang['email_new_password_subheading'] = 'Your password has been reset to: %s';

// Template
$lang['app_title']	  =	'应用程序设计';
$lang['app_prop']	  =	'应用程序属性';
$lang['app_design']	  =	'应用程序设计';
$lang['app_work_flow']	  =	'工作流程';
$lang['app_notify']	  =	'申请的通知';
$lang['app_next']	  =	'下一步';
$lang['app_note']	  =	'注意：';
$lang['app_note_txt']	  =	'特殊字符不允许';
$lang['app_select']	  =	'选择类别';
$lang['app_header_yes']	  =	'是';
$lang['app_header_no']	  =	'否';
$lang['app_page_no']	  =	'页中没有。';
$lang['app_print_temp']	  =	'打印模板';
$lang['app_previous']	  =	'上';
$lang['temp_delete']	  =	'删除';
$lang['app_select_temp']	  =	'选择模板';
$lang['app_close']	  =	'关闭';
$lang['temp_share']	  =	'分享';
$lang['temp_private']	  =	'私有';

$lang['template_nav']			  =	'设计模板';
$lang['app_prop_heading']		 =	'应用程序属性';
$lang['prop_sub_heading']	  =	'定义应用程序属性';
$lang['app_description']	  =	'说明：';
$lang['app_type']	  =	'应用类型：';
$lang['app_expiry']	  =	'申请有效期：';
$lang['app_category']	  =	'应用类别：';
$lang['app_properties'] = '<button class="btn btn-success btn-xs">视图属性</button>';
$lang['app_edit'] = '<button class="btn btn-success btn-xs">修改</button>';
$lang['app_continue'] = '<button class="btn btn-success btn-xs">继续</button>';
$lang['app_use'] = '<button class="btn btn-warning btn-xs">使用</button>';
$lang['app_delete'] = '<button class="btn btn-danger btn-xs">删除</button>';
$lang['app_share'] = '<button class="btn bg-color-pink txt-color-white btn-xs">共享</button>';
$lang['app_unshare'] = '<button class="btn bg-color-pinkDark txt-color-white btn-xs">取消共享</button>';
$lang['prop_ok_btn']	  =	'开始创建应用程序';
$lang['profile_header']   = '使用设备配置文件头';
$lang['notification_nav']			  =	'通知';
$lang['notification_heading']			      =	'用户通知';
$lang['notification_ok_btn']	  =	'发送通知';
$lang['template_heading']         =	'模板开发应用程序';
$lang['template_sub_heading']	  =	'应用';
$lang['appp_edit'] = '编辑 ';
$lang['appp_draft'] = '草案 ';
$lang['appp_create'] = '创建 ';
$lang['appp_use'] = '使用 ';
$lang['template_add_field_btn']	  =	'添加.元素';
$lang['template_move_btn']	  =	'移动到工作流';
$lang['template_app_name']	  =	'应用程序名称：';
$lang['template_element_count']	  =	'剩余的元素：';





// Scaffold
$lang['scaffold_heading']         = 'Output of Tamplate Application';

// Workflow
$lang['workflow_nav']			   	    =	'Workflow Tool';
$lang['workflow_heading']           	= 'Workflow Application';
$lang['workflow_subheading']        	= 'Below is a list of the templates';
$lang['workflow_create_user_link']  	= 'Create a new user';
$lang['workflow_create_group_link'] 	= 'Create a new group';
$lang['workflow_design_template_link']	= 'Design Template';
$lang['workflow_change_password_link']  = 'Change Password';
$lang['workflow_design_template_link']  = 'Design Template';
$lang['workflow_logout_link']			= 'Logout';
$lang['workflow_scaffold_link']	        = 'Scaffolding Main Page';

// User
$lang['user_heading']           	= 'User Inbox';
$lang['user_subheading']        	= 'Below is a list of the pending template';
$lang['user_change_password_link']  = 'Change Password';
$lang['user_logout_link']			= 'Logout';

// Admin dash
$lang['admin_title']				        		= 'Dashboard';
$lang['admin_dash_home']	        		= 'Home';
$lang['admin_dash_board']					= 'My Dashboard';
$lang['admin_dash_sub']						= 'Subscription';
$lang['admin_dash_days']					= 'Days left';
$lang['admin_dash_paper']					= 'Papers Saved';
$lang['admin_dash_tree']					= 'Trees Saved';
$lang['admin_dash_live']					= 'Live Feeds';
$lang['admin_dash_live_stat']				= 'Live Stats';
$lang['admin_dash_saved']					= 'Saved Analytics Pattern';
$lang['admin_dash_live_swtich']				= 'Live switch';
$lang['admin_dash_live_swtich_on']			= 'ON';
$lang['admin_dash_live_swtich_off']			= 'OFF';
$lang['admin_dash_list_apps']		= 'Applications';
$lang['admin_no_predefinedlists'] = 'There is no predefined list created yet.';


$lang['admin_query']				= '<button class="btn btn-warning btn-xs">App Analytics</button>';

$lang['admin_dash_list_doc']		= 'List of Documents';
$lang['admin_dash_list_users']		= 'List of Users';
$lang['admin_dash_list_groups']		= 'List of Groups';
$lang['admin_activities']    		= 'Activities';
$lang['admin_dash_subheading']      = 'Below is a list of the users.';
$lang['index_create_user_link']  	= 'Create a new user';
$lang['index_create_group_link'] 	= 'Create a new group';
$lang['index_design_template_link']	= 'Design Template';
$lang['index_change_password_link'] = 'Change Password';
$lang['index_design_template_link'] = 'Design Template';
$lang['index_logout_link']			= 'Logout';
$lang['index_scaffold_link']	    = 'Scaffolding Main Page';
$lang['index_workflow_link']        = 'Workflow Main Page';
$lang['admin_profile']              = 'Profile';
$lang['admin_settings']             = 'Settings';
$lang['admin_no_apps']       = 'There is no application created yet.';
$lang['admin_no_drafts']       = 'There is no drafts saved yet.';
$lang['admin_no_users']       = 'There are no users created yet.';
$lang['admin_no_groups']       = 'There are no groups created yet.';
$lang['admin_no_docs']       = 'There is no document for any application yet.';
$lang['webview_no_apps']       = 'There is no application assigned for you yet.';
$lang['webview_no_docs']       = 'There is no documents assigned for you yet.';
$lang['admin_no_apps_created'] = 'There is no application created by you';
$lang['delete_confirm'] = 'Do you really want to continue ?';

// User Dashboard
$lang['user_web_apps_link']			='<i class="icon-dash-large icon-picture icon-white"></i><span class="hidden-tablet">  Apps </span>';
$lang['user_web_docs_link']			='<i class="icon-dash-large icon-file icon-white"></i><span class="hidden-tablet"> Documents </span>';
$lang['user_web_docs_search_link']			='<i class="fa fa-search"></i><span class="hidden-tablet"> Search Documents </span>';
$lang['user_dash_activities']       =  'New Activities';
$lang['user_web_app_des']           = 'Description';
$lang['user_web_app_name']          = 'Application';
$lang['user_web_app_created']           = 'App created on';
$lang['user_web_docs_search']           = 'Search Documents';

//Edit admin profile
$lang['edit_admin_profile_nav']              = 'Edit Profile';
$lang['edit_admin_profile_heading']              = 'Edit Profile';
$lang['edit_profile_company_name_label']              = 'Company Name';
$lang['edit_profile_address_label']              = 'Address';
$lang['edit_profile_website_label']              = 'Website';
$lang['edit_profile_contactp_label']              = 'Contact Person';
$lang['edit_profile_email_label']              = 'Email';
$lang['edit_profile_mobile_label']              = 'Mobile';
$lang['edit_profile_username_label']              = 'Username';
$lang['edit_profile_image_label']              = 'Profile Image';
$lang['edit_profile_logo_label']              = 'Company Logo Image';
$lang['edit_profile_submit_btn']              = 'Submit';

//pattern
$lang['pattern_title']                  = 'Title';
$lang['pattern_description']            = 'Description';
$lang['pattern']                        = 'Pattern';
$lang['admin_no_saved_patterns']       	= 'You dont have any saved patterns yet.';
$lang['query_pattern']       			= 'Query';
$lang['query_pattern_delete']       	= 'Delete';

// Events 
$lang['index_event_name']                           = 'Event Name';
$lang['index_event_desc']                           = 'Event Description';
$lang['index_event_requested_user']                 = 'Requested User';
$lang['index_event_requested_time']                 = 'Requested Time';
$lang['index_event_creation']                       = 'Create Event';
$lang['index_event_edit']                           = 'Edit Event';
$lang['admin_dash_list_events']                     = 'Requested Events';
$lang['admin_dash_manage_events']                   = 'Event Management';
$lang['admin_no_events']                            = 'No Events';
$lang['index_event_attachment']                     = 'Attachment';
$lang['index_event_comments']                       = 'Comments';
$lang['event_create']                               = '<button class="btn btn-primary btn-xs">Create</button>';
$lang['event_edit']                                 = '<button class="btn btn-warning btn-xs">Edit</button>';
$lang['event_create_heading']                       = 'Create Event';
$lang['event_edit_heading']                         = 'Edit Event';



// Feedbacks 
$lang['index_feedback_name']                        = 'Feedback Name';
$lang['index_feedback_desc']                        = 'Feedback Description';
$lang['index_feedback_requested_user']              = 'Requested User';
$lang['index_feedback_requested_time']              = 'Requested Time';
$lang['index_feedback_creation']                    = 'Create Feedback';
$lang['index_feedback_edit']                        = 'Edit Feedback';
$lang['admin_dash_list_feedbacks']                  = 'Requested Feedbacks';
$lang['admin_dash_manage_feedbacks']                = 'Feedback Management';
$lang['admin_no_feedbacks']                         = 'No Feedbacks';
$lang['index_feedback_attachment']                  = 'Attachment';
$lang['index_feedback_comments']                    = 'Comments';
$lang['feedback_create']                            = '<button class="btn btn-primary btn-xs">Create</button>';
$lang['feedback_edit']                              = '<button class="btn btn-warning btn-xs">Edit</button>';
$lang['feedback_create_heading']                    = 'Create Feedback';
$lang['feedback_edit_heading']                      = 'Edit Feedback';

//Change password rules & messages
$lang['new_password_check']     = '\'New password should be atleast 8 characters\'';
$lang['confirm_password_check'] = '\'Confirm password should be atleast 8 characters\'';
$lang['confirm_password_match_check'] = '\'Confirm password should match new password\'';

// CREATE SUB ADMIN CLIENT SIDE VALIDATION MESSAGES
$lang['sub_admin_first_name_required']  = '\'First Name is required\'';
$lang['sub_admin_first_name_min']       = '\'First Name should be atleast 3 characters minimum\'';
$lang['sub_admin_first_name_max']       = '\'First Name should be within 25 characters\'';
$lang['sub_admin_last_name_required']   = '\'Last Name is required\'';
$lang['sub_admin_last_name_min']        = '\'Last Name should be atleast 3 characters minimum\'';
$lang['sub_admin_last_name_max']        = '\'Last Name should be within 25 characters\'';
$lang['sub_admin_email_required']       = '\'Email ID is required\'';
$lang['sub_admin_email_valid']          = '\'Email ID should be valid\'';
$lang['sub_admin_phone_required']       = '\'Phone Number is required\'';
$lang['sub_admin_phone_number']         = '\'Phone Number should be in valid format \'';
$lang['sub_admin_phone_min']            = '\'Phone Number should be atleast 10 digits\'';
$lang['sub_admin_password_required']    = '\'Password is required\'';
$lang['sub_admin_password_min']         = '\'Password should be atleast 8 characters minimum\'';
$lang['sub_admin_password_max']         = '\'Password should be within 20 characters\'';
$lang['sub_admin_password_confirm_required'] = '\'Confirm Password is required\'';
$lang['sub_admin_password_confirm_min']      = '\'confirm Password should be atleast 8 characters minimum\'';
$lang['sub_admin_password_confirm_max']      = '\'Confirm Password should be within 20 characters\'';
$lang['sub_admin_password_confirm_match']    = '\'Confirm password should match new password\'';



// CREATE USER CLIENT SIDE VALIDATION MESSAGES
$lang['user_first_name_required']       = '\'First Name is required\'';
$lang['user_first_name_min']       = '\'First Name should be atleast 3 characters minimum\'';
$lang['user_first_name_max']       = '\'First Name should be within 25 characters\'';
$lang['user_last_name_required']   = '\'Last Name is required\'';
$lang['user_last_name_min']        = '\'Last Name should be atleast 3 characters minimum\'';
$lang['user_last_name_max']        = '\'Last Name should be within 25 characters\'';
$lang['user_email_required']       = '\'Email ID is required\'';
$lang['user_email_valid']          = '\'Email ID should be valid\'';
$lang['user_phone_required']       = '\'Phone Number is required\'';
$lang['user_phone_number']         = '\'Phone Number should be in valid format \'';
$lang['user_phone_min']            = '\'Phone Number should be atleast 10 digits\'';
$lang['user_password_required']    = '\'Password is required\'';
$lang['user_password_min']         = '\'Password should be atleast 8 characters minimum\'';
$lang['user_password_max']         = '\'Password should be within 20 characters\'';
$lang['user_password_confirm_required'] = '\'Confirm Password is required\'';
$lang['user_password_confirm_min']      = '\'confirm Password should be atleast 8 characters minimum\'';
$lang['user_password_confirm_max']      = '\'Confirm Password should be within 20 characters\'';
$lang['user_password_confirm_match']    = '\'Confirm password should match new password\'';
