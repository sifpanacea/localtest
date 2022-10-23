<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Support_admin extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->config->load('email');
		$this->load->library('form_validation');
		$this->load->library('bhashsms');
		$this->load->helper('url');
		$this->load->helper('paas');
		$this->load->helper('language');
		$this->load->library('mongo_db');
		$this->load->library('support_common_lib');
		$this->load->model('support_admin_model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->collections = $this->config->item('collections', 'ion_auth');
		$this->config->load('email');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Redirect the support admins to their respective view based on their level
	 *
	 *  
	 * @author Selva 
	 */
	 
	function index()
	{
	   if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		
		$userdetails    = $this->session->userdata("customer");
		$level          = $userdetails['level'];
		$user           = $userdetails['email'];
		$username       = str_replace("@","#",$user);
		$collection     = $username.'_ticket_inbox';
		
		$this->data['last_login'] =  $userdetails['old_last_login'];
		
        if($level=='1')		
		{
		    $this->data['message'] = 'First Level';
			$this->_render_page('support_admin/first_level/inbox', $this->data);
		}
		else if($level=='2')
		{
            $new_tickets = $this->support_common_lib->MY_new_ticketcount($collection);
			$this->data['new_tickets'] = $new_tickets;		
			$this->data['message'] = 'Second Level';
            $this->_render_page('support_admin/second_level/inbox', $this->data);
		}
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Register a device ( for a specified company with a specified plan )
	 *
	 *  
	 * @author Selva 
	 */
	
	function save_device_configuration()
	{
	   $this->form_validation->set_rules('deviceuniqueno', $this->lang->line('change_password_validation_old_password_label'), 'required');
	   $this->form_validation->set_rules('plan', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
	   $this->form_validation->set_rules('customers', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
	   
	   if (!$this->ion_auth->logged_in())
	   {
		  redirect('auth/login');
	   }
	   
	   $plan_details         = $this->support_admin_model->plans();
	   $registered_companies = $this->support_admin_model->registered_companies();

	   if ($this->form_validation->run() == true)
	   {
		    $device_exists= $this->support_admin_model->check_if_device_reg($this->input->post('deviceuniqueno'));
		    if ($device_exists) 
		    {
				//display the device configuration form
				//set the error message if there is one
				$this->data['message']      = "Device with this Unique Number already registered !";
				$this->data['plan_details'] = $plan_details;
				$this->data['customers']    = $registered_companies;
				
						
			    $this->data['deviceuniqueno'] = array(
					'name'  => 'deviceuniqueno',
					'id'    => 'deviceuniqueno',
				    'type'  => 'text',
				    'value' => $this->form_validation->set_value('deviceuniqueno')
						);				
						
				$this->_render_page('support_admin/first_level/device_configuration', $this->data);
			}
			else
			{
			    
				$device_unique_no     = $this->input->post('deviceuniqueno');
				$plan                 = $this->input->post('plan');
				$subscribed_companies = $this->input->post('customers');
				
				if($plan == "Diamond")
		        {
			       $subscription_end = date('Y-m-d',strtotime('+12 months'));
				}
				elseif($plan == "Gold")
				{
					$subscription_end = date('Y-m-d',strtotime('+6 months'));
				}
				elseif($plan == "Silver") 
				{
					$subscription_end = date('Y-m-d',strtotime('+3 month'));
				}
				elseif($plan == "Bronze") 
				{
					$subscription_end = date('Y-m-d',strtotime('+1 month'));
				}
				
						
		        $add_device = $this->support_admin_model->register_device($device_unique_no,$plan,$subscription_end,$subscribed_companies);
				if($add_device)
				{
				  $this->data['message'] = 'Device Registration Success';
				}
				else
				{
				  $this->data['message'] = 'Device Registration Failed';
				}
				
				
				//list the devices
				$dev_details = $this->support_admin_model->device_details_for_first_level_admin();
				$json = json_encode($dev_details);
				$this->data['data'] = $json;
			    $this->_render_page('support_admin/first_level/projects', $this->data);
		    }
	    }
		else
		{
			//display the device configuration form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['plan_details'] = $plan_details;
			$this->data['customers']    = $registered_companies;
				
						
			    $this->data['deviceuniqueno'] = array(
					'name'  => 'deviceuniqueno',
					'id'    => 'deviceuniqueno',
				    'type'  => 'text',
				    'value' => $this->form_validation->set_value('deviceuniqueno')
						);
						
	        $this->_render_page('support_admin/first_level/device_configuration', $this->data);
		}
	
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Redirect the support admin to their respective view based on their level
	 *
	 *  
	 * @author Selva 
	 */
	 
	function view_device_details()
	{
	   $dev_details = $this->support_admin_model->device_details_for_first_level_admin();
	   $json = json_encode($dev_details);
	   $this->data['data'] = $json;
	   $this->data['message'] = '';
       $this->_render_page('support_admin/first_level/projects', $this->data);
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Fetch data from first level support admin inbox
	 *
	 * @param string $status   Status of the message
	 *  
	 * @author Selva 
	 */
	 
	function fetch_first_level_admin_inbox($status)
	{
	    $inbox_details = array();
		
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$level          = $userdetails['level'];
		$username       = str_replace("@","#",$user);
		$collection     = $username.'_support_inbox';
		if($level=='1')
		{
	        $data = $this->support_admin_model->fetch_first_level_inbox_data($status);
		    foreach ($data as $details)
		    {	
		        array_push($inbox_details,$details);
			}
		}	
			
		$this->output->set_output(json_encode($inbox_details));
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the log details for inbox view ( first level admin )
	 *
	 * @param string $unique_id   Unique ID
	 * @param string $crash_id    Crash ID
	 *
	 * @return array
	 * 
     * @author Selva 	 
	 */

	function get_primary_inbox_entry_in_detail($unique_id,$crash_id)
	{
	     $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $level          = $userdetails['level'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		 
		 $user_details_by_unique_id = $this->support_admin_model->get_device_details_for_primary_inbox_entry_in_detail($unique_id);
		 
		 $data['user_details'] = $user_details_by_unique_id;
		 
		 if($level=='1')
		 {
		    $inbox_data = $this->support_admin_model->get_primary_inbox_entry_in_detail($unique_id,$crash_id);
			$data['inbox_data'] = $inbox_data;
			$this->output->set_output(json_encode($data));
		 }
        
    }
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox - first step
	 *
	 * @param string $device_unique_no   Device Unique ID
	 * @param string $crash_id           Crash ID
	 * 
     * @return array
     *	 
	 * @author Selva 
	 */
	 
	function pre_forward_to_second_level_admin_inbox($device_unique_no,$crash_id)
	{
	   $users = array();
	   
	   $userdetails    = $this->session->userdata("customer");
	   $user           = $userdetails['email'];
	   $level          = $userdetails['level'];
	   
	   $users_data = $this->support_admin_model->get_second_level_admin_credentials();
	   
	   foreach($users_data as $data)
	   {
	      array_push($users,$data['email']);
	   }
	   
	   $users = array_values(array_diff($users, array($user)));
	   
	   if($level=='1')
	   {
		 $data = $this->support_admin_model->get_primary_inbox_entry_in_detail($device_unique_no,$crash_id);
	   }
	   
	   $forward_data['users']      = $users;
	   $forward_data['inbox_data'] = $data;
	   
	   $this->output->set_output(json_encode($forward_data));
	   
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox - second step
	 *
	 *  
	 * @author Selva 
	 */
	 
	function forward_to_second_level_admin_inbox()
	{
	   $upload_info = array();
	   
	   // POST DATA
	   $crashed_app          = $this->input->post('app');
	   $crashed_time         = $this->input->post('time');
	   $log_details          = json_decode(base64_decode($this->input->post('level'),TRUE),TRUE);
	   $crash_id             = $this->input->post('cid');
	   $device_unique_number = $this->input->post('dun');
	   $email_message        = $this->input->post('level1_message');
	   $firmware_details     = json_decode($this->input->post('firmware'),TRUE);
	   $users                = json_decode($this->input->post('users'),TRUE);

	   // ATTACHMENT
	   if(isset($_FILES))
	   {
	     if($_FILES['file']['name'] !='')
		 {
			 $config['upload_path']   = LEVEL2;
			 $config['allowed_types'] = '*';
			 $config['max_size']      = '4096';
			 $config['encrypt_name']  = TRUE;
		 
			 $this->load->library('upload',$config);
			  
			  foreach($_FILES as $index => $value)
			  {
				 if ( ! $this->upload->do_upload($index))
				 {
					 echo "file upload failed";
					 return FALSE;
				 }
				 else
				 {
					array_push($upload_info,$this->upload->data());
				 }
				 
			  }
		  
			  $uploaded_file = array(
			'file_name' => $upload_info[0]['file_name'],
			'client_name' => $upload_info[0]['client_name'],
			'file_path' => $upload_info[0]['file_relative_path']);
			}
		}
		
	   
		   $data = array(
		   'crash_id'             	  => $crash_id,
		   'device_unique_number' 	  => $device_unique_number,
		   'crashed_app'          	  => $crashed_app,
		   'log_details'              => $log_details,
		   'crashed_time'         	  => $crashed_time,
		   'email_message'            => $email_message,
		   'device_firmware_details'  => $firmware_details,
		   'status'                   => 'new',
		   'log_received_time'        => date('Y-m-d H:i:s')
		   );
	   
	   if(isset($_FILES))
	   {
	       if($_FILES['file']['name'] !='')
		   {
	         $data['attachments'] = $uploaded_file;
		   }
	   }
	   
	   // Logged in admin details
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$level          = $userdetails['level'];
		
		// Forwarded time
		$forwarded_time = date('Y-m-d H:i:s');
		
		$data['forwarded_by'] = $user;
		$data['forwarded_on']  = $forwarded_time;
	   
	    foreach($users as $inneruser)
	    {
	     $levadmin       = str_replace("@","#",$inneruser);
	     $usercollection = $levadmin.'_support_inbox';
	     $result = $this->support_admin_model->forward_data_to_second_level($usercollection,$data);
	    }
	   
	   if($result)
	   {
	      if($level=='1')
		  {
	         $this->support_admin_model->post_forward_process($user,$device_unique_number,$crash_id,$forwarded_time,$users);
		  }
		  $this->data['message'] = 'Forward Success !';
	   }
	   else
	   {
	      $this->data['message'] = 'Forward Failed !';
	   }	   
	   
	   $this->data['message']    = 'Forward Success !';
	   $this->data['last_login'] = $userdetails['old_last_login'];
	   $this->_render_page('support_admin/first_level/inbox', $this->data);
	   
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Mark auto ticket status as resolved with resolution - Level 1 admin
	 *  
	 * @author Selva 
	 */

	  public function mark_as_auto_ticket_resolved_level_1()
	  {
	     // Logged in support admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 
		 // POST DATA
		 $email          = $this->input->post('owner');
	     $service_req_no = $this->input->post('service_number');
		 $resolution     = $this->input->post('resolution');
		 
		 $resolved_time  = date('Y-m-d H:i:s');
		 
		 $result = $this->support_admin_model->mark_as_auto_ticket_resolved_level_1_model($user,$service_req_no,$resolution,$resolved_time);
		 
		 if($result)
		 {
			// EMAIL TO TICKET OWNER
		    $this->support_common_lib->send_email_notification_to_user($email,$service_req_no,'reply');
		    
			$return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	
	// ------------------------------------------------------------------------

	  /**
	  * Helper: Delete entry ( mark status as trash ) - Level 1 admin
	  *  
	  * @author Selva 
	  */

	   public function mark_as_trash_level_1()
	  {
		
	     $del_data = $this->input->post('del_data');
		 foreach($del_data as $data)
		 {
		    $device_unique_number = $data['device_id'];
			$crash_id             = $data['log_id'];
			
			$result = $this->support_admin_model->mark_as_trash_level_1_model($device_unique_number,$crash_id);
		 }
		 
		 if($result)
		 {
		    $return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }	
	  
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Delete entry ( mark status as trash ) - Level 1 admin
	  *  
	  * @author Selva 
	  */

	   public function notification_panel()
	   {
	      $this->data['message'] = '';
		  $dev_details = $this->support_admin_model->device_details_for_notification();
		  $this->data['devices'] = $dev_details;
	      $this->_render_page('support_admin/first_level/support_admin_dash_create_notification', $this->data);
	   }
	   
	   // ---------------------------------------------------------------------------

	  /**
	   * Helper : Redirect the support admin to their respective view based on their level
	   *
	   *  
	   * @author Selva 
	   */
	 
		function fetch_device_details()
		{
		   $dev_details = $this->support_admin_model->device_details_for_notification();
		   $this->output->set_output(json_encode($dev_details));
		}
	   
	 // ------------------------------------------------------------------------

	  /**
	  * Helper  Send notification to end users - Level 1 admin
	  *  
	  * @author Selva 
	  */

	   public function send_notification()
	   {
	      if(isset($_POST))
		  {
		      // Variable Declaration
			  $email_list       = array();
			  
			  $recipient_list  = $this->input->post('multiselect',TRUE);
			  $message         = $this->input->post('message',TRUE);
			  
			  // Logged in support admin details
			  $loggeduser = $this->session->userdata('customer');
			  $sender     = $loggeduser['email'];
			  
			  // EMAIL NOTIFICATION
			  if(isset($_POST['email']))
			  { 
                    foreach($recipient_list as $email)
                    {
					  array_push($email_list,$email);
				    }
					
				    $fromaddress = $this->config->item('smtp_user');
					$this->email->set_newline("\r\n");
					$this->email->from($fromaddress,'TLSTEC');
					$this->email->to($email_list);
					$this->email->subject("Support Team");
					$this->email->message($message);
					$this->email->send();
		      }
			  
			  // SMS NOTIFICATION
			  if(isset($_POST['sms']))
			  {
			      foreach($recipient_list as $email)
                  {
					  $res = $this->support_admin_model->get_user_details_for_notification($email);
					  $this->bhashsms->send_sms($res['phone'],$message);
				  }
				  
		      }
			  
			  // PUSH NOTIFICATION
			  if(isset($_POST['push_message']))
			  {
			     foreach($recipient_list as $email)
                 {
					 $details    = $this->support_admin_model->get_user_details_for_notification($email);
					 $company    = $details['company'];
					 $receiver   = str_replace("@","#",$email);
					 $receiver   = $receiver."_push_notifications";
					 $message_id = get_unique_id();
					 
					 $this->support_admin_model->send_push_message($receiver,$company,$message_id,$message);
				 }
				 
				 $this->support_admin_model->push_message_save_history($message_id,$message,$sender,$recipient_list);
             }
		  
	      $this->data['message'] = 'Notification Sent !';
		  $dev_details = $this->support_admin_model->device_details_for_notification();
		  $this->data['devices'] = $dev_details;
	      $this->_render_page('support_admin/first_level/support_admin_dash_create_notification', $this->data);
	   }
	   }
	   
	// ------------------------------------------------------------------------

	  /**
	  * Helper: Username of logged in admin
	  *  
	  * @author Selva 
	  */

	   public function first_level_admin_username()
	  {
	    $user = $this->session->userdata("customer");
		$name = $user['username'];
		$this->output->set_output(json_encode($name));
	  }	 
	
	
	// ---------------------------------------------------------------------------

	/**
	 * Change Password
	 *
	 *  
	 * @author Selva 
	 */
	 
	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

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

			//render
			$this->_render_page('support_admin/first_level/change_password', $this->data);
		}
		else
		{
		    $userdetails    = $this->session->userdata("customer");
			$identity_field = $this->config->item('identity', 'ion_auth'); 
			$identity       = $userdetails[$identity_field];

			$change = $this->ion_auth->first_level_admin_change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('auth/login');
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('support_admin/change_password', 'refresh');
			}
		}
	}
	
	 // ------------------------------------------------------------------------

	  /**
	  * Helper: Delete entry in collection - Level 1 admin
	  *  
	  * @author Selva 
	  */

	   public function delete_entry_level_1()
	  {
	     // Logged in admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		
	     $del_data = $this->input->post('del_data');
		 foreach($del_data as $data)
		 {
		    $device_unique_number = $data['device_id'];
			$crash_id             = $data['log_id'];
			
			$result = $this->support_admin_model->delete_log_entry_level_1_model($collection,$device_unique_number,$crash_id);
		 }
		 
		 if($result)
		 {
		    $return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	
    //=====================================================================TICKET MANAGEMENT======================================================	
	  
	// --------------------------------------------------------------------

	/**
	 * Helper : Create a new ticket
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function create_ticket()
	{
		$this->data['title'] = "Create a ticket";
		 
	    //validate form input
		$this->form_validation->set_rules('device_unique_number', $this->lang->line('create_ticket_device_unique_num'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_ticket_user_email'), 'required|valid_email');
		$this->form_validation->set_rules('crashed_app', $this->lang->line('create_ticket_crashed_app'), 'required|xss_clean');
		$this->form_validation->set_rules('ticket_description', $this->lang->line('create_ticket_description'), 'required|xss_clean');
		
		if ($this->form_validation->run() == true)
		{
		    $device_unique_number = $this->input->post('device_unique_number');
			$email                = strtolower($this->input->post('email'));
			$crashedapp           = $this->input->post('crashed_app');
			$ticket_description   = $this->input->post('ticket_description');
			
			$email_exists = $this->support_admin_model->is_registered_user($email);
			
			// Variable Declarations
			$config      = array();
			$upload_info = array();
			$file_data   = array();
		
                        // File upload Configurations
			$config['upload_path']   = TICKETBOX;
			$config['allowed_types'] = '*';
			$config['max_size']      = '10240';
			$config['encrypt_name']  = TRUE;
	
			//create upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(TICKETBOX,0777,TRUE);
			}
			
			if (!$email_exists) 
			{
				//display the create user form
				//set the flash data error message if there is one
				$this->data['message'] = "Email ID not registered!";
						
				$this->data['device_unique_number'] = array(
								'name'  => 'device_unique_number',
								'id'    => 'device_unique_number',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('device_unique_number'),
				);
				$this->data['email'] = array(
								'name'  => 'email',
								'id'    => 'email',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('email'),
				);
				$this->data['crashed_app'] = array(
								'name'  => 'crashed_app',
								'id'    => 'crashed_app',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('crashed_app'),
				);
				$this->data['ticket_description'] = array(
								'name'  => 'ticket_description',
								'id'    => 'ticket_description',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('ticket_description'),
				);
						
				$this->_render_page('support_admin/first_level/create_ticket', $this->data);
			}
			else
			{
				// Unique Service Request Number
				$service_request_number = "SUPPORT-".get_unique_id();
				
				// File data
				if(isset($_FILES))
				{
					$this->load->library('upload',$config);
					  
					foreach($_FILES as $index => $value)
					{
						if($value['name']!='')
						{
							$this->upload->initialize($config,$index);
							if ( ! $this->upload->do_upload($index))
							{
								 echo "file upload failed";
								 return FALSE;
							}
							else
							{
								array_push($upload_info,$this->upload->data());
								
								
								
								$file_data = array(
									"file_client_name"    =>$upload_info[0]['client_name'],
									"file_encrypted_name" =>$upload_info[0]['file_name'],
									"file_path"           =>$upload_info[0]['file_relative_path']
									  );
							}
						}
					  
					}
					
				}
				
			
				$create_ticket = $this->support_admin_model->register_ticket($device_unique_number,$email,$crashedapp,$service_request_number,$ticket_description,$file_data);
						
				//check to see if we are creating the user
				if($create_ticket)
				{
				
				   // EMAIL TO TICKET OWNER
				    $this->support_common_lib->send_email_notification_to_user($email,$service_request_number,'ack');
					
					redirect('support_admin/manage_ticket_first_level');
				    
					/* $config   = array();
					$users    = array();
					$per_page = 5;
		 
					$total_rows = $this->support_admin_model->ticketcount();
		
					$config = $this->support_common_lib->set_paginate_options($total_rows,$per_page);

					//Initialize the pagination class
					$this->pagination->initialize($config);

					//control of number page
					$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

					//find all the categories with paginate and save it in array to past to the view
					$ticket_details = $this->support_admin_model->tickets($config['per_page'], $page);
					
					//create paginate´s links
					$this->data['links'] = $this->pagination->create_links();

					//number page variable
					$this->data['page'] = $page;
					
				    $this->data['message'] = 'Ticket Registered Successfully';
					
					// EMAIL TO TICKET OWNER
				    $this->support_common_lib->send_email_notification_to_user($email,$service_request_number,'ack');
					
					// second level admin details
					$users_data = $this->support_admin_model->get_second_level_admin_credentials();
	   
					foreach($users_data as $data)
					{
						array_push($users,$data['email']);
					}
					
					//list the tickets
					$this->data['tickets']             = $ticket_details;
					$this->data['second_level_admins'] = $users;
					$this->_render_page('support_admin/first_level/manage_ticket', $this->data); */
				}
				else
				{
				
				}
	        }
		}
		else
		{
				//display the create user form
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				
				$this->data['device_unique_number'] = array(
								'name'  => 'device_unique_number',
								'id'    => 'device_unique_number',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('device_unique_number'),
				);
				$this->data['email'] = array(
								'name'  => 'email',
								'id'    => 'email',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('email'),
				);
				$this->data['crashed_app'] = array(
								'name'  => 'crashed_app',
								'id'    => 'crashed_app',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('crashed_app'),
				);
				$this->data['ticket_description'] = array(
								'name'  => 'ticket_description',
								'id'    => 'ticket_description',
								'type'  => 'text',
								'value' => $this->form_validation->set_value('ticket_description'),
				);
	
				$this->_render_page('support_admin/first_level/create_ticket', $this->data);
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Manage ticket
	 *
	 * @author  Selva
	 *
	 * 
	 */
	 
	function manage_ticket_first_level()
	{
	    $config   = array();
		$users    = array();
	    $per_page = 5;
		 
	    $total_rows = $this->support_admin_model->ticketcount();
		
        $config = $this->support_common_lib->set_paginate_options($total_rows,$per_page);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$ticket_details = $this->support_admin_model->tickets($config['per_page'], $page);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
	   
	    // second level admin details
	    $users_data = $this->support_admin_model->get_second_level_admin_credentials();
	   
	    foreach($users_data as $data)
	    {
	      array_push($users,$data['email']);
	    }
		
	    //list the tickets
	    $this->data['tickets']             = $ticket_details;
		$this->data['second_level_admins'] = $users;
		$this->data['total_tickets']       = $total_rows;
		$this->data['message']             = '';
	    $this->_render_page('support_admin/first_level/manage_ticket', $this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Assign a ticket to next level support admin - Level 1 admin
	 *
	 * @author  Selva
	 */
	 
	function assign_ticket()
	{
	  $device_unique_no = $this->input->post('device_no',true);
	  $crashed_app      = $this->input->post('crashed',true);
	  $registered_on    = $this->input->post('registered',true);
	  $ticket_owner     = $this->input->post('owner',true);
	  $description      = $this->input->post('description',true);
	  $service_req_no   = $this->input->post('service_number',true);
	  $user             = $this->input->post('user',true);
	  $file_c           = $this->input->post('file_c',true);
	  $file_e           = $this->input->post('file_e',true);
	  $file_p           = $this->input->post('file_p',true);
	  
	  if(!empty($file_c) && !is_null($file_c))
	  {
	     $file = array(
		 'file_client_name'    => $file_c,
		 'file_encrypted_name' => $file_e,
		 'file_path'           => $file_p
		              );
	  }
	  
	  // To be assigned user details
	  $collectionname = str_replace('@','#',$user);
	  $collection     = $collectionname.'_ticket_inbox';
	  
	  // Logged in support admin details
	  $loggedinsupportadmin = $this->session->userdata("customer");
	  $supportadmin         = $loggedinsupportadmin['email'];
	  
	  //Ticket assigned time
	  $ticket_assigned_on = date('Y-m-d H:i:s');
	  
	  $id = $this->support_admin_model->assign_ticket_to_second_level_admin($device_unique_no,$crashed_app,$registered_on,$ticket_assigned_on,$ticket_owner,$description,$service_req_no,$supportadmin,$collection,$file);
	  
	  if($id)
	  {
	     $this->support_admin_model->post_process_assign_ticket($service_req_no,$ticket_assigned_on,$user,$supportadmin);
	  }
	
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Mark ticket status as resolved with resolution - Level 1 admin
	 *  
	 * @author Selva 
	 */

	  public function mark_as_ticket_resolved_level_1()
	  {
	     // Logged in support admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 
		 // POST DATA
		 $email          = $this->input->post('owner');
	     $service_req_no = $this->input->post('service_number');
		 $resolution     = $this->input->post('resolution');
		 
		 $resolved_time  = date('Y-m-d H:i:s');
		 
		 $result = $this->support_admin_model->mark_as_ticket_resolved_level_1_model($user,$service_req_no,$resolution,$resolved_time);
		 
		 if($result)
		 {
			// EMAIL TO TICKET OWNER
		    $this->support_common_lib->send_email_notification_to_user($email,$service_req_no,'reply');
		    
			$return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	
	//========================================================= LEVEL 2 ADMIN ======================================================================//
	
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the log details for inbox view ( second level admin )
	 *
	 * @param string $unique_id   Unique ID
	 * @param string $crash_id    Crash ID
	 *
	 * @return array
	 * 
     * @author Selva 	 
	 */

	function get_secondary_inbox_entry_in_detail($unique_id,$crash_id)
	{
	     $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $level          = $userdetails['level'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		 
		 $user_details_by_unique_id = $this->support_admin_model->get_device_details_for_primary_inbox_entry_in_detail($unique_id);
		 
		 $data['user_details'] = $user_details_by_unique_id;
		 
		 if($level=='2')
		 {
		    $inbox_data = $this->support_admin_model->get_secondary_inbox_entry_in_detail($collection,$unique_id,$crash_id);
			$data['inbox_data'] = $inbox_data;
			$this->output->set_output(json_encode($data));
		 }
        
    }
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Fetch data from second level support admin inbox
	 *
	 * @param string $status   Status of the message
	 *  
	 * @author Selva 
	 */
	 
	function fetch_second_level_admin_inbox($status)
	{
	    $inbox_details = array();
		
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$level          = $userdetails['level'];
		$username       = str_replace("@","#",$user);
		$collection     = $username.'_support_inbox';
		if($level=='2')
		{
	        $data = $this->support_admin_model->fetch_second_level_inbox_data($collection,$status);
		    foreach ($data as $details)
		    {	
		        array_push($inbox_details,$details);
			}
		}	
			
		$this->output->set_output(json_encode($inbox_details));
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox - first step - Dropped
	 *
	 * @param string $device_unique_no   Device Unique ID
	 * @param string $crash_id           Crash ID
	 * 
     * @return array
     *	 
	 * @author Selva 
	 */
	 
	function pre_reply_to_first_level_admin_inbox($device_unique_no,$crash_id)
	{
	   $userdetails    = $this->session->userdata("customer");
	   $user           = $userdetails['email'];
	   $level          = $userdetails['level'];
	   $username       = str_replace("@","#",$user);
	   $collection     = $username.'_support_inbox';
	   if($level=='2')
	   {
		 $data = $this->support_admin_model->get_secondary_inbox_entry_in_detail($collection,$device_unique_no,$crash_id);
	   }
	   
	   $reply_data['inbox_data'] = $data;
	   $reply_data['user']       = $userdetails['username'];
	   
	   $this->output->set_output(json_encode($reply_data));
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox - second step
	 *
	 *  
	 * @author Selva 
	 */
	 
	function reply_to_first_level_admin_inbox()
	{
	   $upload_info = array();
	   
	   // POST DATA
	   $crash_id             = $this->input->post('cid');
	   $device_unique_number = $this->input->post('dun');
	   $email_message        = $this->input->post('level2_message');
	   $users                = json_decode($this->input->post('users'),TRUE);

	   // ATTACHMENT
	   if(isset($_FILES))
	   {
	     if($_FILES['file']['name'] !='')
		 {
			 $config['upload_path']   = LEVEL1;
			 $config['allowed_types'] = '*';
			 $config['max_size']      = '4096';
			 $config['encrypt_name']  = TRUE;
		 
			 $this->load->library('upload',$config);
			  
			  foreach($_FILES as $index => $value)
			  {
				 if ( ! $this->upload->do_upload($index))
				 {
					 echo "file upload failed";
					 return FALSE;
				 }
				 else
				 {
					array_push($upload_info,$this->upload->data());
				 }
				 
			  }
		  
			  $uploaded_file = array(
			'file_name' => $upload_info[0]['file_name'],
			'client_name' => $upload_info[0]['client_name'],
			'file_path' => $upload_info[0]['file_relative_path']);
			}
		}
		
	   
		   $data = array(
		   'email_message'            => $email_message,
		   'status'                   => 'resolved',
		   'log_received_time'        => date('Y-m-d H:i:s')
		   );
	   
	   if(isset($_FILES))
	   {
	       if($_FILES['file']['name'] !='')
		   {
	         $data['attachments'] = $uploaded_file;
		   }
	   }
	   
	   // Logged in admin details
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$level          = $userdetails['level'];
		$username       = str_replace("@","#",$user);
		$collection     = $username.'_support_inbox';
		
		$data['replied_by'] = $user;
	   
	    foreach($users as $inneruser)
	    {
	     $levadmin       = str_replace("@","#",$inneruser);
	     $usercollection = $levadmin.'_support_inbox';
	     $result = $this->support_admin_model->reply_data_to_first_level($usercollection,$data);
	    }
		
	   if($result)
	   {
	      if($level=='2')
		  {
	         $this->support_admin_model->post_reply_process($collection,$device_unique_number,$crash_id);
		  }
		  $this->data['message'] = 'Reply Success !';
	   }
	   else
	   {
	      $this->data['message'] = 'Reply Failed !';
	   }
	   
       $this->data['last_login'] = $userdetails['old_last_login'];   
	   $this->_render_page('support_admin/second_level/inbox', $this->data);
	   
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: secure file download
	 *
	 *@author Selva 
	 */

	public function download_attachment($relpath)
	{
		$path = str_replace('=','/',$relpath);
		$this->external_file_download($path);
    }
	
	// ---------------------------------------------------------------------------

	/**
	 * Change Password
	 *
	 *  
	 * @author Selva 
	 */
	 
	function change_pwd()
	{
	    $userdetails    = $this->session->userdata("customer");
	    $user           = $userdetails['email'];
	    $username       = str_replace("@","#",$user);
	    $collection     = $username.'_ticket_inbox';
	    $new_tickets = $this->support_common_lib->MY_new_ticketcount($collection);
		
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

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
			
			$this->data['new_tickets'] = $new_tickets;

			//render
			$this->_render_page('support_admin/second_level/change_password', $this->data);
		}
		else
		{
			$userdetails    = $this->session->userdata("customer");
			$identity_field = $this->config->item('identity', 'ion_auth'); 
			$identity       = $userdetails[$identity_field];

			$change = $this->ion_auth->second_level_admin_change_password($identity, $this->input->post('old'), $this->input->post('new_pwd'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('auth/login');
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('support_admin/change_pwd', 'refresh');
			}
		}
	}
	
	// ------------------------------------------------------------------------

	  /**
	  * Helper: Username of logged in admin
	  *  
	  * @author Selva 
	  */

	   public function second_level_admin_username()
	  {
	    $user = $this->session->userdata("customer");
		$name = $user['username'];
		$this->output->set_output(json_encode($name));
	  }

    // ---------------------------------------------------------------------------

	/**
	 * Helper : Redirect the support admin to their respective view based on their level
	 *
	 *  
	 * @author Selva 
	 */
	 
	function device_details()
	{
	   $userdetails    = $this->session->userdata("customer");
	   $user           = $userdetails['email'];
	   $username       = str_replace("@","#",$user);
	   $collection     = $username.'_ticket_inbox';
	   $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	   
	   $dev_details = $this->support_admin_model->device_details_for_first_level_admin();
	   $json = json_encode($dev_details);
	   $this->data['data']    = $json;
	   $this->data['message'] = '';
	   $this->data['new_tickets'] = $new_tickets;
       $this->_render_page('support_admin/second_level/projects', $this->data);
	}

     // ------------------------------------------------------------------------

	  /**
	  * Helper: Mark status as trash in collection - Level 2 admin
	  *  
	  * @author Selva 
	  */

	   public function mark_as_trash_level_2()
	  {
	     // Logged in admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		
	     $del_data = $this->input->post('del_data');
		 foreach($del_data as $data)
		 {
		    $device_unique_number = $data['device_id'];
			$crash_id             = $data['log_id'];
			
			$result = $this->support_admin_model->mark_as_trash_level_2_model($collection,$device_unique_number,$crash_id);
		 }
		 
		 if($result)
		 {
		    $return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }	
	  
	 // ------------------------------------------------------------------------

	  /**
	  * Helper: Delete entry in collection - Level 2 admin
	  *  
	  * @author Selva 
	  */

	   public function delete_entry_level_2()
	  {
	     // Logged in admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		
	     $del_data = $this->input->post('del_data');
		 foreach($del_data as $data)
		 {
		    $device_unique_number = $data['device_id'];
			$crash_id             = $data['log_id'];
			
			$result = $this->support_admin_model->delete_log_entry_level_2_model($collection,$device_unique_number,$crash_id);
		 }
		 
		 if($result)
		 {
		    $return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	  
	  // ---------------------------------------------------------------------------------

	  /**
	  * Helper: View log details in detail - log submitted by debug app ( Level 2 admin )
	  *  
	  * @author Selva 
	  */
	  
	  public function view_log_details()
	  {
	     $config   = array();
		 $per_page = 5;
		 
		 $userdetails    = $this->session->userdata("customer");
	     $user           = $userdetails['email'];
	     $username       = str_replace("@","#",$user);
	     $collection     = $username.'_ticket_inbox';
	     $new_tickets = $this->support_common_lib->MY_new_ticketcount($collection);
		 
	     $total_rows = $this->support_admin_model->logcount();
		

         //---pagination---//
	   	 $config['base_url']         = site_url() .'/'.$this->uri->segment(1).'/'.$this->uri->segment(2);
		 $config['use_page_numbers'] = 'TRUE';
		 $config['per_page']         = $per_page;
		 $config['total_rows']       = $total_rows;
		 $config['uri_segment']      = 3;
		 $config['full_tag_open']    = '<div class="text-center"><ul class="pagination pagination-xs no-margin">';
		 $config['full_tag_close']   = '</ul></div><!--pagination-->';
		 $config['first_link']       = '&laquo; First';
		 $config['first_tag_open']   = '<li class="prev page">';
		 $config['first_tag_close']  = '</li>';
		 $config['last_link']        = 'Last &raquo;';
		 $config['last_tag_open']    = '<li class="next page">';
		 $config['last_tag_close']   = '</li>';
		 $config['next_link']        = 'Next &rarr;';
		 $config['next_tag_open']    = '<li class="next page">';
		 $config['next_tag_close']   = '</li>';
		 $config['prev_link']        = '&larr; Previous';
		 $config['prev_tag_open']    = '<li class="prev page">';
		 $config['prev_tag_close']   = '</li>';
		 $config['cur_tag_open']     = '<li class="active"><a href="">';
		 $config['cur_tag_close']    = '</a></li>';
		 $config['num_tag_open']     = '<li class="page">';
		 $config['num_tag_close']    = '</li>';
		
		 $choice = $config["total_rows"] / $config["per_page"];

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$log_details = $this->support_admin_model->log_details_in_detail($config['per_page'], $page);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
	    $this->data['data']        = $log_details;
		$this->data['logcount']    = $total_rows;
		$this->data['new_tickets'] = $new_tickets;
        $this->_render_page('support_admin/second_level/device_logs_in_detail', $this->data);
	  }
	  
	  // ---------------------------------------------------------------------------------

	  /**
	  * Helper: View log details in detail - log submitted by debug app ( Level 2 admin )
	  *  
	  * @author Selva 
	  */
	  
	  public function delete_log_in_detail($log_id)
	  {
		$this->deleteAll(DETAILEDLOGS.$log_id);	
		
		//delete the item
		if ($this->support_admin_model->delete_log($log_id) == TRUE)
		{
			$this->data['message'] = lang('app_delete_successful');
		}
		else
		{
			$this->data['message'] = lang('app_delete_unsuccessful');
		}
	      redirect('support_admin/view_log_details');
	  }
	  
	  // --------------------------------------------------------------------

	   /**
	  * Helper : Manage the tickets assigned for second level admin
	  * 
	  * @author  Selva
	  */

	  
	  function manage_ticket_second_level()
	  {
	    $config   = array();
		$users    = array();
	    $per_page = 5;
		
        // Logged in admin details
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$username       = str_replace("@","#",$user);
		$collection     = $username.'_ticket_inbox';
		
		$new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
		 
	    $total_rows = $this->support_admin_model->MY_ticketcount($collection);
		
        $config = $this->support_common_lib->set_paginate_options($total_rows,$per_page);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$ticket_details = $this->support_admin_model->MY_tickets($config['per_page'],$page,$collection);
		
		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
		// second level admin details
	    $users_data = $this->support_admin_model->get_second_level_admin_credentials();
	   
	    foreach($users_data as $data)
	    {
	      array_push($users,$data['email']);
	    }
		
		$users = array_values(array_diff($users,array($user)));
		
	    //list the tickets
	    $this->data['tickets']             = $ticket_details;
		$this->data['message']             = '';
		$this->data['second_level_admins'] = $users;
		$this->data['total_tickets']       = $total_rows;
		$this->data['new_tickets']         = $new_tickets;
	    $this->_render_page('support_admin/second_level/manage_ticket', $this->data);
	  
	  
	  }
	  
	  // --------------------------------------------------------------------

	/**
	 * Helper : Re-assign a ticket to next level support admin
	 *
	 * @author  Selva
	 *
	 * 
	 */
	 
	function re_assign_ticket()
	{
	  $device_unique_no = $this->input->post('device_no',true);
	  $crashed_app      = $this->input->post('crashed',true);
	  $registered_on    = $this->input->post('registered',true);
	  $ticket_owner     = $this->input->post('owner',true);
	  $description      = $this->input->post('description',true);
	  $service_req_no   = $this->input->post('service_number',true);
	  $new_user         = $this->input->post('new_user',true);
	  $file_c           = $this->input->post('file_c',true);
	  $file_e           = $this->input->post('file_e',true);
	  $file_p           = $this->input->post('file_p',true);
	  
	  if(!empty($file_c) && !is_null($file_c))
	  {
	     $file = array(
		 'file_client_name'    => $file_c,
		 'file_encrypted_name' => $file_e,
		 'file_path'           => $file_p
		              );
	  }
	  
	  // Logged in support admin details
	  $loggedinsupportadmin = $this->session->userdata("customer");
	  $supportadmin         = $loggedinsupportadmin['email'];
	  
	  // Already assigned user details
	  $ocollectionname = str_replace('@','#',$supportadmin);
	  $oldcollection   = $ocollectionname.'_ticket_inbox';
	  
	  // To be assigned user details
	  $ncollectionname = str_replace('@','#',$new_user);
	  $newcollection   = $ncollectionname.'_ticket_inbox';
	  
	  //Ticket re-assigned time
	  $ticket_re_assigned_on = date('Y-m-d H:i:s');
	  
	  $id = $this->support_admin_model->re_assign_ticket_to_second_level_admin($device_unique_no,$crashed_app,$registered_on,$ticket_re_assigned_on,$ticket_owner,$description,$service_req_no,$supportadmin,$newcollection,$file);
	  
	  if($id)
	  {
	     $this->support_admin_model->post_process_re_assign_ticket($service_req_no,$ticket_re_assigned_on,$new_user,$oldcollection);
		 $return = TRUE;
		 $this->output->set_output($return);
	  }
	  else
	  {
		 $return = FALSE;
		 $this->output->set_output($return);
	  }
	
	}
	  
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Mark ticket status as resolved with resolution - Level 2 admin
	  *  
	  * @author Selva 
	  */

	   public function mark_as_ticket_resolved_level_2()
	  {
	     // Logged in support admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_ticket_inbox';
		
		 // POST DATA
		 $email          = $this->input->post('owner');
	     $service_req_no = $this->input->post('service_number');
		 $resolution     = $this->input->post('resolution');
		 
		 $resolved_time  = date('Y-m-d H:i:s');
		 
		 $result = $this->support_admin_model->mark_as_ticket_resolved_level_2_model($collection,$service_req_no,$resolution,$resolved_time);
		 
		 if($result)
		 {
		    $this->support_admin_model->post_process_resolve_ticket($service_req_no,$resolution,$resolved_time,$user);
			
			// EMAIL TO TICKET OWNER
		    $this->support_common_lib->send_email_notification_to_user($email,$service_req_no,'reply');
		    
			$return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	  
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Mark Auto ticket status as resolved with resolution - Level 2 admin
	  *  
	  * @author Selva 
	  */

	   public function mark_as_auto_ticket_resolved_level_2()
	  {
	     // Logged in support admin details
		 $userdetails    = $this->session->userdata("customer");
		 $user           = $userdetails['email'];
		 $username       = str_replace("@","#",$user);
		 $collection     = $username.'_support_inbox';
		
		 // POST DATA
		 $email          = $this->input->post('owner');
	     $service_req_no = $this->input->post('service_number');
		 $resolution     = $this->input->post('resolution');
		 
		 $resolved_time  = date('Y-m-d H:i:s');
		 
		 $result = $this->support_admin_model->mark_as_auto_ticket_resolved_level_2_model($user,$collection,$service_req_no,$resolution,$resolved_time);
		 
		 if($result)
		 {
		    $this->support_admin_model->post_process_resolve_auto_ticket($service_req_no,$resolution,$resolved_time,$user);
			
			// EMAIL TO TICKET OWNER
		    $this->support_common_lib->send_email_notification_to_user($email,$service_req_no,'reply');
		    
			$return = TRUE;
			$this->output->set_output($return);
		 }
		 else
		 {
		    $return = FALSE;
			$this->output->set_output($return);
		 }
	  }
	  
	  // ---------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox - first step
	 *
	 * @param string $device_unique_no   Device Unique ID
	 * @param string $crash_id           Crash ID
	 * 
     * @return array
     *	 
	 * @author Selva 
	 */
	 
	function pre_forward_from_second_level_admin_inbox($device_unique_no,$crash_id)
	{
	   $users = array();
	   
	   $userdetails    = $this->session->userdata("customer");
	   $user           = $userdetails['email'];
	   $level          = $userdetails['level'];
	   $username       = str_replace("@","#",$user);
	   $collection     = $username.'_support_inbox';
	   
	   $users_data = $this->support_admin_model->get_second_level_admin_credentials();
	   
	   foreach($users_data as $data)
	   {
	      array_push($users,$data['email']);
	   }
	   
	   $users = array_values(array_diff($users,array($user)));
	   
	   if($level=='2')
	   {
		 $data = $this->support_admin_model->get_inbox_entry_in_detail_from_second_level($collection,$device_unique_no,$crash_id);
	   }
	   
	   $forward_data['users']      = $users;
	   $forward_data['inbox_data'] = $data;
	   
	   $this->output->set_output(json_encode($forward_data));
	   
	}
	
	// ---------------------------------------------------------------------------------------------------

	/**
	 * Helper : Forward data to second level support admin inbox from any other second level admin itself
	 *
	 *  
	 * @author Selva 
	 */
	 
	function forward_from_second_level_admin()
	{
	   $upload_info = array();
	   
	   // POST DATA
	   $crashed_app          = $this->input->post('app');
	   $crashed_time         = $this->input->post('time');
	   $log_details          = json_decode(base64_decode($this->input->post('level'),TRUE),TRUE);
	   $crash_id             = $this->input->post('cid');
	   $device_unique_number = $this->input->post('dun');
	   $email_message        = $this->input->post('level2_message');
	   $firmware_details     = json_decode($this->input->post('firmware'),TRUE);
	   $users                = json_decode($this->input->post('users'),TRUE);

	   // ATTACHMENT
	   if(isset($_FILES))
	   {
	     if($_FILES['file']['name'] !='')
		 {
			 $config['upload_path']   = LEVEL2;
			 $config['allowed_types'] = '*';
			 $config['max_size']      = '4096';
			 $config['encrypt_name']  = TRUE;
		 
			 $this->load->library('upload',$config);
			  
			  foreach($_FILES as $index => $value)
			  {
				 if ( ! $this->upload->do_upload($index))
				 {
					 echo "file upload failed";
					 return FALSE;
				 }
				 else
				 {
					array_push($upload_info,$this->upload->data());
				 }
				 
			  }
		  
			  $uploaded_file = array(
			'file_name' => $upload_info[0]['file_name'],
			'client_name' => $upload_info[0]['client_name'],
			'file_path' => $upload_info[0]['file_relative_path']);
			}
		}
		
	   
		   $data = array(
		   'crash_id'             	  => $crash_id,
		   'device_unique_number' 	  => $device_unique_number,
		   'crashed_app'          	  => $crashed_app,
		   'log_details'              => $log_details,
		   'crashed_time'         	  => $crashed_time,
		   'email_message'            => $email_message,
		   'device_firmware_details'  => $firmware_details,
		   'status'                   => 'new',
		   'log_received_time'        => date('Y-m-d H:i:s')
		   );
	   
	   if(isset($_FILES))
	   {
	       if($_FILES['file']['name'] !='')
		   {
	         $data['attachments'] = $uploaded_file;
		   }
	   }
	   
	   // Logged in admin details
		$userdetails    = $this->session->userdata("customer");
		$user           = $userdetails['email'];
		$level          = $userdetails['level'];
		$user_name      = str_replace("@","#",$user);
		$collection     = $user_name.'_support_inbox';
		
	    $ticket_collection  = $user_name.'_ticket_inbox';
	    $new_tickets        = $this->support_common_lib->MY_new_ticketcount($ticket_collection);
		
		// Forwarded time
		$forwarded_time = date('Y-m-d H:i:s');
		
		$data['forwarded_by']  = $user;
		$data['forwarded_on']  = $forwarded_time;
	   
	    foreach($users as $inneruser)
	    {
	     $levadmin       = str_replace("@","#",$inneruser);
	     $usercollection = $levadmin.'_support_inbox';
	     $result = $this->support_admin_model->forward_data_to_second_level($usercollection,$data);
	    }
	   
	   if($result)
	   {
	      if($level=='2')
		  {
	         $this->support_admin_model->post_forward_process_from_second_level($user,$forwarded_time,$collection,$device_unique_number,$crash_id,$users);
		  }
		  $this->data['message'] = 'Forward Success !';
	   }
	   else
	   {
	      $this->data['message'] = 'Forward Failed !';
	   }	   
	   
	   $this->data['message']     = 'Forward Success !';
	   $this->data['last_login']  = $userdetails['old_last_login'];
	   $this->data['new_tickets'] = $new_tickets;
	   $this->_render_page('support_admin/second_level/inbox', $this->data);
	   
	}
	  
	  // --------------------------------------------------------------------

	   /**
	  * Delete sub-folders within a folder  
	  *
	  *
	  * @param	string	$directory  Path to delete a file
	  * @param	boolean	$empty     
	  * 
	  * @author  Unknown
	  */

	   function deleteAll($directory, $empty = false) 
	   {
		
		 if(substr($directory,-1) == "/") {
			$directory = substr($directory,0,-1);
		 }
	
		 if(!file_exists($directory) || !is_dir($directory)) {
			return false;
		 } elseif(!is_readable($directory)) {
			return false;
		 } else {
			$directoryHandle = opendir($directory);
	
			while ($contents = readdir($directoryHandle)) {
				if($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
	
					if(is_dir($path)) {
						$this->deleteAll($path);
					} else {
						unlink($path);
					}
				}
			}
	
			closedir($directoryHandle);
	
			if($empty == false) {
				if(!rmdir($directory)) {
					return false;
				}
			}
	
			return true;
		}
	  }

}
