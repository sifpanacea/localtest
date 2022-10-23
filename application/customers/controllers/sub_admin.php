<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_admin extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->helper('paas');
		$this->load->helper('language');
		$this->load->library('session');
		$this->load->library('paas_common_lib');
		$this->load->library('bhashsms');
		$this->config->load('config', TRUE);
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->load->model('sub_admin_Model');

	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Default page after login ( for sub admin )
	 *  
	 * @author Vikas 
	 */

	function index()
	{
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'auth/login');
		}
		else
		{
			if($this->ion_auth->is_sub_admin())
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : "Logged In Successfully";
	
				$u = $this->session->userdata("customer");
				$email = $u['email'];
				$this->data['email'] = $email;
		
		        // other analytics values
				$data = $this->paas_common_lib->sub_admin_dashboard_analytics_values();
				$this->data = array_merge($this->data,$data);
 				
 				//log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($this->data,true));
				$this->_render_page('sub_admin/sub_dash', $this->data);
			}
			else
			{
			    $this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect(URC.'auth/login');
			}
		}
	}
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Navigate to dashboard
	 *  
	 * @author Vikas 
	 */

    function to_dashboard()
	{
	   	$this->check_for_admin();
	   	$this->check_for_plan('to_dashboard');

	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email'] = $email;

        // other analytics values
		$data = $this->paas_common_lib->sub_admin_dashboard_analytics_values();
		$this->data = array_merge($this->data,$data);

		$this->data['message'] = ''; 
		//log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($this->data,true));
		$this->_render_page('sub_admin/sub_dash', $this->data);
	   
	}
	
	public function pagination_events(){
		$page_number = $this->input->post('page_number');
		log_message('debug','dddddddddddddddddddddddddddddddddddddddd$page_number$page_number$page_number$page_numberdddddddddddddddd'.print_r($page_number,true));
		$item_par_page = 5;
		$position = ($page_number*$item_par_page);
		
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
		
		$total_rows = $this->ion_auth->eventcount($usersession);
		
		//find all the categories with paginate and save it in array to past to the view
		$result_set =$this->ion_auth->paginate_all_events($item_par_page, $page_number, $usersession);
		
		foreach ($result_set as $eventcount=>$event){
			$confirmed_user = 0;
			$noreplied_user = 0;
			$declinded_user = 0;
			$result_set[$eventcount]['confirmed_users'] = $confirmed_user;
			$result_set[$eventcount]['noreply_users'] = $noreplied_user;
			$result_set[$eventcount]['declinded_users'] = $declinded_user;
			foreach ($event['users'] as $user_id){
				$usercollection = $user_id.'_calendar_events';
				$user_details = $this->sub_admin_Model->get_user_calendar_event($usercollection,$event['id']);
				//log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($user_details,true));
				if($user_details[0]['reply'] == 'Confirmed' || $user_details[0]['reply'] == 'Event modified')
				{
					$confirmed_user++;
					$result_set[$eventcount]['confirmed_users'] = $confirmed_user;
				}else if($user_details[0]['reply'] == 'Not yet replied')
				{
					$noreplied_user++;
					$result_set[$eventcount]['noreply_users'] = $noreplied_user;
				}else if($user_details[0]['reply'] == 'Declinded')
				{
					$noreplied_user++;
					$result_set[$eventcount]['declinded_users'] = $declinded_user;
				}
				$result_set[$eventcount]['user_reply'][str_replace("#","@",$user_id)] = $user_details[0]['reply'];
			}
		}
		log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($result_set,true));
		
		//$result_set = $this->db->query("SELECT * FROM countries LIMIT ".$position.",".$item_par_page);
		$total_set =  count($result_set);
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttt'.print_r($total_set,true));
		
		//$page =  $this->db->get('countries') ;
		//$total =  $page->num_rows();
		//break total recoed into pages
		$total = ceil($total_rows/$item_par_page);
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($total,true));
		if($total_set>0){
			$entries = null;
			// get data and store in a json array
			foreach($result_set as $row){
				$entries[] = $row;
			}
			$data = array(
					'TotalRows' => $total,
					'Rows' => $entries
			);log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($data,true));
			//$this->output->set_content_type('application/json');
			echo json_encode(array($data));
			
		}
		exit;
		 
	}
	
	public function pagination_feedbacks(){
		$page_number = $this->input->post('page_number');
		log_message('debug','dddddddddddddddddddddddddddddddddddddddd$page_number$page_number$page_number$page_numberdddddddddddddddd'.print_r($page_number,true));
		$item_par_page = 5;
		$position = ($page_number*$item_par_page);
	
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_feedbacks';
	
		$total_rows = $this->ion_auth->feedbackcount($usersession);
	
		//find all the categories with paginate and save it in array to past to the view
		$result_set =$this->ion_auth->paginate_all_feedbacks($item_par_page, $page_number, $usersession);
	
		foreach ($result_set as $feedbackcount=>$feedback){
		$usercount = 0;
			$result_set[$feedbackcount]['user_filled_forms_count'] = $usercount;
			foreach ($feedback['users'] as $user_id){
				$usercollection = $user_id.'_feedbacks';
				$user_details = $this->sub_admin_Model->get_user_feedback($usercollection,$feedback['id']);
				//log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($user_details,true));
				if($user_details[0]['reply'] == 'Filled')
				{
					$usercount++;
					$result_set[$feedbackcount]['user_filled_forms_count'] = $usercount;
				}
			}
		}
		log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($result_set,true));
	
		//$result_set = $this->db->query("SELECT * FROM countries LIMIT ".$position.",".$item_par_page);
		$total_set =  count($result_set);
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttt'.print_r($total_set,true));
	
		//$page =  $this->db->get('countries') ;
		//$total =  $page->num_rows();
		//break total recoed into pages
		$total = ceil($total_rows/$item_par_page);
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($total,true));
		if($total_set>0){
			$entries = null;
			// get data and store in a json array
			foreach($result_set as $row){
				$entries[] = $row;
			}
			$data = array(
					'TotalRows' => $total,
					'Rows' => $entries
			);log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($data,true));
			//$this->output->set_content_type('application/json');
			echo json_encode(array($data));
				
		}
		exit;
			
	}
	
	public function pagination_analytics(){
		$page_number = $this->input->post('page_number');
		log_message('debug','dddddddddddddddddddddddddddddddddddddddd$page_number$page_number$page_number$page_numberdddddddddddddddd'.print_r($page_number,true));
		$item_par_page = 10;
		$position = ($page_number*$item_par_page);
	
		$total_rows = $total_rows = $this->ion_auth->appcount();
	
		//find all the categories with paginate and save it in array to past to the view
		$result_set =$this->ion_auth->paginate_all($item_par_page, $page_number);
	
		
		log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($result_set,true));
	
		//$result_set = $this->db->query("SELECT * FROM countries LIMIT ".$position.",".$item_par_page);
		$total_set =  count($result_set);
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttt'.print_r($total_set,true));
	
		//$page =  $this->db->get('countries') ;
		//$total =  $page->num_rows();
		//break total recoed into pages
		$total = ceil($total_rows/$item_par_page);
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($total,true));
		if($total_set>0){
			$entries = null;
			// get data and store in a json array
			foreach($result_set as $row){
				$entries[] = $row;
			}
			$data = array(
					'TotalRows' => $total,
					'Rows' => $entries
			);log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($data,true));
			//$this->output->set_content_type('application/json');
			echo json_encode(array($data));
	
		}
		exit;
			
	}
	
	 
	// ------------------------------------------------------------------------

	/**
	 * Helper: List groups of enterprise
	 *  
	 * @author Vikas 
	 */

    function get_groups() 
    {
        $this->check_for_admin();
        $this->check_for_plan('groups');
		    
		$user    = $this->session->userdata("customer");
	    $company = $user['company'];
		$this->load->model('Workflow_Model');
		$this->data['groups']  = $this->Workflow_Model->getgroups($company);
		$this->data['message'] = $this->ion_auth->messages();
		$this->_render_page('admin/admin_dash_groups',$this->data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Helper: List groups of enterprise
     *
     * @author Vikas
     */
    
    function get_group_list()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('groups');
    
    	$groups = $this->ion_auth->groups()->result();
    	$name = array();
    	foreach($groups as $group)
    	{
    		array_push($name,$group->name);
    	}
    
    	$this->output->set_output(json_encode($name));
    		
    
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Enterprise admin profile
	 *  
	 * @author Vikas 
	 */

	function sub_admin_profile()
	{
		$this->check_for_admin();
		$this->check_for_plan('sub_admin_profile');
		
		
	    $total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		
        //create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

        //number page variable
		$this->data['page'] = $page;	
	  
	    $u = $this->session->userdata("customer");
	    $email = $u['email'];
	    $this->data['last_login'] = $u['old_last_login'];
	    $this->data['email'] = $email;

	    $this->load->model('Workflow_Model');
	    $this->data['profile_data'] = $this->Workflow_Model->sub_admin_profile_data($email);
	    $this->data['message'] = "Admin Profile";
        $this->data['myapps'] = $this->ion_auth->MYapps($config['per_page'], $page);
	   
	    $this->_render_page('sub_admin/sub_admin_dash_profile', $this->data);
	}

 	// --------------------------------------------------------------------
	
	/**
	 * Helper : List users
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function get_user_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('user');
	
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
	
    /*****************************************************************************************************************************************
	/
	/                                                           EVENTS
	/
	/******************************************************************************************************************************************
	
		// --------------------------------------------------------------------
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function event_request()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('event_request');
	   
	   	$this->data['message'] = "Manage event page";
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_events');
	   }
	   
		// --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function create_event()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('create_event');
	   	$u = $this->session->userdata("customer");
		$email = $u['email'];
		$this->data['email'] = $email;
	   	$this->data['message'] = "Create event page";
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_create_events');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function request_create_event()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('request_create_event');
	   	 
	   // Variable Declarations
		$config      = array();
		$upload_info = array();
		$file_data   = array();
			
        // Logged in sub admin details
		$loggeduser = $this->session->userdata('customer');
		$username   = $loggeduser['email'];
			
		// File upload Configurations
        $config['upload_path']   = UPLOADFOLDERDIR.'public/event_uploads/';
	    $config['allowed_types'] = '*';
		$config['max_size']      = '10240';
		$config['encrypt_name']  = TRUE;
	
		//create upload folder if not exists
		if (!is_dir($config['upload_path']))
		{
			mkdir(UPLOADFOLDERDIR."public/event_uploads/",0777,TRUE);
		}			
			
		// File data
		if(isset($_FILES))
		{
			$this->load->library('upload',$config);
			  
			foreach($_FILES as $index => $value)
			{
			    if($value['name']!='')
				{
				    $this->upload->initialize($config,$index);
					if ( ! $this->upload->do_upload($index))
					{
						 echo "file upload failed";
						 return FALSE;
					}
					else
					{
						array_push($upload_info,$this->upload->data());
						
						
						
						$file_data = array(
							"file_client_name"    =>$upload_info[0]['client_name'],
							"file_encrypted_name" =>$upload_info[0]['file_name'],
							"file_path"           =>$upload_info[0]['file_relative_path']
							  );
					}
				}
			  
			}
			
		}
			
		// Event related data
		$data['event_name']        = $_POST['name'];
		$data['event_desc']        = $_POST['description'];
		$data['event_expiry']      = $_POST['eventexpiry'];
		$data['requested_user_id'] = $username;
		$data['req_status']        = 'new';
		$data['req_time']          = date('Y-m-d H:i:s');
		$data['id']                = get_unique_id();
		$data['attachment']        = $file_data;
		$data['event_status']      = 'new';
			

		$status = $this->sub_admin_Model->save_event_request_in_collection($data);
			
        if($status)
		{
			redirect('sub_admin/manage_event');
		}
	}
		
	// -------------------------------------------------------------------------------
	   
	/**
	* Helper : Manage the events created by enterprise admin which sub admin requested
    *
	* @author  Vikas
	*
	*
	*/
	   
	function manage_event()
	{
		$this->check_for_admin();
		$this->check_for_plan('manage_event');
			
		// Logged in sub admin details
		$loggedsubadmin = $this->session->userdata('customer');
		$username       = $loggedsubadmin['email'];
		
		$u = $this->session->userdata("customer");
		$email = $u['email'];
		$this->data['email'] = $email;
			
		$this->data['events'] = $this->sub_admin_Model->get_calendar_events_in_request_collection($username);
		   
		$this->_render_page('sub_admin/sub_admin_dash_manage_events', $this->data);
	}
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function event_properties($event_id)
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('event_properties');
	   
	   	$u = $this->session->userdata('customer');
		$useremail = $u['email'];
	   	$username = str_replace("@","#",$useremail);
	   	$usersession=$username.'_calendar_events';
	   	
	   	$this->data['event'] = $this->sub_admin_Model->get_event_in_collection($usersession,$event_id);
	   	
	   	$this->data['event_form'] = $this->sub_admin_Model->get_event_details($useremail,$this->data['event'][0]['event_id']);
	   	
	   	foreach ($this->data['event'][0]['users'] as $user_id){
	   		$usercollection = $user_id.'_calendar_events';
	   		$this->data['user_details'][$user_id] = $this->sub_admin_Model->get_user_calendar_event($usercollection,$this->data['event'][0]['id']);
	   	}
	   	
	   	$this->_render_page('sub_admin/sub_admin_event_specification', $this->data);
	   }
	   
	   // ------------------------------------------------------------------------
	   
	   /**
	    * Helper: redirect to calendar page
	    *
	    * @author Vikas
	    */
	   
	   function event_use($event_id)
	   {
		 $this->session->set_flashdata('event_id', $event_id);
	   	 redirect('sub_admin_calendar');
	   }
	   
	   // ------------------------------------------------------------------------
	   
	   /**
	    * Helper: 
	    *
	    * @author Vikas
	    */
		
	   function event_form_comment()
	   {
	   	 $this->check_for_admin();
	   	 $this->check_for_plan('event_form_comment');
		 
		 $_id      = $this->input->post('id',true);
		 $comments = $this->input->post('comments',true);
		 
	   	 $form_data = array('req_status'=>'edited','comments'=>$comments,'event_status'=>'new_edit');
	   	
		 // Logged in sub admin
	   	 $loggeduser = $this->session->userdata('customer');
	   	 $username   = $loggeduser['email'];
	   
	     $query = $this->sub_admin_Model->update_event_form($username,$_id,$form_data);
	     $this->output->set_output('Edited');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function manage_user_assigned_event()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('manage_user_assigned_event');
	   
	   	$u = $this->session->userdata('customer');
	   	$username = str_replace("@","#",$u['email']);
	   	$usersession=$username.'_calendar_events';
	   	
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   
	   	$this->data['events'] = $this->sub_admin_Model->get_calendar_events_in_collection($usersession);
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_user_assigned_events', $this->data);
	   }
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function event_confirm($user_id, $event_id)
	   {
		   log_message('debug','iiiiiiiiiiiiiinnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn');
	   	$this->check_for_admin();
	   	$this->check_for_plan('event_confirm');

	   	$user_id = base64_decode($user_id);
	   	$form_data = array('reply'=>'Confirmed');
	   	 
	   	$user_id = str_replace("@","#",$user_id);
	   	$usercollection = $user_id.'_calendar_events';
	   	
	   	$query = $this->sub_admin_Model->update_user_event_confirm($usercollection,$event_id,$form_data);
	   	
	   	$event = $this->sub_admin_Model->get_event_in_collection_by_event_id($usercollection,$event_id);
	   	//log_message('debug','eeeeeee11111111111111111111eeeeeeeeeeeeeeeeeeeeeeeeee999999999999999999eeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($event,true));
	   	//----------------------------------Notification--------------------------------------
	   	
	   	$message    = $event[0]['title'].' is confirmed for '.str_replace("@","#",$user_id);
	   		
	   	$loggeduser = $this->session->userdata('customer');
	   	$company    = $loggeduser['company'];
	   	$sender     = $loggeduser['email'];
	   	
	   	$receiver   = $user_id."_push_notifications";
	   	$message_id = get_unique_id();
		log_message('debug','innnnnnnnnnnnnnnnnnn22222222222222222222222222222222222222222222');
	   	$this->sub_admin_Model->send_push_message_event_confirm($receiver,$message_id,$message,$company,$event);
	   	
	   	$this->sub_admin_Model->push_message_save_history($message_id,$message,$company,$sender,$user_id);
	   	
	   	$this->data['message'] = 'Notification Sent !';
	   	
	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   	
	   	$this->output->set_output('Confirmed');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function event_comment($user_id, $event_id)
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('event_confirm');
	   	
	   	$comment = $this->input->post('comments',true);
	   	$user_id = base64_decode($user_id);
	   	$form_data = array('reply'=>'Commented','comments'=>$comment);
	   
	   	$user_id = str_replace("@","#",$user_id);
	   	$usercollection = $user_id.'_calendar_events';
	   	$query = $this->sub_admin_Model->update_user_event_confirm($usercollection,$event_id,$form_data);
	   	$this->output->set_output('Modification Pending');
	   }
	// --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function get_sub_admin_events()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('manage_event');
	   	 
	   	$logged_sub_admin = $this->session->userdata('customer');
	   	$user             = $logged_sub_admin['email'];
	   	 
	   	$app_details = $this->sub_admin_Model->get_sub_admin_calendar_events($user);
	   	$this->output->set_output(json_encode($app_details));
	   }
	  
	/*****************************************************************************************************************************************
	/
	/                                                           FEEDBACKS
	/
	/******************************************************************************************************************************************
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load feedback create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function create_feedback()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('create_feedback');
	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   	$this->data['message'] = "Create feedback page";
	   	$this->_render_page('sub_admin/sub_admin_dash_create_feedback');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	   * Helper : Feedback Request by sub admin ( To enterprise admin )
	    *
	    * @author  Selva
	    *
	    *
	    */
	   
	   function request_create_feedback()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('request_create_feedback');
			
            // Variable Declarations
			$config      = array();
			$upload_info = array();
			$file_data   = array();		
			
		    // Logged in sub admin details
			$loggeduser = $this->session->userdata('customer');
			$username   = $loggeduser['email'];
			
			// File upload Configurations
            $config['upload_path']   = UPLOADFOLDERDIR.'public/feedback_uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '10240';
			$config['encrypt_name']  = TRUE;
			
			//create upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/feedback_uploads/",0777,TRUE);
			}	
			
			// File data
			if(isset($_FILES))
			{
			  $this->load->library('upload',$config);
			  
			  foreach($_FILES as $index => $value)
			  {
			     if($value['name']!='')
				 {
				    $this->upload->initialize($config,$index);
					if ( ! $this->upload->do_upload($index))
					{
						 echo "file upload failed";
						 return FALSE;
					}
					else
					{
						array_push($upload_info,$this->upload->data());
						
						
						
						$file_data = array(
							"file_client_name"    =>$upload_info[0]['client_name'],
							"file_encrypted_name" =>$upload_info[0]['file_name'],
							"file_path"           =>$upload_info[0]['file_relative_path']
							  );
					}
				 }
			  
			  }
			
			}
			
            // Feedback related data			
			$data['feedback_name']      = $_POST['name'];
			$data['feedback_desc']      = $_POST['description'];
			$data['feedback_expiry']    = $_POST['feedbackexpiry'];
			$data['requested_user_id']  = $username;
			$data['req_status']         = 'New';
			$data['req_time']           = date('Y-m-d H:i:s');
			$data['id']                 = get_unique_id();
			$data['attachment']         = $file_data;
			$data['feedback_status']      = 'new';
		   
			$status = $this->sub_admin_Model->save_feedback_request_in_collection($data);
		   
			redirect('sub_admin/manage_feedback');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load feedback create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function feedback_request()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('feedback_request');
	   
	   	$this->data['message'] = "Manage feedback page";
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_feedback');
	   }
	   
	   // ------------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function feedback_properties($feedback_id)
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('feedback_properties');
	   
	   	$u = $this->session->userdata('customer');
	   	$username = str_replace("@","#",$u['email']);
	   	$usersession=$username.'_feedbacks';
	   
	   	$this->data['feedback'] = $this->sub_admin_Model->get_feedback_in_collection($usersession,$feedback_id);
	   
	   	$this->data['feedback_form'] = $this->sub_admin_Model->get_feedback_details($u['email'],$this->data['feedback'][0]['feedback_id']);
	   
	   	foreach ($this->data['feedback'][0]['users'] as $user_id){
	   		$usercollection = $user_id.'_feedbacks';
	   		$this->data['user_details'][$user_id] = $this->sub_admin_Model->get_user_feedback($usercollection,$this->data['feedback'][0]['id']);
	   	}
	   
	   
	   	log_message('debug','eswetrtyttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($this->data,true));
	   	$this->_render_page('sub_admin/sub_admin_feedback_specification', $this->data);
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function manage_user_assigned_feedbacks()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('manage_user_assigned_feedbacks');
	   
	   	$u = $this->session->userdata('customer');
	   	$username = str_replace("@","#",$u['email']);
	   	$usersession=$username.'_feedbacks';
	   	
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   
	   	$this->data['events'] = $this->sub_admin_Model->get_calendar_feedbacks_in_collection($usersession);
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_user_assigned_feedbacks', $this->data);
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load feedback create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function manage_feedback()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('manage_feedback');
	   	
	   	$loggeduser = $this->session->userdata('customer');
	   	$username   = $loggeduser['email'];
	   	
	   	$this->data['email'] = $username;
	   	 
	   	$this->data['feedbacks'] = $this->sub_admin_Model->get_calendar_feedback_in_collection($username);
	   
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_feedback', $this->data);
	   }
	   
	   // ------------------------------------------------------------------------
	   
	   /**
	    * Helper: saves the feedback in admin's calendar feedback collection
	    *
	    * @author Vikas
	    */
	   
	   function save_feedback()
	   {
	   	
	   	 $data["description"]   = $this->input->post('description');
	   	 $data["users"]         = $this->input->post('multiselect');
	   	 $data["feedback_name"] = $this->input->post('name');
	   	 $data["feedback_id"]   = $this->input->post('id');
	   	 $data['id']            = get_unique_id();
	   	 $data['expiry_date']	= $this->input->post('date');
	   	
	   	
	   	$loggedsubadmin  = $this->session->userdata('customer');
		$user            = $loggedsubadmin['email'];
	   	$username        = str_replace("@","#",$user);
	   	$usersession     = $username.'_feedbacks';
		
	   	$this->sub_admin_Model->save_feedback_in_collection($usersession,$data);
	   	$users = $data['users'];
	   	
	   	$data["feedback_template"] = $this->sub_admin_Model->get_feedback_template($user,$data["feedback_id"]);
	   	$data["reply"] = "Not yet filled";
	   
	   
	   	foreach ($users as $user)
		{
	   		$username = str_replace("@","#",$user);
	   		$usersession = $username.'_feedbacks';
	   		$this->sub_admin_Model->save_feedback_in_collection($usersession,$data);
	   		
	   		//----------------------------------Notification to user-----------------------------------
	   		$message    = 'Please fill the feedback: '.$data["feedback_name"];
	   		
	   		$loggeduser = $this->session->userdata('customer');
	   		$company    = $loggeduser['company'];
	   		$sender     = $loggeduser['email'];

	   		$receiver   = $username."_push_notifications";
	   		$message_id = get_unique_id();
	   		$this->sub_admin_Model->send_push_message_feedback_create($receiver,$message_id,$message,$company,$data);
	   	}
	   	
	   	
	   	//----------------------------------Notification to sub admin--------------------------------------

	   	$message    = 'Please fill the feedback: '.$data["feedback_name"];

	   	$loggeduser = $this->session->userdata('customer');
	   	$company    = $loggeduser['company'];
	   	$sender     = $loggeduser['email'];
	   	$message_id = get_unique_id();
	   	
	   	$this->sub_admin_Model->push_message_save_history($message_id,$message,$company,$sender,$users);
	   	
	   	$this->manage_feedback();
	   }
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function feedback_form_comment($_id,$comment)
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('feedback_form_comment');
	   
	   	$form_data = array('req_status'=>'edited','comments'=>$comment,'feedback_status'=>'new_edit');
		 
		 // Logged in sub admin
	   	 $loggeduser = $this->session->userdata('customer');
	   	 $username   = $loggeduser['email'];
	   
	     $query = $this->sub_admin_Model->update_feedback_form($username,$_id,$form_data);
	     $this->output->set_output('Edited');
	   }

	/*****************************************************************************************************************************************
	/
	/                                                           NOTIFICATIONS
	/
	/******************************************************************************************************************************************
	
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load notification create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function create_notification()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('create_notification');
	   	
	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email']   = $email;
		$this->data['message'] = $this->session->flashdata('notification_message');
	   	$this->_render_page('sub_admin/sub_admin_dash_create_notification');
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load notification create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function manage_notification()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('manage_notification');
	   
	    // Logged in sub admin details
	   	$loggeduser = $this->session->userdata('customer');
	    $sender     = $loggeduser['email'];
		
	   	$messages  = $this->sub_admin_Model->get_MY_push_notification($sender);
		
		$json = json_encode($messages);
	    $this->data['data'] = $json;
	   	$this->data['events'] = $this->sub_admin_Model->get_calendar_notification_in_collection($sender);
	   	
	   	$this->data['email'] = $sender;
	   	$this->data['message'] = 'Notification history';
	   	$this->_render_page('sub_admin/sub_admin_dash_manage_notification', $this->data);
	   }
	   
	   /*****************************************************************************************************************************************
	   /
	   /                                                           SMS
	   /
	   /******************************************************************************************************************************************
	   
	   /**
	    * Helper : Load sms dashboard page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function sms_dashboard()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('sms_dashboard');
	   	
	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   	$this->data['message'] = "SMS Dashboard page";
	   	
	   	$this->_render_page('sub_admin/sub_admin_sms_dash',$this->data);
	   }
	   
	   // --------------------------------------------------------------------
	   
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load event create page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function send_sms()
	   {
		log_message('debug','sssssssssssmmmmmmmmmmmmmmssssssssssssssss');
	   	$this->check_for_admin();
	   	$this->check_for_plan('send_sms');
	   
	   	$u = $this->session->userdata('customer');
	   	$username = str_replace("@","#",$u['email']);
	   
	   	$data['sms_msg'] = $_POST['message'];
	   	$user_id = $_POST['multiselect'];
		log_message('debug','sssssssssssmmmmmmmmmmmmmmssssssssssssssss'.print_r($data['sms_msg'],true));
		log_message('debug','sssssssssssmmmmmmmmmmmmmmssssssssssssssss'.print_r($user_id,true));
	   	$users = array();
	   	$mobile_numbers = array();
	   	
	   	foreach ($user_id as $id){
	   		$email = str_replace("#","@",$id);
	   		$mobile = $this->sub_admin_Model->get_user_mobile_number($email);
	   		$details['mobile'] =  $mobile[0]['phone'];
	   		$details['username'] =  $mobile[0]['username'];
	   		array_push($users, $details);
	   		array_push($mobile_numbers, $mobile[0]['phone']);
	   	}
	   	
	   	$mobile_str = implode(',', $mobile_numbers);
	   	
	   	$data['users'] = $users;
	   	$data['time'] = date('Y-m-d H:i:s');
	   	$data['_id'] = get_unique_id();
	   
	   	$usersession=$username.'_sms_history';
	   
	   	$this->sub_admin_Model->save_sms_history_in_collection($usersession,$data);
	   	
	   	$this->bhashsms->send_sms($mobile_str,$data['sms_msg']);
	   
	   	$this->sms_dashboard();
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load sms dashboard page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function sms_history()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('sms_history');
	   	$this->data['message'] = "SMS Dashboard page";
	   	 
	   	$u = $this->session->userdata('customer');
	   	$username = str_replace("@","#",$u['email']);
	   	$usersession=$username.'_sms_history';
	   	$total_rows = $this->sub_admin_Model->get_sms_count($usersession);
	   	 
	   	 
	   	//---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
	   	 
	   	//Initialize the pagination class
	   	$this->pagination->initialize($config);
	   	 
	   	//control of number page
	   	$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	   	 
	   	//find all the categories with paginate and save it in array to past to the view
	   	$this->data['msgs'] = $this->sub_admin_Model->get_sms_history_in_collection($usersession,$config['per_page'],$page); //$this->ion_auth->private_apps($config['per_page'], $page);
	   	//create paginate´s links
	   	$this->data['links'] = $this->pagination->create_links();
	   	 
	   	//number page variable
	   	$this->data['page'] = $page;
	   	 
	   	$this->data['mgscount'] = $total_rows;
	   	
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   	
	   	$this->_render_page('sub_admin/sub_admin_sms_history',$this->data);
	   }
	   
	   // --------------------------------------------------------------------
	   
	   /**
	    * Helper : Load sms dashboard page
	    *
	    * @author  Vikas
	    *
	    *
	    */
	   
	   function third_party_sms()
	   {
	   	$this->check_for_admin();
	   	$this->check_for_plan('third_party_sms');
	   	
	   	$u = $this->session->userdata("customer");
	   	$email = $u['email'];
	   	$this->data['email'] = $email;
	   	$this->data['message'] = "SMS Dashboard page";
	   	$this->_render_page('sub_admin/sub_admin_third_party_sms_dash');
	   }

	   /**
	   * Helper : Get admin's enterprise name
	   *
	   * @author  Selva 
	   *
	   * 
	   */

       function get_company_name()
       {
          $this->load->model('Workflow_Model');
	      $data['users']=$this->Workflow_Model->company_name();
	      $company_name=array();
	      foreach ($data['users'] as $user)
	      {
		      $company_name = $user['company_name'];
	      }
	
	      $this->output->set_output(json_encode($company_name));
       }

     // --------------------------------------------------------------------

	   /**
	   * Helper : Admin's username
	   *
	   * @author  Selva 
	   *
	   * 
	   */

     function adminusername()
     {
           $user = $this->session->userdata("customer");
           $name = $user['username'];
           $this->output->set_output(json_encode($name));
     }

     // --------------------------------------------------------------------

	  /**
	  * Helper : Edit enterprise admin's profile
	  *
	  * @param  string  $company  Name of the company
	  *
	  * @author  Selva 
	  * 
	  */

	 function edit_profile()
	 {
		$this->check_for_admin();
		$this->check_for_plan('edit_profile');
		
		$this->data['title'] = "Edit Profile";

        //validate form input
		$this->form_validation->set_rules('company', $this->lang->line('edit_profile_company_name_label'), 'required|min_length[5]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('company_address', $this->lang->line('signup_customer_company_address'), 'required|xss_clean');
		
		$this->form_validation->set_rules('phone', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
		$this->form_validation->set_rules('company_website', $this->lang->line('signup_customer_company_website'), 'required|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'xss_clean');
		
		if ($this->form_validation->run() === TRUE)
		{
			$data = array(
					'company'     	   => $this->input->post('company'),
					'company_address'  => $this->input->post('company_address'),
			        'phone'    		   => $this->input->post('phone'),
					'email'            => $this->input->post('email'),
					'company_website'  => $this->input->post('company_website'),
					'username'         => $this->input->post('username')
			);
			
			if (isset($_FILES) && !empty($_FILES))
			{
				if($_FILES['file']['tmp_name']!='' || $_FILES['logo']['tmp_name']!='')
				{
					$uploaddir = PROFILEUPLOADFOLDER;
			
					if (!is_dir($uploaddir))
					{
						mkdir($uploaddir,0777,TRUE);
					}
			
					// Logged In Admin Details
					$loggedinuser  = $this->session->userdata("customer");
					$loggedemail   = $loggedinuser['email'];
			
					/***** Profile Image *****/
					$file = $uploaddir.$loggedemail.".png";
			
					if($_FILES['file']['tmp_name']!='')
					{
						if (move_uploaded_file($_FILES['file']['tmp_name'], $file))
						{
							// creating image thumbnail for header profile image
							// Get the CodeIgniter super object
							$CI =& get_instance();
			
							// Path to image thumbnail
							$image_thumb = $uploaddir.$loggedemail."_thumb.png";
			
							// LOAD LIBRARY
							$CI->load->library( 'image_lib' );
			
							// CONFIGURE IMAGE LIBRARY
							$config['image_library']    = 'gd2';
							$config['source_image']     = $file;
							$config['new_image']        = $image_thumb;
							$config['maintain_ratio']   = false;
							$config['height']           = 50;
							$config['width']            = 50;
							$CI->image_lib->initialize( $config );
							$CI->image_lib->resize();
							$CI->image_lib->clear();
						}
						else
						{
							$this->session->set_flashdata('message', "Profile Image upload failed");
							redirect('sub_admin/sub_admin_profile');
						}
					}
					/***** Company Logo *****/
			
					// DEFAULT CONFIGURATIONS
					$maxWidth  = 252;
					$maxHeight = 52;
			
					$file = $uploaddir.TENANT."logo.png";
			
					if($_FILES['logo']['tmp_name']!='')
					{
						list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
							
						if ($width > $maxWidth || $height > $maxHeight)
						{
							$this->session->set_flashdata('message', "Please upload company logo with pre-defined size");
							redirect('sub_admin/edit_profile');
						}
						else
						{
							if (move_uploaded_file($_FILES['logo']['tmp_name'], $file))
							{
									
							}
							else
							{
								$this->session->set_flashdata('message', "Logo Image upload failed");
								redirect('sub_admin/sub_admin_profile');
							}
								
						}
					}
				}
			}
			
			$sub_admin = $this->ion_auth->sub_admins()->row();
			$postcompany = $sub_admin->company;
			$this->ion_auth->sub_admin_profile_update($postcompany,$sub_admin->email,$data);
			
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', "Profile updated");
			redirect('sub_admin/sub_admin_profile');
			
		}
		
		
// 		if (isset($_POST) && !empty($_POST))
// 		{
// 		   $uploaddir = PROFILEUPLOADFOLDERDIR;
//            $file = $uploaddir.TENANT.".png"; 
//            $file_name= "male";

//            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) 
//            { 
//                  echo "success"; 
//            }
//            else
//            {
// 	             echo "error";
//            }
                
//            $file = $uploaddir.TENANT."logo.png";
//            $file_name= "male";
//            if (move_uploaded_file($_FILES['logo']['tmp_name'], $file))
//            {
//            	echo "success";
//            }
//            else
//            {
//            	echo "error";
//            }

//            if ($this->form_validation->run() === TRUE)
// 		   {
				
// 				$sub_admin = $this->ion_auth->sub_admins()->row();
// 				$postcompany = $sub_admin->company;
// 				$this->ion_auth->sub_admin_profile_update($postcompany,$sub_admin->email,$data);
				
// 				//check to see if we are creating the user
// 				//redirect them back to the admin page
// 				$this->session->set_flashdata('message', "Profile updated");
// 				redirect('sub_admin/sub_admin_profile');
// 			}
// 		}

		//display the edit user form
		$this->data['csrf'] = get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$user = $this->ion_auth->sub_admins()->row();
		
		log_message('debug','cccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($user,true));
        
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
			'readonly'=> 'readonly',
		);
		$this->data['company_address'] = array(
			'name'  => 'company_address',
			'id'    => 'company_address',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company_address', $user->company_address),
		);
		
		$this->data['company_website'] = array(
			'name'  => 'company_website',
			'id'    => 'company_website',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company_website', $user->company_website),
		);
		
		$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'email',
				'value' => $this->form_validation->set_value('email', $user->email),
				'readonly'=> 'readonly',
			); 
		
		
		$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $user->phone),
			);
			
		$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username', $user->username),
			);	
			
		$this->data['file'] = array(
				'name'  => 'file',
				'id'    => 'file',
				'type'  => 'file',
				'value' => $this->form_validation->set_value('file'),
			); 	

		$this->_render_page('sub_admin/sub_admin_profile_edit', $this->data);
	}

	

    

    // --------------------------------------------------------------------

	/**
	* Helper : Get documents count in a specified app
	*
	* @author  Vikas 
	* 
	*/

    function docss($appid)
    {
    	$docss = $this->ion_auth->count($appid);
    	if($docss == "")
    	{
    		$docss = 0;
    	}
    	$this->output->set_output($docss);
    }

	//***************Graphs***************START***************//

    // ------------------------------------------------------------------------

	/**
	 * Helper: Graphs 
	 * 
	 * 
	 * @author Sekar 
	 */

    function appgraph()
    {  
 	   $this->check_for_admin();
 	   $this->check_for_plan('appgraph');
 	   $time=date(" H:i:s", time());
	   $this->data = $this->ion_auth->graph_apps($time); 
    }

    //***************Graphs***************END***************//

    //***************Drop down notification***************START***************//
    
    // ------------------------------------------------------------------------

	/**
	 * Helper: Recently created application list
	 * 
	 * 
	 * @author Sekar 
	 */

	function app_history()
	{
	   $data = $this->ion_auth->app_history_model(); 
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Recently submitted documents list
	 * 
	 * 
	 * @author Sekar 
	 */

	function docs_history()
	{
	   $data = $this->ion_auth->docs_history_model(); 
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Messages to enterprise admin from TLSTEC ( regarding offers etc.,)
	 * 
	 * 
	 * @author Sekar 
	 */

	function admin_messages()
	{
	   $data = $this->ion_auth->admin_message_model(); 
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Documents count
	 * 
	 * 
	 * @author Sekar 
	 */

	function docs_count()
	{
	   $data = $this->ion_auth->docs_count_model(); 
	   $this->output->set_output(json_encode($data));
	}

	//***************Drop down notification***************END***************//


	// ------------------------------------------------------------------------

	/**
	 * Helper: Change of plan
	 * 
	 * @param string $companyname Name of the company
	 * 
	 * @author Selva 
	 */

	
	 function plan_upgrade($companyname)
	 {

	 }


	// ------------------------------------------------------------------------

	/**
	 * Helper: Download Files securely
	 *  
	 * @author Vikas 
	 */

	 public function external_file_download_call()
	 {
		$path = $_GET['path'];
		$this->external_file_download($path);
	 }
	 
      // ------------------------------------------------------------------------

	  /**
	  * Helper  Send notification to end users
	  *  
	  * @author Vikas 
	  */

	   public function send_notification()
	   {
	      $this->check_for_admin();
	   	  $this->check_for_plan('send_notification');
	     
		  if(isset($_POST))
		  {
			  $recipient_list  = $this->input->post('multiselect',TRUE);
			  $message         = $this->input->post('message',TRUE);
			  
			  $loggeduser = $this->session->userdata('customer');
			  $company    = $loggeduser['company'];
			  $sender     = $loggeduser['email'];
			  
			 foreach($recipient_list as $email)
             {
				 $receiver   = $email."_push_notifications";
				 $message_id = get_unique_id();
			     $this->sub_admin_Model->send_push_message($receiver,$message_id,$message,$company,$sender);
		     }
			 
			 $this->sub_admin_Model->push_message_save_history($message_id,$message,$company,$sender,$recipient_list);
			 $this->session->set_flashdata('notification_message','Notification Sent !');
				  
		  }
		    redirect('sub_admin/create_notification');
	   }
	   
	   //***************Analytics***************START***************//

      // --------------------------------------------------------------------

	  /**
	  * Helper : Analytics by enterprise admin 
	  *
	  * @author  Vikas
	  *
	  * 
	  */

	  function query_app($id,$updType = FALSE)
     {
    	$this->check_for_admin();
    	$this->check_for_plan('query_app');
    	$this->query($this->ion_auth->get_app_temp($id),$updType);
     }

	 // --------------------------------------------------------------------

	/**
	* Helper : Analytics by enterprise admin 
	*
	* @author  Vikas ( Modified by Sekar)
	*
	* 
	*/

    function query()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('query');
    	
    	$app_id = $_POST['query_app_id'];
    	
		$data = $this->ion_auth->get_app_temp($app_id);

    	//$this->_render_page('sub_admin/sub_dash', $this->data);
		$this->output->set_output(json_encode($data));
    			
    	
    }

    // --------------------------------------------------------------------

	/**
	* Helper : Save a pattern 
	*
	* @author  Selva 
	*
	* 
	*/

	function savepattern()
	{
	  	$this->check_for_admin();
	  	$this->check_for_plan('savepattern');
	  	
	  	log_message('debug','589999999999999999999999999999999999999999999999999999999999999999999'.print_r($_POST,true));
	  	
// 	    $data    = $_POST['pattern'];
// 	    $id      = $_POST['dataid'];
// 		$appname = $_POST['appname'];
// 		$title   = $_POST['pattern_title'];
// 		$des     = $_POST['pattern_title'];
// 		$label   = $_POST['label'];
// 		$value   = $_POST['queryvalue'];
// 		$option  = $_POST['option'];
	  	$u = $this->session->userdata("customer");
	  	
		$insertdata = array(
			'title' => $_POST['pattern_title'],
			'description' => $_POST['pattern_description'],
			'app_id' => $_POST['id'],
			'pattern' => $_POST['saved_query'],
			'app_name' => $_POST['name'],
			'query_user' => $u['email']
		);

		$this->load->model('Workflow_Model');
		$saveddata = $this->Workflow_Model->save_analytics_pattern($insertdata);
	    redirect('sub_admin/to_dashboard');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Helper : Delete saved query pattern
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function delete_saved_pattern($id)
	{
		$this->check_for_admin();
		$this->check_for_plan('delete_my_app');
	
		$this->ion_auth->delete_saved_pattern($id);
		
		redirect('sub_admin/to_dashboard');
	}
  
    // --------------------------------------------------------------------

	/**
	* Helper : Retrieve the saved analytics pattern 
	*
	* @param  string  $id         Application id
	* @param  string  $collection Collection name
	*
	* @author  Selva 
	*
	* 
	*/

    function get_saved_pattern($id,$collection)
    {
  	  $this->check_for_admin();
  	  $this->check_for_plan('get_saved_pattern');
  	
      $this->load->model('Workflow_Model');
      $this->data['pattern'] = $this->Workflow_Model->get_saved_pattern($id);
   
      $pattern = $this->data['pattern']['pattern'];
   
      $patternquery = json_decode($pattern,false);
      $conarray = array();
      $ind = 0;
      foreach($patternquery as $conditon)
      {
    	 foreach($conditon as $fld)
    	 {
    		array_push($conarray,$fld);
         }

    	 if(isset($conarray[2])=="TRUE")
    	 {
    		$operator[$ind]=$conarray[2];
    	 }
			
    	 $result[$ind] = $this->ion_auth->query(strtolower($conarray[0]),$conarray[1],$collection);
    	 $ind ++;
    	 $conarray = array();
    		
       }
       
       $reid = 0;
       foreach ($operator as $logi)
       {
    	  if($logi == "AND")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{
    				$logic[$reid] = array_intersect($res,$res2);
    				
    			}
    		}
    	  }
    	  elseif($logi == "OR")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{
    				$logic[$reid] = array_merge($res,$res2);  
    			}
    		}
    	  }
    	  $reid++;
    	}
        $this->session->set_userdata("savedpattern",json_encode(array_unique($logic)));	
		$this->after_get_saved_pattern();
       		
    } 
  
    // --------------------------------------------------------------------

	/**
	* Helper : Render the retrieved saved analytics pattern
	*
	* @author  Selva 
	*
	* 
	*/

    function after_get_saved_pattern()
    {
  	   $result = $this->session->userdata("savedpattern");
       $order = array("[","{","}","]");
       $replace ='';
       $this->data['result12'] = str_replace($order,$replace,$result);
       $this->_render_page('admin/admin_dash_pattern_result',$this->data);
    }
  
    // --------------------------------------------------------------------
    
    /**
     * Helper : Search for analytics
     *
     * @author  Vikas
     *
     *
     */
    
    function searching()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('searching');
    
    	$querystring = $_POST['strng'];
    	$appid       = $_POST['dataid'];
    	
    	$conditons = json_decode($querystring,true);
    	log_message('debug','sub--------------------18888888888889333333333333333333333333333333333333333'.print_r($appid,true));
    	$search_fields = array();
    	$combined_search = array();
    	$inc = 0;
    	
    	foreach($conditons as $case)
    	{
    		//log_message('debug','71888888888888888888888888888888888888888888888888888888888888888'.print_r($case,true));
    		$field = base64_decode($case['labelname']);
    		$search_fields[$inc] = array("doc_data.widget_data.page"."$field" => $case['value']);
    		//log_message('debug','72222222222222222222222222222222222222222222222222222222222'.print_r($search_fields,true));
    		$inc++;
    	}
    	
    	log_message('debug','72777777777777777777777777777777777777777777777777777777777777777'.print_r($inc,true));
    	//$search_fields = array_merge($search_fields[0],$search_fields[1]);
    	log_message('debug','722222222888888888888888888888888888888888888888888888888888888888888'.print_r($search_fields,true));
    	for($i=0;$i<$inc;$i++){
    		log_message('debug','1111111111111111111111111111111111111111111111111111111111111111111111111111111');
    		if($conditons[$i]['option'] == 'AND'){
    			log_message('debug','2222222222222222222222222222222222222222222222222222222222222222');
    			if($i == $inc-1){
    				log_message('debug','33333333333333333333333333333333333333333333333333333333333333333');
    				$combined_search = array_merge($search_fields[$i]);
    			}else{
    				log_message('debug','44444444444444444444444444444444444444444444444444444');
    				$combined_search = array_merge($search_fields[$i],$search_fields[$i+1]);
    			}
    		}else{
    			log_message('debug','5555555555555555555555555555555555555555555555555555555');
    			if($i == $inc-1){
    				log_message('debug','666666666666666666666666666666666666666666666666666666666666666666');
    				$doc[$i] = $this->ion_auth->query($search_fields[$i],$appid);
    			}else{
    				log_message('debug','7777777777777777777777777777777777777777777777777777777777777777777777777777');
    				$doc[$i] = $this->ion_auth->query($search_fields[$i],$appid);
    				$doc[$i+1] = $this->ion_auth->query($search_fields[$i+1],$appid);
    			}
    		}
    		log_message('debug','888888888888888888888888888888888888888888888888888888888888888888888888888888888888888');
    		$doc[$i] = $this->ion_auth->query($combined_search,$appid);
    	}
    	log_message('debug','7522222222222222222222222222222222222222222222222222222222222222222222'.print_r($doc,true));
    	
    	//log_message('debug','7677777777777777777777777777777777777777777777777777777'.print_r($final_doc,true));
    	$one_dimension = array_map("serialize", $doc);
    	$unique_one_dimension = array_unique($one_dimension);
    	$unique_multi_dimension = array_map("unserialize", $unique_one_dimension);
    	//$doc = array_unique($doc);
    	log_message('debug','7400000000000000000000000000000000000000000000000000000000000000000000'.print_r($unique_multi_dimension,true));
    	
    	
    	$data['docs'] = $unique_multi_dimension;
    	
    	$data['count'] = $this->ion_auth->query_app_count($appid);
    	
    	//log_message('debug','7344444444444444444444444444444444444444444444444444444444444444444'.print_r($data,true));
    
    	$this->output->set_output(json_encode($data));
    }
    
    //-----------------------item based analytics------------------------
    // --------------------------------------------------------------------
    
    /**
     * Helper : Search for analytics
     *
     * @author  Vikas
     *
     *
     */
    
    function item_analytics()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('item_analytics');
    
    	$querystring = $_POST['value'];
    	
    	$all_apps = $this->ion_auth->apps();
    	
    	foreach($all_apps as $app){
    		$documents = $this->ion_auth->get_all_docs($app[_id]);
    		log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff'.print_r($app[_id],true));
    	}
    	
    	
    	
    	//$this->output->set_output(json_encode($data));
    }
    
    // --------------------------------------------------------------------
}
