<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * way2sms SMS API wrapper (CodeIgniter Library)
 *
 * way2sms's application programming interface (API) provides the communication link
 * between your application and way2sms’s SMS Gateway.
 *
 * @author   Vikas
 * 
 * 
 * @date     2014-11-20
 */
class Way2sms {
    
    /**
     * initial api construct
     * return null
     */
    public function __construct($config = array())
    {
	log_message('debug','inligbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
        $this->_ci =& get_instance();
        $this->_ci->load->config('way2sms');
        $this->_config['userID'] = $this->_ci->config->item("userID");
        $this->_config['userPWD'] = $this->_ci->config->item("userPWD");
		log_message('debug','enddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.$this->_config['userID'].$this->_config['userPWD']);
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
     * @param string  $text       Text message's content
     * @param array   $phones     Phone numbers array
     * @param boolean $is_unicode Unicode flag
     * @param integer $send_time  Send time in UNIX timestamp
     *
     */
    function send_sms($recerverNO,$message)
	{
	
		$userID = $this->_config['userID'];
		$userPWD = $this->_config['userPWD'];
		
		log_message('debug','way22222222222222222222222222222222smsssssssssssssssssssssssssssssssssssssssss'.$recerverNO.$message.$userID.$userPWD);
		
		 if(strlen($message)>140) // check for message length
			 {echo "Error : Message length exceeds 140 characters" ; exit(); }
		 if (!function_exists('curl_init')) // check for curl library installation
			 {echo "Error : Curl library not installed";  exit(); }
	 
		 $message_urlencode=rawurlencode($message);
		  // message converted into URL encoded form
		 $cookie_file_path = $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/cookie.txt';
		 // Cookie file location in your machine with full read and write permission
		 $reffer="http://site25.way2sms.com/jsp/InstantSMS.jsp";
	 
	//START OF Code for getting sessionid
	log_message('debug','22222222222222222222222222222222222222222222222222222222222222222222222222'.$recerverNO.$message.$userID.$userPWD);
			$url="http://site25.way2sms.com/content/index.html";
			$header_array[0] = "GET /content/index.html HTTP/1.1";
			$header_array[1]= "Host: site25.way2sms.com";
			$header_array[2]= "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1";
			$header_array[3]= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
			$header_array[4]= "Accept-Language: en-us,en;q=0.5";
			$header_array[5]= "Accept-Encoding: gzip,deflate";
			$header_array[6]= "DNT: 1";
			$header_array[7] = "Connection: keep-alive";
			$ch = curl_init();   //initialise the curl variable
			curl_setopt($ch, CURLOPT_URL,$url);
			//set curl URL for crawling
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array); 
			//set the header for http request to URL 
			curl_setopt($ch, CURLOPT_REFERER, $reffer);  
			 //set reffer url means it shows from where the request is originated.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			 //it means after crawling data will return
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
			// store the return cookie data in cookie file 
			$result = curl_exec ($ch); // Execute the curl function 
			curl_close ($ch);
	//END OF Code for getting sessionid
	 
	//START OF Code for automatic login and storing cookies
			$post_data = "username=".$userID."&password=".$userPWD."&button=Login";
			$url = "http://site25.way2sms.com/Login1.action";
			$header_array[0]="POST /Login1.action HTTP/1.1";
			$header_array[1]="Host: site25.way2sms.com";
			$header_array[2]="User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1";
			$header_array[3]="Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
			$header_array[4]="Accept-Language: en-us,en;q=0.5";
			$header_array[5]="Accept-Encoding: gzip, deflate";
			$header_array[6]="DNT: 1";
			$header_array[7]="Connection: keep-alive";
			$header_array[8]="Content-Type: application/x-www-form-urlencoded";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
			curl_setopt($ch,CURLOPT_REFERER,"http://site25.way2sms.com/content/index.html");
			$content = curl_exec( $ch );
			$response = curl_getinfo( $ch );
			curl_close ($ch);
	//END OF Code for automatic login  and storing cookies
	 
	// START OF Code is  getting way2sms unique user ID
			$url = "http://site25.way2sms.com/jsp/InstantSMS.jsp";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$content = curl_exec($ch);
			curl_close ($ch);
			$regex = '/input type="hidden" name="Action" id="Action" value="(.*)"/';
			preg_match($regex,$content,$match);
			log_message('debug','mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm'.print_r($match,true));
			$userID = $match[1];
	// END OF Code for getting way2sms unique user ID
	 
	// START OF Code for sending SMS to Recever
			$post_data = "HiddenAction=instantsms&catnamedis=Birthday&Action=".$userID."&chkall=on&MobNo=".$recerverNO."&textArea=".$message_urlencode;
			$url = "http://site25.way2sms.com/quicksms.action";
			$header_array[0]="POST /quicksms.action HTTP/1.1";
			$header_array[1]="Host: site25.way2sms.com";
			$header_array[2]="User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1";
			$header_array[3]="Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
			$header_array[4]="Accept-Language: en-us,en;q=0.5";
			$header_array[5]="Accept-Encoding: gzip, deflate";
			$header_array[6]="DNT: 1";
			$header_array[7]="Connection: keep-alive";
			$header_array[8]="Content-Type: application/x-www-form-urlencoded";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	   curl_setopt($ch,CURLOPT_REFERER,"Referer: http://site25.way2sms.com/jsp/InstantSMS.jsp");
			$content = curl_exec( $ch );
			$response = curl_getinfo( $ch );
			curl_close ($ch);
	// END OF Code for sending SMS to Recever
	 
	//START OF Code for automatic logout
			$url = "http://site25.way2sms.com/jsp/logout.jsp";
			$header_array[0]="GET /jsp/logout.jsp HTTP/1.1";
			$header_array[1]="Host: site25.way2sms.com";
			$header_array[2]="User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1";
			$header_array[3]="Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
			$header_array[4]="Accept-Language: en-us,en;q=0.5";
			$header_array[5]="Accept-Encoding: gzip, deflate";
			$header_array[6]="DNT: 1";
			$header_array[7]="Connection: keep-alive";
			$cookie_file_path =$_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/cookie.txt';
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
			curl_setopt($ch,CURLOPT_REFERER,"Referer: http://site25.way2sms.com/jsp/InstantSMS.jsp");
			$content = curl_exec( $ch );
			$response = curl_getinfo( $ch );
			curl_close ($ch);
	//END OF Code for automatic logout
	 
	}// end function send_sms
}
/* End of file textmagic.php */
/* Location: ./application/libraries/textmagic.php */