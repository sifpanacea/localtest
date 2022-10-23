<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Healthcare_app extends MY_Controller {

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
		$this->load->library('panacea_common_lib');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
		}

	function to_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$this->data = $this->panacea_common_lib->to_dashboard($date,$request_duration,$screening_duration);
		$this->output->set_output(json_encode($this->data));
	}
	function to_dashboard_device($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$this->data = $this->panacea_common_lib->to_dashboard_device($date,$request_duration,$screening_duration);
		$this->output->set_output(json_encode($this->data));
	}
	function to_dashboard_with_date()
	{
		// POST DATA
		$today_date         = $_POST['today_date'];
		$request_pie_span   = $_POST['request_pie_span'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name 			= $_POST["dt_name"];
		$school_name 		= $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		
		//log_message('debug','to_dashboard_with_date=====46====='.print_r($_POST,true));
		
		$this->data = $this->panacea_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);
		//log_message('debug','to_dashboard_with_date=====49====='.print_r($this->data,true));
		$this->output->set_output($this->data);
	}
	
	// function update_request_pie()
	// {
		// $today_date = $_POST['today_date'];
		// $request_pie_span = $_POST['request_pie_span'];
		// $this->data = $this->panacea_common_lib->update_request_pie($today_date,$request_pie_span);
	
		// $this->output->set_output($this->data);
	
	// }
	
	function update_screening_pie()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->panacea_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->panacea_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->panacea_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	
	
	function drilling_screening_to_abnormalities()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_districts()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drilling_screening_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students()
	{
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$docs = $this->panacea_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
		$screening_report = base64_encode(json_encode($docs));
		$this->output->set_output($screening_report);
	}
	
	function drill_down_screening_to_students_load_ehr()
	{	
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$docs_id = explode(',',$_POST['ehr_data_for_screening']);
		//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
		$get_docs = $this->panacea_common_model->get_drilling_screenings_students_docs($docs_id);
		$this->data['students'] = $get_docs;
		//set the flash data error message if there is one
	
		$this->output->set_output(json_encode($this->data));
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_id,true));
		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		////log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
		
		$this->data['docscount'] = count($this->data['docs']);
		$this->output->set_output(json_encode($this->data));
	}	
	
	public function drill_down_screening_to_students_load_ehr_doc_IOS($_id)
	{
		$doc_data = Array();
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_id,true));
		$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
		//log_message("debug",'----IOS---'.print_r($docs,true));
		//log_message("debug",'----IOS---'.print_r($docs['screening'][0]['doc_data']['widget_data'],true));
		foreach($docs['screening'][0]['doc_data']['widget_data'] as $pages)
		{
			//log_message("debug",'----IOS--page-'.print_r($pages,true));
			foreach($pages as $page)
			{
				//array_push($doc_data,$pages[$page]);
			}
		}
		$this->data['docs'] = $doc_data;//docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		//log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
		
		//$this->data['docscount'] = count($this->data['docs']);
		$this->output->set_output(json_encode($this->data));
	}
	
	function drilldown_absent_to_districts()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		//log_message("debug","ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp".print_r($_POST,true));
		$absent_report = json_encode($this->panacea_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drilling_absent_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$absent_report = json_encode($this->panacea_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students()
	{
		//log_message("debug","ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp".print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$docs = $this->panacea_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
		$absent_report = base64_encode(json_encode($docs));
		$this->output->set_output($absent_report);
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		//$temp = base64_decode($_GET['ehr_data_for_absent']);
		//$UI_id = $_POST['ehr_data_for_absent'];
		$UI_id = explode(',',$_POST['ehr_data_for_absent']);
		$get_docs = $this->panacea_common_model->get_drilling_absent_students_docs($UI_id);
		$this->data['students'] = $get_docs;
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->output->set_output(json_encode($this->data));
	}
	
	//========================================================================
	function drilldown_request_to_districts()
	{
		//log_message('debug','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$request_report = json_encode($this->panacea_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($request_report);
	}
	
	function drilling_request_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$request_report = json_encode($this->panacea_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$docs = $this->panacea_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
		$request_report = base64_encode(json_encode($docs));
		$this->output->set_output($request_report);
	}
	
	function drill_down_request_to_students_load_ehr()
	{
		//$UI_id = json_decode(base64_decode($_GET['ehr_data_for_request']),true);
		//log_message('debug','pooooooooooooooooooooooooooooooooooooooooooooooooost'.print_r($_POST,true));
		$UI_id = explode(',',$_POST['ehr_data_for_request']);
		//log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($UI_id,true));
		$get_docs = $this->panacea_common_model->get_drilling_request_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
		
		//log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r(json_encode($this->data),true));
		$this->output->set_output(json_encode($this->data));
	}
	
	function drilldown_identifiers_to_districts()
	{
		$data 				= $_POST['data'];
		$today_date 		= $_POST['today_date'];
		$request_pie_span 	= $_POST['request_pie_span'];
		$dt_name 			= $_POST["dt_name"];
		$school_name 		= $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->panacea_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($identifiers_report);
	}
	
	function drilling_identifiers_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->panacea_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students()
	{
		$data = $_POST['data'];
	
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$docs = $this->panacea_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		//$temp = base64_decode($_GET['ehr_data_for_identifiers']);
		//$UI_id = json_decode(base64_decode($_GET['ehr_data_for_identifiers']),true);
		//$UI_id = $_POST['ehr_data_for_identifiers'];
		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$UI_id = explode(',',$_POST['ehr_data_for_identifiers']);
		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($UI_id,true));
		$get_docs = $this->panacea_common_model->get_drilling_identifiers_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->output->set_output(json_encode($this->data));
	}
	
	public function get_schools_list()
	{		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->panacea_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	public function messaging()
	{
		$message = $_POST['message'];
		$uid = $_POST['unique_id'];
	
		$response = $this->data = $this->panacea_common_model->messaging($message,$uid);
		//$this->data = "";
		
		$this->output->set_output($response);
	}
	public function reports_display_ehr_uid()
	{
		$post = $_POST;
		$this->data = $this->panacea_common_lib->panacea_reports_display_ehr_uid($post);
	
		$this->output->set_output(json_encode($this->data));
	}

//=====================GCM part ==========================

  public function user($login)
  {
	    //log_message('debug','HEALTHCARE_APP=====USER=====LOGIN====='.print_r($login,true));
		if($login == "login")
		{
			// reading post params
			$name  = $_POST['name'];
			$email = $_POST['email'];
			
			$response = $this->panacea_common_model->get_user_by_email($name,$email);
		}
		else
		{
			//$userid = base64_decode($login);
			$userid = $login;
			
			//log_message('debug','HEALTHCARE_APP=====USER=====USERID====='.print_r($userid,true));
			
			parse_str(file_get_contents('php://input'), $this->params);
			//log_message('debug','HEALTHCARE_APP=====USER=====$this->params====='.print_r($this->params,true));
			
			$gcm_registration_id = $this->params['gcm_registration_id'];
			$response = $this->panacea_common_model->updateGcmID($userid,$gcm_registration_id);
			
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
		}

		//log_message('debug','HEALTHCARE_APP=====USER=====USERID====='.print_r($response,true));
		$this->output->set_output(json_encode($response));
	}
	
	// added by selva
	public function update_gcm_id()
    {
	    $userid              = $this->input->post('userid',true);
		$gcm_registration_id = $this->input->post('gcmid',true);
		
		$response = $this->panacea_common_model->updateGcmID($userid,$gcm_registration_id);
			
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
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Get accessible chat rooms ( for the loggedin user )
	 * 
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	public function chat_rooms($user_id = false, $message=false)
	{
		// LOGGEDIN USER
		$loggedinuser  = $this->session->userdata("customer");
		$loggedinemail = $loggedinuser['email'];
		
		if(!(isset($_POST['chat_room_id'])) && (!isset($_POST['message']))){
			
			$response = array();
			
			// fetching all user tasks
			$chat_rooms_result = $this->panacea_common_model->get_accessible_groups($loggedinemail);
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==415====='.print_r($chat_rooms_result,true));
			
			// fetching all user tasks
			$result = $this->panacea_common_model->get_all_groups();
			$response["error"]      = false;
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
		else if((isset($_POST['chat_room_id'])) && (!isset($_POST['message']))){
			
			$result = $this->panacea_common_model->get_messages($_POST['chat_room_id']);
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
		else{
			
			$this->data = $this->panacea_common_model->add_message($_POST,$_POST['chat_room_id']);
			
			//+++++++++++++++++++++GCM part +++++++++++++++++++++++++
			if ($this->data['error'] == false) {
				// get the user using userid
				$user = $this->get_user();
			
				$data = array();
				$data['user']         = $user;
				$data['message']      = $this->data['message'];
				$data['chat_room_id'] = $_POST['chat_room_id'];
			
				$this->push->setTitle("DashBoard");
				$this->push->setIsBackground(FALSE);
				$this->push->setFlag(PUSH_FLAG_CHATROOM);
				$this->push->setData($data);
			
				// echo json_encode($push->getPush());exit;
				// sending push message to a topic
				$this->gcm->sendToTopic('topic_' . $_POST['chat_room_id'], $this->push->getPush());
			
				$this->data['user'] = $user;
				$this->data['error'] = false;
			}
		} 
		
		/*if(($user_id === false) && ($message===false))
		{
			$response = array();
			// fetching all user tasks
			$result = $this->panacea_common_model->get_all_groups();
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
			
			$result = $this->panacea_common_model->get_messages($user_id);
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
			//$_POST['message'] = $message;
			$this->data = $this->panacea_common_model->add_message($_POST,$user_id);
			
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
		} */

		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$message==568====='.print_r($this->data,true));
		$this->output->set_output(json_encode($this->data));
	}
	
	function get_user()
	{
		$customer = $this->session->userdata("customer");
		$all_userdata = $this->session->all_userdata();
		//log_message('debug','HEALTHCARE_APP=====GET_USER=====$customer==462====='.print_r($customer,true));
		//log_message('debug','HEALTHCARE_APP=====GET_USER=====$all_userdata==463====='.print_r($all_userdata,true));
		
		$user["user_id"] = $customer['user_id'];
		$user["name"]    = $customer['username'];
		$user["email"]   = $customer['email'];
//		$user["gcm_registration_id"] = $gcm_registration_id;
		$user["created_at"] = $customer['registered'];
		
		return $user;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get data to draw sanitation report pie based on the selected criteria
	 *
	 *@author Selva 
	 */
	 
	function draw_sanitation_report_pie()
	{
	  // Check POST Data
	  if(isset($_POST['date']) && isset($_POST['que']) && isset($_POST['opt']))
	  {
		  // POST Data
		  $date 			= $this->input->post('date',TRUE);
		  $search_criteria  = $this->input->post('que',TRUE);
		  $opt              = $this->input->post('opt',TRUE);
		  
		  $search_criteria  = urldecode($search_criteria);
		  $search_criteria  = str_replace('#','.',$search_criteria);
		  $search_criteria  = str_replace('_',' ',$search_criteria);
		 
		  $search_criteria  = "doc_data.widget_data.".$search_criteria;
		  
		  $sanitation_report_pie = $this->panacea_common_model->get_sanitation_report_pie_data($date,$search_criteria,$opt);
		  
		  if($sanitation_report_pie)
		  {
		    $this->output->set_output(json_encode($sanitation_report_pie));
		  }
		  else
		  {
	        $this->output->set_output('NO_DATA_AVAILABLE');
		  }
		}
		else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: download file from device stage
	 *
	 *@author Selva 
	 */

   public function device_file_download()
   {
  	   $filedata = file_get_contents('php://input');
  	   $data     = json_decode($filedata,true);
  	   $path     = $data['path'];
       $this->external_file_download($path);   
   }
   
   	public function post_note() {
		
		$post = $_POST;
		if(isset($post['uid']) && isset($post['datetime']) && isset($post['note']) && isset($post['username'])){
			$token = $this->panacea_common_lib->insert_ehr_note($post);
			if($token){
				$note = json_encode($this->panacea_common_model->fetch_insert_ehr_note($post));
			}else{
				$note = false;
			}
		   
			$this->output->set_output($note);
		}else{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
		
	}
	
	public function delete_note() {
		
		
		$doc_id = $_POST["doc_id"];
		
		$token = $this->panacea_common_lib->delete_ehr_note($doc_id);
	   
		$this->output->set_output($token);
    }

    // SANITATION INFRASTRUCTURE
	public function get_sanitation_infrastructure()
	{
		// Variables
		$toilets            = array();
		$hand_sanitizers    = array();
		$disposable_bins    = array();
		$water_dispensaries = array();
		$children_seating   = array();
		$bar_chart_data     = array();
		
		//POST DATA
		$district_name  = $this->input->post('district_name',TRUE);
		$school_name    = $this->input->post('school_name',TRUE);
		
		$data = $this->panacea_common_model->get_sanitation_infrastructure_model($district_name,$school_name);
		
		if(isset($data) && !empty($data))
		{
		foreach($data as $index => $value)
		{
			$widget_data = $value['doc_data']['widget_data'];
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			$page5 = $widget_data['page5'];
			$page6 = $widget_data['page6'];
			
			// Toilets
			$buckets = array();
			$buckets['label'] = 'Buckets';
			$buckets['value'] = (int) $page1['Toilets']['Buckets'];
			array_push($toilets,$buckets);
			
			$mugs = array();
			$mugs['label'] = 'Mugs';
			$mugs['value'] = (int) $page1['Toilets']['Mugs'];
			array_push($toilets,$mugs);
			
			$dust_bin = array();
			$dust_bin['label'] = 'Dust Bins';
			$dust_bin['value'] = (int) $page1['Toilets']['Dust Bins'];
			array_push($toilets,$dust_bin);
			
			$soap = array();
			$soap['label'] = 'Soap';
			$soap['value'] = (int) $page1['Toilets']['Soap'];
			array_push($toilets,$soap);
			
			$running_water = array();
			$running_water['label'] = 'Running Water';
			$running_water['value'] = (int) $page4['Water Facility']['Running water(number of taps)'];
			array_push($toilets,$running_water);
			
			$store_water = array();
			$store_water['label'] = 'Store Water';
			$store_water['value'] = (int) $page4['Water Facility']['Store water'];
			array_push($toilets,$store_water);
			
			// Hand Sanitizers
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page2['Hand Wash']['Dining Halls'];
			array_push($hand_sanitizers,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page2['Hand Wash']['Kitchen'];
			array_push($hand_sanitizers,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Kitchen';
			$classroom['value'] = (int) $page2['Hand Wash']['Class Rooms'];
			array_push($hand_sanitizers,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page2['Hand Wash']['Dormitories'];
			array_push($hand_sanitizers,$dormitories);
			
			// Disposable Bins
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page3['Waste Management']['Dining Halls'];
			array_push($disposable_bins,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page3['Waste Management']['Kitchen'];
			array_push($disposable_bins,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page3['Waste Management']['Class Rooms'];
			array_push($disposable_bins,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page3['Waste Management']['Dormitories'];
			array_push($disposable_bins,$dormitories);
			
			// Water Dispensaries
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page4['Water Facility']['Dining Halls'];
			array_push($water_dispensaries,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page4['Water Facility']['Kitchen'];
			array_push($water_dispensaries,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page4['Water Facility']['Class Rooms'];
			array_push($water_dispensaries,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page4['Water Facility']['Dormitories'];
			array_push($water_dispensaries,$dormitories);
			
			// Children Seating
			$floor = array();
			$floor['label'] = 'Floor';
			$floor['value'] = (int) $page5['Dining Hall']['Floor'];
			array_push($children_seating,$floor);
			
			$table_chairs = array();
			$table_chairs['label'] = 'Table and Chairs';
			$table_chairs['value'] = (int) $page5['Dining Hall']['Table and Chairs'];
			array_push($children_seating,$table_chairs);
			
			$benches = array();
			$benches['label'] = 'Benches';
			$benches['value'] = (int) $page5['Dining Hall']['Benches'];
			array_push($children_seating,$benches);
		}
		
		$bar_chart_data['toilets']            = $toilets;
		$bar_chart_data['hand_sanitizers']    = $hand_sanitizers;
		$bar_chart_data['disposable_bins']    = $disposable_bins;
		$bar_chart_data['water_dispensaries'] = $water_dispensaries;
		$bar_chart_data['children_seating']   = $children_seating;
		}
		
		if(!empty($bar_chart_data))
		{
			$this->output->set_output(json_encode($bar_chart_data));
		}
		else
		{
			$this->output->set_output('NO_DATA_AVAILABLE');
		}
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Monthly chronic line graph
	 *
	 *@author Selva 
	 */

    function prepare_pill_compliance_monthly_graph()
	{

	  // Check POST Data
	  if(isset($_POST['unique_id']) && isset($_POST['case_id']) && isset($_POST['begin']) && isset($_POST['end']))
	  {
		  // POST DATA
		  $student_unique_id = $this->input->post('unique_id',TRUE);
		  $case_id           = $this->input->post('case_id',TRUE);
		  $begin           	 = $this->input->post('begin',TRUE);
		  $end               = $this->input->post('end',TRUE);

	      //log_message('debug','HEALTHCARE_APP==prepare_pill_compliance_monthly_graph===$_POST==>'.print_r($_POST,true));
		  
		  $start_date_array  = explode('-',$begin);
		  $new_start_d = $start_date_array[0]."-".$start_date_array[1]; 
		  $new_start_date = new DateTime($begin);
		  $new_start_date = $new_start_date->getTimestamp()*1000;
		  $new_start_day  = date("D M j G:i:s T Y",strtotime($begin));

		  $end_date_array  = explode('-',$end);
		  $new_end_d = $end_date_array[0]."-".$end_date_array[1]; 
		  $new_end_date = new DateTime($end);
		  $new_end_date = $new_end_date->getTimestamp()*1000;
		  $new_end_day  = date("D M j G:i:s T Y",strtotime($end));
	  
	  	  // Variables
		  $final_graph_data  = array();
		  $graph_data        = array();
		  $date_array        = array();
		  $values_array      = array();
			
		  $pcompl_raw_data   = $this->panacea_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
				       $value_date = new DateTime($schedule_date);
					   $Datestart      = new DateTime($begin);
					   $Dateend        = new DateTime($end);

				        if($value_date >= $Datestart && $value_date <= $Dateend )
				       {
						   $new_date = date("D M j G:i:s T Y",strtotime($schedule_date));
						   array_push($date_array,$new_date);
						   array_push($values_array,(int) $values['compliance']);
					   }
				  }
		  
				   $final_graph_data['start_date']  = $new_start_day;
				   $final_graph_data['end_date']    = $new_end_day;
				   $final_graph_data['dates']       = $date_array;
				   $final_graph_data['values']      = $values_array;
		   
				    //log_message('debug','update_compliance_percentage_model=====1='.print_r($final_graph_data,true));
				   
				   $this->output->set_output(json_encode($final_graph_data));
		      }
			  else
			  {
		       	$this->output->set_output('NO_DATA_AVAILABLE');
			  }
		    }
		  }
	    }
		else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	 
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Over all chronic line graph
	 *
	 *@author Selva 
	 */


	function prepare_pill_compliance_overall_graph()
	{
	  // POST DATA
	  $student_unique_id = $this->input->post('unique_id',TRUE);
	  $case_id           = $this->input->post('case_id',TRUE);
	  
	  // Variables
	  $final_graph_data  = array();
	  $graph_data        = array();
	  $month_wise_compliance = array();
	  $overallview_months  = array();
	  $overallview_percent = array();
		
	  $pcompl_raw_data   = $this->panacea_common_model->fetch_student_pill_compliance_data($case_id,$student_unique_id);
	
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
			  
		      foreach($pill_comp_data as $index_ => $values)
			  {
				   $schedule_date  = $values['date'];
				   $monName = date('F', strtotime($schedule_date));
				   $monDays = date('t', strtotime($schedule_date));
				   
				   if($date_details[0] === $monName)
				   {
					 ${$month_name."compliance_value"}+= (int) $values['compliance'];
				   }
			  }
			  
			  $percent = ${$month_name."compliance_value"}/$monDays;
			  
			  $pre_temp = array($month_name,(int) $percent);
			  array_push($overallview_months,$month_name);
			  array_push($overallview_percent,(int) $percent);
		  }
	  
	   $final_graph_data['start_date']  = $start_date;
	   $final_graph_data['end_date']    = $end_date;
	   $final_graph_data['months']      = $overallview_months;
	   $final_graph_data['percentage']  = $overallview_percent;
	   
	    //log_message('debug','prepare_pill_compliance_overall_graph=====6='.print_r($final_graph_data,true));
	   
	   $this->output->set_output(json_encode($final_graph_data));
	    }
		else
		{
	       $this->output->set_output('NO_DATA_AVAILABLE');
		}
	 }
	 
	 }
	 
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Chronic Pie
	 *
	 * @author Vikas 
	 *
	 * @return array 
	 */

	public function chronic_pie_view()
	{
		
		$count = 0;
		$request_report = $this->panacea_common_model->get_chronic_request();

		//log_message('error','HEALTHCARE_APP======CHRONIC_PIE_VIEW=====$REQUEST_REPORT==>'.print_r($request_report,true));

		foreach ($request_report as $value)
		{
			$count = $count + intval($value['value']);
		}

		if($count > 0)
		{
			$this->data['request_report'] = json_encode($request_report);
		}
		else
		{
			$this->data['request_report'] = 1;
		}

		//log_message('debug','HEALTHCARE_APP======CHRONIC_PIE_VIEW=====$THIS->DATA==>'.print_r($this->data,true));

		$this->output->set_output(json_encode($this->data));
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Update chronic request pie
	 *
	 * @author  Vikas ( Modified by Selva )
	 *
	 * @return string 
	 */

	public function update_chronic_request_pie()
	{
		// Check POST
		if(isset($_POST['status_type']))
		{
		   $status_type = $_POST["status_type"];
		   $this->data  = $this->panacea_common_lib->update_chronic_request_pie($status_type);
		   $this->output->set_output(json_encode($this->data));
		}
		else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Drill down chronic requests to symptoms
	 *
	 * @author  Vikas ( Modified by Selva )
	 *
	 * @return string 
	 */

	function drill_down_request_to_symptoms()
	{
		// Check POST
		if(isset($_POST['data']) && isset($_POST['status_type']))
		{
			$data 		 = $_POST['data'];
			$status_type = $_POST['status_type'];
	        $symptoms_report = json_encode($this->panacea_common_model->drill_down_request_to_symptoms($data,$status_type));
	        if($symptoms_report)
	        {
			   $this->output->set_output($symptoms_report);
		    }
		    else
		    {
               $this->output->set_output('ERROR');
		    }
		}
		else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Drill down chronic requests to districts
	 *
	 * @author  Vikas ( Modified by Selva )
	 *
	 * @return string 
	 */

	function drilldown_chronic_request_to_districts()
	{
		// Check POST
		if(isset($_POST['data']) && isset($_POST['status_type']))
		{
			$data 		 = $_POST['data'];
			$status_type = $_POST['status_type'];

			$identifiers_report = json_encode($this->panacea_common_model->drilldown_chronic_request_to_districts($data,$status_type));
			if($identifiers_report)
	        {
				$this->output->set_output($identifiers_report);
		    }
		    else
		    {
               $this->output->set_output('ERROR');
		    }
		}
		else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Drill down chronic requests to schools
	 *
	 * @author  Vikas ( Modified by Selva )
	 *
	 * @return string 
	 */

	function drilldown_chronic_request_to_school()
	{
		// Check POST DATA
		if(isset($_POST['data']) && isset($_POST['status_type']))
		{
			//log_message("debug","schoooooooooooooooooooooooooooooooooooooooo===============================".print_r($_POST,true));
			$data = $_POST['data'];
			$status_type = $_POST['status_type'];
			$request_report = json_encode($this->panacea_common_model->drilldown_chronic_request_to_schools($data,$status_type));
			if($request_report)
	        {
				$this->output->set_output($request_report);
		    }
		    else
		    {
				$this->output->set_output('ERROR');
		    }
	    }
	    else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Drill down chronic requests to students
	 *
	 * @author  Vikas ( Modified by Selva )
	 *
	 * @return string 
	 */

	function drilldown_chronic_request_to_students()
	{
		// Check POST DATA
		if(isset($_POST['data']) && isset($_POST['status_type']))
		{
			$data = $_POST['data'];
			$status_type = $_POST['status_type'];
			$docs = $this->panacea_common_model->drilldown_chronic_request_to_students($data,$status_type);
			if($docs)
			{
				$identifiers_report = base64_encode(json_encode($docs));
				$this->output->set_output($identifiers_report);
		    }
		    else
		    {
		    	$this->output->set_output('ERROR');
		    }
	    }
	    else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	
	function update_request_pie()
	{
		if(isset($_POST['today_date']) && isset($_POST['request_pie_span']) && isset($_POST['request_pie_status']))
		{
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$request_pie_status = $_POST['request_pie_status'];
			$this->data = $this->panacea_common_lib->update_request_pie($today_date,$request_pie_span,$request_pie_status);
		
			$this->output->set_output($this->data);
			
		}else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
		
	
	}
	
	
	public function post_note_request() {
		//log_message('debug','post_note_request=========='.print_r($_POST,true));
		if(isset($_POST['note']) && isset($_POST['username']) && isset($_POST['datetime']) && isset($_POST['doc_id']))
		{
			$post = $_POST;
			
			$token = $this->panacea_common_model->insert_request_note($post);
			
			if($token){
				$response = 'true';
			}else{
				$response = 'false';
			}
		   
			$this->output->set_output($response);
		}
	    else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
	
	public function delete_note_request() {
		
		if(isset($_POST['doc_id']) && isset($_POST['note_id']))
		{
			$post = $_POST;
			$token = $this->panacea_common_model->delete_request_note($post);
			if($token){
				$response = 'true';
			}else{
				$response = 'false';
			}
			$this->output->set_output($response);
		}
	    else
		{
			$this->output->set_output('REQUIRED_PARAMS_MISSING');
		}
	}
		
}
