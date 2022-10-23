<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('memory_limit','1G');


class Schoolhealth_school_lib 
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
		$this->ci->load->model('schoolhealth_school_portal_model');
		$this->ci->load->library('paas_common_lib');
		
	
	}
	
	function to_dashboard($date = FALSE, $request_duration = "Monthly", $screening_duration = "Yearly")
	{
	
		/*$count = 0;
		$absent_report = $this->ci->child_care_school_portal_model->get_all_absent_data($date);
		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
		}else{
			$this->data['absent_report'] = 1;
		}
		
		$count = 0;
		$symptoms_report = $this->ci->child_care_school_portal_model->get_all_symptoms($date,$request_duration);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		$request_report = $this->ci->child_care_school_portal_model->get_all_requests($date,$request_duration);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		
		$count = 0;
		$screening_report = $this->ci->child_care_school_portal_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		$this->data['last_screening_update'] = $this->ci->child_care_school_portal_model->get_last_screening_update();
	
		$this->data['message'] = '';
		
		$this->data['today_date'] = date('Y-m-d');
		
		$this->data['distslist'] = $this->ci->child_care_school_portal_model->get_all_district();*/
		
		$session_data = $this->ci->session->userdata("customer");
		$school_code  = $session_data['school_code'];
		
		$count = 0;
		$screening_report = $this->ci->schoolhealth_school_portal_model->get_all_screenings($date,$screening_duration);
		log_message('debug','$screening_report=====88=='.print_r($screening_report,true));
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		$this->data['classes']  = $this->ci->schoolhealth_school_portal_model->get_all_classes("All",$school_code);
		$this->data['sections'] = $this->ci->schoolhealth_school_portal_model->get_all_sections("All",$school_code);
		
		$school_info = $this->ci->schoolhealth_school_portal_model->get_school_info($school_code);
	
		$school_name = $school_info[0]['school_name'];
		
		$this->data['total_screened_Students_count'] = $this->ci->schoolhealth_school_portal_model->total_screened_Students_count($school_name);
		
		
		$this->data['absent_report']   = 1;
		$this->data['request_report']  = 1;
		$this->data['symptoms_report'] = 1;
		$this->data['last_screening_update'] = "";
		$this->data['today_date'] = date('Y-m-d');
		$this->data['message']    = '';
	
		return $this->data;
	
	}
	
	function to_dashboard_with_date($date = FALSE,$screening_duration = "Yearly")
	{
		/*$count = 0;
		$absent_report = $this->ci->panacea_common_model->get_all_absent_data($date, $dt_name, $school_name);
		foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['absent_report'] = json_encode($absent_report);
		}else{
			$this->data['absent_report'] = 1;
		}
		
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_duration, $dt_name, $school_name);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_duration, $dt_name, $school_name);
		foreach ($request_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['request_report'] = json_encode($request_report);
		}else{
			$this->data['request_report'] = 1;
		}
		*/
		
		$session_data = $this->ci->session->userdata("customer");
		$school_code  = $session_data['school_code'];
		
		$count = 0;
		$screening_report = $this->ci->schoolhealth_school_portal_model->get_all_screenings($date,$screening_duration);
		foreach ($screening_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['screening_report'] = json_encode($screening_report);
		}else{
			$this->data['screening_report'] = 1;
		}
		
		$this->data['classes'] = $this->ci->schoolhealth_school_portal_model->get_all_classes("All",$school_code);
		$this->data['sections'] = $this->ci->schoolhealth_school_portal_model->get_all_sections("All",$school_code);
		
		$this->data['absent_report']   = 1;
		$this->data['request_report']  = 1;
		$this->data['symptoms_report'] = 1;
	
		return json_encode($this->data);
	
	}

    public function classes($school_code)
	{
	
		$total_rows = $this->ci->schoolhealth_school_portal_model->classescount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['classes'] = $this->ci->schoolhealth_school_portal_model->get_classes($config['per_page'], $page, $school_code);
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
	
		$total_rows = $this->ci->schoolhealth_school_portal_model->sectionscount($school_code);
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['sections'] = $this->ci->schoolhealth_school_portal_model->get_sections($config['per_page'], $page,$school_code);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');
	
		$this->data['sectionscount'] = $total_rows;
	
		return $this->data;
	}
	
	public function student_reports()
	{
		$this->data['classlist'] = $this->ci->schoolhealth_school_portal_model->get_all_classes();
	    $total_rows = $this->ci->schoolhealth_school_portal_model->studentscount();
		$this->data['studentscount'] = $total_rows;
		return $this->data;
	}
	
	public function staff_reports()
	{
	    $total_rows = $this->ci->schoolhealth_school_portal_model->staffscount();
		$this->data['staffscount'] = $total_rows;
		return $this->data;
	}
	
	public function staff_management()
	{
	
		$total_rows = $this->ci->schoolhealth_school_portal_model->staffscount();
	
		//---pagination--------//
		$config = $this->ci->paas_common_lib->set_paginate_options($total_rows,10);
	
		//Initialize the pagination class
		$this->ci->pagination->initialize($config);
	
		//control of number page
		$page = ($this->ci->uri->segment(3)) ? $this->ci->uri->segment(3) : 1;
	
		//find all the categories with paginate and save it in array to past to the view
		$this->data['emps'] = $this->ci->schoolhealth_school_portal_model->get_staff($config['per_page'], $page);
		//create paginate´s links
		$this->data['links'] = $this->ci->pagination->create_links();
	
		//number page variable
		$this->data['page'] = $page;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->ci->session->flashdata('message');
	
		$this->data['staffcount'] = $total_rows;
	
		//$this->data = "";
		return $this->data;
	}
	
	public function reports_display_ehr_uid($post,$school_name)
	{
		$docs = $this->ci->schoolhealth_school_portal_model->get_reports_ehr_uid($post['uid'],$school_name);
	    $this->data['docs'] = $docs['screening'];
		//$this->data['docs_requests'] = $docs['request'];
	    $this->data['docscount'] = count($this->data['docs']);
	    return $this->data;
	}
	
	function update_screening_pie($date = FALSE,$screening_pie_span = "Yearly")
	{
	
	    $count = 0;
		$screening_report = $this->ci->schoolhealth_school_portal_model->get_all_screenings($date,$screening_pie_span);
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
	
		$this->data['docscount'] = count($this->data['docs']);
	
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
	
	
	function update_request_pie($date = FALSE,$request_pie_span  = "Monthly")
	{
	
		$count = 0;
		$symptoms_report = $this->ci->panacea_common_model->get_all_symptoms($date,$request_pie_span);
		foreach ($symptoms_report as $value){
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['symptoms_report'] = json_encode($symptoms_report);
		}else{
			$this->data['symptoms_report'] = 1;
		}
	
		$count = 0;
		$request_report = $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span);
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
	
	            $customer = $this->ci->session->userdata("customer");
	            $company_name = $customer['company'];
				
				$company_details = $this->ci->schoolhealth_school_portal_model->fetch_company_details_of_enterprise_admin($company_name);
				
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
						$doc_properties['doc_owner'] = "AMEYA_LIFE";
						$doc_properties['unique_id'] = '';
						$doc_properties['doc_flow'] = "new";
	
						$history['last_stage']['current_stage'] = "Stage Name1";
						$history['last_stage']['approval'] = "true";
						$history['last_stage']['submitted_by'] = "meduserow1#gmail.com";
						$history['last_stage']['time'] = date("Y-m-d H:i:s");
	
						//$this->panacea_mgmt_model->create_health_supervisors($data);
	
						//log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiimmmmmmmmmmmmmmmmm.'.print_r($doc_data,true));
	
						$this->ci->schoolhealth_school_portal_model->insert_student_data($doc_data,$history,$doc_properties);
						$this->ci->schoolhealth_school_portal_model->add_student_into_login_collection($doc_data,$company_details);
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
	
						$this->ci->schoolhealth_school_portal_model->insert_student_data($doc_data,$history,$doc_properties);
	
						$count++;
					}
	
	
					//===============================================
	
					unlink($updata['upload_data']['full_path']);
	
					//redirect('panacea_mgmt/panacea_reports_students');
					//redirect('schoolhealth_school_portal/import_students');
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
			//$this->data['distslist'] = $this->ci->schoolhealth_school_portal_model->get_all_district();
			log_message("debug","message=========1374".print_r($this->data['message']));
			$this->data['error'] = "file_upload_failed";
	
			//$this->_render_page('panacea_admins/panacea_imports_students', $this->data);
			//$this->_render_page('schoolhealth_school_portal_model/import_students', $this->data);
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
					$doc = $this->ci->schoolhealth_school_portal_model->get_students_uid($unique_id);
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
						$this->ci->schoolhealth_school_portal_model->update_student_data($doc,$doc_id);
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
		$objPHPExcel->getProperties()->setCreator("Havik Software Technologies Pvt. Ltd.");
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
		
		$objWorkSheet->getRowDimension(12)->setRowHeight(44);
		//Write cells
		$objWorkSheet->setCellValue('A12', 'Grand Total')
		->getStyle('A12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B12', $grand_total_schools)
		->getStyle('B12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C12', $grand_total_reported)
		->getStyle('C12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D12', $grand_total_not_reported)
		->getStyle('D12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E12', $grand_total_sick)
		->getStyle('E12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F12', $grand_total_r2h)
		->getStyle('F12')->applyFromArray($styleArray);
		
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
				
				$school_data = $this->ci->panacea_common_model->get_absent_school_details($school['name']);
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
	
	public function generate_excel_for_request_pie($date,$request_pie_span, $dt_name = "All", $school_name = "All")
	{
		//load the excel library
		$this->ci->load->library('excel');
		
		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Havik Software Technologies Pvt. Ltd.");
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
		
		$pie_stage1_data =  $this->ci->panacea_common_model->get_all_requests($date,$request_pie_span);
		
		if($dt_name == 'All'){
		$objWorkSheet->getRowDimension(12)->setRowHeight(44);
		//Write cells
		$objWorkSheet->setCellValue('A12', 'Grand Total')
		->getStyle('A12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B12', $pie_stage1_data[0]['value'])
		->getStyle('B12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C12', $pie_stage1_data[1]['value'])
		->getStyle('C12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D12', $pie_stage1_data[2]['value'])
		->getStyle('D12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E12', $pie_stage1_data[3]['value'])
		->getStyle('E12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F12', $pie_stage1_data[4]['value'])
		->getStyle('F12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('G12', $pie_stage1_data[5]['value'])
		->getStyle('G12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('H12', $pie_stage1_data[6]['value'])
		->getStyle('H12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('I12', $pie_stage1_data[7]['value'])
		->getStyle('I12')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('J12', $pie_stage1_data[8]['value'])
		->getStyle('J12')->applyFromArray($styleArray);
		}
		
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
		
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		
		$cell_index = 2;
		foreach ($student_data as $student){
			
			$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
			if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Name']);
				$objWorkSheet->setCellValue('B'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['District']);
				$objWorkSheet->setCellValue('C'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']);
				$objWorkSheet->setCellValue('D'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Class']);
				$objWorkSheet->setCellValue('E'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Section']);
				if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$cell_index ++;
			}
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
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		
		$cell_index = 2;
		foreach ($student_data as $student){
				
			$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
			if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Name']);
				$objWorkSheet->setCellValue('B'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['District']);
				$objWorkSheet->setCellValue('C'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']);
				$objWorkSheet->setCellValue('D'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Class']);
				$objWorkSheet->setCellValue('E'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Section']);
				if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$cell_index ++;
			}
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
		
		$dates = $this->ci->panacea_common_model->get_start_end_date($date,$request_pie_span);
		$student_data = $this->ci->panacea_common_model->get_all_requests_docs( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		
		$cell_index = 2;
		foreach ($student_data as $student){
				
			$doc = $this->ci->panacea_common_model->get_students_uid($student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']);
			//log_message('debug','ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss.'.print_r($student,true));
			if($doc){
				$objWorkSheet->setCellValue('A'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Name']);
				$objWorkSheet->setCellValue('B'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['District']);
				$objWorkSheet->setCellValue('C'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']);
				$objWorkSheet->setCellValue('D'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Class']);
				$objWorkSheet->setCellValue('E'.$cell_index, $doc ["doc_data"] ['widget_data'] ['page2'] ['Personal Information'] ['Section']);
				if(is_array($student['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
					$objWorkSheet->setCellValue('F'.$cell_index, implode(', ',$student['doc_data']['widget_data']['page1']['Problem Info']['Identifier']));
				}
				$objWorkSheet->setCellValue('G'.$cell_index, $student['doc_data']['widget_data']['page2']['Problem Info']['Description']);
				$objWorkSheet->setCellValue('H'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']);
				$objWorkSheet->setCellValue('I'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']);
				$objWorkSheet->setCellValue('J'.$cell_index, $student['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']);
				$objWorkSheet->setCellValue('K'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Request Type']);
				$objWorkSheet->setCellValue('L'.$cell_index, $student['doc_data']['widget_data']['page2']['Review Info']['Status']);
				$cell_index ++;
			}
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
	
	public function generate_excel_for_screening_pie($date, $screening_pie_span, $school_name)
	{
		//load the excel library
		$this->ci->load->library('excel');
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Havik Software Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Selva Ganapathi R");
		$objPHPExcel->getProperties()->setTitle($date."-JGS-Screening Report");
		$objPHPExcel->getProperties()->setSubject($date."-JGS-Screening Report");
		$objPHPExcel->getProperties()->setDescription("Screening report of JGS.");
	
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
		
		$styleArray = array(
				'font'  => array(
						'bold'  => true,
						'name'  => 'Calibri'),
				'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'DCE6F1') ));
		
		$dates = $this->ci->schoolhealth_school_portal_model->get_start_end_date($date, $screening_pie_span);
	
		//Write cells
		$objWorkSheet->setCellValue('A1', 'District')
		->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', "Medchal")
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
		/*$objWorkSheet->setCellValue("K3", "Skin Conditions")
		->getStyle("K3")->applyFromArray($styleArray);*/
			
		$pie_data = $this->ci->schoolhealth_school_portal_model->get_screening_pie_stage5($dates);
		
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
		$requests28 = [ ];
		$requests29 = [ ];
		$requests30 = [ ];
		$requests31 = [ ];
		$requests32 = [ ];
		$requests33 = [ ];
		$requests34 = [ ];
		$requests35 = [ ];
		$requests36 = [ ];
		$requests37 = [ ];
		$requests38 = [ ];
		$requests39 = [ ];
		$requests40 = [ ];
		$requests41 = [ ];
		
			
		foreach ($pie_data as $each_pie){
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"])) {
				$requests1 = array_merge_recursive ( $requests1, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Over Weight"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"]!= null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"])) {
				$requests2 = array_merge_recursive ( $requests2, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Under Weight"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ['Obese'] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"]))
			{
				$requests3 = array_merge_recursive ( $requests3, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Obese"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["General"]!= null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"])) {
				$requests4 = array_merge_recursive ( $requests4, $each_pie ['pie_data'] ['stage5_pie_vales'] ["General"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"]!= null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"])) {
				$requests5 = array_merge_recursive ( $requests5, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Skin"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"])) {
				$requests6 = array_merge_recursive ( $requests6, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Others(Description/Advice)"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] )) {
				$requests7 = array_merge_recursive ( $requests7, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Ortho"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] )) {
				$requests8 = array_merge_recursive ( $requests8, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Postural"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] )) {
				$requests9 = array_merge_recursive ( $requests9, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Defects at Birth"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] )) {
				$requests10 = array_merge_recursive ( $requests10, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Deficencies"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] )) {
				$requests11 = array_merge_recursive ( $requests11, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Childhood Diseases"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] )) {
				$requests12 = array_merge_recursive ( $requests12, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Without Glasses"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] )) {
				$requests13 = array_merge_recursive ( $requests13, $each_pie ['pie_data'] ['stage5_pie_vales'] ["With Glasses"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] )) {
				$requests14 = array_merge_recursive ( $requests14, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Colour Blindness"] );
			}
			
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] )) {
				$requests15 = array_merge_recursive ( $requests15, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Right Ear"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] )) {
				$requests16 = array_merge_recursive ( $requests16, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Left Ear"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] )) {
				$requests17 = array_merge_recursive ( $requests17, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Speech Screening"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] )) {
				$requests18 = array_merge_recursive ( $requests18, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Fair"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] )) {
				$requests19 = array_merge_recursive ( $requests19, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Oral Hygiene - Poor"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] )) {
				$requests20 = array_merge_recursive ( $requests20, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Carious Teeth"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] )) {
				$requests21 = array_merge_recursive ( $requests21, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Flourosis"] );
			}

			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] )) {
				$requests22 = array_merge_recursive ( $requests22, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Orthodontic Treatment"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] )) {
				$requests23 = array_merge_recursive ( $requests23, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Indication for extraction"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] )) {
				$requests24 = array_merge_recursive ( $requests24, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Acne on Face"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] )) {
				$requests25 = array_merge_recursive ( $requests25, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyper Pigmentation"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] )) {
				$requests26 = array_merge_recursive ( $requests26, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Greying Hair"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] )) {
				$requests27 = array_merge_recursive ( $requests27, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Danddruff"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] )) {
				$requests28 = array_merge_recursive ( $requests28, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Facialis"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] )) {
				$requests29 = array_merge_recursive ( $requests29, $each_pie ['pie_data'] ['stage5_pie_vales'] ["White Patches on Face"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] )) {
				$requests30 = array_merge_recursive ( $requests30, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Corporis"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] )) {
				$requests31 = array_merge_recursive ( $requests31, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Allergic Rash"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] )) {
				$requests32 = array_merge_recursive ( $requests32, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Scabies"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] )) {
				$requests33 = array_merge_recursive ( $requests33, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hyperhidrosis"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] )) {
				$requests34 = array_merge_recursive ( $requests34, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Psoriasis"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] )) {
				$requests35 = array_merge_recursive ( $requests35, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Nail Bed Disease"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] )) {
				$requests36 = array_merge_recursive ( $requests36, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hypo Pigmentation"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] )) {
				$requests37 = array_merge_recursive ( $requests37, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Hansens Disease"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] )) {
				$requests38 = array_merge_recursive ( $requests38, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Taenia Cruris"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] )) {
				$requests39 = array_merge_recursive ( $requests39, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Cracked Feet"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] )) {
				$requests40 = array_merge_recursive ( $requests40, $each_pie ['pie_data'] ['stage5_pie_vales'] ["Molluscum"] );
			}
			if ($each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] != null && is_array ( $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] )) {
				$requests41 = array_merge_recursive ( $requests41, $each_pie ['pie_data'] ['stage5_pie_vales'] ["ECCEMA"] );
			}
			
			
		}
			
		$objWorkSheet->setCellValue("B3", count($requests1)+count($requests2)+count($requests3))
		->getStyle("B3")->applyFromArray($styleArray);
		$objWorkSheet->setCellValue("D3", count($requests4)+count($requests5)+count($requests6)+count($requests7)+count($requests8)+count($requests9)+count($requests10)+count($requests11))
		->getStyle("D3")->applyFromArray($styleArray);
		$objWorkSheet->setCellValue("F3", count($requests12)+count($requests13)+count($requests14))
		->getStyle("F3")->applyFromArray($styleArray);
		$objWorkSheet->setCellValue("H3", count($requests15)+count($requests16)+count($requests17))
		->getStyle("H3")->applyFromArray($styleArray);
		$objWorkSheet->setCellValue("J3", count($requests18)+count($requests19)+count($requests20)+count($requests21)+count($requests22)+count($requests23))
		->getStyle("J3")->applyFromArray($styleArray);
		$objWorkSheet->setCellValue("L3", count($requests24)+count($requests25)+count($requests26)+count($requests27)+count($requests28)+count($requests29)+count($requests30)+count($requests31)+count($requests32)+count($requests33)+count($requests34)+count($requests35)+count($requests36)+count($requests37)+count($requests38)+count($requests39)+count($requests40)+count($requests41))
		->getStyle("L3")->applyFromArray($styleArray);
			
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
		
		$objWorkSheet->setCellValue("A7", "Obese");
		$objWorkSheet->setCellValue("B7", count($requests3));
		$sheets_array['Obese']["unique_id"] = $requests3;
		$sheets_array['Obese']["ehr_value"] = [];
		array_push($sheets_array['Obese']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
		
		$objWorkSheet->setCellValue("C5", "General");
		$objWorkSheet->setCellValue("D5", count($requests4));
		$sheets_array['General']["unique_id"] = $requests4;
		$sheets_array['General']["ehr_value"] = [];
		array_push($sheets_array['General']["ehr_value"],"page4^^Doctor Check Up^^Check the box if normal else describe abnormalities");
		
		$objWorkSheet->setCellValue("C6", "Skin");
		$objWorkSheet->setCellValue("D6", count($requests5));
		$sheets_array['Skin']["unique_id"] = $requests5;
		$value = [];
		$sheets_array['Skin']["ehr_value"] = [];
		array_push($sheets_array['Skin']["ehr_value"],"page4^^Doctor Check Up^^Check the box if normal else describe abnormalities");
		
		$objWorkSheet->setCellValue("C7", "Others(Description/Advice)");
		$objWorkSheet->setCellValue("D7", count($requests6));
		$sheets_array['Others(Description&Advice)']["unique_id"] = $requests6;
		$value = [];
		$sheets_array['Others(Description&Advice)']["ehr_value"] = [];
		array_push($sheets_array['Others(Description&Advice)']["ehr_value"],"page4^^Doctor Check Up^^Description");
		array_push($sheets_array['Others(Description&Advice)']["ehr_value"],"page4^^Doctor Check Up^^Advice");
		
		$objWorkSheet->setCellValue("C8", "Ortho");
		$objWorkSheet->setCellValue("D8", count($requests7));
		$sheets_array['Ortho']["unique_id"] = $requests7;
		$value = [];
		$sheets_array['Ortho']["ehr_value"] = [];
		array_push($sheets_array['Ortho']["ehr_value"],"page4^^Doctor Check Up^^Ortho");
		
		$objWorkSheet->setCellValue("C9", "Postural");
		$objWorkSheet->setCellValue("D9", count($requests8));
		$sheets_array['Postural']["unique_id"] = $requests8;
		$value = [];
		$sheets_array['Postural']["ehr_value"] = [];
		array_push($sheets_array['Postural']["ehr_value"],"page4^^Doctor Check Up^^Postural");
		
		$objWorkSheet->setCellValue("C10", "Defects at Birth");
		$objWorkSheet->setCellValue("D10", count($requests9));
		$sheets_array['Defects at Birth']["unique_id"] = $requests9;
		$value = [];
		$sheets_array['Defects at Birth']["ehr_value"] = [];
		array_push($sheets_array['Defects at Birth']["ehr_value"],"page5^^Doctor Check Up^^Defects at Birth");
		
		$objWorkSheet->setCellValue("C11", "Deficencies");
		$objWorkSheet->setCellValue("D11", count($requests10));
		$sheets_array['Deficencies']["unique_id"] = $requests10;
		$value = [];
		$sheets_array['Deficencies']["ehr_value"] = [];
		array_push($sheets_array['Deficencies']["ehr_value"],"page5^^Doctor Check Up^^Deficencies");
		
		$objWorkSheet->setCellValue("C12", "Childhood Diseases");
		$objWorkSheet->setCellValue("D12", count($requests11));
		$sheets_array['Childhood Diseases']["unique_id"] = $requests11;
		$value = [];
		$sheets_array['Childhood Diseases']["ehr_value"] = [];
		array_push($sheets_array['Childhood Diseases']["ehr_value"],"page5^^Doctor Check Up^^Childhood Diseases");
		
		$objWorkSheet->setCellValue("E5", "Without Glasses");
		$objWorkSheet->setCellValue("F5", count($requests12));
		$sheets_array['Without Glasses']["unique_id"] = $requests12;
		$value = [];
		$sheets_array['Without Glasses']["ehr_value"] = [];
		array_push($sheets_array['Without Glasses']["ehr_value"],"page6^^Without Glasses^^Right");
		array_push($sheets_array['Without Glasses']["ehr_value"],"page6^^Without Glasses^^Left");
		
		$objWorkSheet->setCellValue("E6", "With Glasses");
		$objWorkSheet->setCellValue("F6", count($requests13));
		$sheets_array['With Glasses']["unique_id"] = $requests13;
		$value = [];
		$sheets_array['With Glasses']["ehr_value"] = [];
		array_push($sheets_array['With Glasses']["ehr_value"],"page6^^With Glasses^^Right");
		array_push($sheets_array['With Glasses']["ehr_value"],"page6^^With Glasses^^Left");
		
		$objWorkSheet->setCellValue("E7", "Colour Blindness");
		$objWorkSheet->setCellValue("F7", count($requests14));
		$sheets_array['Colour Blindness']["unique_id"] = $requests14;
		$value = [];
		$sheets_array['Colour Blindness']["ehr_value"] = [];
		array_push($sheets_array['Colour Blindness']["ehr_value"],"page7^^Colour Blindness^^Right");
		array_push($sheets_array['Colour Blindness']["ehr_value"],"page7^^Colour Blindness^^Left");
		
		$objWorkSheet->setCellValue("G5", "Right Ear");
		$objWorkSheet->setCellValue("H5", count($requests15));
		$sheets_array['Right Ear']["unique_id"] = $requests15;
		$value = [];
		$sheets_array['Right Ear']["ehr_value"] = [];
		array_push($sheets_array['Right Ear']["ehr_value"],"page8^^ Auditory Screening^^Right");
		
		$objWorkSheet->setCellValue("G6", "Left Ear");
		$objWorkSheet->setCellValue("H6", count($requests16));
		$sheets_array['Left Ear']["unique_id"] = $requests16;
		$value = [];
		$sheets_array['Left Ear']["ehr_value"] = [];
		array_push($sheets_array['Left Ear']["ehr_value"],"page8^^ Auditory Screening^^Left");
		
		$objWorkSheet->setCellValue("G7", "Speech Screening");
		$objWorkSheet->setCellValue("H7", count($requests17));
		$sheets_array['Speech Screening']["unique_id"] = $requests17;
		$value = [];
		$sheets_array['Speech Screening']["ehr_value"] = [];
		array_push($sheets_array['Speech Screening']["ehr_value"],"page8^^ Auditory Screening^^Speech Screening");
		
		$objWorkSheet->setCellValue("I5", "Oral Hygiene - Fair");
		$objWorkSheet->setCellValue("J5", count($requests18));
		$sheets_array['Oral Hygiene - Fair']["unique_id"] = $requests18;
		$value = [];
		$sheets_array['Oral Hygiene - Fair']["ehr_value"] = [];
		array_push($sheets_array['Oral Hygiene - Fair']["ehr_value"],"page9^^Dental Check-up^^Oral Hygiene");
		
		$objWorkSheet->setCellValue("I6", "Oral Hygiene - Poor");
		$objWorkSheet->setCellValue("J6", count($requests19));
		$sheets_array['Oral Hygiene - Poor']["unique_id"] = $requests19;
		$value = [];
		$sheets_array['Oral Hygiene - Poor']["ehr_value"] = [];
		array_push($sheets_array['Oral Hygiene - Poor']["ehr_value"],"page9^^Dental Check-up^^Oral Hygiene");
		
		$objWorkSheet->setCellValue("I7", "Carious Teeth");
		$objWorkSheet->setCellValue("J7", count($requests20));
		$sheets_array['Carious Teeth']["unique_id"] = $requests20;
		$value = [];
		$sheets_array['Carious Teeth']["ehr_value"] = [];
		array_push($sheets_array['Carious Teeth']["ehr_value"],"page9^^Dental Check-up^^Carious Teeth");
		
		$objWorkSheet->setCellValue("I8", "Flourosis");
		$objWorkSheet->setCellValue("J8", count($requests21));
		$sheets_array['Flourosis']["unique_id"] = $requests21;
		$value = [];
		$sheets_array['Flourosis']["ehr_value"] = [];
		array_push($sheets_array['Flourosis']["ehr_value"],"page9^^Dental Check-up^^Flourosis");
		
		$objWorkSheet->setCellValue("I9", "Orthodontic Treatment");
		$objWorkSheet->setCellValue("J9", count($requests22));
		$sheets_array['Orthodontic Treatment']["unique_id"] = $requests22;
		$value = [];
		$sheets_array['Orthodontic Treatment']["ehr_value"] = [];
		array_push($sheets_array['Orthodontic Treatment']["ehr_value"],"page9^^Dental Check-up^^Orthodontic Treatment");
		
		$objWorkSheet->setCellValue("I10", "Indication for extraction");
		$objWorkSheet->setCellValue("J10", count($requests23));
		$sheets_array['Indication for extraction']["unique_id"] = $requests23;
		$value = [];
		$sheets_array['Indication for extraction']["ehr_value"] = [];
		array_push($sheets_array['Indication for extraction']["ehr_value"],"page9^^Dental Check-up^^Indication for extraction");
		
		$objWorkSheet->setCellValue("K5", "Acne on Face");
		$objWorkSheet->setCellValue("L5", count($requests24));
		$sheets_array['Acne on Face']["unique_id"] = $requests24;
		$value = [];
		$sheets_array['Acne on Face']["ehr_value"] = [];
		array_push($sheets_array['Acne on Face']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K6", "Hyper Pigmentation");
		$objWorkSheet->setCellValue("L6", count($requests25));
		$sheets_array['Hyper Pigmentation']["unique_id"] = $requests25;
		$value = [];
		$sheets_array['Hyper Pigmentation']["ehr_value"] = [];
		array_push($sheets_array['Hyper Pigmentation']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K7", "Greying Hair");
		$objWorkSheet->setCellValue("L7", count($requests26));
		$sheets_array['Greying Hair']["unique_id"] = $requests26;
		$value = [];
		$sheets_array['Greying Hair']["ehr_value"] = [];
		array_push($sheets_array['Greying Hair']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K8", "Danddruff");
		$objWorkSheet->setCellValue("L8", count($requests27));
		$sheets_array['Danddruff']["unique_id"] = $requests27;
		$value = [];
		$sheets_array['Danddruff']["ehr_value"] = [];
		array_push($sheets_array['Danddruff']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K9", "Taenia Facialis");
		$objWorkSheet->setCellValue("L9", count($requests28));
		$sheets_array['Taenia Facialis']["unique_id"] = $requests28;
		$value = [];
		$sheets_array['Taenia Facialis']["ehr_value"] = [];
		array_push($sheets_array['Taenia Facialis']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K10", "White Patches on Face");
		$objWorkSheet->setCellValue("L10", count($requests29));
		$sheets_array['White Patches on Face']["unique_id"] = $requests29;
		$value = [];
		$sheets_array['White Patches on Face']["ehr_value"] = [];
		array_push($sheets_array['White Patches on Face']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K11", "Taenia Corporis");
		$objWorkSheet->setCellValue("L11", count($requests30));
		$sheets_array['Taenia Corporis']["unique_id"] = $requests30;
		$value = [];
		$sheets_array['Taenia Corporis']["ehr_value"] = [];
		array_push($sheets_array['Taenia Corporis']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K12", "Allergic Rash");
		$objWorkSheet->setCellValue("L12", count($requests31));
		$sheets_array['Allergic Rash']["unique_id"] = $requests31;
		$value = [];
		$sheets_array['Allergic Rash']["ehr_value"] = [];
		array_push($sheets_array['Allergic Rash']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K13", "Scabies");
		$objWorkSheet->setCellValue("L13", count($requests32));
		$sheets_array['Scabies']["unique_id"] = $requests32;
		$value = [];
		$sheets_array['Scabies']["ehr_value"] = [];
		array_push($sheets_array['Scabies']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K14", "Hyperhidrosis");
		$objWorkSheet->setCellValue("L14", count($requests33));
		$sheets_array['Hyperhidrosis']["unique_id"] = $requests33;
		$value = [];
		$sheets_array['Hyperhidrosis']["ehr_value"] = [];
		array_push($sheets_array['Hyperhidrosis']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K15", "Psoriasis");
		$objWorkSheet->setCellValue("L15", count($requests34));
		$sheets_array['Psoriasis']["unique_id"] = $requests34;
		$value = [];
		$sheets_array['Psoriasis']["ehr_value"] = [];
		array_push($sheets_array['Psoriasis']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K16", "Nail Bed Disease");
		$objWorkSheet->setCellValue("L16", count($requests35));
		$sheets_array['Nail Bed Disease']["unique_id"] = $requests35;
		$value = [];
		$sheets_array['Nail Bed Disease']["ehr_value"] = [];
		array_push($sheets_array['Nail Bed Disease']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K17", "Hypo Pigmentation");
		$objWorkSheet->setCellValue("L17", count($requests36));
		$sheets_array['Hypo Pigmentation']["unique_id"] = $requests36;
		$value = [];
		$sheets_array['Hypo Pigmentation']["ehr_value"] = [];
		array_push($sheets_array['Hypo Pigmentation']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K18", "Hansens Disease");
		$objWorkSheet->setCellValue("L18", count($requests37));
		$sheets_array['Hansens Disease']["unique_id"] = $requests37;
		$value = [];
		$sheets_array['Hansens Disease']["ehr_value"] = [];
		array_push($sheets_array['Hansens Disease']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K19", "Taenia Cruris");
		$objWorkSheet->setCellValue("L19", count($requests38));
		$sheets_array['Taenia Cruris']["unique_id"] = $requests38;
		$value = [];
		$sheets_array['Taenia Cruris']["ehr_value"] = [];
		array_push($sheets_array['Taenia Cruris']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K20", "Cracked Feet");
		$objWorkSheet->setCellValue("L20", count($requests39));
		$sheets_array['Cracked Feet']["unique_id"] = $requests39;
		$value = [];
		$sheets_array['Cracked Feet']["ehr_value"] = [];
		array_push($sheets_array['Cracked Feet']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K21", "Molluscum");
		$objWorkSheet->setCellValue("L21", count($requests40));
		$sheets_array['Molluscum']["unique_id"] = $requests40;
		$value = [];
		$sheets_array['Molluscum']["ehr_value"] = [];
		array_push($sheets_array['Molluscum']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
		
		$objWorkSheet->setCellValue("K22", "ECCEMA");
		$objWorkSheet->setCellValue("L22", count($requests41));
		$sheets_array['ECCEMA']["unique_id"] = $requests41;
		$value = [];
		$sheets_array['ECCEMA']["ehr_value"] = [];
		array_push($sheets_array['ECCEMA']["ehr_value"],"page5^^Doctor Check Up^^Skin conditions");
			
		$styleArray = array(
				'font'  => array(
						'bold'  => true,
						'name'  => 'Calibri'),
				'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'DCE6F1') ));
		
		$sheet_index = 1;
		$cell_collection = ['G','H','I','J','K','L','M','N'];
		foreach ($sheets_array as $sheet_key => $each_sheet){
			
			if(count($each_sheet["unique_id"] ) >0){
				// Add new sheet
				$objWorkSheet = $objPHPExcel->createSheet($sheet_index); //Setting index when creating
				// Rename sheet
				$objWorkSheet->setTitle($sheet_key);
		
				//Write cells
				$objWorkSheet->setCellValue('A1', $sheet_key)
				->getStyle('A1')->applyFromArray($styleArray);
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
		
				$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
				$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
				$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
				$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
				$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
				$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
				
				$cell_value_index = 0;
				foreach($each_sheet["ehr_value"] as $value){
					$val_arr = explode("^^",$value);
					
					$objWorkSheet->setCellValue($cell_collection[$cell_value_index].'2', $val_arr[2])
					->getStyle($cell_collection[$cell_value_index].'2')->applyFromArray($styleArray);
					$objWorkSheet->getColumnDimension($cell_collection[$cell_value_index])->setAutoSize(true);
					
					$cell_value_index ++;
				}
		
				$student_details = $this->ci->schoolhealth_school_portal_model->get_drilling_screenings_students_docs($each_sheet["unique_id"]);
				$cell_ind = 3;
				foreach ($student_details as $student){
					$objWorkSheet->setCellValue('A'.$cell_ind, $student['doc_data']['widget_data']["page1"]['Personal Information']['Hospital Unique ID'] );
					$objWorkSheet->setCellValue('B'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['AD No'] );
					$objWorkSheet->setCellValue('C'.$cell_ind, $student['doc_data']['widget_data']["page1"]['Personal Information']['Name'] );
					$objWorkSheet->setCellValue('D'.$cell_ind, (isset($student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])) ? $student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num']  :"Mobile Number not available" );
					$objWorkSheet->setCellValue('E'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['Class']  );
					$objWorkSheet->setCellValue('F'.$cell_ind, $student['doc_data']['widget_data']["page2"]['Personal Information']['Section'] );
					$cell_value_index = 0;
					foreach($each_sheet["ehr_value"] as $value){
						$val_arr = explode("^^",$value);
						if(isset($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]) && is_array($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]) && !empty($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]])){
							$objWorkSheet->setCellValue($cell_collection[$cell_value_index].$cell_ind, implode(", ", $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]));
						}else{
							if(isset($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]) && !empty ($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]))
							{
							$objWorkSheet->setCellValue($cell_collection[$cell_value_index].$cell_ind, $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]);
							}
							else{
								$objWorkSheet->setCellValue($cell_collection[$cell_value_index].$cell_ind,isset( $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]) && !empty ($student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]]) ? $student['doc_data']['widget_data'][$val_arr[0]][$val_arr[1]][$val_arr[2]] : "");
							}
						}
						$cell_value_index ++;
					}
					
					$cell_ind ++;
						
				}
		
			}
		}
			
		$file_name = $date."-".$school_name.".xlsx";
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			
		$file_save = BASEDIR.TENANT.'/'.$file_name;
		$file_name = URLCustomer.$file_name;
		$objWriter->save($file_save);
		
		return $file_name;
		
	}
	
	public function generate_health_summary_report($unique_id_list)
	{
	
	 $summary_report = "";
	 
	 $summary_report.= "<html><body>";
	
	 foreach($unique_id_list as $unique_id)
	 {
	   $student_doc = $this->ci->schoolhealth_school_portal_model->get_students_uid($unique_id);
	   
	   // Page wise data
	   $page1 = $student_doc['doc_data']['widget_data']['page1'];
	   $page2 = $student_doc['doc_data']['widget_data']['page2'];
	   $page3 = $student_doc['doc_data']['widget_data']['page3'];
	   $page4 = $student_doc['doc_data']['widget_data']['page4'];
	   $page5 = $student_doc['doc_data']['widget_data']['page5'];
	   $page6 = $student_doc['doc_data']['widget_data']['page6'];
	   $page7 = $student_doc['doc_data']['widget_data']['page7'];
	   $page8 = $student_doc['doc_data']['widget_data']['page8'];
	   $page9 = $student_doc['doc_data']['widget_data']['page9'];
	   
	   
	   $summary_report.= "<div class='page_break' id='".$page1['Personal Information']['Hospital Unique ID']."'>";
	   
	   // School Information
	   $summary_report.="<div style='text-align:center;font-weight:bold;font-size:100%;' class='school_information'><label class='school_name'>".$page2['Personal Information']['School Name']."</label></div><hr>";
	   
	   // Personal Information
	   $summary_report.="<div class='personal_information'><label class='title'>Personal Information</label><table><tr><td>Name : ".$page1['Personal Information']['Name']."</td><td>Class : ".$page2['Personal Information']['Class']."</td></tr><tr><td>Health Unique ID : ".$page1['Personal Information']['Hospital Unique ID']."</td><td>Section : ".$page2['Personal Information']['Section']."</td></tr></table></div><br>";
	   
	   //Physical Information
	   if(isset($page3) && !empty($page3))
	   {
		   $bmi = (int) $page3['Physical Exam']['BMI%'];
		   $summary_report.="<div class='physical_information'><label class='title'>Physical Details</label><br><label>Height ( in cms ) : ".$page3['Physical Exam']['Height cms']." </label><br><label>Weight ( in kgs ) : ".$page3['Physical Exam']['Weight kgs']." </label><br><label>BMI : ".$bmi."</label><br>";
		   
		   /*if($bmi < 18)
		   {
			$summary_report.="<label class='malnourished'>Category : Malnourished</label></div>";
		   }
		   else if(($bmi >= 18) && ($bmi <=24))
		   {
			$summary_report.="<label class='underweight'>Category : Underweight</label></div>";
		   }
		   else if($bmi == 25)
		   {
			$summary_report.="<label class='normal'>Category : Normal</label></div>";
		   }
		   else if(($bmi >= 26) && ($bmi <=30))
		   {
			$summary_report.="<label class='overweight'>Category : Overweight</label></div>";
		   }
		   else if($bmi > 30)
		   {
			$summary_report.="<label class='obese'>Category : Obese</label></div>";
		   }*/
		   
		   /*if($bmi < 15)
		   {
			$summary_report.="<label class='very_severely_underweight'>Category : Very severely underweight</label></div>";
		   }
		   else if(($bmi >= 15) && ($bmi <= 16))
		   {
			$summary_report.="<label class='severely_underweight'>Category : Severely underweight</label></div>";
		   }
		   else if(($bmi > 16) && ($bmi <= 18.5))
		   {
			$summary_report.="<label class='underweight'>Category : Underweight </label></div>";
		   }
		   else if(($bmi > 18.5) && ($bmi <= 25))
		   {
			$summary_report.="<label class='normal'>Category : Normal</label></div>";
		   }
		   else if(($bmi > 25) && ($bmi <=30))
		   {
			$summary_report.="<label class='overweight'>Category : Overweight</label></div>";
		   }
		   else if(($bmi > 30) && ($bmi <=35))
		   {
			$summary_report.="<label class='obese_class1'>Category : Obese Class I (Moderately obese) </label></div>";
		   }
		   else if(($bmi > 35) && ($bmi <=40))
		   {
			$summary_report.="<label class='obese_class2'>Category : Obese Class II (Severely obese) </label></div>";
		   }
		   else if($bmi > 40)
		   {
			$summary_report.="<label class='obese_class3'>Category : Obese Class III (Very severely obese) </label></div>";
		   }*/
		   
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
	   
	    //Doctor Signature
	   $summary_report.="<div class='doctor_signature'><img src='https://mednote.in/PaaS/bootstrap/dist/img/prasad_rao_sign.png' alt='Dr Signature' height='50' width='100' style='float:right;display:block'/>
	   <label style='float:right;clear:both'>Dr.N.S D Prasadarao </label></div>"; 
	   
	   //$summary_report.="<div class='doctor_signature'><label style='float:right;clear:both;margin:5px;'>Dr.N.S D Prasadarao, MBBS,DGO,FAIMS,FCGP </label></div>";
	   
	   //Note
	   $summary_report.="<div class='note'><label>Note:-</label><label>1.The above report is autogenerated by the system. For detailed information,look into EHR.</label><br><label> 2.Please use the above health unique id for login into the EHR App and also use 12345678 as default login password.You can change the password as per your convenience once you logged in.</label></div>";
	   
	   //Note
	   $summary_report.="<br><br><br><hr><div class='tlstec'><label>Digital report generated by MedNote healthcare platform by Havik Software Technologies Pvt. Ltd. <a href='http://www.tlstec.com'>(TLSTEC) </a> for <a href='http://www.ameyalife.com/'> Ameya Life </a></label></div>";
	   
	   $summary_report.="</div>";
	   
	 }
	 
	 $summary_report.= "</body></html>";
	 
	 return $summary_report;
	
	}
	
	/**
	 * Helper: BMI PIE REPORT
	 
	 * @author bhanu 
	 */
	
	public function bmi_pie_view_lib( $school_name){
		
		
		
		$count = 0;
		$bmi_report = $this->ci->schoolhealth_school_portal_model->get_bmi_report_model($school_name);
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
	
	public function bmi_pie_view_lib_month_wise( $school_name){
		$current_month = substr($current_month,0,-3);
		$count = 0;
		$bmi_report = $this->ci->schoolhealth_school_portal_model->get_bmi_report_model($school_name);
		foreach ($bmi_report as $value){ 
			
			$count = $count + intval($value['value']);
		}
		if($count > 0){
			$this->data['bmi_report'] = json_encode($bmi_report);
			log_message('debug','bmi_pie_view_lib_month_wise====4572'.print_r($this->data['bmi_report'], true));
		}else{
			$this->data['bmi_report'] = 1;
		
		}
		return $this->data;
	}
	
	public function generate_bmi_report_to_excel_lib($school_name)
	{
		$this->ci->load->library('excel');
		
		//create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Havik soft Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Bhanu Prakash");
		$objPHPExcel->getProperties()->setTitle($school_name."-BMI Report.xlsx");
		$objPHPExcel->getProperties()->setSubject($school_name."-BMI Report.xlsx");
		$objPHPExcel->getProperties()->setDescription("BMI report of".$school_name);
		
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
		$objWorkSheet->setCellValue('A1', 'Unique ID')
									->getStyle('A1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('B1', 'Name')
									->getStyle('B1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('C1', 'School Name')
									->getStyle('C1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('D1', 'Height cms')
									->getStyle('D1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('E1', 'Weight kgs')
									->getStyle('E1')->applyFromArray($styleArray);
		$objWorkSheet->setCellValue('F1', 'BMI')
									->getStyle('F1')->applyFromArray($styleArray);
		
		
		$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("C")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("D")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("E")->setAutoSize(true);
		$objWorkSheet->getColumnDimension("F")->setAutoSize(true);
		
		
		$data = $this->ci->schoolhealth_school_portal_model->export_bmi_reports_monthly_to_excel($school_name);
		
		
		$i = 2;
		

		foreach($data as $doc_data)
		{	
			
			
			$objWorkSheet->setCellValue('A'.$i,$doc_data['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']);
			$objWorkSheet->setCellValue('B'.$i,$doc_data['doc_data']['widget_data']['page1']['Personal Information']['Name']);
			$objWorkSheet->setCellValue('C'.$i,$doc_data['doc_data']['widget_data']['page2']['Personal Information']['School Name']);

			if(isset($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']) && !empty($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']))
			{
					$objWorkSheet->setCellValue('D'.$i,$doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']);
					
			}
			else{
					$objWorkSheet->setCellValue('D'.$i, "");
				}
			if(isset($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']) && !empty($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']))
			{
				$objWorkSheet->setCellValue('E'.$i,$doc_data['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']);
				
			}
			else{
				$objWorkSheet->setCellValue('E'.$i, "");
			}


			if(isset($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']) && !empty($doc_data['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']))
			{
				$objWorkSheet->setCellValue('F'.$i,$doc_data['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']);
			}
			else{
				$objWorkSheet->setCellValue('F'.$i, "");
			}
			
			$i++;
			
		}
		
		
		//$objWorkSheet->getColumnDimension("A")->setAutoSize(true);
		//$objWorkSheet->getColumnDimension("B")->setAutoSize(true);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
		$file_save = BASEDIR.TENANT.'/'.$school_name."-BMI Report.xlsx";
		
		$file_name = URLCustomer.$school_name."-BMI Report.xlsx";
		$objWriter->save($file_save);
		
		return $file_name;
		
	}

	
	
}