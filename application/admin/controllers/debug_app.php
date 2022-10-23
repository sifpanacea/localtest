<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_app extends MY_Controller {

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
		$this->load->model('debug_app_model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Upload detailed log files to server
	 *
	 *  
	 * @author Selva 
	 */
	 
	function upload_all_logs()
	{
	  $postdata = $_POST['data'];
	  $data     = json_decode($postdata);
	  
	  $device_unique_no  = $data->device_unique_number;
	  $application       = $data->crashed_app;
	  $feedback          = $data->feedback;
	  $log_received_time = date('Y-m-d H:i:s');
	  
	  $log_id = get_unique_id();
	  
	  if(isset($_FILES))
    {
	    // File upload Configurations
        $config['upload_path']   = DETAILEDLOGS.$log_id;
	    $config['allowed_types'] = '*';
		$config['max_size']      = '10240';
		$config['encrypt_name']  = TRUE;
		
        $this->load->library('upload',$config);
		
		$log_files_upload_info = array();
		$log_final             = array();
			
		//create upload folder if not exists
		if (!is_dir($config['upload_path']))
		{
		   mkdir(DETAILEDLOGS.$log_id,0777,TRUE);
		}	
		
		foreach($_FILES as $index => $value)
	    {
			 if ($value['name'] != '')
			 {  
				$this->upload->initialize($config,$index);
				if ( ! $this->upload->do_upload($index))
				{
					 echo "file upload failed";
					 return FALSE;
				}
				else
				{
					$log_files_upload_info = $this->upload->data();
			
			        $data_array = array(
										"file_client_name" =>$log_files_upload_info['client_name'],
										"file_encrypted_name" =>$log_files_upload_info['file_name'],
										"file_path" =>$log_files_upload_info['file_relative_path']
										);

                    array_push($log_final,$data_array);
				}
			 } 
	    }
	}
	  
	  $result = $this->debug_app_model->upload_logs($device_unique_no,$application,$log_received_time,$log_final,$log_id,$feedback);
	  
	  if($result)
	  {
	     echo 'SUCCESS'; 
	  }
	  else
	  {
	     echo 'FAIL';
	  }
	   
		
	}

}