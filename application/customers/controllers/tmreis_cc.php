<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Tmreis_cc extends My_Controller {

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
		$this->load->model('tmreis_cc_model');
		$this->load->model('tmreis_common_model');
		$this->load->library('tmreis_common_lib');
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
		redirect('tmreis_cc/to_dashboard');
	}
	
	public function initiate_request()
	{
		$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_application_initiate',$this->data);
	}
	
	public function initiate_attendance()
	{
		$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_attendance_initiate',$this->data);
	}
	
	public function initiate_sanitation_report()
	{
		$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_sanitation_report',$this->data);
	}
	
	public function tmreis_cc_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_states');
	    
	    $this->data = $this->tmreis_common_lib->tmreis_mgmt_states();
		
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_states',$this->data);
	}
	
	public function tmreis_cc_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_district');

        $this->data = $this->tmreis_common_lib->tmreis_mgmt_district();
		
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_dist',$this->data);
	}
	
	public function tmreis_cc_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_health_supervisors');
        $this->data = $this->tmreis_common_lib->tmreis_mgmt_health_supervisors();
		
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_health_supervisors',$this->data);
	}
	
	
	//////////////////////////////////////////////////////
	
	public function tmreis_cc_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_schools');

        $this->data = $this->tmreis_common_lib->tmreis_mgmt_schools();
		
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_schools',$this->data);
	}
	
	//000000000000000000000000000000000000
	public function tmreis_cc_classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_schools');

        $this->data = $this->tmreis_common_lib->tmreis_mgmt_classes();
		
		$this->_render_page('tmreis_cc/tmreis_cc_classes',$this->data);
	}
	
	//sssssssssssssssssssssssssssssssssssssssssss
	public function tmreis_cc_sections()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_schools');

        $this->data = $this->tmreis_common_lib->tmreis_mgmt_sections();
		
		$this->_render_page('tmreis_cc/tmreis_cc_sections',$this->data);
	}
	
	//syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
	public function tmreis_cc_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_symptoms');

        $this->data = $this->tmreis_common_lib->tmreis_mgmt_symptoms();
		
		$this->_render_page('tmreis_cc/tmreis_cc_symptoms',$this->data);
	}
	
	public function tmreis_cc_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_diagnostics');
	
		$this->data = $this->tmreis_common_lib->tmreis_mgmt_diagnostic();
	
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_diagnostics',$this->data);
	}	
	
	public function tmreis_cc_hospitals()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_hospitals');
	
		$this->data = $this->tmreis_common_lib->tmreis_mgmt_hospitals();
	
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_hospitals',$this->data);
	}
	
	public function tmreis_cc_emp()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_cc_emp');
	
		$this->data = $this->tmreis_common_lib->tmreis_mgmt_emp();
	
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_cc_emp',$this->data);
	}
	
	//===============reports======================================
	
	public function tmreis_reports_ehr()
	{
		$this->data["message"] = "";		
		$this->_render_page('tmreis_cc/tmreis_reports_ehr',$this->data);
	}
	
	public function tmreis_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->tmreis_reports_display_ehr($post);
		
	 	$this->_render_page('tmreis_cc/tmreis_reports_display_ehr',$this->data);
	}
	
	public function tmreis_reports_display_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->tmreis_reports_display_ehr_uid($post);
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
	
		$this->_render_page('tmreis_cc/tmreis_reports_display_ehr',$this->data);
	}
	
	public function tmreis_reports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_students');

        $this->data = $this->tmreis_common_lib->tmreis_reports_students();
		
		$this->_render_page('tmreis_cc/tmreis_reports_students',$this->data);
	}
	
	public function tmreis_reports_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_doctors');
	
		$this->data = $this->tmreis_common_lib->tmreis_reports_doctors();
	
		$this->_render_page('tmreis_cc/tmreis_reports_doctors',$this->data);
	}
	
	public function tmreis_reports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_hospital');
	
		$this->data = $this->tmreis_common_lib->tmreis_reports_hospital();
	
		$this->_render_page('tmreis_cc/tmreis_reports_hospitals',$this->data);
	}
	
	public function tmreis_reports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_school');
	
		$this->data = $this->tmreis_common_lib->tmreis_reports_school();
		
		
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_reports_schools',$this->data);
	}
	
	public function tmreis_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_symptom');
	
		$this->data = $this->tmreis_common_lib->tmreis_reports_symptom();
	
		$this->_render_page('tmreis_cc/tmreis_reports_symptom',$this->data);
	}
	
	// --------------------------------------------------------------------
	
	function student_db_to_excel(){
		ini_set('memory_limit', '1024M');
		$docs = $this->mongo_db->get("healthcare2016226112942701");//healthcare2016226112942701
		 
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
		
		$this->data = $this->tmreis_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		
				// Loggedin user
		$loggedinuser = $this->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_web_docs';
		
		$hs_req_docs  = $this->tmreis_cc_model->get_hs_req_docs($collection);
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}
		
		$this->_render_page('tmreis_cc/tmreis_cc_dash', $this->data);
	
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
		$this->data = $this->tmreis_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_request_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_request_pie');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$this->data = $this->tmreis_common_lib->update_request_pie($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$this->check_for_admin();
		$this->check_for_plan('update_screening_pie');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->tmreis_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$this->check_for_admin();
		$this->check_for_plan('refresh_screening_data');
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->tmreis_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->tmreis_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}

	
	function drilling_screening_to_abnormalities()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilling_pie');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->tmreis_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_screening_to_students_load_ehr');
		
		$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		
		$get_docs = $this->tmreis_common_model->get_drilling_screenings_students_docs($docs_id);
		
		$this->data['students'] = $get_docs;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->_render_page('tmreis_cc/drill_down_screening_to_students_load_ehr',$this->data);
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$this->data['docs'] = $this->tmreis_cc_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$docs = $this->tmreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('tmreis_cc/tmreis_reports_display_ehr',$this->data);
	}
	
	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->tmreis_cc_model->drill_down_screening_to_students_load_ehr_doc($_id);
	
		$this->data['doc'] = $this->tmreis_common_model->drill_down_screening_to_students_doc($_id);
	
		$this->_render_page('tmreis_cc/tmreis_reports_display_ehr',$this->data);
	}
	
	function drilldown_absent_to_districts()
	{
		$this->check_for_admin();
		$this->check_for_plan('drilldown_absent_to_districts');
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		////log_message("debug","ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp".print_r($_POST,true));
		$absent_report = json_encode($this->tmreis_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
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
		$absent_report = json_encode($this->tmreis_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
		//log_message("debug","ssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($absent_report,true));
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
		$docs = $this->tmreis_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_absent']),true);
		$get_docs = $this->tmreis_common_model->get_drilling_absent_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('tmreis_cc/drill_down_absent_to_students_load_ehr',$this->data);
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
		$request_report = json_encode($this->tmreis_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
		////log_message("debug","innnnnnnnnnnnnnnnnnnnnnnnpie ppppppppppppppppppppppppppppppppppppppppp".print_r($screening_report,true));
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
		$request_report = json_encode($this->tmreis_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$docs = $this->tmreis_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name);
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_request_to_students_load_ehr');
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_request']),true);
		$get_docs = $this->tmreis_common_model->get_drilling_request_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('tmreis_cc/drill_down_request_to_students_load_ehr',$this->data);
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
		$identifiers_report = json_encode($this->tmreis_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$identifiers_report = json_encode($this->tmreis_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name));
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
		$docs = $this->tmreis_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_identifiers_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_identifiers']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_identifiers']),true);
		$get_docs = $this->tmreis_common_model->get_drilling_identifiers_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->_render_page('tmreis_cc/drill_down_identifiers_to_students_load_ehr',$this->data);
	}
	
	//============================================================================================================
		
	function tmreis_imports_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_imports_diagnostic');
		$this->data = $this->tmreis_common_lib->tmreis_imports_diagnostic();
	
		$this->_render_page('tmreis_cc/tmreis_imports_diagnostic', $this->data);
	}
	
	function import_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_diagnostic');
	
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->import_diagnostic($post);
		
		if($this->data == "redirect_to_diagnostic_fn")
		{
			redirect('tmreis_cc/tmreis_cc_diagnostic');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_diagnostic', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_diagnostic', $this->data);
		}
		
	}
	
	function tmreis_imports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_imports_hospital');
	
		$this->data = $this->tmreis_common_lib->tmreis_imports_hospital();
	
		$this->_render_page('tmreis_cc/tmreis_imports_hospital', $this->data);
	}
	
	function import_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_hospital');
		
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->import_hospital($post);
		
		if($this->data == "redirect_to_hospital_fn")
		{
			redirect('tmreis_cc/tmreis_cc_hospitals');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_hospital', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_hospital', $this->data);
		}
	}
	
	function tmreis_imports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_imports_school');
	
		$this->data = $this->tmreis_common_lib->tmreis_imports_school();
	
		$this->_render_page('tmreis_cc/tmreis_imports_school', $this->data);
	}
	
	function import_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->import_school($post);
		
		if($this->data == "redirect_to_school_fn")
		{
			redirect('tmreis_cc/tmreis_cc_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_school', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_school', $this->data);
		}
	}
	
	function tmreis_imports_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_imports_health_supervisors');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('tmreis_cc/tmreis_imports_health_supervisors', $this->data);
	}
	
	function import_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
	
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->import_health_supervisors($post);
		
		if($this->data == "redirect_to_hs_fn")
		{
			redirect('tmreis_cc/tmreis_cc_health_supervisors');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_health_supervisors', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_health_supervisors', $this->data);
		}
	}
	
	function tmreis_imports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_imports_students');
	
		$this->data['message'] = FALSE;
	
		$this->_render_page('tmreis_cc/tmreis_imports_students', $this->data);
	}
	
	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');
		
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->import_students($post);
		
		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('tmreis_cc/tmreis_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('tmreis_cc/tmreis_imports_students', $this->data);
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
			$doc_properties['doc_owner'] = "tmreis";
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

	public function tmreis_reports_students_filter()
	{
		$this->check_for_admin();
		$this->check_for_plan('tmreis_reports_students_filter');
		$this->data = $this->tmreis_common_lib->tmreis_reports_students_filter();
	
		//$this->data = "";
		$this->_render_page('tmreis_cc/tmreis_reports_students_filter',$this->data);
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->tmreis_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_students_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_students_list');
	
		$school_name = $_POST['school_name'];
		$dist_name = $_POST['dist_name'];
	
		$this->data = $this->tmreis_common_model->get_students_by_school_name($school_name,$dist_name);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}

	public function pie_export()
	{
		$this->data['today_date'] = date('Y-m-d');
		$this->data['distslist'] = $this->tmreis_common_model->get_all_district();
		$this->_render_page('tmreis_cc/tmreis_pie_export',$this->data);
	}
	
	public function generate_excel_for_absent_pie()
	{
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->tmreis_common_lib->generate_excel_for_absent_pie($today_date,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function generate_excel_for_request_pie()
	{
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$file_path = $this->tmreis_common_lib->generate_excel_for_request_pie($today_date,$request_pie_span,$dt_name,$school_name);
		$this->output->set_output($file_path);
	}
	
	public function post_note() {
		
		$post = $_POST;
		
		$token = $this->tmreis_common_lib->insert_ehr_note($post);
	   
		$this->output->set_output($token);
	}
	
	public function delete_note() {
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->tmreis_common_lib->delete_ehr_note($doc_id);
	   
		$this->output->set_output($token);
	}
	
	function list_chronic_cases()
	{
	
	  $total_rows = $this->tmreis_common_model->tmreis_chronic_cases_count();

	  //---pagination--------//
	  $config = $this->paas_common_lib->set_paginate_options($total_rows,5);

	  //Initialize the pagination class
	  $this->pagination->initialize($config);

	  //control of number page
	  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

	  //find all the categories with paginate and save it in array to past to the view
	 /* $this->data['cases'] =$this->tmreis_common_model->get_chronic_cases_model($config['per_page'],$page);*/

	  //create paginate´s links
	  $this->data['links'] = $this->pagination->create_links();

	  //number page variable
	  $this->data['page'] = $page;
	  
	  $this->data['case_count'] = $total_rows;
		
	  $this->data['message'] = '';
	  
	  $this->data['cases'] =$this->tmreis_common_model->get_chronic_cases_model_for_data_table();
		
	  $this->_render_page('tmreis_cc/chronic_case_report',$this->data);
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
		
	  log_message('debug','TSWREIS_SCHOOLS=====CREATE_SCHEDULE_FOLLOWUP=====$_POST==>'.print_r($_POST,true));
	  
	  $is_created = $this->tmreis_common_model->create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames,$case_id);
	  
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
	  
	  $compliance = $this->tmreis_common_model->calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken);
	  
	  //log_message('debug','update_schedule_followup==3='.print_r($compliance,true));
	  
	  $is_updated = $this->tmreis_common_model->update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date);
	  
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
		
	  $pcompl_raw_data   = $this->tmreis_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
		
	  $pcompl_raw_data   = $this->tmreis_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
	
	public function add_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed_view' );
		log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff');
		$this->data ['message'] = "";
		
		$this->_render_page ( 'tmreis_cc/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		$news_return = $this->tmreis_common_lib->add_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'tmreis_cc/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->tmreis_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'tmreis_cc/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->tmreis_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'tmreis_cc/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->tmreis_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'tmreis_cc/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->tmreis_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'tmreis_cc/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	
	public function post_note_request() {
		
		$post = $_POST;
		
		$token = $this->tmreis_common_lib->insert_request_note($post);
	   
		$this->output->set_output($token);
	}
	
	/*
	*BMI App
	*author Naresh view page
	
	*/ 
	public function feed_bmi_student() 
	{
		$this->data = "";
		//$this->data['message'] = $this->session->flashdata('message');
		$this->_render_page('tmreis_cc/tmreis_cc_feed_bmi',$this->data);
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
		$this->_render_page('tmreis_cc/tmreis_cc_show_bmi_graph',$this->data);
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
		
		$bmi_value = $this->tmreis_common_model->get_student_bmi_values($unique_id);
		
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
	
	public function extend_request_view() 
	{
		$this->data['message'] = false;
		
		$this->_render_page('tmreis_cc/extend_request_view',$this->data);
	}
	
	public function extend_request()
	{
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->tmreis_reports_display_ehr_uid($post);
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		$this->data['message'] = false;
	
		$this->_render_page('tmreis_cc/extend_request',$this->data);
	}
	
	public function app_access($doc_id)
	{

		$this->data = $this->tmreis_common_lib->app_access($doc_id);
		$this->_render_page('tmreis_cc/extend_request_app',$this->data);
	}
	
	function hs_req_extend(){

		$form_data = json_decode($_POST['form_data'],true);
		$req_return = $this->tmreis_common_lib->hs_req_extend($form_data);

		if($req_return['return_error']){
			$this->data ['message'] = $req_return['message'];
			$this->_render_page('tmreis_cc/extend_request_view',$this->data);
		}else{
			redirect('tmreis_cc/extend_request_view');
			//$this->manage_news_feed_view();
		}
	}
	
	public function access_request($id)
	{
		$this->data['doc_id'] = $id;
		$this->_render_page('tmreis_cc/ha_req_application_access',$this->data);
	}
	
	//Update Personal Information
	//author Naresh
	public function tmreis_update_ehr()
	{
		$this->data["message"] = "";		
		$this->_render_page('tmreis_cc/update_personal_info',$this->data);
	}
	
	public function tmreis_update_personal_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->tmreis_common_lib->tmreis_update_personal_ehr_uid($post);
		
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('tmreis_cc/tmreis_update_personal_ehr',$this->data);
		log_message("debug","panacea_update_personal_ehr_uid======1415".print_r($this->data,true));
	}
	
	public function update_student_ehr()
	{
		 // Variables
	  $photo_obj = array();
	 
	  
	   log_message("debug","student nameeeeee".print_r($_POST,true));
	 
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
		   
	       $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201672020159570_con/photo/';
		   $config['allowed_types'] 	= '*';
		   $config['min_size'] 		    = '1024';
		   $config['max_size'] 		    = '5120';
		   $config['encrypt_name']		= TRUE;
		   
           //create controller upload folder if not exists
		   if (!is_dir($config['upload_path']))
		   {
			  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201672020159570_con/photo/",0777,TRUE);
		   }
		   
		   // Student Photo
		   foreach ($_FILES as $index => $value)
		   {
			   log_message('debug',"index indexx=========1852".print_r($index,true));
			   log_message('debug',"values value==========1853".print_r($value,true));
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
		   log_message("debug","photoooooooooo".print_r($photo_ele,true));
		   
		   $update_profile['doc_data.widget_data.page1.Personal Information.Photo']= $photo_ele;
			 
		   }
		   
		   
		  // $doc_data['doc_data']['widget_data']['page1']['Personal Information']['Photo'] = $photo_ele;
	  }
	  }
	  
	  log_message("debug","doc_datavvvvvvvv1496".print_r($update_profile,true));
	  $ehr_update = $this->tmreis_common_model->update_student_ehr_model($unique_id,$update_profile); 
	  log_message("debug","photo_updateqwerty".print_r($ehr_update,true));
	   $this->data['message'] = 'Updated Successfully';
	 $this->_render_page('tmreis_cc/update_personal_info',$this->data);
	}

		
}