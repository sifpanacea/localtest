 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_school_portal_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
		$this->load->library('excel');
        
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
		//$this->screening_app_col = 'maharashtra_screening_sync_app_collection';
		//$this->screening_col_2019_2020 = 'maharashtra_screening_sync_app_collection';
		//$this->screening_col_2019_2020 = 'healthcare20161014212024617';
		//$this->screening_app_col_2019_2020 = 'school_health_screening_2019_2020';
		$this->screening_app_col_screening_2018_2019 = $email."_screening_pie_analytics_18_19";
		$this->screening_app_col_screening = $email."_screening_pie_analytics";
		$this->today_date = date ( 'Y-m-d' );
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
	 * Takes a password and validates it against an entry in the collection.
	 */
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
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
			->get($this->collections['schoolhealth_schools']);
		
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
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Get classes count
	 *
	 *
	 * @author Selva
	 */
	 
	public function classescount($school_code) 
	{
		$count = $this->mongo_db->where('school_code', $school_code)->count ($this->collections['schoolhealth_classes']);
		return $count;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Get Classes 
	 *
	 *
	 * @author Selva
	 */
	
	public function get_classes($per_page, $page, $school_code) 
	{
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->where ( 'school_code', $school_code )->get($this->collections['schoolhealth_classes']);
		return $query;
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Get sections count
	 *
	 *
	 * @author Selva
	 */
	 
	public function sectionscount($school_code) 
	{
		$count = $this->mongo_db->where('school_code', $school_code)->count ($this->collections['schoolhealth_sections']);
		return $count;
	}
	      
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Get Sections 
	 *
	 *
	 * @author Selva
	 */
	 
	public function get_sections($per_page, $page, $school_code) 
	{
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit($per_page)->offset($offset)->where('school_code',$school_code)->get($this->collections['schoolhealth_sections']);
		return $query;
	}
	
	public function get_all_classes($cls_name = "All",$school_code) 
	{
		if ($cls_name == "All") 
		{
			$query = $this->mongo_db->orderBy ( array (
					'class_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['schoolhealth_classes']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('class_name', $cls_name )->orderBy ( array (
					'class_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['schoolhealth_classes']);
		}
		
		return $query;
	}
	
	public function get_all_sections($section_name = "All",$school_code) 
	{
		if ($section_name == "All") 
		{
			$query = $this->mongo_db->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['schoolhealth_sections']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('section_name', $section_name )->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['schoolhealth_sections']);
		}
		
		return $query;
	}
	public function get_student($per_page,$page,$school_name)
	{
		$offset = $per_page * ($page - 1);

		$query = $this->mongo_db->limit ($per_page)->offset( $offset)->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->get($this->collections['healthcare20161014212024617']); 
	//$query = $this->mongo_db->get($this->collections['schoolhealth_school_import_students']); 
		
		return $query;
	}
	
	public function get_students_by_class($class, $school_name)
	{
		 if($class == "All")
		{
			$query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->get($this->collections['healthcare20161014212024617']);
			return $query;
		}
		else
		{ 
		   $query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name,"doc_data.widget_data.page2.Personal Information.Class"=>$class))->get($this->collections['healthcare20161014212024617']); 
		   return $query;
		   
		   log_message('debug','particulat school data==========247'.print_r($query, true));
		 } 
	}
	
	public function studentscount($school_name) 
	{
		//$count = $this->mongo_db->count($this->collections['schoolhealth_school_import_students']);
		$count = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->count($this->collections['healthcare20161014212024617']);
		return $count;
	}
	
	public function get_staff($per_page, $page) 
	{
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ($per_page)->offset( $offset)->get ($this->collections['schoolhealth_staffs']);
		return $query;
	}
	
	public function staffscount()
	{
		$count = $this->mongo_db->count($this->collections['schoolhealth_staffs']);
		return $count;
	}
	
	public function create_class($post, $school_code)
    {
    	$data = array(
    			"class_name" => $post['class_name'],
				"school_code" => $school_code
				);
    	$query = $this->mongo_db->insert($this->collections['schoolhealth_classes'],$data);
    	return $query;
    }
    
    public function delete_class($class_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($class_id)))->delete($this->collections['schoolhealth_classes']);
		
		log_message('debug','delete_class========268=====>'.print_r($query,	true));
		
    	return $query;
    }
    
    public function create_section($post, $school_code)
    {
    	$data = array(
    			"section_name" => $post['section_name'],
				"school_code"  => $school_code
				);
    	$query = $this->mongo_db->insert($this->collections['schoolhealth_sections'],$data);
    	return $query;
    }
    
    public function delete_section($section_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($section_id)))->delete($this->collections['schoolhealth_sections']);
    	return $query;
    }
	
	public function create_staff($post)
    {
		$dir = "uploads/";
		$temp = explode(".",$_FILES["image"]["name"]);
		log_message("debug",'$temp======79'.print_r($temp,true));
		//log_message
		$newfilename = $this->input->post('staff_code') . '.' . end($temp);
		log_message("debug",'$newfilename======82'.print_r($newfilename,true));
		$file = $dir . $newfilename;
		$filename=$_FILES['image']['tmp_name'];
		if(!empty($newfilename))
		{
			if(move_uploaded_file($filename,$file))
			{
				echo "fileuploaded successfully";
			}
		}   
		
    	$data = array(
    			"staff_code"  => $post['staff_code'],
    			"staff_name"  => $post['staff_name'],
    			"staff_email" => $post['staff_email'],
    			"staff_mob"   => $post['staff_mob'],
    			"staff_addr"  => $post['staff_addr'],
				"staff_dob"   => $post['staff_dob'],
				"staff_photo" => $file,
    			"staff_qualification" => $post['staff_qualification']);
    	$query = $this->mongo_db->insert($this->collections['schoolhealth_staffs'],$data);
    	return $query;
    }
    
    public function delete_staff($staff_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($staff_id)))->delete($this->collections['schoolhealth_staffs']);
    	return $query;
    }
	/*Naresh ====*/
	
	public function get_students_uid($unique_id)
	{
		 $query = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $unique_id )->get ($this->screening_app_col );
		if ($query) {
			
			log_message('debug','get_students_uid==model====349==='.print_r($query,true));
			return $query [0];
			
		} else {
			return false;
		}
	}
	/*Naresh=====*/
	
	public function update_student_data($doc,$doc_id)
	 {
		$query = $this->mongo_db->where ( "_id", $doc_id )->set ( $doc )->update ( $this->screening_app_col  );
		// $query = $this->mongo_db->where("_id", $doc_id)->set($doc)->update($this->screening_app_col);
		
		return $query;
	}
	
	public function generate_new_hunique_id($school_code, $dist_code, $school_name)
	{
	  $uniqueidlist = array();
	
	  $all_uniqueID = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->get($this->screening_app_col);
		
	  if(!empty($all_uniqueID))
	  {
		$id_array = array();
		foreach ($all_uniqueID as $uID)
		{
			
		    $id = $uID['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
			$id_array = explode("_",$id);
			if(isset($id_array[2]))
			{
			   array_push($uniqueidlist,$id_array[2]);
			}
		}
		
		$maxID     = max($uniqueidlist);
		$uid       = intval($maxID);
		$inc       = $uid+1;
		if(isset($id_array[1]))
		{
		  $unique_id = $id_array[0]."_".$id_array[1]."_".$inc;
		}
		else
		{
		  $unique_id = "O".$dist_code."_".$school_code."_".$inc;
		}
	 }
	 else
	 {
		$unique_id = "O".$dist_code."_".$school_code."_1000";
	 }
	
	 return $unique_id;
	
	}
	
	public function add_student_ehr_model($doc_data,$history)
	{
	  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->insert($this->screening_app_col,$doc_data);
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}
	
	public function get_reports_ehr_uid($uid,$schoolname=false) 
	{
/* 		if($schoolname)
		{
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data',
					'doc_data.chart_data',
					'doc_data.external_attachments',
					'history' 
			) )->where(array( "doc_data.widget_data.page2.Personal Information.School Name"=>$schoolname,"doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=> $uid ))->get ( $this->screening_app_col );
			$result ['screening'] = $query;
			//$result ['request'] = $query_request;
			return $result;
		}
		else
		{
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data',
					'doc_data.chart_data',
					'doc_data.external_attachments',
					'history' 
			) )->whereLike( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
			$result ['screening'] = false;
			//$result ['request']   = false;
			return $result;
		} */
		$query = $this->mongo_db->select ( array (
					'doc_data.widget_data',
					'doc_data.chart_data',
					'doc_data.external_attachments',
					'history' 
			) )->where(array( "doc_data.widget_data.page2.Personal Information.School Name"=>$schoolname,"doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=> $uid ))->get ( $this->screening_app_col );
			$result ['screening'] = $query;
			//$result ['request'] = $query_request;
			return $result;
	}
	
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

		$dates = $this->get_start_end_date($today_date,$screening_duration);
		// ================================================== for generated analytics
		ini_set ( 'memory_limit', '10G' );
		//log_message('debug','SCHOOLHEALTH_SCHOOL_PORTAL_MODEL=====GET_ALL_SCREENINGS=====$this->screening_app_col_screening=====>'.print_r($this->screening_app_col_screening,true));
		if($screening_duration == "2017_2018")
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		}else
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening_2018_2019 );
		}
		
		
		$requests ['Physical Abnormalities'] = 0;
		$requests ['General Abnormalities']  = 0;
		$requests ['Eye Abnormalities']      = 0;
		$requests ['Auditory Abnormalities'] = 0;
		$requests ['Dental Abnormalities']   = 0;
		$requests ['Skin Conditions']  		 = 0;
		
		foreach ( $pie_data as $each_pie ) {
			
			$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [0] ['value'];
			$requests ['General Abnormalities'] = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [1] ['value'];
			$requests ['Eye Abnormalities'] = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [2] ['value'];
			$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [3] ['value'];
			$requests ['Dental Abnormalities'] = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [4] ['value'];
			$requests ['Skin Conditions']   =    $requests ['Skin Conditions'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [5] ['value'];
		}
		
		$result = [ ];
		foreach ( $requests as $request => $req_value ) {
			$req ['label'] = $request;
			$req ['value'] = $req_value;
			array_push ( $result, $req );
		}
		return $result;
	}
	
	public function get_start_end_date($today_date, $request_duration) 
	{
		if ($request_duration == "Daily") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "0 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Weekly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Bi Weekly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-13 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Monthly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Bi Monthly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-2 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Quarterly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-3 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Half Yearly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Yearly") {
			$date = new DateTime ( $today_date );
			
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 year" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			/*$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;*/
			$dates ['today_date'] = "2018-10-11 00:00:00";
			$dates ['end_date'] = "2017-01-11 00:00:00";
			
			return $dates;
		}else if ($request_duration == "2017_2018"){
			$today_date = "2018-05-31";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ('Y-m-d H:i:s');
			$end_date = date ("Y-m-d H:i:s", strtotime ( $today_date . "-12 month"));
			$end_date = date ("Y-m-d H:i:s", strtotime ( $end_date . "1 day"));
			
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			
			return $dates;
		}else if ($request_duration == "2018_2019"){
			$today_date = "2019-12-31";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ('Y-m-d H:i:s');
			$end_date = date ("Y-m-d H:i:s", strtotime ( $today_date . "-12 month"));
			$end_date = date ("Y-m-d H:i:s", strtotime ( $end_date . "1 day"));
			
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			
			return $dates;
		}
	}
	
	public function update_screening_collection($date, $screening_duration, $school_name) 
	{
		if ($date) 
		{
		  $today_date = $date;
		} 
		else 
		{
		  $today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date($today_date,$screening_duration );
		
		// ===================================stage1================================================
		
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) 
		{
			//log_message('debug','$this->screening_pie_data_for_stage5====3=='.print_r($init_date,true));
			
			$query = $this->mongo_db->where ( array (
					'pie_data.date' => $init_date 
			) )->count( $this->screening_app_col_screening_2018_2019 );
			
			$end_date = date("Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ));
			
			$temp_dates['today_date'] = $init_date;
			$temp_dates['end_date']   = $end_date;
			
			//log_message('debug','$this->screening_pie_data_for_stage5====4=='.print_r($query,true));
			
			if ($query == 0) 
			{
				$pie_data = array (
						"pie_data" => array (
								'date' => $init_date 
						) 
				);
				//log_message('debug','$this->screening_pie_data_for_stage5====school_name=====>'.print_r($school_name,true));
				$requests = $this->screening_pie_data_for_stage5_new($temp_dates, $school_name);
				//log_message('debug','$this->screening_pie_data_for_stage5===='.print_r($requests,true));
				$pie_data['pie_data']['stage5_pie_vales'] = $requests;
				
				/*$requests = $this->screening_pie_data_for_stage4_new ( $requests );
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3_new ( $requests );
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;*/
				
				$requests = $this->screening_pie_data_for_stage2_new_for_recent ( $requests );
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				//log_message ( "debug", "before stagesssssssssssssssssssssssssss--------------------".print_r($pie_data,true) );
				$requests = $this->screening_pie_data_for_stage1_new_for_recent ( $requests );
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				//log_message ( "debug", "before stage11111111111111111111--------------------".print_r($pie_data,true) );
				
				$this->mongo_db->insert( $this->screening_app_col_screening_2018_2019,$pie_data );
			}
			
			$init_date = $end_date;
		}
		
	}
	
	public function screening_pie_data_for_stage2_new($requests) {
		$request_stage2 = [ ];
		
		log_message('debug','screening_pie_data_for_stage2_new=====453=='.print_r($requests,true));
		
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests["Over Weight"] as $doc ) 
		{
		  $total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Under Weight"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Obese"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Obese";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["General"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Skin"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Skin";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// ===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Others(Description/Advice)"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Others(Description/Advice)";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Ortho"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Ortho";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Postural"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Postural";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Defects at Birth"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Defects at Birth";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		 $stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Deficencies"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array ); 
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Childhood Diseases"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Childhood Diseases";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Without Glasses"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["With Glasses"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Colour Blindness"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Colour Blindness";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Right Ear"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Right Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Left Ear"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Left Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Speech Screening";
		
		$total_count = 0;
		foreach ( $requests ["Speech Screening"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Speech Screening";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Fair"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Fair";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Poor"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Poor";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Carious Teeth"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Carious Teeth";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Flourosis";
		
		$total_count = 0;
		foreach ( $requests ["Flourosis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Flourosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Orthodontic Treatment"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Orthodontic Treatment";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Indication for extraction"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Indication for extraction";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Acne on Face"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Acne on Face";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hyper Pigmentation"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hyper Pigmentation";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Greying Hair"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Greying Hair";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Danddruff"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Danddruff";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Facialis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Facialis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["White Patches on Face"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "White Patches on Face";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Corporis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Corporis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Allergic Rash"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Allergic Rash";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Scabies"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Scabies";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hyperhidrosis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hyperhidrosis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Psoriasis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Psoriasis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Nail Bed Disease"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Nail Bed Disease";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hypo Pigmentation"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hypo Pigmentation";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hansens Disease"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hansens Disease";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Cruris"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Cruris";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Cracked Feet"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Cracked Feet";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Molluscum"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Molluscum";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["ECCEMA"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "ECCEMA";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		return $request_stage2;
	}
	
	private function screening_pie_data_for_stage5($dates,$school_name) 
	{
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '10G' );
		
		$request = array();
		
        /* $count = $this->mongo_db->where(array ("doc_data.widget_data.page2.Personal Information.School Name" => $school_name))->count ( $this->screening_app_col ); */
      //  $count = $this->mongo_db->count ( $this->screening_app_col );
        $count = $this->mongo_db->count ( $this->screening_app_col );
		
		/*if ($count < 5000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 5000;
			$loop = $count / $per_page;
		}*/
		$per_page = 5000;
		$loop = 5;//$count / $per_page;
		 $merged_array = array();
		
		// Overweight
		 /* $overweight_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt'=> 25,'$lte' => 30
				) 
		);  */
		
		  $overweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				) 
		); 
		
		$page5_exists = array(
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array(
					'$exists' => true

					)
					);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push($merged_array,$overweight_array);
		array_push($merged_array,$schoolwise_check);
		array_push($merged_array,$page5_exists);
		
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
							'$match' => array(
							'$and' => $merged_array 
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
			echo print_r($response,true);
			//exit();
		log_message('debug','OVERWEIGHT=====5====1290====='.print_r($response,true));
			//exit(); 
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date['time'];
					
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
		
		$request["Over Weight"]  = $search_result;
		
		
		// ==========================================================================================
		// Underweight
		$merged_array = array();
		
 	    $underweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		); 
		
		$page5_exists = array(
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array(
				'$exists' => true
				)
		);
		
		
		/* $underweight_array = array (
				"doc_data.widget_data.page3.Physical Exam.BMI%" => array (
						'$gt'=> 18,'$lte' => 25
				) 
		);  */
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push($merged_array,$underweight_array);
		array_push($merged_array,$schoolwise_check);
		array_push($merged_array,$page5_exists);
		
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
							'$match' => array(
							'$and' => $merged_array 
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
			
			log_message('debug','Underweight=====1394====='.print_r($response,true));
			//exit();
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
		//===========================Obese
		
		$merged_array = array();
		
 	    $obese_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Obese" 
						) 
				) 
		); 
		
		$page5_exists = array(
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array(
				'$exists' => true
				)
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push($merged_array,$obese_array);
		array_push($merged_array,$schoolwise_check);
		array_push($merged_array,$page5_exists);
		
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
							'$match' => array(
							'$and' => $merged_array 
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
			
			log_message('debug','Underweight=====1394====='.print_r($response,true));
			//exit();
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
		
		$request["Obese"]  = $search_result;
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
			
		
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$merged_array = array ();
		
		$skin_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
			
		
		
		array_push ( $merged_array, $skin_array );
		array_push ( $merged_array, $schoolwise_check );
		
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
							'$match' => array(
							'$and' => $merged_array 
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
			
			log_message("debug","skin=======1570".print_r($response,true));
			//exit();
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $description_str_empty );
		array_push ( $and_merged_array, $description_str_space );
		array_push ( $and_merged_array, $advice_str_empty );
		array_push ( $and_merged_array, $advice_str_space );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		//=================Skin Conditions=======//
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Acne on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
			
			log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
			
			$temp_result = [ ];
			
			foreach ( $response ['result'] as $doc )
			{
				foreach ( $doc ['history'] as $date )
				{
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Acne on Face"] = $search_result;
		//====================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hyper Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Hyper Pigmentation"] = $search_result;
		
		//===================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Danddruff"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Danddruff"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Greying Hair"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Greying Hair"] = $search_result;
		
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "ECCEMA"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["ECCEMA"] = $search_result;
		
		
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Molluscum"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Molluscum"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Cracked Feet"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Cracked Feet"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Cruris"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Taenia Cruris"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hansens Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Hansens Disease"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hypo Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Hypo Pigmentation"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Nail Bed Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Nail Bed Disease"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Psoriasis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Psoriasis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hyperhidrosis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Hyperhidrosis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Scabies"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Scabies"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Allergic Rash"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Allergic Rash"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Corporis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Taenia Corporis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "White Patches on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["White Patches on Face"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Facialis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
					log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
					log_message("debug","end_date=============1871".print_r($dates ['end_date'],true));
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
		
		$request["Taenia Facialis"] = $search_result;
		
		//========================================================
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		
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
								"no",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"no",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"pass",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $audi_right );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"pass",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"normal",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		
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
								"good",
								"Poor",
								"poor",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
						'$in' => array (
								"Poor",
								"poor" 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"no",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"no",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"no",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
								"no",
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
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
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
	
	public function screening_pie_data_for_stage1_new($requests) 
	{
		$request_stage1 = [ ];
		
		log_message('debug','screening_pie_data_for_stage1_new====2122=='.print_r($requests,true));
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests[0]["Physical Abnormalities"]['value'] + $requests[1]["Physical Abnormalities"]['value'] + $requests[2]["Physical Abnormalities"]['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'] + $requests [10] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [11] ["Eye Abnormalities"] ['value'] + $requests [12] ["Eye Abnormalities"] ['value'] + $requests [13] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [14] ["Auditory Abnormalities"] ['value'] + $requests [15] ["Auditory Abnormalities"] ['value'] + $requests [16] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [17] ["Dental Abnormalities"] ['value'] + $requests [18] ["Dental Abnormalities"] ['value'] + $requests [19] ["Dental Abnormalities"] ['value'] + $requests [20] ["Dental Abnormalities"] ['value'] + $requests [21] ["Dental Abnormalities"] ['value'] + $requests [22] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Skin Conditions";
		$stage_data ['value'] = $requests [23] ["Skin Conditions"] ['value'] + $requests [24] ["Skin Conditions"] ['value'] + $requests [25] ["Skin Conditions"] ['value'] + $requests [26] ["Skin Conditions"] ['value']
		+ $requests [27] ["Skin Conditions"] ['value'] + $requests [28] ["Skin Conditions"] ['value'] + $requests [29] ["Skin Conditions"] ['value'] + $requests [30] ["Skin Conditions"] ['value'] + $requests [31] ["Skin Conditions"] ['value'] + $requests [32] ["Skin Conditions"] ['value'] + $requests [33] ["Skin Conditions"] ['value'] + $requests [34] ["Skin Conditions"] ['value'] + $requests [35] ["Skin Conditions"] ['value'] + $requests [36] ["Skin Conditions"] ['value'] + $requests [37] ["Skin Conditions"] ['value'] + $requests [38] ["Skin Conditions"] ['value'] + $requests [39] ["Skin Conditions"] ['value'] + $requests [40] ["Skin Conditions"] ['value'];
		
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	public function get_drilling_screenings_abnormalities($data, $date = false, $screening_duration = "Yearly") 
	{
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type     = $obj_data ['label'];
		
		$dates = $this->get_start_end_date($today_date,$screening_duration );
		
		ini_set ( 'memory_limit', '10G' );
		if($screening_duration == "2017_2018")
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		}else
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening_2018_2019 );
		}

		if($screening_duration == "2017_2018")
		{
			switch ($type) 
		{
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) 
				{
				  $request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Obese';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) 
				{
				  $request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [2] ['Physical Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [3] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['General Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [11] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				/*$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );*/
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [14] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [15] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [16] ['Auditory Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [17] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [18] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [19] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [20] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [21] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [22] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				//log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($requests,true));
				return $requests;				
				break;
				
				case "Skin Conditions" :
				
				$requests = [ ];
				
				 $request ['label'] = 'Acne on Face';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [23] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hyper Pigmentation';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [24] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				/*$request ['label'] = 'Greying Hair';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [25] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );*/
				
				$request ['label'] = 'Danddruff';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [26] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );  
				
				$request ['label'] = 'Taenia Facialis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [27] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'White Patches on Face';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [28] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Taenia Corporis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [29] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Allergic Rash';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [30] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Scabies';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [31] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
			/*	$request ['label'] = 'Hyperhidrosis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [32] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); */
				
				/*$request ['label'] = 'Psoriasis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [33] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); */
				
				$request ['label'] = 'Nail Bed Disease';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [34] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Hypo Pigmentation';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [35] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Hansens Disease';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [36] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Taenia Cruris';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [37] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Cracked Feet';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [38] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Molluscum';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [39] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'ECCEMA';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [40] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}

		}else
		{
			switch ($type) 
		{
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) 
				{
				  $request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Obese';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) 
				{
				  $request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [2] ['Physical Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [3] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['General Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [16] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [17] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				/*$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );*/
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [19] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [20] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [21] ['Auditory Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [22] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [23] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [24] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [25] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [26] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [27] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				//log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($requests,true));
				return $requests;				
				break;
				
				case "Skin Conditions" :
				
				$requests = [ ];
				
				 $request ['label'] = 'Acne on Face';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [35] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hyper Pigmentation';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [36] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				/*$request ['label'] = 'Greying Hair';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [25] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );*/
				
				$request ['label'] = 'Danddruff';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [37] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );  
				
				$request ['label'] = 'Taenia Facialis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [39] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'White Patches on Face';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [32] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Taenia Corporis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [34] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Allergic Rash';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [45] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Scabies';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [33] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
			/*	$request ['label'] = 'Hyperhidrosis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [32] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); */
				
				/*$request ['label'] = 'Psoriasis';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [33] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); */
				
				$request ['label'] = 'Nail Bed Disease';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [41] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Hypo Pigmentation';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [38] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Hansens Disease';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [44] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Taenia Cruris';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [40] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Cracked Feet';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [46] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'Molluscum';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [42] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request ); 
				
				$request ['label'] = 'ECCEMA';
				 $request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [43] ['Skin Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}

		}
		
		
		
		
		
	}
	
	public function get_drilling_screenings_students($data, $date = false, $screening_duration = "Yearly")
	{
		ini_set ( 'memory_limit', '1G' );
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else
		{
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		if($screening_duration == "2017_2018")
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		}else
		{
			$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
			) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening_2018_2019 );
		}
		
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];

		if($screening_duration == "2017_2018")
		{
			switch ($type) 
		{
			case "Over Weight" :
				
				$requests = [ ];
				
				foreach ( $pie_data as $each_pie ) 
				{
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"])) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] );
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"]);
				}
				
				return $requests;
				break;
				
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"]);
				}
				
				return $requests;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] );
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"]);
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] );
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"]);
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] );
				}
				
				return $requests;
				break;
			
			 case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] );
				}
				
				return $requests;
				break; 
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] );
				}
				
				return $requests;
				break;
				
				 case "Acne on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] );
				}
				
				return $requests;
				break; 
				
				case "Hyper Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Greying Hair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] );
				}
				
				return $requests;
				break;
				
				case "Danddruff" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Facialis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] );
				}
				
				return $requests;
				break;
				
				case "White Patches on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Corporis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] );
				}
				
				return $requests;
				break;
				
				case "Allergic Rash" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] );
				}
				
				return $requests;
				break;
				
				case "Scabies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] );
				}
				
				return $requests;
				break;
				
				case "Hyperhidrosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] );
				}
				
				return $requests;
				break;
				
				case "Psoriasis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] );
				}
				
				return $requests;
				break;
				
				case "Nail Bed Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] );
				}
				
				return $requests;
				break;
				
				case "Hypo Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Hansens Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Cruris" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] );
				}
				
				return $requests;
				break;
				
				case "Cracked Feet" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] );
				}
				
				return $requests;
				break;
				
				case "Molluscum" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] );
				}
				
				return $requests;
				break;
				
				case "ECCEMA" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] );
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] );
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"]);
				}
				
				return $requests;
				break;
			
			/*case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"]);
				}
				
				return $requests;
				break;*/
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] );
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] );
				}
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] );
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] );
				}
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] );
				}
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"]);
				}
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] );
				}
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] );
				}
				
				return $requests;
				break;
			
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] );
				}
				
				return $requests;
				break;
				
			default :
				;
				break;
		}
		}else
		{
			switch ($type) 
		{
			case "Over Weight" :
				
				$requests = [ ];
				
				foreach ( $pie_data as $each_pie ) 
				{
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"])) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] );
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"]);
				}
				
				return $requests;
				break;
				
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"]);
				}
				
				return $requests;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] );
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"]);
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] );
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"]);
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] );
				}
				
				return $requests;
				break;
			
			 case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] );
				}
				
				return $requests;
				break; 
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] );
				}
				
				return $requests;
				break;
				
				 case "Acne on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] );
				}
				
				return $requests;
				break; 
				
				case "Hyper Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Greying Hair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] );
				}
				
				return $requests;
				break;
				
				case "Danddruff" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Facialis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] );
				}
				
				return $requests;
				break;
				
				case "White Patches on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Corporis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] );
				}
				
				return $requests;
				break;
				
				case "Allergic Rash" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] );
				}
				
				return $requests;
				break;
				
				case "Scabies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] );
				}
				
				return $requests;
				break;
				
				case "Hyperhidrosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] );
				}
				
				return $requests;
				break;
				
				case "Psoriasis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] );
				}
				
				return $requests;
				break;
				
				case "Nail Bed Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] );
				}
				
				return $requests;
				break;
				
				case "Hypo Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Hansens Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Cruris" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] );
				}
				
				return $requests;
				break;
				
				case "Cracked Feet" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] );
				}
				
				return $requests;
				break;
				
				case "Molluscum" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] );
				}
				
				return $requests;
				break;
				
				case "ECCEMA" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					log_message("debug","each function skin conditions============3645".print_r($each_pie,true));
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] );
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive Without Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive Without Glasses"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive Without Glasses"] );
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive With Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive With Glasses"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Refractive With Glasses"]);
				}
				
				return $requests;
				break;
			
			/*case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"]);
				}
				
				return $requests;
				break;*/
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] );
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] );
				}
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] );
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] );
				}
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] );
				}
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"]);
				}
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] );
				}
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] );
				}
				
				return $requests;
				break;
			
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] );
				}
				
				return $requests;
				break;
				
			default :
				;
				break;
		}

		}
		
		
	}
	
	public function get_drilling_screenings_students_docs($_id_array) 
	{
		$docs = [ ];
		ini_set ( 'memory_limit', '10G' );
		log_message('debug','get_drilling_screenings_students_docs($_id_array)==1=='.print_r($_id_array,true));
		foreach($_id_array as $_id) 
		{
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			
			if (isset ( $query [0] ))
				array_push ( $docs, $query [0] );
		}
		log_message('debug','get_drilling_screenings_students_docs($_id_array)==2=='.print_r($docs,true));
		return $docs;
		
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id) 
	{
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
		
		if ($query) 
		{
			//$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			//$result ['request'] = $query_request;
			return $result;
		} 
		else 
		{
			$result ['screening'] = false;
			//$result ['request'] = false;
			return $result;
		}
	}
	
	public function get_last_screening_update() 
	{
		$query = $this->mongo_db->limit ( 1 )->orderBy ( array (
				'pie_data.date' => - 1 
		) )->select ( 'pie_data.date' )->get ( $this->screening_app_col_screening );
		
		if (isset ( $query ) && ! empty ( $query ) && (count ( $query ) > 0)) 
		{
		  return "Last update on : " . substr ( $query [0] ['pie_data'] ['date'], 0, 10 );
		} 
		else 
		{
		  return "No updates yet.";
		}
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
			->get($this->collections['schoolhealth_schools']);

		if (count($docs) !== 1)
		{
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			// Store the new password and reset the remember code so all remembered instances have to re-login
			
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			
			$updated = $this->mongo_db
				->where("email", $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['schoolhealth_schools']);
				
            $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}
		
		return FALSE;
	}
	
	////////function for import_students------
	
	public function insert_student_data($doc_data, $history, $doc_properties)
	{
		// $query = $this->mongo_db->getWhere("naresh", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
		
		// $query = $this->mongo_db->getWhere ( $this->collections['schoolhealth_school_import_students'], array (
				// 'doc_data.widget_data.page2.Personal Information.AD No' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'],
				// 'doc_data.widget_data.page2.Personal Information.School Name' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] 
		// ) );
		
		////@Naresh
		
		$query = $this->mongo_db->getWhere('school_health_screening_2019_2020', array (
				'doc_data.widget_data.page2.Personal Information.AD No' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'],
				'doc_data.widget_data.page2.Personal Information.School Name' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] 
		) );
		
		// $query = $this->mongo_db->getWhere("form_data_sample_copy_1", array('doc_data.widget_data.page2.Physical Info.ID number' => $doc_data['widget_data']['page2']['Physical Info']['ID number'],'doc_data.widget_data.page2.Physical Info.School'=>'TSWRS/JC(G)-JADCHERLA'));
		
		$result = json_decode ( json_encode ( $query ), FALSE );
		if (! $result) {
			$form_data = array ();
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
			
			// $this->mongo_db->insert("naresh",$form_data);
			
			//$this->mongo_db->insert ( $this->collections['schoolhealth_school_import_students'], $form_data );
			$this->mongo_db->insert('school_health_screening_2019_2020',$form_data);
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		} else {
			$form_data = array ();
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] = $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] . 'A';
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
			
			// $this->mongo_db->insert("naresh",$form_data);
			//$this->mongo_db->insert ( "schoolhealth_school_import_students", $form_data );
			
			//@Naresh
			$this->mongo_db->insert ('school_health_screening_2019_2020',$form_data);
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		}
	}
	
	
	public function get_schools_by_dist_id($dist_id) {
		if ($dist_id == "All") {
			// ini_set ( 'memory_limit', '1G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->get ( $this->screening_app_col );
			
			ini_set ( 'memory_limit', '1G' );
			//$count = $this->mongo_db->count ( $this->collections['schoolhealth_school_import_students'] );
			//@Naresh
			$count = $this->mongo_db->count($this->collections['healthcare20161014212024617']);
			$per_page = 1000;
			$loop = $count / $per_page;
			
			$result = [ ];
			for($page = 1; $page < $loop; $page ++) {
				$offset = $per_page * ($page);
				$pipeline = [ 
						array (
								'$project' => array (
										"doc_data.widget_data.page1" => true,
										"doc_data.widget_data.page2" => true 
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
						//'aggregate' => $this->collections['schoolhealth_school_import_students'],
						//@Naresh
						'aggregate' => $this->collections['healthcare20161014212024617'],
						'pipeline' => $pipeline 
				) );
				$result = array_merge ( $result, $response ['result'] );
			}
			return $result;
		} else {
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'school_mob',
					'contact_person_name' 
			) )->orderBy ( array (
					'school_name' => 1 
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['schoolhealth_schools'] );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}
	}
	
	function fetch_company_details_of_enterprise_admin($company_name)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$userdocument = $this->mongo_db
		->select(array('company_name','registered_on','plan_expiry'))
		->where(array("company_name" => $company_name))
		->limit(1)
		->get($this->collections['collection_for_authentication']);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $userdocument[0];
	}
	
	function add_student_into_login_collection($array_data,$company_data)
    {
	 
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		
	   $is_uniqueID_exists = $this->mongo_db->where('hospital_unique_id',$array_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'])->get($this->collections['schoolhealth_students']);
	
	   if($is_uniqueID_exists)
	   {
		   
	   }
	   else
	   {
		   $name = $array_data['widget_data']['page1']['Personal Information']['Name'];
		   $pass = 12345678;
		   $salt = substr(md5(uniqid(rand(), true)), 0, 10);
		   $password   = $salt . substr(sha1($salt . $pass), 0, -10);
		   
		   $data = array(
		   'name'   => $array_data['widget_data']['page1']['Personal Information']['Name'],
		   'hospital_unique_id' => $array_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'],
		   'school_name' => $array_data['widget_data']['page2']['Personal Information']['School Name'],
		   'mobile' => $array_data['widget_data']['page1']['Personal Information']['Mobile'],
		   'active'        => 1,
		   'company_name'  => $company_data['company_name'],
		   'last_login'    => date('Y-m-d H:i:s'),
		   'plan_expiry'   => $company_data['plan_expiry'],
		   'password'      => $password,
		   'registered_on' => $company_data['registered_on'],
		   'salt' 		   => null);
		   
		  
		   $this->mongo_db->insert($this->collections['schoolhealth_students'],$data);
		   $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	   }
	   
	
    }
	
	
	public function get_school_info($school_code)
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$res = $this->mongo_db->where(array('school_code' => $school_code))->get($this->collections['schoolhealth_schools']);
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		if($res)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	public function get_district($district_id)
	{
		$qry = $this->mongo_db->where('_id', new MongoId($district_id))->get($this->collections['schoolhealth_districts']);
		if($qry)
		{
			return $qry;
		}
		else
		{
			return false;
		}
	}
	
	public function get_screening_pie_stage5($dates) {
		ini_set ( 'memory_limit', '10G' );
		$pie_stage5 = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		return $pie_stage5;
	}
	
	public function get_students_by_selected_class_section($class/*,$section*/,$school_name)
	{
	  $query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name,"doc_data.widget_data.page2.Personal Information.Class"=>$class/*,"doc_data.widget_data.page2.Personal Information.Section"=>$section*/))->get($this->collections['healthcare20161014212024617']); 
	  return $query;
	
	}
	
		// BMI PIE REPORT
	public function get_bmi_report_model($school_name) {

	
	$requests = [ ];
	
			
			$under_weight = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereLt('doc_data.widget_data.page3.Physical Exam.BMI%',18.50) 
			->get('healthcare20161014212024617');
			$request ['label'] = 'UNDER WEIGHT';
			$request ['value'] = count($under_weight);
			array_push ( $requests, $request );
			
			$normal_weight = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereGte('doc_data.widget_data.page3.Physical Exam.BMI%',18.50)->whereLte('doc_data.widget_data.page3.Physical Exam.BMI%',24.99)->get('healthcare20161014212024617');
			$request ['label'] = 'NORMAL WEIGHT';
			$request ['value'] = count($normal_weight);
			array_push ( $requests, $request );
			
			$over_weight = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereGte('doc_data.widget_data.page3.Physical Exam.BMI%',25.00)->whereLte('doc_data.widget_data.page3.Physical Exam.BMI%',29.99)->get('healthcare20161014212024617');
			$request ['label'] = 'OVER WEIGHT';
			$request ['value'] = count($over_weight);
			array_push ( $requests, $request );
			
			$obese = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereGte('doc_data.widget_data.page3.Physical Exam.BMI%',30.0)->get('healthcare20161014212024617');
			$request ['label'] = 'OBESE';
			$request ['value'] = count($obese);
			array_push ( $requests, $request );
			
			
	return $requests;
	
}


