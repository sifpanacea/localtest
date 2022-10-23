<?php
ini_set ( 'memory_limit',"2G");
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tmreis_common_model extends CI_Model {
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
	function __construct() {
		parent::__construct ();
		
		$this->load->config ( 'ion_auth', TRUE );
		$this->load->config ( 'mongodb', TRUE );
		
		// Initialize MongoDB collection names
		$this->collections = $this->config->item ( 'collections', 'ion_auth' );
		$this->_configvalue = $this->config->item ( 'default' );
		$this->common_db = $this->config->item ( 'default' );
		
		$this->store_salt = $this->config->item ( 'store_salt', 'ion_auth' );
		$this->salt_length = $this->config->item ( 'salt_length', 'ion_auth' );
		
		// Initialize hash method directives (Bcrypt)
		$this->hash_method = $this->config->item ( 'hash_method', 'ion_auth' );
		
		// $this->common_db = $this->config->item('default');
		
		$this->screening_app_col = "healthcare201672020159570";
		$this->screening_app_col_screening = "healthcare201672020159570_screening_final";
		
		$this->absent_app_col = "healthcare2017120192713965";
		$this->bmi_app_col = "healthcare201761916814158";
		$this->request_app_col = "healthcare201610114435690";
		$this->sanitation_infrastructure_app_col = "";
		$this->sanitation_app_col = "healthcare2017121175645993";
		$this->hb_app_col = "tmreis_himglobin_report_col";
		$this->notes_col = "tmreis_ehr_notes";
		$this->today_date = date ( 'Y-m-d' );
	}
	public function statescount() {
		$count = $this->mongo_db->count ( 'tmreis_states' );
		return $count;
	}
	public function get_states($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_states' );
		return $query;
	}
	public function get_all_states() {
		
		/*$bmi_values = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID','history.last_stage.submitted_by'),array())->get("healthcare201761916814158");
		
		foreach($bmi_values as $index => $hospital_id)
		{
			$unique_id_bmi = $hospital_id['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
			
			if(strlen($unique_id_bmi) == 4)
			{
			$bmi_values_four = $this->mongo_db->select(array('doc_data.widget_data','history.last_stage.submitted_by'),array())->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id_bmi))->get("healthcare201761916814158");
			
				foreach($bmi_values_four as $hospital_id)
				{
					$old_unique_id = $hospital_id['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
					$history_id = $hospital_id['history']['last_stage']['submitted_by'];
					$unique_bmi = substr($history_id,0,-12);
					$unique_id = str_replace(".","_",$unique_bmi);
					$naresh = strtoupper($unique_id);
					$new_unique_id = $naresh."".$old_unique_id;
					
					$hospital_id['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $new_unique_id;
				
					$query = $this->mongo_db->where("_id", new MongoID($hospital_id["_id"]))->set($hospital_id)->update('healthcare201761916814158');
				}
				
				echo print_r($hospital_id,true);
				echo print_r($query,true);
			}
			
		}*/
		$query = $this->mongo_db->get ( 'tmreis_states' );
		return $query;
	}
	
	// =================================================
	public function distcount() {
		$count = $this->mongo_db->count ( 'tmreis_district' );
		return $count;
	}
	public function get_district($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_district' );
		foreach ( $query as $distlist => $dist ) {
			$st_name = $this->mongo_db->where ( '_id', new MongoId ( $dist ['st_name'] ) )->get ( 'tmreis_states' );
			if (isset ( $dist ['st_name'] )) {
				$query [$distlist] ['st_name'] = $st_name [0] ['st_name'];
			} else {
				$query [$distlist] ['st_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function get_all_district($dt_name = "All") {
		if ($dt_name == "All") {
			$query = $this->mongo_db->orderBy ( array (
					'dt_name' => 1 
			) )->get ( 'tmreis_district' );
		} else {
			$query = $this->mongo_db->where ( 'dt_name', $dt_name )->orderBy ( array (
					'dt_name' => 1 
			) )->get ( 'tmreis_district' );
		}
		
		return $query;
	}
	public function health_supervisorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_health_supervisors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function create_health_supervisors($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['health_supervisors_email'] );
		$password = $post ['health_supervisors_password'];
		
		// Check if email already exists
		if ($this->user_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		// New user document
		$data = array (
				"school_code" => intval($post ['school_code']),
				"hs_name" => $post ['health_supervisors_name'],
				"hs_mob" => $post ['health_supervisors_mob'],
				"hs_ph" => $post ['health_supervisors_ph'],
				"password" => $password,
				"email" => $email,
				"hs_addr" => $post ['health_supervisors_addr'],
				
				"username" => $post ['health_supervisors_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company' => $this->session->userdata ( "customer" )['company'] 
		);
		
		// Store salt in document?
		
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['tmreis_health_supervisors'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	
	// ///////////////////////////////////////////////////////////
	public function cc_users_count() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['tmreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_cc_users($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['tmreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function create_cc_user($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['email'] );
		$password = $post ['password'];
		
		// Check if email already exists
		if ($this->cc_user_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		// New user document
		$data = array (
				"name" => $post ['cc_user_name'],
				"mobile_number" => $post ['cc_user_mob'],
				"phone_number" => $post ['cc_user_ph'],
				"password" => $password,
				"email" => $email,
				"company_address" => $post ['cc_user_addr'],
				
				"username" => $post ['cc_user_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company_name' => $this->session->userdata ( "customer" )['company'] 
		);
		
		// Store salt in document?
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['tmreis_cc'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function delete_cc_user($cc_id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				"_id" => new MongoId ( $cc_id ) 
		) )->delete ( $this->collections ['tmreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	// ////////////////////////////////////////////////////////////
	public function doctorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['tmreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_doctors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['tmreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	public function get_all_doctors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	// ///////////////////////////////////////////////////////////////////
	public function schoolscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_schools($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function create_school($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['school_email'] );
		$password = $post ['school_password'];
		
		// Check if email already exists
		if ($this->school_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		$data = array (
				"dt_name" => $post ['dt_name'],
				"school_code" => intval($post ['school_code']),
				"school_name" => $post ['school_name'],
				"school_addr" => $post ['school_addr'],
				"password" => $password,
				"email" => $email,
				"school_ph" => $post ['school_ph'],
				"school_mob" => $post ['school_mob'],
				"contact_person_name" => $post ['contact_person_name'],
				
				"username" => $post ['school_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company' => $this->session->userdata ( "customer" )['company'] 
		);
		// Store salt in document?
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['tmreis_schools'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function get_all_schools() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function classescount() {
		$count = $this->mongo_db->count ( 'tmreis_classes' );
		return $count; 
	}
	public function get_classes($per_page, $page) 
	{
		//"NLG_62322_*","NLG_62326_*","RR_61514_*","RR_61517_*","MDK_61729_*","HYD_61605_*","RR_61523_*","MDCL_61524_*","MDCL_61525_*","WGLU_52119_*","WGLU_52120_*","JSKR_52123_*","WGL_52128_*","NLG_62315_*","NLG_62316_*","NLG_62319_*","NLG_62320_*","YDR_62325_*","YDR_62323_*","KMR_61803_*","KMR_61811_*","KMR_61809_*","KMR_61813_*","KMR_61801_*","KMR_61806_*","KMR_61810_*","WNPY_61443_*","MBNR_61442_*","HYD_500004_*","MBBD_52132_*","SRD_61735_*","HYD_500034_*","RR_61527_*","RR_61606_*","JGTL_52035_*","JSKR_52133_*","HYD_61608_*","SDPT_52109_*","MBBD_52111_*","WGLR_52112_*","JSKR_52113_*","JGN_52114_*","MBBD_52115_*","MBBD_52117_*","JGTL_52001_*","PDPL_52002_*","KMNR_52003_*","KMNR_52004_*","SDPT_52005_*","SDPT_52006_*","PDPL_52007_*","PDPL_52008_*","PDPL_52009_*","RJN_52010_*","PDPL_52011_*","RJN_52012_*","WGLU_52013_*","SRD_61723_*","MCRL_51923_*","MCRL_51924_*","NML_51927_*","ADB_51928_*","KMNR_52020_*","RJN_52022_*","JSKR_52024_*","KMNR_52026_*","PDPL_52028_*","JGTL_52031_*","KMM_52214_*","KMM_52215_*","KMM_52216_*","KMM_52217_*","KMM_52220_*","MBNR_61454_*","MBNR_61457_*","WNPY_61458_*","MBNR_61460_*","MBNR_61461_*","MBNR_61462_*","MBNR_61464_*","SDPT_61726_*","SDPT_61727_*","SDPT_61728_*","NLG_62313_*","NZD_61818_*","KMR_61819_*","NZD_61820_*","NZD_61821_*","NZD_61822_*","RR_61519_*","MDCL_61520_*","MDCL_61521_*","RR_61522_*","MDCL_61607_*","KMM_52222_*","MBNR_61465_*","NZD_61823_*","NZD_61825_*","WGLU_52131_*","WGLU_52130_*","NLG_62327_*","MDK_61734_*","SDPT_61733_*","KMNR_52033_*","RJN_52034_*","ADB_51931_*","MCRL_51930_*","JSKR_52127_*","MDK_61722_*","SRD_61725_*","JGTL_52023_*","JGTL_52032_*","JGTL_52030_*","KMR_61816_*","KMR_61817_*","RJN_52019_*","RJN_52027_*","SDPT_52021_*","SRPT_62317_*","SRPT_62314_*","SRPT_62324_*","SRPT_62318_*","KMR_61824_*","NGKL_61467_*","NML_51932_*","VKRD_61526_*","WNPY_61466_*","YDR_62329_*","SDPT_61724_*","SDPT_61731_*","SDPT_61732_*","BDD_52219_*","BDD_52218_*","BDD_52221_*","BDD_52223_*","GDW_61444_*","GDW_61445_*","GDW_61446_*","GDW_61459_*","JSKR_52126_*","KMB_51925_*","KMB_51929_*","MCRL_51922_*","MCRL_51926_*","NGKL_61447_*","NGKL_61448_*","NGKL_61449_*","NGKL_61451_*","NGKL_61452_*","SMBD_61515_*","SMBD_61516_*","SMBD_61518_*","SMBD_61450_*","SMBD_61455_*","VKRD_61456_*","VKRD_61513_*","WNPY_61453_*","WNPY_61463_*","WGLR_52121_*","WGLR_52122_*","WGLU_52129_*","MBBDL_52125_*","SRPT_62328_*","KMR_618008_*","MCRL_51907_*","MCRL_51910_*","MCRL_51906_*","ADB_51905_*","NML_51904_*","KMB_51903_*","MCRL_51920_*","KMB_51911_*","KMB_51901_*","ADB_51902_*","MCRL_51921_*","NML_51908_*","NML_51909_*","NML_51912_*","HYD_61603_*","HYD_61601_*","HYD_61602_*","HYD_61604_*","BDD_52201_*","BDD_52209_*","KMM_52202_*","BDD_52205_*","KMM_52204_*","KMM_52212_*","KMM_52208_*","BDD_52203_*","KMM_52207_*","KMM_52206_*","KMM_52213_*","KMM_52211_*","BDD_52210_*","WNPY_61407_*","MBNR_61401_*","MBNR_61402_*","MBNR_61403_*","MBNR_61404_*","MBNR_61406_*","MBNR_61408_*","GDW_61410_*","MBNR_61411_*","MBNR_61412_*","MBNR_61413_*","MBNR_61405_*","SRD_61701_*","SDPT_61702_*","SRD_61703_*","SRD_61704_*","SRD_61705_*","SRD_61706_*","SDPT_61707_*","MDK_61708_*","SRD_61709_*","SRD_61710_*","SRD_61713_*","SRD_61714_*","MDK_61715_*","SDPT_61716_*","SRD_61717_*","SDPT_61718_*","SDPT_61719_*","YDR_62309_*","YDR_62301_*","SRPT_62302_*","YDR_62303_*",

		/*	$school_codes = ["NLG_62304_*","SRPT_62305_*","NLG_62306_*","NLG_62307_*","SRPT_62308_*","YDR_62310_*","NLG_62311_*","SRPT_62312_*","NZB_61802_*","NZB_61804_*","NZB_61805_*","NZB_61807_*","NZB_61812_*","RR_61509_*","VKRD_61501_*","RR_61503_*","VKRD_61507_*","VKRD_61508_*","MDCL_61511_*","RR_61502_*","RR_61510_*","RR_61512_*","RR_61504_*","RR_61505_*","VKRD_61506_*","MBBD_52105_*","JGN_52116_*","WGLR_52118_*","WGLR_52102_*","JGN_52101_*","JSKR_52103_*","WGLU_52104_*","JGN_52106_*","WGLR_52107_*","WGLR_52108_*","RJN_52014_*","KMNR_52015_*","KMNR_52018_*","KMNR_52025_*","RJN_52029_*","JGN_52124_*","SDPT_61730_*"];

			//for($ind_modi=0;$ind_modi <= count($school_codes)-1;$ind_modi++)
			foreach ($school_codes as $school_code) {
				//echo print_r($code,TRUE);exit();
			
			$code = explode("_", $school_code);
			
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$query = $this->mongo_db->select(array('school_name'))->where(array('school_code' => intval($code[1])))->get($this->collections['panacea_schools']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			
			foreach ($query as $school_name) {
				
				$dist_name = explode(",", $school_name['school_name']);
			
			 $full_doc = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' =>array('$regex' => $school_code)))->get('healthcare2017617145744625');
			 foreach ($full_doc as $value) {
			 	$uID = $value['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
			 	//echo print_r($uID,TRUE);exit();
			 	$this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $uID))->set(array('doc_data.widget_data.school_details.School Name'=>$school_name['school_name'],'doc_data.widget_data.school_details.District' => $dist_name[1]))->update('healthcare2017617145744625');			 
			 }
			//echo print_r($search_uid_2,TRUE);exit();
			
			}		
			

			}*/

		/*for($ind_modi=0;$ind_modi <= count($school_codes)-1;$ind_modi++){
			$code = intval($school_codes[$ind_modi]);
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$query = $this->mongo_db->select(array('contact_person_name','school_mob'))->where('school_code',$code)->get($this->collections['panacea_schools']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			$search_uid = array('Student Unique ID' => array('$regex' => $school_codes[$ind_modi]));
			$get_hs_info = $this->mongo_db->select(array('Student Unique ID','principal_name','principal_mob'))->where($search_uid)->get('hb_sms_count_col');
			//echo print_r($get_hs_info,TRUE);exit();
			foreach ($get_hs_info as $value) {
				//echo print_r($value,TRUE);exit();
				$hs_name = $query[0]['contact_person_name'];
				$hs_mob = $query[0]['school_mob'];
				$unique_id = $value['Student Unique ID'];
				$search_uid_2 = array('Student Unique ID' => array('$regex' => $unique_id));

			$this->mongo_db->where($search_uid_2)->set(array('school_details.0.principal_name'=>$hs_name,'school_details.0.principal_mob' => $hs_mob))->update('hb_sms_count_col');
			}

			}*/
		/*$unique_id = "BKMR_4304_";
		$correct_id = "BKMM_4304_";
		//====================screening collection ==============
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get('healthcare201812217594045');
		
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
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update('healthcare201812217594045');
		//echo print_r($query,true);
		//echo print_r($doc,true);
		//exit();
		}
		}
		}
		exit();*/
		
		/*$unique_ids = ["MADB_161_", "MHYD-257-", "MHYD_128_", "MHYD_133_", "MJGTL_120_", "MKMB_164_", "MKMM_157_", "MKMM_159_", "MKMNR_117_", "MKMR_143_", "MKMR_147_", "MMDCL_168_", "MMDK_100_", "MNLG_113_", "MNML_163_", "MNZD_146_", "MNZD_196_", "MPDPL_119_", "MRJN_122_", "MRR_138_", "MSDPT_105_", "MSDPT_106_", "MSRD_102_", "MSRD_104_", "MSRD_107_", "MSRPT_110_", "MWGL_126_"];

		$correct_ids = ["MADB_161_", "MHYD_257_", "MHYD_128_", "MHYD_133_", "MJGTL_120_", "MKMB_164_", "MKMM_157_", "MKMM_159_", "MKMNR_117_", "MKMR_143_", "MKMR_147_", "MMDCL_168_", "MMDK_100_", "MNLG_113_", "MNML_163_", "MNZD_146_", "MNZD_196_", "MPDPL_119_", "MRJN_122_", "MRR_138_", "MSDPT_105_", "MSDPT_106_", "MSRD_102_", "MSRD_104_", "MSRD_107_", "MSRPT_110_", "MWGL_126_"];

		$districts = ["ADILABAD","HYDERABAD","HYDERABAD","HYDERABAD","JAGTIAL","KOMARAM BHEEM","KHAMMAM","KHAMMAM","KARIMNAGAR","KAMAREDDY","KAMAREDDY","MEDCHAL","SANGAREDDY","NALGONDA","NIRMAL","NIZAMABAD","NIZAMABAD","PEDDAPALLY","RAJANNA","RANGAREDDY","SIDDIPET","SIDDIPET","SANGAREDDY","SANGAREDDY","SANGAREDDY","SURYAPET","WARANGAL"];

		$new_schools_names = ["TMREIS ADILABAD(B),ADILABAD", "TMREIS MUSHEERABAD(B),HYDERABAD", "TMREIS BAHADURPURA(B),HYDERABAD", "TMREIS SAIDABAD(B),HYDERABAD", "TMREIS JAGTIAL(G),JAGTIAL", "TMREIS KAGAZNAGAR(B),KOMARAM BHEEM", "TMREIS KHAMMAM(B),KHAMMAM", "TMREIS SATHUPALLI(B),KHAMMAM", "TMREIS HUZURABAD(B),KARIMNAGAR", "TMREIS BANSWADA(G),KAMAREDDY", "TMREIS YELLAREDDY(B),KAMAREDDY", "TMREIS QUTBULLAPUR(B),MEDCHAL", "TMREIS PATANCHERU(B),SANGAREDDY", "TMREIS DEVERKONDA(B),NALGONDA", "TMREIS BHAINSA(B),NIRMAL", "TMREIS NIZAMABAD(G),NIZAMABAD", "TMREIS RANJAL(G),NIZAMABAD", "TMREIS RAMAGUNDAM(B),PEDDAPALLY", "TMREIS SIRCILLA(G),RAJANNA", "TMREIS RAJENDRANAGAR(G),RANGAREDDY", "TMREIS SIDDIPET(B),SIDDIPET", "TMREIS DUBBAKA(B),SIDDIPET", "TMREIS SADASIVPET(B),SANGAREDDY", "TMREIS ANDOLE(B),SANGAREDDY", "TMREIS NARAYANKHED(G),SANGAREDDY", "TMREIS SURYAPET(B),SURYAPET", "TMREIS WARANGAL(G),WARANGAL"];


		for($ind_modi=0;$ind_modi <= count($unique_ids)-1;$ind_modi++){
			echo $ind_modi;
			echo "/////////////";
			echo $unique_ids[$ind_modi];
			
			$unique_id = $unique_ids[$ind_modi];
			$correct_id = $correct_ids[$ind_modi];
			//$old_school_name = $old_school_names[$ind_modi];
			$correct_school_name = $new_schools_names[$ind_modi];
			$district = $districts[$ind_modi];
			//$email = $emails[$ind_modi];
			//$new_email = $new_emails[$ind_modi];
			//$dt_name = $dt_names[$ind_modi];
			

		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Details.Hospital Unique ID',$unique_id)->get($this->bmi_app_col );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'])){
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'], $unique_id);
			
			if($nlg_pos !== false){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']));

				$new_id = $correct_id.$unique_cut;
				
		$doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $new_id;
		$doc['doc_data']['School Details']['District'] = $district;
		$doc['doc_data']['School Details']['School Name'] = $correct_school_name;
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->bmi_app_col  );
		//echo print_r($doc["_id"],true);
		//echo print_r($doc,true);
		//exit();
		}
		}
		}
		}*/
		
	/*
		//=======================================================START OF HS
		
		$school_id = 'madb.175.hs#gmail.com,madb.176.hs#gmail.com,madb.177.hs#gmail.com,madb.178.hs#gmail.com,mnml.179.hs#gmail.com,mnml.180.hs#gmail.com,mkmb.181.hs#gmail.com,mkmb.182.hs#gmail.com,mmcrl.183.hs#gmail.com,mmcrl.184.hs#gmail.com,mnzd.185.hs#gmail.com,mnzd.186.hs#gmail.com,mnzd.187.hs#gmail.com,mnzd.188.hs#gmail.com,mnzd.189.hs#gmail.com,mnzd.190.hs#gmail.com,mnzd.191.hs#gmail.com,mnzd.192.hs#gmail.com,mnzd.193.hs#gmail.com,mnzd.194.hs#gmail.com,mnzd.195.hs#gmail.com,mnzd.196.hs#gmail.com,mkmr.197.hs#gmail.com,mkmr.198.hs#gmail.com,mkmr.199.hs#gmail.com,mkmnr.200.hs#gmail.com,mkmnr.201.hs#gmail.com,mkmnr.202.hs#gmail.com,mkmnr.203.hs#gmail.com,mkmnr.204.hs#gmail.com,mkmnr.205.hs#gmail.com,mpdpl.206.hs#gmail.com,mjgtl.207.hs#gmail.com,mjgtl.208.hs#gmail.com,mjgtl.209.hs#gmail.com,mrjn.210.hs#gmail.com,mbdd.211.hs#gmail.com,mbdd.212.hs#gmail.com,mbdd.213.hs#gmail.com,mbdd.214.hs#gmail.com,mkmm.215.hs#gmail.com,mkmm.216.hs#gmail.com,mkmm.217.hs#gmail.com,mkmm.218.hs#gmail.com,msrd.219.hs#gmail.com,msrd.220.hs#gmail.com,msrd.221.hs#gmail.com,msrd.222.hs#gmail.com,msdpt.223.hs#gmail.com,msdpt.224.hs#gmail.com,msdpt.225.hs#gmail.com,mmbnr.226.hs#gmail.com,mmbnr.227.hs#gmail.com,mmbnr.228.hs#gmail.com,mmbnr.229.hs#gmail.com,mmbnr.230.hs#gmail.com,mmbnr.231.hs#gmail.com,mmbnr.232.hs#gmail.com,mgdw.233.hs#gmail.com,mngkl.234.hs#gmail.com,mngkl.235.hs#gmail.com,mwnpy.236.hs#gmail.com,mhyd.237.hs#gmail.com,mhyd.238.hs#gmail.com,mhyd.239.hs#gmail.com,mhyd.240.hs#gmail.com,mhyd.241.hs#gmail.com,mhyd.242.hs#gmail.com,mhyd.243.hs#gmail.com,mhyd.244.hs#gmail.com,mhyd.245.hs#gmail.com,mhyd.246.hs#gmail.com,mhyd.247.hs#gmail.com,mhyd.248.hs#gmail.com,mhyd.249.hs#gmail.com,mhyd.250.hs#gmail.com,mhyd.251.hs#gmail.com,mhyd.252.hs#gmail.com,mhyd.253.hs#gmail.com,mhyd.254.hs#gmail.com,mhyd.255.hs#gmail.com,mhyd.256.hs#gmail.com,mhyd.257.hs#gmail.com,mhyd.258.hs#gmail.com,mhyd.259.hs#gmail.com,mhyd.260.hs#gmail.com,mhyd.261.hs#gmail.com,mhyd.262.hs#gmail.com,mhyd.263.hs#gmail.com,mhyd.264.hs#gmail.com,mhyd.265.hs#gmail.com,mhyd.266.hs#gmail.com,mhyd.267.hs#gmail.com,mrr.268.hs#gmail.com,mrr.269.hs#gmail.com,mrr.270.hs#gmail.com,mrr.271.hs#gmail.com,mmdcl.272.hs#gmail.com,mmdcl.273.hs#gmail.com,mmdcl.274.hs#gmail.com,mmdcl.275.hs#gmail.com,mvkrd.276.hs#gmail.com,mvkrd.277.hs#gmail.com,mvkrd.278.hs#gmail.com,mnlg.279.hs#gmail.com,mnlg.280.hs#gmail.com,msrpt.281.hs#gmail.com,msrpt.282.hs#gmail.com,mydr.283.hs#gmail.com,mydr.284.hs#gmail.com,mwglr.285.hs#gmail.com,mwglr.286.hs#gmail.com,mwglr.287.hs#gmail.com,mwglu.288.hs#gmail.com,mwglu.289.hs#gmail.com,mwglu.290.hs#gmail.com,mmbbd.291.hs#gmail.com,mmbbd.292.hs#gmail.com,mjgn.293.hs#gmail.com,mjskr.294.hs#gmail.com,mjskr.295.hs#gmail.com,mhyd.296.hs#gmail.com,mrr.297.hs#gmail.com,mrr.298.hs#gmail.com,mnzd.299.hs#gmail.com,mkmr.300.hs#gmail.com,mnlg.301.hs#gmail.com,mnlg.302.hs#gmail.com,mwgl.303.hs#gmail.com,mmbnr.304.hs#gmail.com,mwnpy.305.hs#gmail.com,msrd.306.hs#gmail.com,msrd.307.hs#gmail.com,mhyd.308.hs#gmail.com,mnzd.309.hs#gmail.com';
		$schoolObj = explode(",",$school_id);
		echo print_r($schoolObj,true);
		foreach($schoolObj as $id)
		{
			
			//==================================================================== Attendence app ===============================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : {"pages" : {    "1" : { "Attendence Details" : {   "District" : {"type" : "select","size" : "1","required" : "TRUE","key" : "TRUE","description" : "","option_choose_one" : "TRUE","with_translations" : "FALSE","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [{"text" : "ADILABAD","selected" : "TRUE","value" : "ADILABAD"},{"text" : "BHADRADRI","selected" : "FALSE","value" : "BHADRADRI"},{"text" : "GADWAL","selected" : "FALSE","value" : "GADWAL"},{"text" : "HYDERABAD","selected" : "FALSE","value" : "HYDERABAD"},{"text" : "JAGTIAL","selected" : "FALSE","value" : "JAGTIAL"},{"text" : "JANGOAN","selected" : "FALSE","value" : "JANGOAN"},{"text" : "JAYASHANKAR","selected" : "FALSE","value" : "JAYASHANKAR"},{"text" : "KAMAREDDY","selected" : "FALSE","value" : "KAMAREDDY"},{"text" : "KARIMNAGAR","selected" : "FALSE","value" : "KARIMNAGAR"},{"text" : "KHAMMAM","selected" : "FALSE","value" : "KHAMMAM"},{"text" : "KOMARAM BHEEM","selected" : "FALSE","value" : "KOMARAM BHEEM"},{"text" : "MAHABUBNAGAR","selected" : "FALSE","value" : "MAHABUBNAGAR"},{"text" : "MAHBOOBABAD","selected" : "FALSE","value" : "MAHBOOBABAD"},{"text" : "MANCHERIAL","selected" : "FALSE","value" : "MANCHERIAL"},{"text" : "MEDAK","selected" : "FALSE","value" : "MEDAK"},{"text" : "MEDCHAL","selected" : "FALSE","value" : "MEDCHAL"},{"text" : "NAGARKURNOOL","selected" : "FALSE","value" : "NAGARKURNOOL"},{"text" : "NALGONDA","selected" : "FALSE","value" : "NALGONDA"},{"text" : "NIRMAL","selected" : "FALSE","value" : "NIRMAL"},{"text" : "NIZAMABAD","selected" : "FALSE","value" : "NIZAMABAD"},{"text" : "PEDDAPALLY","selected" : "FALSE","value" : "PEDDAPALLY"},{"text" : "RAJANNA","selected" : "FALSE","value" : "RAJANNA"},{"text" : "RANGAREDDY","selected" : "FALSE","value" : "RANGAREDDY"},{"text" : "SANGAREDDY","selected" : "FALSE","value" : "SANGAREDDY"},{"text" : "SIDDIPET","selected" : "FALSE","value" : "SIDDIPET"},{"text" : "SURYAPET","selected" : "FALSE","value" : "SURYAPET"},{"text" : "VIKARABAD","selected" : "FALSE","value" : "VIKARABAD"},{"text" : "WANAPARTHY","selected" : "FALSE","value" : "WANAPARTHY"},{"text" : "WARANGAL","selected" : "FALSE","value" : "WARANGAL"},{"text" : "WARANGAL RURAL","selected" : "FALSE","value" : "WARANGAL RURAL"},{"text" : "WARANGAL URBAN","selected" : "FALSE","value" : "WARANGAL URBAN"},{"text" : "YADADRI","selected" : "FALSE","value" : "YADADRI"}]  },   "Select School" : {"type" : "select","size" : "1","required" : "TRUE","key" : "TRUE","description" : "","option_choose_one" : "TRUE","with_translations" : "FALSE","order" : 2,"multilanguage" : "FALSE","notify" : "true","options" :  [{"text" : "TMREIS ADILABAD(B),ADILABAD","selected" : "TRUE","value" : "TMREIS ADILABAD(B),ADILABAD"},{"text" : "TMREIS ADILABAD(G),ADILABAD","selected" : "TRUE","value" : "TMREIS ADILABAD(G),ADILABAD"},{"text" : "TMREIS ADILABAD(B-2),ADILABAD","selected" : "TRUE","value" : "TMREIS ADILABAD(B-2),ADILABAD"},{"text" : "TMREIS ADILABAD(G-2),ADILABAD","selected" : "FALSE","value" : "TMREIS ADILABAD(G-2),ADILABAD"},{"text" : "TMREIS BOATH(B),ADILABAD","selected" : "FALSE","value" : "TMREIS BOATH(B),ADILABAD"},{"text" : "TMREIS UTNOOR(B),ADILABAD","selected" : "FALSE","value" : "TMREIS UTNOOR(B),ADILABAD"},{"text" : "TMREIS ASWARAOPETA(G),BHADRADRI","selected" : "FALSE","value" : "TMREIS ASWARAOPETA(G),BHADRADRI"},{"text" : "TMREIS BHADRACHALAM(B),BHADRADRI","selected" : "FALSE","value" : "TMREIS BHADRACHALAM(B),BHADRADRI"},{"text" : "TMREIS BURGAMPAHAD(G),BHADRADRI","selected" : "FALSE","value" : "TMREIS BURGAMPAHAD(G),BHADRADRI"},{"text" : "TMREIS KOTHAGUDEM(B),BHADRADRI","selected" : "FALSE","value" : "TMREIS KOTHAGUDEM(B),BHADRADRI"},{"text" : "TMREIS KOTHAGUDEM(G),BHADRADRI","selected" : "FALSE","value" : "TMREIS KOTHAGUDEM(G),BHADRADRI"},{"text" : "TMREIS YELLANDU(B),BHADRADRI","selected" : "FALSE","value" : "TMREIS YELLANDU(B),BHADRADRI"},{"text" : "TMREIS ALAMPUR(G),GADWAL","selected" : "FALSE","value" : "TMREIS ALAMPUR(G),GADWAL"},{"text" : "TMREIS GADWAL(G),GADWAL","selected" : "FALSE","value" : "TMREIS GADWAL(G),GADWAL"},{"text" : "TMREIS AMBERPET(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS AMBERPET(B),HYDERABAD"},{"text" : "TMREIS ASIFNAGAR(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS ASIFNAGAR(B),HYDERABAD"},{"text" : "TMREIS ASIFNAGAR(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS ASIFNAGAR(G),HYDERABAD"},{"text" : "TMREIS ASIFNAGAR(B-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS ASIFNAGAR(B-2),HYDERABAD"},{"text" : "TMREIS ASIFNAGAR(G-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS ASIFNAGAR(G-2),HYDERABAD"},{"text" : "TMREIS BAHADURPURA(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS BAHADURPURA(B),HYDERABAD"},{"text" : "TMREIS BAHADURPURA(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS BAHADURPURA(G),HYDERABAD"},{"text" : "TMREIS BAHADURPURA(B-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS BAHADURPURA(B-2),HYDERABAD"},{"text" : "TMREIS BAHADURPURA(G-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS BAHADURPURA(G-2),HYDERABAD"},{"text" : "TMREIS BARKAS(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS BARKAS(B),HYDERABAD"},{"text" : "TMREIS BARKAS(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS BARKAS(G),HYDERABAD"},{"text" : "TMREIS CHANDRAYANGUTTA(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHANDRAYANGUTTA(B),HYDERABAD"},{"text" : "TMREIS CHANDRAYANGUTTA(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHANDRAYANGUTTA(G),HYDERABAD"},{"text" : "TMREIS CHARMINAR(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHARMINAR(B),HYDERABAD"},{"text" : "TMREIS CHARMINAR(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHARMINAR(G),HYDERABAD"},{"text" : "TMREIS CHARMINAR(B-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHARMINAR(B-2),HYDERABAD"},{"text" : "TMREIS CHARMINAR(G-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS CHARMINAR(G-2),HYDERABAD"},{"text" : "TMREIS GOLCONDA(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS GOLCONDA(B),HYDERABAD"},{"text" : "TMREIS GOLCONDA(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS GOLCONDA(G),HYDERABAD"},{"text" : "TMREIS GOLCONDA(G-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS GOLCONDA(G-2),HYDERABAD"},{"text" : "TMREIS GOSHAMAHAL(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS GOSHAMAHAL(B),HYDERABAD"},{"text" : "TMREIS GOSHAMAHAL(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS GOSHAMAHAL(G),HYDERABAD"},{"text" : "TMREIS JUBLIHILLS(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS JUBLIHILLS(B),HYDERABAD"},{"text" : "TMREIS JUBLIHILLS(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS JUBLIHILLS(G),HYDERABAD"},{"text" : "TMREIS KHAIRTABAD(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS KHAIRTABAD(B),HYDERABAD"},{"text" : "TMREIS KHAIRTABAD(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS KHAIRTABAD(G),HYDERABAD"},{"text" : "TMREIS L.B NAGAR(RJC),HYDERABAD","selected" : "FALSE","value" : "TMREIS L.B NAGAR(RJC),HYDERABAD"},{"text" : "TMREIS MUSHEERABAD(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS MUSHEERABAD(B),HYDERABAD"},{"text" : "TMREIS MUSHEERABAD(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS MUSHEERABAD(G),HYDERABAD"},{"text" : "TMREIS MOULALI(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS MOULALI(G),HYDERABAD"},{"text" : "TMREIS SAIDABAD(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS SAIDABAD(B),HYDERABAD"},{"text" : "TMREIS SAIDABAD(B-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS SAIDABAD(B-2),HYDERABAD"},{"text" : "TMREIS SAIDABAD(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS SAIDABAD(G),HYDERABAD"},{"text" : "TMREIS SANATHNAGAR(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS SANATHNAGAR(B),HYDERABAD"},{"text" : "TMREIS SANATHNAGAR(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS SANATHNAGAR(G),HYDERABAD"},{"text" : "TMREIS SECUNDERABAD(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS SECUNDERABAD(B),HYDERABAD"},{"text" : "TMREIS SECUNDERABAD CANTONMENT(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS SECUNDERABAD CANTONMENT(G),HYDERABAD"},{"text" : "TMREIS YAKATPURA(B),HYDERABAD","selected" : "FALSE","value" : "TMREIS YAKATPURA(B),HYDERABAD"},{"text" : "TMREIS YAKATPURA(G),HYDERABAD","selected" : "FALSE","value" : "TMREIS YAKATPURA(G),HYDERABAD"},{"text" : "TMREIS YAKATPURA(B-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS YAKATPURA(B-2),HYDERABAD"},{"text" : "TMREIS YAKATPURA(G-2),HYDERABAD","selected" : "FALSE","value" : "TMREIS YAKATPURA(G-2),HYDERABAD"},{"text" : "TMREIS DHARMAPURI(G),JAGTIAL","selected" : "FALSE","value" : "TMREIS DHARMAPURI(G),JAGTIAL"},{"text" : "TMREIS JAGTIAL(B),JAGTIAL","selected" : "FALSE","value" : "TMREIS JAGTIAL(B),JAGTIAL"},{"text" : "TMREIS JAGTIAL(G),JAGTIAL","selected" : "FALSE","value" : "TMREIS JAGTIAL(G),JAGTIAL"},{"text" : "TMREIS KORUTLA(B),JAGTIAL","selected" : "FALSE","value" : "TMREIS KORUTLA(B),JAGTIAL"},{"text" : "TMREIS METPALLY(B),JAGTIAL","selected" : "FALSE","value" : "TMREIS METPALLY(B),JAGTIAL"},{"text" : "TMREIS GHANPUR(G),JANGOAN","selected" : "FALSE","value" : "TMREIS GHANPUR(G),JANGOAN"},{"text" : "TMREIS JANGAOAN(B),JANGOAN","selected" : "FALSE","value" : "TMREIS JANGAOAN(B),JANGOAN"},{"text" : "TMREIS BHUPALAPALLI(B),JAYASHANKAR","selected" : "FALSE","value" : "TMREIS BHUPALAPALLI(B),JAYASHANKAR"},{"text" : "TMREIS MULUG(G),JAYASHANKAR","selected" : "FALSE","value" : "TMREIS MULUG(G),JAYASHANKAR"},{"text" : "TMREIS BANSWADA(G),KAMAREDDY","selected" : "FALSE","value" : "TMREIS BANSWADA(G),KAMAREDDY"},{"text" : "TMREIS JUKKAL(B),KAMAREDDY","selected" : "FALSE","value" : "TMREIS JUKKAL(B),KAMAREDDY"},{"text" : "TMREIS KAMAREDDY(B),KAMAREDDY","selected" : "FALSE","value" : "TMREIS KAMAREDDY(B),KAMAREDDY"},{"text" : "TMREIS KAMAREDDY(G),KAMAREDDY","selected" : "FALSE","value" : "TMREIS KAMAREDDY(G),KAMAREDDY"},{"text" : "TMREIS LINGAMPET(B),KAMAREDDY","selected" : "FALSE","value" : "TMREIS LINGAMPET(B),KAMAREDDY"},{"text" : "TMREIS YELLAREDDY(B),KAMAREDDY","selected" : "FALSE","value" : "TMREIS YELLAREDDY(B),KAMAREDDY"},{"text" : "TMREIS BOMMAKAL(B),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS BOMMAKAL(B),KARIMNAGAR"},{"text" : "TMREIS CHOPPADANDI(G),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS CHOPPADANDI(G),KARIMNAGAR"},{"text" : "TMREIS HUZURABAD(B),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS HUZURABAD(B),KARIMNAGAR"},{"text" : "TMREIS JAMIKUNTA(G),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS JAMIKUNTA(G),KARIMNAGAR"},{"text" : "TMREIS KARIMNAGAR(B-2),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS KARIMNAGAR(B-2),KARIMNAGAR"},{"text" : "TMREIS KARIMNAGAR(B-3),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS KARIMNAGAR(B-3),KARIMNAGAR"},{"text" : "TMREIS KARIMNAGAR(G-2),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS KARIMNAGAR(G-2),KARIMNAGAR"},{"text" : "TMREIS MANAKONDUR(B),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS MANAKONDUR(B),KARIMNAGAR"},{"text" : "TMREIS REKURTHI(G),KARIMNAGAR","selected" : "FALSE","value" : "TMREIS REKURTHI(G),KARIMNAGAR"},{"text" : "TMREIS KHAMMAM(B),KHAMMAM","selected" : "FALSE","value" : "TMREIS KHAMMAM(B),KHAMMAM"},{"text" : "TMREIS KHAMMAM(G),KHAMMAM","selected" : "FALSE","value" : "TMREIS KHAMMAM(G),KHAMMAM"},{"text" : "TMREIS KHAMMAM(G-2),KHAMMAM","selected" : "FALSE","value" : "TMREIS KHAMMAM(G-2),KHAMMAM"},{"text" : "TMREIS MADHIRA(B),KHAMMAM","selected" : "FALSE","value" : "TMREIS MADHIRA(B),KHAMMAM"},{"text" : "TMREIS NELKONDAPALLI(B),KHAMMAM","selected" : "FALSE","value" : "TMREIS NELKONDAPALLI(B),KHAMMAM"},{"text" : "TMREIS RICOB BAZAR(G),KHAMMAM","selected" : "FALSE","value" : "TMREIS RICOB BAZAR(G),KHAMMAM"},{"text" : "TMREIS SATHUPALLI(B),KHAMMAM","selected" : "FALSE","value" : "TMREIS SATHUPALLI(B),KHAMMAM"},{"text" : "TMREIS WYRA(G),KHAMMAM","selected" : "FALSE","value" : "TMREIS WYRA(G),KHAMMAM"},{"text" : "TMREIS KAGAZNAGAR(B),KOMARAM BHEEM","selected" : "FALSE","value" : "TMREIS KAGAZNAGAR(B),KOMARAM BHEEM"},{"text" : "TMREIS ASIFABAD(G),KOMARAM BHEEM","selected" : "FALSE","value" : "TMREIS ASIFABAD(G),KOMARAM BHEEM"},{"text" : "TMREIS KAGAZNAGAR(B-2),KOMARAM BHEEM","selected" : "FALSE","value" : "TMREIS KAGAZNAGAR(B-2),KOMARAM BHEEM"},{"text" : "TMREIS DEVERKADRA(B),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS DEVERKADRA(B),MAHABUBNAGAR"},{"text" : "TMREIS DEVERKADRA(B-2),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS DEVERKADRA(B-2),MAHABUBNAGAR"},{"text" : "TMREIS FAROOQNAGAR(G),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS FAROOQNAGAR(G),MAHABUBNAGAR"},{"text" : "TMREIS JADCHERLA(B),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS JADCHERLA(B),MAHABUBNAGAR"},{"text" : "TMREIS JADCHERLA(G),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS JADCHERLA(G),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(B),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(B),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(B-2),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(B-2),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(B-3),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(B-3),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(G),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(G),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(G-2),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(G-2),MAHABUBNAGAR"},{"text" : "TMREIS MAHABUBNAGAR(G-3),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAHABUBNAGAR(G-3),MAHABUBNAGAR"},{"text" : "TMREIS MAKTHAL(G),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS MAKTHAL(G),MAHABUBNAGAR"},{"text" : "TMREIS NARAYANPET(B),MAHABUBNAGAR","selected" : "FALSE","value" : "TMREIS NARAYANPET(B),MAHABUBNAGAR"},{"text" : "TMREIS DORNAKAL(G),MAHBOOBABAD","selected" : "FALSE","value" : "TMREIS DORNAKAL(G),MAHBOOBABAD"},{"text" : "TMREIS MAHABOOBABAD(B),MAHBOOBABAD","selected" : "FALSE","value" : "TMREIS MAHABOOBABAD(B),MAHBOOBABAD"},{"text" : "TMREIS THORRUR(G),MAHBOOBABAD","selected" : "FALSE","value" : "TMREIS THORRUR(G),MAHBOOBABAD"},{"text" : "TMREIS BELLAMPALLY(G),MANCHERIAL","selected" : "FALSE","value" : "TMREIS BELLAMPALLY(G),MANCHERIAL"},{"text" : "TMREIS CHENNUR(B),MANCHERIAL","selected" : "FALSE","value" : "TMREIS CHENNUR(B),MANCHERIAL"},{"text" : "TMREIS MANCHIRIAL(G),MANCHERIAL","selected" : "FALSE","value" : "TMREIS MANCHIRIAL(G),MANCHERIAL"},{"text" : "TMREIS INDRA NAGAR(G),MEDAK","selected" : "FALSE","value" : "TMREIS INDRA NAGAR(G),MEDAK"},{"text" : "TMREIS SANGAREDDY(G),MEDAK","selected" : "FALSE","value" : "TMREIS SANGAREDDY(G),MEDAK"},{"text" : "TMREIS NARSAPUR(B),MEDAK","selected" : "FALSE","value" : "TMREIS NARSAPUR(B),MEDAK"},{"text" : "TMREIS BALANAGAR(B),MEDCHAL","selected" : "FALSE","value" : "TMREIS BALANAGAR(B),MEDCHAL"},{"text" : "TMREIS BALANAGAR(G),MEDCHAL","selected" : "FALSE","value" : "TMREIS BALANAGAR(G),MEDCHAL"},{"text" : "TMREIS MEDCHAL(B),MEDCHAL","selected" : "FALSE","value" : "TMREIS MEDCHAL(B),MEDCHAL"},{"text" : "TMREIS MEDCHAL(G),MEDCHAL","selected" : "FALSE","value" : "TMREIS MEDCHAL(G),MEDCHAL"},{"text" : "TMREIS MALKAJGIRI(G),MEDCHAL","selected" : "FALSE","value" : "TMREIS MALKAJGIRI(G),MEDCHAL"},{"text" : "TMREIS QUTBULLAPUR(B),MEDCHAL","selected" : "FALSE","value" : "TMREIS QUTBULLAPUR(B),MEDCHAL"},{"text" : "TMREIS QUTBULLAPUR(G),MEDCHAL","selected" : "FALSE","value" : "TMREIS QUTBULLAPUR(G),MEDCHAL"},{"text" : "TMREIS UPPAL(G),MEDCHAL","selected" : "FALSE","value" : "TMREIS UPPAL(G),MEDCHAL"},{"text" : "TMREIS ACHAMPET(B),NAGARKURNOOL","selected" : "FALSE","value" : "TMREIS ACHAMPET(B),NAGARKURNOOL"},{"text" : "TMREIS KALWAKURTHY(G),NAGARKURNOOL","selected" : "FALSE","value" : "TMREIS KALWAKURTHY(G),NAGARKURNOOL"},{"text" : "TMREIS KOLLAPUR(G),NAGARKURNOOL","selected" : "FALSE","value" : "TMREIS KOLLAPUR(G),NAGARKURNOOL"},{"text" : "TMREIS NAGARKURNOOL(B),NAGARKURNOOL","selected" : "FALSE","value" : "TMREIS NAGARKURNOOL(B),NAGARKURNOOL"},{"text" : "TMREIS ANUMULA(B),NALGONDA","selected" : "FALSE","value" : "TMREIS ANUMULA(B),NALGONDA"},{"text" : "TMREIS DEVERKONDA(B),NALGONDA","selected" : "FALSE","value" : "TMREIS DEVERKONDA(B),NALGONDA"},{"text" : "TMREIS MIRYALAGUDA(G),NALGONDA","selected" : "FALSE","value" : "TMREIS MIRYALAGUDA(G),NALGONDA"},{"text" : "TMREIS NAKREKAL(G),NALGONDA","selected" : "FALSE","value" : "TMREIS NAKREKAL(G),NALGONDA"},{"text" : "TMREIS NALGONDA(B),NALGONDA","selected" : "FALSE","value" : "TMREIS NALGONDA(B),NALGONDA"},{"text" : "TMREIS NALGONDA(G),NALGONDA","selected" : "FALSE","value" : "TMREIS NALGONDA(G),NALGONDA"},{"text" : "TMREIS BHAINSA(B),NIRMAL","selected" : "FALSE","value" : "TMREIS BHAINSA(B),NIRMAL"},{"text" : "TMREIS KHANAPUR(B),NIRMAL","selected" : "FALSE","value" : "TMREIS KHANAPUR(B),NIRMAL"},{"text" : "TMREIS MUDOLE(G),NIRMAL","selected" : "FALSE","value" : "TMREIS MUDOLE(G),NIRMAL"},{"text" : "TMREIS NIRMAL(B),NIRMAL","selected" : "FALSE","value" : "TMREIS NIRMAL(B),NIRMAL"},{"text" : "TMREIS NIRMAL(G),NIRMAL","selected" : "FALSE","value" : "TMREIS NIRMAL(G),NIRMAL"},{"text" : "TMREIS ARMOOR(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS ARMOOR(B),NIZAMABAD"},{"text" : "TMREIS ARMOOR(G),NIZAMABAD","selected" : "FALSE","value" : "TMREIS ARMOOR(G),NIZAMABAD"},{"text" : "TMREIS BALKONDA(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS BALKONDA(B),NIZAMABAD"},{"text" : "TMREIS BALKONDA(G),NIZAMABAD","selected" : "FALSE","value" : "TMREIS BALKONDA(G),NIZAMABAD"},{"text" : "TMREIS BODHAN(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS BODHAN(B),NIZAMABAD"},{"text" : "TMREIS BODHAN(G),NIZAMABAD","selected" : "FALSE","value" : "TMREIS BODHAN(G),NIZAMABAD"},{"text" : "TMREIS DITCHPALLY(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS DITCHPALLY(B),NIZAMABAD"},{"text" : "TMREIS KOTAGIRI(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS KOTAGIRI(B),NIZAMABAD"},{"text" : "TMREIS NAGARAM(B),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NAGARAM(B),NIZAMABAD"},{"text" : "TMREIS NAGARAM(RJC),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NAGARAM(RJC),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(G),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(G),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(B-2),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(B-2),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(B-3),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(B-3),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(B-4),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(B-4),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(G-2),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(G-2),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(G-3),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(G-3),NIZAMABAD"},{"text" : "TMREIS NIZAMABAD(G-4),NIZAMABAD","selected" : "FALSE","value" : "TMREIS NIZAMABAD(G-4),NIZAMABAD"},{"text" : "TMREIS RANJAL(G),NIZAMABAD","selected" : "FALSE","value" : "TMREIS RANJAL(G),NIZAMABAD"},{"text" : "TMREIS MANTHANI(G),PEDDAPALLI","selected" : "FALSE","value" : "TMREIS MANTHANI(G),PEDDAPALLI"},{"text" : "TMREIS PEDDAPALLE(G),PEDDAPALLY","selected" : "FALSE","value" : "TMREIS PEDDAPALLE(G),PEDDAPALLY"},{"text" : "TMREIS RAMAGUNDAM(B),PEDDAPALLY","selected" : "FALSE","value" : "TMREIS RAMAGUNDAM(B),PEDDAPALLY"},{"text" : "TMREIS SIRCILLA(G),RAJANNA","selected" : "FALSE","value" : "TMREIS SIRCILLA(G),RAJANNA"},{"text" : "TMREIS VEMULAWADA(B),RAJANNA","selected" : "FALSE","value" : "TMREIS VEMULAWADA(B),RAJANNA"},{"text" : "TMREIS BALAPUR(B),RANGAREDDY","selected" : "FALSE","value" : "TMREIS BALAPUR(B),RANGAREDDY"},{"text" : "TMREIS HAYATNAGAR(B),RANGAREDDY","selected" : "FALSE","value" : "TMREIS HAYATNAGAR(B),RANGAREDDY"},{"text" : "TMREIS HAYATNAGAR(G),RANGAREDDY","selected" : "FALSE","value" : "TMREIS HAYATNAGAR(G),RANGAREDDY"},{"text" : "TMREIS IBRAHIMPATNAM(G),RANGAREDDY","selected" : "FALSE","value" : "TMREIS IBRAHIMPATNAM(G),RANGAREDDY"},{"text" : "TMREIS MOINABAD(G),RANGAREDDY","selected" : "FALSE","value" : "TMREIS MOINABAD(G),RANGAREDDY"},{"text" : "TMREIS RAJENDRANAGAR(B),RANGAREDDY","selected" : "FALSE","value" : "TMREIS RAJENDRANAGAR(B),RANGAREDDY"},{"text" : "TMREIS RAJENDRANAGAR(G),RANGAREDDY","selected" : "FALSE","value" : "TMREIS RAJENDRANAGAR(G),RANGAREDDY"},{"text" : "TMREIS SERILINGAMPALLY(B),RANGAREDDY","selected" : "FALSE","value" : "TMREIS SERILINGAMPALLY(B),RANGAREDDY"},{"text" : "TMREIS ANDOLE(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS ANDOLE(B),SANGAREDDY"},{"text" : "TMREIS ANDOLE(G),SANGAREDDY","selected" : "FALSE","value" : "TMREIS ANDOLE(G),SANGAREDDY"},{"text" : "TMREIS KOHIR(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS KOHIR(B),SANGAREDDY"},{"text" : "TMREIS KANDI(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS KANDI(B),SANGAREDDY"},{"text" : "TMREIS NARAYANKHED(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS NARAYANKHED(B),SANGAREDDY"},{"text" : "TMREIS NARAYANKHED(G),SANGAREDDY","selected" : "FALSE","value" : "TMREIS NARAYANKHED(G),SANGAREDDY"},{"text" : "TMREIS PATANCHERU(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS PATANCHERU(B),SANGAREDDY"},{"text" : "TMREIS PATANCHERU(G),SANGAREDDY","selected" : "FALSE","value" : "TMREIS PATANCHERU(G),SANGAREDDY"},{"text" : "TMREIS SADASIVPET(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS SADASIVPET(B),SANGAREDDY"},{"text" : "TMREIS ZAHEERABAD(B),SANGAREDDY","selected" : "FALSE","value" : "TMREIS ZAHEERABAD(B),SANGAREDDY"},{"text" : "TMREIS ZAHEERABAD(G),SANGAREDDY","selected" : "FALSE","value" : "TMREIS ZAHEERABAD(G),SANGAREDDY"},{"text" : "TMREIS DUBBAKA(B),SIDDIPET","selected" : "FALSE","value" : "TMREIS DUBBAKA(B),SIDDIPET"},{"text" : "TMREIS GAJWEL(G),SIDDIPET","selected" : "FALSE","value" : "TMREIS GAJWEL(G),SIDDIPET"},{"text" : "TMREIS GAJWEL(G),SIDDIPET","selected" : "FALSE","value" : "TMREIS GAJWEL(G),SIDDIPET"},{"text" : "TMREIS HUSNABAD(G),SIDDIPET","selected" : "FALSE","value" : "TMREIS HUSNABAD(G),SIDDIPET"},{"text" : "TMREIS SIDDIPET(B),SIDDIPET","selected" : "FALSE","value" : "TMREIS SIDDIPET(B),SIDDIPET"},{"text" : "TMREIS SIDDIPET(G),SIDDIPET","selected" : "FALSE","value" : "TMREIS SIDDIPET(G),SIDDIPET"},{"text" : "TMREIS HUZURNAGAR(G),SURYAPET","selected" : "FALSE","value" : "TMREIS HUZURNAGAR(G),SURYAPET"},{"text" : "TMREIS KODAD(G),SURYAPET","selected" : "FALSE","value" : "TMREIS KODAD(G),SURYAPET"},{"text" : "TMREIS SURYAPET(B),SURYAPET","selected" : "FALSE","value" : "TMREIS SURYAPET(B),SURYAPET"},{"text" : "TMREIS THUNGTHURTHI(B),SURYAPET","selected" : "FALSE","value" : "TMREIS THUNGTHURTHI(B),SURYAPET"},{"text" : "TMREIS KODANGAL(B),VIKARABAD","selected" : "FALSE","value" : "TMREIS KODANGAL(B),VIKARABAD"},{"text" : "TMREIS PARGI(B),VIKARABAD","selected" : "FALSE","value" : "TMREIS PARGI(B),VIKARABAD"},{"text" : "TMREIS SHIVAREDDYPET(G),VIKARABAD","selected" : "FALSE","value" : "TMREIS SHIVAREDDYPET(G),VIKARABAD"},{"text" : "TMREIS TANDUR(B),VIKARABAD","selected" : "FALSE","value" : "TMREIS TANDUR(B),VIKARABAD"},{"text" : "TMREIS TANDUR(G),VIKARABAD","selected" : "FALSE","value" : "TMREIS TANDUR(G),VIKARABAD"},{"text" : "TMREIS VIKARABAD(B),VIKARABAD","selected" : "FALSE","value" : "TMREIS VIKARABAD(B),VIKARABAD"},{"text" : "TMREIS WANAPARTHY(B),WANAPARTHY","selected" : "FALSE","value" : "TMREIS WANAPARTHY(B),WANAPARTHY"},{"text" : "TMREIS WANAPARTHY(G),WANAPARTHY","selected" : "FALSE","value" : "TMREIS WANAPARTHY(G),WANAPARTHY"},{"text" : "TMREIS WARANGAL(B),WARANGAL","selected" : "FALSE","value" : "TMREIS WARANGAL(B),WARANGAL"},{"text" : "TMREIS WARANGAL(G),WARANGAL","selected" : "FALSE","value" : "TMREIS WARANGAL(G),WARANGAL"},{"text" : "TMREIS HANUMAKONDA(G),WARANGAL RURAL","selected" : "FALSE","value" : "TMREIS HANUMAKONDA(G),WARANGAL RURAL"},{"text" : "TMREIS NARSAMPET(G),WARANGAL RURAL","selected" : "FALSE","value" : "TMREIS NARSAMPET(G),WARANGAL RURAL"},{"text" : "TMREIS PARKAL(B),WARANGAL RURAL","selected" : "FALSE","value" : "TMREIS PARKAL(B),WARANGAL RURAL"},{"text" : "TMREIS WARDHANNAPET(B),WARANGAL RURAL","selected" : "FALSE","value" : "TMREIS WARDHANNAPET(B),WARANGAL RURAL"},{"text" : "TMREIS HANAMKONDA(B),WARANGAL URBAN","selected" : "FALSE","value" : "TMREIS HANAMKONDA(B),WARANGAL URBAN"},{"text" : "TMREIS KAZIPET(B),WARANGAL URBAN","selected" : "FALSE","value" : "TMREIS KAZIPET(B),WARANGAL URBAN"},{"text" : "TMREIS WARANGAL(G-2),WARANGAL URBAN","selected" : "FALSE","value" : "TMREIS WARANGAL(G-2),WARANGAL URBAN"},{"text" : "TMREIS ALAIR(G),YADADRI","selected" : "FALSE","value" : "TMREIS ALAIR(G),YADADRI"},{"text" : "TMREIS BHONGIR(B),YADADRI","selected" : "FALSE","value" : "TMREIS BHONGIR(B),YADADRI"},{"text" : "TMREIS CHOUTUPPAL(B),YADADRI","selected" : "FALSE","value" : "TMREIS CHOUTUPPAL(B),YADADRI"} ]  },   "Attended" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false"   },   "Sick" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false"   },   "Sick UID" : {"type" : "textarea","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false"   },   "R2H" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6,"notify" : "false"   },   "R2H UID" : {"type" : "textarea","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7,"notify" : "false"   },   "Absent" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 8,"notify" : "false"   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1   } }    },    "2" : { "Attendence Details" : {   "Absent UID" : {"type" : "textarea","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 9,"notify" : "false"   },   "RestRoom" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 10,"notify" : "false"   },   "RestRoom UID" : {"type" : "textarea","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 11,"notify" : "false"   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1   } }    }},"permissions" : {    "Stage Name1" : { "View_Permissions" : [   "Attendence Details" ], "Edit_Permissions" : [   "Attendence Details" ], "index" : 1    }},"notification_parameters" : [    { "field" : "Select School", "page" : "1", "section" : "Attendence Details"    }],"application_header" : {    "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : ""    }}     },     "app_id" : "healthcare2017120192713965",     "app_description" : "TMREIS Attendance App ",     "status" : "new",     "app_name" : "Attendance app",     "app_created" : "2017-01-20 14:45:48",     "app_expiry" : "2017-12-31",     "_version" : 1,     "stages" : ["Stage Name1"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			
			 $this->mongo_db->insert ( $app_coll, $data );
			
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{"app_id" : "healthcare2017120192713965","app_description" : "TMREIS Attendance App ",    "app_name" : "Attendance app","app_created" : "2017-01-20 14:45:48"}',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{ "permissions" : {"Stage Name1" : { "View_Permissions" : [  "Attendence Details" ], "Edit_Permissions" : ["Attendence Details" ], "index" : 1 }},"app_id" : "healthcare2017120192713965","app_description" : "TMREIS Attendance App ", "status" : "new","app_name" : "Attendance app","app_created" : "2017-01-20 14:45:48","app_expiry" : "2017-12-31","application_header" : { "header_details" : {"companyname" : "Healthcare","address" : "401s,secbad,india",
			"logo" : "" }},"_version" : 1,"created_by" : "tlstec.primary2@gmail.com","use_profile_header" : "no",
			"blank_app" : "no"}',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			
			//==================================================================== Health superiors request app ===============================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : {"pages" : {    "1" : {        "Student Info" : {   "Unique ID" : {"type" : "retriever","order" : 1,"coll_ref" : "healthcare201672020159570","field_ref" : "page1_Personal Information_Hospital Unique ID","properties" : {    "type" : "text",    "minlength" : "1",    "maxlength" : "123",    "required" : "TRUE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 1,    "notify" : "false",    "parent" : "retriever"},"retrieve_list" : [    "page1_Personal Information_Name",    "page2_Personal Information_District",    "page2_Personal Information_School Name",    "page2_Personal Information_Class",    "page2_Personal Information_Section"]   },   "Name" : {"type" : "mapper","coll_ref" : "healthcare201672020159570","order" : 2,"field_ref" : "page1_Personal Information_Name","properties" : {    "type" : "text",    "minlength" : "1",    "maxlength" : "123",    "required" : "TRUE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 2,    "notify" : "true",    "parent" : "mapper"}   },   "District" : {"type" : "mapper","coll_ref" : "healthcare201672020159570","order" : 3,"field_ref" : "page2_Personal Information_District","properties" : {    "type" : "text",    "minlength" : "1",    "maxlength" : "123",    "required" : "FALSE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 3,    "notify" : "false",    "parent" : "mapper"}   },   "School Name" : {"type" : "mapper","coll_ref" : "healthcare201672020159570","order" : 4,"field_ref" : "page2_Personal Information_School Name","properties" : {    "type" : "text",    "minlength" : "1",    "maxlength" : "123",    "required" : "FALSE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 4,    "notify" : "false",    "parent" : "mapper"}   },   "Class" : {"type" : "mapper","coll_ref" : "healthcare201672020159570","order" : 5,"field_ref" : "page2_Personal Information_Class","properties" : {    "type" : "number",    "minlength" : "1",    "maxlength" : "123",    "required" : "FALSE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 5,    "notify" : "false",    "parent" : "mapper"}   },   "Section" : {"type" : "mapper","coll_ref" : "healthcare201672020159570","order" : 6,"field_ref" : "page2_Personal Information_Section","properties" : {    "type" : "text",    "minlength" : "1",    "maxlength" : "123",    "required" : "FALSE",    "key" : "TRUE",    "description" : "",    "multilanguage" : "FALSE",    "order" : 6,    "notify" : "false",    "parent" : "mapper"}   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1   }        },        "Problem Info" : {   "Identifier" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {        "label" : "Bites",        "value" : "Bites"    },    {        "label" : "Body pains",        "value" : "Body pains"    },    {        "label" : "Breath less ness",        "value" : "Breath less ness"    },    {        "label" : "Burning micturition",        "value" : "Burning micturition"    },    {        "label" : "Burning sensation in the chest",        "value" : "Burning sensation in the chest"    },    {        "label" : "Chest pain",        "value" : "Chest pain"    },    {        "label" : "Chickenpox",        "value" : "Chickenpox"    },    {        "label" : "cold",        "value" : "cold"    },    {        "label" : "Constipation",        "value" : "Constipation"    },    {        "label" : "Cough",        "value" : "Cough"    },    {        "label" : "Cracks feet",        "value" : "Cracks feet"    },    {        "label" : "Cramps",        "value" : "Cramps"    },    {        "label" : "Defective hearing",        "value" : "Defective hearing"    },    {        "label" : "DeHydration",        "value" : "DeHydration"    },    {        "label" : "Delayed periods",        "value" : "Delayed periods"    },    {        "label" : "Dental problems",        "value" : "Dental problems"    },    {        "label" : "Diarrhea",        "value" : "Diarrhea"    },    {        "label" : "Discharge from ear",        "value" : "Discharge from ear"    },    {        "label" : "Dyspepsia",        "value" : "Dyspepsia"    },    {        "label" : "Ear pain",        "value" : "Ear pain"    },    {        "label" : "Fever",        "value" : "Fever"    },    {        "label" : "Frequent urination",        "value" : "Frequent urination"    },    {        "label" : "Headache",        "value" : "Headache"    },    {        "label" : "Indigestion",        "value" : "Indigestion"    },    {        "label" : "Irregular periods",        "value" : "Irregular periods"    }]   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2   }        }    },    "2" : {        "Problem Info" : {   "Description" : {"type" : "textarea","minlength" : "2","maxlength" : "500","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false"   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2   }        },        "Diagnosis Info" : {   "Doctor Summary" : {"type" : "textarea","minlength" : "2","maxlength" : "500","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "false"   },   "Doctor Advice" : {"type" : "select","size" : "1","required" : "FALSE","key" : "TRUE","description" : "","option_choose_one" : "TRUE","with_translations" : "FALSE","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {        "text" : "Prescription",        "selected" : "TRUE",        "value" : "Prescription"    },    {        "text" : "Advice",        "selected" : "FALSE",        "value" : "Advice"    },    {        "text" : "Refer 2 Hospital",        "selected" : "FALSE",        "value" : "Refer 2 Hospital"    }]   },   "Prescription" : {"type" : "textarea","minlength" : "2","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false"   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3   }        },        "Review Info" : {   "Request Type" : {"type" : "select","size" : "1","required" : "FALSE","key" : "TRUE","description" : "","option_choose_one" : "TRUE","with_translations" : "FALSE","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {        "text" : "Normal",        "selected" : "TRUE",        "value" : "Normal"    },    {        "text" : "Emergency",        "selected" : "FALSE",        "value" : "Emergency"    },    {        "text" : "Chronic",        "selected" : "FALSE",        "value" : "Chronic"    }]   },   "Status" : {"type" : "select","size" : "1","required" : "FALSE","key" : "TRUE","description" : "","option_choose_one" : "TRUE","with_translations" : "FALSE","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {        "text" : "Initiated",        "selected" : "TRUE",        "value" : "Initiated"    },    {        "text" : "Prescribed",        "selected" : "FALSE",        "value" : "Prescribed"    },    {        "text" : "Under Medication",        "selected" : "FALSE",        "value" : "Under Medication"    },    {        "text" : "Follow-up",        "selected" : "FALSE",        "value" : "Follow-up"    },    {        "text" : "Cured",        "selected" : "FALSE",        "value" : "Cured"    }]   },   "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4   }        }    }},"permissions" : {    "HS 1" : {        "View_Permissions" : [   "Student Info",   "Problem Info",   "Review Info"        ],        "Edit_Permissions" : [   "Student Info",   "Problem Info",   "Review Info"        ],        "index" : 1    },    "HS 2" : {        "View_Permissions" : [   "Student Info",   "Problem Info",   "Diagnosis Info",   "Review Info"        ],        "Edit_Permissions" : [   "Student Info",   "Problem Info",   "Review Info"        ],        "index" : 3    }},"notification_parameters" : [    {        "field" : "Name",        "page" : "1",        "section" : "Student Info"    }],"application_header" : {    "header_details" : {        "companyname" : "Healthcare",        "address" : "401s,secbad,india",        "logo" : ""    }}     },     "app_id" : "healthcare201610114435690",     "app_description" : "for tmreis",     "status" : "new",     "app_name" : "Health Supervisor Request App",     "app_created" : "2016-10-01 09:18:41",     "app_expiry" : "2019-12-27",     "_version" : 1,     "stages" : ["HS 1","Doctor","HS 2"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{
							"app_id" : "healthcare201610114435690",
								"app_description" : "for tmreis",
								"app_name" : "Health Supervisor Request App",
						"app_created" : "2016-10-01 09:18:41"}',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{        "permissions" : {         "HS 1" : {             "View_Permissions" : ["Student Info","Problem Info","Review Info"             ],             "Edit_Permissions" : ["Student Info","Problem Info","Review Info"             ],             "index" : 1         }     },     "app_id" : "healthcare201610114435690",     "app_description" : "for tmreis",     "status" : "new",     "app_name" : "Health Supervisor Request App",     "app_created" : "2016-10-01 09:18:41",     "app_expiry" : "2019-12-27",     "application_header" : {         "header_details" : {             "companyname" : "Healthcare",             "address" : "401s,secbad,india",             "logo" : ""         }     },     "_version" : 1,     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			//==================================================================== Sanitation infrastructure app ================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{  "app_template" : {         "pages" : {"1" : { "Dormitories" : { "Separate Dormitory" : {"type" : "radio","required" : "TRUE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "true","options" : [    {        "label" : "Yes",        "value" : "Yes"    },    {        "label" : "No",        "value" : "No"    }] }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1 } }, "Toilets" : { "Water Source" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "false" }, "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "in numbers","description" : "","multilanguage" : "FALSE","order" : 2 }, "Buckets" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false" }, "Mugs" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false" }, "Dust Bins" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false" }, "Soap" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6,"notify" : "false" }, "Incinerator" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7,"notify" : "false" }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2 } }},"2" : { "Hand Wash" : { "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "in numbers","description" : "","multilanguage" : "FALSE","order" : 1 }, "Dining Halls" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false" }, "Kitchen" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false" }, "Class Rooms" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false" }, "Dormitories" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false" }, "Kitchen consists of" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 6,"multilanguage" : "FALSE","notify" : "false","options" : [    {        "label" : "Gas facility",        "value" : "Gas facility"    },    {        "label" : "Kerosene Stove",        "value" : "Kerosene Stove"    },    {        "label" : "Made on wood",        "value" : "Made on wood"    }] }, "newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 7 }, "newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8 }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3 } }},"3" : { "Waste Management" : { "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "Disposable Bins (in numbers)","description" : "","multilanguage" : "FALSE","order" : 1 }, "Dining Halls" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false" }, "Kitchen" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false" }, "Class Rooms" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false" }, "Dormitories" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false" }, "newline6" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 6 }, "newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 7 }, "newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8 }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4 } }},"4" : { "Water Facility" : { "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "Availability of Water in Toiltes (in numbers)","description" : "","multilanguage" : "FALSE","order" : 1 }, "Dining Halls" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false" }, "Kitchen" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false" }, "Class Rooms" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false" }, "Dormitories" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false" }, "Running water(number of taps)" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6,"notify" : "false" }, "Store water" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7,"notify" : "false" }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5 } }},"5" : { "Dining Hall" : { "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "Children sit on (in numbers)","description" : "","multilanguage" : "FALSE","order" : 1 }, "Floor" : {"type" : "number","minlength" : "1","maxlength" : "10","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false" }, "Table and Chairs" : {"type" : "number","minlength" : "1","maxlength" : "10","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" :3,"notify" : "false" }, "Benches" : {"type" : "number","minlength" : "1","maxlength" : "10","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false" }, "newline5" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 5 }, "newline6" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 6 }, "newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" :7 }, "newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8 }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6 } }},"6" : { "Declaration" : { "Note:-" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "I here by declare i would render all the responsibilities as mentioned above","description" : "","multilanguage" : "FALSE","order" : 1 }, "Place" : {"type" : "text","minlength" : "1","maxlength" : "55","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false" }, "Date" : {"type" : "date","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false" }, "newline4" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 4 }, "newline5" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 5 }, "Signature" : {"type" : "text","minlength" : "","maxlength" : "","required" : "FALSE","key" : "FALSE","description" : "","multilanguage" : "FALSE","order" : 6 }, "dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7 } }}         },         "permissions" : {"Stage 1" : { "View_Permissions" : [ "Dormitories", "Toilets", "Hand Wash", "Waste Management", "Water Facility", "Dining Hall", "Declaration" ], "Edit_Permissions" : [ "Dormitories", "Toilets", "Hand Wash", "Waste Management", "Water Facility", "Dining Hall", "Declaration" ], "index" : 1}         },         "notification_parameters" : [{ "field" : "Separate Dormitory", "page" : "1", "section" : "Dormitories"}         ],         "application_header" : {"header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : ""}         }     },     "app_id" : "healthcare2017127194550376",     "app_description" : "TMREIS Sanitation Infrastructure Form",     "status" : "new",     "app_name" : "Sanitation Infrastructure Form",     "app_created" : "2017-01-27 14:16:29",     "app_expiry" : "2020-05-21",     "_version" : 1,     "stages" : [         "Stage 1"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{
					"app_id" : "healthcare2017127194550376",
					"app_description" : "TMREIS Sanitation Infrastructure Form",
					"app_name" : "Sanitation Infrastructure Form",
			"app_created" : "2017-01-27 14:16:29"}',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{     "permissions" : {         "Stage 1" : {             "View_Permissions" : ["Dormitories","Toilets","Hand Wash","Waste Management","Water Facility","Dining Hall","Declaration"             ],             "Edit_Permissions" : ["Dormitories","Toilets","Hand Wash","Waste Management","Water Facility","Dining Hall","Declaration"             ],             "index" : 1         }     },     "app_id" : "healthcare2017127194550376",     "app_description" : "TMREIS Sanitation Infrastructure Form",     "status" : "new",     "app_name" : "Sanitation Infrastructure Form",     "app_created" : "2017-01-27 14:16:29",     "app_expiry" : "2020-05-21",     "application_header" : {         "header_details" : {             "companyname" : "Healthcare",             "address" : "401s,secbad,india",             "logo" : ""         }     },     "_version" : 1,     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			
			//==================================================================== Sanitation form app ==========================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{  "app_template" : {"pages" : {"1" : {  "Hand Wash" : {"Hand sanitizers/soap used" : { "type" : "radio", "required" : "TRUE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "true", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1}  },  "Kitchen" : {"Food stored and served with tight containers" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"Availabilities of storage of perishable products" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2}  }},"2" : {  "Cleanliness" : {"Dormitories" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Kitchen" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Dining Halls" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Class Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 4, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Sick Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 5, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Staff Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 6, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3}  }},"3" : {  "Cleanliness" : {"Water Tanks" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 7, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Dust Bins" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 8, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Toilets" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 9, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"Kitchen Utensils" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 10, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Once","value" : "Once"     },     {"label" : "Twice","value" : "Twice"     },     {"label" : "Thrice","value" : "Thrice"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3}  },  "Food" : {"Food prepared according to the days menu" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"Kitchen staff wears gloves ans caps while serving" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"Every meal is tasted by a staff members before serving" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4}  }},"4" : {  "Waste Management" : {"Separate dumping of Inorganic waste" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"Separate dumping of Organic waste" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [     {"label" : "Yes","value" : "Yes"     },     {"label" : "No","value" : "No"     } ]},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5}  },  "Declaration Information" : {"Declaration:" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "I here by declare i would render all the responsibilities as mentioned above", "description" : "", "multilanguage" : "FALSE", "order" : 1},"Place:" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false"},"Date:" : { "type" : "date", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false"},"newline4" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 4},"Signature:" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 5},"dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6}  }}},"permissions" : {"Stage Name1" : {  "View_Permissions" : ["Hand Wash","Kitchen","Cleanliness","Food","Waste Management","Declaration Information"  ],  "Edit_Permissions" : ["Hand Wash","Kitchen","Cleanliness","Food","Waste Management","Declaration Information"  ],  "index" : 1}},"notification_parameters" : [{  "field" : "Hand sanitizers/soap used",  "page" : "1",  "section" : "Hand Wash"}],"application_header" : {"header_details" : {  "companyname" : "Healthcare",  "address" : "401s,secbad,india",  "logo" : ""}}     },     "app_id" : "healthcare2017121175645993",     "app_description" : "TMREIS Sanitation report",     "status" : "new",     "app_name" : "Sanitation Report",     "app_created" : "2017-01-21 12:27:54",     "app_expiry" : "2020-06-19",     "_version" : 1,     "stages" : ["Stage Name1"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare2017121175645993", "app_description" : "TMREIS Sanitation report",
					"app_name" : "Sanitation Report", "app_created" : "2017-01-21 12:27:54"}',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{  "permissions" : {         "Stage Name1" : {             "View_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information"             ],             "Edit_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information"             ],             "index" : 1         }     },     "app_id" : "healthcare2017121175645993",     "app_description" : "TMREIS Sanitation report",     "status" : "new",     "app_name" : "Sanitation Report",     "app_created" : "2017-01-21 12:27:54",     "app_expiry" : "2020-06-19",     "application_header" : {         "header_details" : {             "companyname" : "Healthcare",             "address" : "401s,secbad,india",             "logo" : ""         }     },     "_version" : 1,     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			//==============================================BMI App=================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : {"pages" : {    "1" : {"Student Details" : {   "Hospital Unique ID" : {       "type" : "retriever",       "order" : 1,       "coll_ref" : "healthcare201672020159570",       "field_ref" : "page1_Personal Information_Hospital Unique ID",       "properties" : {  "type" : "text",  "minlength" : "1",  "maxlength" : "123",  "required" : "TRUE",  "key" : "TRUE",  "description" : "",  "multilanguage" : "FALSE",  "order" : 1,  "notify" : "false",  "parent" : "retriever"       },       "retrieve_list" : [  "page1_Personal Information_Name",  "page2_Personal Information_Class",  "page2_Personal Information_Section"       ]   },   "Name" : {       "type" : "mapper",       "coll_ref" : "healthcare201672020159570",       "order" : 2,       "field_ref" : "page1_Personal Information_Name",       "properties" : {  "type" : "text",  "minlength" : "1",  "maxlength" : "123",  "required" : "TRUE",  "key" : "TRUE",  "description" : "",  "multilanguage" : "FALSE",  "order" : 2,  "notify" : "true",  "parent" : "mapper"       }   },   "Class" : {       "type" : "mapper",       "coll_ref" : "healthcare201672020159570",       "order" : 3,       "field_ref" : "page2_Personal Information_Class",       "properties" : {  "type" : "number",  "minlength" : "1",  "maxlength" : "123",  "required" : "FALSE",  "key" : "TRUE",  "description" : "",  "multilanguage" : "FALSE",  "order" : 3,  "notify" : "false",  "parent" : "mapper"       }   },   "Section" : {       "type" : "mapper",       "coll_ref" : "healthcare201672020159570",       "order" : 4,       "field_ref" : "page2_Personal Information_Section",       "properties" : {  "type" : "text",  "minlength" : "1",  "maxlength" : "123",  "required" : "FALSE",  "key" : "TRUE",  "description" : "",  "multilanguage" : "FALSE",  "order" : 4,  "notify" : "false",  "parent" : "mapper"       }   },   "Height cms" : {       "type" : "number",       "minlength" : "1",       "maxlength" : "4",       "required" : "TRUE",       "key" : "TRUE",       "description" : "",       "multilanguage" : "FALSE",       "order" : 5,       "notify" : "true"   },   "Weight kgs" : {       "type" : "number",       "minlength" : "1",       "maxlength" : "4",       "required" : "TRUE",       "key" : "TRUE",       "description" : "",       "multilanguage" : "FALSE",       "order" : 6,       "notify" : "false"   },   "BMI" : {       "type" : "number",       "minlength" : "1",       "maxlength" : "5",       "required" : "FALSE",       "key" : "TRUE",       "description" : "",       "multilanguage" : "FALSE",       "order" : 7,       "notify" : "false"   },   "Date" : {       "type" : "date",       "required" : "TRUE",       "key" : "TRUE",       "description" : "",       "multilanguage" : "FALSE",       "order" : 8,       "notify" : "false"   },   "dont_use_this_name" : {       "type" : "SBreak",       "key" : "TRUE",       "description" : "",       "multilanguage" : "FALSE",       "order" : 1   }}    }},"permissions" : {    "TMREIS BMI" : {"View_Permissions" : [   "Student Details"],"Edit_Permissions" : [   "Student Details"],"index" : 1    }},"notification_parameters" : [    {"field" : "Height cms","page" : "1","section" : "Student Details"    }],"application_header" : {    "header_details" : {"companyname" : "Healthcare","address" : "401s,secbad,india","logo" : ""    }}     },     "app_id" : "healthcare201761916814158",     "app_description" : "TMREIS BMI for HS",     "status" : "new",     "app_name" : "TMREIS BMI App",     "app_created" : "2017-06-20 09:11:22",     "app_expiry" : "2020-12-31",     "_version" : 3,     "stages" : ["TMREIS BMI"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			$this->mongo_db->insert ($app_coll,$data);
			
			$app_coll = $id."_applist";
			$data = json_decode('{
						"app_id" : "healthcare201761916814158",
						"app_description" : "TMREIS BMI for HS",
						"app_name" : "TMREIS BMI App",
						"app_created" : "2017-06-19 10:38:25"}',true);
			$this->mongo_db->insert ($app_coll,$data);
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{    "permissions" : {        "TMREIS BMI" : {   "View_Permissions" : [  "Student Details"],     "Edit_Permissions" : [     "Student Details"   ],  "index" : 1  }    },    "app_id" : "healthcare201761916814158",    "app_description" : "TMREIS BMI for HS",    "status" : "new",    "app_name" : "TMREIS BMI App",    "app_created" : "2017-06-20 09:11:22",    "app_expiry" : "2020-12-31",    "application_header" : {   "header_details" : {   "companyname" : "Healthcare",   "address" : "401s,secbad,india",            "logo" : ""        }    },    "_version" : 3,    "created_by" : "tlstec.primary2@gmail.com",    "use_profile_header" : "no",    "blank_app" : "no"}',true);
			$this->mongo_db->insert ($app_coll,$data);
			
		} 
		*/
		//=======================================================
		
		//=======================================================
		
		
		//=========================start of modification======
		
	/*
		$unique_ids = ["MADB_167_","MADB_163_","MADB_165_","MADB_164_","MADB_166_","MKMNR_119_","MKMNR_118_","MKMNR_120_","MKMNR_121_","MKMNR_122_","MWGL_125_","MWGL_123_","MWGL_124_","MKMM_158_","MKMM_160_","MNLG_110_","MNLG_111_","MNLG_114_","MRR_135_","MRR_168_","MRR_136_","MRR_140_","MRR_141_","MRR_169_","MRR_137_","MMBNR_170_","MMBNR_154_","MMBNR_148_","MMDK_102_","MMDK_100_","MMDK_107_","MMDK_104_","MMDK_108_","MMDK_106_","MMDK_105_","MMDK_109_","MNZD_143_","MNZD_147_"];
		
		$correct_ids = ["MNML_167_","MNML_163_","MNML_165_","MKMB_164_","MMCRL_166_","MPDPL_119_","MPDPL_118_","MJGTL_120_","MJGTL_121_","MRJN_122_","MWGLR_125_","MMBBD_123_","MJGN_124_","MBDD_158_","MBDD_160_","MSRPT_110_","MSRPT_111_","MYDR_114_","MMDCL_135_","MMDCL_168_","MMDCL_136_","MMDCL_140_","MVKRD_141_","MVKRD_169_","MVKRD_137_","MGDW_170_","MNGKL_154_","MNGKL_148_","MSRD_102_","MSRD_100_","MSRD_107_","MSRD_104_","MSRD_108_","MSDPT_106_","MSDPT_105_","MSDPT_109_","MKMR_143_","MKMR_147_"];
		
		$old_school_names = ["TMREIS NIRMAL(G),ADILABAD","TMREIS BHAINSA(B),ADILABAD","TMREIS KHANAPUR(B),ADILABAD","TMREIS KAGAZNAGAR(B),ADILABAD","TMREIS MANCHIRIAL(G),ADILABAD","TMREIS RAMAGUNDAM(B),KARIMNAGAR","TMREIS PEDDAPALLE(G),KARIMNAGAR","TMREIS JAGTIAL(G),KARIMNAGAR","TMREIS KORUTLA(B),KARIMNAGAR","TMREIS SIRCILLA(G),KARIMNAGAR","TMREIS HANUMAKONDA(G),WARANGAL","TMREIS MAHABOOBABAD(B),WARANGAL","TMREIS JANGAOAN(B),WARANGAL","TMREIS KOTHAGUDEM(G),KHAMMAM","TMREIS YELLANDU(B),KHAMMAM","TMREIS SURYAPET(B),NALGONDA","TMREIS KODAD(G),NALGONDA","TMREIS BHONGIR(B),NALGONDA","TMREIS BALANAGAR(B),RANGAREDDY","TMREIS QUTBULLAPUR(B),RANGAREDDY","TMREIS MALKAJGIRI(G),RANGAREDDY","TMREIS UPPAL(G),RANGAREDDY","TMREIS VIKARABAD(B),RANGAREDDY","TMREIS TANDUR(G),RANGAREDDY","TMREIS PARGI(B),RANGAREDDY","TMREIS GADWAL(G),MAHABUBNAGAR","TMREIS KALWAKURTHY(G),MAHABUBNAGAR","TMREIS ACHAMPET(B),MAHABUBNAGAR","TMREIS SADASIVPET(B),MEDAK","TMREIS PATANCHERU(B),MEDAK","TMREIS NARAYANKHED(G),MEDAK","TMREIS ANDOLE(B),MEDAK","TMREIS ZAHEERABAD(G),MEDAK","TMREIS DUBBAKA(B),MEDAK","TMREIS SIDDIPET(B),MEDAK","TMREIS GAJWEL(G),MEDAK","TMREIS BANSWADA(G),NIZAMABAD","TMREIS YELLAREDDY(B),NIZAMABAD"];
		
		$new_schools_names = ["TMREIS NIRMAL(G),NIRMAL","TMREIS BHAINSA(B),NIRMAL","TMREIS KHANAPUR(B),NIRMAL","TMREIS KAGAZNAGAR(B),KOMARAM BHEEM","TMREIS MANCHIRIAL(G),MANCHERIAL","TMREIS RAMAGUNDAM(B),PEDDAPALLY","TMREIS PEDDAPALLE(G),PEDDAPALLY","TMREIS JAGTIAL(G),JAGTIAL","TMREIS KORUTLA(B),JAGTIAL","TMREIS SIRCILLA(G),RAJANNA","TMREIS HANUMAKONDA(G),WARANGAL RURAL","TMREIS MAHABOOBABAD(B),MAHBOOBABAD","TMREIS JANGAOAN(B),JANGOAN","TMREIS KOTHAGUDEM(G),BHADRADRI","TMREIS YELLANDU(B),BHADRADRI","TMREIS SURYAPET(B),SURYAPET","TMREIS KODAD(G),SURYAPET","TMREIS BHONGIR(B),YADADRI","TMREIS BALANAGAR(B),MEDCHAL","TMREIS QUTBULLAPUR(B),MEDCHAL","TMREIS MALKAJGIRI(G),MEDCHAL","TMREIS UPPAL(G),MEDCHAL","TMREIS VIKARABAD(B),VIKARABAD","TMREIS TANDUR(G),VIKARABAD","TMREIS PARGI(B),VIKARABAD","TMREIS GADWAL(G),GADWAL","TMREIS KALWAKURTHY(G),NAGARKURNOOL","TMREIS ACHAMPET(B),NAGARKURNOOL","TMREIS SADASIVPET(B),SANGAREDDY","TMREIS PATANCHERU(B),SANGAREDDY","TMREIS NARAYANKHED(G),SANGAREDDY","TMREIS ANDOLE(B),SANGAREDDY","TMREIS ZAHEERABAD(G),SANGAREDDY","TMREIS DUBBAKA(B),SIDDIPET","TMREIS SIDDIPET(B),SIDDIPET","TMREIS GAJWEL(G),SIDDIPET","TMREIS BANSWADA(G),KAMAREDDY","TMREIS YELLAREDDY(B),KAMAREDDY"];
		
		$districts = ["NIRMAL","NIRMAL","NIRMAL","KOMARAM BHEEM","MANCHERIAL","PEDDAPALLY","PEDDAPALLY","JAGTIAL","JAGTIAL","RAJANNA","WARANGAL RURAL","MAHBOOBABAD","JANGOAN","BHADRADRI","BHADRADRI","SURYAPET","SURYAPET","YADADRI","MEDCHAL","MEDCHAL","MEDCHAL","MEDCHAL","VIKARABAD","VIKARABAD","VIKARABAD","GADWAL","NAGARKURNOOL","NAGARKURNOOL","SANGAREDDY","SANGAREDDY","SANGAREDDY","SANGAREDDY","SANGAREDDY","SIDDIPET","SIDDIPET","SIDDIPET","KAMAREDDY","KAMAREDDY"];
		
		$emails = ["madb.167.","madb.163.","madb.165.","madb.164.","madb.166.","mkmnr.119.","mkmnr.118.","mkmnr.120.","mkmnr.121.","mkmnr.122.","mwgl.125.","mwgl.123.","mwgl.124.","mkmm.158.","mkmm.160.","mnlg.110.","mnlg.111.","mnlg.114.","mrr.135.","mrr.168.","mrr.136.","mrr.140.","mrr.141.","mrr.169.","mrr.137.","mmbnr.170.","mmbnr.154.","mmbnr.148.","mmdk.102.","mmdk.100.","mmdk.107.","mmdk.104.","mmdk.108.","mmdk.106.","mmdk.105.","mmdk.109.","mnzd.143.","mnzd.147."];
		
		$new_emails = ["mnml.167.","mnml.163.","mnml.165.","mkmb.164.","mmcrl.166.","mpdpl.119.","mpdpl.118.","mjgtl.120.","mjgtl.121.","mrjn.122.","mwglr.125.","mmbbd.123.","mjgn.124.","mbdd.158.","mbdd.160.","msrpt.110.","msrpt.111.","mydr.114.","mmdcl.135.","mmdcl.168.","mmdcl.136.","mmdcl.140.","mvkrd.141.","mvkrd.169.","mvkrd.137.","mgdw.170.","mngkl.154.","mngkl.148.","msrd.102.","msrd.100.","msrd.107.","msrd.104.","msrd.108.","msdpt.106.","msdpt.105.","msdpt.109.","mkmr.143.","mkmr.147."];
		
		$dt_names = ["591bdfe3210552b13ce5dd35","591bdfe3210552b13ce5dd35","591bdfe3210552b13ce5dd35","591bdd8a210552c06de5dd33","591bddde210552a241e5dd48","591bde6d210552146ee5dd33","591bde6d210552146ee5dd33","591bdf14210552136ee5dd30","591bdf14210552136ee5dd30","591bde7c210552b13ce5dd34","591bdf2d2105527661e5dd31","591c25ca210552e23ce5dd37","591bddbd210552be3be5dd4b","591bdd73210552c76de5dd34","591bdd73210552c76de5dd34","591bdeaa210552c63be5dd39","591bdeaa210552c63be5dd39","591bded9210552d43ce5dd33","591bddf1210552e13ce5dd36","591bddf1210552e13ce5dd36","591bddf1210552e13ce5dd36","591bddf1210552e13ce5dd36","591bdeb9210552e13ce5dd37","591bdeb9210552e13ce5dd37","591bdeb9210552e13ce5dd37","591bddae210552c76de5dd35","591bde00210552ae3be5dd47","591bde00210552ae3be5dd47","591bde8b210552c23be5dd3b","591bde8b210552c23be5dd3b","591bde8b210552c23be5dd3b","591bde8b210552c23be5dd3b","591bde8b210552c23be5dd3b","591bde9c210552e23ce5dd36","591bde9c210552e23ce5dd36","591bde9c210552e23ce5dd36","591bddcc210552fe6ce5dd30","591bddcc210552fe6ce5dd30"];
		
		//count($unique_ids)-1
		for($ind_modi=0;$ind_modi <= count($unique_ids)-1;$ind_modi++){
			echo $ind_modi;
			
			echo "/////////////";
			//echo $unique_ids[$ind_modi];
			
			$unique_id = $unique_ids[$ind_modi];
			$correct_id = $correct_ids[$ind_modi];
			$old_school_name = $old_school_names[$ind_modi];
			$correct_school_name = $new_schools_names[$ind_modi];
			$district = $districts[$ind_modi];
			$email = $emails[$ind_modi];
			$new_email = $new_emails[$ind_modi];
			$dt_name = $dt_names[$ind_modi];
			
			 //=======================tmreis_chronic_cases==================
			
			$query = $this->mongo_db->whereLike("school_name",$old_school_name)->get("tmreis_chronic_cases");
			foreach ($query as $doc){
				if(isset($doc['school_name'])){
					$doc['school_name'] = $correct_school_name;
					$doc['student_unique_id'] = str_replace($unique_id,$correct_id,$doc['student_unique_id']);
				}
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis_chronic_cases");
			}
			
			//========================================= 
			
		
		

		//====================screening collection ==============
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col );
		
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])){
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], $unique_id);
			
			if($nlg_pos !== false){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']));

				$new_id = $correct_id.$unique_cut;
				
		$doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $new_id;
		$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = $correct_school_name;
		$doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = $district;
		echo print_r($doc,true);
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col );
		//echo print_r($doc["_id"],true);
		//echo print_r($doc,true);
		//exit();
		}
		}
		}
		
		
		//shadow ==================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col."shadow" );
		
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])){
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], $unique_id);
			
			if($nlg_pos !== false){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']));

				$new_id = $correct_id.$unique_cut;
				
		$doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $new_id;
		$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = $correct_school_name;
		$doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = $district;
		// echo print_r($doc["_id"],true);
		// echo print_r($doc,true);
		// exit();
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col."shadow" );
		}
		}
		}

		
		//======================== request collection		
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$unique_id)->get("healthcare201610114435690");

		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID']))
		{
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'], $unique_id);
			
			if($nlg_pos == 0){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID']));
			
			$new_id = $correct_id.$unique_cut;
			echo print_r($new_id,true);
			//exit();
			$doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'] = $new_id;
		}
		}
		
		if(isset($doc['history'][0]['submitted_by'])){		
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail";
		}
		
		if(isset($doc['history']["last_stage"]['submitted_by'])){	
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		
		if($doc['doc_data']['user_name'] == $email."hs#gmail.com")
		{	
			$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		}
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare201610114435690");
		}
		
		//shadow ==========================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$unique_id)->get("healthcare201610114435690_shadow");

		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID']))
		{
			$nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'], $unique_id);
			
			if($nlg_pos == 0){
				$nlg_end = $nlg_pos + strlen ($unique_id);
				$unique_cut = substr($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID']));

				$new_id = $correct_id.$unique_cut;

		$doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'] = $new_id;
		}
		}
		
		if(isset($doc['history'][0]['submitted_by']) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com")){		
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail";
		}
		
		if($doc['doc_data']['user_name'] == $email."hs#gmail.com")
		{	
			$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		}
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare201610114435690_shadow");
		}
		

		//======================== attendence collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Attendence Details.Select School',$old_school_name)->get("healthcare2017120192713965");
		
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']))
		{		
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School'] = $correct_school_name;
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['District'] = $district;
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID']);
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID']);
		$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID']);
		$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID']);
		
		
		if(isset($doc['history']['last_stage']['submitted_by']))
		{	
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017120192713965");
		}
		}
		
		//shadow ========================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Attendence Details.Select School',$old_school_name)->get("healthcare2017120192713965_shadow");
		
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']))
		{
			
				
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School'] = $correct_school_name;
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['District'] = $district;
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID']);
		$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID']);
		$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID']);
		$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID'] = str_replace($unique_id,$correct_id,$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID']);
		
		
		if((isset($doc['history'][0]['submitted_by'])) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com"))
		{	
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail.com";
		}
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017120192713965_shadow");
		}
		}
		

		//======================== 'Sanitation report' collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page4.School Information.School Name',$old_school_name)->get("healthcare2017121175645993" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page4']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page4']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page4']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history']['last_stage']['submitted_by'])) && ($doc['history']["last_stage"]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017121175645993" );
		}
		
		
		//shadow =====================================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page4.School Information.School Name',$old_school_name)->get("healthcare2017121175645993_shadow" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page4']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page4']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page4']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history'][0]['submitted_by'])) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017121175645993_shadow" );
		}
		
		
		//======================== 'Sanitation infrastructure' collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page6.School Information.School Name',$old_school_name)->get("healthcare2017127194550376" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page6']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page6']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page6']['School Information']['District'] = $district;	
		}
		
		if((isset($doc['history']["last_stage"]['submitted_by'])) && ($doc['history']["last_stage"]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017127194550376" );
		}
		
		
		//shadow =============================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page6.School Information.School Name',$old_school_name)->get("healthcare2017127194550376_shadow" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page6']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page6']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page6']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history'][0]['submitted_by'])) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2017127194550376_shadow" );
		}
		
		
		//======================== panacea_ehr_notes collection
		
		$query = $this->mongo_db->whereLike('uid',$unique_id)->get("tmreis_ehr_notes" );
		foreach ($query as $doc){
			if(isset($doc['uid']))
			{
				$nlg_pos = strpos ( $doc['uid'], $unique_id);
				
				if($nlg_pos == 0){
					$nlg_end = $nlg_pos + strlen ($unique_id);
					$unique_cut = substr($doc['uid'],$nlg_end,strlen ($doc['uid']));

					$new_id = $correct_id.$unique_cut;

			$doc['uid'] = $new_id;
			}
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis_ehr_notes" );
			}
		}
		
		//======================== TMREIS_messages collection
		
		$query = $this->mongo_db->whereLike('message',$unique_id)->get("tmreis_messages" );
		foreach ($query as $doc){
		if(isset($doc['message'])){
			
			$doc['message'] = str_replace($unique_id,$correct_id,$doc['message']);
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis_messages" );
		}}
		
		
		//======================== tmreis_health_supervisors collection
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereLike('email',$email)->get("tmreis_health_supervisors" );
		foreach ($query as $doc){
		if(isset($doc['email'])){
			
			$doc['email'] = $new_email."hs@gmail.com";
			$doc['hs_addr'] = $correct_school_name;
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis_health_supervisors" );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}
		}
		
		
		//======================== tmreis_schools collection
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereLike('school_name',$old_school_name)->get("tmreis_schools" );
		foreach ($query as $doc){
		if(isset($doc['school_name'])){
			
			$doc['school_name'] = $correct_school_name;
			$doc['username'] = $correct_school_name;
			$doc['school_addr'] = $correct_school_name;
			$doc['dt_name'] = $dt_name;
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis_schools" );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}
		}
		
		//rename user collection================================================================
			$query = $this->mongo_db->get($email."hs#gmail.com_applist" );
			foreach ($query as $doc){
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_applist", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_applist");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_apps" );
			foreach ($query as $doc){
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_apps", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_apps");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_apps" );
			foreach ($query as $doc){
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_apps", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_apps");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_web_apps" );
			foreach ($query as $doc){
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_web_apps", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_web_apps");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_docs", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_docs");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_docs", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_docs");
			
			$query = $this->mongo_db->get($email."hs#gmail.com_web_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$query = $this->mongo_db->insert ( $new_email."hs#gmail.com_web_docs", $doc );
			}
			$query = $this->mongo_db->dropCollection ( $email."hs#gmail.com_web_docs");
		//=============================================================
		
		//===========doctor collection
		
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr1#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr1#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr2#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr2#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr3#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr3#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr4#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr4#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr5#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr5#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr6#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr6#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("tmreis.dr7#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tmreis.dr7#gmail.com_docs" );
			}
		
		//============================
			
		}  
		
		
		*/
		
		
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_classes' );
		return $query;
	}
	public function sectionscount() {
		$count = $this->mongo_db->count ( 'tmreis_sections' );
		return $count;
	}
	public function get_sections($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_sections' );
		return $query;
	}
	public function symptomscount() {
		$count = $this->mongo_db->count ( 'tmreis_symptoms' );
		return $count;
	}
	public function get_symptoms($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_symptoms' );
		return $query;
	}
	public function get_reports_ehr($ad_no) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page2.Personal Information.AD No", $ad_no )->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			return $result;
		}
	}

	public function get_reports_ehr_uid($uid) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->limit(700)->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=>-1))->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $uid )->get ( $this->request_app_col );
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['tmreis_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
			}
			
			$query_notes = $this->mongo_db->where ( "uid", $uid )->get ( $this->notes_col );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['tmreis_schools'] );
			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['tmreis_health_supervisors'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
			$result ['hs'] = false;
			return $result;
		}
	}
	
	public function get_students_uid($uid) {
		$query = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	public function create_diagnostic($post) {
		$data = array (
				"dt_name" => $post ['dt_name'],
				"diagnostic_code" => $post ['diagnostic_code'],
				"diagnostic_name" => $post ['diagnostic_name'],
				"diagnostic_ph" => $post ['diagnostic_ph'],
				"diagnostic_mob" => $post ['diagnostic_mob'],
				"diagnostic_addr" => $post ['diagnostic_addr'] 
		);
		$query = $this->mongo_db->insert ( 'tmreis_diagnostics', $data );
		return $query;
	}
	public function create_hospital($post) {
		$data = array (
				"dt_name" => $post ['dt_name'],
				"hospital_code" => $post ['hospital_code'],
				"hospital_name" => $post ['hospital_name'],
				"hospital_ph" => $post ['hospital_ph'],
				"hospital_mob" => $post ['hospital_mob'],
				"hospital_addr" => $post ['hospital_addr'] 
		);
		$query = $this->mongo_db->insert ( 'tmreis_hospitals', $data );
		return $query;
	}
	public function update_student_data($doc, $doc_id) {
		// $query = $this->mongo_db->where ( "_id", $doc_id )->set ( $doc )->update ( "naresh" );
		$query = $this->mongo_db->where ( "_id", $doc_id )->set ( $doc )->update ( $this->screening_app_col );
		
		return $query;
	}
	public function studentscount() {
		$count = $this->mongo_db->count ( $this->screening_app_col );
		return $count;
	}
	public function get_students($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->orderBy ( array (
				'doc_data.widget_data.page1.Personal Information.Name' => 1 
		) )->select ( array (
				"doc_data.widget_data" 
		) )->limit ( $per_page )->offset ( $page - 1 )->get ( $this->screening_app_col );
		return $query;
	}
	public function get_all_students() {
		ini_set ( 'memory_limit', '1G' );
		
		// $merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Over Weight","Under Weight")));
		$count = $this->mongo_db->count ( $this->screening_app_col );
		// log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		$per_page = 1000;
		$loop = $count / $per_page;
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			// log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($page,true));
			// log_message("debug","oooooooooooooooooooooooooooooooooooooooooo".print_r($offset,true));
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true 
							) 
					),
					// array('$match' => $merged_array)
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
			$result = array_merge ( $result, $response ['result'] );
			// log_message("debug","response=====1643==".print_r($response,true));
			// log_message("debug","response=====1643==".print_r(count($response['result']),true));
			// log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($result,true));
		}
		//
		// log_message("debug","response=====1643==".print_r(count($response['result']),true));
		//log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $result, true ) );
		
		// $query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
		// return $query;
		return $result;
	}
	public function hospitalscount() {
		$count = $this->mongo_db->count ( 'tmreis_hospitals' );
		return $count;
	}
	public function get_hospitals($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_hospitals' );
		foreach ( $query as $hospitals => $hospital ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $hospital ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $hospital ['dt_name'] )) {
				$query [$hospitals] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$hospitals] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
	}
	public function diagnosticscount() {
		$count = $this->mongo_db->count ( 'tmreis_diagnostics' );
		return $count;
	}
	public function get_diagnostics($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_diagnostics' );
		foreach ( $query as $diagnostics => $dia ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $dia ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $dia ['dt_name'] )) {
				$query [$diagnostics] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$diagnostics] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function empcount() {
		$count = $this->mongo_db->count ( 'tmreis_emp' );
		return $count;
	}
	public function get_emp($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_emp' );
		return $query;
	}
	public function insert_student_data($doc_data, $history, $doc_properties) {
		// $query = $this->mongo_db->getWhere("naresh", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
		$query = $this->mongo_db->getWhere ( $this->screening_app_col, array (
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
			
			$this->mongo_db->insert ( $this->screening_app_col, $form_data );
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		} else {
			$form_data = array ();
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] = $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] . 'A';
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
			
			// $this->mongo_db->insert("naresh",$form_data);
			$this->mongo_db->insert ( $this->screening_app_col, $form_data );
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		}
	}
	public function get_all_symptoms($date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'] );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		$prob_arr = [ ];
		foreach ( $query as $doc ) {
			if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'] )) {
				$problems = $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'];
				foreach ( $problems as $problem ) {
					if (isset ( $prob_arr [$problem] )) {
						$prob_arr [$problem] ++;
					} else {
						$prob_arr [$problem] = 1;
					}
				}
			}
		}
		
		// //log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob_arr,true));
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
			// //log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob,true));
			// //log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		// //log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
		
		return $final_values;
	}
	
	public function get_absent_pie_schools_data($date = FALSE, $dt_name = "All")
	{
		// Variables
		$all_schools               = array();
		$all_schools_district      = array();
		$all_schools_name      	   = array();
		$submitted_schools 	       = array();
		$submitted_school_district = array();
		$submitted_school_name     = array();
		$not_submitted_schools	   = array();
		$schools_data              = array();
		$not_submitted_dist        = array();
		
		$all_schools_mobile        = array();
		$all_schools_cpn      	   = array();
		$submitted_school_mob 	   = array();
		$submitted_school_person   = array();
		$not_submitted_school_mob 	   = array();
		$not_submitted_school_person   = array();
		
		$schools_list = $this->get_all_schools();
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
			$all_schools_mobile[$school_data['school_name']] = $school_data['school_mob'];
			$all_schools_cpn[$school_data['school_name']] = $school_data['contact_person_name'];
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====716=='.print_r($query,true));
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====717=='.print_r($today_date,true));
		
		foreach ( $query as $doc ) {
			    if(!in_array($doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'],$submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] );
					array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] );
					if(isset($all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']])){
						array_push ( $submitted_school_mob,$all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
						array_push ( $submitted_school_person,$all_schools_cpn[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
					}else{
						array_push ( $submitted_school_mob,"" );
						array_push ( $submitted_school_person,"" );
					}
					
					}
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		$submitted_schools['mobile']       = $submitted_school_mob;
		$submitted_schools['person_name']  = $submitted_school_person;
		
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		if(isset($all_schools_mobile[$school_name])){
				array_push ( $not_submitted_school_mob,$all_schools_mobile[$school_name] );
				array_push ( $not_submitted_school_person,$all_schools_cpn[$school_name] );
			}else{
				array_push ( $not_submitted_school_mob,"" );
				array_push ( $not_submitted_school_person,"" );
			}
		   
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		$not_submitted_schools['mobile']       = $not_submitted_school_mob;
		$not_submitted_schools['person_name']  = $not_submitted_school_person;
		
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====735=='.print_r($schools_data,true));
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====736=='.print_r(gettype($schools_data['submitted']),true));
		
		return $schools_data;
	}
	
	public function get_all_absent_data($date = FALSE, $dt_name = "All", $school_name = "All") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
					array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
		}
		
		$absent = 0;
		$sick = 0;
		$restRoom = 0;
		$r2h = 0;
		// $attended = 0;
		foreach ( $query as $report ) {
			$absent = $absent + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Absent'] );
			$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
			$restRoom = $restRoom + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
			$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
			// $attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
		}
		
		$requests = [ ];
		
		// $request['label'] = 'ATTENDED';
		// $request['value'] = $attended;
		// array_push($requests,$request);
		
		$request ['label'] = 'ABSENT REPORT';
		$request ['value'] = $absent;
		array_push ( $requests, $request );
		
		$request ['label'] = 'SICK CUM ATTENDED';
		$request ['value'] = $sick;
		array_push ( $requests, $request );
		
		$request ['label'] = 'REST ROOM IN MEDICATION';
		$request ['value'] = $restRoom;
		array_push ( $requests, $request );
		
		$request ['label'] = 'REFER TO HOSPITAL';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	
	public function get_all_sanitation_report_data($date = FALSE, $dt_name = "All", $school_name = "All") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ($this->sanitation_report_app_col );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page6'] ['School Information'] ['District'] ) == strtolower ( $dt_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page6'] ['School Information'] ['School Name'] ) == strtolower ( $school_name )) {
					array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
		}
		
		$handwash         = 0;
		$kitchen          = 0;
		$waste_management = 0;
		$cleanliness      = 0;
		$food             = 0;
		// $attended = 0;
		foreach ( $query as $report ) {
			$absent = $absent + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Absent'] );
			$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
			$restRoom = $restRoom + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
			$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
			// $attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
		}
		
		$requests = [ ];
		
		$request ['label'] = 'Handwash';
		$request ['value'] = $absent;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Kitchen';
		$request ['value'] = $sick;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Waste Management';
		$request ['value'] = $restRoom;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Cleanliness';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Food';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	public function drilldown_absent_to_districts($data, $date, $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		ini_set ( 'memory_limit', '1G' );
		switch ($type) {
			case "ABSENT REPORT" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") 
				{
					if ($dt_name != "All") 
					{
						foreach ( $query as $doc ) 
						{
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) 
							{
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} 
					else 
					{
						
					}
				} 
				else 
				{
					foreach ( $query as $doc ) 
					{
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) 
						{
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"Absent");
				break;
			
			case "SICK CUM ATTENDED" :
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"Sick");
				break;
			
			case "REST ROOM IN MEDICATION" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"RestRoom");
				break;
			
			case "REFER TO HOSPITAL" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"R2H");
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_schools($data, $date, $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
		ini_set ( 'memory_limit', '1G' );
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		// //log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		switch ($type) {
			case "ABSENT REPORT" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"Absent");
				
				break;
			case "SICK CUM ATTENDED" :
				
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"Sick");
				
				break;
			
			case "REST ROOM IN MEDICATION" :
				
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"RestRoom" );
				
				break;
			
			case "REFER TO HOSPITAL" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"R2H");
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_students($data, $date, $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		ini_set ( 'memory_limit', '512M' );
		
		switch ($type) {
			case "ABSENT REPORT" :
				ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			case "SICK CUM ATTENDED" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			case "REST ROOM IN MEDICATION" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			case "REFER TO HOSPITAL" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_students_docs($_id_array) {
		$docs = [ ];
		
		ini_set ( 'memory_limit', '512M' );
		
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where/* Like */ ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
			if ($query)
				array_push ( $docs, $query [0] );
		}
		
		// //log_message("debug","abbbbbbbbbbbbbbbbbbbbbbbbbb____________arrrrrrrrrrrrrrrrrrrrrrrrr".print_r($_id_array,true));
		// $query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereIn("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id_array)->get($this->screening_app_col);
		// //log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
		return $docs;
	}
	public function get_drilling_attendance_districts_prepare_pie_array($query,$category) {
		$requests = [ ];
		
		$dist_list = $this->get_all_district ();
		
		$dist_arr = [ ];
		foreach ( $dist_list as $dist ) {
			array_push ( $dist_arr, $dist ['dt_name'] );
		}
		
		foreach ( $dist_arr as $districts ) 
		{
			$request ['label'] = $districts;
			$count = 0;
			if ($query) {
				foreach ( $query as $dist ) {
					if (isset ( $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] )) {
						if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $districts )) {
							//$count ++;
							if($category=="RestRoom")
							{
								$count = $count + (int) $dist ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] [$category];
							}
							else
							{
								$count = $count + (int) $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] [$category];
							}
							
						}
					}
				}
			}
			$request ['value'] = $count;
			array_push ( $requests, $request );
		}
		
		return $requests;
	}
	public function get_drilling_absent_schools_prepare_pie_array($query, $dist,$category) {
		// ////log_message("debug","2222222222222222222222222222222222222222222222222".print_r($query,true));
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				// //log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == $dist) {
						array_push ( $search_result, $doc );
					}
				}
			}
			
			$request = [ ];
			foreach ( $search_result as $doc ) 
			{
				if($category=="RestRoom")
				{
					$request[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] = (int) $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] [$category];
				}
				else
				{
					$request[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] = (int) $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] [$category];
				}
			}
			
			
			$final_values = [ ];
			foreach ( $request as $school => $count ) 
			{
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			
			
			return $final_values;
		}
	}
	public function get_drilling_absent_students_prepare_pie_array($query, $school_name, $type) {
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				// //log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == $school_name) {
						array_push ( $search_result, $doc );
					}
				}
			}
			// //log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $search_result as $doc ) {
				switch ($type) {
					case "ABSENT REPORT" :
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['Absent UID'] );
						// //log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						// //log_message("debug","mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm".print_r($UI_arr,true));
						
						break;
					case "SICK CUM ATTENDED" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick UID'] );
						// //log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						
						break;
					
					case "REST ROOM IN MEDICATION" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom UID'] );
						// log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						
						break;
					
					case "REFER TO HOSPITAL" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H UID'] );
						// log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						
						break;
					
					default :
						;
						break;
				}
			}
			
			return $UI_arr;
		}
	}
	public function get_all_symptoms_docs($start_date, $end_date, $id_for_school = false) {
		ini_set ( 'max_execution_time', 0 );
		if ($id_for_school) {
			$query = $this->mongo_db->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
					$id_for_school 
			) )->get ( $this->request_app_col );
		} else {
			$query = $this->mongo_db->select ( array (
					"doc_data.widget_data",
					"history" 
			) )->get ( $this->request_app_col );
		}
		
		$result = [ ];
		foreach ( $query as $doc ) {
			
			if($doc['doc_data']['widget_data']['page2']['Review Info']['Status'] != "Cured"){
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
				}
			}
		}
		$query = $result;
		return $query;
	}
	public function get_start_end_date($today_date, $request_duration) {
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
		} else if ($request_duration == "2015-16 Academic Year"){
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
		else if ($request_duration == "Yearly") {
			$end_date = "2017-06-01";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			//$month = explode("-",$today_date);
			//$month_final = "-".$month[1]." month";
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date ) );
			//$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
	}
	public function get_all_requests($date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'] );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					log_message('debug','screening_doc==============1619'.print_r($screening_doc,true));
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
				log_message('debug','query==============1627'.print_r($query,true));
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				log_message('debug','screening_doc==============1613'.print_r($screening_doc,true));
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
			log_message('debug','query==============1645'.print_r($query,true));
		}
		
		// $query = $this->mongo_db->select(array("doc_data.widget_data","history"))->get($this->request_app_col);
		
		$device_initiated = 0;
		$web_initiated = 0;
		$screening_initiated = 0;
		$prescribed = 0;
		$medication = 0;
		$followUp = 0;
		$cured = 0;
		$hospitalized = 0;
		// $attended = 0;
		
		$req_normal = 0;
		$req_emergency = 0;
		$req_chronic = 0;
		
		foreach ( $query as $report ) {
			$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			
			if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if (($user_type == "CCUSER")) {
						$web_initiated ++;
					}else if(($user_type == "PADMIN")){
						$screening_initiated ++;
					} else {
						$device_initiated ++;
					}
				} else {
					$device_initiated ++;
				}
			
			if ($status == "Initiated") {
				// if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					// $user_type = $report ['history'] [0] ['submitted_user_type'];
					// if (($user_type == "CCUSER")) {
						// $web_initiated ++;
					// }else if(($user_type == "PADMIN")){
						// $screening_initiated ++;
					// } else {
						// $device_initiated ++;
					// }
				// } else {
					// $device_initiated ++;
				// }
			} else if ($status == "Prescribed") {
				$prescribed ++;
			} else if ($status == "Under Medication") {
				$medication ++;
			} else if ($status == "Follow-up") {
				$followUp ++;
			} else if ($status == "Cured") {
				$cured ++;
			} else if ($status == "Hospitalized") {
				$hospitalized ++;
			}
			
			$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
			if ($request_type == "Normal") {
				$req_normal ++;
			} else if ($request_type == "Emergency") {
				$req_emergency ++;
			} else if ($request_type == "Chronic") {
				$req_chronic ++;
			}
		}
		
		$requests = [ ];
		
		$request ['label'] = 'Device Initiated';
		$request ['value'] = $device_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Web Initiated';
		$request ['value'] = $web_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Screening Initiated';
		$request ['value'] = $screening_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Prescribed';
		$request ['value'] = $prescribed;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Under Medication';
		$request ['value'] = $medication;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Follow-up';
		$request ['value'] = $followUp;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Cured';
		$request ['value'] = $cured;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Hospitalized';
		$request ['value'] = $hospitalized;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Normal Req';
		$request ['value'] = $req_normal;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Emergency Req';
		$request ['value'] = $req_emergency;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Chronic Req';
		$request ['value'] = $req_chronic;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	// ======================================================================
	public function get_all_requests_docs($start_date, $end_date, $type = false, $dt_name = "All", $school_name = "All") {
		if ($type == "Initiated") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Status' => $type,
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->get ( $this->request_app_col );
		}else if ($type == "Screening") {
			$query = $this->mongo_db->where ( array (
					'history.0.submitted_user_type' => "PADMIN",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->get ( $this->request_app_col );
		} else if ($type == "Normal") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Normal",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->get ( $this->request_app_col );
		} else if ($type == "Emergency") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->get ( $this->request_app_col );
		} else if ($type == "Chronic") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->get ( $this->request_app_col );
		} else {
			$query = $this->mongo_db->where ( array('doc_data.widget_data.page2.Review Info.Status' => $type,
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")) )->get ( $this->request_app_col );
		}
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		$result = [ ];
		foreach ( $query as $doc ) {
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
			}
		}
		$query = $result;
		return $query;
	}
	public function drilldown_request_to_districts($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		// ini_set('memory_limit', '512M');
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} else {
					array_push ( $query, $report );
				}
			}
		} else if ($type == "Screening Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Web Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name );
		}
		
		$dist_list = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (isset ( $dist_list [$district] )) {
					$dist_list [$district] ++;
				} else {
					$dist_list [$district] = 1;
				}
			}
		}
		
		$final_values = [ ];
		foreach ( $dist_list as $dicsts => $count ) {
			$result ['label'] = $dicsts;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_request_schools($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} else {
					array_push ( $query, $report );
				}
			}
		} else if ($type == "Web Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Screening Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name );
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
		
		$school_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == $dist) {
					array_push ( $matching_docs, $doc [0] );
				}
			}
		}
		
		foreach ( $matching_docs as $docs ) {
			$school_name = $docs ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
			if (isset ( $school_list [$school_name] )) {
				$school_list [$school_name] ++;
			} else {
				$school_list [$school_name] = 1;
			}
		}
		
		$final_values = [ ];
		foreach ( $school_list as $school => $count ) {
			$result ['label'] = $school;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_request_students($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		$obj_data = json_decode ( $data, true );
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = $obj_data ['1'];
		
		// log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} else {
					array_push ( $query, $report );
				}
			}
		} else if ($type == "Web Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Screening Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name );
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
		$student_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && count ( $doc ) > 0) {
				$school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if ($school == $school_name) {
					array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
				}
			}
		}
		return $matching_docs;
	}
	public function get_drilling_request_students_docs($_id_array) {
		$docs = [ ];
		//log_message ( "debug", "dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd" . print_r ( $_id_array, true ) );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	
	// ----------------------------------------------------------------------
	
	// ===================================id=======================+==========================
	public function drilldown_identifiers_docs($start_date, $end_date, $type) {
		ini_set ( 'memory_limit', '512M' );
		$query = $this->mongo_db->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
				$type 
		) )->get ( $this->request_app_col );
		
		$result = [ ];
		foreach ( $query as $doc ) {
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
			}
		}
		$query = $result;
		
		return $query;
	}
	public function drilldown_identifiers_to_districts($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		//log_message ( 'debug', 'dddddddddddddddddddddddddddddddddddddddd----' . print_r ( $dt_name, true ) );
		//log_message ( 'debug', 'ssssssssssssssssssssssssssssssssssssssss----' . print_r ( $school_name, true ) );
		//log_message ( 'debug', 'dttttttttttttttttttttttttttttttttttttttt----' . print_r ( $data, true ) );
		ini_set ( 'max_execution_time', 0 );
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				$unique_id = $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
		$dist_list = [ ];
		
		foreach ( $query as $identifiers ) {
			
			$retrieval_list = array ();
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			// log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (isset ( $dist_list [$district] )) {
					$dist_list [$district] ++;
				} else {
					$dist_list [$district] = 1;
				}
			}
		}
		
		$final_values = [ ];
		foreach ( $dist_list as $dicsts => $count ) {
			$result ['label'] = $dicsts;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_identifiers_schools($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		ini_set ( 'memory_limit', '10G' );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
		
		$school_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->whereLike ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == strtolower ( $dist )) {
					array_push ( $matching_docs, $doc [0] );
				}
			}
		}
		
		foreach ( $matching_docs as $docs ) {
			$school_name = $docs ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
			if (isset ( $school_list [$school_name] )) {
				$school_list [$school_name] ++;
			} else {
				$school_list [$school_name] = 1;
			}
		}
		
		$final_values = [ ];
		foreach ( $school_list as $school => $count ) {
			$result ['label'] = $school;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_identifiers_students($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = $obj_data ['1'];
		
		// log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
		
		$student_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if ($school == $school_name) {
					array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
				}
			}
		}
		
		return $matching_docs;
	}
	public function get_drilling_identifiers_students_docs($_id_array) {
		$docs = [ ];
		
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	
	// ===================================id=================================================
	private function screening_pie_data_for_stage1($dates) {
		ini_set ( 'max_execution_time', 0 );
		// ============================================================stage 1 ==============================================
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		
		$requests = [ ];
		
		$request ['label'] = 'Physical Abnormalities';
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight", "Under Weight"))->count($this->screening_app_col);
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight",
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
			// log_message("debug","responseeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=====1665==".print_r($response,true));
		}
		
		$request ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request ['label'] = 'General Abnormalities';
		
		// $search = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array("Over Weight", "Under Weight"));
		// $query = $this->mongo_db->where(array("doc_data.widget_data.page5.Doctor Check Up.N A D" => array("Yes")))->count($this->screening_app_col);
		
		$merged_array = array ();
		$nad_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.N A D" => array (
						'$nin' => array (
								"Yes" 
						) 
				) 
		);
		$nad_not_yes = array (
				"doc_data.widget_data.page5.Doctor Check Up.N A D" => array (
						'$exists' => true 
				) 
		);
		
		$abnormalities_not_string = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => " " 
				) 
		);
		$ortho_not_string = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => " " 
				) 
		);
		$postural_not_string = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => " " 
				) 
		);
		$description_not_string = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => "" 
				) 
		);
		$advice_not_string = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => "" 
				) 
		);
		$defects_not_string = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => " " 
				) 
		);
		$deficencles_not_string = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencles" => array (
						'$ne' => " " 
				) 
		);
		$childhood_not_string = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => " " 
				) 
		);
		$nad_not_string = array (
				"doc_data.widget_data.page5.Doctor Check Up.N A D" => array (
						'$ne' => " " 
				) 
		);
		$deficencies_not_string = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => " " 
				) 
		);
		
		array_push ( $merged_array, $nad_exists );
		array_push ( $merged_array, $nad_not_yes );
		
		array_push ( $merged_array, $abnormalities_not_string );
		array_push ( $merged_array, $ortho_not_string );
		array_push ( $merged_array, $postural_not_string );
		array_push ( $merged_array, $description_not_string );
		array_push ( $merged_array, $advice_not_string );
		array_push ( $merged_array, $defects_not_string );
		array_push ( $merged_array, $deficencles_not_string );
		array_push ( $merged_array, $childhood_not_string );
		array_push ( $merged_array, $nad_not_string );
		array_push ( $merged_array, $deficencies_not_string );
		
		// //log_message("debug","response=====1665==".print_r($merged_array,true));
		
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
		
		$request ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request ['label'] = 'Eye Abnormalities';
		// $search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6", "doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "", "doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6", "doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No", "doc_data.widget_data.page6" => array(),"doc_data.widget_data.page7" => array());
		// $query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		
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
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $page7_exists );
		
		// //log_message("debug","response=====1665==".print_r($merged_array,true));
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
		
		// //log_message("debug","response=====1748==".print_r($response,true));
		// //log_message("debug","response=====1749==".print_r(count($response['result']),true));
		
		$request ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request ['label'] = 'Auditory Abnormalities';
		// $search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Left" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array('Normal'), "doc_data.widget_data.page8" => array());
		// $query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		
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
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
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
		
		array_push ( $or_merged_array, $audi_right );
		array_push ( $or_merged_array, $audi_left );
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
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
		
		$request ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request ['label'] = 'Dental Abnormalities';
		// $search = array("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => "Good", "doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => "No", "doc_data.widget_data.page9.Dental Check-up.Flourosis" => "No","doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No","doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => "No", "doc_data.widget_data.page9" => array());
		// $query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"",
								" " 
						) 
				) 
		);
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
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
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $or_merged_array, $flourosis );
		array_push ( $or_merged_array, $orthodontic );
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
			// log_message("debug","deeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee===".print_r($response,true));
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
		
		$request ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		// log_message("debug","pppppppppppppppppppppppppppppppp123=====".print_r($requests,true));
		
		return $requests;
		
		// ============================================================end of stage 1 =======================================
	}
	private function screening_pie_data_for_stage2($dates) {
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '1G' );
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 2 =================================================
		
		// $query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		
		$requests = [ ];
		
		$request = [ ];
		$request ['Physical Abnormalities'] ['label'] = 'Over Weight';
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->count($this->screening_app_col);
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
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
		
		$request ['Physical Abnormalities'] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ['Physical Abnormalities'] ['label'] = 'Under Weight';
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->count($this->screening_app_col);
		
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
		
		$request ['Physical Abnormalities'] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'General';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array()))->count($this->screening_app_col);
		
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
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Skin';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array()))->count($this->screening_app_col);
		
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
		
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Ortho';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Ortho" => array()))->count($this->screening_app_col);
		// $request['value'] = $query;
		
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
		
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Postural';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array(), "doc_data.widget_data.page4" => array()))->count($this->screening_app_col);
		
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
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Defects at Birth';
		
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
		
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
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Deficencies';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
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
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["General Abnormalities"] ['label'] = 'Childhood Diseases';
		// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array(), "doc_data.widget_data.page5" => array()))->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["General Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Eye Abnormalities"] ['label'] = 'Without Glasses';
		// $search = array("doc_data.widget_data.page6.Without Glasses.Right" => "", "doc_data.widget_data.page6.Without Glasses.Left" => "", "doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6", "doc_data.widget_data.page6" => array());
		// $query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Eye Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Eye Abnormalities"] ['label'] = 'With Glasses';
		// $search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "", "doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6", "doc_data.widget_data.page6" => array());
		// $query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Eye Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Eye Abnormalities"] ['label'] = 'Colour Blindness';
		// $search = array("doc_data.widget_data.page7.Colour Blindness.Right" => array("Yes"), "doc_data.widget_data.page7.Colour Blindness.Left" => array("Yes"));
		// $query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Eye Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Auditory Abnormalities"] ['label'] = 'Right Ear';
		// $search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail", "doc_data.widget_data.page8" => array());
		// $query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Auditory Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Auditory Abnormalities"] ['label'] = 'Left Ear';
		// $search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail", "doc_data.widget_data.page8" => array());
		// $query = $this->mongo_db->orWhere($search)->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Auditory Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Auditory Abnormalities"] ['label'] = 'Speech Screening';
		// $query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Auditory Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Oral Hygiene - Fair';
		// $query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Oral Hygiene - Poor';
		// $query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Carious Teeth';
		// $query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Flourosis';
		// $query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Orthodontic Treatment';
		// $query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		$request = [ ];
		$request ["Dental Abnormalities"] ['label'] = 'Indication for extraction';
		// $query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->count($this->screening_app_col);
		// $request['value'] = $query;
		// array_push($requests,$request);
		
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
		$request ["Dental Abnormalities"] ['value'] = count ( $result );
		array_push ( $requests, $request );
		
		return $requests;
		
		// ======================================================end of stage 2===========================================
	}
	private function screening_pie_data_for_stage3($dates) {
		ini_set ( 'max_execution_time', 0 );
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 3 =================================================
		
		$requests = [ ];
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
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
		
		$request ["Over Weight"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		
		$request ["Under Weight"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["General"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Skin"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		array_push ( $requests, $request );
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
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
		$request ["Ortho"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Postural"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Defects at Birth'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Deficencies'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Childhood Diseases'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Without Glasses'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['With Glasses'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Colour Blindness'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Right Ear'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Left Ear'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ['Speech Screening'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Oral Hygiene - Fair"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Oral Hygiene - Poor"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Carious Teeth"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Flourosis"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Orthodontic Treatment"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
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
		$request ["Indication for extraction"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	private function screening_pie_data_for_stage4($dates) {
		ini_set ( 'max_execution_time', 0 );
		
		$dist_list = $this->get_all_district ();
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 3 =================================================
		
		$requests = [ ];
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
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
		
		// log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddlist--------'.print_r($dist_list,true));
		foreach ( $dist_list as $dist ) {
			// log_message('debug','ddddddddddddddddddddddddddddddddddddddd--------------'.print_r(strtolower($dist["dt_name"]),true));
			$request ["Over Weight"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
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
		foreach ( $dist_list as $dist ) {
			$request ["Under Weight"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
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
		
		// $and_merged_array = array ();
		
		// $deficencies = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$not' => array (
								// '$size' => 0 
						// ) 
				// ) 
		// );
		// $page5_exists = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$exists' => true 
				// ) 
		// );
		
		// $deficencies_str_empty = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => '' 
				// ) 
		// );
		// $deficencies_str_space = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => ' ' 
				// ) 
		// );
		// $deficencies_arr = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => [ ] 
				// ) 
		// );
		
		// array_push ( $and_merged_array, $deficencies_str_empty );
		// array_push ( $and_merged_array, $deficencies_str_space );
		// array_push ( $and_merged_array, $deficencies_arr );
		
		// array_push ( $and_merged_array, $deficencies );
		// array_push ( $and_merged_array, $page5_exists );
		
		// $result = [ ];
		// for($page = 1; $page < $loop; $page ++) {
			// $offset = $per_page * ($page);
			// $pipeline = [ 
					// array (
							// '$match' => array (
									// '$and' => $and_merged_array 
							// ) 
					// ),
					// array (
							// '$project' => array (
									// "doc_data.widget_data" => true,
									// "history" => true 
							// ) 
					// ),
					// array (
							// '$limit' => $offset 
					// ),
					// array (
							// '$skip' => $offset - $per_page 
					// ) 
			// ];
			// $response = $this->mongo_db->command ( array (
					// 'aggregate' => $this->screening_app_col,
					// 'pipeline' => $pipeline 
			// ) );
			
			// $temp_result = [ ];
			// foreach ( $response ['result'] as $doc ) {
				// foreach ( $doc ['history'] as $date ) {
					// $time = $date ['time'];
					// if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						// array_push ( $temp_result, $doc );
						// break;
					// }
				// }
			// }
			// $result = array_merge ( $result, $temp_result );
		// }
		// foreach ( $dist_list as $dist ) {
			// $request ['Deficencies'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		// }
		
		//======================Deficencies divided into further parts=================================
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Anaemia" 
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
			$request ["Anaemia"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Vitamin Deficiency - Bcomplex" 
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
			$request ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Vitamin A Deficiency" 
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
			$request ["Vitamin A Deficiency"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Vitamin D Deficiency" 
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
			$request ["Vitamin D Deficiency"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"SAM/stunting" 
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
			$request ["SAM/stunting"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Goiter" 
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
			$request ["Goiter"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		//======================Deficencies deviced into further parts=================================
		
		
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
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	private function screening_pie_data_for_stage5($dates) {
		ini_set ( 'max_execution_time', 0 );
		ini_set('memory_limit','10G');
		
		$school_list = $this->get_all_schools ();
		// log_message('debug','schhhhhhhhhhhhhhhhhhhhhhhhhhoooooooooooooooooooooooooooooolllllllll'.print_r($school_list,true));
		$count = $this->mongo_db->count ( $this->screening_app_col );
		$add_count = $count+100;
		if ($count < $add_count) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 5000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 3 =================================================
		
		$requests = [ ];
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
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
			// log_message('debug','schhhhhhhhhhhhhhhhhhhhhhhhhhoooooooooooooooooooooooooooooolllllllll'.print_r($school_name,true));
			$request ["Over Weight"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Under Weight"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ========================================================================================
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Obese" 
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
			$request ["Obese"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		//============================================
		
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
			$request ["General"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Skin"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Others(Description/Advice)"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Ortho"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Postural"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Defects at Birth'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		// ==============================================================================
		
		// $and_merged_array = array ();
		
		// $deficencies = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$not' => array (
								// '$size' => 0 
						// ) 
				// ) 
		// );
		// $page5_exists = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$exists' => true 
				// ) 
		// );
		
		// $deficencies_str_empty = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => '' 
				// ) 
		// );
		// $deficencies_str_space = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => ' ' 
				// ) 
		// );
		// $deficencies_arr = array (
				// "doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						// '$ne' => [ ] 
				// ) 
		// );
		
		// array_push ( $and_merged_array, $deficencies_str_empty );
		// array_push ( $and_merged_array, $deficencies_str_space );
		// array_push ( $and_merged_array, $deficencies_arr );
		
		// array_push ( $and_merged_array, $deficencies );
		// array_push ( $and_merged_array, $page5_exists );
		
		// $result = [ ];
		// for($page = 1; $page < $loop; $page ++) {
			// $offset = $per_page * ($page);
			// $pipeline = [ 
					// array (
							// '$match' => array (
									// '$and' => $and_merged_array 
							// ) 
					// ),
					// array (
							// '$project' => array (
									// "doc_data.widget_data" => true,
									// "history" => true 
							// ) 
					// ),
					// array (
							// '$limit' => $offset 
					// ),
					// array (
							// '$skip' => $offset - $per_page 
					// ) 
			// ];
			// $response = $this->mongo_db->command ( array (
					// 'aggregate' => $this->screening_app_col,
					// 'pipeline' => $pipeline 
			// ) );
			
			// $temp_result = [ ];
			// foreach ( $response ['result'] as $doc ) {
				// foreach ( $doc ['history'] as $date ) {
					// $time = $date ['time'];
					// if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						// array_push ( $temp_result, $doc );
						// break;
					// }
				// }
			// }
			// $result = array_merge ( $result, $temp_result );
		// }
		
		// foreach ( $school_list as $school_name ) {
			// $request ['Deficencies'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		// }
		
		//======================Deficencies divided into further parts=================================
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Anaemia" 
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
			$request ["Anaemia"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin Deficiency - Bcomplex" 
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
			$request ["Vitamin Deficiency - Bcomplex"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin A Deficiency" 
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
			$request ["Vitamin A Deficiency"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
				$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin Deficiency - Bcomplex" 
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
			$request ["Vitamin Deficiency - Bcomplex"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin D Deficiency" 
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
			$request ["Vitamin D Deficiency"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		
				$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin Deficiency - Bcomplex" 
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
			$request ["Vitamin Deficiency - Bcomplex"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"SAM/stunting" 
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
			$request ["SAM/stunting"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
				$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Vitamin Deficiency - Bcomplex" 
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
			$request ["Vitamin Deficiency - Bcomplex"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		$merged_array = array (
			"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
					'$in' => array (
							"Goiter" 
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
			$request ["Goiter"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		
		//======================Deficencies divided into further parts=================================
		
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
			$request ['Childhood Diseases'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Without Glasses'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['With Glasses'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Colour Blindness'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Right Ear'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Left Ear'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ['Speech Screening'] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Oral Hygiene - Fair"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Oral Hygiene - Poor"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Carious Teeth"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Flourosis"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Orthodontic Treatment"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
		
		foreach ( $school_list as $school_name ) {
			$request ["Indication for extraction"] [base64_encode( strtolower ( $school_name ['school_name']) )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	public function update_screening_collection($date, $screening_duration) {
		if ($date) {
			$today_date = $date;
		} else {
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration ); // "Daily" ); //
		//log_message ( "debug", "datesssssssssssssssssssssssssssssssss--------------------" . print_r ( $dates, true ) );
		// ===================================stage1================================================
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) {
			//log_message ( "debug", "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii--------------------" . print_r ( $init_date, true ) );
			//log_message ( "debug", "eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee--------------------" . print_r ( $dates ['end_date'], true ) );
			$query = $this->mongo_db->where ( array (
					'pie_data.date' => $init_date 
			) )->count ( "healthcare201672020159570_screening_final_".$year );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ) );
			
			$temp_dates ['today_date'] = $init_date;
			$temp_dates ['end_date'] = $end_date;
			
			// $temp_dates = $this->get_start_end_date ( $init_date, "Daily" );
			
			if ($query == 0) {
				
				$pie_data = array (
						"pie_data" => array (
								'date' => $init_date 
						) 
				);
				
				$requests = $this->screening_pie_data_for_stage5 ( $temp_dates );
				$pie_data ['pie_data'] ['stage5_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage4_new ( $requests );
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3_new ( $requests );
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2_new ( $requests );
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				
				$requests = $this->screening_pie_data_for_stage1_new ( $requests );
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				
				$this->mongo_db->insert ( "healthcare201672020159570_screening_final_".$year, $pie_data );
				//log_message ( "debug", "tttttttttttttttttttttttttttttttttttttttttttttttttttttttt" . print_r ( $init_date, true ) );
				//exit();
			}
			$init_date = $end_date;
		}
		
		// ===================================stage1 end============================================
		
		// ============================================================stage 2===============================================
		
		// ============================================================end of stage 2 =======================================
		
		// ===================================================insert into db=================================================
	}
	public function update_screening_collection1($date, $screening_duration) {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration ); // "Daily" ); //
		//log_message ( "debug", "datesssssssssssssssssssssssssssssssss--------------------" . print_r ( $dates, true ) );
		// ===================================stage1================================================
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) {
			//log_message ( "debug", "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii--------------------" . print_r ( $init_date, true ) );
			//log_message ( "debug", "eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee--------------------" . print_r ( $dates ['end_date'], true ) );
			$query = $this->mongo_db->where ( array (
					'pie_data.date' => $init_date 
			) )->count ( $this->screening_app_col_screening );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ) );
			
			$temp_dates ['today_date'] = $init_date;
			$temp_dates ['end_date'] = $end_date;
			
			// $temp_dates = $this->get_start_end_date ( $init_date, "Daily" );
			
			if ($query == 0) {
				
				$pie_data = array (
						"pie_data" => array (
								'date' => $init_date 
						) 
				);
				//log_message ( "debug", "before stagesssssssssssssssssssssssssss--------------------" );
				$requests = $this->screening_pie_data_for_stage1 ( $temp_dates );
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2 ( $temp_dates );
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3 ( $temp_dates );
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage4 ( $temp_dates );
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage5 ( $temp_dates );
				$pie_data ['pie_data'] ['stage5_pie_vales'] = $requests;
				
				$this->mongo_db->insert ( $this->screening_app_col_screening, $pie_data );
				//log_message ( "debug", "tttttttttttttttttttttttttttttttttttttttttttttttttttttttt" . print_r ( $init_date, true ) );
			}
			$init_date = $end_date;
		}
		
		// ===================================stage1 end============================================
		
		// ============================================================stage 2===============================================
		
		// ============================================================end of stage 2 =======================================
		
		// ===================================================insert into db=================================================
	}
	public function get_last_screening_update($date = false,$today_date,$screening_duration = "Yearly") {
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		if ($screening_duration != "Yearly"){
			$get_year = explode(" ",$screening_duration);
			$year = $get_year[0];
		}
		else
		{
			$get_year = explode("-",$today_date);
			$year = $get_year[0];
		}
		
		$query = $this->mongo_db->limit ( 1 )->orderBy ( array (
				'pie_data.date' => - 1 
		) )->select ( 'pie_data.date' )->get ( 'healthcare201672020159570_screening_final_'.$year );
		
		if (isset ( $query ) && ! empty ( $query ) && (count ( $query ) > 0)) {
			return "Last update on : " . substr ( $query [0] ['pie_data'] ['date'], 0, 10 );
		} else {
			return "No updates yet.";
		}
	}
	public function get_all_screenings($date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}		
		
		
		//ini_set ( 'memory_limit', '10G' );
				// $query = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',"TSWREIS SANGAREDDY(G),MEDAK")->get($this->screening_app_col);
				// $array = [];
				// foreach ($query as $doc){
						// if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])) && is_array($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])){
							
							// array_push($array,$doc['history']['last_stage']['time']);			
				// }}
				// array_unique($array);
				// log_message('debug','dtaesssssssssssssssssssssssssssssssssssssssssssssssss======='.print_r($array,true));
		
		
				 /* ini_set ( 'memory_limit', '10G' );
				$query = $this->mongo_db->get($this->screening_app_col);
				foreach ($query as $doc){
				if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'])){
					if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'])){
						if(isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])){
							if(($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'] > 0) && ($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'] != "") && ($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'] > 0) && ($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'] != "")){
							$height = ($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']/100);
							$weight = $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'];
							
							$bmi = ($weight / ($height * $height));
							$bmi    = round($bmi,1);
							$doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%'] = $bmi;
							
						 if ($bmi <= 18.5) 
						  {
							 if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])) && is_array($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])){
								 $search = array_search ( "Under Weight" , $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] );
								 if($search === false){
								 //}else{
									 array_push($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'], "Under Weight");
								 }
							 }else{
								 $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] = [];
								 array_push($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'], "Under Weight");
							 }
							 //==================================
							 $search = array_search ( "Over Weight" , $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] );
								 if($search === false){
								 }else{
									 unset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'][$search]);
								 }
							 //==================================
						  } 
						  else if ($bmi >= 18.5 && $bmi <= 24.9) 
						  { 
							//$normal++;
						  }
						  else
						  {
							  if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])) && is_array($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])){
								 $search = array_search ( "Over Weight" , $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] );
								 if($search === false){
								 //}else{
									 array_push($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'], "Over Weight");
								 }
							 }else{
								 $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] = [];
								 array_push($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'], "Over Weight");
							 }
							 //==================================
							 $search = array_search ( "Under Weight" , $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] );
								 if($search === false){
								 }else{
									 unset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'][$search]);
								 }
							 //==================================
							 //==================================
							 $search = array_search ( "Over weight" , $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'] );
								 if($search === false){
								 }else{
									 unset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'][$search]);
								 }
							 //==================================
						  }
						  //log_message('debug','searchingdoccccccccccccccccccccccccccccccccc======='.print_r($doc['doc_data']['widget_data'],true));
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				}}}}}   */
				
				
				// ini_set ( 'memory_limit', '10G' );
				// $query = $this->mongo_db->get($this->screening_app_col);
				// foreach ($query as $doc){
						// if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])) && (isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up'])) && (isset($doc['doc_data']['widget_data']['page6']['With Glasses'])) && (isset($doc['doc_data']['widget_data']['page6']['Without Glasses'])) && (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness'])) && (isset($doc['doc_data']['widget_data']['page7'][' Auditory Screening'])) && (isset($doc['doc_data']['widget_data']['page8']['Dental Check-up']))){
							// $doc['doc_data']['widget_data']['page3'] = [];
						  
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// }}
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		// ================================================== for generated analytics
		ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( "healthcare201672020159570_screening_final_".$year );
		
		$requests ['Physical Abnormalities'] = 0;
		$requests ['General Abnormalities'] = 0;
		$requests ['Eye Abnormalities'] = 0;
		$requests ['Auditory Abnormalities'] = 0;
		$requests ['Dental Abnormalities'] = 0;
		
		foreach ( $pie_data as $each_pie ) {
			
			$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [0] ['value'];
			$requests ['General Abnormalities'] = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [1] ['value'];
			$requests ['Eye Abnormalities'] = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [2] ['value'];
			$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [3] ['value'];
			$requests ['Dental Abnormalities'] = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [4] ['value'];
		}
		
		$result = [ ];
		foreach ( $requests as $request => $req_value ) {
			$req ['label'] = $request;
			$req ['value'] = $req_value;
			array_push ( $result, $req );
		}
		return $result;
		
		// ===============================================
		// $pie_data = [];
		// for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) {
		
		// $end_date = date ( "Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ) );
		
		// $temp_dates ['today_date'] = $init_date;
		// $temp_dates ['end_date'] = $end_date;
		
		// $requests = $this->screening_pie_data_for_stage1 ( $temp_dates );
		// $temp_pie['pie_data'] ['stage1_pie_vales'] = $requests;
		// array_push($pie_data,$temp_pie);
		
		// $init_date = $end_date;
		// }
		// log_message("debug","ppppppppppppppppppppppppppppppppscreenenenenene=====".print_r($pie_data,true));
		
		// $requests ['Physical Abnormalities'] = 0;
		// $requests ['General Abnormalities'] = 0;
		// $requests ['Eye Abnormalities'] = 0;
		// $requests ['Auditory Abnormalities'] = 0;
		// $requests ['Dental Abnormalities'] = 0;
		
		// foreach ( $pie_data as $each_pie ) {
		
		// $requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [0] ['value'];
		// $requests ['General Abnormalities'] = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [1] ['value'];
		// $requests ['Eye Abnormalities'] = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [2] ['value'];
		// $requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [3] ['value'];
		// $requests ['Dental Abnormalities'] = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_vales'] [4] ['value'];
		// }
		
		// $result = [ ];
		// foreach ( $requests as $request => $req_value ) {
		// $req ['label'] = $request;
		// $req ['value'] = $req_value;
		// array_push ( $result, $req );
		// }
		// return $result;
	}
	public function get_drilling_screenings_abnormalities($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
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
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( "healthcare201672020159570_screening_final_".$year );
		
		switch ($type) {
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
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
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
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
				
				// $request ['label'] = 'Deficencies';
				// $request ['value'] = 0;
				
				// foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					// $request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
				// }
				// array_push ( $requests, $request );
				
				//==========================================Deficencies divided
				
				$request ['label'] = 'Anaemia';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Vitamin Deficiency - Bcomplex';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				
				$request ['label'] = 'Vitamin A Deficiency';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [11] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				
				$request ['label'] = 'Vitamin D Deficiency';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [12] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				
				$request ['label'] = 'SAM/stunting';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				
				$request ['label'] = 'Goiter';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [14] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				//==========================================Deficencies divided
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [15] ['General Abnormalities'] ['value'];
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
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [18] ['Eye Abnormalities'] ['value'];
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
				
				return $requests;
				break;
			
			default :
				break;
		}
	}
	public function get_drilling_screenings_abnormalities1($data, $date = false, $screening_duration = "Yearly") {
		// $query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data ['label'];
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		ini_set ( 'memory_limit', '1G' );
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		// //log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		
		switch ($type) {
			case "Physical Abnormalities" :
				$requests = [ ];
				$request ['label'] = 'Over Weight';
				// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->count($this->screening_app_col);
				
				$merged_array = array (
						"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
								'$in' => array (
										"Over Weight" 
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
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->count($this->screening_app_col);
				
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
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "General Abnormalities" :
				$requests = [ ];
				$request ['label'] = 'General';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array()))->count($this->screening_app_col);
				
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
								'$ne' => array () 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $general_str_empty );
				array_push ( $and_merged_array, $general_str_space );
				array_push ( $and_merged_array, $general_arr );
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
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Ortho" => array()))->count($this->screening_app_col);
				// $request['value'] = $query;
				
				$and_merged_array = array ();
				
				$ortho = array (
						"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
								'$not' => array (
										'$size' => 0 
								) 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $ortho );
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
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array(), "doc_data.widget_data.page4" => array()))->count($this->screening_app_col);
				
				$and_merged_array = array ();
				
				$postural = array (
						"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
								'$not' => array (
										'$size' => 0 
								) 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $postural );
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
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "",
						"doc_data.widget_data.page6.Without Glasses.Left" => "",
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6",
						"doc_data.widget_data.page6" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6",
						"doc_data.widget_data.page6" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => array (
								"Yes" 
						),
						"doc_data.widget_data.page7.Colour Blindness.Left" => array (
								"Yes" 
						) 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail",
						"doc_data.widget_data.page8" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail",
						"doc_data.widget_data.page8" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene';
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage3_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( "healthcare201672020159570_screening_final_".$year );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		switch ($type) {
			case "Over Weight" :
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.District","Nalgonda")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = "Nalgonda";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				//log_message ( "debug", "iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================" );
				
				// }
				
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
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Others(Description/Advice)"] );
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
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			// case "Deficencies" :
				
				// $requests = [ ];
				// foreach ( $pie_data as $each_pie ) {
					// $requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Deficencies"] );
				// }
				
				// $request = [ ];
				// foreach ( $requests as $doc ) {
					// if (isset ( $request [$doc ['label']] )) {
						// $request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					// } else {
						// $request [$doc ['label']] = $doc ['value'];
					// }
				// }
				
				// $final_values = [ ];
				// foreach ( $request as $dist => $count ) {
					// $result ['label'] = $dist;
					// $result ['value'] = $count;
					// array_push ( $final_values, $result );
				// }
				
				// return $final_values;
				// break;
				
				//========================================Deficencies divided
				
			case "Anaemia" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Anaemia"] );
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
				
			case "Vitamin Deficiency - Bcomplex" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Vitamin Deficiency - Bcomplex"] );
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
				
				
			case "Vitamin A Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Vitamin A Deficiency"] );
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
				
			case "Vitamin D Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Vitamin D Deficiency"] );
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
			
			case "SAM/stunting" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["SAM/stunting"] );
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
				
			case "Goiter" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Goiter"] );
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
				
				//========================================Deficencies divided
			
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
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_districts1($data) {
		// $query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data ['label'];
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				break;
			
			case "Under Weight" :
				
				ini_set ( 'memory_limit', '512M' );
				
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
				// //log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWRS-CHITKUL,MEDAK")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = "TSWREIS CHITKUL(G),MEDAK";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// //log_message("debug","iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================");
				
				// }
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage4_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( "healthcare201672020159570_screening_final_".$year );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$dist = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Obese"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Obese"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dist )] );
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
				
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] );
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
			
			// case "Deficencies" :
				
				// $requests = [ ];
				// foreach ( $pie_data as $each_pie ) {
					// if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] != null)
						// $requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] );
				// }
				
				// $request = [ ];
				// foreach ( $requests as $doc ) {
					// if (isset ( $request [$doc ['label']] )) {
						// $request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					// } else {
						// $request [$doc ['label']] = $doc ['value'];
					// }
				// }
				
				// $final_values = [ ];
				// foreach ( $request as $dist => $count ) {
					// $result ['label'] = $dist;
					// $result ['value'] = $count;
					// array_push ( $final_values, $result );
				// }
				
				// return $final_values;
				// break;
				
			//=================================Deficencies divided====================
			
			case "Anaemia" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Anaemia"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Anaemia"] [strtolower ( $dist )] );
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
				
			case "Vitamin Deficiency - Bcomplex" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dist )] );
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
				
			case "Vitamin A Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin A Deficiency"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin A Deficiency"] [strtolower ( $dist )] );
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
			
			case "Vitamin D Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin D Deficiency"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin D Deficiency"] [strtolower ( $dist )] );
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
			
			case "SAM/stunting" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["SAM/stunting"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["SAM/stunting"] [strtolower ( $dist )] );
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
				
			case "Goiter" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Goiter"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Goiter"] [strtolower ( $dist )] );
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
			
			//=================================Deficencies divided====================
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] );
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
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] );
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
	public function get_drilling_screenings_schools1($data) {
		$obj_data = json_decode ( $data, true );
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$dist = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Under Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				// $search = array("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No");
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( "healthcare201672020159570_screening_final_".$year );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		//log_message ( "debug", "obbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbjjjjjjjjjjjjj" . print_r ( $school_name, true ) );
		switch ($type) {
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode($school_name) ] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode($school_name)] )) {
						log_message("debug","pieeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee".print_r($each_pie,true));
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode($school_name)] );
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
				
			case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			// case "Deficencies" :
				
				// $requests = [ ];
				// foreach ( $pie_data as $each_pie ) {
					// if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode($school_name)] ))
						// $requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode($school_name)] );
				// }
				
				// return $requests;
				// break;
				
			//==========================================Deficencies divided================
			
			case "Anaemia" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
				
			case "Vitamin Deficiency - Bcomplex" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
				
			case "Vitamin A Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
				
			case "Vitamin D Deficiency" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "SAM/stunting" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
				
			case "Goiter" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			//==========================================Deficencies divided================
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode($school_name)] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode($school_name)] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode($school_name)] );
				}
				
				return $requests;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_students1($data) {
		$obj_data = json_decode ( $data, true );
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"_id",
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Under Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_districts_prepare_pie_array($query) {
		$requests = [ ];
		
		$dist_list = $this->get_all_district ();
		
		$dist_arr = [ ];
		foreach ( $dist_list as $dist ) {
			array_push ( $dist_arr, $dist ['dt_name'] );
		}
		
		foreach ( $dist_arr as $districts ) {
			$request ['label'] = $districts;
			$count = 0;
			if ($query) {
				foreach ( $query as $dist ) {
					if (isset ( $dist ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] )) {
						if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $districts )) {
							$count ++;
						}
					}
				}
			}
			$request ['value'] = $count;
			array_push ( $requests, $request );
		}
		
		return $requests;
	}
	public function get_drilling_screenings_schools_prepare_pie_array($query, $dist) {
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == $dist) {
						array_push ( $search_result, $doc );
					}
				}
			}
			$request = [ ];
			foreach ( $search_result as $doc ) {
				if (isset ( $request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] )) {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] ++;
				} else {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] = 1;
				}
			}
			
			//log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $request, true ) );
			$final_values = [ ];
			foreach ( $request as $school => $count ) {
				//log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $school, true ) );
				//log_message ( "debug", "ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc" . print_r ( $count, true ) );
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			//log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $final_values, true ) );
			
			return $final_values;
		}
	}
	public function get_drilling_screenings_students_prepare_pie_array($query, $school_name) {
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == $school_name) {
						array_push ( $search_result, $doc ['_id']->{'$id'} );
					}
				}
			}
			
			return $search_result;
		}
	}
	public function get_drilling_screenings_students_docs($_id_array) {
		$docs = [ ];
		ini_set ( 'memory_limit', '10G' );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data' 
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
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=> -1))->where( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			
			
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['tmreis_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->notes_col );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['tmreis_schools'] );
			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['tmreis_health_supervisors'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
			$result ['hs'] = false;
			return $result;
		}
	}
	public function drill_down_screening_to_students_doc($_id) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
		if ($query) {
			
			return $query;
		} else {
			
			return false;
		}
	}
	
	// *************************************************
	
	/**
	 * Helper: Prepares IP address string for database insertion.
	 *
	 * @return string
	 */
	protected function _prepare_ip($ip_address) {
		return $ip_address;
	}
	public function user_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function cc_user_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['tmreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function school_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function doctor_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['tmreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Sets an error message
	 */
	public function set_error($error) {
		$this->errors [] = $error;
		return $error;
	}
	
	/**
	 * Applies delimiters and returns themed errors
	 */
	public function errors() {
		$_output = '';
		foreach ( $this->errors as $error ) {
			$error_lang = $this->lang->line ( $error ) ? $this->lang->line ( $error ) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $error_lang . $this->error_end_delimiter;
		}
		
		return $_output;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Return errors as an array, langified or not
	 */
	public function errors_array($langify = TRUE) {
		if ($langify) {
			$_output = array ();
			foreach ( $this->errors as $error ) {
				$errorLang = $this->lang->line ( $error ) ? $this->lang->line ( $error ) : '##' . $error . '##';
				$_output [] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		} else {
			return $this->errors;
		}
	}
	
	/**
	 * Generates a random salt value.
	 */
	public function salt() {
		return substr ( md5 ( uniqid ( rand (), true ) ), 0, $this->salt_length );
	}
	
	/**
	 * Hashes the password to be stored in the database.
	 */
	public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE) {
		if (empty ( $password )) {
			return FALSE;
		}
		
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			return $this->bcrypt->hash ( $password );
		}
		
		if ($this->store_salt && $salt) {
			return sha1 ( $password . $salt );
		} else {
			$salt = $this->salt ();
			return $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	}
	public function get_schools_by_dist_id($dist_id) {
		if ($dist_id == "All") {
			// ini_set ( 'memory_limit', '1G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->get ( $this->screening_app_col );
			
			ini_set ( 'memory_limit', '1G' );
			$count = $this->mongo_db->count ( $this->screening_app_col );
			if ($count < 10000) {
				$per_page = $count;
				$loop = 2; // $count / $per_page;
			} else {
				$per_page = 10000;
				$loop = $count / $per_page;
			}
			
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
						'aggregate' => $this->screening_app_col,
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
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['tmreis_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}
	}
	public function get_students_by_school_name($school_name, $dist_name) {
		if ($school_name == "All") {
			// log_message("debug","111111111111111111111111111111111111111".print_r(strtoupper($dist_name),true));
			// ini_set ( 'memory_limit', '1G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->where ( "doc_data.widget_data.page2.Personal Information.District", strtoupper($dist_name) )->get ( $this->screening_app_col );
			ini_set ( 'memory_limit', '1G' );
			$count = $this->mongo_db->count ( $this->screening_app_col );
			if ($count < 10000) {
				$per_page = $count;
				$loop = 2; // $count / $per_page;
			} else {
				$per_page = 10000;
				$loop = $count / $per_page;
			}
			
			$merged_array = array (
					"doc_data.widget_data.page2.Personal Information.District" => strtoupper ( $dist_name ) 
			);
			
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
				
				$result = array_merge ( $result, $response ['result'] );
			}
			
			return $result;
		} else {
			ini_set ( 'max_execution_time', 0 );
			//log_message ( "debug", "22222222222222222222222222222222222222" . print_r ( $school_name, true ) );
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->orderBy ( array (
					'Hospital Unique ID' => 1 
			) )->where ( 'doc_data.widget_data.page2.Personal Information.School Name', $school_name )->get ( $this->screening_app_col );
			return $query;
		}
	}
	public function get_reported_schools_count_by_dist_name($dist_id, $date) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $date )->get ( $this->absent_app_col );
		
		$count = 0;
		$r2h = 0;
		$sick = 0;
		foreach ( $query as $report ) {
			if (strtolower ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dist_id )) {
				$count ++;
				$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
				$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
				
				$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
			}
		}
		$data ['count'] = $count;
		$data ['sick'] = $sick;
		$data ['r2h'] = $r2h;
		
		return $data;
	}
	
	/*
	/ Get bmi values based  on selected Month
	*/
	public function get_reported_schools_bmi_count_by_dist_name($date,$dt_name,$school_name){
		
		if($dt_name == "All" && $school_name == "All")
		{
			$query = $this->mongo_db->select ( array (
					"doc_data.widget_data","doc_data.school_details") )
					->whereLike("doc_data.widget_data.page1.Student Details.BMI_values.month" , $date)->get ( $this->bmi_app_col );
		
		}
		else
		{
			$query = $this->mongo_db->select ( array (
					"doc_data.widget_data","doc_data.school_details") )
					->whereLike("doc_data.widget_data.page1.Student Details.BMI_values.month" , $date)->where(array("doc_data.school_details.District" => strtoupper($dt_name), "doc_data.school_details.School Name" => $school_name))->get ( $this->bmi_app_col );
		
		}
		
		/* foreach($query as $doc_data)
		{
			$bmi_values = $doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values'];
		$count_bmi = count($bmi_values);
		log_message("debug","count_bmiiiiii".print_r($count_bmi,true));
		//$i = 0;
		$month[] = $date;
		for($i = 0; $i <= $count_bmi;$i++)
		{
			if(in_array('doc_data.widget_data.page1.Student Details.BMI_values.month',$month))
			{
				log_message("debug","naresh=============15403===".print_r($naresh,true));
			}
			
		}
		}
		exit() */;
		//$test = ['doc_data']['widget_data']['']
			/* $month = array ('doc_data.widget_data.page1.Student Details.BMI_values.month == ' => array (
						'$eq' => $date 
				) );
			
			
			$result = [ ];
				
				$pipeline = [ 
						array (
								'$project' => array (
										"doc_data.widget_data" => true,
										"history" => true 
								) 
						),
						array (
								'$match' => $month
								
						)
				];
				
				$response = $this->mongo_db->command ( array (
						'aggregate' => $this->bmi_app_col,
						'pipeline' => $pipeline 
				) );
				log_message("debug","query_nareshhhhhhhhhhhhhhhhhhhhhhhhhhhh".print_r($response,true));
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query; */
		/* $school_name = array();
		foreach($query as $unique)
		{
			//$query_naresh = array();
			$query_naresh_push = array();
			$stu_unique = $unique['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
			
			$after_explode = explode("_",$stu_unique);
			$school_code = $after_explode[1];
			$school_code = intval($school_code);
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->select ( array('school_name') )->where ( 'school_code', $school_code )->get ( $this->collections ['panacea_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			$school_name = $school_details[0]['school_name'];
			
			//$query_naresh = $school_name;
			array_push($query_naresh_push,$school_name);
			log_message("debug","queryyyyyyyyyyyyyyyyyyyyyyyy".print_r($query_naresh_push,true));
		}
		
		  array_merge_recursive($query,$query_naresh_push);
		log_message("debug","query_nareshhhhhhhhhhhhhhhhhhhhhhhhhhhh".print_r($query,true));
		
		exit(); */
		//echo print_r($query,true);
		//exit();
				
		return $query;
	}
	
	public function get_health_supervisors_school_id($id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( 'school_code', $id )->select ( array (
				'hs_name',
				'hs_mob' 
		) )->get ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	public function get_absent_school_details($school) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( "doc_data.widget_data.page1.Attendence Details.Select School", $school )->get ( $this->absent_app_col );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	public function get_student_count_school_name($school) {
		$count = $this->mongo_db->where ( 'doc_data.widget_data.page2.Personal Information.School Name', $school )->count ( $this->screening_app_col );
		return $count;
	}
	public function get_request_by_school_name($school_name, $date) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.0.time', $date )->get ( $this->request_app_col );
		
		$request = array ();
		foreach ( $query as $identifiers ) {
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$doc_school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if (strtolower ( $doc_school ) == strtolower ( $school_name )) {
					$req ['request'] = $identifiers;
					$req ['stud_details'] ['name'] = $doc [0] ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Name'];
					$req ['stud_details'] ['class'] = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['Class'];
					$req ['stud_details'] ['section'] = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['Section'];
					array_push ( $request, $req );
				}
			}
		}
		return $request;
	}
	public function get_reports_by_dist_name($dist_name) {
		$query = $this->mongo_db->select ( array (
				'school_name',
				'school_code',
				'school_mob',
				'contact_person_name' 
		) )->orderBy ( array (
				'school_name' => 1 
		) )->where ( 'dt_name', $dist_id )->get ( $this->request_app_col );
		return $query;
	}
	public function get_school_data_school_name($school_name) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->select ( array (
				'school_name',
				'school_code',
				'school_mob',
				'contact_person_name' 
		) )->orderBy ( array (
				'school_name' => 1 
		) )->where ( 'school_name', $school_name )->get ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_screening_pie_stage5($dates) {
		ini_set ( 'memory_limit', '10G' );
		$pie_stage5 = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		return $pie_stage5;
	}
	
	public function get_screening_pie_stage4($dates) {
		$pie_stage4 = $this->mongo_db->select ( array (
				'pie_data.stage4_pie_vales'
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		return $pie_stage4;
	}
	public function screening_pie_data_for_stage4_new($requests) {
		$school_list = $this->get_all_schools ();
		$school_in_dist = [ ];
		
		foreach ( $school_list as $school ) {
			$school_in_dist [base64_encode(strtolower ( $school ['school_name'] ))] = strtolower ( $school ['dt_name'] );
		}
		$request_stage4 = [ ];
		//log_message("debug","in 3333333333333333333333333333333333333333333333333=======".print_r($school_in_dist,true));
		foreach ( $requests as $screening_index => $screening_array ) {
			$request_stage4 [$screening_index] = [ ];
			//log_message("debug","in 11111111111111111111111111111111111111111111111111111111=======".print_r($request_stage4,true));
			foreach ( $screening_array as $school_name => $inner_data ) {
				//log_message("debug","in 222222222222222222222222222222222222222222222222222222=======".print_r($request_stage4,true));
				if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
					$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = null;
				}
				
				$school_data = [ ];
				if (count ( $inner_data ) > 0) {
					if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
						$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = [ ];
					}
					$school_data ['label'] = strtoupper ( base64_decode($school_name) );
					$school_data ['value'] = count ( $inner_data );
					array_push ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]], $school_data );
					// log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($request_stage4,true));
				}
			}
		}
		return $request_stage4;
	}
	public function screening_pie_data_for_stage3_new($requests) {
		$request_stage3 = [ ];
		foreach ( $requests as $request => $request_data ) {
			$request_stage3 [$request] = [ ];
			foreach ( $request_data as $dist_name => $dist_array ) {
				$dist_data ['label'] = strtoupper ( $dist_name );
				if (is_array ( $dist_array )) {
					$value_count = 0;
					foreach ( $dist_array as $school_array ) {
						$value_count = $value_count + $school_array ['value'];
					}
					$dist_data ['value'] = $value_count;
				} else {
					$dist_data ['value'] = count ( $dist_array );
				}
				
				array_push ( $request_stage3 [$request], $dist_data );
			}
		}
		return $request_stage3;
	}
	public function screening_pie_data_for_stage2_new($requests) {
		$request_stage2 = [ ];
		
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Over Weight"] as $doc ) {
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
		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Under Weight"] as $doc ) {
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
		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Obese"] as $doc ) {
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
		$stage_array ["Physical Abnormalities"] ["label"] = "Obese";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["General"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Skin"] as $doc ) {
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
		// $stage_array = [ ];
		// $stage_array ["General Abnormalities"] = [ ];
		
		// $request = [ ];
		// foreach ( $requests ["Deficencies"] as $doc ) {
			// if (isset ( $request [$doc ['label']] )) {
				// $request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			// } else {
				// $request [$doc ['label']] = $doc ['value'];
			// }
		// }
		// $total_count = 0;
		// foreach ( $request as $dist => $count ) {
			// $total_count = $total_count + $count;
		// }
		// $stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		// $stage_array ["General Abnormalities"] ['value'] = $total_count;
		// array_push ( $request_stage2, $stage_array );
		
		//===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Anaemia"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "Anaemia";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Vitamin Deficiency - Bcomplex"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin Deficiency - Bcomplex";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Vitamin A Deficiency"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin A Deficiency";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Vitamin D Deficiency"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "Vitamin D Deficiency";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["SAM/stunting"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "SAM/stunting";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Goiter"] as $doc ) {
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
		$stage_array ["General Abnormalities"] ["label"] = "Goiter";
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
	public function screening_pie_data_for_stage1_new($requests) {
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
		$stage_data ['value'] = $requests [22] ["Dental Abnormalities"] ['value'] + $requests [23] ["Dental Abnormalities"] ['value'] + $requests [24] ["Dental Abnormalities"] ['value'] + $requests [25] ["Dental Abnormalities"] ['value'] + $requests [26] ["Dental Abnormalities"] ['value'] + $requests [27] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	public function add_message($post,$msg_id)
	{
		$data = array(
				"message_id"   => get_unique_id(),
				"user_id"      => $post['user_id'],
				"user_name"    => $post['username'],
				"chat_room_id" => $msg_id,
				"message"      => $post['message'],
				"created_at"   => date("Y-m-d H:i:s")
		);
		$query = $this->mongo_db->insert($this->collections['tmreis_messages'],$data);
		
		if($query){
			$response['error'] = false;			
			$response['message'] = $data;
		}else{
			$response['error'] = true;
			$response['message'] = 'Failed send message ' . $stmt->error;
		}
		
		log_message('debug','TMREIS_APP=====CHAT_ROOMS=====$user_id==13660====='.print_r($response,true));
		return $response;
	}
	public function get_messages($msg_id)
	{
		$query = $this->mongo_db->where("chat_room_id",$msg_id)->get($this->collections['tmreis_messages']);
		return $query;
	}
	
	public function get_user_by_email($name, $email){
				
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=========.'.print_r($email,true));
		$user = $this->mongo_db->where("email",$email)->get($this->collections['tmreis_admins']);
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu=========.'.print_r($user,true));
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if($user){
			$temp["user_id"] = $user[0]['email'];
			$temp["name"] = $user[0]['username'];
			$temp["email"] = $user[0]['email'];
			$temp["created_at"] = $user[0]['registered_on'];
			
			// User with same email already existed in the db
			$response["error"] = false;
			$response["user"] = $temp;			
		}else{
			// Failed to create user
			$response["error"] = true;
			$response["message"] = "User not found.";
		}
		return $response;		
	}
	
	public function updateGcmID($login, $gcm_registration_id){
		$id_exists = $this->mongo_db->where("user_id",$login)->get($this->collections['tmreis_users_gcm']);
		$data = array(
			"user_id" => $login,
			"gcm_registration_id" => $gcm_registration_id
		);
		if($id_exists){
			$query = $this->mongo_db->where("user_id",$login)->set($data)->get($this->collections['tmreis_users_gcm']);
		}else{
			$query = $this->mongo_db->insert($this->collections['tmreis_users_gcm'],$data);
		}
		return $query;
	}
	
	public function get_sanitation_report_app()
	{
	  $query = $this->mongo_db->select(array('app_template'))->where('_id',$this->sanitation_app_col)->get($this->collections['records']);
	  return $query[0]['app_template'];
	
	}
	
	// ------------------------------------------------------------------------------------------------------------

	/**
	 * Helper: Get data to draw sanitation report pie based on the selected criteria ( Model )
	 *
	 * @param  string  $date              Selected date 
	 * @param  string  $search_criteria   Criteria question for sanitation report
	 * @param  string  $opt   			  Criteria option for sanitation report
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */
	 
	public function get_sanitation_report_pie_data($date, $search_criteria, $opt) {
	
	 $output 			     = array();
	 $sanitation_report      = array();
	 $sanitation_report['district_list']   = array();
	 $sanitation_report['schools_list']    = array();
	 $sanitation_report['attachment_list'] = array();
	 
	 $query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.external_attachments'),array())->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$date,$search_criteria=>$opt))->get($this->sanitation_app_col);
	 
	 $dist_list = $this->get_all_district ();
		
	 $dist_arr = [ ];
	 foreach ( $dist_list as $dist ) {
		array_push ( $dist_arr, $dist ['dt_name'] );
	 }
	
	foreach ( $dist_arr as $district_name ) {
	    $schools = array();
		$sanitation_attachments = array();
	    $sanitation_report['schools_list'][$district_name]  = array();
		$sanitation_report['attachment_list'][$district_name]  = array();
		$request ['label'] = $district_name;
		$count = 0;
		if ($query) {
			foreach ( $query as $dist ) {
				if (isset ( $dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] )) {
					if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] ) == strtolower ( $district_name )) {
					    if(!in_array($dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$schools))
						{
							$count ++;
							array_push($schools,$dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']);
							if(isset($dist ['doc_data']['external_attachments']) && !empty($dist ['doc_data']['external_attachments']))
							{
						       $path = array();
							   
						       foreach($dist ['doc_data']['external_attachments'] as $key=>$attachments)
							   {
							     array_push($path,$attachments['file_path']);
							   }
							   
							   $sanitation_attachments[$dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']] = $path;
							}
						}
					}
				}
			}
		}
		$request ['value'] = $count;
		array_push ( $output, $request );
		$sanitation_report['schools_list'][$district_name]    = $schools;
		$sanitation_report['attachment_list'][$district_name] = $sanitation_attachments;
	}
	
	$sanitation_report['district_list'] = $output;
		
	 if($sanitation_report)
	     return $sanitation_report;
	 else
		 return false;
		
	}
	
	public function get_sanitation_report_pie_schools_data($date = FALSE)
	{
		// Variables
		$all_schools               = array();
		$all_schools_district      = array();
		$all_schools_name      	   = array();
		$submitted_schools 	       = array();
		$submitted_school_district = array();
		$submitted_school_name     = array();
		$not_submitted_schools	   = array();
		$schools_data              = array();
		$not_submitted_dist        = array();
		
		/*$schools_list = $this->get_all_schools();
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'doc_data.widget_data.page4.Declaration Information.Date:',$today_date )->get ( $this->sanitation_app_col );
		
		foreach ( $query as $doc ) {
			    if(!in_array($doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] );
					array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'] );
		        }
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		return $schools_data; */


		$not_submitted_dist        = array();
		
		$all_schools_mobile        = array();
		$all_schools_cpn      	   = array();
		$submitted_school_mob 	   = array();
		$submitted_school_person   = array();
		$not_submitted_school_mob 	   = array();
		$not_submitted_school_person   = array();
		
		$schools_list = $this->get_all_schools();
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
			$all_schools_mobile[$school_data['school_name']] = $school_data['school_mob'];
			$all_schools_cpn[$school_data['school_name']] = $school_data['contact_person_name'];
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'doc_data.widget_data.page4.Declaration Information.Date:',$today_date )->get ( $this->sanitation_app_col );
		
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====716=='.print_r($query,true));
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====717=='.print_r($today_date,true));
		
		foreach ( $query as $doc ) {
			    if(!in_array($doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] );
					array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'] );
					
					if(isset($all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']])){
						array_push ( $submitted_school_mob,$all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']] );
						array_push ( $submitted_school_person,$all_schools_cpn[$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']] );
					}else{
						array_push ( $submitted_school_mob,"" );
						array_push ( $submitted_school_person,"" );
					}
					
		        }
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		$submitted_schools['mobile']       = $submitted_school_mob;
		$submitted_schools['person_name']  = $submitted_school_person;
		
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		   
			if(isset($all_schools_mobile[$school_name])){
				array_push ( $not_submitted_school_mob,$all_schools_mobile[$school_name] );
				array_push ( $not_submitted_school_person,$all_schools_cpn[$school_name] );
			}else{
				array_push ( $not_submitted_school_mob,"" );
				array_push ( $not_submitted_school_person,"" );
			}
		   
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		$not_submitted_schools['mobile']       = $not_submitted_school_mob;
		$not_submitted_schools['person_name']  = $not_submitted_school_person;
		
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		
		return $schools_data;
	}
	
	public function insert_ehr_note($post)
	{
		$token = $query = $this->mongo_db->insert($this->notes_col,$post);
	
		return $token;
	}
	
	public function delete_ehr_note($doc_id)
	{
		$query = $this->mongo_db->where ( array (
				"_id" => new MongoId ( $doc_id ) 
		) )->delete ( $this->notes_col );
	
		return $query;
	}
	
	 public function tmreis_chronic_cases_count()
    {
     $query = $this->mongo_db->get($this->collections['tmreis_chronic_cases']);
	 return count($query);
    }
	
	function get_chronic_cases_model($limit, $page)
	{
	    $offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->get($this->collections['tmreis_chronic_cases']);
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_chronic_cases_model_for_data_table()
	{
	    $this->mongo_db->orderBy(array('created_time' => -1));
		$query = $this->mongo_db->get($this->collections['tmreis_chronic_cases']);
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_all_chronic_unique_ids_model()
	{
	    $this->mongo_db->orderBy(array('_id' => 1));
		$query = $this->mongo_db->select(array('student_unique_id','case_id','scheduled_months','school_name'),array())->getWhere($this->collections['tmreis_chronic_cases'],array('followup_scheduled'=>'true'));
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	function create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$month_names,$case_id)
	{
	   $update_array = array(
	   'start_date'         => $start_date,
	   'medication_schedule'=> $medication_schedule,
	   'treatment_period'   => $treatment_period,
	   'scheduled_months'   => $month_names,
	   'followup_scheduled' => "true");
	   
	   $updated = $this->mongo_db->where(array('student_unique_id'=>$unique_id,'case_id'=>$case_id))->set($update_array)->update($this->collections['tmreis_chronic_cases']);
	   
	   if($updated)
		   return TRUE;
	   else
		   return FALSE;
	}
	
	function calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken)
	{
	   $medication_schedule = array();
	   $query = $this->mongo_db->select(array(),array())->getWhere($this->collections['tmreis_chronic_cases'],array('student_unique_id'=>$unique_id,'case_id'=>$case_id));  
	   
	   foreach($query as $value)
	   {
	     $medication_schedule = $value['medication_schedule'];
	   }
	   
	   $schedule_count = count($medication_schedule);
	   
	   if(isset($medication_taken) && !empty($medication_taken))
	   {
	     $taken_count = count($medication_taken);
	   }
	   else
	   {
         $taken_count = 0;
	   }
	   $compliance_percentage = ($taken_count/$schedule_count)*100;
	   return $compliance_percentage;
	}
	
	function update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date)
	{
		$check_query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id,"medication_taken"=>array('$elemMatch'=>array("date"=>$selected_date)));
		 
		$is_already_updated = $this->mongo_db->where($check_query)->get($this->collections['tmreis_chronic_cases']);
		
		if($is_already_updated)
		{
	       return "ALREADY_UPDATED";
		}
		else
		{
	       $datewise_update = array("date"=>$selected_date,"taken_slots"=>$medication_taken);
	  
		   $query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id);
		
		   $update = array('$push'=>array("medication_taken"=>$datewise_update));
			 
		   $response = $this->mongo_db->command(array( 
			'findAndModify' => $this->collections['tmreis_chronic_cases'],
			'query'         => $query,
			'update'        => $update,
			'upsert'        => true
			));
		
			if($response['ok'])
			{
			   return "UPDATE_SUCCESS";
			}
			else
			{
		       return "UPDATE_FAIL";
			}
		}
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Fetch Student's pill compliance data
	 *
	 * @param string $case_id 			 Case ID
	 * @param string $student_unique_id  Hospital Unique ID
	 *
	 * @return array
	 *
	 * @author Selva
	 */
	 
	public function fetch_student_pill_compliance_data($case_id,$student_unique_id)
	{
	    $where_clause = array(
		'case_id' => $case_id,
		'student_unique_id'=> $student_unique_id
		);
		
		$query = $this->mongo_db->where($where_clause)->get($this->collections['tmreis_chronic_cases']);
        return $query;
	}
	
	function get_dup_docs($docs_arr){
		$doc_matchs = [];
		$matched_doc_ids = [];
		foreach($docs_arr as $id){
			$doc_sech = $this->mongo_db->select(array("_id",'doc_data'))->where("doc_data.widget_data.page1.Personal Information.Hospital Unique ID" , $id)->get($this->screening_app_col);
			$inner_doc_count = count($doc_sech);
			log_message('debug','111111111111111111111111111111111111'.print_r($inner_doc_count,true));
			if($inner_doc_count >1){
				log_message('debug','2222222222222222222222222222222222'.print_r($id,true));
				if (!in_array($id, $matched_doc_ids)) {
					log_message('debug','33333333333333333333333333333333333333333'.print_r($doc_matchs,true));
					for ($doc_pointer = 0 ; $doc_pointer < $inner_doc_count ; $doc_pointer++){
						$doc_pointer_id = (string)$doc_sech[$doc_pointer]['_id'];
						array_push($matched_doc_ids, $doc_pointer_id);
						//log_message('debug','44444444444444444444444444444444444444444444444'.print_r($matched_doc_ids,true));
					}
					
					$doc_record['doc_id'] = $doc_sech[0]['doc_data']['widget_data']['page1']['Personal Information']['Name'];
					$doc_record['matched_count'] = $inner_doc_count;
					$doc_record['document1'] = (string)$doc_sech[0]['_id'];
					$doc_record['document2'] = (string)$doc_sech[1]['_id'];
					$doc_record['uid'] = $id;
					array_push($doc_matchs, $doc_record);
					
				}
				
			}
		}
		return $doc_matchs;
		
	}
	
	function get_document($doc_id){
		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get($this->screening_app_col);
		
	
		if (isset($query[0])) {
			log_message('debug','fffffffffffffffffffffffffffffffffffffffffff');
			return json_decode(json_encode($query[0]),true);
		}else{
			return false;
		}
	
	}
	
	function get_all_docs_in_uid_no($uid_no){
		
		$query = $this->mongo_db->select(array("doc_data.widget_data"))->where("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid_no)->get($this->screening_app_col);
	
		if (isset($query)) {
			log_message('debug','fffffffffffffffffffffffffffffffffffffffffff');
			return json_decode(json_encode($query),true);
		}else{
			return false;
		}
	
	}

	public function get_sanitation_report_data_with_date($date,$school_name)
	{
	    if ($date) {
			$selected_date = $date;
		} else {
			$selected_date = $this->today_date;
		}
		
		$this->mongo_db->limit(1)->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$selected_date,'doc_data.widget_data.page4.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_app_col);
		if($query)
			return $query;
		else
			return FALSE;
	}
	
	public function insert_request_note($post)
	{
		$query_request = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->get ( $this->collections['tmreis_req_notes'] );
		
		//log_message('debug','notessssssssssssssssspost===query_request==========================='.print_r($query_request,true));
		
		$notes = array(
			'note_id' => get_unique_id(),
			'note'	  => $post['note'],
			'username'=> $post['username'],
			'datetime'=> $post['datetime']
		);
		
		if(isset($query_request[0]['notes_data'])){
			array_push($query_request[0]['notes_data'],$notes);
		}
		else{
			$query_request[0]['notes_data'] = [];
			array_push($query_request[0]['notes_data'],$notes);
			$query_request[0]["req_doc_id"] = $post ['doc_id'];
		}
		
		$is_notes = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->count( $this->collections ['tmreis_req_notes'] );
		
		if($is_notes > 0){
			$token = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->set($query_request[0])->update( $this->collections ['tmreis_req_notes'] );
		}else{
			$token = $this->mongo_db->insert( $this->collections ['tmreis_req_notes'], $query_request[0]);
		}
	
	   // log_message('debug','notessssssssssssssssspost===token==========================='.print_r($token,true));

		return $token;
	}
	
	public function get_today_news_feeds($date){
		
		if(!$date){
			$date = date('Y-m-d');
		}
		
		$display_date = array (
				"display_date" => array (
						'$regex' => $date
				) 
		);
		$result = [ ];
			
			$pipeline = [ 
					
					array('$match' => $display_date)
					
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->collections ['tmreis_news_feed'],
					'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
	
		return $query;
	
	}

	public function add_news_feed($news_data){
		
		$query = $this->mongo_db->insert ( $this->collections ['tmreis_news_feed'], $news_data );
	
		return $query;
	
	}
	
	public function get_all_news_feeds(){
	
		$query = $this->mongo_db->get ( $this->collections ['tmreis_news_feed'] );
	
		return $query;
	
	}
	
	public function delete_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->delete($this->collections['tmreis_news_feed']);
		return $query;
	}
	
	public function get_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->get($this->collections['tmreis_news_feed']);
		return $query[0];
	}
	
	public function update_news_feed($news_data,$news_id)
	{
		
		$query = $this->mongo_db->where(array("_id"=>new MongoId($news_id)))->set($news_data)->update($this->collections['tmreis_news_feed']);
		return $query;
	}
	
	/* field officer report */
	public function field_officer_report_model_todate($date = FALSE)
	{
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$field_officers = [ ];
		
		$field_officer['label'] = "School";
		$report = $this->mongo_db->where(array("case_type" => "School",'doc_data.school_type'=>'TMREIS'))->whereLike ( 'time', $today_date )->count('field_officer_documents');
		$field_officer['value'] = $report;
		array_push($field_officers, $field_officer);
		
		$field_officer['label'] = "Hospital";
		$survey = $this->mongo_db->where(array("case_type" => "Hospital",'doc_data.school_type'=>'TMREIS'))->whereLike ( 'time', $today_date )->count('field_officer_documents');
		$field_officer['value'] = $survey;
		array_push($field_officers, $field_officer);
		
		$field_officer['label'] = "Dept";
		$hospital = $this->mongo_db->where(array("case_type" => "Dept"))->whereLike ( 'time', $today_date )->count('field_officer_documents');
		$field_officer['value'] = $hospital;
		array_push($field_officers, $field_officer);
		
		return $field_officers; 
		
	}
	
	public function tmreis_get_drilling_field_off_docs($case_type,$to_date) {
		
		
		if($case_type == "School")
		{
			$query["school"] = $this->mongo_db->where ( array("case_type"=>"School",'doc_data.school_type'=>'TMREIS') )->whereLike('time',$to_date)->get ( 'field_officer_documents' );
			
			foreach($query["school"] as $res=>$schools)
			{
				$sc_code = intval($schools['doc_data']['school_code']);
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$result = $this->mongo_db->where ( 'school_code', $sc_code) ->select ( array (
				'school_name') )->get ( 'tmreis_schools' );
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
				
				$query["school"][$res]["schools"]= $result[0]['school_name'];
			}
			log_message('debug','drill_down_to_filed_officer_docs-model--===127==>'.print_r($query,true));
			return $query;
		}
		if($case_type == "Hospital")
		{
			$query["hospital"] = $this->mongo_db->where ( array("case_type"=>"Hospital",'doc_data.school_type'=>'TMREIS'))->whereLike('time',$to_date)->get ( 'field_officer_documents' ); 
			return $query;
		}
		if($case_type == "Dept")
		{
			$query["dept"] = $this->mongo_db->where ( array("case_type"=>"Dept") )->whereLike('time',$to_date)->get ( 'field_officer_documents' );
			return $query;
		}
		
	}
	
	public function tmreis_get_student_hospital_report($uid)
	{
		$this->mongo_db->orderBy(array('time' => -1));
		
		$qry = $this->mongo_db->whereLike ( "doc_data.student_id", $uid )->get ( 'field_officer_documents' );
		
		$result ['get_hospital_report'] = $qry;
		return $result;
	}
	
	
	
	/*
	*Fetchinhg BMI value with Unique id
	*author Naresh
	*/ 
	
	public function get_student_bmi_values($unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('healthcare201761916814158');
		log_message("debug","query==========12576".print_r($query,true));
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	
	public function get_all_rised_req()
	{
		$user_data = $this->session->userdata ( "customer" );
		
		$uniqueID = strtoupper(str_ireplace('.','_',substr($user_data['email'],0,strpos($user_data['email'],'@')-2)));
		
		//$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID", $uniqueID)->select(array("doc_data.widget_data","history","doc_properties"))->get($this->request_app_col);
		
		$hs_mail = array (
				"doc_data.widget_data.page1.Student Info.Unique ID" => array (
						'$regex' => $uniqueID
				) 
		);
		$last_stage = array (
				"history.last_stage" => array (
						'$exists' => true
				) 
		);
		
		$and_merged_array = array();
		
		array_push ( $and_merged_array, $hs_mail );
		array_push ( $and_merged_array, $last_stage );
		
		$result = [ ];
			
			$pipeline = [

					array('$match' => array (
									'$and' => $and_merged_array 
							))	
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->request_app_col,
					'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
		
		return $query;
	}
	function update_doc_for_disapprove($doc){
		$query = $this->mongo_db->where('_id', new MongoId($doc['_id']))->set($doc)->update($this->request_app_col);
		unset($doc['_id']);
		$query = $this->mongo_db->insert($this->request_app_col.'_shadow',$doc);
		return $query;
	}
	
	function get_workflow_stage_details($app_id,$collection,$select){
		$query = $this->mongo_db->where('_id', $app_id)->select($select)->get($collection);
		return $query[0];
	}
	
	//update personal Information
   public function get_update_personal_ehr_uid($uid) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
		log_message("debug","update personal Info for modelllllll114816".print_r($query,true));
		 if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $uid )->get ( $this->request_app_col );
			log_message("debug"," query_request update personal Info for modelllllll114816".print_r($query_request,true));
			$result ['screening'] = $query;
			///$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			//$result ['request'] = false;
			return $result;
		} 
	}
	
	public function update_student_ehr_model($unique_id,$doc_data)
	{
	  //$doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set($doc_data)->update($this->screening_app_col);
	  log_message('debug',"updateeeeeeeeeeeee".print_r($query,true));
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}
	
	function delete_doc_from_user_col($doc_id,$hs_user_col){
		$this->mongo_db->where(array('app_id' => $this->request_app_col, 'doc_id' => $doc_id))->delete($hs_user_col.'_web_docs');
		$this->mongo_db->where(array('app_id' => $this->request_app_col, 'doc_id' => $doc_id))->delete($hs_user_col.'_docs');
		$cc_users = $this->get_all_cc_users();
		foreach($cc_users as $cc_user){
			$email = str_replace("@","#",$cc_user['email']);
			$this->mongo_db->where(array('app_id' => $this->request_app_col, 'doc_id' => $doc_id))->delete($email.'_web_docs');
			$this->mongo_db->where(array('app_id' => $this->request_app_col, 'doc_id' => $doc_id))->delete($email.'_docs');
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

	public function get_screening_reports_ehr_uid($uid) 
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

	public function update_screening_details($_id,$data_user)
	{
		//echo $_id;
		//exit();
		
		$this->mongo_db->where(array('_id' => new MongoID($_id)))->set($data_user)->update($this->screening_app_col);
		
	   /* //$this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $query = $this->mongo_db->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $data_user['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']))->set($data_user)->update($this->collections['healthcare20161014212024617']);
	   //$this->mongo_db->switchDatabase($this->common_db['dsn']); */
		 
		
	}

	//Uploading zipfiles
	//author Naresh
	public function insert_screening_details($data_user){
		
		//$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		
		$query = $this->mongo_db->insert($this->screening_app_col,$data_user);
		
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

   public function get_all_cc_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

	public function get_all_health_supervisors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

	public function get_all_active_request(){
		$date = date('Y-m-d') . " 00:00:00";
		
		$dates = $this->get_start_end_date ( $date, "Daily" );
		
		
		$start_date = $dates ['today_date'];
		$end_date = $dates ['end_date'];
		
		$his_0_lte = array (
				"history.0.time" => array (
						'$lte' => $start_date 
				) 
		);
		$his_0_gte = array (
				"history.0.time" => array (
						'$gte' => $end_date 
				) 
		);
		$his_1_lte = array (
				"history.1.time" => array (
						'$lte' => $start_date 
				) 
		);
		$his_1_gte = array (
				"history.1.time" => array (
						'$gte' => $end_date 
				) 
		);
		$his_last_lte = array (
				"history.last_stage.time" => array (
						'$lte' => $start_date 
				) 
		);
		$his_last_gte = array (
				"history.last_stage.time" => array (
						'$gte' => $end_date 
				) 
		);
		
		//$and_merged_array_condition = array();
		$and_merged_array_0 = array();
		$and_merged_array_1 = array();
		$and_merged_array_last = array();
		
		array_push ( $and_merged_array_0, $his_0_lte );
		array_push ( $and_merged_array_0, $his_0_gte );
		
		
		array_push ( $and_merged_array_1, $his_1_lte );
		array_push ( $and_merged_array_1, $his_1_gte );

		array_push ( $and_merged_array_last, $his_last_lte );
		array_push ( $and_merged_array_last, $his_last_gte );
		
		
		// ////log_message("debug","response=====1665==".print_r($merged_array,true));
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
									'$or' => array (
										array('$and' => $and_merged_array_0),
										array('$and' => $and_merged_array_1),
										array('$and' => $and_merged_array_last),
								)									
							) 
					)
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->request_app_col,
					'pipeline' => $pipeline 
			) );
			
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111--".print_r($response,true));
			
			//=============================================vjhbdhdsuhyvsuyhvuyv
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
			
			
		return count($query);
		
	}
	
	public function get_all_raised_request(){
		$date = date('Y-m-d') . " 00:00:00";
		$dates = $this->get_start_end_date ( $date, "Daily" );
		
		$date = date('Y-m-d') . " 00:00:00";
		
		$dates = $this->get_start_end_date ( $date, "Daily" );
		
		
		$start_date = $dates ['today_date'];
		$end_date = $dates ['end_date'];
		
		$his_0_lte = array (
				"history.0.time" => array (
						'$lte' => $start_date 
				) 
		);
		$his_0_gte = array (
				"history.0.time" => array (
						'$gte' => $end_date 
				) 
		);
		
		//$and_merged_array_condition = array();
		$and_merged_array_0 = array();
		
		array_push ( $and_merged_array_0, $his_0_lte );
		array_push ( $and_merged_array_0, $his_0_gte );
		
		
		// ////log_message("debug","response=====1665==".print_r($merged_array,true));
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
									'$or' => array (
										array('$and' => $and_merged_array_0)
								)									
							) 
					)
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->request_app_col,
					'pipeline' => $pipeline 
			) );
			
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111--".print_r($response,true));
			
			//=============================================vjhbdhdsuhyvsuyhvuyv
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
			
			
		return count($query);
	}
	
	public function get_initaite_requests_count($today_date)
	{
		$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		//if(count($document)>0){
			
			return $document;
		//}
		
		
	}
	
	public function get_doctors_response_count($today_date)
	{
		$document = $this->mongo_db->select(array('history'))->whereLike("history.1.time" , $today_date)->get($this->request_app_col);
		
		return $document;
	}

	// BMI PIE REPORT
	public function get_bmi_report_model($current_month,$district_name, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		
		$requests = [ ];
		
		
		if ( $school_name != "All" && $district_name!= "select") {
			
				
				$under_weight = $this->mongo_db->where('doc_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'UNDER WEIGHT';
				$request ['value'] = count($under_weight);
				array_push ( $requests, $request );
				
				$normal_weight = $this->mongo_db->where('doc_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"24.99")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'NORMAL WEIGHT';
				$request ['value'] = count($normal_weight);
				array_push ( $requests, $request );
				
				$over_weight = $this->mongo_db->where('doc_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"25.00",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"29.99")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'OVER WEIGHT';
				$request ['value'] = count($over_weight);
				array_push ( $requests, $request );
				
				$obese = $this->mongo_db->where('doc_data.school_details.School Name',$school_name)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"30.0")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'OBESE';
				$request ['value'] = count($obese);
				array_push ( $requests, $request );
				
		}else {
			}
		
		if ($school_name == "All") {
			if ($district_name != "select") {
				
				$under_weight = $this->mongo_db->where('doc_data.school_details.District',$district_name)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'UNDER WEIGHT';
				$request ['value'] = count($under_weight);
				array_push ( $requests, $request );
				
				$normal_weight = $this->mongo_db->where('doc_data.school_details.District',$district_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"24.99")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'NORMAL WEIGHT';
				$request ['value'] = count($normal_weight);
				array_push ( $requests, $request );
				
				$over_weight = $this->mongo_db->where('doc_data.school_details.District',$district_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"25.00",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"29.99")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'OVER WEIGHT';
				$request ['value'] = count($over_weight);
				array_push ( $requests, $request );
				
				$obese = $this->mongo_db->where('doc_data.school_details.District',$district_name)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"30.0")->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare201761916814158');
				$request ['label'] = 'OBESE';
				$request ['value'] = count($obese);
				array_push ( $requests, $request );
				
			} else {
			}
		} 
		return $requests;
		
	}
	
public function get_drilling_bmi_students_prepare_pie_array($query, $school_name, $type)
	{
		$search_result = [ ];
		$count = 0;
		
		 if ($query) {
			
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $query as $doc ) {
				
				
				switch ($type) {
					case "UNDER WEIGHT" :
					
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
					case "NORMAL WEIGHT" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
					
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
					
					case "OVER WEIGHT" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
						
					case "OBESE" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
						
					default :
					break;
				}
		 }
		
			return $UI_arr;
		}
	}

	public function get_drill_down_to_bmi_report($type, $current_month, $district_name, $school_name = "All") 
	{
		$current_month = substr($current_month,0,-3);

		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		ini_set ( 'memory_limit', '10G' );
		
		switch ($type) {
			case "UNDER WEIGHT" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50")->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
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
						
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
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
				) )->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"18.50",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"24.99")->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
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
						
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
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
				) )->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"25.00",'doc_data.widget_data.page1.Student Details.BMI_values.bmi',"29.99")->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
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
						
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
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
				) )->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',"30.0")->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
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
						
						if (strtolower ( $doc ['doc_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
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
	 

	 
	 public function get_drilling_bmi_students_docs($_id_array) 
	 {
		
		$bmi_request = array ();
		//set_time_limit(0);
		ini_set ( 'memory_limit', '10G' );
		
		if(isset($_id_array) && !empty($_id_array))
		{
			foreach ( $_id_array as $_id ) 
			{
				$query = $this->mongo_db->select ( array (
						'doc_data.widget_data.page1',
						'doc_data.widget_data.page2' 
				) )->where( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
				
				foreach ( $query as $bmi_docs )
				{
					$unique_id = $bmi_docs ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'];
					$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Student Details.Hospital Unique ID', $unique_id )->get ( $this->bmi_app_col);
					
					/* if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) { */
						$bmi ['student_info'] = $bmi_docs;
						$bmi ['BMI_values'] = $doc [0] ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['BMI_values'];
						//$bmi['bmi_id'] = $doc[0]['_id'];
						
						array_push ( $bmi_request, $bmi );
					/* } */
				}
			}
	
			return $bmi_request;
		}
	}
	

	public function get_student_bmi_graph_values($hospital_unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where( array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $hospital_unique_id))->get($this->bmi_app_col);
		
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	
	
	public function export_bmi_reports_monthly_to_excel($date, $district_name="select", $school_name="All"){
		
		$bmi_reports = [ ];
		
		if ( $school_name != "All") {
			
				
				$query = $this->mongo_db->where('doc_data.school_details.School Name',$school_name)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$date)->orderBy ( array (
					'doc_data.school_details.District' => 1 
			) )->get ( $this->bmi_app_col );
				
				array_push ( $bmi_reports, $query );
		}
		if ($school_name == "All") {
			if ($district_name != "select") {
				
				$query = $this->mongo_db->where('doc_data.school_details.District',$district_name)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$date)->orderBy ( array (
					'doc_data.school_details.District' => 1 
			) )->get ( $this->bmi_app_col );
				
				array_push ( $bmi_reports, $query );
			}
		}
		
		return $bmi_reports[0];
	}
	
	public function get_bmi_submitted_schools_list($current_month,$district_name,$dist_id)
	{
		
		// Variables
		$all_schools               = array();
		$all_schools_district      = array();
		$all_schools_name      	   = array();
		
		$submitted_schools 	       = array();
		$submitted_school_district = array();
		$submitted_school_name     = array();
		$not_submitted_schools	   = array();
		$schools_data              = array();
		$not_submitted_dist        = array();
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$schools_list = $this->mongo_db->whereLike('dt_name',$dist_id)->get ( $this->collections ['tmreis_schools'] );
		
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
	
		$query = $this->mongo_db->select(array('doc_data.school_details'))->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->where(array('doc_data.school_details.District' => $district_name))->get($this->bmi_app_col);
		
		
		foreach ( $query as $doc ) {
			
			    if(!in_array($doc ['doc_data'] ['school_details'] ['School Name'], $submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['school_details'] ['District']);
					array_push ( $submitted_school_name,$doc ['doc_data'] ['school_details'] ['School Name'] );
					
		        }
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		   
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		
		
		
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		return $schools_data;
		
	}

	public function get_chronic_request()
	{
		$requests = [ ];
		
		$query = $this->get_request_docs('Chronic',"Not Cured");
		$request ['label'] = 'Chronic';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Deficiency',"Not Cured");
		$request ['label'] = 'Deficiency';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Defects',"Not Cured");
		$request ['label'] = 'Defects';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	public function update_chronic_request_pie($status_type) {
		
		$requests = [ ];
		
		$query = $this->get_request_docs('Chronic',$status_type);
		$request ['label'] = 'Chronic';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Deficiency',$status_type);
		$request ['label'] = 'Deficiency';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Defects',$status_type);
		$request ['label'] = 'Defects';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		return $requests;
		
	}
	
	public function drill_down_request_to_symptoms($data,$status_type){
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$query = $this->get_request_docs($type,$status_type);
		
		$prob_arr = [ ];
		foreach ( $query as $doc ) {
			if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'] )) {
				$problems = $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'];
				foreach ( $problems as $problem ) {
					if (isset ( $prob_arr [$problem] )) {
						$prob_arr [$problem] ++;
					} else {
						$prob_arr [$problem] = 1;
					}
				}
			}
		}
		
		// //log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob_arr,true));
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
			// //log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob,true));
			// //log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		// //log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
		
		return $final_values;
	}
	
	public function drilldown_chronic_request_to_districts($data, $status_type) {
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
		$search_param_2 = $obj_data[1];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		
		//$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		
		$dist_list = [ ];
		
		/*foreach ( $query as $identifiers ) {
			
			$retrieval_list = array ();
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			//log_message ( 'debug', 'unique_id----' . print_r ( $unique_id, true ) );
			$doc = $this->mongo_db->where( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
		    
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (isset ( $dist_list [$district] )) {
					$dist_list [$district] ++;
				} else {
					$dist_list [$district] = 1;
				}
			}
		}*/
		
		$date = "2018-07-02";
		$dist_list = $this->mongo_db->select(array('pie_data.Chronic'),array('_id'))->whereLike('pie_data.date',$date)->get('tmreis_district_wise_healthrequest_counts');
		
		$dist_list = $dist_list[0]['pie_data']['Chronic'][$search_param_2];

		$final_values = [ ];
		foreach ( $dist_list as $dicsts => $count ) {
			$result ['label'] = $dicsts;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function drilldown_chronic_request_to_schools($data, $status_type) {
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		$search_param_2 = $params[2];
		
		//$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		
		$school_list = [ ];
		$matching_docs = [ ];
		/*$dist = strtolower ( $obj_data [1] );
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == $dist) {
					array_push ( $matching_docs, $doc [0] );
				}
			}
		}
		
		foreach ( $matching_docs as $docs ) {
			$school_name = $docs ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
			if (isset ( $school_list [$school_name] )) {
				$school_list [$school_name] ++;
			} else {
				$school_list [$school_name] = 1;
			}
		}*/

		$date = "2018-07-02";
		$dist = strtoupper( $obj_data [1] );
		$dist_list = $this->mongo_db->select(array('pie_data.Chronic'),array('_id'))->whereLike('pie_data.date',$date)->get('tmreis_school_wise_healthrequest_counts');
		//log_message('error',"dist_list===========21494".print_r($dist_list,true));
		$school_list = $dist_list[0]['pie_data']['Chronic'][$search_param_2][$dist];
		
		$final_values = [ ];
		foreach ( $school_list as $school => $count ) {
			$result ['label'] = $school;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function drilldown_chronic_request_to_students($data,$status_type) {
		
		
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		$search_param_2 = $params[2];
		
		//$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
	
		$school_name = $obj_data ['1'];
		$school_name = strtoupper($school_name);
			//log_message("error","school_name====17428".print_r($school_name,true));
		if(isset($school_name))
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$schools_list = $this->mongo_db->select(array('school_code'),array('_id'))->where('school_name',$school_name)->get ( $this->collections ['tmreis_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

			$school_code = $schools_list[0]['school_code'];

			if(isset($school_code) && !empty($school_code))
			{
				$this->mongo_db->switchDatabase($this->common_db ['common_db']);
				$get_hs_email = $this->mongo_db->select(array('email'))->where('school_code',$school_code)->get($this->collections ['tmreis_health_supervisors']);
				$this->mongo_db->switchDatabase($this->common_db ['dsn']);
				$email = $get_hs_email[0]['email'];
				$dist_code = strtoupper(str_ireplace(".", "_",substr($email,0,strpos($email,"@")-2)));
			}
			else{
				$dist_code = "";
			}

		}
		
		if(isset($dist_code) && !empty($dist_code))
		{
			$query = $this->get_request_docs_params_with_school($search_param_1,$search_param_2,$status_type,$dist_code);
		}
		else
		{
			$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		}
		
		$student_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if ($school == $school_name) {
					array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
				}
			}
		}
		
		return $matching_docs;
	}

	private function get_request_docs($request_type,$status_type){
		
		if($status_type == "Cured"){
			
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $request_type);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");

			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $cured );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query;
				
		}else{
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $request_type);
			$not_cured = array ("doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured"));
			$date = array('history.0.time' => array('$gte' => "2018-05-01"));
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $not_cured );
			array_push ( $and_merged_array, $date );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query;
		}
		
		
		
	}
	
	private function get_request_docs_params($search_param_1, $search_param_2, $status_type){
		
		if($status_type == "Cured"){
			
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");

			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $cured );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query;
				
		}else{
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$not_cured = array ("doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured"));
			$date = array('history.0.time' => array('$regex' => "2018-05-*"));
			
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $not_cured );
			array_push ( $and_merged_array, $date );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query;
		}
	}
	private function get_request_docs_params_with_school($search_param_1, $search_param_2, $status_type,$dist_code){

		$start_date = date("Y-m-d H:i:s");
		log_message('error',"start_datestart_date============17561".print_r($start_date,true));
		//$school_code = "HYD_".$school_code."_*";
		if($status_type == "Cured"){
			$unique_id = array('doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $dist_code));
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");
	
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $unique_id );
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $cured );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}
				
				return $query;
				
		}else{
			$unique_id = array('doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $dist_code));
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$not_cured = array ("doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured"));
			$date = array('history.time' => array('$gte' => "2018-05-01"));
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $unique_id );
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $not_cured );
			array_push ( $and_merged_array, $date );
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
						'aggregate' => $this->request_app_col,
						'pipeline' => $pipeline 
				) );
				$query = array();
				if($response['ok']){
					$query = $response["result"];
				}

				return $query;
		}
	}
	function get_sanitation_report_fields_count($district_name,$school_name)
	{
		
		if($district_name == 'All' && $school_name == 'All')
		{
			$today_date = date("Y-m-d");
			$campusOnce = $this->mongo_db->
								where(array(
										
										'doc_properties.status' => 2,
									    "doc_data.widget_data.page4.Declaration Information.Date:" => $today_date
									))->get("healthcare2017121175645993_version_2");
			
			return $campusOnce;
		}
		elseif($school_name == 'All')
		{
			$today_date = date("Y-m-d");
			$campusOnceCount = $this->mongo_db->
								where(array(
										'doc_data.widget_data.page4.School Information.District' => strtoupper($district_name),
										'doc_properties.status' => 2, 
										"doc_data.widget_data.page4.Declaration Information.Date:" => $today_date 					    
									))->get("healthcare2017121175645993_version_2");
								
			return $campusOnceCount;
			
		}
	}
	public function get_initaite_requests_count_today_date($today_date)
	{
		$exists_history = array('history.0.time' => array('$regex' => $today_date ));
		
		$document = $this->mongo_db->select(array('history'))->where($exists_history)->count("healthcare201610114435690_static_html");
		//log_message('error','document--------------------19480'.print_r($document,true));
		if(count($document)>0){
			return $document;
		}
	}

	public function get_requests_count_today_date($today_date)
	{
		$exists_history = array('history.0.time' => array('$regex' => $today_date ));
		
		$document['normal'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->where($exists_history)->count("healthcare201610114435690_static_html");

		$document['defects'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Defects"))->where($exists_history)->count("healthcare201610114435690_static_html");

		$document['deficiency'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Deficiency"))->where($exists_history)->count("healthcare201610114435690_static_html");

		$document['emergency_count'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->where($exists_history)->count("healthcare201610114435690_static_html");

		$document['chronic_count'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->where($exists_history)->count("healthcare201610114435690_static_html");

		$document['normal_count'] = $document['normal'] + $document['defects'] + $document['deficiency'];

		if(count($document)>0){
			return $document;
		} 
	}
	
	public function get_doctors_response_count_today_date($today_date)
	{
		$exists_doctor = array('history.submitted_by' => array('$regex' => 'tmreis.dr'));
		$document = $this->mongo_db->select(array('history'))->where($exists_doctor)->whereLike("history.time" , $today_date)->get("healthcare201610114435690_static_html");
		
		return $document;
	}
	//fetch request form ehr
	public function get_reports_ehr_uid_new_html_static_hs($uid) {
		
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->limit(700)->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=>-1))->where ( "doc_data.widget_data.page1.Student Info.Unique ID", strtoupper($uid) )->get ('healthcare201610114435690_static_html');
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['ttwreis_req_notes'] );
										
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->where ( "uid", strtoupper($uid) )->get ( $this->notes_col );
			
			
			
			if(isset($query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'])){
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['tmreis_schools'] );
				
				if(count($school_details) > 0){
					$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['tmreis_health_supervisors'] );
				}else{
					$query_hs[0] = false;
				}
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			}else{
				$query_hs[0] = false;
			}
			
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
			$result ['hs'] = false;
			return $result;
		}
	}
	public function get_show_ehr_details($request_type,$date,$school_name)
	{
		
		$data['all_request'] = $this->mongo_db->whereLike('history.0.time',$date)->get('healthcare201610114435690_static_html');
		if($school_name == "All")
		{
			if($request_type == "Normal" )
			{
			$normal_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type))->get('healthcare201610114435690_static_html');
				return $normal_request_all;
			
			}
			else if($request_type == "Emergency")
			{ 
			$emergency_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type))->get('healthcare201610114435690_static_html');

			return $emergency_request_all;
				
			}
			else if($request_type == "Chronic")
			{
				$chronic_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type))->get('healthcare201610114435690_static_html');
				return $chronic_request_all;
				
			}
		}else{
			
			if($request_type == "Normal")
			{
			$normal_request = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare201610114435690_static_html');
				return $normal_request;
			
			}
			
			else if($request_type == "Emergency")
			{ 
			$emergency_request = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare201610114435690_static_html');

			return $emergency_request;
				
			}
			else if($request_type == "Chronic")
			{
				$chronic_request = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare201610114435690_static_html');
				return $chronic_request;
				
			}
		}
		
		
	}
	public function drill_down_screening_to_students_load_ehr_new_dashboard($_id) {
		
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$_id)->
		get ( $this->screening_app_col );
		//->where ( "_id", new MongoID ( $_id ) )
		
		if ($query) {
			$query_request = $this->mongo_db->select(array("doc_data.widget_data","doc_data.notes_data","doc_data.external_attachments","doc_properties","history"))->orderBy(array("history.0.time"=> -1))->where( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ('healthcare201610114435690_static_html');
			
			
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->whereLike ("req_doc_id", new MongoId($req['_id']))->get ( $this->collections['ttwreis_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->notes_col );

			//log_message("debug","EHR======notes".print_r($query_notes,true));
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['tmreis_schools'] );

			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['tmreis_health_supervisors'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
			//log_message("debug","result ==========notes".print_r($result ['notes'],true));
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
			$result ['hs'] = false;
			return $result;
		}
	}
	// hb PIE REPORT
	public function get_hb_report_model($current_month,$district_name, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		
		$requests = [ ];
		
		
		if ( $school_name != "All" && $district_name != "select") {
			
				
				$sevier = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8.0 )->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'SEVIER';
				$request ['value'] = count($sevier);
				array_push ( $requests, $request );
				
				$normal_hb = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',12.0,18.0 )->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'NORMAL';
				$request ['value'] = count($normal_hb);
				array_push ( $requests, $request );
				
				$moderate = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8.1,10.0 )->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'MODERATE';
				$request ['value'] = count($moderate);
				array_push ( $requests, $request );
				
				$mild = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10.1,12.0)->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'MILD';
				$request ['value'] = count($mild);
				array_push ( $requests, $request );
				
		}else {
			}
		
		if ($school_name == "All") {
			if ($district_name != "select") {
				
				$sevier = $this->mongo_db->where('doc_data.widget_data.school_details.District',$district_name)->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8.0)->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'SEVIER';
				$request ['value'] = count($sevier);
				array_push ( $requests, $request );
				
				$normal_hb = $this->mongo_db->where('doc_data.widget_data.school_details.District',$district_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',12.0,18.0)->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'NORMAL';
				$request ['value'] = count($normal_hb);
				array_push ( $requests, $request );
				
				$moderate = $this->mongo_db->where('doc_data.widget_data.school_details.District',$district_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8.1,10.0)->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'MODERATE';
				$request ['value'] = count($moderate);
				array_push ( $requests, $request );
				
				$mild = $this->mongo_db->where('doc_data.widget_data.school_details.District',$district_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10.1,12.0)->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get($this->hb_app_col);
				$request ['label'] = 'MILD';
				$request ['value'] = count($mild);
				array_push ( $requests, $request );
				
			} else {
			}
		} 
		return $requests;
		
	}
	public function get_drilling_screenings_students_docs_count($_id_array) {
		$docs = [ ];
		ini_set ( 'memory_limit', '10G' );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select(array("doc_data.widget_data"))->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			
			if (isset ( $query [0] ))
				array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	public function get_drill_down_to_hb_report($type, $current_month, $district_name, $school_name = "All") 
	{
		$current_month = substr($current_month,0,-3);

		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		ini_set ( 'memory_limit', '10G' );
		switch ($type) {
			case "SEVIER" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8.0)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get ( $this->hb_app_col );			
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
						foreach ( $query as $doc ) {
							if(isset($doc ['doc_data'] ['widget_data']['school_details']))
							{
								if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
								}
							}else{
								if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
								}
							}	
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}							
					}
					$query = $doc_query;
					
				}
				
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
			case "NORMAL" :
			ini_set ( 'memory_limit', '10G' );
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',12.0,18.0)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
						foreach ( $query as $doc ) {

						if(isset($doc ['doc_data'] ['widget_data']['school_details']))
							{
								if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
								}
							}else{
								if (strtolower ( $doc ['doc_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
								}
							}							
							
						}
						$query = $doc_query;
						
					} else {
						
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					
					$query = $doc_query;
				}
				
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			case "MODERATE" :
			ini_set ( 'memory_limit', '10G' );
			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8.1,10.0)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
			case "MILD" :
			ini_set ( 'memory_limit', '10G' );
			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10.1,12.0)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "select") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			default :
				;
				break;
		}
	}
	public function get_hb_submitted_schools_list($current_month,$district_name,$dist_id)
	{
		
		// Variables
		$all_schools               = array();
		$all_schools_district      = array();
		$all_schools_name      	   = array();
		
		$submitted_schools 	       = array();
		$submitted_school_district = array();
		$submitted_school_name     = array();
		$not_submitted_schools	   = array();
		$schools_data              = array();
		$not_submitted_dist        = array();
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$schools_list = $this->mongo_db->whereLike('dt_name',$dist_id)->get ( $this->collections ['tmreis_schools'] );
		
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
	
		$query = $this->mongo_db->select(array('doc_data.widget_data.school_details'))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->where(array('doc_data.widget_data.school_details.District' => $district_name))->get($this->hb_app_col);
		
		
		foreach ( $query as $doc ) {
			
			    if(!in_array($doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'], $submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['school_details'] ['District']);
					array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['school_details'] ['School Name'] );
					
		        }
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		   
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		
		
		
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		return $schools_data;
		
	}
	public function get_drilling_hb_students_docs($_id_array) 
	 {
		
		$hb_request = array ();
		//set_time_limit(0);
		ini_set ( 'memory_limit', '10G' );
		
		if(isset($_id_array) && !empty($_id_array))
		{
			foreach ( $_id_array as $_id ) 
			{
				$query = $this->mongo_db->select ( array (
						'doc_data.widget_data.page1.Student Details','doc_data.widget_data.school_details'
				) )->where( "doc_data.widget_data.page1.Student Details.Hospital Unique ID", $_id )->get ( $this->hb_app_col );
				
				foreach ( $query as $hb_docs )
					{
						$unique_id = $hb_docs ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'];
						$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Student Details.Hospital Unique ID', $unique_id )->get ( $this->hb_app_col);
						
						if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
								$hb ['student_info'] = $hb_docs;
								$hb ['HB_values'] = $doc [0] ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['HB_values'];
								//$hb['hb_id'] = $doc[0]['_id'];
								
								array_push ( $hb_request, $hb );
						}
					}
		}
	
		return $hb_request;
	}
	}

	public function get_drilling_hb_students_prepare_pie_array($query, $school_name, $type)
	{
		$search_result = [ ];
		$count = 0;
		
		 if ($query) {
			
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $query as $doc ) {
				
				
				switch ($type) {
					case "SEVIER" :
					
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
					case "NORMAL" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
					
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
					
					case "MODERATE" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
						
					case "MILD" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
						
					default :
					break;
				}
		 }
		
			return $UI_arr;
		}
	}
	public function get_student_hb_graph_values($hospital_unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data'))->where( array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $hospital_unique_id))->get($this->hb_app_col);
		
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	/************************** request pie chart ##author Suman reddy **********************************/
	public function get_schools_by_dist($dist_id) {

		if ($dist_id == "All") {
			ini_set ( 'memory_limit', '10G' );
			
			$count = $this->mongo_db->count ( $this->screening_app_col );
			if ($count < 10000) {
				$per_page = $count;
				$loop = 2; // $count / $per_page;
			} else {
				$per_page = 10000;
				$loop = $count / $per_page;
			}
			
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
						'aggregate' => $this->screening_app_col,
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
			) )->where (array('school_name' => array('$regex' => $dist_id )))->get ( $this->collections ['tmreis_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}
	}
	public function get_monthly_request_by_month($date_month,$school_name,$dt_name)
	{
		$date_month = substr($date_month,0,-3);
		
		$normal_request_counts = array();
		$normal_diease = array();
		$emergency_diease = array();
		$chronic_diease = array();
		
		$emergency_request_counts = array();
		$chronic_request_counts = array();
		
		if($school_name == "All" && $dt_name == "All")
		{
			$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereLike("history.0.time" , $date_month)->get('healthcare201610114435690_static_html');			

			foreach ($query as $request_type)
			{
				$type = $request_type['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
				
						
				switch ($type) {
					case 'Normal':					
						array_push($normal_request_counts, $type);
						$types['normal'] = count($normal_request_counts);
						$normal = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Normal'];
						array_push($normal_diease, $normal);
						$types['disease_normal'] = $normal_diease;
						break;
					case 'Emergency':					
						array_push($emergency_request_counts, $type);
						$types['emergency'] = count($emergency_request_counts);
						$emergency = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];
						array_push($emergency_diease, $emergency);
						$types['disease_emergency'] = $emergency_diease;

						break;	
					case 'Chronic':					
						array_push($chronic_request_counts, $type);
						$types['chronic'] = count($chronic_request_counts);
						$chronic = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];
						array_push($chronic_diease, $chronic);
						$types['disease_chronic'] = $chronic_diease;
						break;
					
					default:
						;
						break;
				}
				
			}
			if($types)
			{
				return $types;
			}else{
				return FALSE;
			}	
		}

		if($dt_name != "All" && $school_name == "All")
		{
			$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereLike("history.0.time" , $date_month)->where(array('doc_data.widget_data.page1.Student Info.District.field_ref' => $dt_name))->get('healthcare201610114435690_static_html');			
			if(!empty($query) && isset($query))
			{
				foreach ($query as $request_type)
				{
					$type = $request_type['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
					
							
					switch ($type) {
						case 'Normal':					
							array_push($normal_request_counts, $type);
							$types['normal'] = count($normal_request_counts);
							$normal = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Normal'];
							array_push($normal_diease, $normal);
							$types['disease_normal'] = $normal_diease;
							

							break;
						case 'Emergency':					
							array_push($emergency_request_counts, $type);
							$types['emergency'] = count($emergency_request_counts);
							$emergency = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];
							array_push($emergency_diease, $emergency);
							$types['disease_emergency'] = $emergency_diease;

							break;	
						case 'Chronic':					
							array_push($chronic_request_counts, $type);
							$types['chronic'] = count($chronic_request_counts);
							$chronic = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];
							array_push($chronic_diease, $chronic);
							$types['disease_chronic'] = $chronic_diease;
							break;
						
						default:
							;
							break;
					}
					
				}
			}else
			{
				$types['no_data'] = "No Data is Availability";
			}
			if($types)
			{
				return $types;
			}else{
				return FALSE;
			}
		}
		if($dt_name != "All" && $school_name != "All")
		{
			$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereLike("history.0.time" , $date_month)->where(array('doc_data.widget_data.page1.Student Info.District.field_ref' => $dt_name,'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare201610114435690_static_html');	
			
		  if(!empty($query) && isset($query))
		   {
			foreach ($query as $request_type)
			{
				$type = $request_type['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
				
						
				switch ($type) {
					case 'Normal':					
						array_push($normal_request_counts, $type);
						$types['normal'] = count($normal_request_counts);
						$normal = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Normal'];
						array_push($normal_diease, $normal);
						$types['disease_normal'] = $normal_diease;
						break;
					case 'Emergency':					
						array_push($emergency_request_counts, $type);
						$types['emergency'] = count($emergency_request_counts);
						$emergency = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];
						array_push($emergency_diease, $emergency);
						$types['disease_emergency'] = $emergency_diease;

						break;	
					case 'Chronic':					
						array_push($chronic_request_counts, $type);
						$types['chronic'] = count($chronic_request_counts);
						$chronic = $request_type['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];
						array_push($chronic_diease, $chronic);
						$types['disease_chronic'] = $chronic_diease;
						break;
					
					default:
						;
						break;
				}
				
			}
		  }else
			{
				$types['no_data'] = "No Data is Availability";
			}
			if($types)
			{
				return $types;
			}else{
				return FALSE;
			}
		}

		
		
		
	}
	public function drill_down_chronic_student_list($chronic_symtom)
	{
		$chronic_sym = explode(".", $chronic_symtom);
		$month = substr($chronic_sym[2], 0,-3);
		$serch = array("doc_data.widget_data.page1.Problem Info.Chronic.".$chronic_sym[0] => $chronic_sym[1],'history.0.time' => array('$regex' => $month));			
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($serch)->get('healthcare201610114435690_static_html');
		
		return $query;			
	}
	public function drill_down_emergency_student_list($emergency_symtom)
	{	
		
		$emergency_sym = explode(".", $emergency_symtom);
		$month = substr($emergency_sym[2], 0,-3);
		if($emergency_sym[3] == "All" && $emergency_sym[4] == "All")
		{			
			$serch = array("doc_data.widget_data.page1.Problem Info.Emergency.".$emergency_sym[0] => $emergency_sym[1],'history.0.time' => array('$regex' => $month));			
			$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($serch)->get('healthcare201610114435690_static_html');
		}else if($emergency_sym[3] != "All" && $emergency_sym[4] == "All") 
		{			
			$serch = array("doc_data.widget_data.page1.Problem Info.Emergency.".$emergency_sym[0] => $emergency_sym[1],'history.0.time' => array('$regex' => $month),'doc_data.widget_data.page1.Student Info.District.field_ref' => $emergency_sym[3]);		
			$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($serch)->get('healthcare201610114435690_static_html');
		}else if($emergency_sym[3] != "All" && $emergency_sym[4] != "All")
		{
			
			$serch = array("doc_data.widget_data.page1.Problem Info.Emergency.".$emergency_sym[0] => $emergency_sym[1],'history.0.time' => array('$regex' => $month),'doc_data.widget_data.page1.Student Info.District.field_ref' => $emergency_sym[3],'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $emergency_sym[4]);		
			$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($serch)->get('healthcare201610114435690_static_html');
		}
		
		
		return $query;			
	}
	public function drill_down_normal_student_list($normal_symtom)
	{
		$normal_sym = explode(".", $normal_symtom);
		$month = substr($normal_sym[2], 0,-3);
		$serch = array("doc_data.widget_data.page1.Problem Info.Normal.".$normal_sym[0] => $normal_sym[1],'history.0.time' => array('$regex' => $month));			
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1'))->where($serch)->get('healthcare201610114435690_static_html');
		
		return $query;			
	}

	public function get_all_students_count()
	{
	$females_count = $this->mongo_db->command(array('distinct' => $this->screening_app_col ,'key' => "doc_data.widget_data.page1.Personal Information.Hospital Unique ID",'query'=>array('doc_data.widget_data.page2.Personal Information.School Name' => array('$regex' => '(G).,', '$options' => 'i'))));
    	$males_count = $this->mongo_db->command(array('distinct' => $this->screening_app_col ,'key' => "doc_data.widget_data.page1.Personal Information.Hospital Unique ID",'query'=>array('doc_data.widget_data.page2.Personal Information.School Name' => array('$regex' => '(B).,', '$options' => 'i'))));
    	$gender['female'] = count($females_count['values']);
    	$gender['male'] = count($males_count['values']);
    	$gender['total_students'] = count($males_count['values']) + count($females_count['values']);
    	
		//$query = $this->mongo_db->count('healthcare2016226112942701');

		return $gender;
	}

	public function get_total_requests()
	{
		$data['total_req_count'] = $this->mongo_db->select(array())->whereNe(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Defects','doc_data.widget_data.page2.Review Info.Request Type' => 'Deficiency','doc_data.widget_data.page2.Review Info.Request Type' => "",'doc_data.widget_data.page2.Review Info.Request Type' => false))->count($this->request_app_col);
		
		$data['normal_req_count'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->count($this->request_app_col);

		$data['normal_req_count_not_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Normal'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->count($this->request_app_col);

		$data['normal_req_count_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Normal'))->where(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->count($this->request_app_col);

		$data['emergency_req_count'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency'))->count($this->request_app_col);

		$data['emergency_req_count_not_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => 'Cured'))->count($this->request_app_col);

		$data['emergency_req_count_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency'))->where(array('doc_data.widget_data.page2.Review Info.Status' => 'Cured'))->count($this->request_app_col);

		$data['chronic_req_count'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->count($this->request_app_col);

		$data['chronic_req_count_not_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => 'Cured'))->count($this->request_app_col);

		$data['chronic_req_count_cured'] = $this->mongo_db->select(array())->where(array('doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic'))->where(array('doc_data.widget_data.page2.Review Info.Status' => 'Cured'))->count($this->request_app_col);

		return $data;
	}
	public function get_sevier_count($type)
	{
		$final_values_6 = array();
		$final_values_8 = array();
		$final_values_10_12 = array();
		$final_values_8_10 = array();
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.HB_values'))->where(array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$exists' => true),'doc_data.widget_data.page1.Student Details.Gender' => ($type != "All") ? $type : array('$exists' => true)))->get($this->hb_app_col);
		/*$query = array('doc_data.widget_data.page1.Student Details.HB_values' => array('$slice' => -1));

		$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $query
					)
					];
		$response = $this->mongo_db->command(array( 
				'aggregate' => $this->hb_app_col,
				'pipeline'         => $pipeline
				 ));
		echo print_r($response,true);exit();*/
		
		/*unset($final_values_6);
				$final_values_6 = array();
				unset($final_values_8);
				$final_values_8 = array();
				unset($final_values_10_12);
				$final_values_10_12 = array();*/
		foreach ($query as $hb_values)
		{
			$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
			if($end_hb['hb'] <= 6 )
			{				
				array_push($final_values_6, $hb_values);
			}else if ($end_hb['hb'] > 6 && $end_hb['hb'] <= 8)
			{				
				array_push($final_values_8, $hb_values);
			}else if($end_hb['hb'] > 10 && $end_hb['hb'] <= 12)
			{				
				array_push($final_values_10_12, $hb_values);				
			}else 
			if($end_hb['hb'] > 8 && $end_hb['hb'] <= 10) 
			{				
				array_push($final_values_8_10, $hb_values);
			}
					
		}

			$final_data['below_6_hb_values'] = count($final_values_6);
			$final_data['sevier'] = count($final_values_8);
			$final_data['mild'] = count($final_values_10_12);
			$final_data['moderate'] = count($final_values_8_10);
//		echo print_r($final_data['below_6_hb_values']);
//exit(); 
		/*$final_data['below_6_hb_values'] = count($final_values);

		$query = $this->mongo_db->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8)->get($this->hb_app_col);
		foreach ($query as $hb_values)
		{
			$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
			if($end_hb['hb'] <= 8 )
			{
				array_push($final_values, $hb_values);
			}
					
		}
		$final_data['sevier'] = count($final_values);

		$query = $this->mongo_db->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10.1,12.0)->get($this->hb_app_col);

		foreach ($query as $hb_values)
		{
			$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
			if($end_hb['hb'] >= 10 || $end_hb['hb'] <= 12 )
			{
				array_push($final_values, $hb_values);
			}
					
		}
		$final_data['mild'] = count($final_values);

		$query = $this->mongo_db->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8.1,10)->get($this->hb_app_col);
		foreach ($query as $hb_values)
		{
			$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
			if($end_hb['hb'] >= 8 || $end_hb['hb'] <= 10 )
			{
				array_push($final_values, $hb_values);
			}
					
		}
		$final_data['moderate'] = count($final_values);*/

		if(!empty($final_data))
		{
			return $final_data;
		}else{
			return FALSE;
		}
	}
	public function get_bmi_count($type)
	{
		$query['below_14_bmi_values'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',14)->count($this->bmi_app_col);
		$query['under_weight'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.5)->count($this->bmi_app_col);
		$query['over_weight'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->count($this->bmi_app_col);
		$query['obese'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Gender' => ($type != "All") ? $type : array('$exists' => true)))->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30)->count($this->bmi_app_col);

		if(!empty($query))
		{
			return $query;
		}else{
			return FALSE;
		}
	}
	public function get_screened_count()
	{
		$exists_screened = array ("doc_data.widget_data.page3.Physical Exam" => array ('$exists' => true),
			"doc_data.widget_data.page4.Doctor Check Up" => array ('$exists' => true));
		$total_count = $this->mongo_db->where($exists_screened)->count($this->screening_app_col);

		$eye_screened = array ("doc_data.widget_data.page3.Physical Exam" => array ('$exists' => true),
			"doc_data.widget_data.page7.Colour Blindness.Right" => array ('$ne' => ""),"doc_data.widget_data.page7.Colour Blindness.Eye Lids" => array ('$exists' => true),'history.last_stage.time' => array('$gte' => "2018-10-01"));
		$eye_screened_count = $this->mongo_db->where($eye_screened)->count($this->screening_app_col);

		$dental_screened = array ("doc_data.widget_data.page3.Physical Exam" => array ('$exists' => true),
			"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array ('$ne' => ""),"doc_data.widget_data.page9.Dental Check-up.Root Canal Treatment" => array ('$exists' => true),'history.last_stage.time' => array('$gte' => "2018-10-01"));
		$dental_screened_count = $this->mongo_db->where($dental_screened)->count($this->screening_app_col);

		$qry['total_count'] = $total_count;
		$qry['eye_screened_count'] = $eye_screened_count;
		$qry['dental_screened_count'] = $dental_screened_count;

		if($qry){
			return $qry;
		}else{
			return FALSE;
		}

			
	}
	public function get_attendance_count()
	{
		$date = $this->today_date;

		$query = $this->mongo_db->whereLike('history.last_stage.time',$date)->count($this->absent_app_col);

		if($query){
			return $query;
		}else{
			return FALSE;
		}
	}
	public function get_sanitation_count()
	{
		$date = $this->today_date;

		$query = $this->mongo_db->whereLike('history.last_stage.time',$date)->count($this->sanitation_app_col);

		if($query){
			return $query;
		}else{
			return FALSE;
		}
	}

	public function get_chronic_asthma_count($type)
	{
		$date = $this->today_date;

		$query['chronic'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['anemia'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Blood' => "Anaemia",'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['tb'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Others' => "TB", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['asthma'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Respiratory_system' => "Asthma", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['scabies'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Skin' => "Scabies", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['epilepsy'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Central_nervous_system' => "Epilepy", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['hypothyroidism'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Endo' => "Hypothyroidism", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['hiv'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Others' => "HIV", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);

		$query['diabetese'] = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Endo' => "Diabetes Milletus Type 1", 'doc_data.widget_data.page1.Student Info.Gender' => ($type != "All") ? $type : array('$exists' => true)))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->count($this->request_app_col);
		//echo print_r($query,TRUE);exit();
		if($query){
			return $query;
		}else{
			return FALSE;
		}
	}
	public function get_total_emergency_req_count()
	{
		$query['total_emergency_req_count'] = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->count($this->request_app_col);

		$query['out_patient_total_count'] = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Out Patients"))->count("field_officer_report");

		$query['admitted_total_count'] = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Emergency or Admitted"))->count("field_officer_report");

		$query['review_cases_total_count'] = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Review Cases"))->count("field_officer_report");

		$query['doctor_visits_total_count'] = $this->mongo_db->count("doctor_visiting_reports");

		if($query){
			return $query;
		}else{
			return FALSE;
		}
	}
	public function get_student_type_for_tails($type)
	{
		$data['hb'] = $this->get_sevier_count($type);
		$data['bmi'] = $this->get_bmi_count($type);
		$data['chronic'] = $this->get_chronic_asthma_count($type);
		return $data;
	}
	public function get_bmi_students_docs($bmi_type)
	{
		/*var_dump($bmi_type);
		exit;*/
		switch ($bmi_type) {
			case 'Below 14 BMI Count ':
			//$search_query = array('doc_data.widget_data.page1.Student Details.BMI_values' => array('$elemMatch' => array('bmi' => 14)));
				//$query = $this->mongo_db->limit(1000)->whereLt($search_query)->get($this->bmi_app_col);
				$query = $this->mongo_db->limit(1000)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',14)->get($this->bmi_app_col);

				return $query;
				break;

				case 'Under Weight Count ':
				$query = $this->mongo_db->limit(200)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.5)->get($this->bmi_app_col);
				return $query;
				break;

				case 'Over Weight Count ':
				$query = $this->mongo_db->limit(100)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->get($this->bmi_app_col);
				return $query;
				break;

				case 'Obese Count ':
				$query = $this->mongo_db->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30)->get($this->bmi_app_col);
				return $query;
				break;
			
			default:
				# code...
				break;
		}
	}
	public function get_hb_students_docs($hb_type)
	{
		//ini_set ( 'memory_limit',"2G");
		
		$final_values = array();
		switch ($hb_type) {
			case 'Below 6 HB Count':
				$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',6)->limit(2000)->get($this->hb_app_col);
				
				foreach ($query as $hb_values) {
					$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
					if($end_hb['hb'] <= 6 )
					{
						array_push($final_values, $hb_values);
					}
					
				}
				return $final_values;
				break;

			case 'Severe HB Count':
				$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',6,8)->limit(2000)->get($this->hb_app_col);
				foreach ($query as $hb_values) {
					$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
					if($end_hb['hb'] > 6 && $end_hb['hb'] <= 8 )
					{
						array_push($final_values, $hb_values);
					}
					
				}
				return $final_values;
				break;

			case 'Mild HB Count':
				$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10.1,12.0)->limit(2000)->get($this->hb_app_col);
				foreach ($query as $hb_values) {
					$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
					if($end_hb['hb'] > 10 ||  $end_hb['hb'] <= 12.0)
					{
						array_push($final_values, $hb_values);
					}
					
				}
				return $final_values;
				break;

			case 'Moderate HB Count':
				$query = $this->mongo_db->select(array('doc_data.widget_data'))->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8.1,10)->limit(2000)->get($this->hb_app_col);
				foreach ($query as $hb_values) {
					$end_hb = end($hb_values['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
					if($end_hb['hb'] > 8 || $end_hb['hb'] <= 10)
					{
						array_push($final_values, $hb_values);
					}
					
				}
				return $final_values;
				break;
			
			default:
				# code...
				break;
		}
	}
	public function get_chronic_students_docs($chronic_type)
	{
		/*var_dump($chronic_type);
		exit;*/
		switch ($chronic_type) {
			case 'Anemia':
		
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Blood' => "Anaemia"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'TB':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Others' => "TB"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'Asthma':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Respiratory_system' => "Asthma"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'Scabies':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Skin' => "Scabies"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'Epilepsy':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Central_nervous_system' => "Epilepy"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;
				
				case 'Hypothyroidism':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Endo' => "Hypothyroidism"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'HIV':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Others' => "HIV"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

				case 'Diabetes':
				$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Problem Info.Chronic.Endo' => "Diabetes Milletus Type 1"))->whereLike('doc_data.widget_data.page2.Review Info.Request Type',"Chronic")->get($this->request_app_col);
				return $query;
				break;

			default:
				# code...
				break;
		}
	}

	public function get_emergency_req_students_docs($emergencyReq)
	{
		$query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
		return $query;
	}

	public function get_field_officer_req_students_docs($fieldOfficerReq)
	{
		switch ($fieldOfficerReq) {
				case 'Out Patient':
				$query = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Out Patients"))->get("field_officer_report");
				return $query;
				break;

				case 'Admitted Cases':
				$query = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Emergency or Admitted"))->get("field_officer_report");
				return $query;
				break;

				case 'Review Cases':
				$query = $this->mongo_db->where(array('doc_data.widget_data.type_of_request' => "Review Cases"))->get("field_officer_report");
				return $query;
				break;

				case 'Doctor Visits':
				$query = $this->mongo_db->get("doctor_visiting_reports");
				return $query;
				break;

			default:
				break;
		}
	}
	 public function show_field_officer_submit_student($doc_id)
    {
            $getSubmittedDocs = $this->mongo_db->where(array('doc_properties.doc_id'=>$doc_id))->get('field_officer_report');
            return $getSubmittedDocs;
    } 
}
