<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tmreis_schools_common_model extends CI_Model {
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
		$this->screening_app_col_screening = $email."_pie_analytics";
		
		$this->screening_app_col = 'healthcare201672020159570';
        $this->screening_staff_app_col = 'healthcare201672020159570_staff';
		$this->absent_app_col    = "healthcare2017120192713965";
		$this->request_app_col   = "healthcare201610114435690";
		$this->sanitation_infrastructure_app_col = "healthcare2017127194550376";
		$this->sanitation_report_app_col         = "healthcare2017121175645993";
		$this->hb_app_col         = "tmreis_himglobin_report_col";
		$this->notes_col = "tmreis_ehr_notes";
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
		$res = $this->mongo_db->where(array('school_code' => $school_code))->get($this->collections['tmreis_schools']);
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
			$dates ['end_date'] = $end_date;
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
			
			$end_date = date ( "Y-m-d H:i:s", strtotime ( $today_date . "-1 year" ) );
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
		
		if ($count < 5000) 
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
		
		$search_result = array();
		
		foreach($result as $doc)
		{
		  array_push($search_result,$doc ['_id']->{'$id'} );
		}
		
		$request["Under Weight"]  = $search_result;
		
		// ========================================================================================
		// ========================================================================================
		
		$merged_array = array();
		
		$underweight_array = array (
				"doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array (
						'$in' => array (
								"Obese" 
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
									'$and' => $merged_array 
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
		
		$request["Obese"]  = $search_result;
	//=======================================================================	
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
		//=================Skin Conditions=======//
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Acne on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
			
			log_message("debug","today_date===========1870".print_r($dates ['today_date'],true));
			
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
		
		$request["Acne on Face"] = $search_result;
		//====================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hyper Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Hyper Pigmentation"] = $search_result;
		
		//===================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Danddruff"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Danddruff"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Greying Hair"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Greying Hair"] = $search_result;
		
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "ECCEMA"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["ECCEMA"] = $search_result;
		
		
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Molluscum"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Molluscum"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Cracked Feet"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Cracked Feet"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Cruris"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Taenia Cruris"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hansens Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Hansens Disease"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hypo Pigmentation"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Hypo Pigmentation"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Nail Bed Disease"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Nail Bed Disease"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Psoriasis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Psoriasis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Hyperhidrosis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Hyperhidrosis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Scabies"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Scabies"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Allergic Rash"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Allergic Rash"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Corporis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Taenia Corporis"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "White Patches on Face"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["White Patches on Face"] = $search_result;
		
		////=====================================
		$and_merged_array = array ();
		
		$skin_conditions = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$eq' => "Taenia Facialis"
				) 
		);
		
		$page4_exists = array (
				"doc_data.widget_data.page4.Doctor Check Up.Skin conditions" => array (
						'$exists' => true 
				) 
		);
		
		$schoolwise_check = array (
				"doc_data.widget_data.page2.Personal Information.School Name" => $school_name
				);
		
		
		array_push ( $and_merged_array, $skin_conditions );
		array_push ( $and_merged_array, $page4_exists );
		array_push ( $and_merged_array, $schoolwise_check );
		
		$result = [ ];
		
		for($page = 1; $page < $loop; $page ++) 
		{
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
		
		$request["Taenia Facialis"] = $search_result;
		
		//======================================================
		
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
		
		log_message('debug','screening_pie_data_for_stage2_new=====453=='.print_r($requests,true));
		
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
		$stage_array ["Physical Abnormalities"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Obese"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Physical Abnormalities"] ["label"] = "Obese";
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
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Acne on Face"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Acne on Face";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hyper Pigmentation"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hyper Pigmentation";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Greying Hair"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Greying Hair";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Danddruff"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Danddruff";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Facialis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Facialis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["White Patches on Face"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "White Patches on Face";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Corporis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Corporis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Allergic Rash"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Allergic Rash";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Scabies"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Scabies";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hyperhidrosis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hyperhidrosis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Psoriasis"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Psoriasis";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Nail Bed Disease"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Nail Bed Disease";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hypo Pigmentation"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hypo Pigmentation";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Hansens Disease"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Hansens Disease";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Taenia Cruris"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Taenia Cruris";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Cracked Feet"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Cracked Feet";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["Molluscum"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "Molluscum";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
		array_push ( $request_stage2, $stage_array );
		
		// =====
		$stage_array = [ ];
		$stage_array ["Skin Conditions"] = [ ];
		
		$total_count = 0;
		foreach ( $requests ["ECCEMA"] as $doc ) 
		{
			$total_count = $total_count + count($doc);
		}
		
		$stage_array ["Skin Conditions"] ["label"] = "ECCEMA";
		$stage_array ["Skin Conditions"] ['value'] = $total_count;
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
		
		log_message('debug','screening_pie_data_for_stage1_new====2122=='.print_r($requests,true));
		
		$stage_data = [ ];
		$stage_data ['label'] = "Physical Abnormalities";
		$stage_data ['value'] = $requests[0]["Physical Abnormalities"]['value'] + $requests[1]["Physical Abnormalities"]['value'] + $requests[2]["Physical Abnormalities"]['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "General Abnormalities";
		$stage_data ['value'] = $requests [3] ["General Abnormalities"] ['value'] + $requests [4] ["General Abnormalities"] ['value'] + $requests [5] ["General Abnormalities"] ['value'] + $requests [6] ["General Abnormalities"] ['value'] + $requests [7] ["General Abnormalities"] ['value'] + $requests [8] ["General Abnormalities"] ['value'] + $requests [9] ["General Abnormalities"] ['value'] + $requests [10] ["General Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Eye Abnormalities";
		$stage_data ['value'] = $requests [11] ["Eye Abnormalities"] ['value'] + $requests [12] ["Eye Abnormalities"] ['value'] + $requests [13] ["Eye Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Auditory Abnormalities";
		$stage_data ['value'] = $requests [14] ["Auditory Abnormalities"] ['value'] + $requests [15] ["Auditory Abnormalities"] ['value'] + $requests [16] ["Auditory Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [ ];
		$stage_data ['label'] = "Dental Abnormalities";
		$stage_data ['value'] = $requests [17] ["Dental Abnormalities"] ['value'] + $requests [18] ["Dental Abnormalities"] ['value'] + $requests [19] ["Dental Abnormalities"] ['value'] + $requests [20] ["Dental Abnormalities"] ['value'] + $requests [21] ["Dental Abnormalities"] ['value'] + $requests [22] ["Dental Abnormalities"] ['value'];
		array_push ( $request_stage1, $stage_data );
		
		$stage_data = [];
		$stage_data ['label'] = "Skin Conditions";
		$stage_data ['value'] = $requests [23] ["Skin Conditions"] ['value'] + $requests [24] ["Skin Conditions"] ['value'] + $requests [25] ["Skin Conditions"] ['value'] + $requests [26] ["Skin Conditions"] ['value'] + $requests [27] ["Skin Conditions"] ['value'] + $requests [28] ["Skin Conditions"] ['value'] + $requests [29] ["Skin Conditions"] ['value'] + $requests [30] ["Skin Conditions"] ['value'] + $requests [31] ["Skin Conditions"] ['value'] + $requests [32] ["Skin Conditions"] ['value'] + $requests [33] ["Skin Conditions"] ['value'] + $requests [34] ["Skin Conditions"] ['value'] + $requests [35] ["Skin Conditions"] ['value'] + $requests [36] ["Skin Conditions"] ['value'] + $requests [37] ["Skin Conditions"] ['value'] + $requests [38] ["Skin Conditions"] ['value'] + $requests [39] ["Skin Conditions"] ['value'] + $requests [40] ["Skin Conditions"] ['value'] ;
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
		$query = $this->mongo_db->get ( $this->collections ['tmreis_schools'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		foreach ( $query as $schools => $school ) {
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $school ['dt_name'] )) {
				$query [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$query [$schools] ['dt_name'] = "No state selected";
			}
		}
		return $query;
	}
	public function classescount($school_code) {
		$count = $this->mongo_db->where('school_code',$school_code)->count ($this->collections['tmreis_classes']);
		return $count;
	}
	
	public function get_classes($per_page, $page, $school_code) 
	{
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->where('school_code',$school_code)->get ( $this->collections['tmreis_classes']);
		return $query;
	}
	
	public function sectionscount($school_code) {
		$count = $this->mongo_db->where('school_code',$school_code)->count ($this->collections['tmreis_sections']);
		return $count;
	}
	public function get_sections($per_page, $page, $school_code) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->where('school_code',$school_code)->get ( $this->collections['tmreis_sections']);
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
		
		log_message('debug','query=====680=='.print_r($query,true));
		log_message('debug','uid=====680=='.print_r($uid,true));
		log_message('debug','school_name=====680=='.print_r($school_name,true));
		
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
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			return $result;
		} else {
			$result ['screening'] = false;
			$result ['request'] = false;
			$result ['notes'] = false;
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
		// log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
		$per_page = 1000;
		$loop = $count / $per_page;
		
		$result = [ ];
		for($page = 1; $page < $loop; $page ++) {
			$offset = $per_page * ($page);
			// log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($page,true));
			// log_message("debug","oooooooooooooooooooooooooooooooooooooooooo".print_r($offset,true));
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
			// log_message("debug","response=====1643==".print_r($response,true));
			// log_message("debug","response=====1643==".print_r(count($response['result']),true));
			// log_message("debug","ppppppppppppppppppppppppppppppppppppppppp".print_r($result,true));
		}
		//
		// log_message("debug","response=====1643==".print_r(count($response['result']),true));
		log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $result, true ) );
		
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
				$screening_doc = $this->mongo_db->select ( array (
						'doc_data.widget_data.page2' 
				) )->where ( array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'=>$unique_id))->get ( $this->screening_app_col );
				
				if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
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
		
		log_message('debug','$schools_data=====get_absent_pie_schools_data=====716=='.print_r($query,true));
		log_message('debug','$schools_data=====get_absent_pie_schools_data=====717=='.print_r($today_date,true));
		
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
		
		log_message('debug','$schools_data=====get_absent_pie_schools_data=====735=='.print_r($schools_data,true));
		log_message('debug','$schools_data=====get_absent_pie_schools_data=====736=='.print_r(gettype($schools_data['submitted']),true));
		
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
		
		$absent   = 0;
		$sick     = 0;
		$restRoom = 0;
		$r2h      = 0;
		
		foreach ( $query as $report ) 
		{
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


		log_message('debug','get_all_absent_data====3201=='.print_r($requests,true));
		
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
		// //log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
		ini_set ( 'memory_limit', '1G' );
		$type = $obj_data [0];
		$dist = strtolower ( $obj_data [1] );
		if ($date) {
			$today_date = $date;
		} else {
			$today_date = $this->today_date;
		}
		// //log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
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
			) )->where/* Like */ ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id )->get ( $this->screening_app_col );
			if ($query)
				array_push ( $docs, $query [0] );
		}
		log_message('debug','drill_down_absent_to_students_load_ehr=====docs=====1356====='.print_r($docs,true));
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
		// //log_message("debug","2222222222222222222222222222222222222222222222222".print_r($query,true));
		$search_result = [ ];
		$count = 0;
		if ($query) {
			foreach ( $query as $doc ) {
				// //log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
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
		log_message('debug','$id_for_school=====3900====='.print_r($id_for_school,true));
		log_message('debug','$unique_id_pattern=====3901====='.print_r($unique_id_pattern,true));
		
		if ($id_for_school) {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->whereIn ( "doc_data.widget_data.page1.Problem Info.Identifier", array (
					$id_for_school 
			) )->get ( $this->request_app_col );
			log_message('debug','$query query =====3861====='.print_r($query,true));
			log_message('debug','$id_for_school =====3862====='.print_r($id_for_school,true));
		} else {
			$query = $this->mongo_db->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->select ( array (
					"doc_data.widget_data",
					"history" 
			) )->get ( $this->request_app_col );
			
			log_message('debug','$unique_id_pattern=====3914====='.print_r(count($query),true));
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
		
		log_message('debug','$unique_id_pattern=====3943====='.print_r($query,true));
		
		foreach ( $query as $doc ) {
				
			$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			$screening_doc = $this->mongo_db->select ( array (
					'doc_data.widget_data.page2' 
			) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
			
			if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
				array_push ( $doc_query, $doc );
				
			}
		}
		
		$query = $doc_query;
		
		log_message('debug','$unique_id_pattern=====3960====='.print_r(count($query),true));
		
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
					} else {
						$device_initiated ++;
					}
				} else {
					$device_initiated ++;
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
	    log_message('debug','get_all_requests_docs====4067==>'.print_r($type,true));
	    log_message('debug','get_all_requests_docs====4068==>'.print_r($unique_id_pattern,true));
		
		if ($type == "Initiated") 
		{
			$query = $this->mongo_db->where ( array (
					'doc_data.widget_data.page2.Review Info.Status' => $type
			) )->whereLike("doc_data.widget_data.page1.Student Info.Unique ID",$unique_id_pattern,'i')->get($this->request_app_col);
			
			log_message('debug','get_all_requests_docs====4076==>'.print_r($query,true));
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
		}
		
		$doc_query = array ();
		
		foreach ( $query as $doc ) 
		{
			$unique_id = $doc ['doc_data'] ['widget_data'] ['page1'] ['Student Info'] ['Unique ID'];
			log_message('debug','get_all_requests_docs====4112==>'.print_r($unique_id,true));
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
			foreach ( $doc ['history'] as $date ) 
			{
				$time = $date ['time'];
				log_message('debug','get_all_requests_docs====4131==>'.print_r($time,true));
				
				if (($time <= $start_date) && ($time >= $end_date)) {
				log_message('debug','get_all_requests_docs====4134==>'.print_r($start_date,true));
				log_message('debug','get_all_requests_docs====4135==>'.print_r($end_date,true));
					array_push ( $result, $doc );
					break;
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $dt_name, $school_name );
			
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
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated", $school_name, $id_pattern);
			
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
		} 
		else if ($type == "Web Initiated") 
		{
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Initiated",$school_name, $id_pattern);
			
			$query = [ ];
			foreach ( $query_temp as $report ) {
				
				if (isset ( $report ['history'] [0] ['submitted_user_type'] )) {
					$user_type = $report ['history'] [0] ['submitted_user_type'];
					if ($user_type == "CCUSER") {
						array_push ( $query, $report );
					}
				}
			}
		} 
		else if ($type == "Screening Initiated") 
		{
			$query_temp = $this->get_all_requests_docs ( $dates ['today_date'], $dates ['end_date'], "Screening", $school_name, $id_pattern);
			
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
			}
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
		log_message ( "debug", "dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd" . print_r ( $_id_array, true ) );
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
		log_message ( 'debug', 'drilldown_identifiers_to_districts==dddddddddddddddddddddddddddddddddddddddd----' . print_r ( $dt_name, true ) );
		log_message ( 'debug', 'ssssssssssssssssssssssssssssssssssssssss----' . print_r ( $school_name, true ) );
		log_message ( 'debug', 'dttttttttttttttttttttttttttttttttttttttt----' . print_r ( $data, true ) );
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
		
		log_message ( 'debug', 'drilldown_identifiers_to_districts=====type----' . print_r ( $type, true ) );
		
		$dates = $this->get_start_end_date ( $today_date, $request_duration );
		$query = $this->get_all_symptoms_docs ( $dates ['today_date'], $dates ['end_date'], $type );
		
		log_message ( 'debug', 'drilldown_identifiers_to_districts----query-----' . print_r ( $query, true ) );
		
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
					log_message ( 'debug', 'drilldown_identifiers_to_districts----unique_id-----' . print_r ( $unique_id, true ) );
					$screening_doc = $this->mongo_db->select ( array (
							'doc_data.widget_data.page2' 
					) )->where ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
					log_message ( 'debug', 'drilldown_identifiers_to_districts----screening_doc-----' . print_r ( $screening_doc, true ) );
					if (isset ( $screening_doc ) && ! empty ( $screening_doc ) && (count ( $screening_doc ) > 0)) {
						
							array_push ( $doc_query, $doc );
					}
				}
				log_message ( 'debug', 'drilldown_identifiers_to_districts----doc_query-----' . print_r ( $doc_query, true ) );
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
			log_message ( 'debug', 'unique_id----' . print_r ( $unique_id, true ) );
			$doc = $this->mongo_db->where/*Like*/ ( 'doc_data.widget_data.page1.Personal Information.Hospital Unique ID', $unique_id )->get ( $this->screening_app_col );
		    log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
			log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd".print_r(count($doc),true));
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
		log_message("debug","matching_docs=========4685".print_r($matching_docs,true));
		return $matching_docs;
	}
	
	public function get_drilling_identifiers_students_docs($_id_array) {
		$docs = [ ];
		log_message('debug','_id_array get_drilling_identifiers_students_docs===tmreis==4689'.print_r($_id_array,true));
		foreach ( $_id_array as $_id ) {
			$query = $this->mongo_db->select ( array (
					'doc_data.widget_data.page1',
					'doc_data.widget_data.page2' 
			) )->where ( "_id", new MongoID ( $_id ) )->get ( $this->screening_app_col );
			array_push ( $docs, $query [0] );
		}
		log_message('debug','get_drilling_identifiers_students_docs===tmreis==4710'.print_r($docs,true));
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
		
		// log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddddddddddddlist--------'.print_r($dist_list,true));
		foreach ( $dist_list as $dist ) {
			// log_message('debug','ddddddddddddddddddddddddddddddddddddddd--------------'.print_r(strtolower($dist["dt_name"]),true));
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
		$requests ['Skin Conditions']   = 0;
		
		foreach ( $pie_data as $each_pie ) {
			
			$requests ['Physical Abnormalities'] = $requests ['Physical Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [0] ['value'];
			$requests ['General Abnormalities']  = $requests ['General Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [1] ['value'];
			$requests ['Eye Abnormalities']      = $requests ['Eye Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [2] ['value'];
			$requests ['Auditory Abnormalities'] = $requests ['Auditory Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [3] ['value'];
			$requests ['Dental Abnormalities']   = $requests ['Dental Abnormalities'] + $each_pie ['pie_data'] ['stage1_pie_values'] [4] ['value'];
			$requests ['Skin Conditions']  		 = $requests ['Skin Conditions'] + $each_pie ['pie_data'] ['stage1_pie_values'] [5] ['value'];
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
else {
			$today_date = $this->today_date;
		}
		
		$obj_data = json_decode ( $data, true );
		$type = $obj_data ['label'];
		
		$dates = $this->get_start_end_date ( $today_date, $screening_duration );
		ini_set ( 'memory_limit', '10G' );
		
		$pie_data = $this->mongo_db->select ( array (
				'pie_data.stage2_pie_values' 
		) )->whereBetween ( 'pie_data.date', $dates ['end_date'], $dates ['today_date'] )->get ( $this->screening_app_col_screening );
		
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
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [1] ['Physical Abnormalities'] ['value'];
				}
				
				array_push ( $requests, $request );
				
				$request ['label'] = 'Obese';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [2] ['Physical Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [3] ['General Abnormalities'] ['value'];
					log_message("debug","each_pie=======9831".print_r($each_pie,true));
					log_message("debug","request value=======9831".print_r($request ['value'],true));
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Skin';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [4] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Others(Description/Advice)';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [5] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Ortho';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [6] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Postural';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [7] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Defects at Birth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [8] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Deficencies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [9] ['General Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Childhood Diseases';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [10] ['General Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [11] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'With Glasses';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [12] ['Eye Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Colour Blindness';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [13] ['Eye Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [14] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Left Ear';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [15] ['Auditory Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Speech Screening';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [16] ['Auditory Abnormalities'] ['value'];
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
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [17] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Oral Hygiene - Poor';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [18] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Carious Teeth';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [19] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Flourosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [20] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Orthodontic Treatment';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [21] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Indication for extraction';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [22] ['Dental Abnormalities'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
				
				case "Skin Conditions" : 
				$requests = [ ];
				
				$request ['label'] = 'Acne on Face';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					log_message("debug","eachhhhhhhhhhhhhhhhhhhhhhhhh========10037".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [23] ['Skin Conditions'] ['value'];
					log_message("debug","request value===========10040".print_r($request ['value'],true));
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hyper Pigmentation';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [24] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Danddruff';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [25] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Greying Hair';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [26] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'ECCEMA';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [27] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Molluscum';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [28] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Cracked Feet';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [29] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Taenia Cruris';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [30] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hansens Disease';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [31] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hypo Pigmentation';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [32] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Nail Bed Disease';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [33] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Psoriasis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [34] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Hyperhidrosis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [35] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Scabies';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [36] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Allergic Rash';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [37] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Taenia Corporis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [38] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'White Patches on Face';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [39] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				$request ['label'] = 'Taenia Facialis';
				$request ['value'] = 0;
				
				foreach ( $pie_data as $each_pie ) {
					// log_message("debug","pppppppppppppppppppppppppppppppp=====".print_r($each_pie,true));
					$request ['value'] = $request ['value'] + $each_pie ['pie_data'] ['stage2_pie_values'] [40] ['Skin Conditions'] ['value'];
				}
				array_push ( $requests, $request );
				
				return $requests;
				break;
			
			default :
				break;
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
		// //log_message("debug","cccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
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
				log_message ( "debug", "iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================" );
				
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
				// //log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
				
				// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name","TSWRS-CHITKUL,MEDAK")->get($this->screening_app_col);
				// foreach ($query as $doc){
				
				// $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'] = "tmreis CHITKUL(G),MEDAK";
				
				// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
				// //log_message("debug","iiiiiiiiiiiiiinnnnnnnnnnncapssssssssssssssss========================");
				
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
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
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
				
				case "Obese" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Obese"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Obese"] ))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Obese"]);
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
				
				case "Acne on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Acne on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Acne on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Acne on Face"] );
				}
				
				return $requests;
				break;
				
				case "Hyper Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Hyper Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Hyper Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Hyper Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Danddruff" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Danddruff"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Danddruff"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Danddruff"] );
				}
				
				return $requests;
				break;
				
				case "Greying Hair" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Greying Hair"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Greying Hair"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Greying Hair"] );
				}
				
				return $requests;
				break;
				
				case "ECCEMA" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["ECCEMA"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["ECCEMA"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["ECCEMA"] );
				}
				
				return $requests;
				break;
				
				case "Molluscum" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Molluscum"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Molluscum"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Molluscum"] );
				}
				
				return $requests;
				break;
				
				case "Cracked Feet" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Cracked Feet"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Cracked Feet"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Cracked Feet"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Cruris" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Cruris"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Cruris"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Cruris"] );
				}
				
				return $requests;
				break;
				
				case "Hansens Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Hansens Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Hansens Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Hansens Disease"] );
				}
				
				return $requests;
				break;
				
				case "Hypo Pigmentation" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Hypo Pigmentation"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Hypo Pigmentation"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Hypo Pigmentation"] );
				}
				
				return $requests;
				break;
				
				case "Nail Bed Disease" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Nail Bed Disease"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Nail Bed Disease"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Nail Bed Disease"] );
				}
				
				return $requests;
				break;
				
				case "Psoriasis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Psoriasis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Psoriasis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Psoriasis"] );
				}
				
				return $requests;
				break;
				
				case "Hyperhidrosis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Hyperhidrosis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Hyperhidrosis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Hyperhidrosis"] );
				}
				
				return $requests;
				break;
				
				case "Scabies" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Scabies"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Scabies"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Scabies"] );
				}
				
				return $requests;
				break;
				
				case "Allergic Rash" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Allergic Rash"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Allergic Rash"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Allergic Rash"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Corporis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Corporis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Corporis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Corporis"] );
				}
				
				return $requests;
				break;
				
				case "White Patches on Face" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["White Patches on Face"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["White Patches on Face"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["White Patches on Face"] );
				}
				
				return $requests;
				break;
				
				case "Taenia Facialis" :
				
				$requests = [ ];
				foreach ( $pie_data as $each_pie ) {
					if ($each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Facialis"] != null && is_array ( $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Facialis"]))
						$requests = array_merge_recursive ( $requests, $each_pie ['pie_data'] ['stage3_pie_values'] ["Taenia Facialis"] );
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
		// log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
		
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
			
			log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $request, true ) );
			$final_values = [ ];
			foreach ( $request as $school => $count ) {
				log_message ( "debug", "schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo" . print_r ( $school, true ) );
				log_message ( "debug", "ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc" . print_r ( $count, true ) );
				$result ['label'] = $school;
				$result ['value'] = $count;
				array_push ( $final_values, $result );
			}
			
			log_message ( "debug", "fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" . print_r ( $final_values, true ) );
			
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
			) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['tmreis_schools'] );
			$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
			return $query;
		}
	}
	public function get_students_by_school_name($school_name, $dist_name) {
		if ($school_name == "All") {
			// log_message("debug","111111111111111111111111111111111111111".print_r(strtoupper($dist_name),true));
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
			log_message ( "debug", "22222222222222222222222222222222222222" . print_r ( $school_name, true ) );
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
		$query = $this->mongo_db->where ( 'school_code', $id )->select ( array (
				'hs_name',
				'hs_mob' 
		) )->get ( $this->collections ['tmreis_health_supervisors'] );
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
		) )->where ( 'school_name', $school_name )->get ( $this->collections ['tmreis_schools'] );
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
			// log_message("debug","in 11111111111111111111111111111111111111111111111111111111=======".print_r($request_stage4,true));
			foreach ( $screening_array as $school_name => $inner_data ) {
				// log_message("debug","in 222222222222222222222222222222222222222222222222222222=======".print_r($request_stage4,true));
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
					// log_message("debug","in ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc=======".print_r($request_stage4,true));
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
	
		$response = $this->data = $this->tmreis_schools_common_model->messaging($message);
		//$this->data = "";
	
		$this->output->set_output($response);
	}
	public function groupscount() {
		$count = $this->mongo_db->count ( 'tmreis_chat_groups' );
		return $count;
	}
	public function get_groups($per_page, $page) {
		$offset = $per_page * ($page - 1);
		$query = $this->mongo_db->limit ( $per_page )->offset ( $offset )->get ( 'tmreis_chat_groups' );
		return $query;
	}
	
	public function get_all_groups() {
		$query = $this->mongo_db->get ( 'tmreis_chat_groups' );
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
		$query = $this->mongo_db->get ( 'tmreis_chat_groups' );
		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13631====='.print_r($query,true));
		foreach($query as $data)
		{
			$group_name = $data['group_name'];
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13635====='.print_r($group_name,true));
			$where_array = array('group_name'=>$group_name,'list_of_users'=>array('$in'=>array($user_email)));
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13637====='.print_r($where_array,true));
			$grps = $this->mongo_db->where($where_array)->get ( 'tmreis_chat_groups_users' );
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13639====='.print_r($grps,true));
			log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13640====='.print_r(count($grps),true));
			if(isset($grps) && !empty($grps))
			{
				array_push($accessible_chat_rooms,$query);
			}
			
		}
		log_message('debug','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==13644====='.print_r($accessible_chat_rooms,true));
		return $accessible_chat_rooms;
	}
	
	public function get_all_admin_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_admins'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_health_supervisors() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_health_supervisors'] );
		$this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
		return $query;
	}
	public function get_all_cc_users() {
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		$query = $this->mongo_db->get ( $this->collections ['tmreis_cc'] );
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
		$query = $this->mongo_db->insert($this->collections['tmreis_messages'],$data);
		
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
		$query = $this->mongo_db->where("chat_room_id",$msg_id)->get($this->collections['tmreis_messages']);
		return $query;
	}
	public function get_user_by_email($name, $email){
				
		$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
		log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee=========.'.print_r($email,true));
		$user = $this->mongo_db->where("email",$email)->get($this->collections['tmreis_admins']);
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu=========.'.print_r($user,true));
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
		$id_exists = $this->mongo_db->where("user_id",$login)->get($this->collections['tmreis_users_gcm']);
		$data = array(
			"user_id" => $login,
			"gcm_registration_id" => $gcm_registration_id
		);
		if($id_exists){
			$query = $this->mongo_db->where("user_id",$login)->set($data)->get($this->collections['tmreis_users_gcm']);
		}else{
			$query = $this->mongo_db->insert($this->collections['tmreis_users_gcm'],$data);
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
	 
	 $query = $this->mongo_db->select(array('doc_data.widget_data'),array())->where(array('doc_data.widget_data.page4.Declaration Information.Date:'=>$date,$search_criteria=>$opt))->get('healthcare2016111212310531');
	 
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
		$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  	$query=$this->mongo_db->select(array(),array('_id'))->where(array('status'=>'new','app_id'=>'healthcare201610114435690'))->get($usercollection);
		return $query; 
	}
	
	public function get_school_details_for_school_code($school_code)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$school_data = $this->mongo_db->where(array('school_code'=>$school_code))->select(array('school_name','dt_name'),array())->get('tmreis_schools');
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		foreach ( $school_data as $schools => $school ) 
		{
			$dt_name = $this->mongo_db->where ( '_id', new MongoId ( $school ['dt_name'] ) )->get ( 'tmreis_district' );
			if (isset ( $school ['dt_name'] )) {
				$school_data [$schools] ['dt_name'] = $dt_name [0] ['dt_name'];
			} else {
				$school_data [$schools] ['dt_name'] = "No district selected";
			}
			return $school_data[0];
		}
		
	}
	
	// SANITATION INFRASTRUCTURE
	public function get_sanitation_infrastructure_model($district_name,$school_name)
	{
		$this->mongo_db->limit(1)->where(array('doc_data.widget_data.page6.School Information.District'=>$district_name,'doc_data.widget_data.page6.School Information.School Name'=>$school_name));
		$query = $this->mongo_db->get($this->sanitation_infrastructure_app_col);
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
			$query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->get($this->screening_app_col);
			return $query;
		}
		else
		{ 
		   $query = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name,"doc_data.widget_data.page2.Personal Information.Class"=>$class))->get($this->screening_app_col); 
		   return $query;
		 } 
	}
	
	public function studentscount($school_name) 
	{
		$count = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name"=>$school_name))->count($this->screening_app_col);
		return $count;
	}
	
	public function get_district($district_id)
	{
		$qry = $this->mongo_db->where('_id', new MongoId($district_id))->get('tmreis_districts');
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
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tmreis_classes']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('class_name', $cls_name )->orderBy ( array (
					'class_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tmreis_classes']);
		}
		
		return $query;
	}
	
	public function get_all_sections($section_name = "All",$school_code) 
	{
		if ($section_name == "All") 
		{
			$query = $this->mongo_db->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tmreis_sections']);
		} 
		else 
		{
			$query = $this->mongo_db->where ('section_name', $section_name )->orderBy ( array (
					'section_name' => 1 
			) )->where(array('school_code'=>$school_code))->get ($this->collections['tmreis_sections']);
		}
		
		return $query;
	}
	
	public function generate_new_student_hunique_id($school_code, $dist_code, $school_name)
	{
	  $uniqueidlist = array();
	
	  $all_uniqueID = $this->mongo_db->where(array('doc_data.widget_data.page2.Personal Information.School Name'=>$school_name))->select(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID'),array())->get($this->screening_app_col);
		
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
	
	public function add_student_ehr_model($doc_data,$history)
	{
	  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->insert($this->screening_app_col,$doc_data);
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
    	$query = $this->mongo_db->insert($this->collections['tmreis_classes'],$data);
    	return $query;
    }
	
	public function delete_class($class_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($class_id)))->delete($this->collections['tmreis_classes']);
		return $query;
    }
    
    public function create_section($post, $school_code)
    {
    	$data = array(
    			"section_name" => $post['section_name'],
				"school_code"  => $school_code
				);
    	$query = $this->mongo_db->insert($this->collections['tmreis_sections'],$data);
    	return $query;
    }
    
    public function delete_section($section_id, $school_code)
    {
    	$query = $this->mongo_db->where(array("school_code" => $school_code, "_id"=>new MongoId($section_id)))->delete($this->collections['tmreis_sections']);
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
			->get($this->collections['tmreis_health_supervisors']);

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
				->update($this->collections['tmreis_health_supervisors']);
				
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
			->get($this->collections['tmreis_health_supervisors']);
		
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
	
	public function tmreis_chronic_cases_count($school_name)
    {
     $query = $this->mongo_db->getWhere($this->collections['tmreis_chronic_cases'],array('school_name'=>$school_name));
	 return count($query);
    }
	
	function get_chronic_cases_model($limit, $page, $school_name)
	{
	    $offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->getWhere($this->collections['tmreis_chronic_cases'],array('school_name'=>$school_name));
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
		$query = $this->mongo_db->getWhere($this->collections['tmreis_chronic_cases'],array('school_name'=>$school_name));
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
		$query = $this->mongo_db->select(array('student_unique_id','case_id','scheduled_months'),array())->getWhere($this->collections['tmreis_chronic_cases'],array('school_name'=>$school_name,'followup_scheduled'=>'true'));
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
	   
	   $updated = $this->mongo_db->where(array('student_unique_id'=>$unique_id,'case_id'=>$case_id))->set($update_array)->update($this->collections['tmreis_chronic_cases']);
	   
	   if($updated)
		   return TRUE;
	   else
		   return FALSE;
	}
	
	function calculate_chronic_graph_compliance_percentage($case_id,$unique_id,$medication_taken)
	{
	   $medication_schedule = array();
	   $query = $this->mongo_db->select(array(),array())->getWhere($this->collections['tmreis_chronic_cases'],array('student_unique_id'=>$unique_id,'case_id'=>$case_id));  
	   
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
		 
		$is_already_updated = $this->mongo_db->where($check_query)->get($this->collections['tmreis_chronic_cases']);
		
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
			'findAndModify' => $this->collections['tmreis_chronic_cases'],
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
		
		$query = $this->mongo_db->where($where_clause)->get($this->collections['tmreis_chronic_cases']);
        return $query;
	}
	
	/*
	*Fetchinhg BMI value with Unique id
	*author Naresh
	
	*/ 
	public function get_student_bmi_values($unique_id)
	{
		$query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.BMI_values'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('healthcare20176616511646');
		log_message("debug","query==========12576".print_r($query,true));
		
		if($query)
			return $query;
	    else
			return FALSE;
	}
	
	//update personal Information
   public function get_update_personal_ehr_uid($uid) {
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->whereLike ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid )->get ( $this->screening_app_col );
		log_message("debug","update personal Info for modelllllll114816".print_r($query,true));
		 if ($query) {
			$query_request = $this->mongo_db->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $uid )->get ( $this->request_app_col );
			log_message("debug"," query_request update personal Info for modelllllll114816".print_r($query_request,true));
			$result ['screening'] = $query;
			///$result ['request'] = $query_request;
			return $result;
		} else {
			$result ['screening'] = false;
			//$result ['request'] = false;
			return $result;
		} 
	}
	
	public function update_student_ehr_model($unique_id,$doc_data)
	{
	  //$doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"history"=>$history);
	  $query = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set($doc_data)->update($this->screening_app_col);
	  log_message('debug',"updateeeeeeeeeeeee".print_r($query,true));
	  if($query)
		  return TRUE;
	  else
		  return FALSE;
	}

	/**
	 * Helper: Get news by todays date
	 *
	 *@author Bhanu 
	 */

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
					'aggregate' => $this->collections ['tmreis_news_feed'],
					'pipeline' => $pipeline 
			) );
			$query = array();
			if($response['ok']){
				$query = $response["result"];
			}
	
		return $query;
	
	}

	public function get_all_news_feeds(){
	
		$query = $this->mongo_db->get ( $this->collections ['tmreis_news_feed'] );
	
		return $query;
	
	}
	
}