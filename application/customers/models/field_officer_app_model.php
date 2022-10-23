<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Field_officer_app_model extends CI_Model {

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
		$this->screening_app_col_sw = "healthcare2016226112942701";
		$this->screening_app_col_tm = "healthcare201672020159570";
		$this->screening_app_col_tt = "healthcare201671115519757";
		
		$this->today_date = date ( 'Y-m-d' );
	}
	
	public function get_school_from_code($code, $school_type) {
		if($school_type == "TSWREIS"){
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->getWhere($this->collections ['panacea_schools'],array("school_code"=> intval($code)));
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}else if($school_type == "TTWREIS"){
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->getWhere($this->collections ['ttwreis_schools'],array("school_code"=> intval($code)));
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}else if($school_type == "TMREIS"){
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$school_details = $this->mongo_db->getWhere($this->collections ['tmreis_schools'],array("school_code"=> intval($code)));
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		}
		return $school_details;
	}
	
	public function get_ehr_from_uid($uid, $school_type) {
		if($school_type == "TSWREIS"){
			$school_details = $this->mongo_db->select(array('doc_data.widget_data'))->getWhere($this->screening_app_col_sw,array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $uid));
		}else if($school_type == "TTWREIS"){
			$school_details = $this->mongo_db->select(array('doc_data.widget_data'))->getWhere($this->screening_app_col_tt,array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $uid));
		}else if($school_type == "TMREIS"){
			$school_details = $this->mongo_db->select(array('doc_data.widget_data'))->getWhere($this->screening_app_col_tm,array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $uid));
		}
		return $school_details;
	}
	
		public function submit_doc($data) {
		
			$query = $this->mongo_db->insert("field_officer_documents",$data);
		log_message('debug','submit_doc==62========='.print_r($query ,true));
		return $query;
	}
	
	public function get_field_offiers(){
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->whereIn('groups',array("Field Officer"))->get("users");
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		return $query;
	}
	
	
}