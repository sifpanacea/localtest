<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_app_builder extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		//$this->load->library('mongo_db');
		$this->config->load('email');
		$this->load->library('PaaS_common_lib');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->model('sub_app_builder_model');
		$this->collections = $this->config->item('collections','ion_auth');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}
    
    // ------------------------------------------------------------------------
    /**
     * Helper: Get event app requests from sub admin
     *
     * @author Selva
     */
    
    public function get_event_requests()
    {
    	$data = $this->sub_app_builder_model->get_event_requests_from_collection();
		if($data){
			foreach ($data as $event){
				$form_data = array('event_status'=>'read');
				$this->sub_app_builder_model->update_event_requests_status($event['id'],$form_data);
			}
		}
	    $this->data['events'] = $data;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('sub_app_builder/admin_dash_event_requests',$this->data);
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Event create page
	 *  
	 * @author Selva  
	 */

	function event_prop($event_id,$event_name,$event_desc,$updType = FALSE,$template = FALSE)
	{
	 	$this->check_for_admin();
	 	$this->check_for_plan('event_prop');


	 	$this->data['title']    = "Event Properties";
        $this->data['updType']  = 'create';
         
        $temp = $template['event_template'];
        $this->data['event_desc'] = base64_decode($event_desc);
        $this->data['event_comment'] = "";

		if ($updType == 'edit')
		{
		    $this->data['template'] = $temp;
			$this->data['updType'] = 'edit';
			$this->data['event_comment'] = $template['comments'];
	    }
        
		$this->data['event_id']    = $event_id;
		$this->data['event_name']  = base64_decode($event_name);
		$this->data['message']     = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	    $this->_render_page('sub_app_builder/event_template', $this->data);
		      
	}
	
	// --------------------------------------------------------------------

	/**
	* Helper : Get Event app - edit part
	*
	* @author  Selva 
	* 
	*/

	function get_event_template($event_id,$event_name,$event_desc,$updType)
    {
    	$this->check_for_admin();
    	$this->check_for_plan('get_event_template');
    	
    	//get the item
    	$template = $this->ion_auth->get_event_app_temp($event_id);
        $this->event_prop($event_id,$event_name,$event_desc,$updType,$template);
    }
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Storing event application json in db
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function save_event_form()
	{
	
		//Rule for validation
		$this->form_validation->set_rules('event_id', 'event_id', 'required|xss_clean');
	    
	
	    //validate the fields of form
	    if ($this->form_validation->run() == FALSE) 
	    {
	
	        $data = $this->sub_app_builder_model->get_event_requests_from_collection();
		    $this->data['events']  = $data;
		    $this->data['message'] = 'Event Application Not Created !';
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		    $this->_render_page('sub_app_builder/admin_dash_event_requests',$this->data);
	    }
	    else
	    {
	
            $event_id        = $this->input->post('event_id', TRUE);
		    $event_json	     = $this->input->post('event_code', TRUE);
		    $event_template  = json_decode("{".$event_json."}", TRUE);
		    $updType         = $this->input->post('updType', TRUE );
			$pagenumber      = $this->input->post('pagenumber', TRUE );
            
			$userdetail  = $this->ion_auth->customer()->row();
			$useremail   = $userdetail->email;

		    if($updType == "edit")
		    {
		    	$existversion = $this->sub_app_builder_model->version_with_id_event($event_id);//$this->mongo_db->where('id', $event_id)->select(array('_version'),array())->get($this->collections['event_requests']);
				
	            // foreach($version_with_id as $ver)
		        // {
		           // $existversion = $ver['_version'];
		        // }
		        $newversion = $existversion + 1;

		        $data['event_template']    = $event_template;
				$data['pages']             = $pagenumber;
				$data['created_by']        = $useremail;
				$data['_version']          = $newversion;
				$data['req_status']        = "Processed";

		        $this->sub_app_builder_model->update_event($event_id,$data);
				//$this->mongo_db->where('id', $event_id)->set($data)->update($this->collections['event_requests']);
				
				//- - - - - EMAIL NOTIFICATION - - - - -//
				
				$event_data = $this->sub_app_builder_model->mail_id_event($event_id);
				$email_id = $event_data['requested_user_id'];
				$event_name = $event_data['event_name'];
				
				$fromaddress = $this->config->item('smtp_user');
				$this->email->set_newline("\r\n");
				$this->email->set_crlf("\r\n");
				$this->email->from($fromaddress,'TLSTEC');
				$this->email->to($email_id);
				$this->email->subject('Requested event form "'.$event_name.'" modified');
				
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
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello,</h3>
										<p class="lead" style="font-size:17px;">
										 Event form "'.$event_name.'" is modified as per your requirements. Please verify and start using it..</p><br>
										 Regards,<br>
										 Admin
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
				
                $data = $this->sub_app_builder_model->get_event_manage_requests_from_collection();
				if($data){
					foreach ($data as $event){
						$form_data = array('event_status'=>'read');
						$this->sub_app_builder_model->update_event_requests_status($event['id'],$form_data);
					}
				}
				$this->data['events'] = $data;
				   
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
					
				$this->_render_page('sub_app_builder/admin_dash_manage_events',$this->data);
	
	        }
			else
			{
			    $data['event_template']    = $event_template;
				$data['pages']             = $pagenumber;
				$data['created_by']        = $useremail;
				$data['_version']          = 1;
				$data['req_status']        = "Processed";
				
				$this->sub_app_builder_model->update_event($event_id,$data);
				//$this->mongo_db->where('id', $event_id)->set($data)->update($this->collections['event_requests']);
				
				//- - - - - EMAIL NOTIFICATION - - - - -//
				
				$event_data = $this->sub_app_builder_model->mail_id_event($event_id);
				$email_id = $event_data['requested_user_id'];
				$event_name = $event_data['event_name'];
				
				$fromaddress = $this->config->item('smtp_user');
				$this->email->set_newline("\r\n");
				$this->email->set_crlf("\r\n");
				$this->email->from($fromaddress,'TLSTEC');
				$this->email->to($email_id);
				$this->email->subject('Requested event form "'.$event_name.'" created');
				
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
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello,</h3>
										<p class="lead" style="font-size:17px;">
										 Event form "'.$event_name.'" is created as per your requirements. Please start using it..</p><br>
										 Regards,<br>
										 Admin
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
				
				$data = $this->sub_app_builder_model->get_event_requests_from_collection();
				if($data){
					foreach ($data as $event){
						$form_data = array('event_status'=>'read');
						$this->sub_app_builder_model->update_event_requests_status($event['id'],$form_data);
					}
				}
				$this->data['events'] = $data;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
				
				$this->_render_page('sub_app_builder/admin_dash_event_requests',$this->data);
			
			}
		    
       }

    } 
    
	// ------------------------------------------------------------------------
    /**
     * Helper: Manage event apps created
     *
     * @author Selva
     */
    
    public function manage_event_apps()
    {
       $data = $this->sub_app_builder_model->get_event_manage_requests_from_collection();
	   if($data){
			foreach ($data as $event){
				$form_data = array('event_status'=>'read');
				$this->sub_app_builder_model->update_event_requests_status($event['id'],$form_data);
			}
		}
	   $this->data['events'] = $data;
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	   $this->_render_page('sub_app_builder/admin_dash_manage_events',$this->data);
    	
    }

	
	// ------------------------------------------------------------------------
    /**
     * Helper: Get feedback app requests from sub admin
     *
     * @author Selva
     */
    
    public function get_feedback_requests()
    {
    	$data = $this->sub_app_builder_model->get_feedback_requests_from_collection();
		if($data){
			foreach ($data as $feedback){
				$form_data = array('feedback_status'=>'read');
				$this->sub_app_builder_model->update_feedback_requests_status($feedback['id'],$form_data);
			}
		}
		$this->data['feedbacks'] = $data;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('sub_app_builder/admin_dash_feedback_requests',$this->data);
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Feedback create page
	 *  
	 * @author Selva 
	 */

	function feedback_prop($feedback_id,$feedback_name,$feedback_desc,$updType = FALSE,$template = FALSE)
	{
	 	$this->check_for_admin();
	 	$this->check_for_plan('feedback_prop');
		

	 	$this->data['title']    ="Feedback Properties";
        $this->data['updType']  = 'create';
         
        $temp = $template['feedback_template'];
		$this->data['feedback_desc'] = base64_decode($feedback_desc);
        $this->data['feedback_comment'] = "";

		if ($updType == 'edit')
		{
		    $this->data['template'] = $temp;
			$this->data['updType']  = 'edit';
			$this->data['feedback_comment'] = $template['comments'];
	    }
        
		$this->data['feedback_id']    = $feedback_id;
		$this->data['feedback_name']  = base64_decode($feedback_name);
		$this->data['message']        = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	    $this->_render_page('sub_app_builder/feedback_template', $this->data);
		      
	}
	
	// --------------------------------------------------------------------

	/**
	* Helper : Get Feedback app - edit part
	*
	* @author  Selva 
	* 
	*/

	function get_feedback_template($feedback_id,$feedback_name,$feedback_desc,$updType)
    {
    	$this->check_for_admin();
    	$this->check_for_plan('get_feedback_template');
    	
    	//get the item
    	$template = $this->ion_auth->get_feedback_app_temp($feedback_id);
        $this->feedback_prop($feedback_id,$feedback_name,$feedback_desc,$updType,$template);
    }
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Storing feedback application json in db
	 *
	 * @author  Selva
	 *
	 * 
	 */

	function save_feedback_form()
	{
	
		//Rule for validation
		$this->form_validation->set_rules('feedback_id', 'feedback_id', 'required|xss_clean');
	    
	
	    //validate the fields of form
	    if ($this->form_validation->run() == FALSE) 
	    {
	
	        $data = $this->sub_app_builder_model->get_feedback_requests_from_collection();
		    $this->data['feedbacks']  = $data;
		    $this->data['message']    = 'Feedback Application Not Created !';
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		    $this->_render_page('sub_app_builder/admin_dash_feedback_requests',$this->data);
	    }
	    else
	    {
	
            $feedback_id       = $this->input->post('feedback_id', TRUE);
		    $feedback_json	   = $this->input->post('feedback_code', TRUE);
		    $feedback_template = json_decode("{".$feedback_json."}", TRUE);
		    $updType           = $this->input->post('updType', TRUE );
			$pagenumber        = $this->input->post('pagenumber', TRUE );
            
			$userdetail  = $this->ion_auth->customer()->row();
			$useremail   = $userdetail->email;

		    if($updType == "edit")
		    {
		    	$existversion = $this->sub_app_builder_model->version_with_id_feedback($feedback_id);//$this->mongo_db->where('id', $event_id)->select(array('_version'),array())->get($this->collections['event_requests']);
				
	            // foreach($version_with_id as $ver)
		        // {
		           // $existversion = $ver['_version'];
		        // }
		        $newversion = $existversion + 1;

		        $data['feedback_template'] = $feedback_template;
				$data['pages']             = $pagenumber;
				$data['created_by']        = $useremail;
				$data['_version']          = $newversion;
				$data['req_status']        = "Processed";
				

		        $this->sub_app_builder_model->update_feedback($feedback_id,$data);
				//$this->mongo_db->where('id', $feedback_id)->set($data)->update($this->collections['feedback_requests']);
				
				//- - - - - EMAIL NOTIFICATION - - - - -//
				
				$feedback_data = $this->sub_app_builder_model->mail_id_feedback($feedback_id);
				$email_id = $feedback_data['requested_user_id'];
				$feedback_name = $feedback_data['feedback_name'];
				
				$fromaddress = $this->config->item('smtp_user');
				$this->email->set_newline("\r\n");
				$this->email->set_crlf("\r\n");
				$this->email->from($fromaddress,'TLSTEC');
				$this->email->to($email_id);
				$this->email->subject('Requested feedback form "'.$feedback_name.'" modified');
				
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
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello,</h3>
										<p class="lead" style="font-size:17px;">
										 Feedback form "'.$feedback_name.'" is modified as per your requirements. Please verify and start using it..</p><br>
										 Regards,<br>
										 Admin
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
				
                $data = $this->sub_app_builder_model->get_feedback_manage_requests_from_collection();
				if($data){
					foreach ($data as $feedback){
						$form_data = array('feedback_status'=>'read');
						$this->sub_app_builder_model->update_feedback_requests_status($feedback['id'],$form_data);
					}
				}
				$this->data['feedbacks'] = $data;
				   
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
					
				$this->_render_page('sub_app_builder/admin_dash_manage_feedbacks',$this->data);
	        }
			else
			{
			    $data['feedback_template'] = $feedback_template;
				$data['pages']             = $pagenumber;
				$data['created_by']        = $useremail;
				$data['_version']          = 1;
				$data['req_status']        = "Processed";
				
				$this->sub_app_builder_model->update_feedback($feedback_id,$data);
				//$this->mongo_db->where('id', $feedback_id)->set($data)->update($this->collections['feedback_requests']);
				
				//- - - - - EMAIL NOTIFICATION - - - - -//
				
				$feedback_data = $this->sub_app_builder_model->mail_id_feedback($feedback_id);
				$email_id = $feedback_data['requested_user_id'];
				$feedback_name = $feedback_data['feedback_name'];
				
				$fromaddress = $this->config->item('smtp_user');
				$this->email->set_newline("\r\n");
				$this->email->set_crlf("\r\n");
				$this->email->from($fromaddress,'TLSTEC');
				$this->email->to($email_id);
				$this->email->subject('Requested feedback form "'.$feedback_name.'" created');
				
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
										<h3 style="font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000;font-weight:500;font-size:27px;">Hello,</h3>
										<p class="lead" style="font-size:17px;">
										 Feedback form "'.$feedback_name.'" is created as per your requirements. Please start using it..</p><br>
										 Regards,<br>
										 Admin
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
				
				
				$data = $this->sub_app_builder_model->get_feedback_requests_from_collection();
				if($data){
					foreach ($data as $feedback){
						$form_data = array('feedback_status'=>'read');
						$this->sub_app_builder_model->update_feedback_requests_status($feedback['id'],$form_data);
					}
				}
				$this->data['feedbacks'] = $data;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
				
				$this->_render_page('sub_app_builder/admin_dash_feedback_requests',$this->data);
			
			}
		    
       }

    } 
	
	// ------------------------------------------------------------------------
    /**
     * Helper: Manage feedback apps created
     *
     * @author Selva
     */
    
    public function manage_feedback_apps()
    {
	   $data = $this->sub_app_builder_model->get_feedback_manage_requests_from_collection();
	   if($data){
			foreach ($data as $feedback){
				$form_data = array('feedback_status'=>'read');
				$this->sub_app_builder_model->update_feedback_requests_status($feedback['id'],$form_data);
			}
		}
	   $this->data['feedbacks'] = $data;
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	   $this->_render_page('sub_app_builder/admin_dash_manage_feedbacks',$this->data);
    }
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: download file securely
	 *
	 *@author Selva 
	 */

	public function download_attachment($relpath)
	{
		$path = str_replace('=','/',$relpath);
		$this->external_file_download($path);
    }

    
}
