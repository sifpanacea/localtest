<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolhealth_auth extends CI_Controller {

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
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->model('schoolhealth_auth_model');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : School Health web login (Admin/Schools/Clinics) 
	 *
	 * @author  Selva
	 *
	 */

	function login()
	{
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == true)
		{
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

            if($this->schoolhealth_auth_model->web_login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
		
			}
			else
			{
		         //if the login was un-successful
				//redirect them back to the login page
				
				$this->data['message'] = $this->ion_auth->errors();
				$this->_render_page('schoolhealth_login/schoolhealth_login', $this->data);
			}
		}
		else
		{
	       //the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

			$this->_render_page('schoolhealth_login/schoolhealth_login', $this->data);
	
		}
		
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Schoolhealth app login ( Students )
	 *
	 * @author  Selva
	 *
	 */

	function dashlogin()
	{
		$uniqueid   = $this->input->post('uniqueid');
		$password   = $this->input->post('password');
		
		$this->schoolhealth_auth_model->student_device_login($uniqueid,$password);
	}
	
	//-------------------------------------------------------------------------------------

	/**
	 * Helper : Forgot password - Doctors/Sugar365days admin/Counsellor
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
				'id' => 'email'
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' )
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('child_care_login/forgot_password', $this->data);
		}
		else
		{
	        log_message('debug','$identity=====107'.print_r($this->input->post('email'),true));
		    $identity = $this->diabetic_care_auth_model->verify_email_for_forgot_password(strtolower($this->input->post('email')));
			log_message('debug','$identity=====109'.print_r($identity,true));
			
			if(!$identity) {
                //$this->ion_auth->set_message('forgot_password_email_not_found');
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("child_care_auth/forgot_password");
            }
            
			//run the forgotten password method to email an activation code to the customer
			$forgotten_password_code = $this->diabetic_care_auth_model->forgotten_password(strtolower($this->input->post('email')));
            log_message('debug','$forgotten=====389'.print_r($forgotten_password_code,true));
			if($forgotten_password_code)
			{
		       // email reset password link
				$fromaddress = $this->config->item('smtp_user');
                $this->email->set_newline("\r\n");
                $this->email->set_crlf( "\r\n" );
                $this->email->from($fromaddress,'TLSTEC');
                $this->email->to($this->input->post('email'));
                $this->email->subject("Reset Password");
			    $message = " Hi, <br/> <br/>  To change your password, please click on this link:\n\n";
			    $message .= URL . 'diabetic_care_auth/reset_password/'. $forgotten_password_code;
			    $this->email->message($message);
			    if ($this->email->send())
				{
				   //if there were no errors
				   $this->data['message'] = "";
			       $this->_render_page('diabetic_care_login/forgot_password_success', $this->data);
				  //$this->session->set_flashdata('message', $this->ion_auth->messages());
				  //redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
				}
				else
				{
			       log_message('debug','PRINT_DEBUGGER====145====='.print_r($this->email->print_debugger(),true));
				   $this->session->set_flashdata('message',"Try again !");
				   redirect("child_care_auth/forgot_password", 'refresh');
				}
				
				
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("child_care_auth/forgot_password", 'refresh');
			}
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * reset password - final step for forgotten password
	 *
	 *
	 */ 

	public function reset_password($code = NULL,$verifycode='')
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->diabetic_care_auth_model->forgotten_password_check($code);
		
		if($user)
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
				
				//$this->data['csrf'] = get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('diabetic_care_login/reset_password_new', $this->data);
			}
			else
			{
					$identity = $user[0]['email'];

					$change = $this->diabetic_care_auth_model->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed

						//$this->data['message'] = $this->ion_auth->messages();
				        //$this->_render_page('diabetic_care_login/diabetic_care_login', $this->data);
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect('diabetic_care_auth/login');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('diabetic_care_auth/reset_password/' . $code, 'refresh');
					}
	
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("diabetic_care_auth/forgot_password", 'refresh');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Diagnostic center signup 
	 *
	 * @author  Ben ( Modified by Selva)
	 *
	 * 
	 */

	public function register_doctor()
	{
	    $this->data['title'] = "Doctor Registration";

		//validate form input
		$this->form_validation->set_rules('drname', $this->lang->line('signup_dr_name'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('signup_customer_company_contact_email'), 'required|valid_email');
		$this->form_validation->set_rules('password', $this->lang->line('signup_customer_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('confirmpassword', $this->lang->line('signup_customer_confirm_password'), 'required|min_length[8]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('draddress', $this->lang->line('signup_dr_address'), 'required|xss_clean');
		$this->form_validation->set_rules('city', $this->lang->line('signup_dr_city'), 'required|xss_clean');
		$this->form_validation->set_rules('state', $this->lang->line('signup_dr_state'), 'required|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'xss_clean');
		$this->form_validation->set_rules('mobile', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
        $this->form_validation->set_rules('contactperson', $this->lang->line('signup_customer_company_contact_person'), 'required|xss_clean');
		$this->form_validation->set_rules('plan', $this->lang->line('signup_customer_plan'), 'xss_clean');
		
		
		if($this->form_validation->run() == true)
		{
			$name     		 = $this->input->post('drname');
			$address         = $this->input->post('draddress');
			$contactperson   = $this->input->post('contactperson');
			$activationcode  = md5(uniqid(rand(),true));

			$additional_data = array(
				'mobile'              => $this->input->post('mobile'),
				'email'               => $this->input->post('email'),
				'username'            => $this->input->post('username'),
				'password'            => $this->input->post('password'),
				'confirmpassword'     => $this->input->post('confirmpassword'),
				'plan'                => $this->input->post('plan'),
				'company_name'        => TENANT,
				'display_company_name'=> TENANT,
				'city'                => $this->input->post('city'),
				'state'               => $this->input->post('state'),
				'activation_code'     => $activationcode
			);

	    }

		if(($this->form_validation->run() == true) && ($id = $this->ion_auth->signup_doctor($name, $address, $contactperson, $additional_data))) 
		{
		   $this->data['message'] = $this->ion_auth->messages();
		   $this->_render_page('signup/signup_success',$this->data);
		}
		else
		{
			//display the signup form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['drname'] = array(
				'name'  => 'drname',
				'id'    => 'drname',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('drname'),
			);
			$this->data['draddress'] = array(
				'name'  => 'draddress',
				'id'    => 'draddress',
				'type'  => 'textarea',
				'value' => $this->form_validation->set_value('draddress'),
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

			$this->_render_page('sugar365days_signup/doc_signup_form', $this->data);
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Forgot password - Device User ( Patient )
	 *
	 *
	 */ 
	 
	public function forgot_password_patient_device()
    {
	  $patient_id = strtolower($this->input->post('patient_id'));
	  
	  $identity = $this->diabetic_care_auth_model->verify_identity_for_forgot_password_patient_device($patient_id);
	  
	  if($identity)
	  {
	    $forgot_pwd_process = $this->diabetic_care_auth_model->forgotten_password_patient_device($patient_id);
		
		log_message('debug','$forgotten_password_code=======875=====verify'.print_r($forgot_pwd_process,true));
		
		if(isset($forgot_pwd_process) && is_array($forgot_pwd_process))
		{
	       $message = " Hi, <br/> <br/>  To change your password, please click on this link:\n\n";
		   $message .= URL . 'diabetic_care_auth/reset_password_patient_device/'. $forgot_pwd_process['forgotten_password_code'];
			  
	      /* // send email if email id is there
		  if(isset($forgot_pwd_process['email']))
		  {
			  $fromaddress = $this->config->item('smtp_user');
			  $this->email->set_newline("\r\n");
			  $this->email->set_crlf( "\r\n" );
			  $this->email->from($fromaddress,'Sugar365days');
			  $this->email->to($forgot_pwd_process['email']);
			  $this->email->subject("Reset Password");
			  
			  $this->email->message($message);
			  if ($this->email->send())
			  {
				echo "SUCCESS";
			  }
			  else
			  {
				echo "FALSE";
			  }
		  } */
		  
		  //send sms
		  if(isset($forgot_pwd_process['mobile']))
		  {
	        $this->bhashsms->send_sms($forgot_pwd_process['mobile']['mob_num'],$message);
		  }
		   
		}
		else
		{
		   echo "FALSE";
		}			
	  }
	  else
	  {
	    echo "NOT_REG_PATIENT";
	  }
	 
	 }
	 
	 // --------------------------------------------------------------------

	/**
	 * Forgot password - Device User ( Doctor )
	 *
	 *
	 */ 
	 
	public function forgot_password_doctor_device()
    {
	  $email = strtolower($this->input->post('email'));
	  
	  $identity = $this->diabetic_care_auth_model->verify_email_for_forgot_password_doctor_device(strtolower($this->input->post('email')));
	  
	  if($identity)
	  {
	    //run the forgotten password method to email an activation code to the user
		$forgotten_password_code = $this->diabetic_care_auth_model->forgotten_password_device_user($email);
		
		if($forgotten_password_code)
		{
		  $fromaddress = $this->config->item('smtp_user');
		  $this->email->set_newline("\r\n");
		  $this->email->set_crlf( "\r\n" );
		  $this->email->from($fromaddress,'TLSTEC');
		  $this->email->to($email);
		  $this->email->subject("Reset Password");
		  $message = " Hi, <br/> <br/>  To change your password, please click on this link:\n\n";
		  $message .= URL . 'diabetic_care_auth/reset_password_doctor_device/'. $forgotten_password_code;
		  $this->email->message($message);
		  if ($this->email->send())
		  {
			echo "SUCCESS";
		  }
		  else
		  {
			echo "FALSE";
		  }
		   
		}
		else
		{
		   echo "FALSE";
		}			
	  }
	  else
	  {
	    echo "NOT_REG_EMAIL";
	  }
	 
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * reset password - final step for forgotten password
	 *
	 *
	 */ 

	public function reset_password_patient_device($code = NULL,$verifycode='')
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->diabetic_care_auth_model->forgotten_password_check($code);
		
		log_message('debug','diabetic_care_auth=====reset_password_patient_device=====$code'.print_r($code,true));
		
		log_message('debug','diabetic_care_auth=====reset_password_patient_device=====$user'.print_r($user,true));
		
		if($user)
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
				
				//$this->data['csrf'] = get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('diabetic_care_login/reset_password_patient_device', $this->data);
			}
			else
			{
				$identity = $user[0]['unique_id'];
					
				log_message('debug','diabetic_care_auth=====reset_password_patient_device=====$identity'.print_r($identity,true));

				$change = $this->diabetic_care_auth_model->reset_password($identity, $this->input->post('new'));
					
				log_message('debug','diabetic_care_auth=====reset_password_patient_device=====$change'.print_r($change,true));

				if ($change)
				{
					//if there were no errors
					$this->data['message'] = "";
					$this->_render_page('diabetic_care_login/reset_password_device_success', $this->data);
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('diabetic_care_auth/reset_password_patient_device/' . $code, 'refresh');
				}
	
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("diabetic_care_auth/forgot_password", 'refresh');
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * reset password - final step for forgotten password
	 *
	 *
	 */ 

	public function reset_password_doctor_device($code = NULL,$verifycode='')
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->diabetic_care_auth_model->forgotten_password_check($code);
		
		log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$code'.print_r($code,true));
		
		log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$user'.print_r($user,true));
		
		if($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');
			
			log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$this->form_validation->run()'.print_r($this->form_validation->run(),true));

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
				
				//$this->data['csrf'] = get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('diabetic_care_login/reset_password_new', $this->data);
			}
			else
			{
					$identity = $user[0]['email'];
					
					log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$identity'.print_r($identity,true));

					$change = $this->diabetic_care_auth_model->reset_password($identity, $this->input->post('new'));
					
					log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$change'.print_r($change,true));

					if ($change)
					{
				         log_message('debug','diabetic_care_auth=====reset_password_doctor_device=====$change==true'.print_r($change,true));
						//if there were no errors
				        $this->data['message'] = "";
			            $this->_render_page('diabetic_care_login/reset_password_device_success', $this->data);
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('diabetic_care_auth/reset_password/' . $code, 'refresh');
					}
	
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("child_care_auth/forgot_password", 'refresh');
		}
	}
	
	public function update_consent_status()
	{
		$hospital_unique_id = $this->input->post('hospital_unique_id');
		$consent_status = $this->input->post('consent_status');
		//log_message('error','postttttttttttttttt==============================440'.print_r($post,true));
		$this->schoolhealth_auth_model->update_consent_status_model($hospital_unique_id, $consent_status);
	}
	
	 	
		
}

/* End of file diabetic_care_auth.php */
/* Location: ./application/admin/controllers/diabetic_care_auth.php */