<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('support_common_lib');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1
	 *
	 *  
	 * @author Selva 
	 */
	 
	function first_level_index()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/index');
		
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
	 * Helper : Support Admin Level 1 Inbox
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_first_level_inbox()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/inbox');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Forwaded
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_first_level_forwarded()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/forwarded');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Resolved
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_first_level_resolved()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/resolved');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Trash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_first_level_trash()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/trash');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Create Tickets
	 *
	 *  
	 * @author Selva 
	 */
	 
	function create_ticket()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/create_ticket');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Manage Tickets
	 *
	 *  
	 * @author Selva 
	 */
	 
	function first_level_manage_ticket()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/manage_ticket');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Manage Tickets
	 *
	 *  
	 * @author Selva 
	 */
	 
	function configure_device()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/configure_device');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Device Details
	 *
	 *  
	 * @author Selva 
	 */
	 
	function first_level_device_details()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/device_details');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Manage Tickets
	 *
	 *  
	 * @author Selva 
	 */
	 
	function notification()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/notification');
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Change Password
	 *
	 *  
	 * @author Selva 
	 */
	 
	function change_password()
	{
	 
	  $this->load->view('getstarted/support_admin/first_level/change_password');
		
	}
	
	// =========== SECOND LEVEL SUPPORT ADMIN HELP ==========//
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 1 Inbox
	 *
	 *  
	 * @author Selva 
	 */
	 
	function second_level_index()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	  $this->load->view('getstarted/support_admin/second_level/index',$data);
		
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
	 * Helper : Support Admin Level 2 Inbox
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_second_level_inbox()
	{
	 
	 $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/inbox',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Forwarded
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_second_level_forwarded()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/forwarded',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Resolved
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_second_level_resolved()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/resolved',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Trash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function fetch_second_level_trash()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/trash',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Trash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function second_level_device_details()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/device_details',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Trash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function log_details()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	  $this->load->view('getstarted/support_admin/second_level/log_details',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Trash
	 *
	 *  
	 * @author Selva 
	 */
	 
	function second_level_manage_tickets()
	{
	 
	  $userdetails    = $this->session->userdata("customer");
	  $user           = $userdetails['email'];
	  $username       = str_replace("@","#",$user);
	  $collection     = $username.'_ticket_inbox';
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	 
	  $this->load->view('getstarted/support_admin/second_level/manage_ticket',$data);
		
	}
	
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Support Admin Level 2 Trash
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
	  $new_tickets    = $this->support_common_lib->MY_new_ticketcount($collection);
	  
	  $data['new_tickets'] = $new_tickets;
	  
	  $this->load->view('getstarted/support_admin/second_level/change_password',$data);
		
	}

}
