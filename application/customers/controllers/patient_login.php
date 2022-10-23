<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_login extends CI_Controller {

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
		$this->load->model('patient_model');
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
		if ((! $this->ion_auth->logged_in())){
			redirect(URC.'patient_login/login');
		}
	
		$this->patient_dashboard();
	
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
		if ((! $this->ion_auth->logged_in())){
			redirect(URC.'patient_login/login');
		}
		
		$view_data = array();
		$u = $this->session->userdata("customer");
		$userID		 = $u['unique_id'];
		$documents = $this->patient_model->get_user_documents($userID);
		
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
			$recipient_list  = $this->input->post('multiselect',TRUE);
			$message         = $this->input->post('message',TRUE);
			
			$u = $this->session->userdata("customer");
			log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($u,true));
			$userID		 = $u['unique_id'];
			$company	 = $u['company'];

			foreach($recipient_list as $email)
			{
				$receiver   = str_replace("@","#",$email)."_push_notifications";
				$message_id = get_unique_id();
				$this->patient_model->send_push_message($receiver,$message_id,$message,$userID);
			}
	
			//$this->patient_model->push_message_save_history($message_id,$message,$company,$userID,$recipient_list);
			$this->session->set_flashdata('notification_message','Notification Sent !');
	
		}
		redirect('patient_login/patient_message');
	}


    // --------------------------------------------------------------------
	
	/**
	 * Patient dashboard after login 
	 *
	 * @author  Vikas
	 *
	 * 
	 */
	public function patient_dashboard()
	{
		if ((! $this->ion_auth->logged_in())){
			redirect(URC.'patient_login/login');
		}
		$view_data = array();
		$u = $this->session->userdata("customer");
		$userID		 = $u['unique_id'];
		$documents = $this->patient_model->get_user_documents($userID);
		
		foreach($documents as $doc_index => $doc_data)
		{
			$data['app_name'] = $doc_data['app_properties']['app_name'];
			$data['app_id']   = $doc_data['app_properties']['app_id'];
			$data['doc_id']   = $doc_data['doc_properties']['doc_id'];
			$data['doc_user'] = str_replace("#","@",$doc_data['doc_data']['user_name']);
			$data['doc_owner']= $doc_data['doc_properties']['doc_owner'];
			$data['doc_count']= $doc_data['pages'];
			$data['doc_ext_attachment']= $doc_data['doc_data']['external_attachments'];
			array_push($view_data,$data);
		}
		
			$this->data['documents'] = $view_data;
			$this->_render_page('patient_login/patient_dash', $this->data);
	
		
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
	 
	 // ---------------------------------------------------------------------------------------
	 
	 /**
	  * Helper: Upload templates
	  *
	  *
	  * @author Vikas
	  */
	 
	 function upload_attachments()
	 {
	 	log_message('debug','11111111111111111111111111111'.print_r($_FILES,true));
	 	//log_message('debug','222222222222222222222222222'.print_r($_POST,true));
	 	
	 	$app_id		= $_POST['app_id'];
	 	$doc_id		= $_POST['doc_id'];
	 	
	 	
	 	$array_data	= $this->patient_model->get_doc($app_id,$doc_id);
	 	
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
	 	//log_message('debug','33333333333333333333333333333333'.print_r($array_data,true));
	 	
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
	 		 
	 		$update = $this->patient_model->update_attributes($array_data['doc_properties']['doc_id'],$array_data);
	 	}
	 	
// 	 	$ds = DIRECTORY_SEPARATOR;
// 	 	$targetPath = TEMPLATES;
// 	 	if (!empty($_FILES))
// 	 	{	$check_status=0;
// 	 	$file_title=$_POST['title'];
// 	 	$file_description=$_POST['description'];
// 	 	$tempFile = $_FILES['file']['tmp_name'];
	 		
// 	 	$targetFile =  $targetPath.basename($_FILES['file']['name']);
// 	 	log_message('debug','tttttttttttttttttttttt'.print_r($targetFile,true));
// 	 	$thumb_path =  $targetPath.'thumb_'.basename($_FILES['file']['name']);
// 	 	$filename=$_FILES['file']['name'];
// 	 	$check_status=move_uploaded_file($tempFile,$targetFile);
// 	 	log_message('debug','cccccccccccccccccccccccccccccccccccccc'.print_r($check_status,true));
// 	 	if($check_status==1)
// 	 	{
// 	 		$this->image_thumb($filename,$targetFile);
// 	 		$this->load->model('template_model');
// 	 		$this->template_model->saveimage_url($filename,$targetFile,$thumb_path,$file_title,$file_description);
// 	 	}
// 	 	}
		//return false;
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
	 	if ((! $this->ion_auth->logged_in())){
	 		redirect(URC.'patient_login/login');
	 	}
	 
	 	$users = $this->patient_model->get_patient_users();
	 	
	 	$this->data['data'] = json_encode($users);
	 	$this->data['message'] = "Doctor details!";
	 	
	 	$this->_render_page('patient_login/patient_display_users',$this->data);
	 
	 }
	 
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
	 
	 /**
	  * 
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function get_user_appointment($email,$selected_date = false)
	 {
	 	if($selected_date){
	 	log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($email,true));
	 	log_message('debug','sssssssssssssssssssssssssssssssssssssssssss'.print_r($selected_date,true));
	 	
	 	$u = $this->session->userdata("customer");
	 	$patientID		 = $u['unique_id'];
	 	
	 	$email = str_replace('@', '#', base64_decode($email));
	 	$appointments = $this->patient_model->get_users_appointments_by_date($email.'_appointments',base64_decode($selected_date));
	 	
	 	log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($appointments,true));
	 	
	 	$slots_booked = array();
	 	$patient_appointments = array();
	 	$re_schedule = array();
	 	foreach ($appointments as $appointment){
	 		if($patientID == $appointment['patient_uniqueID']){
	 			$re_schedule[$appointment['appointment_time']]= new MongoId($appointment['_id']);
	 			array_push($patient_appointments, $appointment['appointment_time']);
	 		}else{
	 			array_push($slots_booked, $appointment['appointment_time']);
	 		}
	 	}
	 	
	 	log_message('debug','$re_schedule'.print_r($re_schedule,true));
	 	
	 	$begin = new DateTime("09:00");
	 	$end   = new DateTime("18:00");
	 	$interval = DateInterval::createFromDateString('15 min');
	 	$times    = new DatePeriod($begin, $interval, $end);
	 	
	 	log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($times,true));
	 	
	 	log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.gettype($times));
	 	log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.iterator_count($times));
// 	 	$table = "";
	 	
// 	 	foreach ($times as $time){
	 	
// 		 	$time_slot = $time->format('H:i').'-'.$time->add($interval)->format('H:i');
// 		 	if(in_array($time_slot,$slots_booked)){
// 		 		$table = $table."<tr class='danger'><td id='time'>".$time_slot.'</td><td><i>Appointment Booked</i></td></tr>';
// 		 	}else if(in_array($time_slot,$patient_appointments)){
// 		 		$table = $table."<tr class='warning '><td id='time'>".$time_slot.'</td><td><a href="#" time='.$time_slot." class='re_appointment' appointment_id='".$re_schedule[$time_slot]."' ><font color='red' id='re_sche' >Re-schedule appointment</font></a><span class='pull-right'><i class='fa fa-lg fa-fw fa-edit'></i><i class='fa fa-lg fa-fw fa-trash-o delete_appointment' appointment_id='".$re_schedule[$time_slot]."'></i></span></td></tr>";
// 		 	}else{
// 		 		$table = $table."<tr class='success'><td id='time'>".$time_slot.'</td><td><a href="#" time='.$time_slot." class='book_appointment'>Book appointment</a></td></tr>";
// 		 	}
// 	 	}
// 	 	$this->output->set_output($table);
	 	
	 	//========================================================================
	 	
	 	$table = '<div class="row">';
	 	if(iterator_count($times) > 4){
	 		$col_element = 0;
	 	foreach ($times as $time){
	 		 
	 		$time_slot = $time->format('H:i').'-'.$time->add($interval)->format('H:i');
	 		
	 		if(in_array($time_slot,$slots_booked)){
	 			
	 			$table = $table."<section class='col col-3'>
				<label class='input' id='time'>
					<font color='#616161' id='time' time=".$time_slot.">".$time_slot.'</font>
				</label></section>';
	 			
	 		}else if(in_array($time_slot,$patient_appointments)){
	 			
	 			$table = $table."<section class='col col-3'>
				<label class='input'>
					<font color='#01579B' id='time' class='re_appointment' time=".$time_slot." appointment_id='".$re_schedule[$time_slot]."'>".$time_slot.'</font><span class="pull-right"><i class="fa fa-lg fa-fw fa-edit"></i><i class="fa fa-lg fa-fw fa-trash-o delete_appointment" appointment_id="'.$re_schedule[$time_slot].'"></i></span>
				</label></section>';
	 			
	 		}else{
	 			
	 			$table = $table."<section class='col col-3'>
				<label class='input'>
					<font color='#00C853' class='book_appointment' id='time' time=".$time_slot.">".$time_slot.'</font>
				</label></section>';
	 		}
	 	}
	 	$col_element++;
		 	if($col_element == 4){
		 		$table = $table."</div>";
		 		$col_element = 0;
		 	}
	 	}else{
	 		foreach ($times as $time){
	 				
	 			$time_slot = $time->format('H:i').'-'.$time->add($interval)->format('H:i');
	 		
	 			if(in_array($time_slot,$slots_booked)){
	 			 	
	 				$table = $table."<section class='col col-3'>
				<label class='input'>
					<font color='#616161' >".$time_slot.'</font>
				</label></section>';
	 			 	
	 			}else if(in_array($time_slot,$patient_appointments)){
	 			 	
	 				$table = $table."<section class='col col-3'>
				<label class='input'>
					<font color='#01579B' class='re_appointment' appointment_id='".$re_schedule[$time_slot]."'>".$time_slot.'</font><span class="pull-right"><i class="fa fa-lg fa-fw fa-edit"></i><i class="fa fa-lg fa-fw fa-trash-o delete_appointment" appointment_id="'.$re_schedule[$time_slot].'"></i></span>
				</label></section>';
	 			 	
	 			}else{
	 			 	
	 				$table = $table."<section class='col col-3'>
				<label class='input'>
					<font color='#00C853' class='book_appointment' >".$time_slot.'</font>
				</label></section>';
	 			}
	 		}
	 		$table = $table."</div>";
	 	}
	 	
	 	$this->output->set_output($table);
	 	
	 	
	 	
	 }else{
	 	
	 	$this->output->set_output('no_date');
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
	 public function re_sche_appointment($selected_date,$time,$appointment_id,$user_id)
	 {
	 	//$this->patient_model->place_appointment($_POST);
	 	
	 	$date = base64_decode($selected_date);
	 	$time = base64_decode($time);
	 	$user_col = base64_decode($user_id).'_appointments';
	 	
	 	$app_update = $this->patient_model->update_appointment($user_col,$appointment_id,$date,$time);
	 	 
	 	if($app_update){
	 		$this->get_user_appointment($user_id,$selected_date);
	 	}else{
	 		$this->output->set_output(false);
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
	 public function place_appointment()
	 {
	 	if ((! $this->ion_auth->logged_in())){
	 		redirect(URC.'patient_login/login');
	 	}
	 	log_message('debug','ppotssssssssssssssssssssssssssssssstttttttttttttttt'.print_r($_POST,true));
	 	$this->patient_model->place_appointment($_POST);
	 	
	 	$this->display_patient_appointments();
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
	 	if ((! $this->ion_auth->logged_in())){
	 		redirect(URC.'patient_login/login');
	 	}
	 	
	 	$u = $this->session->userdata("customer");
	 	$patientID		 = $u['unique_id'];
	 
	 	$appointments = $this->patient_model->get_patient_appointments($patientID);
	 	 
	 	$this->data['data'] = json_encode($appointments);
	 	$this->data['message'] = "Doctor details!";
	 	 
	 	$this->_render_page('patient_login/patient_display_appointments',$this->data);
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  *
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 public function delete_appointment($appointment_id,$user_id,$selected_date = false)
	 {
	 	log_message('debug','ppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($appointment_id,true));
	 	log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.print_r($user_id,true));
	 	//$this->patient_model->place_appointment($_POST);
	 	$user_col = base64_decode($user_id).'_appointments';
	 	 
	 	$app_delete = $this->patient_model->delete_appointment($user_col,$appointment_id);
	 
	 	if($app_delete){
	 		if($selected_date){
	 			$this->get_user_appointment($user_id,$selected_date);
	 		}else{
	 			$this->display_patient_appointments();
	 		}
	 	}else{
	 		$this->output->set_output(false);
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
	 public function edit_appointment()
	 {
	 	
	 	$user_id = base64_decode($_POST['user_email']);
	 	$app_id = $_POST['app_id'];
	 	$app_title = $_POST['text'];
	 	
// 	 	$user_col = base64_decode($user_id).'_appointments';
	 	 
	 	$app_update = $this->patient_model->update_appointment_content($user_id,$app_id,$app_title);
	 
	 	if($app_update){
	 		$this->display_patient_appointments();
	 	}else{
	 		$this->display_patient_appointments();
	 	}
	 }
	 
	 public function secure_file_download($path)
	 {
	 	$path = str_replace('=','/',$path);
	 	$this->external_file_download($path);
	 }
		
}

/* End of file signup.php */
/* Location: ./application/customers/controllers/patient_login.php */