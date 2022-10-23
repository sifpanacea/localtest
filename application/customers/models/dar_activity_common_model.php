<?php
ini_set ( 'memory_limit',"2G");
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Dar_activity_common_model extends CI_Model {
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
		
		$this->today_date = date ( 'Y-m-d' );

		$this->poweroften_registration_col = 'poweroften_registrations';
		$this->poweroften_registration_col_shadow = 'poweroften_registrations_shadow';
	}
	

	public function get_all_district($dt_name = "All") {
		if ($dt_name == "All") {
			$query = $this->mongo_db->orderBy ( array (	'dt_name' => 1 ) )->get ( 'panacea_district' );
		} else {
			$query = $this->mongo_db->where ( 'dt_name', $dt_name )->orderBy ( array ('dt_name' => 1 ) )->get ( 'panacea_district' );
		}
		return $query;
	}

	public function get_counts_for_dashboard()
	{
		//$query['verified_swearos'] = $this->mongo_db->where(array("doc_properties.district_level_verification"=>1, "doc_properties.registration_status"=>1)->count($this->poweroften_registration_col);

		$query['declined'] = $this->mongo_db->count('power_of_ten_declined_users');

		//$query['total_registrations'] = $this->mongo_db->count($this->poweroften_registration_col);

		return $query;
	}
	/* DAR Dash Board Activities*/

	public function get_total_received_district_coordinators()
	{
		$types = [];
		$final = [];
		$query = $this->mongo_db->select(array('doc_data.district'))->where( array("doc_properties.district_level_verification"=>array('$exists'=>TRUE), "doc_properties.registration_status"=>array('$ne'=>1) ))->get($this->poweroften_registration_col);

		
		foreach ($query as $data) {
			$value = $data['doc_data']['district'];
			array_push($types, $value);
		}

		$counts = array_count_values($types);

		if(!empty($counts)){

			foreach ($counts as $key => $value) {
				$request['name'] = $key;
				$request['y'] = $value;

				array_push($final, $request);
			}
			return $final;
		}else{
			return "No data Found"; 
		}
	}

	

	public function get_total_pending_district_coordinators()
	{
		$types = [];
		$final = [];
		$query = $this->mongo_db->select(array('doc_data.district'))->where( array("doc_properties.district_level_verification"=>array('$exists'=>FALSE) ))->get($this->poweroften_registration_col);

		foreach ($query as $data) {
			$value = $data['doc_data']['district'];
			array_push($types, $value);
		}

		$counts = array_count_values($types);

		if(!empty($counts)){

			foreach ($counts as $key => $value) {
				$request['name'] = $key;
				$request['y'] = $value;

				array_push($final, $request);
			}
			return $final;
		}else{
			return "No data Found"; 
		}
	}

	public function get_registritations_confirmed_swaeros($district_name)
	{
		$query = $this->mongo_db->where(array("doc_data.district"=>$district_name, "doc_properties.district_level_verification"=>array('$exists'=>TRUE), "doc_properties.registration_status"=>array('$ne'=>1) ))->get($this->poweroften_registration_col);
		return $query;
	}

	

	public function accept_conformed_registrations($accept_docID)
	{
		
		$query = $this->mongo_db->where('doc_properties.doc_id', $accept_docID)->set(array('doc_properties.registration_status'=> 1))->update($this->poweroften_registration_col);

		$query_shadow = $this->mongo_db->where('doc_properties.doc_id', $accept_docID)->set(array('doc_properties.registration_status'=> 1))->update($this->poweroften_registration_col_shadow);

		return $query;
	}

	public function get_registrations_pending_swaeros($district_name)
	{
		 
		$query = $this->mongo_db->where(array("doc_data.district"=>$district_name, "doc_properties.district_level_verification"=>array('$exists'=>FALSE) ))->get($this->poweroften_registration_col);

		return $query;
	}

	public function decline_conformed_registrations($decline_docID)
	{
		$query = $this->mongo_db->where('doc_properties.doc_id', trim($decline_docID))->get($this->poweroften_registration_col);

		

		$data = array(
			"doc_data" => $query[0]['doc_data'],
			"doc_properties" => $query[0]['doc_properties'],
			"history" => $query[0]['history']
		);

		$insert = $this->mongo_db->insert("power_of_ten_declined_users", $data);

		if($insert){
			$remove_data = $this->mongo_db->where('doc_properties.doc_id', trim($decline_docID))->delete($this->poweroften_registration_col);
		}
		
		return $insert;
		
	}

		
	



}
