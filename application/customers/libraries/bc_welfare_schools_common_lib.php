<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Bc_welfare_schools_common_lib 
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
		$this->ci->load->model('bc_welfare_schools_common_model');
		$this->ci->load->model('bc_welfare_common_model');
		$this->ci->load->library('paas_common_lib');
	
	}

	public function classes($school_code)
	{
	
		$total_rows = $this->ci->bc_welfare_schools_common_model->classescount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['classes'] = $this->ci->bc_welfare_schools_common_model->get_classes($config['per_page'], $page, $school_code);
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
	
		$total_rows = $this->ci->bc_welfare_schools_common_model->sectionscount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['sections'] = $this->ci->bc_welfare_schools_common_model->get_sections($config['per_page'], $page, $school_code);
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
		$docs = $this->ci->bc_welfare_common_model->get_reports_ehr_uid($unique_id,$school_name);
	
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
		
		log_message('debug','this->data=====680=='.print_r($this->data,true));
	
		return $this->data;
	}
	
	public function bc_welfare_reports_students()
	{	
		$total_rows = $this->ci->bc_welfare_common_model->studentscount();
		$this->data['students'] = $this->ci->bc_welfare_common_model->get_all_students();
	
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
	
	function to_dashboard($date = FALSE, $request_duration = "Yearly", $screening_duration = "Yearly")
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
		
		$school_color_code = $this->ci->bc_welfare_schools_common_model->get_school_color_code($school_code);		
		$this->data['school_color_code'] = $school_color_code;
		
		$school_name = $this->ci->bc_welfare_schools_common_model->get_school_details_for_school_code($school_code);
		
		$hs_req_docs  = $this->ci->bc_welfare_schools_common_model->get_hs_req_docs($collection);
		
		if(!empty($hs_req_docs)){
			$this->data['hs_req_docs'] = $hs_req_docs;
		}else{
			$this->data['hs_req_docs'] = "";
		}
		
		
		$absent_report = $this->ci->bc_welfare_schools_common_model->get_all_absent_data($date,$school_name['school_name']);
		
		$submit_report = $this->ci->bc_welfare_schools_common_model->get_attendance_submitted_school_name($date,$school_name['school_name']);

		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);

		}
		
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
			log_message("debug","this========absent_report=====0000000".print_r($this->data['absent_report'],true));
		}
		else if($submit_report)
		{
			$this->data['absent_report'] = 2;
			log_message("debug","this========absent_report=====22222".print_r($this->data['absent_report'],true));
		}
		else{
			$this->data['absent_report'] = 1;

			log_message("debug","this========absent_report=====1111".print_r($this->data['absent_report'],true));

		}
		
		$this->data['screening_report'] = 1; 
		
		$sanitation_infra_report = $this->ci->bc_welfare_schools_common_model->get_sanitation_infrastructure_model($school_name['dt_name'],$school_name['school_name']);
		
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
		$symptoms_report = $this->ci->bc_welfare_schools_common_model->get_all_symptoms($date,$request_duration,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
		
		
	
		$count = 0;
		$request_report = $this->ci->bc_welfare_schools_common_model->get_all_requests($date,$request_duration,$unique_id_pattern);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$count = 0;
		$screening_report = $this->ci->bc_welfare_schools_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1; 
		} 

        $chronic_ids = $this->ci->bc_welfare_schools_common_model->get_all_chronic_unique_ids_model($school_name['school_name']);
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['last_screening_update'] = $this->ci->bc_welfare_schools_common_model->get_last_screening_update(); 

		$this->data ['news_feeds'] = $this->ci->bc_welfare_schools_common_model->get_all_news_feeds($date);
	
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
		
		$school_name = $this->ci->bc_welfare_schools_common_model->get_school_details_for_school_code($school_code);
		
		$absent_report = $this->ci->bc_welfare_schools_common_model->get_all_absent_data($date,$school_name['school_name']);
		
		$submit_report = $this->ci->bc_welfare_schools_common_model->get_attendance_submitted_school_name($date,$school_name['school_name']);
		
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
		$symptoms_report = $this->ci->bc_welfare_schools_common_model->get_all_symptoms($date,$request_duration,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
		
		$count = 0;
		$request_report = $this->ci->bc_welfare_schools_common_model->get_all_requests($date,$request_duration,$unique_id_pattern);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$sanitation_infra_report = $this->ci->bc_welfare_schools_common_model->get_sanitation_infrastructure_model($school_name['dt_name'],$school_name['school_name']);
		
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
		$screening_report = $this->ci->bc_welfare_schools_common_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}*/
        $chronic_ids = $this->ci->bc_welfare_schools_common_model->get_all_chronic_unique_ids_model($school_name['school_name']);
		
		$this->data['chronic_ids'] = json_encode($chronic_ids);
		
		$this->data['screening_report'] = 1;

		$this->data ['news_feeds'] = json_encode($this->ci->bc_welfare_schools_common_model->get_all_news_feeds($date));
		
		$this->data['last_screening_update'] = $this->ci->bc_welfare_schools_common_model->get_last_screening_update();
	
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
		$symptoms_report = $this->ci->bc_welfare_schools_common_model->get_all_symptoms($date,$request_pie_span,$unique_id_pattern);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		log_message("debug","unique_id_pattern============773".print_r($unique_id_pattern,true));
		$request_report = $this->ci->bc_welfare_schools_common_model->get_all_requests($date,$request_pie_span,$unique_id_pattern);
		log_message("debug","request_report===================774".print_r($request_report,true));
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
			log_message("debug","data======request_report===================780".print_r($this->data['request_report'],true));
		}else{
			$this->data['request_report'] = 1;
		}
	
		return json_encode($this->data);
	
	}
	
	function update_screening_pie($date = FALSE,$screening_pie_span  = "Yearly")
	{
	
	$count = 0;
		$screening_report = $this->ci->bc_welfare_schools_common_model->get_all_screenings($date,$screening_pie_span);
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
		$screening_report = $this->ci->bc_welfare_common_model->get_all_sanitation_infrastructure_data();
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
		$screening_report = $this->ci->bc_welfare_common_model->get_all_sanitation_report_data($date,$screening_pie_span);
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
	
	function bc_welfare_imports_diagnostic()
	{
	
		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
	
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
						
					$this->ci->bc_welfare_common_model->create_diagnostic($data);
	
					$count++;
				}
	
	
				//===============================================
	
				unlink($updata['upload_data']['full_path']);
	
				//redirect('bc_welfare_mgmt/bc_welfare_mgmt_diagnostic');
				return "redirect_to_diagnostic_fn";
				
			}else{
				unlink($updata['upload_data']['full_path']);
	
				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";
	
				//$this->_render_page('bc_welfare_admins/bc_welfare_imports_diagnostic', $this->data);
				return $this->data;
	
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('bc_welfare_admins/bc_welfare_imports_diagnostic', $this->data);
			return $this->data;
		}
	}
	
	function bc_welfare_imports_hospital()
	{
	
		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
	
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
	
					$this->ci->bc_welfare_common_model->create_hospital($data);
	
					$count++;
				}
	
	
				//===============================================
	
				unlink($updata['upload_data']['full_path']);
	
				//redirect('bc_welfare_mgmt/bc_welfare_mgmt_hospitals');
				return "redirect_to_hospital_fn";
			}else{
				unlink($updata['upload_data']['full_path']);
	
				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";
	
				//$this->_render_page('bc_welfare_admins/bc_welfare_imports_hospital', $this->data);
				return $this->data;
	
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('bc_welfare_admins/bc_welfare_imports_hospital', $this->data);
			return $this->data;
		}
	}
	
	function bc_welfare_imports_school()
	{	
		$this->data['message'] = FALSE;
		$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
	
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
	
					$insert_success = $this->ci->bc_welfare_common_model->create_school($data);
	
					$count++;
					if($insert_success)
					$school_insert_count++;
				}
	
	
				//===============================================
	
				unlink($updata['upload_data']['full_path']);
				session_start();
				$_SESSION['updated_message'] = "Successfully imported ".$school_insert_count." school document(s).";
	
				//redirect('bc_welfare_mgmt/bc_welfare_mgmt_schools');
				return "redirect_to_school_fn";
			}else{
				unlink($updata['upload_data']['full_path']);
	
				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
				$this->data['error'] = "excel_column_check_fail";
	
				//$this->_render_page('bc_welfare_admins/bc_welfare_imports_school', $this->data);
				return $this->data;
	
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('bc_welfare_admins/bc_welfare_imports_school', $this->data);
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
	
					$insert_success = $this->ci->bc_welfare_common_model->create_health_supervisors($data);
	
					$count++;
					if($insert_success)
						$hs_insert_count++;
				}
	
	
				//===============================================
	
				unlink($updata['upload_data']['full_path']);

				session_start();
				$_SESSION['updated_message'] = "Successfully imported ".$hs_insert_count." health supervisor document(s).";

				//redirect('bc_welfare_mgmt/bc_welfare_mgmt_health_supervisors');
				return "redirect_to_hs_fn";
			}else{
				unlink($updata['upload_data']['full_path']);
	
				$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
				$this->data['error'] = "excel_column_check_fail";
				
				//$this->_render_page('bc_welfare_admins/bc_welfare_imports_health_supervisors', $this->data);
				return $this->data;
	
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('bc_welfare_admins/bc_welfare_imports_health_supervisors', $this->data);
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
									//redirect('bc_welfare_common_lib/bc_welfare_reports_students_redirect');
	
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
	
						//$this->bc_welfare_mgmt_model->create_health_supervisors($data);
	
						//log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiimmmmmmmmmmmmmmmmm.'.print_r($doc_data,true));
	
						$this->ci->bc_welfare_common_model->insert_student_data($doc_data,$history,$doc_properties);
						$insert_count++;
							
						$count++;
					}
	
	
					//===============================================
	
					unlink($updata['upload_data']['full_path']);
					
					session_start();
					$_SESSION['updated_message'] = "Successfully inserted ".$insert_count." student(s) document.";
	
					//redirect('bc_welfare_mgmt/bc_welfare_reports_students');
					return "redirect_to_student_fn";
				}else{
					unlink($updata['upload_data']['full_path']);
	
					$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
					$this->data['error'] = "excel_column_check_fail";
	
					//$this->_render_page('bc_welfare_admins/bc_welfare_imports_students', $this->data);
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
									//redirect('bc_welfare_common_lib/bc_welfare_reports_students_redirect');
	
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
	
						//$this->bc_welfare_mgmt_model->create_health_supervisors($data);
	
						$this->ci->bc_welfare_mgmt_model->insert_student_data($doc_data,$history,$doc_properties);
	
						$count++;
					}
	
	
					//===============================================
	
					unlink($updata['upload_data']['full_path']);
	
					//redirect('bc_welfare_mgmt/bc_welfare_reports_students');
					return "redirect_to_student_fn";
				}else{
					unlink($updata['upload_data']['full_path']);
	
					$this->data['message'] = "Uploaded file doest not contain the following coloumn(s) ". implode(", ", $check);
					
					$this->data['error'] = "excel_column_check_fail";
	
					//$this->_render_page('bc_welfare_admins/bc_welfare_imports_students', $this->data);
					return $this->data;
	
				}
			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_common_model->get_all_district();
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('bc_welfare_admins/bc_welfare_imports_students', $this->data);
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
			log_message('debug','cccccccccccccolllllllllllllllllllllllllllllllllllllllll'.print_r($check_col_array,true));
				
			$check = in_array("hospital unique id",$check_col_array);
				
			//log_message('debug','chkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'.print_r($check,true));
	
	
			if ($check) {
				$arr_data = [];
				$total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
				log_message('debug','rowerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr.'.print_r($total_rows,true));
					
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
									//redirect('bc_welfare_common_lib/bc_welfare_reports_students_redirect');
	
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
					$doc = $this->ci->bc_welfare_common_model->get_students_uid($unique_id);
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
	
						//$this->bc_welfare_mgmt_model->create_health_supervisors($data);
						//log_message('debug','ppppppppppppppppppppppppppppppppppppppppppppppppppppp.'.print_r($doc,true));
						$this->ci->bc_welfare_common_model->update_student_data($doc,$doc_id);
						$update_count++;
					}
						
					$count++;
				}
	
	
				//===============================================
	
				unlink($updata['upload_data']['full_path']);
	
				session_start();
				$_SESSION['updated_message'] = "Successfully updated ".$update_count." student(s) document.";
	
				//redirect('bc_welfare_mgmt/bc_welfare_reports_students');
				return "redirect_to_student_fn";
			}else{
				unlink($updata['upload_data']['full_path']);
	
				$this->data['message'] = "Uploaded file do not have not hospital unique id";
				$this->data['error'] = "excel_column_check_fail";
	
				//$this->_render_page('bc_welfare_admins/bc_welfare_imports_students', $this->data);
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
	
	public function bc_welfare_update_personal_ehr_uid($post)
	{
		ini_set ('memory_limit', '1G');
		$docs = $this->ci->bc_welfare_schools_common_model->get_update_personal_ehr_uid($post);
		$this->data['docs'] = $docs['screening'];
		 log_message("debug","update personal info libbbb====5032".print_r($docs,true));
		log_message("debug","update personal info libbbb====5033".print_r($this->data['docs'],true));
		//$this->data['docs_requests'] = $docs['request'];
		/* 
		$docs = $this->ci->bc_welfare_common_model->get_student_hospital_report($post['uid']);
		$this->data['hospital_reports'] = $docs['get_hospital_report'];
		$this->data['docscount'] = count($this->data['docs']); */
	
		return $this->data;
	}
		function imports_bmi_values($post)
	{

		$dt_name = $post['district_name'];
		$school_name = $post['school_name'];
		

		$uploaddir = EXCEL;
		$row_value = 0;
		$arr_count = 0;
		$header_array = array("hospital unique id",	"student name",	"class","section","date","height in cms",	"weight");
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
					foreach ($cellIterator as $cell)
					{
						$temp_data = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0");
						$data_value = preg_replace('~\x{00a0}~siu',' ',$temp_data);

						if($check_col_array[$header_row] == "hospital unique id")
						{
							// Loggedin user
							/*$loggedinuser = $this->ci->session->userdata("customer");
							$email        = $loggedinuser['email'];
							$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
							
							//preg_match('/^$unique_id/',$data_value);
							if(preg_match("/^$unique_id/i",$data_value) || empty($data_value))
							{
								
							}else{
								$this->data['message'] = "Uploaded file Unique ID value is Empty Or Not Matched, So Please Enter Unique ID ";
							$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
			
							$this->data['error'] = "excel_column_check_fail";
							return $this->data;
							}*/
						}
						if($check_col_array[$header_row] == "height in cms")
						{
							//$regex = '/\.+/i';
							//$matches = preg_match('/\.+/i', $data_value);

							if(preg_match('/\.+/i', $data_value) || empty($data_value))
							{
								$this->data['message'] = "Uploaded file Height values contain Dot(.) or empty value, So Please Enter Height in cms";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
							}
							//str_replace(".", '$data_value/100', subject)
							//$data_value = substr($data_value,0,10);
						}
						if($check_col_array[$header_row] == "weight")
						{
							if(preg_match('/\.+/i', $data_value) || empty($data_value))
							{
								$this->data['message'] = "Uploaded file Weight contain Empty, So Please Enter Student Weight";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
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
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
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
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
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

					$is_exists = $this->ci->bc_welfare_schools_common_model->check_if_doc_exists($unique_id);
					if(!empty($is_exists)){
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
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
						   $bmi    = (double) $bmi;
						   $bmi_final  = round($bmi,1);
						}

						 $new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
					  
					    $monthly_bmi = array(
					  		'height' => (string)$height_in_cms,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => (double)$bmi_final,
					  		'month'  => $ndate
					    );
						 $doc_data['widget_data']['page1']['Student Details']['BMI_values'] = $monthly_bmi;
						 $existing_update = $this->ci->bc_welfare_schools_common_model->bc_welfare_update_bmi_values($ndate,$monthly_bmi,$unique_id);

					}else{
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['school_details']['District'] = $dt_name;
						$doc_data['widget_data']['school_details']['School Name'] = $school_name;

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
						   $bmi    = (double) $bmi;
						   $bmi_   = round($bmi,1);
						}
			  
					  	$new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m');
					  
					    $monthly_bmi = array(
					  		'height' => (string)$height_in_cms,
					  		'weight' => $weight_in_kgs,
					  		'bmi'    => (double)$bmi_,
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

						$this->ci->bc_welfare_schools_common_model->create_bmi_values_monthly($doc_data,$doc_properties,$history);

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
				$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
			
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
								// Loggedin user
								$loggedinuser = $this->ci->session->userdata("customer");
								$email        = $loggedinuser['email'];
								$unique_id = strtoupper(str_replace(".","_",substr($email, 0,strpos($email,'@')-2)));
								
								
								if(preg_match("/^$unique_id/",$data_value) && !empty($data_value))
								{
									
								}else{
									$this->data['message'] = "Uploaded file Unique ID value is Empty Or Not Matched, So Please Enter Unique ID ";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}

						if($check_col_array[$header_row] == "class")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Class value is Empty, So Please Enter Class ";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}

							if($check_col_array[$header_row] == "section")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Section value is Empty, So Please Enter Section ";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}

							if($check_col_array[$header_row] == "hb")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file HB value is Empty, So Please Enter HB ";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
								$this->data['error'] = "excel_column_check_fail";
								return $this->data;
								}
							}
							if($check_col_array[$header_row] == "bloodgroup")
							{
								if(empty($data_value))
								{
									$this->data['message'] = "Uploaded file Blood Group value is Empty, So Please Enter Blood Group ";
								$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
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
					$unique_id = $arr_data[$j]['hospital unique id'];

					$is_exists = $this->ci->bc_welfare_schools_common_model->check_if_doc_exists_hb($unique_id);
					if(!empty($is_exists)){
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup'] = $arr_data[$j]['blood group'];

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
						 $existing_update = $this->ci->bc_welfare_schools_common_model->bc_welfare_update_hb_values($ndate,$monthly_hb,$unique_id);

					}else{
						$doc_data['widget_data']['page1']['Student Details']['Hospital Unique ID'] = $arr_data[$j]['hospital unique id'];
						$doc_data['widget_data']['page1']['Student Details']['Name']['field_ref'] = $arr_data[$j]['student name'];
						$doc_data['widget_data']['page1']['Student Details']['Class']['field_ref'] = $arr_data[$j]['class'];
						$doc_data['widget_data']['page1']['Student Details']['Section']['field_ref'] = $arr_data[$j]['section'];
						$doc_data['widget_data']['page1']['Student Details']['Date'] = $arr_data[$j]['date'];
						$doc_data['widget_data']['page1']['Student Details']['bloodgroup'] = $arr_data[$j]['blood group'];

						$doc_data['widget_data']['school_details']['District'] = $dt_name;
						$doc_data['widget_data']['school_details']['School Name'] = $school_name;

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

						$this->ci->bc_welfare_schools_common_model->create_hb_values_monthly($doc_data,$doc_properties,$history);

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
				//$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
				
				$this->data['error'] = "excel_column_check_fail";

				//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
				return $this->data;

			}
		}
		else
		{
			$this->data['message'] = $this->ci->upload->display_errors();
			//$this->data['distslist'] = $this->ci->bc_welfare_schools_common_model->get_all_district();
			
			$this->data['error'] = "file_upload_failed";

			//$this->_render_page('panacea_admins/panacea_imports_diagnostic', $this->data);
			return $this->data;
		}
	}
	
	public function reports_display_ehr_uid_new_html_static_hs($unique_id,$school_name)
	{
		$docs = $this->ci->bc_welfare_common_model->get_reports_ehr_uid_new_html_static_hs($unique_id,$school_name);
	
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
	function build_sanitation_report_v2($sanitation_report)
	{
	   // Variables
	   /*$campus 	      			= array();
	   $toilets  		  		= array();
	   $kitchen 	  			= array();
	   $water_Supply_Condition  = array();
	   $dormitories 	  		= array();
	   $store 	  				= array();
	   $waste_Management 	 	= array();
	   $water 			 	 	= array();
	   $sanitation_output 		= array();
	   
	   if(isset($sanitation_report) && !empty($sanitation_report))
		{
	      foreach($sanitation_report as $index => $value)
		  {


			$widget_data          = $value['doc_data']['widget_data'];
			$campus_attachments = $value['doc_data']['widget_data']['daily']['Campus']['external_attachments'];
			$toilets_attachments = $value['doc_data']['widget_data']['daily']['Toilets']['external_attachments'];
			$kitchen_attachments = $value['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'];
			$dormitories_attachments = $value['doc_data']['widget_data']['weekly']['Dormitories']['external_attachments'];
			
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
			
			if(count($monthly) > 0 ){
			$water_m['label'] = 'Water Loading Areas';
			$water_m['value'] = $monthly['Water']['Water Loading Areas'];
			array_push($water,$water_m);
			$water_load_times['label'] = 'Warter loading Areas Times';
			$water_load_times['value'] = $monthly['Water']['Warter loading Areas Times'];
			array_push($water,$water_load_times);
			}
			
		}
		
		$sanitation_output['campus']           = $campus;
		$sanitation_output['kitchen']           = $kitchen;
		$sanitation_output['toilets']      	   = $toilets;
		$sanitation_output['water_Supply_Condition']  	   = $water_Supply_Condition;
		$sanitation_output['dormitories'] 		       = $dormitories;
		$sanitation_output['store'] = $store;
		$sanitation_output['waste_management'] = $waste_Management;
		$sanitation_output['water'] = $water;
		$sanitation_output['campus_attachments'] = $campus_attachments;
		$sanitation_output['toilets_attachments'] = $toilets_attachments;
		$sanitation_output['kitchen_attachments'] = $kitchen_attachments;
		$sanitation_output['dormitories_attachments'] = $dormitories_attachments;
	
		return $sanitation_output;
		}*/

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
	
		return $sanitation_output;
		}
	}
	public function bmi_pie_view_lib($current_month, $school_name){
		
		$current_month = substr($current_month,0,-3);
		
		$count = 0;
		$bmi_report = $this->ci->bc_welfare_schools_common_model->get_bmi_report_model($current_month, $school_name);
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
		$bmi_report = $this->ci->bc_welfare_common_model->get_bmi_report_model($current_month, $school_name);
		foreach ($bmi_report as $value){ 
			
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['bmi_report'] = json_encode($bmi_report);
			
		}else{
			$this->data['bmi_report'] = 1;
		
		}
		return $this->data;
	}
	public function hb_pie_view_lib($current_month, $school_name){
		
		$current_month = substr($current_month,0,-3);
		
		$count = 0;
		$hb_report = $this->ci->bc_welfare_schools_common_model->get_hb_report_model($current_month, $school_name);
		//echo print_r($hb_report, true); exit();	
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
		$hb_report = $this->ci->bc_welfare_schools_common_model->get_hb_report_model($current_month, $school_name);
		
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

}
