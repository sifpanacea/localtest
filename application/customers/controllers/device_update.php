<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Device_update extends CI_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->config->load('email');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
    
    // ------------------------------------------------------------------------
    /**
     * Helper: Change log for dff
     *
     * @author Vikas
     */
    
    public function check_ver_DFF()
    {
    	$path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/DFF/change_apk_version.txt';
    	$this->external_file_download($path);
    	 
    }
    
    /**
     * Helper: Download DFF app securely
     *
     * @author Vikas
     */
	 
    
    public function update_DFF($ver = FALSE)
    {
    	if($ver == FALSE){
    		echo read_file($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/DFF/DFF.apk');
    		//$this->external_file_download($path);
    	}else {
    		echo read_file($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/apk/DFF/DFF.apk');
    		//$this->external_file_download($path);
    	}
    	
    }
    
}
