<?php
ini_set ( 'memory_limit',"2G");
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Rhso_common_model extends CI_Model {
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
		
		$this->screening_app_col = "healthcare2016226112942701";
		$this->screening_app_col_screening = "healthcare2016226112942701_screening_final";
		
		$this->absent_app_col = "healthcare201651317373988";
		$this->request_app_col = "healthcare2016531124515424";
		$this->sanitation_infrastructure_app_col = "";
		$this->sanitation_app_col = "healthcare2016111212310531";
		$this->sanitation_infra_app_col  = "healthcare20161114161842748";
		$this->notes_col = "panacea_ehr_notes";
		$this->today_date = date ( 'Y-m-d' );
	}
	/* 
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
	} */
	
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
				"school_code" => intval ($post ['school_code']),
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
		$count = $this->mongo_db->count ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_cc_users($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_cc'] );
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
		$query = $this->mongo_db->insert ( $this->collections ['panacea_cc'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function delete_cc_user($cc_id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				"_id" => new MongoId ( $cc_id ) 
		) )->delete ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	// ////////////////////////////////////////////////////////////
	public function doctorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_doctors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_doctors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_doctors'] );
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
	public function get_all_schools() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_schools'] );
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
	public function classescount() {
		$count = $this->mongo_db->count ( 'panacea_classes' );
		return $count;
	}
	public function get_classes($per_page, $page,$unique_id=false) {
		
		//$this->unique_id_check($unique_id);
		
		//=======================================================START OF HS
		/*
		$school_id = 'jskr.52024.hs#gmail.com,wnpy.61458.hs#gmail.com,nml.51927.hs#gmail.com';
		$schoolObj = explode(",",$school_id);
		foreach($schoolObj as $id)
		{
			//================================================================== medical evaluation-=======================================================
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : { "pages" : { "1" : { "Personal Information" : { "Name" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "true" }, "Mobile" : { "type" : "mobile", "minlength" : "10", "maxlength" : "10", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Hospital Unique ID" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Date of Birth" : { "type" : "date", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Photo" : { "type" : "photo", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "upload" : { "allowed_types" : "*", "encrypt_name" : "TRUE", "max_size" : "5120", "min_size" : "1024" } }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } } }, "2" : { "Personal Information" : { "AD No" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 10, "notify" : "false" }, "District" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 11, "notify" : "false" }, "School Name" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 12, "notify" : "false" }, "Class" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 13, "notify" : "false" }, "Section" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 14, "notify" : "false" }, "Father Name" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 15, "notify" : "false" }, "Date of Exam" : { "type" : "date", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 16, "notify" : "false" }, "newline17" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 17 }, "newline18" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 18 }, "newline19" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 19 }, "newline20" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 20 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } } }, "3" : { "Physical Exam" : { "Height cms" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false" }, "Weight kgs" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "BMI%" : { "type" : "number", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Pulse" : { "type" : "number", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "B P" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "Blood Group" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 6, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "A+", "value" : "A+" }, { "label" : "A-", "value" : "A-" }, { "label" : "A1-", "value" : "A1-" }, { "label" : "A1+", "value" : "A1+" }, { "label" : "A1B-", "value" : "A1B-" }, { "label" : "A1B+", "value" : "A1B+" }, { "label" : "A2-", "value" : "A2-" }, { "label" : "A2+", "value" : "A2+" }, { "label" : "A2B-", "value" : "A2B-" }, { "label" : "A2B+", "value" : "A2B+" }, { "label" : "AB+", "value" : "AB+" }, { "label" : "AB-", "value" : "AB-" }, { "label" : "B-", "value" : "B-" }, { "label" : "B+", "value" : "B+" }, { "label" : "B1+", "value" : "B1+" }, { "label" : "O-", "value" : "O-" }, { "label" : "O+", "value" : "O+" } ] }, "H B" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7, "notify" : "false" }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "newline10" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 10 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2 } } }, "4" : { "Doctor Check Up" : { "Check the box if normal else describe abnormalities" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Neurologic", "value" : "Neurologic" }, { "label" : "H and N", "value" : "H and N" }, { "label" : "ENT", "value" : "ENT" }, { "label" : "Lymphatic", "value" : "Lymphatic" }, { "label" : "Heart", "value" : "Heart" }, { "label" : "Lungs", "value" : "Lungs" }, { "label" : "Abdomen", "value" : "Abdomen" }, { "label" : "Genitalia", "value" : "Genitalia" }, { "label" : "Skin", "value" : "Skin" } ] }, "Ortho" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Neck", "value" : "Neck" }, { "label" : "Shoulders", "value" : "Shoulders" }, { "label" : "Arms/Hands", "value" : "Arms/Hands" }, { "label" : "Hips", "value" : "Hips" }, { "label" : "Knees", "value" : "Knees" }, { "label" : "Feet", "value" : "Feet" } ] }, "Postural" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "No spinal Abnormality", "value" : "No spinal Abnormality" }, { "label" : "Spinal Abnormality", "value" : "Spinal Abnormality" }, { "label" : "Mild", "value" : "Mild" }, { "label" : "Marked", "value" : "Marked" }, { "label" : "Moderate", "value" : "Moderate" }, { "label" : "Referral Made", "value" : "Referral Made" } ] }, "Description" : { "type" : "textarea", "minlength" : "0", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Advice" : { "type" : "textarea", "minlength" : "0", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } } }, "5" : { "Doctor Check Up" : { "Defects at Birth" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 10, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Neural Tube Defect", "value" : "Neural Tube Defect" }, { "label" : "Down Syndrome", "value" : "Down Syndrome" }, { "label" : "Cleft Lip and Palate", "value" : "Cleft Lip and Palate" }, { "label" : "Talipes Club foot", "value" : "Talipes Club foot" }, { "label" : "Developmental Dysplasia of Hip", "value" : "Developmental Dysplasia of Hip" }, { "label" : "Congenital Cataract", "value" : "Congenital Cataract" }, { "label" : "Congenital Deafness", "value" : "Congenital Deafness" }, { "label" : "Congenital Heart Disease", "value" : "Congenital Heart Disease" }, { "label" : "Retinopathy of Prematurity", "value" : "Retinopathy of Prematurity" } ] }, "Deficencies" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 11, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Anaemia", "value" : "Anaemia" }, { "label" : "Vitamin Deficiency - Bcomplex", "value" : "Vitamin Deficiency - Bcomplex" }, { "label" : "Vitamin A Deficiency", "value" : "Vitamin A Deficiency" }, { "label" : "Vitamin D Deficiency", "value" : "Vitamin D Deficiency" }, { "label" : "SAM/stunting", "value" : "SAM/stunting" }, { "label" : "Goiter", "value" : "Goiter" }, { "label" : "Under Weight", "value" : "Under Weight" }, { "label" : "Normal Weight", "value" : "Normal Weight" }, { "label" : "Over Weight", "value" : "Over Weight" }, { "label" : "Obese", "value" : "Obese" } ] }, "Childhood Diseases" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 12, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Skin Conditions", "value" : "Skin Conditions" }, { "label" : "Otitis Media", "value" : "Otitis Media" }, { "label" : "Rheumatic Heart Disease", "value" : "Rheumatic Heart Disease" }, { "label" : "Asthma", "value" : "Asthma" }, { "label" : "Convulsive Disorders", "value" : "Convulsive Disorders" }, { "label" : "Hypothyroidism", "value" : "Hypothyroidism" }, { "label" : "Diabetes", "value" : "Diabetes" }, { "label" : "Epilepsy", "value" : "Epilepsy" } ] }, "N A D" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 13, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" } ] }, "Doctor Name and Reg No" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 14 }, "newline15" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 15 }, "newline16" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 16 }, "newline17" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 17 }, "newline18" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 18 }, "newline19" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 19 }, "newline20" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 20 }, "newline21" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 21 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } } }, "6" : { "Screenings" : { "Detected Myopia Hypermetropia" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "Vision Screening", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4 } }, "Without Glasses" : { "Right" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false" }, "Left" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5 } }, "With Glasses" : { "Right" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false" }, "Left" : { "type" : "text", "minlength" : "0", "maxlength" : "60", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "newline3" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 3 }, "newline4" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 4 }, "newline5" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 5 }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6 } } }, "7" : { "Colour Blindness" : { "Right" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Left" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Description" : { "type" : "textarea", "minlength" : "0", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Referral Made" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 4, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" } ] }, "Docter Name and Reg No" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 5 }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "newline10" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 10 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7 } } }, "8" : { " Auditory Screening" : { "Right" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Pass", "value" : "Pass" }, { "label" : "Fail", "value" : "Fail" } ] }, "Left" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Pass", "value" : "Pass" }, { "label" : "Fail", "value" : "Fail" } ] }, "Speech Screening" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Normal", "value" : "Normal" }, { "label" : "Delay", "value" : "Delay" }, { "label" : "Misarticulation", "value" : "Misarticulation" }, { "label" : "Fluency", "value" : "Fluency" }, { "label" : "Voice", "value" : "Voice" } ] }, "D D and disability" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 4, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Language Delay", "value" : "Language Delay" }, { "label" : "Behaviour Disorder", "value" : "Behaviour Disorder" } ] }, "Description" : { "type" : "textarea", "minlength" : "0", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "Referral Made" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 6, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" } ] }, "Doctor Name and Reg No" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "newline10" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 10 }, "newline11" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 11 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 8 } } }, "9" : { "Dental Check-up" : { "Oral Hygiene" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Good", "value" : "Good" }, { "label" : "Fair", "value" : "Fair" }, { "label" : "Poor", "value" : "Poor" } ] }, "Carious Teeth" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Flourosis" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Orthodontic Treatment" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 4, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Indication for extraction" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 5, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Result" : { "type" : "textarea", "minlength" : "0", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6, "notify" : "false" }, "Referral Made" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 7, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" } ] }, "Doctor Name and Reg No" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 8 }, "newline9" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 9 }, "newline10" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 10 }, "newline11" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 11 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 9 } } } }, "permissions" : { "Stage Name1" : { "View_Permissions" : [ "Personal Information", "Physical Exam", "Doctor Check Up", "Screenings", "Without Glasses", "With Glasses", "Colour Blindness", " Auditory Screening", "Dental Check-up" ], "Edit_Permissions" : [ "Personal Information", "Physical Exam", "Doctor Check Up", "Screenings", "Without Glasses", "With Glasses", "Colour Blindness", " Auditory Screening", "Dental Check-up" ], "index" : 1 } }, "notification_parameters" : [ { "field" : "Name", "page" : "1", "section" : "Personal Information" } ], "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } } }, "app_id" : "healthcare2016226112942701", "app_description" : "for tswreis edit", "status" : "processed", "app_name" : "Medical Evaluation", "app_created" : "2016-10-05 13:46:08", "app_expiry" : "2018-01-25", "_version" : 2, "stages" : [ "Stage Name1" ], "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare2016226112942701", "app_description" : "for tswreis edit", "app_name" : "Medical Evaluation", "app_created" : "2016-10-05 13:46:08" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			//====================================================================================================================================
			
			//==================================================================== Attendence app ===============================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : { "pages" : { "1" : { "Attendence Details" : { "District" : { "type" : "select", "size" : "1", "required" : "TRUE", "key" : "TRUE", "description" : "", "option_choose_one" : "TRUE", "with_translations" : "FALSE", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [  {  "text" : "ADILABAD",  "selected" : "TRUE",  "value" : "ADILABAD"  },  {  "text" : "BHADRADRI",  "selected" : "FALSE",  "value" : "BHADRADRI"  },  {  "text" : "GADWAL",  "selected" : "FALSE",  "value" : "GADWAL"  },  {  "text" : "HYDERABAD",  "selected" : "FALSE",  "value" : "HYDERABAD"  },  {  "text" : "JAGTIAL",  "selected" : "FALSE",  "value" : "JAGTIAL"  },  {  "text" : "JANGAON",  "selected" : "FALSE",  "value" : "JANGAON"  },  {  "text" : "JAYASHANKAR",  "selected" : "FALSE",  "value" : "JAYASHANKAR"  },  {  "text" : "KAMAREDDY",  "selected" : "FALSE",  "value" : "KAMAREDDY"  },   {  "text" : "KARIMNAGAR",  "selected" : "FALSE",  "value" : "KARIMNAGAR"  },  {  "text" : "KHAMMAM",  "selected" : "FALSE",  "value" : "KHAMMAM"  },  {  "text" : "KOMARAM BHEEM",  "selected" : "FALSE",  "value" : "KOMARAM BHEEM"  },  {  "text" : "MAHABUBABAD",  "selected" : "FALSE",  "value" : "MAHABUBABAD"  },  {  "text" : "MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "MAHABUBNAGAR"  },  {  "text" : "MANCHERIAL",  "selected" : "FALSE",  "value" : "MANCHERIAL"  },  {  "text" : "MEDAK",  "selected" : "FALSE",  "value" : "MEDAK"  },  {  "text" : "MEDCHAL",  "selected" : "FALSE",  "value" : "MEDCHAL"  },  {  "text" : "NAGARKURNOOL",  "selected" : "FALSE",  "value" : "NAGARKURNOOL"  },   {  "text" : "NALGONDA",  "selected" : "FALSE",  "value" : "NALGONDA"  },  {  "text" : "NIRMAL",  "selected" : "FALSE",  "value" : "NIRMAL"  },  {  "text" : "NIZAMABAD",  "selected" : "FALSE",  "value" : "NIZAMABAD"  },  {  "text" : "PEDDAPALLI",  "selected" : "FALSE",  "value" : "PEDDAPALLI"  },  {  "text" : "RAJANNA SIRCILLA",  "selected" : "FALSE",  "value" : "RAJANNA SIRCILLA"  },  {  "text" : "RANGAREDDY",  "selected" : "FALSE",  "value" : "RANGAREDDY"  },  {  "text" : "SANGAREDDY",  "selected" : "FALSE",  "value" : "SANGAREDDY"  },  {  "text" : "SIDDIPET",  "selected" : "FALSE",  "value" : "SIDDIPET"  },  {  "text" : "SIDDIPET",  "selected" : "FALSE",  "value" : "SIDDIPET"  },  {  "text" : "SURYAPET",  "selected" : "FALSE",  "value" : "SURYAPET"  },  {  "text" : "VIKARABAD",  "selected" : "FALSE",  "value" : "VIKARABAD"  },  {  "text" : "WANAPARTHY",  "selected" : "FALSE",  "value" : "WANAPARTHY"  },  {  "text" : "WARANGAL RURAL",  "selected" : "FALSE",  "value" : "WARANGAL RURAL"  },  {  "text" : "WARANGAL URBAN",  "selected" : "FALSE",  "value" : "WARANGAL URBAN"  },  {  "text" : "YADADRI",  "selected" : "FALSE",  "value" : "YADADRI"  }  ] }, "Select School" : { "type" : "select", "size" : "1", "required" : "TRUE", "key" : "TRUE", "description" : "", "option_choose_one" : "TRUE", "with_translations" : "FALSE", "order" : 2, "multilanguage" : "FALSE", "notify" : "true", "options" : [  {  "text" : "TSWREIS ADILABAD(G),ADILABAD",  "selected" : "TRUE",  "value" : "TSWREIS ADILABAD(G),ADILABAD"  },  {  "text" : "TSWREIS BOATH(G),ADILABAD",  "selected" : "FALSE",  "value" : "TSWREIS BOATH(G),ADILABAD"  },  {  "text" : "TSWREIS ICHODA(B),ADILABAD",  "selected" : "FALSE",  "value" : "TSWREIS ICHODA(B),ADILABAD"  },  {  "text" : "TSWRDCW ADILABAD(G),ADILABAD",  "selected" : "FALSE",  "value" : "TSWRDCW ADILABAD(G),ADILABAD"  },  {  "text" : "TSWREIS ANNAPUREDDYPALLI(B),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS ANNAPUREDDYPALLI(B),BHADRADRI"  },  {  "text" : "TSWREIS BHADRACHALAM(G),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS BHADRACHALAM(G),BHADRADRI"  },  {  "text" : "TSWREIS DAMMAPET(B),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS DAMMAPET(B),BHADRADRI"  },  {  "text" : "TSWREIS KOTHAGUDEM(B),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS KOTHAGUDEM(B),BHADRADRI"  },  {  "text" : "TSWREIS MANUGURU(B),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS MANUGURU(B),BHADRADRI"  },  {  "text" : "TSWREIS MULKALAPALLY(G),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS MULKALAPALLY(G),BHADRADRI"  },  {  "text" : "TSWREIS PALAVANCHA(G),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS PALAVANCHA(G),BHADRADRI"  },  {  "text" : "TSWREIS YELLANDU(G),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWREIS YELLANDU(G),BHADRADRI"  },  {  "text" : "TSWRDCW KOTHAGUDEM(G),BHADRADRI",  "selected" : "FALSE",  "value" : "TSWRDCW KOTHAGUDEM(G),BHADRADRI"  },  {  "text" : "TSWREIS ALAMPUR(G),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS ALAMPUR(G),GADWAL"  },  {  "text" : "TSWREIS ALAMPUR(B),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS ALAMPUR(B),GADWAL"  },  {  "text" : "TSWREIS GADWAL(B),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS GADWAL(B),GADWAL"  },  {  "text" : "TSWREIS GHATTU(G),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS GHATTU(G),GADWAL"  },  {  "text" : "TSWREIS IEEJA(B),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS IEEJA(B),GADWAL"  },  {  "text" : "TSWREIS MANOPAD(G),GADWAL",  "selected" : "FALSE",  "value" : "TSWREIS MANOPAD(G),GADWAL"  },  {  "text" : "TSWRDCW LBNAGAR(G),HYDERABAD",  "selected" : "FALSE",  "value" : "TSWRDCW LBNAGAR(G),HYDERABAD"  },  {  "text" : "TSWREIS MAHENDRAHILLS(G),HYDERABAD",  "selected" : "FALSE",  "value" : "TSWREIS MAHENDRAHILLS(G),HYDERABAD"  },  {  "text" : "TSWREIS MOOSARAMBAGH(G),HYDERABAD",  "selected" : "FALSE",  "value" : "TSWREIS MOOSARAMBAGH(G),HYDERABAD"  },  {  "text" : "TSWREIS SHAIKPET(B),HYDERABAD",  "selected" : "FALSE",  "value" : "TSWREIS SHAIKPET(B),HYDERABAD"  },    {  "text" : "TSWREIS GOLLAPALLE(B),JAGTIAL",  "selected" : "FALSE",  "value" : "TSWREIS GOLLAPALLE(B),JAGTIAL"  },  {  "text" : "TSWREIS JAGTIAL(G),JAGTIAL",  "selected" : "FALSE",  "value" : "TSWREIS JAGTIAL(G),JAGTIAL"  },  {  "text" : "TSWREIS KORATLA(B),JAGTIAL",  "selected" : "FALSE",  "value" : "TSWREIS KORATLA(B),JAGTIAL"  },  {  "text" : "TSWREIS MAIDPALLY(B),JAGTIAL",  "selected" : "FALSE",  "value" : "TSWREIS MAIDPALLY(B),JAGTIAL"  },  {  "text" : "TSWREIS METPALLE(G),JAGTIAL",  "selected" : "FALSE",  "value" : "TSWREIS METPALLE(G),JAGTIAL"  },  {  "text" : "TSWREIS GHANPUR(B),JANGAON",  "selected" : "FALSE",  "value" : "TSWREIS GHANPUR(B),JANGAON"  },  {  "text" : "TSWREIS JANGAON(B),JANGAON",  "selected" : "FALSE",  "value" : "TSWREIS JANGAON(B),JANGAON"  },  {  "text" : "TSWREIS NARMETTA(G),JANGAON",  "selected" : "FALSE",  "value" : "TSWREIS NARMETTA(G),JANGAON"  },  {  "text" : "TSWREIS PALAKURTHI(G),JANGAON",  "selected" : "FALSE",  "value" : "TSWREIS PALAKURTHI(G),JANGAON"  },  {  "text" : "TSWREIS ZAFFERGADH(G),JANGAON",  "selected" : "FALSE",  "value" : "TSWREIS ZAFFERGADH(G),JANGAON"  },  {  "text" : "TSWREIS BHUPALAPALLI(G),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS BHUPALAPALLI(G),JAYASHANKAR"  },  {  "text" : "TSWREIS CHITYAL(G),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS CHITYAL(G),JAYASHANKAR"  },  {  "text" : "TSWREIS ETURNAGARAM(B),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS ETURNAGARAM(B),JAYASHANKAR"  },  {  "text" : "TSWREIS JAKARAM(B),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS JAKARAM(B),JAYASHANKAR"  },  {  "text" : "TSWREIS KATARAM(G),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS KATARAM(G),JAYASHANKAR"  },  {  "text" : "TSWREIS MULUG(G),JAYASHANKAR",  "selected" : "FALSE",  "value" : "TSWREIS MULUG(G),JAYASHANKAR"  },  {  "text" : "TSWREIS BANSWADA(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS BANSWADA(G),KAMAREDDY"  },  {  "text" : "TSWREIS BHIKNOOR(B),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS BHIKNOOR(B),KAMAREDDY"  },  {  "text" : "TSWREIS BICHKUNDA(B),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS BICHKUNDA(B),KAMAREDDY"  },  {  "text" : "TSWREIS DOMAKONDA(B),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS DOMAKONDA(B),KAMAREDDY"  },  {  "text" : "TSWREIS EKLARA BIG(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS EKLARA BIG(G),KAMAREDDY"  },  {  "text" : "TSWREIS LINGAMPET(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS LINGAMPET(G),KAMAREDDY"  },  {  "text" : "TSWREIS KAULASNALA(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS KAULASNALA(G),KAMAREDDY"  },  {  "text" : "TSWREIS TADKOL(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS TADKOL(G),KAMAREDDY"  },   {  "text" : "TSWREIS TADWAI(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS TADWAI(G),KAMAREDDY"  },   {  "text" : "TSWREIS UPPALAWAI(B),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS UPPALAWAI(B),KAMAREDDY"  },  {  "text" : "TSWREIS YELLAREDDY(B),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS YELLAREDDY(B),KAMAREDDY"  },  {  "text" : "TSWRDCW KAMAREDDY(G),KAMAREDDY",  "selected" : "FALSE",  "value" : "TSWRDCW KAMAREDDY(G),KAMAREDDY"  },  {  "text" : "TSWREIS CHINTALAKUNTA(G),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS CHINTALAKUNTA(G),KARIMNAGAR"  },  {  "text" : "TSWREIS CHOPPADANDI(G),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS CHOPPADANDI(G),KARIMNAGAR"  },  {  "text" : "TSWREIS COE KARIMNAGAR(G),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS COE KARIMNAGAR(G),KARIMNAGAR"  },  {  "text" : "TSWREIS HUZURABAD(G),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS HUZURABAD(G),KARIMNAGAR"  },  {  "text" : "TSWREIS JAMMIKUNTA(B),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS JAMMIKUNTA(B),KARIMNAGAR"  },  {  "text" : "TSWREIS MANAKONDURU(B),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS MANAKONDURU(B),KARIMNAGAR"  },  {  "text" : "TSWREIS PEMBATLA(B),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS PEMBATLA(B),KARIMNAGAR"  },  {  "text" : "TSWRDCW KARIMNAGAR(G),KARIMNAGAR",  "selected" : "FALSE",  "value" : "TSWRDCW KARIMNAGAR(G),KARIMNAGAR"  },  {  "text" : "TSWREIS ADAVIMALLELA(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS ADAVIMALLELA(G),KHAMMAM"  },  {  "text" : "TSWREIS DANAVAIGUDEM(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS DANAVAIGUDEM(G),KHAMMAM"  },   {  "text" : "TSWREIS KALLUR(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS KALLUR(G),KHAMMAM"  },  {  "text" : "TSWRDCW KHAMMAM(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWRDCW KHAMMAM(G),KHAMMAM"  },  {  "text" : "TSWREIS KHAMMAM JC(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS KHAMMAM JC(G),KHAMMAM"  },  {  "text" : "TSWREIS KUSUMANCHI(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS KUSUMANCHI(G),KHAMMAM"  },  {  "text" : "TSWREIS MADHIRA(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS MADHIRA(G),KHAMMAM"  },  {  "text" : "TSWREIS MUDIGONDA(B),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS MUDIGONDA(B),KHAMMAM"  },  {  "text" : "TSWREIS NELAKONDAPALLY(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS NELAKONDAPALLY(G),KHAMMAM"  },  {  "text" : "TSWREIS SATHUPALLI(B),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS SATHUPALLI(B),KHAMMAM"  },  {  "text" : "TSWREIS TEKULAPALLY(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS TEKULAPALLY(G),KHAMMAM"  },  {  "text" : "TSWREIS THIRUMALAYAPALEM(B),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS THIRUMALAYAPALEM(B),KHAMMAM"  },  {  "text" : "TSWREIS WYRA(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS WYRA(G),KHAMMAM"  },  {  "text" : "TSWREIS YERRUPALEM(G),KHAMMAM",  "selected" : "FALSE",  "value" : "TSWREIS YERRUPALEM(G),KHAMMAM"  },  {  "text" : "TSWREIS ASIFABAD(B),KOMARAMBHEEM",  "selected" : "FALSE",  "value" : "TSWREIS ASIFABAD(B),KOMARAMBHEEM"  },  {  "text" : "TSWREIS KAGAZNAGAR(G),KOMARAMBHEEM",  "selected" : "FALSE",  "value" : "TSWREIS KAGAZNAGAR(G),KOMARAMBHEEM"  },  {  "text" : "TTSWREIS REBBANA(G),KOMARAMBHEEM",  "selected" : "FALSE",  "value" : "TTSWREIS REBBANA(G),KOMARAMBHEEM"  },   {  "text" : "TSWREIS SIRPUR(G),KOMARAMBHEEM",  "selected" : "FALSE",  "value" : "TSWREIS SIRPUR(G),KOMARAMBHEEM"  },  {  "text" : "TSWREIS SIRPUR(B),KOMARAMBHEEM",  "selected" : "FALSE",  "value" : "TSWREIS SIRPUR(B),KOMARAMBHEEM"  },  {  "text" : "TSWREIS KESAMUDRAM(G),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS KESAMUDRAM(G),MAHABUBABAD"  },  {  "text" : "TSWREIS MAHABUBABAD(G),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS MAHABUBABAD(G),MAHABUBABAD"  },  {  "text" : "TSWREIS MARIPEDA(B),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS MARIPEDA(B),MAHABUBABAD"  },  {  "text" : "TSWREIS NARSIMHULAPETA(G),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS NARSIMHULAPETA(G),MAHABUBABAD"  },  {  "text" : "TSWREIS TORRUR(G),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS TORRUR(G),MAHABUBABAD"  },  {  "text" : "TSWREIS BALANAGAR(B),MAHABUBABAD",  "selected" : "FALSE",  "value" : "TSWREIS BALANAGAR(B),MAHABUBABAD"  },    {  "text" : "TSWREIS DAMARAGIDDA(B),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS DAMARAGIDDA(B),MAHABUBNAGAR"  },  {  "text" : "TSWREIS DEVARAKADRA(B),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS DEVARAKADRA(B),MAHABUBNAGAR"  },   {  "text" : "TSWREIS JADCHERLA JC(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS JADCHERLA JC(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS MADDUR(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS MADDUR(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS MAKTHAL(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS MAKTHAL(G),MAHABUBNAGAR"  }, {  "text" : "TSWREIS MARICAL(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS MARICAL(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS NAZEERABAD(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS NAZEERABAD(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS NARAYANPET(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS NARAYANPET(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS RAMIREDDY GUDEM(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS RAMIREDDY GUDEM(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS UTKOOR(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWREIS UTKOOR(G),MAHABUBNAGAR"  },  {  "text" : "TSWRDCW MAHABUBNAGAR(G),MAHABUBNAGAR",  "selected" : "FALSE",  "value" : "TSWRDCW MAHABUBNAGAR(G),MAHABUBNAGAR"  },  {  "text" : "TSWREIS BELLAMPALLI(G),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS BELLAMPALLI(G),MANCHERIAL"  },  {  "text" : "TSWREIS BELLAMPALLI(B),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS BELLAMPALLI(B),MANCHERIAL"  },  {  "text" : "TSWREIS CHENNUR(G),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS CHENNUR(G),MANCHERIAL"  },  {  "text" : "TSWREIS CHENNUR(B),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS CHENNUR(B),MANCHERIAL"  },  {  "text" : "TSWREIS KASIPET(B),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS KASIPET(B),MANCHERIAL"  },  {  "text" : "TSWREIS KOTAPALLI(B),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS KOTAPALLI(B),MANCHERIAL"  },  {  "text" : "TSWREIS LUXETTIPET(G),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS LUXETTIPET(G),MANCHERIAL"  },  {  "text" : "TSWREIS MANCHERIAL(B),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS MANCHERIAL(B),MANCHERIAL"  },  {  "text" : "TSWREIS MANDAMARRI(G),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWREIS MANDAMARRI(G),MANCHERIAL"  },    {  "text" : "TSWRDCW MANCHERIAL(G),MANCHERIAL",  "selected" : "FALSE",  "value" : "TSWRDCW MANCHERIAL(G),MANCHERIAL"  },   {  "text" : "TSWREIS TOOPRAN(G),MEDAK",  "selected" : "FALSE",  "value" : "TSWREIS TOOPRAN(G),MEDAK"  },  {  "text" : "TSWREIS KULCHARAM(G),MEDAK",  "selected" : "FALSE",  "value" : "TSWREIS KULCHARAM(G),MEDAK"  },  {  "text" : "TSWREIS MEDAK(G),MEDAK",  "selected" : "FALSE",  "value" : "TSWREIS MEDAK(G),MEDAK"  },  {  "text" : "TSWREIS RAMAYAMPET(G),MEDAK",  "selected" : "FALSE",  "value" : "TSWREIS RAMAYAMPET(G),MEDAK"  },  {  "text" : "TSWRDCW MEDAK(G),MEDAK",  "selected" : "FALSE",  "value" : "TSWRDCW MEDAK(G),MEDAK"  },  {  "text" : "TSWREIS MALKAJGIRI(G),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWREIS MALKAJGIRI(G),MEDCHAL"  },  {  "text" : "TSWREIS MEDCHAL(G),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWREIS MEDCHAL(G),MEDCHAL"  },  {  "text" : "TSWREIS JAGADGIRIGUTTA(G),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWREIS JAGADGIRIGUTTA(G),MEDCHAL"  },  {  "text" : "TSWREIS SHAMIRPET(B),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWREIS SHAMIRPET(B),MEDCHAL"  },    {  "text" : "TSWREIS UPPAL(B),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWREIS UPPAL(B),MEDCHAL"  },  {  "text" : "TSWRDCW JAGADGIRI GUTTA(G),MEDCHAL",  "selected" : "FALSE",  "value" : "TSWRDCW JAGADGIRI GUTTA(G),MEDCHAL"  },  {  "text" : "TSWREIS ACHAMPETA(B),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS ACHAMPETA(B),NAGARKURNOOL"  },  {  "text" : "TSWREIS BIJINAPALLE(B),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS BIJINAPALLE(B),NAGARKURNOOL"  },  {  "text" : "TSWREIS JAYAPRAKASHNAGAR(B),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS JAYAPRAKASHNAGAR(B),NAGARKURNOOL"  },  {  "text" : "TSWREIS KOLLAPUR(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS KOLLAPUR(G),NAGARKURNOOL"  },  {  "text" : "TSWREIS LINGAL(B),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS LINGAL(B),NAGARKURNOOL"  },  {  "text" : "TSWREIS MANNANUR(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS MANNANUR(G),NAGARKURNOOL"  },    {  "text" : "TSWREIS PEDDAKOTHAPALLE(B),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS PEDDAKOTHAPALLE(B),NAGARKURNOOL"  },   {  "text" : "TSWREIS TELKAPALLI(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS TELKAPALLI(G),NAGARKURNOOL"  },  {  "text" : "TSWREIS VELDANDA(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS VELDANDA(G),NAGARKURNOOL"  },  {  "text" : "TSWREIS VANGOOR(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWREIS VANGOOR(G),NAGARKURNOOL"  },  {  "text" : "TSWRDCW NAGARKURNOOL(G),NAGARKURNOOL",  "selected" : "FALSE",  "value" : "TSWRDCW NAGARKURNOOL(G),NAGARKURNOOL"  },  {  "text" : "TSWREIS ANUMULA(B),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS ANUMULA(B),NALGONDA"  },  {  "text" : "TSWREIS CHANDUR(B),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS CHANDUR(B),NALGONDA"  },  {  "text" : "TSWREIS DEVARAKONDA(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS DEVARAKONDA(G),NALGONDA"  },  {  "text" : "TSWREIS G.V.GUDEM(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS G.V.GUDEM(G),NALGONDA"  },  {  "text" : "TSWREIS GUNDLAPALLI(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS GUNDLAPALLI(G),NALGONDA"  },  {  "text" : "TSWREIS KATANGUR(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS KATANGUR(G),NALGONDA"  },  {  "text" : "TSWREIS MIRYALAGUDA(B),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS MIRYALAGUDA(B),NALGONDA"  },  {  "text" : "TSWREIS NAKREKAL(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS NAKREKAL(G),NALGONDA"  },  {  "text" : "TSWREIS NAKIREKAL(B),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS NAKIREKAL(B),NALGONDA"  },  {  "text" : "TSWREIS NIDAMANOOR(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS NIDAMANOOR(G),NALGONDA"  },  {  "text" : "TSWREIS THIPPARTHI(B),NALGONDA",  "selected" : "FALSE",  "value" : "TSWREIS THIPPARTHI(B),NALGONDA"  },  {  "text" : "TSWRDCW NALGONDA(G),NALGONDA",  "selected" : "FALSE",  "value" : "TSWRDCW NALGONDA(G),NALGONDA"  },  {  "text" : "TSWREIS BHAINSA(G),NIRMAL",  "selected" : "FALSE",  "value" : "TSWREIS BHAINSA(G),NIRMAL"  },  {  "text" : "TSWREIS JAM(G),NIRMAL",  "selected" : "FALSE",  "value" : "TSWREIS JAM(G),NIRMAL"  },  {  "text" : "TSWREIS KADDAM(G),NIRMAL",  "selected" : "FALSE",  "value" : "TSWREIS KADDAM(G),NIRMAL"  },  {  "text" : "TSWREIS MUDHOLE(B),NIRMAL",  "selected" : "FALSE",  "value" : "TSWREIS MUDHOLE(B),NIRMAL"  },    {  "text" : "TSWREIS NIRMAL(G),NIRMAL",  "selected" : "FALSE",  "value" : "TSWREIS NIRMAL(G),NIRMAL"  },  {  "text" : "TSWRDCW NIRMAL(G),NIRMAL",  "selected" : "FALSE",  "value" : "TSWRDCW NIRMAL(G),NIRMAL"  },   {  "text" : "TSWREIS ARMOOR(B),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS ARMOOR(B),NIZAMABAD"  },  {  "text" : "TSWREIS ARMOOR(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS ARMOOR(G),NIZAMABAD"  },  {  "text" : "TSWREIS BODHAN(B),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS BODHAN(B),NIZAMABAD"  },    {  "text" : "TSWREIS DHARMARAM(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS DHARMARAM(G),NIZAMABAD"  },  {  "text" : "TSWREIS NAVIPET(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS NAVIPET(G),NIZAMABAD"  },  {  "text" : "TSWREIS NIZAMABAD(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS NIZAMABAD(G),NIZAMABAD"  },    {  "text" : "TSWREIS POCHAMPADU(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS POCHAMPADU(G),NIZAMABAD"  },  {  "text" : "TSWREIS SUDDAPALLI(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS SUDDAPALLI(G),NIZAMABAD"  },  {  "text" : "TSWREIS VELPUR(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWREIS VELPUR(G),NIZAMABAD"  },  {  "text" : "TSWRDCW ARMOOR(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWRDCW ARMOOR(G),NIZAMABAD"  },  {  "text" : "TSWRDCW NIZAMABAD(G),NIZAMABAD",  "selected" : "FALSE",  "value" : "TSWRDCW NIZAMABAD(G),NIZAMABAD"  },  {  "text" : "TSWREIS MAHADEVPUR(G),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS MAHADEVPUR(G),PEDDAPALLI"  },    {  "text" : "TSWREIS MALLAPUR(G),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS MALLAPUR(G),PEDDAPALLI"  },  {  "text" : "TSWREIS MANTHANI(B),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS MANTHANI(B),PEDDAPALLI"  },  {  "text" : "TSWREIS NANDIMEDARAM(G),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS NANDIMEDARAM(G),PEDDAPALLI"  },  {  "text" : "TSWREIS PEDAPALLY(G),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS PEDAPALLY(G),PEDDAPALLI"  },    {  "text" : "TSWREIS PEDDAPALLI(B),PEDDAPALLI",  "selected" : "FALSE",  "value" : "TSWREIS PEDDAPALLI(B),PEDDAPALLI"  },  {  "text" : "TSWREIS BOINAPALLE(B),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS BOINAPALLE(B),RAJANNA"  },  {  "text" : "TSWREIS CHINNABONALA(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS CHINNABONALA(G),RAJANNA"  },  {  "text" : "TSWREIS ILLANTHAKUNTA(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS ILLANTHAKUNTA(G),RAJANNA"  },  {  "text" : "TSWREIS MUSTHABAD(B),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS MUSTHABAD(B),RAJANNA"  },  {  "text" : "TSWREIS NARMAL(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS NARMAL(G),RAJANNA"  },    {  "text" : "TSWREIS SIRICILLA(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS SIRICILLA(G),RAJANNA"  },  {  "text" : "TSWREIS VEMULAWADA(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWREIS VEMULAWADA(G),RAJANNA"  },  {  "text" : "TSWRDCW SIRICILLA(G),RAJANNA",  "selected" : "FALSE",  "value" : "TSWRDCW SIRICILLA(G),RAJANNA"  },  {  "text" : "TSWREIS  AMANGAL(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS  AMANGAL(G),RANGAREDDY"  },  {  "text" : "TSWREIS CHEVELLA(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS CHEVELLA(G),RANGAREDDY"  },  {  "text" : "TSWREIS CHILKUR(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS CHILKUR(B),RANGAREDDY"  },  {  "text" : "TSWREIS GACHIBOWLI(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS GACHIBOWLI(B),RANGAREDDY"  },  {  "text" : "TSWREIS GOWLIDODDI(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS GOWLIDODDI(G),RANGAREDDY"  },  {  "text" : "TSWREIS NAGOLE(IIT),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NAGOLE(IIT),RANGAREDDY"  },  {  "text" : "TSWREIS IBRAHIMPATNAM(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS IBRAHIMPATNAM(B),RANGAREDDY"  },  {  "text" : "TSWREIS KAMMADANAM(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS KAMMADANAM(G),RANGAREDDY"  },  {  "text" : "TSWREIS KANDUKUR(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS KANDUKUR(B),RANGAREDDY"  },  {  "text" : "TSWREIS KONDURGU(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS KONDURGU(B),RANGAREDDY"  },  {  "text" : "TSWREIS MAHESHWARAM(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS MAHESHWARAM(G),RANGAREDDY"  },  {  "text" : "TSWREIS NALLAKANCHE(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NALLAKANCHE(G),RANGAREDDY"  },  {  "text" : "TSWREIS NARSINGI(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NARSINGI(G),RANGAREDDY"  },  {  "text" : "TSWREIS SAROORNAGAR(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS SAROORNAGAR(G),RANGAREDDY"  },  {  "text" : "TSWREIS SHAMSHABAD(B),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS SHAMSHABAD(B),RANGAREDDY"  },  {  "text" : "TSWREIS SHANKARPALLE(G),RANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS SHANKARPALLE(G),RANGAREDDY"  },  {  "text" : "TSWREIS ANDOL(G),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS ANDOL(G),SANGAREDDY"  },  {  "text" : "TSWREIS CHITKUL(G),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS CHITKUL(G),SANGAREDDY"  },  {  "text" : "TSWREIS HATNOORA(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS HATNOORA(B),SANGAREDDY"  },  {  "text" : "TSWREIS HATNOORA JC(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS HATNOORA JC(B),SANGAREDDY"  },  {  "text" : "TSWREIS KONDAPUR(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS KONDAPUR(B),SANGAREDDY"  },  {  "text" : "TSWREIS NALLAVAGU(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NALLAVAGU(B),SANGAREDDY"  },  {  "text" : "TSWREIS NARAYANAKHED(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NARAYANAKHED(B),SANGAREDDY"  },  {  "text" : "TSWREIS NYALKAL(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS NYALKAL(B),SANGAREDDY"  },  {  "text" : "TSWREIS RAIKOTE(G),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS RAIKOTE(G),SANGAREDDY"  },  {  "text" : "TSWREIS SANGAREDDY(G),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS SANGAREDDY(G),SANGAREDDY"  },  {  "text" : "TSWREIS SINGOOR(B),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS SINGOOR(B),SANGAREDDY"  },  {  "text" : "TSWREIS ZAHIRABAD(G),SANGAREDDY",  "selected" : "FALSE",  "value" : "TSWREIS ZAHIRABAD(G),SANGAREDDY"  },   {  "text" : "TSWREIS ALWAL(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS ALWAL(G),SIDDIPET"  },  {  "text" : "TSWREIS BEJJANKI(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS BEJJANKI(G),SIDDIPET"  },  {  "text" : "TSWREIS CHERIAL(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS CHERIAL(B),SIDDIPET"  },  {  "text" : "TSWREIS CHINNAKODUR(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS CHINNAKODUR(B),SIDDIPET"  },  {  "text" : "TSWREIS DUBBAK(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS DUBBAK(B),SIDDIPET"  },  {  "text" : "TSWREIS GAJWEL(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS GAJWEL(G),SIDDIPET"  },  {  "text" : "TSWREIS HUSNABAD(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS HUSNABAD(B),SIDDIPET"  },  {  "text" : "TSWREIS JAGDEVPUR(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS JAGDEVPUR(G),SIDDIPET"  },  {  "text" : "TSWREIS KOHEDA(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS KOHEDA(B),SIDDIPET"  },  {  "text" : "TSWREIS KONDAPAK(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS KONDAPAK(B),SIDDIPET"  },  {  "text" : "TSWREIS MITTAPALLI(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS MITTAPALLI(G),SIDDIPET"  },  {  "text" : "TSWREIS MULUGU(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS MULUGU(G),SIDDIPET"  },  {  "text" : "TSWREIS RAMAKKAPET(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS RAMAKKAPET(G),SIDDIPET"  },   {  "text" : "TSWREIS SIDDIPET RURAL(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS SIDDIPET RURAL(G),SIDDIPET"  },  {  "text" : "TSWREIS TOGUTTA(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS TOGUTTA(G),SIDDIPET"  },  {  "text" : "TSWREIS VARGAL(B),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWREIS VARGAL(B),SIDDIPET"  },  {  "text" : "TSWRDCW SIDDIPET(G),SIDDIPET",  "selected" : "FALSE",  "value" : "TSWRDCW SIDDIPET(G),SIDDIPET"  },  {  "text" : "TSWREIS CHIVEMLA(B),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS CHIVEMLA(B),SURYAPET"  },  {  "text" : "TSWREIS HUZURNAGAR(B),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS HUZURNAGAR(B),SURYAPET"  },  {  "text" : "TSWREIS JAJIREDDYGUDEM(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS JAJIREDDYGUDEM(G),SURYAPET"  },  {  "text" : "TSWREIS MATTAMPALLI(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS MATTAMPALLI(G),SURYAPET"  },  {  "text" : "TSWREIS MUNAGALA(B),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS MUNAGALA(B),SURYAPET"  },  {  "text" : "TSWREIS NADIGUDEM(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS NADIGUDEM(G),SURYAPET"  },  {  "text" : "TSWREIS SURYAPET(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS SURYAPET(G),SURYAPET"  },  {  "text" : "TSWREIS TUNGATURTHI(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWREIS TUNGATURTHI(G),SURYAPET"  },  {  "text" : "TSWRDCW SURYAPETA(G),SURYAPET",  "selected" : "FALSE",  "value" : "TSWRDCW SURYAPETA(G),SURYAPET"  },  {  "text" : "TSWREIS BANTWARAM(G),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS BANTWARAM(G),VIKARABAD"  },  {  "text" : "TSWREIS KODANGAL(B),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS KODANGAL(B),VIKARABAD"  },  {  "text" : "TSWREIS MOMINPET(G),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS MOMINPET(G),VIKARABAD"  },  {  "text" : "TSWREIS PARGI(B),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS PARGI(B),VIKARABAD"  },  {  "text" : "TSWREIS  PEDDEMUL(B),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS  PEDDEMUL(B),VIKARABAD"  },  {  "text" : "TSWREIS SIVAREDDYPET(B),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS SIVAREDDYPET(B),VIKARABAD"  },  {  "text" : "TSWREIS VIKARABAD(G),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS VIKARABAD(G),VIKARABAD"  },  {  "text" : "TSWREIS YALAL(G),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWREIS YALAL(G),VIKARABAD"  },  {  "text" : "TSWRDCW VIKARABAD(G),VIKARABAD",  "selected" : "FALSE",  "value" : "TSWRDCW VIKARABAD(G),VIKARABAD"  },  {  "text" : "TSWREIS GOPALPET(G),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWREIS GOPALPET(G),WANAPARTHY"  },  {  "text" : "TSWREIS KOTHAKOTA(G),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWREIS KOTHAKOTA(G),WANAPARTHY"  },  {  "text" : "TSWREIS MADANAPURAM(B),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWREIS MADANAPURAM(B),WANAPARTHY"  },  {  "text" : "TSWREIS PEDDAMANDADI(G),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWREIS PEDDAMANDADI(G),WANAPARTHY"  },  {  "text" : "TSWREIS VEEPANAGANDLA(B),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWREIS VEEPANAGANDLA(B),WANAPARTHY"  },  {  "text" : "TSWRDCW WANAPARTHY(G),WANAPARTHY",  "selected" : "FALSE",  "value" : "TSWRDCW WANAPARTHY(G),WANAPARTHY"  },  {  "text" : "TSWREIS ATMAKUR(G),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS ATMAKUR(G),WARANGAL RURAL"  },  {  "text" : "TSWREIS DUGGONDI(G),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS DUGGONDI(G),WARANGAL RURAL"  },  {  "text" : "TSWREIS NARSAMPET(B),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS NARSAMPET(B),WARANGAL RURAL"  },  {  "text" : "TSWREIS PARKAL(G),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS PARKAL(G),WARANGAL RURAL"  },  {  "text" : "TSWREIS PARKAL(B),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS PARKAL(B),WARANGAL RURAL"  },  {  "text" : "TSWREIS PARVATHAGIRI(G),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS PARVATHAGIRI(G),WARANGAL RURAL"  },  {  "text" : "TSWREIS RAYAPARTHI(G),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS RAYAPARTHI(G),WARANGAL RURAL"  },  {  "text" : "TSWREIS WARDHANNAPET(B),WARANGAL RURAL",  "selected" : "FALSE",  "value" : "TSWREIS WARDHANNAPET(B),WARANGAL RURAL"  },  {  "text" : "TSWREIS DHARMASAGAR(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWREIS DHARMASAGAR(G),WARANGAL URBAN"  },  {  "text" : "TSWREIS ELKATURTI(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWREIS ELKATURTI(G),WARANGAL URBAN"  },  {  "text" : "TSWREIS HASANPARTHY(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWREIS HASANPARTHY(G),WARANGAL URBAN"  },  {  "text" : "TSWREIS MADIKONDA(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWREIS MADIKONDA(G),WARANGAL URBAN"  },   {  "text" : "TSWRDCW WARANGAL EAST(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWRDCW WARANGAL EAST(G),WARANGAL URBAN"  },  {  "text" : "TSWREIS WARANGAL WEST(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWREIS WARANGAL WEST(G),WARANGAL URBAN"  },  {  "text" : "TSWRDCW WARANGAL WEST(G),WARANGAL URBAN",  "selected" : "FALSE",  "value" : "TSWRDCW WARANGAL WEST(G),WARANGAL URBAN"  },  {  "text" : "TSWREIS ALAIR(G),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS ALAIR(G),YADADRI"  },  {  "text" : "TSWREIS BHONGIR(B),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS BHONGIR(B),YADADRI"  },  {  "text" : "TSWREIS CHOUTUPPAL(G),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS CHOUTUPPAL(G),YADADRI"  },  {  "text" : "TSWREIS MOTKUR(B),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS MOTKUR(B),YADADRI"  },  {  "text" : "TSWREIS RAJAPET(B),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS RAJAPET(B),YADADRI"  },  {  "text" : "TSWREIS RAMANNAPET(G),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS RAMANNAPET(G),YADADRI"  },  {  "text" : "TSWREIS VALIGONDA(G),YADADRI",  "selected" : "FALSE",  "value" : "TSWREIS VALIGONDA(G),YADADRI"  },  {  "text" : "TSWRDCW BHONGIR(G),YADADRI",  "selected" : "FALSE",  "value" : "TSWRDCW BHONGIR(G),YADADRI"  } ] }, "Attended" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Sick" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Sick UID" : { "type" : "textarea", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "R2H" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6, "notify" : "false" }, "R2H UID" : { "type" : "textarea", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7, "notify" : "false" }, "Absent" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 8, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } } }, "2" : { "Attendence Details" : { "Absent UID" : { "type" : "textarea", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 9, "notify" : "false" }, "RestRoom" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 10, "notify" : "false" }, "RestRoom UID" : { "type" : "textarea", "minlength" : "1", "maxlength" : "123", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 11, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } } } }, "permissions" : { "TSWREIS Attendance" : { "View_Permissions" : [ "Attendence Details" ], "Edit_Permissions" : [ "Attendence Details" ], "index" : 1 } }, "notification_parameters" : [ { "field" : "Select School", "page" : "1", "section" : "Attendence Details" } ], "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } } }, "app_id" : "healthcare201651317373988", "app_description" : "Attendance app for panacea", "status" : "processed", "app_name" : "Attendance app", "app_created" : "2016-10-27 11:46:23", "app_expiry" : "2017-02-15", "_version" : 15, "stages" : [ "TSWREIS Attendance" ], "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare201651317373988", "app_description" : "Attendance app for panacea", "app_name" : "Attendance app", "app_created" : "2016-10-15 11:51:25" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{ "permissions" : { "TSWREIS Attendance" : { "View_Permissions" : [ "Attendence Details" ], "Edit_Permissions" : [ "Attendence Details" ], "index" : 1 } }, "app_id" : "healthcare201651317373988", "app_description" : "Attendance app for panacea", "status" : "new", "app_name" : "Attendance app", "app_created" : "2016-10-27 11:46:23", "app_expiry" : "2017-02-15", "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } }, "_version" : 15, "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			
			//==================================================================== Health superiors request app ===============================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : { "pages" : { "1" : { "Student Info" : { "Unique ID" : { "type" : "retriever", "order" : 1, "coll_ref" : "healthcare2016226112942701", "field_ref" : "page1_Personal Information_Hospital Unique ID", "properties" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false", "parent" : "retriever" }, "retrieve_list" : [ "page1_Personal Information_Name", "page2_Personal Information_District", "page2_Personal Information_School Name", "page2_Personal Information_Class", "page2_Personal Information_Section" ] }, "Name" : { "type" : "mapper", "coll_ref" : "healthcare2016226112942701", "order" : 2, "field_ref" : "page1_Personal Information_Name", "properties" : { "type" : "text", "minlength" : "3", "maxlength" : "40", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "true", "parent" : "mapper" } }, "District" : { "type" : "mapper", "coll_ref" : "healthcare2016226112942701", "order" : 2, "field_ref" : "page2_Personal Information_District", "properties" : { "type" : "text", "minlength" : "3", "maxlength" : "40", "required" : "TRUE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "true", "parent" : "mapper" } }, "School Name" : { "type" : "mapper", "coll_ref" : "healthcare2016226112942701", "order" : 3, "field_ref" : "page2_Personal Information_School Name", "properties" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false", "parent" : "mapper" } }, "Class" : { "type" : "mapper", "coll_ref" : "healthcare2016226112942701", "order" : 4, "field_ref" : "page2_Personal Information_Class", "properties" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false", "parent" : "mapper" } }, "Section" : { "type" : "mapper", "coll_ref" : "healthcare2016226112942701", "order" : 5, "field_ref" : "page2_Personal Information_Section", "properties" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false", "parent" : "mapper" } }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } }, "Problem Info" : { "Identifier" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Bites", "value" : "Bites" }, { "label" : "Body pains", "value" : "Body pains" }, { "label" : "Breath less ness", "value" : "Breath less ness" }, { "label" : "Burning micturition", "value" : "Burning micturition" }, { "label" : "Burning sensation in the chest", "value" : "Burning sensation in the chest" }, { "label" : "Chest pain", "value" : "Chest pain" }, { "label" : "Chickenpox", "value" : "Chickenpox" }, { "label" : "cold", "value" : "cold" }, { "label" : "Constipation", "value" : "Constipation" }, { "label" : "Cough", "value" : "Cough" }, { "label" : "Cracks feet", "value" : "Cracks feet" }, { "label" : "Cramps", "value" : "Cramps" }, { "label" : "Defective hearing", "value" : "Defective hearing" }, { "label" : "DeHydration", "value" : "DeHydration" }, { "label" : "Delayed periods", "value" : "Delayed periods" }, { "label" : "Dental problems", "value" : "Dental problems" }, { "label" : "Diarrhea", "value" : "Diarrhea" }, { "label" : "Discharge from ear", "value" : "Discharge from ear" }, { "label" : "Dyspepsia", "value" : "Dyspepsia" }, { "label" : "Ear pain", "value" : "Ear pain" }, { "label" : "Fever", "value" : "Fever" }, { "label" : "Frequent urination", "value" : "Frequent urination" }, { "label" : "Headache", "value" : "Headache" }, { "label" : "Indigestion", "value" : "Indigestion" }, { "label" : "Irregular periods", "value" : "Irregular periods" }, { "label" : "Others", "value" : "Others" } ] }, "newline3" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 3 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2 } } }, "2" : { "Problem Info" : { "Description" : { "type" : "textarea", "minlength" : "2", "maxlength" : "500", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } }, "Diagnosis Info" : { "Doctor Summary" : { "type" : "textarea", "minlength" : "2", "maxlength" : "500", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false" }, "Doctor Advice" : { "type" : "select", "size" : "1", "required" : "FALSE", "key" : "TRUE", "description" : "", "option_choose_one" : "TRUE", "with_translations" : "FALSE", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "text" : "Prescription", "selected" : "TRUE", "value" : "Prescription" }, { "text" : "Advice", "selected" : "FALSE", "value" : "Advice" }, { "text" : "Refer 2 Hospital", "selected" : "FALSE", "value" : "Refer 2 Hospital" } ] }, "Prescription" : { "type" : "textarea", "minlength" : "2", "maxlength" : "250", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4 } }, "Review Info" : { "Request Type" : { "type" : "select", "size" : "1", "required" : "FALSE", "key" : "TRUE", "description" : "", "option_choose_one" : "TRUE", "with_translations" : "FALSE", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "text" : "Normal", "selected" : "TRUE", "value" : "Normal" }, { "text" : "Emergency", "selected" : "FALSE", "value" : "Emergency" }, { "text" : "Chronic", "selected" : "FALSE", "value" : "Chronic" } ] }, "Status" : { "type" : "select", "size" : "1", "required" : "FALSE", "key" : "TRUE", "description" : "", "option_choose_one" : "TRUE", "with_translations" : "FALSE", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "text" : "Initiated", "selected" : "TRUE", "value" : "Initiated" }, { "text" : "Prescribed", "selected" : "FALSE", "value" : "Prescribed" }, { "text" : "Under Medication", "selected" : "FALSE", "value" : "Under Medication" }, { "text" : "Follow-up", "selected" : "FALSE", "value" : "Follow-up" }, { "text" : "Cured", "selected" : "FALSE", "value" : "Cured" }, { "text" : "Hospitalized", "selected" : "FALSE", "value" : "Hospitalized" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5 } } } }, "permissions" : { "HS 1" : { "View_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "Edit_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "index" : 1 }, "HS 2" : { "View_Permissions" : [ "Student Info", "Problem Info", "Diagnosis Info", "Review Info" ], "Edit_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "index" : 3 } }, "notification_parameters" : [ { "field" : "Name", "page" : "1", "section" : "Student Info" } ], "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,\nsecbad,\nindia", "logo" : "" } } }, "app_id" : "healthcare2016531124515424", "app_description" : "App for health supervisor to initiate and continue requests", "status" : "processed", "app_name" : "Health Supervisor Request App", "app_created" : "2016-05-31 13:05:24", "app_expiry" : "2017-05-31", "_version" : 1, "stages" : [ "HS 1", "Doctor", "HS 2" ], "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare2016531124515424", "app_description" : "App for health supervisor to initiate and continue requests", "app_name" : "Health Supervisor Request App", "app_created" : "2016-05-31 13:05:24" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{ "permissions" : { "HS 1" : { "View_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "Edit_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "index" : 1 }, "HS 2" : { "View_Permissions" : [ "Student Info", "Problem Info", "Diagnosis Info", "Review Info" ], "Edit_Permissions" : [ "Student Info", "Problem Info", "Review Info" ], "index" : 3 } }, "app_id" : "healthcare2016531124515424", "app_description" : "App for health supervisor to initiate and continue requests", "status" : "new", "app_name" : "Health Supervisor Request App", "app_created" : "2016-05-31 13:05:24", "app_expiry" : "2017-05-31", "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,\nsecbad,\nindia", "logo" : "" } }, "_version" : 1, "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			//==================================================================== Sanitation infrastructure app ================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : { "pages" : { "1" : { "Dormitories" : { "Separate Dormitory" : { "type" : "radio", "required" : "TRUE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "true", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } }, "Toilets" : { "Water Source" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1, "notify" : "false" }, "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "in numbers", "description" : "", "multilanguage" : "FALSE", "order" : 2 }, "Buckets" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Mugs" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Dust Bins" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "Soap" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6, "notify" : "false" }, "Incinerator" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2 } } }, "2" : { "Hand Wash" : { "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "in numbers", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Dining Halls" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Kitchen" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Class Rooms" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Dormitories" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "Kitchen consists of" : { "type" : "checkbox", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 6, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Gas facility", "value" : "Gas facility" }, { "label" : "Kerosene Stove", "value" : "Kerosene Stove" }, { "label" : "Made on wood", "value" : "Made on wood" } ] }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } } }, "3" : { "Waste Management" : { "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "Disposable Bins (in numbers)", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Dining Halls" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Kitchen" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Class Rooms" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Dormitories" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4 } } }, "4" : { "Water Facility" : { "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "Availability of Water in Toiltes (in numbers)", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Dining Halls" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Kitchen" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Class Rooms" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "Dormitories" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5, "notify" : "false" }, "Running water(number of taps)" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6, "notify" : "false" }, "Store water" : { "type" : "number", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7, "notify" : "false" }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5 } } }, "5" : { "Dining Hall" : { "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "Children sit on (in numbers)", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Floor" : { "type" : "number", "minlength" : "1", "maxlength" : "10", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Table and Chairs" : { "type" : "number", "minlength" : "1", "maxlength" : "10", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "Benches" : { "type" : "number", "minlength" : "1", "maxlength" : "10", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4, "notify" : "false" }, "newline5" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 5 }, "newline6" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 6 }, "newline7" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 7 }, "newline8" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 8 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6 } } }, "6" : { "Declaration" : { "Note:-" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "I here by declare i would render all the responsibilities as mentioned above", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Place" : { "type" : "text", "minlength" : "1", "maxlength" : "55", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Date" : { "type" : "date", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "newline4" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 4 }, "newline5" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 5 }, "Signature" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 6 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 7 } } } }, "permissions" : { "Stage Name1" : { "View_Permissions" : [ "Dormitories", "Toilets", "Hand Wash", "Waste Management", "Water Facility", "Dining Hall", "Declaration" ], "Edit_Permissions" : [ "Dormitories", "Toilets", "Hand Wash", "Waste Management", "Water Facility", "Dining Hall", "Declaration" ], "index" : 1 } }, "notification_parameters" : [ { "field" : "Separate Dormitory", "page" : "1", "section" : "Dormitories" } ], "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } } }, "app_id" : "healthcare20161114161842748", "app_description" : "Sanitation Infrastructure Form", "status" : "processed", "app_name" : "Sanitation Infrastructure Form", "app_created" : "2016-11-14 13:04:48", "app_expiry" : "2020-05-21", "_version" : 3, "stages" : [ "Stage Name1" ], "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare20161114161842748", "app_description" : "Sanitation Infrastructure Form", "app_name" : "Sanitation Infrastructure Form", "app_created" : "2016-11-14 11:35:59" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			//$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			
			//==================================================================== Sanitation form app ==========================================
			
			$app_coll = $id."_apps";
			$data = json_decode('{ "app_template" : { "pages" : { "1" : { "Hand Wash" : { "Hand sanitizers/soap used" : { "type" : "radio", "required" : "TRUE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "true", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 1 } }, "Kitchen" : { "Food stored and served with tight containers" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Availabilities of storage of perishable products" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2 } } }, "2" : { "Cleanliness" : { "Dormitories" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Kitchen" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Dining Halls" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Class Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 4, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Sick Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 5, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Staff Rooms" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 6, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } } }, "3" : { "Cleanliness" : { "Water Tanks" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 7, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Dust Bins" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 8, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Toilets" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 9, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "Kitchen Utensils" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 10, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Once", "value" : "Once" }, { "label" : "Twice", "value" : "Twice" }, { "label" : "Thrice", "value" : "Thrice" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3 } }, "Food" : { "Food prepared according to the days menu" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Kitchen staff wears gloves ans caps while serving" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Every meal is tasted by a staff members before serving" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 3, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 4 } } }, "4" : { "Waste Management" : { "Separate dumping of Inorganic waste" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 1, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "Separate dumping of Organic waste" : { "type" : "radio", "required" : "FALSE", "key" : "TRUE", "description" : "", "order" : 2, "multilanguage" : "FALSE", "notify" : "false", "options" : [ { "label" : "Yes", "value" : "Yes" }, { "label" : "No", "value" : "No" } ] }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 5 } }, "Declaration Information" : { "Declaration:" : { "type" : "instruction", "required" : "FALSE", "key" : "TRUE", "instructions" : "I here by declare i would render all the responsibilities as mentioned above", "description" : "", "multilanguage" : "FALSE", "order" : 1 }, "Place:" : { "type" : "text", "minlength" : "1", "maxlength" : "123", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 2, "notify" : "false" }, "Date:" : { "type" : "date", "required" : "FALSE", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 3, "notify" : "false" }, "newline4" : { "type" : "newline", "key" : "FALSE", "multilanguage" : "FALSE", "order" : 4 }, "Signature:" : { "type" : "text", "minlength" : "", "maxlength" : "", "required" : "FALSE", "key" : "FALSE", "description" : "", "multilanguage" : "FALSE", "order" : 5 }, "dont_use_this_name" : { "type" : "SBreak", "key" : "TRUE", "description" : "", "multilanguage" : "FALSE", "order" : 6 } } } }, "permissions" : { "Stage Name1" : { "View_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information" ], "Edit_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information" ], "index" : 1 } }, "notification_parameters" : [ { "field" : "Hand sanitizers/soap used", "page" : "1", "section" : "Hand Wash" } ], "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } } }, "app_id" : "healthcare2016111212310531", "app_description" : "Sanitation Form", "status" : "new", "app_name" : "Sanitation Report", "app_created" : "2017-01-21 14:57:24", "app_expiry" : "2020-06-19", "_version" : 4, "stages" : [ "Stage Name1" ], "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_applist";
			$data = json_decode('{ "app_id" : "healthcare2016111212310531", "app_description" : "Sanitation Form", "app_name" : "Sanitation Form", "app_created" : "2016-11-14 12:31:08" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			
			$app_coll = $id."_web_apps";
			$data = json_decode('{ "permissions" : { "Stage Name1" : { "View_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information" ], "Edit_Permissions" : [ "Hand Wash", "Kitchen", "Cleanliness", "Food", "Waste Management", "Declaration Information" ], "index" : 1 } }, "app_id" : "healthcare2016111212310531", "app_description" : "Sanitation Form", "status" : "new", "app_name" : "Sanitation Report", "app_created" : "2017-01-21 14:57:24", "app_expiry" : "2020-06-19", "application_header" : { "header_details" : { "companyname" : "Healthcare", "address" : "401s,secbad,india", "logo" : "" } }, "_version" : 4, "created_by" : "tlstec.primary2@gmail.com", "use_profile_header" : "no", "blank_app" : "no" }',true);
			////log_message("debug","dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".print_r($data,true));
			$this->mongo_db->insert ( $app_coll, $data );
			
			//===================================================================================================================================
			
			
		} 
		
		//=======================================================
		*/
		//=======================================================
		
		
		//=========================start of modification======
		/*
		
		$unique_ids = ["KMNR_52031_","WGL_52124_","WGL_52123_","WGL_52127_","NZD_61819_","ADB_51930_","ADB_51924_","ADB_51923_","RR_61520_","RR_61521_","RR_61524_","RR_61525_","HYD_61607_","KMNR_52028_","KMNR_52029_","KMNR_52022_","KMNR_52034_","SMBD_61450_","SMBD_61516_","SMBD_61518_","SMBD_61455_","SMBD_61515_","MDK_61726_","MDK_61727_","MDK_61728_","MDK_61730_","MDK_61733_","RR_61514_","RR_61519_","WGL_52128_","WGL_52119_","WGL_52120_","WGL_52130_","WGL_52131_","KMNR_52014_","KMNR_52012_","KMNR_52010_","MDK_61705_","MDK_61701_","MDK_61703_","MDK_61710_","MDK_61704_","MDK_61706_","MDK_61713_","MDK_61714_","MDK_61717_","MDK_61709_","MDK_61723_","MDK_61719_","MDK_61702_","MDK_61716_","MDK_61718_","MDK_61707_","MDK_61724_","KMNR_52006_","KMNR_52005_","WGL_52109_","NLG_62305_","NLG_62302_","NLG_62308_","NLG_62312_","RR_61506_","RR_61501_","RR_61507_","RR_61508_","MBNR_61407_","MBNR_61443_","WGL_52107_","WGL_52108_","WGL_52102_","WGL_52112_","WGL_52118_","WGL_52104_","KMNR_52013_","NLG_62301_","NLG_62303_","NLG_62309_","NLG_62310_","KMM_52201_","KMM_52209_","KMM_52205_","KMM_52210_","KMM_52203_","KMNR_52001_","WGL_52114_","WGL_52101_","WGL_52106_","WGL_52116_","WGL_52103_","WGL_52113_","MBNR_61410_","ADB_51901_","ADB_51903_","ADB_51911_","ADB_51907_","ADB_51921_","ADB_51906_","ADB_51920_","ADB_51910_","ADB_51904_","ADB_51908_","ADB_51909_","ADB_51912_","KMNR_52009_","KMNR_52002_","KMNR_52011_","KMNR_52007_","KMNR_52008_","WGL_52111_","WGL_52105_","WGL_52115_","WGL_52117_","RR_61511_"];
		
		$correct_ids = ["JGTL_52031_","JGN_52124_","JSKR_52123_","JSKR_52127_","KMR_61819_","MCRL_51930_","MCRL_51924_","MCRL_51923_","MDCL_61520_","MDCL_61521_","MDCL_61524_","MDCL_61525_","MDCL_61607_","PDPL_52028_","RJN_52029_","RJN_52022_","RJN_52034_","RR_61450_","RR_61516_","RR_61518_","RR_61455_","RR_61515_","SDPT_61726_","SDPT_61727_","SDPT_61728_","SDPT_61730_","SDPT_61733_","VKRD_61514_","VKRD_61519_","WGLR_52128_","WGLU_52119_","WGLU_52120_","WGLU_52130_","WGLU_52131_","RJN_52014_","RJN_52012_","RJN_52010_","SRD_61705_","SRD_61701_","SRD_61703_","SRD_61710_","SRD_61704_","SRD_61706_","SRD_61713_","SRD_61714_","SRD_61717_","SRD_61709_","SRD_61723_","SDPT_61719_","SDPT_61702_","SDPT_61716_","SDPT_61718_","SDPT_61707_","SDPT_61724_","SDPT_52006_","SDPT_52005_","SDPT_52109_","SRPT_62305_","SRPT_62302_","SRPT_62308_","SRPT_62312_","VKRD_61506_","VKRD_61501_","VKRD_61507_","VKRD_61508_","WNPY_61407_","WNPY_61443_","WGLR_52107_","WGLR_52108_","WGLR_52102_","WGLR_52112_","WGLR_52118_","WGLU_52104_","WGLU_52013_","YDR_62301_","YDR_62303_","YDR_62309_","YDR_62310_","BDD_52201_","BDD_52209_","BDD_52205_","BDD_52210_","BDD_52203_","JGTL_52001_","JGN_52114_","JGN_52101_","JGN_52106_","JGN_52116_","JSKR_52103_","JSKR_52113_","GDW_61410_","KMB_51901_","KMB_51903_","KMB_51911_","MCRL_51907_","MCRL_51921_","MCRL_51906_","MCRL_51920_","MCRL_51910_","NML_51904_","NML_51908_","NML_51909_","NML_51912_","PDPL_52009_","PDPL_52002_","PDPL_52011_","PDPL_52007_","PDPL_52008_","MBBD_52111_","MBBD_52105_","MBBD_52115_","MBBD_52117_","MDCL_61511_"];
		
		$old_school_names = ["TSWREIS KORATLA(B),KARIMNAGAR","TSWREIS NARMETTA(G),WARANGAL","TSWREIS BHUPALAPALLI(G),WARANGAL","TSWREIS MULUG(G),WARANGAL","TSWREIS LINGAMPET(G),NIZAMABAD","TSWRDCW MANCHIRIAL(G),ADILABAD","TSWREIS KASIPET(B),ADILABAD","TSWREIS MANDAMARRI(G),ADILABAD","TSWREIS SHAMIRPET(B),RANGAREDDY","TSWREIS MALKAJGIRI(G),RANGAREDDY","TSWREIS JAGADGIRIGUTTA(G),RANGAREDDY","TSWREIS UPPAL(B),RANGAREDDY","TSWRDCW JAGADGIRI GUTTA(G),HYDERABAD","TSWREIS PEDDAPALLI(B),KARIMNAGAR","TSWREIS MUSTHABAD(B),KARIMNAGAR","TSWREIS ILLANTHAKUNTA(G),KARIMNAGAR","TSWRDCW SIRICILLA(G),KARIMNAGAR","TSWREIS AMANGAL(G),SHAMSHABAD","TSWREIS CHEVELLA(G),SHAMSHABAD","TSWREIS KANDUKUR(B),SHAMSHABAD","TSWREIS KONDURGU(B),SHAMSHABAD","TSWREIS SHANKARPALLE(G),SHAMSHABAD","TSWREIS GAJWEL(G),MEDAK","TSWREIS KONDAPAK(B),MEDAK","TSWREIS JAGADEVPUR(G),MEDAK","TSWREIS VARGAL(B),MEDAK","TSWRDCW SIDDIPET(G),MEDAK","TSWREIS BANTWARAM(G),RANGAREDDY","TSWREIS PEDDEMUL(B),RANGAREDDY","TSWREIS DUGGONDI(G),WARANGAL","TSWREIS DHARMASAGAR(G),WARANGAL","TSWREIS HASANPARTHY(G),WARANGAL","TSWRDCW WARANGAL EAST(G),WARANGAL","TSWRDCW WARANGAL WEST(G),WARANGAL","TSWREIS CHINNABONALA(G),KARIMNAGAR","TSWREIS NARMAL(G),KARIMNAGAR","TSWREIS SIRICILLA(G),KARIMNAGAR","TSWREIS CHITKUL(G),MEDAK","TSWREIS NALLAVAGU(B),MEDAK","TSWREIS HATNOORA(B),MEDAK","TSWREIS HATNOORA JC(B),MEDAK","TSWREIS NARAYANAKHED(B),MEDAK","TSWREIS SANGAREDDY(G),MEDAK","TSWREIS ZAHIRABAD(G),MEDAK","TSWREIS KONDAPUR(B),MEDAK","TSWREIS ANDOL(G),MEDAK","TSWREIS SINGOOR(B),MEDAK","TSWREIS RAIKOTE(G),MEDAK","TSWREIS MITTAPALLI(G),MEDAK","TSWREIS RAMAKKAPET(G),MEDAK","TSWREIS MULUGU(G),MEDAK","TSWREIS ALWAL(B),MEDAK","TSWREIS TOGUTTA(G),MEDAK","TSWREIS DUBBAK(B),SIDDIPET","TSWREIS KOHEDA(B),KARIMNAGAR","TSWREIS HUSNABAD(B),KARIMNAGAR","TSWREIS CHERIAL(B),WARANGAL","TSWREIS SURYAPET(G),NALGONDA","TSWREIS MATTAMPALLI(G),NALGONDA","TSWREIS NADIGUDEM(G),NALGONDA","TSWREIS TUNGATURTHI(G),NALGONDA","TSWREIS VIKARABAD(G),RANGAREDDY","TSWREIS PARGI(B),RANGAREDDY","TSWREIS SIVAREDDYPET(B),RANGAREDDY","TSWREIS YALAL(G),RANGAREDDY","TSWREIS MADANAPURAM(B),MAHABUBNAGAR","TSWREIS GOPALPET(G),MAHABUBNAGAR","TSWREIS NARSAMPET(B),WARANGAL","TSWREIS PARVATHAGIRI(G),WARANGAL","TSWREIS PARKAL(G),WARANGAL","TSWREIS RAYAPARTHI(G),WARANGAL","TSWREIS WARDHANNAPET(B),WARANGAL","TSWREIS MADIKONDA(G),WARANGAL","TSWREIS ELKATURTI(G),KARIMNAGAR","TSWREIS BHONGIR(B),NALGONDA","TSWREIS RAJAPET(B),NALGONDA","TSWREIS RAMANNAPET(G),NALGONDA","TSWREIS ALAIR(G),NALGONDA","TSWREIS ANNAPUREDDYPALLI(B),KHAMMAM","TSWREIS PALAVANCHA(G),KHAMMAM","TSWREIS KOTHAGUDEM(B),KHAMMAM","TSWREIS MULAKALAPALLI(G),KHAMMAM","TSWREIS DAMMAPET(B),KHAMMAM","TSWREIS MAIDPALLY(B),KARIMNAGAR","TSWREIS PALAKURTHI(G),WARANGAL","TSWREIS JANGAON(B),WARANGAL","TSWREIS GHANPUR(B),WARANGAL","TSWREIS ZAFFERGADH(G),WARANGAL","TSWREIS JAKARAM(B),WARANGAL","TSWREIS CHITYAL(G),WARANGAL","TSWREIS GHATTU(G),MAHABUBNAGAR","TSWREIS ASIFABAD(B),ADILABAD","TSWREIS SIRPUR(B),ADILABAD","TSWREIS SIRPUR(G),ADILABAD","TSWREIS CHENNUR(B),ADILABAD","TSWREIS CHENNUR(G),ADILABAD","TSWREIS LUXETTIPET(G),ADILABAD","TSWREIS BELLAMPALLI(B),ADILABAD","TSWREIS BELLAMPALLI(G),ADILABAD","TSWREIS MUDHOLE(B),ADILABAD","TSWREIS NIRMAL(G),ADILABAD","TSWREIS KADDAM(G),ADILABAD","TSWREIS JAM(G),ADILABAD","TSWREIS MAHADEVPUR(G),KARIMNAGAR","TSWREIS MALLAPUR(G),KARIMNAGAR","TSWREIS NANDIMEDARAM(G),KARIMNAGAR","TSWREIS PEDAPALLY(G),KARIMNAGAR","TSWREIS MANTHANI(B),KARIMNAGAR","TSWREIS TORRUR(G),WARANGAL","TSWREIS MAHABOOBABAD(G),WARANGAL","TSWREIS KESAMUDRAM(G),WARANGAL","TSWREIS MARIPEDA(B),WARANGAL","TSWREIS MEDCHEL(G),RANGAREDDY"];
		
		$new_schools_names = ["TSWREIS KORATLA(B),JAGTIAL","TSWREIS NARMETTA(G),JANGAON","TSWREIS BHUPALAPALLI(G),JAYASHANKAR","TSWREIS MULUG(G),JAYASHANKAR","TSWREIS LINGAMPET(G),KAMAREDDY","TSWRDCW MANCHIRIAL(G),MANCHERIAL","TSWREIS KASIPET(B),MANCHERIAL","TSWREIS MANDAMARRI(G),MANCHERIAL","TSWREIS SHAMIRPET(B),MEDCHAL","TSWREIS MALKAJGIRI(G),MEDCHAL","TSWREIS JAGADGIRIGUTTA(G),MEDCHAL","TSWREIS UPPAL(B),MEDCHAL","TSWRDCW JAGADGIRI GUTTA(G),MEDCHAL","TSWREIS PEDDAPALLI(B),PEDDAPALLI","TSWREIS MUSTHABAD(B),RAJANNA","TSWREIS ILLANTHAKUNTA(G),RAJANNA","TSWRDCW SIRICILLA(G),RAJANNA","TSWREIS AMANGAL(G),RANGAREDDY","TSWREIS CHEVELLA(G),RANGAREDDY","TSWREIS KANDUKUR(B),RANGAREDDY","TSWREIS KONDURGU(B),RANGAREDDY","TSWREIS SHANKARPALLE(G),RANGAREDDY","TSWREIS GAJWEL(G),SIDDIPET","TSWREIS KONDAPAK(B),SIDDIPET","TSWREIS JAGADEVPUR(G),SIDDIPET","TSWREIS VARGAL(B),SIDDIPET","TSWRDCW SIDDIPET(G),SIDDIPET","TSWREIS BANTWARAM(G),VIKARABAD","TSWREIS PEDDEMUL(B),VIKARABAD","TSWREIS DUGGONDI(G),WARANGAL RURAL","TSWREIS DHARMASAGAR(G),WARANGAL URBAN","TSWREIS HASANPARTHY(G),WARANGAL URBAN","TSWRDCW WARANGAL EAST(G),WARANGAL URBAN","TSWRDCW WARANGAL WEST(G),WARANGAL URBAN","TSWREIS CHINNABONALA(G),RAJANNA","TSWREIS NARMAL(G),RAJANNA","TSWREIS SIRICILLA(G),RAJANNA","TSWREIS CHITKUL(G),SANGAREDDY","TSWREIS NALLAVAGU(B),SANGAREDDY","TSWREIS HATNOORA(B),SANGAREDDY","TSWREIS HATNOORA JC(B),SANGAREDDY","TSWREIS NARAYANAKHED(B),SANGAREDDY","TSWREIS SANGAREDDY(G),SANGAREDDY","TSWREIS ZAHIRABAD(G),SANGAREDDY","TSWREIS KONDAPUR(B),SANGAREDDY","TSWREIS ANDOL(G),SANGAREDDY","TSWREIS SINGOOR(B),SANGAREDDY","TSWREIS RAIKOTE(G),SANGAREDDY","TSWREIS MITTAPALLI(G),SIDDIPET","TSWREIS RAMAKKAPET(G),SIDDIPET","TSWREIS MULUGU(G),SIDDIPET","TSWREIS ALWAL(B),SIDDIPET","TSWREIS TOGUTTA(G),SIDDIPET","TSWREIS DUBBAK(B),SIDDIPET","TSWREIS KOHEDA(B),SIDDIPET","TSWREIS HUSNABAD(B),SIDDIPET","TSWREIS CHERIAL(B),SIDDIPET","TSWREIS SURYAPET(G),SURYAPET","TSWREIS MATTAMPALLI(G),SURYAPET","TSWREIS NADIGUDEM(G),SURYAPET","TSWREIS TUNGATURTHI(G),SURYAPET","TSWREIS VIKARABAD(G),VIKARABAD","TSWREIS PARGI(B),VIKARABAD","TSWREIS SIVAREDDYPET(B),VIKARABAD","TSWREIS YALAL(G),VIKARABAD","TSWREIS MADANAPURAM(B),WANAPARTHY","TSWREIS GOPALPET(G),WANAPARTHY","TSWREIS NARSAMPET(B),WARANGAL RURAL","TSWREIS PARVATHAGIRI(G),WARANGAL RURAL","TSWREIS PARKAL(G),WARANGAL RURAL","TSWREIS RAYAPARTHI(G),WARANGAL RURAL","TSWREIS WARDHANNAPET(B),WARANGAL RURAL","TSWREIS MADIKONDA(G),WARANGAL URBAN","TSWREIS ELKATURTI(G),WARANGAL URBAN","TSWREIS BHONGIR(B),YADADRI","TSWREIS RAJAPET(B),YADADRI","TSWREIS RAMANNAPET(G),YADADRI","TSWREIS ALAIR(G),YADADRI","TSWREIS ANNAPUREDDYPALLI(B),BHADRADRI","TSWREIS PALAVANCHA(G),BHADRADRI","TSWREIS KOTHAGUDEM(B),BHADRADRI","TSWREIS MULAKALAPALLI(G),BHADRADRI","TSWREIS DAMMAPET(B),BHADRADRI","TSWREIS MAIDPALLY(B),JAGTIAL","TSWREIS PALAKURTHI(G),JANGAON","TSWREIS JANGAON(B),JANGAON","TSWREIS GHANPUR(B),JANGAON","TSWREIS ZAFFERGADH(G),JANGAON","TSWREIS JAKARAM(B),JAYASHANKAR","TSWREIS CHITYAL(G),JAYASHANKAR","TSWREIS GHATTU(G),GADWAL","TSWREIS ASIFABAD(B),KOMURAM BHEEM","TSWREIS SIRPUR(B),KOMURAM BHEEM","TSWREIS SIRPUR(G),KOMURAM BHEEM","TSWREIS CHENNUR(B),MANCHERIAL","TSWREIS CHENNUR(G),MANCHERIAL","TSWREIS LUXETTIPET(G),MANCHERIAL","TSWREIS BELLAMPALLI(B),MANCHERIAL","TSWREIS BELLAMPALLI(G),MANCHERIAL","TSWREIS MUDHOLE(B),NIRMAL","TSWREIS NIRMAL(G),NIRMAL","TSWREIS KADDAM(G),NIRMAL","TSWREIS JAM(G),NIRMAL","TSWREIS MAHADEVPUR(G),PEDDAPALLI","TSWREIS MALLAPUR(G),PEDDAPALLI","TSWREIS NANDIMEDARAM(G),PEDDAPALLI","TSWREIS PEDAPALLY(G),PEDDAPALLI","TSWREIS MANTHANI(B),PEDDAPALLI","TSWREIS TORRUR(G),MAHABUBABAD","TSWREIS MAHABOOBABAD(G),MAHABUBABAD","TSWREIS KESAMUDRAM(G),MAHABUBABAD","TSWREIS MARIPEDA(B),MAHABUBABAD","TSWREIS MEDCHEL(G),MEDCHEL"];
		
		$districts = ["JAGTIAL", "JANGAON", "JAYASHANKAR", "JAYASHANKAR", "KAMAREDDY", "MANCHERIAL", "MANCHERIAL", "MANCHERIAL", "MEDCHAL", "MEDCHAL", "MEDCHAL", "MEDCHAL", "MEDCHAL", "PEDDAPALLI", "RAJANNA", "RAJANNA", "RAJANNA", "RANGAREDDY", "RANGAREDDY", "RANGAREDDY", "RANGAREDDY", "RANGAREDDY", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "VIKARABAD", "VIKARABAD", "WARANGAL RURAL", "WARANGAL URBAN", "WARANGAL URBAN", "WARANGAL URBAN", "WARANGAL URBAN","RAJANNA", "RAJANNA", "RAJANNA", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SANGAREDDY", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SIDDIPET", "SURYAPET", "SURYAPET", "SURYAPET", "SURYAPET", "VIKARABAD", "VIKARABAD", "VIKARABAD", "VIKARABAD", "WANAPARTHY", "WANAPARTHY", "WARANGAL RURAL", "WARANGAL RURAL", "WARANGAL RURAL", "WARANGAL RURAL", "WARANGAL RURAL", "WARANGAL URBAN", "WARANGAL URBAN", "YADADRI", "YADADRI", "YADADRI", "YADADRI", "BHADRADRI", "BHADRADRI", "BHADRADRI", "BHADRADRI", "BHADRADRI", "JAGTIAL", "JANGAON", "JANGAON", "JANGAON", "JANGAON", "JAYASHANKAR", "JAYASHANKAR", "GADWAL", "KOMURAM BHEEM", "KOMURAM BHEEM", "KOMURAM BHEEM", "MANCHERIAL", "MANCHERIAL", "MANCHERIAL", "MANCHERIAL", "MANCHERIAL", "NIRMAL", "NIRMAL", "NIRMAL", "NIRMAL", "PEDDAPALLI", "PEDDAPALLI", "PEDDAPALLI", "PEDDAPALLI", "PEDDAPALLI", "MAHABUBABAD", "MAHABUBABAD", "MAHABUBABAD", "MAHABUBABAD", "MEDCHEL"];
		
		$emails = ["kmnr.52031.","wgl.52124.","wgl.52123.","wgl.52127.","nzd.61819.","adb.51930.","adb.51924.","adb.51923.","rr.61520.","rr.61521.","rr.61524.","rr.61525.","hyd.61607.","kmnr.52028.","kmnr.52029.","kmnr.52022.","kmnr.52034.","smbd.61450.","smbd.61516.","smbd.61518.","smbd.61455.","smbd.61515.","mdk.61726.","mdk.61727.","mdk.61728.","mdk.61730.","mdk.61733.","rr.61514.","rr.61519.","wgl.52128.","wgl.52119.","wgl.52120.","wgl.52130.","wgl.52131.","kmnr.52014.","kmnr.52012.","kmnr.52010.","mdk.61705.","mdk.61701.","mdk.61703.","mdk.61710.","mdk.61704.","mdk.61706.","mdk.61713.","mdk.61714.","mdk.61717.","mdk.61709.","mdk.61723.","mdk.61719.","mdk.61702.","mdk.61716.","mdk.61718.","mdk.61707.","mdk.61724.","kmnr.52006.","kmnr.52005.","wgl.52109.","nlg.62305.","nlg.62302.","nlg.62308.","nlg.62312.","rr.61506.","rr.61501.","rr.61507.","rr.61508.","mbnr.61407.","mbnr.61443.","wgl.52107.","wgl.52108.","wgl.52102.","wgl.52112.","wgl.52118.","wgl.52104.","kmnr.52013.","nlg.62301.","nlg.62303.","nlg.62309.","nlg.62310.","kmm.52201.","kmm.52209.","kmm.52205.","kmm.52210.","kmm.52203.","kmnr.52001.","wgl.52114.","wgl.52101.","wgl.52106.","wgl.52116.","wgl.52103.","wgl.52113.","mbnr.61410.","adb.51901.","adb.51903.","adb.51911.","adb.51907.","adb.51921.","adb.51906.","adb.51920.","adb.51910.","adb.51904.","adb.51908.","adb.51909.","adb.51912.","kmnr.52009.","kmnr.52002.","kmnr.52011.","kmnr.52007.","kmnr.52008.","wgl.52111.","wgl.52105.","wgl.52115.","wgl.52117.","rr.61511."];
		
		$new_emails = ["jgtl.52031.","jgn.52124.","jskr.52123.","jskr.52127.","kmr.61819.","mcrl.51930.","mcrl.51924.","mcrl.51923.","mdcl.61520.","mdcl.61521.","mdcl.61524.","mdcl.61525.","mdcl.61607.","pdpl.52028.","rjn.52029.","rjn.52022.","rjn.52034.","rr.61450.","rr.61516.","rr.61518.","rr.61455.","rr.61515.","sdpt.61726.","sdpt.61727.","sdpt.61728.","sdpt.61730.","sdpt.61733.","vkrd.61514.","vkrd.61519.","wglr.52128.","wglu.52119.","wglu.52120.","wglu.52130.","wglu.52131.","rjn.52014.","rjn.52012.","rjn.52010.","srd.61705.","srd.61701.","srd.61703.","srd.61710.","srd.61704.","srd.61706.","srd.61713.","srd.61714.","srd.61717.","srd.61709.","srd.61723.","sdpt.61719.","sdpt.61702.","sdpt.61716.","sdpt.61718.","sdpt.61707.","sdpt.61724.","sdpt.52006.","sdpt.52005.","sdpt.52109.","srpt.62305.","srpt.62302.","srpt.62308.","srpt.62312.","vkrd.61506.","vkrd.61501.","vkrd.61507.","vkrd.61508.","wnpy.61407.","wnpy.61443.","wglr.52107.","wglr.52108.","wglr.52102.","wglr.52112.","wglr.52118.","wglu.52104.","wglu.52013.","ydr.62301.","ydr.62303.","ydr.62309.","ydr.62310.","bdd.52201.","bdd.52209.","bdd.52205.","bdd.52210.","bdd.52203.","jgtl.52001.","jgn.52114.","jgn.52101.","jgn.52106.","jgn.52116.","jskr.52103.","jskr.52113.","gdw.61410.","kmb.51901.","kmb.51903.","kmb.51911.","mcrl.51907.","mcrl.51921.","mcrl.51906.","mcrl.51920.","mcrl.51910.","nml.51904.","nml.51908.","nml.51909.","nml.51912.","pdpl.52009.","pdpl.52002.","pdpl.52011.","pdpl.52007.","pdpl.52008.","mbbd.52111.","mbbd.52105.","mbbd.52115.","mbbd.52117.","mdcl.61511."];
		
		$dt_names = ["588aec373969286917f3e6f0","5874cdeb523f4a9257aa9546","5874caa8523f4a1d56aa9546","5874caa8523f4a1d56aa9546","5874ce0e523f4a9557aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","5874d3d0523f4a125daa9546","5874d3d0523f4a125daa9546","5874d3d0523f4a125daa9546","5874d3d0523f4a125daa9546","5874d3d0523f4a125daa9546","58771b09523f4a4e4caa9546","58771b15523f4afc4baa9546","58771b15523f4afc4baa9546","58771b15523f4afc4baa9546","5732d8e4dbe7820a3d760e3b","5732d8e4dbe7820a3d760e3b","5732d8e4dbe7820a3d760e3b","5732d8e4dbe7820a3d760e3b","5732d8e4dbe7820a3d760e3b","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b46523f4a704caa9546","58771b46523f4a704caa9546","588b53fe396928932bf3e6f0","588b542d396928d709f3e6f5","588b542d396928d709f3e6f5","588b542d396928d709f3e6f5","588b542d396928d709f3e6f5","58771b15523f4afc4baa9546","58771b15523f4afc4baa9546","58771b15523f4afc4baa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b21523f4a344caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b2c523f4a604caa9546","58771b37523f4aed4baa9546","58771b37523f4aed4baa9546","58771b37523f4aed4baa9546","58771b37523f4aed4baa9546","58771b46523f4a704caa9546","58771b46523f4a704caa9546","58771b46523f4a704caa9546","58771b46523f4a704caa9546","58771b51523f4a024caa9546","58771b51523f4a024caa9546","588b53fe396928932bf3e6f0","588b53fe396928932bf3e6f0","588b53fe396928932bf3e6f0","588b53fe396928932bf3e6f0","588b53fe396928932bf3e6f0","588b542d396928d709f3e6f5","588b542d396928d709f3e6f5","58771b68523f4a4d4caa9546","58771b68523f4a4d4caa9546","58771b68523f4a4d4caa9546","58771b68523f4a4d4caa9546","5874ca27523f4ab455aa9546","5874ca27523f4ab455aa9546","5874ca27523f4ab455aa9546","5874ca27523f4ab455aa9546","5874ca27523f4ab455aa9546","588aec373969286917f3e6f0","5874cdeb523f4a9257aa9546","5874cdeb523f4a9257aa9546","5874cdeb523f4a9257aa9546","5874cdeb523f4a9257aa9546","5874caa8523f4a1d56aa9546","5874caa8523f4a1d56aa9546","5874cad7523f4add55aa9546","5874ca64523f4ace55aa9546","5874ca64523f4ace55aa9546","5874ca64523f4ace55aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","5874cf0f523f4a0658aa9546","58771af9523f4a4c4caa9546","58771af9523f4a4c4caa9546","58771af9523f4a4c4caa9546","58771af9523f4a4c4caa9546","58771b09523f4a4e4caa9546","58771b09523f4a4e4caa9546","58771b09523f4a4e4caa9546","58771b09523f4a4e4caa9546","58771b09523f4a4e4caa9546","588b5618396928b11af3e6f9","588b5618396928b11af3e6f9","588b5618396928b11af3e6f9","588b5618396928b11af3e6f9","5874d3d0523f4a125daa9546",];
		
		//count($unique_ids)-1
		for($ind_modi=0;$ind_modi <= count($unique_ids)-1;$ind_modi++){
			echo $ind_modi;
			echo "/////////////";
			echo $unique_ids[$ind_modi];
			
			$unique_id = $unique_ids[$ind_modi];
			$correct_id = $correct_ids[$ind_modi];
			$old_school_name = $old_school_names[$ind_modi];
			$correct_school_name = $new_schools_names[$ind_modi];
			$district = $districts[$ind_modi];
			$email = $emails[$ind_modi];
			$new_email = $new_emails[$ind_modi];
			$dt_name = $dt_names[$ind_modi];
			
			//=======================tswreis_chronic_cases==================
			
			$query = $this->mongo_db->whereLike("school_name",$old_school_name)->get("tswreis_chronic_cases");
			foreach ($query as $doc){
				if(isset($doc['school_name'])){
					$doc['school_name'] = $correct_school_name;
					$doc['student_unique_id'] = str_replace($unique_id,$correct_id,$doc['student_unique_id']);
				}
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("tswreis_chronic_cases");
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
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$unique_id)->get("healthcare2016531124515424");

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
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2016531124515424");
		}
		
		//shadow ==========================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$unique_id)->get("healthcare2016531124515424_shadow");

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
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2016531124515424_shadow");
		}
		

		//======================== attendence collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Attendence Details.Select School',$old_school_name)->get("healthcare201651317373988");
		
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
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare201651317373988");
		}
		}
		
		//shadow ========================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Attendence Details.Select School',$old_school_name)->get("healthcare201651317373988_shadow");
		
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
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare201651317373988_shadow");
		}
		}
		

		//======================== 'Sanitation report' collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page4.School Information.School Name',$old_school_name)->get("healthcare2016111212310531" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page4']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page4']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page4']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history']['last_stage']['submitted_by'])) && ($doc['history']["last_stage"]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2016111212310531" );
		}
		
		
		//shadow =====================================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page4.School Information.School Name',$old_school_name)->get("healthcare2016111212310531_shadow" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page4']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page4']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page4']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history'][0]['submitted_by'])) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare2016111212310531_shadow" );
		}
		
		
		//======================== 'Sanitation infrastructure' collection
		
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page6.School Information.School Name',$old_school_name)->get("healthcare20161114161842748" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page6']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page6']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page6']['School Information']['District'] = $district;	
		}
		
		if((isset($doc['history']["last_stage"]['submitted_by'])) && ($doc['history']["last_stage"]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history']["last_stage"]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare20161114161842748" );
		}
		
		
		//shadow =============================================================
		$query = $this->mongo_db->whereLike('doc_data.widget_data.page6.School Information.School Name',$old_school_name)->get("healthcare20161114161842748_shadow" );
		foreach ($query as $doc){
		if(isset($doc['doc_data']['widget_data']['page6']['School Information']['School Name'])){
			$doc['doc_data']['widget_data']['page6']['School Information']['School Name'] = $correct_school_name;
			$doc['doc_data']['widget_data']['page6']['School Information']['District'] = $district;
		}
		
		if((isset($doc['history'][0]['submitted_by'])) && ($doc['history'][0]['submitted_by'] == $email."hs#gmail.com")){
			$doc['history'][0]['submitted_by'] = $new_email."hs#gmail.com";
		}
		$doc['doc_data']['user_name'] = $new_email."hs#gmail.com";
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("healthcare20161114161842748_shadow" );
		}
		
		
		//======================== panacea_ehr_notes collection
		
		$query = $this->mongo_db->whereLike('uid',$unique_id)->get("panacea_ehr_notes" );
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
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea_ehr_notes" );
			}
		}
		
		//======================== panacea_messages collection
		
		$query = $this->mongo_db->whereLike('message',$unique_id)->get("panacea_messages" );
		foreach ($query as $doc){
		if(isset($doc['message'])){
			
			$doc['message'] = str_replace($unique_id,$correct_id,$doc['message']);
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea_messages" );
		}}
		
		
		//======================== panacea_health_supervisors collection
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereLike('email',$email)->get("panacea_health_supervisors" );
		foreach ($query as $doc){
		if(isset($doc['email'])){
			
			$doc['email'] = $new_email."hs@gmail.com";
			$doc['hs_addr'] = $correct_school_name;
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea_health_supervisors" );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}
		}
		
		
		//======================== panacea_schools collection
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereLike('school_name',$old_school_name)->get("panacea_schools" );
		foreach ($query as $doc){
		if(isset($doc['school_name'])){
			
			$doc['school_name'] = $correct_school_name;
			$doc['username'] = $correct_school_name;
			$doc['school_addr'] = $correct_school_name;
			$doc['dt_name'] = $dt_name;
		
		$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea_schools" );
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
		
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr1#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr1#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr2#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr2#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr3#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr3#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr4#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr4#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr5#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr5#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr6#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr6#gmail.com_docs" );
			}
			
		$query = $this->mongo_db->where("from_user",$email."hs#gmail.com")->get("panacea.dr7#gmail.com_docs" );
			foreach ($query as $doc){
				if(isset($doc['notification_param']['Unique ID'])){
					$doc['notification_param']['Unique ID'] = str_replace($unique_id,$correct_id,$doc['notification_param']['Unique ID']);
				}
				$doc["from_user"] = $new_email."hs#gmail.com";
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update("panacea.dr7#gmail.com_docs" );
			}
		
		//============================
			
			
		}
		
		exit();
		
		*/

		//=========================end of modification========
		
		//======================================================= END OF HS
		
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_classes' );
		return $query;
	}
	
	public function sectionscount() {
		$count = $this->mongo_db->count ( 'panacea_sections' );
		return $count;
	}
	public function get_sections($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_sections' );
		return $query;
	}
	public function symptomscount() {
		$count = $this->mongo_db->count ( 'panacea_symptoms' );
		return $count;
	}
	public function get_symptoms($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_symptoms' );
		return $query;
	}
	public function get_reports_ehr($ad_no) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page2.Personal Information.AD No", $ad_no )->limit(700)->get ( $this->screening_app_col );
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
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=>-1))->where ( "doc_data.widget_data.page1.Student Info.Unique ID", strtoupper($uid) )->get ( $this->request_app_col );
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( $this->collections['panacea_req_notes'] );
										
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->where ( "uid", strtoupper($uid) )->get ( $this->notes_col );
			
			
			
			if(isset($query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'])){
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['panacea_schools'] );
				
				if(count($school_details) > 0){
					$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['panacea_health_supervisors'] );
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
		$query = $this->mongo_db->insert ( 'panacea_diagnostics', $data );
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
		$query = $this->mongo_db->insert ( 'panacea_hospitals', $data );
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
		// //log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		$per_page = 1000;
		$loop = $count / $per_page;
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			// //log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($page,true));
			// //log_message("debug","oooooooooooooooooooooooooooooooooooooooooo".print_r($offset,true));
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
			// //log_message("debug","response=====1643==".print_r($response,true));
			// //log_message("debug","response=====1643==".print_r(count($response['result']),true));
			// //log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($result,true));
		}
		//
		// //log_message("debug","response=====1643==".print_r(count($response['result']),true));
		//log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $result, true ) );
		
		// $query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
		// return $query;
		return $result;
	}
	public function hospitalscount() {
		$count = $this->mongo_db->count ( 'panacea_hospitals' );
		return $count;
	}
	public function get_hospitals($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_hospitals' );
		foreach ( $query as $hospitals => $hospital ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $hospital ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $hospital ['dt_name'] )) {
				$query [$hospitals] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$hospitals] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
	}
	public function diagnosticscount() {
		$count = $this->mongo_db->count ( 'panacea_diagnostics' );
		return $count;
	}
	public function get_diagnostics($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_diagnostics' );
		foreach ( $query as $diagnostics => $dia ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $dia ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $dia ['dt_name'] )) {
				$query [$diagnostics] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$diagnostics] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function empcount() {
		$count = $this->mongo_db->count ( 'panacea_emp' );
		return $count;
	}
	public function get_emp($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_emp' );
		return $query;
	}
	public function insert_student_data($doc_data, $history, $doc_properties) {
		ini_set ( 'memory_limit', "2G" );
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
	public function get_all_symptoms($date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],false,$request_pie_status );
		
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
			//log_message("debug","iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii".print_r($doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'],true));
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
		
		//log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob_arr,true));
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
			//log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob,true));
			//log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		// ////log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
		
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
		
		/* echo print_r($query,true);
		exit; */
		
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
	
	
	public function get_absent_report_list($date = FALSE, $dt_name = "All", $school_name = "All")
	{
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select(array
											(
												'doc_data.widget_data.page1.Attendence Details.District',
												'doc_data.widget_data.page1.Attendence Details.Select School',
												'doc_data.widget_data.page1.Attendence Details.Attended',
												'doc_data.widget_data.page1.Attendence Details.Sick',
												'doc_data.widget_data.page1.Attendence Details.R2H',
												'doc_data.widget_data.page1.Attendence Details.Absent',
												'doc_data.widget_data.page2.Attendence Details.RestRoom'
											)
										)->whereLike('history.last_stage.time',$today_date)
										 ->get($this->absent_app_col);
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
				log_message('debug','ABSENT_DATA===1489=='.print_r($query, true));
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
					array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
			log_message('debug','ABSENT_DATA===1500=='.print_r($query, true));
			
		}
		log_message('debug','ABSENT_DATA===1503=='.print_r($query, true));
		return $query;
		
		
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
		// ////log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
		ini_set ( 'memory_limit', '1G' );
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		// ////log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
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
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"Sick");
				
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
		ini_set ( 'memory_limit', '10G' );
		
		switch ($type) {
			case "ABSENT REPORT" :
				
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
		//log_message('debug','drill_down_absent_to_students_load_ehr=====docs=====1356====='.print_r($docs,true));
		// ////log_message("debug","abbbbbbbbbbbbbbbbbbbbbbbbbb____________arrrrrrrrrrrrrrrrrrrrrrrrr".print_r($_id_array,true));
		// $query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereIn("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id_array)->get($this->screening_app_col);
		// ////log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
		return $docs;
	}
	public function get_drilling_attendance_districts_prepare_pie_array($query,$category) {
		$requests = [ ];
		
		$dist_list = $this->get_all_district();
		
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
				// ////log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
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
				////log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == $school_name) {
						array_push ( $search_result, $doc );
					}
				}
			}
			// ////log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $search_result as $doc ) {
				switch ($type) {
					case "ABSENT REPORT" :
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['Absent UID'] );
						////log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						////log_message("debug","mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm".print_r($UI_arr,true));
						
						break;
					case "SICK CUM ATTENDED" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick UID'] );
						// ////log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						
						break;
					
					case "REST ROOM IN MEDICATION" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom UID'] );
						// //log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						
						break;
					
					case "REFER TO HOSPITAL" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H UID'] );
						// //log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
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
	public function get_all_symptoms_docs($start_date, $end_date, $id_for_school = false,$request_pie_status = "All") {
		//ini_set ( 'max_execution_time', 0 );
		//ini_set('memory_limit', '100G');
		log_message('info','44444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444444.');
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
			
			if($request_pie_status == "All"){
				if($doc['doc_data']['widget_data']['page2']['Review Info']['Status'] != "Cured"){
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
				}
			}
			}else if($request_pie_status == "Cured"){
				if($doc['doc_data']['widget_data']['page2']['Review Info']['Status'] == "Cured"){
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
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
			log_message("debug","today_date today_date======2165".print_r($today_date,true));
			log_message("debug","end_date end_date======2166".print_r($end_date,true));
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
	public function get_all_requests($date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],false,$request_pie_status );
		
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
				log_message("debug","query query======2268".print_r($query,true));
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
			log_message("debug","query query======2286".print_r($query,true));
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
		//echo print_r(count($query),true);
		//exit();
		foreach ( $query as $report ) {
			$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			log_message("debug","status status======2308".print_r($status,true));
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
			
			if ($status == "Initiated") {
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
	
		public function get_all_requests_docs($start_date, $end_date, $type = false, $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		
			//===============================================degfdvbdcvgsydugvuhysd
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111--".print_r($start_date,true));
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111--".print_r($end_date,true));
		
		if($request_pie_status == "All")
		{
			$and_merged_array_condition = array();
			if ($type == "Initiated") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Status' => $type,
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")
				);
			}else if ($type == "Screening") {
				
				$and_merged_array_condition = array (
					'history.0.submitted_user_type' => "PADMIN",
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")
				);
				
				
			} else if ($type == "Normal") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Normal",
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")				
				);
			} else if ($type == "Emergency") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency",
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")
				);
				
				
			} else if ($type == "Chronic") {
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic",
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")
				);
			} else {
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Status' => $type,
					"doc_data.widget_data.page2.Review Info.Status" => array ('$ne' => "Cured")
				);
				
			}
		}
		else if ($request_pie_status == "Cured"){
			
			$and_merged_array_condition = array();
			if ($type == "Initiated") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Status' => $type,
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
			}else if ($type == "Screening") {
				
				$and_merged_array_condition = array (
					'history.0.submitted_user_type' => "PADMIN",
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
				
				
			} else if ($type == "Normal") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Normal",
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
			} else if ($type == "Emergency") {
				
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency",
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
				
				
			} else if ($type == "Chronic") {
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic",
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
			} else {
				$and_merged_array_condition = array (
					'doc_data.widget_data.page2.Review Info.Status' => $type,
					"doc_data.widget_data.page2.Review Info.Status" => "Cured"
				);
				
			}
			
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111--".print_r($and_merged_array_condition,true));
		
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
		array_push ( $and_merged_array_0, $and_merged_array_condition );
		
		
		array_push ( $and_merged_array_1, $his_1_lte );
		array_push ( $and_merged_array_1, $his_1_gte );
		array_push ( $and_merged_array_1, $and_merged_array_condition );

		array_push ( $and_merged_array_last, $his_last_lte );
		array_push ( $and_merged_array_last, $his_last_gte );
		array_push ( $and_merged_array_last, $and_merged_array_condition );
		
		
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
			
		
		
		
		// if ($type == "Initiated") {
			// $query = $this->mongo_db->where ( array (
					// 'doc_data.widget_data.page2.Review Info.Status' => $type 
			// ) )->get ( $this->request_app_col );
		// }else if ($type == "Screening") {
			// $query = $this->mongo_db->where ( array (
					// 'history.0.submitted_user_type' => "PADMIN" 
			// ) )->get ( $this->request_app_col );
		// } else if ($type == "Normal") {
			// $query = $this->mongo_db->where ( array (
					// 'doc_data.widget_data.page2.Review Info.Request Type' => "Normal" 
			// ) )->get ( $this->request_app_col );
		// } else if ($type == "Emergency") {
			// $query = $this->mongo_db->where ( array (
					// 'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency" 
			// ) )->get ( $this->request_app_col );
		// } else if ($type == "Chronic") {
			// $query = $this->mongo_db->where ( array (
					// 'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic" 
			// ) )->get ( $this->request_app_col );
		// } else {
			// $query = $this->mongo_db->whereLike ( 'doc_data.widget_data.page2.Review Info.Status', $type )->get ( $this->request_app_col );
		// }
		
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
		
		// $result = [ ];
		// foreach ( $query as $doc ) {
			
			// foreach ( $doc ['history'] as $date ) {
				// $time = $date ['time'];
				
				// if (($time <= $start_date) && ($time >= $end_date)) {
					// array_push ( $result, $doc );
					// break;
				// }
			// }
		// }
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111qqq--".print_r($query,true));
		//$query = $result;
		return $query;
	}
	
	
	public function get_all_requests_docs_old($start_date, $end_date, $type = false, $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii");
		
		
		if ($type == "Initiated") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Status' => $type 
			) )->get ( $this->request_app_col );
		}else if ($type == "Screening") {
			$query = $this->mongo_db->where ( array (
					'history.0.submitted_user_type' => "PADMIN" 
			) )->get ( $this->request_app_col );
		} else if ($type == "Normal") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Normal" 
			) )->get ( $this->request_app_col );
		} else if ($type == "Emergency") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency" 
			) )->get ( $this->request_app_col );
		} else if ($type == "Chronic") {
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic" 
			) )->get ( $this->request_app_col );
		} else {
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj");
			$query = $this->mongo_db->where ( 'doc_data.widget_data.page2.Review Info.Status', $type )->get ( $this->request_app_col );
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvv11111111111111111111111111111111111");
		$doc_query = array ();
		if ($school_name == "All") {
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
			if ($dt_name != "All") {
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
				foreach ( $query as $doc ) {
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvcccccccccccccccccccccccccccccccccccccccccccccc");
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv222222222222222222222222222222222222222");
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2.Personal Information.District' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvdddddddddddddddddddddddddddddddddddddddd");
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvv33333333333333333333333333333333333333333");
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvveeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvffffffffffffffffffffffffffffffffffffffffff");
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv222222222222222222222222222222222222222");
			}
		} else {
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvgggggggggggggggggggggggggggggggggggggg");
			foreach ( $query as $doc ) {
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh");
				////log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv33333333333333333333333333333");
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2.Personal Information.School Name' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv33333333333333333333333333333333333333333333333");
		$result = [ ];
		foreach ( $query as $doc ) {
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv44444444444444444444444444444444444444444444444444");
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv5555555555555555555555555555555555555555555555555");
				if (($time <= $start_date) && ($time >= $end_date)) {
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv66666666666666666666666666666666666666666666");
					array_push ( $result, $doc );
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv777777777777777777777777777777777777777777");
					break;
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv8888888888888888888888888888888888888888888");
				}
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn vvvvvvvvvvvvvvvvvvvvv9999999999999999999999999999999999");
			}
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
		$query = $result;
		return $query;
	}
	public function drilldown_request_to_districts($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 2222222222222222222222222222222222222222222222222222222222");
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		//========================================
		
		
		
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query_temp = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],false,$request_pie_status );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query_temp as $doc ) {
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
				$query_temp = $doc_query;
			} else {
			}
		} else {
			foreach ( $query_temp as $doc ) {
				
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
			$query_temp = $doc_query;
		}
		
		if ($type == "Device Initiated") {
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				
					if ($status == "Initiated") {
						
						if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						} else {
							array_push ( $query, $report );
						}
					} else {
						array_push ( $query, $report );
					}	
				} 
			}
		
		} else if ($type == "Screening Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {				
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						}else if(($user_type == "PADMIN")){
							array_push ( $query, $report );
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
				
			}
		} else if ($type == "Web Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							array_push ( $query, $report );							
						}else if(($user_type == "PADMIN")){
							//$screening_initiated ++;
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Normal") {
					array_push ( $query, $report );	
				}
			}	
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Emergency") {
					array_push ( $query, $report );	
				}
			}
			
			
			
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Chronic") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Prescribed") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Prescribed") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Under Medication") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Under Medication") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Follow-up") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Follow-up") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Cured") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Cured") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Hospitalized") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Hospitalized") {
					array_push ( $query, $report );	
				}
			}
			
			
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name,$request_pie_status );
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
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 333333333333333333333333333333333333333333333333333333");
		return $final_values;
	}
	public function get_drilling_request_schools($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 55555555555555555555555555555555555555555555555555555");
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
		
		$query_temp = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],false,$request_pie_status );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query_temp as $doc ) {
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
				$query_temp = $doc_query;
			} else {
			}
		} else {
			foreach ( $query_temp as $doc ) {
				
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
			$query_temp = $doc_query;
		}
		
		if ($type == "Device Initiated") {
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				
					if ($status == "Initiated") {
						
						if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						} else {
							array_push ( $query, $report );
						}
					} else {
						array_push ( $query, $report );
					}	
				} 
			}
		
		} else if ($type == "Screening Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {				
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						}else if(($user_type == "PADMIN")){
							array_push ( $query, $report );
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
				
			}
		} else if ($type == "Web Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							array_push ( $query, $report );							
						}else if(($user_type == "PADMIN")){
							//$screening_initiated ++;
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Normal") {
					array_push ( $query, $report );	
				}
			}	
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Emergency") {
					array_push ( $query, $report );	
				}
			}
			
			
			
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Chronic") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Prescribed") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Prescribed") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Under Medication") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Under Medication") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Follow-up") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Follow-up") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Cured") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Cured") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Hospitalized") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Hospitalized") {
					array_push ( $query, $report );	
				}
			}
			
			
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name,$request_pie_status );
		}
		
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
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 666666666666666666666666666666666666666");
		return $final_values;
	}
	public function get_drilling_request_students($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 8888888888888888888888888888888888888888888888888");
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = $obj_data ['1'];
		
		// //log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		
		$query_temp = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'],false,$request_pie_status );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query_temp as $doc ) {
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
				$query_temp = $doc_query;
			} else {
			}
		} else {
			foreach ( $query_temp as $doc ) {
				
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
			$query_temp = $doc_query;
		}
		
		if ($type == "Device Initiated") {
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				
					if ($status == "Initiated") {
						
						if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						} else {
							array_push ( $query, $report );
						}
					} else {
						array_push ( $query, $report );
					}	
				} 
			}
		
		} else if ($type == "Screening Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {				
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							//$web_initiated ++;
						}else if(($user_type == "PADMIN")){
							array_push ( $query, $report );
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
				
			}
		} else if ($type == "Web Initiated") {
			//$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Initiated") {
					if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
						$user_type = $report ['history'] [0] ['submitted_user_type'];
						if (($user_type == "CCUSER")) {
							array_push ( $query, $report );							
						}else if(($user_type == "PADMIN")){
							//$screening_initiated ++;
						} else {
							//$device_initiated ++;
						}
					} else {
						//$device_initiated ++;
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name,$request_pie_status );
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Normal") {
					array_push ( $query, $report );	
				}
			}	
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Emergency") {
					array_push ( $query, $report );	
				}
			}
			
			
			
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
				if ($request_type == "Chronic") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Prescribed") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Prescribed") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Under Medication") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Under Medication") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Follow-up") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Follow-up") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Cured") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Cured") {
					array_push ( $query, $report );	
				}
			}
			
			
		}else if ($type == "Hospitalized") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			//$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name,$request_pie_status );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
				if ($status == "Hospitalized") {
					array_push ( $query, $report );	
				}
			}
			
			
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name,$request_pie_status );
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
		$student_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn 9999999999999999999999999999999999999");
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && count ( $doc ) > 0) {
				//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
				$school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if ($school == $school_name) {
					//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
					array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
				}
			}
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn ccccccccccccccccccccccccccccccccccccccccccccccccccccccc");
		return $matching_docs;
	}
	public function get_drilling_request_students_docs($_id_array) {
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
		$docs = [ ];
		//log_message ( "debug", "rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr" . print_r ( $_id_array, true ) );
		if (isset ( $_id_array ) && ! empty ( $_id_array ) && count ( $_id_array ) > 0) {
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		}
		//log_message("debug","innnnnnnnnnnnnnnnnnnnnn fn ffffffffffffffffffffffffffffffffffffffffffffffffffffffff");
		return $docs;
	}
	
	// ----------------------------------------------------------------------
	
	// ===================================id=======================+==========================
	public function drilldown_identifiers_docs($start_date, $end_date, $type) {
		ini_set ( 'memory_limit', '10G' );
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
	public function drilldown_identifiers_to_districts($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		//log_message ( 'debug', 'drilldown_identifiers_to_districts==dddddddddddddddddddddddddddddddddddddddd----' . print_r ( $dt_name, true ) );
		//log_message ( 'debug', 'ssssssssssssssssssssssssssssssssssssssss----' . print_r ( $school_name, true ) );
		//log_message ( 'debug', 'dttttttttttttttttttttttttttttttttttttttt----' . print_r ( $data, true ) );
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '10G' );
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		//log_message ( 'debug', 'drilldown_identifiers_to_districts=====type----' . print_r ( $type, true ) );
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type,$request_pie_status );
		
		//log_message ( 'debug', 'drilldown_identifiers_to_districts----query-----' . print_r ( $query, true ) );
		
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
			} 
			else 
			{
				// ADDED BY SELVA
				foreach ($query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					//log_message ( 'debug', 'drilldown_identifiers_to_districts----unique_id-----' . print_r ( $unique_id, true ) );
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					//log_message ( 'debug', 'drilldown_identifiers_to_districts----screening_doc-----' . print_r ( $screening_doc, true ) );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						
							array_push ( $doc_query, $doc );
					}
				}
				//log_message ( 'debug', 'drilldown_identifiers_to_districts----doc_query-----' . print_r ( $doc_query, true ) );
				$query = $doc_query;
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
			//log_message ( 'debug', 'unique_id----' . print_r ( $unique_id, true ) );
			$doc = $this->mongo_db->where/*Like*/ ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
		    //log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
			//log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r(count($doc),true));
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
	public function get_drilling_identifiers_schools($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		log_message('info','222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222.');
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		log_message('info','333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333.');
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type,$request_pie_status );
		
		//=====new change===================================
		
		
		
		
		
		//==================================================
		
		
		log_message('info','6666666666666666666666666666666666666666666666666666666666666666666666666666666.');
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
				ini_set ( 'memory_limit', '100G' );
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
			$doc = $this->mongo_db->where( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
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
		log_message('info','777777777777777777777777777777777777777777777777777777777777777777777777777777777.');
		return $final_values;
	}
	public function get_drilling_identifiers_students($data, $date = false, $request_duration = "Daily", $dt_name = "All", $school_name = "All",$request_pie_status = "All") {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = $obj_data ['1'];
		
		// //log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type,$request_pie_status );
		ini_set ( 'memory_limit', '100G' );
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
		// //log_message ( "debug", "innnnnnnnnnnnnnnnnnnnnnn stage 111111111111111111111111111111111--------------------" . print_r ( $dates, true ) );
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
			// //log_message("debug","responseeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=====1665==".print_r($response,true));
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
		
		// ////log_message("debug","response=====1665==".print_r($merged_array,true));
		
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
		
		// ////log_message("debug","response=====1665==".print_r($merged_array,true));
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
		
		// ////log_message("debug","response=====1748==".print_r($response,true));
		// ////log_message("debug","response=====1749==".print_r(count($response['result']),true));
		
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
			// //log_message("debug","deeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee===".print_r($response,true));
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
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
		
		// //log_message("debug","pppppppppppppppppppppppppppppppp123=====".print_r($requests,true));
		
		return $requests;
		
		// ============================================================end of stage 1 =======================================
	}
	private function screening_pie_data_for_stage2($dates) {
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '10G' );
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
		
		// //log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddlist--------'.print_r($dist_list,true));
		foreach ( $dist_list as $dist ) {
			// //log_message('debug','ddddddddddddddddddddddddddddddddddddddd--------------'.print_r(strtolower($dist["dt_name"]),true));
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
	public function update_screening_collection($date, $screening_duration) {
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
				
				$requests = $this->screening_pie_data_for_stage5 ( $temp_dates );
				$pie_data ['pie_data'] ['stage5_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage4_new ( $requests );
				$pie_data ['pie_data'] ['stage4_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage3_new ( $requests );
				$pie_data ['pie_data'] ['stage3_pie_vales'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2_new ( $requests );
				$pie_data ['pie_data'] ['stage2_pie_vales'] = $requests;
				
				//log_message ( "debug", "before stagesssssssssssssssssssssssssss--------------------" );
				$requests = $this->screening_pie_data_for_stage1_new ( $requests );
				$pie_data ['pie_data'] ['stage1_pie_vales'] = $requests;
				
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
		
		
		//ini_set ( 'memory_limit', '10G' );
				// $query = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name',"TSWREIS SANGAREDDY(G),MEDAK")->get($this->screening_app_col);
				// $array = [];
				// foreach ($query as $doc){
						// if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])) && is_array($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])){
							
							// array_push($array,$doc['history']['last_stage']['time']);			
				// }}
				// array_unique($array);
				// //log_message('debug','dtaesssssssssssssssssssssssssssssssssssssssssssssssss======='.print_r($array,true));
		
		
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
						  ////log_message('debug','searchingdoccccccccccccccccccccccccccccccccc======='.print_r($doc['doc_data']['widget_data'],true));
				$query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				}}}}}   */
				
				
				// ini_set ( 'memory_limit', '10G' );
				// $query = $this->mongo_db->get($this->screening_app_col);
				// foreach ($query as $doc){
						// if((isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])) && (isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up'])) && (isset($doc['doc_data']['widget_data']['page6']['With Glasses'])) && (isset($doc['doc_data']['widget_data']['page6']['Without Glasses'])) && (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness'])) && (isset($doc['doc_data']['widget_data']['page7'][' Auditory Screening'])) && (isset($doc['doc_data']['widget_data']['page8']['Dental Check-up']))){
							// $doc['doc_data']['widget_data']['page3'] = [];
						  
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// }}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		// ================================================== for generated analytics
		//ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
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
		
		//echo print_r($result,true);
		//exit;
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
		// //log_message("debug","ppppppppppppppppppppppppppppppppscreenenenenene=====".print_r($pie_data,true));
		
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
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
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
		// ////log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
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
				// ////log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWRS-CHITKUL,MEDAK")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = "TSWREIS CHITKUL(G),MEDAK";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// ////log_message("debug","iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================");
				
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
	public function get_drilling_screenings_schools1($data) {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
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
	public function get_drilling_screenings_students1($data) {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
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
			$query_request = $this->mongo_db->select(array("doc_data.widget_data","doc_data.notes_data","doc_data.external_attachments","doc_properties","history"))->orderBy(array("history.0.time"=> -1))->where( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			
			
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
		) )->get ( $this->collections ['panacea_health_supervisors'] );
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
		) )->get ( $this->collections ['panacea_cc'] );
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
		) )->get ( $this->collections ['panacea_doctors'] );
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
			ini_set ( 'memory_limit', '10G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->get ( $this->screening_app_col );
			
			//ini_set ( 'memory_limit', '1G' );
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
			// //log_message("debug","111111111111111111111111111111111111111".print_r(strtoupper($dist_name),true));
			ini_set ( 'memory_limit', '10G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->where ( "doc_data.widget_data.page2.Personal Information.District", strtoupper($dist_name) )->get ( $this->screening_app_col );
			//ini_set ( 'memory_limit', '1G' );
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
					'doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => 1 
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
			if (strtolower ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) === strtolower ( $dist_id )) {
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
		) )->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	// MODIFIED BY SELVA FOR TESTING
	public function get_absent_school_details($school,$date) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike("history.last_stage.time",$date)->whereLike ( "doc_data.widget_data.page1.Attendence Details.Select School", $school )->get ( $this->absent_app_col );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	// VIKAS 
	/*public function get_absent_school_details($school) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( "doc_data.widget_data.page1.Attendence Details.Select School", $school )->get ( $this->absent_app_col );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}*/
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
	
		$response = $this->data = $this->panacea_common_model->messaging($message);
		//$this->data = "";
	
		$this->output->set_output($response);
	}
	public function groupscount() {
		$count = $this->mongo_db->count ( 'panacea_chat_groups' );
		return $count;
	}
	public function get_groups($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_chat_groups' );
		return $query;
	}
	
	public function get_all_groups() {
		$query = $this->mongo_db->get ( 'panacea_chat_groups' );
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
		$query = $this->mongo_db->get ( 'panacea_chat_groups' );
		//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13631====='.print_r($query,true));
		foreach($query as $data)
		{
			$group_name = $data['group_name'];
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13635====='.print_r($group_name,true));
			$where_array = array('group_name'=>$group_name,'list_of_users'=>array('$in'=>array($user_email)));
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13637====='.print_r($where_array,true));
			$grps = $this->mongo_db->where($where_array)->get ( 'panacea_chat_groups_users' );
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13639====='.print_r($grps,true));
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13640====='.print_r(count($grps),true));
			if(isset($grps) && !empty($grps))
			{
				array_push($accessible_chat_rooms,$query);
			}
			
		}
		//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13644====='.print_r($accessible_chat_rooms,true));
		return $accessible_chat_rooms;
	}
	
	public function get_all_admin_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_admins'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_health_supervisors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_cc_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_cc'] );
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
		$query = $this->mongo_db->insert($this->collections['panacea_messages'],$data);
		
		if($query){
			$response['error'] = false;			
			$response['message'] = $data;
		}else{
			$response['error'] = true;
			$response['message'] = 'Failed send message ' . $stmt->error;
		}
		
		return $response;
	}
	public function get_messages($msg_id)
	{
		$query = $this->mongo_db->where("chat_room_id",$msg_id)->get($this->collections['panacea_messages']);
		return $query;
	}
	public function get_user_by_email($name, $email){
				
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		//log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=========.'.print_r($email,true));
		$user = $this->mongo_db->where("email",$email)->get($this->collections['panacea_admins']);
		//log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu=========.'.print_r($user,true));
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
		$id_exists = $this->mongo_db->where("user_id",$login)->get($this->collections['panacea_users_gcm']);
		$data = array(
			"user_id" => $login,
			"gcm_registration_id" => $gcm_registration_id
		);
		if($id_exists){
			$query = $this->mongo_db->where("user_id",$login)->set($data)->get($this->collections['panacea_users_gcm']);
		}else{
			$query = $this->mongo_db->insert($this->collections['panacea_users_gcm'],$data);
		}
		return $query;
	}
	
	public function get_sanitation_report_app()
	{
	  $query = $this->mongo_db->select(array('app_template'))->where('_id',$this->sanitation_app_col)->get($this->collections['records']);
	  return $query[0]['app_template'];
	
	}
	
	public function get_sanitation_report_data_with_date($date,$school_name)
	{
	    if ($date) {
			$selected_date = $date;
		} else {
			$selected_date = $this->today_date;
		}
		
		$this->mongo_db->whereLike('doc_data.widget_data.page4.Declaration Information.Date:',$selected_date)->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$selected_date,'doc_data.widget_data.page4.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_app_col);
		if($query)
			return $query;
		else
			return FALSE;
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
	 
	 //log_message("debug","order_by sanitation==============16285".print_r($order_by,true));
	 $query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.external_attachments'),array())
	 ->orderBy(array('history.last_stage.time' => -1))->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$date,$search_criteria=>$opt))->get('healthcare2016111212310531');
	 
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
								 log_message("debug","path attachment_list===========16316".print_r($path,true));
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
		
		// $schools_list = $this->get_all_schools();
		
		// foreach($schools_list as $school_data)
		// {
			// array_push($all_schools_district,$school_data['dt_name']);
			// array_push($all_schools_name,$school_data['school_name']);
		// }
		
		// $all_schools['district'] = $all_schools_district; 
		// $all_schools['school']   = $all_schools_name; 
		
		// if ($date) {
			// $today_date = $date;
		// } else {
			// $today_date = $this->today_date;
		// }
		
		// $query = $this->mongo_db->select ( array (
				// "doc_data.widget_data" 
		// ) )->whereLike ( 'doc_data.widget_data.page4.Declaration Information.Date:',$today_date )->get ( $this->sanitation_app_col );
		
		// foreach ( $query as $doc ) {
			    // if(!in_array($doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$submitted_school_name))
				// {
					// array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] );
					// array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'] );
		        // }
		// }
		
		// $submitted_schools['district']     = $submitted_school_district;
		// $submitted_schools['school']       = $submitted_school_name;
		// $not_submitted_schools['district'] = array();
		// $not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		// foreach($not_submitted_schools['school'] as $index => $school_name)
		// {
		   // $dist_array    = explode(",",$school_name);
		   // $dist_array[1] = strtolower($dist_array[1]);
		   // array_push($not_submitted_dist,ucfirst($dist_array[1]));
		// }
		// $not_submitted_schools['district']   = $not_submitted_dist;
		// $schools_data['submitted']     		 = $submitted_schools;
		// $schools_data['submitted_count']     = count($submitted_schools['school']);
		// $schools_data['not_submitted'] 		 = $not_submitted_schools;
		// $schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		
		
		//
		
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
		
		log_message('debug','$schools_data=====get_absent_pie_schools_data=====716=='.print_r($query,true));
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
		
		//
		
		return $schools_data;
	}
	
	public function insert_ehr_note($post)
	{
		$token = $query = $this->mongo_db->insert($this->notes_col,$post);
	
		return $token;
	}
	
	public function fetch_insert_ehr_note($post)
	{
		$token = $query = $this->mongo_db->where(array("datetime"=> $post['datetime'],"username"=> $post['username'],"uid"=> $post['uid'],"note"=> $post['note']))->get($this->notes_col);
	
		return $token;
	}
	
	public function delete_ehr_note($doc_id)
	{
		$query = $this->mongo_db->where ( array (
				"_id" => new MongoId ( $doc_id ) 
		) )->delete ( $this->notes_col );
	
		return $query;
	}

    public function tswreis_chronic_cases_count()
    {
     $query = $this->mongo_db->get($this->collections['tswreis_chronic_cases']);
	 return count($query);
    }
	
	function get_chronic_cases_model($limit, $page)
	{
	    $offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->get($this->collections['tswreis_chronic_cases']);
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
		$query = $this->mongo_db->get($this->collections['tswreis_chronic_cases']);
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
		$query = $this->mongo_db->select(array('student_unique_id','case_id','scheduled_months','school_name'),array())->getWhere($this->collections['tswreis_chronic_cases'],array('followup_scheduled'=>'true'));
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
	

	
	public function insert_request_note($post)
	{
		$query_request = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->get ( $this->collections['panacea_req_notes'] );
		
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
		
		$is_notes = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->count( $this->collections ['panacea_req_notes'] );
		
		if($is_notes > 0){
			$token = $this->mongo_db->where ( "req_doc_id", $post ['doc_id'] )->set($query_request[0])->update( $this->collections ['panacea_req_notes'] );
		}else{
			$token = $this->mongo_db->insert( $this->collections ['panacea_req_notes'], $query_request[0]);
		}
	
	   // log_message('debug','notessssssssssssssssspost===token==========================='.print_r($token,true));

		return $token;
	}

	// ------------------------------------------------------------------------
	 
	/**
	* Helper : Update note content ( request notes )
	*
	* @param  string  $doc_id   Request document id
	* @param  string  $note_id  Note id
	* @param  string  $note     Note
	*
	* @return bool
	*
	* @author Selva
	*/

	public function update_request_note($doc_id,$note_id,$note)
	{
       $query = array('req_doc_id'=>$doc_id,'notes_data'=>array('$elemMatch'=>array('note_id'=>$note_id)));

       $update = array('$set'=>array('notes_data.$.note'=>$note));

       $response = $this->mongo_db->command(array( 
		'findAndModify' => 'panacea_req_notes',
		'query'         => $query,
		'update'        => $update
	    ));
		
		return $response['ok'];
	}
	
	public function delete_request_note($post)
	{
		$query_request = $this->mongo_db->where ( "doc_properties.doc_id", $post ['doc_id'] )->get ( $this->request_app_col );
		
		foreach($query_request[0]['doc_data']['notes_data'] as $note => $note_data){
			if($note_data['note_id'] == $post ['note_id']){
				unset($query_request[0]['doc_data']['notes_data'][$note]);
			}
		}
		
		$token = $this->mongo_db->where ( "doc_properties.doc_id", $post ['doc_id'] )->set($query_request[0])->update( $this->request_app_col );
	
		return $token;
	}
	
	
	public function get_chronic_request() {
		
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
		
		$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		
		$dist_list = [ ];
		
		foreach ( $query as $identifiers ) {
			
			$retrieval_list = array ();
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			log_message ( 'debug', 'unique_id----' . print_r ( $unique_id, true ) );
			$doc = $this->mongo_db->where/*Like*/ ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
		    log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
			log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r(count($doc),true));
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
	
	public function drilldown_chronic_request_to_schools($data, $status_type) {
		
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		$search_param_2 = $params[2];
		
		$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		
		$dist = strtolower ( $obj_data [1] );
		
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
	
	public function drilldown_chronic_request_to_students($data,$status_type) {
		
		
		$query = [ ];
		
		$obj_data = json_decode ( $data, true );
		$search_param_1 = $obj_data[0];
		$params = explode(" / ", $search_param_1);
		$search_param_1 = $params[1];
		$search_param_2 = $params[2];
		
		$query = $this->get_request_docs_params($search_param_1,$search_param_2,$status_type);
		
		$school_name = $obj_data ['1'];
		
		
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

			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $not_cured );
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

			$and_merged_array = array();
			
			array_push ( $and_merged_array, $init_request );
			array_push ( $and_merged_array, $symptoms );
			array_push ( $and_merged_array, $not_cured );
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
	
		$query = $this->mongo_db->limit(1)->get ( $this->collections ['rhso_news_feed'] );
	
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
		$query = $this->mongo_db->where('_id', new MongoId($doc['_id']))->set($doc)->update('healthcare2016531124515424');
		unset($doc['_id']);
		$query = $this->mongo_db->insert('healthcare2016531124515424_shadow',$doc);
		return $query;
	}
	
	function get_workflow_stage_details($app_id,$collection,$select){
		$query = $this->mongo_db->where('_id', $app_id)->select($select)->get($collection);
		return $query[0];
	}
	
	/*
	*Fetchinhg BMI value with Unique id
	*author Naresh
	
	*/ 
	
	public function get_student_bmi_values($unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('healthcare2017617145744625');
		log_message("debug","query==========12576".print_r($query,true));
		
		if($query)
			return $query;
	    else
			return FALSE;
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
	
	function unique_id_check($unique_id){
		
		$and_merged_array = array ();
		
		$unique_id = array (
				"doc_data.widget_data.page1.Personal Information.Hospital Unique ID" => "HYD_61602_10021"
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
	
	public function get_reports_for_extend($uid) {
		
		//$query_request = $this->mongo_db->orderBy(array("history.0.time"=>-1))->where ( array("doc_data.widget_data.page1.Student Info.Unique ID" => strtoupper($uid), ) )->get ( $this->request_app_col );
		//return $result;
		
		$and_merged_array = array ();
					
		$last_stage_exists = array (
				"history.last_stage" => array (
						'$exists' => true 
				) 
		);
		$uid = array (
				"doc_data.widget_data.page1.Student Info.Unique ID" => strtoupper($uid)
		);
		
		array_push ( $and_merged_array, $uid );
		array_push ( $and_merged_array, $last_stage_exists );
		
		$result = [ ];
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"doc_properties" => true,
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
			$query = [];
			if($response['ok']){
				$query['docs_requests'] = $response["result"];
				return $query;
			}else{
				return $query;
			}
	}
	
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: GET RHSO Submitted Forms Data ( as District Wise )
	 *
	 *@author Bhanu 
	 */
	
	public function get_sanitation_inspection_model(/* $rhso_reports_span,  */$district, $school_name)
	{
		$this->mongo_db->orderBy(array("history.last_stage.time"=>-1));
		$sanitation_inspection = $this->mongo_db->limit(1)->select(array('doc_data.School Information','doc_data.widget_data'))->where(array('doc_data.School Information.district_name' => $district, 'doc_data.School Information.school_name' => $school_name))->get('healthcare20171226174552433');
		log_message('debug','rhso_submitted_reports_model===17761=='.print_r($sanitation_inspection, true));
		
		
		
		if($sanitation_inspection)
		{
		return $sanitation_inspection;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_food_hygiene_inspection_model($district)
	{
		$food_hygiene = $this->mongo_db->select(array('doc_data.School Information','doc_data.widget_data'))->whereLike('doc_data.School Information.district' , $district)->get('healthcare20171221112544749');
		if($food_hygiene)
		{
		return $food_hygiene;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_rhso_checklist_model($district)
	{
		$rhso_checklist = $this->mongo_db->select(array('doc_data.School Information','doc_data.widget_data'))->whereLike('doc_data.School Information.district' , $district)->get('healthcare20171227173441869');
		if($rhso_checklist)
		{
		return $rhso_checklist;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_civil_infrastructure_inspection_model($district)
	{
		$civil_infrastructure = $this->mongo_db->select(array('doc_data.School Information','doc_data.widget_data'))->whereLike('doc_data.School Information.district' , $district)->get('healthcare20171227153054237');
		if($civil_infrastructure)
		{
		return $civil_infrastructure;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_initaite_requests_count_today_date($today_date,$district_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->where(array('doc_data.widget_data.page1.Student Info.District.field_ref'=>$district_name))->get("healthcare2016531124515424_static_html");
		//log_message('error','document--------------------19480'.print_r($document,true));
		if(count($document)>0){
			return $document;
		}
	}

	public function get_normal_requests_count_today_date($today_date,$district_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->where(array('doc_data.widget_data.page1.Student Info.District.field_ref'=>$district_name))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}
	public function get_emergency_requests_count_today_date($today_date,$district_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->where(array('doc_data.widget_data.page1.Student Info.District.field_ref'=>$district_name))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}
	public function get_chronic_requests_count_today_date($today_date,$district_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->where(array('doc_data.widget_data.page1.Student Info.District.field_ref'=>$district_name))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}
	public function get_doctors_response_count_today_date($today_date,$district_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.1.time" , $today_date)->get($this->request_app_col);
		$document = $this->mongo_db->select(array('history'))->whereLike("history.1.time" , $today_date)->where(array('doc_data.widget_data.page1.Student Info.District.field_ref'=>$district_name))->get("healthcare2016531124515424_static_html");
		
		return $document;
	}

	public function get_show_ehr_details($request_type,$date,$school_name,$district_name)
	{
		
		$data['all_request'] = $this->mongo_db->whereLike('history.0.time',$date)->get('healthcare2016531124515424_static_html');
		if($school_name== "All")
		{
			if($request_type == "Normal" )
			{
			$normal_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.District.field_ref' => $district_name))->get('healthcare2016531124515424_static_html');
				return $normal_request_all;
			
			}
			else if($request_type == "Emergency")
			{ 
			$emergency_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.District.field_ref' => $district_name))->get('healthcare2016531124515424_static_html');

			return $emergency_request_all;
				
			}
			else if($request_type == "Chronic")
			{
				$chronic_request_all = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.District.field_ref' => $district_name))->get('healthcare2016531124515424_static_html');
				return $chronic_request_all;
				
			}
		}else{
			
			if($request_type == "Normal")
			{
			$normal_request = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare2016531124515424_static_html');
				return $normal_request;
			
			}
			
			else if($request_type == "Emergency")
			{ 
			$emergency_request = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare2016531124515424_static_html');

			return $emergency_request;
				
			}
			else if($request_type == "Chronic")
			{
				$chronic_request = $this->mongo_db->select(array('doc_data.widget_data.page1'),array('_id'))->whereLike('history.0.time',$date)->where(array('doc_data.widget_data.page2.Review Info.Request Type' => $request_type, 'doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get('healthcare2016531124515424_static_html');
				return $chronic_request;
				
			}
		}
		
		
	}

	public function get_initaite_requests_count($today_date,$school_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}

	public function get_normal_requests_count($today_date,$school_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name,
	'doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}
	public function get_emergency_requests_count($today_date,$school_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name,
			'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}
	public function get_chronic_requests_count($today_date,$school_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.0.time" , $today_date)->get($this->request_app_col);
		
		$document = $this->mongo_db->select(array('history'))->
		where(array('doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name,
			'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->whereLike("history.0.time" , $today_date)->get("healthcare2016531124515424_static_html");
		
		if(count($document)>0){
			return $document;
		}
	}

	
	public function get_doctors_response_count($today_date,$school_name)
	{
		//$document = $this->mongo_db->select(array('history'))->whereLike("history.1.time" , $today_date)->get($this->request_app_col);
						$document = $this->mongo_db->select(array('history'))->where(array('doc_data.doc_data.widget_data.page1.Student Info.School Name.field_ref' => $school_name))->get("healthcare2016531124515424_static_html");
		
		return $document;
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
			$query_request = $this->mongo_db->select(array("doc_data.widget_data","doc_data.notes_data","doc_data.external_attachments","doc_properties","history"))->orderBy(array("history.0.time"=> -1))->where( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ('healthcare2016531124515424_static_html');
			
			
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->whereLike ("req_doc_id", new MongoId($req['_id']))->get ( $this->collections['panacea_req_notes'] );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->notes_col );

			//log_message("debug","EHR======notes".print_r($query_notes,true));
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['panacea_schools'] );

			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['panacea_health_supervisors'] );
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


	function get_sanitation_report_fields_count($district_name,$school_name)
	{
		
		if( $school_name == 'All')
		{
			$today_date = date("Y-m-d");
			$campusOnce = $this->mongo_db->
								where(array(
										
										'doc_properties.status' => 2,
									    "doc_data.widget_data.page4.Declaration Information.Date:" => $today_date,
									    "doc_data.widget_data.page4.School Information.District" =>strtoupper($district_name)
									))->get("healthcare2016111212310531_version_2");
			
			return $campusOnce;
		}
		else
		{
			$today_date = date("Y-m-d");
			$campusOnceCount = $this->mongo_db->
								where(array(
										'doc_data.widget_data.page4.School Information.District' => strtoupper($district_name),
										'doc_properties.status' => 2, 
										"doc_data.widget_data.page4.Declaration Information.Date:" => $today_date 					    
									))->get("healthcare2016111212310531_version_2");
								
			return $campusOnceCount;
			
		}
	}


	function get_sanitaiton_report_by_school($today_date,$school_name)
	{
		
			$daily_count = $this->mongo_db->where(array('doc_properties.status' => 2, 'doc_data.widget_data.page4.School Information.School Name' => $school_name))->whereLike('doc_data.widget_data.page4.Declaration Information.Date:',$today_date)->get("healthcare2016111212310531_version_2");
			return $daily_count;

	}

	
	
	
	// ----------------------------------------------------------------------------------------------
}