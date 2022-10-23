<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tswreis_schools_common_model extends CI_Model {
	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 */
	protected $errors;
	
	/**
	 * error start delimiter
	 *
	 * @var string
	 */
	protected $error_start_delimiter;
	
	/**
	 * error end delimiter
	 *
	 * @var string
	 */
	protected $error_end_delimiter;
	function __construct() {
		parent::__construct ();
		
		$this->load->config ( 'ion_auth', TRUE );
		$this->load->config ( 'mongodb', TRUE );
		
		// Initialize MongoDB collection names
		$this->collections = $this->config->item ( 'collections', 'ion_auth' );
		$this->_configvalue = $this->config->item ( 'default' );
		$this->common_db = $this->config->item ( 'default' );
		
		$this->store_salt = $this->config->item ( 'store_salt', 'ion_auth' );
		$this->salt_length = $this->config->item ( 'salt_length', 'ion_auth' );
		
		// Initialize hash method directives (Bcrypt)
		$this->hash_method = $this->config->item ( 'hash_method', 'ion_auth' );
		
		$school_admin = $this->session->userdata('customer');
		$email     	  = $school_admin['email'];
		$email        = str_replace("@","#",$email);
		$this->screening_app_col = 'healthcare2016226112942701';
		$this->screening_app_col_2020_21 = 'tswreis_screening_report_col_2020-2021';
		$this->screening_app_col_2021_22 = 'tswreis_screening_report_col_2021-2022';
		$this->screening_staff_app_col = 'healthcare2016226112942701_staff_panacea_emp';
		$this->screening_app_col_screening = $email."_pie_analytics";
		
		$this->absent_app_col = "healthcare201651317373988";
		$this->request_app_col = "healthcare2016531124515424";
		$this->sanitation_infra_app_col  = "healthcare20161114161842748";
		$this->sanitation_report_app_col = "healthcare2016111212310531";
		$this->sanitation_report_app_col_v2 = "healthcare2016111212310531_version_2";
		$this->bmi_app_col = "healthcare2017617145744625";
		//$this->bmi_app_col_xl_import = "healthcare2017617145744625_XL_Import";
		//$this->hb_app_col = "himglobin_report_col_XL_Import";
		$this->hb_app_col = "himglobin_report_col";
		$this->notes_col = "panacea_ehr_notes";
		$this->today_date = date ( 'Y-m-d' );
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch school details using the school code
	 *
	 * @param  int $school_code  School code
	 *
	 * @return array
	 */
	 
	public function get_school_info($school_code)
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$res = $this->mongo_db->where(array('school_code' => $school_code))->get($this->collections['panacea_schools']);
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		if($res)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	public function get_searched_student_sick_requests_model($search_data,$school_name)
	{
		$data = trim($search_data);

		if(preg_match("/_/", $data)){

			$query = $this->mongo_db->where('doc_data.widget_data.page1.Student Info.School Name.field_ref',$school_name)->whereLike('doc_data.widget_data.page1.Student Info.Unique ID', $data)->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->orderBy(array('history.0.time' => -1))->get("healthcare2016531124515424_static_html");

		}else{

		$query = $this->mongo_db->where('doc_data.widget_data.page1.Student Info.School Name.field_ref',$school_name)->whereLike('doc_data.widget_data.page1.Student Info.Name.field_ref', $data)->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->orderBy(array('history.0.time' => -1))->get("healthcare2016531124515424_static_html");
			
		}

		return $query;
	}
	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate analytics 
	 *
	 * @param  mixed $date 				 Result array or object
	 * @param  mixed $screening_duration Result array or object
	 * @param  mixed $school_name 		 Result array or object
	 *
	 * @return void
	 */
	 
	public function update_screening_collection($date, $screening_duration, $school_name) 
	{
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
			}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration ); 
		
		for($init_date = $dates ['today_date']; $init_date >= $dates ['end_date'];) 
		{
			$query = $this->mongo_db->where ( array (
					'pie_data.date' => $init_date 
			) )->count ( $this->screening_app_col_screening );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $init_date . "-1 day" ) );
			
			$temp_dates ['today_date'] = $init_date;
			$temp_dates ['end_date']   = $end_date;
			
			if ($query == 0) {
				
				$pie_data = array (
						"pie_data" => array (
								'date' => $init_date 
						) 
				);
				
				$requests = $this->screening_pie_data_for_stage3($temp_dates,$school_name);
				$pie_data ['pie_data'] ['stage3_pie_values'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage2($requests);
				$pie_data ['pie_data'] ['stage2_pie_values'] = $requests;
				
				$requests = $this->screening_pie_data_for_stage1($requests);
				$pie_data ['pie_data'] ['stage1_pie_values'] = $requests;
				
				$this->mongo_db->insert ( $this->screening_app_col_screening, $pie_data );
			}
			$init_date = $end_date;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Get start date and end date
	 *
	 *
	 * @param  string $today_date  		 Date
	 * @param  string $request_duration  Duration
	 *
	 * @return array
	 */
	 
	public function get_start_end_date($today_date, $request_duration)
	{
		if ($request_duration == "Daily") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date   = date ( "Y-m-d H:i:s", strtotime ( $today_date . "0 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date']   = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Weekly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Bi Weekly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-13 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Monthly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date']   = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Bi Monthly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-2 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Quarterly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-3 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Half Yearly") 
		{
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-6 month" ) );
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} 
		else if ($request_duration == "Yearly") 
		{
			$date = new DateTime ( $today_date );
			//$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-4 years" ) );
			//$end_date = date ( "Y-m-d H:i:s", strtotime ( $end_date . "1 day" ) );
			//$today_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "1 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate screening pie analytics
	 *
	 *
	 * @param  array  $dates  		Dates ( Start date & end date )
	 * @param  string $school_name  Name of the school ( logged in school )
	 *
	 * @return array
	 */
	 
	private function screening_pie_data_for_stage3($dates,$school_name) 
	{
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '2G' );
		
		$request = array();
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		$add_count = $count+100;
		if ($count < $add_count) 
		{
			$per_page = $count;
			$loop = 2; 
		} 
		else 
		{
			$per_page = 5000;
			$loop 	  = $count / $per_page;
		}
		
		$merged_array = array();
		
		$overweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				    )
				
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push($merged_array,$overweight_array);
		array_push($merged_array,$schoolwise_check);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) 
		{
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array(
							'$and'	=> $merged_array
							)
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) 
			{
				foreach ( $doc ['history'] as $date ) 
				{
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) 
					{
						array_push ( $temp_result, $doc );
						
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Over Weight"]  = $search_result;
		
		// ==========================================================================================
		
		$merged_array = array();
		
		$underweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push($merged_array,$underweight_array);
		array_push($merged_array,$schoolwise_check);
		
		
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array(
							'$and'	 => $merged_array 
							)
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Under Weight"]  = $search_result;
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["General"]  = $search_result;
		
		// ========================================================================================
		
		
		$merged_array = array ();
		
		$skin_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
			
		array_push ( $merged_array, $skin_array );
		array_push ( $merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array(
							'$and'	 =>$merged_array 
							)
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Skin"]  = $search_result;
		
		// ========================================================================================
	
		$and_merged_array = array ();
		
		$description_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ''
				)
		);
		$description_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Description" => array (
						'$ne' => ' '
				)
		);
		
		$advice_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ''
				)
		);
		$advice_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Advice" => array (
						'$ne' => ' '
				)
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true
				)
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $description_str_empty );
		array_push ( $and_merged_array, $description_str_space );
		array_push ( $and_merged_array, $advice_str_empty );
		array_push ( $and_merged_array, $advice_str_space );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [
			array (
					'$match' => array (
							'$and' => $and_merged_array
					)
			),
			array (
					'$project' => array (
							"doc_data.widget_data" => true,
							"history" => true
					)
			),
			array (
					'$limit' => $offset
			),
			array (
					'$skip' => $offset - $per_page
			)
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
		
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
						
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
				
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Others(Description/Advice)"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Ortho"] = $search_result;
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Postural"] = $search_result;
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Defects at Birth"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
				
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Deficencies"] = $search_result;
		
		// ==========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Childhood Diseases"] = $search_result;
		
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
				
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Without Glasses"] = $search_result;
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["With Glasses"] = $search_result;
		
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Colour Blindness"] = $search_result;
		
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$eye_lids = array (
				"doc_data.widget_data.page7.Colour Blindness.Eye Lids" => array (
						'$nin' => array (
								"Normal",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $eye_lids );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Eye Lids"] = $search_result;
		//============================================
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$conjunctiva = array (
				"doc_data.widget_data.page7.Colour Blindness.Conjunctiva" => array (
						'$nin' => array (
								"Normal",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $conjunctiva );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Conjunctiva"] = $search_result;
		//============================================
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$cornea = array (
				"doc_data.widget_data.page7.Colour Blindness.Cornea" => array (
						'$nin' => array (
								"Normal",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $cornea );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Cornea"] = $search_result;
		//============================================
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$pupil = array (
				"doc_data.widget_data.page7.Colour Blindness.Pupil" => array (
						'$nin' => array (
								"Normal",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $pupil );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Pupil"] = $search_result;
		//============================================
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$wearing_spectacles = array (
				"doc_data.widget_data.page7.Colour Blindness.Wearing Spectacles" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $wearing_spectacles );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Wearing Spectacles"] = $search_result;
		//============================================
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$subjective_refraction = array (
				"doc_data.widget_data.page7.Colour Blindness.Subjective Refraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);		
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $subjective_refraction );
		array_push ( $and_merged_array, $page7_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Subjective Refraction"] = $search_result;
		//============================================
		
		$and_merged_array = array ();
		$or_merged_array  = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $audi_right );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Right Ear"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array  = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Left Ear"] = $search_result;
		
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array  = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Speech Screening"] = $search_result;
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Oral Hygiene - Fair"] = $search_result;
		
		// ==========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Oral Hygiene - Poor"] = $search_result;
		
		// ==========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array  = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Carious Teeth"] = $search_result;
		
		// ==========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Flourosis"] = $search_result;
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Orthodontic Treatment"] = $search_result;
		
		// ==========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Indication for extraction"] = $search_result;
		
		return $request;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate screening pie analytics - stage 2
	 *
	 *
	 * @param  array  $requests  Request data
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage2($requests) {
		$request_stage2 = [ ];
		
		//log_message('debug','screening_pie_data_for_stage2_new=====453=='.print_r($requests,true));
		
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests["Over Weight"] as $doc ) 
		{
		  $total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Under Weight"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["General"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Skin"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Skin";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// ===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Others(Description/Advice)"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Others(Description/Advice)";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Ortho"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Ortho";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Postural"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Postural";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Defects at Birth"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Defects at Birth";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Deficencies"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Childhood Diseases"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["General Abnormalities"] ["label"] = "Childhood Diseases";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Without Glasses"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["With Glasses"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Colour Blindness"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Colour Blindness";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Eye Lids"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Eye Lids";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Conjunctiva"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Conjunctiva";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Cornea"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Cornea";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Pupil"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Pupil";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Wearing Spectacles"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Wearing Spectacles";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Subjective Refraction"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Eye Abnormalities"] ["label"] = "Subjective Refraction";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Right Ear"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Right Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Left Ear"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Left Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Speech Screening";
		
		$total_count = 0;
		foreach ( $requests ["Speech Screening"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Auditory Abnormalities"] ["label"] = "Speech Screening";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Fair"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Fair";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Oral Hygiene - Poor"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Poor";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Carious Teeth"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Carious Teeth";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Flourosis";
		
		$total_count = 0;
		foreach ( $requests ["Flourosis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}

		$stage_array ["Dental Abnormalities"] ["label"] = "Flourosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Orthodontic Treatment"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Orthodontic Treatment";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Indication for extraction"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Dental Abnormalities"] ["label"] = "Indication for extraction";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		return $request_stage2;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Generate screening pie analytics - stage 1
	 *
	 *
	 * @param  array  $requests  Request data
	 *
	 * @return array
	 */
	 
	public function screening_pie_data_for_stage1($requests) 
	{
		$request_stage1 = [ ];
		
		//log_message('debug','screening_pie_data_for_stage1_new====2122=='.print_r($requests,true));
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests[0]["Physical Abnormalities"]['value'] + $requests[1]["Physical Abnormalities"]['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [2] ["General Abnormalities"] ['value'] + $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [10] ["Eye Abnormalities"] ['value'] + $requests [11] ["Eye Abnormalities"] ['value'] + $requests [12] ["Eye Abnormalities"] ['value'] + $requests [13] ["Eye Abnormalities"] ['value'] + $requests [14] ["Eye Abnormalities"] ['value'] + $requests [15] ["Eye Abnormalities"] ['value'] + $requests [16] ["Eye Abnormalities"] ['value'] + $requests [17] ["Eye Abnormalities"] ['value'] + $requests [18] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [19] ["Auditory Abnormalities"] ['value'] + $requests [20] ["Auditory Abnormalities"] ['value'] + $requests [21] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [22] ["Dental Abnormalities"] ['value'] + $requests [23] ["Dental Abnormalities"] ['value'] + $requests [24] ["Dental Abnormalities"] ['value'] + $requests [25] ["Dental Abnormalities"] ['value'] + $requests [26] ["Dental Abnormalities"] ['value'] + $requests [27] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) 
	{

		$dates = array();
		$current = strtotime($first);
		$last 	 = strtotime($last);

		while( $current <= $last ) {

			$dates[] = date($output_format, $current);
			$current = strtotime($step, $current);
		}

		return $dates;
	}
	
	public function statescount() {
		$count = $this->mongo_db->count ( 'panacea_states' );
		return $count;
	}
	public function get_states($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_states' );
		return $query;
	}
	public function get_all_states() {
		$query = $this->mongo_db->get ( 'panacea_states' );
		return $query;
	}
	
	// =================================================
	public function distcount() {
		$count = $this->mongo_db->count ( 'panacea_district' );
		return $count;
	}
	
	
	public function get_all_district($dt_name = "All") {
		if ($dt_name == "All") {
			$query = $this->mongo_db->orderBy ( array (
					'dt_name' => 1 
			) )->get ( 'panacea_district' );
		} else {
			$query = $this->mongo_db->where ( 'dt_name', $dt_name )->orderBy ( array (
					'dt_name' => 1 
			) )->get ( 'panacea_district' );
		}
		
		return $query;
	}
	public function health_supervisorscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_health_supervisors($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function create_health_supervisors($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['health_supervisors_email'] );
		$password = $post ['health_supervisors_password'];
		
		// Check if email already exists
		if ($this->user_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		// New user document
		$data = array (
				"school_code" => $post ['school_code'],
				"hs_name" => $post ['health_supervisors_name'],
				"hs_mob" => $post ['health_supervisors_mob'],
				"hs_ph" => $post ['health_supervisors_ph'],
				"password" => $password,
				"email" => $email,
				"hs_addr" => $post ['health_supervisors_addr'],
				
				"username" => $post ['health_supervisors_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company' => $this->session->userdata ( "customer" )['company'] 
		);
		
		// Store salt in document?
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['panacea_health_supervisors'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	
	// ///////////////////////////////////////////////////////////
	public function cc_users_count() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_cc_users($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function create_cc_user($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['email'] );
		$password = $post ['password'];
		
		// Check if email already exists
		if ($this->cc_user_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		// New user document
		$data = array (
				"name" => $post ['cc_user_name'],
				"mobile_number" => $post ['cc_user_mob'],
				"phone_number" => $post ['cc_user_ph'],
				"password" => $password,
				"email" => $email,
				"company_address" => $post ['cc_user_addr'],
				
				"username" => $post ['cc_user_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company_name' => $this->session->userdata ( "customer" )['company'] 
		);
		
		// Store salt in document?
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['panacea_cc'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function delete_cc_user($cc_id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				"_id" => new MongoId ( $cc_id ) 
		) )->delete ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	
	// ///////////////////////////////////////////////////////////////////
	public function schoolscount() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$count = $this->mongo_db->count ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $count;
	}
	public function get_schools($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function create_school($post) {
		$this->load->config ( 'ion_auth', TRUE );
		
		$email = strtolower ( $post ['school_email'] );
		$password = $post ['school_password'];
		
		// Check if email already exists
		if ($this->school_exists ( $email )) {
			$this->set_error ( 'account_creation_duplicate_email' );
			return FALSE;
		}
		
		// IP address
		$ip_address = $this->_prepare_ip ( $this->input->ip_address () );
		$salt = $this->store_salt ? $this->salt () : FALSE;
		$password = $this->hash_password ( $password, $salt );
		
		$data = array (
				"dt_name" => $post ['dt_name'],
				"school_code" => $post ['school_code'],
				"school_name" => $post ['school_name'],
				"school_addr" => $post ['school_addr'],
				"password" => $password,
				"email" => $email,
				"school_ph" => $post ['school_ph'],
				"school_mob" => $post ['school_mob'],
				"contact_person_name" => $post ['contact_person_name'],
				
				"username" => $post ['school_name'],
				'ip_address' => $ip_address,
				'created_on' => time (),
				'registered_on' => date ( "Y-m-d" ),
				'last_login' => date ( "Y-m-d H:i:s" ),
				// 'active' => ($admin_manual_activation === FALSE ? 1 : 0),
				'active' => 1,
				'company' => $this->session->userdata ( "customer" )['company'] 
		);
		// Store salt in document?
		if ($this->store_salt) {
			$data ['salt'] = $salt;
		}
		
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->insert ( $this->collections ['panacea_schools'], $data );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		// Return new document _id or FALSE on failure
		return isset ( $query ) ? $query : FALSE;
	}
	public function get_all_schools() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function classescount($school_code) {
		$count = $this->mongo_db->where('school_code',$school_code)->count ($this->collections['tswreis_classes']);
		return $count;
	}
	
	public function get_classes($per_page, $page, $school_code) 
	{
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->where('school_code',$school_code)->get ( $this->collections['tswreis_classes']);
		return $query;
	}
	
	public function sectionscount($school_code) {
		$count = $this->mongo_db->where('school_code',$school_code)->count ($this->collections['tswreis_sections']);
		return $count;
	}
	public function get_sections($per_page, $page, $school_code) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->where('school_code',$school_code)->get ( $this->collections['tswreis_sections']);
		return $query;
	}
	
	public function get_reports_ehr($ad_no) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page2.Personal Information.AD No", $ad_no )->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			return $result;
		}
	}
	public function get_reports_ehr_uid($uid,$school_name) {
	//"doc_data.widget_data.page2.Personal Information.School Name"=>$school_name,
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where(array("doc_data.widget_data.page1.Personal Information.Hospital Unique ID"=> $uid))->get ( $this->screening_app_col );
		
		//log_message('debug','query=====680=='.print_r($query,true));
		//log_message('debug','uid=====680=='.print_r($uid,true));
		//log_message('debug','school_name=====680=='.print_r($school_name,true));
		
		if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $uid )->get ( $this->request_app_col );
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( 'panacea_req_notes' );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}
				
			}
			
			$query_notes = $this->mongo_db->where ( "uid", $uid )->get ( $this->notes_col );

			if(isset($query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'])){
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( $this->collections ['panacea_schools'] );
				
				if(count($school_details) > 0){
					$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( $this->collections ['panacea_health_supervisors'] );
				}else{
					$query_hs[0] = false;
				}
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			}else{
				$query_hs[0] = false;
			}
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
			$result ['hs'] = false;
			return $result;
		}
	}
	
	public function get_students_uid($uid) {
		$query = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
		if ($query) {
			
			return $query [0];
		} else {
			return false;
		}
	}
	
	public function get_students_uid_for_print($uid) {
		$final_output = array();
		$screeningInfo = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
	
					foreach ( $screeningInfo as $screening )
					{
						$unique_id = $screening ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'];
						$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Student Info.Unique ID', $unique_id )->get ( $this->request_app_col);
							$info ['student_info'] = $screening;
							$info ['request_info'] = $doc;
							
						array_push ( $final_output, $info );
					}
			return $final_output;
		
	}
	
	public function create_diagnostic($post) {
		$data = array (
				"dt_name" => $post ['dt_name'],
				"diagnostic_code" => $post ['diagnostic_code'],
				"diagnostic_name" => $post ['diagnostic_name'],
				"diagnostic_ph" => $post ['diagnostic_ph'],
				"diagnostic_mob" => $post ['diagnostic_mob'],
				"diagnostic_addr" => $post ['diagnostic_addr'] 
		);
		$query = $this->mongo_db->insert ( 'panacea_diagnostics', $data );
		return $query;
	}
	public function create_hospital($post) {
		$data = array (
				"dt_name" => $post ['dt_name'],
				"hospital_code" => $post ['hospital_code'],
				"hospital_name" => $post ['hospital_name'],
				"hospital_ph" => $post ['hospital_ph'],
				"hospital_mob" => $post ['hospital_mob'],
				"hospital_addr" => $post ['hospital_addr'] 
		);
		$query = $this->mongo_db->insert ( 'panacea_hospitals', $data );
		return $query;
	}
	public function update_student_data($doc, $doc_id) {
		// $query = $this->mongo_db->where ( "_id", $doc_id )->set ( $doc )->update ( "naresh" );
		$query = $this->mongo_db->where ( "_id", $doc_id )->set ( $doc )->update ( $this->screening_app_col );
		
		return $query;
	}
	
	public function get_students($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->orderBy ( array (
				'doc_data.widget_data.page1.Personal Information.Name' => 1 
		) )->select ( array (
				"doc_data.widget_data" 
		) )->limit ( $per_page )->offset ( $page - 1 )->get ( $this->screening_app_col );
		return $query;
	}
	public function get_all_students() {
		ini_set ( 'memory_limit', '1G' );
		
		// $merged_array = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array('$in'=>array("Over Weight","Under Weight")));
		$count = $this->mongo_db->count ( $this->screening_app_col );
		// //log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		$per_page = 1000;
		$loop = $count / $per_page;
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			// //log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($page,true));
			// //log_message("debug","oooooooooooooooooooooooooooooooooooooooooo".print_r($offset,true));
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true 
							) 
					),
					// array('$match' => $merged_array)
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$result = array_merge ( $result, $response ['result'] );
			// //log_message("debug","response=====1643==".print_r($response,true));
			// //log_message("debug","response=====1643==".print_r(count($response['result']),true));
			// //log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($result,true));
		}
		//
		// //log_message("debug","response=====1643==".print_r(count($response['result']),true));
		//log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $result, true ) );
		
		// $query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
		// return $query;
		return $result;
	}
	public function hospitalscount() {
		$count = $this->mongo_db->count ( 'panacea_hospitals' );
		return $count;
	}
	public function get_hospitals($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_hospitals' );
		foreach ( $query as $hospitals => $hospital ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $hospital ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $hospital ['dt_name'] )) {
				$query [$hospitals] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$hospitals] ['dt_name'] = "No state selected";
			}
		}
		
		return $query;
	}
	public function diagnosticscount() {
		$count = $this->mongo_db->count ( 'panacea_diagnostics' );
		return $count;
	}
	public function get_diagnostics($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_diagnostics' );
		foreach ( $query as $diagnostics => $dia ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $dia ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $dia ['dt_name'] )) {
				$query [$diagnostics] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$diagnostics] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function empcount() {
		$count = $this->mongo_db->count ( 'panacea_emp' );
		return $count;
	}
	public function get_emp($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_emp' );
		return $query;
	}
	public function insert_student_data($doc_data, $history, $doc_properties) {
		// $query = $this->mongo_db->getWhere("naresh", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
		$query = $this->mongo_db->getWhere ( $this->screening_app_col, array (
				'doc_data.widget_data.page2.Personal Information.AD No' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'],
				'doc_data.widget_data.page2.Personal Information.School Name' => $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] 
		) );
		
		// $query = $this->mongo_db->getWhere("form_data_sample_copy_1", array('doc_data.widget_data.page2.Physical Info.ID number' => $doc_data['widget_data']['page2']['Physical Info']['ID number'],'doc_data.widget_data.page2.Physical Info.School'=>'TSWRS/JC(G)-JADCHERLA'));
		
		$result = json_decode ( json_encode ( $query ), FALSE );
		if (! $result) {
			$form_data = array ();
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
			
			// $this->mongo_db->insert("naresh",$form_data);
			
			$this->mongo_db->insert ( $this->screening_app_col, $form_data );
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		} else {
			$form_data = array ();
			$form_data ['doc_data'] = $doc_data;
			$form_data ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] = $doc_data ['widget_data'] ['page2'] ['Personal Information'] ['AD No'] . 'A';
			$form_data ['doc_properties'] = $doc_properties;
			$form_data ['history'] = $history;
			
			// $this->mongo_db->insert("naresh",$form_data);
			$this->mongo_db->insert ( $this->screening_app_col, $form_data );
			// $this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		}
	}
	
	public function get_all_symptoms($date = false, $request_duration = "Monthly",$unique_id_pattern) 
	{
		$query = [ ];
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'] ,$unique_id_pattern);
		
		$doc_query = array ();
		
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				/*$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=>$unique_id))->get ( $this->screening_app_col );*/
				
				if (isset ( $unique_id ) && ! empty ( $unique_id ) && (count ( $unique_id ) > 0)) {
				array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
		
		$prob_arr = [ ];
		foreach ( $query as $doc ) {
			if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'] )) {
				$problems = $doc ['doc_data'] ['widget_data'] ['page1'] ['Problem Info'] ['Identifier'];
				foreach ( $problems as $problem ) {
					if (isset ( $prob_arr [$problem] )) {
						$prob_arr [$problem] ++;
					} else {
						$prob_arr [$problem] = 1;
					}
				}
			}
		}
		
		
		$final_values = [ ];
		foreach ( $prob_arr as $prob => $count ) {
			
			$result ['label'] = $prob;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function get_absent_pie_schools_data($date = FALSE, $dt_name = "All")
	{
		// Variables
		$all_schools               = array();
		$all_schools_district      = array();
		$all_schools_name      	   = array();
		$submitted_schools 	       = array();
		$submitted_school_district = array();
		$submitted_school_name     = array();
		$not_submitted_schools	   = array();
		$schools_data              = array();
		$not_submitted_dist        = array();
		
		$schools_list = $this->get_all_schools();
		
		foreach($schools_list as $school_data)
		{
			array_push($all_schools_district,$school_data['dt_name']);
			array_push($all_schools_name,$school_data['school_name']);
		}
		
		$all_schools['district'] = $all_schools_district; 
		$all_schools['school']   = $all_schools_name; 
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====716=='.print_r($query,true));
		//log_message('debug','$schools_data=====get_absent_pie_schools_data=====717=='.print_r($today_date,true));
		
		foreach ( $query as $doc ) {
			    if(!in_array($doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'],$submitted_school_name))
				{
					array_push ( $submitted_school_district,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] );
					array_push ( $submitted_school_name,$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] );
		        }
		}
		
		$submitted_schools['district']     = $submitted_school_district;
		$submitted_schools['school']       = $submitted_school_name;
		$not_submitted_schools['district'] = array();
		$not_submitted_schools['school']   = array_values(array_diff($all_schools['school'],$submitted_schools['school']));
		foreach($not_submitted_schools['school'] as $index => $school_name)
		{
		   $dist_array    = explode(",",$school_name);
		   $dist_array[1] = strtolower($dist_array[1]);
		   array_push($not_submitted_dist,ucfirst($dist_array[1]));
		}
		$not_submitted_schools['district']   = $not_submitted_dist;
		$schools_data['submitted']     		 = $submitted_schools;
		$schools_data['submitted_count']     = count($submitted_schools['school']);
		$schools_data['not_submitted'] 		 = $not_submitted_schools;
		$schools_data['not_submitted_count'] = count($not_submitted_schools['school']);
		
		////log_message('debug','$schools_data=====get_absent_pie_schools_data=====735=='.print_r($schools_data,true));
		////log_message('debug','$schools_data=====get_absent_pie_schools_data=====736=='.print_r(gettype($schools_data['submitted']),true));
		
		return $schools_data;
	}
	
	public function get_attendance_submitted_school_name($date = FALSE,$school_name)
	{
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->where('doc_data.widget_data.page1.Attendence Details.Select School',$school_name)->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		return $query;
		
	}
	
	public function get_all_absent_data($date = FALSE,$school_name) {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->where('doc_data.widget_data.page1.Attendence Details.Select School',$school_name)->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		$absent = 0;
		$sick = 0;
		$restRoom = 0;
		$r2h = 0;
		
		foreach ( $query as $report ) {
			$absent = $absent + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Absent'] );
			$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
			$restRoom = $restRoom + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
			$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
		}
		
		$requests = [ ];
		
		$request ['label'] = 'ABSENT REPORT';
		$request ['value'] = $absent;
		array_push ( $requests, $request );
		
		$request ['label'] = 'SICK CUM ATTENDED';
		$request ['value'] = $sick;
		array_push ( $requests, $request );
		
		$request ['label'] = 'REST ROOM IN MEDICATION';
		$request ['value'] = $restRoom;
		array_push ( $requests, $request );
		
		$request ['label'] = 'REFER TO HOSPITAL';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	
	public function get_all_sanitation_report_data($date = FALSE, $dt_name = "All", $school_name = "All") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ($this->sanitation_report_app_col );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page6'] ['School Information'] ['District'] ) == strtolower ( $dt_name )) {
						array_push ( $doc_query, $doc );
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page6'] ['School Information'] ['School Name'] ) == strtolower ( $school_name )) {
					array_push ( $doc_query, $doc );
				}
			}
			$query = $doc_query;
		}
		
		$handwash         = 0;
		$kitchen          = 0;
		$waste_management = 0;
		$cleanliness      = 0;
		$food             = 0;
		// $attended = 0;
		foreach ( $query as $report ) {
			$absent = $absent + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Absent'] );
			$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
			$restRoom = $restRoom + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
			$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
			// $attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
		}
		
		$requests = [ ];
		
		$request ['label'] = 'Handwash';
		$request ['value'] = $absent;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Kitchen';
		$request ['value'] = $sick;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Waste Management';
		$request ['value'] = $restRoom;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Cleanliness';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Food';
		$request ['value'] = $r2h;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	public function drilldown_absent_to_districts($data, $date, $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		ini_set ( 'memory_limit', '1G' );
		switch ($type) {
			case "ABSENT REPORT" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") 
				{
					if ($dt_name != "All") 
					{
						foreach ( $query as $doc ) 
						{
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) 
							{
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} 
					else 
					{
						
					}
				} 
				else 
				{
					foreach ( $query as $doc ) 
					{
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) 
						{
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"Absent");
				break;
			
			case "SICK CUM ATTENDED" :
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"Sick");
				break;
			
			case "REST ROOM IN MEDICATION" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"RestRoom");
				break;
			
			case "REFER TO HOSPITAL" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_attendance_districts_prepare_pie_array ($query,"R2H");
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_schools($data, $date, $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		// ////log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
		ini_set ( 'memory_limit', '1G' );
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		// ////log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
		switch ($type) {
			case "ABSENT REPORT" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"Absent");
				
				break;
			case "SICK CUM ATTENDED" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"Sick");
				
				break;
			
			case "REST ROOM IN MEDICATION" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"RestRoom" );
				
				break;
			
			case "REFER TO HOSPITAL" :
				
				ini_set ( 'memory_limit', '1G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_schools_prepare_pie_array ( $query, $dist,"R2H");
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_students($type, $date, $school_name) 
	{
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else
		{
			$today_date = $this->today_date;
		}
		
		ini_set ( 'memory_limit', '10G' );
		
		switch ($type) 
		{
			case "ABSENT REPORT" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->where("doc_data.widget_data.page1.Attendence Details.Select School",$school_name)->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			case "SICK CUM ATTENDED" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			case "REST ROOM IN MEDICATION" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			case "REFER TO HOSPITAL" :
			ini_set ( 'memory_limit', '5G' );
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($dt_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dt_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_absent_students_prepare_pie_array ( $query, $school_name, $type );
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_absent_students_docs($_id_array) {
		$docs = [ ];
		
		ini_set ( 'memory_limit', '512M' );
		
		if(isset($_id_array) && !empty($_id_array))
		{
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where/* Like */ ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( "tswreis_screening_report_col_2020-2021" );
			if ($query)
				array_push ( $docs, $query [0] );
		}
		//log_message('debug','drill_down_absent_to_students_load_ehr=====docs=====1356====='.print_r($docs,true));
		}
		
		return $docs;
	}
	public function get_drilling_attendance_districts_prepare_pie_array($query,$category) {
		$requests = [ ];
		
		$dist_list = $this->get_all_district();
		
		$dist_arr = [ ];
		foreach ( $dist_list as $dist ) {
			array_push ( $dist_arr, $dist ['dt_name'] );
		}
		
		foreach ( $dist_arr as $districts ) 
		{
			$request ['label'] = $districts;
			$count = 0;
			if ($query) {
				foreach ( $query as $dist ) {
					if (isset ( $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] )) {
						if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $districts )) {
							//$count ++;
							if($category=="RestRoom")
							{
								$count = $count + (int) $dist ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] [$category];
							}
							else
							{
								$count = $count + (int) $dist ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] [$category];
							}
							
						}
					}
				}
			}
			$request ['value'] = $count;
			array_push ( $requests, $request );
		}
		
		return $requests;
	}
	public function get_drilling_absent_schools_prepare_pie_array($query, $dist,$category) {
		// ////log_message("debug","2222222222222222222222222222222222222222222222222".print_r($query,true));
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				// ////log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == $dist) {
						array_push ( $search_result, $doc );
					}
				}
			}
			
			$request = [ ];
			foreach ( $search_result as $doc ) 
			{
				if($category=="RestRoom")
				{
					$request[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] = (int) $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] [$category];
				}
				else
				{
					$request[$doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Select School']] = (int) $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] [$category];
				}
			}
			
			
			$final_values = [ ];
			foreach ( $request as $school => $count ) 
			{
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			
			
			return $final_values;
		}
	}
	public function get_drilling_absent_students_prepare_pie_array($search_result, $school_name, $type) 
	{
		$count = 0;
		$request = [ ];
		$UI_arr = [ ];
			
			foreach ( $search_result as $doc ) 
			{
				switch ($type) 
				{
					case "ABSENT REPORT" :
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['Absent UID'] );
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						break;
						
					case "SICK CUM ATTENDED" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick UID'] );
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						break;
					
					case "REST ROOM IN MEDICATION" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom UID'] );
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						break;
					
					case "REFER TO HOSPITAL" :
						
						$absent_id_arr = explode ( ",", $doc ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H UID'] );
						$UI_arr = array_merge ( $UI_arr, $absent_id_arr );
						break;
					
					default :
						break;
				}
			}
			
			return $UI_arr;
		   
	}
	
	public function get_all_symptoms_docs($start_date, $end_date, $unique_id_pattern,$id_for_school = false) {
		ini_set ( 'max_execution_time', 0 );
		////log_message('debug','$id_for_school=====3900====='.print_r($id_for_school,true));
		////log_message('debug','$unique_id_pattern=====3901====='.print_r($unique_id_pattern,true));
		
		if ($id_for_school) {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
					$id_for_school 
			) )->get ( $this->request_app_col );
		} else {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->select ( array (
					"doc_data.widget_data",
					"history" 
			) )->get ( $this->request_app_col );
			
			//log_message('debug','$unique_id_pattern=====3914====='.print_r(count($query),true));
		}
		
		$result = [ ];
		foreach ( $query as $doc ) {
			
			if($doc['doc_data']['widget_data']['page2']['Review Info']['Status'] != "Cured"){
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
				}
			}
		}
		$query = $result;
		return $query;
	}
	
	public function get_all_requests($date = false, $request_duration = "Monthly",$unique_id_pattern) {
		$query = [ ];
		$doc_query = array();
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $unique_id_pattern);
		
		//log_message('debug','$unique_id_pattern=====3943====='.print_r($query,true));
		
		foreach ( $query as $doc ) {
				
			$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			/*$screening_doc = $this->mongo_db->select ( array (
					'doc_data.widget_data.page2' 
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );*/
			
			if (isset ( $unique_id ) && ! empty ( $unique_id ) && (count ( $unique_id ) > 0)) {
				array_push ( $doc_query, $doc );
				
			}
		}
		
		$query = $doc_query;
		
		//log_message('debug','$unique_id_pattern=====3960====='.print_r(count($query),true));
		
		$device_initiated = 0;
		$web_initiated = 0;
		$screening_initiated = 0;
		$prescribed = 0;
		$medication = 0;
		$followUp = 0;
		$cured = 0;
		
		$req_normal = 0;
		$req_emergency = 0;
		$req_chronic = 0;
		
		foreach ( $query as $report ) {
			$status = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Status'];
			
			if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if (($user_type == "CCUSER")) {
						$web_initiated ++;
					}else if(($user_type == "PADMIN")){
						$screening_initiated ++;
					} else if(($user_type == "HS")){
						$device_initiated ++;
					}
				} else {
					//$device_initiated ++;
					//log_message("debug","No Submitted_user_type============3966");
				}
			
			if ($status == "Initiated") {
				// if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					// $user_type = $report ['history'] [0] ['submitted_user_type'];
					// if (($user_type == "CCUSER")) {
						// $web_initiated ++;
					// }else if(($user_type == "PADMIN")){
						// $screening_initiated ++;
					// } else {
						// $device_initiated ++;
					// }
				// } else {
					// $device_initiated ++;
				// }
			} else if ($status == "Prescribed") {
				$prescribed ++;
			} else if ($status == "Under Medication") {
				$medication ++;
			} else if ($status == "Follow-up") {
				$followUp ++;
			} else if ($status == "Cured") {
				$cured ++;
			}
			
			$request_type = $report ['doc_data'] ['widget_data'] ['page2'] ['Review Info'] ['Request Type'];
			if ($request_type == "Normal") {
				$req_normal ++;
			} else if ($request_type == "Emergency") {
				$req_emergency ++;
			} else if ($request_type == "Chronic") {
				$req_chronic ++;
			}
		}
		
		$requests = [ ];
		
		$request ['label'] = 'Device Initiated';
		$request ['value'] = $device_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Web Initiated';
		$request ['value'] = $web_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Screening Initiated';
		$request ['value'] = $screening_initiated;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Prescribed';
		$request ['value'] = $prescribed;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Under Medication';
		$request ['value'] = $medication;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Follow-up';
		$request ['value'] = $followUp;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Cured';
		$request ['value'] = $cured;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Normal Req';
		$request ['value'] = $req_normal;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Emergency Req';
		$request ['value'] = $req_emergency;
		array_push ( $requests, $request );
		
		$request ['label'] = 'Chronic Req';
		$request ['value'] = $req_chronic;
		array_push ( $requests, $request );
		
		return $requests;
	}
	
	// ======================================================================
	public function get_all_requests_docs($start_date, $end_date, $type = false, $school_name, $unique_id_pattern) 
	{
	    //log_message('debug','get_all_requests_docs====4067==>'.print_r($type,true));
	    //log_message('debug','get_all_requests_docs====4068==>'.print_r($unique_id_pattern,true));
		
		if ($type == "Device Initiated") 
		{
			$query = $this->mongo_db->where ( array (
					'history.0.submitted_user_type' => "HS"
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->get($this->request_app_col);
			
			//log_message('debug','get_all_requests_docs====4076==>'.print_r($query,true));
		}
		else if ($type == "Web Initiated") 
		{
			$query = $this->mongo_db->where ( array (
					'history.0.submitted_user_type' => "CCUSER" 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->get($this->request_app_col);
			
			//log_message('debug','get_all_requests_docs====4076==>'.print_r($query,true));
		}
		else if($type == "Screening") 
		{
			$query = $this->mongo_db->where ( array (
					'history.0.submitted_user_type' => "PADMIN" 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern)->get ( $this->request_app_col );
		} 
		else if($type == "Normal") 
		{
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Normal"
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern)->get ( $this->request_app_col );
		} 
		else if($type == "Emergency") 
		{
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Emergency" 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern)->get ( $this->request_app_col );
		} 
		else if($type == "Chronic") 
		{
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Request Type' => "Chronic" 
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern)->get ( $this->request_app_col );
		} 
		else 
		{
			$query = $this->mongo_db->whereLike ( 'doc_data.widget_data.page2.Review Info.Status', $type )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern)->get ( $this->request_app_col );
			//log_message("debug","Without any Status type ==============4096");
		}
		
		$doc_query = array ();
		
		foreach ( $query as $doc ) 
		{
			$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			//log_message('debug','get_all_requests_docs====4112==>'.print_r($unique_id,true));
			$screening_doc = $this->mongo_db->select ( array (
					'doc_data.widget_data.page2' 
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			
			if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					array_push ( $doc_query, $doc );
				
			}
		}
		$query = $doc_query;
		
		$result = [ ];
		
		foreach ( $query as $doc ) 
		{
			if($doc['doc_data']['widget_data']['page2']['Review Info']['Status'] != "Cured"){
				
			foreach ( $doc ['history'] as $date ) 
			{
				$time = $date ['time'];
				//log_message('debug','get_all_requests_docs====4131==>'.print_r($time,true));
				
				if (($time <= $start_date) && ($time >= $end_date)) {
				//log_message('debug','get_all_requests_docs====4134==>'.print_r($start_date,true));
				//log_message('debug','get_all_requests_docs====4135==>'.print_r($end_date,true));
					array_push ( $result, $doc );
					break;
				}
			}
			}
		}
		$query = $result;
		return $query;
	}
	
	public function drilldown_request_to_districts($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All") {
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		// ini_set('memory_limit', '512M');
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Device Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} else {
					array_push ( $query, $report );
				}
			}
		} else if ($type == "Screening Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Web Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Web Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name );
		}
		
		$dist_list = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (isset ( $dist_list [$district] )) {
					$dist_list [$district] ++;
				} else {
					$dist_list [$district] = 1;
				}
			}
		}
		
		$final_values = [ ];
		foreach ( $dist_list as $dicsts => $count ) {
			$result ['label'] = $dicsts;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_request_schools($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		
		if ($type == "Device Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Device Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} else {
					array_push ( $query, $report );
				}
			}
		} else if ($type == "Web Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Web Initiated", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Screening Initiated") {
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $dt_name, $school_name );
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") {
						array_push ( $query, $report );
					}
				}
			}
		} else if ($type == "Normal Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $dt_name, $school_name );
		} else if ($type == "Emergency Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $dt_name, $school_name );
		} else if ($type == "Chronic Req") {
			// $query = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $dt_name, $school_name );
		} else {
			// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $dt_name, $school_name );
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
		
		$school_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == $dist) {
					array_push ( $matching_docs, $doc [0] );
				}
			}
		}
		
		foreach ( $matching_docs as $docs ) {
			$school_name = $docs ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
			if (isset ( $school_list [$school_name] )) {
				$school_list [$school_name] ++;
			} else {
				$school_list [$school_name] = 1;
			}
		}
		
		$final_values = [ ];
		foreach ( $school_list as $school => $count ) {
			$result ['label'] = $school;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function get_drilling_request_students($type, $date = false, $request_duration = "Monthly",$school_name,$id_pattern) 
	{
		$query = [ ];
		
		if ($date)
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		
		//$id_pattern = "/".$id_pattern."/";
		
		if ($type == "Device Initiated") 
		{
			/* //log_message("debug","typeeeeeeeeeeeeeeee".print_r($type,true));
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $school_name, $id_pattern);
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type != "CCUSER") {
						array_push ( $query, $report );
					}
				} /* else {
					array_push ( $query, $report );
				} 
			} */
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Device Initiated", $school_name, $id_pattern);
		} 
		else if ($type == "Web Initiated") 
		{
			/* $query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$school_name, $id_pattern);
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			} */
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Web Initiated",$school_name, $id_pattern);
		} 
		else if ($type == "Screening Initiated") 
		{
			/* $query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $school_name, $id_pattern);
			
			$query = [ ];
			foreach ( $query_temp as $report ) 
			{
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) 
				{
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "PADMIN") 
					{
						array_push ( $query, $report );
					}
				}
			} */
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $school_name, $id_pattern);
		} 
		else if ($type == "Normal Req") 
		{
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Normal", $school_name, $id_pattern);
		} 
		else if ($type == "Emergency Req") 
		{
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Emergency", $school_name, $id_pattern);
		} 
		else if ($type == "Chronic Req") 
		{
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Chronic", $school_name, $id_pattern);
		} 
		else 
		{
			$query = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], $type, $school_name, $id_pattern);
		}
		
		$matching_docs = [ ];
		
		foreach ( $query as $request ) 
		{
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && count ( $doc ) > 0) {
				array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
			}
		}
		return $matching_docs;
	}
	
	public function get_drilling_request_students_docs($_id_array) {
		$docs = [ ];
		//log_message ( "debug", "dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd" . print_r ( $_id_array, true ) );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	
	// ----------------------------------------------------------------------
	
	// ===================================id=======================+==========================
	public function drilldown_identifiers_docs($start_date, $end_date, $type) {
		ini_set ( 'memory_limit', '1G' );
		$query = $this->mongo_db->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
				$type 
		) )->get ( $this->request_app_col );
		
		$result = [ ];
		foreach ( $query as $doc ) {
			
			foreach ( $doc ['history'] as $date ) {
				$time = $date ['time'];
				
				if (($time <= $start_date) && ($time >= $end_date)) {
					array_push ( $result, $doc );
					break;
				}
			}
		}
		$query = $result;
		
		return $query;
	}
	public function drilldown_identifiers_to_districts($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All") {
		//log_message ( 'debug', 'drilldown_identifiers_to_districts==dddddddddddddddddddddddddddddddddddddddd----' . print_r ( $dt_name, true ) );
		//log_message ( 'debug', 'ssssssssssssssssssssssssssssssssssssssss----' . print_r ( $school_name, true ) );
		//log_message ( 'debug', 'dttttttttttttttttttttttttttttttttttttttt----' . print_r ( $data, true ) );
		ini_set ( 'max_execution_time', 0 );
		ini_set ( 'memory_limit', '5G' );
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		//log_message ( 'debug', 'drilldown_identifiers_to_districts=====type----' . print_r ( $type, true ) );
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		//log_message ( 'debug', 'drilldown_identifiers_to_districts----query-----' . print_r ( $query, true ) );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} 
			else 
			{
				// ADDED BY SELVA
				foreach ($query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					//log_message ( 'debug', 'drilldown_identifiers_to_districts----unique_id-----' . print_r ( $unique_id, true ) );
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					//log_message ( 'debug', 'drilldown_identifiers_to_districts----screening_doc-----' . print_r ( $screening_doc, true ) );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						
							array_push ( $doc_query, $doc );
					}
				}
				//log_message ( 'debug', 'drilldown_identifiers_to_districts----doc_query-----' . print_r ( $doc_query, true ) );
				$query = $doc_query;
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
		$dist_list = [ ];
		
		foreach ( $query as $identifiers ) {
			
			$retrieval_list = array ();
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			//log_message ( 'debug', 'unique_id----' . print_r ( $unique_id, true ) );
			$doc = $this->mongo_db->where/*Like*/ ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
		    //log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
			//log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r(count($doc),true));
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (isset ( $dist_list [$district] )) {
					$dist_list [$district] ++;
				} else {
					$dist_list [$district] = 1;
				}
			}
		}
		
		$final_values = [ ];
		foreach ( $dist_list as $dicsts => $count ) {
			$result ['label'] = $dicsts;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	public function get_drilling_identifiers_schools($data, $date = false, $request_duration = "Monthly", $dt_name = "All", $school_name = "All") {
		$query = [ ];
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		ini_set ( 'memory_limit', '10G' );
		
		$doc_query = array ();
		if ($school_name == "All") {
			if ($dt_name != "All") {
				foreach ( $query as $doc ) {
					$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $dt_name )) {
							array_push ( $doc_query, $doc );
						}
					}
				}
				$query = $doc_query;
			} else {
			}
		} else {
			foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
			}
			$query = $doc_query;
		}
		
		// ini_set('memory_limit', '512M');
		// $query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
		
		$school_list = [ ];
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->whereLike ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$district = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'];
				if (strtolower ( $district ) == strtolower ( $dist )) {
					array_push ( $matching_docs, $doc [0] );
				}
			}
		}
		
		foreach ( $matching_docs as $docs ) {
			$school_name = $docs ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
			if (isset ( $school_list [$school_name] )) {
				$school_list [$school_name] ++;
			} else {
				$school_list [$school_name] = 1;
			}
		}
		
		$final_values = [ ];
		foreach ( $school_list as $school => $count ) {
			$result ['label'] = $school;
			$result ['value'] = $count;
			array_push ( $final_values, $result );
		}
		
		return $final_values;
	}
	
	public function get_drilling_identifiers_students($type, $date = false,$request_duration = "Monthly",$school_name,$id_pattern) 
	{
		$query = [];
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $id_pattern,$type );
		
		$doc_query = array ();
		
		foreach ( $query as $doc ) {
				
				$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
					if (strtolower ( $screening_doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == strtolower ( $school_name )) {
						array_push ( $doc_query, $doc );
					}
				}
		}
		$query = $doc_query;
		
		$matching_docs = [ ];
		
		foreach ( $query as $request ) {
			
			$retrieval_list = array ();
			$unique_id = $request ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if ($school == $school_name) {
					array_push ( $matching_docs, $doc [0] ['_id']->{'$id'} );
				}
			}
		}
		
		return $matching_docs;
	}
	
	public function get_drilling_identifiers_students_docs($_id_array) {
		$docs = [ ];
		
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		//log_message('debug','get_drilling_identifiers_students_docs===tsreis==4710'.print_r($docs,true));
		return $docs;
	}
	
	private function screening_pie_data_for_stage3_old($dates) {
		ini_set ( 'max_execution_time', 0 );
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 3 =================================================
		
		$requests = [ ];
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$request ["Over Weight"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==========================================================================================
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		$request ["Under Weight"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["General"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		$and_merged_array = array ();
		
		$merged_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Skin"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		array_push ( $requests, $request );
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Ortho"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Postural"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Defects at Birth'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Deficencies'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Childhood Diseases'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Without Glasses'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['With Glasses'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Colour Blindness'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_right );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Right Ear'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Left Ear'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		$request ['Speech Screening'] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Oral Hygiene - Fair"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Oral Hygiene - Poor"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Carious Teeth"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Flourosis"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Orthodontic Treatment"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		$request ["Indication for extraction"] = $this->get_drilling_screenings_districts_prepare_pie_array ( $result );
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	private function screening_pie_data_for_stage4($dates) {
		ini_set ( 'max_execution_time', 0 );
		
		$dist_list = $this->get_all_district ();
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		// ======================================================stage 3 =================================================
		
		$requests = [ ];
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Over Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		// //log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddlist--------'.print_r($dist_list,true));
		foreach ( $dist_list as $dist ) {
			// //log_message('debug','ddddddddddddddddddddddddddddddddddddddd--------------'.print_r(strtolower($dist["dt_name"]),true));
			$request ["Over Weight"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==========================================================================================
		
		$merged_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Under Weight" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Under Weight"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ========================================================================================
		
		$and_merged_array = array ();
		
		$general_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => '' 
				) 
		);
		$general_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => ' ' 
				) 
		);
		$general_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$ne' => [ ] 
				) 
		);
		
		$not_skin = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$nin' => array (
								"Skin" 
						) 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $general_str_empty );
		array_push ( $and_merged_array, $general_str_space );
		array_push ( $and_merged_array, $general_arr );
		array_push ( $and_merged_array, $not_skin );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["General"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$and_merged_array = array ();
		
		$merged_array = array (
				"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
						'$in' => array (
								"Skin" 
						) 
				) 
		);
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => $merged_array 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Skin"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$ortho = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		
		$ortho_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => '' 
				) 
		);
		$ortho_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => ' ' 
				) 
		);
		$ortho_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $ortho );
		array_push ( $and_merged_array, $ortho_str_empty );
		array_push ( $and_merged_array, $ortho_str_space );
		array_push ( $and_merged_array, $ortho_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Ortho"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================
		
		$and_merged_array = array ();
		
		$postural = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$postural_str_empty = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => '' 
				) 
		);
		$postural_str_space = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => ' ' 
				) 
		);
		$postural_arr = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$ne' => [ ] 
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $and_merged_array, $postural );
		array_push ( $and_merged_array, $postural_str_empty );
		array_push ( $and_merged_array, $postural_str_space );
		array_push ( $and_merged_array, $postural_arr );
		array_push ( $and_merged_array, $page4_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ["Postural"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ========================================================================
		
		$and_merged_array = array ();
		
		$birth_defect = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$exists' => true 
				) 
		);
		
		$birth_defect_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => '' 
				) 
		);
		$birth_defect_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => ' ' 
				) 
		);
		$birth_defect_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $birth_defect_str_empty );
		array_push ( $and_merged_array, $birth_defect_str_space );
		array_push ( $and_merged_array, $birth_defect_arr );
		
		array_push ( $and_merged_array, $birth_defect );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ['Defects at Birth'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		
		$deficencies = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$exists' => true 
				) 
		);
		
		$deficencies_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => '' 
				) 
		);
		$deficencies_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => ' ' 
				) 
		);
		$deficencies_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $deficencies_str_empty );
		array_push ( $and_merged_array, $deficencies_str_space );
		array_push ( $and_merged_array, $deficencies_arr );
		
		array_push ( $and_merged_array, $deficencies );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		foreach ( $dist_list as $dist ) {
			$request ['Deficencies'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		
		$childhood_diseases = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$not' => array (
								'$size' => 0 
						) 
				) 
		);
		$page5_exists = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$exists' => true 
				) 
		);
		
		$childhood_diseases_str_empty = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => '' 
				) 
		);
		$childhood_diseases_str_space = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => ' ' 
				) 
		);
		$childhood_diseases_arr = array (
				"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (
						'$ne' => [ ] 
				) 
		);
		
		array_push ( $and_merged_array, $childhood_diseases_str_empty );
		array_push ( $and_merged_array, $childhood_diseases_str_space );
		array_push ( $and_merged_array, $childhood_diseases_arr );
		
		array_push ( $and_merged_array, $childhood_diseases );
		array_push ( $and_merged_array, $page5_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Childhood Diseases'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$without_glass_left = array (
				"doc_data.widget_data.page6.Without Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"" 
						) 
				) 
		);
		$without_glass_right = array (
				"doc_data.widget_data.page6.Without Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.Without Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $without_glass_left );
		array_push ( $or_merged_array, $without_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Without Glasses'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// =============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$with_glass_left = array (
				"doc_data.widget_data.page6.With Glasses.Left" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		$with_glass_right = array (
				"doc_data.widget_data.page6.With Glasses.Right" => array (
						'$nin' => array (
								"6/6",
								"",
								" " 
						) 
				) 
		);
		
		$page6_exists = array (
				"doc_data.widget_data.page6.With Glasses" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $with_glass_left );
		array_push ( $or_merged_array, $with_glass_right );
		array_push ( $and_merged_array, $page6_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['With Glasses'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$color_right = array (
				"doc_data.widget_data.page7.Colour Blindness.Right" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$color_left = array (
				"doc_data.widget_data.page7.Colour Blindness.Left" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		
		$page7_exists = array (
				"doc_data.widget_data.page7.Colour Blindness" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $color_right );
		array_push ( $or_merged_array, $color_left );
		array_push ( $and_merged_array, $page7_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Colour Blindness'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_right = array (
				"doc_data.widget_data.page8. Auditory Screening.Right" => array (
						'$nin' => array (
								"Pass",
								"",
								" " 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_right );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Right Ear'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$audi_left = array (
				"doc_data.widget_data.page8. Auditory Screening.Left" => array (
						'$nin' => array (
								"Pass",
								" ",
								"" 
						) 
				) 
		);
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $audi_left );
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Left Ear'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ====================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$speech = array (
				"doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array (
						'$nin' => array (
								"Normal",
								"",
								" ",
								[ ] 
						) 
				) 
		);
		
		$page8_exists = array (
				"doc_data.widget_data.page8. Auditory Screening" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $speech );
		
		array_push ( $and_merged_array, $page8_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ['Speech Screening'] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// =============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Poor",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Oral Hygiene - Fair"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$oral_hygiene = array (
				"doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => array (
						'$nin' => array (
								"Good",
								"Fair",
								"",
								" " 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $oral_hygiene );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Oral Hygiene - Poor"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$carious_teeth = array (
				"doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => array (
						'$nin' => array (
								"No",
								" ",
								"" 
						) 
				) 
		);
		
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $carious_teeth );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Carious Teeth"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$flourosis = array (
				"doc_data.widget_data.page9.Dental Check-up.Flourosis" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $flourosis );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Flourosis"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ==============================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$orthodontic = array (
				"doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $orthodontic );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Orthodontic Treatment"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ===========================================================================================
		
		$and_merged_array = array ();
		$or_merged_array = array ();
		
		$indication = array (
				"doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => array (
						'$nin' => array (
								"No",
								"",
								" " 
						) 
				) 
		);
		$page9_exists = array (
				"doc_data.widget_data.page9.Dental Check-up" => array (
						'$exists' => true 
				) 
		);
		
		array_push ( $or_merged_array, $indication );
		array_push ( $and_merged_array, $page9_exists );
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			$pipeline = [ 
					array (
							'$project' => array (
									"doc_data.widget_data" => true,
									"history" => true 
							) 
					),
					array (
							'$match' => array (
									'$and' => $and_merged_array,
									'$or' => $or_merged_array 
							) 
					),
					array (
							'$limit' => $offset 
					),
					array (
							'$skip' => $offset - $per_page 
					) 
			];
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->screening_app_col,
					'pipeline' => $pipeline 
			) );
			$temp_result = [ ];
			foreach ( $response ['result'] as $doc ) {
				
				foreach ( $doc ['history'] as $date ) {
					$time = $date ['time'];
					
					if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
						array_push ( $temp_result, $doc );
						break;
					}
				}
			}
			
			$result = array_merge ( $result, $temp_result );
		}
		
		foreach ( $dist_list as $dist ) {
			$request ["Indication for extraction"] [strtolower ( $dist ["dt_name"] )] = $this->get_drilling_screenings_schools_prepare_pie_array ( $result, strtolower ( $dist ["dt_name"] ) );
		}
		
		// ======================================================end of stage 3 ===========================================
		return $request;
	}
	
	
	
	public function get_last_screening_update() {
		$query = $this->mongo_db->limit ( 1 )->orderBy ( array (
				'pie_data.date' => - 1 
		) )->select ( 'pie_data.date' )->get ( $this->screening_app_col_screening );
		
		if (isset ( $query ) && ! empty ( $query ) && (count ( $query ) > 0)) {
			return "Last update on : " . substr ( $query [0] ['pie_data'] ['date'], 0, 10 );
		} else {
			return "No updates yet.";
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch screening analytics data to show pie
	 *
	 * @param  string $date 				Date
	 * @param  string $screening_duration 	Duration for the screening ( from the selected date )
	 *
	 * @return array
	 */
	 
	public function get_all_screenings($date = false, $screening_duration = "Yearly") 
	{
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}		

		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		ini_set ( 'memory_limit', '10G' );
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$requests ['Physical Abnormalities'] = 0;
		$requests ['General Abnormalities']  = 0;
		$requests ['Eye Abnormalities']      = 0;
		$requests ['Auditory Abnormalities'] = 0;
		$requests ['Dental Abnormalities']   = 0;
		
		foreach ( $pie_data as $each_pie ) {
			
			$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [0] ['value'];
			$requests ['General Abnormalities']  = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [1] ['value'];
			$requests ['Eye Abnormalities']      = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [2] ['value'];
			$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [3] ['value'];
			$requests ['Dental Abnormalities']   = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [4] ['value'];
		}
		
		$result = [ ];
		foreach ( $requests as $request => $req_value ) {
			$req ['label'] = $request;
			$req ['value'] = $req_value;
			array_push ( $result, $req );
		}
		return $result;
	}
	
	public function get_drilling_screenings_abnormalities($data, $date = false, $screening_duration = "Yearly") {
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type     = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		
		if(isset($pie_data [0] ['pie_data'] ['stage2_pie_values'] [22] ['Dental Abnormalities'] ['label']) && !empty($each_pie ['pie_data'] ['stage2_pie_values'] [22] ['Dental Abnormalities'] ['label']))
		{
			switch ($type) {
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "General Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'General';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [2] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [3] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [10] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [11] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Eye Lids';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [13] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Conjunctiva';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [14] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Cornea';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [15] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Pupil';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [16] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Wearing Spectacles';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [17] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );

				$request ['label'] = 'Subjective Refraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [18] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [19] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [20] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [21] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene - Fair';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [22] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [23] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [24] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [25] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [26] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [27] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}
		}else{
			switch ($type) {
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "General Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'General';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [2] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [3] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [10] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [11] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [13] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [14] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [15] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene - Fair';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [16] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [17] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [18] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [19] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [20] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// //log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [21] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}
		}
	
		
		
	}
	public function get_drilling_screenings_abnormalities1($data, $date = false, $screening_duration = "Yearly") {
		// $query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data ['label'];
		
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		ini_set ( 'memory_limit', '1G' );
		
		$count = $this->mongo_db->count ( $this->screening_app_col );
		// ////log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		if ($count < 10000) {
			$per_page = $count;
			$loop = 2; // $count / $per_page;
		} else {
			$per_page = 10000;
			$loop = $count / $per_page;
		}
		
		switch ($type) {
			case "Physical Abnormalities" :
				$requests = [ ];
				$request ['label'] = 'Over Weight';
				// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->count($this->screening_app_col);
				
				$merged_array = array (
						"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
								'$in' => array (
										"Over Weight" 
								) 
						) 
				);
				
				$result = [ ];
				for($page = 1; $page < $loop; $page ++) {
					$offset = $per_page * ($page);
					$pipeline = [ 
							array (
									'$project' => array (
											"doc_data.widget_data" => true,
											"history" => true 
									) 
							),
							array (
									'$match' => $merged_array 
							),
							array (
									'$limit' => $offset 
							),
							array (
									'$skip' => $offset - $per_page 
							) 
					];
					$response = $this->mongo_db->command ( array (
							'aggregate' => $this->screening_app_col,
							'pipeline' => $pipeline 
					) );
					$temp_result = [ ];
					foreach ( $response ['result'] as $doc ) {
						
						foreach ( $doc ['history'] as $date ) {
							$time = $date ['time'];
							
							if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
								array_push ( $temp_result, $doc );
								break;
							}
						}
					}
					
					$result = array_merge ( $result, $temp_result );
				}
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				// $query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->count($this->screening_app_col);
				
				$merged_array = array (
						"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
								'$in' => array (
										"Under Weight" 
								) 
						) 
				);
				
				$result = [ ];
				for($page = 1; $page < $loop; $page ++) {
					$offset = $per_page * ($page);
					$pipeline = [ 
							array (
									'$project' => array (
											"doc_data.widget_data" => true,
											"history" => true 
									) 
							),
							array (
									'$match' => $merged_array 
							),
							array (
									'$limit' => $offset 
							),
							array (
									'$skip' => $offset - $per_page 
							) 
					];
					$response = $this->mongo_db->command ( array (
							'aggregate' => $this->screening_app_col,
							'pipeline' => $pipeline 
					) );
					$temp_result = [ ];
					foreach ( $response ['result'] as $doc ) {
						
						foreach ( $doc ['history'] as $date ) {
							$time = $date ['time'];
							
							if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
								array_push ( $temp_result, $doc );
								break;
							}
						}
					}
					
					$result = array_merge ( $result, $temp_result );
				}
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "General Abnormalities" :
				$requests = [ ];
				$request ['label'] = 'General';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array()))->count($this->screening_app_col);
				
				$and_merged_array = array ();
				
				$general_str_empty = array (
						"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
								'$ne' => '' 
						) 
				);
				$general_str_space = array (
						"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
								'$ne' => ' ' 
						) 
				);
				$general_arr = array (
						"doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities" => array (
								'$ne' => array () 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $general_str_empty );
				array_push ( $and_merged_array, $general_str_space );
				array_push ( $and_merged_array, $general_arr );
				array_push ( $and_merged_array, $page4_exists );
				
				$result = [ ];
				for($page = 1; $page < $loop; $page ++) {
					$offset = $per_page * ($page);
					$pipeline = [ 
							array (
									'$match' => array (
											'$and' => $and_merged_array 
									) 
							),
							array (
									'$project' => array (
											"doc_data.widget_data" => true,
											"history" => true 
									) 
							),
							array (
									'$limit' => $offset 
							),
							array (
									'$skip' => $offset - $per_page 
							) 
					];
					$response = $this->mongo_db->command ( array (
							'aggregate' => $this->screening_app_col,
							'pipeline' => $pipeline 
					) );
					$temp_result = [ ];
					foreach ( $response ['result'] as $doc ) {
						
						foreach ( $doc ['history'] as $date ) {
							$time = $date ['time'];
							
							if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
								array_push ( $temp_result, $doc );
								break;
							}
						}
					}
					
					$result = array_merge ( $result, $temp_result );
				}
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4" => array(), "doc_data.widget_data.page4.Doctor Check Up.Ortho" => array()))->count($this->screening_app_col);
				// $request['value'] = $query;
				
				$and_merged_array = array ();
				
				$ortho = array (
						"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
								'$not' => array (
										'$size' => 0 
								) 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up.Ortho" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $ortho );
				array_push ( $and_merged_array, $page4_exists );
				
				$result = [ ];
				for($page = 1; $page < $loop; $page ++) {
					$offset = $per_page * ($page);
					$pipeline = [ 
							array (
									'$match' => array (
											'$and' => $and_merged_array 
									) 
							),
							array (
									'$project' => array (
											"doc_data.widget_data" => true,
											"history" => true 
									) 
							),
							array (
									'$limit' => $offset 
							),
							array (
									'$skip' => $offset - $per_page 
							) 
					];
					
					$response = $this->mongo_db->command ( array (
							'aggregate' => $this->screening_app_col,
							'pipeline' => $pipeline 
					) );
					$temp_result = [ ];
					foreach ( $response ['result'] as $doc ) {
						
						foreach ( $doc ['history'] as $date ) {
							$time = $date ['time'];
							
							if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
								array_push ( $temp_result, $doc );
								break;
							}
						}
					}
					
					$result = array_merge ( $result, $temp_result );
				}
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				// $query = $this->mongo_db->whereNe(array("doc_data.widget_data.page4.Doctor Check Up.Postural" => array(), "doc_data.widget_data.page4" => array()))->count($this->screening_app_col);
				
				$and_merged_array = array ();
				
				$postural = array (
						"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
								'$not' => array (
										'$size' => 0 
								) 
						) 
				);
				
				$page4_exists = array (
						"doc_data.widget_data.page4.Doctor Check Up.Postural" => array (
								'$exists' => true 
						) 
				);
				
				array_push ( $and_merged_array, $postural );
				array_push ( $and_merged_array, $page4_exists );
				
				$result = [ ];
				for($page = 1; $page < $loop; $page ++) {
					$offset = $per_page * ($page);
					$pipeline = [ 
							array (
									'$match' => array (
											'$and' => $and_merged_array 
									) 
							),
							array (
									'$project' => array (
											"doc_data.widget_data" => true,
											"history" => true 
									) 
							),
							array (
									'$limit' => $offset 
							),
							array (
									'$skip' => $offset - $per_page 
							) 
					];
					
					$response = $this->mongo_db->command ( array (
							'aggregate' => $this->screening_app_col,
							'pipeline' => $pipeline 
					) );
					$temp_result = [ ];
					foreach ( $response ['result'] as $doc ) {
						
						foreach ( $doc ['history'] as $date ) {
							$time = $date ['time'];
							
							if (($time <= $dates ['today_date']) && ($time >= $dates ['end_date'])) {
								array_push ( $temp_result, $doc );
								break;
							}
						}
					}
					
					$result = array_merge ( $result, $temp_result );
				}
				
				$request ['value'] = count ( $result );
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Defects at Birth" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$query = $this->mongo_db->whereNe ( array (
						"doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases" => array (),
						"doc_data.widget_data.page5" => array () 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "",
						"doc_data.widget_data.page6.Without Glasses.Left" => "",
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6",
						"doc_data.widget_data.page6" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6",
						"doc_data.widget_data.page6" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => array (
								"Yes" 
						),
						"doc_data.widget_data.page7.Colour Blindness.Left" => array (
								"Yes" 
						) 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail",
						"doc_data.widget_data.page8" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail",
						"doc_data.widget_data.page8" => array () 
				);
				$query = $this->mongo_db->orWhere ( $search )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene';
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->count ( $this->screening_app_col );
				$request ['value'] = $query;
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_districts($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage3_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		switch ($type) {
			case "Over Weight" :
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.District","Nalgonda")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['District'] = "Nalgonda";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				//log_message ( "debug", "iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================" );
				
				// }
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Over Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Under Weight"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["General"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Skin"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Others(Description/Advice)"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Ortho"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Postural"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Defects at Birth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Deficencies"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Childhood Diseases"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Without Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["With Glasses"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Colour Blindness"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Right Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Left Ear"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Speech Screening"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Oral Hygiene - Fair"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Oral Hygiene - Poor"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Carious Teeth"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Flourosis"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Orthodontic Treatment"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_vales'] ["Indication for extraction"] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_districts1($data) {
		// $query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
		$obj_data = json_decode ( $data, true );
		
		$type = $obj_data ['label'];
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				break;
			
			case "Under Weight" :
				
				ini_set ( 'memory_limit', '512M' );
				
				// $query = $this->mongo_db->get($this->screening_app_col);
				// $chk =0;
				// $id=1000;
				// foreach ($query as $doc){
				// if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity'])){
				// $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'] = $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity'];
				// unset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disablity']);
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// }
				
				// $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = 'MBNR_1423101_'.$id;
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// $chk++;
				// $id++;
				
				// }
				// ////log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWRS-CHITKUL,MEDAK")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = "TSWREIS CHITKUL(G),MEDAK";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// ////log_message("debug","iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================");
				
				// }
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_districts_prepare_pie_array ( $query );
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_schools($data, $date = false, $screening_duration = "Yearly") {
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage4_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$dist = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Over Weight"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Under Weight"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["General"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Skin"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
				
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Others(Description/Advice)"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Ortho"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Postural"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Defects at Birth"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Deficencies"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Childhood Diseases"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Without Glasses"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["With Glasses"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Colour Blindness"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Right Ear"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Left Ear"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Speech Screening"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Fair"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Oral Hygiene - Poor"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Carious Teeth"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Flourosis"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Orthodontic Treatment"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] != null)
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage4_pie_vales'] ["Indication for extraction"] [strtolower ( $dist )] );
				}
				
				$request = [ ];
				foreach ( $requests as $doc ) {
					if (isset ( $request [$doc ['label']] )) {
						$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
					} else {
						$request [$doc ['label']] = $doc ['value'];
					}
				}
				
				$final_values = [ ];
				foreach ( $request as $dist => $count ) {
					$result ['label'] = $dist;
					$result ['value'] = $count;
					array_push ( $final_values, $result );
				}
				
				return $final_values;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_schools1($data) {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$dist = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Under Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				// $search = array("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No");
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_schools_prepare_pie_array ( $query, $dist );
				
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_students($data, $date = false, $screening_duration = "Yearly") {
		ini_set ( 'memory_limit', '1G' );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage3_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		
		switch ($type) {
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
				
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"] )) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"]);
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"]);
				}
				
				return $requests;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["General"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["General"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["General"]);
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] );
				}
				
				return $requests;
				break;
			
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"]);
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"]);
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"]);
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"]);
				}
				
				return $requests;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"]);
				}
				
				return $requests;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"]);
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]  != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]);
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"]);
				}
				
				return $requests;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"]);
				}
				
				return $requests;
				break;

			case "Eye Lids" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Eye Lids"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Eye Lids"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Eye Lids"]);
				}
				
				return $requests;
				break;

			case "Conjunctiva" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Conjunctiva"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Conjunctiva"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Conjunctiva"]);
				}
				
				return $requests;
				break;
			case "Cornea" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Cornea"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Cornea"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Cornea"]);
				}
				
				return $requests;
				break;
			case "Pupil" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Pupil"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Pupil"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Pupil"]);
				}
				
				return $requests;
				break;
				
			case "Wearing Spectacles" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Wearing Spectacles"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Wearing Spectacles"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Wearing Spectacles"]);
				}
				
				return $requests;
				break;
				
			case "Subjective Refraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Subjective Refraction"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Subjective Refraction"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Subjective Refraction"]);
				}
				
				return $requests;
				break;					
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"]);
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"] );
				}
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"]))
						$requests = array_merge_recursive ($requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"]))
						$requests = array_merge_recursive ($requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]!= null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]);
				}
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] );
				}
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"] );
				}
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] );
				}
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"] );
				}
				
				return $requests;
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_students1($data) {
		$obj_data = json_decode ( $data, true );
		// //log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
		$type = $obj_data ['0'];
		$school_name = strtolower ( $obj_data ['1'] );
		switch ($type) {
			case "Over Weight" :
				
				$query = $this->mongo_db->select ( array (
						"_id",
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Over Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Under Weight" :
				
				$query = $this->mongo_db->select ( array (
						"doc_data.widget_data" 
				) )->whereIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Under Weight" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "General" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array (
						"Neurologic",
						"H and N",
						"ENT",
						"Lymphatic",
						"Heart",
						"Lungs",
						"Genitalia",
						"Skin" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Ortho" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Ortho", array (
						"Neck",
						"Shoulders",
						"Arms/Hands",
						"Hips",
						"Knees",
						"Feet" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Postural" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page4.Doctor Check Up.Postural", array (
						"No spinal Abnormality",
						"Spinal Abnormality",
						"Mild",
						"Marked",
						"Moderate" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Defects at Birth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array (
						"Neural Tube Defect",
						"Down Syndrome",
						"Cleft Lip and Palate",
						"Talipes Club foot",
						"Developmental Dysplasia of Hip",
						"Congenital Cataract",
						"Congenital Deafness",
						"Congenital Heart Disease",
						"Retinopathy of Prematurity" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Deficencies" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Deficencies", array (
						"Anaemia",
						"Vitamin Deficiency - Bcomplex",
						"Vitamin A Deficiency",
						"Vitamin D Deficiency",
						"SAM/stunting",
						"Goiter",
						"Under Weight",
						"Over Weight",
						"Obese" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Childhood Diseases" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNotIn ( "doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array (
						"Skin Conditions",
						"Otitis Media",
						"Rheumatic Heart Disease",
						"Asthma",
						"Convulsive Disorders",
						"Hypothyroidism",
						"Diabetes",
						"Epilepsy" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Without Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.Without Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.Without Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "With Glasses" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page6.With Glasses.Right" => "",
						"doc_data.widget_data.page6.With Glasses.Left" => "",
						"doc_data.widget_data.page6.With Glasses.Right" => "6/6",
						"doc_data.widget_data.page6.With Glasses.Left" => "6/6" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Colour Blindness" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page7.Colour Blindness.Right" => "No",
						"doc_data.widget_data.page7.Colour Blindness.Left" => "No" 
				);
				$query = $this->mongo_db->whereNe ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Right Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Right" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Left Ear" :
				ini_set ( 'memory_limit', '512M' );
				$search = array (
						"doc_data.widget_data.page8. Auditory Screening.Left" => "Fail" 
				);
				$query = $this->mongo_db->where ( $search )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Speech Screening" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereInAll ( "doc_data.widget_data.page8. Auditory Screening.Speech Screening", array (
						'Delay',
						"Misarticulation",
						"Fluency",
						"Voice" 
				) )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Oral Hygiene" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->whereNe ( "doc_data.widget_data.page9.Dental Check-up.Oral Hygiene", "Good" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Carious Teeth" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Flourosis" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Flourosis", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Orthodontic Treatment" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			case "Indication for extraction" :
				ini_set ( 'memory_limit', '512M' );
				$query = $this->mongo_db->where ( "doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes" )->get ( $this->screening_app_col );
				
				return $this->get_drilling_screenings_students_prepare_pie_array ( $query, $school_name );
				
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_screenings_districts_prepare_pie_array($query) {
		$requests = [ ];
		
		$dist_list = $this->get_all_district ();
		
		$dist_arr = [ ];
		foreach ( $dist_list as $dist ) {
			array_push ( $dist_arr, $dist ['dt_name'] );
		}
		
		foreach ( $dist_arr as $districts ) {
			$request ['label'] = $districts;
			$count = 0;
			if ($query) {
				foreach ( $query as $dist ) {
					if (isset ( $dist ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] )) {
						if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == strtolower ( $districts )) {
							$count ++;
						}
					}
				}
			}
			$request ['value'] = $count;
			array_push ( $requests, $request );
		}
		
		return $requests;
	}
	public function get_drilling_screenings_schools_prepare_pie_array($query, $dist) {
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['District'] ) == $dist) {
						array_push ( $search_result, $doc );
					}
				}
			}
			$request = [ ];
			foreach ( $search_result as $doc ) {
				if (isset ( $request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] )) {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] ++;
				} else {
					$request [$doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name']] = 1;
				}
			}
			
			//log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $request, true ) );
			$final_values = [ ];
			foreach ( $request as $school => $count ) {
				//log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $school, true ) );
				//log_message ( "debug", "ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc" . print_r ( $count, true ) );
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			//log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $final_values, true ) );
			
			return $final_values;
		}
	}
	public function get_drilling_screenings_students_prepare_pie_array($query, $school_name) {
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				if (isset ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] )) {
					if (strtolower ( $doc ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'] ) == $school_name) {
						array_push ( $search_result, $doc ['_id']->{'$id'} );
					}
				}
			}
			
			return $search_result;
		}
	}
	public function get_drilling_screenings_students_docs($_id_array) {
		$docs = [ ];
		ini_set ( 'memory_limit', '10G' );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select(array("doc_data.widget_data"))->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			
			if (isset ( $query [0] ))
				array_push ( $docs, $query [0] );
		}
		return $docs;
	}
	public function drill_down_screening_to_students_load_ehr_doc($_id) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( $this->request_app_col );
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			return $result;
		}
	}
	public function get_students_load_ehr_doc_model($_id) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
		if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( 'healthcare2016531124515424_static_html' );
			
			$bmi_value_table_addto_ehr = $this->mongo_db->where("doc_data.widget_data.page1.Student Details.Hospital Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'])->get('healthcare2017617145744625');

			$hb_value_table_addto_ehr = $this->mongo_db->where("doc_data.widget_data.page1.Student Details.Hospital Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'])->get($this->hb_app_col);
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['BMI_report'] = $bmi_value_table_addto_ehr;
			$result ['hb_report'] = $hb_value_table_addto_ehr;
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['BMI_report'] = false;
			$result ['hb_report'] = false;
			
			return $result;
		}
	}
	public function drill_down_screening_to_students_doc($_id) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
		if ($query) {
			
			return $query;
		} else {
			
			return false;
		}
	}
	
	// *************************************************
	
	/**
	 * Helper: Prepares IP address string for database insertion.
	 *
	 * @return string
	 */
	protected function _prepare_ip($ip_address) {
		return $ip_address;
	}
	public function user_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function cc_user_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function school_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function doctor_exists($email = FALSE) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( array (
				'email' => $email 
		) )->get ( $this->collections ['panacea_doctors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if ($query !== array ()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Sets an error message
	 */
	public function set_error($error) {
		$this->errors [] = $error;
		return $error;
	}
	
	/**
	 * Applies delimiters and returns themed errors
	 */
	public function errors() {
		$_output = '';
		foreach ( $this->errors as $error ) {
			$error_lang = $this->lang->line ( $error ) ? $this->lang->line ( $error ) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $error_lang . $this->error_end_delimiter;
		}
		
		return $_output;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Return errors as an array, langified or not
	 */
	public function errors_array($langify = TRUE) {
		if ($langify) {
			$_output = array ();
			foreach ( $this->errors as $error ) {
				$errorLang = $this->lang->line ( $error ) ? $this->lang->line ( $error ) : '##' . $error . '##';
				$_output [] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		} else {
			return $this->errors;
		}
	}
	
	/**
	 * Generates a random salt value.
	 */
	public function salt() {
		return substr ( md5 ( uniqid ( rand (), true ) ), 0, $this->salt_length );
	}
	
	/**
	 * Hashes the password to be stored in the database.
	 */
	public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE) {
		if (empty ( $password )) {
			return FALSE;
		}
		
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			return $this->bcrypt->hash ( $password );
		}
		
		if ($this->store_salt && $salt) {
			return sha1 ( $password . $salt );
		} else {
			$salt = $this->salt ();
			return $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	}
	public function get_schools_by_dist_id($dist_id) {
		if ($dist_id == "All") {
			// ini_set ( 'memory_limit', '1G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->get ( $this->screening_app_col );
			
			ini_set ( 'memory_limit', '1G' );
			$count = $this->mongo_db->count ( $this->screening_app_col );
			if ($count < 10000) {
				$per_page = $count;
				$loop = 2; // $count / $per_page;
			} else {
				$per_page = 10000;
				$loop = $count / $per_page;
			}
			
			$result = [ ];
			for($page = 1; $page < $loop; $page ++) {
				$offset = $per_page * ($page);
				$pipeline = [ 
						array (
								'$project' => array (
										"doc_data.widget_data.page1" => true,
										"doc_data.widget_data.page2" => true 
								) 
						),
						array (
								'$limit' => $offset 
						),
						array (
								'$skip' => $offset - $per_page 
						) 
				];
				$response = $this->mongo_db->command ( array (
						'aggregate' => $this->screening_app_col,
						'pipeline' => $pipeline 
				) );
				$result = array_merge ( $result, $response ['result'] );
			}
			return $result;
		} else {
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$query = $this->mongo_db->select ( array (
					'school_name',
					'school_code',
					'school_mob',
					'contact_person_name' 
			) )->orderBy ( array (
					'school_name' => 1 
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['panacea_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}
	}
	public function get_students_by_school_name($school_name, $dist_name) {
		if ($school_name == "All") {
			// //log_message("debug","111111111111111111111111111111111111111".print_r(strtoupper($dist_name),true));
			// ini_set ( 'memory_limit', '1G' );
			// $query = $this->mongo_db->select ( array ( 'doc_data.widget_data.page1', 'doc_data.widget_data.page2' ) )->orderBy(array('Hospital Unique ID' => 1))->where ( "doc_data.widget_data.page2.Personal Information.District", strtoupper($dist_name) )->get ( $this->screening_app_col );
			ini_set ( 'memory_limit', '1G' );
			$count = $this->mongo_db->count ( $this->screening_app_col );
			if ($count < 10000) {
				$per_page = $count;
				$loop = 2; // $count / $per_page;
			} else {
				$per_page = 10000;
				$loop = $count / $per_page;
			}
			
			$merged_array = array (
					"doc_data.widget_data.page2.Personal Information.District" => strtoupper ( $dist_name ) 
			);
			
			$result = [ ];
			for($page = 1; $page < $loop; $page ++) {
				$offset = $per_page * ($page);
				$pipeline = [ 
						array (
								'$project' => array (
										"doc_data.widget_data.page1" => true,
										"doc_data.widget_data.page2" => true 
								) 
						),
						array (
								'$match' => $merged_array 
						),
						array (
								'$limit' => $offset 
						),
						array (
								'$skip' => $offset - $per_page 
						) 
				];
				$response = $this->mongo_db->command ( array (
						'aggregate' => $this->screening_app_col,
						'pipeline' => $pipeline 
				) );
				
				$result = array_merge ( $result, $response ['result'] );
			}
			
			return $result;
		} else {
			ini_set ( 'max_execution_time', 0 );
			//log_message ( "debug", "22222222222222222222222222222222222222" . print_r ( $school_name, true ) );
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->orderBy ( array (
					'Hospital Unique ID' => 1 
			) )->where ( 'doc_data.widget_data.page2.Personal Information.School Name', $school_name )->get ( $this->screening_app_col );
			return $query;
		}
	}
	public function get_reported_schools_count_by_dist_name($dist_id, $date) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.last_stage.time', $date )->get ( $this->absent_app_col );
		
		$count = 0;
		$r2h = 0;
		$sick = 0;
		foreach ( $query as $report ) {
			if (strtolower ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['District'] ) == strtolower ( $dist_id )) {
				$count ++;
				$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['Sick'] );
				$sick = $sick + intval ( $report ['doc_data'] ['widget_data'] ['page2'] ['Attendence Details'] ['RestRoom'] );
				
				$r2h = $r2h + intval ( $report ['doc_data'] ['widget_data'] ['page1'] ['Attendence Details'] ['R2H'] );
			}
		}
		$data ['count'] = $count;
		$data ['sick'] = $sick;
		$data ['r2h'] = $r2h;
		
		return $data;
	}
	public function get_health_supervisors_school_id($id) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->where ( 'school_code', (int)$id )->select ( array (
				'hs_name','hs_mob' ) )->get ($this->collections ['panacea_health_supervisors']);
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	public function get_absent_school_details($school) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( "doc_data.widget_data.page1.Attendence Details.Select School", $school )->get ( $this->absent_app_col );
		if ($query) {
			return $query [0];
		} else {
			return false;
		}
	}
	public function get_student_count_school_name($school) {
		$count = $this->mongo_db->where ( 'doc_data.widget_data.page2.Personal Information.School Name', $school )->count ( $this->screening_app_col );
		return $count;
	}
	public function get_request_by_school_name($school_name, $date) {
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data" 
		) )->whereLike ( 'history.0.time', $date )->get ( $this->request_app_col );
		
		$request = array ();
		foreach ( $query as $identifiers ) {
			$unique_id = $identifiers ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$doc = $this->mongo_db->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			if (isset ( $doc ) && ! empty ( $doc ) && (count ( $doc ) > 0)) {
				$doc_school = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['School Name'];
				if (strtolower ( $doc_school ) == strtolower ( $school_name )) {
					$req ['request'] = $identifiers;
					$req ['stud_details'] ['name'] = $doc [0] ['doc_data'] ['widget_data'] ['page1'] ['Personal Information'] ['Name'];
					$req ['stud_details'] ['class'] = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['Class'];
					$req ['stud_details'] ['section'] = $doc [0] ['doc_data'] ['widget_data'] ['page2'] ['Personal Information'] ['Section'];
					array_push ( $request, $req );
				}
			}
		}
		return $request;
	}
	public function get_reports_by_dist_name($dist_name) {
		$query = $this->mongo_db->select ( array (
				'school_name',
				'school_code',
				'school_mob',
				'contact_person_name' 
		) )->orderBy ( array (
				'school_name' => 1 
		) )->where ( 'dt_name', $dist_id )->get ( $this->request_app_col );
		return $query;
	}
	public function get_school_data_school_name($school_name) {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->select ( array (
				'school_name',
				'school_code',
				'school_mob',
				'contact_person_name' 
		) )->orderBy ( array (
				'school_name' => 1 
		) )->where ( 'school_name', $school_name )->get ( $this->collections ['panacea_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_screening_pie_stage5($dates) {
		ini_set ( 'memory_limit', '10G' );
		$pie_stage5 = $this->mongo_db->select ( array (
				'pie_data.stage5_pie_vales' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		return $pie_stage5;
	}
	public function get_screening_pie_stage4($dates) {
		$pie_stage4 = $this->mongo_db->select ( array (
				'pie_data.stage4_pie_vales'
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		return $pie_stage4;
	}
	public function screening_pie_data_for_stage4_new($requests) {
		$school_list = $this->get_all_schools ();
		$school_in_dist = [ ];
		
		foreach ( $school_list as $school ) {
			$school_in_dist [strtolower ( $school ['school_name'] )] = strtolower ( $school ['dt_name'] );
		}
		$request_stage4 = [ ];
		
		foreach ( $requests as $screening_index => $screening_array ) {
			$request_stage4 [$screening_index] = [ ];
			// //log_message("debug","in 11111111111111111111111111111111111111111111111111111111=======".print_r($request_stage4,true));
			foreach ( $screening_array as $school_name => $inner_data ) {
				// //log_message("debug","in 222222222222222222222222222222222222222222222222222222=======".print_r($request_stage4,true));
				if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
					$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = null;
				}
				
				$school_data = [ ];
				if (count ( $inner_data ) > 0) {
					if (! isset ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]] )) {
						$request_stage4 [$screening_index] [$school_in_dist [$school_name]] = [ ];
					}
					$school_data ['label'] = strtoupper ( $school_name );
					$school_data ['value'] = count ( $inner_data );
					array_push ( $request_stage4 [$screening_index] [$school_in_dist [$school_name]], $school_data );
					// //log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($request_stage4,true));
				}
			}
		}
		return $request_stage4;
	}
	public function screening_pie_data_for_stage3_new($requests) {
		$request_stage3 = [ ];
		foreach ( $requests as $request => $request_data ) {
			$request_stage3 [$request] = [ ];
			foreach ( $request_data as $dist_name => $dist_array ) {
				$dist_data ['label'] = strtoupper ( $dist_name );
				if (is_array ( $dist_array )) {
					$value_count = 0;
					foreach ( $dist_array as $school_array ) {
						$value_count = $value_count + $school_array ['value'];
					}
					$dist_data ['value'] = $value_count;
				} else {
					$dist_data ['value'] = count ( $dist_array );
				}
				
				array_push ( $request_stage3 [$request], $dist_data );
			}
		}
		
		return $request_stage3;
	}
	public function screening_pie_data_for_stage2_old($requests) {
		$request_stage2 = [ ];
		
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Over Weight"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Over Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Under Weight"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Physical Abnormalities"] ["label"] = "Under Weight";
		$stage_array ["Physical Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["General"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "General";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Skin"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Skin";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// ===
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Others(Description/Advice)"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Others(Description/Advice)";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// =====
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Ortho"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Ortho";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Postural"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Postural";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Defects at Birth"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Defects at Birth";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Deficencies"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Deficencies";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["General Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Childhood Diseases"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["General Abnormalities"] ["label"] = "Childhood Diseases";
		$stage_array ["General Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Without Glasses"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Without Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["With Glasses"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "With Glasses";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Eye Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Colour Blindness"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Eye Abnormalities"] ["label"] = "Colour Blindness";
		$stage_array ["Eye Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Right Ear"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Right Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Left Ear"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Left Ear";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Auditory Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Speech Screening";
		
		$request = [ ];
		foreach ( $requests ["Speech Screening"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Auditory Abnormalities"] ["label"] = "Speech Screening";
		$stage_array ["Auditory Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Oral Hygiene - Fair"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Fair";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Oral Hygiene - Poor"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Oral Hygiene - Poor";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Carious Teeth"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Carious Teeth";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$stage2_data = [ ];
		$stage2_data ["label"] = "Flourosis";
		
		$request = [ ];
		foreach ( $requests ["Flourosis"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Flourosis";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Orthodontic Treatment"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Orthodontic Treatment";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		// =====
		$stage_array = [ ];
		$stage_array ["Dental Abnormalities"] = [ ];
		
		$request = [ ];
		foreach ( $requests ["Indication for extraction"] as $doc ) {
			if (isset ( $request [$doc ['label']] )) {
				$request [$doc ['label']] = $request [$doc ['label']] + $doc ['value'];
			} else {
				$request [$doc ['label']] = $doc ['value'];
			}
		}
		$total_count = 0;
		foreach ( $request as $dist => $count ) {
			$total_count = $total_count + $count;
		}
		$stage_array ["Dental Abnormalities"] ["label"] = "Indication for extraction";
		$stage_array ["Dental Abnormalities"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		// ===
		
		return $request_stage2;
	}
	public function screening_pie_data_for_stage1_new($requests) {
		$request_stage1 = [ ];
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests [0] ["Physical Abnormalities"] ['value'] + $requests [1] ["Physical Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [2] ["General Abnormalities"] ['value'] + $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [10] ["Eye Abnormalities"] ['value'] + $requests [11] ["Eye Abnormalities"] ['value'] + $requests [12] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [13] ["Auditory Abnormalities"] ['value'] + $requests [14] ["Auditory Abnormalities"] ['value'] + $requests [15] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [16] ["Dental Abnormalities"] ['value'] + $requests [17] ["Dental Abnormalities"] ['value'] + $requests [18] ["Dental Abnormalities"] ['value'] + $requests [19] ["Dental Abnormalities"] ['value'] + $requests [20] ["Dental Abnormalities"] ['value'] + $requests [21] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		return $request_stage1;
	}
	
	public function initiate_request($doc_id, $user_coll_data, $app_coll_data) {
		$user_collection = $doc_id . "_docs";
		$this->mongo_db->insert ( $user_collection, $user_coll_data );
		$this->mongo_db->insert ( $this->request_app_col, $app_coll_data );
		$this->mongo_db->insert ( $this->request_app_col . "_shadow", $app_coll_data );
		$this->mongo_db->insertStut ( 'status', $this->request_app_col, $app_coll_data ["doc_properties"] ['doc_id'], $app_coll_data ["app_properties"] ['app_name'] );
	}
	
	public function messaging($message)
	{
		$data = $this->session->userdata("customer");
		
		$data = array(
			"message" => $message,
			"unique_id" => $uid,
		);
	
		$response = $this->data = $this->panacea_common_model->messaging($message);
		//$this->data = "";
	
		$this->output->set_output($response);
	}
	public function groupscount() {
		$count = $this->mongo_db->count ( 'panacea_chat_groups' );
		return $count;
	}
	public function get_groups($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'panacea_chat_groups' );
		return $query;
	}
	
	public function get_all_groups() {
		$query = $this->mongo_db->get ( 'panacea_chat_groups' );
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Get accessible chat rooms ( for the loggedin user )
	 * 
	 * @param  array  $user_email  Loggedin user email
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	public function get_accessible_groups($user_email) {
		$accessible_chat_rooms = array();
		$query = $this->mongo_db->get ( 'panacea_chat_groups' );
		//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13631====='.print_r($query,true));
		foreach($query as $data)
		{
			$group_name = $data['group_name'];
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13635====='.print_r($group_name,true));
			$where_array = array('group_name'=>$group_name,'list_of_users'=>array('$in'=>array($user_email)));
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13637====='.print_r($where_array,true));
			$grps = $this->mongo_db->where($where_array)->get ( 'panacea_chat_groups_users' );
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13639====='.print_r($grps,true));
			//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13640====='.print_r(count($grps),true));
			if(isset($grps) && !empty($grps))
			{
				array_push($accessible_chat_rooms,$query);
			}
			
		}
		//log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13644====='.print_r($accessible_chat_rooms,true));
		return $accessible_chat_rooms;
	}
	
	public function get_all_admin_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_admins'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_health_supervisors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_cc_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['panacea_cc'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_superiors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['superiors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper: Add message
	 * 
	 * @param  array  $post          $_POST data
	 * @param  array  $chat_room_id  chat Room ID
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	public function add_message($post,$chat_room_id)
	{
		$data = array(
				"message_id"   => get_unique_id(),
				"user_id"      => $post['user_id'],
				"user_name"    => $post['username'],
				"chat_room_id" => $chat_room_id,
				"message" 	   => $post['message'],
				"created_at"   => date("Y-m-d H:i:s")
		);
		$query = $this->mongo_db->insert($this->collections['panacea_messages'],$data);
		
		if($query){
			$response['error'] = false;			
			$response['message'] = $data;
		}else{
			$response['error'] = true;
			$response['message'] = 'Failed send message ' . $stmt->error;
		}
		
		return $response;
	}
	public function get_messages($msg_id)
	{
		$query = $this->mongo_db->where("chat_room_id",$msg_id)->get($this->collections['panacea_messages']);
		return $query;
	}
	public function get_user_by_email($name, $email){
				
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		//log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=========.'.print_r($email,true));
		$user = $this->mongo_db->where("email",$email)->get($this->collections['panacea_admins']);
		//log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu=========.'.print_r($user,true));
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		
		if($user){
			$temp["user_id"] = $user[0]['email'];
			$temp["name"] = $user[0]['username'];
			$temp["email"] = $user[0]['email'];
			$temp["created_at"] = $user[0]['registered_on'];
			
			// User with same email already existed in the db
			$response["error"] = false;
			$response["user"] = $temp;			
		}else{
			// Failed to create user
			$response["error"] = true;
			$response["message"] = "User not found.";
		}
		return $response;		
	}
	public function updateGcmID($login, $gcm_registration_id){
		$id_exists = $this->mongo_db->where("user_id",$login)->get($this->collections['panacea_users_gcm']);
		$data = array(
			"user_id" => $login,
			"gcm_registration_id" => $gcm_registration_id
		);
		if($id_exists){
			$query = $this->mongo_db->where("user_id",$login)->set($data)->get($this->collections['panacea_users_gcm']);
		}else{
			$query = $this->mongo_db->insert($this->collections['panacea_users_gcm'],$data);
		}
		return $query;
	}
	
	public function get_sanitation_report_app()
	{
	  $query = $this->mongo_db->select(array('app_template'))->where('_id','healthcare2016111212310531')->get($this->collections['records']);
	  return $query[0]['app_template'];
	
	}
	
	// ------------------------------------------------------------------------------------------------------------

	/**
	 * Helper: Get data to draw sanitation report pie based on the selected criteria ( Model )
	 *
	 * @param  string  $date              Selected date 
	 * @param  string  $search_criteria   Criteria question for sanitation report
	 * @param  string  $opt   			  Criteria option for sanitation report
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */
	 
	public function get_sanitation_report_pie_data($date, $search_criteria, $opt) {
	
	 $output 			= array();
	 $sanitation_report = array();
	 $sanitation_report['district_list'] = array();
	 $sanitation_report['schools_list']  = array();
	 
	 $query = $this->mongo_db->select(array('doc_data.widget_data'),array())->whereLike('doc_data.widget_data.page4.Declaration Information.Date:',$date)->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$date,$search_criteria=>$opt))->get('healthcare2016111212310531');
	 
	 $dist_list = $this->get_all_district ();
		
	 $dist_arr = [ ];
	 foreach ( $dist_list as $dist ) {
		array_push ( $dist_arr, $dist ['dt_name'] );
	 }
	
	foreach ( $dist_arr as $district_name ) {
	    $schools = array();
	    $sanitation_report['schools_list'][$district_name]  = array();
		$request ['label'] = $district_name;
		$count = 0;
		if ($query) {
			foreach ( $query as $dist ) {
				if (isset ( $dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] )) {
					if (strtolower ( $dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['District'] ) == strtolower ( $district_name )) {
					    if(!in_array($dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name'],$schools))
						{
							$count ++;
							array_push($schools,$dist ['doc_data'] ['widget_data'] ['page4'] ['School Information'] ['School Name']);
						}
					}
				}
			}
		}
		$request ['value'] = $count;
		array_push ( $output, $request );
		$sanitation_report['schools_list'][$district_name] = $schools;
	}
	
	$sanitation_report['district_list'] = $output;
		
	 if($sanitation_report)
	     return $sanitation_report;
	 else
		 return false;
		
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the new documents from user collection
	 *
	 * @author Selva 
	 *
	 * @param string user collection
	 *
	 *
	 * @return array
	 */

	public function get_hs_req_docs($usercollection)
	{
		//echo print_r($usercollection,true);
		//exit();
		
		/* $this->mongo_db->orderBy(array('doc_received_time' => -1));
	  	$query=$this->mongo_db->select(array(),array('_id'))->where(array('status'=>'new','app_id'=>'healthcare2016531124515424'))->get($usercollection);
		return $query; */ 
		
		$email = str_replace(".", "_", $usercollection);
		$unique_id = explode("hs#", $email);
		$upper_uniqueid = strtoupper($unique_id[0]);
		
		$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  	$query=$this->mongo_db->select(array(),array('_id'))->where(array('status'=>'new','app_id'=>'healthcare2016531124515424'))->whereLike('notification_param.Unique ID', $upper_uniqueid)->get($usercollection);
	  	
		return $query; 
	}
	
	public function get_school_details_for_school_code($school_code)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$school_data = $this->mongo_db->where(array('school_code'=>$school_code))->select(array('school_name','dt_name'),array())->get('panacea_schools');
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		//echo print_r($school_data,true);
		//echo print_r($school_code,true);
		foreach ( $school_data as $schools => $school ) 
		{
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $school ['dt_name'] )) {
				$school_data [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
				
			} else {
				$school_data [$schools] ['dt_name'] = "No district selected";
			}
		}
		
		return $school_data[0];
	}
	
	// SANITATION INFRASTRUCTURE
	public function get_sanitation_infrastructure_model($district_name,$school_name)
	{
		$this->mongo_db->limit(1)->where(array('doc_data.widget_data.page6.School Information.District'=>$district_name,'doc_data.widget_data.page6.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_infra_app_col);
		if($query)
			return $query;
		else
			return FALSE;
	}
	
	// SANITATION REPORT 
	public function get_sanitation_report_data_with_date($date,$school_name)
	{
	    if ($date) {
			$selected_date = $date;
		} else {
			$selected_date = $this->today_date;
		}
		
		$this->mongo_db->whereLike('doc_data.widget_data.page4.Declaration Information.Date:',$selected_date)->where(array('doc_data.widget_data.page4.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_report_app_col);
		if($query)
			return $query;
		else
			return FALSE;
	}

	public function get_sanitation_report_data_with_date_version_2($date,$school_name)
	{
	    if ($date) {
			$selected_date = $date;
		} else {
			$selected_date = $this->today_date;
		}
		
		$this->mongo_db->whereLike('doc_data.widget_data.page4.Declaration Information.Date:',$selected_date)->where(array('doc_data.widget_data.page4.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get('healthcare2016111212310531_version_2');
		if($query)
			return $query;
		else
			return FALSE;
	}
	
	public function get_student($per_page,$page,$school_name)
	{
		$offset = $per_page * ($page - 1);

		$query = $this->mongo_db->limit ($per_page)->offset( $offset)->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->get($this->screening_app_col); 
		
		return $query;
	}
	
	public function get_students_by_class($class, $school_name)
	{		
		
		if($class == "All")
		{
			$query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->get('tswreis_screening_report_col_2020-2021');
			return $query;
		}
		
		else
		{ 
		
		   $query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name,"doc_data.widget_data.page2.Personal Information.Class"=>$class))->get('tswreis_screening_report_col_2020-2021');
		   return $query;
		 } 
	}
	
	public function studentscount($school_name) 
	{
		$count = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->count($this->screening_app_col_2020_21);
		return $count;
	}
	
	public function get_district($district_id)
	{
		$qry = $this->mongo_db->where('_id', new MongoId($district_id))->get('panacea_district');
		if($qry)
		{
			return $qry;
		}
		else
		{
			return false;
		}
	}
	
	public function get_all_classes($cls_name = "All",$school_code) 
	{
		if ($cls_name == "All") 
		{
			$query = $this->mongo_db->orderBy ( array (
					'class_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tswreis_classes']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('class_name', $cls_name )->orderBy ( array (
					'class_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tswreis_classes']);
		}
		
		return $query;
	}
	
	public function get_all_sections($section_name = "All",$school_code) 
	{
		if ($section_name == "All") 
		{
			$query = $this->mongo_db->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tswreis_sections']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('section_name', $section_name )->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tswreis_sections']);
		}
		
		return $query;
	}

	public function generate_new_uniqueid_for_new_student_by_checking_all_collection($school_code,$district_code,$school_name)
	{
		$all_list = array();
		$unique_ids_list = array();

		$all_uniqueID = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => 1))->get('tswreis_screening_report_col_2021-2022');
		if(!empty($all_uniqueID)){
			foreach ($all_uniqueID as $data1) {
				$uniques1 = $data1['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
				array_push($all_list, $uniques1);
			}
		}

		$all_uniqueID_frm_pasout = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => 1))->get('screening_report_col_2021-2022_passed_out');
		if(!empty($all_uniqueID_frm_pasout)){
			foreach ($all_uniqueID_frm_pasout as $data1) {
				$uniques2 = $data1['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
				if(!in_array($uniques2, $all_list)){
					array_push($all_list, $uniques2);
				}
			}
		}

		$all_uniqueID_frm_otherscls = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => 1))->get('other_classes_screening_data_2020-2021');
		if(!empty($all_uniqueID_frm_otherscls)){
			foreach ($all_uniqueID_frm_otherscls as $data1) {
				$uniques3 = $data1['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
				if(!in_array($uniques3, $all_list)){
					array_push($all_list, $uniques3);
				}
			}
		}

		/*
			Above we have taken ids from all the collections for one school

			increase last id by ordering array in ascending order,  then last id will come
		*/
		sort($all_list);
		$value = end($all_list);
		$take_value_frm_id = explode('_', $value);
		$last_val_frm_id = $take_value_frm_id[2];
		$increase_value = $last_val_frm_id+1;
		$new_id = $district_code."_".$school_code."_".$increase_value;
		return $new_id;
	}
	
	public function generate_new_student_hunique_id($school_code, $dist_code, $school_name)
	{
		 
	  $uniqueidlist = array();
	  /*$staff_unique_id = array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => array('$regex' => '/STAFF/i'));
	  ->whereNe($staff_unique_id)*/
	
	  $all_uniqueID = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->orderBy(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => 1))->get($this->screening_app_col);
		
	  if(!empty($all_uniqueID))
	  {
		$id_array = array();
		//$last_uniqueID =  $all_uniqueID[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];

		foreach ($all_uniqueID as $uID)
		{
			
		    $id = $uID['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
			 if(preg_match('/STAFF/i', $id))
			{
				//log_message('error',"last_uniqueID for========12282".print_r($last_uniqueID,true));
			}else
			{

			$id_array = explode("_",$id);
			//log_message('error',"id_array for========12282".print_r($id_array,true));
			if(isset($id_array[2]))
			{	
			   $num_length = strlen((string)$id_array[2]);

			   if($num_length >= 4)
			   {
			   		$last_id = $id_array[2];
			   		array_push($uniqueidlist,$id_array[2]);	
			   }			   
			}
			}
		
		}
		
		if(empty($uniqueidlist))
		{
			$ip_address = $this->session->userdata('ip_address');
			//log_message('error',"ip_address for generate unique_id========12282".print_r($ip_address,true));
			$session_details = $this->session->userdata('customer');
			$email = $session_details['email'];
			//log_message('error',"email for generate unique_id========12280".print_r($email,true));
		}
		//log_message('error',"last_uniqueID for========12282".print_r($uniqueidlist,true));
		$maxID     = max($uniqueidlist);
		$uid       = intval($last_id);
		//$uid       = intval($id_array[2]);
		
		$inc       = $uid+1;
		
		if(isset($id_array[1]))
		{
		  $unique_id = $id_array[0]."_".$id_array[1]."_".$inc;
		}
		else
		{
		  $unique_id = $dist_code."_".$school_code."_".$inc;
		}
	 }
	 else
	 {
		$unique_id = $dist_code."_".$school_code."_1000";
	 }
	
	 return $unique_id;
	
	}
	
	public function generate_new_staff_hunique_id($school_code, $dist_code, $school_name)
	{
	  $uniqueidlist = array();
	
	  $all_uniqueID = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->get($this->screening_staff_app_col);
		
	  if(!empty($all_uniqueID))
	  {
		$id_array = array();
		foreach ($all_uniqueID as $uID)
		{
			
		    $id = $uID['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
			$id_array = explode("_",$id);
			if(isset($id_array[2]))
			{
			   array_push($uniqueidlist,$id_array[2]);
			}
		}
		
		$maxID     = max($uniqueidlist);
		$uid       = intval($maxID);
		$inc       = $uid+1;
		if(isset($id_array[1]))
		{
		  $unique_id = $id_array[0]."_".$id_array[1]."_STAFF".$inc;
		}
		else
		{
		  $unique_id = $dist_code."_".$school_code."_STAFF".$inc;
		}
	 }
	 else
	 {
		$unique_id = $dist_code."_".$school_code."_STAFF001";
	 }
	
	 return $unique_id;
	
	}
	
	public function add_student_ehr_model($doc_data,$history,$doc_properties)
	{
	  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history,"doc_properties"=>$doc_properties);
	  $query = $this->mongo_db->insert($this->screening_app_col_2021_22,$doc_data);
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}
	
	public function add_staff_ehr_model($doc_data,$history)
	{
	  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->insert($this->screening_staff_app_col,$doc_data);
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}
	
	public function create_class($post, $school_code)
    {
    	$data = array(
    			"class_name" => $post['class_name'],
				"school_code" => $school_code
				);
    	$query = $this->mongo_db->insert($this->collections['tswreis_classes'],$data);
    	return $query;
    }
	
	public function delete_class($class_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($class_id)))->delete($this->collections['tswreis_classes']);
		return $query;
    }
    
    public function create_section($post, $school_code)
    {
    	$data = array(
    			"section_name" => $post['section_name'],
				"school_code"  => $school_code
				);
    	$query = $this->mongo_db->insert($this->collections['tswreis_sections'],$data);
    	return $query;
    }
    
    public function delete_section($section_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($section_id)))->delete($this->collections['tswreis_sections']);
    	return $query;
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	public function change_password($identity, $old, $new)
	{
	$this->mongo_db->switchDatabase($this->common_db['common_db']);
   
	$docs = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where("email", $identity)
			->limit(1)
			->get($this->collections['panacea_health_supervisors']);

		if (count($docs) !== 1)
		{
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			// Store the new password and reset the remember code so all remembered instances have to re-login
			
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			
			$updated = $this->mongo_db
				->where("email", $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['panacea_health_supervisors']);
				
            $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}
		
		return FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Takes a password and validates it against an entry in the collection.
	 */
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
		->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['panacea_health_supervisors']);
		
		$hash_password_db = (object) $document[0];

		if (count($document) !== 1)
		{
			return FALSE;
		}

		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return ($db_password == $hash_password_db->password);
	}

	
	public function tswreis_chronic_cases_count($school_name)
    {
     $query = $this->mongo_db->getWhere($this->collections['tswreis_chronic_cases'],array('school_name'=>$school_name));
	 return count($query);
    }
	
	function get_chronic_cases_model($limit, $page, $school_name)
	{
	    $offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->getWhere($this->collections['tswreis_chronic_cases'],array('school_name'=>$school_name));
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_chronic_cases_model_for_data_table($school_name)
	{
	    $this->mongo_db->orderBy(array('created_time' => -1));
		$query = $this->mongo_db->getWhere($this->collections['tswreis_chronic_cases'],array('school_name'=>$school_name));
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_all_chronic_unique_ids_model($school_name)
	{
	    $this->mongo_db->orderBy(array('_id' => 1));
		$query = $this->mongo_db->select(array('student_unique_id','case_id','scheduled_months'),array())->getWhere($this->collections['tswreis_chronic_cases'],array('school_name'=>$school_name,'followup_scheduled'=>'true'));
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	function create_schedule_followup_model($unique_id,$medication_schedule,$treatment_period,$start_date,$monthNames,$case_id)
	{
	   $update_array = array(
	   'start_date'         => $start_date,
	   'medication_schedule'=> $medication_schedule,
	   'treatment_period'   => $treatment_period,
	   'scheduled_months'   => $monthNames,
	   'followup_scheduled' => "true");
	   
	   $updated = $this->mongo_db->where(array('student_unique_id'=>$unique_id,'case_id'=>$case_id))->set($update_array)->update($this->collections['tswreis_chronic_cases']);
	   
	   if($updated)
		   return TRUE;
	   else
		   return FALSE;
	}
	
	function calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken)
	{
	   $medication_schedule = array();
	   $query = $this->mongo_db->select(array(),array())->getWhere($this->collections['tswreis_chronic_cases'],array('student_unique_id'=>$unique_id,'case_id'=>$case_id));  
	   
	   foreach($query as $value)
	   {
	     $medication_schedule = $value['medication_schedule'];
	   }
	   
	   $schedule_count = count($medication_schedule);
	   
	   if(isset($medication_taken) && !empty($medication_taken))
	   {
	     $taken_count = count($medication_taken);
	   }
	   else
	   {
         $taken_count = 0;
	   }
	   $compliance_percentage = ($taken_count/$schedule_count)*100;
	   return $compliance_percentage;
	}
	
	function update_schedule_followup_model($unique_id,$case_id,$compliance,$selected_date)
	{
	   $check_query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id,"medication_taken"=>array('$elemMatch'=>array("date"=>$selected_date)));
		 
		$is_already_updated = $this->mongo_db->where($check_query)->get($this->collections['tswreis_chronic_cases']);
		
		if($is_already_updated)
		{
	       return "ALREADY_UPDATED";
		}
		else
		{
	       $datewise_update = array("date"=>$selected_date,"compliance"=>$compliance);
	  
		   $query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id);
		
		   $update = array('$push'=>array("medication_taken"=>$datewise_update));
			 
		   $response = $this->mongo_db->command(array( 
			'findAndModify' => $this->collections['tswreis_chronic_cases'],
			'query'         => $query,
			'update'        => $update,
			'upsert'        => true
			));
		
			if($response['ok'])
			{
			   return "UPDATE_SUCCESS";
			}
			else
			{
		       return "UPDATE_FAIL";
			}
		}
		
		
		/*$datewise_update = array("date"=>$selected_date,"taken_slots"=>$medication_taken);
	  
		$query = array("student_unique_id"=>$unique_id);
		
		$update = array('$push'=>array("medication_taken"=>$datewise_update));
		 
		$response = $this->mongo_db->command(array( 
		'findAndModify' => $this->collections['tswreis_chronic_cases'],
		'query'         => $query,
		'update'        => $update,
		'upsert'        => true
		));
	
		return $response['ok'];*/
	}
	
	// ---------------------------------------------------------------------------------------
	
	/**
	 * Helper: Fetch Student's pill compliance data
	 *
	 * @param string $case_id 			 Case ID
	 * @param string $student_unique_id  Hospital Unique ID
	 *
	 * @return array
	 *
	 * @author Selva
	 */
	 
	public function fetch_student_pill_compliance_data($case_id,$student_unique_id)
	{
	    $where_clause = array(
		'case_id' => $case_id,
		'student_unique_id'=> $student_unique_id
		);
		
		//$query = $this->mongo_db->where($where_clause)->whereBetween('pill_compliance.$.date',$from_date,$to_date)->select(array('pill_compliance'),array())->get($collection);
		$query = $this->mongo_db->where($where_clause)->get($this->collections['tswreis_chronic_cases']);
        return $query;
	}
	/*
	*Fetchinhg BMI value with Unique id
	*author Naresh
	
	*/ 
	public function get_student_bmi_values($unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))
           ->get('healthcare2017617145744625');
		//->get('healthcare2017617145744625');
		//log_message("debug","query==========12576".print_r($query,true));
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	
	//update personal Information
   public function get_update_personal_ehr_uid($uid) {
	  
	  ini_set ( 'memory_limit', '2G' );
		//log_message("debug","uid modelllllll114816".print_r($uid,true));
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'doc_properties',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->orderBy(array('history.last_stage.time' => -1))->limit(1)->get ( $this->screening_app_col_2021_22 );
//		//log_message("debug"," query modelllllll114816".print_r($query,true));
		 if ($query) {
			/* $query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $uid )->get ( $this->request_app_col ); */
			
			$result ['screening'] = $query;
			//$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			//$result ['request'] = false;
			return $result;
		} 
	}
	
	public function update_student_ehr_model($unique_id,$doc_data)
	{
		ini_set ( 'memory_limit', '2G' );
	  //$doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set($doc_data)->update($this->screening_app_col_2021_22);
	  
	  $query_two = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set($doc_data)->update($this->screening_app_col);
	  //log_message('debug',"updateeeeeeeeeeeee".print_r($query,true));
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}
	

	public function get_today_news_feeds($date){
		
		if(!$date){
			$date = date('Y-m-d');
		}
		
		$display_date = array (
				"display_date" => array (
						'$regex' => $date
				) 
		);
		$result = [ ];
			
			$pipeline = [ 
					
					array('$match' => $display_date)
					
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->collections ['panacea_news_feed'],
					'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
	
		return $query;
	
	}

	public function get_all_news_feeds(){
	
		$query = $this->mongo_db->get ( $this->collections ['panacea_news_feed'] );
	
		return $query;
	
	}
	
	
	public function get_school_color_code($school_code)
	{
		$query_under = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Details.Hospital Unique ID",$school_code,'i')->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.5)->get($this->bmi_app_col);
		
		$count_under = count($query_under);
		$query_normal = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Details.Hospital Unique ID",$school_code,'i')->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.5 , 24.9)->get($this->bmi_app_col);
		
		$count_normal = count($query_normal);
		$query_over = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Details.Hospital Unique ID",$school_code,'i')->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.0,29.9)->get($this->bmi_app_col);
		
		$count_over = count($query_over);
		$query_obese = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Details.Hospital Unique ID",$school_code,'i')->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0)->get($this->bmi_app_col);
		
		$count_obese = count($query_obese);
		$final = max($query_under,$query_normal,$query_over,$query_obese);
		
		$final_count = count($final);
		
		if(($final_count == $count_under) && ($final_count != 0))
		{
			 $query = "under_weight";
			
		}
		else if(($final_count == $count_normal) && ($final_count != 0))
		{
			 $query = "normal_weight";
		}
		else if(($final_count == $count_over) && ($final_count != 0))
		{
			 $query = "over_weight";
		}
		else if(($final_count == $count_obese) && ($final_count != 0))
		{
			 $query = "obese";
		}
		else
		{
			$query = "No BMI values";
		}
		
		return $query;
		
	}

		 // BMI PIE REPORT
	public function get_bmi_report_model($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		
		$requests = [ ];

				
				$under_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50) ->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');
				$request ['label'] = 'UNDER WEIGHT';
				$request ['value'] = count($under_weight);
				array_push ( $requests, $request );
				
				$normal_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50,24.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');
				$request ['label'] = 'NORMAL WEIGHT';
				$request ['value'] = count($normal_weight);
				array_push ( $requests, $request );
				
				$over_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');
				$request ['label'] = 'OVER WEIGHT';
				$request ['value'] = count($over_weight);
				array_push ( $requests, $request );
				
				$obese = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');
				$request ['label'] = 'OBESE';
				$request ['value'] = count($obese);
				array_push ( $requests, $request );
				
				
		return $requests;
		
	}
	
	
	public function get_drill_down_to_bmi_report($type, $current_month,  $school_name) 
	{
		$current_month = substr($current_month,0,-3);

		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		ini_set ( 'memory_limit', '10G' );
		
		switch ($type) {
			case "UNDER WEIGHT" :
				ini_set ( 'memory_limit', '10G' );
				//$select_qry = 
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50)->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->where("doc_data.widget_data.school_details.School Name",$school_name)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data']['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
					
				}
				
				return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
			case "NORMAL WEIGHT" :
			ini_set ( 'memory_limit', '10G' );
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50,24.99) ->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			case "OVER WEIGHT" :
			ini_set ( 'memory_limit', '10G' );

			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)-> get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
				
				case "OBESE" :
				ini_set ( 'memory_limit', '10G' );
			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0) ->whereLike ( 'doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)-> get ( $this->bmi_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_bmi_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_bmi_students_prepare_pie_array($query, $school_name, $type)
	{
		$search_result = [ ];
		$count = 0;
		
		 if ($query) {
			//ini_set('memory_limit','20G');
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $query as $doc ) {
				
				switch ($type) {
					case "UNDER WEIGHT" :
					
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
					case "NORMAL WEIGHT" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
					
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
					
					case "OVER WEIGHT" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
						
					case "OBESE" :
						
						$bmi_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $bmi_ids_arr );
						
						break;
					
					default :
					break;
				}
		 }
		
			return $UI_arr;
		}
	}
	 
	 public function get_drilling_bmi_students_docs($_id_array) 
	 {
		$docs = [ ];
		//set_time_limit(0);

		ini_set ( 'memory_limit', '10G' );
			if(isset($_id_array) && !empty($_id_array))
		{
			foreach ( $_id_array as $_id ) 
			{
				/*$query = $this->mongo_db->select ( array (
						'doc_data.widget_data.page1',
						'doc_data.widget_data.page2' 
				) )->where( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );*/
				//if ($query)
				//array_push ( $docs, $query [0] );
				
				//$unique_id = $query['0']['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
				$bmi_info = $this->mongo_db->select(array('doc_data'))->where('doc_data.widget_data.page1.Student Details.Hospital Unique ID',$_id)->get($this->bmi_app_col);
	          //  $student_info_and_bmi_info['student_info'] = $query;
	            $student_info_and_bmi_info['bmi_info'] = $bmi_info;
	            array_push($docs,$student_info_and_bmi_info);
	            
			}
		}

		/*echo '<pre>';
		echo print_r($docs,true);
		echo '</pre>';
		exit;
	*/
		return $docs;
		
		
	 }

	
	public function get_student_bmi_graph_values($hospital_unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where( array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $hospital_unique_id))->get($this->bmi_app_col);
		
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	
	
	public function export_bmi_reports_monthly_to_excel($date,$school_name){
		
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data","doc_data.widget_data.school_details") )
				->whereLike("doc_data.widget_data.page1.Student Details.BMI_values.month" , $date)->where(array( "doc_data.widget_data.school_details.School Name" => $school_name))->get ( $this->bmi_app_col );
		//log_message("debug","get_reported_schools_bmi_count_by_dist_name==13030".print_r($query,true));
		return $query;
	}
	
	/**
	 * Helper: Fetch student details using the school code
	 *
	 * @param  int $school_code  School code
	 *
	 * @return array
	 */
	 
	public function fetch_student_info_model($school_name, $unique_id)
	{
		
		$res = $this->mongo_db->select(array('doc_data.widget_data'))->where(array('doc_data.widget_data.page2.Personal Information.School Name' => $school_name, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $unique_id))->get('tswreis_screening_report_col_2020-2021');
	
		if($res)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	function fetch_existing_document($doc_id)
	{
		  $query = $this->mongo_db->getWhere('healthcare2017617145744625', array('doc_properties.doc_id' => $doc_id));
		  return $query[0];
	}
	
	function check_if_doc_exists_in_bmi($unique_id)
	{
		$is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->get('healthcare2017617145744625');
		
		//log_message('debug',"unique_id4537======".print_r($unique_id,true));
		//log_message('debug',"is_existssssssss".print_r($is_exists,true));
		
		if($is_exists)
		{
			return TRUE;
		}
		else {
		  return FALSE;
		}
		
	}

	/* BMI Form update function */
	function update_bmi_values($month,$monthly_bmi,$unique_id,$bmi_values_data)
	{
		$this->increament_bmi_count($bmi_values_data);
		$exists_unique =  $this->mongo_db->where(array('Student Unique ID' => $unique_id))->get('bmi_sms_count_col');
		if(!empty($exists_unique))
		{
			$this->mongo_db->where(array('Student Unique ID' => $unique_id))->push("BMI_values",$bmi_values_data['BMI_values'])->update('bmi_sms_count_col');
		}else
		{
			if(!empty($bmi_values_data) && isset($bmi_values_data))
			{
				$this->mongo_db->insert('bmi_sms_count_col',$bmi_values_data);
			}
		}
		
		  
		 /* 
		 $check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.BMI_values"=>array('$elemMatch'=>array("month"=>$month)));

		 $is_already_updated = $this->mongo_db->where($check_query)->get('healthcare2017617145744625');
		  
			
		  if($is_already_updated)
		  {
			 $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.BMI_values"=>array('$elemMatch'=>array("month"=>$month)));
			 
			$update_values = array('doc_data.widget_data.page1.Student Details.BMI_values.$.height'=>$monthly_bmi['height'],'doc_data.widget_data.page1.Student Details.BMI_values.$.weight'=>$monthly_bmi['weight'],'doc_data.widget_data.page1.Student Details.BMI_values.$.bmi'=>$monthly_bmi['bmi']);
			 
			$update = array('$set'=>$update_values);
			

			$response = $this->mongo_db->command(array( 
			'findAndModify' => 'healthcare2017617145744625',
			'query'         => $query,
			'update'        => $update
			 ));
			 
			 $update_values_main = array('doc_data.widget_data.page1.Student Details.Height cms'=>$monthly_bmi['height'],
								   'doc_data.widget_data.page1.Student Details.Weight kgs'=>$monthly_bmi['weight'],
								   'doc_data.widget_data.page1.Student Details.BMI'=>$monthly_bmi['bmi']);
								   
			 $update_main = array('$set'=>$update_values_main);
			 
			 $response = $this->mongo_db->command(array( 
			'findAndModify' => 'healthcare2017617145744625',
			'query'         => $query,
			'update'        => $update_main
			 ));
			 
			 if($response['ok'])
				return TRUE;
			else
				return FALSE; 
			
		 
		  }*/
		  
			 $new_date = new DateTime($month);
					$ndate = $new_date->format('Y-m-d');
					
			/* $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.Height cms'=>$monthly_bmi['height'],
										'doc_data.widget_data.page1.Student Details.Weight kgs'=>$monthly_bmi['weight'],
										'doc_data.widget_data.page1.Student Details.BMI'=>$monthly_bmi['bmi'],
										'doc_data.widget_data.page1.Student Details.Date'=>$ndate))
										->update('healthcare2017617145744625');*/

			$after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.BMI_latest'=> array('height' => $monthly_bmi['height'],
		 																	'weight'=>$monthly_bmi['weight'],
		 																	'bmi'=>$monthly_bmi['bmi'],
		 																	'month' => $ndate)))->update($this->bmi_app_col);
			
				
		  $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->push('doc_data.widget_data.page1.Student Details.BMI_values',$monthly_bmi)->update('healthcare2017617145744625');
			
		  if($after_update)
			return TRUE;
		  else
			return FALSE; 
	}

		
	/* BMI Form submit function */
	public function add_student_BMI_model($doc_data, $doc_properties, $app_properties, $history,$bmi_values_data)
	{
		$this->increament_bmi_count($bmi_values_data);
		  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data), "doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);

		  $query = $this->mongo_db->insert('healthcare2017617145744625',$doc_data);

		   if(!empty($bmi_values_data) && isset($bmi_values_data))
			{
				$this->mongo_db->insert('bmi_sms_count_col',$bmi_values_data);
			}
			
		  if($query)
			  return TRUE;
		  else
			  return FALSE;
	}
	
	function check_if_doc_exists_in_hb($unique_id)
	{
		$is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->get($this->hb_app_col);

		if($is_exists)
		{
			return TRUE;
		}
		else {
		  return FALSE;
		}
	
	}
	
	/* HB Form update function */

	function update_hb_values($month,$monthly_hb,$unique_id,$hb_values_data)
	{	
		$this->increament_hb_count($hb_values_data);		
		$exists_unique =  $this->mongo_db->where(array('Student Unique ID' => $unique_id))->get('hb_sms_count_col');
		if(!empty($exists_unique))
		{
			//echo print_r($hb_values_data,TRUE); exit;
			$this->mongo_db->where(array('Student Unique ID' => $unique_id))->push("HB_values",$monthly_hb )->update('hb_sms_count_col');
		}else
		{
			if(!empty($hb_values_data) && isset($hb_values_data))
			{
				$this->mongo_db->insert('hb_sms_count_col',$hb_values_data);
			}
			
			//echo print_r($query,TRUE);exit();
		}
	 
			 
				
			 /*
			 $check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.HB_values"=>array('$elemMatch'=>array("month"=>$month)));
			 
		 $is_already_updated = $this->mongo_db->where($check_query)->get($this->hb_app_col);

				
			 if($is_already_updated)
			 {
				 $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.HB_values"=>array('$elemMatch'=>array("month"=>$month)));
				 
				$update_values = array('doc_data.widget_data.page1.Student Details.HB_values.$.hb'=>$monthly_hb['hb']);
				 
				$update = array('$set'=>$update_values);
				

				$response = $this->mongo_db->command(array( 
				'findAndModify' => $this->hb_app_col,
				'query'         => $query,
				'update'        => $update
				 ));
				 
				 $update_values_main = array(
									   'doc_data.widget_data.page1.Student Details.HB'=>$monthly_hb['hb']);
									   
				 $update_main = array('$set'=>$update_values_main);
				 
				 $response = $this->mongo_db->command(array( 
				'findAndModify' => $this->hb_app_col,
				'query'         => $query,
				'update'        => $update_main
				 ));
				 
				 if($response['ok'])
					return TRUE;
				else
					return FALSE; 
				
				

			 }*/
			 
			
				 $new_date = new DateTime($month);
					  	$ndate = $new_date->format('Y-m-d');
						
				/* $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array(
											'doc_data.widget_data.page1.Student Details.HB'=>$monthly_hb['hb'],
											'doc_data.widget_data.page1.Student Details.Date'=>$ndate))
											->update($this->hb_app_col);*/
				$after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.HB_latest'=> array('hb' => $monthly_hb['hb'],
		 																				  'month' => $ndate)))->update($this->hb_app_col);
					
			 $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->push('doc_data.widget_data.page1.Student Details.HB_values',$monthly_hb)->update($this->hb_app_col);
				
			 if($after_update)
				return TRUE;
			 else
				return FALSE; 
		 
	}

	
	/* HB Form submit function */
	public function add_student_HB_model($monthly_hb,$doc_properties, $app_properties, $history,$hb_values_data)
	{
		$this->increament_hb_count($hb_values_data);
	  	$doc_data = array("doc_data"=>array("widget_data"=>$monthly_hb), 
	  	"doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);
	 
	 	$query = $this->mongo_db->insert($this->hb_app_col,$doc_data);

	  	if(!empty($hb_values_data) && isset($hb_values_data))
		{
			$this->mongo_db->insert('hb_sms_count_col',$hb_values_data);
		}
		  if($query)
			  return TRUE;
		  else
			  return FALSE;
	}	
	public function get_student_hb_values($unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get($this->hb_app_col);
		
		if($query)
			return $query;
		else
			return FALSE;
	}
	
	
	/* Attendance Form submit function */
	public function create_attendence_report_model($doc_data, $doc_properties, $app_properties, $history)
	{
		$final_values = array("doc_data"=>array("widget_data"=>$doc_data),"doc_properties"=>$doc_properties, "history"=>$history, "app_properties"=>$app_properties);
		$query = $this->mongo_db->insert('healthcare201651317373988',$final_values);

		if($query)
		  return TRUE;
		else
		  return FALSE;
	}
	
	 
	public function fetch_student_information_model($school_name,$unique_id)
	{
		//log_message('error','unique_id===============1778'.print_r(trim($unique_id),true));
		//log_message('error','school_name===============78'.print_r($school_name,true));
		$unique_id = trim($unique_id);
		$res = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where(array('doc_data.widget_data.page2.Personal Information.School Name' => $school_name, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $unique_id))->get('healthcare2016226112942701');
	////log_message('error','res===============13106'.print_r($res,true));
		if($res)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}

	function create_chronic_case($student_unique_id,$chronic_disease,$disease_desc,$request_type,$school_data)
	{
		$count = count($chronic_disease);
		/*for($i = 0; $i<$count; $i++)
		{*/
		  $data = array(
		  'case_id'            => get_unique_id(),
		  'student_unique_id'  => $student_unique_id,
		  'chronic_disease'    => $chronic_disease,
		  'disease_desc'       => $disease_desc,
		 // 'school_name'        => $school_data['school_name'],
		  'created_time'       => date('Y-m-d H:i:s'),
		  'followup_scheduled' => "false"); 
		  
		  $query = $this->mongo_db->insert('tswreis_chronic_cases_new', $data);

			 /* $district_name = $school_data['dt_name'];
			  $school_name = $school_data['school_name'];
			  $select_query_value = "pie_data.".$request_type.".".$chronic_disease[$i];

					  $date = "2018-07-02";

					  $query = $this->mongo_db->select(array($select_query_value))->whereLike('pie_data.date',$date)->get('panacea_district_wise_healthrequest_counts');

					 	foreach ($query as $index => $district) {
					  		if(isset($district['pie_data'][$request_type][$chronic_disease[$i]][$district_name]))
					  		{
					  			$district_list = $district['pie_data'][$request_type][$chronic_disease[$i]][$district_name];
						  		if(!empty($district_list))
						  		{
						  			$district_list ++;
						  		}
						  		else
						  		{
						  			$district_list = 1;
						  		}
					  		}else
					  		{
					  			$district_list = 1;
					  		}
					  	}
					  		$update_dist = array($select_query_value.".".$district_name => $district_list);
					  		$this->mongo_db->whereLike('pie_data.date',$date)->set($update_dist)->update('panacea_district_wise_healthrequest_counts');

					  		//updating School Name count in manually created collection

					  		$chronic =array($select_query_value.".".$district_name);
					  		$query = $this->mongo_db->select($chronic)->whereLike('pie_data.date',$date)->get('panacea_school_wise_healthrequest_counts');
					  		//log_message('error',"query============5304".print_r($query,true));
					  	foreach ($query as $index => $school) {
					  		if(isset($school['pie_data'][$request_type][$chronic_disease[$i]][$district_name][$school_name]))
					  		{
					  			$school_list = $school['pie_data'][$request_type][$chronic_disease[$i]][$district_name][$school_name];
						  		if(!empty($school_list))
						  		{
						  			$school_list ++;
						  		}
						  		else
						  		{
						  			$school_list = 1;
						  		
						  		}
					  		}
					  		else{
					  			$school_list = 1;
					  		}
					  	}
					  	$update_school = array($select_query_value.".".$district_name.".".$school_name => $school_list);
					  	$this->mongo_db->whereLike('pie_data.date',$date)->set($update_school)->update('panacea_school_wise_healthrequest_counts');*/
		//}
	}
	
	function create_chronic_case_new($student_unique_id,$request_type,$chronic_disease,$disease_desc,$schoolName)
	{
		/*if(!empty($chronic_disease['Central_nervous_system'][0]) && $chronic_disease['Central_nervous_system'][0] == "Epilepy")
		{
			$query = $this->mongo_db->where(array('School Details.School Name' => $schoolName))->inc(array('School Status.2019-02-28.Epilepy' => 1))->update('get_schools_status_collection');			
		}
		if(!empty($chronic_disease['Respiratory_system'][0]) && $chronic_disease['Respiratory_system'][0] == "Asthma") 
		{
			$query = $this->mongo_db->where(array('School Details.School Name' => $schoolName))->inc(array('School Status.2019-02-28.Asthma' => 1))->update('get_schools_status_collection');			
		}
		if(!empty($chronic_disease['Skin'][0]) && $chronic_disease['Skin'][0] == "Scabies") 
		{
			$query = $this->mongo_db->where(array('School Details.School Name' => $schoolName))->inc(array('School Status.2019-02-28.Scabies' => 1))->update('get_schools_status_collection');			
		}*/
		if($chronic_disease['Kidney'] !== array())
		{
			$query = $this->mongo_db->where(array('School Details.School Name' => $schoolName))->inc(array('School Status.2019-02-28.Kidney' => 1))->update('get_schools_status_collection');
		}
		foreach ($chronic_disease as $disease_type => $disease_names)
		{
			if(!empty($disease_names) && gettype($disease_names) != array())
			{
				foreach ($disease_names as $disease_name)
				{
					$query = $this->mongo_db->where(array('School Details.School Name' => $schoolName,'School Status.2019-02-28.'.$disease_name => array('$exists' => true)))->inc(array('School Status.2019-02-28.'.$disease_name => 1))->update('get_schools_status_collection');
				}
			}
		}
		
		  $data = array(
		  'case_id'            => get_unique_id(),
		  'student_unique_id'  => $student_unique_id,
		  'chronic_disease'    => $chronic_disease,
		  'disease_desc'       => $disease_desc,
		  'school_name'        => $schoolName,
		  'created_time'       => date('Y-m-d H:i:s'),
		  'followup_scheduled' => "false"); 
		  
		$query = $this->mongo_db->insert('tswreis_chronic_cases', $data);
	}

	/**
	 * Helper: Inserting HS Request doc
	 *
	 * @param  int $doc_data,$doc_properties,$app_properties,$array_history
	 *
	 * @return array
	 * author Naresh
	 */

	public function initiate_request_model($doc_data,$doc_properties,$app_properties,$array_history)
	{
		
		$doc_data = array('doc_data' => $doc_data,"doc_properties"=>$doc_properties,'app_properties' => $app_properties,'history' => $array_history);
		
		$res = $this->mongo_db->insert('healthcare2016531124515424_static_html',$doc_data);
		$this->mongo_db->insert('healthcare2016531124515424_static_html_shadow',$doc_data);
	
		if($res)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}
	/**
	 * Helper: Fetch Normal requests
	 *
	 *  author Naresh
	 */
	public function get_hs_req_normal($usercollection,$unique_id)
    {
			/*$status = array();  
            $match = array('app_properties.status' => "new",'app_properties.app_id' => "healthcare2016531124515424");
            $request_type = array('doc_data.widget_data.page2.Review Info.Request Type' => array(
                    '$ne' => 'Emergency'
                    )
                         );
             $request_type_chronic = array('doc_data.widget_data.page2.Review Info.Request Type' => array(
                    '$ne' => 'Chronic'
                    )
                         );
             array_push($status,$match);
             array_push($status,$request_type);
             //array_push($status,$request_type_chronic);
              echo print_r($status,true);
             $limit = 200;

        $sort = array('history.0.time' => -1);
        $pipeline = [ 
                    array (
                            '$project' => array (
                                    "doc_data.widget_data" => true
                                    //"doc_data.external_attachments" => true,
                                    //"_id" => false
                            ) 
                    ),
                    // array('$match' => $merged_array)
                    array(
                        '$match' =>array(
                            '$and' => $status
                        ) 
                    ),
                    array(
                        '$sort' => $sort
                    ),
                    array (
                            '$limit' => $limit 
                    )
                    
            ];
            $response = $this->mongo_db->command ( array (
                    'aggregate' => $usercollection,
                    'pipeline' => $pipeline 
            ) );
            $result = [];
            foreach ($response['result'] as $query) {
                array_push($result,$query);
            }
           echo print_r($response,true);
           exit();
            return $result;*/
          $query_normal = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Normal','doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $unique_id),'doc_data.widget_data.page2.Review Info.Status' => array('$ne' => "Cured"));
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($query_normal)->get($usercollection);

        	$full_doc = array();
        foreach ($query as $value) {

            if ($value['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Expired')
            {
                 array_push($full_doc, $value);
              
        }
    }
        
        return $full_doc;
    }
	/**
	 * Helper: Fetch Emergency requests
	 *
	 *  author Naresh
	 */
    public function get_hs_req_emergency($usercollection,$unique_id)
    {
    	//$array_emergency = array();
    	$query_emergency = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency','doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $unique_id), 'doc_data.widget_data.page2.Review Info.Status' => array('$ne' => "Cured"));
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($query_emergency)->get($usercollection);
        $full_doc = array();
                foreach ($query as $value) {

                    if ($value['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Expired')
                    {
                         array_push($full_doc, $value);
                      
                }
            }
                
                return $full_doc;
    }
    /**
	 * Helper: Fetch Chronic requests
	 *
	 *  author Naresh
	 */
    public function get_hs_req_chronic($usercollection,$unique_id)
    {
    	$query_chronic = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic','doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $unique_id), 'doc_data.widget_data.page2.Review Info.Status' => array('$ne' => "Cured"));
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($query_chronic)->get($usercollection);

        $full_doc = array();
                foreach ($query as $value) {

                    if ($value['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Expired')
                    {
                         array_push($full_doc, $value);
                      
                }
            }
                
                return $full_doc;
    }

    /**
	 * Helper: Fetch Chronic requests
	 *
	 *  author yoga
	 */
    public function get_hs_req_cured($usercollection,$unique_id)
    {
    	$query_chronic = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Status' => 'Cured','doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $unique_id));
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(2000)->where($query_chronic)->get($usercollection);

        return $query;
    }

    public function get_hs_req_expired($usercollection,$unique_id)
    {
    	$query_chronic = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Status' => 'Expired','doc_data.widget_data.page1.Student Info.Unique ID' => array('$regex' => $unique_id));
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($query_chronic)->get($usercollection);

        return $query;
    }

     /**
	 * Helper: Fetch Chronic requests
	 *
	 *  author Yoga
	 */

	public function access_submited_request_docs($doc_id,$doc_access,$access_by)
	{
		$time = time();
		$get_document = $this->mongo_db->select(array(),array('_id'))->where(array('doc_properties.doc_id' => $doc_id))->limit(1)->get('healthcare2016531124515424_static_html');
			if(isset($get_document) && !empty($get_document))
			{
				foreach ($get_document as $document) 
				{
					if($doc_access != $document['doc_properties']['doc_access'])
					{
						//$final_doc = $document;
						$update = $this->mongo_db->where(array('doc_properties.doc_id' => $doc_id))->set(array('doc_properties.doc_access' => $doc_access,'doc_properties.access_by' => $access_by,'doc_properties.doc_access_time' => $time))->update('healthcare2016531124515424_static_html');
						$get_document = $this->mongo_db->select(array(),array('_id'))->where(array('doc_properties.doc_id' => $doc_id))->limit(1)->get('healthcare2016531124515424_static_html');
						$final_doc = $get_document[0];
					}else 
					{					
						$server_time = $get_document[0]['doc_properties']['doc_access_time'];
						
						$result_time = round(($time - $server_time)/ 60);	
						
						if($result_time >= 5)
						{			
							$final_doc = $document;
							$time_2 = time();
							$update = $this->mongo_db->where(array('doc_properties.doc_id' => $doc_id))->set(array('doc_properties.doc_access' => $doc_access,'doc_properties.access_by' => $access_by,'doc_properties.doc_access_time' => $time_2))->update('healthcare2016531124515424_static_html');

						}
						else
						{
							$final_doc['access_by'] = $document['doc_properties'];		
											
						}					

					}
				}
				if(!empty($final_doc))
				{
					return $final_doc;
				}
				else
				{
					return "No Documents Found";
				}
			}else
			{
				return "No Documents Found";
			}
	}

	public function get_history($unique_id,$doc_id)
	{
		$query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id,'doc_properties.doc_id' => $doc_id))->get('healthcare2016531124515424_static_html');
		return $query;
	}
	 /**
	 * Helper: Updating the existing request doc
	 *
	 *  author Naresh
	 */

	public function update_request_submit_model($doc_data,$history_array,$unique_id,$doc_id,$doc_properties)
	{	
		
		$update_query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->set(array('doc_data' =>$doc_data,"doc_properties.doc_access"=>$doc_properties['doc_access'],"doc_properties.access_by"=>$doc_properties['access_by'],"doc_properties.doc_access_time"=>$doc_properties['doc_access_time'],'history'=>$history_array))->update('healthcare2016531124515424_static_html');
		
		$app_properties = array(
						'app_name' => "Health Requests App",
						'app_id' => "healthcare2016531124515424",
						'status' => "new"
					);

		$doc_data = array('doc_data' => $doc_data,"doc_properties"=>$doc_properties,'app_properties' => $app_properties,'history' => $history_array);
		$this->mongo_db->insert('healthcare2016531124515424_static_html_shadow',$doc_data);

		return $update_query;
	}
	
	/*  public function get_students_list_device($school)
    {

        $query = $this->mongo_db->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID', 'doc_data.widget_data.page2.Personal Information.Class'))->where(array('doc_data.widget_data.page2.Personal Information.School Name' => $school))->get('healthcare2016226112942701');
        $lists = [];
        foreach ($query as $doc) {
            $class    = $doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];
            $healthid = $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
            if (isset($lists[$class])) {
                array_push($lists[$class], $healthid);
            } else {
                $lists[$class] = [];
                array_push($lists[$class], $healthid);
            }

        }

        return $lists;
    } */

    // -------------------------------------------------------------------------------

	/**
	 * Helper: Get health supervisor details for school code
	 *
	 * @return array
	 *  
	 * @author Naresh 
	 */
	public function get_school_information_for_school_code($school_code)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$school_data = $this->mongo_db->where(array('school_code'=> (int)$school_code))->select(array('school_name','dt_name','contact_person_name','school_mob'),array())->get('panacea_schools');
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		foreach ( $school_data as $schools => $school ) 
		{
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'panacea_district' );
			if (isset ( $school ['dt_name'] )) {
				$school_data [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$school_data [$schools] ['dt_name'] = "No district selected";
			}
		}
		return $school_data[0];
	}
	// -------------------------------------------------------------------------------

	/**
	 * Helper: Get health supervisor details for school code
	 *
	 * @return array
	 *  
	 * @author Naresh 
	 */

	public function get_health_supervisor_details($schoolCode)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->where(array('school_code'=>$schoolCode))->get($this->collections['panacea_health_supervisors']);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		
		return $query[0];
	}

	/**
	 * Helper: get approval history from document collection
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */

    function get_approval_history($doc_id)
    {
    	$query = $this->mongo_db->select(array('history'))->where(array('doc_properties.doc_id'=> $doc_id))->get('healthcare2016531124515424_static_html');
    	return $query[0]['history'];
    }

    function fetch_all_ha()
	{
	  $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	  $ha_email = $this->mongo_db->select(array('email'),array())->get($this->collections['panacea_cc']);
	  $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	  $ha_emails = [];
	  foreach($ha_email as $email){
		  $id = str_replace("@","#",$email['email']);
		  array_push($ha_emails,$id);
	  }
	  return $ha_emails;
		
	}

	function fetch_hs_by_school_code($school_code)
	{
	  $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	  $email = $this->mongo_db->select(array('email'),array())->where('school_code',$school_code)->get($this->collections['panacea_health_supervisors']);
	  $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	  return $email[0]['email'];
		
	}

	public function insert_into_notification_message($title,$date,$message)
	{
		$notification_msg = array(
						'title' => $title,
						'date' => $date,
						'message' =>$message
					);
		$query = $this->mongo_db->insert('notification_message',$notification_msg);
		if($query)
		{
			return $query;
		}
	}
	
	/*
	* Dashboard version 2 functionalities
	* author Harish
	*/
	
		public function get_attendance_report_daily_count($school_name,$date)
		{

			 if ($date) {
				$today_date = $date;
			} else {
				$today_date = $this->today_date;
			}

			$query = $this->mongo_db->select(array(
											'doc_data.widget_data.page1.Attendence Details',
											'doc_data.widget_data.page2.Attendence Details'
			))->where(array('doc_data.widget_data.page1.Attendence Details.Select School' => $school_name))->WhereLike('history.last_stage.time', $today_date)->get('healthcare201651317373988');
			if($query)
			{
				
				return $query;
			}
			
			else{
				return FALSE;
			}
				
		}

