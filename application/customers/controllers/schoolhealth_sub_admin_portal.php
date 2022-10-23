<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_sub_admin_portal extends MY_Controller {

	  function __construct()
	  {
	     
		 parent::__construct();
		
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('file');
		 $this->load->helper('paas');
		 $this->load->helper('myform');
		 $this->load->library('session');

		 // Load MongoDB library instead of native db driver if required
		 $this->config->load('email');
		 $this->load->library('mongo_db');
		 $this->load->helper('cookie');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		 
         $this->load->library('bhashsms');
         $this->load->library('schoolhealth_sub_admin_lib');
		 $this->lang->load('auth');
		 $this->load->helper('language');
		 $this->load->model('schoolhealth_sub_admin_portal_model');
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
	   $this->_render_page('schoolhealth/sub_admins/ehr_search_view',$this->data);
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
	  $this->data = $this->schoolhealth_sub_admin_lib->reports_display_ehr_uid($post);
	  $this->_render_page('schoolhealth/sub_admins/ehr_display_view',$this->data);
	}
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : List all referral doctors
	 *
	 *
	 * @author  Selva
	 */
	 public function list_doctors()
	 {
	    // Logged in sub admin 
		$sub_admin = $this->session->userdata('customer');
		$email = $sub_admin['email'];
		$this->data['message'] = $this->session->flashdata('message');
		$this->data = $this->schoolhealth_sub_admin_lib->doctors($email);
		$this->_render_page('schoolhealth/sub_admins/referral_doctors',$this->data);
     }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : List all referral doctors
	 *
	 *
	 * @author  Selva
	 */
	 public function add_specialization_view()
	 {
	    // Logged in sub admin 
		$sub_admin = $this->session->userdata('customer');
		$email = $sub_admin['email'];
		$this->data = $this->schoolhealth_sub_admin_lib->specializations($email);
		$this->_render_page('schoolhealth/sub_admins/specialization',$this->data);
     }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Add specialization
	 *
	 *
	 * @author  Selva
	 */
	 
	 public function add_specialization()
	 {
	    //Sub Admin Details
	    $sub_admin = $this->session->userdata('customer');
	    $subadminemail = $sub_admin['email'];
		$_POST['spec_added_by'] = $subadminemail;
	    $this->schoolhealth_sub_admin_portal_model->add_specialization_model($_POST);	
	 	redirect('schoolhealth_sub_admin_portal/add_specialization_view');
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete specialization
	 *
	 *
	 * @author  Selva
	 */
	 
	 public function delete_specialization($spec_id)
	{
	 	$this->schoolhealth_sub_admin_portal_model->delete_specialization($spec_id);	
	 	redirect('schoolhealth_sub_admin_portal/add_specialization_view');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Add Doctor View
	 *
	 *
	 * @author  Selva
	 */
	 
	function create_doctor_view()
	{
	   //Sub Admin Details
	   $sub_admin = $this->session->userdata('customer');
	   $subadminemail = $sub_admin['email'];
	   
	   $this->data = $this->schoolhealth_sub_admin_lib->specializations($subadminemail);
	   $this->_render_page('schoolhealth/sub_admins/create_doctor_view',$this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Add Doctor 
	 *
	 *
	 * @author  Selva
	 */
	 
	function add_referral_doctor()
	{
	  // POST DATA
	  $name    = $this->input->post('name',TRUE);
	  $mobile  = $this->input->post('mobile',TRUE);
	  $email   = $this->input->post('email',TRUE);
	  $qualification = $this->input->post('qualification',TRUE);
	  $address = $this->input->post('address',TRUE);
	  $spec    = $this->input->post('specialization',TRUE);
	  
	  //Sub Admin Details
	  $sub_admin = $this->session->userdata('customer');
	  $subadminemail = $sub_admin['email'];
	  
	  $data = array(
	  'name'   => $name,
	  'mobile' => array('country_code' => "91",'mob_num'=>$mobile),
	  'email'  => $email,
	  'qualification' => $qualification,
	  'address' => $address,
	  'specialization' => $spec,
	  'referred_by' => $subadminemail);
	  
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
		 $uploaddir = PROFILEUPLOADFOLDER;
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
	   
		
		/***** Profile Image *****/
	    $file = $uploaddir.$email.".png";
	    $path = ".".UPLOADFOLDER.'public/'.$email.".png";
				  
        if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file)) 
			  { 
				  // creating image thumbnail for header profile image
				  // Get the CodeIgniter super object
				  $CI =& get_instance();

				  // Path to image thumbnail
				  $image_thumb = $uploaddir.$email."_thumb.png";

				  // LOAD LIBRARY
				  $CI->load->library( 'image_lib' );

				  // CONFIGURE IMAGE LIBRARY
				  $config['image_library']    = 'gd2';
				  $config['source_image']     = $file;
				  $config['new_image']        = $image_thumb;
				  $config['maintain_ratio']   = false;
				  $config['height']           = 90;
				  $config['width']            = 90;
				  $CI->image_lib->initialize( $config );
				  $CI->image_lib->resize();
				  $CI->image_lib->clear();
			  }
			  
			  $data['profile_pic_path'] = $path;
	
  
	  }
	  
	  $added = $this->schoolhealth_sub_admin_portal_model->add_referral_doctor_model($data);
	  if($added)
	  {
        $this->session->set_flashdata('message','Doctor added successfully');
	    redirect('schoolhealth_sub_admin_portal/list_doctors');
	  }
	  else
	  {
        $this->session->set_flashdata('message','Failed ! Try again !!');
		redirect('schoolhealth_sub_admin_portal/list_doctors');
	  }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get doctor by ID
	 *
	 * @param  string  $id  Doctor _id field ( MongoID )
	 *
	 * @author  Selva
	 */
	 
	function get_referral_doctor_id($id = FALSE)
	{
	
		$this->data['refferal_doctor'] = $this->schoolhealth_sub_admin_portal_model->get_referral_doctor_model($id);
		$this->data['specializations'] = $this->schoolhealth_sub_admin_portal_model->get_specialization_model();
		log_message('debug','get_referral_doctor_id=================254====='.print_r($this->data,true));
		$this->_render_page('schoolhealth/sub_admins/update_doctor_details_view',$this->data);
	
	}
	// --------------------------------------------------------------------

	/**
	 * Helper : Edit Doctor 
	 *
	 * @param  string  $id  Doctor _id field ( MongoID )
	 *
	 * @author  Selva
	 */
	 
	function update_referral_doctor()
	{
		
	  $id=$this->input->post('doctor_id');
		  // POST DATA
	  $name    = $this->input->post('name',TRUE);
	  $mobile  = $this->input->post('mobile',TRUE);
	  $email   = $this->input->post('email',TRUE);
	  $qualification = $this->input->post('qualification',TRUE);
	  $address = $this->input->post('address',TRUE);
	  $spec    = $this->input->post('specialization',TRUE);
	  
	  //Sub Admin Details
	  $sub_admin = $this->session->userdata('customer');
	  $subadminemail = $sub_admin['email'];
	  
	  $data = array(
	  'name'   => $name,
	  'mobile' => array('country_code' => "91",'mob_num'=>$mobile),
	  'email'  => $email,
	  'qualification' => $qualification,
	  'address' => $address,
	  'specialization' => $spec);
	  
	  // Logo
	  if(isset($_FILES) && !empty($_FILES))
	  {
		 $uploaddir = PROFILEUPLOADFOLDER;
			  
		if (!is_dir($uploaddir))
		{
		 mkdir($uploaddir,0777,TRUE);
		}
	   
		
		/***** Profile Image *****/
	    $file = $uploaddir.$email.".png";
	    $path = ".".UPLOADFOLDER.'public/'.$email.".png";
				  
        if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file)) 
			  { 
				  // creating image thumbnail for header profile image
				  // Get the CodeIgniter super object
				  $CI =& get_instance();

				  // Path to image thumbnail
				  $image_thumb = $uploaddir.$email."_thumb.png";

				  // LOAD LIBRARY
				  $CI->load->library( 'image_lib' );

				  // CONFIGURE IMAGE LIBRARY
				  $config['image_library']    = 'gd2';
				  $config['source_image']     = $file;
				  $config['new_image']        = $image_thumb;
				  $config['maintain_ratio']   = false;
				  $config['height']           = 90;
				  $config['width']            = 90;
				  $CI->image_lib->initialize( $config );
				  $CI->image_lib->resize();
				  $CI->image_lib->clear();
			  }
			  
			  $data['profile_pic_path'] = $path;
	
  
	  }
	  
	  $updated = $this->schoolhealth_sub_admin_portal_model->update_referral_doctor_model($id, $data);
	  if($updated)
	  {
        $this->session->set_flashdata('message','Doctor details updated successfully');
	    redirect('schoolhealth_sub_admin_portal/list_doctors');
	  }
	  else
	  {
        $this->session->set_flashdata('message','Failed ! Try again !!');
		redirect('schoolhealth_sub_admin_portal/list_doctors');
	  }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete Doctor 
	 *
	 * @param  string  $id  Doctor _id field ( MongoID )
	 *
	 * @output bool
	 *
	 * @author  Selva
	 */
	 
	function delete_referral_doctor($id)
	{
		//delete the item
		if ($this->schoolhealth_sub_admin_portal_model->delete_referral_doctor_model($id) == TRUE)
		{
	       $this->session->set_flashdata('message','Doctor deleted successfully !');
		}
		else
		{
			$this->session->set_flashdata('message','Failed ! Try again !!');
		}
		
		redirect('schoolhealth_sub_admin_portal/list_doctors');
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Load school display view
	 *
	 * @author  Selva
	 */
	 
	function schools()
	{
	  //Sub Admin Details
	  $sub_admin  = $this->session->userdata('customer');
	  $subadminid = $sub_admin['user_id'];
		
	  $this->data['states']       = $this->schoolhealth_sub_admin_portal_model->get_all_states();
	  $this->data['schoolscount'] = $this->schoolhealth_sub_admin_portal_model->get_all_subscribed_schools_count($subadminid);
	  $this->data['message'] = "";
	  $this->_render_page('schoolhealth/sub_admins/schools_view',$this->data);
	
	}
	// --------------------------------------------------------------------

	/**
	 * Helper : Import zipfile 
	 *
	 * @author  Naresh
	 */
	
	function imports()
	{
	  $sub_admin  = $this->session->userdata('customer');
	  $subadminid = $sub_admin['user_id'];
	  $this->data['message'] = "";
	  $this->_render_page('schoolhealth/sub_admins/zipfile_import_view',$this->data);
	
	}
	
	//Excel importing function
	//author Naresh
	 function school_screening_file_import()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('school_screening_file_import');
		
		$post = $_POST;
		$this->data = $this->schoolhealth_sub_admin_lib->school_screening_file_import($post);
		
    }
	//Excel importing function
	//author Naresh
	 function import_screening_lib()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('import_screening_lib');
		
		$post = $_POST;
		$this->data = $this->schoolhealth_sub_admin_lib->import_screening_lib($post);
		
		$this->data['message'] = "";
		$this->_render_page('panacea_cc/to_dashboard', $this->data);
		
    }
	// --------------------------------------------------------------------

	/**
	 * Helper : Get districts for the the selected state
	 *
	 * @param  string  $state  State _id field ( MongoID )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_districts_list_for_state()
	{
		$state = $this->input->post('state',TRUE);
		$districts = $this->schoolhealth_sub_admin_portal_model->get_district_list_for_state_model($state);
		if($districts)
		{
			$this->output->set_output(json_encode($districts));
		}
		else
		{
			$this->output->set_output('NO_DISTRICTS');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get all districts ( for all the states )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_all_districts()
	{
		$districts = $this->schoolhealth_sub_admin_portal_model->get_all_districts_model();
		if($districts)
		{
			$this->output->set_output(json_encode($districts));
		}
		else
		{
			$this->output->set_output('NO_DISTRICTS');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get schools for the the selected district
	 *
	 * @param  string  $dist_id  District _id field ( MongoID )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		//Sub Admin Details
	    $sub_admin  = $this->session->userdata('customer');
	    $subadminid = $sub_admin['user_id'];
		
		$dist_id    = $this->input->post('dist_id',TRUE);
		$state_id   = $this->input->post('state_id',TRUE);
		$this->data = $this->schoolhealth_sub_admin_portal_model->get_schools_by_dist_id($dist_id,$state_id,$subadminid);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get schools for the the all districts ( for all states )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_all_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_all_schools_list');
		
		//Sub Admin Details
	    $sub_admin  = $this->session->userdata('customer');
	    $subadminid = $sub_admin['user_id'];
		
		$this->data = $this->schoolhealth_sub_admin_portal_model->get_all_schools_list_model($subadminid);
		$this->output->set_output(json_encode($this->data));
	}
	
	function clinics()
	{
	  //Sub Admin Details
	  $sub_admin  = $this->session->userdata('customer');
	  $subadminid = $sub_admin['user_id'];
	  
	  $this->data['states']       = $this->schoolhealth_sub_admin_portal_model->get_all_states();
	  $this->data['clinicscount'] = $this->schoolhealth_sub_admin_portal_model->get_all_subscribed_clinics_count($subadminid);
	  $this->data['message'] = "";
	  $this->_render_page('schoolhealth/sub_admins/clinics_view',$this->data);
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get schools for the the selected district
	 *
	 * @param  string  $dist_id  District _id field ( MongoID )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_clinics_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_clinics_list');
		
		//Sub Admin Details
	    $sub_admin  = $this->session->userdata('customer');
	    $subadminid = $sub_admin['user_id'];
		
		$dist_id    = $this->input->post('dist_id',TRUE);
		$state_id   = $this->input->post('state_id',TRUE);
		$this->data = $this->schoolhealth_sub_admin_portal_model->get_clinics_by_dist_id($dist_id,$state_id,$subadminid);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get schools for the the all districts ( for all states )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	public function get_all_clinics_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_all_clinics_list');
		
		//Sub Admin Details
	    $sub_admin  = $this->session->userdata('customer');
	    $subadminid = $sub_admin['user_id'];
		
		$this->data = $this->schoolhealth_sub_admin_portal_model->get_all_clinics_list_model($subadminid);
		$this->output->set_output(json_encode($this->data));
	}
	
	function sickroom()
	{
	  //$doctors = $this->schoolhealth_sub_admin_portal_model->fetch_all_doctors();
	  $this->data['message'] = "";
	  $this->_render_page('schoolhealth/sub_admins/sickroom_view',$this->data);
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		log_message('debug','$pie_data=====282====='.print_r($_POST,true));
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->schoolhealth_sub_admin_portal_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->schoolhealth_sub_admin_portal_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	
	// -------------------------------------------------------------------------------

	/**
	 * Helper : Analytics 
	 *
	 * @param  string  today_date          Date 
	 * @param  string  screening_pie_span  Screening pie span ( Weekly,Monthly etc., )
	 *
	 * @output string
	 *
	 * @author  Selva
	 */
	 
	function to_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		     
		$this->data = $this->schoolhealth_sub_admin_lib->to_dashboard_with_date($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_states($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		log_message('debug','screening_report=====drilling_screening_to_districts====558'.print_r($screening_report,true));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		log_message('debug','drill_down_screening_to_students_load_ehr===_POST==='.print_r($_POST,true));
		log_message('debug','drill_down_screening_to_students_load_ehr===DOCS_ID==='.print_r($docs_id,true));
		
		$get_docs = $this->schoolhealth_sub_admin_portal_model->get_drilling_screenings_students_docs($docs_id);
		
		//Sub Admin Details
	    $sub_admin     = $this->session->userdata('customer');
	    $subadminemail = $sub_admin['email'];
		
		$referral_doctors = $this->schoolhealth_sub_admin_portal_model->get_referral_doctors_list($subadminemail);
		
		log_message('debug','sub_admin======drill_down_screening_to_students_load_ehr'.print_r($sub_admin,true));
		log_message('debug','referral_doctors======drill_down_screening_to_students_load_ehr'.print_r($referral_doctors,true));
		
		$this->data['students']     = $get_docs;
		$this->data['doctor_list']  = $referral_doctors;
		$navigation                 = $_POST['ehr_navigation'];
		$this->data['navigation']   = $navigation;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('schoolhealth/sub_admins/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		
		$docs = $this->schoolhealth_sub_admin_portal_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs']          = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('schoolhealth/sub_admins/reports_display_ehr',$this->data);
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->schoolhealth_sub_admin_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : Edit a existing user
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function edit_counsellor($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() )
		{
			redirect(URC.'diabetic_care_auth/login');
		}
		
		$counsellor = $this->diabetic_care_admin_portal_model->fetch_counsellor_by_id($id);
		
		
        
		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone')
			);

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}
			
			//update the email if it was posted
			if ($this->input->post('email'))
			{
				$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
				$data['email'] = $this->input->post('email');
			}

			if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data))
			{
				    // unset csrf userdata
				    unset_csrf_userdata();
                    
					$this->session->set_flashdata('message',$this->ion_auth->messages());
					redirect('diabetic_care_admin_portal/counsellor');
			}
			else
			{

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

				//pass the user to the view
				$this->data['user'] = $user;

				$this->data['first_name'] = array(
					'name'  => 'first_name',
					'id'    => 'first_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('first_name', $user->first_name),
				);
				$this->data['last_name'] = array(
					'name'  => 'last_name',
					'id'    => 'last_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('last_name', $user->last_name),
				);
				
				$this->data['phone'] = array(
					'name'  => 'phone',
					'id'    => 'phone',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('phone', $user->phone),
				);
				
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('email', $user->email),
					); 
				
				$this->data['password'] = array(
					'name' => 'password',
					'id'   => 'password',
					'type' => 'password'
				);
				$this->data['password_confirm'] = array(
					'name' => 'password_confirm',
					'id'   => 'password_confirm',
					'type' => 'password'
				);
				
				$this->_render_page('diabetic_care/admins/edit_counsellor', $this->data);
				}
		}

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();
				
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				//pass the user to the view
				$this->data['user'] = $counsellor;
				
				$this->data['first_name'] = array(
						'name'  => 'first_name',
						'id'    => 'first_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('first_name', $counsellor['first_name']),
				);
				$this->data['last_name'] = array(
						'name'  => 'last_name',
						'id'    => 'last_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('last_name', $counsellor['last_name']),
				);
				
				$this->data['phone'] = array(
						'name'  => 'phone',
						'id'    => 'phone',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('phone', $counsellor['phone']),
				);
				
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('email', $counsellor['email']),
				);
				
				$this->data['password'] = array(
						'name' => 'password',
						'id'   => 'password',
						'type' => 'password'
				);

				$this->data['password_confirm'] = array(
						'name' => 'password_confirm',
						'id'   => 'password_confirm',
						'type' => 'password'
				);
				
				$this->_render_page('diabetic_care/admins/edit_counsellor', $this->data);
	}
	
	// --------------------------------------------------------------------

	 /**
	  * Helper : Listing all counsellors with edit option
	  *
	  * @author  Selva
	  *
	  * 
	  */

	   function pre_edit_counsellor()
	  {
	    //list the counsellors
		$this->data['counsellors'] = $this->diabetic_care_admin_portal_model->fetch_all_counsellors();
		$this->_render_page('diabetic_care/admins/pre_edit_counsellors', $this->data);
	  }
	  
	  // --------------------------------------------------------------------

	/**
	 * Helper : Listing all counsellors with delete option
	 *
	 * @author  Selva
	 *
	 * 
	 */

	 function pre_delete_counsellor()
	 {
	 	if (!$this->ion_auth->logged_in())
	 	{
			//redirect them to the login page
			redirect(URC.'diabetic_care_auth/login');
	 	}
		
	 	//list the counsellors
		$this->data['counsellors'] = $this->diabetic_care_admin_portal_model->fetch_all_counsellors();
		$this->_render_page('diabetic_care/admins/pre_delete_counsellors', $this->data);
	  }
	  
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing counsellor
	 *
	 * @param  string  $id  MongoID of the counsellor entry
	 *
	 * @author  Selva
	 * 
	 */

	function delete_counsellor($id)
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'diabetic_care_auth/login');
		}
		
		$this->diabetic_care_admin_portal_model->delete_counsellor($id);
		
		//list the counsellors
		$this->data['counsellors'] = $this->diabetic_care_admin_portal_model->fetch_all_counsellors();
		$this->data['message'] = "Counsellor Deleted";
		
		$this->_render_page('diabetic_care/admins/diabetic_care_counsellors', $this->data);
	
	}
	
	function update_profile() 
	{
	   $data = array(
	   'username'       => $this->input->post('name',TRUE),
	   'mobile_number'  => $this->input->post('mobile',TRUE),
	   'about_me'       => $this->input->post('about-me',TRUE));
	   
	   // Logged In Admin Details
	   $loggedinuser  = $this->session->userdata("customer");
	   $loggedemail   = $loggedinuser['email'];
	   
	   if(isset($_FILES))
	   {
         $this->load->library('upload');
	     $this->load->library('image_lib');
		 
		 if($_FILES['profile_file']['tmp_name']!='')
		 {
			  $uploaddir = PROFILEUPLOADFOLDER;
			  
			  if (!is_dir($uploaddir))
			  {
				 mkdir($uploaddir,0777,TRUE);
			  }
				  
			  /***** Profile Image *****/
			  $file = $uploaddir.$loggedemail.".png";
			  $path = ".".UPLOADFOLDER.'public/'.$loggedemail.".png";
				  
				  
			  if(move_uploaded_file($_FILES['profile_file']['tmp_name'], $file)) 
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
				  $config['height']           = 90;
				  $config['width']            = 90;
				  $CI->image_lib->initialize( $config );
				  $CI->image_lib->resize();
				  $CI->image_lib->clear();
			  }
			  
			  $data['profile_pic_path'] = $path;
		  }
		  
		}
		
		$this->diabetic_care_admin_portal_model->update_profile_data($data,$loggedemail);
		
		redirect('diabetic_care_auth/dashboard','refresh');
		
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing counsellor
	 *
	 * @param  string  $id  MongoID of the counsellor entry
	 *
	 * @author  Selva
	 * 
	 */
	 
	public function list_change_counsellor_requests()
	{
	  $this->data['message'] = "";
	  $this->_render_page('diabetic_care/admins/diabetic_care_change_counsellors', $this->data);
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing counsellor
	 *
	 * @param  string  $id  MongoID of the counsellor entry
	 *
	 * @author  Selva
	 * 
	 */
	 
	public function fetch_change_counsellor_requests($status)
	{
	  $chc_reqs = $this->diabetic_care_admin_portal_model->fetch_change_counsellor_requests_model($status);
	  if($chc_reqs)
	  {
        $this->output->set_output(json_encode($chc_reqs));
	  }
	  else
	  {
        $this->output->set_output('FALSE');
	  }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Detailed view of request entry
	 *
	 * @param  string  $request_id  MongoID of the counsellor entry
	 * @param  string  $patient_id  MongoID of the counsellor entry
	 *
	 * @author  Selva
	 * 
	 */
	 
	public function get_change_counsellor_request_entry_in_detail($request_id,$patient_id)
	{
	  $response = array();
	  
	  $chc_req_detail = $this->diabetic_care_admin_portal_model->get_change_counsellor_request_entry_in_detail_model($request_id);
	  
	  $patient_details = $this->diabetic_care_admin_portal_model->fetch_patient_by_id($patient_id);
	  
	  $counsellor_details = $this->diabetic_care_admin_portal_model->fetch_counsellor_by_email($patient_details['default_counsellor']);
	  
	  $other_counsellors = $this->diabetic_care_admin_portal_model->select_other_counsellors_excluding_existing($patient_details['default_counsellor']);
	   
	  $response['chc_details']        = $chc_req_detail;
	  $response['patient_details']    = $patient_details;
	  $response['counsellor_details'] = $counsellor_details;
	  $response['other_counsellors']  = $other_counsellors;
	  
	  $this->output->set_output(json_encode($response));
	 
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing counsellor
	 *
	 * @param  string  $id  MongoID of the counsellor entry
	 *
	 * @author  Selva
	 * 
	 */
	 
	public function reassign_counsellor()
	{
	  $existing_counsellor = $this->input->post('existing_counsellor',TRUE);
	  $new_counsellor      = $this->input->post('other_counsellors',TRUE);
	  $patient_id          = $this->input->post('patient_id',TRUE);
	  $chc_request_number  = $this->input->post('chc_request_number',TRUE);
	  
	  //$new_counsellor = $this->diabetic_care_admin_portal_model->select_new_random_counsellor_excluding_existing($existing_counsellor);
	  
	  $patient_data = $this->diabetic_care_admin_portal_model->fetch_patient_by_id($patient_id);
	  
	  $res = $this->diabetic_care_admin_portal_model->reassign_new_counsellor_to_patient($patient_id,$new_counsellor);
	  
	  if($res)
	  {
         $this->diabetic_care_admin_portal_model->add_counsellors_history($patient_id,$existing_counsellor);
		 
         // update request status as resolved 
		 $this->diabetic_care_admin_portal_model->update_chc_request_status($chc_request_number);
		 
		 // notifications
         $sms_message = "Dear ".$patient_data['name'].",
               This is in reference to your counsellor change request with change request number ".$chc_request_number." We would like to inform that as per your request,a new counsellor has been assigned to you.
               Regards
               Sugar365days Team";
			   
		$push_notification_message = "We would like to inform that as per your request,a new counsellor has been assigned to you ( Reference CHC number ".$chc_request_number." )";
			   
	     $to_counsellor_message = "This is in reference to the counsellor change request with change request number ".$chc_request_number.".I would like to inform you that as per the request,you have been dissociated as a counsellor to the patient ".$patient_data['name'].".
               Regards
               Sugar365days Admin";
		   
		// SMS NOTIFICATION TO CHANGE REQUEST OWNER ( PATIENT )
		$sms = $this->bhashsms->send_sms($patient_data['mobile']['mob_num'],$sms_message);
			
		// EMAIL NOTIFICATION TO CHANGE REQUEST OWNER ( PATIENT )
		$this->send_email_notification_to_patient($patient_data,$chc_request_number);
			
		$msg_id = get_unique_id();
		
		$counsellor_email = str_replace('@','#',$existing_counsellor);
		
		$patient_push_msg_col    = $patient_id.'_push_notifications';
		$counsellor_push_msg_col = $counsellor_email.'_push_notifications';
		
		$push_message = array();
		$push_message['message_header']  = "Counsellor Change Request Resolved";
		$push_message['message_content'] = $push_notification_message;
		
		// PUSH NOTIFICATION TO CHANGE REQUEST OWNER ( PATIENT )
		$this->diabetic_care_admin_portal_model->send_push_notification_to_patient($patient_push_msg_col,$msg_id,$push_message);
		
		$counsellor_push_message = array();
		$counsellor_push_message['message_header']  = "Counsellor Change Request";
		$counsellor_push_message['message_content'] = $to_counsellor_message;
		
		// PUSH NOTIFICATION TO COUNSELLOR
		$this->diabetic_care_admin_portal_model->send_push_notification_to_counsellor($counsellor_push_msg_col,$msg_id,$counsellor_push_message);
		
		redirect('diabetic_care_admin_portal/list_change_counsellor_requests');
	  }
	  else
	  {
  
	  }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Send email notification to ticket owner  
	 *	
	 *
	 * @author  Selva
	 */

     public function send_email_notification_to_patient($patient_data,$chc_request_number)
     {
	    //- - - - - EMAIL NOTIFICATION - - - - -//
		$fromaddress = $this->config->item('smtp_user');
		$this->email->set_newline("\r\n");
		$this->email->set_crlf('');
		$this->email->from($fromaddress,'Sugar365days Team');
		$this->email->to($patient_data['email']);
		$this->email->subject("Counsellor Changed");
		
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
						<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Dear Customer,</h3>
						<p class="lead" style="font-size:17px;">This is in reference to your counsellor change request with change request number '.$chc_request_number.' We would like to inform that as per your request,a new counsellor has been assigned to you.
					 
                     It is our privilege to have you as our valued customer.

                     Thanking and assuring you of our best services. <br>
					 Warm Regards,<br>
							Sugar365days Team</p>
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
		$this->email->send();
		$this->email->print_debugger();
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Change password
	 *
	 * @author  Ben
	 *
	 * 
	 */

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
			$this->_render_page('schoolhealth/sub_admins/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			
			log_message('debug','$identity=====782======'.print_r($identity,true));
			
			$change = $this->schoolhealth_sub_admin_portal_model->change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));
			
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
			
			//render
			$this->_render_page('schoolhealth/sub_admins/change_password', $this->data);
			}
		}
	}
}

/* End of file diabetic_care_lab_portal.php */
/* Location: ./application/customers/controllers/diabetic_care_lab_portal.php */