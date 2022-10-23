 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Sub_app_builder_model extends CI_Model 
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
	 * Helper: Fetch all event requests ( create an event app )
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_event_requests_from_collection()
	{
		$query = $this->mongo_db->where(array('req_status'=>'new'))->get($this->collections['event_requests']);
		return $query;
		
     }
 
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch all feedback requests ( create a feedback app )
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_feedback_requests_from_collection()
	{
		$query = $this->mongo_db->where(array('req_status'=>'New'))->get($this->collections['feedback_requests']);
		return $query;
		
     }
	 
	  // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch all event manage requests ( edit an event app)
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_event_manage_requests_from_collection()
	{
		$query = $this->mongo_db->where(array('req_status'=>'edited'))->get($this->collections['event_requests']);
		return $query;
		
     }
 
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch all feedback manage requests ( edit a feedback app )
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_feedback_manage_requests_from_collection()
	{
		$query = $this->mongo_db->where(array('req_status'=>'edited'))->get($this->collections['feedback_requests']);
		return $query;
		
     }
	 
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	 
	function update_event_requests_status($id,$form_data)
	{
		$this->mongo_db->where(array('id'=>$id))->set($form_data)->update($this->collections['event_requests']);
	}
	
	/**
	 * Helper: retrieves the created events in admin's calendar events collection
	 *
	 * @author Selva
	 */
	 
	function update_feedback_requests_status($id,$form_data)
	{
		$this->mongo_db->where(array('id'=>$id))->set($form_data)->update($this->collections['feedback_requests']);
	}
	
	/**
	 * Helper: retrieves the version of event
	 *
	 * @author Selva
	 */
	 
	function version_with_id_event($event_id)
	{
		$query = $this->mongo_db->where('id', $event_id)->select(array('_version'),array())->get($this->collections['event_requests']);
		
		return $query[0]['_version'];
	}
	
	/**
	 * Helper: retrieves the mail id of event
	 *
	 * @author Selva
	 */
	 
	function mail_id_event($event_id)
	{
		$query = $this->mongo_db->where('id', $event_id)->select(array('requested_user_id','event_name'),array())->get($this->collections['event_requests']);
		
		return $query[0];
	}
	
	/**
	 * Helper: updates event
	 *
	 * @author Selva
	 */
	 
	function update_event($event_id,$data)
	{
		$this->mongo_db->where('id', $event_id)->set($data)->update($this->collections['event_requests']);
	}
	
	/**
	 * Helper: retrieves the version of feedback
	 *
	 * @author Selva
	 */
	 
	function version_with_id_feedback($feedback_id)
	{
		$query = $this->mongo_db->where('id', $feedback_id)->select(array('_version'),array())->get($this->collections['feedback_requests']);
		
		return $query[0]['_version'];
	}
	
	/**
	 * Helper: retrieves the mail id of feedback
	 *
	 * @author Selva
	 */
	 
	function mail_id_feedback($feedback_id)
	{
		$query = $this->mongo_db->where('id', $feedback_id)->select(array('requested_user_id','feedback_name'),array())->get($this->collections['feedback_requests']);
		
		return $query[0];
	}
	
	/**
	 * Helper: updates feedback
	 *
	 * @author Selva
	 */
	 
	function update_feedback($feedback_id,$data)
	{
		$this->mongo_db->where('id', $feedback_id)->set($data)->update($this->collections['feedback_requests']);
	}
} 