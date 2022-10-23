<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Panacea_ts_normal extends My_Controller {
   
    /* __construct
     *
     * @author  Yoga narasimha
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
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
        $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->library('paas_common_lib');
        $this->load->library('tswreis_schools_common_lib');
        $this->load->model('panacea_cc_model');
        $this->load->model('panacea_common_model');
        $this->load->model('tswreis_schools_common_model');
        $this->load->library('panacea_common_lib');
    }


    public function fetch_normal_requests_docs()
    {
       
        $collection = "healthcare2016531124515424_static_html";
        $hs_req_docs  = $this->panacea_cc_model->get_hs_req_normal($collection);
       /* echo '<pre>';
         echo print_r($hs_req_docs, true); 
         echo '</pre>';
         exit();*/
        $hs_req_emergency  = $this->panacea_cc_model->get_hs_req_emergency($collection);

        $hs_req_chronic  = $this->panacea_cc_model->get_hs_req_chronic($collection);
            
            if(!empty($hs_req_docs)){
            $this->data['hs_req_docs'] = $hs_req_docs;
        }else{
            $this->data['hs_req_docs'] = "";
        }

        if(!empty($hs_req_emergency)){
            $this->data['hs_req_emergency'] = $hs_req_emergency;
        }else{
            $this->data['hs_req_emergency'] = "";
        }
        
        if(!empty($hs_req_chronic)){
            $this->data['hs_req_chronic'] = $hs_req_chronic;
        }else{
            $this->data['hs_req_chronic'] = "";
        }
        
        $this->_render_page('panacea_cc_normal/fetch_normal_requests_docs',$this->data);
    }

    public function update_regular_followup_feed_data()
    {

            $student_id = $this->input->post('student_id');
            $case_id = trim($this->input->post('case_id'));
            $created_time = $this->input->post('feeding_date');
            $medicine_details = $this->input->post('medicine_details');
            $followup_desc = $this->input->post('followup_desc');
        

            $update = $this->panacea_common_model->update_requests_followup_data($student_id,$case_id,$created_time,$medicine_details,$followup_desc);
             if($added)
    {
        $this->session->set_flashdata('success', "Notes updated successfully");
    }

            redirect('panacea_ts_normal/fetch_normal_requests_docs');        
        
    }

    public function access_submited_notes_request_docs($id)
       {
            $doc_id = $id;
            $query = $this->panacea_cc_model->access_submited_request_docs($doc_id);
            $this->data['hs_req_docs'] = $query;
            $this->data['hs_req_emergency'] = $query;
            $this->data['hs_req_chronic'] = $query;
            $this->_render_page('panacea_cc_normal/access_submited_notes_request_docs',$this->data);
        }

        //upadate request
        public function update_notes_request_and_submit()
        {
            // POST DATA
            $doc_id = $this->input->post('doc_id',true);
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

            $age = "";
            if($class == "5")
            {
                $age = "10";
            }else if($class == "6")
            {
                $age = "11";
            }else if($class == "7")
            {
                $age = "12";
            }else if($class == "8")
            {
                $age = "13";
            }else if($class == "9")
            {
                $age = "14";
            }else if($class == "10")
            {
                $age = "15";
            }elseif ($class == "11") 
            {
                $age = "16";
            }elseif($class == "12")
            {
                $age = "17";
            }elseif($class == "Degree 1st")
            {
                $age = "18";
            }elseif($class == "Degree 2nd")
            {
                $age = "19";
            }elseif($class == "Degree 3rd")
            {
                $age = "20";
            }

            $normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
            $normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
            $normal_eyes_identifier      = $this->input->post('normal_eyes_identifier',TRUE);
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
            $emergency_identifier       = $this->input->post('emergency_identifier',TRUE);
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
                    'General' => is_array($normal_general_identifier) ? $normal_general_identifier : [],
                        'Head' => is_array($normal_head_identifier) ? $normal_head_identifier : [],
                        'Eyes' => is_array($normal_eyes_identifier) ? $normal_eyes_identifier : [],
                        'Ent' => is_array($normal_ent_identifier) ? $normal_ent_identifier : [],
                        'Respiratory_system' => is_array($normal_rs_identifier) ? $normal_rs_identifier : [],
                'Cardio_vascular_system' => is_array($normal_cvs_identifier) ? $normal_cvs_identifier : [],
                        'Gastro_intestinal' => is_array($normal_gi_identifier) ? $normal_gi_identifier : [],
                        'Genito_urinary' => is_array($normal_gu_identifier) ? $normal_gu_identifier : [],
                        'Gynaecology' => is_array($normal_gyn_identifier) ? $normal_gyn_identifier : [],
                        'Endo_crinology' => is_array($normal_cri_identifier) ? $normal_cri_identifier : [],
                'Musculo_skeletal_syatem' => is_array($normal_msk_identifier) ? $normal_msk_identifier : [],
                'Central_nervous_system' => is_array($normal_cns_identifier) ? $normal_cns_identifier : [],
            'Psychiartic' => is_array($normal_psychiartic_identifier) ? $normal_psychiartic_identifier : []
            );

            $emergency_identifiers = array(
                        'Disease' => is_array($emergency_identifier) ? $emergency_identifier : [],
                        'Bites' => is_array($emergency_bites_identifier) ? $emergency_bites_identifier : []
            );
            
            $chronic_identifiers = array(
                    'Eyes' => is_array($chronic_eyes_identifier) ? $chronic_eyes_identifier : [],
                    'Ent'  => is_array($chronic_ent_identifier) ? $chronic_ent_identifier : [],
                'Central_nervous_system' => is_array($chronic_cns_identifier) ? $chronic_cns_identifier : [],
                    'Respiratory_system' => is_array($chronic_rs_identifier) ? $chronic_rs_identifier : [],
                'Cardio_vascular_system' => is_array($chronic_cvs_identifier) ? $chronic_cvs_identifier : [],
                    'Gastro_intestinal' => is_array($chronic_gi_identifier) ? $chronic_gi_identifier : [],
                    'Blood'  => is_array($chronic_blood_identifier) ? $chronic_blood_identifier : [],
                    'Kidney' => is_array($chronic_kidney_identifier) ? $chronic_kidney_identifier : [],
                    'VandM'  => is_array($chronic_vandm_identifier) ? $chronic_vandm_identifier : [],
                    'Bones'  => is_array($chronic_bones_identifier) ? $chronic_bones_identifier : [],
                    'Skin'   => is_array($chronic_skin_identifier) ? $chronic_skin_identifier : [],
                    'Endo'   => is_array($chronic_endo_identifier) ? $chronic_endo_identifier : [],
                    'Others' => is_array($chronic_others_identifier) ? $chronic_others_identifier : []
                        
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
        $transfer_join_hospital_name = $this->input->post('transfer_join_hospital_name', true);
        $transfer_hospitalised_date = $this->input->post('transfer_hospitalised_date', true);
        $discharge_date = $this->input->post('discharge_date', true);


            //$doc_data = array();
            //$doc_data['page1']['Student Info'] = array();
          // Page 1
            $doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
            $doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']       = $student_name;
            $doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
            $doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
            $doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
            $doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
            $doc_data['widget_data']['page1']['Student Info']['Gender']   = $gender;
            $doc_data['widget_data']['page1']['Student Info']['Age']   = $age;
            //$doc_data['page1']['Problem Info']    = $data_to_store;
            $doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

            $doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = isset($doctor_summary)? $doctor_summary : "";
            $doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = isset($doctor_advice) ? $doctor_advice : "";
            $doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = isset($prescription) ?  $prescription : "";

            $doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
            $doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

            if($review_status == 'Hospitalized')
        {
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;
            $doc_data['widget_data']['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
        }
        if(isset($transfer_join_hospital_name) && !empty($transfer_join_hospital_name))
        {
            $doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Name'] = $transfer_join_hospital_name;
            $doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Join Date'] = $transfer_hospitalised_date;
        }
        if($review_status == 'Discharge')
        {
            $doc_data['widget_data']['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
        }

            if(isset($normal_identifiers) && !empty($normal_identifiers))
            {
            //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
            $doc_data['widget_data']['page1']['Problem Info']['Normal']  = $normal_identifiers;
            }
            if(isset($emergency_identifiers) && !empty($emergency_identifiers))
            {
            //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
                $identifier = $emergency_identifiers;
            $doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;
            }
            if(isset($chronic_identifiers) && !empty($chronic_identifiers))
            {
            //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
            $doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
            }

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
                 $_FILES['hs_req_attachments']['name']  = $files['hs_req_attachments']['name'][$i];
                 $_FILES['hs_req_attachments']['type']  = $files['hs_req_attachments']['type'][$i];
                 $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
                 $_FILES['hs_req_attachments']['error'] = $files['hs_req_attachments']['error'][$i];
                 $_FILES['hs_req_attachments']['size']  = $files['hs_req_attachments']['size'][$i];
            
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
                             
                                    redirect('tswreis_cc/hs_request');  
                                
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
                $doc_history = $this->panacea_cc_model->get_history($unique_id,$doc_id);

                    

                  if(isset($doc_history[0]['doc_data']['external_attachments']))
                  {
                           
                    $external_merged_data = array_merge($doc_history[0]['doc_data']['external_attachments'],$external_final);
                    $doc_data['external_attachments'] = array_replace_recursive($doc_history[0]['doc_data']['external_attachments'],$external_merged_data);
                  }
                  else
                 {
                    $doc_data['external_attachments'] = $external_final;
                 } 
              
             }

             // school data
             $school_data_array = explode("_",$unique_id);
             $schoolCode        = (int) $school_data_array[1];

             $school_data = $this->panacea_cc_model->get_school_information_for_school_code($schoolCode);

             $session_data = $this->session->userdata('customer');
            /* echo print_r($session_data,true);
             exit();*/

              $health_supervisor = $this->panacea_cc_model->get_health_supervisor_details($schoolCode);
                     $hs_name = $health_supervisor['hs_name'];
                     $hs_mob  = $health_supervisor['hs_mob'];
   

          //POST DATA
            $redirected_stage   = "Doctor";
            $current_stage      = "HS 2";
            //$reason             = implode(", ",$reason_array);
            //$notification_param = array("Unique ID" => $form_data['student_code'].", ".$student_name);
            $redirected_stage   = $redirected_stage;
            $current_stage      = $current_stage;
            $disapproving_user  = $username;
            $stage_name         = "HS 2";            
            
            $approval_data = array(
                "current_stage"     => $stage_name,
                "approval"              => "false",
                "disapproved_by"        => $disapproving_user,
                "submitted_by"          => $disapproving_user,
                "time"                  => date('Y-m-d H:i:s'),
                //"reason"              => $reason,
                "redirected_stage"      => $redirected_stage,
                "redirected_user"       => "multi_user_stage",
                "submitted_user_type"   => $submitted_user_type); 
            
            $approval_history = $this->panacea_cc_model->get_approval_history($doc_id);

            array_push($approval_history,$approval_data);

          $existing_update = $this->panacea_cc_model->update_request_submit_model($doc_data,$approval_history,$unique_id,$doc_id); 

          if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge")
      {
        //$insert_hospitalised = $this->maharashtra_doctor_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);

        $check_doc_id = $this->tswreis_schools_common_model->check_doc_id_of_request($doc_id);
        
        if($check_doc_id == 'No Doc Found'){
            $insert_hospitalised = $this->tswreis_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);
        }
      } 
      

          if ($existing_update ) // the information has therefore been successfully saved in the db
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
                        //$send_msg = $this->panacea_common_lib->send_message_to_doctors_update($request_type,$unique_id,$student_name,$total_diseaes);
                            
                    $fcm_notification = $this->panacea_common_lib->fcm_message_notification_update($request_type,$unique_id,$student_name);
                    $this->session->set_flashdata('success','Request Updated successfully !!');
                    redirect('panacea_ts_normal/fetch_normal_requests_docs');
                }
                else
                {
                    $this->session->set_flashdata('fail','Some thing went wrong! Try Again');
                    redirect('panacea_ts_normal/fetch_normal_requests_docs');
                }

          
        }

