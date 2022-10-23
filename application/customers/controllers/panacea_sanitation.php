<?php use Zend\Mail\Message;
defined('BASEPATH') OR exit('No direct script access allowed');

class Panacea_sanitation extends My_Controller {

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
		$this->load->model('panacea_common_model');
		$this->load->library('panacea_common_lib');
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
		redirect('panacea_sanitation/to_dashboard');
	}

	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: To Dashboard
	 *
	 *@author Selva 
	 */

	function to_dashboard()
	{
		$this->check_for_admin();
		$this->check_for_plan('sanitation_report');

		$pagenumber       = array();
	    $page_data        = array();
		$sanitation_report_app = array();
		$today_date       = date("Y-m-d");
		
		$app_template = $this->panacea_common_model->get_sanitation_report_app();
		
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

		$this->data['sanitation_report_schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($today_date);
		
		$this->data['today_date'] = $today_date;
		$this->data['distslist'] = $this->panacea_common_model->get_all_district();
		
		$this->_render_page('panacea_sanitation/admin_dash', $this->data);
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Get data to draw sanitation report pie based on the selected criteria
	 *
	 *@author Selva 
	 */
	
	
	 
	function draw_sanitation_report_pie()
	{
	  // POST Data
	  $date 			= $this->input->post('date',TRUE);
	  $search_criteria  = $this->input->post('que',TRUE);
	  $opt              = $this->input->post('opt',TRUE);
	  
	  $search_criteria  = str_replace('#','.',$search_criteria);
	  $search_criteria  = str_replace('_',' ',$search_criteria);
	 
	  $search_criteria  = "doc_data.widget_data.".$search_criteria;
	  
	  $sanitation_report_pie = $this->panacea_common_model->get_sanitation_report_pie_data($date,$search_criteria,$opt);
	  
	  if($sanitation_report_pie)
	  {
	    $this->output->set_output(json_encode($sanitation_report_pie));
	  }
	  else
	  {
        $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}

	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the sanitation report sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_sanitation_report_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->panacea_common_lib->generate_excel_for_sanitation_report_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Download the sanitation report not sent schools list ( as Excel sheet )
	 *
	 *@author Selva 
	 */
	 
	function download_sanitation_report_not_sent_schools_list()
	{
	   // POST Data
	   $schools_list = $this->input->post('data',TRUE);
	   $date         = $this->input->post('today_date',TRUE);
	   
	   // Excel generation
	   $file_path = $this->panacea_common_lib->generate_excel_for_sanitation_report_not_sent_schools($date,$schools_list);
	   $this->output->set_output($file_path);
	}
	
	public function get_schools_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('get_schools_list');
		
		$dist_id = $_POST['dist_id'];
		
		$this->data = $this->panacea_common_model->get_schools_by_dist_id($dist_id);
		//$this->data = "";
		$this->output->set_output(json_encode($this->data));
	}
	
	function fetch_sanitation_report_against_date()
	{
	  // Variables
	  $sanitation_report = array();

	  // POST Data
	  $date   = $this->input->post('selected_date',TRUE);

	  if(isset($_POST['selected_school']) && !empty($_POST['selected_school']))
	  {
	  	 $school = $this->input->post('selected_school',TRUE);
	  
	     $sanitation_report_data = $this->panacea_common_model->get_sanitation_report_data_with_date($date,$school);
		
	     $sanitation_report['report_data']  = json_encode($this->panacea_common_lib->build_sanitation_report($sanitation_report_data));

	     $sanitation_report['schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($date);
	  }
	  else
	  {
	  	 $sanitation_report['schools_list'] = $this->panacea_common_model->get_sanitation_report_pie_schools_data($date);
	  }
		
	  if(isset($sanitation_report) && !empty($sanitation_report))
	  {
	      $this->output->set_output(json_encode($sanitation_report));
	  }
	  else
	  {
		  $this->output->set_output('NO_DATA_AVAILABLE');
	  }
	}

    public function hb_overall_dashboard(){
        $this->data = "";

        $this->_render_page("panacea_sanitation/hb_overall_dashboard.php". $this->data);
    }

    public function bmi_overall_dashboard(){
        $this->data = "";

        $this->_render_page("panacea_sanitation/bmi_overall_dashboard.php". $this->data);
    }

        public function get_screening_hb_data_by_gender()
    {
          $hb_boys = $this->panacea_common_model->get_screening_hb_data_for_boys();
          $this->data['hb_boys'] = json_encode($hb_boys);
          //echo print_r($this->data['hb_boys'], true); exit();

          $hb_girls = $this->panacea_common_model->get_screening_hb_data_for_girls();
          $this->data['hb_girls'] = json_encode($hb_girls);

      if(!empty($this->data))
      {
        $this->output->set_output(json_encode($this->data));
      }
    }

    public function get_screening_bmi_data_by_gender()
    {
          $bmi_boys = $this->panacea_common_model->get_screening_bmi_data_for_boys();
          $this->data['bmi_boys'] = json_encode($bmi_boys);
          //echo print_r($this->data['hb_boys'], true); exit();

          $bmi_girls = $this->panacea_common_model->get_screening_bmi_data_for_girls();
          $this->data['bmi_girls'] = json_encode($bmi_girls);

      if(!empty($this->data))
      {
        $this->output->set_output(json_encode($this->data));
      }
    }

    public function get_hb_students_girls_from_bar()
        {
        $symptom_type = $this->input->post('hb_symptom', true);
        $gender = $this->input->post('gender_hb',  true);

        if($gender == 'Male'){
        $gender_type = 'Male';
        }else{
             $gender_type = 'Female';
        }

        $this->data['scl_list'] = $this->panacea_common_model->get_hb_submitted_schools_data_with_symptom($symptom_type, $gender_type);

      /*  echo '<pre>';
        echo print_r( $this->data['scl_list'], true);
         echo '</pre>';
        exit();*/
       
        $this->_render_page("panacea_sanitation/hb_students_schools_list.php", $this->data);
  }

  public function get_bmi_students_girls_from_bar()
        {
        $symptom_type = $this->input->post('bmi_symptom', true);
        $gender = $this->input->post('gender_bmi',  true);

        if($gender == 'Male'){
        $gender_type = 'Male';
        }else{
             $gender_type = 'Female';
        }

        $this->data['scl_list'] = $this->panacea_common_model->get_bmi_submitted_schools_data_with_symptom($symptom_type, $gender_type);

      /*  echo '<pre>';
        echo print_r( $this->data['scl_list'], true);
         echo '</pre>';
        exit();*/
       
        $this->_render_page("panacea_sanitation/bmi_students_schools_list.php", $this->data);
  }

  public function get_students_hb_list_scl_wise()
  {
  	$school_name = $this->input->post('school_name', true);
  	$symptom_type = $this->input->post('symptom_name', true);
  	$gender_type = $this->input->post('gender_type', true);

  	$this->data['student_list'] = $this->panacea_common_model->get_students_hb_list_scl_wise($school_name, $symptom_type, $gender_type);

  	$this->_render_page("panacea_sanitation/students_hb_list_school_wise.php", $this->data);
  }

  public function get_students_bmi_list_scl_wise()
  {
    $school_name = $this->input->post('school_name', true);
    $symptom_type = $this->input->post('symptom_name', true);
    $gender_type = $this->input->post('gender_type', true);

    $this->data['student_list'] = $this->panacea_common_model->get_students_bmi_list_scl_wise($school_name, $symptom_type, $gender_type);

    $this->_render_page("panacea_sanitation/students_bmi_list_school_wise.php", $this->data);
  }

public function doctor_personal_form(){
        $this->data = "";
       
        $this->_render_page("panacea_sanitation/doctor_personal_form", $this->data);
    }
  
		
}
