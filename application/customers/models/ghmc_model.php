<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ghmc_model extends CI_Model 
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
	 * Retrives ghmc trips per day information from db
	 * 
	 * @author Vikas
	 * 
	 * @param string $date current date
	 * @return array
	 */
	public function get_total_trip($date)
	{
		$query= $this->mongo_db->select(array('weighbridge'))->getWhere('trips_details', array('date' => $date));
		$weight_val = 0;
		foreach ($query as $weight){
			$weight_val = $weight_val + floatval($weight['weighbridge']);
		}
		
		$today_data['trips'] = count($query);
		$today_data['weight'] = $weight_val;
		
		
		return $today_data;
	}
	
	public function get_total_trip_for_dates($dates)
	{
		$query= $this->mongo_db->select(array('weighbridge'))->whereIn('date', $dates)->get('trips_details');
		$weight_val = 0;
		foreach ($query as $weight){
			$weight_val = $weight_val + floatval($weight['weighbridge']);
		}
	
		$today_data['trips'] = count($query);
		$today_data['weight'] = $weight_val;
	
	
		return $today_data;
	}
	
	public function get_all_trips()
	{
		$query= $this->mongo_db->orderBy(array("date"=>"desc"))->get('trips_details');
		
		$query= json_decode(json_encode($query),true);
		
		
		
		$date = array();
		foreach ($query as $trip){
			array_push($date,$trip['date']);
		}
		//log_message('debug','datessssssssssssssssssssssss'.print_r($date,true));
		$date = array_unique($date);
		foreach ($date as $dt){
			$time[$dt] = array();
			$query= $this->mongo_db->where('date', $dt)->get('trips_details');
			foreach ($query as $trip){
				array_push($time[$dt],$trip['time']);
				$time_val = $trip['time'];
				$weight[$dt."_".$time_val] = array();
				array_push($weight[$dt."_".$time_val], $trip['user_mobile']." brought garbage of ".$trip['weighbridge']." weight.");
				array_push($weight[$dt."_".$time_val], $trip['_id']);
			}
		}
		$result['dates'] = $date;
		if ($query) {
			$result['times'] = $time;
			$result['weight_details'] = $weight;;
		}else{
			$result['times'] = false;
			$result['weight_details'] = false;
		}
		log_message('debug','trippppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($result,true));
		return $result;
	}
	
	public function get_trip_details($trip_id)
	{
		$query= $this->mongo_db->where('_id', new MongoID($trip_id))->get('trips_details');
	
		return $query[0];
	}
	
	/**
	 * Retrives ghmc trips per day information from db
	 *
	 * @author Vikas
	 *
	 * @param string $date current date
	 * @return array
	 */
	public function submit_trip($array_data)
	{
		$query= $this->mongo_db->insert("trips_details", $array_data);
		
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
	
		return $douments;
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
		 
		$this->mongo_db->insert($receiver,$data);
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
		$users= $this->mongo_db->get($this->collections['users']);
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
	 * Inserts appointment to user's collection
	 *
	 * @author Vikas
	 *
	 * @param string $receiver
	 * @param string $message_id
	 * @param string $message
	 * @param string $userID
	 */
	public function place_appointment($data)
	{
		$u = $this->session->userdata("customer");
		log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($u,true));
		
		$unique_id	= get_unique_id();
		
		$insert = array(
			'appointment_date' => $data['appo_date'],
			'appointment_time' => $data['appo_time'],
			'patient_uniqueID' => $u['unique_id'],
			'appointment_title'=> $data['text'],
			'appointment_desc' => $data['desc'],
			'user_id'		   => $data['user_email'],
			'username'		   => $data['username'],
			'company'		   => $u['company'],
			'appointment_id'   => $unique_id
		);
			
		$this->mongo_db->insert($data['user_email'].'_appointments',$insert);
		$this->mongo_db->insert($u['unique_id'].'_appointments',$insert);
	}
	
	function update_appointment($user_col,$appointment_id,$date,$time)
	{
		$u = $this->session->userdata("customer");
		
		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->set(array('appointment_date' => $date,'appointment_time' => $time))->update($user_col);
		
		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($user_col);
		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->set(array('appointment_date' => $date,'appointment_time' => $time))->update($u['unique_id'].'_appointments');
		
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
	public function get_patient_appointments($patient_col)
	{
		$users= $this->mongo_db->orderBy(array('appointment_date' => 1))->get($patient_col."_appointments");
		return $users;
	}
	
	function delete_appointment($user_col,$appointment_id)
	{
		$u = $this->session->userdata("customer");
		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($user_col);
		
		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->delete($user_col);

		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->delete($u['unique_id'].'_appointments');
	
		return $query;
	}
	
	function update_appointment_content($user_id,$appointment_id,$title,$desc)
	{
		$user_col = $user_id.'_appointments';
		$u = $this->session->userdata("customer");
	
		$query = $this->mongo_db->where('_id', new MongoId($appointment_id))->set(array('appointment_title' => $title,'appointment_desc' => $desc))->update($u['unique_id'].'_appointments');
	
		$unique_id = $this->mongo_db->where('_id', new MongoId($appointment_id))->get($u['unique_id'].'_appointments');
		$query = $this->mongo_db->where('appointment_id', $unique_id[0]['appointment_id'])->set(array('appointment_title' => $title,'appointment_desc' => $desc))->update($user_col);
	
		return $query;
	}
}