/*  HS Daily Requests Count to show on table ================ By Harish ============   */

		public function get_requests_daily_count($district_school_code,$date)
		{
			if ($date) {
				$today_date = $date;
			}else {
				$today_date = $this->today_date;
			}

			$normal_requests = $this->mongo_db->whereLike(
				'doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal",
			))->count('healthcare2016531124515424_static_html');


			$emergency_requests = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))
			   ->count('healthcare2016531124515424_static_html');

			$chronic_requests = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))
			   ->count('healthcare2016531124515424_static_html');
			
				$result ['normal_requests'] = $normal_requests;
				$result ['emergency_requests'] = $emergency_requests;
				$result ['chronic_requests'] = $chronic_requests;
				
				return $result;
		}    

 /* HS Daily Requests information to show on modal */ 

		public function get_request_info_by_type($district_school_code,$date)
		{
			if ($date) {
				$today_date = $date;
		}else {
			$today_date = $this->today_date;
		}

			$selected_fields = array('doc_data.widget_data.page1','doc_data.widget_data.page2');

	$requetsInfo = array();

	$normal_requests_info = $this->mongo_db->select($selected_fields)->whereLike(
				'doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			  ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get('healthcare2016531124515424_static_html');
			  	/*echo '<pre>';
				echo print_r($normal_requests_info,true); 
				echo "</pre>";
				exit;*/
			  	
	$emergency_requests_info = $this->mongo_db->select($selected_fields)->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get('healthcare2016531124515424_static_html');
			  

	$chronic_requests_info = $this->mongo_db->select($selected_fields)->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get('healthcare2016531124515424_static_html');
			   
				$result ['normal_requests_info'] = $normal_requests_info;
				$result ['emergency_requests_info'] = $emergency_requests_info;
				$result ['chronic_requests_info'] = $chronic_requests_info;
				
				return $result;
			
		}

/*  DOCTOR Daily Requests Count to show on Table */

	public function get_docs_daily_count($district_school_code, $date)
		{
			if ($date) {
				$today_date = $date;
			}else {
				$today_date = $this->today_date;
			}

			$doc_normal_requests = $this->mongo_db->whereLike(
				'doc_data.widget_data.page1.Student Info.Unique ID', $district_school_code)
			  ->WhereLike('history.1.time', $today_date)
              ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->count('healthcare2016531124515424_static_html');

			$doc_emergency_requests = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.1.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))
			   ->count('healthcare2016531124515424_static_html');
			    

			$doc_chronic_requests = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.1.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))
			   ->count('healthcare2016531124515424_static_html');
			
				$result ['doc_normal_requests'] = $doc_normal_requests;
				$result ['doc_emergency_requests'] = $doc_emergency_requests;
				$result ['doc_chronic_requests'] = $doc_chronic_requests;
				
				return $result;
			
		}

