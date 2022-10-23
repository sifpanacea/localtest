<?php
class School_model extends CI_Model{

 
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		// Load MongoDB library,
		$this->load->library('mongo_db');
		$this->load->config('ion_auth', TRUE);
    }
	
	function fetch_school_names_for_chart($app_id,$field)
	{
		$response = $this->mongo_db->command(array('distinct' => $app_id ,'key' => $field));
		return $response['values'];
	}
	
	
    
}