<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_admin_calendar extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('mongo_db');
		$this->load->library('calendar');
		$this->load->model('sub_admin_calendar_Model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->helper('language');
	}
	
   // ------------------------------------------------------------------------

	/**
	 * Helper: renders calendar page 
	 *  
	 * @author Selva 
	 */
	 
	function index()
	{
		$u = $this->session->userdata("customer");
		$email = $u['email'];
		$this->data['email'] = $email;
		
	  $this->data['message'] = "Calendar Page";
	  $this->data['event'] = $this->session->flashdata('event_id');
	  $this->_render_page('sub_admin/new_calendar', $this->data);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Get users list (of respective enterprise) to populate in workflow part
	 *
	 * @author  Selva
	 *
	 *
	 */
	
	function get_user_list()
	{
		$name = $this->input->post('name');
		$this->load->model('Workflow_Model');
		$data['users']=$this->Workflow_Model->users($name);
		$userlist=array();
		foreach ($data['users'] as $user)
		{
			$userlist[] = $user['email'];
	
		}
		$users = array_values($userlist);
		$user = str_replace("@", "#", $users);
		$this->output->set_output(json_encode(array_unique($user)));
	
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Get groups list (of respective enterprise) to populate in workflow part
	 *
	 * @author  Selva
	 *
	 *
	 */
	
	function get_group_list()
	{
		$groups = $this->ion_auth->groups()->result();
		$name = array();
		foreach($groups as $group)
		{
			array_push($name,$group->name);
		}
	
		$this->output->set_output(json_encode($name));
	
	}
	
   // ------------------------------------------------------------------------

	/**
	 * Helper: saves the calendar events in admin's calendar events collection
	 *  
	 * @author Selva 
	 */
	 
	 function save_calendar_events()
	 {
	    $data = $this->input->post('obj',true);
	    log_message('debug','sssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($data,true));
	    $data["users"] = explode(",", $data["users"]);
	    $data['id']    = get_unique_id();
	    
	   
	    $loggedsubadmin = $this->session->userdata('customer');
		$username       = $loggedsubadmin['email'];
	    $user_          = str_replace("@","#",$username);
	    $usersession    = $user_.'_calendar_events';
		
	    $this->sub_admin_calendar_Model->save_calendar_events_in_collection($usersession,$data);
	    $users = $data['users'];
	   
	    $data["event_template"] = $this->sub_admin_calendar_Model->get_event_template($username, $data["event_id"]);
	    $data["reply"] = "Not yet replied";
	   

	   foreach ($users as $user)
	   {
	   	 $username = str_replace("@","#",$user);
	   	 $usersession = $username.'_calendar_events';
	   	 $this->sub_admin_calendar_Model->save_calendar_events_in_collection($usersession,$data);
		 
		 //----------------------------------Notification to user-----------------------------------
	   		$message    = 'Please reply to the event: '.$data["title"];
	   		
	   		$loggeduser = $this->session->userdata('customer');
	   		$company    = $loggeduser['company'];
	   		$sender     = $loggeduser['email'];

	   		$receiver   = $username."_push_notifications";
	   		$message_id = get_unique_id();
	   		$this->sub_admin_calendar_Model->send_push_message_event_create($receiver,$message_id,$message,$company,$data);
	   }
	   //----------------------------------Notification history to sub admin--------------------------------------

	   	$message    = 'Please reply to the event: '.$data["title"];

	   	$loggeduser = $this->session->userdata('customer');
	   	$company    = $loggeduser['company'];
	   	$sender     = $loggeduser['email'];
	   	$message_id = get_unique_id();
	   	
	   	$this->sub_admin_Model->push_message_save_history($message_id,$message,$company,$sender,$users);
	 }

    // ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the saved calendar events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_user_events()
	{
		$user = $_POST['user'];
		$id = $_POST['id'];
		
		$username = str_replace("@","#",$user);
		$usercollection = $username.'_calendar_events';
		
		$data = $this->sub_admin_calendar_Model->get_user_calendar_event($usercollection,$id);
	    $this->output->set_output($data[0]['reply']);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the saved calendar events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_calendar_events()
	{
		$u = $this->session->userdata('customer');
		$user = $u['email'];
		$username = str_replace("@","#",$user);
		$usercollection=$username.'_calendar_events';
		$data = $this->sub_admin_calendar_Model->get_calendar_events_in_collection($usercollection);
		$this->output->set_output(json_encode($data));
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper: updates the calendar events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function update_calendar_events()
	{
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu');
		$title = $_POST['title'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$id = $_POST['id'];
		$users = $_POST['users'];
		if(isset($_POST['end_time'])){$event_end_time = $_POST['end_time'];}else{$event_end_time = "";};
		
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuu------------------------------------>'.print_r($_POST,true));
		
		$event_type = $_POST['eventtype'];
		if($event_type=="appexpiry")
		{
		  $this->modify_appexpiry_date($id,$start);
		}
		else if($event_type=="myevent")
		{
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';

		$form_data = array(
			"title" 		 => $title,
			"start" 		 => $start,
			"end"   		 => $end,
			);
		if($event_end_time != ""){
			$form_data = array(
					"title" 		 => $title,
					"start" 		 => $start,
					"end"   		 => $end,
					"event_end_time" => $event_end_time);
			}else{
				$form_data = array(
						"title" 		 => $title,
						"start" 		 => $start,
						"end"   		 => $end);
			}

		$this->sub_admin_calendar_Model->update_calendar_events_in_collection($usersession,$id,$form_data);
		
		$users = explode(",", $users);
		
		foreach ($users as $user){
			$username = str_replace("@","#",$user);
			$usersession = $username.'_calendar_events';
			$event = $this->sub_admin_calendar_Model->get_user_calendar_event($usersession,$id);
			
			if($event[0]['reply'] == 'Not yet replied'){
				$current_status = 'Not yet replied';
			}else{
				$current_status = 'Event modified';
			}
			
			if($event_end_time != ""){
				$form_data = array(
						"title" 		 => $title,
						"start" 		 => $start,
						"end"   		 => $end,
						"event_end_time" => $event_end_time,
						"reply" 		 => $current_status);
			}else{
				$form_data = array(
						"title" 		 => $title,
						"start" 		 => $start,
						"end"   		 => $end,
						"reply" 		 => $current_status);
			}
			$this->sub_admin_calendar_Model->update_calendar_events_in_collection($usersession,$id,$form_data);
		}
		}
	}
	
    // ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the recently saved event's id in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_recent_event_id()
	{
	    $loggeduser = $this->session->userdata('customer');
		$username   = $loggeduser['email'];
		
	    $data = $this->sub_admin_calendar_Model->get_recent_event_id_in_collection($username);
	    $this->output->set_output(json_encode($data));
	}
	
    // ------------------------------------------------------------------------	
    
    /**
	 * Helper: deletes selected event in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
    function delete_calendar_events()
	{
		
	    $id = $_POST['id'];
	    $user = $_POST['user'];
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
		//log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddd'.$usersession.'-----------'.$id);
		$this->sub_admin_calendar_Model->delete_calendar_events_in_collection($usersession,$id);
		
		$users = explode(",", $user);
		//unset($users);
		
		foreach ($users as $user){
			$username = str_replace("@","#",$user);
			$usersession = $username.'_calendar_events';
			//log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddd'.$usersession.'-----------'.$id);
			$this->sub_admin_calendar_Model->delete_calendar_events_in_collection($usersession,$id);
		}
	}
	
    // ------------------------------------------------------------------------		
	
	/**
	 * Helper: edit the selected event in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function edit_event()
	{
		log_message('debug','eddddddddddddddddddeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($_POST,true));
		$user = $_POST['user'];
	    $title = $_POST['title'];
		$description = $_POST['description'];
		$id = $_POST['id'];
		$event_place = $_POST['event_place'];
		$event_time = $_POST['event_time'];
		$event_end_time = $_POST['event_end_time'];
		
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
		
		$form_data = array(
			"title" => $title,
			"description" => $description,
			"event_place" => $event_place,
			"event_time" => $event_time,
			"event_end_time" => $event_end_time);
		$this->sub_admin_calendar_Model->edit_events_in_collection($usersession,$id,$form_data);
		
		$users = explode(",", $user);
		//unset($users);
		
		$form_data = array(
				"title" => $title,
				"description" => $description,
				//"reply" => "Event modified",
				"event_place" => $event_place,
				"event_time" => $event_time,
				"event_end_time" => $event_end_time);
		
		$message    = $title.' is modified by '.str_replace("@","#",$u['email']);
		
		$loggeduser = $this->session->userdata('customer');
		$company    = $loggeduser['company'];
		$sender     = $loggeduser['email'];
		
		foreach ($users as $user){
			$username = str_replace("@","#",$user);
			$usersession = $username.'_calendar_events';
			$this->sub_admin_calendar_Model->edit_events_in_collection($usersession,$id,$form_data);
			//------------------------------Notifications----------------------------------------------
			$receiver   = $username."_push_notifications";
			$message_id = get_unique_id();
			$this->sub_admin_calendar_Model->send_push_message_event_modify($receiver,$message_id,$message,$company,$sender);
			
		}
		
		$this->sub_admin_calendar_Model->push_message_save_history($message_id,$message,$company,$sender,$users);
		
		
	}
	
    // ------------------------------------------------------------------------	
    
    /**
	 * Helper: fetches app created details to display in calendar
	 *  
	 * @author Selva 
	 */
	 
	function get_appcreated_details()
	{
	 	$appdata = $this->sub_admin_calendar_Model->get_appcreated_schedule_for_calendar_display();
     	$this->output->set_output(json_encode($appdata));
	}
	
    // ------------------------------------------------------------------------		
	
	/**
	 * Helper: fetches app expiry details to display in calendar 
	 *  
	 * @author Selva 
	 */
	 
	function get_appexpiry_details()
	{
		$appdata = $this->sub_admin_calendar_Model->get_appexpiry_schedule_for_calendar_display();
    	$this->output->set_output(json_encode($appdata));
	}

    // ------------------------------------------------------------------------		
	
	/**
	 * Helper: modifies app expiry date in "applications" collection (** admin can drag and drop the app expiry event on some other date to update it's expiry date)
	 *  
	 * @author Selva 
	 */
	 
	function modify_appexpiry_date($appid,$appexpiry)
	{
	 	$this->sub_admin_calendar_Model->modify_appexpiry_date_in_collection($appid,$appexpiry);
	}
	
	
}