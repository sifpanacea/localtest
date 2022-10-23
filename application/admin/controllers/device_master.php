<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Device_master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->config->load('email');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->library('mongo_db');
		$this->load->helper('paas');
		$this->load->library('support_common_lib');
		$this->load->model('device_master_model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Update support admin collections during crash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function declare_crash()
	{
      $user = array();

	  $postdata = file_get_contents('php://input');
	  $data     = json_decode($postdata);
	  
	  $device_unique_no  = $data->device_unique_no;
	  $application       = $data->application;
	  $log_details       = $data->log_details;
	  $crashed_time      = $data->crashed_time;
	  $device_firmware   = $data->device_firmware;
	  $log_received_time = date('Y-m-d H:i:s');
      $service_req_no    = "SUPPORT-".get_unique_id();

      $user_details = $this->device_master_model->get_user_by_device_unique_number($device_unique_no);
       
      foreach($user_details as $data)
	  {
	     array_push($user,$data['email']); 
	  }
	  
	  $result = $this->device_master_model->declare_crash($device_unique_no,$application,$log_details,$crashed_time,$device_firmware,$log_received_time,$service_req_no);
	  
	  if($result)
	  {
            
         // EMAIL TO TICKET OWNER
	     $this->support_common_lib->send_email_notification_to_user($user,$service_req_no,'ack');
					 
	     echo 'SUCCESS'; 
	  }
	  else
	  {
	     echo 'FAIL';
	  }
	  
    }
	
	// ------------------------------------------------------------------------
    /**
     * Helper: Check log for new update of apk
     *
     * @author Vikas
     */
    
     public function check_version()
    {
    	$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/change_apk_version.txt';
		log_message('debug','$path=====check_version'.print_r($path,true));
    	$this->external_file_download($path);
    	 
    }
	
	// ------------------------------------------------------------------------
    /**
     * Helper: Download DFF app securely
     *
     * @author Vikas
     */
    
    public function download_DFF()
    {
    	$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/DFF.apk';
    	$this->external_file_download($path);
    }
	
	// ------------------------------------------------------------------------
    /**
     * Helper: Download Tnote app securely
     *
     * @author Vikas
     */
    
    public function download_TNOTE()
    {
    	$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/Tnote.apk';
    	$this->external_file_download($path);
    }
	
	// ------------------------------------------------------------------------
	
	/**
     * Helper: Download MedNote ( PANACEA SCHOOLHEALTH PROGRAM ) app securely
     *
     * @author Selva
     */
    
    public function update_mednote($ver = FALSE)
    {
    	if($ver == FALSE)
		{
    		$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/SCHOOLHEALTH/MedNote.apk';
    		$this->external_file_download($path);
    	}
		else
		{
    		
			$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/SCHOOLHEALTH/MedNote.apk';
    		$this->external_file_download($path);
    	}
    	
    }
}
