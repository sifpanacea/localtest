<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Device extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->model('device_model');
		$this->load->model('ion_auth_mongodb_model');
		$this->load->model('tswreis_schools_common_model');
		$this->load->helper('language');
		$this->config->load('email');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: get the new application details for logged in user
     *
     * 
     * @return array
     *  
     * @author Sekar 
     */

    function get_update_apps()
    {
    	header('Access-Control-Allow-Origin: *');
    	$username = $this->input->post('usersession');
		log_message("debug","username==========".print_r($username,true));
    	$usersession=$username.'_apps';
		log_message("debug","usersession==========37".print_r($usersession,true));
		$this->load->model('device_model');
    	$data=$this->device_model->update_apps($usersession);
    	$this->output->set_output(json_encode($data));
    }

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: get the new document details for logged in user
     *
     *
     * @return array
     *  
     * @author Sekar 
     */

    function get_update_docs()
    {
    	header('Access-Control-Allow-Origin: *');
    	$usernamedocs = $this->input->post('usersession');
		$ip_address = $this->session->userdata('ip_address');
		//log_message('error','ip_address========59====='.print_r($ip_address,true));
		//log_message('error','login user email======60====='.print_r($usernamedocs,true));
		//log_message('debug','GET_UPDATE_DOCS=====POST=====56====='.print_r($_POST,true));
    	$usersession=$usernamedocs.'_docs';
    	$this->load->model('device_model');
    	$data['uplist']=$this->device_model->update_docs($usersession);
    	$relacion=array();
    	foreach ($data['uplist'] as $details)
    	{
    		$relacion[] = $details;
    	}
		$this->output->set_output(json_encode($relacion));
    
    }

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: Install the app in device
     *
     *
     * @return array
     *  
     * @author Sekar 
     */

    function install_apps()
    {
    	header('Access-Control-Allow-Origin: *');
    	$appid = $this->input->post('appid');
    	$user1 = $this->input->post('username');
    	$user=$user1.'_apps';
		$this->load->model('device_model');
    	$data['installdata']=$this->device_model->install_data($appid,$user);
    	log_message('debug',print_r($data['installdata'],true));
    	$relacion=array();
    	foreach ($data['installdata'] as $details)
    	{
    		$relacion[] = $details;
    	}
    	
    	$this->output->set_output(json_encode($relacion));
		
	}
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: get the new push messages for logged in user
     *
     *
     * @return array
     *  
     * @author Selva 
     */

    function get_update_push_notifications()
    {   
	    // NEW MESSAGES
    	header('Access-Control-Allow-Origin: *');
    	$username = $this->input->post('usersession');
    	$usersession=$username.'_push_notifications';
		$this->load->model('device_model');
    	$data=$this->device_model->update_push_messages($usersession);
    	$this->output->set_output(json_encode($data));
		
		//SET STATUS AS READ
		$this->device_model->mark_read_push_notifications($usersession);
    }
   
  
		
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the new events details for logged in user
	*
	*
	* @return array
	*
	* @author Vikas
	*/

	function get_events()
	{
		//header('Access-Control-Allow-Origin: *');
		$username    = $this->input->post('usersession');
		$usersession = $username.'_calendar_events';
		$this->load->model('device_model');
		$data=$this->device_model->get_user_events($usersession);
		$this->output->set_output(json_encode($data));
	}
	
	/**
	* Helper: get the new events details for logged in user
	*
	*
	* @return array
	*
	* @author Vikas
	*/

	function get_feedbacks()
	{
		$username    = $this->input->post('usersession');
		$usersession = $username.'_feedbacks';
		$this->load->model('device_model');
		$data=$this->device_model->get_user_feedbacks($usersession);
		$this->output->set_output(json_encode($data));
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the new events details for logged in user
	*
	*
	* @return array
	*
	* @author Vikas
	*/

	function get_events_docs()
	{
	    // Data from device
        $postdata = file_get_contents('php://input');
        $data = json_decode($postdata,TRUE);
        $id = $data['id'];
        $username = $data['user_name'];
		$usersession = $username.'_event_docs';
		$this->load->model('device_model');
		$data=$this->device_model->get_user_event_by_id($usersession,$id);
		$this->output->set_output(json_encode($data));
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update the event response for specific user
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */

/*	function event_response()
	{
	    // Data from device
		$postdata = file_get_contents('php://input');
		$data     = json_decode($postdata);
		$id       = $data->id;
		$reply    = $data->reply;
		$username = $data->user_name;
		
		// Form user collection name
		$usersession = $username.'_calendar_events';
		
		$data=$this->device_model->update_user_event_response($usersession,$id,$reply);
		$this->output->set_output($data); 
	} */
	
	// ---------------------------------------------------------------------------------------

/**
* Helper: update the event response for specific user
*
* @return bool
*
* @author Selva
*/

function event_response()
{
// Data from device
$postdata = file_get_contents('php://input');
$data = json_decode($postdata,TRUE);
$id = $data['id'];
$reply = $data['reply'];
$form_status = $data['form_status'];
$username = $data['user_name'];
//log_message('debug','$reply======event_response=====197'.print_r($data['event_form'],true));

// Form user collection name
$usersession = $username.'_calendar_events';

// Form user collection for event document
$user_doc_col = $username.'_event_docs';

$model_data=$this->device_model->update_user_event_response($usersession,$id,$reply,$form_status);

log_message('debug','$reply======event_response=====206'.print_r($reply,true));

if($reply == 'Join' || $reply == 'May be' || $reply == 'Comment reply'){

$postdata = $data['event_form'];
log_message('debug','$postdata======event_response=====208'.print_r($postdata,true));

if (isset($postdata))
{
// Get the data
$imageData=$postdata;

// Remove the headers (data:,) part.
// A real application should use them according to needs such as to check image type
//$filteredData=substr($imageData, strpos($imageData, ',')+1);

//$this->_page_data = $filteredData;
// Need to decode before saving since the data we received is already base64 encoded
//$unencodedData=base64_decode($filteredData);

//echo 'unencodedData'.$unencodedData;

// Save file. This example uses a hard coded filename for testing,
// but a real application can specify filename in POST variable

//Seprating image data
//$imgData=substr($imageData, 0, strpos($imageData, '&')-1);

$array_data = json_decode($imageData, TRUE);

log_message('debug','$array_data======event_response=====237'.print_r($array_data,true));

if($array_data['doc_properties']['doc_id'] == 'new'){
	$doc_exist = false;
}else{
	$doc_exist = $this->device_model->doc_exist($user_doc_col,$array_data['doc_properties']['doc_id']);
}

if(!$doc_exist)
{
$this->device_model->user_event_create($user_doc_col,$array_data);
}
else
{
$this->device_model->user_event_update($user_doc_col,$array_data['doc_properties']['doc_id'],$array_data);
}
}

}else if($reply == 'Not yet replied'){
	log_message('debug','ghhhhhhhhhhhhhhhhdsuyfweryudsgvjhdbjlvfehpgiyhruiyg'.print_r($postdata,true));
	$postdata = $data['id'];
	log_message('debug','ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff'.print_r($postdata,true));
	if (isset($postdata))
	{
		// Get the data
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.print_r($user_doc_col,true));
		$this->device_model->user_event_remove($user_doc_col,$postdata);
	}
}
echo "event form submitted";
//$this->output->set_output($data);
}

// ---------------------------------------------------------------------------------------

/**
* Helper: update the event response for specific user
*
* @return bool
*
* @author Vikas
*/

function feedback_response()
{
	// Data from device
	$postdata    = file_get_contents('php://input');
	$data        = json_decode($postdata,TRUE);
	$id          = $data['id'];
	$reply       = $data['reply'];
	$form_status = $data['form_status'];
	$username    = $data['user_name'];
	

	// Form user collection name
	$usersession = $username.'_feedbacks';

	// Form user collection for feedback document
	$user_doc_col = $username.'_feedback_docs';

    $model_data=$this->device_model->update_user_feedback_response($usersession,$id,$reply,$form_status);

    $postdata = $data['feedback_form'];


	if (isset($postdata))
	{
		log_message('debug','posttttttttttttttttttdataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($postdata,true));
		// Get the data
		$imageData=$postdata;
		$array_data = json_decode(json_encode($imageData), TRUE);
		
		log_message('debug','posttttttttttttttttttdataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($array_data,true));
		$this->device_model->user_feedback_create($user_doc_col,$array_data);
    }


	echo "Feedback form submitted";
}

    // ---------------------------------------------------------------------------------------

	/**
	* Helper: Change Password
	*
	* @return bool
	*
	* @author Selva
	*/
  
    function change_password()
	{
		$email 		  = $this->input->post('email',TRUE);
		$old_password = $this->input->post('old_password',TRUE);
		$new_password = $this->input->post('new_password',TRUE);
		
		//log_message('debug','CHANGE_PASSWORD=====$_POST=====369====='.print_r($_POST,true));
		
		$change = $this->ion_auth_mongodb_model->change_device_user_password($email,$old_password,$new_password);
		
		if($change)
		{
			$this->output->set_output('CHANGE_PWD_SUCCESS');
		}
		else
		{
			$this->output->set_output('CHANGE_PWD_FAILED');
		}
	}
	
	public function post_note_request() {
		
		//log_message('debug','notessssssssssssssssspost=============================='.print_r($_POST,true));
		
		$post = $_POST;
		
		$token = $this->device_model->insert_request_note($post);
		
		if($token){
			$response = 'true';
		}else{
			$response = 'false';
		}
	   
		$this->output->set_output($response);
	}
	
	public function get_note_request() {
		
		//log_message('debug','notessssssssssssssssspost=============================='.print_r($_POST,true));
		
		$doc_id = $_POST['doc_id'];
		$app_id = $_POST['app_id'];
		
		$token = $this->device_model->get_request_note($doc_id,$app_id);
		
		$this->output->set_output(json_encode($token));
	}
	
	public function delete_note_request() {
		
		//log_message('debug','notesssssssssssssssssdelete=============================='.print_r($_POST,true));
		
		$post = $_POST;
		
		$token = $this->device_model->delete_request_note($post);
		
		if($token){
			$response = 'true';
		}else{
			$response = 'false';
		}
	   
		$this->output->set_output($response);
	}
	
	public function get_student_ehr()
    {
    	//log_message('debug','notesssssssssssssssssdelete=============================='.print_r($_POST,true));
		
		$post = $_POST;
		//log_message('error','postttttttttttttttt==============================440'.print_r($post,true));
		$docs = $this->device_model->screening_to_students_load_ehr_doc($post);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['docscount'] = count($this->data['docs']);
		
	   
		$this->output->set_output(json_encode($this->data));
    
    }
	
	public function get_student_req()
    {		
		$post = $_POST;
		
		$docs = $this->device_model->get_student_req($post);	
	   
		$this->output->set_output(json_encode($docs));
    
    }
    
    // ---------------------------------------------------------------------------------------

	/**
	* Helper: Upload audio file ( call recorded from the doctor stage )
	*
	* @return string
	*
	* @author Naresh
	*/

    public function upload_call_audio_file()
    {
    	log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$_POST==>'.print_r($_POST,true));
    	log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$_FILES==>'.print_r($_FILES,true));

    	if(isset($_POST['data']) && isset($_FILES))
    	{
    	   $this->load->library('upload');
    	   $device_upload_info = array();

    	   $array_data = json_decode($_POST['data'], TRUE);
    	   $app_id     = $array_data['app_id'];
    	   $doc_id     = $array_data['doc_id'];

    	   $config['upload_path'] 	= UPLOADFOLDERDIR.'public/uploads/'.$app_id.'/files/audio_files/';
		   $config['allowed_types'] = '*';
		   $config['max_size'] 		= '4096';
		   $config['encrypt_name']  = TRUE;

		   if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$app_id/files/audio_files/",0777,TRUE);
			}

		   $this->upload->initialize($config);

		   foreach($_FILES as $index => $value)
		   {
		   	  if(!empty($value['name']))
		   	  {
		   	  	if ( ! $this->upload->do_upload($index))
			    {
			  	     echo "FILE_UPLOAD_FAILED";
			  	     log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$ERROR==>'.print_r($this->upload->display_errors(),true));
			         return FALSE;
			    }
			    else
			    {
			        array_push($device_upload_info,$this->upload->data());
				}
		   	  }
		   }

 			$audio_file_info = array(
				  "file_client_name"    => $device_upload_info[0]['client_name'],
				  "file_encrypted_name" => $device_upload_info[0]['file_name'],
				  "file_path"           => $device_upload_info[0]['file_relative_path'],
				  "file_size"           => $device_upload_info[0]['file_size']
				  );
		    	
		   $res = $this->device_model->upload_call_audio_file_model($app_id,$doc_id,$audio_file_info);

		   if($res)
		   {
		   		$this->output->set_output('FILE_UPLOAD_SUCCESS');
		   }
		   else
		   {
		   		$this->output->set_output('FILE_UPLOAD_FAILED');
		   }
	    }
	    else
	    {
	    	$this->output->set_output('REQUIRED_PARAMS_MISSING');
	    }
    }

    // ---------------------------------------------------------------------------------------

	/**
	* Helper: get audio files ( call recorded from the doctor stage )
	*
	* @return string
	*
	* @author Selva
	*/

    public function get_call_audio_file()
    {
    	if(isset($_POST['app_id']) && isset($_POST['doc_id']))
    	{
	    	$doc_id = $_POST['doc_id'];
			$app_id = $_POST['app_id'];
			
			$audio_files = $this->device_model->get_call_audio_file_model($doc_id,$app_id);
			
			if($audio_files)
			{
				$this->output->set_output(json_encode($audio_files));
		    }
		    else
		    {
		    	$this->output->set_output('NO_AUDIO_FILES');
		    }
	    }
	    else
	    {
	    	$this->output->set_output('REQUIRED_PARAMS_MISSING');
	    }
    }

    // ---------------------------------------------------------------------------------------

	/**
	* Helper: Change Password ( For SIFNOTE Users - HS,Doctors )
	*
	* @return bool
	*
	* @author Selva
	*/
  
    function change_sifnote_user_password()
	{
		if(isset($_POST['email']) && isset($_POST['old_password']) && isset($_POST['new_password']))
		{
			$email 		  = $this->input->post('email',TRUE);
			$old_password = $this->input->post('old_password',TRUE);
			$new_password = $this->input->post('new_password',TRUE);
			
			$change = $this->ion_auth_mongodb_model->change_sifnote_user_password_model($email,$old_password,$new_password);
			
			if($change)
			{
				$this->output->set_output('CHANGE_PWD_SUCCESS');
			}
			else
			{
				$this->output->set_output('CHANGE_PWD_FAILED');
			}
	    }
	    else
	    {
	    	$this->output->set_output('REQUIRED_PARAMS_MISSING');
	    }
	}
	
	public function get_districts_list()
    {
		$this->data = $this->device_model->get_districts_list_model();
		$this->output->set_output(json_encode($this->data));
    }
 	/**
	* Helper: Get School List ( Based on Dist_id )
	*
	* @author Bhanu
	*/
    public function get_schools_list()
	{
		$dist_id = $_POST['district_id'];
		
		$this->data = $this->device_model->get_schools_by_district_id($dist_id);
	
		$this->output->set_output(json_encode($this->data));
	}
	
	function get_students_list_device()
	{
		$school = $_POST['school_name'];
		$students_lists = $this->tswreis_schools_common_model->get_students_list_device($school);
		
		$this->output->set_output(json_encode($students_lists));
	}



}
