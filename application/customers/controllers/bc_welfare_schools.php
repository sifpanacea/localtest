<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Bc_welfare_schools extends My_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Vikas
	 *
	 * @return void
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		$this->upload_info = array();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('bhashsms');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->library('paas_common_lib');
		$this->load->library('bc_welfare_common_lib');
		$this->load->model('bc_welfare_cc_model');
		$this->load->model('bc_welfare_schools_common_model');
		$this->load->model('bc_welfare_common_model');
		$this->load->library('bc_welfare_schools_common_lib');
		$this->load->helper('paas'); 
	}
	
	// -------------------------------------------
	/**
	 * 
	 *
	 * @author  Vikas
	 *
	 *
	 */
	public function index()
	{
		redirect('bc_welfare_schools/to_dashboard');
	}
	
	public function initiate_request()
	{
		$this->data = "";
		$this->_render_page('bc_welfare_schools/application_initiate',$this->data);
	}
	
	public function access_request($id)
	{
		$this->data['doc_id'] = $id;
		$this->_render_page('bc_welfare_schools/hs_req_application_access',$this->data);
	}
	
	public function initiate_attendance()
	{
		$this->data = "";
		$this->_render_page('bc_welfare_schools/attendance_initiate',$this->data);
	}
	
	public function initiate_sanitation_report()
	{
		$this->data = "";
		$this->_render_page('bc_welfare_schools/sanitation_report',$this->data);
	}
	
	function list_chronic_cases()
	{
	  // School Code
	  $school_code = $this->get_my_school_code();
		
	  //Fetch school details with school code
	  $school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  $school_name = $school_info[0]['school_name'];
	  
	  $total_rows = $this->bc_welfare_schools_common_model->bc_welfare_chronic_cases_count($school_name);

	  //---pagination--------//
	  $config = $this->paas_common_lib->set_paginate_options($total_rows,5);

	  //Initialize the pagination class
	  $this->pagination->initialize($config);

	  //control of number page
	  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

	  //find all the categories with paginate and save it in array to past to the view
	  /*$this->data['cases'] =$this->bc_welfare_schools_common_model->get_chronic_cases_model($config['per_page'],$page,$school_name);*/

	  //create paginate´s links
	  $this->data['links'] = $this->pagination->create_links();

	  //number page variable
	  $this->data['page'] = $page;
	  
	  $this->data['case_count'] = $total_rows;
		
	  $this->data['message'] = '';
	  
	  $this->data['cases'] =$this->bc_welfare_schools_common_model->get_chronic_cases_model_for_data_table($school_name);
		
	  $this->_render_page('bc_welfare_schools/chronic_case_report',$this->data);
	}
	
	public function get_my_school_code()
	{
	    $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		return $school_code;
	}

	public function get_my_username()
	{
	    $logged_in_user = $this->session->userdata("customer");
		$username       = $logged_in_user['username'];
		return $username;
	}
	
	public function secure_file_download($path)
	{
		$path = str_replace('=','/',$path);
		$this->external_file_download($path);
	}
	
	function to_dashboard($date = FALSE, $request_duration = "Monthly", $screening_duration = "Yearly")
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard');
		$this->data = $this->bc_welfare_schools_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		$this->_render_page('bc_welfare_schools/school_dash', $this->data);
	
	}
	
	function to_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$screening_pie_span = $_POST['screening_pie_span'];
		log_message("debug","today_date============148".print_r($today_date,true));
		$this->data = $this->bc_welfare_schools_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span);
		$this->output->set_output($this->data);
	
	}
	
	function fetch_sanitation_report_against_date()
	{
	  // POST Data
	  $date = $this->input->post('date',TRUE);
	  
	  // School Code
	  $school_code = $this->get_my_school_code();
		
	  //Fetch school details with school code
	  $school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  $school_name = $school_info[0]['school_name'];
		
	  $sanitation_report_data = $this->bc_welfare_schools_common_model->get_sanitation_report_data_with_date($date,$school_name);
		
	  $sanitation_report = $this->bc_welfare_schools_common_lib->build_sanitation_report($sanitation_report_data);
		
	  if(isset($sanitation_report) && !empty($sanitation_report))
	  {
	      $this->output->set_output(json_encode($sanitation_report));
	  }
	  else
	  {
		  $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}
	
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date       = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		
		$this->data = $this->bc_welfare_schools_common_lib->update_request_pie($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->bc_welfare_schools_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		
		//Loggedinuser
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		
		//Fetch school details with school code
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		$this->bc_welfare_schools_common_model->update_screening_collection($today_date,$screening_pie_span,$school_name);
		$today_date = $this->bc_welfare_schools_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}

	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->bc_welfare_schools_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->bc_welfare_schools_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->bc_welfare_schools_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students']   = $get_docs;

		$this->data['navigation'] = $_POST['ehr_navigation'];

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('bc_welfare_schools/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		$docs = $this->bc_welfare_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		 
		$this->data['docscount'] = count($this->data['docs']);

		// username
		$username  = $this->get_my_username();
		$this->data['username'] = $username;
	
		$this->_render_page('bc_welfare_schools/bc_welfare_schools_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		$this->data['doc'] = $this->bc_welfare_schools_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('bc_welfare_schools/bc_welfare_reports_display_ehr',$this->data);
	}
	
	function drill_down_absent_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students');
		
		//POST Data
		$label      = $_POST['label'];
		$today_date = $_POST['today_date'];
		log_message("debug","labellllllllllllllll".print_r($label,true));
		log_message("debug","today_date".print_r($today_date,true));
		// Get my school code
		$school_code = $this->get_my_school_code();
		log_message("debug","school_code".print_r($school_code,true));
		$school_data = $this->bc_welfare_schools_common_model->get_school_details_for_school_code($school_code);
		
		$docs = $this->bc_welfare_schools_common_model->get_drilling_absent_students($label,$today_date,$school_data['school_name']);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		$temp = base64_decode($_GET['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_GET['ehr_data_for_absent']),true);
		
		$get_docs = $this->bc_welfare_schools_common_model->get_drilling_absent_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('bc_welfare_schools/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	function drill_down_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students');
		
		$request_label    = $_POST['data'];
		$today_date       = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
			
		// Loggedin user
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$district_code  = strtoupper($email_array[0]);
		$school_code    = (int) $email_array[1];
		
		// Unique ID pattern
		$id_pattern = $district_code."_".$school_code."_";
		
		//Fetch school details with school code
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		$docs = $this->bc_welfare_schools_common_model->get_drilling_request_students($request_label,$today_date,$request_pie_span,$school_name,$id_pattern);
		
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		$UI_id = json_decode(base64_decode($_GET['ehr_data_for_request']),true);
		//echo print_r($UI_id,true)."gggggggggggg";
		//exit();
		$get_docs = $this->bc_welfare_schools_common_model->get_drilling_request_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('bc_welfare_schools/drill_down_request_to_students_load_ehr',$this->data);
	}
	
	function drill_down_identifiers_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students');
		
		$identifier 	  = $_POST['identifier'];
		$today_date       = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		
		// Loggedin user
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$district_code  = strtoupper($email_array[0]);
		$school_code    = (int) $email_array[1];
		
		// Unique ID pattern
		$id_pattern = $district_code."_".$school_code."_";
		
		//Fetch school details with school code
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
	
		$docs = $this->bc_welfare_schools_common_model->get_drilling_identifiers_students($identifier,$today_date,$request_pie_span,$school_name,$id_pattern);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		$temp = base64_decode($_GET['ehr_data_for_identifiers']);
		$UI_id = json_decode(base64_decode($_GET['ehr_data_for_identifiers']),true);
		$get_docs = $this->bc_welfare_schools_common_model->get_drilling_identifiers_students_docs($UI_id);
		$this->data['students'] = $get_docs;
		log_message('debug','drill_down_identifiers_to_students_load_ehr===tsreis==410'.print_r($this->data['students'],true));
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('bc_welfare_schools/drill_down_identifiers_to_students_load_ehr',$this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get classes
	 *
	 *
	 * @author  Selva
	 */
	
	 public function classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('classes');
		
		// School Code
	    $school_code = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		
		$this->data = $this->bc_welfare_schools_common_lib->classes($school_code);
		$this->_render_page('bc_welfare_schools/classes',$this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Create class
	 *
	 *
	 * @author  Selva
	 */
	 
	public function create_class()
	{
		// School Code
	    $school_code = $this->get_my_school_code();
		
	 	$this->bc_welfare_schools_common_model->create_class($_POST, $school_code);	
	 	redirect('bc_welfare_schools/classes');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete class
	 *
	 *
	 * @author  Selva
	 */
	 
	public function delete_class($class_id)
	{
		// School Code
	    $school_code = $this->get_my_school_code();
		
	 	$this->bc_welfare_schools_common_model->delete_class($class_id, $school_code);	
	 	redirect('bc_welfare_schools/classes');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Get sections
	 *
	 *
	 * @author  Selva
	 */
	 
	public function sections()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('sections');
		
		// School Code
	    $school_code = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		
        $this->data = $this->bc_welfare_schools_common_lib->sections($school_code);
		$this->_render_page('bc_welfare_schools/sections',$this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Create section
	 *
	 *
	 * @author  Selva
	 */
	 
	public function create_section()
	{
		// School Code
	    $school_code = $this->get_my_school_code();
		
	 	$this->bc_welfare_schools_common_model->create_section($_POST, $school_code);
	 	redirect('bc_welfare_schools/sections');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Delete section
	 *
	 *
	 * @author  Selva
	 */
	 
	public function delete_section($section_id)
	{
	  // School Code
	  $school_code = $this->get_my_school_code();
		
	  $this->bc_welfare_schools_common_model->delete_section($section_id, $school_code);	
	  redirect('bc_welfare_schools/sections');
	}
	
	public function create_student()
	{
		$school_code  = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);

		$district_id = $school_info[0]['dt_name'];
		$school_name = $school_info[0]['school_name'];
		$dist_info = $this->bc_welfare_schools_common_model->get_district($district_id);
		$district_code = $dist_info[0]['dt_code'];

		$get_district_name = explode(",",$school_name);
		$district = $get_district_name[1];
		
		$classes     = $this->bc_welfare_schools_common_model->get_all_classes("All",$school_code);
		$sections    = $this->bc_welfare_schools_common_model->get_all_sections("All",$school_code);
		//$hunique_id  = $this->bc_welfare_schools_common_model->generate_new_student_hunique_id($school_code,$district_code,$school_name);
		$hunique_id  = $this->tswreis_schools_common_model->generate_new_uniqueid_for_new_student_by_checking_all_collection($school_code,$district_code,$school_name);
		
		$this->data['school_name']   = $school_name;
		$this->data['district']      = $district;
		$this->data['classes']       = $classes;
		$this->data['sections']      = $sections;
		$this->data['huniqueid']     = $hunique_id;
		$this->data['message']       = '';
		
		$this->_render_page('bc_welfare_schools/create_student',$this->data);
		
	}
	
	public function create_staff()
	{
		$school_code  = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		
		$district_id   = $school_info[0]['dt_name'];
		$school_name   = $school_info[0]['school_name'];
		$dist_info     = $this->bc_welfare_schools_common_model->get_district($district_id);
		$district_code = $dist_info[0]['dt_code'];
		
		$hunique_id  = $this->bc_welfare_schools_common_model->generate_new_staff_hunique_id($school_code,$district_code,$school_name);
		
		$this->data['school_name']   = $school_name;
		$this->data['huniqueid']     = $hunique_id;
		$this->data['message']       = '';
		
		$this->_render_page('bc_welfare_schools/create_staff',$this->data);
		
	}
	
	public function add_student_ehr()
	{
	  // Variables
	  $photo_obj = array();
	  
	  // Session data
	  $school_code  = $this->get_my_school_code();
	  $school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  $district_id = $school_info[0]['dt_name'];
	  $school      = $school_info[0]['school_name'];
	  
	  // District data
	  $dist_info = $this->bc_welfare_schools_common_model->get_district($district_id);
	  $district_code = $dist_info[0]['dt_code'];
	  $district_name = Strtoupper($dist_info[0]['dt_name']);

	  $get_district_name = explode(",",$school);
		$district_name = $get_district_name[1];
	  
	  // POST DATA
	  $student_name = $this->input->post('name',TRUE);
	  $admission_no = $this->input->post('ad_no',TRUE);
	  $student_mob  = $this->input->post('mobile',TRUE);
	  $student_dob  = $this->input->post('date_of_birth',TRUE);
	  $helath_unique_id = $this->input->post('helath_unique_id',TRUE);
	  $father_name  = $this->input->post('father_name',TRUE);
	  $class        = $this->input->post('class',TRUE);
	  $section      = $this->input->post('section',TRUE);
	  $school_name  = $this->input->post('school_name',TRUE); 
	  
	  // Form EHR Document
	  $doc_data = array();
	  $doc_data['page1']['Personal Information']  = array();
	  $doc_data['page2']['Personal Information']  = array();
	  $doc_data['page3'] = array();
	  $doc_data['page4'] = array();
	  $doc_data['page5'] = array();
	  $doc_data['page6'] = array();
	  $doc_data['page7'] = array(); 
	  $doc_data['page8'] = array();
	  $doc_data['page9'] = array();
	  
	  // Page 1
	  $doc_data['page1']['Personal Information']['Name']             = $student_name;
	  $doc_data['page1']['Personal Information']['Mobile']           = array("country_code"=>"91","mob_num"=>$student_mob);
	  $doc_data['page1']['Personal Information']['Date of Birth']    = $student_dob;
	  $doc_data['page1']['Personal Information']['Hospital Unique ID'] = $helath_unique_id;
	  $doc_data['page1']['Personal Information']['Photo']            = "";
	  
	  // Page 2
	  $doc_data['page2']['Personal Information']['AD No']            = $admission_no;
	  $doc_data['page2']['Personal Information']['District']         = $district_name;
	  $doc_data['page2']['Personal Information']['School Name']      = $school_name;
	  $doc_data['page2']['Personal Information']['Class'] 			 = $class;
	  $doc_data['page2']['Personal Information']['Section']          = $section;
	  $doc_data['page2']['Personal Information']['Father Name']      = $father_name;
	  $doc_data['page2']['Personal Information']['Date of Exam']     = "";
	  
	  if(isset($_FILES) && !empty($_FILES))
	  {
           $this->load->library('upload');
		   
	       $config = array();
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201812217594045_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201812217594045_con/photo/",0777,TRUE);
		   }
		   
		   // Student Photo
		   foreach ($_FILES as $index => $value)
		   {
			$this->upload->initialize($config);
			 if ( ! $this->upload->do_upload($index))
			 {
				//echo "file upload failed";
				//return FALSE;
				 $doc_data['page1']['Personal Information']['Photo'] = "";
			 }
			 else
			 {
				$photo_obj = $this->upload->data();
				 $photo_ele = array(
					"file_client_name"    => $photo_obj['client_name'],
					"file_encrypted_name" => $photo_obj['file_name'],
					"file_path" 		  => $photo_obj['file_relative_path'],
					"file_size" 		  => $photo_obj['file_size']
				);
		   
		   $doc_data['page1']['Personal Information']['Photo'] = $photo_ele;
			 }
			 
		   }
		   
		  /* $photo_ele = array(
					"file_client_name"    => $photo_obj['client_name'],
					"file_encrypted_name" => $photo_obj['file_name'],
					"file_path" 		  => $photo_obj['file_relative_path'],
					"file_size" 		  => $photo_obj['file_size']
		  );
		   
		   $doc_data['page1']['Personal Information']['Photo'] = $photo_ele;*/
	  }
			//$doc_data['doc_data'] = $doc_data;
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 1;
		$doc_properties['doc_owner'] = "BC Welfare";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
		
		//$doc_properties['doc_properties'] = $doc_properties;
		//$doc_data['doc_properties'] = $doc_data_properties;

		$session_data = $this->session->userdata("customer");
		$email_id = $session_data['email'];
		
		$email = str_replace("@","#",$email_id);
		// History
		  $approval_data = array(
              "current_stage" => "stage1",
              "approval" => "true",
              "submitted_by" => $email,
              "time" => date('Y-m-d H:i:s'));

          //array_push($approval_history,$approval_data);

          $history['last_stage'] = $approval_data;
		
	  // History
	 /* $history = array();
	  $history_entry = array('time'=>date('Y-m-d H:i:s'),'submitted_by'=>'','approval'=>"true","stage_name"=>"");
	  array_push($history,$history_entry);*/
	  
	  
	  $added = $this->bc_welfare_schools_common_model->add_student_ehr_model($doc_data,$history,$doc_properties); 
	  
	  if($added)
	  {
        $classes  = $this->bc_welfare_schools_common_model->get_all_classes("All",$school_code);
		$sections = $this->bc_welfare_schools_common_model->get_all_sections("All",$school_code);
		$hunique_id = $this->bc_welfare_schools_common_model->generate_new_student_hunique_id($school_code,$district_code,$school_name);
		$this->data['school_name']   = $school_name;
		$this->data['district']   = $district_name;
		$this->data['classes']   = $classes;
		$this->data['sections']  = $sections;
		$this->data['huniqueid'] = $hunique_id;
		$this->data['message']  = "EHR added successfully";
		$this->_render_page('bc_welfare_schools/create_student',$this->data);
	  }
	  else
	  {
        $classes  = $this->bc_welfare_schools_common_model->get_all_classes("All");
		$sections = $this->bc_welfare_schools_common_model->get_all_sections("All");
		$hunique_id = $this->bc_welfare_schools_common_model->generate_new_student_hunique_id($school_code,$district_code,$school_name);
		$this->data['classes']   = $classes;
		$this->data['sections']  = $sections;
		$this->data['huniqueid'] = $hunique_id;
		$this->data['message']  = "Failed. Try Again !";
		$this->_render_page('bc_welfare_schools/create_student',$this->data);
	  }
	 
	}
	
	public function add_staff_ehr()
	{
	  // Variables
	  $photo_obj = array();
	  
	  // School Data
	  $school_code  = $this->get_my_school_code();
	  $school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  $district_id = $school_info[0]['dt_name'];
	  $school_name = $school_info[0]['school_name'];
	  
	  // District Data
	  $dist_info = $this->bc_welfare_schools_common_model->get_district($district_id);
	  $district_code = $dist_info[0]['dt_code'];
	  $district_name = Strtoupper($dist_info[0]['dt_name']);
	 
	  // POST DATA
	  $staff_name   = $this->input->post('name',TRUE);
	  $staff_mob    = $this->input->post('mobile',TRUE);
	  $staff_dob    = $this->input->post('date_of_birth',TRUE);
	  $helath_unique_id = $this->input->post('helath_unique_id',TRUE);
	  $father_name  = $this->input->post('father_name',TRUE);
  
	  // Form EHR Document
	  $doc_data = array();
	  $doc_data['page1']['Personal Information'] = array();
	  $doc_data['page2']['Personal Information']  = array();
	  $doc_data['page3'] = array();
	  $doc_data['page4'] = array();
	  $doc_data['page5'] = array();
	  $doc_data['page6'] = array();
	  $doc_data['page7'] = array(); 
	  $doc_data['page8'] = array();
	  $doc_data['page9'] = array();
	  
	  // Page 1
	  $doc_data['page1']['Personal Information']['Name']             = $staff_name;
	  $doc_data['page1']['Personal Information']['Mobile']           = array("country_code"=>"91","mob_num"=>$staff_mob);
	  $doc_data['page1']['Personal Information']['Date of Birth']    = $staff_dob;
	  $doc_data['page1']['Personal Information']['Hospital Unique ID'] = $helath_unique_id;
	  $doc_data['page1']['Personal Information']['Photo']            = "";
	  
	  // Page 2
	  $doc_data['page2']['Personal Information']['AD No']            = "";
	  $doc_data['page2']['Personal Information']['District']         = $district_name;
	  $doc_data['page2']['Personal Information']['School Name']      = $school_name;
	  $doc_data['page2']['Personal Information']['Class'] 			 = "";
	  $doc_data['page2']['Personal Information']['Section']          = "";
	  $doc_data['page2']['Personal Information']['Father Name']      = $father_name;
	  $doc_data['page2']['Personal Information']['Date of Exam']     = "";
	  
	  if(isset($_FILES) && !empty($_FILES))
	  {
           $this->load->library('upload');
		   
	       $config = array();
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201812217594045_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201812217594045_con/photo/",0777,TRUE);
		   }
		   
		   // Student Photo
		   foreach ($_FILES as $index => $value)
		   {
			 $this->upload->initialize($config);
			 if ( ! $this->upload->do_upload($index))
			 {
				echo "file upload failed";
				return FALSE;
			 }
			 else
			 {
				$photo_obj = $this->upload->data();
			 }
			 
		   }
		   
		   $photo_ele = array(
					"file_client_name"    => $photo_obj['client_name'],
					"file_encrypted_name" => $photo_obj['file_name'],
					"file_path" 		  => $photo_obj['file_relative_path'],
					"file_size" 		  => $photo_obj['file_size']
		  );
		   
		   $doc_data['page1']['Personal Information']['Photo'] = $photo_ele;
	  }
	  
	  // History
	  $history = array();
	  $history_entry = array('time'=>date('Y-m-d H:i:s'),'submitted_by'=>'','approval'=>"true","stage_name"=>"");
	  array_push($history,$history_entry);
	  
	  $added = $this->bc_welfare_schools_common_model->add_staff_ehr_model($doc_data,$history); 
	  
	  if($added)
	  {
		$hunique_id = $this->bc_welfare_schools_common_model->generate_new_staff_hunique_id($school_code,$district_code,$school_name);
		$this->data['school_name']   = $school_name;
		$this->data['huniqueid']     = $hunique_id;
		$this->data['message']  = "EHR added successfully";
		$this->_render_page('bc_welfare_schools/create_staff',$this->data);
	  }
	  else
	  {
		$hunique_id = $this->bc_welfare_schools_common_model->generate_new_staff_hunique_id($school_code,$district_code,$school_name);
		$this->data['huniqueid'] = $hunique_id;
		$this->data['message']   = "Failed. Try Again !";
		$this->_render_page('bc_welfare_schools/create_staff',$this->data);
	  }
	 
	}
	
	public function student_reports()
	{
		$this->check_for_admin();
		$this->check_for_plan('student_reports');
		
		$per_page = ""; 
		$page     = ""; 
		$school_code = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
        $school_name = $school_info[0]['school_name'];
		
		$this->data['students'] = $this->bc_welfare_schools_common_model->get_student($per_page,$page,$school_name);
		$this->data['studentscount'] = $this->bc_welfare_schools_common_model->studentscount($school_name);
		$this->data['classlist'] = $this->bc_welfare_schools_common_model->get_classes($per_page, $page, $school_code);
		$this->_render_page('bc_welfare_schools/student_reports',$this->data);
	}
	
	public function get_students_list_by_class()
	{
		// POST Data
		$selected_class = $this->input->post('selected_class',TRUE);
		$selected_class = (int) $selected_class;
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$students = $this->bc_welfare_schools_common_model->get_students_by_class($selected_class, $school_name);
		$this->output->set_output(json_encode($students));
	}
	
	public function reports_ehr()
	{
	    $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->_render_page('bc_welfare_schools/reports_ehr',$this->data);
	}
	
	public function reports_display_ehr_uid()
	{
		$student_unique_id = $_POST['student_unique_id'];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$logged_in_user = $this->session->userdata("customer");
		$username       = $logged_in_user['username'];

        $this->data = $this->bc_welfare_schools_common_lib->reports_display_ehr_uid($student_unique_id,$school_name);
		
		$this->data["username"] = $username.','.$school_name;
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		
		$this->_render_page('bc_welfare_schools/reports_display_ehr',$this->data);
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
			redirect(URC.'auth/login');
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
			$this->_render_page('bc_welfare_schools/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			
			log_message('debug','$identity=====782======'.print_r($identity,true));
			
			$change = $this->bc_welfare_schools_common_model->change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));
			
			log_message('debug','$identity=====786======'.print_r($change,true));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('auth/logout');
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
			$this->_render_page('bc_welfare_schools/change_password', $this->data);
			}
		}
	}
	
	public function staff_reports()
	{
		$this->check_for_admin();
		$this->check_for_plan('staff_reports');
		$this->data = "";
        //$this->data = $this->bc_welfare_schools_common_model->staff_reports();
		$this->_render_page('bc_welfare_schools/staff_reports',$this->data);
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Create Schedule Follow up
	 *
	 * @author Selva
	 *
	 * 
	 */
	 
	function create_schedule_followup()
	{
	   // Variables
	   $monthNames = array();
	   
	  // POST Data
	  $unique_id           = $this->input->post('unique_id',TRUE);
	  $case_id             = $this->input->post('case_id',TRUE);
	  $medication_schedule = $this->input->post('medication_schedule',TRUE);
	  //$start_date    	   = $this->input->post('start_date',TRUE);
	  $start_date    	   = date("Y-m-d");
	  $treatment_period    = $this->input->post('treatment_period',TRUE);
	  
	  $end_date = date('Y-m-d',strtotime('+'.$treatment_period.' days',strtotime($start_date)));
		
	  $begin = new DateTime($start_date);
	  $end   = new DateTime($end_date);
	  $interval = DateInterval::createFromDateString('1 month');
	  $months   = new DatePeriod($begin, $interval, $end);
	  
	  foreach ($months as $dt) 
	  {
		$dateObj   = DateTime::createFromFormat('!m Y', $dt->format("m Y"));
		$monthName = $dateObj->format('F-Y'); 
		array_push($monthNames,$monthName);
	  }
		
	  log_message('debug','bc_welfare_SCHOOLS=====CREATE_SCHEDULE_FOLLOWUP=====$_POST==>'.print_r($_POST,true));
	  
	  $is_created = $this->bc_welfare_schools_common_model->create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames,$case_id);
	  
	  if($is_created)
	  {
        $this->output->set_output('SCHEDULE_CREATION_COMPLETED');
	  }
	  else
	  {
        $this->output->set_output('SCHEDULE_CREATION_FAILED');
	  }
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Update Schedule Follow up
	 *
	 * @author Selva
	 * 
	 */
	 
	function update_schedule_followup()
	{
	  // POST Data
	  $unique_id        = $this->input->post('unique_id',TRUE);
	  $case_id          = $this->input->post('case_id',TRUE);
	  $medication_taken = $this->input->post('medication_taken',TRUE);
	  //$selected_date    = $this->input->post('selected_date',TRUE);
	  $selected_date    = date("Y-m-d");
	  
	  //log_message('debug','update_schedule_followup==1='.print_r($_POST,true));
	  //log_message('debug','update_schedule_followup==2='.print_r($medication_taken,true));
	  
	  $compliance = $this->bc_welfare_schools_common_model->calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken);
	  
	  //log_message('debug','update_schedule_followup==3='.print_r($compliance,true));
	  
	  $is_updated = $this->bc_welfare_schools_common_model->update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date);
	  
	  if($is_updated=="ALREADY_UPDATED")
	  {
        $this->output->set_output('SCHEDULE_ALREADY_UPDATED');
	  }
	  else if($is_updated=="UPDATE_SUCCESS")
	  {
        $this->output->set_output('SCHEDULE_UPDATE_COMPLETED');
	  }
	  else
	  {
        $this->output->set_output('SCHEDULE_UPDATE_FAILED');
	  }
	
	}
	
	function prepare_pill_compliance_monthly_graph()
	{
	  // POST DATA
	  $student_unique_id = $this->input->post('unique_id',TRUE);
	  $case_id           = $this->input->post('case_id',TRUE);
	  $begin           	 = $this->input->post('begin',TRUE);
	  $end               = $this->input->post('end',TRUE);
	  
	  $start_date_array  = explode('-',$begin);
	  $new_start_d = $start_date_array[0]."-".$start_date_array[1]; 
	  $new_start_date = new DateTime($begin);
	  $new_start_date = $new_start_date->getTimestamp()*1000;
	  
	  $end_date_array  = explode('-',$end);
	  $new_end_d = $end_date_array[0]."-".$end_date_array[1]; 
	  $new_end_date = new DateTime($end);
	  $new_end_date = $new_end_date->getTimestamp()*1000;
	  
	  // Variables
	  $final_graph_data  = array();
	  $graph_data        = array();
		
	  $pcompl_raw_data   = $this->bc_welfare_schools_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
	 if(isset($pcompl_raw_data) && !empty($pcompl_raw_data))
	 {
       foreach($pcompl_raw_data as $data)
	   {
	      if(isset($data['medication_taken']) && !empty($data['medication_taken']))
		  {
	      $pill_comp_data = $data['medication_taken'];
		  $schedule       = $data['medication_schedule'];
		  $start_date     = $data['start_date'];
		  $duration       = $data['treatment_period'];
		  $end_date       = date('Y-m-d',strtotime('+'.$duration.' days',strtotime($start_date)));
		  
		  foreach($pill_comp_data as $index => $values)
	      {
		       $schedule_date  = $values['date'];
			   
			   $date_array  = explode('-',$schedule_date);
			   $new_entry_d = $date_array[0]."-".$date_array[1]; 
			
			   $date = new DateTime($schedule_date);
			   $new_date = $date->getTimestamp()*1000;
			   
			   $pre_temp = array($new_date,(int) $values['compliance']);
			   array_push($graph_data,$pre_temp);
		       
		  }
	  
	   $new_schedule = array();
	   $replacements = array(
		    'mor'   => 'Morning',
		    'noon'  => 'Afternoon',
		    'night' => 'Night'
		);
		foreach ($schedule as $key => $value) {
		    if (isset($replacements[$value])) {
		        $new_schedule[$key] = $replacements[$value];
		    }
		}

	   $final_graph_data['start_date']  = $new_start_date;
	   $final_graph_data['end_date']    = $new_end_date;
	   $final_graph_data['graph_data']  = $graph_data;
	   $final_graph_data['schedule']    = implode(",",$new_schedule);
	   
	    log_message('debug','update_compliance_percentage_model=====1='.print_r($final_graph_data,true));
	   
	   $this->output->set_output(json_encode($final_graph_data));
	    }
		else
		{
	       $this->output->set_output('');
		}
	 }
	 }
	 
	}
	
	function prepare_pill_compliance_overall_graph()
	{
	  // POST DATA
	  $student_unique_id = $this->input->post('unique_id',TRUE);
	  $case_id           = $this->input->post('case_id',TRUE);
	  
	  // Variables
	  $final_graph_data  = array();
	  $graph_data        = array();
	  $month_wise_compliance = array();
		
	  $pcompl_raw_data   = $this->bc_welfare_schools_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
	 if(isset($pcompl_raw_data) && !empty($pcompl_raw_data))
	 {
       foreach($pcompl_raw_data as $data)
	   {
	      if(isset($data['medication_taken']) && !empty($data['medication_taken']))
		  {
	      $pill_comp_data = $data['medication_taken'];
		  $schedule       = $data['medication_schedule'];
		  $scheduled_months = $data['scheduled_months'];
		  $start_date     = $data['start_date'];
		  $duration       = $data['treatment_period'];
		  $end_date       = date('Y-m-d',strtotime('+'.$duration.' days',strtotime($start_date)));
		  
		  $start_date_array  = explode('-',$start_date);
		  $new_start_d = $start_date_array[0]."-".$start_date_array[1]; 
		  $new_start_date = new DateTime($new_start_d);
		  $new_start_date = $new_start_date->getTimestamp()*1000;
		  
		  $end_date_array  = explode('-',$end_date);
		  $new_end_d = $end_date_array[0]."-".$end_date_array[1]; 
		  $new_end_date = new DateTime($new_end_d);
		  $new_end_date = $new_end_date->getTimestamp()*1000;
		  
		  foreach($scheduled_months as $index => $month_name)
		  {
			  $date_details         = explode("-",$month_name);
			  $first_date_format    = $date_details[0]." 01 ,".$date_details[1];
			  $first_day_this_month = date('Y-m-01',strtotime($first_date_format)); // hard-coded '01' for first day
			  
			  $start_date_array  = explode('-',$first_day_this_month);
			  $new_start_d = $start_date_array[0]."-".$start_date_array[1]; 
			  $new_start_ = new DateTime($new_start_d);
			  $month_start = $new_start_->getTimestamp()*1000;
			  ${$month_name."compliance_value"}  = 0;
			  
		      foreach($pill_comp_data as $index_ => $values)
			  {
				   $schedule_date  = $values['date'];
				   $monName = date('F', strtotime($schedule_date));
				   $monDays = date('t', strtotime($schedule_date));
				   
				   if($date_details[0] === $monName)
				   {
					 ${$month_name."compliance_value"}+= (int) $values['compliance'];
				   }
			  }
			  
			  $percent = ${$month_name."compliance_value"}/$monDays;
			  
			  $pre_temp = array($month_start,(int) $percent);
			  array_push($month_wise_compliance,$pre_temp);
		  }

	   $new_schedule = array();
	   $replacements = array(
		    'mor'   => 'Morning',
		    'noon'  => 'Afternoon',
		    'night' => 'Night'
		);
		foreach ($schedule as $key => $value) {
		    if (isset($replacements[$value])) {
		        $new_schedule[$key] = $replacements[$value];
		    }
		}
	  
	   $final_graph_data['start_date']  = $new_start_date;
	   $final_graph_data['end_date']    = $new_end_date;
	   $final_graph_data['graph_data']  = $month_wise_compliance;
	   $final_graph_data['schedule']    = implode(",",$new_schedule);
	   
	    log_message('debug','prepare_pill_compliance_overall_graph=====6='.print_r($final_graph_data,true));
	   
	   $this->output->set_output(json_encode($final_graph_data));
	    }
		else
		{
	       $this->output->set_output('');
		}
	 }
	 
	 }
	}

	 public function post_note() 
	 {
		
		$post = $_POST;
		
		$token = $this->bc_welfare_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() 
	{
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->bc_welfare_common_lib->delete_ehr_note($doc_id);
	   
		$this->output->set_output($token);
    }


	 public function post_note_request() 
	 {
		$post = $_POST;
		
		$token = $this->bc_welfare_common_lib->insert_request_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function extend_request() 
	{
		
		$this->data['requests'] = $this->bc_welfare_common_model->get_all_rised_req();
		$this->data['message'] = false;
		
		$this->_render_page('bc_welfare_schools/extend_request',$this->data);
	}
	
	public function app_access($doc_id)
	{

		$this->data = $this->bc_welfare_common_lib->app_access($doc_id);
		$this->_render_page('bc_welfare_schools/extend_request_app',$this->data);
	}
	
	function hs_req_extend(){
		
		$form_data = json_decode($_POST['form_data'],true);
		
		$req_return = $this->bc_welfare_common_lib->hs_req_extend($form_data);
		
		if($req_return['return_error']){
			$this->data ['message'] = $req_return['message'];
			$this->_render_page('bc_welfare_schools/extend_request',$this->data);
		}else{
			redirect('bc_welfare_schools/extend_request');
			//$this->manage_news_feed_view();
		}
	}
	/*
	*BMI App
	*author Naresh view page
	
	*/ 
	public function feed_bmi_student() 
	 {
		 $this->data = "";
		//$this->data['message'] = $this->session->flashdata('message');
		$this->_render_page('bc_welfare_schools/school_wise_feed_bmi',$this->data);
	}
	
	/*
	*BMI show bmi graph
	*author Naresh
	
	*/ 
	public function show_bmi_student() 
	 {
		
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->_render_page('bc_welfare_schools/school_wise_show_bmi_graph',$this->data);
	}
	/*
	*BMI Ajax function
	*author Naresh
	
	*/ 
	public function student_bmi_graph(){
		
		$month_wise_bmi  = array();
		$month_wise_data = array();
		$final_bmi_data  = array();
		$temp 	 		 = array();
		$unique_id = $_POST['uid'];
		
		$bmi_value = $this->bc_welfare_schools_common_model->get_student_bmi_values($unique_id);
		
		if(isset($bmi_value) && !empty($bmi_value))
		{
		
		foreach($bmi_value as $bmi)
		{
			log_message("debug","bmi1339".print_r($bmi,true));
			if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']))
		  {
			  foreach($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values'] as $bmi_data )
			  {
				  $bmi    = $bmi_data['bmi'];
				  $date   = $bmi_data['month'];
				  $height = $bmi_data['height'];
				  $weight = $bmi_data['weight'];
				  
				  
				  log_message("debug","bmi_isssssss1339".print_r($bmi,true));
				  $new_start_ = new DateTime($date);
				  $month_start = $new_start_->getTimestamp()*1000;
				  $pre_temp = array($month_start,(int) $bmi);
				  
				  array_push($month_wise_bmi,$pre_temp);
				 
				  $temp_data = array();
				  $temp_data['height'] = $height;
				  $temp_data['weight'] = $weight;
				  $temp[$month_start]  = $temp_data;
				  
			  }
			  
		  }
			
		}

        array_push($month_wise_data,$temp);
		arsort($month_wise_bmi);
		$month_wise_bmi  = array_values($month_wise_bmi);
		$final_bmi_data['graph_data'] = $month_wise_bmi;
		$final_bmi_data['month_data'] = $month_wise_data;
		
		$this->output->set_output(json_encode($final_bmi_data));
		}
		else
		{
			$this->output->set_output('NO_GRAPH');
		}
		
		
	}
	
	//Update Personal Information
	//author Naresh
	public function bc_welfare_update_ehr()
	{
		 $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->data["message"] = "";		
		$this->_render_page('bc_welfare_schools/bc_welfare_update_personal_info',$this->data);
	}
	
	public function bc_welfare_update_personal_ehr_uid()
	{
		$post = $_POST['student_unique_id'];
		$this->data = $this->bc_welfare_schools_common_lib->bc_welfare_update_personal_ehr_uid($post);
		
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('bc_welfare_schools/bc_welfare_update_personal_ehr',$this->data);
		//log_message("debug","bc_welfare_update_personal_ehr_uid======1415".print_r($this->data,true));
	}
	
	public function update_student_ehr()
	{
		 // Variables
	  $photo_obj = array();
	 
	  
	   //log_message("debug","student nameeeeee".print_r($_POST,true));
	 
	  // POST DATA
	  $student_name = $this->input->post('name',TRUE);
	  $student_mob  = $this->input->post('mobile',TRUE);
	  $student_dob  = $this->input->post('date_of_birth',TRUE);
	  $father_name  = $this->input->post('father_name',TRUE);
	  $class        = $this->input->post('class',TRUE);
	  $section      = $this->input->post('section',TRUE);
	  $unique_id    = $this->input->post('unique_id',TRUE);
	  //log_message("debug","unique id ddddd1434".print_r($unique_id,true));
	  
	  $update_profile = array(
	   'doc_data.widget_data.page1.Personal Information.Name'           => $student_name,
	   'doc_data.widget_data.page1.Personal Information.Mobile.mob_num' => $student_mob,
	   'doc_data.widget_data.page1.Personal Information.Date of Birth'  => $student_dob,
	   'doc_data.widget_data.page2.Personal Information.Class'          => $class,
	   'doc_data.widget_data.page2.Personal Information.Section'        => $section,
	   'doc_data.widget_data.page2.Personal Information.Father Name'    => $father_name);

	  if(isset($_FILES) && !empty($_FILES))
	  {
           $this->load->library('upload');
		   
	       $config = array();
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201812217594045_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201812217594045_con/photo/",0777,TRUE);
		   }
		   
		   // Student Photo
		   foreach ($_FILES as $index => $value)
		   {
			   //log_message('debug',"index indexx=========1852".print_r($index,true));
			   //log_message('debug',"values value==========1853".print_r($value,true));
			 $this->upload->initialize($config);
			 if(!empty($value['name']))
			 {
			 if ( ! $this->upload->do_upload($index))
			 {
				echo "file upload failed";
				return FALSE;
			 }
			 else
			 {
				$photo_obj = $this->upload->data();
			 }
			 
			 $photo_ele = array(
					"file_client_name"    => $photo_obj['client_name'],
					"file_encrypted_name" => $photo_obj['file_name'],
					"file_path" 		  => $photo_obj['file_relative_path'],
					"file_size" 		  => $photo_obj['file_size']
		  );
		   //log_message("debug","photoooooooooo".print_r($photo_ele,true));
		   
		   $update_profile['doc_data.widget_data.page1.Personal Information.Photo']= $photo_ele;
			 
		   }
		   
		   
		  // $doc_data['doc_data']['widget_data']['page1']['Personal Information']['Photo'] = $photo_ele;
	  }
	  }
	  
	  //log_message("debug","doc_datavvvvvvvv1496".print_r($update_profile,true));
	  $ehr_update = $this->bc_welfare_schools_common_model->update_student_ehr_model($unique_id,$update_profile); 
	  //log_message("debug","photo_updateqwerty".print_r($ehr_update,true));
	  $this->data['message'] = 'Updated Successfully';

	   $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->data["message"] = "";
	  
	 $this->_render_page('bc_welfare_schools/bc_welfare_update_personal_info',$this->data);
	}
	
	/*
	* author Suman Reddy
	*/
	public function contact_us()
	{
		$this->data['message'] = "";
		$this->_render_page("bc_welfare_schools/show_contact_us",$this->data);
	}

	/*
	 | -------------------------------------------------------------------------
	 | Attendance Report Related Functionalties(create).bc_welfare_schools
	 | -------------------------------------------------------------------------
	 */
	
	public function initiateAttendanceReport()
	{
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$dist = explode(',', $school_name);
		$districtName = $dist[1];

		$this->data['schoolName'] = $school_name;
		$this->data['districtName'] = $districtName;

		$this->_render_page('bc_welfare_schools/attendance_report_view', $this->data);
	}

	public function create_attendence_report()
	{

		// Form data
		$doc_data = array();
		$doc_data['page1']['Attendence Details'] = array();
		$doc_data['page2']['Attendence Details'] = array();

		$district 			= $this->input->post('page1_AttendenceDetails_District',true);
		$school 			= $this->input->post('page1_AttendenceDetails_SelectSchool',true);

		$present_students 	= $this->input->post('page1_AttendenceDetails_Attended',true);

		$sick_students 		= $this->input->post('page1_AttendenceDetails_Sick',true);
		$sick_ids 			= $this->input->post('page1_AttendenceDetails_SickUID',true);

		$rtoh_students		= $this->input->post('page1_AttendenceDetails_R2H',true);
		$rtoh_ids		    = $this->input->post('page1_AttendenceDetails_R2HUID',true);

		$absent_students	= $this->input->post('page1_AttendenceDetails_Absent',true);
		$absent_ids	        = $this->input->post('page2_AttendenceDetails_AbsentUID',true);

		$rest_room_students = $this->input->post('page2_AttendenceDetails_RestRoom',true);
		$rest_room_ids	    = $this->input->post('page2_AttendenceDetails_RestRoomUID',true);

		$doc_data['page1']['Attendence Details']['District'] 		= $district;
		$doc_data['page1']['Attendence Details']['Select School'] 	= $school;
		$doc_data['page1']['Attendence Details']['Attended'] 		= $present_students;
		$doc_data['page1']['Attendence Details']['Sick'] 			= $sick_students;
		$doc_data['page1']['Attendence Details']['Sick UID'] 		= $sick_ids;
		$doc_data['page1']['Attendence Details']['R2H'] 			= $rtoh_students;
		$doc_data['page1']['Attendence Details']['R2H UID']		    = $rtoh_ids;
		$doc_data['page1']['Attendence Details']['Absent'] 			= $absent_students;
		$doc_data['page2']['Attendence Details']['Absent UID'] 		= $absent_ids;
		$doc_data['page2']['Attendence Details']['RestRoom'] 		= $rest_room_students;
		$doc_data['page2']['Attendence Details']['RestRoom UID'] 	= $rest_room_ids;

		//Doc Properites
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['total_pages'] = 1;

		//App Properites
		$app_properties['app_name'] = "Attendance app";
		$app_properties['app_id'] = "healthcare2018130121531948";
		
		//History
		$session_data = $this->session->userdata("customer");
		$email_id = $session_data['email'];
		
		$email = str_replace("@","#",$email_id);
		// History
		$approval_data = array(
			"current_stage" => "Stage Name1",
			"approval" => "true",
			"submitted_by" => $email,
			"time" => date('Y-m-d H:i:s'));

		$history['last_stage'] = $approval_data;
		
		$added = $this->bc_welfare_schools_common_model->create_attendence_report_model($doc_data, $doc_properties, $app_properties, $history);
		if($added)
		{
			$this->data['message'] = $this->session->set_flashdata('message','Attendance Report Submitted Successfully');
			redirect('bc_welfare_schools/to_dashboard');
		}
		else
		{
			redirect('bc_welfare_schools/initiateAttendanceReport');
		}

	}
	/*
	* Sanitation form functionalities
	* author Suman
	*/
	public function initiateSanitationReport()
	  {
	  	
			$this->data = "";
		  	$this->_render_page('bc_welfare_schools/sanitation_report_view', $this->data);
	  }

	  // //new suman sanitation form
	public function create_sanitation_report_new()
	{
		$doc_data = array();
		$doc_data['daily'] = array();
		$doc_data['weekly'] = array();
		$doc_data['monthly'] = array();
		
		
		if(!empty($_POST['cleanliness_Of_the_campus']) || !empty($_POST['campus_cleanliness_times']) ||!empty($_POST['animals_around_campus']) ||!empty($_POST['type_of_animal']) || !empty($_POST['other_animal_name'])|| !empty($_POST['cleanliness_toilets'])|| !empty($_POST['cleanliness_toilets_times'])|| !empty($_POST['any_damages_toilets'])|| !empty($_POST['cleanliness_Kitchen'])|| !empty($_POST['cleanliness_Kitchen_times'])|| !empty($_POST['food_days_menu'])|| !empty($_POST['kitchen_utensils'])|| !empty($_POST['cleanliness_diningHalls'])|| !empty($_POST['cleanliness_diningHalls_times'])|| !empty($_POST['hand_gloves_used_by_serving_people'])|| !empty($_POST['staffmembers_tasty_food_before_serving_meals'])|| !empty($_POST['cleanliness_wellness']) || !empty($_POST['cleanliness_Wellness_times']) || !empty($_POST['page3_Cleanliness_Wellness']) )
		{
			
		$campus	 = $this->input->post('cleanliness_Of_the_campus',true);
		$campus_cleanliness_times	 = $this->input->post('campus_cleanliness_times',true);
		$animals_around_campus = $this->input->post("animals_around_campus",true);
		$type_of_animal = $this->input->post('type_of_animal');
		$other_animal_name  = $this->input->post('other_animal_name');
		$cleanliness_toilets = $this->input->post('cleanliness_toilets');
		$cleanliness_toilets_times = $this->input->post('page3_Cleanliness_Toilets');
		$any_damages_toilets = $this->input->post('any_damages_toilets');
		$cleanliness_Kitchen = $this->input->post('cleanliness_Kitchen');
		$cleanliness_Kitchen_times = $this->input->post('cleanliness_Kitchen_times');
		$food_days_menu = $this->input->post('page3_Food_Foodpreparedaccordingtothedaysmenu');
		$kitchen_utensils = $this->input->post('page3_Cleanliness_KitchenUtensils');
		$cleanliness_diningHalls = $this->input->post('cleanliness_diningHalls');
		$cleanliness_diningHalls_times = $this->input->post('page2_Cleanliness_DiningHalls');
		$hand_gloves_used_by_serving_people  = $this->input->post('hand_gloves_used_by_serving_people');
		$staffmembers_tasty_food_before_serving_meals  = $this->input->post('staffmembers_tasty_food_before_serving_meals');
		$cleanliness_wellness  = $this->input->post('cleanliness_Of_the_wellness');
		$cleanliness_Wellness_times= $this->input->post('page3_Cleanliness_Wellness');
						
			//submiting daily sanitation report 
		$doc_data['daily']['Campus']['Cleanliness Of Campus'] = empty($campus) ? "" : $campus;
		$doc_data['daily']['Campus']['Cleanliness Of Campus Times'] = empty($campus_cleanliness_times) ? "" : $campus_cleanliness_times;
		$doc_data['daily']['Campus']['Animals Around Campus'] 	= empty($animals_around_campus) ? "" :   $animals_around_campus ;
		$doc_data['daily']['Campus']['Type Of Animal'] = empty($type_of_animal) ? "":$type_of_animal;
		$doc_data['daily']['Campus']['Other Animal Name'] 			= $other_animal_name;
		$doc_data['daily']['Toilets']['Cleanliness Toilets or Bathrooms'] = empty($cleanliness_toilets) ? "":$cleanliness_toilets;
		$doc_data['daily']['Toilets']['Cleanliness Toilets or Bathrooms In A Day']= empty($cleanliness_toilets_times) ? "":$cleanliness_toilets_times;
		$doc_data['daily']['Toilets']['Any Damages To The Toilets'] = empty($any_damages_toilets) ? "" : $any_damages_toilets;
		
		$doc_data['daily']['Kitchen']['Cleanliness Of The Kitchen Place'] = empty($cleanliness_Kitchen) ? "":$any_damages_toilets;
		$doc_data['daily']['Kitchen']['Cleanliness Of The Kitchen Place In A Day'] = empty($cleanliness_Kitchen_times) ? "" :$cleanliness_Kitchen_times;
		$doc_data['daily']['Kitchen']['Daily Menu Followed'] 			= empty($food_days_menu) ? "" :$food_days_menu;
		$doc_data['daily']['Kitchen']['Utensils Cleanliness'] 			= empty($kitchen_utensils) ? "" :$kitchen_utensils;
		$doc_data['daily']['Kitchen']['Dining Hall Cleanliness'] 	= empty($cleanliness_diningHalls) ? "" :$cleanliness_diningHalls;
		$doc_data['daily']['Kitchen']['page2_Cleanliness_DiningHalls'] 	= empty($cleanliness_diningHalls_times) ? "":$cleanliness_diningHalls_times;
		$doc_data['daily']['Kitchen']['Hand Gloves Used By Serving People'] 		= empty($hand_gloves_used_by_serving_people) ? "":$hand_gloves_used_by_serving_people;
		$doc_data['daily']['Kitchen']['Staffmembers Tasty Food Before Serving Meals'] = empty($staffmembers_tasty_food_before_serving_meals) ? "":$staffmembers_tasty_food_before_serving_meals;
		$doc_data['daily']['Kitchen']['Wellness Centre Cleanliness'] = empty($cleanliness_wellness) ? "":$cleanliness_wellness;
		$doc_data['daily']['Kitchen']['Cleanliness Of The Wellness Centre'] = empty($cleanliness_Wellness_times) ? "":$cleanliness_Wellness_times;
			
		}
		else
		{
			
			$doc_data['daily'] = array();
		}


		if(!empty($_POST['water_condition_ro_plant']) || !empty($_POST['water_condition_borewater']) || !empty($_POST['water_condition_noplant_working']) || !empty($_POST['water_tank_cleaning']) || !empty($_POST['cleanliness_dormitories'])||!empty($_POST['page2_Cleanliness_Dormitories']) ||    !empty($_POST['any_damages_to_beds']) || !empty($_POST['cleanliness_of_the_store']) || !empty($_POST['page3_Cleanliness_Store']) || !empty($_POST['storage_Of_the_items']) || !empty($_POST['any_items_issued'])|| !empty($_POST['separatedumpingof_Inorganicwaste'])|| !empty($_POST['separatedumpingof_Organicwaste'])|| !empty($_POST['dustbins'])  )
		{

			$ro_plant = $this->input->post('water_condition_ro_plant');
			$borewater = $this->input->post('water_condition_borewater');
			$noplant_working = $this->input->post('water_condition_noplant_working');
			$water_tank_cleaning = $this->input->post('water_tank_cleaning');
			$dormitories = $this->input->post('cleanliness_dormitories');
			$Cleanliness_Dormitories_times = $this->input->post('page2_Cleanliness_Dormitories');
			$any_damages_to_beds = $this->input->post('any_damages_to_beds');
			$cleanliness_of_the_store = $this->input->post('cleanliness_of_the_store');
			$cleanliness_store_times = $this->input->post('page3_Cleanliness_Store');
			$storage_of_the_items = $this->input->post('storage_Of_the_items');
			$any_items_issued =$this->input->post('any_items_issued');
			$separatedumpingof_Inorganicwaste = $this->input->post('page4_WasteManagement_SeparatedumpingofInorganicwaste');
			$separatedumpingof_Organicwaste = $this->input->post('page4_WasteManagement_SeparatedumpingofOrganicwaste');
			$dustbins = $this->input->post('dustbins');	

			$doc_data['weekly']['Water Supply Condition']['RO Plant'] = empty($ro_plant) ? "":$ro_plant;
			$doc_data['weekly']['Water Supply Condition']['Bore Water'] = empty($borewater) ? "":$borewater;
			$doc_data['weekly']['Water Supply Condition']['No Plant Working'] = empty($noplant_working) ? "":$noplant_working;
			$doc_data['weekly']['Water Supply Condition']['Water Tank Cleaning'] = empty($water_tank_cleaning) ? "":$water_tank_cleaning;
			$doc_data['weekly']['Dormitories']['Dormitory Cleaning'] = empty($dormitories) ? "":$dormitories;
			$doc_data['weekly']['Dormitories']['Cleanliness Of The Dormitory Room'] = empty($Cleanliness_Dormitories_times) ? "":$Cleanliness_Dormitories_times;
			$doc_data['weekly']['Dormitories']['Any Damages To Beds'] = empty($any_damages_to_beds) ? "":$any_damages_to_beds;

			$doc_data['weekly']['Store']['Store Room Cleanliness'] = empty($cleanliness_of_the_store) ? "":$cleanliness_of_the_store;
			$doc_data['weekly']['Store']['Cleanliness of The Store Room'] = empty($cleanliness_store_times) ? "":$cleanliness_store_times;
			$doc_data['weekly']['Store']['Proper Storage of ITEMS'] = empty($storage_of_the_items) ? "":$storage_of_the_items;
			$doc_data['weekly']['Store']['Any Default Items Issued'] = empty($any_items_issued) ? "":$any_items_issued;
			$doc_data['weekly']['Waste Management']['Separate dumping of Inorganic waste'] 			= empty($separatedumpingof_Inorganicwaste) ? "":$separatedumpingof_Inorganicwaste;
			$doc_data['weekly']['Waste Management']['Separate dumping of Organic waste'] 				= empty($separatedumpingof_Organicwaste) ? "":$separatedumpingof_Organicwaste  ;
			$doc_data['weekly']['Waste Management']['Dustbins']  = empty($dustbins) ? "" : $dustbins;

		}
		else
		{
			
			$doc_data['weekly'] = array();
		}
		if(!empty($_POST['cleanliness_water_loading']) || !empty($_POST['cleanliness_waterLoading_times']))
		{
		$cleanliness_water_loading = $this->input->post('cleanliness_water_loading');
		$cleanliness_water_loading_times = $this->input->post('cleanliness_waterLoading_times');

		$doc_data['monthly']['Water']['Water Loading Areas']  = empty($cleanliness_water_loading) ? "" : $cleanliness_water_loading;
		$doc_data['monthly']['Water']['Warter loading Areas Times']  = empty($cleanliness_water_loading_times)?"" : $cleanliness_water_loading_times;
		}
		else
		{
			
			$doc_data['monthly'] = array();
		}

		//files attachment data
	if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');
        
        $campus_external_files_upload_info = array();
        $toilet_files_upload_info = array();
        $kitchen_files_upload_info = array();
        $dormitory_files_upload_info = array();
        
        $campus_external_final    = array();
        $toilet_external_final    = array();
        $kitchen_final            = array();
        $dormitory_final          = array();
    
                    
        
        
        foreach ($_FILES as $index => $value)
       {
            
            $files = $_FILES;
            //$cpt = count($_FILES['hs_req_attachments_campus']['name']);
            if(strpos($index,'hs_req_attachments_campus')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['hs_req_attachments_campus']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['hs_req_attachments_campus']['name']  = $files['hs_req_attachments_campus']['name'][$i];
                     $_FILES['hs_req_attachments_campus']['type']  = $files['hs_req_attachments_campus']['type'][$i];
                     $_FILES['hs_req_attachments_campus']['tmp_name']= $files['hs_req_attachments_campus']['tmp_name'][$i];
                     $_FILES['hs_req_attachments_campus']['error'] = $files['hs_req_attachments_campus']['error'][$i];
                     $_FILES['hs_req_attachments_campus']['size']  = $files['hs_req_attachments_campus']['size'][$i];
            
              		$this->upload->initialize($this->campus_attachment_upload_options('healthcare201822113134483_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $campus_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$campus_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$campus_external_files_upload_info['file_name'],
                                            "file_path" =>$campus_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$campus_external_files_upload_info['file_size']
                                            )

                                        );

                    $campus_external_final = array_merge($campus_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'hs_req_attachments_toilets')!== false)
			{
                if(!empty($value['name']))
                {
                $mri = count($_FILES['hs_req_attachments_toilets']['name']);
                for($i=0; $i<$mri; $i++)
                {
                     $_FILES['hs_req_attachments_toilets']['name']    = $files['hs_req_attachments_toilets']['name'][$i];
                     $_FILES['hs_req_attachments_toilets']['type']    = $files['hs_req_attachments_toilets']['type'][$i];
                     $_FILES['hs_req_attachments_toilets']['tmp_name']= $files['hs_req_attachments_toilets']['tmp_name'][$i];
                     $_FILES['hs_req_attachments_toilets']['error']   = $files['hs_req_attachments_toilets']['error'][$i];
                     $_FILES['hs_req_attachments_toilets']['size']    = $files['hs_req_attachments_toilets']['size'][$i];
                     
                
		
			       $this->upload->initialize($this->toilet_attachment_upload_options('healthcare201822113134483_con',$index));
                if ( ! $this->upload->do_upload($index))
                {
                     echo "mri and scan file upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $toilet_files_upload_info = $this->upload->data();
                
                    $toilet_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$toilet_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$toilet_files_upload_info['file_name'],
                                            "file_path" =>$toilet_files_upload_info['file_relative_path'],
                                            "file_size" =>$toilet_files_upload_info['file_size']
                                                            )

                                            );

                    $toilet_external_final = array_merge($toilet_external_final,$toilet_data_array);
                
                }
                }
         }
     }
       
       	if(strpos($index,'hs_req_attachments_kitchen')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['hs_req_attachments_kitchen']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['hs_req_attachments_kitchen']['name']    = $files['hs_req_attachments_kitchen']['name'][$i];
                 $_FILES['hs_req_attachments_kitchen']['type']    = $files['hs_req_attachments_kitchen']['type'][$i];
                 $_FILES['hs_req_attachments_kitchen']['tmp_name']= $files['hs_req_attachments_kitchen']['tmp_name'][$i];
                 $_FILES['hs_req_attachments_kitchen']['error']   = $files['hs_req_attachments_kitchen']['error'][$i];
                 $_FILES['hs_req_attachments_kitchen']['size']    = $files['hs_req_attachments_kitchen']['size'][$i];
                 
                $this->upload->initialize($this->kitchen_attachment_upload_options('healthcare201822113134483_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "kitchen upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $kitchen_files_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$kitchen_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$kitchen_files_upload_info['file_name'],
                                            "file_path" =>$kitchen_files_upload_info['file_relative_path'],
                                            "file_size" =>$kitchen_files_upload_info['file_size']
                                                            )

                                             );

                    $kitchen_final = array_merge($kitchen_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'hs_req_attachments_dormitory')!== false)
		 {
             if(!empty($value['name']))
            {
            $dormitory = count($_FILES['hs_req_attachments_dormitory']['name']);
            for($i=0; $i<$dormitory; $i++)
            {
                
                 $_FILES['hs_req_attachments_dormitory']['name']    = $files['hs_req_attachments_dormitory']['name'][$i];
                 $_FILES['hs_req_attachments_dormitory']['type']    = $files['hs_req_attachments_dormitory']['type'][$i];
                 $_FILES['hs_req_attachments_dormitory']['tmp_name']= $files['hs_req_attachments_dormitory']['tmp_name'][$i];
                 $_FILES['hs_req_attachments_dormitory']['error']   = $files['hs_req_attachments_dormitory']['error'][$i];
                 $_FILES['hs_req_attachments_dormitory']['size']    = $files['hs_req_attachments_dormitory']['size'][$i];
                 
                $this->upload->initialize($this->dormitory_attachment_upload_options('healthcare201822113134483_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $dormitory_files_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$dormitory_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$dormitory_files_upload_info['file_name'],
                                            "file_path" =>$dormitory_files_upload_info['file_relative_path'],
                                            "file_size" =>$dormitory_files_upload_info['file_size']
                                                            )

                                             );

                    $dormitory_final = array_merge($dormitory_final,$dormitory_data_array);
            
                }
            }
            }
        }
         
       }
         if(isset($doc_data['daily']['Campus']['external_attachments']))
        {
               
                $campus_merged_data = array_merge($doc_data['daily']['Campus']['external_attachments'],$campus_external_final);
                $doc_data['daily']['Campus']['external_attachments'] = array_replace_recursive($doc_data['daily']['Campus']['external_attachments'],$campus_merged_data);
                
                log_message('debug','campus_merged_data=========387=='.print_r($campus_merged_data,true));
            
                
        }
        else
        {
                $doc_data['daily']['Campus']['external_attachments'] = $campus_external_final;
                
        } 
        
        
        
        if(isset($doc_data['daily']['Toilets']['external_attachments']))
        {
                $toilets_merged_data = array_merge($doc_data['daily']['Toilets']['external_attachments'],$toilet_external_final);
                $doc_data['daily']['Toilets']['external_attachments'] = array_replace_recursive($doc_data['daily']['Toilets']['external_attachments'],$toilets_merged_data); 
               
        }
        else
        {
                $doc_data['daily']['Toilets']['external_attachments'] = $toilet_external_final;
        }
        
        if(isset($doc_data['daily']['Kitchen']['external_attachments']))
        {
                $kitchen_merged_data = array_merge($doc_data['daily']['Kitchen']['external_attachments'],$kitchen_final);
                $doc_data['daily']['Kitchen']['external_attachments'] = array_replace_recursive($doc_data['daily']['Kitchen']['external_attachments'],$kitchen_merged_data);
        }
        else
        {
                $doc_data['daily']['Kitchen']['external_attachments'] = $kitchen_final;
        }

         if(isset($doc_data['weekly']['Dormitories']['external_attachments']))
        {
                $dormitory_merged_data = array_merge($doc_data['weekly']['Dormitories']['external_attachments'],$dormitory_final);
                $doc_data['weekly']['Dormitories']['external_attachments'] = array_replace_recursive($doc_data['weekly']['Dormitories']['external_attachments'],$dormitory_merged_data);
        }
        else
        {
                $doc_data['weekly']['Dormitories']['external_attachments'] = $dormitory_final;
        }


    } 
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$dist = explode(',', $school_name);
		$districtName = $dist[1];				
		$today_date = date("Y-m-d");
		
		$doc_data['page4']['Declaration Information']['Date:'] = $today_date;

		$doc_data['page4']['School Information']['School Name'] = $school_name;
		$doc_data['page4']['School Information']['District'] = $districtName;



	
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";

	
		
		$app_properties['app_name'] = "Sanitation app";
		$app_properties['app_id'] = "healthcare201822113134483";

	
		
		$session_data = $this->session->userdata("customer");
		$email_id = $session_data['email'];
	
		$email = str_replace("@","#",$email_id);
		// History
		$approval_data = array(
			"current_stage" => "stage1",
			"approval" => "true",
			"submitted_by" => $email,
			"time" => date('Y-m-d H:i:s'));

		$history['last_stage'] = $approval_data;


		
		$added = $this->bc_welfare_schools_common_model->create_sanitation_report_model($doc_data,$doc_properties, $app_properties, $history);
		if($added)
		{
			redirect('bc_welfare_schools/to_dashboard');
		}
		else
		{
			redirect('bc_welfare_schools/initiateSanitationReport');
		}

	}

	private function campus_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_campus')!== false)
		{
			$controller = 'healthcare201822113134483_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function toilet_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_toilets')!== false)
		{
			$controller = 'healthcare201822113134483_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function kitchen_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_kitchen')!== false)
		{
			$controller = 'healthcare201822113134483_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	private function dormitory_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_dormitory')!== false)
		{
			$controller = 'healthcare201822113134483_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	public function fetch_student_info()
	{
		$unique_id   = $_POST['page1_StudentDetails_HospitalUniqueID'];
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		
		
		$this->data['get_data'] = $this->bc_welfare_schools_common_model->fetch_student_info_model($school_name, $unique_id);

		if($this->data['get_data'] && !empty($this->data['get_data']))
		{
			$this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
	}
	/*
	 | -------------------------------------------------------------------------
	 | BMI Related Functionalties(create, update, view).
	 | -------------------------------------------------------------------------
	 */

	public function initiateBmiReport()
	{
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->load->view('bc_welfare_schools/bmifeed_report_view', $this->data);

	}

	 public function feedBmiStudentReport()
	{	
		$monthly_bmi = array();
		$uniqueId   = $this->input->post('student_code');
		$studentName = $this->input->post('page1_StudentDetails_Name');
		$class = $this->input->post('page1_StudentDetails_Class');
		$section = $this->input->post('page1_StudentDetails_Section');

		$session_data = $this->session->userdata('customer');
		$username = $session_data['email'];

		// school data
		 $school_data_array = explode("_",$uniqueId);
		 $schoolCode        = (int) $school_data_array[1];

		/* if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ion_auth->health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
		 }*/
		 $school_data = $this->bc_welfare_schools_common_model->get_school_information_for_school_code($schoolCode);
		 		$principal_name = $school_data['contact_person_name'];
		 		$principal_mob = $school_data['school_mob'];

		 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
	 	 	$hs_name = $health_supervisor['hs_name'];
	 	 	$hs_mob  = $health_supervisor['hs_mob'];

	 	 	$school_code = $this->get_my_school_code();
			$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
			$school_name = $school_info[0]['school_name'];
			$dist = explode(',', $school_name);
			$districtName = $dist[1];
		 $bmi_values_data = "";
		 $is_exists = $this->bc_welfare_schools_common_model->check_if_doc_exists_in_bmi($uniqueId);
		if(!empty($is_exists))
		{
			$height_cms = $this->input->post('page1_StudentDetails_Heightcms');
			$height_foots = $this->input->post('page1_StudentDetails_Heightfoots');
			$height_inchs = $this->input->post('page1_StudentDetails_Heightinchs');
			$weight = $this->input->post('page1_StudentDetails_Weightkgs');
			$bmi    = $this->input->post('page1_StudentDetails_BMI');
			$month  = $this->input->post('page1_StudentDetails_Date');

			if(!empty($height_cms))
			{
				$height = $height_cms;
			}else if(!empty($height_foots))
			{
				$height = ($height_foots * 30.48);
			}else if(!empty($height_inchs))
			{
				$height = ($height_inchs * 2.54);
			}

			$bmi_count_value = (double) $bmi;
			$new_date = new DateTime($month);
			$ndate = $new_date->format('Y-m-d');
			$bmi_final = array();
			$monthly_bmi = array(
				'height' => $height,
				'weight' => $weight,
				'bmi' => $bmi_count_value,
				'month' => $ndate
			);
			array_push($bmi_final, $monthly_bmi);
			$school_details = array('hs_name' => $hs_name,
									'hs_mob' => $hs_mob,
									'principal_name' => $principal_name,
									'principal_mob' => $principal_mob,
									'School Name' => $school_name,
									'District' => $districtName );

		   if($bmi_count_value <= 14 )
		   {
			   	$bmi_values_data = array(
			    	'Student Unique ID' => $uniqueId,
			    	'Name' => $studentName,
			    	'Class' => $class,
			    	'BMI_values'	=> $bmi_final,  	  	
			    	'school_details' => $school_details,
			    	'msg_count' => 0,
			    	'ecc_count' => 0
			    );
		   }else if($bmi_count_value <= 18.5 )
		   {
			   	$bmi_values_data = array(
				    	'Student Unique ID' => $uniqueId,
				    	'Name' => $studentName,
				    	'Class' => $class,
				    	'BMI_values'	=> $bmi_final,  	  	
				    	'school_details' => $school_details,
				    	'msg_count' => 0,
				    	'ecc_count' => 0
				    );
		   }else if($bmi_count_value >= 25.0 || $bmi_count_value <= 29.9)
		   {
			   	$bmi_values_data = array(
				    	'Student Unique ID' => $uniqueId,
				    	'Name' => $studentName,
				    	'Class' => $class,
				    	'BMI_values'	=> $bmi_final,  	  	
				    	'school_details' => $school_details,
				    	'msg_count' => 0,
				    	'ecc_count' => 0
				    );
		   }else if($bmi_count_value >= 30)
		   {
			   	$bmi_values_data = array(
				    	'Student Unique ID' => $uniqueId,
				    	'Name' => $studentName,
				    	'Class' => $class,
				    	'BMI_values'	=> $bmi_final,  	  	
				    	'school_details' => $school_details,
				    	'msg_count' => 0,
				    	'ecc_count' => 0
				    );
		   }
			$existing_update = $this->bc_welfare_schools_common_model->update_bmi_values($ndate,$monthly_bmi, $uniqueId,$bmi_values_data);

				if ($existing_update ) // the information has therefore been successfully saved in the db
				{
					if($bmi_count_value <= 18.5) 
					{
						$message = "Under Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}elseif ($bmi_count_value >= 25.0 && $bmi_count_value <= 29.9) {
						$message = "Over Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}
					elseif ($bmi_count_value >= 30) {
						$message = "Obese Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}
					$this->session->set_flashdata('success','BMI report updated successfully !!');
					redirect('bc_welfare_schools/initiateBmiReport');
				}
				else
				{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('bc_welfare_schools/initiateBmiReport');
				}


			}
			else 
			{
				 //Validation OK!				
				$bmi_final = array();
				$uniqueId   = $this->input->post('student_code');
				$height_cms = $this->input->post('page1_StudentDetails_Heightcms');
				$height_foots = $this->input->post('page1_StudentDetails_Heightfoots');
				$height_inchs = $this->input->post('page1_StudentDetails_Heightinchs');
				$weight = $this->input->post('page1_StudentDetails_Weightkgs');
				$bmi    = $this->input->post('page1_StudentDetails_BMI');
				$month  = $this->input->post('page1_StudentDetails_Date');

				if(!empty($height_cms))
				{
					$height = $height_cms;
				}else if(!empty($height_foots))
				{
					$height = ($height_foots * 30.48);
				}else if(!empty($height_inchs))
				{
					$height = ($height_inchs * 2.54);
				}

				$new_date = new DateTime($month);

				$ndate = $new_date->format('Y-m-d');
				$bmi_count_value = (double) $bmi;

			$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}

			$age = "";
			if($class == "5")
			{
				$age = 10;
			}else if($class == "6")
			{
				$age = 11;
			}else if($class == "7")
			{
				$age = 12;
			}else if($class == "8")
			{
				$age = 13;
			}else if($class == "9")
			{
				$age = 14;
			}else if($class == "10")
			{
				$age = 15;
			}elseif ($class == "11") 
			{
				$age = 16;
			}elseif($class == "12")
			{
				$age = 17;
			}elseif($class == "Degree 1st")
			{
				$age = 18;
			}elseif($class == "Degree 2nd")
			{
				$age = 19;
			}elseif($class == "Degree 3rd")
			{
				$age = 20;
			}

				$bmi_array = array();
				$monthly_bmi['page1']['Student Details']['Hospital Unique ID'] = $uniqueId;
				$monthly_bmi['page1']['Student Details']['Name']['field_ref'] = $studentName;
				$monthly_bmi['page1']['Student Details']['Class']['field_ref'] = $class;
				$monthly_bmi['page1']['Student Details']['Section']['field_ref'] = $section;
				$monthly_bmi['page1']['Student Details']['Date'] = $month;
				$monthly_bmi['page1']['Student Details']['Gender'] = ($gender) ? $gender : "";
				$monthly_bmi['page1']['Student Details']['Age'] = ($age) ? $age : "";
				
				$test = array(
					'height' => $height,
					'weight' => $weight,
					'bmi' 	 => $bmi_count_value,
					'month'  => $ndate
				);
				
				array_push($bmi_array, $test);

				$monthly_bmi['page1']['Student Details']['BMI_values'] = $bmi_array;
				$monthly_bmi['page1']['Student Details']['BMI_latest'] = $test;

				$school_details = array('hs_name' => $hs_name,
									'hs_mob' => $hs_mob,
									'principal_name' => $principal_name,
									'principal_mob' => $principal_mob,
									'School Name' => $school_name,
									'District' => $districtName );

			   if($bmi_count_value <= 14 )
			   {
				   	$bmi_values_data = array(
				    	'Student Unique ID' => $uniqueId,
				    	'Name' => $studentName,
				    	'Class' => $class,
				    	'BMI_values'	=> $bmi_array,  	  	
				    	'school_details' => $school_details,
				    	'msg_count' => 0,
				    	'ecc_count' => 0
				    );
			   }else if($bmi_count_value <= 18.5 )
			   {
				   	$bmi_values_data = array(
					    	'Student Unique ID' => $uniqueId,
					    	'Name' => $studentName,
					    	'Class' => $class,
					    	'BMI_values'	=> $bmi_array,  	  	
					    	'school_details' => $school_details,
					    	'msg_count' => 0,
					    	'ecc_count' => 0
					    );
			   }else if($bmi_count_value >= 25.0 || $bmi_count_value <= 29.9)
			   {
				   	$bmi_values_data = array(
					    	'Student Unique ID' => $uniqueId,
					    	'Name' => $studentName,
					    	'Class' => $class,
					    	'BMI_values'	=> $bmi_array,  	  	
					    	'school_details' => $school_details,
					    	'msg_count' => 0,
					    	'ecc_count' => 0
					    );
			   }else if($bmi_count_value >= 30)
			   {
				   	$bmi_values_data = array(
					    	'Student Unique ID' => $uniqueId,
					    	'Name' => $studentName,
					    	'Class' => $class,
					    	'BMI_values'	=> $bmi_array,  	  	
					    	'school_details' => $school_details,
					    	'msg_count' => 0,
					    	'ecc_count' => 0
					    );
			   }

				$monthly_bmi['school_details']['School Name'] = $school_name;
				$monthly_bmi['school_details']['District'] = $districtName;

				// Doc properties
				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 2;
				$doc_properties['total_pages'] = 1;

				// App properties
				$app_properties['app_name'] = "BC WELFARE BMI App";
				$app_properties['app_id'] = "healthcare2018213172422286";



				$session_data = $this->session->userdata("customer");
			
				$email_id = $session_data['email'];

				$email = str_replace("@","#",$email_id);
	          // History
				$approval_data = array(
					"current_stage" => "BC WELFARE BMI",
					"approval" => "true",
					"submitted_by" => $email,
					"time" => date('Y-m-d H:i:s'));

				$history['last_stage'] = $approval_data;


				$newly_created = $this->bc_welfare_schools_common_model->add_student_BMI_model($monthly_bmi, $doc_properties, $app_properties, $history,$bmi_values_data);

				if($newly_created)
				{
					if($bmi_count_value <= 18.5) 
					{
						$message = "Under Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}elseif ($bmi_count_value >= 25.0 && $bmi_count_value <= 29.9) {
						$message = "Over Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}
					elseif ($bmi_count_value >= 30) {
						$message = "Obese Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi_count_value;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
					}
					$this->session->set_flashdata('success','BMI report submitted successfully !!');
					redirect('bc_welfare_schools/initiateBmiReport');
				}
				else{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('bc_welfare_schools/initiateBmiReport');
				}

			}
	}
	 /*
	 |
	 | BMI Related Functionalties closed.
	 | -------------------------------------------------------------------------
	 */
	public function get_district_school_name()
	{
		$school_code  = $this->get_my_school_code();
		
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);

		$school_name = $school_info[0]['school_name'];
		
		$district = explode(",", $school_name);
		$this->data['district_name'] = $district['1'];
		$this->data['school_name'] = $school_name;

		return $this->data;
	}
	function bc_welfare_imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('bc_welfare_imports_bmi_values');
		$this->data = $this->get_district_school_name();
		$this->data['message'] = "";
		$this->_render_page('bc_welfare_schools/bc_welfare_imports_bmi_values', $this->data);
	}

	function imported_bmi_xl_sheet()
	{
		$this->check_for_admin();
		$this->check_for_plan('bc_welfare_imports_diagnostic');
		//$this->data = $this->bc_welfare_schools_common_lib->bc_welfare_imports_hb_values();
			$this->data = $this->get_district_school_name();
			$this->data['message'] = "Imported BMI XL Sheet Successfully !";
		$this->_render_page('bc_welfare_schools/bc_welfare_imports_bmi_values', $this->data);
	}

	function imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_bmi_values');

		$post = $_POST;

		//log_message('error','post=============='.print_r($post,true));exit();
		$this->data = $this->bc_welfare_schools_common_lib->imports_bmi_values($post);

		
		if($this->data == "redirect_to_bmi_fn")
		{

			redirect('bc_welfare_schools/imported_bmi_xl_sheet');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->data = $this->get_district_school_name();
			$this->_render_page('bc_welfare_schools/bc_welfare_imports_bmi_values', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->data = $this->get_district_school_name();
			$this->_render_page('bc_welfare_schools/bc_welfare_imports_bmi_values', $this->data);
		}
	}
	 /*
	 |
	 | BMI Related Functionalties closed.
	 | -------------------------------------------------------------------------
	 */
	 /*
	 | -------------------------------------------------------------------------
	 | HB Related Functionalties(create, update, view).
	 | -------------------------------------------------------------------------
	 */

	public function initiateHemoglobinReport()
	{
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		//$this->data['message'] = $this->session->flashdata('message');
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);

	  	$this->_render_page('bc_welfare_schools/hb_feed_view', $this->data);
	}

	  //hemoglobin submit data
 	public function create_hemoglobin_report()
  	{

	  	$uniqueId   = $this->input->post('student_code');
	  	$studentName = $this->input->post('page1_StudentDetails_Name');
	  	$class = $this->input->post('page1_StudentDetails_Class');


		$session_data = $this->session->userdata('customer');
		$username = $session_data['email'];

		// school data
		 $school_data_array = explode("_",$uniqueId);
		 $schoolCode        = (int) $school_data_array[1];
		 
		 	$school_data = $this->bc_welfare_schools_common_model->get_school_information_for_school_code($schoolCode);
		 		$principal_name = $school_data['contact_person_name'];
		 		$principal_mob = $school_data['school_mob'];

			 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
		 	 	$hs_name = $health_supervisor['hs_name'];
		 	 	$hs_mob  = $health_supervisor['hs_mob'];

		 	 	$school_code = $this->get_my_school_code();
				$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
				$school_name = $school_info[0]['school_name'];
				$dist = explode(',', $school_name);
				$districtName = $dist[1];
		 
	
		$is_exists  = $this->bc_welfare_schools_common_model->check_if_doc_exists_in_hb($uniqueId);
		$hb_values_data = "";
		if(!empty($is_exists))
		{
			$hb_final = array();
			$hb    = $this->input->post('page1_StudentDetails_HB');
			$month  = $this->input->post('page1_StudentDetails_Date');

			$new_date = new DateTime($month);
			$ndate = $new_date->format('Y-m-d');
			$hb_count_value = (double)$hb;

			$monthly_hb = array(
				'hb' => $hb_count_value,
				'month' => $ndate
			);
			array_push($hb_final, $monthly_hb);
			$school_details = array('hs_name' => $hs_name,
									'hs_mob' => $hs_mob,
									'principal_name' => $principal_name,
									'principal_mob' => $principal_mob,
									'School Name' => $school_name,
									'District' => $districtName );

		   if($hb_count_value < 13 )
		   {
			   	$hb_values_data = array(
			    	'Student Unique ID' => $uniqueId,
			    	'Name' => $studentName,
			    	'Class' => $class,
			    	'HB_values'	=> $hb_final,  	  	
			    	'school_details' => $school_details,
			    	'msg_count' => 0,
			    	'ecc_count' => 0
			    );
		   }


			$existing_update = $this->bc_welfare_schools_common_model->update_hb_values($ndate,$monthly_hb, $uniqueId,$hb_values_data);

			if ($existing_update ) // the information has therefore been successfully saved in the db
			{
				if($hb_count_value <= 8)
				{

					$message = "Sevier Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
				}elseif ($hb_count_value >= 8.1 && $hb_count_value <= 10) {
					$message = "Moderate Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
				}elseif ($hb_count_value >= 10.1 && $hb_count_value <= 12) {
					$message = "Mild Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
				}
				$this->session->set_flashdata('success','HB report updated successfully !!');
				redirect('bc_welfare_schools/initiateHemoglobinReport');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('bc_welfare_schools/initiateHemoglobinReport');
			}
		}
		else 
		{				
			$hb_final = array();
			$uniqueId   = $this->input->post('student_code');
			$studentName = $this->input->post('page1_StudentDetails_Name');
			$class = $this->input->post('page1_StudentDetails_Class');
			$section = $this->input->post('page1_StudentDetails_Section');
			$hb = $this->input->post('page1_StudentDetails_HB');
			$bloodgroup = $this->input->post('page1_StudentDetails_bloodgroup');
			$month = $this->input->post('page1_StudentDetails_Date');

			$new_date = new DateTime($month);
			$ndate = $new_date->format('Y-m-d');

			$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}

			$age = "";
			if($class == "5")
			{
				$age = 10;
			}else if($class == "6")
			{
				$age = 11;
			}else if($class == "7")
			{
				$age = 12;
			}else if($class == "8")
			{
				$age = 13;
			}else if($class == "9")
			{
				$age = 14;
			}else if($class == "10")
			{
				$age = 15;
			}elseif ($class == "11") 
			{
				$age = 16;
			}elseif($class == "12")
			{
				$age = 17;
			}elseif($class == "Degree 1st")
			{
				$age = 18;
			}elseif($class == "Degree 2nd")
			{
				$age = 19;
			}elseif($class == "Degree 3rd")
			{
				$age = 20;
			}

			$hb_array = array();
			$monthly_hb['page1']['Student Details']['Hospital Unique ID'] = $uniqueId;
			$monthly_hb['page1']['Student Details']['Name']['field_ref'] = $studentName;
			$monthly_hb['page1']['Student Details']['Class']['field_ref'] = $class;
			$monthly_hb['page1']['Student Details']['Section']['field_ref'] = $section;
			$monthly_hb['page1']['Student Details']['bloodgroup']['field_ref'] = $bloodgroup;
			$monthly_hb['page1']['Student Details']['Date'] = $month;
			$monthly_hb['page1']['Student Details']['Gender'] = ($gender)?$gender:"";
			$monthly_hb['page1']['Student Details']['Age'] = ($age)?$age:"";
			
			$hb_count_value = (double)$hb;
			$test = array(
				  		'hb'    => $hb_count_value,
				  		'month'  => $ndate
				    );

			array_push($hb_final, $test);	

			$school_details = array('hs_name' => $hs_name,
								'hs_mob' => $hs_mob,
								'principal_name' => $principal_name,
								'principal_mob' => $principal_mob,
								'School Name' => $school_name,
								'District' => $districtName );
		   if($hb_count_value < 13 )
		   {
			   	$hb_values_data = array(
			    	'Student Unique ID' => $uniqueId,
			    	'Name' => $studentName,
			    	'Class' => $class,
			    	'HB_values'	=> $hb_final,  	  	
			    	'school_details' => $school_details,
			    	'msg_count' => 0,
			    	'ecc_count' => 0
			    );
		   }

			$monthly_hb['page1']['Student Details']['HB_values'] = $hb_final;
			$monthly_hb['page1']['Student Details']['HB_latest'] = $test;

			/*$school_code = $this->get_my_school_code();
			$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
			$school_name = $school_info[0]['school_name'];
			$dist = explode(',', $school_name);
			$districtName = $dist[1];*/				

			$monthly_hb['school_details']['School Name'] = $school_name;
			$monthly_hb['school_details']['District'] = $districtName;


			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";


			$app_properties['app_name'] = "BC WELFARE HB App";
			$app_properties['app_id'] = "bc_welfare_himglobin_report_col";



			$session_data = $this->session->userdata("customer");
			$email_id = $session_data['email'];

			$email = str_replace("@","#",$email_id);
          	// History
			$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $email,
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;


			$newly_created = $this->bc_welfare_schools_common_model->add_student_HB_model($monthly_hb, $doc_properties, $app_properties, $history,$hb_values_data);

			if($newly_created){
				if($hb_count_value <= 8)
			{
				$message = "Sevier Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
			}elseif ($hb_count_value >= 8.1 && $hb_count_value <= 10) {
				$message = "Moderate Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
			}elseif ($hb_count_value >= 10.1 && $hb_count_value <= 12) {
				$message = "Mild Child Oberved Details: Name : ".$studentName."\nU ID : ".$uniqueId.", Class:".$class.",HB : ".$hb_count_value;
					$sms =  $this->bhashsms->send_sms($hs_mob,$message);
			}
				$this->session->set_flashdata('success','HB report submitted successfully !!');
				redirect('bc_welfare_schools/initiateHemoglobinReport');
				
			}
			else{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('bc_welfare_schools/initiateHemoglobinReport');
			}

		}
	}
	
	/*
	*show HB graph
	*/ 
	public function show_hb_student() 
	{
		
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->_render_page('bc_welfare_schools/school_wise_show_hb_graph',$this->data);
	}
	/*
	*BMI Ajax function
	
	*/ 
	public function student_hb_graph(){
		
		$month_wise_hb  = array();
		$month_wise_data = array();
		$final_hb_data  = array();
		$temp 	 		 = array();
		$unique_id = $_POST['uid'];
		
		$hb_value = $this->bc_welfare_schools_common_model->get_student_hb_values($unique_id);

		
		if(isset($hb_value) && !empty($hb_value))
		{

			foreach($hb_value as $hb)
			{
				$bloodgroup = $hb['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']['field_ref'];
				
				if(isset($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']) && !empty($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']))
				{
					
					foreach($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb_data )
					{
						
						$hb    = $hb_data['hb'];
						$date   = $hb_data['month'];
						$new_start_ = new DateTime($date);
						$month_start = $new_start_->getTimestamp()*1000;
						$pre_temp = array($month_start,(int) $hb);

						array_push($month_wise_hb,$pre_temp, $bloodgroup);

						$temp_data = array();
						$temp_data['HB'] = $hb;
						
						$temp[$month_start]  = $temp_data;

					}

				}

			}

			array_push($month_wise_data,$temp);
			arsort($month_wise_hb);
			$month_wise_hb  = array_values($month_wise_hb);
			$final_hb_data['graph_data'] = $month_wise_hb;
			$final_hb_data['month_data'] = $month_wise_data;
			$final_hb_data['bloodgroup'] = $bloodgroup;
			
			
			$this->output->set_output(json_encode($final_hb_data));
		}
		else
		{
			$this->output->set_output('NO_GRAPH');
		}
		
		
	}
	function bc_welfare_imports_hb_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('bc_welfare_imports_diagnostic');
		//$this->data = $this->tswreis_schools_common_lib->bc_welfare_imports_hb_values();
			$this->data = $this->get_district_school_name();
			$this->data['message'] = "";
		$this->_render_page('bc_welfare_schools/bc_welfare_imports_hb_values', $this->data);
	}
	function imported_hb_xl_sheet()
	{
		$this->check_for_admin();
		$this->check_for_plan('bc_welfare_imports_diagnostic');
		//$this->data = $this->bc_welfare_schools_common_lib->bc_welfare_imports_hb_values();
			$this->data = $this->get_district_school_name();
			$this->data['message'] = "Imported HB XL Sheet Successfully !";
		$this->_render_page('bc_welfare_schools/bc_welfare_imports_hb_values', $this->data);
	}

	function imports_hb_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_hb_values');

		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
	
		$post = $_POST;
		//log_message('error','post=============='.print_r($post,true));exit();
		$this->data = $this->bc_welfare_schools_common_lib->imports_hb_values($post);

		
		if($this->data == "redirect_to_hb_fn")
		{
			redirect('bc_welfare_schools/imported_hb_xl_sheet');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->data = $this->get_district_school_name();
			$this->_render_page('bc_welfare_schools/bc_welfare_imports_hb_values', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->data = $this->get_district_school_name();
			$this->_render_page('bc_welfare_schools/bc_welfare_imports_hb_values', $this->data);
		}
	}
	/*
	* Normal HTML Form Creating HS Request
	* author Naresh
	*/
	public function hs_request()
	{
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$this->data['district_code']  = strtoupper($email_array[0]);
		$this->data['school_code']    = (int) $email_array[1];
		$this->_render_page('bc_welfare_schools/hs_initiate_request', $this->data);
	}

	/*
	* Getting Students information based on Unique ID
	* author Naresh
	*/
	public function fetch_student_information()
	{
		$unique_id= $_POST['unique_id'];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		$this->data['get_data'] = $this->bc_welfare_schools_common_model->fetch_student_information_model($school_name, $unique_id);
		log_message('error','this=====data===============1778'.print_r($this->data['get_data'],true));
		$this->output->set_output(json_encode($this->data));
		
	}

	/*
	* Submiting HS Request function
	* author Naresh
	*/
	public function initiate_hs_request()
	{
		
		 // POST DATA
		/*$post = $_POST['unique_id'];
		echo print_r($post,true);
		exit();*/

		$unique_id = $this->input->post('student_code',TRUE);
		$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
		$district = $this->input->post('page1_StudentInfo_District',TRUE);
		$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
		$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
		$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
		$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
		$normal_eyes_identifier      = $this->input->post('normal_eyes_identifier',TRUE);
		$normal_ent_identifier      = $this->input->post('normal_ent_identifier',TRUE);
		$normal_rs_identifier       = $this->input->post('normal_rs_identifier',TRUE);
		$normal_cvs_identifier      = $this->input->post('normal_cvs_identifier',TRUE);
		$normal_gi_identifier       = $this->input->post('normal_gi_identifier',TRUE);
		$normal_gu_identifier       = $this->input->post('normal_gu_identifier',TRUE);
		$normal_gyn_identifier      = $this->input->post('normal_gyn_identifier',TRUE);
		$normal_cri_identifier      = $this->input->post('normal_cri_identifier',TRUE);
		$normal_msk_identifier      = $this->input->post('normal_msk_identifier',TRUE);
		$normal_cns_identifier      = $this->input->post('normal_cns_identifier',TRUE);
		$normal_psychiartic_identifier      = $this->input->post('normal_psychiartic_identifier',TRUE);
		$emergency_identifier 		= $this->input->post('emergency_identifier',TRUE);
		$emergency_bites_identifier = $this->input->post('emergency_bites_identifier',TRUE);
		
		$chronic_eyes_identifier  = $this->input->post('chronic_eyes_identifier',TRUE);
		$chronic_ent_identifier  = $this->input->post('chronic_ent_identifier',TRUE);
		$chronic_cns_identifier  = $this->input->post('chronic_cns_identifier',TRUE);
		$chronic_rs_identifier  = $this->input->post('chronic_rs_identifier',TRUE);
		$chronic_cvs_identifier  = $this->input->post('chronic_cvs_identifier',TRUE);
		$chronic_gi_identifier  = $this->input->post('chronic_gi_identifier',TRUE);
		$chronic_blood_identifier  = $this->input->post('chronic_blood_identifier',TRUE);
		$chronic_kidney_identifier  = $this->input->post('chronic_kidney_identifier',TRUE);
		$chronic_vandm_identifier  = $this->input->post('chronic_vandm_identifier',TRUE);
		$chronic_bones_identifier  = $this->input->post('chronic_bones_identifier',TRUE);
		$chronic_skin_identifier  = $this->input->post('chronic_skin_identifier',TRUE);
		$chronic_endo_identifier  = $this->input->post('chronic_endo_identifier',TRUE);
		$chronic_others_identifier  = $this->input->post('chronic_others_identifier',TRUE);

		$normal_identifiers = array(
				'General' => is_array($normal_general_identifier) ? $normal_general_identifier : [],
					'Head' => is_array($normal_head_identifier) ? $normal_head_identifier : [],
					'Eyes' => is_array($normal_eyes_identifier) ? $normal_eyes_identifier : [],
					'Ent' => is_array($normal_ent_identifier) ? $normal_ent_identifier : [],
					'Respiratory_system' => is_array($normal_rs_identifier) ? $normal_rs_identifier : [],
			'Cardio_vascular_system' => is_array($normal_cvs_identifier) ? $normal_cvs_identifier : [],
					'Gastro_intestinal' => is_array($normal_gi_identifier) ? $normal_gi_identifier : [],
					'Genito_urinary' => is_array($normal_gu_identifier) ? $normal_gu_identifier : [],
					'Gynaecology' => is_array($normal_gyn_identifier) ? $normal_gyn_identifier : [],
					'Endo_crinology' => is_array($normal_cri_identifier) ? $normal_cri_identifier : [],
			'Musculo_skeletal_syatem' => is_array($normal_msk_identifier) ? $normal_msk_identifier : [],
			'Central_nervous_system' => is_array($normal_cns_identifier) ? $normal_cns_identifier : [],
		'Psychiartic' => is_array($normal_psychiartic_identifier) ? $normal_psychiartic_identifier : []
		);

		$emergency_identifiers = array(
					'Disease' => is_array($emergency_identifier) ? $emergency_identifier : [],
					'Bites' => is_array($emergency_bites_identifier) ? $emergency_bites_identifier : []
		);
		
		$chronic_identifiers = array(
				'Eyes' => is_array($chronic_eyes_identifier) ? $chronic_eyes_identifier : [],
				'Ent'  => is_array($chronic_ent_identifier) ? $chronic_ent_identifier : [],
			'Central_nervous_system' => is_array($chronic_cns_identifier) ? $chronic_cns_identifier : [],
				'Respiratory_system' => is_array($chronic_rs_identifier) ? $chronic_rs_identifier : [],
			'Cardio_vascular_system' => is_array($chronic_cvs_identifier) ? $chronic_cvs_identifier : [],
				'Gastro_intestinal' => is_array($chronic_gi_identifier) ? $chronic_gi_identifier : [],
				'Blood'  => is_array($chronic_blood_identifier) ? $chronic_blood_identifier : [],
				'Kidney' => is_array($chronic_kidney_identifier) ? $chronic_kidney_identifier : [],
				'VandM'  => is_array($chronic_vandm_identifier) ? $chronic_vandm_identifier : [],
				'Bones'  => is_array($chronic_bones_identifier) ? $chronic_bones_identifier : [],
				'Skin'   => is_array($chronic_skin_identifier) ? $chronic_skin_identifier : [],
				'Endo'   => is_array($chronic_endo_identifier) ? $chronic_endo_identifier : [],
				'Others' => is_array($chronic_others_identifier) ? $chronic_others_identifier : []
					
		);

		$problem_info_description  = $this->input->post('page2_ProblemInfo_Description',TRUE);
		
		$doctor_summary  = $this->input->post('page2_DiagnosisInfo_DoctorSummary',TRUE);
		$doctor_advice  = $this->input->post('page2_DiagnosisInfo_DoctorAdvice',TRUE);
		$prescription  = $this->input->post('page2_DiagnosisInfo_Prescription',TRUE);

		$request_type  = $this->input->post('page2_ReviewInfo_RequestType',TRUE);
		$review_status = $this->input->post('page2_ReviewInfo_Status',TRUE);

		$gender_info = substr($school_name,strpos($school_name, "),")-1,1);

		if($gender_info == "B")
		{
			$gender = "Male";
		}else if($gender_info == "G")
		{
			$gender = "Female";
		}

		$age = "";
		if($class == "5")
		{
			$age = 10;
		}else if($class == "6")
		{
			$age = 11;
		}else if($class == "7")
		{
			$age = 12;
		}else if($class == "8")
		{
			$age = 13;
		}else if($class == "9")
		{
			$age = 14;
		}else if($class == "10")
		{
			$age = 15;
		}elseif ($class == "11") 
		{
			$age = 16;
		}elseif($class == "12")
		{
			$age = 17;
		}elseif($class == "Degree 1st")
		{
			$age = 18;
		}elseif($class == "Degree 2nd")
		{
			$age = 19;
		}elseif($class == "Degree 3rd")
		{
			$age = 20;
		}

	  // Page 1
		$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
		$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = $student_name;
		$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
		$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    = $school_name;
		$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
		$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
		$doc_data['widget_data']['page1']['Student Info']['Gender']    = ($gender) ? $gender : "";
		$doc_data['widget_data']['page1']['Student Info']['Age']    = ($age) ? $age : "";
		//$doc_data['page1']['Problem Info']    = $data_to_store;
		$doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = $doctor_summary;
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = $doctor_advice;
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = $prescription;

		$doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
		$doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

		$std_join_hospital_name = $this->input->post('std_join_hospital_name', true);
		$std_join_hospital_type = $this->input->post('std_join_hospital_type', true);
		$std_join_hospital_dist = $this->input->post('std_join_hospital_dist', true);
		$hospitalised_date = $this->input->post('hospitalised_date', true);
		$transfer_join_hospital_name = $this->input->post('transfer_join_hospital_name', true);
		$transfer_hospitalised_date = $this->input->post('transfer_hospitalised_date', true);
		$discharge_date = $this->input->post('discharge_date', true);

		if($review_status == 'Hospitalized')
		{
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;
			$doc_data['widget_data']['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
		}
		if(isset($transfer_join_hospital_name) && !empty($transfer_join_hospital_name))
		{
			$doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Name'] = $transfer_join_hospital_name;
			$doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Join Date'] = $transfer_hospitalised_date;
		}
		if($review_status == 'Discharge')
		{
			$doc_data['widget_data']['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
		}

		if(isset($normal_identifiers) && !empty($normal_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Normal']  = $normal_identifiers;
		}
		if(isset($emergency_identifiers) && !empty($emergency_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;
		}
		if(isset($chronic_identifiers) && !empty($chronic_identifiers))
		{	
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
		}

		// Attachments
		 /*if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['hs_req_attachments']['name']);
		   
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['hs_req_attachments']['name']	= $files['hs_req_attachments']['name'][$i];
			 $_FILES['hs_req_attachments']['type']	= $files['hs_req_attachments']['type'][$i];
			 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
			 $_FILES['hs_req_attachments']['error']	= $files['hs_req_attachments']['error'][$i];
			 $_FILES['hs_req_attachments']['size']	= $files['hs_req_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare2018122191146894_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 
								redirect('tswreis_schools/hs_request');  
							
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
							$rand_number = mt_rand();
						$external_data_array = array(
												  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
												"file_client_name" =>$external_files_upload_info['client_name'],
												"file_encrypted_name" =>$external_files_upload_info['file_name'],
												"file_path" =>$external_files_upload_info['file_relative_path'],
												"file_size" =>$external_files_upload_info['file_size']
																)

													 );

						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
			
			  if(isset($doc_data['external_attachments']))
			  {
					   
				$external_merged_data = array_merge($doc_data['external_attachments'],$external_final);
				$doc_data['external_attachments'] = array_replace_recursive($doc_data['external_attachments'],$external_merged_data);
			  }
			  else
			 {
			    $doc_data['external_attachments'] = $external_final;
			 } 
		  
		 }*/
		  if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');

        /*echo "<pre>";
        echo print_r($_FILES,true);
		echo "</pre>";
		exit();*/
        $Prescriptions_external_files_upload_info 	= array();
        $Lab_Reports_external_files_upload_info 	= array();
        $Digital_Images_external_files_upload_info 	= array();
        $Payments_Bills_upload_info 				= array();
        $Discharge_Summary_upload_info 				= array();
      	$external_attachments_upload_info 			= array();
		


        $Prescriptions_external_final          		= array();
        $Lab_Reports_external_final    				= array();
        $Digital_external_final    					= array();
        $Payments_Bills_external_final            	= array();
        $Discharge_Summary_external_final          	= array();
        $hs_req_attachments_external_final          = array();
        $external_final            					= array();
    
                    
        
        
        foreach ($_FILES as $index => $value)
       {
            
            $files = $_FILES;
            //$cpt = count($_FILES['hs_req_attachments_campus']['name']);

             if(strpos($index,'Prescriptions')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Prescriptions']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
                     $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
                     $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
                     $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
                     $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Prescriptions_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
                                            )

                                        );

                    $Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
            }
                }
                }
            }
            if(strpos($index,'Lab_Reports')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Lab_Reports']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Lab_Reports_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
                                            )

                                        );

                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'Digital_Images')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Digital_Images']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Digital_Images']['name']  = $files['Digital_Images']['name'][$i];
                     $_FILES['Digital_Images']['type']  = $files['Digital_Images']['type'][$i];
                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
                     $_FILES['Digital_Images']['error'] = $files['Digital_Images']['error'][$i];
                     $_FILES['Digital_Images']['size']  = $files['Digital_Images']['size'][$i];
            
              		$this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Digital_Images_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Digital_Images_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Digital_Images_external_files_upload_info['file_name'],
                                            "file_path" =>$Digital_Images_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Digital_Images_external_files_upload_info['file_size']
                                            )

                                        );

                    $Digital_external_final = array_merge($Digital_external_final,$hs_external_data_array);
            }
                }
                }
            }
       
       	if(strpos($index,'Payments_Bills')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['Payments_Bills']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Payments/Bills  upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Payments_Bills_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
                                            "file_size" =>$Payments_Bills_upload_info['file_size']
                                                            )

                                             );

                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'Discharge_Summary')!== false)
		 {
             if(!empty($value['name']))
            {
            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
            for($i=0; $i<$discharge_summary_count; $i++)
            {
                
                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Discharge_Summary_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
                                                            )

                                             );

                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
            
                }
            }
            }
        }
        if(strpos($index,'hs_req_attachments')!== false)
		 {
             if(!empty($value['name']))
            {
            $hs_req_attachments_count = count($_FILES['hs_req_attachments']['name']);
            for($i=0; $i<$hs_req_attachments_count; $i++)
            {
                
                 $_FILES['hs_req_attachments']['name']    = $files['hs_req_attachments']['name'][$i];
                 $_FILES['hs_req_attachments']['type']    = $files['hs_req_attachments']['type'][$i];
                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                 $_FILES['hs_req_attachments']['error']   = $files['hs_req_attachments']['error'][$i];
                 $_FILES['hs_req_attachments']['size']    = $files['hs_req_attachments']['size'][$i];
                 
                $this->upload->initialize($this->external_attachments_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external old attachments upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $external_attachments_upload_info = $this->upload->data();
                    
                    $external_attachments_old_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
                                            "file_size" =>$external_attachments_upload_info['file_size']
                                                            )

                                             );

                    $external_final = array_merge($external_final,$external_attachments_old_data_array);
            
                }
            }
            }
        }
         
       }

       	if(isset($doc_data['Prescriptions']))
        {
               
                $prescription_merged_data = array_merge($doc_data['Prescriptions'],$Prescriptions_external_final);
                $doc_data['Prescriptions'] = array_replace_recursive($doc_data['Prescriptions'],$prescription_merged_data);
                
                
            
                
        }
        else
        {
                $doc_data['Prescriptions'] = $Prescriptions_external_final;
                
        } 

         if(isset($doc_data['Lab_Reports']))
        {
               
                $lab_reports_merged_data = array_merge($doc_data['Lab_Reports'],$Lab_Reports_external_final);
                $doc_data['Lab_Reports'] = array_replace_recursive($doc_data['Lab_Reports'],$lab_reports_merged_data);
                
                
            
                
        }
        else
        {
                $doc_data['Lab_Reports'] = $Lab_Reports_external_final;
                
        } 
        
        
        
        if(isset($doc_data['Digital_Images']))
        {
                $digital_images_merged_data = array_merge($doc_data['Digital_Images'],$Digital_external_final);
                $doc_data['Digital_Images'] = array_replace_recursive($doc_data['Digital_Images'],$digital_images_merged_data); 
               
        }
        else
        {
                $doc_data['Digital_Images'] = $Digital_external_final;
        }
        
        if(isset($doc_data['Payments_Bills']))
        {
                $kitchen_merged_data = array_merge($doc_data['Payments_Bills'],$Payments_Bills_external_final);
                $doc_data['Payments_Bills'] = array_replace_recursive($doc_data['Payments_Bills'],$kitchen_merged_data);
        }
        else
        {
                $doc_data['Payments_Bills'] = $Payments_Bills_external_final;
        }

         if(isset($doc_data['Discharge_Summary']))
        {

                $dormitory_merged_data = array_merge($doc_data['Discharge_Summary'],$Discharge_Summary_external_final);
                $doc_data['Discharge_Summary'] = array_replace_recursive($doc_data['Discharge_Summary'],$dormitory_merged_data);
        }
        else
        {
                $doc_data['Discharge_Summary'] = $Discharge_Summary_external_final;
        }

         if(isset($doc_data['external_attachments']))
        {

                $external_merged_data = array_merge($doc_data['external_attachments'],$external_final);
                $doc_data['external_attachments'] = array_replace_recursive($doc_data['external_attachments'],$external_merged_data);
        }
        else
        {
                $doc_data['external_attachments'] = $external_final;
        }


    }
		 $session_data = $this->session->userdata('customer');
		 $username = $session_data['email'];
		

		  $doc_data['stage_name'] = 'healthcare2018122191146894';

          $doc_data['first_stage_name'] = "HS 1";

          $doc_data['user_name']  = $username;
		  
		  $doc_data['chart_data']  = '';

		 // school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->bc_welfare_schools_common_model->get_school_information_for_school_code($schoolCode);


		 /*if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ion_auth->bc_welfare_health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
		 }*/
		 	$health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];

		 //log_message('debug','healthcare2018122191146894_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2018122191146894_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
