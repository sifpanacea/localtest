 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Device_master_model extends CI_Model 
{
 
    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->library('session');
        $this->collections = $this->config->item('collections', 'ion_auth');
	}
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Declare about crash to support admin inbox
	 *
	 *  
	 * @author Selva 
	 */
	 

     function declare_crash($device_unique_no,$application,$log_details,$crashed_time,$device_firmware,$log_received_time,$service_req_no)
	{
		$data = array(
		'device_unique_number'    => $device_unique_no,
		'crashed_app'             => $application,
		'crash_id'                => $service_req_no,
		'log_details'             => $log_details,
		'crashed_time'            => $crashed_time,
		'device_firmware_details' => $device_firmware,
		'log_received_time'       => $log_received_time,
		'status'                  => 'new');
		
		
		$res = $this->mongo_db->insert($this->collections['support_inbox'],$data);
		
		if($res)
		{ 
		   return TRUE;
		}
		else
		{
		   return FALSE;
		}
		
		
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper: Get user details using device unique number
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_user_by_device_unique_number($device_unique_no)
     {
	    $query  = $this->mongo_db->select(array('email'),array())->where('device_unique_number',$device_unique_no)->get($this->collections['users']);
	    return $query;	
		
     }
	
	
 
 
    
	 
} 
