<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Field_officer_app extends MY_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Ben
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
		//$this->load->model('healthcare_app_model');
		$this->load->model('field_officer_app_model');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
	}
	
	function get_school_from_code($code = FALSE, $school_type = FALSE)
	{
		if($code){
			if($school_type){
				$this->data = $this->field_officer_app_model->get_school_from_code($code, $school_type);
			}else{
				$this->data = "No school type provided";
			}
		}else{
			$this->data = "No school code provided";
		}
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function get_ehr_from_uid($uid = FALSE, $school_type = FALSE)
	{
		if($uid){
			if($school_type){
				$this->data = $this->field_officer_app_model->get_ehr_from_uid($uid, $school_type);
			}else{
				$this->data = "No school type provided";
			}
		}else{
			$this->data = "No uid code provided";
		}
		
		$this->output->set_output(json_encode($this->data));
	}
	
	function device_submit()
	{
		
		log_message('debug','document================================================='.print_r($_POST['data'],true));
		log_message('debug','files================================================='.print_r($_FILES,true));
		
		
		
		$data = json_decode($_POST['data'],true);
		
		if($data["case_type"] == "Hospital"){
			
			$form_data = array(
				"hospital_name" => $data["hospital_name"],
				"school_type" => $data["school_type"],
				"student_id" => $data["student_id"],
			);
			
		}else if($data["case_type"] == "Dept"){
			
			$form_data = array(
				"department_name" => $data["department_name"],
			);
			
		}else if($data["case_type"] == "School"){
			
			$form_data = array(
				"school_type" => $data["school_type"],
				"school_code" => $data["school_code"],
			);
			
		}
		
		$newdata = $this->session->userdata('customer');
			$username  = $newdata['username'];
			$email     = $newdata['email'];
			
		$data = array(
			"doc_type" => $data["doc_type"],
			"case_type" => $data["case_type"],
			"doc_data"  => $form_data,
			
			"case_purpose" => $data["case_purpose"],
			"case_details" => $data["case_details"],
			"time"		   => date('Y-m-d H:i:s'),
			"username"	   =>$username,
			"email"		   =>$email
			
		);
		
		if(isset($_FILES))
		{
			$this->load->library('upload');
			
			$device_upload_info = array();
			$config				= array();
			
			foreach ($_FILES as $index => $value)
			{
				if ($value['name'] != '')
				{  
			        $config['upload_path'] 	= PROFILEUPLOADFOLDER.'uploads/healthcare/files/field_officer_uploads/';
					$config['allowed_types'] 	= '*';
					$config['max_size'] 		= '4096';
					$config['encrypt_name']     = TRUE;
		
					//create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(PROFILEUPLOADFOLDER."uploads/healthcare/files/field_officer_uploads/",0777,TRUE);
					}
					
					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload($index))
					{
						echo "file upload failed";
						return FALSE;
					}
					else
					{
						 $dataa = $this->upload->data();
						log_message('debug','file_upload_check==142'.print_r($dataa,true));
						array_push($device_upload_info,$this->upload->data());
					}
				}
		    }
			
			$data['attachments'] = $device_upload_info;
			
		}
		
		
		$this->data = $this->field_officer_app_model->submit_doc($data);
		$this->output->set_output(json_encode("success"));
	}
	
	function get_field_offiers()
	{
		$this->data = $this->field_officer_app_model->get_field_offiers();
		
		$this->output->set_output(json_encode($this->data));
	}
		
}
