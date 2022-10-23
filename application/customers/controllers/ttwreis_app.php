<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Ttwreis_app extends CI_Controller {

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
		$this->load->library('ttwreis_common_lib');
	}

	function to_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$this->data = $this->ttwreis_common_lib->to_dashboard($date, $request_duration, $screening_duration);
		$this->output->set_output(json_encode($this->data));
	}
	
	function to_dashboard_with_date()
	{
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
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$this->data = $this->ttwreis_common_lib->update_request_pie($today_date,$request_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function update_screening_pie()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->ttwreis_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->ttwreis_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->ttwreis_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	
	
	function drilling_screening_to_abnormalities()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->ttwreis_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{	
		log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$docs_id = explode(',',$_POST['ehr_data_for_screening']);
		//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		$get_docs = $this->ttwreis_common_model->get_drilling_screenings_students_docs($docs_id);
		$this->data['students'] = $get_docs;
		//set the flash data error message if there is one
	
		$this->output->set_output(json_encode($this->data));
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_id,true));
		$docs = $this->ttwreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['docscount'] = count($this->data['docs']);
		$this->output->set_output(json_encode($this->data));
	}
	
	function drilldown_absent_to_districts()
	{
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
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$absent_report = json_encode($this->ttwreis_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students()
	{
		log_message("debug","ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp".print_r($_POST,true));
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
		//$temp = base64_decode($_GET['ehr_data_for_absent']);
		//$UI_id = $_POST['ehr_data_for_absent'];
		$UI_id = explode(',',$_POST['ehr_data_for_absent']);
		$get_docs = $this->ttwreis_common_model->get_drilling_absent_students_docs($UI_id);
		$this->data['students'] = $get_docs;
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->output->set_output(json($this->data));
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		log_message('debug','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_report = json_encode($this->ttwreis_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
		$this->output->set_output($request_report);
	}
	
	function drilling_request_to_schools()
	{
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
		//$UI_id = json_decode(base64_decode($_GET['ehr_data_for_request']),true);
		log_message('debug','pooooooooooooooooooooooooooooooooooooooooooooooooost'.print_r($_POST,true));
		$UI_id = explode(',',$_POST['ehr_data_for_request']);
		log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($UI_id,true));
		$get_docs = $this->ttwreis_common_model->get_drilling_request_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
		
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r(json_encode($this->data),true));
		$this->output->set_output(json_encode($this->data));
	}
	
	function drilldown_identifiers_to_districts()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$identifiers_report = json_encode($this->ttwreis_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name));
		log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($identifiers_report,true));
		$this->output->set_output($identifiers_report);
	}
	
	function drilling_identifiers_to_schools()
	{
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
		//$temp = base64_decode($_GET['ehr_data_for_identifiers']);
		//$UI_id = json_decode(base64_decode($_GET['ehr_data_for_identifiers']),true);
		//$UI_id = $_POST['ehr_data_for_identifiers'];
		log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$UI_id = explode(',',$_POST['ehr_data_for_identifiers']);
		log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($UI_id,true));
		$get_docs = $this->ttwreis_common_model->get_drilling_identifiers_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_schools_list()
	{		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->ttwreis_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	//=====================GCM part ==========================

	// added by selva
	public function update_gcm_id()
    {
	    $userid              = $this->input->post('userid',true);
		$gcm_registration_id = $this->input->post('gcmid',true);
		
		$response = $this->ttwreis_common_model->updateGcmID($userid,$gcm_registration_id);
			
		if($response)
		{
			// User successfully updated
			$response["error"]   = false;
			$response["message"] = 'GCM registration ID updated successfully';
		}
		else
		{
			// Failed to update user
			$response["error"] = true;
			$response["message"] = "Failed to update GCM registration ID";
		}
		
		$this->output->set_output(json_encode($response));
	}
	
	public function chat_rooms($user_id = false, $message=false)
	{
		log_message('debug','TTWREIS_APP=====CHAT_ROOMS=====$user_id==360====='.print_r($user_id,true));
		log_message('debug','TTWREIS_APP=====CHAT_ROOMS=====$message==361====='.print_r($message,true));
		log_message('debug','TTWREIS_APP=====CHAT_ROOMS=====$message==362====='.print_r($_POST,true));
		
		if(($user_id === false) && ($message===false))
		{
			$response = array();
			// fetching all user tasks
			$result = $this->ttwreis_common_model->get_all_groups();
			$response["error"] = false;
			$response["chat_rooms"] = array();
			
			foreach ($result as $group)
			{
				$tmp = array();
				$tmp["chat_room_id"] = $group["group_name"];
				$tmp["name"] = $group["group_name"];
				$tmp["created_at"] = $group["created_at"];
				array_push($response["chat_rooms"], $tmp);
			}	
			$this->data = $response;
		}
		else if($message===false)
		{
			
			$result = $this->ttwreis_common_model->get_messages($user_id);
			$response["messages"] = array();
			$response['chat_room'] = array();
			
			$i = 0;
			foreach ($result as $msg)
			{
				// adding chat room node
				if ($i == 0) 
				{
					$tmp = array();
					$tmp["chat_room_id"] = $msg["chat_room_id"];
					$tmp["name"] = $msg["user_id"];
					$tmp["created_at"] = $msg["created_at"];
					$response['chat_room'] = $tmp;
					$i++;
				}
				if ($msg['user_id'] != NULL) 
				{
					// message node
					$cmt = array();
					$cmt["message"]    = $msg["message"];
					$cmt["message_id"] = $msg["message_id"];
					$cmt["created_at"] = $msg["created_at"];
			
					// user node
					$user = array();
					$user['user_id']      = $msg['user_id'];
					$user['username']     = $msg['user_name'];
					$cmt['user']          = $user;
			
					array_push($response["messages"], $cmt);
				}
			}
				
			$response["error"] = false;
			$this->data = $response;
		}
		else
		{
			$this->data = $this->ttwreis_common_model->add_message($_POST,$user_id);
			
			//+++++++++++++++++++++GCM part +++++++++++++++++++++++++
			if ($this->data['error'] == false) {
				// get the user using userid
				$user = $this->get_user();
			
				$data = array();
				$data['user'] = $user;
				$data['message'] = $this->data['message'];
				$data['chat_room_id'] = $user_id;
			
				$this->push->setTitle("DashBoard");
				$this->push->setIsBackground(FALSE);
				$this->push->setFlag(PUSH_FLAG_CHATROOM);
				$this->push->setData($data);
			
				// echo json_encode($push->getPush());exit;
				// sending push message to a topic
				$this->gcm->sendToTopic('topic_' . $user_id, $this->push->getPush());
			
				$this->data['user'] = $user;
				$this->data['error'] = false;
			}
		}

		$this->output->set_output(json_encode($this->data));
	}
	
	function get_user()
	{
		$customer = $this->session->userdata("customer");
		$all_userdata = $this->session->all_userdata();
		
		$user["user_id"] = $customer['identity'];
		$user["name"]    = $customer['username'];
		$user["email"]   = $customer['email'];
		$user["created_at"] = $customer['registered'];
		
		return $user;
	}
		
}

/* End of file signup.php */
/* Location: ./application/customers/controllers/patient_login.php */
