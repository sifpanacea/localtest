<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_dash extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->config->load('email');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->helper('paas_helper');
		$this->load->library('mongo_db');
		$this->load->model('Admin_Model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		
	}
	
	//redirect if needed, otherwise display the user list
	function index()
	{
		$this->customers();
	}

	function dashboard()
	{
	   $this->data['message'] = "TLSTEC Admin Dashboard";
	   $this->data['customers'] = count($this->ion_auth->customers());
	   $this->_render_page('admin/admin_dash',$this->data);
	}
	
	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
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

			//render
			$this->_render_page('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	//forgot password
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
			$this->_render_page('auth/forgotpassword', $this->data);
		}
		else
		{
		    $identity = $this->ion_auth->verify_email_for_forgot_password(strtolower($this->input->post('email')));
			if(!$identity) {
				log_message('debug','IDENTITY___________________FAILS____________FORGOT_____PWD');
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/forgot_password");
            }
            
			//run the forgotten password method to email an activation code to the customer
			$forgotten = $this->ion_auth->forgotten_password(strtolower($this->input->post('email')));
            
			if($forgotten)
			{
				//if there were no errors
				$this->data['message'] = $this->ion_auth->messages();
			    $this->_render_page('forgot_password_success', $this->data);
				//$this->session->set_flashdata('message', $this->ion_auth->messages());
				//redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				log_message('debug','IDENTITY___________________FAILS____________elseeeeeeeeeeeeeeeeeeeeeeeeeeee');
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL,$verifycode='')
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
				/* $this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				); */
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('auth/reset_password_new', $this->data);
			}
			else
			{
					$identity = $user[0]['email'];

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed

						$this->data['message'] = $this->ion_auth->messages();
				        $this->_render_page('auth/login', $this->data);
						//$this->session->set_flashdata('message', $this->ion_auth->messages());
						//$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
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
	
	function pre_activate()
	{
		
		$this->data['customerslist'] = $this->Admin_Model->get_customer_details();
		log_message('debug','ccccccccccccccccccccccccccccccccccuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.print_r($this->data['customerslist'],true));
		$this->_render_page('admin/admin_pre_activate_de_cust', $this->data);
	}


	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else 
		{
			$activation = $this->ion_auth->activate($id);
		} 

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->customers();
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
		
	}
	
	//activate the user
	function first_time_activate($id)
	{
		$customer = $this->Admin_Model->get_customer($id);
		
		$fromaddress = $this->config->item('smtp_user');
		$this->email->set_newline("\r\n");
		$this->email->set_crlf( "\r\n" );
		$this->email->from($fromaddress,'TLSTEC');
		$this->email->to($customer['email']);
		$this->email->subject("Your account has been activated");
		$this->email->message("Your TLSTEC PAASS account has been activated, please click on this link to start using it:\n\n".URL);
		$email_send = $this->email->send();
		
		if($email_send)
		{
			$this->data['message'] ="Activation Email sent successfully";
		}
		else
		{
			$this->data['message'] ="Activation Email notification not sent successfully";
		}
		//$this->email->print_debugger();
		
		
			$activation = $this->ion_auth->activate($id);
	
		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', "Activation successfully");
			redirect('admin_dash/customers');
		}
		else
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', "Activation unsuccessfully");
			redirect('admin_dash/customers');
		}
	
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = get_csrf_nonce();
			$this->data['customer'] = $this->Admin_Model->get_customer($id);
			log_message('debug',' ccccccccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($this->data,true));
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

				// do we have the right userlevel?
				{
					$this->ion_auth->deactivate($id);
				}
			}

			$this->customers();
		}
	}

	//create a new user
	function create_admin()
	{
		$this->data['title'] = "Create Admin";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		} 

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{
			//check to see if we are creating the user
			//redirect them back to the admin page
			//$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->data['message'] = " Admin Created Successfully";
			$this->_render_page('admin/admin_dash', $this->data);
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

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
				'value' => $this->form_validation->set_value('company'),
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
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page('auth/create_admin', $this->data);
		}
	}

	//edit a user
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
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

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("auth", 'refresh');
			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

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
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
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

		$this->_render_page('auth/edit_user', $this->data);
	}

	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
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

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
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
				redirect("auth", 'refresh');
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
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}
	
	function customers($usage = FALSE)
	{
		if($usage == FALSE){
			$this->data['usage'] = FALSE;
			$this->data['customerslist'] = $this->Admin_Model->get_customer_details();
			$this->data['message'] = "TLSTEC Customers";
		}else{
			$this->data['usage'] = TRUE;
			$this->data['customerslist'] = $this->Admin_Model->get_customer_details();
		}
		$this->_render_page('admin/admin_dash_customers', $this->data);
	}
	
	function check_usage($id)
	{
		$this->data['customerslist'] = $this->Admin_Model->get_customer($id);
		
		$this->data['count_array'] = $this->Admin_Model->count_app_doc_api($id,$this->data['customerslist']['company_name']);
		
		log_message('debug','cccccccccccccccccccccccccccccccccc'.print_r($this->data,true));
		
		$this->_render_page('admin/admin_customers_usage', $this->data);
	}
	
	function get_users($company_name)
	{
		$this->data['users_list'] = $this->Admin_Model->get_users_list($company_name);
	
		log_message('debug','cccccccccccccccccccccccccccccccccc'.print_r($this->data,true));
	
		$this->_render_page('admin/admin_users_list', $this->data);
	}
	
	function get_user_usage($email)
	{
		$email = base64_decode($email);
		$this->data['user_details'] = $this->Admin_Model->get_user($email);
	
		$this->data['count_array'] = $this->Admin_Model->count_app_doc_user($email,$this->data['user_details']['company']);
	
		log_message('debug','cccccccccccccccccccccccccccccccccc'.print_r($this->data,true));

		$this->_render_page('admin/admin_users_usage', $this->data);
	}
	
	function get_user_billing($email)
	{
		$email = base64_decode($email);
		$this->data['user_details'] = $this->Admin_Model->get_user($email);
		
		$this->data['daily_docs_limit'] = "";
		$this->data['daily_transaction_limit'] = "";
		$this->data['cost_per_visit'] = "";
		$this->data['general_follow_up'] = "";
		$this->data['in_week_follow_up'] = "";
		$this->data['discount'] = "";
		$this->data['cost_beyond_document_limit'] = "";
		$this->data['cost_beyond_transaction_limit'] = "";
		
		
		$get_billing_plan = $this->Admin_Model->get_billing_plan($email);
		if($get_billing_plan){
			$this->data['daily_docs_limit'] = $get_billing_plan[0]['daily_docs_limit'];
			$this->data['daily_transaction_limit'] = $get_billing_plan[0]['daily_transaction_limit'];
			$this->data['cost_per_visit'] = $get_billing_plan[0]['cost_per_visit'];
			$this->data['general_follow_up'] = $get_billing_plan[0]['general_follow_up'];
			$this->data['in_week_follow_up'] = $get_billing_plan[0]['in_week_follow_up'];
			$this->data['discount'] = $get_billing_plan[0]['discount'];
			$this->data['cost_beyond_document_limit'] = $get_billing_plan[0]['cost_beyond_document_limit'];
			$this->data['cost_beyond_transaction_limit'] = $get_billing_plan[0]['cost_beyond_transaction_limit'];
		}
	
		$this->_render_page('admin/admin_user_billing_detail', $this->data);
	}
	
	function get_user_charges($email)
	{
		$email = base64_decode($email);
		$this->data['user_details'] = $this->Admin_Model->get_user($email);
		
		$get_billing_plan = $this->Admin_Model->get_billing_plan($email);
		$get_user_charges = $this->Admin_Model->get_user_charges($email);
		
		if($get_billing_plan){
			if($get_user_charges){
				
				$new_docs = 0;
				$in_week_resubmit = 0;
				$general_resubmit = 0;
				$transactions = 0;
				foreach ($get_user_charges as $per_day_charge){
					$new_docs = $new_docs + intval($per_day_charge['new_doc']);
					$in_week_resubmit = $in_week_resubmit + intval($per_day_charge['in_week_resubmit']);
					$general_resubmit = $general_resubmit + intval($per_day_charge['general_resubmit']);
					$transactions = $transactions + intval($per_day_charge['transactions']);
				}
				
				$total_new_docs = $new_docs;
				$extra_docs = $new_docs - intval($get_billing_plan[0]['daily_docs_limit']);
				if($extra_docs > 0 ){
					$extra_doc_cost = $extra_docs * intval($get_billing_plan[0]['cost_beyond_document_limit']);
				}else{
					$extra_doc_cost = 0;
					$extra_docs = 0;
				}
				
				$new_doc_cost = intval($get_billing_plan[0]['cost_per_visit']) * $new_docs;
				$in_week_follow_cost = intval($get_billing_plan[0]['in_week_follow_up']) * $in_week_resubmit;
				$general_follow_cost = intval($get_billing_plan[0]['general_follow_up']) * $general_resubmit;
				
				$total_transactions = $transactions;
				$extra_transactions = $transactions - intval($get_billing_plan[0]['daily_transaction_limit']);
				if($extra_transactions > 0 ){
					$extra_transaction_cost = $extra_transactions * intval($get_billing_plan[0]['cost_beyond_transaction_limit']);
				}else{
					$extra_transaction_cost = 0;
					$extra_transactions = 0;
				}
				
				$total_cost = $extra_doc_cost+$new_doc_cost+$in_week_follow_cost+$general_follow_cost+$extra_transaction_cost;
				
				$discount_cent = (intval($get_billing_plan[0]['discount'])/100) * $total_cost;
				
				$grand_total = $total_cost - $discount_cent;
				
				$date = date("Y-m");
				
				$this->data['expense_details'] = array(
						"email" => $email,
						"total_new_docs" => $new_docs,
						"doc_limit" => $get_billing_plan[0]['daily_docs_limit'],
						"extra_docs" => $extra_docs,
						"extra_doc_cost" => $extra_doc_cost,
						"new_doc_cost" => $new_doc_cost,
						"in_week_follow_cost" => $in_week_follow_cost,
						"general_follow_cost" => $general_follow_cost,
						"total_transactions" => $total_transactions,
						"transaction_limit" => $get_billing_plan[0]['daily_transaction_limit'],
						"extra_transaction" => $extra_transactions,
						"total_cost" => $total_cost,
						"discount" => $get_billing_plan[0]['discount'],
						"discount_cent" => $discount_cent,
						"grand_total" => $grand_total,
						"month" => $date
				);
				
				$store_monthly_bill = $this->Admin_Model->store_monthly_bill($this->data['expense_details']);
				
				$this->_render_page('admin/admin_user_charging_details', $this->data);
				
			}else{
				$this->data['message'] = "No usage made yet to generate bill.";
				$this->_render_page('admin/admin_user_charging_details', $this->data);
			}
			
		}else{
			$this->data['message'] = "Billing plan not yet implemented for this user.";
			$this->_render_page('admin/admin_user_charging_details', $this->data);
			
		}
	
		//$this->_render_page('admin/admin_user_billing_detail', $this->data);
	}
	
	function create_user_billing()
	{
		$insert_plan = $this->Admin_Model->insert_user_billing_plan($_POST);
	
		redirect('admin_dash/customers/usage', 'refresh');
	}
	
	function new_customers()
	{
		$this->data['customerslist'] = $this->Admin_Model->get_customer_details('new');
		
		$this->_render_page('admin/admin_new_customer', $this->data);
	}

	 function activation_success()
	 {
	    $this->data['message'] = "Activation successful";
	    $this->_render_page('activation_success', $this->data);
	 }

	private function create_folder_if_no_exists($path,$rights,$recursion,$not_customer)
	{
		if (@$this->mkdir_ext($path,$rights,$recursion,$not_customer))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	private function save_file($filename, $path, $data, $mode = "w")
	{
		$file = fopen(PATH.$path.$filename.".php" , $mode);
      
		if ($file)
		{
			$result = fputs ($file, $data);
		}
		fclose ($file);

		if ($result)
			return TRUE;
		else
		{
			$this->errors = lang('scaffolds_error_file')." ".APPPATH.$path.$filename.".php";
			return FALSE;
		}
	}
	
	//////////////////////////////////////////////////////////// SUPPORT ADMIN //////////////////////////////////////////////////////////////
	
	// --------------------------------------------------------------------

	/**
	* Helper : List all support admin 
	*
	* 
	* @author  Selva 
	*/

    function support_admin()
	{
	    //set the flash data error message if there is one
		$this->data['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		
		//list the users
		$this->data['support_admin'] = $this->ion_auth->support_admins()->result();
		$this->_render_page('admin/admin_dash_support_admin', $this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	* Helper : create a new support admin 
	*
	* 
	* @author  Selva 
	*/
	
	function create_support_admin()
	{
		$this->data['title'] = "Create Support Admin";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		} 

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_support_admin_first_name'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_support_admin_last_name'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_support_admin_email'), 'required|valid_email');
		$this->form_validation->set_rules('phone', $this->lang->line('create_support_admin_first_phone'), 'required|xss_clean');
		$this->form_validation->set_rules('company', $this->lang->line('create_support_admin_company_name'), 'required|xss_clean');
		$this->form_validation->set_rules('password', $this->lang->line('create_support_admin_password'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_support_admin_confirm_password'), 'required');
		$this->form_validation->set_rules('level', $this->lang->line('create_support_admin_level'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
				'level'      => $this->input->post('level'),
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register_support_admin($username, $password, $email, $additional_data))
		{
			//list the users
		    $this->data['support_admin'] = $this->ion_auth->support_admins()->result();
			$this->data['message'] = " Support Admin Created Successfully";
		    $this->_render_page('admin/admin_dash_support_admin', $this->data);
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

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
				'value' => $this->form_validation->set_value('company'),
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
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
			
			$this->_render_page('auth/create_support_admin', $this->data);
		}
	}
	
	// --------------------------------------------------------------------

	 /**
	  * Helper : Listing all support admin with edit option
	  *
	  *
	  * 
	  * @author  Selva
	  */

	   function pre_edit_support_admin()
	  {
	    if (!$this->ion_auth->logged_in())
	    {
			redirect('auth/login');
	    }
		
		$this->data['support_admin'] = $this->ion_auth->support_admins()->result();
		$this->_render_page('admin/admin_pre_edit_support_admin', $this->data);
	  }
	  
	// --------------------------------------------------------------------

	/**
	 * Helper : Edit a existing support admin
	 *
	 *
	 * 
	 * @author  Selva
	 */

	function edit_support_admin($id)
	{
		$this->data['title'] = "Edit Support Admin";

		if (!$this->ion_auth->logged_in() )
		{
			redirect('auth/login');
		}

		$user = $this->ion_auth->support_admin($id)->row();
		
        //validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|min_length[3]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
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
				'phone'      => $this->input->post('phone'),
				'level'      => implode(" ",$this->input->post('level'))
			);

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

			if ($this->form_validation->run() === TRUE && $this->ion_auth->update_support_admin($user->id, $data))
			{
			        // unset csrf userdata
				    unset_csrf_userdata();
					
                    $this->session->set_flashdata('message',$this->ion_auth->messages());
					redirect('admin_dash/support_admin');
			}
			else
			{

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

				//pass the user to the view
				$this->data['user'] = $user;

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

				$this->_render_page('admin/admin_edit_support_admin', $this->data);
				}
		}

				//display the edit user form
				$this->data['csrf'] = get_csrf_nonce();
				
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				//pass the user to the view
				$this->data['user'] = $user;
				
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
				
				$this->_render_page('admin/admin_edit_support_admin', $this->data);
	}

	  
	  // --------------------------------------------------------------------

	 /**
	  * Helper : Listing all support admin with delete option
	  *
	  *
	  * 
	  * @author  Selva
	  */

	   function pre_delete_support_admin()
	  {
	    if (!$this->ion_auth->logged_in())
	    {
			redirect('auth/login');
	    }
		
		$this->data['support_admin'] = $this->ion_auth->support_admins()->result();
		$this->_render_page('admin/admin_pre_delete_support_admin', $this->data);
	  }
	  
	  // --------------------------------------------------------------------

	/**
	 * Helper : Delete a existing support admin
	 *
	 *
	 * 
	 * @author  Selva
	 */

	function delete_support_admin($id)
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
		
		$this->ion_auth->delete_support_admin($id);
		$this->data['support_admin'] = $this->ion_auth->support_admins()->result();
		$this->data['message'] = "Support Admin Deleted";
		$this->_render_page('admin/admin_dash_support_admin',$this->data);
	
	}
	
	 // --------------------------------------------------------------------

	 /**
	  * Helper : Listing all support admins with activate/deactivate option
	  *
	  *
	  *
      * @author  Selva	  
	  */

     function pre_activate_support_admin()
	 {
	    if (!$this->ion_auth->logged_in())
	    {
		  	//redirect them to the login page
			redirect('auth/login');
	    }
	  
	    $this->data['support_admin'] = $this->ion_auth->support_admins()->result();
		$this->_render_page('admin/admin_pre_activate_de_support_admin', $this->data);
	 }
}
