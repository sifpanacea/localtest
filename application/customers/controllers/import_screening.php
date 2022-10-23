<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_screening extends My_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Vikas
	 *
	 * @return void
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		$this->upload_info = array();
		$this->load->library('form_validation');
		$this->load->helper('url');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->library('paas_common_lib');
		$this->load->library('paas_common_lib');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
		$this->load->model('panacea_mgmt_model');
		$this->load->library('panacea_common_lib');
		$this->load->library('session');
	}
	
	/**
	 * 
	 *
	 * @author  Vikas
	 *
	 *
	 */
	public function index()
	{
		redirect('import_screening/imports');
	}
	//===============================================
	/**
	 * Helper : Import zipfile 
	 *
	 * @author  Naresh
	 */
	
	function imports()
	{
	  $sub_admin  = $this->session->userdata('customer');
	  $subadminid = $sub_admin['user_id'];
	  $this->data['message'] = "";
	  $this->_render_page('panacea_imports/panacea_zipfile_import_view',$this->data);
	}

	//Excel importing function
	//author Naresh
	 function school_screening_file_import()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('school_screening_file_import');
		
		$post = $_POST;
		
		$this->data = $this->panacea_common_lib->school_screening_file_import($post);
		
    }
	

		
}