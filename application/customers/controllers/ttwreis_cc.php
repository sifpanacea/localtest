<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Ttwreis_cc extends My_Controller {

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
		$this->load->model('ttwreis_cc_model');
		$this->load->model('ttwreis_common_model');
		$this->load->model('ttwreis_schools_common_model');
		$this->load->library('ttwreis_common_lib');
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
		redirect('ttwreis_cc/to_dashboard');
	}

	public function initiate_request()
	{
		$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_application_initiate',$this->data);
	}

	public function initiate_attendance()
	{
		$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_attendance_initiate',$this->data);
	}

	public function initiate_sanitation_report()
	{
		$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_sanitation_report',$this->data);
	}

	public function ttwreis_cc_states()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_states');

	    $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_states();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_states',$this->data);
	}

	public function ttwreis_cc_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_district');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_district();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_dist',$this->data);
	}

	public function ttwreis_cc_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_health_supervisors');
        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_health_supervisors();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_health_supervisors',$this->data);
	}


	//////////////////////////////////////////////////////

	public function ttwreis_cc_schools()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_schools();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_schools',$this->data);
	}

	//000000000000000000000000000000000000
	public function ttwreis_cc_classes()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_classes();

		$this->_render_page('ttwreis_cc/ttwreis_cc_classes',$this->data);
	}

	//sssssssssssssssssssssssssssssssssssssssssss
	public function ttwreis_cc_sections()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_schools');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_sections();

		$this->_render_page('ttwreis_cc/ttwreis_cc_sections',$this->data);
	}

	//syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
	public function ttwreis_cc_symptoms()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_symptoms');

        $this->data = $this->ttwreis_common_lib->ttwreis_mgmt_symptoms();

		$this->_render_page('ttwreis_cc/ttwreis_cc_symptoms',$this->data);
	}

	public function ttwreis_cc_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_diagnostics');

		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_diagnostic();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_diagnostics',$this->data);
	}

	public function ttwreis_cc_hospitals()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_hospitals');

		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_hospitals();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_hospitals',$this->data);
	}

	public function ttwreis_cc_emp()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_cc_emp');

		$this->data = $this->ttwreis_common_lib->ttwreis_mgmt_emp();

		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_emp',$this->data);
	}

	//===============reports======================================

	public function ttwreis_reports_ehr()
	{
		$this->data["message"] = "";
		$this->_render_page('ttwreis_cc/ttwreis_reports_ehr',$this->data);
	}

	public function ttwreis_reports_display_ehr()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_display_ehr($post);

	 	$this->_render_page('ttwreis_cc/ttwreis_reports_display_ehr',$this->data);
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

		$this->_render_page('ttwreis_cc/ttwreis_reports_display_ehr',$this->data);
	}

	public function ttwreis_reports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_students');

        $this->data = $this->ttwreis_common_lib->ttwreis_reports_students();

		$this->_render_page('ttwreis_cc/ttwreis_reports_students',$this->data);
	}

	public function ttwreis_reports_doctors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_doctors');

		$this->data = $this->ttwreis_common_lib->ttwreis_reports_doctors();

		$this->_render_page('ttwreis_cc/ttwreis_reports_doctors',$this->data);
	}

	public function ttwreis_reports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_hospital');

		$this->data = $this->ttwreis_common_lib->ttwreis_reports_hospital();

		$this->_render_page('ttwreis_cc/ttwreis_reports_hospitals',$this->data);
	}

	public function ttwreis_reports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_school');

		$this->data = $this->ttwreis_common_lib->ttwreis_reports_school();


		//$this->data = "";
		$this->_render_page('ttwreis_cc/ttwreis_reports_schools',$this->data);
	}

	public function ttwreis_reports_symptom()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_reports_symptom');

		$this->data = $this->ttwreis_common_lib->ttwreis_reports_symptom();

		$this->_render_page('ttwreis_cc/ttwreis_reports_symptom',$this->data);
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

		$this->data = $this->ttwreis_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		
		// Loggedin user
		$loggedinuser = $this->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$hs_req_docs  = $this->ttwreis_cc_model->get_hs_req_docs($collection);
		
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}
			
		$this->_render_page('ttwreis_cc/ttwreis_cc_dash', $this->data);

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

		$this->_render_page('ttwreis_cc/drill_down_screening_to_students_load_ehr',$this->data);
	}

	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//$this->data['docs'] = $this->ttwreis_cc_model->drill_down_screening_to_students_load_ehr_doc($_id);

		$docs = $this->ttwreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);

		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];

		$this->data['docscount'] = count($this->data['docs']);

		$this->_render_page('ttwreis_cc/ttwreis_reports_display_ehr',$this->data);
	}

	public function drill_down_screening_initiate_request($_id)
	{
		//$this->data['docs'] = $this->ttwreis_cc_model->drill_down_screening_to_students_load_ehr_doc($_id);

		$this->data['doc'] = $this->ttwreis_common_model->drill_down_screening_to_students_doc($_id);

		$this->_render_page('ttwreis_cc/ttwreis_reports_display_ehr',$this->data);
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
		$docs = $this->ttwreis_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}

	function drill_down_absent_to_students_load_ehr()
	{
		$this->check_for_admin();
		$this->check_for_plan('drill_down_absent_to_students_load_ehr');
		$temp = base64_decode($_POST['ehr_data_for_absent']);
		$UI_id = json_decode(base64_decode($_POST['ehr_data_for_absent']),true);
		$get_docs = $this->ttwreis_common_model->get_drilling_absent_students_docs($UI_id);

		$navigation = $_POST['ehr_navigation_for_absent'];
		$this->data['navigation'] = $navigation;
		$this->data['students'] = $get_docs;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$this->_render_page('ttwreis_cc/drill_down_absent_to_students_load_ehr',$this->data);
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
		$request_report = json_encode($this->ttwreis_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
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

		$this->_render_page('ttwreis_cc/drill_down_request_to_students_load_ehr',$this->data);
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

		$this->_render_page('ttwreis_cc/drill_down_identifiers_to_students_load_ehr',$this->data);
	}

	//============================================================================================================

	function ttwreis_imports_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_diagnostic');
		$this->data = $this->ttwreis_common_lib->ttwreis_imports_diagnostic();

		$this->_render_page('ttwreis_cc/ttwreis_imports_diagnostic', $this->data);
	}

	function import_diagnostic()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_diagnostic');

		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_diagnostic($post);

		if($this->data == "redirect_to_diagnostic_fn")
		{
			redirect('ttwreis_cc/ttwreis_cc_diagnostic');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_diagnostic', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_diagnostic', $this->data);
		}

	}

	function ttwreis_imports_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_hospital');

		$this->data = $this->ttwreis_common_lib->ttwreis_imports_hospital();

		$this->_render_page('ttwreis_cc/ttwreis_imports_hospital', $this->data);
	}

	function import_hospital()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_hospital');

		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_hospital($post);

		if($this->data == "redirect_to_hospital_fn")
		{
			redirect('ttwreis_cc/ttwreis_cc_hospitals');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_hospital', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_hospital', $this->data);
		}
	}

	function ttwreis_imports_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_school');

		$this->data = $this->ttwreis_common_lib->ttwreis_imports_school();

		$this->_render_page('ttwreis_cc/ttwreis_imports_school', $this->data);
	}

	function import_school()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_school($post);

		if($this->data == "redirect_to_school_fn")
		{
			redirect('ttwreis_cc/ttwreis_cc_schools');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_school', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_school', $this->data);
		}
	}

	function ttwreis_imports_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_health_supervisors');

		$this->data['message'] = FALSE;

		$this->_render_page('ttwreis_cc/ttwreis_imports_health_supervisors', $this->data);
	}

	function import_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_school');

		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_health_supervisors($post);

		if($this->data == "redirect_to_hs_fn")
		{
			redirect('ttwreis_cc/ttwreis_cc_health_supervisors');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_health_supervisors', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_health_supervisors', $this->data);
		}
	}

	function ttwreis_imports_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_students');

		$this->data['message'] = FALSE;

		$this->_render_page('ttwreis_cc/ttwreis_imports_students', $this->data);
	}

	function import_students()
	{
		$this->check_for_admin();
		$this->check_for_plan('import_students');

		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->import_students($post);

		if($this->data['error'] == 'excel_sheet_faild')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_students', $this->data);
		}else if($this->data == "redirect_to_student_fn")
		{
			redirect('ttwreis_cc/ttwreis_reports_students_filter');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_students', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			$this->_render_page('ttwreis_cc/ttwreis_imports_students', $this->data);
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
		$this->_render_page('ttwreis_cc/ttwreis_reports_students_filter',$this->data);
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

		$this->data = $this->ttwreis_common_model->get_students_by_school_name_cc_users($school_name,$dist_name);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}

	public function pie_export()
	{
		$this->data['today_date'] = date('Y-m-d');
		$this->data['distslist'] = $this->ttwreis_common_model->get_all_district();
		$this->_render_page('ttwreis_cc/ttwreis_pie_export',$this->data);
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

		$session_data = $this->session->userdata("customer");				
		$email = $session_data['email'];
		$username = $session_data['username'];

		
		$token = $this->ttwreis_common_lib->insert_ehr_note($post,$username);

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
	 /* $this->data['cases'] =$this->ttwreis_common_model->get_chronic_cases_model($config['per_page'],$page); */

	  //create paginate´s links
	  $this->data['links'] = $this->pagination->create_links();

	  //number page variable
	  $this->data['page'] = $page;

	  $this->data['case_count'] = $total_rows;

	  $this->data['message'] = '';

	  $this->data['cases'] =$this->ttwreis_common_model->get_chronic_cases_model_for_data_table();

	  $this->_render_page('ttwreis_cc/chronic_case_report',$this->data);
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
			  $monDays = 0;
		      foreach($pill_comp_data as $index_ => $values)
			  {
				   $schedule_date  = $values['date'];
				   $monName = date('F', strtotime($schedule_date));
				  // $monDays = date('t', strtotime($schedule_date));

				   if($date_details[0] === $monName)
				   {
			         $monDays++;
					 ${$month_name."compliance_value"}+= (int) $values['compliance'];
				   }
			  }

			  $percent = ${$month_name."compliance_value"}/$monDays;

			  $pre_temp = array($month_start,(int) $percent);
			  array_push($month_wise_compliance,$pre_temp);
			  $monDays = 0;
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
		
		$this->_render_page ( 'ttwreis_cc/add_news_feed', $this->data );
	}
	public function add_news_feed() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'add_news_feed' );
		
		$news_return = $this->ttwreis_common_lib->add_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_cc/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	public function manage_news_feed_view() {
		$this->check_for_admin ();
		$this->check_for_plan ( 'manage_news_feed_view' );
		$this->data ['news_feeds'] = $this->ttwreis_common_model->get_all_news_feeds();
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_cc/manage_news_feed', $this->data );
	}
	public function delete_news_feed($nf_id) {
		$this->ttwreis_common_lib->delete_news_feed($nf_id);
		
		redirect ( 'ttwreis_cc/manage_news_feed_view' );
	}
	
	public function edit_news_feed_view($nf_id) {
		$this->check_for_admin ();
		$this->check_for_plan ( 'edit_news_feed_view' );
		$this->data ['news_feed'] = $this->ttwreis_common_model->get_news_feed($nf_id);
		$this->data ['message'] = "";
	
		$this->_render_page ( 'ttwreis_cc/add_news_feed', $this->data );
	}
	
	public function update_news_feed() {
	
		$news_return = $this->ttwreis_common_lib->update_news_feed ();
		log_message ( 'debug', 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn' . print_r ( $news_return, true ) );
		if($news_return['return_error']){
			$this->data ['message'] = $news_return['message'];
			$this->_render_page ( 'ttwreis_cc/add_news_feed', $this->data );
		}else{
			$this->manage_news_feed_view();
		}
	}
	
	public function extend_request_view() 
	{
		$this->data['message'] = false;
		
		$this->_render_page('ttwreis_cc/extend_request_view',$this->data);
	}
	
	public function extend_request()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_reports_display_ehr_uid($post);
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		$this->data['message'] = false;
	
		$this->_render_page('ttwreis_cc/extend_request',$this->data);
	}
	
	public function app_access($doc_id)
	{

		$this->data = $this->ttwreis_common_lib->app_access($doc_id);
		$this->_render_page('ttwreis_cc/extend_request_app',$this->data);
	}
	
	function hs_req_extend(){

		$form_data = json_decode($_POST['form_data'],true);
		$req_return = $this->ttwreis_common_lib->hs_req_extend($form_data);

		if($req_return['return_error']){
			$this->data ['message'] = $req_return['message'];
			$this->_render_page('ttwreis_cc/extend_request_view',$this->data);
		}else{
			redirect('ttwreis_cc/extend_request_view');
			//$this->manage_news_feed_view();
		}
	}
	
	public function access_request($id)
	{
		$this->data['doc_id'] = $id;
		$this->_render_page('ttwreis_cc/ha_req_application_access',$this->data);
	}
	
	public function feed_bmi_student() 
	 {
		$this->data = "";
		$this->data['message'] = "";
		$this->_render_page('ttwreis_cc/ttwreis_cc_feed_bmi',$this->data);
	}
	
	public function show_bmi_student() 
	 {
		
		 $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		$this->data["message"]       = "";
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->_render_page('ttwreis_cc/ttwreis_cc_show_bmi_graph',$this->data);
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
		$this->_render_page('ttwreis_cc/update_personal_info',$this->data);
	}
	
	public function ttwreis_update_personal_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->ttwreis_common_lib->ttwreis_update_personal_ehr_uid($post);
		
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('ttwreis_cc/ttwreis_update_personal_ehr',$this->data);
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
	  $ehr_update = $this->ttwreis_common_model->update_student_ehr_model($unique_id,$update_profile); 
	  log_message("debug","photo_updateqwerty".print_r($ehr_update,true));
	   $this->data['message'] = 'Updated Successfully';
	 $this->_render_page('ttwreis_cc/update_personal_info',$this->data);
	}
	
	public function post_note_request() {
		
		$post = $_POST;
		
		$token = $this->ttwreis_common_lib->insert_request_note($post);
	   
		$this->output->set_output($token);
	}
	public function initiateAttendanceReport()
	{
		$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
		
		$this->_render_page('ttwreis_cc/attendance_report_view', $this->data);
	}
	public function get_schools_list_by_district()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list_by_district');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->ttwreis_cc_model->get_schools_by_dist_id_model($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	public function create_attendence_report()
    {

        // Form data
        $doc_data = array();
        $doc_data['page1']['Attendence Details'] = array();
        $doc_data['page2']['Attendence Details'] = array();
        $districtID           = $this->input->post('page1_AttendenceDetails_District',true);

        $district = $this->ttwreis_cc_model->get_dist_name_with_dist_id( $districtID);
        
        $school             = $this->input->post('page1_AttendenceDetails_SelectSchool',true);

        $present_students   = $this->input->post('page1_AttendenceDetails_Attended',true);

        $sick_students      = $this->input->post('page1_AttendenceDetails_Sick',true);
        $sick_ids           = $this->input->post('page1_AttendenceDetails_SickUID',true);

        $rtoh_students      = $this->input->post('page1_AttendenceDetails_R2H',true);
        $rtoh_ids           = $this->input->post('page1_AttendenceDetails_R2HUID',true);

        $absent_students    = $this->input->post('page1_AttendenceDetails_Absent',true);
        $absent_ids         = $this->input->post('page2_AttendenceDetails_AbsentUID',true);

        $rest_room_students = $this->input->post('page2_AttendenceDetails_RestRoom',true);
        $rest_room_ids      = $this->input->post('page2_AttendenceDetails_RestRoomUID',true);

        $doc_data['page1']['Attendence Details']['District']        = $district;
        $doc_data['page1']['Attendence Details']['Select School']   = $school;
        $doc_data['page1']['Attendence Details']['Attended']        = $present_students;
        $doc_data['page1']['Attendence Details']['Sick']            = $sick_students;
        $doc_data['page1']['Attendence Details']['Sick UID']        = $sick_ids;
        $doc_data['page1']['Attendence Details']['R2H']             = $rtoh_students;
        $doc_data['page1']['Attendence Details']['R2H UID']         = $rtoh_ids;
        $doc_data['page1']['Attendence Details']['Absent']          = $absent_students;
        $doc_data['page2']['Attendence Details']['Absent UID']      = $absent_ids;
        $doc_data['page2']['Attendence Details']['RestRoom']        = $rest_room_students;
        $doc_data['page2']['Attendence Details']['RestRoom UID']    = $rest_room_ids;

        //Doc Properites
        $doc_properties['doc_id'] = get_unique_id();
        $doc_properties['status'] = 2;
        $doc_properties['_version'] = 2;
        $doc_properties['total_pages'] = 1;

        //App Properites
        $app_properties['app_name'] = "Attendance app";
        $app_properties['app_id'] = "healthcare20161015173311279";
        
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
        
        $added = $this->ttwreis_cc_model->create_attendence_report_model($doc_data, $doc_properties, $app_properties, $history);
        if($added)
        {
            $this->data['message'] = $this->session->set_flashdata('message','Attendance Report Submitted Successfully');
            redirect('ttwreis_cc/to_dashboard');
        }
        else
        {
            redirect('ttwreis_cc/initiateAttendanceReport');
        }

    }
    public function get_my_school_code()
	{
	    $logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		return $school_code;
	}
    public function fetch_student_info()
	{
		$unique_id= $_POST['page1_StudentDetails_HospitalUniqueID'];
		/*$school_code = $this->get_my_school_code();*/
		//$school_info = $this->ttwreis_cc_model->get_school_info($school_code);
		//$school_name = $school_info[0]['school_name'];
		//$this->data['get_data'] = $this->panacea_cc_model->fetch_student_info_model( $unique_id);
		$this->data['get_data'] = $this->ttwreis_cc_model->fetch_student_info_model( $unique_id);

		if($this->data['get_data'] && !empty($this->data['get_data']))
		{
			$this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
	}
    public function initiateBmiReport()
	{
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);
		$this->load->view('ttwreis_cc/bmifeed_report_view', $this->data);

	}

	 public function feedBmiStudentReport()
	{

		
		$monthly_bmi = array();
		$uniqueId   = $this->input->post('student_code',TRUE);
		$is_exists = $this->ttwreis_schools_common_model->check_if_doc_exists_in_bmi($uniqueId);

		if(!empty($is_exists))
		{
			$height = $this->input->post('page1_StudentDetails_Heightcms',TRUE);
			$weight = $this->input->post('page1_StudentDetails_Weightkgs',TRUE);
			$bmi    = $this->input->post('page1_StudentDetails_BMI',TRUE);
			$month  = $this->input->post('page1_StudentDetails_Date',TRUE);

			$new_date = new DateTime($month);
			$ndate = $new_date->format('Y-m');

			$monthly_bmi = array(
				'height' => $height,
				'weight' => $weight,
				'bmi' => (double) $bmi,
				'month' => $ndate
			);


			$existing_update = $this->ttwreis_schools_common_model->update_bmi_values($ndate,$monthly_bmi, $uniqueId);

				if ($existing_update ) // the information has therefore been successfully saved in the db
				{
					/*if($bmi < "18.5") 
					{
						$message = "Under Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}elseif ($bmi > "25" && $bmi < "29.99") {
						$message = "Over Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}
					elseif ($bmi > "30") {
						$message = "Obese Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}*/
					$this->session->set_flashdata('success','BMI report updated successfully !!');
					redirect('ttwreis_cc/initiateBmiReport');
				}
				else
				{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('ttwreis_cc/initiateBmiReport');
				}


			}

			else 
			{
				  //Validation OK!
				
				$bmi_final = array();
				$uniqueId   = $this->input->post('student_code',TRUE);
				$studentName = $this->input->post('page1_StudentDetails_Name',TRUE);
				$class = $this->input->post('page1_StudentDetails_Class',TRUE);
				$section = $this->input->post('page1_StudentDetails_Section',TRUE);
				$height = $this->input->post('page1_StudentDetails_Heightcms',TRUE);
				$weight = $this->input->post('page1_StudentDetails_Weightkgs',TRUE);
				$bmi = $this->input->post('page1_StudentDetails_BMI',TRUE);
				$month = $this->input->post('page1_StudentDetails_Date',TRUE);

				$new_date = new DateTime($month);

				$ndate = $new_date->format('Y-m');


				$bmi_array = array();
				$monthly_bmi['page1']['Student Details']['Hospital Unique ID'] = $uniqueId;
				$monthly_bmi['page1']['Student Details']['Name']['field_ref'] = $studentName;
				$monthly_bmi['page1']['Student Details']['Class']['field_ref'] = $class;
				$monthly_bmi['page1']['Student Details']['Section']['field_ref'] = $section;
				$monthly_bmi['page1']['Student Details']['Date'] = $month;
				
				$test = array(
					'height' => $height,
					'weight' => $weight,
					'bmi' 	 => (double) $bmi,
					'month'  => $ndate
				);
				
				
				array_push($bmi_array, $test);

				$monthly_bmi['page1']['Student Details']['BMI_values'] = $bmi_array;


				$school_code = $this->get_my_school_code();
				$school_info = $this->ttwreis_schools_common_model->get_school_info($school_code);
				$school_name = $school_info[0]['school_name'];
				$dist = explode(',', $school_name);
				$districtName = $dist[1];				

				$school_details['School Name'] = $school_name;
				$school_details['District'] = $districtName;

				// Doc properties
				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 2;
				$doc_properties['_version'] = 2;
				$doc_properties['total_pages'] = 1;

				// App properties
				$app_properties['app_name'] = "TTWREIS BMI App";
				$app_properties['app_id'] = "healthcare2017619153715384";



				$session_data = $this->session->userdata("customer");
			
				$email_id = $session_data['email'];

				$email = str_replace("@","#",$email_id);
	          // History
				$approval_data = array(
					"current_stage" => "TTWREIS BMI",
					"approval" => "true",
					"submitted_by" => $email,
					"time" => date('Y-m-d H:i:s'));

				$history['last_stage'] = $approval_data;


				$newly_created = $this->ttwreis_schools_common_model->add_student_BMI_model($monthly_bmi, $school_details, $doc_properties, $app_properties, $history);

				if($newly_created){
					/*if($bmi < "18.5") 
					{
						$message = "Under Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}elseif ($bmi > "25" && $bmi < "29.99") {
						$message = "Over Weight Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}
					elseif ($bmi > "30") {
						$message = "Obese Child Observed Details: Name : ".$studentName." U ID : ".$uniqueId." Class: ".$class." BMI : ".$bmi;
						$sms =  $this->bhashsms->send_sms($this->mobile['mob_num'],$message);
					}*/
					$this->session->set_flashdata('success','BMI report submitted successfully !!');
					redirect('ttwreis_cc/initiateBmiReport');
				}
				else{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('ttwreis_cc/initiateBmiReport');
				}

			}
	}
	function ttwreis_imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_bmi_values');
		//$this->data = $this->get_district_school_name();
		$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
		$this->data['message'] = "";
		$this->_render_page('ttwreis_cc/ttwreis_imports_bmi_values', $this->data);
	}

	function imported_bmi_xl_sheet()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_diagnostic');
		//$this->data = $this->ttwreis_cc_lib->bc_welfare_imports_hb_values();
			//$this->data = $this->get_district_school_name();
			$this->data['message'] = "Imported BMI XL Sheet Successfully !";
		$this->_render_page('ttwreis_cc/ttwreis_imports_bmi_values', $this->data);
	}
	function imports_bmi_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('imports_bmi_values');

		$post = $_POST;

		
		$this->data = $this->ttwreis_common_lib->imports_bmi_values($post);

		
		if($this->data == "redirect_to_bmi_fn")
		{

			redirect('ttwreis_cc/ttwreis_imports_bmi_values');
		}else if($this->data['error'] == 'excel_column_check_fail')
		{
			/*$this->data = $this->get_district_school_name();*/
			$this->data = $this->ttwreis_cc_model->get_all_district();
			//$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
			$this->_render_page('ttwreis_cc/ttwreis_imports_bmi_values', $this->data);
		}else if($this->data['error'] == 'file_upload_failed')
		{
			/*$this->data = $this->get_district_school_name();*/
			$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
			$this->_render_page('ttwreis_cc/ttwreis_imports_bmi_values', $this->data);
		}
	}
	public function initiateHemoglobinReport()
	{
		$logged_in_user = $this->session->userdata("customer");
		$email    		= $logged_in_user['email'];
		$email_array    = explode(".",$email);
		
		//$this->data['message'] = $this->session->flashdata('message');
		$this->data["school_code"]   = $email_array[1];
		$this->data["district_code"] = strtoupper($email_array[0]);

	  	$this->_render_page('ttwreis_cc/ttwreis_cc_hb_view', $this->data);
	}
	//hemoglobin submit data
 	public function create_hemoglobin_report()
  	{

	  	$uniqueId   = $this->input->post('page1_StudentDetails_HospitalUniqueID',TRUE);
		$is_exists  = $this->ttwreis_cc_model->check_if_doc_exists_in_hb($uniqueId);
		
		if(!empty($is_exists))
		{
			$hb    = $this->input->post('page1_StudentDetails_HB',TRUE);
			$month  = $this->input->post('page1_StudentDetails_Date',TRUE);

			$new_date = new DateTime($month);
			$ndate = $new_date->format('Y-m');

			$monthly_hb = array(
				'hb' => $hb,
				'month' => $ndate
			);


			$existing_update = $this->ttwreis_cc_model->update_hb_values($ndate,$monthly_hb, $uniqueId);

			if ($existing_update ) // the information has therefore been successfully saved in the db
			{
				$this->session->set_flashdata('success','HB report updated successfully !!');
				redirect('ttwreis_cc/initiateHemoglobinReport');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('ttwreis_cc/initiateHemoglobinReport');
			}


			}

			else 
			{
				
				$hb_final = array();
				$uniqueId   = $this->input->post('page1_StudentDetails_HospitalUniqueID',TRUE);
				$studentName = $this->input->post('page1_StudentDetails_Name',TRUE);
				$class = $this->input->post('page1_StudentDetails_Class',TRUE);
				$section = $this->input->post('page1_StudentDetails_Section',TRUE);
				$hb = $this->input->post('page1_StudentDetails_HB',TRUE);
				$bloodgroup = $this->input->post('page1_StudentDetails_bloodgroup',TRUE);
				$month = $this->input->post('page1_StudentDetails_Date',TRUE);

				$new_date = new DateTime($month);

				$ndate = $new_date->format('Y-m');


				$hb_array = array();
				$monthly_hb['page1']['Student Details']['Hospital Unique ID'] = $uniqueId;
				$monthly_hb['page1']['Student Details']['Name']['field_ref'] = $studentName;
				$monthly_hb['page1']['Student Details']['Class']['field_ref'] = $class;
				$monthly_hb['page1']['Student Details']['Section']['field_ref'] = $section;
				$monthly_hb['page1']['Student Details']['bloodgroup']['field_ref'] = $bloodgroup;
				$monthly_hb['page1']['Student Details']['Date'] = $month;
				
				
				$test = array(
					'hb' => $hb,
					'month' => $ndate
				);
				
				
				array_push($hb_array, $test);

				$monthly_hb['page1']['Student Details']['HB_values'] = $hb_array;

				// $school_code = $this->get_my_school_code();
				// $school_info = $this->tswreis_schools_common_model->get_school_info($school_code);
				// $school_name = $school_info[0]['school_name'];
				// $dist = explode(',', $school_name);
				// $districtName = $dist[1];				

				// $doc_data['school_details']['School Name'] = $school_name;
				// $doc_data['school_details']['District'] = $districtName;


				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "TTWREIS";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";


				$app_properties['app_name'] = "TTWREIS HB App";
				$app_properties['app_id'] = "TT HB";



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


				$newly_created = $this->ttwreis_cc_model->add_student_HB_model($monthly_hb, $doc_properties, $app_properties, $history);

				if($newly_created){
					$this->session->set_flashdata('success','HB report submitted successfully !!');
					redirect('ttwreis_cc/initiateHemoglobinReport');
					
				}
				else{
					$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
					redirect('ttwreis_cc/initiateHemoglobinReport');
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
		$this->_render_page('ttwreis_cc/school_wise_show_hb_graph',$this->data);
	}
	public function student_hb_graph(){
		
		$month_wise_hb  = array();
		$month_wise_data = array();
		$final_hb_data  = array();
		$temp 	 		 = array();
		$unique_id = $_POST['student_code'];
		

		$hb_value = $this->ttwreis_cc_model->get_student_hb_values($unique_id);

				
		
		if(isset($hb_value) && !empty($hb_value))
		{

			foreach($hb_value as $hb)
			{
				log_message("debug","bmi1436".print_r($hb,true));
				if(isset($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']) && !empty($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']))
				{
					foreach($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb_data )
					{
						/*echo print_r($hb_data,true);
						exit();*/
						$hb    = $hb_data['hb'];
						$date   = $hb_data['month'];

						$new_start_ = new DateTime($date);
						$month_start = $new_start_->getTimestamp()*1000;
						$pre_temp = array($month_start,(int) $hb);

						array_push($month_wise_hb,$pre_temp);

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

			$this->output->set_output(json_encode($final_hb_data));
		}
		else
		{
			$this->output->set_output('NO_GRAPH');
		}

		
	}
	function ttwreis_imports_hb_values()
	{
		$this->check_for_admin();
		$this->check_for_plan('ttwreis_imports_diagnostic');
		//$this->data = $this->tswreis_schools_common_lib->ttwreis_imports_hb_values();
		//$this->data = $this->get_district_school_name();
		$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
		
			$this->data['message'] = "";
		$this->_render_page('ttwreis_cc/ttwreis_imports_hb_values', $this->data);
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

		//$this->data["message"]       = "";
		$this->data['district_code']  = strtoupper($email_array[0]);
		$this->data['school_code']    = $email_array[1];

		$this->_render_page('ttwreis_cc/hs_initiate_request', $this->data);
	}
	/*
	* Getting Students information based on Unique ID
	* author Naresh
	*/

	/*
	* Submiting HS Request function
	* author Naresh
	*/
	public function initiate_hs_request()
	{		 

		$unique_id = $this->input->post('student_code',TRUE);
		$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
		$district = $this->input->post('page1_StudentInfo_District',TRUE);
		$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
		$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
		$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

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
			$age = "10";
		}else if($class == "6")
		{
			$age = "11";
		}else if($class == "7")
		{
			$age = "12";
		}else if($class == "8")
		{
			$age = "13";
		}else if($class == "9")
		{
			$age = "14";
		}else if($class == "10")
		{
			$age = "15";
		}elseif ($class == "11") 
		{
			$age = "16";
		}elseif($class == "12")
		{
			$age = "17";
		}elseif($class == "Degree 1st")
		{
			$age = "18";
		}elseif($class == "Degree 2nd")
		{
			$age = "19";
		}elseif($class == "Degree 3rd")
		{
			$age = "20";
		}

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

		//$doc_data = array();
		//$doc_data['page1']['Student Info'] = array();
	  // Page 1
		$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
		$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = $student_name;
		$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
		$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
		$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
		$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
		$doc_data['widget_data']['page1']['Student Info']['Gender']    = $gender;
		$doc_data['widget_data']['page1']['Student Info']['Age']    = $age;
		
		$doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = $doctor_summary;
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = $doctor_advice;
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = $prescription;

		$doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
		$doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

		if($review_status == 'Hospitalized' || $review_status == 'Out-Patient' || $review_status == 'Review')
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
		  
		 }
		 $session_data = $this->session->userdata('customer');
		 $username = $session_data['email'];
		

		  $doc_data['stage_name'] = 'healthcare2016108181933756';

          $doc_data['first_stage_name'] = "HS 1";

          $doc_data['user_name']  = $username;
		  
		  $doc_data['chart_data']  = '';

		 // school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 /*$school_data = $this->ttwreis_schools_common_model->get_school_information_for_school_code($schoolCode);*/


		 if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ion_auth->ttwreis_health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->ttwreis_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
		 }
		 

		 //log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
//		echo print_r($doc_data,true);
		//exit();
		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 1;
		$doc_properties['doc_owner'] = "TTWREIS";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";

		
		$app_properties = array(
						'app_name' => "Health Requests App",
						'app_id' => "healthcare2016108181933756",
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
  	 
		  if($request_type === "Chronic" || $request_type === "Deficiency" || $request_type === "Defects")
		 {
	       $chronic_disease   = $chronic_identifiers;
	       $disease_desc      = $problem_info_description;
		  // log_message('error','chronic_disease=========2662'.print_r($chronic_disease,TRUE));exit();
		   $this->ttwreis_schools_common_model->create_chronic_case_new($unique_id,$request_type,$chronic_disease,$disease_desc,$schoolName);
	     }

  	  $initate_submit = $this->ttwreis_schools_common_model->initiate_request_model($doc_data,$doc_properties,$app_properties,$array_history); 

  	   $get_doc_id = $this->ttwreis_schools_common_model->get_doc_id_for_check($initate_submit);

	    if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
  	  {
  	  	$insert_hospitalised = $this->ttwreis_schools_common_model->insert_hospitalised_students_data($doc_data,$array_history,$unique_id,$get_doc_id,$doc_properties);
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
				$send_msg = $this->ttwreis_common_lib->send_message_to_doctors($request_type,$unique_id,$student_name,$total_diseaes);

				$fcm_notification = $this->ttwreis_common_lib->fcm_message_notification($request_type,$unique_id,$student_name);
				
				$this->session->set_flashdata('success','Request Raised successfully !!');
				redirect('ttwreis_cc/hs_request');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('ttwreis_cc/hs_request');
			}

  	 

  	}
  	// get the requests
  		public function fetch_submited_requests_docs()
  	{
  		/*$session_data = $this->session->userdata('customer');
  		$email = $session_data['email'];
  		$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
  		
  		$unique_id_code = $unique_id."*";
  		*/
  		$collection = "healthcare2016108181933756_static_html";
  		$hs_req_docs  = $this->ttwreis_cc_model->get_hs_req_normal($collection);

		$hs_req_emergency  = $this->ttwreis_cc_model->get_hs_req_emergency($collection);

		$hs_req_chronic  = $this->ttwreis_cc_model->get_hs_req_chronic($collection);
			
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
		
  		$this->_render_page('ttwreis_cc/fetch_submited_requests_docs',$this->data);
  	}
  	public function access_submited_request_docs($id)
  	{
  		$doc_id = $id;
		$query = $this->ttwreis_cc_model->access_submited_request_docs($doc_id);

		$this->data['hs_req_docs'] = $query;
		$this->_render_page('ttwreis_cc/access_submited_request_docs',$this->data);
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
			$age = "10";
		}else if($class == "6")
		{
			$age = "11";
		}else if($class == "7")
		{
			$age = "12";
		}else if($class == "8")
		{
			$age = "13";
		}else if($class == "9")
		{
			$age = "14";
		}else if($class == "10")
		{
			$age = "15";
		}elseif ($class == "11") 
		{
			$age = "16";
		}elseif($class == "12")
		{
			$age = "17";
		}elseif($class == "Degree 1st")
		{
			$age = "18";
		}elseif($class == "Degree 2nd")
		{
			$age = "19";
		}elseif($class == "Degree 3rd")
		{
			$age = "20";
		}

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


		//$doc_data = array();
		//$doc_data['page1']['Student Info'] = array();
	  // Page 1
		$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
		$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = $student_name;
		$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
		$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
		$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
		$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
		$doc_data['widget_data']['page1']['Student Info']['Gender']   = $gender;
		$doc_data['widget_data']['page1']['Student Info']['Age']   = $age;
		//$doc_data['page1']['Problem Info']    = $data_to_store;
		$doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = isset($doctor_summary)? $doctor_summary : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = isset($doctor_advice) ? $doctor_advice : "";
		$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = isset($prescription) ?  $prescription : "";

		$doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
		$doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

		if($review_status == 'Hospitalized' || $review_status == 'Out-Patient' || $review_status == 'Review')
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
						 
								redirect('ttwreis_cc/hs_request');  
							
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
			$doc_history = $this->ttwreis_cc_model->get_history($unique_id,$doc_id);

				

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

		 $school_data = $this->ttwreis_cc_model->get_school_information_for_school_code($schoolCode);

		 $session_data = $this->session->userdata('customer');
		/* echo print_r($session_data,true);
		 exit();*/

		  $health_supervisor = $this->ttwreis_cc_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];
/*
		 if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "CCUSER")
			{
				$health_supervisor = $this->ion_auth->health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 			
			}
		 }*/
		 

		 //log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSNAME==>'.print_r($hs_name,true));
		// log_message('debug','healthcare2016108181933756_CON=====GET_HEALTH_SUPERVISOR_DETAILS==HSMOB==>'.print_r($hs_mob,true));

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
		
		/*$doc_history = $this->ttwreis_cc_model->get_history($unique_id,$doc_id);
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
		
		
		/*$history_array = [];
		
		foreach($doc_history['history'] as $stg_history){
			array_push($history_array, $stg_history);
		}
		$doc_history['history'] = $history_array;*/
		
		//$doc_update = $this->ci->panacea_common_model->update_doc_for_disapprove($healthcare2016108181933756_edit);
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
		
		$approval_history = $this->ttwreis_cc_model->get_approval_history($doc_id);

		array_push($approval_history,$approval_data);

  	  $existing_update = $this->ttwreis_cc_model->update_request_submit_model($doc_data,$approval_history,$unique_id,$doc_id); 

  	   if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
      {
        
        $check_doc_id = $this->ttwreis_schools_common_model->check_doc_id_of_request($doc_id);
        
        if($check_doc_id == 'No Doc Found'){
            $insert_hospitalised = $this->ttwreis_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
        }else if($review_status != $check_doc_id[0]['doc_data']['widget_data']['page2']['Review Info']['Status']){

        $insert_hospitalised = $this->ttwreis_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
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
				$send_msg = $this->ttwreis_common_lib->send_message_to_doctors_update($request_type,$unique_id,$student_name,$total_diseaes);
						
				$fcm_notification = $this->ttwreis_common_lib->fcm_message_notification_update($request_type,$unique_id,$student_name);
				$this->session->set_flashdata('success','Request Updated successfully !!');
				redirect('ttwreis_cc/fetch_submited_requests_docs');
			}
			else
			{
				$this->session->set_flashdata('fail','Some thing went wrong! Try Again');
				redirect('ttwreis_cc/fetch_submited_requests_docs');
			}

  	  
  	}
  	public function reports_display_ehr_uid_new_html_static_hs()
	{
		$student_unique_id = $_POST['student_unique_id'];
		
		$school_code = $this->get_my_school_code();
		$school_info = $this->ttwreis_schools_common_model->get_school_info($school_code);
		$school_name = $school_info[0]['school_name'];

		$logged_in_user = $this->session->userdata("customer");
		$username       = $logged_in_user['username'];

        $this->data = $this->ttwreis_common_lib->reports_display_ehr_uid_new_html_static_hs($student_unique_id,$school_name);
		$this->data["username"] = $username.','.$school_name;
		if(isset($_POST['timee']))
		{
			$time = $_POST['timee'];
			$this->data['time'] = $time;
		}
		
		$this->_render_page('ttwreis_cc/ttwreis_reports_display_ehr_new_dashboard',$this->data);
	}
	//field officer view author suman reddy
	public function field_officer()
	{
		//$this->data['districts_list'] =  $this->ttwreis_cc_model->get_all_district();
		$this->data ="";
		
		$this->_render_page('ttwreis_cc/field_officer', $this->data);
	}
	//submit the field officer form
	public function submit_field_officer()
	{
		$doc_data = array();
		$widget_data = array();
		$doc_attachments =array();

		
		$unique_id = $this->input->post('page1_StudentDetails_HospitalUniqueID',TRUE);
		$student_name = $this->input->post('student_name',TRUE);
		//$district = $this->input->post('page1_AttendenceDetails_District',TRUE);
		//$school_name  = $this->input->post('student_name',TRUE);
		$class  = $this->input->post('student_class',TRUE);
		$section  = $this->input->post('student_section',TRUE);
		$father_name  = $this->input->post('student_fathername',TRUE);
		$mobile_number  = $this->input->post('mobile_number',TRUE);
		$case_type  = $this->input->post('type_of_request',TRUE);

		//out patient details
		$op_doctor_name  = $this->input->post('op_doctor_name',TRUE);
		$op_hospital_name     = $this->input->post('op_hospital_name',TRUE);
		$op_patient_details      = $this->input->post('op_patient_details',TRUE);
		$op_investigation      = $this->input->post('op_investigation',TRUE);
		$op_review_date       = $this->input->post('op_review_date',TRUE);
		$op_meditation      = $this->input->post('op_meditation',TRUE);

		//emergeny or admittted 
		$admitted_doctor_name  = $this->input->post('admitted_doctor_name',TRUE);
		$admitted_hospital_name     = $this->input->post('admitted_hospital_name',TRUE);
		$admitted_patient_details      = $this->input->post('admitted_patient_details',TRUE);
		$admitted_investigation      = $this->input->post('admitted_investigation',TRUE);
		$admitted_review_date       = $this->input->post('admitted_review_date',TRUE);
		$admitted_meditation      = $this->input->post('admitted_meditation',TRUE);

		//review
		$review_doctor_name  = $this->input->post('review_doctor_name',TRUE);
		$review_hospital_name     = $this->input->post('review_hospital_name',TRUE);
		$review_patient_details      = $this->input->post('review_patient_details',TRUE);
		$review_investigation      = $this->input->post('review_investigation',TRUE);
		$review_review_date       = $this->input->post('review_review_date',TRUE);
		$review_meditation      = $this->input->post('review_meditation',TRUE);
		$review_caseclose      = $this->input->post('review_caseclose',TRUE);

		

    		

		//$op_doctor_name      = $this->input->post('op_doctor_name',TRUE);
		//submit to the database 
		$doc_data['widget_data']['Student Details']['Hospital Unique ID'] = $unique_id;
		$doc_data['widget_data']['Student Details']['Student Name'] = $student_name;
		//$doc_data['widget_data']['Student Details']['District'] = $district;
		//$doc_data['widget_data']['Student Details']['School Name'] = $school_name;
		$doc_data['widget_data']['Student Details']['Class'] =  $class;
		$doc_data['widget_data']['Student Details']['Section'] =  $section;
		$doc_data['widget_data']['Student Details']['Father Name'] =  $father_name;
		$doc_data['widget_data']['Student Details']['mobile_number'] =  $mobile_number;
		$doc_data['widget_data']['type_of_request'] =  $case_type;

		///op patient
		
		$doc_data['widget_data']['Out Patient']['doctor_name'] =  $op_doctor_name;
		$doc_data['widget_data']['Out Patient']['hospialt_name'] =  $op_hospital_name;
		$doc_data['widget_data']['Out Patient']['patient_details'] =  $op_patient_details;
		$doc_data['widget_data']['Out Patient']['investigations'] =  $op_investigation;
		$doc_data['widget_data']['Out Patient']['review_date'] =  $op_review_date;
		$doc_data['widget_data']['Out Patient']['medication'] =  $op_meditation;
		
		

		$doc_data['widget_data']['Emergency or Admitted']['doctor_name'] =  $admitted_doctor_name;
		$doc_data['widget_data']['Emergency or Admitted']['hospialt_name'] =  $admitted_hospital_name;
		$doc_data['widget_data']['Emergency or Admitted']['patient_details'] =  $admitted_patient_details;
		$doc_data['widget_data']['Emergency or Admitted']['investigations'] =  $admitted_investigation;
		$doc_data['widget_data']['Emergency or Admitted']['medication'] =  $admitted_medication;
		$doc_data['widget_data']['Emergency or Admitted']['review_date'] =  $admitted_review_date;

		$doc_data['widget_data']['Review Cases']['doctor_name'] =  $review_doctor_name;
		$doc_data['widget_data']['Review Cases']['hospialt_name'] =  $review_hospital_name;
		$doc_data['widget_data']['Review Cases']['patient_details'] =  $review_patient_details;
		$doc_data['widget_data']['Review Cases']['investigations'] =  $review_investigation;
		$doc_data['widget_data']['Review Cases']['medication'] =  $review_medication;
		$doc_data['widget_data']['Review Cases']['review_date'] =  $review_review_date;
		$doc_data['widget_data']['Review Cases']['review_caseclose'] =  $review_caseclose;

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


				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";

				$app_properties['app_name'] = "TTWREIS Field Officer report App";
				$app_properties['app_id'] = "Field Officer App";

				$session_data = $this->session->userdata("customer");
				$email_id = $session_data['email'];

				$email = str_replace("@","#",$email_id);
				$approval_data = array(
					"current_stage" => "stage1",
					"approval" => "true",
					"submitted_by" => $email,
					"time" => date('Y-m-d H:i:s'));

				$history['last_stage'] = $approval_data;

				$newly_created = $this->ttwreis_cc_model->submit_field_officer($doc_data,$doc_attachments, $doc_properties, $app_properties, $history);
				redirect('ttwreis_cc/field_officer');

	}

	///submit the attachments
	private function Prescriptions_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Prescriptions')!== false)
		{
			$controller = 'healthcare2016108181933756_con';
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			//$config['max_size']      = '*';
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
	//submit form end========
	///field offcer pie or chart
	///field offcer pie or chart
	function field_officer_chart() {
		

		$this->data['today_date'] = date('Y-m-d');
		
		
		$this->_render_page('ttwreis_cc/field_officer_pie',$this->data);

	}
	function fetch_field_officer_reports() {

		$today_date= $_POST['today_date'];
		$reports = $this->ttwreis_cc_model->fetch_field_officer_reports($today_date);
		
		$this->output->set_output(json_encode($reports));

	}
	/* ==============get doctor visit report to Students List==============*/
	public function drill_down_to_field_officer_reports(){

		$selectedCase = $_POST['selected_case'];
		$selectedDate = $_POST['selected_date'];
		
		$this->data['student_list'] = $this->ttwreis_cc_model->drill_down_to_field_officer_reports_list($selectedCase, $selectedDate);
		$this->data['selected_case'] = $selectedCase;
		$this->data['selected_date'] = $selectedDate;
		$this->_render_page('ttwreis_cc/field_officer_submit_student_list',$this->data);
	  	
	}
	public function show_doctor_treated_student($doc_id)
	{
		$this->data['unique_id'] = $this->ttwreis_cc_model->show_field_officer_submit_student($doc_id);
		$this->_render_page('ttwreis_cc/field_officer_show_submitted_list',$this->data);
	}
	//new screening import data...........
	public function search_screening_info_by_unique_id()
	{
		$post = $_POST['unique_id'];

		$this->data = $this->ttwreis_common_lib->get_study_circle_data_lib($post);

		$this->data['docscount'] = count($this->data['docs']);


		$this->_render_page('ttwreis_cc/get_screening_report_view',$this->data);
		
	}
	public function update_student_info_view()
	{
     

     $this->data["message"]       = "";
    
     $this->_render_page('ttwreis_cc/update_screening_info_view',$this->data);
	}

	
	public function update_screening_report()
	{
	  	$photo_obj = array();
		$uniqueid = $this->input->post('uniqueid');
		//$doc_id = $this->input->post('doc_id');
		$mobile = $this->input->post('mobile');
		$student_name = $this->input->post('student_name');
		$father_name = $this->input->post('father_name');
		$date_of_birth = $this->input->post('date_of_birth');
		$mobile_number = $this->input->post('mobile_number');
		$class = $this->input->post('class');
		$section = $this->input->post('section');
		$school_name = $this->input->post('school_name');
		$district_name = $this->input->post('district_name');
		$admission_no = $this->input->post('admission_no');
		$date_of_exam = $this->input->post('date_of_exam');

		$height_in_cms = $this->input->post('height');
		$weight_in_kgs = $this->input->post('weight');
		$bmi = $this->input->post('bmi');
		$pulse = $this->input->post('pulse');
		$bp = $this->input->post('bp');
		$hb = $this->input->post('hb');
		$blood_group = $this->input->post('blood_group');

		$general_problems = $this->input->post('general_problems',TRUE);
		
		//echo print_r($general_problems,TRUE);

		$ortho_problems = $this->input->post('ortho_problems',TRUE);
		//echo print_r($ortho_problems,TRUE);exit();
		$postural_problems = $this->input->post('postural_problems');
		$general_description = $this->input->post('general_description');
		$defects_at_birth_problems = $this->input->post('defects_at_birth_problems');
		$skin_problems = $this->input->post('skin_problems');
		$deficencies_problems = $this->input->post('deficencies_problems');
		$childhood_disease_problems = $this->input->post('childhood_disease_problems');
		$nad = $this->input->post('nad');
		$without_glasses_right = $this->input->post('without_glasses_right');
		$without_glasses_left = $this->input->post('without_glasses_left');

		$with_glasses_right = $this->input->post('with_glasses_right');
		$with_glasses_left = $this->input->post('with_glasses_left');

		$subjective_refraction = $this->input->post('subjective_refraction');

		$colour_blindness_right = $this->input->post('colour_blindness_right');
		$colour_blindness_left = $this->input->post('colour_blindness_left');

		$ocular_diagnosis = $this->input->post('ocular_diagnosis');
		$eye_treatment_description = $this->input->post('eye_treatment_description');

		$eye_referral_made = $this->input->post('eye_referral_made');

		$eye_lids = $this->input->post('eye_lids');
		$conjuctiva = $this->input->post('conjuctiva');
		$cornea = $this->input->post('cornea');
		$pupil = $this->input->post('pupil');
		$complaints = $this->input->post('complaints');
		$wearing_spectacles = $this->input->post('wearing_spectacles');


		$auditory_screening_right = $this->input->post('auditory_screening_right');
		$auditory_screening_left = $this->input->post('auditory_screening_left');
		$speech_screening = $this->input->post('speech_screening');
		$DD_and_disability = $this->input->post('D D and disability');
		$auditory_advice = $this->input->post('auditory_advice');
		$auditory_referral_made = $this->input->post('auditory_referral_made');

		$oral_hygiene = $this->input->post('oral_hygiene');
		$carious_teeth = $this->input->post('carious_teeth');
		$flourosis = $this->input->post('flourosis');
		$orthodontics_treatment = $this->input->post('orthodontics_treatment');
		$indication_for_extraction = $this->input->post('indication_for_extraction');
		$root_canal_treatment = $this->input->post('root_canal_treatment');
		$crowns = $this->input->post('crowns');
		$fixed_partial_denture = $this->input->post('fixed_partial_denture');
		$curettage = $this->input->post('curettage');

		$dental_result = $this->input->post('dental_result');
		$estimated_amount = $this->input->post('estimated_amount');
		$dental_referral_made = $this->input->post('dental_referral_made');




		  // Form EHR Document
		  $doc_data = array();
		  $doc_data['page1']['Personal Information']  = array();
		  $doc_data['page2']['Personal Information']  = array();
		  $doc_data['page3']['Physical Exam'] = array();
		  $doc_data['page4']['Doctor Check Up'] = array();
		  $doc_data['page5']['Doctor Check Up'] = array();
		  $doc_data['page6'] = array();
		  $doc_data['page7'] = array();
		  $doc_data['page8'][' Auditory Screening'] = array();
		  $doc_data['page9']['Dental Check-up'] = array();

		  // Page 1
		  $doc_data['page1']['Personal Information']['Name']             = $student_name;
		  $doc_data['page1']['Personal Information']['Mobile']           = array("country_code"=>"91","mob_num"=>$mobile_number);
		  $doc_data['page1']['Personal Information']['Date of Birth']    = $date_of_birth;
		  $doc_data['page1']['Personal Information']['Hospital Unique ID'] = $uniqueid;
		  $query = $this->ttwreis_cc_model->get_pf_photo($uniqueid);
		  if(isset($query) && !empty($query))
		  {
		  	$photo_path = $query[0]['doc_data']['widget_data']['page1']['Personal Information']['Photo'];
		 	$doc_data['page1']['Personal Information']['Photo']            = $photo_path;
		  }else
		  {
		  	$doc_data['page1']['Personal Information']['Photo']            = "";
		  }
		  
		   
		  // Page 2
		  $doc_data['page2']['Personal Information']['AD No']            = $admission_no;
		  $doc_data['page2']['Personal Information']['District']         = $district_name;
		  $doc_data['page2']['Personal Information']['School Name']      = $school_name;
		  $doc_data['page2']['Personal Information']['Class'] 			 = $class;
		  $doc_data['page2']['Personal Information']['Section']          = $section;
		  $doc_data['page2']['Personal Information']['Father Name']      = $father_name;
		  $doc_data['page2']['Personal Information']['Date of Exam']     = $date_of_exam;

		  // Page 3
		  if( $height_in_cms =="" && $weight_in_kgs ==" " && $bmi =="" && $pulse =="" && $bp =="" && $hb =="" && $blood_group =="" ){  
		  	 $doc_data['page3'] =array();
		   }
		   else{
		  $doc_data['page3']['Physical Exam']['Height cms']         = $height_in_cms;
		  $doc_data['page3']['Physical Exam']['Weight kgs']         = $weight_in_kgs;
		  $doc_data['page3']['Physical Exam']['BMI%']      			= $bmi;
		  $doc_data['page3']['Physical Exam']['Pulse'] 			 	= $pulse;
		  $doc_data['page3']['Physical Exam']['B P']          		= $bp;
		  $doc_data['page3']['Physical Exam']['H B']      			= $hb;
		  $doc_data['page3']['Physical Exam']['Blood Group']     	= $blood_group;
		}
		


	
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
		 
			 $this->upload->initialize($config);
			 if ( ! $this->upload->do_upload('logo_file'))
			 {
				//echo "file upload failed";
				//return FALSE;
				// $doc_data['page1']['Personal Information']['Photo'] = "";
			 }
			 else
			 {
				$photo_obj = $this->upload->data('logo_file');
				 $photo_ele = array(
					"file_client_name"    => $photo_obj['client_name'],
					"file_encrypted_name" => $photo_obj['file_name'],
					"file_path" 		  => $photo_obj['file_relative_path'],
					"file_size" 		  => $photo_obj['file_size']
				);

		   		$doc_data['page1']['Personal Information']['Photo'] = $photo_ele;
		   		
			 }

			$doc_data['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = is_array($general_problems) ? $general_problems : [];	
				
			$doc_data['page4']['Doctor Check Up']['Ortho'] = is_array($ortho_problems) ? $ortho_problems : [];
			$doc_data['page4']['Doctor Check Up']['Postural'] = is_array($postural_problems) ? $postural_problems : [];
			$doc_data['page4']['Doctor Check Up']['Description'] = $general_description;
			$doc_data['page4']['Doctor Check Up']['Skin conditions'] = is_array($skin_problems) ? $skin_problems : [];

			$doc_data['page5']['Doctor Check Up']['Defects at Birth'] = is_array($defects_at_birth_problems) ? $defects_at_birth_problems : [];
			$doc_data['page5']['Doctor Check Up']['Deficencies'] = is_array($deficencies_problems) ? $deficencies_problems : [];
			$doc_data['page5']['Doctor Check Up']['Childhood Diseases'] = is_array($childhood_disease_problems) ? $childhood_disease_problems : [];

			$doc_data['page5']['Doctor Check Up']['N A D'] = is_array($nad) ? $nad : [];

			$doc_data['page6']['Without Glasses']['Right'] = ($without_glasses_right) ? $without_glasses_right : "";
			$doc_data['page6']['Without Glasses']['Left'] = ($without_glasses_left) ? $without_glasses_left : "";

			$doc_data['page6']['With Glasses']['Right'] = ($with_glasses_right) ? $with_glasses_right : "";
			$doc_data['page6']['With Glasses']['Left'] = ($with_glasses_left) ? $with_glasses_left : "";
			$doc_data['page7']['Colour Blindness']['Right'] = ($colour_blindness_right) ? $colour_blindness_right : "";
			$doc_data['page7']['Colour Blindness']['Left'] = ($colour_blindness_left) ? $colour_blindness_left : "";

			$doc_data['page7']['Colour Blindness']['Eye Lids'] = ($eye_lids) ? $eye_lids : "";
			$doc_data['page7']['Colour Blindness']['Conjunctiva'] = ($conjuctiva) ? $conjuctiva : "";
			$doc_data['page7']['Colour Blindness']['Cornea'] = ($cornea) ? $cornea : "";
			$doc_data['page7']['Colour Blindness']['Pupil'] = ($pupil) ? $pupil : "";
			$doc_data['page7']['Colour Blindness']['Complaints'] = ($complaints) ? $complaints : "";
			$doc_data['page7']['Colour Blindness']['Wearing Spectacles'] = ($wearing_spectacles) ? $wearing_spectacles : "";

			$doc_data['page7']['Colour Blindness']['Subjective Refraction'] = ($subjective_refraction) ? $subjective_refraction : "";
			$doc_data['page7']['Colour Blindness']['Ocular Diagnosis'] = $ocular_diagnosis;
			$doc_data['page7']['Colour Blindness']['Description'] = $eye_treatment_description;
			$doc_data['page7']['Colour Blindness']['Referral Made'] = is_array($eye_referral_made) ? $eye_referral_made : [];

			$doc_data['page8'][' Auditory Screening']['Right'] = ($auditory_screening_right) ? $auditory_screening_right : "";
			$doc_data['page8'][' Auditory Screening']['Left'] = ($auditory_screening_left) ? $auditory_screening_left : "";
			$doc_data['page8'][' Auditory Screening']['Speech Screening'] = is_array($speech_screening) ? $speech_screening : [];
			$doc_data['page8'][' Auditory Screening']['D D and disability'] = is_array($DD_and_disability) ? $DD_and_disability : [];
			$doc_data['page8'][' Auditory Screening']['Description'] = $auditory_advice;
			$doc_data['page8'][' Auditory Screening']['Referral Made'] = is_array($auditory_referral_made) ? $auditory_referral_made : [];

			$doc_data['page9']['Dental Check-up']['Oral Hygiene'] = ($oral_hygiene) ? $oral_hygiene : "";
			$doc_data['page9']['Dental Check-up']['Carious Teeth'] = ($carious_teeth) ? $carious_teeth:"";
			$doc_data['page9']['Dental Check-up']['Flourosis'] = ($flourosis) ? $flourosis : "";
			$doc_data['page9']['Dental Check-up']['Orthodontic Treatment'] = ($orthodontics_treatment) ? $orthodontics_treatment : "";
			$doc_data['page9']['Dental Check-up']['Indication for extraction'] = ($indication_for_extraction) ? $indication_for_extraction: "";
			$doc_data['page9']['Dental Check-up']['Root Canal Treatment'] = ($root_canal_treatment) ? $root_canal_treatment : "";
			$doc_data['page9']['Dental Check-up']['CROWNS'] = ($crowns) ? $crowns: "";
			$doc_data['page9']['Dental Check-up']['Fixed Partial Denture'] = ($fixed_partial_denture) ? $fixed_partial_denture : "";
			$doc_data['page9']['Dental Check-up']['Curettage'] = ($curettage) ? $curettage :"";
			$doc_data['page9']['Dental Check-up']['Referral Made'] = is_array($dental_referral_made) ? $dental_referral_made : [];
			$doc_data['page9']['Dental Check-up']['Result'] = ($dental_result) ? $dental_result:"";
			$doc_data['page9']['Dental Check-up']['Estimated Amount'] = ($estimated_amount) ? $estimated_amount:"";

			 $doc_history = $this->ttwreis_cc_model->get_student_history($uniqueid);

			/* External attachements*/

			 	if(isset($_FILES) && !empty($_FILES))
				{
				
			       $this->load->library('upload');
			       $this->load->library('image_lib');
				   
				   $external_screening_files = array();
				   $external_screening_final = array();
				   $external_screening_merged_data = array();

				    $mef_files = array();
				    $mef_final = array();
				    $mef_merged_data       = array();

				   

				   if(isset($_FILES['external_attachments']['name']) && !empty($_FILES['external_attachments']['name']))
				   {
				   	   $files = $_FILES;
					   $cpt = count($_FILES['external_attachments']['name']);
					    
					   for($i=0; $i<$cpt; $i++)
					   {
						 $_FILES['external_attachments']['name']	= $files['external_attachments']['name'][$i];
						 $_FILES['external_attachments']['type']	= $files['external_attachments']['type'][$i];
						 $_FILES['external_attachments']['tmp_name'] = $files['external_attachments']['tmp_name'][$i];
						 $_FILES['external_attachments']['error']	= $files['external_attachments']['error'][$i];
						 $_FILES['external_attachments']['size']	= $files['external_attachments']['size'][$i];
						
					   foreach ($_FILES as $index => $value)
				       {			       
				       
				       		if(!empty($value['name'] && $index == 'external_attachments'))
						  	{
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/healthcare201671115519757_con/files/external_files/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '*';
								$config['encrypt_name']  = TRUE;
						  	
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201671115519757_con/files/external_files/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									 echo "external file upload failed";
					        		 return FALSE;
								}
								else
								{
									$external_screening_files = $this->upload->data();
									//log_message('debug', 'external_screening_files=======5849'.print_r($external_screening_files, true));
									$rand_number = mt_rand();
									$external_screening_data_array = array(
															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
															"file_name" =>$external_screening_files['file_name'],
															"file_path" =>$external_screening_files['file_relative_path'],
															"file_size" =>$external_screening_files['file_size']
														)	);

									$external_screening_final = array_merge($external_screening_final,$external_screening_data_array);
									
								}  
							}
						}
						 }
					 
					   if(isset($doc_history[0]['doc_data']['widget_data']['external_attachments']))
						  {
								   
							$external_screening_merged_data = array_merge($doc_history[0]['doc_data']['widget_data']['external_attachments'],$external_screening_final);
							$doc_data['external_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['widget_data']['external_attachments'],$external_screening_merged_data);
						  }
						  else
						 {
						    $doc_data['external_attachments'] = $external_screening_final;
						 }
					   }else
					   {
					   		$doc_data['external_attachments'] = [];
					   }

				/* MEF Forms */
					   if(isset($_FILES['mef_files']['name']) && !empty($_FILES['mef_files']['name']))
					   {
					   	   $files = $_FILES;
						   $cpt = count($_FILES['mef_files']['name']);
						    
						   for($i=0; $i<$cpt; $i++)
						   {
							 $_FILES['mef_files']['name']	= $files['mef_files']['name'][$i];
							 $_FILES['mef_files']['type']	= $files['mef_files']['type'][$i];
							 $_FILES['mef_files']['tmp_name'] = $files['mef_files']['tmp_name'][$i];
							 $_FILES['mef_files']['error']	= $files['mef_files']['error'][$i];
							 $_FILES['mef_files']['size']	= $files['mef_files']['size'][$i];
							
						   foreach ($_FILES as $index => $value)
					       {			       
					       
					       		if(!empty($value['name'] && $index == 'mef_files'))
							  	{
							        $config = array();
									$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/files/ttwreis_mef_external_files/';
									$config['allowed_types'] = '*';
									$config['max_size']      = '*';
									$config['encrypt_name']  = TRUE;
							  	
							        //create controller upload folder if not exists
									if (!is_dir($config['upload_path']))
									{
										mkdir(UPLOADFOLDERDIR."public/uploads/files/ttwreis_mef_external_files/",0777,TRUE);
									}
						
									$this->upload->initialize($config);
									
									if ( ! $this->upload->do_upload($index))
									{
										 echo "external file upload failed";
						        		 return FALSE;
									}
									else
									{
										$mef_files = $this->upload->data();
										//log_message('debug', 'mef_files=======5849'.print_r($mef_files, true));
										$rand_number = mt_rand();
										$mef_external_screening_data_array = array(
																"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
																"file_name" =>$mef_files['file_name'],
																"file_path" =>$mef_files['file_relative_path'],
																"file_size" =>$mef_files['file_size']
															)	);

										$mef_final = array_merge($mef_final,$mef_external_screening_data_array);
										
									}  
								}
							}
							 }
						 
						   if(isset($doc_history[0]['doc_data']['widget_data']['mef_attachments']))
							  {
									   
								$external_screening_merged_data = array_merge($doc_history[0]['doc_data']['widget_data']['mef_attachments'],$mef_final);
								$doc_data['mef_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['widget_data']['mef_attachments'],$external_screening_merged_data);
							  }
							  else
							 {
							    $doc_data['mef_attachments'] = $mef_final;
							 }
						   }else
						   {
						   		$doc_data['mef_attachments'] = [];
						   }

				/*END mef forms*/

			}
			/* End External attachements*/


		
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
	         /* echo "<pre>";
		echo print_r($doc_data,true);
		echo "</pre>";
		exit();*/

	 		
	  	$added = $this->ttwreis_cc_model->update_screening_report_model($uniqueid, $doc_data,$history);
	  	if ($added ) // the information has therefore been successfully saved in the db
		{
			$this->session->set_flashdata('success','Screening Data updated successfully !!');
			redirect('ttwreis_cc/update_student_info_view');
		}
		
		
	}

	public function get_searched_student_sick_requests()
	{
		$search_data = $this->input->post('search_value', true);

		$logged_in_user = $this->session->userdata("customer");
		$email          = $logged_in_user['email'];
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		
		$this->data = $this->ttwreis_cc_model->get_searched_student_sick_requests_model($search_data);

		$this->output->set_output(json_encode($this->data));
	}

}
