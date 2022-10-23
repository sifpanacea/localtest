<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_users extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->model('form_users_model');
		$this->load->helper('language');
		$this->config->load('email');
		$this->load->library('bhashsms');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	* Helper: Save user ( form users ) details from device end
	*
	*
	* @author Selva
	*/

	function save_form_user_details()
	{
	    // Data from device
        $postdata = file_get_contents('php://input');
        $data     = json_decode($postdata,TRUE);
		
		// DETAILS
		$name           = $data['name'];
		$email          = $data['email'];
		$dob            = $data['dob'];
		$mobile         = $data['mobile_number'];
		$address        = $data['address'];
		$sender_name    = $data['sender_username'];
		$sender_company = $data['sender_company'];
		$sender         = $data['sender'];
		$sender_email   = str_replace('#','@',$sender);
		
		$exists = $this->form_users_model->check_if_mobile_number_already_exists($mobile,$sender_email);
		
		if($exists)
		{
		   echo "already_registered";
		}
		else
		{
			$result = $this->form_users_model->save_form_user_details_model($sender_email,$name,$email,$dob,$mobile,$address);
		
			if($result)
			{
			  
			  // NOTIFY TO USER
			  $message = "Hi ".$name.",Please use your mobile number as Unique Id for future reference.\nRegards,\n".$sender_name."\n".$sender_company."";
			  
			  // SMS
			  $this->bhashsms->send_sms($mobile['mob_num'],$message);
			  
			  // EMAIL
			  $fromaddress = $this->config->item('smtp_user');
			  $this->email->set_newline("\r\n");
			  $this->email->set_crlf("\r\n");
			  $this->email->from($fromaddress,'TLSTEC');
			  $this->email->to($email);
			  $this->email->subject("Unique ID for future reference");
			  $email_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "    http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<!-- If you delete this meta tag, Half Life 3 will never be released. -->
				<meta name="viewport" content="width=device-width" />

				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title></title>
				</head>
				 
				<body bgcolor="#FFFFFF" style="margin:0;padding:0;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100% !important;height:100%;font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif">

				<!-- BODY -->
				<table class="body-wrap" style="width:100%">
					<tr>
						<td></td>
						<td class="container" bgcolor="#FFFFFF" style="display:block !important;max-width:600px !important;margin:0 auto !important;clear:both !important;">

							<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;">
							<table style="width:100%">
								<tr>
									<td>
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hi '.$name.' ,</h3>
										<p class="lead" style="font-size:17px;">
										 Please use your mobile number as Unique Id for future reference</p><br>
										 Regards,<br>
										 '.$sender_name.'<br>
										 '.$sender_company.'
										 <!-- Callout Panel -->
										<p class="callout" style="padding:15px;background-color:#ecf8ff;margin-bottom:15px;">
											Please do not reply back this mail as this is an automated response.
										</p><!-- /Callout Panel -->					
										
									</td>
								</tr>
							</table>
							</div><!-- /content -->
													
						</td>
						<td></td>
					</tr>
				</table><!-- /BODY -->

				</body>
				</html>';
			  $this->email->message($email_message);
			  $this->email->send();
			  $this->email->print_debugger();
			  
			  echo "SUCCESS";
			}
			else
			{
			  echo "FAIL";
			} 
			
			// Birthday message TO user
			$b_message = "Hi ".$name.", many more happy returns of the day. With best wishes,\n".$sender_name."\n".$sender_company."";
			$dateofbirth = explode('-',$dob);
			$b_date   = $dateofbirth[2].'/'.$dateofbirth[1];
			$this->form_users_model->save_birthday_event($b_message,$b_date,$mobile,$email,$sender_name);
		
		}
	}

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get user ( form users ) details from device end
	 *
	 * @return array
	 *
	 * @author Selva 
	 */

	function get_form_user_details()
	{
	    // Data from device
        $postdata     = file_get_contents('php://input');
        $data         = json_decode($postdata,TRUE);
		$sender       = $data['sender'];
		$sender_email = str_replace('#','@',$sender);
	    
        $user_details = $this->form_users_model->fetch_form_user_details($sender_email);
		
		if($user_details)
		{
		   $this->output->set_output(json_encode($user_details));
		}
    }

    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: Customized notification[SMS,EMAIL] to form ( selected ) users
	 *
	 *
	 * @author Selva 
	 */

	function send_custom_notification_to_form_users()
	{
	    // Data from device
        $postdata     = file_get_contents('php://input');
        $data         = json_decode($postdata,TRUE);
		$userslist    = $data['userslist'];
		$message      = $data['message'];
		$sender       = $data['sender'];
		$sender_email = str_replace('#','@',$sender);
		
		foreach($userslist as $id)
		{
		  $user_info = $this->form_users_model->user_details_by_id($id);
		  
		  //SMS
	      $result = $this->bhashsms->send_sms($user_info[0]['mobile']['mob_num'],$message);
		  
		  //EMAIL
		  if(isset($user_info[0]['email']) && !empty($user_info[0]['email']))
		  {
			  $emailid = $user_info[0]['email'];
			  $fromaddress = $this->config->item('smtp_user');
			  $this->email->set_newline("\r\n");
			  $this->email->set_crlf("\r\n");
			  $this->email->from($fromaddress,'TLSTEC');
			  $this->email->to($emailid);
			  $this->email->subject("Notification");
			  $email_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<!-- If you delete this meta tag, Half Life 3 will never be released. -->
				<meta name="viewport" content="width=device-width" />

				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title></title>
				</head>
				 
				<body bgcolor="#FFFFFF" style="margin:0;padding:0;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100% !important;height:100%;font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif">

				<!-- BODY -->
				<table class="body-wrap" style="width:100%">
					<tr>
						<td></td>
						<td class="container" bgcolor="#FFFFFF" style="display:block !important;max-width:600px !important;margin:0 auto !important;clear:both !important;">

							<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;">
							<table style="width:100%">
								<tr>
									<td>
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello '.$user_info[0]['name'].',</h3>
										<p class="lead" style="font-size:17px;">
										 '.$message.'</p><br>
										 Regards,<br>
										 '.$sender_email.'
										 <!-- Callout Panel -->
										<p class="callout" style="padding:15px;background-color:#ecf8ff;margin-bottom:15px;">
											Please do not reply back this mail as this is an automated response.
										</p><!-- /Callout Panel -->					
										
									</td>
								</tr>
							</table>
							</div><!-- /content -->
													
						</td>
						<td></td>
					</tr>
				</table><!-- /BODY -->

				</body>
				</html>';

				$this->email->message($email_message);
				$this->email->send();
		}
		
		}
	  
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Search by Unique ID ( Advanced search )
	 *
	 *
	 * @author Selva 
	 */
	 
	function search_by_unique_id()
    {
	   $postdata     = file_get_contents('php://input');
       $data         = json_decode($postdata,TRUE);
	   $unique_id    = $data['unique_id'];
	   $matched_documents = $this->form_users_model->search_documents_by_unique_id($unique_id);
	   if($matched_documents)
	   {
		   $this->output->set_output(json_encode($matched_documents));
	   }
    }
    
    // ---------------------------------------------------------------------------------------
    
    /**
     * Helper: Search by Extend Unique ID ( Advanced search )
     *
     *
     * @author Naresh
     */
    
    function search_by_uid()
    {
    	$postdata     = file_get_contents('php://input');
		log_message('debug','postdata==============285'.print_r($postdata,true));
    	$data         = json_decode($postdata,TRUE);
		$user_details = $this->session->userdata("customer");
    	$unique_id    = $data['unique_id'];
		log_message('debug','uiddddddddddddddddddddddddddddddddddddddddiiiiiiii'.print_r($data,true));
		log_message('debug','user_details============290'.print_r($user_details,true));
		log_message('debug','unique_id============================289'.print_r($unique_id,true));
    	//$matched_documents = $this->form_users_model->search_documents_by_uid($unique_id,$user_details['email']);
    	$matched_documents = $this->form_users_model->search_documents_by_uid($unique_id,$user_details);
		log_message('debug','matched_documents============================291'.print_r($matched_documents,true));
    	if($matched_documents)
    	{
    		$this->output->set_output(json_encode($matched_documents));
    	}
    }
	
	// ---------------------------------------------------------------------------------------
    
    /**
     * Helper: Search by Unique ID ( Advanced search )
     *
     *
     * @author Selva
     */
    
   /*  function search_by_uid()
    {
    	$postdata     = file_get_contents('php://input');
    	$data         = json_decode($postdata,TRUE);
		$user_details = $this->session->userdata("customer");
    	$unique_id    = $data['unique_id'];
		log_message('debug','uiddddddddddddddddddddddddddddddddddddddddiiiiiiii'.print_r($data,true));
		log_message('debug','unique_id============================289'.print_r($unique_id,true));
		$matched_documents = $this->form_users_model->search_documents_by_uid($unique_id,$user_details['email']);
		log_message('debug','matched_documents============================291'.print_r($matched_documents,true));
    	if($matched_documents)
    	{
    		$this->output->set_output(json_encode($matched_documents));
    	}
    }
 */
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

}
