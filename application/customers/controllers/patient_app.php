<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_app extends MY_Controller {

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
		$this->load->model('patient_app_model');
		$this->load->library('mongo_db');
	}
	
	
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Helper : Logs the user out
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 function logout()
	 {
	 	$this->data['title'] = "Logout";
	 
	 	//log the user out
	 	$logout = $this->ion_auth->logout();
	 	
	 	//redirect them to the login page
	 	$this->session->set_flashdata('message', $this->ion_auth->messages());
	 	redirect(URC.'patient_login/login');
	 }
	 
	 // --------------------------------------------------------------------
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Sending user notification message
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function display_user_appointments($email,$username)
	 {
	 	if ((! $this->ion_auth->logged_in())){
	 		redirect(URC.'patient_login/login');
	 	}
	 	$email = str_replace('@', '#', base64_decode($email));
	 	$appointments = $this->patient_model->get_users_appointments($email.'_appointments');
	 	 
	 	$this->data['appointments'] = json_encode($appointments);
	 	$this->data['username'] = base64_decode($username);
	 	$this->data['user_email'] = $email;
	 	$this->data['message'] = "Doctor appointments!";
	 	
	 	$this->_render_page('patient_login/patient_users_appointments',$this->data);
	 
	 }
	 
// --------------------------------------------------------------------
	 
	 // --------------------------------------------------------------------
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	/* public function edit_appointment()
	 {
	 	
	 	$user_id = base64_decode($_POST['user_email']);
	 	$app_id = $_POST['app_id'];
	 	$app_title = $_POST['text'];
	 	$app_desc = $_POST['desc'];
	 	
// 	 	$user_col = base64_decode($user_id).'_appointments';
	 	 
	 	$app_update = $this->patient_model->update_appointment_content($user_id,$app_id,$app_title,$app_desc);
	 
	 	if($app_update){
	 		$this->display_patient_appointments();
	 	}else{
	 		$this->display_patient_appointments();
	 	}
	 } */
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Patient dashboard after login
	  *
	  * @author  Vikas
	  *
	  * session cookie
	  * 	[identity] => moonpharma100110015
	  [dob] => 2015-09-04
	  [patient_name] => jak
	  [user_id] => 5597c858a37261a40b000029
	  [company] => moonpharma
	  [mobile] => 7097740121
	  [grouped_under] => selva.r@tlstec.com
	  *
	  */
	 public function get_patient_records()
	 {
	 
	 	$view_data = array();
	 	$u = $this->session->userdata("customer");
		log_message('debug','$u=====my_controller=====18'.print_r($u,true));
	 	$userID		 = $u['identity'];
		log_message('debug','$userID=====my_controller=====18'.print_r($userID,true));
	 	$documents = $this->patient_app_model->get_user_documents($userID);
		log_message('debug','$documents=====my_controller=====18'.print_r($documents,true));
	 
	 	foreach($documents as $doc_index => $doc_data)
	 	{
	 		$data['app_name'] = $doc_data['app_properties']['app_name'];
	 		$data['app_id']   = $doc_data['app_properties']['app_id'];
	 		$data['doc_id']   = $doc_data['doc_properties']['doc_id'];
	 		$data['doc_user'] = str_replace("#","@",$doc_data['doc_data']['user_name']);
	 		$data['doc_owner']= $doc_data['doc_properties']['doc_owner'];
	 		$data['doc_count']= $doc_data['pages'];
	 			
	 		array_push($view_data,$data);
	 	}
	 
	 	$this->data['documents'] = $view_data;
	 	$this->output->set_output(json_encode($this->data));
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function get_external_attachment($app_id,$doc_id)
	 {
	 
	 	$attachments = $this->patient_app_model->get_external_attachment($app_id,$doc_id);
	 
	 	$this->data['attachments'] = $attachments[0]['doc_data']['external_attachments'];
	 
	 	$this->output->set_output(json_encode($this->data));
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Sending user notification message
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function display_all_users()
	 {
	 	$users = $this->patient_app_model->get_patient_users();
	 		
	 	$this->data['data'] = json_encode($users);
	 		
	 	$this->output->set_output(json_encode($this->data));
	 
	 }
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function get_user_appointment()
	 {
	 	$email = $_POST['email'];
	 	$selected_date = $_POST['date'];
	 	
	 	log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($email,true));
	 	log_message('debug','sssssssssssssssssssssssssssssssssssssssssss'.print_r($selected_date,true));
	 	 
	 	$u = $this->session->userdata("customer");
	 	$patientID		 = $u['identity'];
	 	
	 	log_message('debug','pppppppppiddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($patientID,true));
	 	
	 	$email = str_replace('@', '#', $email);
	 	$appointments = $this->patient_app_model->get_users_appointments_by_date($email.'_appointments',$selected_date);
	 	
	 	log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($appointments,true));
	 	
	 	
	 	
	 	$slots_booked = array();
	 	$patient_appointments = array();
	 	$re_schedule = array();
	 	foreach ($appointments as $appointment){
	 		if($patientID == $appointment['patient_uniqueID']){
	 			$re_schedule[$appointment['appointment_time']]= $appointment['_id']->{'$id'};
	 			array_push($patient_appointments, $appointment['appointment_time']);
	 		}else{
	 			array_push($slots_booked, $appointment['appointment_time']);
	 		}
	 	}
	 	log_message('debug','$re_schedule=====401=======PATIENT_APP'.print_r($re_schedule,true));
	 	$begin = new DateTime("09:00");
	 	$end   = new DateTime("18:00");
	 	$interval = DateInterval::createFromDateString('15 min');
	 	$times    = new DatePeriod($begin, $interval, $end);
	 	$table = array();
	 	 
	 	foreach ($times as $time){
	 		 
	 		$time_slot = $time->format('H:i').'-'.$time->add($interval)->format('H:i');
	 		if(in_array($time_slot,$slots_booked)){
	 			array_push($table, array('time' => $time_slot,'status' => 'booked'));
	 		}else if(in_array($time_slot,$patient_appointments)){
	 			array_push($table, array('time' => $time_slot,'status' => 'reschedule','appointment_id' => $re_schedule[$time_slot]));
	 		}else{
	 			array_push($table, array('time' => $time_slot,'status' => 'free'));
	 		}
	 	}
	 	$this->output->set_output(json_encode($table));
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Sending user notification message
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function patient_message()
	 {
	 
	 	$view_data = array();
	 	$u = $this->session->userdata("customer");
	 	$userID		 = $u['identity'];
	 	$documents = $this->patient_app_model->get_user_documents($userID);
	 
	 	foreach($documents as $doc_index => $doc_data)
	 	{
	 		//log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($doc_data['app_properties']['app_name'],true));
	 		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($doc_data['pages'],true));
	 		$data = str_replace("#","@",$doc_data['doc_data']['user_name']);
	 		array_push($view_data,$data);
	 	}
	 
	 	$this->data['documents'] = array_unique($view_data);
	 	$this->data['message'] = "Send notification to doctor!";
	 
	 	$this->_render_page('patient_login/patient_create_notifications',$this->data);
	 
	 }
	 
	 // ---------------------------------------------------------------------------------------
	 
	 /**
	  * Helper: get the new push messages
	  *
	  *
	  * @return array
	  *
	  * @author Vikas
	  */
	 
	 function get_update_push_notifications()
	 {
	 	$u = $this->session->userdata("customer");
	 	//log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.print_r($u,true));
	 	
	 	$userID		 = $u['identity'];
	 	$usersession=$userID.'_push_notifications';
	 	
	 	//Message
	 	$data['messages']=$this->patient_app_model->update_push_messages($usersession);
	 	$this->output->set_output(json_encode($data));
	 
	 	//SET STATUS AS READ
	 	$this->patient_app_model->mark_read_push_notifications($usersession);
	 }
	 
	 
	 // ------------------------------------------------------------------------
	 
	 /**
	  * Helper  Send notification to end users
	  *
	  * @author Vikas
	  */
	 
	 public function send_notification()
	 {
	 
	 	if(isset($_POST))
	 	{
	 		$recipient_list  = $_POST['recipient'];
	 		$message         = $_POST['message'];
	 			
	 		$u = $this->session->userdata("customer");
	 		$userID		 = $u['identity'];
	 		$company	 = $u['company'];
	 
 			$receiver   = str_replace("@","#",$recipient_list)."_push_notifications";
 			$message_id = get_unique_id();
 			$response = $this->patient_app_model->send_push_message($receiver,$message_id,$message,$userID);
 			log_message('debug','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($response,true));
 			if($response){
 				$this->output->set_output('succ');
 			}else{
 				$this->output->set_output('fail');
 			}
	 	}
	 }
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function place_appointment()
	 {
	 	 
		 log_message('debug','ppotssssssssssssssssssssssssssssssstttttttttttttttt'.print_r($_POST,true));
	 	$this->patient_app_model->place_appointment($_POST);
	 	 
	 	$this->output->set_output('succ');
	 }
	 
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function display_patient_appointments()
	 {
	 	$appointments = array();
	 	 
	 	$u = $this->session->userdata("customer");
	 	$patientID		 = $u['identity'];
	 
	 	$appointments['data'] = $this->patient_app_model->get_patient_appointments($patientID);
	 	
	 	$this->output->set_output(json_encode($appointments));
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function edit_appointment()
	 {
	 	log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($_POST,true));
	 	$user_id = $_POST['user_email'];
	 	$app_id = $_POST['app_id'];
	 	$app_title = $_POST['text'];
	 	 
	 	// 	 	$user_col = base64_decode($user_id).'_appointments';
	 
	 	$app_update = $this->patient_app_model->update_appointment_content($user_id,$app_id,$app_title);
	 
	 	if($app_update){
	 		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');
	 		$this->output->set_output('succ');
	 	}else{
	 		log_message('debug','ffffffffffffffffffffffffffffffffffffffffffffffffffff');
	 		$this->output->set_output('fail');
	 	}
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function delete_appointment()
	 {
	 	log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($_POST,true));
	 	$user_id = $_POST['user_email'];
	 	$appointment_id = $_POST['app_id'];
	 	
	 	$user_col = $user_id.'_appointments';
	 
	 	$app_delete = $this->patient_app_model->delete_appointment($user_col,$appointment_id);
	 
	 	if($app_delete){
	 		$this->output->set_output('succ');
	 	}else{
	 		$this->output->set_output('fail');
	 	}
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function re_sche_appointment()
	 {
	 	log_message('debug','reeeeeeeeeeeeeerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrreeeee'.print_r($_POST,true));
	 	$date			= $_POST['selected_date'];
	 	$time			= $_POST['time'];
	 	$user_col		= str_replace('@', '#', $_POST['user_email']).'_appointments';
	 	$appointment_id = $_POST['appointment_id'];

	 	$app_update = $this->patient_app_model->update_appointment($user_col,$appointment_id,$date,$time);
	 
	 	if($app_update){
	 		$this->output->set_output('succ');
	 	}else{
	 		$this->output->set_output('fail');
	 	}
	 }
	 
	 // ---------------------------------------------------------------------------------------
	 
	 /**
	  * Helper: download file from device stage
	  *
	  *@author Vikas
	  */
	 
	 public function device_file_download()
	 {
	 	$filedata = file_get_contents('php://input');
	 	log_message('debug','ffffffffffffffffffffffffffffffffffffffffffff'.print_r($filedata,true));
	 	$data     = json_decode($filedata,true);
	 	log_message('debug','reeeeeeeeeeeeeerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrreeeee'.print_r($data,true));
	 	$path     = $data['path'];
	 	$this->external_file_download($path);
	 }
	 
	 // ---------------------------------------------------------------------------------------
	 
	 /**
	  * Helper: Upload templates
	  *
	  *
	  * @author Vikas
	  */
	 
	 function upload_attachments($app_id,$doc_id)
	 {
	 	log_message('debug','11111111111111111111111111111'.print_r($_FILES,true));
	 	log_message('debug','222222222222222222222222222'.print_r($_POST,true));
	 	log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($app_id,true));
	 	log_message('debug','ddddddddddddddddddddddddddddddddddddd'.print_r($doc_id,true));
	 	 
	 	$app_id		= $app_id;
	 	$doc_id		= $doc_id;
	 	 
	 	 
	 	$array_data	= $this->patient_app_model->get_doc($app_id,$doc_id);
	 	 
	 	$app_con = $app_id.'_con';
	 	 
	 	$this->load->library('upload');
	 	$external_files_upload_info = array();
	 	$external_final             = array();
	 	$ext_id						= get_unique_id();
	 	 
	 	$config = array();
	 	 
	 	$config['upload_path'] = UPLOADFOLDERDIR.'public/uploads/'.$app_con.'/files/external_files/';
	 	$config['allowed_types'] = '*';
	 	$config['max_size'] = '4096';
	 	$config['encrypt_name'] = TRUE;
	 	//create controller upload folder if not exists
	 	if (!is_dir($config['upload_path']))
	 	{
	 		mkdir(UPLOADFOLDERDIR."public/uploads/$app_con/files/external_files/",0777,TRUE);
	 	}
	 	 
	 	$this->upload->initialize($config);
	 	//log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffffffff'.print_r($_FILES,true));
	 	 
	 	foreach ($_FILES as $index => $values){
	 		if ( ! $this->upload->do_upload($index))
	 		{
	 			//log_message('debug','9999999999999999999999999999999999999');
	 			echo "attachment file upload failed";
	 			return FALSE;
	 		}
	 		else
	 		{
	 			$external_files_upload_info = $this->upload->data();
	 			$external_data_array = array(
	 					$ext_id => array(
	 							"file_client_name" =>$external_files_upload_info['client_name'],
	 							"file_encrypted_name" =>$external_files_upload_info['file_name'],
	 							"file_path" =>$external_files_upload_info['file_relative_path'],
	 							"file_size" =>$external_files_upload_info['file_size']
	 					)
	 			);
	 			$external_final = array_merge($external_final,$external_data_array);
	 
	 		}
	 
	 		//log_message('debug','9999999999999999999999999999999999999'.print_r($external_final,true));
	 
	 		if(isset($array_data['doc_data']['external_attachments']))
	 		{
	 			//log_message('debug','666666666666666666666666666'.print_r($array_data['doc_data']['external_attachments'],true));
	 			$external_merged_data = array_merge($array_data['doc_data']['external_attachments'],$external_final);
	 			$array_data['doc_data']['external_attachments'] = array_replace_recursive($array_data['doc_data']['external_attachments'],$external_merged_data);
	 		}
	 		else
	 		{
	 			//log_message('debug','77777777777777777777777777777777');
	 			$array_data['doc_data']['external_attachments'] = $external_final;
	 		}
	 
	 		//log_message('debug','88888888888888888888888888888888');
	 	 	
	 		$user=$array_data['doc_data']['user_name'].'_docs';
	 	 	
	 		$user    = $this->session->userdata("customer");
	 		$company = $user['company'];
	 		//log_message('debug','555555555555555555555555555555555555555555555555555'.print_r($user,true));
	 	 	
	 		$update = $this->patient_app_model->update_attributes($array_data['doc_properties']['doc_id'],$array_data);
	 		
	 		if($update){
	 			$this->output->set_output('succ');
	 		}else{
	 			$this->output->set_output('fail');
	 		}
	 	}
	 }
	 
	 // ------------------------------------------------------------------------
	 
	 /**
	  * Helper  Graph Data
	  *
	  * @author Selva
	  */
	 
	 public function store_patient_vital_graph_values()
	 {
	    $patient_id    = $_POST['patient_id'];
	 	$graph_data	   = $_POST['graph_data'];
		$initial_stage = $_POST['initial_stage'];
		
		$graph_data = json_decode($graph_data);
		
		$response = $this->patient_app_model->store_patient_vital_graph_values_model($patient_id,$graph_data,$initial_stage);
		
		if($response)
		{
	      $this->output->set_output('SUCCESS');
		}
		else
		{
	      $this->output->set_output('FAIL');
		}
		
	}
	
	// ------------------------------------------------------------------------
	 
	 /**
	  * Helper  Graph Data
	  *
	  * @author Selva
	  */
	 
	 public function reset_patient_vital_graph()
	 {
	    $patient_id = $_POST['patient_id'];
	 	
		$response = $this->patient_app_model->reset_patient_vital_graph_model($patient_id);
		
		if($response)
		{
	      $this->output->set_output('SUCCESS');
		}
		else
		{
	      $this->output->set_output('FAIL');
		}
		
	}
		
}

/* End of file signup.php */
/* Location: ./application/customers/controllers/patient_login.php */