public function hs_new_request()
    {
        $logged_in_user = $this->session->userdata("customer");
        $email          = $logged_in_user['email'];
        $email_array    = explode(".",$email);
        $this->data['district_code']  = strtoupper($email_array[0]);
        $this->data['school_code']    = (int) $email_array[1];
        $this->_render_page('panacea_cc_normal/hs_new_initiate_request', $this->data);
    }

    //fetch with unique id
public function fetch_student_info()
    {
        $unique_id= $_POST['page1_StudentDetails_HospitalUniqueID'];
        
        $this->data['get_data'] = $this->panacea_cc_model->fetch_student_info_model( $unique_id);

        if($this->data['get_data'] && !empty($this->data['get_data']))
        {
            $this->output->set_output(json_encode($this->data));
        }
        else
        {
            $this->output->set_output('NO_DATA_AVAILABLE');
        }
    }

     //submit hs request
     
    public function initiate_new_hs_request()
    {
        $unique_id = $this->input->post('student_code',TRUE);
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

        $age = "";
        if($class == "5")
        {
            $age = "10";
        }else if($class == "6")
        {
            $age = "11";
        }else if($class == "7")
        {
            $age = "12";
        }else if($class == "8")
        {
            $age = "13";
        }else if($class == "9")
        {
            $age = "14";
        }else if($class == "10")
        {
            $age = "15";
        }elseif ($class == "11") 
        {
            $age = "16";
        }elseif($class == "12")
        {
            $age = "17";
        }elseif($class == "Degree 1st")
        {
            $age = "18";
        }elseif($class == "Degree 2nd")
        {
            $age = "19";
        }elseif($class == "Degree 3rd")
        {
            $age = "20";
        }

        $normal_general_identifier  = $this->input->post('normal_general_identifier',TRUE);
        $normal_head_identifier     = $this->input->post('normal_head_identifier',TRUE);
        $normal_eyes_identifier      = $this->input->post('normal_eyes_identifier',TRUE);
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
        $emergency_identifier       = $this->input->post('emergency_identifier',TRUE);
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
                'General' => is_array($normal_general_identifier) ? $normal_general_identifier : [],
                    'Head' => is_array($normal_head_identifier) ? $normal_head_identifier : [],
                    'Eyes' => is_array($normal_eyes_identifier) ? $normal_eyes_identifier : [],
                    'Ent' => is_array($normal_ent_identifier) ? $normal_ent_identifier : [],
                    'Respiratory_system' => is_array($normal_rs_identifier) ? $normal_rs_identifier : [],
            'Cardio_vascular_system' => is_array($normal_cvs_identifier) ? $normal_cvs_identifier : [],
                    'Gastro_intestinal' => is_array($normal_gi_identifier) ? $normal_gi_identifier : [],
                    'Genito_urinary' => is_array($normal_gu_identifier) ? $normal_gu_identifier : [],
                    'Gynaecology' => is_array($normal_gyn_identifier) ? $normal_gyn_identifier : [],
                    'Endo_crinology' => is_array($normal_cri_identifier) ? $normal_cri_identifier : [],
            'Musculo_skeletal_syatem' => is_array($normal_msk_identifier) ? $normal_msk_identifier : [],
            'Central_nervous_system' => is_array($normal_cns_identifier) ? $normal_cns_identifier : [],
        'Psychiartic' => is_array($normal_psychiartic_identifier) ? $normal_psychiartic_identifier : []
        );

        $emergency_identifiers = array(
                    'Disease' => is_array($emergency_identifier) ? $emergency_identifier : [],
                    'Bites' => is_array($emergency_bites_identifier) ? $emergency_bites_identifier : []
        );
        
        $chronic_identifiers = array(
                'Eyes' => is_array($chronic_eyes_identifier) ? $chronic_eyes_identifier : [],
                'Ent'  => is_array($chronic_ent_identifier) ? $chronic_ent_identifier : [],
            'Central_nervous_system' => is_array($chronic_cns_identifier) ? $chronic_cns_identifier : [],
                'Respiratory_system' => is_array($chronic_rs_identifier) ? $chronic_rs_identifier : [],
            'Cardio_vascular_system' => is_array($chronic_cvs_identifier) ? $chronic_cvs_identifier : [],
                'Gastro_intestinal' => is_array($chronic_gi_identifier) ? $chronic_gi_identifier : [],
                'Blood'  => is_array($chronic_blood_identifier) ? $chronic_blood_identifier : [],
                'Kidney' => is_array($chronic_kidney_identifier) ? $chronic_kidney_identifier : [],
                'VandM'  => is_array($chronic_vandm_identifier) ? $chronic_vandm_identifier : [],
                'Bones'  => is_array($chronic_bones_identifier) ? $chronic_bones_identifier : [],
                'Skin'   => is_array($chronic_skin_identifier) ? $chronic_skin_identifier : [],
                'Endo'   => is_array($chronic_endo_identifier) ? $chronic_endo_identifier : [],
                'Others' => is_array($chronic_others_identifier) ? $chronic_others_identifier : []
                    
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
        $transfer_join_hospital_name = $this->input->post('transfer_join_hospital_name', true);
        $transfer_hospitalised_date = $this->input->post('transfer_hospitalised_date', true);
        $discharge_date = $this->input->post('discharge_date', true);

        //$doc_data = array();
        //$doc_data['page1']['Student Info'] = array();
      // Page 1
        $doc_data['widget_data']['page1']['Student Info']['Unique ID']    = $unique_id;
        $doc_data['widget_data']['page1']['Student Info']['Name']['field_ref']       = $student_name;
        $doc_data['widget_data']['page1']['Student Info']['District']['field_ref']    = $district;
        $doc_data['widget_data']['page1']['Student Info']['School Name']['field_ref']    =$school_name;
        $doc_data['widget_data']['page1']['Student Info']['Class']['field_ref']    = $class;
        $doc_data['widget_data']['page1']['Student Info']['Section']['field_ref']    = $section;
        $doc_data['widget_data']['page1']['Student Info']['Gender']    = $gender;
        $doc_data['widget_data']['page1']['Student Info']['Age']    = $age;
        //$doc_data['page1']['Problem Info']    = $data_to_store;
        $doc_data['widget_data']['page2']['Problem Info']['Description']    = $problem_info_description;

        $doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Summary']  = $doctor_summary;
        $doc_data['widget_data']['page2']['Diagnosis Info']['Doctor Advice']  = $doctor_advice;
        $doc_data['widget_data']['page2']['Diagnosis Info']['Prescription']  = $prescription;

        $doc_data['widget_data']['page2']['Review Info']['Request Type']    = $request_type;
        $doc_data['widget_data']['page2']['Review Info']['Status']    = $review_status;

        if($review_status == 'Hospitalized')
        {
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Name'] = $std_join_hospital_name;
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Type'] = $std_join_hospital_type;
            $doc_data['widget_data']['page2']['Hospital Info']['District Name'] = $std_join_hospital_dist;
            $doc_data['widget_data']['page2']['Hospital Info']['Hospital Join Date'] = $hospitalised_date;
        }
        if(isset($transfer_join_hospital_name) && !empty($transfer_join_hospital_name))
        {
            $doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Name'] = $transfer_join_hospital_name;
            $doc_data['widget_data']['page2']['Transferred Hospital Info']['Transfer Hospital Join Date'] = $transfer_hospitalised_date;
        }
        if($review_status == 'Discharge')
        {
            $doc_data['widget_data']['page2']['Hospital Discharge Info']['Discharge Date'] = $discharge_date;
        }

        if(isset($normal_identifiers) && !empty($normal_identifiers))
        {
        //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
        $doc_data['widget_data']['page1']['Problem Info']['Normal']  = $normal_identifiers;
        }
        if(isset($emergency_identifiers) && !empty($emergency_identifiers))
        {
        //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
        $doc_data['widget_data']['page1']['Problem Info']['Emergency']  = $emergency_identifiers;
        }
        if(isset($chronic_identifiers) && !empty($chronic_identifiers))
        {
        //$doc_data['widget_data']['page1']['Problem Info']['Normal_type']  = "Normal";
        $doc_data['widget_data']['page1']['Problem Info']['Chronic']  = $chronic_identifiers;
        }

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
             $_FILES['hs_req_attachments']['name']  = $files['hs_req_attachments']['name'][$i];
             $_FILES['hs_req_attachments']['type']  = $files['hs_req_attachments']['type'][$i];
             $_FILES['hs_req_attachments']['tmp_name']= $files['hs_req_attachments']['tmp_name'][$i];
             $_FILES['hs_req_attachments']['error'] = $files['hs_req_attachments']['error'][$i];
             $_FILES['hs_req_attachments']['size']  = $files['hs_req_attachments']['size'][$i];
        
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
                         
                                redirect('tswreis_schools/hs_new_request');  
                            
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
         $session_data = $this->session->userdata('customer');
         $username = $session_data['email'];
        

          $doc_data['stage_name'] = 'healthcare2016531124515424';

          $doc_data['first_stage_name'] = "HS 1";

          $doc_data['user_name']  = $username;
          
          $doc_data['chart_data']  = '';

         // school data
         $school_data_array = explode("_",$unique_id);
         $schoolCode        = (int) $school_data_array[1];
         
         $school_data = $this->tswreis_schools_common_model->get_school_information_for_school_code($schoolCode);
        $session_data = $this->session->userdata('customer');
        $health_supervisor = $this->tswreis_schools_common_model->get_health_supervisor_details($schoolCode);
        $hs_name = $health_supervisor['hs_name'];
        $hs_mob  = $health_supervisor['hs_mob'];

         $school_contact_details = array(
            'health_supervisor' => array('name'=>$hs_name,'mobile'=>$hs_mob),
            'principal'         => array('name'=>$school_data['contact_person_name'],'mobile'=>$school_data['school_mob'])
         );

         $doc_data['school_contact_details']  = $school_contact_details;
//      echo print_r($doc_data,true);
        //exit();
        $doc_properties['doc_id'] = get_unique_id();
        $doc_properties['status'] = 1;
        $doc_properties['_version'] = 1;
        $doc_properties['doc_owner'] = "PANACEA";
        $doc_properties['unique_id'] = '';
        $doc_properties['doc_flow'] = "new";

        
        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id' => "healthcare2016531124515424",
                        'status' => "new"
                    );
        $array_history = array();
        $session_data = $this->session->userdata("customer");
        /*echo print_r($session_data,true);
        exit();*/
        $schoolName = $school_data['school_name'];
        
        $email = $session_data['email'];
        $array_data = array(
            'current_stage' => "HS 1",
            'approval' => "true",
            'submitted_by' => $email,
            'time' => date('Y-m-d H:i:s')
            );
        array_push($array_history, $array_data);
        
        //$array_history['history'] = $array_history;
       // History
    /* $history = array();
     $history_entry = array('time'=>date('Y-m-d H:i:s'),'submitted_by'=>'','approval'=>"true","stage_name"=>"");
      array_push($history);
        */
     // $request_type = $form_data['doc_data']['widget_data']['page2']['Review Info']['Request Type'];
     
          if($request_type === "Chronic" || $request_type === "Deficiency" || $request_type === "Defects")
         {
           $chronic_disease   = $chronic_identifiers;
           $disease_desc      = $problem_info_description;
          // log_message('error','chronic_disease=========2662'.print_r($chronic_disease,TRUE));exit();
           $this->tswreis_schools_common_model->create_chronic_case_new($unique_id,$request_type,$chronic_disease,$disease_desc,$schoolName);
         }

         if($review_status == 'Hospitalized' || $review_status == "Surgery-Needed" || $review_status == "Expired" || $review_status == "Discharge" )
      {         
      
        $insert_hospitalised = $this->tswreis_schools_common_model->insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties);          

      }

      $initate_submit = $this->tswreis_schools_common_model->initiate_request_model($doc_data,$doc_properties,$app_properties,$array_history); 


      if ($initate_submit ) // the information has therefore been successfully saved in the db
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
                //$send_msg = $this->panacea_common_lib->send_message_to_doctors($request_type,$unique_id,$student_name,$total_diseaes);

                $fcm_notification = $this->panacea_common_lib->fcm_message_notification($request_type,$unique_id,$student_name);
                
                $this->session->set_flashdata('success','Request Raised successfully !!');
                redirect('panacea_ts_normal/hs_new_request');
            }
            else
            {
                $this->session->set_flashdata('fail','Some thing went wrong! Try Again');
                redirect('panacea_ts_normal/hs_new_request');
            }


    }

    public function get_searched_student_sick_requests()
    {
        $search_data = $this->input->post('search_value', true);

        $logged_in_user = $this->session->userdata("customer");
        $email          = $logged_in_user['email'];
        $email_array    = explode(".",$email);
        $school_code    = (int) $email_array[1];
        
        $this->data = $this->panacea_cc_model->get_searched_student_sick_requests_model($search_data);

        $this->output->set_output(json_encode($this->data));
    }
    
}
