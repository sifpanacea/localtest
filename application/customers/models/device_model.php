<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class device_Model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
		
		// Initialize MongoDB collection names
		$this->collections = $this->config->item ( 'collections', 'ion_auth' );
		$this->_configvalue = $this->config->item ( 'default' );
		$this->common_db = $this->config->item ( 'default' );
		
		$this->load->library('mongo_db');
        $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
	    $this->collections  = $this->config->item('collections','ion_auth');
	    $this->load->library('mongo_db');
		$this->load->helper('paas_helper');
    } 
    
    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the new apps from user collection
	 *
	 * @author Selva 
	 *
	 * @param string user collection
	 *
	 *
	 * @return array
	 */

	public function update_apps($collection_name)
    {	
	    $this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->limit(50)->get($collection_name);
		return $query;
    }

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the new documents from user collection
	 *
	 * @author Selva 
	 *
	 * @param string user collection
	 *
	 *
	 * @return array
	 */

	public function update_docs($usercollection)
	{
		//$today = date("Y-m-d");
		//$from_date = "2017-09-01";
		
		/*$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  		$query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where('status','new')->get($usercollection);
	  		return $query;*/

		if(preg_match("/dr/i",$usercollection))
		{
			$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  		$query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where('status','new')->get($usercollection);
	  		return $query; 
	    }
	    else
	    {
	    	$user_email = explode("#", $usercollection);
			$unique_id = $user_email[0];
			$dist_code = strtoupper(str_replace(".", "_", $unique_id));
			$dist_code_hs = str_replace("HS", "", $dist_code);
			$where_check = array('notification_param.Unique ID' => array('$regex' => $dist_code_hs),
				'status' => "new");
			$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  		$query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($where_check)->get($usercollection);
			return $query;
	    }
		
	  	//$query=$this->mongo_db->whereBetween('doc_received_time',$from_date,$today)->select(array(),array('_id'))->where('status','new')->get($usercollection);
	  	//log_message('error',"healthcare2016531124515424========".print_r($query,true));
		
		/*$documents = [];
		foreach($query as $doc)
		{
			if($doc['app_id'] == "healthcare2016531124515424")
			{
				$doc['is_status_emergency'] = false;
				$is_status_emergency = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->count("healthcare2016531124515424");
				// log_message('debug','emergency======================count========================='.print_r($is_status_emergency,true));
				if($is_status_emergency > 0)
				{
					$doc['is_status_emergency'] = true;
					//$doc_emergency = $this->mongo_db->select(array('doc_data.widget_data.page2.Review Info.Request Type'))->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get("healthcare2016531124515424");
					//log_message('debug','emergency======================count========================='.print_r($doc_emergency,true));
				}
				
				$doc['is_status_chronic'] = false;
				$is_status_chronic = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->count("healthcare2016531124515424");
				// log_message('debug','emergency======================count========================='.print_r($is_status_emergency,true));
				if($is_status_chronic > 0)
				{
					$doc['is_status_chronic'] = true;
					
				}
			} 
			
			else {
				 if($doc['app_id'] == "healthcare201610114435690")
			{
				$doc['is_status_emergency'] = false;
				$is_status_emergency = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->count("healthcare201610114435690");
				 
				if($is_status_emergency > 0)
				{
					$doc['is_status_emergency'] = true;
					
				}
				$doc['is_status_chronic'] = false;
				$is_status_chronic = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->count("healthcare201610114435690");
				if($is_status_chronic > 0)
				{
					$doc['is_status_chronic'] = true;
					
				}
				
			}
			else{
				if($doc['app_id'] == "healthcare2016108181933756")
			{
				$doc['is_status_emergency'] = false;
				$is_status_emergency = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->count("healthcare2016108181933756");
				 
				if($is_status_emergency > 0)
				{
					$doc['is_status_emergency'] = true;
					
				}
				
				$doc['is_status_chronic'] = false;
				$is_status_chronic = $this->mongo_db->where(array('doc_properties.doc_id' => $doc['doc_id'], 'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->count("healthcare2016108181933756");
				if($is_status_chronic > 0)
				{
					$doc['is_status_chronic'] = true;
					
				}
				
			}
			}
			
			}
			array_push($documents ,$doc);
			
			
		}
		//log_message('debug','document======================count========================='.print_r(count($documents),true));
		//log_message('debug','document======================list========================='.print_r($documents,true));
		return $documents; */
		
		/*$final_query = array();
		$query = array();
		
		$doc_query=$this->mongo_db->select(array(),array('_id'))->where('status','new')->get($usercollection);
		

		$data_query = $this->mongo_db->select(array('app_id','doc_id'),array())->where('status','new')->get($usercollection);
		
		foreach($data_query as $id)
		{
		$collection_name = $id['app_id'];
		$doc_id          = $id['doc_id'];
		
		$this->mongo_db->where('doc_properties.doc_id',$doc_id);
		$this->mongo_db->select(array('doc_data.widget_data.page1'),array());
		$query = array_merge($query,$this->mongo_db->get($collection_name));
		}
		
		

		$final_query['doc']    = $doc_query;
		$final_query['widget'] = $query;

		$obj = json_decode(json_encode($final_query),FALSE);
		
		return $obj;   */
		
	}

	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the application details for inbox view from the usercollection
	*
	* @author Selva
	*
	* @param string user collection
	* @param string application id
	*
	*
	* @return array
	*/

	public function get_application_details_from_collection($usercollection,$app_id)
	{

	$query=$this->mongo_db->select(array('app_id','app_name','app_description','app_created','app_expiry','_version'))->where('app_id',$app_id)->get($usercollection);
	return $query[0];
	}

	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the document details for inbox view from the usercollection
	*
	* @author Selva
	*
	* @param string user collection
	* @param string application id
	* @param string document id
	*
	* @return array
	*/

	public function get_document_details_from_collection($usercollection,$app_id,$doc_id)
	{
		$query=$this->mongo_db->select(array(),array('_id','stage','status'))->where(array('app_id'=>$app_id,'doc_id'=>$doc_id))->get($usercollection);
		return $query[0];
	}
	
	public function update_latest_apps($usersession)
	{	
		
		
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->limit(10)->get($usersession);
		return $query;
	}
	
	public function user_web_apps($usercollection)
	{	
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->get($usercollection);
		return $query;
		
	}
	
	
	public function user_web_docs($usercollection)
	{	
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->get($usercollection);
		//log_message('debug','user____Web_______________docssssssssssssss'.print_r($query,true));
		return $query;
		
	}
	
	
    public function update_latest_docs($usersession)
	{
		
	  	
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->limit(10)->get($usersession);
		return $query;
		
	}
	
	public function user_profile_data($email)
	{
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select(array(),array('password','ip_address','_id','created_on','last_login','active','salt','remember_code','forgotten_password_code','forgotten_password_time','activation_code'));
	   $query=$this->mongo_db->limit(1)->getWhere('users',array('email'=>$email));
	   return $query;
	}
	
	public function user_company_data($company)
	{
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select();
	   $query=$this->mongo_db->limit(1)->getWhere('customers',array('company_name'=>$company));
	   return $query;
	}
	
	public function install_data($appid, $user)
	{
		
		
		$this->load->library('mongo_db');
		$this->mongo_db->select(array(),array('status','app_id','_id'));
		$test=$this->mongo_db->getWhere($user,array('app_id'=>$appid));
		//$this->mongo_db->get($user);
		$this->mongo_db->where(array('app_id'=>$appid))->set('status','processed')->update($user);
		
		return $test;
		
	}
 
 
 public function access_docs($appid,$user)
	{
		
		
		$doci = intval($appid);
		
		$this->load->library('mongo_db');
		$this->mongo_db->select(array(),array('status','time','_id'));
		$test=$this->mongo_db->getWhere($user,array('doc_id'=>$doci));
		//$this->mongo_db->get($user);
		//$this->mongo_db->where(array('app_id'=>$appid))->set('status','processed')->update($user);
		
		return $test;
		
	}

	public function expiry_date($company)
	  {
	    $comp = str_replace(" ","",$company);
	    $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	    $document = $this->mongo_db
			->select(array('plan_expiry'))
			->where('company_name',$comp)
			->limit(1)
			->get('customers'); 
	  
      $expirydate = $document[0]['plan_expiry'];
	  
	  return $expirydate;
	 }

	 public function getappforcreate($appid)
	 {

       $query=$this->mongo_db->select(array('workflow'))->where('_id',$appid)->get($this->collections['records']);
       
       return $query[0];

	 }
	
	 public function getpermissionsforcreate($appid,$collection)
	 {
        $this->mongo_db->select();
		$test=$this->mongo_db->getWhere($collection,array('app_id'=>$appid)); 
		return $test[0];
	 }

	public function process_web_apps($usercollection,$appname)
	{	
	    $this->load->library('mongo_db');
		$this->mongo_db->where('app_name', $appname)->set('status','processed')->update($usercollection);
		
		
	}

	public function process_web_docs($usercollection,$appname,$docid)
	{	
	    
	    
		$doci = intval($docid);
		$this->mongo_db->where(array('app_name'=>$appname,'doc_id'=>$doci))->set('status','processed')->update($usercollection);
		
		
	} 

	public function web_doc_search($collection)
	{

        $this->load->library('mongo_db');
		$query=$this->mongo_db->get($collection);
		return $query;
	}

	public function update_read_apps($collection_name)
    {	
	    $this->mongo_db->select(array(),array('_id'))->where('status','processed');
		$query=$this->mongo_db->get($collection_name);
		
		return $query;
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the events for specific user
	 *
	 * @param string user collection
	 *
	 * @return array
	 *
	 * @author Selva 
	 */

	public function get_user_events($collection_name)
    {	
	    $this->mongo_db->select(array(),array('_id'));
		$query = $this->mongo_db->get($collection_name);
		return $query;
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the feebback for specific user
	 *
	 * @param string user collection
	 *
	 * @return array
	 *
	 * @author Vikas 
	 */

	public function get_user_feedbacks($collection_name)
    {	
	    $this->mongo_db->select(array(),array('_id'));
		$query = $this->mongo_db->where(array('reply'=>'Not yet filled'))->get($collection_name);
		return $query;
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the events for specific user
	 *
	 * @param string user collection
	 *
	 * @return array
	 *
	 * @author Selva 
	 */

	public function get_user_event_by_id($collection_name,$id)
    {	
	    $this->mongo_db->select(array(),array('_id'));
		$query = $this->mongo_db->where(array('event_properties.event_id'=>$id))->get($collection_name);
		return $query;
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update the event response for specific user
	 *
	 * @param string $collection_name  User collection name
	 * @param string $id               Event ID
	 * @param string $reply            Event Reply
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */

	public function update_user_event_response($collection_name,$id,$reply,$form_status)
    {	
		$query = $this->mongo_db->set(array('reply'=> $reply,'form_status'=>$form_status))->where('id',$id)->update($collection_name);
		if($query)
		{
		   return true;
		}
		else
        {
           return false;
        }		  
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update the feedback response for specific user
	 *
	 * @param string $collection_name  User collection name
	 * @param string $id               Event ID
	 * @param string $reply            Event Reply
	 *
	 * @return bool
	 *
	 * @author Vikas 
	 */

	public function update_user_feedback_response($collection_name,$id,$reply,$form_status)
    {	
		$query = $this->mongo_db->set(array('reply'=> $reply,'form_status'=>$form_status))->where('id',$id)->update($collection_name);
		if($query)
		{
		   return true;
		}
		else
        {
           return false;
        }		  
    }
	
	 // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the new push messages from user collection
	 *
	* @param string $collection_name  user push message collection
	 *
	 * @return array
	 *
	 * @author Selva 
	 */

		public function update_push_messages($collection_name)
		{	
			$this->mongo_db->select(array(),array('_id'));
			$query=$this->mongo_db->where('status','new')->get($collection_name);
			return $query;
		}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update push message status in user collection
	 *
	 * @param string $collection_name  user push message collection
	 *
	 * @author Selva 
	 */

	public function mark_read_push_notifications($collection_name)
    {	
	   $this->mongo_db->set('status','read')->where('status','new')->updateAll($collection_name);
    }
	
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update push message status in user collection
	 *
	 * @param string $collection_name  user push message collection
	 *
	 * @author Vikas 
	 */
	 
	function user_event_create($collection,$data)
    {
         $id = get_unique_id();
         $data['doc_properties']['doc_id'] = $id;
         $query = $this->mongo_db->insert($collection, $data);
         return $query;
    }
    
    /**
     * Helper: update push message status in user collection
     *
     * @param string $collection_name  user push message collection
     *
     * @author Vikas
     */
    
    function user_event_remove($collection,$id)
    {
    	$deleted = $this->mongo_db->where('event_properties.event_id', $id)->delete($collection);
    	
    	return $deleted;
    }
    
    /**
     * Helper: update push message status in user collection
     *
     * @param string $collection_name  user push message collection
     *
     * @author Vikas
     */
    
    function user_event_update($collection,$id,$data)
    {
    	$deleted = $this->mongo_db->set($data)->where('doc_properties.doc_id', $id)->update($collection);
    	 
    	return $deleted;
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: update push message status in user collection
	 *
	 * @param string $collection_name  user push message collection
	 *
	 * @author Vikas 
	 */
	 
	function user_feedback_create($collection,$data)
    {
         $id = get_unique_id();
         $data['doc_properties']['doc_id'] = $id;
         $query = $this->mongo_db->insert($collection, $data);
         return $query;
    }

	function doc_count()
	{
		$this->mongo_db->select('total_docs');
		$query = $this->mongo_db->get('total_docs');

		if (isset($query))
		{
		return $query[0]['total_docs'];
		}
		else
		{
		return 0;
		}

	}

	function doc_exist($collection,$id)
	{
		$query = $this->mongo_db->getWhere($collection, array('doc_properties.doc_id' => $id));

		$result = json_decode(json_encode($query), FALSE);

		if ($result)
		return TRUE;
		else
		return FALSE;
	}
	
	public function insert_request_note($post)
	{
		
		if($post['app_id'] == "healthcare2016531124515424")
		{
			$collection = $this->collections['panacea_req_notes'];
		}
		else if($post['app_id'] == "healthcare201610114435690")
		{
			$collection = $this->collections['tmreis_req_notes'];
		}
		else if($post['app_id'] == "healthcare2016108181933756")
		{
			$collection = $this->collections['ttwreis_req_notes'];
		}
		else if($post['app_id'] == "healthcare2018122191146894")
		{
			$collection = $this->collections['bc_welfare_req_notes'];
		}
		else
		{
			log_message('error',"post_iddddddddddddddddd".print_r($collection,true));
		}
				
		$query_request = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->get ( $collection );
		
		//log_message('debug','notessssssssssssssssspost===query_request==========================='.print_r($query_request,true));

		$note = json_decode($post['note'],true);
		
		$notes = array(
			'note_id' => get_unique_id(),
			'note'	  => $note['note'],
			'username'=> $note['username'],
			'datetime'=> $note['datetime']
		);
		
		if(isset($query_request[0]['notes_data'])){
			array_push($query_request[0]['notes_data'],$notes);
		}
		else{
			$query_request[0]['notes_data'] = [];
			array_push($query_request[0]['notes_data'],$notes);
			$query_request[0]["req_doc_id"] = $post ['doc_id'];
		}
		
		$is_notes = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->count( $collection );
		
		if($is_notes > 0){
			$token = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->set($query_request[0])->update( $collection );
		}else{
			$token = $this->mongo_db->insert( $collection, $query_request[0]);
		}
	
	   // log_message('debug','notessssssssssssssssspost===token==========================='.print_r($token,true));

		return $token;
	}
	
	public function get_request_note($doc_id,$app_id)
	{
		
		log_message("error","doc_id=====================631".print_r($doc_id,true));
		if($app_id == "healthcare2016531124515424"){
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['panacea_req_notes'] );
		}else if($app_id == "healthcare201610114435690"){
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['tmreis_req_notes'] );
		}else if($app_id == "healthcare2016108181933756"){
			
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['ttwreis_req_notes'] );
			
		}
		else if($app_id == "healthcare2018122191146894")
		{
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['bc_welfare_req_notes'] );
		}
		return $query_request;
	}
	
	public function delete_request_note($post)
	{
		$query_request = $this->mongo_db->where ( "doc_properties.doc_id", $post ['doc_id'] )->get ( $post ['app_id'] );
		
		foreach($query_request[0]['doc_data']['notes_data'] as $note => $note_data){
			if($note_data['note_id'] == $post ['note_id']){
				unset($query_request[0]['doc_data']['notes_data'][$note]);
			}
		}
		
		$token = $this->mongo_db->where ( "doc_properties.doc_id", $post ['doc_id'] )->set($query_request[0])->update( $post ['app_id'] );
	
		return $token;
	}
	
	
	public function screening_to_students_load_ehr_doc($post) {
		
		if(($post['type'] == "tswreis") || ($post['type'] == "healthcare2016531124515424")){
			
			$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history'
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $post['uniqueID'] )->get ( "healthcare2016226112942701" );
			if ($query) {
				$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( "healthcare2016531124515424" );
				$result ['screening'] = $query;
				$result ['request'] = $query_request;
				return $result;
			} else {
				$result ['screening'] = false;
				$result ['request'] = false;
				return $result;
			}
			
			
			

		}
		if(($post['type'] == "ttwreis") || ($post['type'] == "healthcare2016108181933756")){
			
			$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history'
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $post['uniqueID'] )->get ( "healthcare201671115519757" );
			if ($query) {
				$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( "healthcare2016108181933756" );
				$result ['screening'] = $query;
				$result ['request'] = $query_request;
				return $result;
			} else {
				$result ['screening'] = false;
				$result ['request'] = false;
				return $result;
			}
			
		}
		if(($post['type'] == "tmreis") || ($post['type'] == "healthcare201610114435690")){
			
			$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history'
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $post['uniqueID'] )->orderBy(array("history.0.time"=>-1))->get ( "healthcare201672020159570" );
			if ($query) {
				$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( "healthcare201610114435690" );
				$result ['screening'] = $query;
				$result ['request'] = $query_request;
				return $result;
			} else {
				$result ['screening'] = false;
				$result ['request']   = false;
				return $result;
			}
			
		}
		
		if(($post['type'] == "bcwelfare") || ($post['type'] == "healthcare2018122191146894")){
			
			$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history'
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $post['uniqueID'] )->get ( "healthcare201812217594045" );
			if ($query) {
				$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( "healthcare2018122191146894" );
				$result ['screening'] = $query;
				$result ['request'] = $query_request;
				return $result;
			} else {
				$result ['screening'] = false;
				$result ['request'] = false;
				return $result;
			}
			
			
			

		}
		
		
		
	}
	
	public function get_student_req($post) {
		
		if(($post['type'] == "tswreis") || ($post['type'] == "healthcare2016531124515424")){
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $post['uniqueID'] )->get ( "healthcare2016531124515424" );
			return $query_request;
		}
		if(($post['type'] == "ttwreis") || ($post['type'] == "healthcare2016108181933756")){
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $post['uniqueID'] )->get ( "healthcare2016108181933756" );
			return $query_request;
			
		}
		if(($post['type'] == "tmreis") || ($post['type'] == "healthcare201610114435690")){
			
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $post['uniqueID'] )->get ( "healthcare201610114435690" );
			return $query_request;
		}		
		
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: upload audio attachments
	 *
	 * @param string $app_id  	 Application Id
	 * @param string $doc_id  	 Document Id
	 * @param string $file_data  Audio file related data
	 *
	 * @return bool
	 *
	 * @author Selva 
	 */

	public function upload_call_audio_file_model($app_id,$doc_id,$file_data)
	{
		
		if($app_id == "healthcare2016531124515424")
		{
			$collection = $this->collections['panacea_req_audio'];
		}
		else if($app_id == "healthcare201610114435690")
		{
			$collection = $this->collections['tmreis_req_audio'];
		}
		else if($app_id == "healthcare2016108181933756")
		{
			$collection = $this->collections['ttwreis_req_audio'];
		}
		
		$query_request = $this->mongo_db->where("req_doc_id",$doc_id)->get ($collection);
		
		
		$attach = array(
			'attachment_id' 	=> get_unique_id(),
			'audio_attachments'	=> $file_data,
			'datetime'			=> date('Y-m-d H:i:s')
		);
		
		if(isset($query_request[0]['attachment'])){
			array_push($query_request[0]['attachment'],$attach);
		}
		else{
			$query_request[0]['attachment'] = [];
			array_push($query_request[0]['attachment'],$attach);
			$query_request[0]["req_doc_id"] = $doc_id;
		}
		
		$is_audio = $this->mongo_db->where("req_doc_id",$doc_id)->count($collection);
		
		if($is_audio > 0)
		{
			$token = $this->mongo_db->where("req_doc_id",$doc_id)->set($query_request[0])->update($collection);
		}
		else
		{
			$token = $this->mongo_db->insert($collection, $query_request[0]);
		}

		return $token;
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get audio attachments
	 *
	 * @param string $app_id  	 Application Id
	 * @param string $doc_id  	 Document Id
	 *
	 * @return array
	 *
	 * @author Selva 
	 */

	public function get_call_audio_file_model($doc_id,$app_id)
	{
		if($app_id == "healthcare2016531124515424")
		{
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['panacea_req_audio'] );
		}
		else if($app_id == "healthcare201610114435690")
		{
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['tmreis_req_audio'] );
		}
		else if($app_id == "healthcare2016108181933756")
		{
			$query_request = $this->mongo_db->where ( "req_doc_id", $doc_id )->get ( $this->collections['ttwreis_req_audio'] );
		}
		
        if(isset($query_request[0]['attachment']) && !empty($query_request[0]['attachment']))
        {
			return $query_request[0]['attachment'];
	    }
	    else
	    {
	    	return FALSE;
	    }
	}
	
	public function get_districts_list_model()
	 {
			$query = $this->mongo_db->orderBy ( array (
					'dt_name' => 1 
			) )->get ( 'panacea_district' );
		if($query){
			return $query;
		}else{
			return FALSE;
		}
	}

	public function get_schools_by_district_id($dist_id) {
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name'
			) )->orderBy ( array (
					'school_name' => 1 
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['panacea_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}

}