//		echo print_r($doc_data,true);
		//exit();
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
		$doc_properties['doc_access'] = "false";
		$doc_properties['access_by'] = "";
		$doc_properties['doc_access_time'] = 0;

		
		$app_properties = array(
						'app_name' => "Health Requests App",
						'app_id' => "healthcare2018122191146894",
						'status' => "new"
					);
		$array_history = array();
		$session_data = $this->session->userdata("customer");
		$schoolName = $session_data['school_name'];
		
		$email = $session_data['email'];
	 	$array_data = array(
	 		'current_stage' => "HS 1",
	 		'approval' => "true",
	 		'submitted_by' => $email,
	 		'time' => date('Y-m-d H:i:s')
	 		);
	 	array_push($array_history, $array_data);
	 	
	 	//$array_history['history'] = $array_history;
	   // History
  	/* $history = array();
  	 $history_entry = array('time'=>date('Y-m-d H:i:s'),'submitted_by'=>'','approval'=>"true","stage_name"=>"");
  	  array_push($history);
		*/
  	 // $request_type = $form_data['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
  	  log_message('error','request_type=========2658'.print_r($request_type,TRUE));
		  if($request_type === "Chronic" || $request_type === "Deficiency" || $request_type === "Defects")
		 {
	       $chronic_disease   = $chronic_identifiers;
	       $disease_desc      = $problem_info_description;
		  // log_message('error','chronic_disease=========2662'.print_r($chronic_disease,TRUE));exit();
		   $this->bc_welfare_schools_common_model->create_chronic_case_new($unique_id,$request_type,$chronic_disease,$disease_desc,$schoolName);
	     }

	   $initate_submit = $this->bc_welfare_schools_common_model->initiate_request_model($doc_data,$doc_properties,$app_properties,$array_history); 

  	  $get_doc_id = $this->bc_welfare_schools_common_model->get_doc_id_for_check($initate_submit);

	     if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
  	  {  	  	
  	  
  	  $insert_hospitalised = $this->bc_welfare_schools_common_model->insert_hospitalised_students_data($doc_data,$array_history,$unique_id,$get_doc_id,$doc_properties);  	  	

  	  } 


  	  if ($initate_submit ) // the information has therefore been successfully saved in the db
			{
				$issueinfo_2 = array();
				if(isset($normal_identifiers) && !empty($normal_identifiers))
				{
				  	foreach($normal_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	   
				  	$total_diseaes =  implode(",",$issueinfo_2);
				  	
		  	    }
		  	    if(isset($emergency_identifiers) && !empty($emergency_identifiers))
				{
				  	foreach($emergency_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);
		  	    }

		  	    if(isset($chronic_identifiers) && !empty($chronic_identifiers))
				{
				  	foreach($chronic_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);

		  	    }
				//$send_msg = $this->panacea_common_lib->send_message_to_doctors($request_type,$unique_id,$student_name,$total_diseaes);

				$fcm_notification = $this->bc_welfare_common_lib->fcm_message_notification($request_type,$unique_id,$student_name);
				
				$this->session->set_flashdata('success','Request Raised successfully !!');
				redirect('bc_welfare_schools/hs_request');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('bc_welfare_schools/hs_request');
			}

  	 

  	}

  	/*
	* Getting Request Docs from collection
	* author Naresh
	*/

  	public function fetch_submited_requests_docs()
  	{
  		$session_data = $this->session->userdata('customer');
  		$email = $session_data['email'];
  		
  		$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
  		$unique_id_code = $unique_id."*";
  		
  		$collection = "healthcare2018122191146894_static_html";
  		$hs_req_docs  = $this->bc_welfare_schools_common_model->get_hs_req_normal($collection,$unique_id_code);

		$hs_req_emergency  = $this->bc_welfare_schools_common_model->get_hs_req_emergency($collection,$unique_id_code);

		$hs_req_chronic  = $this->bc_welfare_schools_common_model->get_hs_req_chronic($collection,$unique_id_code);

		$hs_req_cured  = $this->bc_welfare_schools_common_model->get_hs_req_cured($collection,$unique_id_code);

		$hs_req_expired  = $this->bc_welfare_schools_common_model->get_hs_req_expired($collection,$unique_id_code);
			
			if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}

		if(!empty($hs_req_emergency)){
			$this->data['hs_req_emergency'] = $hs_req_emergency;
		}else{
			$this->data['hs_req_emergency'] = "";
		}
		
		if(!empty($hs_req_chronic)){
			$this->data['hs_req_chronic'] = $hs_req_chronic;
		}else{
			$this->data['hs_req_chronic'] = "";
		}

		if(!empty($hs_req_cured)){
			$this->data['hs_req_cured'] = $hs_req_cured;
		}else{
			$this->data['hs_req_cured'] = "";
		}

		if(!empty($hs_req_expired)){
			$this->data['hs_req_expired'] = $hs_req_expired;
		}else{
			$this->data['hs_req_expired'] = "";
		}
		
  		$this->_render_page('bc_welfare_schools/fetch_submited_requests_docs',$this->data);
  	}

  	/*
	* Access the request form
	* author Naresh
	*/

  	public function access_submited_request_docs($id)
  	{
  		$doc_id = $id;
		$query = $this->bc_welfare_schools_common_model->access_submited_request_docs($doc_id);
		$this->data['hs_req_docs'] = $query;
		$this->_render_page('bc_welfare_schools/access_submited_request_docs',$this->data);
  	}

  	/*
	* Update or Extended the Request Doc's
	* author Naresh
	*/

  	public function update_request_and_submit()
  	{
  		// POST DATA
  		$doc_id = $this->input->post('doc_id',true);
		$unique_id = $this->input->post('unique_id',TRUE);
		$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
		$district = $this->input->post('page1_StudentInfo_District',TRUE);
		$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
		$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
		$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
		$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
		$normal_eyes_identifier      = $this->input->post('normal_eyes_identifier',TRUE);
		$normal_ent_identifier      = $this->input->post('normal_ent_identifier',TRUE);
		$normal_rs_identifier       = $this->input->post('normal_rs_identifier',TRUE);
		$normal_cvs_identifier      = $this->input->post('normal_cvs_identifier',TRUE);
		$normal_gi_identifier       = $this->input->post('normal_gi_identifier',TRUE);
		$normal_gu_identifier       = $this->input->post('normal_gu_identifier',TRUE);
		$normal_gyn_identifier      = $this->input->post('normal_gyn_identifier',TRUE);
		$normal_cri_identifier      = $this->input->post('normal_cri_identifier',TRUE);
		$normal_msk_identifier      = $this->input->post('normal_msk_identifier',TRUE);
		$normal_cns_identifier      = $this->input->post('normal_cns_identifier',TRUE);
		$normal_psychiartic_identifier      = $this->input->post('normal_psychiartic_identifier',TRUE);
		$emergency_identifier 		= $this->input->post('emergency_identifier',TRUE);
		$emergency_bites_identifier = $this->input->post('emergency_bites_identifier',TRUE);
		
		$chronic_eyes_identifier  = $this->input->post('chronic_eyes_identifier',TRUE);
		$chronic_ent_identifier  = $this->input->post('chronic_ent_identifier',TRUE);
		$chronic_cns_identifier  = $this->input->post('chronic_cns_identifier',TRUE);
		$chronic_rs_identifier  = $this->input->post('chronic_rs_identifier',TRUE);
		$chronic_cvs_identifier  = $this->input->post('chronic_cvs_identifier',TRUE);
		$chronic_gi_identifier  = $this->input->post('chronic_gi_identifier',TRUE);
		$chronic_blood_identifier  = $this->input->post('chronic_blood_identifier',TRUE);
		$chronic_kidney_identifier  = $this->input->post('chronic_kidney_identifier',TRUE);
		$chronic_vandm_identifier  = $this->input->post('chronic_vandm_identifier',TRUE);
		$chronic_bones_identifier  = $this->input->post('chronic_bones_identifier',TRUE);
		$chronic_skin_identifier  = $this->input->post('chronic_skin_identifier',TRUE);
		$chronic_endo_identifier  = $this->input->post('chronic_endo_identifier',TRUE);
		$chronic_others_identifier  = $this->input->post('chronic_others_identifier',TRUE);

		$normal_identifiers = array(
				'General' => is_array($normal_general_identifier) ? $normal_general_identifier : [],
					'Head' => is_array($normal_head_identifier) ? $normal_head_identifier : [],
					'Eyes' => is_array($normal_eyes_identifier) ? $normal_eyes_identifier : [],
					'Ent' => is_array($normal_ent_identifier) ? $normal_ent_identifier : [],
					'Respiratory_system' => is_array($normal_rs_identifier) ? $normal_rs_identifier : [],
			'Cardio_vascular_system' => is_array($normal_cvs_identifier) ? $normal_cvs_identifier : [],
					'Gastro_intestinal' => is_array($normal_gi_identifier) ? $normal_gi_identifier : [],
					'Genito_urinary' => is_array($normal_gu_identifier) ? $normal_gu_identifier : [],
					'Gynaecology' => is_array($normal_gyn_identifier) ? $normal_gyn_identifier : [],
					'Endo_crinology' => is_array($normal_cri_identifier) ? $normal_cri_identifier : [],
			'Musculo_skeletal_syatem' => is_array($normal_msk_identifier) ? $normal_msk_identifier : [],
			'Central_nervous_system' => is_array($normal_cns_identifier) ? $normal_cns_identifier : [],
		'Psychiartic' => is_array($normal_psychiartic_identifier) ? $normal_psychiartic_identifier : []
		);

		$emergency_identifiers = array(
					'Disease' => is_array($emergency_identifier) ? $emergency_identifier : [],
					'Bites' => is_array($emergency_bites_identifier) ? $emergency_bites_identifier : []
		);
		
		$chronic_identifiers = array(
				'Eyes' => is_array($chronic_eyes_identifier) ? $chronic_eyes_identifier : [],
				'Ent'  => is_array($chronic_ent_identifier) ? $chronic_ent_identifier : [],
			'Central_nervous_system' => is_array($chronic_cns_identifier) ? $chronic_cns_identifier : [],
				'Respiratory_system' => is_array($chronic_rs_identifier) ? $chronic_rs_identifier : [],
			'Cardio_vascular_system' => is_array($chronic_cvs_identifier) ? $chronic_cvs_identifier : [],
				'Gastro_intestinal' => is_array($chronic_gi_identifier) ? $chronic_gi_identifier : [],
				'Blood'  => is_array($chronic_blood_identifier) ? $chronic_blood_identifier : [],
				'Kidney' => is_array($chronic_kidney_identifier) ? $chronic_kidney_identifier : [],
				'VandM'  => is_array($chronic_vandm_identifier) ? $chronic_vandm_identifier : [],
				'Bones'  => is_array($chronic_bones_identifier) ? $chronic_bones_identifier : [],
				'Skin'   => is_array($chronic_skin_identifier) ? $chronic_skin_identifier : [],
				'Endo'   => is_array($chronic_endo_identifier) ? $chronic_endo_identifier : [],
				'Others' => is_array($chronic_others_identifier) ? $chronic_others_identifier : []
					
		);

		$problem_info_description  = $this->input->post('page2_ProblemInfo_Description',TRUE);
		
		$doctor_summary  = $this->input->post('page2_DiagnosisInfo_DoctorSummary',TRUE);
		$doctor_advice  = $this->input->post('page2_DiagnosisInfo_DoctorAdvice',TRUE);
		$prescription  = $this->input->post('page2_DiagnosisInfo_Prescription',TRUE);

		$request_type  = $this->input->post('page2_ReviewInfo_RequestType',TRUE);
		$review_status = $this->input->post('page2_ReviewInfo_Status',TRUE);
		$std_join_hospital_name = $this->input->post('std_join_hospital_name', true);
		$std_join_hospital_type = $this->input->post('std_join_hospital_type', true);
        $std_join_hospital_dist = $this->input->post('std_join_hospital_dist', true);
        $hospitalised_date = $this->input->post('hospitalised_date', true);
        $transfer_join_hospital_name = $this->input->post('transfer_join_hospital_name', true);
        $transfer_hospitalised_date = $this->input->post('transfer_hospitalised_date', true);
        $discharge_date = $this->input->post('discharge_date', true);


		$gender_info = substr($school_name,strpos($school_name, "),")-1,1);

		if($gender_info == "B")
		{
			$gender = "Male";
		}else if($gender_info == "G")
		{
			$gender = "Female";
		}

		$age = "";
		if($class == "5")
		{
			$age = 10;
		}else if($class == "6")
		{
			$age = 11;
		}else if($class == "7")
		{
			$age = 12;
		}else if($class == "8")
		{
			$age = 13;
		}else if($class == "9")
		{
			$age = 14;
		}else if($class == "10")
		{
			$age = 15;
		}elseif ($class == "11") 
		{
			$age = 16;
		}elseif($class == "12")
		{
			$age = 17;
		}elseif($class == "Degree 1st")
		{
			$age = 18;
		}elseif($class == "Degree 2nd")
		{
			$age = 19;
		}elseif($class == "Degree 3rd")
		{
			$age = 20;
		}
	  // Page 1
		$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
		$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = $student_name;
		$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
		$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
		$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
		$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
		$doc_data['widget_data']['page1']['Student Info']['Gender']    = ($gender) ? $gender : "";
		$doc_data['widget_data']['page1']['Student Info']['Age']    = ($age) ? $age : "";
		//$doc_data['page1']['Problem Info']    = $data_to_store;
		$doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = isset($doctor_summary)? $doctor_summary : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = isset($doctor_advice) ? $doctor_advice : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = isset($prescription) ?  $prescription : "";

		$doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
		$doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

		if($review_status == 'Hospitalized')
		{
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;			
            $doc_data['widget_data']['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
		}
		if(isset($transfer_join_hospital_name) && !empty($transfer_join_hospital_name))
		{
			$doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Name'] = $transfer_join_hospital_name;
			$doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Join Date'] = $transfer_hospitalised_date;
		}
		if($review_status == 'Discharge')
		{
			$doc_data['widget_data']['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
		}

		if(isset($normal_identifiers) && !empty($normal_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Normal']  = $normal_identifiers;
		}
		if(isset($emergency_identifiers) && !empty($emergency_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
			$identifier = $emergency_identifiers;
		$doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;
		}
		if(isset($chronic_identifiers) && !empty($chronic_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
		}

		
	if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');

        /*echo "<pre>";
        echo print_r($_FILES,true);
		echo "</pre>";
		exit();*/
        $Prescriptions_upload_info 					= array();
        $Lab_Reports_external_files_upload_info 	= array();
        $Digital_Images_toilet_files_upload_info 	= array();
        $Payments_Bills_upload_info 				= array();
        $Discharge_Summary_upload_info 				= array();
        $external_files_upload_info 				= array();
		


        $Prescriptions_external_final          		= array();
        $Lab_Reports_external_final    				= array();
        $Digital_external_final    					= array();
        $Payments_Bills_external_final            	= array();
        $Discharge_Summary_external_final          	= array();
        $external_final            					= array();
    
                    
        
        
        foreach ($_FILES as $index => $value)
       {
           
            $files = $_FILES;
            //$cpt = count($_FILES['hs_req_attachments_campus']['name']);

             if(strpos($index,'Prescriptions')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Prescriptions']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
                     $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
                     $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
                     $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
                     $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Prescriptions_external_files_upload_info = $this->upload->data();
                	$rand_number =  mt_rand();
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number=> array(
                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
                                            )

                                        );

                    $Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
            }
                }
                }
            }

            if(strpos($index,'Lab_Reports')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Lab_Reports']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Lab_Reports=====================";
                    // return FALSE;

                }
            else
            {
                    $Lab_Reports_external_files_upload_info = $this->upload->data();
                	$rand_number = mt_rand();
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number => array(
                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
                                            )

                                        );

                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
                   // echo print_r($Lab_Reports_external_final,TRUE);
            }
                }
                }
            }
        
     		if(strpos($index,'Digital_Images')!== false)
			{
                if(!empty($value['name']))
                {
                $mri = count($_FILES['Digital_Images']['name']);
                for($i=0; $i<$mri; $i++)
                {
                     $_FILES['Digital_Images']['name']    = $files['Digital_Images']['name'][$i];
                     $_FILES['Digital_Images']['type']    = $files['Digital_Images']['type'][$i];
                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
                     $_FILES['Digital_Images']['error']   = $files['Digital_Images']['error'][$i];
                     $_FILES['Digital_Images']['size']    = $files['Digital_Images']['size'][$i];
                     
                
		
			       $this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare2018122191146894_con',$index));
                if ( ! $this->upload->do_upload($index))
                {
                     echo "mri and scan file upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Digital_Images_toilet_files_upload_info = $this->upload->data();
                	 $rand_number =  mt_rand();
                    $toilet_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number  => array(
                                            "file_client_name" =>$Digital_Images_toilet_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Digital_Images_toilet_files_upload_info['file_name'],
                                            "file_path" =>$Digital_Images_toilet_files_upload_info['file_relative_path'],
                                            "file_size" =>$Digital_Images_toilet_files_upload_info['file_size']
                                                            )

                                            );

                  $Digital_external_final = array_merge($Digital_external_final,$toilet_data_array);
                
                }
                }
         }
     }
       
       	if(strpos($index,'Payments_Bills')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['Payments_Bills']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare2018122191146894_con',$index));
               
                if ( ! $this->upload->do_upload($index))
                {
                     echo "Payments_Bills upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Payments_Bills_upload_info = $this->upload->data();
                     $rand_number =  mt_rand();
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number  => array(
                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
                                            "file_size" =>$Payments_Bills_upload_info['file_size']
                                                            )

                                             );

                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
            
                }
            }
            }
        }
        if(strpos($index,'hs_req_attachments')!== false)
      {
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['hs_req_attachments']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['hs_req_attachments']['name']  = $files['hs_req_attachments']['name'][$i];
                     $_FILES['hs_req_attachments']['type']  = $files['hs_req_attachments']['type'][$i];
                     $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                     $_FILES['hs_req_attachments']['error'] = $files['hs_req_attachments']['error'][$i];
                     $_FILES['hs_req_attachments']['size']  = $files['hs_req_attachments']['size'][$i];
            
                  $this->upload->initialize($this->external_attachments_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $external_attachments_upload_info = $this->upload->data();
                  $rand_number =  mt_rand();
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number=> array(
                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
                                            "file_size" =>$external_attachments_upload_info['file_size']
                                            )

                                        );

                    $external_final = array_merge($external_final,$hs_external_data_array);
            }
                }
                }
            } 
        
        	if(strpos($index,'Discharge_Summary')!== false)
		 {
             if(!empty($value['name']))
            {
            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
            /*echo print_r($discharge_summary_count,true);
            exit()*/
            for($i=0; $i<$discharge_summary_count; $i++)
            {
                
                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Discharge Summary upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Discharge_Summary_upload_info = $this->upload->data();
                    $rand_number =  mt_rand();
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$rand_number => array(
                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
                                                            )

                                             );

                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
                   /* echo print_r($Discharge_Summary_external_final,true);
                    exit();*/
            
                }
            }
            }
        }
         
       }

       $doc_history = $this->bc_welfare_schools_common_model->get_history($unique_id,$doc_id);
      
       	if(isset($doc_history[0]['doc_data']['Prescriptions']))
        {
               
                $prescription_merged_data = array_merge($doc_history[0]['doc_data']['Prescriptions'],$Prescriptions_external_final);
                $doc_data['Prescriptions'] = array_replace_recursive($doc_history[0]['doc_data']['Prescriptions'],$prescription_merged_data);
                
                
            
                
        }
        else
        {
                $doc_data['Prescriptions'] = $Prescriptions_external_final;
                
        } 
        

         if(isset($doc_history[0]['doc_data']['Lab_Reports']))
        {
               
                $Lab_Reports = array_merge($doc_history[0]['doc_data']['Lab_Reports'],$Lab_Reports_external_final);                
                $doc_data['Lab_Reports'] = array_replace_recursive($doc_history[0]['doc_data']['Lab_Reports'],$Lab_Reports);
                
        }
        else
        {
                $doc_data['Lab_Reports'] = $Lab_Reports_external_final;
                
        } 
        
        
        
        if(isset($doc_history[0]['doc_data']['Digital_Images']))
        {
                $digital_images_merged_data = array_merge($doc_history[0]['doc_data']['Digital_Images'],$Digital_external_final);
                $doc_data['Digital_Images'] = array_replace_recursive($doc_history[0]['doc_data']['Digital_Images'],$digital_images_merged_data); 
               
        }
        else
        {
                $doc_data['Digital_Images'] = $Digital_external_final;
        }
        
        if(isset($doc_history[0]['doc_data']['Payments_Bills']))
        {
                $payments_Bills_merged_data = array_merge($doc_history[0]['doc_data']['Payments_Bills'],$Payments_Bills_external_final);
                $doc_data['Payments_Bills'] = array_replace_recursive($doc_history[0]['doc_data']['Payments_Bills'],$payments_Bills_merged_data);
        }
        else
        {
                $doc_data['Payments_Bills'] = $Payments_Bills_external_final;
        }

         if(isset($doc_history[0]['doc_data']['Discharge_Summary']))
        {
                $discharge_Summary_merged_data = array_merge($doc_history[0]['doc_data']['Discharge_Summary'],$Discharge_Summary_external_final);
                $doc_data['Discharge_Summary'] = array_replace_recursive($doc_history[0]['doc_data']['Discharge_Summary'],$discharge_Summary_merged_data);
        }
        else
        {
                $doc_data['Discharge_Summary'] = $Discharge_Summary_external_final;
        }
        if(isset($doc_history[0]['doc_data']['external_attachments']))
        {
                $external_merged_data = array_merge($doc_history[0]['doc_data']['external_attachments'],$external_final);
                $doc_data['external_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['external_attachments'],$external_merged_data);
        }
        else
        {
                $doc_data['external_attachments'] = $external_final;
        }


    }

		 // school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->bc_welfare_schools_common_model->get_school_information_for_school_code($schoolCode);


		 /*if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ion_auth->bc_welfare_health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
		 }*/
		 
		 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];
		 //log_message('debug','healthcare2018122191146894_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2018122191146894_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
		 	
		$session_data = $this->session->userdata('customer');
		//echo print_r($session_data,true);exit();
		$email = $session_data['email'];
		$username = $email;
		$doc_data['user_name'] = $email;
		$submitted_user_type = $session_data['user_type'];
		/*$doc_data['widget_data']['page1']['Problem Info']['Chronic_request']['Eyes']    = $chronic_identifiers;*/
		
		/*$doc_history = $this->bc_welfare_schools_common_model->get_history($unique_id,$doc_id);
		$doc_data['user_name'] = $doc_history[0]['history'][0]['submitted_by'];
		$history_array = array();
		foreach($doc_history[0]['history'] as $array_history);
		{
			array_push($history_array, $array_history);
		}
		$session_data = $this->session->userdata("customer");

	 	$array_data = array(
	 		'current_stage' => "HS 1",
	 		'approval' => "true",
	 		'submitted_by' => $session_data['email'],
	 		'updated_info' =>"Description",
	 		'time' => date('Y-m-d H:i:s')
	 		);

	 	array_push($history_array, $array_data);*/

	 
	 	//$array_history['history'] = $array_history;
	   // History
  	/* $history = array();
  	 $history_entry = array('time'=>date('Y-m-d H:i:s'),'submitted_by'=>'','approval'=>"true","stage_name"=>"");
  	  array_push($history);
		*/

  	  //POST DATA
		$redirected_stage   = "Doctor";
		$current_stage      = "HS 2";
		//$reason             = implode(", ",$reason_array);
		//$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		$disapproving_user	= $username;
		$stage_name 		= "HS 2";

		$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 2;
			$doc_properties['_version'] = 2;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";
			$doc_properties['doc_access'] = "false";
			$doc_properties['access_by'] = "";
			$doc_properties['doc_access_time'] = 0;
		
		
		/*$history_array = [];
		
		foreach($doc_history['history'] as $stg_history){
			array_push($history_array, $stg_history);
		}
		$doc_history['history'] = $history_array;*/
		
		//$doc_update = $this->ci->panacea_common_model->update_doc_for_disapprove($healthcare2018122191146894_edit);
		$approval_data = array(
			"current_stage"    	=> $stage_name,
			"approval"		        => "false",
			"disapproved_by"	    => $disapproving_user,
			"submitted_by"			=> $disapproving_user,
			"time"		            => date('Y-m-d H:i:s'),
			//"reason"	            => $reason,
			"redirected_stage"		=> $redirected_stage,
			"redirected_user"		=> "multi_user_stage",
			"submitted_user_type"	=> $submitted_user_type); 
		
		$approval_history = $this->bc_welfare_schools_common_model->get_approval_history($doc_id);

		array_push($approval_history,$approval_data);

  	  $existing_update = $this->bc_welfare_schools_common_model->update_request_submit_model($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);

  	   if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
      {
        //$insert_hospitalised = $this->maharashtra_doctor_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);

        $check_doc_id = $this->bc_welfare_schools_common_model->check_doc_id_of_request($doc_id);
        
        if($check_doc_id == 'No Doc Found'){
         $insert_hospitalised = $this->bc_welfare_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
        }else if($review_status != $check_doc_id[0]['doc_data']['widget_data']['page2']['Review Info']['Status'])
        {
        $insert_hospitalised = $this->bc_welfare_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
        }
      }  
      
  	  if ($existing_update ) // the information has therefore been successfully saved in the db
			{
				$issueinfo_2 = array();
				if(isset($normal_identifiers) && !empty($normal_identifiers))
				{
				  	foreach($normal_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	   
				  	$total_diseaes =  implode(",",$issueinfo_2);
				  	
		  	    }
		  	    if(isset($emergency_identifiers) && !empty($emergency_identifiers))
				{
				  	foreach($emergency_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);
		  	    }

		  	    if(isset($chronic_identifiers) && !empty($chronic_identifiers))
				{
				  	foreach($chronic_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);

		  	    }
					//$send_msg = $this->panacea_common_lib->send_message_to_doctors_update($request_type,$unique_id,$student_name,$total_diseaes);
						
				$fcm_notification = $this->bc_welfare_common_lib->fcm_message_notification_update($request_type,$unique_id,$student_name);
				$this->session->set_flashdata('success','Request Updated successfully !!');
				redirect('bc_welfare_schools/fetch_submited_requests_docs');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('bc_welfare_schools/fetch_submited_requests_docs');
			}

  	  
  	}
	
	/* function get_students_list_device()
	{
		$school = $_POST['school_name'];
		$students_lists = $this->tswreis_schools_common_model->get_students_list_device($school);
		
		$this->output->set_output(json_encode($students_lists));
	} */
	public function reports_display_ehr_uid_new_html_static_hs()
	{
		$student_unique_id = $_POST['student_unique_id'];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$logged_in_user = $this->session->userdata("customer");
		$username       = $logged_in_user['username'];

        $this->data = $this->bc_welfare_schools_common_lib->reports_display_ehr_uid_new_html_static_hs($student_unique_id,$school_name);
		$this->data["username"] = $username.','.$school_name;
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		
		$this->_render_page('bc_welfare_schools/bc_welfare_reports_display_ehr_new_dashboard',$this->data);
	}
	/*
	* Dashboard version 2 functionalities
	* author Harish
	*/

	public function all_activities($date = FALSE, $request_duration = "Monthly", $screening_duration = "Yearly", $request_duration = "Yearly")
	{ 
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		
		$email_array    = explode(".",$email);
		$district_code = strtoupper($email_array[0]);
		$district_school_code    = strtoupper($email_array[0])."_".$email_array[1]."_";
		$unique_id_pattern = $district_code."_".$school_code."_";

		$today_date = date('Y-m-d');
		/* echo print_r($school_name,true);
		echo print_r($today_date,true);
		exit; */
		$this->data['report_count'] = $this->bc_welfare_schools_common_model->get_attendance_report_daily_count($school_name, $today_date);
		
		$docs = $this->bc_welfare_schools_common_model->get_requests_daily_count($district_school_code, $today_date);
		$requests_info = $this->bc_welfare_schools_common_model->get_request_info_by_type($district_school_code, $today_date);
		$doctor_docs = $this->bc_welfare_schools_common_model->get_docs_daily_count($district_school_code, $today_date);
		$doctor_docs_info = $this->bc_welfare_schools_common_model->get_docs_daily_requests_info($district_school_code, $today_date);
		$this->data['total_student_count'] = $this->bc_welfare_schools_common_model->studentscount($school_name);
		
		$this->data['normal_requests'] = $docs['normal_requests'];
		$this->data['emergency_requests'] = $docs['emergency_requests'];
		$this->data['chronic_requests'] = $docs['chronic_requests'];
		if(!empty($requests_info['normal_requests_info'])){

			$this->data['normal_requests_info'] = $requests_info['normal_requests_info'];
			
		}
		if(!empty($requests_info['emergency_requests_info'])){

			$this->data['emergency_requests_info'] = $requests_info['emergency_requests_info'];
			
		}

		if (!empty($requests_info['chronic_requests_info'])) {
		   $this->data['chronic_requests_info'] = $requests_info['chronic_requests_info'];
			
		}

		$this->data['doc_normal_requests'] = $doctor_docs['doc_normal_requests'];
		$this->data['doc_emergency_requests'] = $doctor_docs['doc_emergency_requests'];
		$this->data['doc_chronic_requests'] = $doctor_docs['doc_chronic_requests'];

		if(!empty($doctor_docs_info['doc_normal_requests_info'])){
             $this->data['doc_normal_requests_info'] = $doctor_docs_info['doc_normal_requests_info'];
       		 
       }
       if (!empty($doctor_docs_info['doc_emergency_requests_info'])){
           $this->data['doc_emergency_requests_info'] = $doctor_docs_info['doc_emergency_requests_info'];
         
       }
       if(!empty($doctor_docs_info['doc_chronic_requests_info'])) {
           $this->data['doc_chronic_requests_info'] = $doctor_docs_info['doc_chronic_requests_info'];
       		
       	}
       
		$dist = explode(',', $school_name);
		$districtName = $dist[1];

		$this->data['schoolName'] = $school_name;
		$this->data['districtName'] = $districtName;
		$this->data['dist_name_with_school_code'] = $district_school_code;

		$current_month = date('Y-m-d');
		$current_month = substr($current_month,0,-3);
				
		$bmi_info = $this->bc_welfare_schools_common_model->get_bmi_report_model_count($current_month, $school_name);

		$this->data['under_weight'] = $bmi_info['under_weight'];
		$this->data['normal_weight'] = $bmi_info['normal_weight'];
		$this->data['over_weight'] = $bmi_info['over_weight'];
		$this->data['obese'] = $bmi_info['obese'];

		$bmi_student_info = $this->bc_welfare_schools_common_model->get_bmi_report_model_info($current_month, $school_name);
		
		if(!empty($bmi_student_info['under_weight_info'])){
			$this->data['under_weight_info'] = $bmi_student_info['under_weight_info'];
			
		}
		if(!empty($bmi_student_info['normal_weight_info'])){
			$this->data['normal_weight_info'] = $bmi_student_info['normal_weight_info'];
			
		}
		if(!empty($bmi_student_info['over_weight_info'])){
		  $this->data['over_weight_info'] = $bmi_student_info['over_weight_info'];
		  
		}
		if(!empty($bmi_student_info['obese_info'])){
			$this->data['obese_info'] = $bmi_student_info['obese_info'];
		  
		}
		
		$this->data['screening_info'] =$this->bc_welfare_schools_common_model->get_all_screenings_count($today_date = false, $screening_duration = "Yearly");
		

		$hb_count = $this->bc_welfare_schools_common_model->get_hb_report_model_count($current_month, $school_name);
		$hb_count_info = $this->bc_welfare_schools_common_model->get_hb_report_model_info($current_month, $school_name);
		$this->data['severe_anamia_info'] = $hb_count_info['severe_anamia_info'];
		$this->data['moderate_anamia_info'] = $hb_count_info['moderate_anamia_info'];
		/* echo print_r($this->data['moderate_anamia_info'], true);
		exit; */
		$this->data['mild_anamia_info'] = $hb_count_info['mild_anamia_info'];
		$this->data['normal_anamia_info'] = $hb_count_info['normal_anamia_info'];
		
		$this->data['severe_anamia'] = $hb_count['severe_anamia'];
		$this->data['moderate_anamia'] = $hb_count['moderate_anamia'];
		$this->data['mild_anamia'] = $hb_count['mild_anamia'];
		$this->data['normal_anamia'] = $hb_count['normal_anamia'];

		$date = date('Y-m-d');

		$sanitation_report = $this->bc_welfare_schools_common_model->get_sanitation_report_data_with_date_version_2($date,$school_name);

		$this->data['sanitation_report'] = $this->bc_welfare_schools_common_lib->build_sanitation_report_v2($sanitation_report);
		
		
		//$this->data['sanitation_report'] = $this->bc_welfare_schools_common_lib->build_sanitation_report_activities($date,$school_name);
		//$this->data['sanitation_report'] = "";
		
		$this->data['basic_dash_screening_report_yearly'] = $this->bc_welfare_schools_common_model->get_screened_students_count($unique_id_pattern);

		$this->data['basic_dash_yearly_request_count'] = $this->bc_welfare_schools_common_model->get_all_requests_yearly_count($date,$request_duration="Yearly",$unique_id_pattern);

		$this->data['basic_dash_students_count'] = $this->bc_welfare_schools_common_model->studentscount($school_name);
		
		$school_color_code = $this->bc_welfare_schools_common_model->get_school_color_code($school_code);
		$this->data['school_color_code'] = $school_color_code;

		//$hsrequests = $this->bc_welfare_schools_common_lib->to_dashboard_new($date, $request_duration, $screening_duration);

		//$this->data['report'] = $hsrequests;
		$this->data ['news_feeds'] = $this->bc_welfare_schools_common_model->get_all_news_feeds();
		$this->data['all'] = $this->bc_welfare_schools_common_model->get_doctor_visiting_report_date_wise($school_name);
		$this->data['today_date'] = date('Y-m-d');
		$this->_render_page('bc_welfare_schools/all_activities_view',$this->data);
	}
	
	function drill_down_absent_to_students_load_ehr_basic()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr_basic');
		$temp = $_POST['ehr_data_for_absent'];
		$sick = $_POST['ehr_data_for_sick'];
		$r2h = $_POST['ehr_data_for_r2h'];
		$restroom = $_POST['ehr_data_for_restroom'];

		//$UI_id = json_decode(base64_decode($_POST['ehr_data_for_absent']),true);
		if(isset($temp) && !empty($temp))
		{
			$UI_id = explode(",", $temp);

		} else if(isset($sick) && !empty($sick))
		{
			$UI_id = explode(",", $sick);
		}else if(isset($r2h) && !empty($r2h))
		{
			$UI_id = explode(",", $r2h);
		}else if(isset($restroom) && !empty($restroom))
		{
			$UI_id = explode(",", $restroom);
		}

		$get_docs = $this->bc_welfare_schools_common_model->get_drilling_absent_students_docs($UI_id);

		$this->data['students'] = $get_docs;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$this->_render_page('bc_welfare_schools/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	public function get_students_load_ehr_doc_basic_dashboard($_id)
	{
		$docs = $this->bc_welfare_schools_common_model->get_students_load_ehr_doc_model($_id);

		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		//$this->data['notes'] = $docs['notes'];
		//$this->data['hs'] = $docs['hs'];
		$this->data['BMI_report'] = $docs['BMI_report'];
		$this->data['hb_report'] = $docs['hb_report'];

		$this->data['docscount'] = count($this->data['docs']);

		// username
		$username  = $this->get_my_username();
		$this->data['username'] = $username;

		$this->_render_page('bc_welfare_schools/bc_welfare_schools_reports_display_ehr_new',$this->data);
	}
	public function my_profile_hs()
	{
		 $session_data = $this->session->userdata('customer');
		 $email = $session_data['email'];
		 $school_data_array = explode(".",$email);

		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->bc_welfare_schools_common_model->get_school_information_for_school_code($schoolCode);
		 		$this->data['principal_name'] = $school_data['contact_person_name'];
		 		$this->data['principal_mob'] = $school_data['school_mob'];
		 		$this->data['school_name'] = $school_data['school_name'];

		 $health_supervisor = $this->bc_welfare_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $this->data['hs_name'] = $health_supervisor['hs_name'];
			 	 $this->data['hs_mob']  = $health_supervisor['hs_mob'];
		 
		 $this->_render_page('bc_welfare_schools/my_profile_hs',$this->data);
		
	}
	public function update_principal_hs_profile()
	{ 
		$session_data = $this->session->userdata('customer');
		 $email = $session_data['email'];
		 $school_data_array = explode(".",$email);

		 $schoolCode        = (int) $school_data_array[1];
		$post = $_POST;

		$result = $this->bc_welfare_schools_common_model->update_principal_hs_profile($post,$schoolCode);

		if ($result ) // the information has therefore been successfully saved in the db
				{
					$this->session->set_flashdata('success','Updated Profile successfully !!');
					redirect('bc_welfare_schools/my_profile_hs');
				}
				else
				{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('bc_welfare_schools/my_profile_hs');
				}
		//$this->data['message'] = "Updated Successfully!";
		// $this->_render_page('tswreis_schools/my_profile_hs',$this->data);
	}
	/************** BMI PIE default page************************/
	public function bmi_pie_view()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		$current_month = date('Y-m-d');
		$school_code = $this->get_my_school_code();
		
	  //Fetch school details with school code
	  	$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$this->data = $this->bc_welfare_schools_common_lib->bmi_pie_view_lib($current_month, $school_name);
		$this->_render_page('bc_welfare_schools/bc_welfare_schools_bmi_pie_view',$this->data);
	}
	/************** BMI PIE based selecting widget(month)************************/
	public function bmi_pie_view_month_wise()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		$current_month = $_POST["current_month"];
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		
	  	$school_name = $school_info[0]['school_name'];
	  	$dist = explode(",", $school_name);
	  	$district_name =strtoupper($dist[1]);
		
		$this->data = $this->bc_welfare_common_lib->bmi_pie_view_lib_month_wise($current_month,$district_name,$school_name);
		//echo print_r($this->data,true);exit();

		$this->output->set_output(json_encode($this->data));
	}
	/************** clicking BMI pie to show student reports************************/
	function drill_down_bmi_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_bmi_to_students');
		$symptom_type = $_POST['case_type'];
		$month = $_POST["current_month"];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$docs = $this->bc_welfare_schools_common_model->get_drill_down_to_bmi_report($symptom_type ,$month ,$school_name);
		
		$bmi_report = base64_encode(json_encode($docs));
		$this->output->set_output($bmi_report);
		
	}
	
	/************** drilldown to bmi reports students************************/
	function drill_down_to_bmi_report_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_to_bmi_report_students');
	
		//$temp = base64_decode($_POST['ehr_data_for_bmi']);
	
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_bmi']),true);
				
		$students = $this->bc_welfare_schools_common_model->get_drilling_bmi_students_docs($UI_id);
		
		$this->data['get_bmi_docs'] = $students;
		
		$navigation = $_POST['ehr_navigation_for_bmi'];
		$this->data['navigation'] = $navigation;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('bc_welfare_schools/drill_down_to_bmi_report_view',$this->data);
	}
	
	
	public function show_student_bmi_graph($hospital_unique_id){
		
		$month_wise_bmi  = array();
		$month_wise_data = array();
		$final_bmi_data  = array();
		$temp 	 		 = array();
		
		
		$bmi_value = $this->bc_welfare_schools_common_model->get_student_bmi_graph_values($hospital_unique_id);
	
		
		if(isset($bmi_value) && !empty($bmi_value))
		{
		
		foreach($bmi_value as $bmi)
		{
			
			if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']))
		  {
			  foreach($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values'] as $bmi_data )
			  {
				  $bmi    = $bmi_data['bmi'];
				  $date   = $bmi_data['month'];
				  $height = $bmi_data['height'];
				  $weight = $bmi_data['weight'];
				  
				  $new_start_ = new DateTime($date);
				  $month_start = $new_start_->getTimestamp()*1000;
				  $pre_temp = array($month_start,(int) $bmi);
				  
				  array_push($month_wise_bmi,$pre_temp);
				 
				  $temp_data = array();
				  $temp_data['height'] = $height;
				  $temp_data['weight'] = $weight;
				  $temp[$month_start]  = $temp_data;
				  
			  }
			  
		  }
			
		}

        array_push($month_wise_data,$temp);
		arsort($month_wise_bmi);
		$month_wise_bmi  = array_values($month_wise_bmi);
		$final_bmi_data['graph_data'] = $month_wise_bmi;
		$final_bmi_data['month_data'] = $month_wise_data;
		
		$this->_render_page('bc_welfare_schools/bc_welfare_schools_show_student_bmi_graph_view',$final_bmi_data);
		}
		
	}
	/************** hb PIE default page************************/
	public function hb_pie_view()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'hb_pie_view' );
		$current_month = date('Y-m-d');
		$school_code = $this->get_my_school_code();
		
	  //Fetch school details with school code
	  	$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$this->data = $this->bc_welfare_schools_common_lib->hb_pie_view_lib($current_month, $school_name);
		$this->_render_page('bc_welfare_schools/hb_pie_view',$this->data);
	}
	/************** clicking hb pie to show student reports************************/
	function drill_down_hb_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_hb_to_students');
		$symptom_type = $_POST['case_type'];
		$month = $_POST["current_month"];
		
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
	
		$docs = $this->bc_welfare_schools_common_model->get_drill_down_to_hb_report($symptom_type ,$month ,$school_name);
	
		$hb_report = base64_encode(json_encode($docs));
		$this->output->set_output($hb_report);
		
	}
	/************** drilldown to hb reports students************************/
	function drill_down_to_hb_report_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_to_hb_report_students');
	
		//$temp = base64_decode($_POST['ehr_data_for_hb']);
		
		//log_message('error','month==========='.print_r($month,TRUE));
	
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_hb']),true);
		
				

		$students = $this->bc_welfare_schools_common_model->get_drilling_hb_students_docs($UI_id);

		$this->data['get_hb_docs'] = $students;
		
		$navigation = $_POST['ehr_navigation_for_hb'];
		$this->data['navigation'] = $navigation;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		//echo print_r($this->data,true);
		$this->_render_page('bc_welfare_schools/drill_down_to_hb_report_view',$this->data);
	}
	/************** hb PIE based selecting widget(month)************************/
	public function hb_pie_view_month_wise()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'hb_pie_view' );
		$current_month = $_POST["current_month"];
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];

		$this->data = $this->bc_welfare_schools_common_lib->hb_pie_view_lib_month_wise($current_month,$school_name);
		$this->output->set_output(json_encode($this->data));
	}
	/************** clicking hb pie to show student reports************************/ 
	public function show_student_hb_graph($hospital_unique_id){
		
		$month_wise_hb  = array();
		$month_wise_data = array();
		$final_hb_data  = array();
		$temp 	 		 = array();
		
		
		$hb_value = $this->bc_welfare_schools_common_model->get_student_hb_graph_values($hospital_unique_id);
	
		
		if(isset($hb_value) && !empty($hb_value))
		{
		
		foreach($hb_value as $hb)
		{

			if(isset($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']) && !empty($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']))
		  {
		  		
			  foreach($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb_data )
			  {
				  $hb    = $hb_data['hb'];
				  $date   = $hb_data['month'];

				  
				  $new_start_ = new DateTime($date);
				  $month_start = $new_start_->getTimestamp()*1000;
				  $pre_temp = array($month_start,(int) $hb);
				  
				  array_push($month_wise_hb,$pre_temp);
				 
				  $temp_data = array();
				 
				  $temp[$month_start]  = $temp_data;
				  
			  }
			
		  }
			
		}

        array_push($month_wise_data,$temp);
		arsort($month_wise_hb);
		$month_wise_hb  = array_values($month_wise_hb);
		$final_hb_data['graph_data'] = $month_wise_hb;
		$final_hb_data['month_data'] = $month_wise_data;
		
		$this->_render_page('bc_welfare_schools/show_student_hb_graph_view',$final_hb_data);
		
		}
		
		
	}
	private function Prescriptions_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Prescriptions')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	private function Lab_Reports_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Lab_Reports')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function Digital_Images_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Digital_Images')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function Payments_Bills_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Payments_Bills')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	private function Discharge_Summary_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Discharge_Summary')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	private function external_attachments_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments')!== false)
		{
			$controller = 'healthcare2018122191146894_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	/* ==============weekly doctor visit start===@suman reddy===========*/
	public function doctor_visit(){
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		//$this->data['message'] = $this->session->flashdata('message');
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);

		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$this->data['all'] = $this->bc_welfare_schools_common_model->get_doctor_visiting_report_date_wise($school_name);

		$this->data['doc_names'] = $this->bc_welfare_schools_common_model->get_doctor_names($email);

	  	$this->_render_page('bc_welfare_schools/doctor_visit', $this->data);
	}
	public function fetch_student_info_for_doctor_visit()
	{
		$unique_id= $_POST['page1_StudentDetails_HospitalUniqueID'];
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		$this->data['get_data'] = $this->bc_welfare_schools_common_model->fetch_student_info_for_doctor_visit($school_name, $unique_id);
		$this->data['get_attachments'] = $this->bc_welfare_schools_common_model->fetch_student_attachments($school_name, $unique_id);
		
		if(isset($this->data) && !empty($this->data))
		{
			
			$this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
	}
	//Doctor Visiting submit data
 	public function create_doctor_visit_report()
  	{ 			  	
				 $doc_data = array();
				 $doc_attachments = array();
				$uniqueId   = $this->input->post('student_code');
				$doc_id   = $this->input->post('doc_id');
				$studentName = $this->input->post('student_name');
				$class = $this->input->post('student_class');
				$section = $this->input->post('student_section');
				$doctor_visiting_date = $this->input->post('doctor_visiting_date');
				$select_doc_name = $this->input->post('select_doc_name');
				$remarks = $this->input->post('remarks');	

				$doc_data['Student Details']['Hospital Unique ID'] = $uniqueId;
				$doc_data['Student Details']['Name'] = $studentName;
				$doc_data['Student Details']['Class'] = $class;
				$doc_data['Student Details']['Section'] = $section;
				$doc_data['Student Details']['doctor_visiting_date'] = $doctor_visiting_date;
				$doc_data['Student Details']['Visiting_doctor_name'] = $select_doc_name;
				$doc_data['Student Details']['remarks'] = $remarks;
	
		
		if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');

        
        $Prescriptions_external_files_upload_info 	= array();
        $Lab_Reports_external_files_upload_info 	= array();
        $Digital_Images_external_files_upload_info 	= array();
        $Payments_Bills_upload_info 				= array();
        $Discharge_Summary_upload_info 				= array();
      	$external_attachments_upload_info 			= array();

        $Prescriptions_external_final          		= array();
        $Lab_Reports_external_final    				= array();
        $Digital_external_final    					= array();
        $Payments_Bills_external_final            	= array();
        $Discharge_Summary_external_final          	= array();
        $hs_req_attachments_external_final          = array();
        $external_final            					= array();
        
        foreach ($_FILES as $index => $value)
       {
            
            $files = $_FILES;
           

             if(strpos($index,'Prescriptions')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Prescriptions']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
                     $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
                     $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
                     $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
                     $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Prescriptions_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
                                            )

                                        );

                    $Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
            }
                }
                }
            }
            if(strpos($index,'Lab_Reports')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Lab_Reports']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Lab_Reports_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
                                            )

                                        );

                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'Digital_Images')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Digital_Images']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Digital_Images']['name']  = $files['Digital_Images']['name'][$i];
                     $_FILES['Digital_Images']['type']  = $files['Digital_Images']['type'][$i];
                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
                     $_FILES['Digital_Images']['error'] = $files['Digital_Images']['error'][$i];
                     $_FILES['Digital_Images']['size']  = $files['Digital_Images']['size'][$i];
            
              		$this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Digital_Images_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Digital_Images_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Digital_Images_external_files_upload_info['file_name'],
                                            "file_path" =>$Digital_Images_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Digital_Images_external_files_upload_info['file_size']
                                            )

                                        );

                    $Digital_external_final = array_merge($Digital_external_final,$hs_external_data_array);
            }
                }
                }
            }
       
       	if(strpos($index,'Payments_Bills')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['Payments_Bills']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Payments/Bills  upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Payments_Bills_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
                                            "file_size" =>$Payments_Bills_upload_info['file_size']
                                                            )

                                             );

                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'Discharge_Summary')!== false)
		 {
             if(!empty($value['name']))
            {
            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
            for($i=0; $i<$discharge_summary_count; $i++)
            {
                
                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare2018122191146894_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Discharge_Summary_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
                                                            )

                                             );

                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
            
                }
            }
            }
        }
        if(strpos($index,'hs_req_attachments')!== false)
		 {
             if(!empty($value['name']))
            {
            $hs_req_attachments_count = count($_FILES['hs_req_attachments']['name']);
            for($i=0; $i<$hs_req_attachments_count; $i++)
            {
                
                 $_FILES['hs_req_attachments']['name']    = $files['hs_req_attachments']['name'][$i];
                 $_FILES['hs_req_attachments']['type']    = $files['hs_req_attachments']['type'][$i];
                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                 $_FILES['hs_req_attachments']['error']   = $files['hs_req_attachments']['error'][$i];
                 $_FILES['hs_req_attachments']['size']    = $files['hs_req_attachments']['size'][$i];
                 
                $this->upload->initialize($this->external_attachments_upload_options('healthcare2018122191146894_con',$index));

				                if ( ! $this->upload->do_upload($index))
				                {
				                     echo "external old attachments upload failed";
				                     //return FALSE;
				                }
				                else
				                {   
				        
				                    $external_attachments_upload_info = $this->upload->data();
				                    
				                    $external_attachments_old_data_array = array(
				                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
				                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
				                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
				                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
				                                            "file_size" =>$external_attachments_upload_info['file_size']
				                                                            )

				                                             );

				                    $external_final = array_merge($external_final,$external_attachments_old_data_array);
				            
				                }
            			}
            		}
        		}
         
       		}

	       	if(isset($doc_attachments['Prescriptions']))
	        {
	               
	                $prescription_merged_data = array_merge($doc_attachments['Prescriptions'],$Prescriptions_external_final);
	                $doc_attachments['Prescriptions'] = array_replace_recursive($doc_attachments['Prescriptions'],$prescription_merged_data);
	                
	        }
	        else
	        {
	                $doc_attachments['Prescriptions'] = $Prescriptions_external_final;
	                
	        } 

	         if(isset($doc_attachments['Lab_Reports']))
	        {
	               
	                $lab_reports_merged_data = array_merge($doc_attachments['Lab_Reports'],$Lab_Reports_external_final);
	                $doc_attachments['Lab_Reports'] = array_replace_recursive($doc_attachments['Lab_Reports'],$lab_reports_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Lab_Reports'] = $Lab_Reports_external_final;
	                
	        } 
	        
	        
	        
	        if(isset($doc_attachments['Digital_Images']))
	        {
	                $digital_images_merged_data = array_merge($doc_attachments['Digital_Images'],$Digital_external_final);
	                $doc_attachments['Digital_Images'] = array_replace_recursive($doc_attachments['Digital_Images'],$digital_images_merged_data); 
	               
	        }
	        else
	        {
	                $doc_attachments['Digital_Images'] = $Digital_external_final;
	        }
	        
	        if(isset($doc_attachments['Payments_Bills']))
	        {
	                $kitchen_merged_data = array_merge($doc_attachments['Payments_Bills'],$Payments_Bills_external_final);
	                $doc_attachments['Payments_Bills'] = array_replace_recursive($doc_attachments['Payments_Bills'],$kitchen_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Payments_Bills'] = $Payments_Bills_external_final;
	        }

	         if(isset($doc_attachments['Discharge_Summary']))
	        {

	                $dormitory_merged_data = array_merge($doc_attachments['Discharge_Summary'],$Discharge_Summary_external_final);
	                $doc_attachments['Discharge_Summary'] = array_replace_recursive($doc_attachments['Discharge_Summary'],$dormitory_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Discharge_Summary'] = $Discharge_Summary_external_final;
	        }

	         if(isset($doc_attachments['external_attachments']))
	        {

	                $external_merged_data = array_merge($doc_attachments['external_attachments'],$external_final);
	                $doc_attachments['external_attachments'] = array_replace_recursive($doc_attachments['external_attachments'],$external_merged_data);
	        }
	        else
	        {
	                $doc_attachments['external_attachments'] = $external_final;
	        }


    	}

				$school_code = $this->get_my_school_code();
				$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
				$school_name = $school_info[0]['school_name'];
				$dist = explode(',', $school_name);
				$districtName = $dist[1];				

				$doc_data['school_details']['School Name'] = $school_name;
				$doc_data['school_details']['District'] = $districtName;


				$doc_properties['doc_id'] = get_unique_id();

				$doc_properties['status'] = 2;
				$doc_properties['_version'] = 2;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";


				$app_properties['app_name'] = "BC Welfare Doctor Visiting App";
				$app_properties['app_id'] = "Doctor Visiting";



				$session_data = $this->session->userdata("customer");
				$email_id = $session_data['email'];

				$email = str_replace("@","#",$email_id);
	          	// History
				$approval_data = array(
					"current_stage" => "stage1",
					"approval" => "true",
					"submitted_by" => $email,
					'raised_by' => "web_side",
					"time" => date('Y-m-d H:i:s'));

				$history['last_stage'] = $approval_data;


				$newly_created = $this->bc_welfare_schools_common_model->submit_doctor_visiting_report($doc_data,$doc_attachments, $doc_properties, $app_properties, $history);

				if($newly_created){
					$this->session->set_flashdata('success','Doctor Report submitted successfully');
					redirect('bc_welfare_schools/doctor_visit');
					
				}
				else{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('bc_welfare_schools/doctor_visit');
				}

			
	}

	/* ==============get doctor visit report to Students List==============*/
	public function drill_down_to_doctor_treated_list(){
		$school_code = $this->get_my_school_code();
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$selectedDate = $_POST['selected_date'];

		$this->data['student_list'] = $this->bc_welfare_schools_common_model->drill_down_to_doctor_treated_list($school_name,$selectedDate);

		$this->data['doctor_visiting_date'] = $selectedDate;
	  	$this->_render_page('bc_welfare_schools/doctor_visit_student_list', $this->data);
	}
	public function show_doctor_treated_student(/*$uid,$doctor_visit_date*/ $doc_id)

	 {
	 	
		$this->data['unique_id'] = $this->bc_welfare_schools_common_model->show_doctor_treated_student(/*$uid, $doctor_visit_date*/$doc_id);
		
		
		$this->_render_page('bc_welfare_schools/show_doctor_visit_student',$this->data);
	}

	public function get_officials_info()
	{
		$get_info = $this->bc_welfare_schools_common_model->get_officials_info();
		return $get_info;
	}

	public function send_sms_for_not_submited_attendance()
	{
		$get_info = $this->get_officials_info();		
//		$mobile_official_3 = "9121333476";
		//$mobile_official_3 = $get_info[3]['mobile_number'];
		//$mobile_official_9 = $get_info[9]['mobile_number'];
		
		$result = $this->bc_welfare_schools_common_model->send_sms_for_not_submited_attendance();

		foreach ($result as $details) {
			$data['message_hs'] = "Hi ".$details['hs_name'].", Today Attendence Report not submitted in Your School, So Please submit Attendace Report daily before 3PM";

			$data['message_principal'] = "Hi ".$details['pc_name'].", Today Attendence Report not submitted in Your School, So Please submit Attendace Report daily before 3PM";
			$data['school_name'] = $details['school_name'];

			$message_official = "Hi Sir/Mam, ".$details['school_name']." This School did not submit Attendace Report we  have sent SMS for HS : ".$details['hs_name']." principal : ".$details['pc_name'] ;

			//$data['message_rhso'] = "Hi ".$details['rhso_name'].",".$details['school_name']." This School did not submit Attendace Report we  have sent SMS for HS : ".$details['hs_name']." principal : ".$details['pc_name'] ;
			//$general_msg = "Respected all, This is a reminder for tomorrow being National Deworming day ie: 8th August 2019. Ensure every child gets benefited (tablet) from tomorrow's program without fail.";
			$this->bc_welfare_schools_common_model->insert_attendance_info($data);
			//echo print_r($message_official.$message_hs.$message_principal.$details['hs_mob'].$details['pc_mob'].$mobile_official_9,TRUE);exit();
			//$general_msg = "Hi All, 13 Districts with Albendazole tablets with Batch numbers 1)9658,2)9659,3)9665 are not of standard quality. The districts are Nizamabad, Kamareddy, Khammam, Kothagudem, Karimanagar, Jagithyal, Peddapally, Sircilla, Mahabubnagar, Gadwal, Nagakurnnol, Narayanpet, Wanapathy Hence these districts to STOP NDD tomorrow and bring back the drugs from schools,colleges,AWC & other institutions where these tablets have been distributed :PANACEA.";
			$this->bhashsms->send_sms($details['hs_mob'], $data['message_hs']);
			$this->bhashsms->send_sms($details['pc_mob'], $data['message_principal']);
			//$this->bhashsms->send_sms($details['rhso_mobile'], $data['message_rhso']);
			//$this->bhashsms->send_sms($mobile_official_3, $message_official);
			//$this->bhashsms->send_sms($mobile_official_9,$message_official);
		}
//			$this->bhashsms->send_sms($mobile_official_3, $message_official);

		
		/*$result = $this->bc_welfare_schools_common_model->send_sms_for_not_submited_attendance();
		foreach ($result as $details) {
			$message_hs = "Hi ".$details['hs_name'].", Health request status is not updated to cured for most of cured cases. Please change the status immidiately if any.";

			$message_principal = "Hi ".$details['pc_name'].", Health request status is not updated to cured for most of cured cases. Please instruct the HS accordingly.";			
			//echo print_r($message_official.$message_hs.$message_principal.$details['hs_mob'].$details['pc_mob'].$mobile_official_9,TRUE);exit();
			$this->bhashsms->send_sms($details['hs_mob'],$message_hs);
			$this->bhashsms->send_sms($details['pc_mob'],$message_principal);
			
		}*/

		exit();
		
		
		
	}

	public function send_sms_for_not_submited_sanitation()
	{
		$get_info = $this->get_officials_info();
		$mobile_official_3 = "9121333476"; 
		//$mobile_official_3 = $get_info[3]['mobile_number'];
		//$mobile_official_9 = $get_info[9]['mobile_number'];
		$result = $this->bc_welfare_schools_common_model->send_sms_for_not_submited_sanitation();
		
		foreach ($result as $details) {
			$data['message_hs'] = "Hi ".$details['hs_name'].", Today Sanitation Report not submitted in Your School, So Please submit Sanitation Report daily before 11AM";

			$data['message_principal'] = "Hi ".$details['pc_name'].", Today Sanitation Report not submitted in Your School, So Please submit Sanitation Report daily before 11AM";

			$message_official = "Hi Sir/Mam, ".$details['school_name']." This School did not submit Sanitation Report we  have sent SMS for HS : ".$details['hs_name']." principal : ".$details['pc_name'];

			$data['message_rhso'] = "Hi ".$details['rhso_name'].",".$details['school_name']." This School did not submit Attendace Report we  have sent SMS for HS : ".$details['hs_name']." principal : ".$details['pc_name'];
			$data['school_name'] = $details['school_name'];

			//$this->bc_welfare_schools_common_model->insert_sanitation_info($data);
			//echo print_r($message_official.$message_hs.$message_principal.$details['hs_mob'].$details['pc_mob'].$mobile_official_9,TRUE);exit();
			$this->bhashsms->send_sms($details['hs_mob'],$data['message_hs']);
			$this->bhashsms->send_sms($details['pc_mob'],$data['message_principal']);
			$this->bhashsms->send_sms($details['rhso_mobile'],$data['message_rhso']);
			$this->bhashsms->send_sms($mobile_official_3,$message_official);
			//$this->bhashsms->send_sms($mobile_official_9,$message_official);
		}
		exit();
	}

	public function add_doctor_profile()
    {       
      // POST DATA
      $doctor_name      = $this->input->post('doc_name',TRUE);
      $registraction_no = $this->input->post('rgs_no',TRUE);
      $doctor_mob       = $this->input->post('mobile',TRUE);
      $working_place    = $this->input->post('current_working_place',TRUE); 
      $specialization   = $this->input->post('doc_specialization',TRUE);
      $qualification    = $this->input->post('qualification_id',TRUE);         
     
      // Form EHR Document
      $doc_data = array();
      $doc_data['page1']['Personal Information']  = array();      
      
      // Page 1
      $doc_data['page1']['Personal Information']['Name']                    = $doctor_name;
      $doc_data['page1']['Personal Information']['Mobile']                  = array("country_code"=>"91","mob_num"=>$doctor_mob);
      $doc_data['page1']['Personal Information']['Specialization']          = $qualification;
      $doc_data['page1']['Personal Information']['Qualification']           = $specialization;
      $doc_data['page1']['Personal Information']['RGD No']                  = $registraction_no;
      $doc_data['page1']['Personal Information']['Working hospital name']   = $working_place;     
      
            //$doc_data['doc_data'] = $doc_data;        
        $doc_properties['status'] = 1;
        $doc_properties['_version'] = 1;
        $doc_properties['doc_owner'] = "PANACEA";
        $doc_properties['unique_id'] = '';
        $doc_properties['doc_flow'] = "new";
        
        $session_data = $this->session->userdata("customer");
        $email_id = $session_data['email'];
        
        $email = str_replace("@","#",$email_id);
        // History
          $approval_data = array(            
             
              "submitted_by" => $email,
              "submitted_name" => "HS name",
              "time" => date('Y-m-d H:i:s'));
         
          $history['last_stage'] = $approval_data;    
      
      $added = $this->bc_welfare_schools_common_model->add_doctor_profile_model($history,$doc_properties,$doc_data); 
      if($added)
    {
        $this->session->set_flashdata('success', "Dr. name created successfully");
    }
      
    redirect('bc_welfare_schools/doctor_visit');
     
    }

    public function get_searched_student_sick_requests()
	{
		$search_data = $this->input->post('search_value', true);

		
		$logged_in_user = $this->session->userdata("customer");
		$email          = $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		       
		//Fetch school details with school code
		$school_info = $this->bc_welfare_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];


		$this->data = $this->bc_welfare_schools_common_model->get_searched_student_sick_requests_model($search_data, $school_name);

		$this->output->set_output(json_encode($this->data));
	}
	
}
