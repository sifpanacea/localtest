<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Zip_import_lib 
{
	
	// --------------------------------------------------------------------

    /**
	* Constructor
	*
	*/

	public function __construct()
	{
		$this->ci = &get_instance();         // In custom libraries we need to get instance of ci to make use of ci core classes (here we use Loader class)
		
		$this->ci->load->config('ion_auth', TRUE);
		$this->ci->load->library('session');
		$this->ci->load->helper('url');
		$this->ci->load->helper('paas');
		$this->ci->lang->load('auth');
		
		$this->ci->config->load('config', TRUE);
		$this->ci->upload_info = array();
		//$this->ci->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->ci->load->model('panacea_common_model');
	
	}

    
    // --------------------------------------------------------------------

	public function unique_id_check($unique_id)
	{
		
		$result = $this->ci->panacea_common_model->unique_id_check($unique_id);
		
		if($result == "Only unique id document")
		{
			$query = $this->ci->panacea_common_model->import_screening_lib($post);
		}
		if($result == "Only personal info document")
		{
			
		}
		if($result == "Full document")
		{
			
		}
		
		echo print_r($result,true);
		exit();
	
		return $result;
	}
	
	
	
}