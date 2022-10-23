<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * bhashsms.com SMS API wrapper (CodeIgniter Library)
 *
 * bhashsms's application programming interface (API) provides the communication link
 * between your application and bhashsms’s SMS Gateway.
 *
 * @author   Vikas
 * 
 * 
 * @date     2014-11-20
 */
class Bhashsms {
    
    /**
     * initial api construct
     * return null
     */
    public function __construct($config = array())
    {
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
        $this->_ci =& get_instance();
        $this->_ci->load->config('bhashsms');
        $this->_config['user'] = $this->_ci->config->item("user");
        $this->_config['pass'] = $this->_ci->config->item("pass");
		$this->_config['sender'] = $this->_ci->config->item("sender");
		$this->_config['priority'] = $this->_ci->config->item("priority");
		$this->_config['stype'] = $this->_ci->config->item("stype");
    }
	
	/**
     * Initialize preferences
     *
     * @access  public
     * @param   array
     * @return  this
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            $this->_config[$key] = $val;
        }
        return $this;
    }
    
    /**
     * Send text
     *
     * The send command is used to send the SMS message to a mobile phone,
     * or make a scheduled sending.
     *
     * 
     *
     */
    function send_sms($recerverNO,$message)
	{
	
		$message_urlencode=rawurlencode($message);
	
		$curl_data = "?user=".$this->_config['user']."&pass=".$this->_config['pass']."&sender=".$this->_config['sender']."&phone=".$recerverNO."&text=".$message_urlencode."&priority=".$this->_config['priority']."&stype=".$this->_config['stype'];
		
		$url = "http://bhashsms.com/api/sendmsg.php".$curl_data;
log_message('debug','urlllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.$url);
		$request =  $url; 
		$response = file_get_contents($request);

		return "Response: ".$response;  
	 
	}// end function send_sms
}
/* End of file bhashsms.php */
/* Location: ./application/libraries/bhashsms.php */