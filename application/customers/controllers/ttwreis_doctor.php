<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Ttwreis_doctor extends My_Controller {

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
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
		$this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->library('paas_common_lib');
		$this->load->model('ttwreis_doctor_model');
		$this->load->model('ttwreis_common_model');
		$this->load->model('ttwreis_schools_common_model');
		$this->load->library('ttwreis_schools_common_lib');
		$this->load->library('ttwreis_common_lib');
		$this->load->library('bhashsms');

		
		
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
		//redirect('ttwreis_doctor/to_dashboard');
		redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');
	}
	
	public function initiate_request()
	{
		$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_application_initiate',$this->data);
	}
	
	public function initiate_attendance()
	{
		$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_attendance_initiate',$this->data);
	}
	
	public function initiate_sanitation_report()
	{
		$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_sanitation_report',$this->data);
	}
	
	public function ttwreis_doctor_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_states');
	    
	    $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_states();
		
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_states',$this->data);
	}
	
	public function ttwreis_doctor_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_district');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_district();
		
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_dist',$this->data);
	}
	
	public function ttwreis_doctor_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_health_supervisors');
        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_health_supervisors();
		
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_health_supervisors',$this->data);
	}
	
	
	//////////////////////////////////////////////////////
	
	public function ttwreis_doctor_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_schools();
		
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_schools',$this->data);
	}
	
	//000000000000000000000000000000000000
	public function ttwreis_doctor_classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_classes();
		
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_classes',$this->data);
	}
	
	//sssssssssssssssssssssssssssssssssssssssssss
	public function ttwreis_doctor_sections()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_sections();
		
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_sections',$this->data);
	}
	
	//syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
	public function ttwreis_doctor_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_symptoms');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_symptoms();
		
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_symptoms',$this->data);
	}
	
	public function ttwreis_doctor_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_diagnostics');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_diagnostic();
	
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_diagnostics',$this->data);
	}	
	
	public function ttwreis_doctor_hospitals()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_hospitals');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_hospitals();
	
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_hospitals',$this->data);
	}
	
	public function ttwreis_doctor_emp()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_doctor_emp');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_emp();
	
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_emp',$this->data);
	}
	
	//===============reports======================================
	
	public function ttwreis_reports_ehr()
	{
		$this->data["message"] = "";		
		$this->_render_page('ttwreis_doctor/ttwreis_reports_ehr',$this->data);
	}
	
	public function ttwreis_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_display_ehr($post);
		
	 	$this->_render_page('ttwreis_doctor/ttwreis_reports_display_ehr',$this->data);
	}
	
	public function ttwreis_reports_display_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_display_ehr_uid($post);
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_display_ehr',$this->data);
	}
	
	public function ttwreis_reports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_students');

        $this->data = $this->ttwreis_common_lib->ttwreis_reports_students();
		
		$this->_render_page('ttwreis_doctor/ttwreis_reports_students',$this->data);
	}
	
	public function ttwreis_reports_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_doctors');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_doctors();
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_doctors',$this->data);
	}
	
	public function ttwreis_reports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_hospital');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_hospital();
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_hospitals',$this->data);
	}
	
	public function ttwreis_reports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_school');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_school();
		
		
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_reports_schools',$this->data);
	}
	
	public function ttwreis_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_symptom');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_symptom();
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_symptom',$this->data);
	}
	
	// --------------------------------------------------------------------
	
	function student_db_to_excel(){
		ini_set('memory_limit', '1024M');
		$docs = $this->mongo_db->get("healthcare201671115519757");//healthcare201671115519757
		 
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
		$objPHPExcel->getProperties()->setTitle("Document collection");
		$objPHPExcel->getProperties()->setSubject("Document collection");
		$objPHPExcel->getProperties()->setDescription("Document collection of student health check up.");
	
		// Add some data
		echo date('H:i:s') . " Add some data\n";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student Health Check Up');
	
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
		$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'School Name');
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
				
				$objPHPExcel->getActiveSheet()->SetCellValue('AV2', 'External Attachments');
				$i = 1;
				foreach($doc["doc_data"]["external_attachments"] as $attachment){
					$objPHPExcel->getActiveSheet()->SetCellValue('AV4', 'Attachment_'.$i);
					
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
		$objWriter->save(EXCEL."/document.xlsx");
	
		$this->secure_file_download(EXCEL."/document.xlsx");
	
		unlink(EXCEL."/document.xlsx");
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
		
		$this->data = $this->ttwreis_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		
		// Loggedin user
		$loggedinuser = $this->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		/* $hs_req_docs  = $this->ttwreis_doctor_model->get_hs_req_docs($collection);
		
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		} */
		
		$hs_req_docs  = $this->ttwreis_doctor_model->get_hs_req_docs($collection);

		$hs_req_emergency  = $this->ttwreis_doctor_model->get_hs_req_emergency($collection);

		$hs_req_chronic  = $this->ttwreis_doctor_model->get_hs_req_chronic($collection);
		
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
		
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_dash', $this->data);
	
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
		$this->data = $this->ttwreis_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name);
		
		// Loggedin user
		$loggedinuser = $this->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$hs_req_docs  = $this->ttwreis_doctor_model->get_hs_req_docs($collection);
		
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}
	
		$this->output->set_output($this->data);
	
	}
	
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$this->data = $this->ttwreis_common_lib->update_request_pie($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->ttwreis_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
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

	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->ttwreis_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->ttwreis_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('ttwreis_doctor/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$this->data['docs'] = $this->ttwreis_doctor_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$docs = $this->ttwreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->ttwreis_doctor_model->drill_down_screening_to_students_load_ehr_doc($_id);
	
		$this->data['doc'] = $this->ttwreis_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('ttwreis_doctor/ttwreis_reports_display_ehr',$this->data);
	}
	
	function drilldown_absent_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_absent_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$absent_report = json_encode($this->ttwreis_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
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
		$absent_report = json_encode($this->ttwreis_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
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
		$docs = $this->ttwreis_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		$temp = base64_decode($_GET['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_GET['ehr_data_for_absent']),true);
		$get_docs = $this->ttwreis_common_model->get_drilling_absent_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('ttwreis_doctor/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_request_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_report = json_encode($this->ttwreis_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$request_report = json_encode($this->ttwreis_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$docs = $this->ttwreis_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name);
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request']),true);
		$get_docs = $this->ttwreis_common_model->get_drilling_request_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('ttwreis_doctor/drill_down_request_to_students_load_ehr',$this->data);
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
		$identifiers_report = json_encode($this->ttwreis_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$identifiers_report = json_encode($this->ttwreis_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$docs = $this->ttwreis_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_identifiers']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_identifiers']),true);
		$get_docs = $this->ttwreis_common_model->get_drilling_identifiers_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('ttwreis_doctor/drill_down_identifiers_to_students_load_ehr',$this->data);
	}
	
	//============================================================================================================
		
	function ttwreis_imports_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_diagnostic');
		$this->data = $this->ttwreis_common_lib->ttwreis_imports_diagnostic();
	
		$this->_render_page('ttwreis_doctor/ttwreis_imports_diagnostic', $this->data);
	}
	
	function import_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_diagnostic');
	
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_diagnostic($post);
		
		if($this->data == "redirect_to_diagnostic_fn")
		{
			redirect('ttwreis_doctor/ttwreis_doctor_diagnostic');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_diagnostic', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_diagnostic', $this->data);
		}
		
	}
	
	function ttwreis_imports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_hospital');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_imports_hospital();
	
		$this->_render_page('ttwreis_doctor/ttwreis_imports_hospital', $this->data);
	}
	
	function import_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_hospital');
		
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_hospital($post);
		
		if($this->data == "redirect_to_hospital_fn")
		{
			redirect('ttwreis_doctor/ttwreis_doctor_hospitals');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_hospital', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_hospital', $this->data);
		}
	}
	
	function ttwreis_imports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_school');
	
		$this->data = $this->ttwreis_common_lib->ttwreis_imports_school();
	
		$this->_render_page('ttwreis_doctor/ttwreis_imports_school', $this->data);
	}
	
	function import_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_school($post);
		
		if($this->data == "redirect_to_school_fn")
		{
			redirect('ttwreis_doctor/ttwreis_doctor_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_school', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_school', $this->data);
		}
	}
	
	function ttwreis_imports_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_health_supervisors');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('ttwreis_doctor/ttwreis_imports_health_supervisors', $this->data);
	}
	
	function import_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
	
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_health_supervisors($post);
		
		if($this->data == "redirect_to_hs_fn")
		{
			redirect('ttwreis_doctor/ttwreis_doctor_health_supervisors');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_health_supervisors', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_health_supervisors', $this->data);
		}
	}
	
	function ttwreis_imports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_students');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('ttwreis_doctor/ttwreis_imports_students', $this->data);
	}
	
	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');
		
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_students($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('ttwreis_doctor/ttwreis_reports_students');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_doctor/ttwreis_imports_students', $this->data);
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
			
		//log_message("debug","entered into read_excel form_data".print_r($form_data,true));
	}

	public function ttwreis_reports_students_filter()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_students_filter');
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_students_filter();
	
		//$this->data = "";
		$this->_render_page('ttwreis_doctor/ttwreis_reports_students_filter',$this->data);
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->ttwreis_common_model->get_schools_by_dist_id($dist_id);
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
		$this->_render_page('ttwreis_doctor/ttwreis_pie_export',$this->data);
	}
	
	public function generate_excel_for_absent_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->ttwreis_common_lib->generate_excel_for_absent_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_request_pie()
	{
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->ttwreis_common_lib->generate_excel_for_request_pie($today_date,$request_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function post_note() {
		
		$post = $_POST;
		
		$token = $this->ttwreis_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() {
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->ttwreis_common_lib->delete_ehr_note($doc_id);
	   
		$this->output->set_output($token);
    }

    function list_chronic_cases()
	{
	
	  $total_rows = $this->ttwreis_common_model->ttwreis_chronic_cases_count();

	  //---pagination--------//
	  $config = $this->paas_common_lib->set_paginate_options($total_rows,5);

	  //Initialize the pagination class
	  $this->pagination->initialize($config);

	  //control of number page
	  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

	  //find all the categories with paginate and save it in array to past to the view
	  $this->data['cases_old'] =$this->ttwreis_common_model->get_chronic_cases_model($config['per_page'],$page);

	  //create paginate´s links
	  $this->data['links'] = $this->pagination->create_links();

	  //number page variable
	  $this->data['page'] = $page;
	  
	  $this->data['case_count'] = $total_rows;
		
	  $this->data['message'] = '';
	  
	  $this->data['cases'] =$this->ttwreis_common_model->get_chronic_cases_model_for_data_table();
		
	  $this->_render_page('ttwreis_doctor/chronic_case_report',$this->data);
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
		
	  log_message('debug','ttwreis_SCHOOLS=====CREATE_SCHEDULE_FOLLOWUP=====$_POST==>'.print_r($_POST,true));
	  
	  $is_created = $this->ttwreis_common_model->create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames,$case_id);
	  
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
	  
	  $compliance = $this->ttwreis_common_model->calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken);
	  
	  //log_message('debug','update_schedule_followup==3='.print_r($compliance,true));
	  
	  $is_updated = $this->ttwreis_common_model->update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date);
	  
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
		
	  $pcompl_raw_data   = $this->ttwreis_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
		
	  $pcompl_raw_data   = $this->ttwreis_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	
	public function post_note_request() {
		
		$post = $_POST;
		
		$token = $this->ttwreis_common_lib->insert_request_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function chronic_pie_view(){
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_chronic_pie_view');
		
		$this->data = $this->ttwreis_common_lib->chronic_pie_view();
		
		$this->_render_page('ttwreis_doctor/chronic_pie_view',$this->data);
	}
	
	public function update_chronic_request_pie(){
		
		$this->check_for_admin();
		$this->check_for_plan('update_chronic_request_pie');
		
		$status_type = $_POST["status_type"];
		
		$this->data = $this->ttwreis_common_lib->update_chronic_request_pie($status_type);
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function drill_down_request_to_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_symptoms');
		
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];

		$symptoms_report = json_encode($this->ttwreis_common_model->drill_down_request_to_symptoms($data,$status_type));
		$this->output->set_output($symptoms_report);
	}
	
	function drilldown_chronic_request_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_districts');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$identifiers_report = json_encode($this->ttwreis_common_model->drilldown_chronic_request_to_districts($data,$status_type));
		$this->output->set_output($identifiers_report);
	}
	
	function drilldown_chronic_request_to_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_school');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$request_report = json_encode($this->ttwreis_common_model->drilldown_chronic_request_to_schools($data,$status_type));
		$this->output->set_output($request_report);
	}
	
	function drilldown_chronic_request_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_chronic_request_to_students');
		$data = $_POST['data'];
		$status_type = $_POST['status_type'];
		$docs = $this->ttwreis_common_model->drilldown_chronic_request_to_students($data,$status_type);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
		function drill_down_chronic_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_chronic_request_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->ttwreis_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;
		$navigation = $_POST['ehr_navigation'];
		$this->data['navigation'] = $navigation;
		
		$doc_list = $this->ttwreis_common_model->get_all_doctors();
		////log_message("debug","dddddddddddddddddddddddd===============================".print_r($doc_list,true));
		
		$this->data['doctor_list'] = $doc_list;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('ttwreis_doctor/drill_down_absent_to_students_load_ehr',$this->data);
	}
	
	public function add_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed_view' );
		log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff');
		$this->data ['message'] = "";
		
		$this->_render_page ( 'ttwreis_doctor/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		$news_return = $this->ttwreis_common_lib->add_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_doctor/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->ttwreis_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_doctor/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->ttwreis_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'ttwreis_doctor/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->ttwreis_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_doctor/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->ttwreis_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_doctor/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	
	public function extend_request_view() 
	{
		$this->data['message'] = false;
		
		$this->_render_page('ttwreis_doctor/extend_request_view',$this->data);
	}
	
	public function extend_request()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_model->get_reports_for_extend($post['uid']);
		$this->data['message'] = false;
	
		$this->_render_page('ttwreis_doctor/extend_request',$this->data);
	}
	
	public function app_access($doc_id)
	{

		$this->data = $this->ttwreis_common_lib->app_access($doc_id);
		$this->_render_page('ttwreis_doctor/extend_request_app',$this->data);
	}
	
	function hs_req_extend(){

		$form_data = json_decode($_POST['form_data'],true);
		$req_return = $this->ttwreis_common_lib->hs_req_extend($form_data);

		if($req_return['return_error']){
			$this->data ['message'] = $req_return['message'];
			$this->_render_page('ttwreis_doctor/extend_request_view',$this->data);
		}else{
			redirect('ttwreis_doctor/extend_request_view');
			//$this->manage_news_feed_view();
		}
	}
	
	function doctor_req_extend(){

		$form_data = json_decode($_POST['form_data'],true);
		$req_return = $this->ttwreis_common_lib->doctor_req_extend($form_data);

		if($req_return['return_error']){
			$this->data ['message'] = $req_return['message'];
			$this->_render_page('ttwreis_doctor/ttwreis_doctor_dash',$this->data);
		}else{
			redirect('ttwreis_doctor/to_dashboard');
			//$this->manage_news_feed_view();
		}
	}
	
	public function access_request($id)
	{
		$this->data['doc_id'] = $id;
		$this->_render_page('ttwreis_doctor/ha_req_application_access',$this->data);
	}
	
	public function feed_bmi_student() 
	 {
		$this->data = "";
		$this->data['message'] = "";
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_feed_bmi',$this->data);
	}
	
	public function show_bmi_student() 
	 {
		
		 $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->_render_page('ttwreis_doctor/ttwreis_doctor_show_bmi_graph',$this->data);
	}
	
	public function student_bmi_graph(){
		
		$month_wise_bmi  = array();
		$month_wise_data = array();
		$final_bmi_data  = array();
		$temp 	 		 = array();
		$unique_id = $_POST['uid'];
		
		$bmi_value = $this->ttwreis_common_model->get_student_bmi_values($unique_id);
		
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
	public function ttwreis_update_ehr()
	{
		$this->data["message"] = "";		
		$this->_render_page('ttwreis_doctor/update_personal_info',$this->data);
	}
	
	public function ttwreis_update_personal_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_update_personal_ehr_uid($post);
		
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('ttwreis_doctor/ttwreis_update_personal_ehr',$this->data);
		log_message("debug","ttwreis_update_personal_ehr_uid======1415".print_r($this->data,true));
	}
	
	public function update_student_ehr()
	{
		 // Variables
	  $photo_obj = array();
	 
	  
	  // log_message("debug","student nameeeeee".print_r($_POST,true));
	 
	  // POST DATA
	  $student_name = $this->input->post('name',TRUE);
	  $student_mob  = $this->input->post('mobile',TRUE);
	  $student_dob  = $this->input->post('date_of_birth',TRUE);
	  $father_name  = $this->input->post('father_name',TRUE);
	  $class        = $this->input->post('class',TRUE);
	  $section      = $this->input->post('section',TRUE);
	  $unique_id    = $this->input->post('unique_id',TRUE);
	  log_message("debug","unique id ddddd1434".print_r($unique_id,true));
	  
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
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201671115519757_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201671115519757_con/photo/",0777,TRUE);
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
	  $ehr_update = $this->ttwreis_common_model->update_student_ehr_model($unique_id,$update_profile); 
	  //log_message("debug","photo_updateqwerty".print_r($ehr_update,true));
	   $this->data['message'] = 'Updated Successfully';
	 $this->_render_page('ttwreis_doctor/update_personal_info',$this->data);
	}

	public function fetch_request_docs_from_hs_list()
	{
		
		$collection = "healthcare2016108181933756_static_html";
  		$hs_req_docs  = $this->ttwreis_doctor_model->get_hs_req_normal_new($collection);

		$hs_req_emergency  = $this->ttwreis_doctor_model->get_hs_req_emergency_new($collection);

		$hs_req_chronic  = $this->ttwreis_doctor_model->get_hs_req_chronic_new($collection);
			
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
		
		$this->_render_page('ttwreis_doctor/fetch_request_docs_from_hs_list',$this->data);
	}

	public function submitted_request_docs_doctor()
	{
		$collection = "healthcare2016108181933756_static_html";
  		$hs_req_docs  = $this->ttwreis_doctor_model->get_hs_req_normal_doctor($collection);

		$hs_req_emergency  = $this->ttwreis_doctor_model->get_hs_req_emergency_doctor($collection);

		$hs_req_chronic  = $this->ttwreis_doctor_model->get_hs_req_chronic_doctor($collection);
			
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
		
		$this->_render_page('ttwreis_doctor/submited_request_docs_from_hs_list',$this->data);
	}

	public function access_request_docs_form_hs($id)
	{
		/*$doc_id = $id; 
		
		$query = $this->ttwreis_doctor_model->access_request_docs_form_hs($doc_id);
		//echo print_r($query,true);exit();
		$this->data['hs_req_docs'] = $query;
		//$this->data['student_photo'] = $query['student_photo'];
		$this->_render_page('ttwreis_doctor/access_request_docs_form_hs',$this->data);*/
		$session_data = $this->session->userdata("customer");
		$doc_id = $id;
  		$doc_access = "true";
  		$doc_access_by = $session_data['user_type']." ".$session_data['username'];
  		
		$query = $this->ttwreis_doctor_model->access_request_docs_form_hs($doc_id,$doc_access,$doc_access_by);

		if(isset($query['access_by']) && !empty($query['access_by']))
		{
			$this->session->set_flashdata('access_by',"Accessed By : ".$query['access_by']['access_by'].", Please wait for HS to Update Request or reopen after 5 mins");
				redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');
			//$this->_render_page('tswreis_schools/access_submited_request_docs',$this->data);
		}else
		{
			$this->data['hs_req_docs'] = [$query];		
			if($this->data['hs_req_docs'][0] == "No Documents Found")
			{
				log_message('error','doc_id============2011'.print_r($doc_id,TRUE));
			}else
			{ 
				$this->_render_page('ttwreis_doctor/access_request_docs_form_hs',$this->data);
			}
			
		}
	}

	/*public function doctor_submit_request_docs()
  	{

  		$doc_id = $this->input->post('doc_id',true);
  		$unique_id = $this->input->post('unique_id',TRUE);
		$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
		$district = $this->input->post('page1_StudentInfo_District',TRUE);
		$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
		$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
		$section  = $this->input->post('page1_StudentInfo_Section',TRUE);
		

  		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
		$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
		$normal_eyes_identifier      = $this->input->post('normal_eye_identifier',TRUE);
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

		
		//$doc_data = array();
		//$doc_data['page1']['Student Info'] = array();
	  // Page 1
		$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
		$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = $student_name;
		$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
		$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
		$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
		$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
		//$doc_data['page1']['Problem Info']    = $data_to_store;
		$doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = !empty($doctor_summary) ? $doctor_summary : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = !empty($doctor_advice) ? $doctor_advice : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = !empty($prescription) ? $prescription : "";



		$doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
		$doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;
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
		//log_message('error','FILESssssssssssssssssssssssssssssss'.print_r($_POST,TRUE));

		// Attachments
		 if(isset($_FILES) && !empty($_FILES))
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
			        $controller = 'healthcare2016108181933756_con';
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
						 log_message('error','FILESsssssssssssssssss======2166');
						redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');
							
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
						 log_message('error','FILESsssssssssssssssss======2166'.print_r($external_data_array,TRUE));
						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
			$doc_history = $this->ttwreis_doctor_model->get_history($unique_id,$doc_id);

				

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

		$doc_history = $this->ttwreis_doctor_model->get_history($unique_id,$doc_id);

		// school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->ttwreis_schools_common_model->get_school_information_for_school_code($schoolCode);


		
		 $health_supervisor = $this->ttwreis_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];
		 

		 //log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
		
		$doc_data['user_name'] = $doc_history[0]['history'][0]['submitted_by'];

		
		$history_array = array();
		
		$session_data = $this->session->userdata("customer");

	 	//POST DATA
		$redirected_stage   = "HS 2";
		$current_stage      = "Doctor";
		//$doc_id 			= $form_data['docid'];
		//$reason             = implode(", ",$reason_array);
		//$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		//$disapproving_user	= $username;
		$stage_name 		= "Doctor";

		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
		$doc_properties['doc_access'] = "false";
		$doc_properties['access_by'] = "";
		$doc_properties['doc_access_time'] = 0;

                 $approval_data = array(
                 	"current_stage" => "Doctor",
                 	"approval" => "true",
                 	"submitted_by" => $session_data['email'],
                 	"submitted_by_name" => $session_data['username'],
                 	"time" => date('Y-m-d H:i:s'));

                 $doctor_type = $session_data['user_type'];

                 $approval_history = $this->ttwreis_common_model->get_approval_history($doc_id);
                 array_push($approval_history,$approval_data);
                 log_message('error','approval_history======2281'.print_r($approval_history,TRUE));

  	  $existing_update = $this->ttwreis_doctor_model->request_docs_update_doctor_model($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties); 

  	  if($existing_update)
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
  	    if($request_type == "Chronic")
  	    {
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

		  	$message = "Dr Response : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type.", Issues:".$total_diseaes."\n **Create Schedule for Chronic Case**";
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
						goto fcm_notification;	
  	    	}
  	    }
  	  //	log_message('error', "Issues list: issueinfo_1".print_r($issueinfo_1,TRUE));
  	  	$message = "Dr Response : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type.", Issues:".$total_diseaes;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
			fcm_notification:		
  	  	//$fcm_notification = $this->ttwreis_common_lib->fcm_message_notification_doctor_update($request_type,$unique_id,$student_name);
  	  	$this->session->set_flashdata('success','Request Updated successfully !!');
  	  	redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');
  	  }
  	  else
  	  {
  	  	$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
  	  	redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');

  	  }
  	}*/

  	public function doctor_submit_request_docs()
  	{
  		$doc_id = $this->input->post('doc_id',true);
  		$unique_id = $this->input->post('unique_id',TRUE);
		$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
		$district = $this->input->post('page1_StudentInfo_District',TRUE);
		$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
		$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
		$section  = $this->input->post('page1_StudentInfo_Section',TRUE);
		$scheduled_date = $this->input->post('scheduled_date', TRUE);
		$date = new DateTime($scheduled_date);
		$scheduled_date= $date->format('Y-m-d');
		$add_to_regular_followup = $this->input->post('add_to_regular_followup', TRUE);

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
		
		/*echo print_r($school_name,true);
		exit;*/
  		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
		$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
		$normal_eyes_identifier      = $this->input->post('normal_eye_identifier',TRUE);
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

		log_message('error','unique_id=======2091'.print_r($unique_id,TRUE));
		log_message('error','doc_id=======20922'.print_r($doc_id,TRUE));
		log_message('error','doctor_summary=======2091'.print_r($doctor_summary,TRUE));
		log_message('error','doctor_advice===========2092'.print_r($doctor_advice,TRUE));
		log_message('error','prescription======2093'.print_r($prescription,TRUE));
		//$doc_data = array();
		//$doc_data['page1']['Student Info'] = array();
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
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = !empty($doctor_summary) ? $doctor_summary : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = !empty($doctor_advice) ? $doctor_advice : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = !empty($prescription) ? $prescription : "";



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
		$doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;
		}
		if(isset($chronic_identifiers) && !empty($chronic_identifiers))
		{
		//$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
		$doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
		}
		//log_message('error','FILESssssssssssssssssssssssssssssss'.print_r($_POST,TRUE));
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
			        $controller = 'healthcare2016108181933756_con';
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
						 log_message('error','FILESsssssssssssssssss======2166');
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
						 log_message('error','FILESsssssssssssssssss======2166'.print_r($external_data_array,TRUE));
						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
			$doc_history = $this->ttwreis_doctor_model->get_history($unique_id,$doc_id);

				

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
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2016108181933756_con',$index));

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
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare2016108181933756_con',$index));

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
            
              		$this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare2016108181933756_con',$index));

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
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare2016108181933756_con',$index));

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
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare2016108181933756_con',$index));

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
                 
                $this->upload->initialize($this->external_attachments_upload_options('healthcare2016108181933756_con',$index));

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

       $doc_history = $this->ttwreis_doctor_model->get_history($unique_id,$doc_id);

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
               
                $lab_reports_merged_data = array_merge($doc_history[0]['doc_data']['Lab_Reports'],$Lab_Reports_external_final);
                $doc_history[0]['doc_data']['Lab_Reports'] = array_replace_recursive($doc_history[0]['doc_data']['Lab_Reports'],$lab_reports_merged_data);
                
                
            
                
        }
        else
        {
                $doc_data['Lab_Reports'] = $Lab_Reports_external_final;
                
        } 
        
        
        
        if(isset($doc_history[0]['doc_data']['Digital_Images']))
        {
                $digital_images_merged_data = array_merge($doc_history[0]['doc_data']['Digital_Images'],$Digital_external_final);
                $doc_history[0]['doc_data']['Digital_Images'] = array_replace_recursive($doc_history[0]['doc_data']['Digital_Images'],$digital_images_merged_data); 
               
        }
        else
        {
                $doc_data['Digital_Images'] = $Digital_external_final;
        }
        
        if(isset($doc_history[0]['doc_data']['Payments_Bills']))
        {
                $kitchen_merged_data = array_merge($doc_history[0]['doc_data']['Payments_Bills'],$Payments_Bills_external_final);
                $doc_history[0]['doc_data']['Payments_Bills'] = array_replace_recursive($doc_history[0]['doc_data']['Payments_Bills'],$kitchen_merged_data);
        }
        else
        {
                $doc_data['Payments_Bills'] = $Payments_Bills_external_final;
        }

         if(isset($doc_history[0]['doc_data']['Discharge_Summary']))
        {

                $dormitory_merged_data = array_merge($doc_history[0]['doc_data']['Discharge_Summary'],$Discharge_Summary_external_final);
                $doc_history[0]['doc_data']['Discharge_Summary'] = array_replace_recursive($doc_history[0]['doc_data']['Discharge_Summary'],$dormitory_merged_data);
        }
        else
        {
                $doc_data['Discharge_Summary'] = $Discharge_Summary_external_final;
        }

         if(isset($doc_history[0]['doc_data']['external_attachments']))
        {

                $external_merged_data = array_merge($doc_history[0]['doc_data']['external_attachments'],$external_final);
                $doc_history[0]['doc_data']['external_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['external_attachments'],$external_merged_data);
        }
        else
        {
                $doc_data['external_attachments'] = $external_final;
        }


    }

		$doc_history = $this->ttwreis_doctor_model->get_history($unique_id,$doc_id);

		// school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->tswreis_schools_common_model->get_school_information_for_school_code($schoolCode);


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
		if(isset($_FILES) && !empty($_FILES))
    	{
        $this->load->library('upload');
        $this->load->library('image_lib');

       
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
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2016108181933756_con',$index));

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
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare2016108181933756_con',$index));

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
                     
                
		
			       $this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare2016108181933756_con',$index));
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
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare2016108181933756_con',$index));
               
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
            
                  $this->upload->initialize($this->external_attachments_upload_options('healthcare2016108181933756_con',$index));

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
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare2016108181933756_con',$index));

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

       $doc_history = $this->ttwreis_schools_common_model->get_history($unique_id,$doc_id);
      
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
	    $school_data_array = explode("_",$unique_id);
		$schoolCode        = (int) $school_data_array[1];
		$health_supervisor = $this->ttwreis_schools_common_model->get_health_supervisor_details($schoolCode);
		$hs_name = $health_supervisor['hs_name'];
		$hs_mob  = $health_supervisor['hs_mob'];
		$school_data = $this->ttwreis_schools_common_model->get_school_information_for_school_code($schoolCode);

		 //log_message('debug','healthcare2016108181933756_con=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2016108181933756_con=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
		
		$doc_data['user_name'] = $doc_history[0]['history'][0]['submitted_by'];

		
		$history_array = array();
		
		$session_data = $this->session->userdata("customer");

	 	//POST DATA
		$redirected_stage   = "HS 2";
		$current_stage      = "Doctor";
		//$doc_id 			= $form_data['docid'];
		//$reason             = implode(", ",$reason_array);
		//$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		//$disapproving_user	= $username;
		$stage_name 		= "Doctor";
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "TTWREIS";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
		$doc_properties['doc_access'] = "false";
		$doc_properties['access_by'] = "";
		$doc_properties['doc_access_time'] = 0;

                 $approval_data = array(
                 	"current_stage" => "Doctor",
                 	"approval" => "true",
                 	"submitted_by" => $session_data['email'],
                 	"submitted_by_name" => $session_data['username'],
                 	"time" => date('Y-m-d H:i:s'));

                 $doctor_type = $session_data['user_type'];

                 $approval_history = $this->ttwreis_common_model->get_approval_history($doc_id);
                 array_push($approval_history,$approval_data);
                 log_message('error','approval_history======2281'.print_r($approval_history,TRUE));

      /* if($add_to_regular_followup == "add_to_regular_followup")
       {
       		$symptoms = array();
       		
	       	 if($normal_identifiers)
			{
				foreach ($normal_identifiers as $identifier => $values) {
					foreach ($values as  $value) {

						array_push($symptoms,$value);
					}
				}

			}	
			
			if($emergency_identifiers)
			{
				foreach ($emergency_identifiers as $identifier => $values) {
					foreach ($values as  $value) {

						array_push($symptoms,$value);
					}
				}
			}
			if($chronic_identifiers)
			{
				foreach ($chronic_identifiers as $identifier => $values) {
					foreach ($values as  $value) {

						array_push($symptoms,$value);
					}
				}
			}

       		$this->ttwreis_doctor_model->create_regular_followup_request($unique_id,$student_name,$class,$request_type,$symptoms,$problem_info_description,$school_name,$district, $scheduled_date, $review_status);
       }*/

       if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
      {
        

        $check_doc_id = $this->ttwreis_doctor_model->check_doc_id_of_request($doc_id);
        
        if($check_doc_id == 'No Doc Found'){
            $insert_hospitalised = $this->ttwreis_doctor_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
        }
      } 


  	  $existing_update = $this->ttwreis_doctor_model->request_docs_update_doctor_model($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties); 

  	  if($existing_update)
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
  	    if($request_type == "Chronic")
  	    {
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

		  /*	$message = "Dr Response : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type.", Issues:".$total_diseaes."\n **Create Schedule for Chronic Case**";
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);
						goto fcm_notification;	*/
  	    	}
  	    }
  	  //	log_message('error', "Issues list: issueinfo_1".print_r($issueinfo_1,TRUE));
  	  	//$message = "Dr Response : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type.", Issues:".$total_diseaes;
						//$sms =  $this->bhashsms->send_sms($hs_mob,$message);
			//fcm_notification:		
  	  	//$fcm_notification = $this->panacea_common_lib->fcm_message_notification_doctor_update($request_type,$unique_id,$student_name);
  	  	$this->session->set_flashdata('success','Request Updated successfully !!');
  	  	redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');
  	  }
  	  else
  	  {
  	  	$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
  	  	redirect('ttwreis_doctor/fetch_request_docs_from_hs_list');

  	  }
  	}
  	private function Prescriptions_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Prescriptions')!== false)
		{
			$controller = 'healthcare2016108181933756_con';
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
			$controller = 'healthcare2016108181933756_con';
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
			$controller = 'healthcare2016108181933756_con';
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
			$controller = 'healthcare2016108181933756_con';
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
			$controller = 'healthcare2016108181933756_con';
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
			$controller = 'healthcare2016108181933756_con';
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
  	public function get_my_school_code()
	{
	    $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		return $school_code;
	}
  	

	public function reports_display_ehr_uid_new_html_static_hs()
	{
		$student_unique_id = $_POST['student_unique_id'];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->ttwreis_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$logged_in_user = $this->session->userdata("customer");
		$username       = $logged_in_user['username'];

        $this->data = $this->ttwreis_schools_common_lib->reports_display_ehr_uid_new_html_static_hs($student_unique_id,$school_name);
		$this->data["username"] = $username.','.$school_name;
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		
		$this->_render_page('ttwreis_doctor/ttwreis_reports_display_ehr_new_dashboard',$this->data);
	}

		
}
