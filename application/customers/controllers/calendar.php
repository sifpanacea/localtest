<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('PaaS_common_lib');
		$this->load->library('mongo_db');
		$this->load->library('calendar');
		$this->load->model('calendar_Model');
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
	  $this->data['message'] = "Calendar Page";
	  
	  //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	  $this->_render_page('admin/new_calendar', $this->data);
	}
	
   // ------------------------------------------------------------------------

	/**
	 * Helper: saves the calendar events in admin's calendar events collection
	 *  
	 * @author Selva 
	 */
	 
	 function save_calendar_events()
	 {
	   $data = $this->input->post('obj');
	   $u = $this->session->userdata('customer');
	   $username = str_replace("@","#",$u['email']);
	   $usersession=$username.'_calendar_events';
	   $this->calendar_Model->save_calendar_events_in_collection($usersession,$data);
	 }

    // ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the saved calendar events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_calendar_events()
	{
	    $newdata = array();
	    $u = $this->session->userdata('customer');
		$user = $u['email'];
		$username = str_replace("@","#",$user);
		$usercollection=$username.'_calendar_events';
		$data = $this->calendar_Model->get_calendar_events_in_collection($usercollection);
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
		$title = $_POST['title'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$id = $_POST['id'];
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
		$this->calendar_Model->update_calendar_events_in_collection($usersession,$id,$title,$start,$end);
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
	    $u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
	    $data = $this->calendar_Model->get_recent_event_id_in_collection($usersession);
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
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
		$this->calendar_Model->delete_calendar_events_in_collection($usersession,$id);
	}
	
    // ------------------------------------------------------------------------		
	
	/**
	 * Helper: edit the selected event in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function edit_event()
	{
	    $title = $_POST['title'];
		$description = $_POST['description'];
		$id = $_POST['id'];
		$u = $this->session->userdata('customer');
		$username = str_replace("@","#",$u['email']);
		$usersession=$username.'_calendar_events';
		$this->calendar_Model->edit_events_in_collection($usersession,$id,$title,$description);
	}
	
    // ------------------------------------------------------------------------	
    
    /**
	 * Helper: fetches app created details to display in calendar
	 *  
	 * @author Selva 
	 */
	 
	function get_appcreated_details()
	{
	 	$appdata = $this->calendar_Model->get_appcreated_schedule_for_calendar_display();
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
		$appdata = $this->calendar_Model->get_appexpiry_schedule_for_calendar_display();
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
	 	$this->calendar_Model->modify_appexpiry_date_in_collection($appid,$appexpiry);
	}
	
	
}