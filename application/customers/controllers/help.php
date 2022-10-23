<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends MY_Controller {

	  function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('PaaS_common_lib');
		$this->config->load('email');
		$this->load->library('mongo_db');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->helper('language');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Loading help page for enterprise admin  
     *
     *
     * @author Selva 
     */

	public function index()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		
		$this->load->view('getstarted/admin/index',$data_bubble_count);
	}
	
	/**
	 * Helper: Loading help page for enterprise admin
	 *
	 *
	 * @author Selva
	 */
	
	public function sub_admin()
	{
		$this->load->view('getstarted/sub_admin/index');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function create_group()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		
	  $this->load->view('getstarted/admin/create_group',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_group()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_group',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function groups()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/groups',$data_bubble_count);
	  
	}
	
	// ===== SUB ADMIN MANAGEMENT =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function create_sub_admin()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/create_sub_admin',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_sub_admin()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_sub_admin',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function sub_admins()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/sub_admins',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function sub_admin_status_change()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/sub_admin_status_change',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function delete_sub_admin()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_sub_admin',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function create_user()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/create_user',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_user()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_user',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function users()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/users',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function user_status_change()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/user_status_change',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function delete_user()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_user',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function change_pwd()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/change_password',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function admin_profile()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/admin_profile',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_admin_profile()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_profile',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function plan_upgrade()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/plan_upgrade',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function app_prop()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/app_properties',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function app_design()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/app_design',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function workflow()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/workflow',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function notifications()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/notifications',$data_bubble_count);
	  
	}
	
	// ===== PRIVATE APPS =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function all_app_properties()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/all_apps_prop',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_allapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_allapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function use_allapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/use_allapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function delete_allapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_allapps',$data_bubble_count);
	  
	}
	
	// ===== SHARED APPS =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function shared_app_properties()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/shared_apps_prop',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function share_app()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/share_app',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_sharedapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_sharedapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function use_sharedapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/use_sharedapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function delete_sharedapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_sharedapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function unshare_app()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/unshare_app',$data_bubble_count);
	  
	}
	
	// ===== MY APPS =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function my_app_properties()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/my_apps_prop',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_myapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/edit_myapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function use_myapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/use_myapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function delete_myapps()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_myapps',$data_bubble_count);
	  
	}
	
	// ===== COMMUNITY APPS =====/
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function community_app_properties()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/community_apps_prop',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function use_community_app()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/use_communityapps',$data_bubble_count);
	  
	}
	
	// ===== DRAFTS =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function draft_app()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/use_draftapps',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Delete draft help
     *
     *
     * @author Selva 
     */

	public function delete_draft()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/delete_draftapps',$data_bubble_count);
	  
	}
	
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function sql_import()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/sql_import',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function nosql_import()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/nosql_import',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function document_import()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/document_import',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function dashboard()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/dashboard',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function predefined_lists()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/predefined_lists',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function predefined_templates()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/predefined_templates',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function adminusername()
	{
	 redirect('dashboard/adminusername');
	  
	}
	
	// ===== CALENDAR =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function calendar()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/calendar',$data_bubble_count);
	  
	} 
	
	
	// ===== FEEDBACK =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function feedback_requests()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/feedback_requests',$data_bubble_count);
	  
	} 
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function manage_feedbacks()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/manage_feedback_requests',$data_bubble_count);
	  
	} 
	
	// ===== EVENTS =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function event_requests()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/event_requests',$data_bubble_count);
	  
	} 
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function manage_events()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/manage_event_requests',$data_bubble_count);
	  
	} 
	
	// ===== API ( THIRD PARTY ) =====/
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function api_users()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/api_users',$data_bubble_count);
	  
	} 
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function third_party_status_change()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/third_party_status_change',$data_bubble_count);
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function new_api_users()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/new_api_users',$data_bubble_count);
	  
	} 
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function third_party()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/third_party',$data_bubble_count);
	  
	}

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	 public function field_types()
	{
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  $this->load->view('getstarted/admin/field_types',$data_bubble_count);
	  
	} 	
	//********************************************************************************************************************************************************************************************
	//---------------------------------------------------------------------------------------------------------------------------USER-------------------------------------------------------------
	//********************************************************************************************************************************************************************************************
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: User Help Index Page
     *
     *
     * @author Selva 
     */

	public function user_index()
	{
		$this->load->view('getstarted/user/index');
	}
	
	/**
     * Helper: User Inbox Details
     *
     *
     * @author Selva 
     */

	public function user_inbox()
	{
		$this->load->view('getstarted/user/inbox');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function user_apps()
	{
		$this->load->view('getstarted/user/apps');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function user_installed_apps()
	{
		$this->load->view('getstarted/user/installed_apps');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Accessing document
     *
     *
     * @author Selva 
     */

	public function user_access_documents()
	{
		$this->load->view('getstarted/user/access_doc');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Disapproving document
     *
     *
     * @author Selva 
     */

	public function disapprove_documents()
	{
		$this->load->view('getstarted/user/disapprove_doc');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function search_documents()
	{
		$this->load->view('getstarted/user/search_docs');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function user_profile()
	{
	  $this->load->view('getstarted/user/user_profile');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function edit_user_profile()
	{
	  $this->load->view('getstarted/user/edit_user_profile');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function user_change_password()
	{
		$this->load->view('getstarted/user/change_password');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @author Selva 
     */

	public function username()
	{
	 redirect('web/username');
	  
	}
	
	// ====================================================== SUB ADMIN HELP =================================================//
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_dashboard()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_dashboard');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_create_event_forms()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_create_event_forms');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_manage_event_forms()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_manage_event_forms');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_assign_events()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_assign_events');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_user_events()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_user_events');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin event creation
     *
     *
     * @author Selva 
     */

	public function sub_admin_event_prop()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_event_prop');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_create_feed_forms()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_create_feed_forms');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_manage_feed_forms()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_manage_feed_forms');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_assigned_feed()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_assigned_feed_forms');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_feedback_prop()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_feedback_prop');
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_msg()
	{
	  $this->load->view('getstarted/sub_admin/msg_dashboard');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin notification history
     *
     *
     * @author Selva 
     */

	public function sub_admin_msg_history()
	{
	  $this->load->view('getstarted/sub_admin/msg_history');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin sms dashboard
     *
     *
     * @author Selva 
     */

	public function sub_admin_sms()
	{
	  $this->load->view('getstarted/sub_admin/sms_dashboard');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub Admin sms history
     *
     *
     * @author Selva 
     */

	public function sub_admin_sms_history()
	{
	  $this->load->view('getstarted/sub_admin/sms_history');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub admin profile page
     *
     *
     * @author Selva 
     */

	public function sub_admin_profile()
	{
	  $this->load->view('getstarted/sub_admin/sub_admin_profile');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub admin profile edit
     *
     *
     * @author Selva 
     */

	public function edit_sub_admin_profile()
	{
	  $this->load->view('getstarted/sub_admin/edit_sub_admin_profile');
	  
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Sub admin change password
     *
     *
     * @author Selva 
     */

	public function sub_admin_change_pwd()
	{
		$this->load->view('getstarted/sub_admin/change_password');
	}
	
	// ====================================================== SUPPORT ADMIN HELP =================================================//
	
	 
	
	
}

/* End of file help.php */
/* Location: ./application/customers/controllers/help.php */