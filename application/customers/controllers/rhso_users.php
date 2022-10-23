<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Rhso_users extends My_Controller {

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
		$this->load->helper('url');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->library('paas_common_lib');
		$this->load->model('ttwreis_mgmt_model');
		$this->load->model('panacea_common_model');
		$this->load->model('rhso_users_common_model');
		$this->load->library('rhso_users_common_lib');
		$this->load->library('rhso_common_lib');
		$this->load->library('panacea_common_lib');
	}
	
	/**
	 * 
	 *
	 * @author  Vikas
	 *
	 *
	 */
	public function index()
	{
		redirect('rhso_users/to_dashboard');
	}
	
	public function ttwreis_mgmt_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_states');
		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_states();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_states',$this->data);
	}
	
	public function create_state()
	{
	 	$this->ttwreis_mgmt_model->create_state($_POST);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_states');
	}
	 
	public function ttwreis_mgmt_delete_states($st_id)
	{
	 	$this->ttwreis_mgmt_model->delete_state($st_id);
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_states');
	} 
	
	public function ttwreis_mgmt_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_district');
        $this->data = $this->rhso_users_common_lib->ttwreis_mgmt_district();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_dist',$this->data);
	}
	
	public function create_district()
	{
	 	$this->ttwreis_mgmt_model->create_district($_POST);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_district');
	}
	 
	public function ttwreis_mgmt_delete_dists($dt_id)
	{
	 	$this->ttwreis_mgmt_model->delete_district($dt_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_district');
	}
	
	public function ttwreis_mgmt_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_health_supervisors');
        //$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_health_supervisors();
		$this->data['health_supervisors'] = $this->ttwreis_common_model->get_all_health_supervisors();
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->data['health_supervisorscount'] = $this->ttwreis_common_model->health_supervisorscount();
						
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_health_supervisors',$this->data);
	}
	
	public function create_health_supervisors()
	{
	 	$insert = $this->ttwreis_common_model->create_health_supervisors($_POST);
	 	if($insert){
	 		redirect('ttwreis_mgmt/ttwreis_mgmt_health_supervisors');
	 	}else{
	 		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_health_supervisors();
	 		
	 		//$this->data = "";
	 		$this->_render_page('ttwreis_admins/ttwreis_mgmt_health_supervisors',$this->data);
	 	}
	 	
	}
	 
	public function ttwreis_mgmt_delete_health_supervisors($hs_id)
	{
	 	$this->ttwreis_mgmt_model->delete_health_supervisors($hs_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_health_supervisors');
	}
	/////////////////////////////////////////////////////////
	
	public function ttwreis_mgmt_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_doctors');
	
		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_doctors();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_doctors',$this->data);
	}
	
	public function create_doctor()
	{
		$insert = $this->ttwreis_mgmt_model->create_doctor($_POST);
		if($insert){
			redirect('ttwreis_mgmt/ttwreis_mgmt_doctors');
		}else{
			$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_doctors();			
			
			//$this->data = "";
			$this->_render_page('ttwreis_admins/ttwreis_mgmt_doctors',$this->data);
		}
		 
	}
	
	public function ttwreis_mgmt_delete_doctor($hs_id)
	{
		$this->ttwreis_mgmt_model->delete_doctor($hs_id);
		redirect('ttwreis_mgmt/ttwreis_mgmt_doctors');
	}
	
	//////////////////////////////////////////////////////
	
	public function ttwreis_mgmt_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_schools');
        $this->data = $this->rhso_users_common_lib->ttwreis_mgmt_schools();		
		
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_schools',$this->data);
	}
	
	public function create_school()
	{
	 	//$this->ttwreis_mgmt_model->create_school($_POST);	
	 	//redirect('ttwreis_mgmt/ttwreis_mgmt_schools');
	 	
	 	$insert = $this->ttwreis_common_model->create_school($_POST);
	 	if($insert){
	 		redirect('ttwreis_mgmt/ttwreis_mgmt_schools');
	 	}else{
	 		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_schools();
	 			
	 		//$this->data = "";
	 		$this->_render_page('ttwreis_admins/ttwreis_mgmt_schools',$this->data);
	 	}
	}
	 
	public function ttwreis_mgmt_delete_school($school_id)
	{
	 	$this->ttwreis_mgmt_model->delete_school($school_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_schools');
	}
	
	//000000000000000000000000000000000000
	public function ttwreis_mgmt_classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_schools');
        $this->data = $this->rhso_users_common_lib->ttwreis_mgmt_classes();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_classes',$this->data);
	}
	
	public function create_class()
	{
	 	$this->ttwreis_mgmt_model->create_class($_POST);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_classes');
	}
	 
	public function ttwreis_mgmt_delete_class($class_id)
	{
	 	$this->ttwreis_mgmt_model->delete_class($class_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_classes');
	}
	
	public function ttwreis_mgmt_sections()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_schools');
        $this->data = $this->rhso_users_common_lib->ttwreis_mgmt_sections();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_sections',$this->data);
	}
	
	public function create_section()
	{
	 	$this->ttwreis_mgmt_model->create_section($_POST);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_sections');
	}
	 
	public function ttwreis_mgmt_delete_section($section_id)
	{
	 	$this->ttwreis_mgmt_model->delete_section($section_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_sections');
	}
	
	//syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
	public function ttwreis_mgmt_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_symptoms');
        $this->data = $this->rhso_users_common_lib->ttwreis_mgmt_symptoms();
		
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_symptoms',$this->data);
	}
	
	public function create_symptoms()
	{
	 	$this->ttwreis_mgmt_model->create_symptoms($_POST);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_symptoms');
	}
	 
	public function ttwreis_mgmt_delete_symptoms($symptoms_id)
	{
	 	$this->ttwreis_mgmt_model->delete_symptoms($symptoms_id);	
	 	redirect('ttwreis_mgmt/ttwreis_mgmt_symptoms');
	}
	
	public function ttwreis_mgmt_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_diagnostics');
		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_diagnostic();	
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_diagnostics',$this->data);
	}
	
	public function create_diagnostic()
	{
		$this->ttwreis_common_model->create_diagnostic($_POST);
		redirect('ttwreis_mgmt/ttwreis_mgmt_diagnostic');
	}
	
	public function ttwreis_mgmt_delete_diagnostic($diagnostic_id)
	{
		$this->ttwreis_mgmt_model->delete_diagnostic($diagnostic_id);
		redirect('ttwreis_mgmt/ttwreis_mgmt_diagnostic');
	}
	
	
	public function ttwreis_mgmt_hospitals()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_hospitals');
		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_hospitals();
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_hospitals',$this->data);
	}
	
	public function create_hospital()
	{
		$this->ttwreis_common_model->create_hospital($_POST);
		redirect('ttwreis_mgmt/ttwreis_mgmt_hospitals');
	}
	
	public function ttwreis_mgmt_delete_hospital($hospital_id)
	{
		$this->ttwreis_mgmt_model->delete_hospital($hospital_id);
		redirect('ttwreis_mgmt/ttwreis_mgmt_hospitals');
	}
	
	public function ttwreis_mgmt_emp()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_emp');
		$this->data = $this->rhso_users_common_lib->ttwreis_mgmt_emp();
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_emp',$this->data);
	}
	
	public function create_emp()
	{
		$this->ttwreis_mgmt_model->create_emp($_POST);
		redirect('ttwreis_mgmt/ttwreis_mgmt_emp');
	}
	
	public function ttwreis_mgmt_delete_emp($emp_id)
	{
		$this->ttwreis_mgmt_model->delete_emp($emp_id);
		redirect('ttwreis_mgmt/ttwreis_mgmt_emp');
	}
	
	//===============reports======================================
	
	public function ttwreis_reports_ehr()
	{
		$this->data["message"] = "";
		$this->_render_page('ttwreis_admins/ttwreis_reports_ehr',$this->data);
	}
	
	public function ttwreis_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_display_ehr($post);
		
	 	$this->_render_page('ttwreis_admins/ttwreis_reports_display_ehr',$this->data);
	}
	
	public function ttwreis_reports_display_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_display_ehr_uid($post);
	
		$this->_render_page('ttwreis_admins/ttwreis_reports_display_ehr',$this->data);
	}
	
	public function ttwreis_reports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_students');
        $this->data = $this->rhso_users_common_lib->ttwreis_reports_students();
		
		$this->_render_page('ttwreis_admins/ttwreis_reports_students',$this->data);
	}
	
	public function ttwreis_reports_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_doctors');
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_doctors();
	
		$this->_render_page('ttwreis_admins/ttwreis_reports_doctors',$this->data);
	}
	
	public function ttwreis_reports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_hospital');
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_hospital();
	
		$this->_render_page('ttwreis_admins/ttwreis_reports_hospitals',$this->data);
	}
	
	/*public function cro_reports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('cro_reports_school');
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];

		$dist_list = $this->ci->rhso_users_common_model->get_all_district($dt_name);
		$cro_dist = $dist_list[0]['_id'];
		log_message('debug','cro_reports_school============332=='.print_r($cro_dist,true));
		
		$this->data = $this->rhso_users_common_lib->cro_reports_school($cro_dist);
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_reports_schools',$this->data);
	}*/
	
	public function ttwreis_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_symptom');
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_symptom();
	
		$this->_render_page('ttwreis_admins/ttwreis_reports_symptom',$this->data);
	}
	
	// --------------------------------------------------------------------
	
	function student_db_to_excel(){
		ini_set('memory_limit', '1024M');
		$docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWREIS")->get("healthcare2016226112942701");//healthcare2016226112942701
		 
		//log_message('debug','11111111111111111111111111111111111111111111'.print_r($striped_doc,true));
		//log_message('debug','2222222222222222222222222222222222222222222'.print_r(json_encode($striped_doc),true));
		 
		//load the excel library
		$this->load->library('excel');
	
		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);
	
		// Create new PHPExcel object
		echo date('H:i:s') . " Create new PHPExcel object\n";
		$objPHPExcel = new PHPExcel();
	
		// Set properties
		echo date('H:i:s') . " Set properties\n";
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Vikas Singh Chouhan");
		$objPHPExcel->getProperties()->setTitle("ttwreis Health Report");
		$objPHPExcel->getProperties()->setSubject("ttwreis Health Report");
		$objPHPExcel->getProperties()->setDescription("Document collection of ttwreis health check up.");
	
		// Add some data
		echo date('H:i:s') . " Add some data\n";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ttwreis Health Check Up');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Page1');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Personal Information');
		$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'Photo');
		$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'Mobile');
		$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Date of Birth');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Page2');
		$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Personal Information');
		$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'Class');
		$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Section');
		$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'AD No');
		$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('I4', 'Father Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'Date of Exam');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Page3');
		$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'Physical Exam');
		$objPHPExcel->getActiveSheet()->SetCellValue('K4', 'H B');
		$objPHPExcel->getActiveSheet()->SetCellValue('L4', 'Height cms');
		$objPHPExcel->getActiveSheet()->SetCellValue('M4', 'Weight kgs');
		$objPHPExcel->getActiveSheet()->SetCellValue('N4', 'BMI%');
		$objPHPExcel->getActiveSheet()->SetCellValue('O4', 'Pulse');
		$objPHPExcel->getActiveSheet()->SetCellValue('P4', 'B P');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q4', 'Blood Group');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('R2', 'Page4');
		$objPHPExcel->getActiveSheet()->SetCellValue('R3', 'Doctor Check Up');
		$objPHPExcel->getActiveSheet()->SetCellValue('R4', 'Ortho');
		$objPHPExcel->getActiveSheet()->SetCellValue('S4', 'Advice');
		$objPHPExcel->getActiveSheet()->SetCellValue('T4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('U4', 'Postural');
		$objPHPExcel->getActiveSheet()->SetCellValue('V4', 'Check the box if normal else describe abnormalities');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('W2', 'Page5');
		$objPHPExcel->getActiveSheet()->SetCellValue('W3', 'Doctor Check Up');
		$objPHPExcel->getActiveSheet()->SetCellValue('W4', 'Defects at Birth');
		$objPHPExcel->getActiveSheet()->SetCellValue('X4', 'Deficencies');
		$objPHPExcel->getActiveSheet()->SetCellValue('Y4', 'Childhood Diseases');
		$objPHPExcel->getActiveSheet()->SetCellValue('Z4', 'N A D');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('AA2', 'Page6');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA3', 'Without Glasses');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AB4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC3', 'With Glasses');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AD4', 'Left');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('AE2', 'Page7');
		$objPHPExcel->getActiveSheet()->SetCellValue('AE3', 'Colour Blindness');
		$objPHPExcel->getActiveSheet()->SetCellValue('AE4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AF4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AG4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('AH4', 'Referral Made');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('AI2', 'Page8');
		$objPHPExcel->getActiveSheet()->SetCellValue('AI3', ' Auditory Screening');
		$objPHPExcel->getActiveSheet()->SetCellValue('AI4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AJ4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AK4', 'Speech Screening');
		$objPHPExcel->getActiveSheet()->SetCellValue('AL4', 'Referral Made');
		$objPHPExcel->getActiveSheet()->SetCellValue('AM4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('AN4', 'D D and disablity');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('AO2', 'Page9');
		$objPHPExcel->getActiveSheet()->SetCellValue('AO3', 'Dental Check-up');
		$objPHPExcel->getActiveSheet()->SetCellValue('AO4', 'Oral Hygiene');
		$objPHPExcel->getActiveSheet()->SetCellValue('AP4', 'Carious Teeth');
		$objPHPExcel->getActiveSheet()->SetCellValue('AQ4', 'Flourosis');
		$objPHPExcel->getActiveSheet()->SetCellValue('AR4', 'Orthodontic Treatment');
		$objPHPExcel->getActiveSheet()->SetCellValue('AS4', 'Indication for extraction');
		$objPHPExcel->getActiveSheet()->SetCellValue('AT4', 'Result');
		$objPHPExcel->getActiveSheet()->SetCellValue('AU4', 'Referral Made');
	
	
		//====================values==================================================
		$row = 5;
		foreach ($docs as $doc)
		{
			//if($row <16){
			//foreach ($doc["doc_data"]["widget_data"] as $page_no => $page)
			{
				//foreach ($page as $sec_name => $sec)
				{
					//=======================================
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']))
							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']);
							
						if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_path']))
							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, URLCustomer.$doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_path']);
							
						if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Mobile']['mob_num']))
							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Mobile']['mob_num']);
							
						if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Date of Birth']))
							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Date of Birth']);
					}
	
	
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Class']))
							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Class']);
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Section']))
							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Section']);
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['AD No']))
							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['AD No']);
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['School Name']))
							$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['School Name']);
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Father Name']))
							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Father Name']);
						if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Date of Exam']))
							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Date of Exam']);
	
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['H B']))
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['H B']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Height cms']))
							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Height cms']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Weight kgs']))
							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Weight kgs']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['BMI%']))
							$objPHPExcel->getActiveSheet()->SetCellValue('N'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['BMI%']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Pulse']))
							$objPHPExcel->getActiveSheet()->SetCellValue('O'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Pulse']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['B P']))
							$objPHPExcel->getActiveSheet()->SetCellValue('P'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['B P']);
						if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Blood Group']))
							$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Blood Group']);
							
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']))
							$objPHPExcel->getActiveSheet()->SetCellValue('R'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']));
						if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']))
							$objPHPExcel->getActiveSheet()->SetCellValue('S'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']);
						if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']))
							$objPHPExcel->getActiveSheet()->SetCellValue('T'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']);
						if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']))
							$objPHPExcel->getActiveSheet()->SetCellValue('U'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']));
						if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']))
							$objPHPExcel->getActiveSheet()->SetCellValue('V'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']));
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]))
					{
						//log_message('debug','doctorrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth'],true));
						if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']))
							$objPHPExcel->getActiveSheet()->SetCellValue('W'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']));
						if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']))
							$objPHPExcel->getActiveSheet()->SetCellValue('X'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']));
						if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']))
							$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']));
						if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']))
							$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']));
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$row, $doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']);
						if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$row, $doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']);
						if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$row, $doc["doc_data"]["widget_data"]["page6"]["With Glasses"]['Right']);
						if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$row, $doc["doc_data"]["widget_data"]["page6"]["With Glasses"]['Left']);
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Right']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Right']);
						if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Left']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Left']);
						if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Description']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Description']);
						if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']));
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']);
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']);
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']));
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']));
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']);
						if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AN'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']));
					}
	
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]))
					{
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Oral Hygiene']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AO'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Oral Hygiene']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Carious Teeth']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AP'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Carious Teeth']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Flourosis']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Flourosis']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Orthodontic Treatment']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AR'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Orthodontic Treatment']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Indication for extraction']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AS'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Indication for extraction']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Result']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AT'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Result']);
						if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']))
							$objPHPExcel->getActiveSheet()->SetCellValue('AU'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']));
					}
	
					//---------------------------------------
				}
			}
				
			if(!empty($doc["doc_data"]["external_attachments"])){
				log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($doc["doc_data"]["external_attachments"],true));
				$objPHPExcel->getActiveSheet()->SetCellValue('AV2', 'External Attachments');
				$i = 1;
				foreach($doc["doc_data"]["external_attachments"] as $attachment){
					$objPHPExcel->getActiveSheet()->SetCellValue('AV4', 'Attachment_'.$i);
					log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($attachment,true));
					if(isset($attachment['file_path']))
						$objPHPExcel->getActiveSheet()->SetCellValue('AV'.$row, URLCustomer.$attachment['file_path']);
					$i++;
				}
			}
				
			$row ++;
		}
	
		// Save Excel 2007 file
		//echo date('H:i:s') . " Write to Excel2007 format\n";
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(EXCEL."/ttwreis_Health_Report.xlsx");
	
		$this->secure_file_download(EXCEL."/ttwreis_Health_Report.xlsx");
	
		unlink(EXCEL."/ttwreis_Health_Report.xlsx");
	}
	
	public function secure_file_download($path)
	{
		$path = str_replace('=','/',$path);
		$this->external_file_download($path);
	}
	
	function to_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard');
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];
		$this->data = $this->rhso_users_common_lib->to_dashboard($date, $request_duration, $screening_duration,$dt_name);
		$this->_render_page('rhso_users/cro_admin_dash', $this->data);
	
	}
	
	public function chronic_report_graph()
	{
		$this->check_for_admin();
		$this->check_for_plan('chronic_report_graph');
		$this->data = $this->rhso_users_common_lib->chronic_pie_view();
		$this->_render_page('rhso_users/cro_chronic_report_graph', $this->data);
	}

	public function update_chronic_request_pie(){
		
		$this->check_for_admin();
		$this->check_for_plan('update_chronic_request_pie');
		$status_type = $_POST["status_type"];
		$this->data = $this->rhso_users_common_lib->update_chronic_request_pie($status_type);
		
		$this->output->set_output(json_encode($this->data));
	}
	function drill_down_request_to_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_symptoms');
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];

		$symptoms_report = json_encode($this->rhso_users_common_model->drill_down_request_to_symptoms($data,$status_type,$dt_name));
		$this->output->set_output($symptoms_report);
	}

	/*function drilldown_chronic_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_districts');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];
		$identifiers_report = json_encode($this->rhso_users_common_model->drilldown_chronic_request_to_districts($data,$status_type,$dt_name));
		$this->output->set_output($identifiers_report);
	}
*/
	function drilldown_chronic_request_to_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_school');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];
		$request_report = json_encode($this->rhso_users_common_model->drilldown_chronic_request_to_schools($data,$status_type, $dt_name));
		$this->output->set_output($request_report);
	}

	function drilldown_chronic_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_students');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$session = $this->session->userdata('customer');
		$dt_name = $session['dt_name'];
		$docs = $this->rhso_users_common_model->drilldown_chronic_request_to_students($data,$status_type,$dt_name);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
		function drill_down_chronic_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_chronic_request_to_students_load_ehr');
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		$get_docs = $this->rhso_users_common_model->get_drilling_screenings_students_docs($docs_id);
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		//$doc_list = $this->panacea_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		//$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_users/drill_down_absent_to_students_load_ehr',$this->data);
	}
	function to_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		log_message('debug','opppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$this->data = $this->rhso_users_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$this->data = $this->rhso_users_common_lib->update_request_pie($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->panacea_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->ttwreis_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->ttwreis_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	function drilling_screening_to_abnormalities_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_screening_to_abnormalities_pie');
		$data = $_POST['data'];
		$schoolEmail = $_POST['schoolEmail'];
		
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_abnormalities_to_pie($data, $schoolEmail/*,$today_date,$screening_pie_span*/));
		$this->output->set_output($screening_report);
	}
	function drill_screening_to_students_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_screening_to_students_pie');
		/*$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];*/
		$symptome_type = $_POST['data'];
		//$symptome_type = (int) $symptome_type;
		$schoolEmail = $_POST['schoolEmail'];
		
		$docs = $this->panacea_common_model->get_drilling_screenings_students_count(/*$data,$today_date,$screening_pie_span*/$symptome_type, $schoolEmail);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->panacea_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->panacea_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_users/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$this->data['docs'] = $this->ttwreis_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('rhso_users/cro_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->ttwreis_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
	
		$this->data['doc'] = $this->ttwreis_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('ttwreis_admins/ttwreis_reports_display_ehr',$this->data);
	}
	
	function drilldown_absent_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_absent_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		//$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$loggedinuser = $this->session->userdata("customer");
		$district_name        = $loggedinuser['dt_name'];

		$absent_report = json_encode($this->rhso_users_common_model->drilldown_absent_to_districts($data,$today_date,$district_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drilling_absent_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_absent_to_schools');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$loggedinuser = $this->session->userdata("customer");
		$district_name = $loggedinuser['dt_name'];
		$school_name = $_POST["school_name"];
		$absent_report = json_encode($this->rhso_users_common_model->get_drilling_absent_schools($data,$today_date,$district_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		//$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];

		$loggedinuser = $this->session->userdata("customer");
		$district_name        = $loggedinuser['dt_name'];

		$docs = $this->rhso_users_common_model->get_drilling_absent_students($data,$today_date,$district_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_absent']),true);
		$get_docs = $this->rhso_users_common_model->get_drilling_absent_students_docs($UI_id);
		
		$navigation = $_POST['ehr_navigation_for_absent'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_users/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_districts');
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnnnnpie drillllllllllllllllllllllllll".print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$request_report = json_encode($this->rhso_users_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern));
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnnnnpie ppppppppppppppppppppppppppppppppppppppppp".print_r($request_report,true));
		$this->output->set_output($request_report);
	}
	
	function drilling_request_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_request_to_schools');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$request_report = json_encode($this->rhso_users_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$docs = $this->rhso_users_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern);
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request']),true);
		$get_docs = $this->rhso_users_common_model->get_drilling_request_students_docs($UI_id);

		$navigation = $_POST['ehr_navigation_for_request'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('rhso_users/drill_down_request_to_students_load_ehr',$this->data);
	}
	//========================================================================
	
	//==================id===========================================================
	
	function drilldown_identifiers_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_identifiers_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$identifiers_report = json_encode($this->rhso_users_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern));
		$this->output->set_output($identifiers_report);
	}
	
	function drilling_identifiers_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_identifiers_to_schools');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$identifiers_report = json_encode($this->rhso_users_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students');
		$data = $_POST['data'];
	
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code_pattern = $loggedinuser['district_code'];
		
		$docs = $this->rhso_users_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$cro_district_code_pattern);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_identifiers']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_identifiers']),true);
		
		$get_docs = $this->rhso_users_common_model->get_drilling_identifiers_students_docs($UI_id);
		$navigation = $_POST['ehr_navigation_for_identifiers'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('rhso_users/drill_down_identifiers_to_students_load_ehr',$this->data);
	}
	
	//============================================================================================================
		
	function ttwreis_imports_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_diagnostic');
		$this->data = $this->rhso_users_common_lib->ttwreis_imports_diagnostic();
	
		$this->_render_page('ttwreis_admins/ttwreis_imports_diagnostic', $this->data);
	}
	
	function import_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_diagnostic');
	
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->import_diagnostic($post);
		
		if($this->data == "redirect_to_diagnostic_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_mgmt_diagnostic');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_diagnostic', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_diagnostic', $this->data);
		}
		
	}
	
	function ttwreis_imports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_hospital');
	
		$this->data = $this->rhso_users_common_lib->ttwreis_imports_hospital();
	
		$this->_render_page('ttwreis_admins/ttwreis_imports_hospital', $this->data);
	}
	
	function import_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_hospital');
		
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->import_hospital($post);
		
		if($this->data == "redirect_to_hospital_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_mgmt_hospitals');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_hospital', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_hospital', $this->data);
		}
	}
	
	function ttwreis_imports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_school');
	
		$this->data = $this->rhso_users_common_lib->ttwreis_imports_school();
	
		$this->_render_page('ttwreis_admins/ttwreis_imports_school', $this->data);
	}
	
	function import_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->import_school($post);
		
		if($this->data == "redirect_to_school_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_mgmt_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_school', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_school', $this->data);
		}
	}
	
	function ttwreis_imports_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_health_supervisors');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('ttwreis_admins/ttwreis_imports_health_supervisors', $this->data);
	}
	
	function import_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
	
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->import_health_supervisors($post);
		
		if($this->data == "redirect_to_hs_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_mgmt_health_supervisors');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_health_supervisors', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_health_supervisors', $this->data);
		}
	}
	
	function ttwreis_imports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_students');
		
		$this->data['distslist'] = $this->ttwreis_common_model->get_all_district();
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
	}
	
	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');
		
		$post = $_POST;
		$this->data = $this->rhso_users_common_lib->import_students($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}
	}
	
	function update_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('undate_students');
		
		$this->data = $this->rhso_users_common_lib->update_students();
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('ttwreis_mgmt/ttwreis_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_admins/ttwreis_imports_students', $this->data);
		}
	}
	
	function read_excel()
	{
		exit();
		$row_value = 0;
		$arr_count = 0;
		//$header_array = array("Admission No","Student name","Student Addres","Class","Section","Gender","Age","DOB","District","School Contact Number","Students Parent/Guardian Contact Number");
	
		$header_array = array("Admn.No","Student Name","Student Address","Class","Section","Gender","Age","DOB","District","School Contact Num","Parentes Ph.Num");
	
		$file = EXCEL.'/MBNR-Jadcherla MG.xlsx';
		//$file = 'E:/TLSTEC/Program Files/wamp/www/PaaS/bootstrap/dist/excel/Mahendrahills.xls';
	
		//load the excel library
		$this->load->library('excel');
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		//extract to a PHP readable array format
		/* //log_message("debug","entered into read_excel cell_collection".print_r($cell_collection,true)); */
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			$arr_count++;
				
			if (in_array($data_value, $header_array)) {
				$header[$row][$column] = $data_value;
				$row_value = $row;
			}
			else if($row_value > 0)
			{
				log_message('debug','$header[$row_value][$column]======140'.print_r($header[$row_value][$column],true));
				if($header[$row_value][$column] == "Parentes Ph.Num")
				{
					$data_value = substr($data_value,0,10);
				}
	
				if($header[$row_value][$column] == "DOB")
				{
					//$data_value = PHPExcel_Style_NumberFormat::toFormattedString($data_value, "YYYY-MM-DD");
					$date = new DateTime($data_value);
					$data_value= $date->format('Y-m-d');
				}
				$arr_data[$row][$header[$row_value][$column]] = $data_value;
	
			}
	
		}
			
		$doc_data = array();
		$form_data = array();
		$count = 0;
			
		for($j=$row_value+1;$j<count($arr_data);$j++){
	
	
			$doc_data['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['Student Name'];
			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = "";
			$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['DOB'];
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '91';
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['Parentes Ph.Num'];
			$doc_data['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['Admn.No'];
			$doc_data['widget_data']['page2']['Personal Information']['Class'] = $arr_data[$j]['Class'];
			$doc_data['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['Section'];
			$doc_data['widget_data']['page2']['Personal Information']['District'] = $arr_data[$j]['District'];
			$doc_data['widget_data']['page2']['Personal Information']['School Name'] = 'TSWRS-MG,JADCHERLA';
			$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = '';
			$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = '';
			$doc_data['widget_data']['page3'] = [];
			$doc_data['widget_data']['page4'] = [];
			$doc_data['widget_data']['page5'] = [];
			$doc_data['widget_data']['page6'] = [];
			$doc_data['widget_data']['page7'] = [];
			$doc_data['widget_data']['page8'] = [];
			$doc_data['widget_data']['page9'] = [];
				
			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "ttwreis";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";
				
			$history['last_stage']['current_stage'] = "stage1";
			$history['last_stage']['approval'] = "true";
			$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
			$history['last_stage']['time'] = date("Y-m-d H:i:s");
				
			$this->load->model('Workflow_Model');
			$this->Workflow_Model->insert_excel_data($doc_data,$history,$doc_properties);
			$count++;
		}
			
		////log_message("debug","entered into read_excel form_data".print_r($form_data,true));
	}
	
	//==============================CC mgmt=================================
	public function ttwreis_mgmt_cc()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_mgmt_cc');
	
		$total_rows = $this->ttwreis_mgmt_model->cc_users_count();
	
		//---pagination--------//
		$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->pagination->initialize($config);
	
		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['cc_users'] = $this->ttwreis_mgmt_model->get_cc_users($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->data['cc_count'] = $total_rows;
	
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_mgmt_cc_users',$this->data);
	}
	
	public function create_cc_user()
	{
		log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppppppp.'.print_r($_POST,true));
		$insert = $this->ttwreis_mgmt_model->create_cc_user($_POST);
		log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii.'.print_r($insert,true));
		if($insert){
			redirect('ttwreis_mgmt/ttwreis_mgmt_cc');
		}else{
			log_message('debug','errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($this->ttwreis_mgmt_model->errors(),true));
			$total_rows = $this->ttwreis_mgmt_model->cc_users_count();
	
			//---pagination--------//
			$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
		
			//Initialize the pagination class
			$this->pagination->initialize($config);
		
			//control of number page
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		
			//find all the categories with paginate and save it in array to past to the view
			$this->data['cc_users'] = $this->ttwreis_mgmt_model->get_cc_users($config['per_page'], $page);
			//create paginate´s links
			$this->data['links'] = $this->pagination->create_links();
		
			//number page variable
			$this->data['page'] = $page;
	
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ttwreis_mgmt_model->errors() ? $this->ttwreis_mgmt_model->errors() : $this->session->flashdata('message')));
			
			$this->data['cc_count'] = $total_rows;
	
			//$this->data = "";
			$this->_render_page('ttwreis_admins/ttwreis_mgmt_cc_users',$this->data);
		}
		 
	}
	
	public function ttwreis_mgmt_delete_cc_user($cc_id)
	{
		$this->ttwreis_mgmt_model->delete_cc_user($cc_id);
		redirect('ttwreis_mgmt/ttwreis_mgmt_cc');
	}
	
	public function ttwreis_reports_students_filter()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_students_filter');
		$this->data = $this->rhso_users_common_lib->ttwreis_reports_students_filter();
	
		//$this->data = "";
		$this->_render_page('ttwreis_admins/ttwreis_reports_students_filter',$this->data);
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->rhso_users_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_students_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_students_list');
	
		$school_name = $_POST['school_name'];
		$dist_name = $_POST['dist_name'];
	
		$this->data = $this->ttwreis_common_model->get_students_by_school_name($school_name,$dist_name);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function pie_export()
	{
		$this->data['today_date'] = date('Y-m-d');
		$this->data['distslist'] = $this->ttwreis_common_model->get_all_district();
		$this->_render_page('ttwreis_admins/ttwreis_pie_export',$this->data);
	}
	
	public function generate_excel_for_absent_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_users_common_lib->generate_excel_for_absent_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_request_pie()
	{
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_users_common_lib->generate_excel_for_request_pie($today_date,$request_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_screening_pie()
	{
		log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($_POST,true));
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_users_common_lib->generate_excel_for_screening_pie($today_date,$screening_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	function screening_pie_data_for_stage4_new(){
		$this->ttwreis_common_model->screening_pie_data_for_stage4_new();
	}
	
	function forward_request(){
		$this->rhso_users_common_lib->submit_request_to_doctor($_POST);
		redirect('ttwreis_mgmt/to_dashboard');
	}
	
	function ttwreis_create_group()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_create_group');
	
		$this->data = $this->rhso_users_common_lib->ttwreis_groups();
	
		$this->_render_page('ttwreis_admins/create_group', $this->data);
	}
	public function create_group()
	{
		$this->ttwreis_mgmt_model->create_group($_POST);
		redirect('ttwreis_mgmt/ttwreis_create_group');
	}
	public function save_users_to_group()
	{
		log_message("debug","ggggggggggrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr=======".print_r($_POST,true));
		$this->ttwreis_mgmt_model->save_users_to_group($_POST);
		
		redirect('ttwreis_mgmt/group_msg');
	}
	public function delete_group($_id)
	{
		$this->ttwreis_mgmt_model->delete_chat_group($_id);
		redirect('ttwreis_mgmt/ttwreis_create_group');
	}
	
	function group_msg()
	{
		$this->check_for_admin();
		$this->check_for_plan('group_msg');
	
		$this->data = $this->rhso_users_common_lib->group_msg();
	
		$this->_render_page('ttwreis_admins/group_msg', $this->data);
	}
	
	function get_messages($msg_id,$message = false)
	{
		$this->check_for_admin();
		$this->check_for_plan('get_messages');
	
		if($message === false){
			log_message('debug','hereeeeeeeeeeeeeeeeeeeeeeeeeee================================.'.print_r($msg_id,true));
			$result = $this->ttwreis_common_model->get_messages($msg_id);
			$response["messages"] = array();
			$response['chat_room'] = array();
			
			$i = 0;
			foreach ($result as $message){
				// adding chat room node
				if ($i == 0) {
					$tmp = array();
					$tmp["chat_room_id"] = $message["chat_room_id"];
					$tmp["name"] = $message["user_id"];
					$tmp["created_at"] = $message["created_at"];
					$response['chat_room'] = $tmp;
					$i++;
				}
				if ($message['user_id'] != NULL) {
					// message node
					$cmt = array();
					$cmt["message"] = $message["message"];
					$cmt["message_id"] = $message["message_id"];
					$cmt["created_at"] = $message["created_at"];
				
					// user node
					$user = array();
					$user['user_id'] = $message['user_id'];
					$user['username'] = $message['user_id'];
					$cmt['user'] = $user;
				
					array_push($response["messages"], $cmt);
				}
			}
			
			$response["error"] = false;
			$this->data = $response;
		}else{
			$this->data = $this->ttwreis_common_model->add_message($_POST,$msg_id);
			
			//+++++++++++++++++++++GCM part +++++++++++++++++++++++++
			if ($this->data['error'] == false) {
				// get the user using userid
				$user = $this->get_user();
			
				$data = array();
				$data['user'] = $user;
				$data['message'] = $this->data['message'];
				$data['chat_room_id'] = $msg_id;
			
				$this->push->setTitle("DashBoard");
				$this->push->setIsBackground(FALSE);
				$this->push->setFlag(PUSH_FLAG_CHATROOM);
				$this->push->setData($data);
			
				// echo json_encode($push->getPush());exit;
				// sending push message to a topic
				$this->gcm->sendToTopic('topic_' . $msg_id, $this->push->getPush());
			
				$this->data['user'] = $user;
				$this->data['error'] = false;
			}
		}
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function get_user()
	{
		$customer = $this->session->userdata("customer");
		log_message('debug','mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm===========================.'.print_r($customer,true));
		
// 		DEBUG - 2016-11-01 12:21:34 --> mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm===========================.Array
// 		(
// 		[identity] => ttwreis.user@gmail.com
// 		[username] => PANACEA Admin
// 		[email] => ttwreis.user@gmail.com
// 		[user_id] => 55321300a372617c1b000031
// 		[old_last_login] => 2016-05-17 07:21:48
// 		[company] => healthcare
// 		[plan] => Diamond
// 		[registered] => 2015-04-18
// 		[expiry] => 2019-04-18
// 		[user_type] => PADMIN
// 		)
		
		$user["user_id"] = $customer['identity'];
		$user["name"] = $customer['username'];
		$user["email"] = $customer['email'];
//		$user["gcm_registration_id"] = $gcm_registration_id;
		$user["created_at"] = $customer['registered'];
		
		return $user;
	}
	
	function user_msg()
	{
		$this->check_for_admin();
		$this->check_for_plan('user_msg');
	
		$this->data = $this->rhso_users_common_lib->user_msg();
	
		$this->_render_page('ttwreis_admins/user_msg', $this->data);
	}
	function users($user_id,$message){
		$user_id = base64_decode($user_id);
		
		$from_user_id = $_POST['user_id'];
		$message = $_POST['message'];
		
		$fromuser = $this->get_user();
		//$user = $db->getUser($to_user_id);
		$user['gcm_registration_id'] = "";
		
		$msg = array();
		$msg['message'] = $message;
		$msg['message_id'] = '';
		$msg['chat_room_id'] = '';
		$msg['created_at'] = date('Y-m-d H:i:s');
		
		$data = array();
		$data['user'] = $fromuser;
		$data['message'] = $msg;
		$data['image'] = '';
		
		$this->push->setTitle("TLSTEC");
		$this->push->setIsBackground(FALSE);
		$this->push->setFlag(PUSH_FLAG_USER);
		$this->push->setData($data);
		
		// sending push message to single user
		$this->gcm->send($user['gcm_registration_id'], $this->push->getPush());
		
		$response['user'] = $user;
		$response['error'] = false;
		
		$this->output->set_output(json_encode($response));		
	}
	
	function multi_msg()
	{
		$this->check_for_admin();
		$this->check_for_plan('user_msg');
	
		$this->data = $this->rhso_users_common_lib->user_msg();
	
		$this->_render_page('ttwreis_admins/multi_user_msg', $this->data);
	}
	function multi_users($message){
	
		$user_id = $_POST['user_id'];
		$to_user_ids = array_filter(explode(',', $_POST['to']));
		$message = $_POST['message'];
	
		$user = $this->get_user();
		//$user = $db->getUser($to_user_id);
		$users = [];
		foreach ($to_user_ids as $to_user_id){
			$temp['gcm_registration_id'] = "";
			array_push($users, $temp);
		}
		
		$registration_ids = array();
		// preparing gcm registration ids array
		foreach ($users as $u) {
			array_push($registration_ids, $u['gcm_registration_id']);
		}
	
		// creating tmp message, skipping database insertion
	    $msg = array();
	    $msg['message'] = $message;
	    $msg['message_id'] = '';
	    $msg['chat_room_id'] = '';
	    $msg['created_at'] = date('Y-m-d H:i:s');
	
		$data = array();
	    $data['user'] = $user;
	    $data['message'] = $msg;
	    $data['image'] = '';
	
		$this->push->setTitle("TLSTEC");
		$this->push->setIsBackground(FALSE);
		$this->push->setFlag(PUSH_FLAG_USER);
		$this->push->setData($data);
	
		// sending push message to multiple users
    	$this->gcm->sendMultiple($registration_ids, $this->push->getPush());
	
		$response['error'] = false;
	
		$this->output->set_output(json_encode($response));
	}
	
	// SANITATION INFRASTRUCTURE
	public function get_sanitation_infrastructure()
	{
		// Variables
		$toilets            = array();
		$hand_sanitizers    = array();
		$disposable_bins    = array();
		$water_dispensaries = array();
		$children_seating   = array();
		$bar_chart_data     = array();
		
		//POST DATA
		$district_name  = $this->input->post('district_name',TRUE);
		$school_name    = $this->input->post('school_name',TRUE);
		
		$data = $this->rhso_users_common_model->get_sanitation_infrastructure_model($district_name,$school_name);
		
		if(isset($data) && !empty($data))
		{
		foreach($data as $index => $value)
		{
			$widget_data = $value['doc_data']['widget_data'];
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			$page5 = $widget_data['page5'];
			$page6 = $widget_data['page6'];
			
			// Toilets
			$buckets = array();
			$buckets['label'] = 'Buckets';
			$buckets['value'] = (int) $page1['Toilets']['Buckets'];
			array_push($toilets,$buckets);
			
			$mugs = array();
			$mugs['label'] = 'Mugs';
			$mugs['value'] = (int) $page1['Toilets']['Mugs'];
			array_push($toilets,$mugs);
			
			$dust_bin = array();
			$dust_bin['label'] = 'Dust Bins';
			$dust_bin['value'] = (int) $page1['Toilets']['Dust Bins'];
			array_push($toilets,$dust_bin);
			
			$soap = array();
			$soap['label'] = 'Soap';
			$soap['value'] = (int) $page1['Toilets']['Soap'];
			array_push($toilets,$soap);
			
			$running_water = array();
			$running_water['label'] = 'Running Water';
			$running_water['value'] = (int) $page4['Water Facility']['Running water(number of taps)'];
			array_push($toilets,$running_water);
			
			$store_water = array();
			$store_water['label'] = 'Store Water';
			$store_water['value'] = (int) $page4['Water Facility']['Store water'];
			array_push($toilets,$store_water);
			
			// Hand Sanitizers
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page2['Hand Wash']['Dining Halls'];
			array_push($hand_sanitizers,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page2['Hand Wash']['Kitchen'];
			array_push($hand_sanitizers,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Kitchen';
			$classroom['value'] = (int) $page2['Hand Wash']['Class Rooms'];
			array_push($hand_sanitizers,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page2['Hand Wash']['Dormitories'];
			array_push($hand_sanitizers,$dormitories);
			
			// Disposable Bins
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page3['Waste Management']['Dining Halls'];
			array_push($disposable_bins,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page3['Waste Management']['Kitchen'];
			array_push($disposable_bins,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page3['Waste Management']['Class Rooms'];
			array_push($disposable_bins,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page3['Waste Management']['Dormitories'];
			array_push($disposable_bins,$dormitories);
			
			// Water Dispensaries
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page4['Water Facility']['Dining Halls'];
			array_push($water_dispensaries,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page4['Water Facility']['Kitchen'];
			array_push($water_dispensaries,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page4['Water Facility']['Class Rooms'];
			array_push($water_dispensaries,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page4['Water Facility']['Dormitories'];
			array_push($water_dispensaries,$dormitories);
			
			// Children Seating
			$floor = array();
			$floor['label'] = 'Floor';
			$floor['value'] = (int) $page5['Dining Hall']['Floor'];
			array_push($children_seating,$floor);
			
			$table_chairs = array();
			$table_chairs['label'] = 'Table and Chairs';
			$table_chairs['value'] = (int) $page5['Dining Hall']['Table and Chairs'];
			array_push($children_seating,$table_chairs);
			
			$benches = array();
			$benches['label'] = 'Benches';
			$benches['value'] = (int) $page5['Dining Hall']['Benches'];
			array_push($children_seating,$benches);
		}
		
		$bar_chart_data['toilets']            = $toilets;
		$bar_chart_data['hand_sanitizers']    = $hand_sanitizers;
		$bar_chart_data['disposable_bins']    = $disposable_bins;
		$bar_chart_data['water_dispensaries'] = $water_dispensaries;
		$bar_chart_data['children_seating']   = $children_seating;
		}
		
		if(!empty($bar_chart_data))
		{
			$this->output->set_output(json_encode($bar_chart_data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get data to draw sanitation report pie based on the selected criteria
	 *
	 *@author Selva 
	 */
	 
	function draw_sanitation_report_pie()
	{
	  // POST Data
	  $date 			= $this->input->post('date',TRUE);
	  $search_criteria  = $this->input->post('que',TRUE);
	  $opt              = $this->input->post('opt',TRUE);
	  
	  $search_criteria  = str_replace('#','.',$search_criteria);
	  $search_criteria  = str_replace('_',' ',$search_criteria);
	 
	  $search_criteria  = "doc_data.widget_data.".$search_criteria;
	  $session = $this->session->userdata('customer');
	  $dt_name = $session['dt_name'];
	  $sanitation_report_pie = $this->rhso_users_common_model->get_sanitation_report_pie_data($date,$search_criteria,$opt,$dt_name);
	  
	  if($sanitation_report_pie)
	  {
	    $this->output->set_output(json_encode($sanitation_report_pie));
	  }
	  else
	  {
        $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the absent report sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_absent_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);

	   $date  = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->rhso_users_common_lib->generate_excel_for_absent_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the absent report not sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_absent_not_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->rhso_users_common_lib->generate_excel_for_absent_not_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the sanitation report sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_sanitation_report_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->rhso_users_common_lib->generate_excel_for_sanitation_report_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the sanitation report not sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_sanitation_report_not_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->rhso_users_common_lib->generate_excel_for_sanitation_report_not_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	function upgrade_class()
	{
	   // POST Data
	   $school_name = $_POST['school_name'];
	   $last_class   = $_POST['class_select'];
	   $dist_name   = $_POST['select_dt_name'];
	   // DEBUG - 2017-01-19 07:55:33 --> pppppppppppppppppppppppppppppppppppppppppppppArray
		// (
			// [select_dt_name] => 5732d8b7dbe782f13c760e3b
			// [school_name] => TSWREIS GAJWEL(G),MEDAK
			// [class_select] => 10
			// [submit] => 
		// )
	   
	   $students_data = $this->ttwreis_common_model->get_students_by_school_name($school_name,$dist_name);
	   ////log_message('debug','0000000000000000000000000000000000000000000000..'.print_r($students_data,true));
	   foreach ( $students_data as $student){
		   $class = $student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"];
		   
		   ////log_message('debug','school_data111111111111111111111111111..'.print_r($student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"],true));
		   // if(($class == $last_class)){
			   // $class = "2016 ".$last_class." passed out";
		   // }else 
			if((intval($class) == 10)){
			   $class = "2016"." 10th passed out";
		   }else if((intval($class) == 12)){
			   $class = "2016"." 12th passed out";
		   }else{
			   if(isset($class) && !empty($class) && ($class != "") && ($class != " ")){
				   $class++;
			   }
		   }
		   $student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"] = $class;
		   ////log_message('debug','school_data222222222222222222222222222..'.print_r($student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"],true));
		   $doc_id = $student['_id'];
		   //-------disabled-------------
		   $update_data = $this->ttwreis_common_model->update_student_data(array('doc_data.widget_data.page2.Personal Information.Class' => $class),$doc_id);
	   }
	   
	   
	   redirect('ttwreis_mgmt/ttwreis_reports_students_filter');
	}
	
	public function post_note() {
		
		$post = $_POST;
		
		$token = $this->rhso_users_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() {
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->rhso_users_common_lib->delete_ehr_note($doc_id);
	   
		$this->output->set_output($token);
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
	  $medication_schedule = $this->input->post('medication_schedule',TRUE);
	  $start_date    	   = $this->input->post('start_date',TRUE);
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
		
	  log_message('debug','TSWREIS_SCHOOLS=====CREATE_SCHEDULE_FOLLOWUP=====$_POST==>'.print_r($_POST,true));
	  
	  $is_created = $this->rhso_users_common_model->create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames);
	  
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
	  $selected_date    = $this->input->post('selected_date',TRUE);
	  
	  //log_message('debug','update_schedule_followup==1='.print_r($_POST,true));
	  //log_message('debug','update_schedule_followup==2='.print_r($medication_taken,true));
	  
	  $compliance = $this->rhso_users_common_model->calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken);
	  
	  //log_message('debug','update_schedule_followup==3='.print_r($compliance,true));
	  
	  $is_updated = $this->rhso_users_common_model->update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date);
	  
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
		
	  $pcompl_raw_data   = $this->rhso_users_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	  
	   
	   $final_graph_data['start_date']  = $new_start_date;
	   $final_graph_data['end_date']    = $new_end_date;
	   $final_graph_data['graph_data']  = $graph_data;
	   
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
		
	  $pcompl_raw_data   = $this->rhso_users_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	  
	   $final_graph_data['start_date']  = $new_start_date;
	   $final_graph_data['end_date']    = $new_end_date;
	   $final_graph_data['graph_data']  = $month_wise_compliance;
	   
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
	
	
	
		public function docs_comp_open() {
		
		$this->check_for_admin();
		$this->check_for_plan('docs_comp');
		$this->data = "";
		
		$this->_render_page('ttwreis_admins/docs_comp',$this->data);
    }
	
	public function docs_comp() {
		
		$this->check_for_admin();
		$this->check_for_plan('docs_comp');
		$docs_arr = explode(",",$_POST['docs_arr']);
		$docs_arr = array_unique($docs_arr);
		
		$docs = $this->ttwreis_common_model->get_dup_docs($docs_arr);
			
		$this->data['docs'] = json_encode($docs);
		$this->_render_page('ttwreis_admins/docs_compare', $this->data);
		//$this->_render_page('ttwreis_admins/docs_comp',$this->data);
    }
	
	function doc_comp($document1, $document2){
	 	
	 	log_message('debug','iddddddddddddddddddddddddddddddddddddddd'.print_r($document1,true).print_r($document2,true));
	 	$doc1 = $this->ttwreis_common_model->get_document($document1);
	 	$doc2 = $this->ttwreis_common_model->get_document($document2);
	 	
	 	log_message('debug','111111111111111doccccccccccccccccccccccc'.print_r($doc1,true));
	 	log_message('debug','222222222222222doccccccccccccccccccccccc'.print_r($doc2,true));
	 	
// 	 	$docs = $this->mongo_db->get("test_doc_comp");
// 	 	$docs = json_decode(json_encode($docs),true);
// 	 	$doc1 = $docs[0];
// 	 	$doc2 = $docs[1];
	 
	 	unset($doc1["doc_data"]["strokes_data"]);
	 	unset($doc2["doc_data"]["strokes_data"]);
	 
	 	unset($doc1["doc_data"]["consent_data"]);
	 	unset($doc2["doc_data"]["consent_data"]);
	 
	 	//unset($doc1["_id"]);
	 	//unset($doc2["_id"]);
	 
	 	unset($doc1["doc_data"]["position_data"]);
	 	unset($doc2["doc_data"]["position_data"]);
	 	 
	 	$check = strcmp(serialize($doc1), serialize($doc2));
	 	 
	 	$diff_str = str_replace($doc1, "", $doc2);
	 
	 	log_message('debug','comppppppppppppppppppppppppppppp'.print_r($diff_str,true));
	 
	 	//foreach($doc2["doc_data"]["widget_data"] as $doc_page)
	 	{
	 		//echo print_r($doc_page,true);
	 		//echo "\n========================================================================================\n";
	 	}
	 
	 	$this->data['doc1_doc_id'] = $doc1["_id"]['$id'];
	 	$this->data['doc2_doc_id'] = $doc2["_id"]['$id'];
	 
	 	$this->data['doc1'] = json_encode($doc1["doc_data"]["widget_data"]);
	 	$this->data['doc2'] = json_encode($doc2["doc_data"]["widget_data"]);
	 
		$this->_render_page('ttwreis_admins/doc_comp_check', $this->data);
	 	//$this->_render_page('field_agent/field_agent_doc_comp', $this->data);
	 	 
	 	//echo print_r($check,true);
	 	//echo print_r($diff_str,true);
	 }
	 
	function delete_dup_doc(){
	 	$doc_id = $this->input->post('doc_id');
	 	log_message('debug','doc_iiiiddddddddddddddddddddd'.print_r($doc_id,true));
	 	$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get("healthcare201671115519757");
	 	$query_ins = $this->mongo_db->insert("healthcare201671115519757_duplicate",$query[0]);
	 	
	 	if ($query_ins) {
	 		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->delete("healthcare201671115519757");
	 	}
	 	
	 	//$this->index();
		echo "Document deleted, please go back using browser back button";
	 }
	 
	 function show_all_docs($id_no = ""){
	 	
	 	//log_message('debug','idsssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($ad_no,true));
	 	$this->data['docs'] = json_encode($this->ttwreis_common_model->get_all_docs_in_uid_no($id_no));
	 	
	 	$this->_render_page('ttwreis_admins/show_all_docs', $this->data);
	 }
	 
	 function diff_docs(){
	 	$doc_id = $this->input->post('doc_ids');
	 	log_message('debug','doc_iiiiddddddddddddddddddddd'.print_r($doc_id,true));
	 	
	 	redirect("ttwreis_mgmt/doc_comp/$doc_id[0]/$doc_id[1]");
	 	
// 	 	$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get("healthcare201671115519757");
// 	 	$query_ins = $this->mongo_db->insert("healthcare201671115519757_duplicate",$query[0]);
	 	 
// 	 	if ($query_ins) {
// 	 		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->delete("healthcare201671115519757");
// 	 	}
	 	 
// 	 	$this->index();
	 }
	 
	public function add_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed_view' );
		log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff');
		$this->data ['message'] = "";
		
		$this->_render_page ( 'ttwreis_admins/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		$news_return = $this->rhso_users_common_lib->add_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_admins/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->ttwreis_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_admins/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->rhso_users_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'ttwreis_mgmt/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->ttwreis_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_admins/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->rhso_users_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_admins/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	
	public function civil_infrastructure()
	{
		$this->data = "";
		$this->_render_page('rhso_users/civil_infrastructure_inspection',$this->data);
	}
	public function checklist_inspectors()
	{
		$this->data['schools_list'] = $this->get_districtwise_schools_list();
		$this->_render_page('rhso_users/checklist_inspectors_new',$this->data);
	}
	//Health Inspection submit  form 
	public function health_inspection_form_submit()
	{
		
		//$Schoolcode =  $this->input->post('Page1_Schoolcode',true);
		$school_name = $this->input->post('page1_SchoolInfo_SchoolName', TRUE);
		//$district = $this->input->post('page1_SchoolInfo_District', TRUE);
		$principal_name  = $this->input->post('page1_SIFNOTEStatus_PrincipalName');
		$date_of_visit  = $this->input->post('page1_SchoolInfo_DateofVisit');
		$time_to_visit  = $this->input->post('page1_SchoolInfo_Time');
		$health_supervisor_name = $this->input->post('page2_SIFNOTEStatus_HSName');
		$hsqualifcations = $this->input->post('page2_SIFNOTEStatus_HSQualification',true);
		$time_from_to  = $this->input->post('page1_SchoolInfo_Timefromto');
		$date  = $this->input->post('page1_SchoolInfo_DateofVisit');
		//$category  = $this->input->post('');
		$infoto_panacea  = $this->input->post('page1_SIFNOTEStatus_InfotoPANACEA');
		//two radio button code not writen
		$assistent_caretake_name  = $this->input->post('page2_SIFNOTEStatus_Nameofasstcaretaker');
		$students_strength  = $this->input->post('page2_SIFNOTEStatus_StudentsStrength');
		$classes  = $this->input->post('page2_SIFNOTEStatus_Classes');
		$sick_rooms  = $this->input->post('page3_SickRoomSpecifications_NumberofRooms');
		$table_maintenance  = $this->input->post('page3_SickRoomSpecifications_TableMaintenance');
		$green_cloth  = $this->input->post('page3_SickRoomSpecifications_GreenCloth');
		$betadine  = $this->input->post('page3_Tray_Betadine');
		$surgical_spirit  = $this->input->post('page3_Tray_SurgicalSpirit');
		$hydrogen_proxide  = $this->input->post('page3_Tray_HydrogenPeroxide');
		
		
		$cottonor_gauge  = $this->input->post('page3_Tray_CottonorGauge');
		$weighing_machine  = $this->input->post('page4_Equipment_WeighingMachine');
		$bp_apparatus  = $this->input->post('page4_Equipment_BPapparatus');
		$pulse_oxymeter  = $this->input->post('page4_Equipment_PulseOxymeter');
		$thermometer  = $this->input->post('page4_Equipment_Thermometer');
		$stethoscope  = $this->input->post('page4_Equipment_Stethoscope');
		$nebulizer  = $this->input->post('page4_Equipment_Nebulizer');
		$examination_table  = $this->input->post('page4_Equipment_ExaminationTable');
		$saline_stand  = $this->input->post('page4_Equipment_SalineStand');
		$cotsor_mattress  = $this->input->post('page5_Equipment_CotsorMattress');
		$curtains  = $this->input->post('page5_Equipment_Curtains');
		$mesh  = $this->input->post('page5_Equipment_Mesh');
		$fans  = $this->input->post('page5_Equipment_Fans');
		$emergency  = $this->input->post('page5_Pharmacy_Emergency');
		$regular  = $this->input->post('page5_Pharmacy_Regular');
		$flow_charts  = $this->input->post('page5_Pharmacy_FlowCharts');
		$vision  = $this->input->post('page6_AnyHealthCheckups_Vision');
		$hb = $this->input->post('page6_AnyHealthCheckups_HB');
		$dental  = $this->input->post('page6_AnyHealthCheckups_Dental');
		$deworming  = $this->input->post('page6_AnyHealthCheckups_Deworming');
		$vaccination  = $this->input->post('page6_AnyHealthCheckups_Vaccination');
		$hospitalization  = $this->input->post('page6_AnyHealthCheckups_Hospitalization');
		$epidemics  = $this->input->post('page6_AnyHealthCheckups_Epidemics');
		$working_condition  = $this->input->post('page7_Incenerators_WorkingCondition');
		
		$incenerators_using_or_not  = $this->input->post('page7_Incenerators_Usingornotusing');
		$flycatchers  = $this->input->post('page7_Others_Flycatchers');
		$ro_plant  = $this->input->post('page7_Others_ROPlant');
		$washrooms  = $this->input->post('page7_Others_Washrooms');
		$sinksat_washroom_or_mess  = $this->input->post('page7_Others_SinksatWashroomorMess');
		$handwashat_washroom_or_mess  = $this->input->post('page7_Others_HandwashatWashroomorMess');
		$principal_visitdate  = $this->input->post('page8_Others_PrincipalVisitdate');
		$pd_or_pet_name  = $this->input->post('page8_Others_NameofPDorPET');
		$experience  = $this->input->post('page8_Others_Experience');
		$pet_qualification  = $this->input->post('page8_Others_PETQualification');
		$stayof_pet  = $this->input->post('page8_Others_StayofPET');
		$regular_exercise  = $this->input->post('page9_Others_RegularExercise');
		$dietary_habits  = $this->input->post('page9_Others_DietaryHabits');
		$awareness  = $this->input->post('page9_Others_Awareness');
		$education  = $this->input->post('page9_Others_Education');
		$motivation  = $this->input->post('page9_Others_Motivation');
		$special_sports  = $this->input->post('page9_Others_SpecialSports');
		$inspectebydname  = $this->input->post('page9_NameandSignatureoftheInspectionOfficer_Name');
			//sending to the model
		//$doc_data['page1']['School Info']['Hospital Unique ID'] = '';
		$doc_data['page1']['School Info']['School Name']['field_ref'] = $school_name;
		//$doc_data['page1']['School Info']['District']['field_ref'] = $district;
		
		$doc_data['page1']['School Info']['Time'] = $time_from_to;
		$doc_data['page1']['School Info']['Date of Visit'] = $date;
		
		$doc_data['page1']['SIFNOTE Status']['Info to PANACEA'] = $infoto_panacea ;
		$doc_data['page1']['SIFNOTE Status']['principal Name'] = $principal_name;

		$doc_data['page2']['SIFNOTE Status']['Hs Name'] = $health_supervisor_name;
		$doc_data['page2']['SIFNOTE Status']['HS Qualification'] = $hsqualifcations;
		$doc_data['page2']['SIFNOTE Status']['Name of asst care taker'] = $assistent_caretake_name;
		//$doc_data['page2']['SIFNOTE Status']['Asst care taker Qualification'] = $assistent_caretake_name;
		$doc_data['page2']['SIFNOTE Status']['Students Strength'] = $students_strength;
		$doc_data['page2']['SIFNOTE Status']['Classes'] = $classes;
		$doc_data['page3']['Sick Room Specifications']['Number of Rooms'] = $sick_rooms;
		$doc_data['page3']['Sick Room Specifications']['Table Maintenance'] = $table_maintenance;
		$doc_data['page3']['Sick Room Specifications']['Green Cloth'] = $green_cloth;
		$doc_data['page3']['Tray']['Betadine'] = $betadine;
		$doc_data['page3']['Tray']['Surgical Spirit'] = $surgical_spirit;
		$doc_data['page3']['Tray']['Hydrogen Peroxide'] = $hydrogen_proxide;
		
		$doc_data['page3']['Tray']['Cotton or Gauge'] = $cottonor_gauge;
		$doc_data['page4']['Equipment']['Weighing Machine'] = $weighing_machine;
		$doc_data['page4']['Equipment']['BP apparatus'] = $bp_apparatus;
		$doc_data['page4']['Equipment']['Pulse Oxymeter'] = $pulse_oxymeter;
		$doc_data['page4']['Equipment']['Thermometer'] = $thermometer;
		$doc_data['page4']['Equipment']['Stethoscope'] = $stethoscope;
		$doc_data['page4']['Equipment']['Nebulizer'] = $nebulizer;
		$doc_data['page4']['Equipment']['Examination Table'] = $examination_table;
		$doc_data['page4']['Equipment']['Saline Stand'] = $saline_stand;
		$doc_data['page5']['Equipment']['Cots or Mattress'] = $cotsor_mattress;
		$doc_data['page5']['Equipment']['Curtains'] = $curtains;
		$doc_data['page5']['Equipment']['Mesh'] = $mesh;
		$doc_data['page5']['Equipment']['Fans'] = $fans;
		$doc_data['page5']['Pharmacy']['Emergency'] = $emergency;
		$doc_data['page5']['Pharmacy']['Regular'] = $regular;
		$doc_data['page5']['Pharmacy']['Flow Charts'] = $flow_charts;
		$doc_data['page6']['Any Health Checkups']['Vision'] = $vision;
		$doc_data['page6']['Any Health Checkups']['HB'] = $hb;
		$doc_data['page6']['Any Health Checkups']['Dental'] = $dental;
		$doc_data['page6']['Any Health Checkups']['Deworming'] = $deworming;
		$doc_data['page6']['Any Health Checkups']['Vaccination'] = $vaccination;
		$doc_data['page6']['Any Health Checkups']['Hospitalization'] = $hospitalization;
		$doc_data['page6']['Any Health Checkups']['Epidemics'] = $epidemics;
		$doc_data['page7']['Incenerators']['Working Condition'] = $working_condition;
		$doc_data['page7']['Incenerators']['Using or not using'] = $incenerators_using_or_not;
		$doc_data['page7']['Others']['Fly catchers'] = $flycatchers;
		$doc_data['page7']['Others']['RO Plant'] = $ro_plant;
		$doc_data['page7']['Others']['Wash rooms'] = $washrooms;
		$doc_data['page7']['Others']['Sinks at Wash room or Mess'] = $sinksat_washroom_or_mess;
		$doc_data['page7']['Others']['Handwash at Wash room or Mess'] = $handwashat_washroom_or_mess;
		$doc_data['page8']['Others']['Principal Visit date'] = $principal_visitdate;
		$doc_data['page8']['Others']['Name of PD or PET'] = $pd_or_pet_name;
		$doc_data['page8']['Others']['Experience'] = $experience;
		$doc_data['page8']['Others']['PET Qualification'] = $pet_qualification;
		$doc_data['page8']['Others']['Stay of PET'] = $stayof_pet;
		$doc_data['page9']['Others']['Regular Exercise'] = $regular_exercise;
		$doc_data['page9']['Others']['Dietary Habits'] = $dietary_habits;
		$doc_data['page9']['Others']['Awareness'] = $awareness;
		$doc_data['page9']['Others']['Education'] = $education;
		$doc_data['page9']['Others']['Motivation'] = $motivation;
		$doc_data['page9']['Name and Signature of the Inspection Officer']['Name'] = $inspectebydname;
		//$doc_data['page9']['Others']['Motivation'] = $motivation;

		// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['Check_health_inspection_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['Check_health_inspection_attachments']['name']	= $files['Check_health_inspection_attachments']['name'][$i];
			 $_FILES['Check_health_inspection_attachments']['type']	= $files['Check_health_inspection_attachments']['type'][$i];
			 $_FILES['Check_health_inspection_attachments']['tmp_name']= $files['Check_health_inspection_attachments']['tmp_name'][$i];
			 $_FILES['Check_health_inspection_attachments']['error']	= $files['Check_health_inspection_attachments']['error'][$i];
			 $_FILES['Check_health_inspection_attachments']['size']	= $files['Check_health_inspection_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare20171227173441869_health_inspector';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/RHSO_attachments/'.$controller.'/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/RHSO_attachments/$controller/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						
								redirect('rhso_users/checklist_inspectors');  
						
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
					
						$external_data_array = array(
												"DFF_EXTERNAL_ATTACHMENTS_".$i => array(
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
			
			  if(isset($attachments))
			  {
					   
				$external_merged_data = array_merge($attachments,$external_final);
				$attachments = array_replace_recursive($attachments,$external_merged_data);
			  }
			  else
			 {
			    $attachments = $external_final;
			 } 
		  
		 }

		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 2;
		$doc_properties['total_pages'] = '9';
		$separateDistrict = explode(',',$school_name);
		$district_name = $separateDistrict[1];

		$school_info['school_name'] = $school_name;
		$school_info['district_name'] = $district_name;
	
		
		$app_properties['app_name'] = "Checklist for Inspectors";
		$app_properties['app_id'] = "healthcare20171227173441869";
	
		
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

	
		$this->rhso_users_common_model->insert_health_inspection_model($doc_data,$attachments,$school_info,$doc_properties,$app_properties,$history);
		redirect('rhso_users/to_dashboard');
	}
	public function food_hygiene_inspection()
	{
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code = $loggedinuser['district_code'];

		$dist_list = $this->rhso_users_common_model->get_all_district($cro_district_name);
		$cro_dist = $dist_list[0]['_id'];
		$this->data['get_districtwise_schools'] = $this->rhso_users_common_model->get_all_schools($cro_dist);
		$this->_render_page('rhso_users/food_inspection',$this->data);
	}
	public function academics_inspection()
	{
		$this->data = ""; 
		$this->_render_page('rhso_users/academics_inspection',$this->data);
	}
	public function sanitation_inspection()
	{
		$this->data['schools_list'] = $this->get_districtwise_schools_list();
		$this->_render_page('rhso_users/sanitation_inspection_view_version_2',$this->data);
		//$this->_render_page('rhso_users/sanitation_inspection',$this->data);
	}
	//sanitation_inspection new version 2222222222
	public function sanitation_inspection_submit_v2()
	{
		// $this->data = "";
		//$this->_render_page('rhso_users /civil_infrastructure_inspection_view',$this->data);
		//$Schoolcode =  $this->input->post('Page1_Schoolcode',true);
		$school_name = $this->input->post('page1_SchoolInfo_SchoolName', TRUE);
		//$district = $this->input->post('page1_SchoolInfo_District', TRUE);
		$principal_name  = $this->input->post('page1_SchoolInfo_PrincipalName');
		
		$principal_phno =  $this->input->post('page1_SchoolInfo_ContactNumber');
		$hs_name = $this->input->post('page1_SchoolInfo_HSName');
		$asstCareTakerName = $this->input->post('page1_SchoolInfo_AsstCareTakerName');
		//$pin = $this->input->post('page1_SchoolInfo_PIN');
		$childrenwithSpecialNeeds = $this->input->post('page2_GENERALINFORMATION_NoofChildrenwithSpecialNeeds');
		$gi_TypeofSchool = $this->input->post('page2_GENERALINFORMATION_TypeofSchool');
		$gi_schoolhaselectricity = $this->input->post('page2_GENERALINFORMATION_Whethertheschoolhaselectricity');
		$gi_compoundwall = $this->input->post('page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall');
		$gi_sourceofdrinkingwater = $this->input->post('page3_WATER_Whatisthesourceofdrinkingwaterinthepremises');
		$statusoffunctionalityofthesourceofthedrinkingwater = $this->input->post('ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater');
		$watertreatmentareusedbeforedrinkingorcooking = $this->input->post('ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking');



		$watersourceneedrepairs = $this->input->post('page3_WATER_Doeswatersourceneedrepairs');
		$drinkingwaterstorage = $this->input->post('page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage');
		$sourceofthedrinkingwater = 
			$this->input->post('ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater');
			/*echo print_r($sourceofthedrinkingwater,true); exit();*/
		$waterliftedtothetank = $this->input->post('page3_WATER_Ifsohowiswaterliftedtothetank');
		$toiletsinthepremises = $this->input->post('page4_TOILETS_Doestheschoolhavetoiletsinthepremises');
		$no_TOILETS_Girls = $this->input->post('page4_TOILETS_Girls');
		$no_TOILETS_Boys = $this->input->post('page4_TOILETS_Boys');
		$no_TOILETS_Teachers = $this->input->post('page4_TOILETS_Teachers');
		$no_TOILETS_Common = $this->input->post('page4_TOILETS_Common');
		$no_TOILETS_Howmanyarefunctional = $this->input->post('page4_TOILETS_Howmanyarefunctional');
		$Arethetoiletscleanodorless = $this->input->post('ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained');
		$dothetoiletsneedrepairs = $this->input->post('ac_page4_TOILETS_Dothetoiletsneedrepairs');
		
		$waterprovidedtotoilets = 
		$this->input->post('page5_TOILETS_Howisthewaterprovidedtotoiletsorurinals',true);
		$toiletspeciallyforchildrenwithspecialneeds = $this->input->post('page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds');
		$accesswithhandrail = $this->input->post('page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail');
		$awidedoorforwheelchairentry = $this->input->post('page5_TOILETS_Awidedoorforwheelchairentry');
		$handrailsinsidethetoiletforsupport = $this->input->post('page5_TOILETS_Handrailsinsidethetoiletforsupport');
		$cleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals = $this->input->post('page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals');
		$handwashingfacility = $this->input->post('page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet');
		$waterprovidedinthehandwashingfacility = $this->input->post('page6_TOILETS_Istherewaterprovidedinthehandwashingfacility');
		$soapprovidedinthehandwashingfacility = $this->input->post('page6_TOILETS_Istheresoapprovidedinthehandwashingfacility');
		



		$Whocleansthetoilets = $this->input->post('page6_TOILETS_Whocleansthetoiletsandurinals');
		$urinalscleaned = $this->input->post('page6_TOILETS_Howoftenarethetoiletsorurinalscleaned');
		$wasteincludingdustbins = $this->input->post('page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins');
		$disposalofmenstrualwaste = $this->input->post('page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste');
		$handrailsinsidethetoiletforsupport = $this->input->post('page7_TOILETS_Handrailsinsidethetoiletforsupport');
		$Aretherecleaningmaterials = $this->input->post('page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals');
		//$ = $this->input->post('');

		//we need to submit the sanitation to server
		$doc_data['page1']['School Info']['School Name']['field_ref'] = $school_name;
		//$doc_data['page1']['School Info']['District']['field_ref'] = $district;
		$doc_data['page1']['School Info']['Principal Name'] = $principal_name;
		//$doc_data['page1']['School Info']['Time'] = $time_to_visit;
	
		$doc_data['page1']['School Info']['Contact Number'] = $principal_phno;
		$doc_data['page1']['School Info']['HS Name'] = $hs_name;
		$doc_data['page1']['School Info']['Asst Care Taker Name'] = $asstCareTakerName;
		//$doc_data['page1']['School Info']['PIN'] = $pin;
		$doc_data['page2']['GENERAL INFORMATION']['No of Children with Special Needs'] = $childrenwithSpecialNeeds;
		$doc_data['page2']['GENERAL INFORMATION']['Type of School'] = $gi_TypeofSchool;
		$doc_data['page2']['GENERAL INFORMATION']['Whether the school has electricity'] = $gi_schoolhaselectricity;
		$doc_data['page2']['GENERAL INFORMATION']['Status of school boundary or compound wall'] = $gi_compoundwall;
		$doc_data['page3']['WATER']['What is the source of drinking water in the premises'] = $gi_sourceofdrinkingwater;
		$doc_data['page3']['WATER']['What is the status of functionality of the source of the drinking water'] = $statusoffunctionalityofthesourceofthedrinkingwater;
		$doc_data['page3']['WATER']['What methods of water treatment are used before drinking or cooking'] = $watertreatmentareusedbeforedrinkingorcooking;
		$doc_data['page3']['WATER']['Does water source need repairs'] = $watersourceneedrepairs;
		$doc_data['page3']['WATER']['Whether the school has functioning overhead tank for drinking water storage'] = $drinkingwaterstorage;
		$doc_data['page3']['WATER']['If so how is water lifted to the tank'] = $waterliftedtothetank;
		$doc_data['page4']['TOILETS']['Does the school have toilets in the premises'] = $toiletsinthepremises;
		$doc_data['page4']['TOILETS']['Girls'] = $no_TOILETS_Girls;
		$doc_data['page4']['TOILETS']['Boys'] = $no_TOILETS_Boys;
		$doc_data['page4']['TOILETS']['Teachers'] = $no_TOILETS_Teachers;
		$doc_data['page4']['TOILETS']['Common'] = $no_TOILETS_Common;
		$doc_data['page4']['TOILETS']['How many are functional'] = 
			$no_TOILETS_Howmanyarefunctional;
		$doc_data['page4']['TOILETS']['Are the toilets clean odorless and well maintained'] = $Arethetoiletscleanodorless;
		$doc_data['page4']['TOILETS']['Do the toilets need repairs'] = $dothetoiletsneedrepairs;
		$doc_data['page5']['TOILETS']['How is the water provided to toilets or urinals'] = $waterprovidedtotoilets;
		$doc_data['page5']['TOILETS']['Is there a toilet specially for children with special needs'] = $toiletspeciallyforchildrenwithspecialneeds;
		$doc_data['page5']['TOILETS']['If yes does it have ramp access with handrail'] = $accesswithhandrail;
		$doc_data['page5']['TOILETS']['A wide door for wheelchair entry'] = $awidedoorforwheelchairentry;
		$doc_data['page5']['TOILETS']['Handrails inside the toilet for support'] = $handrailsinsidethetoiletforsupport;
		$doc_data['page5']['TOILETS']['Are there cleaning materials available near the toilet for cleaning toilets or urinals'] = 
			$cleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals;	
		$doc_data['page5']['TOILETS']['Is there handwashing facility attached or close to the toilet'] = $handwashingfacility;	
		$doc_data['page6']['TOILETS']['Is there water provided in the handwashing facility'] = $waterprovidedinthehandwashingfacility;	
		$doc_data['page6']['TOILETS']['Is there soap provided in the handwashing facility'] = $soapprovidedinthehandwashingfacility;	
		$doc_data['page6']['TOILETS']['Who cleans the toilets and urinals'] = $Whocleansthetoilets;	
		$doc_data['page6']['TOILETS']['How often are the toilets or urinals cleaned'] = $urinalscleaned;	
		$doc_data['page6']['TOILETS']['Is there adeqate and private space for changing and disposal facilities for menstrual waste including dust bins'] = $wasteincludingdustbins;	
		$doc_data['page6']['TOILETS']['Is there any incinerator installed for the disposal of menstrual waste'] = $disposalofmenstrualwaste;	
		$doc_data['page7']['TOILETS']['Handrails inside the toilet for support'] = $handrailsinsidethetoiletforsupport;	
		$doc_data['page7']['TOILETS']['Are there cleaning materials available near the toilet for cleaning toilets or urinals'] = $Aretherecleaningmaterials;	
			
			// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['rhso_req_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['rhso_req_attachments']['name']	= $files['rhso_req_attachments']['name'][$i];
			 $_FILES['rhso_req_attachments']['type']	= $files['rhso_req_attachments']['type'][$i];
			 $_FILES['rhso_req_attachments']['tmp_name']= $files['rhso_req_attachments']['tmp_name'][$i];
			 $_FILES['rhso_req_attachments']['error']	= $files['rhso_req_attachments']['error'][$i];
			 $_FILES['rhso_req_attachments']['size']	= $files['rhso_req_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare20171226174552433_sanitation_inspection';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/RHSO_attachments/'.$controller.'/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/RHSO_attachments/$controller/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						
								redirect('rhso_users/sanitation_inspection');  
						
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
					
						$external_data_array = array(
												"DFF_EXTERNAL_ATTACHMENTS_".$i => array(
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
			
			  if(isset($attachments))
			  {
					   
				$external_merged_data = array_merge($attachments,$external_final);
				$attachments = array_replace_recursive($attachments,$external_merged_data);
			  }
			  else
			 {
			    $attachments = $external_final;
			 } 
		  
		 }
		 $separateDistrict = explode(',',$school_name);
		 $district_name = $separateDistrict[1];

		$school_info['school_name'] = $school_name;
		$school_info['district_name'] = $district_name;

		 $doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";

		$app_properties['app_name']= "Sanitation Inspection Report";
		$app_properties['app_id'] = "healthcare20171226174552433";
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


		$submitted = $this->rhso_users_common_model->insert_sanitation_inspection_model($doc_data,$attachments,$school_info,$doc_properties,$app_properties,$history);
		redirect('rhso_users/sanitation_inspection_report');
			
			    //redirect('rhso_users/sanitation_inspection_report');
			
			
			   

	}
	public function attendance_report()
	{
		
		$this->data = "";
		$this->_render_page('rhso_users/attendance_initiate',$this->data);
	}
	public function show_bmi_graph() 
	{
		
		$session = $this->session->userdata('customer');
		$this->data['cro_district_code'] = $session['district_code'];
		$this->data["message"] = "";
		$this->_render_page('rhso_users/bmi_graph_view',$this->data);
	}
	
	public function student_bmi_graph(){
		
		$month_wise_bmi  = array();
		$month_wise_data = array();
		$final_bmi_data  = array();
		$temp 	 		 = array();
		
		//setting session variable
		$session = $this->session->userdata('customer');
		$cro_district_code = $session['district_code'];
		
		$unique_id = $_POST['uid'];
		$bmi_value = $this->rhso_users_common_model->get_student_bmi_values($cro_district_code,$unique_id);
		
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
	/* RHSO submitted Reports */
	public function rhso_reports()
	{
		
		$this->data = $this->rhso_users_common_lib->rhso_reports_lib();
		$this->_render_page('rhso_users/rhso_submitted_forms_view',$this->data);
	}
	
	public function rhso_submitted_reports_school_wise()
	{
		
		$school_name = $this->input->post('school_name', TRUE);
		
		$this->data['sanitation_inspection_data'] = $this->rhso_users_common_lib->get_sanitation_inspection_school_report_lib($school_name);
		/* $this->data['food_and_hygiene_data'] = $this->rhso_users_common_lib->get_food_hygiene_school_report_lib($school_name);
		$this->data['check_list_health_data'] = $this->rhso_users_common_lib->get_check_list_inspector_school_lib($school_name); */
		if(!empty($this->data))
		{
			
			$this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
		
		
	}

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
			$this->_render_page('rhso_users/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			
			log_message('debug','$identity=====782======'.print_r($identity,true));
			
			$change = $this->rhso_users_common_model->change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));
			
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
			$this->_render_page('rhso_users/change_password', $this->data);
			}
		}
	}
	
	/*---------------------- RHSO Reports-------------------------------*/
	public function sanitation_inspection_report()
	{

		$this->data = $this->rhso_users_common_lib->rhso_reports_lib();
		$this->_render_page('rhso_users/sanitation_inspection_report_view',$this->data);
		
	}

	public function get_sanitation_inspection_report()
	{
		$school_name = $this->input->post('school_name', TRUE);
		$this->data = $this->rhso_users_common_lib->get_sanitation_inspection_report_lib($school_name);
		
		$this->output->set_output($this->data);
	}

	public function civil_and_infrastructure_report()
	{

		$this->data = $this->rhso_users_common_lib->rhso_reports_lib();
		$this->_render_page('rhso_users/civil_and_infrastructure_report_view',$this->data);
		
	}

	public function get_civil_and_infrastructure_report()
	{
		$school_name = $this->input->post('school_name', TRUE);
		$this->data = $this->rhso_users_common_lib->get_civil_and_infrastructure_report_lib($school_name);
		
		$this->output->set_output($this->data);
	}

	public function health_inspector_inspection_report()
	{

		$this->data = $this->rhso_users_common_lib->rhso_reports_lib();
		$this->_render_page('rhso_users/health_inspector_report_view',$this->data);
		
	}

	public function get_health_inspector_inspection_report()
	{
		$school_name = $this->input->post('school_name', TRUE);
		$this->data = $this->rhso_users_common_lib->get_health_inspector_inspection_report_lib($school_name);
		
		$this->output->set_output($this->data);
	}

	public function food_hygiene_inspection_report()
	{

		$this->data = $this->rhso_users_common_lib->rhso_reports_lib();
		$this->_render_page('rhso_users/food_hygiene_inspection_report_view',$this->data);
		
	}

	public function get_food_hygiene_inspection_report()
	{
		$school_name = $this->input->post('school_name', TRUE);
		$this->data = $this->rhso_users_common_lib->get_food_hygiene_inspection_report_lib($school_name);
		
		$this->output->set_output($this->data);
	}
	
	//////////////////
	public function get_districtwise_schools_list()
	{
			$loggedinuser = $this->session->userdata("customer");
			$cro_district_name = $loggedinuser['dt_name'];
			$cro_district_code = $loggedinuser['district_code'];

			$dist_list = $this->rhso_users_common_model->get_all_district($cro_district_name);
			$cro_dist = $dist_list[0]['_id'];
			$get_districtwise_schools = $this->rhso_users_common_model->get_all_schools($cro_dist);

			return $get_districtwise_schools;
	}

	public function get_school_info_by_school_name()
	{
		$school_name = $_POST['school_name'];
		$this->data = $this->rhso_users_common_model->get_school_info_by_school_name($school_name);
		$this->output->set_output(json_encode($this->data));

	}
	
		//civil infrastracture form 
	public function civil_infrastructure_form()
	{
		$this->data['schools_list'] = $this->get_districtwise_schools_list();
		$this->data['today_date'] = date('Y-m-d');
		$this->_render_page('rhso_users/civil_infrastructure_inspection_view',$this->data);
	}
	
	public function civil_infrastructure_new_submit()
	{

		$doc_data = array();
		$doc_data['page1'] = array();
		$doc_data['page2'] = array();
		$doc_data['page3'] = array();
		$doc_data['page4'] = array();
		$doc_data['page5'] = array();
		$doc_data['page6'] = array();
		$doc_data['page7'] = array();
		$doc_data['page8'] = array();
		$doc_data['page9'] = array();

		$school_info = array();
		

		$school_name = $this->input->post('page1_SchoolInfo_SchoolName', TRUE);
		$principal_name  = $this->input->post('page1_SchoolInfo_PrincipalName');
		$principal_number  = $this->input->post('page1_SchoolInfo_PrincipalNumber');
		$hsname = $this->input->post('page1_SchoolInfo_HealthSupName');
		$hsnumber = $this->input->post('page1_SchoolInfo_HSNumber');
		$date  = $this->input->post('page1_SchoolInfo_Date');
		$category  = $this->input->post('page1_SchoolInfo_Category');

		//school building
		$SchoolBuildingObsevations  = $this->input->post('page2_SchoolBuilding_1Obsevationofissues');
		$SchoolBuildingRemarks  = $this->input->post('page2_SchoolBuilding_2Remarks');

		$KitchenandDiningObsevations  = $this->input->post('page2_KitchenandDining_3Obsevationofissues');
		$KitchenandDiningRemarks  = $this->input->post('page3_KitchenandDining_4Remarks');

		$WaterSupplyObsevations  = $this->input->post('page3_WaterSupply_5Obsevationofissues');
		$WaterSupplyRemarks  = $this->input->post('page3_WaterSupply_6Remarks');

		$ROPlantObsevations  = $this->input->post('page4_ROPlant_7Obsevationofissues');
		$ROPlantRemarks  = $this->input->post('page4_ROPlant_8Remarks');

		$ElectricalTransformerObsevations  = $this->input->post('page4_ElectricalTransformer_9Obsevationofissues');
		$ElectricalTransformerRemarks  = $this->input->post('page5_ElectricalTransformer_10Remarks');

		$GeneratorObsevations  = $this->input->post('page5_Generator_11Obsevationofissues');
		$GeneratorRemarks  = $this->input->post('page5_Generator_12Remarks');

		$CompoundwallObsevations  = $this->input->post('page6_Compoundwall_13Obsevationofissues');
		$CompoundwallRemarks  = $this->input->post('page6_Compoundwall_14Remarks');

		$InternalroadObsevations  = $this->input->post('page6_Internalroad_15Obsevationofissues');
		$InternalroadRemarks  = $this->input->post('page7_Internalroad_16Remarks');
		
		$FireExtinguishersObsevations  = $this->input->post('page7_FireExtinguishers_17Obsevationofissues');
		$FireExtinguishersRemarks  = $this->input->post('page7_FireExtinguishers_18Remarks');

		$ElectrificationObsevations  = $this->input->post('page8_Electrification_19Obsevationofissues');
		$ElectrificationRemarks  = $this->input->post('page8_Electrification_20Remarks');

		$GeneralorWatersanitationObsevations  = $this->input->post('page8_GeneralorWatersanitation_21Obsevationofissues');
		$GeneralorWatersanitationRemarks  = $this->input->post('page9_GeneralorWatersanitation_22Remarks');

		$AnyOthersObsevations  = $this->input->post('page9_AnyOthers_23Obsevationofissues');
		$AnyOthersRemarks  = $this->input->post('page9_AnyOthers_24Remarks');

		$CommentsorSuggestions  = $this->input->post('page9_AnyOthers_CommentsorSuggestions');
		$Overallrating  = $this->input->post('page9_AnyOthers_Overallratingforthefoodandhygineatinstitution');

	
		$doc_data['page1']['School Info']['School Name'] = $school_name;
		$doc_data['page1']['School Info']['Principal Name'] = $principal_name;
		$doc_data['page1']['School Info']['Principal Number'] = $principal_number;
		$doc_data['page1']['School Info']['HS Name'] = $hsname;
		$doc_data['page1']['School Info']['HS Number'] = $hsnumber;
		$doc_data['page1']['School Info']['Date'] = $date;
		$doc_data['page1']['School Info']['Category'] = $category;

		$doc_data['page2']['School Building']['1 Obsevation of issues'] = 
				$SchoolBuildingObsevations;
		$doc_data['page2']['School Building']['2 Remarks'] = $SchoolBuildingRemarks;

		$doc_data['page2']['Kitchen and Dining']['3 Obsevation of issues'] = $KitchenandDiningObsevations;
		$doc_data['page3']['Kitchen and Dining']['4 Remarks'] = $KitchenandDiningRemarks;


		$doc_data['page3']['Water Supply']['5 Obsevation of issues'] = $WaterSupplyObsevations;
		$doc_data['page3']['Water Supply']['6 Remarks'] = $WaterSupplyRemarks;

		$doc_data['page4']['RO Plant']['7 Obsevation of issues'] = $ROPlantObsevations;
		$doc_data['page4']['RO Plant']['8 Remarks'] = $ROPlantRemarks;

		$doc_data['page4']['Electrical Transformer']['9 Obsevation of issues'] = $ElectricalTransformerObsevations;
		$doc_data['page5']['Electrical Transformer']['10 Remarks'] = $ElectricalTransformerRemarks;	

		$doc_data['page5']['Generator']['11 Obsevation of issues'] = $GeneratorObsevations;
		$doc_data['page5']['Generator']['12 Remarks'] = $GeneratorRemarks;

        $doc_data['page6']['Compound wall']['13 Obsevation of issues'] = $CompoundwallObsevations;
		$doc_data['page6']['Compound wall']['14 Remarks'] = $CompoundwallRemarks;

		$doc_data['page6']['Internal road']['15 Obsevation of issues'] = $InternalroadObsevations;
		$doc_data['page7']['Internal road']['16 Remarks'] = $InternalroadRemarks;

		$doc_data['page7']['Fire Extinguishers']['17 Obsevation of issues'] = $FireExtinguishersObsevations;
		$doc_data['page7']['Fire Extinguishers']['18 Remarks'] = $FireExtinguishersRemarks;

         $doc_data['page8']['Electrification']['19 Obsevation of issues'] = $ElectrificationObsevations;
		$doc_data['page8']['Electrification']['20 Remarks'] = $ElectrificationRemarks;  

		$doc_data['page8']['General or Water sanitation']['21 Obsevation of issues'] = $GeneralorWatersanitationObsevations;
		$doc_data['page9']['General or Water sanitation']['22 Remarks'] = $GeneralorWatersanitationRemarks;	

		$doc_data['page9']['Any Others']['23 Obsevation of issues'] = $AnyOthersObsevations;
		$doc_data['page9']['Any Others']['24 Remarks'] = $AnyOthersRemarks;

		$doc_data['page9']['Any Others']['Comments or Suggestions'] = $CommentsorSuggestions;
		$doc_data['page9']['Any Others']['Overall rating for the food and hygine at institution'] = $Overallrating;

							// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['civil_infrastructure_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['civil_infrastructure_attachments']['name']	= $files['civil_infrastructure_attachments']['name'][$i];
			 $_FILES['civil_infrastructure_attachments']['type']	= $files['civil_infrastructure_attachments']['type'][$i];
			 $_FILES['civil_infrastructure_attachments']['tmp_name']= $files['civil_infrastructure_attachments']['tmp_name'][$i];
			 $_FILES['civil_infrastructure_attachments']['error']	= $files['civil_infrastructure_attachments']['error'][$i];
			 $_FILES['civil_infrastructure_attachments']['size']	= $files['civil_infrastructure_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare20171227153054237_civil_infrastructure';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/RHSO_attachments/'.$controller.'/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/RHSO_attachments/$controller/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 if(array_key_exists("user_type",$session_data))
						  {
							if($session_data['user_type'] == "CADMIN")
							{
								redirect('rhso_users/civil_infrastructure');  
							}
						  }
						
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
					
						$external_data_array = array(
												"DFF_EXTERNAL_ATTACHMENTS_".$i => array(
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
			
			  if(isset($attachments))
			  {
					   
				$external_merged_data = array_merge($attachments,$external_final);
				$attachments = array_replace_recursive($attachments,$external_merged_data);
			  }
			  else
			 {
			    $attachments = $external_final;
			 } 
		  
		 }
		
	

		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 2;
		$doc_properties['total_pages'] = 9;

		$separateDistrict = explode(',',$school_name);
		$district_name = $separateDistrict[1];

		$school_info['school_name'] = $school_name;
		$school_info['district_name'] = $district_name;

	
		
		$app_properties['app_name'] = "Civil and Infrastructure Inspection Report";
		$app_properties['app_id'] = "healthcare20171227153054237";

	
		
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

	

		$added = $this->rhso_users_common_model->insert_civil_infrastructure_report($doc_data, $attachments, $school_info, $app_properties, $doc_properties, $history);
		
		if($added)
		{
			redirect('rhso_users/civil_and_infrastructure_report');
		}
		else
		{
			redirect('rhso_users/civil_infrastructure_form');
		}
		
		
		
		
	}
	
	public function foodHygieneInspection()
	{
		$this->data['schools_list'] = $this->get_districtwise_schools_list();
		$this->data['today_date'] = date('Y-m-d');
		
		$this->_render_page('rhso_users/food_hygiene_inspection_report_new',$this->data);
		
	}
	//new foodinspectionfomr submit
	public function food_hygiene_inspection_new_submit()
	{

		$doc_data = array();
		$doc_data['page1'] = array();
		$doc_data['page2'] = array();
		$doc_data['page3'] = array();
		$doc_data['page4'] = array();
		$doc_data['page5'] = array();
		$doc_data['page6'] = array();
		$doc_data['page7'] = array();
		$doc_data['page8'] = array();
		$doc_data['page9'] = array();
		$doc_data['page10'] = array();
		$doc_data['page11'] = array();
		$doc_data['page12'] = array();
		$doc_data['page13'] = array();
		

		$school_name = $this->input->post('page1_SchoolInfo_SchoolName', TRUE);
		
		$principal_name  = $this->input->post('page1_SchoolInfo_PrincipalName');
		$health_supervisor_name = $this->input->post('page1_SchoolInfo_HealthSupName');
		$time_from_to  = $this->input->post('page1_SchoolInfo_Timefromto');
		$date  = $this->input->post('page1_SchoolInfo_Date');
		$category  = $this->input->post('page1_SchoolInfo_Category');

		$foodPreparationareaorKitchen_1ObservationofissuesorFaults  = $this->input->post('page2_FoodPreparationareaorKitchen_1ObservationofissuesorFaults');
		$foodPreparationareaorKitchen_2Remarks  = $this->input->post('page2_FoodPreparationareaorKitchen_2Remarks');
		$cookingMode_3ObservationofissuesorFaults  = $this->input->post('page2_CookingMode_3ObservationofissuesorFaults');
		$cookingMode_4Remarks  = $this->input->post('page3_CookingMode_4Remarks');
		$srorageofVegetablesandCuttingarea_5ObservationofissuesorFaults  = $this->input->post('page3_SrorageofVegetablesandCuttingarea_5ObservationofissuesorFaults');
		$srorageofVegetablesandCuttingarea_6Remarks  = $this->input->post('page3_SrorageofVegetablesandCuttingarea_6Remarks');
		$personalHygieneofFoodHandlers_7ObservationofissuesorFaults  = $this->input->post('page4_PersonalHygieneofFoodHandlers_7ObservationofissuesorFaults');
		$personalHygieneofFoodHandlers_8Remarks  = $this->input->post('page4_PersonalHygieneofFoodHandlers_8Remarks');
		$conditionofCookingContainers_9ObservationofissuesorFaults  = $this->input->post('page5_ConditionofCookingContainers_9ObservationofissuesorFaults');
		$conditionofCookingContainers_10Remarks  = $this->input->post('page5_ConditionofCookingContainers_10Remarks');
		$storeroom_11ObservationofissuesorFaults  = $this->input->post('page5_Storeroom_11ObservationofissuesorFaults');
		$storeroom_12Remarks  = $this->input->post('page5_Storeroom_12Remarks');
		$qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults  = $this->input->post('page6_Qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults');
		$qualityofrawmaterialforpreperationoffood_14Remarks  = $this->input->post('page6_Qualityofrawmaterialforpreperationoffood_14Remarks');
		$samplescollected_15ObservationofissuesorFaults  = $this->input->post('page6_Samplescollected_15ObservationofissuesorFaults');
		$samplescollected_16Remarks  = $this->input->post('page7_Samplescollected_16Remarks');
		$eggs_17ObservationofissuesorFaults  = $this->input->post('page7_Eggs_17ObservationofissuesorFaults');
		$eggs_18Remarks  = $this->input->post('page7_Eggs_18Remarks');
		$milkandCurd_19ObservationofissuesorFaults  = $this->input->post('page8_MilkandCurd_19ObservationofissuesorFaults');
		$milkandCurd_20Remarks  = $this->input->post('page8_MilkandCurd_20Remarks');
		$bananaorFruit_21ObservationofissuesorFaults  = $this->input->post('page8_BananaorFruit_21ObservationofissuesorFaults');
		$bananaorFruit_22Remarks  = $this->input->post('page8_BananaorFruit_22Remarks');
		$cookedpreparedfoodarticles_23ObservationofissuesorFaults  = $this->input->post('page9_Cookedpreparedfoodarticles_23ObservationofissuesorFaults');
		$cookedpreparedfoodarticles_24Remarks  = $this->input->post('page9_Cookedpreparedfoodarticles_24Remarks');
		$drinkingWater_25ObservationofissuesorFaults  = $this->input->post('page9_DrinkingWater_25ObservationofissuesorFaults');
		$drinkingWater_26Remarks  = $this->input->post('page10_DrinkingWater_26Remarks');
		$diningHall_27ObservationofissuesorFaults  = $this->input->post('page10_DiningHall_27ObservationofissuesorFaults');
		$diningHall_28Remarks  = $this->input->post('page10_DiningHall_28Remarks');
		$handwashingfacilityindiningarea_29ObservationofissuesorFaults  = $this->input->post('page11_Handwashingfacilityindiningarea_29ObservationofissuesorFaults');
		$handwashingfacilityindiningarea_30Remarks  = $this->input->post('page11_Handwashingfacilityindiningarea_30Remarks');
		$anyother_31ObservationofissuesorFaults  = $this->input->post('page12_Anyother_31ObservationofissuesorFaults');
		$anyother_32Remarks  = $this->input->post('page12_Anyother_32Remarks');
		$anyother_CommentsorSuggestions  = $this->input->post('page13_Anyother_CommentsorSuggestions');
		$anyother_Overallratingforthefoodandhygiensatinstitution = $this->input->post('page13_Anyother_Overallratingforthefoodandhygiensatinstitution');
		$inspected_by = $this->input->post('inspected_by');


 	


		
		$doc_data['page1']['School Info']['School Name']['field_ref'] = $school_name;
	
		$doc_data['page1']['School Info']['Principal name'] = $principal_name;
		$doc_data['page1']['School Info']['Health Sup Name'] = $health_supervisor_name;
		$doc_data['page1']['School Info']['Time from to'] = $time_from_to;
		$doc_data['page1']['School Info']['Date'] = $date;
		$doc_data['page1']['School Info']['Category'] = $category;
		$doc_data['page2']['Food Preparation area or Kitchen']['1 Observation of issues or Faults'] = 
				$foodPreparationareaorKitchen_1ObservationofissuesorFaults;
		$doc_data['page2']['Food Preparation area or Kitchen']['2 Remarks'] = $foodPreparationareaorKitchen_2Remarks;
		$doc_data['page2']['Cooking Mode']['3 Observation of issues or Faults'] = $cookingMode_3ObservationofissuesorFaults;
		$doc_data['page3']['Cooking Mode']['4 Remarks'] = $cookingMode_4Remarks;
		$doc_data['page3']['Srorage of Vegetables and Cutting area']['5 Observation of issues or Faults'] = $srorageofVegetablesandCuttingarea_5ObservationofissuesorFaults;
		$doc_data['page3']['Srorage of Vegetables and Cutting area']['6 Remarks'] =  $srorageofVegetablesandCuttingarea_6Remarks;
		$doc_data['page4']['Personal Hygiene of Food Handlers']['7 Observation of issues or Faults'] = $personalHygieneofFoodHandlers_7ObservationofissuesorFaults;
		$doc_data['page4']['Personal Hygiene of Food Handlers']['8 Remarks'] = $personalHygieneofFoodHandlers_8Remarks;
		$doc_data['page5']['Condition of Cooking Containers']['9 Observation of issues or Faults'] = $conditionofCookingContainers_9ObservationofissuesorFaults;

		$doc_data["page5"]["Condition of Cooking Containers"]["10 Remarks"] = $conditionofCookingContainers_10Remarks;
		$doc_data["page5"]["Store room"]["11 Observation of issues or Faults"] = $storeroom_11ObservationofissuesorFaults;
		$doc_data["page5"]["Store room"]["12 Remarks"] = $storeroom_12Remarks;
		$doc_data["page6"]["Quality of raw material for preperation of food"]["13 Observation of issues or Faults"] = $qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults;
		$doc_data["page6"]["Quality of raw material for preperation of food"]["14 Remarks"] = $qualityofrawmaterialforpreperationoffood_14Remarks;
		$doc_data["page6"]["Samples collected"]["15 Observation of issues or Faults"] = $samplescollected_15ObservationofissuesorFaults;
		$doc_data["page7"]["Samples collected"]["16 Remarks"] = $samplescollected_16Remarks;
		$doc_data["page7"]["Eggs"]["17 Observation of issues or Faults"] = $eggs_17ObservationofissuesorFaults;
		$doc_data["page7"]["Eggs"]["18 Remarks"] = $eggs_18Remarks;
		$doc_data["page8"]["Milk and Curd"]["19 Observation of issues or Faults"] = $milkandCurd_19ObservationofissuesorFaults;
		$doc_data["page8"]["Milk and Curd"]["20 Remarks"] = $milkandCurd_20Remarks;
		$doc_data["page8"]["Banana or Fruit"]["21 Observation of issues or Faults"] = $bananaorFruit_21ObservationofissuesorFaults;
		$doc_data["page8"]["Banana or Fruit"]["22 Remarks"] = $bananaorFruit_22Remarks;
		$doc_data["page9"]["Cooked prepared food articles"]["23 Observation of issues or Faults"] = $cookedpreparedfoodarticles_23ObservationofissuesorFaults;
		$doc_data["page9"]["Cooked prepared food articles"]["24 Remarks"] = $cookedpreparedfoodarticles_24Remarks;
		$doc_data["page9"]["Drinking Water"]["25 Observation of issues or Faults"] = $drinkingWater_25ObservationofissuesorFaults;
		$doc_data["page10"]["Drinking Water"]["26 Remarks"] = $drinkingWater_26Remarks;
		$doc_data["page10"]["Dining Hall"]["27 Observation of issues or Faults"] = $diningHall_27ObservationofissuesorFaults;
		$doc_data["page10"]["Dining Hal"]["28 Remarks"] = $diningHall_28Remarks;
		$doc_data["page11"]["Hand washing facility in dining area"]["29 Observation of issues or Faults"] = $handwashingfacilityindiningarea_29ObservationofissuesorFaults;
		$doc_data["page11"]["Hand washing facility in dining area"]["30 Remarks"] = $handwashingfacilityindiningarea_30Remarks;
		$doc_data["page12"]["Any other"]["31 Observation of issues or Faults"] = $handwashingfacilityindiningarea_30Remarks;
		$doc_data["page12"]["Any other"]["32 Remarks"] = $handwashingfacilityindiningarea_30Remarks;
		$doc_data["page11"]["Hand washing facility in dining area"]["30 Remarks"] = $handwashingfacilityindiningarea_30Remarks;
		$doc_data["page12"]["Any other"]["31 Observation of issues or Faults"] = $anyother_31ObservationofissuesorFaults;
		$doc_data["page12"]["Any other"]["32 Remarks"] = $anyother_32Remarks;
		$doc_data["page13"]["Any other"]["Comments or Suggestions"] = $anyother_CommentsorSuggestions;
		$doc_data["page13"]["Any other"]["Overall rating for the food and hygiens at institution"] = $anyother_Overallratingforthefoodandhygiensatinstitution;
		$doc_data["page13"]["Inspected By"]["Name"] = $inspected_by;
	

		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['food_hygiene_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['food_hygiene_attachments']['name']	= $files['food_hygiene_attachments']['name'][$i];
			 $_FILES['food_hygiene_attachments']['type']	= $files['food_hygiene_attachments']['type'][$i];
			 $_FILES['food_hygiene_attachments']['tmp_name']= $files['food_hygiene_attachments']['tmp_name'][$i];
			 $_FILES['food_hygiene_attachments']['error']	= $files['food_hygiene_attachments']['error'][$i];
			 $_FILES['food_hygiene_attachments']['size']	= $files['food_hygiene_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare20171221112544749_food_hygiene_inspection';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/RHSO_attachments/'.$controller.'/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/RHSO_attachments/$controller/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 if(array_key_exists("user_type",$session_data))
						  {
							if($session_data['user_type'] == "CADMIN")
							{
								redirect('rhso_users/foodHygieneInspection');  
							}
						  }
						
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
					
						$external_data_array = array(
												"DFF_EXTERNAL_ATTACHMENTS_".$i => array(
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
			
			  if(isset($attachments))
			  {
					   
				$external_merged_data = array_merge($attachments,$external_final);
				$attachments = array_replace_recursive($attachments,$external_merged_data);
			  }
			  else
			 {
			    $attachments = $external_final;
			 } 
		  
		 }
		
	

		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 2;
		$doc_properties['total_pages'] = 12;

		$separateDistrict = explode(',',$school_name);
		$district_name = $separateDistrict[1];

		$school_info['school_name'] = $school_name;
		$school_info['district_name'] = $district_name;

		
		$app_properties['app_name'] = "Food and Hygiene Inspection report";
		$app_properties['app_id'] = "healthcare20171221112544749";

	
		
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


		$added = $this->rhso_users_common_model->insert_food_hygiene_report($doc_data, $attachments, $school_info, $app_properties, $doc_properties, $history);
		if($added)
		{
			redirect('rhso_users/food_hygiene_inspection_report');
		}
		else
		{
			redirect('rhso_users/foodHygieneInspection');
		}
		
		
		
	}
	
	
	


	// ------------------Basic Dashboard------------------------------

	/**
	 * Helper: Screening PIE with tabel format
	 
	 * @author yoga 
	 */
	 public function basic_dashboard($date = FALSE,  $screening_duration = "Yearly")
	{
		$this->data['today_date'] = date('Y-m-d');
		
		$count = 0;
		$screening_report = $this->panacea_common_model->get_all_screenings($date,$screening_duration);
		
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}

		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name        = $loggedinuser['dt_name'];


		$dist_list = $this->rhso_users_common_model->get_all_district($cro_district_name);
		$cro_dist = $dist_list[0]['_id'];
		$this->data['schools_list'] = $this->rhso_users_common_model->get_all_schools($cro_dist);
		$this->_render_page('rhso_users/rhso_basic_dashboard',$this->data);

	}

	public function show_hb_submitted_list()
	{
		$this->data['today_date'] = date('Y-m-d');
		
		$count = 0;		

	    $loggedinuser = $this->session->userdata("customer");
        $cro_district_name        = $loggedinuser['dt_name'];


        $dist_list = $this->rhso_users_common_model->get_all_district($cro_district_name);
        $cro_dist = $dist_list[0]['_id'];
        $this->data['schools_list'] = $this->rhso_users_common_model->get_all_schools($cro_dist);
       
		$this->_render_page('rhso_users/show_hb_submitted_list',$this->data);

	}

	public function rhso_hb_pie_view()
	{

		$this->check_for_admin ();
		$this->check_for_plan ( 'hb_pie_view' );
		$current_month = $_POST["current_month"];
		$school_name = $_POST["school_name"];
/*
		echo print_r($current_month,true);
		echo print_r($school_name,true);
		exit();*/
			
		$this->data = $this->rhso_users_common_lib->hb_pie_view_lib_month_wise($current_month,$school_name);
		$this->output->set_output(json_encode($this->data));
	}
	
	function basic_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];

		$loggedinuser = $this->session->userdata("customer");
		$district_name        = $loggedinuser['dt_name'];
		
		//$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];

		$this->data = $this->rhso_common_lib->basic_dashboard_with_date($today_date,$screening_pie_span,$district_name,$school_name);


		$this->output->set_output($this->data);
	
	}

	public function initiate_request_count_all_today_date()
	{
		$today_date = $_POST['today_date'];
		$loggedinuser = $this->session->userdata("customer");
		$district_name        = $loggedinuser['dt_name'];
		$document = $this->rhso_common_lib->get_initaite_requests_count_today_date($today_date,$district_name);
		//log_message('error',"document=================268000".print_r($document,true));
		//echo print_r(count($this->data['count']),true); exit;
		$this->output->set_output(json_encode($document));
	}

	public function get_show_ehr_details()
	{
		$date = $_POST['to_date_new'];
		$school_name = $_POST['school_name_new'];
		$request_type = $_POST['request_type_new'];

		$loggedinuser = $this->session->userdata("customer");
		$district_name        = $loggedinuser['dt_name'];

		$this->data['students_details'] = $this->rhso_common_model->get_show_ehr_details($request_type,$date,$school_name,$district_name);
		
		$this->_render_page('rhso_users/basic_show_ehr_view',$this->data);
	}

	public function drill_down_screening_to_students_load_ehr_new_dashboard($_id)
	{
		//$_id = $_POST['ehr_data'];
		
		$docs = $this->rhso_common_model->drill_down_screening_to_students_load_ehr_new_dashboard($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		//$this->data['history'] = $docs['history'];
		
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('rhso_users/rhso_reports_display_ehr_new_dashboard',$this->data);
	}

	public function get_screening_data_with_district_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_screening_data_with_district_school');

		
		$today_date = $_POST['today_date'];
		$school_name = $_POST['school_name'];
		$school_details = $this->panacea_common_model->get_school_code_by_school_name($school_name);
		$school_code = $school_details[0]['school_code'];

		
		$school_info = $this->panacea_common_model->get_school_name_by_school_code($school_code);
		$school_email = $school_info[0]['email'];
		
		$email = str_replace('@', '#', $school_email);
		
		$this->data = $this->rhso_common_lib->get_data_with_district_school($email, $date = false, $screening_duration = "Yearly", $today_date,$school_name);
	
		$this->output->set_output($this->data);

	}

	public function get_screening_data_with_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_screening_data_with_abnormalities');
	
		$selectedLabel = $_POST['selectedLabel'];
		
		$schoolEmail = $_POST['schoolEmail'];
		
		$this->data = $this->panacea_common_model->get_abnormalities_screening_count($selectedLabel, $schoolEmail, $date = false, $screening_duration = "Yearly");
		
		$this->output->set_output(json_encode($this->data));


	}

	function drill_down_screening_to_students_count()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_count');
		/*$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];*/
		$symptome_type = $_POST['symptome_type'];
		$schoolEmail = $_POST['schoolEmail'];
		$docs = $this->panacea_common_model->get_drilling_screenings_students_count(/*$data,$today_date,$screening_pie_span*/$symptome_type, $schoolEmail);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}

	function drill_down_screening_to_students_load_ehr_count()
	{

		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr_count');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);

		$get_docs = $this->panacea_common_model->get_drilling_screenings_students_docs_count($docs_id);
		
		$ehr_navigation = $_POST['ehr_navigation'];
		$this->data['students']   = $get_docs;

		$this->data['navigation'] = $ehr_navigation;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_users/screening_to_students_load_ehr',$this->data);
	}

	

	/*public function checklist_inspectors()
	{
		$loggedinuser = $this->session->userdata("customer");
		$cro_district_name = $loggedinuser['dt_name'];
		$cro_district_code = $loggedinuser['district_code'];

		$dist_list = $this->rhso_users_common_model->get_all_district($cro_district_name);
		$cro_dist = $dist_list[0]['_id'];
		//$this->data['get_districtwise_schools'] = $this->rhso_users_common_model->get_all_schools($cro_dist);
		$this->data['schools_list'] = $this->get_districtwise_schools_list();
		
		$this->_render_page('rhso_users/checklist_inspectors_new',$this->data);
	}*/

	//**********************rhso users import xl reports*******************************\\\\\\\\\\\\\\\\
	function import_rhso_report_xl()
	{
		
	
		$this->data['schools_list'] = $this->get_districtwise_schools_list();

		$this->_render_page('rhso_users/rhso_import_xl_sheet', $this->data);
	}
	
	function import_rhso_report_xl_sheet_v3()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_rhso_report_xl_sheet_v3');

		$post = $_POST;
		
		$this->data = $this->rhso_users_common_lib->import_rhso_reports_XL();
		//$this->data = $this->rhso_users_common_lib->import_rhso_report_xl_sheet_v3($post);
		
		redirect('rhso_users/basic_dashboard');
	}

	function get_rhso_submitted_report_count()
	{
		$xl_date = $_POST['xl_date'];

		$this->data = $this->rhso_users_common_model->get_rhso_submitted_report_count($xl_date);
		
		$this->output->set_output(json_encode($this->data));
	}
	function download_rhso_report_xl_sheet()
	{
		
		$schoolName = $_POST['schoolName'];
		$todayDate = $_POST['todayDate'];
		$this->data = $this->rhso_users_common_model->get_rhso_submitted_report_date_school($schoolName, $todayDate);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	
		
}
