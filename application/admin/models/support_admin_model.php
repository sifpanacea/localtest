 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Support_admin_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->library('session');
        $this->collections = $this->config->item('collections', 'ion_auth');
	}
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch all plan details 
	 *
	 *  
	 * @author Selva 
	 */
	 

     function plans()
	{
		$query = $this->mongo_db->get($this->collections['plan_details']);
		return $query;
		
     }
 
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch all companies registered with PaaS
	 *
	 * @return array
     * 	 
	 * @author Selva 
	 */
	 

     function registered_companies()
	{
		$query = $this->mongo_db->get($this->collections['customers']);
		return $query;
		
     }
	 
	// ------------------------------------------------------------------------

	/**
	 * Helper: Check if a device is already registered
	 *
	 * @param  string  $device_unique_no  Device Unique Number
	 *
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 

     function check_if_device_reg($device_unique_no)
	{
		$query = $this->mongo_db->where(array('device_unique_number'=> $device_unique_no))->get($this->collections['devices']);
		if($query)
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
	 * Helper: Register a device
	 *
	 * @param  string  $device_unique_no  Device Unique Number
	 * @param  string  $plan              Subscribed Plan
	 * @param  string  $subscription_end  Subscription End 
	 * @param  array   $companies         Subscribed Companies
	 * 
     * @return bool
     *	 
	 * @author Selva 
	 */
	 

     function register_device($device_unique_no,$plan,$subscription_end,$companies)
	{
		$data = array(
		'device_unique_number' => $device_unique_no,
		'plan_subscribed'      => $plan,
		'subscribed_with'      => $companies,
		'subscription_start'   => date('Y-m-d'),
		'subscription_end'     => $subscription_end,
		'active'               => 0,
		'status'               => 'offline'
		);
		
		$id = $this->mongo_db->insert($this->collections['devices'],$data);
		
		$id = $this->mongo_db->insert($this->collections['users'],$data);
		
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
	 * Helper: Fetch inbox details for first level support admin
	 *
	 * @param  string  $status      Status of the log entry
	 *
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
	function fetch_first_level_inbox_data($status)
	{
	    $this->mongo_db->orderBy(array('log_received_time' => -1));
	    $query = $this->mongo_db->where('status',$status)->get($this->collections['support_inbox']);
		return $query;
	
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch inbox details for second level support admin
	 *
	 * @param  string  $collection  Admin collection
	 * @param  string  $status      Status of the log entry
	 *
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
	function fetch_second_level_inbox_data($collection,$status)
	{
	    $this->mongo_db->orderBy(array('log_received_time' => -1));
	    $query = $this->mongo_db->where('status',$status)->get($collection);
		return $query;
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the particular crash entry detail for inbox view ( first level admin )
	*
	* @param string  $usercollection  Name of the collection
	* @param string  $unique_id       Unique ID of the device
	* @param string  $crash_id        Crash ID
	* 
	* @return array
	*
	* @author Selva
	*/

	public function get_primary_inbox_entry_in_detail($unique_id,$crash_id)
	{
      $query = $this->mongo_db->select(array(),array('_id'))->where(array('device_unique_number'=>$unique_id,'crash_id'=>$crash_id))->get($this->collections['support_inbox']);
	  return $query[0];
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the particular crash entry detail for inbox view ( first level admin )
	*
	* @param string  $usercollection  Name of the collection
	* @param string  $unique_id       Unique ID of the device
	* @param string  $crash_id        Crash ID
	* 
	* @return array
	*
	* @author Selva
	*/

	public function get_device_details_for_primary_inbox_entry_in_detail($unique_id)
	{
      $query = $this->mongo_db->select(array(),array('_id'))->where(array('device_unique_number'=>$unique_id))->get($this->collections['users']);
	  if($query)
	  {
	    return $query[0];
	  }	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the particular crash entry detail for inbox view ( second level admin )
	*
	* @param string  $usercollection  Name of the collection
	* @param string  $unique_id       Unique ID of the device
	* @param string  $crash_id        Crash ID
	* 
	* @return array
	*
	* @author Selva
	*/

	public function get_secondary_inbox_entry_in_detail($usercollection,$unique_id,$crash_id)
	{
      $query = $this->mongo_db->select(array(),array('_id'))->where(array('device_unique_number'=>$unique_id,'crash_id'=>$crash_id))->get($usercollection);
	  return $query[0];
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the second level admin credentials ( email id )
	* 
	* @return array
	*
	* @author Selva
	*/

	public function get_second_level_admin_credentials()
	{
	   // Get second level admin email id's
	   $query  = $this->mongo_db->select(array('email'),array())->where(array('level'=>'2'))->get($this->collections['support_admin']);
	   return $query;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: forward data to the second level admin 
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $data                   Crash related data
	*
	* @return bool
	*
	* @author Selva
	*/

	public function forward_data_to_second_level($usercollection,$data)
	{
	   $id = $this->mongo_db->insert($usercollection,$data);
		
		if($id)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when forwarding completed
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	*
	* @author Selva
	*/

	public function post_forward_process($user,$device_unique_number,$crash_id,$forwarded_time,$users)
	{
	   $this->mongo_db->set(array('status'=>'forwarded','forwarded_by'=>$user,'forwarded_on'=>$forwarded_time,'forwarded_to'=>$users))->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->update($this->collections['support_inbox']);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked
	* 
	*
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_trash_level_1_model($device_unique_number,$crash_id)
	{
	    
		$res = $this->mongo_db->set('status','trash')->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->update($this->collections['support_inbox']);
		
		if($res)
		{
		   return TRUE;
		}
        else
        {
          return FALSE;
        }		  
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function delete_log_entry_level_1_model($usercollection,$device_unique_number,$crash_id)
	{
	    
		$res = $this->mongo_db->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->delete($usercollection);
		
		if($res)
		{
		   return TRUE;
		}
        else
        {
          return FALSE;
        }		  
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Reply data to the first level admin 
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $data                   Crash related data
	*
	* @return bool
	*
	* @author Selva
	*/

	public function reply_data_to_first_level($usercollection,$data)
	{
	   $id = $this->mongo_db->set($data)->update($usercollection);
		
		if($id)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when reply process completed
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	*
	* @author Selva
	*/

	public function post_reply_process($usercollection,$device_unique_number,$crash_id)
	{
	   $this->mongo_db->set('status','resolved')->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->update($usercollection);
	}
	
	
    // ---------------------------------------------------------------------------------------

	/**
	* Helper: Device details ( All details even inactive devices )
	* 
	* @return array
	*
	* @author Selva
	*/

	public function device_details_for_first_level_admin()
	{
	   $query = $this->mongo_db->get($this->collections['users']);
	   return $query;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Device details for notification panel ( Active devices only )
	*
	* @return array
	*
	* @author Selva
	*/

	public function device_details_for_notification()
	{
	   $query = $this->mongo_db->where(array('active'=>1))->get($this->collections['users']);
	   return $query;
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: User details to send notification 
	*
	* @return array
	*
	* @author Selva
	*/
	
	public function get_user_details_for_notification($email)
	{
	   $query = $this->mongo_db->where(array('email'=>$email))->get($this->collections['users']);
	   return $query[0];
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: User details to send notification 
	*
	* @return array
	*
	* @author Selva
	*/
	
	public function send_push_message($receiver,$company,$message_id,$message)
	{
	   $data = array(
	   'message_id'    => $message_id,
	   'message'       => $message,
	   'sent_time'     => date('Y-m-d H:i:s'),
	   'sent_source'   => 'support_admin',
	   'message_owner' => 'TLSTEC',
	   'status'        => 'new'
	   );
	   
	   $this->mongo_db->switchDb(URL_DB.$company);
	   $this->mongo_db->insert($receiver,$data);
	   $this->mongo_db->switchDb(DBNAME);
	}
	
	// ---------------------------------------------------------------------------------------
    /**
	* Helper: Save all push messages 
	*
	*
	* @author Selva
	*/
	
	public function push_message_save_history($message_id,$message,$sender,$recipient_list)
	{
	    $data = array(
		   'message_id'    => $message_id,
		   'message'       => $message,
		   'sent_time'     => date('Y-m-d H:i:s'),
		   'sent_by'       => $sender,
		   'recipients'    => $recipient_list,
	    );
	  
	  $this->mongo_db->insert($this->collections['tlstec_push_notifications'],$data);
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked - LEVEL 2 admin
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_trash_level_2_model($usercollection,$device_unique_number,$crash_id)
	{
	    
		$res = $this->mongo_db->set('status','trash')->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->update($usercollection);
		
		if($res)
		{
		   return TRUE;
		}
        else
        {
          return FALSE;
        }		  
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Remove entry from collection
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function delete_log_entry_level_2_model($usercollection,$device_unique_number,$crash_id)
	{
	    
		$res = $this->mongo_db->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->delete($usercollection);
		
		if($res)
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
	 * Helper: Fetch inbox details for first level support admin
	 *
	 * @param  string  $collection  Admin collection
	 * @param  string  $status      Status of the log entry
	 *
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
	public function log_details_in_detail($limit, $page)
	{
	    $offset = $limit * ( $page - 1) ;
		$this->mongo_db->orderBy(array('log_received_time' => -1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
	    $query = $this->mongo_db->get($this->collections['detailed_crash_logs']);
		return $query;
	}
	 
	 // ------------------------------------------------------------------------

	/**
	 * Helper: Log count
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function logcount()
    {
      $logcount = $this->mongo_db->get($this->collections['detailed_crash_logs']);
	  return count($logcount);
    }

	// ------------------------------------------------------------------------

	/**
	 * Helper: Delete log entry in collection
	 *
	 * @param  string  $id  Log ID
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
    public function delete_log($id)
	{
		// Delete log document 
		$deleted = $this->mongo_db
		->where('log_id', $id)
		->delete($this->collections['detailed_crash_logs']);
	
		if ( ! $deleted)
		{
			return FALSE;
		}

		return TRUE;
	}

	 // ------------------------------------------------------------------------

	/**
	 * Helper: Register ticket
	 *
	 * @param  string  $id  Log ID
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
     function register_ticket($device_unique_no,$email,$crashed_app,$service_request_number,$ticket_description,$file_data=FALSE)
	{
		$data = array(
		'device_unique_number'   => $device_unique_no,
		'service_request_number' => $service_request_number,
		'owner'                  => $email,
		'crashed_app'            => $crashed_app,
		'description'            => $ticket_description,
		'registered_on'          => date('Y-m-d H:i:s'),
		'status'                 => 'new',
		);
		
		if(!empty($file_data) && !is_null($file_data))
		{
		  $data['attachment'] = $file_data;
		}
		
		$id = $this->mongo_db->insert($this->collections['tickets'],$data);
		
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
	 * Helper: Check if the email is registered with PaaS
	 *
	 * @param  string  $email  Email ID
	 *
	 * @return array
	 *
	 * @author Selva 
	 */
	 
     function is_registered_user($email)
	{
		$res = $this->mongo_db->where(array('email'=>$email))->get($this->collections['users']);
		
		if($res)
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
	 * Helper: Get registered ticket details
	 *
	 * @return array
	 *
	 * @author Selva 
	 */
	 
     function tickets($limit,$page)
	{
		$offset = $limit * ( $page - 1) ;
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
	    $query = $this->mongo_db->get($this->collections['tickets']);
		return $query;
		
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Count of the tickets
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function ticketcount()
    {
      $count = $this->mongo_db->get($this->collections['tickets']);
	  return count($count);
    }

	// ------------------------------------------------------------------------

	/**
	 * Helper: Get registered ticket details
	 *
	 * @return array
	 *
	 * @author Selva 
	 */
	 
     function MY_tickets($limit,$page,$collection)
	{
		$offset = $limit * ( $page - 1) ;
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
	    $query = $this->mongo_db->get($collection);
		return $query;
		
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Count of the tickets assigned to second level support admin
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function MY_ticketcount($collection)
    {
      $count = $this->mongo_db->get($collection);
	  return count($count);
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Count of the tickets assigned to second level support admin
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function assign_ticket_to_second_level_admin($device_unique_no,$crashed_app,$registered_on,$ticket_assigned_on,$ticket_owner,$ticket_description,$service_request_number,$support_admin,$collection,$file_data=FALSE)
    {
       $data = array(
		'device_unique_number'   => $device_unique_no,
		'service_request_number' => $service_request_number,
		'owner'                  => $ticket_owner,
		'crashed_app'            => $crashed_app,
		'description'            => $ticket_description,
		'registered_on'          => $registered_on,
		'assigned_by'            => $support_admin,
		'assigned_on'            => $ticket_assigned_on,
		'status'                 => 'new'
		);
		
		if(!empty($file_data) && !is_null($file_data))
		{
		  $data['attachment'] = $file_data;
		}
		
		$id = $this->mongo_db->insert($collection,$data);
		
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
	 * Helper: Count of the tickets assigned to second level support admin
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function post_process_assign_ticket($service_request_number,$ticket_assigned_on,$user,$support_admin)
    {
       $this->mongo_db->set(array('status'=>'assigned','assigned_to'=>$user,'assigned_on'=>$ticket_assigned_on,'assigned_by'=>$support_admin))->where(array('service_request_number'=>$service_request_number))->update($this->collections['tickets']);
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked - LEVEL 2 admin
	* 
	*
	* @param string  $usercollection   Name of the collection
	* @param string  $service_req_no   Service Request Number
	* @param string  $resolution       Resolution
	* @param string  $resolved_time    Resolved time
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_ticket_resolved_level_1_model($user,$service_req_no,$resolution,$resolved_time)
	{
	    
		$res = $this->mongo_db->set(array('status'=>'processed','resolution'=>$resolution,'resolved_on'=>$resolved_time,'resolved_by'=>$user,'resolved_level'=>1))->where(array('service_request_number'=>$service_req_no))->update($this->collections['tickets']);
		
		if($res)
		{
		   return TRUE;
		}
        else
        {
          return FALSE;
        }		  
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked - LEVEL 2 admin
	* 
	*
	* @param string  $usercollection   Name of the collection
	* @param string  $service_req_no   Service Request Number
	* @param string  $resolution       Resolution
	* @param string  $resolved_time    Resolved time
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_ticket_resolved_level_2_model($usercollection,$service_req_no,$resolution,$resolved_time)
	{
	    
		$res = $this->mongo_db->set(array('status'=>'processed','resolution'=>$resolution,'resolved_on'=>$resolved_time))->where(array('service_request_number'=>$service_req_no))->update($usercollection);
		
		if($res)
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
	 * Helper: Count of the tickets assigned to second level support admin
	 *
	 *
	 * @author Selva 
	 */
	 
	 public function post_process_resolve_ticket($service_req_no,$resolution,$resolved_time,$user)
    {
       $this->mongo_db->set(array('status'=>'processed','resolution'=>$resolution,'resolved_on'=>$resolved_time,'resolved_by'=>$user,'resolved_level'=>2))->where(array('service_request_number'=>$service_req_no))->update($this->collections['tickets']);
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Re-assign ticket to other admin by original assignee
	 *
	 * @param string  $service_request_number   Service Request Number
	 * @param string  $ticket_assigned_on       Ticket assigned time
	 * @param string  $new_user                 To be assigned user
	 * @param string  $old_user_collection      Name of the collection of original ticket assignee
	 *
	 * @author Selva 
	 */
	 
	 public function re_assign_ticket_to_second_level_admin($device_unique_no,$crashed_app,$registered_on,$ticket_assigned_on,$ticket_owner,$ticket_description,$service_request_number,$support_admin,$collection,$file_data=FALSE)
    {
       $data = array(
		'device_unique_number'   => $device_unique_no,
		'service_request_number' => $service_request_number,
		'owner'                  => $ticket_owner,
		'crashed_app'            => $crashed_app,
		'description'            => $ticket_description,
		'registered_on'          => $registered_on,
		'assigned_by'            => $support_admin,
		'assigned_on'            => $ticket_assigned_on,
		'status'                 => 'new',
		);
		
		if(!empty($file_data) && !is_null($file_data))
		{
		  $data['attachment'] = $file_data;
		}
		
		$id = $this->mongo_db->insert($collection,$data);
		
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
	 * Helper: Re-assign ticket - post process
	 *
	 * @param string  $service_request_number   Service Request Number
	 * @param string  $ticket_assigned_on       Ticket assigned time
	 * @param string  $new_user                 To be assigned user
	 * @param string  $old_user_collection      Name of the collection of original ticket assignee
	 *
	 * @author Selva 
	 */
	 
	 public function post_process_re_assign_ticket($service_request_number,$ticket_assigned_on,$new_user,$old_user_collection)
    {
	   $this->mongo_db->set(array('status'=>'re_assigned','re_assigned_to'=>$new_user,'re_assigned_on'=>$ticket_assigned_on))->where(array('service_request_number'=>$service_request_number))->update($old_user_collection);
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked - LEVEL 2 admin
	* 
	*
	* @param string  $user             Name of the collection
	* @param string  $service_req_no   Service Request Number
	* @param string  $resolution       Resolution
	* @param string  $resolved_time    Resolved time
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_auto_ticket_resolved_level_1_model($user,$service_req_no,$resolution,$resolved_time)
	{
	    
		$res = $this->mongo_db->set(array('status'=>'resolved','resolution'=>$resolution,'resolved_on'=>$resolved_time,'resolved_by'=>$user,'resolved_level'=>1))->where(array('crash_id'=>$service_req_no))->update($this->collections['support_inbox']);
		
		if($res)
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
	 * Helper: Count of the tickets assigned to second level support admin
	 *
	 *
	 * @author Selva 
	 */
	 
	 public function post_process_resolve_auto_ticket($service_req_no,$resolution,$resolved_time,$user)
    {
       $this->mongo_db->set(array('status'=>'resolved','resolution'=>$resolution,'resolved_on'=>$resolved_time,'resolved_by'=>$user,'resolved_level'=>2))->where(array('crash_id'=>$service_req_no))->update($this->collections['support_inbox']);
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when delete clicked - LEVEL 2 admin
	* 
	*
	* @param string  $usercollection   Name of the collection
	* @param string  $service_req_no   Service Request Number
	* @param string  $resolution       Resolution
	* @param string  $resolved_time    Resolved time
	*
	* @return bool
	*
	* @author Selva
	*/
	
	public function mark_as_auto_ticket_resolved_level_2_model($user,$usercollection,$service_req_no,$resolution,$resolved_time)
	{
	    
		$res = $this->mongo_db->set(array('status'=>'resolved','resolution'=>$resolution,'resolved_on'=>$resolved_time,'resolved_by'=>$user))->where(array('crash_id'=>$service_req_no))->update($usercollection);
		
		if($res)
		{
		   return TRUE;
		}
        else
        {
          return FALSE;
        }		  
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the particular crash entry detail for inbox view ( first level admin )
	*
	* @param string  $usercollection  Name of the collection
	* @param string  $unique_id       Unique ID of the device
	* @param string  $crash_id        Crash ID
	* 
	* @return array
	*
	* @author Selva
	*/

	public function get_inbox_entry_in_detail_from_second_level($usercollection,$unique_id,$crash_id)
	{
      $query = $this->mongo_db->select(array(),array('_id'))->where(array('device_unique_number'=>$unique_id,'crash_id'=>$crash_id))->get($usercollection);
	  return $query[0];
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Update status when forwarding completed
	* 
	*
	* @param string  $usercollection         Name of the collection
	* @param string  $device_unique_number   Unique ID of the device
	* @param string  $crash_id               Crash ID
	*
	*
	* @author Selva
	*/

	public function post_forward_process_from_second_level($user,$forwarded_time,$usercollection,$device_unique_number,$crash_id,$users)
	{
	   $this->mongo_db->set(array('status'=>'forwarded','forwarded_by'=>$user,'forwarded_on'=>$forwarded_time,'forwarded_to'=>$users))->where(array('device_unique_number'=>$device_unique_number,'crash_id'=>$crash_id))->update($usercollection);
	}

} 