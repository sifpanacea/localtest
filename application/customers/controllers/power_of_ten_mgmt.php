<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Power_of_ten_mgmt extends My_Controller {

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
		$this->load->model('panacea_mgmt_model');
		$this->load->model('power_of_ten_common_model');
		$this->load->model('power_of_ten_mgmt_model');
		$this->load->library('panacea_common_lib');
		$this->load->library('power_of_ten_common_lib');
		$this->load->library('tswreis_schools_common_lib');
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
		//redirect('panacea_mgmt/to_dashboard');
		redirect('power_of_ten_mgmt/basic_dashboard');
	}
	
	public function panacea_mgmt_states()
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
	} 
	
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
		
		$this->data['health_supervisors'] = $this->panacea_common_model->get_all_health_supervisors();
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->data['health_supervisorscount'] = $this->panacea_common_model->health_supervisorscount();
		
        //$this->data = $this->panacea_common_lib->panacea_mgmt_health_supervisors();
				
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_health_supervisors',$this->data);
	}
	
	public function create_health_supervisors()
	{
	 	$insert = $this->panacea_common_model->create_health_supervisors($_POST);
	 	if($insert){
	 		redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
	 	}else{
	 		$this->data = $this->panacea_common_lib->panacea_mgmt_health_supervisors();
	 		
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
        $this->data = $this->panacea_common_lib->panacea_mgmt_schools();		
		
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_schools',$this->data);
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
		$this->_render_page('panacea_admins/panacea_reports_ehr',$this->data);
	}
	
	public function panacea_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->panacea_common_lib->panacea_reports_display_ehr($post);
		
	 	$this->_render_page('panacea_admins/panacea_reports_display_ehr',$this->data);
	}
	
	public function panacea_reports_display_ehr_uid()
	{
		if(isset($_POST['uid']) && !empty($_POST['uid']))
		{
			$unique_id = $_POST['uid'];
		}else
		{
			$unique_id = $_GET['id_'];
		}
		
		/*$this->data = $this->panacea_common_lib->panacea_reports_display_ehr_uid($post);	
		$this->_render_page('panacea_admins/panacea_reports_display_ehr',$this->data);*/

		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_panacea_new_dashboard($unique_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];

		//echo print_r($this->data['hs'],true);exit();

		$this->data['BMI_report'] = $docs['BMI_report'];
		$this->data['hb_report'] = $docs['hb_report'];
		//$this->data['history'] = $docs['history'];	
				 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('panacea_admins/panacea_display_new_ehr',$this->data);
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
		$this->data = $this->panacea_common_lib->panacea_reports_school();
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_reports_schools',$this->data);
	}
	
	public function panacea_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_reports_symptom');
		$this->data = $this->panacea_common_lib->panacea_reports_symptom();
	
		$this->_render_page('panacea_admins/panacea_reports_symptom',$this->data);
	}
	
	// --------------------------------------------------------------------
	
	function student_db_to_excel()
	{
		ini_set('memory_limit', '1024M');
		$docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWREIS CHITKUL(G),MEDAK")->get("healthcare2016226112942701");
		$this->load->library('excel');
	
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
	
	function to_dashboard($date = FALSE, $request_duration = "Yearly", $screening_duration = "Yearly")
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard');
		
		$this->data = $this->panacea_common_lib->to_dashboard_old_dash($date, $request_duration, $screening_duration);
		$this->_render_page('panacea_admins/panacea_admin_dash', $this->data);
	
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
		$this->data = $this->panacea_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_request_pie_old_dash()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$this->data = $this->panacea_common_lib->update_request_pie_old_dash($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$request_pie_status = $_POST['request_pie_status'];
		$this->data = $this->panacea_common_lib->update_request_pie($today_date,$request_pie_span,$request_pie_status);
	
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
		$this->panacea_common_model->update_screening_collection($today_date,$screening_pie_span);
		//$today_date = $this->panacea_common_model->get_last_screening_update($today_date,$screening_pie_span);
		//$this->output->set_output($today_date);
	}
	
	function get_last_screened_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_last_screened_date');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$today_date = $this->panacea_common_model->get_last_screening_update($today_date,$screening_pie_span);
		$this->output->set_output($today_date);
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

	function get_counts_for_bar()
	{
		$counts = $this->panacea_common_model->get_counts_for_bar();
		$counts_list = array();
		foreach ($counts as $value) {
			foreach ($value as $index => $label_counts) {
				array_push($counts_list,$label_counts);
			}
		}
		$this->output->set_output(json_encode($counts_list));	
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
		
		$doc_list = $this->panacea_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('panacea_admins/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$unique_id = $_GET['id_'];

		//$this->data['docs'] = $this->panacea_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		$this->data['BMI_report'] = $docs['BMI_report'];
        $this->data['hb_report'] = $docs['hb_report'];
		//$this->data['history'] = $docs['history'];
		
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('panacea_admins/panacea_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->panacea_mgmt_model->drill_down_screening_to_students_load_ehr_doc($_id);
	
		$this->data['doc'] = $this->panacea_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('panacea_admins/panacea_reports_display_ehr',$this->data);
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
		$absent_report = json_encode($this->panacea_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
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
		$absent_report = json_encode($this->panacea_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
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
		$docs = $this->panacea_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
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
		$get_docs = $this->panacea_common_model->get_drilling_absent_students_docs($UI_id);
		//log_message('debug','drill_down_absent_to_students_load_ehr=====get_docs=====879====='.print_r($get_docs,true));
		$navigation = $_GET['ehr_navigation_for_absent'];
		$this->data['navigation'] = $navigation;
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('panacea_admins/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 1111111111111111111111111111111111111111111111111111111");
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_districts');
		if(isset($_POST['student_type']) && isset($_POST['student_age']))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$student_type = (isset($_POST["student_type"])) ? $_POST["student_type"] : "";
			$student_age = (isset($_POST["student_age"])) ? $_POST["student_age"] : "";
			
			$request_report = json_encode($this->panacea_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status,$student_type,$student_age));
			$this->output->set_output($request_report);
		}else
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_report = json_encode($this->panacea_common_model->drilldown_request_to_districts_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			
			$this->output->set_output($request_report);
		}
		
	}
	
	function drilling_request_to_schools()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 4444444444444444444444444444444444444444444444444444444444444444444444");
		$this->check_for_admin();
		$this->check_for_plan('drilling_request_to_schools');
		if(isset($_POST["request_pie_status"]))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$request_report = json_encode($this->panacea_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
			$this->output->set_output($request_report);
		}else
		{
			//echo print_r($_POST['data'], true); exit();
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_report = json_encode($this->panacea_common_model->get_drilling_request_schools_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			$this->output->set_output($request_report);
		}
		
	}
	
	function drill_down_request_to_students()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 77777777777777777777777777777777777777777777777777777777");
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students');
		if(isset($_POST['request_pie_status']))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$docs = $this->panacea_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
			$request_report = base64_encode(json_encode($docs));
			$this->output->set_output($request_report);
		}else
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$docs = $this->panacea_common_model->get_drilling_request_students_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name);
			$request_report = base64_encode(json_encode($docs));
			$this->output->set_output($request_report);
		}
		
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn dddddddddddddddddddddddddddddddddddddddddddddddddddddddddd");
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		if(empty($_POST['ehr_data_for_request_old_dash']))
		{
			$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request']),true);
			
			$get_docs = $this->panacea_common_model->get_drilling_request_students_docs($UI_id);
			
			$navigation = $_POST['ehr_navigation_for_request'];
			$this->data['navigation'] = $navigation;
		
			$this->data['students'] = $get_docs;

			
		
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn gggggggggggggggggggggggggggggggggggggggggggggggggggggg");
			$this->_render_page('panacea_admins/drill_down_request_to_students_load_ehr.php',$this->data);
		}else
		{
			$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request_old_dash']),true);

			$get_docs = $this->panacea_common_model->get_drilling_request_students_docs_old_dash($UI_id);
		
			$this->data['students'] = $get_docs;
			$this->data['navigation'] =  "";
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
			$this->_render_page('panacea_admins/drill_down_request_to_students_load_ehr_old_dash.php',$this->data);
		}		
	}
	//========================================================================
	
	//==================id===========================================================
	
	function drilldown_identifiers_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_identifiers_to_districts');
		if(isset($_POST["request_pie_status"]))
		{			
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = $_POST["request_pie_status"];
			$identifiers_report = json_encode($this->panacea_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
			$this->output->set_output($identifiers_report);
		}else
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$identifiers_report = json_encode($this->panacea_common_model->drilldown_identifiers_to_districts_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			$this->output->set_output($identifiers_report);
		}
	}
	
	function drilling_identifiers_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_identifiers_to_schools');
		if(isset($_POST["request_pie_status"]))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = $_POST["request_pie_status"];
			$identifiers_report = json_encode($this->panacea_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
			$this->output->set_output($identifiers_report);
		}else{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$identifiers_report = json_encode($this->panacea_common_model->get_drilling_identifiers_schools_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			$this->output->set_output($identifiers_report);
		}	
	}
	
	function drill_down_identifiers_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students');
		if(isset($_POST["request_pie_status"]))
		{
			$data = $_POST['data'];	
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = $_POST["request_pie_status"];
			$docs = $this->panacea_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
			$identifiers_report = base64_encode(json_encode($docs));
			$this->output->set_output($identifiers_report);
		}else
		{
			$data = $_POST['data'];	
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$docs = $this->panacea_common_model->get_drilling_identifiers_students_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name);
			$identifiers_report = base64_encode(json_encode($docs));
			$this->output->set_output($identifiers_report);
		}
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		if(isset($_POST['ehr_data_for_identifiers']) && !empty($_POST['ehr_data_for_identifiers']))
		{
			$temp = base64_decode($_POST['ehr_data_for_identifiers']);
			$UI_id = json_decode(base64_decode($_POST['ehr_data_for_identifiers']),true);
			$get_docs = $this->panacea_common_model->get_drilling_identifiers_students_docs($UI_id);
			
			$navigation = $_POST['ehr_navigation_for_identifiers'];
			$this->data['navigation'] = $navigation;
		
			$this->data['students'] = $get_docs;
		
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
			$this->_render_page('panacea_admins/drill_down_identifiers_to_students_load_ehr',$this->data);
		}else
		{
			$temp = base64_decode($_GET['ehr_data_for_identifiers']);
			$UI_id = json_decode(base64_decode($_GET['ehr_data_for_identifiers']),true);
			
			$get_docs = $this->panacea_common_model->get_drilling_identifiers_students_docs_old_dash($UI_id);
			
		
			$this->data['students'] = $get_docs;
			$this->data['navigation'] = "";
		
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
			$this->_render_page('panacea_admins/drill_down_identifiers_to_students_load_ehr',$this->data);
		}
		
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
		//create paginate??s links
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
			//create paginate??s links
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
		$this->data = $this->panacea_common_lib->panacea_reports_students_filter();
	
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_reports_students_filter',$this->data);
	}

	public function get_excel_for_students_reports()
    {
    	$district = $this->input->post('dist_name',true);
    	$scl = $this->input->post('school',true);    	
    	
    	$get_reports = $this->panacea_common_lib->get_excel_for_students_reports_lib($district, $scl);

    	$this->output->set_output($get_reports);	
		
    }
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];

		$this->data = $this->panacea_common_model->get_schools_by_dist_id($dist_id);
		//echo print_r($this->data, true); exit();
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_students_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_students_list');
	
		$school_name = $_POST['school_name'];
		$dist_name = $_POST['dist_name'];
	
		$this->data = $this->panacea_common_model->get_students_by_school_name($school_name,$dist_name);	
		$this->output->set_output(json_encode($this->data));
	}
	
	public function pie_export()
	{
		$this->data['today_date'] = date('Y-m-d');
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/panacea_pie_export',$this->data);
	}
	
	public function generate_excel_for_absent_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->panacea_common_lib->generate_excel_for_absent_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_bmi_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name    = $_POST['dt_name'];
		$school_name = $_POST['school_name'];
		$file_path = $this->panacea_common_lib->generate_excel_for_bmi_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_request_pie()
	{
		
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->panacea_common_lib->generate_excel_for_request_pie($today_date,$request_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_screening_pie()
	{
		//log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($_POST,true));
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->panacea_common_lib->generate_excel_for_screening_pie($today_date,$screening_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	function screening_pie_data_for_stage4_new(){
		$this->panacea_common_model->screening_pie_data_for_stage4_new();
	}
	
	function forward_request(){
		$this->panacea_common_lib->submit_request_to_doctor($_POST);
		//redirect('panacea_mgmt/to_dashboard');
		redirect('panacea_mgmt/basic_dashboard');
	}
	
	function panacea_create_group()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_create_group');
	
		$this->data = $this->panacea_common_lib->panacea_groups();
	
		$this->_render_page('panacea_admins/create_group', $this->data);
	}
	public function create_group()
	{
		$session_data = $this->session->userdata('customer');
		$user_type = $session_data['user_type'];
		$this->panacea_mgmt_model->create_group($_POST,$user_type);
		redirect('panacea_mgmt/panacea_create_group');
	}
	public function save_users_to_group()
	{
		//log_message("debug","ggggggggggrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr=======".print_r($_POST,true));
		$this->panacea_mgmt_model->save_users_to_group($_POST);
		
		redirect('panacea_mgmt/group_msg');
	}
	public function delete_group($_id)
	{
		$this->panacea_mgmt_model->delete_chat_group($_id);
		redirect('panacea_mgmt/panacea_create_group');
	}
	
	function group_msg()
	{
		$this->check_for_admin();
		$this->check_for_plan('group_msg');
	
		$this->data = $this->panacea_common_lib->group_msg();
	
		$this->_render_page('panacea_admins/group_msg', $this->data);
	}
	
	function get_messages($msg_id,$message = false)
	{
		$this->check_for_admin();
		$this->check_for_plan('get_messages');
	
		if($message === false){
			//log_message('debug','hereeeeeeeeeeeeeeeeeeeeeeeeeee================================.'.print_r($msg_id,true));
			$result = $this->panacea_common_model->get_messages($msg_id);
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
			$this->data = $this->panacea_common_model->add_message($_POST,$msg_id);
			
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
	
		$this->data = $this->panacea_common_lib->user_msg();
	
		$this->_render_page('panacea_admins/user_msg', $this->data);
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
	
		$this->data = $this->panacea_common_lib->user_msg();
	
		$this->_render_page('panacea_admins/multi_user_msg', $this->data);
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
		
		$data = $this->panacea_mgmt_model->get_sanitation_infrastructure_model($district_name,$school_name);
		
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
	function sanitation_report()
	{
		$this->check_for_admin();
		$this->check_for_plan('sanitation_report');

		$pagenumber       = array();
	    $page_data        = array();
		$sanitation_report_app = array();
		$today_date       = date("Y-m-d");
		
		$app_template = $this->panacea_common_model->get_sanitation_report_app();
		
		foreach ($app_template as $pageno => $pages)
        {
		  	array_push($pagenumber,$pageno);
        }

     	$pagecount = count($pagenumber);

     	for($i=1;$i<=$pagecount;$i++)
	 	{
			 array_push($page_data,$app_template[$i]);
     	}

     	for($ii=0;$ii<$pagecount;$ii++)
     	{
             $pgno = $ii +1;
			 foreach($page_data[$ii] as $section => $index_array)
			 {
			    unset($index_array['dont_use_this_name']);
				$sanitation_report_app[$pgno][$section] = array();
				foreach($index_array as $index => $value)
	            {
				   $ele_name = str_replace(' ','_',$index);
				   $sec_name = str_replace(' ','_',$section);
				   $path = "page".$pgno."#".$sec_name."#".$ele_name;
				   switch ($value['type'])
		      	   {
		        		case 'radio':
						$options = array();
						$options_array = $value['options'];
						foreach($options_array as $index_no => $val)
						{
						  array_push($options,$val['label']);
						}
						break;
				   }
				   $opt = array('path'=>$path,'options'=>$options);
				   $ele = array($index=>$opt);
				   array_push($sanitation_report_app[$pgno][$section],$ele); 
				}
			 }
		}
		unset($sanitation_report_app[4]['Declaration Information']);
		
		$this->data['sanitation_report_obj'] = json_encode($sanitation_report_app);

		$this->data['sanitation_report_schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($today_date);
		
		$this->data['today_date'] = $today_date;
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		
		$this->_render_page('panacea_admins/sanitation_display_report_view', $this->data);
	
	}

	function fetch_sanitation_report_against_date()
	{
	  // Variables
	  $sanitation_report = array();

	  // POST Data
	  $date   = $this->input->post('selected_date',TRUE);

	  if(isset($_POST['selected_school']) && !empty($_POST['selected_school']))
	  {
	  	 $school = $this->input->post('selected_school',TRUE);
	  
	     $sanitation_report_data = $this->panacea_common_model->get_sanitation_report_data_with_date($date,$school);
		
	     $sanitation_report['report_data']  = json_encode($this->panacea_common_lib->build_sanitation_report($sanitation_report_data));

	     $sanitation_report['schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($date);
	  }
	  else
	  {
	  	 $sanitation_report['schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($date);
	  }
		
	  if(isset($sanitation_report) && !empty($sanitation_report))
	  {
	      $this->output->set_output(json_encode($sanitation_report));
	  }
	  else
	  {
		  $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}

	 
	function draw_sanitation_report_pie()
	{
	  // POST Data
	  $date 			= $this->input->post('date',TRUE);
	  $search_criteria  = $this->input->post('que',TRUE);
	  $opt              = $this->input->post('opt',TRUE);
	  
	  $search_criteria  = str_replace('#','.',$search_criteria);
	  $search_criteria  = str_replace('_',' ',$search_criteria);
	 
	  $search_criteria  = "doc_data.widget_data.".$search_criteria;
	  
	  $sanitation_report_pie = $this->panacea_common_model->get_sanitation_report_pie_data($date,$search_criteria,$opt);
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
			//commented by bhanu 
		
			/* if((intval($class) == 10)){
			   $class = "2017"." 10th passed out";
		   }else if((intval($class) == 12)){
			   $class = "2017"." 12th passed out";
		   } else if($class == "Inter 1st"){
			   $class = "Inter 2nd";
		   } else if($class == "Degree 1st")
			   $class = "Degree 2nd";
		   else{
			   if(isset($class) && !empty($class) && ($class != "") && ($class != " ") && (intval($class) < 11)){
				   $class++;
			   }
		   } */
		   
		   if($class == 10){
			   $class = "2018"." 10th passed out";
		   }else if($class == 12){
			   $class = "2018"." 12th passed out";
		   } else if($class == 11){
			   $class = 12;
		   } else if($class == "Degree 1st"){
			   $class = "Degree 2nd";
		   }else if($class == "Degree 2nd")
			    $class = "Degree 3rd";
		   else{
			   if(isset($class) && !empty($class) && ($class != "") && ($class != " ") && ($class < 11)){
				   $class++;
			   }
		   }
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
		
		$token = $this->panacea_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() {
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->panacea_common_lib->delete_ehr_note($doc_id);
	   
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
		
	  $pcompl_raw_data   = $this->panacea_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
		
	  $pcompl_raw_data   = $this->panacea_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	
	public function docs_comp_open() {
		
		$this->check_for_admin();
		$this->check_for_plan('docs_comp');
		$this->data = "";
		
		$this->_render_page('panacea_admins/docs_comp',$this->data);
    }
	
	public function docs_comp() {
		
		$this->check_for_admin();
		$this->check_for_plan('docs_comp');
		$docs_arr = explode(",",$_POST['docs_arr']);
		$docs_arr = array_unique($docs_arr);
		
		$docs = $this->panacea_common_model->get_dup_docs($docs_arr);
			
		$this->data['docs'] = json_encode($docs);
		$this->_render_page('panacea_admins/docs_compare', $this->data);
		//$this->_render_page('panacea_admins/docs_comp',$this->data);
    }
	
	function doc_comp($document1, $document2){
	 	
	 	log_message('debug','iddddddddddddddddddddddddddddddddddddddd'.print_r($document1,true).print_r($document2,true));
	 	$doc1 = $this->panacea_common_model->get_document($document1);
	 	$doc2 = $this->panacea_common_model->get_document($document2);
	 	
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
	 
		$this->_render_page('panacea_admins/doc_comp_check', $this->data);
	 	//$this->_render_page('field_agent/field_agent_doc_comp', $this->data);
	 	 
	 	//echo print_r($check,true);
	 	//echo print_r($diff_str,true);
	 }
	 
	function delete_dup_doc(){
	 	$doc_id = $this->input->post('doc_id');
	 	log_message('debug','doc_iiiiddddddddddddddddddddd'.print_r($doc_id,true));
	 	$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get("healthcare2016226112942701");
	 	$query_ins = $this->mongo_db->insert("healthcare2016226112942701_duplicate",$query[0]);
	 	
	 	if ($query_ins) {
	 		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->delete("healthcare2016226112942701");
	 	}
	 	
	 	//$this->index();
		echo "Document deleted, please go back using browser back button";
	 }
	 
	 function show_all_docs($id_no = ""){
	 	
	 	//log_message('debug','idsssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($ad_no,true));
	 	$this->data['docs'] = json_encode($this->panacea_common_model->get_all_docs_in_uid_no($id_no));
	 	
	 	$this->_render_page('panacea_admins/show_all_docs', $this->data);
	 }
	 
	 function diff_docs(){
	 	$doc_id = $this->input->post('doc_ids');
	 	log_message('debug','doc_iiiiddddddddddddddddddddd'.print_r($doc_id,true));
	 	
	 	redirect("panacea_mgmt/doc_comp/$doc_id[0]/$doc_id[1]");
	 	
// 	 	$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get("healthcare2016226112942701");
// 	 	$query_ins = $this->mongo_db->insert("healthcare2016226112942701_duplicate",$query[0]);
	 	 
// 	 	if ($query_ins) {
// 	 		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->delete("healthcare2016226112942701");
// 	 	}
	 	 
// 	 	$this->index();
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
		$this->check_for_plan('panacea_chronic_pie_view');
		
		$this->data = $this->panacea_common_lib->chronic_pie_view();
		$this->data['screening_report_count'] = $this->panacea_common_model->get_all_students_count();
		
		$this->_render_page('panacea_admins/chronic_pie_view',$this->data);
	}


	public function hospitalized_pie_view(){
		$this->check_for_admin();
		$this->check_for_plan('panacea_hospitalized_pie_view');
		
		$this->data = $this->panacea_common_lib->hospitalized_pie_view();
		$this->data['screening_report_count'] = $this->panacea_common_model->get_all_students_count();
		
		$this->_render_page('panacea_admins/panacea_hospitalized_pie_view',$this->data);
	}
	
	public function update_chronic_request_pie(){
		
		$this->check_for_admin();
		$this->check_for_plan('update_chronic_request_pie');
		
		$status_type = $_POST["status_type"];
		
		$this->data = $this->panacea_common_lib->update_chronic_request_pie($status_type);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function drill_down_request_to_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_symptoms');
		
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$symptoms_report = json_encode($this->panacea_common_model->drill_down_request_to_symptoms($data,$status_type));
		$this->output->set_output($symptoms_report);
	}
	
	function drilldown_chronic_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_districts');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$identifiers_report = json_encode($this->panacea_common_model->drilldown_chronic_request_to_districts($data,$status_type));
		$this->output->set_output($identifiers_report);
	}
	
	function drilldown_chronic_request_to_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_school');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$request_report = json_encode($this->panacea_common_model->drilldown_chronic_request_to_schools($data,$status_type));
		$this->output->set_output($request_report);
	}
	
	function drilldown_chronic_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_students');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$docs = $this->panacea_common_model->drilldown_chronic_request_to_students($data,$status_type);
		log_message('error','drilldown_chronic_request_to_students'.print_r(base64_encode(json_encode($docs)),true));
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_chronic_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_chronic_request_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->panacea_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		$doc_list = $this->panacea_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('panacea_admins/chronic_list_to_students_load_ehr',$this->data);
	}
	
	public function add_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed_view' );
		
		$this->data ['message'] = "";
		
		$this->_render_page ( 'power_of_ten_admins/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		
		$news_return = $this->power_of_ten_common_lib->add_news_feed($_POST);
		
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'power_of_ten_admins/add_news_feed', $this->data );
		}else{
			redirect('power_of_ten_mgmt/manage_news_feed_view');
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->power_of_ten_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'power_of_ten_admins/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->power_of_ten_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'power_of_ten_mgmt/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->panacea_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'panacea_admins/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->panacea_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'panacea_admins/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	//===============================================
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
	  $this->_render_page('panacea_admins/panacea_zipfile_import_view',$this->data);
	}

	//Excel importing function
	//author Naresh
	 function school_screening_file_import()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('school_screening_file_import');
		
		$post = $_POST;
		
		$this->data = $this->panacea_common_lib->school_screening_file_import($post);
		
    }
	
	public function requests_status()
	{
		
		$this->data['message'] = "";
		$this->_render_page("panacea_admins/requests_status_view",$this->data);
	}
	
	public function initiate_request_count_all_today_date()
	{
		$today_date = $_POST['today_date'];
		//$school_name = trim($_POST['school_name']);
		//$this->data['message'] = "";
		$document = $this->panacea_common_lib->get_initaite_requests_count_today_date($today_date);
		//log_message('error',"document=================268000".print_r($document,true));
		//echo print_r(count($this->data['count']),true); exit;
		$this->output->set_output(json_encode($document));
	}
		
		
	
	
	public function initaite_requests_status_count_school()
	{
		$today_date = $_POST['today_date'];
		$school_name = trim($_POST['school_name']);
		//$this->data['message'] = "";
		$document = $this->panacea_common_lib->get_initaite_requests_count_school($today_date,$school_name);
		//log_message('error',"document=================268000".print_r($document,true));
		//echo print_r(count($this->data['count']),true); exit;
		$this->output->set_output(json_encode($document));
		//$this->_render_page("panacea_admins/requests_status_view",$this->data);
	} 


	// ------------------------------------------------------------------------

	/**
	 * Helper: BMI PIE REPORT
	 
	 * @author bhanu 
	 */
	
	
	/************** BMI PIE default page************************/
	public function bmi_pie_view()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		
		$this->data['bmi_submitted_month'] = date('Y-m-d');
		$this->data['district_list'] = $this->panacea_common_model->get_all_district();
		$this->data['total_students'] = $this->panacea_common_model->get_all_students_count();
		$this->_render_page('panacea_admins/bmi_pie_view',$this->data);
	}

	
	/************** BMI PIE based selecting widegt(month, district,school)************************/
	public function bmi_pie_view_month_wise()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		$current_month = $_POST["current_month"];
		$district_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$student_type = $_POST["student_type"];
		$student_age = $_POST["student_age"];
		
		$this->data = $this->panacea_common_lib->bmi_pie_view_lib_month_wise($current_month,$district_name, $school_name,$student_type,$student_age);
		$this->output->set_output(json_encode($this->data));
	}
	
	/************** clicking BMI pie to show student reports************************/
	/*function drill_down_bmi_to_district()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'bmi_pie_view' );
		$data = $_POST["data"];
		$current_month = $_POST["current_month"];
		$district_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		
		$this->data = $this->panacea_common_lib->drill_down_bmi_to_district($data,$current_month,$district_name, $school_name);
		$this->output->set_output(json_encode($this->data));
	}*/

	function drill_down_bmi_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_bmi_to_students');
		$symptom_type = $_POST['case_type'];
		$month = $_POST["current_month"];
		$district_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$student_type = $_POST["student_type"];
		$student_age = $_POST["student_age"];
		
		$docs = $this->panacea_common_model->get_drill_down_to_bmi_report($symptom_type,$month,$district_name,$school_name,$student_type,$student_age);
		
		$bmi_report = base64_encode(json_encode($docs));
		$this->output->set_output($bmi_report);
		
	}
	
	/************** drilldown to bmi reports students************************/
	function drill_down_to_bmi_report_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_to_bmi_report_students');
	
		$temp = base64_decode($_POST['ehr_data_for_bmi']);
		
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_bmi']),true);
		
		$selectedMonth = $_POST['selectedMonth'];
		
		$selectedMonth = substr($selectedMonth,0,-3);
				
		$students = $this->panacea_common_model->get_drilling_bmi_students_docs($UI_id, $selectedMonth);
		/*echo "<pre>";
		echo print_r($students,true);echo "</pre>";exit();*/
		$this->data['get_bmi_docs'] = $students;
		
		$navigation = $_POST['ehr_navigation_for_bmi'];
		
		$this->data['navigation'] = $navigation;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('panacea_admins/drill_down_to_bmi_report_view',$this->data);
	}
	
	public function show_student_bmi_graph($hospital_unique_id){
		
		$month_wise_bmi  = array();
		$month_wise_data = array();
		$final_bmi_data  = array();
		$temp 	 		 = array();
		
		
		$bmi_value = $this->panacea_common_model->get_student_bmi_graph_values($hospital_unique_id);
	
		
		if(isset($bmi_value) && !empty($bmi_value))
		{
		
		foreach($bmi_value as $bmi)
		{
			
			if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values']))
		  {
			  	$student_info['unique_id'] = $bmi['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
				$student_info['name'] = $bmi['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'];
			  	$student_info['class'] = $bmi['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'];
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
		$final_bmi_data['students_details'] = $student_info;
		
		$this->_render_page('panacea_admins/show_student_bmi_graph_view',$final_bmi_data);
		}
		
	}

	/************** HB PIE default page************************/
	public function hb_pie_view()
	{
		$this->check_for_admin ();
		$this->check_for_plan ( 'hb_pie_view' );
		
		$this->data['hb_submitted_month'] = date('Y-m-d');
		$this->data['district_list'] = $this->panacea_common_model->get_all_district();
		$this->data['total_students'] = $this->panacea_common_model->get_all_students_count();
		$this->_render_page('panacea_admins/hb_pie_view',$this->data);
	}
	
	/************** HB PIE based selecting widegt(month, district,school)************************/
	public function hb_pie_view_month_wise()
	{

		$this->check_for_admin ();
		$this->check_for_plan ( 'hb_pie_view' );
		$current_month = $_POST["current_month"];
		$district_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$student_type = $_POST["student_type"];
		$student_age = $_POST["student_age"];
		
		$this->data = $this->panacea_common_lib->hb_pie_view_lib_month_wise($current_month,$district_name, $school_name,$student_type,$student_age);
		$this->output->set_output(json_encode($this->data));
	}

	/************** clicking BMI pie to show student reports************************/
	function drill_down_hb_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_hb_to_students');
		$symptom_type = $_POST['case_type'];
		$month = $_POST["current_month"];
		$district_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$student_type = $_POST["student_type"];
		$student_age = $_POST["student_age"];
		//echo print_r($symptom_type.$month.$district_name.$school_name,TRUE);exit();
		$docs = $this->panacea_common_model->get_drill_down_to_hb_report($symptom_type,$month,$district_name,$school_name,$student_type,$student_age);
		//log_message('error','docs--------------------2919'.print_r($docs,TRUE));
		$bmi_report = base64_encode(json_encode($docs));
		$this->output->set_output($bmi_report);
		
	}

	/************** drilldown to hb reports students************************/
	function drill_down_to_hb_report_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_to_hb_report_students');
	
		$temp = base64_decode($_POST['ehr_data_for_hb']);
		
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_hb']),true);
		
		$selectedMonth = $_POST['selectedMonth'];
		
		$selectedMonth = substr($selectedMonth,0,-3);
				
		$students = $this->panacea_common_model->get_drilling_hb_students_docs($UI_id, $selectedMonth);
		
		
		$this->data['get_bmi_docs'] = $students;
		
		$navigation = $_POST['ehr_navigation_for_bmi'];
		
		$this->data['navigation'] = $navigation;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('panacea_admins/drill_down_to_hb_report_view',$this->data);
	}
	
	public function show_student_hb_graph($hospital_unique_id){
		
		$month_wise_hb  = array();
		$month_wise_data = array();
		$final_hb_data  = array();
		$temp 	 		 = array();
		
		
		$hb_values = $this->panacea_common_model->get_student_hb_graph_values($hospital_unique_id);
	
		
		if(isset($hb_values) && !empty($hb_values))
		{
		
		foreach($hb_values as $hb)
		{
			
			if(isset($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']) && !empty($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']))
		  {
		  		$student_info['unique_id'] = $hb['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
				$student_info['name'] = $hb['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'];
			  	$student_info['class'] = $hb['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'];
			  	
			  foreach($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb_data )
			  {
				  $hb    = $hb_data['hb'];
				  $date   = $hb_data['month'];
				 // $height = $hb_data['height'];
				  //$weight = $hb_data['weight'];
				  
				  $new_start_ = new DateTime($date);
				  $month_start = $new_start_->getTimestamp()*1000;
				  $pre_temp = array($month_start,(int) $hb);
				  
				  array_push($month_wise_hb,$pre_temp);
				 
				  $temp_data = array();
				 // $temp_data['height'] = $height;
				  //$temp_data['weight'] = $weight;
				  $temp[$month_start]  = $temp_data;
				  
			  }
			  
		  }
			
		}

        array_push($month_wise_data,$temp);
		arsort($month_wise_hb);
		$month_wise_hb  = array_values($month_wise_hb);
		$final_hb_data['graph_data'] = $month_wise_hb;
		$final_hb_data['month_data'] = $month_wise_data;
		$final_hb_data['students_details'] = $student_info;
		$final_hb_data['message'] = "";
		
		$this->_render_page('panacea_admins/show_student_hb_graph_view',$final_hb_data);
		}
		
	}
	

	public function generate_bmi_report_to_excel()
	{
		$date = $_POST['current_month'];
		$district_name = $_POST['district_name'];
		$school_name = $_POST['school_name'];
		
		$file_path = $this->panacea_common_lib->generate_bmi_report_to_excel_lib($date,$district_name, $school_name);
		
		$this->output->set_output($file_path);
		
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: All Contact Numbers PIE
	 
	 * @author bhanu 
	 */
	
	public function contact_numbers()
	{
		$this->data['contact_numbers'] = $this->panacea_common_model->contact_numbers();
		$this->_render_page('panacea_admins/contact_numbers_view', $this->data);
	}
	
	public function drill_down_to_contacts()
	{
		$contact_type = $_POST['contact_type'];
		$docs  = $this->panacea_common_model->get_contacts_list_by_contact_type($contact_type);
		$contact_lists = json_encode($docs);

		$this->output->set_output($contact_lists);
		
	}

	function drill_down_to_contacts_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_to_contacts_list');
		
		$contact_type = $_POST['contact_type'];      
		$navigation = $_POST['ehr_navigation_for_contacts'];
		
		$this->data['contacts'] = $this->panacea_common_model->get_contacts_list_by_contact_type($contact_type);
		$this->data['navigation'] = $navigation;
		$this->data['type'] = $contact_type;
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('panacea_admins/contact_numbers_list_view',$this->data);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: RHSO submitted REPORT
	 
	 * @author bhanu 
	 */
	
	public function sanitation_inspection_report()
	{

		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/sanitation_inspection_report_view',$this->data);
		
	}

	public function get_sanitation_inspection_report()
	{
		$dist_name = $_POST['select_dt_name'];
		$school_name = $_POST['school_name'];
		$this->data = $this->panacea_common_lib->get_sanitation_inspection_report_lib($dist_name, $school_name);
		
		$this->output->set_output($this->data);
	}

	public function civil_and_infrastructure_report()
	{
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/civil_and_infrastructure_report_view',$this->data);
		
	}

	public function get_civil_and_infrastructure_report()
	{
		$dist_name = $_POST['select_dt_name'];
		$school_name = $_POST['school_name'];
		$this->data = $this->panacea_common_lib->get_civil_and_infrastructure_report_lib($dist_name,$school_name);
		
		$this->output->set_output($this->data);
	}

	public function health_inspector_inspection_report()
	{

		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/health_inspector_report_view',$this->data);
		
	}

	public function get_health_inspector_inspection_report()
	{
		$dist_name = $_POST['select_dt_name'];
		$school_name = $_POST['school_name'];
		$this->data = $this->panacea_common_lib->get_health_inspector_inspection_report_lib($dist_name,$school_name);
		
		$this->output->set_output($this->data);
	}

	public function food_hygiene_inspection_report()
	{

		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/food_hygiene_inspection_report_view',$this->data);
		
	}

	public function get_food_hygiene_inspection_report()
	{
		$dist_name = $_POST['select_dt_name'];
		$school_name = $_POST['school_name'];
		$this->data = $this->panacea_common_lib->get_food_hygiene_inspection_report_lib($dist_name, $school_name);
		
		$this->output->set_output($this->data);
	}
	
	
	// ------------------Basic Dashboard------------------------------

	/**
	 * Helper: Screening PIE with tabel format
	 
	 * @author Naresh 
	 */
	
	 public function basic_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		
		$this->data['today_date'] = date('Y-m-d');
		
		$getData = $this->power_of_ten_common_model->get_total_registered_swaeroes();

		$this->data['total_student_swaroes'] = $getData['sudent_count'];
		//$this->data['total_like_minded_swaroes'] = $getData['like_minded_count'];
		$this->data['payment_pend'] = $getData['payment_pend'];
		$this->data['verified_swaroes'] = $getData['verified_reg'];
		$this->data['not_verified_swaroes'] = $getData['not_verified'];
		
		$this->_render_page('power_of_ten_admins/power_of_ten_basic_dashboard',$this->data);

	}
	
	function basic_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];

		$this->data = $this->panacea_common_lib->basic_dashboard_with_date($today_date,$screening_pie_span,$dt_name,$school_name);


		$this->output->set_output($this->data);
	
	}


	public function get_schools_list_with_school_code()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->panacea_common_model->get_schools_by_district_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}

	/* public function get_screening_data_with_district_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_screening_data_with_district_school');

		
		$school_name = $_POST['school_name'];
		$school_details = $this->panacea_common_model->get_school_code_by_school_name($school_name);
		$school_code = $school_details[0]['school_code'];

		
		$school_info = $this->panacea_common_model->get_school_name_by_school_code($school_code);
		$school_email = $school_info[0]['email'];
		
		$email = str_replace('@', '#', $school_email);
		

		$this->data['screening_report'] = $this->panacea_common_model->get_all_screenings_count_by_school($email, $date = false, $screening_duration = "Yearly");
		$this->data['school_email_id'] = $email;
		
		$this->output->set_output(json_encode($this->data));


	} */
	
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
		
		$this->data = $this->panacea_common_lib->get_data_with_district_school($email, $date = false, $screening_duration = "Yearly", $today_date,$school_name);
	
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

	function drilling_screening_to_abnormalities_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_screening_to_abnormalities_pie');
		$data = $_POST['data'];
		$schoolEmail = $_POST['schoolEmail'];
		
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_abnormalities_to_pie($data, $schoolEmail/*,$today_date,$screening_pie_span*/));
		$this->output->set_output($screening_report);
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
		
		$this->_render_page('panacea_admins/screening_to_students_load_ehr',$this->data);
	}

	 public function initaite_requests_status_count_new_dashboard()
	{
		$today_date = $_POST['today_date'];
		$this->data['message'] = "";
		$document = $this->panacea_common_lib->get_initaite_requests_count_new_dashboard($today_date);
		//echo print_r(count($this->data['count']),true); exit;
		$this->output->set_output(json_encode($document));
		//$this->_render_page("panacea_admins/requests_status_view",$this->data);
		
	} 
	
	public function get_show_ehr_details()
	{
		//$date = "2018-08-16";
		
		$date = $_POST['to_date_new'];
		$school_name = $_POST['school_name_new'];
		$request_type = $_POST['request_type_new'];
		/* 
		if(isset($_POST['Normal']))
		{
			$post = $_POST['Normal'];
		}
		else 
		{
			if(isset($_POST['Emergency']))
			{
				$post = $_POST['Emergency'];
			}
			else 
			{
				if(isset($_POST['Chronic']))
				{
					$post = $_POST['Chronic'];
				}
			}
		} */
		
		
		$this->data['students_details'] = $this->panacea_common_model->get_show_ehr_details($request_type,$date,$school_name);
		//echo print_r($this->data['students_details'],TRUE); exit();
		$this->_render_page('panacea_admins/basic_show_ehr_view',$this->data);
	}

	/*function get_show_ehr_student_unique_id()
	{
		$unique_id = $_POST['unique_id'];
		echo print_r($unique_id,true); exit();
		$query = $this->panacea_common_model->get_show_ehr_student_unique_id($unique_id);
		$_id = $query['0']['_id'];

		$this->output->set_output($_id); 
	}*/

	/*public function drill_down_screening_to_students_load_ehr_new_dashboard($_id)
	{
		//$unique_id = $_GET['id_'];
		
		//$_id = $_POST['ehr_data'];		
		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_new_dashboard($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		//$this->data['history'] = $docs['history'];		
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('panacea_admins/panacea_reports_display_ehr_new_dashboard',$this->data);
	}*/
	
	// ------------------EHR Print Option------------------------------

	/**
	 * Helper: Student Electronic Health Record functionality
	 
	 * @author bhanu 
	 */
	 
	 public function init_health_summary_report_process()
	{
	  // POST Data
	  $id_str = trim($this->input->post('unique_id',true));
	
	  //$id_str = json_decode($id_str,true);
	  
	  $health_summary_report = $this->panacea_common_lib->generate_health_summary_report($id_str);
	   
	  
	  if($health_summary_report)
	  {
        $this->output->set_output($health_summary_report);
	  }
	  
	}

	function panacea_imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_bmi_values');
		$this->data = $this->panacea_common_lib->panacea_imports_bmi_values();
	
		$this->_render_page('panacea_admins/panacea_imports_bmi_values', $this->data);
	}

	function imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_bmi_values');
	
		$post = $_POST;
		//log_message('error','post=============='.print_r($post,true));exit();
		$this->data = $this->tswreis_schools_common_lib->imports_bmi_values($post);

		
		if($this->data == "redirect_to_bmi_fn")
		{
			redirect('panacea_mgmt/panacea_imports_bmi_values');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_bmi_values', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_bmi_values', $this->data);
		}
	}

	function panacea_imports_hb_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_imports_diagnostic');
		$this->data = $this->panacea_common_lib->panacea_imports_hb_values();
	
		$this->_render_page('panacea_admins/panacea_imports_hb_values', $this->data);
	}

	function imports_hb_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_hb_values');
	
		$post = $_POST;
		//log_message('error','post=============='.print_r($post,true));exit();
		$this->data = $this->tswreis_schools_common_lib->imports_hb_values($post);

		
		if($this->data == "redirect_to_hb_fn")
		{
			redirect('panacea_mgmt/panacea_imports_hb_values');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('panacea_admins/panacea_imports_hb_values', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('panacea_admins/panacea_imports_hb_values', $this->data);
		}
	}
	public function get_sevier_count()
	{
		$student_type = $_POST['student_type'];
		$get_count = $this->panacea_common_model->get_sevier_count($student_type);
		$this->output->set_output(json_encode($get_count));
	}

	public function show_tails_label_counts()
	{
		//$month = $_POST['month'];
		//$student_type = $_POST['student_type'];
		$student_type = 'All';
		$get_count = $this->panacea_common_model->get_bmi_count($student_type);
		//echo print_r($get_count[0]['under_weight'], true); exit();
		$get_count['screened_count'] = $this->panacea_common_model->get_screened_count();
		$get_count['attendance_count'] = $this->panacea_common_model->get_attendance_count();
		$get_count['sanitation_count'] = $this->panacea_common_model->get_sanitation_count();
		$get_count['chronic_count'] = $this->panacea_common_model->get_chronic_asthma_count($student_type);
		$get_count['requests_total_count'] = $this->panacea_common_model->get_total_emergency_req_count($student_type);
		
		$this->output->set_output(json_encode($get_count));
	}

	public function get_student_type_for_tails()
	{
		$student_type = $_POST['student_type'];
		$get_count = $this->panacea_common_model->get_student_type_for_tails($student_type);
		//$get_count = $this->panacea_common_model->get_bmi_count($student_type);
		
		$this->output->set_output(json_encode($get_count));
	}
	public function get_bmi_students_docs()
	{
		$bmi_type = $_POST['bmi_type'];
		$age_bmi_type = $_POST['age_bmi_type'];
		$format_string = explode(":", $bmi_type);
		$bmi_type = $format_string[0];
	
		$this->data['get_bmi_docs'] = $this->panacea_common_model->get_bmi_students_docs($bmi_type, $age_bmi_type);
		$this->data['bmi_case_type'] = $bmi_type;
		/*echo print_r($this->data['get_bmi_docs'],true);
		exit;*/
		$this->_render_page('panacea_admins/panacea_show_bmi_issues_list_view', $this->data);
	}

	public function get_hb_students_docs()
	{
		$hb_type = $_POST['hb_type'];
		$format_string = explode(":", $hb_type);
		$hb_type = $format_string[0];
		
		$this->data['get_hb_docs'] = $this->panacea_common_model->get_hb_students_docs($hb_type);
		$this->data['hb_case_type'] = $hb_type;
		
		$this->_render_page('panacea_admins/panacea_show_hb_issues_list_view', $this->data);
	}
	public function get_chronic_students_docs()
	{
		$chronic_type = $_POST['chronic_type'];
		$format_string = explode(" ", $chronic_type);
		$chronic_type = $format_string[0];
		
		$this->data['get_chronic_docs'] = $this->panacea_common_model->get_chronic_students_docs($chronic_type);
		$this->data['chronic_case_type'] = $chronic_type;
		
		$this->_render_page('panacea_admins/panacea_show_chronic_issues_list_view', $this->data);
	}
	public function get_emergency_req_students_docs()
	{
		$emergencyReq = $_POST['emergencyReq'];
		$format_string = explode(":", $emergencyReq);
		$emergencyReq = $format_string[0];
		
		$this->data['get_emergency_docs'] = $this->panacea_common_model->get_emergency_req_students_docs($emergencyReq);
		$this->data['emergency_requests'] = $emergencyReq;
		
		$this->_render_page('panacea_admins/panacea_show_request_issues_list_view', $this->data);
	}

	public function get_field_officer_docs()
	{
		$fieldOfficerReq = isset($_POST['fieldOfficerReq']) ? $_POST['fieldOfficerReq'] : "Doctor Visits:";
		$format_string = explode(":", $fieldOfficerReq);
		$fieldOfficerReq = $format_string[0];
		
		$this->data['get_field_officer_docs'] = $this->panacea_common_model->get_field_officer_req_students_docs($fieldOfficerReq);
		$this->data['field_officer_requests'] = $fieldOfficerReq;
		
		$this->_render_page('panacea_admins/panacea_show_field_officer_report_list_view', $this->data);
	}
	public function get_doctor_visiting_docs()
	{
		$doctorVisitReq = $_POST['doctorVisitReq'];
		$format_string = explode(":", $doctorVisitReq);
		$doctorVisitReq = $format_string[0];
		
		$this->data['get_field_officer_docs'] = $this->panacea_common_model->get_field_officer_req_students_docs($doctorVisitReq);
		$this->data['field_officer_requests'] = $doctorVisitReq;
		
		$this->_render_page('panacea_admins/panacea_show_field_officer_report_list_view', $this->data);
	}

	public function update_dr_visiting_and_submit()
  	{
  		// POST DATA
  		$doc_id = $this->input->post('doc_id',true);
		$unique_id = $this->input->post('unique_id',TRUE);
		$remarks = $this->input->post('page_student_remarks',TRUE);
		$updated = $this->panacea_common_model->update_dr_visiting_and_submit($doc_id,$remarks);
		
		 if ($updated) // the information has therefore been successfully saved in the db
			{
				$this->session->set_flashdata('success','Doctor Visiting Report Updated successfully !!');
				redirect('panacea_mgmt/get_field_officer_docs');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('panacea_mgmt/get_field_officer_docs');
			}

	}


	public function show_doctor_treated_student($doc_id)
	 {
		$this->data['unique_id'] = $this->panacea_common_model->show_doctor_visiting_reports($doc_id);
		$this->_render_page('panacea_admins/field_dr_show_submitted_list',$this->data);
	}
	public function show_field_officer_student($doc_id)
	 {
		$this->data['unique_id'] = $this->panacea_common_model->show_field_officer_submit_student($doc_id);
		$this->_render_page('panacea_admins/field_officer_show_submitted_list',$this->data);
	}
	public function get_schools_health_status()
	{
		$chronic_count = $this->panacea_common_model->get_schools_health_status_count_model_new();
		
		$this->data['request_report'] = json_encode($chronic_count);
		$this->_render_page('panacea_admins/tswreis_schools_status_view', $this->data);
	}
	public function get_schoolhealth_students_values()
	{
		$student_type = $_POST['student_type'];
		$chronic_count = $this->panacea_common_model->get_schools_health_status_count_model_new($student_type);
		$this->data = json_encode($chronic_count);
		$this->output->set_output($this->data);
	}
	public function get_chronic_student_docs()
	{
		$labelColor = $_POST['labelColor'];
		$schoolName = $_POST['schoolName'];
		$this->data['all'] = $this->panacea_common_model->get_chronic_student_docs_model($labelColor, $schoolName);
		/*echo '<pre>';
			echo print_r($this->data,true)."<br>";
			
			echo '</pre>';
			exit;*/
		$this->_render_page('panacea_admins/tswreis_symptom_wise_school_view', $this->data);
	}
	public function send_sms_list()
	{
		$this->data['message'] = "";
		$this->_render_page('panacea_admins/panacea_send_sms_list',$this->data);
	}

	public function get_attendance_msg_info()
	{
		$today_date = (isset($_POST['today_date'])) ? $_POST['today_date'] : Date('Y-m-d');
		$type_of_msg = $_POST['type_of_msg'];
		$query = $this->panacea_common_model->get_sms_messages_list($today_date, $type_of_msg);

		if(isset($query) && !empty($query))
		{
			$this->data['sms_list'] = $query[0][$today_date];
			$this->data['today_date'] = $today_date;
			$this->output->set_output(json_encode($this->data));
		}else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
		

		
	}


	public function get_requests_students_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$request_pie_status = $_POST['request_pie_status'];
		$student_type = $_POST['student_type'];
		$student_age = $_POST['student_age'];
		$this->data = $this->panacea_common_lib->update_request_pie($today_date,$request_pie_span,$request_pie_status,$student_type,$student_age);
	    //echo print_r($this->data,true);exit();
		$this->output->set_output($this->data);
	}

	/*******************request chart Start #author suman reddy***************************************/
	public function get_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		$dist_id = $_POST['dist_id'];
		$this->data = $this->panacea_common_model->get_schools_by_dist($dist_id);
		$this->output->set_output(json_encode($this->data));
	}
	public function monthly_request_charts()
	{
		
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		$this->_render_page('panacea_admins/get_month_wise_report', $this->data);

	}
	public function monthly_request_report_chart()
	{
		
		$date_month = $this->input->post('month_date',true);
		$school_name = $this->input->post('school_name',true);
		$dt_name = $this->input->post('dt_name',true);
			
		$this->data['data']= $this->panacea_common_model->get_monthly_request_by_month($date_month,$school_name,$dt_name);
		$this->output->set_output(json_encode($this->data));

	}
	public function drill_down_chronic_student_list()
	{

		$chronic_symtom = $this->input->post('chronic_symtom',true);
		

		$this->data['student_list'] = $this->panacea_common_model->drill_down_chronic_student_list($chronic_symtom);
		
		$this->_render_page('panacea_admins/monthly_chronic_student_list', $this->data);
	}
	public function drill_down_emergency_student_list()
	{
		$emergency_symtom = $this->input->post('emergency_symtom',true);
		$this->data['student_list'] = $this->panacea_common_model->drill_down_emergency_student_list($emergency_symtom);		
		$this->_render_page('panacea_admins/monthly_emergency_student_list', $this->data);
	}
	public function drill_down_normal_student_list()
	{
		$normal_symtom = $this->input->post('normal_symtom',true);
		$this->data['student_list'] = $this->panacea_common_model->drill_down_normal_student_list($normal_symtom);		
		$this->_render_page('panacea_admins/monthly_normal_student_list', $this->data);
	}

	public function show_yearly_hb_graph()
	{
		$present_year = date('Y');
		$data = $this->panacea_common_model->update_hb_request_pie($present_year);
		$this->data['documents'] = json_encode($data);
		$this->_render_page('panacea_admins/show_yearly_hb_graph_view', $this->data);
	}

	public function update_hb_request_pie()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('update_hb_request_pie');
		
		$select_year = $_POST["select_year"];		
		$data = json_encode($this->panacea_common_model->update_hb_request_pie($select_year));
		$this->output->set_output($data);		
	}
	
	function drill_down_request_to_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_district');
		$data = $_POST['data'];
		$select_year = $_POST['select_year'];
		$identifiers_report = json_encode($this->panacea_common_model->drill_down_request_to_district($data, $select_year));
		$this->output->set_output($identifiers_report);
	}

	function drilldown_request_to_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_school');
		$data = $_POST['data'];
		$select_year = $_POST['select_year'];
		$request_report = json_encode($this->panacea_common_model->drilldown_request_to_school($data, $select_year));
		$this->output->set_output($request_report);
	}

	function drilldown_request_to_student()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_student');
		$data = $_POST['data'];
		$select_year = $_POST['select_year'];
		$docs = $this->panacea_common_model->drilldown_request_to_student($data, $select_year);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	function drill_down_hb_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_chronic_request_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		$get_docs = $this->panacea_common_model->drill_down_request_to_students_load_ehr($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		$doc_list = $this->panacea_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('panacea_admins/student_list_to_students_load_ehr',$this->data);
	}
      /**
   * Helper: Student Health Track Chart
   * @author Bhanu
   */

  public function disease_wise_students_list()
  {
        $this->data['today_date'] = date('Y-m-d');
        $this->data['district_list'] = $this->panacea_common_model->get_all_district();
        $this->_render_page('panacea_admins/disease_wise_students_list',$this->data);
  }

  public function get_month_wise_diseases()
 {
    $selectedMonth = substr($_POST['selectedMonth'], 0,-3);    
    $selectedDistrict = $_POST['selectedDistrict'];
    $selectedSchool= $_POST['selectedSchool'];
    $data = $this->panacea_common_model->get_data($selectedMonth, $selectedDistrict, $selectedSchool);
    $this->output->set_output(json_encode($data));
 }

  public function get_students_list_based_on_request_symptom()
  {

      $symptomName = $_POST['symptomName'];
      $symptomCategory = $_POST['symptomCategory'];
      $selectedMonth = substr($_POST['selectedMonth'], 0,-3);
      $selectedDistrict = $_POST['selectedDistrict'];
      $selectedSchool= $_POST['selectedSchool'];
      $this->data['students'] = $this->panacea_common_model->get_students_list_based_on_request_symptom($symptomName, $symptomCategory, $selectedMonth, $selectedDistrict, $selectedSchool);
      $this->data['symptomName'] = $symptomName;
      $this->_render_page('panacea_admins/students_list_based_on_request_symptom',$this->data);
  }

  public function get_date_wise_attendance_report()
  {
  	$today_date = $_POST['today_date'];
  	$count = 0;
  	$absent_report = $this->panacea_common_model->get_all_absent_data($today_date);
  	foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
	if($count > 0){
			$this->data['absent_report'] = $absent_report;

		}else{
			$this->data['absent_report'] = 1;
			
		}
	$this->data['absent_report_schools_list'] = $this->panacea_common_model->get_absent_pie_schools_data($today_date);

	//echo print_r($this->data['absent_report'], true); exit();
	//$this->data['sanitation_report_schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($today_date);
		
  	$this->output->set_output(json_encode($this->data));
  }

  public function get_screening_hb_data_by_gender()
    {
          $hb_boys = $this->panacea_common_model->get_screening_hb_data_for_boys();
          $this->data['hb_boys'] = json_encode($hb_boys);
          //echo print_r($this->data['hb_boys'], true); exit();

          $hb_girls = $this->panacea_common_model->get_screening_hb_data_for_girls();
          $this->data['hb_girls'] = json_encode($hb_girls);

      if(!empty($this->data))
      {
        $this->output->set_output(json_encode($this->data));
      }
    }

