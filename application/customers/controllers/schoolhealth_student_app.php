<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_student_app extends CI_Controller {

	  function __construct()
	  {
	     
		 parent::__construct();
		
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('paas');

		 // Load MongoDB library instead of native db driver if required
		 $this->load->library('mongo_db');
		 $this->load->helper('cookie');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
         
		 $this->screening_app_col = "healthcare20161014212024617";
		 $this->lang->load('auth');
		 $this->load->helper('language');
		 $this->load->model('schoolhealth_student_app_model');
	 }
	 
	 // ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Fetch Student EHR  ( Showing detailed EHR view )
	 *
	 * @param  string  unique_id  Student's Hospital Unique ID
	 *
	 * @author Selva
	 */
	 
	 public function fetch_student_ehr_doc()
	 {
	    $unique_id = $this->input->post('unique_id',TRUE);
		$docs = $this->schoolhealth_student_app_model->fetch_student_ehr_doc_model($unique_id);
		$this->data['screening'] = $docs['screening'];
		$this->data['requests']  = $docs['request'];
		$this->output->set_output(json_encode($this->data));
	 }
	 
	// ------------------------------------------------------------------------
	 
	/**
	 * Helper : Change Password
	 *
	 * @author Selva
	*/
	
   function change_password()
   {
       // POST DATA
       $unique_id    = $_POST['unique_id'];
       $old_password = $_POST['old_password'];
       $new_password = $_POST['new_password'];
	   
       $change = $this->schoolhealth_student_app_model->change_password($unique_id,$old_password,$new_password);

	   if($change)
	   {
          $this->output->set_output('CHANGE_PWD_SUCCESS');
	   }
	   else
	   {
		  $this->output->set_output('CHANGE_PWD_FAILED');
	   }
	}
	
	public function calculate_bmi()
	{
	  $ehr_list = $this->schoolhealth_student_app_model->fetch_students_ehr_docs();
	  foreach($ehr_list as $index => $ehr_doc)
	  {
	     $bmi = "";
	     log_message('debug','$add_students_data_to_login_collection====1=='.print_r($ehr_doc,true));
	     foreach($ehr_doc['doc_data'] as $doc)
		 {
			$page1 = $doc['page1'];
			$page3 = $doc['page3'];
			
			$unique_id = $page1['Personal Information']['Hospital Unique ID'];
			
			$height = $page3['Physical Exam']['Height cms'];
			$weight = $page3['Physical Exam']['Weight kgs'];
			
			$height = (int) $height;
		    $weight = (int) $weight;
			
			log_message('debug','$add_students_data_to_login_collection====1=='.print_r($height,true));
			log_message('debug','$add_students_data_to_login_collection====1=='.print_r($weight,true));
					
		    if(($height > 0) && ($weight > 0))
		    {
			   $height = ($height/100);
			   $bmi    = ($weight / ($height * $height));
			   $bmi    = (int) $bmi;
			   $bmi    = round($bmi,1);
			   log_message('debug','$add_students_data_to_login_collection====1=='.print_r($bmi,true));
			}
		 }
		 
		 $this->schoolhealth_student_app_model->calculate_bmi_model($unique_id,$bmi);
	   
	  }
	
	}
	 
	/* public function add_students_data_to_login_collection()
	 {
	   $ehr_list = $this->schoolhealth_student_app_model->fetch_students_ehr_docs();
	   foreach($ehr_list as $index => $ehr_doc)
	   {
	     log_message('debug','$add_students_data_to_login_collection====1=='.print_r($ehr_doc,true));
	     foreach($ehr_doc['doc_data'] as $doc)
		 {
		    log_message('debug','$add_students_data_to_login_collection====2=='.print_r($doc,true));
			$page1 = $doc['page1'];
			$page2 = $doc['page2'];
			
		 }
		 $this->schoolhealth_student_app_model->add_students_data_to_login_collection_model($page1,$page2);
	   
	   }
	 }
	 */
	 
	 // --------------------------------------------------------------------

	 
	  // --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Upload attachments to existing EHR
	 *
	 * @param string $unique_id   Student Hospital Unique ID
	 *
	 * @author Selva
	 */
	 
	 public function upload_attachments_to_ehr()
	 {
	    // POST DATA
	    $unique_id  = $_POST['unique_id'];
		
		if(isset($_FILES))
		{
			$external_final             = array();
			$external_files_upload_info = array();
			$config 	                = array();
			
			$this->load->library('upload');
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$this->screening_app_col.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;
			
			if (!is_dir($config['upload_path']))
		    {
		      mkdir(UPLOADFOLDERDIR."public/uploads/$this->screening_app_col/files/external_files/",0777,TRUE);
		    }
			foreach($_FILES as $index => $value)
			{
			 if($value['name'] != '')
			 {
                $this->upload->initialize($config);
				
				if (!$this->upload->do_upload($index))
				{
					echo "FILE_UPLOAD_FAILED";
					return FALSE;
				}
				else
				{
					$external_files_upload_info = $this->upload->data();

					$external_data_array = array(
										  $index => array(
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
	    
		
		  $res = $this->schoolhealth_student_app_model->upload_attachments_to_ehr_model($unique_id,$external_final);
		  
		  if($res)
		      $this->output->set_output('UPLOAD_SUCCESS');
		  else
			  $this->output->set_output('UPLOAD_FAILED');
		}
	 }
	  // --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Download attachments 
	 *
	 * @param string $path   URL of the file
	 *
	 * @author Selva
	 */
	 
	 public function device_file_download()
	 {
	   $filedata = file_get_contents('php://input');
  	   $data     = json_decode($filedata,true);
  	   $path     = $data['path'];
       $this->external_file_download($path);  
	 }


	 public function device_news_msg()
	 {
	 	header('Access-Control-Allow-Origin: *');
		/* $this->data['title'] = "Causes of Heart palpitations";
		$this->data['url'] = "https://www.webmd.com/heart/news/20180723/heart-docs-analyze-trends-name-healthiest-foods#1";
		$this->data['img'] = "https://img.webmd.com/dtmcms/live/webmd/consumer_assets/site_images/articles/health_tools/what_are_the_causes_of_heart_palpitations_slideshow/493ss_thinkstock_rf_heart_palpitation_anatomy_concept.jpg"; */
		
		$this->data = $this->schoolhealth_student_app_model->get_news();
		
		//log_message('error',"device_news_msg=====228".print_r(json_encode($this->data),true));

		//$object = json_decode($data,true);
		//log_message('error',"device_news_msg=====230".print_r($object,true));
		$this->output->set_output(json_encode($this->data));
	 }
	 
    
}

/* End of file schoolhealth_student_app.php */
/* Location: ./application/customers/controllers/schoolhealth_student_app.php */