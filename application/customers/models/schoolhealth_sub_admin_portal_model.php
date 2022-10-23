 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Schoolhealth_sub_admin_portal_model extends CI_Model 
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
		
		$sub_admin = $this->session->userdata('customer');
		$email     = $sub_admin['email'];
		$email     = str_replace("@","#",$email);
		$this->screening_app_col = 'healthcare20161014212024617';
		$this->screening_app_col_screening = $email."_pie_analytics";
		$this->today_date = date ( 'Y-m-d' );
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Diagnostic center profile
	 *
	 * @param  string $email   Logged in diagnostic center email
	 *
	 * @output array
	 */
	 
	function admin_dashboard_profile_data($email)
	{
	   $this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $query = $this->mongo_db->where('email',$email)->get($this->collections['diabetic_care_admins']);
	   $this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		if($query)
		   return $query[0];
	
	}
	
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Update diagnostic center profile
	 *
	 * @param string $data          Profile data
	 * @param string $loggedemail   Logged in doctor email
	 *
	 * @output bool
	 */
	
	public function update_profile_data($data,$loggedemail)
	{
	   $this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $query = $this->mongo_db->where('email',$loggedemail)->set($data)->update($this->collections['diabetic_care_admins']);
	   $this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		if($query)
		   return TRUE;
	    else
		   return FALSE;
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
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch all doctors referred by this admin
	 *
	 * @return array
	 */
	 
	public function get_referral_doctors($per_page,$page,$email)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
      $query = $this->mongo_db->limit($per_page)->offset($page-1)->where('referred_by',$email)->get($this->collections['schoolhealth_referral_doctors']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch all states
	 *
	 * @return array
	 */
	 
	public function get_all_states()
	{
		
      $query = $this->mongo_db->get($this->collections['schoolhealth_states']);
	  return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch all districts for the given state
	 *
	 * @return array
	 */
	 
	public function get_district_list_for_state_model($state_id)
	{
		$query = $this->mongo_db->where('st_name',$state_id)->get($this->collections['schoolhealth_districts']);
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch all districts for the all states
	 *
	 * @return array
	 */
	 
	public function get_all_districts_model()
	{
		$query = $this->mongo_db->get($this->collections['schoolhealth_districts']);
		return $query;
	}
	
	public function get_schools_by_dist_id($dist_id,$state_id,$subadmin_id) 
	{
	    if($state_id == "All" && $dist_id == "All")
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'mobile',
					'contact_person',
					'address'
			))->orderBy (array(
					'school_name' => 1 
			))->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else if($state_id != "All" && $dist_id == "All")
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'school_name' => 1 
			))->where(array('st_name'=>$state_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else if($state_id == "All" && $dist_id != "All")
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'school_name' => 1 
			))->where(array('dt_name'=>$dist_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'school_name' => 1 
			))->where(array('st_name'=>$state_id,'dt_name'=>$dist_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
	}
	
	public function get_all_subscribed_schools_count($subadmin_id) 
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
		$this->mongo_db->switchDatabase($this->common_db ['dsn']);
		return count($query);
	}
	
	public function get_all_schools_list_model($subadmin_id) 
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->select ( array (
		        'st_name',
				'dt_name',
				'school_name',
				'school_code',
				'mobile',
				'contact_person',
				'address'
		))->orderBy (array(
				'school_name' => 1 
		))->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_schools']);
		$this->mongo_db->switchDatabase($this->common_db ['dsn']);
		
		foreach ( $query as $schoollist => $school ) 
		{
		    // state 
			$st_name = $this->mongo_db->where('_id', new MongoId ( $school['st_name']))->get($this->collections['schoolhealth_states']);
			if (isset ( $school ['st_name'] )) 
			{
				$query [$schoollist] ['st_name'] = $st_name [0] ['st_name'];
			} 
			else 
			{
				$query [$schoollist] ['st_name'] = "No state selected";
			}
			
			// district
			$dt_name = $this->mongo_db->where('_id', new MongoId ( $school['dt_name']))->get($this->collections['schoolhealth_districts']);
			if (isset ( $school ['dt_name'] )) 
			{
				$query [$schoollist] ['dt_name'] = $dt_name [0] ['dt_name'];
			} 
			else 
			{
				$query [$schoollist] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
		
	}
	
	public function get_clinics_by_dist_id($dist_id,$state_id,$subadmin_id) 
	{
	    if($state_id == "All" && $dist_id == "All")
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'clinic_name',
					'mobile',
					'contact_person',
					'address'
			))->orderBy (array(
					'clinic_name' => 1 
			))->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else if($state_id != "All" && $dist_id == "All")
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'clinic_name',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'clinic_name' => 1 
			))->where(array('st_name'=>$state_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else if($state_id == "All" && $dist_id != "All")
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'clinic_name',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'clinic_name' => 1 
			))->where(array('dt_name'=>$dist_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
		else
		{
	        $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'clinic_name',
					'mobile',
					'contact_person',
					'address'
			))->orderBy(array(
					'clinic_name' => 1 
			))->where(array('st_name'=>$state_id,'dt_name'=>$dist_id,'sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
			$this->mongo_db->switchDatabase($this->common_db ['dsn']);
			return $query;
		}
	}
	
	public function get_all_subscribed_clinics_count($subadmin_id) 
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
		$this->mongo_db->switchDatabase($this->common_db ['dsn']);
		return count($query);
	}
	
	public function get_all_clinics_list_model($subadmin_id) 
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->select ( array (
		        'st_name',
				'dt_name',
				'clinic_name',
				'mobile',
				'contact_person',
				'address'
		))->orderBy (array(
				'clinic_name' => 1 
		))->where(array('sub_admin'=>$subadmin_id))->get ($this->collections['schoolhealth_clinics']);
		$this->mongo_db->switchDatabase($this->common_db ['dsn']);
		
		foreach ( $query as $clinicslist => $clinic ) 
		{
		    // state 
			$st_name = $this->mongo_db->where('_id', new MongoId ( $clinic['st_name']))->get($this->collections['schoolhealth_states']);
			if (isset ( $clinic ['st_name'] )) 
			{
				$query [$clinicslist] ['st_name'] = $st_name [0] ['st_name'];
			} 
			else 
			{
				$query [$clinicslist] ['st_name'] = "No state selected";
			}
			
			// district
			$dt_name = $this->mongo_db->where('_id', new MongoId ( $clinic['dt_name']))->get($this->collections['schoolhealth_districts']);
			if (isset ( $clinic ['dt_name'] )) 
			{
				$query [$clinicslist] ['dt_name'] = $dt_name [0] ['dt_name'];
			} 
			else 
			{
				$query [$clinicslist] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
		
	}
	
	public function get_last_screening_update() {
		$query = $this->mongo_db->limit ( 1 )->orderBy ( array (
				'pie_data.date' => - 1 
		) )->select ( 'pie_data.date' )->get ( $this->screening_app_col_screening );
		
		if (isset ( $query ) && ! empty ( $query ) && (count ( $query ) > 0)) {
			return "Last update on : " . substr ( $query [0] ['pie_data'] ['date'], 0, 10 );
		} else {
			return "No updates yet.";
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch referral doctors
	 *
	 * @param  string $email Loggedin sub admin email
	 *
	 * @return array
	 */
	 
	public function get_referral_doctors_list($email)
    {
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
      $this->mongo_db->where('referred_by',$email);
	  $query = $this->mongo_db->get($this->collections['schoolhealth_referral_doctors']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Total Doctors Count
	 *
	 * @param  string $email Loggedin sub admin email
	 *
	 * @return int
	 */
	 
	public function doctors_count($email)
    {
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
      $this->mongo_db->where('referred_by',$email);
	  $count = $this->mongo_db->count($this->collections['schoolhealth_referral_doctors']);
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $count;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Delete doctor 
	 *
	 * @return bool
	 */
	 
	public function delete_referral_doctor_model($id)
	{
	   $this->mongo_db->switchDatabase($this->common_db['common_db']);
       $query = $this->mongo_db->where(array("_id"=>new MongoId($id)))->delete($this->collections['schoolhealth_referral_doctors']);
	   $this->mongo_db->switchDatabase($this->common_db['dsn']);
       return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Add specialization into the collection
	 *
	 * @return bool
	 */
	 
	public function add_specialization_model($post)
    {
    	$data = array(
    			"specialization_name" => $post['spec_name'],
    			"spec_added_by"       => $post['spec_added_by']);
    	$query = $this->mongo_db->insert($this->collections['schoolhealth_doctors_specialization'],$data);
		
    	return $query;
    }
    
	// ------------------------------------------------------------------------

	/**
	 * Helper : Delete a specified specialization from collection
	 *
	 * @return bool
	 */
	 
    public function delete_specialization($spec_id)
    {
	    $query = $this->mongo_db->where(array("_id"=>new MongoId($spec_id)))->delete($this->collections['schoolhealth_doctors_specialization']);
		return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Total Specialization Count
	 *
	 * @return int
	 */
	 
	public function specscount($email)
    {
      $count = $this->mongo_db->where('spec_added_by',$email)->count($this->collections['schoolhealth_doctors_specialization']);
	  return $count;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch specialization based on per page
	 *
	 * @return array
	 */
	 
	public function get_specialization($per_page,$page,$email)
    {

    	/*$unique_id = "HYD_500004_";
		$correct_id = "HYD_50004_";
		//====================screening collection ==============
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col );
		
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])){
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], $unique_id);
			echo print_r($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],true);
			
			if($nlg_pos !== false){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']));
				
				$new_id = $correct_id.$unique_cut;
				//echo print_r($new_id,true);
				//exit();
		$doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $new_id;
		//$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = $correct_school_name;
		//$doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = $district;
		//echo print_r($doc,true);
		//exit();
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col );
		//echo print_r($query,true);
		//echo print_r($doc,true);
		//exit();
		}
		}
		}*/

		$query = $this->mongo_db->limit($per_page)->offset($page-1)->where('spec_added_by',$email)->get($this->collections['schoolhealth_doctors_specialization']);
	  return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a counsellor document into counsellors collection.
	 *
	 * @return bool
	 */
	
	public function add_referral_doctor_model($data)
    {
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->insert($this->collections['schoolhealth_referral_doctors'],$data);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	if($query)
			return TRUE;
		else
			return FALSE;
    }

    public function update_referral_doctor_model($id, $data)
    {
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	   	$query = $this->mongo_db->where(array('_id'=> new MongoId($id)))->set($data)->update($this->collections['schoolhealth_referral_doctors']);
	   	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	if($query)
			return $query;
		else
			return FALSE;
    }
	

    public function get_referral_doctor_model($id)
    {
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	   	$query = $this->mongo_db->where('_id',new MongoId($id))->get($this->collections['schoolhealth_referral_doctors']);
	   	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	if($query)
			return $query;
		else
			return FALSE;
    }
	
	public function get_specialization_model()
	{
		$specializations = $this->mongo_db->select(array('specialization_name'))->get($this->collections['schoolhealth_doctors_specialization']);
		if($specializations)
			return $specializations;
		else
			return FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a counsellor document into counsellors collection.
	 *
	 * @return bool
	 */
	public function register_counsellor($username,$password,$email,$additional_data = array())
	{
		$manual_activation = $this->config->item('manual_activation', 'ion_auth');

		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password,$salt);

		// New user document
		$data = array(
			'username'   	=> $username,
			'password'   	=> $password,
			'email'      	=> $email,
			'registered_on' => date("Y-m-d"),
			'last_login' 	=> date("Y-m-d H:i:s"),
			'active'     	=> ($manual_activation === FALSE ? 1 : 0)
		);
		
		$data = array_merge($data,$additional_data);

		// Store salt in document?
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['diabetic_care_counsellors'], $data);

         $this->mongo_db->switchDatabase($this->common_db['dsn']);
		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;
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
	 * Generates a random salt value.
	 */
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}
	
	
    // ------------------------------------------------------------------------

	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	 
	public function change_password($identity, $old, $new)
	{
	$this->mongo_db->switchDatabase($this->common_db['common_db']);
   
	$docs = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where("email", $identity)
			->limit(1)
			->get($this->collections['schoolhealth_sub_admins']);
			
		if (count($docs) !== 1)
		{
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_schoolhealth_sub_admin_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			// Store the new password and reset the remember code so all remembered instances have to re-login
			
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			
			$updated = $this->mongo_db
				->where("email", $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['schoolhealth_sub_admins']);
				
            $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}
		
		return FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Takes a password and validates it against an entry in the collection.
	 */
	public function hash_password_schoolhealth_sub_admin_db($id, $password, $use_sha1_override = FALSE)
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
			->get($this->collections['schoolhealth_sub_admins']);
		
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
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return ($db_password == $hash_password_db->password);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Get screening data
	 *
	 * @return array
	 */
	 
	public function get_all_screenings($date = false, $screening_duration = "Yearly") 
	{
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}

		if ($screening_duration != "Yearly")
		{
			$get_year = explode(" ",$screening_duration);
			$year = $get_year[0];
		}
		else
		{
			$get_year = explode("-",$today_date);
			$year = $get_year[0];
		}
		
	
		$dates = $this->get_start_end_date($today_date,$screening_duration);
		
		// ================================================== for generated analytics
		ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( 'schoolhealth.ameya#gmail.com_pie_analytics' );
		

		$requests ['Physical Abnormalities'] = 0;
		$requests ['General Abnormalities']  = 0;
		$requests ['Eye Abnormalities']      = 0;
		$requests ['Auditory Abnormalities'] = 0;
		$requests ['Dental Abnormalities']   = 0;
		
		foreach ( $pie_data as $each_pie ) 
		{
			
			$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [0] ['value'];
			$requests ['General Abnormalities'] = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [1] ['value'];
			$requests ['Eye Abnormalities'] = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [2] ['value'];
			$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [3] ['value'];
			$requests ['Dental Abnormalities'] = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [4] ['value'];
		}
		
		$result = [ ];
		foreach ( $requests as $request => $req_value ) 
		{
			$req ['label'] = $request;
			$req ['value'] = $req_value;
			array_push ($result,$req);
		}
		
		return $result;
	}
	
	
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Get start and end date 
	 *
	 * @return array
	 */
	 
	public function get_start_end_date($today_date, $request_duration) 
	{
		if ($request_duration == "Daily") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "0 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date']   = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Weekly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Bi Weekly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-13 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Monthly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Bi Monthly")
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-2 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Quarterly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-3 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Half Yearly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
		else if ($request_duration == "2015-16 Academic Year"){
			$today_date = "2016-05-31";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ('Y-m-d H:i:s');
			$end_date = date ("Y-m-d H:i:s", strtotime ( $today_date . "-12 month"));
			$end_date = date ("Y-m-d H:i:s", strtotime ( $end_date . "1 day"));
			
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}else if ($request_duration == "2016-17 Academic Year"){
			$today_date = "2017-05-31";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ('Y-m-d H:i:s');
			$end_date = date ("Y-m-d H:i:s", strtotime ( $today_date . "-12 month"));
			$end_date = date ("Y-m-d H:i:s", strtotime ( $end_date . "1 day"));
			
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
		else if ($request_duration == "Yearly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 year" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Get all schools 
	 *
	 * @return array
	 */
	 
	public function get_all_schools() 
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->get($this->collections['schoolhealth_schools']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		foreach ($query as $schools => $school ) 
		{
			$dt_name = $this->mongo_db->where( '_id', new MongoId ( $school ['dt_name'] ) )->get($this->collections['schoolhealth_districts']);
			
			$st_name = $this->mongo_db->where( '_id', new MongoId ( $school ['st_name'] ) )->get($this->collections['schoolhealth_states']);
			
			if (isset ($school['dt_name'])) 
			{
				$query[$schools]['dt_name'] = $dt_name[0]['dt_name'];
			} 
			else 
			{
				$query[$schools]['dt_name'] = "No district selected";
			}
			
			if (isset ($school['st_name'])) 
			{
				$query[$schools]['st_name'] = $st_name[0]['st_name'];
			} 
			else 
			{
				$query[$schools]['st_name'] = "No state selected";
			}
		}
		
		log_message('debug','$query=====854444444====='.print_r($query,true));
		
		
		
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Update Analytics
	 *
	 * @return array
	 */
	 
	public function update_screening_collection($date,$screening_duration) 
	{
		if ($date) 
		{
		  $today_date = $date;
		} 
		else 
		{
		  $today_date = $this->today_date;
		}
		
		//Creating analytics For Year wise
		if ($screening_duration != "Yearly"){
			$get_year = explode(" ",$screening_duration);
			$year = $get_year[0];
		}
		else
		{
			$get_year = explode("-",$today_date);
			$year = $get_year[0];
		}
		
		$dates = $this->get_start_end_date($today_date,$screening_duration );
		
		$sub_admin = $this->session->userdata('customer');
		$email     = $sub_admin['email'];
		$email     = str_replace("@","#",$email);
		
		
		
		// ===================================stage1================================================
		
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) 
		{
			
			$query = $this->mongo_db->where(array(
					'pie_data.date' => $init_date 
			))->count($email."_analytics_".$year);
			
			log_message('debug','$dates=====891====='.print_r($query,true));
			
			$end_date = date("Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ));
			
			log_message('debug','$dates=====895====='.print_r($end_date,true));
			
			$temp_dates['today_date'] = $init_date;
			$temp_dates['end_date']   = $end_date;
			
		    if ($query == 0) 
			{
				$pie_data = array (
						"pie_data" => array (
								'date' => $init_date 
						) 
				);
				
				$stage7_requests = $this->screening_pie_data_for_stage7($temp_dates);
				$pie_data['pie_data']['stage7_pie_vales'] = $stage7_requests;
				
			//	log_message('debug','$pie_data=====655====='.print_r($pie_data,true));
				
				$requests = $this->screening_pie_data_for_stage6($stage7_requests);
				$pie_data ['pie_data'] ['stage6_pie_vales'] = $requests;
				
				log_message('debug','$pie_data=====660====='.print_r($pie_data,true));
				
				$requests = $this->screening_pie_data_for_stage5($requests);
				$pie_data ['pie_data'] ['stage5_pie_vales'] = $requests;
				
			//	log_message('debug','$pie_data=====665====='.print_r($pie_data,true));
				
				$requests = $this->screening_pie_data_for_stage4($requests);
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3($requests);
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2($requests);
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage1($requests);
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				
				log_message('debug','$pie_data=====673====='.print_r($pie_data,true));
				
				$this->mongo_db->insert($email."_analytics_".$year,$pie_data);
			}
			
			$init_date = $end_date;
		}
		
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 1
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage1($requests) 
	{
		$request_stage1 = [ ];
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests [0] ["Physical Abnormalities"] ['value'] + $requests [1] ["Physical Abnormalities"] ['value'] + $requests [2] ["Physical Abnormalities"] ['value'] + $requests [3] ["Physical Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'] + $requests [10] ["General Abnormalities"] ['value'] + $requests [11] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [12] ["Eye Abnormalities"] ['value'] + $requests [13] ["Eye Abnormalities"] ['value'] + $requests [14] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [15] ["Auditory Abnormalities"] ['value'] + $requests [16] ["Auditory Abnormalities"] ['value'] + $requests [17] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [18] ["Dental Abnormalities"] ['value'] + $requests [19] ["Dental Abnormalities"] ['value'] + $requests [20] ["Dental Abnormalities"] ['value'] + $requests [21] ["Dental Abnormalities"] ['value'] + $requests [22] ["Dental Abnormalities"] ['value'] + $requests [23] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 2
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage2($requests) 
	{
		$request_stage2 = [ ];
		
		// ===== Physical Abnormalities ==== //
		
		// Malnourished
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Malnourished"] as $doc ) 
		{
			if (isset ( $request [$doc ['label']] ))
			{
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} 
			else 
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) 
		{
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Malnourished";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		// Over Weight
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Over Weight"] as $doc ) 
		{
			if (isset ( $request [$doc ['label']] ))
			{
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} 
			else 
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) 
		{
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		
		// Under Weight
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Under Weight"] as $doc )
		{
			if (isset ( $request [$doc ['label']] )) 
			{
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} 
			else
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		
		$total_count = 0;
		foreach ( $request as $dist => $count ) 
		{
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// Obese
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Obese"] as $doc )
		{
			if (isset ( $request [$doc ['label']] )) 
			{
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} 
			else
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		
		$total_count = 0;
		foreach ( $request as $dist => $count ) 
		{
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Obese";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// ===== General Abnormalities ==== //
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["General"] as $doc ) 
		{
			if (isset ( $request [$doc ['label']] )) 
			{
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			}
			else
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Skin"] as $doc ) 
		{
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} 
			else
			{
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) 
		{
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Skin";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// ===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Others(Description/Advice)"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Others(Description/Advice)";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Ortho"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Ortho";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Postural"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Postural";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Defects at Birth"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Defects at Birth";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Deficencies"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Childhood Diseases"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Childhood Diseases";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Without Glasses"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["With Glasses"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Colour Blindness"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Colour Blindness";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Right Ear"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Right Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Left Ear"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Left Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Speech Screening";
		
		$request = [ ];
		foreach ( $requests ["Speech Screening"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Speech Screening";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Oral Hygiene - Fair"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Fair";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Oral Hygiene - Poor"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Poor";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Carious Teeth"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Carious Teeth";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Flourosis";
		
		$request = [ ];
		foreach ( $requests ["Flourosis"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Flourosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Orthodontic Treatment"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Orthodontic Treatment";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Indication for extraction"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Indication for extraction";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		
		return $request_stage2;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 3
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage3($requests) 
	{
		$request_stage3 = [ ];
		foreach ( $requests as $request => $request_data ) 
		{
			$request_stage3 [$request] = [ ];
			foreach ( $request_data as $state_name => $dist_array )
			{
				$state_data ['label'] = strtoupper ( $state_name );
				if (is_array ( $dist_array )) 
				{
					$value_count = 0;
					foreach ( $dist_array as $school_array ) 
					{
						$value_count = $value_count + $school_array ['value'];
					}
					$state_data ['value'] = $value_count;
				} 
				else 
				{
					$state_data ['value'] = count ( $dist_array );
				}
				
				array_push ( $request_stage3 [$request], $state_data );
			}
		}
		
		return $request_stage3;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 4 ( all districts )
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage4_old($requests)
	{
		$school_list    = $this->get_all_schools();
		$school_in_dist = [ ];
		
		foreach($school_list as $school ) 
		{
			$school_in_dist [strtolower ( $school ['school_name'] )] = strtolower ( $school ['st_name'] );
		}
		
		$request_stage4 = [ ];
		
		foreach ( $requests as $screening_index => $screening_array ) 
		{
			$request_stage4 [$screening_index] = [ ];
			
			foreach ( $screening_array as $school_name => $inner_data ) 
			{
				if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) 
				{
					$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = null;
				}
				
				$school_data = [ ];
				
				if (count ( $inner_data ) > 0) 
				{
					if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) 
					{
						$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = [ ];
					}
					$school_data ['label'] = strtoupper($school_name);
					$school_data ['value'] = count ($inner_data);
					array_push ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]], $school_data );
			    }
			}
		}
		return $request_stage4;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 6
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage4($requests)
	{
		$school_list    = $this->get_all_schools();
		$school_in_dist = [];
		
		log_message('debug','$school_in_dist=====1641====='.print_r($requests,true));
		
		foreach($school_list as $school ) 
		{
		   log_message('debug','$school_in_dist=====1645====='.print_r($school,true));
			$school_in_dist [strtoupper ( $school ['dt_name'] )] = strtolower ( $school ['st_name'] );
		}
		
		log_message('debug','$school_in_dist=====1649====='.print_r($school_in_dist,true));
		
		$request_stage4 = [];
		
		foreach($requests as $screening_index => $screening_array) 
		{
			$request_stage4 [$screening_index] = [ ];
			
			log_message('debug','$school_in_dist=====1657====='.print_r($school_in_dist,true));
			log_message('debug','$school_in_dist=====1658====='.print_r($request_stage4,true));
			
			foreach ( $screening_array as $index => $inner_data ) 
			{
			    log_message('debug','$school_in_dist=====1662====='.print_r($index,true));
			    log_message('debug','$school_in_dist=====1663====='.print_r($inner_data,true));
				$district_label = $inner_data['label'];
				$district_value = $inner_data['value'];
				
				$district_data = [];
				
				if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$district_label]] )) 
				{
				  $request_stage4 [$screening_index] [$school_in_dist [$district_label]] = [ ];
				}
				$district_data ['label'] = $district_label;
				$district_data ['value'] = $district_value;
				array_push ( $request_stage4 [$screening_index] [$school_in_dist [$district_label]], $district_data );
			    
			}
		}
		
		log_message('debug','request_stage4======1684===='.print_r($request_stage4,true));
		
		return $request_stage4;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 5
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage5($requests) 
	{
		$request_stage5 = [ ];
		foreach ( $requests as $request => $request_data ) 
		{
		    log_message('debug','request_stage5======1622===='.print_r($request,true));
			$request_stage5 [$request] = [ ];
			foreach ( $request_data as $dist_name => $dist_array )
			{
			    log_message('debug','request_stage5======1626===='.print_r($dist_name,true));
				$dist_data ['label'] = strtoupper ( $dist_name );
				if (is_array ( $dist_array )) 
				{
					$value_count = 0;
					foreach ( $dist_array as $school_array ) 
					{
						$value_count = $value_count + $school_array ['value'];
					}
					$dist_data ['value'] = $value_count;
				} 
				else 
				{
					$dist_data ['value'] = count ( $dist_array );
				}
				
				log_message('debug','request_stage5======1642===='.print_r($dist_data,true));
				array_push ( $request_stage5 [$request], $dist_data );
			}
		}
		
		log_message('debug','request_stage5======1647===='.print_r($request_stage5,true));
		
		return $request_stage5;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 6
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage6($requests)
	{
		$school_list    = $this->get_all_schools();
		$school_in_dist = [];
		
		foreach($school_list as $school ) 
		{
			$school_in_dist [strtolower ( $school ['school_name'] )] = strtolower ( $school ['dt_name'] );
		}
		
		$request_stage6 = [];
		
		foreach($requests as $screening_index => $screening_array) 
		{
			$request_stage6 [$screening_index] = [ ];
			
			foreach ( $screening_array as $school_name => $inner_data ) 
			{
				if (! isset ( $request_stage6 [$screening_index] [$school_in_dist [$school_name]] )) 
				{
					$request_stage6 [$screening_index] [$school_in_dist [$school_name]] = null;
				}
				
				$school_data = [];
				
				if (count ( $inner_data ) > 0) 
				{
					if (! isset ( $request_stage6 [$screening_index] [$school_in_dist [$school_name]] )) 
					{
						$request_stage6 [$screening_index] [$school_in_dist [$school_name]] = [ ];
					}
					$school_data ['label'] = strtoupper($school_name);
					$school_data ['value'] = count ($inner_data);
					array_push ( $request_stage6 [$screening_index] [$school_in_dist [$school_name]], $school_data );
			    }
			}
		}
		
		return $request_stage6;
	}
	
	public function get_drilling_screenings_schools_prepare_pie_array($query, $dist) {
		$search_result = [ ];
		$count = 0;
		log_message ( "debug", "get_drilling_screenings_schools_prepare_pie_array=====1" . print_r ( $query, true ) );
		if ($query) {
			foreach ( $query as $doc ) {
			log_message ( "debug", "get_drilling_screenings_schools_prepare_pie_array=====2" . print_r ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'], true ) );
			log_message ( "debug", "get_drilling_screenings_schools_prepare_pie_array=====3" . print_r ( $dist, true ) );
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == $dist) {
						array_push ( $search_result, $doc );
					}
				}
			}
			log_message ( "debug", "get_drilling_screenings_schools_prepare_pie_array=====4" . print_r ( $search_result, true ) );
			$request = [ ];
			foreach ( $search_result as $doc ) {
				if (isset ( $request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] )) {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] ++;
				} else {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] = 1;
				}
			}
			
			log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $request, true ) );
			$final_values = [ ];
			foreach ( $request as $school => $count ) {
				log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $school, true ) );
				log_message ( "debug", "ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc" . print_r ( $count, true ) );
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $final_values, true ) );
			
			return $final_values;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 6
	 *
	 * @return array   // STAGE6
	 */
	 
	private function screening_pie_data_for_stage6_old($dates) 
	{
		ini_set ( 'max_execution_time', 0 );
		
		$dist_list = $this->get_all_districts_model();
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		
		if ($count < 10000) 
		{
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} 
		else 
		{
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		
		
		
		$requests = [ ];
		
		// BMI ( Malnourished,Underweight,Overweight,Obese )
	   
	   // Malnourished
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$lt' => 18
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			log_message('debug','$RESULT=====1510====='.print_r($response,true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					log_message('debug','$RESULT=====1519====='.print_r($time,true));
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
					log_message('debug','$RESULT=====1520====='.print_r($dates['today_date'],true));
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		log_message('debug','$RESULT=====1528====='.print_r($result,true));
		
		foreach ( $dist_list as $dist ) 
		{
		   $request["Malnourished"] [strtolower ( $dist ['dt_name'] )] = $this->get_drilling_screenings_schools_prepare_pie_array($result, strtolower($dist['dt_name']));
		}
		
		
			
		// Underweight
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gte'=> 18,'$lte' => 25
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			log_message('debug','$RESULT=====1832====='.print_r($response['result'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					log_message('debug','$RESULT=====1842====='.print_r($dates['today_date'],true));
					log_message('debug','$RESULT=====1843====='.print_r($dates['end_date'],true));
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) 
		{
		   $request["Underweight"] [strtolower ( $dist ['dt_name'] )] = $this->get_drilling_screenings_schools_prepare_pie_array($result, strtolower($dist['dt_name']));
		}
		
		
		// Overweight
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gte'=> 25,'$lte' => 30
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			log_message('debug','$RESULT=====1894====='.print_r($response['result'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) 
		{
		   $request["Overweight"] [strtolower ( $dist ['dt_name'] )] = $this->get_drilling_screenings_schools_prepare_pie_array($result, strtolower($dist['dt_name']));
		}
		
		
		// Obese
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt' => 30
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) 
		{
		   $request["Obese"] [strtolower ( $dist ['dt_name'] )] = $this->get_drilling_screenings_schools_prepare_pie_array($result, strtolower($dist['dt_name']));
		}
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["General"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$and_merged_array = array ();
		
		$merged_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Skin"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Ortho"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Postural"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ['Defects at Birth'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ['Deficencies'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Childhood Diseases'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"" 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Without Glasses'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['With Glasses'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Colour Blindness'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_right );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Right Ear'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								" ",
								"" 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Left Ear'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Speech Screening'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Oral Hygiene - Fair"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Oral Hygiene - Poor"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								" ",
								"" 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Carious Teeth"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Flourosis"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Orthodontic Treatment"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Indication for extraction"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		
		return $request;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Generate Analytics for Stage 7
	 *
	 * @return array  // STAGE7
	 */
	 
	private function screening_pie_data_for_stage7($dates) 
	{
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '10G' );
		
		$school_list = $this->get_all_schools();
		
		$count = $this->mongo_db->count ($this->screening_app_col);
		
		if ($count < 5000) 
		{
			$per_page = $count;
			$loop     = 2; 
		} 
		else
		{
			$per_page = 5000;
			$loop     = $count / $per_page;
		}
		
		$requests = [];
		
		
		// BMI ( Malnourished,Underweight,Overweight,Obese )
	   
	   // Malnourished
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$lt' => 18
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			//log_message('debug','$RESULT=====1510====='.print_r($response,true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date['time'];
					//log_message('debug','$RESULT=====1519====='.print_r($time,true));
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
					//log_message('debug','$RESULT=====1520====='.print_r($dates['today_date'],true));
						array_push($temp_result,$doc);
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
	//	log_message('debug','$RESULT=====1528====='.print_r($result,true));
		
		foreach ( $school_list as $school_name ) 
		{
		   $request["Malnourished"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array($result, strtolower($school_name['school_name']));
		}
		
		
			
		// Underweight
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt'=> 18,'$lte' => 25
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			//log_message('debug','$RESULT=====1832====='.print_r($response['result'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					//log_message('debug','$RESULT=====1842====='.print_r($dates['today_date'],true));
					//log_message('debug','$RESULT=====1843====='.print_r($dates['end_date'],true));
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) 
		{
		   $request["Under Weight"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array($result, strtolower($school_name['school_name']));
		}
		
		
		
		// Overweight
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt'=> 25,'$lte' => 30
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			log_message('debug','$RESULT=====1894====='.print_r($response['result'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) 
		{
		   $request["Over Weight"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array($result, strtolower($school_name['school_name']));
		}
		
		
		// Obese
		$merged_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt' => 30
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) 
		{
		   $request["Obese"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array($result, strtolower($school_name['school_name']));
		}
		
		
		
		
		/* // ---------------------------------------------------------------------------------------- //
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				) 
		);
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) 
		{
		   $request ["Over Weight"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array($result, strtolower($school_name['school_name']));
		}
		
		// ==========================================================================================
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Under Weight"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		} */
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $school_list as $school_name ) {
			$request ["General"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$and_merged_array = array ();
		
		$merged_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $school_list as $school_name ) {
			$request ["Skin"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		//===============================================================================
		
				//==============================================================================
		$and_merged_array = array ();
		
		$description_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ''
				)
		);
		$description_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ' '
				)
		);
		
		$advice_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ''
				)
		);
		$advice_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ' '
				)
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true
				)
		);
		
		array_push ( $and_merged_array, $description_str_empty );
		array_push ( $and_merged_array, $description_str_space );
		array_push ( $and_merged_array, $advice_str_empty );
		array_push ( $and_merged_array, $advice_str_space );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [
			array (
					'$match' => array (
							'$and' => $and_merged_array
					)
			),
			array (
					'$project' => array (
							"doc_data.widget_data" => true,
							"history" => true
					)
			),
			array (
					'$limit' => $offset
			),
			array (
					'$skip' => $offset - $per_page
			)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
		
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
						
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
				
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $school_list as $school_name ) {
			$request ["Others(Description/Advice)"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Ortho"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Postural"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Defects at Birth'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			log_message('debug','rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($response,true));
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Deficencies'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Childhood Diseases'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Without Glasses'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['With Glasses'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Colour Blindness'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_right );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Right Ear'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Left Ear'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ['Speech Screening'] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Oral Hygiene - Fair"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Oral Hygiene - Poor"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Carious Teeth"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Flourosis"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) {
			$request ["Orthodontic Treatment"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ($or_merged_array,$indication );
		array_push ($and_merged_array,$page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $school_list as $school_name ) 
		{
			$request ["Indication for extraction"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	
	private function screening_pie_data_for_stage5_old($dates) 
	{
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '10G' );
		
		$request = array();
		
        $count = $this->mongo_db->count ( $this->screening_app_col );
		
		if ($count < 5000) 
		{
			$per_page = $count;
			$loop     = 2; 
		} 
		else 
		{
			$per_page = 5000;
			$loop     = $count / $per_page;
		}
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			log_message('debug','OVERWEIGHT=====502====='.print_r($response['result'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date['time'];
					
					log_message('debug','OVERWEIGHT=====512====='.print_r($date,true));
					log_message('debug','OVERWEIGHT=====513====='.print_r($dates,true));
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date']))
					{
				        array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			log_message('debug','OVERWEIGHT=====523====='.print_r($result,true));
			
			$result = array_merge ( $result, $temp_result );
		}
		
		log_message('debug','OVERWEIGHT=====519====='.print_r($result,true));
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  log_message('debug','OVERWEIGHT=====527====='.print_r($doc,true));
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Over Weight"]  = $search_result;
		
		
		// ==========================================================================================
		
	    $merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Under Weight"]  = $search_result;
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["General"] = $search_result;
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$merged_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Skin"] = $search_result;
		
		//===============================================================================
		
		$and_merged_array = array ();
		
		$description_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ''
				)
		);
		$description_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ' '
				)
		);
		
		$advice_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ''
				)
		);
		$advice_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ' '
				)
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true
				)
		);
		
		array_push ( $and_merged_array, $description_str_empty );
		array_push ( $and_merged_array, $description_str_space );
		array_push ( $and_merged_array, $advice_str_empty );
		array_push ( $and_merged_array, $advice_str_space );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [
			array (
					'$match' => array (
							'$and' => $and_merged_array
					)
			),
			array (
					'$project' => array (
							"doc_data.widget_data" => true,
							"history" => true
					)
			),
			array (
					'$limit' => $offset
			),
			array (
					'$skip' => $offset - $per_page
			)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline
			) );
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
		
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date ['time'];
						
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) 
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
				
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Others(Description/Advice)"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Ortho"] = $search_result;
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc )
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date']))
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Postural"] = $search_result;
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) 
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Defects at Birth"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Deficencies"] = $search_result;
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Childhood Diseases"] = $search_result;
	
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++)
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc )
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date']))
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Without Glasses"] = $search_result;
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ($result,$temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["With Glasses"] = $search_result;
		
		// ===================================================================================
		
	    $and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) 
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Colour Blindness"] = $search_result;
		
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_right );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Right Ear"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Left Ear"] = $search_result;
		
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc )
			{
				foreach ( $doc ['history'] as $date )
				{
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) 
					{
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Speech Screening"] = $search_result;
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Oral Hygiene - Fair"] = $search_result;
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Oral Hygiene - Poor"] = $search_result;
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Carious Teeth"] = $search_result;
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Flourosis"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Orthodontic Treatment"] = $search_result;
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Indication for extraction"] = $search_result;
		
		return $request;
	}
	
	public function get_drilling_screenings_students_prepare_pie_array($query, $school_name) 
	{
		$search_result = [ ];
		$count = 0;
		log_message('debug','PIEDATA=====5155====='.print_r($query));
		if ($query) 
		{
			foreach ( $query as $doc ) 
			{
			    log_message('debug','PIEDATA=====5159====='.print_r($doc));
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] )) 
				{
			        log_message('debug','PIEDATA=====5161====='.print_r(strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] )));
			        log_message('debug','PIEDATA=====5162====='.print_r($school_name));
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == $school_name) 
					{
						array_push ( $search_result, $doc ['_id']->{'$id'} );
					}
				}
			}
			
			return $search_result;
		}
	}
	
	public function get_drilling_screenings_abnormalities($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		switch ($type) {
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Malnourished';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [2] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Obese';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [3] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "General Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'General';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					 log_message("debug","pppppppppppppppppppppppppppppppp=====7371==".print_r($each_pie,true));
					 log_message("debug","pppppppppppppppppppppppppppppppp=====7372==".print_r($each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities']['value'],true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities']['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [11] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [14] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [15] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [16] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [17] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene - Fair';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [18] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [19] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [20] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [21] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [22] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [23] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}
	}
	
	public function get_drilling_screenings_states($data, $date = false, $screening_duration = "Yearly") {
	    $type = "";
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage3_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		switch ($type) {
		
		    case "Malnourished" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Malnourished"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
				   
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
						
						
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
				
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Over Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Under Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Obese"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["General"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Skin"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Ortho"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Postural"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Defects at Birth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Deficencies"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Childhood Diseases"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Without Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["With Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Colour Blindness"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Right Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Left Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Speech Screening"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Oral Hygiene - Fair"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Oral Hygiene - Poor"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Carious Teeth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Flourosis"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Orthodontic Treatment"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Indication for extraction"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					$result ['type']  = $type;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_districts($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		log_message('debug','screening_report=====drilling_screening_to_districts====8163'.print_r($obj_data,true));
		$type = $obj_data ['type'];
		switch ($type) {
		
		    case "Malnourished" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Malnourished"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
				
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				log_message('debug','screening_report=====drilling_screening_to_districts====8756'.print_r($request,true));
				foreach ( $request as $dist => $count ) {
				log_message('debug','screening_report=====drilling_screening_to_districts====8758'.print_r($dist,true));
				log_message('debug','screening_report=====drilling_screening_to_districts====8759'.print_r($count,true));
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				log_message('debug','screening_report=====drilling_screening_to_districts====8762'.print_r($final_values,true));
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_schools($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage6_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$dist = strtolower ( $obj_data ['1'] );
		log_message("error","distttttttttttttttttt".print_r($dist,true));
		switch ($type) {
		
		    case "Malnourished" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Malnourished"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Malnourished"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Over Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Over Weight"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Under Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Under Weight"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Obese"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Obese"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["General"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["General"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Skin"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Skin"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Ortho"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Ortho"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Postural"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Postural"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Deficencies"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Deficencies"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["With Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["With Glasses"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Right Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Right Ear"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("error","each_pie==============9306".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Left Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Left Ear"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Flourosis"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Flourosis"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage6_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage6_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_students($data, $date = false, $screening_duration = "Yearly") {
		ini_set ( 'memory_limit', '1G' );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage7_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		log_message ( "debug", "obbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbjjjjjjjjjjjjj" . print_r ( $obj_data, true ) );
		switch ($type) {
		    
			case "Malnourished" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie )
				{
					
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Malnourished"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Malnourished"] [strtolower ( $school_name )] )) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Malnourished"] [strtolower ( $school_name )] );
					}
				}
				
				return $requests;
				break;
				
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie )
				{
					
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] )) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] );
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie )
				{
					
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Obese"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Obese"] [strtolower ( $school_name )] )) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Obese"] [strtolower ( $school_name )] );
					}
				}
				
				return $requests;
				break;
				
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["General"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["General"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["General"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Skin"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Skin"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Skin"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Ortho"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Ortho"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Ortho"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Postural"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Postural"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Postural"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] );
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Left Ear"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] );
				}
			}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
				    if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Flourosis"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if(isset($each_pie ['pie_data'] ['stage7_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )])){
					if ($each_pie ['pie_data'] ['stage7_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage7_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage7_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] );
				}
			    }
				
				return $requests;
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_students_docs($_id_array) {
		$docs = [ ];
		
		foreach ( (array)$_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			
			if (isset ( $query [0] ))
				array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	public function drill_down_screening_to_students_load_ehr_doc($_id) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
		if ($query) {
			//$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			$result ['request']   = "";
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			return $result;
		}
	}
	
	/* public function get_reports_ehr_uid($uid) 
	{
		
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data',
					'doc_data.chart_data',
					'doc_data.external_attachments',
					'history' 
			) )->where(array("doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=> $uid ))->get ( $this->screening_app_col );
			$result ['screening'] = $query;
			//$result ['request'] = $query_request;
			return $result;
		
	} */
	
	//Uploading zipfiles
	//author Naresh
	public function insert_screening_details($data_user){
		
		//$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		
		$query = $this->mongo_db->insert($this->collections['healthcare20161014212024617'],$data_user);
		
		//$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		log_message('debug','insert_screening_details'.print_r($query,TRUE));
		
		//return $query;
		
	 log_message('debug',"data_history======".print_r($query,true));
		if($query)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
   }
   
  function unique_id_check($unique_id_check_zipfile){
		
		$and_merged_array = array ();
		
		$unique_id = array (
				"doc_data.widget_data.page1.Personal Information.Hospital Unique ID" => $unique_id_check_zipfile
				);
				
		$page3_exists = array (
				"doc_data.widget_data.page3.Physical Exam" => array (
						'$exists' => true 
				) 
		);
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up" => array (
						'$exists' => true 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up" => array (
						'$exists' => true 
				) 
		);
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $unique_id );
		array_push ( $and_merged_array, $page3_exists );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array
							) 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			//echo print_r($response,true);
			//exit();
			
			$query = 0;
			if($response['ok']){
				//$query = count($response["result"]);
				if(count($response["result"]) == 0 ){
					
					$and_merged_array = array ();
					
					$page1_exists = array (
							"doc_data.widget_data.page1.Personal Information" => array (
									'$exists' => true 
							) 
					);
					$page2_exists = array (
							"doc_data.widget_data.page2.Personal Information" => array (
									'$exists' => true 
							) 
					);
					
					array_push ( $and_merged_array, $unique_id );
					array_push ( $and_merged_array, $page1_exists );
					array_push ( $and_merged_array, $page2_exists );
					
					$result = [ ];
						$pipeline = [ 
								array (
										'$project' => array (
												"doc_data.widget_data" => true,
												"history" => true 
										) 
								),
								array (
										'$match' => array (
												'$and' => $and_merged_array
										) 
								) 
						];
						$response = $this->mongo_db->command ( array (
								'aggregate' => $this->screening_app_col,
								'pipeline' => $pipeline 
						) );
						
						$query = 0;
						if($response['ok']){
							//$query = count($response["result"]);
							if(count($response["result"]) == 0 ){
								$and_merged_array = array ();
								
								array_push ( $and_merged_array, $unique_id );
								
								$result = [ ];
									$pipeline = [ 
											array (
													'$project' => array (
															"doc_data.widget_data" => true,
															"history" => true 
													) 
											),
											array (
													'$match' => array (
															'$and' => $and_merged_array
													) 
											) 
									];
									$response = $this->mongo_db->command ( array (
											'aggregate' => $this->screening_app_col,
											'pipeline' => $pipeline 
									) );
									
									$query = 0;
									if($response['ok']){
										//$query = count($response["result"]);
										if(count($response["result"]) == 0 ){
											return "No document found";
											//exit();
										}else{
											return "Only unique id document";
											
										}
									}
							}
							else
							{
								return "Only personal info document";
								
							}
						}
				}else{
					return "Full document";
					
				}
			}
		
	}
	
	public function update_screening_details($_id,$data_user)
	{
		//echo $_id;
		//exit();
		
		$this->mongo_db->where(array('_id' => new MongoID($_id)))->set($data_user)->update($this->collections['healthcare20161014212024617']);
		
	   /* //$this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $query = $this->mongo_db->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $data_user['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']))->set($data_user)->update($this->collections['healthcare20161014212024617']);
	   //$this->mongo_db->switchDatabase($this->common_db['dsn']); */
		 
		
	}
	  
}    
 