public function get_hb_students_girls_from_bar()
        {
        $symptom_type = $this->input->post('hb_symptom', true);
        $gender = $this->input->post('gender_hb',  true);

        if($gender == 'Male'){
        $gender_type = 'Male';
        }else{
             $gender_type = 'Female';
        }

        $this->data['scl_list'] = $this->panacea_common_model->get_hb_submitted_schools_data_with_symptom($symptom_type, $gender_type);

      /*  echo '<pre>';
        echo print_r( $this->data['scl_list'], true);
         echo '</pre>';
        exit();*/
       
        $this->_render_page("panacea_admins/hb_students_schools_list", $this->data);
  }

  public function hb_overall_dashboard(){
        $this->data = "";
       
        $this->_render_page("panacea_admins/hb_overall_dashboard", $this->data);
    }

    public function bmi_overall_dashboard(){
        $this->data = "";

        $this->_render_page("panacea_admins/bmi_overall_dashboard", $this->data);
    }

    public function get_screening_bmi_data_by_gender()
    {
          $bmi_boys = $this->panacea_common_model->get_screening_bmi_data_for_boys();
          $this->data['bmi_boys'] = json_encode($bmi_boys);
          //echo print_r($this->data['hb_boys'], true); exit();

          $bmi_girls = $this->panacea_common_model->get_screening_bmi_data_for_girls();
          $this->data['bmi_girls'] = json_encode($bmi_girls);

      if(!empty($this->data))
      {
        $this->output->set_output(json_encode($this->data));
      }
    }

    public function get_bmi_students_girls_from_bar()
        {
        $symptom_type = $this->input->post('bmi_symptom', true);
        $gender = $this->input->post('gender_bmi',  true);

        if($gender == 'Male'){
        $gender_type = 'Male';
        }else{
             $gender_type = 'Female';
        }

        $this->data['scl_list'] = $this->panacea_common_model->get_bmi_submitted_schools_data_with_symptom($symptom_type, $gender_type);

      /*  echo '<pre>';
        echo print_r( $this->data['scl_list'], true);
         echo '</pre>';
        exit();*/
       
        $this->_render_page("panacea_admins/bmi_students_schools_list.php", $this->data);
  }

  public function get_students_bmi_list_scl_wise()
  {
    $school_name = $this->input->post('school_name', true);
    $symptom_type = $this->input->post('symptom_name', true);
    $gender_type = $this->input->post('gender_type', true);

    $this->data['student_list'] = $this->panacea_common_model->get_students_bmi_list_scl_wise($school_name, $symptom_type, $gender_type);

    $this->_render_page("panacea_admins/students_bmi_list_school_wise.php", $this->data);
  }

    public function get_students_hb_list_scl_wise()
  {
  	$school_name = $this->input->post('school_name', true);
  	$symptom_type = $this->input->post('symptom_name', true);
  	$gender_type = $this->input->post('gender_type', true);

  	$this->data['student_list'] = $this->panacea_common_model->get_students_hb_list_scl_wise($school_name, $symptom_type, $gender_type);

  	$this->_render_page("panacea_admins/students_hb_list_school_wise.php", $this->data);
  }

 public function get_searched_student_sick_requests()
  {
  $search_data = $this->input->post('search_value', true);

  $this->data = $this->panacea_common_model->get_searched_student_sick_requests($search_data);

  

  $this->output->set_output(json_encode($this->data));
  }

  public function get_data_for_cards()
  {
  	$academic_year = $this->input->post('academic_year', true);
  	$district_name = $this->input->post('district_name', true);
  	$school_name = $this->input->post('school_name', true);
  	//$school_name = 'All';
  	$gender_type = $this->input->post('gender_type', true);


  	$this->data = $this->panacea_common_model->get_data_for_cards_with_filters($academic_year, $district_name, $school_name, $gender_type);

 

  	if(isset($this->data) && !empty($this->data))
  	{
  		$this->output->set_output(json_encode($this->data));
  	}else{

  		$this->output->set_output(json_encode('No Data Found'));
  	}

  }
  
  public function get_daily_request_for_bar()
{
	$today_date = $this->input->post('today_date', true);
	$district_name = $this->input->post('district_name', true);
	$school_name = $this->input->post('school_name', true);
	//echo print_r($today_date, true); exit();

	$this->data = $this->panacea_common_model->get_daily_request_for_bar($today_date, $district_name, $school_name);
	
	if(isset($this->data) && !empty($this->data))
	{
		$this->output->set_output(json_encode($this->data));
	}else{
		$this->output->set_output(json_encode("No Data Found"));
	}
}

