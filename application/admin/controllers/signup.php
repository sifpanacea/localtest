<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

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
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccgdfvgdgfuiwegfiuew'.print_r($this->config->item('language'),true));
		//$language = $this->session->userdata("language");
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccgdfvgdgfuiwegfiuew'.print_r($this->config->item('language'),true));
		
		
		$this->load->library('ion_auth');
		$this->config->load('email');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->library('mongo_db');
		$this->load->library('signup_lib');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->model('Admin_Model');
		
	}

    // --------------------------------------------------------------------

	/**
	 * Loading enterprise customer signup page
	 *
	 * @author  Selva
	 *
	 * 
	 */

	public function customer_signup()
	{
	    $this->load->view('signup/signup_form');
	}

    // --------------------------------------------------------------------

	/**
	 * Loading API customer signup page
	 *
	 * @author  Vikas
	 *
	 * 
	 */

	public function api_signup()
	{
	    $this->data['customerslist'] = $this->Admin_Model->get_customer_details();
	    $this->load->view('signup/api_signup_form',$this->data);
	}


    // --------------------------------------------------------------------

	/**
	 * Enterprise customer signup 
	 *
	 * @author  Ben ( Modified by Selva)
	 *
	 * 
	 */

	public function create_customer()
	{
	    $this->data['title'] = "Create Customer";

		//validate form input
		$this->form_validation->set_rules('companyname', $this->lang->line('signup_customer_company_name'), 'required|min_length[5]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('companywebsite', $this->lang->line('signup_customer_company_website'), 'required|xss_clean');
		$this->form_validation->set_rules('companyaddress', $this->lang->line('signup_customer_company_address'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('signup_customer_company_contact_email'), 'required|valid_email');
		$this->form_validation->set_rules('password', $this->lang->line('signup_customer_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('confirmpassword', $this->lang->line('signup_customer_confirm_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'xss_clean');
		$this->form_validation->set_rules('mobile', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
        $this->form_validation->set_rules('contactperson', $this->lang->line('signup_customer_company_contact_person'), 'required|xss_clean');
		$this->form_validation->set_rules('plan', $this->lang->line('signup_customer_plan'), 'xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$companyname     = $this->input->post('companyname');
			$companyaddress  = $this->input->post('companyaddress');
			$contactperson   = $this->input->post('contactperson');
			$activationcode  = md5(uniqid(rand(),true));

			$additional_data = array(
				'mobile'              => $this->input->post('mobile'),
				'email'               => $this->input->post('email'),
				'username'            => $this->input->post('username'),
				'password'            => $this->input->post('password'),
				'confirmpassword'     => $this->input->post('confirmpassword'),
				'plan'                => $this->input->post('plan'),
				'display_company_name'=>$this->input->post('companyname'),
				'companywebsite'      => $this->input->post('companywebsite'),
				'activation_code'     => $activationcode
			);

			$company_name = strtolower(str_replace(" ","",$companyname));
			
		}

		if (($this->form_validation->run() == true) && ($id = $this->ion_auth->signup($company_name, $companyaddress, $contactperson, $additional_data))) 
		{

		    $docsresult = $this->ion_auth->create_total_docs_collection("total_docs",$company_name);

		    $tempresult = $this->ion_auth->create_templates_collection("templates",$company_name);

		    $configres = $this->signup_lib->init($company_name);

            $this->data['message'] = $this->ion_auth->messages();
			$this->_render_page('signup/signup_success',$this->data);
		}
		else
		{
			//display the signup form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['companyname'] = array(
				'name'  => 'companyname',
				'id'    => 'companyname',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('companyname'),
			);
			$this->data['companyaddress'] = array(
				'name'  => 'companyaddress',
				'id'    => 'companyaddress',
				'type'  => 'textarea',
				'value' => $this->form_validation->set_value('companyaddress'),
			);
			$this->data['contactperson'] = array(
				'name'  => 'contactperson',
				'id'    => 'contactperson',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contactperson'),
			);
			$this->data['mobile'] = array(
				'name'  => 'mobile',
				'id'    => 'mobile',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('mobile'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'email',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['companywebsite'] = array(
				'name'  => 'companywebsite',
				'id'    => 'companywebsite',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('companywebsite'),
			);
			$this->data['plan'] = array(
				'name'  => 'plan',
				'id'    => 'plan',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('plan'),
			);
			$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['confirmpassword'] = array(
				'name'  => 'confirmpassword',
				'id'    => 'confirmpassword',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('confirmpassword'),
			);

			$this->_render_page('signup/signup_form', $this->data);
		}
	}

    // --------------------------------------------------------------------

	/**
	 * API customer signup 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

	public function api_create_customer()
	{
	    $this->data['title'] = "Create API Customer";
	   
		//Validate form input
		
		$this->form_validation->set_rules('companyname', $this->lang->line('api_customer_company_name'), 'required|min_length[5]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('comp_type', $this->lang->line('api_customer_company_type'), 'required|xss_clean');
		$this->form_validation->set_rules('companywebsite', $this->lang->line('api_customer_company_website'), 'required|xss_clean');
		$this->form_validation->set_rules('companyaddress', $this->lang->line('api_customer_company_address'), 'required|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('api_customer_username'), 'xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('api_customer_company_email'), 'required|valid_email');
		$this->form_validation->set_rules('password', $this->lang->line('api_customer_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('confirmpassword', $this->lang->line('api_customer_confirm_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('mobile', $this->lang->line('api_customer_company_contact_mobile'), 'required|xss_clean');
		$this->form_validation->set_rules('customer', $this->lang->line('api_customer_names'), 'xss_clean');
		
		if ($this->form_validation->run() == true)
		{
			$companyname     = $this->input->post('companyname');
			$companyaddress  = $this->input->post('companyaddress');
            $activationcode  = md5(uniqid(rand(),true));

			$company_name = strtolower(str_replace(" ","",$companyname));
			
			$additional_data = array(
				'company_name'        => $company_name,
				'type'                => $this->input->post('comp_type'),
				'collection'          => $company_name,
				'company_address'     => $companyaddress,
				'mobile' 		      => $this->input->post('mobile'),
				'email'               => $this->input->post('email'),
				'companywebsite'      => $this->input->post('companywebsite'),
				'username'            => $this->input->post('username'),
				'password'            => $this->input->post('password'),
				'confirmpassword'     => $this->input->post('confirmpassword'),
				'display_company_name'=> $this->input->post('companyname'),
				'activation_code'     => $activationcode,
				'api_key'             => md5($company_name),
				'access'		      => md5(uniqid(rand(),true)),
				'customer'		      => $this->input->post('customer',TRUE)
			);
			
			
			if($this->ion_auth->signup_api($additional_data))
			{
				$this->data['message'] = $this->ion_auth->messages();
				$this->_render_page('signup/signup_success', $this->data);
			}
			else
			{
				//display the signup form
				
				$this->data['customerslist'] = $this->Admin_Model->get_customer_details();
				
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				$this->data['companyname'] = array(
						'name'  => 'companyname',
						'id'    => 'companyname',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('companyname'),
				);
				$this->data['companyaddress'] = array(
						'name'  => 'companyaddress',
						'id'    => 'companyaddress',
						'type'  => 'textarea',
						'value' => $this->form_validation->set_value('companyaddress'),
				);
				$this->data['mobile'] = array(
						'name'  => 'mobile',
						'id'    => 'mobile',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('mobile'),
				);
				$this->data['email'] = array(
						'name'  => 'email',
						'id'    => 'email',
						'type'  => 'email',
						'value' => $this->form_validation->set_value('email'),
				);
				$this->data['companywebsite'] = array(
						'name'  => 'companywebsite',
						'id'    => 'companywebsite',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('companywebsite'),
				);
				$this->data['username'] = array(
						'name'  => 'username',
						'id'    => 'username',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('username'),
				);
				$this->data['password'] = array(
						'name'  => 'password',
						'id'    => 'password',
						'type'  => 'password',
						'value' => $this->form_validation->set_value('password'),
				);
				$this->data['confirmpassword'] = array(
						'name'  => 'confirmpassword',
						'id'    => 'confirmpassword',
						'type'  => 'password',
						'value' => $this->form_validation->set_value('confirmpassword'),
				);
				
				$this->_render_page('signup/api_signup_form', $this->data);
			}
		}
		else
		{
			//display the signup form
			
			$this->data['customerslist'] = $this->Admin_Model->get_customer_details();
			
			log_message('debug','$this->data[customerslist]=====ADMIN_MODEL=====24'.print_r($this->data['customerslist'],true));
			
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			
			$this->data['companyname'] = array(
				'name'  => 'companyname',
				'id'    => 'companyname',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('companyname'),
			);
			$this->data['companyaddress'] = array(
				'name'  => 'companyaddress',
				'id'    => 'companyaddress',
				'type'  => 'textarea',
				'value' => $this->form_validation->set_value('companyaddress'),
			);
			$this->data['mobile'] = array(
				'name'  => 'mobile',
				'id'    => 'mobile',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('mobile'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'email',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['companywebsite'] = array(
				'name'  => 'companywebsite',
				'id'    => 'companywebsite',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('companywebsite'),
			);
			$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['confirmpassword'] = array(
				'name'  => 'confirmpassword',
				'id'    => 'confirmpassword',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('confirmpassword'),
			);

			$this->_render_page('signup/api_signup_form', $this->data);
		}
		
	}


     // --------------------------------------------------------------------

	/**
	 * Loading Activation success page
	 *
	 * @author  Selva
	 *
	 * 
	 */

	 public function activation_success()
	 {
	    $this->data['message'] = "Activation successful";
	    $this->_render_page('signup/activation_success', $this->data);
	 }
	 
	 // --------------------------------------------------------------------

	/**
	 * Loading user pre registration page
	 *
	 * @author  Selva
	 *
	 * 
	 */
	 
	 function user_registration()
	 {
	   $this->data['message'] = 'Enter Your Device Unique Number !';
	   $this->_render_page('signup/user_pre_self_register',$this->data);
	 
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * User registration post process
	 *
	 * @author  Selva
	 *
	 * 
	 */
	 
	 function register_user_with_device()
	 {
	    $groups = array();
		
	    //validate form input
	    $this->form_validation->set_rules('device_unique_no', 'Device Unique Number', 'required');
		
	    $device_uniq_no = $this->input->post('device_unique_no');
		
		$registered_device_details = $this->Admin_Model->get_device_details_by_device_unique_number($device_uniq_no);
		
		if($registered_device_details)
		{
		    $device_unique_number = $registered_device_details['device_unique_number'];
			$company              = $registered_device_details['subscribed_with'];
			$plan                 = $registered_device_details['plan_subscribed'];
			$companyname          = implode(" ",$company);
			
			$group_details = $this->ion_auth->get_groups_by_companyname($companyname);
			foreach($group_details as $details)
			{
			   array_push($groups,$details['name']);
			}
			
			$this->data['subscribed_with'] = $company;
			$this->data['plans']           = $plan;
			$this->data['groups']          = $groups;
			$this->data['device_uniq_no']  = $device_unique_number;
			$this->_render_page('signup/user_post_self_register', $this->data);
		}
        else
        {
            $this->data['message']  = "Device Unique Number does not exists";
			$this->_render_page('signup/user_pre_self_register', $this->data);
        } 		
    }
	
	// --------------------------------------------------------------------

	/**
	 * User registration post process
	 *
	 * @author  Selva
	 *
	 * 
	 */
	
	public function save_user_details_with_device()
	{
	    $this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('password_confirm', 'confirm_password', 'required');
		 
	    if (isset($_POST) && !empty($_POST))
	    {
		   $email    = $this->input->post('email');
		   $password = $this->input->post('password');
		   
		   $additional_data = array(
				'first_name'        => $this->input->post('first_name'),
				'last_name'         => $this->input->post('last_name'),
				'phone'             => $this->input->post('phone'),
				'active'            => 1,
				'username'          => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
				'company'           => strtolower(str_replace(" ","",$this->input->post('company'))),
				'groups'            => $this->input->post('groupname')
			);
			
			$device_unique_number = $this->input->post('dev_uni_no');
			
		   if ($this->form_validation->run() === TRUE)
		   {
				$result = $this->ion_auth->update_user_registration_details($device_unique_number,$email,$password,$additional_data);
				
				if($result)
				{
				   $this->data['message'] = "User Details Saved";
				   $this->_render_page('signup/user_self_register_success', $this->data);
				}
				else
				{
				  $this->data['message'] = "User Details not saved";
				  $this->_render_page('signup/user_post_self_register', $this->data);
				}
				
		   }
		
		}
	}	
		
}

/* End of file signup.php */
/* Location: ./application/admin/controllers/signup.php */