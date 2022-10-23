<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Sub_Admin_Model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
		$this->load->library('mongo_db');
        $this->config->load('mongodb');
		$this->load->config('ion_auth', TRUE);
		$this->collections = $this->config->item('collections','ion_auth');
	    $this->_configvalue = $this->config->item('default','mongodb');
	    
	    $this->switchs = $this->config->item('switchs', 'ion_auth');
	    $this->common_db = $this->config->item('default');
    }

    // ------------------------------------------------------------------------

	/**
	 * Helper: saves the created events in admin's calendar events collection 
	 *  
	 * @author Vikas 
	 */
	 
	function save_calendar_feedback_in_collection($collection,$insertdata)
	{
	  $this->mongo_db->insert($collection,$insertdata);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Push event request in "event_requests" collection
	 *
	 * @param  array  $insertdata  Event related details
	 *
	 * @return bool
	 *
	 * @author Selva
	 */
	
	function save_event_request_in_collection($insertdata)
	{
		$id = $this->mongo_db->insert($this->collections['event_requests'],$insertdata);
		
		if($id)
		{
		   return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Push feedback request in "feedback_requests" collection
	 *
	 * @param  array  $insertdata  Feedback related details
	 *
	 * @return bool
	 *
	 * @author Selva
	 */
	
	function save_feedback_request_in_collection($insertdata)
	{
		$id = $this->mongo_db->insert($this->collections['feedback_requests'],$insertdata);
		
		if($id)
		{
		   return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: saves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function save_sms_history_in_collection($collection,$insertdata)
	{
		$this->mongo_db->insert($collection,$insertdata);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_event_details($user,$event_id)
	{
		$query = $this->mongo_db->where(array('id'=>$event_id,'requested_user_id'=>$user))->get($this->collections['event_requests']);
		return $query;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_user_calendar_event($collection,$id)
	{
		$query = $this->mongo_db->select(array(),array('_id'))->where('id', $id)->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_user_feedback($collection,$id)
	{
		$query = $this->mongo_db->select(array(),array('_id'))->where('id', $id)->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_user_mobile_number($email)
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->select(array('phone','username'))->where('email', $email)->get('users');
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $query;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the created events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_calendar_events_in_request_collection($user)
	{
	  $query = $this->mongo_db->select()->where(array('requested_user_id'=>$user))->get($this->collections['event_requests']);
	  return $query;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: updates the events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function update_user_event_confirm($collection,$event_id,$form_data)
	{
		$this->mongo_db->where('id', $event_id)->set($form_data)->update($collection);
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_calendar_feedbacks_in_collection($collection)
	{
		$query = $this->mongo_db->select(array())->get($collection);
		return $query;
	}
	
    // ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the created events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_calendar_events_in_collection($collection)
	{
		$query = $this->mongo_db->select()->get($collection);
	  return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_sms_count($collection)
	{
		$query = $this->mongo_db->count($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_sms_history_in_collection($collection,$limit, $page)
	{
		$offset = $limit * ( $page - 1) ;
		
		$query = $this->mongo_db->limit($limit)->offset($offset)->select(array())->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_sub_admin_calendar_events($user)
	{
		$query = $this->mongo_db->where(array('requested_user_id'=>$user,'req_status'=>'Processed'))->get($this->collections['event_requests']);
		return $query;
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: User details to send notification 
	*
	* @author Selva
	*/
	
	public function send_push_message($receiver,$message_id,$message,$company)
	{
	   $data = array(
	   'message_id'    => $message_id,
	   'message'       => $message,
	   'sent_time'     => date('Y-m-d H:i:s'),
	   'sent_source'   => 'sub_admin',
	   'message_owner' => $company,
	   'status'        => 'new'
	   );
	  
	  $this->mongo_db->insert($receiver,$data);
	}
	
	// ---------------------------------------------------------------------------------------
	/**
	 * Helper: User details to send notification
	 *
	 * @author Selva
	 */
	
	public function send_push_message_event_confirm($receiver,$message_id,$message,$company,$event)
	{
		log_message('debug','11111111111111111111111111111111111111111'.print_r($event[0],true));
		if($event[0]['end'] == ''){
			log_message('debug','222222222222222222222222222222222222222222222');
			$data = array(
					'message_id'    => $message_id,
					'message'       => $message,
					'sent_time'     => date('Y-m-d H:i:s'),
					'sent_source'   => 'sub_admin',
					'message_owner' => 'Event',
					'status'        => 'new',
					'event_date' 	=> $event[0]['start'],
					'event_place' 	=> $event[0]['event_place'],
					'event_time' 	=> $event[0]['event_time'],
					'event_title'	=> $event[0]['title'],
			);
			
		}else if (!isset($event[0]['end'])){
			log_message('debug','33333333333333333333333333333333333333333333333333333333333333');
			$data = array(
					'message_id'    => $message_id,
					'message'       => $message,
					'sent_time'     => date('Y-m-d H:i:s'),
					'sent_source'   => 'sub_admin',
					'message_owner' => 'Event',
					'status'        => 'new',
					'event_date' 	=> $event[0]['start'],
					'event_place' 	=> $event[0]['event_place'],
					'event_time' 	=> $event[0]['event_time'],
					'event_title'	=> $event[0]['title'],
			);
		}else{
			log_message('debug','44444444444444444444444444444444444444444444444444');
			$data = array(
				'message_id'    => $message_id,
				'message'       => $message,
				'sent_time'     => date('Y-m-d H:i:s'),
				'sent_source'   => 'sub_admin',
				'message_owner' => 'Event',
				'status'        => 'new',
				'event_date' 	=> $event[0]['start'],
				'event_place' 	=> $event[0]['event_place'],
				'event_time' 	=> $event[0]['event_time'],
				'event_title'	=> $event[0]['title'],
				'event_end' 	=> $event[0]['end'],
				'event_end_time'=> $event[0]['event_end_time'],
		);
		}
		 
		$this->mongo_db->insert($receiver,$data);
	}
	
	// ---------------------------------------------------------------------------------------
	/**
	 * Helper: User details to send notification
	 *
	 * @author Selva
	 */
	
	public function send_push_message_feedback_create($receiver,$message_id,$message,$company,$data)
	{
		$data = array(
				'message_id'    => $message_id,
				'message'       => $message,
				'sent_time'     => date('Y-m-d H:i:s'),
				'sent_source'   => 'sub_admin',
				'message_owner' => 'Feedback',
				'status'        => 'new',
				'feedback_title'=> $data['feedback_name'],
				'expiry_date'	=> $data['expiry_date']
		);
			
		$this->mongo_db->insert($receiver,$data);
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: Save all push messages 
	*
	*
	* @author Selva
	*/
	
	public function push_message_save_history($message_id,$message,$company,$sender,$recipient_list)
	{
	    $data = array(
	   'message_id'    => $message_id,
	   'message'       => $message,
	   'sent_time'     => date('Y-m-d H:i:s'),
	   'message_owner' => $company,
	   'sent_by'       => $sender,
	   'recipients'    => $recipient_list,
	   );
	  
	  $this->mongo_db->insert("push_notifications",$data);
	
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: Save all push messages 
	*
	*
	* @author Selva
	*/
	
	public function get_MY_push_notification($sender)
	{
	   $query = $this->mongo_db->where(array('sent_by'=>$sender))->get($this->collections['enterprise_push_notifications']);
	   return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_event_in_collection($collection,$event_id)
	{
		$query = $this->mongo_db->where('_id', new MongoId($event_id))->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_event_in_collection_by_event_id($collection,$event_id)
	{
		$query = $this->mongo_db->where('id', $event_id)->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	 
	function update_event_form($collection,$_id,$form_data)
	{
		$this->mongo_db->where(array('id'=>$_id))->set($form_data)->update($this->collections['event_requests']);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created feedback in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_calendar_feedback_in_collection($user)
	{
		$query = $this->mongo_db->select()->where(array('requested_user_id'=>$user))->get($this->collections['feedback_requests']);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_feedback_in_collection($collection,$feedback_id)
	{
		$query = $this->mongo_db->where('_id', new MongoId($feedback_id))->get($collection);
		return $query;
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_feedback_details($user,$feedback_id)
	{
		$query = $this->mongo_db->where(array('requested_user_id'=>$user,'id'=> $feedback_id))->get($this->collections['feedback_requests']);
		return $query;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created notification in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_calendar_notification_in_collection($user)
	{
		$query = $this->mongo_db->select(array(),array('_id'))->where(array('sent_by'=>$user))->get($this->collections['enterprise_push_notifications']);
		return $query;
	}
	
	
// ------------------------------------------------------------------------

	/**
	 * Helper: updates the events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function update_calendar_events_in_collection($collection,$id,$form_data)
	{
	  $this->mongo_db->where('id', $id)->set($form_data)->update($collection);
	}	

// ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the recently created event's id in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
    function get_recent_event_id_in_collection($usersession)
   {
      $this->mongo_db->orderBy(array('id' => -1));
	  $this->mongo_db->limit(1);
      $query = $this->mongo_db->select(array('id'),array())->get($usersession);
	  return $query;

   }
   
// ------------------------------------------------------------------------

	/**
	 * Helper: Deletes the selected event in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
   function delete_calendar_events_in_collection($usersession,$id)
  {
    $deleted = $this->mongo_db
			->where('id', $id)
			->delete($usersession);
    
	if (!$deleted)
		{
			return FALSE;
		}
            return TRUE;
  } 
  
    // ------------------------------------------------------------------------

	/**
	 * Helper: edit the event in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
  function edit_events_in_collection($usersession,$id,$form_data)
  { 
	  $this->mongo_db->where('id', $id)->set($form_data)->update($usersession);

  }

/**
	 * Helper: saves the created feedbacks in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function save_feedback_in_collection($collection,$insertdata)
	{
		$this->mongo_db->insert($collection,$insertdata);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Vikas
	 */
	
	function get_feedback_template($user,$feedback_id)
	{
		$query = $this->mongo_db->where(array('requested_user_id'=>$user,'id'=>$feedback_id))->get($this->collections['feedback_requests']);
		return $query[0]['feedback_template'];
	}
	
	/**
	 * Helper: updates the events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function update_feedback_form($user,$_id,$form_data)
	{
		$this->mongo_db->where(array('id'=>$_id))->set($form_data)->update($this->collections['feedback_requests']);
	}

}	