public function get_daily_updated_request_for_bar()
	{
		$start_date = $this->input->post('request_start_date', true);
	  	$end_date = $this->input->post('request_end_date', true);
	  	$district_name = $this->input->post('district_name', true);
	  	$school_name = $this->input->post('school_name', true);


	  	$this->data = $this->panacea_common_model->get_daily_updated_request_for_bar($start_date, $end_date, $district_name, $school_name); 
	  	
	  	if(isset($this->data) && !empty($this->data))
	  	{
	  		$this->output->set_output(json_encode($this->data));
	  	}else{
	  		$this->output->set_output(json_encode("No Data Found"));
	  	}
	  	
	}

  public function get_hospital_type_of_bar_count()
  {
   // $today_date = $this->input->post('today_date', true);
     $start = $this->input->post('start_date', true);
     $end   = $this->input->post('end_date', true); 
   /* echo print_r($start, true);
    echo print_r($end, true);
     exit();*/
    $this->data = $this->panacea_common_model->get_hospital_type_of_bar_count_model($start,$end); 
    
    if(isset($this->data) && !empty($this->data))
    {
        $this->output->set_output(json_encode($this->data));
    }else{
        $this->output->set_output(json_encode("No Data Found"));
    }
  }

   public function get_hospital_type_data_table()
    {
        
        $start = $this->input->post('start_date_type', true);
        $end = $this->input->post('end_date_type', true);
        $hospital_type = $this->input->post('type_id', true);

       /* echo print_r($start,true);
        echo print_r($end,true);
        echo print_r($hospital_type,true);
        exit();*/
        
        $this->data['students_details'] = $this->panacea_common_model->get_hospital_type_data_table_model($start,$end, $hospital_type);

        $this->data['type'] = $hospital_type;
      
        $this->_render_page('panacea_admins/get_hospital_type_data_table_view', $this->data);

    }

  public function get_total_counts_requests_pie()
  {
  	$academic_year = $this->input->post('academic_year', true);
  	$district_name = $this->input->post('district_name', true);
  	$school_name = $this->input->post('school_name', true);
  	$gender_type = $this->input->post('gender_type', true);
  	/*echo print_r($academic_year, true);
  	echo print_r($district_name, true);
  	echo print_r($school_name, true);
  	exit();
  	echo print_r($gender_type, true);*/
 	$this->data = $this->panacea_common_model->get_total_counts_requests_pie($academic_year, $district_name, $school_name, $gender_type);

 	if(isset($this->data) && !empty($this->data))

  	{
  		$this->output->set_output(json_encode($this->data));

  	}else{
  		$this->output->set_output(json_encode("No Data Found"));
  	}


  }

  public function get_screening_pie_values()
	{
		$academic_year = $this->input->post('academic_year', true);


		if($academic_year == '2019-2020'){
			$span = '2018-2019';
		}

		$this->data = $this->panacea_common_model->get_screening_pie_values($span);

		if(!empty($this->data))
		  {
	        $this->output->set_output(json_encode($this->data));
		  }

	}

	public function tswreis_diseases_counts_report()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$this->data['abnormality'] = $_POST['abnormality_name'];
			$this->data['academic'] = $_POST['academic_year'];
		}

		//Getting all districts list.
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		
		//Getting collections list for students report fetching academic year wise.
		//$this->data['collection_list'] = $this->panacea_common_model->get_all_academic_wise_collection_list();

		$this->_render_page('panacea_admins/tswreis_diseases_counts_report', $this->data);
	}

	public function get_all_screening_diseases_counts()
	{
		$academic_year = $this->input->post('academic_year', true);
		$abnormalities = $this->input->post('abnormalities', true);
		$dt_name = $this->input->post('po_name', true);
		$school_name = $this->input->post('school_name', true);
		if($dt_name != 'All'){
			$dist = $this->panacea_common_model->get_dt_name_based_on_id($dt_name);
			$po_name = $dist[0]['dt_name'];
		}else{
			$po_name = 'All';
		}

	
		$abnormalities = $this->panacea_common_model->get_all_screening_diseases_counts($academic_year, $abnormalities, $po_name, $school_name);

		if(count($abnormalities) > 0)
		{
			$result = call_user_func_array("array_merge", $abnormalities);
			$this->output->set_output(json_encode($abnormalities));

		}
		else
		{
			
			$this->output->set_output(json_encode("No Problems found"));
		}

	}

	public function get_schools_by_symptom()
	{
		$this->check_for_admin();
		$symptom = $_POST['symptom_name'];
		$school_name = $_POST['school_name'];
		$dt_name = $_POST['po_name'];
		$academic_year = $_POST['academic'];
		if($dt_name != 'All'){
			$dist = $this->panacea_common_model->get_dt_name_based_on_id($dt_name);
			$po_name = $dist[0]['dt_name'];
		}else{
			$po_name = 'All';
		}

		$this->data['students_list'] = $this->panacea_common_model->get_schools_by_symptom($symptom, $academic_year, $po_name, $school_name);

		//$this->data['hospitals'] = $this->maharashtra_common_model->get_all_hospitals();
		$this->data['symptom_name'] = $symptom; 
		$this->data['academic_year'] = $academic_year;
		$this->data['symptom_count'] = array_sum($this->data['students_list']);
		//$this->data['students_count'] = count($this->data['students_list']); 
		$this->_render_page('panacea_admins/symptom_wise_schools_list_view',$this->data);

	}

	public function get_students_by_symptom()
	{
		$this->check_for_admin();
		$symptom = $_POST['symptom_name'];
		$school = $_POST['school_name'];
		$academic_year = $_POST['academic_year'];

		
		$this->data['students_list'] = $this->panacea_common_model->get_students_by_symptom($symptom, $school, $academic_year);

		//$this->data['hospitals'] = $this->maharashtra_common_model->get_all_hospitals();
		$this->data['symptom_name'] = $symptom; 
		$this->data['academic_year'] = $academic_year; 
		$this->data['students_count'] = count($this->data['students_list']); 
		$this->_render_page('panacea_admins/symptom_wise_students_list_view',$this->data);

	}

	public function to_daily_health_request()
	{
		
		$date = $this->input->post('date_id', true);
		$request_type = $this->input->post('val_id', true);
		$school_name = $this->input->post('scl_id', true);
		$district = $this->input->post('dist_id', true);		

		$this->data['students_details'] = $this->panacea_common_model->to_daily_health_request($date, $request_type,$school_name, $district);
		
		$this->_render_page('panacea_admins/to_daily_health_request', $this->data);

	}

	public function get_daily_updated_health_request()
	{
		$this->data['today_date'] = date('Y-m-d');
		$start_date = $this->input->post('sart_id', true);	
		$end_date = $this->input->post('end_id', true);	
		$request_type = $this->input->post('value_id', true);
		$school_name = $this->input->post('school_id', true);
		$district = $this->input->post('district_id', true);		

		$this->data['students_details'] = $this->panacea_common_model->get_daily_updated_health_request($start_date,$end_date, $request_type,$school_name, $district);
		
		$this->_render_page('panacea_admins/get_daily_updated_health_request', $this->data);

	}

	public function dr_responce_daily_health_request()
	{
		$date = $this->input->post('dr_date',true);
		$status = $this->input->post('dr_responce',true);	

		$this->data['dr_responcer'] = $this->panacea_common_model->get_dr_name_wise_submitted_docs_students_model($date);

		$this->_render_page('panacea_admins/dr_responce_daily_health_request_view', $this->data);

	}

	public function get_daily_attendance_report()
	 {
	   $today_date = $this->input->post('today_date', true);	  

	   $this->data = $this->panacea_common_model->get_daily_attendance_report_model($today_date);
	   
	   if(isset($this->data) && !empty($this->data))
	   {
	       $this->output->set_output(json_encode($this->data));
	   }else{
	       $this->output->set_output(json_encode("No Data Found"));
	   }

	 }

	public function get_attendance_data_for_bar_schools()
	{
		$date = $this->input->post('date_attendance', true);
		$attendance_type = $this->input->post('value_attn',true);

		$this->data['attendance_details'] = $this->panacea_common_model->get_attendance_data_for_bar_schools($date, $attendance_type);
		$sum = 0;
		   foreach ($this->data['attendance_details'] as $count) {
		   $sum += $count['value'];
		}

		$this->data['attendance_type'] = $attendance_type;
		$this->data['today_date'] = $date;
		$this->data['symptom_count'] = $sum;

		$this->_render_page('panacea_admins/attendance_data_for_schools', $this->data);
	}

	public function get_attendance_data_for_bar_students()
	{
		$symptom = $_POST['symptom_type'];
        $school = $_POST['school_name'];
        $date = $_POST['today_date'];
        

		$this->data['students_list'] = $this->panacea_common_model->get_attendance_data_for_bar_students($symptom, $school, $date);

		
		$this->data['today_date'] = $date;
		$this->data['school_name'] = $school;
		 $this->data['symptom_name'] = $symptom;
		$this->data['students_count'] = count($this->data['students_list']);

		$this->_render_page('panacea_admins/attendance_data_for_students', $this->data);
	}

	public function get_total_requests_pie_table_data()
	{
		$date = $this->input->post('',true);
		$request_type_total = $this->input->post('',true);

		$this->data['request_details'] = $this->panacea_common_model->get_total_requests_pie_table_data($date ,$request_type_total);

		$this->_render_page('panacea_admins/request_pie_total_counts_per_year', $this->data);
	}

	public function get_sanitation_day_to_day_counts()
	{
		$today_date = $this->input->post('today_date', true);
		//$today_date       = date("Y-m-d");
		$this->data['sanitation_report_schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($today_date);

		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode("No School Submitted Today"));
		}
	}

	public function get_sanitation_report_school_wise()
	{
		$today_date = $this->input->post('today_date', true);
		$sanitaion_type = $this->input->post('sanitation_type', true);
		
		$this->data['date'] = $today_date;
		$this->data['sanitaion'] = $sanitaion_type;
		$this->data['today_date'] = date('Y-m-d');
	
		$this->_render_page('panacea_admins/sanitation_display_report_view', $this->data);
	}

	public function get_sanitation_data_schools_data()
	{
		$today_date = $this->input->post('today_date', true);
		$sanitaion_type = $this->input->post('sanitation_type', true);

		$this->data = $this->panacea_common_model->get_schools_list_based_on_sanitation_type($today_date, $sanitaion_type);

		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode('No Data Available'));
		}
	}

	public function get_not_working_sanitation_schools_data()
	{
		$start_date = $this->input->post('start_date', true);
		$end_date = $this->input->post('end_date', true);
		$request = $this->input->post('request_data', true);
		//$request = 'Animal around campus';

		$this->data = $this->panacea_common_model->get_not_working_sanitation_schools_data($start_date, $end_date, $request);

		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode('No Data Available'));
		}
	}

	public function send_text_message_sanitation()
	{
		
		$post = $_POST;

		 $username="SRINILONONKA";
        //$password ="475489";
        $password ="9866059098";
        $sender='SIFSMS';

		$scl_names = $post['ehr_data_for_request'];
		$message = strval($post['message']);
		$date = $post['set_date'];
		$msg = [];
		$phone_no = $this->panacea_common_model->get_mobile_no_based_on_school_name($scl_names);

		foreach ($phone_no as $number) {
			

        $url="https://www.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($number)."&sender=".urlencode($sender)."&message=".urlencode($message)."&type=".urlencode('3');
       echo print_r($url, true); 
       // log_message('error','Mobile number:'.$recerverNO.' url Message: '.$url);
      


         $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curl_scraped_page = curl_exec($ch);

        curl_close($ch);

		}

		

		//$this->data = $this->panacea_common_model->send_text_message_sanitation($scl_names, $message, $date);



		$this->_render_page('panacea_admins/sanitation_display_report_view', $this->data);
	}


	public function get_hospitalized_data_count()
	{
		
    //$today_date = $this->input->post('today_date', true);
     $start = $this->input->post('start_date', true);
	 $end   = $this->input->post('end_date', true);   

    $this->data = $this->panacea_common_model->get_hospitalized_data_count_model($start,$end); 
    
    if(isset($this->data) && !empty($this->data))
    {
        $this->output->set_output(json_encode($this->data));
    }else{
        $this->output->set_output(json_encode("No Data Found"));
    }

	}

	 public function get_hospitalized_data_table()
    {         
        $start = $this->input->post('start_date', true);
        $end = $this->input->post('end_date', true);
        $request_type = $this->input->post('status_type', true);
        $hospital_district = $this->input->post('hospital_district_name', true);

        $this->data['students_details'] = $this->panacea_common_model->get_hospitalized_data_table_model($start,$end,$request_type, $hospital_district);
        $this->data['request_type'] = $request_type;
        $this->data['hospital_district'] = $hospital_district;
        
        $this->_render_page('panacea_admins/hospitalised_students_table_data', $this->data);

    }

     public function get_hb_gender_wise_data_count()
    {
    $start = $this->input->post('start_date', true);
    $end = $this->input->post('end_date', true);

    $this->data = $this->panacea_common_model->get_hb_gender_wise_data_count_model($start,$end); 
    
    if(isset($this->data) && !empty($this->data))
    {
        $this->output->set_output(json_encode($this->data));
    }else{
        $this->output->set_output(json_encode("No Data Found"));
    }

    }

     public function get_hb_gender_wise_data_table()
    {        
        
        $start = $this->input->post('start_date_hb', true);
        $end = $this->input->post('end_date_hb', true);
        $hb_type = $this->input->post('hb_type', true);
        $hb_gender = $this->input->post('hb_gender', true);

        $this->data['schools_list'] = $this->panacea_common_model->get_hb_gender_wise_data_table_model($start, $end, $hb_type, $hb_gender);

        $this->data['gender'] = $hb_gender;
        $this->data['type']  = $hb_type;
        $this->data['start_date']  = $start;
        $this->data['end_date']  = $end;
               
        $this->_render_page('panacea_admins/hb_gender_wise_schools_data_count_view', $this->data);
    }

     public function hb_gender_wise_students_data()
    {
        
        //$date_hb = $this->input->post('date', true);
        $start_hb = $this->input->post('start_date', true);
        $end_hb = $this->input->post('end_date', true);
        $hb_type = $this->input->post('type', true);
        $hb_gender = $this->input->post('gender', true);
        $school_name = $this->input->post('school_name', true);

        $this->data['students_details'] = $this->panacea_common_model->get_hb_gender_wise_students_data_table_model($start_hb,$end_hb, $hb_type, $hb_gender, $school_name);
        
        $this->_render_page('panacea_admins/hb_gender_wise_students_data_view', $this->data);      

    }

    public function get_bmi_gender_wise_data_count()
    {
        
    $start = $this->input->post('start_date', true);
    $end = $this->input->post('end_date', true);  

    $this->data = $this->panacea_common_model->get_bmi_gender_wise_data_count_model($start,$end); 
    
    if(isset($this->data) && !empty($this->data))
    {
        $this->output->set_output(json_encode($this->data));
    }else{
        $this->output->set_output(json_encode("No Data Found"));
    }

    }

     public function get_bmi_gender_wise_data_table()
    {
        
        $start = $this->input->post('start_date_bmi', true);
        $end = $this->input->post('end_date_bmi', true);
        $bmi_type = $this->input->post('bmi_type', true);
        $bmi_gender = $this->input->post('bmi_gender', true);

        $this->data['schools_list'] = $this->panacea_common_model->get_bmi_gender_wise_data_table_model($start, $end, $bmi_type, $bmi_gender);
     	
        //$this->data['date']  = $date;
        $this->data['gender'] = $bmi_gender;
        $this->data['type']   = $bmi_type;
        $this->data['start_date']  = $start;
        $this->data['end_date']  = $end;

        $this->_render_page('panacea_admins/bmi_gender_wise_schools_data_count_view', $this->data);
       
           
    }

     public function bmi_gender_wise_students_data()
    {
        
        $start_bmi = $this->input->post('start_date', true);
        $end_bmi = $this->input->post('end_date', true);
        $bmi_type = $this->input->post('type', true);
        $bmi_gender = $this->input->post('gender', true);
        $school_name = $this->input->post('school_name', true);
        
        $this->data['students_details'] = $this->panacea_common_model->get_bmi_gender_wise_students_data_table_model($start_bmi, $end_bmi, $bmi_type, $bmi_gender, $school_name);
        
        $this->_render_page('panacea_admins/bmi_gender_wise_students_data_view', $this->data);      

    }

	public function get_school_health_status_zone_schools()
	{
		$this->data="";

		$this->_render_page('panacea_admins/school_health_status_for_schools' , $this->data);
	}
	
	public function get_disease_wise_school_health_status()
	{
		$status = $this->input->post('status',true);

		$this->data = $this->panacea_common_model->get_scabies_schools_health_status_count_model_new();


		/*if($status_scl == 'Scabies'){
			

		}elseif ($status_scl == 'Abdominal') {
			$this->data = $this->panacea_common_model->get_abnormal_disease_status_count_model_new();

		}elseif ($status_scl == 'Bites') {
			$this->data = $this->panacea_common_model->get_unknown_bites_status_count_model_new();
		}elseif ($status_scl == 'HB Status') {
			$this->data = $this->panacea_common_model->get_schools_health_hb_status_count_model_new();
		}elseif ($status_scl == 'BMI status') {
			$this->data = $this->panacea_common_model->get_schools_health_bmi_status_count_model_new();
		}

        */


		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode('No Data Available'));
		}

	}

	public function get_school_health_status_zone_students()
	{
		$this->data="";

		$this->_render_page('panacea_admins/school_health_status_for_students' , $this->data);
	}

	public function show_quick_glance_label_counts()
    {
    	 
            if(isset($_POST['today_date']))
            {
                $today_date = $_POST['today_date'];
           			
                $data_req = $this->panacea_common_model->get_quick_glance_label_counts_model($today_date);

               //$today_regular_followup = $this->panacea_common_model->get_today_regular_followup_cases_for_dashboard_status($today_date);                
                //$this->data['today_regular_followup_dashboard'] = $today_regular_followup;
               
                $this->data['surgery_needed_counts'] = $data_req['surgery_needed_counts'];

                $this->data['fo_out_patient_count'] = $data_req['fo_out_patient_count'];

                //$this->data['doctor_visiting_count'] = $data_req['doctor_visiting_count'];

                $this->data['fo_emergency_count'] = $data_req['fo_emergency_count'];

                $this->data['aneamia_cases_count'] = $data_req['aneamia_cases_count'];

                $this->data['fo_review_cases_count'] = $data_req['fo_review_cases_count'];

                $this->data['doc_visiting_schools_count'] = $data_req['count'];               
          
                if(!empty($this->data))
                {
                    $this->output->set_output(json_encode($this->data));
                }else
                {
                    $this->output->set_output(json_encode(array('status' => FALSE,'message'=>'failed')));
                }
            }
    }

     public function get_day_to_day_glance_students()
	{
		
		$date = $this->input->post('today_date',true);
		$status = $this->input->post('day_to_day_status',true);
		
		$this->data['get_students'] = $this->panacea_common_model->get_day_to_day_glance_students_model($date,$status);
		
		$this->data['get_students_count'] = count($this->data['get_students']);
		$this->data['day_to_day_status'] = $status;
		
		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode('No Data Available'));
		}

		$this->_render_page('panacea_admins/day_to_day_glance_students_data_view',$this->data);
	
	}

	/*school information model*/

	public function getschoolsInformation()
   {
  
 	 $this->data = $this->panacea_common_model-> getschoolsInformation_model();
  
  		if(!empty($this->data)){

  	 $this->output->set_output(json_encode($this->data));

  		}else{
  		
  		 $this->output->set_output(json_encode("No Data Available"));
  	}

   }

   public function anemia_daily_health_request(){

   	$date = $this->input->post('hs_date',true);
   	$status = $this->input->post('hs_responce',true); 

   	$this->data['anemic_cases'] = $this->panacea_common_model->get_hs_anemia_submitted_docs_students_model($date);

   	$this->_render_page('panacea_admins/anemia_daily_health_request_view', $this->data);

   }

   public function get_anemia_records_based_on_span()
   {
   	$start = $this->input->post('start_date', true);
   	$end = $this->input->post('end_date', true);
   
   	$this->data = $this->panacea_common_model->get_anemia_records_based_on_span_model($start, $end);
	
 if(!empty($this->data)){
   		$this->output->set_output(json_encode($this->data));
   	}else{
   		$this->output->set_output(json_encode("No Data Available"));
   	}   

   }

      public function get_surgery_records_based_on_span()
      {
      	$start = $this->input->post('start_date', true);
      	$end = $this->input->post('end_date', true);
    	
      	$this->data = $this->panacea_common_model->get_surgery_records_based_on_span_model($start, $end);
   	
    if(!empty($this->data)){
      		$this->output->set_output(json_encode($this->data));
      	}else{
      		$this->output->set_output(json_encode("No Data Available"));
      	}      	

      }

   public function surgery_daily_health_request(){

   	$date = $this->input->post('date',true);
   	$status = $this->input->post('hs_surgery',true); 
   
   	$this->data['surgery_cases'] = $this->panacea_common_model->get_hs_surgery_submitted_docs_students_model($date);
 
   	$this->_render_page('panacea_admins/surgery_daily_health_request_view', $this->data);

   }

    public function daily_doctor_visit_schools_list(){

   	$date = $this->input->post('visiting_date',true);
   	$status = $this->input->post('doctor_visit',true); 
   
   	$this->data['doctor_visit'] = $this->panacea_common_model->get_daily_doctor_visit_schools_list_model($date, $status);
 	
 	$this->data['dr_visit']  = $status;
    $this->data['date']  = $date;

   	$this->_render_page('panacea_admins/daily_doctor_visit_schools_list_view', $this->data);

   }

   public function visiting_doctor_students_data()
   {

   		$date = $this->input->post('date',true);
   	    $status = $this->input->post('dr_visit',true);
   	    $school_name = $this->input->post('school_name', true);   	  

   	  $this->data['dr_visit_student_list'] = $this->panacea_common_model->get_daily_doctor_visit_student_list_model($date, $status, $school_name);

   	  $this->_render_page('panacea_admins/daily_doctor_visit_students_list_view', $this->data);

   }

     public function get_dr_visit_schools_based_on_span()
     {
      $start = $this->input->post('start_date', true);
      $end = $this->input->post('end_date', true);

      $this->data = $this->panacea_common_model->get_dr_visit_schools_based_on_span_model($start, $end);
      
   if(!empty($this->data)){
          $this->output->set_output(json_encode($this->data));
      }else{
          $this->output->set_output(json_encode("No Data Available"));
      }    

     }

     public function get_dr_visit_students_list_based_on_span()
   {
   	$start = $this->input->post('start_date', true);
   	$end = $this->input->post('end_date', true);
   
   	$this->data = $this->panacea_common_model->get_dr_visit_students_list_based_on_span_model($start, $end);
	
 if(!empty($this->data)){
   		$this->output->set_output(json_encode($this->data));
   	}else{
   		$this->output->set_output(json_encode("No Data Available"));
   	}   

   }

   public function time_span_visiting_doctor_students_data()
   {
	$start = $this->input->post('start', true);
	$end = $this->input->post('end', true);
	$school_name = $this->input->post('scl_id', true); 	
    $this->data['dr_visit_student_list'] = $this->panacea_common_model->get_time_span_visiting_student_list_model($school_name,$start,$end);
    $this->_render_page('panacea_admins/daily_doctor_visit_students_list_view', $this->data);
   }

   public function requests_notes()
   {
            
       $this->data['notes_data'] = $this->panacea_common_model->requests_notes_model(); 
      
       $this->_render_page('panacea_admins/requests_notes_view');
   }

   public function panacea_rhso_reports()
   {   	  
   		$today_date =  date('Y-m-d');
   		
       $this->data['rhso_data'] = $this->panacea_common_model->panacea_rhso_reports_model($today_date); 
       
       $this->data['rhso_names'] = $this->panacea_common_model->get_rhso_names();
      
       $this->_render_page('panacea_admins/panacea_rhso_reports_view');
   }

   public function get_rhso_records_based_on_span()
	{
		
		$start = $this->input->post('start_date', true);
		$end = $this->input->post('end_date', true);
		$rhso_name = $this->input->post('rhso_mail', true);

		
		$this->data = $this->panacea_common_model->get_rhso_records_based_on_span_model($start, $end, $rhso_name);

		if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode("No Data Available"));
		}
		
	}

   public function get_hospitalized_data_count_time_span()
   {

      $start = $this->input->post('start_date', true);
      $end = $this->input->post('end_date', true);
      
      $this->data = $this->panacea_common_model->get_hospitalized_data_count_time_span_model($start,$end);
      
      if(isset($this->data) && !empty($this->data))
      {
          $this->output->set_output(json_encode($this->data));
      }else{
          $this->output->set_output(json_encode("No Data Found"));
      }

   }

    /*public function get_hospitalized_data_table_time_span()
   {
       
       $start = $this->input->post('start_date', true);
       $end = $this->input->post('end_date', true);
       $request_type = $this->input->post('status_type', true);
       $hospital_district = $this->input->post('hospital_district_name', true);
       echo print_r($start , true);
       echo print_r($end , true);
       echo print_r($request_type , true);        
       echo print_r($hospital_district , true);      
       exit();
       $this->data['students_details'] = $this->panacea_common_model->get_hospitalized_data_table_time_span_model($start,$end, $request_type, $hospital_district);
       //echo print_r($this->data,true); exit();
       $this->_render_page('panacea_admins/hospitalised_students_table_data', $this->data);

   }*/
