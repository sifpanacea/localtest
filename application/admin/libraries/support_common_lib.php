<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Support_common_lib 
{
	
	// --------------------------------------------------------------------

    /**
	* Constructor
	*
	*/

	public function __construct()
	{
		$this->ci = &get_instance();         // In custom libraries we need to get instance of ci to make use of ci core classes (here we use Loader class)
		
		$this->ci->load->library('ion_auth');
		$this->ci->load->library('session');
		$this->ci->load->helper('url');
		$this->ci->load->helper('paas');
		$this->ci->lang->load('auth');
		$this->ci->config->load('email');
		$this->ci->load->library('mongo_db');
	
	}

    
    // --------------------------------------------------------------------

	/**
	 * Generating pagination config values 
	 *
	 *
	 * @param	int	    $total_rows       Number of total rows
	 * @param	int	    $per_page         Per page count
	 *
	 * @return  array
	 *
	 * @author  Selva
	 */

     public function set_paginate_options($total_rows,$per_page)
     {
    	$config = array();

    	$config['base_url']         = site_url() .'/'.$this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2);
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

        return $config;
     }
	 
	 // --------------------------------------------------------------------

	/**
	 * Helper : Send email notification to ticket owner  
	 *	
	 *
	 * @author  Selva
	 */

     public function send_email_notification_to_user($email,$service_req_no,$type)
     {
		//- - - - - EMAIL NOTIFICATION - - - - -//
		$fromaddress = $this->ci->config->item('smtp_user');
		$this->ci->email->set_newline("\r\n");
		$this->ci->email->set_crlf("\r\n");
		$this->ci->email->from($fromaddress,'TLSTEC');
		$this->ci->email->to($email);
		
		// Data passed to view
		$this->ci->data['service_req_no'] = $service_req_no;
		
		if($type == 'ack')
		{
		  $this->ci->email->subject("Ticket Registered");
		  $msg = $this->ci->load->view('support_admin/email/email_ack',$this->ci->data,true); 
		}
        else if($type == 'reply')
        {
          $this->ci->email->subject("Ticket Resolved");
		  $msg = $this->ci->load->view('support_admin/email/email_reply',$this->ci->data,true);
		}		
		
        		
		$this->ci->email->message($msg);
		$this->ci->email->send();
		$this->ci->email->print_debugger();
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Send email notification to ticket owner  
	 *	
	 *
	 * @author  Selva
	 */

     public function send_email_notification_to_admin($email,$service_req_no)
     {
	    //- - - - - EMAIL NOTIFICATION - - - - -//
		$fromaddress = $this->ci->config->item('smtp_user');
		$this->ci->email->set_newline("\r\n");
		$this->ci->email->set_crlf('');
		$this->ci->email->from($fromaddress,'TLSTEC');
		$this->ci->email->to($email);
		$this->ci->email->subject("Ticket Assigned");
		
		$msg = "Dear Admin,
               
			         We acknowledge the receipt of your issue.We take this opportunity to thank you and it is our pleasure to serve you.
                     Kindly allow us to respond to your concern.Please note down your service request number '".$service_req_no."' for future reference.
                     We assure you timely response and resolution. Thank you for choosing TLSTEC.
                     Regards
                     Support Team";
		  
		$this->ci->email->message($msg);
		$this->ci->email->send();
		$this->ci->email->print_debugger();
	 }
	 
	// ---------------------------------------------------------------------------------

	/**
	 * Helper: Count of the tickets assigned to second level support admin ( New tickets)
	 *
	 *
	 * @return int
	 *
	 * @author Selva 
	 */
	 
	 public function MY_new_ticketcount($collection)
    {
      $count = $this->ci->mongo_db->where('status','new')->get($collection);
	  return count($count);
    }
	 
	 

}