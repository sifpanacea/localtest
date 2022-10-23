<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Printer_model extends CI_Model{

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->config('mongodb');
        $this->collections = $this->config->item('collections','ion_auth');
        $this->common_db = $this->config->item('default');
		// Load MongoDB library,
		$this->load->library('mongo_db');
		$this->load->config('email');
		$this->load->model('api_model');
    }
    
    // -------------------------------------------------------------------------------

    /**
     * Helper: fetch application and document for print view
     *
     * @param   string $doc_id          Document id
     * @param   string $app_id          Application id
     * @param   string $initial_stage   Initial stage or not
     *
     * @return array
     *  
     * @author Selva 
     */

    function fetch_app_and_doc($doc_id,$app_id,$initial_stage)
    {
        $query = array();
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$widget_def = $this->mongo_db->get('widget_info');
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
        $doc = $this->mongo_db->getWhere($app_id, array('doc_properties.doc_id' => $doc_id)); 
        $app = $this->mongo_db->select(array(),array('_id'))->where('_id',$app_id)->get($this->collections['records']);
        if($initial_stage=="false")
        {
            $query['document']    = $doc[0];
        }
        $query['application'] = $app[0];
        $query['widget_def']  = $widget_def[0];
    	return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Retrieve data for mapper fields
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */
	 
	public function fetch_retriever_data_model($query_param,$query_value,$retrieval_list,$collection_name)
	{
	  $query_param = "doc_data.widget_data.".$query_param;
	  $value = $this->mongo_db->select($retrieval_list)->where(array($query_param => $query_value))->get($collection_name);
	  return $value[0];
	}
	
	// -------------------------------------------------------------------------------

    /**
     * Helper: fetch application and document for print view
     *
     * @param   string $doc_id          Document id
     * @param   string $app_id          Application id
     * @param   string $initial_stage   Initial stage or not
     *
     * @return array
     *  
     * @author Selva 
     */

    function fetch_photo_element_data($doc_id,$app_id,$element_data)
    {
	    $query = array();
		
	    foreach($element_data as $element)
		{
	      $ele = "doc_data.widget_data.".$element;
          $data = $this->mongo_db->select(array($ele),array())->where(array('doc_properties.doc_id' => $doc_id))->get($app_id);
		  $segment = explode('.',$element);
		  if(isset($data) && !empty($data))
		  {
		    array_push($query,$data[0]['doc_data']['widget_data'][$segment[0]][$segment[1]]);
		  }
		}
    	return $query;
    }
    
    // -------------------------------------------------------------------------------
    
    /**
     * Helper: fetch widget deff for print view
     *
     *
     * @return array
     *
     * @author Selva
     */
    
    function fetch_widget_def()
    {
    	$query = array();
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$widget_def = $this->mongo_db->get('widget_info');
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	
    	$query  = $widget_def[0];
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
    
    // ------------------------------------------------------------------------
    
    /**
     * Helper: retrieves the created events in admin's calendar events collection
     *
     * @author Vikas
     */
    
    function get_event_details($event_id,$userid)
    {
    	$query = $this->mongo_db->where(array('id'=>$event_id,'requested_user_id'=>$userid))->get($this->collections['event_requests']);
    	return $query;
    }
    
    /**
     * Helper: retrieves the created events in admin's calendar events collection
     *
     * @author Vikas
     */
    
    function get_feedback_details($feedback_id,$userid)
    {
    	$query = $this->mongo_db->where(array('id'=>$feedback_id,'requested_user_id'=>$userid))->get($this->collections['feedback_requests']);
    	return $query;
    }
    
    // -------------------------------------------------------------------------------
    
    /**
     * Helper: fetch application and document for print view
     *
     * @param   string $doc_id          Document id
     * @param   string $app_id          Application id
     * @param   string $initial_stage   Initial stage or not
     *
     * @return array
     *
     * @author Selva
     */
    
    function fetch_doc($collection,$event_id)
    {
    	$query = array();
    	
    	$doc = $this->mongo_db->getWhere($collection, array('event_properties.event_id' => $event_id));
    	
    	$query = $doc[0];
    	
    	return $query;
    }
    
    /**
     * Helper: fetch application and document for print view
     *
     * @param   string $doc_id          Document id
     * @param   string $app_id          Application id
     * @param   string $initial_stage   Initial stage or not
     *
     * @return array
     *
     * @author Selva
     */
    
    function fetch_doc_event($collection,$event_id)
    {
    	$query = array();
    	 
    	$doc = $this->mongo_db->getWhere($collection, array('event_properties.event_id' => $event_id));
    	 
    	$query = $doc[0];
    	 
    	return $query;
    }
    
    /**
     * Helper: fetch application and document for print view
     *
     * @param   string $doc_id          Document id
     * @param   string $app_id          Application id
     * @param   string $initial_stage   Initial stage or not
     *
     * @return array
     *
     * @author Selva
     */
    
    function fetch_doc_feedback($collection,$feedback_id)
    {
    	$query = array();
    	 
    	$doc = $this->mongo_db->getWhere($collection, array('feedback_properties.feedback_id' => $feedback_id));
    	 
    	$query = $doc[0];
    	 
    	return $query;
    }

}