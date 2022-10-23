<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Admin_Model extends CI_Model 
{
	 function __construct()
    {
        parent::__construct();
        
        $this->load->config('ion_auth', TRUE);
        $this->load->config('mongodb',TRUE);
        
        // Initialize MongoDB collection names
        $this->collections = $this->config->item('collections', 'ion_auth');
        $this->common_db   = $this->config->item('default');
    }
	
	public function get_customer_details($new = FALSE)
	{
	  
	   if($new == FALSE)
	   {
			$query=$this->mongo_db->getWhere($this->collections['customers'], array('first_time_user' => 0));
	   }
	   else
	   {
		    $query=$this->mongo_db->getWhere($this->collections['customers'], array('first_time_user' => 1));
	   }
	   
	   return $query;
	}
	
	public function get_customer($id)
	{
		//$this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->getWhere($this->collections['customers'], array('_id' => new MongoId($id)));
		return $query[0];
	}
	
	public function get_users_list($company_name)
	{
		//$this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->getWhere($this->collections['users'], array('company' => $company_name));
		return $query;
	}
	
	public function get_user($email)
	{
		//$this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->getWhere($this->collections['users'], array('email' => $email));
		return $query[0];
	}
	
	public function count_app_doc_user($email,$company_name)
	{
		//$this->mongo_db->select(array(),array('_id'));
		
		$email_with_hash = str_replace("@", "#", $email);
	
		$this->mongo_db->switchDatabase($this->common_db['mongo_hostbase'].$company_name);
		
		$total_apps = 0;
		$device_apps = $this->mongo_db->select(array("app_id"))->get($email_with_hash."_apps");
		$web_apps = $this->mongo_db->select(array("app_id"))->get($email_with_hash."_web_apps");
		$total_apps = count($device_apps)+count($web_apps);
		
		$all_apps = array();
		foreach ($device_apps as $app){
			array_push($all_apps, $app["app_id"]);
		}
		foreach ($web_apps as $app){
			array_push($all_apps, $app["app_id"]);
		}
		
		$docs_count = 0;
		foreach ($all_apps as $app){
			$count = $this->mongo_db->where(array("doc_data.user_name" => $email_with_hash))->count($app."_shadow");
			$docs_count = $docs_count + intval($count);
		}
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		$query['app'] = $total_apps;
		$query['docs']= $docs_count;
	
		
	
		return $query;
	}
	
	public function get_billing_plan($email)
	{
		$get_billing_plan = $this->mongo_db->where(array("email" => $email))->get($this->collections['user_billing']);
		return $get_billing_plan;
	}
	
	public function get_user_charges($email)
	{
		$get_user_charges = $this->mongo_db->where(array("email" => $email))->get($this->collections['user_expense']);
		return $get_user_charges;
	}
	
	public function store_monthly_bill($data)
	{
	
		$bill_exists = $this->mongo_db->where(array("email" => $data['email'],"month" => $data['month']))->get($this->collections['monthly_user_bill']);
	
		if($bill_exists){
			$get_bill_plan = $this->mongo_db->where(array("email" => $data['email'],"month" => $data['month']))->set($data)->update($this->collections['monthly_user_bill']);
		}else{
			$get_bill_plan = $this->mongo_db->insert($this->collections['monthly_user_bill'],$data);
		}
		return true;
	}
	
	public function insert_user_billing_plan($post)
	{
		log_message('debug','ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($post,true));
		$email = $post['email'];
		
		$plan_exists = $this->mongo_db->where(array("email" => $email))->get($this->collections['user_billing']);
		
		if($plan_exists){
			$get_billing_plan = $this->mongo_db->where(array("email" => $email))->set($post)->update($this->collections['user_billing']);
		}else{
			$get_billing_plan = $this->mongo_db->insert($this->collections['user_billing'],$post);
		}
		return true;
	}
	
	public function count_app_doc_api($id,$company_name)
	{
		//$this->mongo_db->select(array(),array('_id'));
		
		$this->mongo_db->switchDatabase($this->common_db['mongo_hostbase'].$company_name);
		
		$query['app'] = $this->mongo_db->count($this->collections['records']);
		$docs_count = $this->mongo_db->select(array('total_docs'))->get($this->collections['total_docs']);
		$query['on_wf'] = $this->mongo_db->where(array('status' => 0))->count($this->collections['status']);
		$query['off_wf'] = $this->mongo_db->where(array('status' => 1))->count($this->collections['status']);
		
		$dbStats = $this->mongo_db->command(array('dbStats'=>1));
		
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		$query['api'] = $this->mongo_db->where(array('customer' => $id))->count($this->collections['api_details']);
		$query['users'] = ($this->mongo_db->where(array('company' => $company_name))->count($this->collections['users']))+1;
		$query['doc'] = $docs_count[0]['total_docs'];
		
		$bytes = intval($dbStats['fileSize']);
		$db_size = ($bytes /(1024*1024*1024)) ;
		$db_size_rnd = strval(round($db_size, 2));
		
		$query['dbsize'] = $db_size_rnd;
		
		return $query;
	}
	
	public  function exists($collectionname,$id)
    {
    	$query = $this->mongo_db->getWhere($collectionname, array('appid' => $id));
    	
		$result = json_decode(json_encode($query), FALSE);

    	if ($result)
    		return TRUE;
    	else
    		return FALSE;
    }
	
	// --------------------------------------------------------------------

	/**
	 * User registration post process
	 *
	 * @author  Selva
	 *
	 * 
	 */
	 
	public function get_device_details_by_device_unique_number($device_unique_no)
	{
	   $query = $this->mongo_db->select(array(),array('_id'))->getWhere($this->collections['devices'], array('device_unique_number' => $device_unique_no));
	   if($query)
	   {
	     return $query[0];
	   }	 
	}
	
	
 
}


