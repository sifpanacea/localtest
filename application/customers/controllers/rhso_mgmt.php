<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Rhso_mgmt extends My_Controller {

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
		$this->load->library('paas_common_lib');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
		$this->load->model('rhso_mgmt_model');
		$this->load->library('rhso_common_lib');
		$this->load->library('session');
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
		log_message("debug","index=============687");
		redirect('rhso_mgmt/to_dashboard');
	}
	
	/*****************************************
		Manage states
	******************************************/
	/* public function panacea_mgmt_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_states');
		$this->data = $this->panacea_common_lib->panacea_mgmt_states();
		
		$this->_render_page('panacea_admins/panacea_mgmt_states',$this->data);
	}
	
	public function create_state()
	{
	 	$this->panacea_mgmt_model->create_state($_POST);	
	 	redirect('panacea_mgmt/panacea_mgmt_states');
	}
	 
	public function panacea_mgmt_delete_states($st_id)
	{
	 	$this->panacea_mgmt_model->delete_state($st_id);
	 	redirect('panacea_mgmt/panacea_mgmt_states');
	} */ 
	
	public function panacea_mgmt_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_district');
        $this->data = $this->panacea_common_lib->panacea_mgmt_district();
		
		$this->_render_page('panacea_admins/panacea_mgmt_dist',$this->data);
	}
	
	public function create_district()
	{
	 	$this->panacea_mgmt_model->create_district($_POST);	
	 	redirect('panacea_mgmt/panacea_mgmt_district');
	}
	 
	public function panacea_mgmt_delete_dists($dt_id)
	{
	 	$this->panacea_mgmt_model->delete_district($dt_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_district');
	}
	
	public function panacea_mgmt_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_health_supervisors');
		
		$this->data['health_supervisors'] = $this->rhso_common_model->get_all_health_supervisors();
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->data['health_supervisorscount'] = $this->rhso_common_model->health_supervisorscount();
		
        //$this->data = $this->panacea_common_lib->panacea_mgmt_health_supervisors();
				
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_health_supervisors',$this->data);
	}
	
	public function create_health_supervisors()
	{
	 	$insert = $this->rhso_common_model->create_health_supervisors($_POST);
	 	if($insert){
	 		redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
	 	}else{
	 		$this->data = $this->rhso_common_lib->panacea_mgmt_health_supervisors();
	 		
	 		//$this->data = "";
	 		$this->_render_page('panacea_admins/panacea_mgmt_health_supervisors',$this->data);
	 	}
	 	
	}
	 
	public function panacea_mgmt_delete_health_supervisors($hs_id)
	{
	 	$this->panacea_mgmt_model->delete_health_supervisors($hs_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
	}
	/////////////////////////////////////////////////////////
	
	public function panacea_mgmt_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_doctors');
	
		$this->data = $this->panacea_common_lib->panacea_mgmt_doctors();
		
		$this->_render_page('panacea_admins/panacea_mgmt_doctors',$this->data);
	}
	
	public function create_doctor()
	{
		$insert = $this->panacea_mgmt_model->create_doctor($_POST);
		if($insert){
			redirect('panacea_mgmt/panacea_mgmt_doctors');
		}else{
			$this->data = $this->panacea_common_lib->panacea_mgmt_doctors();			
			
			//$this->data = "";
			$this->_render_page('panacea_admins/panacea_mgmt_doctors',$this->data);
		}
		 
	}
	
	public function panacea_mgmt_delete_doctor($hs_id)
	{
		$this->panacea_mgmt_model->delete_doctor($hs_id);
		redirect('panacea_mgmt/panacea_mgmt_doctors');
	}
	
	//////////////////////////////////////////////////////
	
	public function panacea_mgmt_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_schools');
        $this->data = $this->rhso_common_lib->panacea_mgmt_schools();		
		
		//$this->data = "";
		$this->_render_page('rhso_admins/panacea_mgmt_schools',$this->data);
	}
	
	public function create_school()
	{
	 	//$this->panacea_mgmt_model->create_school($_POST);	
	 	//redirect('panacea_mgmt/panacea_mgmt_schools');
	 	
	 	$insert = $this->panacea_common_model->create_school($_POST);
	 	if($insert){
	 		redirect('panacea_mgmt/panacea_mgmt_schools');
	 	}else{
	 		$this->data = $this->panacea_common_lib->panacea_mgmt_schools();
	 			
	 		//$this->data = "";
	 		$this->_render_page('panacea_admins/panacea_mgmt_schools',$this->data);
	 	}
	}
	 
	public function panacea_mgmt_delete_school($school_id)
	{
	 	$this->panacea_mgmt_model->delete_school($school_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_schools');
	}
	
	//000000000000000000000000000000000000
	public function panacea_mgmt_classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_schools');
        $this->data = $this->panacea_common_lib->panacea_mgmt_classes();
		
		$this->_render_page('panacea_admins/panacea_mgmt_classes',$this->data);
	}
	
	public function create_class()
	{
	 	$this->panacea_mgmt_model->create_class($_POST);	
	 	redirect('panacea_mgmt/panacea_mgmt_classes');
	}
	 
	public function panacea_mgmt_delete_class($class_id)
	{
	 	$this->panacea_mgmt_model->delete_class($class_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_classes');
	}
	
	public function panacea_mgmt_sections()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_schools');
        $this->data = $this->panacea_common_lib->panacea_mgmt_sections();
		
		$this->_render_page('panacea_admins/panacea_mgmt_sections',$this->data);
	}
	
	public function create_section()
	{
	 	$this->panacea_mgmt_model->create_section($_POST);	
	 	redirect('panacea_mgmt/panacea_mgmt_sections');
	}
	 
	public function panacea_mgmt_delete_section($section_id)
	{
	 	$this->panacea_mgmt_model->delete_section($section_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_sections');
	}
	
	//syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
	public function panacea_mgmt_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_symptoms');
        $this->data = $this->panacea_common_lib->panacea_mgmt_symptoms();
		
		$this->_render_page('panacea_admins/panacea_mgmt_symptoms',$this->data);
	}
	
	public function create_symptoms()
	{
	 	$this->panacea_mgmt_model->create_symptoms($_POST);	
	 	redirect('panacea_mgmt/panacea_mgmt_symptoms');
	}
	 
	public function panacea_mgmt_delete_symptoms($symptoms_id)
	{
	 	$this->panacea_mgmt_model->delete_symptoms($symptoms_id);	
	 	redirect('panacea_mgmt/panacea_mgmt_symptoms');
	}
	
	public function panacea_mgmt_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_diagnostics');
		$this->data = $this->panacea_common_lib->panacea_mgmt_diagnostic();	
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_diagnostics',$this->data);
	}
	
	public function create_diagnostic()
	{
		$this->panacea_common_model->create_diagnostic($_POST);
		redirect('panacea_mgmt/panacea_mgmt_diagnostic');
	}
	
	public function panacea_mgmt_delete_diagnostic($diagnostic_id)
	{
		$this->panacea_mgmt_model->delete_diagnostic($diagnostic_id);
		redirect('panacea_mgmt/panacea_mgmt_diagnostic');
	}
	
	
	public function panacea_mgmt_hospitals()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_hospitals');
		$this->data = $this->panacea_common_lib->panacea_mgmt_hospitals();
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_hospitals',$this->data);
	}
	
	public function create_hospital()
	{
		$this->panacea_common_model->create_hospital($_POST);
		redirect('panacea_mgmt/panacea_mgmt_hospitals');
	}
	
	public function panacea_mgmt_delete_hospital($hospital_id)
	{
		$this->panacea_mgmt_model->delete_hospital($hospital_id);
		redirect('panacea_mgmt/panacea_mgmt_hospitals');
	}
	
	public function panacea_mgmt_emp()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_emp');
		$this->data = $this->panacea_common_lib->panacea_mgmt_emp();
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_emp',$this->data);
	}
	
	public function create_emp()
	{
		$this->panacea_mgmt_model->create_emp($_POST);
		redirect('panacea_mgmt/panacea_mgmt_emp');
	}
	
	public function panacea_mgmt_delete_emp($emp_id)
	{
		$this->panacea_mgmt_model->delete_emp($emp_id);
		redirect('panacea_mgmt/panacea_mgmt_emp');
	}
	
	//===============reports======================================
	
	public function panacea_reports_ehr()
	{
		$this->data["message"] = "";
		$this->_render_page('rhso_admins/panacea_reports_ehr',$this->data);
	}
	
	public function panacea_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->rhso_common_lib->panacea_reports_display_ehr($post);
		
	 	$this->_render_page('rhso_admins/panacea_reports_display_ehr',$this->data);
	}
	
	public function panacea_reports_display_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->rhso_common_lib->panacea_reports_display_ehr_uid($post);
	
		$this->_render_page('rhso_admins/panacea_reports_display_ehr',$this->data);
	}
	
	public function panacea_reports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_students');
        $this->data = $this->panacea_common_lib->panacea_reports_students();
		
		$this->_render_page('panacea_admins/panacea_reports_students',$this->data);
	}
	
	public function panacea_reports_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_doctors');
		$this->data = $this->panacea_common_lib->panacea_reports_doctors();
	
		$this->_render_page('panacea_admins/panacea_reports_doctors',$this->data);
	}
	
	public function panacea_reports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_hospital');
		$this->data = $this->panacea_common_lib->panacea_reports_hospital();
	
		$this->_render_page('panacea_admins/panacea_reports_hospitals',$this->data);
	}
	
	public function panacea_reports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_school');
		$this->data = $this->rhso_common_lib->panacea_reports_school();
	
		//$this->data = "";
		$this->_render_page('rhso_admins/panacea_reports_schools',$this->data);
	}
	
	public function panacea_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_symptom');
		$this->data = $this->panacea_common_lib->panacea_reports_symptom();
	
		$this->_render_page('panacea_admins/panacea_reports_symptom',$this->data);
		}
	
	// --------------------------------------------------------------------
	
	function student_db_to_excel(){
		ini_set('memory_limit', '1024M');
		$docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWREIS CHITKUL(G),MEDAK")->get("healthcare2016226112942701");//healthcare2016226112942701
		 
		////log_message('debug','11111111111111111111111111111111111111111111'.print_r($striped_doc,true));
		////log_message('debug','2222222222222222222222222222222222222222222'.print_r(json_encode($striped_doc),true));
		 
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
		$objPHPExcel->getProperties()->setTitle("PANACEA Health Report");
		$objPHPExcel->getProperties()->setSubject("PANACEA Health Report");
		$objPHPExcel->getProperties()->setDescription("Document collection of PANACEA health check up.");
	
		// Add some data
		echo date('H:i:s') . " Add some data\n";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'PANACEA Health Check Up');
	
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
						////log_message('debug','doctorrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth'],true));
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
				////log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($doc["doc_data"]["external_attachments"],true));
				$objPHPExcel->getActiveSheet()->SetCellValue('AV2', 'External Attachments');
				$i = 1;
				foreach($doc["doc_data"]["external_attachments"] as $attachment){
					$objPHPExcel->getActiveSheet()->SetCellValue('AV4', 'Attachment_'.$i);
					////log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($attachment,true));
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
		$objWriter->save(EXCEL."/PANACEA_Health_Report.xlsx");
	
		$this->secure_file_download(EXCEL."/PANACEA_Health_Report.xlsx");
	
		unlink(EXCEL."/PANACEA_Health_Report.xlsx");
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
		$this->data = $this->rhso_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		
		//log_message("debug","this->data==============687".print_r($this->data,true));
		$this->_render_page('rhso_admins/rhso_admin_dash', $this->data);
	
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
		$request_pie_status = $_POST["request_pie_status"];
		log_message('debug','opppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$this->data = $this->rhso_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$request_pie_status = $_POST['request_pie_status'];
		$this->data = $this->rhso_common_lib->update_request_pie($today_date,$request_pie_span,$request_pie_status);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->rhso_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->rhso_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->rhso_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}

	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->rhso_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->rhso_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->rhso_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->rhso_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->rhso_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		$doc_list = $this->rhso_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_admins/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$this->data['docs'] = $this->panacea_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$docs = $this->rhso_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		//$this->data['history'] = $docs['history'];
		
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('rhso_admins/panacea_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->panacea_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
	
		$this->data['doc'] = $this->panacea_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('rhso_admins/panacea_reports_display_ehr',$this->data);
	}
	
	function drilldown_absent_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_absent_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		//log_message("debug","drilldown_absent_to_districts=====839=====".print_r($_POST,true));
		$absent_report = json_encode($this->rhso_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drilling_absent_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_absent_to_schools');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$absent_report = json_encode($this->rhso_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
		////log_message("debug","ssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($absent_report,true));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$docs = $this->rhso_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		//log_message('debug','drill_down_absent_to_students_load_ehr=====GET=====874====='.print_r($_GET,true));
		$temp = base64_decode($_GET['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_GET['ehr_data_for_absent']),true);
		//log_message('debug','drill_down_absent_to_students_load_ehr=====UI_id=====877====='.print_r($UI_id,true));
		$get_docs = $this->rhso_common_model->get_drilling_absent_students_docs($UI_id);
		//log_message('debug','drill_down_absent_to_students_load_ehr=====get_docs=====879====='.print_r($get_docs,true));
		$navigation = $_GET['ehr_navigation_for_absent'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('rhso_admins/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 1111111111111111111111111111111111111111111111111111111");
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_districts');
		////log_message("debug","innnnnnnnnnnnnnnnnnnnnnnnpie drillllllllllllllllllllllllll".print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST["request_pie_status"];
		$request_report = json_encode($this->rhso_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		////log_message("debug","innnnnnnnnnnnnnnnnnnnnnnnpie ppppppppppppppppppppppppppppppppppppppppp".print_r($screening_report,true));
		$this->output->set_output($request_report);
	}
	
	function drilling_request_to_schools()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 4444444444444444444444444444444444444444444444444444444444444444444444");
		$this->check_for_admin();
		$this->check_for_plan('drilling_request_to_schools');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST["request_pie_status"];
		$request_report = json_encode($this->rhso_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 77777777777777777777777777777777777777777777777777777777");
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST["request_pie_status"];
		$docs = $this->rhso_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn dddddddddddddddddddddddddddddddddddddddddddddddddddddddddd");
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request']),true);
		$get_docs = $this->rhso_common_model->get_drilling_request_students_docs($UI_id);
		
		$navigation = $_POST['ehr_navigation_for_request'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn gggggggggggggggggggggggggggggggggggggggggggggggggggggg");
		$this->_render_page('rhso_admins/drill_down_request_to_students_load_ehr',$this->data);
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
		$request_pie_status = $_POST["request_pie_status"];
		$identifiers_report = json_encode($this->rhso_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
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
		$request_pie_status = $_POST["request_pie_status"];
		$identifiers_report = json_encode($this->rhso_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
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
		$request_pie_status = $_POST["request_pie_status"];
		$docs = $this->rhso_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_identifiers']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_identifiers']),true);
		$get_docs = $this->rhso_common_model->get_drilling_identifiers_students_docs($UI_id);
		
		$navigation = $_POST['ehr_navigation_for_identifiers'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('rhso_admins/drill_down_identifiers_to_students_load_ehr',$this->data);
	}
	
	//============================================================================================================
		
	function panacea_imports_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_diagnostic');
		$this->data = $this->panacea_common_lib->panacea_imports_diagnostic();
	
		$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
	}
	
	function import_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_diagnostic');
	
		$post = $_POST;
		$this->data = $this->panacea_common_lib->import_diagnostic($post);
		
		if($this->data == "redirect_to_diagnostic_fn")
		{
			redirect('panacea_mgmt/panacea_mgmt_diagnostic');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
		}
		
	}
	
	function panacea_imports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_hospital');
	
		$this->data = $this->panacea_common_lib->panacea_imports_hospital();
	
		$this->_render_page('panacea_admins/panacea_imports_hospital', $this->data);
	}
	
	function import_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_hospital');
		
		$post = $_POST;
		$this->data = $this->panacea_common_lib->import_hospital($post);
		
		if($this->data == "redirect_to_hospital_fn")
		{
			redirect('panacea_mgmt/panacea_mgmt_hospitals');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_hospital', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_hospital', $this->data);
		}
	}
	
	function panacea_imports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_school');
	
		$this->data = $this->panacea_common_lib->panacea_imports_school();
	
		$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
	}
	
	function import_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
		$post = $_POST;
		$this->data = $this->panacea_common_lib->import_school($post);
		
		if($this->data == "redirect_to_school_fn")
		{
			redirect('panacea_mgmt/panacea_mgmt_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
		}
	}
	
	function panacea_imports_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_health_supervisors');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('panacea_admins/panacea_imports_health_supervisors', $this->data);
	}
	
	function import_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
	
		$post = $_POST;
		$this->data = $this->panacea_common_lib->import_health_supervisors($post);
		
		if($this->data == "redirect_to_hs_fn")
		{
			redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_health_supervisors', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_health_supervisors', $this->data);
		}
	}
	
	function panacea_imports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_students');
		
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
	}
	
	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');
		
		$post = $_POST;
		$this->data = $this->panacea_common_lib->import_students($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('panacea_mgmt/panacea_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
		}
	}
	
	function update_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('undate_students');
		
		$this->data = $this->panacea_common_lib->update_students();
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('panacea_mgmt/panacea_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
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
		/* ////log_message("debug","entered into read_excel cell_collection".print_r($cell_collection,true)); */
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
				//log_message('debug','$header[$row_value][$column]======140'.print_r($header[$row_value][$column],true));
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
			$doc_properties['doc_owner'] = "PANACEA";
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
			
		//////log_message("debug","entered into read_excel form_data".print_r($form_data,true));
	}
	
	//==============================CC mgmt=================================
	public function panacea_mgmt_cc()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_cc');
	
		$total_rows = $this->panacea_mgmt_model->cc_users_count();
	
		//---pagination--------//
		$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->pagination->initialize($config);
	
		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['cc_users'] = $this->panacea_mgmt_model->get_cc_users($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->data['cc_count'] = $total_rows;
	
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_cc_users',$this->data);
	}
	
	public function create_cc_user()
	{
		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppppppp.'.print_r($_POST,true));
		$insert = $this->panacea_mgmt_model->create_cc_user($_POST);
		//log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii.'.print_r($insert,true));
		if($insert){
			redirect('panacea_mgmt/panacea_mgmt_cc');
		}else{
			//log_message('debug','errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($this->panacea_mgmt_model->errors(),true));
			$total_rows = $this->panacea_mgmt_model->cc_users_count();
	
			//---pagination--------//
			$config = $this->paas_common_lib->set_paginate_options($total_rows,10);
		
			//Initialize the pagination class
			$this->pagination->initialize($config);
		
			//control of number page
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		
			//find all the categories with paginate and save it in array to past to the view
			$this->data['cc_users'] = $this->panacea_mgmt_model->get_cc_users($config['per_page'], $page);
			//create paginate´s links
			$this->data['links'] = $this->pagination->create_links();
		
			//number page variable
			$this->data['page'] = $page;
	
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->panacea_mgmt_model->errors() ? $this->panacea_mgmt_model->errors() : $this->session->flashdata('message')));
			
			$this->data['cc_count'] = $total_rows;
	
			//$this->data = "";
			$this->_render_page('panacea_admins/panacea_mgmt_cc_users',$this->data);
		}
		 
	}
	
	public function panacea_mgmt_delete_cc_user($cc_id)
	{
		$this->panacea_mgmt_model->delete_cc_user($cc_id);
		redirect('panacea_mgmt/panacea_mgmt_cc');
	}
	
	public function panacea_reports_students_filter()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_students_filter');
		$this->data = $this->rhso_common_lib->panacea_reports_students_filter();
	
		//$this->data = "";
		$this->_render_page('rhso_admins/panacea_reports_students_filter',$this->data);
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->rhso_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_students_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_students_list');
	
		$school_name = $_POST['school_name'];
		$dist_name = $_POST['dist_name'];
	
		$this->data = $this->rhso_common_model->get_students_by_school_name($school_name,$dist_name);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function pie_export()
	{
		$this->data['today_date'] = date('Y-m-d');
		$this->data['distslist'] = $this->rhso_common_model->get_all_district();
		$this->_render_page('rhso_admins/panacea_pie_export',$this->data);
	}
	
	public function generate_excel_for_absent_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_common_lib->generate_excel_for_absent_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_request_pie()
	{
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_common_lib->generate_excel_for_request_pie($today_date,$request_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_screening_pie()
	{
		//log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($_POST,true));
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->rhso_common_lib->generate_excel_for_screening_pie($today_date,$screening_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	function screening_pie_data_for_stage4_new(){
		$this->panacea_common_model->screening_pie_data_for_stage4_new();
	}
	
	function forward_request(){
		$this->panacea_common_lib->submit_request_to_doctor($_POST);
		redirect('panacea_mgmt/to_dashboard');
	}
	
	function panacea_create_group()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_create_group');
	
		$this->data = $this->rhso_common_lib->panacea_groups();
	
		$this->_render_page('rhso_admins/create_group', $this->data);
	}
	public function create_group()
	{
		$this->rhso_mgmt_model->create_group($_POST);
		redirect('rhso_mgmt/panacea_create_group');
	}
	public function save_users_to_group()
	{
		//log_message("debug","ggggggggggrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr=======".print_r($_POST,true));
		$this->rhso_mgmt_model->save_users_to_group($_POST);
		
		redirect('rhso_mgmt/group_msg');
	}
	public function delete_group($_id)
	{
		$this->rhso_mgmt_model->delete_chat_group($_id);
		redirect('rhso_mgmt/panacea_create_group');
	}
	
	function group_msg()
	{
		$this->check_for_admin();
		$this->check_for_plan('group_msg');
	
		$this->data = $this->rhso_common_lib->group_msg();
	
		$this->_render_page('rhso_admins/group_msg', $this->data);
	}
	
	function get_messages($msg_id,$message = false)
	{
		$this->check_for_admin();
		$this->check_for_plan('get_messages');
	
		if($message === false){
			//log_message('debug','hereeeeeeeeeeeeeeeeeeeeeeeeeee================================.'.print_r($msg_id,true));
			$result = $this->rhso_common_model->get_messages($msg_id);
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
			$this->data = $this->rhso_common_model->add_message($_POST,$msg_id);
			
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
		//log_message('debug','mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm===========================.'.print_r($customer,true));
		
// 		DEBUG - 2016-11-01 12:21:34 --> mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm===========================.Array
// 		(
// 		[identity] => panacea.user@gmail.com
// 		[username] => PANACEA Admin
// 		[email] => panacea.user@gmail.com
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
	
		$this->data = $this->rhso_common_lib->user_msg();
	
		$this->_render_page('rhso_admins/user_msg', $this->data);
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
	
		$this->data = $this->rhso_common_lib->user_msg();
	
		$this->_render_page('rhso_admins/multi_user_msg', $this->data);
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
		
		$data = $this->rhso_mgmt_model->get_sanitation_infrastructure_model($district_name,$school_name);
		log_message('debug','get_sanitation_infrastructure_model=====1730=='.print_r($data, true));
		
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
			log_message('debug','get_sanitation_infrastructure_model=====json_encode=='.print_r($bar_chart_data, true));

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
	  
	  $sanitation_report_pie = $this->rhso_common_model->get_sanitation_report_pie_data($date,$search_criteria,$opt);
	  log_message("debug","sanitation_report_pie==============1884".print_r($sanitation_report_pie,true));
	  
	  if($sanitation_report_pie)
	  {
	   $json_encode = $this->output->set_output(json_encode($sanitation_report_pie));
	   log_message("debug","json_encode==============1889".print_r($json_encode,true));
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
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->panacea_common_lib->generate_excel_for_absent_sent_schools($date,$schools_list);
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
	   $file_path = $this->panacea_common_lib->generate_excel_for_absent_not_sent_schools($date,$schools_list);
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
	   $file_path = $this->panacea_common_lib->generate_excel_for_sanitation_report_sent_schools($date,$schools_list);
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
	   $file_path = $this->panacea_common_lib->generate_excel_for_sanitation_report_not_sent_schools($date,$schools_list);
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
	   
	   $students_data = $this->panacea_common_model->get_students_by_school_name($school_name,$dist_name);
	   ////log_message('debug','0000000000000000000000000000000000000000000000..'.print_r($students_data,true));
	   foreach ( $students_data as $student){
		   $class = $student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"];
		   
		   ////log_message('debug','school_data111111111111111111111111111..'.print_r($student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"],true));
		   // if(($class == $last_class)){
			   // $class = "2016 ".$last_class." passed out";
		   // }else 
			/* commented by bhanu 
			if((intval($class) == 10)){
			   //$class = "2017"." 10th passed out";
		   }else if((intval($class) == 12)){
			   //$class = "2017"." 12th passed out";
		   }else if($class == "Inter 1st"){
			   $class = "Inter 2nd";
		   }else{
			   if(isset($class) && !empty($class) && ($class != "") && ($class != " ") && (intval($class) < 11)){
				   //$class++;
			   }
		   }*/
		   $student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"] = $class;
		   ////log_message('debug','school_data222222222222222222222222222..'.print_r($student['doc_data']["widget_data"]["page2"]["Personal Information"]["Class"],true));
		   $doc_id = $student['_id'];
		   //-------disabled-------------
		   $update_data = $this->panacea_common_model->update_student_data(array('doc_data.widget_data.page2.Personal Information.Class' => $class),$doc_id);
	   }
	   
	   
	   redirect('panacea_mgmt/panacea_reports_students_filter');
	}
	
	public function post_note() {
		
		$post = $_POST;
		
		$token = $this->rhso_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() {
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->rhso_common_lib->delete_ehr_note($doc_id);
	   
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
	  
	  $is_created = $this->panacea_common_model->create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames);
	  
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
	  
	  $compliance = $this->panacea_common_model->calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken);
	  
	  //log_message('debug','update_schedule_followup==3='.print_r($compliance,true));
	  
	  $is_updated = $this->panacea_common_model->update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date);
	  
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
		
	  $pcompl_raw_data   = $this->rhso_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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

		       $value_date = new DateTime($schedule_date);
			   $Datestart  = new DateTime($begin);
			   $Dateend    = new DateTime($end);

		        if($value_date >= $Datestart && $value_date <= $Dateend )
		       {
			   
				   $date_array  = explode('-',$schedule_date);
				   $new_entry_d = $date_array[0]."-".$date_array[1]; 
				
				   $date = new DateTime($schedule_date);
				   $new_date = $date->getTimestamp()*1000;
				   
				   $pre_temp = array($new_date,(int) $values['compliance']);
				   array_push($graph_data,$pre_temp);

			   }
		       
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
		
	  $pcompl_raw_data   = $this->rhso_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	

	 public function post_note_request() {
		
		$post = $_POST;
		
		$token = $this->panacea_common_lib->insert_request_note($post);
	   
		$this->output->set_output($token);
	}
    
    // ------------------------------------------------------------------------
	 
	/**
	* Helper : Update note content ( request notes )
	*
	* @return string
	*
	* @author Selva
	*/

	public function update_note_request() 
	{
		
		if(isset($_POST['note_id']) && isset($_POST['note']))
		{
			// POST Data
			$doc_id  = $this->input->post('doc_id',true);
			$note_id = $this->input->post('note_id',true);
			$note    = $this->input->post('note',true);

			$token = $this->panacea_common_model->update_request_note($doc_id,$note_id,$note);

			if($token)
			{
  				$this->output->set_output('NOTE_UPDATE_SUCCESS');
			}
			else
			{
				$this->output->set_output('NOTE_UPDATE_FAILED');
			}
		}
		else
		{
		   $this->output->set_output('REQUIRED_PARAMS_MISSING');	
		}
		
	}
	
	public function chronic_pie_view(){
		$this->check_for_admin();
		$this->check_for_plan('chronic_pie_view');
		
		$this->data = $this->rhso_common_lib->chronic_pie_view();
		
		$this->_render_page('rhso_admins/chronic_pie_view',$this->data);
	}
	
	public function update_chronic_request_pie(){
		
		$this->check_for_admin();
		$this->check_for_plan('update_chronic_request_pie');
		
		$status_type = $_POST["status_type"];
		
		$this->data = $this->rhso_common_lib->update_chronic_request_pie($status_type);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function drill_down_request_to_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_symptoms');
		
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];

		$symptoms_report = json_encode($this->rhso_common_model->drill_down_request_to_symptoms($data,$status_type));
		$this->output->set_output($symptoms_report);
	}
	
	function drilldown_chronic_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_districts');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$identifiers_report = json_encode($this->rhso_common_model->drilldown_chronic_request_to_districts($data,$status_type));
		$this->output->set_output($identifiers_report);
	}
	
	function drilldown_chronic_request_to_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_school');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$request_report = json_encode($this->rhso_common_model->drilldown_chronic_request_to_schools($data,$status_type));
		$this->output->set_output($request_report);
	}
	
	function drilldown_chronic_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_students');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$docs = $this->rhso_common_model->drilldown_chronic_request_to_students($data,$status_type);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_chronic_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_chronic_request_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->rhso_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		$doc_list = $this->rhso_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('rhso_admins/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	public function add_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed_view' );
		$this->data ['message'] = "";
		
		$this->_render_page ( 'rhso_admins/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		$news_return = $this->rhso_common_lib->add_news_feed ();
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'rhso_admins/add_news_feed', $this->data );
		}else{
			redirect('rhso_mgmt/manage_news_feed_view');
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->rhso_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'rhso_admins/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->rhso_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'rhso_mgmt/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->rhso_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'rhso_admins/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->rhso_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'rhso_admins/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	
	
	
	public function rhso_submitted_reports()
	{
		
		$this->data = $this->rhso_common_lib->rhso_submitted_reports_lib();
		$this->_render_page('rhso_admins/rhso_submitted_reports_view',$this->data);
		
	}
	
	public function rhso_submitted_reports_district_wise()
	{
		//$rhso_reports_span = $this->input->post('rhso_reports_span', TRUE);
		$district 		   = $this->input->post('dt_name', TRUE);
		$school_name 	   = $this->input->post('school_name', TRUE);
		
		$this->data['sanitation_inspection_data'] = $this->rhso_common_lib->get_sanitation_inspection_report_lib(/* $rhso_reports_span,  */$district, $school_name);
		if(!empty($this->data['sanitation_inspection_data']))
		{
			$this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
		
		
	}
	
		
}