/*  DOCTOR Daily Requests information to show on modal */

	public function get_docs_daily_requests_info($district_school_code, $date)
		{
			if ($date) {
				$today_date = $date;
			}else {
				$today_date = $this->today_date;
			}

	$selected_fields = array('doc_data.widget_data.page1','doc_data.widget_data.page2');
    $docrequetsInfo = array();

	$doc_normal_requests_info = $this->mongo_db->select($selected_fields)->whereLike(
				'doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			    ->WhereLike('history.1.time', $today_date)
                ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->get('healthcare2016531124515424_static_html');
             
	$doc_emergency_requests_info =$this->mongo_db->select($selected_fields)->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.1.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->get('healthcare2016531124515424_static_html');
			 

	$doc_chronic_requests_info = $this->mongo_db->select($selected_fields)->whereLike('doc_data.widget_data.page1.Student Info.Unique ID',$district_school_code)
			   ->WhereLike('history.1.time', $today_date)
			   ->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->get('healthcare2016531124515424_static_html');
			 
			
				$result ['doc_normal_requests_info'] = $doc_normal_requests_info;
				$result ['doc_emergency_requests_info'] = $doc_emergency_requests_info;
				$result ['doc_chronic_requests_info'] = $doc_chronic_requests_info;
				
				return $result;
			
		}

/*  HS Daily Sanitation count to show on Table */
		
		public function get_sanitation_report_daily_count($school_name,$date)
		{

				if ($date) {
					$today_date = $date;
				}else {
					$today_date = $this->today_date;
				}


				$query = $this->mongo_db->select(array(
						    'doc_data.widget_data.page4.School Information',
						    'doc_data.widget_data.page4.Declaration Information'
				))->where(array('doc_data.widget_data.page4.School Information.School Name' => $school_name))->WhereLike('history.last_stage.time', $today_date)->get('healthcare2016111212310531');

				if($query)
				{
					
					return $query;
				}
				
				else{
					return FALSE;
				}
					
		}

/*  HS BMI count to show on Table */

	public function get_bmi_report_model_count($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
			}
				
				$under_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50) ->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->count('healthcare2017617145744625');
				
				$normal_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50,24.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->count('healthcare2017617145744625');
				
				$over_weight = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->count('healthcare2017617145744625');

				$obese = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->count('healthcare2017617145744625');

				$result ['under_weight'] = $under_weight;
				$result ['normal_weight'] = $normal_weight;
				$result ['over_weight'] = $over_weight;
				$result ['obese'] = $obese;

			
		return $result;
		
	}

