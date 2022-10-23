 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Schoolhealth_student_app_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
		$this->load->helper('paas');
        
		$this->lang->load('ion_auth');
		
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
		
        // Initialize MongoDB collection names
        $this->collections  = $this->config->item('collections', 'ion_auth');
        $this->common_db    = $this->config->item('default');
		
		$this->screening_app_col           = "healthcare20161014212024617";
		$this->screening_app_col_sample    = "healthcare20161014212024617_sample";
		$this->screening_app_col_screening = "healthcare20161014212024617_screening_final";
		
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Fetching EHR of a student ( Showing detailed EHR view )
	 *
	 * @param  string  unique_id  Student's Hospital Unique ID
	 *
	 * @author Selva
	 */
	 
	public function fetch_student_ehr_doc_model($unique_id) 
	{
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
                'doc_data.external_attachments',
				'history',
				'doc_properties.doc_id'
		) )->where ('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get ( $this->screening_app_col );
		log_message("debug","documentssssssssssssssss======55".print_r($query,true));
		if ($query) 
		{
			//$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			$result ['request']   = [];
			return $result;
		} 
		else 
		{
			$result ['screening'] = false;
			$result ['request']   = false;
			return $result;
		}
	}
	
	public function fetch_students_ehr_docs()
	{
	  $query = $this->mongo_db->select ( array (
				'doc_data.widget_data'
		))->get ( $this->screening_app_col );
	  return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Change password
	 *
	 * @param  string  $patient_id  Patient ID
	 * @param  string  $old_pwd     Old password
	 * @param  string  $new_pwd     New password
	 *
	 * @return bool
	 */
	 
	function change_password($unique_id,$old_pwd,$new_pwd)
	{
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
		
		$docs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where('hospital_unique_id', $unique_id)
			->limit(1)
			->get($this->collections['schoolhealth_students']);
			
		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		if (count($docs) !== 1)
		{
			return FALSE;
		}
		
		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_schoolhealth_student_db($result->_id, $old_pwd);
		$new         = $this->hash_password($new_pwd, $result->salt);
		
		if ($old === TRUE)
		{
		   // Store the new password and reset the remember code so all remembered instances have to re-login
		   
		   $this->mongo_db->switchDatabase($this->common_db['common_db']);
		   
		   $updated = $this->mongo_db
			 ->where('hospital_unique_id', $unique_id)
			 ->set(array('password' => $new, 'remember_code' => NULL))
			 ->update($this->collections['schoolhealth_students']);
			 
		   $this->mongo_db->switchDatabase($this->common_db['dsn']);

			if (!$updated)
			{
				return FALSE;
			}

		   return $updated;
		}

	   return FALSE;
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
	
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);
		
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['schoolhealth_students']);
		
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
   
     public function calculate_bmi_model($unique_id,$bmi)
	 {
	   $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set(array('doc_data.widget_data.page3.Physical Exam.BMI%'=>$bmi))->update($this->screening_app_col_sample);
	 
	 }
	
	 public function add_students_data_to_login_collection_model($page1,$page2)
	 {
	   $name = $page1['Personal Information']['Name'];
	   $pass = 12345678;
	   $salt = substr(md5(uniqid(rand(), true)), 0, 10);
	   $password   = $salt . substr(sha1($salt . $pass), 0, -10);
	   
	   log_message('debug','$add_students_data_to_login_collection====3=='.print_r($page1,true));
	   log_message('debug','$add_students_data_to_login_collection====4=='.print_r($page2,true));
	   
	   $data = array(
	   'name'          => $page1['Personal Information']['Name'],
	   'hospital_unique_id' => $page1['Personal Information']['Hospital Unique ID'],
	   'school_name'   => $page2['Personal Information']['School Name'],
	   'mobile'        => $page1['Personal Information']['Mobile'],
	   'active'        => 1,
	   'company_name'  => "healthcare",
	   'last_login'    => date('Y-m-d H:i:s'),
	   'plan_expiry'   => "2017-05-30",
	   'password'      => $password,
	   'registered_on' => "2015-05-30",
       'salt'          => null);
	   
	   $this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $this->mongo_db->insert($this->collections['schoolhealth_students'],$data);
	   $this->mongo_db->switchDatabase($this->common_db['dsn']);
	 
	 }
	 // --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Upload attachments to existing EHR
	 *
	 * @param string $unique_id        Student Hospital Unique ID
	 * @param array  $external_final   Attached Files Data 
	 *
	 *
	 * @output bool
	 *
	 * @author Selva
	 */
	 
	 public function upload_attachments_to_ehr_model($unique_id,$external_final)
	 {
	   $external_array = array();
	   
	   $doc = $this->mongo_db->where(array("doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=>$unique_id))->select(array('doc_data.external_attachments'),array())->get($this->screening_app_col);
	   
	   if(isset($doc[0]['doc_data']['external_attachments']))
	   {
			$external_merged_data = array_merge($doc[0]['doc_data']['external_attachments'],$external_final);
			$external_array = array_replace_recursive($doc[0]['doc_data']['external_attachments'],$external_merged_data);
	   }
	   else
	   {
			$external_array = array_merge($external_array,$external_final);
	   } 
		
	   
	   $query  = array("doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=>$unique_id); 
	   $update = array('$set'=>array("doc_data.external_attachments"=>$external_array));
		 
	   $response = $this->mongo_db->command(array( 
		'findAndModify' => $this->screening_app_col,
		'query'         => $query,
		'update'        => $update
	    ));
		
	    return $response['ok'];
	 
	 }
	 
	 public function get_news()
	 {
		$news = $this->mongo_db->get('school_health_news');
		if($news)
		{
		 return $news;
		}
		else
		{
		 return false;
		}
	 }
	
	
}
