<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Todo extends CI_Controller
{
    private $_params;
    private $keys;
     
    public function __construct()
    {
    	parent::__construct();
    	$this->ci =& get_instance();
    	$this->ci->load->library('ion_auth');
        $this->ci->load->helper('form');
        $this->ci->load->helper('url');
        $this->ci->load->model('todo_item','todo_item');
    	
    }
	
	// --------------------------------------------------------------------

	/**
	 * Helper :
	 * 
	 * 
	 * @author  Vikas
	 */

	public function index()
	{
		//get the encrypted request
		$enc_request = $_REQUEST['enc_request'];
		
		//get the provided app id
		$app_id = $_REQUEST['app_id'];
		
		$name = $_REQUEST['name'];
		
		$this->keys = $this->todo_item->fetch_keys($name);
		
		//Define our id-key pairs
		$this->applications = array(
			$this->keys['api_key'] => $this->keys['access'], //randomly generated app key
		);
		
		
		//decrypt the request
		$this->_params = json_decode(base64_decode($enc_request),true);
		log_message('debug','3333333333333333333333333333333333333333');
		$username = $this->_params['username'];
		$password = $this->_params['userpass'];
		
	    if ($this->todo_item->login($username, $password, $this->keys['collection']))
		{
			
			//$data = $this->todo_item->fetch_api_docs($username, $this->keys['collection']);
			$data = "Log-in successfull please provide the IP to which documents have to be pushed..";
			$result['data'] = $data;
			$result['success'] = true;
			echo json_encode($result);
			exit();
		}
		
		
		
		$result['error'] = 'Invalid user !';
		$result['success'] = true;

		echo json_encode($result);
		exit();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *   
	 * 
	 * @author  Vikas
	 */

	public function get_pdf()
	{
		log_message('debug','1111111111111111111111111pushhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
	
		if ( ! $this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		
		log_message('debug','222222222222222222222222pushhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
		
		//get the encrypted request
		$enc_request = $_REQUEST['enc_request'];
	
		//get the provided app id
		$app_id = $_REQUEST['app_id'];
	
		$name = $_REQUEST['name'];
	
		$this->keys = $this->todo_item->fetch_keys($name);
	
		
	
		//Define our id-key pairs
		$this->applications = array(
				$this->keys['api_key'] => $this->keys['access'], //randomly generated app key
		);
	
		//decrypt the request
		$this->_params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB)),true);
	
		$username = $this->_params['username'];
		$password = $this->_params['userpass'];
		$trans_id = $this->_params['trans_id'];
		
	
		if ($this->todo_item->login($username, $password, $this->keys['collection']))
		{
			
			$data = $this->todo_item->fetch_api_pdf($username, $trans_id, $this->keys['collection']);
			$result['pdf'] = $data[0];
			$result['success'] = true;
			echo json_encode($result);
			exit();
		}
	
	    $result['error'] = 'Invalid user !';
		$result['success'] = true;
			
		echo json_encode($result);
		exit();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * 
	 * @author  Vikas
	 */

	public function submit()
	{
	
		if ( ! $this->ion_auth->logged_in())
		{
			redirect(URC.'auth/login');
		}
		
		//get the encrypted request
		$enc_request = $_REQUEST['enc_request'];
	
		//get the provided app id
		$app_id = $_REQUEST['app_id'];
	
		$name = $_REQUEST['name'];
	
		$this->keys = $this->todo_item->fetch_keys($name);
	
		//Define our id-key pairs
		$this->applications = array(
				$this->keys['api_key'] => $this->keys['access'], //randomly generated app key
		);
	
		//decrypt the request
		$this->_params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB)),true);
	
		
		$ins_data = $this->_params['data'];
		$username = $this->_params['username'];
		$password = $this->_params['userpass'];
		$trans_id = $ins_data['trans_id'];
		
	
		if ($this->todo_item->login($username, $password, $this->keys['collection']))
		{
			$data = $this->todo_item->update_api_data($username, $trans_id, $this->keys['collection'], $ins_data);
	
			$result['success'] = $data;
			echo json_encode($result);
			exit();
		}
	
	    $result['error'] = 'In valide user !';
		$result['success'] = true;

		echo json_encode($result);
		exit();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * 
	 * @author  Vikas
	 */

    function api_login()
   {
	    $username = $this->_params['username'];
		$password = $this->_params['userpass'];
		
		//innnnnnnnnnnnnnnnnnnnnnnnlllllllllllllllllllllllllllllllllllll
		
		if ($this->todo_item->login($username, $password, $this->keys['collection']))
		{
			$result['data'] = 'apiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii';
			$result['success'] = true;
			
			echo json_encode($result);
			
		}
	}

    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * 
	 * @author  Vikas
	 */

    public function createAction()
    {
    	if ( ! $this->ion_auth->logged_in())
    	{
    		redirect(URC.'auth/login');
    	}
        
        //create a new todo item
		//create a new todo item
		$todo = new TodoItem();
		$todo->title = $this->_params['title'];
		$todo->description = $this->_params['description'];
		$todo->due_date = $this->_params['due_date'];
		$todo->is_done = 'false';
		 
		//pass the user's username and password to authenticate the user
		$todo->save($this->_params['username'], $this->_params['userpass']);
		 
		//return the todo item in array format
		return $todo->toArray();
    }
    
    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * 
	 * @author  Vikas
	 */

    public function readAction()
    {
        //read all the todo items
    }
    
    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *    
	 * 
	 * @author  Vikas
	 */

    public function updateAction()
    {
        //update a todo item
    }
    
    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * 
	 * @author  Vikas
	 */

    public function deleteAction()
    {
        //delete a todo item
    }
    
    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 *
	 * @param	string	$url   
	 * @param	string	$permanent (optional)    
	 * 
	 * @author  Vikas
	 */

    function api_redirect($url, $permanent = false)
    {
    	if (headers_sent() === false)
    	{
    		header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    	}
    
    	exit();
    }
    
    /**
     * Helper :
     *
     *
     *
     * @author  Vikas
     */
    
    public function push_doc()
    {
    	log_message('debug','1111111111111111111111111pushhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
    	
// 	    if ( ! $this->ion_auth->logged_in())
// 	    {
// 	    	redirect(URC.'auth/login');
// 	    }
    
    	
    	log_message('debug','222222222222222222222222pushhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
    
    	
    	//get the encrypted request
    	$enc_request = $_REQUEST['enc_request'];
    
    	$name = $_REQUEST['name'];
    
    	$this->keys = $this->todo_item->fetch_keys($name);
    
    	//get the provided app id
    	$app_id = $_REQUEST['app_id'];
    
    	//Define our id-key pairs
    	$this->applications = array(
    			$this->keys['api_key'] => $this->keys['access'], //randomly generated app key
    	);
    
    	//decrypt the request
    	$this->_params = json_decode(base64_decode($enc_request),true);
    
    	$ip = $this->_params['ip'];
    
    
    	//if ($this->todo_item->login($username, $password, $this->keys['collection']))
    	{
    			
    		//$data = $this->todo_item->fetch_api_pdf($username, $trans_id, $this->keys['collection']);
    		if($this->todo_item->ip_exists($this->keys['collection'])){
				$this->todo_item->update_ip_in_collection($ip, $this->keys['collection']);
			}else{
				$this->todo_item->put_ip_in_collection($ip, $this->keys['collection']);
			}
    		
    		$result['message'] = "Push server activated for: ".$ip;//$data[0];
    		$result['success'] = true;
    		echo json_encode($result);
    		exit();
    	}
    
    	$result['error'] = 'Invalid user !';
    	$result['success'] = true;
    		
    	echo json_encode($result);
    	exit();
    }
}