public function update_personal_info_to_notes()
{
	$uniques = $this->panacea_common_model->update_personal_info_to_notes();

	foreach ($uniques as $value) {
	echo print_r($value,true);
		$get_info = $this->panacea_common_model->get_personal_info_for_notes($value);
		$num = 0;
		foreach ($get_info as $data) {
			echo print_r($data, true);
			$personal['name'] = $data['doc_data']['widget_data']['page1']['Personal Information']['Name'];
			$personal['class'] = $data['doc_data']['widget_data']['page2']['Personal Information']['Class'];
			$personal['school'] = $data['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
		

			$this->data = $this->panacea_common_model->update_personal_info_note($personal, $value);

			echo print_r($this->data,true);
			$num++;
			
				
		}
	}

	echo print_r($num,true);
	 $this->_render_page('panacea_admins/hospitalised_students_table_data', $this->data);
}


public function show_scrollspy()
    {
    $this->data['message'] = "";
    $today_date=Date("Y-m-d");
    //$start_date = $this->medicare_dutydoctor_model->get_scrollspy_model();
    $this->_render_page('panacea_admins/scrollspy_view');

    }
    public function post_scroll_spy()
    {
      $this->data['scrolldata'] = $this->panacea_common_model->post_scroll_spy_model($_POST);

      $this->_render_page('panacea_admins/scrollspy_view', $this->data);
      //$this->output->set_output(json_encode($query));
    }
    public function datewise_newsfeed_list()
    {
    
    $start = $this->input->post('start_date', true);

    $end = $this->input->post('end_date', true);


    $this->data = $this->panacea_common_model->datewise_newsfeed_list_model($start,$end);

    if(!empty($this->data)){
      $this->output->set_output(json_encode($this->data));
      }else{
      $this->output->set_output(json_encode("No Data Available"));
      }  

    } 

public function panacea_covid_cured_cases()
    {
    $this->data['message'] = "";

    $today_date=Date("Y-m-d");

    $passing_date = substr($today_date,0,-3); 

	$this->data['covid_cured'] = $this->panacea_common_model->panacea_covid_cured_cases_model($passing_date);
   
    $this->_render_page('panacea_admins/panacea_covid_cured_cases_view');
    }

     public function get_covid_cured_cases_students_span()
   {
    
    $start = $this->input->post('start_date', true);

    $end = $this->input->post('end_date', true);
  
    $this->data = $this->panacea_common_model->get_covid_cured_cases_students_span_model($start,$end);

    if(!empty($this->data)){

      $this->output->set_output(json_encode($this->data));
      }else{
      $this->output->set_output(json_encode("No Data Available"));
      } 

    }

     public function get_excel_covid_cured_cases_students()
	 {
	 	$start_date = $this->input->post('start_date',true);
        $end_date = $this->input->post('end_date',true);
             
    	$get_reports = $this->panacea_common_lib->get_excel_covid_cured_cases_students_lib($start_date, $end_date);

        $this->output->set_output($get_reports);
	 }

	public function tswreis_swaero_electronic_record()
	{
	 	$this->data = "";
	 	$this->_render_page('power_of_ten_admins/tswreis_swaero_electronic_record', $this->data);
	}

	public function swareo_social_network()
	{
	 	$this->data = "";
	 	$this->_render_page('power_of_ten_admins/swareo_social_network', $this->data);
	}

	public function swareo_gps_tracking()
	{
	 	$this->data = "";
	 	$this->_render_page('power_of_ten_admins/swareo_gps_tracking', $this->data);
	}


	public function swaero_mgmt_states()
	{
	 	$this->data = "";
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_states', $this->data);
	}

	public function swaero_create_state()
	{
		$post = $_POST;

		$this->power_of_ten_mgmt_model->swaero_create_state($post['state_name']);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_states');
	}

	public function swaero_mgmt_zonal()
	{
	 	
	 	$this->data['state'] = $this->power_of_ten_mgmt_model->get_states_data();
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_zonal', $this->data);
	}

	public function swaero_create_zonal()
	{
		
		$this->power_of_ten_mgmt_model->swaero_create_zonal($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_zonal');
	}

	public function swaero_mgmt_district()
	{
	 	$this->data['state'] = $this->power_of_ten_mgmt_model->get_states_data();
	 	$this->data['zone'] = $this->power_of_ten_mgmt_model->get_zones_data();
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_district', $this->data);
	}

	public function swaero_create_district()
	{
		$this->power_of_ten_mgmt_model->swaero_create_district($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_district');
	}

	public function swaero_mgmt_mandal()
	{
	 	$this->data['state'] = $this->power_of_ten_mgmt_model->get_states_data();
	 	$this->data['zone'] = $this->power_of_ten_mgmt_model->get_zones_data();
	 	$this->data['district'] = $this->power_of_ten_mgmt_model->get_district_data();
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_mandal', $this->data);
	}

	public function swaero_create_mandal()
	{
		$this->power_of_ten_mgmt_model->swaero_create_mandal($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_mandal');
	}

	public function swaero_mgmt_village()
	{
	 	$this->data['state'] = $this->power_of_ten_mgmt_model->get_states_data();
	 	$this->data['zone'] = $this->power_of_ten_mgmt_model->get_zones_data();
	 	$this->data['district'] = $this->power_of_ten_mgmt_model->get_district_data();
	 	$this->data['mandal'] = $this->power_of_ten_mgmt_model->get_mandal_data();
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_village', $this->data);
	}

	public function swaero_create_village()
	{
		$this->power_of_ten_mgmt_model->swaero_create_village($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_village');
	}

	public function swaero_mgmt_network()
	{
	 	$this->data['state'] = $this->power_of_ten_mgmt_model->get_states_data();
	 	$this->data['zone'] = $this->power_of_ten_mgmt_model->get_zones_data();
	 	$this->data['district'] = $this->power_of_ten_mgmt_model->get_district_data();
	 	$this->data['mandal'] = $this->power_of_ten_mgmt_model->get_mandal_data();
	 	$this->data['village'] = $this->power_of_ten_mgmt_model->get_village_data();
	 	$this->_render_page('power_of_ten_admins/swaero_mgmt_network', $this->data);
	}

	public function swaero_create_network()
	{
		$this->power_of_ten_mgmt_model->swaero_create_network($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_network');
	}

	public function swaero_mgmt_district_coordinator()
	{
		$this->data['coordinators'] = $this->power_of_ten_mgmt_model->get_district_coordinators_data();

		if($this->data['coordinators'] != "No data"){
			$this->data['district_coordinator_counts'] = count($this->data['coordinators']);
		}else{
			$this->data['district_coordinator_counts'] = 0;
		}
		
		$this->_render_page('power_of_ten_admins/swaero_mgmt_district_coordinator', $this->data);
	}

	public function swaero_create_district_coordinator()
	{
		$this->power_of_ten_mgmt_model->swaero_create_district_coordinator($_POST);	
	 	redirect('power_of_ten_mgmt/swaero_mgmt_district_coordinator');
	}

	public function swaero_user_reports()
	{
	 	$this->data = "";
	 	$this->_render_page('power_of_ten_admins/swaero_user_reports', $this->data);
	}

	public function send_message_to_schools()
	{
		
		$this->data = "";
		$this->_render_page('power_of_ten_admins/send_message_to_schools', $this->data);
	}

	public function swaero_todo_list()
	{
		$this->data = "";
		$this->_render_page('power_of_ten_admins/swaero_todo_list', $this->data);
	}

	public function get_total_registration()
	{
		$this->data = $this->power_of_ten_common_model->get_total_registration();
		$this->output->set_output(json_encode($this->data));
	}

	public function drilldown_selected_to_districts()
	{
		$data = $_POST['data'];

		$this->data = $this->power_of_ten_common_model->drilldown_selected_to_districts($data);

		$this->output->set_output(json_encode($this->data));
	}

	public function drilling_districts_to_mandals()
	{
		$data = $_POST['data'];
		$heading = $_POST['heading'];

		$this->data = $this->power_of_ten_common_model->drilling_districts_to_mandals($data, $heading);

		$this->output->set_output(json_encode($this->data));
	}

	public function verify_pending_requests()
	{
		$this->data['pending'] = $this->power_of_ten_common_model->verify_pending_requests();


		$this->_render_page('power_of_ten_admins/verify_swaero_pending_requests', $this->data);
	}

	public function accept_registered_users()
	{
		$id = $_POST['doc_id'];

		$this->data = $this->power_of_ten_common_model->accept_registered_users($id);
		$this->output->set_output(json_encode($this->data));
	}

	public function decline_registered_users()
	{
		$id = $_POST['doc_id'];

		$this->data = $this->power_of_ten_common_model->decline_registered_users($id);

		$this->output->set_output(json_encode($this->data));
	}

	public function get_registered_swaeros_list()
	{
		$dist = $this->input->post('registered_district', true);

		$this->data['registered'] = $this->power_of_ten_common_model->get_registered_swaeros_list($dist);
		$this->_render_page('power_of_ten_admins/get_registered_swaeros_list', $this->data);
	}

	public function swaero_user_imports()
	{
	 	$this->check_for_admin();
		$this->check_for_plan('swaero_user_imports');
	
		$this->data = $this->power_of_ten_common_lib->swaero_user_imports();
	 	$this->_render_page('power_of_ten_admins/swaero_user_imports', $this->data);
	}

	public function import_users()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_users');
		$post = $_POST;
		$this->data = $this->power_of_ten_common_lib->import_users($post);
		
		if($this->data == "redirect_to_school_fn")
		{
			redirect('power_of_ten_mgmt/panacea_mgmt_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('power_of_ten_admins/swaero_user_imports', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('power_of_ten_admins/swaero_user_imports', $this->data);
		}
	}

	public function get_funds_raised_by_swaeroes()
	{
		$fund = $this->input->post('type_of_fund', true);

		$this->data['funds'] = $this->power_of_ten_common_model->get_funds_raised_by_swaeroes($fund); 

		$this->_render_page('power_of_ten_admins/funds_raised_by_swaeroes', $this->data);
	}

	public function get_vidya_nidhi_funding_data()
	{
		$data = $this->input->post('vidya_nidhi_clicked', true);
		$this->data['datas'] = $this->power_of_ten_common_model->get_vidya_nidhi_funding_data($data);

		$this->data['clicked'] = $data;

		$this->_render_page('power_of_ten_admins/vidya_nidhi_funding_for_swaeroes', $this->data);
	}

	public function get_alumini_requests_data()
	{
		$request_type = $this->input->post('type_of_alumni_request', true);

		$this->data['requests'] = $this->power_of_ten_common_model->get_alumini_requests_data($request_type);
		$this->_render_page('power_of_ten_admins/alumini_requests_by_swaeroes', $this->data);
	}

	public function get_emergency_calls_data()
	{
		$this->data = "";
		$this->_render_page('power_of_ten_admins/emergency_calls_details_of_swaeroes', $this->data);
	}

	public function get_swaeroes_funds_data()
	{
		//$this->data = "";

		$this->data = $this->power_of_ten_common_model->get_swaeroes_funds_data();
		if(!empty($this->data))
		{
			$this->output->set_output(json_encode($this->data));
		}else
		{
			$this->output->set_output(json_encode("No Data Available"));
		}
	}

	public function get_swaeroes_vidya_nidhi_details()
	{
		$this->data = $this->power_of_ten_common_model->get_swaeroes_vidya_nidhi_details();
		if(!empty($this->data))
		{
			$this->output->set_output(json_encode($this->data));
		}else
		{
			$this->output->set_output(json_encode("No Data Available"));
		}
	}

	public function get_swaeroes_alumini_data()
	{
		$this->data = $this->power_of_ten_common_model->get_swaeroes_alumini_data();
		if(!empty($this->data))
		{
			$this->output->set_output(json_encode($this->data));
		}else
		{
			$this->output->set_output(json_encode("No Data Available"));
		}
	}

	public function get_swaeroes_emergency_calls_data()
	{

		$this->data = $this->power_of_ten_common_model->get_swaeroes_emergency_calls_data();
		if(!empty($this->data))
		{
			$this->output->set_output(json_encode($this->data));
		}else
		{
			$this->output->set_output(json_encode("No Data Available"));
		}
	}

	public function get_swaeroes_pending_requests()
	{
		$this->data = $this->power_of_ten_common_model->get_swaeroes_pending_requests();

		if($this->data == "nodata")
		{
			$this->output->set_output(json_encode("No Data Available"));
		}else
		{
			$this->output->set_output(json_encode($this->data));
		}

	}

	public function get_pending_regitrations_details()
	{
		$category = $this->input->post('categoryID', true);
		
		$this->data['pending_requests'] = $this->power_of_ten_common_model->get_pending_regitrations_details($category);
		$this->_render_page('power_of_ten_admins/swaeroes_pending_registrations', $this->data);
	}

	public function swaero_payment_info()
	{
		$this->data = "";
		$this->_render_page('power_of_ten_admins/swaeroes_payment_information', $this->data);
	}

	public function get_searched_payment_data()
	{
		$data = $this->input->post('search_param', true);

		$getData = $this->power_of_ten_common_model->get_searched_payment_data($data);

		if($getData){

			$this->output->set_output(json_encode($getData));
		}else{
			$this->output->set_output(json_encode("No data"));
		}
		
	}

	public function update_payment_details()
	{

		$docID = $this->input->post('doc_id', true);
		$transaction_no = $this->input->post('transaction_no', true);

		if(!empty($docID) && !empty($transaction_no)){

			$data = array(
						"transaction_no" => $this->input->post('transaction_no', true),
						"amount" => intval($this->input->post('amount', true)),
						"payment_date" => $this->input->post('set_date', true)
					);

			$data = $this->power_of_ten_common_model->update_payment_details($docID,$data);

			
			redirect('power_of_ten_mgmt/swaero_payment_info');
			
		}else{

			//return "Check Transaction no missing";

			redirect('power_of_ten_mgmt/swaero_payment_info');
		}
		
		


	} 

	public function district_wise_profession_data()
	{
		$this->data = "";
		$this->_render_page('power_of_ten_admins/district_wise_profession_data', $this->data);
	}

	public function get_swaero_organisations()
	{
		$this->data = "";
		$this->_render_page('power_of_ten_admins/all_swaero_organisations', $this->data);
	}



	public function get_organisation_wise_info()
	{
		$org_name = $this->input->post('org_names',true);

		$this->data = $this->power_of_ten_common_model->get_organisation_wise_info($org_name);
		$this->_render_page('power_of_ten_admins/swaero_organisational_info', $this->data);
	}

	



  //**********************************************************************************//
		
}
