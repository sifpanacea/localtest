<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class reader_Model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
		$this->load->library('mongo_db');
        $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
	    $this->collections = $this->config->item('collections','ion_auth');
	    $this->load->library('mongo_db');
    }
    
    // ---------------------------------------------------------------------------------------

	/**
	 * Helper: get the new apps from user collection
	 *
	 * @author Selva 
	 *
	 * @param string user collection
	 *
	 *
	 * @return array
	 */

	public function update_apps($collection_name)
    {	
	    $this->mongo_db->orderBy(array('app_created'=>-1));
		$this->mongo_db->where(array('status'=>'new'));
	    $this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->get($collection_name);
		return $query;
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

	public function update_docs($usercollection)
	{
		$final_query = array();
		$query       = array();
		
		$this->mongo_db->orderBy(array('doc_received_time'=>-1));
		$doc_query=$this->mongo_db->select(array(),array('_id'))->where('status','new')->get($usercollection);
		

		$data_query = $this->mongo_db->select(array('app_id','doc_id'),array())->where('status','new')->get($usercollection);
		
		foreach($data_query as $id)
		{
			$collection_name = $id['app_id'];
			$doc_id          = $id['doc_id'];
			
			$this->mongo_db->where('doc_properties.doc_id',$doc_id);
			$this->mongo_db->select(array('doc_data.widget_data.page1'),array());
			$query = array_merge($query,$this->mongo_db->get($collection_name));
		}
		
		$final_query['doc']    = $doc_query;
		$final_query['widget'] = $query;

		$obj = json_decode(json_encode($final_query),FALSE);
		
		return $obj; 
		
	}

	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the application details for inbox view from the usercollection
	*
	* @author Selva
	*
	* @param string user collection
	* @param string application id
	*
	*
	* @return array
	*/

	public function get_application_details_from_collection($usercollection,$app_id)
	{

	$query=$this->mongo_db->select(array('app_id','app_name','app_description','app_created','app_expiry','_version','created_by'))->where('app_id',$app_id)->get($usercollection);
	return $query[0];
	}

	// ---------------------------------------------------------------------------------------

	/**
	* Helper: get the document details for inbox view from the usercollection
	*
	* @author Selva
	*
	* @param string user collection
	* @param string application id
	* @param string document id
	*
	* @return array
	*/

	public function get_document_details_from_collection($usercollection,$app_id,$doc_id)
	{
		$query=$this->mongo_db->select(array(),array('_id','stage'))->where(array('app_id'=>$app_id,'doc_id'=>$doc_id))->get($usercollection);
		return $query[0];
	}
	
	public function update_latest_apps($usersession)
	{	
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->limit(10)->get($usersession);
		return $query;
	}
	
	public function user_web_apps($usercollection)
	{	
	    $this->load->library('mongo_db');
		$this->mongo_db->orderBy(array('app_created'=>-1));
		$query=$this->mongo_db->get($usercollection);
		return $query;
		
	}
	
	
	public function user_web_docs($usercollection)
	{	
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->get($usercollection);
		log_message('debug','user____Web_______________docssssssssssssss'.print_r($query,true));
		return $query;
		
	}
	
	
    public function update_latest_docs($usersession)
	{
		
	  	log_message('debug','entered users');
		log_message('debug','@@@@@@@@@@@@@@@aaaaaaaaaarrrrrrrrrray user********session******!!!!!!!!!!!!!!!!!!'.print_r($usersession,true));
	    $this->load->library('mongo_db');
		$query=$this->mongo_db->limit(10)->get($usersession);
		return $query;
		
	}
	
	public function user_profile_data($email)
	{
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select(array(),array('password','ip_address','_id','created_on','last_login','active','salt','remember_code','forgotten_password_code','forgotten_password_time','activation_code'));
	   $query=$this->mongo_db->limit(1)->getWhere('users',array('email'=>$email));
	   return $query;
	}
	
	public function user_company_data($company)
	{
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select();
	   $query=$this->mongo_db->limit(1)->getWhere('customers',array('company_name'=>$company));
	   return $query;
	}
	
	public function install_data($appid, $user)
	{
		
		log_message('debug','install_datainstall_datainstall_datainstall_data');
		log_message('debug',print_r($appid,true));
		log_message('debug',print_r($user,true));
		log_message('debug','verification of appid in install_data fn');
		$this->load->library('mongo_db');
		$this->mongo_db->select(array(),array('status','app_id','_id'));
		$test=$this->mongo_db->getWhere($user,array('app_id'=>$appid));
		//$this->mongo_db->get($user);
		$this->mongo_db->where(array('app_id'=>$appid))->set('status','processed')->update($user);
		log_message('debug','EWxit arrayaayayayya');
		log_message('debug',print_r($user,true));
		log_message('debug','444444444444444444444444444444444444444444444');
		log_message('debug',print_r($test,true));
		return $test;
		
	}
 
 
 public function access_docs($appid,$user)
	{
		
		log_message('debug','install_datainstall_datainstall_datainstall_data');
		log_message('debug',print_r($appid,true));
		$doci = intval($appid);
		log_message('debug',print_r($user,true));
		log_message('debug','verification of appid in install_data fn');
		$this->load->library('mongo_db');
		$this->mongo_db->select(array(),array('status','time','_id'));
		$test=$this->mongo_db->getWhere($user,array('doc_id'=>$doci));
		//$this->mongo_db->get($user);
		//$this->mongo_db->where(array('app_id'=>$appid))->set('status','processed')->update($user);
		log_message('debug','EWxit arrayaayayayya');
		log_message('debug',print_r($user,true));
		log_message('debug','5555555555555555555555555555555555555555555555');
		log_message('debug',print_r($test,true));
		return $test;
		
	}

	public function expiry_date($company)
	  {
	    $comp = str_replace(" ","",$company);
	    $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	    $document = $this->mongo_db
			->select(array('plan_expiry'))
			->where('company_name',$comp)
			->limit(1)
			->get('customers'); 
	  log_message('debug','paas expiryyyyyyyyyyyyyyyyyyyyyyy documenttttttttttttttttttttttttttttttt in reader_______modelllll'.print_r($document,true));
      $expirydate = $document[0]['plan_expiry'];
	  log_message('debug','paas expiryyyyyyyyyyyyyyyyyyyyyyy documenttttttttttttttttttttttttttttttt in reader_______modelllll'.print_r($expirydate,true));
	  return $expirydate;
	 }

	 public function getappforcreate($appid)
	 {

       $query=$this->mongo_db->select(array('workflow'))->where('_id',$appid)->get($this->collections['records']);
       log_message('debug','getappforcreateeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee in reader_______modelllll'.print_r($query[0],true));
       return $query[0];

	 }
	
	 public function getpermissionsforcreate($appid,$collection)
	 {
        $this->mongo_db->select();
		$test=$this->mongo_db->getWhere($collection,array('app_id'=>$appid)); 
		return $test[0];
	 }

	public function process_web_apps($usercollection,$appid)
	{	
	    $this->load->library('mongo_db');
		$this->mongo_db->where('app_id', $appid)->set('status','read')->update($usercollection);
	}

	public function process_web_docs($usercollection,$appname,$docid)
	{	
	    log_message('debug','inside process_web_---docsssssssssssssssssssssssssssssss collection_name'.print_r($usercollection,true));
	    log_message('debug','inside process_web_---docsssssssssssssssssssssssssssssss appnameee'.print_r($appname,true));
		log_message('debug','inside process_web_---docsssssssssssssssssssssssssssssss doc_id'.print_r($docid,true));
		$doci = intval($docid);
		$this->mongo_db->where(array('app_name'=>$appname,'doc_id'=>$doci))->set('status','processed')->update($usercollection);
		log_message('debug','inside process_web_---docsssssssssssssssssssssssssssssss processedddddddddddddddddddddddddd');
		
	} 

	public function web_doc_search($collection)
	{

        $this->load->library('mongo_db');
		$query=$this->mongo_db->get($collection);
		log_message('debug','user____Web_______________appsssssslisttttttt'.print_r($query,true));
		return $query;
	}

	public function update_read_apps($collection_name)
    {	
	    $this->mongo_db->select(array(),array('_id'))->where('status','read');
		$query=$this->mongo_db->get($collection_name);
		return $query;
    }
}