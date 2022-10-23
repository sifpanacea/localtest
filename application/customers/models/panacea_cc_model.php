<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panacea_cc_model extends CI_Model
{
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
	
	 function __construct()
    {
        parent::__construct();
        
        $this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
        
        // Initialize MongoDB collection names
        $this->collections = $this->config->item('collections', 'ion_auth');
        $this->_configvalue = $this->config->item('default');
        $this->common_db   = $this->config->item('default');
        
        $this->store_salt      = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length     = $this->config->item('salt_length', 'ion_auth');
        
        // Initialize hash method directives (Bcrypt)
        $this->hash_method    = $this->config->item('hash_method', 'ion_auth');
        
        //$this->common_db = $this->config->item('default');
        
        $this->screening_app_col = "healthcare2016226112942701";
        $this->absent_app_col = "healthcare201651317373988";
        $this->request_app_col = "healthcare2016512203543321";
        $this->request_app_col_static_html = "healthcare2016531124515424_static_html";

        $this->screening_app_col_sw_2021_2022 = "tswreis_screening_report_col_2021-2022";
    }
	
	public function get_all_district()
    {
     $query = $this->mongo_db->get('panacea_district');
	 
	 return $query;
    }
	
	
	public function schoolscount()
    {
     $count = $this->mongo_db->count('panacea_schools');
	 return $count;
    }
	
	public function get_schools($per_page,$page)
    {
     $offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_schools');
    foreach($query as $schools => $school){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($school['dt_name']))->get('panacea_district');
    		if(isset($school['dt_name'])){
    			$query[$schools]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$schools]['dt_name'] = "No state selected";
    		}
    	}
	 return $query;
    }
    
    public function get_all_schools()
    {
    	$query = $this->mongo_db->get('panacea_schools');
    	foreach($query as $schools => $school){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($school['dt_name']))->get('panacea_district');
    		if(isset($school['dt_name'])){
    			$query[$schools]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$schools]['dt_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
	
	public function create_school($post)
    {
		$data = array(
		"dt_name" => $post['dt_name'],
		"school_code" => $post['school_code'],
		"school_name" => $post['school_name'],
		"school_addr" => $post['school_addr'],
		"school_email" => $post['school_email'],
		"school_ph" => $post['school_ph'],
		"school_mob" => $post['school_mob'],
		"contact_person_name" => $post['contact_person_name']);
     $query = $this->mongo_db->insert('panacea_schools',$data);
	 return $query;
    }
	
	public function delete_school($school_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($school_id)))->delete('panacea_schools');
	 return $query;
    }
	
	public function classescount()
    {
     $count = $this->mongo_db->count('panacea_classes');
	 return $count;
    }
	
	public function get_classes($per_page,$page)
    {
     $offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_classes');
	 return $query;
    }
	
	public function create_class($post)
    {
		$data = array(
		"class_name" => $post['class_name']);
     $query = $this->mongo_db->insert('panacea_classes',$data);
	 return $query;
    }
	
	public function delete_class($class_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($class_id)))->delete('panacea_classes');
	 return $query;
    }
	
	public function sectionscount()
    {
     $count = $this->mongo_db->count('panacea_sections');
	 return $count;
    }
	
	public function get_sections($per_page,$page)
    {
     $offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_sections');
	 return $query;
    }
	
	public function create_section($post)
    {
		$data = array(
		"section_name" => $post['section_name']);
     $query = $this->mongo_db->insert('panacea_sections',$data);
	 return $query;
    }
	
	public function delete_section($section_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($section_id)))->delete('panacea_sections');
	 return $query;
    }
	
	public function symptomscount()
    {
     $count = $this->mongo_db->count('panacea_symptoms');
	 return $count;
    }
	
	public function get_symptoms($per_page,$page)
    {
     $offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_symptoms');
	 return $query;
    }
	
	public function create_symptoms($post)
    {
		$data = array(
		"symptom_name" => $post['symptom_name']);
     $query = $this->mongo_db->insert('panacea_symptoms',$data);
	 return $query;
    }
	
	public function delete_symptoms($symptoms_id)
    {
     $query = $this->mongo_db->where(array("_id"=>new MongoId($symptoms_id)))->delete('panacea_symptoms');
	 return $query;
    }
	
	public function get_reports_ehr($ad_no)
    {
     $query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->whereLike("doc_data.widget_data.page2.Personal Information.AD No", $ad_no)->get($this->screening_app_col);
	 if($query){
	 	$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $query[0]["doc_data"]['widget_data']['page1']['Personal Information']['Hospital Unique ID'])->get($this->request_app_col);
	 	 $result['screening'] = $query;
	 	 $result['request'] = $query_request;
		 return $result;
	 }else{
		 $result['screening'] = false;
    	$result['request'] = false;
    	return $result;
	 }
    }
    
    public function get_reports_ehr_uid($uid)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->whereLike(
    			"doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid)->get($this->screening_app_col);
    	if($query){
    		$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $uid)->get($this->request_app_col);
    		$result['screening'] = $query;
    		$result['request']	 = $query_request;
    		return $result;
    	}else{
    		$result['screening'] = false;
    		$result['request'] = false;
    		return $result;
    	}
    }
    
    public function get_students_uid($uid)
    {
    	$query = $this->mongo_db->where("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $uid)->get($this->screening_app_col."_test");
    	log_message('debug','cccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc.'.print_r($this->screening_app_col."test",true));
    	log_message('debug','iuiuiuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu.'.print_r($uid,true));
    	//log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq.'.print_r($query,true));
    	if($query){
    		
    		return $query[0];
    	}else{
    		return false;
    	}
    }
    
    public function update_student_data($doc,$doc_id)
    {
    	$query = $this->mongo_db->where("_id", $doc_id)->set($doc)->update($this->screening_app_col."_test");
    	
    	return $query;
    }
    
    public function studentscount()
    {
    	$count = $this->mongo_db->count($this->screening_app_col);
    	return $count;
    }
    
    public function get_students($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	return $query;
    }
    
    public function get_all_students()
    {
    	ini_set('memory_limit', '512M');
    	$query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->screening_app_col);
    	return $query;
    }
    
    public function doctorscount()
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$count = $this->mongo_db->count($this->collections['panacea_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $count;
    }
    
    public function get_doctors($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->limit($per_page)->offset($offset)->get($this->collections['panacea_doctors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    }
    
    public function hospitalscount()
    {
    	$count = $this->mongo_db->count('panacea_hospitals');
    	return $count;
    }
    
    public function get_hospitals($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_hospitals');
    	foreach($query as $hospitals => $hospital){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($hospital['dt_name']))->get('panacea_district');
    		if(isset($hospital['dt_name'])){
    			$query[$hospitals]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$hospitals]['dt_name'] = "No state selected";
    		}
    	}
    	
    	return $query;
    }
    
    public function diagnosticscount()
    {
    	$count = $this->mongo_db->count('panacea_diagnostics');
    	return $count;
    }
    
    public function get_diagnostics($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_diagnostics');
    	foreach($query as $diagnostics => $dia){
    		$dt_name = $this->mongo_db->where('_id', new MongoId($dia['dt_name']))->get('panacea_district');
    		if(isset($dia['dt_name'])){
    			$query[$diagnostics]['dt_name'] = $dt_name[0]['dt_name'];
    		}else{
    			$query[$diagnostics]['dt_name'] = "No state selected";
    		}
    	}
    	return $query;
    }
    
    public function create_diagnostic($post)
    {
    	$data = array(
    			"dt_name" => $post['dt_name'],
    			"diagnostic_code" => $post['diagnostic_code'],
    			"diagnostic_name" => $post['diagnostic_name'],
    			"diagnostic_ph" => $post['diagnostic_ph'],
    			"diagnostic_mob" => $post['diagnostic_mob'],
    			"diagnostic_addr" => $post['diagnostic_addr'],);
    	$query = $this->mongo_db->insert('panacea_diagnostics',$data);
    	return $query;
    }
    
    public function delete_diagnostic($diagnostic_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($diagnostic_id)))->delete('panacea_diagnostics');
    	return $query;
    }
    
    public function create_hospital($post)
    {
    	$data = array(
    			"dt_name" => $post['dt_name'],
    			"hospital_code" => $post['hospital_code'],
    			"hospital_name" => $post['hospital_name'],
    			"hospital_ph" => $post['hospital_ph'],
    			"hospital_mob" => $post['hospital_mob'],
    			"hospital_addr" => $post['hospital_addr'],);
    	$query = $this->mongo_db->insert('panacea_hospitals',$data);
    	return $query;
    }
    
    public function delete_hospital($hospital_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($hospital_id)))->delete('panacea_hospitals');
    	return $query;
    }
    
    public function empcount()
    {
    	$count = $this->mongo_db->count('panacea_emp');
    	return $count;
    }
    
    public function get_emp($per_page,$page)
    {
    	$offset = $per_page * ( $page - 1) ;
		$query = $this->mongo_db->limit($per_page)->offset($offset)->get('panacea_emp');
    	return $query;
    }
    
    public function create_emp($post)
    {
    	$data = array(
    			"emp_code" => $post['emp_code'],
    			"emp_name" => $post['emp_name'],
    			"emp_email" => $post['emp_email'],
    			"emp_mob" => $post['emp_mob'],
    			"emp_addr" => $post['emp_addr'],
    			"emp_qualification" => $post['emp_qualification'],);
    	$query = $this->mongo_db->insert('panacea_emp',$data);
    	return $query;
    }
    
    public function delete_emp($emp_id)
    {
    	$query = $this->mongo_db->where(array("_id"=>new MongoId($emp_id)))->delete('panacea_emp');
    	return $query;
    }
    
    public function insert_student_data($doc_data, $history, $doc_properties)
    {    
    	$query = $this->mongo_db->getWhere("naresh", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=> $doc_data['widget_data']['page2']['Personal Information']['School Name']));
    
    	//$query = $this->mongo_db->getWhere("form_data_sample_copy_1", array('doc_data.widget_data.page2.Physical Info.ID number' => $doc_data['widget_data']['page2']['Physical Info']['ID number'],'doc_data.widget_data.page2.Physical Info.School'=>'TSWRS/JC(G)-JADCHERLA'));
    	
    	$result = json_decode(json_encode($query), FALSE);
    	if (!$result)
    	{
    		$form_data = array();
    		$form_data['doc_data']       = $doc_data;
    		$form_data['doc_properties'] = $doc_properties;
    		$form_data['history']        = $history;
    
    		$this->mongo_db->insert("naresh",$form_data);
    		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);
    	}
    	else
    	{
    		$form_data = array();
    		$form_data['doc_data'] = $doc_data;
    		$form_data['doc_data']['widget_data']['page2']['Personal Information']['AD No'] = $doc_data['widget_data']['page2']['Personal Information']['AD No'].'A';
    		$form_data['doc_properties'] = $doc_properties;
    		$form_data['history'] = $history;
    		$this->mongo_db->insert("naresh",$form_data);
    		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);
    
    	}
    }
	
    public function get_all_symptoms()
    {
    	
    	$today = date('Y-m-d');
    	log_message("debug","ttttttttttttttttttttttttttttttttttttttttttttttt".print_r($today,true));
    	$query = $this->mongo_db->select(array("doc_data.widget_data"))->get($this->request_app_col);

    	$prob_arr = [];
    		foreach ($query as $doc){
    			if(isset($doc['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])){
    				$problems = $doc['doc_data']['widget_data']['page1']['Problem Info']['Identifier'];
    				foreach ($problems as $problem){
    					if(isset($prob_arr[$problem])){
    						$prob_arr[$problem]++;
    					}else{
    						$prob_arr[$problem] = 1;
    					}
    				}
    			}
    		}
    	
    		log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob_arr,true));
    		$final_values = [];
    		foreach ($prob_arr as $prob => $count){
    			log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($prob,true));
    			log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    			$result['label'] = $prob;
    			$result['value'] = $count;
    			array_push($final_values,$result);
    		}
    	
    		log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    	
    		return $final_values;
    }
    
    public function get_all_absent_data()
    {
    	$today = date('Y-m-d');
    	log_message("debug","ttttttttttttttttttttttttttttttttttttttttttttttt".print_r($today,true));
    	$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    	
    	$absent = 0;
    	$sick = 0;
    	$restRoom = 0;
    	$r2h = 0;
    	//$attended = 0;
    	foreach ($query as $report){
    		$absent = $absent + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Absent']);
    		$sick = $sick + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Sick']);
    		$restRoom = $restRoom + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']);
    		$r2h = $r2h + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['R2H']);
    		//$attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
    	}
    	
    	$requests = [];
    	
