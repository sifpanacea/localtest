<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Field_agent extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		
		$this->load->library('ion_auth');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('language');
		$this->load->library('excel');
		$this->load->library('bhashsms');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->lang->load('ion_auth');
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		
		$this->load->model('field_agent_model');

	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Default page after login
	 *  
	 * @author Vikas 
	 */

	function index()
	{
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'auth/login');
		}
		else
		{
			$docs = $this->field_agent_model->get_dup_docs("healthcare2016226112942701");
			
			log_message('debug','matched_docssssssssssssssssssssssssssss'.print_r($docs,true));
			
			$this->data['docs'] = json_encode($docs);
			
			$this->_render_page('field_agent/field_agent_dash', $this->data);
		}
	}
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Navigate to dashboard
	 *  
	 * @author Vikas 
	 */

    function to_dashboard()
	{
	   	$this->check_for_admin();
	   	$this->check_for_plan('to_dashboard');

        $total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,5);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);

		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

        // other analytics values
		$data = $this->paas_common_lib->admin_dashboard_analytics_values();
		$this->data = array_merge($this->data,$data);	 

		$this->data['message'] = ''; 		

		$this->_render_page('field_agent/field_agent_dash', $this->data);
	   
	}

	//***************Application Specification***************END***************//
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Download Files securely
	 *  
	 * @author Vikas 
	 */

	 public function external_file_download_call()
	 {
		$path = $_GET['path'];
		$this->external_file_download($path);
	 }

	 public function secure_file_download($path)
	 {
	 	$path = str_replace('=','/',$path);
        $this->external_file_download($path);
	 }
	 
	 public function send_sms()
	 {
		
		$reply = $this->bhashsms->send_sms("9789779801","Demo Application 1 created... Download in your device !");
		echo $reply;
	 }
	 
	 //=Compare records of given application=====
	 
	 function doc_comp($document1, $document2){
	 	
	 	log_message('debug','iddddddddddddddddddddddddddddddddddddddd'.print_r($document1,true).print_r($document2,true));
	 	$doc1 = $this->field_agent_model->get_document("healthcare2016226112942701",$document1);
	 	$doc2 = $this->field_agent_model->get_document("healthcare2016226112942701",$document2);
	 	
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
	 
	 
	 	$this->_render_page('field_agent/field_agent_doc_comp', $this->data);
	 	 
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
	 	
	 	$this->index();
	 }
	 
	 function diff_docs(){
	 	$doc_id = $this->input->post('doc_ids');
	 	log_message('debug','doc_iiiiddddddddddddddddddddd'.print_r($doc_id,true));
	 	
	 	redirect("field_agent/doc_comp/$doc_id[0]/$doc_id[1]");
	 	
// 	 	$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get("healthcare2016226112942701");
// 	 	$query_ins = $this->mongo_db->insert("healthcare2016226112942701_duplicate",$query[0]);
	 	 
// 	 	if ($query_ins) {
// 	 		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->delete("healthcare2016226112942701");
// 	 	}
	 	 
// 	 	$this->index();
	 }
	 
	 function show_all_docs($ad_no = ""){
	 	
	 	log_message('debug','idsssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($ad_no,true));
	 	$this->data['docs'] = json_encode($this->field_agent_model->get_all_docs_in_ad_no('healthcare2016226112942701',$ad_no));
	 	
	 	$this->_render_page('field_agent/field_agent_show_all_docs', $this->data);
	 }
	 
	 function db_to_excel(){
	 	$striped_doc = array();
	 	$doc_mini = array();
	 	$docs = $this->mongo_db->limit(2)->get("test_doc_comp");
	 	foreach ($docs as $doc){
	 		foreach ($doc["doc_data"]["widget_data"] as $page_no => $page){
	 			$doc_mini[$page_no] = [];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page1"]["Personal Information"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page2"]["Personal Information"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page4"][];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page5"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page6"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page7"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page8"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page9"];
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
	 		//$doc_mini = array_merge($doc_mini, $page);
	 		foreach ($page as $sec_name => $sec){
	 			
	 			if(array_key_exists($sec_name, $doc_mini[$page_no])){
	 				//usort($sec, "cmp");
	 				ksort($sec);
	 				log_message('debug','0000000000000000000000000000000000000000000000000'.print_r($sec,true));
	 				
	 				$doc_mini[$page_no][$sec_name] = array_merge($doc_mini[$page_no][$sec_name], $sec);
	 			
	 			}else{
	 				//usort($page, "cmp");
	 				array_multisort($page, SORT_ASC);
	 				log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($page,true));
	 				
	 				$doc_mini[$page_no] = array_merge($doc_mini[$page_no], $page);
	 				
	 			}
	 			
	 		}
	 		
	 	}
	 	array_push($striped_doc, $doc_mini);
	 	}
	 	
	 	log_message('debug','1111111111111111111111111111111111111111111'.print_r($striped_doc,true));
	 	log_message('debug','2222222222222222222222222222222222222222222'.print_r(json_encode($striped_doc),true));
	 	
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
			if($row <16){
			//foreach ($doc["doc_data"]["widget_data"] as $page_no => $page)
			{
				//foreach ($page as $sec_name => $sec)
				{
				//=======================================
				if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']))
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']);
					
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_client_name']))
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_client_name']);
					
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
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']))
					$objPHPExcel->getActiveSheet()->SetCellValue('R'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']));
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']))
					$objPHPExcel->getActiveSheet()->SetCellValue('S'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']);
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']))
					$objPHPExcel->getActiveSheet()->SetCellValue('T'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']);
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']))
					$objPHPExcel->getActiveSheet()->SetCellValue('U'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']));
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']))
					$objPHPExcel->getActiveSheet()->SetCellValue('V'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']))
					$objPHPExcel->getActiveSheet()->SetCellValue('W'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']))
					$objPHPExcel->getActiveSheet()->SetCellValue('X'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']))
					$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']))
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
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']));
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']));
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']))
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
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AU'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']));
				}
				
				//---------------------------------------
				}
			}
			
			$row ++;
			}else{
				$this->to_dashboard();
			}
		}
		
		// Save Excel 2007 file
		echo date('H:i:s') . " Write to Excel2007 format\n";
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("G:/SkyDrive/PaaS/bootstrap/dist/excel/document.xlsx");
		
		// Echo done
		echo date('H:i:s') . " Done writing file.\r\n";
		
		$this->secure_file_download("G:/SkyDrive/PaaS/bootstrap/dist/excel/document.xlsx");
		
		unlink("G:/SkyDrive/PaaS/bootstrap/dist/excel/document.xlsx");
		
	 }
	 
	 function db_filter(){
	 	
	 	$docs_count = $docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name", "")->get("healthcare2016226112942701");
	 	
	 	$docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name", "")->get("healthcare2016226112942701");
	 	
	 	$docs = json_decode(json_encode($docs),true);
	 	$dup = 0;
	 	$dup_AD_no = [];
	 	foreach ($docs as $doc)
	 	{
	 		if(!isset($doc["device_properties"]))// && $doc["doc_data"]["widget_data"]['page2']['Personal Information']['Class'] == 'MPC')
	 		{
	 			//log_message('debug','docccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r(count($docs),true));
	 			$dup_doc_count = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.AD No" => $doc["doc_data"]["widget_data"]['page2']['Personal Information']['AD No']))->count("healthcare2016226112942701");
	 			if($dup_doc_count == 2)
	 			{
	 				$dup ++;
	 				array_push($dup_AD_no, $doc["doc_data"]["widget_data"]['page2']['Personal Information']['AD No']);
	 				$query = $this->mongo_db->where("_id", new MongoId($doc['_id']['$id']))->get("healthcare2016226112942701");
	 				$query = $this->mongo_db->insert("healthcare2016226112942701_dup",$query[0]);
	 				if($query)
	 					$query = $this->mongo_db->where("_id", new MongoId($doc['_id']['$id']))->delete("healthcare2016226112942701");
	 				
	 				//log_message('debug','docccccccccccc'.print_r($query,true));
	 			}
	 		}
	 	}
	 	
	 	log_message('debug','for class-------------------------------------'.print_r("All classes",true));
	 	log_message('debug','number of dup---------------------------------'.print_r($dup,true));
	 	//log_message('debug','docccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r(count($docs),true));
	 	log_message('debug','AD No ----------------------------------------'.print_r($dup_AD_no,true));
	 }
	 
	 // ---------------------------------------------------------------------------------------
	 
	 /**
	  * Helper: Computes the subscription days left count
	  *
	  *
	  * @author Vikas
	  */
	 
	 function dateDifference()
	 {
	 	$session_flag = $this->ajax_session_validation();
	 
	 	if($session_flag == "true")
	 	{
	 		$det     = $this->session->userdata("customer");
	 		$company = $det['company'];
	 			
	 		$this->load->model('reader_Model');
	 		$expirydate = $this->reader_Model->expiry_date($company);
	 		$expiryday = strtotime($expirydate);
	 		$currentday = strtotime(date("Y-m-d"));
	 		$daysleftt = $expiryday - $currentday;
	 		$dayss = floor($daysleftt/3600/24);
	 		$this->output->set_output(json_encode($dayss));
	 	}
	 	else
	 	{
	 		$this->output->set_output($session_flag);
	 	}
	 		
	 }
	 
	 /**
	  * Helper: Username of logged in user
	  *
	  * @author Vikas
	  */
	 
	 public function username()
	 {
	 	$session_flag = $this->ajax_session_validation();
	 	if($session_flag == "true")
	 	{
	 		$user = $this->ion_auth->user()->row();
	 		$name = $user->username;
	 		$this->output->set_output(json_encode($name));
	 	}
	 	else
	 	{
	 		$this->output->set_output($session_flag);
	 	}
	 }

}