/*  HS BMI Info to show on modal */

	public function get_bmi_report_model_info($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;

			}
			$selected_month = substr($selected_month,0,-3);

			/*	$under_weight_array = array('doc_data.widget_data.page1.Student Details.BMI_values.month' => $selected_month,'doc_data.widget_data.school_details.School Name'=> $school_name);
				$under_weight_info = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details'))->where($under_weight_array)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.49)->get('healthcare2017617145744625');*/
				
				$under_weight_info = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.49) ->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');
 
				/*$normal_weight_array = array('doc_data.widget_data.page1.Student Details.BMI_values.month' => $selected_month,'doc_data.widget_data.school_details.School Name'=> $school_name);

			    $normal_weight_info = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details'))->where($normal_weight_array)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50,24.99)->get('healthcare2017617145744625');*/
			   
			   $normal_weight_info = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',18.50,24.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');

				
				/*$over_weight_array = array('doc_data.widget_data.page1.Student Details.BMI_values.month' => $selected_month,'doc_data.widget_data.school_details.School Name'=> $school_name);
			 
				$over_weight_info = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details'))->where($normal_weight_array)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->get('healthcare2017617145744625');*/
			
			$over_weight_info = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.BMI_values.bmi',25.00,29.99)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');

			$obese_info = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0)->whereLike('doc_data.widget_data.page1.Student Details.BMI_values.month',$selected_month)->get('healthcare2017617145744625');

				
				/*$obese_weight_array = array('doc_data.widget_data.page1.Student Details.BMI_values.month' => $selected_month,'doc_data.widget_data.school_details.School Name'=> $school_name);

				$obese_info = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details'))->where($obese_weight_array)->wheregte('doc_data.widget_data.page1.Student Details.BMI_values.bmi',30.0)->get('healthcare2017617145744625');*/
				

			   	if($under_weight_info || $normal_weight_info || $over_weight_info || $obese_info)
			   	{
					$result ['under_weight_info'] = $under_weight_info;
					$result ['normal_weight_info'] = $normal_weight_info;
					$result ['over_weight_info'] = $over_weight_info;
					$result ['obese_info'] = $obese_info;
			   		return $result;
			   	}else{
			   		$result ['under_weight_info'] = false;
					$result ['normal_weight_info'] = false;
					$result ['over_weight_info'] = false;
					$result ['obese_info'] =false;
					return $result;
			   	}
	}