//     	$request['label'] = 'ATTENDED';
//     	$request['value'] = $attended;
//     	array_push($requests,$request);
    	
    	$request['label'] = 'ABSENT REPORT';
		$request['value'] = $absent;
		array_push($requests,$request);
    	
    	$request['label'] = 'SICK CUM ATTENDED';
    	$request['value'] = $sick;
    	array_push($requests,$request);
    	
    	$request['label'] = 'REST ROOM IN MEDICATION';
    	$request['value'] = $restRoom;
    	array_push($requests,$request);
    	
    	$request['label'] = 'REFER TO HOSPITAL';
    	$request['value'] = $r2h;
    	array_push($requests,$request);
    	
    	return $requests;
    }
    
    public function drilldown_absent_to_districts($data)
    {
    	$today = date('Y-m-d');
    	
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    	switch ($type) {
    		case "ABSENT REPORT":
    			
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			
    		case "SICK CUM ATTENDED":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			
    		case "REST ROOM IN MEDICATION":
    				 
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			
    		case "REFER TO HOSPITAL":
    					
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			return $this->get_drilling_attendance_districts_prepare_pie_array($query);
    			break;
    			

    		default:
    			;
    			break;
    	}
    }
    
    public function get_drilling_absent_schools($data)
    {
    	$today = date('Y-m-d');
    	
    	$obj_data = json_decode($data,true);
    	log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	switch ($type) {
    		case "ABSENT REPORT":
    
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    		  
    			break;
    		case "SICK CUM ATTENDED":
    			
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    			
    			break;
    		
    		case "REST ROOM IN MEDICATION":
    			
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    		
    			break;
    			
    		case "REFER TO HOSPITAL":
    				
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_schools_prepare_pie_array($query,$dist);
    				
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_absent_students($data)
    {
    	$today = date('Y-m-d');
    	
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = strtolower ($obj_data['1']);
    	switch ($type) {
    		case "ABSENT REPORT":
    
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    		  
    			break;
    		case "SICK CUM ATTENDED":
    			
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    			
    			break;
    		
    		case "REST ROOM IN MEDICATION":
    			
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    		
    			break;
    			
    		case "REFER TO HOSPITAL":
    				
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereLike('history.last_stage.time',$today)->get($this->absent_app_col);
    			//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    			return $this->get_drilling_absent_students_prepare_pie_array($query,$school_name,$type);
    				
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_absent_students_docs($_id_array)
    {
    	
    	$docs = [];
    	 
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereLike("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id)->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	
    	
    	//log_message("debug","abbbbbbbbbbbbbbbbbbbbbbbbbb____________arrrrrrrrrrrrrrrrrrrrrrrrr".print_r($_id_array,true));
    	//$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->whereIn("doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $_id_array)->get($this->screening_app_col);
    	//log_message("debug","qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq".print_r($query,true));
    	return $docs;
    	 
    }
    
    public function get_drilling_attendance_districts_prepare_pie_array($query)
    {
    	$requests = [];
    	 
    	$request['label'] = 'Adilabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'adilabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Hyderabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'hyderabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'KarimNagar';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'karimnagar'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Khammam';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'khammam'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Mahabubnagar';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'mahabubnagar'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Medak';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'medak'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Nalgonda';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'nalgonda'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Nizamabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'nizamabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Ranga Reddy';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'ranga reddy'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	$request['label'] = 'Warangal';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page1']['Attendence Details']['District']) == 'warangal'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	 
    	return $requests;
    }
    
    public function get_drilling_absent_schools_prepare_pie_array($query,$dist)
    {
    	log_message("debug","2222222222222222222222222222222222222222222222222".print_r($query,true));
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
    			if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['District'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page1']['Attendence Details']['District']) == $dist){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
    		$request = [];
    		foreach ($search_result as $doc){
    			if(isset($request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']])){
    				$request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']]++;
    			}else{
    				$request[$doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']] = 1;
    			}
    		}
    		
//     		$absent = 0;
//     		$sick = 0;
//     		$restRoom = 0;
//     		$r2h = 0;
//     		//$attended = 0;
//     		foreach ($query as $report){
//     			$absent = $absent + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['Absent']);
//     			$sick = $sick + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Sick']);
//     			$restRoom = $restRoom + intval($report['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']);
//     			$r2h = $r2h + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['R2H']);
//     			//$attended = $attended + intval($report['doc_data']['widget_data']['page1']['Attendence Details']['Attended']);
//     		}
    		 
//     		$requests = [];
    		 
//     		//     	$request['label'] = 'ATTENDED';
//     		//     	$request['value'] = $attended;
//     		//     	array_push($requests,$request);
    		 
//     		$request['label'] = 'ABSENT REPORT';
//     		$request['value'] = $absent;
//     		array_push($requests,$request);
    		 
//     		$request['label'] = 'SICK CUM ATTENDED';
//     		$request['value'] = $sick;
//     		array_push($requests,$request);
    		 
//     		$request['label'] = 'REST ROOM IN MEDICATION';
//     		$request['value'] = $restRoom;
//     		array_push($requests,$request);
    		 
//     		$request['label'] = 'REFER TO HOSPITAL';
//     		$request['value'] = $r2h;
//     		array_push($requests,$request);
    		 
    		log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($request,true));
    		$final_values = [];
    		foreach ($request as $school => $count){
    			log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($school,true));
    			log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    			$result['label'] = $school;
    			$result['value'] = $count;
    			array_push($final_values,$result);
    		}
    		 
    		log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    		 
    		return $final_values;
    	}
    }
    
    public function get_drilling_absent_students_prepare_pie_array($query,$school_name,$type)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			//log_message("debug","dddddddddddddddddddddddddddddddddddddddddddddddddd".print_r($doc,true));
    			if(isset($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page1']['Attendence Details']['Select School']) == $school_name){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		//log_message("debug","sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($search_result,true));
    		$request = [];
    		$UI_arr = [];
    		foreach ($search_result as $doc){
    			switch ($type){
    				case "ABSENT REPORT":
    					$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID']);
    					log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
    					log_message("debug","mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm".print_r($UI_arr,true));
		    		  
		    			break;
		    		case "SICK CUM ATTENDED":
		    			
		    			$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID']);
    					log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
		    			
		    			break;
		    		
		    		case "REST ROOM IN MEDICATION":
		    			
		    			$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID']);
    					log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
		    		
		    			break;
		    			
		    		case "REFER TO HOSPITAL":
		    				
		    			$absent_id_arr = explode(",",$doc['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID']);
    					log_message("debug","aaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbarrrrrrrrrrrrrrrrrrrrrrr".print_r($absent_id_arr,true));
    					$UI_arr = array_merge($UI_arr,$absent_id_arr);
		    				
		    			break;
		    
		    
		    		default:
		    			;
		    			break;
    			}
    		}
    		 
    		return $UI_arr;
    	}
    }
    
	public function get_all_requests()
    {
    	$today = date('Y-m-d');
    	log_message("debug","ttttttttttttttttttttttttttttttttttttttttttttttt".print_r($today,true));
    	$query = $this->mongo_db->select(array("doc_data.widget_data","history"))->get($this->request_app_col);
    	$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    	
    	$stage_type = [];
    	
    	log_message("debug","wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww".print_r($app_workflow,true));
    	
    	foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    		foreach ($stage['UsersList'] as $user){
    			$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    		}
    	}
    	
    	log_message("debug","sassssssssssssssssssssssssssssssssssssssssssssssssssssssss".print_r($stage_type,true));
    	
    	$device_initiated = 0;
    	$web_initiated = 0;
    	$prescribed = 0;
    	$medication = 0;
    	$followUp = 0;
		$cured = 0;
    	//$attended = 0;
		
    	foreach ($query as $report){
			$status = $report['doc_data']['widget_data']['page2']['Review Info']['Status'];
			if($status == "Initiated"){
				$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
				$stg_type = $stage_type[$array_key];
				if($stg_type == "device"){
					$device_initiated++;
				}else{
					$web_initiated++;
				}
				
			}else if($status == "Prescribed"){
				$prescribed++;
			}else if($status == "Under Medication"){
				$medication++;
			}else if($status == "Follow-up"){
				$followUp++;
			}else if($status == "Cured"){
				$cured++;
			}
    	}
		
    	$requests = [];
		
		$request['label'] = 'Device Initiated';
		$request['value'] = $device_initiated;
		array_push($requests,$request);
		
		$request['label'] = 'Web Initiated';
		$request['value'] = $web_initiated;
		array_push($requests,$request);
		
		$request['label'] = 'Prescribed';
		$request['value'] = $prescribed;
		array_push($requests,$request);
		
		$request['label'] = 'Under Medication';
		$request['value'] = $medication;
		array_push($requests,$request);
		
		$request['label'] = 'Follow-up';
		$request['value'] = $followUp;
		array_push($requests,$request);
		
		$request['label'] = 'Cured';
		$request['value'] = $cured;
		array_push($requests,$request);
		
		
		return $requests;
    }
    
    //======================================================================
    
    public function drilldown_request_to_districts($data)
    {
    	$today = date('Y-m-d');
    	 
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    			 
    	ini_set('memory_limit', '512M');
    	
    	if($type == "Device Initiated"){
    		
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    		
    		
    		
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    		
    		$query = [];
	    	foreach ($query_temp as $report){
				$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
				$stg_type = $stage_type[$array_key];
				if($stg_type == "device"){
					array_push($query,$report);
				}
	    	}
    		
    	}else if($type == "Web Initiated"){
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    		
    		
    		
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    		
    		$query = [];
	    	foreach ($query_temp as $report){
				$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
				$stg_type = $stage_type[$array_key];
				if($stg_type == "device"){
					array_push($query,$report);
				}
	    	}
    	}else{
    		$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	}
    	
    	$dist_list = [];
    			
    	foreach ($query as $request){
    				
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
    		if(isset($dist_list[$district])){
    			$dist_list[$district]++;
    		}else{
    			$dist_list[$district] = 1;
    			}
    	}
    			
    	$final_values = [];
    	foreach ($dist_list as $dicsts => $count){
    		$result['label'] = $dicsts;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    			
    	return $final_values;
    }
    
    public function get_drilling_request_schools($data)
    {
    	$today = date('Y-m-d');
    	 
    	$obj_data = json_decode($data,true);
    	log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	
    	
    	if($type == "Device Initiated"){
    	
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    	
    	
    	
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    	
    		$query = [];
    		foreach ($query_temp as $report){
    			$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
    			$stg_type = $stage_type[$array_key];
    			if($stg_type == "device"){
    				array_push($query,$report);
    			}
    		}
    	
    	}else if($type == "Web Initiated"){
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    	
    	
    	
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    	
    		$query = [];
    		foreach ($query_temp as $report){
    			$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
    			$stg_type = $stage_type[$array_key];
    			if($stg_type == "device"){
    				array_push($query,$report);
    			}
    		}
    	}else{
    		$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	}
    	
//     	ini_set('memory_limit', '512M');
//     	$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);

    	$school_list = [];
    	$matching_docs = [];
    	 
    	foreach ($query as $request){
    	
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
    		if(strtolower($district) == $dist){
    			array_push($matching_docs,$doc[0]);
    		}
    	}
    	
    	foreach ($matching_docs as $docs){
    		$school_name = $docs['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if(isset($school_list[$school_name])){
    			$school_list[$school_name]++;
    		}else{
    			$school_list[$school_name] = 1;
    		}
    	}
    	
    	 
    	$final_values = [];
    	foreach ($school_list as $school => $count){
    		$result['label'] = $school;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    	 
    	return $final_values;    
    }
    
    public function get_drilling_request_students($data)
    {
    	$today = date('Y-m-d');
    	 
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = $obj_data['1'];
    	
    	log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	
    	if($type == "Device Initiated"){
    	
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    	
    	
    	
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    	
    		$query = [];
    		foreach ($query_temp as $report){
    			$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
    			$stg_type = $stage_type[$array_key];
    			if($stg_type == "device"){
    				array_push($query,$report);
    			}
    		}
    	
    	}else if($type == "Web Initiated"){
    		$app_workflow = $this->mongo_db->select(array("workflow"))->where("_id", $this->request_app_col)->get("applications");
    		 
    		$stage_type = [];
    		foreach ($app_workflow[0]['workflow'] as $stg => $stage){
    			foreach ($stage['UsersList'] as $user){
    				$stage_type[$stg."_".$user] = $stage['Stage_Type'];
    			}
    		}
    	
    	
    	
    		$query_temp = $this->mongo_db->where(array('doc_data.widget_data.page2.Review Info.Status' => "Initiated"))->get($this->request_app_col);
    	
    		$query = [];
    		foreach ($query_temp as $report){
    			$array_key = $report['history'][0]['current_stage']."_".$report['history'][0]['submitted_by'];
    			$stg_type = $stage_type[$array_key];
    			if($stg_type == "device"){
    				array_push($query,$report);
    			}
    		}
    	}else{
    		$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	}
    	 
    	//ini_set('memory_limit', '512M');
    	//$query = $this->mongo_db->whereLike('doc_data.widget_data.page2.Review Info.Status',$type)->get($this->request_app_col);
    	$student_list = [];
    	$matching_docs = [];
    	
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$school = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if($school == $school_name){
    			array_push($matching_docs,$doc[0]['_id']->{'$id'});
    		}
    	}
    		 
    	return $matching_docs;
    
    }
    
    public function get_drilling_request_students_docs($_id_array)
    {
    	$docs = [];
    	
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    
    }
    
    //----------------------------------------------------------------------
    
    //===================================id=================================================
    

    public function drilldown_identifiers_to_districts($data)
    {
    	$today = date('Y-m-d');
    
    	$obj_data = json_decode($data,true);
    	$type = $obj_data['label'];
    
    	ini_set('memory_limit', '512M');
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	$dist_list = [];
    	 
    	foreach ($query as $identifiers){
    
    		$retrieval_list = array();
    		$unique_id 	 	 = $identifiers['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
    		if(isset($dist_list[$district])){
    			$dist_list[$district]++;
    		}else{
    			$dist_list[$district] = 1;
    		}
    	}
    	 
    	$final_values = [];
    	foreach ($dist_list as $dicsts => $count){
    		$result['label'] = $dicsts;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    	 
    	return $final_values;
    }
    
    public function get_drilling_identifiers_schools($data)
    {
    	$today = date('Y-m-d');
    
    	$obj_data = json_decode($data,true);
    	log_message("debug","aaaaaaaaaaaaasfsdadsvadsfvdfvfdvfdvfd".print_r($obj_data,true));
    
    	$type = $obj_data[0];
    	$dist = strtolower ($obj_data[1]);
    	log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    	 
    	ini_set('memory_limit', '512M');
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	$school_list = [];
    	$matching_docs = [];
    
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$district = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['District'];
    		if(strtolower($district) == $dist){
    			array_push($matching_docs,$doc[0]);
    		}
    	}
    	 
    	foreach ($matching_docs as $docs){
    		$school_name = $docs['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if(isset($school_list[$school_name])){
    			$school_list[$school_name]++;
    		}else{
    			$school_list[$school_name] = 1;
    		}
    	}
    	 
    
    	$final_values = [];
    	foreach ($school_list as $school => $count){
    		$result['label'] = $school;
    		$result['value'] = $count;
    		array_push($final_values,$result);
    	}
    
    	return $final_values;
    }
    
    public function get_drilling_identifiers_students($data)
    {
    	$today = date('Y-m-d');
    
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = $obj_data['1'];
    	 
    	log_message("debug","tttttttttttttttttttttttttttttttttttttttttttttt".print_r($type,true));
    
    	ini_set('memory_limit', '512M');
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page1.Problem Info.Identifier", array($type))->get($this->request_app_col);
    	$student_list = [];
    	$matching_docs = [];
    	 
    	foreach ($query as $request){
    		 
    		$retrieval_list = array();
    		$unique_id 	 	 = $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];
    		$doc = $this->mongo_db->where('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->get($this->screening_app_col);
    		$school = $doc[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'];
    		if($school == $school_name){
    			array_push($matching_docs,$doc[0]['_id']->{'$id'});
    		}
    	}
    	 
    	return $matching_docs;
    
    }
    
    public function get_drilling_identifiers_students_docs($_id_array)
    {
    	$docs = [];
    	 
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    
    }
    
    //===================================id=================================================
    
    public function get_all_screenings()
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	
    	$requests = [];
    	
		$request['label'] = 'Physical Abnormalities';
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight", "Under Weight"))->count($this->screening_app_col);
		$request['value'] = $query;
		array_push($requests,$request);
		
		
		$request['label'] = 'General Abnormalities';
		//$search = array("doc_data.widget_data.page5.Doctor Check Up.Deficencies" => array("Over Weight", "Under Weight"));
    	$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.N A D", array("Yes"))->count($this->screening_app_col);
		$request['value'] = $query;
		array_push($requests,$request);
		
		$request['label'] = 'Eye Abnormalities';
		$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6", "doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "", "doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6", "doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
		$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		$request['value'] = $query;
		array_push($requests,$request);
		
		$request['label'] = 'Auditory Abnormalities';
		$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Left" => "Pass", "doc_data.widget_data.page8. Auditory Screening.Speech Screening" => array('Normal'));
		$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		$request['value'] = $query;
		array_push($requests,$request);
		
		$request['label'] = 'Dental Abnormalities';
		$search = array("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene" => "Good", "doc_data.widget_data.page9.Dental Check-up.Carious Teeth" => "No", "doc_data.widget_data.page9.Dental Check-up.Flourosis" => "No","doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No","doc_data.widget_data.page9.Dental Check-up.Indication for extraction" => "No");
		$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
		$request['value'] = $query;
		array_push($requests,$request);
		
		return $requests;
    }
    
    public function get_drilling_screenings_abnormalities($data)
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	$obj_data = json_decode($data,true);
    	
    	$type = $obj_data['label'];
    	switch ($type) {
    		case "Physical Abnormalities":
    			$requests = [];
    			$request['label'] = 'Over Weight';
    			$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Under Weight';
    			$query = $this->mongo_db->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);

    			return $requests;
    		break;
    		case "General Abnormalities":
    			$requests = [];
    			$request['label'] = 'General';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Ortho';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Postural';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Defects at Birth';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Deficencies';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Childhood Diseases';
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);

    			return $requests;
    		break;
    		case "Eye Abnormalities":
    			$requests = [];
    			
    			$request['label'] = 'Without Glasses';
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
				$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'With Glasses';
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			$request['label'] = 'Colour Blindness';
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			return $requests;
    		break;
    		case "Auditory Abnormalities":
    			$requests = [];
    			
    			$request['label'] = 'Right Ear';
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Left Ear';
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			 
    			$request['label'] = 'Speech Screening';
    			
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->count($this->screening_app_col);
    			$request['value'] = $query;
    			array_push($requests,$request);
    			
    			return $requests;
    		break;
    		case "Dental Abnormalities":
    			$requests = [];
    			
    			$request['label'] = 'Oral Hygiene';
				$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->count($this->screening_app_col);
				$request['value'] = $query;
				array_push($requests,$request);
				
				$request['label'] = 'Carious Teeth';
				$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->count($this->screening_app_col);
				$request['value'] = $query;
				array_push($requests,$request);
				
				$request['label'] = 'Flourosis';
				$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->count($this->screening_app_col);
				$request['value'] = $query;
				array_push($requests,$request);
				
				$request['label'] = 'Orthodontic Treatment';
				$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->count($this->screening_app_col);
				$request['value'] = $query;
				array_push($requests,$request);
				
				$request['label'] = 'Indication for extraction';
				$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->count($this->screening_app_col);
				$request['value'] = $query;
				array_push($requests,$request);
				
    			return $requests;
    		break;
    		
    		default:
    			;
    		break;
    	}
    	
    }
    
    public function get_drilling_screenings_districts($data)
    {
    	//$query = $this->mongo_db->orderBy(array('doc_data.widget_data.page1.Personal Information.Name' => 1))->select(array("doc_data.widget_data"))->limit($per_page)->offset($page-1)->get($this->screening_app_col);
    	$obj_data = json_decode($data,true);
    	
    	$type = $obj_data['label'];
    	switch ($type) {
    		case "Over Weight":
    			
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    			
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		break;
    		
    		case "Under Weight":
    			
				// ini_set('memory_limit', '512M');
    			// $query = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name", "TSWRS-MG,JADCHERLA")->get($this->screening_app_col);
    			// $chk =0;
				// $id=1000;
    			// foreach ($query as $doc){
    					// $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'] = 'MBNR_1423101_'.$id;
    					// $query = $this->mongo_db->where("_id", new MongoID($doc["_id"]))->set($doc)->update($this->screening_app_col);
    					// $chk++;
						// $id++;
    				
    			// }
    			// log_message("debug","chhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhkkkkkkkkkkkkkkkkkkkkkkkk".print_r($chk,true));
    			
    			 
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    			 
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    			
    		break;
    		
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    			 
    		break;
    		
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    		
    			return $this->get_drilling_screenings_districts_prepare_pie_array($query);
    		
    		break;
    		
    
    		default:
    			;
    			break;
    	}
    	 
    }
    
    public function get_drilling_screenings_schools($data)
    {
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    	 
    	$type = $obj_data['0'];
    	$dist = strtolower ($obj_data['1']);
    	switch ($type) {
    		case "Over Weight":
    			 
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    			
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
		    	
    			break;
    
    		case "Under Weight":
    			 
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    			 
    			break;
    
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			//$search = array("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment" => "No");
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_schools_prepare_pie_array($query,$dist);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_screenings_students($data)
    {
    	$obj_data = json_decode($data,true);
    	log_message("debug","ooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($obj_data,true));
    
    	$type = $obj_data['0'];
    	$school_name = strtolower ($obj_data['1']);
    	switch ($type) {
    		case "Over Weight":
    
    			$query = $this->mongo_db->select(array("_id","doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Over Weight"))->get($this->screening_app_col);
    			 
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    		  
    			break;
    
    		case "Under Weight":
    
    
    			$query = $this->mongo_db->select(array("doc_data.widget_data"))->whereIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Under Weight"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "General":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Check the box if normal else describe abnormalities", array("Neurologic", "H and N", "ENT","Lymphatic","Heart","Lungs","Genitalia","Skin"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Ortho":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Ortho", array("Neck", "Shoulders", "Arms/Hands","Hips","Knees","Feet"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Postural":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page4.Doctor Check Up.Postural", array("No spinal Abnormality", "Spinal Abnormality", "Mild","Marked","Moderate"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Defects at Birth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Defects at Birth", array("Neural Tube Defect", "Down Syndrome", "Cleft Lip and Palate","Talipes Club foot","Developmental Dysplasia of Hip", "Congenital Cataract","Congenital Deafness","Congenital Heart Disease","Retinopathy of Prematurity"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Deficencies":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Deficencies", array("Anaemia", "Vitamin Deficiency - Bcomplex", "Vitamin A Deficiency","Vitamin D Deficiency","SAM/stunting", "Goiter","Under Weight","Over Weight","Obese"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Childhood Diseases":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNotIn("doc_data.widget_data.page5.Doctor Check Up.Childhood Diseases", array("Skin Conditions","Otitis Media","Rheumatic Heart Disease","Asthma","Convulsive Disorders","Hypothyroidism","Diabetes","Epilepsy"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Without Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.Without Glasses.Right" => "6/6", "doc_data.widget_data.page6.Without Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "With Glasses":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page6.With Glasses.Right" => "", "doc_data.widget_data.page6.With Glasses.Left" => "","doc_data.widget_data.page6.With Glasses.Right" => "6/6", "doc_data.widget_data.page6.With Glasses.Left" => "6/6");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Colour Blindness":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page7.Colour Blindness.Right" => "No", "doc_data.widget_data.page7.Colour Blindness.Left" => "No");
    			$query = $this->mongo_db->whereNe($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Right Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Right" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Left Ear":
    			ini_set('memory_limit', '512M');
    			$search = array("doc_data.widget_data.page8. Auditory Screening.Left" => "Fail");
    			$query = $this->mongo_db->where($search)->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Speech Screening":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereInAll("doc_data.widget_data.page8. Auditory Screening.Speech Screening", array('Delay',"Misarticulation","Fluency","Voice"))->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Oral Hygiene":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->whereNe("doc_data.widget_data.page9.Dental Check-up.Oral Hygiene","Good")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Carious Teeth":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Carious Teeth", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Flourosis":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Flourosis","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Orthodontic Treatment":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Orthodontic Treatment","Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    		case "Indication for extraction":
    			ini_set('memory_limit', '512M');
    			$query = $this->mongo_db->where("doc_data.widget_data.page9.Dental Check-up.Indication for extraction", "Yes")->get($this->screening_app_col);
    
    			return $this->get_drilling_screenings_students_prepare_pie_array($query,$school_name);
    
    			break;
    
    
    		default:
    			;
    			break;
    	}
    
    }
    
    public function get_drilling_screenings_districts_prepare_pie_array($query)
    {
    	$requests = [];
    	
    	$request['label'] = 'Adilabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'adilabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Hyderabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'hyderabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'KarimNagar';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'karimnagar'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Khammam';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'khammam'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Mahabubnagar';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'mahabubnagar'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Medak';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'medak'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Nalgonda';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'nalgonda'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Nizamabad';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'nizamabad'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Ranga Reddy';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'ranga reddy'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	$request['label'] = 'Warangal';
    	$count = 0;
    	if($query){
    		foreach ($query as $dist){
    			if(isset($dist['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($dist['doc_data']['widget_data']['page2']['Personal Information']['District']) == 'warangal'){
    					$count++;
    				}
    			}
    		}
    	}
    	$request['value'] = $count;
    	array_push($requests,$request);
    	
    	return $requests;
    }
    
    public function get_drilling_screenings_schools_prepare_pie_array($query,$dist)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['District'])){
    				if(strtolower ($doc['doc_data']['widget_data']['page2']['Personal Information']['District']) == $dist){
    					array_push($search_result,$doc);
    				}
    			}
    		}
    		$request = [];
    		foreach ($search_result as $doc){
    			if(isset($request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']])){
    				$request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']]++;
    			}else{
    				$request[$doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']] = 1;
    			}
    		}
    	
    		log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($request,true));
    		$final_values = [];
    		foreach ($request as $school => $count){
    			log_message("debug","schooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo".print_r($school,true));
    			log_message("debug","ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc".print_r($count,true));
    			$result['label'] = $school;
    			$result['value'] = $count;
    			array_push($final_values,$result);
    		}
    	
    		log_message("debug","fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff".print_r($final_values,true));
    	
    		return $final_values;
    	}
    }
    
    public function get_drilling_screenings_students_prepare_pie_array($query,$school_name)
    {
    	$search_result = [];
    	$count = 0;
    	if($query){
    		foreach ($query as $doc){
    			if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'])){
    				if(strtolower($doc['doc_data']['widget_data']['page2']['Personal Information']['School Name']) == $school_name){
    					array_push($search_result,$doc['_id']->{'$id'});
    				}
    			}
    		}
    		 
    		return $search_result;
    	}
    }
    
    public function get_drilling_screenings_students_docs($_id_array)
    {
    	
    	$docs = [];
    	
    	foreach ($_id_array as $_id){
    		$query = $this->mongo_db->select(array('doc_data.widget_data.page1','doc_data.widget_data.page2'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    		array_push($docs,$query[0]);
    	}
    	return $docs;
    	
    }
    
	public function drill_down_screening_to_students_load_ehr_doc($_id)
    {
     $query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    if($query){
	 	$query_request = $this->mongo_db->where("doc_data.widget_data.page1.Student Info.Unique ID", $query[0]["doc_data"]['widget_data']['page1']['Personal Information']['Hospital Unique ID'])->get($this->request_app_col);
	 	 $result['screening'] = $query;
	 	 $result['request'] = $query_request;
		 return $result;
	 }else{
		 $result['screening'] = false;
    	$result['request'] = false;
    	return $result;
	 }
    }
    
    public function drill_down_screening_to_students_doc($_id)
    {
    	$query = $this->mongo_db->select(array('doc_data.widget_data','doc_data.chart_data','doc_data.external_attachments','history'))->where("_id", new MongoID($_id))->get($this->screening_app_col);
    	if($query){
    		
    		return $query;
    	}else{
    		
    		return false;
    	}
    }
    
    
	//*************************************************
	
    /**
     * Helper: Prepares IP address string for database insertion.
     *
     * @return string
     */
    protected function _prepare_ip($ip_address)
    {
    	return $ip_address;
    }	
    
    public function user_exists($email = FALSE){
    	 
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['panacea_health_supervisors']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    
    	if($query !== array()){
    		return  TRUE;
    	}else{
    		return FALSE;
    	}
    }
    
    /**
     * Sets an error message
     */
    public function set_error($error)
    {
    	$this->errors[] = $error;
    	return $error;
    }
    
    /**
     * Applies delimiters and returns themed errors
     */
    public function errors()
    {
    	$_output = '';
    	foreach ($this->errors as $error)
    	{
    		$error_lang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
    		$_output .= $this->error_start_delimiter . $error_lang . $this->error_end_delimiter;
    	}
    
    	return $_output;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Return errors as an array, langified or not
     **/
    public function errors_array($langify = TRUE)
    {
    	if ($langify)
    	{
    		$_output = array();
    		foreach ($this->errors as $error)
    		{
    			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
    			$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
    		}
    		return $_output;
    	}
    	else
    	{
    		return $this->errors;
    	}
    }
    
    /**
     * Generates a random salt value.
     */
    public function salt()
    {
    	return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }
    
    /**
     * Hashes the password to be stored in the database.
     */
    public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE)
    {
    	if (empty($password))
    	{
    		return FALSE;
    	}
    
    	// Bcrypt
    	if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
    	{
    		return $this->bcrypt->hash($password);
    	}
    
    
    	if ($this->store_salt && $salt)
    	{
    		return sha1($password . $salt);
    	}
    	else
    	{
    		$salt = $this->salt();
    		return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
    	}
    }
	
	public function get_hs_req_docs($usercollection)
	{
		$this->mongo_db->orderBy(array('doc_received_time' => -1))->limit(10);
	  	$query=$this->mongo_db->select(array(),array('_id'))->where(array('status'=>'new','app_id'=>'healthcare2016531124515424'))->get($usercollection);
       	return $query; 
	}
    public function check_if_doc_exists($unique_id)
    {
        $is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('healthcare2017617145744625');

        log_message('debug', "unique_id4537======" . print_r($unique_id, true));
        log_message('debug', "is_existssssssss" . print_r($is_exists, true));

        if ($is_exists) {
            return true;
        } else {
            return false;
        }

    }
    public function update_bmi_values($month, $monthly_bmi, $unique_id)
    {

        $check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id, "doc_data.widget_data.page1.Student Details.BMI_values" => array('$elemMatch' => array("month" => $month)));

        $is_already_updated = $this->mongo_db->where($check_query)->get('healthcare2017617145744625');

        if ($is_already_updated) {
            $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id, "doc_data.widget_data.page1.Student Details.BMI_values" => array('$elemMatch' => array("month" => $month)));

            $update_values = array('doc_data.widget_data.page1.Student Details.BMI_values.$.height' => $monthly_bmi['height'], 'doc_data.widget_data.page1.Student Details.BMI_values.$.weight' => $monthly_bmi['weight'], 'doc_data.widget_data.page1.Student Details.BMI_values.$.bmi' => $monthly_bmi['bmi']);

            $update = array('$set' => $update_values);

            $response = $this->mongo_db->command(array(
                'findAndModify' => 'healthcare2017617145744625',
                'query'         => $query,
                'update'        => $update,
            ));

            $update_values_main = array('doc_data.widget_data.page1.Student Details.Height cms' => $monthly_bmi['height'],
                'doc_data.widget_data.page1.Student Details.Weight kgs'                             => $monthly_bmi['weight'],
                'doc_data.widget_data.page1.Student Details.BMI'                                    => $monthly_bmi['bmi']);

            $update_main = array('$set' => $update_values_main);

            $response = $this->mongo_db->command(array(
                'findAndModify' => 'healthcare2017617145744625',
                'query'         => $query,
                'update'        => $update_main,
            ));

            if ($response['ok']) {
                return true;
            } else {
                return false;
            }

            //db.getCollection('healthcare20176616511646').update({},{'$pull':{"doc_data.widget_data.page1.Student Details.BMI_values":{"month":"2017-11"}}})

            //$this->mongo_db->pull('doc_data.widget_data.page1.Student Details.BMI_values',array('month'=>$month))->update('healthcare20176616511646');

        }

        //$query_main = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.Date"=>array('$elemMatch'=> $month));

        $new_date = new DateTime($month);
        $ndate    = $new_date->format('Y-m-d');

        $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->set(array('doc_data.widget_data.page1.Student Details.Height cms' => $monthly_bmi['height'],
            'doc_data.widget_data.page1.Student Details.Weight kgs'  => $monthly_bmi['weight'],
            'doc_data.widget_data.page1.Student Details.BMI'              => $monthly_bmi['bmi'],
            'doc_data.widget_data.page1.Student Details.Date'             => $ndate))
            ->update('healthcare2017617145744625');

        $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->push('doc_data.widget_data.page1.Student Details.BMI_values', $monthly_bmi)->update('healthcare2017617145744625');

        if ($after_update) {
            return true;
        } else {
            return false;
        }

    }

    public function add_student_BMI_model($doc_data, $doc_properties, $app_properties, $history)
    {
        $doc_data = array("doc_data" => array("widget_data" => $doc_data), "doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);

        $query = $this->mongo_db->insert('healthcare2017617145744625', $doc_data);
        if ($query) {
            return true;
        } else {
            return false;
        }

    }
    public function fetch_student_info_model($unique_id)
    {
        //$res = $this->mongo_db->select(array('doc_data.widget_data'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $unique_id))->get('healthcare2016226112942701');
        $res = $this->mongo_db->select(array('doc_data.widget_data'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $unique_id))->get('tswreis_screening_report_col_2021-2022');
        if(empty($res))
        {
            $res = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $unique_id )->get ( "other_classes_screening_data_2020-2021" );
        }else if (empty($res)) {
           $res = $this->mongo_db->where ( "doc_data.widget_data.page1.Personal Information.Hospital Unique ID", $unique_id )->get ( "screening_report_col_2021-2022_passed_out" );
        }     
            return $res;
    }
    //hb === cc
     public function check_if_doc_exists_in_hb($unique_id)
    {
        $is_exists = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('himglobin_report_col');
        if ($is_exists) {
            return true;
        } else {
            return false;
        }

    }

     public function add_student_HB_model($doc_data, $doc_properties, $app_properties, $history)
    {
        $doc_data = array("doc_data" => array("widget_data" => $doc_data), "doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);

        $query = $this->mongo_db->insert('himglobin_report_col', $doc_data);
        if ($query) {
            return true;
        } else {
            return false;
        }

    }
     public function update_hb_values($month, $monthly_hb, $unique_id)
    {
        $check_query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id, "doc_data.widget_data.page1.Student Details.HB_values" => array('$elemMatch' => array("month" => $month)));

        $is_already_updated = $this->mongo_db->where($check_query)->get('himglobin_report_col');

        if ($is_already_updated) {
            $query = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id, "doc_data.widget_data.page1.Student Details.HB_values" => array('$elemMatch' => array("month" => $month)));

            $update_values = array('doc_data.widget_data.page1.Student Details.HB_values.$.hb' => $monthly_hb['hb']);

            $update = array('$set' => $update_values);

            $response = $this->mongo_db->command(array(
                'findAndModify' => 'himglobin_report_col',
                'query'         => $query,
                'update'        => $update,
            ));

            $update_values_main = array(
                'doc_data.widget_data.page1.Student Details.HB' => $monthly_hb['hb']);

            $update_main = array('$set' => $update_values_main);

            $response = $this->mongo_db->command(array(
                'findAndModify' => 'himglobin_report_col',
                'query'         => $query,
                'update'        => $update_main,
            ));

            if ($response['ok']) {
                return true;
            } else {
                return false;
            }

            //db.getCollection('healthcare20176616511646').update({},{'$pull':{"doc_data.widget_data.page1.Student Details.BMI_values":{"month":"2017-11"}}})

            //$this->mongo_db->pull('doc_data.widget_data.page1.Student Details.BMI_values',array('month'=>$month))->update('healthcare20176616511646');

        }

        //$query_main = array('doc_data.widget_data.page1.Student Details.Hospital Unique ID'=> $unique_id,"doc_data.widget_data.page1.Student Details.Date"=>array('$elemMatch'=> $month));

        $new_date = new DateTime($month);
        $ndate    = $new_date->format('Y-m-d');

        $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->set(array(
            'doc_data.widget_data.page1.Student Details.HB'   => $monthly_hb['hb'],
            'doc_data.widget_data.page1.Student Details.Date' => $ndate))
            ->update('himglobin_report_col');

        $after_update = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->push('doc_data.widget_data.page1.Student Details.HB_values', $monthly_hb)->update('himglobin_report_col');

        if ($after_update) {
            return true;
        } else {
            return false;
        }

    }
    //hb cc
    public function get_student_hb_values($unique_id)
    {
        $query = $this->mongo_db->select(array('doc_data.widget_data.page1.Student Details.HB_values'))->where(array('doc_data.widget_data.page1.Student Details.Hospital Unique ID' => $unique_id))->get('himglobin_report_col');

        if ($query) {
            return $query;
        } else {
            return false;
        }

    }
    //common model
     public function get_hs_req_normal($usercollection)
    {
            //ini_set ( 'memory_limit', '2G' );
          $query_normal = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Normal');
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->where($query_normal)->get($usercollection);


       /* $hs_history = array('app_properties.status'=>'new','doc_data.widget_data.page2.Review Info.Request Type' => 'Normal');
         $this->mongo_db->orderBy(array('history.time' => -1));
        $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(200)->where($hs_history)->get($usercollection);*/
        
        return $query;
    }
     public function get_hs_req_emergency($usercollection)
    {
        //$array_emergency = array();
        $documents = [];
        $query_emergency = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency');
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->where($query_emergency)->get($usercollection);

        foreach ($query as $data) {
                $exp = $data['doc_data']['widget_data']['page2']['Review Info']['Status'];
                if($exp != "Expired"){
                    array_push($documents, $data);
                }
            }

        return $documents;
    }
    /**
     * Helper: Fetch Chronic requests
     *
     *  author Naresh
     */
    public function get_hs_req_chronic($usercollection)
    {
        $query_chronic = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic');
        $this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->
        where($query_chronic)->get($usercollection);

        return $query;
    }
    // upadate request
     public function get_history($unique_id,$doc_id)
    {
        $query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id,'doc_properties.doc_id' => $doc_id))->get('healthcare2016531124515424_static_html');
        return $query;
    } 

    public function get_student_history($uniqueid)
    {
        $query = $this->mongo_db->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $uniqueid))->get('tswreis_screening_report_col_2021-2022');
        return $query;
    }
     public function update_request_submit_model($doc_data,$history_array,$unique_id,$doc_id)
    {   
        
        $update_query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->set(array('doc_data' =>$doc_data,'history'=>$history_array))->update('healthcare2016531124515424_static_html');

        return $update_query;
    }
     public function access_submited_request_docs($doc_id)
    {
        $query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_properties.doc_id' => $doc_id))->get('healthcare2016531124515424_static_html');
        
        return $query;
    }
    //cc attendence
    public function get_schools_by_dist_id_model($dist_id) {
            $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
            $query = $this->mongo_db->select ( array (
                    'school_name'
            ) )->orderBy ( array (
                    'school_name' => 1 
            ) )->where ( 'dt_name', $dist_id )->get ( $this->collections ['panacea_schools'] );
            $this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );
            return $query;
     
    }

    public function get_dist_name_with_dist_id( $districtID)
    {
       
        $query = $this->mongo_db->select(array('dt_name'))->where("_id", new MongoID($districtID))->get('panacea_district');

        $result = $query[0]['dt_name'];
       
        return $result;
    }

    public function create_attendence_report_model($doc_data, $doc_properties, $app_properties, $history)
    {
        $final_values = array("doc_data"=>array("widget_data"=>$doc_data),"doc_properties"=>$doc_properties, "history"=>$history, "app_properties"=>$app_properties);
        $query = $this->mongo_db->insert('healthcare201651317373988',$final_values);

        if($query)
          return TRUE;
        else
          return FALSE;
    }
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
    public function get_school_information_for_school_code($school_code)
    {
        $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
        $school_data = $this->mongo_db->where(array('school_code'=>$school_code))->select(array('school_name','dt_name','contact_person_name','school_mob'),array())->get('panacea_schools');
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
    public function get_health_supervisor_details($schoolCode)
    {
        $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
        $query = $this->mongo_db->where(array('school_code'=>$schoolCode))->get($this->collections['panacea_health_supervisors']);
        $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
        return $query[0];
    }
    function get_approval_history($doc_id)
    {
        $query = $this->mongo_db->select(array('history'))->where(array('doc_properties.doc_id'=> $doc_id))->get('healthcare2016531124515424_static_html');
        return $query[0]['history'];
    }
    public function create_screening_report_model($doc_data, $doc_properties, $app_properties, $history)
    {
        $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"doc_properties"=>$doc_properties, "app_properties" => $app_properties ,"history"=>$history);

        $query = $this->mongo_db->insert ($this->screening_app_col, $doc_data );
        return $query;
    }

    public function get_pf_photo($unique_id)
    {
        $query = $this->mongo_db->select(array('doc_data.widget_data.page1.Personal Information.Photo'))->where(array('doc_data.widget_data.page1.Personal Information.Hospital Unique ID' => $unique_id))->whereNe(array('doc_data.widget_data.page1.Personal Information.Photo' => ""))->get($this->screening_app_col);
        if(!empty($query))
        {
            return $query;
        }
    }

    public function update_screening_report_model($unique_id, $doc_data, $history)
    {
        //echo print_r($doc_data,TRUE);exit();
      //  $doc_data = array("doc_data"=>array("widget_data"=>$doc_data),"doc_properties"=>$doc_properties, "app_properties" => $app_properties ,"history"=>$history);

        //$query = $this->mongo_db->where ('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set(array('doc_data.widget_data' => $doc_data,"history"=>$history))->update($this->screening_app_col);
        $query = $this->mongo_db->where ('doc_data.widget_data.page1.Personal Information.Hospital Unique ID',$unique_id)->set(array('doc_data.widget_data' => $doc_data,"history"=>$history))->update($this->screening_app_col_sw_2021_2022);
        return $query;
    }

    //field officer form start author sumanreddy
    public function submit_field_officer($doc_data, $doc_attachments,  $doc_properties, $app_properties, $history)
    {

          $doc_data = array("doc_data" => $doc_data,'doc_attachments' =>$doc_attachments, "doc_properties" => $doc_properties, "app_properties" => $app_properties, "history" => $history);

          $query = $this->mongo_db->insert('field_officer_report',$doc_data);
          if($query)
              return TRUE;
          else
              return FALSE;
    }
    public function fetch_field_officer_reports($today_date)
    {
        $final_data = array();

          $out_patients = $this->mongo_db->whereLike('history.last_stage.time',$today_date )->where('doc_data.widget_data.type_of_request',"Out Patients")->count('field_officer_report');

          $data['name'] = "Out Patients";
          $data['y'] = $out_patients;
          array_push($final_data,$data); 

          $emergency_admitted = $this->mongo_db->whereLike('history.last_stage.time',$today_date )->where('doc_data.widget_data.type_of_request',"Emergency or Admitted")->count('field_officer_report');

          $data['name'] = "Emergency or Admitted";
          $data['y'] = $emergency_admitted;
          array_push($final_data,$data); 


          $review_cases = $this->mongo_db->whereLike('history.last_stage.time',$today_date )->where('doc_data.widget_data.type_of_request',"Review Cases")->count('field_officer_report');

          $data['name'] = "Review Cases";
          $data['y'] = $review_cases;
          array_push($final_data,$data);


          return $final_data;

    }
    public function drill_down_to_field_officer_reports_list($selectedCase, $selectedDate)
    {
         
           $this->mongo_db->orderBy(array('history.last_stage.time' => -1));
             $getSubmittedDocs = $this->mongo_db->whereLike('history.last_stage.time',$selectedDate)->where(array("doc_data.widget_data.type_of_request"=>$selectedCase))->get('field_officer_report');
             
             if(!empty($getSubmittedDocs)){
                return $getSubmittedDocs;
             }else{
                return "No details";
             }
           
            return $getSubmittedDocs;
          
    }
    public function show_field_officer_submit_student(/*$student_id,$doctor_visit_date*/ $doc_id)
    {
         
            
             $getSubmittedDocs = $this->mongo_db->where(array('doc_properties.doc_id'=>$doc_id))->get('field_officer_report');
             

           
            return $getSubmittedDocs;
          
    }
    //field officer form end


    // Regular follwoups

     public function get_regular_followup_cases_from_requests($email_id)
    {
        
        $query=$this->mongo_db->where(array('regular_follow_up.Active_status'=> 1))->get($this->request_app_col_static_html);

        return $query;
    }
    public function get_regular_followup_closed_cases()
    {
        $query = $this->mongo_db->where('regular_follow_up.Active_status', 0)->get($this->request_app_col_static_html);
        
        return $query;
    }

