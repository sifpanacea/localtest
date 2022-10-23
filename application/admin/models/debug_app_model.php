 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Debug_app_model extends CI_Model 
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
	 

     function upload_logs($device_unique_no,$application,$log_received_time,$file_data,$log_id,$feedback)
	{
		$data = array(
		'device_unique_number'    => $device_unique_no,
		'crashed_app'             => $application,
		'log_id'                  => $log_id,
		'log_received_time'       => $log_received_time,
		'log_files'               => $file_data,
		'feedback'                => $feedback);
		   
		$res = $this->mongo_db->insert($this->collections['detailed_crash_logs'],$data);
		
		if($res)
		{ 
		   return TRUE;
		}
		else
		{
		   return FALSE;
		}
		
	}
	
} 