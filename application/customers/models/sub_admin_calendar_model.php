<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Sub_Admin_Calendar_Model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
		$this->load->library('mongo_db');
        $this->config->load('mongodb');
		$this->load->config('ion_auth', TRUE);
		$this->collections = $this->config->item('collections','ion_auth');
	    $this->_configvalue = $this->config->item('default','mongodb');
    }

// ------------------------------------------------------------------------

	/**
	 * Helper: saves the created events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function save_calendar_events_in_collection($collection,$insertdata)
	{
	  $this->mongo_db->insert($collection,$insertdata);
	}

// ------------------------------------------------------------------------

	/**
	 * Helper: retrieves the created events in admin's calendar events collection 
	 *  
	 * @author Selva 
	 */
	 
	function get_calendar_events_in_collection($collection)
	{
	  $query = $this->mongo_db->select(array(),array('_id'))->get($collection);
	  return $query;
	}
	
// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_event_template($user,$event_id)
	{
		$query = $this->mongo_db->where(array('id'=>$event_id,'requested_user_id'=>$user))->get($this->collections['event_requests']);
		return $query[0]['event_template'];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	
	function get_user_calendar_event($collection,$id)
	{
		$query = $this->mongo_db->select(array(),array('_id'))->where('id', $id)->get($collection);
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
	 
    function get_recent_event_id_in_collection($user)
   {
   	log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.print_r($user,true));
      $this->mongo_db->orderBy(array('id' => -1));
	  $this->mongo_db->limit(1);
      $query = $this->mongo_db->where(array('req_status'=>'Processed','requested_user_id'=>$user))->select(array('id'),array())->get($this->collections['event_requests']);
      log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
	  return $query;

   }
   
   // ---------------------------------------------------------------------------------------
	/**
	 * Helper: User details to send notification
	 *
	 * @author Selva
	 */
	
	public function send_push_message_event_create($receiver,$message_id,$message,$company,$data)
	{
		$data = array(
				'message_id'    => $message_id,
				'message'       => $message,
				'sent_time'     => date('Y-m-d H:i:s'),
				'sent_source'   => 'sub_admin',
				'message_owner' => 'Event Creation',
				'status'        => 'new',
				'title'			=> $data['title'],
				'start_date'	=> $data['start'],
				'event_time'	=> $data['event_time']
		);
			
		$this->mongo_db->insert($receiver,$data);
	}
   
   // ---------------------------------------------------------------------------------------
   /**
    * Helper: User details to send notification
    *
    * @author Selva
    */
   
   public function send_push_message_event_modify($receiver,$message_id,$message,$company)
   {
   	$data = array(
   			'message_id'    => $message_id,
   			'message'       => $message,
   			'sent_time'     => date('Y-m-d H:i:s'),
   			'sent_source'   => 'sub_admin',
   			'message_owner' => 'Event Modified',
   			'status'        => 'new',
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

 // ------------------------------------------------------------------------

	/**
	 * Helper: fetches the app created details to display in calendar
	 *  
	 * @author Selva 
	 */
	 
 function get_appcreated_schedule_for_calendar_display()
{
      $appname = array();
      $start = array();
	  $newevent = array();
      $this->mongo_db->select(array(),array());
	  $query=$this->mongo_db->get($this->collections['records']);
	  foreach($query as $eve)
	  {
	    array_push($appname,$eve['app_name']);
		array_push($start,$eve['time']);
	  }
	  $count = count($appname);
	  log_message('debug','get_apps_for_calendar_display()()()()()()()'.print_r($count,true));
	  for($ii=0;$ii<$count;$ii++)
	  {
	  $event = array(
	  "title" =>"Hello ! App Created",
	  "start" => $start[$ii],
	  "allDay" =>"true",
	  "id"   =>$ii,
	  "className" =>"bg-color-blue txt-color-white",
	  "description" => $appname[$ii]);
	  array_push($newevent,$event);
	  }
	  log_message('debug','get_apps_for_calendar_display()()()()()()()'.print_r($newevent,true));
	  return $newevent;
} 

 // ------------------------------------------------------------------------

	/**
	 * Helper: fetches the app expiry details to display in calendar
	 *  
	 * @author Selva 
	 */
	 
 function get_appexpiry_schedule_for_calendar_display()
{
   $appname = array();
   $start = array();
   $newevent = array();
   $id = array();
   $this->mongo_db->select(array(),array());//array('_version','time','app_template','app_type','app_category','_id','workflow','created_by','pages'));
	  $query=$this->mongo_db->get($this->collections['records']);
	  foreach($query as $eve)
	  {
	    log_message('debug','eveeveveevveevevevevevevevevevev'.print_r($eve['app_expiry'],true));
	    array_push($appname,$eve['app_name']);
		array_push($start,$eve['app_expiry']);
		array_push($id,$eve['_id']);
	  }
	  $count = count($appname);
	  log_message('debug','get_appexpiry_schedule_for_calendar_display()()()()()()()'.print_r($start,true));
	  for($ii=0;$ii<$count;$ii++)
	  {
	  $event = array(
	  "title" =>"Hello ! App will expire today",
	  "start" => $start[$ii],
	  "allDay" =>"true",
	  "id"   =>$id[$ii],
	  "className" =>"bg-color-red txt-color-white",
	  "description" => $appname[$ii]);
	  array_push($newevent,$event);
	  }
	  log_message('debug','get_apps_for_calendar_display()()()()()()()'.print_r($newevent,true));
	  return $newevent;

} 

 // ------------------------------------------------------------------------

	/**
	 * Helper: Modifies the app expiry date in "records" collection
	 *  
	 * @author Selva 
	 */
	 
 function modify_appexpiry_date_in_collection($appid,$appexpiry)
 {
 $this->mongo_db->where('_id', $appid)->set('app_expiry',$appexpiry)->update($this->collections['records']);
 }
}	