public function update_requests_followup_data($student_id, $case_id,$created_time,$medicine_details,$followup_desc, $next_scheduled_date)
    {
        
         $data = array('medicine_details'=>$medicine_details, 'followup_desc'=>$followup_desc,'created_time'=> $created_time, 'next_scheduled_date' => $next_scheduled_date);
      
           $query = array('doc_data.widget_data.page1.Student Info.Unique ID' => $student_id, 'doc_properties.doc_id' => $case_id);

          
           $update = array('$push'=>array("regular_follow_up.Follow_Up"=>$data));

          
           $response = $this->mongo_db->command(array( 
            'findAndModify' => $this->request_app_col_static_html,
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

      public function close_followup_request($case_id)
    {

        $followup_close = $this->mongo_db->where(array('doc_properties.doc_id' =>$case_id))->set('regular_follow_up.Active_status', 0)->update($this->request_app_col_static_html);

        return $followup_close;
    }

    public function get_searched_student_sick_requests_model($search_data)
    {
        $data = trim($search_data);

        if(preg_match("/_/", $data)){

            $query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Unique ID', $data)->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->orderBy(array('history.0.time' => -1))->get("healthcare2016531124515424_static_html");

        }else{

            $query = $this->mongo_db->whereLike('doc_data.widget_data.page1.Student Info.Name.field_ref', $data)->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->orderBy(array('history.0.time' => -1))->get("healthcare2016531124515424_static_html");
            
        }

        return $query;
    }

    /* Transferring Dental photos to External Attachments */

   public function transfer_dental_to_external_attachment($doc_id, $academic)
    {
        if(!empty($doc_id))
        {
            $docs = json_decode($doc_id);

            foreach ($docs as $id) {
                $get_pic = $this->mongo_db->select(array("doc_data"))->where("doc_properties.doc_id", $id)->get($academic);

                if(!empty( $get_pic[0]['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']))
                {
                   
                   // $fileName = $get_pic[0]['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_name'];
                    $filePath = $get_pic[0]['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];
                    $fileSize = $get_pic[0]['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_size'];

                 
                    $random = substr(number_format(time() * rand(),0,'',''),0,10);

                    $external_data_array = array(
                                              "DFF_EXTERNAL_ATTACHMENTS_".$random => array(
                                            "file_client_name" =>"Dental_photo",
                                            
                                            "file_path" =>$filePath,
                                            "file_size" =>$fileSize ) );

                    if(isset($get_pic[0]['doc_data']['external_attachments']) && !empty($get_pic[0]['doc_data']['external_attachments'])){

                        $updates = array('$push'=> array("doc_data.external_attachments"=> $external_data_array));

                        $query = array("doc_properties.doc_id"=> $id);
                       
                        $response = $this->mongo_db->command(array(
                        'findAndModify' => $academic,
                        'query'         => $query,
                        'update'        => $updates
                         ));

                        if($response['ok']){

                             $set_profile_empty = $this->mongo_db->where("doc_properties.doc_id", $id)->set(array("doc_data.widget_data.page1.Personal Information.Photo"=>""))->update($academic);
                           
                        }

                    }
                    else{

                       $update_external_if_not_exists = $this->mongo_db->where("doc_properties.doc_id", $id)->set(array("doc_data.external_attachments"=> $external_data_array))->update($academic);

                       if($update_external_if_not_exists)
                       {
                            $set_profile_empty = $this->mongo_db->where("doc_properties.doc_id", $id)->set(array("doc_data.widget_data.page1.Personal Information.Photo"=>""))->update($academic);
                           
                       }
                    }

                  
                }
            }

            return "Succesfull";
        }
    }

    public function save_cc_request_notes($data, $history)
    {
        $doc_data = array("doc_data"=>$data, "history" => $history);

       $query =  $this->mongo_db->insert("tswreis_cc_notes_for_requests", $doc_data);

       if($query){
            return "Successfully inserted";
       }else{
            return "Failed";
       }
    }

    public function save_cc_calls_count($purpose, $call, $history)
    {
        $dates = explode(" ", $history['datetime']);

        $today = $dates[0];

        $checkExists = $this->mongo_db->where("Purpose", $purpose)->whereLike("history.datetime", $today)->get("tswreis_calls_data");

        if(!empty($checkExists)){

            
            if(isset($checkExists[0]["Details"]["$call"])){
                $count = $checkExists[0]["Details"]["$call"]+1;
            }else{
                $count = 1;
            }

            $totalCalls = $checkExists[0]["Total_calls"]+1;
           
            
            $query = $this->mongo_db->where('Purpose', $purpose)->whereLike('history.datetime', $today)->set(array("Details.$call"=>$count, "Total_calls"=>$totalCalls))->update("tswreis_calls_data");

            if($query){
                return "Updated";
            }else{
                return "Not updated";
            }
        }else{

            $data = array(
                    'Purpose'=>$purpose,
                    "Details" => array("$call"=>1),
                    "Total_calls" => 1,
                    "history" => $history
                );

           $query = $this->mongo_db->insert("tswreis_calls_data", $data);

          return $query;
        }

    
    }

    public function get_saved_notes($start_date, $username)
    {
       
        $query = $this->mongo_db->where('history.user', $username)->whereLike('history.datetime', $start_date)->get("tswreis_cc_notes_for_requests");

        return $query;
    }

     public function get_schools_list_only() {

        $final = [];

            $this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
            $query = $this->mongo_db->select ( array (
                    'school_name'
            ) )->orderBy ( array (
                    'school_name' => 1 
            ) )->get ( $this->collections ['panacea_schools'] );
            $this->mongo_db->switchDatabase ( $this->common_db ['dsn'] );

            foreach ($query as $schools) {
                array_push($final, $schools['school_name']);
            }

            return $final;
     
    }

    public function get_student_recent_requests_data($id)
    {
        $data['totalReqCount'] = $this->mongo_db->where('doc_data.widget_data.page1.Student Info.Unique ID', $id)->count($this->request_app_col_static_html);

        $data['recent_req'] = $this->mongo_db->where('doc_data.widget_data.page1.Student Info.Unique ID', $id)->orderBy(array('history.0.time'=>-1))->limit(1)->get($this->request_app_col_static_html);
        return $data;
    }

    public function calls_data_date_wise($date, $email)
    {

        $query = $this->mongo_db->where("history.user", $email)->whereLike("history.datetime", $date)->get("tswreis_calls_data");

        return $query;
    }



}

