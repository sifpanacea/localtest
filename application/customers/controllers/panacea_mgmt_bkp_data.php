	<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Panacea_mgmt extends My_Controller {

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
		$this->load->library('panacea_common_lib');
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
		redirect('panacea_mgmt/to_dashboard');
		//redirect('panacea_mgmt/basic_dashboard');
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
		/*echo '<pre>';
		echo print_r($docs , true);
		echo '</pre>';
	    exit();
*/
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		$this->data['BMI_report'] = $docs['BMI_report'];
		$this->data['hb_report'] = $docs['hb_report'];
		$this->data['fo_officer_report'] = $docs['fo_report'];
		//$this->data['history'] = $docs['history'];	
				 
		$this->data['docscount'] = count($this->data['docs']);
	
		//$this->_render_page('panacea_admins/panacea_reports_display_ehr_new_dashboard',$this->data);
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
		/*echo print_r($today_date , true);
		echo print_r($request_pie_span , true);
		echo print_r($request_pie_status , true);
		exit();*/
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

}
	//========================================================================
	
	//==================id===========================================================
	
	