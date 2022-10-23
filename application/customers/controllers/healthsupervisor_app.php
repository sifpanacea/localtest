<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Healthsupervisor_app extends CI_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Ben
	 *
	 * @return void 
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		$this->common_db = $this->config->item ( 'default' );
		//$this->config->library('session');
		$this->upload_info = array();
		$this->load->library('form_validation');
		$this->load->helper('url');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));		
		$this->load->model('healthsupervisor_app_model');
		$this->load->model('tswreis_schools_common_model');
		$this->load->library('panacea_common_lib');
		$this->load->library('ttwreis_common_lib');
		$this->load->library('tmreis_common_lib');
		$this->load->library('bc_welfare_common_lib');
		$this->load->model('panacea_common_model');
		$this->load->model('schoolhealth_school_portal_model');
		$this->load->model('ttwreis_common_model');
		$this->load->model('tmreis_common_model');
		$this->load->model('bc_welfare_common_model');
		$this->load->library('gcm/gcm');
		$this->load->library('gcm/push');
		$this->load->library('bhashsms');
		$this->mobile['mob_num'] = '9866059098';
		
	}

	public function attendanceReportSubmitFromDevice()
	{
		if(isset($_POST['selectDistrict']) && isset($_POST['selectSchool']) && isset($_POST['attended_students_count']) && isset($_POST['sick_students_count']) && isset($_POST['sick_UID']) && isset($_POST['rtoh_students_count']) && isset($_POST['r2h_UID']) && isset($_POST['absent_students_count']) && isset($_POST['absent_UID']) && isset($_POST['rest_room_students_count']) && isset($_POST['restRoom_UID']))
		{
			$user_type = $_POST['user_type'];
			$sick_UID = $this->input->post('sick_UID',true);

			$doc_data = array();
			
			$doc_data['page1']['Attendence Details']['District'] = $this->input->post('selectDistrict',true);
			$doc_data['page1']['Attendence Details']['Select School'] = $this->input->post('selectSchool',true);
			$doc_data['page1']['Attendence Details']['Attended'] 	= $this->input->post('attended_students_count',true);
			$doc_data['page1']['Attendence Details']['Sick'] 		= $this->input->post('sick_students_count',true);
			$doc_data['page1']['Attendence Details']['Sick UID']  	= $sick_UID;
			$doc_data['page1']['Attendence Details']['R2H'] 		= $this->input->post('rtoh_students_count',true);
			$doc_data['page1']['Attendence Details']['R2H UID']		= $this->input->post('r2h_UID',true);
			$doc_data['page1']['Attendence Details']['Absent'] 		= $this->input->post('absent_students_count',true);
			$doc_data['page2']['Attendence Details']['Absent UID'] 	= $this->input->post('absent_UID',true);
			$doc_data['page2']['Attendence Details']['RestRoom'] 	= $this->input->post('rest_room_students_count',true);
			$doc_data['page2']['Attendence Details']['RestRoom UID']= $this->input->post('restRoom_UID',true);

			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = "";
			$doc_properties['doc_flow'] = "new";
			
			
			
			/*$session_data = $this->session->userdata("customer");
			$email_id = $session_data['email'];
			
			$email = str_replace("@","#",$email_id);*/

			$email_id = $this->input->post('submitted_by',TRUE);
			$email = str_replace("@","#",$email_id);
			// History
			$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $email,
				'raised_by' => "device_side",
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;

			$submitted_data = $this->healthsupervisor_app_model->attendanceReportSubmitFromDeviceModel($doc_data, $doc_properties, $history,$user_type);
		
			if($submitted_data)
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Attendance report submitted successfully')
									));
			}
			else
			{
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed to submit ! Try again')
									));
			}

		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'REQUIRED_PARAMS_MISSING')
									));
		}
	}

	public function search_unique_id()
	{
		$unique_id = $_POST['unique_id'];
		$user_type = $_POST['user_type'];
		/*$code = explode("_", $unique_id);
		$schoolCode = intval($code[1]);
		$school_name = $this->healthsupervisor_app_model->get_schoolName_details($schoolCode,$user_type);
		$school_name = $school_name[0]['school_name'];*/
		$student_details = $this->healthsupervisor_app_model->get_student_details($unique_id,$user_type);
		if(!empty($student_details))
		{
			$this->output->set_output(json_encode($student_details));
		}else{
		  	$this->output->set_output(json_encode(array('status' => FALSE,
		  												'message' => "No Unique ID Found")));
		}
		

	}

	public function initiate_request_from_device()
	{
		//log_message('error','postdatttttttta print 102========'.print_r($_POST, true)); 
	
		if(isset($_POST['unique_id']))
		{			
		//$doc_id = $this->input->post('doc_id',TRUE);
			$user_type = $_POST['user_type'];
	  		$unique_id = $this->input->post('unique_id',TRUE);
			$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
			$district = $this->input->post('page1_StudentInfo_District',TRUE);
			$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
			$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
			$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

			$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}

			
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

	  		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
			$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
			$normal_eyes_identifier     = $this->input->post('normal_eye_identifier',TRUE);
			$normal_ent_identifier      = $this->input->post('normal_ent_identifier',TRUE);
			$normal_rs_identifier       = $this->input->post('normal_rs_identifier',TRUE);
			$normal_cvs_identifier      = $this->input->post('normal_cvs_identifier',TRUE);
			$normal_gi_identifier       = $this->input->post('normal_gi_identifier',TRUE);
			$normal_gu_identifier       = $this->input->post('normal_gu_identifier',TRUE);
			$normal_gyn_identifier      = $this->input->post('normal_gyn_identifier',TRUE);
			$normal_cri_identifier      = $this->input->post('normal_cri_identifier',TRUE);
			$normal_msk_identifier      = $this->input->post('normal_msk_identifier',TRUE);
			$normal_cns_identifier      = $this->input->post('normal_cns_identifier',TRUE);
			$normal_psychiartic_identifier      = $this->input->post('normal_psychiartic_identifier',TRUE);

			$emergency_identifier 		= $this->input->post('emergency_identifier',TRUE);			
			$emergency_bites_identifier = $this->input->post('emergency_bites_identifier',TRUE);
			
			$chronic_eyes_identifier  = $this->input->post('chronic_eyes_identifier',TRUE);
			$chronic_ent_identifier  = $this->input->post('chronic_ent_identifier',TRUE);
			$chronic_cns_identifier  = $this->input->post('chronic_cns_identifier',TRUE);
			$chronic_rs_identifier  = $this->input->post('chronic_rs_identifier',TRUE);
			$chronic_cvs_identifier  = $this->input->post('chronic_cvs_identifier',TRUE);
			$chronic_gi_identifier  = $this->input->post('chronic_gi_identifier',TRUE);
			$chronic_blood_identifier  = $this->input->post('chronic_blood_identifier',TRUE);
			$chronic_kidney_identifier  = $this->input->post('chronic_kidney_identifier',TRUE);
			$chronic_vandm_identifier  = $this->input->post('chronic_vandm_identifier',TRUE);
			$chronic_bones_identifier  = $this->input->post('chronic_bones_identifier',TRUE);
			$chronic_skin_identifier  = $this->input->post('chronic_skin_identifier',TRUE);
			$chronic_endo_identifier  = $this->input->post('chronic_endo_identifier',TRUE);
			$chronic_others_identifier  = $this->input->post('chronic_others_identifier',TRUE);

			$request_type  = $this->input->post('page2_ReviewInfo_RequestType',TRUE);
			$review_status = $this->input->post('page2_ReviewInfo_Status',TRUE);

			$std_join_hospital_name = $this->input->post('std_join_hospital_name', true);
			$std_join_hospital_type = $this->input->post('std_join_hospital_type', true);
			$std_join_hospital_dist = $this->input->post('std_join_hospital_dist', true);
			$hospitalised_date = $this->input->post('hospitalised_date', true);
			//$transfer_join_hospital_name = $this->input->post('transfer_join_hospital_name', true);
			//$transfer_hospitalised_date = $this->input->post('transfer_hospitalised_date', true);
			$discharge_date = $this->input->post('discharge_date', true);

			$normal_identifiers = array(
					'General' => !empty($normal_general_identifier) ? explode(", ",$normal_general_identifier) : [],
					'Head' => !empty($normal_head_identifier) ? explode(", ", $normal_head_identifier) : [],
					'Eyes' => !empty($normal_eyes_identifier) ?explode(", ",$normal_eyes_identifier) : [],
					'Ent' => !empty($normal_ent_identifier) ? explode(", ",$normal_ent_identifier) : [],
					'Respiratory_system' => !empty($normal_rs_identifier) ?explode(", ",$normal_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($normal_cvs_identifier) ? explode(", ",$normal_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($normal_gi_identifier) ? explode(", ",$normal_gi_identifier) : [],
					'Genito_urinary' => !empty($normal_gu_identifier) ? explode(", ",$normal_gu_identifier) : [],
					'Gynaecology' => !empty($normal_gyn_identifier) ? explode(", ",$normal_gyn_identifier) : [],
					'Endo_crinology' => !empty($normal_cri_identifier) ? explode(", ",$normal_cri_identifier) : [],
					'Musculo_skeletal_syatem' => !empty($normal_msk_identifier) ? explode(", ",$normal_msk_identifier) : [],
					'Central_nervous_system' => !empty($normal_cns_identifier) ? explode(", ",$normal_cns_identifier) : [],
					'Psychiartic' => !empty($normal_psychiartic_identifier) ? explode(", ",$normal_psychiartic_identifier) : []
				);

			$emergency_identifiers = array(
					'Disease' => !empty($emergency_identifier) ? explode(", ", $emergency_identifier) : [],
					'Bites' => !empty($emergency_bites_identifier) ? explode(", ",$emergency_bites_identifier) : []	
				);

			$chronic_identifiers = array(
					'Eyes' => !empty($chronic_eyes_identifier) ? explode(", ",$chronic_eyes_identifier): [],
					'Ent'  => !empty($chronic_ent_identifier) ? explode(", ",$chronic_ent_identifier) :[],
					'Central_nervous_system' => !empty($chronic_cns_identifier) ? explode(", ",$chronic_cns_identifier) : [],
					'Respiratory_system' => !empty($chronic_rs_identifier) ? explode(", ",$chronic_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($chronic_cvs_identifier) ? explode(", ",$chronic_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($chronic_gi_identifier) ? explode(", ",$chronic_gi_identifier) : [],
					'Blood'  => !empty($chronic_blood_identifier) ? explode(", ",$chronic_blood_identifier) : [],
					'Kidney' => !empty($chronic_kidney_identifier) ? explode(", ",$chronic_kidney_identifier) : [],
					'VandM'  => !empty($chronic_vandm_identifier) ? explode(", ",$chronic_vandm_identifier) : [],
					'Bones'  => !empty($chronic_bones_identifier) ? explode(", ",$chronic_bones_identifier) : [],
					'Skin'   => !empty($chronic_skin_identifier) ? explode(", ",$chronic_skin_identifier) : [],
					'Endo'   => !empty($chronic_endo_identifier) ? explode(", ",$chronic_endo_identifier) : [],
					'Others' => !empty($chronic_others_identifier) ? explode(", ",$chronic_others_identifier) : []					
				);
			
			$problem_info_description  = $this->input->post('page2_ProblemInfo_Description',TRUE);
			
			$doctor_summary  = $this->input->post('page2_DiagnosisInfo_DoctorSummary',TRUE);
			$doctor_advice  = $this->input->post('page2_DiagnosisInfo_DoctorAdvice',TRUE);
			$prescription  = $this->input->post('page2_DiagnosisInfo_Prescription',TRUE);

		  // Personal information Page 1
			$doc_data['widget_data']['page1']['Student Info']['Unique ID']    = ($unique_id) ? $unique_id : '' ;
			$doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']    	 = ($student_name) ? $student_name : '';
			$doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = ($district) ? $district : '';
			$doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =($school_name) ? $school_name : '';
			$doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = ($class) ? $class : '' ;
			$doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = ($section) ? $section : '';
			$doc_data['widget_data']['page1']['Student Info']['Gender']    = isset($gender) ? $gender : '';
			$doc_data['widget_data']['page1']['Student Info']['Age']    = (!empty($age) && isset($age)) ? $age : '';

			if(isset($normal_identifiers) && !empty($normal_identifiers))
			{
				$doc_data['widget_data']['page1']['Problem Info']['Normal']  = $normal_identifiers;
			}
			if(isset($emergency_identifiers) && !empty($emergency_identifiers))
			{
				$doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;	
			}
			if(isset($chronic_identifiers) && !empty($chronic_identifiers))
			{
				$doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
			}						
			
			//Description Page2
			$doc_data['widget_data']['page2']['Problem Info']['Description']    = ($problem_info_description) ? $problem_info_description : '';
			//Doctor Summary Page2
			$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = !empty($doctor_summary) ? $doctor_summary : "";
			$doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = !empty($doctor_advice) ? $doctor_advice : '';
			$doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = !empty($prescription) ? 
			$prescription : '' ;

			//Request Type and Status Page2
			$doc_data['widget_data']['page2']['Review Info']['Request Type']    = ($request_type) ? $request_type : '';
			$doc_data['widget_data']['page2']['Review Info']['Status']    = ($review_status) ? $review_status : '';
			if($request_type === "Chronic" || $request_type === "Deficiency" || $request_type === "Defects")
			 {
		       $chronic_disease   = $chronic_identifiers;
		       $disease_desc      = $problem_info_description;
			  // log_message('error','chronic_disease=========2662'.print_r($chronic_disease,TRUE));exit();
			   $this->healthsupervisor_app_model->create_chronic_case_new($unique_id,$request_type,$chronic_disease,$disease_desc,$school_name);
		     }

			if($review_status == 'Hospitalized' || $review_status == 'Out-Patient' || $review_status == 'Review')
		{
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;
			$doc_data['widget_data']['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
			$doc_data['widget_data']['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
		}

		if($review_status == 'Discharged')
		{
			$doc_data['widget_data']['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
		}
			  // Attachments
		 	if(isset($_FILES) && !empty($_FILES))
			 {
		       $this->load->library('upload');
		       $this->load->library('image_lib');
			   
			   $external_files_upload_info = array();
			   $external_final             = array();
			   $external_merged_data       = array();
			   
			   $files = $_FILES;
			   $cpt = count($_FILES['hs_req_attachments']['name']);
			   for($i=0; $i<$cpt; $i++)
			   {
				 $_FILES['hs_req_attachments']['name']	= $files['hs_req_attachments']['name'][$i];
				 $_FILES['hs_req_attachments']['type']	= $files['hs_req_attachments']['type'][$i];
				 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
				 $_FILES['hs_req_attachments']['error']	= $files['hs_req_attachments']['error'][$i];
				 $_FILES['hs_req_attachments']['size']	= $files['hs_req_attachments']['size'][$i];
				
			   foreach ($_FILES as $index => $value)
		       {
				  if(!empty($value['name']))
				  {
				  	if($user_type == "PANACEA_HS" || preg_match("/TSWREIS/i", $user_type))
				  	{
				  		$controller = 'healthcare2016531124515424_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
				  	}else if($user_type == "TTWREIS_HS" || preg_match("/TTWREIS/i", $user_type))
				  	{
				  		$controller = 'healthcare2016108181933756_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
				  	}
				  	else if($user_type == "TMREIS_HS" || preg_match("/TMREIS/i", $user_type))
				  	{
				  		$controller = 'healthcare201610114435690_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
				  	}
				  	else if($user_type == "BCWELFARE_HS" || preg_match("/BCWELFARE/i", $user_type))
				  	{
				  		$controller = 'healthcare2018122191146894_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
				  	}
				        //create controller upload folder if not exists
						if (!is_dir($config['upload_path']))
						{
							mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
						}
			
						$this->upload->initialize($config);
						
						if ( ! $this->upload->do_upload($index))
						{
							 echo "external file upload failed";
			        		 return FALSE;
						}
						else
						{
							$external_files_upload_info = $this->upload->data();
						
							$external_data_array = array(
													  "DFF_EXTERNAL_ATTACHMENTS_".$i => array(
													"file_client_name" =>$external_files_upload_info['client_name'],
													"file_encrypted_name" =>$external_files_upload_info['file_name'],
													"file_path" =>$external_files_upload_info['file_relative_path'],
													"file_size" =>$external_files_upload_info['file_size']
																	) );

							$external_final = array_merge($external_final,$external_data_array);
							
						}  
					}
				}
			 }
			   if(isset($doc_data['external_attachments']))
				  {
						   
					$external_merged_data = array_merge($doc_data['doc_data']['external_attachments'],$external_final);
					$doc_data['doc_data']['external_attachments'] = array_replace_recursive($doc_data['doc_data']['external_attachments'],$external_merged_data);
				  }
				  else
				 {
				    $doc_data['external_attachments'] = $external_final;
				 } 
			  
			 }
			 else
			 {
			 	 $doc_data['external_attachments'] = [];
			 }			 

			 // school data
		 	$school_data_array = explode("_",$unique_id);
			$schoolCode        = (int) $school_data_array[1];

		 	$school_data = $this->healthsupervisor_app_model->get_school_information_for_school_code($schoolCode,$user_type);
		 
		 	$health_supervisor = $this->healthsupervisor_app_model->get_health_supervisor_details($schoolCode,$user_type);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];

		 
			 $school_contact_details = array(
			 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
			 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
			 );

		 	$doc_data['school_contact_details']  = $school_contact_details;

		 		$email = strtolower($school_data_array[0]).".".$school_data_array[1]."."."hs#gmail.com";
		 		//$email = $this->input->post('submitted_by',TRUE);
		 		//$hs_email = str_replace('@', '#', $email); 
				//$hs_name = $this->input->post('submitted_by_name',TRUE); 
		
				$doc_data['user_name'] = $email;
				
				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";
				$doc_properties['doc_access'] = "false";
				$doc_properties['access_by'] = "";
				$doc_properties['doc_access_time'] = 0;								
				
				$array_history = array();
				
			 	$array_data = array(
			 		'current_stage' => "HS 1",
			 		'approval' => "true",
			 		'submitted_by' => $email,
			 		'raised_by' => "device_side",
			 		'time' => date('Y-m-d H:i:s')
			 		);

			 	array_push($array_history, $array_data);

			 	//log_message('error','array_history_device========'.print_r($array_history,TRUE));
			 		
		  	$submitted_data_hs = $this->healthsupervisor_app_model->initiate_request_model($doc_data,$doc_properties,$array_history,$user_type);

		  	if($submitted_data_hs){
		  		$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'HS Request submitted successfully')
									));
		  	}

		  	//log_message('error','user_typeuser_type108========'.print_r($user_type,TRUE));	  	

				if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharged" || $review_status == "Out-Patient" || $review_status == "Review")
		  	{
		  		
		  		$doc = $this->healthsupervisor_app_model->get_doc_id_from_doc_properties($submitted_data_hs,$user_type);
				$doc_id = $doc['0']['doc_properties']['doc_id'];
		  		
		$insert_hospitalised = $this->healthsupervisor_app_model->insert_hospitalised_students_data($doc_data,$array_history,$unique_id,$doc_id,$doc_properties, $user_type);
		  	}
           
		  	   // log_message('error','submitted_data_hs---------------'.print_r($submitted_data_hs,TRUE));
		  	  
		  	  if(!empty($submitted_data_hs))
				{
					
					$issueinfo_2 = array();
					if(isset($normal_identifiers) && !empty($normal_identifiers))
					{
					  	foreach($normal_identifiers as $issueInfo)
					  	{
					  		$diseaes = implode(',',$issueInfo);
					  		if(!empty($diseaes))
					  		{
					  			array_push($issueinfo_2, $diseaes);
					  		}
					  	 }
					  	$total_diseaes =  implode(",",$issueinfo_2);
			  	    }
			  	    if(isset($emergency_identifiers) && !empty($emergency_identifiers))
					{
					  	foreach($emergency_identifiers as $issueInfo)
					  	{
					  		$diseaes = implode(',',$issueInfo);
					  		if(!empty($diseaes))
					  		{
					  			array_push($issueinfo_2, $diseaes);
					  		}
					  	 }
					  	$total_diseaes =  implode(",",$issueinfo_2);
			  	    }

			  	    if(isset($chronic_identifiers) && !empty($chronic_identifiers))
					{
					  	foreach($chronic_identifiers as $issueInfo)
					  	{
					  		$diseaes = implode(',',$issueInfo);
					  		if(!empty($diseaes))
					  		{
					  			array_push($issueinfo_2, $diseaes);
					  		}
					  	}
					  	$total_diseaes =  implode(",",$issueinfo_2);
			  	    }
			  	   $message = "Hi ".$hs_name.", New ".$request_type." Request is created with ".$total_diseaes." Identifier is created for ".$student_name." with UID ".$unique_id." on ".date('Y-m-d').", Doctor will update You soon.";
		  	   
		  	  	 	$this->bhashsms->send_sms($hs_mob,$message);

					$send_msg = $this->panacea_common_lib->send_message_to_doctors($request_type,$unique_id,$student_name,$total_diseaes);
				
						
					}
					else
					{
						$this->output->set_output(json_encode(
											array(
												'status' => FALSE, 
												'message' => 'Failed to submit ! Try again')
											));
					}
				}else
				{
					$this->output->set_output(json_encode(
											array(
												'status' => FALSE, 
												'message' => 'REQUIRED_PARAMS_MISSING')
											));
				}
	}


	public function update_request_and_submit_device()
  	{

  		//log_message('error','postdatttttttta print 102========'.print_r($_POST, true));
  			// POST DATA
  			$user_type = $_POST['user_type'];

			$doc_id = $this->input->post('doc_id',TRUE);
	  		$unique_id = $this->input->post('unique_id',TRUE);
			$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
			$district = $this->input->post('page1_StudentInfo_District',TRUE);
			$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
			$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
			$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

			$gender_info = substr($school_name,strpos($school_name, "),")-1,1);

			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}

			
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

	  		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
			$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
			$normal_eyes_identifier      = $this->input->post('normal_eye_identifier',TRUE);
			$normal_ent_identifier      = $this->input->post('normal_ent_identifier',TRUE);
			$normal_rs_identifier       = $this->input->post('normal_rs_identifier',TRUE);
			$normal_cvs_identifier      = $this->input->post('normal_cvs_identifier',TRUE);
			$normal_gi_identifier       = $this->input->post('normal_gi_identifier',TRUE);
			$normal_gu_identifier       = $this->input->post('normal_gu_identifier',TRUE);
			$normal_gyn_identifier      = $this->input->post('normal_gyn_identifier',TRUE);
			$normal_cri_identifier      = $this->input->post('normal_cri_identifier',TRUE);
			$normal_msk_identifier      = $this->input->post('normal_msk_identifier',TRUE);
			$normal_cns_identifier      = $this->input->post('normal_cns_identifier',TRUE);
			$normal_psychiartic_identifier      = $this->input->post('normal_psychiartic_identifier',TRUE);

			$emergency_identifier 		= $this->input->post('emergency_identifier',TRUE);
			
			$emergency_bites_identifier = $this->input->post('emergency_bites_identifier',TRUE);
			
			$chronic_eyes_identifier  = $this->input->post('chronic_eyes_identifier',TRUE);
			$chronic_ent_identifier  = $this->input->post('chronic_ent_identifier',TRUE);
			$chronic_cns_identifier  = $this->input->post('chronic_cns_identifier',TRUE);
			$chronic_rs_identifier  = $this->input->post('chronic_rs_identifier',TRUE);
			$chronic_cvs_identifier  = $this->input->post('chronic_cvs_identifier',TRUE);
			$chronic_gi_identifier  = $this->input->post('chronic_gi_identifier',TRUE);
			$chronic_blood_identifier  = $this->input->post('chronic_blood_identifier',TRUE);
			$chronic_kidney_identifier  = $this->input->post('chronic_kidney_identifier',TRUE);
			$chronic_vandm_identifier  = $this->input->post('chronic_vandm_identifier',TRUE);
			$chronic_bones_identifier  = $this->input->post('chronic_bones_identifier',TRUE);
			$chronic_skin_identifier  = $this->input->post('chronic_skin_identifier',TRUE);
			$chronic_endo_identifier  = $this->input->post('chronic_endo_identifier',TRUE);
			$chronic_others_identifier  = $this->input->post('chronic_others_identifier',TRUE);		

			$request_type  = $this->input->post('page2_ReviewInfo_RequestType',TRUE);
			$review_status = $this->input->post('page2_ReviewInfo_Status',TRUE);

			$std_join_hospital_name = $this->input->post('std_join_hospital_name', true);
			$std_join_hospital_type = $this->input->post('std_join_hospital_type', true);
	        $std_join_hospital_dist = $this->input->post('std_join_hospital_dist', true);
	        $hospitalised_date = $this->input->post('hospitalised_date', true);	      
	        $discharge_date = $this->input->post('discharge_date', true);


			$normal_identifiers = array(
					'General' => !empty($normal_general_identifier) ? explode(", ",$normal_general_identifier) : [],
					'Head' => !empty($normal_head_identifier) ? explode(", ", $normal_head_identifier) : [],
					'Eyes' => !empty($normal_eyes_identifier) ?explode(", ",$normal_eyes_identifier) : [],
					'Ent' => !empty($normal_ent_identifier) ? explode(", ",$normal_ent_identifier) : [],
					'Respiratory_system' => !empty($normal_rs_identifier) ?explode(", ",$normal_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($normal_cvs_identifier) ? explode(", ",$normal_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($normal_gi_identifier) ? explode(", ",$normal_gi_identifier) : [],
					'Genito_urinary' => !empty($normal_gu_identifier) ? explode(", ",$normal_gu_identifier) : [],
					'Gynaecology' => !empty($normal_gyn_identifier) ? explode(", ",$normal_gyn_identifier) : [],
					'Endo_crinology' => !empty($normal_cri_identifier) ? explode(", ",$normal_cri_identifier) : [],
					'Musculo_skeletal_syatem' => !empty($normal_msk_identifier) ? explode(", ",$normal_msk_identifier) : [],
					'Central_nervous_system' => !empty($normal_cns_identifier) ? explode(", ",$normal_cns_identifier) : [],
					'Psychiartic' => !empty($normal_psychiartic_identifier) ? explode(", ",$normal_psychiartic_identifier) : []
				);

			$emergency_identifiers = array(
					'Disease' => !empty($emergency_identifier) ? explode(", ", $emergency_identifier) : [],
					'Bites' => !empty($emergency_bites_identifier) ? explode(", ",$emergency_bites_identifier) : []	
				);

			$chronic_identifiers = array(
					'Eyes' => !empty($chronic_eyes_identifier) ? explode(", ",$chronic_eyes_identifier): [],
					'Ent'  => !empty($chronic_ent_identifier) ? explode(", ",$chronic_ent_identifier) :[],
					'Central_nervous_system' => !empty($chronic_cns_identifier) ? explode(", ",$chronic_cns_identifier) : [],
					'Respiratory_system' => !empty($chronic_rs_identifier) ? explode(", ",$chronic_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($chronic_cvs_identifier) ? explode(", ",$chronic_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($chronic_gi_identifier) ? explode(", ",$chronic_gi_identifier) : [],
					'Blood'  => !empty($chronic_blood_identifier) ? explode(", ",$chronic_blood_identifier) : [],
					'Kidney' => !empty($chronic_kidney_identifier) ? explode(", ",$chronic_kidney_identifier) : [],
					'VandM'  => !empty($chronic_vandm_identifier) ? explode(", ",$chronic_vandm_identifier) : [],
					'Bones'  => !empty($chronic_bones_identifier) ? explode(", ",$chronic_bones_identifier) : [],
					'Skin'   => !empty($chronic_skin_identifier) ? explode(", ",$chronic_skin_identifier) : [],
					'Endo'   => !empty($chronic_endo_identifier) ? explode(", ",$chronic_endo_identifier) : [],
					'Others' => !empty($chronic_others_identifier) ? explode(", ",$chronic_others_identifier) : []					
				);
			
			$problem_info_description  = $this->input->post('page2_ProblemInfo_Description',TRUE);
			
			$doctor_summary  = $this->input->post('page2_DiagnosisInfo_DoctorSummary',TRUE);
			$doctor_advice  = $this->input->post('page2_DiagnosisInfo_DoctorAdvice',TRUE);
			$prescription  = $this->input->post('page2_DiagnosisInfo_Prescription',TRUE);			

		  // Personal information Page 1
			$doc_data['page1']['Student Info']['Unique ID']    = ($unique_id) ? $unique_id : '' ;
			$doc_data['page1']['Student Info']['Name']['field_ref']    	 = ($student_name) ? $student_name : '';
			$doc_data['page1']['Student Info']['District']['field_ref']    = ($district) ? $district : '';
			$doc_data['page1']['Student Info']['School Name']['field_ref']    =($school_name) ? $school_name : '';
			$doc_data['page1']['Student Info']['Class']['field_ref']    = ($class) ? $class : '' ;
			$doc_data['page1']['Student Info']['Section']['field_ref']    = ($section) ? $section : '';
			$doc_data['page1']['Student Info']['Gender']    = ($gender) ? $gender : '';
			$doc_data['page1']['Student Info']['Age']    = (!empty($age) && isset($age)) ? $age : '';			
			
			if(isset($normal_identifiers) && !empty($normal_identifiers))
			{
				$doc_data['page1']['Problem Info']['Normal']  = $normal_identifiers;
			}
			if(isset($emergency_identifiers) && !empty($emergency_identifiers))
			{
				$doc_data['page1']['Problem Info']['Emergency']  = $emergency_identifiers;	
			}
			if(isset($chronic_identifiers) && !empty($chronic_identifiers))
			{
				$doc_data['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
			}
					
			
			//Description Page2
			$doc_data['page2']['Problem Info']['Description']    = ($problem_info_description) ? $problem_info_description : "";
			//Doctor Summary Page2
			$doc_data['page2']['Diagnosis Info']['Doctor Summary']  = !empty($doctor_summary) ? $doctor_summary : "";
			$doc_data['page2']['Diagnosis Info']['Doctor Advice']  = !empty($doctor_advice) ? $doctor_advice : '';
			$doc_data['page2']['Diagnosis Info']['Prescription']  = !empty($prescription) ? 
			$prescription : "" ;

			//Request Type and Status Page2
			$doc_data['page2']['Review Info']['Request Type']    = ($request_type) ? $request_type : '';
			$doc_data['page2']['Review Info']['Status']    = ($review_status) ? $review_status : '';


			if($review_status == 'Hospitalized' || $review_status == 'Out-Patient' || $review_status == 'Review')
		{
			$doc_data['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
			$doc_data['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;			
            $doc_data['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
			$doc_data['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
		}
		
		if($review_status == 'Discharged')
		{
			$doc_data['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
		}

		$doc_data_external_attachments = array();
		// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['hs_req_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['hs_req_attachments']['name']	= $files['hs_req_attachments']['name'][$i];
			 $_FILES['hs_req_attachments']['type']	= $files['hs_req_attachments']['type'][$i];
			 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
			 $_FILES['hs_req_attachments']['error']	= $files['hs_req_attachments']['error'][$i];
			 $_FILES['hs_req_attachments']['size']	= $files['hs_req_attachments']['size'][$i];
			//log_message('error','checking_update_attachment_while_initiate_reuest========single'.print_r($_FILES,TRUE));
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
		  		if($user_type == "PANACEA_HS"){
		  			$controller = 'healthcare2016531124515424_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		  		}else if($user_type == "TTWREIS_HS")
		  		{
		  			$controller = 'healthcare2016108181933756_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		  		}else if($user_type == "TMREIS_HS")
		  		{
		  			$controller = 'healthcare201610114435690_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		  		}else if($user_type == "BCWELFARE_HS")
		  		{
		  			$controller = 'healthcare2018122191146894_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		  		}			        
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 
								redirect('tswreis_schools/hs_request');  
							
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
						$rand_number = mt_rand();
						$external_data_array = array(
												  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
												"file_client_name" =>$external_files_upload_info['client_name'],
												"file_encrypted_name" =>$external_files_upload_info['file_name'],
												"file_path" =>$external_files_upload_info['file_relative_path'],
												"file_size" =>$external_files_upload_info['file_size']
																)

													 );

						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
			$doc_history = $this->healthsupervisor_app_model->get_history($unique_id,$doc_id,$user_type);
				

			  if(isset($doc_history[0]['doc_data']['external_attachments']))
			  {
					   
				$external_merged_data = array_merge($doc_history[0]['doc_data']['external_attachments'],$external_final);
				$doc_data_external_attachments = array_replace_recursive($doc_history[0]['doc_data']['external_attachments'],$external_merged_data);
			  }
			  else
			 {
			    $doc_data_external_attachments = $external_final;
			 } 
		  
		 }
		  // school data
		 $school_data_array = explode("_",$unique_id);
		 $schoolCode        = (int) $school_data_array[1];

		 $school_data = $this->healthsupervisor_app_model->get_school_information_for_school_code($schoolCode,$user_type);
		 
		  $health_supervisor = $this->healthsupervisor_app_model->get_health_supervisor_details($schoolCode,$user_type);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];

	
		 $school_contact_details = array(
		 	'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 	'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 );

		 $doc_data['school_contact_details']  = $school_contact_details;
		
		 	
		//$session_data = $this->session->userdata('customer');
		//echo print_r($session_data,true);exit();
		$email = $_POST['email'];
		$username = $email;
		$doc_data['user_name'] = $email;
		$submitted_user_type = 'HS';
		
  	  //POST DATA
		$redirected_stage   = "Doctor";
		$current_stage      = "HS 2";
		//$reason             = implode(", ",$reason_array);
		//$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
		$redirected_stage	= $redirected_stage;
		$current_stage	    = $current_stage;
		$disapproving_user	= $username;
		$stage_name 		= "HS 2";		
	
			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";
			$doc_properties['doc_access'] = "false";
			$doc_properties['access_by'] = "";
			$doc_properties['doc_access_time'] = 0;

		$approval_data = array(
			"current_stage"    	=> $stage_name,
			"approval"		        => "false",
			"disapproved_by"	    => $disapproving_user,
			"submitted_by"			=> $disapproving_user,
			"time"		            => date('Y-m-d H:i:s'),
			//"reason"	            => $reason,
			"redirected_stage"		=> $redirected_stage,
			"redirected_user"		=> "multi_user_stage",
			"raised_by"			    => "device_side",
			"submitted_user_type"	=> $submitted_user_type); 
		
		$approval_history = $this->healthsupervisor_app_model->get_approval_history($doc_id,$user_type);

		array_push($approval_history,$approval_data);		

  	    $existing_update = $this->healthsupervisor_app_model->update_request_submit_model($doc_data,$doc_properties,$approval_history,$unique_id,$doc_id,$doc_data_external_attachments,$user_type);  
         
          
    if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharged" || $review_status == "Out-Patient" || $review_status == "Review")
      {      	

        $check_doc_id = $this->healthsupervisor_app_model->check_doc_id_of_request($doc_id, $user_type);

        if($check_doc_id == 'No Doc Found'){

        $insert_hospitalised = $this->healthsupervisor_app_model->update_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties, $user_type);
        
        }else if($review_status != $check_doc_id[0]['doc_data']['widget_data']['page2']['Review Info']['Status']){

        $insert_hospitalised = $this->healthsupervisor_app_model->update_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties, $user_type);
        }

      }

  	  $this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'HS Request submitted successfully')
									));
  	  if ($existing_update) // the information has therefore been successfully saved in the db
			{
				$this->output->set_output(json_encode(
											array(
												'status' => TRUE, 
												'message' => 'HS Request Updated successfully')
											));
				$issueinfo_2 = array();
				if(isset($normal_identifiers) && !empty($normal_identifiers))
				{
				  	foreach($normal_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	   
				  	$total_diseaes =  implode(",",$issueinfo_2);
				  	
		  	    }
		  	    if(isset($emergency_identifiers) && !empty($emergency_identifiers))
				{
				  	foreach($emergency_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);
		  	    }

		  	    if(isset($chronic_identifiers) && !empty($chronic_identifiers))
				{
				  	foreach($chronic_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);

		  	    }
		  	     $message = "Hi ".$hs_name.", Update ".$request_type." Request is created with ".$total_diseaes." Identifier is created for ".$student_name." with UID ".$unique_id." on ".date('Y-m-d').", Doctor will update You soon.";
		  	   
		  	  	   $this->bhashsms->send_sms($hs_mob,$message);
				$send_msg = $this->panacea_common_lib->send_message_to_doctors($request_type,$unique_id,$student_name,$total_diseaes);			
			
				
			}
			else
			{
				$this->output->set_output(json_encode(
											array(
												'status' => TRUE, 
												'message' => 'HS Request Not Updated')
											));
				//log_message('error','HS Request Not Updated 9999999999999999');
			}

  	  
  	}

	public function doctor_update_requests_docs()
	{

		if(isset($_POST['unique_id']))
		{
			////log_message('error','posttttttttttttttttttt=====316'.print_r($_POST['doc_id'],true));
			$user_type = $_POST['user_type'];
			$doc_id = $this->input->post('doc_id',TRUE);
	  		$unique_id = $this->input->post('unique_id',TRUE);
			$student_name = $this->input->post('page1_StudentInfo_Name',TRUE);
			$district = $this->input->post('page1_StudentInfo_District',TRUE);
			$school_name  = $this->input->post('page1_StudentInfo_SchoolName',TRUE);
			$class  = $this->input->post('page1_StudentInfo_Class',TRUE);
			$section  = $this->input->post('page1_StudentInfo_Section',TRUE);

			$scheduled_date = $this->input->post('scheduled_date', TRUE);
			$cc_user_follow = $this->input->post('cc_user_follow', TRUE);
			$add_to_regular_followup = $this->input->post('add_to_regular_followup', TRUE);

			$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
			if($gender_info == "B")
			{
				$gender = "Male";
			}else if($gender_info == "G")
			{
				$gender = "Female";
			}
			
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

	  		$normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
			$normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
			$normal_eyes_identifier      = $this->input->post('normal_eye_identifier',TRUE);
			$normal_ent_identifier      = $this->input->post('normal_ent_identifier',TRUE);
			$normal_rs_identifier       = $this->input->post('normal_rs_identifier',TRUE);
			$normal_cvs_identifier      = $this->input->post('normal_cvs_identifier',TRUE);
			$normal_gi_identifier       = $this->input->post('normal_gi_identifier',TRUE);
			$normal_gu_identifier       = $this->input->post('normal_gu_identifier',TRUE);
			$normal_gyn_identifier      = $this->input->post('normal_gyn_identifier',TRUE);
			$normal_cri_identifier      = $this->input->post('normal_cri_identifier',TRUE);
			$normal_msk_identifier      = $this->input->post('normal_msk_identifier',TRUE);
			$normal_cns_identifier      = $this->input->post('normal_cns_identifier',TRUE);
			$normal_psychiartic_identifier      = $this->input->post('normal_psychiartic_identifier',TRUE);

			$emergency_identifier 		= $this->input->post('emergency_identifier',TRUE);
			////log_message('error','emergency_identifier=========801'.print_r($emergency_identifier,TRUE));
			$emergency_bites_identifier = $this->input->post('emergency_bites_identifier',TRUE);
			
			$chronic_eyes_identifier  = $this->input->post('chronic_eyes_identifier',TRUE);
			$chronic_ent_identifier  = $this->input->post('chronic_ent_identifier',TRUE);
			$chronic_cns_identifier  = $this->input->post('chronic_cns_identifier',TRUE);
			$chronic_rs_identifier  = $this->input->post('chronic_rs_identifier',TRUE);
			$chronic_cvs_identifier  = $this->input->post('chronic_cvs_identifier',TRUE);
			$chronic_gi_identifier  = $this->input->post('chronic_gi_identifier',TRUE);
			$chronic_blood_identifier  = $this->input->post('chronic_blood_identifier',TRUE);
			$chronic_kidney_identifier  = $this->input->post('chronic_kidney_identifier',TRUE);
			$chronic_vandm_identifier  = $this->input->post('chronic_vandm_identifier',TRUE);
			$chronic_bones_identifier  = $this->input->post('chronic_bones_identifier',TRUE);
			$chronic_skin_identifier  = $this->input->post('chronic_skin_identifier',TRUE);
			$chronic_endo_identifier  = $this->input->post('chronic_endo_identifier',TRUE);
			$chronic_others_identifier  = $this->input->post('chronic_others_identifier',TRUE);
			
			$normal_identifiers = array(
					'General' 	=> !empty($normal_general_identifier) ? explode(", ",$normal_general_identifier) : [],
					'Head' => !empty($normal_head_identifier) ? explode(", ", $normal_head_identifier) : [],
					'Eyes' => !empty($normal_eyes_identifier) ?explode(", ",$normal_eyes_identifier) : [],
					'Ent' => !empty($normal_ent_identifier) ? explode(", ",$normal_ent_identifier) : [],
					'Respiratory_system' => !empty($normal_rs_identifier) ?explode(", ",$normal_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($normal_cvs_identifier) ? explode(", ",$normal_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($normal_gi_identifier) ? explode(", ",$normal_gi_identifier) : [],
					'Genito_urinary' => !empty($normal_gu_identifier) ? explode(", ",$normal_gu_identifier) : [],
					'Gynaecology' => !empty($normal_gyn_identifier) ? explode(", ",$normal_gyn_identifier) : [],
					'Endo_crinology' => !empty($normal_cri_identifier) ? explode(", ",$normal_cri_identifier) : [],
					'Musculo_skeletal_syatem' => !empty($normal_msk_identifier) ? explode(", ",$normal_msk_identifier) : [],
					'Central_nervous_system' => !empty($normal_cns_identifier) ? explode(", ",$normal_cns_identifier) : [],
					'Psychiartic' => !empty($normal_psychiartic_identifier) ? explode(", ",$normal_psychiartic_identifier) : []
				);

			$emergency_identifiers = array(
					'Disease' => !empty($emergency_identifier) ? explode(", ", $emergency_identifier) : [],
					'Bites'   => !empty($emergency_bites_identifier) ? explode(", ",$emergency_bites_identifier) : []	
				);

			$chronic_identifiers = array(
					'Eyes' => !empty($chronic_eyes_identifier) ? explode(", ",$chronic_eyes_identifier): [],
					'Ent'  => !empty($chronic_ent_identifier) ? explode(", ",$chronic_ent_identifier) :[],
					'Central_nervous_system' => !empty($chronic_cns_identifier) ? explode(", ",$chronic_cns_identifier) : [],
					'Respiratory_system' => !empty($chronic_rs_identifier) ? explode(", ",$chronic_rs_identifier) : [],
					'Cardio_vascular_system' => !empty($chronic_cvs_identifier) ? explode(", ",$chronic_cvs_identifier) : [],
					'Gastro_intestinal' => !empty($chronic_gi_identifier) ? explode(", ",$chronic_gi_identifier) : [],
					'Blood'  => !empty($chronic_blood_identifier) ? explode(", ",$chronic_blood_identifier) : [],
					'Kidney' => !empty($chronic_kidney_identifier) ? explode(", ",$chronic_kidney_identifier) : [],
					'VandM'  => !empty($chronic_vandm_identifier) ? explode(", ",$chronic_vandm_identifier) : [],
					'Bones'  => !empty($chronic_bones_identifier) ? explode(", ",$chronic_bones_identifier) : [],
					'Skin'   => !empty($chronic_skin_identifier) ? explode(", ",$chronic_skin_identifier) : [],
					'Endo'   => !empty($chronic_endo_identifier) ? explode(", ",$chronic_endo_identifier) : [],
					'Others' => !empty($chronic_others_identifier) ? explode(", ",$chronic_others_identifier) : []					
				);

			$problem_info_description  = $this->input->post('page2_ProblemInfo_Description',TRUE);
			
			$doctor_summary  = $this->input->post('page2_DiagnosisInfo_DoctorSummary',TRUE);
			$doctor_advice  = $this->input->post('page2_DiagnosisInfo_DoctorAdvice',TRUE);
			$prescription  = $this->input->post('page2_DiagnosisInfo_Prescription',TRUE);				

			$request_type  = $this->input->post('page2_ReviewInfo_RequestType',TRUE);
			$review_status = $this->input->post('page2_ReviewInfo_Status',TRUE);

			$std_join_hospital_name = $this->input->post('std_join_hospital_name', true);
            $std_join_hospital_type = $this->input->post('std_join_hospital_type', true);
            $std_join_hospital_dist = $this->input->post('std_join_hospital_dist', true);
            $hospitalised_date = $this->input->post('hospitalised_date', true);       
            $discharge_date = $this->input->post('discharge_date', true);

		  // Personal information Page 1
			$doc_data['page1']['Student Info']['Unique ID']    = ($unique_id) ? $unique_id : '' ;
			$doc_data['page1']['Student Info']['Name']['field_ref']    	 = ($student_name) ? $student_name : '';
			$doc_data['page1']['Student Info']['District']['field_ref']    = ($district) ? $district : '';
			$doc_data['page1']['Student Info']['School Name']['field_ref']    =($school_name) ? $school_name : '';
			$doc_data['page1']['Student Info']['Class']['field_ref']    = ($class) ? $class : '' ;
			$doc_data['page1']['Student Info']['Section']['field_ref']    = ($section) ? $section : '';
			$doc_data['page1']['Student Info']['Gender']    = (!empty($gender) && isset($gender)) ? $gender : '';
			$doc_data['page1']['Student Info']['Age']    = (!empty($age) && isset($age)) ? $age : '';
			
			if(isset($normal_identifiers) && !empty($normal_identifiers))
			{
				$doc_data['page1']['Problem Info']['Normal']  = $normal_identifiers;
			}
			if(isset($emergency_identifiers) && !empty($emergency_identifiers))
			{
				$doc_data['page1']['Problem Info']['Emergency']  = $emergency_identifiers;	
				
			}
			if(isset($chronic_identifiers) && !empty($chronic_identifiers))
			{
				$doc_data['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
			}						
			
			//Description Page2
			$doc_data['page2']['Problem Info']['Description']    = ($problem_info_description) ? $problem_info_description : '';
			//Doctor Summary Page2
			$doc_data['page2']['Diagnosis Info']['Doctor Summary']  = !empty($doctor_summary) ? $doctor_summary : "";
			$doc_data['page2']['Diagnosis Info']['Doctor Advice']  = !empty($doctor_advice) ? $doctor_advice : "";
			$doc_data['page2']['Diagnosis Info']['Prescription']  = !empty($prescription) ? 
			$prescription : "" ;

			//Request Type and Status Page2
			$doc_data['page2']['Review Info']['Request Type']    = ($request_type) ? $request_type : '';
			$doc_data['page2']['Review Info']['Status']    = ($review_status) ? $review_status : '';

			if($review_status == 'Hospitalized' || $review_status == 'Out-Patient' || $review_status == 'Review')
			{
			    $doc_data['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
			    $doc_data['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;         
			    $doc_data['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
			    $doc_data['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
			}
			
			if($review_status == 'Discharged')
			{
			    $doc_data['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
			}
			
			$doc_data_external_attachments = "";
			// Attachments
			 if(isset($_FILES) && !empty($_FILES))
			 {
		       $this->load->library('upload');
		       $this->load->library('image_lib');
			   
			   $external_files_upload_info = array();
			   $external_final             = array();
			   
			   $files = $_FILES;
			   $cpt = count($_FILES['hs_req_attachments']['name']);
			   for($i=0; $i<$cpt; $i++)
			   {
				 $_FILES['hs_req_attachments']['name']	= $files['hs_req_attachments']['name'][$i];
				 $_FILES['hs_req_attachments']['type']	= $files['hs_req_attachments']['type'][$i];
				 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
				 $_FILES['hs_req_attachments']['error']	= $files['hs_req_attachments']['error'][$i];
				 $_FILES['hs_req_attachments']['size']	= $files['hs_req_attachments']['size'][$i];
			
			   foreach ($_FILES as $index => $value)
		       {
				  if(!empty($value['name']))
				  {
				       if($user_type == "PANACEA_DOCTOR"){
			  			$controller = 'healthcare2016531124515424_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
			  		}else if($user_type == "TTWREIS_DOCTOR")
			  		{
			  			$controller = 'healthcare2016108181933756_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
			  		}else if($user_type == "TMREIS_DOCTOR")
			  		{
			  			$controller = 'healthcare201610114435690_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
			  		}else if($user_type == "BCWELFARE_DOCTOR")
			  		{
			  			$controller = 'healthcare2018122191146894_con';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
			  		}
			
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 
								redirect('tswreis_schools/hs_request');  
							
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
						$rand_number = mt_rand();
						$external_data_array = array(
												  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
												"file_client_name" =>$external_files_upload_info['client_name'],
												"file_encrypted_name" =>$external_files_upload_info['file_name'],
												"file_path" =>$external_files_upload_info['file_relative_path'],
												"file_size" =>$external_files_upload_info['file_size']
																)

													 );

						$external_final = array_merge($external_final,$external_data_array);
						}  
					}
				}
			 }

				$doc_history = $this->healthsupervisor_app_model->get_history($unique_id,$doc_id,$user_type);

				  if(isset($doc_history[0]['doc_data']['external_attachments']))
				  {
						   
					$external_merged_data = array_merge($doc_history[0]['doc_data']['external_attachments'],$external_final);
					$doc_data_external_attachments = array_replace_recursive($doc_history[0]['doc_data']['external_attachments'],$external_merged_data);
				  }
				  else
				 {
				    $doc_data_external_attachments = $external_final;
				 } 
		  
			 }
			
				//$doc_history = $this->healthsupervisor_app_model->get_history($unique_id,$doc_id);
				
				// school data
			 $school_data_array = explode("_",$unique_id);
			 if(empty($school_data_array[1]))
			 {
			 	log_message('error','unique_id=============Healthsupervisor_app==='.print_r($unique_id,true));
			 }else
			 {
			 	 $schoolCode        = (int) $school_data_array[1];
			 }	

			 $school_data = $this->healthsupervisor_app_model->get_school_information_for_school_code($schoolCode,$user_type);

		  $health_supervisor = $this->healthsupervisor_app_model->get_health_supervisor_details($schoolCode,$user_type);
			 	 $hs_name = $health_supervisor['hs_name'];
			 	 $hs_mob  = $health_supervisor['hs_mob'];

			$school_contact_details = array(
		 		'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
		 		'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
		 	);

		// $doc_data['school_contact_details']  = $school_contact_details;

			 	$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";
				$doc_properties['doc_access'] = "false";
				$doc_properties['access_by'] = "";
				$doc_properties['doc_access_time'] = 0;
				
				
		 		$email = $this->input->post('submitted_by',TRUE);
		 		//$doctor_email = str_replace('@', '#', $email); 
				$doctor_name = $this->input->post('submitted_by_name',TRUE); 
			 	//POST DATA
				$redirected_stage   = "HS 2";
				$current_stage      = "Doctor";
				//$doc_id 			= $form_data['docid'];
				//$reason             = implode(", ",$reason_array);
				//$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
				$redirected_stage	= $redirected_stage;
				$current_stage	    = $current_stage;
				//$disapproving_user	= $username;
				$stage_name 		= "Doctor";

			
                 $approval_data = array(
                 	"current_stage" => "Doctor",
                 	"approval" => "true",
                 	"submitted_by" => $email,
                 	"submitted_by_name" => $doctor_name,
                 	'raised_by' => "device_side",
                 	"time" => date('Y-m-d H:i:s'));           

            $approval_history = $this->healthsupervisor_app_model->get_approval_history($doc_id,$user_type);
                array_push($approval_history,$approval_data);

               // log_message('error','approval_history=========1272'.print_r($approval_history,TRUE));

        // Regular Followups
           if($add_to_regular_followup == "Yes"){

           	$check_regular = $this->healthsupervisor_app_model->check_and_get_if_regular_is_there($doc_id, $unique_id);

           	if($check_regular == 'No Data'){
           		$created = date('Y-m-d');
           		      	
           		      	$push_data = array();

           		      	$medicines = !empty($prescription) ? $prescription : "";
           		      	$follow_descs = !empty($doctor_summary) ? $doctor_summary : "";
           		      	$data = array('medicine_details'=>$medicines, 'followup_desc'=>$follow_descs,'created_time'=> $created, 'next_scheduled_date' => $scheduled_date);
           		      	array_push($push_data, $data);

           		      	$followups = array(
           		      						'Followup_start_date' => $created,
           		      						'CC_follow_name' => $cc_user_follow,
           		      						'Active_status' => 1,
           		      						'Follow_Up' => $push_data
           		      					);
           	} else {

           		//$followups = $check_regular[0]['regular_follow_up'];
           		$followups = FALSE;
           		//echo print_r($regular_data,true); exit();

           	}
           	

           }else{
           	$followups = FALSE;
           } 

  	 	$submitted_data_doctor = $this->healthsupervisor_app_model->request_docs_update_doctor_model($doc_data,$doc_properties,$approval_history,$unique_id,$doc_id,$user_type,$doc_data_external_attachments, $followups);

         if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharged")
      {
        //$insert_hospitalised = $this->maharashtra_doctor_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);

        $check_doc_id = $this->healthsupervisor_app_model->check_doc_id_of_request($doc_id,$user_type);
        
        if($check_doc_id == 'No Doc Found'){
            $insert_hospitalised = $this->healthsupervisor_app_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties,$user_type);
        }
      }  
  	 		 	
  	 		if($submitted_data_doctor)
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'HS Request submitted successfully')
									));
				$issueinfo_2 = array();
				if(isset($normal_identifiers) && !empty($normal_identifiers))
				{
				  	foreach($normal_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	   
				  	$total_diseaes =  implode(",",$issueinfo_2);
				  	
		  	    }
		  	    if(isset($emergency_identifiers) && !empty($emergency_identifiers))
				{
				  	foreach($emergency_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);
		  	    }

		  	    if(isset($chronic_identifiers) && !empty($chronic_identifiers))
				{
				  	foreach($chronic_identifiers as $issueInfo)
				  	{
				  		$diseaes = implode(',',$issueInfo);
				  		if(!empty($diseaes))
				  		{
				  			array_push($issueinfo_2, $diseaes);
				  		}
				  	 }
				  	$total_diseaes =  implode(",",$issueinfo_2);

		  	    }

				$message = "Dr Response : Name : ".$student_name." U ID : ".$unique_id." Request Type : ".$request_type." Issues:".$total_diseaes;
						$sms =  $this->bhashsms->send_sms($hs_mob,$message);						
				
			}
			else
			{
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed to submit ! Try again')
									));
			}
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'REQUIRED_PARAMS_MISSING')
									));
		}
	}

	public function medical_evaluation_app()
	{
		
		//$postdata = file_get_contents('php://input');
		//$post_hs_data = $data;
		//$postdata = $_POST['data'];
		//$post_data = json_decode($postdata,true);
		$post_data = $_POST['data'];
		$post_data = json_decode($post_data,true);
		//log_message('error',"post_data===============1376======sync".print_r($post_data,true));
		//$student_data = json_decode($profile_data,TRUE);
		//log_message('error',"Student_dataaaaaa===============1377======sync".print_r($student_data,true));
		////log_message('error',"post_hs_data===============317".print_r($postdata,true));
		////log_message('error',"post_data===============319".print_r($post_data,true));
		$unique_id = $post_data['UID'];
		$dist_code = explode('_', $unique_id);
		$user_type = $_POST['USER_TYPE'];
		//$school_info = $this->healthsupervisor_app_model->get_school_info_for_screening($dist_code[1],$user_type);
		//$school_name = $school_info[0]['school_name'];
		$school_name = $post_data['SCHOOLNAME'];
		//$dist = explode(',', $school_name);
		//$districtName = $dist[1];		
		$districtName = $post_data['DISTRICT'];		
		
		/*$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
		
		if($gender_info == "B")
		{
			$GENDER = "Male";
		}else if($gender_info == "G")
		{
			$GENDER = "Female";
		}*/

		$class = $post_data['CLASS'];

		/*$age = "";
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
		}*/

		if($post_data)
		{
			
			$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $post_data['UID'];
			$doc_data['widget_data']['page1']['Personal Information']['Name'] = $post_data['NAME'];
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = "+91";
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $post_data['MOBILE'];
			$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $post_data['DATEOFBIRTH'];
			$doc_data['widget_data']['page2']['Personal Information']['Gender'] = $post_data['GENDER'];
			//$doc_data['widget_data']['page2']['Personal Information']['Age'] = ($age) ? $age : "";


			$doc_data['widget_data']['page2']['Personal Information']['Class'] = $post_data['CLASS'];
			$doc_data['widget_data']['page2']['Personal Information']['Section'] = $post_data['SECTION'];
			$doc_data['widget_data']['page1']['Personal Information']['Ad No'] = $post_data['ADNO']; // school admisiion NO
			//$doc_data['widget_data']['page1']['Personal Information']['Aadhar No'] = $post_data['AADHAR']; // aDDHAR NO
			$doc_data['widget_data']['page2']['Personal Information']['School Name'] = $school_name;
			$doc_data['widget_data']['page2']['Personal Information']['District'] = $districtName;
			$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = $post_data['FATHERNAME'];
			$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = $post_data['DATEOFEXAM'];


			$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = $post_data['HEIGHT'];
			$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = $post_data['WEIGHT'];
			$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = $post_data['BMI'];
			$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = $post_data['PULSE'];
			$doc_data['widget_data']['page3']['Physical Exam']['B P'] = $post_data['BP'];
			$doc_data['widget_data']['page3']['Physical Exam']['H B'] = $post_data['HB'];
			$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = $post_data['BLOODGROUP'];

			$doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = (!empty($post_data['ABNORMALITIES'])) ? explode(',', $post_data['ABNORMALITIES']) : [];
			$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = !empty($post_data['ORTHO']) ? explode(',', $post_data['ORTHO']) : [];
			$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = !empty($post_data['POSTURAL']) ? explode(',', $post_data['POSTURAL']) : [];
			$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = $post_data['GENERAL_DESCRIPTION'];
			$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = $post_data['GENERAL_DESCRIPTION_TREATMENT'];
			$doc_data['widget_data']['page4']['Doctor Check Up']['Skin Conditions'] = !empty($post_data['SKIN_CONDITIONS']) ? explode(',', $post_data['SKIN_CONDITIONS']) : [];

			$doc_data['widget_data']['page4']['Doctor Check Up']['Menstrural'] = isset($post_data['MENSURAL']) ? $post_data['MENSURAL'] :"";

			$doc_data['widget_data']['page4']['Doctor Check Up']['Mensural Description'] = isset($post_data['MENSURALDESCREPTION']) ? $post_data['MENSURALDESCREPTION'] :"";

			$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = !empty($post_data['DEFECTS_AT_BIRTH']) ? explode(',', $post_data['DEFECTS_AT_BIRTH']) : [];
			$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = !empty($post_data['DEFICIENCIES']) ? explode(',', $post_data['DEFICIENCIES']) : [];
			$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = !empty($post_data['CHILDHOOD_DISEASES']) ? explode(',', $post_data['CHILDHOOD_DISEASES']) : [];
			$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = !empty($post_data['NAD']) ? explode(',', $post_data['NAD']) : [];
			$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = "";

			$doc_data['widget_data']['page6']['Screenings'] = [];
			$doc_data['widget_data']['page6']['Without Glasses']['Right'] = $post_data['WITHOUTR'];
			$doc_data['widget_data']['page6']['Without Glasses']['Left'] = $post_data['WITHOUTL'];
			$doc_data['widget_data']['page6']['With Glasses']['Right'] = $post_data['WITHR'];
			$doc_data['widget_data']['page6']['With Glasses']['Left'] = $post_data['WITHL'];

			$doc_data['widget_data']['page7']['Colour Blindness']['Right'] = $post_data['COLORBLINDNESSR'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Left'] = $post_data['COLORBLINDNESSL'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Eye Lids'] = $post_data['EYELID'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Conjunctiva'] = $post_data['CONJUNCTIVA'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Cornea'] = $post_data['CORNEA'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Pupil'] = $post_data['PUPIL'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Complaints'] = !empty($post_data['COMPLAINTS']) ? explode(',', $post_data['COMPLAINTS']) : [];
			$doc_data['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'] = $post_data['WEARGLASSES'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Subjective Refraction'] = $post_data['SUBREFRACTION'];
			$doc_data['widget_data']['page7']['Colour Blindness']['Ocular Diagnosis'] = !empty($post_data['OCULARDIAGNOSIS']) ? explode(',', $post_data['OCULARDIAGNOSIS']) : [];
			$doc_data['widget_data']['page7']['Colour Blindness']['Referral Made'] = $post_data['VISION_REF'];
			$doc_data['widget_data']['page7']['Colour Blindness']['TREATMENT_ADVISE_OHD'] = !empty($post_data['TREATMENT_ADVISE_OHD']) ? explode(',', $post_data['TREATMENT_ADVISE_OHD']) : [];
			$doc_data['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'] = [];


			$doc_data['widget_data']['page8']['Auditory Screening']['Right'] = $post_data['AUDITORY_RIGHT'];
			$doc_data['widget_data']['page8']['Auditory Screening']['Left'] = $post_data['AUDITORY_LEFT'];
			$doc_data['widget_data']['page8']['Auditory Screening']['Speech Screening'] = !empty($post_data['SPEECH_SCREENING']) ? explode(',', $post_data['SPEECH_SCREENING']) : [];
			$doc_data['widget_data']['page8']['Auditory Screening']['D D and disability'] = !empty($post_data['DD_DISABILITY']) ? explode(',', $post_data['DD_DISABILITY']) : [];
			$doc_data['widget_data']['page8']['Auditory Screening']['Description'] = $post_data['AUDITORY_ADVICE'];
			$doc_data['widget_data']['page8']['Auditory Screening']['Referral Made'] = $post_data['AUDITORY_REF'];
			$doc_data['widget_data']['page8']['Auditory Screening']['Audiologist Sign'] = [];



			$doc_data['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] = $post_data['ORAL_HYGIENE'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Carious Teeth'] = $post_data['CARIOUS_TEETH'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Flourosis'] = $post_data['FLUOROSIS'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] = $post_data['ORTHODONTIC_TREATMENT'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Indication for extraction'] = $post_data['INDICATION_FOR_EXTRACTION'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Root Canal Treatment'] = $post_data['ROOT_CANAL_TREAT'];
			$doc_data['widget_data']['page9']['Dental Check-up']['CROWNS'] = $post_data['CROWNS'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'] = $post_data['FIXED_PARTIAL_DENTURE'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Curettage'] = $post_data['CUREHAGE'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Estimated Amount'] = $post_data['ESTIMATED_AMOUNT'];
			$doc_data['widget_data']['page9']['Dental Check-up']['DC 11'] = $post_data['DC11'];
			$doc_data['widget_data']['page9']['Dental Check-up']['DC 12'] = $post_data['DC12'];
			$doc_data['widget_data']['page9']['Dental Check-up']['DC 13'] = $post_data['DC13'];
			$doc_data['widget_data']['page9']['Dental Check-up']['DC 14'] = $post_data['DC14'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Referral Made'] = $post_data['DENTAL_REF'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Result'] = $post_data['DENTAL_DESCRIPTION'];
			$doc_data['widget_data']['page9']['Dental Check-up']['Dentist Sign'] = "";


			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 2;
			$doc_properties['_version'] = 2;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";


			// History
			$approval_data = array(
				"current_stage" => "stage1",
				"doc_owner" => "tswreis",
				"submitted_by" => 'medusersw1@gmail.com',
				"time" => date('Y-m-d H:i:s'),
				"approval" => "true",
				'synced_date' => Date('Y-m-d'),
	            "Version" => "2.1");

			$history['last_stage'] = $approval_data;		
			
			
	  		if(isset($_FILES) && !empty($_FILES))
			{
		       $this->load->library('upload');
		       $this->load->library('image_lib');			  
	  		   
	  		  
	  		   		foreach ($_FILES as $index => $value)
			       {
			       	  if(!empty($value['name']) && $index == 'student_image')
					  {		
					  		if($user_type == "tswreis"){
					  			$controller = 'healthcare2016226112942701_con';
					  		}elseif ($user_type == "ttwreis") {
					  			$controller = 'healthcare201671115519757_con';
					  		}elseif($user_type == "bcwelfare"){
					  			$controller = 'healthcare201812217594045_con';
					  		}

					        $config = array();
							$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/photo/';
							$config['allowed_types'] = '*';
							$config['max_size']      = '4096';
							$config['encrypt_name']  = TRUE;
						
					        //create controller upload folder if not exists
							if (!is_dir($config['upload_path']))
							{
								mkdir(UPLOADFOLDERDIR."public/uploads/$controller/photo/",0777,TRUE);
							}
				
							$this->upload->initialize($config);
							
							if ( ! $this->upload->do_upload($index))
							{
								echo "file upload failed";
								return FALSE;
							}
							else
							{
								$photo_obj = $this->upload->data();
							 	$photo_ele = array(
									"file_client_name"    => $photo_obj['client_name'],
									"file_encrypted_name" => $photo_obj['file_name'],
									"file_path" 		  => $photo_obj['file_relative_path'],
									"file_size" 		  => $photo_obj['file_size']
						 		 );			
						 		 $doc_data['widget_data']['page1']['Personal Information']['Photo'] = $photo_ele;	 				
							}  
						}
					}
	  		   	   					 
		 	}else{
		 		 $doc_data['widget_data']['page1']['Personal Information']['Photo']= "";	 				
		 	}

		 	 // Attachments
		 	if(isset($_FILES) && !empty($_FILES))
			{
		       $this->load->library('upload');
		       $this->load->library('image_lib');
			   
			   $external_files_upload_info = array();
			   $external_final             = array();
			   $external_merged_data       = array();

			   $subjective_files_upload_info = array();
			   $subjective_final             = array();
			   $subjective_merged_data       = array();

			   $ocular_files_upload_info = array();
			   $ocular_final             = array();
			   $ocular_merged_data       = array();

			   $mef_files_info = array();
		       $mef_final = array();
		       $mef_merged_data = array();

		       $doc_history = $this->healthsupervisor_app_model->get_student_history($unique_id, $user_type);

			   if(isset($_FILES['external_attachments']['name']) && !empty($_FILES['external_attachments']['name']))
			   {
			   	   $files = $_FILES;
				   $cpt = count($_FILES['external_attachments']['name']);
				   for($i=0; $i<$cpt; $i++)
				   {
					 $_FILES['external_attachments']['name']	= $files['external_attachments']['name'][$i];
					 $_FILES['external_attachments']['type']	= $files['external_attachments']['type'][$i];
					 $_FILES['external_attachments']['tmp_name']= $files['external_attachments']['tmp_name'][$i];
					 $_FILES['external_attachments']['error']	= $files['external_attachments']['error'][$i];
					 $_FILES['external_attachments']['size']	= $files['external_attachments']['size'][$i];
					
				   foreach ($_FILES as $index => $value)
			       {
			       
			       		if(!empty($value['name']) && $index == 'external_attachments')
					  	{
					  		if($user_type == "tswreis"){
					  			$controller = 'healthcare2016226112942701_con';
					  		}elseif ($user_type == "ttwreis") {
					  			$controller = 'healthcare201671115519757_con';
					  		}elseif($user_type == "bcwelfare"){
					  			$controller = 'healthcare201812217594045_con';
					  		}elseif ($user_type == "private_schools") {
					  			$controller = 'private_schools';
					  		}
					        $config = array();
							$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
							$config['allowed_types'] = '*';
							$config['max_size']      = '4096';
							$config['encrypt_name']  = TRUE;
					  	
					        //create controller upload folder if not exists
							if (!is_dir($config['upload_path']))
							{
								mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
							}
				
							$this->upload->initialize($config);
							
							if ( ! $this->upload->do_upload($index))
							{
								 echo "external file upload failed";
				        		 return FALSE;
							}
							else
							{
								$external_files_upload_info = $this->upload->data();
								$rand_number = mt_rand();
								$external_data_array = array(
														  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
														"file_client_name" =>$external_files_upload_info['client_name'],
														"file_encrypted_name" =>$external_files_upload_info['file_name'],
														"file_path" =>$external_files_upload_info['file_relative_path'],
														"file_size" =>$external_files_upload_info['file_size']
																		) );

								$external_final = array_merge($external_final,$external_data_array);
								
							}  
						}
					}
				 }
				 
				   if(isset($doc_data['external_attachments']))
					  {
							   
						$external_merged_data = array_merge($doc_data['doc_data']['external_attachments'],$external_final);
						$doc_data['doc_data']['external_attachments'] = array_replace_recursive($doc_data['doc_data']['external_attachments'],$external_merged_data);
					  }
					  else
					 {
					    $doc_data['external_attachments'] = $external_final;
					 }
			   }else
			   {
			   		$doc_data['external_attachments'] = [];
			   }
			   if(isset($_FILES['subjective_refraction_attachments']['name']) && !empty($_FILES['subjective_refraction_attachments']['name']))
			   {
			   	   $files = $_FILES;
				   $cpt = count($_FILES['subjective_refraction_attachments']['name']);
				   for($i=0; $i<$cpt; $i++)
				   {
					 $_FILES['subjective_refraction_attachments']['name']	= $files['subjective_refraction_attachments']['name'][$i];
					 $_FILES['subjective_refraction_attachments']['type']	= $files['subjective_refraction_attachments']['type'][$i];
					 $_FILES['subjective_refraction_attachments']['tmp_name']= $files['subjective_refraction_attachments']['tmp_name'][$i];
					 $_FILES['subjective_refraction_attachments']['error']	= $files['subjective_refraction_attachments']['error'][$i];
					 $_FILES['subjective_refraction_attachments']['size']	= $files['subjective_refraction_attachments']['size'][$i];
					
				   foreach ($_FILES as $index => $value)
			       {
			       		if(!empty($value['name']) && $index == 'subjective_refraction_attachments')
					  	{
					  		if($user_type == "tswreis"){
					  			$controller = 'healthcare2016226112942701_con';
					  		}elseif ($user_type == "ttwreis") {
					  			$controller = 'healthcare201671115519757_con';
					  		}elseif($user_type == "bcwelfare"){
					  			$controller = 'healthcare201812217594045_con';
					  		}elseif ($user_type == "private_schools") {
					  			$controller = 'private_schools';
					  		}
					        $config = array();
							$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
							$config['allowed_types'] = '*';
							$config['max_size']      = '4096';
							$config['encrypt_name']  = TRUE;
					  	
					        //create controller upload folder if not exists
							if (!is_dir($config['upload_path']))
							{
								mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
							}
				
							$this->upload->initialize($config);
							
							if ( ! $this->upload->do_upload($index))
							{
								 echo "subjective attachments file upload failed";
				        		 return FALSE;
							}
							else
							{
								$subjective_files_upload_info = $this->upload->data();
								$rand_number = mt_rand();
								$subjective_data_array = array(
														  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
														"file_client_name" =>$subjective_files_upload_info['client_name'],
														"file_encrypted_name" =>$subjective_files_upload_info['file_name'],
														"file_path" =>$subjective_files_upload_info['file_relative_path'],
														"file_size" =>$subjective_files_upload_info['file_size']
																		) );

								$subjective_final = array_merge($subjective_final,$subjective_data_array);
								
							}  
						}
					}
				 }
				 
				   if(isset($doc_data['subjective_refraction_attachments']))
					  {
							   
						$subjective_merged_data = array_merge($doc_data['doc_data']['subjective_refraction_attachments'],$subjective_final);
						$doc_data['doc_data']['subjective_refraction_attachments'] = array_replace_recursive($doc_data['doc_data']['subjective_refraction_attachments'],$subjective_merged_data);
					  }
					  else
					 {
					    $doc_data['subjective_refraction_attachments'] = $subjective_final;
					 }
			   }else
			   {
			   		$doc_data['subjective_refraction_attachments'] = [];
			   }
			   if(isset($_FILES['ocular_diagnosis_attachments']['name']) && !empty($_FILES['ocular_diagnosis_attachments']['name']))
			   {
			   	   $files = $_FILES;
				   $cpt = count($_FILES['ocular_diagnosis_attachments']['name']);
				   for($i=0; $i<$cpt; $i++)
				   {
					 $_FILES['ocular_diagnosis_attachments']['name']	= $files['ocular_diagnosis_attachments']['name'][$i];
					 $_FILES['ocular_diagnosis_attachments']['type']	= $files['ocular_diagnosis_attachments']['type'][$i];
					 $_FILES['ocular_diagnosis_attachments']['tmp_name']= $files['ocular_diagnosis_attachments']['tmp_name'][$i];
					 $_FILES['ocular_diagnosis_attachments']['error']	= $files['ocular_diagnosis_attachments']['error'][$i];
					 $_FILES['ocular_diagnosis_attachments']['size']	= $files['ocular_diagnosis_attachments']['size'][$i];
					
				   foreach ($_FILES as $index => $value)
			       {
			       		if(!empty($value['name'])  && $index == 'ocular_diagnosis_attachments')
					  	{
					  		
					  		if($user_type == "tswreis"){
					  			$controller = 'healthcare2016226112942701_con';
					  		}elseif ($user_type == "ttwreis") {
					  			$controller = 'healthcare201671115519757_con';
					  		}elseif($user_type == "bcwelfare"){
					  			$controller = 'healthcare201812217594045_con';
					  		}elseif ($user_type == "private_schools") {
					  			$controller = 'private_schools';
					  		}
					        $config = array();
							$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
							$config['allowed_types'] = '*';
							$config['max_size']      = '4096';
							$config['encrypt_name']  = TRUE;
					  	
					        //create controller upload folder if not exists
							if (!is_dir($config['upload_path']))
							{
								mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
							}
				
							$this->upload->initialize($config);
							
							if ( ! $this->upload->do_upload($index))
							{
								 echo "ocular attachments file upload failed";
				        		 return FALSE;
							}
							else
							{
								$ocular_files_upload_info = $this->upload->data();
								$rand_number = mt_rand();
								$ocular_data_array = array(
														  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
														"file_client_name" =>$ocular_files_upload_info['client_name'],
														"file_encrypted_name" =>$ocular_files_upload_info['file_name'],
														"file_path" =>$ocular_files_upload_info['file_relative_path'],
														"file_size" =>$ocular_files_upload_info['file_size']
																		) );

								$ocular_final = array_merge($ocular_final,$ocular_data_array);
								
							}  
						}
					}
				 }
				 
				  if(isset($doc_data['ocular_diagnosis_attachments']))
				  {
						   
					$ocular_merged_data = array_merge($doc_data['doc_data']['ocular_diagnosis_attachments'],$ocular_final);
					$doc_data['doc_data']['ocular_diagnosis_attachments'] = array_replace_recursive($doc_data['doc_data']['ocular_diagnosis_attachments'],$ocular_merged_data);
				  }
				  else
				 {
				    $doc_data['ocular_diagnosis_attachments'] = $ocular_final;
				 }
			   }else
			   {
			   		$doc_data['ocular_diagnosis_attachments'] = [];
			   }

			/* MEF Forms */

			/* MEF Forms */
				   if(isset($_FILES['mef_files']['name']) && !empty($_FILES['mef_files']['name']))
				   {
				   	   $files = $_FILES;
					   $cpt = count($_FILES['mef_files']['name']);
					    
					   for($i=0; $i<$cpt; $i++)
					   {
						 $_FILES['mef_files']['name']	= $files['mef_files']['name'][$i];
						 $_FILES['mef_files']['type']	= $files['mef_files']['type'][$i];
						 $_FILES['mef_files']['tmp_name'] = $files['mef_files']['tmp_name'][$i];
						 $_FILES['mef_files']['error']	= $files['mef_files']['error'][$i];
						 $_FILES['mef_files']['size']	= $files['mef_files']['size'][$i];
						
					   foreach ($_FILES as $index => $value)
				       {			       
				       
				       		if(!empty($value['name'] && $index == 'mef_files'))
						  	{

						  		if($user_type == "tswreis" || $user_type == "PANACEA_HS"){
						  			$controller = 'mef_external_files';
						  		}elseif ($user_type == "ttwreis" || $user_type == "TTWREIS_HS") {
						  			$controller = 'ttwreis_mef_external_files';
						  		}elseif($user_type == "bcwelfare" || $user_type == "BCWELFARE_HS"){
						  			$controller = 'bcwelfare_mef_external_files';
						  		}elseif ($user_type == "private_schools" || $user_type == "PRIVATE_SCHOOLS") {
					  			$controller = 'private_schools_mef';
					  			}

						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/files/'.$controller.'/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '*';
								$config['encrypt_name']  = TRUE;
						  	
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/files/$controller/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									 echo "MEF file upload failed";
					        		 return FALSE;
								}
								else
								{
									$mef_files_info = $this->upload->data();
									//log_message('debug', 'mef_files_info=======5849'.print_r($mef_files_info, true));
									$rand_number = mt_rand();
									$mef_external_screening_data_array = array(
															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
															"file_name" =>$mef_files_info['file_name'],
															"file_path" =>$mef_files_info['file_relative_path'],
															"file_size" =>$mef_files_info['file_size']
														)	);

									$mef_final = array_merge($mef_final,$mef_external_screening_data_array);
									
								}  
							}
						}
					}
					 
					   if(isset($doc_history[0]['doc_data']['widget_data']['mef_attachments']) || isset($doc_history[0]['doc_data']['mef_attachments']))
						  {
							if(!empty($doc_history[0]['doc_data']['widget_data']['mef_attachments'])){
								$external_screening_merged_data = array_merge($doc_history[0]['doc_data']['widget_data']['mef_attachments'],$mef_final);
							}else{
								$external_screening_merged_data = array_merge($doc_history[0]['doc_data']['mef_attachments'],$mef_final);
							}
							
							if(!empty($doc_history[0]['doc_data']['widget_data']['mef_attachments'])){
								$doc_data['mef_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['widget_data']['mef_attachments'],$external_screening_merged_data);
							}else{
								$doc_data['mef_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['mef_attachments'],$external_screening_merged_data);
							}
							
						  }
						  else
						 {
						    $doc_data['mef_attachments'] = $mef_final;
						 }
					   }else
					   {
					   		$doc_data['mef_attachments'] = [];
					   }

			/*END mef forms*/

			/* End MEF forms attachments*/
		 }
		 	
		 	$status = $this->healthsupervisor_app_model->insert_medical_information_sync($doc_data,$history,$doc_properties,$user_type);
		 	if(!empty($status))
		 	{
		 		$this->output->set_output(json_encode(array('Status' => 'Successfully submitted!',
	 														'Uniqueid' => $post_data['UID'])));
		 	}else{
		 		$this->output->set_output(json_encode(array('Status' => 'Sync Failed')));
		 	}
		}
			  
	}

	public function get_documents_based_on_synced_date()
	{
		$synced_date = $_POST['today_date'];
		//$synced_date = Date('Y-m-d');
		$docs = $this->healthsupervisor_app_model->get_documents_based_on_synced_date($synced_date);
		if(!empty($docs))
		{
			$this->output->set_output(json_encode($docs));
		}else{
			$this->output->set_output(json_encode(array('Status' => 'NO Documents Synced!')));
		}
	}
	
	function get_update_docs_normal()
	{
		$post = $_POST['normal'];
		$user_type = $_POST['user_type'];
		if(isset($_POST['count']) && !empty($_POST['count']))
		{			
			$limit = $_POST['count'];
			$request_type = $_POST['request_state'];
			$query = $this->healthsupervisor_app_model->get_update_docs_normal($post,$user_type,$limit,$request_type);
		}else
		{			
			$query = $this->healthsupervisor_app_model->get_update_docs_normal($post,$user_type);
		}
		$this->output->set_output(json_encode($query));
		
	}
	function get_update_docs_emergency()
	{
		$post = $_POST['emergency'];
		$user_type = $_POST['user_type'];
		
		//$query = $this->healthsupervisor_app_model->get_update_docs_emergency($post,$user_type,$request_type);
		
		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
			$request_type = $_POST['request_state'];
			$query = $this->healthsupervisor_app_model->get_update_docs_emergency($post,$user_type,$limit,$request_type);
		}else
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_emergency($post,$user_type);
		}
		$this->output->set_output(json_encode($query));
		
	}

	function get_update_docs_chronic()
	{
		$post = $_POST['chronic'];
		$user_type = $_POST['user_type'];
		//$query = $this->healthsupervisor_app_model->get_update_docs_chronic($post,$user_type);
		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
			$request_type = $_POST['request_state'];
			$query = $this->healthsupervisor_app_model->get_update_docs_chronic($post,$user_type,$limit,$request_type);
		}else
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_chronic($post,$user_type);
		}
		$this->output->set_output(json_encode($query));
		
	}


	/*function get_update_docs_normal()
	{
		$post = $_POST['normal'];
		$user_type = $_POST['user_type'];
		$limit = $_POST['count'];
		$type = $_POST['request_state'];
		//$query = $this->healthsupervisor_app_model->get_update_docs_normal($post,$user_type);
		if(isset($limit) && !empty($limit))
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_normal($post,$user_type,$limit,$type);
		}else
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_normal($post,$user_type);
		}		
		$this->output->set_output(json_encode($query));
		
	}	
	
	function get_update_docs_emergency()
	{
		$post = $_POST['emergency'];
		$user_type = $_POST['user_type'];
		$limit = $_POST['count'];
		$type = $_POST['request_state'];
		if(isset($limit) && !empty($limit))
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_emergency($post,$user_type,$limit,$type);
		}else
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_emergency($post,$user_type);
		}
		$this->output->set_output(json_encode($query));
		
	}

	function get_update_docs_chronic()
	{
		$post = $_POST['chronic'];
		$user_type = $_POST['user_type'];
		$limit = $_POST['count'];
		$type = $_POST['request_state'];
		if(isset($limit) && !empty($limit))
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_chronic($post,$user_type,$limit,$type);
		}else
		{
			$query = $this->healthsupervisor_app_model->get_update_docs_chronic($post,$user_type);
		}
		$this->output->set_output(json_encode($query));		
	}*/

	function get_full_doc_normal()
	{
		$doc_id = $_POST['doc_id'];
		$user_type = $_POST['user_type'];
		$doc_access = $_POST['doc_access'];
		$access_by = $_POST['access_by'];

		$get_doc = $this->healthsupervisor_app_model->get_full_doc_normal($user_type,$doc_id,$doc_access,$access_by);
		
		if(isset($get_doc) && !empty($get_doc))
		{			
			$this->output->set_output(json_encode($get_doc));			
		}
		else
		{
			$this->output->set_output(json_encode(array('Status' => "Already Opened")));
		}
	}
	
	function get_full_doc_emergency()
	{
		$doc_id = $_POST['doc_id'];
		$user_type = $_POST['user_type'];
		$doc_access = $_POST['doc_access'];
		$access_by = $_POST['access_by'];

		$get_doc = $this->healthsupervisor_app_model->get_full_doc_emergency($user_type,$doc_id,$doc_access,$access_by);
		
		if(isset($get_doc) && !empty($get_doc))
		{			
			$this->output->set_output(json_encode($get_doc));			
		}
		else
		{
			$this->output->set_output(json_encode(array('Status' => "Already Opened")));
		}
	}

	function get_full_doc_chronic()
	{
		$doc_id = $_POST['doc_id'];
		$user_type = $_POST['user_type'];
		$doc_access = $_POST['doc_access'];
		$access_by = $_POST['access_by'];

		$get_doc = $this->healthsupervisor_app_model->get_full_doc_chronic($user_type,$doc_id,$doc_access,$access_by);
		
		if(isset($get_doc) && !empty($get_doc))
		{			
			$this->output->set_output(json_encode($get_doc));			
		}
		else
		{
			$this->output->set_output(json_encode(array('Status' => "Already Opened")));
		}
	}
	
	public function get_student_ehr()
    {
		$post = $_POST;
		$user_type = $_POST['user_type'];
		////log_message('error','postttttttttttttttt==============================440'.print_r($post,true));
		$docs = $this->healthsupervisor_app_model->screening_to_students_load_ehr_doc($post,$user_type);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_2020_2021'] = $docs['screening_2020_2021'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['docs_bmi'] = $docs['bmi'];
		$this->data['docs_hb'] = $docs['hb'];
		$this->data['docscount'] = count($this->data['docs']);	
	   
		$this->output->set_output(json_encode($this->data));
    
    }

	public function get_student_ehr_with_all_data()
    {
		$post = $_POST;
		$user_type = $_POST['user_type'];
		////log_message('error','postttttttttttttttt==============================440'.print_r($post,true));
		//$docs = $this->healthsupervisor_app_model->screening_to_students_load_ehr_doc($post,$user_type);
		$docs = $this->healthsupervisor_app_model->screening_to_students_load_ehr_doc_with_all_data($post,$user_type);
		
		$this->data['docs'] = $docs['screening'];
		$this->data['docs_2020_2021'] = $docs['screening_2020_2021'];
		$this->data['docs_2021_2022'] = $docs['screening_2021_2022'];
		$this->data['docs_requests'] = $docs['request'];
		$this->data['docs_requests_old_format'] = isset($docs['request_old']) ? $docs['request_old'] : "";
		$this->data['docs_bmi'] = $docs['bmi'];
		$this->data['docs_hb'] = $docs['hb'];
		$this->data['doctor_campus_visiting'] = $docs['doctor_visiting_report'];
		$this->data['docscount'] = count($this->data['docs']);	
	   
		$this->output->set_output(json_encode($this->data));
    
    }
	
	public function get_districts_list()
    {
    	$user_type = $_POST['user_type'];
    	$dist_id = explode(".", $_POST['school_email']);
    	/*if($dist_id[0] == 'rhso')
    	{
    		$dist_id_name = substr($dist_id[1],0,strpos($dist_id[1],'@'));
    	}
    	else
    	{
    		$dist_id_name = $dist_id[0];
    	}*/
    	
    	$dist_id_name = $dist_id[0];

    	if (strpos($dist_id_name, '@') !== false) {
    	    if(preg_match("/TSWREIS/i", $dist_id_name))
        	{
        		$dist_id_name = "TSWREIS";
        	}elseif(preg_match("/TTWREIS/i", $dist_id_name))
        	{
        		$dist_id_name = "TTWREIS";
        	}elseif (preg_match("/BCWELFARE/i", $dist_id_name)) 
        	{
        		$dist_id_name = "BCWELFARE";
        	}
    	}else{
    		$dist_id_name = $dist_id[0];
    	}

    	/*echo print_r($dist_id_name, true);
    	echo print_r($user_type, true);
    	exit();

*/
    	//log_message('error','dist_id_name=====1587'.print_r($dist_id_name,TRUE));
    	//log_message('error','user_typeuser_type=====1587'.print_r($user_type,TRUE));

		$this->data = $this->healthsupervisor_app_model->get_districts_list_model($user_type,strtoupper($dist_id_name));
		$this->output->set_output(json_encode($this->data));
    }
 	/**
	* Helper: Get School List ( Based on Dist_id )
	*
	* @author Bhanu
	*/
    public function get_schools_list()
	{
		$dist_id = $_POST['district_id'];
		$user_type = $_POST['user_type'];
		$dist_code_id =  $_POST['school_email'];		
		$this->data = $this->healthsupervisor_app_model->get_schools_by_district_id($dist_id,$user_type,$dist_code_id);
	
		$this->output->set_output(json_encode($this->data));
	}
	
	function get_students_list_device()
	{
		$school = $_POST['school_name'];
		$user_type = $_POST['user_type'];
		$students_lists = $this->healthsupervisor_app_model->get_students_list_device($school,$user_type);
		
		$this->output->set_output(json_encode($students_lists));
	}

	function get_students_list_device_academic_year_wise()
	{
		$school = $_POST['school_name'];
		$user_type = $_POST['user_type'];
		$academic = $_POST['academic_year'];
		$students_lists = $this->healthsupervisor_app_model->get_students_list_device_academic_year_wise($school,$user_type, $academic);
		
		$this->output->set_output(json_encode($students_lists));
	}

	/*public function attachment_submit_device_side()
	{
		
		// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
		 	//log_message('error','FILES====================834'.print_r($_FILES,true));
		 //exit();
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['hs_req_attachments']['name']);
		   
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['hs_req_attachments']['name']	= $files['hs_req_attachments']['name'][$i];
			 $_FILES['hs_req_attachments']['type']	= $files['hs_req_attachments']['type'][$i];
			 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
			 $_FILES['hs_req_attachments']['error']	= $files['hs_req_attachments']['error'][$i];
			 $_FILES['hs_req_attachments']['size']	= $files['hs_req_attachments']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			        $controller = 'healthcare2016531124515424_con';
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						 
								redirect('tswreis_schools/hs_request');  
							
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
							$rand_number = mt_rand();
						$external_data_array = array(
												  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
												"file_client_name" =>$external_files_upload_info['client_name'],
												"file_encrypted_name" =>$external_files_upload_info['file_name'],
												"file_path" =>$external_files_upload_info['file_relative_path'],
												"file_size" =>$external_files_upload_info['file_size']
																)

													 );

						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
			
			  if(isset($doc_data['external_attachments']))
			  {
					   
				$external_merged_data = array_merge($doc_data['external_attachments'],$external_final);
				$doc_data['external_attachments'] = array_replace_recursive($doc_data['external_attachments'],$external_merged_data);
			  }
			  else
			 {
			    $doc_data['external_attachments'] = $external_final;
			 } 
		  
		 }
	

	$this->healthsupervisor_app_model->upload_attachment_device_sync($doc_data);
}*/

/**
	* Helper: Upload audio file ( call recorded from the doctor stage )
	*
	* @return string
	*
	* @author Naresh
	*/

    public function upload_call_audio_file()
    {
    	//log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$_POST==>'.print_r($_POST,true));
    	//log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$_FILES==>'.print_r($_FILES,true));

    	if(isset($_POST['data']) && isset($_FILES))
    	{
    	   $this->load->library('upload');
    	   $device_upload_info = array();

    	   $array_data = json_decode($_POST['data'], TRUE);
    	   $app_id     = $array_data['app_id'];
    	   $doc_id     = $array_data['doc_id'];

    	   $config['upload_path'] 	= UPLOADFOLDERDIR.'public/uploads/'.$app_id.'/files/audio_files/';
		   $config['allowed_types'] = '*';
		   $config['max_size'] 		= '4096';
		   $config['encrypt_name']  = TRUE;

		   if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$app_id/files/audio_files/",0777,TRUE);
			}

		   $this->upload->initialize($config);

		   foreach($_FILES as $index => $value)
		   {
		   	  if(!empty($value['name']))
		   	  {
		   	  	if ( ! $this->upload->do_upload($index))
			    {
			  	     echo "FILE_UPLOAD_FAILED";
			  	     log_message('debug','DEVICE=====UPLOAD_CALL_AUDIO_FILE=====$ERROR==>'.print_r($this->upload->display_errors(),true));
			         return FALSE;
			    }
			    else
			    {
			        array_push($device_upload_info,$this->upload->data());
				}
		   	  }
		   }

 			$audio_file_info = array(
				  "file_client_name"    => $device_upload_info[0]['client_name'],
				  "file_encrypted_name" => $device_upload_info[0]['file_name'],
				  "file_path"           => $device_upload_info[0]['file_relative_path'],
				  "file_size"           => $device_upload_info[0]['file_size']
				  );
		    	
		   $res = $this->healthsupervisor_app_model->upload_call_audio_file_model($app_id,$doc_id,$audio_file_info);

		   if($res)
		   {
		   		$this->output->set_output('FILE_UPLOAD_SUCCESS');
		   }
		   else
		   {
		   		$this->output->set_output('FILE_UPLOAD_FAILED');
		   }
	    }
	    else
	    {
	    	$this->output->set_output('REQUIRED_PARAMS_MISSING');
	    }
    }

    /**
	* Helper: get audio files ( call recorded from the doctor stage )
	*
	* @return string
	*
	* @author Selva
	*/

    public function get_call_audio_file()
    {
    	if(isset($_POST['app_id']) && isset($_POST['doc_id']))
    	{
	    	$doc_id = $_POST['doc_id'];
			$app_id = $_POST['app_id'];
			
			$audio_files = $this->healthsupervisor_app_model->get_call_audio_file_model($doc_id,$app_id);
			
			if($audio_files)
			{
				$this->output->set_output(json_encode($audio_files));
		    }
		    else
		    {
		    	$this->output->set_output('NO_AUDIO_FILES');
		    }
	    }
	    else
	    {
	    	$this->output->set_output('REQUIRED_PARAMS_MISSING');
	    }
    }

    public function insert_fcm_token_from_users()
    {
    	$fcm_token = $_POST['fcm_token'];

    	if(isset($_POST['email']) && !empty($_POST['email']))
    	{
    		$email = $_POST['email'];
    	}

    	if(isset($_POST['user_type']) && !empty($_POST['user_type']))
    	{
    		$user_type = $_POST['user_type'];
    	}
    	/*$email = "";
    	$user_type = "";*/
    	

		$token = $this->healthsupervisor_app_model->insert_fcm_token($fcm_token,$email,$user_type);

		if($token)
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Token inserted successfully')
									));
			}
			else
			{
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed to submit ! Try again')
									));
			}

		
	 }

	 public function insert_fcm_token_from_chart_app_users()
    {
    	$fcm_token = $_POST['fcm_token'];

    	if(isset($_POST['email']) && !empty($_POST['email']))
    	{
    		$email = $_POST['email'];
    	}

    	if(isset($_POST['user_type']) && !empty($_POST['user_type']))
    	{
    		$user_type = $_POST['user_type'];
    	}
    	/*$email = "";
    	$user_type = "";*/    	

		$token = $this->healthsupervisor_app_model->insert_fcm_token_for_chart_app($fcm_token,$email,$user_type);

		if($token)
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Token inserted successfully')
									));
			}
			else
			{
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed to submit ! Try again')
									));
			}

		
	 }

	/* public function send_notification_from_fcm()
	 {
	 	$message=$_POST['message'];
		$title=$_POST['title'];
		$date=$_POST['number'];
		$path_to_fcm='https://fcm.googleapis.com/fcm/send';
		$server_key="AIzaSyDvt3dpbX4f0cUZbpsuQgNziUV4hzMD8gU";
		
		$sql="select fcm_token from fcm_info";
		//$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_row($result);
		$key=$row[0];


		$headers=array('Authorization:key='.$server_key,
		               'Content-Type:application/json');
		               
		 $fields=array('to'=>"/topics/all",
		               'notification'=>array('title'=>$title,'body'=>$message));
		               
		                 	$ar=array();

		//$sql1="insert into notification_message(title,number,message)values('$title','$date','$message')";

		$query = $this->healthsupervisor_app_model->insert_into_notification_message($title,$date,$message);

		                if ($query) {
		              // $last_id = mysqli_insert_id($con);
		                //$ar['Ann_id']=$last_id ;
		                $status =1;
		                $message="Added Succesfully";
		                $ar['message']=$message;
		                } else {
		                $message="Not Added";
		                $ar['message']=$message;
		                }
		               
		               
		               
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
		$ar['status']=$status;
		   $ajson = array();
		   $ajson[] = $ar;
		   $finalresult=json_encode($ajson);
		   echo $finalresult;
		   //echo $sql1;


		$myfile = fopen("send_notification.txt", "a") or die("Unable to open file!");
		        $txt = "title:".$_POST['title']."number: ".$_POST['number']."message: ".$_POST['message']."\nOutput: ".$finalresult;
		        fwrite($myfile, $txt);
		     //    fwrite($myfile,$sql);
		        fclose($myfile);
		
	 }*/

	

	function get_user()
	{
		//$customer = $this->session->userdata("customer");
		//$all_userdata = $this->session->all_userdata();
		//log_message('debug','HEALTHCARE_APP=====GET_USER=====$customer==462====='.print_r($customer,true));
		//log_message('debug','HEALTHCARE_APP=====GET_USER=====$all_userdata==463====='.print_r($all_userdata,true));
		
		$user["user_id"] = $_POST['user_id'];
		$user["name"]    = $_POST['username'];
		//$user["email"]   = $_POST['email'];
//		$user["gcm_registration_id"] = $gcm_registration_id;
		//$user["created_at"] = $_POST['registered'];
		
		return $user;
	}

	/**
	 * Helper: Get accessible chat rooms ( for the loggedin user )
	 * 
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	public function chat_rooms($user_id = false, $message=false)
	{
		// LOGGEDIN USER
		$loggedinuser  = $this->session->userdata("customer");
		$loggedinemail = $loggedinuser['email'];		
		//log_message('error','loggedinemail===========4713'.print_r($email,TRUE));
		$user_type = $_POST['user_type'];
		//log_message('error','user_type============1829===for_ios_test'.print_r($user_type,true));			
		if(!(isset($_POST['chat_room_id'])) && (!isset($_POST['message']))){
			
			$response = array();
			
			$user_type = $_POST['user_type'];
			
			// fetching all user tasks
			//$chat_rooms_result = $this->healthsupervisor_app_model->get_accessible_groups($user_type);
			//log_message('error','HEALTHCARE_APP=====CHAT_ROOMS=====$chat_rooms_result==415====='.print_r($user_type,true));
			if(isset($_POST['email']) && !empty($_POST['email']))
			{
				$email = $_POST['email'];
				//$dist_code = explode(".", $email);
			// fetching all user tasks
			$result = $this->healthsupervisor_app_model->get_all_groups($user_type,$email);
			}
			else
			{
				$result = $this->healthsupervisor_app_model->get_all_groups($user_type);
			}
			
			$response["error"]      = false;
			$response["chat_rooms"] = array();
			////log_message('error','chat_rooms==============457'.print_r($result,true));
			foreach ($result as $group)
			{
				$tmp = array();
				$tmp["chat_room_id"] = $group["group_name"];
				$tmp["name"] = $group["group_name"];
				$tmp["created_at"] = $group["created_at"];
				array_push($response["chat_rooms"], $tmp);
				////log_message('error','$response=====chat_rooms==============457'.print_r($response["chat_rooms"],true));
			}	
			$this->data = $response;
		}
		else if((isset($_POST['chat_room_id'])) && (!isset($_POST['message'])))
		{
			$limit = $_POST['limit'];
			//$skip = $_POST['skip'];
			$user_type = $_POST['user_type'];
			$result = $this->healthsupervisor_app_model->get_messages($_POST['chat_room_id'],$limit,$user_type);
			
			$response["messages"] = array();
			$response['chat_room'] = array();

			$i = 0;
			foreach ($result as $msg)
			{
				// adding chat room node
				if ($i == 0) 
				{
					$tmp = array();
					$tmp["chat_room_id"] = $msg["chat_room_id"];
					$tmp["name"] = $msg["user_id"];
					$tmp["created_at"] = $msg["created_at"];
					$response['chat_room'] = $tmp;
					$i++;
				}
				if ($msg['user_id'] != NULL) 
				{
					// message node
					$cmt = array();
					$cmt["message"]    = $msg["message"];
					$cmt["message_id"] = $msg["message_id"];
					$cmt["created_at"] = $msg["created_at"];
					if(isset($msg["replay_id"]) && !empty($msg["replay_id"]))
					{
						$cmt["replay_id"] = $msg["replay_id"];
						$cmt["replay_by"] = $msg["replay_by"];
						$cmt["replay_msg"] = $msg["replay_msg"];
						
					}					
					//echo print_r($msg['external_attachments'], true);

					if(isset($msg['external_attachments']) && !empty($msg['external_attachments'])){
						$external_attachments = $msg['external_attachments'];
						}else{
							$external_attachments = "";
						}
					$cmt["external_attachments"] = $external_attachments;

					// user node
					$user = array();
					if(!empty($msg['user_email']) && isset($msg['user_email']))
					{
						$user['user_id']      = $msg['user_id'];
						$user['username']     = $msg['user_name'];
						$user['user_email']   = $msg['user_email'];
						$cmt['user']          = $user;
						$cmt["forward_msg"] = (isset($msg["forward_msg"])) ? $msg["forward_msg"] : "";
						$cmt["forwarding_msg"] = (isset($msg["forwarding_msg"])) ? $msg["forwarding_msg"] : "";
					}else
					{
						$user['user_id']      = $msg['user_id'];
						$user['username']     = $msg['user_name'];
						$cmt['user']          = $user;
						$cmt["forward_msg"] = (isset($msg["forward_msg"])) ? $msg["forward_msg"] : "";
						$cmt["forwarding_msg"] = (isset($msg["forwarding_msg"])) ? $msg["forwarding_msg"] : "";
					}			
			
					array_push($response["messages"], $cmt);
				}
			}

			$response["error"] = false;
			$this->data = $response;
		}
		else{
			$user_type = $_POST['user_type'];

			 $data = array(
					"message_id"   => get_unique_id(),
					"user_id"      => $this->input->post('user_id'),
					"user_name"    => $this->input->post('username'),
					"chat_room_id" => $this->input->post('chat_room_id'),
					"user_email"   => $this->input->post('user_email'),
					"message" 	   => $this->input->post('message'),
					"replay_id"	   => $this->input->post('replay_id'),
					"replay_by"    => $this->input->post('replay_by'),
					"replay_msg"   => $this->input->post('replay_msg'),
					"forward_msg"  => $this->input->post('forward_msg'),
					"forwarding_msg"  => $this->input->post('forwarding_msg'),
					"created_at"   => date("Y-m-d H:i:s")
					);
			 ////log_message('error','data=================1881'.print_r($data,TRUE)); 
			 // Attachments
			 if(isset($_FILES) && !empty($_FILES))
			 {
		       $this->load->library('upload');
		       $this->load->library('image_lib');

			   
			   $external_files_upload_info = array();
			   $external_final             = array();
			   $external_merged_data       = array();
			   
			   
			   $files = $_FILES;
			   $cpt = count($_FILES['chat_related_attachments']['name']);
			   for($i=0; $i<$cpt; $i++)
			   {
				 $_FILES['chat_related_attachments']['name']	= $files['chat_related_attachments']['name'][$i];
				 $_FILES['chat_related_attachments']['type']	= $files['chat_related_attachments']['type'][$i];
				 $_FILES['chat_related_attachments']['tmp_name']= $files['chat_related_attachments']['tmp_name'][$i];
				 $_FILES['chat_related_attachments']['error']	= $files['chat_related_attachments']['error'][$i];
				 $_FILES['chat_related_attachments']['size']	= $files['chat_related_attachments']['size'][$i];
			
			   foreach ($_FILES as $index => $value)
		       {
				  if(!empty($value['name']))
				  {				      
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/files/chat_room_attaachments/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
			
				        //create controller upload folder if not exists
						if (!is_dir($config['upload_path']))
						{
							mkdir(UPLOADFOLDERDIR."public/uploads/files/chat_room_attaachments/",0777,TRUE);
						}
			
						$this->upload->initialize($config);
						
						if ( ! $this->upload->do_upload($index))
						{
							 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
							
						}
						else
						{
							$external_files_upload_info = $this->upload->data();
							
							$external_data_array = array(
													  "DFF_EXTERNAL_ATTACHMENTS_".$i => array(
													"file_client_name" =>$external_files_upload_info['client_name'],
													"file_encrypted_name" =>$external_files_upload_info['file_name'],
													"file_path" =>$external_files_upload_info['file_relative_path'],
													"file_size" =>$external_files_upload_info['file_size']
																	)

														 );

							$external_final = array_merge($external_final,$external_data_array);
							
						}  
					}
				}
			 }
			 
			  if(isset($data['external_attachments']))
			  {					   
				$external_merged_data = array_merge($data['external_attachments'],$external_final);
				$data['external_attachments'] = array_replace_recursive($data['external_attachments'],$external_merged_data);
			  }
			  else
			    {
				    $data['external_attachments'] = $external_final;
			    } 
		 }
		 	//log_message('error','data=======2501'.print_r($data,true));
			$this->data = $this->healthsupervisor_app_model->add_message($data,$user_type);
			
			//+++++++++++++++++++++GCM part +++++++++++++++++++++++++
			if ($this->data['error'] == false) {
				// get the user using userid
				$user = $this->get_user();
			/*$user_id = $_POST['user_id'];
			$user_name = $_POST['username'];
			//log_message('error',"user_id============1150".print_r($user_id,true));
			//log_message('error',"user_name============1150".print_r($user_name,true));*/
				$data = array();
				$data['user']         = $user;
				$data['message']      = $this->data['message'];
				$data['chat_room_id'] = $_POST['chat_room_id'];
			
				$this->push->setTitle("DashBoard");
				$this->push->setIsBackground(FALSE);
				$this->push->setFlag(PUSH_FLAG_CHATROOM);
				$this->push->setData($data);
			
				
				//echo json_encode($push->getPush());exit;
				// sending push message to a topic
				//$this->gcm->sendToTopic('topic_' . $_POST['chat_room_id'], $this->push->getPush());
				//$this->gcm->sendToTopic($_POST['chat_room_id'], $this->push->getPush());
				$this->gcm->sendToTopic_chat_users($_POST['chat_room_id'], $this->push->getPush());

				//$this->gcm->fcm_chat_message_notification();
			
				$this->data['user'] = $user;
				$this->data['error'] = false;
			}
		} 
		
		$this->output->set_output(json_encode($this->data));
	}

	public function call_to_hs()
	{
		$user_type = $_POST['user_type'];
		$user_email = $_POST['user_email'];
		$query = $this->healthsupervisor_app_model->get_hs_mob($user_email,$user_type);
		$this->output->set_output(json_encode($query));
	}

	function get_normal_docs_device()
	{
		$email = $_POST['email'];
		$user_type = $_POST['user_type'];		
		$unique_id = strtoupper(str_replace('.','_', substr($email,0,strpos($email,'@')-2)));
		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
			$documents = $this->healthsupervisor_app_model->get_normal_docs_device($unique_id,$user_type,$limit);
		}else
		{
			$documents = $this->healthsupervisor_app_model->get_normal_docs_device($unique_id,$user_type);
		}
		if(!empty($documents))
		{
			$this->output->set_output(json_encode($documents));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No Requests')
									));
		}

	}

	function get_emergency_docs_device()
	{
		$email = $_POST['email'];
		$user_type = $_POST['user_type'];
		$unique_id = strtoupper(str_replace('.','_', substr($email,0,strpos($email,'@')-2)));
		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
		    $documents = $this->healthsupervisor_app_model->get_emergency_docs_device($unique_id,$user_type,$limit);
		}else
		{
			$documents = $this->healthsupervisor_app_model->get_emergency_docs_device($unique_id,$user_type);
		}

		if(!empty($documents))
		{
			$this->output->set_output(json_encode($documents));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No Requests')
									));
		}

	}

	function get_chronic_docs_device()
	{
		$email = $_POST['email'];
		$user_type = $_POST['user_type'];
		$unique_id = strtoupper(str_replace('.','_', substr($email,0,strpos($email,'@')-2)));
		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
		    $documents = $this->healthsupervisor_app_model->get_chronic_docs_device($unique_id,$user_type,$limit);
		}else
		{
			$documents = $this->healthsupervisor_app_model->get_chronic_docs_device($unique_id,$user_type);
		}
		if(!empty($documents))
		{
			$this->output->set_output(json_encode($documents));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No Requests')
									));
		}

	}

	function get_cured_docs_device()
	{
		$email = $_POST['email'];
		$user_type = $_POST['user_type'];
		$unique_id = strtoupper(str_replace('.','_', substr($email,0,strpos($email,'@')-2)));
		$request_type = $_POST['request_type'];	

		if(isset($_POST['count']) && !empty($_POST['count']))
		{
			$limit = $_POST['count'];
		    $documents = $this->healthsupervisor_app_model->get_cured_docs_device($unique_id,$user_type,$request_type,$limit);
		}else
		{
			$documents = $this->healthsupervisor_app_model->get_cured_docs_device($unique_id,$user_type,$request_type);
		}
		if(!empty($documents))
		{
			$this->output->set_output(json_encode($documents));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No Requests')
									));
		}

	}

	public function get_cured_docs_requests_wise()
	{
		$email = $_POST['email'];
		$user_type = $_POST['user_type'];
		$unique_id = strtoupper(str_replace('.','_', substr($email,0,strpos($email,'@')-2)));

		$documents = $this->healthsupervisor_app_model->get_cured_docs_requests_wise($unique_id,$user_type);
		if(!empty($documents))
		{
			$this->output->set_output(json_encode($documents));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No Requests')
									));
		}

	}
 function to_dashboard($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];

			if(preg_match("/PANACEA/i", $user_type))
			{
				$this->data = $this->panacea_common_lib->to_dashboard($date,$request_duration,$screening_duration);
				$this->output->set_output(json_encode($this->data));
			}else if(preg_match("/TTWREIS/i", $user_type))
			{
				$this->data = $this->ttwreis_common_lib->to_dashboard($date,$request_duration,$screening_duration);
				$this->output->set_output(json_encode($this->data));
			}
			else if(preg_match("/TMREIS/i", $user_type))
			{
				$this->data = $this->tmreis_common_lib->to_dashboard($date,$request_duration,$screening_duration);
				$this->output->set_output(json_encode($this->data));
			}
			else if(preg_match("/BCWELFARE/i", $user_type))
			{
				$this->data = $this->bc_welfare_common_lib->to_dashboard($date,$request_duration,$screening_duration);
				$this->output->set_output(json_encode($this->data));
			}
		}else
		{
			//log_message('error',"user_type empty");
		}		

	}

	/*function to_dashboard_identifier($date = FALSE, $request_duration = "Daily", $screening_duration = "Yearly")
	{
		$this->data = $this->panacea_common_lib->to_dashboard_identifier($date,$request_duration,$screening_duration);
		$this->output->set_output(json_encode($this->data));
	}*/

	function to_dashboard_with_date()
	{
		// POST DATA
		$today_date         = $_POST['today_date'];
		$request_pie_span   = $_POST['request_pie_span'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$dt_name 			= $_POST["dt_name"];
		$school_name 		= $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$user_type 			= $_POST['user_type'];

		if(preg_match("/PANACEA/i", $user_type))
		{
			$this->data = $this->panacea_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);

			$this->output->set_output($this->data);
		}else if(preg_match("/TTWREIS/i", $user_type))
		{
			$this->data = $this->ttwreis_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);

			$this->output->set_output($this->data);
		}
		else if(preg_match("/TMREIS/i", $user_type))
		{
			$this->data = $this->tmreis_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);

			$this->output->set_output($this->data);
		}
		else if(preg_match("/BCWELFARE/i", $user_type))
		{
			$this->data = $this->bc_welfare_common_lib->to_dashboard_with_date($today_date,$request_pie_span,$screening_pie_span,$dt_name,$school_name,$request_pie_status);

			$this->output->set_output($this->data);
		}
		
	}



	function drilldown_absent_to_districts()
	{		
		$data        = $_POST['data'];
		$today_date  = $_POST['today_date'];
		$dt_name     = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$user_type 	 = $_POST['user_type'];

		if(preg_match("/PANACEA/i", $user_type))
		{
			$absent_report = json_encode($this->panacea_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TTWREIS/i", $user_type))
		{
			$absent_report = json_encode($this->ttwreis_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TMREIS/i", $user_type))
		{
			$absent_report = json_encode($this->tmreis_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/BCWELFARE/i", $user_type))
		{
			$absent_report = json_encode($this->bc_welfare_common_model->drilldown_absent_to_districts($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
	}
	
	function drilling_absent_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$user_type 			= $_POST['user_type'];

		if(preg_match("/PANACEA/i", $user_type))
		{
			$absent_report = json_encode($this->panacea_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TTWREIS/i", $user_type))
		{
			$absent_report = json_encode($this->ttwreis_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TMREIS/i", $user_type))
		{
			$absent_report = json_encode($this->tmreis_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/BCWELFARE/i", $user_type))
		{
			$absent_report = json_encode($this->bc_welfare_common_model->get_drilling_absent_schools($data,$today_date,$dt_name,$school_name));
			$this->output->set_output($absent_report);
		}
	}
	
	function drill_down_absent_to_students()
	{
		//log_message("debug","ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp".print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$user_type 			= $_POST['user_type'];

		if(preg_match("/PANACEA/i", $user_type))
		{
			$docs = $this->panacea_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
			$absent_report = base64_encode(json_encode($docs));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TTWREIS/i", $user_type))
		{
			$docs = $this->ttwreis_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
			$absent_report = base64_encode(json_encode($docs));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/TMREIS/i", $user_type))
		{
			$docs = $this->tmreis_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
			$absent_report = base64_encode(json_encode($docs));
			$this->output->set_output($absent_report);
		}
		else if(preg_match("/BCWELFARE/i", $user_type))
		{
			$docs = $this->bc_welfare_common_model->get_drilling_absent_students($data,$today_date,$dt_name,$school_name);
			$absent_report = base64_encode(json_encode($docs));
			$this->output->set_output($absent_report);
		}
	}
	
	function drill_down_absent_to_students_load_ehr()
	{
		//$temp = base64_decode($_GET['ehr_data_for_absent']);
		//$UI_id = $_POST['ehr_data_for_absent'];
		$UI_id = explode(',',$_POST['ehr_data_for_absent']);
		$user_type 			= $_POST['user_type'];

		if(preg_match("/PANACEA/i", $user_type))
		{
			$get_docs = $this->panacea_common_model->get_drilling_absent_students_docs($UI_id);
			$this->data['students'] = $get_docs;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->output->set_output(json_encode($this->data));
		}
		else if(preg_match("/TTWREIS/i", $user_type))
		{
			$get_docs = $this->ttwreis_common_model->get_drilling_absent_students_docs($UI_id);
			$this->data['students'] = $get_docs;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->output->set_output(json_encode($this->data));
		}
		else if(preg_match("/TMREIS/i", $user_type))
		{
			$get_docs = $this->tmreis_common_model->get_drilling_absent_students_docs($UI_id);
			$this->data['students'] = $get_docs;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->output->set_output(json_encode($this->data));
		}
		else if(preg_match("/BCWELFARE/i", $user_type))
		{
			$get_docs = $this->bc_welfare_common_model->get_drilling_absent_students_docs($UI_id);
			$this->data['students'] = $get_docs;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->output->set_output(json_encode($this->data));
		}
	}
	
	

	function drilldown_identifiers_to_fillter()
	{
		// POST DATA
		$data 				= $_POST['data'];
		$today_date 		= $_POST['today_date'];
		$request_pie_span 	= $_POST['request_pie_span'];
		$dt_name 			= $_POST["dt_name"];
		$school_name 		= $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->panacea_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($identifiers_report);
	}

	function drilldown_identifiers_to_districts()
	{
		$data 				= $_POST['data'];
		$today_date 		= $_POST['today_date'];
		$request_pie_span 	= $_POST['request_pie_span'];
		$dt_name 			= $_POST["dt_name"];
		$school_name 		= $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->panacea_common_model->drilldown_identifiers_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($identifiers_report);
	}

	function drilling_identifiers_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->panacea_common_model->get_drilling_identifiers_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students()
	{
		$data = $_POST['data'];
	
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$dt_name = $_POST["dt_name"];
		$school_name = $_POST["school_name"];
		$request_pie_status = $_POST['request_pie_status'];
		$docs = $this->panacea_common_model->get_drilling_identifiers_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
		$identifiers_report = base64_encode(json_encode($docs));
		$this->output->set_output($identifiers_report);
	}
	
	function drill_down_identifiers_to_students_load_ehr()
	{
		//$temp = base64_decode($_GET['ehr_data_for_identifiers']);
		//$UI_id = json_decode(base64_decode($_GET['ehr_data_for_identifiers']),true);
		//$UI_id = $_POST['ehr_data_for_identifiers'];
		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($_POST,true));
		$UI_id = explode(',',$_POST['ehr_data_for_identifiers']);
		//log_message('debug','pppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($UI_id,true));
		$get_docs = $this->panacea_common_model->get_drilling_identifiers_students_docs($UI_id);
	
		$this->data['students'] = $get_docs;
	
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		$this->output->set_output(json_encode($this->data));
	}

	private function campus_attachment_upload_options($controller,$field,$user_type)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_campus')!== false)
		{
			if($user_type == "PANACEA_HS"){
				$controller = 'healthcare2016111212310531_con';
	        }else if($user_type == "TTWREIS_HS")
	        {
	        	$controller = 'healthcare20171211809800_con';
	        }else if($user_type == "TMREIS_HS")
	        {
	            $controller = 'healthcare2017121175645993_con';
	        }else if($user_type == "BCWELFARE_HS")
	        {
	            $controller = 'healthcare201822113134483_con';
	        }

			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}

			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	private function toilet_attachment_upload_options($controller,$field,$user_type)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_toilets')!== false)
		{
			if($user_type == "PANACEA_HS"){
				$controller = 'healthcare2016111212310531_con';
	        }else if($user_type == "TTWREIS_HS")
	        {
	        	$controller = 'healthcare20171211809800_con';
	        }else if($user_type == "TMREIS_HS")
	        {
	            $controller = 'healthcare2017121175645993_con';
	        }else if($user_type == "BCWELFARE_HS")
	        {
	            $controller = 'healthcare201822113134483_con';
	        }
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function kitchen_attachment_upload_options($controller,$field,$user_type)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_kitchen')!== false)
		{
			if($user_type == "PANACEA_HS"){
				$controller = 'healthcare2016111212310531_con';
	        }else if($user_type == "TTWREIS_HS")
	        {
	        	$controller = 'healthcare20171211809800_con';
	        }else if($user_type == "TMREIS_HS")
	        {
	            $controller = 'healthcare2017121175645993_con';
	        }else if($user_type == "BCWELFARE_HS")
	        {
	            $controller = 'healthcare201822113134483_con';
	        }
			
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	private function dormitory_attachment_upload_options($controller,$field,$user_type)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments_dormitory')!== false)
		{	
			if($user_type == "PANACEA_HS"){
				$controller = 'healthcare2016111212310531_con';
	        }else if($user_type == "TTWREIS_HS")
	        {
	        	$controller = 'healthcare20171211809800_con';
	        }else if($user_type == "TMREIS_HS")
	        {
	            $controller = 'healthcare2017121175645993_con';
	        }else if($user_type == "BCWELFARE_HS")
	        {
	            $controller = 'healthcare201822113134483_con';
	        }
			
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	function create_sanitation_report_device()
	{
		//log_message('error','create_sanitation_report_device====1746');
		$doc_data = array();
		$doc_data['daily'] = array();
		$doc_data['weekly'] = array();
		$doc_data['monthly'] = array();
		$user_type = $_POST['user_type'];
				
		if(!empty($_POST['cleanliness_Of_the_campus']) || !empty($_POST['campus_cleanliness_times']) ||!empty($_POST['animals_around_campus']) ||!empty($_POST['type_of_animal']) || !empty($_POST['other_animal_name'])|| !empty($_POST['cleanliness_toilets'])|| !empty($_POST['cleanliness_toilets_times'])|| !empty($_POST['any_damages_toilets'])|| !empty($_POST['cleanliness_Kitchen'])|| !empty($_POST['cleanliness_Kitchen_times'])|| !empty($_POST['food_days_menu'])|| !empty($_POST['kitchen_utensils'])|| !empty($_POST['cleanliness_diningHalls'])|| !empty($_POST['cleanliness_diningHalls_times'])|| !empty($_POST['hand_gloves_used_by_serving_people'])|| !empty($_POST['staffmembers_tasty_food_before_serving_meals'])|| !empty($_POST['cleanliness_wellness']) || !empty($_POST['cleanliness_Wellness_times']) || !empty($_POST['page3_Cleanliness_Wellness']) )
		{
			
		$campus	 = $this->input->post('cleanliness_Of_the_campus',true);
		$campus_cleanliness_times	 = $this->input->post('campus_cleanliness_times',true);
		$animals_around_campus = $this->input->post("animals_around_campus",true);
		$type_of_animal = $this->input->post('type_of_animal');
		$other_animal_name  = $this->input->post('other_animal_name');
		$cleanliness_toilets = $this->input->post('cleanliness_toilets');
		$cleanliness_toilets_times = $this->input->post('page3_Cleanliness_Toilets');
		$any_damages_toilets = $this->input->post('any_damages_toilets');
		$cleanliness_Kitchen = $this->input->post('cleanliness_Kitchen');
		$cleanliness_Kitchen_times = $this->input->post('cleanliness_Kitchen_times');
		$food_days_menu = $this->input->post('page3_Food_Foodpreparedaccordingtothedaysmenu');
		$kitchen_utensils = $this->input->post('page3_Cleanliness_KitchenUtensils');
		$cleanliness_diningHalls = $this->input->post('cleanliness_diningHalls');
		$cleanliness_diningHalls_times = $this->input->post('page2_Cleanliness_DiningHalls');
		$hand_gloves_used_by_serving_people  = $this->input->post('hand_gloves_used_by_serving_people');
		$staffmembers_tasty_food_before_serving_meals  = $this->input->post('staffmembers_tasty_food_before_serving_meals');
		$cleanliness_wellness  = $this->input->post('cleanliness_Of_the_wellness');
		$cleanliness_Wellness_times= $this->input->post('page3_Cleanliness_Wellness');
						
			//submiting daily sanitation report 
		$doc_data['daily']['Campus']['Cleanliness Of Campus'] = empty($campus) ? "" : $campus;
		$doc_data['daily']['Campus']['Cleanliness Of Campus Times'] = empty($campus_cleanliness_times) ? "" : $campus_cleanliness_times;
		$doc_data['daily']['Campus']['Animals Around Campus'] 	= empty($animals_around_campus) ? "" :   $animals_around_campus ;
		$doc_data['daily']['Campus']['Type Of Animal'] = empty($type_of_animal) ? "":$type_of_animal;
		$doc_data['daily']['Campus']['Other Animal Name'] 			= $other_animal_name;
		$doc_data['daily']['Toilets']['Cleanliness Toilets or Bathrooms'] = empty($cleanliness_toilets) ? "":$cleanliness_toilets;
		$doc_data['daily']['Toilets']['Cleanliness Toilets or Bathrooms In A Day']= empty($cleanliness_toilets_times) ? "":$cleanliness_toilets_times;
		$doc_data['daily']['Toilets']['Any Damages To The Toilets'] = empty($any_damages_toilets) ? "" : $any_damages_toilets;
		
		$doc_data['daily']['Kitchen']['Cleanliness Of The Kitchen Place'] = empty($cleanliness_Kitchen) ? "":$any_damages_toilets;
		$doc_data['daily']['Kitchen']['Cleanliness Of The Kitchen Place In A Day'] = empty($cleanliness_Kitchen_times) ? "" :$cleanliness_Kitchen_times;
		$doc_data['daily']['Kitchen']['Daily Menu Followed'] 			= empty($food_days_menu) ? "" :$food_days_menu;
		$doc_data['daily']['Kitchen']['Utensils Cleanliness'] 			= empty($kitchen_utensils) ? "" :$kitchen_utensils;
		$doc_data['daily']['Kitchen']['Dining Hall Cleanliness'] 	= empty($cleanliness_diningHalls) ? "" :$cleanliness_diningHalls;
		$doc_data['daily']['Kitchen']['page2_Cleanliness_DiningHalls'] 	= empty($cleanliness_diningHalls_times) ? "":$cleanliness_diningHalls_times;
		$doc_data['daily']['Kitchen']['Hand Gloves Used By Serving People'] 		= empty($hand_gloves_used_by_serving_people) ? "":$hand_gloves_used_by_serving_people;
		$doc_data['daily']['Kitchen']['Staffmembers Tasty Food Before Serving Meals'] = empty($staffmembers_tasty_food_before_serving_meals) ? "":$staffmembers_tasty_food_before_serving_meals;
		$doc_data['daily']['Kitchen']['Wellness Centre Cleanliness'] = empty($cleanliness_wellness) ? "":$cleanliness_wellness;
		$doc_data['daily']['Kitchen']['Cleanliness Of The Wellness Centre'] = empty($cleanliness_Wellness_times) ? "":$cleanliness_Wellness_times;
			
		}
		else
		{
			
			$doc_data['daily'] = array();
		}


		if(!empty($_POST['water_condition_ro_plant']) || !empty($_POST['water_condition_borewater']) || !empty($_POST['water_condition_noplant_working']) || !empty($_POST['water_tank_cleaning']) || !empty($_POST['cleanliness_dormitories'])||!empty($_POST['page2_Cleanliness_Dormitories']) ||    !empty($_POST['any_damages_to_beds']) || !empty($_POST['cleanliness_of_the_store']) || !empty($_POST['page3_Cleanliness_Store']) || !empty($_POST['storage_Of_the_items']) || !empty($_POST['any_items_issued'])|| !empty($_POST['separatedumpingof_Inorganicwaste'])|| !empty($_POST['separatedumpingof_Organicwaste'])|| !empty($_POST['dustbins'])  )
		{

			$ro_plant = $this->input->post('water_condition_ro_plant');
			$borewater = $this->input->post('water_condition_borewater');
			$noplant_working = $this->input->post('water_condition_noplant_working');
			$water_tank_cleaning = $this->input->post('water_tank_cleaning');
			$dormitories = $this->input->post('cleanliness_dormitories');
			$Cleanliness_Dormitories_times = $this->input->post('page2_Cleanliness_Dormitories');
			$any_damages_to_beds = $this->input->post('any_damages_to_beds');
			$cleanliness_of_the_store = $this->input->post('cleanliness_of_the_store');
			$cleanliness_store_times = $this->input->post('page3_Cleanliness_Store');
			$storage_of_the_items = $this->input->post('storage_Of_the_items');
			$any_items_issued =$this->input->post('any_items_issued');
			$separatedumpingof_Inorganicwaste = $this->input->post('page4_WasteManagement_SeparatedumpingofInorganicwaste');
			$separatedumpingof_Organicwaste = $this->input->post('page4_WasteManagement_SeparatedumpingofOrganicwaste');
			$dustbins = $this->input->post('dustbins');	

			$doc_data['weekly']['Water Supply Condition']['RO Plant'] = empty($ro_plant) ? "":$ro_plant;
			$doc_data['weekly']['Water Supply Condition']['Bore Water'] = empty($borewater) ? "":$borewater;
			$doc_data['weekly']['Water Supply Condition']['No Plant Working'] = empty($noplant_working) ? "":$noplant_working;
			$doc_data['weekly']['Water Supply Condition']['Water Tank Cleaning'] = empty($water_tank_cleaning) ? "":$water_tank_cleaning;
			$doc_data['weekly']['Dormitories']['Dormitory Cleaning'] = empty($dormitories) ? "":$dormitories;
			$doc_data['weekly']['Dormitories']['Cleanliness Of The Dormitory Room'] = empty($Cleanliness_Dormitories_times) ? "":$Cleanliness_Dormitories_times;
			$doc_data['weekly']['Dormitories']['Any Damages To Beds'] = empty($any_damages_to_beds) ? "":$any_damages_to_beds;

			$doc_data['weekly']['Store']['Store Room Cleanliness'] = empty($cleanliness_of_the_store) ? "":$cleanliness_of_the_store;
			$doc_data['weekly']['Store']['Cleanliness of The Store Room'] = empty($cleanliness_store_times) ? "":$cleanliness_store_times;
			$doc_data['weekly']['Store']['Proper Storage of ITEMS'] = empty($storage_of_the_items) ? "":$storage_of_the_items;
			$doc_data['weekly']['Store']['Any Default Items Issued'] = empty($any_items_issued) ? "":$any_items_issued;
			$doc_data['weekly']['Waste Management']['Separate dumping of Inorganic waste'] 			= empty($separatedumpingof_Inorganicwaste) ? "":$separatedumpingof_Inorganicwaste;
			$doc_data['weekly']['Waste Management']['Separate dumping of Organic waste'] 				= empty($separatedumpingof_Organicwaste) ? "":$separatedumpingof_Organicwaste  ;
			$doc_data['weekly']['Waste Management']['Dustbins']  = empty($dustbins) ? "" : $dustbins;

		}
		else
		{
			
			$doc_data['weekly'] = array();
		}
		if(!empty($_POST['cleanliness_water_loading']) || !empty($_POST['cleanliness_waterLoading_times']))
		{
		$cleanliness_water_loading = $this->input->post('cleanliness_water_loading');
		$cleanliness_water_loading_times = $this->input->post('cleanliness_waterLoading_times');

		$doc_data['monthly']['Water']['Water Loading Areas']  = empty($cleanliness_water_loading) ? "" : $cleanliness_water_loading;
		$doc_data['monthly']['Water']['Warter loading Areas Times']  = empty($cleanliness_water_loading_times)?"" : $cleanliness_water_loading_times;
		}
		else
		{
			
			$doc_data['monthly'] = array();
		}

		//files attachment data
	if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');
        
        log_message('debug','main_FILES=========338'.print_r($_FILES,true));

        $campus_external_files_upload_info = array();
        $toilet_files_upload_info = array();
        $kitchen_files_upload_info = array();
        $dormitory_files_upload_info = array();
        
        $campus_external_final    = array();
        $toilet_external_final    = array();
        $kitchen_final            = array();
        $dormitory_final          = array();                 
        
        
        foreach ($_FILES as $index => $value)
       {
            
            $files = $_FILES;
            //$cpt = count($_FILES['hs_req_attachments_campus']['name']);
            if(strpos($index,'hs_req_attachments_campus')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['hs_req_attachments_campus']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['hs_req_attachments_campus']['name']  = $files['hs_req_attachments_campus']['name'][$i];
                     $_FILES['hs_req_attachments_campus']['type']  = $files['hs_req_attachments_campus']['type'][$i];
                     $_FILES['hs_req_attachments_campus']['tmp_name']= $files['hs_req_attachments_campus']['tmp_name'][$i];
                     $_FILES['hs_req_attachments_campus']['error'] = $files['hs_req_attachments_campus']['error'][$i];
                     $_FILES['hs_req_attachments_campus']['size']  = $files['hs_req_attachments_campus']['size'][$i];
            
              		$this->upload->initialize($this->campus_attachment_upload_options('healthcare2016111212310531_con',$index,$user_type));
              		//log_message('error',"index==============2139".print_r($index,TRUE));
                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $campus_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$campus_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$campus_external_files_upload_info['file_name'],
                                            "file_path" =>$campus_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$campus_external_files_upload_info['file_size']
                                            )

                                        );

                    $campus_external_final = array_merge($campus_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'hs_req_attachments_toilets')!== false)
			{
                if(!empty($value['name']))
                {
                $mri = count($_FILES['hs_req_attachments_toilets']['name']);
                for($i=0; $i<$mri; $i++)
                {
                     $_FILES['hs_req_attachments_toilets']['name']    = $files['hs_req_attachments_toilets']['name'][$i];
                     $_FILES['hs_req_attachments_toilets']['type']    = $files['hs_req_attachments_toilets']['type'][$i];
                     $_FILES['hs_req_attachments_toilets']['tmp_name']= $files['hs_req_attachments_toilets']['tmp_name'][$i];
                     $_FILES['hs_req_attachments_toilets']['error']   = $files['hs_req_attachments_toilets']['error'][$i];
                     $_FILES['hs_req_attachments_toilets']['size']    = $files['hs_req_attachments_toilets']['size'][$i];
                     
		
			       $this->upload->initialize($this->toilet_attachment_upload_options('healthcare2016111212310531_con',$index,$user_type));
                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $toilet_files_upload_info = $this->upload->data();
                
                    $toilet_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$toilet_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$toilet_files_upload_info['file_name'],
                                            "file_path" =>$toilet_files_upload_info['file_relative_path'],
                                            "file_size" =>$toilet_files_upload_info['file_size']
                                                            )

                                            );

                    $toilet_external_final = array_merge($toilet_external_final,$toilet_data_array);
                
                }
                }
         }
     }
       
       	if(strpos($index,'hs_req_attachments_kitchen')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['hs_req_attachments_kitchen']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['hs_req_attachments_kitchen']['name']    = $files['hs_req_attachments_kitchen']['name'][$i];
                 $_FILES['hs_req_attachments_kitchen']['type']    = $files['hs_req_attachments_kitchen']['type'][$i];
                 $_FILES['hs_req_attachments_kitchen']['tmp_name']= $files['hs_req_attachments_kitchen']['tmp_name'][$i];
                 $_FILES['hs_req_attachments_kitchen']['error']   = $files['hs_req_attachments_kitchen']['error'][$i];
                 $_FILES['hs_req_attachments_kitchen']['size']    = $files['hs_req_attachments_kitchen']['size'][$i];
                 
                $this->upload->initialize($this->kitchen_attachment_upload_options('healthcare2016111212310531_con',$index,$user_type));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "kitchen upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $kitchen_files_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$kitchen_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$kitchen_files_upload_info['file_name'],
                                            "file_path" =>$kitchen_files_upload_info['file_relative_path'],
                                            "file_size" =>$kitchen_files_upload_info['file_size']
                                                            )

                                             );

                    $kitchen_final = array_merge($kitchen_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'hs_req_attachments_dormitory')!== false)
		 {
             if(!empty($value['name']))
            {
            $dormitory = count($_FILES['hs_req_attachments_dormitory']['name']);
            for($i=0; $i<$dormitory; $i++)
            {
                
                 $_FILES['hs_req_attachments_dormitory']['name']    = $files['hs_req_attachments_dormitory']['name'][$i];
                 $_FILES['hs_req_attachments_dormitory']['type']    = $files['hs_req_attachments_dormitory']['type'][$i];
                 $_FILES['hs_req_attachments_dormitory']['tmp_name']= $files['hs_req_attachments_dormitory']['tmp_name'][$i];
                 $_FILES['hs_req_attachments_dormitory']['error']   = $files['hs_req_attachments_dormitory']['error'][$i];
                 $_FILES['hs_req_attachments_dormitory']['size']    = $files['hs_req_attachments_dormitory']['size'][$i];
                 
                $this->upload->initialize($this->dormitory_attachment_upload_options('healthcare2016111212310531_con',$index,$user_type));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $dormitory_files_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$dormitory_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$dormitory_files_upload_info['file_name'],
                                            "file_path" =>$dormitory_files_upload_info['file_relative_path'],
                                            "file_size" =>$dormitory_files_upload_info['file_size']
                                                            )

                                             );

                    $dormitory_final = array_merge($dormitory_final,$dormitory_data_array);
            
                }
            }
            }
        }
         
       }
         if(isset($doc_data['daily']['Campus']['external_attachments']))
        {
           
            $campus_merged_data = array_merge($doc_data['daily']['Campus']['external_attachments'],$campus_external_final);
            $doc_data['daily']['Campus']['external_attachments'] = array_replace_recursive($doc_data['daily']['Campus']['external_attachments'],$campus_merged_data);                
        }
        else
        {
           	$doc_data['daily']['Campus']['external_attachments'] = $campus_external_final;
                
        } 

        if(isset($doc_data['daily']['Toilets']['external_attachments']))
        {
            $toilets_merged_data = array_merge($doc_data['daily']['Toilets']['external_attachments'],$toilet_external_final);
            $doc_data['daily']['Toilets']['external_attachments'] = array_replace_recursive($doc_data['daily']['Toilets']['external_attachments'],$toilets_merged_data); 
        }
        else
        {
                $doc_data['daily']['Toilets']['external_attachments'] = $toilet_external_final;
        }
        
        if(isset($doc_data['daily']['Kitchen']['external_attachments']))
        {
                $kitchen_merged_data = array_merge($doc_data['daily']['Kitchen']['external_attachments'],$kitchen_final);
                $doc_data['daily']['Kitchen']['external_attachments'] = array_replace_recursive($doc_data['daily']['Kitchen']['external_attachments'],$kitchen_merged_data);
        }
        else
        {
                $doc_data['daily']['Kitchen']['external_attachments'] = $kitchen_final;
        }

         if(isset($doc_data['weekly']['Dormitories']['external_attachments']))
        {
                $dormitory_merged_data = array_merge($doc_data['weekly']['Dormitories']['external_attachments'],$dormitory_final);
                $doc_data['weekly']['Dormitories']['external_attachments'] = array_replace_recursive($doc_data['weekly']['Dormitories']['external_attachments'],$dormitory_merged_data);
        }
        else
        {
                $doc_data['weekly']['Dormitories']['external_attachments'] = $dormitory_final;
        }


    } 
		//$school_code = $this->get_my_school_code();
   		$email    		= $_POST['email'];
   		//log_message('error','checking_sanitation_function_post_email==2852==monthly_ts'.print_r($email,true));
		$email_array    = explode(".",$email);
		$school_code    = (int) $email_array[1];
		$school_info = $this->healthsupervisor_app_model->get_school_info($school_code,$user_type);
		$school_name = $school_info[0]['school_name'];
		$dist = explode(',', $school_name);
		$districtName = $dist[1];				
		$today_date = date("Y-m-d");
		
		$doc_data['page4']['Declaration Information']['Date:'] = $today_date;

		$doc_data['page4']['School Information']['School Name'] = $school_name;
		$doc_data['page4']['School Information']['District'] = $districtName;


		$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 2;
		$doc_properties['_version'] = 2;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
		
		//$session_data = $this->session->userdata("customer");
		$email_id = $_POST['email'];
	
		$email = str_replace("@","#",$email_id);
		// History
		$approval_data = array(
			"current_stage" => "stage1",
			"approval" => "true",
			"submitted_by" => $email,
			'raised_by' => "device_side",
			"time" => date('Y-m-d H:i:s'));

		$history['last_stage'] = $approval_data;
		
		$added = $this->healthsupervisor_app_model->create_sanitation_report_model($doc_data, $doc_properties, $history,$user_type);
		if($added)
		{
			$this->output->set_output(json_encode(array('status' => TRUE,
														'message' => 'Sanitation Report submitted Succesfully')
									));
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Sanitation Report Not submitted')
									));
		}
	}
	function get_sanitation_report_daily()
	{
		$today_date = $_POST['today_date'];
		$email = str_replace("@", "#", $_POST['email']);
		$query = $this->healthsupervisor_app_model->get_sanitation_report_daily($today_date,$email);
		//$query['weekly'] = $this->healthsupervisor_app_model->get_sanitation_report_weekly($today_date,$email);
		$this->output->set_output(json_encode($query));
	}

	/*function get_sanitation_report_weekly()
	{
		$today_date = $_POST['today_date'];
		$email = str_replace("@", "#", $_POST['email']);
		$query = $this->healthsupervisor_app_model->get_sanitation_report_weekly($today_date,$email);
		$this->output->set_output(json_encode($query));
	}*/


	function update_screening_pie()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->data = $this->panacea_common_lib->update_screening_pie($today_date,$screening_pie_span);
	
		$this->output->set_output($this->data);
	
	}
	
	function refresh_screening_data()
	{
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$this->panacea_common_model->update_screening_collection($today_date,$screening_pie_span);
		$today_date = $this->panacea_common_model->get_last_screening_update();
		$this->output->set_output($today_date);
	}
	
	
	function drilling_screening_to_abnormalities()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TMREIS/i", $user_type))
		{
			$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			$screening_report = json_encode($this->bc_welfare_common_model->get_drilling_screenings_abnormalities($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		
	}
	
	function drilling_screening_to_districts()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TMREIS/i", $user_type))
		{
			$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			$screening_report = json_encode($this->bc_welfare_common_model->get_drilling_screenings_districts($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
	}
	
	function drilling_screening_to_schools()
	{
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			$screening_report = json_encode($this->panacea_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			$screening_report = json_encode($this->ttwreis_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TMREIS/i", $user_type))
		{
			$screening_report = json_encode($this->tmreis_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			$screening_report = json_encode($this->bc_welfare_common_model->get_drilling_screenings_schools($data,$today_date,$screening_pie_span));
			$this->output->set_output($screening_report);
		}
	}
	
	function drill_down_screening_to_students()
	{
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$data = $_POST['data'];
		$today_date = $_POST['today_date'];
		$screening_pie_span = $_POST['screening_pie_span'];
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			$docs = $this->panacea_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
			$screening_report = base64_encode(json_encode($docs));
			$this->output->set_output($screening_report);
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			$docs = $this->ttwreis_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
			$screening_report = base64_encode(json_encode($docs));
			$this->output->set_output($screening_report);
		}	
		if(preg_match("/TMREIS/i", $user_type))
		{
			$docs = $this->tmreis_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
			$screening_report = base64_encode(json_encode($docs));
			$this->output->set_output($screening_report);
		}	
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			$docs = $this->bc_welfare_common_model->get_drilling_screenings_students($data,$today_date,$screening_pie_span);
			$screening_report = base64_encode(json_encode($docs));
			$this->output->set_output($screening_report);
		}	
	}
	
	function drill_down_screening_to_students_load_ehr()
	{	
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_POST,true));
		$docs_id = explode(',',$_POST['ehr_data_for_screening']);
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
			$get_docs = $this->panacea_common_model->get_drilling_screenings_students_docs($docs_id);
			$this->data['students'] = $get_docs;
			//set the flash data error message if there is one
	
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
			$get_docs = $this->ttwreis_common_model->get_drilling_screenings_students_docs($docs_id);
			$this->data['students'] = $get_docs;
			//set the flash data error message if there is one	
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/TMREIS/i", $user_type))
		{
			//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
			$get_docs = $this->tmreis_common_model->get_drilling_screenings_students_docs($docs_id);
			$this->data['students'] = $get_docs;
			//set the flash data error message if there is one	
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			//$docs_id = json_decode(base64_decode($_POST['ehr_data']),true);
			$get_docs = $this->bc_welfare_common_model->get_drilling_screenings_students_docs($docs_id);
			$this->data['students'] = $get_docs;
			//set the flash data error message if there is one	
			$this->output->set_output(json_encode($this->data));
		}
	}
	
	public function drill_down_screening_to_students_load_ehr_doc($_id)
	{
		//log_message("debug",'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp---'.print_r($_id,true));
		$user_type = $_POST['user_type'];
		if(preg_match("/PANACEA/i", $user_type))
		{
			$docs = $this->panacea_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
			$this->data['docs'] = $docs['screening'];
			$this->data['docs_requests'] = $docs['request'];
			$this->data['notes'] = $docs['notes'];
			////log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
			
			$this->data['docscount'] = count($this->data['docs']);
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/TTWREIS/i", $user_type))
		{
			$docs = $this->ttwreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
			$this->data['docs'] = $docs['screening'];
			$this->data['docs_requests'] = $docs['request'];
			$this->data['notes'] = $docs['notes'];
			////log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
			
			$this->data['docscount'] = count($this->data['docs']);
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/TMREIS/i", $user_type))
		{
			$docs = $this->tmreis_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
			$this->data['docs'] = $docs['screening'];
			$this->data['docs_requests'] = $docs['request'];
			$this->data['notes'] = $docs['notes'];
			////log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
			
			$this->data['docscount'] = count($this->data['docs']);
			$this->output->set_output(json_encode($this->data));
		}
		if(preg_match("/BCWELFARE/i", $user_type))
		{
			$docs = $this->bc_welfare_common_model->drill_down_screening_to_students_load_ehr_doc($_id);
			$this->data['docs'] = $docs['screening'];
			$this->data['docs_requests'] = $docs['request'];
			$this->data['notes'] = $docs['notes'];
			////log_message("debug",'doccccccccccccccccccccccccccccccccccccc---'.print_r($this->data,true));
			
			$this->data['docscount'] = count($this->data['docs']);
			$this->output->set_output(json_encode($this->data));
		}
	}
	public function get_hs_requests_count_all()
	{
		$today_date = trim($_POST['today_date']);
		$user_type = $_POST['user_type'];
		
		$this->data['Initiate Rrequests'] = $this->healthsupervisor_app_model->get_initaite_requests_count_today_date($today_date,$user_type);
		$this->data['Normal'] = $this->healthsupervisor_app_model->get_normal_requests_count_today_date($today_date,$user_type);
		$this->data['Emergency'] = $this->healthsupervisor_app_model->get_emergency_requests_count_today_date($today_date,$user_type);
		$this->data['Chronic'] = $this->healthsupervisor_app_model->get_chronic_requests_count_today_date($today_date,$user_type);
		$this->data['Doctors Responded'] = $this->healthsupervisor_app_model->get_doctors_response_count_today_date($today_date,$user_type);
		
		
		if(isset($this->data) && !empty($this->data))
	  {
		  $this->output->set_output(json_encode($this->data));
	  }
	  else
	  {
		  $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}
	
	public function get_hs_requests_count_by_school()
	{
		$today_date = trim($_POST['today_date']);
		$dt_name = strtoupper($_POST['dt_name']);
		$school_name = $_POST['school_name'];
		$user_type = $_POST['user_type'];

		$this->data['Initiate Requests'] = $this->healthsupervisor_app_model->get_initaite_requests_count($today_date, $dt_name, $school_name,$user_type);
		$this->data['Normal'] = $this->healthsupervisor_app_model->get_normal_requests_count($today_date, $dt_name, $school_name,$user_type);
		$this->data['Emergency'] = $this->healthsupervisor_app_model->get_emergency_requests_count($today_date, $dt_name, $school_name,$user_type);
		$this->data['Chronic'] = $this->healthsupervisor_app_model->get_chronic_requests_count($today_date, $dt_name, $school_name,$user_type);
		$this->data['Doctors Responded'] = $this->healthsupervisor_app_model->get_doctors_response_count($today_date, $dt_name, $school_name,$user_type);
		
		if(isset($this->data) && !empty($this->data))
	  {
		  $this->output->set_output(json_encode($this->data));
	  }
	  else
	  {
		  $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}

	function drilling_to_identifiers()
	{
		$today_date = $_POST['today_date'];
		$request_type_span = $_POST['request_type_span'];
		$user_type = $_POST['user_type'];
		$identifiers_report = json_encode($this->healthsupervisor_app_model->get_drilling_to_identifiers($today_date,$request_type_span,$user_type));
		$this->output->set_output($identifiers_report);
	}

	function drilldown_barchat_identifiers_to_districts()
	{
		//$data 				= $_POST['data'];
		$today_date 		= $_POST['today_date'];
		$request_type_span 	= $_POST['request_type_span'];
		$dt_name 			= strtoupper($_POST["dt_name"]);
		$user_type = $_POST['user_type'];
		//$school_name 		= $_POST["school_name"];
		//$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = json_encode($this->healthsupervisor_app_model->drilldown_barchat_identifiers_to_districts($today_date,$request_type_span,$dt_name,$user_type));
		$this->output->set_output($identifiers_report);
	}

	function drilldown_barchat_identifiers_to_schools()
	{
		//$data 				= $_POST['data'];
		$today_date 		= $_POST['today_date'];
		$request_type_span 	= $_POST['request_type_span'];
		$dt_name 			= strtoupper($_POST["dt_name"]);
		$school_name 		= $_POST["school_name"];
		$user_type = $_POST['user_type'];
		//$school_name 		= $_POST["school_name"];
		//$request_pie_status = $_POST['request_pie_status'];
		$identifiers_report = $this->healthsupervisor_app_model->drilldown_barchat_identifiers_to_school_name($today_date,$request_type_span,$dt_name,$school_name,$user_type);
		$this->data['students'] = $identifiers_report;
		
		$this->output->set_output(json_encode($this->data));
	}
	
	/**
	 * Helper: Chronic Pie
	 *
	 * @author Vikas 
	 *
	 * @return array 
	 */

	public function chronic_pie_view()
	{
		
		$count = 0;
		$request_report = $this->panacea_common_model->get_chronic_request();

		////log_message('error','HEALTHCARE_APP======CHRONIC_PIE_VIEW=====$REQUEST_REPORT==>'.print_r($request_report,true));

		foreach ($request_report as $value)
		{
			$count = $count + intval($value['value']);
		}

		if($count > 0)
		{
			$this->data['request_report'] = json_encode($request_report);
		}
		else
		{
			$this->data['request_report'] = 1;
		}

		//log_message('debug','HEALTHCARE_APP======CHRONIC_PIE_VIEW=====$THIS->DATA==>'.print_r($this->data,true));

		$this->output->set_output(json_encode($this->data));
	}

	public function get_unique_id_for_chat()
	{
		$unique_id = $_POST['unique_id'];
		$user_type = $_POST['user_type'];
		$query = $this->healthsupervisor_app_model->get_unique_id_for_chat($unique_id,$user_type);
		if(isset($query))
		{
			$this->output->set_output(json_encode($query));
		}else{
			$this->output->set_output(json_encode(
										array('status' => FALSE,
											  'message' => 'NO_DATA_AVAILABLE')
										));
		}

	}

	public function insert_unique_id_with_attachment()
	{
		$unique_id = $_POST['unique_id'];
		$doc_id = $_POST['doc_id'];
		$user_type = $_POST['user_type'];
		
		// Attachments
		 if(isset($_FILES) && !empty($_FILES))
		 {
	       $this->load->library('upload');
	       $this->load->library('image_lib');
		   
		   $external_files_upload_info = array();
		   $external_final             = array();
		   $external_merged_data       = array();		   
		   
		   $files = $_FILES;
		   $cpt = count($_FILES['chat_attachment']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['chat_attachment']['name']	= $files['chat_attachment']['name'][$i];
			 $_FILES['chat_attachment']['type']	= $files['chat_attachment']['type'][$i];
			 $_FILES['chat_attachment']['tmp_name'] = $files['chat_attachment']['tmp_name'][$i];
			 $_FILES['chat_attachment']['error']	= $files['chat_attachment']['error'][$i];
			 $_FILES['chat_attachment']['size']	= $files['chat_attachment']['size'][$i];
		
		   foreach ($_FILES as $index => $value)
	       {
			  if(!empty($value['name']))
			  {
			      
			        $config = array();
					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/files/chat_attachment_images/';
					$config['allowed_types'] = '*';
					$config['max_size']      = '4096';
					$config['encrypt_name']  = TRUE;
		
			        //create controller upload folder if not exists
					if (!is_dir($config['upload_path']))
					{
						mkdir(UPLOADFOLDERDIR."public/uploads/files/chat_attachment_images/",0777,TRUE);
					}
		
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload($index))
					{
						 $this->session->set_flashdata('message','Submission failed. Each attachment file should be less than 2 MB');
						
					}
					else
					{
						$external_files_upload_info = $this->upload->data();
						$rand_number = mt_rand();
						$external_data_array = array(
												  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
												"file_client_name" =>$external_files_upload_info['client_name'],
												"file_encrypted_name" =>$external_files_upload_info['file_name'],
												"file_path" =>$external_files_upload_info['file_relative_path'],
												"file_size" =>$external_files_upload_info['file_size']
																)

													 );

						$external_final = array_merge($external_final,$external_data_array);
						
					}  
				}
			}
		 }
		 	$doc_history = $this->healthsupervisor_app_model->get_history($unique_id,$doc_id,$user_type);

			if(isset($doc_history['0']['doc_data']['external_attachments']))
			{   
				$external_merged_data = array_merge($doc_history['0']['doc_data']['external_attachments'],$external_final);
				$external_attachments = array_replace_recursive($doc_history['0']['doc_data']['external_attachments'],$external_merged_data);
			}
			else
			{
			   $external_attachments = $external_final;			   
			} 
		}
		 	////log_message('error','dataaaaaaaaaaaaaaaaa'.print_r($external_attachments,TRUE));exit();
		$query = $this->healthsupervisor_app_model->insert_unique_id_with_attachment($unique_id,$doc_id,$external_attachments,$user_type);

		if(isset($query))
		{
			$this->output->set_output(json_encode($query));
		}else{
			$this->output->set_output(json_encode(
									  array('status' => FALSE,
									  		'message' => "Failed to Insert Attachment ! Try again")
									));
		}
	}
	
	public function get_app_version()
	{
		$this->output->set_output(json_encode(
										array('version' => "3.2",
											'status' => TRUE)
										));
	}
	public function get_app_version_chat_users()
	{
		$this->output->set_output(json_encode(
										array('version' => "1.0",
											'status' => TRUE)
										));
	}

	function get_info_details()
	{
		$query = $this->healthsupervisor_app_model->get_info_details();
		//log_message('error','query===========3362'.print_r($query,TRUE));
		$this->output->set_output(json_encode($query));
	}

	function update_student_profile()
	{

		//log_message('error', 'checking post update students'.print_r($_POST, true));
	  // Variables
	  $photo_obj = array();
	  // POST DATA
	  $student_name = $_POST['name'];
	  $student_mob  = $_POST['mobile'];
	  $student_dob  = $_POST['date_of_birth'];
	  $father_name  = $_POST['father_name'];
	  $class        = $_POST['class'];
	  $section      = $_POST['section'];
	  $unique_id    = $_POST['unique_id'];
	  $user_type    = $_POST['user_type'];
	  $gender       = (isset($_POST['gender'])) ? $_POST['gender'] : "";

	  $aadhar_card    = $_POST['aadhar'];
	  $ration_card    = $_POST['ration'];
	  $arogya_sri    = $_POST['arogya'];

	  if(isset($_POST['doc_id']) && !empty($_POST['doc_id']))
	  {
	  $doc_id    = $_POST['doc_id'];
	  }else{
	  	$doc_id    = "";
	  }	 

	  $exists_unique = $this->healthsupervisor_app_model->check_unique_exists($unique_id,$user_type,$doc_id);

	  //log_message('error', 'checking existng unique'.print_r($exists_unique, true));
	  if(!empty($exists_unique) && isset($exists_unique))
	  {
		  $update_profile = array(
		   'doc_data.widget_data.page1.Personal Information.Name'           => $student_name,
		   'doc_data.widget_data.page1.Personal Information.Mobile.mob_num' => $student_mob,
		   'doc_data.widget_data.page1.Personal Information.Date of Birth'  => $student_dob,
		   'doc_data.widget_data.page2.Personal Information.Class'          => $class,
		   'doc_data.widget_data.page2.Personal Information.Section'        => $section,
		   'doc_data.widget_data.page2.Personal Information.Father Name'    => $father_name,		   
		   'doc_data.widget_data.page2.Personal Information.Aarogya sri'    => $arogya_sri,
		   'doc_data.widget_data.page2.Personal Information.Ration card'    => $ration_card,
		   'doc_data.widget_data.page2.Personal Information.Aadhar card'    => $aadhar_card,
		   'doc_data.widget_data.page1.Personal Information.Gender'         => $gender);
		  

		  if(isset($_FILES) && !empty($_FILES))
		  {
		       $this->load->library('upload');
			   
		       $config = array();
		       //$code = explode("_",$unique_id);
		     // $query_ts = $this->healthsupervisor_app_model->check_school_code($code[1]);
			   if($user_type == "PANACEA_HS")
			   {
			   	   $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare2016226112942701_con/photo/';
				   $config['allowed_types'] 	= '*';
				   $config['min_size'] 		    = '*';
				   $config['max_size'] 		    = '*';
				   $config['encrypt_name']		= TRUE;
				   
		           //create controller upload folder if not exists
				   if (!is_dir($config['upload_path']))
				   {
					  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare2016226112942701_con/photo/",0777,TRUE);
				   }
			   }else if ($user_type == "TMREIS_HS") {
			   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201672020159570_con/photo/';
				   $config['allowed_types'] 	= '*';
				   $config['min_size'] 		    = '1024';
				   $config['max_size'] 		    = '5120';
				   $config['encrypt_name']		= TRUE;
				   
		           //create controller upload folder if not exists
				   if (!is_dir($config['upload_path']))
				   {
					  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201672020159570_con/photo/",0777,TRUE);
				   }
			   }else if($user_type == "TTWREIS_HS") {
			   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201671115519757_con/photo/';
				   $config['allowed_types'] 	= '*';
				   $config['min_size'] 		    = '1024';
				   $config['max_size'] 		    = '5120';
				   $config['encrypt_name']		= TRUE;
				   
		           //create controller upload folder if not exists
				   if (!is_dir($config['upload_path']))
				   {
					  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201671115519757_con/photo/",0777,TRUE);
				   }
				}else if($user_type == "BCWELFARE_HS") {
			   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201812217594045_con/photo/';
				   $config['allowed_types'] 	= '*';
				   $config['min_size'] 		    = '1024';
				   $config['max_size'] 		    = '5120';
				   $config['encrypt_name']		= TRUE;
				   
		           //create controller upload folder if not exists
				   if (!is_dir($config['upload_path']))
				   {
					  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201812217594045_con/photo/",0777,TRUE);
				   }
				}
		       /* $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare2016226112942701_con/photo/';
				   $config['allowed_types'] 	= '*';
				   $config['min_size'] 		    = '1024';
				   $config['max_size'] 		    = '5120';
				   $config['encrypt_name']		= TRUE;
				   
		           //create controller upload folder if not exists
				   if (!is_dir($config['upload_path']))
				   {
					  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare2016226112942701_con/photo/",0777,TRUE);
				   }*/
				   
			   // Student Photo
			   foreach ($_FILES as $index => $value)
			   {
				 $this->upload->initialize($config);
				 if(!empty($value['name']))
				 {
				 if ( ! $this->upload->do_upload($index))
				 {
					echo "file upload failed";
					return FALSE;
				 }
				 else
				 {
					$photo_obj = $this->upload->data();
				 	$photo_ele = array(
						"file_client_name"    => $photo_obj['client_name'],
						"file_encrypted_name" => $photo_obj['file_name'],
						"file_path" 		  => $photo_obj['file_relative_path'],
						"file_size" 		  => $photo_obj['file_size']
			 		 );
				}
			  	 $update_profile['doc_data.widget_data.page1.Personal Information.Photo']= $photo_ele;	 
			   }
			  
		  	}
		  }
		  
		  $ehr_update = $this->healthsupervisor_app_model->update_student_ehr_model($unique_id,$update_profile,$user_type,$doc_id); 
		  if(!empty($ehr_update))
		  {
		  	$this->output->set_output(json_encode(array('status' => TRUE,
		  												'message' => 'Updated Succesfully!')
		  										));
		  }
		  else{
		  	$this->output->set_output(json_encode(array('status' => FALSE,
		  												'message' => "Not Updated Profile Info")));
		  }
		}
		else
		{
				$school_name    = $_POST['school_name'];
		 		$district    = $_POST['district'];
		 		$email = $_POST['email'];
				$doc_data['page1']['Personal Information']['Hospital Unique ID'] = $unique_id;
				$doc_data['page1']['Personal Information']['Name'] = $student_name;
				$doc_data['page1']['Personal Information']['Date of Birth'] = $student_dob;
				$doc_data['page1']['Personal Information']['Mobile']['country_code'] = "91";
				$doc_data['page1']['Personal Information']['Mobile']['mob_num'] = $student_mob;
				$doc_data['page1']['Personal Information']['Gender'] = $gender;
				$doc_data['page2']['Personal Information']['AD No'] = "";
				$doc_data['page2']['Personal Information']['Class'] = $class;
				$doc_data['page2']['Personal Information']['Section'] = $section;
				$doc_data['page2']['Personal Information']['District'] = $district; 
				$doc_data['page2']['Personal Information']['School Name'] = $school_name;
				$doc_data['page2']['Personal Information']['Father Name'] = $father_name;
				$doc_data['page2']['Personal Information']['Date of Exam'] = "";
				$doc_data['page2']['Personal Information']['Aarogya sri'] = $arogya_sri;
				$doc_data['page2']['Personal Information']['Ration card'] = $ration_card;
				$doc_data['page2']['Personal Information']['Aadhar card'] = $aadhar_card;
				$doc_data['page3'] = [];
				$doc_data['page4'] = [];
				$doc_data['page5'] = [];
				$doc_data['page6'] = [];
				$doc_data['page7'] = [];
				$doc_data['page8'] = [];
				$doc_data['page9'] = [];
			  if(isset($_FILES) && !empty($_FILES))
			  {
			       $this->load->library('upload');
				   
			       $config = array();
			       //$code = explode("_",$unique_id);
			     // $query_ts = $this->healthsupervisor_app_model->check_school_code($code[1]);
				   if($user_type == "PANACEA_HS")
				   {
				   	   $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare2016226112942701_con/photo/';
					   $config['allowed_types'] 	= '*';
					   $config['min_size'] 		    = '1024';
					   $config['max_size'] 		    = '5120';
					   $config['encrypt_name']		= TRUE;
					   
			           //create controller upload folder if not exists
					   if (!is_dir($config['upload_path']))
					   {
						  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare2016226112942701_con/photo/",0777,TRUE);
					   }
				   }else if ($user_type == "TMREIS_HS") {
				   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201672020159570_con/photo/';
					   $config['allowed_types'] 	= '*';
					   $config['min_size'] 		    = '1024';
					   $config['max_size'] 		    = '5120';
					   $config['encrypt_name']		= TRUE;
					   
			           //create controller upload folder if not exists
					   if (!is_dir($config['upload_path']))
					   {
						  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201672020159570_con/photo/",0777,TRUE);
					   }
				   }else if($user_type == "TTWREIS_HS") {
				   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201671115519757_con/photo/';
					   $config['allowed_types'] 	= '*';
					   $config['min_size'] 		    = '1024';
					   $config['max_size'] 		    = '5120';
					   $config['encrypt_name']		= TRUE;
					   
			           //create controller upload folder if not exists
					   if (!is_dir($config['upload_path']))
					   {
						  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201671115519757_con/photo/",0777,TRUE);
					   }
					}else if($user_type == "BCWELFARE_HS") {
				   	 $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare201812217594045_con/photo/';
					   $config['allowed_types'] 	= '*';
					   $config['min_size'] 		    = '1024';
					   $config['max_size'] 		    = '5120';
					   $config['encrypt_name']		= TRUE;
					   
			           //create controller upload folder if not exists
					   if (!is_dir($config['upload_path']))
					   {
						  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare201812217594045_con/photo/",0777,TRUE);
					   }
					}
			       /* $config['upload_path'] 		= UPLOADFOLDERDIR.'public/uploads/healthcare2016226112942701_con/photo/';
					   $config['allowed_types'] 	= '*';
					   $config['min_size'] 		    = '1024';
					   $config['max_size'] 		    = '5120';
					   $config['encrypt_name']		= TRUE;
					   
			           //create controller upload folder if not exists
					   if (!is_dir($config['upload_path']))
					   {
						  mkdir(UPLOADFOLDERDIR."public/uploads/healthcare2016226112942701_con/photo/",0777,TRUE);
					   }*/
					   
				   // Student Photo
				   foreach ($_FILES as $index => $value)
				   {
						 $this->upload->initialize($config);
						 if(!empty($value['name']))
						 {
						 if ( ! $this->upload->do_upload($index))
						 {
							echo "file upload failed";
							return FALSE;
						 }
						 else
						 {
							$photo_obj = $this->upload->data();
						 	$photo_ele = array(
								"file_client_name"    => $photo_obj['client_name'],
								"file_encrypted_name" => $photo_obj['file_name'],
								"file_path" 		  => $photo_obj['file_relative_path'],
								"file_size" 		  => $photo_obj['file_size']
					 		 );
						}
					  	 $doc_data['page1']['Personal Information']['Photo']= $photo_ele;	 
					   }
		  			}
			  }else
			  {
			  	$doc_data['page1']['Personal Information']['Photo'] = "";
			  }

			    $doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";

				$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $email,
				'raised_by' => "device_side",
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;

			 $inserted =  $this->healthsupervisor_app_model->create_new_student($doc_data,$user_type,$doc_properties,$history);
			 if(!empty($inserted))
			  {
			  	$this->output->set_output(json_encode(array('status' => TRUE,
			  												'message' => 'Created Succesfully!')
			  										));
			  }
			  else{
			  	$this->output->set_output(json_encode(array('status' => FALSE,
			  												'message' => "Not Updated Profile Info")));
			  }
		}
	  
	}

	function submit_hb_values_device()
	{
		// POST DATA
		$unique_id    = $_POST['unique_id'];
		$student_name = $_POST['name'];
		$class        = $_POST['class'];
		$section      = $_POST['section'];
		$blood_group  = $_POST['blood_group'];
		$school_name  = $_POST['school_name'];
		$district      = $_POST['district'];
		$email_id = $_POST['email'];
		$hb = $_POST['hb'];
		$user_type = $_POST['user_type'];

		$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
		if($gender_info == "B")
		{
			$gender = "Male";
		}else if($gender_info == "G")
		{
			$gender = "Female";
		}

		$hb_array = array();
		$month = date("Y-m-d");
		$new_date = new DateTime($month);

		$ndate = $new_date->format('Y-m-d');
	  
		
		 $hb_values = array(
					'hb' => (double)$hb,
					'month' => $ndate
				);	
		array_push($hb_array, $hb_values);
	 // $exists_unique = $this->healthsupervisor_app_model->check_unique_exists($unique_id,$hb_values);
	 //log_message('error','exists_unique========3554'.print_r($exists_unique,TRUE));
	 /* if(!empty($exists_unique) && isset($exists_unique))
	  {

	  }*/
	    $hb_information['page1']['Student Details']['Hospital Unique ID'] = $unique_id;
	    $hb_information['page1']['Student Details']['Name']['field_ref']          = $student_name;
	    $hb_information['page1']['Student Details']['Class']['field_ref']         = $class;
	    $hb_information['page1']['Student Details']['Section']['field_ref']       = $section;
	    $hb_information['page1']['Student Details']['bloodgroup']['field_ref']    = $blood_group;
		$hb_information['page1']['Student Details']['HB_values'] 				 = $hb_array;
		$hb_information['page1']['Student Details']['Gender'] 				 = ($gender) ? $gender : "";
		$hb_information['school_details']['School Name'] =  $school_name;
		$hb_information['school_details']['District'] =   $district;

	  	$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 1;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";
	
		$email = str_replace("@","#",$email_id);
      	// History
		$approval_data = array(
			"current_stage" => "stage1",
			"approval" => "true",
			"submitted_by" => $email,
			'raised_by' => "device_side",
			"time" => date('Y-m-d H:i:s'));

		$history['last_stage'] = $approval_data;

	 $create_hb =  $this->healthsupervisor_app_model->insert_hb_values($unique_id,$hb_information,$hb_values,$doc_properties,$history,$user_type);

	  if($create_hb)
	  {
	  	$this->output->set_output(json_encode(array('Status' => TRUE,
	  												'message' => "HB Updated Succesfully")));
	  }else
	  {
	  	$this->output->set_output(json_encode(array('Status' => TRUE,
	                                                'message' => "Failed")));
	  }
	}

	function get_submitted_hb_values()
	{
		$email = $_POST['email'];
		$date = $_POST['selected_date'];
		$user_type = $_POST['user_type'];
		$query = $this->healthsupervisor_app_model->get_submitted_hb_values($email,$date,$user_type);
		if(!empty($query))
		{
			$this->output->set_output(json_encode($query));
		}else{
			$this->output->set_output(json_encode(array('status' => FALSE)));
		}
	}

	function submit_bmi_values_device()
	{
		// POST DATA
		$unique_id    = $_POST['unique_id'];
		$student_name = $_POST['name'];
		$class        = $_POST['class'];
		$section      = $_POST['section'];
		$school_name  = $_POST['school_name'];
		$district     = $_POST['district'];
		$email_id 	  = $_POST['email'];
		$height 	  = $_POST['height'];
		$weight 	  = $_POST['weight'];
		$bmi 		  = $_POST['bmi'];
		$user_type = $_POST['user_type'];
		$bmi_array = array();
		$month = date("Y-m-d");
		$new_date = new DateTime($month);

		$ndate = $new_date->format('Y-m-d');
	  
		
		 $bmi_values = array(
					'height' => (string)$height,
					'weight' => $weight,
					'bmi' => (double)$bmi,
					'month' => $ndate
				);	
		array_push($bmi_array, $bmi_values);
	 	$gender_info = substr($school_name,strpos($school_name, "),")-1,1);
		if($gender_info == "B")
		{
			$gender = "Male";
		}else if($gender_info == "G")
		{
			$gender = "Female";
		}

		$age = "";
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
	    $bmi_information['page1']['Student Details']['Hospital Unique ID'] = $unique_id;
	    $bmi_information['page1']['Student Details']['Name']['field_ref']          = $student_name;
	    $bmi_information['page1']['Student Details']['Class']['field_ref']         = $class;
	    $bmi_information['page1']['Student Details']['Section']['field_ref']       = $section;
	    $bmi_information['page1']['Student Details']['Gender']       = ($gender) ? $gender : "";
	    $bmi_information['page1']['Student Details']['Age']       = ($age) ? $age : "";
		$bmi_information['page1']['Student Details']['BMI_values'] 				 = $bmi_array;
		$bmi_information['page1']['Student Details']['BMI_latest'] 				 = $bmi_values;
		$bmi_information['school_details']['School Name'] =  $school_name;
		$bmi_information['school_details']['District'] =   $district;

	  	$doc_properties['doc_id'] = get_unique_id();
		$doc_properties['status'] = 1;
		$doc_properties['_version'] = 1;
		$doc_properties['doc_owner'] = "PANACEA";
		$doc_properties['unique_id'] = '';
		$doc_properties['doc_flow'] = "new";

		$email = str_replace("@","#",$email_id);
      	// History
		$approval_data = array(
			"current_stage" => "stage1",
			"approval" => "true",
			"submitted_by" => $email,
			'raised_by' => "device_side",
			"time" => date('Y-m-d H:i:s'));

		$history['last_stage'] = $approval_data;

	 $create_bmi =  $this->healthsupervisor_app_model->insert_bmi_values($unique_id,$bmi_information,$bmi_values,$doc_properties,$history,$user_type);

	  if($create_bmi)
	  {
	  	$this->output->set_output(json_encode(array('Status' => TRUE,
	  												'message' => "BMI Updated Succesfully")));
	  }else
	  {
	  	$this->output->set_output(json_encode(array('Status' => TRUE,
	                                                'message' => "Failed")));
	  }
	}

	function get_submitted_bmi_values()
	{
		$email = $_POST['email'];
		$date = $_POST['selected_date'];
		$user_type = $_POST['user_type'];
		$query = $this->healthsupervisor_app_model->get_submitted_bmi_values($email,$date,$user_type);
		if(!empty($query))
		{
			$this->output->set_output(json_encode($query));
		}else{
			$this->output->set_output(json_encode(array('status' => FALSE)));
		}
	}
	function get_school_health_status()
	{
		$school_name = $_POST['school_name'];
		$user_type = $_POST['user_type'];
		$query = $this->healthsupervisor_app_model->get_school_health_status($school_name,$user_type);
		
		if(!empty($query))
		{
			$this->output->set_output(json_encode($query));
		}else{
			$this->output->set_output(json_encode(array('status' => FALSE)));
		}
	}

	function get_last_unique_id_from_school()
	{
		$user_type = $_POST['user_type'];
		$email = $_POST['email'];
		$school_code = explode(".", $email);
		$code = $school_code[0]."_".$school_code[1];
		$new_UID = $this->healthsupervisor_app_model->get_last_unique_id_from_school($code,$user_type);
		if(!empty($new_UID))
		{
			$this->output->set_output(json_encode($new_UID));
		}else
		{
			$this->output->set_output(json_encode(array('status' => FALSE)));
		}
	}
	public function get_all_schools_health_status()
	{
		$user_type = $_POST['user_type'];
		$chronic_count = $this->healthsupervisor_app_model->get_all_schools_health_status_count_model($user_type);
		$this->output->set_output(json_encode($chronic_count));
	}

	/**
	 *Helper:Showing counts in the Dashboard
	 *
	 */
	
	public function get_counts_to_dashboard()
	{
		// Get All Schools list
		//$this->data['all_schools_names'] = $this->healthsupervisor_app_model->get_all_schools();
		///$this->data['all_schools_names'] = $this->healthsupervisor_app_model->get_all_schools_list();
		//$student_details = $this->maharashtra_common_model->get_all_schools_list();

		// Get No.of Schools Count
	//	$this->data['all_schools_count'] = count($this->data['all_schools_names']);
		
		// Get All Students Count
		$this->data['all_students_count'] = $this->healthsupervisor_app_model->total_students_count_in_all_schools();
	
		// Get Total Screened Students Count
		$data = $this->healthsupervisor_app_model->screened_schools_count();
		
		$this->data['screened_schools_count'] = $data['screened_schools_count'];
		$this->data['screened_schools_list']  = $data['screened_schools_list'];
		$this->data['not_screened_schools_count'] = $data['not_screened_schools_count'];
		$this->data['not_screened_schools_list'] = $data['not_screened_schools_list'];
		$this->data['all_schools_names'] = $data['all_schools_names'];
		$this->data['all_schools_count'] = $data['all_schools_count'];
		$this->data['screened_students_count'] = $data['screened_students_count'];
		$this->data['screened_not_students_count'] = $data['screened_not_students_count'];
		
		  if(isset($this->data) && !empty($this->data))
		  {
			  $this->output->set_output(json_encode($this->data));
		  }
		  else
		  {
			  $this->output->set_output('NO_DATA_AVAILABLE');
		  }
	}

	function get_school_wise_classes_list()
	{
		$school = $_POST['school_name'];
		$user_type = $_POST['user_type'];
		$classes_list = $this->healthsupervisor_app_model->get_school_wise_classes_list($school,$user_type);
		sort($classes_list);
		$classes = array();
		$class_length = count($classes_list);
		for($x = 0; $x < $class_length; $x++) {
		    array_push($classes, $classes_list[$x]);
		}
		$this->output->set_output(json_encode($classes));
	}

	// IOS 
	function get_students_list_device_ios()
	{
		$school = $_POST['school_name'];
		$class = $_POST['class'];
		$user_type = $_POST['user_type'];
		$students_lists = $this->healthsupervisor_app_model->get_students_list_device_ios($school,$class,$user_type);
		$this->output->set_output(json_encode($students_lists));
	}

	public function get_schools_health_status_ios()
	{
		$chronic_count = $this->healthsupervisor_app_model->get_schools_health_status_count_model_new();
		$this->output->set_output(json_encode($chronic_count));
	}


	public function screened_students_by_school()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(preg_match("/PANACEA/i", $user_type))
			{
				if(!empty($_POST['school_name']))
				{
					$school_name = $_POST['school_name'];
					$this->data['screened_students_list'] = $this->healthsupervisor_app_model->get_screened_students_list_school_wise($school_name);
					$this->data['screened_students_count'] = count($this->data['screened_students_list']);
					if(!empty($this->data))
					{
						$this->output->set_output(json_encode($this->data));
					}
					else
					{
						$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'No Students Found')
										));
					}
				}else{
					$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'School Name Required')
										));
				}
			}
		}else{
					$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
				}

	}
	public function not_screened_students_by_school()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(preg_match("/PANACEA/i", $user_type))
			{
				$school_name = $_POST['school_name'];
				if(!empty($_POST['school_name']))
				{
					$this->data['not_screened_students_list'] = $this->healthsupervisor_app_model->get_not_screened_students_school_wise($school_name);
					$this->data['not_screened_students_count'] = count($this->data['not_screened_students_list']);
					if(!empty($this->data))
					{
						$this->output->set_output(json_encode($this->data));
					}
					else
					{
						$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'No Students Found')
										));
					}
				}else{
					$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'School Name Required')
										));
				}
			}
		}else{
					$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
				}
		
	}

	/**
	 * PANACEA Schools
	 * Helper:Get all BMI related Counts(Under Weight, Over Weight, Obese, Normal)
	 *
	 */
	public function get_bmi_report_count()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			$today_date = $_POST['today_date'];
			//$today_date = date('Y-m-d');
			if(preg_match("/PANACEA/i", $user_type))
			{
				$this->data = $this->healthsupervisor_app_model->get_bmi_report_doctor_modal_count($today_date);
				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}
				else
				{
					$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed')
									));
				}
			}
		}
		else{
			$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
		}
	}

	public function get_hb_report_count()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			$today_date = $_POST['today_date'];
			//$today_date = date('Y-m-d');
			if(preg_match("/PANACEA/i", $user_type))
			{
				$this->data = $this->healthsupervisor_app_model->get_hb_report_doctor_modal_count($today_date);
				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}
				else
				{
					$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed')
									));
				}
			}
		}
		else{
			$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
		}
	}

	public function get_doctors_visit_schools_count()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			
			if(isset($_POST['today_date']))
			{
				$today_date = $_POST['today_date'];
			}
			else
			{
				$today_date = false;
			}
			//$today_date = date('Y-m-d');
			if(preg_match("/PANACEA/i", $user_type))
			{
				$data_req = $this->healthsupervisor_app_model->get_total_requests($today_date);
				$this->data['doctor_visits_total_count'] = $data_req['doctor_visits_total_count'];
				$this->data['doctor_visits_today_date'] = $data_req['doctor_visits_today_date'];
				

				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}else
				{
					$this->output->set_output(json_encode(array('status' => FALSE,'message'=>'failed')));
				}
			}
		}else
		{
			$this->output->set_output(json_encode(array('status' => FALSE,'message' => "User Type is Required")));
		}
	}

	public function get_all_hs_raised_request_count()
	{

		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			
			if(isset($_POST['today_date']))
			{
				$today_date = $_POST['today_date'];
			}
			else
			{
				$today_date = false;
			}
			//$today_date = date('Y-m-d');
			if(preg_match("/PANACEA/i", $user_type))
			{
				$data_req = $this->healthsupervisor_app_model->get_total_requests($today_date);
				$this->data['total_request_count'] = $data_req['total_req_count'];
				$this->data['total_request_not_cured_count'] = $data_req['total_req_count'];
				$this->data['total_request_cured_count'] = $data_req['total_req_count'];
				$this->data['normal_req_count'] = $data_req['normal_req_count'];
				$this->data['normal_req_count_not_cured'] = $data_req['normal_req_count_not_cured'];
				$this->data['normal_req_count_cured'] = $data_req['normal_req_count_cured'];
				$this->data['emergency_req_count'] = $data_req['emergency_req_count'];
				$this->data['emergency_req_count_not_cured'] = $data_req['emergency_req_count_not_cured'];
				$this->data['emergency_req_count_cured'] = $data_req['emergency_req_count_cured'];
				$this->data['chronic_req_count'] = $data_req['chronic_req_count'];
				$this->data['chronic_req_count_not_cured'] = $data_req['chronic_req_count_not_cured'];
				$this->data['chronic_req_count_cured'] = $data_req['chronic_req_count_cured'];
				$this->data['total_request_not_cured_count'] = $data_req['normal_req_count_not_cured'] + $data_req['emergency_req_count_not_cured'] + $data_req['chronic_req_count_not_cured'];
				$this->data['total_request_cured_count'] = $data_req['normal_req_count_cured'] + $data_req['emergency_req_count_cured'] + $data_req['chronic_req_count_cured'];
				$this->data['attendance_submitted_count'] = $data_req['attendance_submitted_count'];
				$this->data['attendance_not_submitted_count'] = $data_req['attendance_not_submitted_count'];
				$this->data['sanitation_submitted_count'] = $data_req['sanitation_submitted_count'];
				$this->data['sanitation_not_submitted_count'] = $data_req['sanitation_not_submitted_count'];

				$this->data['out_patient_total_count'] = $data_req['out_patient_total_count'];
				$this->data['admitted_total_count'] = $data_req['admitted_total_count'];
				$this->data['review_cases_total_count'] = $data_req['review_cases_total_count'];
				$this->data['doctor_visits_total_count'] = $data_req['doctor_visits_total_count'];
				
				$this->data['out_patient_today_date'] = $data_req['out_patient_today_date'];
				$this->data['admitted_today_date'] = $data_req['admitted_today_date'];
				$this->data['review_cases_today_date'] = $data_req['review_cases_today_date'];
				$this->data['doctor_visits_today_date'] = $data_req['doctor_visits_today_date'];
				
				
				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}else
				{
					$this->output->set_output(json_encode(array('status' => FALSE,'message'=>'failed')));
				}
				
			}
		}else
		{
			$this->output->set_output(json_encode(array('status' => FALSE,'message' => "User Type is Required")));
		}
	}

	public function get_hs_requests_cured_and_not_cured()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(isset($_POST['count']) && isset($_POST['today_date']))
			{
				$today_date = $_POST['today_date'];
				$count = $_POST['count'];
			}else
			{
				$today_date = false;
				$count = false;
			}
			
			if(preg_match("/PANACEA/i", $user_type))
			{
                if(!empty( $_POST['request_type'] ))
                {
                    $request_type = $_POST['request_type'];
    				$data = $this->healthsupervisor_app_model->get_hs_requests_cured_and_not_cured($request_type,$count,$today_date);
    				if(!empty($data))
    				{
    					$this->output->set_output(json_encode($data));
    				}
    				else
    				{
    					$this->output->set_output(json_encode(
    									array(
    										'status' => FALSE, 
    										'message' => 'Failed')
    									));
    				}
                }
                else
                {
                    $this->output->set_output(json_encode(
                                        array(
                                            'status' => FALSE, 
                                            'message' => 'Request Type Required')
                                        ));
                }
			}
		}
		else{
			$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
		}
	}	


	public function get_schools_list_by_request_type()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
				if(preg_match("/PANACEA/i", $user_type))
				{
				if(isset($_POST['request_type'])){
					if(isset($_POST['count']) && isset($_POST['today_date']))
					{
						$count = $_POST['count'];
						$today_date = $_POST['today_date'];
					}else
					{
						$count = false;
						$today_date = false;
					}
					$request_type = $_POST['request_type'];
					$data = $this->healthsupervisor_app_model->get_schools_list_by_request_type($request_type,$count,$today_date);
					$this->data['schools'] = $data['schools_list']['school'];
					$this->data['request_type'] = $request_type;
					if(!empty($this->data)){
						$this->output->set_output(json_encode($this->data));
					}else{
						$this->output->set_output('No Student Found for this school');
					}
				}else{
						$this->output->set_output('Required Parameter Missing');
				}
			}
		}else{
					$this->output->set_output('User type Missing');
		}
	}

	public function get_students_list_by_request_type()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
				if(preg_match("/PANACEA/i", $user_type))
				{
				if(isset($_POST['request_type'])){
					$request_type = $_POST['request_type'];
					$school_name = $_POST['school_name'];
					if(isset($_POST['count']) && isset($_POST['today_date']))
					{
						$count = $_POST['count'];	
						$today_date = $_POST['today_date'];	
					}else
					{
						$count = false;
						$today_date = false;
					}
					
					$this->data = $this->healthsupervisor_app_model->get_students_list_by_request_type($request_type,$school_name,$count,$today_date);
					
					
					if(!empty($this->data)){
						$this->output->set_output(json_encode($this->data));
					}else{
						$this->output->set_output('No Student Found for this Symptoms');
					}
				}else{
						$this->output->set_output('Required Parameter Missing');
				}
			}
		}else{
					$this->output->set_output('User type Missing');
		}
	}

	//Get Students count (HS requests all/ Cured/ Not cured)
	public function get_students_count_by_request_type()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(isset($_POST['today_date']) && isset($_POST['count']))
			{
				$count = $_POST['count'];
				$today_date = $_POST['today_date'];
			}else
			{
				$count = false;
				$today_date = false;
			}
			if(preg_match("/PANACEA/i", $user_type))
			{
				if(isset($_POST['request_type']))
				{
					$request_type = $_POST['request_type'];
					$school_name = $_POST['school_name'];
					
					$this->data = $this->healthsupervisor_app_model->get_students_count_by_request_type($request_type,$school_name,$count,$today_date);
					if(!empty($this->data)){
						$this->output->set_output(json_encode($this->data));
					}else{
						$this->output->set_output('No Student Found for this Symptoms');
					}
				}else{
						$this->output->set_output('Required Parameter Missing');
				}
			}
		}else{
					$this->output->set_output('User type Missing');
		}
	}

	public function get_hs_request_student_ehr()
    {
		$post = $_POST;
		$user_type = $_POST['user_type'];
		$request_type = $_POST['request_type'];
		$request_status = $_POST['request_status'];
		$this->data = $this->healthsupervisor_app_model->get_hs_request_student_ehr($post, $user_type, $request_type, $request_status);
		$this->output->set_output(json_encode($this->data));
    
    }

    public function get_request_and_screening_documents()
    {
    	$unique_id = $_POST['unique_id'];
    	$date_time = $_POST['date_time'];
    	$document = $this->healthsupervisor_app_model->get_request_and_screening_documents($unique_id,$date_time);
    	if(!empty($document))
    	{
    		$this->output->set_output(json_encode($document));
    	}
    	else
    	{
    		$this->output->set_output(json_encode(array('Status' =>FALSE,'Message' => "No Data Found")));
    	}
    }

    /*  
		This is for Rhso and field officer form submission purpose
		when they visitedd to hospital
		attaching request docid with rhso and fo followp for data connectivity
    */

	    public function submit_rhso_student_followup()
	    {
	    	$user_type = $_POST['user_type'];
	    	$post = $_POST;
	    	if($user_type == "TSWREIS_FO")
	    	{
	    		$controller = 'healthcare2016531124515424_con';
	    		$this->submit_rhso_reports($controller, $post);
	    	}
	    	/*else if($user_type == "TTWREIS_FO")
	    	{
	    		$controller = 'healthcare2016108181933756_con';
	    		$this->submit_field_officer_reports($controller, $post);
	    	}else if($user_type == "BCWELFARE_FO")
	    	{
	    		$controller = 'healthcare2018122191146894_con';
	    		$this->submit_field_officer_reports($controller, $post);
	    	}else if($user_type == "TMREIS_FO")
	    	{
	    		$controller = 'healthcare201610114435690_con';
	    		$this->submit_field_officer_reports($controller, $post);
	    	}*/
	    }

	    public function submit_rhso_reports($controller, $post)
	    {
	    	log_message('error','submit_field_officer========5467'.print_r($post, true));
	    	$doc_data = array();
			$widget_data = array();
			$doc_attachments =array();
			$user_type = $post['user_type'];
			$unique_id = $post['page1_StudentDetails_HospitalUniqueID'];
			$student_name = $post['student_name'];
			//$district = $post['page1_AttendenceDetails_District',TRUE);
			//$school_name  = $post['student_name',TRUE);
			$class  = $post['student_class'];
			$section  = $post['student_section'];
			$father_name  = $post['student_fathername'];
			$mobile_number  = $post['mobile_number'];
			$case_type  = $post['type_of_request'];

			//out patient details
			$op_doctor_name  = $post['op_doctor_name'];
			$op_hospital_name     = $post['op_hospital_name'];
			$op_patient_details      = $post['op_patient_details'];
			$op_investigation      = $post['op_investigation'];
			$op_review_date       = $post['op_review_date'];
			$op_meditation      = $post['op_meditation'];

			//emergeny or admittted 
			$admitted_doctor_name  = $post['admitted_doctor_name'];
			$admitted_hospital_name     = $post['admitted_hospital_name'];
			$admitted_patient_details      = $post['admitted_patient_details'];
			$admitted_investigation      = $post['admitted_investigation'];
			$admitted_review_date       = $post['admitted_review_date'];
			$admitted_meditation      = $post['admitted_meditation'];
			$admitted_doctor_advice		= $post['doctor_advice'];

			//review
			$review_doctor_name  = $post['review_doctor_name'];
			$review_hospital_name     = $post['review_hospital_name'];
			$review_patient_details      = $post['review_patient_details'];
			$review_investigation      = $post['review_investigation'];
			$review_review_date       = $post['review_review_date'];
			$review_meditation      = $post['review_meditation'];
			$review_caseclose      = $post['review_caseclose'];

			//Followup request doc ID
			if(isset($post['follow_up_request_doc_id']) && !empty($post['follow_up_request_doc_id'])){
				$follow_up_request_doc_id = $post['follow_up_request_doc_id']; 
			}else{
				$follow_up_request_doc_id = ''; 
			}
					

			//$op_doctor_name      = $this->input->post('op_doctor_name',true);
			//submit to the database 
			$doc_data['widget_data']['Student Details']['Hospital Unique ID'] = $unique_id;
			$doc_data['widget_data']['Student Details']['Name'] = $student_name;
			//$doc_data['widget_data']['Student Details']['District'] = $district;
			//$doc_data['widget_data']['Student Details']['School Name'] = $school_name;
			$doc_data['widget_data']['Student Details']['Class'] =  $class;
			$doc_data['widget_data']['Student Details']['Section'] =  $section;
			$doc_data['widget_data']['Student Details']['Father Name'] =  $father_name;
			$doc_data['widget_data']['Student Details']['mobile_number'] =  $mobile_number;
			$doc_data['widget_data']['type_of_request'] =  $case_type;

			///op patient		
			$doc_data['widget_data']['Out Patient']['doctor_name'] =  $op_doctor_name;
			$doc_data['widget_data']['Out Patient']['hospialt_name'] =  $op_hospital_name;
			$doc_data['widget_data']['Out Patient']['patient_details'] =  $op_patient_details;
			$doc_data['widget_data']['Out Patient']['investigations'] =  $op_investigation;
			$doc_data['widget_data']['Out Patient']['review_date'] =  $op_review_date;
			$doc_data['widget_data']['Out Patient']['medication'] =  $op_meditation;		

			$doc_data['widget_data']['Emergency or Admitted']['doctor_name'] =  $admitted_doctor_name;
			$doc_data['widget_data']['Emergency or Admitted']['hospialt_name'] =  $admitted_hospital_name;
			$doc_data['widget_data']['Emergency or Admitted']['patient_details'] =  $admitted_patient_details;
			$doc_data['widget_data']['Emergency or Admitted']['investigations'] =  $admitted_investigation;
			$doc_data['widget_data']['Emergency or Admitted']['Doctor Advice'] =  (!empty($admitted_doctor_advice)) ? $admitted_doctor_advice : "";
			$doc_data['widget_data']['Emergency or Admitted']['medication'] =  $admitted_meditation;
			$doc_data['widget_data']['Emergency or Admitted']['review_date'] =  $admitted_review_date;

			$doc_data['widget_data']['Review Cases']['doctor_name'] =  $review_doctor_name;
			$doc_data['widget_data']['Review Cases']['hospialt_name'] =  $review_hospital_name;
			$doc_data['widget_data']['Review Cases']['patient_details'] =  $review_patient_details;
			$doc_data['widget_data']['Review Cases']['investigations'] =  $review_investigation;
			$doc_data['widget_data']['Review Cases']['medication'] =  $review_meditation;
			$doc_data['widget_data']['Review Cases']['review_date'] =  $review_review_date;
			$doc_data['widget_data']['Review Cases']['review_caseclose'] =  $review_caseclose;

			if(isset($_FILES) && !empty($_FILES))
		    {
	        	$this->load->library('upload');
	       		$this->load->library('image_lib');

		        $Prescriptions_external_files_upload_info 	= array();
		        $Lab_Reports_external_files_upload_info 	= array();
		        $Digital_Images_external_files_upload_info 	= array();
		        $Payments_Bills_upload_info 				= array();
		        $Discharge_Summary_upload_info 				= array();
		      	$external_attachments_upload_info 			= array();

		        $Prescriptions_external_final          		= array();
		        $Lab_Reports_external_final    				= array();
		        $Digital_external_final    					= array();
		        $Payments_Bills_external_final            	= array();
		        $Discharge_Summary_external_final          	= array();
		        $hs_req_attachments_external_final          = array();
		        $external_final            					= array();       
	        
		       foreach ($_FILES as $index => $value)
		       {
		            $files = $_FILES;
		            if(strpos($index,'Prescriptions')!== false)
					{
	                if(!empty($value['name']))
	                {
	                	$cpt = count($_FILES['Prescriptions']['name']);
	                	for($i=0; $i<$cpt; $i++)
	               		{
	                     	$_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
		                    $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
		                    $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
		                    $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
		                    $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];            
	              			$this->upload->initialize($this->Prescriptions_attachment_upload_options($controller,$index));

			                if ( ! $this->upload->do_upload($index))
			                {
			                     echo "external file upload failed";
			                    // return FALSE;
			                }
				            else
				            {
		                   		$Prescriptions_external_files_upload_info = $this->upload->data();	                
		                   		$hs_external_data_array = array(
		                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
		                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
		                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
		                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
		                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
		                                            )
		                                        );
		                  		$Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
		            		}
	               		}
	                }
	            }
	            if(strpos($index,'Lab_Reports')!== false)
				{
	                if(!empty($value['name']))
	                {
	                $cpt = count($_FILES['Lab_Reports']['name']);
	                for($i=0; $i<$cpt; $i++)
	                {
	                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
	                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
	                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
	                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
	                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
	            
	              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options($controller,$index));

	                if ( ! $this->upload->do_upload($index))
	                {
	                     echo "external file upload failed";
	                    // return FALSE;
	                }
	            else
	            {
	                    $Lab_Reports_external_files_upload_info = $this->upload->data();
	                
	                    $hs_external_data_array = array(
	                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
	                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
	                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
	                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
	                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
	                                            )

	                                        );

	                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
	            }
	                }
	                }
	            }
	        
	     		if(strpos($index,'Digital_Images')!== false)
				{
	                if(!empty($value['name']))
	                {
	                $cpt = count($_FILES['Digital_Images']['name']);
	                for($i=0; $i<$cpt; $i++)
	                {
	                     $_FILES['Digital_Images']['name']  = $files['Digital_Images']['name'][$i];
	                     $_FILES['Digital_Images']['type']  = $files['Digital_Images']['type'][$i];
	                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
	                     $_FILES['Digital_Images']['error'] = $files['Digital_Images']['error'][$i];
	                     $_FILES['Digital_Images']['size']  = $files['Digital_Images']['size'][$i];
	            
	              		$this->upload->initialize($this->Digital_Images_attachment_upload_options($controller,$index));

	                if ( ! $this->upload->do_upload($index))
	                {
	                     echo "external file upload failed";
	                    // return FALSE;
	                }
	            else
	            {
	                    $Digital_Images_external_files_upload_info = $this->upload->data();
	                
	                    $hs_external_data_array = array(
	                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
	                                            "file_client_name" =>$Digital_Images_external_files_upload_info['client_name'],
	                                            "file_encrypted_name" =>$Digital_Images_external_files_upload_info['file_name'],
	                                            "file_path" =>$Digital_Images_external_files_upload_info['file_relative_path'],
	                                            "file_size" =>$Digital_Images_external_files_upload_info['file_size']
	                                            )

	                                        );

	                    $Digital_external_final = array_merge($Digital_external_final,$hs_external_data_array);
	            }
	                }
	                }
	            }
	       
	       	if(strpos($index,'Payments_Bills')!== false)
			 {
	             if(!empty($value['name']))
	            {
	            $bill = count($_FILES['Payments_Bills']['name']);
	            for($i=0; $i<$bill; $i++)
	            {
	                
	                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
	                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
	                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
	                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
	                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
	                 
	                $this->upload->initialize($this->Payments_Bills_upload_options($controller,$index));

	                if ( ! $this->upload->do_upload($index))
	                {
	                     echo "Payments/Bills  upload failed";
	                     //return FALSE;
	                }
	                else
	                {   
	        
	                    $Payments_Bills_upload_info = $this->upload->data();
	                    
	                    $kitchen_data_array = array(
	                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
	                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
	                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
	                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
	                                            "file_size" =>$Payments_Bills_upload_info['file_size']
	                                                            )

	                                             );

	                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
	            
	                }
	            }
	            }
	        }

	        	if(strpos($index,'Discharge_Summary')!== false)
			 {
	             if(!empty($value['name']))
	            {
	            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
	            for($i=0; $i<$discharge_summary_count; $i++)
	            {
	                
	                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
	                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
	                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
	                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
	                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
	                 
	                $this->upload->initialize($this->Discharge_Summary_upload_options($controller,$index));

	                if ( ! $this->upload->do_upload($index))
	                {
	                     echo "dormitory upload failed";
	                     //return FALSE;
	                }
	                else
	                {   
	        
	                    $Discharge_Summary_upload_info = $this->upload->data();
	                    
	                    $dormitory_data_array = array(
	                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
	                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
	                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
	                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
	                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
	                                                            )

	                                             );

	                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
	            
	                }
	            }
	            }
	        }
	        if(strpos($index,'hs_req_attachments')!== false)
			{
	            if(!empty($value['name']))
	            {
	            $hs_req_attachments_count = count($_FILES['hs_req_attachments']['name']);
	            for($i=0; $i<$hs_req_attachments_count; $i++)
	            {
	                
	                 $_FILES['hs_req_attachments']['name']    = $files['hs_req_attachments']['name'][$i];
	                 $_FILES['hs_req_attachments']['type']    = $files['hs_req_attachments']['type'][$i];
	                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
	                 $_FILES['hs_req_attachments']['error']   = $files['hs_req_attachments']['error'][$i];
	                 $_FILES['hs_req_attachments']['size']    = $files['hs_req_attachments']['size'][$i];
	                 
	                $this->upload->initialize($this->external_attachments_upload_options($controller,$index));

					                if ( ! $this->upload->do_upload($index))
					                {
					                     echo "external old attachments upload failed";
					                     //return FALSE;
					                }
					                else
					                {   
					        
					                    $external_attachments_upload_info = $this->upload->data();
					                    
					                    $external_attachments_old_data_array = array(
					                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
					                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
					                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
					                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
					                                            "file_size" =>$external_attachments_upload_info['file_size']
					                                                            )

					                                             );

					                    $external_final = array_merge($external_final,$external_attachments_old_data_array);
					            
					                }
	            			}
	            		}
	        		}
	         
	       		}

		       	if(isset($doc_attachments['Prescriptions']))
		        {
		           $prescription_merged_data = array_merge($doc_attachments['Prescriptions'],$Prescriptions_external_final);
		           $doc_attachments['Prescriptions'] = array_replace_recursive($doc_attachments['Prescriptions'],$prescription_merged_data);
		        }
		        else
		        {
		            $doc_attachments['Prescriptions'] = $Prescriptions_external_final;
		                
		        } 

		         if(isset($doc_attachments['Lab_Reports']))
		        {	               
		            $lab_reports_merged_data = array_merge($doc_attachments['Lab_Reports'],$Lab_Reports_external_final);
		            $doc_attachments['Lab_Reports'] = array_replace_recursive($doc_attachments['Lab_Reports'],$lab_reports_merged_data);     
		        }
		        else
		        {
		            $doc_attachments['Lab_Reports'] = $Lab_Reports_external_final;	                
		        }        
		        
		        if(isset($doc_attachments['Digital_Images']))
		        {
		            $digital_images_merged_data = array_merge($doc_attachments['Digital_Images'],$Digital_external_final);
		            $doc_attachments['Digital_Images'] = array_replace_recursive($doc_attachments['Digital_Images'],$digital_images_merged_data); 
		        }
		        else
		        {
		            $doc_attachments['Digital_Images'] = $Digital_external_final;
		        }
		        
		        if(isset($doc_attachments['Payments_Bills']))
		        {
		            $kitchen_merged_data = array_merge($doc_attachments['Payments_Bills'],$Payments_Bills_external_final);
		            $doc_attachments['Payments_Bills'] = array_replace_recursive($doc_attachments['Payments_Bills'],$kitchen_merged_data);
		        }
		        else
		        {
		            $doc_attachments['Payments_Bills'] = $Payments_Bills_external_final;
		        }

		         if(isset($doc_attachments['Discharge_Summary']))
		        {
		            $dormitory_merged_data = array_merge($doc_attachments['Discharge_Summary'],$Discharge_Summary_external_final);
		            $doc_attachments['Discharge_Summary'] = array_replace_recursive($doc_attachments['Discharge_Summary'],$dormitory_merged_data);
		        }
		        else
		        {
		            $doc_attachments['Discharge_Summary'] = $Discharge_Summary_external_final;
		        }

		         if(isset($doc_attachments['external_attachments']))
		        {
		        	$external_merged_data = array_merge($doc_attachments['external_attachments'],$external_final);
		            $doc_attachments['external_attachments'] = array_replace_recursive($doc_attachments['external_attachments'],$external_merged_data);
		        }
		        else
		        {
		            $doc_attachments['external_attachments'] = $external_final;
		        }
	    	}		   
	    		$doc_properties['doc_id'] = get_unique_id();
	    		$doc_properties['followup_request_doc_id'] = $follow_up_request_doc_id;
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";

				$app_properties['app_name'] = "TSWREIS Field Officer report App";
				$app_properties['app_id'] = "Doctor Visiting";

				//$session_data = $this->session->userdata("customer");
				$email_id = $post['email'];

				$email = str_replace("@","#",$email_id);
				$approval_data = array(
					"current_stage" => "stage1",
					"approval" => "true",
					"submitted_by" => $email,
					"time" => date('Y-m-d H:i:s'));
	 
				$history['last_stage'] = $approval_data;

				$newly_created = $this->healthsupervisor_app_model->submit_rhso_follow_up_form($doc_data,$doc_attachments, $doc_properties, $app_properties, $history,$user_type);
				 
				 /*$message = "Hi Dear Sir/Mam, Type Of Request ".$case_type." with UID ".$unique_id." Name ".$student_name." class".$class." Raised On".date('Y-m-d');		  	   
			  	  $this->bhashsms->send_sms($hs_mob,$message);*/
				if(!empty($newly_created))
				{
					$this->output->set_output(json_encode(
										array(
											'status' => TRUE, 
											'message' => 'Inserted successfully')
										));
				}else
				{
					$this->output->set_output(json_encode(array('status' => FALSE,
						'message' => "Not submitted!")));
				}
	    }

    public function submit_field_officer()
    {
    	$user_type = $_POST['user_type'];
    	$post = $_POST;
    	if($user_type == "TSWREIS_FO")
    	{
    		$controller = 'healthcare2016531124515424_con';
    		$this->submit_field_officer_reports($controller, $post);
    	}else if($user_type == "TTWREIS_FO")
    	{
    		$controller = 'healthcare2016108181933756_con';
    		$this->submit_field_officer_reports($controller, $post);
    	}else if($user_type == "BCWELFARE_FO")
    	{
    		$controller = 'healthcare2018122191146894_con';
    		$this->submit_field_officer_reports($controller, $post);
    	}else if($user_type == "TMREIS_FO")
    	{
    		$controller = 'healthcare201610114435690_con';
    		$this->submit_field_officer_reports($controller, $post);
    	}
    }

    public function submit_field_officer_reports($controller, $post)
    {
    	log_message('error','submit_field_officer========5467'.print_r($post, true));
    	$doc_data = array();
		$widget_data = array();
		$doc_attachments =array();
		$user_type = $post['user_type'];
		$unique_id = $post['page1_StudentDetails_HospitalUniqueID'];
		$student_name = $post['student_name'];
		//$district = $post['page1_AttendenceDetails_District',TRUE);
		//$school_name  = $post['student_name',TRUE);
		$class  = $post['student_class'];
		$section  = $post['student_section'];
		$father_name  = $post['student_fathername'];
		$mobile_number  = $post['mobile_number'];
		$case_type  = $post['type_of_request'];

		//out patient details
		$op_doctor_name  = $post['op_doctor_name'];
		$op_hospital_name     = $post['op_hospital_name'];
		$op_patient_details      = $post['op_patient_details'];
		$op_investigation      = $post['op_investigation'];
		$op_review_date       = $post['op_review_date'];
		$op_meditation      = $post['op_meditation'];

		//emergeny or admittted 
		$admitted_doctor_name  = $post['admitted_doctor_name'];
		$admitted_hospital_name     = $post['admitted_hospital_name'];
		$admitted_patient_details      = $post['admitted_patient_details'];
		$admitted_investigation      = $post['admitted_investigation'];
		$admitted_review_date       = $post['admitted_review_date'];
		$admitted_meditation      = $post['admitted_meditation'];
		$admitted_doctor_advice		= $post['doctor_advice'];

		//review
		$review_doctor_name  = $post['review_doctor_name'];
		$review_hospital_name     = $post['review_hospital_name'];
		$review_patient_details      = $post['review_patient_details'];
		$review_investigation      = $post['review_investigation'];
		$review_review_date       = $post['review_review_date'];
		$review_meditation      = $post['review_meditation'];
		$review_caseclose      = $post['review_caseclose'];    		

		//$op_doctor_name      = $this->input->post('op_doctor_name',true);
		//submit to the database 
		$doc_data['widget_data']['Student Details']['Hospital Unique ID'] = $unique_id;
		$doc_data['widget_data']['Student Details']['Name'] = $student_name;
		//$doc_data['widget_data']['Student Details']['District'] = $district;
		//$doc_data['widget_data']['Student Details']['School Name'] = $school_name;
		$doc_data['widget_data']['Student Details']['Class'] =  $class;
		$doc_data['widget_data']['Student Details']['Section'] =  $section;
		$doc_data['widget_data']['Student Details']['Father Name'] =  $father_name;
		$doc_data['widget_data']['Student Details']['mobile_number'] =  $mobile_number;
		$doc_data['widget_data']['type_of_request'] =  $case_type;

		///op patient		
		$doc_data['widget_data']['Out Patient']['doctor_name'] =  $op_doctor_name;
		$doc_data['widget_data']['Out Patient']['hospialt_name'] =  $op_hospital_name;
		$doc_data['widget_data']['Out Patient']['patient_details'] =  $op_patient_details;
		$doc_data['widget_data']['Out Patient']['investigations'] =  $op_investigation;
		$doc_data['widget_data']['Out Patient']['review_date'] =  $op_review_date;
		$doc_data['widget_data']['Out Patient']['medication'] =  $op_meditation;		

		$doc_data['widget_data']['Emergency or Admitted']['doctor_name'] =  $admitted_doctor_name;
		$doc_data['widget_data']['Emergency or Admitted']['hospialt_name'] =  $admitted_hospital_name;
		$doc_data['widget_data']['Emergency or Admitted']['patient_details'] =  $admitted_patient_details;
		$doc_data['widget_data']['Emergency or Admitted']['investigations'] =  $admitted_investigation;
		$doc_data['widget_data']['Emergency or Admitted']['Doctor Advice'] =  (!empty($admitted_doctor_advice)) ? $admitted_doctor_advice : "";
		$doc_data['widget_data']['Emergency or Admitted']['medication'] =  $admitted_meditation;
		$doc_data['widget_data']['Emergency or Admitted']['review_date'] =  $admitted_review_date;

		$doc_data['widget_data']['Review Cases']['doctor_name'] =  $review_doctor_name;
		$doc_data['widget_data']['Review Cases']['hospialt_name'] =  $review_hospital_name;
		$doc_data['widget_data']['Review Cases']['patient_details'] =  $review_patient_details;
		$doc_data['widget_data']['Review Cases']['investigations'] =  $review_investigation;
		$doc_data['widget_data']['Review Cases']['medication'] =  $review_meditation;
		$doc_data['widget_data']['Review Cases']['review_date'] =  $review_review_date;
		$doc_data['widget_data']['Review Cases']['review_caseclose'] =  $review_caseclose;

		if(isset($_FILES) && !empty($_FILES))
	    {
        	$this->load->library('upload');
       		$this->load->library('image_lib');

	        $Prescriptions_external_files_upload_info 	= array();
	        $Lab_Reports_external_files_upload_info 	= array();
	        $Digital_Images_external_files_upload_info 	= array();
	        $Payments_Bills_upload_info 				= array();
	        $Discharge_Summary_upload_info 				= array();
	      	$external_attachments_upload_info 			= array();

	        $Prescriptions_external_final          		= array();
	        $Lab_Reports_external_final    				= array();
	        $Digital_external_final    					= array();
	        $Payments_Bills_external_final            	= array();
	        $Discharge_Summary_external_final          	= array();
	        $hs_req_attachments_external_final          = array();
	        $external_final            					= array();       
        
	       foreach ($_FILES as $index => $value)
	       {
	            $files = $_FILES;
	            if(strpos($index,'Prescriptions')!== false)
				{
                if(!empty($value['name']))
                {
                	$cpt = count($_FILES['Prescriptions']['name']);
                	for($i=0; $i<$cpt; $i++)
               		{
                     	$_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
	                    $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
	                    $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
	                    $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
	                    $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];            
              			$this->upload->initialize($this->Prescriptions_attachment_upload_options($controller,$index));

		                if ( ! $this->upload->do_upload($index))
		                {
		                     echo "external file upload failed";
		                    // return FALSE;
		                }
			            else
			            {
	                   		$Prescriptions_external_files_upload_info = $this->upload->data();	                
	                   		$hs_external_data_array = array(
	                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
	                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
	                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
	                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
	                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
	                                            )
	                                        );
	                  		$Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
	            		}
               		}
                }
            }
            if(strpos($index,'Lab_Reports')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Lab_Reports']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options($controller,$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Lab_Reports_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
                                            )

                                        );

                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'Digital_Images')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Digital_Images']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Digital_Images']['name']  = $files['Digital_Images']['name'][$i];
                     $_FILES['Digital_Images']['type']  = $files['Digital_Images']['type'][$i];
                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
                     $_FILES['Digital_Images']['error'] = $files['Digital_Images']['error'][$i];
                     $_FILES['Digital_Images']['size']  = $files['Digital_Images']['size'][$i];
            
              		$this->upload->initialize($this->Digital_Images_attachment_upload_options($controller,$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Digital_Images_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Digital_Images_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Digital_Images_external_files_upload_info['file_name'],
                                            "file_path" =>$Digital_Images_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Digital_Images_external_files_upload_info['file_size']
                                            )

                                        );

                    $Digital_external_final = array_merge($Digital_external_final,$hs_external_data_array);
            }
                }
                }
            }
       
       	if(strpos($index,'Payments_Bills')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['Payments_Bills']['name']);
            for($i=0; $i<$bill; $i++)
            {
                
                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
                 
                $this->upload->initialize($this->Payments_Bills_upload_options($controller,$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Payments/Bills  upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Payments_Bills_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
                                            "file_size" =>$Payments_Bills_upload_info['file_size']
                                                            )

                                             );

                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'Discharge_Summary')!== false)
		 {
             if(!empty($value['name']))
            {
            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
            for($i=0; $i<$discharge_summary_count; $i++)
            {
                
                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
                 
                $this->upload->initialize($this->Discharge_Summary_upload_options($controller,$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Discharge_Summary_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
                                                            )

                                             );

                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
            
                }
            }
            }
        }
        if(strpos($index,'hs_req_attachments')!== false)
		{
            if(!empty($value['name']))
            {
            $hs_req_attachments_count = count($_FILES['hs_req_attachments']['name']);
            for($i=0; $i<$hs_req_attachments_count; $i++)
            {
                
                 $_FILES['hs_req_attachments']['name']    = $files['hs_req_attachments']['name'][$i];
                 $_FILES['hs_req_attachments']['type']    = $files['hs_req_attachments']['type'][$i];
                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                 $_FILES['hs_req_attachments']['error']   = $files['hs_req_attachments']['error'][$i];
                 $_FILES['hs_req_attachments']['size']    = $files['hs_req_attachments']['size'][$i];
                 
                $this->upload->initialize($this->external_attachments_upload_options($controller,$index));

				                if ( ! $this->upload->do_upload($index))
				                {
				                     echo "external old attachments upload failed";
				                     //return FALSE;
				                }
				                else
				                {   
				        
				                    $external_attachments_upload_info = $this->upload->data();
				                    
				                    $external_attachments_old_data_array = array(
				                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
				                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
				                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
				                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
				                                            "file_size" =>$external_attachments_upload_info['file_size']
				                                                            )

				                                             );

				                    $external_final = array_merge($external_final,$external_attachments_old_data_array);
				            
				                }
            			}
            		}
        		}
         
       		}

	       	if(isset($doc_attachments['Prescriptions']))
	        {
	           $prescription_merged_data = array_merge($doc_attachments['Prescriptions'],$Prescriptions_external_final);
	           $doc_attachments['Prescriptions'] = array_replace_recursive($doc_attachments['Prescriptions'],$prescription_merged_data);
	        }
	        else
	        {
	            $doc_attachments['Prescriptions'] = $Prescriptions_external_final;
	                
	        } 

	         if(isset($doc_attachments['Lab_Reports']))
	        {	               
	            $lab_reports_merged_data = array_merge($doc_attachments['Lab_Reports'],$Lab_Reports_external_final);
	            $doc_attachments['Lab_Reports'] = array_replace_recursive($doc_attachments['Lab_Reports'],$lab_reports_merged_data);     
	        }
	        else
	        {
	            $doc_attachments['Lab_Reports'] = $Lab_Reports_external_final;	                
	        }        
	        
	        if(isset($doc_attachments['Digital_Images']))
	        {
	            $digital_images_merged_data = array_merge($doc_attachments['Digital_Images'],$Digital_external_final);
	            $doc_attachments['Digital_Images'] = array_replace_recursive($doc_attachments['Digital_Images'],$digital_images_merged_data); 
	        }
	        else
	        {
	            $doc_attachments['Digital_Images'] = $Digital_external_final;
	        }
	        
	        if(isset($doc_attachments['Payments_Bills']))
	        {
	            $kitchen_merged_data = array_merge($doc_attachments['Payments_Bills'],$Payments_Bills_external_final);
	            $doc_attachments['Payments_Bills'] = array_replace_recursive($doc_attachments['Payments_Bills'],$kitchen_merged_data);
	        }
	        else
	        {
	            $doc_attachments['Payments_Bills'] = $Payments_Bills_external_final;
	        }

	         if(isset($doc_attachments['Discharge_Summary']))
	        {
	            $dormitory_merged_data = array_merge($doc_attachments['Discharge_Summary'],$Discharge_Summary_external_final);
	            $doc_attachments['Discharge_Summary'] = array_replace_recursive($doc_attachments['Discharge_Summary'],$dormitory_merged_data);
	        }
	        else
	        {
	            $doc_attachments['Discharge_Summary'] = $Discharge_Summary_external_final;
	        }

	         if(isset($doc_attachments['external_attachments']))
	        {
	        	$external_merged_data = array_merge($doc_attachments['external_attachments'],$external_final);
	            $doc_attachments['external_attachments'] = array_replace_recursive($doc_attachments['external_attachments'],$external_merged_data);
	        }
	        else
	        {
	            $doc_attachments['external_attachments'] = $external_final;
	        }
    	}		   
    		$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";

			$app_properties['app_name'] = "TSWREIS Field Officer report App";
			$app_properties['app_id'] = "Doctor Visiting";

			//$session_data = $this->session->userdata("customer");
			$email_id = $post['email'];

			$email = str_replace("@","#",$email_id);
			$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $email,
				"time" => date('Y-m-d H:i:s'));
 
			$history['last_stage'] = $approval_data;

			$newly_created = $this->healthsupervisor_app_model->submit_field_officer($doc_data,$doc_attachments, $doc_properties, $app_properties, $history,$user_type);
			 
			 /*$message = "Hi Dear Sir/Mam, Type Of Request ".$case_type." with UID ".$unique_id." Name ".$student_name." class".$class." Raised On".date('Y-m-d');		  	   
		  	  $this->bhashsms->send_sms($hs_mob,$message);*/
			if(!empty($newly_created))
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Inserted successfully')
									));
			}else
			{
				$this->output->set_output(json_encode(array('status' => FALSE,
					'message' => "Not submitted!")));
			}
    }
    ///submit the attachments
	private function Prescriptions_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Prescriptions')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			//$config['max_size']      = '*';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}


		return $config;
	}
	private function Lab_Reports_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Lab_Reports')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function Digital_Images_attachment_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Digital_Images')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	
	private function Payments_Bills_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Payments_Bills')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	private function Discharge_Summary_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'Discharge_Summary')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}
	private function external_attachments_upload_options($controller,$field)
	{
		$config = array();

		if (strpos($field,'hs_req_attachments')!== false)
		{
			$config = array();
			$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/files/external_files/';
			$config['allowed_types'] = '*';
			$config['max_size']      = '4096';
			$config['encrypt_name']  = TRUE;		
		}
			//create controller upload folder if not exists
			if (!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR."public/uploads/$controller/files/external_files/",0777,TRUE);
			}
		return $config;
	}

	public function fetch_field_officer_reports()
	{
		$today_date = $_POST['today_date'];
		$user_type = $_POST['user_type'];
		//$loggedinuser = $_POST['logged_in_user'];
		$mail = $_POST['email'];

		if($user_type == "TSWREIS_FO") 
		{
			$reports = $this->healthsupervisor_app_model->fetch_field_officer_reports($today_date, $user_type, $mail);
			if(!empty($reports))
			{
				$this->output->set_output(json_encode($reports));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}	
		}else if($user_type == "TTWREIS_FO")
		{
			$reports = $this->healthsupervisor_app_model->fetch_field_officer_reports($today_date, $user_type, $mail);
			if(!empty($reports))
			{
				$this->output->set_output(json_encode($reports));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}
		}else if($user_type == "BCWELFARE_FO")
		{
			$reports = $this->healthsupervisor_app_model->fetch_field_officer_reports($today_date, $user_type, $mail);
			if(!empty($reports))
			{
				$this->output->set_output(json_encode($reports));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}
		}
		
	}

	public function drill_down_to_field_officer_reports()
	{
		$selectedCase = $_POST['selected_case'];
		$selectedDate = $_POST['selected_date'];
		$user_type = $_POST['user_type'];
		$mail = $_POST['email'];
		
		if($user_type == "TSWREIS_FO")
		{
			$this->data['student_list'] = $this->healthsupervisor_app_model->drill_down_to_field_officer_reports_list($selectedCase, $selectedDate, $user_type, $mail);
			$this->data['selected_case'] = $selectedCase;
			$this->data['selected_date'] = $selectedDate;
			
			if($this->data['student_list'])
			{
				$this->output->set_output(json_encode($this->data));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}
		}else if($user_type == "TTWREIS_FO")
		{
			$this->data['student_list'] = $this->healthsupervisor_app_model->drill_down_to_field_officer_reports_list($selectedCase, $selectedDate, $user_type, $mail);
			$this->data['selected_case'] = $selectedCase;
			$this->data['selected_date'] = $selectedDate;
			
			if($this->data['student_list'])
			{
				$this->output->set_output(json_encode($this->data));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}
		}else if($user_type == "BCWELFARE_FO")
		{
			$this->data['student_list'] = $this->healthsupervisor_app_model->drill_down_to_field_officer_reports_list($selectedCase, $selectedDate, $user_type, $mail);
			$this->data['selected_case'] = $selectedCase;
			$this->data['selected_date'] = $selectedDate;
			
			if($this->data['student_list'])
			{
				$this->output->set_output(json_encode($this->data));
			}else
			{
				$this->output->set_output(json_encode(array('status' => 'NO_DATA_AVAILABLE')));
			}
		}		  	
	}

	public function get_schools_by_bmi_range()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(preg_match("/PANACEA/i", $user_type))
			{
				$bmi_type = $_POST['bmi_type'];
				$today_date = $_POST['today_date'];

				$this->data = $this->healthsupervisor_app_model->get_schools_by_bmi_range($bmi_type,$today_date);
				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}
				else
				{
					$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed')
									));
				}
			}
		}
		else{
			$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
		}
	}
	public function get_students_list_by_bmi_range()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(preg_match("/PANACEA/i", $user_type))
			{
				$bmi_type = $_POST['bmi_type'];

				if(isset($_POST['school_name']) && !empty($_POST['school_name']))
				{
					$school_name = $_POST['school_name'];	
				}else
				{
					$school_name = false;
				}
				
				$today_date = $_POST['today_date'];
				$this->data = $this->healthsupervisor_app_model->get_students_list_by_bmi_range($bmi_type, $school_name, $today_date);
				if(!empty($this->data))
				{
					$this->output->set_output(json_encode($this->data));
				}
				else
				{
					$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Failed')
									));
				}
			}
		}
		else{
			$this->output->set_output(json_encode(
										array(
											'status' => FALSE, 
											'message' => 'User Type Required')
										));
		}
	}

    /**************************************************/
    /**
     * PANACEA Schools
     * 
     */
    public function get_schools_by_hb_range()
    {
        if(!empty( $_POST['user_type'] ))
        {
            $user_type = $_POST['user_type'];
            if(preg_match("/PANACEA/i", $user_type))
            {
                $hb_type = $_POST['hb_type'];
                $today_date = $_POST['today_date'];
                $this->data = $this->healthsupervisor_app_model->get_schools_by_hb_range($hb_type,$today_date);
                if(!empty($this->data))
                {
                    $this->output->set_output(json_encode($this->data));
                }
                else
                {
                    $this->output->set_output(json_encode(
                                    array(
                                        'status' => FALSE, 
                                        'message' => 'Failed')
                                    ));
                }
            }
        }
        else{
            $this->output->set_output(json_encode(
                                        array(
                                            'status' => FALSE, 
                                            'message' => 'User Type Required')
                                        ));
        }
    }
    public function get_students_list_by_hb_range()
    {
        if(!empty( $_POST['user_type'] ))
        {
            $user_type = $_POST['user_type'];
            if(preg_match("/PANACEA/i", $user_type))
            {
                $hb_type = $_POST['hb_type'];
                if(isset($_POST['school_name']) && !empty($_POST['school_name']))
				{
					$school_name = $_POST['school_name'];	
				}else
				{
					$school_name = false;
				}
                $today_date = $_POST['today_date'];
                $this->data = $this->healthsupervisor_app_model->get_students_list_by_hb_range($hb_type,$school_name,$today_date);
                if(!empty($this->data))
                {
                    $this->output->set_output(json_encode($this->data));
                }
                else
                {
                    $this->output->set_output(json_encode(
                                    array(
                                        'status' => FALSE, 
                                        'message' => 'Failed')
                                    ));
                }
            }
        }
        else{
            $this->output->set_output(json_encode(
                                        array(
                                            'status' => FALSE, 
                                            'message' => 'User Type Required')
                                        ));
        }
    }

    /**************************************************/
	/**
	 * Panacea Schools
	 * Helper:Fetching Hospitalized/ Surgery Needed/ Op Cases/ Expired Students List
	 */
	public function get_students_list_of_health_status_tracking()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
			if(preg_match("/PANACEA/i", $user_type))
			{
				$review_status = $_POST['review_status'];
				if(isset($_POST['today_date']) && !empty($_POST['today_date']))
				{
					$today_date = $_POST['today_date'];
				}else
				{
					$today_date = false;
				}
				
				$this->data = $this->healthsupervisor_app_model->get_students_list_of_health_status_tracking($review_status,$today_date);
				
				if(!empty($this->data)){
					$this->output->set_output(json_encode($this->data));
				}
				
			}
		}
	}

	public function psychologist_assessment_form()
	{
		$this->load->library('upload');
		$this->load->library('image_lib');
		$psychologist_files_upload_info = array();
	    $psychologist_final             = array();
	    $psychologist_merged_data       = array();

	    $team_coordinator_files_upload_info = array();
	    $team_coordinator_final             = array();
	    $team_coordinator_merged_data       = array();

	    $doc_data['Personal Information']['Hospital Unique ID'] = $_POST['unique_id'];
	    $doc_data['Personal Information']['Name'] = $_POST['Name'];
	    $doc_data['Personal Information']['Class'] = $_POST['Class'];
	    $doc_data['Personal Information']['Section'] = $_POST['Section'];
	    $doc_data['Personal Information']['School Name'] = $_POST['School_Name'];
		$doc_data['Psychologist Assessment']['Memory Attention'] = $_POST['memory_attention'];
		$doc_data['Psychologist Assessment']['Concentration'] = $_POST['concentration'];
		$doc_data['Psychologist Assessment']['Reading'] = $_POST['reading'];
		$doc_data['Psychologist Assessment']['Writing'] = $_POST['writing'];
		$doc_data['Psychologist Assessment']['Comprehension'] = $_POST['comprehension'];
		$doc_data['Psychologist Assessment']['Spelling'] = $_POST['spelling'];
		$doc_data['Developmental Assessment']['Delayed Speech'] = $_POST['delayed_speech'];
		$doc_data['Developmental Assessment']['Mortar Activities'] = $_POST['mortar_activities'];
		$doc_data['Developmental Assessment']['Social Skills'] = $_POST['social_skills'];
		$doc_data['Behavioral Assessment']['Short Temper'] = $_POST['short_temper'];
		$doc_data['Behavioral Assessment']['EmotionalDisturbance'] = $_POST['emotional_disturbance'];
		$doc_data['Behavioral Assessment']['Hyperactivity Fidgeting'] = $_POST['hyperactivity_fidgeting'];
		$doc_data['Behavioral Assessment']['Persistent Nightmares'] = $_POST['persistent_nightmares'];
		$doc_data['Behavioral Assessment']['Persistent Disobedience'] = $_POST['persistent_disobedience'];
		$doc_data['Behavioral Assessment']['Depression'] = $_POST['depression'];
		$doc_data['Behavioral Assessment']['Frequent Tempatantums'] = $_POST['frequent_tempatantums'];
		$doc_data['Behavioral Assessment']['Home Sickness'] = $_POST['home_sickness'];
		$doc_data['Behavioral Assessment']['IQ'] = $_POST['IQ'];
		$doc_data['Description']['Family History'] = $_POST['family_history'];
		$doc_data['Description']['Referrals'] = $_POST['referrals'];


		$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = "";
			$doc_properties['doc_flow'] = "new";

			$email_id = $this->input->post('submitted_by',TRUE);
			$email = str_replace("@","#",$email_id);
			// History
			$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $email,
				'raised_by' => "device_side",
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;


		if(isset($_FILES['psychologist_attachments']['name']) && !empty($_FILES['psychologist_attachments']['name']))
		{
	   	   $files = $_FILES;
		   $cpt = count($_FILES['psychologist_attachments']['name']);
		   
		   for($i=0; $i<$cpt; $i++)
		   {
				$_FILES['psychologist_attachments']['name']	= $files['psychologist_attachments']['name'][$i];
				$_FILES['psychologist_attachments']['type']	= $files['psychologist_attachments']['type'][$i];
				$_FILES['psychologist_attachments']['tmp_name']= $files['psychologist_attachments']['tmp_name'][$i];
				$_FILES['psychologist_attachments']['error']	= $files['psychologist_attachments']['error'][$i];
				$_FILES['psychologist_attachments']['size']	= $files['psychologist_attachments']['size'][$i];
					
			   foreach ($_FILES as $index => $value)
		       {
		       		
		       		if(!empty($value['name']) && $index == 'psychologist_attachments')
				  	{
				  		$controller = 'Psychologist_External_files';
				        $config = array();
						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/psychologist_files/';
						$config['allowed_types'] = '*';
						$config['max_size']      = '4096';
						$config['encrypt_name']  = TRUE;
					  	
				        //create controller upload folder if not exists
						if (!is_dir($config['upload_path']))
						{
							mkdir(UPLOADFOLDERDIR."public/uploads/$controller/psychologist_files/",0777,TRUE);
						}
			
						$this->upload->initialize($config);
						
						if ( ! $this->upload->do_upload($index))
						{
							 echo "psychologist file upload failed";
			        		 return FALSE;
						}
						else
						{
							$psychologist_files_upload_info = $this->upload->data();
							$rand_number = mt_rand();
							$psychologist_data_array = array(
													  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
													"file_client_name" =>$psychologist_files_upload_info['client_name'],
													"file_encrypted_name" =>$psychologist_files_upload_info['file_name'],
													"file_path" =>$psychologist_files_upload_info['file_relative_path'],
													"file_size" =>$psychologist_files_upload_info['file_size']
																	) );

							$psychologist_final = array_merge($psychologist_final,$psychologist_data_array);
							
						}  
					}
				}
			}
				 
		  	if(isset($doc_data['psychologist_attachments']))
			{
					   
				$psychologist_merged_data = array_merge($doc_data['doc_data']['psychologist_attachments'],$psychologist_final);
				$doc_data['doc_data']['psychologist_attachments'] = array_replace_recursive($doc_data['doc_data']['psychologist_attachments'],$psychologist_merged_data);
			}
			else
			{
			    $doc_data['psychologist_attachments'] = $psychologist_final;
			}
	   }else
	   {
	   		$doc_data['psychologist_attachments'] = [];
	   }
			   
	   if(isset($_FILES['team_coordinator_attachments']['name']) && !empty($_FILES['team_coordinator_attachments']['name']))
	   {
	   	   $files = $_FILES;
		   $cpt = count($_FILES['team_coordinator_attachments']['name']);
		   for($i=0; $i<$cpt; $i++)
		   {
			 $_FILES['team_coordinator_attachments']['name']	= $files['team_coordinator_attachments']['name'][$i];
			 $_FILES['team_coordinator_attachments']['type']	= $files['team_coordinator_attachments']['type'][$i];
			 $_FILES['team_coordinator_attachments']['tmp_name']= $files['team_coordinator_attachments']['tmp_name'][$i];
			 $_FILES['team_coordinator_attachments']['error']	= $files['team_coordinator_attachments']['error'][$i];
			 $_FILES['team_coordinator_attachments']['size']	= $files['team_coordinator_attachments']['size'][$i];
					
				   foreach ($_FILES as $index => $value)
			       {
			       		if(!empty($value['name']) && $index == 'team_coordinator_attachments')
					  	{
					  		$controller = 'Psychologist_External_files';
					        $config = array();
							$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/'.$controller.'/psychologist_files/';
							$config['allowed_types'] = '*';
							$config['max_size']      = '4096';
							$config['encrypt_name']  = TRUE;
					  	
					        //create controller upload folder if not exists
							if (!is_dir($config['upload_path']))
							{
								mkdir(UPLOADFOLDERDIR."public/uploads/$controller/psychologist_files/",0777,TRUE);
							}
				
							$this->upload->initialize($config);
							
							if ( ! $this->upload->do_upload($index))
							{
								 echo "external file upload failed";
				        		 return FALSE;
							}
							else
							{
								$team_coordinator_files_upload_info = $this->upload->data();
								$rand_number = mt_rand();
								$team_coordinator_data_array = array(
														  "DFF_EXTERNAL_ATTACHMENTS_".$rand_number => array(
														"file_client_name" =>$team_coordinator_files_upload_info['client_name'],
														"file_encrypted_name" =>$team_coordinator_files_upload_info['file_name'],
														"file_path" =>$team_coordinator_files_upload_info['file_relative_path'],
														"file_size" =>$team_coordinator_files_upload_info['file_size']
																		) );

								$team_coordinator_final = array_merge($team_coordinator_final,$team_coordinator_data_array);
								
							}  
						}
					}
				 }
				 
				   if(isset($doc_data['team_coordinator_attachments']))
					  {
							   
						$team_coordinator_merged_data = array_merge($doc_data['doc_data']['team_coordinator_attachments'],$team_coordinator_final);
						$doc_data['doc_data']['team_coordinator_attachments'] = array_replace_recursive($doc_data['doc_data']['team_coordinator_attachments'],$team_coordinator_merged_data);
					  }
					  else
					 {
					    $doc_data['team_coordinator_attachments'] = $team_coordinator_final;
					 }
			   }else
			   {
			   		$doc_data['team_coordinator_attachments'] = [];
			   }

		$query = $this->healthsupervisor_app_model->insert_psychologist_form_data($doc_data, $doc_properties, $history);
		if($query)
		{
			$this->output->set_output(json_encode(array('Status' => "Successfully Submitted")));
		}
	}

	public function get_psychologist_submitted_details()
	{
		$today_date = $_POST['date'];
		$query = $this->healthsupervisor_app_model->get_psychologist_submitted_details($today_date);
	}

// Atteadance report for view in app side
 public function get_date_wise_attendance_report()
  {
  	$today_date = $_POST['today_date'];
  	$dt_name = $_POST['district'];
  	
  	/*$count = 0;
  	$absent_report = $this->panacea_common_model->get_all_absent_data($today_date);
  	foreach ($absent_report as $value){
			$count = $count + intval($value['value']);
		}
	if($count > 0){
			$this->data['absent_report'] = $absent_report;

		}else{
			$this->data['absent_report'] = 1;
			
		}*/

	$this->data['absent_report_schools_list'] = $this->healthsupervisor_app_model->get_absent_pie_schools_data($today_date, $dt_name);
	//$this->data['sanitation_report_schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($today_date);
	if(!empty($this->data)){
		$this->output->set_output(json_encode($this->data));
	}else{
		$this->output->set_output(json_encode("No Data Found"));
	}		
  	
  }

  function get_requests_students_values()
	{		
		$today_date = $_POST['today_date'];
		$request_pie_span = $_POST['request_pie_span'];
		$request_pie_status = $_POST['request_pie_status'];
		$this->data = $this->panacea_common_lib->update_request_pie($today_date,$request_pie_span,$request_pie_status);

		if(!empty($this->data)){
		$this->output->set_output($this->data);
	}else{
		$this->output->set_output("No Data Found");
	}	
	
	}

	function drilldown_request_to_districts()
	{			
			if(isset($_POST['student_type']) && isset($_POST['student_age']))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$student_type = (isset($_POST["student_type"])) ? $_POST["student_type"] : "";
			$student_age = (isset($_POST["student_age"])) ? $_POST["student_age"] : "";
			
			$request_report = json_encode($this->panacea_common_model->drilldown_request_to_districts($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status,$student_type,$student_age));
			$this->output->set_output($request_report);
		}else
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_report = json_encode($this->panacea_common_model->drilldown_request_to_districts_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			
			$this->output->set_output($request_report);
		}
		
	}

	function drilling_request_to_schools()
	{		
		if(isset($_POST["request_pie_status"]))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$request_report = json_encode($this->panacea_common_model->get_drilling_request_schools($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status));
			$this->output->set_output($request_report);
		}else
		{			
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_report = json_encode($this->panacea_common_model->get_drilling_request_schools_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name));
			$this->output->set_output($request_report);
		}
		
	}

	function drill_down_request_to_students()
	{
		
		if(isset($_POST['request_pie_status']))
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$request_pie_status = (isset($_POST["request_pie_status"])) ? $_POST["request_pie_status"] : "";
			$docs = $this->panacea_common_model->get_drilling_request_students($data,$today_date,$request_pie_span,$dt_name,$school_name,$request_pie_status);
			$request_report = base64_encode(json_encode($docs));
			$this->output->set_output($request_report);
		}else
		{
			$data = $_POST['data'];
			$today_date = $_POST['today_date'];
			$request_pie_span = $_POST['request_pie_span'];
			$dt_name = $_POST["dt_name"];
			$school_name = $_POST["school_name"];
			$docs = $this->panacea_common_model->get_drilling_request_students_old_dash($data,$today_date,$request_pie_span,$dt_name,$school_name);
			$request_report = base64_encode(json_encode($docs));
			$this->output->set_output($request_report);
		}
		
	}

	function drill_down_request_to_students_load_ehr()
	{		
		if(empty($_POST['ehr_data_for_request_old_dash']))
		{
			$UI_id = json_encode(base64_encode($_POST['ehr_data_for_request']),true);
			
			$get_docs = $this->panacea_common_model->get_drilling_request_students_docs($UI_id);
			
			$navigation = $_POST['ehr_navigation_for_request'];
			$this->data['navigation'] = $navigation;
		
			$this->data['students'] = $get_docs;		
		
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
			$this->_render_page('panacea_admins/drill_down_request_to_students_load_ehr.php',$this->data);
		}else
		{
			$UI_id = json_encode(base64_encode($_POST['ehr_data_for_request_old_dash']),true);

			$get_docs = $this->panacea_common_model->get_drilling_request_students_docs_old_dash($UI_id);
		
			$this->data['students'] = $get_docs;
			$this->data['navigation'] =  "";
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
			$this->_render_page('panacea_admins/drill_down_request_to_students_load_ehr_old_dash.php',$this->data);
		}		
	}
	
	public function get_screening_pie_values()
	{
		$academic_year = $this->input->post('academic_year', true);

		if($academic_year == '2018-19 Academic Year'){
			$span = '2018-2019';
		}
		$this->data = $this->panacea_common_model->get_screening_pie_values($span);

		if(!empty($this->data))
		  {
	        $this->output->set_output(json_encode($this->data));
		  }
	}

	public function tswreis_diseases_counts_report()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$this->data['abnormality'] = $_POST['abnormality_name'];
			$this->data['academic'] = $_POST['academic_year'];
		}
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();if(!empty($this->data))

		if(!empty($this->data))
		{
	        $this->output->set_output(json_encode($this->data));
		}
	}

	public function get_schools_by_symptom()
	{		
		$symptom = $_POST['symptom_name'];
		$school_name = $_POST['school_name'];
		$dt_name = $_POST['po_name'];
		$academic_year = $_POST['academic'];
		if($dt_name != 'All'){
			$dist = $this->panacea_common_model->get_dt_name_based_on_id($dt_name);
			$po_name = $dist[0]['dt_name'];
		}else{
			$po_name = 'All';
		}

		$this->data['students_list'] = $this->panacea_common_model->get_schools_by_symptom($symptom, $academic_year, $po_name, $school_name);

		$this->data['symptom_name'] = $symptom; 
		$this->data['academic_year'] = $academic_year;
		
			if(!empty($this->data))
		  {
	        $this->output->set_output(json_encode($this->data));
		  }

	}

	public function get_students_by_symptom()
	{
		
		$symptom = $_POST['symptom_name'];
		$school = $_POST['school_name'];
		$academic_year = $_POST['academic_year'];
		
		$this->data['students_list'] = $this->panacea_common_model->get_students_by_symptom($symptom, $school, $academic_year);
		
		$this->data['symptom_name'] = $symptom; 
		$this->data['academic_year'] = $academic_year; 
		$this->data['students_count'] = count($this->data['students_list']); 

		if(!empty($this->data))
		{
	        $this->output->set_output(json_encode($this->data));
		}
	}

	public function add_doctor_profile()
    {       
      // POST DATA
      $user_type        = $_POST['user_type'];
      $doctor_name      = $this->input->post('doc_name',TRUE);
      $registraction_no = $this->input->post('rgs_no',TRUE);
      $doctor_mob       = $this->input->post('mobile',TRUE);
      $working_place    = $this->input->post('current_working_place',TRUE); 
      $specialization   = $this->input->post('doc_specialization',TRUE);
      $qualification    = $this->input->post('qualification_id',TRUE);         
      $email_id         = $this->input->post('email',TRUE);       

      //log_message('error','doctor name print'.print_r($doctor_name,true)); 
     
      // Form EHR Document
      $doc_data = array();
      $doc_data['page1']['Personal Information']  = array();         
      // Page 1
      $doc_data['page1']['Personal Information']['Name']                    = $doctor_name;
      $doc_data['page1']['Personal Information']['Mobile']                  = array("country_code"=>"91","mob_num"=>$doctor_mob);
      $doc_data['page1']['Personal Information']['Specialization']          = $qualification;
      $doc_data['page1']['Personal Information']['Qualification']           = $specialization;
      $doc_data['page1']['Personal Information']['RGD No']                  = $registraction_no;
      $doc_data['page1']['Personal Information']['Working hospital name']   = $working_place;  
      
            //$doc_data['doc_data'] = $doc_data;        
        $doc_properties['status'] = 1;
        $doc_properties['_version'] = 1;
        $doc_properties['doc_owner'] = "PANACEA";
        $doc_properties['unique_id'] = '';
        $doc_properties['doc_flow'] = "new";

        $email = str_replace("@","#",$email_id);
        // History
          $approval_data = array(            
             
              "submitted_by" => $email,
              "submitted_name" => "HS name",
              "time" => date('Y-m-d H:i:s'));
         
          $history['last_stage'] = $approval_data;    
      
    $added = $this->healthsupervisor_app_model->add_doctor_profile_model($history,$doc_properties,$doc_data,$user_type);      

    if(!empty($added))
		{
	      $this->output->set_output(json_encode($added));
		}

       $this->output->set_output(json_encode(array(
                                        'status' => TRUE, 
                                        'message' => 'Dr. Name Created successfully')
                                    ));
     
    }

    public function doctor_list()
    {
    	$user_type = $_POST['user_type'];
    	$email   = $this->input->post('email');
    	 
    	$added = $this->healthsupervisor_app_model->get_doctor_names($email,$user_type);

    	  if(!empty($added))
		{
	      $this->output->set_output(json_encode($added));
		}
    }


    public function create_doctor_visit_report()
  	{ 		  		
	  	$uniqueId   = $this->input->post('student_code');
	  	$doc_id   = $this->input->post('doc_id');
        $emails   = $this->input->post('$email');
        $user_type = $_POST['user_type'];
	  	
				$doc_data = array();
				$doc_attachments = array();
				$uniqueId   = $this->input->post('student_code');
				$studentName = $this->input->post('student_name');
				$class = $this->input->post('student_class');
				$section = $this->input->post('student_section');
				$doctor_visiting_date = $this->input->post('doctor_visiting_date');
                $select_doc_name = $this->input->post('select_doc_name');
				$remarks = $this->input->post('remarks');

				$doc_data['Student Details']['Hospital Unique ID'] = $uniqueId;
				$doc_data['Student Details']['Name'] = $studentName;
				$doc_data['Student Details']['Class'] = $class;
				$doc_data['Student Details']['Section'] = $section;
				$doc_data['Student Details']['doctor_visiting_date'] = $doctor_visiting_date;
                $doc_data['Student Details']['Visiting_doctor_name'] = $select_doc_name;
				$doc_data['Student Details']['remarks'] = $remarks;	
              
		
		if(isset($_FILES) && !empty($_FILES))
    {
        $this->load->library('upload');
        $this->load->library('image_lib');
        
        $Prescriptions_external_files_upload_info 	= array();
        $Lab_Reports_external_files_upload_info 	= array();
        $Digital_Images_external_files_upload_info 	= array();
        $Payments_Bills_upload_info 				= array();
        $Discharge_Summary_upload_info 				= array();
      	$external_attachments_upload_info 			= array();

        $Prescriptions_external_final          		= array();
        $Lab_Reports_external_final    				= array();
        $Digital_external_final    					= array();
        $Payments_Bills_external_final            	= array();
        $Discharge_Summary_external_final          	= array();
        $hs_req_attachments_external_final          = array();
        $external_final            					= array();
        
        foreach ($_FILES as $index => $value)
       {    
            $files = $_FILES;
             if(strpos($index,'Prescriptions')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Prescriptions']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Prescriptions']['name']  = $files['Prescriptions']['name'][$i];
                     $_FILES['Prescriptions']['type']  = $files['Prescriptions']['type'][$i];
                     $_FILES['Prescriptions']['tmp_name']= $files['Prescriptions']['tmp_name'][$i];
                     $_FILES['Prescriptions']['error'] = $files['Prescriptions']['error'][$i];
                     $_FILES['Prescriptions']['size']  = $files['Prescriptions']['size'][$i];
            
              		$this->upload->initialize($this->Prescriptions_attachment_upload_options('healthcare2016531124515424_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Prescriptions_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Prescriptions_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Prescriptions_external_files_upload_info['file_name'],
                                            "file_path" =>$Prescriptions_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Prescriptions_external_files_upload_info['file_size']
                                            )
                                        );

                    $Prescriptions_external_final = array_merge($Prescriptions_external_final,$hs_external_data_array);
            }
                }
                }
            }
            if(strpos($index,'Lab_Reports')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Lab_Reports']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Lab_Reports']['name']  = $files['Lab_Reports']['name'][$i];
                     $_FILES['Lab_Reports']['type']  = $files['Lab_Reports']['type'][$i];
                     $_FILES['Lab_Reports']['tmp_name']= $files['Lab_Reports']['tmp_name'][$i];
                     $_FILES['Lab_Reports']['error'] = $files['Lab_Reports']['error'][$i];
                     $_FILES['Lab_Reports']['size']  = $files['Lab_Reports']['size'][$i];
            
              		$this->upload->initialize($this->Lab_Reports_attachment_upload_options('healthcare201610114435690_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Lab_Reports_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Lab_Reports_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Lab_Reports_external_files_upload_info['file_name'],
                                            "file_path" =>$Lab_Reports_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Lab_Reports_external_files_upload_info['file_size']
                                            )

                                        );

                    $Lab_Reports_external_final = array_merge($Lab_Reports_external_final,$hs_external_data_array);
            }
                }
                }
            }
        
     		if(strpos($index,'Digital_Images')!== false)
			{
                if(!empty($value['name']))
                {
                $cpt = count($_FILES['Digital_Images']['name']);
                for($i=0; $i<$cpt; $i++)
                {
                     $_FILES['Digital_Images']['name']  = $files['Digital_Images']['name'][$i];
                     $_FILES['Digital_Images']['type']  = $files['Digital_Images']['type'][$i];
                     $_FILES['Digital_Images']['tmp_name']= $files['Digital_Images']['tmp_name'][$i];
                     $_FILES['Digital_Images']['error'] = $files['Digital_Images']['error'][$i];
                     $_FILES['Digital_Images']['size']  = $files['Digital_Images']['size'][$i];
            
              		$this->upload->initialize($this->Digital_Images_attachment_upload_options('healthcare201610114435690_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "external file upload failed";
                    // return FALSE;
                }
            else
            {
                    $Digital_Images_external_files_upload_info = $this->upload->data();
                
                    $hs_external_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i=> array(
                                            "file_client_name" =>$Digital_Images_external_files_upload_info['client_name'],
                                            "file_encrypted_name" =>$Digital_Images_external_files_upload_info['file_name'],
                                            "file_path" =>$Digital_Images_external_files_upload_info['file_relative_path'],
                                            "file_size" =>$Digital_Images_external_files_upload_info['file_size']
                                            )
                                        );

                    $Digital_external_final = array_merge($Digital_external_final,$hs_external_data_array);
            }
                }
                }
            }
       
       	if(strpos($index,'Payments_Bills')!== false)
		 {
             if(!empty($value['name']))
            {
            $bill = count($_FILES['Payments_Bills']['name']);
            for($i=0; $i<$bill; $i++)
            {                
                 $_FILES['Payments_Bills']['name']    = $files['Payments_Bills']['name'][$i];
                 $_FILES['Payments_Bills']['type']    = $files['Payments_Bills']['type'][$i];
                 $_FILES['Payments_Bills']['tmp_name']= $files['Payments_Bills']['tmp_name'][$i];
                 $_FILES['Payments_Bills']['error']   = $files['Payments_Bills']['error'][$i];
                 $_FILES['Payments_Bills']['size']    = $files['Payments_Bills']['size'][$i];
                 
                $this->upload->initialize($this->Payments_Bills_upload_options('healthcare201610114435690_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "Payments/Bills  upload failed";
                     //return FALSE;
                }
                else
                {           
                    $Payments_Bills_upload_info = $this->upload->data();
                    
                    $kitchen_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Payments_Bills_upload_info['client_name'],
                                            "file_encrypted_name" =>$Payments_Bills_upload_info['file_name'],
                                            "file_path" =>$Payments_Bills_upload_info['file_relative_path'],
                                            "file_size" =>$Payments_Bills_upload_info['file_size']
                                                            )
                                             );

                    $Payments_Bills_external_final = array_merge($Payments_Bills_external_final,$kitchen_data_array);
            
                }
            }
            }
        }

        	if(strpos($index,'Discharge_Summary')!== false)
		 {
             if(!empty($value['name']))
            {
            $discharge_summary_count = count($_FILES['Discharge_Summary']['name']);
            for($i=0; $i<$discharge_summary_count; $i++)
            {
                
                 $_FILES['Discharge_Summary']['name']    = $files['Discharge_Summary']['name'][$i];
                 $_FILES['Discharge_Summary']['type']    = $files['Discharge_Summary']['type'][$i];
                 $_FILES['Discharge_Summary']['tmp_name']= $files['Discharge_Summary']['tmp_name'][$i];
                 $_FILES['Discharge_Summary']['error']   = $files['Discharge_Summary']['error'][$i];
                 $_FILES['Discharge_Summary']['size']    = $files['Discharge_Summary']['size'][$i];
                
                $this->upload->initialize($this->Discharge_Summary_upload_options('healthcare201610114435690_con',$index));

                if ( ! $this->upload->do_upload($index))
                {
                     echo "dormitory upload failed";
                     //return FALSE;
                }
                else
                {   
        
                    $Discharge_Summary_upload_info = $this->upload->data();
                    
                    $dormitory_data_array = array(
                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
                                            "file_client_name" =>$Discharge_Summary_upload_info['client_name'],
                                            "file_encrypted_name" =>$Discharge_Summary_upload_info['file_name'],
                                            "file_path" =>$Discharge_Summary_upload_info['file_relative_path'],
                                            "file_size" =>$Discharge_Summary_upload_info['file_size']
                                                            )
                                             );

                    $Discharge_Summary_external_final = array_merge($Discharge_Summary_external_final,$dormitory_data_array);
            
                }
            }
            }
        }
        if(strpos($index,'hs_req_attachments')!== false)
		 {
             if(!empty($value['name']))
            {
            $hs_req_attachments_count = count($_FILES['hs_req_attachments']['name']);
            for($i=0; $i<$hs_req_attachments_count; $i++)
            {                
                 $_FILES['hs_req_attachments']['name']    = $files['hs_req_attachments']['name'][$i];
                 $_FILES['hs_req_attachments']['type']    = $files['hs_req_attachments']['type'][$i];
                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                 $_FILES['hs_req_attachments']['error']   = $files['hs_req_attachments']['error'][$i];
                 $_FILES['hs_req_attachments']['size']    = $files['hs_req_attachments']['size'][$i];
                 
                $this->upload->initialize($this->external_attachments_upload_options('healthcare201610114435690_con',$index));

				                if ( ! $this->upload->do_upload($index))
				                {
				                     echo "external old attachments upload failed";
				                     //return FALSE;
				                }
				                else
				                {   				        
				                    $external_attachments_upload_info = $this->upload->data();
				                    
				                    $external_attachments_old_data_array = array(
				                                            "DFF_EXTERENAL_FILE_ATTACHMENT_".$i => array(
				                                            "file_client_name" =>$external_attachments_upload_info['client_name'],
				                                            "file_encrypted_name" =>$external_attachments_upload_info['file_name'],
				                                            "file_path" =>$external_attachments_upload_info['file_relative_path'],
				                                            "file_size" =>$external_attachments_upload_info['file_size']
				                                                            )
				                                             );

				                    $external_final = array_merge($external_final,$external_attachments_old_data_array);				            
				                }
            			}
            		}
        		}
         
       		}

	       	if(isset($doc_attachments['Prescriptions']))
	        {	               
	                $prescription_merged_data = array_merge($doc_attachments['Prescriptions'],$Prescriptions_external_final);
	                $doc_attachments['Prescriptions'] = array_replace_recursive($doc_attachments['Prescriptions'],$prescription_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Prescriptions'] = $Prescriptions_external_final;	                
	        } 

	         if(isset($doc_attachments['Lab_Reports']))
	        {	               
	                $lab_reports_merged_data = array_merge($doc_attachments['Lab_Reports'],$Lab_Reports_external_final);
	                $doc_attachments['Lab_Reports'] = array_replace_recursive($doc_attachments['Lab_Reports'],$lab_reports_merged_data);	                
	        }
	        else
	        {
	                $doc_attachments['Lab_Reports'] = $Lab_Reports_external_final;
	                
	        } 
	        if(isset($doc_attachments['Digital_Images']))
	        {
	                $digital_images_merged_data = array_merge($doc_attachments['Digital_Images'],$Digital_external_final);
	                $doc_attachments['Digital_Images'] = array_replace_recursive($doc_attachments['Digital_Images'],$digital_images_merged_data); 
	               
	        }
	        else
	        {
	                $doc_attachments['Digital_Images'] = $Digital_external_final;
	        }
	        
	        if(isset($doc_attachments['Payments_Bills']))
	        {
	                $kitchen_merged_data = array_merge($doc_attachments['Payments_Bills'],$Payments_Bills_external_final);
	                $doc_attachments['Payments_Bills'] = array_replace_recursive($doc_attachments['Payments_Bills'],$kitchen_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Payments_Bills'] = $Payments_Bills_external_final;
	        }

	         if(isset($doc_attachments['Discharge_Summary']))
	        {
	                $dormitory_merged_data = array_merge($doc_attachments['Discharge_Summary'],$Discharge_Summary_external_final);
	                $doc_attachments['Discharge_Summary'] = array_replace_recursive($doc_attachments['Discharge_Summary'],$dormitory_merged_data);
	        }
	        else
	        {
	                $doc_attachments['Discharge_Summary'] = $Discharge_Summary_external_final;
	        }

	         if(isset($doc_attachments['external_attachments']))
	        {
	                $external_merged_data = array_merge($doc_attachments['external_attachments'],$external_final);
	                $doc_attachments['external_attachments'] = array_replace_recursive($doc_attachments['external_attachments'],$external_merged_data);
	        }
	        else
	        {
	                $doc_attachments['external_attachments'] = $external_final;
	        }


    	}

    		    $email    		= $this->input->post('email', true);
		        $email_array    = explode(".",$email);
		        $school_code    = (int) $email_array[1];
				$school_info = $this->healthsupervisor_app_model->get_school_info($school_code,$user_type);				
				$school_name = $school_info[0]['school_name'];
				$dist = explode(',', $school_name);
				$districtName = $dist[1];				

				$doc_data['school_details']['School Name'] = $school_name;
				$doc_data['school_details']['District'] = $districtName;

				$doc_properties['doc_id'] = get_unique_id();

				$doc_properties['status'] = 2;
				$doc_properties['_version'] = 2;
				$doc_properties['doc_owner'] = "PANACEA";
				$doc_properties['unique_id'] = '';
				$doc_properties['doc_flow'] = "new";
				$app_properties['app_name'] = "Doctor Visiting App";
				$app_properties['app_id'] = "Doctor Visiting";				
				
				$emails = str_replace("@","#",$email);
	          	// History
				$approval_data = array(
					"current_stage" => "stage1",
					"approval" => "true",
					"submitted_by" => $emails,
                    'raised_by' => "Device_Side",
					"time" => date('Y-m-d H:i:s'));

				$history['last_stage'] = $approval_data;

		$newly_created = $this->healthsupervisor_app_model->submit_doctor_visiting_report($doc_data,$doc_attachments, $doc_properties, $app_properties, $history, $user_type);

		    if(!empty($newly_created))
		  {
	        $this->output->set_output(json_encode($newly_created));
		  }

          $this->output->set_output(json_encode(array(
                                        'status' => TRUE, 
                                        'message' => 'Submitted Successfully')
                                    ));

				
	}

    public function get_all_screening_diseases_counts()
    {
        $academic_year = $this->input->post('academic_year', true);
        $abnormalities = $this->input->post('abnormalities', true);
        $dt_name = $this->input->post('po_name', true);
        $school_name = $this->input->post('school_name', true);

        if($dt_name != 'All'){
            $dist = $this->panacea_common_model->get_dt_name_based_on_id($dt_name);
            $po_name = $dist[0]['dt_name'];
        }else{
            $po_name = 'All';
        }
    
        $abnormalities = $this->panacea_common_model->get_all_screening_diseases_counts($academic_year, $abnormalities, $po_name, $school_name);

        if(count($abnormalities) > 0)
        {
            $result = call_user_func_array("array_merge", $abnormalities);
            $this->output->set_output(json_encode($abnormalities));
        }
        else
        {            
            $this->output->set_output(json_encode("No Problems found"));
        }

    }

    function get_submitted_doctor_visit_students()
    {   
        $email = $_POST['email'];
        $date = $_POST['selected_date'];
        $user_type = $_POST['user_type'];

        $query = $this->healthsupervisor_app_model->get_submitted_doctor_visit_students($email,$date,$user_type);
        if(!empty($query))
        {
            $this->output->set_output(json_encode($query));
        }else{
            $this->output->set_output(json_encode(json_encode("No Problems found")));
        }

    }









  

// Maharashtra Screening Syncing app  maharashtra_medical_evaluation_app =====================================

	public function medical_evaluation_app_bkp_mh()
		{
			$post_data = isset($_POST['data']) ? $_POST['data'] : "";
			log_message('error','before encoding' .print_r($post_data, true));
			$post_data = json_decode($post_data,true);
			log_message('error','postdatachecking_after_encoding====159' .print_r($post_data, true));
			//$student_data = json_decode($profile_data,TRUE);
			//log_message('error',"Student_dataaaaaa===============1377======sync".print_r($student_data,true));
			////log_message('error',"post_hs_data===============317".print_r($postdata,true));
			////log_message('error',"post_data===============319".print_r($post_data,true));
			$unique_id = $post_data['UID'];
			$id = explode("_", $unique_id);
			log_message('error', "checking id in model".print_r($id, true)); 
			$original = $id[2]+1;

			log_message('error', "checkin === check_name originalllll".print_r($id[0]."_".$id[1]."_".$original, true));
			
			$student_name = $post_data['NAME'];

			/*$check_name = $this->schoolhealth_school_portal_model->checking_name_if_exists($student_name, $unique_id);

			log_message('error', "checkin === check_name".print_r($check_name[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'], true));			

			if(!empty($check_name)){
				$health_id = $check_name[0]['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];
				log_message('error', "checkin === health_id".print_r($health_id, true));
			}else{
				//$health_id = $post_data['UID'];
				$check_unique_id = $this->schoolhealth_school_portal_model->checking_unique_id_if_exists($unique_id);
				log_message('error', "checkin === check_unique_id".print_r($check_unique_id, true));
				if(!empty($check_unique_id)){
					$health_id = $check_unique_id;
					
				}else
				{
					$health_id = $post_data['UID'];

				}
			}*/

			log_message('error', "checkin === health_id 6558".print_r($health_id, true)); 
			$dist_code = explode('_', $unique_id);			

			//$school_name = $post_data['SCHOOLNAME'];
			//$districtName = $post_data['DISTRICT'];
			
			if($post_data)
			{
				log_message('error','checkingpostdata_screenings_screening======4854'.print_r($post_data, true));

				$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $post_data['UID']; //$health_id;
				$doc_data['widget_data']['page1']['Personal Information']['Name'] = $student_name;
				$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
				$doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = $post_data['MOBILE'];
				$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $post_data['DATEOFBIRTH'];
				$doc_data['widget_data']['page1']['Personal Information']['Gender'] = (isset($post_data['GENDER'])) ? $post_data['GENDER'] : "";
				$doc_data['widget_data']['page2']['Personal Information']['Aadhaar No'] = $post_data['ADNO'];
				$doc_data['widget_data']['page2']['Personal Information']['School Name'] = $post_data['SCHOOLNAME']; //$school_name;
				$doc_data['widget_data']['page2']['Personal Information']['District'] = $post_data['DISTRICT'];//$districtName;
				$doc_data['widget_data']['page2']['Personal Information']['Class'] = $post_data['CLASS'];
				$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = $post_data['FATHERNAME'];
				$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = $post_data['DATEOFEXAM'];

				$doc_data['widget_data']['page3']['Physical Exam']['Height cms'] = intval($post_data['HEIGHT']);
				$doc_data['widget_data']['page3']['Physical Exam']['Weight kgs'] = intval($post_data['WEIGHT']);
				$doc_data['widget_data']['page3']['Physical Exam']['BMI%'] = floatval($post_data['BMI']);
				$doc_data['widget_data']['page3']['Physical Exam']['Pulse'] = $post_data['PULSE'];
				$doc_data['widget_data']['page3']['Physical Exam']['B P'] = $post_data['BP'];
				$doc_data['widget_data']['page3']['Physical Exam']['H B'] = floatval($post_data['HB']);
				$doc_data['widget_data']['page3']['Physical Exam']['Blood Group'] = $post_data['BLOODGROUP'];
				$doc_data['widget_data']['page3']['Physical Exam']['SPO2'] = $post_data['spo2'];
				$doc_data['widget_data']['page3']['Physical Exam']['Ni Gluc'] = $post_data['NI-Giuc'];
	            $doc_data['widget_data']['page3']['Physical Exam']['H R'] = $post_data['HR'];
	            $doc_data['widget_data']['page3']['Physical Exam']['Temperature'] = $post_data['Temperature'];

			    $doc_data['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'] = (!empty($post_data['ABNORMALITIES'])) ? explode(',', $post_data['ABNORMALITIES']) : [];

				$doc_data['widget_data']['page4']['Doctor Check Up']['Ortho'] = (!empty($post_data['ORTHO'])) ? explode(',', $post_data['ORTHO']) : [];
				$doc_data['widget_data']['page4']['Doctor Check Up']['Postural'] = (!empty($post_data['POSTURAL'])) ? explode(',', $post_data['POSTURAL']) : [];
				  $doc_data['widget_data']['page4']['Doctor Check Up']['Common Diseases'] = !empty($post_data['Common diseases']) ? explode(',', $post_data['Common diseases']) : [];
	            
				$doc_data['widget_data']['page4']['Doctor Check Up']['Description'] = $post_data['General doctor DESCRIPTION'];
				$doc_data['widget_data']['page4']['Doctor Check Up']['Treatment'] = $post_data['General doctor TREATMENT'];
				if(isset($post_data['Skin conditions']) && !empty($post_data['Skin conditions'])){
					$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = !empty($post_data['Skin conditions']) ? explode(',', $post_data['Skin conditions']) : [];
					}
					else{
						$doc_data['widget_data']['page4']['Doctor Check Up']['Skin conditions'] = [];
					}

				$doc_data['widget_data']['page5']['Doctor Check Up']['Defects at Birth'] = !empty($post_data['DEFECTSATBIRTH']) ? explode(',', $post_data['DEFECTSATBIRTH']) : [];
				$doc_data['widget_data']['page5']['Doctor Check Up']['Deficencies'] = !empty($post_data['DEFICIENCIES']) ? explode(',', $post_data['DEFICIENCIES']) : [];
				$doc_data['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'] = !empty($post_data['CHILDHOODDISEASES']) ? explode(',', $post_data['CHILDHOODDISEASES']) : [];
				   $doc_data['widget_data']['page5']['Doctor Check Up']['Referral Made'] = !empty($post_data['general referalmade']) ? $post_data['general referalmade'] : [];
				$doc_data['widget_data']['page5']['Doctor Check Up']['N A D'] = !empty($post_data['NAD']) ? $post_data['NAD'] : [];
				//$doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = ($arr_data[$i]['general_doctor']) ? $arr_data[$i]['general_doctor'] : "";

				$doc_data['widget_data']['page6']['Screenings'] = [];
				$doc_data['widget_data']['page6']['Without Glasses']['Right'] = ($post_data['WITHOUTR']) ? $post_data['WITHOUTR'] : "";
				$doc_data['widget_data']['page6']['Without Glasses']['Left'] = ($post_data['WITHOUTL']) ? $post_data['WITHOUTL'] : "";
				$doc_data['widget_data']['page6']['With Glasses']['Right'] = ($post_data['WITHR']) ? $post_data['WITHR'] : "";
				$doc_data['widget_data']['page6']['With Glasses']['Left'] = ($post_data['WITHL']) ? $post_data['WITHL'] : "";
				$doc_data['widget_data']['page6']['Vision Screening'] = !empty($post_data['visionScreening']) ? explode(',', $post_data['visionScreening']) : [];
				$doc_data['widget_data']['page6']['Bitot Night Blindness'] = !empty($post_data['bitotNightblindness']) ? explode(',', $post_data['bitotNightblindness']) : [];
				
				$doc_data['widget_data']['page7']['Colour Blindness']['Referral Made'] = !empty($post_data['VISIONSCREEN referalmade']) ? $post_data['VISIONSCREEN referalmade'] : [];
				$doc_data['widget_data']['page7']['Colour Blindness']['Description'] = $post_data['Eye DESCRIPTION'];						

				$doc_data['widget_data']['page8'][' Auditory Screening']['Right'] = ($post_data['Aditory Right']) ? $post_data['Aditory Right'] : [];
				$doc_data['widget_data']['page8'][' Auditory Screening']['Left'] = ($post_data['Aditory Left']) ? $post_data['Aditory Left'] : [];
				$doc_data['widget_data']['page8'][' Auditory Screening']['Speech Screening'] = !empty($post_data['SPEECHSCREENING']) ? explode(',', $post_data['SPEECHSCREENING']) : [];
				$doc_data['widget_data']['page8'][' Auditory Screening']['D D and disability'] = !empty($post_data['DDDisability']) ? explode(',', $post_data['DDDisability']) : [];
				$doc_data['widget_data']['page8'][' Auditory Screening']['Description'] = $post_data['Auditory DESCRIPTION'];
				$doc_data['widget_data']['page8'][' Auditory Screening']['Referral Made'] = !empty($post_data['AUDITORYSCREEN referalmade']) ? $post_data['AUDITORYSCREEN referalmade'] : [];

				$doc_data['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] = ($post_data['Oral Hygiene']) ? $post_data['Oral Hygiene'] : "";
				$doc_data['widget_data']['page9']['Dental Check-up']['Carious Teeth'] = ($post_data['Carious teeth']) ? $post_data['Carious teeth']:"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Flourosis'] = ($post_data['Flouosis']) ? $post_data['Flouosis'] : "";
				$doc_data['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] = ($post_data['Orthodontic treatment']) ? $post_data['Orthodontic treatment'] :"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Indication for extraction'] = ($post_data['Ind for Extraction']) ? $post_data['Ind for Extraction']:"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Halitosis'] = ($post_data['halitosis']) ? $post_data['halitosis'] :"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Flat patches'] = ($post_data['flatpatches']) ? $post_data['flatpatches'] :"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Ulcer'] = (isset($post_data['ulcer'])) ? $post_data['ulcer']:"";
				$doc_data['widget_data']['page9']['Dental Check-up']['Referral Made'] = (isset($post_data['DENTALRCHECK referalmade'])) ? $post_data['DENTALRCHECK referalmade']:[];
				$doc_data['widget_data']['page9']['Dental Check-up']['Result'] = (isset($post_data['Dental DESCRIPTION'])) ? $post_data['Dental DESCRIPTION']:[];
				$doc_data['widget_data']['page10']['General Information']['Command Center Followup'] = (isset($post_data['commandCenterFollowup'])) ? $post_data['commandCenterFollowup']:[];
				$doc_data['widget_data']['page10']['General Information']['Post Operative Cases'] = (isset($post_data['postOperCase'])) ? $post_data['postOperCase']:[];
				$doc_data['widget_data']['page10']['General Information']['Surgery Cases'] = (isset($post_data['surgeryCase'])) ? $post_data['surgeryCase']:[];

				 // Doc properties
				$doc_properties['doc_id'] = get_unique_id();
				$doc_properties['status'] = 1;
				$doc_properties['_version'] = 1;
			    $doc_properties["doc_owner"] = "MH";
		        $doc_properties["unique_id"] = "";
		        $doc_properties["doc_flow"] = "new";
				// History
				$approval_data = array(
					"current_stage" => "stage1",
					"doc_owner" => "ameya schools",
					"submitted_by" => (isset($_POST['submitted_by'])) ? $_POST['submitted_by']:'schoolhealth.ameya@gmail.com',
					"time" => date('Y-m-d H:i:s'),
					"approval" => "true",
					'synced_date' => Date('Y-m-d'),
		            "Version" => "2.0");

				$history['last_stage'] = $approval_data;

				$app_properties['app_name'] = "MH General Medical Evaluation";
				$app_properties['app_id'] = "Medical_Evalution_Syn_collection";

			// Student Profile PDF_set_info_creator()

		  		if(isset($_FILES) && !empty($_FILES))
				{
				log_message("error", "student_img_pathcheck === 7016".print_r($_FILES, true));
			       $this->load->library('upload');
			       $this->load->library('image_lib');			  
		  		   
		  		  
		  		   		foreach ($_FILES as $index => $value)
				       {
				       	  if(!empty($value['name']) && $index == 'student_image')
						  {				      
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/photo/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '4096';
								$config['encrypt_name']  = TRUE;
							
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/photo/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									echo "file upload failed";
									return FALSE;
								}
								else
								{
									
									$photo_obj = $this->upload->data();
								 	$photo_ele = array(
										"file_client_name"    => $photo_obj['client_name'],
										"file_encrypted_name" => $photo_obj['file_name'],
										"file_path" 		  => $photo_obj['file_relative_path'],
										"file_size" 		  => $photo_obj['file_size']
							 		 );			
							 		 $doc_data['widget_data']['page1']['Personal Information']['Photo'] = $photo_ele;	 				
								}  
							}
						}
		  		   	   					 
			 	}else{
			 		 $doc_data['widget_data']['page1']['Personal Information']['Photo']= "";	 				
			 	}

			 	// General Physician Sign

			 	if(isset($_FILES) && !empty($_FILES))
				{
			       $this->load->library('upload');
			       $this->load->library('image_lib');			  
		  		   
		  		  
		  		   		foreach ($_FILES as $index => $value)
				       {
				       	  if(!empty($value['name']) && $index == 'doctor_sign_img')
						  {				      
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/screening_signs/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '4096';
								$config['encrypt_name']  = TRUE;
							
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/screening_signs/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									echo "file upload failed";
									return FALSE;
								}
								else
								{
									$doctor_obj = $this->upload->data();
								 	$doctor_ele = array(
										"file_client_name"    => $doctor_obj['client_name'],
										"file_encrypted_name" => $doctor_obj['file_name'],
										"file_path" 		  => $doctor_obj['file_relative_path'],
										"file_size" 		  => $doctor_obj['file_size']
							 		 );			
							 		 $doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign'] = $doctor_ele;	 				
								}  
							}
						}
		  		   	   					 
			 	}else{
			 		 $doc_data['widget_data']['page5']['Doctor Check Up']['General Physician Sign']= "";		
			 	}

			// Opthomologist Sign

			 	if(isset($_FILES) && !empty($_FILES))
				{
			       $this->load->library('upload');
			       $this->load->library('image_lib');		  		   
		  		  
		  		   		foreach ($_FILES as $index => $value)
				       {
				       	  if(!empty($value['name']) && $index == 'opthomologist_sign')
						  {				      
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/screening_signs/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '4096';
								$config['encrypt_name']  = TRUE;
							
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/screening_signs/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									echo "file upload failed";
									return FALSE;
								}
								else
								{
									$opthomologist_obj = $this->upload->data();
								 	$opthomologist_ele = array(
										"file_client_name"    => $opthomologist_obj['client_name'],
										"file_encrypted_name" => $opthomologist_obj['file_name'],
										"file_path" 		  => $opthomologist_obj['file_relative_path'],
										"file_size" 		  => $opthomologist_obj['file_size']
							 		 );			
							 		 $doc_data['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'] = $opthomologist_ele;	 				
								}  
							}
						}
		  		   	   					 
			 	}else{
			 		 $doc_data['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']= "";	 				
			 	}

			 	// Dentist Sign

			 	if(isset($_FILES) && !empty($_FILES))
				{
			       $this->load->library('upload');
			       $this->load->library('image_lib');			  
		  		   
		  		  
		  		   		foreach ($_FILES as $index => $value)
				       {
				       	  if(!empty($value['name']) && $index == 'audiologist_sign')
						  {				      
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/screening_signs/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '4096';
								$config['encrypt_name']  = TRUE;
							
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/screening_signs/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									echo "file upload failed";
									return FALSE;
								}
								else
								{
									$audiologist_obj = $this->upload->data();
								 	$audiologist_ele = array(
										"file_client_name"    => $audiologist_obj['client_name'],
										"file_encrypted_name" => $audiologist_obj['file_name'],
										"file_path" 		  => $audiologist_obj['file_relative_path'],
										"file_size" 		  => $audiologist_obj['file_size']
							 		 );			
							 		 $doc_data['widget_data']['page8'][' Auditory Screening']['Audiologist Sign'] = $audiologist_ele;	 				
								}  
							}
						}
		  		   	   					 
			 	}else{
			 		 $doc_data['widget_data']['page8'][' Auditory Screening']['Audiologist Sign'] = "";	 				
			 	}

			 	// Dentist Sign

			 	if(isset($_FILES) && !empty($_FILES))
				{
			       $this->load->library('upload');
			       $this->load->library('image_lib');			  
		  		   
		  		  
		  		   		foreach ($_FILES as $index => $value)
				       {
				       	  if(!empty($value['name']) && $index == 'dentist_sign_img')
						  {				      
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/screening_signs/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '4096';
								$config['encrypt_name']  = TRUE;
							
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/screening_signs/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									echo "file upload failed";
									return FALSE;
								}
								else
								{
									$dentist_obj = $this->upload->data();
								 	$dentist_ele = array(
										"file_client_name"    => $dentist_obj['client_name'],
										"file_encrypted_name" => $dentist_obj['file_name'],
										"file_path" 		  => $dentist_obj['file_relative_path'],
										"file_size" 		  => $dentist_obj['file_size']
							 		 );			
							 		 $doc_data['widget_data']['page9']['Dental Check-up']['Dentist Sign'] = $dentist_ele;	 				
								}  
							}
						}
		  		   	   					 
			 	}else{
			 		 $doc_data['widget_data']['page9']['Dental Check-up']['Dentist Sign']= "";	 				
			 	}

			
			 	if(isset($_FILES) && !empty($_FILES))
				{
				
			       $this->load->library('upload');
			       $this->load->library('image_lib');
				   
				   $external_screening_files = array();
				   $external_screening_final = array();
				   $external_screening_merged_data = array();

				    $medical_evalution_form_mef = array();
				    $medical_evalution_form_final = array();
				    $mef_merged_data       = array();


				   if(isset($_FILES['external_attachments']['name']) && !empty($_FILES['external_attachments']['name']))
				   {
				   	   $files = $_FILES;
					   $cpt = count($_FILES['external_attachments']['name']);
					    
					   for($i=0; $i<$cpt; $i++)
					   {
						 $_FILES['external_attachments']['name']	= $files['external_attachments']['name'][$i];
						 $_FILES['external_attachments']['type']	= $files['external_attachments']['type'][$i];
						 $_FILES['external_attachments']['tmp_name'] = $files['external_attachments']['tmp_name'][$i];
						 $_FILES['external_attachments']['error']	= $files['external_attachments']['error'][$i];
						 $_FILES['external_attachments']['size']	= $files['external_attachments']['size'][$i];
						
					   foreach ($_FILES as $index => $value)
				       {			       
				       
				       		if(!empty($value['name'] && $index == 'external_attachments'))
						  	{
						        $config = array();
								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/files/external_files/';
								$config['allowed_types'] = '*';
								$config['max_size']      = '*';
								$config['encrypt_name']  = TRUE;
						  	
						        //create controller upload folder if not exists
								if (!is_dir($config['upload_path']))
								{
									mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/files/external_files/",0777,TRUE);
								}
					
								$this->upload->initialize($config);
								
								if ( ! $this->upload->do_upload($index))
								{
									 echo "external file upload failed";
					        		 return FALSE;
								}
								else
								{
									$external_screening_files = $this->upload->data();
									//log_message('debug', 'external_screening_files=======5849'.print_r($external_screening_files, true));
									$rand_number = mt_rand();
									$external_screening_data_array = array(
															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
															"file_name" =>$external_screening_files['file_name'],
															"file_path" =>$external_screening_files['file_relative_path'],
															"file_size" =>$external_screening_files['file_size']
														)	);

									$external_screening_final = array_merge($external_screening_final,$external_screening_data_array);
									
								}  
							}
						}
						 }
					 
					   if(isset($doc_data['external_attachments']))
						  {
								   
							$external_screening_merged_data = array_merge($doc_data['doc_data']['external_attachments'],$external_screening_final);
							$doc_data['doc_data']['external_attachments'] = array_replace_recursive($doc_data['doc_data']['external_attachments'],$external_screening_merged_data);
						  }
						  else
						 {
						    $doc_data['external_attachments'] = $external_screening_final;
						 }
					   }else
					   {
					   		$doc_data['external_attachments'] = [];
					   }
					    if(isset($_FILES['mef_attachments']['name']) && !empty($_FILES['mef_attachments']['name']))
					   {
					   	   $files = $_FILES;
						   $cpt = count($_FILES['mef_attachments']['name']);
						   for($i=0; $i<$cpt; $i++)
						   {
							 $_FILES['mef_attachments']['name']	= $files['mef_attachments']['name'][$i];
							 $_FILES['mef_attachments']['type']	= $files['mef_attachments']['type'][$i];
							 $_FILES['mef_attachments']['tmp_name']= $files['mef_attachments']['tmp_name'][$i];
							 $_FILES['mef_attachments']['error']	= $files['mef_attachments']['error'][$i];
							 $_FILES['mef_attachments']['size']	= $files['mef_attachments']['size'][$i];
							
						   foreach ($_FILES as $index => $value)
					       {
					       		if(!empty($value['name']) && $index == 'mef_attachments')
							  	{
							        $config = array();
									$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/maharashtra_files/files/medical_evaluation_forms/';
									$config['allowed_types'] = '*';
									$config['max_size']      = '4096';
									$config['encrypt_name']  = TRUE;
							  	
							        //create controller upload folder if not exists
									if (!is_dir($config['upload_path']))
									{
										mkdir(UPLOADFOLDERDIR."public/uploads/maharashtra_files/files/medical_evaluation_forms/",0777,TRUE);
									}
						
									$this->upload->initialize($config);
									
									if ( ! $this->upload->do_upload($index))
									{
										 echo "external file upload failed";
						        		 return FALSE;
									}
									else
									{
										$medical_evalution_form_mef = $this->upload->data();
										$rand_number = mt_rand();
										$mef_data_array = array( $rand_number => array(
																 "file_name" =>$medical_evalution_form_mef['file_name'],
																"file_path" =>$medical_evalution_form_mef['file_relative_path'],
																"file_size" =>$medical_evalution_form_mef['file_size']
																)
															);

										$medical_evalution_form_final = array_merge($medical_evalution_form_final,$mef_data_array);
										
									}  
								}
							}
						 }
					 
					   if(isset($doc_data['medical_evaluation_form']))
						  {
								   
							$mef_merged_data = array_merge($doc_data['doc_data']['medical_evaluation_form'],$medical_evalution_form_final);
							$doc_data['doc_data']['medical_evaluation_form'] = array_replace_recursive($doc_data['doc_data']['medical_evaluation_form'],$mef_merged_data);
						  }
						  else
						 {
						    $doc_data['medical_evaluation_form'] = $medical_evalution_form_final;
						 }
				   }else
				   {
				   		$doc_data['medical_evaluation_form'] = [];
				   }			
				 	
				   
				}
			 	
			 	$status = $this->healthsupervisor_app_model->maharashtra_insert_medical_information_sync($doc_data,$history,$doc_properties, $app_properties);
			 	//log_message('error','insert_medical_information_syncinsert_medical_information_sync=========5437'.print_r($status, true));
			 	if(!empty($status))
			 	{
			 		$this->output->set_output(json_encode(array('Status' => 'Successfully submitted!',
			 			"unique_id" => $unique_id)));
			 	}else{
			 		$this->output->set_output(json_encode(array('Status' => 'Sync Failed')));
			 	}
			}
				  
		}
// End  Maharashtra Screening Syncing app =====================================

// For Students parents Detailscapturing register process

	public function register_otp_for_parents_health()
	{
		$post = $_POST;

		$name = $post['name'];
		$uid = $post['uid'];
		$otp = $post['otp'];
		$mobile = $post['mobile'];
		$dist = $post['district'];
		$scl = $post['school'];
		$date = date('Y-m-d');
		
		//echo print_r($uid, true); exit();
		if(isset($uid) && preg_match("/[a-zA-Z]+_+[0-9]+_+[0-9]/i", $uid))
		{
			$check_exists = $this->healthsupervisor_app_model->check_uid_exists_today($uid, $date);
			$pattern_check = "pattern_matched";
		}else{
			$pattern_check = "pattern_not_matched";
		}

		
	// checking id exists in today or not

		if($check_exists == TRUE && $pattern_check == "pattern_matched"){
			$this->output->set_output(json_encode(array('Status' => 'You already generated OTP for today please call to Command Center.')));
		}elseif($pattern_check == "pattern_not_matched"){
			$this->output->set_output(json_encode(array('Status' => 'Sorry your ID pattern not matched, please call to Command Center.')));
		}else{

			$doc_data = array('Student Name' => $name,
							'Hospital Unique ID'=> $uid,
							'District'=> $dist,
							'School Name' => $scl,
							'Mobile No' => $mobile,
							'Otp' => $otp,
							'Date' => $date,
							'Status' => 1);

			// History
			$approval_data = array(
				"current_stage" => "stage1",
				"approval" => "true",
				"submitted_by" => $mobile,
	            'raised_by' => "Device_Side",
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;

			$insert = $this->healthsupervisor_app_model->register_otp_for_parents_health($doc_data, $history);

			if(!empty($insert))
			 	{
			 		$this->output->set_output(json_encode(array('Status' => 'Successfully submitted! You will Receive a call from Command Center')));
			 	}else{
			 		$this->output->set_output(json_encode(array('Status' => 'Failed')));
			 	}

		}
		
		

	}
// End  Students parents Detailscapturing register process

//Submitting family details

	public function submit_family_health_information()
	{
		$post = isset($_POST) ? $_POST : "";
		//log_message('debug', "post data checking before decode".print_r($post, true));
		//$post_data = json_decode($post, true);
		$post_data = $post;

		
		//log_message('error', "post data checking after decode".print_r($post_data, true));

		if($post_data){
			$doc_data['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = $post_data['UID'];
			$doc_data['widget_data']['page1']['Personal Information']['Name'] = (isset($post_data['STUDENT_NAME'])) ? $post_data['STUDENT_NAME'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '+91';
			$doc_data['widget_data']['page1']['Personal Information']['Mobile'] ['mob_num'] = (isset($post_data['MOBILE'])) ? $post_data['MOBILE'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = (isset($post_data['DATEOFBIRTH'])) ? $post_data['DATEOFBIRTH'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Gender'] = (isset($post_data['GENDER'])) ? $post_data['GENDER'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Aadhaar No'] = (isset($post_data['ADNO'])) ? $post_data['ADNO'] : "";//AADHAR NO
			$doc_data['widget_data']['page1']['Personal Information']['School Name'] = (isset($post_data['SCHOOLNAME'])) ? $post_data['SCHOOLNAME'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['District'] = (isset($post_data['DISTRICT'])) ? $post_data['DISTRICT'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Class'] = (isset($post_data['CLASS'])) ? $post_data['CLASS'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Father Name'] = (isset($post_data['FATHERNAME'])) ? $post_data['FATHERNAME'] : "";
			$doc_data['widget_data']['page1']['Personal Information']['Date of Submission'] = date('Y-m-d');//DATE_OF_SUBMISSION
			
			/*'Total Family No'=> (isset($post_data['TOTAL_FAMILY_COUNT'])) ? $post_data['TOTAL_FAMILY_COUNT'] : "",*/
			$count = array(
							'No Of Sisters' => (isset($post_data['TOTAL_SISTERS'])) ? $post_data['TOTAL_SISTERS'] : "",
							'No Of Brothers'=> (isset($post_data['TOTAL_BROTHERS'])) ? $post_data['TOTAL_BROTHERS'] : ""
							
			);

			$father_ques = array(
								'health issues'=> (isset($post_data['FATHER_HEALTH_ISSUES'])) ? explode(',', $post_data['FATHER_HEALTH_ISSUES']): ""
								
							);

			$father = array('Father Name'=> (isset($post_data['FATHER_NAME'])) ? $post_data['FATHER_NAME'] : "",
							'Age' => (isset($post_data['FATHER_AGE'])) ? $post_data['FATHER_AGE'] : "",
							'Medical Data'=> $father_ques,
							'Others Issues' => (isset($post_data['FATHER_OTHER_ISSUES'])) ? $post_data['FATHER_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['FATHER_DESCRIPTION'])) ? $post_data['FATHER_DESCRIPTION'] : ""
			);

			$mother_ques = array(
								'health issues'=> (isset($post_data['MOTHER_HEALTH_ISSUES'])) ? explode(',', $post_data['MOTHER_HEALTH_ISSUES']): ""
								
							);

			$mother = array('Mother Name'=> (isset($post_data['MOTHER_NAME'])) ? $post_data['MOTHER_NAME'] : "",
							'Age' => (isset($post_data['MOTHER_AGE'])) ? $post_data['MOTHER_AGE'] : "",
							'Medical Data'=> $mother_ques,
							'Others Issues' => (isset($post_data['MOTHER_OTHER_ISSUES'])) ? $post_data['MOTHER_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['MOTHER_DESCRIPTION'])) ? $post_data['MOTHER_DESCRIPTION'] : ""
			);

			
			$sister1_ques = array(
								'health issues'=> (isset($post_data['SISTER1_HEALTH_ISSUES'])) ? explode(',', $post_data['SISTER1_HEALTH_ISSUES']): ""
								
							);

			$sister2_ques = array(
								'health issues'=> (isset($post_data['SISTER2_HEALTH_ISSUES'])) ? explode(',', $post_data['SISTER2_HEALTH_ISSUES']): ""
								
							);

			$sister3_ques = array(
								'health issues'=> (isset($post_data['SISTER3_HEALTH_ISSUES'])) ? explode(',', $post_data['SISTER3_HEALTH_ISSUES']): ""
								
							);

			$sister4_ques = array(
								'health issues'=> (isset($post_data['SISTER4_HEALTH_ISSUES'])) ? explode(',', $post_data['SISTER4_HEALTH_ISSUES']): ""
								
							);
			$sister5_ques = array(
								'health issues'=> (isset($post_data['SISTER5_HEALTH_ISSUES'])) ? explode(',', $post_data['SISTER5_HEALTH_ISSUES']): ""
								
							);



			$sister1 = array('Sister Name'=> (isset($post_data['SISTER1_NAME'])) ? $post_data['SISTER1_NAME'] : "",
							'Age' => (isset($post_data['SISTER1_AGE'])) ? $post_data['SISTER1_AGE'] : "",
							'Medical Data'=> $sister1_ques,
							'Others Issues' => (isset($post_data['SISTER1_OTHER_ISSUES'])) ? $post_data['SISTER1_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['SISTER1_DESCRIPTION'])) ? $post_data['SISTER1_DESCRIPTION'] : ""
			);

			$sister2 = array('Sister Name'=> (isset($post_data['SISTER2_NAME'])) ? $post_data['SISTER2_NAME'] : "",
							'Age' => (isset($post_data['SISTER2_AGE'])) ? $post_data['SISTER2_AGE'] : "",
							'Medical Data'=> $sister2_ques,
							'Others Issues' => (isset($post_data['SISTER2_OTHER_ISSUES'])) ? $post_data['SISTER2_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['SISTER2_DESCRIPTION'])) ? $post_data['SISTER2_DESCRIPTION'] : ""
			);

			$sister3 = array('Sister Name'=> (isset($post_data['SISTER3_NAME'])) ? $post_data['SISTER3_NAME'] : "",
							'Age' => (isset($post_data['SISTER3_AGE'])) ? $post_data['SISTER3_AGE'] : "",
							'Medical Data'=> $sister3_ques,
							'Others Issues' => (isset($post_data['SISTER3_OTHER_ISSUES'])) ? $post_data['SISTER3_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['SISTER3_DESCRIPTION'])) ? $post_data['SISTER3_DESCRIPTION'] : ""
			);

			$sister4 = array('Sister Name'=> (isset($post_data['SISTER4_NAME'])) ? $post_data['SISTER4_NAME'] : "",
							'Age' => (isset($post_data['SISTER4_AGE'])) ? $post_data['SISTER4_AGE'] : "",
							'Medical Data'=> $sister4_ques,
							'Others Issues' => (isset($post_data['SISTER4_OTHER_ISSUES'])) ? $post_data['SISTER4_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['SISTER4_DESCRIPTION'])) ? $post_data['SISTER4_DESCRIPTION'] : ""
			);

			$sister5 = array('Sister Name'=> (isset($post_data['SISTER5_NAME'])) ? $post_data['SISTER5_NAME'] : "",
							'Age' => (isset($post_data['SISTER5_AGE'])) ? $post_data['SISTER5_AGE'] : "",
							'Medical Data'=> $sister5_ques,
							'Others Issues' => (isset($post_data['SISTER5_OTHER_ISSUES'])) ? $post_data['SISTER5_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['SISTER5_DESCRIPTION'])) ? $post_data['SISTER5_DESCRIPTION'] : ""
			);

			$brother1_ques = array(
								'health issues'=> (isset($post_data['BROTHER1_HEALTH_ISSUES'])) ? explode(',', $post_data['BROTHER1_HEALTH_ISSUES']): ""
							);

			$brother2_ques = array(
								'health issues'=> (isset($post_data['BROTHER2_HEALTH_ISSUES'])) ? explode(',', $post_data['BROTHER2_HEALTH_ISSUES']): ""
								
							);

			$brother3_ques = array(
								'health issues'=> (isset($post_data['BROTHER3_HEALTH_ISSUES'])) ? explode(',', $post_data['BROTHER3_HEALTH_ISSUES']): ""
								
							);

			$brother4_ques = array(
								'health issues'=> (isset($post_data['BROTHER4_HEALTH_ISSUES'])) ? explode(',', $post_data['BROTHER4_HEALTH_ISSUES']): ""
								
							);

			$brother5_ques = array(
								'health issues'=> (isset($post_data['BROTHER5_HEALTH_ISSUES'])) ? explode(',', $post_data['BROTHER5_HEALTH_ISSUES']): ""
								
							);

			$brother1 = array('Brother Name'=> (isset($post_data['BROTHER1_NAME'])) ? $post_data['BROTHER1_NAME'] : "",
							'Age' => (isset($post_data['BROTHER1_AGE'])) ? $post_data['BROTHER1_AGE'] : "",
							'Medical Data'=> $brother1_ques,
							'Others Issues' => (isset($post_data['BROTHER1_OTHER_ISSUES'])) ? $post_data['BROTHER1_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['BROTHER1_DESCRIPTION'])) ? $post_data['BROTHER1_DESCRIPTION'] : ""
			);

			$brother2 = array('Brother Name'=> (isset($post_data['BROTHER2_NAME'])) ? $post_data['BROTHER2_NAME'] : "",
							'Age' => (isset($post_data['BROTHER2_AGE'])) ? $post_data['BROTHER2_AGE'] : "",
							'Medical Data'=> $brother2_ques,
							'Others Issues' => (isset($post_data['BROTHER2_OTHER_ISSUES'])) ? $post_data['BROTHER2_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['BROTHER2_DESCRIPTION'])) ? $post_data['BROTHER2_DESCRIPTION'] : ""
			);

			$brother3 = array('Brother Name'=> (isset($post_data['BROTHER3_NAME'])) ? $post_data['BROTHER3_NAME'] : "",
							'Age' => (isset($post_data['BROTHER3_AGE'])) ? $post_data['BROTHER3_AGE'] : "",
							'Medical Data'=> $brother3_ques,
							'Others Issues' => (isset($post_data['BROTHER3_OTHER_ISSUES'])) ? $post_data['BROTHER3_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['BROTHER3_DESCRIPTION'])) ? $post_data['BROTHER3_DESCRIPTION'] : ""
			);

			$brother4 = array('Brother Name'=> (isset($post_data['BROTHER4_NAME'])) ? $post_data['BROTHER4_NAME'] : "",
							'Age' => (isset($post_data['BROTHER4_AGE'])) ? $post_data['BROTHER4_AGE'] : "",
							'Medical Data'=> $brother4_ques,
							'Others Issues' => (isset($post_data['BROTHER4_OTHER_ISSUES'])) ? $post_data['BROTHER4_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['BROTHER4_DESCRIPTION'])) ? $post_data['BROTHER4_DESCRIPTION'] : ""
			);

			$brother5 = array('Brother Name'=> (isset($post_data['BROTHER5_NAME'])) ? $post_data['BROTHER5_NAME'] : "",
							'Age' => (isset($post_data['BROTHER5_AGE'])) ? $post_data['BROTHER5_AGE'] : "",
							'Medical Data'=> $brother5_ques,
							'Others Issues' => (isset($post_data['BROTHER5_OTHER_ISSUES'])) ? $post_data['BROTHER5_OTHER_ISSUES'] :"",
							'Description'=> (isset($post_data['BROTHER5_DESCRIPTION'])) ? $post_data['BROTHER5_DESCRIPTION'] : ""
			);
			
			$doc_data['widget_data']['page2']['Family Health Info']['Family Counts'] = !empty($count) ? $count : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Father Data'] = !empty($father) ? $father : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Mother Data'] = !empty($mother) ? $mother : [];
			//$doc_data['widget_data']['page2']['Family Health Info']['Guardian Data'] = !empty($guardian) ? $guardian : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1'] = !empty($sister1) ? $sister1 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2'] = !empty($sister2) ? $sister2 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3'] = !empty($sister3) ? $sister3 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4'] = !empty($sister4) ? $sister4 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5'] = !empty($sister5) ? $sister5 : [];

			$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1'] = !empty($brother1) ? $brother1 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2'] = !empty($brother2) ? $brother2 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3'] = !empty($brother3) ? $brother3 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4'] = !empty($brother4) ? $brother4 : [];
			$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5'] = !empty($brother5) ? $brother5 : [];


			 // Doc properties
			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
		    $doc_properties["doc_owner"] = "PANACEA";
	        $doc_properties["doc_flow"] = "new";
			// History
			$approval_data = array(
				"current_stage" => "stage1",
				"doc_owner" => "Panacea schools",
				"submitted_by" => (isset($post_data['UID'])) ? $post_data['UID']:'',
				"time" => date('Y-m-d H:i:s'),
				"approval" => "true",
				'synced_date' => date('Y-m-d'),
	            "Version" => "1.0");

			$history['last_stage'] = $approval_data;

			$app_properties['app_name'] = "Family Health Registration";
			$app_properties['app_id'] = "students_family_health_registration";


			//Attachments Data
				if(isset($_FILES) && !empty($_FILES))
				{
					//log_message('debug', "post data family attachments time check".print_r($_FILES, true));

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $father_external_files = array();
				   $father_external_final = array();
				   $father_external_merged_data = array();

				   $mother_external_files = array();
				   $mother_external_final = array();
				   $mother_external_merged_data = array();

				   $sister1_external_files = array();
				   $sister1_external_final = array();
				   $sister1_external_merged_data = array();

				   $sister2_external_files = array();
				   $sister2_external_final = array();
				   $sister2_external_merged_data = array();

				   $sister3_external_files = array();
				   $sister3_external_final = array();
				   $sister3_external_merged_data = array();


				   $sister4_external_files = array();
				   $sister4_external_final = array();
				   $sister4_external_merged_data = array();

				   $sister5_external_files = array();
				   $sister5_external_final = array();
				   $sister5_external_merged_data = array();

				   $brother1_external_files = array();
				   $brother1_external_final = array();
				   $brother1_external_merged_data = array();

				   $brother2_external_files = array();
				   $brother2_external_final = array();
				   $brother2_external_merged_data = array();

				   $brother3_external_files = array();
				   $brother3_external_final = array();
				   $brother3_external_merged_data = array();

				   $brother4_external_files = array();
				   $brother4_external_final = array();
				   $brother4_external_merged_data = array();

				   $brother5_external_files = array();
				   $brother5_external_final = array();
				   $brother5_external_merged_data = array();

				 

				   if(isset($_FILES['father_attachments']['name']) && !empty($_FILES['father_attachments']['name']))
				   {
				   	   $files = $_FILES;

					   $cpt = count($_FILES['father_attachments']['name']);
					    
					   for($i=0; $i<$cpt; $i++)
					   {
						 $_FILES['father_attachments']['name']	= $files['father_attachments']['name'][$i];
						 $_FILES['father_attachments']['type']	= $files['father_attachments']['type'][$i];
						 $_FILES['father_attachments']['tmp_name'] = $files['father_attachments']['tmp_name'][$i];
						 $_FILES['father_attachments']['error']	= $files['father_attachments']['error'][$i];
						 $_FILES['father_attachments']['size']	= $files['father_attachments']['size'][$i];
						
						   foreach ($_FILES as $index => $value)
					       {			       
					       
					       		if(!empty($value['name'] && $index == 'father_attachments'))
							  	{
							        $config = array();
									$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
									$config['allowed_types'] = '*';
									$config['max_size']      = '*';
									$config['encrypt_name']  = TRUE;
							  	
							        //create controller upload folder if not exists
									if (!is_dir($config['upload_path']))
									{
										mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
									}
						
									$this->upload->initialize($config);
									
									if ( ! $this->upload->do_upload($index))
									{
										 echo "external file upload failed";
						        		 return FALSE;
									}
									else
									{
										$father_external_files = $this->upload->data();
										//log_message('debug', 'father_external_files=======5849'.print_r($father_external_files, true));
										$rand_number = mt_rand();
										$external_screening_data_array = array(
																"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
																"file_name" =>$father_external_files['file_name'],
																"file_path" =>$father_external_files['file_relative_path'],
																"file_size" =>$father_external_files['file_size']
															)	);

										$father_external_final = array_merge($father_external_final,$external_screening_data_array);
										
									}  
								}
							}
						}
					 

					 //$doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] = $father_external_final;
					  if(isset($doc_data['father_attachments']))
						  {
								   
							$father_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'],$father_external_final);
							$doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'],$father_external_merged_data);
						  }
						  else
						 {
						    $doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] = $father_external_final;
						 }
				   	}else
					   {
					   		$doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] = [];
					   }



				// Mother Attachments
					   	      if(isset($_FILES['mother_attachments']['name']) && !empty($_FILES['mother_attachments']['name']))
					   	      {
					   	      	   $files = $_FILES;
					   	   	   $cpt = count($_FILES['mother_attachments']['name']);
					   	   	    
					   	   	   for($i=0; $i<$cpt; $i++)
					   	   	   {
					   	   		 $_FILES['mother_attachments']['name']	= $files['mother_attachments']['name'][$i];
					   	   		 $_FILES['mother_attachments']['type']	= $files['mother_attachments']['type'][$i];
					   	   		 $_FILES['mother_attachments']['tmp_name'] = $files['mother_attachments']['tmp_name'][$i];
					   	   		 $_FILES['mother_attachments']['error']	= $files['mother_attachments']['error'][$i];
					   	   		 $_FILES['mother_attachments']['size']	= $files['mother_attachments']['size'][$i];
					   	   		
					   	   		   foreach ($_FILES as $index => $value)
					   	   	       {			       
					   	   	       
					   	   	       		if(!empty($value['name'] && $index == 'mother_attachments'))
					   	   			  	{
					   	   			        $config = array();
					   	   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					   	   					$config['allowed_types'] = '*';
					   	   					$config['max_size']      = '*';
					   	   					$config['encrypt_name']  = TRUE;
					   	   			  	
					   	   			        //create controller upload folder if not exists
					   	   					if (!is_dir($config['upload_path']))
					   	   					{
					   	   						mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					   	   					}
					   	   		
					   	   					$this->upload->initialize($config);
					   	   					
					   	   					if ( ! $this->upload->do_upload($index))
					   	   					{
					   	   						 echo "external file upload failed";
					   	   		        		 return FALSE;
					   	   					}
					   	   					else
					   	   					{
					   	   						$mother_external_files = $this->upload->data();
					   	   						//log_message('debug', 'mother_external_files=======5849'.print_r($mother_external_files, true));
					   	   						$rand_number = mt_rand();
					   	   						$external_mother_data_array = array(
					   	   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					   	   												"file_name" =>$mother_external_files['file_name'],
					   	   												"file_path" =>$mother_external_files['file_relative_path'],
					   	   												"file_size" =>$mother_external_files['file_size']
					   	   											)	);

					   	   						$mother_external_final = array_merge($mother_external_final,$external_mother_data_array);
					   	   						
					   	   					}  
					   	   				}
					   	   			}
					   	   		}
					   	   	 

					   	   	 //$doc_data['widget_data']['page2']['Family Health Info']['mother Data']['attachments'] = $mother_external_final;
					   	   	  if(isset($doc_data['mother_attachments']))
					   	   		  {
					   	   				   
					   	   			$mother_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'],$mother_external_final);
					   	   			$doc_data['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'],$mother_external_merged_data);
					   	   		  }
					   	   		  else
					   	   		 {
					   	   		    $doc_data['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'] = $mother_external_final;
					   	   		 }
					   	      	}else
					   	   	   {
					   	   	   		$doc_data['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'] = [];
					   	   	   }

				
				// Sisters Attchements

					  //sister Attachments
					  	   	  if(isset($_FILES['sister1_attachments']['name']) && !empty($_FILES['sister1_attachments']['name']))
					  	   	   {
					  	   	   	   $files = $_FILES;
					  	   		   $cpt = count($_FILES['sister1_attachments']['name']);
					  	   		    
					  	   		   for($i=0; $i<$cpt; $i++)
					  	   		   {
					  	   			 $_FILES['sister1_attachments']['name']	= $files['sister1_attachments']['name'][$i];
					  	   			 $_FILES['sister1_attachments']['type']	= $files['sister1_attachments']['type'][$i];
					  	   			 $_FILES['sister1_attachments']['tmp_name'] = $files['sister1_attachments']['tmp_name'][$i];
					  	   			 $_FILES['sister1_attachments']['error']	= $files['sister1_attachments']['error'][$i];
					  	   			 $_FILES['sister1_attachments']['size']	= $files['sister1_attachments']['size'][$i];
					  	   			
					  	   			   foreach ($_FILES as $index => $value)
					  	   		       {			       
					  	   		       
					  	   		       		if(!empty($value['name'] && $index == 'sister1_attachments'))
					  	   				  	{
					  	   				        $config = array();
					  	   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  	   						$config['allowed_types'] = '*';
					  	   						$config['max_size']      = '*';
					  	   						$config['encrypt_name']  = TRUE;
					  	   				  	
					  	   				        //create controller upload folder if not exists
					  	   						if (!is_dir($config['upload_path']))
					  	   						{
					  	   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  	   						}
					  	   			
					  	   						$this->upload->initialize($config);
					  	   						
					  	   						if ( ! $this->upload->do_upload($index))
					  	   						{
					  	   							 echo "external file upload failed";
					  	   			        		 return FALSE;
					  	   						}
					  	   						else
					  	   						{
					  	   							$sister1_external_files = $this->upload->data();
					  	   							//log_message('debug', 'sister1_external_files=======5849'.print_r($sister1_external_files, true));
					  	   							$rand_number = mt_rand();
					  	   							$external_sister1_data_array = array(
					  	   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  	   													"file_name" =>$sister1_external_files['file_name'],
					  	   													"file_path" =>$sister1_external_files['file_relative_path'],
					  	   													"file_size" =>$sister1_external_files['file_size']
					  	   												)	);

					  	   							$sister1_external_final = array_merge($sister1_external_final,$external_sister1_data_array);
					  	   							
					  	   						}  
					  	   					}
					  	   				}
					  	   			}
					  	   		 

					  	   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister1 Data']['attachments'] = $sister1_external_final;
					  	   		  if(isset($doc_data['sister1_attachments']))
					  	   			  {
					  	   					   
					  	   				$sister1_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1']['attachments'],$sister1_external_final);
					  	   				$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1']['attachments'],$sister1_external_merged_data);
					  	   			  }
					  	   			  else
					  	   			 {
					  	   			    $doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1']['attachments'] = $sister1_external_final;
					  	   			 }
					  	   	   	}else
					  	   		   {
					  	   		   		$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister1']['attachments'] = [];
					  	   		   }

					 //end sister 1

					  	//sister Attachments
					  		   	  if(isset($_FILES['sister2_attachments']['name']) && !empty($_FILES['sister2_attachments']['name']))
					  		   	   {
					  		   	   	   $files = $_FILES;
					  		   		   $cpt = count($_FILES['sister2_attachments']['name']);
					  		   		    
					  		   		   for($i=0; $i<$cpt; $i++)
					  		   		   {
					  		   			 $_FILES['sister2_attachments']['name']	= $files['sister2_attachments']['name'][$i];
					  		   			 $_FILES['sister2_attachments']['type']	= $files['sister2_attachments']['type'][$i];
					  		   			 $_FILES['sister2_attachments']['tmp_name'] = $files['sister2_attachments']['tmp_name'][$i];
					  		   			 $_FILES['sister2_attachments']['error']	= $files['sister2_attachments']['error'][$i];
					  		   			 $_FILES['sister2_attachments']['size']	= $files['sister2_attachments']['size'][$i];
					  		   			
					  		   			   foreach ($_FILES as $index => $value)
					  		   		       {			       
					  		   		       
					  		   		       		if(!empty($value['name'] && $index == 'sister2_attachments'))
					  		   				  	{
					  		   				        $config = array();
					  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  		   						$config['allowed_types'] = '*';
					  		   						$config['max_size']      = '*';
					  		   						$config['encrypt_name']  = TRUE;
					  		   				  	
					  		   				        //create controller upload folder if not exists
					  		   						if (!is_dir($config['upload_path']))
					  		   						{
					  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  		   						}
					  		   			
					  		   						$this->upload->initialize($config);
					  		   						
					  		   						if ( ! $this->upload->do_upload($index))
					  		   						{
					  		   							 echo "external file upload failed";
					  		   			        		 return FALSE;
					  		   						}
					  		   						else
					  		   						{
					  		   							$sister2_external_files = $this->upload->data();
					  		   							//log_message('debug', 'sister2_external_files=======5849'.print_r($sister2_external_files, true));
					  		   							$rand_number = mt_rand();
					  		   							$external_sister2_data_array = array(
					  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  		   													"file_name" =>$sister2_external_files['file_name'],
					  		   													"file_path" =>$sister2_external_files['file_relative_path'],
					  		   													"file_size" =>$sister2_external_files['file_size']
					  		   												)	);

					  		   							$sister2_external_final = array_merge($sister2_external_final,$external_sister2_data_array);
					  		   							
					  		   						}  
					  		   					}
					  		   				}
					  		   			}
					  		   		 

					  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister2 Data']['attachments'] = $sister2_external_final;
					  		   		  if(isset($doc_data['sister2_attachments']))
					  		   			  {
					  		   					   
					  		   				$sister2_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2']['attachments'],$sister2_external_final);
					  		   				$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2']['attachments'],$sister2_external_merged_data);
					  		   			  }
					  		   			  else
					  		   			 {
					  		   			    $doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2']['attachments'] = $sister2_external_final;
					  		   			 }
					  		   	   	}else
					  		   		   {
					  		   		   		$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister2']['attachments'] = [];
					  		   		   }

					  	//End sister2

					  	//sister Attachments
					  		   	  if(isset($_FILES['sister3_attachments']['name']) && !empty($_FILES['sister3_attachments']['name']))
					  		   	   {
					  		   	   	   $files = $_FILES;
					  		   		   $cpt = count($_FILES['sister3_attachments']['name']);
					  		   		    
					  		   		   for($i=0; $i<$cpt; $i++)
					  		   		   {
					  		   			 $_FILES['sister3_attachments']['name']	= $files['sister3_attachments']['name'][$i];
					  		   			 $_FILES['sister3_attachments']['type']	= $files['sister3_attachments']['type'][$i];
					  		   			 $_FILES['sister3_attachments']['tmp_name'] = $files['sister3_attachments']['tmp_name'][$i];
					  		   			 $_FILES['sister3_attachments']['error']	= $files['sister3_attachments']['error'][$i];
					  		   			 $_FILES['sister3_attachments']['size']	= $files['sister3_attachments']['size'][$i];
					  		   			
					  		   			   foreach ($_FILES as $index => $value)
					  		   		       {			       
					  		   		       
					  		   		       		if(!empty($value['name'] && $index == 'sister3_attachments'))
					  		   				  	{
					  		   				        $config = array();
					  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  		   						$config['allowed_types'] = '*';
					  		   						$config['max_size']      = '*';
					  		   						$config['encrypt_name']  = TRUE;
					  		   				  	
					  		   				        //create controller upload folder if not exists
					  		   						if (!is_dir($config['upload_path']))
					  		   						{
					  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  		   						}
					  		   			
					  		   						$this->upload->initialize($config);
					  		   						
					  		   						if ( ! $this->upload->do_upload($index))
					  		   						{
					  		   							 echo "external file upload failed";
					  		   			        		 return FALSE;
					  		   						}
					  		   						else
					  		   						{
					  		   							$sister3_external_files = $this->upload->data();
					  		   							//log_message('debug', 'sister3_external_files=======5849'.print_r($sister3_external_files, true));
					  		   							$rand_number = mt_rand();
					  		   							$external_sister3_data_array = array(
					  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  		   													"file_name" =>$sister3_external_files['file_name'],
					  		   													"file_path" =>$sister3_external_files['file_relative_path'],
					  		   													"file_size" =>$sister3_external_files['file_size']
					  		   												)	);

					  		   							$sister3_external_final = array_merge($sister3_external_final,$external_sister3_data_array);
					  		   							
					  		   						}  
					  		   					}
					  		   				}
					  		   			}
					  		   		 

					  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister3 Data']['attachments'] = $sister3_external_final;
					  		   		  if(isset($doc_data['sister3_attachments']))
					  		   			  {
					  		   					   
					  		   				$sister3_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3']['attachments'],$sister3_external_final);
					  		   				$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3']['attachments'],$sister3_external_merged_data);
					  		   			  }
					  		   			  else
					  		   			 {
					  		   			    $doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3']['attachments'] = $sister3_external_final;
					  		   			 }
					  		   	   	}else
					  		   		   {
					  		   		   		$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister3']['attachments'] = [];
					  		   		   }

					  	//End sister3
					//sister Attachments
						   	  if(isset($_FILES['sister4_attachments']['name']) && !empty($_FILES['sister4_attachments']['name']))
						   	   {
						   	   	   $files = $_FILES;
						   		   $cpt = count($_FILES['sister4_attachments']['name']);
						   		    
						   		   for($i=0; $i<$cpt; $i++)
						   		   {
						   			 $_FILES['sister4_attachments']['name']	= $files['sister4_attachments']['name'][$i];
						   			 $_FILES['sister4_attachments']['type']	= $files['sister4_attachments']['type'][$i];
						   			 $_FILES['sister4_attachments']['tmp_name'] = $files['sister4_attachments']['tmp_name'][$i];
						   			 $_FILES['sister4_attachments']['error']	= $files['sister4_attachments']['error'][$i];
						   			 $_FILES['sister4_attachments']['size']	= $files['sister4_attachments']['size'][$i];
						   			
						   			   foreach ($_FILES as $index => $value)
						   		       {			       
						   		       
						   		       		if(!empty($value['name'] && $index == 'sister4_attachments'))
						   				  	{
						   				        $config = array();
						   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						   						$config['allowed_types'] = '*';
						   						$config['max_size']      = '*';
						   						$config['encrypt_name']  = TRUE;
						   				  	
						   				        //create controller upload folder if not exists
						   						if (!is_dir($config['upload_path']))
						   						{
						   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						   						}
						   			
						   						$this->upload->initialize($config);
						   						
						   						if ( ! $this->upload->do_upload($index))
						   						{
						   							 echo "external file upload failed";
						   			        		 return FALSE;
						   						}
						   						else
						   						{
						   							$sister4_external_files = $this->upload->data();
						   							//log_message('debug', 'sister4_external_files=======5849'.print_r($sister4_external_files, true));
						   							$rand_number = mt_rand();
						   							$external_sister4_data_array = array(
						   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						   													"file_name" =>$sister4_external_files['file_name'],
						   													"file_path" =>$sister4_external_files['file_relative_path'],
						   													"file_size" =>$sister4_external_files['file_size']
						   												)	);

						   							$sister4_external_final = array_merge($sister4_external_final,$external_sister4_data_array);
						   							
						   						}  
						   					}
						   				}
						   			}
						   		 

						   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister4 Data']['attachments'] = $sister4_external_final;
						   		  if(isset($doc_data['sister4_attachments']))
						   			  {
						   					   
						   				$sister4_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4']['attachments'],$sister4_external_final);
						   				$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4']['attachments'],$sister4_external_merged_data);
						   			  }
						   			  else
						   			 {
						   			    $doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4']['attachments'] = $sister4_external_final;
						   			 }
						   	   	}else
						   		   {
						   		   		$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister4']['attachments'] = [];
						   		   }

					//End sister4
					//sister Attachments
						   	  if(isset($_FILES['sister5_attachments']['name']) && !empty($_FILES['sister5_attachments']['name']))
						   	   {
						   	   	   $files = $_FILES;
						   		   $cpt = count($_FILES['sister5_attachments']['name']);
						   		    
						   		   for($i=0; $i<$cpt; $i++)
						   		   {
						   			 $_FILES['sister5_attachments']['name']	= $files['sister5_attachments']['name'][$i];
						   			 $_FILES['sister5_attachments']['type']	= $files['sister5_attachments']['type'][$i];
						   			 $_FILES['sister5_attachments']['tmp_name'] = $files['sister5_attachments']['tmp_name'][$i];
						   			 $_FILES['sister5_attachments']['error']	= $files['sister5_attachments']['error'][$i];
						   			 $_FILES['sister5_attachments']['size']	= $files['sister5_attachments']['size'][$i];
						   			
						   			   foreach ($_FILES as $index => $value)
						   		       {			       
						   		       
						   		       		if(!empty($value['name'] && $index == 'sister5_attachments'))
						   				  	{
						   				        $config = array();
						   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						   						$config['allowed_types'] = '*';
						   						$config['max_size']      = '*';
						   						$config['encrypt_name']  = TRUE;
						   				  	
						   				        //create controller upload folder if not exists
						   						if (!is_dir($config['upload_path']))
						   						{
						   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						   						}
						   			
						   						$this->upload->initialize($config);
						   						
						   						if ( ! $this->upload->do_upload($index))
						   						{
						   							 echo "external file upload failed";
						   			        		 return FALSE;
						   						}
						   						else
						   						{
						   							$sister5_external_files = $this->upload->data();
						   							//log_message('debug', 'sister5_external_files=======5849'.print_r($sister5_external_files, true));
						   							$rand_number = mt_rand();
						   							$external_sister5_data_array = array(
						   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						   													"file_name" =>$sister5_external_files['file_name'],
						   													"file_path" =>$sister5_external_files['file_relative_path'],
						   													"file_size" =>$sister5_external_files['file_size']
						   												)	);

						   							$sister5_external_final = array_merge($sister5_external_final,$external_sister5_data_array);
						   							
						   						}  
						   					}
						   				}
						   			}
						   		 

						   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister5 Data']['attachments'] = $sister5_external_final;
						   		  if(isset($doc_data['sister5_attachments']))
						   			  {
						   					   
						   				$sister5_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5']['attachments'],$sister5_external_final);
						   				$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5']['attachments'],$sister5_external_merged_data);
						   			  }
						   			  else
						   			 {
						   			    $doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5']['attachments'] = $sister5_external_final;
						   			 }
						   	   	}else
						   		   {
						   		   		$doc_data['widget_data']['page2']['Family Health Info']['Sisters Data']['Sister5']['attachments'] = [];
						   		   }

					//End sister 5
				// End Sisters attachment

		// Brother Attchemtns

			// Brothers Attchements

				  //brothers Attachments
				  	   	  if(isset($_FILES['brother1_attachments']['name']) && !empty($_FILES['brother1_attachments']['name']))
				  	   	   {
				  	   	   	   $files = $_FILES;
				  	   		   $cpt = count($_FILES['brother1_attachments']['name']);
				  	   		    
				  	   		   for($i=0; $i<$cpt; $i++)
				  	   		   {
				  	   			 $_FILES['brother1_attachments']['name']	= $files['brother1_attachments']['name'][$i];
				  	   			 $_FILES['brother1_attachments']['type']	= $files['brother1_attachments']['type'][$i];
				  	   			 $_FILES['brother1_attachments']['tmp_name'] = $files['brother1_attachments']['tmp_name'][$i];
				  	   			 $_FILES['brother1_attachments']['error']	= $files['brother1_attachments']['error'][$i];
				  	   			 $_FILES['brother1_attachments']['size']	= $files['brother1_attachments']['size'][$i];
				  	   			
				  	   			   foreach ($_FILES as $index => $value)
				  	   		       {			       
				  	   		       
				  	   		       		if(!empty($value['name'] && $index == 'brother1_attachments'))
				  	   				  	{
				  	   				        $config = array();
				  	   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
				  	   						$config['allowed_types'] = '*';
				  	   						$config['max_size']      = '*';
				  	   						$config['encrypt_name']  = TRUE;
				  	   				  	
				  	   				        //create controller upload folder if not exists
				  	   						if (!is_dir($config['upload_path']))
				  	   						{
				  	   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
				  	   						}
				  	   			
				  	   						$this->upload->initialize($config);
				  	   						
				  	   						if ( ! $this->upload->do_upload($index))
				  	   						{
				  	   							 echo "external file upload failed";
				  	   			        		 return FALSE;
				  	   						}
				  	   						else
				  	   						{
				  	   							$brother1_external_files = $this->upload->data();
				  	   							//log_message('debug', 'brother1_external_files=======5849'.print_r($brother1_external_files, true));
				  	   							$rand_number = mt_rand();
				  	   							$external_brother1_data_array = array(
				  	   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				  	   													"file_name" =>$brother1_external_files['file_name'],
				  	   													"file_path" =>$brother1_external_files['file_relative_path'],
				  	   													"file_size" =>$brother1_external_files['file_size']
				  	   												)	);

				  	   							$brother1_external_final = array_merge($brother1_external_final,$external_brother1_data_array);
				  	   							
				  	   						}  
				  	   					}
				  	   				}
				  	   			}
				  	   		 

				  	   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother1 Data']['attachments'] = $brother1_external_final;
				  	   		  if(isset($doc_data['brother1_attachments']))
				  	   			  {
				  	   					   
				  	   				$brother1_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1']['attachments'],$brother1_external_final);
				  	   				$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1']['attachments'],$brother1_external_merged_data);
				  	   			  }
				  	   			  else
				  	   			 {
				  	   			    $doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1']['attachments'] = $brother1_external_final;
				  	   			 }
				  	   	   	}else
				  	   		   {
				  	   		   		$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother1']['attachments'] = [];
				  	   		   }

				
				  	//brothers Attachments
				  		   	  if(isset($_FILES['brother2_attachments']['name']) && !empty($_FILES['brother2_attachments']['name']))
				  		   	   {
				  		   	   	   $files = $_FILES;
				  		   		   $cpt = count($_FILES['brother2_attachments']['name']);
				  		   		    
				  		   		   for($i=0; $i<$cpt; $i++)
				  		   		   {
				  		   			 $_FILES['brother2_attachments']['name']	= $files['brother2_attachments']['name'][$i];
				  		   			 $_FILES['brother2_attachments']['type']	= $files['brother2_attachments']['type'][$i];
				  		   			 $_FILES['brother2_attachments']['tmp_name'] = $files['brother2_attachments']['tmp_name'][$i];
				  		   			 $_FILES['brother2_attachments']['error']	= $files['brother2_attachments']['error'][$i];
				  		   			 $_FILES['brother2_attachments']['size']	= $files['brother2_attachments']['size'][$i];
				  		   			
				  		   			   foreach ($_FILES as $index => $value)
				  		   		       {			       
				  		   		       
				  		   		       		if(!empty($value['name'] && $index == 'brother2_attachments'))
				  		   				  	{
				  		   				        $config = array();
				  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
				  		   						$config['allowed_types'] = '*';
				  		   						$config['max_size']      = '*';
				  		   						$config['encrypt_name']  = TRUE;
				  		   				  	
				  		   				        //create controller upload folder if not exists
				  		   						if (!is_dir($config['upload_path']))
				  		   						{
				  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
				  		   						}
				  		   			
				  		   						$this->upload->initialize($config);
				  		   						
				  		   						if ( ! $this->upload->do_upload($index))
				  		   						{
				  		   							 echo "external file upload failed";
				  		   			        		 return FALSE;
				  		   						}
				  		   						else
				  		   						{
				  		   							$brother2_external_files = $this->upload->data();
				  		   							//log_message('debug', 'brother2_external_files=======5849'.print_r($brother2_external_files, true));
				  		   							$rand_number = mt_rand();
				  		   							$external_brother2_data_array = array(
				  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				  		   													"file_name" =>$brother2_external_files['file_name'],
				  		   													"file_path" =>$brother2_external_files['file_relative_path'],
				  		   													"file_size" =>$brother2_external_files['file_size']
				  		   												)	);

				  		   							$brother2_external_final = array_merge($brother2_external_final,$external_brother2_data_array);
				  		   							
				  		   						}  
				  		   					}
				  		   				}
				  		   			}
				  		   		 

				  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother2 Data']['attachments'] = $brother2_external_final;
				  		   		  if(isset($doc_data['brother2_attachments']))
				  		   			  {
				  		   					   
				  		   				$brother2_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2']['attachments'],$brother2_external_final);
				  		   				$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2']['attachments'],$brother2_external_merged_data);
				  		   			  }
				  		   			  else
				  		   			 {
				  		   			    $doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2']['attachments'] = $brother2_external_final;
				  		   			 }
				  		   	   	}else
				  		   		   {
				  		   		   		$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother2']['attachments'] = [];
				  		   		   }

				  	

				  	//brothers Attachments
				  		   	  if(isset($_FILES['brother3_attachments']['name']) && !empty($_FILES['brother3_attachments']['name']))
				  		   	   {
				  		   	   	   $files = $_FILES;
				  		   		   $cpt = count($_FILES['brother3_attachments']['name']);
				  		   		    
				  		   		   for($i=0; $i<$cpt; $i++)
				  		   		   {
				  		   			 $_FILES['brother3_attachments']['name']	= $files['brother3_attachments']['name'][$i];
				  		   			 $_FILES['brother3_attachments']['type']	= $files['brother3_attachments']['type'][$i];
				  		   			 $_FILES['brother3_attachments']['tmp_name'] = $files['brother3_attachments']['tmp_name'][$i];
				  		   			 $_FILES['brother3_attachments']['error']	= $files['brother3_attachments']['error'][$i];
				  		   			 $_FILES['brother3_attachments']['size']	= $files['brother3_attachments']['size'][$i];
				  		   			
				  		   			   foreach ($_FILES as $index => $value)
				  		   		       {			       
				  		   		       
				  		   		       		if(!empty($value['name'] && $index == 'brother3_attachments'))
				  		   				  	{
				  		   				        $config = array();
				  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
				  		   						$config['allowed_types'] = '*';
				  		   						$config['max_size']      = '*';
				  		   						$config['encrypt_name']  = TRUE;
				  		   				  	
				  		   				        //create controller upload folder if not exists
				  		   						if (!is_dir($config['upload_path']))
				  		   						{
				  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
				  		   						}
				  		   			
				  		   						$this->upload->initialize($config);
				  		   						
				  		   						if ( ! $this->upload->do_upload($index))
				  		   						{
				  		   							 echo "external file upload failed";
				  		   			        		 return FALSE;
				  		   						}
				  		   						else
				  		   						{
				  		   							$brother3_external_files = $this->upload->data();
				  		   							//log_message('debug', 'brother3_external_files=======5849'.print_r($brother3_external_files, true));
				  		   							$rand_number = mt_rand();
				  		   							$external_brother3_data_array = array(
				  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				  		   													"file_name" =>$brother3_external_files['file_name'],
				  		   													"file_path" =>$brother3_external_files['file_relative_path'],
				  		   													"file_size" =>$brother3_external_files['file_size']
				  		   												)	);

				  		   							$brother3_external_final = array_merge($brother3_external_final,$external_brother3_data_array);
				  		   							
				  		   						}  
				  		   					}
				  		   				}
				  		   			}
				  		   		 

				  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother3 Data']['attachments'] = $brother3_external_final;
				  		   		  if(isset($doc_data['brother3_attachments']))
				  		   			  {
				  		   					   
				  		   				$brother3_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3']['attachments'],$brother3_external_final);
				  		   				$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3']['attachments'],$brother3_external_merged_data);
				  		   			  }
				  		   			  else
				  		   			 {
				  		   			    $doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3']['attachments'] = $brother3_external_final;
				  		   			 }
				  		   	   	}else
				  		   		   {
				  		   		   		$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother3']['attachments'] = [];
				  		   		   }

				  	
					   	  if(isset($_FILES['brother4_attachments']['name']) && !empty($_FILES['brother4_attachments']['name']))
					   	   {
					   	   	   $files = $_FILES;
					   		   $cpt = count($_FILES['brother4_attachments']['name']);
					   		    
					   		   for($i=0; $i<$cpt; $i++)
					   		   {
					   			 $_FILES['brother4_attachments']['name']	= $files['brother4_attachments']['name'][$i];
					   			 $_FILES['brother4_attachments']['type']	= $files['brother4_attachments']['type'][$i];
					   			 $_FILES['brother4_attachments']['tmp_name'] = $files['brother4_attachments']['tmp_name'][$i];
					   			 $_FILES['brother4_attachments']['error']	= $files['brother4_attachments']['error'][$i];
					   			 $_FILES['brother4_attachments']['size']	= $files['brother4_attachments']['size'][$i];
					   			
					   			   foreach ($_FILES as $index => $value)
					   		       {			       
					   		       
					   		       		if(!empty($value['name'] && $index == 'brother4_attachments'))
					   				  	{
					   				        $config = array();
					   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					   						$config['allowed_types'] = '*';
					   						$config['max_size']      = '*';
					   						$config['encrypt_name']  = TRUE;
					   				  	
					   				        //create controller upload folder if not exists
					   						if (!is_dir($config['upload_path']))
					   						{
					   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					   						}
					   			
					   						$this->upload->initialize($config);
					   						
					   						if ( ! $this->upload->do_upload($index))
					   						{
					   							 echo "external file upload failed";
					   			        		 return FALSE;
					   						}
					   						else
					   						{
					   							$brother4_external_files = $this->upload->data();
					   							//log_message('debug', 'brother4_external_files=======5849'.print_r($brother4_external_files, true));
					   							$rand_number = mt_rand();
					   							$external_brother4_data_array = array(
					   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					   													"file_name" =>$brother4_external_files['file_name'],
					   													"file_path" =>$brother4_external_files['file_relative_path'],
					   													"file_size" =>$brother4_external_files['file_size']
					   												)	);

					   							$brother4_external_final = array_merge($brother4_external_final,$external_brother4_data_array);
					   							
					   						}  
					   					}
					   				}
					   			}
					   		 

					   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother4 Data']['attachments'] = $brother4_external_final;
					   		  if(isset($doc_data['brother4_attachments']))
					   			  {
					   					   
					   				$brother4_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4']['attachments'],$brother4_external_final);
					   				$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4']['attachments'],$brother4_external_merged_data);
					   			  }
					   			  else
					   			 {
					   			    $doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4']['attachments'] = $brother4_external_final;
					   			 }
					   	   	}else
					   		   {
					   		   		$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother4']['attachments'] = [];
					   		   }

				
					   	  if(isset($_FILES['brother5_attachments']['name']) && !empty($_FILES['brother5_attachments']['name']))
					   	   {
					   	   	   $files = $_FILES;
					   		   $cpt = count($_FILES['brother5_attachments']['name']);
					   		    
					   		   for($i=0; $i<$cpt; $i++)
					   		   {
					   			 $_FILES['brother5_attachments']['name']	= $files['brother5_attachments']['name'][$i];
					   			 $_FILES['brother5_attachments']['type']	= $files['brother5_attachments']['type'][$i];
					   			 $_FILES['brother5_attachments']['tmp_name'] = $files['brother5_attachments']['tmp_name'][$i];
					   			 $_FILES['brother5_attachments']['error']	= $files['brother5_attachments']['error'][$i];
					   			 $_FILES['brother5_attachments']['size']	= $files['brother5_attachments']['size'][$i];
					   			
					   			   foreach ($_FILES as $index => $value)
					   		       {			       
					   		       
					   		       		if(!empty($value['name'] && $index == 'brother5_attachments'))
					   				  	{
					   				        $config = array();
					   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					   						$config['allowed_types'] = '*';
					   						$config['max_size']      = '*';
					   						$config['encrypt_name']  = TRUE;
					   				  	
					   				        //create controller upload folder if not exists
					   						if (!is_dir($config['upload_path']))
					   						{
					   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					   						}
					   			
					   						$this->upload->initialize($config);
					   						
					   						if ( ! $this->upload->do_upload($index))
					   						{
					   							 echo "external file upload failed";
					   			        		 return FALSE;
					   						}
					   						else
					   						{
					   							$brother5_external_files = $this->upload->data();
					   							//log_message('debug', 'brother5_external_files=======5849'.print_r($brother5_external_files, true));
					   							$rand_number = mt_rand();
					   							$external_brother5_data_array = array(
					   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					   													"file_name" =>$brother5_external_files['file_name'],
					   													"file_path" =>$brother5_external_files['file_relative_path'],
					   													"file_size" =>$brother5_external_files['file_size']
					   												)	);

					   							$brother5_external_final = array_merge($brother5_external_final,$external_brother5_data_array);
					   							
					   						}  
					   					}
					   				}
					   			}
					   		 

					   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
					   		  if(isset($doc_data['brother5_attachments']))
					   			  {
					   					   
					   				$brother5_external_merged_data = array_merge($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5']['attachments'],$brother5_external_final);
					   				$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5']['attachments'] = array_replace_recursive($doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5']['attachments'],$brother5_external_merged_data);
					   			  }
					   			  else
					   			 {
					   			    $doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5']['attachments'] = $brother5_external_final;
					   			 }
					   	   	}else
					   		   {
					   		   		$doc_data['widget_data']['page2']['Family Health Info']['Brothers Data']['Brother5']['attachments'] = [];
					   		   }

			
		// End Brother Attchemtns




	}

			//End Attchments data


			$insert_data = $this->healthsupervisor_app_model->submit_family_health_information($doc_data, $history, $app_properties, $doc_properties);

			//log_message('debug', 'check responce in controller family time check'.print_r($insert_data, true));

			if(!empty($insert_data))
		 	{
		 		$this->output->set_output(json_encode(array('Status' => 'Successfully submitted!')));
		 	}else{
		 		$this->output->set_output(json_encode(array('Status' => 'Failed')));
		 	}
		}


	}
//End Submitting family details

	public function update_family_health_attachements()
	{
		$post = $_POST;

		$doc_id = $post['doc_id'];
		$unique = $post['unique_id'];


				//Attachments Data
					if(isset($_FILES) && !empty($_FILES))
					{

					   $this->load->library('upload');
					   $this->load->library('image_lib');
					   
					   $father_external_files = array();
					   $father_external_final = array();
					   $father_external_merged_data = array();

					   $mother_external_files = array();
					   $mother_external_final = array();
					   $mother_external_merged_data = array();

					   $sister1_external_files = array();
					   $sister1_external_final = array();
					   $sister1_external_merged_data = array();

					   $sister2_external_files = array();
					   $sister2_external_final = array();
					   $sister2_external_merged_data = array();

					   $sister3_external_files = array();
					   $sister3_external_final = array();
					   $sister3_external_merged_data = array();


					   $sister4_external_files = array();
					   $sister4_external_final = array();
					   $sister4_external_merged_data = array();

					   $sister5_external_files = array();
					   $sister5_external_final = array();
					   $sister5_external_merged_data = array();

					   $brother1_external_files = array();
					   $brother1_external_final = array();
					   $brother1_external_merged_data = array();

					   $brother2_external_files = array();
					   $brother2_external_final = array();
					   $brother2_external_merged_data = array();

					   $brother3_external_files = array();
					   $brother3_external_final = array();
					   $brother3_external_merged_data = array();

					   $brother4_external_files = array();
					   $brother4_external_final = array();
					   $brother4_external_merged_data = array();

					   $brother5_external_files = array();
					   $brother5_external_final = array();
					   $brother5_external_merged_data = array();

					 

					   if(isset($_FILES['father_attachments']['name']) && !empty($_FILES['father_attachments']['name']))
					   {
					   	   $files = $_FILES;
						   $cpt = count($_FILES['father_attachments']['name']);
						    
						   for($i=0; $i<$cpt; $i++)
						   {
							 $_FILES['father_attachments']['name']	= $files['father_attachments']['name'][$i];
							 $_FILES['father_attachments']['type']	= $files['father_attachments']['type'][$i];
							 $_FILES['father_attachments']['tmp_name'] = $files['father_attachments']['tmp_name'][$i];
							 $_FILES['father_attachments']['error']	= $files['father_attachments']['error'][$i];
							 $_FILES['father_attachments']['size']	= $files['father_attachments']['size'][$i];
							
							   foreach ($_FILES as $index => $value)
						       {			       
						       
						       		if(!empty($value['name'] && $index == 'father_attachments'))
								  	{
								        $config = array();
										$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
										$config['allowed_types'] = '*';
										$config['max_size']      = '*';
										$config['encrypt_name']  = TRUE;
								  	
								        //create controller upload folder if not exists
										if (!is_dir($config['upload_path']))
										{
											mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
										}
							
										$this->upload->initialize($config);
										
										if ( ! $this->upload->do_upload($index))
										{
											 echo "external file upload failed";
							        		 return FALSE;
										}
										else
										{
											$father_external_files = $this->upload->data();
											//log_message('debug', 'father_external_files=======5849'.print_r($father_external_files, true));
											$rand_number = mt_rand();
											$external_screening_data_array = array(
																	"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
																	"file_name" =>$father_external_files['file_name'],
																	"file_path" =>$father_external_files['file_relative_path'],
																	"file_size" =>$father_external_files['file_size']
																)	);

											$father_external_final = array_merge($father_external_final,$external_screening_data_array);
											
										}  
									}
								}
							}
						 

						 //$doc_data['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] = $father_external_final;
						  if(isset($doc_data['father_attachments']))
							  {
									   
								$father_external_merged_data = array_merge($doc_data['father_attach_update'],$father_external_final);
								$doc_data['father_attach_update'] = array_replace_recursive($doc_data['father_attach_update'],$father_external_merged_data);
							  }
							  else
							 {
							    $doc_data['father_attach_update'] = $father_external_final;
							 }
					   	}else
						   {
						   		$doc_data['father_attach_update'] = [];
						   }



					// Mother Attachments
						   	      if(isset($_FILES['mother_attachments']['name']) && !empty($_FILES['mother_attachments']['name']))
						   	      {
						   	      	   $files = $_FILES;
						   	   	   $cpt = count($_FILES['mother_attachments']['name']);
						   	   	    
						   	   	   for($i=0; $i<$cpt; $i++)
						   	   	   {
						   	   		 $_FILES['mother_attachments']['name']	= $files['mother_attachments']['name'][$i];
						   	   		 $_FILES['mother_attachments']['type']	= $files['mother_attachments']['type'][$i];
						   	   		 $_FILES['mother_attachments']['tmp_name'] = $files['mother_attachments']['tmp_name'][$i];
						   	   		 $_FILES['mother_attachments']['error']	= $files['mother_attachments']['error'][$i];
						   	   		 $_FILES['mother_attachments']['size']	= $files['mother_attachments']['size'][$i];
						   	   		
						   	   		   foreach ($_FILES as $index => $value)
						   	   	       {			       
						   	   	       
						   	   	       		if(!empty($value['name'] && $index == 'mother_attachments'))
						   	   			  	{
						   	   			        $config = array();
						   	   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						   	   					$config['allowed_types'] = '*';
						   	   					$config['max_size']      = '*';
						   	   					$config['encrypt_name']  = TRUE;
						   	   			  	
						   	   			        //create controller upload folder if not exists
						   	   					if (!is_dir($config['upload_path']))
						   	   					{
						   	   						mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						   	   					}
						   	   		
						   	   					$this->upload->initialize($config);
						   	   					
						   	   					if ( ! $this->upload->do_upload($index))
						   	   					{
						   	   						 echo "external file upload failed";
						   	   		        		 return FALSE;
						   	   					}
						   	   					else
						   	   					{
						   	   						$mother_external_files = $this->upload->data();
						   	   						//log_message('debug', 'mother_external_files=======5849'.print_r($mother_external_files, true));
						   	   						$rand_number = mt_rand();
						   	   						$external_mother_data_array = array(
						   	   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						   	   												"file_name" =>$mother_external_files['file_name'],
						   	   												"file_path" =>$mother_external_files['file_relative_path'],
						   	   												"file_size" =>$mother_external_files['file_size']
						   	   											)	);

						   	   						$mother_external_final = array_merge($mother_external_final,$external_mother_data_array);
						   	   						
						   	   					}  
						   	   				}
						   	   			}
						   	   		}
						   	   	 

						   	   	 //$doc_data['widget_data']['page2']['Family Health Info']['mother Data']['attachments'] = $mother_external_final;
						   	   	  if(isset($doc_data['mother_attachments']))
						   	   		  {
						   	   				   
						   	   			$mother_external_merged_data = array_merge($doc_data['mother_attach_update'],$mother_external_final);
						   	   			$doc_data['mother_attach_update'] = array_replace_recursive($doc_data['mother_attach_update'],$mother_external_merged_data);
						   	   		  }
						   	   		  else
						   	   		 {
						   	   		    $doc_data['mother_attach_update'] = $mother_external_final;
						   	   		 }
						   	      	}else
						   	   	   {
						   	   	   		$doc_data['mother_attach_update'] = [];
						   	   	   }


					// Sisters Attchements

						  //sister Attachments
						  	   	  if(isset($_FILES['sister1_attachments']['name']) && !empty($_FILES['sister1_attachments']['name']))
						  	   	   {
						  	   	   	   $files = $_FILES;
						  	   		   $cpt = count($_FILES['sister1_attachments']['name']);
						  	   		    
						  	   		   for($i=0; $i<$cpt; $i++)
						  	   		   {
						  	   			 $_FILES['sister1_attachments']['name']	= $files['sister1_attachments']['name'][$i];
						  	   			 $_FILES['sister1_attachments']['type']	= $files['sister1_attachments']['type'][$i];
						  	   			 $_FILES['sister1_attachments']['tmp_name'] = $files['sister1_attachments']['tmp_name'][$i];
						  	   			 $_FILES['sister1_attachments']['error']	= $files['sister1_attachments']['error'][$i];
						  	   			 $_FILES['sister1_attachments']['size']	= $files['sister1_attachments']['size'][$i];
						  	   			
						  	   			   foreach ($_FILES as $index => $value)
						  	   		       {			       
						  	   		       
						  	   		       		if(!empty($value['name'] && $index == 'sister1_attachments'))
						  	   				  	{
						  	   				        $config = array();
						  	   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						  	   						$config['allowed_types'] = '*';
						  	   						$config['max_size']      = '*';
						  	   						$config['encrypt_name']  = TRUE;
						  	   				  	
						  	   				        //create controller upload folder if not exists
						  	   						if (!is_dir($config['upload_path']))
						  	   						{
						  	   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						  	   						}
						  	   			
						  	   						$this->upload->initialize($config);
						  	   						
						  	   						if ( ! $this->upload->do_upload($index))
						  	   						{
						  	   							 echo "external file upload failed";
						  	   			        		 return FALSE;
						  	   						}
						  	   						else
						  	   						{
						  	   							$sister1_external_files = $this->upload->data();
						  	   							//log_message('debug', 'sister1_external_files=======5849'.print_r($sister1_external_files, true));
						  	   							$rand_number = mt_rand();
						  	   							$external_sister1_data_array = array(
						  	   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						  	   													"file_name" =>$sister1_external_files['file_name'],
						  	   													"file_path" =>$sister1_external_files['file_relative_path'],
						  	   													"file_size" =>$sister1_external_files['file_size']
						  	   												)	);

						  	   							$sister1_external_final = array_merge($sister1_external_final,$external_sister1_data_array);
						  	   							
						  	   						}  
						  	   					}
						  	   				}
						  	   			}
						  	   		 

						  	   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister1 Data']['attachments'] = $sister1_external_final;
						  	   		  if(isset($doc_data['sister1_attachments']))
						  	   			  {
						  	   					   
						  	   				$sister1_external_merged_data = array_merge($doc_data['sister1_attach_update'],$sister1_external_final);
						  	   				$doc_data['sister1_attach_update'] = array_replace_recursive($doc_data['sister1_attach_update'],$sister1_external_merged_data);
						  	   			  }
						  	   			  else
						  	   			 {
						  	   			    $doc_data['sister1_attach_update'] = $sister1_external_final;
						  	   			 }
						  	   	   	}else
						  	   		   {
						  	   		   		$doc_data['sister1_attach_update'] = [];
						  	   		   }

						 //end sister 1

						  	//sister Attachments
						  		   	  if(isset($_FILES['sister2_attachments']['name']) && !empty($_FILES['sister2_attachments']['name']))
						  		   	   {
						  		   	   	   $files = $_FILES;
						  		   		   $cpt = count($_FILES['sister2_attachments']['name']);
						  		   		    
						  		   		   for($i=0; $i<$cpt; $i++)
						  		   		   {
						  		   			 $_FILES['sister2_attachments']['name']	= $files['sister2_attachments']['name'][$i];
						  		   			 $_FILES['sister2_attachments']['type']	= $files['sister2_attachments']['type'][$i];
						  		   			 $_FILES['sister2_attachments']['tmp_name'] = $files['sister2_attachments']['tmp_name'][$i];
						  		   			 $_FILES['sister2_attachments']['error']	= $files['sister2_attachments']['error'][$i];
						  		   			 $_FILES['sister2_attachments']['size']	= $files['sister2_attachments']['size'][$i];
						  		   			
						  		   			   foreach ($_FILES as $index => $value)
						  		   		       {			       
						  		   		       
						  		   		       		if(!empty($value['name'] && $index == 'sister2_attachments'))
						  		   				  	{
						  		   				        $config = array();
						  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						  		   						$config['allowed_types'] = '*';
						  		   						$config['max_size']      = '*';
						  		   						$config['encrypt_name']  = TRUE;
						  		   				  	
						  		   				        //create controller upload folder if not exists
						  		   						if (!is_dir($config['upload_path']))
						  		   						{
						  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						  		   						}
						  		   			
						  		   						$this->upload->initialize($config);
						  		   						
						  		   						if ( ! $this->upload->do_upload($index))
						  		   						{
						  		   							 echo "external file upload failed";
						  		   			        		 return FALSE;
						  		   						}
						  		   						else
						  		   						{
						  		   							$sister2_external_files = $this->upload->data();
						  		   							//log_message('debug', 'sister2_external_files=======5849'.print_r($sister2_external_files, true));
						  		   							$rand_number = mt_rand();
						  		   							$external_sister2_data_array = array(
						  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						  		   													"file_name" =>$sister2_external_files['file_name'],
						  		   													"file_path" =>$sister2_external_files['file_relative_path'],
						  		   													"file_size" =>$sister2_external_files['file_size']
						  		   												)	);

						  		   							$sister2_external_final = array_merge($sister2_external_final,$external_sister2_data_array);
						  		   							
						  		   						}  
						  		   					}
						  		   				}
						  		   			}
						  		   		 

						  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister2 Data']['attachments'] = $sister2_external_final;
						  		   		  if(isset($doc_data['sister2_attachments']))
						  		   			  {
						  		   					   
						  		   				$sister2_external_merged_data = array_merge($doc_data['sister2_attach_update'],$sister2_external_final);
						  		   				$doc_data['sister2_attach_update'] = array_replace_recursive($doc_data['sister2_attach_update'],$sister2_external_merged_data);
						  		   			  }
						  		   			  else
						  		   			 {
						  		   			    $doc_data['sister2_attach_update'] = $sister2_external_final;
						  		   			 }
						  		   	   	}else
						  		   		   {
						  		   		   		$doc_data['sister2_attach_update'] = [];
						  		   		   }

						  	//End sister2

						  	//sister Attachments
						  		   	  if(isset($_FILES['sister3_attachments']['name']) && !empty($_FILES['sister3_attachments']['name']))
						  		   	   {
						  		   	   	   $files = $_FILES;
						  		   		   $cpt = count($_FILES['sister3_attachments']['name']);
						  		   		    
						  		   		   for($i=0; $i<$cpt; $i++)
						  		   		   {
						  		   			 $_FILES['sister3_attachments']['name']	= $files['sister3_attachments']['name'][$i];
						  		   			 $_FILES['sister3_attachments']['type']	= $files['sister3_attachments']['type'][$i];
						  		   			 $_FILES['sister3_attachments']['tmp_name'] = $files['sister3_attachments']['tmp_name'][$i];
						  		   			 $_FILES['sister3_attachments']['error']	= $files['sister3_attachments']['error'][$i];
						  		   			 $_FILES['sister3_attachments']['size']	= $files['sister3_attachments']['size'][$i];
						  		   			
						  		   			   foreach ($_FILES as $index => $value)
						  		   		       {			       
						  		   		       
						  		   		       		if(!empty($value['name'] && $index == 'sister3_attachments'))
						  		   				  	{
						  		   				        $config = array();
						  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						  		   						$config['allowed_types'] = '*';
						  		   						$config['max_size']      = '*';
						  		   						$config['encrypt_name']  = TRUE;
						  		   				  	
						  		   				        //create controller upload folder if not exists
						  		   						if (!is_dir($config['upload_path']))
						  		   						{
						  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						  		   						}
						  		   			
						  		   						$this->upload->initialize($config);
						  		   						
						  		   						if ( ! $this->upload->do_upload($index))
						  		   						{
						  		   							 echo "external file upload failed";
						  		   			        		 return FALSE;
						  		   						}
						  		   						else
						  		   						{
						  		   							$sister3_external_files = $this->upload->data();
						  		   							//log_message('debug', 'sister3_external_files=======5849'.print_r($sister3_external_files, true));
						  		   							$rand_number = mt_rand();
						  		   							$external_sister3_data_array = array(
						  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						  		   													"file_name" =>$sister3_external_files['file_name'],
						  		   													"file_path" =>$sister3_external_files['file_relative_path'],
						  		   													"file_size" =>$sister3_external_files['file_size']
						  		   												)	);

						  		   							$sister3_external_final = array_merge($sister3_external_final,$external_sister3_data_array);
						  		   							
						  		   						}  
						  		   					}
						  		   				}
						  		   			}
						  		   		 

						  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister3 Data']['attachments'] = $sister3_external_final;
						  		   		  if(isset($doc_data['sister3_attachments']))
						  		   			  {
						  		   					   
						  		   				$sister3_external_merged_data = array_merge($doc_data['sister3_attach_update'],$sister3_external_final);
						  		   				$doc_data['sister3_attach_update'] = array_replace_recursive($doc_data['sister3_attach_update'],$sister3_external_merged_data);
						  		   			  }
						  		   			  else
						  		   			 {
						  		   			    $doc_data['sister3_attach_update'] = $sister3_external_final;
						  		   			 }
						  		   	   	}else
						  		   		   {
						  		   		   		$doc_data['sister3_attach_update'] = [];
						  		   		   }

						  	//End sister3
						//sister Attachments
							   	  if(isset($_FILES['sister4_attachments']['name']) && !empty($_FILES['sister4_attachments']['name']))
							   	   {
							   	   	   $files = $_FILES;
							   		   $cpt = count($_FILES['sister4_attachments']['name']);
							   		    
							   		   for($i=0; $i<$cpt; $i++)
							   		   {
							   			 $_FILES['sister4_attachments']['name']	= $files['sister4_attachments']['name'][$i];
							   			 $_FILES['sister4_attachments']['type']	= $files['sister4_attachments']['type'][$i];
							   			 $_FILES['sister4_attachments']['tmp_name'] = $files['sister4_attachments']['tmp_name'][$i];
							   			 $_FILES['sister4_attachments']['error']	= $files['sister4_attachments']['error'][$i];
							   			 $_FILES['sister4_attachments']['size']	= $files['sister4_attachments']['size'][$i];
							   			
							   			   foreach ($_FILES as $index => $value)
							   		       {			       
							   		       
							   		       		if(!empty($value['name'] && $index == 'sister4_attachments'))
							   				  	{
							   				        $config = array();
							   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
							   						$config['allowed_types'] = '*';
							   						$config['max_size']      = '*';
							   						$config['encrypt_name']  = TRUE;
							   				  	
							   				        //create controller upload folder if not exists
							   						if (!is_dir($config['upload_path']))
							   						{
							   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
							   						}
							   			
							   						$this->upload->initialize($config);
							   						
							   						if ( ! $this->upload->do_upload($index))
							   						{
							   							 echo "external file upload failed";
							   			        		 return FALSE;
							   						}
							   						else
							   						{
							   							$sister4_external_files = $this->upload->data();
							   							//log_message('debug', 'sister4_external_files=======5849'.print_r($sister4_external_files, true));
							   							$rand_number = mt_rand();
							   							$external_sister4_data_array = array(
							   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
							   													"file_name" =>$sister4_external_files['file_name'],
							   													"file_path" =>$sister4_external_files['file_relative_path'],
							   													"file_size" =>$sister4_external_files['file_size']
							   												)	);

							   							$sister4_external_final = array_merge($sister4_external_final,$external_sister4_data_array);
							   							
							   						}  
							   					}
							   				}
							   			}
							   		 

							   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister4 Data']['attachments'] = $sister4_external_final;
							   		  if(isset($doc_data['sister4_attachments']))
							   			  {
							   					   
							   				$sister4_external_merged_data = array_merge($doc_data['sister4_attach_update'],$sister4_external_final);
							   				$doc_data['sister4_attach_update'] = array_replace_recursive($doc_data['sister4_attach_update'],$sister4_external_merged_data);
							   			  }
							   			  else
							   			 {
							   			    $doc_data['sister4_attach_update'] = $sister4_external_final;
							   			 }
							   	   	}else
							   		   {
							   		   		$doc_data['sister4_attach_update'] = [];
							   		   }

						//End sister4
						//sister Attachments
							   	  if(isset($_FILES['sister5_attachments']['name']) && !empty($_FILES['sister5_attachments']['name']))
							   	   {
							   	   	   $files = $_FILES;
							   		   $cpt = count($_FILES['sister5_attachments']['name']);
							   		    
							   		   for($i=0; $i<$cpt; $i++)
							   		   {
							   			 $_FILES['sister5_attachments']['name']	= $files['sister5_attachments']['name'][$i];
							   			 $_FILES['sister5_attachments']['type']	= $files['sister5_attachments']['type'][$i];
							   			 $_FILES['sister5_attachments']['tmp_name'] = $files['sister5_attachments']['tmp_name'][$i];
							   			 $_FILES['sister5_attachments']['error']	= $files['sister5_attachments']['error'][$i];
							   			 $_FILES['sister5_attachments']['size']	= $files['sister5_attachments']['size'][$i];
							   			
							   			   foreach ($_FILES as $index => $value)
							   		       {			       
							   		       
							   		       		if(!empty($value['name'] && $index == 'sister5_attachments'))
							   				  	{
							   				        $config = array();
							   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
							   						$config['allowed_types'] = '*';
							   						$config['max_size']      = '*';
							   						$config['encrypt_name']  = TRUE;
							   				  	
							   				        //create controller upload folder if not exists
							   						if (!is_dir($config['upload_path']))
							   						{
							   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
							   						}
							   			
							   						$this->upload->initialize($config);
							   						
							   						if ( ! $this->upload->do_upload($index))
							   						{
							   							 echo "external file upload failed";
							   			        		 return FALSE;
							   						}
							   						else
							   						{
							   							$sister5_external_files = $this->upload->data();
							   							//log_message('debug', 'sister5_external_files=======5849'.print_r($sister5_external_files, true));
							   							$rand_number = mt_rand();
							   							$external_sister5_data_array = array(
							   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
							   													"file_name" =>$sister5_external_files['file_name'],
							   													"file_path" =>$sister5_external_files['file_relative_path'],
							   													"file_size" =>$sister5_external_files['file_size']
							   												)	);

							   							$sister5_external_final = array_merge($sister5_external_final,$external_sister5_data_array);
							   							
							   						}  
							   					}
							   				}
							   			}
							   		 

							   		 //$doc_data['widget_data']['page2']['Family Health Info']['sister5 Data']['attachments'] = $sister5_external_final;
							   		  if(isset($doc_data['sister5_attachments']))
							   			  {
							   					   
							   				$sister5_external_merged_data = array_merge($doc_data['sister5_attach_update'],$sister5_external_final);
							   				$doc_data['sister5_attach_update'] = array_replace_recursive($doc_data['sister5_attach_update'],$sister5_external_merged_data);
							   			  }
							   			  else
							   			 {
							   			    $doc_data['sister5_attach_update'] = $sister5_external_final;
							   			 }
							   	   	}else
							   		   {
							   		   		$doc_data['sister5_attach_update'] = [];
							   		   }

						//End sister 5
					// End Sisters attachment

			// Brother Attchemtns

				// Brothers Attchements

					  //brothers Attachments
					  	   	  if(isset($_FILES['brother1_attachments']['name']) && !empty($_FILES['brother1_attachments']['name']))
					  	   	   {
					  	   	   	   $files = $_FILES;
					  	   		   $cpt = count($_FILES['brother1_attachments']['name']);
					  	   		    
					  	   		   for($i=0; $i<$cpt; $i++)
					  	   		   {
					  	   			 $_FILES['brother1_attachments']['name']	= $files['brother1_attachments']['name'][$i];
					  	   			 $_FILES['brother1_attachments']['type']	= $files['brother1_attachments']['type'][$i];
					  	   			 $_FILES['brother1_attachments']['tmp_name'] = $files['brother1_attachments']['tmp_name'][$i];
					  	   			 $_FILES['brother1_attachments']['error']	= $files['brother1_attachments']['error'][$i];
					  	   			 $_FILES['brother1_attachments']['size']	= $files['brother1_attachments']['size'][$i];
					  	   			
					  	   			   foreach ($_FILES as $index => $value)
					  	   		       {			       
					  	   		       
					  	   		       		if(!empty($value['name'] && $index == 'brother1_attachments'))
					  	   				  	{
					  	   				        $config = array();
					  	   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  	   						$config['allowed_types'] = '*';
					  	   						$config['max_size']      = '*';
					  	   						$config['encrypt_name']  = TRUE;
					  	   				  	
					  	   				        //create controller upload folder if not exists
					  	   						if (!is_dir($config['upload_path']))
					  	   						{
					  	   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  	   						}
					  	   			
					  	   						$this->upload->initialize($config);
					  	   						
					  	   						if ( ! $this->upload->do_upload($index))
					  	   						{
					  	   							 echo "external file upload failed";
					  	   			        		 return FALSE;
					  	   						}
					  	   						else
					  	   						{
					  	   							$brother1_external_files = $this->upload->data();
					  	   							//log_message('debug', 'brother1_external_files=======5849'.print_r($brother1_external_files, true));
					  	   							$rand_number = mt_rand();
					  	   							$external_brother1_data_array = array(
					  	   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  	   													"file_name" =>$brother1_external_files['file_name'],
					  	   													"file_path" =>$brother1_external_files['file_relative_path'],
					  	   													"file_size" =>$brother1_external_files['file_size']
					  	   												)	);

					  	   							$brother1_external_final = array_merge($brother1_external_final,$external_brother1_data_array);
					  	   							
					  	   						}  
					  	   					}
					  	   				}
					  	   			}
					  	   		 

					  	   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother1 Data']['attachments'] = $brother1_external_final;
					  	   		  if(isset($doc_data['brother1_attachments']))
					  	   			  {
					  	   					   
					  	   				$brother1_external_merged_data = array_merge($doc_data['brother1_attach_update'],$brother1_external_final);
					  	   				$doc_data['brother1_attach_update'] = array_replace_recursive($doc_data['brother1_attach_update'],$brother1_external_merged_data);
					  	   			  }
					  	   			  else
					  	   			 {
					  	   			    $doc_data['brother1_attach_update'] = $brother1_external_final;
					  	   			 }
					  	   	   	}else
					  	   		   {
					  	   		   		$doc_data['brother1_attach_update'] = [];
					  	   		   }

					
					  	//brothers Attachments
					  		   	  if(isset($_FILES['brother2_attachments']['name']) && !empty($_FILES['brother2_attachments']['name']))
					  		   	   {
					  		   	   	   $files = $_FILES;
					  		   		   $cpt = count($_FILES['brother2_attachments']['name']);
					  		   		    
					  		   		   for($i=0; $i<$cpt; $i++)
					  		   		   {
					  		   			 $_FILES['brother2_attachments']['name']	= $files['brother2_attachments']['name'][$i];
					  		   			 $_FILES['brother2_attachments']['type']	= $files['brother2_attachments']['type'][$i];
					  		   			 $_FILES['brother2_attachments']['tmp_name'] = $files['brother2_attachments']['tmp_name'][$i];
					  		   			 $_FILES['brother2_attachments']['error']	= $files['brother2_attachments']['error'][$i];
					  		   			 $_FILES['brother2_attachments']['size']	= $files['brother2_attachments']['size'][$i];
					  		   			
					  		   			   foreach ($_FILES as $index => $value)
					  		   		       {			       
					  		   		       
					  		   		       		if(!empty($value['name'] && $index == 'brother2_attachments'))
					  		   				  	{
					  		   				        $config = array();
					  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  		   						$config['allowed_types'] = '*';
					  		   						$config['max_size']      = '*';
					  		   						$config['encrypt_name']  = TRUE;
					  		   				  	
					  		   				        //create controller upload folder if not exists
					  		   						if (!is_dir($config['upload_path']))
					  		   						{
					  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  		   						}
					  		   			
					  		   						$this->upload->initialize($config);
					  		   						
					  		   						if ( ! $this->upload->do_upload($index))
					  		   						{
					  		   							 echo "external file upload failed";
					  		   			        		 return FALSE;
					  		   						}
					  		   						else
					  		   						{
					  		   							$brother2_external_files = $this->upload->data();
					  		   							//log_message('debug', 'brother2_external_files=======5849'.print_r($brother2_external_files, true));
					  		   							$rand_number = mt_rand();
					  		   							$external_brother2_data_array = array(
					  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  		   													"file_name" =>$brother2_external_files['file_name'],
					  		   													"file_path" =>$brother2_external_files['file_relative_path'],
					  		   													"file_size" =>$brother2_external_files['file_size']
					  		   												)	);

					  		   							$brother2_external_final = array_merge($brother2_external_final,$external_brother2_data_array);
					  		   							
					  		   						}  
					  		   					}
					  		   				}
					  		   			}
					  		   		 

					  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother2 Data']['attachments'] = $brother2_external_final;
					  		   		  if(isset($doc_data['brother2_attachments']))
					  		   			  {
					  		   					   
					  		   				$brother2_external_merged_data = array_merge($doc_data['brother2_attach_update'],$brother2_external_final);
					  		   				$doc_data['brother2_attach_update'] = array_replace_recursive($doc_data['brother2_attach_update'],$brother2_external_merged_data);
					  		   			  }
					  		   			  else
					  		   			 {
					  		   			    $doc_data['brother2_attach_update'] = $brother2_external_final;
					  		   			 }
					  		   	   	}else
					  		   		   {
					  		   		   		$doc_data['brother2_attach_update'] = [];
					  		   		   }

					  	

					  	//brothers Attachments
					  		   	  if(isset($_FILES['brother3_attachments']['name']) && !empty($_FILES['brother3_attachments']['name']))
					  		   	   {
					  		   	   	   $files = $_FILES;
					  		   		   $cpt = count($_FILES['brother3_attachments']['name']);
					  		   		    
					  		   		   for($i=0; $i<$cpt; $i++)
					  		   		   {
					  		   			 $_FILES['brother3_attachments']['name']	= $files['brother3_attachments']['name'][$i];
					  		   			 $_FILES['brother3_attachments']['type']	= $files['brother3_attachments']['type'][$i];
					  		   			 $_FILES['brother3_attachments']['tmp_name'] = $files['brother3_attachments']['tmp_name'][$i];
					  		   			 $_FILES['brother3_attachments']['error']	= $files['brother3_attachments']['error'][$i];
					  		   			 $_FILES['brother3_attachments']['size']	= $files['brother3_attachments']['size'][$i];
					  		   			
					  		   			   foreach ($_FILES as $index => $value)
					  		   		       {			       
					  		   		       
					  		   		       		if(!empty($value['name'] && $index == 'brother3_attachments'))
					  		   				  	{
					  		   				        $config = array();
					  		   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
					  		   						$config['allowed_types'] = '*';
					  		   						$config['max_size']      = '*';
					  		   						$config['encrypt_name']  = TRUE;
					  		   				  	
					  		   				        //create controller upload folder if not exists
					  		   						if (!is_dir($config['upload_path']))
					  		   						{
					  		   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
					  		   						}
					  		   			
					  		   						$this->upload->initialize($config);
					  		   						
					  		   						if ( ! $this->upload->do_upload($index))
					  		   						{
					  		   							 echo "external file upload failed";
					  		   			        		 return FALSE;
					  		   						}
					  		   						else
					  		   						{
					  		   							$brother3_external_files = $this->upload->data();
					  		   							//log_message('debug', 'brother3_external_files=======5849'.print_r($brother3_external_files, true));
					  		   							$rand_number = mt_rand();
					  		   							$external_brother3_data_array = array(
					  		   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
					  		   													"file_name" =>$brother3_external_files['file_name'],
					  		   													"file_path" =>$brother3_external_files['file_relative_path'],
					  		   													"file_size" =>$brother3_external_files['file_size']
					  		   												)	);

					  		   							$brother3_external_final = array_merge($brother3_external_final,$external_brother3_data_array);
					  		   							
					  		   						}  
					  		   					}
					  		   				}
					  		   			}
					  		   		 

					  		   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother3 Data']['attachments'] = $brother3_external_final;
					  		   		  if(isset($doc_data['brother3_attachments']))
					  		   			  {
					  		   					   
					  		   				$brother3_external_merged_data = array_merge($doc_data['brother3_attach_update'],$brother3_external_final);
					  		   				$doc_data['brother3_attach_update'] = array_replace_recursive($doc_data['brother3_attach_update'],$brother3_external_merged_data);
					  		   			  }
					  		   			  else
					  		   			 {
					  		   			    $doc_data['brother3_attach_update'] = $brother3_external_final;
					  		   			 }
					  		   	   	}else
					  		   		   {
					  		   		   		$doc_data['brother3_attach_update'] = [];
					  		   		   }

					  	
						   	  if(isset($_FILES['brother4_attachments']['name']) && !empty($_FILES['brother4_attachments']['name']))
						   	   {
						   	   	   $files = $_FILES;
						   		   $cpt = count($_FILES['brother4_attachments']['name']);
						   		    
						   		   for($i=0; $i<$cpt; $i++)
						   		   {
						   			 $_FILES['brother4_attachments']['name']	= $files['brother4_attachments']['name'][$i];
						   			 $_FILES['brother4_attachments']['type']	= $files['brother4_attachments']['type'][$i];
						   			 $_FILES['brother4_attachments']['tmp_name'] = $files['brother4_attachments']['tmp_name'][$i];
						   			 $_FILES['brother4_attachments']['error']	= $files['brother4_attachments']['error'][$i];
						   			 $_FILES['brother4_attachments']['size']	= $files['brother4_attachments']['size'][$i];
						   			
						   			   foreach ($_FILES as $index => $value)
						   		       {			       
						   		       
						   		       		if(!empty($value['name'] && $index == 'brother4_attachments'))
						   				  	{
						   				        $config = array();
						   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						   						$config['allowed_types'] = '*';
						   						$config['max_size']      = '*';
						   						$config['encrypt_name']  = TRUE;
						   				  	
						   				        //create controller upload folder if not exists
						   						if (!is_dir($config['upload_path']))
						   						{
						   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						   						}
						   			
						   						$this->upload->initialize($config);
						   						
						   						if ( ! $this->upload->do_upload($index))
						   						{
						   							 echo "external file upload failed";
						   			        		 return FALSE;
						   						}
						   						else
						   						{
						   							$brother4_external_files = $this->upload->data();
						   							//log_message('debug', 'brother4_external_files=======5849'.print_r($brother4_external_files, true));
						   							$rand_number = mt_rand();
						   							$external_brother4_data_array = array(
						   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						   													"file_name" =>$brother4_external_files['file_name'],
						   													"file_path" =>$brother4_external_files['file_relative_path'],
						   													"file_size" =>$brother4_external_files['file_size']
						   												)	);

						   							$brother4_external_final = array_merge($brother4_external_final,$external_brother4_data_array);
						   							
						   						}  
						   					}
						   				}
						   			}
						   		 

						   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother4 Data']['attachments'] = $brother4_external_final;
						   		  if(isset($doc_data['brother4_attachments']))
						   			  {
						   					   
						   				$brother4_external_merged_data = array_merge($doc_data['brother4_attach_update'],$brother4_external_final);
						   				$doc_data['brother4_attach_update'] = array_replace_recursive($doc_data['brother4_attach_update'],$brother4_external_merged_data);
						   			  }
						   			  else
						   			 {
						   			    $doc_data['brother4_attach_update'] = $brother4_external_final;
						   			 }
						   	   	}else
						   		   {
						   		   		$doc_data['brother4_attach_update'] = [];
						   		   }

					
						   	  if(isset($_FILES['brother5_attachments']['name']) && !empty($_FILES['brother5_attachments']['name']))
						   	   {
						   	   	   $files = $_FILES;
						   		   $cpt = count($_FILES['brother5_attachments']['name']);
						   		    
						   		   for($i=0; $i<$cpt; $i++)
						   		   {
						   			 $_FILES['brother5_attachments']['name']	= $files['brother5_attachments']['name'][$i];
						   			 $_FILES['brother5_attachments']['type']	= $files['brother5_attachments']['type'][$i];
						   			 $_FILES['brother5_attachments']['tmp_name'] = $files['brother5_attachments']['tmp_name'][$i];
						   			 $_FILES['brother5_attachments']['error']	= $files['brother5_attachments']['error'][$i];
						   			 $_FILES['brother5_attachments']['size']	= $files['brother5_attachments']['size'][$i];
						   			
						   			   foreach ($_FILES as $index => $value)
						   		       {			       
						   		       
						   		       		if(!empty($value['name'] && $index == 'brother5_attachments'))
						   				  	{
						   				        $config = array();
						   						$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/stud_family/external_files/';
						   						$config['allowed_types'] = '*';
						   						$config['max_size']      = '*';
						   						$config['encrypt_name']  = TRUE;
						   				  	
						   				        //create controller upload folder if not exists
						   						if (!is_dir($config['upload_path']))
						   						{
						   							mkdir(UPLOADFOLDERDIR."public/uploads/stud_family/external_files/",0777,TRUE);
						   						}
						   			
						   						$this->upload->initialize($config);
						   						
						   						if ( ! $this->upload->do_upload($index))
						   						{
						   							 echo "external file upload failed";
						   			        		 return FALSE;
						   						}
						   						else
						   						{
						   							$brother5_external_files = $this->upload->data();
						   							//log_message('debug', 'brother5_external_files=======5849'.print_r($brother5_external_files, true));
						   							$rand_number = mt_rand();
						   							$external_brother5_data_array = array(
						   													"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
						   													"file_name" =>$brother5_external_files['file_name'],
						   													"file_path" =>$brother5_external_files['file_relative_path'],
						   													"file_size" =>$brother5_external_files['file_size']
						   												)	);

						   							$brother5_external_final = array_merge($brother5_external_final,$external_brother5_data_array);
						   							
						   						}  
						   					}
						   				}
						   			}
						   		 

						   		 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
						   		  if(isset($doc_data['brother5_attachments']))
						   			  {
						   					   
						   				$brother5_external_merged_data = array_merge($doc_data['brother5_attach_update'],$brother5_external_final);
						   				$doc_data['brother5_attach_update'] = array_replace_recursive($doc_data['brother5_attach_update'],$brother5_external_merged_data);
						   			  }
						   			  else
						   			 {
						   			    $doc_data['brother5_attach_update'] = $brother5_external_final;
						   			 }
						   	   	}else
						   		   {
						   		   		$doc_data['brother5_attach_update'] = [];
						   		   }

				
			// End Brother Attchemtns

		}

		//echo print_r($doc_data, true); exit();

		if(isset($doc_data) && !empty($doc_data)){
			$insert_data = $this->healthsupervisor_app_model->update_family_health_attachements($doc_data, $doc_id, $unique);

			if($insert_data == 'UPDATE_FAIL')
		 	{
		 		
		 		$this->output->set_output(json_encode(array('Status' => 'Failed')));
		 	}else{
		 		$this->output->set_output(json_encode(array('Status' => 'Successfully Submitted')));
		 	}
		}
		else{
			$this->output->set_output(json_encode(array('Status' => 'Please Select Attachments')));
		}

			
	}

// To show submitted parent submissions

	public function fetch_family_submitted_details()
	{
		$unique_id = $this->input->post('UID', true);
		$data = $this->healthsupervisor_app_model->fetch_family_submitted_details($unique_id);

		if(!empty($data)){
			$this->output->set_output(json_encode($data));
		}else{
			$this->output->set_output(json_encode("No Data Available"));
		}
	}
// End To show submitted parent submissions 

	/* Get Student personal Information by School Name*/
	public function get_student_info_by_school_name()
	{
		if(!empty( $_POST['user_type'] ))
		{
			$user_type = $_POST['user_type'];
				if(preg_match("/PANACEA/i", $user_type))
				{
				if(isset($_POST['school_name'])){
					$school_name = $_POST['school_name'];
					$result = $this->healthsupervisor_app_model->get_student_info_by_school_name($school_name);
					//$result = call_user_func_array("array_merge", $abnormalities);
					if(!empty($result)){
						$this->output->set_output(json_encode($result));
					}else{
						$this->output->set_output(json_encode(array(
			 											'status' => FALSE,
			 											'message' => "No Students Found for this School"
			 										)));
					}
				}else{
						$this->output->set_output(json_encode(array(
			 											'status' => FALSE,
			 											'message' => "REQUIRED_PARAMS_MISSING"
			 										)));
				}
			}
		}else{
						$this->output->set_output(json_encode(array(
			 											'status' => FALSE,
			 											'message' => "User Type Missing"
			 										)));
		}
	}

	//Getting parents registraion OTP list

	public function get_otp_list_for_hs_for_app()
    {
    	$post = $_POST;

    	if(isset($post['user_type'])){
    		$school = $post['school_name'];
    		$start = $post['start_date'];
    		$end = $post['end_date'];

    		$this->data = $this->healthsupervisor_app_model->get_otp_list_for_hs_for_app($school, $start, $end);

    		if(!empty($this->data)){
    			$this->output->set_output(json_encode($this->data));
    		}else{
    			$this->output->set_output(json_encode("No Data Available"));
    		}
    	}
    	
    }

    public function change_status_to_remove_from_list()
    {
    	
    	$id = $this->input->post('ids', true);

    	if(isset($id)){

    		$this->data = $this->healthsupervisor_app_model->change_status_to_remove_from_list($id);

    	}

    	if(!empty($this->data)){
			$this->output->set_output(json_encode(array('status' => $this->data)));
		}else{
			$this->output->set_output(json_encode("No Data Available"));
		}
    	
    }

    public function get_cc_users_list()
    {
    	$this->data = $this->panacea_common_model->get_field_officer_name();

    	if(!empty($this->data)){
			$this->output->set_output(json_encode($this->data));
		}else{
			$this->output->set_output(json_encode("No Data Available"));
		}
    }

    public function submit_rhso_sanitation_form()
    {
    	$post = $_POST;

    	if (isset($post['submission_type'])) {
    		$submission_type = $post['submission_type'];
    	}
    	
    	$school = $post['school_name'];
    	$district = $post['district'];
    	$date = date('Y-m-d');

    	if(isset($post['status'])){
    		$status = $post['status'];
    	}
    	

    	if (isset($post['email'])) {
    		$email = $post['email'];
    	}
    	

    	if(isset($status) && !empty($status)){

    		$update_status = $this->healthsupervisor_app_model->update_status_today_submission($school, $district, $date, $status, $email);

    		if($update_status == 'Success'){
    			
    			$this->output->set_output(json_encode('Submitted Successfully'));
    		}else{
    			$this->output->set_output(json_encode('Failed'));
    		}

    	}

    	if(isset($submission_type) && $submission_type == 'Kitchens'){

    		$check = $this->healthsupervisor_app_model->check_today_submission($school, $district, $date);

    		if($check == 'Success'){

    			$doc_data['Description'] = $post['kitchens_discription'];
    			
    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $kitchens_external_files = array();
				   $kitchens_external_final = array();
				   $kitchens_external_merged_data = array();
				    if(isset($_FILES['kitchens_attachments']['name']) && !empty($_FILES['kitchens_attachments']['name']))
    			   {
    			   	   $files = $_FILES;
    				   $cpt = count($_FILES['kitchens_attachments']['name']);
    				    
    				   for($i=0; $i<$cpt; $i++)
    				   {
    					 $_FILES['kitchens_attachments']['name']	= $files['kitchens_attachments']['name'][$i];
    					 $_FILES['kitchens_attachments']['type']	= $files['kitchens_attachments']['type'][$i];
    					 $_FILES['kitchens_attachments']['tmp_name'] = $files['kitchens_attachments']['tmp_name'][$i];
    					 $_FILES['kitchens_attachments']['error']	= $files['kitchens_attachments']['error'][$i];
    					 $_FILES['kitchens_attachments']['size']	= $files['kitchens_attachments']['size'][$i];
    					
    					   foreach ($_FILES as $index => $value)
    				       {			       
    				       
    				       		if(!empty($value['name'] && $index == 'kitchens_attachments'))
    						  	{
    						        $config = array();
    								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
    								$config['allowed_types'] = '*';
    								$config['max_size']      = '*';
    								$config['encrypt_name']  = TRUE;
    						  	
    						        //create controller upload folder if not exists
    								if (!is_dir($config['upload_path']))
    								{
    									mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
    								}
    					
    								$this->upload->initialize($config);
    								
    								if ( ! $this->upload->do_upload($index))
    								{
    									 echo "external file upload failed";
    					        		 return FALSE;
    								}
    								else
    								{
    									$kitchens_external_files = $this->upload->data();
    									//log_message('debug', 'kitchens_external_files=======5849'.print_r($kitchens_external_files, true));
    									$rand_number = mt_rand();
    									$external_kitchens_data_array = array(
    															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
    															"file_name" =>$kitchens_external_files['file_name'],
    															"file_path" =>$kitchens_external_files['file_relative_path'],
    															"file_size" =>$kitchens_external_files['file_size']
    														)	);

    									$kitchens_external_final = array_merge($kitchens_external_final,$external_kitchens_data_array);
    									
    								}  
    							}
    						}
    					}
    				
    				$check_image = $this->healthsupervisor_app_model->check_images_rhso_sanitation_data($submission_type, $school, $district, $date);

    				
    				 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
    				  if(isset($check_image[0]['Kitchen']['Attachments']))
    					  {
    					  	//echo print_r($check_image, true);
    							   
    						$kitchens_external_merged_data = array_merge($check_image[0]['Kitchen']['Attachments'],$kitchens_external_final);

    						//echo print_r($kitchens_external_merged_data, true);
    						$doc_data['Attachments'] = array_replace_recursive($check_image[0]['Kitchen']['Attachments'],$kitchens_external_merged_data);

    						
    					  }
    					  else
    					 {
    					    $doc_data['Attachments'] = $kitchens_external_final;
    					 }
    			   	}else
    				   {
    				   		$doc_data['Attachments'] = [];
    				   }


				}
    			 
    			
    			$insert = $this->healthsupervisor_app_model->update_rhso_sanitation_data($doc_data, $school, $district, $date, $submission_type);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		else
    		{
    			$doc_data['Date'] = $date;
    			$doc_data['School Name'] = $school;
    			$doc_data['District'] = $district;
    			$doc_data['Kitchen']['Description'] = $post['kitchens_discription'];

    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $kitchens_external_files = array();
				   $kitchens_external_final = array();
				   $kitchens_external_merged_data = array();

				     if(isset($_FILES['kitchens_attachments']['name']) && !empty($_FILES['kitchens_attachments']['name']))
				      {
				      	   $files = $_FILES;
				   	   $cpt = count($_FILES['kitchens_attachments']['name']);
				   	    
				   	   for($i=0; $i<$cpt; $i++)
				   	   {
				   		 $_FILES['kitchens_attachments']['name']	= $files['kitchens_attachments']['name'][$i];
				   		 $_FILES['kitchens_attachments']['type']	= $files['kitchens_attachments']['type'][$i];
				   		 $_FILES['kitchens_attachments']['tmp_name'] = $files['kitchens_attachments']['tmp_name'][$i];
				   		 $_FILES['kitchens_attachments']['error']	= $files['kitchens_attachments']['error'][$i];
				   		 $_FILES['kitchens_attachments']['size']	= $files['kitchens_attachments']['size'][$i];
				   		
				   		   foreach ($_FILES as $index => $value)
				   	       {			       
				   	       
				   	       		if(!empty($value['name'] && $index == 'kitchens_attachments'))
				   			  	{
				   			        $config = array();
				   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
				   					$config['allowed_types'] = '*';
				   					$config['max_size']      = '*';
				   					$config['encrypt_name']  = TRUE;
				   			  	
				   			        //create controller upload folder if not exists
				   					if (!is_dir($config['upload_path']))
				   					{
				   						mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
				   					}
				   		
				   					$this->upload->initialize($config);
				   					
				   					if ( ! $this->upload->do_upload($index))
				   					{
				   						 echo "external file upload failed";
				   		        		 return FALSE;
				   					}
				   					else
				   					{
				   						$kitchens_external_files = $this->upload->data();
				   						//log_message('debug', 'kitchens_external_files=======5849'.print_r($kitchens_external_files, true));
				   						$rand_number = mt_rand();
				   						$external_kitchens_data_array = array(
				   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				   												"file_name" =>$kitchens_external_files['file_name'],
				   												"file_path" =>$kitchens_external_files['file_relative_path'],
				   												"file_size" =>$kitchens_external_files['file_size']
				   											)	);

				   						$kitchens_external_final = array_merge($kitchens_external_final,$external_kitchens_data_array);
				   						
				   					}  
				   				}
				   			}
				   		}
				   	 

				   	 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
				   	  if(isset($doc_data['Kitchen']['kitchens_attachments']))
				   		  {
				   				   
				   			$kitchens_external_merged_data = array_merge($doc_data['Kitchen']['Attachments'],$kitchens_external_final);
				   			$doc_data['Kitchen']['kitchens_attachments'] = array_replace_recursive($doc_data['Kitchen']['Attachments'],$kitchens_external_merged_data);
				   		  }
				   		  else
				   		 {
				   		    $doc_data['Kitchen']['Attachments'] = $kitchens_external_final;
				   		 }
				      	}else
				   	   {
				   	   		$doc_data['Kitchen']['Attachments'] = [];
				   	   }
				}
    			  


    			$insert = $this->healthsupervisor_app_model->insert_rhso_sanitation_data($doc_data, $email);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		

    	}elseif(isset($submission_type) && $submission_type == 'ROPlant'){

    		$check = $this->healthsupervisor_app_model->check_today_submission($school, $district, $date);

    		if($check == 'Success'){

    			$doc_data['Description'] = $post['ROPlant_discription'];
    			
    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $ROPlant_external_files = array();
				   $ROPlant_external_final = array();
				   $ROPlant_external_merged_data = array();
				    if(isset($_FILES['ROPlant_attachments']['name']) && !empty($_FILES['ROPlant_attachments']['name']))
    			   {
    			   	   $files = $_FILES;
    				   $cpt = count($_FILES['ROPlant_attachments']['name']);
    				    
    				   for($i=0; $i<$cpt; $i++)
    				   {
    					 $_FILES['ROPlant_attachments']['name']	= $files['ROPlant_attachments']['name'][$i];
    					 $_FILES['ROPlant_attachments']['type']	= $files['ROPlant_attachments']['type'][$i];
    					 $_FILES['ROPlant_attachments']['tmp_name'] = $files['ROPlant_attachments']['tmp_name'][$i];
    					 $_FILES['ROPlant_attachments']['error']	= $files['ROPlant_attachments']['error'][$i];
    					 $_FILES['ROPlant_attachments']['size']	= $files['ROPlant_attachments']['size'][$i];
    					
    					   foreach ($_FILES as $index => $value)
    				       {			       
    				       
    				       		if(!empty($value['name'] && $index == 'ROPlant_attachments'))
    						  	{
    						        $config = array();
    								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
    								$config['allowed_types'] = '*';
    								$config['max_size']      = '*';
    								$config['encrypt_name']  = TRUE;
    						  	
    						        //create controller upload folder if not exists
    								if (!is_dir($config['upload_path']))
    								{
    									mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
    								}
    					
    								$this->upload->initialize($config);
    								
    								if ( ! $this->upload->do_upload($index))
    								{
    									 echo "external file upload failed";
    					        		 return FALSE;
    								}
    								else
    								{
    									$ROPlant_external_files = $this->upload->data();
    									//log_message('debug', 'ROPlant_external_files=======5849'.print_r($ROPlant_external_files, true));
    									$rand_number = mt_rand();
    									$external_ROPlant_data_array = array(
    															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
    															"file_name" =>$ROPlant_external_files['file_name'],
    															"file_path" =>$ROPlant_external_files['file_relative_path'],
    															"file_size" =>$ROPlant_external_files['file_size']
    														)	);

    									$ROPlant_external_final = array_merge($ROPlant_external_final,$external_ROPlant_data_array);
    									
    								}  
    							}
    						}
    					}
    				
    				$check_image = $this->healthsupervisor_app_model->check_images_rhso_sanitation_data($submission_type, $school, $district, $date);

    				
    				 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
    				  if(isset($check_image[0]['ROPlant']['Attachments']))
    					  {
    					  	//echo print_r($check_image, true);
    							   
    						$ROPlant_external_merged_data = array_merge($check_image[0]['ROPlant']['Attachments'],$ROPlant_external_final);

    						//echo print_r($ROPlant_external_merged_data, true);
    						$doc_data['Attachments'] = array_replace_recursive($check_image[0]['ROPlant']['Attachments'],$ROPlant_external_merged_data);

    						
    					  }
    					  else
    					 {
    					    $doc_data['Attachments'] = $ROPlant_external_final;
    					 }
    			   	}else
    				   {
    				   		$doc_data['Attachments'] = [];
    				   }


				}
    			 
    			
    			$insert = $this->healthsupervisor_app_model->update_rhso_sanitation_data($doc_data, $school, $district, $date, $submission_type);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		else
    		{
    			$doc_data['Date'] = $date;
    			$doc_data['School Name'] = $school;
    			$doc_data['District'] = $district;
    			$doc_data['ROPlant']['Description'] = $post['ROPlant_discription'];

    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $ROPlant_external_files = array();
				   $ROPlant_external_final = array();
				   $ROPlant_external_merged_data = array();

				     if(isset($_FILES['ROPlant_attachments']['name']) && !empty($_FILES['ROPlant_attachments']['name']))
				      {
				      	   $files = $_FILES;
				   	   $cpt = count($_FILES['ROPlant_attachments']['name']);
				   	    
				   	   for($i=0; $i<$cpt; $i++)
				   	   {
				   		 $_FILES['ROPlant_attachments']['name']	= $files['ROPlant_attachments']['name'][$i];
				   		 $_FILES['ROPlant_attachments']['type']	= $files['ROPlant_attachments']['type'][$i];
				   		 $_FILES['ROPlant_attachments']['tmp_name'] = $files['ROPlant_attachments']['tmp_name'][$i];
				   		 $_FILES['ROPlant_attachments']['error']	= $files['ROPlant_attachments']['error'][$i];
				   		 $_FILES['ROPlant_attachments']['size']	= $files['ROPlant_attachments']['size'][$i];
				   		
				   		   foreach ($_FILES as $index => $value)
				   	       {			       
				   	       
				   	       		if(!empty($value['name'] && $index == 'ROPlant_attachments'))
				   			  	{
				   			        $config = array();
				   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
				   					$config['allowed_types'] = '*';
				   					$config['max_size']      = '*';
				   					$config['encrypt_name']  = TRUE;
				   			  	
				   			        //create controller upload folder if not exists
				   					if (!is_dir($config['upload_path']))
				   					{
				   						mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
				   					}
				   		
				   					$this->upload->initialize($config);
				   					
				   					if ( ! $this->upload->do_upload($index))
				   					{
				   						 echo "external file upload failed";
				   		        		 return FALSE;
				   					}
				   					else
				   					{
				   						$ROPlant_external_files = $this->upload->data();
				   						//log_message('debug', 'ROPlant_external_files=======5849'.print_r($ROPlant_external_files, true));
				   						$rand_number = mt_rand();
				   						$external_ROPlant_data_array = array(
				   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				   												"file_name" =>$ROPlant_external_files['file_name'],
				   												"file_path" =>$ROPlant_external_files['file_relative_path'],
				   												"file_size" =>$ROPlant_external_files['file_size']
				   											)	);

				   						$ROPlant_external_final = array_merge($ROPlant_external_final,$external_ROPlant_data_array);
				   						
				   					}  
				   				}
				   			}
				   		}
				   	 

				   	 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
				   	  if(isset($doc_data['ROPlant']['ROPlant_attachments']))
				   		  {
				   				   
				   			$ROPlant_external_merged_data = array_merge($doc_data['ROPlant']['Attachments'],$ROPlant_external_final);
				   			$doc_data['ROPlant']['ROPlant_attachments'] = array_replace_recursive($doc_data['ROPlant']['Attachments'],$ROPlant_external_merged_data);
				   		  }
				   		  else
				   		 {
				   		    $doc_data['ROPlant']['Attachments'] = $ROPlant_external_final;
				   		 }
				      	}else
				   	   {
				   	   		$doc_data['ROPlant']['Attachments'] = [];
				   	   }
				}
    			  


    			$insert = $this->healthsupervisor_app_model->insert_rhso_sanitation_data($doc_data, $email);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));

    			}else{

    				$this->output->set_output(json_encode('Failed'));

    			}

    		}
    		

    	}elseif (isset($submission_type) && $submission_type == 'Toilets') {
    		
    		$check = $this->healthsupervisor_app_model->check_today_submission($school, $district, $date);

    		if($check == 'Success'){

    			$doc_data['Description'] = $post['toilets_discription'];
    			
    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $toilets_external_files = array();
				   $toilets_external_final = array();
				   $toilets_external_merged_data = array();
				    if(isset($_FILES['toilets_attachments']['name']) && !empty($_FILES['toilets_attachments']['name']))
    			   {
    			   	   $files = $_FILES;
    				   $cpt = count($_FILES['toilets_attachments']['name']);
    				    
    				   for($i=0; $i<$cpt; $i++)
    				   {
    					 $_FILES['toilets_attachments']['name']	= $files['toilets_attachments']['name'][$i];
    					 $_FILES['toilets_attachments']['type']	= $files['toilets_attachments']['type'][$i];
    					 $_FILES['toilets_attachments']['tmp_name'] = $files['toilets_attachments']['tmp_name'][$i];
    					 $_FILES['toilets_attachments']['error']	= $files['toilets_attachments']['error'][$i];
    					 $_FILES['toilets_attachments']['size']	= $files['toilets_attachments']['size'][$i];
    					
    					   foreach ($_FILES as $index => $value)
    				       {			       
    				       
    				       		if(!empty($value['name'] && $index == 'toilets_attachments'))
    						  	{
    						        $config = array();
    								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
    								$config['allowed_types'] = '*';
    								$config['max_size']      = '*';
    								$config['encrypt_name']  = TRUE;
    						  	
    						        //create controller upload folder if not exists
    								if (!is_dir($config['upload_path']))
    								{
    									mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
    								}
    					
    								$this->upload->initialize($config);
    								
    								if ( ! $this->upload->do_upload($index))
    								{
    									 echo "external file upload failed";
    					        		 return FALSE;
    								}
    								else
    								{
    									$toilets_external_files = $this->upload->data();
    									//log_message('debug', 'toilets_external_files=======5849'.print_r($toilets_external_files, true));
    									$rand_number = mt_rand();
    									$external_toilets_data_array = array(
    															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
    															"file_name" =>$toilets_external_files['file_name'],
    															"file_path" =>$toilets_external_files['file_relative_path'],
    															"file_size" =>$toilets_external_files['file_size']
    														)	);

    									$toilets_external_final = array_merge($toilets_external_final,$external_toilets_data_array);
    									
    								}  
    							}
    						}
    					}
    				
    				$check_image = $this->healthsupervisor_app_model->check_images_rhso_sanitation_data($submission_type, $school, $district, $date);

    				
    				 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
    				  if(isset($check_image[0]['Toilets']['Attachments']))
    					  {
    					  	//echo print_r($check_image, true);
    							   
    						$toilets_external_merged_data = array_merge($check_image[0]['Toilets']['Attachments'],$toilets_external_final);

    						//echo print_r($toilets_external_merged_data, true);
    						$doc_data['Attachments'] = array_replace_recursive($check_image[0]['Toilets']['Attachments'],$toilets_external_merged_data);

    						
    					  }
    					  else
    					 {
    					    $doc_data['Attachments'] = $toilets_external_final;
    					 }
    			   	}else
    				   {
    				   		$doc_data['Attachments'] = [];
    				   }


				}
    			 
    			
    			$insert = $this->healthsupervisor_app_model->update_rhso_sanitation_data($doc_data, $school, $district, $date, $submission_type);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		else
    		{
    			$doc_data['Date'] = $date;
    			$doc_data['School Name'] = $school;
    			$doc_data['District'] = $district;
    			$doc_data['Toilets']['Description'] = $post['toilets_discription'];

    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $toilets_external_files = array();
				   $toilets_external_final = array();
				   $toilets_external_merged_data = array();

				     if(isset($_FILES['toilets_attachments']['name']) && !empty($_FILES['toilets_attachments']['name']))
				      {
				      	   $files = $_FILES;
				   	   $cpt = count($_FILES['toilets_attachments']['name']);
				   	    
				   	   for($i=0; $i<$cpt; $i++)
				   	   {
				   		 $_FILES['toilets_attachments']['name']	= $files['toilets_attachments']['name'][$i];
				   		 $_FILES['toilets_attachments']['type']	= $files['toilets_attachments']['type'][$i];
				   		 $_FILES['toilets_attachments']['tmp_name'] = $files['toilets_attachments']['tmp_name'][$i];
				   		 $_FILES['toilets_attachments']['error']	= $files['toilets_attachments']['error'][$i];
				   		 $_FILES['toilets_attachments']['size']	= $files['toilets_attachments']['size'][$i];
				   		
				   		   foreach ($_FILES as $index => $value)
				   	       {			       
				   	       
				   	       		if(!empty($value['name'] && $index == 'toilets_attachments'))
				   			  	{
				   			        $config = array();
				   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
				   					$config['allowed_types'] = '*';
				   					$config['max_size']      = '*';
				   					$config['encrypt_name']  = TRUE;
				   			  	
				   			        //create controller upload folder if not exists
				   					if (!is_dir($config['upload_path']))
				   					{
				   						mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
				   					}
				   		
				   					$this->upload->initialize($config);
				   					
				   					if ( ! $this->upload->do_upload($index))
				   					{
				   						 echo "external file upload failed";
				   		        		 return FALSE;
				   					}
				   					else
				   					{
				   						$toilets_external_files = $this->upload->data();
				   						//log_message('debug', 'toilets_external_files=======5849'.print_r($toilets_external_files, true));
				   						$rand_number = mt_rand();
				   						$external_toilets_data_array = array(
				   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				   												"file_name" =>$toilets_external_files['file_name'],
				   												"file_path" =>$toilets_external_files['file_relative_path'],
				   												"file_size" =>$toilets_external_files['file_size']
				   											)	);

				   						$toilets_external_final = array_merge($toilets_external_final,$external_toilets_data_array);
				   						
				   					}  
				   				}
				   			}
				   		}
				   	 

				   	 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
				   	  if(isset($doc_data['Toilets']['toilets_attachments']))
				   		  {
				   				   
				   			$toilets_external_merged_data = array_merge($doc_data['Toilets']['Attachments'],$toilets_external_final);
				   			$doc_data['Toilets']['toilets_attachments'] = array_replace_recursive($doc_data['Toilets']['Attachments'],$toilets_external_merged_data);
				   		  }
				   		  else
				   		 {
				   		    $doc_data['Toilets']['Attachments'] = $toilets_external_final;
				   		 }
				      	}else
				   	   {
				   	   		$doc_data['Toilets']['Attachments'] = [];
				   	   }
				}
    			  


    			$insert = $this->healthsupervisor_app_model->insert_rhso_sanitation_data($doc_data, $email);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}

    	}elseif (isset($submission_type) && $submission_type == 'Campus') {
    		
    		$check = $this->healthsupervisor_app_model->check_today_submission($school, $district, $date);

    		if($check == 'Success'){

    			$doc_data['Description'] = $post['campus_discription'];
    			
    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $campus_external_files = array();
				   $campus_external_final = array();
				   $campus_external_merged_data = array();
				    if(isset($_FILES['campus_attachments']['name']) && !empty($_FILES['campus_attachments']['name']))
    			   {
    			   	   $files = $_FILES;
    				   $cpt = count($_FILES['campus_attachments']['name']);
    				    
    				   for($i=0; $i<$cpt; $i++)
    				   {
    					 $_FILES['campus_attachments']['name']	= $files['campus_attachments']['name'][$i];
    					 $_FILES['campus_attachments']['type']	= $files['campus_attachments']['type'][$i];
    					 $_FILES['campus_attachments']['tmp_name'] = $files['campus_attachments']['tmp_name'][$i];
    					 $_FILES['campus_attachments']['error']	= $files['campus_attachments']['error'][$i];
    					 $_FILES['campus_attachments']['size']	= $files['campus_attachments']['size'][$i];
    					
    					   foreach ($_FILES as $index => $value)
    				       {			       
    				       
    				       		if(!empty($value['name'] && $index == 'campus_attachments'))
    						  	{
    						        $config = array();
    								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
    								$config['allowed_types'] = '*';
    								$config['max_size']      = '*';
    								$config['encrypt_name']  = TRUE;
    						  	
    						        //create controller upload folder if not exists
    								if (!is_dir($config['upload_path']))
    								{
    									mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
    								}
    					
    								$this->upload->initialize($config);
    								
    								if ( ! $this->upload->do_upload($index))
    								{
    									 echo "external file upload failed";
    					        		 return FALSE;
    								}
    								else
    								{
    									$campus_external_files = $this->upload->data();
    									//log_message('debug', 'campus_external_files=======5849'.print_r($campus_external_files, true));
    									$rand_number = mt_rand();
    									$external_campus_data_array = array(
    															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
    															"file_name" =>$campus_external_files['file_name'],
    															"file_path" =>$campus_external_files['file_relative_path'],
    															"file_size" =>$campus_external_files['file_size']
    														)	);

    									$campus_external_final = array_merge($campus_external_final,$external_campus_data_array);
    									
    								}  
    							}
    						}
    					}
    				
    				$check_image = $this->healthsupervisor_app_model->check_images_rhso_sanitation_data($submission_type, $school, $district, $date);

    				
    				 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
    				  if(isset($check_image[0]['Campus']['Attachments']))
    					  {
    					  	//echo print_r($check_image, true);
    							   
    						$campus_external_merged_data = array_merge($check_image[0]['Campus']['Attachments'],$campus_external_final);

    						//echo print_r($campus_external_merged_data, true);
    						$doc_data['Attachments'] = array_replace_recursive($check_image[0]['Campus']['Attachments'],$campus_external_merged_data);

    						
    					  }
    					  else
    					 {
    					    $doc_data['Attachments'] = $campus_external_final;
    					 }
    			   	}else
    				   {
    				   		$doc_data['Attachments'] = [];
    				   }


				}
    			 
    			
    			$insert = $this->healthsupervisor_app_model->update_rhso_sanitation_data($doc_data, $school, $district, $date, $submission_type);

    			//echo print_r($insert, true); exit();

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		else
    		{
    			$doc_data['Date'] = $date;
    			$doc_data['School Name'] = $school;
    			$doc_data['District'] = $district;
    			$doc_data['Campus']['Description'] = $post['campus_discription'];

    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $campus_external_files = array();
				   $campus_external_final = array();
				   $campus_external_merged_data = array();

				     if(isset($_FILES['campus_attachments']['name']) && !empty($_FILES['campus_attachments']['name']))
				      {
				      	   $files = $_FILES;
				   	   $cpt = count($_FILES['campus_attachments']['name']);
				   	    
				   	   for($i=0; $i<$cpt; $i++)
				   	   {
				   		 $_FILES['campus_attachments']['name']	= $files['campus_attachments']['name'][$i];
				   		 $_FILES['campus_attachments']['type']	= $files['campus_attachments']['type'][$i];
				   		 $_FILES['campus_attachments']['tmp_name'] = $files['campus_attachments']['tmp_name'][$i];
				   		 $_FILES['campus_attachments']['error']	= $files['campus_attachments']['error'][$i];
				   		 $_FILES['campus_attachments']['size']	= $files['campus_attachments']['size'][$i];
				   		
				   		   foreach ($_FILES as $index => $value)
				   	       {			       
				   	       
				   	       		if(!empty($value['name'] && $index == 'campus_attachments'))
				   			  	{
				   			        $config = array();
				   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
				   					$config['allowed_types'] = '*';
				   					$config['max_size']      = '*';
				   					$config['encrypt_name']  = TRUE;
				   			  	
				   			        //create controller upload folder if not exists
				   					if (!is_dir($config['upload_path']))
				   					{
				   						mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
				   					}
				   		
				   					$this->upload->initialize($config);
				   					
				   					if ( ! $this->upload->do_upload($index))
				   					{
				   						 echo "external file upload failed";
				   		        		 return FALSE;
				   					}
				   					else
				   					{
				   						$campus_external_files = $this->upload->data();
				   						//log_message('debug', 'campus_external_files=======5849'.print_r($campus_external_files, true));
				   						$rand_number = mt_rand();
				   						$external_campus_data_array = array(
				   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				   												"file_name" =>$campus_external_files['file_name'],
				   												"file_path" =>$campus_external_files['file_relative_path'],
				   												"file_size" =>$campus_external_files['file_size']
				   											)	);

				   						$campus_external_final = array_merge($campus_external_final,$external_campus_data_array);
				   						
				   					}  
				   				}
				   			}
				   		}
				   	 

				   	 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
				   	  if(isset($doc_data['Campus']['Attachments']))
				   		  {
				   				   
				   			$campus_external_merged_data = array_merge($doc_data['Campus']['Attachments'],$campus_external_final);
				   			$doc_data['Campus']['Attachments'] = array_replace_recursive($doc_data['Campus']['Attachments'],$campus_external_merged_data);
				   		  }
				   		  else
				   		 {
				   		    $doc_data['Campus']['Attachments'] = $campus_external_final;
				   		 }
				      	}else
				   	   {
				   	   		$doc_data['Campus']['Attachments'] = [];
				   	   }
				}
    			  


    			$insert = $this->healthsupervisor_app_model->insert_rhso_sanitation_data($doc_data, $email);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    	}elseif (isset($submission_type) && $submission_type == 'Dormitory') {
    		
    		$check = $this->healthsupervisor_app_model->check_today_submission($school, $district, $date);

    		if($check == 'Success'){

    			$doc_data['Description'] = $post['dormitory_discription'];
    			
    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $dormitory_external_files = array();
				   $dormitory_external_final = array();
				   $dormitory_external_merged_data = array();
				    if(isset($_FILES['dormitory_attachments']['name']) && !empty($_FILES['dormitory_attachments']['name']))
    			   {
    			   	   $files = $_FILES;
    				   $cpt = count($_FILES['dormitory_attachments']['name']);
    				    
    				   for($i=0; $i<$cpt; $i++)
    				   {
    					 $_FILES['dormitory_attachments']['name']	= $files['dormitory_attachments']['name'][$i];
    					 $_FILES['dormitory_attachments']['type']	= $files['dormitory_attachments']['type'][$i];
    					 $_FILES['dormitory_attachments']['tmp_name'] = $files['dormitory_attachments']['tmp_name'][$i];
    					 $_FILES['dormitory_attachments']['error']	= $files['dormitory_attachments']['error'][$i];
    					 $_FILES['dormitory_attachments']['size']	= $files['dormitory_attachments']['size'][$i];
    					
    					   foreach ($_FILES as $index => $value)
    				       {			       
    				       
    				       		if(!empty($value['name'] && $index == 'dormitory_attachments'))
    						  	{
    						        $config = array();
    								$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
    								$config['allowed_types'] = '*';
    								$config['max_size']      = '*';
    								$config['encrypt_name']  = TRUE;
    						  	
    						        //create controller upload folder if not exists
    								if (!is_dir($config['upload_path']))
    								{
    									mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
    								}
    					
    								$this->upload->initialize($config);
    								
    								if ( ! $this->upload->do_upload($index))
    								{
    									 echo "external file upload failed";
    					        		 return FALSE;
    								}
    								else
    								{
    									$dormitory_external_files = $this->upload->data();
    									//log_message('debug', 'dormitory_external_files=======5849'.print_r($dormitory_external_files, true));
    									$rand_number = mt_rand();
    									$external_dormitory_data_array = array(
    															"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
    															"file_name" =>$dormitory_external_files['file_name'],
    															"file_path" =>$dormitory_external_files['file_relative_path'],
    															"file_size" =>$dormitory_external_files['file_size']
    														)	);

    									$dormitory_external_final = array_merge($dormitory_external_final,$external_dormitory_data_array);
    									
    								}  
    							}
    						}
    					}
    				
    				$check_image = $this->healthsupervisor_app_model->check_images_rhso_sanitation_data($submission_type, $school, $district, $date);

    				
    				 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
    				  if(isset($check_image[0]['Dormitory']['Attachments']))
    					  {
    					  	//echo print_r($check_image, true);
    							   
    						$dormitory_external_merged_data = array_merge($check_image[0]['Dormitory']['Attachments'],$dormitory_external_final);

    						//echo print_r($dormitory_external_merged_data, true);
    						$doc_data['Attachments'] = array_replace_recursive($check_image[0]['Dormitory']['Attachments'],$dormitory_external_merged_data);

    						
    					  }
    					  else
    					 {
    					    $doc_data['Attachments'] = $dormitory_external_final;
    					 }
    			   	}else
    				   {
    				   		$doc_data['Attachments'] = [];
    				   }


				}
    			 
    			
    			$insert = $this->healthsupervisor_app_model->update_rhso_sanitation_data($doc_data, $school, $district, $date, $submission_type);

    			//echo print_r($insert, true); exit();

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}
    		else
    		{
    			$doc_data['Date'] = $date;
    			$doc_data['School Name'] = $school;
    			$doc_data['District'] = $district;
    			$doc_data['Dormitory']['Description'] = $post['dormitory_discription'];

    			if(isset($_FILES) && !empty($_FILES))
				{

				   $this->load->library('upload');
				   $this->load->library('image_lib');
				   
				   $dormitory_external_files = array();
				   $dormitory_external_final = array();
				   $dormitory_external_merged_data = array();

				     if(isset($_FILES['dormitory_attachments']['name']) && !empty($_FILES['dormitory_attachments']['name']))
				      {
				      	   $files = $_FILES;
				   	   $cpt = count($_FILES['dormitory_attachments']['name']);
				   	    
				   	   for($i=0; $i<$cpt; $i++)
				   	   {
				   		 $_FILES['dormitory_attachments']['name']	= $files['dormitory_attachments']['name'][$i];
				   		 $_FILES['dormitory_attachments']['type']	= $files['dormitory_attachments']['type'][$i];
				   		 $_FILES['dormitory_attachments']['tmp_name'] = $files['dormitory_attachments']['tmp_name'][$i];
				   		 $_FILES['dormitory_attachments']['error']	= $files['dormitory_attachments']['error'][$i];
				   		 $_FILES['dormitory_attachments']['size']	= $files['dormitory_attachments']['size'][$i];
				   		
				   		   foreach ($_FILES as $index => $value)
				   	       {			       
				   	       
				   	       		if(!empty($value['name'] && $index == 'dormitory_attachments'))
				   			  	{
				   			        $config = array();
				   					$config['upload_path']   = UPLOADFOLDERDIR.'public/uploads/rhso_sanitation_pics/';
				   					$config['allowed_types'] = '*';
				   					$config['max_size']      = '*';
				   					$config['encrypt_name']  = TRUE;
				   			  	
				   			        //create controller upload folder if not exists
				   					if (!is_dir($config['upload_path']))
				   					{
				   						mkdir(UPLOADFOLDERDIR."public/uploads/rhso_sanitation_pics/",0777,TRUE);
				   					}
				   		
				   					$this->upload->initialize($config);
				   					
				   					if ( ! $this->upload->do_upload($index))
				   					{
				   						 echo "external file upload failed";
				   		        		 return FALSE;
				   					}
				   					else
				   					{
				   						$dormitory_external_files = $this->upload->data();
				   						//log_message('debug', 'dormitory_external_files=======5849'.print_r($dormitory_external_files, true));
				   						$rand_number = mt_rand();
				   						$external_dormitory_data_array = array(
				   												"DFF_EXTERNAL_ATTACHMENTS_".$rand_number =>	array(
				   												"file_name" =>$dormitory_external_files['file_name'],
				   												"file_path" =>$dormitory_external_files['file_relative_path'],
				   												"file_size" =>$dormitory_external_files['file_size']
				   											)	);

				   						$dormitory_external_final = array_merge($dormitory_external_final,$external_dormitory_data_array);
				   						
				   					}  
				   				}
				   			}
				   		}
				   	 

				   	 //$doc_data['widget_data']['page2']['Family Health Info']['brother5 Data']['attachments'] = $brother5_external_final;
				   	  if(isset($doc_data['Dormitory']['Attachments']))
				   		  {
				   				   
				   			$dormitory_external_merged_data = array_merge($doc_data['Dormitory']['Attachments'],$dormitory_external_final);
				   			$doc_data['Dormitory']['Attachments'] = array_replace_recursive($doc_data['Dormitory']['Attachments'],$dormitory_external_merged_data);
				   		  }
				   		  else
				   		 {
				   		    $doc_data['Dormitory']['Attachments'] = $dormitory_external_final;
				   		 }
				      	}else
				   	   {
				   	   		$doc_data['Dormitory']['Attachments'] = [];
				   	   }
				}
    			  


    			$insert = $this->healthsupervisor_app_model->insert_rhso_sanitation_data($doc_data, $email);

    			if($insert == 'Success'){
    				
    				$this->output->set_output(json_encode('Submitted Successfully'));
    			}else{
    				$this->output->set_output(json_encode('Failed'));
    			}

    		}


    	}

    }

/* Rhso Submission End */

/* Rhso submitted list shwing*/
	
	public function show_rhso_sanitation_submitted_list()
	{
		$post = $_POST;

		$user_type = $post['user_type'];
		$email = $post['email'];
		$start_date = $post['start_date'];
		$end_date = $post['end_date'];

		if(isset($user_type) && !empty($user_type)){

			if(preg_match("/PANACEA/i", $user_type)){
				$data = $this->healthsupervisor_app_model->show_rhso_sanitation_submitted_list($email, $start_date, $end_date);

				//echo print_r($data, TRUE); exit();
				if(!empty($data)){
					
					$this->output->set_output(json_encode($data));
				}else{
					$this->output->set_output(json_encode(array('status' => "No data found")));
				}
				
			}
		}else{
			$this->output->set_output(json_encode(array('status' => "User Type Missing")));
		}

	}

	/*
		Rhso district wise full data showing 

		starting with Counts

	*/

	public function district_wise_counts_for_rhso()
	{
		//$session = $this->session->user_data("customer");
		$post = $_POST;
		$email = $post['email'];
		$district = $post['district'];
		$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];

		$get_data = $this->healthsupervisor_app_model->district_wise_counts_for_rhso($email, $district, $user_type, $academic_year);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}

	}

	/*
		Get get schools names and total request counts and student counts
	*/

	public function get_schools_info_for_rhso_with_counts()
	{
		$post = $_POST;
		$email = $post['email'];
		$district = isset($post['district']) ? $post['district'] : "All";
		$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];

		$get_data = $this->healthsupervisor_app_model->get_schools_info_for_rhso_with_counts($district, $user_type, $academic_year);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	// Span for requests

	public function get_requests_for_selected_span()
	{
		$post = $_POST;
		//$email = $post['email'];
		//$district = $post['district'];
		//$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];
		$start = $post['start_date'];
		$end = $post['end_date'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$get_data = $this->healthsupervisor_app_model->get_requests_for_selected_span($district, $user_type, $start, $end, $school);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/*
		Get Diseases with selected Sub Request
	*/
	public function get_diseases_with_counts_for_request_type()
	{
		$post = $_POST;
		$email = $post['email'];
		$district = $post['district'];
		//$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];
		$start = $post['start_date'];
		$end = $post['end_date'];
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';
		$request = $post['request_type'];

		$get_data = $this->healthsupervisor_app_model->get_diseases_with_counts_for_request_type($district, $user_type, $start, $end, $school, $request);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/*
		Get students for Diseases
	*/

	public function get_students_for_selected_disease_for_span()
	{
		$post = $_POST;
		$email = $post['email'];
		$district = $post['district'];
		//$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];
		$start = $post['start_date'];
		$end = $post['end_date'];
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';
		$request = $post['request_type'];
		$symptom = $post['symptom_name'];

		$get_data = $this->healthsupervisor_app_model->get_students_for_selected_disease_for_span($district, $user_type, $start, $end, $school, $request, $symptom);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	// Global Search for requests
	public function get_search_with_name_or_id_for_active_requests()
	{
		$post = $_POST;
		//$email = $post['email'];
		$district = $post['district'];
		//$academic_year = $post['academic_year'];
		$user_type = $post['user_type'];
		$search = $post['search_value'];

		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$get_data = $this->healthsupervisor_app_model->get_search_with_name_or_id_for_active_requests($district, $user_type, $search, $school);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	// Hospitalised students list for hospitals followup.

	public function get_hospitalised_students_list_for_followup()
	{
		$post = $_POST;
		//$email = $post['email'];
		$district = $post['district'];
		$user_type = $post['user_type'];

		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';
		
		$get_data = $this->healthsupervisor_app_model->get_hospitalised_students_list_for_followup($district, $user_type, $school);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/*
		School level app apis for new version
	*/

	public function get_counts_for_school_level_app()
	{
		//$session = $this->session->user_data("customer");
		$post = $_POST;
		$user_type = $post['user_type'];
		$school = $post['school_name'];
		$academic = $post['academic_year'];

		$get_data = $this->healthsupervisor_app_model->get_counts_for_school_level_app($user_type, $school,$academic);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	public function get_latest_submitted_hb_values()
	{
		$post = $_POST;
		$user_type= $post['user_type'];
		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$start_date = (isset($post['start_date']) && !empty($post['start_date'])) ? $post['start_date'] : FALSE;
		$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : FALSE;

		$get_data = $this->healthsupervisor_app_model->get_latest_submitted_hb_values($user_type, $district, $school, $start_date, $end_date);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	public function get_hb_values_school_wise_count()
	{
		$post = $_POST;
		$user_type = $post['user_type'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$hb_type = $post['hb_type'];

		$get_data = $this->healthsupervisor_app_model->get_hb_values_school_wise_count($user_type, $district, $school, $hb_type);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}


	}

	public function get_students_for_latest_submitted_hb()
	{
		$post = $_POST;
		$user_type = $post['user_type'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$hb_type = $post['hb_type'];
		//$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : FALSE;

		$fetch_count = $post['doc_skip_count'];

		$get_data = $this->healthsupervisor_app_model->get_students_for_latest_submitted_hb($user_type, $district, $school, $fetch_count, $hb_type);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}


	}

	public function get_latest_submitted_bmi_values()
	{
		$post = $_POST;
		$user_type= $post['user_type'];
		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$start_date = (isset($post['start_date']) && !empty($post['start_date'])) ? $post['start_date'] : FALSE;
		$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : FALSE;

		$get_data = $this->healthsupervisor_app_model->get_latest_submitted_bmi_values($user_type, $district, $school, $start_date, $end_date);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/* Get Schools for bmi counts */

	public function get_bmi_values_school_wise_count()
	{
		$post = $_POST;
		$user_type = $post['user_type'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$bmi_type = $post['bmi_type'];
		
		$get_data = $this->healthsupervisor_app_model->get_bmi_values_school_wise_count($user_type, $district, $school, $bmi_type);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}

	}

	/* Get Students for bmi */

	public function get_students_for_latest_submitted_bmi()
	{
		$post = $_POST;
		$user_type = $post['user_type'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';

		$bmi_type = $post['bmi_type'];
		//$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : FALSE;

		$fetch_count = $post['doc_skip_count'];

		$get_data = $this->healthsupervisor_app_model->get_students_for_latest_submitted_bmi($user_type, $district, $school, $fetch_count, $bmi_type);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}


	}

	/* Admin App For All welfares */
	public function get_total_counts_for_admin()
	{
		//$session = $this->session->user_data("customer");
		$post = $_POST;
		$user_type = $post['user_type'];
		//$district = $post['district'];
		//$school = $post['school_name'];
		$academic = $post['academic_year'];
		$today_date = $post['today_date'];

		$get_data = $this->healthsupervisor_app_model->get_total_counts_for_admin($user_type, $academic, $today_date);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/* Get Time SChedules based on parameters */
	public function get_start_end_date($today_date, $request_duration) {

		if ($request_duration == "Daily") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			//log_message("debug","today_date today_date======2165".print_r($today_date,true));
			//log_message("debug","end_date end_date======2166".print_r($end_date,true));
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Weekly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-6 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Bi Weekly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-13 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Monthly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-1 month" ) );
			$end_date = date ( "Y-m-d", strtotime ( $end_date . "0 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Bi Monthly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-2 month" ) );
			$end_date = date ( "Y-m-d", strtotime ( $end_date . "0 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Quarterly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-3 month" ) );
			$end_date = date ( "Y-m-d", strtotime ( $end_date . "0 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		} else if ($request_duration == "Half Yearly") {
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			
			$end_date = date ( "Y-m-d", strtotime ( $today_date . "-6 month" ) );
			$end_date = date ( "Y-m-d", strtotime ( $end_date . "0 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			return $dates;
		}
		else if ($request_duration == "Yearly") {
			$end_date = "2018-06-01";
			$date = new DateTime ( $today_date );
			$today_date = $date->format ( 'Y-m-d H:i:s' );
			//$month = explode("-",$today_date);
			//$month_final = "-".$month[1]." month";
			$end_date = date ( "Y-m-d", strtotime ( $end_date ) );
			//$end_date = date ( "Y-m-d", strtotime ( $end_date . "1 day" ) );
			$today_date = date ( "Y-m-d", strtotime ( $today_date . "0 day" ) );
			$dates ['today_date'] = $today_date;
			$dates ['end_date'] = $end_date;
			//echo print_r($dates,TRUE);exit();
			return $dates;
		}
	}

	/* Rhso And RC work */
	public function get_total_counts_for_rhso_rc_work_for_admin()
	{
		
		$post = $_POST;
		$user_type = $post['user_type'];
		//$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		//$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';
		$request_duration = $post['request_duration'];
		//$today_date = $post['today_date'];
		$today_date = date('Y-m-d');

		$getDates = $this->get_start_end_date ( $today_date, $request_duration );

		$start_date = $getDates['end_date'];
		$end_date = $getDates['today_date'];

		$get_data = $this->healthsupervisor_app_model->get_total_counts_for_rhso_rc_work_for_admin($user_type, $start_date, $end_date);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/* Rhso name wise dictrict wise clickability */
	public function get_school_and_hospital_submission_rhso_wise()
	{
		$post = $_POST;

		$request_duration = $post['request_duration'];
		$today_date = date('Y-m-d');
		$selected_option = $post['hospital_or_scl'];
		$user_type = $post['user_type'];

		$getDates = $this->get_start_end_date ( $today_date, $request_duration );

		$start_date = $getDates['end_date'];
		$end_date = $getDates['today_date'];

		$get_data = $this->healthsupervisor_app_model->get_school_and_hospital_submission_rhso_wise($user_type, $start_date, $end_date, $selected_option);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}

	}

	/* sCHOOL tOTAL COUNT*/

	public function total_school_students_count()
	{
		$user_type =$_POST['user_type'];
		$school_name =$_POST['school_name'];

		$get_data = $this->healthsupervisor_app_model->total_school_students_count($user_type, $school_name);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/* Power of Ten starts*/

	public function register_for_subscription()
	{
		$post = $_POST;

		if(!empty($post['email'] && $post['password']))
		{
			$check_email = $post['email'];
			$check_mobile1 = $post['mobile_no'];
			$passwords = $post['password'];
			

			$doc_data['name'] =  isset($post['name']) ? $post['name'] :"Nil";
			$doc_data['email'] =  isset($post['email']) ? trim($post['email']):"Nil";
			$doc_data['password'] =  isset($post['password']) ? trim($post['password']):"Nil"; 
			$doc_data['mobile_no'] =  isset($post['mobile_no']) ? $post['mobile_no']:"Nil"; 
			$doc_data['gender'] =  isset($post['gender']) ? $post['gender'] :"Nil";
			$doc_data['are_you_alumni_of'] =  isset($post['are_you_alumni_of']) ? $post['are_you_alumni_of'] :"Nil";
			$doc_data['course_name'] =  isset($post['course_name']) ? $post['course_name'] :"Nil";
			$doc_data['institution_name'] =  isset($post['institution_name']) ? $post['institution_name'] :"Nil";
			$doc_data['passed_out_year'] =  isset($post['passed_out_year']) ? $post['passed_out_year'] :"Nil";
			$doc_data['blood_group'] =  isset($post['blood_group']) ? $post['blood_group']:"Nil";
			$doc_data['district'] =  isset($post['district']) ? $post['district']:"Nil";

			$doc_data['device_unique_number'] = isset($post['device_unique_number']) ? $post['device_unique_number']:"Nil";
			$doc_data['location'] = isset($post['location']) ? $post['location']:"Nil";

			
			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "POWEROFTEN";
			$doc_properties['doc_flow'] = "new";
			$doc_properties['registration_status'] = 0; // if payment done this will be 1
			// History
			$approval_data = array(
				"current_stage" => "registration",
				"submitted_by" => $post['email'],
				"time" => date('Y-m-d H:i:s'));

			$history['last_stage'] = $approval_data;

			$submitted_data = $this->healthsupervisor_app_model->register_for_subscription($doc_data, $doc_properties, $history,$check_email, $check_mobile1, $passwords);

			if($submitted_data == TRUE)
			{
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Registred Succesfully, Wait for the verification message')
									));

			}elseif($submitted_data == FALSE){

			}elseif ($submitted_data == "user_already_exists") {

				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Already Registred with this details')
									));

			}elseif ($submitted_data == "Email_mobile_empty") {

				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Please Provide Email and Mobile no')
									));
			}
		}
		else
		{
			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Fill the Details')
									));
		}
	}


	public function check_registration_status()
	{
		$post = $_POST;
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		$check = $this->healthsupervisor_app_model->check_registration_status($email_id, $mobile, $doc_properties_id);

		if($check == "payment_success")
		{
			$this->output->set_output(json_encode(
								array(
									'status' => TRUE, 
									'message' => 'Payment Done')
								));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Payment Not Done')
								));
		}
	}

	public function power_of_ten_payment_details()
	{
		$post = $_POST;
		
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		if(!empty($email_id) && !empty($mobile)&& !empty($doc_properties_id))
		{
			$data = array(
				'payment_id'=> isset($post['payment_id']) ? $post['payment_id']:"Nil",
				'payment_status'=>isset($post['payment_status']) ? $post['payment_status']:"Nil", 
				'payment_amount'=>isset($post['payment_amount']) ? $post['payment_amount']:"Nil", 
				'payment_description'=>isset($post['payment_description']) ? $post['payment_description']:"Nil", 
				'payment_date'=>isset($post['payment_date']) ? $post['payment_date']:"Nil"
			);

			$check = $this->healthsupervisor_app_model->power_of_ten_payment_details($email_id, $mobile, $doc_properties_id, $data);

			if($check == "Data inserted")
			{
				$this->output->set_output(json_encode(
								array(
									'message' => "Data Inserted")
								));
			}
			else{
				$this->output->set_output(json_encode(
								array(
									'message' => "Data not inserted")
								));
			}

		}else{
			$this->output->set_output(json_encode(
								array(
									'message' => "Email and password empty")
								));
		}
		
	}

	public function change_user_password()
	{
		$post = $_POST;
		$email_id = $post['email'];
		$mobile = $post['phone_no'];

		$change_password = $this->input->post('change_password', true);

		$check = $this->healthsupervisor_app_model->change_user_password($email_id, $mobile, $change_password);

		if($check == TRUE)
		{
			$this->output->set_output(json_encode(
								array(
									'status' => TRUE, 
									'message' => 'Password Changed successfully')
								));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Password Change Failed')
								));
		}
	}

	public function send_request_to_command_center()
	{

		$post = $_POST;
		
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		$request_type = $post['request_type'];
		$request_description = $post['request_description'];

		$data = $this->healthsupervisor_app_model->send_request_to_command_center($email_id, $mobile, $doc_properties_id, $request_type, $request_description);

		if($data == "submitted_successfully"){
			$this->output->set_output(json_encode(
								array(
									'status' => TRUE, 
									'message' => 'Submitted successfully')
								));
		}elseif($data == "not_submitted"){
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Submi Failed')
								));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Your Doc id not found, contact command center')
								));
		}

	}

	public function show_requests_send_to_cc()
	{

		$post = $_POST;
		
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		
		$data = $this->healthsupervisor_app_model->show_requests_send_to_cc($email_id, $mobile, $doc_properties_id);

		if($data){
			$this->output->set_output(json_encode($data));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'No Data Found')
								));
		

		}
	}

	public function show_questions_send_to_cc()
	{

		$post = $_POST;
		
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		
		$data = $this->healthsupervisor_app_model->show_questions_send_to_cc($email_id, $mobile, $doc_properties_id);

		if($data){
			$this->output->set_output(json_encode($data));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'No Data Found')
								));

		}
	}

	public function ask_questions_to_command_center()
	{
		$post = $_POST;
		
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		$question_type = $post['question_type'];
		$question_description = $post['question_description'];

		$data = $this->healthsupervisor_app_model->ask_questions_to_command_center($email_id, $mobile, $doc_properties_id, $question_type, $question_description);

		if($data == "submitted_successfully"){
			$this->output->set_output(json_encode(
								array(
									'status' => TRUE, 
									'message' => 'Submitted successfully')
								));
		}elseif($data == "not_submitted"){
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Submi Failed')
								));
		}else{
			$this->output->set_output(json_encode(
								array(
									'status' => FALSE, 
									'message' => 'Your Doc id not found, contact command center')
								));
		}

	}

	public function emergency_button_calling()
	{
		$post = $_POST;
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];
		$current_district = isset($post['current_district']) ? $post['current_district'] : "Nil";

		$reason_for_call = isset($post['reason_for_call']) ? $post['reason_for_call'] : "Nil";

		$latandlng = isset($post['location']) ? $post['location']:"Nil";

		$location_info = isset($post['location_info']) ? $post['location_info']:"Nil";

		$calling_time = date('Y-m-d H:i:s');

		if(isset($_FILES['audio_record']['name']) && !empty($_FILES['audio_record']['name']))
		{
			$this->load->library('upload');
			$upload_info = array();

			$config['upload_path'] = UPLOADFOLDERDIR.'public/uploads/poweroften_audio/'.$mobile.'/';
			$config['allowed_types'] = "*";
			$config['max_size'] = "4096";
			$config['encrypt_name'] =TRUE;

			if(!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR.'public/uploads/poweroften_audio/'.$mobile.'/',0777,TRUE);
			}

			$this->upload->initialize($config);

			foreach ($_FILES as $index => $value) {
				if(!empty($value['name'])){

					if(!$this->upload->do_upload($index)){

					    echo "FILE_UPLOAD_FAILED";
					    log_message('debug','DEVICE=====UPLOADEMERGENCYBUTTONAUDIO=====$ERROR==>'.print_r($this->upload->display_errors(),true));
				        return FALSE;

					}else{

						array_push($upload_info,$this->upload->data());
					}
				}
			}

			$audio_file_info = array(
				  "file_client_name"    => $upload_info[0]['client_name'],
				  "file_encrypted_name" => $upload_info[0]['file_name'],
				  "file_path"           => $upload_info[0]['file_relative_path'],
				  "file_size"           => $upload_info[0]['file_size']
				  );
		}


		$audio = isset($audio_file_info) ? $audio_file_info:FALSE;

		$data = $this->healthsupervisor_app_model->emergency_button_calling($email_id, $mobile, $doc_properties_id, $calling_time, $reason_for_call, $audio, $latandlng, $current_district, $location_info);


		if($audio != FALSE){

			$audioFilePath = $audio['file_path'];
		}else{
			$audioFilePath = "Nil";
		}

		$sent_data = $this->send_notification_nearBy_from_fcm($audioFilePath, $reason_for_call, $doc_properties_id, $current_district, $latandlng, $location_info);

		if($sent_data != FALSE){

			if($data == "submitted_successfully"){
				$this->output->set_output(json_encode(
									array(
										'status' => TRUE, 
										'message' => 'Submitted successfully')
									));
			}elseif($data == "not_submitted"){
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Submi Failed')
									));
			}else{
				$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'Your Doc id not found, contact command center')
									));
			}
		}else{

			$this->output->set_output(json_encode(
									array(
										'status' => FALSE, 
										'message' => 'No one found in this district, contact command center')
									));
		}


	}

	public function news_feed_data_showing()
	{
		$post = $_POST;
		$email_id = $post['email'];
		$mobile = $post['phone_no'];
		$doc_properties_id = $post['doc_properties_id'];

		$data = $this->healthsupervisor_app_model->news_feed_data_showing();

		if($data){
			$this->output->set_output(json_encode($data));
		}else{
			$this->output->set_output(json_encode(
								array(
									'message' => 'No News Feed Found')
								));
		}

	}

	//fetched data ofregistered

	public function get_list_for_district_level_verification()
	{
		$post = $_POST;
		$district = $post['district'];

		$fetch_count = $post['doc_skip_count'];

		$get_data = $this->healthsupervisor_app_model->get_list_for_district_level_verification($district, $fetch_count);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}


	}

	public function district_level_verification_submission()
	{
		$post = $_POST;

		$doc_data = array(
			'email' => $post['email'],
			'mobile_no' => $post['mobile_no'],
			'district' => $post['district'],
			'are_you_alumni_of' => $post['are_you_alumni_of'],
			'school_verification' => $post['school_verification'],
			'alumni_verification' => $post['alumni_verification'],
			'membership_verification' => $post['membership_verification'],
			'recommended_description' => $post['recommended_description'],
			'district_officer_email' => $post['district_officer_email'],
			'doc_id' => $post['doc_id']

		);

		$get_data = $this->healthsupervisor_app_model->district_level_verification_submission($doc_data);

		if($get_data == "Inserted Successfully, Application at DAR Team. Wait for call please" || $get_data == "User in not recommended list"){

			$this->output->set_output(json_encode(array('status'=>TRUE, 'message'=>$get_data)));

		}else{
			$this->output->set_output(json_encode(array('status'=>FALSE, 'message'=>$get_data)));
		}
		

	}

	public function send_notification_nearBy_from_fcm($audioFilePath=FALSE, $reason_for_call, $doc_properties_id, $current_district, $latandlng, $location_info)
	 {

	 	/*$data['location_info'] = $location_info;
	 	$data['reason'] = $reason_for_call;
	 	//$data['audio_file'] = $audioFilePath;
	 	
	 	$data['location'] = $latandlng;*/

	 	$data = array("location_info"=>$location_info, "reason"=>$reason_for_call, "location"=>$latandlng, "audio"=>$audioFilePath);

		$path_to_fcm='https://fcm.googleapis.com/fcm/send';
		$server_key="AAAAkAW7QYI:APA91bHgYdZ-r2JGWssyQUJj7J_y_LekU96Ig1mHbWH44SUU4c1lsWHGZ_8q8XnPOpb0GJ2xWZDgGDU2rTs3T_i1y0juaawKZ0wwfJayfr0-bFB1oKj-x0dc-toe_VAGJWO5eVyn83DA";
		
		/*$server_key = "AIzaSyAb3gO8QyIWJduMGfrdQL3Gc0y9omfUDdQ";*/

		$get_district_people = $this->healthsupervisor_app_model->get_district_people_for_notification($current_district);

		//echo print_r($get_district_people, true);
		//exit();

	
			$headers=array('Authorization:key='.$server_key,
			               'Content-Type:application/json');
			               
			$fields=array('registration_ids'=>$get_district_people,
			               'notification'=>array('title'=>"Emergency Need",'body'=>$data, 'vibrate' => 1, 'sound' => 1));
			               
			               
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

		$notified_persons['id'] = $get_district_people;
		$notified_persons['response'] = $result;
		$notified_persons['date'] = date('Y-m-d H:i:s');
		
		$save_data = $this->healthsupervisor_app_model->save_notification_data($notified_persons);
		
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
			
		
	 }

/*	public function notify_accepted_person_data()
	{
		$data['name'] = $_POST['name'];
		$data['email'] = $_POST['email'];
		$data['lat_lng'] = $_POST['lat_lng'];
		$data['current_address'] = $_POST['current_address'];
		$data['doc_properties_id'] = $_POST['doc_properties_id']; 
		$data['needy_person_doc_id'] = $_POST['needy_person_doc_id'];

		$insert = $this->healthsupervisor_app_model->notify_accepted_person_data($data);

		if($insert == "District Not Found"){

			$this->output->set_output(json_encode($insert));

		}else{

			
				$msg = "$data['name'], came forward to help";
			 	$data = array("reason"=> $msg);

				$path_to_fcm='https://fcm.googleapis.com/fcm/send';
				$server_key="AAAAkAW7QYI:APA91bHgYdZ-r2JGWssyQUJj7J_y_LekU96Ig1mHbWH44SUU4c1lsWHGZ_8q8XnPOpb0GJ2xWZDgGDU2rTs3T_i1y0juaawKZ0wwfJayfr0-bFB1oKj-x0dc-toe_VAGJWO5eVyn83DA";
				
				/*$server_key = "AIzaSyAb3gO8QyIWJduMGfrdQL3Gc0y9omfUDdQ";*/

				//$get_district_people = $this->healthsupervisor_app_model->get_district_people_for_notification($insert);

				//echo print_r($get_district_people, true);
				//exit();

			
					/*$headers=array('Authorization:key='.$server_key,
					               'Content-Type:application/json');
					               
					$fields=array('registration_ids'=>$get_district_people,
					               'notification'=>array('title'=>"Helping alert",'body'=>$data, 'vibrate' => 1, 'sound' => 1));
					               
					               
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

				if($result){
					return TRUE;
				}else{
					return FALSE;
				}
		

		}
		
	}*/

	 public function apply_for_vidya_nidhi()
	 {
	 	/*$session_data = $this->session->userdata("customer");
	 	$data['email'] = $session_data['email'];
	 	$data['phone'] = $session_data['phone_no'];
	 	$data['doc_id'] = $session_data['doc_properties_id'];*/

	 	$data['name'] = $_POST['name'];
	 	$data['applying_for'] = $_POST['applying_for'];
	 	$data['school_name'] = $_POST['school_name'];
	 	

	 	$data['date'] = date('Y-m-d H:i:s');

	 	$data['status'] = "Prosessing";

	 	
	 	
		$data = $this->healthsupervisor_app_model->vidya_nidhi_applying($data);

		$this->output->set_output(json_encode($data));
	 }

	

	/* Power of Ten End */

/*POST Notes Option*/
/*Author by Yoga*/

public function create_hs_note()
  	{ 		  		
	  
	  	//$doc_id   = $this->input->post('doc_id');
        //$emails   = $this->input->post('$email');
        $user_type = $_POST['user_type'];
	  	
                $email           = $this->input->post('email', true);
                $email_array    = explode(".",$email);
                $school_code    = (int) $email_array[1];
                $school_info = $this->healthsupervisor_app_model->get_school_info($school_code,$user_type);             
                $school_name = $school_info[0]['school_name'];
                                        
                $emails = str_replace("@","#",$email); 
							
				$raised_time = $this->input->post('raised_note_time');               
				$hs_note = $this->input->post('remarks');                
				$unique_id   = $this->input->post('student_code');
				$student_name = $this->input->post('student_name');
				$class = $this->input->post('student_class');			

				$data['datetime'] = $raised_time;
				$data['note']= $hs_note;	
                $data['username'] = $emails;
				$data['uid'] = $unique_id;
				$data['Name'] = $student_name;
				$data['Class'] = $class;               
                $data['School_Name'] = $school_name;    		 
			

		$note_created = $this->healthsupervisor_app_model->submit_hs_note($data, $user_type);

		    if(!empty($note_created))
		  {
	        $this->output->set_output(json_encode($note_created));
		  }

          $this->output->set_output(json_encode(array(
                                        'status' => TRUE, 
                                        'message' => 'Submitted Successfully')
                                    ));

				
	}


	function get_submitted_students_notes()

    {   
        $email = $_POST['email'];
        $date = $_POST['selected_date'];
        $user_type = $_POST['user_type'];

        $query = $this->healthsupervisor_app_model->get_submitted_students_notes_modal($email,$date,$user_type);

        if(!empty($query))
        {
            $this->output->set_output(json_encode($query));

        }else{

            $this->output->set_output(json_encode(json_encode("No Students found")));

        }

    }

/*POST Vaccination Status Interface*/
/*Author by Yoga Narasimha Reddy*/

public function submit_vaccination_ststus()
  	{ 	
	  	
        $user_type = $_POST['user_type'];
	  	
                $email           = $this->input->post('email', true);
                $email_array    = explode(".",$email);
                $school_code    = (int) $email_array[1];
                $school_info = $this->healthsupervisor_app_model->get_school_info($school_code,$user_type);             
                $school_name = $school_info[0]['school_name'];
                $dist = explode(',', $school_name);
				$districtName = $dist[1];
                                        
                $emails = str_replace("@","#",$email); 
							
				$unique_id   = $this->input->post('student_code');
				$student_name = $this->input->post('student_name');
				$class = $this->input->post('student_class');			
				$affected = $this->input->post('covid_affected');               
				$vaccine_type = $this->input->post('vaccine_name');                
				$vaccination_date = $this->input->post('vaccine_date');                
				$dosage = $this->input->post('vaccine_dose');                
				$submitted_by = $this->input->post('submitted_date');                

				$data['uid'] = $unique_id;
				$data['Name'] = $student_name;
				$data['Class'] = $class;               
                $data['School_Name'] = $school_name;    		 
                $data['District_Name'] = $districtName;    		 
				$data['Covid_Affected'] = $affected;
				$data['Vaccine_Type']= $vaccine_type;	
				$data['Vaccine_dosage']= $dosage;	
				$data['Vaccination_date']= $vaccination_date;	
                $data['Submitted_Date'] = $submitted_by;			
                $data['username'] = $emails;			

		$vaccine_status = $this->healthsupervisor_app_model->submit_vaccination_ststus_model($data, $user_type);

		    if(!empty($vaccine_status))
		  {
	        $this->output->set_output(json_encode($vaccine_status));
		  }

          $this->output->set_output(json_encode(array(
                                        'status' => TRUE, 
                                        'message' => 'Submitted Successfully')
                                    ));
				
	}

	function get_submitted_students_vaccination_status()

    {   
        $email = $_POST['email'];
        $date = $_POST['selected_date'];
        $user_type = $_POST['user_type'];

        $query = $this->healthsupervisor_app_model->get_submitted_students_vaccination_status_modal($email,$date,$user_type);

        if(!empty($query))
        {
            $this->output->set_output(json_encode($query));

        }else{

            $this->output->set_output(json_encode(json_encode("No Students found")));

        }

    }

  /*HS Notes shows in RHSO Interface*/
/*Author by Yoga Narasimha Reddy*/

public function get_hs_notes_school_wise_count()
{
	$post = $_POST;
	$user_type = $post['user_type'];
	$today_date = $_POST['today_date'];

	$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All'; 
	$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';		 
     /*$dist = explode(',', $school);
	 $district = $dist[1];*/
	
	$get_data = $this->healthsupervisor_app_model->get_hs_notes_school_wise_count_modal($user_type, $district, $school,$today_date);

	if(!empty($get_data))
	{
		$this->output->set_output(json_encode($get_data));
	}else{
		$this->output->set_output(json_encode(array('status'=>"No Data Found")));
	}

}


public function get_students_for_hs_notes_school_wise()
	{
		$post = $_POST;
		$user_type = $post['user_type'];
		$today_date = $_POST['today_date'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';		

		$get_data = $this->healthsupervisor_app_model->get_students_for_hs_notes_school_wise_modal($user_type, $district, $school, $today_date);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}


	}

	/* CC Users API's 
		Author Harish Seelam
	*/

	/*
		API for showing school list with respective persons contact no
	*/

	public function school_names_with_phone_numbers()
	{
		$post = $_POST;

		$user_type = $post['user_type'];
		$email = $post['email'];

		$get_data = $this->healthsupervisor_app_model->school_names_with_phone_numbers($user_type, $email);

		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}
	}

	/* 
		Show requests to CC app users. They can able to raise request
		Adding contact no's in return array
	*/

	public function get_students_requests_data_with_phone_no()
	{
		$post = $_POST;
		$user_type = $post['user_type'];

		$district = (isset($post['district']) && !empty($post['district'])) ? $post['district'] : 'All';
		$school = (isset($post['school_name']) && !empty($post['school_name'])) ? $post['school_name'] : 'All';
		$start_date = (isset($post['start_date']) && !empty($post['start_date'])) ? $post['start_date'] : date('Y-m-d');
		$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : date('Y-m-d');

		$request_type = $post['request_type'];
		//$end_date = (isset($post['end_date']) && !empty($post['end_date'])) ? $post['end_date'] : FALSE;

		$fetch_count = $post['doc_skip_count'];

		$get_data = $this->healthsupervisor_app_model->get_students_requests_data_with_phone_no($user_type, $district, $school, $start_date, $end_date, $fetch_count, $request_type);
		
		if(!empty($get_data))
		{
			$this->output->set_output(json_encode($get_data));
		}else{
			$this->output->set_output(json_encode(array('status'=>"No Data Found")));
		}

	}

/* Save Call recording for CC users */
	public function save_cc_user_call_recording()
	{
		$post = $_POST;
		$data['email_id'] = $post['email'];
		$data['user_type'] = $post['user_type']; 
		$data['cc_user_name'] =$post['cc_user_name'];
		$data['call_type'] =$post['call_type']; /* Type will be Health requests or attendace or other*/
		$data['call_to'] = $post['call_to'];
		$data['doc_id'] = get_unique_id();
 		$data['date_time'] = date('Y-m-d H:i:s');

 		if(isset($post['call_type']) && $post['call_type'] == "Request"){
 			$data['call_data'] = array(
										"request_type" => $post['request_type'],
										"request_doc_id" => $post['request_doc_id'],
										"unique_id" => $post["unique_id"]
										);
 		}elseif (isset($post['call_type']) && ($post['call_type'] == "Attendence" || $post['call_type'] == "Sanitation") ) {
 			$data['call_data'] = array(
										"school_name" => $post['school_name'],
										"district" => $post["district"]
 										);
 		}else{
 			$data['call_data'] = array(
										"call_purpose" => $post['call_purpose'],
										"school_name" => $post['school_name'],
										"district" => $post["district"]
 										);
 		}

		if(preg_match("/PANACEA/i", $post['user_type']) || preg_match("/TSWREIS/i", $post['user_type'])){
			$user_folder = "tswreis";
		}elseif (preg_match("/TTWREIS/i", $post['user_type'])) {
			$user_folder = "ttwreis";
		}elseif(preg_match("/BCWELFARE/i", $post['user_type'])){
			$user_folder = "bcwelfare";
		}

		/* File Upload*/
		if(isset($_FILES['audio_record']['name']) && !empty($_FILES['audio_record']['name']))
		{
			$this->load->library('upload');
			$upload_info = array();

			$config['upload_path'] = UPLOADFOLDERDIR.'public/uploads/cc_user_calls_audio/'.$user_folder.'/';
			$config['allowed_types'] = "*";
			$config['max_size'] = "*";
			$config['encrypt_name'] =TRUE;

			if(!is_dir($config['upload_path']))
			{
				mkdir(UPLOADFOLDERDIR.'public/uploads/cc_user_calls_audio/'.$user_folder.'/',0777,TRUE);
			}

			$this->upload->initialize($config);

			foreach ($_FILES as $index => $value) {
				if(!empty($value['name'])){

					if(!$this->upload->do_upload($index)){

					    echo "FILE_UPLOAD_FAILED";
					    log_message('debug','DEVICE=====UPLOADEMERGENCYBUTTONAUDIO=====$ERROR==>'.print_r($this->upload->display_errors(),true));
				        return FALSE;

					}else{

						array_push($upload_info,$this->upload->data());
					}
				}
			}

			$audio_file_info = array(
				  "file_client_name"    => $upload_info[0]['client_name'],
				  "file_encrypted_name" => $upload_info[0]['file_name'],
				  "file_path"           => $upload_info[0]['file_relative_path'],
				  "file_size"           => $upload_info[0]['file_size']
				  );
		}


		$data['audio'] = isset($audio_file_info) ? $audio_file_info:FALSE;

		$get_data = $this->healthsupervisor_app_model->save_cc_user_call_recording($data, $data['user_type']);

		if($get_data == TRUE)
		{
			$this->output->set_output(json_encode(array('status'=>"Data submitted Succesfully")));
		}else{
			$this->output->set_output(json_encode(array('status'=>"Data Not submitted")));
		}

	}


/* Save Menstural cycle data*/
public function save_monthly_menstural_data()
{
	$data['user_type'] = $_POST['user_type'];
	$data['uid'] = $_POST['uid'];
	$data['lastPeriodStartDate'] = $_POST['lastPeriodStartDate'];
	$data['periodStartDate'] = $_POST['periodStartDate'];
	$data['menstrualZone'] = $_POST['mensuralZone'];
	$data['datetime'] = date('Y-m-d H:i:s');

	$get_data = $this->healthsupervisor_app_model->save_monthly_menstural_data($data);

	if($get_data == TRUE)
	{
		$this->output->set_output(json_encode(array('status'=>"Data submitted Succesfully")));
	}else{
		$this->output->set_output(json_encode(array('status'=>"Data Not submitted")));
	}
}

/* Get last submitted data */
public function get_menstural_last_submitted_date(){
	$data = $_POST;

	$get_data = $this->healthsupervisor_app_model->get_menstural_last_submitted_date($data);

	if(!empty($get_data))
	{
		$this->output->set_output(json_encode($get_data));
	}else{
		$this->output->set_output(json_encode(array('status'=>"No Data Found")));
	}

}

/*************************************************************/

	
}

/* End of file signup.php */
/* Location: ./application/customers/controllers/patient_login.php */
