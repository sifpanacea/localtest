<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Code_gen extends MY_Controller
{

	function __construct()
	{
	parent::__construct();
	$this->load->library('session');
	
	$this->load->library('ion_auth');
	$this->load->library('form_validation');
	$this->load->library('paas_common_lib');
	$this->load->library('mongo_db');
	$this->load->library('bhashsms');
	$this->load->helper('url');
	$this->load->helper('language');
	$this->load->library('excel');
	$this->load->model('Workflow_Model');
	$this->config->load('email');
	$this->load->config('mongodb');
	$this->collections = $this->config->item('collections','ion_auth');
	$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	$this->lang->load('auth');
	$this->tab 		= chr(9);
	$this->tabx2 	= chr(9).chr(9);
	$this->tabx3 	= chr(9).chr(9).chr(9);
	$this->tabx4 	= chr(9).chr(9).chr(9).chr(9);
	$this->tabx5 	= chr(9).chr(9).chr(9).chr(9).chr(9);
	$this->tabx6 	= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9);
	$this->tabx7 	= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9);
	$this->sl  		= chr(13).chr(10);
	}
	

	// --------------------------------------------------------------------

	/**
	 * Helper : Storing application related stuff in db and redirecting to workflow
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function index()
	{
	
		//Rules for validation
		$this->_set_rules();
	    
	
	    //validate the fields of form
	    if ($this->form_validation->run() == FALSE) 
	    {
	
	        $this->session->set_flashdata('message','App template not yet created');
	        redirect('/dashboard/app_prop');
	    }
	    else
	    {
	

		    $controller_name = $this->input->post('controller_name', TRUE);
		    $app_id          = substr($controller_name, 0,strpos($controller_name, "_"));
		    $app_name        = $this->input->post('appName', TRUE);
		    $app_description = $this->input->post('appDescription',TRUE);
		    $app_type        = $this->input->post('apptype',TRUE);
			$header_type     = $this->input->post('headertype',TRUE);
			$blank_app     = $this->input->post('blank_app',TRUE);
		    $app_expiry      = $this->input->post('appexpiry',TRUE);
		    $app_category    = $this->input->post('appcategory',TRUE);
		    $scaffold_code	 = $this->input->post('scaffold_code', TRUE);
		    $app_template    = json_decode("{".$scaffold_code."}", TRUE);
		    $updType         = $this->input->post('updType', TRUE );
			$pagenumber      = $this->input->post('pagenumber', TRUE );
			$appcomplete     = $this->input->post('appcomplete', TRUE );
			$print_temp      = $this->input->post('print',TRUE);
			$notify          = $this->input->post('notify_values',TRUE);
			$notify_param    = json_decode($notify,TRUE);
			$appheader       = $this->input->post('header_values',TRUE);
			$application_header = json_decode($appheader,TRUE);
            $status          = intval($appcomplete);
			$print_template  = json_decode("{".$print_temp."}", TRUE);
           
			$userdetail  = $this->ion_auth->customer()->row();
			$useremail   = $userdetail->email;
			$usercompany = $userdetail->company_name;

			$view_data = array(
	        'controller_name'		=>	$controller_name,
	        'model_name'			=>	$this->input->post('model_name', TRUE),
	        'appName'				=>	$app_name,
			'appDescription'        =>  $app_description,
			'apptype'				=>	$app_type,
			'companyname'           =>  $this->input->post('companyname',TRUE),
			'companyaddress'        =>  $this->input->post('companyaddress',TRUE),
			'updType'               =>  $this->input->post('updType',TRUE )
	        ); 
		

		    if($updType == "edit")
		    {
		    	$version_with_id = $this->mongo_db->where('_id', $app_id)->select(array('_version','workflow'),array())->get($this->collections['records']);
	            foreach($version_with_id as $ver)
		        {
		           $existversion = $ver['_version'];
				   $workflow     = $ver['workflow'];
		        }
		        $newversion = $existversion + 1;

                $data['app_name']           = $app_name;
		        $data['app_template']       = $app_template;
				$data['app_expiry']         = $app_expiry; 
				$data['app_type']           = $app_type;
				$data['use_profile_header'] = $header_type;
				$data['blank_app']			= $blank_app;
				$data['app_category']      = $app_category;
				$data['app_description']   = $app_description;
				$data['pages']             = $pagenumber;
				$data['created_by']        = $useremail;
				$data['_version']          = $newversion;
				$data['status']            = $status;
				$data['print_template']    = $print_template;
				$data['notify_parameters'] = $notify_param;
				$data['application_header'] = $application_header;
				$data['time']               = date('Y-m-d H:i:s');
		
		
		        $this->mongo_db->where('_id', $app_id)->set($data)->update($this->collections['records']);
				
		        // Save in community app gallery
                if($app_type==="Shared")
                {
               		$this->load->model('Workflow_Model');
               		$this->Workflow_Model->insert_shared_app_template($usercompany,$app_id,$app_name,$app_description,$app_type,$app_expiry,$app_category,$app_template);
            	}
                
                $verdata['_version'] = $newversion;
		        $verdata['app_name'] = $app_name;
		        $this->mongo_db->where('app_id', $app_id)->set($verdata)->update($this->collections['analytics_data']);
		        $view_data['workflow_mode'] = "edit";
				$view_data['workflow']      = $workflow;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$view_data = array_merge($view_data,$data_bubble_count);
				
	            $this->_render_page('workflow/workflow',$view_data);
		

		    }
		    elseif($updType == "draft")
		    {
		    	$version_with_id = $this->mongo_db->where('_id', $app_id)->select(array('_version'),array())->get($this->collections['records']);
	            foreach($version_with_id as $ver)
		        {
		           $existversion = $ver['_version'];
		        }

		        $data['app_name']         = $app_name;
		        $data['app_template']     = $app_template;
				$data['app_expiry']       = $app_expiry; 
				$data['app_type']         = $app_type;
				$data['use_profile_header'] = $header_type;
				$data['blank_app']			= $blank_app;
				$data['app_category']     = $app_category;
				$data['app_description']  = $app_description;
				$data['pages']            = $pagenumber;
				$data['created_by']       = $useremail;
				$data['_version']         = $existversion;
				$data['status']           = $status;
				$data['print_template']   = $print_template;
				$data['notify_parameters'] = $notify_param;
				$data['application_header'] = $application_header;
				$data['time']               = date('Y-m-d H:i:s');
		
		
		        $this->mongo_db->where('_id', $app_id)->set($data)->update($this->collections['records']);
                
                // Save in community app gallery
                if($app_type==="Shared")
                {
               		$this->load->model('Workflow_Model');
               		$this->Workflow_Model->insert_shared_app_template($usercompany,$app_id,$app_name,$app_description,$app_type,$app_expiry,$app_category,$app_template);
            	}


                $verdata['app_name'] = $app_name;
		        $this->mongo_db->where('app_id', $app_id)->set($verdata)->update($this->collections['analytics_data']);
		        $view_data['workflow_mode'] = "draft";
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$view_data = array_merge($view_data,$data_bubble_count);
				
	            $this->_render_page('workflow/workflow',$view_data);

		    }
		    else
		    {
		    	// Save in community app gallery
                if($app_type==="Shared")
                {
               		$this->load->model('Workflow_Model');
               		$this->Workflow_Model->insert_shared_app_template($usercompany,$app_id,$app_name,$app_description,$app_type,$app_expiry,$app_category,$app_template);
            	}


		    	$version = 1;
		    	$this->mongo_db->create_collection($app_id);
				$this->mongo_db->create_collection($app_id."_shadow");
				$index_array = array();
				
                // foreach($app_template as $pages => $page_data)
				// {
                   // foreach ($page_data as $section => $section_data)
				   // {
                     // foreach($section_data as $elem_name => $elem_data)
					 // {
                         // if($elem_name != 'dont_use_this_name')
						 // {
                            // $pg = $pages;
                            // $index_array['widget_data.page'.$pg.'.'.$section.'.'.$elem_name] = 1;
                            // $indexresult = $this->mongo_db->addIndex($app_id,$index_array);
                            // $index_array = array();
                          // }
                     // }
                  // }
                // }
               
				$this->mongo_db->insert_application_in_collection($this->collections['records'], $app_id,$app_template,$app_name,$app_description,$app_type,$app_expiry,$app_category,$useremail,$pagenumber,$version,$status,$print_template,$notify_param,$application_header,$header_type,$blank_app);
                $this->mongo_db->create_analytics_data_collection($this->collections['analytics_data'],$app_name,$version,$app_id);
                $view_data['workflow_mode'] = "create";
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$view_data = array_merge($view_data,$data_bubble_count);
		
	            $this->_render_page('workflow/workflow',$view_data);
		    }

       }

    } 

	// --------------------------------------------------------------------

	/**
	* Helper : Get health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_panacea_hs_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->panacea_helath_supervisors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   	// --------------------------------------------------------------------

	/**
	* Helper : Get panacea doctors list to populate in workflow part
	*
	* @author  Vikas
	*
	* 
	*/

	function get_panacea_doctors_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->panacea_doctors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   	function get_ttwreis_admin_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->ttwreis_admin_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
    function get_ttwreis_cc_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->ttwreis_cc_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
       function get_ttwreis_doctors_list()
	{log_message('debug','ddddddddddddddddddddddddddddddd111111111111111111111111111111111111111');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->ttwreis_doctors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------

	/**
	* Helper : Get TTWREIS health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_ttwreis_hs_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_ttwreis_hs_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->ttwreis_health_supervisors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   //TMREIS =====================================
     function get_tmreis_admin_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_tmreis_admin_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->tmreis_admin_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
    function get_tmreis_cc_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_tmreis_cc_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->tmreis_cc_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
       function get_tmreis_doctors_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_tmreis_doctors_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->tmreis_doctors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------

	/**
	* Helper : Get TTWREIS health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_tmreis_hs_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_tmreis_hs_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->tmreis_health_supervisors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------
   
    //TMREIS =====================================
     function get_bc_welfare_admin_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_bc_welfare_admin_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->bc_welfare_admin_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
    function get_bc_welfare_cc_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_bc_welfare_cc_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->bc_welfare_cc_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
       function get_bc_welfare_doctors_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_bc_welfare_doctors_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->bc_welfare_doctors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------

	/**
	* Helper : Get TTWREIS health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_bc_welfare_hs_list()
	{log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn get_bc_welfare_hs_list');
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->bc_welfare_health_supervisors_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   //=====================================

	/**
	* Helper : Get health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_panacea_admin_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->panacea_admin_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------

	/**
	* Helper : Get health supervisors list to populate in workflow part
	*
	* @author  Selva
	*
	* 
	*/

	function get_panacea_cc_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->panacea_cc_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }
   
   // --------------------------------------------------------------------

	/**
	* Helper : Get RHSO's users list to populate in workflow part
	*
	* @author  Bhanu
	*
	* 
	*/

	function get_rhso_users_list()
	{
	   $this->load->model('Workflow_Model');
	   $data['users']=$this->Workflow_Model->get_rhso_users_model();
	   $userlist = array();
	   foreach ($data['users'] as $user)
	   {
	     $userlist[] = $user['email'];
	   }
	   $users = array_values($userlist);
	   $user = str_replace("@", "#", $users);
	   $this->output->set_output(json_encode(array_unique($user)));
   }


	// --------------------------------------------------------------------

	/**
	 * Helper : Workflow mainpage
	 *
	 * @author  Vikas
	 *
	 * 
	 */

	function workflow()
	{
	
		$this->data['title'] = "Workflow_Main_Page";
		$this->data['message'] = "Template successfully created";
	
		{
		  //bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		  $this->_render_page('workflow/workflow', $this->data);
		}
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Get users list (of respective enterprise) to populate in workflow part
	 *
	 * @author  Selva
	 *
	 * 
	 */

    function get_user_list()
	{
		$name = $this->input->post('name');
		$this->load->model('Workflow_Model');
		$data['users']=$this->Workflow_Model->users($name);
		$userlist=array();
		foreach ($data['users'] as $user)
		{
			 
			$userlist[] = $user['email'];
	
		}
		$users = array_values($userlist);
		$user = str_replace("@", "#", $users);
		$this->output->set_output(json_encode(array_unique($user)));
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get groups list (of respective enterprise) to populate in workflow part
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function get_group_list()
	{
		$groups = $this->ion_auth->groups()->result();
		$name = array();
		foreach($groups as $group)
		{
			array_push($name,$group->name);
		}
	
	    $this->output->set_output(json_encode($name));
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

	function getpost()
	{
		if (isset($_POST['jsonval']))
		{
         	$jsonstring    = $_POST['jsonval'];
			$jsonobj       = json_decode($jsonstring);
			$jsonarray     = (array) $jsonobj;

		    $app_id        = $_POST['app_id'];
			$comp_name     = $_POST['comp_name'];
			$comp_addr     = $_POST['comp_addr'];
			$workflow_mode = $_POST['workflow_mode'];
			$app_name      = $_POST['app_name'];

	
			$this->load->model('Workflow_Model');
			$this->Workflow_Model->save($jsonarray,$app_id);
			$this->insert_user_application_collection($jsonarray,$app_id,$comp_name,$comp_addr,$workflow_mode);
		}
		else
		{
			$this->data['title'] = "Workflow_Main_Page";
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
			$this->_render_page('workflow/workflow', $this->data);
  		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function get_api_details()
	{
		$docss = $this->ion_auth->get_col_docs('api_details');
		$this->output->set_output(json_encode($docss));
	}
    
    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function get_app_con()
	{
		$app_con = (isset($_POST['app_id'])?$_POST['app_id']:'not yet');
		$data['users']=$this->Workflow_Model->getappcon($app_con);
		$userlist=array();
		foreach ($data['users'] as $user)
		{
			$userlist[] = $user['app_template'];
			
		}
	
		$this->output->set_output(json_encode($userlist));
	
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function get_api_users()
	{
		$api_coll=$this->input->post('colname');
		$user_coll = $api_coll.'_users';
	    $docss = $this->ion_auth->get_all_docs($user_coll);
		$this->output->set_output(json_encode($docss));
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Get Sections of the respective app to populate in workflow part
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function get_section_list()
	{
		$app_con = (isset($_POST['app_id'])?$_POST['app_id']:'not yet');
		
		$data['app']=$this->Workflow_Model->get_app_template($app_con);
		$sectionlist= array();
		$sect = array();
		foreach($data['app'] as $sec)
		{
	
			$sectionlist = $sec['app_template'];
		}
	
		foreach ($sectionlist as $pages => $page)
		{
			foreach($page as $page_data => $element){
	
				array_push($sect, $page_data);
			}
		}
	
		$result = explode(",", implode(",", array_unique($sect)));
		$this->output->set_output(json_encode($result));
	
	}
     
	// --------------------------------------------------------------------

	/**
	 * Helper : Process workflow json for getting all stage names
	 *
	 *
	 * @param  array jsonarray  Workflow json
	 *
	 * 
	 * @author  Selva
	 */
	
	function process_workflow_for_stagenames($app_id)
	{
		$perstagedata_         = array();
	    $workflow_type_        = array();
	    $allstages_            = array();
	    $stage_name_           = array();
	    $singlestagename_      = array();
	    $parallelstagename_    = array();
	    $conditionalstagename_ = array();

	    //-------------------------------------------------------------------------------------------//

	     $jsonarraynew = $this->Workflow_Model->workflow_for_processing_stagenames($app_id);

	     log_message('debug','jsonarraynew=====444'.print_r($jsonarraynew,true));

	      foreach($jsonarraynew[0] as $worktemplate_ => $template_)
		  {
		  	 foreach($template_ as $temp_ => $t_)
		  	 {
		     	array_push($allstages_,$temp_);
		     	array_push($perstagedata_,$t_);
		     }
		  }

		    $count_ = count($perstagedata_);
			for ($ij = 0; $ij < $count_; $ij++)
			{
				array_push($workflow_type_,$perstagedata_[$ij]['Workflow_Type']);
				if($workflow_type_[$ij] == "single")
				{
					$singlestagename_ = $this->get_single_stage_stagename($allstages_[$ij]);
					array_push($stage_name_,$singlestagename_);
				}
				
				if($workflow_type_[$ij] == "parallel")
				{
					log_message('debug','perstagedata=====77'.print_r($perstagedata_,true));
					$arrayPar_ = $perstagedata_[$ij];
					unset($arrayPar_['Workflow_Type']);
					$parallelstagename_= $this->get_parallel_stage_stagename($arrayPar_);
					$stage_name_ = array_merge($stage_name_,$parallelstagename_);
					log_message('debug','perstagedata=====7711'.print_r($perstagedata_,true));
				}
				
				if($workflow_type_[$ij] == "conditional")
				{
					$arrayCon = $perstagedata_[$ij];
					$conditionalstagename_ = $this->get_conditional_stage_stagename($arrayCon);
					$stage_name_ = array_merge($stage_name_,$conditionalstagename_);
				}
		    }
		    return $stage_name_;

    }

    // --------------------------------------------------------------------

	/**
	 * Helper : Get stagename - single stage
	 *
	 * @param string $stage stage name
	 *
	 * @return string
	 * 
	 * @author  Selva
	 */

    function get_single_stage_stagename($stage)
    {
    	return $stage;
    }

    // --------------------------------------------------------------------

	/**
	 * Helper : Get stagename - parallel stage
	 *
	 * @param  array  $arrayPar  Parallel workflow json
     *
	 *
	 * @return array
	 *
	 * 
	 * @author  Selva
	 */

    function get_parallel_stage_stagename($arrayPar_)
    {
	       
		$arrayCon              = array();
		$branchs               = array();
		$parallel_perstagedata = array();
		$parallelbranches      = array();
		$parallelbranchwtype   = array();
		$parallelstagedata     = array();
		$stagename             = array();
		
		foreach($arrayPar_ as $paral => $para)
		{
			array_push($branchs,$paral);
		}
		
		foreach ($branchs as $perbranch)
		{
			$parallelstagenames = array();

			if(isset($parallel_perstagedata))
			{
				array_shift($parallel_perstagedata);
			}
			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}
			foreach($arrayPar_[$perbranch] as $branchname => $branch)
			{
				array_push($parallelbranches,$branchname);
				array_push($parallel_perstagedata,$branch);
			}
		
			$parallelstagecount = count($parallel_perstagedata);
		
			for($kk=0;$kk<$parallelstagecount;$kk++)
			{
				array_push($parallelbranchwtype,$parallel_perstagedata[$kk]['Workflow_Type']);
				if($parallelbranchwtype[$kk]=="single")
				{
					if(isset($parallel_perstagedata[$kk-1]))
					{
						unset($parallel_perstagedata[$kk-1]);
					}
		
					if(isset($parallelstagenames[$kk-1]))
					{
						unset($parallelstagenames[$kk-1]);
					}
		
		
					foreach($arrayPar_[$perbranch] as $branchname => $branch)
					{
						if($branch['Workflow_Type']=="single")
						{
							array_push($parallelstagenames,$branchname);
		
						}
					}
					$singlestagename = $this->get_single_stage_stagename($parallelstagenames[$kk]);
					array_push($stagename,$singlestagename);
				}

				if($parallelbranchwtype[$kk]=="parallel")
				{
					$arrayParnew = $parallel_perstagedata[$kk];
					unset($arrayParnew['Workflow_Type']);
					$parallelstagename = $this->get_parallel_stage_stagename($arrayParnew);
					$stagename = array_merge($stagename,$parallelstagename);
				}

				if($parallelbranchwtype[$kk]=="conditional")
				{
					$arrayCon = $parallel_perstagedata[$kk];
					$conditionalstagename = $this->get_conditional_stage_stagename($arrayCon);
					$stagename = array_merge($stagename,$conditionalstagename);
				}
		    }

	    }

	     return $stagename;
    } 

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get stagename - Conditional stage
	 *
	 * @param  array  $arrayCon  Conditional workflow json
     *
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

    function get_conditional_stage_stagename($arrayCon)
    {
	    $arrayPar                           = array();
		$approvedstage                      = array();
		$disapprovedstage                   = array();
		$conditionalperstagedata            = array();
		$conditionaldisapprovedperstagedata = array();
		$conditionalapprovedwtype           = array();
		$conditionaldisapprovedwtype        = array();
		$conditionalperstagedata            = array();
		$conditionalapprovedstagename       = array();
		$conditionaldisapprovedstagename    = array();
		$approvedstagenames                 = array();
		$disapprovedstagenames              = array();
		$disapprovedconditionalstagedata    = array();
		$stage_name                         = array();
		
		array_push($approvedstage,$arrayCon['approved']);
		array_push($disapprovedstage,$arrayCon['disapproved']);

		//---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
		foreach($approvedstage as $worktemplate => $temp)
		{
		  foreach($temp as $stagename => $t)
		  {
			array_push($conditionalperstagedata,$t);
		  }
		}

		$conditionalperstagedatacount = count($conditionalperstagedata);
		
		for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
		{
		  array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);
		  if($conditionalapprovedwtype[$ii]=="single")
		  {
		    foreach($approvedstage as $worktemplate => $temp)
		    {
		      foreach($temp as $stagename => $t)
		      {
		        if($t['Workflow_Type']=="single")
		        {
					array_push($approvedstagenames,$stagename);
		        }
		      }
		    }		 
		    $conditionalapprovedstagename = $this->get_single_stage_stagename($approvedstagenames[$ii]);
		    array_push($stage_name,$conditionalapprovedstagename);
		  }

		  if($conditionalapprovedwtype[$ii]=="parallel")
		  {
			$arrayPar = $conditionalperstagedata[$ii];
			unset($arrayPar['Workflow_Type']);
			//$this->get_parallel_stage_stagename($arrayPar);
			$approvedparallelstagename = $this->get_parallel_stage_stagename($arrayPar);
		    $stage_name = array_merge($stage_name,$approvedparallelstagename);
		  }

		  if($conditionalapprovedwtype[$ii]=="conditional")
		  {
		    $arrayConnew = $conditionalperstagedata[$ii];
		    //$this->get_conditional_stage_stagename($arrayConnew);
		    $approvedconditionalstagename = $this->get_conditional_stage_stagename($arrayConnew);
		    $stage_name = array_merge($stage_name,$approvedconditionalstagename);
		  }
	   }
		
	   //-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
	   foreach($disapprovedstage as $worktemplate => $temp)
	   {
		foreach($temp as $stagename => $t)
		{
		   array_push($conditionaldisapprovedperstagedata,$t);
		}
	   }

	   $conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);

	   for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
	   {
		  array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);
		  if($conditionaldisapprovedwtype[$jj]=="single")
	      {
		      foreach($disapprovedstage as $worktemplate => $temp)
		      {
		        foreach($temp as $stagename => $t)
		        {
		           if($t['Workflow_Type']=="single")
		           {
		             array_push($disapprovedstagenames,$stagename);
		           }
		        }
		     }

		     $conditionaldisapprovedstagename = $this->get_single_stage_stagename($disapprovedstagenames[$jj]);
		     array_push($stage_name,$conditionaldisapprovedstagename);
		  }

		  if($conditionaldisapprovedwtype[$jj]=="parallel")
		  {
		    $arrayPar = $conditionaldisapprovedperstagedata[$jj];
		    unset($arrayPar['Workflow_Type']);
		    //$this->get_parallel_stage_stagename($arrayPar);
		    $disapprovedparallelstagename = $this->get_parallel_stage_stagename($arrayPar);
		    $stage_name = array_merge($stage_name,$disapprovedparallelstagename);
		  }

		  if($conditionaldisapprovedwtype[$jj]=="conditional")
		  {
		    $arrayConnew = $conditionaldisapprovedperstagedata[$jj];
		    //$this->get_conditional_stage_stagename($arrayCon);
		    $disapprovedconditionalstagename = $this->get_conditional_stage_stagename($arrayConnew);
		    $stage_name = array_merge($stage_name,$disapprovedconditionalstagename);
		  }
	   }

		return $stage_name;

    }
	

	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's application collection
	 *
	 * @param  array  $jsonarray      Workflow json
	 * @param  string $app_id         Application id
	 * @param  string $companyname    Company name
	 * @param  string $companyaddress Company address
	 * @param  string $workflow_mode  Workflow mode (create or edit etc.,)
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function insert_user_application_collection($jsonarray,$app_id,$companyname,$companyaddress,$workflow_mode)
	{
		$perstagedata    = array();
		$singlestagedata = array();
		$workflow_type   = array();
		$stages          = array();
		$allstages       = array();
		$stagenames      = array();
		$jsonarraynew    = array();

		//-------------------------------------------------------------------------------------------//
	
		$apptemplate = $this->Workflow_Model->get_app_template($app_id);
		foreach($apptemplate as $app)
		{
	        $app_temp                = $app['app_template'];
			$appname                 = $app['app_name'];
			$appid                   = $app['_id'];
			$appdescription          = $app['app_description'];
			$appcreated              = $app['time'];
			$appexpiry               = $app['app_expiry'];
			$version                 = $app['_version'];
			$notification_parameters = $app['notify_parameters'];
			$application_header      = $app['application_header'];
			$created_by              = $app['created_by'];
			$use_profile_header      = $app['use_profile_header'];
			$blank_app      		 = $app['blank_app'];
		}
	
		$jsonarraynew   = $jsonarray;

		foreach($jsonarray as $worktemplate => $temp)
		{
            array_push($allstages,$worktemplate);
			array_push($perstagedata,$temp);
		}

		//------------------------------------------------------------------------------------------------------------------//
	
		 $stagenames = $this->process_workflow_for_stagenames($appid);
	
		//------------------------------------------------------------------------------------------------------------------//
	
		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
			array_push($workflow_type,$perstagedata[$i]->Workflow_Type);

			if($workflow_type[$i] == "single")
			{
				$this->single_stage_workflow($perstagedata[$i],$allstages[$i],$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
	        }
	
			if($workflow_type[$i] == "parallel")
			{
				$arrayPar = $perstagedata[$i];
				unset($arrayPar->Workflow_Type);
				$this->parallel_stage_workflow($arrayPar,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
            }
	
			if($workflow_type[$i] == "conditional")
			{
				$arrayCon = $perstagedata[$i];
				$this->conditional_stage_workflow($arrayCon,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}
	
		}
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's application collection - SINGLE STAGE Processing
	 *
	 * @param  array  $perstagedata   			Single stage workflow json
	 * @param  string $stage          			Stage name
	 * @param  string $app_temp       			Application template
	 * @param  string $appdescription 			Description of the app
	 * @param  string $appid          			Application id
	 * @param  string $appname        			Application name
	 * @param  string $appcreated     			Application created date & time
	 * @param  string $companyname    			Company name
	 * @param  string $companyaddress 			Company address
	 * @param  string $appexpiry      			Application expiry date
	 * @param  string $version        			Application version
	 * @param  string $workflow_mode            Workflow mode (create or edit etc.,)
	 * @param  array  $stagenames               Array of all stages of that application workflow
	 * @param  array  $notification_parameters  Array of all parameters chosen for notification
	 * @param  string $created_by               Identity of the admin who created the application
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function single_stage_workflow($perstagedata,$stage,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app)
	{
		$users      = array();
		$vp         = array();
		$ep         = array();
		$stype      = array();
		$app_temp1  = array();
		$stageindex = array();

		array_push($users,$perstagedata->UsersList);
		array_push($vp,$perstagedata->View_Permissions);
		array_push($ep,$perstagedata->Edit_Permissions);
		array_push($stype,$perstagedata->Stage_Type);
		array_push($stageindex,$perstagedata->index);

		$app_temp1[$stage]['View_Permissions'] = $vp[0];
		$app_temp1[$stage]['Edit_Permissions'] = $ep[0];
		$app_temp1[$stage]['index']            = $stageindex[0];
	
		foreach($users[0] as $inneruser)
		{
			$this->load->model('Workflow_Model');
			$this->Workflow_Model->insert_user_applist($inneruser.'_applist',$appname,$appid,$appdescription,$appcreated,$appexpiry,$version,$created_by);
			if($stype[0]=="device")
			{
				if($stageindex[0]==1)
				{
			       if($workflow_mode=="edit")
				   {
			          $this->Workflow_Model->remove_app_from_user_appcollection($inneruser.'_apps',$appid);
				   }
				}
				
				$this->Workflow_Model->insert_user_appcollection($inneruser.'_apps',$app_temp,$appdescription,$app_temp1,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}
			else if($stype[0]=="hybrid")
			{
				if($stageindex[0]==1)
				{
			       if($workflow_mode=="edit")
				   {
			          $this->Workflow_Model->remove_app_from_user_appcollection($inneruser.'_apps',$appid);
			          $this->Workflow_Model->remove_app_from_user_appcollection($inneruser.'_web_apps',$appid);
				   }
				   
				   $this->Workflow_Model->insert_user_web_appcollection($inneruser.'_web_apps',$app_temp1,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$created_by,$use_profile_header,$blank_app);
				}
				
				$this->Workflow_Model->insert_user_appcollection($inneruser.'_apps',$app_temp,$appdescription,$app_temp1,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
				
			}
			else
			{
				if($stageindex[0]==1)
				{
					if($workflow_mode=="edit")
				    {
			          $this->Workflow_Model->remove_app_from_user_appcollection($inneruser.'_web_apps',$appid);
				    }
					
					$this->Workflow_Model->insert_user_web_appcollection($inneruser.'_web_apps',$app_temp1,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$created_by,$use_profile_header,$blank_app);
	            }
			}
	    }
	
	    unset($app_temp1[$stage]);
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's application collection - PARALLEL STAGE Processing
	 *
	 * @param  array  $arrayPar       			 Parallel stage workflow json
	 * @param  string $app_temp       			 Application template
	 * @param  string $appdescription 			 Description of the app
	 * @param  string $appid          			 Application id
	 * @param  string $appname        			 Application name
	 * @param  string $appcreated     			 Application created date & time
	 * @param  string $companyname    			 Company name
	 * @param  string $companyaddress 			 Company address
	 * @param  string $appexpiry      			 Application expiry date
	 * @param  string $version        			 Application version
	 * @param  string $workflow_mode  			 Workflow mode (create or edit etc.,)
	 * @param  array  $stagenames     			 Array of all stages of that application workflow
	 * @param  array  $notification_parameters   Array of all parameters chosen for notification
	 * @param  string $created_by                Identity of the admin who created the application
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function parallel_stage_workflow($arrayPar,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app)
	{
		$arrayCon             = array();
		$branchs              = array();
		$parallelperstagedata = array();
		$parallelbranches     = array();
		$parallelbranchwtype  = array();
		$parallelstagedata    = array();
	
		foreach($arrayPar as $paral => $para)
		{
			array_push($branchs,$paral);
		}
	
		foreach ($branchs as $perbranch)
		{
			$parallelstagenames = array();
	
			if(isset($parallelperstagedata))
			{
				array_shift($parallelperstagedata);
			}
			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}
			if(isset($parallelbranchwtype))
			{
				array_shift($parallelbranchwtype);
			}

			foreach($arrayPar->$perbranch as $branchname => $branch)
			{
				array_push($parallelbranches,$branchname);
				array_push($parallelperstagedata,$branch);
			}
	
			$parallelstagecount = count($parallelperstagedata);
	
			for($kk=0;$kk<$parallelstagecount;$kk++)
			{
				array_push($parallelbranchwtype,$parallelperstagedata[$kk]->Workflow_Type);
				if($parallelbranchwtype[$kk]=="single")
				{
					if(isset($parallelperstagedata[$kk-1]))
					{
						unset($parallelperstagedata[$kk-1]);
					}
	
					if(isset($parallelstagenames[$kk-1]))
					{
						unset($parallelstagenames[$kk-1]);
					}
	
	
					foreach($arrayPar->$perbranch as $branchname => $branch)
					{
						if($branch->Workflow_Type=="single")
						{
							array_push($parallelstagenames,$branchname);
	
						}
					}
	
					$this->single_stage_workflow($parallelperstagedata[$kk],$parallelstagenames[$kk],$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
				}

	            if($parallelbranchwtype[$kk]=="parallel")
	            {
	
	               $arrayParnew = $parallelperstagedata[$kk];
	               unset($arrayParnew->Workflow_Type);
	               $this->parallel_stage_workflow($arrayParnew,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
	             }

				if($parallelbranchwtype[$kk]=="conditional")
				{
				   $arrayCon = $parallelperstagedata[$kk];
				   $this->conditional_stage_workflow($arrayCon,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
	            }
	       }
	    }
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's application collection - CONDITIONAL STAGE Processing
	 *
	 * @param  array  $arrayCon       			Conditional stage workflow json
	 * @param  string $app_temp       			Application template
	 * @param  string $appdescription 			Description of the app
	 * @param  string $appid          			Application id
	 * @param  string $appname        			Application name
	 * @param  string $appcreated     			Application created date & time
	 * @param  string $companyname    			Company name
	 * @param  string $companyaddress 			Company address
	 * @param  string $appexpiry      			Application expiry date
	 * @param  string $version        			Application version
	 * @param  string $workflow_mode  			Workflow mode (create or edit etc.,)
	 * @param  array  $stagenames     			Array of all stages of that application workflow
	 * @param  array  $notification_parameters  Array of all parameters chosen for notification
	 * @param  string $created_by               Identity of the admin who created the application
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function conditional_stage_workflow($arrayCon,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app)
	{
	  $arrayPar                           = array();
	  $approvedstage                      = array();
	  $disapprovedstage                   = array();
	  $conditionalperstagedata            = array();
	  $conditionalapprovedwtype           = array();
	  $conditionaldisapprovedwtype        = array();
	  $conditionaldisapprovedperstagedata = array();
	  $conditionalperstagedata            = array();
	  $conditionalstagedata               = array();
	  $approvedstagenames                 = array();
	  $disapprovedstagenames              = array();
	  $disapprovedconditionalstagedata    = array();
	
	  array_push($approvedstage,$arrayCon->approved);
	  array_push($disapprovedstage,$arrayCon->disapproved);

	  //---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
	  foreach($approvedstage as $worktemplate => $temp)
	  {
	 	 foreach($temp as $stagename => $t)
	 	 {
			array_push($conditionalperstagedata,$t);
	     }
	  }
	  
	  $conditionalperstagedatacount = count($conditionalperstagedata);
	  for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
	  {
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]->Workflow_Type);
			if($conditionalapprovedwtype[$ii]=="single")
			{
				foreach($approvedstage as $worktemplate => $temp)
				{
					foreach($temp as $stagename => $t)
					{
						if($t->Workflow_Type=="single")
						{
							array_push($approvedstagenames,$stagename);
						}
					}
				}		 
				$this->single_stage_workflow($conditionalperstagedata[$ii],$approvedstagenames[$ii],$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}
	
	        if($conditionalapprovedwtype[$ii]=="parallel")
			{
				$arrayPar = $conditionalperstagedata[$ii];
				unset($arrayPar->Workflow_Type);
				$this->parallel_stage_workflow($arrayPar,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}

			if($conditionalapprovedwtype[$ii]=="conditional")
			{	
				$arrayConnew = $conditionalperstagedata[$ii];
				$this->conditional_stage_workflow($arrayConnew,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
	
			}
	   }
	
	   //-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
	   foreach($disapprovedstage as $worktemplate => $temp)
	   {
			foreach($temp as $stagename => $t)
			{
				array_push($conditionaldisapprovedperstagedata,$t);
			}
	   }

	   $conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);

	   for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
	   {
	      	array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]->Workflow_Type);
			if($conditionaldisapprovedwtype[$jj]=="single")
			{
				foreach($disapprovedstage as $worktemplate => $temp)
				{
					foreach($temp as $stagename => $t)
					{
						if($t->Workflow_Type=="single")
						{
							array_push($disapprovedstagenames,$stagename);
						}
					}
				}
				$this->single_stage_workflow($conditionaldisapprovedperstagedata[$jj],$disapprovedstagenames[$jj],$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}

			if($conditionaldisapprovedwtype[$jj]=="parallel")
			{
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar->Workflow_Type);
				$this->parallel_stage_workflow($arrayPar,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}

			if($conditionaldisapprovedwtype[$jj]=="conditional")
			{
				$arrayCon = $conditionaldisapprovedperstagedata[$jj];
				$this->conditional_stage_workflow($arrayCon,$app_temp,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app);
			}
		}
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's email id for notification part (Redirect to notifications part from workflow part)
	 *
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	 function todashnew()
	 {
	
		$perstagedata          = array();
		$singlestagedata       = array();
		$workflow_type         = array();
		$stages                = array();
		$allstages             = array();
		$users                 = array();
		$singleuser            = array();
		$singlearrayusers      = array();
		$paralleluser          = array();
		$parallelarrayusers    = array();
		$conditionalarrayusers = array();
	
		//-------------------------------------------------------------------------------------------//
	
	
	   	$app_id    = $_GET['app_id'];
		$comp_name = $_GET['comp_name'];
		$comp_addr = $_GET['comp_addr'];
		$app_name  = $_GET['app_name'];
		$app_des   = $_GET['app_des'];
		$app_mod   = $_GET['app_mod'];
		
		// MVC creation
		$this->create_mvc($app_id,$app_mod,$comp_name);
		$this->create_code_gen_view($app_id,$app_mod,$comp_name);

	    $wtemp = $this->Workflow_Model->userfornotifyapp($app_id);
	
		//------------------------------------------------------------------------------------------------------------------//
	
		foreach($wtemp[0] as $worktemplate => $template)
		{
			foreach($template as $temp => $t)
			{
	
				array_push($allstages,$temp);
				array_push($perstagedata,$t);
	        }
	
	    }
	
		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
			array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);

			if($workflow_type[$i] == "single")
			{
				$singleuser = $this->get_single_stage_users($perstagedata[$i]);
				$singlearrayusers = call_user_func_array('array_merge', $singleuser);
				$users = array_merge($users,$singlearrayusers);
			}
	
			if($workflow_type[$i] == "parallel")
			{
				$arrayPar = $perstagedata[$i];
				unset($arrayPar['Workflow_Type']);
	            $paralleluser = $this->get_parallel_stage_users($arrayPar);
				$parallelarrayusers = call_user_func_array('array_merge', $paralleluser);
				$users = array_merge($users,$parallelarrayusers);
			}
	
			if($workflow_type[$i] == "conditional")
			{
				$arrayCon = $perstagedata[$i];
				$conditionaluser = $this->get_conditional_stage_users($arrayCon);
				$conditionalarrayusers = call_user_func_array('array_merge', $conditionaluser);
				$users = array_merge($users,$conditionalarrayusers);
	        }
	
	    }
	
	    $this->data['message']   = "Workflow successfully created";
		$this->data['userlist']  = array_unique($users);
    	$this->data['app_id']    = $app_id;
		$this->data['comp_name'] = $comp_name;
		$this->data['comp_addr'] = $comp_addr;
		$this->data['app_name']  = $app_name;
		$this->data['app_des']   = $app_des;
		$this->data['app_mod']   = $app_mod;
		
		// CUSTOM NOTIFICATION
		$this->custom_notification($this->data);
	
	}
	
	function custom_notification($data)
	{
	   // VARIABLES
	   $allstagenames                = array();
	   $perstagedata                 = array();
	   $workflow_type                = array();
	   $custom_part                  = array();
	   $singlestagedata              = array();
	   $parallelstagedata            = array();
	   $conditionalstagedata         = array();
	   $edit_per                     = array();
	   $edit_permissions             = array();
	   $first_round_edit_permissions = array();
	  
	   $application_id = substr($data['app_id'], 0,strpos($data['app_id'], "_"));
	   $app_details = $this->Workflow_Model->fetch_app_details_for_custom_notification($application_id);
	   $application_template = $app_details[0]['app_template'];
	   $workflow_template    = $app_details[0]['workflow'];
	   
	   foreach($workflow_template as $stage_name => $w_template)
	   {
		  array_push($allstagenames,$stage_name);
		  array_push($perstagedata,$w_template);
	   }
	   
	   $count = count($perstagedata);
	   for ($i = 0; $i < $count; $i++)
       {
		  array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);
          
		  if($workflow_type[$i] == "single")
		  {
		    $sms_check = $perstagedata[$i]['sms'];
            if($sms_check=="true")
            { 
				if($i==0)
				{
			      $first_round_edit_permissions = $perstagedata[$i]['Edit_Permissions'];
				  $edit_permissions = array_merge($perstagedata[$i]['Edit_Permissions'],$edit_per);			
				  $singlestagedata = $this->get_single_stage_data_for_custom_notification($perstagedata[$i],$allstagenames[$i],$application_template,$edit_permissions);
				  $custom_part = array_merge($custom_part,$singlestagedata);
				}
				else if($i>0)
				{
					$edit_permissions = array_merge($perstagedata[$i]['Edit_Permissions'],$first_round_edit_permissions);
					$edit_permissions = array_unique($edit_permissions);
					$singlestagedata = $this->get_single_stage_data_for_custom_notification($perstagedata[$i],$allstagenames[$i],$application_template,$edit_permissions);
					$custom_part = array_merge($custom_part,$singlestagedata);
				}
			}	
	      }
		  
		  if($workflow_type[$i] == "parallel")
		  {
		       $arrayPar = $perstagedata[$i];
			   unset($arrayPar['Workflow_Type']);
		       $parallelstagedata = $this->get_parallel_stage_data_for_custom_notification($arrayPar,$application_template,$edit_permissions);
			   $custom_part = array_merge($custom_part,$parallelstagedata);
		  }
		  
		  if($workflow_type[$i] == "conditional")
		  {    
		       $arrayCon = $perstagedata[$i];
		       $conditionalstagedata = $this->get_conditional_stage_data_for_custom_notification($arrayCon,$application_template,$edit_permissions);
			   $custom_part = array_merge($custom_part,$conditionalstagedata);
		  }
	   }
	 
	  $custom_data['custom_notification'] = $custom_part;
	  
	  //bubble count for events and feedbacks
	  $data_bubble_count = $this->paas_common_lib->admin_bubble_count();
	  
	  $this->data = array_merge($data,$data_bubble_count);
	  $this->data = array_merge($this->data,$custom_data);
	  $this->_render_page('template/notification',$this->data);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Custom Notification - SINGLE STAGE Processing
	 *
	 * @param  array  $perstagedata Single stage workflow json
	 * @param  string $stage_name   Name of the stage
	 * @param  array  $app_temp     Application template
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

	function get_single_stage_data_for_custom_notification($perstagedata,$stage_name,$app_temp,$edit_permissions)
	{
	    $custom_notification 			  = array();
		$widget                           = array();
		$to                               = array();
		$custom_notification[$stage_name] = array();
		
		$edit_permissions = array_unique($edit_permissions);
		
		foreach($app_temp as $pageno => $pages)
		{
			foreach($pages as $section => $sec)
		    {
				foreach($edit_permissions as $permission)
			    {
					if($section === $permission)
					{
						unset($sec['dont_use_this_name']);
						foreach($sec as $elename => $element)
						{
							if($element['type']!="file" && $element['type']!="photo")
							{
								if(($element['key']=="true") OR ($element['key']=="TRUE"))
								{
								  $widget_array[$elename] = array();
								  $widget_label = 'page'.$pageno.'.'.$section.'.'.$elename;
								  $widget_array[$elename] = $widget_label;
								  array_push($widget,$widget_array);
								}
							}
							  
							if($element['type']=="mobile")
							{
							   $to_label = 'page'.$pageno.'.'.$section.'.'.$elename;
							   $to_array = array(
								'name' => $elename,
								'label' => $widget_label);
							   array_push($to,$to_array);
							}
						}
				    }
			    }
			}
		    
		} 
		
		
		$custom_notification[$stage_name]['elements']  = $widget_array;
		$custom_notification[$stage_name]['to']        = $to;
		
	    return $custom_notification;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Custom Notification - PARALLEL STAGE Processing
	 *
	 * @param  array  $arrayPar     Parallel stage workflow json
	 * @param  string $app_temp     Application template
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function get_parallel_stage_data_for_custom_notification($arrayPar,$app_temp,$edit_permissions)
	{
	    $custom_part          = array();
		$arrayCon             = array();
		$branchs              = array();
		$parallelperstagedata = array();
		$parallelbranches     = array();
		$parallelbranchwtype  = array();
		$parallelstagedata    = array();
	
		foreach($arrayPar as $paral => $para)
		{
			array_push($branchs,$paral);
		}
	
		foreach ($branchs as $perbranch)
		{
			$parallelstagenames = array();
	
			if(isset($parallelperstagedata))
			{
				array_shift($parallelperstagedata);
			}
			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}
			if(isset($parallelbranchwtype))
			{
				array_shift($parallelbranchwtype);
			}

			foreach($arrayPar[$perbranch] as $branchname => $branch)
			{
				array_push($parallelbranches,$branchname);
				array_push($parallelperstagedata,$branch);
			}
	
			$parallelstagecount = count($parallelperstagedata);
	
			for($kk=0;$kk<$parallelstagecount;$kk++)
			{
				array_push($parallelbranchwtype,$parallelperstagedata[$kk]['Workflow_Type']);
				if($parallelbranchwtype[$kk]=="single")
				{
					if(isset($parallelperstagedata[$kk-1]))
					{
						unset($parallelperstagedata[$kk-1]);
					}
	
					if(isset($parallelstagenames[$kk-1]))
					{
						unset($parallelstagenames[$kk-1]);
					}
	
	
					foreach($arrayPar[$perbranch] as $branchname => $branch)
					{
						if($branch['Workflow_Type']=="single")
						{
							array_push($parallelstagenames,$branchname);
	
						}
					}
	                $sms_check = $parallelperstagedata[$kk]['sms'];
                    if($sms_check=="true")
                    {
					  $edit_permissions = array_merge($edit_permissions,$parallelperstagedata[$kk]['Edit_Permissions']);
					  $custom_data = $this->get_single_stage_data_for_custom_notification($parallelperstagedata[$kk],$parallelstagenames[$kk],$app_temp,$edit_permissions);
					  $custom_part = array_merge($custom_part,$custom_data);
					}
				}

	            if($parallelbranchwtype[$kk]=="parallel")
	            {
	
	               $arrayParnew = $parallelperstagedata[$kk];
	               unset($arrayParnew['Workflow_Type']);
	               $custom_data = $this->get_parallel_stage_data_for_custom_notification($arrayParnew,$app_temp,$edit_permissions);
				   $custom_part = array_merge($custom_part,$custom_data);
	             }

				if($parallelbranchwtype[$kk]=="conditional")
				{
				   $arrayCon = $parallelperstagedata[$kk];
				   $custom_data = $this->get_conditional_stage_data_for_custom_notification($arrayCon,$app_temp,$edit_permissions);
				   $custom_part = array_merge($custom_part,$custom_data);
	            }
	       }
	    }
		
		return $custom_part;
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Custom Notification - CONDITIONAL STAGE Processing
	 *
	 * @param  array  $arrayCon       			Conditional stage workflow json
	 * @param  string $app_temp       			Application template
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function get_conditional_stage_data_for_custom_notification($arrayCon,$app_temp,$edit_permissions)
	{
	  $arrayPar                           = array();
	  $custom_part                       = array();
	  $approvedstage                      = array();
	  $disapprovedstage                   = array();
	  $conditionalperstagedata            = array();
	  $conditionalapprovedwtype           = array();
	  $conditionaldisapprovedwtype        = array();
	  $conditionaldisapprovedperstagedata = array();
	  $conditionalperstagedata            = array();
	  $conditionalstagedata               = array();
	  $approvedstagenames                 = array();
	  $disapprovedstagenames              = array();
	  $disapprovedconditionalstagedata    = array();
	
	  array_push($approvedstage,$arrayCon['approved']);
	  array_push($disapprovedstage,$arrayCon['disapproved']);

	  //---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
	  foreach($approvedstage as $worktemplate => $temp)
	  {
	 	 foreach($temp as $stagename => $t)
	 	 {
			array_push($conditionalperstagedata,$t);
	     }
	  }
	  
	  $conditionalperstagedatacount = count($conditionalperstagedata);
	  for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
	  {
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);
			if($conditionalapprovedwtype[$ii]=="single")
			{
				foreach($approvedstage as $worktemplate => $temp)
				{
					foreach($temp as $stagename => $t)
					{
						if($t['Workflow_Type']=="single")
						{
							array_push($approvedstagenames,$stagename);
						}
					}
				}
				
                $sms_check = $conditionalperstagedata[$ii]['sms'];
                if($sms_check=="true")
                {
                  $edit_permissions = array_merge($edit_permissions,$conditionalperstagedata[$ii]['Edit_Permissions']);	  $custom_data = $this->get_single_stage_data_for_custom_notification($conditionalperstagedata[$ii],$approvedstagenames[$ii],$app_temp,$edit_permissions);
				  $custom_part = array_merge($custom_part,$custom_data);
				}
			}
	
	        if($conditionalapprovedwtype[$ii]=="parallel")
			{
				$arrayPar = $conditionalperstagedata[$ii];
				unset($arrayPar['Workflow_Type']);
				$custom_data = $this->get_parallel_stage_data_for_custom_notification($arrayPar,$app_temp,$edit_permissions);
				$custom_part = array_merge($custom_part,$custom_data);
			}

			if($conditionalapprovedwtype[$ii]=="conditional")
			{	
				$arrayConnew = $conditionalperstagedata[$ii];
				$custom_data = $this->get_conditional_stage_data_for_custom_notification($arrayConnew,$app_temp,$edit_permissions);
				$custom_part = array_merge($custom_part,$custom_data);
	        }
	   }
	
	   //-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
	   foreach($disapprovedstage as $worktemplate => $temp)
	   {
			foreach($temp as $stagename => $t)
			{
				array_push($conditionaldisapprovedperstagedata,$t);
			}
	   }

	   $conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);

	   for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
	   {
	      	array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);
			if($conditionaldisapprovedwtype[$jj]=="single")
			{
				foreach($disapprovedstage as $worktemplate => $temp)
				{
					foreach($temp as $stagename => $t)
					{
						if($t['Workflow_Type']=="single")
						{
							array_push($disapprovedstagenames,$stagename);
						}
					}
				}
				
				$sms_check = $conditionaldisapprovedperstagedata[$jj]['sms'];
                if($sms_check=="true")
                {
				   $edit_permissions = array_merge($edit_permissions,$conditionaldisapprovedperstagedata[$jj]['Edit_Permissions']);
				   $custom_data = $this->get_single_stage_data_for_custom_notification($conditionaldisapprovedperstagedata[$jj],$disapprovedstagenames[$jj],$app_temp,$edit_permissions);
				   $custom_part = array_merge($custom_part,$custom_data);
				}
			}

			if($conditionaldisapprovedwtype[$jj]=="parallel")
			{
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar['Workflow_Type']);
				$custom_data = $this->get_parallel_stage_data_for_custom_notification($arrayPar,$app_temp,$edit_permissions);
				$custom_part = array_merge($custom_part,$custom_data);
			}

			if($conditionaldisapprovedwtype[$jj]=="conditional")
			{
				$arrayCon = $conditionaldisapprovedperstagedata[$jj];
				$custom_data = $this->get_conditional_stage_data_for_custom_notification($arrayCon,$app_temp,$edit_permissions);
				$custom_part = array_merge($custom_part,$custom_data);
			}
		}
		
		return $custom_part;
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's email id for notification part - SINGLE STAGE Processing
	 *
	 * @param  array $perstagedata Single stage workflow json
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

	function get_single_stage_users($perstagedata)
	{
		$users = array();
		array_push($users,$perstagedata['UsersList']);
	    return $users;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's email id for notification part - PARALLEL STAGE Processing
	 *
	 * @param  array $arrayPar Parallel stage workflow json
	 *
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

	function get_parallel_stage_users($arrayPar)
	{
		$users                  = array();
		$arrayCon               = array();
		$branchs                = array();
		$parallelperstagedata_  = array();
		$parallelbranches       = array();
		$parallelbranchwtype    = array();
		$parallelstagedata      = array();
		$parallelstagedatauser  = array();
		
		foreach($arrayPar as $paral => $para)
		{
			array_push($branchs,$paral);
		}
	
		foreach ($branchs as $perbranch)
		{
			$parallelstagenames = array();
	        
	        log_message('debug','$parallelperstagedata_=====1279'.print_r($parallelperstagedata_,true));
	        log_message('debug','$parallelbranchwtype=====1280'.print_r($parallelbranchwtype,true));

			if(isset($parallelperstagedata_))
			{
				array_shift($parallelperstagedata_);
			}
			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}
			if(isset($parallelbranchwtype))
			{
				array_shift($parallelbranchwtype);
		    }

			foreach($arrayPar[$perbranch] as $branchname => $branch)
			{
				array_push($parallelbranches,$branchname);
				log_message('debug','$branch=====1292'.print_r($branch,true));
				array_push($parallelperstagedata_,$branch);
			}
	
			$parallelstagecount = count($parallelperstagedata_);
	
			for($kk=0;$kk<$parallelstagecount;$kk++)
			{
				array_push($parallelbranchwtype,$parallelperstagedata_[$kk]['Workflow_Type']);
				if($parallelbranchwtype[$kk]=="single")
				{
	
					if(isset($parallelperstagedata_[$kk-1]))
					{
						unset($parallelperstagedata_[$kk-1]);
					}
	
					if(isset($parallelstagenames[$kk-1]))
					{
						unset($parallelstagenames[$kk-1]);
					}
					foreach($arrayPar[$perbranch] as $branchname => $branch)
					{
						if($branch['Workflow_Type']=="single")
						{
							array_push($parallelstagenames,$branchname);
						}
					}
	
					$parallelstagedatauser = $this->get_single_stage_users($parallelperstagedata_[$kk]);
	
					$users = array_merge($users,$parallelstagedatauser);
	
				}

				if($parallelbranchwtype[$kk]=="parallel")
				{
					/*if(isset($parallelperstagedata_[$kk-1]))
					{
						unset($parallelperstagedata_[$kk-1]);
					}*/
	
					$arrayParnew = $parallelperstagedata_[$kk];
					unset($arrayParnew['Workflow_Type']);
					$parallelusers = $this->get_parallel_stage_users($arrayParnew);
					$users = array_merge($users,$parallelusers);

				}

				if($parallelbranchwtype[$kk]=="conditional")
				{
					/*if(isset($parallelperstagedata_[$kk-1]))
					{
						unset($parallelperstagedata_[$kk-1]);
					}*/
                    log_message('debug','$parallelbranchwtype[kk]=====1344'.print_r($parallelbranchwtype[$kk],true));
					log_message('debug','$kk=====1345'.print_r($kk,true));
					log_message('debug','$parallelperstagedata_=====1346'.print_r($parallelperstagedata_,true));
					$arrayCon = $parallelperstagedata_[$kk];
					log_message('debug','$arrayCon=====1348'.print_r($arrayCon,true));
					$conditionalusers = $this->get_conditional_stage_users($arrayCon);
					$users = array_merge($users,$conditionalusers);
	
				}
			}
	
		}

		return $users;
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Populates user's email id for notification part - CONDITIONAL STAGE Processing
	 *
	 * @param  array $arrayCon Conditional stage workflow json
	 *
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

	function get_conditional_stage_users($arrayCon)
	{
        $arrayPar                           = array();
		$approvedstage                      = array();
		$disapprovedstage                   = array();
		$conditionalperstagedata            = array();
		$conditionalapprovedwtype           = array();
		$conditionaldisapprovedwtype        = array();
		$conditionaldisapprovedperstagedata = array();
		$conditionalperstagedata            = array();
		$approvedstagenames                 = array();
		$disapprovedstagenames              = array();
		$users                              = array();
		$conditionalapprovedstagedata       = array();
		$conditionaldisapprovedstagedata    = array();
		$conditionaldisapprovedusers        = array();
		$conditionalapprovedusers           = array();

		array_push($approvedstage,$arrayCon['approved']);
		array_push($disapprovedstage,$arrayCon['disapproved']);
	
	
		//---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
	
	
		foreach($approvedstage as $worktemplate => $temp)
		{
	        foreach($temp as $stagename => $t)
	        {
				array_push($conditionalperstagedata,$t);
	        }
	    }

	
		$conditionalperstagedatacount = count($conditionalperstagedata);


		for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
		{
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);

			if($conditionalapprovedwtype[$ii]=="single")
			{
				$approveduser = $this->get_single_stage_users($conditionalperstagedata[$ii]);
				$users = array_merge($users,$approveduser);
			}

			if($conditionalapprovedwtype[$ii]=="parallel")
			{
				$arrayPar = $conditionalperstagedata[$ii];
				unset($arrayPar['Workflow_Type']);
				$parallelusers = $this->get_parallel_stage_users($arrayPar);
				$users = array_merge($users,$parallelusers);
			}

			if($conditionalapprovedwtype[$ii]=="conditional")
			{
				$arrayConnew = $conditionalperstagedata[$ii];
				$approvedconditionalusers = $this->get_conditional_stage_users($arrayConnew);
				$users = array_merge($users,$approvedconditionalusers);
            }
	    }
	
		//-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
		foreach($disapprovedstage as $worktemplate => $temp)
		{
			foreach($temp as $stagename => $t)
			{
				array_push($conditionaldisapprovedperstagedata,$t);
			}
		}

	
		$conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);

        for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
		{
			array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);

			if($conditionaldisapprovedwtype[$jj]=="single")
			{
				$disapproveduser = $this->get_single_stage_users($conditionaldisapprovedperstagedata[$jj]);
	            $users = array_merge($users,$disapproveduser);
	        }
	
	        if($conditionaldisapprovedwtype[$jj]=="parallel")
			{
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar['Workflow_Type']);
				$parallelusers = $this->get_parallel_stage_users($arrayPar);
				$users = array_merge($users,$parallelusers);
	        }

			if($conditionaldisapprovedwtype[$jj]=="conditional")
			{
			    $arrayConnew = $conditionaldisapprovedperstagedata[$jj];
				$disapprovedconditionalusers = $this->get_conditional_stage_users($arrayConnew);
				$users = array_merge($users,$disapprovedconditionalusers);
	        }
         }

	   return $users;
	}
	
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Notification part - Sending notifications
	 *
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

	function usernotification()
	{
		$paperssavednew = '';
		$treessavednew  = '';

        $app_name  = $_POST['app_name'];
		$app_id    = $_POST['app_id'];
		$app_con   = $_POST['app_id'];
		$app_mod   = $_POST['app_mod'];
		$comp_name = $_POST['comp_name'];
		
		// CUSTOM SMS CONTENT
		if(isset($_POST['sms_content']))
		{
		    $sms_con = $this->input->post('sms_content',TRUE);
			$sms_con = json_decode($sms_con);
			$this->Workflow_Model->save_sms_content_to_app_definition($app_id,$sms_con);
		}
		
		//- - - - - EMAIL NOTIFICATION - - - - -//
		if(isset($_POST['emailall']) || isset($_POST['email']))
		{
		  $arr = $_POST['userlist'];
		  foreach($arr as $value)
		  {
		     $email_id = str_replace("#", "@", $value);
			 
			$fromaddress = $this->config->item('smtp_user');
			$this->email->set_newline("\r\n");
			$this->email->set_crlf("\r\n");
			$this->email->from($fromaddress,'TLSTEC');
			$this->email->to($email_id);
			$this->email->subject("New app created");
			$email_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<!-- If you delete this meta tag, Half Life 3 will never be released. -->
	<meta name="viewport" content="width=device-width" />

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	</head>
	 
	<body bgcolor="#FFFFFF" style="margin:0;padding:0;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100% !important;height:100%;font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif">

	<!-- BODY -->
	<table class="body-wrap" style="width:100%">
		<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF" style="display:block !important;max-width:600px !important;margin:0 auto !important;clear:both !important;">

				<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;">
				<table style="width:100%">
					<tr>
						<td>
							<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello,</h3>
							<p class="lead" style="font-size:17px;">
							 New Application "'.$app_name.'" created. Download in your device and start using..</p><br>
							 Regards,<br>
							 Admin
							 <!-- Callout Panel -->
							<p class="callout" style="padding:15px;background-color:#ecf8ff;margin-bottom:15px;">
								Please do not reply back this mail as this is an automated response.
							</p><!-- /Callout Panel -->					
							
						</td>
					</tr>
				</table>
				</div><!-- /content -->
										
			</td>
			<td></td>
		</tr>
	</table><!-- /BODY -->

	</body>
	</html>';

		$this->email->message($email_message);
		if($this->email->send())
		{
		$this->data['message'] ="App created successfully and notification sent successfully";
		}
		$this->email->print_debugger();
  
		  }
		}
		
		     
		//- - - - - - - SMS Notification - - - - - -//
		if(isset($_POST['sms']) || isset($_POST['smsall']))
		{
		   $sms_ = 'New Application "'.$app_name.'" created. Download in your device.';
		   $arr  = $_POST['userlist'];
		   foreach($arr as $value)
		   {
		      $user_ = str_replace("#", "@", $value);
			  $mobile_no = $this->ion_auth->user_by_email($user_);
			  $result = $this->bhashsms->send_sms($mobile_no[0]['phone'],$sms_);
		   }
		  
		  $this->data['message'] ="App created successfully and notification sent successfully";
		
		}
   
        {
		$total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
		
		//Initialize the pagination class
		$this->pagination->initialize($config);
		
		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		
		//find all the categories with paginate and save it in array to past to the view
		$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();
		
		//number page variable
		$this->data['page'] = $page;
		
		// other analytics values
		$data = $this->paas_common_lib->admin_dashboard_analytics_values();
		$this->data = array_merge($this->data,$data);

		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash', $this->data);
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Creating controller,model and view(list view) for respective apps 
	 *
	 *
	 * @param	string	$app_id    Application id
	 * @param	string	$app_mod   Model name
	 * @param	string	$comp_name Company name
	 *
	 * @author  Selva 
	 */

    function create_mvc($app_id,$app_mod,$comp_name)
	{
		$application_template     = array();
		$application_per_workflow = array();

		$currentappdata = $this->Workflow_Model->getcurrentapp($app_id);
		
		foreach($currentappdata as $data)
		{
			$application_template     = $data['app_template'];
			$application_name         = $data['app_name'];
			$app_type                 = $data['app_type'];
			$application_per_workflow = $data['workflow'];
			$pages                    = $data['pages'];
		}

		$data = array(
			'controller_name'    => $app_id,
			'model_name'         => $app_mod,
			'scaffold_delete_bd' => 1,
			'scaffold_bd'        => 1,
			'scaffold_routes'    => 1,
			'scaffold_menu'      => 1,
			'create_controller'  => 1,
			'create_model'       => 1,
			'create_view_list'   => 1,
			'scaffold_model_type'=> 'activerecord',
			'app_name'           => $application_name,
			'app_template'       => $application_template,
			'app_type'           => $app_type,
			'workflow'           => $application_per_workflow,
			'pages'              => $pages);

		$this->code_gen_lib->create_mvc($data);

	}

	// --------------------------------------------------------------------

	/**
	 * Creating web stage views - Initial process 
	 *
	 *
	 * @param	string	$controllername Application id  
	 * @param	string	$modelname      Model name
	 * @param	string	$companyname    Company name
	 * 
	 * @author  Selva
	 */
	
	function create_code_gen_view($controllername,$modelname,$companyname)
	{
	
		$application_template     = array();
		$application_per_workflow = array();

		$currentappdata = $this->Workflow_Model->getcurrentapp($controllername);
		
		foreach($currentappdata as $data)
		{
			$application_template     = $data['app_template'];
			$application_name         = $data['app_name'];
			$application_per_workflow = $data['workflow'];
		}

		
		$this->view_modification($application_per_workflow,$application_name,$application_template,$companyname,$controllername,$modelname);
		$this->code_gen_lib->write_config($companyname,$controllername);
		$this->code_gen_lib->write_config_ui($companyname,$controllername);
		$this->code_gen_lib->write_header($companyname,$controllername);
		$this->code_gen_lib->write_footer($companyname,$controllername);
		$this->code_gen_lib->write_nav($companyname,$controllername);
		$this->code_gen_lib->write_ribbon($companyname,$controllername);
		$this->code_gen_lib->write_scripts($companyname,$controllername);
		
	}

	// --------------------------------------------------------------------

	/**
	 * Creating validation rules for web stages - Initial process 
	 *
	 *
	 * @param	array	$workflow              Workflow json
	 * @param	string	$application_name      Application name
	 * @param	array	$application_template  Application template
	 * @param	string	$companyname           Company name
	 * @param	string	$controllername        Controller name
	 *
	 * @author  Selva 
	 */

	function validation_rules_for_stages($workflow,$application_name,$application_template,$companyname,$controllername)
	{

	    //**************************** single **********************************************//
	 	$stages                             = array();
		$app_temp                           = array();
		$app_temp1                          = array();
		$perstagedata                       = array();
		$singlestagedata                    = array();
		$allstages                          = array();
	
		//******************************** conditional **************************************//
		$conditionalstageusers              = array();
		$approvedstype                      = array();
		$disapprovedstype                   = array();
		$conditionalapp_temp1               = array();
		$conditionalapp_temp2               = array();
		$conditionalstages                  = array();
		$conditionaldisapprovedstages       = array();
		$conditionaldisapprovedstageusers   = array();
		$conditionalperstagedata            = array();
		$approvedst                         = array();
		$disapprovedstage                   = array();
		$conditionaldisapprovedperstagedata = array();
		$approvedvp                         = array();
		$approvedep                         = array();
		$disapprovedvp                      = array();
		$disapprovedep                      = array();
		$arrayCon                           = array();
	
	
		//******************************** parallel **************************************//
		$parallelstageusers                 = array();
		$parallelstype                      = array();
		$parallelvp                         = array();
		$parallelep                         = array();
		$workflow_type                      = array();
		$arrayPar                           = array();
		$parallelapp_temp1                  = array();
		$branchs                            = array();
		$parallelperstagedata               = array();
		$parallelbranches                   = array();
	
		//*********************************************************************************//
	
		foreach($workflow as $worktemplate => $temp)
		{
			array_push($allstages,$worktemplate);
			array_push($perstagedata,$temp);
		}

		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
		
		array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);
        
	   ///////////////////////////////////////////////////////////////////////---single---////////////////////////////////////////////////////////////////////////////////////////////////////////////
	          
	      if($workflow_type[$i] == "single")
	      {
	         $this->process_single_stage_for_validation_rules($perstagedata[$i],$allstages[$i],$application_name,$application_template,$companyname,$controllername);
	       }
	    }
    }

	// --------------------------------------------------------------------------

	/**
	 * Creating views for web stages - processing workflow to operate on stages
	 *
	 *
	 * @param	array	$application_per_workflow   Workflow json
	 * @param	string	$application_name           Application name
	 * @param	array	$application_template       Application template
	 * @param	string	$companyname                Company name
	 * @param	string	$controllername             Controller name
	 * @param	string	$modelname                  Model name
	 *
	 * @author  Selva 
	 */

	function view_modification($application_per_workflow,$application_name,$application_template,$companyname,$controllername,$modelname)
	{
	
		//**************************** single **********************************************//
		$stages                             = array();
		$app_temp                           = array();
		$app_temp1                          = array();
		$perstagedata                       = array();
		$singlestagedata                    = array();
		$allstages                          = array();
	
		//******************************** conditional **************************************//
		$conditionalstageusers              = array();
		$approvedstype                      = array();
		$disapprovedstype                   = array();
		$conditionalapp_temp1               = array();
		$conditionalapp_temp2               = array();
		$conditionalstages                  = array();
		$conditionaldisapprovedstages       = array();
		$conditionaldisapprovedstageusers   = array();
		$conditionalperstagedata            = array();
		$approvedst                         = array();
		$disapprovedstage                   = array();
		$conditionaldisapprovedperstagedata = array();
		$approvedvp                         = array();
		$approvedep                         = array();
		$disapprovedvp                      = array();
		$disapprovedep                      = array();
		$arrayCon                           = array();
	
	
		//******************************** parallel **************************************//
		$parallelstageusers                = array();
		$parallelstype                     = array();
		$parallelvp                        = array();
		$parallelep                        = array();
		$workflow_type                     = array();
		$arrayPar                          = array();
		$parallelapp_temp1                 = array();
		$branchs                           = array();
		$parallelperstagedata              = array();
		$parallelbranches                  = array();
	
		//*********************************************************************************//
	
		foreach($application_per_workflow as $worktemplate => $temp)
		{
			array_push($allstages,$worktemplate);
			array_push($perstagedata,$temp);
		}

		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
		
			array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);
	         
		
		    ///////////////////////////////////////////////////////////////////////---single---////////////////////////////////////////////////////////////////////////////////////////////////////////////
		          
			    if($workflow_type[$i] == "single")
			    {
			
			       $this->view_modification_single($perstagedata[$i],$allstages[$i],$application_name,$application_template,$companyname,$controllername,$modelname);
			    }
		
		
		
		    ////////////////////////////////////////////////////////////////////////////////---CONDITIONAL---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
			     if($workflow_type[$i] == "conditional")
			     {
			        $arrayCon = $perstagedata[$i];
			        $this->view_modification_conditional($arrayCon,$application_name,$application_template,$companyname,$controllername,$modelname);
			     }

		
		    ////////////////////////////////////////////////////////////////////////---PARALLEL---///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			    if($workflow_type[$i] == "parallel")
			    {
			       $arrayPar = $perstagedata[$i];
			       unset($arrayPar['Workflow_Type']);
			       $this->view_modification_parallel($arrayPar,$application_name,$application_template,$companyname,$controllername,$modelname);
			        	 
			    }

	     }
	}


	// --------------------------------------------------------------------

	/**
	 * Creating views for web stages - processing single stage 
	 *
	 *
	 * @param	array	$singlestagedata      Single stage workflow json
	 * @param	string	$stage                Stage name
	 * @param	string	$application_name     Application name
	 * @param	array	$application_template Application template
	 * @param	string	$companyname          Company name
	 * @param	string	$controllername       Controller name
	 * @param	string	$modelname            Model name
	 * 
	 * @author  Selva
	 */
	
	function view_modification_single($singlestagedata,$stage,$application_name,$application_template,$companyname,$controllername,$modelname)
	{
		$app_temp   = array();
		$vp         = array();
		$ep         = array();
		$stype      = array();
		$stageindex = array();

		array_push($vp,$singlestagedata['View_Permissions']);
		array_push($ep,$singlestagedata['Edit_Permissions']);
		array_push($stype,$singlestagedata['Stage_Type']);
		array_push($stageindex,$singlestagedata['index']);
		$app_temp[$stage]['View_Permissions'] = $vp[0];
		$app_temp[$stage]['Edit_Permissions'] = $ep[0];
		$app_temp[$stage]['index']            = $stageindex[0];
		
		if($stype[0]=="web")
		{
			$this->code_gen_lib->create_single_stage_web_view($application_name,$application_template,$app_temp,$companyname,$controllername,$modelname);
		}
		else if($stype[0]=="hybrid")
		{
		   $this->code_gen_lib->create_single_stage_web_view($application_name,$application_template,$app_temp,$companyname,$controllername,$modelname);
		
		}
			
	}

	// --------------------------------------------------------------------

	/**
	 * Creating views for web stages - processing conditional stage 
	 *
	 * @author  Selva
	 *
	 * @param	array	$arrayCon              Conditional stage workflow data
	 * @param	string	$application_name      Application name
	 * @param	array	$application_template  Application template
	 * @param	string	$companyname           Company name
	 * @param	string	$controllername        Controller name
	 * @param	string	$modelname             Model name
	 * 
	 */
	
	function view_modification_conditional($arrayCon,$application_name,$application_template,$companyname,$controllername,$modelname)
	{
		$arrayPar                           = array();
		$approvedstage                      = array();
		$disapprovedstage                   = array();
		$conditionalperstagedata            = array();
		$conditionalapprovedwtype           = array();
		$conditionaldisapprovedwtype        = array();
		$conditionaldisapprovedperstagedata = array();
		$conditionalstagedata               = array();
		$approvedstagenames                 = array();
		$disapprovedstagenames              = array();
		$conditionaldisapprovedstagedata    = array();
	
		array_push($approvedstage,$arrayCon['approved']);
		array_push($disapprovedstage,$arrayCon['disapproved']);

		/***** LOG *****/
		log_message('debug','$APPROVEDSTAGE==========VIEW_MODIFICATION_CONDITIONAL'.print_r($approvedstage,true));
		log_message('debug','$DISAPPROVEDSTAGE==========VIEW_MODIFICATION_CONDITIONAL'.print_r($disapprovedstage,true));
	
		//---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
		 foreach($approvedstage as $worktemplate => $temp)
		 {
			foreach($temp as $stagename => $t)
			{
				array_push($conditionalperstagedata,$t);
			}
		}
		$conditionalperstagedatacount = count($conditionalperstagedata);
		for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
		{
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);
             if($conditionalapprovedwtype[$ii]=="single")
	         {
	             foreach($approvedstage as $worktemplate => $temp)
	             {
	               foreach($temp as $stagename => $t)
	               {
	                 if($t['Workflow_Type']=="single")
			         {
						array_push($approvedstagenames,$stagename);
		             }
		           }
		         }

				$this->view_modification_single($conditionalperstagedata[$ii],$approvedstagenames[$ii],$application_name,$application_template,$companyname,$controllername,$modelname);
		     }
			 if($conditionalapprovedwtype[$ii]=="parallel")
		     {
				$arrayPar = $conditionalperstagedata[$ii];
				unset($arrayPar['Workflow_Type']);
				$this->view_modification_parallel($arrayPar,$application_name,$application_template,$companyname,$controllername,$modelname);
			}
			if($conditionalapprovedwtype[$ii]=="conditional")
			{
				$arrayConnew = $conditionalperstagedata[$ii];
				$this->view_modification_conditional($arrayConnew,$application_name,$application_template,$companyname,$controllername,$modelname);
	
		    }
		 }
		 
		//-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
		
		foreach($disapprovedstage as $worktemplate => $temp)
		{
		  foreach($temp as $stagename => $t)
		  {
				array_push($conditionaldisapprovedperstagedata,$t);
		  }
	    }
		 
		$conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);
		for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
		{
		 
			array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);
			if($conditionaldisapprovedwtype[$jj]=="single")
			{
			  foreach($disapprovedstage as $worktemplate => $temp)
			  {
			    foreach($temp as $stagename => $t)
			    {
			      if($t['Workflow_Type']=="single")
			      {
					array_push($disapprovedstagenames,$stagename);
		          }
		        }
		      }

				$this->view_modification_single($conditionaldisapprovedperstagedata[$jj],$disapprovedstagenames[$jj],$application_name,$application_template,$companyname,$controllername,$modelname);
		    }
		   if($conditionaldisapprovedwtype[$jj]=="parallel")
		   {
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar['Workflow_Type']);
				$this->view_modification_parallel($arrayPar,$application_name,$application_template,$companyname,$controllername,$modelname);
	       }
		  if($conditionaldisapprovedwtype[$jj]=="conditional")
		  {
		
				$arrayConnew = $conditionaldisapprovedperstagedata[$jj];
				$this->view_modification_conditional($arrayConnew,$application_name,$application_template,$companyname,$controllername,$modelname);
		   }
		
		
	    }
   }


	// --------------------------------------------------------------------

	/**
	 * Creating views for web stages - processing parallel stage 
	 *
	 * @author  Selva
	 *
	 * @param	array	$arrayPar              Parallel stage data
	 * @param	string	$application_name      Application name
	 * @param	array	$application_template  Application template
	 * @param	string	$companyname           Company name
	 * @param	string	$controllername        Controller name
	 * @param	string	$modelname             Model name
	 * 
	 */

	function view_modification_parallel($arrayPar,$application_name,$application_template,$companyname,$controllername,$modelname)
	{
		$arrayCon              = array();
		$branchs               = array();
		$parallelperstagedata  = array();
		$parallelbranches      = array();
		$parallelbranchwtype   = array();
		$parallelstagedata     = array();
		$parallelstagenames    = array();
	
		foreach($arrayPar as $paral => $para)
		{
			array_push($branchs,$paral);
		}
	
		foreach ($branchs as $perbranch)
		{
	        if(isset($parallelperstagedata))
			{
				array_shift($parallelperstagedata);
			}

			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}

			if(isset($parallelbranchwtype))
			{
				array_shift($parallelbranchwtype);
			}

		 foreach($arrayPar[$perbranch] as $branchname => $branch)
		 {
			array_push($parallelbranches,$branchname);
			array_push($parallelperstagedata,$branch);
		 }
			
		  $parallelstagecount = count($parallelperstagedata);
			
		  for($kk=0;$kk<$parallelstagecount;$kk++)
		  {
			 array_push($parallelbranchwtype,$parallelperstagedata[$kk]['Workflow_Type']);
			 if($parallelbranchwtype[$kk]=="single")
			 {
			   if(isset($parallelperstagedata[$kk-1]))
			   {
			      unset($parallelperstagedata[$kk-1]);
			   }
			 
			   if(isset($parallelbranches[$kk-1]))
			   {
			     unset($parallelbranches[$kk-1]);
			   }
			 
			   $this->view_modification_single($parallelperstagedata[$kk],$parallelbranches[$kk],$application_name,$application_template,$companyname,$controllername,$modelname);
             }

             if($parallelbranchwtype[$kk]=="parallel")
			 {
				$arrayParnew = $parallelperstagedata[$kk];
				unset($arrayParnew['Workflow_Type']);
				$this->view_modification_parallel($arrayParnew,$application_name,$application_template,$companyname,$controllername,$modelname);
		     }

		     if($parallelbranchwtype[$kk]=="conditional")
		    {
		        $arrayCon = $parallelperstagedata[$kk];
			    $this->view_modification_conditional($arrayCon,$application_name,$application_template,$companyname,$controllername,$modelname);
	        }
		  }
	    }
	
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Saving an app as draft from workflow stage
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function workflow_stage_draft()
	{
	  // POST DATA
	  $controller_name = $this->input->post('controller_name',TRUE);
	  $workflow_data   = $this->input->post('workflow',TRUE);
	  
	  $workflow_obj = json_decode($workflow_data);
	  $workflow     = (array) $workflow_obj;
	  
	  $application_id = substr($controller_name, 0,strpos($controller_name, "_"));
	  $this->load->model('Workflow_Model');
	  $this->Workflow_Model->mark_as_draft($application_id,$workflow);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Deleting app from collection when it is not saved as draft from workflow stage
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function workflow_app_delete()
	{
	  $controller_name = $this->input->post('controller_name',TRUE);
	  $app_type = $this->input->post('app_type',TRUE);
	  $id = substr($controller_name, 0,strpos($controller_name, "_"));
	  $cusdetail = $this->ion_auth->customer()->row();
	  $usercompany = $cusdetail->company_name;
	  unlink(APPPATH."controllers/".$usercompany."/".$id."_con.php");
	  unlink(APPPATH."models/".$usercompany."/".$id."_mod.php");
	  $this->deleteAll(APPPATH."views/".$usercompany."/".$id."_con");	

      //delete the item
	  if($app_type==="Private")
	  {
	     $this->ion_auth->delete_app($id);
	  }
	  else if($app_type==="Shared")
	  {
         $this->ion_auth->delete_shared_app($id);
	  }  
	}
	
	// --------------------------------------------------------------------

	/**
	 * Delete sub-folders within a folder - For Delete app 
	 *
	 *
	 * @param	string	directory Path to delete a file
	 * @param	boolean	empty     
	 * 
	 * @author  Unknown
	 */
	function deleteAll($directory, $empty = false) {
		if(substr($directory,-1) == "/") {
			$directory = substr($directory,0,-1);
		}
	
		if(!file_exists($directory) || !is_dir($directory)) {
			return false;
		} elseif(!is_readable($directory)) {
			return false;
		} else {
			$directoryHandle = opendir($directory);
	
			while ($contents = readdir($directoryHandle)) {
				if($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
	
					if(is_dir($path)) {
						$this->deleteAll($path);
					} else {
						unlink($path);
					}
				}
			}
	
			closedir($directoryHandle);
	
			if($empty == false) {
				if(!rmdir($directory)) {
					return false;
				}
			}
	
			return true;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Validation rules for scaffold process
	 *
	 * @author  Sangar
	 *
	 * @param	string	type  
	 * @param	string	id    
	 * 
	 */
	private function _set_rules($type = 'create', $id = NULL)
	{
		//validate form input
		$this->form_validation->set_rules('controller_name', 'Controller Name', 'required|xss_clean');
		$this->form_validation->set_rules('model_name', 'Controller Name', 'required|xss_clean');
		$this->form_validation->set_rules('scaffold_code', 'Scaffold Code', 'required|xss_clean');
		$this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
	}


}
