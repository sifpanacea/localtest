<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_admin_portal extends MY_Controller {

	  function __construct()
	  {
	     
		 parent::__construct();
		
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('paas');
		 $this->load->library('session');

		 // Load MongoDB library instead of native db driver if required
		 $this->config->load('email');
		 $this->load->library('mongo_db');
		 $this->load->helper('cookie');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		 
		 $this->lang->load('auth');
		 $this->load->helper('language');
		 $this->load->model('schoolhealth_admin_portal_model');
		 $this->load->model('ion_auth_mongodb_model');
		 
		 $this->load->library('schoolhealth_admin_lib');
		 $this->load->library('paas_common_lib');
	 }
	 
	 // --------------------------------------------------------------------

	/**
	 * Helper : Open EHR Search View
	 *
	 *
	 * @author  Selva
	 */
	 public function search_ehr()
	 {
	   $this->data["message"] = "";
	   $this->_render_page('schoolhealth/admins/ehr_search_view',$this->data);
    }
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Display EHR ( Expand View )
	 *
	 *
	 * @author  Selva
	 */
	 
	public function display_ehr_uid()
	{
	  $post = $_POST;
	  $this->data = $this->schoolhealth_admin_lib->reports_display_ehr_uid($post);
	  $this->_render_page('schoolhealth/admins/ehr_display_view',$this->data);
	}
	
	 public function manage_state()
	 {
		$this->check_for_admin();
		$this->check_for_plan('manage_state');

        $total_rows = $this->schoolhealth_admin_portal_model->statescount();

        //---pagination--------//
	   $config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['states'] = $this->schoolhealth_admin_portal_model->get_states($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['statcount'] = $total_rows;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		
		//$this->data = "";
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_states',$this->data);
	}
	
	public function create_state()
	{
	 	$this->schoolhealth_admin_portal_model->create_state($_POST);	
	 	redirect('schoolhealth_admin_portal/manage_state');
	}
	 
	public function delete_state($st_id)
	{
	 	$this->schoolhealth_admin_portal_model->delete_state($st_id);	
	 	redirect('schoolhealth_admin_portal/manage_state');
	}
	
	/////////////////////Districts/////
	 
	public function manage_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('schoolhealth_mgmt_district');

        $total_rows = $this->schoolhealth_admin_portal_model->distcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['dists'] = $this->schoolhealth_admin_portal_model->get_district($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['distscount'] = $total_rows;
		
		$this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states();
		log_message('debug','$this->data ==statelist===110'.print_r($this->data['statelist'],true));
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		//$this->data = "";
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_district',$this->data);
	}
	
	public function create_district()
	{
	 	$this->schoolhealth_admin_portal_model->create_district($_POST);	
	 	redirect('schoolhealth_admin_portal/manage_district');
	}
	 
	public function delete_district($dt_id)
	{
	 	$this->schoolhealth_admin_portal_model->delete_district($dt_id);	
	 	redirect('schoolhealth_admin_portal/manage_district');
	}
	
	//////////////////////schools///////////////////////////
	
	public function list_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_school');

        $total_rows = $this->schoolhealth_admin_portal_model->schoolscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['schools'] = $this->schoolhealth_admin_portal_model->get_schools($config['per_page'], $page);
		
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['schoolscount'] = $total_rows;
		$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_all_district();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		
		//$this->data = "";
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_school_list',$this->data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Create School
	 *
	 * @author Selva 
	 */
	 
	public function create_school()
	{
	  // POST DATA
	  $state_name     = $this->input->post('st_name',TRUE);
	  $district_name  = $this->input->post('dt_name',TRUE);
	  $school_name    = $this->input->post('school_name',TRUE);
	  $school_code    = $this->input->post('school_code',TRUE);
	  $username       = $this->input->post('username',TRUE);
	  $contact_person = $this->input->post('contact_person',TRUE);
	  $email   		  = $this->input->post('email',TRUE);
	  $password       = $this->input->post('password',TRUE);
	  $mobile         = $this->input->post('mobile',TRUE);
	  $address        = $this->input->post('address',TRUE);
	  $sub_admin      = $this->input->post('sub_admin',TRUE);
	  $sick_room      = $this->input->post('sick_room',TRUE);
	  
	  // Form entry
	  $data = array(
		"st_name"        => $state_name,
		"dt_name"        => $district_name,
		"school_code"    => $school_code,
		"school_name"    => $school_name,
		"username"       => $username,
		"contact_person" => $contact_person,
		"address"        => $address,
		"email"          => $email,
		"mobile"         => $mobile,
		"sub_admin"      => $sub_admin,
		"sick_room"      => $sick_room);
	log_message("debug","state nameee=====244".print_r($data,true));
	 
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
         // DEFAULT CONFIGURATIONS
		 $maxWidth  = 252;
		 $maxHeight = 52;
		 
		 $uploaddir = PROFILEUPLOADFOLDER;
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
	   
		$file = $uploaddir.$email.".png";
				  
        foreach($_FILES as $index => $value)
		{
		   if($value['tmp_name'] != '')
		   {
	          list($width, $height, $type, $attr) = getimagesize($_FILES['logo_file']['tmp_name']);
					  
			  if ($width > $maxWidth || $height > $maxHeight)
			  {
				 $this->session->set_flashdata('message', "Please upload logo with pre-defined dimensions");
				 redirect('schoolhealth_admin_portal/add_school');
			  }
			  else
			  {
				 if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file))
				 {
					
				 }
				 else
				 {
					$this->session->set_flashdata('message', "Logo Image upload failed");
					redirect('schoolhealth_admin_portal/add_school'); 
				 }
			  
			  }	   
		   }
		}
  
	  }
		
	  $added = $this->schoolhealth_admin_portal_model->create_school_model($data,$password);
	  
	  if($added)
	  {
        $this->session->set_flashdata('message',"School added successfully !");
	    redirect('schoolhealth_admin_portal/list_school'); 
	  }
	  else
	  {
        $this->session->set_flashdata('message',"Failed ! Try again ! !");
        redirect('schoolhealth_admin_portal/add_school'); 
	  }
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Load school add view
	 *
	 * @author Naresh 
	 */
	 public function edit_school($id = NULL)
	 {
		  
		 $this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states_edit();
		  
		  log_message("debug","statelist=======316".print_r($this->data['statelist'],true));
		  
		  $this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_all_district();
		  
		  $this->data['subadmins'] = $this->schoolhealth_admin_portal_model->get_all_subadmins();
		  
		  $this->data['edit_details'] = $this->schoolhealth_admin_portal_model->get_edit_details($id);
		 
		  log_message("debug","edit_school=======316===".print_r($this->data['edit_details'],true));
		  
		 // redirect('schoolhealth_admin_portal/list_school');
		  $this->_render_page("schoolhealth/admins/school_edit",$this->data);
		  return $this->data;
		 
	 }
	 
	 /**
	 * Helper : Load school add view
	 *
	 * @author Naresh 
	 */
	 public function update_school()//id = NULL
	 {
	   // $this->data['get_id'] = $this->schoolhealth_admin_portal_model->get_update_details($id);
	   // log_message("debug","gettttttt=====336".print_r($this->data['get_id'],true));
	   // POST DATA
	  $state_name     = $this->input->post('st_name',TRUE);
	  $district_name  = $this->input->post('dt_name',TRUE);
	  $school_name    = $this->input->post('school_name',TRUE);
	  $school_code    = $this->input->post('school_code',TRUE);
	  $username       = $this->input->post('username',TRUE);
	  $contact_person = $this->input->post('contact_person',TRUE);
	  $email   		  = $this->input->post('email',TRUE);
	  $password       = $this->input->post('password',TRUE);
	  $mobile         = $this->input->post('mobile',TRUE);
	  $address        = $this->input->post('address',TRUE);
	  $sub_admin      = $this->input->post('sub_admin',TRUE);
	  $sick_room      = $this->input->post('sick_room',TRUE);
	  $id     		  = $this->input->post('id',TRUE);
	  
	  //log_message("debug","id========347".print_r($id,true));
	  // Form entry
	  $data = array(
		"st_name"        => $state_name,
		"dt_name"        => $district_name,
		"school_code"    => $school_code,
		"school_name"    => $school_name,
		"username"       => $username,
		"contact_person" => $contact_person,
		"address"        => $address,
		"email"          => $email,
		"mobile"         => $mobile,
		"sub_admin"      => $sub_admin,
		"sick_room"      => $sick_room);
	log_message("debug","state nameee=====244".print_r($data,true));
	 
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
         // DEFAULT CONFIGURATIONS
		  // $maxWidth  = 252;
		 // $maxHeight = 52;
		 $maxWidth  = 520;
		 $maxHeight = 252;
		 
		 $uploaddir = PROFILEUPLOADFOLDER;
		 log_message("debug","uploaddirrrr====373".print_r($uploaddir,true));
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
	   
		$file = $uploaddir.$email.".png";
		log_message("debug","file========381".print_r($file,true));		  
        foreach($_FILES as $index => $value)
		{
		   if($value['tmp_name'] != '')
		   {
	          list($width, $height, $type, $attr) = getimagesize($_FILES['logo_file']['tmp_name']);
					  
			  if ($width > $maxWidth || $height > $maxHeight)
			  {
				 $this->session->set_flashdata('message', "Please upload logo with pre-defined dimensions");
				 redirect('schoolhealth_admin_portal/add_school');
			  }
			  else
			  {
				 if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file))
				 {
					
				 }
				 else
				 {
					$this->session->set_flashdata('message', "Logo Image upload failed");
					redirect('schoolhealth_admin_portal/add_school'); 
				 }
			  
			  }	   
		   }
		}
  
	  }
		
	  $added = $this->schoolhealth_admin_portal_model->update_school_details_model($id,$data);
	  log_message("debug","added===========411".print_r($added,true));
	  
	  if($added)
	  {
        $this->session->set_flashdata('message',"School added successfully !");
	    redirect('schoolhealth_admin_portal/list_school'); 
	  }
	  else
	  {
        $this->session->set_flashdata('message',"Failed ! Try again ! !");
        redirect('schoolhealth_admin_portal/add_school'); 
	  }
	  $this->_render_page("schoolhealth/admins/school_edit",$added);
	  return $this->data;
	 }
	
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Load school add view
	 *
	 * @author Prakash 
	 */
	 
	public function add_school()
	{
	    $this->data['message']   = $this->session->flashdata('message');
		$this->data['subadmins'] = $this->schoolhealth_admin_portal_model->get_all_subadmins();
		$this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states();
	 	$this->_render_page('schoolhealth/admins/create_school_admin',$this->data);
		
	}
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Delete School
	 *
	 * @author  Selva
	 *
	 */
	 
	public function delete_school($school_id)
	{
	 	$deleted = $this->schoolhealth_admin_portal_model->delete_school_model($school_id);
		if($deleted)
		  $this->session->set_flashdata('message','SChool deleted successfully !');
	    else
		  $this->session->set_flashdata('message','Failed ! Try again ! !');
	 	redirect('schoolhealth_admin_portal/list_school');
	}
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : List down the sub admins with status controls ( Activate/Deactivate )
	 *
	 * @author  Selva
	 *
	 */
	 
	function list_schools_with_status_controls()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_schools_with_status_controls');

        $total_rows = $this->schoolhealth_admin_portal_model->schoolscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['schools'] = $this->schoolhealth_admin_portal_model->get_schools($config['per_page'], $page);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['schoolscount'] = $total_rows;
		
		$this->_render_page('schoolhealth/admins/schoolhealth_school_control',$this->data);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Activate the school ( Existing De-activated school )
	 *
	 * @author  Ben
	 *
	 */
	
	function activate_school($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->schoolhealth_admin_portal_model->activate_school_model($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
		    $activation = $this->schoolhealth_admin_portal_model->activate_school_model($id);
		}
	
		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("schoolhealth_admin_portal/list_school");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("schoolhealth_admin_portal/forgot_password", 'refresh');
		}
	}

    // --------------------------------------------------------------------
	
	/**
	 * Helper : De-activate the school ( admin ) 
	 *
	 * @author  Ben
	 *
	 *
	 */
	 
	public function deactivate_school($id = NULL)
	{

		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('_id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['message'] = "User Deactivation";
			$this->data['csrf']    = get_csrf_nonce();  // Using PaaS helper function
            $this->data['user']    = $this->schoolhealth_admin_portal_model->get_school_by_id($id);
			
			$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_school_deactivate',$this->data);
		}
		else
		{
	        // do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
		        // do we have the right userlevel?
				$this->schoolhealth_admin_portal_model->deactivate_school_model($id);
			}

			//redirect them back to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('schoolhealth_admin_portal/list_school');
		}
		
	}
	
	//////////////////////// sub admins //////////////
	
	public function list_sub_admin()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_sub_admin');

        $total_rows = $this->schoolhealth_admin_portal_model->subadminscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['subadmins'] = $this->schoolhealth_admin_portal_model->get_subadmins($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['subadminscount'] = $total_rows;
		//$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_subadmins();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		
		//$this->data = "";
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_subadmin_list',$this->data);
	}
	
	public function create_sub_admin()
	{
	   // Form entry
	   $data = array(
		"st_name"           => $this->input->post('st_name',TRUE),
		"dt_name"           => $this->input->post('dt_name',TRUE),
		"organization_name" => $this->input->post('organization_name',TRUE),
		"address"           => $this->input->post('address',TRUE),
		"username"          => $this->input->post('contact_person',TRUE),
		"email"             => $this->input->post('email',TRUE),
		"mobile"            => $this->input->post('mobile',TRUE),
		"contact_person"    => $this->input->post('contact_person',TRUE),
		"active"            => 1,
		"last_login"        => date('Y-m-d H:i:s'),
		"password"          => $this->input->post('password',TRUE));
		
		$email = $this->input->post('email',TRUE); 
		
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
        // DEFAULT CONFIGURATIONS
		$maxWidth  = 252;
		$maxHeight = 52;
	     
		$uploaddir = PROFILEUPLOADFOLDER;
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
		
		$file = $uploaddir.$email.".png";
				  
        foreach($_FILES as $index => $value)
		{
		   if($value['tmp_name'] != '')
		   {																					
			  
			  list($width, $height, $type, $attr) = getimagesize($_FILES['logo_file']['tmp_name']);
					  
			  if ($width > $maxWidth || $height > $maxHeight)
			  {
				 $this->session->set_flashdata('message', "Please upload logo with pre-defined dimensions");
				 redirect('schoolhealth_admin_portal/add_sub_admin');
			  }
			  else
			  { 
				 if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file))
				 {
					
				 }
				 else
				 {
					$this->session->set_flashdata('message', "Logo Image upload failed");
					redirect('schoolhealth_admin_portal/add_sub_admin'); 
				 }
			  
			  }	   
		   }
		}
  
	  }
		
	  $added = $this->schoolhealth_admin_portal_model->create_sub_admin_model($data);
	  
	  if($added)
	  {
        $this->session->set_flashdata('message',"School added successfully !");
	    redirect('schoolhealth_admin_portal/list_sub_admin'); 
	  }
	  else
	  {
        $this->session->set_flashdata('message',"Failed ! Try again ! !");
        redirect('schoolhealth_admin_portal/add_sub_admin'); 
	  }
	  
	}
	
	public function add_sub_admin()
	{
		$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_all_district();
		$this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states();
	 	$this->_render_page('schoolhealth/admins/create_sub_admin',$this->data);
	}


	
	public function delete_sub_admin($school_id)
	{
	 	$deleted = $this->schoolhealth_admin_portal_model->delete_sub_admin_model($school_id);	
		if($deleted)
		  $this->session->set_flashdata('message','Sub admin deleted successfully !');
	    else
		  $this->session->set_flashdata('message','Failed ! Try again ! !');
	  
	 	redirect('schoolhealth_admin_portal/list_sub_admin');
	}
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : List down the sub admins with status controls ( Activate/Deactivate )
	 *
	 * @author  Selva
	 *
	 */
	 
	function list_sub_admins_with_status_controls()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_sub_admins_with_status_controls');

        $total_rows = $this->schoolhealth_admin_portal_model->subadminscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['subadmins'] = $this->schoolhealth_admin_portal_model->get_subadmins($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['subadminscount'] = $total_rows;
		
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_subadmin_activate',$this->data);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Activate the sub admin ( Existing De-activated sub admin )
	 *
	 * @author  Ben
	 *
	 */
	
	function activate_sub_admin($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->schoolhealth_admin_portal_model->activate_sub_admin_model($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
		    $activation = $this->schoolhealth_admin_portal_model->activate_sub_admin_model($id);
		}
	
		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("schoolhealth_admin_portal/list_sub_admin");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("schoolhealth_admin_portal/forgot_password", 'refresh');
		}
	}

    // --------------------------------------------------------------------
	
	/**
	 * Helper : De-activate the sub admin 
	 *
	 * @author  Ben
	 *
	 *
	 */
	 
	public function deactivate_sub_admin($id = NULL)
	{

		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('_id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['message'] = "User Deactivation";
			$this->data['csrf']    = get_csrf_nonce();  // Using PaaS helper function
            $this->data['user']    = $this->schoolhealth_admin_portal_model->get_subadmin_by_id($id);
			
			$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_subadmin_deactivate',$this->data);
		}
		else
		{
	        // do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
		        // do we have the right userlevel?
				$this->schoolhealth_admin_portal_model->deactivate_sub_admin_model($id);
			}

			//redirect them back to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('schoolhealth_admin_portal/list_sub_admin');
		}
		
	}
	
	
	////////////////////clinics  ///////////////////
	public function list_clinic()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_clinic');

        $total_rows = $this->schoolhealth_admin_portal_model->clinicscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['clinics'] = $this->schoolhealth_admin_portal_model->get_clinics($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['clinicscount'] = $total_rows;
		//$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_clinics();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		
		//$this->data = "";
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_clinic_list',$this->data);
	}
	
	public function create_clinic()
	{
	   // Form entry
	   $data = array(
		"st_name"           => $this->input->post('st_name',TRUE),
		"dt_name"           => $this->input->post('dt_name',TRUE),
		"clinic_name"       => $this->input->post('clinic_name',TRUE),
		"address"           => $this->input->post('address',TRUE),
		"username"          => $this->input->post('username',TRUE),
		"email"             => $this->input->post('email',TRUE),
		"mobile"            => $this->input->post('mobile',TRUE),
		"contact_person"    => $this->input->post('contact_person',TRUE),
		"active"            => 1,
		"last_login"        => date('Y-m-d H:i:s'),
		"password"          => $this->input->post('password',TRUE),
		"sub_admin"         => $this->input->post('sub_admin',TRUE));
		
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
        // DEFAULT CONFIGURATIONS
		$maxWidth  = 252;
		$maxHeight = 52;
	    
		$uploaddir = PROFILEUPLOADFOLDER;
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
		
		$file = $uploaddir.$email.".png";
				  
        foreach($_FILES as $index => $value)
		{
		   if($value['tmp_name'] != '')
		   {
	          list($width, $height, $type, $attr) = getimagesize($_FILES['logo_file']['tmp_name']);
					  
			  if ($width > $maxWidth || $height > $maxHeight)
			  {
				 $this->session->set_flashdata('message', "Please upload logo with pre-defined dimensions");
				 redirect('schoolhealth_admin_portal/add_clinic');
			  }
			  else
			  {
				 if(move_uploaded_file($_FILES['logo_file']['tmp_name'], $file))
				 {
					
				 }
				 else
				 {
					$this->session->set_flashdata('message', "Logo Image upload failed");
					redirect('schoolhealth_admin_portal/add_clinic'); 
				 }
			  
			  }	   
		   }
		}
  
	  }
		
	  $added = $this->schoolhealth_admin_portal_model->create_clinic_model($data);
	  
	  if($added)
	  {
        $this->session->set_flashdata('message',"School added successfully !");
	    redirect('schoolhealth_admin_portal/list_clinic'); 
	  }
	  else
	  {
        $this->session->set_flashdata('message',"Failed ! Try again ! !");
        redirect('schoolhealth_admin_portal/add_clinic'); 
	  }
	}
	
	
	public function add_clinic()
	{
		$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_all_district();
		$this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states();
		$this->data['subadmins'] = $this->schoolhealth_admin_portal_model->get_all_subadmins();
		$this->_render_page('schoolhealth/admins/create_clinic_admin',$this->data);
	}
	 
	public function delete_clinic($clinic_id)
	{
	 	$deleted = $this->schoolhealth_admin_portal_model->delete_clinic_model($clinic_id);	
		if($deleted)
		  $this->session->set_flashdata('message','Clinic deleted successfully !');
	    else
		  $this->session->set_flashdata('message','Failed ! Try again ! !');
	 	redirect('schoolhealth_admin_portal/list_clinic');
	}
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : List down the sub admins with status controls ( Activate/Deactivate )
	 *
	 * @author  Selva
	 *
	 */
	 
	function list_clinics_with_status_controls()
	{
		$this->check_for_admin();
		$this->check_for_plan('list_clinics_with_status_controls');

        $total_rows = $this->schoolhealth_admin_portal_model->clinicscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['clinics'] = $this->schoolhealth_admin_portal_model->get_clinics($config['per_page'], $page);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['clinicscount'] = $total_rows;
		
		$this->_render_page('schoolhealth/admins/schoolhealth_clinic_control',$this->data);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Activate the sub admin ( Existing De-activated sub admin )
	 *
	 * @author  Ben
	 *
	 */
	
	function activate_clinic($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->schoolhealth_admin_portal_model->activate_clinic_model($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
		    $activation = $this->schoolhealth_admin_portal_model->activate_clinic_model($id);
		}
	
		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("schoolhealth_admin_portal/list_clinic");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("schoolhealth_admin_portal/forgot_password", 'refresh');
		}
	}

    // --------------------------------------------------------------------
	
	/**
	 * Helper : De-activate the sub admin 
	 *
	 * @author  Ben
	 *
	 *
	 */
	 
	public function deactivate_clinic($id = NULL)
	{

		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('_id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['message'] = "User Deactivation";
			$this->data['csrf']    = get_csrf_nonce();  // Using PaaS helper function
            $this->data['user']    = $this->schoolhealth_admin_portal_model->get_clinic_by_id($id);
			
			$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_clinic_deactivate',$this->data);
		}
		else
		{
	        // do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
		        // do we have the right userlevel?
				$this->schoolhealth_admin_portal_model->deactivate_clinic_model($id);
			}

			//redirect them back to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('schoolhealth_admin_portal/list_clinic');
		}
		
	}
	
	/* public function edit_clinic()
	{
		$this->data['distslist'] = $this->schoolhealth_admin_portal_model->get_all_district();
		$this->data['statelist'] = $this->schoolhealth_admin_portal_model->get_all_states();
		$this->_render_page('schoolhealth/admins/schoolhealth_mgmt_clinic_create',$this->data);
	}
 */
	 ///////////////////////forgot password////////////
	 
	 function change_password()
	{
		
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'schoolhealth_auth/login');
		}

		$user = $this->session->userdata('customer');

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ion_auth->errors();

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new_pwd',
				'id'   => 'new_pwd',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user['user_id'],
			);
			
			//render
			$this->_render_page('schoolhealth/admins/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			
			log_message('debug','$identity=====782======'.print_r($identity,true));
			
			$change = $this->schoolhealth_admin_portal_model->change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));
			
			log_message('debug','$identity=====786======'.print_r($change,true));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('schoolhealth_auth/logout');
			}
			else
			{
				//$this->session->set_flashdata('message', $this->ion_auth->errors());
				//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ion_auth->errors();

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new_pwd',
				'id'   => 'new_pwd',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user['user_id'],
			);
			
			log_message('debug','$identity=====827======'.print_r($user['user_id'],true));
			
			//render
			$this->_render_page('schoolhealth/admins/change_password', $this->data);
			}
		}
	}
	
	public function get_districts_list_for_state()
	{
		$state = $this->input->post('state',TRUE);
		$districts = $this->schoolhealth_admin_portal_model->get_district_list_for_state_model($state);
		if($districts)
		{
			$this->output->set_output(json_encode($districts));
		}
		else
		{
			$this->output->set_output('NO_DISTRICTS');
		}
	}
}

/* End of file schoolhealth_admin_portal.php */
/* Location: ./application/customers/controllers/schoolhealth_admin_portal.php */