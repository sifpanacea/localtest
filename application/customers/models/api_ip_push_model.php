<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api_ip_push_model extends CI_Model
{
   /**
	 * Holds the name of MongoDB collections
	 *
	 * @var array
	 */
   public $collections = array();
   
     function __construct()
    {
    	// Call the Model constructor
    	parent::__construct();
    
    	// Load MongoDB library,
    	$this->load->library('mongo_db');
		$this->collections = $this->config->item('collections','ion_auth');
    	$this->load->config('ion_auth', TRUE);
    	$this->load->config('mongodb',TRUE);
    	$this->load->library('session');
    	
    }
   
        
    public function push_to_ip($doc)
	{
		$this->mongo_db->switchDatabase(COMMON_DB);
		$this->mongo_db->where(array('api_agent' => "sapio", "status" => "online"));
		$this->mongo_db->select(array('ip'));
		$ip = $this->mongo_db->get("push_to_ip");
		$this->mongo_db->switchDatabase(DNS_DB);
		log_message('debug','ipipipippppppppppppppppppppppppppppppppppppppppp'.print_r($ip,true));
		
		if(is_array($ip)){
		if(isset($ip[0])){
			$data = json_encode($doc["doc_data"]["widget_data"]);
			log_message('debug','curlllllllllllllllllllllllllllllllllllllllllll'.print_r($data,true));
		//initialize and setup the curl handler
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ip[0]['ip']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 
        //execute the request
        $result = curl_exec($ch);
		}
		}
    } 
   
}