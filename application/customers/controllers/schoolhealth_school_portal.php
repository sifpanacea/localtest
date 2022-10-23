<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_school_portal extends MY_Controller {

	  function __construct()
	  {
	     
		parent::__construct();
		
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('file');
		 $this->load->helper('paas');
		 $this->load->library('session');

		 // Load MongoDB library instead of native db driver if required
		 $this->config->load('email');
		 $this->load->library('mongo_db');
		 $this->load->helper('cookie');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		 
		 $this->lang->load('auth');
		 $this->load->helper('language');
		 $this->load->model('schoolhealth_school_portal_model');
		 $this->load->library('schoolhealth_school_lib');
		 $this->load->library('ciqrcode');
		 
	 }
	 
	 public function classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('classes');
		
		$session_data = $this->session->userdata("customer");
		$school_code  = $session_data['school_code'];
		
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		
		log_message('debug','school_code=========40==========='.print_r($school_code,TRUE));
		
        $this->data = $this->schoolhealth_school_lib->classes($school_code);
		$this->_render_page('schoolhealth/schools/classes',$this->data);
	}
	
	public function create_class()
	{
		$session_data = $this->session->userdata("customer");
		$school_code  = $session_data['school_code'];
		
	 	$this->schoolhealth_school_portal_model->create_class($_POST, $school_code);	
	 	redirect('schoolhealth_school_portal/classes');
	}
	 
	public function delete_class($class_id, $school_code)
	{
		$session_data = $this->session->userdata("customer");
		$school_code =  $session_data['school_code'];
		
	 	$this->schoolhealth_school_portal_model->delete_class($class_id, $school_code);	
	 	redirect('schoolhealth_school_portal/classes');
	}
	
	public function sections()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('sections');
		
		$session_data = $this->session->userdata("customer");
		$school_code = $session_data['school_code'];
		
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		
        $this->data = $this->schoolhealth_school_lib->sections($school_code);
		$this->_render_page('schoolhealth/schools/sections',$this->data);
	}
	
	public function create_section()
	{
		$session_data = $this->session->userdata('customer');
		$school_code = $session_data['school_code'];
		
	 	$this->schoolhealth_school_portal_model->create_section($_POST, $school_code);
	 	redirect('schoolhealth_school_portal/sections');
	}
	 
	public function delete_section($section_id, $school_code)
	{
		$session_data = $this->session->userdata('customer');
		$school_code = $session_data['school_code'];
		
	 	$this->schoolhealth_school_portal_model->delete_section($section_id, $school_code);	
	 	redirect('schoolhealth_school_portal/sections');
	}
	
	public function staffs()
	{
		$this->check_for_admin();
		$this->check_for_plan('staffs');
		$this->data = $this->schoolhealth_school_lib->staff_management();
	    $this->_render_page('schoolhealth/schools/staffs',$this->data);
	}
	
	public function create_staff()
	{
		$this->schoolhealth_school_portal_model->create_staff($_POST);
		redirect('schoolhealth_school_portal/staffs');
	}
	
	public function delete_staff($staff_id)
	{
		$this->schoolhealth_school_portal_model->delete_staff($staff_id);
		redirect('schoolhealth_school_portal/staffs');
	}
	
	public function create_student()
	{
		$session_data = $this->session->userdata("customer");
		$school_code  = $session_data['school_code'];
		
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		log_message("debug","school_information=====123=====".print_r($school_info,true));
		
		$district_id = $school_info[0]['dt_name'];
		$school_name = $school_info[0]['school_name'];
		$dist_info = $this->schoolhealth_school_portal_model->get_district($district_id);
		log_message("debug","district_code=====126=====".print_r($dist_info,true));
		$district_code = $dist_info[0]['dt_code'];
		
		$classes     = $this->schoolhealth_school_portal_model->get_all_classes("All",$school_code);
		$sections    = $this->schoolhealth_school_portal_model->get_all_sections("All",$school_code);
		$hunique_id  = $this->schoolhealth_school_portal_model->generate_new_hunique_id($school_code,$district_code,$school_name);
		
		$this->data['school_name']   = $school_name;
		$this->data['classes']       = $classes;
		$this->data['sections']      = $sections;
		$this->data['huniqueid']     = $hunique_id;
		$this->data['message']       = '';
		
		$this->_render_page('schoolhealth/schools/create_student',$this->data);
		
	}
	
	public function add_student_ehr()
	{
	  // Variables
	  $photo_obj = array();
	  
	  // Session data
	  $session_data = $this->session->userdata("customer");
	  $school_code  = $session_data['school_code'];
	 
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
	  $siblings_check = $this->input->post('siblings_check',TRUE);
  
	  if($siblings_check)
	  {
		$siblings = $this->input->post('siblings',TRUE);
		$siblings = explode(",",$siblings);
	  }
	  else
	  {
		$siblings = "";
	  } 
  
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
	  $doc_data['page1']['Personal Information']['Name']             = $student_name;
	  $doc_data['page1']['Personal Information']['Mobile']           = array("country_code"=>"91","mob_num"=>$student_mob);
	  $doc_data['page1']['Personal Information']['Date of Birth']    = $student_dob;
	  $doc_data['page1']['Personal Information']['Hospital Unique ID'] = $helath_unique_id;
	  $doc_data['page1']['Personal Information']['Photo']            = "";
	  
	  // Page 2
	  $doc_data['page2']['Personal Information']['AD No']            = $admission_no;
	  $doc_data['page2']['Personal Information']['District']         = "";
	  $doc_data['page2']['Personal Information']['School Name']      = $school_name;
	  $doc_data['page2']['Personal Information']['Class'] 			 = $class;
	  $doc_data['page2']['Personal Information']['Section']          = $section;
	  $doc_data['page2']['Personal Information']['Father Name']      = $father_name;
	  $doc_data['page2']['Personal Information']['Siblings']         = $siblings;
	  $doc_data['page2']['Personal Information']['Date of Exam']     = "";
	  
	  if(isset($_FILES) && !empty($_FILES))
	  {
           $this->load->library('upload');
		   
	       $config = array();
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare20161014212024617_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare20161014212024617_con/photo/",0777,TRUE);
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
	  
	  $added = $this->schoolhealth_school_portal_model->add_student_ehr_model($doc_data,$history); 
	  
	  $school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  $district_id = $school_info[0]['dt_name'];
	  $school_name = $school_info[0]['school_name'];
	  $dist_info = $this->schoolhealth_school_portal_model->get_district($district_id);
	  $district_code = $dist_info[0]['dt_code'];
	  
	  if($added)
	  {
        $classes  = $this->schoolhealth_school_portal_model->get_all_classes("All",$school_code);
		$sections = $this->schoolhealth_school_portal_model->get_all_sections("All",$school_code);
		$hunique_id = $this->schoolhealth_school_portal_model->generate_new_hunique_id($school_code,$district_code,$school_name);
		$this->data['school_name']   = $school_name;
		$this->data['classes']   = $classes;
		$this->data['sections']  = $sections;
		$this->data['huniqueid'] = $hunique_id;
		$this->data['message']  = "EHR added successfully";
		$this->_render_page('schoolhealth/schools/create_student',$this->data);
	  }
	  else
	  {
        $classes  = $this->schoolhealth_school_portal_model->get_all_classes("All");
		$sections = $this->schoolhealth_school_portal_model->get_all_sections("All");
		$hunique_id = $this->schoolhealth_school_portal_model->generate_new_hunique_id($school_code,$district_code,$school_name);
		$this->data['classes']   = $classes;
		$this->data['sections']  = $sections;
		$this->data['huniqueid'] = $hunique_id;
		$this->data['message']  = "Failed. Try Again !";
		$this->_render_page('schoolhealth/schools/create_student',$this->data);
	  }
	 
	}
	
	public function student_reports()
	{
		
		$per_page = ""; $page = ""; 
		$school_code = $this->get_my_school_code();
		$this->check_for_admin();
		$this->check_for_plan('student_reports');
		
		$session_data = $this->session->userdata("customer");

		$user = $session_data['username'];


		if(!preg_match('/SAM/', $user)){
			
			$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
			log_message("debug","school_information=====286=====".print_r($school_info,true));

			$school_name = $school_info[0]['school_name'];
			
			log_message("debug","school_information=====290=====".print_r($school_name,true));
			$this->data['students'] = $this->schoolhealth_school_portal_model->get_student($per_page,$page,$school_name);
				
			$this->data['studentscount'] = $this->schoolhealth_school_portal_model->studentscount($school_name);
			$this->data['classlist'] = $this->schoolhealth_school_portal_model->get_classes($per_page, $page, $school_code);
			$this->data['schoolname'] = $school_name;

		}else{
			

			$school_name = $session_data['school_name'];
			$school_code = $session_data['school_code'];


			log_message("debug","school_information=====290=====".print_r($school_name,true));
			$this->data['students'] = $this->schoolhealth_school_portal_model->get_student($per_page,$page,$school_name);
				
			$this->data['studentscount'] = $this->schoolhealth_school_portal_model->studentscount($school_name);
			$this->data['classlist'] = $this->schoolhealth_school_portal_model->get_classes($per_page, $page, $school_code);
			$this->data['schoolname'] = $school_name;
		}


		
		$this->_render_page('schoolhealth/schools/student_reports',$this->data);
	}
	
	public function get_students_list_by_class()
	{
		// POST Data
		$selected_class = $this->input->post('selected_class',TRUE);
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$students = $this->schoolhealth_school_portal_model->get_students_by_class($selected_class, $school_name);
		$this->output->set_output(json_encode($students));
	}
	
	public function get_my_school_code()
	{
	  $session_data = $this->session->userdata("customer");
	  $school_code  = $session_data['school_code'];
	  return $school_code;
	}
	
	public function staff_reports()
	{
		$this->check_for_admin();
		$this->check_for_plan('staff_reports');
		$this->data = "";
        //$this->data = $this->schoolhealth_school_portal_model->staff_reports();
		$this->_render_page('schoolhealth/schools/staff_reports',$this->data);
	}
	
	public function reports_ehr()
	{
		$this->data["message"] = "";
		$this->_render_page('schoolhealth/schools/reports_ehr',$this->data);
	}
	
	function imports_option_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_option_students');
	    $this->data['message'] = FALSE;
	    $this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
	}
	
	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');
		
		$post = $_POST;
		$this->data = $this->schoolhealth_school_lib->import_students($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
	      $this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
		else if($this->data == "redirect_to_student_fn")
		{
		  redirect('schoolhealth_school_portal/create_student');
		}
		else if($this->data['error'] == 'excel_column_check_fail')
		{
		  $this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
		else if($this->data['error'] == 'file_upload_failed')
		{
	      $this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
	}
	
	function update_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('undate_students');
		
		$this->data = $this->schoolhealth_school_lib->update_students();
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
		else if($this->data == "redirect_to_student_fn")
		{
			redirect('schoolhealth_school_portal/student_reports');
		}
		else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
		else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('schoolhealth/schools/imports_option_students', $this->data);
		}
	}
	
	function imports_option_staffs()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_option_staffs');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('schoolhealth/schools/imports_option_staffs', $this->data);
	}
	
	function import_staffs()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_staffs');
		
		$post = $_POST;
		$this->data = $this->schoolhealth_school_lib->import_staffs($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('schoolhealth/schools/imports_option_staffs', $this->data);
		}
		else if($this->data == "redirect_to_student_fn")
		{
			redirect('schoolhealth_school_portal/staff_reports');
		}
		else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('schoolhealth/schools/imports_option_staffs', $this->data);
		}
		else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('schoolhealth/schools/imports_option_staffs', $this->data);
		}
	}
	
	public function export_pie()
	{
	   $this->data['today_date'] = date('Y-m-d');
	   $this->_render_page('schoolhealth/schools/pie_export',$this->data);
	}
	
	public function reports_display_ehr_uid()
	{
		$post = $_POST;
	  
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$this->data = $this->schoolhealth_school_lib->reports_display_ehr_uid($post,$school_name);
		$this->_render_page('schoolhealth/schools/reports_display_ehr',$this->data);
	
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		
		//POST Data
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		
		//Loggedinuser
		$logged_in_user = $this->session->userdata("customer");
		$school_code    = $logged_in_user['school_code'];
		
		log_message('debug','$this->screening_pie_data_for_stage5====logged_in_user=====>'.print_r($logged_in_user,true));
		
		//Fetch school details with school code
		
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		log_message('debug','$this->screening_pie_data_for_stage5====school_name=====>'.print_r($school_name,true));
		
		
		$this->schoolhealth_school_portal_model->update_screening_collection($today_date,$screening_pie_span,$school_name);
		$today_date = $this->schoolhealth_school_portal_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	
	function to_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];

		$session_data = $this->session->userdata("customer");

		    
		$this->data = $this->schoolhealth_school_lib->to_dashboard_with_date($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->schoolhealth_school_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		log_message("debug","postttttttttttttttttttttttt".print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->schoolhealth_school_portal_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		log_message('debug','$_POST=====371=====1=='.print_r($_POST,true));
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->schoolhealth_school_portal_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		log_message('debug','$_POST=====371=====2=='.print_r($docs,true));
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		log_message('debug','get_drilling_screenings_students_docs=$_POST='.print_r($_POST,true));
		
		
		$get_docs = $this->schoolhealth_school_portal_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		/*$doc_list = $this->schoolhealth_school_portal_model->get_all_doctors();*/
		
		
		$this->data['doctor_list'] = "";

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('schoolhealth/schools/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		$docs = $this->schoolhealth_school_portal_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs']          = $docs['screening'];
		//$this->data['docs_requests'] = $docs['request'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('schoolhealth/schools/reports_display_ehr',$this->data);
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
			$this->_render_page('schoolhealth/schools/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			
			log_message('debug','$identity=====782======'.print_r($identity,true));
			
			$change = $this->schoolhealth_school_portal_model->change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));
			
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
			$this->_render_page('schoolhealth/schools/change_password', $this->data);
			}
		}
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->schoolhealth_school_portal_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_students_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_student_list');
		 $dist_id = $_POST['dist_id'];
		 $this->data = $this->schoolhealth_school_portal_model->get_student_by_class_id($dist_id);
		 $this->output->set_output(json_encode($this->data));
	}
	
	public function generate_excel_for_screening_pie()
	{
	    // POST Data
		$today_date         = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		
		// get school name
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		$file_path = $this->schoolhealth_school_lib->generate_excel_for_screening_pie($today_date,$screening_pie_span,$school_name);
		$this->output->set_output($file_path);
	}
	
	function get_students_for_generating_health_summary_report()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_students_for_health_summary_report');
		
		// POST Data
		$selected_class   = $this->input->post('selected_class',true);
		//$selected_section = $this->input->post('selected_section',true);
		
		// get school name
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];
		
		$students_list = $this->schoolhealth_school_portal_model->get_students_by_selected_class_section($selected_class,$school_name);
		
		$this->data['students'] = $students_list;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('schoolhealth/schools/selected_students_list_health_summary',$this->data);
	}
	
	public function init_health_summary_report_process()
	{
	  // POST Data
	  $id_str = $this->input->post('unique_ids',true);
	  //$id_str = json_decode($id_str,true);
	  
	  $health_summary_report = $this->schoolhealth_school_lib->generate_health_summary_report($id_str);
	  
	  if($health_summary_report)
	  {
        $this->output->set_output($health_summary_report);
	  }
	  
	}
	
	/**
	 * Helper: BMI PIE REPORT
	 
	 * @author bhanu 
	 */
	
	
	/************** BMI PIE default page************************/
	public function bmi_pie_view()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		$current_month = date('Y-m-d');
		$school_code = $this->get_my_school_code();
		
	  //Fetch school details with school code
	  	$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$this->data = $this->schoolhealth_school_lib->bmi_pie_view_lib($school_name);
		$this->_render_page('schoolhealth/schools/bmi_pie_view',$this->data);
	}
	
	/************** BMI PIE based selecting widget(month)************************/
	public function bmi_pie_view_month_wise()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
	
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		$this->data = $this->schoolhealth_school_lib->bmi_pie_view_lib_month_wise($school_name);
		$this->output->set_output(json_encode($this->data));
	}
	
	/************** clicking BMI pie to show student reports************************/
	function drill_down_bmi_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_bmi_to_students');
		$symptom_type = $_POST['case_type'];
	
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$docs = $this->schoolhealth_school_portal_model->get_drill_down_to_bmi_report($symptom_type, $school_name);
		
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
				
		$students = $this->schoolhealth_school_portal_model->get_drilling_bmi_students_docs($UI_id);
		
		$this->data['get_bmi_docs'] = $students;
		
		$navigation = $_POST['ehr_navigation_for_bmi'];
		$this->data['navigation'] = $navigation;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('schoolhealth/schools/drill_down_to_bmi_report_view',$this->data);
	}
	
	public function generate_bmi_report_to_excel()
	{
		//$date = $_POST['current_month'];
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];
		
		$file_path = $this->schoolhealth_school_lib->generate_bmi_report_to_excel_lib($school_name);
		
		$this->output->set_output($file_path);
		
	}
	
	public function student_ehr_document($hospital_unique_id)
	{
		$school_code = $this->get_my_school_code();
		$school_info = $this->schoolhealth_school_portal_model->get_school_info($school_code);
	  	$school_name = $school_info[0]['school_name'];

		$docs = $this->schoolhealth_school_portal_model->get_student_ehr_document($hospital_unique_id,$school_name );
		$this->data['docs'] = $docs['screening'];
	    $this->data['docscount'] = count($this->data['docs']);
		$this->_render_page('schoolhealth/schools/reports_display_ehr',$this->data);
	}

	public function get_qr_image_for_student()
	{
		$data['name'] = $this->input->post('stud_name', true);
		$data['health_id'] = $this->input->post('stud_id', true);
		//$data['school'] = $this->input->post('stud_scl', true);
		$doc_id = $this->input->post('doc_ID', true);

		//$data = $this->schoolhealth_school_lib->get_qr_image_for_student($data);
		$this->load->library('upload');
		$this->load->library('ciqrcode');

		/* $SERVERFILEPATH = UPLOADFOLDERDIR.'public/uploads/qrcodes/';
		//$text = $qrtext;
		//$text1= substr($text, 0,9);
		$id = $data;
		$folder = $SERVERFILEPATH;
		$file_name1 = $data['health_id']."_Qrcode_" . rand(2,200000) . ".png";
		$file_name = $folder.$file_name1;*/

		$params['data'] = $data;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = UPLOADFOLDERDIR.'public/uploads/qrcodes/'.$data['health_id']."_Qrcode_" . rand(2,200000) . ".png";
		$this->ciqrcode->generate($params);


		//QRcode::png($id,$file_name);

		$photo_size = filesize($params['savename']);

		//echo print_r($photo_size, true);"file_name" 		=> $file_name1, 
		$photo_ele = array(
					"file_path" 		=> $params['savename'],
					"file_size" 		=> $photo_size
		  );

		$insert_data = $this->schoolhealth_school_portal_model->get_qr_image_for_student($doc_id, $data, $photo_ele);
		
		if($insert_data == 'EXISTS'){
			$this->output->set_output("QR code already exists");
		}else{
			$this->output->set_output("QR code generated successfully");
		}
	 	
	}
	
}

/* End of file diabetic_care_lab_portal.php */
/* Location: ./application/customers/controllers/diabetic_care_lab_portal.php */