/*  HS Screening count to show on Table */
		
	public function get_all_screenings_count($date = false, $screening_duration = "Yearly") 
		{
			if ($date) 
			{
				$today_date = $date;
			} 
			else 
			{
				$today_date = $this->today_date;
			}		

				$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		 

				ini_set ( 'memory_limit', '10G' );
				$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage1_pie_values','pie_data.date' 
				) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		 
				$requests ['Physical Abnormalities'] = 0;
				$requests ['General Abnormalities']  = 0;
				$requests ['Eye Abnormalities']      = 0;
				$requests ['Auditory Abnormalities'] = 0;
				$requests ['Dental Abnormalities']   = 0;
		
				foreach ( $pie_data as $each_pie ) {
					
					$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [0] ['value'];
					$requests ['General Abnormalities']  = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [1] ['value'];
					$requests ['Eye Abnormalities']      = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [2] ['value'];
					$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [3] ['value'];
					$requests ['Dental Abnormalities']   = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [4] ['value'];
				}
	
		$result = [ ];
		foreach ( $requests as $request => $req_value ) {
			$req ['label'] = $request;
			$req ['value'] = $req_value;
			array_push ( $result, $req );
		}
		return $result;
	}

/*  HS HB count to show on Table */

	public function get_hb_report_model_count($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
			}
