<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* File: application/core/MY_Controller.php */
	class MY_Controller extends CI_Controller
{
	//var $SITE_LANGUAGE='english';

    function __construct()
    {
        parent::__construct();
        
		log_message('debug','$this->session->userdata(user_agent)=====my_controller=====11====='.print_r($this->session->userdata("user_agent"),true));
		log_message('debug','$this->session->userdata(user_agent)=====my_controller=====11====='.print_r($this->session->all_userdata(),true));
		
		log_message('debug','$this->input->cookie(ci_session)=====my_controller=====13======'.print_r($this->input->cookie("ci_session"),true));
		//log_message('debug','$this->input->cookie(ci_session)=====my_controller=====13======'.print_r($this->input->cookie());
		
		$t = base64_decode($this->input->cookie('ci_session', TRUE));
        $idata = json_decode($t,true);
		
		log_message('debug','$idata=====my_controller=====18'.print_r($idata,true));
		
        if($this->session->userdata("user_agent") == DEVICE_UA)
        {
        	
        	$t = base64_decode($this->input->cookie('ci_session', TRUE));
        	$idata = json_decode($t,true);
        	$this->session->set_userdata("customer",$idata);
	         if ((! $this->ion_auth->logged_in()))// || (! $this->ion_auth->is_plan_active()))
		     {
		        log_message('debug','ECHO=============1'.print_r($this->input->cookie("ci_session"),true));
		         echo 1;
		         exit;
		     }	
        }
		else if($this->session->userdata("user_agent") == PATIENT_APP_UA)
        {
		    $t = base64_decode($this->input->cookie('ci_session', TRUE));
        	$idata = json_decode($t,true);
			log_message('debug','$idata=====my_controller=====32'.print_r($idata,true));
        	$this->session->set_userdata("customer",$idata);
        	if ((! $this->ion_auth->logged_in()))
        	{
        		echo 1;
        		exit;
        	}
        }
		else if($this->session->userdata("user_agent") == SCHOOLHEALTH_UA)
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
		else if($this->session->userdata("user_agent") == "Selva-1.0")
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
        else{
			log_message('debug','$idata=====my_controller=====59'.print_r($this->ion_auth->logged_in(),true));
        	if ((! $this->ion_auth->logged_in()) || (! $this->ion_auth->is_plan_active())){
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