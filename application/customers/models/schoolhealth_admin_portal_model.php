 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Schoolhealth_admin_portal_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
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
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
		$this->random_rounds  = $this->config->item('random_rounds', 'ion_auth');
		$this->min_rounds     = $this->config->item('min_rounds', 'ion_auth');
		$this->max_rounds     = $this->config->item('max_rounds', 'ion_auth');
		
		$this->screening_app_col = 'healthcare20161014212024617';
		$this->screening_app_col_screening = "healthcare20161014212024617_screening_final";
		$this->today_date = date ( 'Y-m-d' );
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Hashes the password to be stored in the database.
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
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper : Generates a random salt value.
	 */
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}
	
	
	public function get_reports_ehr_uid($uid) 
	{
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data',
					'doc_data.chart_data',
					'doc_data.external_attachments',
					'history' 
			) )->whereLike( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
			$result ['screening'] = $query;
			//$result ['request']   = false;
			return $result;
			
	}
	
	
	//////////// States/////////////////////////////////////////////
	public function statescount()
    {
     $count = $this->mongo_db->count($this->collections['schoolhealth_states']);
	 return $count;
    }
	
	public function get_states($per_page,$page)
    {
     $query = $this->mongo_db->limit($per_page)->offset($page-1)->get($this->collections['schoolhealth_states']);
	 return $query;
    }
	
	public function get_all_states()
    {
     $query = $this->mongo_db->get($this->collections['schoolhealth_states']);
	 return $query;
    }
	
	public function get_all_states_edit()
    {
     $query = $this->mongo_db->get($this->collections['schoolhealth_districts']);
	 foreach($query as $distlist => $dist)
	     {
		 $st_name = $this->mongo_db->where('_id',new MongoId($dist['st_name']))->get($this->collections['schoolhealth_states']);
		 if(isset($dist['st_name'])){
			$query[$distlist]['st_name'] = $st_name[0]['st_name'];
		 }else{
			$query[$distlist]['st_name'] = "No state selected";
		 }
	 }
	 return $query;
    }
	
	public function create_state($post)
    {
		$data = array(
		"st_code" => $post['st_code'],
		"st_name" => $post['st_name']);
     $query = $this->mongo_db->insert($this->collections['schoolhealth_states'],$data);
	 return $query;
    }
	
	public function delete_state($st_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($st_id)))->delete($this->collections['schoolhealth_states']);
	 return $query;
    }
	
	/////   Districts /////////////////////////////
	
	public function distcount()
    {
     $count = $this->mongo_db->count($this->collections['schoolhealth_districts']);
	 return $count;
    }
	
	public function get_district($per_page,$page)
    {
     $query = $this->mongo_db->limit($per_page)->offset($page-1)->get($this->collections['schoolhealth_districts']);
	 foreach($query as $distlist => $dist){
		 $st_name = $this->mongo_db->where('_id',new MongoId($dist['st_name']))->get($this->collections['schoolhealth_states']);
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
     $query = $this->mongo_db->get($this->collections['schoolhealth_districts']);
	 
	 return $query;
    }
	
	public function create_district($post)
    {
		$data = array(
		"st_name" => $post['st_name'],
		"dt_code" => $post['dt_code'],
		"dt_name" => $post['dt_name']);
     $query = $this->mongo_db->insert($this->collections['schoolhealth_districts'],$data);
	 return $query;
    }
	
	public function delete_district($dt_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($dt_id)))->delete($this->collections['schoolhealth_districts']);
	 return $query;
    }
	
	/////////////////// schools//////////////////////
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Delete School
	 *
	 * @author  Selva
	 *
	 */
	 
	public function schoolscount()
    {
	 $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
     $count = $this->mongo_db->count($this->collections['schoolhealth_schools']);
	 $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	 return $count;
    }
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Get All Schools
	 *
	 * @author  Selva
	 *
	 */
	 
	public function get_schools($per_page,$page)
    {
      $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	  $query = $this->mongo_db->get($this->collections['schoolhealth_schools']);
	  $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	  log_message("debug","query======123".print_r($query,true));
	  foreach($query as $schools => $school)
	  {
	    if (isset ( $school ['st_name'] )) 
		{
	      $st_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['st_name'] ) )->get ($this->collections['schoolhealth_states']);
		  
		  $query [$schools] ['st_name'] = $st_name[0]['st_name'];
		}
		else
		{
	      $query [$schools] ['st_name'] = "No state selected";
		}
		
		if (isset ( $school ['dt_name'] )) 
		{
		  $dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( $this->collections['schoolhealth_districts']);
		  
		  $query [$schools] ['dt_name'] = $dt_name[0]['dt_name'];
		  
		}
		else
		{
	      $query [$schools] ['dt_name'] = "No district selected";
		}
		
	  }
	  
	  return $query;
	 
    }
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Edit School
	 *
	 * @author  Naresh
	 *
	 */
	public function get_edit_details($id)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->where(array('_id' => new MongoID($id)))->get($this->collections['schoolhealth_schools']);
		log_message("debug","query=========234".print_r($query,true));
		log_message("debug","iddd=========235".print_r($id,true));
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);

		foreach($query as $statelist => $state)
		{
			$st_name = $this->mongo_db->where('_id',new MongoId($state['st_name']))->get($this->collections['schoolhealth_states']);
			if(isset($state['st_name']))
			{
				$query[$statelist]['st_name'] = $st_name[0]['st_name'];
			}
			else
			{
				$query[$statelist]['st_name'] = "No state selected";
			}
		}
		foreach($query as $district => $dist)
		{
			$dt_name = $this->mongo_db->where('_id',new MongoId($dist['dt_name']))->get($this->collections['schoolhealth_districts']);
			log_message("debug","district=======251".print_r($dt_name,true));
			if(isset($dist['dt_name']))
			{
				$query[$district]['dt_name'] = $dt_name[0]['dt_name'];
			}
			else
			{
				$query[$district]['dt_name'] = "No district selected";
			}
		}
		log_message("debug","get_edit_details======233".print_r($query,true));

		return $query;
		
	}
	 
	 // ------------------------------------------------------------------------------
	
	/**
	 * Helper : Update School
	 *
	 * @author  Naresh
	 *
	 */ 
	 public function update_school_details_model($id,$data)
	 {
		 log_message("debug","idddddddddd========276".print_r($id,true));
		 log_message("debug","dataaaa========277".print_r($data,true));
		 
		 $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->where (array('_id'=> new MongoId($id)))->set($data)->update($this->collections['schoolhealth_schools']);    
		 $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		 
		 log_message("debug","update_school_details_model========279".print_r($query,true));
		 return $query;
	   
		 
	 }
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Create School
	 *
	 * @author  Selva
	 *
	 */
	 
	public function create_school_model($data,$password)
    {
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password,$salt);
		
		$data['password']      = $password;
		$data['salt']          = $salt;
		$data['company_name']  = "sugar365days";
		$data['active']        = 1;
		$data['last_login']    = date('Y-m-d H:i:s');
		$data['plan']          = "Silver";
		$data['registered_on'] = "2016-06-25";
		$data['plan_expiry']   = "2017-06-25";
		
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->insert($this->collections['schoolhealth_schools'],$data);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $query;
    }
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Delete School
	 *
	 * @author  Selva
	 *
	 */
	 
	public function delete_school_model($school_id)
    {
	 $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
     $query = $this->mongo_db->where(array("_id"=>new MongoId($school_id)))->delete($this->collections['schoolhealth_schools']);
	 $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	 return $query;
    }
	
	// ------------------------------------------------------------------------------
	
	/**
	 * Helper : Fetch School 
	 *
	 * @author  Selva
	 *
	 */
	 
	public function get_school_by_id($id)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $query = $this->mongo_db->where('_id',new MongoId($id))->get($this->collections['schoolhealth_schools']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query[0];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validates and removes activation code.
	 */
	public function activate_school_model($id, $code = FALSE)
	{
	    // If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
			->select(array('email'),array())
			->where('activation_code', $code)
			->limit(1)
			->get($this->collections['schoolhealth_schools']);
			
			$result = (object) $docs[0];
	
			// If unsuccessfull
			if(count($docs) !== 1)
			{
			  return FALSE;
			}
	
			$identity = $result['email'];
			 
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_schools']);
	
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_schools']);
		}
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}
	
		return $updated;
	}
	
	public function deactivate_school_model($id = NULL)
	{
        if (!isset($id))
		{
			return FALSE;
		}

		$activation_code = sha1(md5(microtime()));

        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['schoolhealth_schools']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}

		return $updated;
	}
	
	
	//////////// sub admin///////////////////
	
	public function subadminscount()
    {
	 $this->mongo_db->switchDatabase($this->common_db['common_db']);
     $count = $this->mongo_db->count($this->collections['schoolhealth_sub_admins']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 return $count;
    }
	
	public function get_subadmins($per_page,$page)
    {
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
      $query = $this->mongo_db->limit($per_page)->offset($page-1)->get($this->collections['schoolhealth_sub_admins']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query;
    }
	
	public function get_all_subadmins()
    {
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
      $query = $this->mongo_db->get($this->collections['schoolhealth_sub_admins']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query;
    }
	
	public function get_subadmin_by_id($id)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $query = $this->mongo_db->where('_id',new MongoId($id))->get($this->collections['schoolhealth_sub_admins']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query[0];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validates and removes activation code.
	 */
	public function activate_sub_admin_model($id, $code = FALSE)
	{
	    // If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
			->select(array('email'),array())
			->where('activation_code', $code)
			->limit(1)
			->get($this->collections['schoolhealth_sub_admins']);
			
			$result = (object) $docs[0];
	
			// If unsuccessfull
			if(count($docs) !== 1)
			{
			  return FALSE;
			}
	
			$identity = $result['email'];
			 
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_sub_admins']);
	
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_sub_admins']);
		}
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}
	
		return $updated;
	}
	
	public function deactivate_sub_admin_model($id = NULL)
	{
        if (!isset($id))
		{
			return FALSE;
		}

		$activation_code = sha1(md5(microtime()));

        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['schoolhealth_sub_admins']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}

		return $updated;
	}
	
	public function create_sub_admin_model($post)
    {
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($post['password'], $salt);
		
		$post["salt"] 	        = $salt;
		$post["password"] 	    = $password;
		$post["company_name"]   = "healthcare";
		$post["plan"]           = "Silver";
		$post["registered_on"]  = "2016-06-25";
		$post["plan_expiry"]    = "2017-06-25";
	
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
        $query = $this->mongo_db->insert($this->collections['schoolhealth_sub_admins'],$post);
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 
	    return $query;
    }
	
	public function delete_sub_admin_model($subadmin_id)
    {
	 $this->mongo_db->switchDatabase($this->common_db['common_db']);
	 $query = $this->mongo_db->where(array("_id"=>new MongoId($subadmin_id)))->delete($this->collections['schoolhealth_sub_admins']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 return $query;
    }
	
	
	
	//////////// clinics///////////////////
	
	public function clinicscount()
    {
	 $this->mongo_db->switchDatabase($this->common_db['common_db']);
     $count = $this->mongo_db->count($this->collections['schoolhealth_clinics']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 return $count;
    }
	
	public function get_clinics($per_page,$page)
    {
	 $this->mongo_db->switchDatabase($this->common_db['common_db']);
     $query = $this->mongo_db->limit($per_page)->offset($page-1)->get($this->collections['schoolhealth_clinics']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 return $query;
    }
	
	public function create_clinic_model($post)
    {
	    $salt       = $this->store_salt ? $this->salt() : FALSE;
	    $password   = $this->hash_password($post['password'], $salt);
		
		$post["salt"] 	        = $salt;
		$post["password"] 	    = $password;
		$post["company_name"]   = "sugar365days";
		$post["plan"]           = "Silver";
		$post["registered_on"]  = "2016-06-25";
		$post["plan_expiry"]    = "2017-06-25";
	
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
        $query = $this->mongo_db->insert($this->collections['schoolhealth_clinics'],$post);
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 
	    return $query;
	}
	
	public function delete_clinic_model($clinic_id)
    {
	 $this->mongo_db->switchDatabase($this->common_db['common_db']);
     $query = $this->mongo_db->where(array("_id"=>new MongoId($clinic_id)))->delete($this->collections['schoolhealth_clinics']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 return $query;
    }
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validates and removes activation code.
	 */
	public function activate_clinic_model($id, $code = FALSE)
	{
	    // If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
			->select(array('email'),array())
			->where('activation_code', $code)
			->limit(1)
			->get($this->collections['schoolhealth_clinics']);
			
			$result = (object) $docs[0];
	
			// If unsuccessfull
			if(count($docs) !== 1)
			{
			  return FALSE;
			}
	
			$identity = $result['email'];
			 
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_clinics']);
	
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['schoolhealth_clinics']);
		}
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}
	
		return $updated;
	}
	
	public function deactivate_clinic_model($id = NULL)
	{
        if (!isset($id))
		{
			return FALSE;
		}

		$activation_code = sha1(md5(microtime()));

        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['schoolhealth_clinics']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			
		}
		else
		{
			return FALSE;
		}

		return $updated;
	}
	
	public function get_clinic_by_id($id)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $query = $this->mongo_db->where('_id',new MongoId($id))->get($this->collections['schoolhealth_clinics']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query[0];
	}
	
	
	/////// change password/////////////
	
	public function change_password($identity, $old, $new)
	{
	$this->mongo_db->switchDatabase($this->common_db['common_db']);
   
	$docs = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where("email", $identity)
			->limit(1)
			->get($this->collections['schoolhealth_admins']);

		if (count($docs) !== 1)
		{
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_schoolhealth_admin_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			// Store the new password and reset the remember code so all remembered instances have to re-login
			
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			
			$updated = $this->mongo_db
				->where("email", $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['schoolhealth_admins']);
				
           $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}
		
		return FALSE;
	}
	
	// --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Takes a password and validates it against an entry in the collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Selva
	 */
	
	public function hash_password_schoolhealth_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
		
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_admins']);
		
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		$hash_password_db = (object) $document[0];
	
		if(count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}
	
	public function get_district_list_for_state_model($state_id)
	{
		$query = $this->mongo_db->where('st_name',$state_id)->get($this->collections['schoolhealth_districts']);
		return $query;
	}
	
} 
