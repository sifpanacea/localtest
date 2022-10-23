<?php
ini_set ( 'memory_limit', '2G' );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Rhso_users_common_model extends CI_Model {
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

		$screening_district_code = $this->session->userdata('customer');
		//$cro_dist = strtolower($screening_district_code['district_code']);
		//adb.51902.hs#gmail.com_pie_analytics

		
		$this->screening_app_col = "healthcare2016226112942701";
		$this->screening_app_col_screening = "healthcare2016226112942701_screening_final";
		$this->absent_app_col = "healthcare201651317373988";
		$this->request_app_col = "healthcare2016531124515424";
		$this->sanitation_infra_app_col  = "healthcare20161114161842748";
		$this->sanitation_app_col = "healthcare2016111212310531";
		$this->rhso_users_xl_report = "rhso_users_xl_report";
		$this->notes_col = "panacea_ehr_notes";
		$this->today_date = date ( 'Y-m-d' );
		$this->hb_app_col = "himglobin_report_col";


	}

	public function get_cro_district_wise_screenining_data($cro_district_code)
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$district_wise_screening = $this->mongo_db->select(array('email'))->whereLike('email',$cro_district_code)->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

		foreach ( $district_wise_screening as $school ) {
			$district_wise_screening['email']= $school['email'];
		}
		log_message('debug','get_cro_district_wise_screenining_data=====68=='.print_r($district_wise_screening,true));
		return $district_wise_screening;
	}
	public function statescount() {
		$count = $this->mongo_db->count ( 'panacea_states' );
		return $count;
	}
	public function get_states($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_states' );
		return $query;
	}
	public function get_all_states() {
		$query = $this->mongo_db->get ( 'panacea_states' );
		return $query;
	}
	
	// =================================================
	public function distcount() {
		$count = $this->mongo_db->count ( 'panacea_district' );
		return $count;
	}
	public function get_district($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_district' );
		foreach ( $query as $distlist => $dist ) {
			$st_name = $this->mongo_db->where ( '_id', new MongoId ( $dist ['st_name'] ) )->get ( 'panacea_states' );
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
			) )->get ( 'panacea_district' );
		} else {
			$query = $this->mongo_db->where ( 'dt_name', $dt_name )->orderBy ( array (
				'dt_name' => 1 
			) )->get ( 'panacea_district' );
		}

		
		return $query;
	}
	public function health_supervisorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_health_supervisors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_health_supervisors'] );
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
			"school_code" => $post ['school_code'],
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
		$query = $this->mongo_db->insert ( $this->collections ['panacea_health_supervisors'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	
	// ///////////////////////////////////////////////////////////
	public function cc_users_count() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['ttwreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_cc_users($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['ttwreis_cc'] );
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
		$query = $this->mongo_db->insert ( $this->collections ['ttwreis_cc'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function delete_cc_user($cc_id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
			"_id" => new MongoId ( $cc_id ) 
		) )->delete ( $this->collections ['ttwreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	// ////////////////////////////////////////////////////////////
	public function doctorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['ttwreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_doctors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['ttwreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

	public function get_all_doctors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['ttwreis_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	// ///////////////////////////////////////////////////////////////////
	public function schoolscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_schools($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
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
			"school_code" => $post ['school_code'],
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
		$query = $this->mongo_db->insert ( $this->collections ['panacea_schools'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function get_all_schools($cro_dist) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereLike ( 'dt_name',$cro_dist )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
	}
	public function get_schools_details_for_district_code($district_name) {
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$school_data = $this->mongo_db->where('dt_name',$district_name)->get('panacea_schools');
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		
		return $school_data;
	}
	public function classescount() {
		$count = $this->mongo_db->count ( 'panacea_classes' );
		return $count;
	}
	public function get_classes($per_page, $page)
	{
		/*
		// $unique_id = "TKM_2112_";
		// $correct_id = "TKMM_2112_";
		// $query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
			// foreach ($query as $doc){
			// if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'])){
				// $nlg_pos = strpos ( $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], $unique_id);
				
				// if($nlg_pos !== false){
					// $nlg_end = $nlg_pos + strlen ($unique_id);
					// $unique_cut = substr($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'],$nlg_end,strlen ($doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']));
// log_message("debug","unique ------------------ beforeeeeeeeeeee--------".print_r($unique_cut,true));

					// $new_id = $correct_id.$unique_cut;
					
					// log_message("debug","unique ------------------ aftertttttttttttttttt--------".print_r($new_id,true));
			// $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $new_id;
			// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
			// }
			// }
			// }
		{
		 $school_id = 'mitk.128.hs#gmail.com,naresh_0597.hs#gmail.com';
		 $schoolObj = explode(",",$school_id); 
		foreach($schoolObj as $id)
		{
			 //echo print_r($schoolObj,true);
			//exit();
			//================================================================== medical evaluation//-=======================================================
			 $app_coll = $id."_apps";
			 
			 $data = json_decode('{    "app_template" : {"pages" : { "1" : {     "Personal Information" : {"Name" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "true"},"Mobile" : {"type" : "mobile","minlength" : "10","maxlength" : "10","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false"},"Hospital Unique ID" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false"},"Date of Birth" : {"type" : "date","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false"},"Photo" : {"type" : "photo","required" : "TRUE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"upload" : {    "allowed_types" : "*",    "encrypt_name" : "TRUE",    "max_size" : "5120",    "min_size" : "1024"}},"newline6" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 6},"newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 7},"newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8},"newline9" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 9},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1}     } }, "2" : {     "Personal Information" : {"AD No" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 10,"notify" : "false"},"District" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 11,"notify" : "false"},"School Name" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 12,"notify" : "false"},"Class" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 13,"notify" : "false"},"Section" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 14,"notify" : "false"},"Father Name" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 15,"notify" : "false"},"Date of Exam" : {"type" : "date","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 16,"notify" : "false"},"newline17" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 17},"newline18" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 18},"newline19" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 19},"newline20" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 20},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1}     } }, "3" : {     "Physical Exam" : {"Height cms" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "false"},"Weight kgs" : {"type" : "number","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false"},"BMI%" : {"type" : "number","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false"},"Pulse" : {"type" : "number","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false"},"B P" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false"},"Blood Group" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 6,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "A+","value" : "A+"    },    {"label" : "A-","value" : "A-"    },    {"label" : "A1-","value" : "A1-"    },    {"label" : "A1+","value" : "A1+"    },    {"label" : "A1B-","value" : "A1B-"    },    {"label" : "A1B+","value" : "A1B+"    },    {"label" : "A2-","value" : "A2-"    },    {"label" : "A2+","value" : "A2+"    },    {"label" : "A2B-","value" : "A2B-"    },    {"label" : "A2B+","value" : "A2B+"    },    {"label" : "AB+","value" : "AB+"    },    {"label" : "AB-","value" : "AB-"    },    {"label" : "B-","value" : "B-"    },    {"label" : "B+","value" : "B+"    },    {"label" : "B1+","value" : "B1+"    },    {"label" : "O-","value" : "O-"    },    {"label" : "O+","value" : "O+"    }]},"H B" : {"type" : "text","minlength" : "1","maxlength" : "123","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7,"notify" : "false"},"newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8},"newline9" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 9},"newline10" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 10},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2}     } }, "4" : {     "Doctor Check Up" : {"Check the box if normal else describe abnormalities" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Neurologic","value" : "Neurologic"    },    {"label" : "H and N","value" : "H and N"    },    {"label" : "ENT","value" : "ENT"    },    {"label" : "Lymphatic","value" : "Lymphatic"    },    {"label" : "Heart","value" : "Heart"    },    {"label" : "Lungs","value" : "Lungs"    },    {"label" : "Abdomen","value" : "Abdomen"    },    {"label" : "Genitalia","value" : "Genitalia"    },    {"label" : "Skin","value" : "Skin"    }]},"Ortho" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Neck","value" : "Neck"    },    {"label" : "Shoulders","value" : "Shoulders"    },    {"label" : "Arms/Hands","value" : "Arms/Hands"    },    {"label" : "Hips","value" : "Hips"    },    {"label" : "Knees","value" : "Knees"    },    {"label" : "Feet","value" : "Feet"    }]},"Postural" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 3,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "No spinal Abnormality","value" : "No spinal Abnormality"    },    {"label" : "Spinal Abnormality","value" : "Spinal Abnormality"    },    {"label" : "Mild","value" : "Mild"    },    {"label" : "Marked","value" : "Marked"    },    {"label" : "Moderate","value" : "Moderate"    },    {"label" : "Referral Made","value" : "Referral Made"    }]},"Description" : {"type" : "textarea","minlength" : "0","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4,"notify" : "false"},"Advice" : {"type" : "textarea","minlength" : "0","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false"},"newline6" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 6},"newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 7},"newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3}     } }, "5" : {     "Doctor Check Up" : {"Defects at Birth" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 9,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Neural Tube Defect","value" : "Neural Tube Defect"    },    {"label" : "Down Syndrome","value" : "Down Syndrome"    },    {"label" : "Cleft Lip and Palate","value" : "Cleft Lip and Palate"    },    {"label" : "Talipes Club foot","value" : "Talipes Club foot"    },    {"label" : "Developmental Dysplasia of Hip","value" : "Developmental Dysplasia of Hip"    },    {"label" : "Congenital Cataract","value" : "Congenital Cataract"    },    {"label" : "Congenital Deafness","value" : "Congenital Deafness"    },    {"label" : "Congenital Heart Disease","value" : "Congenital Heart Disease"    },    {"label" : "Retinopathy of Prematurity","value" : "Retinopathy of Prematurity"    }]},"Deficencies" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 10,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Anaemia","value" : "Anaemia"    },    {"label" : "Vitamin Deficiency - Bcomplex","value" : "Vitamin Deficiency - Bcomplex"    },    {"label" : "Vitamin A Deficiency","value" : "Vitamin A Deficiency"    },    {"label" : "Vitamin D Deficiency","value" : "Vitamin D Deficiency"    },    {"label" : "SAM/stunting","value" : "SAM/stunting"    },    {"label" : "Goiter","value" : "Goiter"    },    {"label" : "Under Weight","value" : "Under Weight"    },    {"label" : "Normal Weight","value" : "Normal Weight"    },    {"label" : "Over Weight","value" : "Over Weight"    },    {"label" : "Obese","value" : "Obese"    }]},"Childhood Diseases" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 11,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Skin Conditions","value" : "Skin Conditions"    },    {"label" : "Otitis Media","value" : "Otitis Media"    },    {"label" : "Rheumatic Heart Disease","value" : "Rheumatic Heart Disease"    },    {"label" : "Asthma","value" : "Asthma"    },    {"label" : "Convulsive Disorders","value" : "Convulsive Disorders"    },    {"label" : "Hypothyroidism","value" : "Hypothyroidism"    },    {"label" : "Diabetes","value" : "Diabetes"    },    {"label" : "Epilepsy","value" : "Epilepsy"    }]},"N A D" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 12,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    }]},"Doctor Name and Reg No" : {"type" : "text","minlength" : "","maxlength" : "","required" : "FALSE","key" : "FALSE","description" : "","multilanguage" : "FALSE","order" : 13},"newline14" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 14},"newline15" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 15},"newline16" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 16},"newline17" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 17},"newline18" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 18},"newline19" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 19},"newline20" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 20},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3}     } }, "6" : {     "Screenings" : {"Detected Myopia Hypermetropia" : {"type" : "instruction","required" : "FALSE","key" : "TRUE","instructions" : "Vision Screening","description" : "","multilanguage" : "FALSE","order" : 1},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 4}     },     "Without Glasses" : {"Right" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "false"},"Left" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false"},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5}     },     "With Glasses" : {"Right" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 1,"notify" : "false"},"Left" : {"type" : "text","minlength" : "0","maxlength" : "60","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 2,"notify" : "false"},"newline3" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 3},"newline4" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 4},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6}     } }, "7" : {     "Colour Blindness" : {"Right" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Left" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Description" : {"type" : "textarea","minlength" : "0","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 3,"notify" : "false"},"Referral Made" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 4,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    }]},"Docter Name and Reg No" : {"type" : "text","minlength" : "","maxlength" : "","required" : "FALSE","key" : "FALSE","description" : "","multilanguage" : "FALSE","order" : 5},"newline6" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 6},"newline7" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 7},"newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8},"newline9" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 9},"newline10" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 10},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 7}     } }, "8" : {     " Auditory Screening" : {"Right" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Pass","value" : "Pass"    },    {"label" : "Fail","value" : "Fail"    }]},"Left" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Pass","value" : "Pass"    },    {"label" : "Fail","value" : "Fail"    }]},"Speech Screening" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 3,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Normal","value" : "Normal"    },    {"label" : "Delay","value" : "Delay"    },    {"label" : "Misarticulation","value" : "Misarticulation"    },    {"label" : "Fluency","value" : "Fluency"    },    {"label" : "Voice","value" : "Voice"    }]},"D D and disability" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 4,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Language Delay","value" : "Language Delay"    },    {"label" : "Behaviour Disorder","value" : "Behaviour Disorder"    }]},"Description" : {"type" : "textarea","minlength" : "0","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 5,"notify" : "false"},"Referral Made" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 6,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    }]},"Doctor Name and Reg No" : {"type" : "text","minlength" : "","maxlength" : "","required" : "FALSE","key" : "FALSE","description" : "","multilanguage" : "FALSE","order" : 7},"newline8" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 8},"newline9" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 9},"newline10" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 10},"newline11" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 11},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 8}     } }, "9" : {     "Dental Check-up" : {"Oral Hygiene" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 1,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Good","value" : "Good"    },    {"label" : "Fair","value" : "Fair"    },    {"label" : "Poor","value" : "Poor"    }]},"Carious Teeth" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 2,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Flourosis" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 3,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Orthodontic Treatment" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 4,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Indication for extraction" : {"type" : "radio","required" : "FALSE","key" : "TRUE","description" : "","order" : 5,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    },    {"label" : "No","value" : "No"    }]},"Result" : {"type" : "textarea","minlength" : "0","maxlength" : "250","required" : "FALSE","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 6,"notify" : "false"},"Referral Made" : {"type" : "checkbox","required" : "FALSE","key" : "TRUE","description" : "","order" : 7,"multilanguage" : "FALSE","notify" : "false","options" : [    {"label" : "Yes","value" : "Yes"    }]},"Doctor Name and Reg No" : {"type" : "text","minlength" : "","maxlength" : "","required" : "FALSE","key" : "FALSE","description" : "","multilanguage" : "FALSE","order" : 8},"newline9" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 9},"newline10" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 10},"newline11" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 11},"newline12" : {"type" : "newline","key" : "FALSE","multilanguage" : "FALSE","order" : 12},"dont_use_this_name" : {"type" : "SBreak","key" : "TRUE","description" : "","multilanguage" : "FALSE","order" : 9}     } }},"permissions" : { "Stage Name1" : {     "View_Permissions" : ["Personal Information","Physical Exam","Doctor Check Up","Screenings","Without Glasses","With Glasses","Colour Blindness"," Auditory Screening","Dental Check-up"     ],     "Edit_Permissions" : ["Personal Information","Physical Exam","Doctor Check Up","Screenings","Without Glasses","With Glasses","Colour Blindness"," Auditory Screening","Dental Check-up"     ],     "index" : 1 }},"notification_parameters" : [ {     "field" : "Name",     "page" : "1",     "section" : "Personal Information" }],"application_header" : { "header_details" : {     "companyname" : "TMREIS",     "address" : "HYD,TG,India",     "logo" : "./uploaddir/public/app_header/healthcare201671115519757.png" }}     },     "app_id" : "healthcare201671115519757",     "app_description" : "for tmwreis",     "status" : "processed",     "app_name" : "Medical Evaluation",     "app_created" : "2016-07-20 20:32:12",     "app_expiry" : "2020-05-21",     "_version" : 1,     "stages" : ["Stage Name1"     ],     "created_by" : "tlstec.primary2@gmail.com",     "use_profile_header" : "no",     "blank_app" : "no" }',true);
			
			log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			 $app_coll = $id."_applist";
			 $data = json_decode('{"app_id" : "healthcare201671115519757","app_description" : "for tmwreis","app_name" : "Medical Evaluation","app_created" : "2016-07-20 20:32:12"}',true);
			 
			log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			 $this->mongo_db->insert ( $app_coll, $data ); 
			 
			
			 echo print_r($app_coll,true);
			exit();
		} */
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_classes' );
		return $query;
	}
	public function sectionscount() {
		$count = $this->mongo_db->count ( 'ttwreis_sections' );
		return $count;
	}
	public function get_sections($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_sections' );
		return $query;
	}
	public function symptomscount() {
		$count = $this->mongo_db->count ( 'ttwreis_symptoms' );
		return $count;
	}
	public function get_symptoms($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_symptoms' );
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
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=> -1))->where( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['ttwreis_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->notes_col );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['panacea_schools'] );
			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['ttwreis_health_supervisors'] );
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
		$query = $this->mongo_db->insert ( 'ttwreis_diagnostics', $data );
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
		$query = $this->mongo_db->insert ( 'ttwreis_hospitals', $data );
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
		log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $result, true ) );
		
		// $query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
		// return $query;
		return $result;
	}
	public function hospitalscount() {
		$count = $this->mongo_db->count ( 'ttwreis_hospitals' );
		return $count;
	}
	public function get_hospitals($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_hospitals' );
		foreach ( $query as $hospitals => $hospital ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $hospital ['dt_name'] ) )->get ( 'ttwreis_district' );
			if (isset ( $hospital ['dt_name'] )) {
				$query [$hospitals] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$hospitals] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
	}
	public function diagnosticscount() {
		$count = $this->mongo_db->count ( 'ttwreis_diagnostics' );
		return $count;
	}
	public function get_diagnostics($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_diagnostics' );
		foreach ( $query as $diagnostics => $dia ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $dia ['dt_name'] ) )->get ( 'ttwreis_district' );
			if (isset ( $dia ['dt_name'] )) {
				$query [$diagnostics] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$diagnostics] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function empcount() {
		$count = $this->mongo_db->count ( 'ttwreis_emp' );
		return $count;
	}
	public function get_emp($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_emp' );
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
	public function get_all_symptoms($date = false, $request_duration = "Daily",$cro_district_code,$dt_name = "All", $school_name = "All") {


		/* $query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],$cro_district_code );
		
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
		
		
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
		
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values; */
		
		$query = [ ];
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $cro_district_code);
		
		$doc_query = array ();
		
		foreach ( $query as $doc ) {
			
			$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$screening_doc = $this->mongo_db->select ( array (
				'doc_data.widget_data.page2' 
			) )->where ( array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=>$unique_id))->get ( $this->screening_app_col );
			
			if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
				array_push ( $doc_query, $doc );
			}
		}
		$query = $doc_query;
		
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
		
		
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
			
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function get_absent_pie_schools_data($date = FALSE,$district_name, $cro_dist)
	{
		
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
		
		
		//$schools_list = $this->get_all_schools($dt_name);

		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$schools_list = $this->mongo_db->whereLike ( 'dt_name',$cro_dist )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

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
		) )->whereLike ( 'history.last_stage.time', $today_date)->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );

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

		return $schools_data;
	}
	
	public function get_all_absent_data($date = FALSE, $dt_name="All", $school_name = "All") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		$query = $this->mongo_db->select ( array (
			"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->where(array('doc_data.widget_data.page1.Attendence Details.District'=>$dt_name))->get ( $this->absent_app_col );
		log_message('debug','get_all_absent_data====select======='.print_r($query,true));

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
			$query = $doc_query;
			log_message('debug','$school_name == "All"==========='.print_r($query,true));

			
		} else {
			foreach ( $query as $doc ) {
				
				if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
					array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
			log_message('debug','individualschool============='.print_r($query,true));

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
		
		log_message('debug','final_absent_pie============='.print_r($requests,true));
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
	
	public function drilldown_absent_to_districts($data, $date, $district_name, $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		switch ($type) {
			case "ABSENT REPORT" :
			
			ini_set ( 'memory_limit', '1G' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );

			$doc_query = array ();
			if ($school_name == "All") 
			{
				foreach ( $query as $doc ) 
				{
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) 
					{
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
			ini_set ( 'memory_limit', '512M' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
			
			ini_set ( 'memory_limit', '512M' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
			
			ini_set ( 'memory_limit', '512M' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
	public function get_drilling_absent_schools($data, $date, $district_name, $school_name) {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		switch ($type) {
			case "ABSENT REPORT" :
			
			ini_set ( 'memory_limit', '512M' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
			
			ini_set ( 'memory_limit', '512M' );
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
	public function get_drilling_absent_students($data, $date, $district_name, $school_name = "All") {
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
			
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				
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
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where('doc_data.widget_data.page1.Attendence Details.District',$district_name)->get ( $this->absent_app_col );
			
			$doc_query = array ();
			if ($school_name == "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $district_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
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
		
		foreach ( $dist_arr as $districts ) {
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
			// //log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
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
	public function get_all_symptoms_docs($start_date, $end_date,$cro_district_code, $id_for_school = false,$dt_name = "All", $school_name = "All") {

		ini_set ( 'max_execution_time', 0 );
		//ini_set('memory_limit', '100G');
		
		if ($id_for_school) {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
				$id_for_school 
			) )->get ( $this->request_app_col );
		} else {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->select ( array (
				"doc_data.widget_data",
				"history" 
			) )->get ( $this->request_app_col );
			
			log_message('debug','$unique_id_pattern=====3914====='.print_r($query,true));
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
		} else if ($request_duration == "Yearly") {
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
	public function get_all_requests($date = false, $request_duration = "Daily",$cro_district_code, $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration);
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],$cro_district_code);
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id)->get ( $this->screening_app_col );
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
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id)->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
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
	public function get_all_requests_docs($start_date, $end_date, $type = false,$cro_district_code, $dt_name = "All", $school_name = "All") {
		if ($type == "Initiated") {
			$query = $this->mongo_db->where ( array (
				'doc_data.widget_data.page2.Review Info.Status' => $type,
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code,'i')->get ( $this->request_app_col );
		}else if ($type == "Screening") {
			$query = $this->mongo_db->where ( array (
				'history.0.submitted_user_type' => "PADMIN",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
		} else if ($type == "Normal") {
			$query = $this->mongo_db->where ( array (
				'doc_data.widget_data.page2.Review Info.Request Type' => "Normal",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
		} else if ($type == "Emergency") {
			$query = $this->mongo_db->where ( array (
				'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
		} else if ($type == "Chronic") {
			$query = $this->mongo_db->where ( array (
				'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
		} 
		else if ($type == "Hospitalized") {
			$query = $this->mongo_db->where ( array (
				'doc_data.widget_data.page2.Review Info.Status' => "Hospitalized",
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured") 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
		}else {
			$query = $this->mongo_db->whereLike ( array('doc_data.widget_data.page2.Review Info.Status'=> $type,
				"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$cro_district_code)->get ( $this->request_app_col );
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
	public function drilldown_request_to_districts($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		//$data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		// ini_set('memory_limit', '512M');
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name);
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal",$cro_district_code_pattern, $dt_name, $school_name);
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency",$cro_district_code_pattern, $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic",$cro_district_code_pattern, $dt_name, $school_name );
		} 
		else if ($type == "Hospitalized") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Hospitalized",$cro_district_code_pattern, $dt_name, $school_name );
		}else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type,$cro_district_code_pattern, $dt_name, $school_name);
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
	public function get_drilling_request_schools($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $cro_district_code_pattern,$dt_name, $school_name );
			
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
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal",$cro_district_code_pattern, $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency",$cro_district_code_pattern, $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic",$cro_district_code_pattern, $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type,$cro_district_code_pattern, $dt_name, $school_name );
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
	public function get_drilling_request_students($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening",$cro_district_code_pattern, $dt_name, $school_name );
			
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
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal",$cro_district_code_pattern, $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency",$cro_district_code_pattern, $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic",$cro_district_code_pattern, $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type,$cro_district_code_pattern, $dt_name, $school_name );
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
		log_message ( "debug", "dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd" . print_r ( $_id_array, true ) );
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
	public function drilldown_identifiers_to_districts($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
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
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $cro_district_code_pattern,$type );
		
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
	public function get_drilling_identifiers_schools($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
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
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],$cro_district_code_pattern, $type );
		
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
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
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
	public function get_drilling_identifiers_students($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All",$cro_district_code_pattern) {
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
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],$cro_district_code_pattern, $type );
		
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
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	
	
	private function screening_pie_data_for_stage5($dates) {
		ini_set ( 'max_execution_time', 0 );
		ini_set('memory_limit','10G');
		
		$school_list = $this->get_all_schools ();
		// log_message('debug','schhhhhhhhhhhhhhhhhhhhhhhhhhoooooooooooooooooooooooooooooolllllllll'.print_r($school_list,true));
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 5000) {
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
	
	
	private function screening_pie_data_for_stage5_tt($dates) {
		ini_set ( 'max_execution_time', 0 );
		ini_set('memory_limit','10G');
		
		$school_list = $this->get_all_schools ();
		// log_message('debug','schhhhhhhhhhhhhhhhhhhhhhhhhhoooooooooooooooooooooooooooooolllllllll'.print_r($school_list,true));
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 5000) {
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
			$request ["Over Weight"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
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
			$request ["Indication for extraction"] [strtolower ( $school_name ['school_name'] )] = $this->get_drilling_screenings_students_prepare_pie_array ( $result, strtolower ( $school_name ['school_name'] ) );
		}
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	public function update_screening_collection($date, $screening_duration) {
		if ($date) {
			//$date = date('Y-m-d', $date);
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration ); // "Daily" ); //
		log_message ( "debug", "datesssssssssssssssssssssssssssssssss--------------------" . print_r ( $dates, true ) );
		// ===================================stage1================================================
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) {
			log_message ( "debug", "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii--------------------" . print_r ( $init_date, true ) );
			log_message ( "debug", "eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee--------------------" . print_r ( $dates ['end_date'], true ) );
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
				
				$requests = $this->screening_pie_data_for_stage5 ( $temp_dates );
				$pie_data ['pie_data'] ['stage5_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage4_new ( $requests );
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3_new ( $requests );
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2_new ( $requests );
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				log_message ( "debug", "before stagesssssssssssssssssssssssssss--------------------" );
				$requests = $this->screening_pie_data_for_stage1_new ( $requests );
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				
				$this->mongo_db->insert ( $this->screening_app_col_screening, $pie_data );
				log_message ( "debug", "tttttttttttttttttttttttttttttttttttttttttttttttttttttttt" . print_r ( $init_date, true ) );
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
		log_message ( "debug", "datesssssssssssssssssssssssssssssssss--------------------" . print_r ( $dates, true ) );
		// ===================================stage1================================================
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) {
			log_message ( "debug", "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii--------------------" . print_r ( $init_date, true ) );
			log_message ( "debug", "eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee--------------------" . print_r ( $dates ['end_date'], true ) );
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
				log_message ( "debug", "before stagesssssssssssssssssssssssssss--------------------" );
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
				log_message ( "debug", "tttttttttttttttttttttttttttttttttttttttttttttttttttttttt" . print_r ( $init_date, true ) );
			}
			$init_date = $end_date;
		}
		
		// ===================================stage1 end============================================
		
		// ============================================================stage 2===============================================
		
		// ============================================================end of stage 2 =======================================
		
		// ===================================================insert into db=================================================
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
	public function get_all_screenings($date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		// ================================================== for generated analytics
		ini_set ( 'memory_limit', '10G' );
		//ini_set ( 'memory_limit', '100G' );
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage1_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( 'healthcare2016226112942701_screening_final_'.$year );
		
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
		
		
	}
	
	public function get_drilling_screenings_abnormalities($data, $date = false, $screening_duration = "Yearly") {
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
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage2_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( 'healthcare2016226112942701_screening_final_'.$year );

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
			
			return $requests;
			break;
			
			case "General Abnormalities" :
			
			$requests = [ ];
			
			$request ['label'] = 'General';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [2] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Skin';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [3] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Others(Description/Advice)';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [4] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Ortho';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [5] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Postural';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Defects at Birth';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [7] ['General Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Vitamin Deficiency - Bcomplex';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			
			$request ['label'] = 'Vitamin A Deficiency';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			
			$request ['label'] = 'Vitamin D Deficiency';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [11] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			
			$request ['label'] = 'SAM/stunting';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [12] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			
			$request ['label'] = 'Goiter';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
				//==========================================Deficencies divided
			
			$request ['label'] = 'Childhood Diseases';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [14] ['General Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [15] ['Eye Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'With Glasses';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [16] ['Eye Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Colour Blindness';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [17] ['Eye Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [18] ['Auditory Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Left Ear';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [19] ['Auditory Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Speech Screening';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [20] ['Auditory Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [21] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Oral Hygiene - Poor';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [22] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Carious Teeth';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [23] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Flourosis';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [24] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Orthodontic Treatment';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [25] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Indication for extraction';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [26] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			return $requests;
			break;
			
			default :
			break;
		}
	}
	
	public function get_drilling_screenings_abnormalities_tt($data, $date = false, $screening_duration = "Yearly") {
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
			
			return $requests;
			break;
			
			case "General Abnormalities" :
			
			$requests = [ ];
			
			$request ['label'] = 'General';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [2] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Skin';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [3] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Others(Description/Advice)';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [4] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Ortho';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [5] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Postural';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [6] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Defects at Birth';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [7] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Deficencies';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [8] ['General Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Childhood Diseases';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [9] ['General Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [10] ['Eye Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'With Glasses';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [11] ['Eye Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Colour Blindness';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [12] ['Eye Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [13] ['Auditory Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Left Ear';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [14] ['Auditory Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Speech Screening';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [15] ['Auditory Abnormalities'] ['value'];
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
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [16] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Oral Hygiene - Poor';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [17] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Carious Teeth';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [18] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Flourosis';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [19] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Orthodontic Treatment';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [20] ['Dental Abnormalities'] ['value'];
			}
			array_push ( $requests, $request );
			
			$request ['label'] = 'Indication for extraction';
			$request ['value'] = 0;
			
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
				$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_vales'] [21] ['Dental Abnormalities'] ['value'];
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage3_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		switch ($type) {
			case "Over Weight" :
			
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.District","Nalgonda")->get($this->screening_app_col);
				// foreach ($query as $doc){
			
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = "Nalgonda";
			
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
			log_message ( "debug", "iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================" );
			
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
	
	public function get_drilling_screenings_districts_tt($data, $date = false, $screening_duration = "Yearly") {
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
			case "Over Weight" :
			
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.District","Nalgonda")->get($this->screening_app_col);
				// foreach ($query as $doc){
			
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = "Nalgonda";
			
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
			log_message ( "debug", "iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================" );
			
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage4_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
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
	
	public function get_drilling_screenings_schools_tt($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage4_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
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
			
			case "Deficencies" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] != null)
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] );
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
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
			'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		log_message ( "debug", "obbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbjjjjjjjjjjjjj" . print_r ( $school_name, true ) );
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
	
	public function get_drilling_screenings_students_tt($data, $date = false, $screening_duration = "Yearly") {
		ini_set ( 'memory_limit', '1G' );
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
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		log_message ( "debug", "obbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbjjjjjjjjjjjjj" . print_r ( $obj_data, true ) );
		switch ($type) {
			case "Over Weight" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pieeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee".print_r($each_pie,true));
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] )) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [strtolower ( $school_name )] );
				}
			}
			
			return $requests;
			break;
			
			case "Under Weight" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "General" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Skin" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Others(Description/Advice)" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Ortho" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Postural" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Defects at Birth" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Deficencies" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Childhood Diseases" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Without Glasses" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "With Glasses" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Colour Blindness" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Right Ear" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Left Ear" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Speech Screening" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Oral Hygiene - Fair" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Oral Hygiene - Poor" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Carious Teeth" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Flourosis" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Orthodontic Treatment" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $school_name )] );
			}
			
			return $requests;
			break;
			
			case "Indication for extraction" :
			
			$requests = [ ];
			foreach ( $pie_data as $each_pie ) {
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] ))
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [strtolower ( $school_name )] );
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
		
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select(array("doc_data.widget_data"))->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			
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
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['panacea_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->notes_col );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['panacea_schools'] );
			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['panacea_health_supervisors'] );
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
		) )->get ( $this->collections ['ttwreis_health_supervisors'] );
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
		) )->get ( $this->collections ['ttwreis_cc'] );
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
		) )->get ( $this->collections ['panacea_schools'] );
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
		) )->get ( $this->collections ['ttwreis_doctors'] );
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
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['panacea_schools'] );
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
			log_message ( "debug", "22222222222222222222222222222222222222" . print_r ( $school_name, true ) );
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
	public function get_health_supervisors_school_id($id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( 'school_code', $id )->select ( array (
			'hs_name',
			'hs_mob' 
		) )->get ( $this->collections ['ttwreis_health_supervisors'] );
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
		) )->where ( 'school_name', $school_name )->get ( $this->collections ['panacea_schools'] );
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
		$stage_data ['value'] = $requests [0] ["Physical Abnormalities"] ['value'] + $requests [1] ["Physical Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [2] ["General Abnormalities"] ['value'] + $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'] + $requests [10] ["General Abnormalities"] ['value'] + $requests [11] ["General Abnormalities"] ['value'] + $requests [12] ["General Abnormalities"] ['value'] + $requests [13] ["General Abnormalities"] ['value'] + $requests [14] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [15] ["Eye Abnormalities"] ['value'] + $requests [16] ["Eye Abnormalities"] ['value'] + $requests [17] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [18] ["Auditory Abnormalities"] ['value'] + $requests [19] ["Auditory Abnormalities"] ['value'] + $requests [20] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [21] ["Dental Abnormalities"] ['value'] + $requests [22] ["Dental Abnormalities"] ['value'] + $requests [23] ["Dental Abnormalities"] ['value'] + $requests [24] ["Dental Abnormalities"] ['value'] + $requests [25] ["Dental Abnormalities"] ['value'] + $requests [26] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	public function screening_pie_data_for_stage4_new_tt($requests) {
		$school_list = $this->get_all_schools ();
		$school_in_dist = [ ];
		
		foreach ( $school_list as $school ) {
			$school_in_dist [strtolower ( $school ['school_name'] )] = strtolower ( $school ['dt_name'] );
		}
		$request_stage4 = [ ];
		
		foreach ( $requests as $screening_index => $screening_array ) {
			$request_stage4 [$screening_index] = [ ];
			// log_message("debug","in 11111111111111111111111111111111111111111111111111111111=======".print_r($request_stage4,true));
			foreach ( $screening_array as $school_name => $inner_data ) {
				// log_message("debug","in 222222222222222222222222222222222222222222222222222222=======".print_r($request_stage4,true));
				if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
					$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = null;
				}
				
				$school_data = [ ];
				if (count ( $inner_data ) > 0) {
					if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
						$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = [ ];
					}
					$school_data ['label'] = strtoupper ( $school_name );
					$school_data ['value'] = count ( $inner_data );
					array_push ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]], $school_data );
					// log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($request_stage4,true));
				}
			}
		}
		return $request_stage4;
	}
	public function screening_pie_data_for_stage3_new_tt($requests) {
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
	public function screening_pie_data_for_stage2_new_tt($requests) {
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
	public function screening_pie_data_for_stage1_new_tt($requests) {
		$request_stage1 = [ ];
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests [0] ["Physical Abnormalities"] ['value'] + $requests [1] ["Physical Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [2] ["General Abnormalities"] ['value'] + $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [10] ["Eye Abnormalities"] ['value'] + $requests [11] ["Eye Abnormalities"] ['value'] + $requests [12] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [13] ["Auditory Abnormalities"] ['value'] + $requests [14] ["Auditory Abnormalities"] ['value'] + $requests [15] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [16] ["Dental Abnormalities"] ['value'] + $requests [17] ["Dental Abnormalities"] ['value'] + $requests [18] ["Dental Abnormalities"] ['value'] + $requests [19] ["Dental Abnormalities"] ['value'] + $requests [20] ["Dental Abnormalities"] ['value'] + $requests [21] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	public function initiate_request($doc_id, $user_coll_data, $app_coll_data) {
		$user_collection = $doc_id . "_docs";
		$this->mongo_db->insert ( $user_collection, $user_coll_data );
		$this->mongo_db->insert ( $this->request_app_col, $app_coll_data );
		$this->mongo_db->insert ( $this->request_app_col . "_shadow", $app_coll_data );
		$this->mongo_db->insertStut ( 'status', $this->request_app_col, $app_coll_data ["doc_properties"] ['doc_id'], $app_coll_data ["app_properties"] ['app_name'] );
	}
	
	public function messaging($message)
	{
		$data = $this->session->userdata("customer");
		
		$data = array(
			"message" => $message,
			"unique_id" => $uid,
		);
		
		$response = $this->data = $this->ttwreis_common_model->messaging($message);
		//$this->data = "";
		
		$this->output->set_output($response);
	}
	public function groupscount() {
		$count = $this->mongo_db->count ( 'ttwreis_chat_groups' );
		return $count;
	}
	public function get_groups($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'ttwreis_chat_groups' );
		return $query;
	}
	
	public function get_all_groups() {
		$query = $this->mongo_db->get ( 'ttwreis_chat_groups' );
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Get accessible chat rooms ( for the loggedin user )
	 * 
	 * @param  array  $user_email  Loggedin user email
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	
	public function get_accessible_groups($user_email) {
		$accessible_chat_rooms = array();
		$query = $this->mongo_db->get ( 'ttwreis_chat_groups' );
		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13631====='.print_r($query,true));
		foreach($query as $data)
		{
			$group_name = $data['group_name'];
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13635====='.print_r($group_name,true));
			$where_array = array('group_name'=>$group_name,'list_of_users'=>array('$in'=>array($user_email)));
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13637====='.print_r($where_array,true));
			$grps = $this->mongo_db->where($where_array)->get ( 'ttwreis_chat_groups_users' );
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13639====='.print_r($grps,true));
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13640====='.print_r(count($grps),true));
			if(isset($grps) && !empty($grps))
			{
				array_push($accessible_chat_rooms,$query);
			}
			
		}
		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13644====='.print_r($accessible_chat_rooms,true));
		return $accessible_chat_rooms;
	}
	
	public function get_all_admin_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['ttwreis_admins'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_health_supervisors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['ttwreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_cc_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['ttwreis_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_superiors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['superiors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper: Add message
	 * 
	 * @param  array  $post          $_POST data
	 * @param  array  $chat_room_id  chat Room ID
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	
	public function add_message($post,$chat_room_id)
	{
		$data = array(
			"message_id"   => get_unique_id(),
			"user_id"      => $post['user_id'],
			"user_name"    => $post['username'],
			"chat_room_id" => $chat_room_id,
			"message" 	   => $post['message'],
			"created_at"   => date("Y-m-d H:i:s")
		);
		$query = $this->mongo_db->insert($this->collections['ttwreis_messages'],$data);
		
		if($query){
			$response['error'] = false;			
			$response['message'] = $data;
		}else{
			$response['error'] = true;
			$response['message'] = 'Failed send message ' . $stmt->error;
		}
		
		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$user_id==13660====='.print_r($response,true));
		return $response;
	}
	public function get_messages($msg_id)
	{
		$query = $this->mongo_db->where("chat_room_id",$msg_id)->get($this->collections['ttwreis_messages']);
		return $query;
	}
	
	public function get_user_by_email($name, $email){
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=========.'.print_r($email,true));
		$user = $this->mongo_db->where("email",$email)->get($this->collections['ttwreis_admins']);
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
		$id_exists = $this->mongo_db->where("user_id",$login)->get($this->collections['ttwreis_users_gcm']);
		$data = array(
			"user_id" => $login,
			"gcm_registration_id" => $gcm_registration_id
		);
		if($id_exists){
			$query = $this->mongo_db->where("user_id",$login)->set($data)->get($this->collections['ttwreis_users_gcm']);
		}else{
			$query = $this->mongo_db->insert($this->collections['ttwreis_users_gcm'],$data);
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
	
	public function get_sanitation_report_pie_data($date, $search_criteria, $opt,$dt_name) {
		
		$output 			     = array();
		$sanitation_report      = array();
		$sanitation_report['district_list']   = array();
		$sanitation_report['schools_list']    = array();
		$sanitation_report['attachment_list'] = array();
		
		$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.external_attachments'),array())->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$date,$search_criteria=>$opt,'doc_data.widget_data.page4.School Information.District'=>$dt_name))->get($this->sanitation_app_col);
		log_message("debug","get_sanitation_report_pie_data====18787====".print_r($query,true));

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
	
	public function get_sanitation_report_pie_schools_data($date = FALSE,$dt_name,$cro_dist)
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

		$not_submitted_dist        = array();
		
		$all_schools_mobile        = array();
		$all_schools_cpn      	   = array();
		$submitted_school_mob 	   = array();
		$submitted_school_person   = array();
		$not_submitted_school_mob 	   = array();
		$not_submitted_school_person   = array();
		
		//$schools_list = $this->get_all_schools($dt_name);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$schools_list = $this->mongo_db->whereLike ( 'dt_name',$cro_dist )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

		
		
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
		) )->whereLike ( 'doc_data.widget_data.page4.Declaration Information.Date:',$today_date)->where('doc_data.widget_data.page4.School Information.District',$dt_name)->get ( $this->sanitation_app_col );
		
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
	
	public function ttwreis_chronic_cases_count()
	{
		$query = $this->mongo_db->get($this->collections['ttwreis_chronic_cases']);
		return count($query);
	}
	
	function get_chronic_cases_model($limit, $page)
	{
		$offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->get($this->collections['ttwreis_chronic_cases']);
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
		$query = $this->mongo_db->get($this->collections['ttwreis_chronic_cases']);
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_all_chronic_unique_ids_model($cro_district_code)
	{
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$query = $this->mongo_db->select(array('student_unique_id','case_id','scheduled_months','school_name'),array())->where(array('followup_scheduled'=>'true'))->whereLike('student_unique_id',$cro_district_code)->get($this->collections['tswreis_chronic_cases']);
		
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
		
		$updated = $this->mongo_db->where(array('student_unique_id'=>$unique_id,'case_id'=>$case_id))->set($update_array)->update($this->collections['tswreis_chronic_cases']);
		
		if($updated)
			return TRUE;
		else
			return FALSE;
	}
	
	function calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken)
	{
		$medication_schedule = array();
		$query = $this->mongo_db->select(array(),array())->getWhere($this->collections['tswreis_chronic_cases'],array('student_unique_id'=>$unique_id,'case_id'=>$case_id));  
		
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
		
		$is_already_updated = $this->mongo_db->where($check_query)->get($this->collections['tswreis_chronic_cases']);
		
		if($is_already_updated)
		{
			return "ALREADY_UPDATED";
		}
		else
		{
			$datewise_update = array("date"=>$selected_date,"compliance"=>$compliance);
			
			$query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id);
			
			$update = array('$push'=>array("medication_taken"=>$datewise_update));
			
			$response = $this->mongo_db->command(array( 
				'findAndModify' => $this->collections['tswreis_chronic_cases'],
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
		
		$query = $this->mongo_db->where($where_clause)->get($this->collections['tswreis_chronic_cases']);
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
		$query_request = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->get ( $this->collections['ttwreis_req_notes'] );
		
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
		
		$is_notes = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->count( $this->collections ['ttwreis_req_notes'] );
		
		if($is_notes > 0){
			$token = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->set($query_request[0])->update( $this->collections ['ttwreis_req_notes'] );
		}else{
			$token = $this->mongo_db->insert( $this->collections ['ttwreis_req_notes'], $query_request[0]);
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
			'aggregate' => $this->collections ['rhso_news_feed'],
			'pipeline' => $pipeline 
		) );
		$query = array();
		if($response['ok']){
			$query = $response["result"];
		}
		
		return $query;
		
	}

	public function add_news_feed($news_data){
		
		$query = $this->mongo_db->insert ( $this->collections ['rhso_news_feed'], $news_data );
		
		return $query;
		
	}
	
	public function get_all_news_feeds(){
		
		$query = $this->mongo_db->get ( $this->collections ['rhso_news_feed'] );
		
		return $query;
		
	}
	
	public function delete_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->delete($this->collections['rhso_news_feed']);
		return $query;
	}
	
	public function get_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->get($this->collections['rhso_news_feed']);
		return $query[0];
	}
	
	public function update_news_feed($news_data,$news_id)
	{
		
		$query = $this->mongo_db->where(array("_id"=>new MongoId($news_id)))->set($news_data)->update($this->collections['rhso_news_feed']);
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
	
	
	/*
	*Fetchinhg BMI value with Unique id
	*author Naresh
	
	*/ 
	public function get_student_bmi_values($cro_district_code,$unique_id)
	{
		
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID','doc_data.widget_data.page1.Student Details.BMI_values'))->whereLike('doc_data.widget_data.page1.Student Details.Hospital Unique ID',$cro_district_code)->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('healthcare2017617145744625');
		/* $merge_BMI_uniqueid = array();
		foreach($query as $q)
		{
			
			$hospital_unique_id = $q['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];
			$personal_info = $this->mongo_db->select(array('doc_data.widget_data.page1.Personal Information','doc_data.widget_data.page2.Personal Information'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $hospital_unique_id))->get('healthcare2016226112942701');
			 array_push($merge_BMI_uniqueid,$personal_info);
			 log_message('debug','get_student_bmi_values====19419=='.print_r($merge_BMI_uniqueid,true));
			} */
			
			if($query)
			{
				return $query;
			} 
			else
			{
				return FALSE;
			}
		}

		
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
			->get($this->collections['rhso_users']);

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
				->update($this->collections['rhso_users']);
				
				$this->mongo_db->switchDatabase($this->common_db['dsn']);
				return $updated;
			}
			
			return FALSE;
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
		->get($this->collections['rhso_users']);
		
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

	public function attendance_submitted_schools_reports_model($date, $dt_name, $cro_dist)
	{
		
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

		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$schools_list = $this->mongo_db->select(array('school_name'))->whereLike ( 'dt_name',$cro_dist )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

		foreach($schools_list as $school_data)
		{
			//array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
			/*$all_schools_mobile[$school_data['school_name']] = $school_data['school_mob'];
			$all_schools_cpn[$school_data['school_name']] = $school_data['contact_person_name'];*/
		}
		
		//$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name;

		$query = $this->mongo_db->select ( array (
			"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date)->where('doc_data.widget_data.page1.Attendence Details.District',$dt_name)->get ( $this->absent_app_col );

		foreach ( $query as $doc ) {
			if(!in_array($doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'],$submitted_school_name))
			{
					//array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] );
				array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] );
					/*if(isset($all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']])){
						array_push ( $submitted_school_mob,$all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
						array_push ( $submitted_school_person,$all_schools_cpn[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
					}else{
						array_push ( $submitted_school_mob,"" );
						array_push ( $submitted_school_person,"" );
					}*/
				}
			}
			
		//$submitted_schools['district']     = $submitted_school_district;
			$submitted_schools  = $submitted_school_name;
		//$submitted_schools['mobile']       = $submitted_school_mob;
		//$submitted_schools['person_name']  = $submitted_school_person;

		//$not_submitted_schools['district'] = array();
			$not_submitted_schools   = array_values(array_diff($all_schools['school'],$submitted_schools));
			
		//$result['submitted_schools'] = $submitted_schools;
			$result['submitted_schools'] = $query;
			$result['not_submitted'] = $not_submitted_schools;

			return $result;
			
		}

		public function sanitation_submitted_schools_reports_model($date, $dt_name, $cro_dist)
		{
			
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

			if ($date) {
				$today_date = $date;
			} else {
				$today_date = $this->today_date;
			}
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$schools_list = $this->mongo_db->select(array('school_name'))->whereLike ( 'dt_name',$cro_dist )->get ( $this->collections ['panacea_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

			foreach($schools_list as $school_data)
			{
			//array_push($all_schools_district,$school_data['dt_name']);
				array_push($all_schools_name,$school_data['school_name']);
			/*$all_schools_mobile[$school_data['school_name']] = $school_data['school_mob'];
			$all_schools_cpn[$school_data['school_name']] = $school_data['contact_person_name'];*/
		}
		
		//$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name;

		$query = $this->mongo_db->select ( array (
			"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date)->whereLike('doc_data.widget_data.page4.School Information.District',$dt_name)->get ( $this->sanitation_app_col );

		foreach ( $query as $doc ) {
			if(!in_array($doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$submitted_school_name))
			{
					//array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] );
				array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'] );
					/*if(isset($all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']])){
						array_push ( $submitted_school_mob,$all_schools_mobile[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
						array_push ( $submitted_school_person,$all_schools_cpn[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] );
					}else{
						array_push ( $submitted_school_mob,"" );
						array_push ( $submitted_school_person,"" );
					}*/
				}
			}
			
		//$submitted_schools['district']     = $submitted_school_district;
			$submitted_schools  = $submitted_school_name;
		//$submitted_schools['mobile']       = $submitted_school_mob;
		//$submitted_schools['person_name']  = $submitted_school_person;

		//$not_submitted_schools['district'] = array();
			$not_submitted_schools   = array_values(array_diff($all_schools['school'],$submitted_schools));
			
		//$result['submitted_schools'] = $submitted_schools;
			$result['sanitation_submitted_schools'] = $query;
			$result['sanitation_not_submitted'] = $not_submitted_schools;
			
			return $result;
			
		}

		public function get_all_absent_report($date = FALSE, $dt_name="All", $school_name = "All") {
			if ($date) {
				$today_date = $date;
			} else {
				$today_date = $this->today_date;
			}
			$query = $this->mongo_db->select ( array (
				"doc_data.widget_data.page1.Attendence Details.District",
				"doc_data.widget_data.page1.Attendence Details.Select School",
				"doc_data.widget_data.page1.Attendence Details.Attended", 
				"doc_data.widget_data.page1.Attendence Details.Sick", 
				"doc_data.widget_data.page1.Attendence Details.R2H", 
				"doc_data.widget_data.page1.Attendence Details.Absent", 
				"doc_data.widget_data.page2.Attendence Details.RestRoom", 
			) )->whereLike ( 'history.last_stage.time', $today_date )->where(array('doc_data.widget_data.page1.Attendence Details.District'=>$dt_name))->get ( $this->absent_app_col );
			log_message('debug','get_all_absent_data====select======='.print_r($query,true));

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
				$query = $doc_query;
				log_message('debug','$school_name == "All"==========='.print_r($query,true));

				
			} else {
				foreach ( $query as $doc ) {
					
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				log_message('debug','individualschool============='.print_r($query,true));

			}
			log_message('debug','queryyyyyyyyyyyy============='.print_r($query,true));
			return $query;
			
		}
		
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: GET RHSO Submitted Forms Data ( as School Wise )
	 *
	 *@author Bhanu 
	 */
	
	

	public function sanitation_insp_count($school_name) {
		$count = $this->mongo_db->where(array('doc_data.School Information.school_name' => $school_name))->count ( 'healthcare20171226174552433' );
		return $count;
	}

	public function get_sanitation_inspection_report_model($school_name)
	{
		
		$sanitation_inspection = $this->mongo_db->where ( array (
			"doc_data.School Information.school_name" =>  $school_name  
		) )->get('healthcare20171226174552433');
		if($sanitation_inspection)
		{
			return $sanitation_inspection;
		}
		else
		{
			return FALSE;
		}
	}


	public function civil_infrastructure_count($school_name) {
		$count = $this->mongo_db->where(array('doc_data.School Information.school_name' => $school_name))->count ( 'healthcare20171227153054237' );
		return $count;
	}

	public function get_civil_and_infrastructure_report_model($school_name)
	{
		
		$civil_and_infrastructure = $this->mongo_db->where ( array (
			"doc_data.School Information.school_name" =>  $school_name  
		) )->get('healthcare20171227153054237');
		log_message('debug', 'get_civil_and_infrastructure_report====='.print_r($civil_and_infrastructure, true));
		if($civil_and_infrastructure)
		{
			return $civil_and_infrastructure;
		}
		else
		{
			return FALSE;
		}
	}

	public function health_inspector_inspection_count($school_name) {
		$count = $this->mongo_db->where(array('doc_data.School Information.school_name' => $school_name))->count ( 'healthcare20171227173441869' );
		return $count;
	}

	public function get_health_inspector_inspection_report_model($school_name)
	{
		
		$health_inspector_inspection = $this->mongo_db->where ( array (
			"doc_data.School Information.school_name" =>  $school_name  
		) )->get('healthcare20171227173441869');
		log_message('debug', 'get_health_inspector_inspection_report====='.print_r($health_inspector_inspection, true));
		if($health_inspector_inspection)
		{
			return $health_inspector_inspection;
		}
		else
		{
			return FALSE;
		}
	}

	public function food_hygiene_inspection_count($school_name) {
		$count = $this->mongo_db->where(array('doc_data.School Information.school_name' => $school_name))->count ( 'healthcare20171221112544749' );
		return $count;
	}

	public function get_food_hygiene_inspection_report_model($school_name)
	{
		
		$food_hygiene_inspection = $this->mongo_db->where ( array (
			"doc_data.School Information.school_name" =>  $school_name  
		) )->get('healthcare20171221112544749');
		log_message('debug', 'get_food_hygiene_inspection_report====='.print_r($food_hygiene_inspection, true));
		if($food_hygiene_inspection)
		{
			return $food_hygiene_inspection;
		}
		else
		{
			return FALSE;
		}
	}
	///19-09-2018 added
	
	public function get_school_info_by_school_name($school_name) 
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$school_data = $this->mongo_db->where('school_name',$school_name)->get('panacea_schools');
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		
		return $school_data;
	}
	
	public function insert_civil_infrastructure_report($doc_data,$attachments, $school_info, $doc_properties,$app_properties,$history)
	{

		$final_values = array("doc_data"=>array("widget_data"=>$doc_data, "external_attachments" =>$attachments, "School Information" => $school_info), "history"=>$history,"app_properties"=>$app_properties,"doc_properties"=>$doc_properties);

		$query = $this->mongo_db->insert('healthcare20171227153054237',$final_values);
	}

	public function insert_food_hygiene_report($doc_data,$attachments, $school_info, $doc_properties,$app_properties,$history)
	{

		$final_values = array("doc_data"=>array("widget_data"=>$doc_data, "external_attachments" =>$attachments, "School Information" => $school_info), "history"=>$history,"app_properties"=>$app_properties,"doc_properties"=>$doc_properties);

		$query = $this->mongo_db->insert('healthcare20171221112544749',$final_values);
	}
	public function insert_sanitation_inspection_model($doc_data,$attachments,$school_info,$doc_properties,$app_properties,$history)
	{

		$final_values = array("doc_data"=>array("widget_data"=>$doc_data, "external_attachments" =>$attachments, "School Information" => $school_info),"history"=>$history,"app_properties"=>$app_properties,"doc_properties"=>$doc_properties);
		$query = $this->mongo_db->insert('healthcare20171226174552433',$final_values);
	}

	public function insert_health_inspection_model($doc_data,$attachments,$school_info,$doc_properties,$app_properties,$history)
	{

		$final_values = array("doc_data"=>array("widget_data"=>$doc_data, "external_attachments" =>$attachments, "School Information" => $school_info),"history"=>$history,"app_properties"=>$app_properties,"doc_properties"=>$doc_properties);
		$query = $this->mongo_db->insert('healthcare20171227173441869',$final_values);
	}


	public function get_district_wise_absent_data($date, $dt_name, $school_name) {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		$query = $this->mongo_db->select ( array (
			"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->where(array('doc_data.widget_data.page1.Attendence Details.District'=>$dt_name))->get ( $this->absent_app_col );
		
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
			$query = $doc_query;
			
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

	private function get_request_docs($request_type,$status_type,$dt_name){
		
		if($status_type == "Cured"){
			
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $request_type);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");
			$district_wise = array ("doc_data.widget_data.page1.Student Info.District.field_ref" => $dt_name);
			//$date = array('history.0.time' => array('$regex' => "2018-05-*"));

			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $cured );
			array_push ( $and_merged_array, $district_wise );
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
				'aggregate' => 'healthcare2016531124515424_static_html',
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
			$district_wise = array ("doc_data.widget_data.page1.Student Info.District.field_ref" => $dt_name);
			$date = array('history.time' => array('$gte' => "2018-05-01"));
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $not_cured );
			array_push ( $and_merged_array, $district_wise );
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
				'aggregate' => 'healthcare2016531124515424_static_html',
				'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}

			return $query;
		}
	}
	public function update_chronic_request_pie($status_type,$dt_name) {
		
		$requests = [ ];
		
		$query = $this->get_request_docs('Chronic',$status_type,$dt_name);
		$request ['label'] = 'Chronic';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Deficiency',$status_type,$dt_name);
		$request ['label'] = 'Deficiency';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Defects',$status_type,$dt_name);
		$request ['label'] = 'Defects';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		return $requests;
		
	}
	public function get_chronic_request($dt_name) {
		
		$requests = [ ];
		
		$query = $this->get_request_docs('Chronic',"Not Cured",$dt_name);
		$request ['label'] = 'Chronic';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		/*$query = $this->get_request_docs('Deficiency',"Not Cured",$dt_name);
		$request ['label'] = 'Deficiency';
		$request ['value'] = count($query);
		array_push ( $requests, $request );
		
		$query = $this->get_request_docs('Defects',"Not Cured",$dt_name);
		$request ['label'] = 'Defects';
		$request ['value'] = count($query);
		array_push ( $requests, $request );*/
		
		return $requests;
		
	}
	public function drill_down_request_to_symptoms($data,$status_type,$dt_name){
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		
		$query = $this->get_request_docs($type,$status_type,$dt_name);
		
		$prob_arr = [ ];
		$all_chronic_cases = [ ];
		foreach ( $query as $doc ) {	

			if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Chronic'] )) {
				$problems_info = $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Chronic'];

				foreach ($problems_info as $key => $value) {
					
					$problems = $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Chronic'][$key];
					if(!empty($problems)){
						foreach ( $problems as $problem ) {
							if (isset ( $prob_arr [$problem] )) {
								$prob_arr [$problem] ++;
							} else {
								$prob_arr [$problem] = 1;
							}
						}
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
		return $final_values;
	}

	private function get_request_docs_params($search_param_1,$search_param_2, $status_type, $dt_name){

		$start_date = date("Y-m-d H:i:s");
		if($status_type == "Cured"){
			
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Chronic' => $search_param_2);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");
			$district_wise = array ("doc_data.widget_data.page1.Student Info.District.field_ref" => $dt_name);
			
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			//array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $cured );
			array_push ( $and_merged_array, $dt_name );
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
				'aggregate' => 'healthcare2016531124515424_static_html',
				'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
			
			return $query;
			
		}else{
			$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			//$symptoms = array ('doc_data.widget_data.page1.Problem Info.Chronic' => $search_param_2);
			$not_cured = array ("doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured"));
			$district_wise = array ("doc_data.widget_data.page1.Student Info.District.field_ref" => $dt_name);
			$date = array('history.time' => array('$gte' => "2018-05-01"));
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			//array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $not_cured );
			array_push ( $and_merged_array, $district_wise );
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
				'aggregate' => 'healthcare2016531124515424_static_html',
				'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}

			return $query;
		}
	}

/*public function drilldown_chronic_request_to_districts($data, $status_type,$dt_name) {
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
	//	$search_param_2 = $obj_data[1];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
	
		$dist_list = [ ];

		$query = $this->get_request_docs_params($search_param_1,$status_type, $dt_name);

		foreach ( $query as $identifiers ) {
			
			$retrieval_list = array ();
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
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
	}*/

	public function drilldown_chronic_request_to_schools($data, $status_type,$dt_name) {
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		
		$search_param_1 = $obj_data[0];
		$search_param_2 = $obj_data[1];

		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		
		$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type,$dt_name);
		$school_list = [ ];
		$matching_docs = [ ];

		$dist_to_lower = strtolower ( $obj_data [1] );
		
		foreach ( $query as $request ) {
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == strtolower($dt_name)) {
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
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 666666666666666666666666666666666666666");
		return $final_values;
	}

	public function drilldown_chronic_request_to_students($data,$status_type,$dt_name) {
		
		
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		//log_message("error","obj_data====17428".print_r($obj_data,true));
		$search_param_1 = $obj_data[0];
		
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		$search_param_2 = $obj_data[1];

		$school_name = $obj_data ['1'];
		$school_name = strtoupper($school_name);
			//log_message("error","school_name====17428".print_r($school_name,true));
		if(isset($school_name))
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$schools_list = $this->mongo_db->select(array('school_code'),array('_id'))->where('school_name',$school_name)->get ( $this->collections ['panacea_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			$school_code = $schools_list[0]['school_code'];

			if(isset($school_code) && !empty($school_code))
			{
				$this->mongo_db->switchDatabase($this->common_db ['common_db']);
				$get_hs_email = $this->mongo_db->select(array('email'))->where('school_code',$school_code)->get($this->collections ['panacea_health_supervisors']);
				$this->mongo_db->switchDatabase($this->common_db ['dsn']);
				$email = $get_hs_email[0]['email'];
				$dist_code = strtoupper(str_ireplace(".", "_",substr($email,0,strpos($email,"@")-2)));
			}
		}
		
		if(isset($dist_code) && !empty($dist_code))
		{
			$query = $this->get_request_docs_params_with_school($search_param_1,$search_param_2,$status_type,$dist_code);
		}
		else
		{
			$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type,$dt_name);
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

	private function get_request_docs_params_with_school($search_param_1, $search_param_2, $status_type,$dist_code){

		$start_date = date("Y-m-d H:i:s");
		
		if($status_type == "Cured"){
			$unique_id = array('doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $dist_code));
		//	$init_request = array ('doc_data.widget_data.page2.Review Info.Request Type' => $search_param_1);
			$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$cured = array ("doc_data.widget_data.page2.Review Info.Status" => "Cured");
			
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $unique_id );
			array_push ( $and_merged_array, $init_request );
			//array_push ( $and_merged_array, $symptoms );
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
				'aggregate' => "healthcare2016531124515424_static_html",
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
			//$symptoms = array ('doc_data.widget_data.page1.Problem Info.Identifier' => $search_param_2);
			$not_cured = array ("doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured"));
			$date = array('history.time' => array('$gte' => "2018-05-01"));
			$and_merged_array = array();
			
			array_push ( $and_merged_array, $unique_id );
			array_push ( $and_merged_array, $init_request );
			//array_push ( $and_merged_array, $symptoms );
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
				'aggregate' =>"healthcare2016531124515424_static_html",
				'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}

			return $query;
		}
	}
	public function create_rhso_users_xl_sheet($date, $data,$history) {
		
		$final_array = array();
		array_push($final_array, $data);
		$date_1 = new DateTime($date);
		$new_date = $date_1->format('Y-m-d');
		
		$query = $this->mongo_db->where(array("date"=>$new_date))->push('identity',$data)->update($this->rhso_users_xl_report);
		
		if($query == FALSE)
		{
			$data_2 = array('date' => $new_date, 
				'identity' => $final_array,
				'history' => $history
			);
			
			$query = $this->mongo_db->insert( $this->rhso_users_xl_report, $data_2 );
		}

		
		return $query;
	}
	function get_rhso_submitted_report_count($date_xl){

		$query = $this->mongo_db->where(array('date' => $date_xl))->get('rhso_users_xl_report');
		
		
		if($query)
		{
			return $query;
		}
		else {
			return FALSE;
		}
		
	}
	function get_rhso_submitted_report_date_school($schoolName, $todayDate)
	{
		$query = $this->mongo_db->where(array('identity.name of the school/district/region'=> $schoolName, 'date' =>$todayDate))->get('rhso_users_xl_report');
		
		if($query)
		{
			return $query;
		}
		else {
			return FALSE;
		}
		
	}

	public function create_rhso_xl_import($doc_data, $doc_properties, $history)
	{
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
		$this->mongo_db->insert('Rhso_health_inspection_Col', $form_data);
		exit();
	}

	// hb PIE REPORT
	public function get_hb_report_model($current_month, $school_name) {
		
		if ($current_month){
			$selected_month = $current_month;
			//$selected_month = "2018-10";
		}else {
			$selected_month = $this->selected_month;
			//$selected_month = "2018-10";
		}

		$requests = [ ];
		$requests_array = [ ];
		$requests_array_normal = [ ];
		$requests_array_moderate = [ ];
		$requests_array_mild = [ ];
		
		if($school_name == "All" )
		{
			$query = array('doc_data.widget_data.page1.Student Details.HB_latest.month' => array('$regex' => $selected_month));
		
			$documents = $this->mongo_db->select(array('doc_data.widget_data'))->where($query)->get($this->hb_app_col);
			
			foreach ($documents as $value) 
			{		
				$end_array = end($value['doc_data']['widget_data']['page1']['Student Details']['HB_values']);				
				
				if($end_array['hb'] <= 8.0)
				{
					array_push($requests_array, $value);
				}else if($end_array['hb'] >= 12.0 && $end_array['hb'] <= 18.0)
				{
					array_push($requests_array_normal, $value);			
				}else if($end_array['hb'] >= 8.1 && $end_array['hb'] <= 10.0)
				{
					array_push($requests_array_moderate, $value);
				}else if($end_array['hb'] >= 10.1 && $end_array['hb'] < 12.0)
				{
					array_push($requests_array_mild, $value);
				}

			}
			
			$request ['label'] = 'SEVERE';
			$request ['value'] = count($requests_array);
			array_push ( $requests, $request );
			$request ['label'] = 'NORMAL';
			$request ['value'] = count($requests_array_normal);
			array_push ( $requests, $request );
			$request ['label'] = 'MODERATE';
			$request ['value'] = count($requests_array_moderate);
			array_push ( $requests, $request );
			$request ['label'] = 'MILD';
			$request ['value'] = count($requests_array_mild);
			array_push ( $requests, $request );

			return $requests;
		
		}else if ( $school_name != "All" && $district_name != "select") 
		{
			
			$query = array('doc_data.widget_data.school_details.School Name' => $school_name,
				'doc_data.widget_data.school_details.District' => $district_name,
				'doc_data.widget_data.page1.Student Details.HB_values.month' => array('$regex' => $selected_month."-_*"),
				'doc_data.widget_data.page1.Student Details.Gender' => (!empty($student_type) && isset($student_type)) ? $student_type : array('$exists' => true),
				'doc_data.widget_data.page1.Student Details.Age' => (!empty($student_age) && $student_age != "select" && intval($student_age) != 0 ) ? intval($student_age) : array('$exists' => true));
			$documents = $this->mongo_db->select(array('doc_data.widget_data'))->where($query)->get($this->hb_app_col);
						
			foreach ($documents as $value) 
			{
				$end_array = end($value['doc_data']['widget_data']['page1']['Student Details']['HB_values']);				
				
				if($end_array['hb'] <= 8.0)
				{
					array_push($requests_array, $value);
				}else if($end_array['hb'] >= 12.0 && $end_array['hb'] <= 18.0)
				{
					array_push($requests_array_normal, $value);			
				}else if($end_array['hb'] >= 8.1 && $end_array['hb'] <= 10.0)
				{
					array_push($requests_array_moderate, $value);
				}else if($end_array['hb'] >= 10.1 && $end_array['hb'] < 12.0)
				{
					array_push($requests_array_mild, $value);
				}

			}
			$request ['label'] = 'SEVERE';
			$request ['value'] = count($requests_array);
			array_push ( $requests, $request );
			$request ['label'] = 'NORMAL';
			$request ['value'] = count($requests_array_normal);
			array_push ( $requests, $request );
			$request ['label'] = 'MODERATE';
			$request ['value'] = count($requests_array_moderate);
			array_push ( $requests, $request );
			$request ['label'] = 'MILD';
			$request ['value'] = count($requests_array_mild);
			array_push ( $requests, $request );
						
			return $requests;
							
		}else if ($school_name == "All") {
			if ($district_name != "select") {	

				$query = array('doc_data.widget_data.school_details.District' => $district_name,
				'doc_data.widget_data.page1.Student Details.HB_values.month' => array('$regex' => $selected_month),
				'doc_data.widget_data.page1.Student Details.Gender' => (!empty($student_type) && isset($student_type)) ? $student_type : array('$exists' => true),
				'doc_data.widget_data.page1.Student Details.Age' => (!empty($student_age) && $student_age != "select" && intval($student_age) != 0 ) ? intval($student_age) : array('$exists' => true));
			$documents = $this->mongo_db->select(array('doc_data.widget_data'))->where($query)->get($this->hb_app_col);
			
			foreach ($documents as $value) 
			{
				$end_array = end($value['doc_data']['widget_data']['page1']['Student Details']['HB_values']);				
				
				if($end_array['hb'] <= 8.0)
				{
					array_push($requests_array, $value);
				}else if($end_array['hb'] >= 12.0 && $end_array['hb'] <= 18.0)
				{
					array_push($requests_array_normal, $value);			
				}else if($end_array['hb'] >= 8.1 && $end_array['hb'] <= 10.0)
				{
					array_push($requests_array_moderate, $value);
				}else if($end_array['hb'] >= 10.1 && $end_array['hb'] < 12.0)
				{
					array_push($requests_array_mild, $value);
				}

			}
			$request ['label'] = 'SEVERE';
			$request ['value'] = count($requests_array);
			array_push ( $requests, $request );
			$request ['label'] = 'NORMAL';
			$request ['value'] = count($requests_array_normal);
			array_push ( $requests, $request );
			$request ['label'] = 'MODERATE';
			$request ['value'] = count($requests_array_moderate);
			array_push ( $requests, $request );
			$request ['label'] = 'MILD';
			$request ['value'] = count($requests_array_mild);
			array_push ( $requests, $request );
						
			return $requests;

				
			} 
		} 
		//echo print_r($requests,TRUE);exit();
		return $requests;
		
	}

}
