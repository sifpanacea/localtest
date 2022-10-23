 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Schoolhealth_auth_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->config('ion_auth', TRUE);
		$this->load->library('session');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
        $this->collections = $this->config->item('collections', 'ion_auth');
		
		// Initialize general config directives
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length     = $this->config->item('salt_length', 'ion_auth');
		
		// Initialize hash method directives (Bcrypt)
		$this->hash_method    = $this->config->item('hash_method', 'ion_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
		$this->random_rounds  = $this->config->item('random_rounds', 'ion_auth');
		$this->min_rounds     = $this->config->item('min_rounds', 'ion_auth');
		$this->max_rounds     = $this->config->item('max_rounds', 'ion_auth');
	}
 
    
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Device Login ( Students )
	 *
	 * @param  string  $identity     Unique ID ( identity field )
	 * @param  string  $password     Password
	 *  
	 * @author Selva 
	 */
	 
	public function student_device_login($identity,$password)
    {
	   
   	if(empty($identity) || empty($password))
   	{
   		echo "EMPTY_CREDENTIALS";
   		return FALSE;
   	}
	
	$currentdate = Date("Y-m-d");
	
	$userdocument = $this->mongo_db
		->select(array($this->identity_column, '_id', 'name','password','active', 'last_login','registered_on','plan_expiry','company_name','hospital_unique_id','consent_status'))
		->where(array("hospital_unique_id" => $identity))
		->limit(1)
		->get($this->collections['schoolhealth_students']);
	    
		if($userdocument)
		{
			$user = (object) $userdocument[0];
			$password = $this->hash_password_schoolhealth_student_db($user->_id,$password);
			
			if($password === TRUE)
			{
				// Not yet activated?
				if ($user->active == 0)
				{
					echo "USER_INACTIVE";
					return FALSE;
				}
				
				// Expired?
				$expiry_date = $user->plan_expiry;
				if($expiry_date == $currentdate || $expiry_date < $currentdate )
				{
				  echo "EXPIRED";
                  return FALSE;
				}	 
		
				// Update last login time
				$this->update_last_login($user->_id,"schoolhealth_student");
				
				// Set user session data
                $session_data = array(
					'identity'       => $user->hospital_unique_id,
					'username'       => $user->name,
					'password'		 => $user->password,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'registered'     => $user->registered_on,
					'plan_expiry'    => $user->plan_exp,
					'consent_status'    => $user->consent_status,
					'status'		 => "SUCCESS"
                );

				$cus_url = base_url().$user->company_name;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/schoolhealth_auth/dashsession/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
		        
				return TRUE;
			}
			else
			{
			   //echo "INCORRECT_PASSWD";
			   $this->output->set_output(json_encode(array('status' => 'INCORRECT PASSWORD')));
			   return FALSE;
			}
		
		}
		else
		{
		   //echo "INCORRECT_UNIQUEID";
			$this->output->set_output(json_encode(array('status' => 'INCORRECT UNIQUEID')));
		   return FALSE;
		}
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Updates patient last login timestamp.
	 *
	 * @param  string  $id  _id field
	 *
	 * @return bool
	 */
	public function update_last_login($id,$user)
	{
	    if($user == "schoolhealth_school")
		{
			return $this->mongo_db
				->where('_id', new MongoId($id))
				->set('last_login', date("Y-m-d H:i:s"))
				->update($this->collections['schoolhealth_schools']);
		}
		else if($user == "schoolhealth_clinic")
		{
			return $this->mongo_db
				->where('_id', new MongoId($id))
				->set('last_login', date("Y-m-d H:i:s"))
				->update($this->collections['schoolhealth_clinics']);
		}
		else if($user == "schoolhealth_admin")
		{
			return $this->mongo_db
				->where('_id', new MongoId($id))
				->set('last_login', date("Y-m-d H:i:s"))
				->update($this->collections['schoolhealth_admins']);
		}
		else if($user == "schoolhealth_student")
		{
			return $this->mongo_db
				->where('_id', new MongoId($id))
				->set('last_login', date("Y-m-d H:i:s"))
				->update($this->collections['schoolhealth_students']);
		}
		else if($user == "schoolhealth_sub_admin")
		{
			return $this->mongo_db
				->where('_id', new MongoId($id))
				->set('last_login', date("Y-m-d H:i:s"))
				->update($this->collections['schoolhealth_sub_admins']);
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Web Login
	 *
	 * @param  string  $identity     Unique id ( identity field )
	 * @param  string  $password     Password
	 * @param  string  $remember     Remember me flag
	 *  
	 * @author Selva 
	 */
	 
	public function web_login($identity,$password,$remember)
	{
		if (empty($identity) || empty($password))
		{
			return FALSE;
		}

        $currentdate = date("Y-m-d");
		$schooldocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan','display_company_name','school_code','school_name'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['schoolhealth_schools']);
		
		// If school document founds
		if (count($schooldocument) === 1)
		{
			$user = (object) $schooldocument[0];
			
			$password = $this->hash_password_schoolhealth_school_db($user->_id, $password);
			
			if ($password === TRUE)
			{
				// Not yet activated?
                if ($user->active == 0)
                {
                    return FALSE;
                }
				
				if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
				{
                    return FALSE;
				}	

                // Set user session data
                $session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'display_company_name'=> $user->display_company_name,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'expiry'         => $user->plan_expiry,
					'designation'    => "schoolhealth_school",
					'school_code'    => $user->school_code,
					'school_name'    => $user->school_name
                );
				
                // Clean login attempts, also update last login time
                $this->update_last_login($user->_id,"schoolhealth_school");

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_me($user->email);
                }
				
                $cus_url = base_url().$user->company_name;
			    $h       = json_encode($session_data);
			    $str     = base64_encode($h);
				
				$this->input->set_cookie('language', 'english', 3600*2);
				
			    redirect($cus_url.'/index.php/schoolhealth_auth/session/'.$str);
                return TRUE;
				
			}
		}
		else 
		{
		   $clinicdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','subscription_end'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['schoolhealth_clinics']);
			
			// If doctor document found
            if(count($clinicdocument) === 1)
			{
				$user = (object) $clinicdocument[0];
				$password = $this->hash_password_schoolhealth_clinic_db($user->_id,$password);
				if ($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						return FALSE;
					}
					
					// Set user session data
					$session_data = array(
						'identity'       => $user->{$this->identity_column},
						'username'       => $user->username,
						'email'          => $user->email,
						'user_id'        => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company'        => $user->company_name,
						'designation'    => "schoolhealth_clinic"
					);
					
					// Clean login attempts, also update last login time
					$this->update_last_login($user->_id,"schoolhealth_clinic");
			
					// Check whether we should remember the user
					if ($remember && $this->config->item('remember_users', 'ion_auth'))
					{
						$this->remember_me($user->email);
					}
					
					$cus_url = base_url().$user->company_name;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					
					$this->input->set_cookie('language', 'english', 3600*2);
					
					redirect($cus_url.'/index.php/schoolhealth_auth/session/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
					return TRUE;
			    }
		    }
			else
			{
				$admindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry'))
				->where("email", (string) $identity)
				->limit(1)
				->get($this->collections['schoolhealth_admins']);
				
				if(count($admindocument) === 1)
				{
					$user = (object) $admindocument[0];
					$password = $this->hash_password_schoolhealth_admin_db($user->_id, $password);
					if ($password === TRUE)
					{
						// Not yet activated?
						if ($user->active == 0)
						{
							$this->set_error('login_unsuccessful_not_active');
							return FALSE;
						}

						// Set user session data
						$session_data = array(
							'identity'       => $user->{$this->identity_column},
							'username'       => $user->username,
							'email'          => $user->email,
							'user_id'        => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company'        => $user->company_name,
							'plan_expiry'    => $user->plan_expiry,
							'designation'    => "schoolhealth_admin"
						);

						set_cookie(array(
						'name'   => 'admin_identity',
						'value'  => $user->email
					   ));

						// Clean login attempts, also update last login time
						$this->update_last_login($user->_id,"schoolhealth_admin");
					

						// Check whether we should remember the user
						if ($remember && $this->config->item('remember_users', 'ion_auth'))
						{
							$this->remember_me($user->email);
						}
						$cus_url = base_url()."healthcare";
						$h = json_encode($session_data);
						$str = base64_encode($h);
						
						$this->input->set_cookie('language', 'english', 3600*2);
						
						redirect($cus_url.'/index.php/schoolhealth_auth/session/'.$str);
						$this->trigger_events(array('post_login', 'post_login_successful'));
						$this->set_message('login_successful');
						return TRUE;
					}
				}
				else
				{
					   $subadmindocument = $this->mongo_db
					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry'))
					->where("email", (string) $identity)
					->limit(1)
					->get($this->collections['schoolhealth_sub_admins']);
					
					if(count($subadmindocument) === 1)
					{
						$user = (object) $subadmindocument[0];
						$password = $this->hash_password_schoolhealth_sub_admin_db($user->_id, $password);
						if ($password === TRUE)
						{
							// Not yet activated?
							if ($user->active == 0)
							{
								$this->set_error('login_unsuccessful_not_active');
								return FALSE;
							}

							// Set user session data
							$session_data = array(
								'identity'       => $user->{$this->identity_column},
								'username'       => $user->username,
								'email'          => $user->email,
								'user_id'        => $user->_id->{'$id'},
								'old_last_login' => $user->last_login,
								'company'        => $user->company_name,
							    'plan_expiry'    => $user->plan_expiry,
								'designation'    => "schoolhealth_sub_admin"
							);

							set_cookie(array(
							'name'   => 'admin_identity',
							'value'  => $user->email
						   ));

							// update last login time
							$this->update_last_login($user->_id,"schoolhealth_sub_admin");
						

							// Check whether we should remember the user
							if ($remember && $this->config->item('remember_users', 'ion_auth'))
							{
								$this->remember_me($user->email);
							}
							$cus_url = base_url().$user->company_name;
							$h = json_encode($session_data);
							$str = base64_encode($h);
							
							$this->input->set_cookie('language', 'english', 3600*2);
							
							redirect($cus_url.'/index.php/schoolhealth_auth/session/'.$str);
							$this->trigger_events(array('post_login', 'post_login_successful'));
							$this->set_message('login_successful');
							return TRUE;
						}
					}
				} 
			}
		}
		
		// The document was not found
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
	
	public function hash_password_schoolhealth_school_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_schools']);
		
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
	
	// --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Takes a password and validates it against an entry in the collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Selva
	 */
	
	public function hash_password_schoolhealth_clinic_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_clinics']);
		
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
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_admins']);
		
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
	
	// --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Takes a password and validates it against an entry in the collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Selva
	 */
	
	public function hash_password_schoolhealth_sub_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_sub_admins']);
		
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
	
	// --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Takes a password and validates it against an entry in the collection ( student login collection ).
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Selva
	 */
	
	public function hash_password_schoolhealth_student_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_students']);
		
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
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Remembers Diagnostic center/Doctor/Technician by setting required cookies
	 *
	 * @return bool
	 */
	public function remember_me($email)
	{

		if (!$email)
		{
			return FALSE;
		}

		 // Load child care school admin document
		 $school_admin = $this->mongo_db->getWhere($this->collections['schoolhealth_schools'],array('email'=>$email));

		if($school_admin)
		{
			// Re-hash user password as remember code
			$salt = sha1($school_admin[0]['password']);

			$updated = $this->mongo_db
				->where('email', $email)
				->set('remember_code', $salt)
				->update($this->collections['schoolhealth_schools']);

			// Set cookies
			if ($updated)
			{
				// if the user_expire is set to zero we'll set the expiration two years from now.
				if($this->config->item('user_expire', 'ion_auth') === 0)
				{
					$expire = (60*60*24*365*2);
				}
				// otherwise use what is set
				else
				{
					$expire = $this->config->item('user_expire', 'ion_auth');
				}

				set_cookie(array(
					'name'   => 'identity',
					'value'  => $customer['email'],
					'expire' => $expire
				));

				set_cookie(array(
					'name'   => 'remember_code',
					'value'  => $salt,
					'expire' => $expire
				));
				
				return TRUE;
			}
	    }
	   else
	   {

	    // Load child care clinic document
		 $clinic = $this->mongo_db->getWhere($this->collections['schoolhealth_clinics'],array('email'=>$email));
		 
	   	if($clinic)
	   	{
	   		$salt = sha1($clinic[0]['password']);

		   $updated = $this->mongo_db
			->where('email', $email)
			->set('remember_code', $salt)
			->update($this->collections['schoolhealth_clinics']);

			// Set cookies
			if ($updated)
			{
				// if the user_expire is set to zero we'll set the expiration two years from now.
				if($this->config->item('user_expire', 'ion_auth') === 0)
				{
					$expire = (60*60*24*365*2);
				}
				// otherwise use what is set
				else
				{
					$expire = $this->config->item('user_expire', 'ion_auth');
				}

				set_cookie(array(
					'name'   => 'identity',
					'value'  => $user['email'],
					'expire' => $expire
				));

				set_cookie(array(
					'name'   => 'remember_code',
					'value'  => $salt,
					'expire' => $expire
				));

				return TRUE;
			}

	   	}
	   	else
	   	{
	   		// Load child care admin document
		 $admin = $this->mongo_db->getWhere($this->collections['schoolhealth_admins'],array('email'=>$email));

			if($admin)
			{
				// Re-hash user password as remember code
				$salt = sha1($admin[0]['password']);

			   $updated = $this->mongo_db
				 ->where('email', $email)
				 ->set('remember_code', $salt)
				 ->update($this->collections['schoolhealth_admins']);

				// Set cookies
				if ($updated)
				{
					// if the user_expire is set to zero we'll set the expiration two years from now.
					if($this->config->item('user_expire', 'ion_auth') === 0)
					{
						$expire = (60*60*24*365*2);
					}
					// otherwise use what is set
					else
					{
						$expire = $this->config->item('user_expire', 'ion_auth');
					}

					set_cookie(array(
						'name'   => 'identity',
						'value'  => $admin['email'],
						'expire' => $expire
					));

					set_cookie(array(
						'name'   => 'remember_code',
						'value'  => $salt,
						'expire' => $expire
					));

					return TRUE;
				}

			}
		
		 }
	   	}

		// User not found
		return FALSE;
		}
		
		// ---------------------------------------------------------------------------

	/**
	 * Helper : Device Login
	 *
	 * @param  string  $identity     Unique id ( identity field )
	 * @param  string  $password     Password
	 * @param  string  $remember     Remember me flag
	 *  
	 * @author Selva 
	 */
	 
	public function device_doctor_login($identity,$password,$remember=FALSE)
   {
	   
   	if(empty($identity) || empty($password))
   	{
   		echo "EMPTY_CREDENTIALS";
   		return FALSE;
   	}
	
	$identity = strtolower($identity);
	
	$currentdate = Date("Y-m-d");
	
	$userdocument = $this->mongo_db
		->select(array($this->identity_column, '_id', 'dr_name', 'email', 'password','active', 'last_login','registered_on','plan_expiry','company_name','plan','email','city'))
		->where(array("email" => $identity))
		->limit(1)
		->get($this->collections['sugar365days_doctors']);
	
		if($userdocument)
		{
			$user = (object) $userdocument[0];
			$password = $this->hash_password_doctor_db($user->_id,$password);
			
			if($password === TRUE)
			{
				// Not yet activated?
				if ($user->active == 0)
				{
					echo "USER_INACTIVE";
					return FALSE;
				}
				
				// Expired?
				$registered_date = $user->registered_on;
				$expiry_date     = $user->plan_expiry;
				
				if($expiry_date == $currentdate || $expiry_date < $currentdate )
				{
				  echo "EXPIRED";
                  return FALSE;
				}	 
		
				// Update last login time
				$this->update_last_login($user->_id,"patient");
				
				// Set user session data
                $session_data = array(
					'identity'       => $user->email,
					'username'       => $user->dr_name,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'plan_expiry'    => $user->plan_expiry,
					'city'           => $user->city
                );

				$cus_url = base_url().$user->company_name;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/sugar365days_auth/dashsession/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
		        
				return TRUE;
			}
			else
			{
			   echo "INCORRECT_PASSWD";
			   return FALSE;
			}
		
		}
		else
		{
		   echo "INCORRECT_EMAIL";
		   return FALSE;
		}
    }
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Hash password
	 *
	 * @param  string  $id                 _id field
	 * @param  string  $password           Password
	 * @param  string  $use_sha1_override  Sha1
	 * 
	 * @return bool
	 *
	 * @author Selva 
	 */
	 
	public function hash_password_patient_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['diabetic_care_patients']);
		
		$hash_password_db = (object) $document[0];

		if (count($document) !== 1)
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
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Validates the email id for forgot password 
	 *
	 * @return bool
	 *  
	 * @author Selva 
	 */
	 
	public function verify_email_for_forgot_password($email)
	{
	  
	  log_message('debug','$email=======1211=====verify'.print_r($email,true));
	  $doctor_document = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['diabetic_care_doctors'],array('email'=>$email));
	  
	  if(!empty($doctor_document) && count($doctor_document === 1))
	  {
        log_message('debug','$email=======1216=====verify'.print_r($doctor_document,true));
	    return TRUE;
	  }
	  else
	  {
	    $lab_document = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['diabetic_care_diagnostic_centers'],array('email'=>$email));
		if(!empty($lab_document) && count($lab_document === 1))
		{
		  return TRUE;
		}
		else
        {
			$admin_document = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['diabetic_care_admins'],array('email'=>$email));
			if(!empty($admin_document) && count($admin_document === 1))
			{
				return TRUE;
			}
			else
			{
				$counsellor_document = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['diabetic_care_counsellors'],array('email'=>$email));
				if(!empty($counsellor_document) && count($counsellor_document === 1))
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
	   }
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Validates the email id for forgot password 
	 *
	 * @return bool
	 *  
	 * @author Selva 
	 */
	 
	public function verify_email_for_forgot_password_doctor_device($email)
	{
	  
	  log_message('debug','$email=======1211=====verify'.print_r($email,true));
	  $doctor_document = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['diabetic_care_doctors'],array('email'=>$email));
	  
	  if(!empty($doctor_document) && count($doctor_document === 1))
	  {
        log_message('debug','$email=======1216=====verify'.print_r($doctor_document,true));
	    return TRUE;
	  }
	  else
	  {
	    return FALSE;
	  }
			
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Validates the email id for forgot password 
	 *
	 * @return bool
	 *  
	 * @author Selva 
	 */
	 
	public function verify_identity_for_forgot_password_patient_device($patient_id)
	{
	  log_message('debug','$email=======1211=====verify'.print_r($patient_id,true));
	  $patient_document = $this->mongo_db->where('unique_id',$patient_id)->select(array(),array('_id'))->get($this->collections['diabetic_care_patients']);
	  log_message('debug','$email=======1211=====verify'.print_r($patient_document,true));
	  log_message('debug','$email=======1211=====verify'.print_r($this->collections['diabetic_care_patients'],true));
	  
	  if(!empty($patient_document) && count($patient_document === 1))
	  {
        log_message('debug','$email=======1216=====patient_document==if=='.print_r($patient_document,true));
	    return TRUE;
	  }
	  else
	  {
         log_message('debug','$email=======1211=====patient_document==else=='.print_r($patient_document,true));
	    return FALSE;
	  }
			
	}
		
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a forgotten password key.
	 *
	 * @return bool
	 */
	public function forgotten_password($identity)
	{
		if(empty($identity))
		{
			return FALSE;
		}

		$forgotten_password_code = $this->hash_code(microtime() . $identity);
		
		$drdocument = $this->mongo_db
			->select()
			->where('email', (string) $identity)
			->limit(1)
			->get($this->collections['diabetic_care_doctors']);

			
		// If customer document found
		if(count($drdocument) === 1)
		{

		 $updated = $this->mongo_db
			->where('email', $identity)
			->set(array(
				'forgotten_password_code' => $forgotten_password_code,
				'forgotten_password_time' => date('Y-m-d H:i:s')
			))
			->update($this->collections['diabetic_care_doctors']);
        
			  if (!$updated)
			   {
				  return FALSE;
			   }
			   else
			   {
		          return $forgotten_password_code;
			   }
		 }  
		 else 
		 {
		 	 $labdocument = $this->mongo_db
			->select()
			->where("email", (string) $identity)
			->limit(1)
			->get($this->collections['diabetic_care_diagnostic_centers']);

               // If user document found
			if (count($labdocument) === 1)
			{

		   	  $updated = $this->mongo_db
			->where('email', $identity)
			->set(array(
				'forgotten_password_code' => $forgotten_password_code,
				'forgotten_password_time' => date('Y-m-d H:i:s')
			))
			->update($this->collections['diabetic_care_diagnostic_centers']);

			   if (!$updated)
			   {
				  return FALSE;
			   }
			   else
			   {
		          return $forgotten_password_code;
			   }

		    }
            else
            {
            	$admindocument = $this->mongo_db
			   ->select()
			   ->where('email', (string) $identity)
			   ->limit(1)
			   ->get($this->collections['diabetic_care_admins']);

			   if (count($admindocument) === 1)
			   {

				  $updated = $this->mongo_db
				->where('email', $identity)
				->set(array(
					'forgotten_password_code' => $forgotten_password_code,
					'forgotten_password_time' => date('Y-m-d H:i:s')
				))
				->update($this->collections['diabetic_care_admins']);

				if (!$updated)
			   {
				  return FALSE;
			   }
			   else
			   {
		          return $forgotten_password_code;
			   }

                }
				else
				{
						$counsellordocument = $this->mongo_db
				   ->select()
				   ->where('email', (string) $identity)
				   ->limit(1)
				   ->get($this->collections['diabetic_care_counsellors']);

				   if (count($counsellordocument) === 1)
				   {

					  $updated = $this->mongo_db
					->where('email', $identity)
					->set(array(
						'forgotten_password_code' => $forgotten_password_code,
						'forgotten_password_time' => date('Y-m-d H:i:s')
					))
					->update($this->collections['diabetic_care_counsellors']);

					if (!$updated)
				   {
					  return FALSE;
				   }
				   else
				   {
					  return $forgotten_password_code;
				   }
				}
				else
				{
			       return FALSE;
				}
			}
			}
		}
			
		return $updated;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a forgotten password key.
	 *
	 * @return bool
	 */
	public function forgotten_password_device_user($identity)
	{
		if(empty($identity))
		{
			return FALSE;
		}

		$forgotten_password_code = $this->hash_code(microtime() . $identity);
		
		$drdocument = $this->mongo_db
			->select()
			->where('email', (string) $identity)
			->limit(1)
			->get($this->collections['diabetic_care_doctors']);

			
		// If customer document found
		if(count($drdocument) === 1)
		{

		 $updated = $this->mongo_db
			->where('email', $identity)
			->set(array(
				'forgotten_password_code' => $forgotten_password_code,
				'forgotten_password_time' => date('Y-m-d H:i:s')
			))
			->update($this->collections['diabetic_care_doctors']);
        
			  if (!$updated)
			   {
		          echo "FAIL";
				  return FALSE;
			   }
			   else
			   {
		          return $forgotten_password_code;
			   }
		 }  
		 else 
		 {
		 	$counsellordocument = $this->mongo_db
				   ->select()
				   ->where('email', (string) $identity)
				   ->limit(1)
				   ->get($this->collections['diabetic_care_counsellors']);

				   if (count($counsellordocument) === 1)
				   {

					  $updated = $this->mongo_db
					->where('email', $identity)
					->set(array(
						'forgotten_password_code' => $forgotten_password_code,
						'forgotten_password_time' => date('Y-m-d H:i:s')
					))
					->update($this->collections['diabetic_care_counsellors']);

					if (!$updated)
				   {
			          echo "FAIL";
					  return FALSE;
				   }
				   else
				   {
					  return $forgotten_password_code;
				   }
				}
				else
				{
			      $patientdocument = $this->mongo_db
				   ->select()
				   ->where('email', (string) $identity)
				   ->limit(1)
				   ->get($this->collections['diabetic_care_patients']);

				   if (count($patientdocument) === 1)
				   {

					  $updated = $this->mongo_db
					->where('email', $identity)
					->set(array(
						'forgotten_password_code' => $forgotten_password_code,
						'forgotten_password_time' => date('Y-m-d H:i:s')
					))
					->update($this->collections['diabetic_care_patients']);

					if (!$updated)
				   {
			          echo "FAIL";
					  return FALSE;
				   }
				   else
				   {
					  return $forgotten_password_code;
				   }
				   }
				   else
				   {
			         return FALSE;
				   }
			
				}
				
			}
			
		return $updated;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a forgotten password key.
	 *
	 * @return bool
	 */
	public function forgotten_password_patient_device($identity)
	{
		if(empty($identity))
		{
			return FALSE;
		}

		$forgotten_password_code = $this->hash_code(microtime() . $identity);
		
	   $patientdocument = $this->mongo_db
	    ->select()
	    ->where('unique_id', (string) $identity)
	    ->limit(1)
	    ->get($this->collections['diabetic_care_patients']);
		
	  $email  = $patientdocument[0]['email'];
	  $mobile = $patientdocument[0]['mobile'];
	  
	  $forgot_pwd_process = array();

	   if (count($patientdocument) === 1)
	   {
          $updated = $this->mongo_db
		     ->where('unique_id', $identity)
		     ->set(array(
				'forgotten_password_code' => $forgotten_password_code,
				'forgotten_password_time' => date('Y-m-d H:i:s')
		    ))
		    ->update($this->collections['diabetic_care_patients']);

				if (!$updated)
				{
				  echo "FAIL";
				  return FALSE;
				}
				else
				{
			      if(isset($email) && !empty($email))
				  {
			         $forgot_pwd_process['email'] = $email;
				  }
				  
				  if(isset($mobile) && !empty($mobile))
				  {
			         $forgot_pwd_process['mobile'] = $mobile;
				  }
				  
				  $forgot_pwd_process['forgotten_password_code'] = $forgotten_password_code;
				  
				  return $forgot_pwd_process;
				}
		}
	    else
	    {
		  return FALSE;
	    }
		
	}
				
	// ------------------------------------------------------------------------

	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 */
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}
	
	/**
	 * Generates a random salt value.
	 */
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}
	
	// ------------------------------------------------------------------------

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
	 * forgotten_password_check
	 *
	 * @return void
	 * @author Michael ( Modified by Selva )
	 **/
	public function forgotten_password_check($code)
	{
        $profile = $this->get_details_by_forgotten_password_code($code); 
		log_message('debug','diabetic_care_auth_model=====forgotten_password_check=====$profile'.print_r($profile,true));
		
		if (!is_array($profile))
		{
			return FALSE;
		}
		else
		{
	       log_message('debug','diabetic_care_auth_model=====forgotten_password_check=====$profile==is_array==else'.print_r($profile,true));
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				log_message('debug','diabetic_care_auth_model=====forgotten_password_check=====$expiration'.print_r($expiration,true));
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					log_message('debug','diabetic_care_auth_model=====forgotten_password_check=====EXPIRED');
					$this->clear_forgotten_password_code($code);
					return FALSE;
				}
			}
			return $profile;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: get customer/user profile by forgotten password code 
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */
	 
	public function get_details_by_forgotten_password_code($code)
	{
	  $drprofile = $this->mongo_db->getWhere($this->collections['diabetic_care_doctors'],array('forgotten_password_code'=>$code));
	  
	  if($drprofile)
	  {
		  return $drprofile;
	  }
	  else
	  {
        $labprofile = $this->mongo_db->getWhere($this->collections['diabetic_care_diagnostic_centers'],array('forgotten_password_code'=>$code));
        if($labprofile)
	     {
	       return $labprofile;
	     }
	     else
	     {
	     	$adminprofile = $this->mongo_db->getWhere($this->collections['diabetic_care_admins'],array('forgotten_password_code'=>$code));
            if($adminprofile)
	        {
	           return $adminprofile;
	        }
			else
			{
		       $counsellorprofile = $this->mongo_db->getWhere($this->collections['diabetic_care_counsellors'],array('forgotten_password_code'=>$code));
				if($counsellorprofile)
				{
				   return $counsellorprofile;
				}
				else
				{
			       $patientprofile = $this->mongo_db->getWhere($this->collections['diabetic_care_patients'],array('forgotten_password_code'=>$code));
				   log_message('debug','diabetic_care_auth_model=====get_details_by_forgotten_password_code=====$patientprofile'.print_r($patientprofile,true));
					if($patientprofile)
					{
					   return $patientprofile;
					}
					else
					{
				
					}
				}
			}
		}
	   }
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Resets password.
	 *
	 * @return bool
	 */
	public function reset_password($identity,$new) 
	{
		if (!$this->identity_check_for_reset_password($identity)) {
			return FALSE;
		}

		$drdocs = $this->mongo_db
			->select(array('_id','password','salt'))
			->where('email', $identity)
			->limit(1)
			->get($this->collections['diabetic_care_doctors']);

		// Unsuccessfull password change
		if (count($drdocs) !== 1)
		{

			$labdocs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where('email', $identity)
			->limit(1)
			->get($this->collections['diabetic_care_diagnostic_centers']);

             if(count($labdocs) !== 1)
             {
             	$admindocs = $this->mongo_db
			   ->select(array('_id', 'password', 'salt'))
			   ->where('email', $identity)
			   ->limit(1)
			   ->get($this->collections['diabetic_care_admins']);

				    if(count($admindocs) !== 1)
			        {
				      $patientdocs = $this->mongo_db
						->select(array('_id', 'password', 'salt'))
						->where('unique_id', $identity)
						->limit(1)
						->get($this->collections['diabetic_care_patients']);
						
						log_message('debug','diabetic_care_auth_model=====reset_password=====$patientdocs'.print_r($patientdocs,true));
						
						if(count($patientdocs) !== 1)
			            {
						   return FALSE;
						}
						else
						{
					        // Generate new password hash
						$result = (object) $patientdocs[0];
						$new    = $this->hash_password($new);

						// Store the new password and reset the remember code so all remembered instances have
						// to re-login. Also clear the forgotten password code.
						$updated = $this->mongo_db
							->where('unique_id', $identity)
							->set(array(
								'password'                => $new,
								'remember_code'           => NULL,
								'forgotten_password_code' => NULL,
								'forgotten_password_time' => NULL,
							))
							->update($this->collections['diabetic_care_patients']);
							
							if ($updated)
				            {
					          
				            }
				           else
				           {
					         
				            }
						}
					}
					else
					{ 
					    // Generate new password hash
						$result = (object) $admindocs[0];
						$new    = $this->hash_password($new);

						// Store the new password and reset the remember code so all remembered instances have
						// to re-login. Also clear the forgotten password code.
						$updated = $this->mongo_db
							->where('email', $identity)
							->set(array(
								'password'                => $new,
								'remember_code'           => NULL,
								'forgotten_password_code' => NULL,
								'forgotten_password_time' => NULL,
							))
							->update($this->collections['diabetic_care_admins']);
							
							if ($updated)
				            {
					          
				            }
				           else
				           {
					         
				            }
					  
						 
					}
	        }
			else
			{
			            // Generate new password hash
						$result = (object) $labdocs[0];
						$new    = $this->hash_password($new);


						// Store the new password and reset the remember code so all remembered instances have
						// to re-login. Also clear the forgotten password code.
						$updated = $this->mongo_db
							->where('email', $identity)
							->set(array(
								'password'                => $new,
								'remember_code'           => NULL,
								'forgotten_password_code' => NULL,
								'forgotten_password_time' => NULL,
							))
							->update($this->collections['diabetic_care_diagnostic_centers']);

							if ($updated)
				            {
					          
				            }
				           else
				           {
					         
				            }

	        }
	    }        
        else
        { 
             	// Generate new password hash
				$result = (object) $drdocs[0];
				$new    = $this->hash_password($new);

				// Store the new password and reset the remember code so all remembered instances have
				// to re-login. Also clear the forgotten password code.
				$updated = $this->mongo_db
					->where($this->identity_column, $identity)
					->set(array(
						'password'                => $new,
						'remember_code'           => NULL,
						'forgotten_password_code' => NULL,
						'forgotten_password_time' => NULL,
					))
					->update($this->collections['diabetic_care_doctors']);

				if ($updated)
				{
					return TRUE;
				}
				else
				{
					
				}
	    }	
        
		log_message('debug','diabetic_care_auth_model=====reset_password=====$updated'.print_r($updated,true));
		
		return $updated;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Checks identity field for reset password.
	 *
	 * @return bool
	 */
	protected function identity_check_for_reset_password($identity = '')
	{
		if(empty($identity))
		{
			return FALSE;
		}

		$dridentity = $this->mongo_db->where('email', $identity)->get($this->collections['diabetic_care_doctors']);
		
        if($dridentity)
        {
        	return count($dridentity) > 0;
        }
        else
        {
          $labidentity = $this->mongo_db->where('email', $identity)->get($this->collections['diabetic_care_diagnostic_centers']);
		  
          if($labidentity)
          {
        	return count($labidentity) > 0;
          }
          else
          {
          	$admindentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['diabetic_care_admins']);	
            if($admindentity)
            {
        	   return count($admindentity) > 0;
            }
			else
			{
		       $patientidentity = $this->mongo_db->where("unique_id", $identity)->get($this->collections['diabetic_care_patients']);	
				if($patientidentity)
				{
				   return count($patientidentity) > 0;
				}
				else
				{
				  return 0;
				}
		      
			}
			

          }
        }
	}
	
	public function update_consent_status_model($hospital_unique_id,$consent_status)
	{
		$updated = $this->mongo_db->where (array("hospital_unique_id" =>$hospital_unique_id ))
						->set(array('consent_status' => $consent_status))
						->update( $this->collections['schoolhealth_students'] );
		if($updated)
		{
			 $this->output->set_output(json_encode(array('status' => 'CONSENT STATUS UPDATED')));

		}else{
			$this->output->set_output(json_encode(array('status' => 'FAILED')));
			return FALSE;
		}
		
	}
	
	 
} 
