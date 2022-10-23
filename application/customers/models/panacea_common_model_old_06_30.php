<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panacea_common_model extends CI_Model 
{
	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 */
	protected $errors;
	
	/**
	 * error start delimiter
	 *
	 * @var string
	 */
	protected $error_start_delimiter;
	
	/**
	 * error end delimiter
	 *
	 * @var string
	 */
	protected $error_end_delimiter;
	
	 function __construct()
    {
        parent::__construct();
        
        $this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
        
        // Initialize MongoDB collection names
        $this->collections = $this->config->item('collections', 'ion_auth');
        $this->_configvalue = $this->config->item('default');
        $this->common_db   = $this->config->item('default');
        
        $this->store_salt      = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length     = $this->config->item('salt_length', 'ion_auth');
        
        // Initialize hash method directives (Bcrypt)
        $this->hash_method    = $this->config->item('hash_method', 'ion_auth');
        
        //$this->common_db = $this->config->item('default');
        
        $this->screening_app_col = "healthcare2016226112942701";
        $this->absent_app_col = "healthcare201651317373988";
        $this->request_app_col = "healthcare2016531124515424";
        $this->today_date = date('Y-m-d');
    }

    public function statescount()
    {
    	$count = $this->mongo_db->count('panacea_states');
    	return $count;
    }
    
    public function get_states($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_states');
    	return $query;
    }
    
    public function get_all_states()
    {
    	$query = $this->mongo_db->get('panacea_states');
    	return $query;
    }
    
    //=================================================
    
    public function distcount()
    {
    	$count = $this->mongo_db->count('panacea_district');
    	return $count;
    }
    
    public function get_district($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_district');
    	foreach($query as $distlist => $dist){
    		$st_name = $this->mongo_db->where('_id', new MongoId($dist['st_name']))->get('panacea_states');
    		if(isset($dist['st_name'])){
    			$query[$distlist]['st_name'] = $st_name[0]['st_name'];
    		}else{
    			$query[$distlist]['st_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
    
    public function get_all_district()
    {
    	$query = $this->mongo_db->get('panacea_district');
    
    	return $query;
    }
    
    public function health_supervisorscount()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['panacea_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_health_supervisors($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['panacea_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    public function create_health_supervisors($post)
    {
    	$this->load->config('ion_auth', TRUE);
    
    	$email = strtolower($post['health_supervisors_email']);
    	$password = $post['health_supervisors_password'];
    
    	// Check if email already exists
    	if ($this->user_exists($email))
    	{
    		$this->set_error('account_creation_duplicate_email');
    		return FALSE;
    	}
    
    	// IP address
    	$ip_address = $this->_prepare_ip($this->input->ip_address());
    	$salt       = $this->store_salt ? $this->salt() : FALSE;
    	$password   = $this->hash_password($password, $salt);
    
    	// New user document
    	$data = array(
    			"school_code" => $post['school_code'],
    			"hs_name" => $post['health_supervisors_name'],
    			"hs_mob" => $post['health_supervisors_mob'],
    			"hs_ph" => $post['health_supervisors_ph'],
    			"password" => $password,
    			"email" => $email,
    			"hs_addr" => $post['health_supervisors_addr'],
    
    			"username"=> $post['health_supervisors_name'],
    			'ip_address' => $ip_address,
    			'created_on' => time(),
    			'registered_on' => date("Y-m-d"),
    			'last_login' => date("Y-m-d H:i:s"),
    			//'active'     => ($admin_manual_activation === FALSE ? 1 : 0),
    			'active'     => 1,
    			'company' => $this->session->userdata("customer")['company'],
    	);
    
    	// Store salt in document?
    	if ($this->store_salt)
    	{
    		$data['salt'] = $salt;
    	}
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->insert($this->collections['panacea_health_supervisors'],$data);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($query) ? $query : FALSE;
    }
    
    /////////////////////////////////////////////////////////////
    
    
    
    
    public function cc_users_count()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['panacea_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_cc_users($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['panacea_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    public function create_cc_user($post)
    {
    	$this->load->config('ion_auth', TRUE);
    
    	$email = strtolower($post['email']);
    	$password = $post['password'];
    
    	// Check if email already exists
    	if ($this->cc_user_exists($email))
    	{
    		$this->set_error('account_creation_duplicate_email');
    		return FALSE;
    	}
    
    	// IP address
    	$ip_address = $this->_prepare_ip($this->input->ip_address());
    	$salt       = $this->store_salt ? $this->salt() : FALSE;
    	$password   = $this->hash_password($password, $salt);
    
    	// New user document
    	$data = array(
    			"name" => $post['cc_user_name'],
    			"mobile_number" => $post['cc_user_mob'],
    			"phone_number" => $post['cc_user_ph'],
    			"password" => $password,
    			"email" => $email,
    			"company_address" => $post['cc_user_addr'],
    
    			"username"=> $post['cc_user_name'],
    			'ip_address' => $ip_address,
    			'created_on' => time(),
    			'registered_on' => date("Y-m-d"),
    			'last_login' => date("Y-m-d H:i:s"),
    			//'active'     => ($admin_manual_activation === FALSE ? 1 : 0),
    			'active'     => 1,
    			'company_name' => $this->session->userdata("customer")['company'],
    	);
    
    	// Store salt in document?
    	if ($this->store_salt)
    	{
    		$data['salt'] = $salt;
    	}
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->insert($this->collections['panacea_cc'],$data);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($query) ? $query : FALSE;
    }
    
    public function delete_cc_user($cc_id)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($cc_id)))->delete($this->collections['panacea_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    
    
    //////////////////////////////////////////////////////////////
    
    
    public function doctorscount()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['panacea_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_doctors($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['panacea_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    
    /////////////////////////////////////////////////////////////////////
    
    
    public function schoolscount()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['panacea_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_schools($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['panacea_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	foreach($query as $schools => $school){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($school['dt_name']))->get('panacea_district');
    		if(isset($school['dt_name'])){
    			$query[$schools]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$schools]['dt_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
    
    public function create_school($post)
    {
    	$this->load->config('ion_auth', TRUE);

    	$email = strtolower($post['school_email']);
    	$password = $post['school_password'];

    	// Check if email already exists
    	if ($this->school_exists($email))
    	{
    		$this->set_error('account_creation_duplicate_email');
    		return FALSE;
    	}

    	// IP address
    	$ip_address = $this->_prepare_ip($this->input->ip_address());
    	$salt       = $this->store_salt ? $this->salt() : FALSE;
    	$password   = $this->hash_password($password, $salt);

    	$data = array(
    			"dt_name" => $post['dt_name'],
    			"school_code" => $post['school_code'],
    			"school_name" => $post['school_name'],
    			"school_addr" => $post['school_addr'],
    			"password" => $password,
    			"email" => $email,
    			"school_ph" => $post['school_ph'],
    			"school_mob" => $post['school_mob'],
    			"contact_person_name" => $post['contact_person_name'],
    			 
    			"username"=> $post['school_name'],
    			'ip_address' => $ip_address,
    			'created_on' => time(),
    			'registered_on' => date("Y-m-d"),
    			'last_login' => date("Y-m-d H:i:s"),
    			//'active'     => ($admin_manual_activation === FALSE ? 1 : 0),
    			'active'     => 1,
    			'company' => $this->session->userdata("customer")['company'],);
    	// Store salt in document?
    	if ($this->store_salt)
    	{
    		$data['salt'] = $salt;
    	}
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->insert($this->collections['panacea_schools'],$data);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($query) ? $query : FALSE;
    }
    
    public function get_all_schools()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->get($this->collections['panacea_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	foreach($query as $schools => $school){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($school['dt_name']))->get('panacea_district');
    		if(isset($school['dt_name'])){
    			$query[$schools]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$schools]['dt_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
    
    public function classescount()
    {
    	$count = $this->mongo_db->count('panacea_classes');
    	return $count;
    }
    
    public function get_classes($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_classes');
    	return $query;
    }
    
    public function sectionscount()
    {
    	$count = $this->mongo_db->count('panacea_sections');
    	return $count;
    }
    
    public function get_sections($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_sections');
    	return $query;
    }
    
    public function symptomscount()
    {
    	$count = $this->mongo_db->count('panacea_symptoms');
    	return $count;
    }
    
    public function get_symptoms($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_symptoms');
    	return $query;
    }
    
    public function get_reports_ehr($ad_no)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->whereLike("doc_data.widget_data.page2.Personal Information.AD No", $ad_no)->get($this->screening_app_col);
    	if($query){
    		$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $query[0]["doc_data"]['widget_data']['page1']['Personal Information']['Hospital Unique ID'])->get($this->request_app_col);
    		$result['screening'] = $query;
    		$result['request'] = $query_request;
    		return $result;
    	}else{
    		$result['screening'] = false;
    		$result['request'] = false;
    		return $result;
    	}
    }
    
    public function get_reports_ehr_uid($uid)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->whereLike(
    			"doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid)->get($this->screening_app_col);
    	if($query){
    		$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $uid)->get($this->request_app_col);
    		$result['screening'] = $query;
    		$result['request']	 = $query_request;
    		return $result;
    	}else{
    		$result['screening'] = false;
    		$result['request'] = false;
    		return $result;
    	}
    }
    
    public function get_students_uid($uid)
    {
		//$query = $this->mongo_db->where("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid)->get("naresh");
    	$query = $this->mongo_db->where("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid)->get($this->screening_app_col);
    	if($query){
    
    		return $query[0];
    	}else{
    		return false;
    	}
    }
    
    public function create_diagnostic($post)
    {
    	$data = array(
    			"dt_name" => $post['dt_name'],
    			"diagnostic_code" => $post['diagnostic_code'],
    			"diagnostic_name" => $post['diagnostic_name'],
    			"diagnostic_ph" => $post['diagnostic_ph'],
    			"diagnostic_mob" => $post['diagnostic_mob'],
    			"diagnostic_addr" => $post['diagnostic_addr'],);
    	$query = $this->mongo_db->insert('panacea_diagnostics',$data);
    	return $query;
    }
    


    public function create_hospital($post)
    {
    	$data = array(
    			"dt_name" => $post['dt_name'],
    			"hospital_code" => $post['hospital_code'],
    			"hospital_name" => $post['hospital_name'],
    			"hospital_ph" => $post['hospital_ph'],
    			"hospital_mob" => $post['hospital_mob'],
    			"hospital_addr" => $post['hospital_addr'],);
    	$query = $this->mongo_db->insert('panacea_hospitals',$data);
    	return $query;
    }
    
    public function update_student_data($doc,$doc_id)
    {
		//$query = $this->mongo_db->where("_id", $doc_id)->set($doc)->update("naresh");
    	$query = $this->mongo_db->where("_id", $doc_id)->set($doc)->update($this->screening_app_col);
    	 
    	return $query;
    }
    
    public function studentscount()
    {
    	$count = $this->mongo_db->count($this->screening_app_col);
    	return $count;
    }
    
    public function get_students($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	return $query;
    }
    
    public function get_all_students()
    {
    	ini_set('memory_limit', '1G');
    	
    	//$merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Over Weight","Under Weight")));
    	$count = $this->mongo_db->count($this->screening_app_col);
    	//log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    	$per_page = 1000;
    	$loop = $count/$per_page;

    	$result = [];
    	for($page=1;$page < $loop;$page++)
    	{
    		$offset = $per_page * ( $page) ;
    		//log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($page,true));
    		//log_message("debug","oooooooooooooooooooooooooooooooooooooooooo".print_r($offset,true));
    		$pipeline = [
	    	array('$project' => array("doc_data.widget_data"=>true)),
	    	//array('$match' => $merged_array)
	    	array('$limit' => $offset),
	    	array('$skip' => $offset-$per_page)
	    	];
	    	$response = $this->mongo_db->command(array(
	    			'aggregate'=>$this->screening_app_col,
	    			'pipeline' => $pipeline
	    	)
	    	);
	    	$result = array_merge($result,$response['result']);
	    	//log_message("debug","response=====1643==".print_r($response,true));
	    	//log_message("debug","response=====1643==".print_r(count($response['result']),true));
	    	//log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($result,true));
    	}
    	//
    	//log_message("debug","response=====1643==".print_r(count($response['result']),true));
    	log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($result,true));
    	
    	
    	//$query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
    	//return $query;
    	return $result;
    }
    
    public function hospitalscount()
    {
    	$count = $this->mongo_db->count('panacea_hospitals');
    	return $count;
    }
    
    public function get_hospitals($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_hospitals');
    	foreach($query as $hospitals => $hospital){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($hospital['dt_name']))->get('panacea_district');
    		if(isset($hospital['dt_name'])){
    			$query[$hospitals]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$hospitals]['dt_name'] = "No state selected";
    		}
    	}
    	 
    	return $query;
    }
    
    public function diagnosticscount()
    {
    	$count = $this->mongo_db->count('panacea_diagnostics');
    	return $count;
    }
    
    public function get_diagnostics($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_diagnostics');
    	foreach($query as $diagnostics => $dia){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($dia['dt_name']))->get('panacea_district');
    		if(isset($dia['dt_name'])){
    			$query[$diagnostics]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$diagnostics]['dt_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
    
    public function empcount()
    {
    	$count = $this->mongo_db->count('panacea_emp');
    	return $count;
    }
    
    public function get_emp($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_emp');
    	return $query;
    }
    
    public function insert_student_data($doc_data, $history, $doc_properties)
    {
    	//$query = $this->mongo_db->getWhere("naresh", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
		
		$query = $this->mongo_db->getWhere($this->screening_app_col, array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
    
    	//$query = $this->mongo_db->getWhere("form_data_sample_copy_1", array('doc_data.widget_data.page2.Physical Info.ID number' => $doc_data['widget_data']['page2']['Physical Info']['ID number'],'doc_data.widget_data.page2.Physical Info.School'=>'TSWRS/JC(G)-JADCHERLA'));
    	 
    	$result = json_decode(json_encode($query), FALSE);
    	if (!$result)
    	{
    		$form_data = array();
    		$form_data['doc_data']       = $doc_data;
    		$form_data['doc_properties'] = $doc_properties;
    		$form_data['history']        = $history;
    
			//$this->mongo_db->insert("naresh",$form_data);
			
    		$this->mongo_db->insert($this->screening_app_col,$form_data);
    		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);
    	}
    	else
    	{
    		$form_data = array();
    		$form_data['doc_data'] = $doc_data;
    		$form_data['doc_data']['widget_data']['page2']['Personal Information']['AD No'] = $doc_data['widget_data']['page2']['Personal Information']['AD No'].'A';
    		$form_data['doc_properties'] = $doc_properties;
    		$form_data['history'] = $history;
			
			//$this->mongo_db->insert("naresh",$form_data);
    		$this->mongo_db->insert($this->screening_app_col,$form_data);
    		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);
    
    	}
    }
    
    public function get_all_symptoms($date = false, $request_duration = "Monthly")
    {    	
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	 
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	$query = $this->get_all_symptoms_docs($dates['today_date'], $dates['end_date']);    	
    
    	$prob_arr = [];
    	foreach ($query as $doc){
    		if(isset($doc['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
    			$problems = $doc['doc_data']['widget_data']['page1']['Problem Info']['Identifier'];
    			foreach ($problems as $problem){
    				if(isset($prob_arr[$problem])){
    					$prob_arr[$problem]++;
    				}else{
    					$prob_arr[$problem] = 1;
    				}
    			}
    		}
    	}
    	 
    	////log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob_arr,true));
    	$final_values = [];
    	foreach ($prob_arr as $prob => $count){
    		////log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob,true));
    		////log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    		$result['label'] = $prob;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    	////log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    	 
    	return $final_values;
    }
    
    public function get_all_absent_data($date = FALSE)
    {
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	
    	$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);    	
    	$absent = 0;
    	$sick = 0;
    	$restRoom = 0;
    	$r2h = 0;
    	//$attended = 0;
    	foreach ($query as $report){
    		$absent = $absent + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Absent']);
    		$sick = $sick + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Sick']);
    		$restRoom = $restRoom + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']);
    		$r2h = $r2h + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['R2H']);
    		//$attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
    	}
    	 
    	$requests = [];
    	 
    	//     	$request['label'] = 'ATTENDED';
    	//     	$request['value'] = $attended;
    	//     	array_push($requests,$request);
    	 
    	$request['label'] = 'ABSENT REPORT';
    	$request['value'] = $absent;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'SICK CUM ATTENDED';
    	$request['value'] = $sick;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'REST ROOM IN MEDICATION';
    	$request['value'] = $restRoom;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'REFER TO HOSPITAL';
    	$request['value'] = $r2h;
    	array_push($requests,$request);
    	 
    	return $requests;
    }
    
    public function drilldown_absent_to_districts($data,$date)
    { 
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	switch ($type) {
    		case "ABSENT REPORT":
    			 
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			 
    		case "SICK CUM ATTENDED":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			 
    		case "REST ROOM IN MEDICATION":
    				
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			 
    		case "REFER TO HOSPITAL":
    				
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			 
    
    		default:
    			;
    			break;
    	}
    }
    
    public function get_drilling_absent_schools($data,$date)
    {
    	$obj_data = json_decode($data,true);
    	////log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	////log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	switch ($type) {
    		case "ABSENT REPORT":
    
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    
    			break;
    		case "SICK CUM ATTENDED":
    			 
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    			 
    			break;
    
    		case "REST ROOM IN MEDICATION":
    			 
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    
    			break;
    			 
    		case "REFER TO HOSPITAL":
    
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_absent_students($data,$date)
    {    	 
    	$obj_data = json_decode($data,true);    
    	$type = $obj_data['0'];
    	$school_name = strtolower ($obj_data['1']);
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	ini_set('memory_limit', '512M');
    	
    	switch ($type) {
    		case "ABSENT REPORT":
    
    			
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    
    			break;
    		case "SICK CUM ATTENDED":
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    			 
    			break;
    
    		case "REST ROOM IN MEDICATION":
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    
    			break;
    			 
    		case "REFER TO HOSPITAL":
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today_date)->get($this->absent_app_col);
    			////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_absent_students_docs($_id_array)
    {
    	 
    	$docs = [];
    
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereLike("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id)->get($this->screening_app_col);
    		if($query)
    		array_push($docs,$query[0]);
    	}
    	 
    	 
    	////log_message("debug","abbbbbbbbbbbbbbbbbbbbbbbbbb____________arrrrrrrrrrrrrrrrrrrrrrrrr".print_r($_id_array,true));
    	//$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereIn("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id_array)->get($this->screening_app_col);
    	////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    	return $docs;
    
    }
    
    public function get_drilling_attendance_districts_prepare_pie_array($query)
    {
    	$requests = [];
    	
    	$dist_list = $this->get_all_district();
    	
    	$dist_arr = [];
    	foreach ($dist_list as $dist){
    		array_push($dist_arr,$dist['dt_name']);
    	}
    	
    	foreach ($dist_arr as $districts){
    		$request['label'] = $districts;
	    	$count = 0;
	    	if($query){
	    		foreach ($query as $dist){
	    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
	    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == strtolower($districts)){
	    					$count++;
	    				}
	    			}
	    		}
	    	}
	    	$request['value'] = $count;
	    	array_push($requests,$request);
    	}
        	    
    	return $requests;
    }
    
    public function get_drilling_absent_schools_prepare_pie_array($query,$dist)
    {
    	////log_message("debug","2222222222222222222222222222222222222222222222222".print_r($query,true));
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			////log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
    			if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page1']['Attendence Details']['District']) == $dist){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		////log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
    		$request = [];
    		foreach ($search_result as $doc){
    			if(isset($request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']])){
    				$request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']]++;
    			}else{
    				$request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']] = 1;
    			}
    		}
    
    		//     		$absent = 0;
    		//     		$sick = 0;
    		//     		$restRoom = 0;
    		//     		$r2h = 0;
    		//     		//$attended = 0;
    		//     		foreach ($query as $report){
    		//     			$absent = $absent + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['Absent']);
    		//     			$sick = $sick + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Sick']);
    		//     			$restRoom = $restRoom + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']);
    		//     			$r2h = $r2h + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['R2H']);
    		//     			//$attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
    		//     		}
    		 
    		//     		$requests = [];
    		 
    		//     		//     	$request['label'] = 'ATTENDED';
    		//     		//     	$request['value'] = $attended;
    		//     		//     	array_push($requests,$request);
    		 
    		//     		$request['label'] = 'ABSENT REPORT';
    		//     		$request['value'] = $absent;
    		//     		array_push($requests,$request);
    		 
    		//     		$request['label'] = 'SICK CUM ATTENDED';
    		//     		$request['value'] = $sick;
    		//     		array_push($requests,$request);
    		 
    		//     		$request['label'] = 'REST ROOM IN MEDICATION';
    		//     		$request['value'] = $restRoom;
    		//     		array_push($requests,$request);
    		 
    		//     		$request['label'] = 'REFER TO HOSPITAL';
    		//     		$request['value'] = $r2h;
    		//     		array_push($requests,$request);
    		 
    		////log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($request,true));
    		$final_values = [];
    		foreach ($request as $school => $count){
    			//log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($school,true));
    			//log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    			$result['label'] = $school;
    			$result['value'] = $count;
    			array_push($final_values,$result);
    		}
    		 
    		////log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    		 
    		return $final_values;
    	}
    }
    
    public function get_drilling_absent_students_prepare_pie_array($query,$school_name,$type)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			////log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
    			if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']) == $school_name){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		////log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
    		$request = [];
    		$UI_arr = [];
    		foreach ($search_result as $doc){
    			switch ($type){
    				case "ABSENT REPORT":
    					$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID']);
    					////log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
    					////log_message("debug","mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm".print_r($UI_arr,true));
    
    					break;
    				case "SICK CUM ATTENDED":
    					 
    					$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID']);
    					////log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
    					 
    					break;
    
    				case "REST ROOM IN MEDICATION":
    					 
    					$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID']);
    					//log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
    
    					break;
    					 
    				case "REFER TO HOSPITAL":
    
    					$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID']);
    					//log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
    
    					break;
    
    
    				default:
    					;
    					break;
    			}
    		}
    		 
    		return $UI_arr;
    	}
    }
    
    public function get_all_symptoms_docs($start_date, $end_date, $id_for_school = false){
    	
    	if($id_for_school){
    		$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($id_for_school))->get($this->request_app_col);
    	}else{
    		$query = $this->mongo_db->select(array("doc_data.widget_data","history"))->get($this->request_app_col);
    	}
    	
    	$result = [];
    	foreach ($query as $doc){
    		 
    		foreach ($doc['history'] as $date){
    			$time = $date['time'];
    
    			if (($time <= $start_date) && ($time >= $end_date)) {
    				array_push($result,$doc);
    				break;
    			}
    		}
    	}
    	$query = $result;
    	return $query;
    }
    
    public function get_start_end_date($today_date, $request_duration){
    	
    	if($request_duration == "Daily"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "1 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Weekly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-7 day"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Bi Weekly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-14 day"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Monthly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-1 month"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Bi Monthly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-2 month"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Quarterly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-3 month"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Half Yearly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-6 month"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}else if($request_duration == "Yearly"){
    		$date = new DateTime($today_date);
    		$today_date = $date->format('Y-m-d H:i:s');
    	
    		$end_date = date("Y-m-d H:i:s", strtotime($today_date . "-1 year"));
    		$today_date = date("Y-m-d H:i:s", strtotime($today_date . "0 day"));
    		$dates['today_date'] =$today_date;
    		$dates['end_date'] =$end_date;
    		return $dates;
    	}
    }
    
    public function get_all_requests($date = false, $request_duration = "Monthly")
    {
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	$query = $this->get_all_symptoms_docs($dates['today_date'], $dates['end_date']);
    	
    	//$query = $this->mongo_db->select(array("doc_data.widget_data","history"))->get($this->request_app_col);
    	 
    	$device_initiated = 0;
    	$web_initiated = 0;
    	$prescribed = 0;
    	$medication = 0;
    	$followUp = 0;
    	$cured = 0;
    	//$attended = 0;
		
		$req_normal = 0;
		$req_emergency = 0;
		$req_chronic = 0;
		
    	foreach ($query as $report){
			$status = $report['doc_data']['widget_data']['page2']['Review Info']['Status'];
			if($status == "Initiated"){
				if(isset($report['history'][0]['submitted_user_type'])){
					$user_type = $report['history'][0]['submitted_user_type'];
					if($user_type == "CCUSER"){
						$web_initiated++;
					}else{
						$device_initiated++;
					}
				}else{
					$device_initiated++;
				}
				
				
			}else if($status == "Prescribed"){
				$prescribed++;
			}else if($status == "Under Medication"){
				$medication++;
			}else if($status == "Follow-up"){
				$followUp++;
			}else if($status == "Cured"){
				$cured++;
			}
			
			$request_type = $report['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
			if($request_type == "Normal"){
				$req_normal++;
			}else if($request_type == "Emergency"){
				$req_emergency++;
			}else if($request_type == "Chronic"){
				$req_chronic++;
			}
    	}
		
    	$requests = [];
		
		$request['label'] = 'Device Initiated';
		$request['value'] = $device_initiated;
		array_push($requests,$request);
		
		$request['label'] = 'Web Initiated';
		$request['value'] = $web_initiated;
		array_push($requests,$request);
		
		$request['label'] = 'Prescribed';
		$request['value'] = $prescribed;
		array_push($requests,$request);
		
		$request['label'] = 'Under Medication';
		$request['value'] = $medication;
		array_push($requests,$request);
		
		$request['label'] = 'Follow-up';
		$request['value'] = $followUp;
		array_push($requests,$request);
		
		$request['label'] = 'Cured';
		$request['value'] = $cured;
		array_push($requests,$request);
		
		$request['label'] = 'Normal Req';
		$request['value'] = $req_normal;
		array_push($requests,$request);
		
		$request['label'] = 'Emergency Req';
		$request['value'] = $req_emergency;
		array_push($requests,$request);
		
		$request['label'] = 'Chronic Req';
		$request['value'] = $req_chronic;
		array_push($requests,$request);
		
		
		return $requests;
    }
    
    //======================================================================
    
    public function get_all_requests_docs($start_date, $end_date, $type = false){
    	 
    	if($type == "Initiated"){
    		$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => $type))->get($this->request_app_col);
    	}else if ($type == "Normal"){
    		$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
    	}else if ($type == "Emergency"){
    		$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
    	}else if ($type == "Chronic"){
    		$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
    	}else {
    		$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	}
    	 
    	$result = [];
    	foreach ($query as $doc){
    		 
    		foreach ($doc['history'] as $date){
    			$time = $date['time'];
    
    			if (($time <= $start_date) && ($time >= $end_date)) {
    				array_push($result,$doc);
    				break;
    			}
    		}
    	}
    	$query = $result;
    	return $query;
    }
    
    public function drilldown_request_to_districts($data,$date = false, $request_duration = "Monthly")
    {
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    	
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	
    	
//     	ini_set('memory_limit', '512M');
    	 
    	if($type == "Device Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
    		foreach ($query_temp as $report){
    			if(isset($report['history'][0]['submitted_user_type'])){
    				$user_type = $report['history'][0]['submitted_user_type'];
    				if($user_type != "CCUSER"){
    					array_push($query,$report);
    				}
    			}else{
    				array_push($query,$report);
    			}
    		}
    
    	}else if($type == "Web Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
	    	foreach ($query_temp as $report){
				
				if(isset($report['history'][0]['submitted_user_type'])){
					$user_type = $report['history'][0]['submitted_user_type'];
					if($user_type == "CCUSER"){
						array_push($query,$report);
					}
				}
	    	}
    	}else if($type == "Normal Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Normal");
    		
    		
    	}else if($type == "Emergency Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Emergency");
    		
    	}else if($type == "Chronic Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Chronic");
    		
    	}else{
    		//$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], $type);
    	}
    	 
    	$dist_list = [];
    	 
    	foreach ($query as $request){
    
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		if(isset($doc) && !empty($doc) && (count($doc) > 0)){
	    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
	    		if(isset($dist_list[$district])){
	    			$dist_list[$district]++;
	    		}else{
	    			$dist_list[$district] = 1;
	    		}
    		}
    	}
    	 
    	$final_values = [];
    	foreach ($dist_list as $dicsts => $count){
    		$result['label'] = $dicsts;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    	 
    	return $final_values;
    }
    
    public function get_drilling_request_schools($data,$date = false, $request_duration = "Monthly")
    {
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    
    	$obj_data = json_decode($data,true);
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	 
    	 
		if($type == "Device Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
    		foreach ($query_temp as $report){
    			if(isset($report['history'][0]['submitted_user_type'])){
    				$user_type = $report['history'][0]['submitted_user_type'];
    				if($user_type != "CCUSER"){
    					array_push($query,$report);
    				}
    			}else{
    				array_push($query,$report);
    			}
    		}
    
    	}else if($type == "Web Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
	    	foreach ($query_temp as $report){
				
				if(isset($report['history'][0]['submitted_user_type'])){
					$user_type = $report['history'][0]['submitted_user_type'];
					if($user_type == "CCUSER"){
						array_push($query,$report);
					}
				}
	    	}
    	}else if($type == "Normal Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Normal");
    		
    		
    	}else if($type == "Emergency Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Emergency");
    		
    	}else if($type == "Chronic Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Chronic");
    		
    	}else{
    		//$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], $type);
    	}
    	 
    	//     	ini_set('memory_limit', '512M');
    	//     	$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    
    	$school_list = [];
    	$matching_docs = [];
    
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		if(isset($doc) && !empty($doc) && (count($doc) > 0)){
	    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
	    		if(strtolower($district) == $dist){
	    			array_push($matching_docs,$doc[0]);
	    		}
    		}
    	}
    	 
    	foreach ($matching_docs as $docs){
    		$school_name = $docs['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if(isset($school_list[$school_name])){
    			$school_list[$school_name]++;
    		}else{
    			$school_list[$school_name] = 1;
    		}
    	}
    	 
    
    	$final_values = [];
    	foreach ($school_list as $school => $count){
    		$result['label'] = $school;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    
    	return $final_values;
    }
    
    public function get_drilling_request_students($data,$date = false, $request_duration = "Monthly")
    {
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    
    	$obj_data = json_decode($data,true);
    	//log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = $obj_data['1'];
    	 
    	//log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	 
   		if($type == "Device Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
    		foreach ($query_temp as $report){
    			if(isset($report['history'][0]['submitted_user_type'])){
    				$user_type = $report['history'][0]['submitted_user_type'];
    				if($user_type != "CCUSER"){
    					array_push($query,$report);
    				}
    			}else{
    				array_push($query,$report);
    			}
    		}
    
    	}else if($type == "Web Initiated"){
    		$query_temp = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Initiated");
    		
    		$query = [];
	    	foreach ($query_temp as $report){
				
				if(isset($report['history'][0]['submitted_user_type'])){
					$user_type = $report['history'][0]['submitted_user_type'];
					if($user_type == "CCUSER"){
						array_push($query,$report);
					}
				}
	    	}
    	}else if($type == "Normal Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Normal");
    		
    		
    	}else if($type == "Emergency Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Emergency");
    		
    	}else if($type == "Chronic Req"){
    		//$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], "Chronic");
    		
    	}else{
    		//$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    		$query = $this->get_all_requests_docs($dates['today_date'], $dates['end_date'], $type);
    	}
    
    	//ini_set('memory_limit', '512M');
    	//$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	$student_list = [];
    	$matching_docs = [];
    	 
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		if(isset($doc) && !empty($doc) && count($doc)>0){
	    		$school = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
	    		if($school == $school_name){
	    			array_push($matching_docs,$doc[0]['_id']->{'$id'});
	    		}
    		}
    	}
    	return $matching_docs;
    }
    
    public function get_drilling_request_students_docs($_id_array)
    {
    	$docs = [];
    	log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($_id_array,true));
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    
    }
    
    //----------------------------------------------------------------------
    
    //===================================id=======================+==========================
    
    public function drilldown_identifiers_docs($start_date, $end_date, $type){
    	ini_set('memory_limit', '512M');
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	
    	$result = [];
    	foreach ($query as $doc){
    		 
    		foreach ($doc['history'] as $date){
    			$time = $date['time'];
    	
    			if (($time <= $start_date) && ($time >= $end_date)) {
    				array_push($result,$doc);
    				break;
    			}
    		}
    	}
    	$query = $result;
    	
    	return $query;
    }
    
    public function drilldown_identifiers_to_districts($data,$date = false, $request_duration = "Monthly")
    {
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    	 
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	$query = $this->get_all_symptoms_docs($dates['today_date'], $dates['end_date'], $type); 	
    	
    	
    	//$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	$dist_list = [];
    
    	foreach ($query as $identifiers){
    
    		$retrieval_list = array();
    		$unique_id 	 	 = $identifiers['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		//log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
    		if(isset($doc) && !empty($doc) && (count($doc) > 0)){
	    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
	    		if(isset($dist_list[$district])){
	    			$dist_list[$district]++;
	    		}else{
	    			$dist_list[$district] = 1;
	    		}
    		}
    	}
    
    	$final_values = [];
    	foreach ($dist_list as $dicsts => $count){
    		$result['label'] = $dicsts;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    
    	return $final_values;
    }
    
    public function get_drilling_identifiers_schools($data, $date = false, $request_duration = "Monthly")
    {
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    
    	$obj_data = json_decode($data,true);
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	$query = $this->get_all_symptoms_docs($dates['today_date'], $dates['end_date'], $type);
    
    	//ini_set('memory_limit', '512M');
    	//$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	
    	$school_list = [];
    	$matching_docs = [];
    
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		if(isset($doc) && !empty($doc) && (count($doc) > 0)){
	    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
	    		if(strtolower($district) == strtolower($dist)){
	    			array_push($matching_docs,$doc[0]);
	    		}
    		}
    	}
    
    	foreach ($matching_docs as $docs){
    		$school_name = $docs['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if(isset($school_list[$school_name])){
    			$school_list[$school_name]++;
    		}else{
    			$school_list[$school_name] = 1;
    		}
    	}
    
    
    	$final_values = [];
    	foreach ($school_list as $school => $count){
    		$result['label'] = $school;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    
    	return $final_values;
    }
    
    public function get_drilling_identifiers_students($data, $date = false, $request_duration = "Monthly")
    {
    
    	$obj_data = json_decode($data,true);
    	//log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = $obj_data['1'];
    
    	//log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	
    	
    	$query = [];
    	if($date){
    		$today_date = $date;
    	}else{
    		$today_date = $this->today_date;
    	}
    	 
    	$dates = $this->get_start_end_date($today_date, $request_duration);
    	$query = $this->get_all_symptoms_docs($dates['today_date'], $dates['end_date'], $type);
    
    	//ini_set('memory_limit', '512M');
    	//$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	
    	$student_list = [];
    	$matching_docs = [];
    
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
			if(isset($doc) && !empty($doc) && (count($doc) > 0)){
    		$school = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if($school == $school_name){
    			array_push($matching_docs,$doc[0]['_id']->{'$id'});
    		}
			}
    	}
    
    	return $matching_docs;
    
    }
    
    public function get_drilling_identifiers_students_docs($_id_array)
    {
    	$docs = [];
    
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    
    }
    
    //===================================id=================================================
    
    public function get_all_screenings()
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		
		//ini_set('memory_limit', '1G');
		//$search = array("doc_data.widget_data.page3" => array(),"doc_data.widget_data.page4" => array(),"doc_data.widget_data.page5" => array(),"doc_data.widget_data.page6" => array(),"doc_data.widget_data.page7" => array(),"doc_data.widget_data.page8" => array(),"doc_data.widget_data.page9" => array());
    	//$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		//log_message("debug","counttttttttttttttttttttttttttttttttttttttttttttttttttttt========================".print_r($query,true));
		
		// $count = $this->mongo_db->count($this->screening_app_col);
		// log_message("debug","counttttttttttttttttttttttttttttttttttttttt========================".print_r($count,true));
		
		// $doc = [];
		// for($num=0;$num<=$count;$num++){
			// log_message("debug","ffffffffffffffffffffffffffffffffffffffffffffff========================".print_r($num,true));
			// $page = $num;
			// $per_page = 10;
			// $offset = $per_page * ( $page - 1) ;
			// $query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->screening_app_col);
			// array_push($doc,$query);
		// }
		// log_message("debug","docccccccccccccccccccccccccccccccccccccccccccccccccc========================".print_r($doc,true));
		
    	$count = $this->mongo_db->count($this->screening_app_col);
    	$per_page = 10000;
    	$loop = $count/$per_page;
    	 
    	$requests = [];
    	 
    	$request['label'] = 'Physical Abnormalities';
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight", "Under Weight"))->count($this->screening_app_col);
    	
    	// $merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Over Weight","Under Weight")));
    	
    	// $result = [];
    	// for($page=1;$page < $loop;$page++)
    	// {
    		// $offset = $per_page * ( $page) ;
    		
    		// $pipeline = [
	    	// array('$project' => array("doc_data.widget_data"=>true)),
			// array('$match' => $merged_array),
			// array('$limit' => $offset),
			// array('$skip' => $offset-$per_page)
			// ];
	    	// $response = $this->mongo_db->command(array(
	    			// 'aggregate'=>$this->screening_app_col,
	    			// 'pipeline' => $pipeline
	    			
	    	// )
	    	// ); 
	    	// $result = array_merge($result,$response['result']);
    	// }
    	 	
    	
    	$request['value'] = $query;
    	array_push($requests,$request);
    	
    	$request['label'] = 'General Abnormalities';
    	
    	$search = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array("Over Weight", "Under Weight"));
    	$query = $this->mongo_db->where(array("doc_data.widget_data.page5.Doctor Check Up.N A D" => array("Yes")))->count($this->screening_app_col);
    	
    	// $merged_array = array();
    	// $nad_exists  = array("doc_data.widget_data.page5.Doctor Check Up.N A D" => array('$nin'=>array("Yes")));
    	// $nad_not_yes = array("doc_data.widget_data.page5.Doctor Check Up.N A D" => array('$exists'=>true));
    	// array_push($merged_array,$nad_exists);
    	// array_push($merged_array,$nad_not_yes);
    	 
    	// log_message("debug","response=====1665==".print_r($merged_array,true));
    	
    	// $result = [];
    	// for($page=1;$page < $loop;$page++)
    	// {
    	// $offset = $per_page * ( $page) ;
    	// $pipeline = [
    	// array('$project' => array("doc_data.widget_data"=>true)),
			// array('$match' => array('$and'=>$merged_array)),
    				// array('$limit' => $offset),
    				// array('$skip' => $offset-$per_page)
			// ];
    				// $response = $this->mongo_db->command(array(
    						// 'aggregate'=>$this->screening_app_col,
    						// 'pipeline' => $pipeline
    	
    				// )
    				// );
	    	// $result = array_merge($result,$response['result']);
    	// }
    	
    	$request['value'] = $query;
    	array_push($requests,$request);
    
    	$request['label'] = 'Eye Abnormalities';
    	$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6", "doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "", "doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6", "doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No", "doc_data.widget_data.page6" => array(),"doc_data.widget_data.page7" => array());
    	$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
    	
    	
    	// $and_merged_array = array();
    	// $or_merged_array = array();
    	
    	// $without_glass_left  = array("doc_data.widget_data.page6.Without Glasses.Left" => array('$nin'=>array("6/6", "")));
    	// $without_glass_right = array("doc_data.widget_data.page6.Without Glasses.Right" => array('$nin'=>array("6/6", "")));
    	// $with_glass_left     = array("doc_data.widget_data.page6.With Glasses.Left" => array('$nin'=>array("6/6", "")));
    	// $with_glass_right    = array("doc_data.widget_data.page6.With Glasses.Right" => array('$nin'=>array("6/6", "")));
    	// $color_right    = array("doc_data.widget_data.page7.Colour Blindness.Right" => array('$nin'=>array("No", "")));
    	// $color_left    = array("doc_data.widget_data.page7.Colour Blindness.Left" => array('$nin'=>array("No", "")));
    	
    	// $page6_exists = array("doc_data.widget_data.page6.With Glasses"     => array('$exists'=>true));
    	// $page7_exists = array("doc_data.widget_data.page7.Colour Blindness" => array('$exists'=>true));
    	
    	// array_push($or_merged_array,$without_glass_left);
    	// array_push($or_merged_array,$without_glass_right);
    	// array_push($or_merged_array,$with_glass_left);
    	// array_push($or_merged_array,$with_glass_right);
    	// array_push($or_merged_array,$color_right);
    	// array_push($or_merged_array,$color_left);
    	
    	// array_push($and_merged_array,$page6_exists);
    	// array_push($and_merged_array,$page7_exists);
    	
    	// log_message("debug","response=====1665==".print_r($merged_array,true));
    	// $result = [];
    	// for($page=1;$page < $loop;$page++)
    	// {
    		// $offset = $per_page * ( $page) ;
    		// $pipeline = [
	    	// array('$project' => array("doc_data.widget_data"=>true)),
	    	// array('$match' => array('$and'=>$and_merged_array, '$or'=>$or_merged_array)),
	    	// array('$limit' => $offset),
	    	// array('$skip' => $offset-$per_page)
	    	// ];
	    	
	    	// $response = $this->mongo_db->command(array(
	    			// 'aggregate'=>$this->screening_app_col,
	    			// 'pipeline' => $pipeline
	    	// )
	    	// );
	    	// $result = array_merge($result,$response['result']);
    	// }
    	
    	//log_message("debug","response=====1748==".print_r($response,true));
    	//log_message("debug","response=====1749==".print_r(count($response['result']),true));
    	
    	
    	$request['value'] = $query;
    	array_push($requests,$request);
    
    	$request['label'] = 'Auditory Abnormalities';
    	$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Left" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array('Normal'), "doc_data.widget_data.page8" => array());
    	$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
    	
    	// $and_merged_array = array();
    	// $or_merged_array = array();
    	
    	// $audi_right  = array("doc_data.widget_data.page8. Auditory Screening.Right" => array('$nin'=>array("Pass")));
    	// $audi_left = array("doc_data.widget_data.page8. Auditory Screening.Left" => array('$nin'=>array("Pass")));
    	// $speech     = array("doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array('$nin'=>array("Normal","")));
    	
    	// $page8_exists = array("doc_data.widget_data.page8. Auditory Screening"     => array('$exists'=>true));
    	 
    	// array_push($or_merged_array,$audi_right);
    	// array_push($or_merged_array,$audi_left);
    	// array_push($or_merged_array,$speech);
    	
    	// array_push($and_merged_array,$page8_exists);
    	// $result = [];
    	// for($page=1;$page < $loop;$page++)
    	// {
    		// $offset = $per_page * ( $page) ;
    		// $pipeline = [
	    	// array('$project' => array("doc_data.widget_data"=>true)),
	    	// array('$match' => array('$and'=>$and_merged_array, '$or'=>$or_merged_array)),
	    	// array('$limit' => $offset),
	    	// array('$skip' => $offset-$per_page)
	    	// ];
	    	// $response = $this->mongo_db->command(array(
	    			// 'aggregate'=>$this->screening_app_col,
	    			// 'pipeline' => $pipeline
	    	// )
	    	// );
	    	// $result = array_merge($result,$response['result']);
    	// }
    	
    	$request['value'] = $query;
    	array_push($requests,$request);
    
    	$request['label'] = 'Dental Abnormalities';
    	$search = array("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => "Good", "doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => "No", "doc_data.widget_data.page9.Dental Check-up.Flourosis" => "No","doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No","doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => "No", "doc_data.widget_data.page9" => array());
    	$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);    	
    	
    	// $and_merged_array = array();
    	// $or_merged_array = array();
    	 
    	// $oral_hygiene  = array("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array('$nin'=>array("Good")));
    	// $carious_teeth = array("doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array('$nin'=>array("No")));
    	// $flourosis     = array("doc_data.widget_data.page9.Dental Check-up.Flourosis" => array('$nin'=>array("No")));
    	// $orthodontic   = array("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array('$nin'=>array("No")));
    	// $indication    = array("doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array('$nin'=>array("No")));
    	 
    	// $page9_exists = array("doc_data.widget_data.page9.Dental Check-up"     => array('$exists'=>true));
    	
    	// array_push($or_merged_array,$oral_hygiene);
    	// array_push($or_merged_array,$carious_teeth);
    	// array_push($or_merged_array,$flourosis);
    	// array_push($or_merged_array,$orthodontic);
    	// array_push($or_merged_array,$indication);
    	 
    	// array_push($and_merged_array,$page9_exists);
    	
    	// $result = [];
    	// for($page=1;$page < $loop;$page++)
    	// {
    		// $offset = $per_page * ( $page) ;
    		// $pipeline = [
	    	// array('$project' => array("doc_data.widget_data"=>true)),
	    	// array('$match' => array('$and'=>$and_merged_array, '$or'=>$or_merged_array)),
	    	// array('$limit' => $offset),
	    	// array('$skip' => $offset-$per_page)
	    	// ];
	    	// $response = $this->mongo_db->command(array(
	    			// 'aggregate'=>$this->screening_app_col,
	    			// 'pipeline' => $pipeline
	    	// )
	    	// );
	    	// $result = array_merge($result,$response['result']);
    	// }
    	
    	
    	$request['value'] = $query;
    	array_push($requests,$request);
    
    	return $requests;
    }
    
    public function get_drilling_screenings_abnormalities($data)
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	$obj_data = json_decode($data,true);
    	 
    	$type = $obj_data['label'];
    	
    	ini_set('memory_limit', '1G');
    	
    	$count = $this->mongo_db->count($this->screening_app_col);
    	//log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    	$per_page = 1000;
    	$loop = $count/$per_page;
    	
    	switch ($type) {
    		case "Physical Abnormalities":
    			$requests = [];
    			$request['label'] = 'Over Weight';
    			//$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->count($this->screening_app_col);
    			
    			$merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Over Weight")));
    			
    			$result = [];
    			for($page=1;$page < $loop;$page++)
    			{
    				$offset = $per_page * ( $page) ;
    				$pipeline = [
			    	array('$project' => array("doc_data.widget_data"=>true)),
					array('$match' => $merged_array),
					array('$limit' => $offset),
					array('$skip' => $offset-$per_page)
					];
			    	$response = $this->mongo_db->command(array(
			    			'aggregate'=>$this->screening_app_col,
			    			'pipeline' => $pipeline
			    	)
			    	);
			    	$result = array_merge($result,$response['result']);
    			}
		    	
		    	$request['value'] = count($result);
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Under Weight';
    			//$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->count($this->screening_app_col);
    			
    			$merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Under Weight")));
    			
    			$result = [];
    			for($page=1;$page < $loop;$page++)
    			{
	    			$offset = $per_page * ( $page) ;
	    			$pipeline = [
	    			array('$project' => array("doc_data.widget_data"=>true)),
	    			array('$match' => $merged_array),
	    			array('$limit' => $offset),
	    			array('$skip' => $offset-$per_page)
	    			];
	    			$response = $this->mongo_db->command(array(
	    					'aggregate'=>$this->screening_app_col,
	    					'pipeline' => $pipeline
	    			)
	    			);
	    			$result = array_merge($result,$response['result']);
    			}
    			
    			
    			$request['value'] = count($result);
    			array_push($requests,$request);
    
    			return $requests;
    			break;
    		case "General Abnormalities":
    			$requests = [];
    			$request['label'] = 'General';
    			//$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array()))->count($this->screening_app_col);
    			
    			$and_merged_array = array();
    			
    			
    			$general_str_empty  = array("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array('$ne'=> ''));
    			$general_str_space  = array("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array('$ne'=> ' '));
    			$general_arr  = array("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array('$ne'=> array()));
    			
    			$page4_exists = array("doc_data.widget_data.page4.Doctor Check Up"=> array('$exists'=>true));
    			
    			array_push($and_merged_array,$general_str_empty);
    			array_push($and_merged_array,$general_str_space);
    			array_push($and_merged_array,$general_arr);
    			array_push($and_merged_array,$page4_exists);
    			
    			$result = [];
    			for($page=1;$page < $loop;$page++)
    			{
	    			$offset = $per_page * ( $page) ;
	    			$pipeline = [
					array('$match' => array('$and'=>$and_merged_array)),
	    			array('$project' => array("doc_data.widget_data"=>true)),
	    			array('$limit' => $offset),
	    			array('$skip' => $offset-$per_page)
	    			];
	    			$response = $this->mongo_db->command(array(
	    					'aggregate'=>$this->screening_app_col,
	    					'pipeline' => $pipeline
	    			)
	    			);
	    			$result = array_merge($result,$response['result']);
    			}
    			
    			
    			$request['value'] = count($result);
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Ortho';
    			//$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Ortho" => array()))->count($this->screening_app_col);
    			//$request['value'] = $query;
    			
    			$and_merged_array = array();
    			 
    			$ortho  = array("doc_data.widget_data.page4.Doctor Check Up.Ortho" => array('$not'=>array('$size'=>0)));
    			
    			$page4_exists = array("doc_data.widget_data.page4.Doctor Check Up.Ortho" => array('$exists'=>true));
    			 
    			array_push($and_merged_array,$ortho);
    			array_push($and_merged_array,$page4_exists);
    			
    			$result = [];
    			for($page=1;$page < $loop;$page++)
    			{
	    			$offset = $per_page * ( $page) ;
	    			$pipeline = [
					array('$match' => array('$and'=>$and_merged_array)),
	    			array('$project' => array("doc_data.widget_data"=>true)),
					array('$limit' => $offset),
	    			array('$skip' => $offset-$per_page)
	    			];
					
	    			$response = $this->mongo_db->command(array(
	    					'aggregate'=>$this->screening_app_col,
							'pipeline' => $pipeline
					)
	    			);
	    			$result = array_merge($result,$response['result']);
    			}
    			
    			$request['value'] = count($result);
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Postural';
    			//$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array(), "doc_data.widget_data.page4" => array()))->count($this->screening_app_col);
    			
    			$and_merged_array = array();
    			
    			$postural  = array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array('$not'=>array('$size'=>0)));
    			 
    			$page4_exists = array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array('$exists'=>true));
    			
    			array_push($and_merged_array,$postural);
    			array_push($and_merged_array,$page4_exists);
    			 
    			$result = [];
    			for($page=1;$page < $loop;$page++)
    			{
    			$offset = $per_page * ( $page) ;
    			$pipeline = [
    			array('$match' => array('$and'=>$and_merged_array)),
    				array('$project' => array("doc_data.widget_data"=>true)),
    				array('$limit' => $offset),
	    			array('$skip' => $offset-$per_page)
    				];
    								
    				$response = $this->mongo_db->command(array(
    				'aggregate'=>$this->screening_app_col,
    				'pipeline' => $pipeline
    						)
    				);
    				$result = array_merge($result,$response['result']);
    			}
    			
    			$request['value'] = count($result);
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Defects at Birth';
    			$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Deficencies';
    			$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Childhood Diseases';
    			$query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			return $requests;
    			break;
    		case "Eye Abnormalities":
    			$requests = [];
    			 
    			$request['label'] = 'Without Glasses';
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "", "doc_data.widget_data.page6.Without Glasses.Left" => "", "doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6", "doc_data.widget_data.page6" => array());
    			$query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'With Glasses';
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "", "doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6", "doc_data.widget_data.page6" => array());
    			$query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Colour Blindness';
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => array("Yes"), "doc_data.widget_data.page7.Colour Blindness.Left" => array("Yes"));
    			$query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			return $requests;
    			break;
    		case "Auditory Abnormalities":
    			$requests = [];
    			 
    			$request['label'] = 'Right Ear';
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail", "doc_data.widget_data.page8" => array());
    			$query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Left Ear';
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail", "doc_data.widget_data.page8" => array());
    			$query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Speech Screening';
    			 
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			return $requests;
    			break;
    		case "Dental Abnormalities":
    			$requests = [];
    			 
    			$request['label'] = 'Oral Hygiene';
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Carious Teeth';
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Flourosis';
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Orthodontic Treatment';
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			$request['label'] = 'Indication for extraction';
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    
    			return $requests;
    			break;
    
    		default:
    			;
    			break;
    	}
    	 
    }
    
    public function get_drilling_screenings_districts($data)
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	$obj_data = json_decode($data,true);
    	 
    	$type = $obj_data['label'];
    	switch ($type) {
    		case "Over Weight":
    			 
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    			 
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    			break;
    
    		case "Under Weight":
    			
				ini_set('memory_limit', '512M');
				
    			// $query = $this->mongo_db->get($this->screening_app_col);
    			// $chk =0;
				// $id=1000;
    			// foreach ($query as $doc){
					// if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity'])){
						// $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'] = $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity'];
						// unset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity']);
						// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
					// }
					
    					// $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = 'MBNR_1423101_'.$id;
    					// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
    					// $chk++;
						// $id++;
    				
    			// }
    			// log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWRS-CHITKUL,MEDAK")->get($this->screening_app_col);
    			// foreach ($query as $doc){
					
					// $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = "TSWREIS CHITKUL(G),MEDAK";
					
    					// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
						//log_message("debug","iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================");
    				
    			// }
    			 
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    			 
    			break;
    
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_screenings_schools($data)
    {
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$dist = strtolower ($obj_data['1']);
    	switch ($type) {
    		case "Over Weight":
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    			 
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    		  
    			break;
    
    		case "Under Weight":
    
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			//$search = array("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No");
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_screenings_students($data)
    {
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = strtolower ($obj_data['1']);
    	switch ($type) {
    		case "Over Weight":
    
    			$query = $this->mongo_db->select(array("_id","doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Under Weight":
    
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_screenings_districts_prepare_pie_array($query)
    {
    	
    	$requests = [];
    	 
    	$dist_list = $this->get_all_district();
    	 
    	$dist_arr = [];
    	foreach ($dist_list as $dist){
    		array_push($dist_arr,$dist['dt_name']);
    	}
    	 
    	foreach ($dist_arr as $districts){
    		$request['label'] = $districts;
    		$count = 0;
    		if($query){
    			foreach ($query as $dist){
    				if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == strtolower($districts)){
    						$count++;
    					}
    				}
    			}
    		}
    		$request['value'] = $count;
    		array_push($requests,$request);
    	}
    	 
    	return $requests;
    }
    
    public function get_drilling_screenings_schools_prepare_pie_array($query,$dist)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page2']['Personal Information']['District']) == $dist){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		$request = [];
    		foreach ($search_result as $doc){
    			if(isset($request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']])){
    				$request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']]++;
    			}else{
    				$request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']] = 1;
    			}
    		}
    		 
    		//log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($request,true));
    		$final_values = [];
    		foreach ($request as $school => $count){
    			//log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($school,true));
    			//log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    			$result['label'] = $school;
    			$result['value'] = $count;
    			array_push($final_values,$result);
    		}
    		 
    		//log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    		 
    		return $final_values;
    	}
    }
    
    public function get_drilling_screenings_students_prepare_pie_array($query,$school_name)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'])){
    				if(strtolower($doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']) == $school_name){
    					array_push($search_result,$doc['_id']->{'$id'});
    				}
    			}
    		}
    		 
    		return $search_result;
    	}
    }
    
    public function get_drilling_screenings_students_docs($_id_array)
    {
    	 
    	$docs = [];
    	 
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    	 
    }
    
    public function drill_down_screening_to_students_load_ehr_doc($_id)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    	if($query){
    		$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $query[0]["doc_data"]['widget_data']['page1']['Personal Information']['Hospital Unique ID'])->get($this->request_app_col);
    		$result['screening'] = $query;
    		$result['request'] = $query_request;
    		return $result;
    	}else{
    		$result['screening'] = false;
    		$result['request'] = false;
    		return $result;
    	}
    }
    
    public function drill_down_screening_to_students_doc($_id)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    	if($query){
    
    		return $query;
    	}else{
    
    		return false;
    	}
    }
    
    
    //*************************************************
    
    /**
     * Helper: Prepares IP address string for database insertion.
     *
     * @return string
     */
    protected function _prepare_ip($ip_address)
    {
    	return $ip_address;
    }
    
    public function user_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['panacea_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function cc_user_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['panacea_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function school_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['panacea_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function doctor_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['panacea_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    /**
     * Sets an error message
     */
    public function set_error($error)
    {
    	$this->errors[] = $error;
    	return $error;
    }
    
    /**
     * Applies delimiters and returns themed errors
     */
    public function errors()
    {
    	$_output = '';
    	foreach ($this->errors as $error)
    	{
    		$error_lang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
    		$_output .= $this->error_start_delimiter . $error_lang . $this->error_end_delimiter;
    	}
    
    	return $_output;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Return errors as an array, langified or not
     **/
    public function errors_array($langify = TRUE)
    {
    	if ($langify)
    	{
    		$_output = array();
    		foreach ($this->errors as $error)
    		{
    			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
    			$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
    		}
    		return $_output;
    	}
    	else
    	{
    		return $this->errors;
    	}
    }
    
    /**
     * Generates a random salt value.
     */
    public function salt()
    {
    	return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }
    
    /**
     * Hashes the password to be stored in the database.
     */
    public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE)
    {
    	if (empty($password))
    	{
    		return FALSE;
    	}
    
    	// Bcrypt
    	if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
    	{
    		return $this->bcrypt->hash($password);
    	}
    
    
    	if ($this->store_salt && $salt)
    	{
    		return sha1($password . $salt);
    	}
    	else
    	{
    		$salt = $this->salt();
    		return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
    	}
    }
}