/* Severe Type Anamea */			
				/* $severe_scl = array('doc_data.widget_data.school_details.School Name' => $school_name);
				$severe_range =  array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 8));
				$severe_mnth = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				$severe_anamia = $this->mongo_db->where($severe_range,$severe_scl,$severe_mnth)->count($this->hb_app_col); */
				
			$severe_anamia = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8)->count($this->hb_app_col);
				


/* Moderate Type Anamea */	
				/* $moderate_gt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 8));
					$moderate_lt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 10));
					$sclname = array('doc_data.widget_data.school_details.School Name' => $school_name);
					$slct_mnth = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);		
					$moderate_anamia = $this->mongo_db->where($moderate_gt,$moderate_lt,$sclname,$slct_mnth)->count($this->hb_app_col); */
					
				$moderate_anamia = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8,10)->count($this->hb_app_col);
				
/* Mild Type Anamea */	
				/* $mild_gt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 10));
				$mild_lt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 12));
				
				$school_name = array('doc_data.widget_data.school_details.School Name' => $school_name);
	  			$selected_month = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				
				$mild_anamia = $this->mongo_db->where($mild_gt,$mild_lt,$school_name,$selected_month)->count($this->hb_app_col); */
				
				$mild_anamia = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10,12)->count($this->hb_app_col);
				
/* Normal Type Anamea */	

			/* 	$normal_scl = array('doc_data.widget_data.school_details.School Name' => $school_name);
				$normal_range =  array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 12.1));
				$normal_mnth = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				$normal_anamia = $this->mongo_db->where($normal_range,$normal_scl,$normal_mnth)->count($this->hb_app_col); */
				
				
				$normal_anamia = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereGt('doc_data.widget_data.page1.Student Details.HB_values.hb',12.1)->count($this->hb_app_col);

				$result ['severe_anamia'] = $severe_anamia;
				$result ['moderate_anamia'] = $moderate_anamia;
				$result ['mild_anamia'] = $mild_anamia;
				$result ['normal_anamia'] = $normal_anamia;

			
		return $result;
		
	}

public function get_hb_report_model_info($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
			}
/* Severe Type Anamea */			
				/* $severe_scl_info = array('doc_data.widget_data.school_details.School Name' => $school_name);
				$severe_range_info =  array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 8));
				$severe_mnth_info = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				$severe_anamia_info = $this->mongo_db->where($severe_range_info,$severe_scl_info,$severe_mnth_info)->get($this->hb_app_col); */
				
				$severe_anamia_info = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereLt('doc_data.widget_data.page1.Student Details.HB_values.hb',8)->get($this->hb_app_col);
				
				

/* Moderate Type Anamea */	
				/* $moderate_gt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 9));
					$moderate_lt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 10));
					$sclname = array('doc_data.widget_data.school_details.School Name' => $school_name);
					$slct_mnth = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);		
					$moderate_anamia_info = $this->mongo_db->where($moderate_gt,$moderate_lt,$sclname,$slct_mnth)->get($this->hb_app_col); */
					
				$moderate_anamia_info = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',8,10)->get($this->hb_app_col);

				
/* Mild Type Anamea */	
				/* $mild_gt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 10));
				
				$mild_lt = array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$lt' => 12));
				
				$school_name = array('doc_data.widget_data.school_details.School Name' => $school_name);
	  			$selected_month = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				
				$mild_anamia_info = $this->mongo_db->where($mild_gt,$mild_lt,$school_name,$selected_month)->get($this->hb_app_col); */
				
				$mild_anamia_info = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereBetween('doc_data.widget_data.page1.Student Details.HB_values.hb',10,12)->get($this->hb_app_col);


				
