<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Power_of_ten_common_lib 
{
	
	// --------------------------------------------------------------------

    /**
	* Constructor
	*
	*/

	public function __construct()
	{
		$this->ci = &get_instance();         // In custom libraries we need to get instance of ci to make use of ci core classes (here we use Loader class)
		
		$this->ci->load->config('ion_auth', TRUE);
		$this->ci->load->library('session');
		$this->ci->load->helper('url');
		$this->ci->load->helper('paas');
		$this->ci->lang->load('auth');
		
		$this->ci->config->load('config', TRUE);
		$this->ci->upload_info = array();
		//$this->ci->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->ci->load->model('panacea_common_model');
		$this->ci->load->model('power_of_ten_common_model');
		$this->ci->load->library('paas_common_lib');
		$this->ci->load->library('bhashsms');
		//$this->ci->load->model('tswreis_schools_common_model');

	}


    // --------------------------------------------------------------------

	public function panacea_mgmt_states()
	{
		$total_rows = $this->ci->panacea_common_model->statescount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['states'] = $this->ci->panacea_common_model->get_states($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['statcount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_mgmt_district()
	{
		$total_rows = $this->ci->panacea_common_model->distcount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['dists'] = $this->ci->panacea_common_model->get_district($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['distscount'] = $total_rows;

		$this->data['statelist'] = $this->ci->panacea_common_model->get_all_states();

		return $this->data;
	}
	
	public function panacea_mgmt_health_supervisors()
	{

		$total_rows = $this->ci->panacea_common_model->health_supervisorscount();
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		$this->data['health_supervisors'] = $this->ci->panacea_common_model->get_all_health_supervisors();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		session_start();
		$message = "";
		if(!empty($_SESSION['updated_message']))
			$message = $_SESSION['updated_message'];
		unset($_SESSION['updated_message']);
		// rest of your code
		log_message('debug','mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm.'.print_r($message,true));
		$this->data['message'] = $message;
		
		$this->data['health_supervisors'] = $this->ci->panacea_common_model->get_health_supervisors($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		$this->data['health_supervisorscount'] = $total_rows;


		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_mgmt_doctors()
	{

		$total_rows = $this->ci->panacea_common_model->doctorscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['doctors'] = $this->ci->panacea_common_model->get_doctors($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['doctorscount'] = $total_rows;


		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_mgmt_schools()
	{
		$total_rows = $this->ci->panacea_common_model->schoolscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['schools'] = $this->ci->panacea_common_model->get_schools($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		session_start();
		$message = "";
		if(!empty($_SESSION['updated_message']))
			$message = $_SESSION['updated_message'];
		unset($_SESSION['updated_message']);
		// rest of your code
		log_message('debug','mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm.'.print_r($message,true));
		$this->data['message'] = $message;

		$this->data['schoolscount'] = $total_rows;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();


		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_mgmt_classes()
	{

		$total_rows = $this->ci->panacea_common_model->classescount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['classes'] = $this->ci->panacea_common_model->get_classes($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['classescount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_mgmt_sections()
	{

		$total_rows = $this->ci->panacea_common_model->sectionscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['sections'] = $this->ci->panacea_common_model->get_sections($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['sectionscount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_mgmt_symptoms()
	{

		$total_rows = $this->ci->panacea_common_model->symptomscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['symptoms'] = $this->ci->panacea_common_model->get_symptoms($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['symptomscount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_mgmt_diagnostic()
	{

		$total_rows = $this->ci->panacea_common_model->diagnosticscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['diagnostics'] = $this->ci->panacea_common_model->get_diagnostics($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['diagnosticscount'] = $total_rows;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();


		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_mgmt_hospitals()
	{
		$total_rows = $this->ci->panacea_common_model->hospitalscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['hospitals'] = $this->ci->panacea_common_model->get_hospitals($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['hospitalscount'] = $total_rows;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_mgmt_emp()
	{

		$total_rows = $this->ci->panacea_common_model->empcount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['emps'] = $this->ci->panacea_common_model->get_emp($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['empcount'] = $total_rows;

		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_reports_display_ehr($post)
	{
		$docs = $this->ci->panacea_common_model->get_reports_ehr($post['ad_no']);

		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];

		$this->data['docscount'] = count($this->data['docs']);

		return $this->data;
	}
	
	public function panacea_reports_display_ehr_uid($post)
	{
		$docs = $this->ci->panacea_common_model->get_reports_ehr_uid($post['uid']);

		$this->data['docs'] = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['notes'] = $docs['notes'];
		$this->data['hs'] = $docs['hs'];

		$this->data['docscount'] = count($this->data['docs']);
		
		$this->data['fromEHR'] = "fromEHRSearch";

		return $this->data;
	}
	
	public function panacea_reports_students()
	{	
		$total_rows = $this->ci->panacea_common_model->studentscount();
		$this->data['students'] = $this->ci->panacea_common_model->get_all_students();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		session_start();
		$message = "";
		if(!empty($_SESSION['updated_message']))
			$message = $_SESSION['updated_message'];
		unset($_SESSION['updated_message']);
		// rest of your code
		$this->data['message'] = $message;

		$this->data['studentscount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_reports_doctors()
	{	
		$total_rows = $this->ci->panacea_common_model->doctorscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['doctors'] = $this->ci->panacea_common_model->get_doctors($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['doccount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_reports_hospital()
	{	
		$total_rows = $this->ci->panacea_common_model->hospitalscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['hospitals'] = $this->ci->panacea_common_model->get_hospitals($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['hospitalcount'] = $total_rows;

		return $this->data;
	}
	
	public function panacea_reports_school()
	{	
		$total_rows = $this->ci->panacea_common_model->schoolscount();

		$this->data['schools'] = $this->ci->panacea_common_model->get_all_schools();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['schoolscount'] = $total_rows;

		//$this->data = "";
		return $this->data;
	}
	
	public function panacea_reports_symptom()
	{	
		$total_rows = $this->ci->panacea_common_model->symptomscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['symptoms'] = $this->ci->panacea_common_model->get_symptoms($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		$this->data['symptomscount'] = $total_rows;

		return $this->data;
	}

	/*function to_dashboard_identifier($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms_identifier($date,$request_duration);
		log_message('error','symptoms_report=============548'.print_r($symptoms_report,TRUE));
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}

		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_duration);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
	}
	*/
	function to_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$pagenumber       = array();
		$page_data        = array();
		$sanitation_report_app = array();
		$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date);		

		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);

		}else{
			$this->data['absent_report'] = 1;
			
		}

		$this->data['absent_report_schools_list'] = $this->ci->panacea_common_model->get_absent_pie_schools_data($date);
		$this->data['sanitation_report_schools_list'] = $this->ci->panacea_common_model->get_sanitation_report_pie_schools_data($date);
		

		$count = 0;
		/*$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_duration);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}*/
		$this->data['symptoms_report'] = 1;

		$count = 0;
		/*$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_duration);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}*/
		$this->data['request_report'] = 1;
		
		$count = 0;
		/*$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		*/
		$this->data['screening_report'] = 1;
		$app_template = $this->ci->panacea_common_model->get_sanitation_report_app();
		
		foreach ($app_template as $pageno => $pages)
		{
			array_push($pagenumber,$pageno);
		}

		$pagecount = count($pagenumber);

		for($i=1;$i<=$pagecount;$i++)
		{
			array_push($page_data,$app_template[$i]);
		}

		for($ii=0;$ii<$pagecount;$ii++)
		{
			$pgno = $ii +1;
			foreach($page_data[$ii] as $section => $index_array)
			{
				unset($index_array['dont_use_this_name']);
				$sanitation_report_app[$pgno][$section] = array();
				foreach($index_array as $index => $value)
				{
					$ele_name = str_replace(' ','_',$index);
					$sec_name = str_replace(' ','_',$section);
					$path = "page".$pgno."#".$sec_name."#".$ele_name;
					switch ($value['type'])
					{
						case 'radio':
						$options = array();
						$options_array = $value['options'];
						foreach($options_array as $index_no => $val)
						{
							array_push($options,$val['label']);
						}
						break;
					}
					$opt = array('path'=>$path,'options'=>$options);
					$ele = array($index=>$opt);
					array_push($sanitation_report_app[$pgno][$section],$ele); 
				}
			}
		}
		unset($sanitation_report_app[4]['Declaration Information']);
		
		$this->data['sanitation_report_obj'] = json_encode($sanitation_report_app);
		
		$chronic_ids = $this->ci->panacea_common_model->get_all_chronic_unique_ids_model();
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['last_screening_update'] = $this->ci->panacea_common_model->get_last_screening_update($date,$screening_duration);

		$this->data['message'] = '';
		
		$this->data['today_date'] = date('Y-m-d');
		
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
		
		$this->data['total_active_req'] = $this->ci->panacea_common_model->get_all_active_request();
		$this->data['total_raised_req'] = $this->ci->panacea_common_model->get_all_raised_request();
		
		$this->data ['news_feeds'] = $this->ci->panacea_common_model->get_all_news_feeds();
		//log_message("debug","to_dashboarddddddddddddd".print_r($this->data['news_feeds'],true));
		//log_message("debug","to_dashboarddddddddddddd_dataaaaaaaaaaaaaa".print_r($this->data,true));
		return $this->data;

	}
	function to_dashboard_old_dash($date = FALSE, $request_duration = "Yearly", $screening_duration = "Yearly")
	{
		$pagenumber       = array();
		$page_data        = array();
		$sanitation_report_app = array();
		$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date);		

		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);

		}else{
			$this->data['absent_report'] = 1;
			
		}

		$this->data['absent_report_schools_list'] = $this->ci->panacea_common_model->get_absent_pie_schools_data($date);
		$this->data['sanitation_report_schools_list'] = $this->ci->panacea_common_model->get_sanitation_report_pie_schools_data($date);
		

		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms_old_dash($date,$request_duration);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}

		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests_old_dash($date,$request_duration);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		$app_template = $this->ci->panacea_common_model->get_sanitation_report_app();
		
		foreach ($app_template as $pageno => $pages)
		{
			array_push($pagenumber,$pageno);
		}

		$pagecount = count($pagenumber);

		for($i=1;$i<=$pagecount;$i++)
		{
			array_push($page_data,$app_template[$i]);
		}

		for($ii=0;$ii<$pagecount;$ii++)
		{
			$pgno = $ii +1;
			foreach($page_data[$ii] as $section => $index_array)
			{
				unset($index_array['dont_use_this_name']);
				$sanitation_report_app[$pgno][$section] = array();
				foreach($index_array as $index => $value)
				{
					$ele_name = str_replace(' ','_',$index);
					$sec_name = str_replace(' ','_',$section);
					$path = "page".$pgno."#".$sec_name."#".$ele_name;
					switch ($value['type'])
					{
						case 'radio':
						$options = array();
						$options_array = $value['options'];
						foreach($options_array as $index_no => $val)
						{
							array_push($options,$val['label']);
						}
						break;
					}
					$opt = array('path'=>$path,'options'=>$options);
					$ele = array($index=>$opt);
					array_push($sanitation_report_app[$pgno][$section],$ele); 
				}
			}
		}
		unset($sanitation_report_app[4]['Declaration Information']);
		
		$this->data['sanitation_report_obj'] = json_encode($sanitation_report_app);
		
		$chronic_ids = $this->ci->panacea_common_model->get_all_chronic_unique_ids_model();
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['last_screening_update'] = $this->ci->panacea_common_model->get_last_screening_update($date,$screening_duration);

		$this->data['message'] = '';
		
		$this->data['today_date'] = date('Y-m-d');
		
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
		
		$this->data['total_active_req'] = $this->ci->panacea_common_model->get_all_active_request();
		$this->data['total_raised_req'] = $this->ci->panacea_common_model->get_all_raised_request();
		
		$this->data ['news_feeds'] = $this->ci->panacea_common_model->get_all_news_feeds();
		$this->data['emergency_alerts'] = $this->ci->panacea_common_model->get_data_for_emergency_carousel($this->data['today_date']);

		//log_message("debug","to_dashboarddddddddddddd".print_r($this->data['news_feeds'],true));
		//log_message("debug","to_dashboarddddddddddddd_dataaaaaaaaaaaaaa".print_r($this->data,true));
		return $this->data;

	}

	function update_request_pie_old_dash($date = FALSE,$request_pie_span  = "Monthly")
	{
	
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms_old_dash($date,$request_pie_span);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests_old_dash($date,$request_pie_span);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
	
		return json_encode($this->data);
	
	}

	function to_dashboard_device($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		/*$this->data['absent_report'] = json_encode(array(array("label"=>"report","value"=>53),array("label"=>"absent report","value"=>53)));
		$this->data['symptoms_report'] = json_encode(array(array("label"=>"report","value"=>53),array("label"=>"absent report","value"=>53)));
		$this->data['screening_report'] = json_encode(array(array("label"=>"report","value"=>53),array("label"=>"absent report","value"=>53)));
		$this->data['request_report'] = json_encode(array(array("label"=>"report","value"=>53),array("label"=>"absent report","value"=>53)));*/

		$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date);
		

		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);

		}else{
			$this->data['absent_report'] = 1;
			
		}
		

		
		$this->data['absent_report_schools_list'] = $this->ci->panacea_common_model->get_absent_pie_schools_data($date);

		return $this->data;
	}
	function to_dashboard_device____($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$pagenumber       = array();
		$page_data        = array();
		$sanitation_report_app = array();
		$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date);
		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
		}else{
			$this->data['absent_report'] = 1;
		}
		/* $this->data['absent_report'] = json_encode(array(array("label"=>"report","value"=>"53"),array("label"=>"absent report","value"=>"53")));
		$this->data['symptoms_report'] = json_encode(array(array("label"=>"report","value"=>"53"),array("label"=>"absent report","value"=>"53")));
		$this->data['screening_report'] = json_encode(array(array("label"=>"report","value"=>"53"),array("label"=>"absent report","value"=>"53")));
		$this->data['request_report'] = json_encode(array(array("label"=>"report","value"=>"53"),array("label"=>"absent report","value"=>"53"))); */
		
		//$this->data['absent_report'] = "";
		//$this->data['absent_report_schools_list'] = $this->ci->panacea_common_model->get_absent_pie_schools_data($date);
		
		//$this->data['sanitation_report_schools_list'] = $this->ci->panacea_common_model->get_sanitation_report_pie_schools_data($date);
		
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_duration);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
		/* $this->data['symptoms_report'] = ""; */
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_duration);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		/* $this->data['request_report'] = 1; */
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		/* $app_template = $this->ci->panacea_common_model->get_sanitation_report_app();
		
		foreach ($app_template as $pageno => $pages)
        {
		  	array_push($pagenumber,$pageno);
        }

     	$pagecount = count($pagenumber);

     	for($i=1;$i<=$pagecount;$i++)
	 	{
			 array_push($page_data,$app_template[$i]);
     	}

     	for($ii=0;$ii<$pagecount;$ii++)
     	{
             $pgno = $ii +1;
			 foreach($page_data[$ii] as $section => $index_array)
			 {
			    unset($index_array['dont_use_this_name']);
				$sanitation_report_app[$pgno][$section] = array();
				foreach($index_array as $index => $value)
	            {
				   $ele_name = str_replace(' ','_',$index);
				   $sec_name = str_replace(' ','_',$section);
				   $path = "page".$pgno."#".$sec_name."#".$ele_name;
				   switch ($value['type'])
		      	   {
		        		case 'radio':
						$options = array();
						$options_array = $value['options'];
						foreach($options_array as $index_no => $val)
						{
						  array_push($options,$val['label']);
						}
						break;
				   }
				   $opt = array('path'=>$path,'options'=>$options);
				   $ele = array($index=>$opt);
				   array_push($sanitation_report_app[$pgno][$section],$ele); 
				}
			 }
		}
		unset($sanitation_report_app[4]['Declaration Information']);
		
		$this->data['sanitation_report_obj'] = json_encode($sanitation_report_app);
		
        $chronic_ids = $this->ci->panacea_common_model->get_all_chronic_unique_ids_model();
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
        $this->data['last_screening_update'] = $this->ci->panacea_common_model->get_last_screening_update();
	
		$this->data['message'] = '';
		
		$this->data['today_date'] = date('Y-m-d');
		
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
		
		$this->data['total_active_req'] = $this->ci->panacea_common_model->get_all_active_request();
		$this->data['total_raised_req'] = $this->ci->panacea_common_model->get_all_raised_request();
		
		$this->data ['news_feeds'] = $this->ci->panacea_common_model->get_today_news_feeds($date); */
		//log_message("debug","to_dashboarddddddddddddd".print_r($this->data['screening_report'],true));
		return $this->data;

	}
	function to_dashboard_with_date($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly", $dt_name = "All", $school_name = "All",$request_pie_status = "All")
	{
		$pagenumber       = array();
		$page_data        = array();
		$sanitation_report_app = array();
		$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date, $dt_name, $school_name);
		
		if(!empty($absent_report))
		{		
			$this->data['absent_report'] = json_encode($absent_report);			
		}else
		{
			$this->data['absent_report'] = 1;
		}
		/*foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		
		if($count >= 0){
			$this->data['absent_report'] = json_encode($absent_report);
			
		}else{
			$this->data['absent_report'] = 1;
		}*/
		
		$this->data['absent_report_schools_list'] = $this->ci->panacea_common_model->get_absent_pie_schools_data($date);
		
		$this->data['sanitation_report_schools_list'] = $this->ci->panacea_common_model->get_sanitation_report_pie_schools_data($date);
		
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_duration, $dt_name, $school_name,$request_pie_status);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}

		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_duration, $dt_name, $school_name,$request_pie_status);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		//log_message('error','screening_report=====904'.print_r($screening_report,TRUE));
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		$app_template = $this->ci->panacea_common_model->get_sanitation_report_app();
		
		foreach ($app_template as $pageno => $pages)
		{
			array_push($pagenumber,$pageno);
		}

		$pagecount = count($pagenumber);

		for($i=1;$i<=$pagecount;$i++)
		{
			array_push($page_data,$app_template[$i]);
		}

		for($ii=0;$ii<$pagecount;$ii++)
		{
			$pgno = $ii +1;
			foreach($page_data[$ii] as $section => $index_array)
			{
				unset($index_array['dont_use_this_name']);
				$sanitation_report_app[$pgno][$section] = array();
				foreach($index_array as $index => $value)
				{
					$ele_name = str_replace(' ','_',$index);
					$sec_name = str_replace(' ','_',$section);
					$path = "page".$pgno."#".$sec_name."#".$ele_name;
					switch ($value['type'])
					{
						case 'radio':
						$options = array();
						$options_array = $value['options'];
						foreach($options_array as $index_no => $val)
						{
							array_push($options,$val['label']);
						}
						break;
					}
					$opt = array('path'=>$path,'options'=>$options);
					$ele = array($index=>$opt);
					array_push($sanitation_report_app[$pgno][$section],$ele); 
				}
			}
		}
		unset($sanitation_report_app[4]['Declaration Information']);
		
		$this->data['sanitation_report_obj'] = json_encode($sanitation_report_app);
		

		$chronic_ids = $this->ci->panacea_common_model->get_all_chronic_unique_ids_model();
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data ['news_feeds'] = json_encode($this->ci->panacea_common_model->get_all_news_feeds());

		return json_encode($this->data);

	}
	
	function update_request_pie($date = FALSE,$request_pie_span  = "Daily",$request_pie_status = "All" ,$student_type = false,$student_age = false)
	{

		/*if(isset($student_type) && !empty($student_type))
		{*/
			
			$count = 0;
			$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_pie_span,"All","All",$request_pie_status,$student_type,$student_age);
			
			foreach ($symptoms_report as $value){
				$count = $count + intval($value['value']);
			}
			if($count > 0){
				$this->data['symptoms_report'] = json_encode($symptoms_report);
			}else{
				$this->data['symptoms_report'] = 1;
			}

			$count = 0;
			$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span,"All","All",$request_pie_status,$student_type,$student_age);
			
			foreach ($request_report as $value){
				$count = $count + intval($value['value']);
			}
			if($count > 0){
				$this->data['request_report'] = json_encode($request_report);
			}else{
				$this->data['request_report'] = 1;
			}

			return json_encode($this->data);
		/*}else
		{
			
			$count = 0;
			$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_pie_span,"All","All",$request_pie_status);
			foreach ($symptoms_report as $value){
				$count = $count + intval($value['value']);
			}
			if($count > 0){
				$this->data['symptoms_report'] = json_encode($symptoms_report);
			}else{
				$this->data['symptoms_report'] = 1;
			}

			$count = 0;
			$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span,"All","All",$request_pie_status);
			foreach ($request_report as $value){
				$count = $count + intval($value['value']);
			}
			if($count > 0){
				$this->data['request_report'] = json_encode($request_report);
			}else{
				$this->data['request_report'] = 1;
			}

			return json_encode($this->data);
		}	*/	

	}
	
	function update_screening_pie($date = FALSE,$screening_pie_span  = "Yearly")
	{

		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_pie_span);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}

		return json_encode($this->data);

	}
	
	function update_sanitation_infrastructure_pie()
	{
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_sanitation_infrastructure_data();
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}

		return json_encode($this->data);
	}
	
	function update_sanitation_report_pie()
	{
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_sanitation_report_data($date,$screening_pie_span);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}

		return json_encode($this->data);
	}
	
	function panacea_imports_diagnostic()
	{

		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		return $this->data;
	}
	
	function import_diagnostic($post)
	{

		$dt_name   = $post['dt_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("diagnostic center code","diagnostic center name","phone number","mobile number","address");

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile number")
						{
							$data_value = substr($data_value,0,10);
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;

				for($j=2;$j<count($arr_data)+2;$j++){
					$data = array(
						"dt_name" => $dt_name,
						"diagnostic_code" => $arr_data[$j]['diagnostic center code'],
						"diagnostic_name" => $arr_data[$j]['diagnostic center name'],
						"diagnostic_ph" => $arr_data[$j]['phone number'],
						"diagnostic_mob" => $arr_data[$j]['mobile number'],
						"diagnostic_addr" => $arr_data[$j]['address'],);

					$this->ci->panacea_common_model->create_diagnostic($data);

					$count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				//redirect('panacea_mgmt/panacea_mgmt_diagnostic');
				return "redirect_to_diagnostic_fn";
				
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}
	
	function panacea_imports_hospital()
	{

		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		return $this->data;
	}
	
	function import_hospital($post)
	{

		$dt_name   = $post['dt_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital code","hospital name","phone number","mobile number","address");

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile number")
						{
							$data_value = substr($data_value,0,10);
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;

				for($j=2;$j<count($arr_data)+2;$j++){
					$data = array(
						"dt_name" => $dt_name,
						"hospital_code" => $arr_data[$j]['hospital code'],
						"hospital_name" => $arr_data[$j]['hospital name'],
						"hospital_ph" => $arr_data[$j]['phone number'],
						"hospital_mob" => $arr_data[$j]['mobile number'],
						"hospital_addr" => $arr_data[$j]['address'],);

					$this->ci->panacea_common_model->create_hospital($data);

					$count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				//redirect('panacea_mgmt/panacea_mgmt_hospitals');
				return "redirect_to_hospital_fn";
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_hospital', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_hospital', $this->data);
			return $this->data;
		}
	}
	
	function panacea_imports_school()
	{	
		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		return $this->data;
	}
	
	function import_school($post)
	{	
		$dt_name   = $post['dt_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("school code","school name","email", "phone number","mobile number","address", "contact person name", "password");

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("F1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("G1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile number")
						{
							$data_value = substr($data_value,0,10);
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;
				$school_insert_count = 0;

				for($j=2;$j<count($arr_data)+2;$j++){
					$data = array(
						"dt_name" => $dt_name,
						"school_code" => $arr_data[$j]['school code'],
						"school_name" => $arr_data[$j]['school name'],
						"school_addr" => $arr_data[$j]['address'],
						"school_email" => $arr_data[$j]['email'],
						"school_password" => $arr_data[$j]['password'],
						"school_ph" => $arr_data[$j]['phone number'],
						"school_mob" => $arr_data[$j]['mobile number'],
						"contact_person_name" => $arr_data[$j]['contact person name']);

					$insert_success = $this->ci->panacea_common_model->create_school($data);

					$count++;
					if($insert_success)
						$school_insert_count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);
				session_start();
				$_SESSION['updated_message'] = "Successfully imported ".$school_insert_count." school document(s).";

				//redirect('panacea_mgmt/panacea_mgmt_schools');
				return "redirect_to_school_fn";
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
			return $this->data;
		}
	}

	function swaero_user_imports()
	{	
		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->power_of_ten_common_model->get_all_district();

		return $this->data;
	}
	
	function import_users($post)
	{	
		$dt_name   = $post['dt_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("school code","school name","email", "phone number","mobile number","address", "contact person name", "password");

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("F1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("G1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile number")
						{
							$data_value = substr($data_value,0,10);
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;
				$school_insert_count = 0;

				for($j=2;$j<count($arr_data)+2;$j++){
					$data = array(
						"dt_name" => $dt_name,
						"school_code" => $arr_data[$j]['school code'],
						"school_name" => $arr_data[$j]['school name'],
						"school_addr" => $arr_data[$j]['address'],
						"school_email" => $arr_data[$j]['email'],
						"school_password" => $arr_data[$j]['password'],
						"school_ph" => $arr_data[$j]['phone number'],
						"school_mob" => $arr_data[$j]['mobile number'],
						"contact_person_name" => $arr_data[$j]['contact person name']);

					$insert_success = $this->ci->power_of_ten_common_model->create_swaero_users($data);

					$count++;
					if($insert_success)
						$school_insert_count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);
				session_start();
				$_SESSION['updated_message'] = "Successfully imported ".$school_insert_count." school document(s).";

				//redirect('panacea_mgmt/panacea_mgmt_schools');
				return "redirect_to_school_fn";
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->power_of_ten_common_model->get_all_district();
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->power_of_ten_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_school', $this->data);
			return $this->data;
		}
	}
	
	function import_health_supervisors()
	{	
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("school code","healthsupervisors name","email", "phone number","mobile number","address","password");

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("F1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile number")
						{
							$data_value = substr($data_value,0,10);
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;
				$hs_insert_count = 0;
				for($j=2;$j<count($arr_data)+2;$j++){

					$data = array(
						"school_code" => $arr_data[$j]['school code'],
						"health_supervisors_name" => $arr_data[$j]['healthsupervisors name'],
						"health_supervisors_mob" => $arr_data[$j]['mobile number'],
						"health_supervisors_ph" => $arr_data[$j]['phone number'],
						"health_supervisors_email" => $arr_data[$j]['email'],
						"health_supervisors_addr" => $arr_data[$j]['address'],
						"health_supervisors_password" => $arr_data[$j]['password'],);

					$insert_success = $this->ci->panacea_common_model->create_health_supervisors($data);

					$count++;
					if($insert_success)
						$hs_insert_count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				session_start();
				$_SESSION['updated_message'] = "Successfully imported ".$hs_insert_count." health supervisor document(s).";

				//redirect('panacea_mgmt/panacea_mgmt_health_supervisors');
				return "redirect_to_hs_fn";
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['error'] = "excel_column_check_fail";
				
				//$this->_render_page('panacea_admins/panacea_imports_health_supervisors', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_health_supervisors', $this->data);
			return $this->data;
		}
	}
	
	function import_students($post)
	{
		ini_set ( 'memory_limit', '2G' );
		$import_type   = $post['import_type'];
		$school_name   = $post['school_name'];
		
		$uploaddir = EXCEL;
	
		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;
			
		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');
	
			
		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];
	
			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format
					
			if($import_type == "personal_info")
			{

				$row_value = 0;
				$arr_count = 0;
				$header_array = array("ad no", "student name", "mobile number", "date of birth", "school name", "class","section", "father name", "district","hospital unique id");

				$check_col_array = [];

				$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				foreach ($cellIterator as $cell) {
					echo $cell->getValue();
					array_push($check_col_array,strtolower($cell->getValue()));
				}
				//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

				$check = array_diff($header_array,$check_col_array);
				//log_message('debug','chkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'.print_r($check,true));


				if (count($check)==0) {
					$arr_data = [];
					$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
					//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

					for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
						$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(false);
						$header_row = 0;
						foreach ($cellIterator as $cell) {

							$temp_data = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");

							$data_value = preg_replace('~\x{00a0}~siu',' ',$temp_data);

							if($check_col_array[$header_row] == "mobile number")
							{
								$data_value = substr($data_value,0,10);
							}
							if($check_col_array[$header_row] == "student name")
							{
								$student_name = $data_value;
							}

							if($check_col_array[$header_row] == "class")
							{
								$age = "";
								$class = $data_value;


								if($class == "5")
								{
									$age = 10;
								}else if($class == "6")
								{
									$age = 11;
								}else if($class == "7")
								{
									$age = 12;
								}else if($class == "8")
								{
									$age = 13;
								}else if($class == "9")
								{
									$age = 14;
								}else if($class == "10")
								{
									$age = 15;
								}elseif ($class == "11") 
								{
									$age = 16;
								}elseif($class == "12")
								{
									$age = 17;
								}elseif($class == "Degree 1st")
								{
									$age = 18;
								}elseif($class == "Degree 2nd")
								{
									$age = 19;
								}elseif($class == "Degree 3rd")
								{
									$age = 20;
								}

								if($age == "")
								{
									
									unlink($updata['upload_data']['full_path']);
									$colCoordinate = $cell->getCoordinate();

									session_start();
									$_SESSION['request_message'] = "Update falied due to following error in excel sheet: Class value it Maybe Empty or Wrong Class for this Child ".$student_name;


									//session_start();
									$message = "";
									if(!empty($_SESSION['request_message']))
										$message = $_SESSION['request_message'];
									// rest of your code
									$this->data['message'] = $message;
									
									$this->data['error'] = "excel_sheet_faild";
									
									return $this->data;
								}
							}
							if($check_col_array[$header_row] == "date of birth")
							{
								try {
									//$date = new DateTime('2000-01-01');

									//log_message('debug','11111111111111111111111111111111111111111.'.print_r($data_value,true));
									if(isset($data_value) || $data_value == "" || $data_value == " ")
									{

									}else{
										$date = new DateTime($data_value);
										$data_value= $date->format('Y-m-d');
									}
									
									//log_message('debug','2222222222222222222222222222222222222222.'.print_r($data_value,true));


								} catch (Exception $e) {
									//echo $e->getMessage();
									//exit(1);
									unlink($updata['upload_data']['full_path']);
									$colCoordinate = $cell->getCoordinate();

									session_start();
									$_SESSION['request_message'] = "Update falied due to following error in excel sheet: value in ".$colCoordinate." seems to of different type that of <strong>text</strong>.";


									//session_start();
									$message = "";
									if(!empty($_SESSION['request_message']))
										$message = $_SESSION['request_message'];
									// rest of your code
									$this->data['message'] = $message;
									
									$this->data['error'] = "excel_sheet_faild";
									
									return $this->data;
									//redirect('panacea_common_lib/panacea_reports_students_redirect');

								}
							}


							$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
							$header_row ++;
						}
					}

							$phpExcel = new PHPExcel;

						// Setting font to Arial Black

							$phpExcel->getDefaultStyle()->getFont()->setName('Cambria');

						// Setting font size to 14

							$phpExcel->getDefaultStyle()->getFont()->setSize(12);

						//Setting description, creator and title

							$phpExcel ->getProperties()->setTitle("Vendor list");

							$phpExcel ->getProperties()->setCreator("Robert");

							$phpExcel ->getProperties()->setDescription("Excel SpreadSheet in PHP");

						// Creating PHPExcel spreadsheet writer object

						// We will create xlsx file (Excel 2007 and above)

							$writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");

						// When creating the writer object, the first sheet is also created

						// We will get the already created sheet

							$sheet = $phpExcel ->getActiveSheet();
						 //log_message("debug","sheeeeettttttttttt".print_r($sheet,true));
						// Setting title of the sheet

							$sheet->setTitle('Errors list');

						// Creating spreadsheet header

						//$sheet ->getCell('A1')->setValue('Hospital Unique ID')->getStyle('A1');
						//$sheet ->setCellValue('A1', 'hospital uniqid id')->getStyle('A1');
						 //$sheet ->setCellValue('B1', 'mobile number')->getStyle('B1');

						 //$sheet ->getCell('A1')->setValue('hospital uniqid id');
						// $sheet ->getCell('B1')->setValue('Description');
							$sheet->setCellValue('A1', 'Hospital uniqid id')->getStyle('A1');
						//$sheet->setCellValue('B1', 'Status Passed')->getStyle('B1');
						//$sheet->setCellValue('C1', 'Status Failed')->getStyle('C1');
							$sheet->setCellValue('B1', 'Student Name')->getStyle('B1');
							$sheet->setCellValue('C1', 'Class')->getStyle('C1');
							$sheet->setCellValue('D1', 'Section')->getStyle('D1');
							$sheet->setCellValue('E1', 'School Name')->getStyle('E1');
							$sheet->setCellValue('F1', 'Mobile Number')->getStyle('F1');
							$sheet->setCellValue('G1', 'Date Of Birth')->getStyle('G1');
							$sheet->setCellValue('H1', 'Father Name')->getStyle('H1');

						//$sheet->setCellValue('D1', 'Student Name')->getStyle('D1');
						//$sheet->setCellValue('A1', 'hospital uniqid id')->getStyle('A1');
						//$sheet->insertNewColumnAfter('A1', $unique_id);
						//$sheet->getActiveSheet()->setCellValue('A',$unique_id);

						//$sheet->setCellValue('B1', 'hospital uniqid id')->getStyle('B1');
						//$sheet ->getCell('C1')->setValue('Cost');

						// Making headers text bold and larger

							$sheet->getStyle('A1:I1')->getFont()->setBold(false)->setSize(12);

						// Insert product data

						// Autosize the columns

							$sheet->getColumnDimension('A')->setAutoSize(true); 

					


					$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
					if($gender_info == "B")
					{
						$gender = "Male";
					}else if($gender_info == "G")
					{
						$gender = "Female";
					}

					$dist_name = explode(",", $school_name);

					$last_unique_id = $this->ci->panacea_common_model->check_unique_id_is_exists($school_name);
					/* if(preg_match('/STAFF/i', $last_unique_id))
					{
						echo print_r($last_unique_id, true); exit();
						//log_message('error',"last_uniqueID for========12282".print_r($last_uniqueID,true));
					}else
					{
						$unique_id = explode("_", $last_unique_id);
						$latest_unique_id = $unique_id[0]."_".$unique_id[1]."_";
						$num_length = strlen((string)$unique_id[2]);
						if($num_length >= 4)
						{
							$hightest_id =  $unique_id[2];
						}else
						{
							$hightest_id = 999;
						}
					}*/
					
					$unique_id = explode("_", $last_unique_id);
					$latest_unique_id = $unique_id[0]."_".$unique_id[1]."_";
						

					$doc_data = array();
					$form_data = array();
					$count = 0;
					$insert_count = 0;
					$inc_unique = $unique_id[2]+1;
					//echo print_r($inc_unique, true); exit();
					//$inc_unique = 1000+1;
					for($j=2;$j<count($arr_data)+2;$j++)
					{
						
						$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $latest_unique_id.$inc_unique;
						$doc_data['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Personal Information']['Photo'] = "";
						$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['date of birth'];
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '91';
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['mobile number'];
						$doc_data['widget_data']['page1']['Personal Information']['Gender'] = (isset($gender)) ? $gender : "CO-Education";
						$doc_data['widget_data']['page1']['Personal Information']['Age'] = ($age) ? $age : "";
						$doc_data['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['ad no'];
						$doc_data['widget_data']['page2']['Personal Information']['Class'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page2']['Personal Information']['District'] = ($dist_name[1]) ? $dist_name[1] : "";
						$doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($school_name) ? $school_name : "";
						$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = $arr_data[$j]['father name'];
						$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = '';
						$doc_data['widget_data']['page3'] = [];
						$doc_data['widget_data']['page4'] = [];
						$doc_data['widget_data']['page5'] = [];
						$doc_data['widget_data']['page6'] = [];
						$doc_data['widget_data']['page7'] = [];
						$doc_data['widget_data']['page8'] = [];
						$doc_data['widget_data']['page9'] = [];

						$doc_properties['doc_id'] = get_unique_id();
						$doc_properties['status'] = 1;
						$doc_properties['_version'] = 1;
						$doc_properties['doc_owner'] = "PANACEA";
						$doc_properties['unique_id'] = '';
						$doc_properties['doc_flow'] = "new";

						$history['last_stage']['current_stage'] = "stage1";
						$history['last_stage']['approval'] = "true";
						$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$history['last_stage']['time'] = date("Y-m-d H:i:s");

						//$this->panacea_mgmt_model->create_health_supervisors($data);

						//log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiimmmmmmmmmmmmmmmmm.'.print_r($doc_data,true));
						
						$this->ci->panacea_common_model->insert_student_data($doc_data,$history,$doc_properties);

						$sheet->setCellValue('A'.$j,$latest_unique_id.$inc_unique);
						$sheet->setCellValue('B'.$j,$arr_data[$j]['student name']);
						$sheet->setCellValue('C'.$j,$arr_data[$j]['class']);
						$sheet->setCellValue('D'.$j,$arr_data[$j]['section']);
						$sheet->setCellValue('E'.$j,$school_name);
						$sheet->setCellValue('F'.$j,$arr_data[$j]['mobile number']);
						$sheet->setCellValue('G'.$j,$arr_data[$j]['date of birth']);
						$sheet->setCellValue('H'.$j,$arr_data[$j]['father name']);

						$insert_count++;
						$inc_unique++;
						$count++;
					}

					 

					//===============================================

					unlink($updata['upload_data']['full_path']);
					
					session_start();
					$_SESSION['updated_message'] = "Successfully inserted ".$insert_count." student(s) document.";

					header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
					header("Content-Disposition: attachment; filename=\"unique_ids_list.xls\"");
					header("Cache-Control: max-age=0");
					ob_end_clean(); 



					$writer->save("php://output");

					//redirect('panacea_mgmt/panacea_reports_students');
					return "redirect_to_student_fn";
				}else{
					unlink($updata['upload_data']['full_path']);

					$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
					$this->data['error'] = "excel_column_check_fail";

					//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
					return $this->data;

				}
			}else if($import_type == "full_doc"){
				$row_value = 0;
				$arr_count = 0;
				$header_array = array("hospital unique id", "student name", "mobile number", "date of birth", "ad no", "school name", "class","section", "father name", "date of exam", "district", "height", "weight", "pulse", "b p", "blood group", 'h b', 'ortho', 'advice', 'doctor check-up-description', 'postural', 'check the box if normal else describe abnormalities', 'defects at birth', 'deficencies', 'childhood diseases', 'n a d', 'without glasses-right', 'with glasses-left', 'with glasses-right', 'without glasses-right', 'colour blindness-right', 'colour blindness-left', 'vision screening-description', 'vision screening-referral made', 'auditory screening-right', 'auditory screening-left', 'speech screening', 'auditory screening-description', 'auditory screening-referral made', 'd d and disablity', 'oral hygiene', 'carious teeth' , 'flourosis' , 'orthodontic treatment', 'indication for extraction' , 'dental check-up-result', 'dental check-up-referral made');

				$check_col_array = [];

				$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				foreach ($cellIterator as $cell) {
					//echo $cell->getValue();
					array_push($check_col_array,strtolower($cell->getValue()));
				}
				//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

				$check = array_diff($header_array,$check_col_array);
				//log_message('debug','chkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'.print_r($check,true));


				if (count($check)==0) {
					$arr_data = [];
					$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
					//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

					for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
						$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(false);
						$header_row = 0;
						foreach ($cellIterator as $cell) {

							$data_value = $cell->getValue();

							if($check_col_array[$header_row] == "mobile number")
							{
								$data_value = substr($data_value,0,10);
							}

							if($check_col_array[$header_row] == "date of birth")
							{
								try {
									//$date = new DateTime('2000-01-01');

									//log_message('debug','11111111111111111111111111111111111111111.'.print_r($data_value,true));
									if(isset($data_value) || $data_value == "" || $data_value == " "){
									}else{
										$date = new DateTime($data_value);
										$data_value= $date->format('Y-m-d');
									}
									//log_message('debug','2222222222222222222222222222222222222222.'.print_r($data_value,true));


								} catch (Exception $e) {
									//echo $e->getMessage();
									//exit(1);
									unlink($updata['upload_data']['full_path']);
									$colCoordinate = $cell->getCoordinate();

									session_start();
									$_SESSION['request_message'] = "Update falied due to following error in excel sheet: value in ".$colCoordinate." seems to of different type that of <strong>text</strong>.";


									//session_start();
									$message = "";
									if(!empty($_SESSION['request_message']))
										$message = $_SESSION['request_message'];
									// rest of your code
									$this->data['message'] = $message;
									
									$this->data['error'] = "excel_sheet_faild";
									
									return $this->data;
									//redirect('panacea_common_lib/panacea_reports_students_redirect');

								}
							}


							$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
							$header_row ++;
						}
					}
					//log_message('debug','arrrrrrdtatattatattatatattatattatatat.'.print_r($arr_data,true));

					$doc_data = array();
					$form_data = array();
					$count = 0;

					for($j=2;$j<count($arr_data)+2;$j++){

						$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$j]['hospital unique id']) ? $arr_data[$j]['hospital unique id'] : "" ;
						$doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$j]['student name']) ? $arr_data[$j]['student name'] : "" ;
						$doc_data['widget_data']['page1']['Personal Information']['Photo'] = "";
						$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$j]['date of birth']) ? $arr_data[$j]['date of birth'] : "" ;
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '91';
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = ($arr_data[$j]['mobile number']) ? $arr_data[$j]['mobile number'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['AD No'] = ($arr_data[$j]['ad no']) ? (String) $arr_data[$j]['ad no'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$j]['class']) ? $arr_data[$j]['class'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$j]['section']) ? $arr_data[$j]['section'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$j]['district']) ? $arr_data[$j]['district'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$j]['school name']) ? $arr_data[$j]['school name'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$j]['father name']) ? $arr_data[$j]['father name'] : "" ;
						$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$j]['date of exam']) ? $arr_data[$j]['date of exam'] : "";

						$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$j]['height']) ? $arr_data[$j]['height'] : "";
						$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$j]['weight']) ? $arr_data[$j]['weight'] : "";
						$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = '';
						$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$j]['pulse']) ? $arr_data[$j]['pulse'] : "";
						$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$j]['b p']) ? $arr_data[$j]['b p'] : "";
						$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$j]['h b']) ? $arr_data[$j]['h b'] : "";
						$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$j]['blood group']) ? $arr_data[$j]['blood group'] : "";

						$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$j]['check the box if normal else describe abnormalities']) ? explode(',',$arr_data[$j]['check the box if normal else describe abnormalities']) : "";
						$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$j]['ortho']) ? explode(',',$arr_data[$j]['ortho']) : "";
						$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$j]['postural']) ? explode(',',$arr_data[$j]['postural']) : "";
						$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$j]['doctor check-up-description']) ? $arr_data[$j]['doctor check-up-description'] : "";
						$doc_data['widget_data']['page4']['Doctor Check Up']['Advice'] = ($arr_data[$j]['advice']) ? $arr_data[$j]['advice'] : "";

						$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$j]['defects at birth']) ? explode(',',$arr_data[$j]['defects at birth']) : "";
						$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$j]['deficencies']) ? explode(',',$arr_data[$j]['deficencies']) : "";
						$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$j]['childhood diseases']) ? explode(',',$arr_data[$j]['childhood diseases']) : "";
						$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$j]['n a d']) ? explode(',',$arr_data[$j]['n a d']) : "";

						$doc_data['widget_data']['page6']['Screenings'] = [];
						$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$j]['without glasses-right']) ? $arr_data[$j]['without glasses-right'] : "",
							'Left' => ($arr_data[$j]['without glasses-left']) ? $arr_data[$j]['without glasses-right'] : "");
						$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$j]['with glasses-right']) ? $arr_data[$j]['with glasses-right'] : "",
							'Left' => ($arr_data[$j]['with glasses-left']) ? $arr_data[$j]['with glasses-left'] : "");

						$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$j]['auditory screening-right']) ? $arr_data[$j]['auditory screening-right'] : "",
							'Left' => ($arr_data[$j]['auditory screening-left']) ? $arr_data[$j]['auditory screening-left'] : "",
							'Speech Screening' => ($arr_data[$j]['speech screening']) ? explode(',',$arr_data[$j]['speech screening']) : "",
							'D D and disability' => ($arr_data[$j]['d d and disablity']) ? explode(',',$arr_data[$j]['d d and disablity']) : "",
							'Referral Made' => ($arr_data[$j]['vision screening-referral made']) ? explode(',',$arr_data[$j]['vision screening-referral made']) : "",
							'Description' => ($arr_data[$j]['vision screening-description']) ? $arr_data[$j]['vision screening-description'] : "");

						$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$j]['auditory screening-right']) ? $arr_data[$j]['auditory screening-right'] : "",
							'Left' => ($arr_data[$j]['auditory screening-left']) ? $arr_data[$j]['auditory screening-left'] : "",
							'Referral Made' => ($arr_data[$j]['auditory screening-referral made']) ? explode(',',$arr_data[$j]['auditory screening-referral made']) : "",
							'Description' => ($arr_data[$j]['auditory screening-description']) ? $arr_data[$j]['auditory screening-description'] : "");


						$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$j]['oral hygiene']) ? $arr_data[$j]['oral hygiene'] : "",
							'Carious Teeth' => ($arr_data[$j]['carious teeth']) ? $arr_data[$j]['carious teeth'] : "",
							'Flourosis' => ($arr_data[$j]['flourosis']) ? $arr_data[$j]['flourosis'] : "",
							'Orthodontic Treatment' => ($arr_data[$j]['orthodontic treatment']) ? $arr_data[$j]['orthodontic treatment'] : "",
							'Indication for extraction' => ($arr_data[$j]['indication for extraction']) ? $arr_data[$j]['indication for extraction'] : "",
							'Referral Made' => ($arr_data[$j]['dental check-up-referral made']) ? explode(',',$arr_data[$j]['dental check-up-referral made']) : "",
							'Result' => ($arr_data[$j]['dental check-up-result']) ? $arr_data[$j]['dental check-up-result'] : "");

						$doc_properties['doc_id'] = get_unique_id();
						$doc_properties['status'] = 1;
						$doc_properties['_version'] = 1;
						$doc_properties['doc_owner'] = "PANACEA";
						$doc_properties['unique_id'] = '';
						$doc_properties['doc_flow'] = "new";

						$history['last_stage']['current_stage'] = "stage1";
						$history['last_stage']['approval'] = "true";
						$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$history['last_stage']['time'] = date("Y-m-d H:i:s");

						//$this->panacea_mgmt_model->create_health_supervisors($data);

						$this->ci->panacea_mgmt_model->insert_student_data($doc_data,$history,$doc_properties);

						$count++;
					}


					//===============================================

					unlink($updata['upload_data']['full_path']);

					//redirect('panacea_mgmt/panacea_reports_students');
					return "redirect_to_student_fn";
				}else{
					unlink($updata['upload_data']['full_path']);

					$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
					
					$this->data['error'] = "excel_column_check_fail";

					//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
					return $this->data;

				}
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
			return $this->data;
		}
	}
	
	function update_students()
	{

		$uploaddir = EXCEL;

		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		
		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');

		//log_message('debug','fileeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($_POST,true));
		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$row_value = 0;
			$arr_count = 0;
			$header_array = array("hospital unique id");

			$check_col_array = [];

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = in_array("hospital unique id",$check_col_array);

			//log_message('debug','chkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'.print_r($check,true));


			if ($check) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));

				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$data_value = $cell->getValue();

						if($check_col_array[$header_row] == "mobile")
						{
							$data_value = substr($data_value,0,10);
						}

						if($check_col_array[$header_row] == "date of birth")
						{
							try {
								//$date = new DateTime('2000-01-01');

								//log_message('debug','11111111111111111111111111111111111111111.'.print_r($data_value,true));
								if(isset($data_value) || $data_value == "" || $data_value == " "){
								}else{
									$date = new DateTime($data_value);
									$data_value= $date->format('Y-m-d');
								}
								//log_message('debug','2222222222222222222222222222222222222222.'.print_r($data_value,true));


							} catch (Exception $e) {
								//echo $e->getMessage();
									//exit(1);
								unlink($updata['upload_data']['full_path']);
								$colCoordinate = $cell->getCoordinate();

								session_start();
								$_SESSION['request_message'] = "Update falied due to following error in excel sheet: value in ".$colCoordinate." seems to of different type that of <strong>text</strong>.";


									//session_start();
								$message = "";
								if(!empty($_SESSION['request_message']))
									$message = $_SESSION['request_message'];
									// rest of your code
								$this->data['message'] = $message;

								$this->data['error'] = "excel_sheet_faild";

								return $this->data;
									//redirect('panacea_common_lib/panacea_reports_students_redirect');

							}
						}


						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;

				$update_count = 0;

				for($j=2;$j<count($arr_data)+2;$j++){

					$unique_id = $arr_data[$j]['hospital unique id'];
					$doc = $this->ci->panacea_common_model->get_students_uid($unique_id);
					//echo print_r($doc,true)."Unique";exit();
					//log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddd.'.print_r($doc,true));
					if($doc){

						$doc_id = $doc['_id'];

						if(isset($arr_data[$j]['student name']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['student name'];

						if(isset($arr_data[$j]['date of birth']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['date of birth'];

						if(isset($arr_data[$j]['mobile']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['mobile'];

						if(isset($arr_data[$j]['ad no']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['ad no'];
						//echo print_r($arr_data[$j]['class'],true);exit();
						if(isset($arr_data[$j]['class']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Class'] = (String) $arr_data[$j]['class'];

						if(isset($arr_data[$j]['section']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['section'];

						if(isset($arr_data[$j]['district']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = $arr_data[$j]['district'];

						if(isset($arr_data[$j]['school name']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = $arr_data[$j]['school name'];

						if(isset($arr_data[$j]['father name']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'] = $arr_data[$j]['father name'];


						/* $doc['history']['last_stage']['current_stage'] = "stage1";
						$doc['history']['last_stage']['approval'] = "true";
						$doc['history']['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$doc['history']['last_stage']['time'] = date("Y-m-d H:i:s"); */

						//$this->panacea_mgmt_model->create_health_supervisors($data);
						//log_message('debug','ppppppppppppppppppppppppppppppppppppppppppppppppppppp.'.print_r($doc,true));
						$this->ci->panacea_common_model->update_student_data($doc,$doc_id);
						$update_count++;
					}

					$count++;
				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				session_start();
				$_SESSION['updated_message'] = "Successfully updated ".$update_count." student(s) document.";

				//redirect('panacea_mgmt/panacea_reports_students');
				return "redirect_to_student_fn";
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file do not have not hospital unique id";
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
			return $this->data;
		}
	}
	
	public function panacea_reports_students_filter()
	{
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		$total_rows = $this->ci->panacea_common_model->studentscount();
		$this->data['studentscount'] = $total_rows;

		//$this->data = "";
		return $this->data;
	}
	
	public function generate_excel_for_absent_pie($date, $dt_name = "All", $school_name = "All")
	{
		//load the excel library
		$this->ci->load->library('excel');
		
		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Vikas Singh Chouhan");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Attendance Report");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREIS-Attendance Report");
		$objPHPExcel->getProperties()->setDescription("Daily attendance report of TSWREIS.");
		
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Final Report");
		
		$objWorkSheet->getRowDimension(1)->setRowHeight(44);
		
		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));
		
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'name'  => 'Calibri'),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'DCE6F1') ));
		
		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'Total Number of Schools')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'Reported Schools')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Not Reported Schools')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Resting & Medicated')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'Referred to Hospital')
		->getStyle('F1')->applyFromArray($styleArray);
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		
		
		$dist_list = $this->ci->panacea_common_model->get_all_district($dt_name);
		
		$cell_index = 2;
		
		$grand_total_schools = 0;
		$grand_total_reported = 0;
		$grand_total_not_reported = 0;
		$grand_total_sick = 0;
		$grand_total_r2h = 0;
		
		$schools_array = array();
		$reported_schools_data = array();
		
		foreach ($dist_list as $dist){
			$objWorkSheet->setCellValue('A'.$cell_index, $dist['dt_name']);
			
			if($school_name == "All"){
				$schools_list = $this->ci->panacea_common_model->get_schools_by_dist_id($dist['_id']->{'$id'});
			}else{
				$schools_list = $this->ci->panacea_common_model->get_school_data_school_name($school_name);
			}


			$schools_array[$dist['dt_name']] = array();
			foreach ($schools_list as $school){
				$school_data['name'] = $school['school_name'];
				$school_data['code'] = $school['school_code'];
				$school_data['mob'] = $school['school_mob'];
				$school_data['contact_person_name'] = $school['contact_person_name'];

				array_push($schools_array[$dist['dt_name']], $school_data);
			}
			
			$total_schools = count($schools_array[$dist['dt_name']]);
			$objWorkSheet->setCellValue('B'.$cell_index, $total_schools);
			$grand_total_schools = $grand_total_schools + $total_schools;

			
			$reported_schools_data = $this->ci->panacea_common_model->get_reported_schools_count_by_dist_name($dist['dt_name'],$date);

			$objWorkSheet->setCellValue('C'.$cell_index, $reported_schools_data['count']);
			$grand_total_reported = $grand_total_reported + $reported_schools_data['count'];
			
			$not_reported = $total_schools-$reported_schools_data['count'];
			$objWorkSheet->setCellValue('D'.$cell_index, $not_reported);
			$grand_total_not_reported = $grand_total_not_reported + $not_reported;
			
			$objWorkSheet->setCellValue('E'.$cell_index, $reported_schools_data['sick']);
			$grand_total_sick = $grand_total_sick + $reported_schools_data['sick'];
			
			$objWorkSheet->setCellValue('F'.$cell_index, $reported_schools_data['r2h']);
			$grand_total_r2h = $grand_total_r2h + $reported_schools_data['r2h'];
			
			$cell_index ++;
		}
		
		$objWorkSheet->getRowDimension(40)->setRowHeight(44);
		//Write cells
		$objWorkSheet->setCellValue('A40', 'Grand Total')
		->getStyle('A40')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B40', $grand_total_schools)
		->getStyle('B40')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C40', $grand_total_reported)
		->getStyle('C40')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D40', $grand_total_not_reported)
		->getStyle('D40')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E40', $grand_total_sick)
		->getStyle('E40')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F40', $grand_total_r2h)
		->getStyle('F40')->applyFromArray($styleArray);
		
		$sheet = 1;
		foreach ($dist_list as $dist){
			log_message('debug','SCHEEEEEEEEEEEEEEEEEEEETTTTTTTTTTTTTTTTTTNUMBERRRRRRRRRRRRRRRRRRRRRRR__'.print_r($sheet,true));
			log_message('debug','DISTNAMEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE__'.print_r($dist['dt_name'],true));
			// Add new sheet
			$objWorkSheet = $objPHPExcel->createSheet($sheet); //Setting index when creating
			
			// Rename sheet
			$objWorkSheet->setTitle(strtoupper($dist['dt_name']));
			
			$objWorkSheet->getRowDimension(1)->setRowHeight(44);
			//Write cells
			$objWorkSheet->setCellValue('A1', 'School Name')
			->getStyle('A1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B1', 'Principal Name & Contact No.')
			->getStyle('B1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('C1', 'Health Supervisor Name & Contact No.')
			->getStyle('C1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('D1', 'Reported Schools')
			->getStyle('D1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('E1', 'Total No. of Students')
			->getStyle('E1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('F1', 'Attended')
			->getStyle('F1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('G1', 'Absent')
			->getStyle('G1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('H1', 'General Sick ( Attended Classes)')
			->getStyle('H1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('I1', 'Resting & Medicated ')
			->getStyle('I1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('J1', 'Referred to Hospital')
			->getStyle('J1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('K1', 'Student Name')
			->getStyle('K1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('L1', 'Class | Section')
			->getStyle('L1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('M1', 'Symptom')
			->getStyle('M1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('N1', 'Problem Description')
			->getStyle('N1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('O1', 'Request Status | Type')
			->getStyle('O1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('P1', 'Doctor Summary')
			->getStyle('P1')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('Q1', 'Prescription')
			->getStyle('Q1')->applyFromArray($styleArray);
			
			$schhols_in_dist = $schools_array[$dist['dt_name']];
			$cell_count = 2;
			foreach ($schhols_in_dist as $school){
				$objWorkSheet->setCellValue('A'.$cell_count, $school['name']);
				$objWorkSheet->setCellValue('B'.$cell_count, $school['contact_person_name'].' '.$school['mob']);
				$objWorkSheet->getStyle('B'.$cell_count)->getAlignment()->setWrapText(true);
				//log_message('debug','DISTNAMEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE__'.print_r($school,true));
				
				$hs_details = $this->ci->panacea_common_model->get_health_supervisors_school_id(strval($school['code']));
				//log_message('debug','hssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss---'.print_r($hs_details,true));
				if($hs_details){
					$objWorkSheet->setCellValue('C'.$cell_count, $hs_details['hs_name'].' '.$hs_details['hs_mob']);
					$objWorkSheet->getStyle('C'.$cell_count)->getAlignment()->setWrapText(true);
				}
				
				$school_data = $this->ci->panacea_common_model->get_absent_school_details($school['name'],$date);
				//log_message('debug','schoooooooooooooooooooooooooooooooooooooooo---'.print_r($school_data,true));
				if($school_data){
					//log_message('debug','inschooooooooooooooooooooooooofunnnnnnnnnnnn---'.print_r($school_data,true));
					$objWorkSheet->setCellValue('D'.$cell_count, "y");
					
					$objWorkSheet->setCellValue('E'.$cell_count, $school_data['doc_data']['widget_data']['page1']['Attendence Details']['Attended']+$school_data['doc_data']['widget_data']['page1']['Attendence Details']['Absent']);
					$objWorkSheet->setCellValue('F'.$cell_count, $school_data['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
					$objWorkSheet->setCellValue('G'.$cell_count, $school_data['doc_data']['widget_data']['page1']['Attendence Details']['Absent']);
					$objWorkSheet->setCellValue('H'.$cell_count, $school_data['doc_data']['widget_data']['page1']['Attendence Details']['Sick']);
					$objWorkSheet->setCellValue('I'.$cell_count, $school_data['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']);
					$objWorkSheet->setCellValue('J'.$cell_count, $school_data['doc_data']['widget_data']['page1']['Attendence Details']['R2H']);
					
					$request = $this->ci->panacea_common_model->get_request_by_school_name($school['name'],$date);
					log_message('debug','schoooooooooooooooooooooooooooooooooooooooooooo---'.print_r($school['name'],true));
					log_message('debug','reqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq---'.print_r($request,true));
					
					if($request){
						$req_number = 1;
						foreach ($request as $req){
							
							$objWorkSheet->setCellValue('K'.$cell_count, $req['stud_details']['name']);
							$objWorkSheet->setCellValue('L'.$cell_count, $req['stud_details']['class'].' | '.$req['stud_details']['section']);
							//$objWorkSheet->setCellValue('M'.$cell_count, implode(', ', $req['request']['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
							if(is_array($req['request']['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
								$objWorkSheet->setCellValue('M'.$cell_index, implode(', ',$req['request']['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
							}
							$objWorkSheet->setCellValue('N'.$cell_count, $req['request']['doc_data']['widget_data']['page2']['Problem Info']['Description']);
							$objWorkSheet->setCellValue('O'.$cell_count, $req['request']['doc_data']['widget_data']['page2']['Review Info']['Status'].' | '.$req['request']['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
							$objWorkSheet->setCellValue('P'.$cell_count, $req['request']['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
							$objWorkSheet->setCellValue('Q'.$cell_count, $req['request']['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
							if($req_number < count($request)){
								$cell_count++;
							}
							$req_number ++;
						}
					}
					
				}else{
					$objWorkSheet->setCellValue('D'.$cell_count, "rnr");
					$students_count = $this->ci->panacea_common_model->get_student_count_school_name($school['name']);
					$objWorkSheet->setCellValue('E'.$cell_count, $students_count);
				}
				
				$cell_count ++;
			}
			
			$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("I")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("J")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("K")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("L")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("M")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("N")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("O")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("P")->setAutoSize(true);
			$objWorkSheet->getColumnDimension("Q")->setAutoSize(true);
			
			$sheet ++;
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$file_save = BASEDIR.TENANT.'/'.$date."-TSWREIS-Attendance_Report.xlsx";
		$file_name = URLCustomer.$date."-TSWREIS-Attendance_Report.xlsx";
		$objWriter->save($file_save);
		//$this->secure_file_download($file_name);
		//unlink($file_name);
		return $file_name;
	}
	
	public function generate_excel_for_bmi_pie($date,$dt_name = "All", $school_name = "All")
	{
		$date = substr($date,0,-3);
		//echo print_r($date,true);
		//exit();
		//load the excel library
		$this->ci->load->library('excel');
		
		//create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Havik soft Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Naresh Reddy");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-BMI Report");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREIS-BMI Report");
		$objPHPExcel->getProperties()->setDescription("BMI report of TSWREIS");
		
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Final Report");
		
		$objWorkSheet->getRowDimension(1)->setRowHeight(44);
		
		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));
		
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'name'  => 'Calibri'),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'DCE6F1') ));
		
		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'School Name')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'Unique ID')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Height cms')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Weight kgs')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'BMI')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Month')
		->getStyle('G1')->applyFromArray($styleArray);
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		
		$data = $this->ci->panacea_common_model->get_reported_schools_bmi_count_by_dist_name($date,$dt_name,$school_name);
		log_message("debug","dataaaaaaaaaaaaa".print_r($data,true));
		//echo print_r(count($data),true);
		//exit();
		$i = 2;
		/* for($i=2;$i<count($data)+2;$i++)
					{
			$objWorkSheet->setCellValue('A'.$i,$data[0]['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']);
			$objWorkSheet->setCellValue('B'.$i,$data[0]['doc_data']['widget_data']['page1']['Student Details']['BMI_values'][0]['bmi']);
			
		} */

		foreach($data as $doc_data)
		{		
			$count_bmi = count($doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']);
			for($j = 0; $j <= $count_bmi; $j++)
			{
				log_message("debug","counttttttttttt".print_r($count_bmi,true));
				if($doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['month'] == $date)
				{
					log_message("debug","ifffffffffffffffff".print_r($count_bmi,true));

					if(isset($doc_data['doc_data']['school_details']['District']) && !empty($doc_data['doc_data']['school_details']['District']))
					{
						$objWorkSheet->setCellValue('A'.$i,$doc_data['doc_data']['school_details']['District']);
					}
					else{
						$objWorkSheet->setCellValue('A'.$i, "No District Name");
					}

					if(isset($doc_data['doc_data']['school_details']['School Name']) && !empty($doc_data['doc_data']['school_details']['School Name']))
					{
						$objWorkSheet->setCellValue('B'.$i,$doc_data['doc_data']['school_details']['School Name']);
					}
					else{
						$objWorkSheet->setCellValue('B'.$i, "No School Name");
					}

					$objWorkSheet->setCellValue('C'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']);

					$objWorkSheet->setCellValue('D'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['height']);
					$objWorkSheet->setCellValue('E'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['weight']);
					$objWorkSheet->setCellValue('F'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['bmi']);
					$objWorkSheet->setCellValue('G'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['month']);

					break;

				}

			}
			$i++;
			
		}
		//$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		//$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$file_save = BASEDIR.TENANT.'/'.$date."-TSWREIS-Attendance_Report.xlsx";
		$file_name = URLCustomer.$date."-TSWREIS-Attendance_Report.xlsx";
		$objWriter->save($file_save);
		//$this->secure_file_download($file_name);
		//unlink($file_name);
		return $file_name;
		
	}
	
	public function generate_excel_for_request_pie($date,$request_pie_span, $dt_name = "All", $school_name = "All")
	{
		//load the excel library
		$this->ci->load->library('excel');
		
		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Vikas Singh Chouhan");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Request Report");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREIS-Request Report");
		$objPHPExcel->getProperties()->setDescription("Request report of TSWREIS.");
		
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating
		
		// Rename sheet
		$objWorkSheet->setTitle("Final Report");
		
		$objWorkSheet->getRowDimension(1)->setRowHeight(44);
		
		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));
		
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'name'  => 'Calibri'),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'DCE6F1') ));
		
		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'Device Initiated')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'Web Initiated')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Prescribed')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Under Medication')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'Follow-up')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Cured')
		->getStyle('G1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H1', 'Normal Req')
		->getStyle('H1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I1', 'Emergency Req')
		->getStyle('I1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('J1', 'Chronic Req')
		->getStyle('J1')->applyFromArray($styleArray);
		
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("I")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("J")->setAutoSize(true);
		
		
		$dist_list = $this->ci->panacea_common_model->get_all_district($dt_name);
		
		//$pie_stage1_data =  $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span);
		
		//log_message('error','pie_stage1_data=====================2594'.print_r($pie_stage1_data,TRUE));
		
		
		$label = array('label' => 'Device Initiated');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Device Initiated'][strtolower($pie_data['label'])] = $pie_data['value']; 
		}
		
		$label = array('label' => 'Web Initiated');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Web Initiated'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Prescribed');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Prescribed'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Under Medication');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Under Medication'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Follow-up');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Follow-up'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Cured');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Cured'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Normal Req');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Normal Req'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Emergency Req');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Emergency Req'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$label = array('label' => 'Chronic Req');
		$data = json_encode($label);
		$pie_stage2_data =  $this->ci->panacea_common_model->drilldown_request_to_districts($data,$date,$request_pie_span,$dt_name,$school_name);
		foreach ($pie_stage2_data as $pie_data){
			$pie_stage2['Chronic Req'][strtolower($pie_data['label'])] = $pie_data['value'];
		}
		
		$cell_index = 2;

		foreach ($dist_list as $dist){
			$objWorkSheet->setCellValue('A'.$cell_index, $dist['dt_name']);
			
			if(isset($pie_stage2['Device Initiated'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('B'.$cell_index, $pie_stage2['Device Initiated'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('B'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Web Initiated'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('C'.$cell_index, $pie_stage2['Web Initiated'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('C'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Prescribed'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('D'.$cell_index, $pie_stage2['Prescribed'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('D'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Under Medication'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('E'.$cell_index, $pie_stage2['Under Medication'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('E'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Follow-up'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('F'.$cell_index, $pie_stage2['Follow-up'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('F'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Cured'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('G'.$cell_index, $pie_stage2['Cured'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('G'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Normal Req'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('H'.$cell_index, $pie_stage2['Normal Req'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('H'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Emergency Req'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('I'.$cell_index, $pie_stage2['Emergency Req'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('I'.$cell_index, 0);
			}
			
			if(isset($pie_stage2['Chronic Req'][strtolower($dist['dt_name'])])){
				$objWorkSheet->setCellValue('J'.$cell_index, $pie_stage2['Chronic Req'][strtolower($dist['dt_name'])]);
			}else{
				$objWorkSheet->setCellValue('J'.$cell_index, 0);
			}
			
			$cell_index ++;
		}
		
		//$pie_stage1_data =  $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span);
		if($dt_name == 'All'){
			$device_init = 0;
			if(isset($pie_stage2['Device Initiated'])){
				foreach($pie_stage2['Device Initiated'] as $dist){
					$device_init = $device_init + $dist;
				}
			}
			
			$web_init = 0;
			if(isset($pie_stage2['Web Initiated'])){
				foreach($pie_stage2['Web Initiated'] as $dist){
					$web_init = $web_init + $dist;
				}
			}
			
			$prescribed = 0;
			if(isset($pie_stage2['Prescribed'])){
				foreach($pie_stage2['Prescribed'] as $dist){
					$prescribed = $prescribed + $dist;
				}
			}
			
			$under_med = 0;
			if(isset($pie_stage2['Under Medication'])){
				foreach($pie_stage2['Under Medication'] as $dist){
					$under_med = $under_med + $dist;
				}
			}
			
			$follow = 0;
			if(isset($pie_stage2['Follow-up'])){
				foreach($pie_stage2['Follow-up'] as $dist){
					$follow = $follow + $dist;
				}
			}
			
			$cured = 0;
			if(isset($pie_stage2['Cured'])){
				foreach($pie_stage2['Cured'] as $dist){
					$cured = $cured + $dist;
				}
			}
			
			$normal = 0;
			if(isset($pie_stage2['Normal Req'])){
				foreach($pie_stage2['Normal Req'] as $dist){
					$normal = $normal + $dist;
				}
			}
			
			$emergency = 0;
			if(isset($pie_stage2['Emergency Req'])){
				foreach($pie_stage2['Emergency Req'] as $dist){
					$emergency = $emergency + $dist;
				}
			}
			
			$chronic = 0;
			if(isset($pie_stage2['Chronic Req'])){
				foreach($pie_stage2['Chronic Req'] as $dist){
					$chronic = $chronic + $dist;
				}
			}
			
			$cell_index = $cell_index+1;
			$objWorkSheet->getRowDimension($cell_index)->setRowHeight(44);
			
			$objWorkSheet = $objPHPExcel->setActiveSheetIndex(0); 
			
			//Write cells
			$objWorkSheet->setCellValue('A'.$cell_index, 'Grand Total')
			->getStyle('A'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B'.$cell_index, $device_init)
			->getStyle('B'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('C'.$cell_index, $web_init)
			->getStyle('C'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('D'.$cell_index, $prescribed)
			->getStyle('D'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('E'.$cell_index, $under_med)
			->getStyle('E'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('F'.$cell_index, $follow)
			->getStyle('F'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('G'.$cell_index, $cured)
			->getStyle('G'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('H'.$cell_index, $normal)
			->getStyle('H'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('I'.$cell_index, $emergency)
			->getStyle('I'.$cell_index)->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('J'.$cell_index, $chronic)
			->getStyle('J'.$cell_index)->applyFromArray($styleArray);
		}
		
		//======================'Normal Req' ====== Tab
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
		// Rename sheet
		$objWorkSheet->setTitle('Normal Req');
		
		//Write cells
		$objWorkSheet->setCellValue('A1', "Student's Name")
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'District')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'School Name')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Class')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Section')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'Problem Info')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Problem Description')
		->getStyle('G1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H1', 'Doctor Summary')
		->getStyle('H1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I1', 'Doctor Advice')
		->getStyle('I1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('J1', 'Prescription')
		->getStyle('J1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('K1', 'Request Type')
		->getStyle('K1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('L1', 'Status')
		->getStyle('L1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('M1', 'Unique ID')
		->getStyle('M1')->applyFromArray($styleArray);
		
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("I")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("J")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("K")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("L")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("M")->setAutoSize(true);
		
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		
		$cell_index = 2;
		foreach ($student_data as $student){
			
			//$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//echo print_r($doc,TRUE);exit();
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
			//if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Name']['field_ref']);
				$objWorkSheet->setCellValue('B'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['District']['field_ref']);
				$objWorkSheet->setCellValue('C'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['School Name']['field_ref']);
				$objWorkSheet->setCellValue('D'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Class']['field_ref']);
				$objWorkSheet->setCellValue('E'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Section']['field_ref']);
				//if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				if(isset($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) && !empty($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				{
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}else
				{
					$identifiers_normal = $student['doc_data']['widget_data']['page1']['Problem Info']['Normal'];
					foreach ($identifiers_normal as $identifier => $values)
					{
						$var123 = implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]);
						if(!empty($var123))
						{
							$objWorkSheet->setCellValue('F'.$cell_index,(gettype($student['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier");
						} 
					}
					//$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Normal']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$objWorkSheet->setCellValue('M'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID']);
				$cell_index ++;
			//}
		}

		
		
		//======================'Emergency Req' ====== Tab
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(2); //Setting index when creating
		// Rename sheet
		$objWorkSheet->setTitle('Emergency Req');
		
		//Write cells
		$objWorkSheet->setCellValue('A1', "Student's Name")
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'District')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'School Name')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Class')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Section')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'Problem Info')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Problem Description')
		->getStyle('G1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H1', 'Doctor Summary')
		->getStyle('H1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I1', 'Doctor Advice')
		->getStyle('I1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('J1', 'Prescription')
		->getStyle('J1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('K1', 'Request Type')
		->getStyle('K1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('L1', 'Status')
		->getStyle('L1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('M1', 'Unique ID')
		->getStyle('M1')->applyFromArray($styleArray);
		
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("I")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("J")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("K")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("L")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("M")->setAutoSize(true);
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		
		$cell_index = 2;
		foreach ($student_data as $student){

			//$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
		//if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Name']['field_ref']);
				$objWorkSheet->setCellValue('B'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['District']['field_ref']);
				$objWorkSheet->setCellValue('C'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['School Name']['field_ref']);
				$objWorkSheet->setCellValue('D'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Class']['field_ref']);
				$objWorkSheet->setCellValue('E'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Section']['field_ref']);
				/*if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}*/
				//if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) && !empty($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				if(isset($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) && !empty($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				{
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}else
				{
					$identifiers_emergency = $student['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];
					foreach ($identifiers_emergency as $identifier => $values)
					{
						$var123 = implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]);
						if(!empty($var123))
						{
							$objWorkSheet->setCellValue('F'.$cell_index,(gettype($student['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier");
						} 
					}
					//$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Normal']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$objWorkSheet->setCellValue('M'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID']);
				$cell_index ++;
			//}
		}
		
		//======================'Chronic Req' ====== Tab
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(3); //Setting index when creating
		// Rename sheet
		$objWorkSheet->setTitle('Chronic Req');
		
		//Write cells
		$objWorkSheet->setCellValue('A1', "Student's Name")
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'District')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'School Name')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Class')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Section')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'Problem Info')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Problem Description')
		->getStyle('G1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H1', 'Doctor Summary')
		->getStyle('H1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I1', 'Doctor Advice')
		->getStyle('I1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('J1', 'Prescription')
		->getStyle('J1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('K1', 'Request Type')
		->getStyle('K1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('L1', 'Status')
		->getStyle('L1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('M1', 'Unique ID')
		->getStyle('M1')->applyFromArray($styleArray);
		
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("I")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("J")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("K")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("L")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("M")->setAutoSize(true);
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		//log_message('error','student_data===========3146'.print_r($student_data,TRUE));
		$cell_index = 2;
		foreach ($student_data as $student){

			//$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
			//if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Name']['field_ref']);
				$objWorkSheet->setCellValue('B'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['District']['field_ref']);
				$objWorkSheet->setCellValue('C'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['School Name']['field_ref']);
				$objWorkSheet->setCellValue('D'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Class']['field_ref']);
				$objWorkSheet->setCellValue('E'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Section']['field_ref']);
				/*if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}*/
				//if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) && !empty($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				if(isset($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) && !empty($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']))
				{
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}else
				{
					$identifiers_chronic = $student['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];
					foreach ($identifiers_chronic as $identifier => $values)
					{
						$var123 = implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]);
						if(!empty($var123))
						{
							$objWorkSheet->setCellValue('F'.$cell_index,(gettype($student['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$student['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier");
						} 
					}
					//$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Normal']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$objWorkSheet->setCellValue('M'.$cell_index, $student ["doc_data"] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID']);
				$cell_index ++;
			//}
		}

		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$file_save = BASEDIR.TENANT.'/'.$date."-TSWREIS-Request_Report.xlsx";
		$file_name = URLCustomer.$date."-TSWREIS-Request_Report.xlsx";
		$objWriter->save($file_save);
			//$this->secure_file_download($file_name);
			//unlink($file_name);
		return $file_name;
	}
	
	public function generate_excel_for_screening_pie($date,$screening_pie_span, $dt_name = "All", $school_name = "All")
	{
		//$dt_name = "All";
		 //$school_name = "All";

		//load the excel library
		$this->ci->load->library('excel');

		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Vikas Singh Chouhan");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Screening Report");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREIS-Screening Report");
		$objPHPExcel->getProperties()->setDescription("Screening report of TSWREIS.");

		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Report Summary");

		$objWorkSheet->getRowDimension(1)->setRowHeight(44);

		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));

		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		//$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'name'  => 'Calibri'),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'DCE6F1') ));
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date, $screening_pie_span);

		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', $dt_name)
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'School')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', $school_name)
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Date')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', $dates ['today_date'])
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Screening Span')
		->getStyle('G1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H1', 'From '.$dates ['today_date']." to ".$dates ['end_date'])
		->getStyle('H1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I1', $screening_pie_span)
		->getStyle('I1')->applyFromArray($styleArray);
		


		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("I")->setAutoSize(true);

		if($school_name == "All"){
			if($dt_name == "All"){
				$pie_stage1_data =  $this->ci->panacea_common_model->get_all_screenings($date,$screening_pie_span);
				$cell_count = 0;
				$cell_collection = ['A','B','C','D','E','F','G','H','I','J','K','L','M',"N"];

				$styleArray = array(
					'font'  => array(
						'bold'  => true ));

				foreach ($pie_stage1_data as $pie_sector){
					$objWorkSheet->setCellValue($cell_collection[$cell_count]."3", $pie_sector['label'])
					->getStyle($cell_collection[$cell_count]."3")->applyFromArray($styleArray);
					$data = json_encode($pie_sector);
					$pie_stage2_data =  $this->ci->panacea_common_model->get_drilling_screenings_abnormalities($data, $date, $screening_pie_span);
					$stage2_cell = 5;
					$stage1_value = 0;
					foreach ($pie_stage2_data as $stage2_sector){
						$objWorkSheet->setCellValue($cell_collection[$cell_count].$stage2_cell, $stage2_sector['label']);
						$objWorkSheet->setCellValue($cell_collection[$cell_count+1].$stage2_cell, $stage2_sector['value']);
						$stage1_value = $stage1_value + $stage2_sector['value'];
						$stage2_cell ++;
					}
					$objWorkSheet->setCellValue($cell_collection[$cell_count+1]."3", $stage1_value)
					->getStyle($cell_collection[$cell_count+1]."3")->applyFromArray($styleArray);
					$cell_count +=2;
				}
			}else{
				
				$styleArray = array(
					'font'  => array(
						'bold'  => true ));

				$objWorkSheet->setCellValue("A3", "Physical Abnormalities")
				->getStyle("A3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("C3", "General Abnormalities")
				->getStyle("C3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("E3", "Eye Abnormalities")
				->getStyle("E3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("G3", "Auditory Abnormalities")
				->getStyle("G3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("I3", "Dental Abnormalities")
				->getStyle("I3")->applyFromArray($styleArray);

				//$objWorkSheet->setCellValue("B3", $stage1_value)
				//->getStyle("B3")->applyFromArray($styleArray);

				$pie_data = $this->ci->panacea_common_model->get_screening_pie_stage4($dates);
				$requests1 = 0;
				$requests2 = 0;
				$requests3 = 0;
				$requests4 = 0;
				$requests5 = 0;
				$requests6 = 0;
				$requests7 = 0;
				$requests8 = 0;
				$requests9 = 0;
				$requests10 = 0;
				$requests11 = 0;
				$requests12 = 0;
				$requests13 = 0;
				$requests14 = 0;
				$requests15 = 0;
				$requests16 = 0;
				$requests17 = 0;
				$requests18 = 0;
				$requests19 = 0;
				$requests20 = 0;
				$requests21 = 0;
				$requests22 = 0;
				$requests23 = 0;
				$requests24 = 0;
				$requests25 = 0;
				$requests26 = 0;
				$requests27 = 0;
				foreach ($pie_data as $each_pie){


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dt_name )] as $dist_arr){
							$requests1 = $requests1 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dt_name )] as $dist_arr){
							$requests2 = $requests2 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dt_name )] as $dist_arr){
							$requests3 = $requests3 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dt_name )] as $dist_arr){
							$requests4 = $requests4 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dt_name )] as $dist_arr){
							$requests5 = $requests5 + intval($dist_arr['value']);
						}
					}

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dt_name )] as $dist_arr){
							$requests6 = $requests6 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dt_name )] as $dist_arr){
							$requests7 = $requests7 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dt_name )] as $dist_arr){
							$requests8 = $requests8 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					// if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dt_name )] )) {
						// foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dt_name )] as $dist_arr){
							// $requests9 = $requests9 + intval($dist_arr['value']);
						// }
					// }
					
					//==============================================Deficencies divided===================
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Anaemia"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Anaemia"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Anaemia"] [strtolower ( $dt_name )] as $dist_arr){
							$requests9 = $requests9 + intval($dist_arr['value']);
						}
					}
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [strtolower ( $dt_name )] as $dist_arr){
							$requests10 = $requests10 + intval($dist_arr['value']);
						}
					}
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin A Deficiency"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin A Deficiency"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin A Deficiency"] [strtolower ( $dt_name )] as $dist_arr){
							$requests11 = $requests11 + intval($dist_arr['value']);
						}
					}
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin D Deficiency"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin D Deficiency"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Vitamin D Deficiency"] [strtolower ( $dt_name )] as $dist_arr){
							$requests12 = $requests12 + intval($dist_arr['value']);
						}
					}
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["SAM/stunting"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["SAM/stunting"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["SAM/stunting"] [strtolower ( $dt_name )] as $dist_arr){
							$requests13 = $requests13 + intval($dist_arr['value']);
						}
					}
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Goiter"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Goiter"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Goiter"] [strtolower ( $dt_name )] as $dist_arr){
							$requests14 = $requests14 + intval($dist_arr['value']);
						}
					}
					
					//==============================================Deficencies divided===================


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dt_name )] as $dist_arr){
							$requests15 = $requests15 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dt_name )] as $dist_arr){
							$requests16 = $requests16 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dt_name )] as $dist_arr){
							$requests17 = $requests17 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dt_name )] as $dist_arr){
							$requests18 = $requests18 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dt_name )] as $dist_arr){
							$requests19 = $requests19 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dt_name )] as $dist_arr){
							$requests20 = $requests20 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dt_name )] as $dist_arr){
							$requests21 = $requests21 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dt_name )] as $dist_arr){
							$requests22 = $requests22 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dt_name )] )) {
						log_message("debug","oooooooooooooooooooooooooooooooooooooo================".print_r($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dt_name )],true));
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dt_name )] as $dist_arr){
							$requests23 = $requests23 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dt_name )] as $dist_arr){
							$requests24 = $requests24 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dt_name )] as $dist_arr){
							$requests25 = $requests25 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);

					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dt_name )] as $dist_arr){
							$requests26 = $requests26 + intval($dist_arr['value']);
						}
					}


					//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dt_name )] != null && is_array ( $each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dt_name )] )) {
						foreach($each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dt_name )] as $dist_arr){
							$requests27 = $requests27 + intval($dist_arr['value']);
						}
					}

				}
				$objWorkSheet->setCellValue("B3", $requests1+$requests2)
				->getStyle("B3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("D3", $requests3+$requests4+$requests5+$requests6+$requests7+$requests8+$requests9+$requests10+$requests11+$requests12+$requests13+$requests14+$requests15)
				->getStyle("D3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("F3", $requests16+$requests17+$requests18)
				->getStyle("F3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("H3", $requests19+$requests20+$requests21)
				->getStyle("H3")->applyFromArray($styleArray);
				$objWorkSheet->setCellValue("J3", $requests22+$requests23+$requests24+$requests25+$requests26+$requests27)
				->getStyle("J3")->applyFromArray($styleArray);

				$sheets_array = [];
				$objWorkSheet->setCellValue("A5", "Over Weight");
				$objWorkSheet->setCellValue("B5", $requests1);
				$sheets_array['Over Weight'] = $requests1;

				$objWorkSheet->setCellValue("A6", "Under Weight");
				$objWorkSheet->setCellValue("B6", $requests2);
				$sheets_array['Under Weight'] = $requests2;

				$objWorkSheet->setCellValue("C5", "General");
				$objWorkSheet->setCellValue("D5", $requests3);
				$sheets_array['General'] = $requests3;

				$objWorkSheet->setCellValue("C6", "Skin");
				$objWorkSheet->setCellValue("D6", $requests4);
				$sheets_array['Skin'] = $requests4;

				$objWorkSheet->setCellValue("C7", "Others(Description/Advice)");
				$objWorkSheet->setCellValue("D7", $requests5);
				$sheets_array['Ortho'] = $requests5;

				$objWorkSheet->setCellValue("C8", "Ortho");
				$objWorkSheet->setCellValue("D8", $requests6);
				$sheets_array['Ortho'] = $requests6;

				$objWorkSheet->setCellValue("C9", "Postural");
				$objWorkSheet->setCellValue("D9", $requests7);
				$sheets_array['Postural'] = $requests7;

				$objWorkSheet->setCellValue("C10", "Defects at Birth");
				$objWorkSheet->setCellValue("D10", $requests8);
				$sheets_array['Defects at Birth'] = $requests8;

			// $objWorkSheet->setCellValue("C11", "Deficencies");
			// $objWorkSheet->setCellValue("D11", $requests9);
			// $sheets_array['Deficencies'] = $requests9;

			//==========================================Deficencies divided========================

				$objWorkSheet->setCellValue("C11", "Anaemia");
				$objWorkSheet->setCellValue("D11", $requests9);
				$sheets_array['Anaemia'] = $requests9;

				$objWorkSheet->setCellValue("C12", "Vitamin Deficiency - Bcomplex");
				$objWorkSheet->setCellValue("D12", $requests10);
				$sheets_array['Vitamin Deficiency - Bcomplex'] = $requests10;

				$objWorkSheet->setCellValue("C13", "Vitamin A Deficiency");
				$objWorkSheet->setCellValue("D13", $requests11);
				$sheets_array['Vitamin A Deficiency'] = $requests11;

				$objWorkSheet->setCellValue("C14", "Vitamin D Deficiency");
				$objWorkSheet->setCellValue("D14", $requests12);
				$sheets_array['Vitamin D Deficiency'] = $requests12;

				$objWorkSheet->setCellValue("C15", "SAM/stunting");
				$objWorkSheet->setCellValue("D15", $requests13);
				$sheets_array['SAM/stunting'] = $requests13;

				$objWorkSheet->setCellValue("C16", "Goiter");
				$objWorkSheet->setCellValue("D16", $requests14);
				$sheets_array['Goiter'] = $requests14;



			//==========================================Deficencies divided========================

				$objWorkSheet->setCellValue("C17", "Childhood Diseases");
				$objWorkSheet->setCellValue("D17", $requests15);
				$sheets_array['Childhood Diseases'] = $requests15;

				$objWorkSheet->setCellValue("E5", "Without Glasses");
				$objWorkSheet->setCellValue("F5", $requests16);
				$sheets_array['Without Glasses'] = $requests16;

				$objWorkSheet->setCellValue("E6", "With Glasses");
				$objWorkSheet->setCellValue("F6", $requests17);
				$sheets_array['With Glasses'] = $requests17;

				$objWorkSheet->setCellValue("E7", "Colour Blindness");
				$objWorkSheet->setCellValue("F7", $requests18);
				$sheets_array['Colour Blindness'] = $requests18;

				$objWorkSheet->setCellValue("G5", "Right Ear");
				$objWorkSheet->setCellValue("H5", $requests19);
				$sheets_array['Right Ear'] = $requests19;

				$objWorkSheet->setCellValue("G6", "Left Ear");
				$objWorkSheet->setCellValue("H6", $requests20);
				$sheets_array['Left Ear'] = $requests20;

				$objWorkSheet->setCellValue("G7", "Speech Screening");
				$objWorkSheet->setCellValue("H7", $requests21);
				$sheets_array['Speech Screening'] = $requests21;

				$objWorkSheet->setCellValue("I5", "Oral Hygiene - Fair");
				$objWorkSheet->setCellValue("J5", $requests22);
				$sheets_array['Oral Hygiene - Fair'] = $requests22;

				$objWorkSheet->setCellValue("I6", "Oral Hygiene - Poor");
				$objWorkSheet->setCellValue("J6", $requests23);
				$sheets_array['Oral Hygiene - Poor'] = $requests23;

				$objWorkSheet->setCellValue("I7", "Carious Teeth");
				$objWorkSheet->setCellValue("J7", $requests24);
				$sheets_array['Carious Teeth'] = $requests24;

				$objWorkSheet->setCellValue("I8", "Flourosis");
				$objWorkSheet->setCellValue("J8", $requests25);
				$sheets_array['Flourosis'] = $requests20;

				$objWorkSheet->setCellValue("I9", "Orthodontic Treatment");
				$objWorkSheet->setCellValue("J9", $requests26);
				$sheets_array['Orthodontic Treatment'] = $requests26;

				$objWorkSheet->setCellValue("I10", "Indication for extraction");
				$objWorkSheet->setCellValue("J10", $requests27);
				$sheets_array['Indication for extraction'] = $requests27;
				
			}
		}else{
			$styleArray = array(
				'font'  => array(
					'bold' => true ));
			
			$objWorkSheet->setCellValue("A3", "Physical Abnormalities")
			->getStyle("A3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("C3", "General Abnormalities")
			->getStyle("C3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("E3", "Eye Abnormalities")
			->getStyle("E3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("G3", "Auditory Abnormalities")
			->getStyle("G3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("I3", "Dental Abnormalities")
			->getStyle("I3")->applyFromArray($styleArray);
			
			//$objWorkSheet->setCellValue("B3", $stage1_value)
			//->getStyle("B3")->applyFromArray($styleArray);
			
			$pie_data = $this->ci->panacea_common_model->get_screening_pie_stage5($dates);
			$requests1 = [ ];
			$requests2 = [ ];
			$requests3 = [ ];
			$requests4 = [ ];
			$requests5 = [ ];
			$requests6 = [ ];
			$requests7 = [ ];
			$requests8 = [ ];
			$requests9 = [ ];
			$requests10 = [ ];
			$requests11 = [ ];
			$requests12 = [ ];
			$requests13 = [ ];
			$requests14 = [ ];
			$requests15 = [ ];
			$requests16 = [ ];
			$requests17 = [ ];
			$requests18 = [ ];
			$requests19 = [ ];
			$requests20 = [ ];
			$requests21 = [ ];
			$requests22 = [ ];
			$requests23 = [ ];
			$requests24 = [ ];
			$requests25 = [ ];
			$requests26 = [ ];
			$requests27 = [ ];
			foreach ($pie_data as $each_pie){
				
				
				//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
				
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode ( strtolower($school_name) )] )) {
					$requests1 = array_merge_recursive ( $requests1, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] [base64_encode ( strtolower($school_name) )] );
				}
				
				
				//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode ( strtolower($school_name) )] )) {
					$requests2 = array_merge_recursive ( $requests2, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] [base64_encode ( strtolower($school_name) )] );
				}
				
				
				//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode ( strtolower($school_name) )] )) {
					$requests3 = array_merge_recursive ( $requests3, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] [base64_encode ( strtolower($school_name) )] );
				}
				
				
				//$objWorkSheet->setCellValue("A6", $each_pie["Over Weight"]);
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode ( strtolower($school_name) )] )) {
					$requests4 = array_merge_recursive ( $requests4, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode ( strtolower($school_name) )] )) {
					$requests5 = array_merge_recursive ( $requests5, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode ( strtolower($school_name) )] )) {
					$requests6 = array_merge_recursive ( $requests6, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode ( strtolower($school_name) )] )) {
					$requests7 = array_merge_recursive ( $requests7, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode ( strtolower($school_name) )] )) {
					$requests8 = array_merge_recursive ( $requests8, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] [base64_encode ( strtolower($school_name) )] );
				}
				
				// if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode ( strtolower($school_name) )] )) {
					// $requests9 = array_merge_recursive ( $requests9, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] [base64_encode ( strtolower($school_name) )] );
				// }
				
				//============================ Deficencies divided========================
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode ( strtolower($school_name) )] )) {
					$requests9 = array_merge_recursive ( $requests9, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Anaemia"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode ( strtolower($school_name) )] )) {
					$requests10 = array_merge_recursive ( $requests10, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin Deficiency - Bcomplex"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode ( strtolower($school_name) )] )) {
					$requests11 = array_merge_recursive ( $requests11, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin A Deficiency"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode ( strtolower($school_name) )] )) {
					$requests12 = array_merge_recursive ( $requests12, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Vitamin D Deficiency"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode ( strtolower($school_name) )] )) {
					$requests13 = array_merge_recursive ( $requests13, $each_pie ['pie_data'] ['stage5_pie_vales'] ["SAM/stunting"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode ( strtolower($school_name) )] )) {
					$requests14 = array_merge_recursive ( $requests14, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Goiter"] [base64_encode ( strtolower($school_name) )] );
				}
				
				//============================Deficencies divided========================
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode ( strtolower($school_name) )] )) {
					$requests15 = array_merge_recursive ( $requests15, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode ( strtolower($school_name) )] )) {
					$requests16 = array_merge_recursive ( $requests16, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode ( strtolower($school_name) )] )) {
					$requests17 = array_merge_recursive ( $requests17, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode ( strtolower($school_name) )] )) {
					$requests18 = array_merge_recursive ( $requests18, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] [base64_encode ( strtolower($school_name) )] );
				}
				
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode ( strtolower($school_name) )] )) {
					$requests19 = array_merge_recursive ( $requests19, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode ( strtolower($school_name) )] )) {
					$requests20 = array_merge_recursive ( $requests20, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode ( strtolower($school_name) )] )) {
					$requests21 = array_merge_recursive ( $requests21, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode ( strtolower($school_name) )] )) {
					$requests22 = array_merge_recursive ( $requests22, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode ( strtolower($school_name) )] )) {
					$requests23 = array_merge_recursive ( $requests23, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode ( strtolower($school_name) )] )) {
					$requests24 = array_merge_recursive ( $requests24, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode ( strtolower($school_name) )] )) {
					$requests25 = array_merge_recursive ( $requests25, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] [base64_encode ( strtolower($school_name) )] );
				}

				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode ( strtolower($school_name) )] )) {
					$requests26 = array_merge_recursive ( $requests26, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] [base64_encode ( strtolower($school_name) )] );
				}
				if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode ( strtolower($school_name) )] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode ( strtolower($school_name) )] )) {
					$requests27 = array_merge_recursive ( $requests27, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] [base64_encode ( strtolower($school_name) )] );
				}
				
			}
			
			$objWorkSheet->setCellValue("B3", count($requests1)+count($requests2))
			->getStyle("B3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("D3", count($requests3)+count($requests4)+count($requests5)+count($requests6)+count($requests7)+count($requests8)+count($requests9)+count($requests10)+count($requests11)+count($requests12)+count($requests13)+count($requests14)+count($requests15))
			->getStyle("D3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("F3", count($requests16)+count($requests17)+count($requests18))
			->getStyle("F3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("H3", count($requests19)+count($requests20)+count($requests21))
			->getStyle("H3")->applyFromArray($styleArray);
			$objWorkSheet->setCellValue("J3", count($requests22)+count($requests23)+count($requests24)+count($requests25)+count($requests26)+count($requests27))
			->getStyle("J3")->applyFromArray($styleArray);
			
			$sheets_array = [];
			$objWorkSheet->setCellValue("A5", "Over Weight");
			$objWorkSheet->setCellValue("B5", count($requests1));
			$sheets_array['Over Weight']["unique_id"] = $requests1;
			$sheets_array['Over Weight']["ehr_value"] = [];
			array_push($sheets_array['Over Weight']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			
			$objWorkSheet->setCellValue("A6", "Under Weight");
			$objWorkSheet->setCellValue("B6", count($requests2));
			$sheets_array['Under Weight']["unique_id"] = $requests2;
			$sheets_array['Under Weight']["ehr_value"] = [];
			array_push($sheets_array['Under Weight']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");			
			
			$objWorkSheet->setCellValue("C5", "General");
			$objWorkSheet->setCellValue("D5", count($requests3));
			$sheets_array['General']["unique_id"] = $requests3;
			$sheets_array['General']["ehr_value"] = [];
			array_push($sheets_array['General']["ehr_value"],"page4^^Doctor Check Up^^Check the box if normal else describe abnormalities");
			
			$objWorkSheet->setCellValue("C6", "Skin");
			$objWorkSheet->setCellValue("D6", count($requests4));
			$sheets_array['Skin']["unique_id"] = $requests4;
			$value = [];
			$sheets_array['Skin']["ehr_value"] = [];
			array_push($sheets_array['Skin']["ehr_value"],"page4^^Doctor Check Up^^Check the box if normal else describe abnormalities");
			
			$objWorkSheet->setCellValue("C7", "Others(Description/Advice)");
			$objWorkSheet->setCellValue("D7", count($requests5));
			$sheets_array['Others(Description&Advice)']["unique_id"] = $requests5;
			$value = [];
			$sheets_array['Others(Description&Advice)']["ehr_value"] = [];
			array_push($sheets_array['Others(Description&Advice)']["ehr_value"],"page4^^Doctor Check Up^^Description");
			array_push($sheets_array['Others(Description&Advice)']["ehr_value"],"page4^^Doctor Check Up^^Advice");
			
			$objWorkSheet->setCellValue("C8", "Ortho");
			$objWorkSheet->setCellValue("D8", count($requests6));
			$sheets_array['Ortho']["unique_id"] = $requests6;
			$value = [];
			$sheets_array['Ortho']["ehr_value"] = [];
			array_push($sheets_array['Ortho']["ehr_value"],"page4^^Doctor Check Up^^Ortho");
			
			$objWorkSheet->setCellValue("C9", "Postural");
			$objWorkSheet->setCellValue("D9", count($requests7));
			$sheets_array['Postural']["unique_id"] = $requests7;
			$value = [];
			$sheets_array['Postural']["ehr_value"] = [];
			array_push($sheets_array['Postural']["ehr_value"],"page4^^Doctor Check Up^^Postural");
			
			$objWorkSheet->setCellValue("C10", "Defects at Birth");
			$objWorkSheet->setCellValue("D10", count($requests8));
			$sheets_array['Defects at Birth']["unique_id"] = $requests8;
			$value = [];
			$sheets_array['Defects at Birth']["ehr_value"] = [];
			array_push($sheets_array['Defects at Birth']["ehr_value"],"page5^^Doctor Check Up^^Defects at Birth");
			
			// $objWorkSheet->setCellValue("C11", "Deficencies");
			// $objWorkSheet->setCellValue("D11", count($requests9));
			// $sheets_array['Deficencies']["unique_id"] = $requests9;
			// $value = [];
			// $sheets_array['Deficencies']["ehr_value"] = [];
			// array_push($sheets_array['Deficencies']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			//================================== Deficencies divided==============
			
			$objWorkSheet->setCellValue("C11", "Anaemia");
			$objWorkSheet->setCellValue("D11", count($requests9));
			$sheets_array['Anaemia']["unique_id"] = $requests9;
			$sheets_array['Anaemia']["ehr_value"] = [];
			array_push($sheets_array['Anaemia']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			$objWorkSheet->setCellValue("C12", "Vitamin Deficiency - Bcomplex");
			$objWorkSheet->setCellValue("D12", count($requests10));
			$sheets_array['Vitamin Deficiency - Bcomplex']["unique_id"] = $requests10;
			$sheets_array['Vitamin Deficiency - Bcomplex']["ehr_value"] = [];
			array_push($sheets_array['Vitamin Deficiency - Bcomplex']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			$objWorkSheet->setCellValue("C13", "Vitamin A Deficiency");
			$objWorkSheet->setCellValue("D13", count($requests11));
			$sheets_array['Vitamin A Deficiency']["unique_id"] = $requests11;
			$sheets_array['Vitamin A Deficiency']["ehr_value"] = [];
			array_push($sheets_array['Vitamin A Deficiency']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			$objWorkSheet->setCellValue("C14", "Vitamin D Deficiency");
			$objWorkSheet->setCellValue("D14", count($requests12));
			$sheets_array['Vitamin D Deficiency']["unique_id"] = $requests12;
			$sheets_array['Vitamin D Deficiency']["ehr_value"] = [];
			array_push($sheets_array['Vitamin D Deficiency']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			$objWorkSheet->setCellValue("C15", "SAM/stunting");
			$objWorkSheet->setCellValue("D15", count($requests13));
			$sheets_array['SAM/stunting']["unique_id"] = $requests13;
			$sheets_array['SAM/stunting']["ehr_value"] = [];
			array_push($sheets_array['SAM/stunting']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			$objWorkSheet->setCellValue("C16", "Goiter");
			$objWorkSheet->setCellValue("D16", count($requests14));
			$sheets_array['Goiter']["unique_id"] = $requests14;
			$sheets_array['Goiter']["ehr_value"] = [];
			array_push($sheets_array['Goiter']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
			
			//================================== Deficencies divided==============
			
			$objWorkSheet->setCellValue("C17", "Childhood Diseases");
			$objWorkSheet->setCellValue("D17", count($requests15));
			$sheets_array['Childhood Diseases']["unique_id"] = $requests15;
			$value = [];
			$sheets_array['Childhood Diseases']["ehr_value"] = [];
			array_push($sheets_array['Childhood Diseases']["ehr_value"],"page5^^Doctor Check Up^^Childhood Diseases");
			
			$objWorkSheet->setCellValue("E5", "Without Glasses");
			$objWorkSheet->setCellValue("F5", count($requests16));
			$sheets_array['Without Glasses']["unique_id"] = $requests16;
			$value = [];
			$sheets_array['Without Glasses']["ehr_value"] = [];
			array_push($sheets_array['Without Glasses']["ehr_value"],"page6^^Without Glasses^^Right");
			array_push($sheets_array['Without Glasses']["ehr_value"],"page6^^Without Glasses^^Left");
			
			$objWorkSheet->setCellValue("E6", "With Glasses");
			$objWorkSheet->setCellValue("F6", count($requests17));
			$sheets_array['With Glasses']["unique_id"] = $requests17;
			$value = [];
			$sheets_array['With Glasses']["ehr_value"] = [];
			array_push($sheets_array['With Glasses']["ehr_value"],"page6^^With Glasses^^Right");
			array_push($sheets_array['With Glasses']["ehr_value"],"page6^^With Glasses^^Left");
			
			$objWorkSheet->setCellValue("E7", "Colour Blindness");
			$objWorkSheet->setCellValue("F7", count($requests18));
			$sheets_array['Colour Blindness']["unique_id"] = $requests18;
			$value = [];
			$sheets_array['Colour Blindness']["ehr_value"] = [];
			array_push($sheets_array['Colour Blindness']["ehr_value"],"page7^^Colour Blindness^^Right");
			array_push($sheets_array['Colour Blindness']["ehr_value"],"page7^^Colour Blindness^^Left");
			
			$objWorkSheet->setCellValue("G5", "Right Ear");
			$objWorkSheet->setCellValue("H5", count($requests19));
			$sheets_array['Right Ear']["unique_id"] = $requests19;
			$value = [];
			$sheets_array['Right Ear']["ehr_value"] = [];
			array_push($sheets_array['Right Ear']["ehr_value"],"page8^^ Auditory Screening^^Right");
			
			$objWorkSheet->setCellValue("G6", "Left Ear");
			$objWorkSheet->setCellValue("H6", count($requests20));
			$sheets_array['Left Ear']["unique_id"] = $requests20;
			$value = [];
			$sheets_array['Left Ear']["ehr_value"] = [];
			array_push($sheets_array['Left Ear']["ehr_value"],"page8^^ Auditory Screening^^Left");
			
			$objWorkSheet->setCellValue("G7", "Speech Screening");
			$objWorkSheet->setCellValue("H7", count($requests21));
			$sheets_array['Speech Screening']["unique_id"] = $requests21;
			$value = [];
			$sheets_array['Speech Screening']["ehr_value"] = [];
			array_push($sheets_array['Speech Screening']["ehr_value"],"page8^^ Auditory Screening^^Speech Screening");
			
			$objWorkSheet->setCellValue("I5", "Oral Hygiene - Fair");
			$objWorkSheet->setCellValue("J5", count($requests22));
			$sheets_array['Oral Hygiene - Fair']["unique_id"] = $requests22;
			$value = [];
			$sheets_array['Oral Hygiene - Fair']["ehr_value"] = [];
			array_push($sheets_array['Oral Hygiene - Fair']["ehr_value"],"page9^^Dental Check-up^^Oral Hygiene");
			
			$objWorkSheet->setCellValue("I6", "Oral Hygiene - Poor");
			$objWorkSheet->setCellValue("J6", count($requests23));
			$sheets_array['Oral Hygiene - Poor']["unique_id"] = $requests23;
			$value = [];
			$sheets_array['Oral Hygiene - Poor']["ehr_value"] = [];
			array_push($sheets_array['Oral Hygiene - Poor']["ehr_value"],"page9^^Dental Check-up^^Oral Hygiene");
			
			$objWorkSheet->setCellValue("I7", "Carious Teeth");
			$objWorkSheet->setCellValue("J7", count($requests24));
			$sheets_array['Carious Teeth']["unique_id"] = $requests24;
			$value = [];
			$sheets_array['Carious Teeth']["ehr_value"] = [];
			array_push($sheets_array['Carious Teeth']["ehr_value"],"page9^^Dental Check-up^^Carious Teeth");
			
			$objWorkSheet->setCellValue("I8", "Flourosis");
			$objWorkSheet->setCellValue("J8", count($requests25));
			$sheets_array['Flourosis']["unique_id"] = $requests25;
			$value = [];
			$sheets_array['Flourosis']["ehr_value"] = [];
			array_push($sheets_array['Flourosis']["ehr_value"],"page9^^Dental Check-up^^Flourosis");
			
			$objWorkSheet->setCellValue("I9", "Orthodontic Treatment");
			$objWorkSheet->setCellValue("J9", count($requests26));
			$sheets_array['Orthodontic Treatment']["unique_id"] = $requests26;
			$value = [];
			$sheets_array['Orthodontic Treatment']["ehr_value"] = [];
			array_push($sheets_array['Orthodontic Treatment']["ehr_value"],"page9^^Dental Check-up^^Orthodontic Treatment");
			
			$objWorkSheet->setCellValue("I10", "Indication for extraction");
			$objWorkSheet->setCellValue("J10", count($requests27));
			$sheets_array['Indication for extraction']["unique_id"] = $requests27;
			$value = [];
			$sheets_array['Indication for extraction']["ehr_value"] = [];
			array_push($sheets_array['Indication for extraction']["ehr_value"],"page9^^Dental Check-up^^Indication for extraction");
			
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'name'  => 'Calibri'),
				'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'DCE6F1') ));
			
			$sheet_index = 1;
			$cell_collection = ['I','J','K','L','M','N'];
			foreach ($sheets_array as $sheet_key => $each_sheet){


				if(count($each_sheet["unique_id"] ) >0){
					// Add new sheet
					$objWorkSheet = $objPHPExcel->createSheet($sheet_index); //Setting index when creating
					// Rename sheet
					$sheet_key = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', ' ', $sheet_key);
					$objWorkSheet->setTitle($sheet_key);

					//Write cells
					$objWorkSheet->setCellValue('A1', $sheet_key)
					->getStyle('A1')->applyFromArray($styleArray);

					//Write cells
					$objWorkSheet->setCellValue('A2', "Hospital Unique ID")
					->getStyle('A2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('B2', 'Admission Number')
					->getStyle('B2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('C2', 'Student Name')
					->getStyle('C2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('D2', 'Mobile Number')
					->getStyle('D2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('E2', 'Class')
					->getStyle('E2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('F2', 'Section')
					->getStyle('F2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('G2', 'Date of Birth')
					->getStyle('G2')->applyFromArray($styleArray);
					$objWorkSheet->setCellValue('H2', 'Weight')
					->getStyle('H2')->applyFromArray($styleArray);

					$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
					$objWorkSheet->getColumnDimension("H")->setAutoSize(true);
					
					$cell_value_index = 0;
					foreach($each_sheet["ehr_value"] as $value){
						$val_arr = explode("^^",$value);
						
						$objWorkSheet->setCellValue($cell_collection[$cell_value_index].'2', $val_arr[2])
						->getStyle($cell_collection[$cell_value_index].'2')->applyFromArray($styleArray);
						$objWorkSheet->getColumnDimension($cell_collection[$cell_value_index])->setAutoSize(true);
						
						$cell_value_index ++;
					}

					$student_details = $this->ci->panacea_common_model->get_drilling_screenings_students_docs($each_sheet["unique_id"]);
					$cell_ind = 3;
					foreach ($student_details as $student){
						$objWorkSheet->setCellValue('A'.$cell_ind, $student['doc_data']['widget_data']["page1"]['Personal Information']['Hospital Unique ID'] );
						$objWorkSheet->setCellValue('B'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['AD No'] );
						$objWorkSheet->setCellValue('C'.$cell_ind, $student['doc_data']['widget_data']["page1"]['Personal Information']['Name'] );
						$objWorkSheet->setCellValue('D'.$cell_ind, (isset($student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])) ? $student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num']  :"Mobile Number not available" );
						$objWorkSheet->setCellValue('E'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['Class']  );
						$objWorkSheet->setCellValue('F'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['Section'] );
						$objWorkSheet->setCellValue('G'.$cell_ind, $student['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'] );
						$objWorkSheet->setCellValue('H'.$cell_ind, $student['doc_data']['widget_data']["page3"]['Physical Exam']['Weight kgs'] );
						$cell_value_index = 0;
						foreach($each_sheet["ehr_value"] as $value){
							$val_arr = explode("^^",$value);
							if(is_array($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]])){
								$objWorkSheet->setCellValue($cell_collection[$cell_value_index].$cell_ind, implode(", ", $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]));
							}else{
								$objWorkSheet->setCellValue($cell_collection[$cell_value_index].$cell_ind, $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]);
							}
							$cell_value_index ++;
						}
						
						$cell_ind ++;

					}

				}
			}
			
		}
		
		if($school_name != "All"){
			$file_name = $date."-".$school_name.".xlsx";
		}else{
			if($dt_name != "All"){
				$file_name = $date."-".$dt_name.".xlsx";
			}else{
				$file_name = $date."-TSWREIS-Screening_Report.xlsx";
			}
			
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		//$this->secure_file_download($file_name);
		//unlink($file_name);
		return $file_name;
		
	}
	
	function submit_request_to_doctor($form_data){
		$id_str				 = $form_data["ehr_data_for_request"];
		$doc_id			 = $form_data["select_doc"];
		$request_desc = $form_data["desc_request"];
		$time = date('Y-m-d H:i:s');

		$id_array = json_decode($id_str);
		foreach ($id_array as $id){
			$document_id = get_unique_id();
			$doc_data = '{"doc_data" : { "widget_data" : { "page1" : { "Student Info" : { "Unique ID" : "'.$id.'", "Name" : { "field_ref" : "page1_Personal Information_Name" }, "District" : { "field_ref" : "page2_Personal Information_District" }, "School Name" : { "field_ref" : "page2_Personal Information_School Name" }, "Class" : { "field_ref" : "page2_Personal Information_Class" }, "Section" : { "field_ref" : "page2_Personal Information_Section" } }, "Problem Info" : { "Identifier" : null } }, "page2" : { "Problem Info" : { "Description" : "'.$request_desc.'" }, "Diagnosis Info" : { "Doctor Summary" : null, "Doctor Advice" : "", "Prescription" : null }, "Review Info" : { "Request Type" : "Normal", "Status" : "Initiated" } } }, "stage_name" : "healthcare2016531124515424", "user_name" : "panacea.user#gmail.com", "chart_data" : "" }, "history" : [ { "current_stage" : "HS 1", "approval" : "true", "submitted_by" : "panacea.user#gmail.com", "submitted_user_type" : "PADMIN", "time" : "'.$time.'" } ], "app_properties" : { "app_name" : "Health Supervisor Request App", "app_id" : "healthcare2016531124515424" }, "doc_properties" : { "doc_id" : "'.$document_id.'", "status" : 0, "_version" : 1 }}';

			$user_data = '{"app_name" : "Health Supervisor Request App", "app_id" : "healthcare2016531124515424", "doc_id" : "'.$document_id.'", "stage" : "Doctor", "stg_name" : "Doctor", "status" : "new", "from_stage" : "HS 1", "from_user" : "panacea.user#gmail.com", "notification_param" : { "Name" : { "field_ref" : "page1_Personal Information_Name" } }, "doc_received_time" : "'.$time.'", "approval" : "true"}';

			$user_data_array = json_decode($user_data,true);
			$doc_data_array =json_decode($doc_data,true);
			$this->ci->panacea_common_model->initiate_request($doc_id,$user_data_array,$doc_data_array);
		}

	}
	
	public function panacea_groups()
	{
		$total_rows = $this->ci->panacea_common_model->groupscount();

		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->ci->pagination->initialize($config);

		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['groups'] = $this->ci->panacea_common_model->get_groups($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');
		
		//all users in Panacea
		$this->data['users']["admin"] = $this->ci->panacea_common_model->get_all_admin_users();
		$this->data['users']["doctors"] = $this->ci->panacea_common_model->get_all_doctors();
		$this->data['users']["hs"] = $this->ci->panacea_common_model->get_all_health_supervisors();
		$this->data['users']["ha"] = $this->ci->panacea_common_model->get_all_cc_users();
		$this->data['users']["superior"] = $this->ci->panacea_common_model->get_all_superiors();
		$this->data['users']["rhso_group"] = $this->ci->panacea_common_model->get_all_rhso_group();
		$this->data['users']["cro_group"] = $this->ci->panacea_common_model->get_all_cro_group();
		$this->data['users']["rco_group"] = $this->ci->panacea_common_model->get_all_rco_group();
		$this->data['users']["principal"] = $this->ci->panacea_common_model->get_all_principal();

		$this->data['groupscount'] = $total_rows;

		return $this->data;
	}
	
	public function group_msg()
	{
		
		$this->data['groups'] = $this->ci->panacea_common_model->get_all_groups();
		
		$customer = $this->ci->session->userdata("customer");
		$this->data['admin_id'] = $customer["username"];
		
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');

		return $this->data;
	}
	
	public function user_msg()
	{
		$customer = $this->ci->session->userdata("customer");
		$this->data['admin_id'] = $customer["username"];
		
		//all users in Panacea
		$this->data['users']["admin"] = $this->ci->panacea_common_model->get_all_admin_users();
		$this->data['users']["doctors"] = $this->ci->panacea_common_model->get_all_doctors();
		$this->data['users']["hs"] = $this->ci->panacea_common_model->get_all_health_supervisors();
		$this->data['users']["ha"] = $this->ci->panacea_common_model->get_all_cc_users();
		$this->data['users']["superior"] = $this->ci->panacea_common_model->get_all_superiors();
		$this->data['users']["rhso_group"] = $this->ci->panacea_common_model->get_all_rhso_group();
		$this->data['users']["cro_group"] = $this->ci->panacea_common_model->get_all_cro_group();
		$this->data['users']["rco_group"] = $this->ci->panacea_common_model->get_all_rco_group();
		$this->data['users']["principal"] = $this->ci->panacea_common_model->get_all_principal();

		return $this->data;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the absent report sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */

	public function generate_excel_for_absent_sent_schools($date,$schools_list)
	{
		//load the excel library
		$this->ci->load->library('excel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("PANACEA USER");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Absent Report submitted schools");
		$objPHPExcel->getProperties()->setSubject($date."-Absent Report submitted schools");
		$objPHPExcel->getProperties()->setDescription($date."-Absent Report submitted schools");

		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Absent Report Submitted");

		$objWorkSheet->getRowDimension(1)->setRowHeight(44);

		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));

		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'School Name')
		->getStyle('B1')->applyFromArray($styleArray);
		
		for($i=0;$i<count($schools_list['school']);$i++)
		{
			$init=$i+2;
			$objWorkSheet->setCellValue('A'.$init.'', $schools_list['district'][$i])
			->getStyle('A'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B'.$init.'', $schools_list['school'][$i])
			->getStyle('B'.$init.'')->applyFromArray($styleArray);
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$file_name = $date." Absent report submitted schools."."xlsx";
		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		return $file_name;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the absent report not sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */

	public function generate_excel_for_absent_not_sent_schools($date,$schools_list)
	{
		//load the excel library
		$this->ci->load->library('excel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("PANACEA USER");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Absent Report not submitted schools");
		$objPHPExcel->getProperties()->setSubject($date."-Absent Report not submitted schools");
		$objPHPExcel->getProperties()->setDescription($date."-Absent Report not submitted schools");

		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Absent Report Not Submitted");

		$objWorkSheet->getRowDimension(1)->setRowHeight(44);

		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));

		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'School Name')
		->getStyle('B1')->applyFromArray($styleArray);
		
		for($i=0;$i<count($schools_list['school']);$i++)
		{
			$init=$i+2;
			$objWorkSheet->setCellValue('A'.$init.'', $schools_list['district'][$i])
			->getStyle('A'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B'.$init.'', $schools_list['school'][$i])
			->getStyle('B'.$init.'')->applyFromArray($styleArray);
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$file_name = $date." Absent report not submitted schools."."xlsx";
		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		return $file_name;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the sanitation report sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */

	public function generate_excel_for_sanitation_report_sent_schools($date,$schools_list)
	{
		//load the excel library
		$this->ci->load->library('excel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("PANACEA USER");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Sanitation Report submitted schools");
		$objPHPExcel->getProperties()->setSubject($date."-Sanitation Report submitted schools");
		$objPHPExcel->getProperties()->setDescription($date."-Sanitation Report submitted schools");

		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Sanitation Report Submitted");

		$objWorkSheet->getRowDimension(1)->setRowHeight(44);

		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));

		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		
		$objWorkSheet->setCellValue('A1', 'DISTRICT')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'SCHOOL NAME')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'CONTACT PERSON')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'MOBILE')
		->getStyle('D1')->applyFromArray($styleArray);
		
		for($i=0;$i<count($schools_list['school']);$i++)
		{
			$init=$i+2;
			$objWorkSheet->setCellValue('A'.$init.'', $schools_list['district'][$i])
			->getStyle('A'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B'.$init.'', $schools_list['school'][$i])
			->getStyle('B'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('C'.$init.'', $schools_list['person_name'][$i])
			->getStyle('C'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('D'.$init.'', $schools_list['mobile'][$i])
			->getStyle('D'.$init.'')->applyFromArray($styleArray);
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$file_name = $date." Sanitation report submitted schools."."xlsx";
		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		return $file_name;
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the absent report not sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */

	public function generate_excel_for_sanitation_report_not_sent_schools($date,$schools_list)
	{
		//load the excel library
		$this->ci->load->library('excel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("PANACEA USER");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS-Sanitation Report not submitted schools");
		$objPHPExcel->getProperties()->setSubject($date."-Sanitation Report not submitted schools");
		$objPHPExcel->getProperties()->setDescription($date."-Sanitation Report not submitted schools");

		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("Sanitation Report Not Submitted");

		$objWorkSheet->getRowDimension(1)->setRowHeight(44);

		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));

		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		
		$objWorkSheet->setCellValue('A1', 'DISTRICT')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'SCHOOL NAME')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'CONTACT PERSON')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'MOBILE')
		->getStyle('D1')->applyFromArray($styleArray);
		
		for($i=0;$i<count($schools_list['school']);$i++)
		{
			$init=$i+2;
			$objWorkSheet->setCellValue('A'.$init.'', $schools_list['district'][$i])
			->getStyle('A'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('B'.$init.'', $schools_list['school'][$i])
			->getStyle('B'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('C'.$init.'', $schools_list['person_name'][$i])
			->getStyle('C'.$init.'')->applyFromArray($styleArray);
			$objWorkSheet->setCellValue('D'.$init.'', $schools_list['mobile'][$i])
			->getStyle('D'.$init.'')->applyFromArray($styleArray);
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$file_name = $date." Sanitation report not submitted schools."."xlsx";
		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		return $file_name;
	}
	
	public function insert_ehr_note($post)
	{
		$token = $this->ci->panacea_common_model->insert_ehr_note($post);
		
		return $token;
	}
	
	public function delete_ehr_note($doc_id)
	{
		$token = $this->ci->panacea_common_model->delete_ehr_note($doc_id);

		return $token;
	}
	
	function build_sanitation_report($sanitation_report_data)
	{
	   // Variables
		$campus 	       = array();
		$toilets  		   = array();
		$kitchen 	 	   = array();		
		$water_supply  	   = array();
		$dormitories 	   = array();
		$store 	  		   = array();
		$waste_management  = array();
		$sanitation_output = array();
		$attachments       = array();

		if(isset($sanitation_report_data) && !empty($sanitation_report_data))
		{
			foreach($sanitation_report_data as $index => $value)
			{
				$widget_data          = $value['doc_data']['widget_data'];
				$external_attachments = $value['doc_data']['widget_data']['daily']['Campus']['external_attachments'];
				$toilet_external_attachments = $value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'];
				$kitchen_external_attachments = $value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'];
				

				//echo print_r($external_attachments , true);  exit();

				$daily = $widget_data['daily'];
				$weekly = $widget_data['weekly'];
				$monthly = $widget_data['monthly'];
				//$page4 = $widget_data['page4'];
			//daily
			
			// campus
				$clean_campus = array();
				$clean_campus['label'] = 'Cleanliness Of Campus';
				$clean_campus['value'] = $daily['Campus']['Cleanliness Of Campus'];
				array_push($campus,$clean_campus);

				$clean_campus_times = array();
				$clean_campus_times['label'] = 'Cleanliness Of Campus Times';
				$clean_campus_times['value'] = $daily['Campus']['Cleanliness Of Campus Times'];
				array_push($campus,$clean_campus_times);

				$animals = array();
				$animals['label'] = 'Animals Around Campus';
				$animals['value'] = $daily['Campus']['Animals Around Campus'];
				array_push($campus,$animals);

				$animals_type = array();
				$animals_type['label'] = 'Type Of Animal';
				$animals_type['value'] = $daily['Campus']['Type Of Animal'];
				array_push($campus,$animals_type);

				$othere_animals = array();
				$othere_animals['label'] = 'Other Animal Name';
				$othere_animals['value'] = $daily['Campus']['Other Animal Name'];
				array_push($campus,$othere_animals);

			// toilets
			
				$cleanliness_toilets = array();
				$cleanliness_toilets['label'] = 'Cleanliness Toilets or Bathrooms';
				$cleanliness_toilets['value'] = $daily['Toilets']['Cleanliness Toilets or Bathrooms'];
				array_push($toilets,$cleanliness_toilets);

				$cleanliness_toilets_day = array();
				$cleanliness_toilets_day['label'] = 'Cleanliness Toilets or Bathrooms In A Day';
				$cleanliness_toilets_day['value'] = $daily['Toilets']['Cleanliness Toilets or Bathrooms In A Day'];
				array_push($toilets,$cleanliness_toilets_day);

				$damage_toilets = array();
				$damage_toilets['label'] = 'Any Damages To The Toilets';
				$damage_toilets['value'] = $daily['Toilets']['Any Damages To The Toilets'];
				array_push($toilets,$damage_toilets);

			// kitchen
			
				$cleanliness_kitchen = array();
				$cleanliness_kitchen['label'] = 'Cleanliness Of The Kitchen Place';
				$cleanliness_kitchen['value'] = $daily['Kitchen']['Cleanliness Of The Kitchen Place'];
				array_push($kitchen,$cleanliness_kitchen);

				$cleanliness_kitchen_day = array();
				$cleanliness_kitchen_day['label'] = 'Cleanliness Of The Kitchen Place In A Day';
				$cleanliness_kitchen_day['value'] = $daily['Kitchen']['Cleanliness Of The Kitchen Place In A Day'];
				array_push($kitchen,$cleanliness_kitchen_day);

				$menu_followed = array();
				$menu_followed['label'] = 'Daily Menu Followed';
				$menu_followed['value'] = $daily['Kitchen']['Daily Menu Followed'];
				array_push($kitchen,$menu_followed);

				$utensils = array();
				$utensils['label'] = 'Utensils Cleanliness';
				$utensils['value'] = $daily['Kitchen']['Utensils Cleanliness'];
				array_push($kitchen,$utensils);

				$dinining_hall = array();
				$dinining_hall['label'] = 'Dining Hall Cleanliness';
				$dinining_hall['value'] = $daily['Kitchen']['Dining Hall Cleanliness'];
				array_push($kitchen,$dinining_hall);

				$dininghall_clean = array();
				$dininghall_clean['label'] = 'page2_Cleanliness_DiningHalls';
				$dininghall_clean['value'] = $daily['Kitchen']['page2_Cleanliness_DiningHalls'];
				array_push($kitchen,$dininghall_clean);

				$hand_gloves_used = array();
				$hand_gloves_used['label'] = 'Hand Gloves Used By Serving People';
				$hand_gloves_used['value'] = $daily['Kitchen']['Hand Gloves Used By Serving People'];
				array_push($kitchen,$hand_gloves_used);

				$food_tasting = array();
				$food_tasting['label'] = 'Staffmembers Tasty Food Before Serving Meals';
				$food_tasting['value'] = $daily['Kitchen']['Staffmembers Tasty Food Before Serving Meals'];
				array_push($kitchen,$food_tasting);

				$wellness_center = array();
				$wellness_center['label'] = 'Wellness Centre Cleanliness';
				$wellness_center['value'] = $daily['Kitchen']['Wellness Centre Cleanliness'];
				array_push($kitchen,$wellness_center);

				$wellness_cln_times = array();
				$wellness_cln_times['label'] = 'Cleanliness Of The Wellness Centre';
				$wellness_cln_times['value'] = $daily['Kitchen']['Cleanliness Of The Wellness Centre'];
				array_push($kitchen,$wellness_cln_times);

				//weekly
				
			   // water supply condition
			   
				/*$ro_plant = array();
				$ro_plant['label'] = 'RO Plant';
				$ro_plant['value'] = $weekly['Water Supply Condition']['RO Plant'];
				array_push($water_supply,$ro_plant);

				$bore_water = array();
				$bore_water['label'] = 'Bore Water';
				$bore_water['value'] = $weekly['Water Supply Condition']['Bore Water'];
				array_push($water_supply,$bore_water);

				$no_plant_work = array();
				$no_plant_work['label'] = 'No Plant Working';
				$no_plant_work['value'] = $weekly['Water Supply Condition']['No Plant Working'];
				array_push($water_supply,$no_plant_work);

				$watertank_cleaning = array();
				$watertank_cleaning['label'] = 'Water Tank Cleaning';
				$watertank_cleaning['value'] = $weekly['Water Supply Condition']['Water Tank Cleaning'];
				array_push($water_supply,$watertank_cleaning);

			// Dormitories
			
				$dormitories_clean = array();
				$dormitories_clean['label'] = 'Dormitory Cleaning';
				$dormitories_clean['value'] = $weekly['Dormitories']['Dormitory Cleaning'];
				array_push($dormitories,$dormitories_clean);

				$dormitories_clean_count = array();
				$dormitories_clean_count['label'] = 'Cleanliness Of The Dormitory Room';
				$dormitories_clean_count['value'] = $weekly['Dormitories']['Cleanliness Of The Dormitory Room'];
				array_push($dormitories,$dormitories_clean_count);

				$bed_damages = array();
				$bed_damages['label'] = 'Any Damages To Beds';
				$bed_damages['value'] = $weekly['Dormitories']['Any Damages To Beds'];
				array_push($dormitories,$bed_damages);

			//Store
			
				$store_room_clean = array();
				$store_room_clean['label'] = 'Store Room Cleanliness';
				$store_room_clean['value'] = $weekly['Store']['Store Room Cleanliness'];
				array_push($store,$store_room_clean);

				$storeroom_clean_count = array();
				$storeroom_clean_count['label'] = 'Cleanliness of The Store Room';
				$storeroom_clean_count['value'] = $weekly['Store']['Cleanliness of The Store Room'];
				array_push($store,$storeroom_clean_count);				

				$iteams_storage = array();
				$iteams_storage['label'] = 'Proper Storage of ITEMS';
				$iteams_storage['value'] = $weekly['Store']['Proper Storage of ITEMS'];
				array_push($store,$iteams_storage);

				$default_iteams = array();
				$default_iteams['label'] = 'Any Default Items Issued';
				$default_iteams['value'] = $weekly['Store']['Any Default Items Issued'];
				array_push($store,$default_iteams);

			//waste management
			
				$seperate_inorganic = array();
				$seperate_inorganic['label'] = 'Separate dumping of Inorganic waste';
				$seperate_inorganic['value'] = $weekly['Waste Management']['Separate dumping of Inorganic waste'];
				array_push($waste_management,$seperate_inorganic);

				$seperate_organic = array();
				$seperate_organic['label'] = 'Separate dumping of Organic waste';
				$seperate_organic['value'] = $weekly['Waste Management']['Separate dumping of Organic waste'];
				array_push($waste_management,$seperate_organic);

				$dustbins = array();
				$dustbins['label'] = 'Dustbins';
				$dustbins['value'] = $weekly['Waste Management']['Dustbins'];
				array_push($waste_management,$dustbins);*/


			}


			$sanitation_output['campus']         		= json_encode($campus);
			$sanitation_output['toilets']      	   		= json_encode($toilets);
			$sanitation_output['kitchen']  	   			= json_encode($kitchen);
			$sanitation_output['water_supply'] 		    = json_encode($water_supply);
			$sanitation_output['dormitories'] 			= json_encode($dormitories);
			$sanitation_output['store'] 		    	= json_encode($store);
			$sanitation_output['waste_management'] 		= json_encode($waste_management);
			$sanitation_output['external_attachments']  = json_encode($external_attachments);
			$sanitation_output['toilet_external_attachments']  = json_encode($toilet_external_attachments);
			$sanitation_output['kitchen_external_attachments']  = json_encode($kitchen_external_attachments);

			return $sanitation_output;
		
		}
	}
	
	public function insert_request_note($post)
	{
		$token = $this->ci->panacea_common_model->insert_request_note($post);

		return $token;
	}
	
	
	public function chronic_pie_view(){
		
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_chronic_request();
		foreach ($request_report as $value){

			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		return $this->data;
	}

	public function hospitalized_pie_view(){
		
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_hospitalized_request();
		foreach ($request_report as $value){

			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		return $this->data;
	}
	
	public function update_chronic_request_pie($status_type){
		
		$count = 0;
		$request_report = $this->ci->panacea_common_model->update_chronic_request_pie($status_type);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		return $this->data;
	}
	
	public function add_news_feed() {
		$user_data = $this->ci->session->userdata ( "customer" );

		if ((file_exists ( $_FILES ['file'] ['tmp_name'] [0] ) || is_uploaded_file ( $_FILES ['file'] ['tmp_name'] [0] ))) {
			$files_attach = [ ];
			
			$file_path = UPLOADFOLDERDIR . 'public/news_feeds/tswreis/';
			if (! is_dir ( $file_path )) {
				mkdir ( $file_path, 0777, TRUE );
			}
			
			$config ['upload_path'] = $file_path;
			$config ['allowed_types'] = "*";
			$config ['remove_spaces'] = TRUE;
			$config ['encrypt_name'] = TRUE;
			$this->ci->load->library ( 'upload' );
			$this->ci->load->helper ( 'file' );
			$files = $_FILES;
			$cpt = count ( $_FILES ['file'] ['name'] );
			for($i = 0; $i < $cpt; $i ++) {
				$_FILES ['file'] ['name'] = $files ['file'] ['name'] [$i];
				$_FILES ['file'] ['type'] = $files ['file'] ['type'] [$i];
				$_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'] [$i];
				$_FILES ['file'] ['error'] = $files ['file'] ['error'] [$i];
				$_FILES ['file'] ['size'] = $files ['file'] ['size'] [$i];
				
				$this->ci->upload->initialize ( $config );
				
				if ($this->ci->upload->do_upload ( 'file' )) {
					$updata = array (
						'upload_data' => $this->ci->upload->data () 
					);
					$attach = [ ];
					$attach ["file_client_name"] = $updata ['upload_data'] ['client_name'];
					$attach ["file_encrypted_name"] = $updata ['upload_data'] ['file_name'];
					$attach ["file_path"] = $updata ['upload_data'] ['file_relative_path'];
					$attach ["file_size"] = $updata ['upload_data'] ['file_size'];
					
					array_push ( $files_attach, $attach );
				} else {
					$data ['message'] = $this->ci->upload->display_errors ();
					$data['return_error'] = true;
					return $data;
				}
			}
			$news_data = array (
				"news_feed" => $_POST ["news_feed"],
				"display_date" => $_POST ['time'],
				"username" => $user_data ['username'],
				"email" => $user_data ['email'],
				"file_attachment" => $files_attach 
			);
			$token = $this->ci->power_of_ten_common_model->add_news_feed( $news_data );
			if ($token) {
				$data['return_error'] = false;
				return $data;
			} else {
				$data ['message'] = "Failed to add news feed.";
				$data['return_error'] = true;
				return $data;
			}
		} else {
			$news_data = array (
				"news_feed" => $_POST ["news_feed"],
				"display_date" => $_POST ['time'],
				"username" => $user_data ['username'],
				"email" => $user_data ['email'] 
			);

			
			$token = $this->ci->power_of_ten_common_model->add_news_feed( $news_data );
			if ($token) {
				$data['return_error'] = false;
				return $data;
			} else {
				$data ['message'] = "Failed to add news feed.";
				$data['return_error'] = true;
				return $data;
			}
		}
	}
	
	public function delete_news_feed( $nf_id ){
		$news_data = $this->ci->power_of_ten_common_model->get_news_feed($nf_id);
		if(isset($news_data['file_attachment'])){
			foreach($news_data['file_attachment'] as $file => $file_data){
				unlink($file_data['file_path']);
			}
		}
		$this->ci->power_of_ten_common_model->delete_news_feed ( $nf_id );
		return true;
	}
	
	public function update_news_feed() {
		
		$user_data = $this->ci->session->userdata ( "customer" );

		$news_data = $this->ci->panacea_common_model->get_news_feed($_POST['news_id']);
		$news_id = $news_data['_id'];
		$file_path = UPLOADFOLDERDIR . 'public/news_feeds/tswreis/';
		if(isset($_POST['delete_files'])){
			$delete_files_arr = explode("^^", $_POST['delete_files']);
			foreach ($delete_files_arr as $delete_file){
				foreach($news_data['file_attachment'] as $file => $file_data){
					if($file_data['file_encrypted_name'] == $delete_file){
						unlink($file_data['file_path']);
						unset($news_data['file_attachment'][$file]);
					}
				}
			}
		}

		if ((file_exists ( $_FILES ['file'] ['tmp_name'] [0] ) || is_uploaded_file ( $_FILES ['file'] ['tmp_name'] [0] ))) {
			$files_attach = [ ];
			if (! is_dir ( $file_path )) {
				mkdir ( $file_path, 0777, TRUE );
			}

			$config ['upload_path'] = $file_path;
			$config ['allowed_types'] = "*";
			$config ['remove_spaces'] = TRUE;
			$config ['encrypt_name'] = TRUE;
			$this->ci->load->library ( 'upload' );
			$this->ci->load->helper ( 'file' );
			$files = $_FILES;
			$cpt = count ( $_FILES ['file'] ['name'] );
			for($i = 0; $i < $cpt; $i ++) {
				$_FILES ['file'] ['name'] = $files ['file'] ['name'] [$i];
				$_FILES ['file'] ['type'] = $files ['file'] ['type'] [$i];
				$_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'] [$i];
				$_FILES ['file'] ['error'] = $files ['file'] ['error'] [$i];
				$_FILES ['file'] ['size'] = $files ['file'] ['size'] [$i];

				$this->ci->upload->initialize ( $config );

				if ($this->ci->upload->do_upload ( 'file' )) {
					$updata = array (
						'upload_data' => $this->ci->upload->data ()
					);
					$attach = [ ];
					$attach ["file_client_name"] = $updata ['upload_data'] ['client_name'];
					$attach ["file_encrypted_name"] = $updata ['upload_data'] ['file_name'];
					$attach ["file_path"] = $updata ['upload_data'] ['file_relative_path'];
					$attach ["file_size"] = $updata ['upload_data'] ['file_size'];

					array_push ( $files_attach, $attach );
				} else {
					$data ['message'] = $this->ci->upload->display_errors ();
					$data['return_error'] = true;
					return $data;
				}
			}
			if(isset($news_data['file_attachment'])){
				$news_data['file_attachment'] = array_merge($news_data['file_attachment'],$files_attach);
			}else{
				$news_data['file_attachment'] = $files_attach;
			}
			
			$news_data = array (
				"news_feed" => $_POST ["news_feed"],
				"display_date" => $_POST ['time'],
				"username" => $user_data ['username'],
				"email" => $user_data ['email'],
				"file_attachment" => $news_data['file_attachment']
			);
			$token = $this->ci->panacea_common_model->update_news_feed ( $news_data, $news_id );
			if ($token) {
				$data['return_error'] = false;
				return $data;
			} else {
				$data ['message'] = "Failed to add news feed.";
				$data['return_error'] = true;
				return $data;
			}
		} else {
			if(isset($news_data['file_attachment'])){
				$news_data = array (
					"news_feed" => $_POST ["news_feed"],
					"display_date" => $_POST ['time'],
					"username" => $user_data ['username'],
					"email" => $user_data ['email'],
					"file_attachment" => $news_data['file_attachment']
				);
			}else{
				$news_data = array (
					"news_feed" => $_POST ["news_feed"],
					"display_date" => $_POST ['time'],
					"username" => $user_data ['username'],
					"email" => $user_data ['email']
				);
			}

			$token = $this->ci->panacea_common_model->update_news_feed ( $news_data,$news_id );
			if ($token) {
				$data['return_error'] = false;
				return $data;
			} else {
				$data ['message'] = "Failed to add news feed.";
				$data['return_error'] = true;
				return $data;
			}
		}
	}
	
	public function app_access($doc_id)
	{
		$session_data = $this->ci->session->userdata("customer");
		
		if(array_key_exists("user_type",$session_data))
		{
			if($session_data['user_type'] == "HS")
			{
				$user = $this->ci->ion_auth->health_supervisor()->row();
				$username = str_replace("@","#",$user->email); 
				$health_sup = explode('.',$user->email); 
				$district_code = $health_sup[0];
				$school_code   = $health_sup[1];
				$submitted_user_type = "HS";
			}
			else if($session_data['user_type'] == "PADMIN")
			{
				$user = $this->ci->ion_auth->panacea_admin()->row();
				$username = str_replace("@","#",$user->email);  
				$submitted_user_type = "PADMIN";				
			}
			else if($session_data['user_type'] == "CCUSER")
			{
				$user = $this->ci->ion_auth->cc_user()->row();
				$username = str_replace("@","#",$user->email); 
				$submitted_user_type = "CCUSER";
			}
		}
		else
		{
			$user = $this->ci->ion_auth->user()->row();
			$username = str_replace("@","#",$user->email); 
		}
		
		$form_new_data = array();
		//create control variables
		$data['title'] = 'Document Access';
		$data['updType'] = 'Document Access';
		$data['id'] = $doc_id;
		$data['page'] = 1;
		
		
		
		$this->ci->load->model('healthcare/healthcare2016531124515424_mod','healthcare2016531124515424_mod_calls');
		
		//search the item to show in edit form
		$healthcare2016531124515424_edit = $this->ci->healthcare2016531124515424_mod_calls->find('doc_id',$doc_id);
		$data['healthcare2016531124515424_mod'] = json_decode(json_encode($healthcare2016531124515424_edit),TRUE);
		$data['stagename'] = "HS 2";
		$data['template'] = $this->ci->healthcare2016531124515424_mod_calls->get_template();
		$app_template = $this->ci->healthcare2016531124515424_mod_calls->get_template_for_create();			

		$pagenumber  = array();
		$page_data   = array();
		$file_count  = 0;

		foreach ($app_template as $pageno => $pages)
		{
			array_push($pagenumber,$pageno);
		}

		$pagecount = count($pagenumber);

		for($i=1;$i<=$pagecount;$i++)
		{
			array_push($page_data,$app_template[$i]);
		}



		for($ii=0;$ii<$pagecount;$ii++)
		{
			$widget       = array();
			$widget_final = array();
			$previous_section = "";
			$pgno = $ii +1;
			foreach($page_data[$ii] as $section => $index_array)
			{
				$widget_data = array();
				unset($index_array['dont_use_this_name']);
				foreach($index_array as $index => $value)
				{
					switch ($value['type'])
					{
						case 'retriever':
						$query_param     = $value['field_ref'];
						$collection_name = $value['coll_ref'];
						$to_be_retrieved = $value['retrieve_list'];

						$query_param = str_replace("_",".",$query_param);
						$page = 'page'.$pgno;

						$query_value = $healthcare2016531124515424_edit['doc_data']['widget_data'][$page][$section][$index];


						$retrieval_list = array();

						foreach($to_be_retrieved as $index => $value)
						{
							$value = str_replace("_",".",$value);
							$value = "doc_data.widget_data.".$value;
							array_push($retrieval_list,$value);
						}

						  //fetch document 
						$mapper_document = $this->ci->healthcare2016531124515424_mod_calls->fetch_retriever_data_model($query_param,$query_value,$retrieval_list,$collection_name);
						break;
					}

				}
			}
		}
		$data['mapper_doc'] = json_decode(json_encode($mapper_document[0]),TRUE);

		if($session_data['user_type'] == "HS")
		{
			$data['district_code'] = strtoupper($district_code);
			$data['school_code']   = $school_code;
		}


		$data['message'] = false;

		$this->data["hs_page"] = $this->ci->load->view('healthcare/healthcare2016531124515424_con/HS 2', $data, true);
		$this->data['message'] = false;

		return $this->data;
	}
	
	function hs_req_extend($form_data){
		//$form_data = json_decode($_POST['form_data'],true);
		//log_message('debug','hh____posttttttttttttttttttttttttt=====6='.print_r($form_data,true));
		
		//log_message('debug','hh____ffffffffffffffffffffffffffff=====6='.print_r($_FILES,true));
		//exit();
		
		$this->ci->load->model('healthcare/healthcare2016531124515424_mod','healthcare2016531124515424_mod_calls');				
		
		$approval_history = array();
		
		$session_data = $this->ci->session->userdata("customer");
		if(array_key_exists("user_type",$session_data))
		{
			if($session_data['user_type'] == "HS")
			{
				$user = $this->ci->ion_auth->health_supervisor()->row();
				$username = str_replace("@","#",$user->email); 
				$health_sup = explode('.',$user->email); 
				$district_code = $health_sup[0];
				$school_code   = $health_sup[1];
				$submitted_user_type = "HS";
			}
			else if($session_data['user_type'] == "PADMIN")
			{
				$user = $this->ci->ion_auth->panacea_admin()->row();
				$username = str_replace("@","#",$user->email);  
				$submitted_user_type = "PADMIN";				
			}
			else if($session_data['user_type'] == "CCUSER")
			{
				$user = $this->ci->ion_auth->cc_user()->row();
				$username = str_replace("@","#",$user->email); 
				$submitted_user_type = "CCUSER";
			}
			else if($session_data['user_type'] == "DOCTOR")
			{
				$user = $this->ci->ion_auth->panacea_doctor()->row();
				$username = str_replace("@","#",$user->email); 
				$submitted_user_type = "DOCTOR";
			}
		}
		else
		{
			$user = $this->ci->ion_auth->user()->row();
			$username = str_replace("@","#",$user->email); 
		}
		
		$healthcare2016531124515424_edit = $this->ci->healthcare2016531124515424_mod_calls->find('doc_id',$form_data['docid']);
		
		
		$reason_array = [];
		$student_name = "";
		//======================== student name======================================
		$student_name = $this->ci->panacea_common_model->get_students_uid($healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Student Info"]["Unique ID"]);
		if($student_name){
			$student_name = $student_name['doc_data']['widget_data']['page1']['Personal Information']['Name'];
			//array_push($reason_array,"Name: ".$student_name['doc_data']['widget_data']['page1']['Personal Information']['Name']);
		}else{
			//array_push($reason_array,$student_name['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']);
		}
		
		//=====================attachments check=====================================
		if((file_exists ( $_FILES ['attachments'] ['tmp_name'] [0] ) || is_uploaded_file ( $_FILES ['attachments'] ['tmp_name'] [0] ))){
			$external_final = array();

			$config['upload_path'] = UPLOADFOLDERDIR.'public/uploads/healthcare2016531124515424_con/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size'] = '4096';
			$config['encrypt_name'] = TRUE;

			$this->ci->load->library ( 'upload' );
			$this->ci->load->helper ( 'file' );
			$files = $_FILES;
			$cpt = count ( $_FILES ['attachments'] ['name'] );
			
			for($i = 0; $i < $cpt; $i ++) {
				$_FILES ['attachments'] ['name'] = $files ['attachments'] ['name'] [$i];
				$_FILES ['attachments'] ['type'] = $files ['attachments'] ['type'] [$i];
				$_FILES ['attachments'] ['tmp_name'] = $files ['attachments'] ['tmp_name'] [$i];
				$_FILES ['attachments'] ['error'] = $files ['attachments'] ['error'] [$i];
				$_FILES ['attachments'] ['size'] = $files ['attachments'] ['size'] [$i];

				$this->ci->upload->initialize ( $config );

				if ($this->ci->upload->do_upload ( 'attachments' )) {
					$updata = array (
						'upload_data' => $this->ci->upload->data ()
					);
					$attach = [ ];
					$attach ["file_client_name"] = $updata ['upload_data'] ['client_name'];
					$attach ["file_encrypted_name"] = $updata ['upload_data'] ['file_name'];
					$attach ["file_path"] = $updata ['upload_data'] ['file_relative_path'];
					$attach ["file_size"] = $updata ['upload_data'] ['file_size'];
					$date_attachment = date ("dmyHis").$i;
					$external_data_array = array(
						"DFF_EXTERENAL_FILE_ATTACHMENT".$date_attachment => array(
							"file_client_name" =>$updata ['upload_data']['client_name'],
							"file_encrypted_name" =>$updata ['upload_data']['file_name'],
							"file_path" =>$updata ['upload_data']['file_relative_path'],
							"file_size" =>$updata ['upload_data']['file_size']
						)

					);

					$external_final = array_merge($external_final,$external_data_array);
				} else {
					
					$data ['message'] = $this->ci->upload->display_errors ();
					$data['return_error'] = true;
					return $data;
				}
			}
			
			if(isset($healthcare2016531124515424_edit['doc_data']['external_attachments']))
			{

				$external_merged_data = array_merge($healthcare2016531124515424_edit['doc_data']['external_attachments'],$external_final);
				$healthcare2016531124515424_edit['doc_data']['external_attachments'] = array_replace_recursive($healthcare2016531124515424_edit['doc_data']['external_attachments'],$external_merged_data);
			}
			else
			{
				$healthcare2016531124515424_edit['doc_data']['external_attachments'] = $external_final;
			}
			
			array_push($reason_array,"Files attached");
		}		
		
		
		//=====================symptoms check=====================================
		if(isset($form_data['ac_page1_ProblemInfo_Identifier[]'])){
			$symptoms = explode("^^",$form_data['ac_page1_ProblemInfo_Identifier[]']);
		}else{
			$symptoms = [];
		}
		
		$doc_symptoms = $healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Problem Info"]["Identifier"];

		$arraysAreEqual = ($symptoms == $doc_symptoms);
		
		
				// echo print_r($symptoms,true);
				// echo print_r($doc_symptoms,true);
				// echo print_r($arraysAreEqual,true);
				// exit();
		if (!($symptoms == $doc_symptoms)) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Problem Info"]["Identifier"] = $symptoms;
			array_push($reason_array,"Symptoms changed");
		}
		
		//=====================Description check=====================================
		$description = trim($form_data['page2_ProblemInfo_Description']);
		$doc_description = trim($healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Problem Info"]["Description"]);
		
		if ($description != $doc_description) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Problem Info"]["Description"] = $description;
			array_push($reason_array,"Description changed");
		}
		
		//=====================Requesttype check=====================================
		$req_type = $form_data['page2_ReviewInfo_RequestType'];
		$doc_req_type = $healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Request Type"];
		
		if ($req_type != $doc_req_type) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Request Type"] = $req_type;
			array_push($reason_array,"Request type changed");
		}
		
		//=====================status check=====================================
		$status_type = $form_data['page2_ReviewInfo_Status'];
		$doc_status_type = $healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Status"];
		
		if ($status_type != $doc_status_type) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Status"] = $status_type;
			array_push($reason_array,"Status changed");
		}

		//POST DATA
		$redirected_stage   = "Doctor";
		$current_stage      = "HS 2";
		$doc_id 			= $form_data['docid'];
		$reason             = implode(", ",$reason_array);
		$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		$disapproving_user	= $username;
		$stage_name 		= "HS 2";
		
		
		$history_array = [];
		
		foreach($healthcare2016531124515424_edit['history'] as $stg_history){
			array_push($history_array, $stg_history);
		}
		$healthcare2016531124515424_edit['history'] = $history_array;
		
		$doc_update = $this->ci->panacea_common_model->update_doc_for_disapprove($healthcare2016531124515424_edit);
		$approval_data = array(
			"current_stage"    	=> $stage_name,
			"approval"		        => "false",
			"disapproved_by"	    => $disapproving_user,
			"submitted_by"			=> $disapproving_user,
			"time"		            => date('Y-m-d H:i:s'),
			"reason"	            => $reason,
			"redirected_stage"		=> $redirected_stage,
			"redirected_user"		=> "multi_user_stage",
			"submitted_user_type"	=> $submitted_user_type); 

		$approval_history = $this->ci->healthcare2016531124515424_mod_calls->get_approval_history($doc_id);
		array_push($approval_history,$approval_data);
		
		$select = array("workflow.Doctor");
		$stage_details = $this->ci->panacea_common_model->get_workflow_stage_details('healthcare2016531124515424','applications',$select);
		
		foreach($stage_details["workflow"]['Doctor']['UsersList'] as $user){
			$stage_type = $stage_details["workflow"]['Doctor']["Stage_Type"];
			unset($healthcare2016531124515424_edit['_id']);
			
			$uid_arr = explode("_",$healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Student Info"]["Unique ID"]);
			$hs_user_col = strtolower($uid_arr[0]).".".$uid_arr[1].".hs#gmail.com";
			$this->ci->panacea_common_model->delete_doc_from_user_col($doc_id,$hs_user_col);

			$this->healthcare2016531124515424_mod = $this->ci->healthcare2016531124515424_mod_calls->web_disapprove_hs($doc_id,$approval_history,$user,$redirected_stage,$disapproving_user,$stage_type,$current_stage,$notification_param,$reason,$healthcare2016531124515424_edit,$req_type);
		}
		
		if ( $this->healthcare2016531124515424_mod ) // the information has therefore been successfully saved in the db
		{
			$this->ci->session->set_flashdata('message',lang('web_disapprover_success'));
		}
		else
		{
			$this->ci->session->set_flashdata('message',lang('web_disapprover_failed'));
		}
		$data['return_error'] = false;
		return $data;
	}
	
	function doctor_req_extend($form_data){
		//$form_data = json_decode($_POST['form_data'],true);
		//log_message('debug','hh____posttttttttttttttttttttttttt=====6='.print_r($form_data,true));
		
		//log_message('debug','hh____ffffffffffffffffffffffffffff=====6='.print_r($_FILES,true));
		//exit();
		
		$this->ci->load->model('healthcare/healthcare2016531124515424_mod','healthcare2016531124515424_mod_calls');				
		
		$approval_history = array();
		
		$session_data = $this->ci->session->userdata("customer");
		if(array_key_exists("user_type",$session_data))
		{
			if($session_data['user_type'] == "HS")
			{
				$user = $this->ci->ion_auth->health_supervisor()->row();
				$username = str_replace("@","#",$user->email); 
				$health_sup = explode('.',$user->email); 
				$district_code = $health_sup[0];
				$school_code   = $health_sup[1];
				$submitted_user_type = "HS";
			}
			else if($session_data['user_type'] == "PADMIN")
			{
				$user = $this->ci->ion_auth->panacea_admin()->row();
				$username = str_replace("@","#",$user->email);  
				$submitted_user_type = "PADMIN";				
			}
			else if($session_data['user_type'] == "CCUSER")
			{
				$user = $this->ci->ion_auth->cc_user()->row();
				$username = str_replace("@","#",$user->email); 
				$submitted_user_type = "CCUSER";
			}
			else if($session_data['user_type'] == "DOCTOR")
			{
				$user = $this->ci->ion_auth->panacea_doctor()->row();
				$username = str_replace("@","#",$user->email); 
				$submitted_user_type = "DOCTOR";
			}
		}
		else
		{
			$user = $this->ci->ion_auth->user()->row();
			$username = str_replace("@","#",$user->email); 
		}
		
		$healthcare2016531124515424_edit = $this->ci->healthcare2016531124515424_mod_calls->find('doc_id',$form_data['docid']);
		
		
		$reason_array = [];
		$student_name = "";
		//======================== student name======================================
		$student_name = $this->ci->panacea_common_model->get_students_uid($healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Student Info"]["Unique ID"]);
		if($student_name){
			$student_name = $student_name['doc_data']['widget_data']['page1']['Personal Information']['Name'];
			//array_push($reason_array,"Name: ".$student_name['doc_data']['widget_data']['page1']['Personal Information']['Name']);
		}else{
			//array_push($reason_array,$student_name['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']);
		}
		
		//=====================attachments check=====================================
		if((file_exists ( $_FILES ['attachments'] ['tmp_name'] [0] ) || is_uploaded_file ( $_FILES ['attachments'] ['tmp_name'] [0] ))){
			$external_final = array();

			$config['upload_path'] = UPLOADFOLDERDIR.'public/uploads/healthcare2016531124515424_con/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size'] = '4096';
			$config['encrypt_name'] = TRUE;

			$this->ci->load->library ( 'upload' );
			$this->ci->load->helper ( 'file' );
			$files = $_FILES;
			$cpt = count ( $_FILES ['attachments'] ['name'] );
			
			for($i = 0; $i < $cpt; $i ++) {
				$_FILES ['attachments'] ['name'] = $files ['attachments'] ['name'] [$i];
				$_FILES ['attachments'] ['type'] = $files ['attachments'] ['type'] [$i];
				$_FILES ['attachments'] ['tmp_name'] = $files ['attachments'] ['tmp_name'] [$i];
				$_FILES ['attachments'] ['error'] = $files ['attachments'] ['error'] [$i];
				$_FILES ['attachments'] ['size'] = $files ['attachments'] ['size'] [$i];

				$this->ci->upload->initialize ( $config );

				if ($this->ci->upload->do_upload ( 'attachments' )) {
					$updata = array (
						'upload_data' => $this->ci->upload->data ()
					);
					$attach = [ ];
					$attach ["file_client_name"] = $updata ['upload_data'] ['client_name'];
					$attach ["file_encrypted_name"] = $updata ['upload_data'] ['file_name'];
					$attach ["file_path"] = $updata ['upload_data'] ['file_relative_path'];
					$attach ["file_size"] = $updata ['upload_data'] ['file_size'];
					$date_attachment = date ("dmyHis").$i;
					$external_data_array = array(
						"DFF_EXTERENAL_FILE_ATTACHMENT".$date_attachment => array(
							"file_client_name" =>$updata ['upload_data']['client_name'],
							"file_encrypted_name" =>$updata ['upload_data']['file_name'],
							"file_path" =>$updata ['upload_data']['file_relative_path'],
							"file_size" =>$updata ['upload_data']['file_size']
						)

					);

					$external_final = array_merge($external_final,$external_data_array);
				} else {
					
					$data ['message'] = $this->ci->upload->display_errors ();
					$data['return_error'] = true;
					return $data;
				}
			}
			
			if(isset($healthcare2016531124515424_edit['doc_data']['external_attachments']))
			{

				$external_merged_data = array_merge($healthcare2016531124515424_edit['doc_data']['external_attachments'],$external_final);
				$healthcare2016531124515424_edit['doc_data']['external_attachments'] = array_replace_recursive($healthcare2016531124515424_edit['doc_data']['external_attachments'],$external_merged_data);
			}
			else
			{
				$healthcare2016531124515424_edit['doc_data']['external_attachments'] = $external_final;
			}
			
			array_push($reason_array,"Files attached");
		}		
		
		
		//=====================symptoms check=====================================
		if(isset($form_data['ac_page1_ProblemInfo_Identifier[]'])){
			$symptoms = explode("^^",$form_data['ac_page1_ProblemInfo_Identifier[]']);
		}else{
			$symptoms = [];
		}
		
		$doc_symptoms = $healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Problem Info"]["Identifier"];

		$arraysAreEqual = ($symptoms == $doc_symptoms);
		
		
				// echo print_r($symptoms,true);
				// echo print_r($doc_symptoms,true);
				// echo print_r($arraysAreEqual,true);
				// exit();
		if (!($symptoms == $doc_symptoms)) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Problem Info"]["Identifier"] = $symptoms;
			array_push($reason_array,"Symptoms changed");
		}
		
		//=====================Description check=====================================
		$description = trim($form_data['page2_ProblemInfo_Description']);
		$doc_description = trim($healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Problem Info"]["Description"]);
		
		if ($description != $doc_description) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Problem Info"]["Description"] = $description;
			array_push($reason_array,"Description changed");
		}

		//=====================Doctor Summary=====================================
		$doctor_summary = trim($form_data['page2_DiagnosisInfo_DoctorSummary']);
		$doc_doctor_summary = trim($healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Doctor Summary"]);
		
		if ($doctor_summary != $doc_doctor_summary) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Doctor Summary"] = $doctor_summary;
			array_push($reason_array,"DoctorSummary changed");
		}
		//=====================Doctor Advice=====================================
		$doctor_advice = trim($form_data['page2_DiagnosisInfo_DoctorAdvice']);
		$doc_doctor_advice = trim($healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Doctor Advice"]);
		
		if ($doctor_advice != $doc_doctor_advice) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Doctor Advice"] = $doctor_advice;
			array_push($reason_array,"DoctorAdvice changed");
		}
		//=====================Doctor Prescription=====================================
		$doctor_prescription = trim($form_data['page2_DiagnosisInfo_Prescription']);
		$doc_doctor_prescription = trim($healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Prescription"]);
		
		if ($doctor_prescription != $doc_doctor_prescription) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Diagnosis Info"]["Prescription"] = $doctor_prescription;
			array_push($reason_array,"DoctorPrescription changed");
		}
		//=====================Requesttype check=====================================
		$req_type = $form_data['page2_ReviewInfo_RequestType'];
		$doc_req_type = $healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Request Type"];
		
		if ($req_type != $doc_req_type) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Request Type"] = $req_type;
			array_push($reason_array,"Request type changed");
		}
		
		//=====================status check=====================================
		$status_type = $form_data['page2_ReviewInfo_Status'];
		$doc_status_type = $healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Status"];
		
		if ($status_type != $doc_status_type) {
			$healthcare2016531124515424_edit['doc_data']['widget_data']["page2"]["Review Info"]["Status"] = $status_type;
			array_push($reason_array,"Status changed");
		}

		//POST DATA
		$redirected_stage   = "HS 2";
		$current_stage      = "Doctor";
		$doc_id 			= $form_data['docid'];
		$reason             = implode(", ",$reason_array);
		$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		$disapproving_user	= $username;
		$stage_name 		= "Doctor";
		

		
		$history_array = [];
		
		foreach($healthcare2016531124515424_edit['history'] as $stg_history){
			array_push($history_array, $stg_history);
		}
		$healthcare2016531124515424_edit['history'] = $history_array;
		
		$doc_update = $this->ci->panacea_common_model->update_doc_for_disapprove($healthcare2016531124515424_edit);

		/*$approval_data = array(
				 "current_stage"    	=> $stage_name,
                 "approval"		        => "false",
                 "disapproved_by"	    => $disapproving_user,
				 "submitted_by"			=> $disapproving_user,
                 "time"		            => date('Y-m-d H:i:s'),
                 "reason"	            => $reason,
                 "redirected_stage"		=> $redirected_stage,
                 "redirected_user"		=> "multi_user_stage",
                 "submitted_user_type"	=> $submitted_user_type); */

                 $approval_data = array(
                 	"current_stage" => "Doctor",
                 	"approval" => "true",
                 	"submitted_by" => $session_data['email'],
                 	"submitted_by_name" => $session_data['username'],
                 	"time" => date('Y-m-d H:i:s'));

                 $doctor_type = $session_data['user_type'];

                 $approval_history = $this->ci->healthcare2016531124515424_mod_calls->get_approval_history($doc_id);
                 array_push($approval_history,$approval_data);

                 $select = array("workflow.Doctor");
                 $stage_details = $this->ci->panacea_common_model->get_workflow_stage_details('healthcare2016531124515424','applications',$select);

                 foreach($stage_details["workflow"]['Doctor']['UsersList'] as $user){
                 	$stage_type = $stage_details["workflow"]['Doctor']["Stage_Type"];
                 	unset($healthcare2016531124515424_edit['_id']);

                 	$uid_arr = explode("_",$healthcare2016531124515424_edit['doc_data']['widget_data']["page1"]["Student Info"]["Unique ID"]);
                 	$hs_user_col = strtolower($uid_arr[0]).".".$uid_arr[1].".hs#gmail.com";
			//$this->ci->panacea_common_model->delete_doc_from_user_col($doc_id,$user);

                 	$this->healthcare2016531124515424_mod = $this->ci->healthcare2016531124515424_mod_calls->web_disapprove_doctor($doc_id,$approval_history,$hs_user_col,$user,$doctor_type,$redirected_stage,$disapproving_user,$stage_type,$current_stage,$notification_param,$reason,$healthcare2016531124515424_edit,$req_type);
                 }

		if ( $this->healthcare2016531124515424_mod ) // the information has therefore been successfully saved in the db
		{
			$this->ci->session->set_flashdata('message',lang('web_disapprover_success'));
		}
		else
		{
			$this->ci->session->set_flashdata('message',lang('web_disapprover_failed'));
		}
		$data['return_error'] = false;
		return $data;
	}
	
	public function panacea_update_personal_ehr_uid($post)
	{
		$docs = $this->ci->panacea_common_model->get_update_personal_ehr_uid($post['uid']);
		$this->data['docs'] = $docs['screening'];
		log_message("debug","update personal info libbbb====5032".print_r($docs,true));
		log_message("debug","update personal info libbbb====5033".print_r($this->data['docs'],true));
		//$this->data['docs_requests'] = $docs['request'];
		/* 
		$docs = $this->ci->panacea_common_model->get_student_hospital_report($post['uid']);
		$this->data['hospital_reports'] = $docs['get_hospital_report'];
		$this->data['docscount'] = count($this->data['docs']); */

		return $this->data;
	}
	
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

		/*  $this->check_for_admin();
		$this->check_for_plan('deleteAll'); */
		
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


	function school_screening_file_import($post)
	{
		/*ini_set('max_execution_time', 0); 
		ini_set('memory_limit','3000M');
		ini_set('post_max_size', '3000M');
		ini_set('upload_max_filesize', '3000M'); */
		$uploaddir = ZIP_PANACEA;
		
		$config['upload_path'] 		= $uploaddir;
		$config['upload_mednote'] = './uploaddir/public/uploads/healthcare2016226112942701_con/MedNote';
		
		$config['allowed_types'] 	= 'zip';
		
		//$config['max_size']			= '0';
		//$config['max_width']  		= '0';
		//$config['max_height']  		= '0';
		//$config['remove_spaces']  	= TRUE;
		//$config['encrypt_name']  	= TRUE;


		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');
		
		//log_message("debug","file====3998".print_r($file,true));
		//$config['max_size']    = '';
        //$this->load->library('upload', $config);
	//	log_message("error","_FILES==1====3998".print_r($_FILES,true));
		//$_FILES['userfile'] = $this->reArrayFiles($_FILES['userfile']);
		
		
		if ( ! $this->ci->upload->do_upload())
		{
			$error = array('error' => $this->ci->upload->display_errors());
			
			$this->ci->_render_page('panacea_admins/panacea_zipfile_import_view',$error);
		}
		else
		{
			$data = array('upload_data' => $this->ci->upload->data());
			$zip = new ZipArchive;
			$file = $data['upload_data']['full_path'];
			
			chmod($file,0777);
			if ($zip->open($file) === TRUE) {
				$zip->extractTo('./uploaddir/public/uploads/healthcare2016226112942701_con/');
						//$zip->extractTo->$uploaddir;
				$zip->close();
						//echo 'ok';
			} else { 
				echo 'failed';
			}

			if (unlink($file)) {   
				echo "success";
			} else {
				echo "fail";    
			}  

			if($this->import_screening_lib($post) == TRUE )
			{
				$is_delete =  $this->deleteAll($config['upload_mednote']);
				
					
			}
			else
			{
				 //redirect('panacea_cc/file_upload');
				return false;
			} 
			return TRUE;
			$this->data['message'] = "";
			$this->ci->_render_page('panacea_admins/panacea_zipfile_import_view',$this->data);
		}

	}

	public function import_screening_lib($post)
	{  
		/*ini_set('max_execution_time', 0); 
		ini_set('memory_limit','3000M');
		ini_set('post_max_size', '3000M');
		ini_set('upload_max_filesize', '3000M');*/
		$unique_id 		= "";
		$mobile_status  = "";
		$dob_status 	= "";
		$name_status    = "";
		$student    = "";
		$image_data	    = array();
		$sign_data 		= array();
		$dentist_sign_data =array();
		$opthomologist_sign_data =array();
		$audiologist_sign_data =array();

		$attachments_sign_data =array();
		$attachments_sign_data_push =array();
		$attachments_sign_replace =array();

		$subjective_attachments_sign_data =array();
		$subjective_attachments_sign_data_push =array();
		$subjective_attachments_sign_replace =array();

		$ocular_attachments_sign_data =array();
		$ocular_attachments_sign_data_push =array();
		$ocular_attachments_sign_replace =array();

		$general_id   = "";
		$dentist_id = "";
		$status = "";
		$failed_status = "";
		$name_status_fail = "";
		$mobile_status_fail = "";
		$description = "";
		$photo_status = "";
		$version = "";

		$eye_lids      = "";
		$conjunctiva   = "";
		$oral_hygiene  = "";
		$carious_teeth = "";
		$date_of_exam  = "";

		$this->ci->load->library('excel');
		$configUpload['upload_path'] = './uploaddir/public/uploads/healthcare2016226112942701_con/MedNote/Screening.xls';

					 //$configUpload['upload_folder'] = './uploaddir/public/zipfile_upload/MedNote/Attachments';

					 //$configUpload['mednote'] = './uploads/MedNote/';
		//log_message("error","upload import===========47".print_r($configUpload['upload_path'],true));
					 /* $configUpload['allowed_types'] = 'xls|xlsx|csv';
					 $configUpload['max_size'] = '5000';
					 $this->load->library('upload', $configUpload); */
					 
					 //$this->upload->do_upload('userfile');	
					 //$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
					 //$file_name = $upload_data['file_name']; //uploded file name
					 //$extension=$upload_data['file_ext'];    // uploded file extension
					 //$reader = PHPExcel_IOFactory::createReader("Excel5");
					 //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
						$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
					  //Set to read only
						$objReader->setReadDataOnly(true); 		  
					//Load excel file
						$objPHPExcel = PHPExcel_IOFactory::load("./uploaddir/public/uploads/healthcare2016226112942701_con/MedNote/Screening.xls");
					 //$objPHPExcel=$objReader->load('./uploads/naresh/MedNote/Screening.xls');

					/*$header_array = array(
					"hospital unique id","student name","father name","school name","district","date of birth","mobile number","ad no","section","class","date of exam","height","weight","bmi","pulse","b p","h b","blood group","description1","skin conditions","without right","without left","with right","with left","colour blindness right","colour blindness left","description2","auditory screening right","auditory screening left","description3","oral hygiene","carious teeth","flourosis","orthodonic treatment","indication treatment","description4","abnormalities","ortho","postural","defects at birth","deficiencies","childhood diseases","dd&disability","speech screening","n a d","vision screen referral","auditory screen referral","dental check referral","dc 11","dc 12","dc 13","dc 14","imagepath","general physian sign","dentist sign","opthomologist sign","audiologist sign","history","attachments"
				);*/

				//$header_array = array("hospital unique id","student name","father name","school name","district","date of birth","mobile number","ad no","section","class","date of exam","height","weight","bmi","pulse","b p","h b","blood group","description1","skin conditions","without right","without left","with right","with left","colour blindness right","colour blindness left","description2","auditory screening right","auditory screening left","description3","oral hygiene","carious teeth","flourosis","orthodonic treatment","indication treatment","description4","abnormalities","ortho","postural","defects at birth","deficiencies","childhood diseases","dd&disability","speech screening","n a d","vision screen referral","auditory screen referral","dental check referral","dc 11","dc 12","dc 13","dc 14","imagepath","general physian sign","dentist sign","opthomologist sign","audiologist sign","history","attachments","treatment","gender","root canal treatment","crowns","fixed partial denture","curettage","estimated amount","eye lids","conjunctiva","cornea","pupil","complaints","wearing spectacles","subjective refraction","ocular diagnosis");//,"subjective refraction attachments","ocular diagnosis attachments");
				//Screening Import Header Names
				$header_array = array("hospital unique id","student name","father name","school name","district","date of birth","mobile number","ad no","section","class","date of exam","height","weight","bmi","pulse","b p","h b","blood group","description1","skin conditions","presenting vision right","presenting vision left","with right","with left","colour blindness right","colour blindness left","description2","auditory screening right","auditory screening left","description3","oral hygiene","carious teeth","flourosis","orthodonic treatment","indication treatment","description4","abnormalities","ortho","postural","defects at birth","deficiencies","childhood diseases","dd&disability","speech screening","n a d","vision screen referral","auditory screen referral","dental check referral","dc 11","dc 12","dc 13","dc 14","imagepath","general physian sign","dentist sign","opthomologist sign","audiologist sign","history","attachments","treatment","gender","root canal treatment","crowns","fixed partial denture","curettage","estimated amount","eye lids","conjunctiva","cornea","pupil","complaints","wearing spectacles","subjective refraction","ocular diagnosis","subjective refraction attachments","ocular diagnosis attachments","version");

				$check_col_array = [];  
				
				$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				
				foreach ($cellIterator as $cell) {
					//echo $cell->getValue();
					array_push($check_col_array,strtolower($cell->getValue()));
				}
				//log_message('error','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));
				//echo print_r($check_col_array,TRUE);exit();
				$check = array_diff($header_array,$check_col_array);
				//log_message('error','chkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'.print_r($check,true));
				//
				if (count($check)==0)
				{
					
					$arr_data = [];
					$total_rows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel   
					//log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));					
					$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);       

					for($each_row=2 ; $each_row<=$total_rows ; $each_row++)
					{
						unset($attachments_sign_data_push);
						$attachments_sign_data_push = array();
						unset($subjective_attachments_sign_data_push);
						$subjective_attachments_sign_data_push = array();
						unset($ocular_attachments_sign_data_push);
						$ocular_attachments_sign_data_push = array();

						$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
						
						$cellIterator = $row->getCellIterator();
						
						$cellIterator->setIterateOnlyExistingCells(false);
						$header_row = 0;

						foreach ($cellIterator as $cell)
						{

							$data_value = $cell->getValue();
							

							if($check_col_array[$header_row] == "hospital unique id")
							{ 
								if(!empty($data_value))
								{
									$unique_id = $data_value;
									
								}else{
									ob_start();
									echo "Hospital Unique ID is Empty";
									$unique_id = ob_get_clean();
								}

							}
							if($check_col_array[$header_row] == "eye lids")
							{
								$eye_lids = $data_value;								
							}
							if($check_col_array[$header_row] == "conjunctiva")
							{
								$conjunctiva = $data_value;
							}
							
							if($check_col_array[$header_row] == "oral hygiene")
							{
								$oral_hygiene = $data_value;
							}
							if($check_col_array[$header_row] == "carious teeth")
							{
								$carious_teeth = $data_value;
							}
							if($check_col_array[$header_row] == "date of exam")
							{
								$date_of_exam = $data_value;

							}
							/*if($check_col_array[$header_row] == "general physian sign")
							{ 
								$general_id = $data_value;

								$sign_replace = str_replace("/storage/emulated/0/MedNote/Signatures/","",$general_id);
								
							} 
							if($check_col_array[$header_row] == "dentist sign")
							{ 
								$dentist_id = $data_value;
								$dentist_sign_replace = str_replace("/storage/emulated/0/MedNote/Signatures/","",$dentist_id);
							} 
							if($check_col_array[$header_row] == "opthomologist sign")
							{ 
								$opthomologist_id = $data_value;
								$opthomologist_sign_replace = str_replace("/storage/emulated/0/MedNote/Signatures/","",$opthomologist_id);
							} 
							if($check_col_array[$header_row] == "audiologist sign")
							{ 
								$audiologist_id = $data_value;

								$audiologist_sign_replace = str_replace("/storage/emulated/0/MedNote/Signatures/","",$audiologist_id);
							} */
							if($check_col_array[$header_row] == "attachments")
							{ 
								$attachments_id = $data_value;
								$attachments_sign_replace = str_replace(", ",",",$attachments_id);
								$attachments_sign_replace = explode(",",$attachments_sign_replace);

							} 
							if($check_col_array[$header_row] == "subjective refraction attachments")
							{ 

								$subjective_attachments_id = $data_value;
								if(!empty($subjective_attachments_id))
								{
									//log_message('error','subjective_attachments_id=====5998'.print_r($data_value,TRUE));
								}
								
								$subjective_attachments_sign_replace = str_replace(", ",",",$subjective_attachments_id);
								$subjective_attachments_sign_replace = explode(",",$subjective_attachments_sign_replace);
								//log_message('error','subjective_attachments_sign_replace=====5998'.print_r($subjective_attachments_sign_replace,TRUE));

							} 
							if($check_col_array[$header_row] == "ocular diagnosis attachments")
							{ 
								$ocular_attachments_id = $data_value;
								$ocular_attachments_sign_replace = str_replace(", ",",",$ocular_attachments_id);
								$ocular_attachments_sign_replace = explode(",",$ocular_attachments_sign_replace);

							} 
							if($check_col_array[$header_row] == "version")
							{
								$version = $data_value;
							}
							if($check_col_array[$header_row] == "history")
							{
								$history_details = $data_value;
								$history_1 = str_replace("[","",$history_details);
								$history_2 = str_replace("]","",$history_1);
								$history_all = explode(",",$history_2);
								$tailored_history = array();
								$tailored_history['current_stage'] = "stage1";
								$tailored_history['doc_owner'] = $history_all[0];
								$tailored_history['submitted_by'] = $history_all[1];
								$tailored_history['time'] = date('Y-m-d H:i:s');
								$tailored_history['approval'] = "true";
								$tailored_history['Version'] = $version;

							}
							
							/*if ($check_col_array[$header_row] == "student name")
							{
								if(!empty($data_value))
								{
									if(preg_match('/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/', $data_value)) 
									{ 
								//die ("invalid Student name");
										ob_start();	
										echo 'Pass';
										$status = ob_get_clean();
									} else 
									{
										ob_start();	
										echo 'Failed';
										$failed_status = ob_get_clean();

										ob_start();	
										echo 'Failed Invalid Student Name';
										$name_status_fail = ob_get_clean();
									}
								}else {
									ob_start();
									echo "You must provide a student name";
									$name_status = ob_get_clean();
								}
							}*/
							
							/*if($check_col_array[$header_row] == "mobile number")
							{ 
									if(!empty($data_value)) // phone number is not empty
									{
										if(preg_match('/^[0-9]{10}+$/', $data_value)) // phone number is valid
										{
										  //$data_value = '+91' . $data_value;
											$data_value = $data_value;
											ob_start();	
											echo 'Pass';
											$status = ob_get_clean();
										  // your other code here
										}
										 else // phone number is not valid
										 {
										 	ob_start();	
										 	echo 'Failed';
										 	$failed_status = ob_get_clean();
										 	ob_start();	
										 	echo 'Invalid Phone number';
										 	$mobile_status_fail = ob_get_clean();
										 }

										}
									else // phone number is empty
									{
										ob_start();
										echo 'You must provide a phone number';
										$mobile_status = ob_get_clean();
									} 
									
									$data_value = substr($data_value,0,10);


								}*/ 
								if($check_col_array[$header_row] == "imagepath")
								{
									if(!empty($data_value))
									{
										$photo_folder_path = "./uploaddir/public/uploads/healthcare2016226112942701_con/MedNote";
										$photo_folder_upload_test = "./uploaddir/public/uploads/healthcare2016226112942701_con/photo";
										$photo_folder      = $photo_folder_path."/".$unique_id;
										
								// Open a directory, and read its contents
										if (is_dir($photo_folder))
										{
											if ($dh = opendir($photo_folder)){
												while (($file  = readdir($dh)) !== false){
													if($file != "." && $file != "..") {

									    //photo size in MB
														$Filepath = filesize($photo_folder.'/'.$file);
														$Filepath = $Filepath/(1024);
														$filesize = number_format($Filepath,2) . " KB";

										//image name converted in md5
														$filenamekey = md5(uniqid($file, true)); 
														$Fileext = pathinfo($file, PATHINFO_EXTENSION);
														$filenamekey = $filenamekey.'.'.$Fileext;


									  //full_path for photo
														$photo_folder_full_path = $photo_folder.'/'.$file;

														$fi = file_get_contents($photo_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
									 // log_message("debug","fiiiiiii=========175".print_r($fi,true));
														$photo_path = $photo_folder_upload_test."/".$filenamekey;
														file_put_contents($photo_path,$fi);
									  //or fread($photo_path,$fi);
									  // array
														$image_data['file_name'] = $filenamekey;

														$image_data['file_path'] = $photo_path;
														$image_data['file_size'] = $filesize;

													}
												}
												closedir($dh);
											} 
										}
									}
									else{
										$image_data = [];
									}



								}
								/*
								else if($check_col_array[$header_row] == "general physian sign")
								{
									if(!empty($data_value))
									{
										$sign_folder_path = SIGNATURES_PANACEA;
										$sign_folder_upload_test = DOCTORS_SIGN_PANACEA;
										$sign_folder      = $sign_folder_path."/".$sign_replace;
								//$name_file = $_FILES['userfile']['name'];
								//$tmp_name = $_FILES['userfile']['tmp_name'];

								// Open a directory, and read its contents
								//if (is_dir($sign_folder)){
								  //if ($dh = opendir($sign_folder)){
										$file = $sign_folder;

									//while (($file  = readdir($dh)) !== false){
										if($file != "." && $file != "..") {
									  //echo "filename:" . $file . "<br>";

									    //photo size in MB
											$Filepath = filesize($file);
											$Filepath = $Filepath/(1024);
											$filesize = number_format($Filepath,2) . " KB";

									  //full_path for photo
											$sign_folder_full_path = $file;

											$fi = file_get_contents($sign_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
									 //log_message("debug","sign_folder_full_path fi =========320".print_r($fi,true));
											$sign_path = $sign_folder_upload_test."/".$sign_replace;
											file_put_contents($sign_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
											$sign_data['file_name'] = $sign_replace;
											$sign_data['file_path'] = $sign_path;
											$sign_data['file_size'] = $filesize;
										}
									}
									else
									{
										ob_start();
										echo 'General Doctor Signature Not Available';
										$general_status = ob_get_clean();
										
									}
									//}
									//closedir($dh);
								 // } 
								//}
								}

								else if($check_col_array[$header_row] == "dentist sign")
								{
									if(!empty($data_value))
									{
										$dentist_sign_folder_path = SIGNATURES_PANACEA;
										$dentist_sign_folder_upload_test = DOCTORS_SIGN_PANACEA;
										$dentist_sign_folder      = $dentist_sign_folder_path."/".$dentist_sign_replace;
								//$name_file = $_FILES['userfile']['name'];
								//$tmp_name = $_FILES['userfile']['tmp_name'];

								// Open a directory, and read its contents
								//if (is_dir($sign_folder)){
								  //if ($dh = opendir($sign_folder)){
										$file = $dentist_sign_folder;
									//while (($file  = readdir($dh)) !== false){
										if($file != "." && $file != "..") {
									 // echo "filename:" . $file . "<br>";

									    //photo size in MB
											$Filepath = filesize($file);
											$Filepath = $Filepath/(1024);
											$filesize = number_format($Filepath,2) . " KB";

									  //full_path for photo
											$dentist_sign_folder_full_path = $file;

											$fi = file_get_contents($dentist_sign_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
											$dentist_sign_path = $dentist_sign_folder_upload_test."/".$dentist_sign_replace;
											file_put_contents($dentist_sign_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
											$dentist_sign_data['file_name'] = $dentist_sign_replace;
											$dentist_sign_data['file_path'] = $dentist_sign_path;
											$dentist_sign_data['file_size'] = $filesize;

										}
									}
									else
									{
										ob_start();
										echo 'Dental Doctor Signature Not Available';
										$dental_status = ob_get_clean();
									}
									//closedir($dh);
								 // } 
								//}
								}
								else if($check_col_array[$header_row] == "opthomologist sign")
								{
									if(!empty($data_value))
									{
										$opthomologist_sign_folder_path = SIGNATURES_PANACEA;
										$opthomologist_sign_folder_upload_test = DOCTORS_SIGN_PANACEA;
										$opthomologist_sign_folder      = $opthomologist_sign_folder_path."/".$opthomologist_sign_replace;

										$file = $opthomologist_sign_folder;

										//log_message("error","signnnnnnnnn=========295".print_r($file,true));
									//while (($file  = readdir($dh)) !== false){
										if($file != "." && $file != "..") {
									  //echo "filename:" . $file . "<br>";

									    //photo size in MB
											$Filepath = filesize($file);
											$Filepath = $Filepath/(1024);
											$filesize = number_format($Filepath,2) . " KB";

									  //full_path for photo
											$opthomologist_sign_folder_full_path = $file;


											$fi = file_get_contents($opthomologist_sign_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
											$opthomologist_sign_path = $opthomologist_sign_folder_upload_test."/".$opthomologist_sign_replace;
											file_put_contents($opthomologist_sign_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
											$opthomologist_sign_data['file_name'] = $opthomologist_sign_replace;
									//log_message("debug","image_data======".print_r($image_data['file_name'],true));

											$opthomologist_sign_data['file_path'] = $opthomologist_sign_path;
											$opthomologist_sign_data['file_size'] = $filesize;
									//log_message("debug","image_data file_path======".print_r($image_data['file_path'],true));


										}
										else
										{
											ob_start();
											echo 'opthomologist Doctor Signature Not Available';
											$opthomologist_status = ob_get_clean();
										}
									}
									//closedir($dh);
								 // } 
								//}
								}
								else if($check_col_array[$header_row] == "audiologist sign")
								{
									if(!empty($data_value))
									{
										$audiologist_sign_folder_path = SIGNATURES_PANACEA;
										$audiologist_sign_folder_upload_test = DOCTORS_SIGN_PANACEA;
										$audiologist_sign_folder      = $audiologist_sign_folder_path."/".$audiologist_sign_replace;
								//$name_file = $_FILES['userfile']['name'];
								//$tmp_name = $_FILES['userfile']['tmp_name'];

								// Open a directory, and read its contents
								//if (is_dir($sign_folder)){
								  //if ($dh = opendir($sign_folder)){
										$file = $audiologist_sign_folder;
									//while (($file  = readdir($dh)) !== false){
										if($file != "." && $file != "..") {
									  //echo "filename:" . $file . "<br>";

									    //photo size in MB
											$Filepath = filesize($file);

											$Filepath = $Filepath/(1024);
											$filesize = number_format($Filepath,2) . " KB";



									 //full_path for photo

											$audiologist_sign_folder_full_path = $file;


											$fi = file_get_contents($audiologist_sign_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");

											$audiologist_sign_path = $audiologist_sign_folder_upload_test."/".$audiologist_sign_replace;

											file_put_contents($audiologist_sign_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
											$audiologist_sign_data['file_name'] = $audiologist_sign_replace;
											$audiologist_sign_data['file_path'] = $audiologist_sign_path;
											$audiologist_sign_data['file_size'] = $filesize;

										}
										else
										{
											ob_start();
											echo 'audiologist Doctor Signature Not Available';
											$audiologist_status = ob_get_clean();
										}
									}
									
								}else*/
								 if($check_col_array[$header_row] == "attachments")
								{

									$attachments = array();
									if(!empty($data_value))
									{
										$attachments_sign_folder_path = ATTACHMENTS_PANACEA;
										$attachments_sign_folder_upload_test = "./uploaddir/public/uploads/healthcare2016226112942701_con/files/external_files";

										foreach($attachments_sign_replace as $index => $attachments)
										{
											$attachments_sign_folder = $attachments_sign_folder_path."/".$attachments;
										}
									// Open a directory, and read its contents
										if (is_dir($attachments_sign_folder_path))
										{
											if ($dh = opendir($attachments_sign_folder_path))
											{

												while (($file  = readdir($dh)) !== false){

													if($file != "." && $file != "..") {

														$attach_unique_id = $unique_id."_Attachment_";

														if(preg_match("/$attach_unique_id/", $file))
														{

										//photo size in MB
													$Filepath = filesize($attachments_sign_folder_path.'/'.$file);

															$Filepath = $Filepath/(1024);
															$filesize = number_format($Filepath,2) . " KB";


										//image name converted in md5
															$filenamekey = md5(uniqid($file, true)); 
															$Fileext = pathinfo($file, PATHINFO_EXTENSION);
															$filenamekey = $filenamekey.'.'.$Fileext;

									  //full_path for photo
														$attachment_folder_full_path = $attachments_sign_folder_path.'/'.$file;
															
														$fi = file_get_contents($attachment_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
									 // log_message("debug","fiiiiiii=========175".print_r($fi,true));
															$attachment_photo_path = $attachments_sign_folder_upload_test."/".$filenamekey;
															file_put_contents($attachment_photo_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
															$attachments_sign_data['file_name'] = $filenamekey;
															$attachments_sign_data['file_path'] = $attachment_photo_path;
															$attachments_sign_data['file_size'] = $filesize;
															array_push($attachments_sign_data_push,$attachments_sign_data);

														}
													}
												}
												closedir($dh);
											}							  
										}

									}else
									{
									/*ob_start();
									   echo 'attachments Not Available';
									   $attachments_status = ob_get_clean(); */
									   $attachments_sign_data_push = [];
									}
								}else if($check_col_array[$header_row] == "subjective refraction attachments")
								{

									$subjective_attachments = array();
									if(!empty($data_value))
									{
										
										$subjective_attachments_sign_folder_path = PRESCRIPTION_ATTACHMENTS_PANACEA;
										$subjective_attachments_sign_folder_upload_test = "./uploaddir/public/uploads/healthcare2016226112942701_con/files/external_files";

										foreach($subjective_attachments_sign_replace as $index => $subjective_attachments)
										{
											
											$subjective_attachments_sign_folder = $subjective_attachments_sign_folder_path."/".$subjective_attachments;
										}
										
									// Open a directory, and read its contents
										if (is_dir($subjective_attachments_sign_folder_path))
										{
											if ($dh = opendir($subjective_attachments_sign_folder_path))
											{

												while (($file  = readdir($dh)) !== false){

													if($file != "." && $file != "..") {

														$attach_unique_id = $unique_id."Prescription of medicines";

														if(preg_match("/$attach_unique_id/", $file))
														{

										//photo size in MB
													$Filepath = filesize($subjective_attachments_sign_folder_path.'/'.$file);

															$Filepath = $Filepath/(1024);
															$filesize = number_format($Filepath,2) . " KB";


										//image name converted in md5
															$filenamekey = md5(uniqid($file, true)); 
															$Fileext = pathinfo($file, PATHINFO_EXTENSION);
															$filenamekey = $filenamekey.'.'.$Fileext;

									  //full_path for photo
														$subjective_attachment_folder_full_path = $subjective_attachments_sign_folder_path.'/'.$file;
															
														$fi = file_get_contents($subjective_attachment_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
									 // log_message("debug","fiiiiiii=========175".print_r($fi,true));
															$subjective_attachment_photo_path = $subjective_attachments_sign_folder_upload_test."/".$filenamekey;
															file_put_contents($subjective_attachment_photo_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
															$subjective_attachments_sign_data['file_name'] = $filenamekey;
															$subjective_attachments_sign_data['file_path'] = $subjective_attachment_photo_path;
															$subjective_attachments_sign_data['file_size'] = $filesize;
															array_push($subjective_attachments_sign_data_push,$subjective_attachments_sign_data);

														}
													}
												}
												closedir($dh);
											}							  
										}

									}else
									{
									/*ob_start();
									   echo 'attachments Not Available';
									   $attachments_status = ob_get_clean(); */
									   $subjective_attachments_sign_data_push = [];
									}
								}else if($check_col_array[$header_row] == "ocular diagnosis attachments")
								{

									$ocular_attachments = array();
									if(!empty($data_value))
									{
										$ocular_attachments_sign_folder_path = SPECTACLE_ATTACHMENTS_PANACEA;
										$ocular_attachments_sign_folder_upload_test = "./uploaddir/public/uploads/healthcare2016226112942701_con/files/external_files";

										foreach($ocular_attachments_sign_replace as $index => $ocular_attachments)
										{
											$ocular_attachments_sign_folder = $ocular_attachments_sign_folder_path."/".$ocular_attachments;
										}
									// Open a directory, and read its contents
										if (is_dir($ocular_attachments_sign_folder_path))
										{
											if ($dh = opendir($ocular_attachments_sign_folder_path))
											{

												while (($file  = readdir($dh)) !== false){

													if($file != "." && $file != "..") {

														$attach_unique_id = $unique_id."_spectacle prescription";

														if(preg_match("/$attach_unique_id/", $file))
														{

										//photo size in MB
													$Filepath = filesize($ocular_attachments_sign_folder_path.'/'.$file);

															$Filepath = $Filepath/(1024);
															$filesize = number_format($Filepath,2) . " KB";


										//image name converted in md5
															$filenamekey = md5(uniqid($file, true)); 
															$Fileext = pathinfo($file, PATHINFO_EXTENSION);
															$filenamekey = $filenamekey.'.'.$Fileext;

									  //full_path for photo
														$ocular_attachment_folder_full_path = $ocular_attachments_sign_folder_path.'/'.$file;
															
														$fi = file_get_contents($ocular_attachment_folder_full_path,"r+");
									  // or fopen($photo_folder_full_path,"r+");
									 // log_message("debug","fiiiiiii=========175".print_r($fi,true));
															$ocular_attachment_photo_path = $ocular_attachments_sign_folder_upload_test."/".$filenamekey;
															file_put_contents($ocular_attachment_photo_path,$fi);
									  //or fread($photo_path,$fi);

									  // array
															$ocular_attachments_sign_data['file_name'] = $filenamekey;
															$ocular_attachments_sign_data['file_path'] = $ocular_attachment_photo_path;
															$ocular_attachments_sign_data['file_size'] = $filesize;
															array_push($ocular_attachments_sign_data_push,$ocular_attachments_sign_data);

														}
													}
												}
												closedir($dh);
											}							  
										}

									}else
									{
									/*ob_start();
									   echo 'attachments Not Available';
									   $attachments_status = ob_get_clean(); */
									   $ocular_attachments_sign_data_push = [];
									}
								}

								/*if($check_col_array[$header_row] == "date of birth")
								{ 
									//log_message("debug","date foramt checking====".print_r($data_value.true)); 
									//if(!preg_match("/([012]?[1-9]|[12]0|3[01])\/(0?[1-9]|1[012])\/([0-9]{4})/", $data_value))
									if(preg_match('[0-9]{2}\/[0-9]{2}\/[0-9]{4}',$data_value))
									{ 
										//$data_value = $data_value;
									 log_message("debug","date foramt for DOBBBBBB====".print_r($data_value.true));
									 ob_start();
									 echo "Successfully inserted DOB"; 
									$dob_status =  ob_get_clean();
									log_message("debug","DOB invalid====".print_r($data_value.true));
									 
										 
									}else // phone number is not valid
										{
										   ob_start();
									 echo "DOB Invalid";
									 $dob_status = ob_get_clean();
										  //redirect(base_url("zipfile_controller/zipfile_success"));
										}
								 

										try {
									//$date = new DateTime('2000-01-01');

											//log_message('debug','11111111111111111111111111111111111111111.'.print_r($data_value,true));
											if(isset($data_value) || $data_value == "" || $data_value == " "){
											}else{
												$date = new DateTime($data_value);
												$data_value= $date->format('Y-m-d');
											}
											//log_message('debug','2222222222222222222222222222222222222222.'.print_r($data_value,true));


										} catch (Exception $e) {
									//echo $e->getMessage();
									//exit(1);
									//unlink($updata['upload_data']['full_path']);
											$colCoordinate = $cell->getCoordinate();

											session_start();
											$_SESSION['request_message'] = "Update falied due to following error in excel sheet: value in ".$colCoordinate." seems to of different type that of <strong>text</strong>.";


									//session_start();
											$message = "";
											if(!empty($_SESSION['request_message']))
												$message = $_SESSION['request_message'];
									// rest of your code
											$this->data['message'] = $message;

											$this->data['error'] = "excel_sheet_faild";

											return $this->data;
									//redirect('panacea_common_lib/panacea_reports_students_redirect');

										}
									}*/

									$description = $name_status_fail.' '.$mobile_status_fail.' '.$photo_status;

									$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
									$arr_data[$each_row]['unique_id']		 = $unique_id;
									$eye_lids_data          				 = ($eye_lids) ? $eye_lids : "";
									$conjunctiva_data           			 = ($conjunctiva) ? $conjunctiva : "";
									$oral_hygiene_data           			 = ($oral_hygiene) ? $oral_hygiene : "";
									$carious_teeth_data           			 = ($carious_teeth) ? $carious_teeth : "";
									$date_of_exam_data          			 = ($date_of_exam) ? $date_of_exam: "";
									$arr_data[$each_row]['description']      = $description;
									$arr_data[$each_row]['dob_status']   	 = $dob_status;
									$arr_data[$each_row]['photo']        	 = $image_data;
									$arr_data[$each_row]['general_doctor']   = $sign_data;
									$arr_data[$each_row]['dentist']          = $dentist_sign_data;
									$arr_data[$each_row]['opthomologist']    = $opthomologist_sign_data;
									$arr_data[$each_row]['audiologist']      = $audiologist_sign_data;
									$arr_data[$each_row]['attachments']      = $attachments_sign_data_push;
									$arr_data[$each_row]['subjective refraction attachments']      = $subjective_attachments_sign_data_push;
									$arr_data[$each_row]['ocular diagnosis attachments']      = $ocular_attachments_sign_data_push;
									$header_row ++;
								}
							}						
							
							$check_count = $this->ci->panacea_common_model->check_date_of_exam($arr_data[$each_row]['unique_id'],$date_of_exam_data,$eye_lids_data,$conjunctiva_data,$oral_hygiene_data,$carious_teeth_data);
							if($check_count == "No Document found")
							{
								//echo "No Found".$unique_id;exit();
							}else if($check_count == "document exists")
							{
								echo "Already This Document is exists".$unique_id;exit();
							}

				  //loop from first data untill last data
				    //$doc_data = array();
					//$form_data = array();
					//require('PHPExcel.php');

							$phpExcel = new PHPExcel;

						// Setting font to Arial Black

							$phpExcel->getDefaultStyle()->getFont()->setName('Cambria');

						// Setting font size to 14

							$phpExcel->getDefaultStyle()->getFont()->setSize(12);

						//Setting description, creator and title

							$phpExcel ->getProperties()->setTitle("Vendor list");

							$phpExcel ->getProperties()->setCreator("Robert");

							$phpExcel ->getProperties()->setDescription("Excel SpreadSheet in PHP");

						// Creating PHPExcel spreadsheet writer object

						// We will create xlsx file (Excel 2007 and above)

							$writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");

						// When creating the writer object, the first sheet is also created

						// We will get the already created sheet

							$sheet = $phpExcel ->getActiveSheet();
						 //log_message("debug","sheeeeettttttttttt".print_r($sheet,true));
						// Setting title of the sheet

							$sheet->setTitle('Errors list');

						// Creating spreadsheet header

						//$sheet ->getCell('A1')->setValue('Hospital Unique ID')->getStyle('A1');
						//$sheet ->setCellValue('A1', 'hospital uniqid id')->getStyle('A1');
						 //$sheet ->setCellValue('B1', 'mobile number')->getStyle('B1');

						 //$sheet ->getCell('A1')->setValue('hospital uniqid id');
						// $sheet ->getCell('B1')->setValue('Description');
							$sheet->setCellValue('A1', 'Hospital uniqid id')->getStyle('A1');
						//$sheet->setCellValue('B1', 'Status Passed')->getStyle('B1');
						//$sheet->setCellValue('C1', 'Status Failed')->getStyle('C1');
							$sheet->setCellValue('B1', 'Description')->getStyle('B1');

						//$sheet->setCellValue('D1', 'Student Name')->getStyle('D1');
						//$sheet->setCellValue('A1', 'hospital uniqid id')->getStyle('A1');
						//$sheet->insertNewColumnAfter('A1', $unique_id);
						//$sheet->getActiveSheet()->setCellValue('A',$unique_id);

						//$sheet->setCellValue('B1', 'hospital uniqid id')->getStyle('B1');
						//$sheet ->getCell('C1')->setValue('Cost');

						// Making headers text bold and larger

							$sheet->getStyle('A1:D1')->getFont()->setBold(false)->setSize(12);

						// Insert product data

						// Autosize the columns

							$sheet->getColumnDimension('A')->setAutoSize(true); 

						//$sheet->getColumnDimension('B')->setAutoSize(true);

						//$sheet->getColumnDimension('C')->setAutoSize(true);

						// Save the spreadsheet

							$doc_data = array();
							$count = 0;

							for($i=2;$i<count($arr_data)+2;$i++)
							{

								$sheet->setCellValue('A'.$i,$arr_data[$i]['unique_id']);
						//$sheet->setCellValue('B'.$i,$arr_data[$i]['status']);
						//$sheet->setCellValue('C'.$i,$arr_data[$i]['failed']);
								$sheet->setCellValue('B'.$i,$arr_data[$i]['description']);

					  //$FirstName = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
					 // $FirstName = ($arr_data[$i]['firstname']) ? $arr_data[$i]['firstname'] : "" ;	
					  /* log_message('debug','firstnameeeeeeeeeeee.'.print_r($doc_data,true));
					  //$LastName = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
					 $LastName = ($arr_data[$i]['lastname'] )? $arr_data[$i]['lastname'] : "" ; //Excel Column 1
					  $Email= ($arr_data[$i]['email']) ? $arr_data[$i]['email'] : "" ; //Excel Column 2
					  $Mobile= ($arr_data[$i]['mobile']) ? $arr_data[$i]['mobile'] : "" ;  //Excel Column 3
					  $Address= ($arr_data[$i]['address']) ? $arr_data[$i]['address'] : "" ;  //Excel Column 4 */ 
					  $result = $this->ci->panacea_common_model->unique_id_check($arr_data[$i]['hospital unique id']);
					  //log_message('error','result===============6453'.print_r($result,TRUE));
					  if($result == "No document found")
					  {
					  	$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$i]['hospital unique id']) ? $arr_data[$i]['hospital unique id'] : "" ;		
					  	$doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
					  	$doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$i]['student name']) ? $arr_data[$i]['student name'] : "" ;
					  	$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
					  	$doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = ($arr_data[$i]['mobile number']) ? $arr_data[$i]['mobile number'] : "" ;
					  	$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$i]['date of birth']) ? $arr_data[$i]['date of birth'] : "" ;
					  	$doc_data['widget_data']['page1']['Personal Information']['Gender'] = ($arr_data[$i]['gender']) ? $arr_data[$i]['gender'] : "" ;

					  	$doc_data['widget_data']['page2']['Personal Information']['AD No'] = (String)($arr_data[$i]['ad no']) ? $arr_data[$i]['ad no'] : "" ;
					  	$doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$i]['school name']) ? (String) $arr_data[$i]['school name'] : "" ;
					  	$doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$i]['district']) ? $arr_data[$i]['district'] : "" ;
					  	$doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$i]['class']) ? $arr_data[$i]['class'] : "";
					  	$doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$i]['section']) ? $arr_data[$i]['section'] : "" ;
					  	$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$i]['father name']) ? $arr_data[$i]['father name'] : "" ;
					  	$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$i]['date of exam']) ? $arr_data[$i]['date of exam'] : "" ;

					  	$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$i]['height']) ? $arr_data[$i]['height'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$i]['weight']) ? $arr_data[$i]['weight'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = ($arr_data[$i]['bmi']) ? $arr_data[$i]['bmi'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$i]['pulse']) ? $arr_data[$i]['pulse'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$i]['b p']) ? $arr_data[$i]['b p'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$i]['h b']) ? $arr_data[$i]['h b'] : "";
					  	$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$i]['blood group']) ? $arr_data[$i]['blood group'] : ""; 

					  	$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$i]['abnormalities']) ? explode(',',$arr_data[$i]['abnormalities']) : [];
					  	$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$i]['ortho']) ? explode(',',$arr_data[$i]['ortho']) : [];
					  	$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$i]['postural']) ? explode(',',$arr_data[$i]['postural']) : [];
					  	$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$i]['description1']) ? $arr_data[$i]['description1'] : "";
					  	$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = ($arr_data[$i]['treatment']) ? $arr_data[$i]['treatment'] : "";
					  	if(isset($arr_data[$i]['skin conditions']) && !empty($arr_data[$i]['skin conditions'])){
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = ($arr_data[$i]['skin conditions']) ? explode(',',$arr_data[$i]['skin conditions']) : [];
					  	}
					  	else{
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
					  	}

					  	$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$i]['defects at birth']) ? explode(',',$arr_data[$i]['defects at birth']) : [];
					  	$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$i]['deficiencies']) ? explode(',',$arr_data[$i]['deficiencies']) : [];
					  	$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$i]['childhood diseases']) ? explode(',',$arr_data[$i]['childhood diseases']) : [];
					  	$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$i]['n a d']) ? explode(',',$arr_data[$i]['n a d']) : [];
					  	$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

					  	$doc_data['widget_data']['page6']['Screenings'] = [];
					  //$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['without right']) ? $arr_data[$i]['without right'] : "",
					  		//'Left' => ($arr_data[$i]['without left']) ? $arr_data[$i]['without left'] : "");
						$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['presenting vision right']) ? $arr_data[$i]['presenting vision right'] : "",
					  		'Left' => ($arr_data[$i]['presenting vision left']) ? $arr_data[$i]['presenting vision left'] : "");
					  	$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$i]['with right']) ? $arr_data[$i]['with right'] : "",
					  		'Left' => ($arr_data[$i]['with left']) ? $arr_data[$i]['with left'] : "");

					  	$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$i]['colour blindness right']) ? $arr_data[$i]['colour blindness right'] : "",
					  		'Left' => ($arr_data[$i]['colour blindness left']) ? $arr_data[$i]['colour blindness left'] : "",
					  		'Eye Lids' => ($arr_data[$i]['eye lids']) ? $arr_data[$i]['eye lids'] : "",
					  		'Conjunctiva' => ($arr_data[$i]['conjunctiva']) ? $arr_data[$i]['conjunctiva'] : "",
					  		'Cornea' => ($arr_data[$i]['cornea']) ? $arr_data[$i]['cornea'] : "",
					  		'Pupil' => ($arr_data[$i]['pupil']) ? $arr_data[$i]['pupil'] : "",
					  		'Complaints' => ($arr_data[$i]['complaints']) ? $arr_data[$i]['complaints'] : "",
					  		'Wearing Spectacles' => ($arr_data[$i]['wearing spectacles']) ? $arr_data[$i]['wearing spectacles'] : "",
					  		'Subjective Refraction' => ($arr_data[$i]['subjective refraction']) ? $arr_data[$i]['subjective refraction'] : "",
					  		'Ocular Diagnosis' => ($arr_data[$i]['ocular diagnosis']) ? $arr_data[$i]['ocular diagnosis'] : "",
								//'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
								//'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  		'Referral Made' => ($arr_data[$i]['vision screen referral']) ? explode(',',$arr_data[$i]['vision screen referral']) : "",
					  		'Description' => ($arr_data[$i]['description2']) ? $arr_data[$i]['description2'] : "",
					  		'Opthomologist Sign' => ($arr_data[$i]['opthomologist']) ? $arr_data[$i]['opthomologist'] : "");

					  	$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$i]['auditory screening right']) ? $arr_data[$i]['auditory screening right'] : "",
					  		'Left' => ($arr_data[$i]['auditory screening left']) ? $arr_data[$i]['auditory screening left'] : "",
					  		'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
					  		'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  		'Description' => ($arr_data[$i]['description3']) ? $arr_data[$i]['description3'] : "",
					  		'Referral Made' => ($arr_data[$i]['auditory screen referral']) ? explode(',',$arr_data[$i]['auditory screen referral']) : "",
					  		'Audiologist Sign' => ($arr_data[$i]['audiologist']) ? $arr_data[$i]['audiologist'] : "");


					  	$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$i]['oral hygiene']) ? $arr_data[$i]['oral hygiene'] : "",
					  		'Carious Teeth' => ($arr_data[$i]['carious teeth']) ? $arr_data[$i]['carious teeth'] : "",
					  		'Flourosis' => ($arr_data[$i]['flourosis']) ? $arr_data[$i]['flourosis'] : "",
					  		'Orthodontic Treatment' => ($arr_data[$i]['orthodonic treatment']) ? $arr_data[$i]['orthodonic treatment'] : "",
					  		'Indication for extraction' => ($arr_data[$i]['indication treatment']) ? $arr_data[$i]['indication treatment'] : "",
					  		'Root Canal Treatment' => ($arr_data[$i]['root canal treatment']) ? $arr_data[$i]['root canal treatment'] : "",
					  		'CROWNS' => ($arr_data[$i]['crowns']) ? $arr_data[$i]['crowns'] : "",
					  		'Fixed Partial Denture' => ($arr_data[$i]['fixed partial denture']) ? $arr_data[$i]['fixed partial denture'] : "",
					  		'Curettage' => ($arr_data[$i]['curettage']) ? $arr_data[$i]['curettage'] : "",
					  		'Estimated Amount' => ($arr_data[$i]['estimated amount']) ? $arr_data[$i]['estimated amount'] : "",


					  		'DC 11'  => ($arr_data[$i]['dc 11']) ? $arr_data[$i]['dc 11'] : "",
					  		'DC 12'  => ($arr_data[$i]['dc 12']) ? $arr_data[$i]['dc 12'] : "",
					  		'DC 13'  => ($arr_data[$i]['dc 13']) ? $arr_data[$i]['dc 13'] : "",
					  		'DC 14'  => ($arr_data[$i]['dc 14']) ? $arr_data[$i]['dc 14'] : "",
					  		'Referral Made' => ($arr_data[$i]['dental check referral']) ? explode(',',$arr_data[$i]['dental check referral']) : "",
					  		'Result' => ($arr_data[$i]['description4']) ? $arr_data[$i]['description4'] : "",
					  		'Dentist Sign' => ($arr_data[$i]['dentist']) ? $arr_data[$i]['dentist'] : "");
					  	if(isset($arr_data[$i]['attachments']) && !empty($arr_data[$i]['attachments'])){
					  		$doc_data['external_attachments'] = ($arr_data[$i]['attachments']) ? ($arr_data[$i]['attachments']) : "";
					  	}
					  	else{
					  		$doc_data['external_attachments'] = [];
					  	}
					  	if(isset($arr_data[$i]['subjective refraction attachments']) && !empty($arr_data[$i]['subjective refraction attachments'])){
					  		$doc_data['subjective_refraction_attachments'] = ($arr_data[$i]['subjective refraction attachments']) ? ($arr_data[$i]['subjective refraction attachments']) : "";
					  	}
					  	else{
					  		$doc_data['subjective_refraction_attachments'] = [];
					  	}
					  	if(isset($arr_data[$i]['ocular diagnosis attachments']) && !empty($arr_data[$i]['ocular diagnosis attachments'])){
					  		$doc_data['ocular_diagnosis_attachments'] = ($arr_data[$i]['ocular diagnosis attachments']) ? ($arr_data[$i]['ocular diagnosis attachments']) : "";
					  	}
					  	else{
					  		$doc_data['ocular_diagnosis_attachments'] = [];
					  	}
					  }else{
					  	if($result == "Only personal info document")
					  	{

					  		$db_doc = $this->ci->panacea_common_model->get_screening_reports_ehr_uid($arr_data[$i]['hospital unique id']);

					  		$doc_data = $db_doc['screening'][0]["doc_data"];

						 // $doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$i]['hospital unique id']) ? $arr_data[$i]['hospital unique id'] : "" ;		
						 // $doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
						// $doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$i]['student name']) ? $arr_data[$i]['student name'] : "" ;
						// $doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
					// $doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = ($arr_data[$i]['mobile number']) ? $arr_data[$i]['mobile number'] : "" ;
						// $doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$i]['date of birth']) ? $arr_data[$i]['date of birth'] : "" ;

						// $doc_data['widget_data']['page2']['Personal Information']['AD No'] = (String)($arr_data[$i]['ad no']) ? $arr_data[$i]['ad no'] : "" ;
						// $doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$i]['school name']) ? (String) $arr_data[$i]['school name'] : "" ;
						// $doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$i]['district']) ? $arr_data[$i]['district'] : "" ;
						// $doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$i]['class']) ? $arr_data[$i]['class'] : "";
						// $doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$i]['section']) ? $arr_data[$i]['section'] : "" ;
						// $doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$i]['father name']) ? $arr_data[$i]['father name'] : "" ;
					  		$doc_data['widget_data']['page1']['Personal Information']['Gender'] = ($arr_data[$i]['gender']) ? $arr_data[$i]['gender'] : "" ;
					  		$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$i]['date of exam']) ? $arr_data[$i]['date of exam'] : "" ; 

					  		if((empty($doc_data['doc_data']['widget_data']['page1']['Personal Information']['Photo'])) && !(empty($arr_data[$i]['photo'])))
					  		{
					  			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
					  		}


					  		$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$i]['height']) ? $arr_data[$i]['height'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$i]['weight']) ? $arr_data[$i]['weight'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = ($arr_data[$i]['bmi']) ? $arr_data[$i]['bmi'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$i]['pulse']) ? $arr_data[$i]['pulse'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$i]['b p']) ? $arr_data[$i]['b p'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$i]['h b']) ? $arr_data[$i]['h b'] : "";
					  		$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$i]['blood group']) ? $arr_data[$i]['blood group'] : ""; 

					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$i]['abnormalities']) ? explode(',',$arr_data[$i]['abnormalities']) : [];
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$i]['ortho']) ? explode(',',$arr_data[$i]['ortho']) : [];
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$i]['postural']) ? explode(',',$arr_data[$i]['postural']) : [];
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$i]['description1']) ? $arr_data[$i]['description1'] : "";
					  		$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = ($arr_data[$i]['treatment']) ? $arr_data[$i]['treatment'] : "";
					  		if(isset($arr_data[$i]['skin conditions']) && !empty($arr_data[$i]['skin conditions'])){
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = ($arr_data[$i]['skin conditions']) ? explode(',',$arr_data[$i]['skin conditions']) : [];
					  		}
					  		else{
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
					  		}

					  		$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$i]['defects at birth']) ? explode(',',$arr_data[$i]['defects at birth']) : [];
					  		$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$i]['deficiencies']) ? explode(',',$arr_data[$i]['deficiencies']) : [];
					  		$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$i]['childhood diseases']) ? explode(',',$arr_data[$i]['childhood diseases']) : [];
					  		$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$i]['n a d']) ? explode(',',$arr_data[$i]['n a d']) : [];
					  		$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

					  		$doc_data['widget_data']['page6']['Screenings'] = [];
					  		//$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['without right']) ? $arr_data[$i]['without right'] : "",
					  			//'Left' => ($arr_data[$i]['without left']) ? $arr_data[$i]['without left'] : "");
					  		$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['presenting vision right']) ? $arr_data[$i]['presenting vision right'] : "",
					  		'Left' => ($arr_data[$i]['presenting vision left']) ? $arr_data[$i]['presenting vision left'] : "");
					  		$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$i]['with right']) ? $arr_data[$i]['with right'] : "",
					  			'Left' => ($arr_data[$i]['with left']) ? $arr_data[$i]['with left'] : "");

					  		$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$i]['colour blindness right']) ? $arr_data[$i]['colour blindness right'] : "",
					  			'Left' => ($arr_data[$i]['colour blindness left']) ? $arr_data[$i]['colour blindness left'] : "",
					  			'Eye Lids' => ($arr_data[$i]['eye lids']) ? $arr_data[$i]['eye lids'] : "",
					  			'Conjunctiva' => ($arr_data[$i]['conjunctiva']) ? $arr_data[$i]['conjunctiva'] : "",
					  			'Cornea' => ($arr_data[$i]['cornea']) ? $arr_data[$i]['cornea'] : "",
					  			'Pupil' => ($arr_data[$i]['pupil']) ? $arr_data[$i]['pupil'] : "",
					  			'Complaints' => ($arr_data[$i]['complaints']) ? $arr_data[$i]['complaints'] : "",
					  			'Wearing Spectacles' => ($arr_data[$i]['wearing spectacles']) ? $arr_data[$i]['wearing spectacles'] : "",
					  			'Subjective Refraction' => ($arr_data[$i]['subjective refraction']) ? $arr_data[$i]['subjective refraction'] : "",
					  			'Ocular Diagnosis' => ($arr_data[$i]['ocular diagnosis']) ? $arr_data[$i]['ocular diagnosis'] : "",
								//'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
								//'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  			'Referral Made' => ($arr_data[$i]['vision screen referral']) ? explode(',',$arr_data[$i]['vision screen referral']) : "",
					  			'Description' => ($arr_data[$i]['description2']) ? $arr_data[$i]['description2'] : "",
					  			'Opthomologist Sign' => ($arr_data[$i]['opthomologist']) ? $arr_data[$i]['opthomologist'] : "");

					  		$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$i]['auditory screening right']) ? $arr_data[$i]['auditory screening right'] : "",
					  			'Left' => ($arr_data[$i]['auditory screening left']) ? $arr_data[$i]['auditory screening left'] : "",
					  			'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
					  			'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  			'Description' => ($arr_data[$i]['description3']) ? $arr_data[$i]['description3'] : "",
					  			'Referral Made' => ($arr_data[$i]['auditory screen referral']) ? explode(',',$arr_data[$i]['auditory screen referral']) : "",
					  			'Audiologist Sign' => ($arr_data[$i]['audiologist']) ? $arr_data[$i]['audiologist'] : "");


					  		$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$i]['oral hygiene']) ? $arr_data[$i]['oral hygiene'] : "",
					  			'Carious Teeth' => ($arr_data[$i]['carious teeth']) ? $arr_data[$i]['carious teeth'] : "",
					  			'Flourosis' => ($arr_data[$i]['flourosis']) ? $arr_data[$i]['flourosis'] : "",
					  			'Orthodontic Treatment' => ($arr_data[$i]['orthodonic treatment']) ? $arr_data[$i]['orthodonic treatment'] : "",
					  			'Indication for extraction' => ($arr_data[$i]['indication treatment']) ? $arr_data[$i]['indication treatment'] : "",
					  			'Root Canal Treatment' => ($arr_data[$i]['root canal treatment']) ? $arr_data[$i]['root canal treatment'] : "",
					  		'CROWNS' => ($arr_data[$i]['crowns']) ? $arr_data[$i]['crowns'] : "",
					  		'Fixed Partial Denture' => ($arr_data[$i]['fixed partial denture']) ? $arr_data[$i]['fixed partial denture'] : "",
					  		'Curettage' => ($arr_data[$i]['curettage']) ? $arr_data[$i]['curettage'] : "",
					  		'Estimated Amount' => ($arr_data[$i]['estimated amount']) ? $arr_data[$i]['estimated amount'] : "",
					  			'DC 11'  => ($arr_data[$i]['dc 11']) ? $arr_data[$i]['dc 11'] : "",
					  			'DC 12'  => ($arr_data[$i]['dc 12']) ? $arr_data[$i]['dc 12'] : "",
					  			'DC 13'  => ($arr_data[$i]['dc 13']) ? $arr_data[$i]['dc 13'] : "",
					  			'DC 14'  => ($arr_data[$i]['dc 14']) ? $arr_data[$i]['dc 14'] : "",
					  			'Referral Made' => ($arr_data[$i]['dental check referral']) ? explode(',',$arr_data[$i]['dental check referral']) : "",
					  			'Result' => ($arr_data[$i]['description4']) ? $arr_data[$i]['description4'] : "",
					  			'Dentist Sign' => ($arr_data[$i]['dentist']) ? $arr_data[$i]['dentist'] : "");
					  		if(isset($arr_data[$i]['attachments']) && !empty($arr_data[$i]['attachments'])){
					  			$doc_data['external_attachments'] = ($arr_data[$i]['attachments']) ? ($arr_data[$i]['attachments']) : "";
					  		}
					  		else{
					  			$doc_data['external_attachments'] = [];
					  		}
						  	if(isset($arr_data[$i]['subjective refraction attachments']) && !empty($arr_data[$i]['subjective refraction attachments'])){
						  		$doc_data['subjective_refraction_attachments'] = ($arr_data[$i]['subjective refraction attachments']) ? ($arr_data[$i]['subjective refraction attachments']) : "";
						  	}
						  	else{
						  		$doc_data['subjective_refraction_attachments'] = [];
						  	}
						  	if(isset($arr_data[$i]['ocular diagnosis attachments']) && !empty($arr_data[$i]['ocular diagnosis attachments'])){
						  		$doc_data['ocular_diagnosis_attachments'] = ($arr_data[$i]['ocular diagnosis attachments']) ? ($arr_data[$i]['ocular diagnosis attachments']) : "";
						  	}
						  	else{
						  		$doc_data['ocular_diagnosis_attachments'] = [];
						  	}
					  		
					  	}
					  	else
					  	{ 
					  		if($result == "Full document")
					  		{
								//echo print_r("Full dcoument",true);
								//exit();
					  			$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$i]['hospital unique id']) ? $arr_data[$i]['hospital unique id'] : "" ;		
					  			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
					  			$doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$i]['student name']) ? $arr_data[$i]['student name'] : "" ;
					  			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
					  			$doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = ($arr_data[$i]['mobile number']) ? $arr_data[$i]['mobile number'] : "" ;
					  			$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$i]['date of birth']) ? $arr_data[$i]['date of birth'] : "" ;
					  			$doc_data['widget_data']['page1']['Personal Information']['Gender'] = ($arr_data[$i]['gender']) ? $arr_data[$i]['gender'] : "" ;

					  			$doc_data['widget_data']['page2']['Personal Information']['AD No'] = (String)($arr_data[$i]['ad no']) ? $arr_data[$i]['ad no'] : "" ;
					  			$doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$i]['school name']) ? (String) $arr_data[$i]['school name'] : "" ;
					  			$doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$i]['district']) ? $arr_data[$i]['district'] : "" ;
					  			$doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$i]['class']) ? $arr_data[$i]['class'] : "";
					  			$doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$i]['section']) ? $arr_data[$i]['section'] : "" ;
					  			$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$i]['father name']) ? $arr_data[$i]['father name'] : "" ;
					  			$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$i]['date of exam']) ? $arr_data[$i]['date of exam'] : "" ;

					  			$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$i]['height']) ? $arr_data[$i]['height'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$i]['weight']) ? $arr_data[$i]['weight'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = ($arr_data[$i]['bmi']) ? $arr_data[$i]['bmi'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$i]['pulse']) ? $arr_data[$i]['pulse'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$i]['b p']) ? $arr_data[$i]['b p'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$i]['h b']) ? $arr_data[$i]['h b'] : "";
					  			$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$i]['blood group']) ? $arr_data[$i]['blood group'] : ""; 

					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$i]['abnormalities']) ? explode(',',$arr_data[$i]['abnormalities']) : [];
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$i]['ortho']) ? explode(',',$arr_data[$i]['ortho']) : [];
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$i]['postural']) ? explode(',',$arr_data[$i]['postural']) : [];
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$i]['description1']) ? $arr_data[$i]['description1'] : "";
					  			$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = ($arr_data[$i]['treatment']) ? $arr_data[$i]['treatment'] : "";
					  			if(isset($arr_data[$i]['skin conditions']) && !empty($arr_data[$i]['skin conditions'])){
					  				$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = ($arr_data[$i]['skin conditions']) ? explode(',',$arr_data[$i]['skin conditions']) : [];
					  			}
					  			else{
					  				$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
					  			}

					  			$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$i]['defects at birth']) ? explode(',',$arr_data[$i]['defects at birth']) : [];
					  			$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$i]['deficiencies']) ? explode(',',$arr_data[$i]['deficiencies']) : [];
					  			$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$i]['childhood diseases']) ? explode(',',$arr_data[$i]['childhood diseases']) : [];
					  			$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$i]['n a d']) ? explode(',',$arr_data[$i]['n a d']) : [];
					  			$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

					  			$doc_data['widget_data']['page6']['Screenings'] = [];
					  			//$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['without right']) ? $arr_data[$i]['without right'] : "",
					  				//'Left' => ($arr_data[$i]['without left']) ? $arr_data[$i]['without left'] : "");
					  			$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['presenting vision right']) ? $arr_data[$i]['presenting vision right'] : "",
					  			'Left' => ($arr_data[$i]['presenting vision left']) ? $arr_data[$i]['presenting vision left'] : "");
					  			$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$i]['with right']) ? $arr_data[$i]['with right'] : "",
					  				'Left' => ($arr_data[$i]['with left']) ? $arr_data[$i]['with left'] : "");

					  			$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$i]['colour blindness right']) ? $arr_data[$i]['colour blindness right'] : "",
					  				'Left' => ($arr_data[$i]['colour blindness left']) ? $arr_data[$i]['colour blindness left'] : "",
					  				'Eye Lids' => ($arr_data[$i]['eye lids']) ? $arr_data[$i]['eye lids'] : "",
					  			'Conjunctiva' => ($arr_data[$i]['conjunctiva']) ? $arr_data[$i]['conjunctiva'] : "",
					  			'Cornea' => ($arr_data[$i]['cornea']) ? $arr_data[$i]['cornea'] : "",
					  			'Pupil' => ($arr_data[$i]['pupil']) ? $arr_data[$i]['pupil'] : "",
					  			'Complaints' => ($arr_data[$i]['complaints']) ? $arr_data[$i]['complaints'] : "",
					  			'Wearing Spectacles' => ($arr_data[$i]['wearing spectacles']) ? $arr_data[$i]['wearing spectacles'] : "",
					  			'Subjective Refraction' => ($arr_data[$i]['subjective refraction']) ? $arr_data[$i]['subjective refraction'] : "",
					  			'Ocular Diagnosis' => ($arr_data[$i]['ocular diagnosis']) ? $arr_data[$i]['ocular diagnosis'] : "",
								//'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
								//'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  				'Referral Made' => ($arr_data[$i]['vision screen referral']) ? explode(',',$arr_data[$i]['vision screen referral']) : "",
					  				'Description' => ($arr_data[$i]['description2']) ? $arr_data[$i]['description2'] : "",
					  				'Opthomologist Sign' => ($arr_data[$i]['opthomologist']) ? $arr_data[$i]['opthomologist'] : "");

					  			$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$i]['auditory screening right']) ? $arr_data[$i]['auditory screening right'] : "",
					  				'Left' => ($arr_data[$i]['auditory screening left']) ? $arr_data[$i]['auditory screening left'] : "",
					  				'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
					  				'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
					  				'Description' => ($arr_data[$i]['description3']) ? $arr_data[$i]['description3'] : "",
					  				'Referral Made' => ($arr_data[$i]['auditory screen referral']) ? explode(',',$arr_data[$i]['auditory screen referral']) : "",
					  				'Audiologist Sign' => ($arr_data[$i]['audiologist']) ? $arr_data[$i]['audiologist'] : "");


					  			$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$i]['oral hygiene']) ? $arr_data[$i]['oral hygiene'] : "",
					  				'Carious Teeth' => ($arr_data[$i]['carious teeth']) ? $arr_data[$i]['carious teeth'] : "",
					  				'Flourosis' => ($arr_data[$i]['flourosis']) ? $arr_data[$i]['flourosis'] : "",
					  				'Orthodontic Treatment' => ($arr_data[$i]['orthodonic treatment']) ? $arr_data[$i]['orthodonic treatment'] : "",
					  				'Indication for extraction' => ($arr_data[$i]['indication treatment']) ? $arr_data[$i]['indication treatment'] : "",
					  				'Root Canal Treatment' => ($arr_data[$i]['root canal treatment']) ? $arr_data[$i]['root canal treatment'] : "",
				  				'CROWNS' => ($arr_data[$i]['crowns']) ? $arr_data[$i]['crowns'] : "",
						  		'Fixed Partial Denture' => ($arr_data[$i]['fixed partial denture']) ? $arr_data[$i]['fixed partial denture'] : "",
						  		'Curettage' => ($arr_data[$i]['curettage']) ? $arr_data[$i]['curettage'] : "",
						  		'Estimated Amount' => ($arr_data[$i]['estimated amount']) ? $arr_data[$i]['estimated amount'] : "",
					  				'DC 11'  => ($arr_data[$i]['dc 11']) ? $arr_data[$i]['dc 11'] : "",
					  				'DC 12'  => ($arr_data[$i]['dc 12']) ? $arr_data[$i]['dc 12'] : "",
					  				'DC 13'  => ($arr_data[$i]['dc 13']) ? $arr_data[$i]['dc 13'] : "",
					  				'DC 14'  => ($arr_data[$i]['dc 14']) ? $arr_data[$i]['dc 14'] : "",
					  				'Referral Made' => ($arr_data[$i]['dental check referral']) ? explode(',',$arr_data[$i]['dental check referral']) : "",
					  				'Result' => ($arr_data[$i]['description4']) ? $arr_data[$i]['description4'] : "",
					  				'Dentist Sign' => ($arr_data[$i]['dentist']) ? $arr_data[$i]['dentist'] : "");
					  			if(isset($arr_data[$i]['attachments']) && !empty($arr_data[$i]['attachments'])){
					  				$doc_data['external_attachments'] = ($arr_data[$i]['attachments']) ? ($arr_data[$i]['attachments']) : "";
					  			}
					  			else{
					  				$doc_data['external_attachments'] = [];
					  			}
					  			if(isset($arr_data[$i]['subjective refraction attachments']) && !empty($arr_data[$i]['subjective refraction attachments'])){
						  		$doc_data['subjective_refraction_attachments'] = ($arr_data[$i]['subjective refraction attachments']) ? ($arr_data[$i]['subjective refraction attachments']) : "";
							  	}
							  	else{
							  		$doc_data['subjective_refraction_attachments'] = [];
							  	}
							  	if(isset($arr_data[$i]['ocular diagnosis attachments']) && !empty($arr_data[$i]['ocular diagnosis attachments'])){
							  		$doc_data['ocular_diagnosis_attachments'] = ($arr_data[$i]['ocular diagnosis attachments']) ? ($arr_data[$i]['ocular diagnosis attachments']) : "";
							  	}
							  	else{
							  		$doc_data['ocular_diagnosis_attachments'] = [];
							  	}
					  		}else
					  		{
					  			if($result == "Eye Screened Exists")
							  		{	
							  			$db_doc = $this->ci->panacea_common_model->get_screening_eye_reports_ehr_uid($arr_data[$i]['hospital unique id']);

								  		$doc_data = $db_doc['screening'][0]["doc_data"];

										 // $doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$i]['hospital unique id']) ? $arr_data[$i]['hospital unique id'] : "" ;		
										 // $doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
										// $doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$i]['student name']) ? $arr_data[$i]['student name'] : "" ;
										// $doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
										// $doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = ($arr_data[$i]['mobile number']) ? $arr_data[$i]['mobile number'] : "" ;
										// $doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$i]['date of birth']) ? $arr_data[$i]['date of birth'] : "" ;

										// $doc_data['widget_data']['page2']['Personal Information']['AD No'] = (String)($arr_data[$i]['ad no']) ? $arr_data[$i]['ad no'] : "" ;
										// $doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$i]['school name']) ? (String) $arr_data[$i]['school name'] : "" ;
										// $doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$i]['district']) ? $arr_data[$i]['district'] : "" ;
										// $doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$i]['class']) ? $arr_data[$i]['class'] : "";
										// $doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$i]['section']) ? $arr_data[$i]['section'] : "" ;
										// $doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$i]['father name']) ? $arr_data[$i]['father name'] : "" ;
								  		$doc_data['widget_data']['page1']['Personal Information']['Gender'] = ($arr_data[$i]['gender']) ? $arr_data[$i]['gender'] : "" ;
								  		$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$i]['date of exam']) ? $arr_data[$i]['date of exam'] : "" ; 

								  		if((empty($doc_data['doc_data']['widget_data']['page1']['Personal Information']['Photo'])) && !(empty($arr_data[$i]['photo'])))
								  		{
								  			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
								  		}


								  		$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$i]['height']) ? $arr_data[$i]['height'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$i]['weight']) ? $arr_data[$i]['weight'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = ($arr_data[$i]['bmi']) ? $arr_data[$i]['bmi'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$i]['pulse']) ? $arr_data[$i]['pulse'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$i]['b p']) ? $arr_data[$i]['b p'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$i]['h b']) ? $arr_data[$i]['h b'] : "";
								  		$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$i]['blood group']) ? $arr_data[$i]['blood group'] : ""; 

								  		$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$i]['abnormalities']) ? explode(',',$arr_data[$i]['abnormalities']) : [];
								  		$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$i]['ortho']) ? explode(',',$arr_data[$i]['ortho']) : [];
								  		$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$i]['postural']) ? explode(',',$arr_data[$i]['postural']) : [];
								  		$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$i]['description1']) ? $arr_data[$i]['description1'] : "";
								  		$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = ($arr_data[$i]['treatment']) ? $arr_data[$i]['treatment'] : "";
								  		if(isset($arr_data[$i]['skin conditions']) && !empty($arr_data[$i]['skin conditions'])){
								  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = ($arr_data[$i]['skin conditions']) ? explode(',',$arr_data[$i]['skin conditions']) : [];
								  		}
								  		else{
								  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
								  		}

								  		$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$i]['defects at birth']) ? explode(',',$arr_data[$i]['defects at birth']) : [];
								  		$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$i]['deficiencies']) ? explode(',',$arr_data[$i]['deficiencies']) : [];
								  		$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$i]['childhood diseases']) ? explode(',',$arr_data[$i]['childhood diseases']) : [];
								  		$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$i]['n a d']) ? explode(',',$arr_data[$i]['n a d']) : [];
								  		$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

								  		$doc_data['widget_data']['page6']['Screenings'] = [];
								  		//$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['without right']) ? $arr_data[$i]['without right'] : "",
								  			//'Left' => ($arr_data[$i]['without left']) ? $arr_data[$i]['without left'] : "");
								  		$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['presenting vision right']) ? $arr_data[$i]['presenting vision right'] : "",
								  		'Left' => ($arr_data[$i]['presenting vision left']) ? $arr_data[$i]['presenting vision left'] : "");
								  		$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$i]['with right']) ? $arr_data[$i]['with right'] : "",
								  			'Left' => ($arr_data[$i]['with left']) ? $arr_data[$i]['with left'] : "");

								  		/*$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$i]['colour blindness right']) ? $arr_data[$i]['colour blindness right'] : "",
								  			'Left' => ($arr_data[$i]['colour blindness left']) ? $arr_data[$i]['colour blindness left'] : "",
								  			'Eye Lids' => ($arr_data[$i]['eye lids']) ? $arr_data[$i]['eye lids'] : "",
								  			'Conjunctiva' => ($arr_data[$i]['conjunctiva']) ? $arr_data[$i]['conjunctiva'] : "",
								  			'Cornea' => ($arr_data[$i]['cornea']) ? $arr_data[$i]['cornea'] : "",
								  			'Pupil' => ($arr_data[$i]['pupil']) ? $arr_data[$i]['pupil'] : "",
								  			'Complaints' => ($arr_data[$i]['complaints']) ? $arr_data[$i]['complaints'] : "",
								  			'Wearing Spectacles' => ($arr_data[$i]['wearing spectacles']) ? $arr_data[$i]['wearing spectacles'] : "",
								  			'Subjective Refraction' => ($arr_data[$i]['subjective refraction']) ? $arr_data[$i]['subjective refraction'] : "",
								  			'Ocular Diagnosis' => ($arr_data[$i]['ocular diagnosis']) ? $arr_data[$i]['ocular diagnosis'] : "",
											//'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
											//'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
								  			'Referral Made' => ($arr_data[$i]['vision screen referral']) ? explode(',',$arr_data[$i]['vision screen referral']) : "",
								  			'Description' => ($arr_data[$i]['description2']) ? $arr_data[$i]['description2'] : "",
								  			'Opthomologist Sign' => ($arr_data[$i]['opthomologist']) ? $arr_data[$i]['opthomologist'] : "");*/

								  		$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$i]['auditory screening right']) ? $arr_data[$i]['auditory screening right'] : "",
								  			'Left' => ($arr_data[$i]['auditory screening left']) ? $arr_data[$i]['auditory screening left'] : "",
								  			'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
								  			'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
								  			'Description' => ($arr_data[$i]['description3']) ? $arr_data[$i]['description3'] : "",
								  			'Referral Made' => ($arr_data[$i]['auditory screen referral']) ? explode(',',$arr_data[$i]['auditory screen referral']) : "",
								  			'Audiologist Sign' => ($arr_data[$i]['audiologist']) ? $arr_data[$i]['audiologist'] : "");


								  		$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$i]['oral hygiene']) ? $arr_data[$i]['oral hygiene'] : "",
								  			'Carious Teeth' => ($arr_data[$i]['carious teeth']) ? $arr_data[$i]['carious teeth'] : "",
								  			'Flourosis' => ($arr_data[$i]['flourosis']) ? $arr_data[$i]['flourosis'] : "",
								  			'Orthodontic Treatment' => ($arr_data[$i]['orthodonic treatment']) ? $arr_data[$i]['orthodonic treatment'] : "",
								  			'Indication for extraction' => ($arr_data[$i]['indication treatment']) ? $arr_data[$i]['indication treatment'] : "",
								  			'Root Canal Treatment' => ($arr_data[$i]['root canal treatment']) ? $arr_data[$i]['root canal treatment'] : "",
								  		'CROWNS' => ($arr_data[$i]['crowns']) ? $arr_data[$i]['crowns'] : "",
								  		'Fixed Partial Denture' => ($arr_data[$i]['fixed partial denture']) ? $arr_data[$i]['fixed partial denture'] : "",
								  		'Curettage' => ($arr_data[$i]['curettage']) ? $arr_data[$i]['curettage'] : "",
								  		'Estimated Amount' => ($arr_data[$i]['estimated amount']) ? $arr_data[$i]['estimated amount'] : "",
								  			'DC 11'  => ($arr_data[$i]['dc 11']) ? $arr_data[$i]['dc 11'] : "",
								  			'DC 12'  => ($arr_data[$i]['dc 12']) ? $arr_data[$i]['dc 12'] : "",
								  			'DC 13'  => ($arr_data[$i]['dc 13']) ? $arr_data[$i]['dc 13'] : "",
								  			'DC 14'  => ($arr_data[$i]['dc 14']) ? $arr_data[$i]['dc 14'] : "",
								  			'Referral Made' => ($arr_data[$i]['dental check referral']) ? explode(',',$arr_data[$i]['dental check referral']) : "",
								  			'Result' => ($arr_data[$i]['description4']) ? $arr_data[$i]['description4'] : "",
								  			'Dentist Sign' => ($arr_data[$i]['dentist']) ? $arr_data[$i]['dentist'] : "");
								  		if(isset($arr_data[$i]['attachments']) && !empty($arr_data[$i]['attachments'])){
								  			$doc_data['external_attachments'] = ($arr_data[$i]['attachments']) ? ($arr_data[$i]['attachments']) : "";
								  		}
								  		else{
								  			$doc_data['external_attachments'] = [];
								  		}	
								  		if(isset($arr_data[$i]['subjective refraction attachments']) && !empty($arr_data[$i]['subjective refraction attachments'])){
								  		$doc_data['subjective_refraction_attachments'] = ($arr_data[$i]['subjective refraction attachments']) ? ($arr_data[$i]['subjective refraction attachments']) : "";
									  	}
									  	else{
									  		$doc_data['subjective_refraction_attachments'] = [];
									  	}
									  	if(isset($arr_data[$i]['ocular diagnosis attachments']) && !empty($arr_data[$i]['ocular diagnosis attachments'])){
									  		$doc_data['ocular_diagnosis_attachments'] = ($arr_data[$i]['ocular diagnosis attachments']) ? ($arr_data[$i]['ocular diagnosis attachments']) : "";
									  	}
									  	else{
									  		$doc_data['ocular_diagnosis_attachments'] = [];
									  	}						  		
						  			}else
								  	{
								  		if($result == "Dental Screened Exists")
								  		{
								  			$db_doc = $this->ci->panacea_common_model->get_screening_dental_reports_ehr_uid($arr_data[$i]['hospital unique id']);

								  			$doc_data = $db_doc['screening'][0]["doc_data"];

											 // $doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = ($arr_data[$i]['hospital unique id']) ? $arr_data[$i]['hospital unique id'] : "" ;		
											 // $doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
											// $doc_data['widget_data']['page1']['Personal Information']['Name'] = ($arr_data[$i]['student name']) ? $arr_data[$i]['student name'] : "" ;
											// $doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
											// $doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = ($arr_data[$i]['mobile number']) ? $arr_data[$i]['mobile number'] : "" ;
											// $doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = ($arr_data[$i]['date of birth']) ? $arr_data[$i]['date of birth'] : "" ;

											// $doc_data['widget_data']['page2']['Personal Information']['AD No'] = (String)($arr_data[$i]['ad no']) ? $arr_data[$i]['ad no'] : "" ;
											// $doc_data['widget_data']['page2']['Personal Information']['School Name'] = ($arr_data[$i]['school name']) ? (String) $arr_data[$i]['school name'] : "" ;
											// $doc_data['widget_data']['page2']['Personal Information']['District'] = ($arr_data[$i]['district']) ? $arr_data[$i]['district'] : "" ;
											// $doc_data['widget_data']['page2']['Personal Information']['Class'] = ($arr_data[$i]['class']) ? $arr_data[$i]['class'] : "";
											// $doc_data['widget_data']['page2']['Personal Information']['Section'] = ($arr_data[$i]['section']) ? $arr_data[$i]['section'] : "" ;
											// $doc_data['widget_data']['page2']['Personal Information']['Father Name'] = ($arr_data[$i]['father name']) ? $arr_data[$i]['father name'] : "" ;
									  		$doc_data['widget_data']['page1']['Personal Information']['Gender'] = ($arr_data[$i]['gender']) ? $arr_data[$i]['gender'] : "" ;
									  		$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = ($arr_data[$i]['date of exam']) ? $arr_data[$i]['date of exam'] : "" ; 

									  		if((empty($doc_data['doc_data']['widget_data']['page1']['Personal Information']['Photo'])) && !(empty($arr_data[$i]['photo'])))
									  		{
									  			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = ($arr_data[$i]['photo']) ? $arr_data[$i]['photo'] : "";
									  		}


									  		$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = ($arr_data[$i]['height']) ? $arr_data[$i]['height'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = ($arr_data[$i]['weight']) ? $arr_data[$i]['weight'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = ($arr_data[$i]['bmi']) ? $arr_data[$i]['bmi'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = ($arr_data[$i]['pulse']) ? $arr_data[$i]['pulse'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['B P'] = ($arr_data[$i]['b p']) ? $arr_data[$i]['b p'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['H B'] = ($arr_data[$i]['h b']) ? $arr_data[$i]['h b'] : "";
									  		$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = ($arr_data[$i]['blood group']) ? $arr_data[$i]['blood group'] : ""; 

									  		$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = ($arr_data[$i]['abnormalities']) ? explode(',',$arr_data[$i]['abnormalities']) : [];
									  		$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = ($arr_data[$i]['ortho']) ? explode(',',$arr_data[$i]['ortho']) : [];
									  		$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = ($arr_data[$i]['postural']) ? explode(',',$arr_data[$i]['postural']) : [];
									  		$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = ($arr_data[$i]['description1']) ? $arr_data[$i]['description1'] : "";
									  		$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = ($arr_data[$i]['treatment']) ? $arr_data[$i]['treatment'] : "";
									  		if(isset($arr_data[$i]['skin conditions']) && !empty($arr_data[$i]['skin conditions'])){
									  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = ($arr_data[$i]['skin conditions']) ? explode(',',$arr_data[$i]['skin conditions']) : [];
									  		}
									  		else{
									  			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
									  		}

									  		$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = ($arr_data[$i]['defects at birth']) ? explode(',',$arr_data[$i]['defects at birth']) : [];
									  		$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = ($arr_data[$i]['deficiencies']) ? explode(',',$arr_data[$i]['deficiencies']) : [];
									  		$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = ($arr_data[$i]['childhood diseases']) ? explode(',',$arr_data[$i]['childhood diseases']) : [];
									  		$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = ($arr_data[$i]['n a d']) ? explode(',',$arr_data[$i]['n a d']) : [];
									  		$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

									  		$doc_data['widget_data']['page6']['Screenings'] = [];
									  		//$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['without right']) ? $arr_data[$i]['without right'] : "",
									  			//'Left' => ($arr_data[$i]['without left']) ? $arr_data[$i]['without left'] : "");
									  		$doc_data['widget_data']['page6']['Without Glasses'] = array('Right' => ($arr_data[$i]['presenting vision right']) ? $arr_data[$i]['presenting vision right'] : "",
									  		'Left' => ($arr_data[$i]['presenting vision left']) ? $arr_data[$i]['presenting vision left'] : "");
									  		$doc_data['widget_data']['page6']['With Glasses'] = array('Right' => ($arr_data[$i]['with right']) ? $arr_data[$i]['with right'] : "",
									  			'Left' => ($arr_data[$i]['with left']) ? $arr_data[$i]['with left'] : "");

									  		$doc_data['widget_data']['page7']['Colour Blindness'] = array('Right' => ($arr_data[$i]['colour blindness right']) ? $arr_data[$i]['colour blindness right'] : "",
									  			'Left' => ($arr_data[$i]['colour blindness left']) ? $arr_data[$i]['colour blindness left'] : "",
									  			'Eye Lids' => ($arr_data[$i]['eye lids']) ? $arr_data[$i]['eye lids'] : "",
									  			'Conjunctiva' => ($arr_data[$i]['conjunctiva']) ? $arr_data[$i]['conjunctiva'] : "",
									  			'Cornea' => ($arr_data[$i]['cornea']) ? $arr_data[$i]['cornea'] : "",
									  			'Pupil' => ($arr_data[$i]['pupil']) ? $arr_data[$i]['pupil'] : "",
									  			'Complaints' => ($arr_data[$i]['complaints']) ? $arr_data[$i]['complaints'] : "",
									  			'Wearing Spectacles' => ($arr_data[$i]['wearing spectacles']) ? $arr_data[$i]['wearing spectacles'] : "",
									  			'Subjective Refraction' => ($arr_data[$i]['subjective refraction']) ? $arr_data[$i]['subjective refraction'] : "",
									  			'Ocular Diagnosis' => ($arr_data[$i]['ocular diagnosis']) ? $arr_data[$i]['ocular diagnosis'] : "",
												//'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
												//'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
									  			'Referral Made' => ($arr_data[$i]['vision screen referral']) ? explode(',',$arr_data[$i]['vision screen referral']) : "",
									  			'Description' => ($arr_data[$i]['description2']) ? $arr_data[$i]['description2'] : "",
									  			'Opthomologist Sign' => ($arr_data[$i]['opthomologist']) ? $arr_data[$i]['opthomologist'] : "");

									  		$doc_data['widget_data']['page8'][' Auditory Screening'] = array('Right' => ($arr_data[$i]['auditory screening right']) ? $arr_data[$i]['auditory screening right'] : "",
									  			'Left' => ($arr_data[$i]['auditory screening left']) ? $arr_data[$i]['auditory screening left'] : "",
									  			'Speech Screening' => ($arr_data[$i]['speech screening']) ? explode(',',$arr_data[$i]['speech screening']) : "",
									  			'D D and disability' => ($arr_data[$i]['dd&disability']) ? explode(',',$arr_data[$i]['dd&disability']) : "",
									  			'Description' => ($arr_data[$i]['description3']) ? $arr_data[$i]['description3'] : "",
									  			'Referral Made' => ($arr_data[$i]['auditory screen referral']) ? explode(',',$arr_data[$i]['auditory screen referral']) : "",
									  			'Audiologist Sign' => ($arr_data[$i]['audiologist']) ? $arr_data[$i]['audiologist'] : "");


									  		/*$doc_data['widget_data']['page9']['Dental Check-up'] = array('Oral Hygiene' => ($arr_data[$i]['oral hygiene']) ? $arr_data[$i]['oral hygiene'] : "",
									  			'Carious Teeth' => ($arr_data[$i]['carious teeth']) ? $arr_data[$i]['carious teeth'] : "",
									  			'Flourosis' => ($arr_data[$i]['flourosis']) ? $arr_data[$i]['flourosis'] : "",
									  			'Orthodontic Treatment' => ($arr_data[$i]['orthodonic treatment']) ? $arr_data[$i]['orthodonic treatment'] : "",
									  			'Indication for extraction' => ($arr_data[$i]['indication treatment']) ? $arr_data[$i]['indication treatment'] : "",
									  			'Root Canal Treatment' => ($arr_data[$i]['root canal treatment']) ? $arr_data[$i]['root canal treatment'] : "",
									  		'CROWNS' => ($arr_data[$i]['crowns']) ? $arr_data[$i]['crowns'] : "",
									  		'Fixed Partial Denture' => ($arr_data[$i]['fixed partial denture']) ? $arr_data[$i]['fixed partial denture'] : "",
									  		'Curettage' => ($arr_data[$i]['curettage']) ? $arr_data[$i]['curettage'] : "",
									  		'Estimated Amount' => ($arr_data[$i]['estimated amount']) ? $arr_data[$i]['estimated amount'] : "",
									  			'DC 11'  => ($arr_data[$i]['dc 11']) ? $arr_data[$i]['dc 11'] : "",
									  			'DC 12'  => ($arr_data[$i]['dc 12']) ? $arr_data[$i]['dc 12'] : "",
									  			'DC 13'  => ($arr_data[$i]['dc 13']) ? $arr_data[$i]['dc 13'] : "",
									  			'DC 14'  => ($arr_data[$i]['dc 14']) ? $arr_data[$i]['dc 14'] : "",
									  			'Referral Made' => ($arr_data[$i]['dental check referral']) ? explode(',',$arr_data[$i]['dental check referral']) : "",
									  			'Result' => ($arr_data[$i]['description4']) ? $arr_data[$i]['description4'] : "",
									  			'Dentist Sign' => ($arr_data[$i]['dentist']) ? $arr_data[$i]['dentist'] : "");*/
									  		if(isset($arr_data[$i]['attachments']) && !empty($arr_data[$i]['attachments'])){
									  			$doc_data['external_attachments'] = ($arr_data[$i]['attachments']) ? ($arr_data[$i]['attachments']) : "";
									  		}
									  		else{
									  			$doc_data['external_attachments'] = [];
									  		}
									  		if(isset($arr_data[$i]['subjective refraction attachments']) && !empty($arr_data[$i]['subjective refraction attachments'])){
									  		$doc_data['subjective_refraction_attachments'] = ($arr_data[$i]['subjective refraction attachments']) ? ($arr_data[$i]['subjective refraction attachments']) : "";
										  	}
										  	else{
										  		$doc_data['subjective_refraction_attachments'] = [];
										  	}
										  	if(isset($arr_data[$i]['ocular diagnosis attachments']) && !empty($arr_data[$i]['ocular diagnosis attachments'])){
										  		$doc_data['ocular_diagnosis_attachments'] = ($arr_data[$i]['ocular diagnosis attachments']) ? ($arr_data[$i]['ocular diagnosis attachments']) : "";
										  	}
										  	else{
										  		$doc_data['ocular_diagnosis_attachments'] = [];
										  	}
								  		}
								  	}
					  		}
					  	}
					  }


					  $data_user=array();
						//$data_history = array();
					  $data_user['doc_data']=$doc_data;
					  if($result == "No document found")
					  {

					  	$data_user_properties['doc_id'] = get_unique_id();
					  	$data_user_properties['status'] = 1;
					  	$data_user_properties['_version'] = 1;
					  	$data_user_properties['doc_owner'] = "PANACEA";
					  	$data_user_properties['unique_id'] = '';
					  	$data_user_properties['doc_flow'] = "new";

					  	$app_properties['app_name'] = 'General Medical Evaluation';
					  	$app_properties['app_id']   = 'healthcare2016226112942701';

					  } else{
					  	if($result == "Only personal info document")
					  	{

					  		$data_user_properties['doc_id'] = get_unique_id();
					  		$data_user_properties['status'] = 1;
					  		$data_user_properties['_version'] = 1;
					  		$data_user_properties['doc_owner'] = "PANACEA";
					  		$data_user_properties['unique_id'] = '';
					  		$data_user_properties['doc_flow'] = "new";

					  		$app_properties['app_name'] = 'General Medical Evaluation';
					  		$app_properties['app_id']   = 'healthcare2016226112942701';
					  	}else{
					  		if($result == "Full document")
					  		{

					  			$data_user_properties['doc_id'] = get_unique_id();
					  			$data_user_properties['status'] = 1;
					  			$data_user_properties['_version'] = 1;
					  			$data_user_properties['doc_owner'] = "PANACEA";
					  			$data_user_properties['unique_id'] = '';
					  			$data_user_properties['doc_flow'] = "new";

					  			$app_properties['app_name'] = 'General Medical Evaluation';
					  			$app_properties['app_id']   = 'healthcare2016226112942701';

					  		}else
					  		{
					  			if($result == "Eye Screened Exists")
					  			{
					  				$data_user_properties['doc_id'] = get_unique_id();
							  		$data_user_properties['status'] = 1;
							  		$data_user_properties['_version'] = 1;
							  		$data_user_properties['doc_owner'] = "PANACEA";
							  		$data_user_properties['unique_id'] = '';
							  		$data_user_properties['doc_flow'] = "new";

							  		$app_properties['app_name'] = 'General Medical Evaluation';
							  		$app_properties['app_id']   = 'healthcare2016226112942701';
					  			}else
					  			{
					  				if($result == "Dental Screened Exists")
					  				{
					  					$data_user_properties['doc_id'] = get_unique_id();
								  		$data_user_properties['status'] = 1;
								  		$data_user_properties['_version'] = 1;
								  		$data_user_properties['doc_owner'] = "PANACEA";
								  		$data_user_properties['unique_id'] = '';
								  		$data_user_properties['doc_flow'] = "new";

								  		$app_properties['app_name'] = 'General Medical Evaluation';
								  		$app_properties['app_id']   = 'healthcare2016226112942701';
					  				}
					  			}
					  		}

					  	}
					  }
					  $data_user['doc_properties']=$data_user_properties;
					  $data_user['app_properties']=$app_properties;
						//history
						//$history['last_stage']['current_stage'] = "stage1";
						//$history['last_stage']['approval'] = "true";
						//$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
					  $history['last_stage'] = $tailored_history;

					  $data_user['history']=$history;

					  //log_message('error','arrrrrrdtatattatattatatattatattatatat.'.print_r($data_user,true));
					 // log_message('error','historyyyyyyyyyyyyyyyyy.'.print_r($data_user['history'],true));
					  if($result == "Only personal info document")
					  {
							  //echo print_r("only personal information",true);
								//exit();
					  	//log_message('error','Only personal info document.'.print_r($result,true));
					  	$this->ci->panacea_common_model->update_screening_details($db_doc['screening'][0]["_id"],$data_user);

					  }else if($result == "No document found"){

					  	$session_data = $this->ci->session->userdata('customer');
					  	$company_name = $session_data['company'];

							  /*$company_details = $this->ci->healthcare201672020159570_mod_calls->fetch_company_details_of_enterprise_admin($company_name);
							  
							   $student_exists = $this->ci->healthcare201672020159570_mod_calls->check_if_student_exists($data_user['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']);
							  
							  if(!$student_exists)
							  {
								$this->ci->healthcare201672020159570_mod_calls->add_student_into_login_collection($data_user,$company_details);
							} */
							//log_message('error','No document found'.print_r($result,true));

							$this->ci->panacea_common_model->insert_screening_details($data_user);


						} else if($result == "Full document"){
							//log_message('error','Full document'.print_r($result,true));
							$this->ci->panacea_common_model->insert_screening_details($data_user);
						}else if($result == "Eye Screened Exists")
						{
							$this->ci->panacea_common_model->update_screening_details($db_doc['screening'][0]["_id"],$data_user);
						}else if($result == "Dental Screened Exists")
						{
							$this->ci->panacea_common_model->update_screening_details($db_doc['screening'][0]["_id"],$data_user);
						}
						
						//  


						//$data = array('upload_data' => $this->upload->data());
						//$this->ci->_render_page('panacea_cc/panacea_cc_dash',$data_user);

					}

					 //unlink('./uploads/naresh/MedNote/example.xlsx'); //File Deleted After uploading in database .			 
					//redirect(base_url() . "put link were you want to redirect");
					
					 //$writer->save('uploads/products6.xlsx');

					header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
					header("Content-Disposition: attachment; filename=\"results.xls\"");
					header("Cache-Control: max-age=0");
					ob_end_clean(); 



					$writer->save("php://output"); 


				} 			
			/* $this->data['message'] = "";
			$this->ci->_render_page("panacea_cc/to_dashboard",$this->data); */
			log_message("debug","deleteeeeeeeeeeeeeee=====5008");
			return TRUE;

		}

	// ------------------------------------------------------------------------

	/**
	 * Helper: BMI PIE REPORT
	 
	 * @author Naresh 
	 */
	
	/*public function drill_down_bmi_to_district($data,$month,$district, $school_name)
	{
		$obj_data = json_decode($data,TRUE);
		$type = $obj_data['label'];
		$bmi_report_state = $this->ci->panacea_common_model->drill_down_bmi_to_district($type,$month,$district, $school_name);

	}*/

	
	public function bmi_pie_view_lib_month_wise($current_month,$district_name, $school_name,$student_type = false,$student_age = false){
		$current_month = substr($current_month,0,-3);
		$count = 0;
		$bmi_report = $this->ci->panacea_common_model->get_bmi_report_model($current_month, $district_name, $school_name,$student_type,$student_age);
		
		foreach ($bmi_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['bmi_report'] = json_encode($bmi_report);
			
		}else{
			$this->data['bmi_report'] = 1;

		}
		
		if($district_name == "select" && $school_name == "select")
		{

		}else
		{
			$dist_list = $this->ci->panacea_common_model->get_all_district($district_name);
			//log_message('debug','panacea_get_all_district=============================6397=='.print_r($dist_list, true));

			$dist_id = $dist_list[0]['_id'];
			
			$bmi_reported_schools_list = $this->ci->panacea_common_model->get_bmi_submitted_schools_list($current_month,$district_name,$dist_id);
			
			$this->data['bmi_reported_schools'] = json_encode($bmi_reported_schools_list);
		}
		

		return $this->data;
	}

	public function hb_pie_view_lib_month_wise($current_month,$district_name, $school_name,$student_type = false,$student_age = false){
		$current_month = substr($current_month,0,-3);
		$count = 0;
		$hb_report = $this->ci->panacea_common_model->get_hb_report_model($current_month, $district_name, $school_name,$student_type,$student_age);
		
		foreach ($hb_report as $value){
			$count = $count + intval($value['value']);
		}

		if($count > 0){
			$this->data['hb_report'] = json_encode($hb_report);
			
		}else{
			$this->data['hb_report'] = 1;

		}

		if($district_name == "select" && $school_name == "select")
		{

		}else
		{
		$dist_list = $this->ci->panacea_common_model->get_all_district($district_name);
		

		$dist_id = $dist_list[0]['_id'];
		
		$hb_reported_schools_list = $this->ci->panacea_common_model->get_hb_submitted_schools_list($current_month,$district_name,$dist_id);
		
		$this->data['hb_reported_schools'] = json_encode($hb_reported_schools_list);
		}
		return $this->data;
	}
	
	public function generate_bmi_report_to_excel_lib($date,$district_name, $school_name)
	{
		$date = substr($date,0,-3);
		
		//load the excel library
		$this->ci->load->library('excel');
		
		//create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Havik soft Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Bhanu Prakash");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREIS BMI Report.xlsx");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREIS BMI Report.xlsx");
		$objPHPExcel->getProperties()->setDescription("BMI report of TSWREIS");
		
		// Add new sheet
		$objWorkSheet = $objPHPExcel->createSheet(0); //Setting index when creating

		// Rename sheet
		$objWorkSheet->setTitle("BMI Report");
		
		$objWorkSheet->getRowDimension(1)->setRowHeight(44);
		
		$styleArray = array(
			'borders' => array('allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('rgb' => '000000'))));
		
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		
		$styleArray = array(
			'font'  => array(
				'bold'  => true,
				'name'  => 'Calibri'),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'DCE6F1') ));
		
		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'School Name')
		->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'Unique ID')
		->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Height cms')
		->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Weight kgs')
		->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'BMI')
		->getStyle('F1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G1', 'Month')
		->getStyle('G1')->applyFromArray($styleArray);
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("G")->setAutoSize(true);
		
		$data = $this->ci->panacea_common_model->export_bmi_reports_monthly_to_excel( $date,$district_name,$school_name);
		
		
		$i = 2;
		

		foreach($data as $doc_data)
		{		
			
			$count_bmi = count($doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']);
			for($j = 0; $j <= $count_bmi; $j++)
			{
				
				if($doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['month'] == $date)
				{


					if(isset($doc_data['doc_data']['school_details']['District']) && !empty($doc_data['doc_data']['school_details']['District']))
					{
						$objWorkSheet->setCellValue('A'.$i,$doc_data['doc_data']['school_details']['District']);
					}
					else{
						$objWorkSheet->setCellValue('A'.$i, "No District Name");
					}

					if(isset($doc_data['doc_data']['school_details']['School Name']) && !empty($doc_data['doc_data']['school_details']['School Name']))
					{
						$objWorkSheet->setCellValue('B'.$i,$doc_data['doc_data']['school_details']['School Name']);
					}
					else{
						$objWorkSheet->setCellValue('B'.$i, "No School Name");
					}

					$objWorkSheet->setCellValue('C'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']);

					$objWorkSheet->setCellValue('D'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['height']);
					$objWorkSheet->setCellValue('E'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['weight']);
					$objWorkSheet->setCellValue('F'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['bmi']);
					$objWorkSheet->setCellValue('G'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
						[$j]['month']);

					break;

				}

				$objWorkSheet->setCellValue('C'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']);

				$objWorkSheet->setCellValue('D'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['height']);
				$objWorkSheet->setCellValue('E'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['weight']);
				$objWorkSheet->setCellValue('F'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['bmi']);
				$objWorkSheet->setCellValue('G'.$i,$doc_data['doc_data']['widget_data']['page1']['Student Details']['BMI_values']
					[$j]['month']);

			}
			$i++;
			
		}
		
		
		//$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		//$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$file_save = BASEDIR.TENANT.'/'.$date."-TSWREIS BMI Report.xlsx";
		
		$file_name = URLCustomer.$date."-TSWREIS BMI Report.xlsx";
		$objWriter->save($file_save);
		
		return $file_name;
		
	}
	
	
	public function get_sanitation_inspection_report_lib($dist_name, $school_name)
	{
		$total_rows = $this->ci->panacea_common_model->sanitation_insp_count($school_name);
		
		//find all the categories with paginate and save it in array to past to the view
		$this->data['get_sanitation_reports'] = $this->ci->panacea_common_model->get_sanitation_inspection_report_model($dist_name, $school_name);
		
		$this->data['reports_count'] = $total_rows;
		
		return json_encode($this->data);
	}

	public function get_civil_and_infrastructure_report_lib($dist_name, $school_name)
	{
		$total_rows = $this->ci->panacea_common_model->civil_infrastructure_count($school_name);
		
		//find all the categories with paginate and save it in array to past to the view
		$this->data['get_civil_and_infrastructure_reports'] = $this->ci->panacea_common_model->get_civil_and_infrastructure_report_model($dist_name, $school_name);
		
		$this->data['reports_count'] = $total_rows;
		
		return json_encode($this->data);
	}

	public function get_health_inspector_inspection_report_lib($dist_name, $school_name)
	{
		$total_rows = $this->ci->panacea_common_model->health_inspector_inspection_count($school_name);
		
		//find all the categories with paginate and save it in array to past to the view
		$this->data['get_health_inspector_reports'] = $this->ci->panacea_common_model->get_health_inspector_inspection_report_model($dist_name, $school_name);
		
		$this->data['reports_count'] = $total_rows;
		
		return json_encode($this->data);
	}

	public function get_food_hygiene_inspection_report_lib($dist_name, $school_name)
	{
		$total_rows = $this->ci->panacea_common_model->food_hygiene_inspection_count($school_name);
		
		//find all the categories with paginate and save it in array to past to the view
		$this->data['get_food_hygiene_inspection_reports'] = $this->ci->panacea_common_model->get_food_hygiene_inspection_report_model($dist_name, $school_name);
		
		$this->data['reports_count'] = $total_rows;
		
		return json_encode($this->data);
	}

	public function get_initaite_requests_count_today_date($today_date)
		{
			$initiate_count_list = array();
			$doctors_email_list_dr1 = array();
			$doctors_email_list_dr2 = array();
			$doctors_email_list_dr3 = array();
			$doctors_email_list_dr4 = array();
			$doctors_email_list_dr5 = array();
			$doctors_email_list_dr6 = array();
			$doctors_email_list_dr7 = array();
			$doctors_email_list_dr7 = array();
			$doctors_email_list_dr10 = array();
			$doctors_email_list_dr11 = array();
			$doctors_email_list_dr12 = array();
			$doctors_email_list_dr13 = array();
			$doctors_email_list_dr14 = array();
			$doctors_email_list_dr15 = array();

			$initiate_count = $this->ci->panacea_common_model->get_initaite_requests_count_today_date($today_date);

			$requests_count = $this->ci->panacea_common_model->get_requests_count_today_date($today_date);
			//echo print_r($requests_count,TRUE);exit();

			//$emergency_requests_count = $this->ci->panacea_common_model->get_emergency_requests_count_today_date($today_date);

			//$chronic_requests_count = $this->ci->panacea_common_model->get_chronic_requests_count_today_date($today_date);
			//$request_type_counts = $this->ci->panacea_common_model->get_total_issues_requests_count($today_date,$school_name);
			$doctors_response_count = $this->ci->panacea_common_model->get_doctors_response_count_today_date($today_date);

			
			$this->data['initiate_request_count_for_today'] = $initiate_count;
			$this->data['normal_requests_count'] = $requests_count['normal_count'];
			$this->data['emergency_requests_count'] =$requests_count['emergency_count'];
			$this->data['chronic_requests_count'] = $requests_count['chronic_count'];
			
			
			
			$doctors_names = array();
		if(count($doctors_response_count)>0)
		{
			foreach($doctors_response_count as $index => $history)
			{
				//$end_history = end($history['history']);
				
				for ($i=0; $i < count($history['history']); $i++) { 
					$date = explode(" ",$history['history'][$i]['time']);
					if($date[0] == $today_date && $history['history'][$i]['current_stage'] == "Doctor")
					{
						/*echo print_r($history['history'][$i]['current_stage'],true);
						echo print_r($date[0],true);
						exit();*/

						//$this->data['doctors_count'] = count($history['history'][$i]['current_stage']);

						$doctor_submitted_list = $history['history'][$i]['submitted_by'];
						$doctor_submitted_name = $history['history'][$i]['submitted_by_name'];

						
						array_push($doctors_names,$doctor_submitted_name);
					}else
					{
						//echo print_r($date[0],true);
						//echo print_r($history['history'][$i]['current_stage'],true);
					}
				}
				
				/*if(preg_match("/panacea.dr/i", $end_history['submitted_by']))
				{									
					$this->data['doctors_count'] = count($doctors_response_count);

					$doctor_submitted_list = $end_history['submitted_by'];
					$doctor_submitted_name = $end_history['submitted_by_name'];

					
					array_push($doctors_names,$doctor_submitted_name);

					if($doctor_submitted_list == "panacea.dr1@gmail.com")
					{
						$this->data['doctor_name_dr1'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr1,$doctor_submitted_list);
						$this->data['doctors_count_list_dr1'] = count($doctors_email_list_dr1);
					}
					else if($doctor_submitted_list == "panacea.dr2@gmail.com")
					{
						$this->data['doctor_name_dr2'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr2,$doctor_submitted_list);
						$this->data['doctors_count_list_dr2'] = count($doctors_email_list_dr2);
					}
					else if($doctor_submitted_list == "panacea.dr3@gmail.com")
					{
						$this->data['doctor_name_dr3'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr3,$doctor_submitted_list);
						$this->data['doctors_count_list_dr3'] = count($doctors_email_list_dr3);
					}
					else if($doctor_submitted_list == "panacea.dr4@gmail.com")
					{
						$this->data['doctor_name_dr4'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr4,$doctor_submitted_list);
						$this->data['doctors_count_list_dr4'] = count($doctors_email_list_dr4);
					}
					else if($doctor_submitted_list == "panacea.dr5@gmail.com")
					{
						$this->data['doctor_name_dr5'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr5,$doctor_submitted_list);
						$this->data['doctors_count_list_dr5'] = count($doctors_email_list_dr5);
					}
					else if($doctor_submitted_list == "panacea.dr6@gmail.com")
					{
						$this->data['doctor_name_dr6'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr6,$doctor_submitted_list);
						$this->data['doctors_count_list_dr6'] = count($doctors_email_list_dr6);
					}
					else if($doctor_submitted_list == "panacea.dr7@gmail.com")
					{
						$this->data['doctor_name_dr7'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr7,$doctor_submitted_list);
						$this->data['doctors_count_list_dr7'] =  count($doctors_email_list_dr7);
					}else if($doctor_submitted_list == "panacea.dr10@gmail.com")
					{
						$this->data['doctor_name_dr10'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr10,$doctor_submitted_list);
						$this->data['doctors_count_list_dr10'] =  count($doctors_email_list_dr10);
					}
					else if($doctor_submitted_list == "panacea.dr11@gmail.com")
					{
						$this->data['doctor_name_dr11'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr11,$doctor_submitted_list);
						$this->data['doctors_count_list_dr11'] =  count($doctors_email_list_dr11);
					}else if($doctor_submitted_list == "panacea.dr12@gmail.com")
					{
						$this->data['doctor_name_dr12'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr12,$doctor_submitted_list);
						$this->data['doctors_count_list_dr12'] =  count($doctors_email_list_dr12);
					}else if($doctor_submitted_list == "panacea.dr13@gmail.com")
					{
						$this->data['doctor_name_dr13'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr13,$doctor_submitted_list);
						$this->data['doctors_count_list_dr13'] =  count($doctors_email_list_dr13);
					}else if($doctor_submitted_list == "panacea.dr14@gmail.com")
					{
						$this->data['doctor_name_dr14'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr14,$doctor_submitted_list);
						$this->data['doctors_count_list_dr14'] =  count($doctors_email_list_dr14);
					}
					else if($doctor_submitted_list == "panacea.dr15@gmail.com")
					{
						$this->data['doctor_name_dr15'] = $doctor_submitted_name;
						array_push($doctors_email_list_dr15,$doctor_submitted_list);
						$this->data['doctors_count_list_dr15'] =  count($doctors_email_list_dr15);
					}
				}*/
			}			
			
			$list_for_doctors = array_count_values($doctors_names);
			$this->data['doctors_names'] = $list_for_doctors;
			$this->data['doctors_count'] = count($doctors_names);
		}
		return $this->data;
	}
		
		public function get_initaite_requests_count_school($today_date,$school_name)
		{
			$initiate_count_list = array();
			$doctors_email_list_dr1 = array();
			$doctors_email_list_dr2 = array();
			$doctors_email_list_dr3 = array();
			$doctors_email_list_dr4 = array();
			$doctors_email_list_dr5 = array();
			$doctors_email_list_dr6 = array();
			$doctors_email_list_dr7 = array();

			$initiate_count = $this->ci->panacea_common_model->get_initaite_requests_count($today_date,$school_name);

			$normal_requests_count = $this->ci->panacea_common_model->get_normal_requests_count($today_date,$school_name);

			$emergency_requests_count = $this->ci->panacea_common_model->get_emergency_requests_count($today_date,$school_name);

			$chronic_requests_count = $this->ci->panacea_common_model->get_chronic_requests_count($today_date,$school_name);


			//$request_type_counts = $this->ci->panacea_common_model->get_total_issues_requests_count($today_date,$school_name);
			$doctors_response_count = $this->ci->panacea_common_model->get_doctors_response_count($today_date,$school_name);

			
			$this->data['initiate_request_count_for_today'] = count($initiate_count);
			$this->data['normal_requests_count'] = count($normal_requests_count);
			$this->data['emergency_requests_count'] = count($emergency_requests_count);
			$this->data['chronic_requests_count'] = count($chronic_requests_count);
			
			$this->data['doctors_count'] = count($doctors_response_count);
		
		if(count($doctors_response_count)>0)
		{
			foreach($doctors_response_count as $index => $history)
			{
				$doctor_submitted_list = $history['history'][1]['submitted_by'];
				if($doctor_submitted_list == "panacea.dr1#gmail.com")
				{
					$this->data['doctor_name_dr1'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr1,$doctor_submitted_list);
					$this->data['doctors_count_list_dr1'] = count($doctors_email_list_dr1);
				}
				else if($doctor_submitted_list == "panacea.dr2#gmail.com")
				{
					$this->data['doctor_name_dr2'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr2,$doctor_submitted_list);
					$this->data['doctors_count_list_dr2'] = count($doctors_email_list_dr2);
				}
				else if($doctor_submitted_list == "panacea.dr3#gmail.com")
				{
					$this->data['doctor_name_dr3'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr3,$doctor_submitted_list);
					$this->data['doctors_count_list_dr3'] = count($doctors_email_list_dr3);
				}
				else if($doctor_submitted_list == "panacea.dr4#gmail.com")
				{
					$this->data['doctor_name_dr4'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr4,$doctor_submitted_list);
					$this->data['doctors_count_list_dr4'] = count($doctors_email_list_dr4);
				}
				else if($doctor_submitted_list == "panacea.dr5#gmail.com")
				{
					$this->data['doctor_name_dr5'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr5,$doctor_submitted_list);
					$this->data['doctors_count_list_dr5'] = count($doctors_email_list_dr5);
				}
				else if($doctor_submitted_list == "panacea.dr6#gmail.com")
				{
					$this->data['doctor_name_dr6'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr6,$doctor_submitted_list);
					$this->data['doctors_count_list_dr6'] = count($doctors_email_list_dr6);
				}
				else if($doctor_submitted_list == "panacea.dr7#gmail.com")
				{
					$this->data['doctor_name_dr7'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr7,$doctor_submitted_list);
					$this->data['doctors_count_list_dr7'] =  count($doctors_email_list_dr7);
				}
			}
			
		}
		
		
		/*echo print_r($request_counts, true);
		exit;*/
		return $this->data;
	}
	
	function basic_dashboard_with_date($date = FALSE, $screening_duration = "Yearly", $dt_name = "All", $school_name = "All")
	{

		$this->data['sanitation_report_schools_list'] = $this->ci->panacea_common_model->get_sanitation_report_pie_schools_data($date);
		$test = $this->ci->panacea_common_model->get_sanitation_report_fields_count($date, $dt_name, $school_name);

		$counts = array();
		$counts_to = array();
		$counts_three = array();
		$counts_kit = array();
		$counts_kit_two = array();
		$counts_kit_three = array();
		$counts_dininghall = array();
		$counts_dininghall_two = array();
		$counts_dininghall_three = array();
		$counts_toilets = array();
		$counts_toilets_two = array();
		$counts_toilets_three = array();
		$counts_wellness_centre = array();
		$counts_wellness_centre_two = array();
		$counts_wellness_centre_three = array();
		$counts_dormitories = array();
		$counts_dormitories_two = array();
		$counts_dormitories_three = array();
		$counts_store = array();
		$counts_store_two = array();
		$counts_store_three = array();
		$counts_water = array();
		$counts_water_two = array();
		$counts_water_three = array();
		$animal_yes_count = array();
		$animal_no_count = array();
		$damages_yes_toilets = array();
		$damages_no_toilets = array();
		$daily_kitchen_menu_yes_count = array();
		$daily_kitchen_menu_no_count = array();
		$daily_kitchen_Utensils_yes_count = array();
		$daily_kitchen_Utensils_no_count = array();
		$daily_kitchen_hand_gloves_yes_count = array();
		$daily_kitchen_hand_gloves_no_count = array();
		$daily_kitchen_tasty_food_yes_count = array();
		$daily_kitchen_tasty_food_no_count =array();
		$weekly_ro_yes_count = array();
		$weekly_ro_no_count = array();
		$weekly_bore_yes_count = array();
		$weekly_bore_no_count = array();
		$weekly_noplant_yes_count = array();
		$weekly_noplant_no_count = array();
		$weekly_watertank_yes_count = array();
		$weekly_watertank_no_count = array();
		$weekly_beddamges_yes_count = array();
		$weekly_beddamges_no_count = array();
		$weekly_defaultitem_yes_count = array();
		$weekly_defaultitem_no_count = array();
		$wmg_inorganic_yes = array();
		$wmg_inorganic_no = array();
		$wmg_organic_yes = array();
		$wmg_organic_no = array();
		$wmg_dustbins_yes = array();
		$wmg_dustbins_no = array();


		//$count_animal_no = array();
		

		foreach ($test as $key) {
			$daily = $key['doc_data']['widget_data']['daily'];
			

			if(count($daily) > 1){

			if(isset($daily['Campus']['Cleanliness Of Campus Times'])){


			$daily_campus_clg_count = $daily['Campus']['Cleanliness Of Campus Times'];  

			
			if($daily_campus_clg_count == 'Once')
			{
				array_push($counts, $daily_campus_clg_count);
			}

			if($daily_campus_clg_count == 'Twice')
			{
				array_push($counts_to, $daily_campus_clg_count);
			}
			if($daily_campus_clg_count == 'Thrice')
			{
				array_push($counts_three, $daily_campus_clg_count);
			}
			}
		//kitchen
		$daily_kitchen = $key['doc_data']['widget_data']['daily']['Kitchen'];
		if(isset($daily_kitchen['Cleanliness Of The Kitchen Place In A Day'])){
		$daily_kit_clg_count = $daily_kitchen['Cleanliness Of The Kitchen Place In A Day'];	
			if($daily_kit_clg_count == 'Once')
			{
				array_push($counts_kit, $daily_kit_clg_count);
			}
			if($daily_kit_clg_count == 'Twice')
			{
				array_push($counts_kit_two, $daily_kit_clg_count);
			}
			if($daily_kit_clg_count == 'Thrice')
			{
				array_push($counts_kit_three, $daily_kit_clg_count);
			}
		}

			if(isset($daily_kitchen['page2_Cleanliness_DiningHalls'])){
			$daily_dininghall_clg_count = $daily_kitchen['page2_Cleanliness_DiningHalls'];	
			if($daily_dininghall_clg_count == 'Once')
			{
				
				array_push($counts_dininghall, $daily_dininghall_clg_count);
			}
			if($daily_dininghall_clg_count == 'Twice')
			{
				
				array_push($counts_dininghall_two, $daily_dininghall_clg_count);
			}
			if($daily_dininghall_clg_count == 'Thrice')
			{
				
				array_push($counts_dininghall_three, $daily_dininghall_clg_count);
			}
		}
			//yes  no 
			

			//toilets
		$daily_toilets = $key['doc_data']['widget_data']['daily']['Toilets'];
		if(isset($daily_toilets['Cleanliness Toilets or Bathrooms In A Day'])){
		$daily_toilets_clg_count = $daily_toilets['Cleanliness Toilets or Bathrooms In A Day'];	
			if($daily_toilets_clg_count == 'Once')
			{
				$count_toilets_once = $daily_toilets_clg_count;
				array_push($counts_toilets, $count_toilets_once);
			}
			if($daily_toilets_clg_count == 'Twice')
			{
				$count_toilets_twice = $daily_toilets_clg_count;
				array_push($counts_toilets_two, $count_toilets_twice);
			}
			if($daily_toilets_clg_count == 'Thrice')
			{
				$count_toilets_thrice = $daily_toilets_clg_count;
				array_push($counts_toilets_three, $count_toilets_thrice);
			}
		}
			$daily_wellness_centre = $key['doc_data']['widget_data']['daily']['Kitchen'];
		if(isset($daily_wellness_centre['Cleanliness Of The Wellness Centre'])){


	$daily_wellness_centre_count = $daily_wellness_centre['Cleanliness Of The Wellness Centre'];
		//wellness centre
			if($daily_wellness_centre_count == 'Once')
			{
				$count_wellness_centre_once = $daily_wellness_centre_count;
				array_push($counts_wellness_centre, $count_wellness_centre_once);
			}
			if($daily_wellness_centre_count == 'Twice')
			{
				$count_wellness_centre_twice = $daily_wellness_centre_count;
				array_push($counts_wellness_centre_two, $count_wellness_centre_twice);
			}
			if($daily_wellness_centre_count == 'Thrice')
			{
				$count_wellness_centre_thrice = $daily_wellness_centre_count;
				array_push($counts_wellness_centre_three, $count_wellness_centre_thrice);
			}
		}
			///yesssssssssssssssssss or nooooooooooo
		if(isset($daily['Campus']['Animals Around Campus'])){
			$campus_animal_daily_count = $daily['Campus']['Animals Around Campus'];
			if($campus_animal_daily_count == 'Yes')
			{
				$animal_count_yes = $campus_animal_daily_count;
				array_push($animal_yes_count, $animal_count_yes);
				
			}
			/*if($campus_animal_daily_count == 'No' || $campus_animal_daily_count == "")*/else{
				array_push($animal_no_count, $campus_animal_daily_count);
			}
		}

		if(isset($daily_toilets["Any Damages To The Toilets"])){
		$damages_toilets_count = $daily_toilets["Any Damages To The Toilets"];
		if($damages_toilets_count == "Yes")
		{
			array_push($damages_yes_toilets, $damages_toilets_count);
			
		}else
		/*if($damages_toilets_count == "No" || $damages_toilets_count == "")*/

		{
			array_push($damages_no_toilets, $damages_toilets_count);
		}
		}
		if(isset($daily_kitchen["Daily Menu Followed"])){
		$daily_kitchen_menu = $daily_kitchen["Daily Menu Followed"];
			if($daily_kitchen_menu == 'Yes')
			{
				array_push($daily_kitchen_menu_yes_count, $daily_kitchen_menu);
			}
			if($daily_kitchen_menu == 'No' || $daily_kitchen_menu == "")
			{
				array_push($daily_kitchen_menu_no_count, $daily_kitchen_menu);
			}
		}
		if(isset($daily_kitchen["Utensils Cleanliness"])){
		$daily_kitchen_Utensils = $daily_kitchen["Utensils Cleanliness"];
			if($daily_kitchen_Utensils == 'Yes')
			{
				array_push($daily_kitchen_Utensils_yes_count, $daily_kitchen_Utensils);
			}
			if($daily_kitchen_Utensils == 'No' || $daily_kitchen_Utensils == "")
			{
				array_push($daily_kitchen_Utensils_no_count, $daily_kitchen_Utensils);
			}
		}
		if(isset($daily_kitchen["Hand Gloves Used By Serving People"])){
		$daily_kitchen_hand_gloves = $daily_kitchen["Hand Gloves Used By Serving People"];
			if($daily_kitchen_hand_gloves == 'Yes')
			{
				array_push($daily_kitchen_hand_gloves_yes_count, $daily_kitchen_hand_gloves);
			}
			if($daily_kitchen_hand_gloves == 'No' || $daily_kitchen_hand_gloves == "")
			{
				array_push($daily_kitchen_hand_gloves_no_count, $daily_kitchen_hand_gloves);
			}
		}

		if(isset($daily_kitchen["Staffmembers Tasty Food Before Serving Meals"])){
		$daily_kitchen_tasty_food = $daily_kitchen["Staffmembers Tasty Food Before Serving Meals"];
			if($daily_kitchen_tasty_food == 'Yes')
			{
				array_push($daily_kitchen_tasty_food_yes_count, $daily_kitchen_tasty_food);
			}
			/*if($daily_kitchen_tasty_food == 'No' || $daily_kitchen_tasty_food == "")*/
			else
			{
				array_push($daily_kitchen_tasty_food_no_count, $daily_kitchen_tasty_food);
			}
		}	
		
		}else {
			$daily_campus_clg_count = 0;
			$daily_wellness_centre_count = 0;
			$daily_toilets_clg_count = 0;
			$count_kitchen_once= 0;
			$daily_dininghall_clg_count = 0;
		}

		
	$weekly_dormitories = $key['doc_data']['widget_data']['weekly'];
	if(count($weekly_dormitories)>1){
	$weekly_dormitories_count = $weekly_dormitories['Dormitories']['Cleanliness Of The Dormitory Room'];
		
			if($weekly_dormitories_count == 'Once')
			{
				$count_dormitories_once = $weekly_dormitories_count;
				array_push($counts_dormitories, $count_dormitories_once);
			}
			if($weekly_dormitories_count == 'Twice')
			{
				$count_dormitories_twice = $weekly_dormitories_count;
				array_push($counts_dormitories_two, $count_dormitories_twice);
			}
			if($weekly_dormitories_count == 'Thrice')
			{
				$count_dormitories_thrice = $weekly_dormitories_count;
				array_push($counts_dormitories_three, $count_dormitories_thrice);
			}	
		

			$weekly_store = $key['doc_data']['widget_data']['weekly']['Store'];

			$weekly_store_count = $weekly_store['Cleanliness of The Store Room'];
	
			if($weekly_store_count == 'Once')
			{
				$count_store_once = $weekly_store_count;
				array_push($counts_store, $count_store_once);
			}
			if($weekly_store_count == 'Twice')
			{
				$count_store_twice = $weekly_store_count;
				array_push($counts_store_two, $count_store_twice);
			}
			if($weekly_store_count == 'Thrice')
			{
				$count_store_thrice = $weekly_store_count;
				array_push($counts_store_three, $count_store_thrice);
			}
			$weekly_wsp = $key['doc_data']['widget_data']['weekly']['Water Supply Condition'];
			$ro_wsp = $weekly_wsp["RO Plant"];
			if($ro_wsp == "Yes")
			{
				array_push($weekly_ro_yes_count, $ro_wsp);
			}else{
				array_push($weekly_ro_no_count,$ro_wsp);
			}
			$bore_wsp = $weekly_wsp["Bore Water"];
			if($bore_wsp == "Yes")
			{
				array_push($weekly_bore_yes_count, $bore_wsp);
			}else{
				array_push($weekly_bore_no_count,$bore_wsp);
			}
			$noplant_wsp = $weekly_wsp["No Plant Working"];
			if($noplant_wsp == "Yes")
			{
				array_push($weekly_noplant_yes_count, $noplant_wsp);
			}else{
				array_push($weekly_noplant_no_count,$noplant_wsp);
			}
			$watertank_wsp = $weekly_wsp["Water Tank Cleaning"];
			if($watertank_wsp == "Yes")
			{
				array_push($weekly_watertank_yes_count, $watertank_wsp);
			}else{
				array_push($weekly_watertank_no_count,$watertank_wsp);
			}
			$bed_damages = $weekly_dormitories['Dormitories']['Any Damages To Beds'];
			if($bed_damages == "Yes")
			{
				array_push($weekly_beddamges_yes_count, $bed_damages);
			}else{
				array_push($weekly_beddamges_no_count,$bed_damages);
			}
			$store_defaultitem =$weekly_store['Cleanliness of The Store Room'];
			if($store_defaultitem == "Yes")
			{
				array_push($weekly_defaultitem_yes_count, $store_defaultitem);
			}else{
				array_push($weekly_defaultitem_no_count,$store_defaultitem);
			}
			$weekly_wmg = $key['doc_data']['widget_data']['weekly']['Waste Management'];
			$wmg_inorganic = $weekly_wmg["Separate dumping of Inorganic waste"];
			if($wmg_inorganic == "Yes"){
				array_push($wmg_inorganic_yes, $wmg_inorganic);
			}else
			{
				array_push($wmg_inorganic_no, $wmg_inorganic);
			}
			$wmg_organic = $weekly_wmg["Separate dumping of Organic waste"];
			if($wmg_organic == "Yes"){
				array_push($wmg_organic_yes, $wmg_organic);
			}else
			{
				array_push($wmg_organic_no, $wmg_organic);
			}
			$wmg_dustbins = $weekly_wmg["Dustbins"];
			if($wmg_dustbins == "Yes"){
				array_push($wmg_dustbins_yes, $wmg_dustbins);
			}else
			{
				array_push($wmg_dustbins_no, $wmg_dustbins);
			}

		}
		else{
			$weekly_dormitories_count = 0;
			$store_count  =  0;
		}	



		///monthly data

		if(count($key['doc_data']['widget_data']['monthly']) > 0 ){
			$monthly_water = $key['doc_data']['widget_data']['monthly']['Water'];
			/*echo print_r($monthly_water,true);
			exit;*/
			
	$monthly_water_count = $monthly_water['Warter loading Areas Times'];
	
			if($monthly_water_count == 'Once')
			{
				
				array_push($counts_water, $monthly_water_count);
			}
			if($monthly_water_count == 'Twice')
			{
				
				array_push($counts_water_two, $monthly_water_count);
			}
			if($monthly_water_count == 'Thrice')
			{
				
				array_push($counts_water_three, $monthly_water_count);
			}	
		}else{
			$monthly_water_count = 0;
			
		}

				
		}
		$once['once'] = count($counts);
		$once['twice'] = count($counts_to);
		$once['thrice'] = count($counts_three);
		$once['kit_once'] =count($counts_kit);
		$once['kit_twice'] =count($counts_kit_two);
		$once['kit_thrice'] =count($counts_kit_three);
		$once['dininghall_once'] =count($counts_dininghall);
		$once['dininghall_twice'] =count($counts_dininghall_two);
		$once['dininghall_thrice'] =count($counts_dininghall_three);
		$once['toilets_once'] =count($counts_toilets);
		$once['toilets_twice'] =count($counts_toilets_two);
		$once['toilets_thrice'] =count($counts_toilets_three);
		$once['wellness_centre_once'] =count($counts_wellness_centre);
		$once['wellness_centre_twice'] =count($counts_wellness_centre_two);
		$once['wellness_centre_thrice'] =count($counts_wellness_centre_three);
		$once['dormitories_once'] =count($counts_dormitories);
		$once['dormitories_twice'] =count($counts_dormitories_two);
		$once['dormitories_thrice'] =count($counts_dormitories_three);
		$once['store_once'] =count($counts_store);
		$once['store_twice'] =count($counts_store_two);
		$once['store_thrice'] =count($counts_store_three);
		$once['water_once'] =count($counts_water);
		$once['water_twice'] =count($counts_water_two);
		$once['water_thrice'] =count($counts_water_three);

		//yes or no count
		$once['animal_yes'] =count($animal_yes_count);
		$once['animal_no'] =count($animal_no_count);
		$once['damages_toilets_yes'] = count($damages_yes_toilets);
		$once['damages_toilets_no'] = count($damages_no_toilets);
		$once['kitchen_menu_yes_count']= count($daily_kitchen_menu_yes_count);
		$once['kitchen_menu_no_count']= count($daily_kitchen_menu_no_count);
		$once['kitchen_Utensils_yes_count']= count($daily_kitchen_Utensils_yes_count);
		$once['kitchen_Utensils_no_count']= count($daily_kitchen_Utensils_no_count);
		$once['kitchen_hand_gloves_yes_count']= count($daily_kitchen_hand_gloves_yes_count);
		$once['kitchen_hand_gloves_no_count']= count($daily_kitchen_hand_gloves_no_count);
		$once['kitchen_tasty_food_yes_count']= count($daily_kitchen_tasty_food_yes_count);
		$once['kitchen_tasty_food_no_count']= count($daily_kitchen_tasty_food_no_count);
		$once['ro_yes_count']= count($weekly_ro_yes_count);
		$once['ro_no_count']= count($weekly_ro_no_count);
		$once['bore_yes_count']= count($weekly_bore_yes_count);
		$once['bore_no_count']= count($weekly_bore_no_count);
		$once['noplant_yes_count']= count($weekly_noplant_yes_count);
		$once['noplant_no_count']= count($weekly_noplant_no_count);
		$once['watertank_yes_count']= count($weekly_watertank_yes_count);
		$once['watertank_no_count']= count($weekly_watertank_no_count);
		$once['beddamges_yes_count']= count($weekly_beddamges_yes_count);
		$once['beddamges_no_count']= count($weekly_beddamges_no_count);
		$once['defaultitem_yes_count']= count($weekly_defaultitem_yes_count);
		$once['defaultitem_no_count']= count($weekly_defaultitem_no_count);
		$once['inorganic_yes_count']= count($wmg_inorganic_yes);
		$once['inorganic_no_count']= count($wmg_inorganic_no);
		$once['organic_yes_count']= count($wmg_organic_yes);
		$once['organic_no_count']= count($wmg_organic_no);
		$once['dustbins_yes_count']= count($wmg_dustbins_yes);
		$once['dustbins_no_count']= count($wmg_dustbins_no);
		
		$this->data['sanitation_report'] =  json_encode($once);
		
		$count = 0;
		$screening_report = $this->ci->panacea_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
	
	
		return json_encode($this->data);
	
	}
	
	public function generate_health_summary_report($unique_id)
	{
	
	 $summary_report = "";
	 
	 $summary_report.= "<html><body>";
	

	   $student_docs = $this->ci->panacea_common_model->get_students_uid_for_print($unique_id);
	 	foreach ($student_docs as $student_doc) {
	 	
	
	   // Page wise data
	   $page1 = isset($student_doc['student_info']['doc_data']['widget_data']['page1']) ? $student_doc['student_info']['doc_data']['widget_data']['page1'] : "";
	   $page2 = isset($student_doc['student_info']['doc_data']['widget_data']['page2']) ? $student_doc['student_info']['doc_data']['widget_data']['page2'] : "";

	   $page3 = isset($student_doc['student_info']['doc_data']['widget_data']['page3']) ? $student_doc['student_info']['doc_data']['widget_data']['page3'] : "";
	   $page4 = isset($student_doc['student_info']['doc_data']['widget_data']['page4']) ? $student_doc['student_info']['doc_data']['widget_data']['page4'] : "";
	   $page5 = isset($student_doc['student_info']['doc_data']['widget_data']['page5']) ? $student_doc['student_info']['doc_data']['widget_data']['page5'] : "";
	   $page6 = isset($student_doc['student_info']['doc_data']['widget_data']['page6']) ? $student_doc['student_info']['doc_data']['widget_data']['page6'] : "";
	   $page7 = isset($student_doc['student_info']['doc_data']['widget_data']['page7']) ? $student_doc['student_info']['doc_data']['widget_data']['page7'] : "";
	   $page8 = isset($student_doc['student_info']['doc_data']['widget_data']['page8']) ? $student_doc['student_info']['doc_data']['widget_data']['page8'] : "";
	   $page9 = isset($student_doc['student_info']['doc_data']['widget_data']['page9']) ? $student_doc['student_info']['doc_data']['widget_data']['page9'] : "";
	   
	   
	   $summary_report.= "<div class='page_break' id='".$page1['Personal Information']['Hospital Unique ID']."'>";
	   
	   // School Information
	    $path = URLCustomer;
	   if(isset($page1['Personal Information']['Photo']['file_path']) && !is_null($page1['Personal Information']['Photo']['file_path']) && !empty($page1['Personal Information']['Photo']['file_path'])){

	    	$summary_report.="<div style='text-align:center;font-weight:bold;font-size:100%;' class='school_information'><img src=". $path.$page1['Personal Information']['Photo']['file_path']." height='80px'></div><hr>";
	   }
	    
	    
	   // Personal Information
	   $summary_report.="<div class='personal_information'><label class='title'>Personal Information</label><table><tr><td>Name : ".$page1['Personal Information']['Name']."</td><td>Class : ".$page2['Personal Information']['Class']."</td></tr><tr><td>Health Unique ID : ".$page1['Personal Information']['Hospital Unique ID']."</td><td>Section : ".$page2['Personal Information']['Section']."</td></tr><tr><td>School name:".ucwords($page2['Personal Information']['School Name'])."</td><td>District:".$page2['Personal Information']['District']."</td></tr></table></div><hr>";
	   
	    $summary_report.="<div class='screening_information'><label class='title'>Screening Information</label></div>";
	   //Physical Information

	   if(isset($page3) && !empty($page3))
	   {
		   $bmi = (int) $page3['Physical Exam']['BMI%'];
		   $summary_report.="<div class='physical_information'><label class='title'>Physical Details</label><br><label>Height ( in cms ) : ".$page3['Physical Exam']['Height cms']." </label><br><label>Weight ( in kgs ) : ".$page3['Physical Exam']['Weight kgs']." </label><br><label>BMI : ".$bmi."</label><br>";
		   
		
		   
		   $summary_report.="</div>";
	   }
	   
	   //Eye Abnormalities
	   if(isset($page6) && !empty($page6))
	   {
	   $eye_abnorm = "false";
	   $summary_report.="<div class='eye_abnormalities'><label class='title'>Eye Abnormalities</label><br>";
	   $without_glasses_left   = $page6['Without Glasses']['Left'];
	   $without_glasses_right  = $page6['Without Glasses']['Right'];
	   $with_glasses_left      = $page6['With Glasses']['Left'];
	   $with_glasses_right     = $page6['With Glasses']['Right'];
	   $color_blindness_left   = $page7['Colour Blindness']['Left'];
	   $color_blindness_right  = $page7['Colour Blindness']['Right'];
	   
	   if(($without_glasses_left !="6/6") && ($without_glasses_left !="") && ($without_glasses_right !="6/6") && ($without_glasses_right !=""))
	   {
          $summary_report.="<label class='without_glasses'> Without Glasses  </label><br>";
          $summary_report.="<label class='without_gl_left'> Left  : ".$without_glasses_left." </label><br>";
		  $summary_report.="<label class='without_gl_right'>Right : ".$without_glasses_right."</label><br>";
		  $eye_abnorm = "true";
       }
	   
	   if(($with_glasses_left !="6/6") && ($with_glasses_right !="6/6") && ($with_glasses_right !="6/6") && ($with_glasses_right !=""))
	   {
          $summary_report.="<label class='with_glasses'> With Glasses  </label><br>";
          $summary_report.="<label class='with_gl_left'> Left  : ".$with_glasses_left." </label><br>";
		  $summary_report.="<label class='with_gl_right'>Right : ".$with_glasses_right."</label><br>";
		  $eye_abnorm = "true";
	   }
	   
	   if($color_blindness_left == "Yes")
	   {
         $summary_report.="<label class='clr_blindness_left'>Colour blindness found in left eye</label><br>";
         $eye_abnorm = "true";
	   }
	   
	   if($color_blindness_right == "Yes")
	   {
         $summary_report.="<label class='clr_blindness_right'>Colour blindness found in right eye</label><br>";
         $eye_abnorm = "true";
	   }
	   
	   $eye_description = $page7['Colour Blindness']['Description'];
	   
	   $summary_report.="<label class='eye_description'>".$eye_description."</label><br>";
	   
	   $clr_blindness_referral_made = $page7['Colour Blindness']['Referral Made'];
	   
	   if($clr_blindness_referral_made == "Yes")
	   {
         $summary_report.="<label class='clr_blindness_referral'>Referral has been made</label><br>";
         $eye_abnorm = "true";
	   }
	   
	   if($eye_abnorm == "false")
	   {
          // no eye abnormalities
		  $summary_report.="<label class='no_eye_abnorm'>No eye abnormalities found</label><br>";
	   }
	   $summary_report.="</div>";
	   }
	   
	   //Auditory Abnormalities
	   if(isset($page8) && !empty($page8))
	   {
	   $audi_abnorm = "false";
	   $summary_report.="<div class='auditory_abnormalities'><label class='title'>Auditory Abnormalities</label><br>";
	   $audi_screen_left  = $page8[' Auditory Screening']['Left'];
	   $audi_screen_right = $page8[' Auditory Screening']['Right'];
	   
	   if($audi_screen_left == "Fail")
	   {
         $summary_report.="<label class='audi_screen_left'>Auditory abnormalities found in left ear</label><br>";
         $audi_abnorm = "true";
	   }
	   
	   if($audi_screen_right == "Fail")
	   {
         $summary_report.="<label class='audi_screen_right'>Auditory abnormalities found in right ear</label><br>";
         $audi_abnorm = "true";
	   }
	   
	   $audi_description = $page8[' Auditory Screening']['Description'];
	   
	   // Speech Screening
	   $speech_screening = $page8[' Auditory Screening']['Speech Screening'];
	   if(is_array($speech_screening))
	   {
	   if(in_array("Delay",$speech_screening))
	   {
         $summary_report.="<label class='speech_screen_delay'>Delay found in speech screening</label><br>";
         $audi_abnorm = "true";
       }
	   
	   if(in_array("Misarticulation",$speech_screening))
	   {
         $summary_report.="<label class='speech_screen_delay'>Misarticulation found in speech screening</label><br>";
         $audi_abnorm = "true";
       }
	   
	   if(in_array("Fluency",$speech_screening))
	   {
         $summary_report.="<label class='speech_screen_delay'>Fluency problem found in speech screening</label><br>";
         $audi_abnorm = "true";
       }
	   
	   if(in_array("Voice",$speech_screening))
	   {
         $summary_report.="<label class='speech_screen_delay'>Voice problem found in speech screening</label><br>";
         $audi_abnorm = "true";
       }
	   }
	   
	   // D D and Disability
	   $dd_and_disability = $page8[' Auditory Screening']['D D and disability'];
	   if(is_array($dd_and_disability))
	   {
	   if(in_array("Language Delay",$dd_and_disability))
	   {
         $summary_report.="<label class='audi_lang_delay'>Language Delay found </label><br>";
         $audi_abnorm = "true";
       }
	   
	   
	   if(in_array("Behaviour Disorder",$dd_and_disability))
	   {
         $summary_report.="<label class='audi_behave_disorder'>Behaviour Disorder found </label><br>";
         $audi_abnorm = "true";
       }
	   }
	   
	   $audi_abnorm_referral_made = $page8[' Auditory Screening']['Referral Made'];
	   
	   if($audi_abnorm_referral_made == "Yes")
	   {
         $summary_report.="<label class='audi_abnorm_referral'>Referral has been made</label><br>";
         $audi_abnorm = "true";
	   }
	   
	   if($audi_abnorm == "false")
	   {
          // no eye abnormalities
		  $summary_report.="<label class='no_ear_abnorm'>No auditory abnormalities found</label><br>";
	   }
	   $summary_report.="</div>";
	   }
	   
	   if(isset($page9) && !empty($page9))
	   {
	   //Dental Abnormalities
	   $summary_report.="<div class='dental_abnormalities'><label class='title'>Dental Abnormalities</label><br>";
	   $oral_hygiene  			  = $page9['Dental Check-up']['Oral Hygiene'];
	   $carious_teeth 			  = $page9['Dental Check-up']['Carious Teeth'];
	   $flourosis  				  = $page9['Dental Check-up']['Flourosis'];
	   $orthodontic_treatment     = $page9['Dental Check-up']['Orthodontic Treatment'];
	   $indication_for_extraction = $page9['Dental Check-up']['Indication for extraction'];
	   $dental_referral_made      = $page9['Dental Check-up']['Referral Made'];
	   $dental_abnorm             = "false";
	   
	   if($oral_hygiene == "Poor")
	   {
         $summary_report.="<label class='oral_hygiene'> Oral hygiene is poor</label><br>";
         $dental_abnorm = "true";
	   }
	   
	   if($carious_teeth == "Poor")
	   {
         $summary_report.="<label class='carious_teeth'> Carious teeth found</label><br>";
         $dental_abnorm = "true";
	   }
	   
	   if($flourosis == "Yes")
	   {
         $summary_report.="<label class='flourosis'> Flourosis found</label><br>";
         $dental_abnorm = "true";
	   }
	   
	   if($orthodontic_treatment == "Yes")
	   {
         $summary_report.="<label class='dental_referral'>Orthodontic Treatment needed</label><br>";
         $dental_abnorm = "true";
	   }
	   
	   if($indication_for_extraction == "Yes")
	   {
         $summary_report.="<label class='indication_for_extraction'> Indication for extraction</label><br>";
         $dental_abnorm = "true";
	   }
	   
	   if($dental_referral_made == "Yes")
	   {
         $summary_report.="<label class='dental_abnorm_referral'>Referral has been made</label>";
         $dental_abnorm = "true";
	   }
	   
	   if($dental_abnorm == "false")
	   {
          // no eye abnormalities
		  $summary_report.="<label class='no_dental_abnorm'>No dental abnormalities found</label><br>";
	   }
	   $summary_report.="</div>";
	   }
	   
	   if(isset($page4) && !empty($page4) && isset($page5) && !empty($page5))
	   {
	   //General Abnormalities
	   $summary_report.="<div class='general_abnormalities'><label class='title'>General Abnormalities</label><br>";
	   $g_abnormalities = $page4['Doctor Check Up']['Check the box if normal else describe abnormalities'];
	   $ortho    = $page4['Doctor Check Up']['Ortho'];
	   $postural = $page4['Doctor Check Up']['Postural'];
	   $general_description = $page4['Doctor Check Up']['Description'];
	   $general_advice      = (isset($page4['Doctor Check Up']['Advice']) && !empty($page4['Doctor Check Up']['Advice']))? $page4['Doctor Check Up']['Advice']:"";
	   $defects_at_birth    = $page5['Doctor Check Up']['Defects at Birth'];
	   $deficencies 		= $page5['Doctor Check Up']['Deficencies'];
	   $childhood_diseases  = $page5['Doctor Check Up']['Childhood Diseases'];
	   $general_nad         = $page5['Doctor Check Up']['N A D'];
	   $gen_abnorm  = "false";
	   
	   if(is_array($g_abnormalities))
	   {
	   if(in_array("Neurologic",$g_abnormalities))
	   {
         $summary_report.="<label class='neurologic'>Neurologic abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("H and N",$g_abnormalities))
	   {
         $summary_report.="<label class='handn'>H and N abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("ENT",$g_abnormalities))
	   {
         $summary_report.="<label class='ent'>ENT abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Lymphatic",$g_abnormalities))
	   {
         $summary_report.="<label class='lymphatic'>Lymphatic abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Heart",$g_abnormalities))
	   {
         $summary_report.="<label class='heart'>Heart abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Lungs",$g_abnormalities))
	   {
         $summary_report.="<label class='lungs'>Lungs abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Abdomen",$g_abnormalities))
	   {
         $summary_report.="<label class='abdomen'>Abdomen abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Genitalia",$g_abnormalities))
	   {
         $summary_report.="<label class='genitalia'>Genitalia abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Skin",$g_abnormalities))
	   {
         $summary_report.="<label class='skin'>Skin abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   //Ortho
	   if(is_array($ortho))
	   {
	   if(in_array("Neck",$ortho))
	   {
         $summary_report.="<label class='neck_ortho'>Ortho problem in neck found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Shoulders",$ortho))
	   {
         $summary_report.="<label class='ortho_shoulders'>Ortho problem in shoulders found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Arms/Hands",$ortho))
	   {
         $summary_report.="<label class='arms'>Ortho problem in arms/hands found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Hips",$ortho))
	   {
         $summary_report.="<label class='hips'>Ortho problem in hips found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Knees",$ortho))
	   {
         $summary_report.="<label class='knees'>Ortho problem in knees found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Feet",$ortho))
	   {
         $summary_report.="<label class='feet_ortho'>Ortho problem in feet found</label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   //Postural
	   if(is_array($postural))
	   {
	   if(in_array("Spinal Abnormality",$postural))
	   {
         $summary_report.="<label class='spinal_abnorm'>Spinal abnormality found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Mild",$postural))
	   {
         $summary_report.="<label class='mild_postural'>Mild postural problems found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Marked",$postural))
	   {
         $summary_report.="<label class='marked'>Marked postural problems found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Moderate",$postural))
	   {
         $summary_report.="<label class='moderate'>Moderate postural problems found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Referral Made",$postural))
	   {
         $summary_report.="<label class='postural_referral'>Referral for postural made </label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   //Defects at Birth
	   if(is_array($defects_at_birth))
	   {
	   if(in_array("Neural Tube Defect",$defects_at_birth))
	   {
         $summary_report.="<label class='neck_ortho'>Neural tube defect found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Down Syndrome",$defects_at_birth))
	   {
         $summary_report.="<label class='ortho_shoulders'>Down syndrome found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Cleft Lip and Palate",$defects_at_birth))
	   {
         $summary_report.="<label class='cleft'>Cleft lip and palate found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Talipes Club foot",$defects_at_birth))
	   {
         $summary_report.="<label class='talipes_club'>Talipes club foot found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Developmental Dysplasia of Hip",$defects_at_birth))
	   {
         $summary_report.="<label class='knees'>Developmental dysplasia of hip found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Congenital Cataract",$defects_at_birth))
	   {
         $summary_report.="<label class='cong_catract'>Congenital cataract found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Congenital Deafness",$defects_at_birth))
	   {
         $summary_report.="<label class='cong_deaf'>Congenital deafness found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Congenital Heart Disease",$defects_at_birth))
	   {
         $summary_report.="<label class='cong_heart'>Congenital heart disease found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Retinopathy of Prematurity",$defects_at_birth))
	   {
         $summary_report.="<label class='feet_ortho'>Retinopathy of prematurity problem found</label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   //Deficences
	   if(is_array($deficencies))
	   {
	   if(in_array("Anaemia",$deficencies))
	   {
         $summary_report.="<label class='anaemia'>Anaemia found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Vitamin D Deficiency",$deficencies))
	   {
         $summary_report.="<label class='vita_d_defi'>Vitamin D deficiency found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Vitamin Deficiency - Bcomplex",$deficencies))
	   {
         $summary_report.="<label class='vita_bcomplex_defi'>Vitamin deficiency - Bcomplex found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Vitamin A Deficiency",$deficencies))
	   {
         $summary_report.="<label class='vita_a_defi'>Vitamin A deficiency found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("SAM/stunting",$deficencies))
	   {
         $summary_report.="<label class='sam'>SAM/stunting found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Goiter",$deficencies))
	   {
         $summary_report.="<label class='goiter'>Goiter found</label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   //Childhood Diseases
	   if(is_array($childhood_diseases))
	   {
	   if(in_array("Skin Conditions",$childhood_diseases))
	   {
         $summary_report.="<label class='skin'>Skin problem found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Otitis Media",$childhood_diseases))
	   {
         $summary_report.="<label class='otitis'>Otitis Media found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Rheumatic Heart Disease",$childhood_diseases))
	   {
         $summary_report.="<label class='rheumatic'>Rheumatic heart disease found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Asthma",$childhood_diseases))
	   {
         $summary_report.="<label class='asthma'>Asthma found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Convulsive Disorders",$childhood_diseases))
	   {
         $summary_report.="<label class='convulsive_disorders'>Convulsive disorders found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Hypothyroidism",$childhood_diseases))
	   {
         $summary_report.="<label class='hypothyroidism'>Hypothyroidism found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Diabetes",$childhood_diseases))
	   {
         $summary_report.="<label class='diabetes'>Diabetes found</label><br>";
         $gen_abnorm = "true";
       }
	   if(in_array("Epilepsy",$childhood_diseases))
	   {
         $summary_report.="<label class='epilepsy'>Epilepsy found</label><br>";
         $gen_abnorm = "true";
       }
	   }
	   
	   if(!empty($general_description))
	   {
         $summary_report.="<label class='general_description'>Description : ".$general_description."</label><br>";
		 $gen_abnorm = "true";
       }
	   
	   if(!empty($general_advice))
	   {
         if($general_advice =="physical activity")
		 {
	        $summary_report.="<label class='general_advice'>Advice : Suggested ".$general_advice."</label><br>";
			$gen_abnorm = "true";
		 }
		 else
		 {
			$summary_report.="<label class='general_advice'>Advice : ".$general_advice."</label><br>";
			$gen_abnorm = "true";
		 }
       }
	   
	   if($gen_abnorm == "false")
	   {
          // no general abnormalities
		  $summary_report.="<label class='no_general_abnorm'>No general abnormalities found</label><br>";
	   }
	   
	   $summary_report.="</div>";
	   } 

	   /* REQUEST FOLLOWUPS INFORMATION */
	   if(count($student_doc['request_info'] ) > 0){
	     $reqCount =  count($student_doc['request_info']);
	      $summary_report.="<hr><div class='request_information'><label class='title'>Request Follow-ups</label>:  ".$reqCount."</div>";
	   	  $i = 0;
	    foreach ($student_doc['request_info'] as $req) {
	    // Page wise data
	   $page1 = $req['doc_data']['widget_data']['page1'];
	   $page2 = $req['doc_data']['widget_data']['page2'];
	   $history = $req['history'][0];
	 
	  $i++;

		 
	  // $summary_report.=	$student_doc['request_info'][0]['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
	// Request Information
	  $identifier_s = "";

	   $summary_report.="<div class='request_info'> ";

	   if($page2['Review Info']['Request Type'] == 'Normal')
	   {
	   	if(isset($page1['Problem Info']['Normal']) && !empty($page1['Problem Info']['Normal']))
	   	{
	   		foreach($page1['Problem Info']['Normal'] as $identifier => $values){
	   			foreach($values as $value){
	   			 	if(isset($value)){
	   		   				$identifier_s = $value;
	   		   		}else{
	   		   				$identifier_s = $identifier;
	   		   		}
	   			 }	   			 
	   		   }
	   	}else
	   	{
	   		foreach($page1['Problem Info'] as $identifier => $values)
	   		{
	   			if(!empty($values))
	   			{	   				
		  			foreach($values as $value){
		  			 	if(isset($value)){
		  		   				$identifier_s = $value;
		  		   		}else{
		  		   				$identifier_s = $identifier;
		  		   		}
		  			 }	   			 
	   			}
	  		}
	   	}
	   }elseif ($page2['Review Info']['Request Type'] == 'Emergency') {
	   	if(isset($page1['Problem Info']['Emergency']) && !empty($page1['Problem Info']['Emergency']))
	   	{
	  		foreach($page1['Problem Info']['Emergency'] as $identifier => $values){
	  			foreach($values as $value){
	  			 	if(isset($value)){
	  		   				$identifier_s = $value;
	  		   		}else{
	  		   				$identifier_s = $identifier;
	  		   		}
	  			 }	   			 
	  		   }
	   	}else
	   	{
	   		foreach($page1['Problem Info'] as $identifier => $values){
	  			foreach($values as $value){
	  			 	if(isset($value)){
	  		   				$identifier_s = $value;
	  		   		}else{
	  		   				$identifier_s = $identifier;
	  		   		}
	  			 }	   			 
	  		   }
	   	}
	   }elseif ($page2['Review Info']['Request Type'] == 'Chronic') {
	   	if(isset($page1['Problem Info']['Chronic']) && !empty($page1['Problem Info']['Chronic']))
	   	{
	   		foreach($page1['Problem Info']['Chronic'] as $identifier => $values){
	   			foreach($values as $value){
	   			 	if(isset($value)){
	   		   				$identifier_s = $value;
	   		   		}else{
	   		   				$identifier_s = $identifier;
	   		   		}
	   			 }	   			 
	   		   }
	   	}else
	   	{
	   		foreach($page1['Problem Info'] as $identifier => $values){
	  			foreach($values as $value){
	  			 	if(isset($value)){
	  		   				$identifier_s = $value;
	  		   		}else{
	  		   				$identifier_s = $identifier;
	  		   		}
	  			 }	   			 
	  		   }
	   	}
	   }
	   	
	      $summary_report.="<div class='req_information'><label class='title'><h4>".$i.")  Request raised on: ".$history['time']."</h4></label><br><label>Problem Information	</label><br><label>Request Type : ".$page2['Review Info']['Request Type']."</label><br><label>Follow Up status : ".$page2['Review Info']['Status']."</label><br><label>Problem Information : ".$identifier_s ." </label><br><label>Description : ".$page2['Problem Info']['Description']." </label><br><label>Doctor Summary : ".$page2['Diagnosis Info']['Doctor Summary']."  </label><br><label>Doctor Prescription : ".$page2['Diagnosis Info']['Prescription']."  </label><br>";



	   $summary_report.="</div>";
	}


  }
	 

	   
	   //Note
	   $summary_report.="<br><br><div class='note'><label>Note:-</label><label>1.The above report is autogenerated by the system. For detailed information,look into EHR.</label></div>";
	   
	   //Note
	   $summary_report.="<br><hr><div class='tlstec'><label>Digital report generated by MedNote healthcare platform by Havik Software Technologies Pvt. Ltd. <a href='http://www.tlstec.com'>(TLSTEC) </a> for <a href='http://www.sifhyd.org//'> Panacea </a></label></div>";

	   $summary_report.="</div>";

	  
	 	
	}
	 $summary_report.= "</body></html>";
	 
	 return $summary_report;
	
	}
	public function reports_display_ehr_uid_new_html_static_hs($unique_id,$school_name)
	{
		$docs = $this->ci->panacea_common_model->get_reports_ehr_uid_new_html_static_hs($unique_id,$school_name);
	
		$this->data['docs']          = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['hs'] = $docs['hs'];

		if(isset($docs['notes']))
		{
			$this->data['notes'] = $docs['notes'];
		}
		else
		{
			$this->data['notes'] = "";
		}
	
		$this->data['docscount'] = count($this->data['docs']);
		
		//log_message('debug','this->data=====680=='.print_r($this->data,true));
	
		return $this->data;
	}
	
	public function get_data_with_district_school($email, $date=false, $screening_duration = "Yearly", $today_date,$school_name){
		  // Variables
		
		 $campus 	      			= array();
	   $toilets  		  		= array();
	   $kitchen 	  			= array();
	   $water_Supply_Condition  = array();
	   $dormitories 	  		= array();
	   $store 	  				= array();
	   $waste_Management 	 	= array();
	   $water 			 	 	= array();
	   $sanitation_output 		= array();

	   $today_date = date("Y-m-d");

		$sanitation_report = $this->ci->panacea_common_model->get_sanitaiton_report_by_school($today_date,$school_name);
	   if(isset($sanitation_report) && !empty($sanitation_report))
		{
	      foreach($sanitation_report as $index => $value)
		  {


			$widget_data          = $value['doc_data']['widget_data'];

			if(isset($value['doc_data']['widget_data']['daily']['Campus']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Campus']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Campus']['external_attachments'])){
				$campus_attachments = $value['doc_data']['widget_data']['daily']['Campus']['external_attachments'];
				$sanitation_output['campus_attachments'] = json_encode($campus_attachments);
		
			} 
			if(isset($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'])){
				$toilets_attachments = $value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'];
				$sanitation_output['toilets_attachments'] = json_encode($toilets_attachments);
		
			} 
			if(isset($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'])){
				$kitchen_attachments = $value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'];
				$sanitation_output['kitchen_attachments'] = json_encode($kitchen_attachments);
			} 
			if(isset($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments']) && !is_null($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments']) && !empty($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments'])){
				$dormitories_attachments = $value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments'];
				$sanitation_output['dormitories_attachments'] = json_encode($dormitories_attachments);

			} 
			
		
			$daily = $widget_data['daily'];
			$weekly = $widget_data['weekly'];
			

			$monthly = $widget_data['monthly'];
			
			if(count($daily['Campus']) > 1)
			{
				
			// Campus
			//$campus_area = array();	
			$campus_area_clg['label'] = 'Cleanliness Of Campus';
			$campus_area_clg['value'] = $daily['Campus']['Cleanliness Of Campus'];
			array_push($campus,$campus_area_clg);
			$campus_clg_times['label'] = 'Cleanliness Of Campus Times';
			$campus_clg_times['value'] = $daily['Campus']['Cleanliness Of Campus Times'];
			array_push($campus,$campus_clg_times);
			$campus_around_animal['label'] = 'Animals Around Campus';
			$campus_around_animal['value'] = $daily['Campus']['Animals Around Campus'];
			array_push($campus,$campus_around_animal);
			$campus_type_animal['label'] = 'Type Of Animal';
			$campus_type_animal['value'] = $daily['Campus']['Type Of Animal'];
			array_push($campus,$campus_type_animal);
			$campus_other_animal_name['label'] = 'Other Animal Name';
			$campus_other_animal_name['value'] = $daily['Campus']['Other Animal Name'];
			array_push($campus,$campus_other_animal_name);
			
			// Toilets
			//$toilets_clg = array();
			$toilets_clg['label'] = 'Cleanliness Toilets or Bathrooms';
			$toilets_clg['value'] = $daily['Toilets']['Cleanliness Toilets or Bathrooms'];
			array_push($toilets,$toilets_clg);
			
			$toilets_cng['label'] = 'Cleanliness Toilets or Bathrooms In A Day';
			$toilets_cng['value'] = $daily['Toilets']['Cleanliness Toilets or Bathrooms In A Day'];

			array_push($toilets,$toilets_cng);

			$toilets_dmg['label'] = 'Any Damages To The Toilets';
			$toilets_dmg['value'] = $daily['Toilets']['Any Damages To The Toilets'];
			array_push($toilets,$toilets_dmg);
			
			$kitchen_clg = array();
			$kitchen_clg['label'] = 'Cleanliness Of The Kitchen Place';
			$kitchen_clg['value'] = $daily['Kitchen']['Cleanliness Of The Kitchen Place'];
			array_push($kitchen,$kitchen_clg);
			$kitchen_clg_day['label'] = 'Cleanliness Of The Kitchen Place In A Day';
			$kitchen_clg_day['value'] = $daily['Kitchen']['Cleanliness Of The Kitchen Place In A Day'];
			array_push($kitchen,$kitchen_clg_day);
			$daily_menu['label'] = 'Daily Menu Followed';
			$daily_menu['value'] = $daily['Kitchen']['Daily Menu Followed'];
			array_push($kitchen,$daily_menu);
			$utensils['label'] = 'Utensils Cleanliness';
			$utensils['value'] = $daily['Kitchen']['Utensils Cleanliness'];
			array_push($kitchen,$utensils);
			$dining_clg['label'] = 'Dining Hall Cleanliness';
			$dining_clg['value'] = $daily['Kitchen']['Dining Hall Cleanliness'];
			array_push($kitchen,$dining_clg);
			$kitchen_clg['label'] = 'page2_Cleanliness_DiningHalls';
			$kitchen_clg['value'] = $daily['Kitchen']['page2_Cleanliness_DiningHalls'];
			array_push($kitchen,$dining_clg);
			$hand_gloves['label'] = 'Hand Gloves Used By Serving People';
			$hand_gloves['value'] = $daily['Kitchen']['Hand Gloves Used By Serving People'];
			array_push($kitchen,$hand_gloves);
			$staff_test['label'] = 'Staffmembers Tasty Food Before Serving Meals';
			$staff_test['value'] = $daily['Kitchen']['Staffmembers Tasty Food Before Serving Meals'];
			array_push($kitchen,$staff_test);
			$wellness_clg['label'] = 'Wellness Centre Cleanliness';
			$wellness_clg['value'] = $daily['Kitchen']['Wellness Centre Cleanliness'];
			array_push($kitchen,$wellness_clg);
			$wellness_clg_times['label'] = 'Cleanliness Of The Wellness Centre';
			$wellness_clg_times['value'] = $daily['Kitchen']['Cleanliness Of The Wellness Centre'];
			array_push($kitchen,$wellness_clg_times);
			}
			
			//weekly
			// Cleanliness //WATRER SUPPLY CND
			if(count($weekly) > 1)
			{
			$water_sp_ro['label'] = 'RO Plant';
			$water_sp_ro['value'] = $weekly['Water Supply Condition']['RO Plant'];
			array_push($water_Supply_Condition,$water_sp_ro);

			$water_sp_bp['label'] = 'Bore Water';
			$water_sp_bp['value'] = $weekly['Water Supply Condition']['Bore Water'];
			array_push($water_Supply_Condition,$water_sp_bp);
			$water_sp_noplant['label'] = 'No Plant Working';
			$water_sp_noplant['value'] = $weekly['Water Supply Condition']['No Plant Working'];
			array_push($water_Supply_Condition,$water_sp_noplant);
			$water_sp_clg['label'] = 'Water Tank Cleaning';
			$water_sp_clg['value'] = $weekly['Water Supply Condition']['Water Tank Cleaning'];
			array_push($water_Supply_Condition,$water_sp_clg);
			
			$dormitories_clg = array();
			$dormitories_clg['label'] = 'Dormitory Cleaning';
			$dormitories_clg['value'] = $weekly['Dormitories']['Dormitory Cleaning'];
			array_push($dormitories,$dormitories_clg);
			$dormitories_times['label'] = 'Cleanliness Of The Dormitory Room';
			$dormitories_times['value'] = $weekly['Dormitories']['Cleanliness Of The Dormitory Room'];
			array_push($dormitories,$dormitories_times);
			$damges_beds['label'] = 'Any Damages To Beds';
			$damges_beds['value'] = $weekly['Dormitories']['Any Damages To Beds'];
			array_push($dormitories,$damges_beds);
			
			//$store_clg = array();
			$store_clg['label'] = 'Store Room Cleanliness';
			$store_clg['value'] = $weekly['Store']['Store Room Cleanliness'];
			array_push($store,$store_clg);
			$store_clg['label'] = 'Cleanliness of The Store Room';
			$store_clg['value'] = $weekly['Store']['Cleanliness of The Store Room'];
			array_push($store,$store_clg);
			$store_clg['label'] = 'Proper Storage of ITEMS';
			$store_clg['value'] = $weekly['Store']['Proper Storage of ITEMS'];
			array_push($store,$store_clg);
			$store_clg['label'] = 'Any Default Items Issued';
			$store_clg['value'] = $weekly['Store']['Any Default Items Issued'];
			array_push($store,$store_clg);
			
			//$waste_mg = array();
			$waste_mg['label'] = 'Separate dumping of Inorganic waste';
			$waste_mg['value'] = $weekly['Waste Management']['Separate dumping of Inorganic waste'];
			array_push($waste_Management,$waste_mg);
			$waste_mg_organic['label'] = 'Separate dumping of Organic waste';
			$waste_mg_organic['value'] = $weekly['Waste Management']['Separate dumping of Organic waste'];
			array_push($waste_Management,$waste_mg_organic);
			$waste_mg_dustbins['label'] = 'Dustbins';
			$waste_mg_dustbins['value'] = $weekly['Waste Management']['Dustbins'];
			array_push($waste_Management,$waste_mg_dustbins);
		}
			/*$water_m = array();*/
			if(count($monthly) > 0 ){
			$water_m['label'] = 'Water Loading Areas';
			$water_m['value'] = $monthly['Water']['Water Loading Areas'];
			array_push($water,$water_m);
			$water_load_times['label'] = 'Warter loading Areas Times';
			$water_load_times['value'] = $monthly['Water']['Warter loading Areas Times'];
			array_push($water,$water_load_times);
			}
			
		}
		
		$sanitation_output['campus']           = json_encode($campus);
		$sanitation_output['kitchen']           = json_encode($kitchen);
		$sanitation_output['toilets']      	   = json_encode($toilets);
		$sanitation_output['water_Supply_Condition']  	   = json_encode($water_Supply_Condition);
		$sanitation_output['dormitories'] 		       = json_encode($dormitories);
		$sanitation_output['store'] = json_encode($store);
		$sanitation_output['waste_management'] = json_encode($waste_Management);
		$sanitation_output['water'] = json_encode($water);

		$this->data['sanitation_report'] = $sanitation_output; 
	}
	$initiate_count_list = array();
			$doctors_email_list_dr1 = array();
			$doctors_email_list_dr2 = array();
			$doctors_email_list_dr3 = array();
			$doctors_email_list_dr4 = array();
			$doctors_email_list_dr5 = array();
			$doctors_email_list_dr6 = array();
			$doctors_email_list_dr7 = array();

			$initiate_count = $this->ci->panacea_common_model->get_initaite_requests_count($today_date,$school_name);

			$normal_requests_count = $this->ci->panacea_common_model->get_normal_requests_count($today_date,$school_name);

			$emergency_requests_count = $this->ci->panacea_common_model->get_emergency_requests_count($today_date,$school_name);

			$chronic_requests_count = $this->ci->panacea_common_model->get_chronic_requests_count($today_date,$school_name);


			//$request_type_counts = $this->ci->panacea_common_model->get_total_issues_requests_count($today_date,$school_name);
			$doctors_response_count = $this->ci->panacea_common_model->get_doctors_response_count($today_date,$school_name);

			
			$this->data['initiate_request_count_for_today'] = count($initiate_count);
			$this->data['normal_requests_count'] = count($normal_requests_count);
			$this->data['emergency_requests_count'] = count($emergency_requests_count);
			$this->data['chronic_requests_count'] = count($chronic_requests_count);
			
			$this->data['doctors_count'] = count($doctors_response_count);
		
		if(count($doctors_response_count)>0)
		{
			foreach($doctors_response_count as $index => $history)
			{
				/*$end_history = end($history);
				
				if(preg_match("/panacea.dr/i", $end_history['submitted_by']))
				{

				}*/
				$doctor_submitted_list = $history['history'][1]['submitted_by'];
				if($doctor_submitted_list == "panacea.dr1#gmail.com")
				{
					$this->data['doctor_name_dr1'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr1,$doctor_submitted_list);
					$this->data['doctors_count_list_dr1'] = count($doctors_email_list_dr1);
				}
				else if($doctor_submitted_list == "panacea.dr2#gmail.com")
				{
					$this->data['doctor_name_dr2'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr2,$doctor_submitted_list);
					$this->data['doctors_count_list_dr2'] = count($doctors_email_list_dr2);
				}
				else if($doctor_submitted_list == "panacea.dr3#gmail.com")
				{
					$this->data['doctor_name_dr3'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr3,$doctor_submitted_list);
					$this->data['doctors_count_list_dr3'] = count($doctors_email_list_dr3);
				}
				else if($doctor_submitted_list == "panacea.dr4#gmail.com")
				{
					$this->data['doctor_name_dr4'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr4,$doctor_submitted_list);
					$this->data['doctors_count_list_dr4'] = count($doctors_email_list_dr4);
				}
				else if($doctor_submitted_list == "panacea.dr5#gmail.com")
				{
					$this->data['doctor_name_dr5'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr5,$doctor_submitted_list);
					$this->data['doctors_count_list_dr5'] = count($doctors_email_list_dr5);
				}
				else if($doctor_submitted_list == "panacea.dr6#gmail.com")
				{
					$this->data['doctor_name_dr6'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr6,$doctor_submitted_list);
					$this->data['doctors_count_list_dr6'] = count($doctors_email_list_dr6);
				}
				else if($doctor_submitted_list == "panacea.dr7#gmail.com")
				{
					$this->data['doctor_name_dr7'] = $history['history'][1]['submitted_by_name'];
					array_push($doctors_email_list_dr7,$doctor_submitted_list);
					$this->data['doctors_count_list_dr7'] =  count($doctors_email_list_dr7);
				}
			}
			
		}
		$this->data['screening_report'] = $this->ci->panacea_common_model->get_all_screenings_count_by_school($email, $date = false, $screening_duration = "Yearly");

			$this->data['school_email_id'] = $email;
			
			return json_encode($this->data);
		}

		public function send_message_to_doctors($request_type,$unique_id,$student_name,$issueinfo_1)
		{			
			/*$doctors_numbers = array();
	  	  	$doctors_1 = "8179075893";
	  	    $doctors_2 = "7680094841";
	  	    $doctors_3 = "8019069273";
	  	    $doctors_4 = "8639470830";
	  	    $doctors_5 = "9866530361";
	  	    //$doctors_6 = "9121174432";
	  	    
	  	    array_push($doctors_numbers, $doctors_1);
	  	    array_push($doctors_numbers, $doctors_2);
	  	    array_push($doctors_numbers, $doctors_3);
	  	    array_push($doctors_numbers, $doctors_4);
	  	    array_push($doctors_numbers, $doctors_5);
	  	   // array_push($doctors_numbers, $doctors_6);
				  	   
	  	    foreach ($doctors_numbers as $number)
	  	    {	
	  	    	$message = "New : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type." Issues:".$issueinfo_1;
	  	    	$sms =  $this->ci->bhashsms->send_sms($number,$message);
	  	    }*/
		}

	public function send_message_to_doctors_update($request_type,$unique_id,$student_name,$issueinfo_1)
	{
		/*$doctors_numbers = array();
		$doctors_1 = "8179075893";
  	    $doctors_2 = "7680094841";
  	    $doctors_3 = "8019069273";
  	    $doctors_4 = "8639470830";
  	    $doctors_5 = "9866530361";
  	    //$doctors_6 = "9121174432";
  	      	   
  	    
  	    array_push($doctors_numbers, $doctors_1);
  	    array_push($doctors_numbers, $doctors_2);
  	    array_push($doctors_numbers, $doctors_3);
  	    array_push($doctors_numbers, $doctors_4);
  	    array_push($doctors_numbers, $doctors_5);
  	   // array_push($doctors_numbers, $doctors_6);
  	   
  	    
  	    foreach ($doctors_numbers as $number)
  	    {
  	    	$message = "Update : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type." Issues:".$issueinfo_1;
			$sms =  $this->ci->bhashsms->send_sms($number,$message);
  	    }*/
	}

	// Sends Push notification for iOS users
	/*public function fcm_message_notification_ios($request_type,$unique_id,$student_name)
	{


		// (iOS) Private key's passphrase.
		//private static $passphrase = 'joashp';
		$passphrase = '1234';
		
		// Message payload
		$data = array (
			'mtitle' => $request_type,
			'mdesc' => "Student ID : ".$unique_id." Name : ".$student_name,
		);
		
		$deviceToken = "7de9a8ca35c26423095cf41373eae9a2e1673c174a3c67c335a505f83e5fe963";
		$ctx = stream_context_create();
		
		// ck.pem is your certificate file
		 stream_context_set_option($ctx, 'ssl', 'local_cert', IOS_PEM);
		//stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'tls://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
			    'title' => $data['mtitle'],
                'body' => $data['mdesc'],
			 ),
			'sound' => 'default'
		);

		//$body['aps'] = array(
        //'alert' => $message,
       // 'sound' => 'default'
       // );
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		
		// Close the connection to the server
		fclose($fp);
		if (!$result)
			return 'Message not delivered' . PHP_EOL;
		else
			return 'Message successfully delivered' . PHP_EOL;	
	}*/

	public function fcm_message_notification($request_type,$unique_id,$student_name,$user_type = false)
	{
		$message= $request_type;  
		$title= "New: ".$unique_id.": ".$student_name;
		$date='FCM';
		$path_to_fcm='https://fcm.googleapis.com/fcm/send';
		//$server_key="AIzaSyDvt3dpbX4f0cUZbpsuQgNziUV4hzMD8gU";
		$server_key="AIzaSyB424Ma6dDfdzf2ELLY9YqUG-ud09iuUXM";

		if(!empty($user_type))
		{
			$query = $this->ci->panacea_common_model->get_fcm_token($user_type);

			foreach ($query as $fcm_token_doctor) 
			{
				//$fcm_token = $query[0]['fcm_token'];
				/*$sql="select fcm_token from fcm_info where topic='$topic'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_row($result);
				$key=$row[0];*/

				$headers=array('Authorization:key='.$server_key,
				               'Content-Type:application/json');
				               
				 $fields=array('to' => $fcm_token_doctor['fcm_token'],
				               'notification' => array('title' => $title,'body' => $message,'tag' => "test_tag"));

				//$sql1="insert into notification_message(title,number,message)values('$title','$date','$message')";

				/*$query = $this->ci->panacea_common_model->insert_into_notification_message($title,$date,$message);

				                if ($query) {
				              // $last_id = mysqli_insert_id($con);
				                //$ar['Ann_id']=$last_id ;
				                $status =1;
				                $message="Added Succesfully";
				                $ar['message']=$message;
				                } else {
				                $message="Not Added";
				                $ar['message']=$message;
				                }*/
				               
				               
				               
				$payload=json_encode($fields);

				$curl_session=curl_init();
				curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
				curl_setopt($curl_session, CURLOPT_POST, true);
				curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
				curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

				$result=curl_exec($curl_session);

				curl_close($curl_session);
				//mysqli_close($con);
				/*$ar['status']=$status;
				   $ajson = array();
				   $ajson[] = $ar;
				   $finalresult=json_encode($ajson);
				   echo $finalresult;*/
				   //echo $sql1;


				/*$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message."\nOutput: ".$finalresult;
				        fwrite($myfile, $txt);*/
				        $myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message;
				        fwrite($myfile, $txt);
				     //    fwrite($myfile,$sql);
				        fclose($myfile);
				
			}
		}		
	}

	public function fcm_message_notification_update($request_type,$unique_id,$student_name,$user_type = false)
	{
		$message= $request_type;  
		$title= "Update: ".$unique_id.": ".$student_name;
		$date='FCM';
		$path_to_fcm='https://fcm.googleapis.com/fcm/send';
		$server_key="AIzaSyB424Ma6dDfdzf2ELLY9YqUG-ud09iuUXM";

		if(!empty($user_type))
		{
			$query = $this->ci->panacea_common_model->get_fcm_token($user_type);

			foreach ($query as $fcm_token_doctor)
			{

				$headers=array('Authorization:key='.$server_key,
				               'Content-Type:application/json');
				               
				 $fields=array('to' => $fcm_token_doctor['fcm_token'],
				               'notification' => array('title' => $title,'body' => $message,'tag' => "test_tag"));

				$payload=json_encode($fields);
				$curl_session=curl_init();
				curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
				curl_setopt($curl_session, CURLOPT_POST, true);
				curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
				curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

				$result=curl_exec($curl_session);

				curl_close($curl_session);
				$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				$txt = "title:".$title."message: ".$message;
				fwrite($myfile, $txt);
				//    fwrite($myfile,$sql);
				fclose($myfile);			
			}
		}

	}

	public function fcm_message_notification_doctor_update($request_type,$unique_id,$student_name)
	{
				$message= $request_type;  
				$title= "Dr Response: ".$unique_id.": ".$student_name;
				$date='FCM';
				$path_to_fcm='https://fcm.googleapis.com/fcm/send';
				$server_key="AIzaSyB424Ma6dDfdzf2ELLY9YqUG-ud09iuUXM";
				
				//$query = $this->ci->panacea_common_model->get_check_email_id_for_fcm($email);


				/*$sql="select fcm_token from fcm_info where topic='$topic'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_row($result);
				$key=$row[0];*/

				$headers=array('Authorization:key='.$server_key,
				               'Content-Type:application/json');
				               
				 $fields=array('to'=>"/topics/all",
				               'notification'=>array('title'=>$title,'body'=>$message,'tag'=>"test_tag"));
				               
				                 	//$ar=array();

				//$sql1="insert into notification_message(title,number,message)values('$title','$date','$message')";

				/*$query = $this->ci->panacea_common_model->insert_into_notification_message($title,$date,$message);

				                if ($query) {
				              // $last_id = mysqli_insert_id($con);
				                //$ar['Ann_id']=$last_id ;
				                $status =1;
				                $message="Added Succesfully";
				                $ar['message']=$message;
				                } else {
				                $message="Not Added";
				                $ar['message']=$message;
				                }*/
				               
				               
				               
				$payload=json_encode($fields);

				$curl_session=curl_init();
				curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
				curl_setopt($curl_session, CURLOPT_POST, true);
				curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
				curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

				$result=curl_exec($curl_session);

				curl_close($curl_session);
				//mysqli_close($con);
				/*$ar['status']=$status;
				   $ajson = array();
				   $ajson[] = $ar;
				   $finalresult=json_encode($ajson);
				   echo $finalresult;*/
				   //echo $sql1;


				/*$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message."\nOutput: ".$finalresult;
				        fwrite($myfile, $txt);*/
				        $myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
				        $txt = "title:".$title."message: ".$message;
				        fwrite($myfile, $txt);
				     //    fwrite($myfile,$sql);
				        fclose($myfile);
	}


	function panacea_imports_bmi_values()
	{

		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		return $this->data;
	}
	function imports_bmi_values($post)
	{

		$dt_name = $post['district_name'];
		$school_name = $post['school_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital unique id",	"student name",	"class","section","date","height in cms",	"weight","bmi");
		//log_message('error',"imports_bmi_values".print_r($header_array,TRUE));exit();
		//echo print_r($header_array,TRUE);exit();
		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');


		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				
				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$temp_data = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");

							$data_value = preg_replace('~\x{00a0}~siu',' ',$temp_data);

						if($check_col_array[$header_row] == "height in cms")
						{
							//$regex = '/\.+/i';
							//$matches = preg_match('/\.+/i', $data_value);

							if(preg_match('/\.+/i', $data_value) && empty($data_value))
							{
								$this->data['message'] = "Uploaded file Height values contain Dot(.) or empty value, So Please Enter Height in cms";
								$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
							//str_replace(".", '$data_value/100', subject)
							//$data_value = substr($data_value,0,10);
						}
						if($check_col_array[$header_row] == "weight")
						{
							if(preg_match('/\.+/i', $data_value) && empty($data_value))
							{
								$this->data['message'] = "Uploaded file Weight contain Empty, So Please Enter Student Weight";
								$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
						}

						if($check_col_array[$header_row] == "class")
						{
							if(isset($data_value) && !empty($data_value))
							{

							}else{
								$this->data['message'] = "Uploaded file Class contain Empty, So Please Enter Student Class";
								$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
						}
						if($check_col_array[$header_row] == "section")
						{
							if(isset($data_value) && !empty($data_value))
							{

							}else{
								$this->data['message'] = "Uploaded file Section contain Empty, So Please Enter Student Section";
								$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
						}

						

						if($check_col_array[$header_row] == 'date')
						{
							try {
								//$date = new DateTime('2000-01-01');

								log_message('debug','11111111111111111111111111111111111111111.'.print_r($data_value,true));
								if(isset($data_value) || $data_value == "" || $data_value == " "){
								}else{
									$date = new DateTime($data_value);
									$data_value= $date->format('Y-m-d');
								}
								log_message('debug','2222222222222222222222222222222222222222.'.print_r($data_value,true));


							} catch (Exception $e) {
								//echo $e->getMessage();
									//exit(1);
								unlink($updata['upload_data']['full_path']);
								$colCoordinate = $cell->getCoordinate();

								session_start();
								$_SESSION['request_message'] = "Update falied due to following error in excel sheet: value in ".$colCoordinate." seems to of different type that of <strong>text</strong>.";


									//session_start();
								$message = "";
								if(!empty($_SESSION['request_message']))
									$message = $_SESSION['request_message'];
									// rest of your code
								$this->data['message'] = $message;

								$this->data['error'] = "excel_sheet_faild";

								return $this->data;
									//redirect('panacea_common_lib/panacea_reports_students_redirect');

							}
						}

						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;

				for($j=2;$j<count($arr_data)+2;$j++)
				{
					$unique_id = $arr_data[$j]['hospital unique id'];

					$is_exists = $this->ci->panacea_common_model->check_if_doc_exists($unique_id);
					if(!empty($is_exists)){
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_refld'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_refld'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_refld'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];

						 $height_in_cms = $arr_data[$j]['height in cms'];
						 $weight_in_kgs = $arr_data[$j]['weight'];
						 $month = $arr_data[$j]['date'];
					 	//$bmi = $arr_data[$j]['bmi'];

						 // BMI Generation
					  	$bmi    = 0;
					  	$height = (int) $height_in_cms;
						//log_message("debug","heighttttttttttt".print_r($height,true));
					  	$weight = (int) $weight_in_kgs;
						//log_message("debug","weightttttttttt".print_r($height,true));
						if(($height > 0) && ($weight > 0))
						{
						   $height = ($height/100);
						   $bmi    = ($weight / ($height * $height));
						   //log_message("debug","bmiiiiiiiiiiiiiii".print_r($bmi,true));
						   $bmi    = (float) $bmi;
						   $bmi_final  = round($bmi,1);
						}

						 $new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m');
					  
					    $monthly_bmi = array(
					  		'height' => $height_in_cms,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => (string)$bmi_final,
					  		'month'  => $ndate
					    );
						 $doc_data['widget_data']['page1']['Student Details']['BMI_values'] = $monthly_bmi;
						 $existing_update = $this->ci->panacea_common_model->update_bmi_values($ndate,$monthly_bmi,$unique_id);

					}else{
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_refld'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_refld'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_refld'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['school_details']['District'] = $dt_name;
						$doc_data['school_details']['School Name'] = $school_name;

						 $height_in_cms = $arr_data[$j]['height in cms'];
						 $weight_in_kgs = $arr_data[$j]['weight'];
						 $month = $arr_data[$j]['date'];
						 //$bmi = $arr_data[$j]['bmi'];

							// BMI Generation
					  	$bmi       = 0;
					  	$bmi_final = array();
					  	$height    = (int) $height_in_cms;
					  	$weight    = (int) $weight_in_kgs;
				
						if(($height > 0) && ($weight > 0))
						{
						   $height = ($height/100);
						   $bmi    = ($weight / ($height * $height));
						   $bmi    = (float) $bmi;
						   $bmi_   = round($bmi,1);
						}
			  
					  	$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m');
					  
					    $monthly_bmi = array(
					  		'height' => $height_in_cms,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => (string)$bmi_,
					  		'month'  => $ndate
					    );

			    	array_push($bmi_final,$monthly_bmi);

						 $doc_data['widget_data']['page1']['Student Details']['BMI_values'] = $bmi_final;
						$doc_properties['doc_id'] = get_unique_id();
						$doc_properties['status'] = 1;
						$doc_properties['_version'] = 1;
						$doc_properties['doc_owner'] = "PANACEA";
						$doc_properties['unique_id'] = '';
						$doc_properties['doc_flow'] = "new";

						$history['last_stage']['current_stage'] = "stage1";
						$history['last_stage']['approval'] = "true";
						$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$history['last_stage']['time'] = date("Y-m-d H:i:s");

						$this->ci->panacea_common_model->create_bmi_values_monthly($doc_data,$doc_properties,$history);

						$count++;
					}

				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				//redirect('panacea_mgmt/panacea_mgmt_diagnostic');
				return "redirect_to_bmi_fn";
				
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}

	function panacea_imports_hb_values()
	{

		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();

		return $this->data;
	}
	function imports_hb_values($post)
	{

		$dt_name = $post['district_name'];
		$school_name = $post['school_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital unique id",	"student name",	"class","section","date","hb","blood group");
		//log_message('error',"imports_bmi_values".print_r($header_array,TRUE));exit();
		//echo print_r($header_array,TRUE);exit();
		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');

		$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
		if($gender_info == "B")
		{
			$gender = "Male";
		}else if($gender_info == "G")
		{
			$gender = "Female";
		}

		if ($this->ci->upload->do_upload("file"))
		{
			$updata = array('upload_data' => $this->ci->upload->data());
			$file = $updata['upload_data']['full_path'];

			//load the excel library
			$this->ci->load->library('excel');
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format

			$check_col_array = [];

			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("A1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("B1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("C1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("D1")->getValue()));
			// 			array_push($check_col_array,strtolower($objPHPExcel->getActiveSheet()->getCell("E1")->getValue()));

			$row = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				//echo $cell->getValue();
				array_push($check_col_array,strtolower($cell->getValue()));
			}
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));

			$check = array_diff($header_array,$check_col_array);
			//log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check,true));
			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				
				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					foreach ($cellIterator as $cell) {

						$temp_data = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");

							$data_value = preg_replace('~\x{00a0}~siu',' ',$temp_data);

						/*if($check_col_array[$header_row] == "height in cms")
						{
							//$regex = '/\.+/i';
							//$matches = preg_match('/\.+/i', $data_value);

							if(preg_match('/\.+/i', $data_value))
							{
								$this->data['message'] = "Uploaded file Height values contain Dot(.), So Please Enter Height in cms";
								$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
							//str_replace(".", '$data_value/100', subject)
							$data_value = substr($data_value,0,10);
						}*/

						$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
						$header_row ++;
					}
				}

				$doc_data = array();
				$form_data = array();
				$count = 0;

				for($j=2;$j<count($arr_data)+2;$j++)
				{
					$unique_id = $arr_data[$j]['hospital unique id'];

					$is_exists = $this->ci->panacea_common_model->check_if_doc_exists_hb($unique_id);
					if(!empty($is_exists)){
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_refld'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_refld'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_refld'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup'] = $arr_data[$j]['blood group'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender)?$gender:"";

						 $hb_value = $arr_data[$j]['hb'];
						 $month = $arr_data[$j]['date'];
					 	//$bmi = $arr_data[$j]['bmi'];

						 

						 $new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m');
					  
					    $monthly_hb = array(
					  		'hb'    => $hb_value,
					  		'month'  => $ndate
					    );
						 $doc_data['widget_data']['page1']['Student Details']['HB_values'] = $monthly_hb;
						 $existing_update = $this->ci->panacea_common_model->update_hb_values($ndate,$monthly_hb,$unique_id);

					}else{
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_refld'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_refld'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_refld'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup'] = $arr_data[$j]['blood group'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender)?$gender:"";

						$doc_data['school_details']['District'] = $dt_name;
						$doc_data['school_details']['School Name'] = $school_name;

						
						if($arr_data[$j]['class'] == "5")
						{
							$age = 10;
						}else if($arr_data[$j]['class'] == "6")
						{
							$age = 11;
						}else if($arr_data[$j]['class'] == "7")
						{
							$age = 12;
						}else if($arr_data[$j]['class'] == "8")
						{
							$age = 13;
						}else if($arr_data[$j]['class'] == "9")
						{
							$age = 14;
						}else if($arr_data[$j]['class'] == "10")
						{
							$age = 15;
						}elseif ($arr_data[$j]['class'] == "11") 
						{
							$age = 16;
						}elseif($arr_data[$j]['class'] == "12")
						{
							$age = 17;
						}elseif($arr_data[$j]['class'] == "Degree 1st")
						{
							$age = 18;
						}elseif($arr_data[$j]['class'] == "Degree 2nd")
						{
							$age = 19;
						}elseif($arr_data[$j]['class'] == "Degree 3rd")
						{
							$age = 20;
						}

						$doc_data['widget_data']['page1']['Student Details']['Age'] = (!empty($age) && isset($age)) ? $age : "";

						 $hb_value = $arr_data[$j]['hb'];
						 $month = $arr_data[$j]['date'];
						 //$bmi = $arr_data[$j]['bmi'];

							
					  	$hb_final = array();
					  	
			  
					  	$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m');
					  
					    $monthly_hb = array(
					  		'hb'    => $hb_value,
					  		'month'  => $ndate
					    );

			    	array_push($hb_final,$monthly_hb);

						 $doc_data['widget_data']['page1']['Student Details']['HB_values'] = $hb_final;
						$doc_properties['doc_id'] = get_unique_id();
						$doc_properties['status'] = 1;
						$doc_properties['_version'] = 1;
						$doc_properties['doc_owner'] = "PANACEA";
						$doc_properties['unique_id'] = '';
						$doc_properties['doc_flow'] = "new";

						$history['last_stage']['current_stage'] = "stage1";
						$history['last_stage']['approval'] = "true";
						$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$history['last_stage']['time'] = date("Y-m-d H:i:s");

						$this->ci->panacea_common_model->create_hb_values_monthly($doc_data,$doc_properties,$history);

						$count++;
					}

				}


				//===============================================

				unlink($updata['upload_data']['full_path']);

				//redirect('panacea_mgmt/panacea_mgmt_diagnostic');
				return "redirect_to_hb_fn";
				
			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->panacea_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}


	public function get_study_circle_data_lib($post)
	{
		$docs = $this->ci->panacea_common_model->get_study_circle_data_model($post);
		$this->data['docs'] = $docs['screening'];
		//$this->data['docs_requests'] = $docs['request'];
		/* 
		$docs = $this->ci->panacea_common_model->get_student_hospital_report($post['uid']);
		$this->data['hospital_reports'] = $docs['get_hospital_report'];
		$this->data['docscount'] = count($this->data['docs']); */
	
		return $this->data;
	}


public function get_excel_for_students_reports_lib($district, $scl)
{
    $this->ci->load->library('excel');

    $objPHPExcel = new PHPExcel();

    //set Properties

    $objPHPExcel->getProperties()->setCreator("Havik Healthcare Technologies Pvt. Ltd");
    $objPHPExcel->getProperties()->setLastModifiedBy("Yoga");
    $objPHPExcel->getProperties()->setTitle("Students Reorts");
    $objPHPExcel->getProperties()->setSubject("Students Reorts");
    $objPHPExcel->getProperties()->setDescription("Students Reorts");

    //Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet(0); //select sheet index you want to print
    $objWorkSheet->setTitle("Students Report");
    $objWorkSheet->getRowDimension(1)->setRowHeight(44);

    //Add Styles
    $styleArray = array(
            'borders' => array('allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
            'color' => array('rgb' => '000000')))
    );

    $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
    $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $styleArray = array(
            'font'  => array(
                    'bold'  => true,
                    'name'  => 'Calibri'),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DCE6F1') ));

    //Set Cells

    $objWorkSheet->setCellValue('A1', 'Student ID')->getStyle('A1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('B1', 'Student Name')->getStyle('B1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('C1', 'Class')->getStyle('C1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('D1', 'Section')->getStyle('F1')->applyFromArray($styleArray);  
    $objWorkSheet->setCellValue('E1', 'School Name')->getStyle('D1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('F1', 'District')->getStyle('E1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('G1', 'Father name')->getStyle('F1')->applyFromArray($styleArray);  
    $objWorkSheet->setCellValue('H1', 'Mobile NO')->getStyle('F1')->applyFromArray($styleArray);  
    
    
   

    $objWorkSheet->getColumnDimension("A")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("B")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("C")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("D")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("E")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("F")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("G")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("H")->setAutoSize(true);
    
       
    $reports_data = $this->ci->panacea_common_model->get_excel_for_students_reports_model($district, $scl);

    $cell_index = 2;

    foreach ($reports_data as $student_data) {
     
        $objWorkSheet->setCellValue('A'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Personal Information"]["Hospital Unique ID"]);
        $objWorkSheet->setCellValue('B'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Personal Information"]["Name"]);
        $objWorkSheet->setCellValue('C'.$cell_index, $student_data ["doc_data"]["widget_data"]["page2"]["Personal Information"]["Class"]);
        $objWorkSheet->setCellValue('D'.$cell_index, $student_data ["doc_data"]["widget_data"]["page2"]["Personal Information"]["Section"]);
        $objWorkSheet->setCellValue('E'.$cell_index, $student_data ["doc_data"]["widget_data"]["page2"]["Personal Information"]["School Name"]);
        $objWorkSheet->setCellValue('F'.$cell_index, $student_data ["doc_data"]["widget_data"]["page2"]["Personal Information"]["District"]);
        $objWorkSheet->setCellValue('G'.$cell_index, $student_data ["doc_data"]["widget_data"]["page2"]["Personal Information"]["Father Name"]);
        $objWorkSheet->setCellValue('H'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Personal Information"]["Mobile"]["mob_num"]);
        
       
       
        $cell_index ++;

    }

    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    $file_save = BASEDIR.TENANT.'/'."Student-Reports.xlsx";
    $file_name = URLCustomer."Student-Reports.xlsx";
    $objWriter->save($file_save);
   
    return $file_name;

}

public function get_excel_covid_cured_cases_students_lib($start_date, $end_date)
{

   
    $this->ci->load->library('excel');

    $objPHPExcel = new PHPExcel();

    //set Properties

    $objPHPExcel->getProperties()->setCreator("Havik Healthcare Technologies Pvt. Ltd");
    $objPHPExcel->getProperties()->setLastModifiedBy("Yoga");
    $objPHPExcel->getProperties()->setTitle("Students Reorts");
    $objPHPExcel->getProperties()->setSubject("Students Reorts");
    $objPHPExcel->getProperties()->setDescription("Students Reorts");

    //Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet(0); //select sheet index you want to print
    $objWorkSheet->setTitle("Students Report");
    $objWorkSheet->getRowDimension(1)->setRowHeight(44);

    //Add Styles
    $styleArray = array(
            'borders' => array('allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
            'color' => array('rgb' => '000000')))
    );

    $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
    $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $styleArray = array(
            'font'  => array(
                    'bold'  => true,
                    'name'  => 'Calibri'),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DCE6F1') ));

    //Set Cells

    $objWorkSheet->setCellValue('A1', 'Student ID')->getStyle('A1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('B1', 'Student Name')->getStyle('B1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('C1', 'Class')->getStyle('C1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('D1', 'School Name')->getStyle('D1')->applyFromArray($styleArray);
    $objWorkSheet->setCellValue('E1', 'Request Raised Time')->getStyle('E1')->applyFromArray($styleArray); 

    $objWorkSheet->getColumnDimension("A")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("B")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("C")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("D")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("E")->setAutoSize(true);

    $reports_data = $this->ci->panacea_common_model->get_excel_covid_cured_cases_students_lib_model($start_date, $end_date);

    $cell_index = 2;

    foreach ($reports_data as $student_data) { 

        $objWorkSheet->setCellValue('A'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Student Info"]["Unique ID"]);
        $objWorkSheet->setCellValue('B'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Student Info"]["Name"]["field_ref"]);
        $objWorkSheet->setCellValue('C'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Student Info"]["Class"]["field_ref"]);
        $objWorkSheet->setCellValue('D'.$cell_index, $student_data ["doc_data"]["widget_data"]["page1"]["Student Info"]["School Name"]["field_ref"]);
       $objWorkSheet->setCellValue('E'.$cell_index, $student_data ["history"]['0']['time']);
        $cell_index ++;

    }

    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    $file_save = BASEDIR.TENANT.'/'."Student-Reports.xlsx";
    $file_name = URLCustomer."Student-Reports.xlsx";
    $objWriter->save($file_save);
   
    return $file_name;

} 

	
	
}