public function get_drill_down_to_bmi_report($type,$school_name) 
{
	
	ini_set ( 'memory_limit', '10G' );
	
	switch ($type) {
		case "UNDER WEIGHT" :
			ini_set ( 'memory_limit', '10G' );
			//$select_qry = 
			$query = $this->mongo_db->select ( array (
					"doc_data" 
			) )->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereLt('doc_data.widget_data.page3.Physical Exam.BMI%',18.50) 
			->get('healthcare20161014212024617');
			
			$doc_query = array ();
			if ($school_name == "All") {
				if ($district_name != "All") {
					foreach ( $query as $doc ) {
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				} else {
				}
			} else {
				foreach ( $query as $doc ) {
					
					if (strtolower ( $doc ['doc_data'] ['widget_data']['page2']['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
			}
			
			return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
			break;
			
		case "NORMAL WEIGHT" :
		ini_set ( 'memory_limit', '10G' );
			$query = $this->mongo_db->select ( array (
					"doc_data" 
			) )->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereGte('doc_data.widget_data.page3.Physical Exam.BMI%',18.50)->whereLte('doc_data.widget_data.page3.Physical Exam.BMI%',24.99)->get('healthcare20161014212024617');
			
			$doc_query = array ();
			if ($school_name == "All") {
				if ($district_name != "All") {
					foreach ( $query as $doc ) {
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				} else {
				}
			} else {
				foreach ( $query as $doc ) {
					
					if (strtolower ( $doc ['doc_data'] ['widget_data']['page2']['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			}
			
			return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
			break;
		
		case "OVER WEIGHT" :
		ini_set ( 'memory_limit', '10G' );

		
			$query = $this->mongo_db->select ( array (
					"doc_data" 
			) )->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->whereGte('doc_data.widget_data.page3.Physical Exam.BMI%',25.00)->whereLte('doc_data.widget_data.page3.Physical Exam.BMI%',29.99)->get('healthcare20161014212024617');
			
			$doc_query = array ();
			if ($school_name == "All") {
				if ($district_name != "All") {
					foreach ( $query as $doc ) {
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				} else {
				}
			} else {
				foreach ( $query as $doc ) {
					
					if (strtolower ( $doc ['doc_data'] ['widget_data']['page2']['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			}
			return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
			break;
			
			
			case "OBESE" :
			ini_set ( 'memory_limit', '10G' );
		
			$query = $this->mongo_db->select ( array (
					"doc_data" 
			) )->where('doc_data.widget_data.page2.Personal Information.School Name',$school_name)->wheregte('doc_data.widget_data.page3.Physical Exam.BMI%',30.0)->get('healthcare20161014212024617');
			
			$doc_query = array ();
			if ($school_name == "All") {
				if ($district_name != "All") {
					foreach ( $query as $doc ) {
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				} else {
				}
			} else {
				foreach ( $query as $doc ) {
					
					if (strtolower ( $doc ['doc_data'] ['widget_data']['page2']['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			}
			return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
			break;
		
		default :
			;
			break;
	}
}

public function get_drilling_bmi_students_prepare_pie_array($query, $school_name, $type)
{
	$search_result = [ ];
	$count = 0;
	
		if ($query) {
		//ini_set('memory_limit','20G');
		$request = [ ];
		$UI_arr = [ ];
		foreach ( $query as $doc ) {
			
			switch ($type) {
				case "UNDER WEIGHT" :
				
					$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] );
					$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
					
					break;
				case "NORMAL WEIGHT" :
					
					$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] );
				
					$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
					
					break;
				
				case "OVER WEIGHT" :
					
					$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] );
					
					$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
					
					break;
					
				case "OBESE" :
					
					$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] );
					
					$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
					
					break;
				
				default :
				break;
			}
		}
	
		return $UI_arr;
	}
}
	
public function get_drilling_bmi_students_docs($_id_array) 
{
	$docs = [ ];
		
		ini_set ( 'memory_limit', '1G' );
		
		if(isset($_id_array) && !empty($_id_array))
		{
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' ,
					'doc_data.widget_data.page3'
			) )->where/* Like */ ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
			if ($query)
				array_push ( $docs, $query [0] );
		}
		
		}
		

		return $docs;
}



public function export_bmi_reports_monthly_to_excel($school_name){
	
	$query = $this->mongo_db->select ( array (
			"doc_data.widget_data") )
			->where(array( "doc_data.widget_data.page2.Personal Information.School Name" => $school_name))->get ( $this->screening_app_col );
	
	return $query;
}

public function get_student_ehr_document($hospital_unique_id, $school_name)
{
	$qry = $this->mongo_db->where(array("doc_data.widget_data.page1.Personal Information.Hospital Unique ID" => $hospital_unique_id, "doc_data.widget_data.page2.Personal Information.School Name" => $school_name))->get($this->screening_app_col);

	$result ['screening'] = $qry;
	return $result;
}

public function total_screened_Students_count($school_name)
{
	$query = array("doc_data.widget_data.page3.Physical Exam" => array('$exists' => True), "doc_data.widget_data.page9.Dental Check-up" => array('$exists' => True));
	$qry = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name" => $school_name))->where($query)->count($this->screening_app_col);

	return $qry;
}

public function checking_name_if_exists($student_name, $unique_id)
{
	log_message('error', "checking student name in model".print_r($student_name, true));
	$id = explode("_", $unique_id);
		log_message('error', "checking id in model".print_r($id, true)); 
	$original = $id[0]."_".$id[1]."_*";

	$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where(array("doc_data.widget_data.page1.Personal Information.Name" => $student_name, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => array('$regex' => $original)))->get('healthcare20161014212024617');
	/*if(empty($query))
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where(array("doc_data.widget_data.page1.Personal Information.Name" => $student_name, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => array('$regex' => $original)))->get('maharashtra_screening_sync_app_collection');
	}*/

	log_message('error', "original original =======".print_r($original, true));
	log_message('error', "checking student name query in model".print_r($query, true));

	if(!empty($query) && isset($query)){
		return $query;
	}else{
		return false;
	}
}

public function checking_unique_id_if_exists($unique_id)
{
	$query = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id)->count('healthcare20161014212024617');
	
	//log_message('error', "checking_unique_id_if_exists".print_r($query, true)); 
	if($query != 0)
	{

		$id = explode("_", $unique_id);
		log_message('error', "checking id in model".print_r($id, true)); 
		$original = $id[0]."_".$id[1]."_*";
		
		$pass_id = array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => array('$regex' => $original));
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($pass_id)->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => -1))->limit(1)->get('healthcare20161014212024617');
		
		$check_id = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $query[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']))->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => -1))->limit(1)->get('maharashtra_screening_sync_app_collection');
		if(!empty($check_id))
		{
			$query = $check_id;
		}
		log_message('error', "checking original model".print_r($query, true)); 
		$idss = (isset($query[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']) && !empty($query[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])) ? $query[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] : "";
		log_message('error', "idssidssidss original model".print_r($idss, true)); 
		if(!preg_match('/staff/i', $idss) && !empty($idss)){
			$id_input = explode('_', $idss);
			$u_id = $id_input[2]+1;
			$final_u_id = $id_input[0]."_".$id_input[1]."_".$u_id;
			return $final_u_id;
		}else
		{
			return false;
		}
	}else{
		return false;
		}
	}


// Screening Stages as per maharashtra form


	private function screening_pie_data_for_stage5_new($dates,$school_name)
	{
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '2G' );

		$request = array();

		$count = $this->mongo_db->count ( $this->screening_col_2019_2020 );
		

		/*if ($count < 5000)
		{
			$per_page = $count;
			$loop = 2;
		}
		else
		{
			$per_page = 5000;
			$loop 	  = $count / $per_page;
		}*/

		/*$per_page = 6000;
		$loop 	  = $count / $per_page;*/

		$merged_array = array();

		$overweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight"
						)
				    )

		);

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push($merged_array,$overweight_array);
		array_push($merged_array,$schoolwise_check);

		$result = [ ];
		$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' => array(
							'$and'	=> $merged_array
							)
					)
			];

			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
			
		/*$result = [ ];
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
							'$match' => array(
							'$and'	=> $merged_array
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
					'aggregate' => $this->screening_col_2019_2020,
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
		}*/

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Over Weight"]  = $search_result;
		
		// ==========================================================================================

		$merged_array = array();

		$underweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight"
						)
				)
		);

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push($merged_array,$underweight_array);
		array_push($merged_array,$schoolwise_check);



		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' => array(
							'$and'	 => $merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Under Weight"]  = $search_result;

		// ========================================================================================

		$merged_array = array();

		$obese_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Obese"
						)
				)
		);

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push($merged_array,$obese_array);
		array_push($merged_array,$schoolwise_check);



		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' => array(
							'$and'	 => $merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Obese"]  = $search_result;

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["General"]  = $search_result;

		// ========================================================================================


		$merged_array = array ();

		$skin_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin"
						)
				)
		);

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $merged_array, $skin_array );
		array_push ( $merged_array, $schoolwise_check );

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' => array(
							'$and'	 =>$merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Skin"]  = $search_result;

		// ========================================================================================

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $description_str_empty );
		array_push ( $and_merged_array, $description_str_space );
		array_push ( $and_merged_array, $advice_str_empty );
		array_push ( $and_merged_array, $advice_str_space );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
			)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];

			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );

		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Defects at Birth"] = $search_result;

		// ==============================================================================

		/*$and_merged_array = array ();

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );

		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$request["Deficencies"] = $search_result;*/

		// ==========================================================================================

		//======================Deficencies divided into further parts=================================
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Anaemia"
					)
			)
		);

		$schoolwise_check = array (
			"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
			);

			array_push($and_merged_array, $merged_array);
			array_push($and_merged_array, $schoolwise_check);

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' =>array(
							'$and' => $merged_array
						)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Anaemia"] = $search_result;
		//=============================================================
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin Deficiency - Bcomplex"
					)
			)
		);

		$schoolwise_check = array (
			"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
			);

			array_push($and_merged_array, $merged_array);
			array_push($and_merged_array, $schoolwise_check);

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' =>array(
							'$and' => $and_merged_array
						)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Vitamin Deficiency - Bcomplex"] = $search_result;
		//=========================================================
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin A Deficiency"
					)
			)
		);

		$schoolwise_check = array (
			"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
			);

			array_push($and_merged_array, $merged_array);
			array_push($and_merged_array, $schoolwise_check);

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' =>array(
							'$and' => $and_merged_array
						)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Vitamin A Deficiency"] = $search_result;
		//=========================================================
			
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin D Deficiency"
					)
			)
		);

		$schoolwise_check = array (
			"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
			);

			array_push($and_merged_array, $merged_array);
			array_push($and_merged_array, $schoolwise_check);

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' =>array(
							'$and' => $and_merged_array
						)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Vitamin D Deficiency"] = $search_result;
		//=========================================================
			
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"SAM/stunting"
					)
			)
		);

		$schoolwise_check = array (
			"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
			);

			array_push($and_merged_array, $merged_array);
			array_push($and_merged_array, $schoolwise_check);

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' =>array(
							'$and' => $and_merged_array
						)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["SAM/stunting"] = $search_result;
		//=========================================================
			
		$and_merged_array = array();
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Goiter"
					)
			)
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $and_merged_array, $merged_array );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
			$pipeline = [
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true
							)
					),
					array (
							'$match' => array(
								'$and' => $and_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Goiter"] = $search_result;
		//=========================================================

		//======================Deficencies divided into further parts=================================

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );

		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
								"6/9",
								"6/12",
								"",
								" "
						)
				)
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"6/9",
								"6/12",
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Refractive Without Glasses"] = $search_result;

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Refractive With Glasses"] = $search_result;

		// ===================================================================================

		/*$and_merged_array = array ();
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$request["Colour Blindness"] = $search_result;*/


		$and_merged_array = array ();
		//$or_merged_array = array ();

		$color_right = array (
				"doc_data.widget_data.page6.Vision Screening" => array (
						'$nin' => array (
								"",
								" ",
								[],
								[ ]
						)
				)
		);
		

		
		$vision_screening_exists = array (
				"doc_data.widget_data.page6.Vision Screening" => array (
						'$exists' => true
				)
		);


		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		
		array_push ( $and_merged_array, $color_right );
		array_push ( $and_merged_array, $vision_screening_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Other Eye Problems"] = $search_result;

		// ===========================================================================

		$and_merged_array = array ();
		$or_merged_array  = array ();

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $audi_right );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Right Ear"] = $search_result;

		// ==============================================================================

		$and_merged_array = array ();
		$or_merged_array  = array ();

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Left Ear"] = $search_result;

		// ====================================================================================

		$and_merged_array = array ();
		$or_merged_array  = array ();

		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"Normal ",
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $speech );

		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );

		$result = [ ];
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Oral Hygiene - Fair"] = $search_result;

		// ==========================================================================================

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Oral Hygiene - Poor"] = $search_result;

		// ==========================================================================================

		$and_merged_array = array ();
		$or_merged_array  = array ();

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Carious Teeth"] = $search_result;

		// ==========================================================================================

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Orthodontic Treatment"] = $search_result;

		// ==========================================================================================

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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Indication for extraction"] = $search_result;


		//==========================================================================================

		$and_merged_array = array ();
		$or_merged_array = array ();

		$halitosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Halitosis" => array (
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $halitosis );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Halitosis"] = $search_result;


		//==========================================================================================

		$and_merged_array = array ();
		$or_merged_array = array ();

		$flatpatches_red = array (
				"doc_data.widget_data.page9.Dental Check-up.Flat patches" => array (
						'$nin' => array (
								"White",
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $flatpatches_red );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Flat Patches - Red"] = $search_result;

		//==========================================================================================

		$and_merged_array = array ();
		$or_merged_array = array ();

		$flatpatches_white = array (
				"doc_data.widget_data.page9.Dental Check-up.Flat patches" => array (
						'$nin' => array (
								"Red",
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $flatpatches_white );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Flat Patches - White"] = $search_result;

		//==========================================================================================

		$and_merged_array = array ();
		$or_merged_array = array ();

		$ulcer = array (
				"doc_data.widget_data.page9.Dental Check-up.Ulcer" => array (
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

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);

		array_push ( $or_merged_array, $ulcer );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );

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
									'$and' => $and_merged_array,
									'$or' => $or_merged_array
							)
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		

		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Ulcer"] = $search_result;

		// =================================SKIN ABNORMALITIES======================================

		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$white_patches_on_face = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "White Patches on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $white_patches_on_face );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["White Patches on Face"] = $search_result;
		//======================= Scabies=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$scabies = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Scabies"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $scabies );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Scabies"] = $search_result;
		//======================= Scabies=======================================================
		//======================= Taenia Corporis=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$taenia_corporis = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Corporis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $taenia_corporis );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Taenia Corporis"] = $search_result;
		//======================= Taenia Corporis=======================================================
		//======================= Acne on Face=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$acne_on_face = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Acne on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $acne_on_face );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Acne on Face"] = $search_result;
		//======================= Acne on Face=======================================================
		//======================= Hyper Pigmentation=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$hyper_pigmentation = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hyper Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $hyper_pigmentation );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Hyper Pigmentation"] = $search_result;
		//======================= Hyper Pigmentation=======================================================
		//======================= Danddruff=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$danddruff = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Danddruff"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $danddruff );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Danddruff"] = $search_result;
		//======================= Danddruff=======================================================
		//======================= Hypo Pigmentation=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$hypo_pigmentation = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hypo Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $hypo_pigmentation );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Hypo Pigmentation"] = $search_result;
		//======================= Hypo Pigmentation=======================================================
		//======================= Taenia Facialis=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$taenia_facialis = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Facialis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $taenia_facialis );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Taenia Facialis"] = $search_result;
		//======================= Taenia Facialis=======================================================
		//======================= Taenia Cruris=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$taenia_crusis = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Cruris"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $taenia_crusis );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Taenia Cruris"] = $search_result;
		//======================= Taenia Cruris=======================================================
		//======================= Nail Bed Disease=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$nai_bed_disease = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Nail Bed Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $nai_bed_disease );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Nail Bed Disease"] = $search_result;
		//======================= Nail Bed Disease=======================================================
		//======================= Molluscum=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$molluscum = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Molluscum"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $molluscum );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Molluscum"] = $search_result;
		//======================= Molluscum=======================================================
		//======================= ECCEMA=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$ECCEMA = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "ECCEMA"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $ECCEMA );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["ECCEMA"] = $search_result;
		//======================= ECCEMA=======================================================
		//======================= Hansens Disease=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$hansens_disease = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hansens Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $hansens_disease );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Hansens Disease"] = $search_result;
		//======================= Hansens Disease=======================================================
			//======================= Allergic Rash=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$allergic_rash = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Allergic Rash"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true
						) 
				);

		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $allergic_rash );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Allergic Rash"] = $search_result;
		//======================= Allergic Rash=======================================================
		//======================= Cracked Feet=======================================================
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$cracked_feet = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Cracked Feet"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true
						) 
				);
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		array_push ( $or_merged_array, $cracked_feet );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
				
		$result = [ ];
		
		
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
					)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_col_2019_2020,
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
		
		
		$search_result = array();

		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}

		$request["Cracked Feet"] = $search_result;
		//======================= Cracked Feet=======================================================
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate screening pie analytics - stage 2
	 *
	 *
	 * @param  array  $requests  Request data
	 *
	 * @return array
	 */

	public function screening_pie_data_for_stage2_new_for_recent($requests) {
		$request_stage2 = [ ];

		//log_message('debug','screening_pie_data_for_stage2_new=====453=='.print_r($requests,true));

		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests["Over Weight"] as $doc )
		{
		  $total_count = $total_count + count($doc);
		}

		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;

		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Under Weight"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Obese"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Physical Abnormalities"] ["label"] = "Obese";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["General"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Skin"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Skin";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// ===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Others(Description/Advice)"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Others(Description/Advice)";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Ortho"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Ortho";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Postural"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Postural";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Defects at Birth"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Defects at Birth";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		/*$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Deficencies"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );*/

		//===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Anaemia"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "Anaemia";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Vitamin Deficiency - Bcomplex"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin Deficiency - Bcomplex";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Vitamin A Deficiency"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin A Deficiency";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Vitamin D Deficiency"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin D Deficiency";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["SAM/stunting"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "SAM/stunting";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Goiter"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["General Abnormalities"] ["label"] = "Goiter";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===

		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Childhood Diseases"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["General Abnormalities"] ["label"] = "Childhood Diseases";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//================
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Refractive Without Glasses"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Refractive Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Refractive With Glasses"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Refractive With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Other Eye Problems"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		//$stage_array ["Eye Abnormalities"] ["label"] = "Colour Blindness";
		$stage_array ["Eye Abnormalities"] ["label"] = "Other Eye Problems";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		/*$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Without Glasses"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Eye Abnormalities"] ["label"] = "Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["With Glasses"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Eye Abnormalities"] ["label"] = "With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Vision Screening"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Eye Abnormalities"] ["label"] = "Vision Screening";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
			*/
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Right Ear"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Auditory Abnormalities"] ["label"] = "Right Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Left Ear"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Auditory Abnormalities"] ["label"] = "Left Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Speech Screening"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Auditory Abnormalities"] ["label"] = "Speech Screening";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Fair"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Fair";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Poor"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Poor";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Carious Teeth"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Carious Teeth";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$stage2_data = [ ];
		$stage2_data ["label"] = "Flourosis";

		$total_count = 0;
		foreach ( $requests ["Flourosis"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Flourosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Orthodontic Treatment"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Orthodontic Treatment";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Indication for extraction"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Indication for extraction";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Halitosis"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Halitosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Flat Patches - Red"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Flat Patches - Red";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Flat Patches - White"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Flat Patches - White";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Ulcer"] as $doc )
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Ulcer";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );

		// ===== SKIN ABNORMALITIES
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["White Patches on Face"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "White Patches on Face";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Scabies"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Scabies";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Scabies ====================//
		//============== Taenia Corporis ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Taenia Corporis"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Taenia Corporis";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Taenia Corporis ====================//
		//============== Acne on Face ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Acne on Face"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Acne on Face";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Acne on Face ====================//
		//============== Hyper Pigmentation ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Hyper Pigmentation"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Hyper Pigmentation";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Hyper Pigmentation ====================//
		//============== Danddruff ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Danddruff"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Danddruff";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Danddruff ====================//
		//============== Hypo Pigmentation ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Hypo Pigmentation"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Hypo Pigmentation";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Hypo Pigmentation ====================//
		//============== Taenia Facialis ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Taenia Facialis"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Taenia Facialis";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Taenia Facialis ====================//
		//============== Taenia Cruris ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Taenia Cruris"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Taenia Cruris";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Taenia Cruris ====================//
		//============== Nail Bed Disease ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Nail Bed Disease"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Nail Bed Disease";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Nail Bed Disease ====================//
		//============== Molluscum ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Molluscum"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Molluscum";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Molluscum ====================//
		//============== ECCEMA ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["ECCEMA"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "ECCEMA";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== ECCEMA ====================//
		//============== Hansens Disease ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Hansens Disease"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Hansens Disease";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Hansens Disease ====================//
		//============== Allergic Rash ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Allergic Rash"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Allergic Rash";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Allergic Rash ====================//
		//============== Cracked Feet ====================//
		$stage_array = [ ];
		$stage_array ["Skin Abnormalities"] = [ ];

		$total_count = 0;
		foreach ( $requests ["Cracked Feet"] as $doc ) {
			$total_count = $total_count + count($doc);
		}
		$stage_array ["Skin Abnormalities"] ["label"] = "Cracked Feet";
		$stage_array ["Skin Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		//============== Cracked Feet ====================//

		return $request_stage2;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate screening pie analytics - stage 1
	 *
	 *
	 * @param  array  $requests  Request data
	 *
	 * @return array
	 */

	public function screening_pie_data_for_stage1_new_for_recent($requests)
	{
		$request_stage1 = [ ];

		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests [0] ["Physical Abnormalities"] ['value'] + $requests [1] ["Physical Abnormalities"] ['value'] + $requests [2] ["Physical Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'] + $requests [10] ["General Abnormalities"] ['value'] + $requests [11] ["General Abnormalities"] ['value'] + $requests [12] ["General Abnormalities"] ['value'] + $requests [13] ["General Abnormalities"] ['value'] + $requests [14] ["General Abnormalities"] ['value'] + $requests [15] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [16] ["Eye Abnormalities"] ['value'] + $requests [17] ["Eye Abnormalities"] ['value'] + $requests [18] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [19] ["Auditory Abnormalities"] ['value'] + $requests [20] ["Auditory Abnormalities"] ['value'] + $requests [21] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [22] ["Dental Abnormalities"] ['value'] + $requests [23] ["Dental Abnormalities"] ['value'] + $requests [24] ["Dental Abnormalities"] ['value'] + $requests [25] ["Dental Abnormalities"] ['value'] + $requests [26] ["Dental Abnormalities"] ['value'] + $requests [27] ["Dental Abnormalities"] ['value'] + $requests [28] ["Dental Abnormalities"] ['value'] + $requests [29] ["Dental Abnormalities"] ['value'] + $requests [30] ["Dental Abnormalities"] ['value'] + $requests [31] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		$stage_data = [ ];
		$stage_data ['label'] = "Skin Abnormalities";
		$stage_data ['value'] = $requests [32] ["Skin Abnormalities"] ['value'] + $requests [33] ["Skin Abnormalities"] ['value'] + $requests [34] ["Skin Abnormalities"] ['value'] + $requests [35] ["Skin Abnormalities"] ['value'] + $requests [36] ["Skin Abnormalities"] ['value'] + $requests [37] ["Skin Abnormalities"] ['value'] + $requests [38] ["Skin Abnormalities"] ['value'] + $requests [39] ["Skin Abnormalities"] ['value'] + $requests [40] ["Skin Abnormalities"] ['value'] + $requests [41] ["Skin Abnormalities"] ['value']+ $requests [42] ["Skin Abnormalities"] ['value'] + $requests [43] ["Skin Abnormalities"] ['value'] + $requests [44] ["Skin Abnormalities"] ['value'] + $requests [45] ["Skin Abnormalities"] ['value'] + $requests [46] ["Skin Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );

		return $request_stage1;
	}

	public function get_qr_image_for_student($docs_id, $data, $photo_ele)
	{

		$check_existance = $this->mongo_db->where(array('doc_properties.doc_id'=>$docs_id, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $data['health_id']))->where(array('doc_data.qrcodeimage' => array('$exists'=> TRUE)))->get($this->screening_app_col);
		
		if(!empty($check_existance)){

			return "EXISTS";

		}else{
			$query = $this->mongo_db->where(array('doc_properties.doc_id'=>$docs_id, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $data['health_id']))->set(array('doc_data.qrcodeimage'=> $photo_ele))->update($this->screening_app_col);
			return $query;
		}
		

	}
	
} 
