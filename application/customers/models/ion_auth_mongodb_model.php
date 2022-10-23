<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (defined('APP_TENANTPATH'))
require(APP_TENANTPATH.'config.php');
/**
 * IonAuth MongoDB Model
 *
 * A rewrite of IonAuth model to use MongoDB as database backend. It
 * requires both CodeIgniter MongoDB Active Record and CodeIgniter MongoDB Session
 * libraries installed.
 *
 * This model class will be loaded in case that it's set to use MongoDB as
 * database backend instead of the original model class, see controller and library
 * files for more info on its internal usage.
 *
 * @package		CodeIgniter
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2012 Sepehr Lajevardi.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		https://github.com/sepehr/ci-mongodb-ionauth-module
 * @version 	Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * IonAuth MongoDB Model
 *
 * A rewrite of IonAuth model class to use MongoDB as database backend.
 *
 * @package 	CodeIgniter
 * @subpackage	Models
 * @category	Authentication
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @link		https://github.com/sepehr/ci-mongodb-ionauth-module
 * @todo		Re-document the code!
 */
class Ion_auth_mongodb_model extends CI_Model {

	/**
	 * Holds the name of MongoDB collections
	 *
	 * @var array
	 */
	public $collections = array();

	/**
	 * activation code
	 *
	 * @var string
	 */
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 */
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 */
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 */
	public $identity;

	/**
	 * Where
	 *
	 * @var array
	 */
	public $_ion_where = array();

	/**
	 * Select
	 *
	 * @var string
	 */
	public $_ion_select = array();

	/**
	 * Limit
	 *
	 * @var string
	 */
	public $_ion_limit = NULL;

	/**
	 * Offset
	 *
	 * @var string
	 */
	public $_ion_offset = NULL;

	/**
	 * Order By
	 *
	 * @var string
	 */
	public $_ion_order_by = NULL;

	/**
	 * Order
	 *
	 * @var string
	 */
	public $_ion_order = NULL;

	/**
	 * Hooks
	 *
	 * @var object
	 */
	protected $_ion_hooks;

	/**
	 * Response
	 *
	 * @var string
	 */
	protected $response = NULL;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 */
	protected $messages;

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

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 */
	public $_cache_user_in_group = array();

	/**
	 * caching of groups
	 *
	 * @var array
	 */
	protected $_cache_groups = array();
	
	

	// ------------------------------------------------------------------------

	/**
	 * IonAuth MongoDB Model Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mongo_db');
		$this->load->config('ion_auth', TRUE);
		$this->load->config('mongodb',TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
		

		// Initialize MongoDB collection names
		$this->collections = $this->config->item('collections', 'ion_auth');

		$this->switchs = $this->config->item('switchs', 'ion_auth');
		// Initialize general config directives
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length     = $this->config->item('salt_length', 'ion_auth');

		// Initialize hash method directives (Bcrypt)
		$this->hash_method    = $this->config->item('hash_method', 'ion_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
		$this->random_rounds  = $this->config->item('random_rounds', 'ion_auth');
		$this->min_rounds     = $this->config->item('min_rounds', 'ion_auth');
		$this->max_rounds     = $this->config->item('max_rounds', 'ion_auth');

		 
		$this->common_db = $this->config->item('default');
		log_message('debug','_config value in ion_auth_mongodb_modelllllllllllllllllllllllllllllllllllllllllll'.print_r($this->common_db['common_db'] ,true));

		// Initialize messages and errors directives
		$this->messages                = array();
		$this->errors                  = array();
		$this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
		$this->message_end_delimiter   = $this->config->item('message_end_delimiter', 'ion_auth');
		$this->error_start_delimiter   = $this->config->item('error_start_delimiter', 'ion_auth');
		$this->error_end_delimiter     = $this->config->item('error_end_delimiter', 'ion_auth');

		// Initialize IonAuth hooks object
		$this->_ion_hooks = new stdClass;

		// Load the Bcrypt class if needed
		if ($this->hash_method == 'bcrypt') {
			if ($this->random_rounds)
			{
				$rand   = rand($this->min_rounds,$this->max_rounds);
				$rounds = array('rounds' => $rand);
			}
			else
			{
				$rounds = array('rounds' => $this->default_rounds);
			}
			$this->load->library('bcrypt',$rounds);
		}

		$this->trigger_events('model_constructor');
	}

	// ------------------------------------------------------------------------

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

	// ------------------------------------------------------------------------

	/**
	 * Takes a password and validates it against an entry in the collection.
	 */
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['collection_for_authentication']);
		$hash_password_db = (object) $document[0];

		if (count($document) !== 1)
		{
			return FALSE;
		}

		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return ($db_password == $hash_password_db->password);
	}

	// ------------------------------------------------------------------------

	 public function hash_password_user_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['users']);
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($document,true));
		$hash_password_db = (object) $document[0];

		if (count($document) !== 1)
		{
			return FALSE;
		}

		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return ($db_password == $hash_password_db->password);
	}
	
	public function hash_password_sub_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['sub_admins']);
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($document,true));
		$hash_password_db = (object) $document[0];
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return ($db_password == $hash_password_db->password);
	}
	
  // ---------------------------------------------------------------------------------------------------
  
	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 */
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 */
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}

	// ------------------------------------------------------------------------

	/**
	 * Validates and removes activation code.
	 */
	public function activate($id, $code = FALSE)
	{
		$this->trigger_events('pre_activate');

		// If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
            $this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
				->select($this->identity_column)
				->where('activation_code', $code)
				->limit(1)
				->get($this->collections['users']);
			$result = (object) $docs[0];

			// If unsuccessfull
			if (count($docs) !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('docs');
				return FALSE;
			}

			$identity = $result->{$this->identity_column};
             
			$this->trigger_events('extra_where');
			$updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array('activation_code' => NULL, 'active' => 1))
				->update($this->collections['users']);
				
				$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->trigger_events('extra_where');
            $this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
				->where('_id', new MongoId($id))
				->set(array('activation_code' => NULL, 'active' => 1))
				->update($this->collections['users']);
		}

        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}

		return $updated;
	}
	
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validates and removes activation code.
	 */
	public function activate_sub_admin($id, $code = FALSE)
	{
		$this->trigger_events('pre_activate');
	
		// If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
			->select($this->identity_column)
			->where('activation_code', $code)
			->limit(1)
			->get($this->collections['sub_admins']);
			$result = (object) $docs[0];
	
			// If unsuccessfull
			if (count($docs) !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('docs');
				return FALSE;
			}
	
			$identity = $result->{$this->identity_column};
			 
			$this->trigger_events('extra_where');
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['sub_admins']);
	
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->trigger_events('extra_where');
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => NULL, 'active' => 1))
			->update($this->collections['sub_admins']);
		}
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}
	
		return $updated;
	}

	// ------------------------------------------------------------------------

	/**
	 * Updates a user document with an activation code.
	 */
	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}

		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		$this->trigger_events('extra_where');
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['users']);

			 $this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->set_message('deactivate_successful');
		}
		else
		{
			$this->set_error('deactivate_unsuccessful');
		}

		return $updated;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Updates a user document with an activation code.
	 */
	public function deactivate_sub_admin($id = NULL)
	{
		$this->trigger_events('deactivate_sub_admin');
	
		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}
	
		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
		->where('_id', new MongoId($id))
		->set(array('activation_code' => $activation_code, 'active' => 0))
		->update($this->collections['sub_admins']);
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->set_message('deactivate_successful');
		}
		else
		{
			$this->set_error('deactivate_unsuccessful');
		}
	
		return $updated;
	}

	// ------------------------------------------------------------------------

	/**
	 * Clears forgotten password code from database.
	 */
	public function clear_forgotten_password_code($code) {
		if (empty($code))
		{
			return FALSE;
		}

		$count = count($this->mongo_db
			->where('forgotten_password_code', $code)
			->get($this->collections['users']));

		if ($count > 0)
		{
			$this->mongo_db
				->where('forgotten_password_code', $code)
				->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
				->update($this->collections['users']);

			return TRUE;
		}
		return FALSE;
	}

// ------------------------------------------------------------------------

	/**
	 * Resets password.
	 *
	 * @return bool
	 */
	public function reset_password($identity, $new) {
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check_customer($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$this->trigger_events('extra_where');
        
		$docs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['customers']);

		// Unsuccessfull password change
		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		// Generate new password hash
		$result = (object) $docs[0];
		$new    = $this->hash_password($new, $result->salt);

		$this->trigger_events('extra_where');

		// Store the new password and reset the remember code so all remembered instances have
		// to re-login. Also clear the forgotten password code.
		$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'password'                => $new,
				'remember_code'           => NULL,
				'forgotten_password_code' => NULL,
				'forgotten_password_time' => NULL,
			))
			->update($this->collections['customers']);

		if ($updated)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $updated;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Resets password.
	 *
	 * @return bool
	 */
	public function reset_user_password($identity, $new) {
	
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$this->trigger_events('extra_where');

		$docs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['users']);

		// Unsuccessfull password change
		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		// Generate new password hash
		$result = (object) $docs[0];
		$new    = $this->hash_password($new, $result->salt);

		$this->trigger_events('extra_where');

		// Store the new password and reset the remember code so all remembered instances have
		// to re-login. Also clear the forgotten password code.
		$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'password'                => $new,
				'remember_code'           => NULL,
				'forgotten_password_code' => NULL,
				'forgotten_password_time' => NULL,
			))
			->update($this->collections['users']);

		if ($updated)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

$this->mongo_db->switchDatabase($this->common_db['dsn']);


		return $updated;
	}

		// ------------------------------------------------------------------------

	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	public function change_password($identity, $old, $new)
	{
	    log_message('debug','password change parttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
      log_message('debug','password change parttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($identity,true));
		$docs = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['collection_for_authentication']);

		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			$this->trigger_events('extra_where');
            $this->mongo_db->switchDatabase($this->common_db['common_db']);
			// Store the new password and reset the remember code so all remembered instances have to re-login
			$updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['collection_for_authentication']);

			if ($updated)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}
            $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}
	
	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	public function change_password_sub_admin($identity, $old, $new)
	{
		log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccdfuyhgfuysd');
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		log_message('debug','pasgyugfydufshvchsvhbvhjhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh'.print_r($identity,true));
		$docs = $this->mongo_db
		->select(array('_id', 'password','salt'))
		->where($this->identity_column, $identity)
		->limit(1)
		->get($this->collections['sub_admins']);
	
		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
	
		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_sub_admin_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);
	
		if ($old === TRUE)
		{
			$this->trigger_events('extra_where');
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			// Store the new password and reset the remember code so all remembered instances have to re-login
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('password' => $new, 'remember_code' => NULL))
			->update($this->collections['sub_admins']);
	
			if ($updated)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}
	
		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

