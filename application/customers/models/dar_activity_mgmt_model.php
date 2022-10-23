<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dar_activity_mgmt_model extends CI_Model 
{
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
	
	 function __construct()
    {
        parent::__construct();
        
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
        
        //$this->common_db = $this->config->item('default');
        
        $this->screening_app_col = "healthcare2016226112942701";
        $this->absent_app_col = "healthcare201651317373988";
        $this->request_app_col = "healthcare2016531124515424";
		
        $this->sanitation_infra_app_col  = "healthcare20161114161842748";
        $this->sanitation_report_app_col = "healthcare2016111212310531";
    }

    
    

    
}


