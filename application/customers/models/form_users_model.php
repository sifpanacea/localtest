<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Form_users_model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
		$this->load->library('mongo_db');
        $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
	    $this->collections  = $this->config->item('collections','ion_auth');
	    $this->load->library('mongo_db');
		$this->load->helper('paas_helper');
    } 
    
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Save user ( form users ) details from device end
	*
	* @param string  $sender   Doctor Reference ( Email Id )
	* @param string  $name     Name of the form user 
	* @param string  $email    Email Id of the form user
	* @param string  $dob      DOB of form user
	* @param array   $mobile   Mobile number details ( with country code ) of the form user
	* @param string  $address  Address of the form user
	*
	* @return bool
	*
	* @author Selva
	*/

	public function save_form_user_details_model($sender,$name,$email,$dob,$mobile,$address)
	{
	    $data = array(
		'name'    		=> $name,
		'email'   		=> $email,
		'dob'     		=> $dob,
		'mobile'  		=> $mobile,
		'address'       => $address,
		'grouped_under' => $sender
		);
		
		$query = $this->mongo_db->insert($this->collections['form_users'],$data);
		
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
	* Helper: Check if the mobile number is already registered
	*
	* @param array  $mobile  Mobile Number Details
	* @param string $sender  Doctor Reference ( Email Id )
	*
	* @return bool
	*
	* @author Selva
	*/

	public function check_if_mobile_number_already_exists($mobile,$sender)
	{
	    $country_code = $mobile['country_code'];
		$mobile_no    = $mobile['mob_num'];
		
		$query = $this->mongo_db->getWhere($this->collections['form_users'], array('mobile.mob_num' => $mobile_no,'grouped_under'=>$sender));

        $result = json_decode(json_encode($query), FALSE);

		if ($result)
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
	* Helper: Fetch form user details 
	*
	* @param  string  $sender   Doctor Reference ( EMAIL ID )
	*
	* @return array
	*
	* @author Selva
	*/

	public function fetch_form_user_details($sender)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->select(array('patient_name'),array())->getWhere($this->collections['form_users'], array('grouped_under'=>$sender));
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
        return $query;  
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Fetch form user details by id 
	*
	* @param  string  $id   Id of the user
	*
	* @return array
	*
	* @author Selva
	*/
	
	public function user_details_by_id($id)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->where(array('_id'=> new MongoId($id)))->get($this->collections['form_users']);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $query;
	}
	
	// ------------------------------------------------------------------------------------------

	/**
	* Helper: Search documents ( irrespective of applications ) using unique id of the form user
	*
	* @param  string  $unique_id   Unique Id of the form user ( Mobile number )
	*
	* @return array
	*
	* @author Selva
	*/
	
	public function search_documents_by_unique_id($unique_id)
	{
	   $query        = array();
	   $this->mongo_db->orderBy(array('time' => -1));
	   $this->mongo_db->select(array('_id','notify_parameters'),array());
	   $result_id = $this->mongo_db->get($this->collections['records']);
	   
	   // NOTIFICATION PARAM & DOC ID
	   foreach($result_id as $id)
	   {
	      $widget_param    = array();
	      $param           = $id['notify_parameters'];
		  $collection_name = $id['_id'];
		  
		  foreach($param as $param_data)
		  {
            $field   = $param_data['field'];
			$pageno  = $param_data['page'];
			$section = $param_data['section'];
			$value   = 'doc_data.widget_data.'.'page'.$pageno.'.'.$section.'.'.$field;
			array_push($widget_param,$value);
		  }

		  $count = count($widget_param);
		  for($i=0;$i<$count;$i++)
		  {
			  $this->mongo_db->select(array($widget_param[$i]));
		  }
		  $this->mongo_db->where(array('doc_properties.unique_id'=>$unique_id,'doc_properties.status'=>1));
		  $this->mongo_db->orderBy(array('history.last_stage.time' => -1));
		  $this->mongo_db->select(array('doc_properties.doc_id','app_properties'));
		  $query = array_merge($query,$this->mongo_db->get($collection_name));
	      
	   }
	   
	   $obj = json_decode(json_encode($query), FALSE);
	   return $obj;
	}
	
	/**
     * Helper: Search by Extend Unique ID ( Advanced search )
     *
     *
     * @author Naresh
     */
	public function search_documents_by_uid($unique_id,$user_email=false)
	{
		log_message('debug','user_email========================183'.print_r($unique_id,true));
		log_message('debug','user_email============================184'.print_r($user_email,true));
		$app_ids= $this->mongo_db->get($this->collections['records']);
		
		log_message('debug','app_ids============================187'.print_r($app_ids,true));
		$douments = [];
		foreach ($app_ids as $app_id){
			$widget_param    = array();
			$param           = $app_id['notify_parameters'];
			
			foreach($param as $param_data)
			{
				$field   = $param_data['field'];
				$pageno  = $param_data['page'];
				$section = $param_data['section'];
				$value   = 'doc_data.widget_data.'.'page'.$pageno.'.'.$section.'.'.$field;
				array_push($widget_param,$value);
			}
			
			$count = count($widget_param);
			log_message('debug','count===================202'.print_r($count,true));
			for($i=0;$i<$count;$i++)
			{
			$this->mongo_db->select(array($widget_param[$i]));
			}

			/* $document = $this->mongo_db->select(array('doc_properties.doc_id','app_properties'))->getWhere($app_id['_id'], array('doc_data.patient_uniqueID' => $unique_id,'doc_data.user_name' => str_replace("@","#",$user_email))); */
			
			$document = $this->mongo_db->select(array('doc_data.widget_data','doc_properties.doc_id','app_properties'))->getWhere($app_id['_id'], array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id));
			if($document != array()){
				foreach ($document as $doc){
					array_push($douments, $doc);
				}
			}
		}
		log_message('debug','douments============================214'.print_r($douments,true));
		return $douments;
	}
	
	/**
     * Helper: Search by Unique ID ( Advanced search )
     *
     *
     * @author Selva
     */
	 
	 /*public function search_documents_by_uid($unique_id,$user_email)
	{
		
		$app_ids= $this->mongo_db->get($this->collections['records']);
		
		log_message('debug','app_ids============================187'.print_r($app_ids,true));
		$douments = [];
		foreach ($app_ids as $app_id){
			$widget_param    = array();
			$param           = $app_id['notify_parameters'];
			
			foreach($param as $param_data)
			{
				$field   = $param_data['field'];
				$pageno  = $param_data['page'];
				$section = $param_data['section'];
				$value   = 'doc_data.widget_data.'.'page'.$pageno.'.'.$section.'.'.$field;
				array_push($widget_param,$value);
			}
			
			$count = count($widget_param);
			
			for($i=0;$i<$count;$i++)
			{
			$this->mongo_db->select(array($widget_param[$i]));
			}
			$document = $this->mongo_db->select(array('doc_properties.doc_id','app_properties'))->getWhere($app_id['_id'], array('doc_data.patient_uniqueID' => $unique_id,'doc_data.user_name' => str_replace("@","#",$user_email))); 
		
			if($document != array()){
				foreach ($document as $doc){
					array_push($douments, $doc);
				}
			}
		}
		
		return $douments;
	} */
	
	// ------------------------------------------------------------------------------------------

	/**
	* Helper: Save birthday event in form users event collection ( IN COMMON DATABASE ) 
	*
	* @param  string  $message  Wish message to be sent
	* @param  string  $month    Month
	* @param  string  $date     Date 
	* @param  string  $mobile   Mobile number to which sms has to be sent
	* @param  string  $email    Email Id to which email has to be sent
	*
	*
	* @author Selva
	*/
	
	public function save_birthday_event($message,$date,$mobile,$email)
	{
	  $b_array = array(
	  'message' 	=> $message,
	  'date'    	=> $date,
	  'event'   	=> 'birthday',
	  'mobile'  	=> $mobile,
	  'email'       => $email,
	  'sender_name' => $sender_name);
	  
	  $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	  $this->mongo_db->insert($this->collections['form_users_events'],$b_array);
	  $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	}

}