// ------------------------------------------------------------------------------------------------------------

	public function change_user_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');
      log_message('debug','password change parttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($identity,true));
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$docs = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['users']);

		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result      = (object) $docs[0];
		log_message('debug','RRRRRRRRREEEEEEEEEEESSSSSSSSSSSSSSSSSSSSSSUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUULLLLLLLLLLLLLLLLLLLLLLLLTTTTTTTTTTTTT'.print_r($result,true));
		$db_password = $result->password;
		$old         = $this->hash_password_user_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);
        log_message('debug','RRRRRRRRREEEEEEEEEEESSSSSSSSSSSSSSSSSSSSSSUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUULLLLLLLLLLLLLLLLLLLLLLLLTTTTTTTTTTTTT olddddd'.print_r($old,true));
		if ($old === TRUE)
		{
			$this->trigger_events('extra_where');
            $this->mongo_db->switchDatabase($this->common_db['common_db']);
			// Store the new password and reset the remember code so all remembered instances have to re-login
			$updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['users']);

			if ($updated)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
			    log_message('debug','else partttttttttttttttttttttt userrrrrrrrrrrrrr changeeeeeeeeeeeeeee passwordddddddddddddd');
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}
            $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $updated;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper : Change Password Model ( For SIFNOTE Users - HS,Doctors )
	 *
	 * @return bool
	 */

	public function change_sifnote_user_password_model($identity, $old, $new)
	{
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);

	    // PANACEA HS
		$panacea_hs_doc = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['panacea_health_supervisors']);

		if($panacea_hs_doc)
		{
			$result      = (object) $panacea_hs_doc[0];
			$db_password = $result->password;
			$old         = $this->hash_password_panacea_hs_db($result->_id, $old);
			$new         = $this->hash_password($new, $result->salt);

			if ($old === TRUE)
		    {
				$this->trigger_events('extra_where');
	            $this->mongo_db->switchDatabase($this->common_db['common_db']);
				// Store the new password and reset the remember code so all remembered instances have to re-login
				$updated = $this->mongo_db
					->where($this->identity_column, $identity)
					->set(array('password' => $new, 'remember_code' => NULL))
					->update($this->collections['panacea_health_supervisors']);

				if ($updated)
				{
					$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
					$this->set_message('password_change_successful');
				}
				else
				{
					$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
					$this->set_error('password_change_unsuccessful');
				}
	            $this->mongo_db->switchDatabase($this->common_db['dsn']);
				return $updated;
		    }
		}
		else
		{
			// PANACEA Doctor
		  $panacea_dr_doc = $this->mongo_db
			->select(array('_id', 'password','salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['panacea_doctors']);

			if($panacea_dr_doc)
			{
				$result      = (object) $panacea_dr_doc[0];
				$db_password = $result->password;
				$old         = $this->hash_password_panacea_dr_db($result->_id, $old);
				$new         = $this->hash_password($new, $result->salt);

				if ($old === TRUE)
			    {
					$this->trigger_events('extra_where');
		            $this->mongo_db->switchDatabase($this->common_db['common_db']);
					// Store the new password and reset the remember code so all remembered instances have to re-login
					$updated = $this->mongo_db
						->where($this->identity_column, $identity)
						->set(array('password' => $new, 'remember_code' => NULL))
						->update($this->collections['panacea_doctors']);

					if ($updated)
					{
						$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
						$this->set_message('password_change_successful');
					}
					else
					{
						$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
						$this->set_error('password_change_unsuccessful');
					}
		            $this->mongo_db->switchDatabase($this->common_db['dsn']);
					return $updated;
			    }

			}
			else
			{
				// TMREIS HS
		 	 	$tmreis_hs_doc = $this->mongo_db
					->select(array('_id', 'password','salt'))
					->where($this->identity_column, $identity)
					->limit(1)
					->get($this->collections['tmreis_health_supervisors']);

				if($tmreis_hs_doc)
				{
					$result      = (object) $tmreis_hs_doc[0];
					$db_password = $result->password;
					$old         = $this->hash_password_tmreis_hs_db($result->_id, $old);
					$new         = $this->hash_password($new, $result->salt);

					if ($old === TRUE)
				    {
						$this->trigger_events('extra_where');
			            $this->mongo_db->switchDatabase($this->common_db['common_db']);
						// Store the new password and reset the remember code so all remembered instances have to re-login
						$updated = $this->mongo_db
							->where($this->identity_column, $identity)
							->set(array('password' => $new, 'remember_code' => NULL))
							->update($this->collections['tmreis_health_supervisors']);

						if ($updated)
						{
							$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
							$this->set_message('password_change_successful');
						}
						else
						{
							$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
							$this->set_error('password_change_unsuccessful');
						}
			            $this->mongo_db->switchDatabase($this->common_db['dsn']);
						return $updated;
				    }
				}
				else
				{
					// TMREIS Doctor
		 	 		$tmreis_dr_doc = $this->mongo_db
						->select(array('_id', 'password','salt'))
						->where($this->identity_column, $identity)
						->limit(1)
						->get($this->collections['tmreis_doctors']);

					if($tmreis_dr_doc)
					{
						$result      = (object) $tmreis_dr_doc[0];
						$db_password = $result->password;
						$old         = $this->hash_password_tmreis_dr_db($result->_id, $old);
						$new         = $this->hash_password($new, $result->salt);

						if ($old === TRUE)
					    {
							$this->trigger_events('extra_where');
				            $this->mongo_db->switchDatabase($this->common_db['common_db']);
							// Store the new password and reset the remember code so all remembered instances have to re-login
							$updated = $this->mongo_db
								->where($this->identity_column, $identity)
								->set(array('password' => $new, 'remember_code' => NULL))
								->update($this->collections['tmreis_doctors']);

							if ($updated)
							{
								$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
								$this->set_message('password_change_successful');
							}
							else
							{
								$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
								$this->set_error('password_change_unsuccessful');
							}
				            $this->mongo_db->switchDatabase($this->common_db['dsn']);
							return $updated;
					    }
					}
					else
					{
						// TTWREIS HS
			 	 		$ttwreis_hs_doc = $this->mongo_db
							->select(array('_id', 'password','salt'))
							->where($this->identity_column, $identity)
							->limit(1)
							->get($this->collections['ttwreis_health_supervisors']);

						if($ttwreis_hs_doc)
						{
							$result      = (object) $ttwreis_hs_doc[0];
							$db_password = $result->password;
							$old         = $this->hash_password_ttwreis_hs_db($result->_id, $old);
							$new         = $this->hash_password($new, $result->salt);

							if ($old === TRUE)
						    {
								$this->trigger_events('extra_where');
					            $this->mongo_db->switchDatabase($this->common_db['common_db']);
								// Store the new password and reset the remember code so all remembered instances have to re-login
								$updated = $this->mongo_db
									->where($this->identity_column, $identity)
									->set(array('password' => $new, 'remember_code' => NULL))
									->update($this->collections['ttwreis_health_supervisors']);

								if ($updated)
								{
									$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
									$this->set_message('password_change_successful');
								}
								else
								{
									$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
									$this->set_error('password_change_unsuccessful');
								}
					            $this->mongo_db->switchDatabase($this->common_db['dsn']);
								return $updated;
						    }
						}
						else
						{
							// TTWREIS Doctor
				 	 		$ttwreis_dr_doc = $this->mongo_db
								->select(array('_id', 'password','salt'))
								->where($this->identity_column, $identity)
								->limit(1)
								->get($this->collections['ttwreis_doctors']);

							if($ttwreis_dr_doc)
							{
								$result      = (object) $ttwreis_dr_doc[0];
								$db_password = $result->password;
								$old         = $this->hash_password_ttwreis_dr_db($result->_id, $old);
								$new         = $this->hash_password($new, $result->salt);

								if ($old === TRUE)
							    {
									$this->trigger_events('extra_where');
						            $this->mongo_db->switchDatabase($this->common_db['common_db']);
									// Store the new password and reset the remember code so all remembered instances have to re-login
									$updated = $this->mongo_db
										->where($this->identity_column, $identity)
										->set(array('password' => $new, 'remember_code' => NULL))
										->update($this->collections['ttwreis_doctors']);

									if ($updated)
									{
										$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
										$this->set_message('password_change_successful');
									}
									else
									{
										$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
										$this->set_error('password_change_unsuccessful');
									}
						            $this->mongo_db->switchDatabase($this->common_db['dsn']);
									return $updated;
							    }
							}
							else
							{
								
							}
							
						}
					}
				}
			}
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_panacea_hs_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
	    $this->mongo_db->switchDatabase($this->common_db['common_db']);

		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_health_supervisors']);
		$hash_password_db = (object) $document[0];

		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_panacea_dr_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_doctors']);
		$hash_password_db = (object) $document[0];

		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_tmreis_hs_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['tmreis_health_supervisors']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		$hash_password_db = (object) $document[0];
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_tmreis_dr_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['tmreis_doctors']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		$hash_password_db = (object) $document[0];
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_ttwreis_hs_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ttwreis_health_supervisors']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		$hash_password_db = (object) $document[0];
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_ttwreis_dr_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');

		$this->mongo_db->switchDatabase($this->common_db['common_db']);
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ttwreis_doctors']);

		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		$hash_password_db = (object) $document[0];
	
		if (count($document) !== 1)
		{
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $hash_password_db->password))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	
		return ($db_password == $hash_password_db->password);
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks username.
	 *
	 * @return bool
	 */
	public function username_check($username = '')
	{
		$this->trigger_events('username_check');

		if (empty($username))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$username = new MongoRegex('/^'.$username.'$/i');
		return count($this->mongo_db
			->where('username', $username)
			->get($this->collections['users'])) > 0;
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
	 
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks email.
	 *
	 * @return bool
	 */
	public function email_check($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
        	//$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$email = new MongoRegex('/^'.$email.'$/i');
		return count($this->mongo_db
			->where('email', $email)
			->get($this->collections['users'])) > 0;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Checks email.
	 *
	 * @return bool
	 */
	public function device_unique_number_check($device_unique_number = '')
	{
		$this->trigger_events('email_check');

		if (empty($device_unique_number))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
        	//$this->mongo_db->switchDatabase($this->common_db['common_db']);
		
		return count($this->mongo_db
			->where('device_unique_number', $device_unique_number)
			->get($this->collections['users'])) > 0;
	}


	// ------------------------------------------------------------------------

	/**
	 * Checks identity field.
	 *
	 * @return bool
	 */
	protected function identity_check($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}
        //$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	return count($this->mongo_db
			->where($this->identity_column, $identity)
			->get($this->collections['users'])) > 0;
	}

	 // ------------------------------------------------------------------------

	/**
	 * Checks identity field.
	 *
	 * @return bool
	 */
	protected function identity_check_customer($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$count = count($this->mongo_db
			->where($this->identity_column, $identity)
			->get($this->collections['customers']));
			 $this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $count >0;
    	/*return count($this->mongo_db
			->where($this->identity_column, $identity)
			->get($this->collections['customers'])) > 0;*/
	}

	// ------------------------------------------------------------------------

	/**
	 * Inserts a forgotten password key.
	 *
	 * @return bool
	 */
	public function forgotten_password($identity)
	{
		if (empty($identity))
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			return FALSE;
		}

		$this->forgotten_password_code = $this->hash_code(microtime() . $identity);
		$this->trigger_events('extra_where');
         $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'forgotten_password_code' => $this->forgotten_password_code,
				'forgotten_password_time' => time()
			))
			->update($this->collections['users']);

		if ($updated)
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
		}
		else
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
		}

		return $updated;
	}

	// ------------------------------------------------------------------------

	/**
	 * Completes a forgotten password procedure.
	 *
	 * @return string
	 */
	public function forgotten_password_complete($code, $salt=FALSE)
	{
		$this->trigger_events('pre_forgotten_password_complete');

		if (empty($code))
		{
			$this->trigger_events(array(
				'post_forgotten_password_complete',
				'post_forgotten_password_complete_unsuccessful',
			));
			return FALSE;
		}

		// Get user document for this forgotten password code
		$profile = $this->where('forgotten_password_code', $code)->users()->document();

		// If there's any user with this code:
		if ($profile)
		{
			// Check if the forgot password request is not expired yet
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
			{
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');

				// If the forgot password request is expired, abort the operation!
				if (time() - $profile->forgotten_password_time > $expiration)
				{
					$this->set_error('forgot_password_expired');
					$this->trigger_events(array(
						'post_forgotten_password_complete',
						'post_forgotten_password_complete_unsuccessful'
					));
					return FALSE;
				}
			}

			// Update the user document with the new password
			$password = $this->salt();
            	$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$this->mongo_db
				->where('forgotten_password_code', $code)
				->set(array(
					'password'                => $this->hash_password($password, $salt),
					'forgotten_password_code' => NULL,
					'active'                  => 1,
				))
				->update($this->collections['users']);

			// Trigger appropriate hooks
			$this->trigger_events(array(
				'post_forgotten_password_complete',
				'post_forgotten_password_complete_successful'
			));

			// And return the password
			return $password;
		}

		// But if there were no users with that forgotten password code:
		$this->trigger_events(array(
			'post_forgotten_password_complete',
			'post_forgotten_password_complete_unsuccessful'
		));
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Inserts a user document into users collection.
	 *
	 * @return bool
	 */
	public function register($username,$password,$email,$device_unique_number,$additional_data = array(),$groups = array())
	{
		$this->trigger_events('pre_register');
		$manual_activation = $this->config->item('manual_activation', 'ion_auth');
log_message('debug','ddddddddddeeeeeeeeeeeeeeeeffffffffffffffff'.print_r($groups,true));
		// Check if email already exists
		if ($this->identity_column == 'email' && $this->email_check($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
		// Check if username already exists
		elseif ($this->identity_column == 'username' && $this->username_check($username))
		{
			$this->set_error('account_creation_duplicate_username');
			return FALSE;
		}
		// Check if device unique number already exists
		/* if ($this->device_unique_number_check($device_unique_number))
		{
			$this->set_error('account_creation_duplicate_device_unique_number');
			return FALSE;
		} */

		// If username is taken, use username1 or username2, etc.
		// TODO: Drop this shit!
		if ($this->identity_column != 'username')
		{
			$original_username = $username;
			for($i = 0; $this->username_check($username); $i++)
			{
				if($i > 0)
				{
					$username = $original_username . $i;
				}
			}
		}

		// IP address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);

		// New user document
		$data = array(
			'username'   => $username,
			'password'   => $password,
			'email'      => $email,
			'device_unique_number' => $device_unique_number,
			'ip_address' => $ip_address,
			'registered_on' => date("Y-m-d"),
			'last_login' => date("Y-m-d H:i:s"),
			'active'     => ($manual_activation === FALSE ? 1 : 0),
			'groups'     => array(),
			'status'     => 'offline'
			
		);

		// Store salt in document?
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}

		// Add groups to the user document, We don't use
		// add_to_group() API function here regarding lesser queries.
		if (!empty($groups))
		{
			foreach ($groups as $group)
			{
				$data['groups'][] = $group;
			}
		}
		// Add to default group if not already set,
		// get the ID of the default group first:
		$default_group = $this->where('name', $this->config->item('default_group', 'ion_auth'))->group()->document();
		
		if ((isset($default_group->_id) && !isset($groups)) || (empty($groups) && !in_array($default_group->id, $groups)))
		{
			$data['groups'][] = $default_group->_id;
		}

		// Filter out any data passed that doesn't have a matching column in the
		// user document and merge the set user data with the passed additional data
		$data = array_merge($this->_filter_data($this->collections['users'], $additional_data), $data);

		$this->trigger_events('extra_set');

        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['users'], $data);

		$this->trigger_events('post_register');
         $this->mongo_db->switchDatabase($this->common_db['dsn']);
		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Inserts a sub admin document into users collection.
	 *
	 * @return bool
	 */
	public function register_sub_admin($username, $password, $email, $additional_data = array(), $groups = array(),$cusdetail)
	{
		$this->trigger_events('pre_register');
		$manual_activation = $this->config->item('manual_activation', 'ion_auth');
	
		// Check if email already exists
		if ($this->identity_column == 'email' && $this->email_check($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
		// Check if username already exists
		elseif ($this->identity_column == 'username' && $this->username_check($username))
		{
			$this->set_error('account_creation_duplicate_username');
			return FALSE;
		}
	
		// If username is taken, use username1 or username2, etc.
		// TODO: Drop this shit!
		if ($this->identity_column != 'username')
		{
			$original_username = $username;
			for($i = 0; $this->username_check($username); $i++)
			{
			if($i > 0)
			{
			$username = $original_username . $i;
			}
			}
			}
	
			// IP address
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			$salt       = $this->store_salt ? $this->salt() : FALSE;
			$password   = $this->hash_password($password, $salt);
	
			// New user document
			$data = array(
			'username'   			=> $username,
			'password'   			=> $password,
			'email'      			=> $email,
			'ip_address' 			=> $ip_address,
			'created_on' 			=> date("Y-m-d"),
			'last_login' 			=> date("Y-m-d H:i:s"),
			'active'     			=> ($manual_activation === FALSE ? 1 : 0),
			'groups'     			=> $groups,
			'plan_expiry'			=> $cusdetail['plan_expiry'],
			'display_company_name'	=> $cusdetail['display_company_name'],
			'plan'					=> $cusdetail['plan'],
			'company_address'		=> $cusdetail['company_address'],
			'company_address'		=> $cusdetail['company_address'],
			'registered_on'			=> date('Y-m-d'),
			'company_website'		=> $cusdetail['company_website'],
					
				);
	
				// Store salt in document?
				if ($this->store_salt)
				{
				$data['salt'] = $salt;
				}
	
				// Filter out any data passed that doesn't have a matching column in the
				// user document and merge the set user data with the passed additional data
				$data = array_merge($this->_filter_data($this->collections['users'], $additional_data), $data);
	
				$this->trigger_events('extra_set');
	
				$this->mongo_db->switchDatabase($this->common_db['common_db']);
				
				// Insert new document and store the _id value
				$id = $this->mongo_db->insert($this->collections['sub_admins'], $data);
	
				$this->trigger_events('post_register');
				$this->mongo_db->switchDatabase($this->common_db['dsn']);
				// Return new document _id or FALSE on failure
				return isset($id) ? $id : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks credentials and logs the passed user in if possible.
	 *
	 * @return bool
	 */
	public function login($identity, $password, $remember = FALSE)
	{
		$this->trigger_events('pre_login');

		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
		log_message('debug','identity in login timeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($identity,true));
        $current_date = date("Y-m-d");
		$this->trigger_events('extra_where');
		$this->com = trim($this->common_db['dsn']);
		$parts = parse_url($this->com);
		$companyname = str_replace('/', '', $parts['path']);
		$company = str_replace('_saas', '', $companyname);
		log_message('debug','company nameeeeeeeeeeeeeeeeeee in login  timeeeeeeeeeeeeee'.print_r($company,true));
		log_message('debug','identity in login timeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($this->common_db['common_db'],true));
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		
		/*$this->mongo_db->select(array('$this->identity_column', '_id', 'username', 'email', 'password', 'active','plan_expiry','last_login'));
		$document1 = $this->mongo_db->Where(array('company_name'=>$company,'email'=>$identity))->limit(1)->get($this->collections['collection_for_authentication']);
		log_message('debug','query11111111111111111in login  timeeeeeeeeeeeeee'.print_r($document1,true)); */
		$document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active','plan_expiry','last_login'))
			// MongoDB is vulnerable to SQL Injection like attacks (in PHP at least), in MongoDB
			// PHP driver we use objects to make queries and as we know PHP allows us to submit
			// objects via GET, POST, etc. and so getting user input like password[$ne]=1 is possible
			// which translates to: array('$ne' => 1) in for example find queries. So we make sure that
			// what we put into a collection is strictly string typed. We also watch what we put in our
			// stomach, goveg! :))
			//->where($this->identity_column, (string) $identity)
		    ->Where(array('company_name'=>$company,'email'=>$identity))
			->limit(1)
			->get($this->collections['collection_for_authentication']); 
			
			log_message('debug','document 11111111111111111111111111111111111111111111111111111'.print_r($document,true));

		// If customer document found
		if (count($document) === 1)
		{
			$user = (object) $document[0];
			$password = $this->hash_password_db($user->_id, $password);
			
			if ($password === TRUE)
			{
				// Not yet activated?
                if ($user->active == 0)
                {
                     $this->trigger_events('post_login_unsuccessful');
                     $this->set_error('login_unsuccessful_not_active');
                     return FALSE;
                }
                
                if($user->plan_expiry == $current_date)
				{
				   $this->trigger_events('post_login_unsuccessful');
                   $this->set_error('login_unsuccessful_plan_expired');
                   return FALSE;
				}
                // Set user session data
                $session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login
		  );
                $this->session->set_userdata($session_data);
				$this->session->set_userdata("customer",$session_data);

                // Clean login attempts, also update last login time
                $this->update_last_login($user->_id);
				$this->clear_login_attempts($identity);

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_user($user->_id);
                }
               $this->mongo_db->switchDatabase($this->common_db['dsn']);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');
                return TRUE;
			}
		}
		else
		{
		   $this->mongo_db->switchDatabase($this->common_db['dsn']);
		   $document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active','company','last_login'))
			// MongoDB is vulnerable to SQL Injection like attacks (in PHP at least), in MongoDB
			// PHP driver we use objects to make queries and as we know PHP allows us to submit
			// objects via GET, POST, etc. and so getting user input like password[$ne]=1 is possible
			// which translates to: array('$ne' => 1) in for example find queries. So we make sure that
			// what we put into a collection is strictly string typed. We also watch what we put in our
			// stomach, goveg! :))
			//->where($this->identity_column, (string) $identity)
		    ->Where(array('email'=>$identity))
			->limit(1)
			->get($this->collections['users']); 
			
			if (count($document) === 1)
		{
		    log_message('debug','User dashboard documentttttttttttttttttttttttttttttttttttttttttttttttttt()()()&&&&&'.print_r($document,true));
			$user = (object) $document[0];
			log_message('debug','User dashboard forrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($user,true));
			$password = $this->hash_password_user_db($user->_id, $password);

			if ($password === TRUE)
			{
				// Not yet activated?
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }
				
				$session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company
		  );
                $this->session->set_userdata($session_data);
				$this->session->set_userdata("user",$session_data);
				
				  if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_user($user->_id);
                }
              // $this->mongo_db->switchDatabase($this->common_db['dsn']);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');
                return TRUE;
		   
		
		}
		}
		}

		// The user document was not found
		$this->hash_password($password);
		$this->increase_login_attempts($identity);
		$this->trigger_events('post_login_unsuccessful');
		$this->set_error('login_unsuccessful');
        return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks whether the maximum login attempts limit is reached.
	 *
	 * @return bool
	 */
	public function is_max_login_attempts_exceeded($identity)
	{
		// Do we set to track login attempts?
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$max_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');

			if ($max_attempts > 0)
			{
				$attempts = $this->get_attempts_num($identity);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the number of attempts to login occured from given IP or identity
	 *
	 * @return int
	 */
	function get_attempts_num($identity)
	{
		// Do we set to track login attempts?
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$this->mongo_db->where('ip_address', $this->_prepare_ip($this->input->ip_address()));

			if (strlen($identity) > 0)
			{
				$this->mongo_db->or_where('login', $identity);
			}

			$document = $this->mongo_db->get($this->collections['login_attempts']);
			return count($document);
		}
		return 0;
	}

	// ------------------------------------------------------------------------

	/**
	 * Increses login attempts of the passed user
	 */
	public function increase_login_attempts($identity)
	{
		// Do we set to track login attempts?
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			return $this->mongo_db->insert($this->collections['login_attempts'], array(
				'ip_address' => $this->_prepare_ip($this->input->ip_address()),
				'login'      => (string) $identity,
				'time'       => time(),
			));
		}
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Clears login attempts of the passed identity.
	 */
	public function clear_login_attempts($identity, $expire_period = 86400)
	{
		// Do we set to track login attempts?
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			return $this->mongo_db
				->where(array(
					'login'      => $identity,
					'ip_address' => $this->_prepare_ip($this->input->ip_address())
				))
				// Purge obsolete login attempts
				->or_where(array('time <' => time() - $expire_period))
				->delete($this->collections['login_attempts']);
		}
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Sets query limit parameter.
	 */
	public function limit($limit)
	{
		$this->trigger_events('limit');
		$this->_ion_limit = $limit;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Sets query offset parameter.
	 */
	public function offset($offset)
	{
		$this->trigger_events('offset');
		$this->_ion_offset = $offset;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Sets query where conditions.
	 */
	public function where($where, $value = NULL)
	{
		$this->trigger_events('where');

		if (!is_array($where))
		{
			$where = array($where => $value);
		}

		array_push($this->_ion_where, $where);

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Sets query select portion.
	 */
	public function select($select)
	{
		$this->trigger_events('select');

		$this->_ion_select[] = $select;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Sets query orderby parameter.
	 */
	public function order_by($by, $order='desc')
	{
		$this->trigger_events('order_by');

		$this->_ion_order_by = $by;
		$this->_ion_order    = $order;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns a single document object from an array of results.
	 *
	 * It's our MongoDB equivalent of CodeIgniter row() method.
	 *
	 * @return object
	 */
	public function document()
	{
		$this->trigger_events('document');

		$document = array();
		if (isset($this->response[0]))
		{
			// Clone mongoid of the resulting array, if necessary
			$this->response[0] = $this->_clone_mongoid($this->response[0]);
			// Get the first result as an object
			$document = (object) $this->response[0];
		}
		// Free memory
		unset($this->response);

		return $document;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns a single document object from an array of results.
	 *
	 * It's our MongoDB equivalent of CodeIgniter row_array() method.
	 *
	 * @return array
	 */
	public function document_array()
	{
		$this->trigger_events(array('document', 'document_array'));

		$document = array();
		if (isset($this->response[0]))
		{
			// Clone mongoid of the resulting array, if necessary
			$this->response[0] = $this->_clone_mongoid($this->response[0]);
			// Get the first result as an array
			$document = $this->response[0];
		}
		// Free memory
		unset($this->response);

		return $document;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Alias function for document() to maintain API consistency.
	 *
	 * @return object
	 */
	public function row()
	{
		return $this->document();
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Alias function for document_array() to maintain API consistency.
	 *
	 * @return object
	 */
	public function row_array()
	{
		return $this->document_array();
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns query results as an array of objects.
	 *
	 * This helper, and a few others (result_array, row, row_array) are
	 * implemented to mimic the behavior of their equivalent functions in the
	 * original model, but in MongoDB interface environment.
	 *
	 * TODO: These kinda helpers should exist in MongoDB Active Record Library.
	 */
	public function result()
	{
		$this->trigger_events('result');

		if (! empty($this->response))
		{
			foreach ($this->response as $key => $value)
			{
				// We need to add an arbitrary "id" field to the resulted
				// object to maintain IonAuth compatibility with both
				// mongodb library and the native database drivers with
				// minimum level of code change.
				$this->response[$key] = $this->_clone_mongoid($value);

				// Typecast document results into objects for API consistency
				$this->response[$key] = (object) $this->response[$key];
			}
		}

		$result = $this->response;
		unset($this->response);

		return $result;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns query results as an array of arrays.
	 */
	public function result_array()
	{
		$this->trigger_events(array('result', 'result_array'));

		$result = $this->response;
		unset($this->response);

		// We need to add an arbitrary "id" field to the resulted
		// object to maintain IonAuth compatibility with both
		// mongodb library and the native database drivers with
		// minimum level of code change.
		$result = $this->_clone_mongoid($result);

		return $result;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function users($groups = NULL)
	{
		$this->trigger_events('users');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        // Filter by group if any passed
        if (isset($groups))
        {
        	// Build an array if only one group was passed
        	if (is_numeric($groups))
        	{
        		$groups = array($groups);
        	}

        	if ( ! empty($groups))
        	{
        		$this->mongo_db->where_in('groups', $groups);
	        }
        }

		$this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['users']);
		log_message('debug','USERRRRRRR++++++======>>>>>--------->>>>>>>>---------------------'.print_r($this->response,true));
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function health_supervisors($groups = NULL)
	{
		$this->trigger_events('health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['panacea_health_supervisors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISORS=====$THIS->RESPONSE==>'.print_r($this->response,true));
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function health_supervisor($id = FALSE)
	{
		$this->trigger_events('health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->health_supervisors();
		
		log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISOR=====$THIS==>'.print_r($this,true));

		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function bc_welfare_health_supervisors($groups = NULL)
	{
		$this->trigger_events('bc_welfare_health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['bc_welfare_health_supervisors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISORS=====$THIS->RESPONSE==>'.print_r($this->response,true));
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function bc_welfare_health_supervisor($id = FALSE)
	{
		$this->trigger_events('bc_welfare_health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->bc_welfare_health_supervisors();
		
		log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISOR=====$THIS==>'.print_r($this,true));

		return $this;
	}
	//=====================================================
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function tmreis_health_supervisors($groups = NULL)
	{
		$this->trigger_events('health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['tmreis_health_supervisors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function tmreis_health_supervisor($id = FALSE)
	{
		$this->trigger_events('tmreis_health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->tmreis_health_supervisors();

		return $this;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function ttwreis_health_supervisors($groups = NULL)
	{
		/* $this->trigger_events('ttwreis_health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		log_message("debug","data=====2814".print_r($data,true));
		$companyname = $data['company'];
		log_message("debug","companyname=====2816".print_r($companyname,true));
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['ttwreis_health_supervisors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		log_message("debug","this->response=====2819".print_r($this->response,true));
		return $this; */
		
		$this->trigger_events('ttwreis_health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['ttwreis_health_supervisors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISORS=====$THIS->RESPONSE==>'.print_r($this->response,true));
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function ttwreis_health_supervisor($id = FALSE)
	{
		/* $this->trigger_events('ttwreis_health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->ttwreis_health_supervisors();

		return $this; */
		
		$this->trigger_events('ttwreis_health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->ttwreis_health_supervisors();
		
		log_message('debug','ION_AUTH_MONGODB_MODEL=====ttwreis_health_supervisor=====$THIS==>'.print_r($this,true));

		return $this;
	}
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function panacea_admins($groups = NULL)
	{
		$this->trigger_events('panacea_admins');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['panacea_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function panacea_admin($id = FALSE)
	{
		$this->trigger_events('panacea_admin');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->panacea_admins();

		return $this;
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function bc_welfare_admins($groups = NULL)
	{
		$this->trigger_events('bc_welfare_admins');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['bc_welfare_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function bc_welfare_admin($id = FALSE)
	{
		$this->trigger_events('bc_welfare_admin');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->bc_welfare_admins();

		return $this;
	}
	//============================================

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function tmreis_admins($groups = NULL)
	{
		$this->trigger_events('tmreis_admins');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['tmreis_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function tmreis_admin($id = FALSE)
	{
		$this->trigger_events('tmreis_admin');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->tmreis_admins();

		return $this;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function ttwreis_admins($groups = NULL)
	{
		$this->trigger_events('ttwreis_admins');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['ttwreis_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function ttwreis_admin($id = FALSE)
	{
		$this->trigger_events('ttwreis_admin');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->ttwreis_admins();

		return $this;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function cc_users($groups = NULL)
	{
		$this->trigger_events('health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['panacea_cc']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function cc_user($id = FALSE)
	{
		$this->trigger_events('health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->cc_users();

		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function bc_welfare_cc_users($groups = NULL)
	{
		$this->trigger_events('bc_welfare_cc_users');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['bc_welfare_cc']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function bc_welfare_cc_user($id = FALSE)
	{
		$this->trigger_events('bc_welfare_cc_user');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->bc_welfare_cc_users();

		return $this;
	}
	
	//============================================

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function tmreis_cc_users($groups = NULL)
	{
		$this->trigger_events('health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['tmreis_cc']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function tmreis_cc_user($id = FALSE)
	{
		$this->trigger_events('health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->tmreis_cc_users();

		return $this;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function ttwreis_cc_users($groups = NULL)
	{
		$this->trigger_events('health_supervisors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['ttwreis_cc']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function ttwreis_cc_user($id = FALSE)
	{
		$this->trigger_events('health_supervisor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->ttwreis_cc_users();

		return $this;
	}
	
		/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function rhso_users($groups = NULL)
	{
		$this->trigger_events('rhso_users');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['rhso_users']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function rhso_user($id = FALSE)
	{
		$this->trigger_events('rhso_user');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->rhso_users();

		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function sub_admin($groups = NULL)
	{
		$this->trigger_events('sub_admin');
	
		// If there are specific select fields, apply them and flush SELECT buffer
		if (isset($this->_ion_select))
		{
			foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
			$this->_ion_select = array();
		}
	
		// Filter by group if any passed
		if (isset($groups))
		{
			// Build an array if only one group was passed
			if (is_numeric($groups))
			{
				$groups = array($groups);
			}
	
			if ( ! empty($groups))
			{
				$this->mongo_db->where_in('groups', $groups);
			}
		}
	
		$this->trigger_events('extra_where');
	
		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}
	
		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
			->limit($this->_ion_limit)
			->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}
	
		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}
	
		$data = $this->session->userdata("customer");
		$companyname = $data['company'];
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company',$companyname)->get($this->collections['sub_admins']);
		log_message('debug','USERRRRRRR++++++======>>>>>--------->>>>>>>>---------------------'.print_r($this->response,true));
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	public function customers()
	{
	     // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }
		
		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}
		
         $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['collection_for_authentication']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function panacea_doctors($groups = NULL)
	{
		$this->trigger_events('panacea_doctors');

        // If there are specific select fields, apply them and flush SELECT buffer
        if (isset($this->_ion_select))
        {
        	foreach ($this->_ion_select as $select)
			{
				$this->mongo_db->select($select);
			}
            $this->_ion_select = array();
        }

        $this->trigger_events('extra_where');

		// Run each set where conditions and flush conditions buffer
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit portion of the query if set
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit = NULL;
		}

		// Finally apply the order portion
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
			// Flush order buffers
			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

        $data = $this->session->userdata("customer");
		$companyname = $data['company'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where('company_name',$companyname)->get($this->collections['panacea_doctors']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		//log_message('debug','ION_AUTH_MONGODB_MODEL=====HEALTH_SUPERVISORS=====$THIS->RESPONSE==>'.print_r($this->response,true));
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function panacea_doctor($id = FALSE)
	{
		$this->trigger_events('panacea_doctor');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
		
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->panacea_doctors();

		return $this;
	}
	//=================================================

	public function savedpatterns()
	{
		$u = $this->session->userdata("customer");
		
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->where(array('query_user'=> $u['email']))->get($this->collections['analytics']);
		return $this->response;
		
	}
	
	// ------------------------------------------------------------------------

	public function savedpappercount()
	{
		$this->response = $this->mongo_db->get($this->collections['analytics_data']);
		return $this->response;
		
		
	}

    // ------------------------------------------------------------------------
	
     public function getdbsize()
    {
	  $result = $this->mongo_db->command(array(
         'dbStats'=>1
       ));
	   
	return $result['fileSize'];
	}
	
	// ------------------------------------------------------------------------

	public function savecount()
	{
	    $pipeline = [
        '$group' => array(
            '_id' =>null,
           'totalPop' => array('$sum' => '$pages_saved'),
        ),
    ];
		$response = $this->mongo_db->command(array(
		'aggregate'=>"analytics_data",
		'pipeline' => $pipeline,
		));
		
		return $response;
		
	}
	
	public function analyse($pipeline,$appid)
	{
		$response = $this->mongo_db->command(array(
			'aggregate'=>$appid,
			'pipeline' => $pipeline
			)
		);
		return count($response['result']);
	}
	
	public function bar_chart_x_axis($field,$appid)
	{
		$response = $this->mongo_db->command(array('distinct' => $appid ,'key' => $field));
		log_message("debug","response=====2146".print_r($response,true));
		return $response['values'];
	}
	
	public function bar_chart_x($param,$appid)
	{
		$response = $this->mongo_db->command(array(
			'aggregate'=>$appid,
			'pipeline' => $param
			)
		);
		return count($response['result']);
	}
	
	public function fetch_values_for_bmi_chart($appid,$school_value,$school_field_name,$height_field_name,$weight_field_name)
	{
	  $result = $this->mongo_db->select(array($height_field_name,$weight_field_name),array())->getWhere($appid,array(''.$school_field_name.''=>$school_value));
	  
	  return $result;
		
	}
	
	public function fetch_values_for_height_chart($appid,$school_value,$school_field_name,$height_field_name,$dob_field_name,$m_date_field_name)
	{
	  $result = $this->mongo_db->select(array($height_field_name,$dob_field_name,$m_date_field_name),array())->getWhere($appid,array(''.$school_field_name.''=>$school_value));
	  
	  return $result;
		
	}
	
	public function fetch_values_for_weight_chart($appid,$school_value,$school_field_name,$dob_field_name,$m_date_field_name,$weight_field_name)
	{
	  $result = $this->mongo_db->select(array($dob_field_name,$m_date_field_name,$weight_field_name),array())->getWhere($appid,array(''.$school_field_name.''=>$school_value));
	  
	  return $result;
		
	}
	
	public function fetch_values_for_summary_chart($appid,$school_value,$school_field_name)
	{
	  $result = $this->mongo_db->select(array('doc_data.widget_data'),array())->getWhere($appid,array(''.$school_field_name.''=>$school_value));
	  return $result;
		
	}
	
    // ------------------------------------------------------------------------
	
	public function plan_details($plan)
    {

     $this->mongo_db->switchDatabase($this->common_db['common_db']);
	$this->response = $this->mongo_db->getWhere($this->collections['plan_details'],array('plan_name'=>$plan));
	$obj = json_decode(json_encode($this->response), FALSE);
    $result = array();
		
		foreach ($obj as $row)
		{
		    $result[] = $row;
		}

       $this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}

   }

// ------------------------------------------------------------------------

public function api($customerid)
{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$this->response = $this->mongo_db->getWhere($this->collections['api_details'],array('customer'=>$customerid));
		$obj = json_decode(json_encode($this->response), FALSE);
		
		$result = array();

		$this->mongo_db->switchDatabase($this->common_db['dsn']);

		foreach ($obj as $row)
		{
		    $result[] = $row;
		}

		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
}

// ------------------------------------------------------------------------
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	public function apps()
	{
		//$this->trigger_events('apps');

		// Execute and return the object itself for the sake of chaining!
		
		$this->response = $this->mongo_db->getWhere($this->collections['records'],array('status'=>1));
		$obj = json_decode(json_encode($this->response), FALSE);
		
		$result = array();
		
		foreach ($obj as $row)
		{
		    $result[] = $row;
		}

		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}


    // ------------------------------------------------------------------------

	/**
	 * Fetching finished apps for listing apps in dashboard ( for analytics )
	 *
	 * @author  Selva
	 * 
	 */

	public function apps_for_dashboard_analytics()
	{
		$this->response = $this->mongo_db->select()->getWhere($this->collections['records'],array('status'=>1));
		$obj = json_decode(json_encode($this->response), FALSE);
		
		$result = array();
		
		foreach ($obj as $row)
		{
		    $result[] = $row;
		}

		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
//------------pagination-----------//	
		function paginate_all($limit, $page)
		{
		$offset = $limit * ( $page - 1) ;
		
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$query = $this->mongo_db->select(array('app_name'))->getWhere($this->collections['records'],array('status'=>1));
		if ($query)
		{
			return $query;
		}
		else
		{
			return FALSE;
		}
	}
	
	//------------pagination-----------//
	function paginate_all_feedbacks($limit, $page, $collection)
	{
		$offset = $limit * ( $page - 1) ;
	
		$this->mongo_db->orderBy(array('_id' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		//$this->mongo_db->select(array(),array('feedback_template', 'email'));
		$query = $this->mongo_db->get($collection);
		return $query;
	}
	
	function paginate_all_events($limit, $page, $collection)
	{
		$offset = $limit * ( $page - 1) ;
	
		$this->mongo_db->orderBy(array('start' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		//$this->mongo_db->select(array(),array('feedback_template', 'email'));
		$query = $this->mongo_db->get($collection);
		return $query;
	}
	
	public function appcount()
    {
     $appcount = $this->mongo_db->get($this->collections['records']);
	 return count($appcount);
    }
    
    public function feedbackcount($collection)
    {
    	$feedbackcount = $this->mongo_db->count($collection);
    	return $feedbackcount;
    }
    
    public function eventcount($collection)
    {
    	$feedbackcount = $this->mongo_db->count($collection);
    	return $feedbackcount;
    }

// ------------------------------------------------------------------------

    public function privateappscount()
    {
     $appcount = $this->mongo_db->getWhere($this->collections['records'],array('app_type'=>'Private','status'=>1));
	 return count($appcount);
    }

// ------------------------------------------------------------------------
	
	public function private_apps($limit, $page)
	{
		$offset = $limit * ( $page - 1) ;
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);
		$this->mongo_db->orderBy(array('app_name' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$this->response = $this->mongo_db->getWhere($this->collections['records'],array('app_type'=>'Private','status'=>1));
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
// ------------------------------------------------------------------------

	public function shared_apps($limit, $page)
	{
		$offset = $limit * ( $page - 1) ;
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);
		$this->mongo_db->orderBy(array('app_name' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$this->response = $this->mongo_db->getWhere($this->collections['records'],array('app_type'=>'Shared','status'=>1));
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
	
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
// ------------------------------------------------------------------------
	
	public function sharedappscount()
    {
     $appcount = $this->mongo_db->getWhere($this->collections['records'],array('app_type'=>'Shared','status'=>1));
	 return count($appcount);
    }
	
// ------------------------------------------------------------------------

	public function MYapps($limit,$page)
	{
		$offset = $limit * ( $page - 1) ;
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);
	    $CI = & get_instance();  //get instance, access the CI superobject
	 	$collection = $CI->session->userdata("customer");
		$useremail = $collection['email'];
		$this->mongo_db->orderBy(array('app_name' => 1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$this->response = $this->mongo_db->getWhere($this->collections['records'],array('created_by'=>$useremail,'status'=>1));
		$obj = json_decode(json_encode($this->response), FALSE);
		
		$result = array();
		
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
		
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
// ------------------------------------------------------------------------

    public function myappscount()
    {
	  $CI = & get_instance();  //get instance, access the CI superobject
	  $collection = $CI->session->userdata("customer");
	  $useremail = $collection['email'];
      $appcount = $this->mongo_db->getWhere($this->collections['records'],array('created_by'=>$useremail,'status'=>1));
	  return count($appcount);
    }
	
// ------------------------------------------------------------------------
	
	public function gallery_apps()
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$this->response = $this->mongo_db->get($this->collections['collection_for_shared_apps']);
		$obj = json_decode(json_encode($this->response), FALSE);
	    log_message('debug','tlstec common dbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb objjjjjjjjjjjjjjjjjjj'.print_r($this->response,true));

		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
	
	    $this->mongo_db->switchDatabase($this->common_db['dsn']);

		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
// ------------------------------------------------------------------------	

    public function communityappscount($category)
    {
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	   $appcount = $this->mongo_db->getWhere($this->collections['collection_for_shared_apps'],array('app_category'=>$category));
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return count($appcount);
    }
	
// -----------------------------------------------------------------------
	
	public function unfinished_workflow()
	{
		//$this->trigger_events('apps');
	
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['status']);
		$obj = $this->response;
		
		
		$d = 0;
		foreach ($obj as $docss){
			
			if($docss['status'] == "0"){
				$d++;
			}
		}
		return $d;	
	}
	
	// -----------------------------------------------------------------------
	
	public function finished_workflow()
	{
		//$this->trigger_events('apps');
	
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['status']);
		$obj = $this->response;
		
		
		$d = 0;
		foreach ($obj as $docss){
			
			if($docss['status'] == "1"){
				$d++;
			}
		}
		
		log_message('debug','finished________________workflowwwwwwwwwwwwwwwwwwwwwwwwww'.print_r($d,true));
		return $d;
	}
	


// ------------------------------------------------------------------------		
	public function user_check($id)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $document = $this->mongo_db
			->select(array('username', 'email'))
			->where('_id',new MongoId($id))
			->limit(1)
			->get($this->collections['users']); 
			log_message('debug','user_____----check documenttttttttttttttttttttttttttttttttttt'.print_r($document,true));
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
	if($document){
          return TRUE;
	}else{
          return FALSE;	
	}}
	
	// ------------------------------------------------------------------------
	public function sub_admin_check($id)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $document = $this->mongo_db
			->select(array('username', 'email'))
			->where('_id',new MongoId($id))
			->limit(1)
			->get($this->collections['sub_admins']); 
			log_message('debug','user_____----check documenttttttttttttttttttttttttttttttttttt'.print_r($document,true));
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
	if($document){
          return TRUE;
	}else{
          return FALSE;	
	}}
	
// ------------------------------------------------------------------------	

	// ------------------------------------------------------------------------
	public function admin_check($id)
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
		->select(array('username', 'email'))
		->where('_id',new MongoId($id))
		->limit(1)
		->get($this->collections['collection_for_authentication']);
		log_message('debug','user_____----check documenttttttttttttttttttttttttttttttttttt'.print_r($document,true));
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if($document){
			return TRUE;
		}else{
			return FALSE;
		}
	}

    public function paas_expiry($company)
	{
	  $comp = str_replace(" ","",$company);
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $document = $this->mongo_db
			->select(array('plan_expiry'))
			->where('company_name',$comp)
			->limit(1)
			->get($this->collections['collection_for_authentication']);
	 $this->mongo_db->switchDatabase($this->common_db['dsn']);	
	 $current_date = date("Y-m-d");
	 $data = (object) $document[0];
	 
	 if($data->plan_expiry == $current_date || $data->plan_expiry < $current_date ){
		  $this->trigger_events('post_login_unsuccessful');
	      $this->set_error('login_unsuccessful_plan_expired');
	      return FALSE;
		}
    else
       return TRUE;	
	
	}
// ------------------------------------------------------------------------	
	/**
	 * Helper: Builds & executes a MongoDB query based on the already set parameters
	 * against users collection.
	 *
	 * @return object
	 */
	
	public function docs()
	{
		$this->response = $this->mongo_db->select()->getWhere($this->collections['status'], array('status' =>1));
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
	
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	

    // --------------------------------------------------------------------

	/**
	 * Helper : Deleting app from collection 
	 *
	 * @param string  $id  Application ID
	 *
	 * @return bool
	 *
	 * @author  Selva
	 *
	 */

	 public function delete_app($id)
	{

		$this->trigger_events('pre_delete_app');
	
		// Delete document 
		$deleted = $this->mongo_db
		->where('_id', $id)
		->delete($this->collections['records']);

		// Drop related collections
		$this->mongo_db->dropCollection($id);
		$this->mongo_db->dropCollection($id."_shadow");
	
		if ( ! $deleted)
		{
			$this->trigger_events(array('post_delete_app', 'post_delete_app_unsuccessful'));
			$this->set_error('app_delete_unsuccessful');
			return FALSE;
		}
	 
		$this->trigger_events(array('post_delete_user', 'post_delete_app_successful'));
		$this->set_message('app_delete_successful');
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Deleting query from collection
	 *
	 * @param string  $id  query ID
	 *
	 * @return bool
	 *
	 * @author  Vikas
	 *
	 */
	
	public function delete_saved_pattern($id)
	{
	
		$this->trigger_events('pre_delete_saved_pattern');
	
		// Delete document
		$deleted = $this->mongo_db
		->where('_id', new MongoId($id))
		->delete($this->collections['analytics']);
		
		return TRUE;
	}
	
    // --------------------------------------------------------------------

	/**
	 * Helper : Deleting app from common collection 
	 *
	 * @param string  $id  Application ID
	 *
	 * @return bool
	 *
	 * @author  Selva
	 *
	 */
	
	 public function delete_shared_app($id)
	{
		$this->trigger_events('pre_delete_app');

        // Connect to common database
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
        
		// Delete document 
		$deleted = $this->mongo_db
		->where('app_id', $id)
		->delete($this->collections['collection_for_shared_apps']);
  
        //Connect to enterprise database
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		// Delete document 
		$deleted = $this->mongo_db
		->where('_id', $id)
		->delete($this->collections['records']);

		// Drop related collections
		$this->mongo_db->dropCollection($id);
		$this->mongo_db->dropCollection($id."_shadow");
		
		if ( ! $deleted)
		{   
		    $this->trigger_events(array('post_delete_app', 'post_delete_app_unsuccessful'));
			$this->set_error('app_delete_unsuccessful');
			return FALSE;
		}

		$this->trigger_events(array('post_delete_user', 'post_delete_app_successful'));
		$this->set_message('app_delete_successful');
		return TRUE;
	}
	
	
// ------------------------------------------------------------------------
	
	public function get_app_temp($id)
	{
		$this->trigger_events('pre_get_app_temp');
	
		
		$template = $this->mongo_db
		->where('_id', $id)
		->get($this->collections['records']);

		if ( $template[0] == "")
		{  
		    $this->trigger_events(array('post_get_app_temp', 'post_get_app_temp_unsuccessful'));
			$this->set_error('get_app_temp_unsuccessful');
			return FALSE;
		}
		return json_encode($template[0]);
	}
	
// ------------------------------------------------------------------------

	public function get_community_app_temp($id)
	{
		$this->trigger_events('pre_get_app_temp');
	
	     $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$template = $this->mongo_db
		->select(array('app_template','app_name','app_description','app_type','app_category','app_expiry'))
		->where('app_id', $id)
		->get($this->collections['collection_for_shared_apps']);
		
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ( $template[0] == "")
		{
			$this->trigger_events(array('post_get_app_temp', 'post_get_app_temp_unsuccessful'));
			$this->set_error('get_app_temp_unsuccessful');
			return FALSE;
		}
		
		return json_encode($template[0]);
	}
	
   // ------------------------------------------------------------------------	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function user($id = FALSE)
	{
		$this->trigger_events('user');

		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];

		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->users();

		return $this;
	}

	public function customer($id = FALSE)
	{
		$this->trigger_events('user');

		// If no id was passed use the current user id stored in session
		$custom = $this->session->userdata('customer');
		$id || $id = $custom['user_id'];

		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->customers();
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	/**
	 * Helper: Returns user object by its passed ID.
	 *
	 * @return object
	 */
	public function sub_admins($id = FALSE)
	{
		$this->trigger_events('sub_admin');
	
		// If no id was passed use the current user id stored in session
		$userdata = $this->session->userdata('customer');
		$id || $id = $userdata['user_id'];
	
		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));
	
		// Build and execute the query
		$this->sub_admin();
	
		return $this;
	}
	// ------------------------------------------------------------------------

	/**
	 * Returns an array of the user groups.
	 *
	 * @return array
	 */
	public function get_users_groups($id = FALSE)
	{
		$this->trigger_events('get_users_group');

		// If no id passed use the current user id stored in session
		$data = $this->session->userdata('customer');
		$id || $id = $data['user_id'];
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$groups = array();
		// Load user's group IDs array
		$user = $this->mongo_db
			->select(array('groups'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['users']);

		if (empty($user))
		{
			$this->response = new stdClass;
			return $this;
		}

		// Buildup user groups data array
		$user = (object) $user[0];
		log_message('debug','GETTTTTTT_____USERSSSSSS_____GROUPSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS*************&&&&&&&&&&&&&^^^^^^^^^^^^^^^^'.print_r($user,true));
		foreach ($user->groups as $group_name)
		{
			log_message('debug','GETTTTTTT_____USERSSSSSS_____GROUPSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS*************&&&&&&&&&&&&&^^^^^^^^^^^^^^^^'.print_r($group_name,true));
			$groups[] = $this->group_by_name($group_name)->document();
		}

		$this->response = $groups;
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Returns an array of the user groups.
	 *
	 * @return array
	 */
	public function get_sub_admin_groups($id = FALSE)
	{
		$this->trigger_events('get_users_group');
	
		// If no id passed use the current user id stored in session
		$data = $this->session->userdata('customer');
		$id || $id = $data['user_id'];
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$groups = array();
		// Load user's group IDs array
		$user = $this->mongo_db
		->select(array('groups'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['sub_admins']);
	
		if (empty($user))
		{
			$this->response = new stdClass;
			return $this;
		}
	
		// Buildup user groups data array
		$user = (object) $user[0];
		log_message('debug','GETTTTTTT_____USERSSSSSS_____GROUPSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS*************&&&&&&&&&&&&&^^^^^^^^^^^^^^^^'.print_r($user,true));
		foreach ($user->groups as $group_name)
		{
			log_message('debug','GETTTTTTT_____USERSSSSSS_____GROUPSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS*************&&&&&&&&&&&&&^^^^^^^^^^^^^^^^'.print_r($group_name,true));
			$groups[] = $this->group_by_name($group_name)->document();
		}
	
		$this->response = $groups;
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Adds group ID to the specified user document.
	 *
	 * @return bool
	 */
	public function add_to_group($group_id, $user_id = FALSE)
	{
		$this->trigger_events('add_to_group');

		// If no id passed use the current user id stored in session
		$userdetails = $this->session->userdata("customer");
		$user_id || $user_id = $userdetails['user_id'];

		return $this->mongo_db
			->where('_id', new MongoId($user_id))
			->push('groups', $group_id)
			->update($this->collections['users']);
	}

	// ------------------------------------------------------------------------

	/**
	 * Removes passed group from the user document.
	 *
	 * If the group ID is not set, it will remove all groups
	 * from the user document.
	 *
	 * @return bool
	 */
	public function remove_from_group($group_id = FALSE, $user_id = FALSE)
	{
		$this->trigger_events('remove_from_group');
        log_message('debug','USERRRRRRRRRRRRRRRRR-------------------------------COMPANYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY'.print_r($group_id,true));
		// If no id passed use the current user id stored in session
		$userdet = $this->session->userdata("customer");
		$user_id || $user_id = $userdet['user_id'];
		$usercompany = $userdet['company'];
        log_message('debug','USERRRRRRRRRRRRRRRRR-------------------------------COMPANYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY'.print_r($usercompany,true));
		// If no group name is passed remove user from all groups
		$this->mongo_db->switchDatabase($this->common_db['common_db']); 
		if (empty($group_id))
		{
			return $this->mongo_db
				->where(array('_id' => new MongoId($user_id),'company'=>$usercompany))
				->set('groups', array())
				->update($this->collections['users']);
		}
		// Only remove the specified group name from the user document
		else
		{
			return $this->mongo_db
			->where(array('_id' => new MongoId($user_id),'company'=>$usercompany))
			->pull('groups', $group_id)
			->update($this->collections['users']);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Builds and executes a MongoDB query against groups collection.
	 *
	 * @return object
	 */
	public function groups()
	{
		$this->trigger_events('groups');

		// Apply buffered conditions, and flush immediately
		if (isset($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->mongo_db->where($where);
			}
			$this->_ion_where = array();
		}

		// Apply limit/offset portions, and flush immediately
		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->mongo_db
				->limit($this->_ion_limit)
				->offset($this->_ion_offset);
			// Flush...
			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		// Limit only?
		elseif (isset($this->_ion_limit))
		{
			$this->mongo_db->limit($this->_ion_limit);
			$this->_ion_limit  = NULL;
		}

		// Apply order portion of query
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->mongo_db->order_by(array($this->_ion_order_by => $this->_ion_order));
		}
		$g = $this->session->userdata("customer");
		$group_company_name = $g['company'];
		log_message('debug','****************----------GRRRRRRRRRROOOOOOOOOOOUUUUUUUUUUUUUUPPPPPPPPPPPPPPSSSSSSSSSSSSSS----------************'.print_r($group_company_name,true));
        $this->mongo_db->switchDatabase($this->common_db['common_db']); 
		// Execute, store results and return the object itself
		$this->response = $this->mongo_db->where('company',$group_company_name)->get($this->collections['groups']);
		log_message('debug','****************----------GRRRRRRRRRROOOOOOOOOOOUUUUUUUUUUUUUUPPPPPPPPPPPPPPSSSSSSSSSSSSSS----------************'.print_r($this->response,true));
		//$this->mongo_db->switchDatabase($this->common_db['dsn']); 
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns a group object based on pre-defined buffered parameters.
	 *
	 * @return object
	 */
	public function group($id=FALSE)
	{
		$this->trigger_events('group');

		if (isset($id))
		{
			//$this->mongo_db->where('_id', new MongoId($id));
			$this->mongo_db->where('_id', $id);
		}
		

		// Set query parameters
		$this->limit(1);

		// Execute and return results
		return $this->groups();
	}

	// ------------------------------------------------------------------------


	public function group_by_name($name)
	{

		log_message('debug','nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn'.print_r($name,true));
		$name = str_replace('%20', ' ', $name);
		log_message('debug','nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn'.print_r($name,true));
         $this->mongo_db->switchDatabase($this->common_db['common_db']); 
		 $this->response = $this->mongo_db->where(array('company'=> $this->session->userdata("customer")['company'], 'name'=>$name))->get($this->collections['groups']);
		log_message('debug','ffffffffffffffffffffffffffffffffffffffffffffffffffffff'.print_r($this->response,true));
		$this->mongo_db->switchDatabase($this->common_db['dsn']); 
		return $this;

     }

    // ------------------------------------------------------------------------
	/**
	 * Updates a user document.
	 *
	 * @return bool
	 */
	public function update($id, array $data)
	{
		$this->trigger_events('pre_update_user');

		// Get user document to update
		$user = $this->user($id)->document();

		// If we're updating user document with a new identity
		// and the identity is not available to register, bam!
		if (array_key_exists($this->identity_column, $data) &&
			$this->identity_check($data[$this->identity_column]) &&
			$user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->set_error('account_creation_duplicate_' . $this->identity_column);
			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Filter the data passed
		$data = $this->_filter_data($this->collections['users'], $data);

		// Hash new password
		if (array_key_exists('password', $data))
		{
			if( ! empty($data['password']))
			{
				$data['password'] = $this->hash_user_password($data['password'], $user->salt);
			}
			else
			{
				// unset password so it doesn't effect database entry if password field empty
				unset($data['password']);
			}
		}

		// TODO: DO WE NEED TO CHECK EMAIL AND USERNAME VALUES REGARDLESS
		// OF WHAT IDENTITY FIELD IS? ARE THEY STILL UNIQUE? DONNO!

		// Check if new email already exists
		if ($this->identity_column !== 'email' && array_key_exists('email', $data) &&
			$this->email_check($data['email']) && $user->email !== $data['email'])
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}

		// Check if new username already exists
		if ($this->identity_column !== 'username' && array_key_exists('username', $data) &&
			$this->username_check($data['username']) && $user->username !== $data['username'])
		{
			$this->set_error('account_creation_duplicate_username');
			return FALSE;
		}

		$this->trigger_events('extra_where');
         $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($user->id))
			->set($data)
			->update($this->collections['users']);

		if ( ! $updated)
		{
			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}

		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Updates a user document.
	 *
	 * @return bool
	 */
	public function update_sub_admin($id, array $data)
	{
		$this->trigger_events('pre_update_user');
	
		// Get user document to update
		$user = $this->sub_admins($id)->document();
	
		// If we're updating user document with a new identity
		// and the identity is not available to register, bam!
		if (array_key_exists($this->identity_column, $data) &&
		$this->identity_check($data[$this->identity_column]) &&
		$user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->set_error('account_creation_duplicate_' . $this->identity_column);
			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
	
			return FALSE;
		}
		// Filter the data passed
		$data = $this->_filter_data_sub_admin($this->collections['sub_admins'], $data);
	
		// Hash new password
		if (array_key_exists('password', $data))
		{
			if( ! empty($data['password']))
			{
				$data['password'] = $this->hash_user_password($data['password'], $user->salt);
			}
			else
			{
				// unset password so it doesn't effect database entry if password field empty
				unset($data['password']);
			}
		}
	
		// TODO: DO WE NEED TO CHECK EMAIL AND USERNAME VALUES REGARDLESS
		// OF WHAT IDENTITY FIELD IS? ARE THEY STILL UNIQUE? DONNO!
	
		// Check if new email already exists
		if ($this->identity_column !== 'email' && array_key_exists('email', $data) &&
		$this->email_check($data['email']) && $user->email !== $data['email'])
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
	
		// Check if new username already exists
		if ($this->identity_column !== 'username' && array_key_exists('username', $data) &&
		$this->username_check($data['username']) && $user->username !== $data['username'])
		{
			$this->set_error('account_creation_duplicate_username');
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
		->where('_id', new MongoId($user->id))
		->set($data)
		->update($this->collections['sub_admins']);
	
		if ( ! $updated)
		{
			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}
	
		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}

	// ------------------------------------------------------------------------
	
	    public function admin_profile_update($company,$data)
		{
		  $this->mongo_db->switchDatabase($this->common_db['common_db']);
		  $res = $this->mongo_db->where('company_name', $company)->set($data)->update($this->collections['collection_for_authentication']);
		  $this->mongo_db->switchDatabase($this->common_db['dsn']);
		  if($res)
		  {
		    return TRUE;
		  }
		  else
		  {
		    return FALSE;
		  }
		}
		
		public function sub_admin_profile_update($company,$email,$data)
		{
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$this->mongo_db->where(array('company' => $company,'email'=>$email))->set($data)->update($this->collections['sub_admins']);
				
			$user = $this->mongo_db->where(array('company' => $company,'email'=>$email))->get($this->collections['sub_admins']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
				
			$user = (object) $user[0];
			// Set user session data
			$session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'expiry'         => $user->plan_expiry
			);
				
				
			$this->session->set_userdata("customer",$session_data);
				
			return TRUE;
		}
	
	
	// ------------------------------------------------------------------------

	   
	   /* public function admin_session_update($company)
		{
		  $this->mongo_db->switchDatabase($this->common_db['common_db']);
		  $query=$this->mongo_db->limit(1)->getWhere($this->collections['collection_for_authentication'],array('company_name'=>$company));
		  $query = $this->mongo_db->where('company_name', $company)->set($data)->update($this->collections['collection_for_authentication']);
		  $this->mongo_db->switchDatabase($this->common_db['dsn']);
		  return TRUE;
		} */

	// ------------------------------------------------------------------------
	
		public function user_profile_update($company,$email,$data)
		{
		  $this->mongo_db->switchDatabase($this->common_db['common_db']);
		  $this->mongo_db->Where(array('company'=>$company,'email'=>$email))->set($data)->update($this->collections['users']);
		  $this->mongo_db->switchDatabase($this->common_db['dsn']);
		  return TRUE;
		}
	
	
	// ------------------------------------------------------------------------
	/**
	 * Getts a query by its ID.
	 * searching search
	 * @return bool
	 */
	public function query($search_fields,$appid)
	{
		$this->trigger_events('pre_query');
		
	
		$query = $this->mongo_db->where($search_fields)->get($appid);
		//$query['search_field'] = $search.$value;
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
	
		return ($query);
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Getts a query by its ID.
	 * searching search
	 * @return bool
	 */
	public function query_app_count($appid)
	{
		$this->trigger_events('pre_query');
	
	
		$query = $this->mongo_db->count($appid);
		//$query['search_field'] = $search.$value;
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
	
		return ($query);
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Getts a query by its ID.
	 * searching search
	 * @return bool
	 */
	public function query_old($field,$value,$appid)
	{
		$this->trigger_events('pre_query');
	    
	    $search = array("doc_data.widget_data.page"."$field" => $value);
        log_message('debug','q sssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($search,true));
	    log_message('debug','fielddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($field,true));
		log_message('debug','valueeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($value,true));
		
		$query = $this->mongo_db->where($search)->get($appid);
		//$query['search_field'] = $search.$value;
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		
		return ($query);
	}
	
	
	// ------------------------------------------------------------------------

	/**
	 * Deletes a user document by its ID.
	 *
	 * @return bool
	 */
	public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		// Delete user document (groups association will also be deleted)
        	$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$deleted = $this->mongo_db
			->where('_id', new MongoId($id))
			->delete($this->collections['users']);

		if ( ! $deleted)
		{
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
$this->mongo_db->switchDatabase($this->common_db['dsn']);

		return TRUE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Deletes a user document by its ID.
	 *
	 * @return bool
	 */
	public function delete_sub_admin($id)
	{
		$this->trigger_events('pre_delete_user');
	
		// Delete user document (groups association will also be deleted)
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$deleted = $this->mongo_db
		->where('_id', new MongoId($id))
		->delete($this->collections['sub_admins']);
	
		if ( ! $deleted)
		{
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}
	
		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Updates user last login timestamp.
	 *
	 * @return bool
	 */
	public function update_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d"))
			->update($this->collections['users']);
	}

	/**
	 * Import document to datebase.
	 * searching search
	 * @return bool
	 */
	public function doc_import($coll, $data)
	{
		$this->trigger_events('pre_doc_import');
	
	
		$query = $this->mongo_db->insert($coll, $data);
		
		if ( $query == "")
		{
			$this->trigger_events(array('post_doc_import', 'post_doc_import_unsuccessful'));
			$this->set_error('get_doc_import_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	
	/**
	 * Count the documents in a collection.
	 * searching search
	 * @return int
	 */
	public function count($coll)
	{
		$this->trigger_events('pre_sql_import');
	
	
		$query = $this->mongo_db->count($coll);
		
		if ( $query == "")
		{
			$this->trigger_events(array('post_count', 'post_count_unsuccessful'));
			$this->set_error('get_count_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Import data to datebase.
	 * searching search
	 * @return bool
	 */
	public function json_import($coll,$obj)
	{
		$this->trigger_events('pre_sql_import');
	
	
		$query = $this->mongo_db->insert($coll,$obj);
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		if ( $query == "")
		{
			$this->trigger_events(array('post_sql_import', 'post_sql_import_unsuccessful'));
			$this->set_error('get_sql_import_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	
	public function json_import_edt($coll,$obj)
	{
		$this->trigger_events('pre_sql_import');
	
	
		$query = $this->mongo_db->insert($coll,$obj);
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		if ( $query == "")
		{
			$this->trigger_events(array('post_sql_import', 'post_sql_import_unsuccessful'));
			$this->set_error('get_sql_import_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	
	public function index($coll,$arr)
	{
		$this->trigger_events('pre_sql_import');
	
	
		$query = $this->mongo_db->addIndex($coll,$arr);
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		if ( $query == "")
		{
			$this->trigger_events(array('post_sql_import', 'post_sql_import_unsuccessful'));
			$this->set_error('get_sql_import_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	
	// ------------------------------------------------------------------------
	
	public function list_import($collection,$coll,$obj)
	{
		$this->trigger_events('pre_list_import');
	     $this->mongo_db->switchDatabase($this->common_db['dsn']);
	    $objarray = array(
		"list_name" => $coll,
		"list_values" => $obj
		);
		$query = $this->mongo_db->Insert($collection,$objarray);
		//log_message('debug','mooooooooooooooooooooooooooooooooooooooooooo'.print_r($query,true));
		if ( $query == "")
		{
			$this->trigger_events(array('post_sql_import', 'post_sql_import_unsuccessful'));
			$this->set_error('get_sql_import_unsuccessful');
			return FALSE;
		}
		return ($query);
	}
	// ------------------------------------------------------------------------

	 public function predefined_lists()
	{
	     $this->mongo_db->switchDatabase($this->common_db['dsn']);
		$this->response = $this->mongo_db->get($this->collections['lists']);
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Sets language cookie.
	 *
	 * @return bool
	 */
	public function set_lang($lang = 'en')
	{
		$this->trigger_events('set_lang');

		// if the user_expire is set to zero we'll set the expiration two years from now.
		if($this->config->item('user_expire', 'ion_auth') === 0)
		{
			$expire = (60*60*24*365*2);
		}
		// otherwise use what is set
		else
		{
			$expire = $this->config->item('user_expire', 'ion_auth');
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Remembers user by setting required cookies
	 *
	 * @return bool
	 */
	public function remember_user($id)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id)
		{
			return FALSE;
		}

		// Load user document
		$user = $this->user($id)->document();
		// Re-hash user password as remember code
		$salt = sha1($user->password);
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set('remember_code', $salt)
			->update($this->collections['users']);

		// Set cookies
		if ($updated)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'ion_auth') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'ion_auth');
			}

			set_cookie(array(
			    'name'   => 'identity',
			    'value'  => $user->{$this->identity_column},
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $expire
			));
              $this->mongo_db->switchDatabase($this->common_db['dsn']);
			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		// User not found
		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Logs in a remembered user.
	 *
	 * @return bool
	 */
	public function login_remembered_user()
	{
		$this->trigger_events('pre_login_remembered_user');

		// Check for valid data
		if ( !get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity')))
		{
			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
			return FALSE;
		}

		// Load the user by cookie data
		$this->trigger_events('extra_where');
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$document = $this->mongo_db
			->select(array('_id', $this->identity_column))
		    ->where($this->identity_column, get_cookie('identity'))
		    ->where('remember_code', get_cookie('remember_code'))
		    ->limit(1)
		    ->get($this->collections['users']);

		// If the user was found, sign them in
		if (count($document))
		{
			$user = (object) $document[0];

			// Update last login timestamp
			$this->update_last_login($user->_id);

			// And set user session data
			$this->session->set_userdata(array(
				$this->identity_column => $user->{$this->identity_column},
				'user_id'              => $user->_id,
			));

			// Extend the users cookies if the option is enabled
			if ($this->config->item('user_extend_on_login', 'ion_auth'))
			{
				$this->remember_user($user->_id);
			}

			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
			return TRUE;
		}

		// User not found
		$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
		return FALSE;
	}


	/**
	 * create_group
	 *
	 * @author Ben Edmunds
	*/
	public function create_group($group_name, $group_description = '', $additional_data = array())
	{
	
	    log_message('debug','inside createeeeee_______________groupppppppppppppppppppppppppppppppppppppppp@@@@@@@@@!!!!!!!!!!!!!############');
		// bail if the group name was not passed
		if(!$group_name)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->where('name', $group_name)->group()->document();
		log_message('debug','grouppppppppp_________existinggggggggggggggggggggggggggggggggggggggg'.print_r($existing_group,true));
		if(isset($existing_group) && !empty($existing_group))
		{
			$this->set_error('group_already_exists');
			return FALSE;
		}
        $com = $this->session->userdata("customer");
		$company = $com['company'];
		log_message('debug','grouppppppppp_________companyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy'.print_r($company,true));
		$data = array('name'=>$group_name,'description'=>$group_description,'company'=>$company);

		//filter out any data passed that doesnt have a matching column in the groups table
		//and merge the set group data and the additional data
		//if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->collections['groups'], $additional_data), $data);
        if (!empty($additional_data)) $data = array_merge($additional_data,$data);
		$this->trigger_events('extra_group_set');
         log_message('debug','DDDDDDDAAAAAAAAAATTTTTTTTAAAAAAAAAA_____inside_____create_______group'.print_r($data,true));
		// insert the new group
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$group_id = $this->mongo_db->insert($this->collections['groups'], $data);

		// report success
		$this->set_message('group_creation_successful');
         log_message('debug','group_____creation__________successfulllllllllllllll');
		 $this->mongo_db->switchDatabase($this->common_db['dsn']);
		// return the brand new group id
		return $group_id;
	}

	/**
	 * update_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
	{
		if (empty($group_id)) return FALSE;

		$data = array();

		if (!empty($group_name))
		{
			// we are changing the name, so do some checks

			// bail if the group name already exists
			$existing_group = $this->where('name', $group_name)->group()->document();
			log_message('debug','EEEEEEEEEEEEEEXXXXXXXXXXXXXXXIIIIIIIIIIIIIISSSSSSSSSSSSSSSSSSSSSTTTTTTTTTTTTTTIIIIIIIIIIIIIIIIIIIIIIIIIINNNNNNNNNNNNNNNNGGGGGGGGGGGGG__________GGGRRRRRRRRRROOOOOOOOUUUUUUUUUUUUUUUUUUUUPPPPPPPPPPPPPP'.print_r($existing_group,true));
			if(isset($existing_group->id) && $existing_group->id != $group_id)
			{
				$this->set_error('group_already_exists');
				return FALSE;
			}	

			$data['name'] = $group_name;		
		}
		

		// IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
		// New projects should work with 3rd param as array
		if (is_string($additional_data)) $additional_data = array('description' => $additional_data);
		

		//filter out any data passed that doesnt have a matching column in the groups table
		//and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->collections['groups'], $additional_data), $data);

        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
			->where('_id', new MongoId($group_id))
			->set($data)
			->update($this->collections['groups']);

		$this->set_message('group_update_successful');
        $this->mongo_db->switchDatabase($this->common_db['dsn']);
		return TRUE;
	}

	/**
	* delete_group
	*
	* @return bool
	* @author Ben Edmunds
	**/
	public function delete_group($group_id = FALSE)
	{
		// bail if mandatory param not set
		if(!$group_id || empty($group_id))
		{
			return FALSE;
		}

		$this->trigger_events('pre_delete_group');
        
        $this->mongo_db->switchDatabase($this->common_db['common_db']);

		// delete this group
		$deleted = $this->mongo_db
			->where('_id', new MongoId($group_id))
			->delete($this->collections['groups']);

		if (!$deleted)
		{
			$this->trigger_events(array('post_delete_group', 'post_delete_group_unsuccessful'));
			$this->set_error('group_delete_unsuccessful');
			return FALSE;
		}

         $this->mongo_db->switchDatabase($this->common_db['dsn']);
		$this->trigger_events(array('post_delete_group', 'post_delete_group_successful'));
		$this->set_message('group_delete_successful');
		return TRUE;
	}


	// ------------------------------------------------------------------------

	/**
	 * Registers a hook.
	 */
	public function set_hook($event, $name, $class, $method, $arguments)
	{
		$this->_ion_hooks->{$event}[$name] = (object) array(
			'class'     => $class,
			'method'    => $method,
			'arguments' => $arguments,
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Unregisters a hook.
	 */
	public function remove_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]))
		{
			unset($this->_ion_hooks->{$event}[$name]);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Unregisters all hooks from the passed event.
	 */
	public function remove_hooks($event)
	{
		if (isset($this->_ion_hooks->$event))
		{
			unset($this->_ion_hooks->$event);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Calls a registered hook callback.
	 */
	protected function _call_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]) &&
			method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method))
		{
			$hook = $this->_ion_hooks->{$event}[$name];
			return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 *
	 */
	public function trigger_events($events)
	{
		// If it's an array, call hooks foreach event
		if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				$this->trigger_events($event);
			}
		}
		else
		{
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
			{
				foreach ($this->_ion_hooks->$events as $name => $hook)
				{
					$this->_call_hook($events, $name);
				}
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets the message delimiters
	 *
	 * @return bool
	 */
	public function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets the error delimiters
	 *
	 * @return bool
	 */
	public function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets a message
	 */
	public function set_message($message)
	{
		
		$this->messages[] = $message;
		return $message;
	}

	// ------------------------------------------------------------------------

	/**
	 * Applies delimiters and returns themed messages
	 */
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
            $message_lang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $_output .= $this->message_start_delimiter . $message_lang . $this->message_end_delimiter;
		}

		return $_output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Return messages as an array, langified or not
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets an error message
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;
		return $error;
	}

	// ------------------------------------------------------------------------

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

	// ------------------------------------------------------------------------

	/**
	 * Helper: Filters out any data passed that doesn't have a matching column in $table.
	 *
	 * Since MongoDB is a schemaless database, we define collection fields as an static array,
	 * why we do so? MongoDB is vulnerable to Null Byte Injection as stated in the below article.
	 *
	 * @see http://www.idontplaydarts.com/2011/02/mongodb-null-byte-injection-attacks/
	 * @return array
	 */
	protected function _filter_data($collection, $data)
	{
		$filtered_data = $columns = array();
		// Define field dictionaries
		$columns = $collection == 'users' ?
			// Users collection static schema array
			array('_id', 'ip_address', 'username', 'password', 'salt', 'email', 'activation_code', 'forgotten_password_code', 'forgotten_password_time', 'remember_code', 'created_on', 'last_login', 'active', 'first_name', 'last_name', 'company', 'phone','device_unique_number','subscribed_with','subscription_start','subscription_end','plan_subscribed') :
			// Groups collection static schema array
			array('_id', 'name', 'description','company');

		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				// Skip unavailable fields
				if (array_key_exists($column, $data))
				{
					$filtered_data[$column] = $data[$column];
				}
			}
		}

		return $filtered_data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Helper: Filters out any data passed that doesn't have a matching column in $table.
	 *
	 * Since MongoDB is a schemaless database, we define collection fields as an static array,
	 * why we do so? MongoDB is vulnerable to Null Byte Injection as stated in the below article.
	 *
	 * @see http://www.idontplaydarts.com/2011/02/mongodb-null-byte-injection-attacks/
	 * @return array
	 */
	protected function _filter_data_sub_admin($collection, $data)
	{
		$filtered_data = $columns = array();
		// Define field dictionaries
		$columns = $collection == 'sub_admins' ?
		// Users collection static schema array
		array('_id', 'ip_address', 'username', 'password', 'salt', 'email', 'activation_code', 'forgotten_password_code', 'forgotten_password_time', 'remember_code', 'created_on', 'last_login', 'active', 'first_name', 'last_name', 'company', 'phone') :
		// Groups collection static schema array
		array('_id', 'name', 'description','company');
	
		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				// Skip unavailable fields
				if (array_key_exists($column, $data))
				{
					$filtered_data[$column] = $data[$column];
				}
			}
		}
	
		return $filtered_data;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Prepares IP address string for database insertion.
	 *
	 * @return string
	 */
	protected function _prepare_ip($ip_address)
	{
		return $ip_address;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Clones "_id" field value in a new "id" property.
	 *
	 * We need to add an arbitrary "id" field to the resulted
	 * object to maintain IonAuth compatibility with both
	 * mongodb library and the native database drivers with
	 * minimum level of code change.
	 *
	 * This helper only clones the _id field value, if:
	 * 1. _id field is already present in the array.
	 * 2. id field is not set already set with any other values.
	 *
	 * @param  mixed $result Result array or object
	 * @return mixed
	 */
	public function _clone_mongoid($result)
	{
		$data = is_object($result) ? (array) $result : $result;

		// It's an array of array, clone mongoid of each one
		if (isset($data[0]))
		{
			foreach ($data as $key => $value)
			{
				$data[$key] = $this->_clone_mongoid($value);
			}
		}
		elseif ( ! isset($data['id']) && isset($data['_id']))
		{
			$data['id'] = $data['_id']->{'$id'};
		}

		return is_object($result) ? (object) $data : $data;
	}
//****new code********************************************************************************************************************************

	function get_col_docs($coll)
	{

		$this->mongo_db->switchDatabase($this->common_db['common_db']); 
		$query = $this->mongo_db->select(array('name','collection'),array())->get($coll);
		$this->mongo_db->switchDatabase($this->common_db['dsn']); 
		return $query;
	}
	
	function get_all_docs($coll)
	{

		$this->mongo_db->switchDatabase($this->common_db['common_db']); 
		$query = $this->mongo_db->select(array(),array('_id','username','password','last_login','mobile_number','registered_on'))->get($coll);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $query;
	}
	
	function get_api_details($api_id)
	{
	
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->where(array('_id' => new MongoId($api_id)))->get('api_details');
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $query[0];
	}

	function graph_apps()
	{
		//log_message('debug','~~~~~~~~~~~~~~~apps~~~~~~~~~~~~~~~~~~~~~');
		$previousday = date('Y-m-d H:i:s', strtotime('-1 day'));
		$prevtim=$previousday;
		$appgraph_value = array();
		for($i=0;$i<12;$i++)
		{
			$previousday = date_create($previousday);
			$hours=date_add($previousday, date_interval_create_from_date_string('2 hours'));
			$twohours=date_format($hours, 'Y-m-d H:i:s');
			$this->mongo_db->whereBetween('time',$prevtim,$twohours);
			$query = $this->mongo_db->count($this->collections['records']);
			array_push($appgraph_value, $query);
			$previousday=$twohours;
			$prevtim=$twohours;
		}
		return $appgraph_value;
	}
	
	function graph_docs()
	{
		//log_message('debug','~~~~~~~~~~~~~~~~docsssssss~~~~~~~~~~~~~~~~~~~~');
		$previousweek = date('Y-m-d H:i:s', strtotime('-7 days'));
		$prevwk=$previousweek;
		$docgraph_value = array();
		for($l=0;$l<14;$l++)
		{
			$previousweek = date_create($previousweek);
			$hours_docs=date_add($previousweek, date_interval_create_from_date_string('12 hours'));//('2 hours'));
			$two_hours=date_format($hours_docs, 'Y-m-d H:i:s');
			$this->mongo_db->whereBetween('time',$prevwk,$two_hours);
			$this->mongo_db->where('status',1);
			$query_result = $this->mongo_db->count('status');
			//log_message('debug','~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.print_r($query_result,true));
			array_push($docgraph_value, $query_result);
			$previousweek=$two_hours;
			$prevwk=$two_hours;
			//break;
		}
		//log_message('debug','~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.print_r($docgraph_value,true));
		return $docgraph_value;
	}	
	function app_history_model()
	{
		//log_message('debug','`````````````````````````````````````');
		$this->mongo_db->orderBy(array('time' => -1));
		$this->mongo_db->limit(10);
		$this->mongo_db->select(array('time','app_name','app_description'));		
		$query = $this->mongo_db->get($this->collections['records']);
		//log_message('debug','`````````````````````````````````````'.print_r($query,true));
		$obj = json_decode(json_encode($query), FALSE);
		//log_message('debug','`````````````````````````````````````'.print_r($obj,true));		
		return $obj;
	}
	function docs_history_model()
	{
		$query = array();
		$this->mongo_db->orderBy(array('time' => -1));
		$this->mongo_db->select(array('_id'));
		$result_id = $this->mongo_db->get($this->collections['records']);

		foreach($result_id as $id)
		{
			$this->mongo_db->orderBy(array('history.last_stage.time' => -1));
			$this->mongo_db->limit(2);
			$this->mongo_db->where(array('doc_properties.status'=>1));
			$this->mongo_db->select(array('app_properties','history'),array());
			$collection_name =$id['_id'];
			$query = array_merge($query,$this->mongo_db->get($collection_name));
		}
		$obj = json_decode(json_encode($query), FALSE);
		return $obj;
	}
	
	function admin_message_model()
	{
		//log_message('debug','`````````````````````````````````````');
		$this->mongo_db->orderBy(array('time' => -1));
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		//$this->mongo_db->orderBy(array('_id' => -1));
		$this->mongo_db->limit(10);
		$this->mongo_db->select(array('subject','message'));		
		$query = $this->mongo_db->get($this->collections['message_notify']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		//log_message('debug','`````````````````````````````````````'.print_r($query,true));
		$obj = json_decode(json_encode($query), FALSE);
		//log_message('debug','`````````````````````````````````````'.print_r($obj,true));		
		return $obj;
	}
	function docs_count_model()
	{
		//log_message('debug','`````````````````````````````````````');
		$this->mongo_db->limit(10);
		$this->mongo_db->select(array('total_docs','time'));		
		$query = $this->mongo_db->get($this->collections['total_docs']);
		//log_message('debug','```````))))))))))))`````````'.print_r($query,true));
		$obj = json_decode(json_encode($query), FALSE);
		//log_message('debug','`````````````````````````````````````'.print_r($obj,true));		
		return $obj;
}

    function get_apps($id)
	{
	    $collection = str_replace('=','#',$id);

		$query     = $this->mongo_db->select(array('app_id','status','app_name'))->get($collection.'_apps');
        $query_web = $this->mongo_db->select(array('app_id','status','app_name'))->get($collection.'_web_apps');
        $result    = array_merge($query,$query_web);
        return $result;
	}


    	function change_status($_id,$user,$status)
	{
	
         $collection = str_replace('=','#',$user);
		
		 $query = $this->mongo_db->where('_id', new MongoId($_id))->set(array('status'=>$status))->update($collection.'_apps');
		
		 if($query == false)
		 {
			$query = $this->mongo_db->where('_id', new MongoId($_id))->set(array('status'=>$status))->update($collection.'_web_apps');
		 }
		
		 return $query;
	}
    

    function fetch_details_for_app_specification($appid)
	{
	  $this->mongo_db->select();
	  $query=$this->mongo_db->getWhere($this->collections['records'],array('_id'=>$appid));
	  return $query;
	
	}
	
	function fetch_details_for_community_app_specification($appid)
	{
	  $this->mongo_db->switchDatabase($this->common_db['common_db']);
	  $query = $this->mongo_db->getWhere($this->collections['collection_for_shared_apps'],array('app_id'=>$appid));
	  $this->mongo_db->switchDatabase($this->common_db['dsn']);
	  return $query;
	
	}
	
	function get_draft_apps($limit,$page)
	{
	    $offset = $limit * ( $page - 1) ;
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
	    $this->response = $this->mongo_db->getWhere($this->collections['records'],array('status'=>0));
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
		if ($result)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	
	}
	
	function draftcount()
	{
	  $draft = $this->mongo_db->getWhere($this->collections['records'],array('status'=>0));
	  return count($draft);
	
	}
	
	public function user_creation_allowed()
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$plan_name = $this->mongo_db->select(array('plan'))->where(array('company_name'=> $this->session->userdata("customer")['company']))->get($this->collections['collection_for_authentication']);
		$allowed_users = $this->mongo_db->select(array('total_users'))->where(array('plan_name'=> $plan_name[0]['plan']))->get($this->collections['plan_details']);
		$users_count = $this->mongo_db->where(array('company'=> $this->session->userdata("customer")['company']))->count($this->collections['users']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		if($users_count < $allowed_users[0]['total_users']){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function app_creation_allowed()
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$plan_name = $this->mongo_db->select(array('plan'))->where(array('company_name'=> $this->session->userdata("customer")['company']))->get($this->collections['collection_for_authentication']);
		$allowed_apps = $this->mongo_db->select(array('total_apps'))->where(array('plan_name'=> $plan_name[0]['plan']))->get($this->collections['plan_details']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		
		$apps_count = $this->mongo_db->count($this->collections['records']);
		
		if($apps_count < $allowed_apps[0]['total_apps']){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function doc_submit_allowed()
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$plan_name = $this->mongo_db->select(array('plan'))->where(array('company_name'=> $this->session->userdata("customer")['company']))->get($this->collections['collection_for_authentication']);
		$allowed_docs = $this->mongo_db->select(array('total_docs'))->where(array('plan_name'=> $plan_name[0]['plan']))->get($this->collections['plan_details']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		$docs_count = $this->mongo_db->select(array('total_docs'))->get($this->collections['total_docs']);
		
		if($docs_count[0]['total_docs'] < $allowed_docs[0]['total_docs']){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function user_by_email($email)
	{
        $this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['users']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $query;
	
	}
	
	public function sub_admin_by_email($email)
	{
	
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$query = $this->mongo_db->where(array('email'=> $email))->get($this->collections['sub_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		return $query;
	
	}
	
	public function api_users($id = FALSE,$new = FALSE)
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		if (($id == FALSE) && ($new == FALSE)) {
			$query = $this->mongo_db->where(array('customer'=> $this->session->userdata("customer")['user_id'],'first_time_user'=>0))->get($this->collections['api_details']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $query;
		}elseif(($id != FALSE) && ($new == FALSE)){
			$query = $this->mongo_db->where(array('_id'=> new MongoId($id)))->get($this->collections['api_details']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $query[0];
		}
		if ($new != FALSE) {
			$query = $this->mongo_db->where(array('customer'=> $this->session->userdata("customer")['user_id'], 'first_time_user'=> 1))->get($this->collections['api_details']);
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
			return $query;
		}
	}
	
	public function activate_api($id, $code = FALSE)
	{
		$this->trigger_events('pre_activate');
	
		// If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$docs = $this->mongo_db
			->select($this->identity_column)
			->where('activation_code', $code)
			->limit(1)
			->get($this->collections['api_details']);
			$result = (object) $docs[0];
	
			// If unsuccessfull
			if (count($docs) !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('docs');
				return FALSE;
			}
	
			$identity = $result->{$this->identity_column};
			 
			$this->trigger_events('extra_where');
			$updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array('activation_code' => NULL, 'active' => 1,'first_time_user' => 0))
			->update($this->collections['api_details']);
	
			$this->mongo_db->switchDatabase($this->common_db['dsn']);
		}
		// Activation code is not set
		else
		{
			$this->trigger_events('extra_where');
			$this->mongo_db->switchDatabase($this->common_db['common_db']);
			$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => NULL, 'active' => 1,'first_time_user' => 0))
			->update($this->collections['api_details']);
		}
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}
	
		return $updated;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Updates a user document with an activation code.
	 */
	public function deactivate_api($id = NULL)
	{
		$this->trigger_events('deactivate');
	
		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}
	
		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		$this->trigger_events('extra_where');
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$updated = $this->mongo_db
		->where('_id', new MongoId($id))
		->set(array('activation_code' => $activation_code, 'active' => 0))
		->update($this->collections['api_details']);
	
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
		if ($updated)
		{
			$this->set_message('deactivate_successful');
		}
		else
		{
			$this->set_error('deactivate_unsuccessful');
		}
	
		return $updated;
	}
	
	public function get_event_app_temp($id)
	{
	    $template = $this->mongo_db
		->select(array('event_template',"comments"),array())
		->where('id', $id)
		->get($this->collections['event_requests']);

		if ($template[0] == "")
		{
			$this->trigger_events(array('post_get_app_temp', 'post_get_app_temp_unsuccessful'));
			$this->set_error('get_app_temp_unsuccessful');
			return FALSE;
		}
		return $template[0];
	}
	
	public function get_feedback_app_temp($id)
	{
	    $template = $this->mongo_db
		->select(array('feedback_template',"comments"),array())
		->where('id', $id)
		->get($this->collections['feedback_requests']);

		if ($template[0] == "")
		{
			$this->trigger_events(array('post_get_app_temp', 'post_get_app_temp_unsuccessful'));
			$this->set_error('get_app_temp_unsuccessful');
			return FALSE;
		}
		return $template[0];
	}
	
	public function sub_admin_creation_allowed()
	{
		$this->mongo_db->switchDatabase($this->common_db['common_db']);
		$plan_name = $this->mongo_db->select(array('plan'))->where(array('company_name'=> $this->session->userdata("customer")['company']))->get($this->collections['collection_for_authentication']);
		$allowed_users = $this->mongo_db->select(array('total_sub_admins'))->where(array('plan_name'=> $plan_name[0]['plan']))->get($this->collections['plan_details']);
		$users_count = $this->mongo_db->where(array('company'=> $this->session->userdata("customer")['company']))->count($this->collections['sub_admins']);
		$this->mongo_db->switchDatabase($this->common_db['dsn']);
	
		if($users_count < $allowed_users[0]['total_sub_admins']){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch count of all event requests ( create an event app )
	 *
	 *  
	 * @author Vikas 
	 */
	 

     function get_event_requests_count()
	{
		$query = $this->mongo_db->where(array('req_status'=>'new','event_status'=>'new'))->count($this->collections['event_requests']);
		return $query;
		
     }
	 // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch count of all event edit requests ( create an event app )
	 *
	 *  
	 * @author Vikas 
	 */
	 

     function get_event_requests_edit_count()
	{
		$query = $this->mongo_db->where(array('req_status'=>'edited','event_status'=>'new_edit'))->count($this->collections['event_requests']);
		return $query;
		
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Fetch count of all feedback requests ( create a feedback app )
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_feedback_requests_count()
	{
		$query = $this->mongo_db->where(array('req_status'=>'New','feedback_status'=>'new'))->count($this->collections['feedback_requests']);
		return $query;
		
    }
	 
	 // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch count of all feedback edit requests ( create a feedback app )
	 *
	 *  
	 * @author Selva 
	 */
	 

     function get_feedback_requests_edit_count()
	{
		$query = $this->mongo_db->where(array('req_status'=>'edited','feedback_status'=>'new_edit'))->count($this->collections['feedback_requests']);
		return $query;
		
    }
	
    function fetch_applications()
	{
	  $this->mongo_db->select(array('app_name','app_template'),array());
	  $query=$this->mongo_db->getWhere($this->collections['records'],array('status'=>1));
	  return $query;
	
	}

	public function user_by_mobile($mobile)
    {
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	$query = $this->mongo_db->where(array('mobile'=> $mobile))->get($this->collections['ghmc_users']);
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	return $query;
    
    }
	
	   /**
     * Inserts a user document into users collection.
     *
     * @return bool
     */
    public function register_ghmc_user($additional_data = array())
    {
    	//$manual_activation = $this->config->item('manual_activation', 'ion_auth');
    	
    
    		// IP address
    		$ip_address = $this->_prepare_ip($this->input->ip_address());
    		
    		//========Unique ID==================================
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$all_uniqueID = $this->mongo_db->select(array('unique_id'))->getWhere($this->collections['ghmc_users'], array('company' => TENANT));
    		
    		if(!empty($all_uniqueID)){
    			foreach ($all_uniqueID as $uID){
    				array_push($user, $uID['unique_id']);
    			}
    			$maxID = max($user);
    			$uid_str = substr($maxID, strlen($maxID)-9);
    			$uid = intval($uid_str);
    			$inc = $uid+1;
    			$unique_id = TENANT.$inc;
    		}else{
    			$unique_id = TENANT."100110001";
    		}
    		//===================================================
    
    		// New user document
    		$data = array(
    		'unique_id'	 => $unique_id,
    		'dob'		 => $additional_data['dob'],
    		'username'   => $additional_data['username'],
    		'mobile'	 => $additional_data['phone'],
    		'first_name' => $additional_data['first_name'],
    		'last_name'	 => $additional_data['last_name'],
    		'company_name'	 => $additional_data['company'],
    		'ip_address' => $ip_address,
    		'registered_on' => date("Y-m-d"),
    		'last_login' => date("Y-m-d H:i:s"),
			'active'     => 1,//($manual_activation === FALSE ? 1 : 0),
    		'status'     => 'offline'
		);
    
    	// Filter out any data passed that doesn't have a matching column in the
    	// user document and merge the set user data with the passed additional data
    	//$data = array_merge($this->_filter_data($this->collections['users'], $additional_data), $data);
    
    	$this->mongo_db->switchDatabase($this->common_db['common_db']);
    	// Insert new document and store the _id value
    	$id = $this->mongo_db->insert($this->collections['ghmc_users'], $data);
    
    	$this->trigger_events('post_register');
    	$this->mongo_db->switchDatabase($this->common_db['dsn']);
    	// Return new document _id or FALSE on failure
    	return isset($id) ? $id : FALSE;
    }
    
    function check_plan_and_charge($access_type){
    	$collection = $this->session->userdata("customer");
		//$companyname = $data['company'];
    	$useremail = $collection['email'];
    	
    	$date = date("Y-m-d");
    	
    	if($access_type == "doc_submit"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    			"email" => $expense[0]['email'],
    			"time" => $expense[0]['time'],
    			"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    			"general_resubmit" => $expense[0]['general_resubmit'],
    			"new_doc" => intval($expense[0]['new_doc']) + 1,
    			"transactions" => intval($expense[0]['transactions']) + 1
    			);
    			
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "0",
    					"new_doc" => "1",
    					"transactions" => "1"
    			);
    			 
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    		
    	}
    	
    	if($access_type == "doc_update"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    					"email" => $expense[0]['email'],
    					"time" => $expense[0]['time'],
    					"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    					"general_resubmit" => intval($expense[0]['general_resubmit'])+1,
    					"new_doc" => $expense[0]['new_doc'],
    					"transactions" => intval($expense[0]['transactions']) + 1
    			);
    			 
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "1",
    					"new_doc" => "0",
    					"transactions" => "1"
    			);
    	
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    	
    	}
    	
    	if($access_type == "transaction"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    					"email" => $expense[0]['email'],
    					"time" => $expense[0]['time'],
    					"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    					"general_resubmit" => $expense[0]['general_resubmit'],
    					"new_doc" => $expense[0]['new_doc'],
    					"transactions" => intval($expense[0]['transactions']) + 1
    			);
    			 
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "0",
    					"new_doc" => "0",
    					"transactions" => "1"
    			);
    	
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    	
    	}
    	
    }
    
    function notify_user_about_plan_charge(){
    	$collection = $this->session->userdata("customer");
    	//$companyname = $data['company'];
    	$useremail = $collection['email'];
    	 
    	$date = date("Y-m-d");
    	 
    	if($access_type == "doc_submit"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    					"email" => $expense[0]['email'],
    					"time" => $expense[0]['time'],
    					"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    					"general_resubmit" => $expense[0]['general_resubmit'],
    					"new_doc" => intval($expense[0]['new_doc']) + 1,
    					"transactions" => intval($expense[0]['transactions']) + 1
    			);
    			 
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "0",
    					"new_doc" => "1",
    					"transactions" => "1"
    			);
    
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    
    	}
    	 
    	if($access_type == "doc_update"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    					"email" => $expense[0]['email'],
    					"time" => $expense[0]['time'],
    					"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    					"general_resubmit" => intval($expense[0]['general_resubmit'])+1,
    					"new_doc" => $expense[0]['new_doc'],
    					"transactions" => intval($expense[0]['transactions']) + 1
    			);
    
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "1",
    					"new_doc" => "0",
    					"transactions" => "1"
    			);
    			 
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    		 
    	}
    	 
    	if($access_type == "transaction"){
    		$this->mongo_db->switchDatabase($this->common_db['common_db']);
    		$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->get($this->collections['user_expense']);
    		if($expense){
    			$data = array(
    					"email" => $expense[0]['email'],
    					"time" => $expense[0]['time'],
    					"in_week_resubmit" => $expense[0]['in_week_resubmit'],
    					"general_resubmit" => $expense[0]['general_resubmit'],
    					"new_doc" => $expense[0]['new_doc'],
    					"transactions" => intval($expense[0]['transactions']) + 1
    			);
    
    			$expense = $this->mongo_db->where(array("email" => $useremail, "time" => $date))->set($data)->update($this->collections['user_expense']);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}else{
    			$data = array(
    					"email" => $useremail,
    					"time" => $date,
    					"in_week_resubmit" => "0",
    					"general_resubmit" => "0",
    					"new_doc" => "0",
    					"transactions" => "1"
    			);
    			 
    			$expense = $this->mongo_db->insert($this->collections['user_expense'],$data);
    			$this->mongo_db->switchDatabase($this->common_db['dsn']);
    		}
    		 
    	}
    	 
    }
}
// END Ion_auth_mongodb_model Class

/* End of file ion_auth_mongodb_model.php */
/* Location: ./application/modules/auth/models/ion_auth_mongodb_model.php */
