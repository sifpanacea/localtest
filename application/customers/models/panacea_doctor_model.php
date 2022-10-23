<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panacea_doctor_model extends CI_Model
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
        $this->regular_followup_col = "tswreis_reqular_followup_requests";
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
	
	/* public function get_hs_req_docs($usercollection)
	{
		$this->mongo_db->orderBy(array('doc_received_time' => -1));
	  	$query=$this->mongo_db->select(array(),array('_id'))->where(array('status'=>'new','app_id'=>'healthcare2016531124515424'))->get($usercollection);
		return $query; 
	} */
	
	 public function get_hs_req_docs($usercollection)
    {
        //$normal = array('request_type' => 'Emergency','request_type' => 'Chronic', 'status' => 'processed');
      /*  $documents = [];
        $this->mongo_db->orderBy(array('doc_received_time' => -1));
        $not_emergency = $this->mongo_db->select(array(),array('_id'))->limit(200)->whereNe(array('request_type' => 'Emergency'))->where(array('status'=>'new','app_id'=>'healthcare2016531124515424'))->get($usercollection);

        foreach($not_emergency as $query)
        {
            if(isset($query['request_type']) != "Chronic" && isset($query['request_type']) == "Emergency")
            {
                $not_emergency = $not_emergency;
               //continue;
                //return false;
            }
            else
            {
                 $docs =  $query;
            }
            array_push($documents,$docs);
        }
            if(!empty($documents))
            {
                 return $documents;
            }
            else
            {
                 return false;
            } */
			
			$status = array();  
            $match = array('status' => "new");
            $request_type = array('request_type' => array(
                    '$ne' => 'Emergency'
                    )
                         );
             $request_type_chronic = array('request_type' => array(
                    '$ne' => 'Chronic'
                    )
                         );
            array_push($status,$match);
             array_push($status,$request_type);
             array_push($status,$request_type_chronic);
             $limit = 500;
        $sort = array('doc_received_time' => -1);
        $pipeline = [ 
                    array (
                            '$project' => array (
                                    "app_name" => true,
                                    "app_id" => true,
                                    "doc_id" => true,
                                    "stage" => true,
                                    "stg_name" => true,
                                    "status" => true,
                                    "from_stage" => true,
                                    "from_user" => true,
                                    "notification_param" => true,
                                    "doc_received_time" => true,
                                    "approval" => true,
                                    "reason" => true,
                                    "request_type" => true,
                                    "_id" => false
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
           //echo print_r($result,true);
           //exit();
            return $result;
    }
	
    public function get_hs_req_emergency($usercollection)
    {
        $this->mongo_db->orderBy(array('doc_received_time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(500)->where(array('status'=>'new','app_id'=>'healthcare2016531124515424','request_type' => 'Emergency'))->get($usercollection);
        return $query;
    }

    public function get_hs_req_chronic($usercollection)
    {
        $this->mongo_db->orderBy(array('doc_received_time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(500)->where(array('status'=>'new','app_id'=>'healthcare2016531124515424','request_type' => 'Chronic'))->get($usercollection);
        
        return $query; 
    }

    public function get_hs_req_normal_new($usercollection)
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
             /*$this->mongo_db->orderBy(array('history.0.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where(array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Normal'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->get($usercollection);*/

        $hs_history = array('doc_data.widget_data.page2.Review Info.Request Type' => 'Normal');
         $this->mongo_db->orderBy(array('history.time' => -1));
        $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(3000)->where($hs_history)->get($usercollection);
         $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
    {       
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Hospitalized')
        {
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Out-Patient')
            {
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Review')
           
                if(preg_match('/HS/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        }
    }
            } 
        return $full_doc;
    }
    
    public function get_hs_req_emergency_new($usercollection)
    {
        
        $hs_history = array('doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency');
         $this->mongo_db->orderBy(array('history.time' => -1));
        $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(4000)->where($hs_history)->get($usercollection);
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
    {       
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Hospitalized')
        {
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Out-Patient')
            {
            if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Review')
           
                if(preg_match('/HS/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        }
    }
            }
        return $full_doc;
    }

    public function get_hs_req_chronic_new($usercollection)
    {
    
    $hs_history = array('doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic');
         $this->mongo_db->orderBy(array('history.time' => -1));
        $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(4000)->where($hs_history)->get($usercollection);        
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                {       
                        if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Hospitalized')
                    {
                        if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Out-Patient')
                        {
                        if ($history['doc_data']['widget_data']['page2']['Review Info']['Status'] != 'Review')
                       
                            if(preg_match('/HS/i', $end_array['current_stage']))
                            {
                                array_push($full_doc, $history);
                               // $total_req = count($full_doc);
                            }
                        }
                    }
                }
            }
        return $full_doc;
    }

    public function get_hs_req_hospitalized_new($usercollection)
    {      

    $hs_history = array('doc_data.widget_data.page2.Review Info.Status' => 'Hospitalized');
         $this->mongo_db->orderBy(array('history.time' => -1));
    $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->where($hs_history)->get($usercollection);        
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/HS/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

    public function get_hs_req_outpatient_new($usercollection)
    {      

    $hs_history = array('doc_data.widget_data.page2.Review Info.Status' => 'Out-Patient');
         $this->mongo_db->orderBy(array('history.time' => -1));
    $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->where($hs_history)->get($usercollection);        
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/HS/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

    public function get_hs_req_review_new($usercollection)
    {      

    $hs_history = array('doc_data.widget_data.page2.Review Info.Status' => 'Review');
         $this->mongo_db->orderBy(array('history.time' => -1));
    $query = $this->mongo_db->select(array(),array('_id'))->whereNe(array('doc_data.widget_data.page2.Review Info.Status' => "Cured"))->limit(500)->where($hs_history)->get($usercollection);        
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/HS/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

     public function get_hs_req_normal_doctor($usercollection)
    {
        $doctor_history = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Normal');
             $this->mongo_db->orderBy(array('history.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($doctor_history)->get($usercollection);
        $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/Doctor/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

     public function get_hs_req_emergency_doctor($usercollection)
    {
       $doctor_history = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Emergency');
             $this->mongo_db->orderBy(array('history.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($doctor_history)->get($usercollection);
       $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/Doctor/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

     public function get_hs_req_chronic_doctor($usercollection)
    {
    $doctor_history = array('app_properties.status'=>'new','app_properties.app_id'=>'healthcare2016531124515424','doc_data.widget_data.page2.Review Info.Request Type' => 'Chronic');
             $this->mongo_db->orderBy(array('history.time' => -1));
        $query=$this->mongo_db->select(array(),array('_id'))->limit(200)->where($doctor_history)->get($usercollection);
       $full_doc = array();
        foreach ($query as $history)
            {
             $end_array = end($history['history']);
                if(preg_match('/Doctor/i', $end_array['current_stage']))
                {
                    array_push($full_doc, $history);
                   // $total_req = count($full_doc);
                }
            }
        return $full_doc;
    }

    public function access_request_docs_form_hs($doc_id,$doc_access,$access_by)
    {
      $time = time();
        $get_document = $this->mongo_db->select(array(),array('_id'))->where(array('doc_properties.doc_id' => $doc_id))->limit(1)->get('healthcare2016531124515424_static_html');
            if(isset($get_document) && !empty($get_document))
            {
                foreach ($get_document as $document) 
                {
                    if($doc_access != $document['doc_properties']['doc_access'])
                    {
                       // $final_doc = $document;
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

        return $query;
    }

    public function get_history($unique_id,$doc_id)
    {
        $query = $this->mongo_db->select(array(),array('_id'))->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id,'doc_properties.doc_id' => $doc_id))->get('healthcare2016531124515424_static_html');
        return $query;
    }

   /* public function request_docs_update_doctor_model($doc_data,$history_array,$unique_id,$doc_id,$doc_properties)
    {
       // echo print_r($doc_data,true);
       // exit();        
        $update_query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->set(array('doc_data' =>$doc_data,"doc_properties.doc_access"=>$doc_properties['doc_access'],"doc_properties.access_by"=>$doc_properties['access_by'],"doc_properties.doc_access_time"=>$doc_properties['doc_access_time'],'history'=>$history_array))->update('healthcare2016531124515424_static_html');
        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id' => "healthcare2016531124515424",
                        'status' => "new"
                    );

        $doc_data = array('doc_data' => $doc_data,"doc_properties"=>$doc_properties,'app_properties' => $app_properties,'history' => $history_array);
        $this->mongo_db->insert('healthcare2016531124515424_static_html_shadow',$doc_data);

        return $update_query;
    }*/

    public function check_and_get_if_regular_is_there($doc_id, $unique_id)
    {
        $query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->where(array('regular_follow_up'=>array('$exists'=>true)))->get('healthcare2016531124515424_static_html');

        if(isset($query) && !empty($query)){
            return $query;
        }else{
            return "No Data";
        }
    }

     public function request_docs_update_doctor_model($doc_data,$history_array,$unique_id,$doc_id,$doc_properties, $followups)
    {
       // echo print_r($doc_data,true);
       // exit(); 

       if($followups == FALSE){
            $update_query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->set(array('doc_data' =>$doc_data,"doc_properties.doc_access"=>$doc_properties['doc_access'],"doc_properties.access_by"=>$doc_properties['access_by'],"doc_properties.doc_access_time"=>$doc_properties['doc_access_time'],'history'=>$history_array))->update('healthcare2016531124515424_static_html');
       } else {
            $update_query = $this->mongo_db->where(array('doc_data.widget_data.page1.Student Info.Unique ID' => $unique_id, 'doc_properties.doc_id' => $doc_id))->set(array('doc_data' =>$doc_data,"doc_properties.doc_access"=>$doc_properties['doc_access'],"doc_properties.access_by"=>$doc_properties['access_by'],"doc_properties.doc_access_time"=>$doc_properties['doc_access_time'],'history'=>$history_array, 'regular_follow_up'=>$followups))->update('healthcare2016531124515424_static_html');
       }     
        
        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id' => "healthcare2016531124515424",
                        'status' => "new"
                    );

        if($followups == FALSE){
            $doc_data = array('doc_data' => $doc_data,"doc_properties"=>$doc_properties,'app_properties' => $app_properties,'history' => $history_array);
        }else {
            $doc_data = array('doc_data' => $doc_data,"doc_properties"=>$doc_properties,'app_properties' => $app_properties,'history' => $history_array, 'regular_follow_up'=>$followups);
        }
        
        $this->mongo_db->insert('healthcare2016531124515424_static_html_shadow',$doc_data);

        return $update_query;
    }

    function create_regular_followup_request($unique_id,$student_name,$class,$request_type,$symptoms,$problem_info_description,$school_name,$district, $scheduled_date, $review_status)
    {

          $data = array(
          'case_id'            => get_unique_id(),
          'student_unique_id'  => $unique_id,
          'student_name'       => $student_name,
          'class'              => $class,
          'request_type'       => $request_type,
          'review_status'       => $review_status,
          'symptom'            => $symptoms,
          'problem_info_description' => $problem_info_description,
          'scheduled_date' => $scheduled_date,
          'school_name'        => $school_name,
          'district'            => $district,
          'created_time'       => date('Y-m-d H:i:s'),
            'status'            => 1);

        $query = $this->mongo_db->insert('tswreis_reqular_followup_requests', $data);

        return $query;

    }

     public function get_regular_followup_cases_from_requests()
    {

        $query=$this->mongo_db->where('status', 1)->get($this->regular_followup_col);

        return $query;
    }

    public function update_requests_followup_data($student_id,$case_id,$created_time,$medicine_details,$followup_desc, $next_scheduled_date)
    {
        $exist_follow_up = array();
        /*$query = array("student_unique_id"=>$unique_id,"case_id"=>$case_id);

           $update = array('$push'=>array("medication_taken"=>$datewise_update));*/
           $Follow_Up_data = $this->mongo_db->select(array('Follow_Up'))->where(array('case_id' =>$case_id))->get($this->regular_followup_col);

           if(isset($Follow_Up_data[0]['Follow_Up']) && !empty($Follow_Up_data[0]['Follow_Up']))
           {
             $exist_follow_up = $Follow_Up_data[0]['Follow_Up'];
               

             $data = array('medicine_details'=>$medicine_details, 'followup_desc'=>$followup_desc,'created_time'=> $created_time, 'next_scheduled_date' => $next_scheduled_date);

            // array_push($array_follow_up, $Follow_Up_data);
             array_push($exist_follow_up, $data);
           
            $response =  $this->mongo_db->where(array('case_id' =>$case_id))->set(array('Follow_Up' =>
                $exist_follow_up))->update($this->regular_followup_col);
        }else
        {
            $data = array('medicine_details'=>$medicine_details, 'followup_desc'=>$followup_desc,'created_time'=> $created_time, 'next_scheduled_date' => $next_scheduled_date);           
         $response =  $this->mongo_db->where(array('case_id' =>$case_id))->set(array('Follow_Up' =>
                [$data]))->update($this->regular_followup_col);
        }
          


            if($response)
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

        $followup_close = $this->mongo_db->where(array('case_id' =>$case_id))->set('status', 0)->update($this->regular_followup_col);

        return $followup_close;
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

    public function insert_hospitalised_students_data($doc_data,$approval_history,$unique_id,$doc_id,$doc_properties)
    {

        $app_properties = array(
                        'app_name' => "Health Requests App",
                        'app_id' => "healthcare2016531124515424_static_html",
                        'status' => "new"
                    );
       
        $doc_data = array('doc_data' => $doc_data,"doc_id"=>$doc_id,'app_properties' => $app_properties,'history' => $approval_history);

        /*->set(array("doc_properties.doc_access"=>$doc_properties['doc_access'],"doc_properties.access_by"=>$doc_properties['access_by'],"doc_properties.doc_access_time"=>$doc_properties['doc_access_time'],'history'=>$history_array))*/

        $this->mongo_db->insert('ts_hospitalised_students_col',$doc_data);

    }
    

}


