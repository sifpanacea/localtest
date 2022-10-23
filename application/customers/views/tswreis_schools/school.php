<?php defined('BASEPATH') OR exit('No direct script access allowed');

class School extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->model('school_model');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('paas');
		$this->load->library('PaaS_common_lib');
		$this->config->load('email');
		$this->load->library('mongo_db');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->helper('language');
	}

    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function index()
    {
	    if (!$this->ion_auth->logged_in())
	    {
		   redirect(URC.'auth/login');
	    }

		$this->data['message'] = "This is where you can connect with our 3rd party intgration";
		//list the apps
		$this->data['apps'] = $this->ion_auth->apps();
		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
			
		$this->_render_page('admin/admin_dash_api', $this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 * @author  Vikas
	 *
	 * 
	 */
	 
	function fetch_school_names()
	{
    	$appid       = $_POST['dataid'];
		$field_name  = $_POST['field_name'];
		$field       = base64_decode($field_name);
		$field       = "doc_data.widget_data.page".$field;
		
		$schools = $this->school_model->fetch_school_names_for_chart($appid,$field);
	    $this->output->set_output(json_encode($schools));
	}
	

   
    
}
