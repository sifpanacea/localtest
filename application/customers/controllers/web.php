<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Web extends CI_Controller {

	  function __construct()
	  {
	     
		 parent::__construct();
		
		 $this->load->library('ion_auth');
		 $this->load->library('form_validation');
		 $this->load->helper('url');
		 $this->load->helper('paas');

		 // Load MongoDB library instead of native db driver if required
		 $this->config->load('email');
		 $this->load->library('mongo_db');

		 $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
         
		 $this->lang->load('auth');
		 $this->load->helper('language');
	  }
	  
	   // ------------------------------------------------------------------------

	  /**
	  * Helper: Session validation for ajax call
	  *  
	  * @author Selva 
	  */
	  
	  function ajax_session_validation()
	  {
	     if ((! $this->ion_auth->logged_in()) || (! $this->ion_auth->is_plan_active())){
        		return "false";
        	}
			else
			{
			   return "true";
			}
	  
	  }
	
      // ------------------------------------------------------------------------

	  /**
	  * Helper: Default page after login ( enterprise user )
	  *  
	  * @author Selva 
	  */

	  public function index()
	  {
		  $this->load->view('user/user_dash');
	  }
	
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Login a user
	  *  
	  * @author Selva 
	  */

	  public function login()
	  {
		  redirect(URC.'auth/login');
	  }
	
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Logouts a user
	  *  
	  * @author Selva 
	  */

	  public function logout()
	  {
		 redirect(URC.'auth/logout');
	  }
	
	
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Username of logged in user
	  *  
	  * @author Selva 
	  */

	  public function username()
	  {
	    $session_flag = $this->ajax_session_validation();
		if($session_flag == "true")
		{
			$user = $this->ion_auth->user()->row();
			$name = $user->username;
			$this->output->set_output(json_encode($name));
	    }
		else
		{
		    $this->output->set_output($session_flag);
		}
	  }	  
	
	  // ------------------------------------------------------------------------

	  /**
	  * Helper: Lists apps assigned for logged in user
	  *  
	  * @author Selva 
	  */

	  public function apps()
	  {
	     $session_flag = $this->ajax_session_validation();
		 if($session_flag == "true")
		 {
		     $u = $this->session->userdata('customer');
			 $user = $u['email'];
			 $username = str_replace("@","#",$user);
			 $usercollection=$username.'_web_apps';
			 $this->load->model('reader_Model');
			 $this->data['apps'] = $this->reader_Model->user_web_apps($usercollection);
			 $this->_render_page('user/user_dash_apps', $this->data);
		 }
		 else
		 {
		     $this->output->set_output($session_flag);
		 }
	     
	  }

	 // ---------------------------------------------------------------------------------------

	 /**
	 * Helper: get the new application details for logged in user
	 *
	 * @author Selva 
	 *
	 *
	 * @return array
	 *  
	 */
	
	function get_update_apps()
	{ 
	    $session_flag = $this->ajax_session_validation();
		if($session_flag == "true")
		{
			log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($session_flag,true));		
			$u = $this->session->userdata("customer");
			$user = $u['email'];
			$username = str_replace("@","#",$user);
			$usercollection=$username.'_web_apps';
			$this->load->model('reader_Model');
			$data['uplist']=$this->reader_Model->update_apps($usercollection);
			
			$apps_details = array();
			foreach ($data['uplist'] as $details)
			{	
				array_push($apps_details,$details);
				
			}
			
			$this->output->set_output(json_encode($apps_details));
        }
		else
		{
			log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($session_flag,true));
		    $this->output->set_output($session_flag);
		}
    }

     // ---------------------------------------------------------------------------------------

	 /**
	  * Helper: get the new document details for logged in user
	  *
	  * @author Selva 
	  *
	  *
	  * @return array
	  *  
	  */

	 function get_update_docs()
	 { 
	    $session_flag = $this->ajax_session_validation();
		
		if($session_flag == "true")
		{
			$doc_details = array();

			$userdetails    = $this->session->userdata("customer");
			$user           = $userdetails['email'];
			$username       = str_replace("@","#",$user);
			$usercollection = $username.'_web_docs';
			$this->load->model('reader_Model');
			$data['doclist']=$this->reader_Model->update_docs($usercollection);
			
			//foreach ($data['doclist'] as $details)
			//{	
			//	array_push($doc_details,$details);
			//}
		
			$this->output->set_output(json_encode($data['doclist']));
		}
        else
        {
            $this->output->set_output($session_flag);
        }		
     }

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the application details for inbox view
	 *
	 * @author Selva 
	 *
	 * @param string $app_id Application id
	 *
	 * @return array
	 *  
	 */

	function get_application_details($app_id)
	{
	    $session_flag = $this->ajax_session_validation();
        
		if($session_flag == "true")
		{
			$userdetails  = $this->session->userdata('customer');
			$user         = $userdetails['email'];
			$username = str_replace("@","#",$user);
			$usercollection=$username.'_web_apps';
			$this->load->model('reader_Model');
			$app_details = $this->reader_Model->get_application_details_from_collection($usercollection,$app_id);
			$app_info['app_details']  = $app_details;
		    $app_info['user_details'] = $userdetails;
            $this->output->set_output(json_encode($app_info));
        }
		else
		{
		    $this->output->set_output($session_flag);
		}
	}


	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the document details for inbox view
	 *
	 * @author Selva 
	 *
	 * @param string $app_id Application id
	 * @param string $doc_id Document id
	 *
	 * @return array
	 *  
	 */

	function get_document_details($app_id,$doc_id)
	{
	    $session_flag = $this->ajax_session_validation();
		
		if($session_flag == "true")
		{
			$doc_info = array();
			$userdetails  = $this->session->userdata('customer');
			$user         = $userdetails['email'];
			$username = str_replace("@","#",$user);
			$usercollection=$username.'_web_docs';
			$this->load->model('reader_Model');
			$doc_details = $this->reader_Model->get_document_details_from_collection($usercollection,$app_id,$doc_id);
			$doc_info['doc_details'] = $doc_details;
			$doc_info['user_details'] = $userdetails;
			$this->output->set_output(json_encode($doc_info));
		}
		else
		{
		    $this->output->set_output($session_flag);
		}
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Profile details of logged in user
	 *
	 * @author Selva 
	 *
	 *  
	 */

	function profile()
	{
	   $u = $this->session->userdata("customer");
	   $user    = $u['email'];
	   $company = $u['company'];

	   $this->load->model('reader_Model');
	   $this->data['profile_data'] = $this->reader_Model->user_profile_data($user);
	   $this->data['company_data'] = $this->reader_Model->user_company_data($company);
	   $this->data['email'] = $user;
	   $this->_render_page('user/user_profile_page', $this->data);
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Edit the profile details
	 *
	 * @author Selva 
	 *
	 * @param string $company  Company name
	 * @param string $emailid  Email id (encoded with base64)
	 *
	 *  
	 */

	 function edit_profile($company,$emailid)
	 {
		
        $email = base64_decode($emailid);
        if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}

        //validate form input
		$this->form_validation->set_rules('phone', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('signup_customer_company_contact_email'), 'required|valid_email');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'xss_clean');
		

		if (isset($_POST) && !empty($_POST))
		{
                $data = array(
				'phone'      => $this->input->post('phone'),
				'email'      => $this->input->post('email'),
				'username' => $this->input->post('username')
			);

            if (isset($_FILES) && !empty($_FILES))
		    {
		       if($_FILES['profile_image']['tmp_name']!='')
			   {
				  $uploaddir = PROFILEUPLOADFOLDER;
				  
				  if (!is_dir($uploaddir))
				  {
					 mkdir($uploaddir,0777,TRUE);
				  }
				  
				  // Logged In Admin Details
				  $loggedinuser  = $this->session->userdata("customer");
				  $loggedemail   = $loggedinuser['email'];
			   
				  /***** Profile Image *****/
				  $file = $uploaddir.$loggedemail.".png";

                  if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $file)) 
				  { 
						  // creating image thumbnail for header profile image
						  // Get the CodeIgniter super object
						  $CI =& get_instance();

						  // Path to image thumbnail
						  $image_thumb = $uploaddir.$loggedemail."_thumb.png";

						  // LOAD LIBRARY
						  $CI->load->library( 'image_lib' );

						  // CONFIGURE IMAGE LIBRARY
						  $config['image_library']    = 'gd2';
						  $config['source_image']     = $file;
						  $config['new_image']        = $image_thumb;
						  $config['maintain_ratio']   = false;
						  $config['height']           = 50;
						  $config['width']            = 50;
						  $CI->image_lib->initialize( $config );
						  $CI->image_lib->resize();
						  $CI->image_lib->clear();
				  }
				  else
				  {
						$this->session->set_flashdata('message', "Profile Image upload failed");
						redirect('web/profile');
				  }				  
		        }
		    }
			
			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->user_profile_update($company,$email,$data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "Profile updated");
				redirect("web/profile");
			}
		}

		//display the edit user form
		$this->data['csrf'] = get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$user = $this->ion_auth->user()->row();
 
		$this->data['email'] = array(
				'name'     => 'email',
				'id'       => 'email',
				'type'     => 'email',
				'value'    => $this->form_validation->set_value('email', $user->email),
				'readOnly' => 'readOnly'
			); 
		
		
		$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $user->phone),
			);
			
		$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username', $user->username),
			);		

		$this->_render_page('user/user_profile_edit', $this->data);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Change password
	 *
	 * @author Ben 
	 *
	 *  
	 */

	public function change_password()
	{
	    $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new_pwd', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->session->keep_flashdata('message');
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
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);
           
			//render
			$this->_render_page('user/change_password', $this->data);
		}
		else
		{
			$data = $this->session->userdata("customer");
			$identity = $data['email'];

			$change = $this->ion_auth->change_user_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('web/change_password', 'refresh');
			}
		}
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Changes the status of app
	 *
	 * @param string $app_id  Application id
	 *
	 * 
	 * @author Sekar  
	 */

	public function set_status_apps($app_id)
	{
	$userdetails = $this->session->userdata('customer');
	$user = $userdetails['email'];
	$username = str_replace("@","#",$user);
	$usercollection=$username.'_web_apps';
	$this->load->model('reader_Model');
	$this->reader_Model->process_web_apps($usercollection,$app_id);
	}
  
    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get installed apps (with status as processed)
	 *
	 *
	 * @author Sekar  
	 */

    function get_update_read_apps()
	{ 
	    $session_flag = $this->ajax_session_validation();
		
		if($session_flag == "true")
		{
			$u = $this->session->userdata("customer");
			$user = $u['email'];
			$username = str_replace("@","#",$user);
			$usercollection=$username.'_web_apps';
			$this->load->model('reader_Model');
			$data['uplist']=$this->reader_Model->update_read_apps($usercollection);
			
			$apps_details = array();

			foreach ($data['uplist'] as $details)
			{	
				array_push($apps_details,$details);
			}
			
			$this->output->set_output(json_encode($apps_details));
		}
		else
		{  
		   $this->output->set_output($session_flag);
		}
    }

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Loads search page with apps assigned for that user 
	 *
	 *
	 * @author Selva  
	 */

    function doc_search()
    {
	   $session_flag = $this->ajax_session_validation();
	   
	   if($session_flag == "true")
	   {
		   $u = $this->session->userdata("customer");
		   $userdetails = $u['email'];
		   $user = str_replace("@","#",$userdetails);
		   $collection = $user.'_applist';
		   $this->load->model('reader_Model');
		   $this->data['search_data'] = $this->reader_Model->web_doc_search($collection);
		   $this->_render_page('user/user_dash_docs_search', $this->data);
	   }
	   else
	   {
          $this->output->set_output($session_flag);
	   }	   

    }

     // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Computes the subscription days left count 
	 *
	 *
	 * @author Selva  
	 */

	 function dateDifference()
	 {
	      $session_flag = $this->ajax_session_validation();
		  
		  if($session_flag == "true")
	     {
			  $det     = $this->session->userdata("customer");
			  $company = $det['company']; 
			  
			  $this->load->model('reader_Model');
			  $expirydate = $this->reader_Model->expiry_date($company);
			  $expiryday = strtotime($expirydate);
			  $currentday = strtotime(date("Y-m-d"));
			  $daysleftt = $expiryday - $currentday;
			  $dayss = floor($daysleftt/3600/24);
			  $this->output->set_output(json_encode($dayss));
	     }
		 else
		 {
		     $this->output->set_output($session_flag);
		 }
		 
	 } 
}

/* End of file web.php */
/* Location: ./application/customers/controllers/web.php */