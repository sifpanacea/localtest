<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* File: application/core/MY_Controller.php */
	class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata("user_agent") == DEVICE_UA)
        {
        	
        	$t = base64_decode($this->input->cookie('ci_session', TRUE));
        	$idata = json_decode($t,true);
        	$this->session->set_userdata("customer",$idata);
	        if ((! $this->ion_auth->logged_in()))
		    {
		       echo 1;
		       exit;
		    }	
        }
		else if($this->session->userdata("user_agent") == MASTER_UA)
        {
        	$dev_u_no = $this->input->cookie('master_session',TRUE);
			log_message('debug','$dev_u_no=====my_controller'.print_r($dev_u_no,true));
        	$this->session->set_userdata("master",$dev_u_no);
			if ((! $this->ion_auth->master_logged_in()))
		     {
			     echo 1;
		         exit;
		     }	
        }
		else
		{
        	if ((! $this->ion_auth->logged_in())){
        		redirect(URC.'auth/login');
        	}
        }
    }
   
    function check_for_admin(){
    	if ($this->ion_auth->is_user())
    	{
    		redirect(URC.'auth/login');
    	}
    }
    
    function send(){
    	if(!$this->ion_auth->check_doc_limit()){
		   	exit();
    	}
    }
    
    function create(){
    	if(!$this->ion_auth->check_doc_limit()){
    		exit();
    	}
    }
    
    function check_for_plan($check_for = FALSE){
    }
    
    
    
}

?>