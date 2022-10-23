<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Dar_activity_mgmt extends My_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Vikas
	 *
	 * @return void
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		$this->upload_info = array();
		$this->load->library('form_validation');
		$this->load->helper('url');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->library('paas_common_lib');
		$this->load->library('paas_common_lib');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
		$this->load->model('panacea_mgmt_model');
		$this->load->model('dar_activity_common_model');
		$this->load->library('panacea_common_lib');
		$this->load->library('tswreis_schools_common_lib');
		$this->load->library('session');
	}
	
	/**
	 * 
	 *
	 * @author  Vikas
	 *
	 *
	 */
	public function index()
	{
		//redirect('panacea_mgmt/to_dashboard');
		redirect('dar_activity_mgmt/to_dashboard');
	}
	
	public function panacea_mgmt_health_supervisors()
	{
		$this->check_for_admin();
		$this->check_for_plan('panacea_mgmt_health_supervisors');
		
		$this->data['health_supervisors'] = $this->panacea_common_model->get_all_health_supervisors();
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->data['health_supervisorscount'] = $this->panacea_common_model->health_supervisorscount();
		
        //$this->data = $this->panacea_common_lib->panacea_mgmt_health_supervisors();
				
		//$this->data = "";
		$this->_render_page('panacea_admins/panacea_mgmt_health_supervisors',$this->data);
	}
	
	public function create_health_supervisors()
	{
	 	$insert = $this->panacea_common_model->create_health_supervisors($_POST);
	 	if($insert){
	 		redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
	 	}else{
	 		$this->data = $this->panacea_common_lib->panacea_mgmt_health_supervisors();
	 		
	 		//$this->data = "";
	 		$this->_render_page('panacea_admins/panacea_mgmt_health_supervisors',$this->data);
	 	}
	 	
	}
	 
	
	function to_dashboard($date = FALSE, $request_duration = "Yearly", $screening_duration = "Yearly")
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard');
		
		$this->data['counts_data'] = $this->dar_activity_common_model->get_counts_for_dashboard();
		
		$this->_render_page('dar_activity_admins/power_of_ten_dar_admin_dash', $this->data);
	
	}
	
	function to_dashboard_with_date()
	{
		$this->check_for_admin();
		$this->check_for_plan('to_dashboard_with_date');
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST["request_pie_status"];
		log_message('debug','opppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$this->data = $this->panacea_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);
	
		$this->output->set_output($this->data);
	}

	public function get_registritations_confirmed_swaeros()
	{
		$district_name = $_POST['district_name_completed'];

		$this->data['confirmed_list'] = $this->dar_activity_common_model->get_registritations_confirmed_swaeros($district_name);

		$this->_render_page('dar_activity_admins/confirmed_registrations', $this->data);
	}

	public function get_registrations_pending_swaeros()
	{
		$district_name = $_POST['district_name_pending'];

		$this->data['pending'] = $this->dar_activity_common_model->get_registrations_pending_swaeros($district_name);

		$this->_render_page('dar_activity_admins/pending_registrations', $this->data);
	}

	public function get_total_received_district_coordinators()
	{
		$this->data = $this->dar_activity_common_model->get_total_received_district_coordinators();
		$this->output->set_output(json_encode($this->data));
	}

	public function get_total_pending_district_coordinators()
	{
		$this->data = $this->dar_activity_common_model->get_total_pending_district_coordinators();
		$this->output->set_output(json_encode($this->data));
	}

	public function accept_conformed_registrations()
	{

		$accept_docID = $this->input->post('doc_id');

		$this->data = $this->dar_activity_common_model->accept_conformed_registrations($accept_docID);
		if($this->data){
			$this->output->set_output(json_encode("User Accepted"));
		}else{
			$this->output->set_output(json_encode("User Acceptance not updated"));
		}
		
	}

	public function decline_conformed_registrations()
	{
		$decline_docID = $this->input->post('doc_id');
		$this->data = $this->dar_activity_common_model->decline_conformed_registrations($decline_docID);

		if($this->data){
			$this->output->set_output(json_encode("Successfully Deleted"));
		}else{

			$this->output->set_output(json_encode("No Deleted"));
		}
	}


	
	
	



  //**********************************************************************************//
		
}
