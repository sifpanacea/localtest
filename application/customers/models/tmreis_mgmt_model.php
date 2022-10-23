<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tmreis_mgmt_model extends CI_Model 
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
        
        $this->screening_app_col = "healthcare201672020159570";
        $this->absent_app_col    = "healthcare2017120192713965";
        $this->request_app_col   = "healthcare201610114435690";
		
        $this->sanitation_infra_app_col  = "healthcare20161114161842748";
        $this->sanitation_report_app_col = "healthcare2017121175645993";
    }
    
    public function create_state($post)
    {
    	$data = array(
    			"st_code" => $post['st_code'],
    			"st_name" => $post['st_name']);
    	$query = $this->mongo_db->insert('tmreis_states',$data);
    	return $query;
    }
    
    public function delete_state($st_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($st_id)))->delete('tmreis_states');
    	return $query;
    }
    
    //=================================================
    
    public function create_district($post)
    {
    	$data = array(
    			"st_name" => $post['st_name'],
    			"dt_code" => $post['dt_code'],
    			"dt_name" => $post['dt_name']);
    	$query = $this->mongo_db->insert('tmreis_district',$data);
    	return $query;
    }
    
    public function delete_district($dt_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($dt_id)))->delete('tmreis_district');
    	return $query;
    }
    
    public function delete_health_supervisors($hs_id)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($hs_id)))->delete($this->collections['tmreis_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    /////////////////////////////////////////////////////////////
    
    
    
    
    public function cc_users_count()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['tmreis_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_cc_users($per_page,$page)
    {
		
		//=======================================================
				// $query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',"KMNR_122_")->get($this->screening_app_col);
				// foreach ($query as $doc){
				// if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])){
					// $nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], "KMNR_122_");
					
					// if($nlg_pos !== false){
						// $nlg_end = $nlg_pos + 10;
						// $unique_cut = substr($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']));
						// log_message("debug","unique ------------------ beforeeeeeeeeeee--------".print_r($unique_cut,true));

						// $new_id = "MKMNR_122_".$unique_cut;
						
						// log_message("debug","unique ------------------ aftertttttttttttttttt--------".print_r($new_id,true));
				// $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $new_id;
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// }
				// }
				// }
		//=======================================================
		
		
		
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['tmreis_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    public function create_cc_user($post)
    {
    	$this->load->config('ion_auth', TRUE);
    	 
    	log_message('debug','ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo.'.print_r($_POST,true));
    
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
    	$query = $this->mongo_db->insert($this->collections['tmreis_cc'],$data);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($query) ? $query : FALSE;
    }
    
    public function delete_cc_user($cc_id)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($cc_id)))->delete($this->collections['tmreis_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    
    
    //////////////////////////////////////////////////////////////
    
    public function create_doctor($post)
    {
    	$this->load->config('ion_auth', TRUE);
    
    	log_message('debug','ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo.'.print_r($_POST,true));
    
    	$email = strtolower($post['email']);
    	$password = $post['password'];
    
    	// Check if email already exists
    	if ($this->doctor_exists($email))
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
    			"name" => $post['doc_name'],
    			"mobile_number" => $post['mob_number'],
    			"qualification" => $post['qualification'],
    			"specification" => $post['specification'],
    			"district"		=> $post['district'],
    			"password" => $password,
    			"email" => $email,
    			"company_address" => $post['address'],
    
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
    	$query = $this->mongo_db->insert($this->collections['tmreis_doctors'],$data);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($query) ? $query : FALSE;
    }
    
    public function delete_doctor($cc_id)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($cc_id)))->delete($this->collections['tmreis_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    
    /////////////////////////////////////////////////////////////////////
    
    public function delete_school($school_id)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($school_id)))->delete($this->collections['tmreis_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    public function create_class($post)
    {
    	$data = array(
    			"class_name" => $post['class_name']);
    	$query = $this->mongo_db->insert('tmreis_classes',$data);
    	return $query;
    }
    
    public function delete_class($class_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($class_id)))->delete('tmreis_classes');
    	return $query;
    }
    
    public function create_section($post)
    {
    	$data = array(
    			"section_name" => $post['section_name']);
    	$query = $this->mongo_db->insert('tmreis_sections',$data);
    	return $query;
    }
    
    public function delete_section($section_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($section_id)))->delete('tmreis_sections');
    	return $query;
    }
    
    public function create_symptoms($post)
    {
    	$data = array(
    			"symptom_name" => $post['symptom_name']);
    	$query = $this->mongo_db->insert('tmreis_symptoms',$data);
    	return $query;
    }
    
    public function delete_symptoms($symptoms_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($symptoms_id)))->delete('tmreis_symptoms');
    	return $query;
    }
    
    public function get_students($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	return $query;
    }
    
    public function delete_diagnostic($diagnostic_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($diagnostic_id)))->delete('tmreis_diagnostics');
    	return $query;
    }
    
    public function delete_hospital($hospital_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($hospital_id)))->delete('tmreis_hospitals');
    	return $query;
    }
    
    public function create_emp($post)
    {
    	$data = array(
    			"emp_code" => $post['emp_code'],
    			"emp_name" => $post['emp_name'],
    			"emp_email" => $post['emp_email'],
    			"emp_mob" => $post['emp_mob'],
    			"emp_addr" => $post['emp_addr'],
    			"emp_qualification" => $post['emp_qualification'],);
    	$query = $this->mongo_db->insert('tmreis_emp',$data);
    	return $query;
    }
    
    public function delete_emp($emp_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($emp_id)))->delete('tmreis_emp');
    	return $query;
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
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['tmreis_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function school_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['tmreis_schools']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function cc_user_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['tmreis_cc']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    public function doctor_exists($email = FALSE){
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['tmreis_doctors']);
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
    
   
    public function delete_chat_group($_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($_id)))->delete('tmreis_chat_groups');
    	return $query;
    }
    public function save_users_to_group($post)
    {
    	$group_exists = $this->mongo_db->where("group_name",$post['ed_group_name'])->get('tmreis_chat_groups_users');
    	if($group_exists){
    		$data = array(
    				"selected_group" => $post['select_group_name'],
    				"group_name" => $post['ed_group_name'],
    				"list_of_users" => $post['users'],
    				"created_time" => date("Y-m-d H:i:s")
    		);
    		$query = $this->mongo_db->where("group_name",$post['ed_group_name'])->set ( $data )->update ( $this->collections['tmreis_chat_groups_users'] );
    		
    		return $query;
    		
    	}else{
    		$data = array(
    				"selected_group" => $post['select_group_name'],
    				"group_name" => $post['ed_group_name'],
    				"list_of_users" => $post['users'],
    				"created_time" => date("Y-m-d H:i:s")
    		);
    		$query = $this->mongo_db->insert($this->collections['tmreis_chat_groups_users'],$data);

    		return $query;
    	}
    }

    public function create_group($post,$type)
    {
        $data = array(
                "group_name" => $post['group_name'],
                "created_at" => date('Y-m-d H:i:s'),
                "user_type"  => $type
        );
        
        $query = $this->mongo_db->insert('tmreis_chat_groups',$data);
        return $query;
    }
	
	// SANITATION
	public function get_sanitation_infrastructure_model($district_name,$school_name)
	{
		$this->mongo_db->limit(1)->where(array('doc_data.widget_data.page6.School Information.District'=>$district_name,'doc_data.widget_data.page6.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_infra_app_col);
		if($query)
			return $query;
		else
			return FALSE;
	}
}