/* Normal Type Anamea */	

				/* $normal_scl = array('doc_data.widget_data.school_details.School Name' => $school_name);
				$normal_range =  array('doc_data.widget_data.page1.Student Details.HB_values.hb' => array('$gt' => 13));
				$normal_mnth = array('doc_data.widget_data.page1.Student Details.HB_values.month' => $selected_month);
				$normal_anamia_info = $this->mongo_db->where($normal_range,$normal_scl,$normal_mnth)->get($this->hb_app_col); */
				
				$normal_anamia_info = $this->mongo_db->where(array('doc_data.widget_data.school_details.School Name' => $school_name))->whereLike('doc_data.widget_data.page1.Student Details.HB_values.month',$selected_month)->whereGt('doc_data.widget_data.page1.Student Details.HB_values.hb',12.1)->get($this->hb_app_col);
			

				$result ['severe_anamia_info'] = $severe_anamia_info;
				
				$result ['moderate_anamia_info'] = $moderate_anamia_info;
				$result ['mild_anamia_info'] = $mild_anamia_info;
				$result ['normal_anamia_info'] = $normal_anamia_info;

			
		return $result;
		
	}
	
	public function get_all_requests_yearly_count($date = false, $request_duration = "Yearly",$unique_id_pattern) {
		$query = [ ];
		$doc_query = array();
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $unique_id_pattern);
		
		$requests_count_yearly = count($query);

		return $requests_count_yearly;

	}
	
	public function get_screened_students_count($unique_id_pattern)
	{
		$Page3Exists = array (
						"doc_data.widget_data.page3.Physical Exam" => array (
								'$exists' => true
						)
		);

		$screening_count = $this->mongo_db->whereLike('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id_pattern)->where($Page3Exists)->count($this->screening_app_col);

		if($screening_count){
			return $screening_count;
		}else{
			return false;
		}
	}

	public function create_sanitation_report_model($doc_data, $doc_properties, $app_properties, $history)
		{
			$submitted = $history['last_stage']['submitted_by'];
			$time = $history['last_stage']['time'];
			$get_time = explode(" ", $time);
			$get_count = $this->mongo_db->where(array('history.last_stage.submitted_by' => $submitted,'doc_data.widget_data.page4.Declaration Information.Date:' => $get_time[0]))->count('healthcare2016111212310531_version_2');
			if($get_count == 0)
			{
				 $final_values = array("doc_data"=>array("widget_data"=>$doc_data),"doc_properties"=>$doc_properties, "history"=>$history, "app_properties"=>$app_properties);
			  	$query = $this->mongo_db->insert('healthcare2016111212310531_version_2',$final_values);
			}else
			{
				$update = array('doc_data.widget_data' => $doc_data, "history"=>$history);				
				$query = $this->mongo_db->where(array('history.last_stage.submitted_by' => $submitted,'doc_data.widget_data.page4.Declaration Information.Date:' => $get_time[0]))->set($update)->update('healthcare2016111212310531_version_2');
			}
			 

			  if($query)
				  return TRUE;
			  else
				  return FALSE;
		}
		
	 public function get_drilling_screenings_abnormalities_to_count($selectedLabel, $date = false, $screening_duration = "Yearly") {
		
		if ($date) 
		{
			$today_date = $date;
		} 
		else 
		{
			$today_date = $this->today_date;
		}
		
	
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
		switch ($selectedLabel) {
			case "Physical Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'Over Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [0] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Under Weight';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "General Abnormalities" :
				
				$requests = [ ];
				
				$request ['label'] = 'General';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [2] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [3] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Eye Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Without Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [10] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [11] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Auditory Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Right Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [13] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [14] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [15] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			case "Dental Abnormalities" :
				$requests = [ ];
				
				$request ['label'] = 'Oral Hygiene - Fair';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [16] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [17] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [18] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [19] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [20] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [21] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
		}
	}
	
	public function drill_down_screening_to_students_count($symptome_type, $date = false, $screening_duration = "Yearly") {
		ini_set ( 'memory_limit', '1G' );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage3_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
	

		
		switch ($symptome_type) {
			case "Over Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
				
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"] )) 
					{
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Over Weight"]);
					}
				}
				
				return $requests;
				break;
			
			case "Under Weight" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Under Weight"]);
				}
				
				return $requests;
				break;
			
			case "General" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["General"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["General"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["General"]);
				}
				
				return $requests;
				break;
			
			case "Skin" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Skin"] );
				}
				
				return $requests;
				break;
			
			case "Others(Description/Advice)" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Others(Description/Advice)"]);
				}
				
				return $requests;
				break;
			
			case "Ortho" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Ortho"]);
				}
				
				return $requests;
				break;
			
			case "Postural" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Postural"]);
				}
				
				return $requests;
				break;
			
			case "Defects at Birth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Defects at Birth"]);
				}
				
				return $requests;
				break;
			
			case "Deficencies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Deficencies"]);
				}
				
				return $requests;
				break;
			
			case "Childhood Diseases" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Childhood Diseases"]);
				}
				
				return $requests;
				break;
			
			case "Without Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]  != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Without Glasses"]);
				}
				
				return $requests;
				break;
			
			case "With Glasses" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["With Glasses"]);
				}
				
				return $requests;
				break;
			
			case "Colour Blindness" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Colour Blindness"]);
				}
				
				return $requests;
				break;
			
			case "Right Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Right Ear"]);
				}
				
				return $requests;
				break;
			
			case "Left Ear" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Left Ear"] );
				}
				
				return $requests;
				break;
			
			case "Speech Screening" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"]))
						$requests = array_merge_recursive ($requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Speech Screening"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Fair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"]))
						$requests = array_merge_recursive ($requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Fair"]);
				}
				
				return $requests;
				break;
			
			case "Oral Hygiene - Poor" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]!= null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Oral Hygiene - Poor"]);
				}
				
				return $requests;
				break;
			
			case "Carious Teeth" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Carious Teeth"] );
				}
				
				return $requests;
				break;
			
			case "Flourosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Flourosis"] );
				}
				
				return $requests;
				break;
			
			case "Orthodontic Treatment" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Orthodontic Treatment"] );
				}
				
				return $requests;
				break;
			
			case "Indication for extraction" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Indication for extraction"] );
				}
				
				return $requests;
				break;
			
			default :
				;
				break;
		}
	}
	
	public function get_drilling_screenings_students_docs_count($_id_array) {
		$docs = [ ];
		ini_set ( 'memory_limit', '10G' );
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select(array("doc_data.widget_data"))->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			if (isset ( $query [0] ) && !empty($query[0]))
			{
				array_push ( $docs, $query [0] );
			}else
			{
				$query = $this->mongo_db->select(array("doc_data.widget_data"))->where ( "_id", new MongoID ( $_id ) )->get ( 'healthcare2016226112942701_divided_passed_out' );
				array_push ( $docs, $query [0] );
			}
		}
		return $docs;
	}
	// SANITATION REPORT
   public function get_sanitation_report_v2($date, $school_name)
   {
   	 if ($date) {
           $selected_date = $date;
       } else {
           $selected_date = $this->today_date;
       }
       $lastSubmitted = $this->mongo_db->select(array('doc_data.widget_data'))->whereLike('doc_data.widget_data.page4.Declaration Information.Date:', $selected_date)->where(array('doc_data.widget_data.page4.School Information.School Name'=> $school_name, 'doc_properties.status' => 2))->get('healthcare2016111212310531_version_2');


		

			  if($lastSubmitted)
				  return $lastSubmitted;
			  else
				  return FALSE;

   }

   	public function get_history_for_attachments($unique_id,$doc_id)
	{

		$query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $unique_id,'doc_properties.doc_id' => $doc_id))->get($this->screening_app_col);
		
		return $query;
	}

 	 public function import_medical_certificates($unique_id,$update_profile,$doc_id)
	{
		//ini_set ( 'memory_limit', '2G' );
		 
		$update_attachment = array('doc_data.external_attachments'=>$update_profile);
		
	  	//$doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	 	$query = $this->mongo_db->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' =>$unique_id,"doc_properties.doc_id" => $doc_id))->set($update_attachment)->update($this->screening_app_col_2021_22);
	}

	function check_if_doc_exists($unique_id)
	{
		$is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->get($this->bmi_app_col);
		
		if($is_exists)
		{
			return TRUE;
		}
		else {
		  return FALSE;
		}
		
	}
	public function create_bmi_values_monthly($doc_data,$doc_properties,$history,$bmi_values_data) 
	{
		$this->increament_bmi_count($bmi_values_data);
		$document = array('doc_data' => $doc_data,'doc_properties'=>$doc_properties,'history'=>$history);
		$query = $this->mongo_db->insert ( $this->bmi_app_col, $document );

		if(!empty($bmi_values_data) && isset($bmi_values_data))
		{
			$query = $this->mongo_db->insert('bmi_sms_count_col',$bmi_values_data);
		}
		return $query;
	}
	function panacea_update_bmi_values($month,$monthly_bmi,$unique_id,$bmi_values_data)
	{
		$this->increament_bmi_count($bmi_values_data);
	   $exists_unique =  $this->mongo_db->where(array('Student Unique ID' => $unique_id))->get('bmi_sms_count_col');
		if(!empty($exists_unique))
		{
			$this->mongo_db->where(array('Student Unique ID' => $unique_id))->push("BMI_values",$monthly_bmi )->update('bmi_sms_count_col');
		}else
		{
			if(!empty($bmi_values_data) && isset($bmi_values_data))
			{
				$this->mongo_db->insert('bmi_sms_count_col',$bmi_values_data);
			}
		}
	 /*
	$check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.BMI_values"=>array('$elemMatch'=>array("month"=>$month)));

	 $is_already_updated = $this->mongo_db->where($check_query)->get($this->bmi_app_col);
	 
		
	 if($is_already_updated)
	 {
		 $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.BMI_values"=>array('$elemMatch'=>array("month"=>$month)));
		 
		$update_values = array('doc_data.widget_data.page1.Student Details.BMI_values.$.height'=>$monthly_bmi['height'],'doc_data.widget_data.page1.Student Details.BMI_values.$.weight'=>$monthly_bmi['weight'],'doc_data.widget_data.page1.Student Details.BMI_values.$.bmi'=>$monthly_bmi['bmi']);
		 
		$update = array('$set'=>$update_values);
		

		$response = $this->mongo_db->command(array( 
		'findAndModify' => $this->bmi_app_col,
		'query'         => $query,
		'update'        => $update
		 ));
		 
		 $update_values_main = array(
		 						'doc_data.widget_data.page1.Student Details.Height cms'=>$monthly_bmi['height'],
							   'doc_data.widget_data.page1.Student Details.Weight kgs'=>$monthly_bmi['weight'],
							   'doc_data.widget_data.page1.Student Details.BMI'=>$monthly_bmi['bmi']);
							   
		 $update_main = array('$set'=>$update_values_main);
		 
		 $response = $this->mongo_db->command(array( 
		'findAndModify' => $this->bmi_app_col,
		'query'         => $query,
		'update'        => $update_main
		 ));
		 
		 if($response['ok'])
			return TRUE;
		else
			return FALSE; 
		
		
		//db.getCollection('healthcare20176616511646').update({},{'$pull':{"doc_data.widget_data.page1.Student Details.BMI_values":{"month":"2017-11"}}})
		
		//$this->mongo_db->pull('doc_data.widget_data.page1.Student Details.BMI_values',array('month'=>$month))->update('healthcare20176616511646');

	 }*/
	 
	 //$query_main = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.Date"=>array('$elemMatch'=> $month));
	 
	 
		 $new_date = new DateTime($month);
			  	$ndate = $new_date->format('Y-m-d');
				
		 /*$after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.Height cms'=>$monthly_bmi['height'],
									'doc_data.widget_data.page1.Student Details.Weight kgs'=>$monthly_bmi['weight'],
									'doc_data.widget_data.page1.Student Details.BMI'=>$monthly_bmi['bmi'],
									'doc_data.widget_data.page1.Student Details.Date'=>$ndate))
									->update($this->bmi_app_col);*/
		$after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.BMI_latest'=> array('height' => $monthly_bmi['height'],
		 																	'weight'=>$monthly_bmi['weight'],
		 																	'bmi'=>$monthly_bmi['bmi'],
		 																	'month' => $ndate)))->update($this->bmi_app_col);
			
	 $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->push('doc_data.widget_data.page1.Student Details.BMI_values',$monthly_bmi)->update($this->bmi_app_col);
		
	 if($after_update)
		return TRUE;
	 else
		return FALSE; 
	}
	function check_if_doc_exists_hb($unique_id)
	{
		$is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->get($this->hb_app_col);
		
		if($is_exists)
		{
			return TRUE;
		}
		else {
		  return FALSE;
		}
		
	}
	public function create_hb_values_monthly($doc_data,$doc_properties,$history,$hb_values_data) 
	{
		$this->increament_hb_count($hb_values_data);		
		$document = array('doc_data' => $doc_data,'doc_properties'=>$doc_properties,'history'=>$history);
		$query = $this->mongo_db->insert ( $this->hb_app_col, $document );

		if(!empty($hb_values_data) && isset($hb_values_data))
		{
			$this->mongo_db->insert('hb_sms_count_col',$hb_values_data);
		}
		
		return $query;
	}
	function panacea_update_hb_values($month,$monthly_hb,$unique_id,$hb_values_data)
	{
		$this->increament_hb_count($hb_values_data);	
		$exists_unique =  $this->mongo_db->where(array('Student Unique ID' => $unique_id))->get('hb_sms_count_col');
		if(!empty($exists_unique))
		{
			$this->mongo_db->where(array('Student Unique ID' => $unique_id))->push("HB_values",$monthly_hb )->update('hb_sms_count_col');
		}else
		{
			if(!empty($hb_values_data) && isset($hb_values_data))
			{
				$this->mongo_db->insert('hb_sms_count_col',$hb_values_data);
			}
		}	 
		
	 /*
	 $check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.HB_values"=>array('$elemMatch'=>array("month"=>$month)));

	 	$is_already_updated = $this->mongo_db->where($check_query)->get($this->hb_app_col);
	 if($is_already_updated)
	 {
		 $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.HB_values"=>array('$elemMatch'=>array("month"=>$month)));
		 
		$update_values = array('doc_data.widget_data.page1.Student Details.HB_values.$.hb'=>$monthly_hb['hb']);
		 
		$update = array('$set'=>$update_values);
		

		$response = $this->mongo_db->command(array( 
		'findAndModify' => $this->hb_app_col,
		'query'         => $query,
		'update'        => $update
		 ));
		 
		 
		 
		 if($response['ok'])
			return TRUE;
		else
			return FALSE; */

			/*$update_values_main = array('doc_data.widget_data.page1.Student Details.HB'=>$monthly_hb['hb']);
							   
		 $update_main = array('$set'=>$update_values_main);
		 
		 $response = $this->mongo_db->command(array( 
		'findAndModify' => $this->hb_app_col,
		'query'         => $query,
		'update'        => $update_main
		 ));
		
		
		//db.getCollection('healthcare20176616511646').update({},{'$pull':{"doc_data.widget_data.page1.Student Details.BMI_values":{"month":"2017-11"}}})
		
		//$this->mongo_db->pull('doc_data.widget_data.page1.Student Details.BMI_values',array('month'=>$month))->update('healthcare20176616511646');

	 }
	 */
	 //$query_main = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.Date"=>array('$elemMatch'=> $month));
	 
	 
			 $new_date = new DateTime($month);
			  	$ndate = $new_date->format('Y-m-d');
			
			$after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->set(array('doc_data.widget_data.page1.Student Details.HB_latest'=> array('hb' => $monthly_hb['hb'],
		 																	'month' => $ndate)))->update($this->hb_app_col);
			
	 $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id))->push('doc_data.widget_data.page1.Student Details.HB_values',$monthly_hb)->update($this->hb_app_col);
		
	 if($after_update)
		return TRUE;
	 else
		return FALSE; 
	}

	public function update_principal_hs_profile($data,$schoolCode)
	{
		//echo print_r($data,TRUE);exit();
		
			$update_profile_hs = array(
			'hs_name' => $data['hs_name'],
			'hs_mob' => $data['hs_mobile'],
			'username' => $data['hs_name']
			);
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$result = $this->mongo_db->where('school_code',$schoolCode)->set($update_profile_hs)->update('panacea_health_supervisors');
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
			$update_profile_principal = array(
			'contact_person_name' => $data['name'],
			'school_mob' => $data['mobile']
			);
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$result = $this->mongo_db->where('school_code',$schoolCode)->set($update_profile_principal)->update('panacea_schools');
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		if($result)
			return TRUE;
		else
			return FALSE;
		

	}
