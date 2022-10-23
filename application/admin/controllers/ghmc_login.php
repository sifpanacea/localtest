<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ghmc_login extends CI_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Ben
	 *
	 * @return void 
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		//$language = $this->session->userdata("language");
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
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
		$this->login();
	
	}
	 
	// --------------------------------------------------------------------

	/**
	 * Loading patient login page
	 *
	 * @author  Vikas
	 *
	 * 
	 */
	 
	 function login()
	 {
	   $this->data['message'] = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : false;
	   $this->_render_page('ghmc_login/ghmc_login',$this->data);
	 
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Device login
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 
	 function dashlogin()
	 {
	 	log_message('debug','ghmc innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn');
	 	$response ="";
	 	header('Access-Control-Allow-Origin: *');
	 	$mobile                = $this->input->post('mobile');
	 		
	 	if ($this->ion_auth->dashlogin_ghmc($mobile))
	 	{
	 		log_message('debug','ghmccccccccccccccccccccccccccccccccccccccccccccc');
	 		// $response=$name;
	 		// echo $response;
	 		// log_message('debug',print_r($name,true));
	 		// $mess=$this->session->set_flashdata('message', $this->ion_auth->messages());
	 	}else{
	 		echo "login_unsuccessful";
	 	}
	 }
	 
	 // --------------------------------------------------------------------
	 
	 /**
	  * Loading verifying patient and logging in
	  *
	  * @author  Vikas
	  *
	  *
	  */
	 
	 function ghmc_verification()
	 {
	 	$uniqueid             = $this->input->post('uniqueid');
		$password             = $this->input->post('password');
		$login = $this->ion_auth->ghmc_login($uniqueid, $password);
		if(!$login){
		$this->session->set_flashdata('message', "Incorrect Login");
	 	redirect('ghmc_login/login');
		}
	 
	 }
	 	
		
}

/* End of file signup.php */
/* Location: ./application/admin/controllers/patient_login.php */