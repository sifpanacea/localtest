<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_App_model extends CI_Model 
{
	 function __construct()
    {
        parent::__construct();
        
        $this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
        
        // Initialize MongoDB collection names
        $this->collections = $this->config->item('collections', 'ion_auth');
        $this->_configvalue = $this->config->item('default');
        $this->common_db   = $this->config->item('default');
    }
    
    
	/**
	 * Retrives patients information from db
	 * 
	 * @author Vikas
	 * 
	 * @param string $userID User id of patient
	 * @param int $passward Password of patient
	 * @return array
	 */
	public function get_patient($userID,$passward)
	{
		$query= $this->mongo_db->getWhere($this->collections['form_users'], array('unique_id' => $userID, 'password' => $passward));
		return $query;
	}
	
	/**
	 * Returns documents containing userid
	 * 
	 * @author Vikas
	 * 
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_user_documents($userID)
	{
		
		$app_ids= $this->mongo_db->select(array('_id'))->get($this->collections['records']);
		$douments = [];
		foreach ($app_ids as $app_id){
			$document = $this->mongo_db->select(array('doc_data','app_properties','doc_properties'))->getWhere($app_id['_id'], array('doc_data.patient_uniqueID' => $userID));
			$pages = $this->mongo_db->getWhere($this->collections['records'], array('_id' => $app_id['_id']));
			if($document != array()){
				foreach ($document as $doc){
					$doc['pages'] = $pages[0]['pages'];
					if(isset($doc['doc_data']['strokes_data'])){
						$doc_count = count($doc['doc_data']['strokes_data']);
						if($pages[0]['pages'] <= $doc_count){
							$document[0]['pages'] = $pages[0]['pages'];
						}else{
							$document[0]['pages'] = $doc_count;
						}
					}else{
						$document[0]['pages'] = $pages[0]['pages'];
					}
					array_push($douments, $doc);
			}}
		}
	
	    log_message('debug','$douments=====patient_app_model=====68'.print_r($douments,true));
		
		return $douments;
	}
	
	/**
	 * Returns documents containing userid
	 *
	 * @author Vikas
	 *
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_external_attachment($app_id,$doc_id)
	{
		$document = $this->mongo_db->select(array('doc_data'))->getWhere($app_id, array('doc_properties.doc_id' => $doc_id));
		//log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($document,true));
		return $document;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Inserts push message to receiver's collection
	 * 
	 * @author Vikas
	 * 
	 * @param string $receiver
	 * @param string $message_id
	 * @param string $message
	 * @param string $userID
	 */
	public function send_push_message($receiver,$message_id,$message,$userID)
	{
		$data = array(
				'message_id'    => $message_id,
				'message'       => $message,
				'sent_time'     => date('Y-m-d H:i:s'),
				'sent_source'   => 'patient',
				'message_owner' => $userID,
				'status'        => 'new'
		);
		 
		$query = $this->mongo_db->insert($receiver,$data);
		return $query;
	}
	
	function get_doc($collection,$doc_id)
	{
		//log_message('debug','innnnnnnnnnnngggggggggggggggggggggggggggggggggggggggggggggggggg'.print_r($data,true));
		$query = $this->mongo_db->where('doc_properties.doc_id', $doc_id)->get($collection);
		
		//log_message('debug','oooooooooooooooooggggggggggggggggggggggggggggggggggggggggggggg'.print_r($query,true));
		return $query[0];
	}
	
	function update_attributes($id,$data)
	{
		unset($data['_id']);
		//log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn'.print_r($data,true));
		$val = $id;
		//log_message('debug','1111111111111111111111111111111111111111111111111111111111)))))'.$id);
		$_id = get_unique_id();
		//log_message('debug','22222222222222222222222222222222222222222222222222'.print_r($this->mongo_db->where('doc_properties.doc_id', $val)->get('moonpharma20157215410391'),true));
		$ver = 4;//moonpharma20157215410391_mod::version_inc($val);
		//log_message('debug','333333333333333333333333333333333333333333333333');
		
		$this->mongo_db->where('doc_properties.doc_id', $val)->set($data)->update($data['app_properties']['app_id']);
		//log_message('debug','444444444444444444444444444444444444444444444444444444444444444444444444');
		$query = $this->mongo_db->insertDocument($data['app_properties']['app_id'].'_shadow', $data, $_id, $val-1, $data['app_properties']['app_name'], $ver);
		//log_message('debug','ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		return $query;
	}
	
	/**
	 * Returns documents containing userid
	 *
	 * @author Vikas
	 *
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_patient_users()
	{
	
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$users= $this->mongo_db->select(array(),array('_id','password','device_unique_number','plan_subscribed','subscription_start','subscription_end','active','status','ip_address','registered_on','last_login'))->get($this->collections['users']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		return $users;
	}
	
	/**
	 * Returns documents containing userid
	 *
	 * @author Vikas
	 *
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_users_appointments($collection)
	{
	
	
		$appointments = $this->mongo_db->get($collection);
	
		return $appointments;
	}
	
	/**
	 * Returns documents containing userid
	 *
	 * @author Vikas
	 *
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_users_appointments_by_date($collection,$date)
	{
	
		
		$appointments = $this->mongo_db->where(array('appointment_date' => $date))->get($collection);
	
		return $appointments;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: get the new push messages from collection
	 *
	 * @param string $collection_name  user push message collection
	 *
	 * @return array
	 *
	 * @author Vikas
	 */
	
	public function update_push_messages($collection_name)
	{
		$this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->where('status','read')->get($collection_name);
		return $query;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: update push message status in collection
	 *
	 * @param string $collection_name  user push message collection
	 *
	 * @author Vikas
	 */
	
	public function mark_read_push_notifications($collection_name)
	{
		$this->mongo_db->set('status','read')->where('status','new')->updateAll($collection_name);
	}
	
	/**
	 * Inserts appointment to user's collection
	 *
	 * @author Vikas
	 *
	 * @param array $data
	 */
	public function place_appointment($data)
	{
		$u = $this->session->userdata("customer");
		log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($u,true));
	
		$unique_id	= get_unique_id();
		$user_email = str_replace('@', '#', $data['user_email']);
	
		$insert = array(
				'appointment_date' => $data['appo_date'],
				'appointment_time' => $data['appo_time'],
				'appointment_title'=> $data['text'],
				'user_id'		   => $user_email,
				'username'		   => $data['username'],
				'patient_uniqueID' => $u['identity'],
				'company'		   => $u['company'],
				'appointment_id'   => $unique_id
		);
			
		$this->mongo_db->insert($user_email.'_appointments',$insert);
		$this->mongo_db->insert($u['identity'].'_appointments',$insert);
	}
	
	/**
	 * Returns documents containing userid
	 *
	 * @author Vikas
	 *
	 * @param int $userID User id of patient
	 * @return array
	 */
	public function get_patient_appointments($patient_col)
	{
		$users= $this->mongo_db->orderBy(array('appointment_date' => 1))->get($patient_col."_appointments");
		return $users;
	}
	
	function update_appointment_content($user_id,$appointment_id,$title)
	{
		$user_col = $user_id.'_appointments';
		$u = $this->session->userdata("customer");
	
		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->set(array('appointment_title' => $title))->update($u['identity'].'_appointments');
	
		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($u['identity'].'_appointments');
		
		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->set(array('appointment_title' => $title))->update($user_col);
		
		return $query;
	}
	

	function delete_appointment($user_col,$appointment_id)
	{
		$u = $this->session->userdata("customer");
		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($user_col);
	
		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->delete($user_col);
	
		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->delete($u['identity'].'_appointments');
	
		return $query;
	}
	
	// ---------------------------------------------------------------------------------------
	
	
	
	function update_appointment($user_col,$appointment_id,$date,$time)
	{
		$u = $this->session->userdata("customer");

		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->set(array('appointment_date' => $date,'appointment_time' => $time))->update($user_col);

		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($user_col);
		
		log_message('debug','111111111111111111111111111111111111111111111111111111'.print_r($user_col,true));
		log_message('debug','22222222222222222222222222222222222222222222222'.print_r($appointment_id,true));
		log_message('debug','3333333333333333333333333333333333333333333333'.print_r($unique_id,true));
		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->set(array('appointment_date' => $date,'appointment_time' => $time))->update($u['identity'].'_appointments');

		return $query;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Store vital graph data
	 *
	 *
	 * @author Selva
	 */
	
	public function store_patient_vital_graph_values_model($patient_id,$graph_data,$initial_stage)
	{
	    $patient_graph_col = $patient_id."_graphs";
		
		if($initial_stage == "true")
		{
	       $data = array(
				'graph_id'      => $patient_id,
				'graph_data'    => $graph_data,
				'sent_time'     => date('Y-m-d H:i:s')
		   );
		 
		   $query = $this->mongo_db->insert($patient_graph_col,$data);
		}
		else
		{
	       $query = $this->mongo_db->where('graph_id',$patient_id)->set(array('graph_data' => $graph_data,'sent_time'=> date('Y-m-d H:i:s')))->update($patient_graph_col);
		}
		
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
	  * Helper  Graph Data
	  *
	  * @author Selva
	  */
	 
	 public function reset_patient_vital_graph_model($patient_id)
	 {
	    $patient_graph_col = $patient_id."_graphs";
		
	    $query = $this->mongo_db->where('graph_id',$patient_id)->delete($patient_graph_col);
		
		if($query)
		   return TRUE;
	    else
		   return FALSE;
		
	}
}