// HB PIE REPORT
	public function get_hb_report_model($current_month, $school_name) {
		
		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}
		
		$requests = [ ];
		
			
				$sevier = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereLt('doc_data.widget_data.page1.Student Details.HB_latest.hb',8) ->whereLike('doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->get('himglobin_report_col');
				$request ['label'] = 'Sevier';
				$request ['value'] = count($sevier);
				array_push ( $requests, $request );
				
				$normal = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',12,18)->whereLike('doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->get('himglobin_report_col');
				$request ['label'] = 'NORMAL';
				$request ['value'] = count($normal);
				array_push ( $requests, $request );
				
				$moderate = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',8.1,10)->whereLike('doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->get('himglobin_report_col');
				$request ['label'] = 'Moderate';
				$request ['value'] = count($moderate);
				array_push ( $requests, $request );
				
				$mild = $this->mongo_db->where('doc_data.widget_data.school_details.School Name',$school_name)->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',10.1,12)->whereLike('doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->get('himglobin_report_col');
				$request ['label'] = 'Mild';
				$request ['value'] = count($mild);
				array_push ( $requests, $request );
				
				
		return $requests;
		
	}
	public function get_drill_down_to_hb_report($type, $current_month,  $school_name) 
	{
		$current_month = substr($current_month,0,-3);

		if ($current_month) {
			$selected_month = $current_month;
		} else {
			$selected_month = $this->selected_month;
		}

		ini_set ( 'memory_limit', '10G' );
		
		switch ($type) {
			case "Sevier" :
				ini_set ( 'memory_limit', '10G' );
				//$select_qry = 
				$query = $this->mongo_db->select ( array (
						"doc_data" 

				) )->whereLt('doc_data.widget_data.page1.Student Details.HB_latest.hb',8)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->where("doc_data.widget_data.school_details.School Name",$school_name)->get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data'] ['widget_data']['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
					
				}
				
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
			case "NORMAL" :
			ini_set ( 'memory_limit', '10G' );
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',12,18) ->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->where("doc_data.widget_data.school_details.School Name",$school_name)->get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data']['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			case "Moderate" :
			ini_set ( 'memory_limit', '10G' );

			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',8.1,10)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->where("doc_data.widget_data.school_details.School Name",$school_name)-> get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data']['school_details']['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data']['widget_data']['school_details']['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
				
				
				case "Mild" :
				ini_set ( 'memory_limit', '10G' );
			
				$query = $this->mongo_db->select ( array (
						"doc_data" 
				) )->whereBetween('doc_data.widget_data.page1.Student Details.HB_latest.hb',10.1,12)->whereLike ( 'doc_data.widget_data.page1.Student Details.HB_latest.month',$selected_month)->where("doc_data.widget_data.school_details.School Name",$school_name)-> get ( $this->hb_app_col );
				
				$doc_query = array ();
				if ($school_name == "All") {
					if ($district_name != "All") {
						foreach ( $query as $doc ) {
							if (strtolower ( $doc ['doc_data']['widget_data'] ['school_details'] ['District'] ) == strtolower ( $district_name )) {
								array_push ( $doc_query, $doc );
							}
						}
						$query = $doc_query;
					} else {
					}
				} else {
					foreach ( $query as $doc ) {
						
						if (strtolower ( $doc ['doc_data'] ['widget_data']['school_details'] ['School Name'] ) == strtolower ( $school_name )) {
							array_push ( $doc_query, $doc );
						}
					}
					$query = $doc_query;
				}
				return $this->get_drilling_hb_students_prepare_pie_array ( $query, $school_name, $type );
				break;
			
			default :
				;
				break;
		}
	}
	public function get_drilling_hb_students_prepare_pie_array($query, $school_name, $type)
	{
		$search_result = [ ];
		$count = 0;
		
		 if ($query) {
			//ini_set('memory_limit','20G');
			$request = [ ];
			$UI_arr = [ ];
			foreach ( $query as $doc ) {
				
				switch ($type) {
					case "Sevier" :
					
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
					case "NORMAL" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
					
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
					
					case "Moderate" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
						
					case "Mild" :
						
						$hb_ids_arr = explode ( " ", $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Details'] ['Hospital Unique ID'] );
						
						$UI_arr = array_merge ( $UI_arr, $hb_ids_arr );
						
						break;
					
					default :
					break;
				}
		 }
		
			return $UI_arr;
		}
	}
	public function get_drilling_hb_students_docs($_id_array) 
	 {
		$docs = [ ];
		//set_time_limit(0);

		ini_set ( 'memory_limit', '10G' );
		if(isset($_id_array) && !empty($_id_array))
		{
			foreach ( $_id_array as $_id ) 
			{
				$query = $this->mongo_db->select ( array (
						'doc_data.widget_data' 
				) )->where( "doc_data.widget_data.page1.Student Details.Hospital Unique ID", $_id )->get ( $this->hb_app_col );
				//if ($query)

				array_push ( $docs, $query [0] );
			}
		}

		/*$query = $this->mongo_db->select ( array (
						'doc_data.widget_data.page1'
				) )->where( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
		if($query)
		{
			$hb_value = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.HB_values.hb'))->where("doc_data.widget_data.page1.Student Details.Hospital Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'])->get($this->hb_app_col);
			$docs['hb_value'] = $hb_value;
		}*/
		
	
		return $docs;
		
	 }
	 public function get_student_hb_graph_values($hospital_unique_id)
	{

		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.HB_values'))->where( array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $hospital_unique_id))->get($this->hb_app_col);
		
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	public function get_hs_info()
	{
		$query = $this->mongo_db->select(array())->get('hb_sms_count_col');

		return $query;
	}

	public function update_msg_info($unique_id,$message_history,$add_msg_info,$add_msg)
	{
		//echo print_r($add_msg,TRUE);exit();

		$update = array('msg_count' => $add_msg['msg'],
						'ecc_count' => $add_msg['ecc']
			);
		$this->mongo_db->where('Student Unique ID',$unique_id)->set($update)->update('hb_sms_count_col');
		$query = $this->mongo_db->where('Student Unique ID',$unique_id)->push('msg_history' , $add_msg_info)->update('hb_sms_count_col');
		if($query)
		{
			return TRUE;
		}else
		{
			return false;
		}
	}

	public function update_msg_info_bmi($unique_id,$message_history,$add_msg_info,$add_msg)
	{
		//echo print_r($add_msg,TRUE);exit();

		$update = array('msg_count' => $add_msg['msg'],
						'ecc_count' => $add_msg['ecc']
			);
		$this->mongo_db->where('Student Unique ID',$unique_id)->set($update)->update('bmi_sms_count_col');
		$query = $this->mongo_db->where('Student Unique ID',$unique_id)->push('msg_history' , $add_msg_info)->update('bmi_sms_count_col');
		if($query)
		{
			return TRUE;
		}else
		{
			return false;
		}
	}

	public function get_officials_info()
	{

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->select(array('contact_person','mobile_number'))->get('superiors');
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		return $query;
	}

	public function get_hs_info_from_bmi()
	{
		$query = $this->mongo_db->select(array())->get('bmi_sms_count_col');

		return $query;
	}
	/*========weekly doctor visiting start ========= */
		public function get_doctor_visiting_report_date_wise($school_name)
    {
          
          $final_data = array();
          $final_data_doc = array();
          $query = array("doc_data.widget_data.school_details.School Name"=>$school_name);
          $response = $this->mongo_db->command(array('distinct' => "doctor_visiting_reports" ,'key' => "doc_data.widget_data.Student Details.doctor_visiting_date",'query'=>$query));


          foreach ($response['values'] as $dates) {
            
             $getSubmittedDocs = $this->mongo_db->where(array('doc_data.widget_data.Student Details.doctor_visiting_date' =>$dates,"doc_data.widget_data.school_details.School Name"=>$school_name))->count('doctor_visiting_reports');
             
            array_push($final_data,$dates);
            array_push($final_data_doc,$getSubmittedDocs);
              
          }


               
          return [$final_data,$final_data_doc];
          
    }
     public function fetch_student_info_for_doctor_visit($school_name, $unique_id)
    {
        
        $res = $this->mongo_db->select(array('doc_data.widget_data'))->where(array('doc_data.widget_data.page2.Personal Information.School Name' => $school_name, 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=> $unique_id))->get('healthcare2016226112942701');
    
        if($res)
        {
            return $res;
        }
        else
        {
            return false;
        }
    }
    public function fetch_student_attachments($school_name, $unique_id)
    {
        
        $res  = $this->mongo_db->select(array("doc_data.widget_data.Student Details.Hospital Unique ID","doc_properties.doc_id","doc_attachments"))->where(array('doc_data.widget_data.Student Details.Hospital Unique ID' =>$unique_id,"doc_data.widget_data.school_details.School Name"=>$school_name))->get('doctor_visiting_reports');
    
        if(count($res) >0)
        {
            return $res;
        }
        else
        {
            return false;
        }
    }

    function check_if_doc_exists_in_doctor_visiting_student($unique_id)
    {
        $is_exists = $this->mongo_db->where(array('doc_data.widget_data.Student Details.Hospital Unique ID'=> $unique_id))->get('doctor_visiting_reports');
        if($is_exists)
        {
            return TRUE;
        }
        else {
          return FALSE;
        }
        
    }
    // -------------------------------------------------------------------------------

    /**
     * Helper: Get Doctor Submitted Report while he visitng  to school
     *
     * @return array
     *  
     * @author Suman 
     */
    public function submit_doctor_visiting_report($doc_data, $doc_attachments,  $doc_properties, $app_properties, $history)
    {

          $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),'doc_attachments' =>$doc_attachments, "doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);

          $query = $this->mongo_db->insert('doctor_visiting_reports',$doc_data);
          if($query)
              return TRUE;
          else
              return FALSE;
    }
     public function get_dr_attachments_history($unique_id,$doc_id)
    {
        $query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_data.widget_data.Student Details.Hospital Unique ID' => $unique_id,'doc_properties.doc_id' => $doc_id))->get('doctor_visiting_reports');
       
        return $query;
    }
    public function update_doctor_treated_student($doc_attachments,$uniqueId,$doc_id)
    {   
        
        $update_query = $this->mongo_db->where(array('doc_data.widget_data.Student Details.Hospital Unique ID' => $uniqueId, 'doc_properties.doc_id' => $doc_id))->set(array('doc_attachments' =>$doc_attachments))->update('doctor_visiting_reports');
        

        return $update_query;
    }

    public function drill_down_to_doctor_treated_list($school_name,$selectedDate)
    {
          
            
             $getSubmittedDocs = $this->mongo_db->where(array('doc_data.widget_data.Student Details.doctor_visiting_date' =>$selectedDate,"doc_data.widget_data.school_details.School Name"=>$school_name))->get('doctor_visiting_reports');
           
            return $getSubmittedDocs;
          
    }
    public function show_doctor_treated_student(/*$student_id,$doctor_visit_date*/ $doc_id)
    {        
            
       $getSubmittedDocs = $this->mongo_db->select(array(),array('_id'))->where(array('doc_properties.doc_id'=>$doc_id))->get('doctor_visiting_reports');
                        
            return $getSubmittedDocs;
          
    }

	/*========weekly doctor visiting end ========= */

	public function get_schools_health_status_count_model($school_name)
	{
			$all_asthma_cases = array();
			$all_kidney_cases = array();
			$all_scabies_cases = array();
			$all_epilepsy_cases = array();
			$all_bmi_cases = array();
			$all_hb_cases = array();
			$astham_cases_details = array();
			$kidney_cases_details = array();
			$scabies_cases_details = array();
			$epilipsy_cases_details = array();
			$bmi_details = array();
			$bmi_between_15_and_28_details = array();
			$bmi_above_28_details = array();
			$hb_between_7_and_18_details = array();
			$hb_details = array();
			$asthma_count = 0;
			$kidney_count = 0;
			$scabies_count = 0;
			$epilipsy_count = 0;
			$bmi_count = 0;
			$hb_count = 0;
		
			$zone_data = [];

			$all_cases = array();
		
				// Asthma cases
				$asthma_docs = $this->mongo_db->select ( array (
						//"doc_data.widget_data",
						"doc_data.widget_data"
				) )->where(array(
					"doc_data.widget_data.page1.Problem Info.Chronic.Respiratory_system" => "Asthma",
					"doc_data.widget_data.page1.Student Info.School Name.field_ref"=>$school_name
				) )->whereNe(array(
					'doc_data.widget_data.page2.Review Info.Status' => "Cured"
				))->get ( 'healthcare2016531124515424_static_html' );

				// Kidney cases
				$kidney_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->where(array(
					"doc_data.widget_data.page1.Student Info.School Name.field_ref"=>$school_name
				) )->whereNe(array(
					"doc_data.widget_data.page1.Problem Info.Chronic.Kidney" => array(),
					'doc_data.widget_data.page2.Review Info.Status' => "Cured"
				))->get ( 'healthcare2016531124515424_static_html' );

				// Scabies cases
				$scabies_docs = $this->mongo_db->select ( array (
						//"doc_data.widget_data",
						"doc_data.widget_data"
				) )->where(array(
					"doc_data.widget_data.page1.Problem Info.Chronic.Skin" => "Scabies",
					"doc_data.widget_data.page1.Student Info.School Name.field_ref"=>$school_name,
				) )->whereNe(array(
					'doc_data.widget_data.page2.Review Info.Status' => "Cured"
				))->get ( 'healthcare2016531124515424_static_html' );

				// Epilipsy cases
				$epilipsy_docs = $this->mongo_db->select ( array (
						//"doc_data.widget_data",
						"doc_data.widget_data"
				) )->where(array(
					"doc_data.widget_data.page1.Problem Info.Chronic.Central_nervous_system" => "Epilepy",
					"doc_data.widget_data.page1.Student Info.School Name.field_ref"=>$school_name,
				) )->whereNe(array(
					'doc_data.widget_data.page2.Review Info.Status' => "Cured"
				))->get ( 'healthcare2016531124515424_static_html' );

				// Modified code
				$bmi_array_14 = array();
				$bmi_array_15_28 = array();
				$bmi_array_28 = array();
				$bmi_details['bmi_issues_docs'] = 0;
				$bmi_details['bmi_issues_count'] = 0;
				$bmi_between_15_and_28_details['bmi_between_count'] = 0;
				$bmi_above_28_details['bmi_above_count'] = 0;
				$bmi_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->get ( 'healthcare2017617145744625' );

				foreach ($bmi_docs as $bmi_details_info) 
				{
					$end_array = end($bmi_details_info['doc_data']['widget_data']['page1']['Student Details']['BMI_values']);
					
					if($end_array['bmi'] <= 14)
					{
						array_push($bmi_array_14, $bmi_details_info);
						//log_message('error','bmi_array=============15762'.print_r($bmi_array,TRUE));
						$bmi_details['school_name'] = $school_name;
			   			$bmi_details['bmi_issues_docs'] = $bmi_array_14;
						$bmi_details['bmi_issues_count'] = count($bmi_array_14);
						//echo print_r($bmi_details['bmi_issues_count'],TRUE);
						
					}else if($end_array['bmi'] >= 15 && $end_array['bmi'] <= 28 )
					{   
						array_push($bmi_array_15_28, $bmi_details_info);
						$bmi_between_15_and_28_details['school_name'] = $school_name;
			   			$bmi_between_15_and_28_details['bmi_between_docs'] = $bmi_array_15_28;
						$bmi_between_15_and_28_details['bmi_between_count'] = count($bmi_array_15_28);
						
					}else if($end_array['bmi'] > 28)
					{
						array_push($bmi_array_28, $bmi_details_info);
						$bmi_above_28_details['school_name'] = $school_name;
			   			$bmi_above_28_details['bmi_above_docs'] = $bmi_array_28;
						$bmi_above_28_details['bmi_above_count'] = count($bmi_array_28);
					}
				}


				//exit();

				/*
					// BMI cases
				// Getting BMI value LESSTHAN 14 Students
				$bmi_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->whereLte(
					"doc_data.widget_data.page1.Student Details.BMI_values.bmi",14
				)->where(array("doc_data.school_details.School Name"=>$school_name))->get ( 'healthcare2017617145744625' );
	
				// Getting BMI value BETWEEN 14 AND 28 Students
				$bmi_between_14_and_28_docs = $this->mongo_db->select ( array (
						//"doc_data.widget_data",
						"doc_data.widget_data"
				) )->whereBetween(
					"doc_data.widget_data.page1.Student Details.BMI_values.bmi",14,28
				)->where(array("doc_data.school_details.School Name"=>$school_name))->get ( 'healthcare2017617145744625' );
				$bmi_between_15_and_28_details['school_name'] = $school_name;
			    $bmi_between_15_and_28_details['bmi_between_docs'] = $bmi_between_14_and_28_docs;
				$bmi_between_15_and_28_details['bmi_between_count'] = count($bmi_between_14_and_28_docs);


				// Getting BMI value ABOVE 28 Students
				$bmi_above_28_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->whereGte(
					"doc_data.widget_data.page1.Student Details.BMI_values.bmi",28
				)->where(array("doc_data.school_details.School Name"=>$school_name))->get ( 'healthcare2017617145744625' );
				$bmi_above_28_details['school_name'] = $school_name;
			    $bmi_above_28_details['bmi_above_docs'] = $bmi_above_28_docs;
				$bmi_above_28_details['bmi_above_count'] = count($bmi_above_28_docs);*/

				// Getting All BMI report Submitted Students data
				$bmi_total_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->count ( 'healthcare2017617145744625' );

				// Getting HB value LESSTHAN 6 Students
				/*$hb_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->whereLte(
					"doc_data.widget_data.page1.Student Details.HB_values.hb",6
				)->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->get ( 'himglobin_report_col' );
				$hb_details['school_name'] = $school_name;
			    $hb_details['hb_issues_docs'] = $hb_docs;
				$hb_details['hb_issues_count'] = count($hb_docs);

				// Getting HB value BETWEEEN 6 AND 18 Students
				$hb_between_6_and_18_docs = $this->mongo_db->select ( array (
						//"doc_data.widget_data",
						"doc_data.widget_data"
				) )->whereBetween(
					"doc_data.widget_data.page1.Student Details.HB_values.hb",6,18
				)->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->get ( 'himglobin_report_col' );
				$hb_between_7_and_18_details['school_name'] = $school_name;
			    $hb_between_7_and_18_details['hb_between_docs'] = $hb_between_6_and_18_docs;
				$hb_between_7_and_18_details['hb_between_count'] = count($hb_between_6_and_18_docs);*/


				// Modified code
				$hb_array_6 = array();
				$hb_array_7_18 = array();
				$hb_details['hb_issues_docs'] = 0;
				$hb_details['hb_issues_count'] = 0;
				$hb_details['hb_between_count'] = 0;
			
				$hb_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->get ( 'himglobin_report_col' );

				foreach ($hb_docs as $hb_details_info) 
				{
					$end_array = end($hb_details_info['doc_data']['widget_data']['page1']['Student Details']['HB_values']);
					
					if($end_array['hb'] <= 6)
					{
						array_push($hb_array_6, $hb_details_info);
						//log_message('error','bmi_array=============15762'.print_r($bmi_array,TRUE));
						$hb_details['school_name'] = $school_name;
			   			$hb_details['hb_issues_docs'] = $hb_array_6;
						$hb_details['hb_issues_count'] = count($hb_array_6);
					
					}else if($end_array['hb'] >= 7 && $end_array['hb'] <= 18 )
					{   
						array_push($hb_array_7_18, $hb_details_info);
						$hb_between_7_and_18_details['school_name'] = $school_name;
			   			$hb_between_7_and_18_details['hb_between_docs'] = $hb_array_7_18;
						$hb_between_7_and_18_details['hb_between_count'] = count($hb_array_7_18);
						
					}
				}

				
				// fetching all HB report students data
				$hb_total_docs = $this->mongo_db->select ( array (
						"doc_data.widget_data"
				) )->where(array("doc_data.widget_data.school_details.School Name"=>$school_name))->count ( 'himglobin_report_col' );
			
				//$asthma_cases_details['school_name'] = $school_name;
			    $asthma_cases_details['asthma_issues_docs'] = $asthma_docs;
				$asthma_cases_details['asthma_issues_count'] = count($asthma_docs);	
				if($asthma_cases_details['asthma_issues_count']>8)
				{
					$asthma_weight = 3;
				}
				elseif($asthma_cases_details['asthma_issues_count']>4)
				{
					$asthma_weight = 2;
				}
				else
				{
					$asthma_weight = 1;
				}
				//$kidney_cases_details['school_name'] = $school_name;
				$kidney_cases_details['kidney_issues_docs'] = $kidney_docs;
				$kidney_cases_details['kidney_issues_count'] = count($kidney_docs);

				if($kidney_cases_details['kidney_issues_count']>8)
				{
					$kidney_weight = 3;
				}
				elseif($kidney_cases_details['kidney_issues_count']>4)
				{
					$kidney_weight = 2;
				}
				else
				{
					$kidney_weight = 1;
				}
			    
				//$scabies_cases_details['school_name'] = $school;
			    $scabies_cases_details['scabies_issues_docs'] = $scabies_docs;
				$scabies_cases_details['scabies_issues_count'] = count($scabies_docs);
				if($scabies_cases_details['scabies_issues_count']>8 )
				{
					$scabies_weight = 3;
				}
				elseif($scabies_cases_details['scabies_issues_count']>4)
				{
					$scabies_weight = 2;
				}
				else
				{
					$scabies_weight = 1;
				}
				//$epilipsy_cases_details['school_name'] = $school;
			    $epilipsy_cases_details['epilipsy_issues_docs'] = $epilipsy_docs;
				$epilipsy_cases_details['epilipsy_issues_count'] = count($epilipsy_docs);
				if($epilipsy_cases_details['epilipsy_issues_count']>8)
				{
					$epilipsy_weight = 3;
				}
				elseif($epilipsy_cases_details['epilipsy_issues_count']>4)
				{
					$epilipsy_weight = 2;
				}
				else
				{
					$epilipsy_weight = 1;
				}
				//Calculate Grade
				$total_grade = $asthma_weight + $kidney_weight + $scabies_weight + $epilipsy_weight;
				/*echo print_r($bmi_above_28_details['bmi_above_count'],true);
				exit;
*/
				//echo print_r($total_grade,true); $bmi_total_docs == 0 || $hb_total_docs == 0 ||
					if( $bmi_details['bmi_issues_count'] > 0 || $bmi_above_28_details['bmi_above_count'] > 0 || $hb_details['hb_issues_count'] > 0 || $scabies_cases_details['scabies_issues_count'] > 0 || $total_grade > 12){
						$zone1_criteria = array($school_name,$bmi_total_docs==0?"BMI values not submitted till now":"BMI value Less than 14 students count is : ".$bmi_details['bmi_issues_count'],"BMI value Greater than 28 students count is : ".$bmi_above_28_details['bmi_above_count'],$hb_total_docs==0?"HB values not submitted till now": "HB value Less than 6 students count is : ".$hb_details['hb_issues_count'], "Total Asthma cases count is : ".$asthma_cases_details['asthma_issues_count'],"Total Kidney cases count is : ".$kidney_cases_details['kidney_issues_count'],"Total Epilipsy cases count is : ".$epilipsy_cases_details['epilipsy_issues_count'],"Total Scabies count is : ".$scabies_cases_details['scabies_issues_count']);

						//$zone1_students = array($bmi_details['bmi_issues_docs'],$hb_details['hb_issues_docs'],$asthma_cases_details['asthma_issues_count'],$kidney_cases_details['kidney_issues_count'],$epilipsy_cases_details['epilipsy_issues_count'],$scabies_cases_details['scabies_issues_count']);
			
						$zone['status_color'] = "Red"; 
						$zone['criteria'] = $zone1_criteria; 
						/*$zone['bmi_students'] = $bmi_details['bmi_issues_docs']; 
						$zone['hb_students'] = $hb_details['hb_issues_docs']; 
						$zone['asthma_students'] = $asthma_cases_details['asthma_issues_docs']; 
						$zone['kidney_students'] = $kidney_cases_details['kidney_issues_docs']; 
						$zone['epilipsy_students'] = $epilipsy_cases_details['epilipsy_issues_docs']; 
						$zone['scabies_students'] = $scabies_cases_details['scabies_issues_docs'];*/

						array_push($zone_data,$zone);
						/*echo '<pre>';
						echo print_r($zone_data,true);
						echo '</pre>';
						exit;*/
				}
				elseif($total_grade > 8 )//|| $bmi_between_15_and_28_details['bmi_between_count'] > 0 || 
					//$hb_between_7_and_18_details['hb_between_count'] > 0 )
				{
					//YEL
						$zone2_criteria = array($school_name, "BMI values are >15 and <28, Total No: ".$bmi_between_15_and_28_details['bmi_between_count'], "HB values are  >7 and <18 students count is : ".$hb_between_7_and_18_details['hb_between_count'], "Total Asthma cases count is : ".$asthma_cases_details['asthma_issues_count'],"Total Kidney cases count is : ".$kidney_cases_details['kidney_issues_count'],"Total Epilipsy cases count is : ".$epilipsy_cases_details['epilipsy_issues_count'],"Total Scabies cases count is : ".$scabies_cases_details['scabies_issues_count']);
						$zone['status_color'] = "Yellow"; 
						$zone['criteria'] = $zone2_criteria; 
						array_push($zone_data,$zone);

				}
				else
				{
					//GREEN
					$zone3_criteria = array($school_name, "Total Asthma cases count is : ".$asthma_cases_details['asthma_issues_count'],"Total Kidney cases count is :".$kidney_cases_details['kidney_issues_count'],"Total Epilipsy cases count is : ".$epilipsy_cases_details['epilipsy_issues_count'],"Total Scabies cases count is : ".$scabies_cases_details['scabies_issues_count']);
						$zone['status_color'] = "Green"; 
						$zone['criteria'] = $zone3_criteria; 
						array_push($zone_data,$zone);
				}
				array_push($all_cases, $zone);
			return $all_cases;
			
		}

	public function send_sms_for_not_submited_attendance()
	{
		$today_date = date("Y-m-d");
		$schools_array = array();
		$schools_array_col = array();
		$final_data = array();
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data.page1.Attendence Details.Select School"
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
		
		foreach ($query as  $school_names) {
			$school_name = $school_names['doc_data']['widget_data']['page1']['Attendence Details']['Select School'];
			array_push($schools_array, $school_name);
		}
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$res = $this->mongo_db->select(array('school_name'))->get($this->collections['panacea_schools']);
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ($res as $school) {
			$school_name_col = $school['school_name'];
			array_push($schools_array_col, $school_name_col);
		}
		$final_result = array_values(array_diff($schools_array_col, $schools_array));
		foreach ($final_result as $school_name) 
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$pc_result = $this->mongo_db->select(array('school_code','school_mob','contact_person_name'))->where(array('school_name' => $school_name))->get($this->collections['panacea_schools']);
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

			foreach ($pc_result as $school_code)
			{
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$hs_result = $this->mongo_db->select(array('hs_name', 'hs_mob', 'email'))->
				where(array('school_code' => $school_code['school_code']))->get($this->collections['panacea_health_supervisors']);
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

				$rhso_email = explode(".", $hs_result[0]['email']);
				$email = $rhso_email[0].".rhso@gmail.com";

				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$rhso_result = $this->mongo_db->select(array('rhso_name', 'rhso_mobile'))->
				where(array('email' => $email))->get('rhso_users');
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
				if(isset($rhso_result[0]['rhso_name']))
				{
					$data['rhso_name'] = $rhso_result[0]['rhso_name'];
					$data['rhso_mobile'] = $rhso_result[0]['rhso_mobile'];
					$data['hs_name'] = $hs_result[0]['hs_name'];
					$data['hs_mob'] = $hs_result[0]['hs_mob'];
					$data['pc_name'] = $school_code['contact_person_name'];
					$data['pc_mob'] = $school_code['school_mob'];
					$data['school_name'] = $school_name;
					
					array_push($final_data, $data);
				}	
			}
			
		}
		
		return $final_data;		
	}
	/*public function send_sms_for_not_submited_attendance()
	{
//	$today_date = date("Y-m-d");
//	$schools_array = array();
//	$schools_array_col = array();
	$final_data = array();
//	$query = $this->mongo_db->select ( array (
//			"doc_data.widget_data.page1.Attendence Details.Select School"
//	) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->absent_app_col );
//	
//	foreach ($query as  $school_names) {
//		$school_name = $school_names['doc_data']['widget_data']['page1']['Attendence Details']['Select School'];
//		array_push($schools_array, $school_name);
//	}
//	$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
//	$res = $this->mongo_db->select(array('school_name'))->get($this->collections['panacea_schools']);
//	$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
//	foreach ($res as $school) {
//		$school_name_col = $school['school_name'];
//		array_push($schools_array_col, $school_name_col);
//	}
//	$final_result = array_values(array_diff($schools_array_col, $schools_array));
//		foreach ($final_result as $school_name) 
		{
//			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$pc_result = $this->mongo_db->select(array('school_code','school_mob','contact_person_name'))->get($this->collections['panacea_schools']);
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

			foreach ($pc_result as $school_code)
			{
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$hs_result = $this->mongo_db->select(array('hs_name', 'hs_mob', 'email'))->
				where(array('school_code' => $school_code['school_code']))->get($this->collections['panacea_health_supervisors']);
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

				$rhso_email = explode(".", $hs_result[0]['email']);
				$email = $rhso_email[0].".rhso@gmail.com";

				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$rhso_result = $this->mongo_db->select(array('rhso_name', 'rhso_mobile'))->
				where(array('email' => $email))->get('rhso_users');
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
				$data['hs_name'] = $hs_result[0]['hs_name'];
				$data['hs_mob'] = $hs_result[0]['hs_mob'];
				$data['pc_name'] = $school_code['contact_person_name'];
				$data['pc_mob'] = $school_code['school_mob'];
				//$data['school_name'] = $school_name;
				
				array_push($final_data, $data);
			}
			
		}
		
		return $final_data;		
	}*/

	public function send_sms_for_not_submited_sanitation()
	{
		$today_date = date("Y-m-d");		
		$schools_array = array();
		$schools_array_col = array();
		$final_data = array();
		$query = $this->mongo_db->select ( array (
				"doc_data.widget_data.page4.School Information.School Name"
		) )->whereLike ( 'history.last_stage.time', $today_date )->get ( $this->sanitation_report_app_col_v2 );
		
		foreach ($query as  $school_names) {
			$school_name = $school_names['doc_data']['widget_data']['page4']['School Information']['School Name'];
			array_push($schools_array, $school_name);
		}
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$res = $this->mongo_db->select(array('school_name'))->get($this->collections['panacea_schools']);
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ($res as $school) {
			$school_name_col = $school['school_name'];
			array_push($schools_array_col, $school_name_col);
		}
		$final_result = array_values(array_diff($schools_array_col, $schools_array));
		foreach ($final_result as $school_name) 
		{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$pc_result = $this->mongo_db->select(array('school_code','school_mob','contact_person_name'))->where(array('school_name' => $school_name))->get($this->collections['panacea_schools']);
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

			foreach ($pc_result as $school_code)
			{
				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$act_result = $this->mongo_db->select(array('act_name','act_mob', 'email'))->
				where(array('school_code' => $school_code['school_code']))->get('ACT_collection');
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

				$rhso_email = explode(".", $act_result[0]['email']);
				$email = $rhso_email[0].".rhso@gmail.com";

				$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
				$rhso_result = $this->mongo_db->select(array('rhso_name', 'rhso_mobile'))->
				where(array('email' => $email))->get('rhso_users');
				$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
				if(isset($rhso_result[0]['rhso_name']))
				{
					$data['rhso_name'] = $rhso_result[0]['rhso_name'];
					$data['rhso_mobile'] = $rhso_result[0]['rhso_mobile'];
					$data['act_name'] = $act_result[0]['act_name'];
					$data['act_mob'] = $act_result[0]['act_mob'];
					$data['pc_name'] = $school_code['contact_person_name'];
					$data['pc_mob'] = $school_code['school_mob'];
					$data['school_name'] = $school_name;
					
					array_push($final_data, $data);
				}
				/*$data['hs_name'] = $hs_result[0]['hs_name'];
				$data['hs_mob'] = $hs_result[0]['hs_mob'];
				$data['pc_name'] = $school_code['contact_person_name'];
				$data['pc_mob'] = $school_code['school_mob'];
				$data['school_name'] = $school_name;
				
				array_push($final_data, $data);*/
			}
			
		}
		
		return $final_data;		
	}

	public function send_sms_for_health_request()
	{
		$today_date = Date("Y-m-d");
		$full_doc = array();
		$final_date = date("Y-m-d",strtotime($today_date."-40 day"));
		$query['Normal'] = $this->mongo_db->select(array())->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Normal"))->whereLte('history.0.time', $final_date)->get('healthcare2016531124515424_static_html');
		$query['Emergency'] = $this->mongo_db->select(array())->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Emergency"))->whereLte('history.0.time', $final_date)->get('healthcare2016531124515424_static_html');
		$query['Chronic'] = $this->mongo_db->select(array())->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->where(array('doc_data.widget_data.page2.Review Info.Request Type' => "Chronic"))->whereLte('history.0.time', $final_date)->get('healthcare2016531124515424_static_html');
		//echo print_r($query,TRUE);exit();
		/*foreach ($query as $document) {
			$end_array = end($document['history']);
			if(preg_match('/HS/i', $end_array['current_stage']))
			{
				

				array_push($full_doc, $document);
				
			}
		}*/

		return $query;			
		
	}

	function get_treatment_advice_list($school_code)
	{
		$where_query = array('doc_data.widget_data.page7.Colour Blindness.Description' => array(
				'$nin' => array(
					'', '(\nno\n\n\n\n)', '( nil )', '( nil)', '( nill )', '()', '(No\n\n)', '(No\n )', 
					'(No\n)', '(No \n)', '(j9l\n)', '(n\no\n)', '(n)', '(n0\n)', '(n8l\n)', '(n8l )', '(n8l)', '(n9\n)', '(n9l\n)', '(ni l)', '(ni)', '(ni6)', '(nil\n\n)', '(nil\n)', '(nil \n\n)', '(nil \n)', '(nil )', '(nil)', '(nill \n)', '(nill )', '(nio)', '(nip)', '(nl)', '(no\n\n)', '(no\n )', '(no\n)', '(no \n\n)', '(no \n)', '(no )', '(no pic)', '(no)', '(nol)', '(nolan\n)', '(nolan )', '(normal\n)', '(normal \n)', '(np\n )', '(np\n)', 'NEW', 'NIL', 'NILL', 'NO', 'Nill( )', 'Nill()', 'no','(No\n)'
				)
			),
			'doc_data.widget_data.page1.Personal Information.Hospital Unique ID' =>array(
				'$regex' => $school_code."*"
			),
			'doc_data.widget_data.page7.Colour Blindness.Eye Lids' => array('$exists' => TRUE)
		);
		$query = $this->mongo_db->select(array())->where($where_query)->get($this->screening_app_col);
		if(!empty($query))
		{
			return $query;
		}else
		{
			return FALSE;
		}
	}

	function increament_bmi_count($bmi_values_data)
	{
		$bmi_values = $bmi_values_data['BMI_values'][0]['bmi'];
		$school_name = $bmi_values_data['school_details']['School Name'];

		if($bmi_values <= 18.5) 
		{
			$query = $this->mongo_db->where(array('School Status.2019-02-28.BMI.Under Weight' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.BMI.Under Weight' => 1))->update('get_schools_status_collection');
		}elseif ($bmi_values >= 25.0 && $bmi_values <= 29.9) {
			$query = $this->mongo_db->where(array('School Status.2019-02-28.BMI.Over Weight' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.BMI.Over Weight' => 1))->update('get_schools_status_collection');
		}
		elseif ($bmi_values >= 30) {
			$query = $this->mongo_db->where(array('School Status.2019-02-28.BMI.Obese' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.BMI.Obese' => 1))->update('get_schools_status_collection');
		}

	}

	function increament_hb_count($hb_values_data)
	{
		$school_name = $hb_values_data['school_details']['School Name'];
		$hb_value = $hb_values_data['HB_values'][0]['hb'];

		if($hb_value <= 8)
		{		
			$query = $this->mongo_db->where(array('School Status.2019-02-28.HB.Severe' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.HB.Severe' => 1))->update('get_schools_status_collection');
			
		}elseif ($hb_value >= 8.1 && $hb_value <= 10) {
			$query = $this->mongo_db->where(array('School Status.2019-02-28.HB.Moderate' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.HB.Moderate' => 1))->update('get_schools_status_collection');
		}elseif ($hb_value >= 10.1 && $hb_value <= 12)
		{
			$query = $this->mongo_db->where(array('School Status.2019-02-28.HB.Mild' => array('$exists' => TRUE),'School Details.School Name' => $school_name))->inc(array('School Status.2019-02-28.HB.Mild' => 1))->update('get_schools_status_collection');
		}
	}

	public function insert_attendance_info($data)
	{
		$today_date = Date("Y-m-d");
		$get_count  = $this->mongo_db->where(array('Messages_info.school_name' => $data['school_name']))->count('Attendance_msg_count');
		if($get_count != 0)
		{
			$query = $this->mongo_db->push('Sent_dates', $today_date)->inc(array('Sent_count' => 1))->update('Attendance_msg_count');
		}else
		{
			$today_date  = Date("Y-m-d");
			$insert_data = array('Messages_info' => $data,'Sent_count' => 1,'Sent_dates' => [$today_date],'Type_of_Msg' => "Attendance Not Submitted");
			$query = $this->mongo_db->insert("Attendance_msg_count", $insert_data);	
		}

		$over_all_count = $this->mongo_db->where(array("$today_date.Attendance_Messages_info.Sent_date" => $today_date))->count('Over_all_Msg_status');

		if($over_all_count != 0)
		{
			$query = $this->mongo_db->where(array("$today_date.Attendance_Messages_info.Sent_date" => $today_date))->push("$today_date.Attendance_Messages_info.school_name", $data['school_name'])->inc(array("$today_date.Attendance_Messages_info.Sent_count" => 1))->update('Over_all_Msg_status');
		}else
		{
			$over_all_status['Attendance_Messages_info'] = array(
				'message_hs' => $data['message_hs'],
				'message_principal' => $data['message_principal'],
				'message_rhso' => $data['message_rhso'],
				'school_name' => [$data['school_name']],
				'Sent_count' => 1,
				'Sent_date' => $today_date,
				'Type_of_Msg' => "Attendance Not Submitted"
			);

			$insert_over_data = array($today_date => $over_all_status);
			$query = $this->mongo_db->insert('Over_all_Msg_status', $insert_over_data);
		}

		return $query;
	}

	public function insert_sanitation_info($data)
	{
		$today_date = Date("Y-m-d");
		$get_count  = $this->mongo_db->where(array('Messages_info.school_name' => $data['school_name']))->count('Sanitation_msg_count');

		if($get_count != 0)
		{
			$query = $this->mongo_db->push('Sent_dates', $today_date)->inc(array('Sent_count' => 1))->update('Sanitation_msg_count');
		}else
		{
			$today_date  = Date("Y-m-d");
			$insert_data = array('Messages_info' => $data,'Sent_count' => 1,'Sent_dates' => [$today_date],'Type_of_Msg' => "Sanitation Not Submitted");
			$query = $this->mongo_db->insert("Sanitation_msg_count", $insert_data);	
		}

		$over_all_count = $this->mongo_db->where(array("$today_date.Sanitation_Messages_info.Sent_date" => $today_date))->count('Over_all_Msg_status');

		if($over_all_count != 0 )
		{
			$query = $this->mongo_db->where(array("$today_date.Sanitation_Messages_info.Sent_date" => $today_date))->push("$today_date.Sanitation_Messages_info.school_name", $data['school_name'])->inc(array("$today_date.Sanitation_Messages_info.Sent_count" => 1))->update('Over_all_Msg_status');
		}else
		{
			$over_all_status['Sanitation_Messages_info'] = array(
				'message_act' => $data['message_act'],
				'message_principal' => $data['message_principal'],
				'message_principal' => $data['message_rhso'],
				'school_name' => [$data['school_name']],
				'Sent_count' => 1,
				'Sent_date' => $today_date,
				'Type_of_Msg' => "Sanitation Not Submitted"
			);

			$insert_over_data = array($today_date => $over_all_status);
			$query = $this->mongo_db->insert('Over_all_Msg_status', $insert_over_data);
		}

		return $query;
	}

	public function add_doctor_profile_model($history,$doc_properties,$doc_data)
	{		
	  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history,"doc_properties"=>$doc_properties);
	  $query = $this->mongo_db->insert('ts_doctor_personal_profile_report', $doc_data);
	  if($query){
		  return TRUE;
	  }
	  else{
		  return FALSE;
	  }
	}

	public function get_doctor_names($email)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Personal Information.Name'))->where(array("history.last_stage.submitted_by" => str_replace("@", "#", $email)))->get('ts_doctor_personal_profile_report');
		return $query;
	}


	 public function insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties)
    {
    	
        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id'   => "healthcare2016531124515424_static_html",
                        'time' => date('Y-m-d H:i:s'),
                        'status'   => "new"
                              );

        $doc_datas = array('doc_data' => $doc_data, "doc_id"=>$doc_id, 'doc_properties'=>$doc_properties, 'app_properties' =>  $app_properties,'history' => $approval_history);    

      $resp =	$this->mongo_db->insert('ts_hospitalised_students_col', $doc_datas);

        //return $inserted;

    }

     public function update_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties)
    {
    	
        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id'   => "healthcare2016531124515424_static_html",
                        'time' => date('Y-m-d H:i:s'),
                        'status'   => "new"
                              );
 		$doc_data_info['widget_data'] = $doc_data;

        $doc_datas = array('doc_data' => $doc_data_info, "doc_id"=>$doc_id, 'doc_properties'=>$doc_properties, 'app_properties' =>  $app_properties,'history' => $approval_history);    

      $resp =	$this->mongo_db->insert('ts_hospitalised_students_col', $doc_datas);

        //return $inserted;

    }

    

    public function check_doc_id_of_request($doc_id)
    {
        $query = $this->mongo_db->where('doc_id', $doc_id)->get('ts_hospitalised_students_col');

        if(!empty($query)){
            return $query;
        }else{
            return 'No Doc Found';
        }
    }

    public function get_doc_id_for_check($initate_submit)
	{
		//echo print_r($initate_submit, true);
		$get_id = $this->mongo_db->select('doc_properties.doc_id')->where ( '_id', $initate_submit )->get('healthcare2016531124515424_static_html');
	
		$id = $get_id[0]['doc_properties']['doc_id'];
	
		return $id;

	}

	public function create_medicine_inventory($data_sent, $school)
	 {
		$this->load->config ( 'ion_auth', TRUE );
		$hist = array(
					'registered_on' => date ( "Y-m-d" ),
					'last_login' => date ( "Y-m-d H:i:s" ),
					'active' => 1
					);
		
		$doc_data['doc_data']['widget_data']['page1']['School Info'] = $school;
		$doc_data['doc_data']['widget_data']['page2']['medicine_names'] = $data_sent;
		$doc_data['history'] = $hist;
		
		$query = $this->mongo_db->insert ( 'medicine_list_inventory_ts', $doc_data );
		//$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		log_message('error',"medicine_inventory_db========db".print_r($query, true));

		return isset ( $query ) ? $query : FALSE;
	}

	public function get_medicine_inventorylist()
	{
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );		
		$query = $this->mongo_db->get ( 'medicine_list_inventory_ts' );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}

	public function medicine_inventory_list($school_code)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page2'))->where('doc_data.widget_data.page1.School Info.School Code', $school_code )->get('medicine_list_inventory_ts');

			
		return $query;			
	}

	public function get_import_medicine() 
	{
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			$count = $this->mongo_db->count ('medicine_list_inventory_ts');
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $count;
	}

	/*public function get_today_news_feeds($date){
		
		if(!$date){
			$date = date('Y-m-d');
		}
		
		$display_date = array (
				"display_date" => array (
						'$regex' => $date
				) 
		);
		$result = [ ];
			
			$pipeline = [ 
					
					array('$match' => $display_date)
					
			];
			
			$response = $this->mongo_db->command ( array (
					'aggregate' => $this->collections ['panacea_news_feed'],
					'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
	
		return $query;
	
	}*/

	/*public function add_news_feed($news_data){
		
		$query = $this->mongo_db->insert ( $this->collections ['panacea_news_feed'], $news_data );
	
		return $query;
	
	}
	
	public function get_all_news_feeds(){
	
		$query = $this->mongo_db->get ( $this->collections ['panacea_news_feed'] );
	
		return $query;
	
	}
	
	public function delete_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->delete($this->collections['panacea_news_feed']);
		return $query;
	}
	
	public function get_news_feed($nf_id)
	{
		$query = $this->mongo_db->where(array("_id"=>new MongoId($nf_id)))->get($this->collections['panacea_news_feed']);
		return $query[0];
	}
	
	public function update_news_feed($news_data,$news_id)
	{
		
		$query = $this->mongo_db->where(array("_id"=>new MongoId($news_id)))->set($news_data)->update($this->collections['panacea_news_feed']);
		return $query;
	}*/

	
}
