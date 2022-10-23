<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolhealth_auth extends CI_Controller {

	  function __construct()
	  {
	     
		 parent::__construct();
		
		 $this->config->load('config',TRUE);
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('paas');
		 $this->load->helper('cookie');

		 // Load MongoDB library instead of native db driver if required
		 $this->config->load('email');
		 $this->load->library('mongo_db');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
         
		 $this->lang->load('auth');
		 $this->load->helper('language');
		 $this->load->library('schoolhealth_school_lib');
		 $this->load->library('schoolhealth_sub_admin_lib');
		 $this->load->library('schoolhealth_admin_lib');
	  }
	  
	// --------------------------------------------------------------------

	/**
	 * Helper : Dashboard
	 *
	 * @author  Selva
	 * 
	 */

	function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'schoolhealth_auth/login');
		}
		else
		{
	        $user_designation = $this->get_designation_for_logged_in_user();
			
			if($user_designation == "schoolhealth_school")
			{
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "2018_2019";//"Yearly";
			  $this->data = $this->schoolhealth_school_lib->to_dashboard($date,$request_duration, $screening_duration);
		      $this->_render_page('schoolhealth/schools/schoolhealth_dash_school', $this->data);
			}
			else if($user_designation == "schoolhealth_clinic")
			{
		      $this->_render_page('schoolhealth/clinics/schoolhealth_dash_clinic', $this->data);
			}
			else if($user_designation == "schoolhealth_admin")
			{
		      log_message('debug','$this->data=====schoolhealth_sub_admin_lib==='.print_r($user_designation,true));
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "Yearly";
			  $this->data = $this->schoolhealth_sub_admin_lib->to_dashboard($date,$request_duration, $screening_duration);
              $this->_render_page('schoolhealth/admins/schoolhealth_dash_admin', $this->data);
			}
			else if($user_designation == "schoolhealth_sub_admin")
			{
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "Yearly";
			  $this->data = $this->schoolhealth_sub_admin_lib->to_dashboard($date,$request_duration, $screening_duration);
			  log_message('debug','$this->data=====schoolhealth_sub_admin_lib==='.print_r($this->data,true));
              $this->_render_page('schoolhealth/sub_admins/schoolhealth_dash_sub_admin', $this->data);
			}
			else
			{
		       //redirect them to the login page
			   redirect(URC.'schoolhealth_auth/login');
			}
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Dashboard
	 *
	 * @author  Selva
	 * 
	 */

	function dashboard()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'schoolhealth_auth/login');
		}
		else
		{
	        $user_designation = $this->get_designation_for_logged_in_user();
			
			if($user_designation == "schoolhealth_school")
			{
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "2018_2019";//"Yearly";
			  $this->data = $this->schoolhealth_school_lib->to_dashboard($date, $request_duration, $screening_duration);
		      $this->_render_page('schoolhealth/schools/schoolhealth_dash_school', $this->data);
			}
			else if($user_designation == "schoolhealth_clinic")
			{
		      $this->_render_page('schoolhealth/clinics/schoolhealth_dash_clinic', $this->data);
			}
			else if($user_designation == "schoolhealth_admin")
			{
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "Yearly";
			  $this->data = $this->schoolhealth_sub_admin_lib->to_dashboard($date,$request_duration, $screening_duration);
              $this->_render_page('schoolhealth/admins/schoolhealth_dash_admin', $this->data);
			}
			else if($user_designation == "schoolhealth_sub_admin")
			{
		      $date = FALSE;
			  $request_duration   = "Monthly";
			  $screening_duration = "Yearly";
			  $this->data = $this->schoolhealth_sub_admin_lib->to_dashboard($date,$request_duration, $screening_duration);
			  log_message('debug','$this->data=====71====='.print_r($this->data,true));
              $this->_render_page('schoolhealth/sub_admins/schoolhealth_dash_sub_admin', $this->data);
			}
			else
			{
		       //redirect them to the login page
			   redirect(URC.'schoolhealth_auth/login');
			}
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Logs the user out
	 *
	 * @author  Ben
	 *
	 * 
	 */
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();
		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect(URC.'schoolhealth_auth/login');
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch designation
	 *
	 * @return string
	 *
	 * @author Selva 
	 */

     function get_designation_for_logged_in_user()
	 {
	 	$user = $this->session->userdata("customer");
		return $user['designation'];
	 }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Setting School Admin/Clinic Admin/Admin login data as session data
	 *
	 * @param array $data
	 *
	 *
	 * @author Selva 
	 */
	 
  	 function session($data)
	 {
	 	$t = base64_decode($data);
	  	$idata = json_decode($t,true);
	  	$this->session->set_userdata("customer",$idata);
		log_message('debug','SCHOOLHEALTH_AUTH_SESSION=====197====='.print_r($this->session->userdata("customer"),true));
	  	redirect(URL.'schoolhealth_auth/index');
	 }
	 
	 // ------------------------------------------------------------------------

	/**
	 * Helper: Setting student login data as cookie data ( from device login)
	 *
	 * @param array $data
	 *
	 *
	 * @author Selva 
	 */

     function dashsession($data)
	 {
	 	$t     = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	echo json_encode($idata);
	 	
	 	$cookie = array(
	 			'name'   => $this->config->item('sess_cookie_name','config'),
	 			'value'  => $data,
	 			'expire' => $this->config->item('sess_expiration','config'),
	 			'domain' => $this->config->item('cookie_domain','config'),
	 			'path'   => $this->config->item('cookie_path', 'config'),
	 			'prefix' => $this->config->item('cookie_prefix','config'),
	 			'secure' => $this->config->item('cookie_secure','config')
	 	);
		
	 	$this->input->set_cookie($cookie);
	 }
	
     
    
}

/* End of file schoolhealth_auth.php */
/* Location: ./application/customers/controllers/schoolhealth_auth.php */