<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tswreis_schools_common_lib 
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
		$this->ci->load->model('tswreis_schools_common_model');
		$this->ci->load->model('panacea_common_model');
		$this->ci->load->library('paas_common_lib');
		$this->ci->load->library('bhashsms');
	
	}

	public function classes($school_code)
	{
	
		$total_rows = $this->ci->tswreis_schools_common_model->classescount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['classes'] = $this->ci->tswreis_schools_common_model->get_classes($config['per_page'], $page, $school_code);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');
	
		$this->data['classescount'] = $total_rows;
	
		return $this->data;
	}
	
	public function sections($school_code)
	{
	
		$total_rows = $this->ci->tswreis_schools_common_model->sectionscount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['sections'] = $this->ci->tswreis_schools_common_model->get_sections($config['per_page'], $page, $school_code);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');
	
		$this->data['sectionscount'] = $total_rows;
	
		return $this->data;
	}
	
	public function reports_display_ehr_uid($unique_id,$school_name)
	{
		$docs = $this->ci->panacea_common_model->get_reports_ehr_uid($unique_id,$school_name);
	
		$this->data['docs']          = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['hs'] = $docs['hs'];
		$this->data['BMI_report'] = $docs['BMI_report'];
		$this->data['hb_report'] = $docs['hb_report'];

		if(isset($docs['notes']))
		{
			$this->data['notes'] = $docs['notes'];
		}
		else
		{
			$this->data['notes'] = "";
		}
	
		$this->data['docscount'] = count($this->data['docs']);
		$this->data['fromEHR'] = "fromEHRSearch" ;
		
		//log_message('debug','this->data=====680=='.print_r($this->data,true));
	
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
	
	function build_sanitation_report($sanitation_report)
	{
	   // Variables
	   $handwash 	      = array();
	   $kitchen  		  = array();
	   $cleanliness 	  = array();
	   $food 	  		  = array();
	   $waste_management  = array();
	   $sanitation_output = array();
	   
	   if(isset($sanitation_report) && !empty($sanitation_report))
		{
	      foreach($sanitation_report as $index => $value)
		  {
			$widget_data          = $value['doc_data']['widget_data'];
			$external_attachments = $value['doc_data']['external_attachments'];
			
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			
			// Hand wash
			$hand_sanitizers = array();
			$hand_sanitizers['label'] = 'Hand sanitizers/soap used';
			$hand_sanitizers['value'] = $page1['Hand Wash']['Hand sanitizers/soap used'];
			array_push($handwash,$hand_sanitizers);
			
			// Kitchen
			$food_stored = array();
			$food_stored['label'] = 'Food stored and served';
			$food_stored['value'] = $page1['Kitchen']['Food stored and served with tight containers'];
			array_push($kitchen,$food_stored);
			
			$avail_perishable_prod = array();
			$avail_perishable_prod['label'] = 'Availabilities of storage of perishable products';
			$avail_perishable_prod['value'] = $page1['Kitchen']['Availabilities of storage of perishable products'];
			array_push($kitchen,$avail_perishable_prod);
			
			// Cleanliness
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = $page2['Cleanliness']['Dormitories'];
			array_push($cleanliness,$dormitories);
			
			$kitchen_room = array();
			$kitchen_room['label'] = 'Kitchen';
			$kitchen_room['value'] = $page2['Cleanliness']['Kitchen'];
			array_push($cleanliness,$kitchen_room);
			
			$dining_halls = array();
			$dining_halls['label'] = 'Dining Halls';
			$dining_halls['value'] = $page2['Cleanliness']['Dining Halls'];
			array_push($cleanliness,$dining_halls);
			
			$class_rooms = array();
			$class_rooms['label'] = 'Class Rooms';
			$class_rooms['value'] = $page2['Cleanliness']['Class Rooms'];
			array_push($cleanliness,$class_rooms);
			
			$sick_rooms = array();
			$sick_rooms['label'] = 'Sick Rooms';
			$sick_rooms['value'] = $page2['Cleanliness']['Sick Rooms'];
			array_push($cleanliness,$sick_rooms);
			
			$staff_rooms = array();
			$staff_rooms['label'] = 'Staff Rooms';
			$staff_rooms['value'] = $page2['Cleanliness']['Staff Rooms'];
			array_push($cleanliness,$staff_rooms);
			
			$water_tanks = array();
			$water_tanks['label'] = 'Water Tanks';
			$water_tanks['value'] = $page3['Cleanliness']['Water Tanks'];
			array_push($cleanliness,$water_tanks);
			
			$dust_bins = array();
			$dust_bins['label'] = 'Dust Bins';
			$dust_bins['value'] = $page3['Cleanliness']['Dust Bins'];
			array_push($cleanliness,$dust_bins);
			
			$toilets = array();
			$toilets['label'] = 'Toilets';
			$toilets['value'] = $page3['Cleanliness']['Toilets'];
			array_push($cleanliness,$toilets);
			
			$kitchen_utensils = array();
			$kitchen_utensils['label'] = 'Kitchen Utensils';
			$kitchen_utensils['value'] = $page3['Cleanliness']['Kitchen Utensils'];
			array_push($cleanliness,$kitchen_utensils);
			
			// Food
			$food_menu_wise = array();
			$food_menu_wise['label'] = 'Food prepared according to the days menu';
			$food_menu_wise['value'] = $page3['Food']['Food prepared according to the days menu'];
			array_push($food,$food_menu_wise);
			
			$wears_gloves_caps = array();
			$wears_gloves_caps['label'] = 'Kitchen staff wears gloves ans caps while serving';
			$wears_gloves_caps['value'] = $page3['Food']['Kitchen staff wears gloves ans caps while serving'];
			array_push($food,$wears_gloves_caps);
			
			$meal_tasted_by_staff = array();
			$meal_tasted_by_staff['label'] = 'Every meal is tasted by a staff members before serving';
			$meal_tasted_by_staff['value'] = $page3['Food']['Every meal is tasted by a staff members before serving'];
			array_push($food,$meal_tasted_by_staff);
			
			// Waste Management
			$inorganic_separate_dumping = array();
			$inorganic_separate_dumping['label'] = 'Separate dumping of Inorganic waste';
			$inorganic_separate_dumping['value'] = $page4['Waste Management']['Separate dumping of Inorganic waste'];
			array_push($waste_management,$inorganic_separate_dumping);
			
			$organic_separate_dumping = array();
			$organic_separate_dumping['label'] = 'Separate dumping of Organic waste';
			$organic_separate_dumping['value'] = $page4['Waste Management']['Separate dumping of Organic waste'];
			array_push($waste_management,$organic_separate_dumping);
		}
		
		$sanitation_output['handwash']         = json_encode($handwash);
		$sanitation_output['kitchen']      	   = json_encode($kitchen);
		$sanitation_output['cleanliness']  	   = json_encode($cleanliness);
		$sanitation_output['food'] 		       = json_encode($food);
		$sanitation_output['waste_management'] = json_encode($waste_management);
		$sanitation_output['external_attachments'] = json_encode($external_attachments);
		
		return $sanitation_output;
		}
	}
	
	function to_dashboard($date = FALSE, $request_duration = "Monthly", $screening_duration = "Yearly")
	{
	    // Variables
		$toilets            = array();
		$hand_sanitizers    = array();
		$disposable_bins    = array();
		$water_dispensaries = array();
		$children_seating   = array();
		$bar_chart_data     = array();
	    $pagenumber         = array();
	    $page_data          = array();
		
		$sanitation_report_app = array();
		$count 				   = 0;
		
		// Loggedin user
		$loggedinuser = $this->ci->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$school_data    = explode(".",$email);
		$district_code  = strtoupper($school_data[0]);
		$school_code    = (int) $school_data[1];
		
		$school_color_code = $this->ci->tswreis_schools_common_model->get_school_color_code($school_code);
		
		$this->data['school_color_code'] = $school_color_code;
		
		$school_name = $this->ci->tswreis_schools_common_model->get_school_details_for_school_code($school_code);
		
		$hs_req_docs  = $this->ci->tswreis_schools_common_model->get_hs_req_docs($collection);
		
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}
		
		
		$absent_report = $this->ci->tswreis_schools_common_model->get_all_absent_data($date,$school_name['school_name']);
		
		$submit_report = $this->ci->tswreis_schools_common_model->get_attendance_submitted_school_name($date,$school_name['school_name']);


		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
		}
		else if($submit_report)
		{
			$this->data['absent_report'] = 2;

		}
		else{
			$this->data['absent_report'] = 1;
		}
		
		$this->data['screening_report'] = 1; 
		
		$sanitation_infra_report = $this->ci->tswreis_schools_common_model->get_sanitation_infrastructure_model($school_name['dt_name'],$school_name['school_name']);
		
		if(isset($sanitation_infra_report) && !empty($sanitation_infra_report))
		{
	      foreach($sanitation_infra_report as $index => $value)
		  {
			$widget_data = $value['doc_data']['widget_data'];
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			$page5 = $widget_data['page5'];
			$page6 = $widget_data['page6'];
			
			// Toilets
			$buckets = array();
			$buckets['label'] = 'Buckets';
			$buckets['value'] = (int) $page1['Toilets']['Buckets'];
			array_push($toilets,$buckets);
			
			$mugs = array();
			$mugs['label'] = 'Mugs';
			$mugs['value'] = (int) $page1['Toilets']['Mugs'];
			array_push($toilets,$mugs);
			
			$dust_bin = array();
			$dust_bin['label'] = 'Dust Bins';
			$dust_bin['value'] = (int) $page1['Toilets']['Dust Bins'];
			array_push($toilets,$dust_bin);
			
			$soap = array();
			$soap['label'] = 'Soap';
			$soap['value'] = (int) $page1['Toilets']['Soap'];
			array_push($toilets,$soap);
			
			$running_water = array();
			$running_water['label'] = 'Running Water';
			$running_water['value'] = (int) $page4['Water Facility']['Running water(number of taps)'];
			array_push($toilets,$running_water);
			
			$store_water = array();
			$store_water['label'] = 'Store Water';
			$store_water['value'] = (int) $page4['Water Facility']['Store water'];
			array_push($toilets,$store_water);
			
			// Hand Sanitizers
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page2['Hand Wash']['Dining Halls'];
			array_push($hand_sanitizers,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page2['Hand Wash']['Kitchen'];
			array_push($hand_sanitizers,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Kitchen';
			$classroom['value'] = (int) $page2['Hand Wash']['Class Rooms'];
			array_push($hand_sanitizers,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page2['Hand Wash']['Dormitories'];
			array_push($hand_sanitizers,$dormitories);
			
			// Disposable Bins
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page3['Waste Management']['Dining Halls'];
			array_push($disposable_bins,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page3['Waste Management']['Kitchen'];
			array_push($disposable_bins,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page3['Waste Management']['Class Rooms'];
			array_push($disposable_bins,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page3['Waste Management']['Dormitories'];
			array_push($disposable_bins,$dormitories);
			
			// Water Dispensaries
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page4['Water Facility']['Dining Halls'];
			array_push($water_dispensaries,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page4['Water Facility']['Kitchen'];
			array_push($water_dispensaries,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page4['Water Facility']['Class Rooms'];
			array_push($water_dispensaries,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page4['Water Facility']['Dormitories'];
			array_push($water_dispensaries,$dormitories);
			
			// Children Seating
			$floor = array();
			$floor['label'] = 'Floor';
			$floor['value'] = (int) $page5['Dining Hall']['Floor'];
			array_push($children_seating,$floor);
			
			$table_chairs = array();
			$table_chairs['label'] = 'Table and Chairs';
			$table_chairs['value'] = (int) $page5['Dining Hall']['Table and Chairs'];
			array_push($children_seating,$table_chairs);
			
			$benches = array();
			$benches['label'] = 'Benches';
			$benches['value'] = (int) $page5['Dining Hall']['Benches'];
			array_push($children_seating,$benches);
		}
		
		$bar_chart_data['toilets']            = json_encode($toilets);
		$bar_chart_data['hand_sanitizers']    = json_encode($hand_sanitizers);
		$bar_chart_data['disposable_bins']    = json_encode($disposable_bins);
		$bar_chart_data['water_dispensaries'] = json_encode($water_dispensaries);
		$bar_chart_data['children_seating']   = json_encode($children_seating);
		
			$this->data['sanitation_infra_data'] = json_encode($bar_chart_data);
		}
		else
		{
			$this->data['sanitation_infra_data'] = 1;
		} 
		
		$count = 0;
		$unique_id_pattern = $district_code."_".$school_code."_";
		$symptoms_report = $this->ci->tswreis_schools_common_model->get_all_symptoms($date,$request_duration,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
		
		
	
		$count = 0;
		$request_report = $this->ci->tswreis_schools_common_model->get_all_requests($date,$request_duration,$unique_id_pattern);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$count = 0;
		$screening_report = $this->ci->tswreis_schools_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1; 
		} 

        $chronic_ids = $this->ci->tswreis_schools_common_model->get_all_chronic_unique_ids_model($school_name['school_name']);
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['last_screening_update'] = $this->ci->tswreis_schools_common_model->get_last_screening_update(); 

		$this->data ['news_feeds'] = $this->ci->tswreis_schools_common_model->get_all_news_feeds();
		
		$this->data['students_count'] = $this->ci->tswreis_schools_common_model->studentscount($school_name['school_name']);
		
		$this->data['yearly_request_count'] = $this->ci->tswreis_schools_common_model->get_all_requests_yearly_count($date,$request_duration="Yearly",$unique_id_pattern);
		
		$this->data['screening_report_yearly'] = $this->ci->tswreis_schools_common_model->get_screened_students_count($unique_id_pattern);
		/* $request_report_yearly = $this->ci->tswreis_schools_common_model->get_all_requests_yearly_count($date,$request_duration="Yearly",$unique_id_pattern); */
		//$this->data['yearly_request_count'] = $request_report_yearly[0] + $request_report_yearly[1] + $request_report_yearly[2];
	
		$this->data['message'] = $this->ci->session->flashdata('message');
		
		$this->data['today_date'] = date('Y-m-d');
	
		return $this->data;
	
	}
	
	function to_dashboard_with_date($date = FALSE, $request_duration = "Monthly", $screening_duration = "Yearly")
	{
	    // Variables
		$toilets            = array();
		$hand_sanitizers    = array();
		$disposable_bins    = array();
		$water_dispensaries = array();
		$children_seating   = array();
		$bar_chart_data     = array();
	    $pagenumber         = array();
	    $page_data          = array();
		
		$sanitation_report_app = array();
		$count 				   = 0;
		
		// Loggedin user
		$loggedinuser = $this->ci->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$school_data    = explode(".",$email);
		$district_code  = strtoupper($school_data[0]);
		$school_code    = (int) $school_data[1];
		
		$unique_id_pattern = $district_code."_".$school_code."_";
		
		$school_name = $this->ci->tswreis_schools_common_model->get_school_details_for_school_code($school_code);
		
		$absent_report = $this->ci->tswreis_schools_common_model->get_all_absent_data($date,$school_name['school_name']);
		
		$submit_report = $this->ci->tswreis_schools_common_model->get_attendance_submitted_school_name($date,$school_name['school_name']);

		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
		}
		else if($submit_report)
		{
			$this->data['absent_report'] = 2;

		}
		else{
			$this->data['absent_report'] = 1;
		}
		
		$count = 0;
		$symptoms_report = $this->ci->tswreis_schools_common_model->get_all_symptoms($date,$request_duration,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
		
		$count = 0;
		$request_report = $this->ci->tswreis_schools_common_model->get_all_requests($date,$request_duration,$unique_id_pattern);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$sanitation_infra_report = $this->ci->tswreis_schools_common_model->get_sanitation_infrastructure_model($school_name['dt_name'],$school_name['school_name']);
		
		if(isset($sanitation_infra_report) && !empty($sanitation_infra_report))
		{
	      foreach($sanitation_infra_report as $index => $value)
		  {
			$widget_data = $value['doc_data']['widget_data'];
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			$page5 = $widget_data['page5'];
			$page6 = $widget_data['page6'];
			
			// Toilets
			$buckets = array();
			$buckets['label'] = 'Buckets';
			$buckets['value'] = (int) $page1['Toilets']['Buckets'];
			array_push($toilets,$buckets);
			
			$mugs = array();
			$mugs['label'] = 'Mugs';
			$mugs['value'] = (int) $page1['Toilets']['Mugs'];
			array_push($toilets,$mugs);
			
			$dust_bin = array();
			$dust_bin['label'] = 'Dust Bins';
			$dust_bin['value'] = (int) $page1['Toilets']['Dust Bins'];
			array_push($toilets,$dust_bin);
			
			$soap = array();
			$soap['label'] = 'Soap';
			$soap['value'] = (int) $page1['Toilets']['Soap'];
			array_push($toilets,$soap);
			
			$running_water = array();
			$running_water['label'] = 'Running Water';
			$running_water['value'] = (int) $page4['Water Facility']['Running water(number of taps)'];
			array_push($toilets,$running_water);
			
			$store_water = array();
			$store_water['label'] = 'Store Water';
			$store_water['value'] = (int) $page4['Water Facility']['Store water'];
			array_push($toilets,$store_water);
			
			// Hand Sanitizers
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page2['Hand Wash']['Dining Halls'];
			array_push($hand_sanitizers,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page2['Hand Wash']['Kitchen'];
			array_push($hand_sanitizers,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Kitchen';
			$classroom['value'] = (int) $page2['Hand Wash']['Class Rooms'];
			array_push($hand_sanitizers,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page2['Hand Wash']['Dormitories'];
			array_push($hand_sanitizers,$dormitories);
			
			// Disposable Bins
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page3['Waste Management']['Dining Halls'];
			array_push($disposable_bins,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page3['Waste Management']['Kitchen'];
			array_push($disposable_bins,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page3['Waste Management']['Class Rooms'];
			array_push($disposable_bins,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page3['Waste Management']['Dormitories'];
			array_push($disposable_bins,$dormitories);
			
			// Water Dispensaries
			$dining_hall = array();
			$dining_hall['label'] = 'Dining Halls';
			$dining_hall['value'] = (int) $page4['Water Facility']['Dining Halls'];
			array_push($water_dispensaries,$dining_hall);
			
			$kitchen = array();
			$kitchen['label'] = 'Kitchen';
			$kitchen['value'] = (int) $page4['Water Facility']['Kitchen'];
			array_push($water_dispensaries,$kitchen);
			
			$classroom = array();
			$classroom['label'] = 'Class Rooms';
			$classroom['value'] = (int) $page4['Water Facility']['Class Rooms'];
			array_push($water_dispensaries,$classroom);
			
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = (int) $page4['Water Facility']['Dormitories'];
			array_push($water_dispensaries,$dormitories);
			
			// Children Seating
			$floor = array();
			$floor['label'] = 'Floor';
			$floor['value'] = (int) $page5['Dining Hall']['Floor'];
			array_push($children_seating,$floor);
			
			$table_chairs = array();
			$table_chairs['label'] = 'Table and Chairs';
			$table_chairs['value'] = (int) $page5['Dining Hall']['Table and Chairs'];
			array_push($children_seating,$table_chairs);
			
			$benches = array();
			$benches['label'] = 'Benches';
			$benches['value'] = (int) $page5['Dining Hall']['Benches'];
			array_push($children_seating,$benches);
		}
		
		$bar_chart_data['toilets']            = json_encode($toilets);
		$bar_chart_data['hand_sanitizers']    = json_encode($hand_sanitizers);
		$bar_chart_data['disposable_bins']    = json_encode($disposable_bins);
		$bar_chart_data['water_dispensaries'] = json_encode($water_dispensaries);
		$bar_chart_data['children_seating']   = json_encode($children_seating);
		
			$this->data['sanitation_infra_data'] = json_encode($bar_chart_data);
		}
		else
		{
			$this->data['sanitation_infra_data'] = 1;
		}
		
		/*$count = 0;
		$screening_report = $this->ci->tswreis_schools_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}*/
        $chronic_ids = $this->ci->tswreis_schools_common_model->get_all_chronic_unique_ids_model($school_name['school_name']);
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['screening_report'] = 1;

		$this->data ['news_feeds'] = json_encode($this->ci->tswreis_schools_common_model->get_today_news_feeds($date));
		
		$this->data['last_screening_update'] = $this->ci->tswreis_schools_common_model->get_last_screening_update();
	
		return json_encode($this->data);
	
	}
	
	function update_request_pie($date = FALSE,$request_pie_span  = "Monthly")
	{
	    // Loggedin user
		$loggedinuser = $this->ci->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$school_data    = explode(".",$email);
		$district_code  = strtoupper($school_data[0]);
		$school_code    = $school_data[1];
		
		$unique_id_pattern = $district_code."_".$school_code."_";
		
		$count = 0;
		$symptoms_report = $this->ci->tswreis_schools_common_model->get_all_symptoms($date,$request_pie_span,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		$request_report = $this->ci->tswreis_schools_common_model->get_all_requests($date,$request_pie_span,$unique_id_pattern);
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
	
	function update_screening_pie($date = FALSE,$screening_pie_span  = "Yearly")
	{
	
	$count = 0;
		$screening_report = $this->ci->tswreis_schools_common_model->get_all_screenings($date,$screening_pie_span);
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
		ini_set ( 'memory_limit', '1G' );
		$import_type   = $post['import_type'];
	
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
					$insert_count = 0;
	
					for($j=2;$j<count($arr_data)+2;$j++){
	
						$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Personal Information']['Photo'] = "";
						$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['date of birth'];
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '91';
						$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['mobile number'];
						$doc_data['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['ad no'];
						$doc_data['widget_data']['page2']['Personal Information']['Class'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page2']['Personal Information']['District'] = $arr_data[$j]['district'];
						$doc_data['widget_data']['page2']['Personal Information']['School Name'] = $arr_data[$j]['school name'];
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
						$insert_count++;
							
						$count++;
					}
	
	
					//===============================================
	
					unlink($updata['upload_data']['full_path']);
					
					session_start();
					$_SESSION['updated_message'] = "Successfully inserted ".$insert_count." student(s) document.";
	
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
	
				$doc_data = array();
				$form_data = array();
				$count = 0;
	
				$update_count = 0;
	
				for($j=2;$j<count($arr_data)+2;$j++){
						
					$unique_id = $arr_data[$j]['hospital unique id'];
					$doc = $this->ci->panacea_common_model->get_students_uid($unique_id);
					//log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddd.'.print_r($doc,true));
					if($doc){
	
						$doc_id = $doc['_id'];
	
						if(isset($arr_data[$j]['student name']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['student name'];
	
						if(isset($arr_data[$j]['date of birth']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['date of birth'];
	
						if(isset($arr_data[$j]['mobile number']))
							$doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['mobile number'];
	
						if(isset($arr_data[$j]['ad no']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['ad no'];
	
						if(isset($arr_data[$j]['class']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Class'] = $arr_data[$j]['class'];
	
						if(isset($arr_data[$j]['section']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['section'];
	
						if(isset($arr_data[$j]['district']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = $arr_data[$j]['district'];
	
						if(isset($arr_data[$j]['school name']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = $arr_data[$j]['school name'];
	
						if(isset($arr_data[$j]['father name']))
							$doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'] = $arr_data[$j]['father name'];
	
	
						$doc['history']['last_stage']['current_stage'] = "stage1";
						$doc['history']['last_stage']['approval'] = "true";
						$doc['history']['last_stage']['submitted_by'] = "medusersw1#gmail.com";
						$doc['history']['last_stage']['time'] = date("Y-m-d H:i:s");
	
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
			$this->data['message'] = $this->upload->display_errors();
			$this->data['error'] = "file_upload_failed";
	
			return $this->data;
		}
	}
	
	public function panacea_update_personal_ehr_uid($post)
	{
		ini_set ('memory_limit', '1G');
		$docs = $this->ci->tswreis_schools_common_model->get_update_personal_ehr_uid($post);
		$this->data['docs'] = $docs['screening'];
		 
		//$this->data['docs_requests'] = $docs['request'];
		/* 
		$docs = $this->ci->panacea_common_model->get_student_hospital_report($post['uid']);
		$this->data['hospital_reports'] = $docs['get_hospital_report'];
		$this->data['docscount'] = count($this->data['docs']); */
	
		return $this->data;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: BMI PIE REPORT
	 
	 * @author bhanu 
	 */
	
	public function bmi_pie_view_lib($current_month, $school_name){
		
		$current_month = substr($current_month,0,-3);
		
		$count = 0;
		$bmi_report = $this->ci->tswreis_schools_common_model->get_bmi_report_model($current_month, $school_name);
		foreach ($bmi_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['bmi_report'] = json_encode($bmi_report);
		}else{
			$this->data['bmi_report'] = 1;
		
		}
		$this->data['bmi_submitted_month'] = date('Y-m-d');
		//$this->data['district_list'] = $this->ci->tswreis_schools_common_model->get_all_district();
		return $this->data;
	}
	
	public function bmi_pie_view_lib_month_wise($current_month, $school_name){
		$current_month = substr($current_month,0,-3);
		$count = 0;
		$bmi_report = $this->ci->tswreis_schools_common_model->get_bmi_report_model($current_month, $school_name);
		foreach ($bmi_report as $value){ 
			
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['bmi_report'] = json_encode($bmi_report);
			//log_message('debug','bmi_pie_view_lib_month_wise====4572'.print_r($this->data['bmi_report'], true));
		}else{
			$this->data['bmi_report'] = 1;
		
		}
		return $this->data;
	}
	
	public function generate_bmi_report_to_excel_lib($date, $school_name)
	{
		$date = substr($date,0,-3);
		
		//load the excel library
		$this->ci->load->library('excel');
		
		//create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Havik soft Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Naresh Reddy");
		$objPHPExcel->getProperties()->setTitle($date."-TSWREISBMI Report.xlsx");
		$objPHPExcel->getProperties()->setSubject($date."-TSWREISBMI Report.xlsx");
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
		
		$data = $this->ci->tswreis_schools_common_model->export_bmi_reports_monthly_to_excel( $date,$school_name);
		//log_message("debug","dataaaaaaaaaaaaa".print_r($data,true));
		
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
	
		$file_save = BASEDIR.TENANT.'/'.$date."-TSWREISBMI Report.xlsx";
		//log_message('debug','file_save====================2750'.print_r($file_save,true));
		$file_name = URLCustomer.$date."-TSWREISBMI Report.xlsx";
		$objWriter->save($file_save);
		
		return $file_name;
		
	}

	//hb pie
	/**
	 * Helper: hb PIE REPORT
	 
	 * @author bhanu 
	 */
	
	public function hb_pie_view_lib($current_month, $school_name){
		
		$current_month = substr($current_month,0,-3);
		
		$count = 0;
		$hb_report = $this->ci->tswreis_schools_common_model->get_hb_report_model($current_month, $school_name);
		foreach ($hb_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['hb_report'] = json_encode($hb_report);
		}else{
			$this->data['hb_report'] = 1;
		
		}
		$this->data['hb_submitted_month'] = date('Y-m-d');
		//$this->data['district_list'] = $this->ci->tswreis_schools_common_model->get_all_district();
		return $this->data;
	}
	
	public function hb_pie_view_lib_month_wise($current_month, $school_name){
		$current_month = substr($current_month,0,-3);
		$count = 0;
		$hb_report = $this->ci->tswreis_schools_common_model->get_hb_report_model($current_month, $school_name);
		
		foreach ($hb_report as $value){ 
			
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['hb_report'] = json_encode($hb_report);
			//log_message('debug','hb_pie_view_lib_month_wise====4572'.print_r($this->data['hb_report'], true));
		}else{
			$this->data['hb_report'] = 1;
		
		}
		return $this->data;
	}
	
	/*function build_sanitation_report_activities($date,$school_name)
	{
	   // Variables
	   $handwash 	      = array();
	   $kitchen  		  = array();
	   $cleanliness 	  = array();
	   $food 	  		  = array();
	   $waste_management  = array();
	   $sanitation_output = array();
	   $sanitation_report = $this->ci->tswreis_schools_common_model->get_sanitation_report_data_with_date($date,$school_name);
	   if(isset($sanitation_report) && !empty($sanitation_report))
		{
	      foreach($sanitation_report as $index => $value)
		  {
			$widget_data          = $value['doc_data']['widget_data'];
			$external_attachments = $value['doc_data']['external_attachments'];
			
			$page1 = $widget_data['page1'];
			$page2 = $widget_data['page2'];
			$page3 = $widget_data['page3'];
			$page4 = $widget_data['page4'];
			
			// Hand wash
			$hand_sanitizers = array();
			$hand_sanitizers['label'] = 'Hand sanitizers/soap used';
			$hand_sanitizers['value'] = $page1['Hand Wash']['Hand sanitizers/soap used'];
			array_push($handwash,$hand_sanitizers);
			
			// Kitchen
			$food_stored = array();
			$food_stored['label'] = 'Food stored and served';
			$food_stored['value'] = $page1['Kitchen']['Food stored and served with tight containers'];
			array_push($kitchen,$food_stored);
			
			$avail_perishable_prod = array();
			$avail_perishable_prod['label'] = 'Availabilities of storage of perishable products';
			$avail_perishable_prod['value'] = $page1['Kitchen']['Availabilities of storage of perishable products'];
			array_push($kitchen,$avail_perishable_prod);
			
			// Cleanliness
			$dormitories = array();
			$dormitories['label'] = 'Dormitories';
			$dormitories['value'] = $page2['Cleanliness']['Dormitories'];
			array_push($cleanliness,$dormitories);
			
			$kitchen_room = array();
			$kitchen_room['label'] = 'Kitchen';
			$kitchen_room['value'] = $page2['Cleanliness']['Kitchen'];
			array_push($cleanliness,$kitchen_room);
			
			$dining_halls = array();
			$dining_halls['label'] = 'Dining Halls';
			$dining_halls['value'] = $page2['Cleanliness']['Dining Halls'];
			array_push($cleanliness,$dining_halls);
			
			$class_rooms = array();
			$class_rooms['label'] = 'Class Rooms';
			$class_rooms['value'] = $page2['Cleanliness']['Class Rooms'];
			array_push($cleanliness,$class_rooms);
			
			$sick_rooms = array();
			$sick_rooms['label'] = 'Sick Rooms';
			$sick_rooms['value'] = $page2['Cleanliness']['Sick Rooms'];
			array_push($cleanliness,$sick_rooms);
			
			$staff_rooms = array();
			$staff_rooms['label'] = 'Staff Rooms';
			$staff_rooms['value'] = $page2['Cleanliness']['Staff Rooms'];
			array_push($cleanliness,$staff_rooms);
			
			$water_tanks = array();
			$water_tanks['label'] = 'Water Tanks';
			$water_tanks['value'] = $page3['Cleanliness']['Water Tanks'];
			array_push($cleanliness,$water_tanks);
			
			$dust_bins = array();
			$dust_bins['label'] = 'Dust Bins';
			$dust_bins['value'] = $page3['Cleanliness']['Dust Bins'];
			array_push($cleanliness,$dust_bins);
			
			$toilets = array();
			$toilets['label'] = 'Toilets';
			$toilets['value'] = $page3['Cleanliness']['Toilets'];
			array_push($cleanliness,$toilets);
			
			$kitchen_utensils = array();
			$kitchen_utensils['label'] = 'Kitchen Utensils';
			$kitchen_utensils['value'] = $page3['Cleanliness']['Kitchen Utensils'];
			array_push($cleanliness,$kitchen_utensils);
			
			// Food
			$food_menu_wise = array();
			$food_menu_wise['label'] = 'Food prepared according to the days menu';
			$food_menu_wise['value'] = $page3['Food']['Food prepared according to the days menu'];
			array_push($food,$food_menu_wise);
			
			$wears_gloves_caps = array();
			$wears_gloves_caps['label'] = 'Kitchen staff wears gloves ans caps while serving';
			$wears_gloves_caps['value'] = $page3['Food']['Kitchen staff wears gloves ans caps while serving'];
			array_push($food,$wears_gloves_caps);
			
			$meal_tasted_by_staff = array();
			$meal_tasted_by_staff['label'] = 'Every meal is tasted by a staff members before serving';
			$meal_tasted_by_staff['value'] = $page3['Food']['Every meal is tasted by a staff members before serving'];
			array_push($food,$meal_tasted_by_staff);
			
			// Waste Management
			$inorganic_separate_dumping = array();
			$inorganic_separate_dumping['label'] = 'Separate dumping of Inorganic waste';
			$inorganic_separate_dumping['value'] = $page4['Waste Management']['Separate dumping of Inorganic waste'];
			array_push($waste_management,$inorganic_separate_dumping);
			
			$organic_separate_dumping = array();
			$organic_separate_dumping['label'] = 'Separate dumping of Organic waste';
			$organic_separate_dumping['value'] = $page4['Waste Management']['Separate dumping of Organic waste'];
			array_push($waste_management,$organic_separate_dumping);
		}
		
		$sanitation_output['handwash']         = $handwash;
		$sanitation_output['kitchen']      	   = $kitchen;
		$sanitation_output['cleanliness']  	   = $cleanliness;
		$sanitation_output['food'] 		       = $food;
		$sanitation_output['waste_management'] = $waste_management;
		$sanitation_output['external_attachments'] = $external_attachments;
		
		return $sanitation_output;
		}
	}*/
	
	function to_dashboard_new($date = FALSE, $request_duration = "Yearly", $screening_duration = "Yearly")
	{
	    // Variables
		$toilets            = array();
		$hand_sanitizers    = array();
		$disposable_bins    = array();
		$water_dispensaries = array();
		$children_seating   = array();
		$bar_chart_data     = array();
	    $pagenumber         = array();
	    $page_data          = array();
		
		$sanitation_report_app = array();
		$count 				   = 0;
		
		// Loggedin user
		$loggedinuser = $this->ci->session->userdata("customer");
		$email        = $loggedinuser['email'];
		$col          = str_replace("@","#",$email);
		$collection   = $col.'_docs';
		
		$school_data    = explode(".",$email);
		$district_code  = strtoupper($school_data[0]);
		$school_code    = (int) $school_data[1];
	
		
		$this->data['today_date'] = date('Y-m-d');
	
		return $this->data;
	
	}

	public function reports_display_ehr_uid_new_html_static_hs($unique_id,$school_name)
	{

		$docs = $this->ci->panacea_common_model->get_reports_ehr_uid_new_html_static_hs($unique_id,$school_name);

		$regular_followup = $this->ci->panacea_common_model->get_regular_followup_data($unique_id);

		
		$this->data['docs']          = $docs['screening'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['hs'] = $docs['hs'];
		$this->data['BMI_report'] = $docs['BMI_report'];
		$this->data['hb_report'] = $docs['hb_report'];
		$this->data['regular_followup'] = $regular_followup;

		
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
	
		public function generate_health_summary_report($unique_id)
	{
	
	 $summary_report = "";
	 
	 $summary_report.= "<html><body>";
	

	   $student_docs = $this->ci->tswreis_schools_common_model->get_students_uid_for_print($unique_id);
	 	foreach ($student_docs as $student_doc) {
	 	
	 	
	/*	echo '<pre>';
	 	echo print_r($student_doc,true);
	 	echo "</pre>";
	 	exit;*/
	 /*	echo print_r($student_doc[0]['request_info'][0]['doc_data']['widget_data']['page1']['Student Info']['Name'], true);
	 	exit;*/
	   // Page wise data
	   $page1 = $student_doc['student_info']['doc_data']['widget_data']['page1'];
	   $page2 = $student_doc['student_info']['doc_data']['widget_data']['page2'];
	   $page3 = $student_doc['student_info']['doc_data']['widget_data']['page3'];
	   $page4 = $student_doc['student_info']['doc_data']['widget_data']['page4'];
	   $page5 = $student_doc['student_info']['doc_data']['widget_data']['page5'];
	   $page6 = $student_doc['student_info']['doc_data']['widget_data']['page6'];
	   $page7 = $student_doc['student_info']['doc_data']['widget_data']['page7'];
	   $page8 = $student_doc['student_info']['doc_data']['widget_data']['page8'];
	   $page9 = $student_doc['student_info']['doc_data']['widget_data']['page9'];
	   
	   
	   $summary_report.= "<div class='page_break' id='".$page1['Personal Information']['Hospital Unique ID']."'>";
	   
	   // School Information
	    $path = URLCustomer;
	   if(isset($page1['Personal Information']['Photo']['file_path']) && !is_null($page1['Personal Information']['Photo']['file_path']) && !empty($page1['Personal Information']['Photo']['file_path'])){

	    	$summary_report.="<div style='text-align:center;font-weight:bold;font-size:100%;' class='school_information'><img src=". $path.$page1['Personal Information']['Photo']['file_path']." height='80px'></div><hr>";
	   }
	    
	    
	   // Personal Information
	   $summary_report.="<div class='personal_information'><label class='title'>Personal Information</label><table><tr><td>Name : ".$page1['Personal Information']['Name']."</td><td>Class : ".$page2['Personal Information']['Class']."</td></tr><tr><td>Health Unique ID : ".$page1['Personal Information']['Hospital Unique ID']."</td><td>Section : ".$page2['Personal Information']['Section']."</td></tr><tr><td>School name:".strtolower($page2['Personal Information']['School Name'])."</td><td>School name:".$page2['Personal Information']['District']."</td></tr></table></div><hr>";
	   
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
	   $summary_report.="<div class='request_info'> ";
	  if(gettype($page1['Problem Info']['Identifier'] == "array")){
	  			$identifier = implode (", ",$page1['Problem Info']['Identifier']);
	  }else{
	  			$identifier = $page1['Problem Info']['Identifier'];
	  }
	   	
	      $summary_report.="<div class='req_information'><label class='title'><h4>".$i.")  Request raised on: ".$history['time']."</h4></label><br><label>Problem Information	</label><br><label>Request Type : ".$page2['Review Info']['Request Type']."</label><br><label>Follow Up status : ".$page2['Review Info']['Status']."</label><br><label>Problem Information : ".$identifier ." </label><br><label>Description : ".$page2['Problem Info']['Description']." </label><br><label>Diagnosis Information :  </label><br>";



	   $summary_report.="</div>";
	}


  }
	 
	   	    //Doctor Signature
/*	   $summary_report.="<div class='doctor_signature'><img src='https://mednote.in/PaaS/bootstrap/dist/img/prasad_rao_sign.png' alt='Dr Signature' height='50' width='100' style='float:right;display:block'/>
	   <label style='float:right;clear:both'>Dr.N.S D Prasadarao </label></div>"; */
	   
	   //$summary_report.="<div class='doctor_signature'><label style='float:right;clear:both;margin:5px;'>Dr.N.S D Prasadarao, MBBS,DGO,FAIMS,FCGP </label></div>";
	   
	   //Note
	   $summary_report.="<br><br><div class='note'><label>Note:-</label><label>1.The above report is autogenerated by the system. For detailed information,look into EHR.</label></div>";
	   
	   //Note
	   $summary_report.="<br><hr><div class='tlstec'><label>Digital report generated by MedNote healthcare platform by Havik Software Technologies Pvt. Ltd. <a href='http://www.tlstec.com'>(TLSTEC) </a> for <a href='http://www.sifhyd.org//'> Panacea </a></label></div>";

	   $summary_report.="</div>";

	  
	 	
	}
	 $summary_report.= "</body></html>";
	 
	 return $summary_report;
	
	}


	function build_sanitation_report_v2($sanitation_report)
	{
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
	   $campus_attachments = array();
	   $toilets_attachments = array();
	   $kitchen_attachments = array();
	   $dormitories_attachments = array();
	   
	   if(isset($sanitation_report) && !empty($sanitation_report))
		{
	      foreach($sanitation_report as $index => $value)
		  {


			$widget_data = $value['doc_data']['widget_data'];

			if(isset($value['doc_data']['widget_data']['daily']['Campus']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Campus']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Campus']['external_attachments'])){
				$campus_attachments = $value['doc_data']['widget_data']['daily']['Campus']['external_attachments'];

		
			} 
			if(isset($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'])){
				$toilets_attachments = $value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'];
		
			} 
			if(isset($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments']) && !is_null($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments']) && !empty($value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'])){
				$kitchen_attachments = $value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'];
			} 
			if(isset($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments']) && !is_null($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments']) && !empty($value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments'])){
				$dormitories_attachments = $value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments'];

			}

			$daily = $widget_data['daily'];
			$weekly = $widget_data['weekly'];


			$monthly = $widget_data['monthly'];
			
			if(isset($daily['Campus']) && count($daily['Campus']) > 1)
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
		
		$sanitation_output['campus']           				= $campus;
		$sanitation_output['kitchen']           			= $kitchen;
		$sanitation_output['toilets']      	   				= $toilets;
		$sanitation_output['water_Supply_Condition']  	   	= $water_Supply_Condition;
		$sanitation_output['dormitories'] 		       		= $dormitories;
		$sanitation_output['store'] 						= $store;
		$sanitation_output['waste_management'] 				= $waste_Management;
		$sanitation_output['water'] 						= $water;
		$sanitation_output['campus_attachments'] 			= ($campus_attachments) ? $campus_attachments : [];
		$sanitation_output['toilets_attachments'] 			= ($toilets_attachments) ? $toilets_attachments : [];
		$sanitation_output['kitchen_attachments'] 	= ($kitchen_attachments) ? $kitchen_attachments : [];
		$sanitation_output['dormitories_attachments'] = ($dormitories_attachments) ? $dormitories_attachments : [];
	
	
      
		/*echo print_r($sanitation_output,true);
		exit();*/
		return $sanitation_output;
		}
	}


	
	function imports_bmi_values($post)
	{
		$dt_name = (isset($post['district_name'])) ? $post['district_name'] : "";
		$school_name = (isset($post['school_name'])) ? $post['school_name'] : "";
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital unique id",	"student name",	"class","section","date","height in cms","height in foots","height in inchs","weight");
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

		$session_data = $this->ci->session->userdata('customer');
		$email = $session_data['email'];
		$school_data_array = explode(".",$email);

		$schoolCode        = (int) $school_data_array[1];
		$school_data = $this->ci->tswreis_schools_common_model->get_school_information_for_school_code($schoolCode);
 		$principal_name = $school_data['contact_person_name'];
 		$principal_mob = $school_data['school_mob'];

		$health_supervisor = $this->ci->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
	 	$hs_name = $health_supervisor['hs_name'];
	 	$hs_mob  = $health_supervisor['hs_mob'];

		 /*if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ci->ion_auth->health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->ci->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
		 }*/

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

			if (count($check)==0) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				
				for($each_row=2 ; $each_row<=$total_rows ; $each_row++){
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($each_row)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					$header_row = 0;
					$height_value = "";
					foreach ($cellIterator as $cell)
					{
						$temp_data = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");
						$data_value = preg_replace('~\x{00a0}~siu',' ',$temp_data);

						if($check_col_array[$header_row] == "hospital unique id")
						{
							$unique_id = $data_value;
							/*// Loggedin user
							$loggedinuser = $this->ci->session->userdata("customer");
							$email        = $loggedinuser['email'];
							$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
							
							//preg_match('/^$unique_id/',$data_value);
							if(preg_match("/^$unique_id/i",$data_value) && !empty($data_value))
							{
								
							}else{
								$this->data['message'] = "Uploaded file Unique ID value is Empty Or Not Matched, So Please Enter Unique ID ";
							$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
			
							$this->data['error'] = "excel_column_check_fail";
							return $this->data;
							}*/
						}
						if($check_col_array[$header_row] == "height in foots")
						{

							/*if(!preg_match('/\.+/i', $data_value))
							{
								//echo print_r($data_value,TRUE);exit();
								$this->data['message'] = "Uploaded file Height values is wrong, So Please Enter Height in foots(Eg:5.5) ".$unique_id;
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
							else
							{
								$height_value = $data_value;
								log_message('error','height_value=======3411'.print_r($height_value,TRUE));
							}	*/					
							
						}
						if($check_col_array[$header_row] == "height in inchs")
						{							
							$height_value = $data_value;		
							log_message('error','height_value=======3419'.print_r($height_value,TRUE));		
						}
						if($check_col_array[$header_row] == "height in cms")
						{
							if(preg_match('/\.+/i', $data_value))
							{
								//echo print_r($data_value,TRUE);exit();
								$this->data['message'] = "Uploaded file Height values contain Dot(.), So Please Enter Height in cms(Eg: 155) ".$unique_id;
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
							/*else
							{
								$height_value = $data_value;
								log_message('error','height_value=======3393'.print_r($height_value,TRUE));
							}*/						
							
						}

						/*if(!empty($height_value))
						{
							log_message('error','height_value=======3421111====='.print_r($height_value,TRUE));
								
						}else{
							log_message('error','height_value=======3424====='.print_r($height_value,TRUE));
							$this->data['message'] = "Uploaded file Height values are Empty, So Please Enter Height value.";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
						}*/

						if($check_col_array[$header_row] == "weight")
						{
							if(preg_match('/\.+/i', $data_value) || empty($data_value))
							{
								$this->data['message'] = "Uploaded file Weight contain Empty, So Please Enter Student Weight";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
						}

						if($check_col_array[$header_row] == "class")
						{
							//echo print_r($data_value,true);exit();
							if(isset($data_value) && !empty($data_value))
							{

							}else{
								$this->data['message'] = "Uploaded file Class contain Empty, So Please Enter Student Class";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
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
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
						}

						

						if($check_col_array[$header_row] == 'date')
						{
							try {
								//$date = new DateTime('2000-01-01');

								if(isset($data_value) || $data_value == "" || $data_value == " "){
								}else{
									$date = new DateTime($data_value);
									$data_value= $date->format('Y-m-d');
								}
								


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
					$age = "";
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

					$is_exists = $this->ci->tswreis_schools_common_model->check_if_doc_exists($unique_id);
					if(!empty($is_exists))
					{
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender) ? $gender : "";
						$doc_data['widget_data']['page1']['Student Details']['Age'] = ($age) ? $age : "";
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						if(isset($arr_data[$j]['height in cms']) && !empty($arr_data[$j]['height in cms']))
						{
							$height_in_cms = $arr_data[$j]['height in cms'];
						}else if(isset($arr_data[$j]['height in foots']) && !empty($arr_data[$j]['height in foots']))
						{
							$height_in_cms = ($arr_data[$j]['height in foots'] * 30.48);
						}else if(isset($arr_data[$j]['height in inchs']) && !empty($arr_data[$j]['height in inchs']))
						{
							$height_in_cms = ($arr_data[$j]['height in inchs'] * 2.54);							
						}
						 
						 $weight_in_kgs = $arr_data[$j]['weight'];
						 $month = $arr_data[$j]['date'];
					 	//$bmi = $arr_data[$j]['bmi'];

						 // BMI Generation
						 $height_final = round($height_in_cms,0);
					  	$bmi    = 0;
					  	$height = (int) $height_final;
						//log_message("debug","heighttttttttttt".print_r($height,true));
					  	$weight = (int) $weight_in_kgs;
						//log_message("debug","weightttttttttt".print_r($height,true));
						if(($height > 0) && ($weight > 0))
						{
						   $height = ($height/100);
						   $bmi    = ($weight / ($height * $height));
						   //log_message("debug","bmiiiiiiiiiiiiiii".print_r($bmi,true));
						   $bmi    = (double) $bmi;
						   $bmi_final  = round($bmi,1);
						}
						$bmi_count_value = $bmi_final;
						 $new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
					  	$bmi_final_value = array();
					    $monthly_bmi = array(
					  		'height' => (string)$height_final,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => (double)$bmi_count_value,
					  		'month'  => $ndate
					    );
					    array_push($bmi_final_value, $monthly_bmi);

					     $school_details = array('hs_name' => $hs_name,
												'hs_mob' => $hs_mob,
												'principal_name' => $principal_name,
												'principal_mob' => $principal_mob,
												'School Name' => $school_name,
												'District' => $dt_name );

						   if($bmi_count_value <= 14 )
						   {
							   	$bmi_values_data = array(
							    	'Student Unique ID' => $unique_id,
							    	'Name' => $arr_data[$j]['student name'],
							    	'Class' => $arr_data[$j]['class'],
							    	'BMI_values'	=> $bmi_final_value,  	  	
							    	'school_details' => $school_details,
							    	'msg_count' => 0,
							    	'ecc_count' => 0
							    );
						   }else if($bmi_count_value <= 18.5 )
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final_value,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }else if($bmi_count_value >= 25.0 || $bmi_count_value <= 29.9)
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final_value,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }else if($bmi_count_value >= 30)
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final_value,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }
						 $doc_data['widget_data']['page1']['Student Details']['BMI_values'] = $monthly_bmi;
						 $existing_update = $this->ci->tswreis_schools_common_model->panacea_update_bmi_values($ndate,$monthly_bmi,$unique_id,$bmi_values_data);

						 if($existing_update){
						 	if($bmi_count_value <= 14)
						 	{
						 		$message = "Severe Under Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
						 	}
							else if($bmi_count_value <= 18.5) 
							{
								$message = "Under Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($bmi_count_value >= 25.0 && $bmi_count_value <= 29.9) {
								$message = "Over Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
							elseif ($bmi_count_value >= 30) {
								$message = "Obese Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
							
						}

					}else{
						$unique_id = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender) ? $gender : "";
						$doc_data['widget_data']['page1']['Student Details']['Age'] = ($age) ? $age : "";
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['school_details']['District'] = $dt_name;
						$doc_data['widget_data']['school_details']['School Name'] = $school_name;

						if(isset($arr_data[$j]['height in cms']) && !empty($arr_data[$j]['height in cms']))
						{
							$height_in_cms = $arr_data[$j]['height in cms'];
						}else if(isset($arr_data[$j]['height in foots']) && !empty($arr_data[$j]['height in foots']))
						{
							$height_in_cms = ($arr_data[$j]['height in foots'] * 30.48);
						}else if(isset($arr_data[$j]['height in inchs']) && !empty($arr_data[$j]['height in inchs']))
						{
							$height_in_cms = ($arr_data[$j]['height in inchs'] * 2.54);
						}
						 $weight_in_kgs = $arr_data[$j]['weight'];
						 $month = $arr_data[$j]['date'];
						 //$bmi = $arr_data[$j]['bmi'];
						 $height_final = round($height_in_cms,0);
							// BMI Generation
					  	$bmi       = 0;
					  	$bmi_final = array();
					  	$height    = (int) $height_in_cms;
					  	$weight    = (int) $weight_in_kgs;
				
						if(($height > 0) && ($weight > 0))
						{
						   $height = ($height/100);
						   $bmi    = ($weight / ($height * $height));
						   $bmi    = (double) $bmi;
						   $bmi_   = round($bmi,1);
						}
			  
					  	$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
					  $bmi_count_value = (double)$bmi_;
					    $monthly_bmi = array(
					  		'height' => (string)$height_final,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => $bmi_count_value,
					  		'month'  => $ndate
					    );

			    	array_push($bmi_final,$monthly_bmi);

			    	 $school_details = array('hs_name' => $hs_name,
												'hs_mob' => $hs_mob,
												'principal_name' => $principal_name,
												'principal_mob' => $principal_mob,
												'School Name' => $school_name,
												'District' => $dt_name );
						   if($bmi_count_value <= 14 )
						   {
							   	$bmi_values_data = array(
							    	'Student Unique ID' => $unique_id,
							    	'Name' => $arr_data[$j]['student name'],
							    	'Class' => $arr_data[$j]['class'],
							    	'BMI_values'	=> $bmi_final,  	  	
							    	'school_details' => $school_details,
							    	'msg_count' => 0,
							    	'ecc_count' => 0
							    );
						   }else if($bmi_count_value <= 18.5 )
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }else if($bmi_count_value >= 25.0 || $bmi_count_value <= 29.9)
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }else if($bmi_count_value >= 30)
						   {
							   	$bmi_values_data = array(
								    	'Student Unique ID' => $unique_id,
								    	'Name' => $arr_data[$j]['student name'],
								    	'Class' => $arr_data[$j]['class'],
								    	'BMI_values'	=> $bmi_final,  	  	
								    	'school_details' => $school_details,
								    	'msg_count' => 0,
								    	'ecc_count' => 0
								    );
						   }

						 $doc_data['widget_data']['page1']['Student Details']['BMI_values'] = $bmi_final;
						 $doc_data['widget_data']['page1']['Student Details']['BMI_latest'] = $monthly_bmi;
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

						$new_created = $this->ci->tswreis_schools_common_model->create_bmi_values_monthly($doc_data,$doc_properties,$history,$bmi_values_data);

						if($new_created){
							if($bmi_count_value <= 14)
						 	{
						 		$message = "Severe Under Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
						 	}
							else if($bmi_count_value <= 18.5) 
							{
								$message = "Under Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($bmi_count_value >= 25.0 && $bmi_count_value <= 29.9) {
								$message = "Over Weight Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
							elseif ($bmi_count_value >= 30) {
								$message = "Obese Child Observed Details: Name : ".$arr_data[$j]['student name']." U ID : ".$arr_data[$j]['hospital unique id']." Class: ".$arr_data[$j]['class']." BMI : ".$bmi_count_value;
								$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
							
						}

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
				$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}

	
	function imports_hb_values($post)
	{

		$dt_name = $post['district_name'];
		$school_name = $post['school_name'];
		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital unique id",	"student name",	"class","section","date","hb","blood group");
		
		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "xlsx|xls";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;

		$this->ci->load->library('upload', $config);
		$this->ci->load->helper('file');

		$session_data = $this->ci->session->userdata('customer');
		 $email = $session_data['email'];
		 $school_data_array = explode(".",$email);

		 $schoolCode        = (int) $school_data_array[1];

		/* if(array_key_exists("user_type",$session_data))
		 {
			if($session_data['user_type'] == "HS")
			{
				$health_supervisor = $this->ci->ion_auth->health_supervisor()->row();
				$hs_name = $health_supervisor->hs_name;
				$hs_mob  = $health_supervisor->hs_mob;
			}
			else
			{
			 	 $health_supervisor = $this->ci->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
			 	 echo print_r($health_supervisor,TRUE);exit();
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];		
			}
			
		 	

		 }*/

		 	$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}

			 $school_data = $this->ci->tswreis_schools_common_model->get_school_information_for_school_code($schoolCode);
		 		$principal_name = $school_data['contact_person_name'];
		 		$principal_mob = $school_data['school_mob'];

			 $health_supervisor = $this->ci->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
		 	 	$hs_name = $health_supervisor['hs_name'];
		 	 	$hs_mob  = $health_supervisor['hs_mob'];

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

							if($check_col_array[$header_row] == "hospital unique id")
							{
								$unique_id = $data_value;
								
								/*// Loggedin user
								$loggedinuser = $this->ci->session->userdata("customer");
								$email        = $loggedinuser['email'];
								$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
								
								
								if(preg_match("/^$unique_id/",$data_value) && !empty($data_value))
								{
									
								}else{
									$this->data['message'] = "Uploaded file Unique ID value is Empty Or Not Matched, So Please Enter Unique ID ";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}*/
							}

						if($check_col_array[$header_row] == "class")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Class value is Empty, So Please Enter Class ";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}

							if($check_col_array[$header_row] == "section")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Section value is Empty, So Please Enter Section ";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}

							if($check_col_array[$header_row] == "hb")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file HB value is Empty, So Please Enter HB ";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}
							if($check_col_array[$header_row] == "bloodgroup")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Blood Group value is Empty, So Please Enter Blood Group ";
								$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
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
					$hb_values_data = array();
					$unique_id = $arr_data[$j]['hospital unique id'];

					$age = "";
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

					$is_exists = $this->ci->tswreis_schools_common_model->check_if_doc_exists_hb($unique_id);
					if(!empty($is_exists)){
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup']['field_ref'] = $arr_data[$j]['blood group'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender)?$gender:"";
						$doc_data['widget_data']['page1']['Student Details']['Age'] = ($age)?$age:"";

						 $hb_value = $arr_data[$j]['hb'];
						 $month = $arr_data[$j]['date'];
					 	//$bmi = $arr_data[$j]['bmi'];

						$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
					  	$hb_count_value = (double) $hb_value;
					  	$hb_final = array();
					    $monthly_hb = array(
					  		'hb'    => $hb_count_value,
					  		'month'  => $ndate
					    );

					    array_push($hb_final, $monthly_hb);

					    $school_details = array('hs_name' => $hs_name,
												'hs_mob' => $hs_mob,
												'principal_name' => $principal_name,
												'principal_mob' => $principal_mob,
												'School Name' => $school_name,
												'District' => $dt_name );
					   if($hb_count_value < 13 )
					   {
						   	$hb_values_data = array(
						    	'Student Unique ID' => $unique_id,
						    	'Name' => $arr_data[$j]['student name'],
						    	'Class' => $arr_data[$j]['class'],
						    	'HB_values'	=> $hb_final,  	  	
						    	'school_details' => $school_details,
						    	'msg_count' => 0,
						    	'ecc_count' => 0
						    );
					   }
					    $doc_data['widget_data']['page1']['Student Details']['HB_values'] = $monthly_hb;
						 $existing_update = $this->ci->tswreis_schools_common_model->panacea_update_hb_values($ndate,$monthly_hb,$unique_id,$hb_values_data);

						 if($existing_update)
						 {
					//	 	log_message('error','hb_value===========3863'.print_r($hb_value,TRUE));
						  if($hb_count_value <= 6)
							{
								$message = "A Child with severe anemia found, Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$arr_data[$j]['hospital unique id'].", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif($hb_count_value <= 8)
							{
								$message = "Sevier Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$unique_id.", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($hb_count_value >= 8.1 && $hb_count_value <= 10) {
								$message = "Moderate Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$unique_id.", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($hb_count_value >= 10.1 && $hb_count_value <= 12) {
								$message = "Mild Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$unique_id.", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
						 }

					}else{
						
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup']['field_ref'] = $arr_data[$j]['blood group'];
						$doc_data['widget_data']['page1']['Student Details']['Gender'] = ($gender)?$gender:"";
						$doc_data['widget_data']['page1']['Student Details']['Age'] = ($age) ? $age : "";

						$doc_data['widget_data']['school_details']['District'] = $dt_name;
						$doc_data['widget_data']['school_details']['School Name'] = $school_name;

						 $hb_value = $arr_data[$j]['hb'];
						 $month = $arr_data[$j]['date'];
						 //$bmi = $arr_data[$j]['bmi'];							
					  	$hb_final = array();					  	
			  
					  	$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
					  	$hb_count_value = (double) $hb_value;
					    $monthly_hb = array(
					  		'hb'    => $hb_count_value,
					  		'month'  => $ndate
					    );

			    	array_push($hb_final,$monthly_hb);
			    	 $school_details = array('hs_name' => $hs_name,
											'hs_mob' => $hs_mob,
											'principal_name' => $principal_name,
											'principal_mob' => $principal_mob,
											'School Name' => $school_name,
											'District' => $dt_name );
			    	if($hb_count_value < 13)
					   {
							$hb_values_data = array(
						    	'Student Unique ID' => $unique_id,
						    	'Name' => $arr_data[$j]['student name'],
						    	'Class' => $arr_data[$j]['class'],
						    	'HB_values'	=> $hb_final,  	  	
						    	'school_details' => $school_details,
						    	'msg_count' => 0,
						    	'ecc_count' => 0
						    );
						}

						 $doc_data['widget_data']['page1']['Student Details']['HB_values'] = $hb_final;
						 $doc_data['widget_data']['page1']['Student Details']['HB_latest'] = $monthly_hb;
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
						

						$new_created = $this->ci->tswreis_schools_common_model->create_hb_values_monthly($doc_data,$doc_properties,$history,$hb_values_data);

						if($new_created)
						 {
						 	 if($hb_count_value <= 6)
							{
								$message = "A Child with severe anemia found, Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$arr_data[$j]['hospital unique id'].", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}else if($hb_count_value <= 8)
							{
								$message = "Severe Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$arr_data[$j]['hospital unique id'].", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($hb_count_value >= 8.1 && $hb_count_value <= 10) {
								$message = "Moderate Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$arr_data[$j]['hospital unique id'].", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}elseif ($hb_count_value >= 10.1 && $hb_count_value <= 12) {
								$message = "Mild Child Oberved Details: Name : ".$arr_data[$j]['student name']."\nU ID : ".$arr_data[$j]['hospital unique id'].", Class:".$arr_data[$j]['class'].",HB : ".$hb_count_value;
									$sms =  $this->ci->bhashsms->send_sms($hs_mob,$message);
							}
						 }

						$count++;
					}

				}
				//===============================================

				unlink($updata['upload_data']['full_path']);

				//redirect('panacea_mgmt/panacea_mgmt_diagnostic');
				return 'redirect_to_hb_fn';

			}else{
				unlink($updata['upload_data']['full_path']);

				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				//$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			//$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}
	function all_activite_date($unique_id_pattern,$today_date){


		$requests = $this->ci->tswreis_schools_common_model->get_requests_daily_count($unique_id_pattern, $today_date);

		$response = $this->ci->tswreis_schools_common_model->get_docs_daily_count($unique_id_pattern, $today_date);

		$requests_info = $this->ci->tswreis_schools_common_model->get_request_info_by_type($unique_id_pattern, $today_date);

		$doctor_docs_info = $this->ci->tswreis_schools_common_model->get_docs_daily_requests_info($unique_id_pattern, $today_date);

		$this->data['request_info'] = json_encode($requests);
		$this->data['response_info'] = json_encode($response);
		$this->data['requests_info'] = json_encode($requests_info);
		$this->data['doctor_docs_info'] = json_encode($doctor_docs_info);
		
		return json_encode($this->data);
			

	}
	function all_activite_attendance($today_date,$school_name){


		$attendance= $this->ci->tswreis_schools_common_model->get_all_absent_data($today_date,$school_name);

		$report_count = $this->ci->tswreis_schools_common_model->get_attendance_report_daily_count($school_name, $today_date);

		//$sanitation_report = $this->ci->tswreis_schools_common_model->get_sanitation_report_data_with_date_version_2($today_date,$school_name);
		
		
		$bmi_info = $this->ci->tswreis_schools_common_model->get_bmi_report_model_count($today_date, $school_name);

		$hb_info = $this->ci->tswreis_schools_common_model->get_hb_report_model_count($today_date, $school_name);
		
		
		
		
		$this->data['attendance'] = json_encode($attendance);
		$this->data['report_count'] = json_encode($report_count);
		$this->data['bmi_info'] = json_encode($bmi_info);
		$this->data['hb_info'] = json_encode($hb_info);
		//$this->data['sanitation_report'] = json_encode($sanitation_report);

		
		return json_encode($this->data);
			

	}
	function all_activite_hb_bmi($current_month,$school_name){
		
		//$sanitation_report = $this->ci->tswreis_schools_common_model->get_sanitation_report_data_with_date_version_2($today_date,$school_name);
		
		
		$bmi_info = $this->ci->tswreis_schools_common_model->get_bmi_report_model_count($current_month, $school_name);
		
		/*echo "<pre>";
		echo print_r($bmi_info_stdnt,true);
		echo "</pre";*/

		$hb_info = $this->ci->tswreis_schools_common_model->get_hb_report_model_count($current_month, $school_name);
		$bmi_info_stdnt = $this->ci->tswreis_schools_common_model->get_bmi_report_model_info($current_month, $school_name);
		$hb_info_stdnt = $this->ci->tswreis_schools_common_model->get_hb_report_model_info($current_month, $school_name);

		$this->data['bmi_info'] = json_encode($bmi_info);

		$this->data['bmi_info_stdnt'] = json_encode($bmi_info_stdnt);
		$this->data['hb_info'] = json_encode($hb_info);
		$this->data['hb_info_stdnt'] =json_encode($hb_info_stdnt);

		//$this->data['sanitation_report'] = json_encode($sanitation_report);
		
		return json_encode($this->data);			

	}

		function import_inventory_med($school)
		{	

			$uploaddir = EXCEL;
			$row_value = 0;
			$arr_count = 0;
			$header_array = array("type of medicine","medicine name","batch number","quantity");
			$config['upload_path'] 		= $uploaddir;
			/*$config['allowed_types'] 	= "xlsx|xls";*/
			$config['allowed_types'] 	= "*";
			$config['max_size']			= '0';
			$config['max_width']  		= '0';
			$config['max_height']  		= '0';
			$config['remove_spaces']  	= TRUE;
			$config['encrypt_name']  	= TRUE;

			$this->ci->load->library('upload', $config);//load libraby and helper for excel.
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
							$arr_data[$each_row][$check_col_array[$header_row]] = $data_value;
							$header_row ++;
						}
					}

					$doc_data = array();
					$form_data = array();
					$count = 0;
					//$hs_insert_count = 0;

					$data_sent = [];
					for($j=2;$j<count($arr_data)+2;$j++){

						$data = array(
							"medcine_inventory_medtype" => $arr_data[$j]['type of medicine'],
							"medcine_inventory_medname" => $arr_data[$j]['medicine name'],
							"medcine_inventory_batchno" => $arr_data[$j]['batch number'],
							"medcine_inventory_quantity" => $arr_data[$j]['quantity']);

						$datas = array (
							"med_type" => $data ['medcine_inventory_medtype'],
							"med_name" => $data ['medcine_inventory_medname'],
							"batch_no" => $data ['medcine_inventory_batchno'],
							"med_qty" => $data ['medcine_inventory_quantity'],
							);
							
						array_push($data_sent, $datas);						
						
					}

					$insert_success = $this->ci->tswreis_schools_common_model->create_medicine_inventory($data_sent, $school);
					unlink($updata['upload_data']['full_path']);
					session_start();
					$_SESSION['updated_message'] = "Successfully imported ".$hs_insert_count." health supervisor document(s).";					
					return "redirect_to_hs_fn";
				}else{
					unlink($updata['upload_data']['full_path']);
					$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
					$this->data['error'] = "excel_column_check_fail";
					return $this->data;

				}
			}
			else
			{
				$this->data['message'] = $this->ci->upload->display_errors();
				$this->data['distslist'] = $this->ci->tswreis_schools_common_model->get_medicine_inventorylist();
				$this->data['error'] = "file_upload_failed";
				return $this->data;
			}
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
			$token = $this->ci->tswreis_schools_common_model->add_news_feed ( $news_data );
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
			
			$token = $this->ci->tswreis_schools_common_model->add_news_feed ( $news_data );
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
	
	public function delete_news_feed ( $nf_id ){
		$news_data = $this->ci->tswreis_schools_common_model->get_news_feed($nf_id);
		if(isset($news_data['file_attachment'])){
			foreach($news_data['file_attachment'] as $file => $file_data){
				unlink($file_data['file_path']);
			}
		}
		$this->ci->tswreis_schools_common_model->delete_news_feed ( $nf_id );
		return true;
	}
	
	public function update_news_feed() {
		
		$user_data = $this->ci->session->userdata ( "customer" );

		$news_data = $this->ci->tswreis_schools_common_model->get_news_feed($_POST['news_id']);
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
			$token = $this->ci->tswreis_schools_common_model->update_news_feed ( $news_data, $news_id );
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

			$token = $this->ci->tswreis_schools_common_model->update_news_feed ( $news_data,$news_id );
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
	

	
}
