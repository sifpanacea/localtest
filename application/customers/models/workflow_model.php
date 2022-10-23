<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Workflow_Model extends CI_Model 
{
	public $controller_name;
	public $appName;

    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->library('session');
		$this->load->helper('paas');

        // Initialize MongoDB database names
        $this->collections = $this->config->item('collections', 'ion_auth');
	    $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
 

    }
	
	public function create($data)
	{
		//Extraemos las variables
		$this->init($data);
	}
	
	private function init($data)
	{
		$this->controller_name			=	$data['controller_name'];
		$this->appName					=	$data['appName'];
	}


    public function save($jsonarray,$app_id)
	 {
	 	
	 	$collection_name = substr($app_id, 0,strpos($app_id, "_"));
	 	$form_data = array( 	
							'workflow' => $jsonarray 
						);
		$this->mongo_db->where('_id', $collection_name)->set($form_data)->update($this->collections['records']);
	 }
	 
public function getappcon($app_id)
	 {
	    
	 	$collection_name = substr($app_id, 0,strpos($app_id, "_"));
		$this->mongo_db->select(array(),array('time','app_name','app_type','app_description','_id','app_expiry','created_by','app_category','pages'));
		$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$collection_name));
		log_message('debug','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.print_r($collection_name,true));
		return $query;
	 }

   public function get_app_template($app_id)
   {
	 
	 	$collection_name = substr($app_id, 0,strpos($app_id, "_"));
	 	$this->mongo_db->select(array(),array('workflow'));
	 	$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$collection_name));
	 	return $query;
	 
    }
	 public function getcurrentapp($app_id)
	 {
	 	$collection_name = substr($app_id, 0,strpos($app_id, "_"));
		$this->mongo_db->select(array(),array('time','app_description','_id','app_expiry','created_by','app_category'));
		$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$collection_name));
		return $query;
	 }

	 public function userfornotifyapp($app_id)
	 {
	 	$appid = substr($app_id, 0,strpos($app_id, "_"));
	 	$this->mongo_db->select(array(),array('time','app_name','app_template','app_type','app_description','_id','app_expiry','created_by','app_category','pages','_version','status','print_template','notify_parameters','application_header','use_profile_header','blank_app'));
		$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$appid));
		return $query;
	 }

	 public function workflow_for_processing_stagenames($app_id)
	 {
	 	$this->mongo_db->select(array(),array('time','app_name','app_template','app_type','app_description','_id','app_expiry','created_by','app_category','pages','_version','status','print_template','notify_parameters','application_header','use_profile_header','blank_app'));
		$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$app_id));
		return $query;
	 }
	 
	 public function get_expiry_date()
	 {
	 	$this->mongo_db->select(array(),array('time','app_name','app_template','app_type','app_description','_id','workflow','created_by','pages','_version','app_category','notify_parameters','workflow','use_profile_header','blank_app'));
		$query=$this->mongo_db->get($this->collections['records']);
		return $query;
	 }
	public function users($name)
{
	$CI = & get_instance();  //get instance, access the CI superobject
	$customerdetails = $CI->session->userdata("customer");
	$customer_company = $customerdetails['company'];
    $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
   $count = count($name);
  log_message('debug','USERSSSSSSSSSSSSSSSSSSSSSSSS(((((--WORKFLOW--)))))'.print_r($name,true));
  log_message('debug','USERSSSSSSSSSSSSSSSSSSSSSSSS(((((--WORKFLOW--)))))---------customerrrrrrrrrcompanyyyyyyyyyyyy'.print_r($customer_company,true));
$query = array();
//for($i=0;$i<$count;$i++)
//{
$this->mongo_db->select(array(),array('_id'));
$query=$this->mongo_db->getWhere($this->collections['users'],array('groups'=>$name,'company'=>$customer_company));
//}
log_message('debug','USERSSSSSSSSSSSSSSSSSSSSSSSSQUERYYYYYYYYYYYYYYYYYYYYY(((((--WORKFLOW--)))))'.print_r($query,true));
return $query;

}
	 
	 public function getgroups($company)
	 {
	  	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query = $this->mongo_db->getWhere($this->collections['groups'],array('company'=>$company));
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $query;
	 }

	 public function save_analytics_pattern($insertdata)
     {
     	
     	$query = $this->mongo_db->insert($this->collections['analytics'],$insertdata);
     	return $query;
     }



	  public function get_saved_pattern($id)
	 {
	   $this->mongo_db->select(array('pattern'),array());
	   $query=$this->mongo_db->getWhere('analytics',array('_id'=>new MongoId($id)));
	   log_message('debug','WORKFLOW______________MODEL______________SAVEDDDDDDDDDDDPATTERNNNNNNNNNNNNNN'.print_r($query,true));
	   return $query[0];
	 }
	 
	 public function company_name()
	 {
	    $CI = & get_instance();  //get instance, access the CI superobject
        $customer = $CI->session->userdata("customer");
		$cus_email = $customer['email'];
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$this->mongo_db->select(array('company_name'));
		$query1 = $this->mongo_db->getWhere('customers',array('email'=>$cus_email));
		return $query1;
	 }

	 public function insert_shared_app_template($shared_company,$app_id,$app_name,$app_description,$app_type,$app_expiry,$app_category,$app_template)
	 {
		 $app_created = date('Y-m-d H:i:s');

		 $data = array(
		        "app_name"        => $app_name,
				"app_template"    => $app_template,
				"app_id"          => $app_id,
				"app_description" => $app_description,
				"app_type"        => $app_type,
				"app_expiry"      => $app_expiry,
				"app_category"    => $app_category,
				"app_created_on"  => $app_created,
				"shared_by"       => $shared_company
 			 	);

		 $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
         $this->mongo_db->insert($this->collections['collection_for_shared_apps'],$data);
         $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		
	 }
	 
	 public function share_app($appid,$usercompany)
	 {
	 	$this->mongo_db->select(array(),array('workflow'));
	 	$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$appid));
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	 	foreach($query as $app)
	 	{
	 		$apptemplate = $query[0]['app_template'];
	 		$appdes      = $query[0]['app_description'];
	 		$appex       = $query[0]['app_expiry'];
	 		$nameofapp   = $query[0]['app_name'];
	 		$typeofapp   = $query[0]['app_type'];
	 		$appcreated  = $query[0]['time'];
			$appcategory = $query[0]['app_category'];
	 	}
	 	$data = array(
	 			"app_name" => $nameofapp,
	 			"app_template" => $apptemplate,
	 			"app_id" => $appid,
	 			"app_description" => $appdes,
	 			"app_type" => "Shared",
	 			"app_expiry" => $appex,
				"app_category" => $appcategory,
	 			"app_created_on" => $appcreated,
	 			"shared_by" => $usercompany
	 	);
	 	
	 	$this->mongo_db->insert($this->collections['collection_for_shared_apps'],$data);
	 	$app = array(
	 			"app_type" => "Shared"
	 	);
	 	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	 	$this->mongo_db->where('_id',$appid)->set($app)->update($this->collections['records']);
	 }
	 
	 public function unshare_app($appid)
	 {
	 	
	 	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	    $this->mongo_db->where('app_id',$appid)->delete($this->collections['collection_for_shared_apps']);
	    $app = array(
	    		"app_type" => "Private"
	    );
	    $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	    $this->mongo_db->where('_id', $appid)->set($app)->update($this->collections['records']);
	 }

	 public function insert_user_applist($collection,$appname,$appid,$appdescription,$appcreated)
	 {
         $availablecheck = $this->exists($collection,$appid);
         if($availablecheck == FALSE)
         {
         	$app = array(
 			 	"app_id"          => $appid,
				"app_description" => $appdescription,
 			 	"app_name" 		  => $appname,
				"app_created" 	  => $appcreated
				        );
						
 			 $this->mongo_db->insert($collection,$app);
         }
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Inserts application entry in collection for device stage user
	 *
	 * @param  string     $collectionname    User collection
	 *
	 * @author  Selva
	 */

	 public function insert_user_appcollection($collectionname,$app_temp,$appdescription,$app_temp1,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$stagenames,$notification_parameters,$created_by,$use_profile_header,$blank_app)
	 {
         
	  	$available = $this->exists($collectionname,$appid);
		if(!$available)
		{
			 
 			 $app = array(
				"app_template"        => array('pages'=>$app_temp,'permissions'=> $app_temp1,'notification_parameters'=>$notification_parameters,"application_header"  => $application_header),
 			 	"app_id"              => $appid,
				"app_description"     => $appdescription,
 			 	"status"              => "new",
 			 	"app_name"            => $appname,
				"app_created"         => $appcreated,
				"app_expiry"          => $appexpiry,
				"_version"            => $version,
				"stages"              => $stagenames,
				"created_by"          => $created_by,
				"use_profile_header"  => $use_profile_header,
 			 	"blank_app"			  => $blank_app);
				
				
 			 $this->mongo_db->insert($collectionname,$app);
 			 	
		}
		else
		{
            
			$editedapp = $this->mongo_db->select(array('app_template','_version'))->getWhere($this->collections['records'], array('_id' => $appid));
            $query = $this->mongo_db->select(array('app_template'))->getWhere($collectionname, array('app_id' => $appid));
			$existapp = array();
            $permissions = array();
     
            foreach($editedapp as $data1)
            {
            	$existapp    = $data1['app_template'];
				$newversion  = $data1['_version'];
            }
			foreach($query as $data)
			{

                if($workflow_mode==="create" || $workflow_mode==='')
                {
                	 $permissions = ($data['app_template']['permissions'] += $app_temp1);

                     $apptemp = array(
				       "app_template" => array('pages'=>$existapp,'permissions'=>$permissions,'notification_parameters'=>$notification_parameters,"application_header"  => $application_header)
					   );

			         $this->mongo_db->where('app_id', $appid)->set($apptemp)->update($collectionname);
                }
                else if($workflow_mode==="edit")
                {

                	$permissions = ($data['app_template']['permissions'] += $app_temp1);
					$version_    = $newversion+1;

					$app = array(
				    "app_template"        => array('pages'=>$existapp,'permissions'=>$permissions,'notification_parameters'=>$notification_parameters,"application_header"  => $application_header),
				    "status"              => "new",
					"app_description"     => $appdescription,
 			 	    "app_name"            => $appname,
				    "app_created"         => $appcreated,
				    "app_expiry"          => $appexpiry,
					"_version"            => $version_,
					"stages"              => $stagenames,
					"created_by"          => $created_by,
					"use_profile_header"  => $use_profile_header,
					"blank_app"			  => $blank_app );
					
					$this->mongo_db->where('app_id', $appid)->set($app)->update($collectionname);
                }
			}

        }
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Inserts application entry in collection for web stage user
	 *
	 * @param  string     $collectionname    User collection 
	 *
	 * @author  Selva
	 */

	 public function insert_user_web_appcollection($collectionname,$app_temp1,$appdescription,$appid,$appname,$appcreated,$application_header,$appexpiry,$version,$workflow_mode,$created_by,$use_profile_header,$blank_app)
	 {
        $available = $this->exists($collectionname,$appid);
		if(!$available)
		{
 			 $app = array(
				"permissions"         => $app_temp1,
 			 	"app_id"              => $appid,
				"app_description"     => $appdescription,
 			 	"status"              => "new",
 			 	"app_name"            => $appname,
				"app_created"         => $appcreated,
				"app_expiry"          => $appexpiry,
				"application_header"  => $application_header,
				"_version"            => $version,
				"created_by"          => $created_by,
				"use_profile_header"  => $use_profile_header,
 			 	"blank_app"			  => $blank_app);

 			 $this->mongo_db->insert($collectionname,$app);
 			 	
		}
		else
		{
            $query = $this->mongo_db->select(array('permissions','_version'))->getWhere($collectionname, array('app_id' => $appid));
			$existpermissions = array();
			foreach($query as $data)
			{
				
				$existpermissions = $data['permissions'];
				$newversion       = $data['_version'];
			}
			
			if($workflow_mode==="create" || $workflow_mode==='')
			{
				$permissions = array(
						"permissions" => ($existpermissions += $app_temp1)
				);
				
				$this->mongo_db->where('app_id', $appid)->set($permissions)->update($collectionname);
			}
			else if($workflow_mode==="edit")
			{
				$permissions  = ($existpermissions += $app_temp1);
				$version_     = $newversion+1;
				
			    $app = array(
				"permissions"         => $permissions,
				"app_description"     =>$appdescription,
 			 	"status"              => "new",
 			 	"app_name"            => $appname,
				"app_created"         => $appcreated,
				"app_expiry"          => $appexpiry,
				"application_header"  => $application_header,
				"_version"            => $version_,
				"created_by"          => $created_by,
				"use_profile_header"  => $use_profile_header,
			    "blank_app"			  => $blank_app
				);
				$this->mongo_db->where('app_id', $appid)->set($app)->update($collectionname);
				
			}

		}
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Deletes application entry in user's application list collection
	 *
	 * @param  string     $collection    Name of the collection 
	 * @param  string     $appid         Application ID
	 *
	 * @author  Selva
	 */
	 
	 public function delete_user_applist($collection,$appid)
	 {
         $availablecheck = $this->exists($collection,$appid);
         if($availablecheck == TRUE)
         {
		   $this->mongo_db->where('app_id',$appid)->delete($collection);
         }
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Deletes application entry in user's application list collection
	 *
	 * @param  string     $collection    Name of the collection 
	 * @param  string     $appid         Application ID
	 *
	 * @author  Selva
	 */
	 
	 public function delete_user_appcollection($email,$appid,$application_name)
	 {
	     $collection = $email.'_apps';
         $availablecheck = $this->exists($collection,$appid);
         if($availablecheck == TRUE)
         {
		     $this->mongo_db->where('app_id',$appid)->delete($collection);
		   
			   $notification = array(
					   'message_owner' => 'Delete',
					   'sent_source'   => 'enterprise_admin',
					   'message_id'    => get_unique_id(),
					   'message'       => 'Application '.$application_name.' is deleted',
					   'deleted_ref'   => $appid,
					   'sent_time'     => date('Y-m-d H:i:s'),
					   'status'        => 'new'
									 );
			
            $coll = $email.'_push_notifications';			
            $this->mongo_db->insert($coll,$notification);								 
         }
	 }
	 
	// --------------------------------------------------------------------

	/**
	 * Helper : Deletes application entry in user's application list collection
	 *
	 * @param  string     $collection    Name of the collection 
	 * @param  string     $appid         Application ID
	 *
	 * @author  Selva
	 */
	 
	 public function delete_user_web_appcollection($email,$appid)
	 {
	     $collection = $email.'_web_apps';
         $availablecheck = $this->exists($collection,$appid);
         if($availablecheck == TRUE)
         {
		   $this->mongo_db->where('app_id',$appid)->delete($collection);
         }
	 }
	 
	 // --------------------------------------------------------------------

	/**
	 * Helper : Get application entry in application collection to delete
	 *
	 * @param  string     $collection    Name of the collection 
	 * @param  string     $appid         Application ID
	 *
	 * @author  Selva
	 */
	 
	 public function get_app_for_delete($app_id)
	 {
		$this->mongo_db->select(array(),array());
		$query = $this->mongo_db->getWhere($this->collections['records'],array('_id'=>$app_id));
		return $query;
	 }
	 
	 // --------------------------------------------------------------------

	/**
	 * Helper : Save customizied sms content to applications collection
	 *
	 * @param  string     $appid     Application ID
	 * @param  array      $sms       Customizied sms content 
	 *
	 * @author  Selva
	 */
	 
	 public function save_sms_content_to_app_definition($app_id,$sms)
	 {
		$application_id = substr($app_id, 0,strpos($app_id, "_"));
	 	$form_data = array( 	
							'sms_content' => $sms 
						);
		$this->mongo_db->where('_id', $application_id)->set($form_data)->update($this->collections['records']);
	 }
 
 
 
    public function admin_activities($email)
	{
	   $user = str_replace("@","_",$email);
	   $admincollection = $user.'_activities';
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select(array(),array('_id'));
	   $query1=$this->mongo_db->get($admincollection);
	   $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	   return $query1;
	
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Admin messages 
	 *
	 * @param  string     $email    Email id of the enterprise admin 
	 *
	 * @author  Selva
	 */

    public function admin_profile_data($email)
	{
	   $this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	   $this->mongo_db->select(array(),array('password','confirm_password','ip_address','_id','last_login','active','salt','remember_code','forgotten_password_code','forgotten_password_time','activation_code'));
	   $query=$this->mongo_db->limit(1)->getWhere($this->collections['collection_for_authentication'],array('email'=>$email));
	   $this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	   return $query;
	}
	
	/**
	 * Helper : Admin messages
	 *
	 * @param  string     $email    Email id of the enterprise admin
	 *
	 * @author  Selva
	 */
	
	public function sub_admin_profile_data($email)
	{
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$this->mongo_db->select(array(),array('password','confirm_password','ip_address','_id','last_login','active','salt','remember_code','forgotten_password_code','forgotten_password_time','activation_code'));
		$query=$this->mongo_db->limit(1)->getWhere($this->collections['sub_admins'],array('email'=>$email));
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $query;
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : Admin messages 
	 *
	 * @param  string     $username    Name of the admin 
	 * @param  string     $message     Message		
	 *
	 * @author  Selva
	 */

     public function user_message($username,$message)
     {
	      $user = str_replace("@","_",$username);
	      $msgcollection = $user.'_msg';
	      $this->mongo_db->insert($msgcollection,$message);
     }

    // --------------------------------------------------------------------

	/**
	 * Helper : Select applications ( community apps ) based on the given category
	 *
	 * @param  int     $limit       Limit count
	 * @param  int     $page        Page number	
	 * @param  string  $category    Category of the application	
	 *
	 * @return array
	 *
	 * @author  Selva (Modified by Sekar)
	 */

     public function select_community_app($limit,$page,$category)
     {
		$offset = $limit * ( $page - 1) ;
	   	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
	   	$query = $this->mongo_db->where('app_category',$category)->get($this->collections['collection_for_shared_apps']);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	   	return $query;
   	 }

    // --------------------------------------------------------------------

	/**
	 * Helper : Total count of applications 
	 *	
	 *
	 * @author  Selva
	 */

     function appcount()
     {
    	
    	return $this->mongo_db->count($this->collections['records']);
     }
   
    // --------------------------------------------------------------------

	/**
	 * Helper : Enterprise admin's enterprise details 
	 *	
	 * @param  string  $company   Name of the enterprise	
	 *
	 * @author  Selva
	 */

     public function company_details($company)
     {
   	
	   	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$this->mongo_db->select(array(),array('password','confirm_password','ip_address','_id','last_login','active','salt','remember_code','forgotten_password_code','forgotten_password_time','activation_code'));
		$query = $this->mongo_db->limit(1)->getWhere($this->collections['collection_for_authentication'],array('company_name'=>$company));
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	   	return $query;
   	 }
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Mark the specified app as draft 
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */

	public function mark_as_draft($application_id,$workflow)
	{
	  	$this->mongo_db->where('_id', $application_id)->set(array('workflow'=>$workflow,'status'=>0))->update($this->collections['records']);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */

	public function fetch_app_details_for_custom_notification($application_id)
	{
	  	$query = $this->mongo_db->where('_id', $application_id)->select(array(),array('_id'))->get($this->collections['records']);
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
	public function panacea_helath_supervisors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['panacea_health_supervisors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
	 
	public function panacea_doctors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['panacea_doctors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   	public function ttwreis_admin_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['ttwreis_admins']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
      	public function ttwreis_cc_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['ttwreis_cc']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
    public function ttwreis_doctors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['ttwreis_doctors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddd'.print_r($query,true));
	return $query;
   }
   
   // ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
	public function ttwreis_health_supervisors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['ttwreis_health_supervisors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   //TMREIS=======================================================
      	public function tmreis_admin_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['tmreis_admins']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
      	public function tmreis_cc_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['tmreis_cc']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
    public function tmreis_doctors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['tmreis_doctors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   // ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
	public function tmreis_health_supervisors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['tmreis_health_supervisors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   // ------------------------------------------------------------------------
   
   //BC Welfare=======================================================
      	public function bc_welfare_admin_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['bc_welfare_admins']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
      	public function bc_welfare_cc_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['bc_welfare_cc']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
    public function bc_welfare_doctors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['bc_welfare_doctors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   // ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
	public function bc_welfare_health_supervisors_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['bc_welfare_health_supervisors']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
   public function panacea_admin_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['panacea_admins']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   // ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Selva
	 */
	 
   public function panacea_cc_model()
   {
	$query = array();
	$this->mongo_db->select(array(),array('_id'));
	$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
	$query=$this->mongo_db->getWhere($this->collections['panacea_cc']);
	$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
	return $query;
   }
   
   // ------------------------------------------------------------------------

	/**
	 * Helper : Fetch application template and workflow for custom notification
	 *
	 * @param  string  $application_id   Application id	
	 *
	 * @author  Bhanu
	 */
	 
   public function get_rhso_users_model()
   {
		$query = array();
		$this->mongo_db->select(array(),array('_id'));
		$this->mongo_db->switchDatabase($this->_configvalue['common_db']);
		$query=$this->mongo_db->getWhere($this->collections['rhso_users']);
		$this->mongo_db->switchDatabase($this->_configvalue['dsn']);
		return $query;
   }
	
	// --------------------------------------------------------------------

	/**
	 * Helper : Checks if the given id is present in the given collection 
	 *	
	 * @param  string  $collectionname  Name of the collection
	 * @param  string  $id              Application id
	 *
	 * @return  boolean
	 *
	 * @author  Vikas
	 */

	public  function exists($collectionname,$id)
    {
    	$query = $this->mongo_db->getWhere($collectionname, array('app_id' => $id));
    	
		$result = json_decode(json_encode($query), FALSE);

    	if ($result)
    		return TRUE;
    	else
    		return FALSE;
    }
	
		public function insert_excel_data($doc_data, $history, $doc_properties)
	{
		
		$query = $this->mongo_db->getWhere("healthcare2016226112942701", array('doc_data.widget_data.page2.Personal Information.AD No' => $doc_data['widget_data']['page2']['Personal Information']['AD No'],'doc_data.widget_data.page2.Personal Information.School Name'=>'TSWRS-MG,JADCHERLA'));
		
		//$query = $this->mongo_db->getWhere("form_data_sample_copy_1", array('doc_data.widget_data.page2.Physical Info.ID number' => $doc_data['widget_data']['page2']['Physical Info']['ID number'],'doc_data.widget_data.page2.Physical Info.School'=>'TSWRS/JC(G)-JADCHERLA'));
		
    	
		$result = json_decode(json_encode($query), FALSE);

    	if (!$result)
    	{	
	    $form_data = array();
		$form_data['doc_data']       = $doc_data;
		$form_data['doc_properties'] = $doc_properties;
		$form_data['history']        = $history;

		$this->mongo_db->insert("healthcare2016226112942701",$form_data);
		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);
		}
		else
		{
			 $form_data = array();
		$form_data['doc_data'] = $doc_data;
		$form_data['doc_data']['widget_data']['page2']['Personal Information']['AD No'] = $doc_data['widget_data']['page2']['Personal Information']['AD No'].'A';
		$form_data['doc_properties'] = $doc_properties;
		$form_data['history'] = $history;
		$this->mongo_db->insert("healthcare2016226112942701",$form_data);
		//$this->mongo_db->insert("form_data_sample_copy_1",$form_data);

		}
	}
		
		// --------------------------------------------------------------------

	/**
	 * Helper : remove app if the given id is present in the given collection  ( in edit mode during first stage )
	 *	
	 * @param  string  $collectionname  Name of the collection
	 * @param  string  $id              Application id
	 *
	 * @return  boolean
	 *
	 * @author  Selva
	 */

	public  function remove_app_from_user_appcollection($collectionname,$id)
    {
    	$query = $this->mongo_db->where(array('app_id' => $id))->delete($collectionname);
    	
		$result = json_decode(json_encode($query), FALSE);

    	if ($result)
    		return TRUE;
    	else
    		return FALSE;
    }
		
		
 
}


