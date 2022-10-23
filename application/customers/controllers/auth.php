<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

       
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		log_message('debug','in------------cust---------auth'.print_r($this->config->item('language'),true));
		//$language = $this->session->userdata("language");
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		log_message('debug','in------------cust---after----------------auth'.print_r($this->config->item('language'),true));
		
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->helper('paas');
		$this->load->library('excel');
		$this->load->library('PaaS_common_lib');
		$this->load->helper('cookie');
		$this->config->load('email');
		
		//$this->config->load('config', TRUE);
		
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->lang->load('ion_auth');
		$this->tab 		= chr(9);
		$this->tabx2 	= chr(9).chr(9);
		$this->tabx3 	= chr(9).chr(9).chr(9);
		$this->tabx4 	= chr(9).chr(9).chr(9).chr(9);
		$this->tabx5 	= chr(9).chr(9).chr(9).chr(9).chr(9);
		$this->tabx6 	= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9);
		$this->tabx7 	= chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9);
		$this->sl  		= chr(13).chr(10);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Enterprise admin dashboard
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'auth/login');
		}
		else
		{
			if($this->ion_auth->is_admin())
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : "Logged In Successfully";
	
				$total_rows = $this->ion_auth->appcount();

                //---pagination--------//
	   	        $config = $this->paas_common_lib->set_paginate_options($total_rows,10);
					
				//Initialize the pagination class
				$this->pagination->initialize($config);
					
				//control of number page
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
					
				//find all the categories with paginate and save it in array to past to the view
				$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);
					
				//create paginate´s links
				$this->data['links'] = $this->pagination->create_links();
					
				//number page variable
				$this->data['page'] = $page;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
	            
	            //other analytics values
				$data = $this->paas_common_lib->admin_dashboard_analytics_values();
				        
				$this->data = array_merge($this->data,$data);

			    $this->_render_page('admin/admin_dash', $this->data);
			}
			elseif($this->ion_auth->is_user() && !$this->ion_auth->is_plan_active())
			{
			    $this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect(URC.'auth/login');
			}
			elseif ($this->ion_auth->is_user() && $this->ion_auth->is_plan_active())
			{
				$this->session->set_flashdata('message', "Logged In Successfully");
				redirect('web/index', 'refresh');
			}
			elseif ($this->ion_auth->is_sub_admin() && $this->ion_auth->is_plan_active())
			{
				$this->session->set_flashdata('message', "Logged In Successfully");
				redirect('sub_admin/index', 'refresh');
			}
			else
			{
		       //$this->logout();
			   $this->session->set_flashdata('message', "Logged In Successfully");
			   redirect('web/index', 'refresh'); 
			}
		}
	}
	
    // ------------------------------------------------------------------------

	/**
	 * Helper: Setting enterprise admin/user login data as session data
	 *
	 * @param	array $data
	 *
	 *
	 * @author Selva 
	 */
	 
  	 function session($data)
	 {
	 	
	 	$t = base64_decode($data);
	  	$idata = json_decode($t,true);
	  	$this->session->set_userdata("customer",$idata);
	  	redirect(URL.'auth/index');
	 }
	 
	 /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_panacea_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "panacea.user@gmail.com" || $idata['identity'] == "panacea.view@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'panacea_mgmt/basic_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	
	 }

	  /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_poweroften_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "poweroften.user@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'power_of_ten_mgmt/basic_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	
	 }

	  /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_poweroften_dar_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "poweroften.dar@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'dar_activity_mgmt/to_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	
	 }


	 /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_l3_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "l3.user@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'l3_mgmt/basic_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	
	 }

	 /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_tswreis_sports_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "tswreissports.user@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'tswreis_sports_mgmt/basic_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	
	 }

	 /**
	  * Helper: Setting panacea_secretary login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_panacea_secretary($data)
	 {

	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	if($idata['identity'] == "panacea.secretary@gmail.com")
	 	{
	 		$this->session->set_userdata("customer",$idata);
	 		//redirect(URL.'panacea_mgmt/to_dashboard');
	 		redirect(URL.'panacea_secretary/basic_dashboard');
	 	}else
	 	{
	 		redirect(URC.'auth/login');
	 	}

	 	/*$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'panacea_secretary/basic_dashboard');*/
	 }
	 
	  /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_panacea_viewers($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'panacea_viewers/to_dashboard');
	 }
	 
	 /**
	  * Helper: Setting tmreis viewers login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_tmreis_viewers($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tmreis_viewers/to_dashboard');
	 }
	 
	 /**
	  * Helper: Setting ttwreis viewers login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_ttwreis_viewers($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'ttwreis_viewers/to_dashboard');
	 }

	 /**
	  * Helper: Setting ttwreis viewers login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_panacea_sanitation_admin($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'panacea_sanitation/to_dashboard');
	 }

	 /**
	  * Helper: Setting ttwreis viewers login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_tmreis_sanitation_admin($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tmreis_sanitation/to_dashboard');
	 }

	  /**
	  * Helper: Setting ttwreis viewers login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_ttwreis_sanitation_admin($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'ttwreis_sanitation/to_dashboard');
	 }
	 
	 /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_panacea_hs($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tswreis_schools/index');
	 }
	 
	 /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_tmreis_hs($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tmreis_schools/index');
	 }
	 
	 /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_ttwreis_hs($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'ttwreis_schools/index');
	 }
	 
	 /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_panacea_cc($data)
	 {
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'panacea_cc/to_dashboard');
	 }
	 
	 	 /**
	  * Helper: Setting ttwreis login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_ttwreis_mgmt($data)
	 {
	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
		/* $ip_address = $this->session->userdata('ip_address'); 
		log_message("error","ip_address for web side login users".print_r($ip_address,true));
		$session_data = $this->session->userdata('customer');
		$session_data = $session_data['email'];
		log_message("error","session_data for web side login users".print_r($session_data,true)); */
	 	redirect(URL.'ttwreis_mgmt/to_dashboard');
	 }
	 
	 	 /**
	  * Helper: Setting ttwreis login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 
	 function session_ttwreis_cc($data)
	 {
	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'ttwreis_cc/to_dashboard');
	 }

	 /**
	 	  * Helper: Setting CC-Notes User web side login data as session data
	 	  *
	 	  * @param	array $data
	 	  *
	 	  *
	 	  * @author YOGA
	 	  */
	function session_panacea_cc_normal($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 /*	echo print_r(URL , true); exit();*/
	 	$this->session->set_userdata("customer",$idata);
	 	
	 	redirect(URL.'panacea_ts_normal/fetch_normal_requests_docs');
	 	//redirect(URL.'ttwreis_doctor/fetch_request_docs_from_hs_list');
	 }

	      // ------------------------------------------------------------------------

	 /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Suman & Bhanu
	  */
	 
	 function session_screening_admin($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'import_screening/index');
	 }
	 
	 	 	 /**
	  * Helper: Setting tmreis login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */

	 
	 function session_tmreis_mgmt($data)
	 {
	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tmreis_mgmt/to_dashboard');
	 }
	 
	 	 /**
	  * Helper: Setting tmreis login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_tmreis_cc($data)
	 {
	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'tmreis_cc/to_dashboard');
	 }
	 
	  /**
	  * Helper: Setting panacea login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function session_bc_welfare_admins($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	//redirect(URL.'bc_welfare_mgmt/to_dashboard');
	 	redirect(URL.'bc_welfare_mgmt/basic_dashboard');
	 } 
	  /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_bc_welfare_hs($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'bc_welfare_schools/index');
	 }
	 
	 /**
	  * Helper: Setting School HS login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function session_bc_welfare_cc($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'bc_welfare_cc/index');
	 }
	 	 
	 /**
	  * setting field agent login data as session data
	  *
	  * @author  Vikas
	  *
	  * @param	array $data  login data
	  *
	  */
	 
	 function session_field_agent($data)
	 {
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	//redirect(URL.'field_agent/index');
	 	
	 	$this->session->set_flashdata('message', "Logged In Successfully");
	 	redirect('field_agent/index', 'refresh');
	 }
	 
	 // ------------------------------------------------------------------------
	 
	 /**
	  * Helper: Setting enterprise admin/user login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Selva
	  */
	 
	 function set_patient_session($data)
	 {
	 	$patient_data = base64_decode($data);
	 	$patient_res = json_decode($patient_data,true);
	 	$this->session->set_userdata("customer",$patient_res);
	 	redirect(URL.'patient_login/patient_dashboard');
	 }
	 
	 /**
	  * Helper: Setting RHSO ADMIN login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author bhanu
	  */
	 
	 function session_rhso_admins($data)
	 {
	 	
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'rhso_mgmt/to_dashboard');
	 }
	 /**
	  * Helper: Setting RHSO User login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author bhanu
	  */
	 function session_rhso_users($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	redirect(URL.'rhso_users/basic_dashboard');
	 }
	 
	  /**
	  * Helper: Setting Panacea Doctor User web side login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Naresh
	  */
	 function session_panacea_doctor($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	//redirect(URL.'panacea_doctor/to_dashboard');
	 	redirect(URL.'panacea_doctor/fetch_request_docs_from_hs_list');
	 }
	 
	   /**
	  * Helper: Setting BC-WELFARE Doctor User web side login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Naresh
	  */
	 function session_bc_welfare_doctor($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	//redirect(URL.'bc_welfare_doctor/to_dashboard');
	 	redirect(URL.'bc_welfare_doctor/fetch_request_docs_from_hs_list');
	 }

	 
	   /**
	  * Helper: Setting BC-WELFARE Doctor User web side login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Naresh
	  */
	 function session_ttwreis_doctor($data)
	 {
	 	 
	 	$t = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	$this->session->set_userdata("customer",$idata);
	 	//redirect(URL.'ttwreis_doctor/to_dashboard');
	 	redirect(URL.'ttwreis_doctor/fetch_request_docs_from_hs_list');
	 }

     // ------------------------------------------------------------------------

	/**
	 * Helper: Setting enterprise user login data as cookie data ( from device login)
	 *
	 * @param	array $data
	 *
	 *
	 * @author Vikas 
	 */

     function dashsession($data)
	 {

	 	$t     = base64_decode($data);
	 	$idata = json_decode($t,true);
	 	echo json_encode($idata);
		//log_message('error','json_idataaaaaaaaaaaaaaaaa=======592'.print_r(json_encode($idata),TRUE));
	
			$cookie = array(
	 			'name'   => $this->config->item('sess_cookie_name', 'config'),
	 			'value'  => $data,
	 			'expire' => $this->config->item('sess_expiration', 'config'),
	 			'domain' => $this->config->item('cookie_domain', 'config'),
	 			'path'   => $this->config->item('cookie_path', 'config'),
	 			'prefix' => $this->config->item('cookie_prefix', 'config'),
	 			'secure' => $this->config->item('cookie_secure', 'config')
	 	);
	    log_message('debug','CI_SESSION======332==='.print_r(get_cookie('ci_session'),true));
		
		//added by veera
		//delete_cookie('ci_session');
		//veera code end
		
		log_message('debug','CI_SESSION======338==='.print_r(get_cookie('ci_session'),true));
		
	 	$this->input->set_cookie($cookie);
		
		log_message('debug','CI_SESSION======342==='.print_r($cookie,true));
		
		log_message('debug','CI_SESSION======344==='.print_r(get_cookie('ci_session'),true));
	
	 	
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Enterprise admin dashboard
	 *
	 * @author  Selva
	 *
	 * 
	 */
	  
    function dashboard()
	{
	   	
	   	if (!$this->ion_auth->logged_in())
	   	{
	   		redirect(URC.'auth/login');
	   	}
	   	if ($this->ion_auth->is_user())
	   	{
	   		redirect(URC.'auth/login');
	   	}
		
		$total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);

		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

        //other analytics values
		$data = $this->paas_common_lib->admin_dashboard_analytics_values();
				        
	    $this->data = array_merge($this->data,$data);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

	    $this->_render_page('admin/admin_dash', $this->data);
	   
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
		redirect(URC.'auth/login');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Change password
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function change_password()
	{
		
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}

		$user = $this->session->userdata('customer');

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ion_auth->errors();

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new_pwd',
				'id'   => 'new_pwd',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user['user_id'],
			);
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
           
			//render
			$this->_render_page('auth/change_password', $this->data);
		}
		else
		{
		    $identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				//$this->session->set_flashdata('message', $this->ion_auth->errors());
				//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ion_auth->errors();

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new_pwd',
				'id'   => 'new_pwd',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user['user_id'],
			);
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
           
			//render
			$this->_render_page('auth/change_password', $this->data);
			}
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Change password
	 *
	 * @author  Ben
	 *
	 *
	 */
	
	function change_password_sub_admin()
	{
		log_message('debug','sub__________________________adminnnnnnnnnnnnnnnnnnnnnnn');
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
	
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
	
		$user = $this->session->userdata('customer');
	
		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$u = $this->session->userdata("customer");
			$email = $u['email'];
			$this->data['email'] = $email;
	
			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
					'name' => 'old',
					'id'   => 'old',
					'type' => 'password',
			);
			$this->data['new_password'] = array(
					'name' => 'new_pwd',
					'id'   => 'new_pwd',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user['user_id'],
			);
	
			//render
			$this->_render_page('sub_admin/change_password', $this->data);
		}
		else
		{
			$identitydata = $this->session->userdata("customer");
			$identity = $identitydata['email'];
			$change = $this->ion_auth->change_password_sub_admin($identity, $this->input->post('old'), $this->input->post('new'));
	
			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password_sub_admin', 'refresh');
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : Forgot password
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function forgot_password()
	{
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			// get identity for that email
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            if(empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/forgot_password", 'refresh');
            }
            
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect(URC."auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);

				//render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


    //************** USERS ***************START***************//

	// --------------------------------------------------------------------

	/**
	 * Helper : Activate the user ( Existing De-activated user )
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
			if (!$this->ion_auth->is_user())
			{
				$activation = $this->ion_auth->activate($id);
			}
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("dashboard/user");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Activate the user ( Existing De-activated user )
	 *
	 * @author  Ben
	 *
	 *
	 */
	
	function activate_sub_admin($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate_sub_admin($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
			if (!$this->ion_auth->is_user())
			{
				$activation = $this->ion_auth->activate_sub_admin($id);
			}
		}
	
		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("dashboard/sub_admin");
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : De-activate the user ( Existing active user )
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['message'] = " User Deactivation";
			$this->data['csrf']    =  get_csrf_nonce();  // Using PaaS helper function
            $this->data['user'] = $this->ion_auth->user($id)->row();
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);

			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
                // do we have a valid request?
				if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
				   show_error($this->lang->line('error_csrf'));
				}

                // unset csrf userdata
				unset_csrf_userdata();  // Using PaaS helper function

                // do we have the right userlevel?
				if ($this->ion_auth->logged_in() && !$this->ion_auth->is_user())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('dashboard/user');
		}
	}
	
	/**
	 * Helper : De-activate the user ( Existing active user )
	 *
	 * @author  Ben
	 *
	 *
	 */
	
	function deactivate_sub_admin($id = NULL)
	{
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd');
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;
	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
	
		if ($this->form_validation->run() == FALSE)
		{
			log_message('debug','ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff');
			// insert csrf check
			$this->data['message'] = " Sub Admin Deactivation";
			$this->data['csrf']    =  get_csrf_nonce();  // Using PaaS helper function
			$this->data['user'] = $this->ion_auth->sub_admins($id)->row();
	
			$this->_render_page('auth/deactivate_sub_admin', $this->data);
		}
		else
		{log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',print_r($this->input->post,true));
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				log_message('debug','yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
				// do we have a valid request?
				if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}
	
				// unset csrf userdata
				unset_csrf_userdata();  // Using PaaS helper function
	
				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && !$this->ion_auth->is_user())
				{
					log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu');
					$this->ion_auth->deactivate_sub_admin($id);
				}
			}
	
			//redirect them back to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('dashboard/sub_admin');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : Create a new user
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function create_user()
	{
		$this->data['title'] = "Create User";
		$groups =$this->ion_auth->groups()->result_array();
		
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
	    {
	    	redirect(URC.'auth/login');
	    }
	    
	    //validate user creation limit
	    if($this->ion_auth->check_user_limit()){
			//validate form input
			$this->form_validation->set_rules('device_unique_number', $this->lang->line('create_user_device_unique_number_label'), 'required|xss_clean');
			$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
			$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
			$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
			$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');
			
			if ($this->form_validation->run() == true)
			{
				$user_exists= $this->ion_auth->user_exists(strtolower($this->input->post('email')));
				if ($user_exists) {
						//display the create user form
						//set the flash data error message if there is one
						$this->data['message'] = "Email id already used!";
						$this->data['groups'] = $groups;
						$company = $this->session->userdata("customer")['company'];
						$this->data['device_unique_number'] = array(
								'name'  => 'device_unique_number',
								'id'    => 'device_unique_number',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('device_unique_number'),
						);
						$this->data['first_name'] = array(
								'name'  => 'first_name',
								'id'    => 'first_name',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('first_name'),
						);
						$this->data['last_name'] = array(
								'name'  => 'last_name',
								'id'    => 'last_name',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('last_name'),
						);
						$this->data['email'] = array(
								'name'  => 'email',
								'id'    => 'email',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('email'),
						);
						$this->data['company'] = array(
								'name'  => 'company',
								'id'    => 'company',
								'type'  => 'text',
								'value' => $this->session->userdata("customer")['company'],
						);
						$this->data['phone'] = array(
								'name'  => 'phone',
								'id'    => 'phone',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('phone'),
						);
						$this->data['password'] = array(
								'name'  => 'password',
								'id'    => 'password',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('password'),
						);
						$this->data['password_confirm'] = array(
								'name'  => 'password_confirm',
								'id'    => 'password_confirm',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('password_confirm'),
						);
						
						//bubble count for events and feedbacks
						$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
						$this->data = array_merge($this->data,$data_bubble_count);
						
						$this->_render_page('auth/create_user', $this->data);
					}else{
					
					$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
					$email    = strtolower($this->input->post('email'));
					$password = $this->input->post('password');
					$device_unique_number = $this->input->post('device_unique_number');
		
		            log_message('debug','$_POST=====1153'.print_r($_POST,true));
					$customer_data = $this->ion_auth->customer()->row();
					log_message('debug','$customer_data=====1153'.print_r($customer_data,true));
					
					$additional_data = array(
						'first_name'         => $this->input->post('first_name'),
						'last_name'          => $this->input->post('last_name'),
					    'company'            => strtolower(str_replace(" ","",$this->input->post('company'))),
						'phone'              => $this->input->post('phone'),
						'subscribed_with'    => array($customer_data->display_company_name),
						'subscription_start' => $customer_data->registered_on,
						'subscription_end'   => $customer_data->plan_expiry,
						'plan_subscribed'    => $customer_data->plan,
					);
					
					
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');
		log_message('debug','$groupData=====1153'.print_r($groupData,true));
					$create_usr = $this->ion_auth->register($username,$password,$email,$device_unique_number,$additional_data,$groupData);
					//check to see if we are creating the user
					//redirect them back to the admin page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					//set the flash data error message if there is one
					$this->data['message'] = $this->ion_auth->messages();
					
					//list the users
					$this->data['users'] = $this->ion_auth->users()->result();
					$this->data['apps'] = $this->ion_auth->apps();
					$this->data['numberofuser'] = count($this->ion_auth->users()->result());
					//foreach ($this->data['users'] as $k => $user)
					//{
					//	$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
					//}
					
					//bubble count for events and feedbacks
					$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
					$this->data = array_merge($this->data,$data_bubble_count);
					
					$this->_render_page('admin/admin_dash_users', $this->data);
				}
	    	}
			else
			{
				//display the create user form
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->data['groups'] = $groups;
				$company = $this->session->userdata("customer")['company'];
				$this->data['device_unique_number'] = array(
					'name'  => 'device_unique_number',
					'id'    => 'device_unique_number',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('device_unique_number'),
						);
				$this->data['first_name'] = array(
					'name'  => 'first_name',
					'id'    => 'first_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('first_name'),
				);
				$this->data['last_name'] = array(
					'name'  => 'last_name',
					'id'    => 'last_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('last_name'),
				);
				$this->data['email'] = array(
					'name'  => 'email',
					'id'    => 'email',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('email'),
				);
				$this->data['company'] = array(
					'name'  => 'company',
					'id'    => 'company',
					'type'  => 'text',
					'value' => $this->session->userdata("customer")['company'],
				);
				$this->data['phone'] = array(
					'name'  => 'phone',
					'id'    => 'phone',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('phone'),
				);
				$this->data['password'] = array(
					'name'  => 'password',
					'id'    => 'password',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('password'),
				);
				$this->data['password_confirm'] = array(
					'name'  => 'password_confirm',
					'id'    => 'password_confirm',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('password_confirm'),
				);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
	
				$this->_render_page('auth/create_user', $this->data);
			}
		}else{
			$this->data['message'] = "Your user creation limit is over!";
			$this->data['groups'] = $groups;
			
			$this->data['company'] = array(
					'name'  => 'company',
					'id'    => 'company',
					'type'  => 'text',
					'value' => $this->session->userdata("customer")['company'],
			);
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
			
			$this->_render_page('auth/create_user', $this->data);
			
		}
	}		

	// --------------------------------------------------------------------

	/**
	 * Helper : Edit a existing user
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() )
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}

		$user = $this->ion_auth->user($id)->row();
		
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        
		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone')
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}
			
			//update the email if it was posted
			if ($this->input->post('email'))
			{
				$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
				$data['email'] = $this->input->post('email');
			}

			if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data))
			{
				    // unset csrf userdata
				    unset_csrf_userdata();
                    
                    log_message('debug','AUTH=====EDIT_USER=====all_userdata=====822'.print_r($this->session->all_userdata(),true));

                    $this->session->set_flashdata('message',$this->ion_auth->messages());
					redirect('dashboard/user');
			}
			else
			{

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

				//pass the user to the view
				$this->data['user'] = $user;
				$this->data['groups'] = $groups;
				$this->data['currentGroups'] = $currentGroups;

				$this->data['first_name'] = array(
					'name'  => 'first_name',
					'id'    => 'first_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('first_name', $user->first_name),
				);
				$this->data['last_name'] = array(
					'name'  => 'last_name',
					'id'    => 'last_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('last_name', $user->last_name),
				);
				$this->data['company'] = array(
					'name'    => 'company',
					'id'      => 'company',
					'type'    => 'text',
					'value'   => $this->form_validation->set_value('company', $user->company),
					'readonly'=> 'readonly',
				);
				$this->data['phone'] = array(
					'name'  => 'phone',
					'id'    => 'phone',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('phone', $user->phone),
				);
				
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('email', $user->email),
					); 
				
				$this->data['password'] = array(
					'name' => 'password',
					'id'   => 'password',
					'type' => 'password'
				);
				$this->data['password_confirm'] = array(
					'name' => 'password_confirm',
					'id'   => 'password_confirm',
					'type' => 'password'
				);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);

				$this->_render_page('auth/edit_user', $this->data);
				}
		}

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();
				
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				//pass the user to the view
				$this->data['user'] = $user;
				$this->data['groups'] = $groups;
				$this->data['currentGroups'] = $currentGroups;
				
				$this->data['first_name'] = array(
						'name'  => 'first_name',
						'id'    => 'first_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('first_name', $user->first_name),
				);
				$this->data['last_name'] = array(
						'name'  => 'last_name',
						'id'    => 'last_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('last_name', $user->last_name),
				);
				$this->data['company'] = array(
						'name'    => 'company',
						'id'      => 'company',
						'type'    => 'text',
						'value'   => $this->form_validation->set_value('company', $user->company),
						'readonly'=> 'readonly',
				);
				$this->data['phone'] = array(
						'name'  => 'phone',
						'id'    => 'phone',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('phone', $user->phone),
				);
				
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('email', $user->email),
				);
				
				$this->data['password'] = array(
						'name' => 'password',
						'id'   => 'password',
						'type' => 'password'
				);

				$this->data['password_confirm'] = array(
						'name' => 'password_confirm',
						'id'   => 'password_confirm',
						'type' => 'password'
				);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
				
				$this->_render_page('auth/edit_user', $this->data);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Create a new sub admin
	 *
	 * @author  Ben
	 *
	 *
	 */
	
	function create_sub_admin()
	{
		$this->data['title'] = "Create Sub Admin";
	
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
			
	
		//validate user creation limit
		if($this->ion_auth->check_sub_admin_limit()){
			//validate form input
			$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
			$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
			$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
	
			if ($this->form_validation->run() == true)
			{
				$user_exists= $this->ion_auth->sub_admin_exists(strtolower($this->input->post('email')));
	
				if ($user_exists) {
					//display the create user form
					//set the flash data error message if there is one
					$this->data['message'] = "Email id already used!";
	
					
					$company = $this->session->userdata("customer")['company'];
					$this->data['first_name'] = array(
							'name'  => 'first_name',
							'id'    => 'first_name',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('first_name'),
					);
					$this->data['last_name'] = array(
							'name'  => 'last_name',
							'id'    => 'last_name',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('last_name'),
					);
					$this->data['email'] = array(
							'name'  => 'email',
							'id'    => 'email',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('email'),
					);
					$this->data['company'] = array(
							'name'  => 'company',
							'id'    => 'company',
							'type'  => 'text',
							'value' => $this->session->userdata("customer")['company'],
					);
					$this->data['phone'] = array(
							'name'  => 'phone',
							'id'    => 'phone',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('phone'),
					);
					$this->data['password'] = array(
							'name'  => 'password',
							'id'    => 'password',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('password'),
					);
					$this->data['password_confirm'] = array(
							'name'  => 'password_confirm',
							'id'    => 'password_confirm',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('password_confirm'),
					);
					
					//bubble count for events and feedbacks
					$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
					$this->data = array_merge($this->data,$data_bubble_count);
	
					$this->_render_page('auth/create_sub_admin', $this->data);
				}else{
	
					$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
					$email    = strtolower($this->input->post('email'));
					$password = $this->input->post('password');
	
	
					$additional_data = array(
							'first_name' => $this->input->post('first_name'),
							'last_name'  => $this->input->post('last_name'),
							'company'    => strtolower(str_replace(" ","",$this->input->post('company'))),
							'phone'      => $this->input->post('phone'),
					);
	
	
					//Update the groups user belongs to
					$groupData = array("Sub Admin");
					
					//Taking company details from Admin login session data
					$cusdetail = $this->ion_auth->customer()->row();
					$cusdetail = json_decode(json_encode($cusdetail),true);
	
					$create_usr = $this->ion_auth->register_sub_admin($username, $password, $email, $additional_data,$groupData,$cusdetail);
					
					//check to see if we are creating the user
					//redirect them back to the admin page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					//set the flash data error message if there is one
					$this->data['message'] = $this->ion_auth->messages();
	
					//list the users
					$this->data['users'] = $this->ion_auth->sub_admin()->result();
					//foreach ($this->data['users'] as $k => $user)
					//{
					//	$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
					//}
					
					//bubble count for events and feedbacks
					$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
					$this->data = array_merge($this->data,$data_bubble_count);
					
					$this->_render_page('admin/admin_dash_sub_admin', $this->data);
				}
			}
			else
			{
				//display the create user form
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				log_message('debug','ION_AUTH_ERRORS_SUB_ADMIN_1318'.print_r($this->ion_auth->errors(),true));
				log_message('debug','ION_AUTH_FLASHDATA_SUB_ADMIN_1318'.print_r($this->session->flashdata('message'),true));

				$company = $this->session->userdata("customer")['company'];
				$this->data['first_name'] = array(
						'name'  => 'first_name',
						'id'    => 'first_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('first_name'),
				);
				$this->data['last_name'] = array(
						'name'  => 'last_name',
						'id'    => 'last_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('last_name'),
				);
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('email'),
				);
				$this->data['company'] = array(
						'name'  => 'company',
						'id'    => 'company',
						'type'  => 'text',
						'value' => $this->session->userdata("customer")['company'],
				);
				$this->data['phone'] = array(
						'name'  => 'phone',
						'id'    => 'phone',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('phone'),
				);
				$this->data['password'] = array(
						'name'  => 'password',
						'id'    => 'password',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('password'),
				);
				$this->data['password_confirm'] = array(
						'name'  => 'password_confirm',
						'id'    => 'password_confirm',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('password_confirm'),
				);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
	
				$this->_render_page('auth/create_sub_admin', $this->data);
			}
		}else{
			$this->data['message'] = "Your sub admin creation limit is over!";
			
	
			$this->data['company'] = array(
					'name'  => 'company',
					'id'    => 'company',
					'type'  => 'text',
					'value' => $this->session->userdata("customer")['company'],
			);
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
	
	
			$this->_render_page('auth/create_sub_admin', $this->data);
	
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing user
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function delete_user($id)
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
	    {
	    	redirect(URC.'auth/login');
	    }
		$this->ion_auth->delete_user($id);
		$this->data['users'] = $this->ion_auth->users()->result();
		$this->data['numberofapps'] = count($this->ion_auth->apps());
		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
		$this->data['message'] = "User Deleted";
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_users',$this->data);
	
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Delete a existing user
	 *
	 * @author  Ben
	 *
	 *
	 */
	
	function delete_sub_admin($id)
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		$this->ion_auth->delete_sub_admin($id);
		$this->data['users'] = $this->ion_auth->sub_admin()->result();
		$this->data['numberofapps'] = count($this->ion_auth->apps());
		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
		$this->data['message'] = "User Deleted";
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_sub_admin',$this->data);
	
	}

	// --------------------------------------------------------------------

	 /**
	  * Helper : Listing all users with edit option
	  *
	  * @author  Selva
	  *
	  * 
	  */

	   function pre_edit_user()
	  {
	    if (!$this->ion_auth->logged_in())
	    {
			redirect(URC.'auth/login');
	    }
	    if ($this->ion_auth->is_user())
	    {
	    	redirect(URC.'auth/login');
	    }
	    $user = $this->session->userdata("customer");
		$company = $user['company'];
		$this->data['users'] = $this->ion_auth->users()->result();
		$this->load->model('Workflow_Model');
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_edit_users', $this->data);
	  }

	  // --------------------------------------------------------------------

	/**
	 * Helper : Listing all users with delete option
	 *
	 * @author  Selva
	 *
	 * 
	 */

	 function pre_delete_user()
	 {
	 	if (!$this->ion_auth->logged_in())
	 	{
			//redirect them to the login page
			redirect(URC.'auth/login');
	 	}
	 	if ($this->ion_auth->is_user())
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	$user = $this->session->userdata("customer");
	 	$company = $user['company'];
		$this->data['users'] = $this->ion_auth->users()->result();
		$this->load->model('Workflow_Model');
		$this->data['groups'] = $this->Workflow_Model->getgroups($company);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_delete_users', $this->data);
	  }
	 
	 
	  // --------------------------------------------------------------------
	  
	  /**
	   * Helper : Edit a existing user
	   *
	   * @author  Ben
	   *
	   *
	   */
	  
	  function edit_sub_admin($id)
	  {
	  	$this->data['title'] = "Edit Sub Admin";
	  
	  	if (!$this->ion_auth->logged_in() )
	  	{
	  		redirect(URC.'auth/login');
	  	}
	  	if ($this->ion_auth->is_user())
	  	{
	  		redirect(URC.'auth/login');
	  	}
	  
	  	$user = $this->ion_auth->sub_admins($id)->row();
	  
	  	$groups=$this->ion_auth->groups()->result_array();
	  	$currentGroups = $this->ion_auth->get_sub_admin_groups($id)->result();
	  
	  	//validate form input
	  	$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
	  	$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
	  	$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
	  	$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
	  	$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');
	  	$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
	  
	  	if (isset($_POST) && !empty($_POST))
	  	{
	  		// do we have a valid request?
	  		if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
	  		{
	  			show_error($this->lang->line('error_csrf'));
	  		}
	  
	  		$data = array(
	  				'first_name' => $this->input->post('first_name'),
	  				'last_name'  => $this->input->post('last_name'),
	  				'company'    => $this->input->post('company'),
	  				'phone'      => $this->input->post('phone')
	  		);
	  
	  		//Update the groups user belongs to
	  		$groupData = $this->input->post('groups');
	  
	  		if (isset($groupData) && !empty($groupData)) {
	  
	  			$this->ion_auth->remove_from_group_sub_admin('', $id);
	  
	  			foreach ($groupData as $grp) {
	  				$this->ion_auth->add_to_group_sub_admin($grp, $id);
	  			}
	  
	  		}
	  
	  		//update the password if it was posted
	  		if ($this->input->post('password'))
	  		{
	  			$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
	  			$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
	  
	  			$data['password'] = $this->input->post('password');
	  		}
	  
	  		//update the email if it was posted
	  		if ($this->input->post('email'))
	  		{
	  			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
	  			$data['email'] = $this->input->post('email');
	  		}
	  
	  		if ($this->form_validation->run() === TRUE && $this->ion_auth->update_sub_admin($user->id, $data))
	  		{
	  			// unset csrf userdata
	  			unset_csrf_userdata();
	  
	  			log_message('debug','AUTH=====EDIT_USER=====all_userdata=====822'.print_r($this->session->all_userdata(),true));
	  
	  			$this->session->set_flashdata('message',$this->ion_auth->messages());
	  			redirect('dashboard/sub_admin');
	  		}
	  		else
	  		{
	  
	  			//display the edit user form
	  			$this->data['csrf'] = get_csrf_nonce();
	  
	  			//set the flash data error message if there is one
	  			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
	  
	  			//pass the user to the view
	  			$this->data['user'] = $user;
	  			$this->data['groups'] = $groups;
	  			$this->data['currentGroups'] = $currentGroups;
	  
	  			$this->data['first_name'] = array(
	  					'name'  => 'first_name',
	  					'id'    => 'first_name',
	  					'type'  => 'text',
	  					'value' => $this->form_validation->set_value('first_name', $user->first_name),
	  			);
	  			$this->data['last_name'] = array(
	  					'name'  => 'last_name',
	  					'id'    => 'last_name',
	  					'type'  => 'text',
	  					'value' => $this->form_validation->set_value('last_name', $user->last_name),
	  			);
	  			$this->data['company'] = array(
	  					'name'    => 'company',
	  					'id'      => 'company',
	  					'type'    => 'text',
	  					'value'   => $this->form_validation->set_value('company', $user->company),
	  					'readonly'=> 'readonly',
	  			);
	  			$this->data['phone'] = array(
	  					'name'  => 'phone',
	  					'id'    => 'phone',
	  					'type'  => 'text',
	  					'value' => $this->form_validation->set_value('phone', $user->phone),
	  			);
	  
	  			$this->data['email'] = array(
	  					'name'  => 'email',
	  					'id'    => 'email',
	  					'type'  => 'text',
	  					'value' => $this->form_validation->set_value('email', $user->email),
	  			);
	  
	  			$this->data['password'] = array(
	  					'name' => 'password',
	  					'id'   => 'password',
	  					'type' => 'password'
	  			);
	  			$this->data['password_confirm'] = array(
	  					'name' => 'password_confirm',
	  					'id'   => 'password_confirm',
	  					'type' => 'password'
	  			);
	  
	  			
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
				
				$this->_render_page('auth/edit_sub_admin', $this->data);
	  		}
	  	}
	  
	  	//display the edit user form
	  	$this->data['csrf'] = get_csrf_nonce();
	  
	  	//set the flash data error message if there is one
	  	$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
	  
	  	//pass the user to the view
	  	$this->data['user'] = $user;
	  	$this->data['groups'] = $groups;
	  	$this->data['currentGroups'] = $currentGroups;
	  
	  	$this->data['first_name'] = array(
	  			'name'  => 'first_name',
	  			'id'    => 'first_name',
	  			'type'  => 'text',
	  			'value' => $this->form_validation->set_value('first_name', $user->first_name),
	  	);
	  	$this->data['last_name'] = array(
	  			'name'  => 'last_name',
	  			'id'    => 'last_name',
	  			'type'  => 'text',
	  			'value' => $this->form_validation->set_value('last_name', $user->last_name),
	  	);
	  	$this->data['company'] = array(
	  			'name'    => 'company',
	  			'id'      => 'company',
	  			'type'    => 'text',
	  			'value'   => $this->form_validation->set_value('company', $user->company),
	  			'readonly'=> 'readonly',
	  	);
	  	$this->data['phone'] = array(
	  			'name'  => 'phone',
	  			'id'    => 'phone',
	  			'type'  => 'text',
	  			'value' => $this->form_validation->set_value('phone', $user->phone),
	  	);
	  
	  	$this->data['email'] = array(
	  			'name'  => 'email',
	  			'id'    => 'email',
	  			'type'  => 'text',
	  			'value' => $this->form_validation->set_value('email', $user->email),
	  	);
	  
	  	$this->data['password'] = array(
	  			'name' => 'password',
	  			'id'   => 'password',
	  			'type' => 'password'
	  	);
	  
	  	$this->data['password_confirm'] = array(
	  			'name' => 'password_confirm',
	  			'id'   => 'password_confirm',
	  			'type' => 'password'
	  	);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
	  
	  	$this->_render_page('auth/edit_sub_admin', $this->data);
	  }
	  
	 // --------------------------------------------------------------------

	 /**
	  * Helper : Listing all users with activate/deactivate option
	  *
	  * @author  Selva
	  *
	  * 
	  */

     function pre_activate()
	 {
	    if (!$this->ion_auth->logged_in())
	    {
		  	//redirect them to the login page
			redirect(URC.'auth/login');
	    }
	    if ($this->ion_auth->is_user())
	    {
	    	redirect(URC.'auth/login');
	    }
	    $user = $this->session->userdata("customer");
		$company = $user['company'];
		$this->load->model('Workflow_Model');
		$this->data['users'] = $this->ion_auth->users()->result();
		$this->data['groups'] = $this->Workflow_Model->getgroups($company);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_activate_de_users', $this->data);
	 }

	 //************** USERS ***************END***************//

	 //************** GROUPS ***************START***************//

	 // --------------------------------------------------------------------

	 /**
	 * Helper : Create a new group
	 *
	 * @author  Ben
	 *
	 * 
	 */

	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');
	
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		
	
		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'callback_check_string|required|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');
		 
		if ($this->form_validation->run() == TRUE)
		{
	
			$group_exists= $this->ion_auth->group_exists($this->input->post('group_name'));
			
			if($group_exists){
				if(! $this->ion_auth->is_user()){
				$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
				}else{
					$new_group_id = false;
				}
				if($new_group_id)
				{
					// check to see if we are creating the group
					// redirect them back to the groups page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->data['message'] = $this->ion_auth->messages();
					$user = $this->session->userdata("customer");
					$company = $user['company'];
					$this->load->model('Workflow_Model');
					$this->data['groups'] = $this->Workflow_Model->getgroups($company);
					//bubble count for events and feedbacks
					$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
					$this->data = array_merge($this->data,$data_bubble_count);
					
					$this->_render_page('admin/admin_dash_groups',$this->data);
	
				}
			}else{
				$this->data['message'] = "Group already exists!";
				$this->data['group_name'] = array(
						'name'  => 'group_name',
						'id'    => 'group_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('group_name'),
				);
				$this->data['description'] = array(
						'name'  => 'description',
						'id'    => 'description',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('description'),
				);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
		
				$this->_render_page('auth/create_group', $this->data);
			}
		}
		else
		{
		
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
					'name'  => 'group_name',
					'id'    => 'group_name',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
					'name'  => 'description',
					'id'    => 'description',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('description'),
			);
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
	
			$this->_render_page('auth/create_group', $this->data);
		}
	}

     // --------------------------------------------------------------------

	/**
	 * Helper : Listing all groups with edit option
	 *
	 * @author  Selva
	 *
	 * 
	 */

	 function pre_edit_group()
	 {
	    if (!$this->ion_auth->logged_in())
	    {
	    	//redirect them to the login page
			redirect(URC.'auth/login');
	    }
	    if ($this->ion_auth->is_user())
	    {
	    	redirect(URC.'auth/login');
	    }
	    $user = $this->session->userdata("customer");
		$company = $user['company'];
		$this->load->model('Workflow_Model');
		$this->data['groups'] = $this->Workflow_Model->getgroups($company);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_edit_group', $this->data);
	 }
	 
	 /**
	  * Helper : Listing all users with delete option
	  *
	  * @author  Selva
	  *
	  *
	  */
	  
	 function pre_delete_sub_admin()
	 {
	 	if (!$this->ion_auth->logged_in())
	 	{
	 		//redirect them to the login page
	 		redirect(URC.'auth/login');
	 	}
	 	if ($this->ion_auth->is_user())
	 	{
	 		redirect(URC.'auth/login');
	 	}
	 	$user = $this->session->userdata("customer");
	 	$company = $user['company'];
	 	$this->data['users'] = $this->ion_auth->sub_admin()->result();
	 	$this->load->model('Workflow_Model');
	 	$this->data['groups'] = $this->Workflow_Model->getgroups($company);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	 	$this->_render_page('admin/admin_pre_delete_sub_admin', $this->data);
	 }

	// --------------------------------------------------------------------

	/**
	 * Helper : Edit an existing group
	 *
	 * @author  Ben
	 *
	 * 
	 */
	function edit_group($id,$name)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect(URC.'auth/login');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in()) 
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
        
		$group = json_decode(json_encode($this->ion_auth->group_by_name($name)->row()),true);
		

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				    redirect('dashboard/groups');
			}
		}

		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;
		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group['name']),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group['description']),
		);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

		$this->_render_page('auth/edit_group', $this->data);
	}
	
	/**
	 * Helper : List sub admin
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function pre_activate_sub_admin()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		//set the flash data error message if there is one
		$this->data['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		//list the users
		$this->data['users'] = $this->ion_auth->sub_admin()->result();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_activate_de_sub_admin', $this->data);
	}
	
	// --------------------------------------------------------------------
	 
	/**
	 * Helper : Listing all users with edit option
	 *
	 * @author  Selva
	 *
	 *
	 */
	 
	function pre_edit_sub_admin()
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		if ($this->ion_auth->is_user())
		{
			redirect(URC.'auth/login');
		}
		$user = $this->session->userdata("customer");
		$company = $user['company'];
		$this->data['users'] = $this->ion_auth->sub_admin()->result();
		$this->load->model('Workflow_Model');
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_edit_sub_admins', $this->data);
	}

    //************** GROUPS ***************END***************//

		 /**
	  * Helper: Setting enterprise admin/user login data as session data
	  *
	  * @param	array $data
	  *
	  *
	  * @author Vikas
	  */
	 
	 function set_ghmc_session($data)
	 {
	 	$ghmc_data = base64_decode($data);
	 	$ghmc_res = json_decode($ghmc_data,true);
	 	$this->session->set_userdata("customer",$ghmc_res);
	 	$i = $this->session->userdata("customer");
	 	log_message('debug','at the authhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh'.print_r($i,true));
	 	redirect(URL.'ghmc_login/ghmc_dashboard');
	 }
	
	function create_ghmc_user()
	{
		$this->data['title'] = "Create GHMC User";
	
		// if (!$this->ion_auth->logged_in())
		{
			// redirect(URC.'auth/login');
		}
		 
		//validate user creation limit
		//if($this->ion_auth->check_user_limit())
			log_message('debug','withttttttttttttttttttthijdfdugfuydgfiusgacigbwigigiuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu');
		{
			//validate form input
			$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|min_length[6]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
			$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
			$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
			log_message('debug','1111111113333333333333ssssssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($this->form_validation->run(),true));
			if ($this->form_validation->run() == true)
			{
				$user_exists= $this->ion_auth->ghmc_user_exists($this->input->post('phone'));
				if ($user_exists) {
					//display the create user form
					//set the flash data error message if there is one
					$this->data['message'] = "Mobile number already used!";
					$company = "ghmc";
					$this->data['first_name'] = array(
							'name'  => 'first_name',
							'id'    => 'first_name',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('first_name'),
					);
					$this->data['last_name'] = array(
							'name'  => 'last_name',
							'id'    => 'last_name',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('last_name'),
					);
					$this->data['company'] = array(
							'name'  => 'company',
							'id'    => 'company',
							'type'  => 'text',
							'value' => $this->session->userdata("customer")['company'],
					);
					$this->data['phone'] = array(
							'name'  => 'phone',
							'id'    => 'phone',
							'type'  => 'text',
							'value' => $this->form_validation->set_value('phone'),
					);

					$this->data['dob'] = array(
							'name'  => 'dob',
							'id'    => 'dob',
							'type'  => 'date',
							'value' => $this->form_validation->set_value('dob'),
					);
	
					$this->_render_page('ghmc_login/create_ghmc_user', $this->data);
				}else{
						
					$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
	
					log_message('debug','$_POST=====1153'.print_r($_POST,true));
					//$customer_data = $this->ion_auth->customer()->row();
					//log_message('debug','$customer_data=====1153'.print_r($customer_data,true));
						
					$additional_data = array(
							'first_name'         => $this->input->post('first_name'),
							'last_name'          => $this->input->post('last_name'),
							'company'            => strtolower(str_replace(" ","",$this->input->post('company'))),
							'phone'              => $this->input->post('phone'),
							'username'			 => $username,
							'dob'				 => $this->input->post('dob'),
					);
					
					$create_usr = $this->ion_auth->register_ghmc_user($additional_data);
					//check to see if we are creating the user
					//redirect them back to the admin page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					//set the flash data error message if there is one
					$this->data['message'] = $this->ion_auth->messages();
						
					
					redirect("ghmc_login/ghmc_dashboard");
					//$this->_render_page('ghmc_login/ghmc_dash_users', $this->data);
				}
			}
			else
			{
				//display the create user form
				//set the flash data error message if there is one
				log_message('debug','validationsssssssssssssssssssssssssssssssssssssssssssssss'.print_r(validation_errors(),true));
				log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii'.print_r($this->ion_auth->errors(),true));
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$company = $this->session->userdata("customer")['company'];
				$this->data['first_name'] = array(
						'name'  => 'first_name',
						'id'    => 'first_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('first_name'),
				);
				$this->data['last_name'] = array(
						'name'  => 'last_name',
						'id'    => 'last_name',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('last_name'),
				);
				$this->data['company'] = array(
						'name'  => 'company',
						'id'    => 'company',
						'type'  => 'text',
						'value' => $this->session->userdata("customer")['company'],
				);
				$this->data['phone'] = array(
						'name'  => 'phone',
						'id'    => 'phone',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('phone'),
				);

					$this->data['dob'] = array(
							'name'  => 'dob',
							'id'    => 'dob',
							'type'  => 'date',
							'value' => $this->form_validation->set_value('dob'),
					);
	log_message('debug','validationsssssssssssssssssssssssssssssssssssssssssssssss'.print_r(validation_errors(),true));
					$this->_render_page('ghmc_login/create_ghmc_user', $this->data);
			}
		//}else{
				
// 			$this->data['company'] = array(
// 					'name'  => 'company',
// 					'id'    => 'company',
// 					'type'  => 'text',
// 					'value' => $this->session->userdata("customer")['company'],
// 			);
				
// 			//bubble count for events and feedbacks
// 			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
// 			$this->data = array_merge($this->data,$data_bubble_count);
				
// 			$this->_render_page('auth/create_user', $this->data);
				
		}
	}
	
}
