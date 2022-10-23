<?php
class Todo_item extends CI_Model
{
    public $todo_id;
    public $title;
    public $description;
    public $due_date;
    public $is_done;
    
    function __construct()
    {
    	// Call the Model constructor
    	parent::__construct();
		
		$this->_configvalue = $this->config->item('default');
    
    	// Load MongoDB library,
    	$this->load->library('mongo_db');
    	$this->load->config('email');
    	
    	$this->load->config('ion_auth', TRUE);
    	$this->load->config('mongodb',TRUE);
    	
    	$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
    	$this->salt_length     = $this->config->item('salt_length', 'ion_auth');
    	
    	// Load the session, CI2 as a library, CI3 uses it as a driver
    	if (substr(CI_VERSION, 0, 1) == '2')
    	{
    		$this->load->library('session');
    	}
    	else
    	{
    		$this->load->driver('session');
    	}
    	
    }
     
    public function save($username, $userpass)
    {
        //get the username/password hash
        $userhash = sha1("{$username}_{$userpass}");
        if( is_dir(DATA_PATH."/{$userhash}") === false ) {
            $this->mkdir_ext(DATA_PATH."/{$userhash}");
        }
         
        //if the $todo_id isn't set yet, it means we need to create a new todo item
        if( is_null($this->todo_id) || !is_numeric($this->todo_id) ) {
            //the todo id is the current time
            $this->todo_id = time();
        }
         
        //get the array version of this todo item
        $todo_item_array = $this->toArray();
         
        //save the serialized array version into a file
        $success = file_put_contents(DATA_PATH."/{$userhash}/{$this->todo_id}.txt", serialize($todo_item_array));
         
        //if saving was not successful, throw an exception
        if( $success === false ) {
            throw new Exception('Failed to save todo item');
        }
         
        //return the array version
        return $todo_item_array;
    }
     
    public function toArray()
    {
        //return an array version of the todo item
        return array(
            'todo_id' => $this->todo_id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'is_done' => $this->is_done
        );
    }
    
    function fetch_keys($name){
    	
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->where(array('display_company_name' => $name))->get('api_details');
    	return $query[0];
    }
    
    function login($identity, $password, $coll){
    	
    	log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnlllllllllllllllllllllllllllllllllllll'.$identity.$password.$coll);
    	if (empty($identity) || empty($password) || empty($coll))
    	{
    		$this->set_error('login_unsuccessful');
    		return FALSE;
    	}
    	
    	//$salt       = $this->store_salt ? $this->salt() : FALSE;
    	//$hashed_pswd = $this->hash_password($password, $salt);
		
		
		
		
		$password = md5($password);
    	
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$document = $this->mongo_db->Where(array('email'=>$identity,'password'=>$password))->get('api_details');
    	log_message('debug','ttttttttttttttttttttttttttttttttttttttttttt'.print_r($document,true));
    	
    	// If user document found
    	if (count($document) === 1)
    	{
    		$user = (object) $document[0];
    		
    			// Set user session data
    			$session_data = array(
    					'username' => $user->username,
    					'email' => $user->email,
    					'user_id' => $user->_id->{'$id'},
    					'old_last_login' => $user->last_login
    			);
     			log_message('debug','sssssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($session_data,true));
    			$this->session->set_userdata($session_data);
    			$this->session->set_userdata("customer",$session_data);
    			
    			log_message('debug','2222222222222222222222222222222222222222222222222'.print_r($session_data,true));
    			return TRUE;
    		
    	}
    	// The user document was not found
//     	$this->hash_password($password);
//     	$this->increase_login_attempts($identity);
//     	$this->trigger_events('post_login_unsuccessful');
//     	$this->set_error('login_unsuccessful');
    	return FALSE;
    }
    
    function fetch_api_docs($contact, $coll){
    	 
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->select(array('transaction_id','company', 'app_name'))->where(array('contact' => $contact, 'status' => 'new'))->get($coll);
    	return $query;
    }
    
    function fetch_api_pdf($contact, $trans_id,$coll){
    
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->select(array('transaction_id', 'pdf_name','pdf_path'))->where(array('contact' => $contact, 'transaction_id' => $trans_id))->get($coll);
    	$this->mongo_db->where(array('contact' => $contact, 'transaction_id' => $trans_id))->set(array('status' => 'processed'))->update($coll);
    	return $query;
    }
    
    function update_api_data($contact, $trans_id,$coll, $ins_data){
    	log_message('debug','innnnnnininiuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu');
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->where(array('contact' => $contact, 'transaction_id' => $trans_id))->set($ins_data)->update($coll);
    	$this->mongo_db->switchDatabase(DNS_DB);
    	log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
    	return $query;
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
    
    /**
     * Generates a random salt value.
     */
    public function salt()
    {
    	return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }
    
    function put_ip_in_collection($ip, $api_agent){
    	 
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->insert('push_to_ip', array('api_agent' => $api_agent, 'ip' => $ip, 'status' => 'online'));
    	$this->mongo_db->switchDatabase(DNS_DB);
    	return $query;
    }
	function ip_exists($name){
    	
		log_message('debug','nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn'.print_r($name,true));
    	$this->mongo_db->switchDatabase(COMMON_DB);
    	$query = $this->mongo_db->where('api_agent', $name)->count('push_to_ip');
		log_message('debug','counttttttttttttttttttttttttttttt'.print_r($query,true));
		if($query > 0 ){
			return true;
		}else{
			return false;
		}
    }
	
	function update_ip_in_collection($ip, $api_agent){
    	 
    	$this->mongo_db->switchDatabase(COMMON_DB);
		$query = $this->mongo_db->where(array('api_agent' => $api_agent))->set(array('ip' => $ip, 'status' => 'online'))->update('push_to_ip');
    	$this->mongo_db->switchDatabase(DNS_DB);
    	return $query;
    }
}