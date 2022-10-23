<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
		
		// Database 
		$this->common_db = $this->config->item('default');

		// Initialize MongoDB collection names
		$this->collections = $this->config->item('collections', 'ion_auth');

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

	// --------------------------------------------------------------------------------------------

	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *  
	 * @author Selva 
	 */
	 
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['customers']);
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
	
	public function hash_password_panacea_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_admins']);
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
	
	public function hash_password_poweroften_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('poweroften_admins');
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
	
	public function hash_password_poweroften_dar_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('poweroften_dar_admins');
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
	
	public function hash_password_l3_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('l3_admins');
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
	
	public function hash_password_tswreis_sports_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('tswreis_sports_admins');
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
	
	public function hash_password_panacea_secretary($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('panacea_secretary');

		//->get($this->collections['panacea_secretary']);
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
	
	public function hash_password_screening_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['screening_import_admin']);
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
	 * @author Selva
	 */
	
	public function hash_password_panacea_viewers_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_viewers']);
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
	 * @author Selva
	 */
	
	public function hash_password_tmreis_viewers_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['tmreis_viewers']);
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
	 * @author Selva
	 */
	
	public function hash_password_ttwreis_viewers_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ttwreis_viewers']);
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
	 * @author Selva
	 */
	
	public function hash_password_panacea_sanitation_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_sanitation_admins']);
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
	 * @author Selva
	 */
	
	public function hash_password_tmreis_sanitation_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['tmreis_sanitation_admins']);
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
	 * @author Selva
	 */
	
	public function hash_password_ttwreis_sanitation_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ttwreis_sanitation_admins']);
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
	
	public function hash_password_superiors($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['superiors']);
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
	
	public function hash_password_bc_welfare_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['bc_welfare_admins']);
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
	
	public function hash_password_panacea_health_supervisor($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_health_supervisors']);
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
	
	public function hash_password_tmreis_health_supervisor($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['tmreis_health_supervisors']);
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
	
	public function hash_password_ttwreis_health_supervisor($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ttwreis_health_supervisors']);
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
	
	public function hash_password_bc_welfare_health_supervisor($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['bc_welfare_health_supervisors']);
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
	
	public function hash_password_panacea_ccuser($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_cc']);
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
	//=====================================================
	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_bc_welfare_ccuser($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['bc_welfare_cc']);
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

	//=====================================================
	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_psychologist_user($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get('psycologist_users');
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
	//==============================================================
	public function hash_password_ttwreis_cc($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
		
		$this->trigger_events ( 'extra_where' );
		
		$document = $this->mongo_db->select ( array (
				'password',
				'salt' 
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['ttwreis_cc'] );
		$hash_password_db = ( object ) $document [0];
		
		if (count ( $document ) !== 1) {
			return FALSE;
		}
		
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
			
			return FALSE;
		}
		
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
		
		return ($db_password == $hash_password_db->password);
	}
//============================================================	
	public function hash_password_ttwreis_admins($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['ttwreis_admins'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	//==============================================
	public function hash_password_ttwreis_hs_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
		
		$this->trigger_events ( 'extra_where' );
		
		$document = $this->mongo_db->select ( array (
				'password',
				'salt' 
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['ttwreis_health_supervisors'] );
		$hash_password_db = ( object ) $document [0];
		
		if (count ( $document ) !== 1) {
			return FALSE;
		}
		
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
			
			return FALSE;
		}
		
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
		
		return ($db_password == $hash_password_db->password);
	}
	
		public function hash_password_tmreis_cc($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
		
		$this->trigger_events ( 'extra_where' );
		
		$document = $this->mongo_db->select ( array (
				'password',
				'salt' 
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['tmreis_cc'] );
		$hash_password_db = ( object ) $document [0];
		
		if (count ( $document ) !== 1) {
			return FALSE;
		}
		
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
			
			return FALSE;
		}
		
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
		
		return ($db_password == $hash_password_db->password);
	}
	
	public function hash_password_tmreis_admins($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['tmreis_admins'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	
	// --------------------------------------------------------------------------------------------
	
	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_patient_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['form_users']);
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
	

	
	// --------------------------------------------------------------------------------------------------------

	/**
	 * Helper : Takes a password and validates it against an entry in the API (third party) customers collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *  
	 * @author Vikas 
	 */
	 
	public function hash_password_api_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['api_details']);
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
	
	
	// --------------------------------------------------------------------------------------------

	/**
	 * Helper : Takes a password and validates it against an entry in the support admin collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *  
	 * @author Selva 
	 */
	 
	public function hash_password_support_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['support_admin']);
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
	
	// -----------------------------------------------------------------------------------------

	/**
	 * Helper : Takes a password and validates it against an entry in the sub admin collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *  
	 * @author Vikas 
	 */
	 
	public function hash_password_sub_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['sub_admins']);
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
			$docs = $this->mongo_db
				->select($this->identity_column)
				->where('activation_code', $code)
				->limit(1)
				->get($this->collections['customers']);
			$result = (object) $docs[0];
			//$result = (object) $docs;
             
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
				->update($this->collections['customers']);
		}
		// Activation code is not set
		else
		{
		    //log_message('debug','****************Inside activate functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn else partttttttt*************************');
			$this->trigger_events('extra_where');
			$updated = $this->mongo_db
				->where('_id', new MongoId($id))
				->set(array('activation_code' => NULL, 'active' => 1,'first_time_user' => 0))
				->update($this->collections['customers']);
		}


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

		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['customers']);

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

		$customercount = count($this->mongo_db
			->where('forgotten_password_code', $code)
			->get($this->collections['customers']));

		if ($customercount > 0)
		{
			$this->mongo_db
				->where('forgotten_password_code', $code)
				->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
				->update($this->collections['customers']);

			return TRUE;
		}
		else
		{
			$usercount = count($this->mongo_db
			->where('forgotten_password_code', $code)
			->get($this->collections['users']));

			if ($usercount > 0)
		    {
				$this->mongo_db
					->where('forgotten_password_code', $code)
					->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
					->update($this->collections['users']);

				return TRUE;
		    }
		    else
		    {
		    	$admincount = count($this->mongo_db
			    ->where('forgotten_password_code', $code)
			    ->get($this->collections['tlstec_admin']));

			    if ($admincount > 0)
		        {
					$this->mongo_db
						->where('forgotten_password_code', $code)
						->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
						->update($this->collections['tlstec_admin']);

					return TRUE;
		        }
		        else
		        {
		        	$hscount = count($this->mongo_db
					->where('forgotten_password_code', $code)
					->get($this->collections['panacea_health_supervisors']));

					if ($hscount > 0)
					{
						$this->mongo_db
							->where('forgotten_password_code', $code)
							->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
							->update($this->collections['panacea_health_supervisors']);

						return TRUE;
					}
					
					else
					{
						$bc_welfare_hscount = count($this->mongo_db
						->where('forgotten_password_code', $code)
						->get($this->collections['bc_welfare_health_supervisors']));

						if ($bc_welfare_hscount > 0)
						{
							$this->mongo_db
								->where('forgotten_password_code', $code)
								->set(array('forgotten_password_code' => NULL, 'forgotten_password_time' => NULL))
								->update($this->collections['bc_welfare_health_supervisors']);

							return TRUE;
						}
					
						else
						{
							return FALSE;
						}
					}

		        }

		    }

		}
		
	}

	// ------------------------------------------------------------------------

	/**
	 * Resets password.
	 *
	 * @return bool
	 */
	public function reset_password($identity, $new) 
	{
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check_for_reset_password($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$customerdocs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['customers']);

		// Unsuccessfull password change
		if (count($customerdocs) !== 1)
		{

			$userdocs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['users']);

             if(count($userdocs) !== 1)
             {
             	$admindocs = $this->mongo_db
			   ->select(array('_id', 'password', 'salt'))
			   ->where($this->identity_column, $identity)
			   ->limit(1)
			   ->get($this->collections['tlstec_admin']);

				    if (count($admindocs) !== 1)
			        {
					  $subadmindocs = $this->mongo_db
			          ->select(array('_id', 'password', 'salt'))
			          ->where($this->identity_column, $identity)
			          ->limit(1)
			          ->get($this->collections['sub_admins']);
					  
					   if (count($subadmindocs) !== 1)
			           {
					      $supportadmindocs = $this->mongo_db
			              ->select(array('_id', 'password', 'salt'))
			              ->where($this->identity_column, $identity)
						  ->limit(1)
			              ->get($this->collections['support_admin']);
						  
						    if (count($supportadmindocs) !== 1)
			                {
							    // SW HS
								$tswreis_hs_docs = $this->mongo_db
							  ->select(array('_id', 'password', 'salt'))
							  ->where($this->identity_column, $identity)
							  ->limit(1)
							  ->get($this->collections['panacea_health_supervisors']);
							  
								if (count($tswreis_hs_docs) !== 1)
								{
										// SW Doctors
									$tswreis_dr_docs = $this->mongo_db
								  ->select(array('_id', 'password', 'salt'))
								  ->where($this->identity_column, $identity)
								  ->limit(1)
								  ->get($this->collections['panacea_doctors']);
								  
									if (count($tswreis_dr_docs) !== 1)
									{
										// TMREIS HS
										$tmreis_hs_docs = $this->mongo_db
									  ->select(array('_id', 'password', 'salt'))
									  ->where($this->identity_column, $identity)
									  ->limit(1)
									  ->get($this->collections['tmreis_health_supervisors']);
									  
										if (count($tmreis_hs_docs) !== 1)
										{
											// TMREIS Doctors
											$tmreis_dr_docs = $this->mongo_db
										  ->select(array('_id', 'password', 'salt'))
										  ->where($this->identity_column, $identity)
										  ->limit(1)
										  ->get($this->collections['tmreis_doctors']);
										  
											if (count($tmreis_dr_docs) !== 1)
											{
												// TTWREIS HS
												$ttwreis_hs_docs = $this->mongo_db
											  ->select(array('_id', 'password', 'salt'))
											  ->where($this->identity_column, $identity)
											  ->limit(1)
											  ->get($this->collections['ttwreis_health_supervisors']);
											  
												if (count($ttwreis_hs_docs) !== 1)
												{
													// TTWREIS Doctors
													$ttwreis_dr_docs = $this->mongo_db
												  ->select(array('_id', 'password', 'salt'))
												  ->where($this->identity_column, $identity)
												  ->limit(1)
												  ->get($this->collections['ttwreis_doctors']);
												  
													if (count($ttwreis_dr_docs) !== 1)
													{
														// BC welfare HS
														$bc_welfare_hs_docs = $this->mongo_db
													  ->select(array('_id', 'password', 'salt'))
													  ->where($this->identity_column, $identity)
													  ->limit(1)
													  ->get($this->collections['bc_welfare_health_supervisors']);
													  
													  if(count($bc_welfare_hs_docs) !== 1)
													  {
															// BC Doctors
															$bc_welfare_dr_docs = $this->mongo_db
															->select(array('_id', 'password', 'salt'))
															->where($this->identity_column, $identity)
															->limit(1)
															->get($this->collections['bc_welfare_doctors']);
															if(count($bc_welfare_dr_docs) !== 1)
															{
																
															}
															else
															{
																$result = (object) $ttwreis_hs_docs[0];
																$new    = $this->hash_password($new);

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
																->update($this->collections['bc_welfare_doctors']);
															
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
															}
													  }
													  else
													  {
														  $result = (object) $ttwreis_hs_docs[0];
															$new    = $this->hash_password($new);

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
															->update($this->collections['bc_welfare_health_supervisors']);
														
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
													  }
														
													}
													else
													{
														$result = (object) $ttwreis_dr_docs[0];
														$new    = $this->hash_password($new);

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
													
													}
													
												}
												else
												{
													$result = (object) $ttwreis_hs_docs[0];
													$new    = $this->hash_password($new);

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
												
												}
											}
											else
											{
												$result = (object) $tmreis_dr_docs[0];
												$new    = $this->hash_password($new);

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
											
											}
										}
										else
										{
											$result = (object) $tmreis_hs_docs[0];
											$new    = $this->hash_password($new);

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
										
										}
									}
									else
									{
										$result = (object) $tswreis_dr_docs[0];
										$new    = $this->hash_password($new);

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
									
									}
								}
								else
								{
									$result = (object) $tswreis_hs_docs[0];
									$new    = $this->hash_password($new);

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
								
								}
							}
							else
							{
							    $result = (object) $supportadmindocs[0];
						        $new    = $this->hash_password($new);

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
								->update($this->collections['support_admin']);
							
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
							
							}
						  
					   }
					   else
					   { 
					              	// Generate new password hash
						$result = (object) $subadmindocs[0];
						$new    = $this->hash_password($new);

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
					  
						 
					   }
	                }
	                else
	                {
			                 	// Generate new password hash
						$result = (object) $admindocs[0];
						$new    = $this->hash_password($new);

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
							->update($this->collections['tlstec_admin']);

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

	                  }
	            }        
                else
               { 
             	// Generate new password hash
				$result = (object) $userdocs[0];
				$new    = $this->hash_password($new);

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
			    }


        }	
        else
        {
		// Generate new password hash
		$result = (object) $customerdocs[0];
		$new    = $this->hash_password($new);

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
	    }

		return $updated;
	}


	// ------------------------------------------------------------------------

	/**
	 * Checks identity field for reset password.
	 *
	 * @return bool
	 */
	protected function identity_check_for_reset_password($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		$customeridentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['customers']);
        if($customeridentity)
        {
        	return count($customeridentity) > 0;
        }
        else
        {
          $useridentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['users']);	
          if($useridentity)
          {
        	return count($useridentity) > 0;
          }
          else
          {
          	$adminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['tlstec_admin']);	
            if($adminidentity)
            {
        	   return count($adminidentity) > 0;
            }
			else
			{
			   $subadminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['sub_admins']);	
               if($subadminidentity)
               {
        	      return count($subadminidentity) > 0;
               }
			   else
			   {
			      $supportadminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['support_admin']);	
                  if($supportadminidentity)
                  {
        	        return count($supportadminidentity) > 0;
                  }
				  else
				  {
					  $tswreis_hs_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['panacea_health_supervisors']);	
					  if($tswreis_hs_identity)
					  {
						return count($tswreis_hs_identity) > 0;
					  }
					  else
					  {
						  $tswreis_dr_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['panacea_doctors']);	
						  if($tswreis_dr_identity)
						  {
							return count($tswreis_dr_identity) > 0;
						  }
						  else
						  {
							  $tmreis_hs_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['tmreis_health_supervisors']);	
							  if($tmreis_hs_identity)
							  {
								return count($tmreis_hs_identity) > 0;
							  }
							  else
							  {
								  $tmreis_dr_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['tmreis_doctors']);	
								  if($tmreis_dr_identity)
								  {
									return count($tmreis_dr_identity) > 0;
								  }
								  else
								  {
									  $ttwreis_hs_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['ttwreis_health_supervisors']);	
									  if($ttwreis_hs_identity)
									  {
										return count($ttwreis_hs_identity) > 0;
									  }
									  else
									  {
										  $ttwreis_dr_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['ttwreis_doctors']);	
										  if($ttwreis_dr_identity)
										  {
											return count($ttwreis_dr_identity) > 0;
										  }
										  else
										  {
											  $bc_welfare_hs_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['bc_welfare_health_supervisors']);	
											  if($bc_welfare_hs_identity)
											  {
												return count($bc_welfare_hs_identity) > 0;
											  }
											  else
											  {
												  $bc_welfare_dr_identity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['bc_welfare_doctors']);	
												  if($bc_welfare_dr_identity)
												  {
													return count($bc_welfare_dr_identity) > 0;
												  }
											  }
										  }
									  } 
								  } 
							  } 
						  } 
					  }
				  }
			   
			   
			   }
			
			}

          }
        }
	}


	// ------------------------------------------------------------------------

	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	public function first_level_admin_change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');

		$docs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['support_admin']);

		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_support_admin_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			$this->trigger_events('extra_where');

			// Store the new password and reset the remember code so all remembered instances have to re-login
			$updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['support_admin']);

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

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Changes password.
	 *
	 * @return bool
	 */
	public function second_level_admin_change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');

		$docs = $this->mongo_db
			->select(array('_id', 'password', 'salt'))
			->where($this->identity_column, $identity)
			->limit(1)
			->get($this->collections['support_admin']);

		if (count($docs) !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result      = (object) $docs[0];
		$db_password = $result->password;
		$old         = $this->hash_password_support_admin_db($result->_id, $old);
		$new         = $this->hash_password($new, $result->salt);

		if ($old === TRUE)
		{
			$this->trigger_events('extra_where');

			// Store the new password and reset the remember code so all remembered instances have to re-login
			$updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array('password' => $new, 'remember_code' => NULL))
				->update($this->collections['support_admin']);

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

		$this->set_error('password_change_unsuccessful');
		return FALSE;
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
		$username = new MongoRegex('/^'.$username.'$/i');
		return count($this->mongo_db
			->where('username', $username)
			->get($this->collections['users'])) > 0;
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
		$email = new MongoRegex('/^'.$email.'$/i');
		return count($this->mongo_db
			->where('email', $email)
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

		return count($this->mongo_db
			->where($this->identity_column, $identity)
			->get($this->collections['customers'])) > 0;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks identity field.
	 *
	 * @return bool
	 */
	protected function identity_check_for_remembered_user($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		$customeridentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['customers']);
        if($customeridentity)
        {
        	return count($customeridentity) > 0;
        }
        else
        {
          $useridentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['users']);	
          if($useridentity)
        {
        	return count($useridentity) > 0;
        }
        else
        {
        	$adminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['tlstec_admin']);	
          if($adminidentity)
          {
        	return count($adminidentity) > 0;
          }
		  else
		  {
		     $subadminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['sub_admins']);	
             if($subadminidentity)
             {
        	     return count($subadminidentity) > 0;
             }
			 else
			 {
			    $supportadminidentity = $this->mongo_db->where($this->identity_column, $identity)->get($this->collections['support_admin']);	
				if($supportadminidentity)
				{
        	        return count($supportadminidentity) > 0;
                }
			 }
		  }
        }
        }

	}
	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a forgotten password key in user collection
	 *
	 * @return bool
	 */
	public function forgotten_password_device_user($identity)
	{
		if (empty($identity))
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			return FALSE;
		}

		$this->forgotten_password_code = $this->hash_code(microtime() . $identity);
		$this->trigger_events('extra_where');
		
		$document = $this->mongo_db
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['users']);

			
		// If customer document found
		if (count($document) === 1)
		{
          $updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'forgotten_password_code' => $this->forgotten_password_code,
				'forgotten_password_time' => time()
			))
			->update($this->collections['users']);
        
			  if ($updated)
			   {
				 return $this->forgotten_password_code;
			   }
		 }
		 else
		 {
				 $tswreis_hs_document = $this->mongo_db
				->where($this->identity_column, (string) $identity)
				->limit(1)
				->get($this->collections['panacea_health_supervisors']);

				
				// If document found
				if (count($tswreis_hs_document) === 1)
				{
				  $updated = $this->mongo_db
					->where($this->identity_column, $identity)
					->set(array(
						'forgotten_password_code' => $this->forgotten_password_code,
						'forgotten_password_time' => time()
					))
					->update($this->collections['panacea_health_supervisors']);
				
					  if ($updated)
					   {
						 return $this->forgotten_password_code;
					   }
				}
				else
				{
					 $tswreis_dr_document = $this->mongo_db
					->where($this->identity_column, (string) $identity)
					->limit(1)
					->get($this->collections['panacea_doctors']);

					
					// If document found
					if (count($tswreis_dr_document) === 1)
					{
					  $updated = $this->mongo_db
						->where($this->identity_column, $identity)
						->set(array(
							'forgotten_password_code' => $this->forgotten_password_code,
							'forgotten_password_time' => time()
						))
						->update($this->collections['panacea_doctors']);
					
						  if ($updated)
						   {
							 return $this->forgotten_password_code;
						   }
					}
					else
					{
						 // TMREIS HS
						 $tmreis_hs_document = $this->mongo_db
						->where($this->identity_column, (string) $identity)
						->limit(1)
						->get($this->collections['tmreis_health_supervisors']);

						
						// If document found
						if (count($tmreis_hs_document) === 1)
						{
						  $updated = $this->mongo_db
							->where($this->identity_column, $identity)
							->set(array(
								'forgotten_password_code' => $this->forgotten_password_code,
								'forgotten_password_time' => time()
							))
							->update($this->collections['tmreis_health_supervisors']);
						
							  if ($updated)
							   {
								 return $this->forgotten_password_code;
							   }
						}
						else
						{
							// TMREIS Doctors
							 $tmreis_dr_document = $this->mongo_db
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['tmreis_doctors']);

							
							// If document found
							if (count($tmreis_dr_document) === 1)
							{
							  $updated = $this->mongo_db
								->where($this->identity_column, $identity)
								->set(array(
									'forgotten_password_code' => $this->forgotten_password_code,
									'forgotten_password_time' => time()
								))
								->update($this->collections['tmreis_doctors']);
							
								  if ($updated)
								   {
									 return $this->forgotten_password_code;
								   }
							}
							else
							{
									// TTWREIS HS
									 $ttwreis_hs_document = $this->mongo_db
								->where($this->identity_column, (string) $identity)
								->limit(1)
								->get($this->collections['ttwreis_health_supervisors']);

								
								// If document found
								if (count($ttwreis_hs_document) === 1)
								{
								  $updated = $this->mongo_db
									->where($this->identity_column, $identity)
									->set(array(
										'forgotten_password_code' => $this->forgotten_password_code,
										'forgotten_password_time' => time()
									))
									->update($this->collections['ttwreis_health_supervisors']);
								
									  if ($updated)
									   {
										 return $this->forgotten_password_code;
									   }
								}
								else
								{
										// TTWREIS Doctors
									 $ttwreis_dr_document = $this->mongo_db
									->where($this->identity_column, (string) $identity)
									->limit(1)
									->get($this->collections['ttwreis_doctors']);

									
									// If document found
									if (count($ttwreis_dr_document) === 1)
									{
									  $updated = $this->mongo_db
										->where($this->identity_column, $identity)
										->set(array(
											'forgotten_password_code' => $this->forgotten_password_code,
											'forgotten_password_time' => time()
										))
										->update($this->collections['ttwreis_doctors']);
									
										  if ($updated)
										   {
											 return $this->forgotten_password_code;
										   }
									}
									else
									{
										$bc_welfare_hs_document = $this->mongo_db
										->where($this->identity_column, (string) $identity)
										->limit(1)
										->get($this->collections['bc_welfare_health_supervisors']);

										
										// If document found
										if (count($bc_welfare_hs_document) === 1)
										{
										  $updated = $this->mongo_db
											->where($this->identity_column, $identity)
											->set(array(
												'forgotten_password_code' => $this->forgotten_password_code,
												'forgotten_password_time' => time()
											))
											->update($this->collections['bc_welfare_health_supervisors']);
										
											  if ($updated)
											   {
												 return $this->forgotten_password_code;
											   }
										}
										else
										{
											// TTWREIS Doctors
											 $bc_welfare_dr_document = $this->mongo_db
											->where($this->identity_column, (string) $identity)
											->limit(1)
											->get($this->collections['bc_welfare_doctors']);

											
											// If document found
											if (count($bc_welfare_dr_document) === 1)
											{
											  $updated = $this->mongo_db
												->where($this->identity_column, $identity)
												->set(array(
													'forgotten_password_code' => $this->forgotten_password_code,
													'forgotten_password_time' => time()
												))
												->update($this->collections['bc_welfare_doctors']);
											
												  if ($updated)
												   {
													 return $this->forgotten_password_code;
												   }
											}
										}
									}
									
								}
							}
						}
					}
				}
		 }
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
		$customerdocument = $this->mongo_db
			->select()
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['customers']);

			
		// If customer document found
		if (count($customerdocument) === 1)
		{

		 $updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'forgotten_password_code' => $this->forgotten_password_code,
				'forgotten_password_time' => time()
			))
			->update($this->collections['customers']);
        
			  if ($updated)
			   {
			     $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
			   }
			   else
			   {
			   	 $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			   }
		 }  
		 else 
		 {
		 	 $userdocument = $this->mongo_db
			->select()
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['users']);

               // If user document found
			if (count($userdocument) === 1)
			{

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

		    }
            else
            {
            	$admindocument = $this->mongo_db
			   ->select()
			   ->where($this->identity_column, (string) $identity)
			   ->limit(1)
			   ->get($this->collections['tlstec_admin']);

			   if (count($admindocument) === 1)
			   {

				  $updated = $this->mongo_db
				->where($this->identity_column, $identity)
				->set(array(
					'forgotten_password_code' => $this->forgotten_password_code,
					'forgotten_password_time' => time()
				))
				->update($this->collections['tlstec_admin']);

				if ($updated)
				   {
					 $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
				   }
				else
				   {
					 $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
				   }

                }
			else
			{
			   $subadmindocument = $this->mongo_db
			   ->select()
			   ->where($this->identity_column, (string) $identity)
			   ->limit(1)
			   ->get($this->collections['sub_admins']);
			   
			    if (count($subadmindocument) === 1)
			{

		   	  $updated = $this->mongo_db
			->where($this->identity_column, (string) $identity)
			->set(array(
				'forgotten_password_code' => $this->forgotten_password_code,
				'forgotten_password_time' => time()
			))
			->update($this->collections['sub_admins']);

			if ($updated)
			   {
			     $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
			   }
			   else
			   {
			   	 $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			   }

            }
			else
			{
			 //log_message('debug','$identity=====1325'.print_r($identity,true));
			
			   $supportadmindocument = $this->mongo_db
			   ->select()
			   ->where($this->identity_column, (string) $identity)
			   ->limit(1)
			   ->get($this->collections['support_admin']);
			   
			    if (count($supportadmindocument) === 1)
			{
			
			 //log_message('debug','$supportadmindocument=====1336'.print_r($supportadmindocument,true));

		   	  $updated = $this->mongo_db
			->where($this->identity_column, $identity)
			->set(array(
				'forgotten_password_code' => $this->forgotten_password_code,
				'forgotten_password_time' => time()
			))
			->update($this->collections['support_admin']);
			
			//log_message('debug','$updated=====1346'.print_r($updated,true));

			if ($updated)
			   {
			     $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
			   }
			   else
			   {
			   	 $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			   }

            }
			
			
			}
			
			
			}
		}

	}
		//log_message('debug','$updated=====1368'.print_r($updated,true)); 
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
	public function register($username, $password, $email, $additional_data = array(), $groups = array())
	{
		//$this->trigger_events('pre_register');
		$this->load->config('ion_auth', TRUE);
        $admin_manual_activation = $this->config->item('admin_email_activation','ion_auth');
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
			'username'   => $username,
			'password'   => $password,
			'email'      => $email,
			'ip_address' => $ip_address,
			'created_on' => time(),
			'last_login' => time(),
			'active'     => ($admin_manual_activation === FALSE ? 1 : 0),
			//'active'     => 1,
			'first_name' => $additional_data['first_name'],
			'last_name' => $additional_data['last_name'],
			'company' => $additional_data['first_name'],
			'phone' => $additional_data['phone']
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
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['users'], $data);

		$this->trigger_events('post_register');

		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;
	}
	
	/**
	 * Inserts a user document into api collection.
	 *
	 * @return bool
	 */
	public function register_api($username, $password, $email, $additional_data = array(), $groups = array())
	{
		//$this->trigger_events('pre_register');
		$this->load->config('ion_auth', TRUE);
		$admin_manual_activation = $this->config->item('admin_email_activation','ion_auth');
		
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
			'username'   => $username,
			'password'   => $password,
			'email'      => $email,
			'ip_address' => $ip_address,
			'created_on' => time(),
			'last_login' => time(),
			'active'     => ($admin_manual_activation === FALSE ? 1 : 0),
			//'active'     => 1,
			'first_name' => $additional_data['first_name'],
			'last_name' => $additional_data['last_name'],
			'company' => $additional_data['first_name'],
			'phone' => $additional_data['phone']
		);
	
			// Store salt in document?
			if ($this->store_salt)
			{
			$data['salt'] = $salt;
			}
	
			// Filter out any data passed that doesn't have a matching column in the
			// user document and merge the set user data with the passed additional data
			$data = array_merge($this->_filter_data($this->collections['api_details'], $additional_data), $data);
	
			$this->trigger_events('extra_set');
		// Insert new document and store the _id value
			$id = $this->mongo_db->insert($this->collections['users'], $data);
	
			$this->trigger_events('post_register');
	
		// Return new document _id or FALSE on failure
			return isset($id) ? $id : FALSE;
	}

//*****************************TLSTEC CUSTOMER***************************************************************************
	
	public function customer_email_check($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
		$email = new MongoRegex('/^'.$email.'$/i');
		return count($this->mongo_db
			->where('email', $email)
			->get($this->collections['customers'])) > 0;
	}
	
	public function customer_comp_name_check($comp_name = '')
	{
		//log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($comp_name,true));
		$this->trigger_events('comp_name_check');
	
		if (empty($comp_name))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
		$comp_name = new MongoRegex('/^'.$comp_name.'$/i');
		//log_message('debug','ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($comp_name,true));
		return count($this->mongo_db
				->where('company_name', $comp_name)
				->get($this->collections['customers'])) > 0;
	}
	
	public function signup($companyname, $companyaddress, $contactperson, $additional_data = array())
	{
		$this->trigger_events('pre_register');
		$manual_activation = $this->config->item('manual_activation', 'ion_auth');

		// Check if email already exists
		if ($this->identity_column == 'email' && $this->customer_email_check($additional_data['email']))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
		// Check if company already exists
		if ($this->customer_comp_name_check($companyname))
		{
			$this->set_error('account_creation_duplicate_company');
			return FALSE;
		}
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		
		if($additional_data['plan'] == "Diamond")
		{
			$plan_exp = date('Y-m-d',strtotime('+12 months'));
		}
		elseif($additional_data['plan'] == "Gold")
		{
			$plan_exp = date('Y-m-d',strtotime('+6 months'));
		}
		elseif($additional_data['plan'] == "Silver") 
		{
			$plan_exp = date('Y-m-d',strtotime('+3 month'));
		}
		elseif($additional_data['plan'] == "Bronze") 
		{
			$plan_exp = date('Y-m-d',strtotime('+1 month'));
		}
		// New user document
		$data = array(
		    'company_name'     => $companyname,
			'company_address'  => $companyaddress,
			'contact_person'   => $contactperson,
			'mobile_number'    => $additional_data['mobile'],
			'email'            => $additional_data['email'],
			'company_website'  => $additional_data['companywebsite'],
			'username'         => $additional_data['username'],
			'password'         => $this->hash_password($additional_data['password'], $salt),
			'confirm_password' => $this->hash_password($additional_data['confirmpassword'], $salt),
			'display_company_name' => $additional_data['display_company_name'],
			'plan'             => $additional_data['plan'],
			'registered_on'    => date('Y-m-d'),
			'plan_expiry'      => $plan_exp,
			'ip_address'       => $ip_address,
			'last_login'       => date('Y-m-d H:i:s'),
			'activation_code'  => $additional_data['activation_code'],
			'active'           => ($manual_activation === FALSE ? 1 : 0),
			'first_time_user'  => 1
		);

		 if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}
		// Filter out any data passed that doesn't have a matching column in the
		// user document and merge the set user data with the passed additional data
		$data = array_merge($this->_filter_data($this->collections['customers'], $additional_data), $data);

		$this->trigger_events('extra_set');
		
		 
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['customers'], $data);


		$this->trigger_events('post_register');

		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;

	}
	
	
	public function signup_api($additional_data = array())
	{
		$this->trigger_events('pre_register');
		$manual_activation = $this->config->item('manual_activation', 'ion_auth');
	
		// Check if email already exists
		if ($this->identity_column == 'email' && $this->customer_email_check($additional_data['email']))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}
		// Check if company already exists
		if ($this->customer_comp_name_check($additional_data['company_name']))
		{
			$this->set_error('account_creation_duplicate_company');
			return FALSE;
		}
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$salt       = $this->store_salt ? $this->salt() : FALSE;
	
		// New user document
		$data = array(
				'company_name'     => $additional_data['company_name'],
				'type'             => $additional_data['type'],
				'collection'       => $additional_data['company_name'],
				'company_address'  => $additional_data['company_address'],
				'mobile_number'    => $additional_data['mobile'],
				'email'            => $additional_data['email'],
				'company_website'  => $additional_data['companywebsite'],
				'username'         => $additional_data['username'],
				'customer'		   => $additional_data['customer'],
				'password'         => md5($additional_data['password']),
				'display_company_name' => $additional_data['display_company_name'],
				'registered_on'    => date('Y-m-d'),
				'ip_address'       => $ip_address,
				'last_login'       => time(),
				'activation_code'  => $additional_data['activation_code'],
				'api_key'		   => $additional_data['api_key'],
				'access'		   => $additional_data['access'],
				'active'           => ($manual_activation === FALSE ? 1 : 0),
				'first_time_user' => 1
		);
	
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}
		// Filter out any data passed that doesn't have a matching column in the
		// user document and merge the set user data with the passed additional data
		$data = array_merge($this->_filter_data($this->collections['api_details'], $additional_data), $data);
	
		$this->trigger_events('extra_set');
	
			
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['api_details'], $data);
	
		$this->trigger_events('post_register');
	
		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;
	}
//**********************************************************************************************************************


  public function hash_password_user_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['users']);
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

	/*Power of ten*/
	  public function power_of_ten_hash_password_user_db($id, $password, $use_sha1_override = FALSE)
		{
			if (empty($id) || empty($password))
			{
				return FALSE;
			}

			$this->trigger_events('extra_where');

			$document = $this->mongo_db
				->select(array('password', 'salt'))
				->where('_id', new MongoId($id))
				->limit(1)
				->get('power_of_ten_users');
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

	/*Power of ten district coordinator*/
	  public function power_of_ten_hash_password_dc_db($id, $password, $use_sha1_override = FALSE)
		{
			if (empty($id) || empty($password))
			{
				return FALSE;
			}

			$this->trigger_events('extra_where');

			$document = $this->mongo_db
				->select(array('password', 'salt'))
				->where('_id', new MongoId($id))
				->limit(1)
				->get('power_of_ten_district_coordinators');
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
	
	public function hash_password_panacea_hs_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['panacea_health_supervisors']);
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
	
		public function hash_password_tmreis_hs_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['tmreis_health_supervisors'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	
		public function hash_password_ttwreis_doctor_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['ttwreis_doctors'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}

	public function hash_password_panacea_cc_normal_request ($id, $password, $use_sha1_override = FALSE) {
			if (empty ( $id ) || empty ( $password )) {
				return FALSE;
			}
		
			$this->trigger_events ( 'extra_where' );
		
			$document = $this->mongo_db->select ( array (
					'password',
					'salt'
			) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['panacea_cc_normal_request'] );
			$hash_password_db = ( object ) $document [0];
		
			if (count ( $document ) !== 1) {
				return FALSE;
			}
		
			// Bcrypt
			if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
				if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
					return TRUE;
				}
					
				return FALSE;
			}
		
			// SHA1
			if ($this->store_salt) {
				$db_password = sha1 ( $password . $hash_password_db->salt );
			} else {
				$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
				$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
			}
		
			return ($db_password == $hash_password_db->password);
		}
	
	
	public function hash_password_panacea_doctors_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	//log_message('debug','2222222222222222222222222222222222222222222222222222222222222222222222222'.print_r($password,true));
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['panacea_doctors'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}

	public function hash_password_tmreis_doctor_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['tmreis_doctors'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	
	public function hash_password_bc_welfare_doctors_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	//log_message('debug','2222222222222222222222222222222222222222222222222222222222222222222222222'.print_r($password,true));
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['bc_welfare_doctors'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	
	
	public function hash_password_field_agent_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['field_agents']);
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

	public function hash_password_panacea_pc_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	//log_message('debug','2222222222222222222222222222222222222222222222222222222222222222222222222'.print_r($password,true));
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['panacea_schools'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	//**********************************************************************************************************************
	
	
	
	// ------------------------------------------------------------------------
	
	 public function hash_password_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$document = $this->mongo_db
			->select(array('password', 'salt'))
			->where('_id', new MongoId($id))
			->limit(1)
			->get($this->collections['tlstec_admin']);
			
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
	 * RHSO ADMIN
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author bhanu
	 */
	
	public function hash_password_rhso_admins($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['rhso_admins']);
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
	//-----------------------------------------------
	
	
	/*------------------RHSO ADMIN-----------------------------*/
	public function hash_password_rhso_users($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['rhso_users']);
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
	
	//==============================================
	
	public function hash_password_rhso_admins_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['rhso_users'] );
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}

	public function hash_password_cro_admins_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['cro_collection'] );
		log_message('error','documenttttttt======10124'.print_r($document,TRUE));
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}

	public function hash_password_rco_admins_db($id, $password, $use_sha1_override = FALSE) {
		if (empty ( $id ) || empty ( $password )) {
			return FALSE;
		}
	
		$this->trigger_events ( 'extra_where' );
	
		$document = $this->mongo_db->select ( array (
				'password',
				'salt'
		) )->where ( '_id', new MongoId ( $id ) )->limit ( 1 )->get ( $this->collections ['rco_collection'] );
		log_message('error','documenttttttt======10124'.print_r($document,TRUE));
		$hash_password_db = ( object ) $document [0];
	
		if (count ( $document ) !== 1) {
			return FALSE;
		}
	
		// Bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcrypt->verify ( $password, $hash_password_db->password )) {
				return TRUE;
			}
				
			return FALSE;
		}
	
		// SHA1
		if ($this->store_salt) {
			$db_password = sha1 ( $password . $hash_password_db->salt );
		} else {
			$salt = substr ( $hash_password_db->password, 0, $this->salt_length );
			$db_password = $salt . substr ( sha1 ( $salt . $password ), 0, - $this->salt_length );
		}
	
		return ($db_password == $hash_password_db->password);
	}
	//---------------------------------------
	/**
	 * Checks credentials and logs the passed user in if possible.
	 *
	 * @return bool
	 */
	public function patient_login($identity, $password)
	{
		if (empty($identity) || empty($password))
		{
			//$this->set_error('login_unsuccessful');
			return FALSE;
		}
	
		//$this->trigger_events('extra_where');
		$currentdate = date("Y-m-d");
		$document = $this->mongo_db
		->whereLike('unique_id', $identity)
		->limit(1)
		->get($this->collections['form_users']);
	
		// If customer document found
		if (count($document) === 1)
		{
			$user = (object) $document[0];
			
			$password = $this->hash_password_patient_db($user->_id, $password);
			
			if ($password === TRUE)
			{
				// Set user session data
				$session_data = array(
						'identity'       =>  $user->unique_id,
						'unique_id'       => $user->unique_id,
						'user_id'        => $user->_id->{'$id'},
						'company'        => $user->company_name,
				);
	
				//$this->input->set_cookie('customers', $session_data, 3600*2);
	
				$cus_url = base_url().$user->company_name;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/auth/set_patient_session/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
				return TRUE;
			}
		}
		//$this->trigger_events('post_login_unsuccessful');
		//$this->set_error('login_unsuccessful');
		return FALSE;
	}
	
	
	// ------------------------------------------------------------------------



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

		$this->trigger_events('extra_where');
        $currentdate = date("Y-m-d");
		$document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['customers']);

			
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
				
				if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
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
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'expiry'         => $user->plan_expiry
                );
				
                // Clean login attempts, also update last login time
                $this->update_last_login($user->_id);
				$this->clear_login_attempts($identity);

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_me($user->email);
                }
                $cus_url = base_url().$user->company_name;
			    $h = json_encode($session_data);
			    $str = base64_encode($h);
				
				$this->input->set_cookie('language', 'english', 3600*2);
				
			    redirect($cus_url.'/index.php/auth/session/'.$str);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
				
			}
		}
		else 
		{
		   $userdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','subscription_end'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['users']);
			
			// If user document found
            if(count($userdocument) === 1)
			{
				$user = (object) $userdocument[0];
				$password = $this->hash_password_user_db($user->_id,$password);
				if ($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						$this->trigger_events('post_login_unsuccessful');
						$this->set_error('login_unsuccessful_not_active');
						return FALSE;
					}
					
					if($user->subscription_end == $currentdate || $user->subscription_end < $currentdate )
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
						'old_last_login' => $user->last_login,
						'company'        => $user->company
					);
					
					// Clean login attempts, also update last login time
					$this->update_user_last_login($user->_id);
					$this->clear_login_attempts($identity);

					// Check whether we should remember the user
					if ($remember && $this->config->item('remember_users', 'ion_auth'))
					{
						$this->remember_me($user->email);
					}
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					
					$this->input->set_cookie('language', 'english', 3600*2);
					
					redirect($cus_url.'/index.php/auth/session/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
					return TRUE;
			    }
		    }
			else
			{
				$admindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login'))
				->where($this->identity_column, (string) $identity)
				->limit(1)
				->get($this->collections['tlstec_admin']);
				
				if(count($admindocument) === 1)
				{
					$user = (object) $admindocument[0];
					$password = $this->hash_password_admin_db($user->_id, $password);
					if ($password === TRUE)
					{
						// Not yet activated?
						if ($user->active == 0)
						{
							$this->trigger_events('post_login_unsuccessful');
							$this->set_error('login_unsuccessful_not_active');
							return FALSE;
						}

						// Set user session data
						$session_data = array(
							'identity'       => $user->{$this->identity_column},
							'username'       => $user->username,
							'email'          => $user->email,
							'user_id'        => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
						);

						set_cookie(array(
						'name'   => 'admin_identity',
						'value'  => $user->email
					   ));

						// Clean login attempts, also update last login time
						$this->update_admin_last_login($user->_id);
						$this->clear_login_attempts($identity);

						// Check whether we should remember the user
						if ($remember && $this->config->item('remember_users', 'ion_auth'))
						{
							$this->remember_me($user->email);
						}
						$cus_url = base_url();
						$h = json_encode($session_data);
						$str = base64_encode($h);
						
						$this->input->set_cookie('language', 'english', 3600*2);
						
						redirect($cus_url.'/index.php/auth/session/'.$str);
						$this->trigger_events(array('post_login', 'post_login_successful'));
						$this->set_message('login_successful');
						return TRUE;
					}
				}
                else
                {
            	    // ENTERPRISE SUB ADMIN
            		$subadmindocument = $this->mongo_db
            		->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','plan_expiry','registered_on','plan'))
            		->where($this->identity_column, (string) $identity)
            		->limit(1)
            		->get($this->collections['sub_admins']);
            			
            		// If sub admin document found
            		if (count($subadmindocument) === 1)
            		{
            			$user = (object) $subadmindocument[0];
            			$password = $this->hash_password_sub_admin_db($user->_id, $password);
            			if ($password === TRUE)
            			{
            				// Not yet activated?
            				if ($user->active == 0)
            				{
            					$this->trigger_events('post_login_unsuccessful');
            					$this->set_error('login_unsuccessful_not_active');
            					return FALSE;
            				}
							
							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
				            {
							  $this->trigger_events('post_login_unsuccessful');
							  $this->set_error('login_unsuccessful_plan_expired');
							  return FALSE;
				            }	
            		
            				// Set sub admin session data
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
            		
            				// Clean login attempts, also update last login time
            				$this->update_last_login($user->_id);
            				$this->clear_login_attempts($identity);
            		
            				// Check whether we should remember the user
            				if ($remember && $this->config->item('remember_users', 'ion_auth'))
            				{
            					$this->remember_me($user->email);
            				}
            				$cus_url = base_url().$user->company;
            				$h = json_encode($session_data);
            				$str = base64_encode($h);
							
							$this->input->set_cookie('language', 'english', 3600*2);
							
            				redirect($cus_url.'/index.php/auth/session/'.$str);
            				$this->trigger_events(array('post_login', 'post_login_successful'));
            				$this->set_message('login_successful');
            		
            				return TRUE;
            			}
            		}
            		else
            		{
            			// TLSTEC SUPPORT ADMIN
            			$supportadmindocument = $this->mongo_db
            			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','level'))
            			->where($this->identity_column, (string) $identity)
            			->limit(1)
            			->get($this->collections['support_admin']);
            			
						// If support admin document found						
            			if (count($supportadmindocument) === 1)
            			{
            				$user = (object) $supportadmindocument[0];
            				$password = $this->hash_password_support_admin_db($user->_id, $password);
            				if ($password === TRUE)
            				{
            					// Not yet activated?
            					if ($user->active == 0)
            					{
            						$this->trigger_events('post_login_unsuccessful');
            						$this->set_error('login_unsuccessful_not_active');
            						return FALSE;
            					}
            			
            					// Set user session data
            					$session_data = array(
            							'identity'       => $user->{$this->identity_column},
            							'username'       => $user->username,
            							'email'          => $user->email,
            							'user_id'        => $user->_id->{'$id'},
            							'old_last_login' => $user->last_login,
            							'company'        => $user->company,
            							'level'          => $user->level
            					);
            			
            					$this->session->set_userdata("customer",$session_data);
            			
            					// Clean login attempts, also update last login time
            					$this->update_support_admin_last_login($user->_id);
            					$this->clear_login_attempts($identity);
            			
            					// Check whether we should remember the user
            					if ($remember && $this->config->item('remember_users', 'ion_auth'))
            					{
            						$this->remember_me($user->email);
            					}
            					$this->trigger_events(array('post_login', 'post_login_successful'));
            					$this->set_message('login_successful');
            			        return TRUE;
            				}
            					
            			}
            			else{
            				
            				$userdocument = $this->mongo_db
            				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','plan_expiry'))
            				->where($this->identity_column, (string) $identity)
            				->limit(1)
            				->get($this->collections['field_agents']);
            					
            				// If user document found
            				if(count($userdocument) === 1)
            				{
            					$user = (object) $userdocument[0];
            					$password = $this->hash_password_field_agent_db($user->_id,$password);
            					if ($password === TRUE)
            					{
            						// Not yet activated?
            						if ($user->active == 0)
            						{
            							$this->trigger_events('post_login_unsuccessful');
            							$this->set_error('login_unsuccessful_not_active');
            							return FALSE;
            						}
            							
	            					if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
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
            								'old_last_login' => $user->last_login,
            								'company'        => $user->company
            						);
            							
            						// Clean login attempts, also update last login time
            						$this->update_field_agent_last_login($user->_id);
            						$this->clear_login_attempts($identity);
            				
            						// Check whether we should remember the user
            						if ($remember && $this->config->item('remember_users', 'ion_auth'))
            						{
            							$this->remember_field_agent($user->email);
            						}
            						$cus_url = base_url().$user->company;
            						$h = json_encode($session_data);
            						$str = base64_encode($h);
            							
            						$this->input->set_cookie('language', 'english', 3600*2);
            							
            						redirect($cus_url.'/index.php/auth/session_field_agent/'.$str);
            						$this->trigger_events(array('post_login', 'post_login_successful'));
            						$this->set_message('login_successful');
            						return TRUE;
            					}
            				}else{
            					//======== PANACEA Adminsssssssssssssssssssssssss
            					$document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get($this->collections['panacea_admins']);

            					// If customer document found
            					if (count($document) === 1)
            					{
            						$user = (object) $document[0];
            						
            						$password = $this->hash_password_panacea_admins($user->_id, $password);

            						
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "PADMIN"
            							);


            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            							
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_panacea_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
            					else{
            					//======== Power Of Ten Adminsssssssssssssssssssssssss
            					$document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get('poweroften_admins');
            					
            						
            					// If customer document found
            					if (count($document) === 1)
            					{
            						$user = (object) $document[0];
            							
            						$password = $this->hash_password_poweroften_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "PADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_poweroften_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
            					else{
            					//======== Power Of Ten Adminsssssssssssssssssssssssss
            					$document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get('poweroften_dar_admins');
            					
            						
            					// If customer document found
            					if (count($document) === 1)
            					{
            						$user = (object) $document[0];
            							
            						$password = $this->hash_password_poweroften_dar_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "DAR_ADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_poweroften_dar_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
            					else
            					{
            						//======== L3 Adminsssssssssssssssssssssssss
            					$llldocument = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get('l3_admins');
            					
            						
            					// If customer document found
            					if (count($llldocument) === 1)
            					{
            						$user = (object) $llldocument[0];
            							
            						$password = $this->hash_password_l3_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "PADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_l3_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
            					else
            					{
            						//======== Tswreis Sports Adminsssssssssssssssssssssssss
            					$tswreissportsdocument = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get('tswreis_sports_admins');
            					
            						
            					// If customer document found
            					if (count($tswreissportsdocument) === 1)
            					{
            						$user = (object) $tswreissportsdocument[0];
            							
            						$password = $this->hash_password_tswreis_sports_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "PADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_tswreis_sports_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
								else
								{
									// SOCIAL WELFARE HS
								$hsdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['panacea_health_supervisors']);
								
								//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($hsdocument) === 1)
            					{
            						$user = (object) $hsdocument[0];
            							
            						$password = $this->hash_password_panacea_health_supervisor($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
										$school_details = $this->mongo_db->select(array('school_name','school_code'))->where('school_code',$user->school_code)->get( $this->collections ['panacea_schools'] );
										//log_message("debug","school_details==============4091".print_r($school_details,true));
										
										if($school_details){
											// Set user session data with school code
											$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS",									
												'school_name'    => $school_details[0]['school_name']
            							);
										}else{
											// Set user session data
											$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS"
            							);
										}
            							
            							
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_panacea_hs/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else
								{
							
							      // MINORITY WELFARE HS
								$mhsdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['tmreis_health_supervisors']);
								
								//log_message('debug','$hsdocument======2779====='.print_r($mhsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($mhsdocument) === 1)
            					{
            						$user = (object) $mhsdocument[0];
            							
            						$password = $this->hash_password_tmreis_health_supervisor($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
											$school_details = $this->mongo_db->select(array('school_code','school_name'))->where('school_code',$user->school_code )->get($this->collections['tmreis_schools']);
								if($school_details){
												// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS",
												'school_name'    => $school_details[0]['school_name']
            							);
											}
											else
											{// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS"
            							);
										}
            							           					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_tmreis_hs/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
							    else
								{
							      // TRIBAL WELFARE HS
								$thsdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['ttwreis_health_supervisors']);
								
								//log_message('debug','$hsdocument======2779====='.print_r($thsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($thsdocument) === 1)
            					{
            						$user = (object) $thsdocument[0];
            							
            						$password = $this->hash_password_ttwreis_health_supervisor($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
										$school_details = $this->mongo_db->select(array('school_code','school_name'))->where('school_code',$user->school_code)->get( $this->collections ['ttwreis_schools']);
										if($school_details){
											// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS",
												'school_name'    => $school_details[0]['school_name']
            							);
										}else{
											// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS"
            							);
										}
            					
            							
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_ttwreis_hs/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
							    else
								{
							
							       // PANACEA COMMAND CENTER USER
									$ccdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['panacea_cc']);
            					
            						
            					// If customer document found
            					if (count($ccdocument) === 1)
            					{
            						$user = (object) $ccdocument[0];
            							
            						$password = $this->hash_password_panacea_ccuser($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
												'user_type'      => "CCUSER"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_panacea_cc/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}else {
										// ======== TTWERIS Admin
										$document = $this->mongo_db->select ( array (
												$this->identity_column,
												'_id',
												'username',
												'email',
												'password',
												'active',
												'last_login',
												'company_name',
												'plan_expiry',
												'registered_on',
												'plan' 
										) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_admins'] );
										
										// If customer document found
										if (count ( $document ) === 1) {
											$user = ( object ) $document [0];
											
											$password = $this->hash_password_ttwreis_admins ( $user->_id, $password );
											if ($password === TRUE) {
												// Not yet activated?
												if ($user->active == 0) {
													$this->trigger_events ( 'post_login_unsuccessful' );
													$this->set_error ( 'login_unsuccessful_not_active' );
													return FALSE;
												}
												
												// if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
												// {
												// $this->trigger_events('post_login_unsuccessful');
												// $this->set_error('login_unsuccessful_plan_expired');
												// return FALSE;
												// }
												
												// Set user session data
												$session_data = array (
														'identity' => $user->{$this->identity_column},
														'username' => $user->username,
														'email' => $user->email,
														'user_id' => $user->_id->{'$id'},
														'old_last_login' => $user->last_login,
														'company' => $user->company_name,
														'plan' => $user->plan,
														'registered' => $user->registered_on,
														'expiry' => $user->plan_expiry,
														'user_type'      => "TADMIN"
												);
												
												// Clean login attempts, also update last login time
												// $this->update_last_login($user->_id);
												// $this->clear_login_attempts($identity);
												
												// Check whether we should remember the user
												if ($remember && $this->config->item ( 'remember_users', 'ion_auth' )) {
													$this->remember_me ( $user->email );
												}
												$cus_url = base_url () . $user->company_name;
												$h = json_encode ( $session_data );
												$str = base64_encode ( $h );
												
												$this->input->set_cookie ( 'language', 'english', 3600 * 2 );
												
												redirect ( $cus_url . '/index.php/auth/session_ttwreis_mgmt/' . $str );
												$this->trigger_events ( array (
														'post_login',
														'post_login_successful' 
												) );
												$this->set_message ( 'login_successful' );
												
												return TRUE;
											}
										} else {
										// ======== TTWERIS command center
										$document = $this->mongo_db->select ( array (
												$this->identity_column,
												'_id',
												'username',
												'email',
												'password',
												'active',
												'last_login',
												'company_name',
												'plan_expiry',
												'registered_on',
												'plan' 
										) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_cc'] );
										
										// If customer document found
										if (count ( $document ) === 1) {
											$user = ( object ) $document [0];
											
											$password = $this->hash_password_ttwreis_cc ( $user->_id, $password );
											if ($password === TRUE) {
												// Not yet activated?
												if ($user->active == 0) {
													$this->trigger_events ( 'post_login_unsuccessful' );
													$this->set_error ( 'login_unsuccessful_not_active' );
													return FALSE;
												}
												
												// if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
												// {
												// $this->trigger_events('post_login_unsuccessful');
												// $this->set_error('login_unsuccessful_plan_expired');
												// return FALSE;
												// }
												
												// Set user session data
												$session_data = array (
														'identity' => $user->{$this->identity_column},
														'username' => $user->username,
														'email' => $user->email,
														'user_id' => $user->_id->{'$id'},
														'old_last_login' => $user->last_login,
														'company' => $user->company_name,
														'plan' => $user->plan,
														'registered' => $user->registered_on,
														'expiry' => $user->plan_expiry,
														'user_type'      => "CCUSER"
												);
												
												// Clean login attempts, also update last login time
												// $this->update_last_login($user->_id);
												// $this->clear_login_attempts($identity);
												
												// Check whether we should remember the user
												if ($remember && $this->config->item ( 'remember_users', 'ion_auth' )) {
													$this->remember_me ( $user->email );
												}
												$cus_url = base_url () . $user->company_name;
												$h = json_encode ( $session_data );
												$str = base64_encode ( $h );
												
												$this->input->set_cookie ( 'language', 'english', 3600 * 2 );
												
												redirect ( $cus_url . '/index.php/auth/session_ttwreis_cc/' . $str );
												$this->trigger_events ( array (
														'post_login',
														'post_login_successful' 
												) );
												$this->set_message ( 'login_successful' );
												
												return TRUE;
											}
										} else {
										// ======== tmreis Admin
										$document = $this->mongo_db->select ( array (
												$this->identity_column,
												'_id',
												'username',
												'email',
												'password',
												'active',
												'last_login',
												'company_name',
												'plan_expiry',
												'registered_on',
												'plan' 
										) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['tmreis_admins'] );
										
										// If customer document found
										if (count ( $document ) === 1) {
											$user = ( object ) $document [0];
											
											$password = $this->hash_password_ttwreis_admins ( $user->_id, $password );
											if ($password === TRUE) {
												// Not yet activated?
												if ($user->active == 0) {
													$this->trigger_events ( 'post_login_unsuccessful' );
													$this->set_error ( 'login_unsuccessful_not_active' );
													return FALSE;
												}
												
												// if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
												// {
												// $this->trigger_events('post_login_unsuccessful');
												// $this->set_error('login_unsuccessful_plan_expired');
												// return FALSE;
												// }
												
												// Set user session data
												$session_data = array (
														'identity' => $user->{$this->identity_column},
														'username' => $user->username,
														'email' => $user->email,
														'user_id' => $user->_id->{'$id'},
														'old_last_login' => $user->last_login,
														'company' => $user->company_name,
														'plan' => $user->plan,
														'registered' => $user->registered_on,
														'expiry' => $user->plan_expiry,
														'user_type'      => "MADMIN"
												);
												
												// Clean login attempts, also update last login time
												// $this->update_last_login($user->_id);
												// $this->clear_login_attempts($identity);
												
												// Check whether we should remember the user
												if ($remember && $this->config->item ( 'remember_users', 'ion_auth' )) {
													$this->remember_me ( $user->email );
												}
												$cus_url = base_url () . $user->company_name;
												$h = json_encode ( $session_data );
												$str = base64_encode ( $h );
												
												$this->input->set_cookie ( 'language', 'english', 3600 * 2 );
												
												redirect ( $cus_url . '/index.php/auth/session_tmreis_mgmt/' . $str );
												$this->trigger_events ( array (
														'post_login',
														'post_login_successful' 
												) );
												$this->set_message ( 'login_successful' );
												
												return TRUE;
											}
										} else {
										// ======== tmreis command center
										$document = $this->mongo_db->select ( array (
												$this->identity_column,
												'_id',
												'username',
												'email',
												'password',
												'active',
												'last_login',
												'company_name',
												'plan_expiry',
												'registered_on',
												'plan' 
										) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['tmreis_cc'] );
										
										// If customer document found
										if (count ( $document ) === 1) {
											$user = ( object ) $document [0];
											
											$password = $this->hash_password_tmreis_cc ( $user->_id, $password );
											if ($password === TRUE) {
												// Not yet activated?
												if ($user->active == 0) {
													$this->trigger_events ( 'post_login_unsuccessful' );
													$this->set_error ( 'login_unsuccessful_not_active' );
													return FALSE;
												}
												
												// if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
												// {
												// $this->trigger_events('post_login_unsuccessful');
												// $this->set_error('login_unsuccessful_plan_expired');
												// return FALSE;
												// }
												
												// Set user session data
												$session_data = array (
														'identity' => $user->{$this->identity_column},
														'username' => $user->username,
														'email' => $user->email,
														'user_id' => $user->_id->{'$id'},
														'old_last_login' => $user->last_login,
														'company' => $user->company_name,
														'plan' => $user->plan,
														'registered' => $user->registered_on,
														'expiry' => $user->plan_expiry,
														'user_type' => "CCUSER"
												);
												
												// Clean login attempts, also update last login time
												// $this->update_last_login($user->_id);
												// $this->clear_login_attempts($identity);
												
												// Check whether we should remember the user
												if ($remember && $this->config->item ( 'remember_users', 'ion_auth' )) {
													$this->remember_me ( $user->email );
												}
												$cus_url = base_url () . $user->company_name;
												$h = json_encode ( $session_data );
												$str = base64_encode ( $h );
												
												$this->input->set_cookie ( 'language', 'english', 3600 * 2 );
												
												redirect ( $cus_url . '/index.php/auth/session_tmreis_cc/' . $str );
												$this->trigger_events ( array (
														'post_login',
														'post_login_successful' 
												) );
												$this->set_message ( 'login_successful' );
												
												return TRUE;
											}
										}
										else
										{
										   // PANACEA ADMIN VIEWERS
							$panacea_viewer_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['panacea_viewers']);
							
							
            				
							if (count($panacea_viewer_admin_document) === 1)
							{
								$user = (object) $panacea_viewer_admin_document[0];
								$password = $this->hash_password_panacea_viewers_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "PVIEWER"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_panacea_viewers/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else
							{
						         // TMREIS ADMIN VIEWERS
							$tmreis_viewer_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['tmreis_viewers']);
							
							
            				
							if (count($tmreis_viewer_admin_document) === 1)
							{
								$user = (object) $tmreis_viewer_admin_document[0];
								$password = $this->hash_password_tmreis_viewers_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "MVIEWER"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_tmreis_viewers/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else
							{
						         // TTWREIS ADMIN VIEWERS
							$ttwreis_viewer_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['ttwreis_viewers']);
							
							
            				
							if (count($ttwreis_viewer_admin_document) === 1)
							{
								$user = (object) $ttwreis_viewer_admin_document[0];
								$password = $this->hash_password_ttwreis_viewers_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "TVIEWER"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_ttwreis_viewers/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else{
            					//======== import screning data login =====
            					$document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get($this->collections['screening_import_admin']);
            					
            						
            					// If customer document found
            					if (count($document) === 1)
            					{
            						$user = (object) $document[0];
            							
            						$password = $this->hash_password_screening_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "Screeing ADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_screening_admin/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
							else
							{
                               // PANACEA SANITATION ADMIN
								$panacea_sanitation_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['panacea_sanitation_admins']);
							
							
            				
							if (count($panacea_sanitation_admin_document) === 1)
							{
								$user = (object) $panacea_sanitation_admin_document[0];
								$password = $this->hash_password_panacea_sanitation_admin_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "PSANIADMIN"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_panacea_sanitation_admin/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else
							{
								// TMREIS SANITATION ADMIN
								$tmreis_sanitation_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['tmreis_sanitation_admins']);
							
							
            				
							if (count($tmreis_sanitation_admin_document) === 1)
							{
								$user = (object) $tmreis_sanitation_admin_document[0];
								$password = $this->hash_password_tmreis_sanitation_admin_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "TSANIADMIN"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_tmreis_sanitation_admin/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else
							{
								// TTWREIS SANITATION ADMIN
								$ttwreis_sanitation_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['ttwreis_sanitation_admins']);
							
							
            				
							if (count($ttwreis_sanitation_admin_document) === 1)
							{
								$user = (object) $ttwreis_sanitation_admin_document[0];
								$password = $this->hash_password_ttwreis_sanitation_admin_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "TTSANIADMIN"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company_name;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_ttwreis_sanitation_admin/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
							else{
								//======== BC Welfare Adminsssssssssssssssssssssssss
            					$bc_welfare_document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get($this->collections['bc_welfare_admins']);
            					
            						
            					// If customer document found
            					if (count($bc_welfare_document) === 1)
            					{
            						$user = (object) $bc_welfare_document[0];
            							
            						$password = $this->hash_password_bc_welfare_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "BCADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_bc_welfare_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
								else
								{
									// BC WELFARE HS
								$hsdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['bc_welfare_health_supervisors']);
								
								//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($hsdocument) === 1)
            					{
            						$user = (object) $hsdocument[0];
            							
            						$password = $this->hash_password_bc_welfare_health_supervisor($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
										$school_details = $this->mongo_db->select(array('school_name','school_code'))->where('school_code',$user->school_code)->get( $this->collections ['bc_welfare_schools'] );
										//log_message("debug","school_details==============4091".print_r($school_details,true));
										
										if($school_details){
											// Set user session data with school code
											$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS",
												'school_name'    => $school_details[0]['school_name']
            							);
										}else{
											// Set user session data
											$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->hs_name,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "HS"
            							);
										}
            							
            							
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_bc_welfare_hs/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else
								{
									// PANACEA COMMAND CENTER USER
									$ccdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['bc_welfare_cc']);
            					
            						
            					// If customer document found
            					if (count($ccdocument) === 1)
            					{
            						$user = (object) $ccdocument[0];
            							
            						$password = $this->hash_password_bc_welfare_ccuser($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
												'user_type'      => "CCUSER"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_bc_welfare_cc/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else
								{
									//======== RHSO Adminsssssssssssssssssssssssss
            					$document = $this->mongo_db
            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
            					->where($this->identity_column, (string) $identity)
            					->limit(1)
            					->get($this->collections['rhso_admins']);
            					//log_message("debug","document=============3994".print_r($document,true));
            						
            					// If customer document found
            					if (count($document) === 1)
            					{
            						$user = (object) $document[0];
            							
            						$password = $this->hash_password_rhso_admins($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
//             							{
//             								$this->trigger_events('post_login_unsuccessful');
//             								$this->set_error('login_unsuccessful_plan_expired');
//             								return FALSE;
//             							}
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->{$this->identity_column},
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
            									'plan'           => $user->plan,
            									'registered'     => $user->registered_on,
            									'expiry'         => $user->plan_expiry,
												'user_type'      => "RHSO Admin"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
										//log_message("debug","cus_url==============4045".print_r($cus_url,true));
										//log_message("debug","session_data==============4046".print_r($session_data,true));
										
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_rhso_admins/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
            					
            						}
            					}
								else
								{
									//======== RHSO Adminsssssssssssssssssssssssss
									
								$chsdocument = $this->mongo_db
            					->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company','dt_name','district_code'))
            					->where("email", (string) $identity)
            					->limit(1)
            					->get($this->collections['rhso_users']);
								
								//log_message('debug','$chsdocument======2779====='.print_r($chsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($chsdocument) === 1)
            					{
            						$user = (object) $chsdocument[0];
            							
            						$password = $this->hash_password_rhso_users($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->username,
            									'email'          => $user->email,
												'dt_name'        => $user->dt_name,
												'district_code'  => $user->district_code,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company,
												'user_type'      => "CADMIN"
            							);
            					
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_rhso_users/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else
								{
									//PANACEA Doctors Web side login==========
									$hsdocument = $this->mongo_db
									->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
									->where("email", (string) $identity)
									->limit(1)
									->get($this->collections['panacea_doctors']);
									
								
									//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($hsdocument) === 1)
            					{
            						$user = (object) $hsdocument[0];
            							
            						$password = $this->hash_password_panacea_doctors_db($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
												'user_type'      => "DOCTOR"
            							);
										
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_panacea_doctor/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else
								{
									//BCWELFARE_DOCTOR Web side login==========
									$hsdocument = $this->mongo_db
									->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
									->where("email", (string) $identity)
									->limit(1)
									->get($this->collections['bc_welfare_doctors']);
									
								
									//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($hsdocument) === 1)
            					{
            						$user = (object) $hsdocument[0];
            							
            						$password = $this->hash_password_bc_welfare_doctors_db($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
												'user_type'      => "BC_WELFARE_DOCTOR"
            							);
										
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_bc_welfare_doctor/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}else{
									//BCWELFARE_DOCTOR Web side login==========
									$hsdocument = $this->mongo_db
									->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
									->where("email", (string) $identity)
									->limit(1)
									->get($this->collections['ttwreis_doctors']);
									
								
									//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
            					
            						
            					// If hs customer document found
            					if (count($hsdocument) === 1)
            					{
            						$user = (object) $hsdocument[0];
            							
            						$password = $this->hash_password_ttwreis_doctor_db($user->_id, $password);
            						if ($password === TRUE)
            						{
            							// Not yet activated?
            							if ($user->active == 0)
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_not_active');
            								return FALSE;
            							}
            					
            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
            							{
            								$this->trigger_events('post_login_unsuccessful');
            								$this->set_error('login_unsuccessful_plan_expired');
            								return FALSE;
            							} */
            					
            							// Set user session data
            							$session_data = array(
            									'identity'       => $user->email,
            									'username'       => $user->username,
            									'email'          => $user->email,
            									'user_id'        => $user->_id->{'$id'},
            									'old_last_login' => $user->last_login,
            									'company'        => $user->company_name,
												'user_type'      => "TTWREIS_DOCTOR"
            							);
										
            							// Clean login attempts, also update last login time
            							//$this->update_last_login($user->_id);
            							//$this->clear_login_attempts($identity);
            					
            							// Check whether we should remember the user
            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
            							{
            								$this->remember_me($user->email);
            							}
            							$cus_url = base_url().$user->company_name;
            							$h = json_encode($session_data);
            							$str = base64_encode($h);
            					
            							$this->input->set_cookie('language', 'english', 3600*2);
            					
            							redirect($cus_url.'/index.php/auth/session_ttwreis_doctor/'.$str);
            							$this->trigger_events(array('post_login', 'post_login_successful'));
            							$this->set_message('login_successful');
            					
            							return TRUE;
									}
								}
								else{
									//BCWELFARE_DOCTOR Web side login==========
																		$hsdocument = $this->mongo_db
																		->select(array('_id', 'username', 'email', 'password', 'active', 'last_login','company_name'))
																		->where("email", (string) $identity)
																		->limit(1)
																		->get($this->collections['panacea_cc_normal_request']);
																		
																	
																		//log_message('debug','$hsdocument======2779====='.print_r($hsdocument,true));
									            					
									            						
									            					// If hs customer document found
									            					if (count($hsdocument) === 1)
									            					{
									            						$user = (object) $hsdocument[0];
									            							
									            						$password = $this->hash_password_panacea_cc_normal_request($user->_id, $password);
									            						if ($password === TRUE)
									            						{
									            							// Not yet activated?
									            							if ($user->active == 0)
									            							{
									            								$this->trigger_events('post_login_unsuccessful');
									            								$this->set_error('login_unsuccessful_not_active');
									            								return FALSE;
									            							}
									            					
									            							/* if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
									            							{
									            								$this->trigger_events('post_login_unsuccessful');
									            								$this->set_error('login_unsuccessful_plan_expired');
									            								return FALSE;
									            							} */
									            					
									            							// Set user session data
									            							$session_data = array(
									            									'identity'       => $user->email,
									            									'username'       => $user->username,
									            									'email'          => $user->email,
									            									'user_id'        => $user->_id->{'$id'},
									            									'old_last_login' => $user->last_login,
									            									'company'        => $user->company_name,
																					'user_type'      => "PANACEA_CC_NORMAL"
									            							);
																			
									            							// Clean login attempts, also update last login time
									            							//$this->update_last_login($user->_id);
									            							//$this->clear_login_attempts($identity);
									            					
									            							// Check whether we should remember the user
									            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
									            							{
									            								$this->remember_me($user->email);
									            							}
									            							$cus_url = base_url().$user->company_name;
									            							$h = json_encode($session_data);
									            							$str = base64_encode($h);
									            					
									            							$this->input->set_cookie('language', 'english', 3600*2);
									            					
									            							redirect($cus_url.'/index.php/auth/session_panacea_cc_normal/'.$str);
									            							$this->trigger_events(array('post_login', 'post_login_successful'));
									            							$this->set_message('login_successful');
									            					
									            							return TRUE;
																		}
																	}
				            					else
				            					{
				            						//======== PANACEA secreatry
				            					$secretarydocument = $this->mongo_db
				            					->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
				            					->where($this->identity_column, (string) $identity)
				            					->limit(1)
				            					->get('panacea_secretary');


				            					//->get($this->collections['panacea_secretary']);
				            					
				            						
				            					// If customer document found
				            					if (count($secretarydocument) === 1)
				            					{
				            						$user = (object) $secretarydocument[0];
				            							
				            						$password = $this->hash_password_panacea_secretary($user->_id, $password);
				            						if ($password === TRUE)
				            						{
				            							// Not yet activated?
				            							if ($user->active == 0)
				            							{
				            								$this->trigger_events('post_login_unsuccessful');
				            								$this->set_error('login_unsuccessful_not_active');
				            								return FALSE;
				            							}
				            					
				//             							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
				//             							{
				//             								$this->trigger_events('post_login_unsuccessful');
				//             								$this->set_error('login_unsuccessful_plan_expired');
				//             								return FALSE;
				//             							}
				            					
				            							// Set user session data
				            							$session_data = array(
				            									'identity'       => $user->{$this->identity_column},
				            									'username'       => $user->username,
				            									'email'          => $user->email,
				            									'user_id'        => $user->_id->{'$id'},
				            									'old_last_login' => $user->last_login,
				            									'company'        => $user->company_name,
				            									'plan'           => $user->plan,
				            									'registered'     => $user->registered_on,
				            									'expiry'         => $user->plan_expiry,
																'user_type'      => "SECRETARY"
				            							);
				            					
				            							// Clean login attempts, also update last login time
				            							//$this->update_last_login($user->_id);
				            							//$this->clear_login_attempts($identity);
				            					
				            							// Check whether we should remember the user
				            							if ($remember && $this->config->item('remember_users', 'ion_auth'))
				            							{
				            								$this->remember_me($user->email);
				            							}
				            							$cus_url = base_url().$user->company_name;
				            							$h = json_encode($session_data);
				            							$str = base64_encode($h);
				            					
				            							$this->input->set_cookie('language', 'english', 3600*2);
				            					
				            							redirect($cus_url.'/index.php/auth/session_panacea_secretary/'.$str);
				            							$this->trigger_events(array('post_login', 'post_login_successful'));
				            							$this->set_message('login_successful');
				            					
				            							return TRUE;
				            					
				            						}
				            					}


									else
									{
										// add more login here
									}
								}
								}
								}
								}
								}
								}
								}
								}
								}
							}
							}
							}
							}
							}
							}
							}

							}
							}  
							}
							}
										}
									}
									}
									}
									}
								}
            				}
            				}
            			}
            			
            		}
            	}
            	 
            }
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
	 * Checks credentials and logs the passed user in if possible.
	 *
	 * @return bool
	 */
	public function login_old_func($identity, $password, $remember = FALSE)
	{
		$this->trigger_events('pre_login');

		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$this->trigger_events('extra_where');
        $currentdate = date("Y-m-d");
		$document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
			// MongoDB is vulnerable to SQL Injection like attacks (in PHP at least), in MongoDB
			// PHP driver we use objects to make queries and as we know PHP allows us to submit
			// objects via GET, POST, etc. and so getting user input like password[$ne]=1 is possible
			// which translates to: array('$ne' => 1) in for example find queries. So we make sure that
			// what we put into a collection is strictly string typed. We also watch what we put in our
			// stomach, goveg! :))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['customers']);

			
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
				
				if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
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
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'expiry'         => $user->plan_expiry
                );
				
                // Clean login attempts, also update last login time
                $this->update_last_login($user->_id);
				$this->clear_login_attempts($identity);

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_me($user->email);
                }
                $cus_url = base_url().$user->company_name;
			    $h = json_encode($session_data);
			    $str = base64_encode($h);
			    redirect($cus_url.'/index.php/auth/session/'.$str);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
				
			}
		}
		else 
		{
		   $userdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','subscription_end'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['users']);
			
            if(count($userdocument) === 1)
			{
				$user = (object) $userdocument[0];
				$password = $this->hash_password_user_db($user->_id,$password);
				if ($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						$this->trigger_events('post_login_unsuccessful');
						$this->set_error('login_unsuccessful_not_active');
						return FALSE;
					}
					
					if($user->subscription_end == $currentdate || $user->subscription_end < $currentdate )
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
						'old_last_login' => $user->last_login,
						'company'        => $user->company
					);
					$this->session->set_userdata('user',$session_data);
					// Clean login attempts, also update last login time
					$this->update_user_last_login($user->_id);
					$this->clear_login_attempts($identity);

					// Check whether we should remember the user
					if ($remember && $this->config->item('remember_users', 'ion_auth'))
					{
						$this->remember_me($user->email);
					}
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/session/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');

					return TRUE;
				}
		    }
			else
			{
				$admindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login'))
				->where($this->identity_column, (string) $identity)
				->limit(1)
				->get($this->collections['tlstec_admin']);
				
				if(count($admindocument) === 1 )
				{
				$user = (object) $admindocument[0];
				$password = $this->hash_password_admin_db($user->_id, $password);
				if ($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						$this->trigger_events('post_login_unsuccessful');
						$this->set_error('login_unsuccessful_not_active');
						return FALSE;
					}

					// Set user session data
					$session_data = array(
						'identity'       => $user->{$this->identity_column},
						'username'       => $user->username,
						'email'          => $user->email,
						'user_id'        => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
					);

					set_cookie(array(
					'name'   => 'admin_identity',
					'value'  => $user->email
				   ));

					// Clean login attempts, also update last login time
					$this->update_admin_last_login($user->_id);
					$this->clear_login_attempts($identity);

					// Check whether we should remember the user
					if ($remember && $this->config->item('remember_users', 'ion_auth'))
					{
						$this->remember_me($user->email);
					}
					$cus_url = base_url();
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/session/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
					return TRUE;
					}
				}
            else
            {
            	$document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','registered_on'))
			// MongoDB is vulnerable to SQL Injection like attacks (in PHP at least), in MongoDB
			// PHP driver we use objects to make queries and as we know PHP allows us to submit
			// objects via GET, POST, etc. and so getting user input like password[$ne]=1 is possible
			// which translates to: array('$ne' => 1) in for example find queries. So we make sure that
			// what we put into a collection is strictly string typed. We also watch what we put in our
			// stomach, goveg! :))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['api_details']);

			
		// If user document found
		if (count($document) === 1)
		{
			$user = (object) $document[0];
			$password = $this->hash_password_api_db($user->_id, $password);
			if ($password === TRUE)
			{
				// Not yet activated?
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }

                // Set user session data
                $session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'registered'     => $user->registered_on
                );
				
                // Clean login attempts, also update last login time
                $this->update_last_login($user->_id);
				$this->clear_login_attempts($identity);

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_me($user->email);
                }
                $cus_url = base_url().'api';
			    $h = json_encode($session_data);
			    $str = base64_encode($h);
			    redirect(base_url().'/index.php/auth/session_api/'.$str);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
            		}
        }
		else
            {
            	
            	$document = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','plan_expiry','registered_on','plan'))
			->where($this->identity_column, (string) $identity)
			->limit(1)
			->get($this->collections['sub_admins']);

			
		// If user document found
		if (count($document) === 1)
		{
			$user = (object) $document[0];
			$password = $this->hash_password_sub_admin_db($user->_id,$password);
			if ($password === TRUE)
			{
				// Not yet activated?
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }

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
                //log_message('debug','ppppppppppppppppppppppppppppppppppppppppppppppppppppppp'.print_r($session_data,true));
                $this->session->set_userdata('user',$session_data);
                // Clean login attempts, also update last login time
                $this->update_user_last_login($user->_id);
				$this->clear_login_attempts($identity);

				// Check whether we should remember the user
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_me($user->email);
                }
                $cus_url = base_url().$user->company;
			    $h = json_encode($session_data);
			    $str = base64_encode($h);
			    redirect($cus_url.'/index.php/auth/session/'.$str);
                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
            		}
            	}
            	else
            	{
            		// ENTERPRISE SUB ADMIN
            		$subadmindocument = $this->mongo_db
            		->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company'))
            		->where($this->identity_column, (string) $identity)
            		->limit(1)
            		->get($this->collections['sub_admins']);
            			
            		// If user document found
            		if (count($subadmindocument) === 1)
            		{
            			$user = (object) $subadmindocument[0];
            			$password = $this->hash_password_sub_admin_db($user->_id, $password);
            			if ($password === TRUE)
            			{
            				// Not yet activated?
            				if ($user->active == 0)
            				{
            					$this->trigger_events('post_login_unsuccessful');
            					$this->set_error('login_unsuccessful_not_active');
            					return FALSE;
            				}
							
							if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
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
            						'old_last_login' => $user->last_login,
            						'company'        => $user->company
            				);
            		
            				// Clean login attempts, also update last login time
            				$this->update_last_login($user->_id);
            				$this->clear_login_attempts($identity);
            		
            				// Check whether we should remember the user
            				if ($remember && $this->config->item('remember_users', 'ion_auth'))
            				{
            					$this->remember_me($user->email);
            				}
            				$cus_url = base_url().$user->company;
            				$h = json_encode($session_data);
            				$str = base64_encode($h);
            				redirect($cus_url.'/index.php/auth/session/'.$str);
            				$this->trigger_events(array('post_login', 'post_login_successful'));
            				$this->set_message('login_successful');
            		
            				return TRUE;
            			}
            		}
            		else
            		{
            			// TLSTEC SUPPORT ADMIN
            			$supportadmindocument = $this->mongo_db
            			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','level'))
            			->where($this->identity_column, (string) $identity)
            			->limit(1)
            			->get($this->collections['support_admin']);
            				
            			if (count($supportadmindocument) === 1)
            			{
            				$user = (object) $supportadmindocument[0];
            				$password = $this->hash_password_support_admin_db($user->_id, $password);
            				if ($password === TRUE)
            				{
            					// Not yet activated?
            					if ($user->active == 0)
            					{
            						$this->trigger_events('post_login_unsuccessful');
            						$this->set_error('login_unsuccessful_not_active');
            						return FALSE;
            					}
            			
            					// Set user session data
            					$session_data = array(
            							'identity'       => $user->{$this->identity_column},
            							'username'       => $user->username,
            							'email'          => $user->email,
            							'user_id'        => $user->_id->{'$id'},
            							'old_last_login' => $user->last_login,
            							'company'        => $user->company,
            							'level'          => $user->level
            					);
            			
            					$this->session->set_userdata("customer",$session_data);
            			
            					// Clean login attempts, also update last login time
            					$this->update_support_admin_last_login($user->_id);
            					$this->clear_login_attempts($identity);
            			
            					// Check whether we should remember the user
            					if ($remember && $this->config->item('remember_users', 'ion_auth'))
            					{
            						$this->remember_me($user->email);
            					}
            					
            					$this->trigger_events(array('post_login', 'post_login_successful'));
            					$this->set_message('login_successful');
            			
            					return TRUE;
            				}
            					
            			}
						/*else
						{
					       // PANACEA ADMIN VIEWERS
							$panacea_viewer_admin_document = $this->mongo_db
							->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
							->where($this->identity_column, (string) $identity)
							->limit(1)
							->get($this->collections['panacea_viewers']);
							
							
            				
							if (count($panacea_viewer_admin_document) === 1)
							{
								$user = (object) $panacea_viewer_admin_document[0];
								$password = $this->hash_password_panacea_viewers_db($user->_id, $password);
								if ($password === TRUE)
								{
									// Not yet activated?
									if ($user->active == 0)
									{
										$this->trigger_events('post_login_unsuccessful');
										$this->set_error('login_unsuccessful_not_active');
										return FALSE;
									}
							
									// Set user session data
									$session_data = array(
											'identity'       => $user->{$this->identity_column},
											'username'       => $user->username,
											'email'          => $user->email,
											'user_id'        => $user->_id->{'$id'},
											'old_last_login' => $user->last_login,
											'company'        => $user->company_name,
											'plan'           => $user->plan,
											'registered'     => $user->registered_on,
											'expiry'         => $user->plan_expiry,
											'user_type'      => "PVIEWER"
									);
							
									$this->session->set_userdata("customer",$session_data);
							
									// Clean login attempts, also update last login time
									//$this->update_support_admin_last_login($user->_id);
									//$this->clear_login_attempts($identity);
							
									// Check whether we should remember the user
									if ($remember && $this->config->item('remember_users', 'ion_auth'))
									{
										$this->remember_me($user->email);
									}
									
									$cus_url = base_url().$user->company;
									$h = json_encode($session_data);
									$str = base64_encode($h);
									redirect($cus_url.'/index.php/auth/session_panacea_viewers/'.$str);
									$this->trigger_events(array('post_login', 'post_login_successful'));
									$this->set_message('login_successful');
							
									return TRUE;
								}
									
							}
						} */
            		}
            	}
            	 
            }
            	 
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
 
   public function customers()
	{
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['customers']);
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
	
	public function sub_admins()
	{
		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['sub_admins']);
		$obj = json_decode(json_encode($this->response), FALSE);
	
		$result = array();
	
		foreach ($obj as $row)
		{
			$result[] = $row;
		}
		//log_message('debug','OOOOOOOOOOOOOOOOOBBBBBBBBBBBBBJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJ'.print_r($obj,true));
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

		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['users']);
		return $this;
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
		$id || $id = $this->session->userdata('user_id');

		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->users();

		return $this;
	}

	 // ------------------------------------------------------------------------

	/**
	 * Helper: Returns customer object by its passed Email.
	 *
	 * @return object
	 */
	public function get_customer($email)
	{
	    $query = $this->mongo_db->getWhere($this->collections['customers'],array('email'=>$email));
	    if($query)
	    {
		  return $query[0];
		} 
	}
	

	 // ------------------------------------------------------------------------

	/**
	 * Helper: Returns user object by its passed Email.
	 *
	 * @return object
	 */
	public function get_user($email)
	{
	    $query = $this->mongo_db->getWhere($this->collections['users'],array('email'=>$email));
		if($query)
	    {
		  return $query[0];
		} 
	}	

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns admin object by its passed Email.
	 *
	 * @return object
	 */
	public function get_admin($email)
	{
	    $query = $this->mongo_db->getWhere($this->collections['tlstec_admin'],array('email'=>$email));
		if($query)
	    {
		  return $query[0];
		} 
	}	
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns sub admin object by its passed Email.
	 *
	 * @return object
	 */
	public function get_sub_admin($email)
	{
	    $query = $this->mongo_db->getWhere($this->collections['sub_admins'],array('email'=>$email));
		if($query)
	    {
		  return $query[0];
		} 
	}	
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns support admin object by its passed Email.
	 *
	 * @return object
	 */
	public function get_support_admin($email)
	{
	    $query = $this->mongo_db->getWhere($this->collections['support_admin'],array('email'=>$email));
		if($query)
	    {
		  return $query[0];
		} 
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
		$id || $id = $this->session->userdata('user_id');

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
		foreach ($user->groups as $group_id)
		{
			$groups[] = $this->group($group_id)->document();
		}

		$this->response = $groups;
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
		$user_id || $user_id = $this->session->userdata('user_id');

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

		// If no id passed use the current user id stored in session
		$user_id || $user_id = $this->session->userdata('user_id');

		// If no group name is passed remove user from all groups
		if (empty($group_id))
		{
			return $this->mongo_db
				->where('_id', new MongoId($user_id))
				->set('groups', array())
				->update($this->collections['users']);
		}
		// Only remove the specified group name from the user document
		else
		{
			return $this->mongo_db
			->where('_id', new MongoId($user_id))
			->pull('groups', $group_id)
			->update($this->collections['users']);
		}
	}
	
	public function get_groups_by_companyname($companyname)
	{
	    $company = strtolower(str_replace(" ","",$companyname));
		$this->response = $this->mongo_db->where(array('company'=> $company))->get($this->collections['groups']);
		return $this->response;
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

		// Execute, store results and return the object itself
		$this->response = $this->mongo_db->get($this->collections['groups']);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Returns a group object based on pre-defined buffered parameters.
	 *
	 * @return object
	 */
	public function group($id = NULL)
	{
		$this->trigger_events('group');

		if (isset($id))
		{
			$this->mongo_db->where('_id', new MongoId($id));
		}

		// Set query parameters
		$this->limit(1);

		// Execute and return results
		return $this->groups();
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
				$data['password'] = $this->hash_password($data['password'], $user->salt);
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
	 * Deletes a user document by its ID.
	 *
	 * @return bool
	 */
	public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		// Delete user document (groups association will also be deleted)
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
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Updates enterprise admin last login timestamp.
	 *
	 * @return bool
	 */
	public function update_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update($this->collections['customers']);
	}

    // ------------------------------------------------------------------------

   // ------------------------------------------------------------------------

	/**
	 * Updates user last login timestamp.
	 *
	 * @return bool
	 */
	public function update_user_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update($this->collections['users']);
	}

	public function power_of_ten_update_user_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update('power_of_ten_users');
	}

	public function power_of_ten_update_district_coordinator_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update('power_of_ten_district_coordinators');
	}
	
	/**
	 * Updates field agent last login timestamp.
	 *
	 * @return bool
	 */
	public function update_field_agent_last_login($id)
	{
		$this->load->helper('date');
	
		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');
	
		return $this->mongo_db
		->where('_id', new MongoId($id))
		->set('last_login', date("Y-m-d H:i:s"))
		->update($this->collections['field_agents']);
	}


	// ------------------------------------------------------------------------

	/**
	 * Updates user last login timestamp.
	 *
	 * @return bool
	 */
	public function update_admin_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update($this->collections['tlstec_admin']);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Updates user last login timestamp.
	 *
	 * @return bool
	 */
	public function update_support_admin_last_login($id)
	{
		$this->load->helper('date');

		$this->trigger_events('update_last_login');
		$this->trigger_events('extra_where');

		return $this->mongo_db
			->where('_id', new MongoId($id))
			->set('last_login', date("Y-m-d H:i:s"))
			->update($this->collections['support_admin']);
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

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		// User not found
		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}
	
	/**
	 * Remembers field agent by setting required cookies
	 *
	 * @return bool
	 */
	public function remember_field_agent($id)
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
	
		$updated = $this->mongo_db
		->where('_id', new MongoId($id))
		->set('remember_code', $salt)
		->update($this->collections['field_agents']);
	
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
	
			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}
	
		// User not found
		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	 // ------------------------------------------------------------------------

	/**
	 * Remembers customer/user/ TLSTEC admin by setting required cookies
	 *
	 * @return bool
	 */
	public function remember_me($email)
	{
		$this->trigger_events('pre_remember_user');

		if (!$email)
		{
			return FALSE;
		}

		// Load customer document
		$customer = $this->get_customer($email);

		if($customer)
		{
		// Re-hash user password as remember code
		$salt = sha1($customer['password']);

		$updated = $this->mongo_db
			->where('email', $email)
			->set('remember_code', $salt)
			->update($this->collections['customers']);

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
			    'value'  => $customer['email'],
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}
	    }
	   else
	   {

	   	$user = $this->get_user($email);
	   	if($user)
	   	{
	   		$salt = sha1($user['password']);

		   $updated = $this->mongo_db
			->where('email', $email)
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
			    'value'  => $user['email'],
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

	   	}
	   	else
	   	{
	   		$admin = $this->get_admin($email);

		if($admin)
		{
			// Re-hash user password as remember code
		$salt = sha1($admin['password']);

		$updated = $this->mongo_db
			->where('email', $email)
			->set('remember_code', $salt)
			->update($this->collections['tlstec_admin']);

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
			    'value'  => $admin['email'],
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		}
		else
		{
		   $subadmin = $this->get_sub_admin($email);
		   
		   if($subadmin)
		   {
				 // Re-hash user password as remember code
				 $salt = sha1($subadmin['password']);

				 $updated = $this->mongo_db
				 ->where('email', $email)
				 ->set('remember_code', $salt)
				 ->update($this->collections['sub_admins']);
				 
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
						'value'  => $subadmin['email'],
						'expire' => $expire
					));

					set_cookie(array(
						'name'   => 'remember_code',
						'value'  => $salt,
						'expire' => $expire
					));

					$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
					return TRUE;
				}
		    }
		    else
		    {
			   $supportadmin = $this->get_support_admin($email);
			   
			   if($supportadmin)
		       {
			       // Re-hash user password as remember code
				 $salt = sha1($supportadmin['password']);

				 $updated = $this->mongo_db
				 ->where('email', $email)
				 ->set('remember_code', $salt)
				 ->update($this->collections['support_admin']);
				 
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
						'value'  => $supportadmin['email'],
						'expire' => $expire
					));

					set_cookie(array(
						'name'   => 'remember_code',
						'value'  => $salt,
						'expire' => $expire
					));

					$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
					return TRUE;
				}
			   
			   }
		  
		  
		    }
		}
	   	}
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
		
		//log_message('debug','get_cookie(identity)'.print_r(get_cookie('identity'),true));
		//log_message('debug','get_cookie(identity)'.print_r(get_cookie('remember_code'),true));
		//log_message('debug','get_cookie(identity)'.print_r($this->identity_check_for_remembered_user(get_cookie('identity')),true));

		// Check for valid data
		if ( !get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check_for_remembered_user(get_cookie('identity')))
		{
			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
			return FALSE;
		}

        
		// Load the user by cookie data
		$this->trigger_events('extra_where');
		$currentdate = date("Y-m-d");
		$customerdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company_name','plan_expiry','registered_on','plan'))
		    ->where($this->identity_column, get_cookie('identity'))
		    ->where('remember_code', get_cookie('remember_code'))
		    ->limit(1)
		    ->get($this->collections['customers']);


		// If the user was found, sign them in
		if (count($customerdocument))
		{
			$user = (object) $customerdocument[0];

			// Not yet activated?
            if ($user->active == 0)
            {
                $this->trigger_events('post_login_unsuccessful');
                $this->set_error('login_unsuccessful_not_active');
                return FALSE;
            }
				
		    if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
			{
			    $this->trigger_events('post_login_unsuccessful');
                $this->set_error('login_unsuccessful_plan_expired');
                return FALSE;
			}	

			// Update last login timestamp
			$this->update_last_login($user->_id);


            $session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
					'company'        => $user->company_name,
					'plan'           => $user->plan,
					'registered'     => $user->registered_on,
					'expiry'         => $user->plan_expiry
                );

			// Extend the users cookies if the option is enabled
			if ($this->config->item('user_extend_on_login', 'ion_auth'))
			{
				$this->remember_me($user->email);
			}

			$cus_url = base_url().$user->company_name;
		    $h = json_encode($session_data);
			$str = base64_encode($h);
			redirect($cus_url.'/index.php/auth/session/'.$str);

			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
			return TRUE;
		
		}
		else 
		{
		   $userdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','subscription_end'))
			->where($this->identity_column, get_cookie('identity'))
		    ->where('remember_code', get_cookie('remember_code'))
		    ->limit(1)
			->get($this->collections['users']);
			
            if($userdocument)
			{
				$user = (object) $userdocument[0];
				
				// Not yet activated?
				if ($user->active == 0)
				{
						$this->trigger_events('post_login_unsuccessful');
						$this->set_error('login_unsuccessful_not_active');
						return FALSE;
				}
				
				if($user->subscription_end == $currentdate || $user->subscription_end < $currentdate )
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
						'old_last_login' => $user->last_login,
						'company'        => $user->company
				);
				 
				// Clean login attempts, also update last login time
				$this->update_user_last_login($user->_id);
				

				// Extend the users cookies if the option is enabled
				if ($this->config->item('user_extend_on_login', 'ion_auth'))
				{
					$this->remember_me($user->email);
				}

				$cus_url = base_url().$user->company;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/auth/session/'.$str);
				$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
				return TRUE;
		
            }
		   else
		    {
			    $admindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login'))
				->where($this->identity_column, get_cookie('identity'))
			    ->where('remember_code', get_cookie('remember_code'))
				->limit(1)
				->get($this->collections['tlstec_admin']);

            if($admindocument)
			{
		    $user = (object) $admindocument[0];
			
		    // Not yet activated?
             if ($user->active == 0)
             {
                  $this->trigger_events('post_login_unsuccessful');
                  $this->set_error('login_unsuccessful_not_active');
                  return FALSE;
             }

                // Set user session data
                $session_data = array(
					'identity'       => $user->{$this->identity_column},
					'username'       => $user->username,
					'email'          => $user->email,
					'user_id'        => $user->_id->{'$id'},
					'old_last_login' => $user->last_login,
                );
				
				$this->session->set_userdata("customer",$session_data);
                
                // Clean login attempts, also update last login time
                $this->update_admin_last_login($user->_id);
				

				// Extend the users cookies if the option is enabled
			   if ($this->config->item('user_extend_on_login', 'ion_auth'))
			   {
				 $this->remember_me($user->email);
			   }
			    $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
				return TRUE;
				
            }
			else
			{
			   $subadmindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','plan','registered','plan_expiry'))
				->where($this->identity_column, get_cookie('identity'))
			    ->where('remember_code', get_cookie('remember_code'))
				->limit(1)
				->get($this->collections['sub_admins']);

                if($subadmindocument)
			    {
		             $user = (object) $subadmindocument[0];
					 
					 // Not yet activated?
					 if ($user->active == 0)
					 {
						  $this->trigger_events('post_login_unsuccessful');
						  $this->set_error('login_unsuccessful_not_active');
						  return FALSE;
					 }
					 
					 if($user->plan_expiry == $currentdate || $user->plan_expiry < $currentdate )
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
							'old_last_login' => $user->last_login,
							'company'        => $user->company,
							'plan'           => $user->plan,
							'registered'     => $user->registered_on,
							'expiry'         => $user->plan_expiry
						);
                
						// Clean login attempts, also update last login time
						$this->update_sub_admin_last_login($user->_id);
						
						
						// Extend the users cookies if the option is enabled
					   if ($this->config->item('user_extend_on_login', 'ion_auth'))
					   {
						 $this->remember_me($user->email);
					   }

						$cus_url = base_url();
						$h = json_encode($session_data);
						$str = base64_encode($h);
						redirect($cus_url.'index.php/auth/session/'.$str);
						$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
						return TRUE;
			    }
				else
				{
				    $supportadmindocument = $this->mongo_db
				->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','level'))
				->where($this->identity_column, get_cookie('identity'))
			    ->where('remember_code', get_cookie('remember_code'))
				->limit(1)
				->get($this->collections['support_admin']);

                if($supportadmindocument)
			    {
		             $user = (object) $supportadmindocument[0];
					 
					 // Not yet activated?
					 if ($user->active == 0)
					 {
						  $this->trigger_events('post_login_unsuccessful');
						  $this->set_error('login_unsuccessful_not_active');
						  return FALSE;
					 }
					 
					  // Set user session data
						$session_data = array(
							'identity'       => $user->{$this->identity_column},
							'username'       => $user->username,
							'email'          => $user->email,
							'user_id'        => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'level'          => $user->level
						);
						
						$this->session->set_userdata("customer",$session_data);
                
						// Clean login attempts, also update last login time
						$this->update_support_admin_last_login($user->_id);
						
						
						// Extend the users cookies if the option is enabled
					   if ($this->config->item('user_extend_on_login', 'ion_auth'))
					   {
						 $this->remember_me($user->email);
					   }

						/* $cus_url = base_url();
						$h = json_encode($session_data);
						$str = base64_encode($h);
						redirect($cus_url.'index.php/auth/session_support_admin/'.$str); */
						$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
						return TRUE;
				
				}
			
			}
               
		} 
    
    }
	}

		// Nobody found
		$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
		return FALSE;
	}


    // ------------------------------------------------------------------------

	/**
	 * Remembers device user by setting required cookies
	 *
	 * @return bool
	 */
	public function remember_device_user($email)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id)
		{
			return FALSE;
		}

		// Load user document
		$user = get_user($email);
		// Re-hash user password as remember code
		$salt = sha1($user->password);

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

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		// User not found
		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	 // ------------------------------------------------------------------------

	/**
	 * create_group
	 *
	 * @author Ben Edmunds
	*/
	public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
	{
		// bail if the group name was not passed
		if(!$group_name)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->where('name', $group_name)->group()->document();
		if(isset($existing_group) && !empty($existing_group))
		{
			$this->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('name'=>$group_name,'description'=>$group_description);

		//filter out any data passed that doesnt have a matching column in the groups table
		//and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->collections['groups'], $additional_data), $data);

		$this->trigger_events('extra_group_set');

		// insert the new group
		$group_id = $this->mongo_db->insert($this->collections['groups'], $data);

		// report success
		$this->set_message('group_creation_successful');

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


		$updated = $this->mongo_db
			->where('_id', new MongoId($group_id))
			->set($data)
			->update($this->collections['groups']);

		$this->set_message('group_update_successful');

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
		$columns = $collection == 'customers' ?
			// Users collection static schema array
			array('_id', 'ip_address', 'username', 'password', 'salt', 'email', 'activation_code', 'forgotten_password_code', 'forgotten_password_time', 'remember_code', 'created_on', 'last_login', 'active', 'first_name', 'last_name', 'company', 'phone') :
			// Groups collection static schema array
			array('_id', 'name', 'description');

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
	// new code ****************************************************************************************************************************************
	function ins_api($data){
		//log_message('debug','apiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii');
		//$this->mongo_db->switchDatabase(DBNAME);
		$query = $this->mongo_db->insert('api_details',$data);
		
		//log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		return $query;
	}
	
	function api_users($data,$col){
		//log_message('debug','apiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii');
		$coll = $col.'_users';
		//$this->mongo_db->switchDb(DBNAME);
		$query = $this->mongo_db->insert($coll,$data);
		//log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		return $query;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Validates the email id for forgot password 
	 *
	 * @return bool
	 *  
	 * @author Selva 
	 */
	 
	public function verify_email_for_forgot_password($email)
	{
	  $customerdocument = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['customers'],array('email'=>$email));
	  if(!empty($customerdocument) && count($customerdocument === 1))
	  {
	    return TRUE;
	  }
	  else
	  {
	    $userdocument = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['users'],array('email'=>$email));
		if(!empty($userdocument) && count($userdocument === 1))
		{
		  return TRUE;
		}
		else
        {
          $admindocument = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['tlstec_admin'],array('email'=>$email));
          if(!empty($admindocument) && count($admindocument === 1))
		{
		  return TRUE;
		}
		else
		{
		  $subadmindocument = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['sub_admins'],array('email'=>$email));
		  if(!empty($subadmindocument) && count($subadmindocument === 1))
		  {
		   return TRUE;
		  }
		  else
		  {
		    $supportadmindocument = $this->mongo_db->select(array(),array('_id'))->limit(1)->getWhere($this->collections['support_admin'],array('email'=>$email));
		    if(!empty($supportadmindocument) && count($supportadmindocument === 1))
		    {
			  //log_message('debug','$supportadmindocument=====4093'.print_r($supportadmindocument,true));
		      return TRUE;
		    }
			else
			{
			  return FALSE;
			}
		   }
		}
		}
	  }	
	
	
	}

  // ------------------------------------------------------------------------

	/**
	 * Helper: get customer/user profile by forgotten password code 
	 *
	 * @return array
	 *  
	 * @author Selva 
	 */
	 
	public function get_details_by_forgotten_password_code($code)
	{
	  $customerprofile = $this->mongo_db->getWhere($this->collections['customers'],array('forgotten_password_code'=>$code));
	  if($customerprofile)
	  {
		  return $customerprofile;
	  }
	  else
	  {
        $userprofile = $this->mongo_db->getWhere($this->collections['users'],array('forgotten_password_code'=>$code));
        if($userprofile)
	     {
	       return $userprofile;
	     }
	     else
	     {
	     	$adminprofile = $this->mongo_db->getWhere($this->collections['tlstec_admin'],array('forgotten_password_code'=>$code));
            if($adminprofile)
	        {
	           return $adminprofile;
	        }
			else
			{
			   $subadminprofile = $this->mongo_db->getWhere($this->collections['sub_admins'],array('forgotten_password_code'=>$code));
               if($subadminprofile)
	           {
	              return $subadminprofile;
	           }
			   else
			   {
			      $supportadminprofile = $this->mongo_db->getWhere($this->collections['support_admin'],array('forgotten_password_code'=>$code));
                  if($supportadminprofile)
	              {
	                return $supportadminprofile;
	              }
				  else
				  {
					  $tswreis_hs_profile = $this->mongo_db->getWhere($this->collections['panacea_health_supervisors'],array('forgotten_password_code'=>$code));
					  if($tswreis_hs_profile)
					  {
						return $tswreis_hs_profile;
					  }
					  else
					  {
						  $tswreis_dr_profile = $this->mongo_db->getWhere($this->collections['panacea_doctors'],array('forgotten_password_code'=>$code));
						  if($tswreis_dr_profile)
						  {
							return $tswreis_dr_profile;
						  }
						  else
						  {
							  $tmreis_hs_profile = $this->mongo_db->getWhere($this->collections['tmreis_health_supervisors'],array('forgotten_password_code'=>$code));
							  if($tmreis_hs_profile)
							  {
								return $tmreis_hs_profile;
							  }
							  else
							  {
								  $tmreis_dr_profile = $this->mongo_db->getWhere($this->collections['tmreis_doctors'],array('forgotten_password_code'=>$code));
								  if($tmreis_dr_profile)
								  {
									return $tmreis_dr_profile;
								  }
								  else
								  {
									  $ttwreis_hs_profile = $this->mongo_db->getWhere($this->collections['ttwreis_health_supervisors'],array('forgotten_password_code'=>$code));
									  if($ttwreis_hs_profile)
									  {
										return $ttwreis_hs_profile;
									  }
									  else
									  {
										  $ttwreis_dr_profile = $this->mongo_db->getWhere($this->collections['ttwreis_doctors'],array('forgotten_password_code'=>$code));
										  if($ttwreis_dr_profile)
										  {
											return $ttwreis_dr_profile;
										  }
										  else
										  {
											  $bc_welfare_hs_profile = $this->mongo_db->getWhere($this->collections['bc_welfare_health_supervisors'],array('forgotten_password_code'=>$code));
											  if($bc_welfare_hs_profile)
											  {
												return $bc_welfare_hs_profile;
											  }
											  else
											  {
												  $bc_welfare_dr_profile = $this->mongo_db->getWhere($this->collections['bc_welfare_doctors'],array('forgotten_password_code'=>$code));
												  if($bc_welfare_dr_profile)
												  {
													return $bc_welfare_dr_profile;
												  }
											  }
										  }
									  }
								  }
							  }
						  }
					  }
			   
				  }
			   
			   }
			}
	     }
	   }
	
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Validates the email id for forgot password  ( for device user )
	 *
	 * @return bool
	 *  
	 * @author Selva 
	 */
	 
	public function verify_email_for_forgot_password_device_user($email)
	{
	  $document = $this->mongo_db->limit(1)->getWhere($this->collections['users'],array('email'=>$email));
	  if(!empty($document) && count($document === 1))
	  {
	    return TRUE;
	  }
	  else
	  {
		  $tswreis_hs_document = $this->mongo_db->limit(1)->getWhere($this->collections['panacea_health_supervisors'],array('email'=>$email));
		  if(!empty($tswreis_hs_document) && count($tswreis_hs_document === 1))
		  {
			return TRUE;
		  }
		  else
		  {
			  $tswreis_dr_document = $this->mongo_db->limit(1)->getWhere($this->collections['panacea_doctors'],array('email'=>$email));
			  if(!empty($tswreis_dr_document) && count($tswreis_dr_document === 1))
			  {
				return TRUE;
			  }
			  else
			  {
				  $tmreis_hs_document = $this->mongo_db->limit(1)->getWhere($this->collections['tmreis_health_supervisors'],array('email'=>$email));
				  if(!empty($tmreis_hs_document) && count($tmreis_hs_document === 1))
				  {
					return TRUE;
				  }
				  else
				  {
					  $tmreis_dr_document = $this->mongo_db->limit(1)->getWhere($this->collections['tmreis_doctors'],array('email'=>$email));
					  if(!empty($tmreis_dr_document) && count($tmreis_dr_document === 1))
					  {
						return TRUE;
					  }
					  else
					  {
						  $ttwreis_hs_document = $this->mongo_db->limit(1)->getWhere($this->collections['ttwreis_health_supervisors'],array('email'=>$email));
						  if(!empty($ttwreis_hs_document) && count($ttwreis_hs_document === 1))
						  {
							return TRUE;
						  }
						  else
						  {
							  $ttwreis_dr_document = $this->mongo_db->limit(1)->getWhere($this->collections['ttwreis_doctors'],array('email'=>$email));
							  if(!empty($ttwreis_dr_document) && count($ttwreis_dr_document === 1))
							  {
								return TRUE;
							  }
							  else
							  {
								  $bc_welfare_hs_document = $this->mongo_db->limit(1)->getWhere($this->collections['bc_welfare_health_supervisors'],array('email'=>$email));
								  if(!empty($bc_welfare_hs_document) && count($bc_welfare_hs_document === 1))
								  {
									return TRUE;
								  }
								  else
								  {
									  $bc_welfare_dr_document = $this->mongo_db->limit(1)->getWhere($this->collections['bc_welfare_doctors'],array('email'=>$email));
									  if(!empty($bc_welfare_dr_document) && count($bc_welfare_dr_document === 1))
									  {
										return TRUE;
									  }
									  else
									  {
										  return FALSE;
									  }
								  }
							  }
						  }
					  }
				  }
			  }
		  }
	  }
    }	  
     
    // ------------------------------------------------------------------------

	/**
	 * Helper: create "total_docs" collection for customer while signup
	 *
	 *  
	 * @author Vikas 
	 */
	 
	  
	function create_total_docs_collection($collection = '',$db = '')
	{
		
		$insert = array();
	
		$insert['time']        = date('Y-m-d H:i:s');
		$insert['id']          = 1;
		$insert['total_docs']  = 0;
		
		$this->mongo_db->switchDb(URL_DB.$db);
		$query = $this->mongo_db->insert($collection,$insert);
		$this->mongo_db->switchDb(DBNAME);
		
     }

     // ------------------------------------------------------------------------

	/**
	 * Helper: create "templates" collection for customer while signup
	 *
	 *  
	 * @author Selva 
	 */
	 

     function create_templates_collection($collection,$db)
	{
		$this->mongo_db->switchDb(URL_DB.$db);
		$query = $this->mongo_db->create_collection($collection);
		$this->mongo_db->switchDb(DBNAME);
		
     }


     public function poweroften_dashlogin($identity,$password,$device_unique_number,$companyname=FALSE,$remember=FALSE)
	{


		$this->trigger_events('pre_login');
	
		$company_name = '';
	
		if(empty($identity) || empty($password) || empty($device_unique_number))
		{
			echo "EMPTY_CREDENTIALS";
			return FALSE;
		}
	
		$identity = strtolower($identity);
	
		$currentdate = Date("Y-m-d");
	
		/* if($additional_data['plan'] == "Diamond")
		 {
		$plan_exp = date('Y-m-d',strtotime('+12 months'));
		}
		elseif($additional_data['plan'] == "Gold")
		{
		$plan_exp = date('Y-m-d',strtotime('+6 months'));
		}
		elseif($additional_data['plan'] == "Silver")
		{
		$plan_exp = date('Y-m-d',strtotime('+3 month'));
		}
		elseif($additional_data['plan'] == "Bronze")
		{
		$plan_exp = date('Y-m-d',strtotime('+1 month'));
		} */
	
		 
		$userdocument = $this->mongo_db
		->select(array($this->identity_column, '_id', 'username', 'email', 'phone_no', 'doc_properties_id', 'password', 'active', 'last_login','company'))
		->where(array($this->identity_column => (string) $identity))
		->limit(1)
		->get('power_of_ten_users');
	
		if($userdocument)
		{
			$this->mongo_db
			->set(array('device_unique_number' => $device_unique_number,'status'=>'online'))
			->where($this->identity_column, (string) $identity)
			->update('power_of_ten_users');
	
	
			$user = (object) $userdocument[0];
			$password = $this->power_of_ten_hash_password_user_db($user->_id, $password);
			if($password === TRUE)
			{
				// Not yet activated?
				if ($user->active == 0)
				{
					$this->trigger_events('post_login_unsuccessful');
					$this->set_error('login_unsuccessful_not_active');
					return FALSE;
				}
	
				/* // Expired?
					$registered_date = $user->registered_on;
				$registered_date = strtotime($registered_date);
				$expiry_date     = strtotime('+15 days', $registered_date);
				$expiry_date     = date('Y-m-d',$expiry_date);
	
				if($expiry_date == $currentdate || $expiry_date < $currentdate )
				{
				echo "EXPIRED";
				return FALSE;
				}	 */
	
			
	
				// Set user session data
				$session_data = array(
						'identity'             => $user->{$this->identity_column},
						'username'             => $user->username,
						'email'                => $user->email,
						'phone_no'                => $user->phone_no,
						'doc_properties_id'                => $user->doc_properties_id,
						'user_id'        	   => $user->_id->{'$id'},
						'old_last_login' 	   => $user->last_login,
						'company'        	   => $user->company,
						'company_display_name' => "Power of ten"
				);

				
				
				$this->session->set_userdata('user',$session_data);
				// Clean login attempts, also update last login time
				$this->power_of_ten_update_user_last_login($user->_id);
				//$this->clear_login_attempts($identity);
	
				$cus_url = base_url().$user->company;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/auth/dashsession/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
	
				return TRUE;
			}

			else
			{
				echo "INCORRECT_PASSWD";
				return FALSE;
			}
	
		}
		else
		{
			$district_userdocument = $this->mongo_db
			->select(array($this->identity_column, '_id', 'username', 'district', 'email', 'password', 'active','company'))
			->where(array($this->identity_column => (string) $identity))
			->limit(1)
			->get('power_of_ten_district_coordinators');
			
			if($district_userdocument)
			{
				$this->mongo_db
				->set(array('device_unique_number' => $device_unique_number,'status'=>'online'))
				->where($this->identity_column, (string) $identity)
				->update('power_of_ten_district_coordinators');
			
			
				$user = (object) $district_userdocument[0];
				$password = $this->power_of_ten_hash_password_dc_db($user->_id, $password);
				if($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						$this->trigger_events('post_login_unsuccessful');
						$this->set_error('login_unsuccessful_not_active');
						return FALSE;
					}
			
					/* // Expired?
						$registered_date = $user->registered_on;
					$registered_date = strtotime($registered_date);
					$expiry_date     = strtotime('+15 days', $registered_date);
					$expiry_date     = date('Y-m-d',$expiry_date);
			
					if($expiry_date == $currentdate || $expiry_date < $currentdate )
					{
					echo "EXPIRED";
					return FALSE;
					}	 */
			
				
			
					// Set user session data
					$session_data = array(
							'identity'             => $user->{$this->identity_column},
							'username'             => $user->username,
							'email'                => $user->email,
							'company'        	   => $user->company,
							'company_display_name' => "Power of ten"
					);

					
					
					$this->session->set_userdata('user',$session_data);
					// Clean login attempts, also update last login time
					$this->power_of_ten_update_district_coordinator_last_login($user->_id);
					//$this->clear_login_attempts($identity);
			
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/dashsession/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
			
					return TRUE;
				}

				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
	 }
	}
	 
	// ---------------------------------------------------------------------------

	/**
	 * Helper : Device Login
	 *
	 * @param  string  $identity              Email id ( identity field )
	 * @param  string  $password              Password
	 * @param  string  $device_unique_number  Device Unique Number
	 *  
	 * @author Selva 
	 */
	 
	public function dashlogin($identity,$password,$device_unique_number,$companyname=FALSE,$remember=FALSE)
	{
		
		$this->trigger_events('pre_login');
	
		$company_name = '';
	
		if(empty($identity) || empty($password) || empty($device_unique_number))
		{
			echo "EMPTY_CREDENTIALS";
			return FALSE;
		}
	
		$identity = strtolower($identity);
	
		$currentdate = Date("Y-m-d");
	
		/* if($additional_data['plan'] == "Diamond")
		 {
		$plan_exp = date('Y-m-d',strtotime('+12 months'));
		}
		elseif($additional_data['plan'] == "Gold")
		{
		$plan_exp = date('Y-m-d',strtotime('+6 months'));
		}
		elseif($additional_data['plan'] == "Silver")
		{
		$plan_exp = date('Y-m-d',strtotime('+3 month'));
		}
		elseif($additional_data['plan'] == "Bronze")
		{
		$plan_exp = date('Y-m-d',strtotime('+1 month'));
		} */
	
		 
		$userdocument = $this->mongo_db
		->select(array($this->identity_column, '_id', 'username', 'email', 'password', 'active', 'last_login','company','subscribed_with','registered_on','subscription_end'))
		->where(array($this->identity_column => (string) $identity))
		->limit(1)
		->get($this->collections['users']);
	
		if($userdocument)
		{
			$this->mongo_db
			->set(array('device_unique_number' => $device_unique_number,'status'=>'online'))
			->where($this->identity_column, (string) $identity)
			->update($this->collections['users']);
	
	
			$user = (object) $userdocument[0];
			$password = $this->hash_password_user_db($user->_id, $password);
			if($password === TRUE)
			{
				// Not yet activated?
				if ($user->active == 0)
				{
					$this->trigger_events('post_login_unsuccessful');
					$this->set_error('login_unsuccessful_not_active');
					return FALSE;
				}
	
				/* // Expired?
					$registered_date = $user->registered_on;
				$registered_date = strtotime($registered_date);
				$expiry_date     = strtotime('+15 days', $registered_date);
				$expiry_date     = date('Y-m-d',$expiry_date);
	
				if($expiry_date == $currentdate || $expiry_date < $currentdate )
				{
				echo "EXPIRED";
				return FALSE;
				}	 */
	
				foreach($user->subscribed_with as $index => $comp_name)
				{
					$company_name = $comp_name;
				}
	
				// Set user session data
				$session_data = array(
						'identity'             => $user->{$this->identity_column},
						'username'             => $user->username,
						'email'                => $user->email,
						'user_id'        	   => $user->_id->{'$id'},
						'old_last_login' 	   => $user->last_login,
						'company'        	   => $user->company,
						'company_display_name' => $company_name
				);
				
				
				
				$this->session->set_userdata('user',$session_data);
				// Clean login attempts, also update last login time
				$this->update_user_last_login($user->_id);
				$this->clear_login_attempts($identity);
	
				$cus_url = base_url().$user->company;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/auth/dashsession/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
	
				return TRUE;
			}
			else
			{
				echo "INCORRECT_PASSWD";
				return FALSE;
			}
	
		}
		else
		{ 
			// ===============================disabled hs device login============
			$hsdocument = $this->mongo_db
			->select('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name')
			->where(array('email' => (string) $identity))
			->limit(1)
			->get($this->collections['panacea_health_supervisors']);
			//->get("disabled_by_vikas_hs"); 
			
			//$this->collections['panacea_health_supervisors']."disabled_by_vikas_hs");
			//->get($this->collections['panacea_health_supervisors']);
	
			if($hsdocument)
			{
				$user = (object) $hsdocument[0];
				$password = $this->hash_password_panacea_health_supervisor($user->_id, $password);
				if($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						echo "EXPIRED";
						return FALSE;
					}
	
					/* // Expired?
					 $registered_date = $user->registered_on;
					$registered_date = strtotime($registered_date);
					$expiry_date     = strtotime('+15 days', $registered_date);
					$expiry_date     = date('Y-m-d',$expiry_date);
	
					if($expiry_date == $currentdate || $expiry_date < $currentdate )
					{
					echo "EXPIRED";
					return FALSE;
					}	 */
					
					$school_details = $this->mongo_db->select(array("school_name"))->where( "school_code",  $user->school_code)->limit ( 1 )->get ( $this->collections ['panacea_schools'] );


	
					// Set user session data
					
					if($school_details){

						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "PANACEA_HS",
							'school_name' 			   => $school_details[0]['school_name'],
							'district' 		   => substr($school_details[0]['school_name'] , strpos ( $school_details[0]['school_name'] , ",")+1, strlen($school_details[0]['school_name']) )
							
 							);
					}else{
						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "PANACEA_HS"
					);
					}
					
					
					
					$this->session->set_userdata('user',$session_data);
					// Clean login attempts, also update last login time
					$this->update_user_last_login($user->_id);
					$this->clear_login_attempts($identity);
	
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/dashsession/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
	
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else {
				// ================TTWREIS HEALTH SUPERVISOR==================================
				$hsdocument_ttwreis = $this->mongo_db
			->select('_id', 'username', 'email', 'password', 'active', 'last_login','company','hs_name')
			->where(array('email' => (string) $identity))
			->limit(1)
			->get($this->collections['ttwreis_health_supervisors']);
	
			if($hsdocument_ttwreis)
			{
				$user = (object) $hsdocument_ttwreis[0];
				$password = $this->hash_password_ttwreis_hs_db($user->_id, $password);
				if($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						echo "EXPIRED";
						return FALSE;
					}
	
					/* // Expired?
					 $registered_date = $user->registered_on;
					$registered_date = strtotime($registered_date);
					$expiry_date     = strtotime('+15 days', $registered_date);
					$expiry_date     = date('Y-m-d',$expiry_date);
	
					if($expiry_date == $currentdate || $expiry_date < $currentdate )
					{
					echo "EXPIRED";
					return FALSE;
					}	 */
	
					$school_details = $this->mongo_db->select(array("school_name"))->where( "school_code",  $user->school_code)->limit ( 1 )->get ( $this->collections ['ttwreis_schools'] );
	
					// Set user session data
					
					if($school_details){

						$school_count = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name', $school_details[0]['school_name'])->count('ttwreis_screening_report_col_2020-2021');

						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "TTWREIS_HS",
							'school_name' 			   => $school_details[0]['school_name'],
							'district' 		   => substr($school_details[0]['school_name'] , strpos ( $school_details[0]['school_name'] , ",")+1, strlen($school_details[0]['school_name']) ),
							'school_count' => $school_count
							);
					}else{
						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "TTWREIS_HS"
					);
					}
					$this->session->set_userdata('user',$session_data);
					// Clean login attempts, also update last login time
					$this->update_user_last_login($user->_id);
					$this->clear_login_attempts($identity);
	
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/dashsession/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
	
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}
			else {
				// ================tmreis HEALTH SUPERVISOR==================================
				$hsdocument_tmreis = $this->mongo_db
			->select('_id', 'username', 'email', 'password', 'active', 'last_login','company','hs_name')
			->where(array('email' => (string) $identity))
			->limit(1)
			->get($this->collections['tmreis_health_supervisors']);
	
			if($hsdocument_tmreis)
			{
				$user = (object) $hsdocument_tmreis[0];
				$password = $this->hash_password_tmreis_hs_db($user->_id, $password);
				if($password === TRUE)
				{
					// Not yet activated?
					if ($user->active == 0)
					{
						echo "EXPIRED";
						return FALSE;
					}
	
					/* // Expired?
					 $registered_date = $user->registered_on;
					$registered_date = strtotime($registered_date);
					$expiry_date     = strtotime('+15 days', $registered_date);
					$expiry_date     = date('Y-m-d',$expiry_date);
	
					if($expiry_date == $currentdate || $expiry_date < $currentdate )
					{
					echo "EXPIRED";
					return FALSE;
					}	 */
	
					$school_details = $this->mongo_db->select(array("school_name"))->where( "school_code",  $user->school_code)->limit ( 1 )->get ( $this->collections ['tmreis_schools'] );
	
					// Set user session data
					
					if($school_details){
						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "TMREIS_HS",
							'school_name' 			   => $school_details[0]['school_name'],
							'district' 		   => substr($school_details[0]['school_name'] , strpos ( $school_details[0]['school_name'] , ",")+1, strlen($school_details[0]['school_name']) )
							);
					}else{
						$session_data = array(
							'identity'             => $user->email,
							'username'             => $user->hs_name,
							'email'                => $user->email,
							'user_id'        	   => $user->_id->{'$id'},
							'old_last_login' 	   => $user->last_login,
							'company'        	   => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "TMREIS_HS"
					);
					}
					$this->session->set_userdata('user',$session_data);
					// Clean login attempts, also update last login time
					$this->update_user_last_login($user->_id);
					$this->clear_login_attempts($identity);
	
					$cus_url = base_url().$user->company;
					$h = json_encode($session_data);
					$str = base64_encode($h);
					redirect($cus_url.'/index.php/auth/dashsession/'.$str);
					$this->trigger_events(array('post_login', 'post_login_successful'));
					$this->set_message('login_successful');
	
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}
			else
			{
				// ================PANACEA DOCTORS==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name',
					'subscribed_with' 
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['panacea_doctors'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_panacea_doctors_db ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'company_display_name' => $user->company_name,
							'user_type'			   => "PANACEA_DOCTOR"
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else
			{
				// ================ttwreis DOCTORS==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name',
					'subscribed_with' 
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_doctors'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_ttwreis_doctor_db ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'company_display_name' => $user->company_name,
							'user_type'			   => "TTWREIS_DOCTOR"
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else
			{
				$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name',
					'subscribed_with' 
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['tmreis_doctors'] );
				
				if ($userdocument) 
				{
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_tmreis_doctor_db ( $user->_id, $password );
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'company_display_name' => $user->company_name,
							'user_type'			   => "TMREIS_DOCTOR"
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			    }
				else
				{
					// ===============================disabled hs device login============
					$hsdocument = $this->mongo_db
					->select('_id', 'username', 'email', 'password', 'active', 'last_login','company','school_code','hs_name')
					->where(array('email' => (string) $identity))
					->limit(1)
					->get($this->collections['bc_welfare_health_supervisors']);
					//->get("disabled_by_vikas_hs"); 
					
					//$this->collections['panacea_health_supervisors']."disabled_by_vikas_hs");
					//->get($this->collections['panacea_health_supervisors']);
			
					if($hsdocument)
					{
						$user = (object) $hsdocument[0];
						$password = $this->hash_password_bc_welfare_health_supervisor($user->_id, $password);
						if($password === TRUE)
						{
							// Not yet activated?
							if ($user->active == 0)
							{
								echo "EXPIRED";
								return FALSE;
							}
			
							/* // Expired?
							 $registered_date = $user->registered_on;
							$registered_date = strtotime($registered_date);
							$expiry_date     = strtotime('+15 days', $registered_date);
							$expiry_date     = date('Y-m-d',$expiry_date);
			
							if($expiry_date == $currentdate || $expiry_date < $currentdate )
							{
							echo "EXPIRED";
							return FALSE;
							}	 */
							
							$school_details = $this->mongo_db->select(array("school_name"))->where( "school_code",  $user->school_code)->limit ( 1 )->get ( $this->collections ['bc_welfare_schools'] );
							
							// Set user session data
							
							if($school_details){

								$school_count = $this->mongo_db->where('doc_data.widget_data.page2.Personal Information.School Name', $school_details[0]['school_name'])->count('bcwelfare_screening_report_col_2020-2021');

								$session_data = array(
									'identity'             => $user->email,
									'username'             => $user->hs_name,
									'email'                => $user->email,
									'user_id'        	   => $user->_id->{'$id'},
									'old_last_login' 	   => $user->last_login,
									'company'        	   => $user->company,
									'company_display_name' => $company_name,
									'user_type'			   => "BCWELFARE_HS",
									'school_name' 			   => $school_details[0]['school_name'],
									'district' 		   => substr($school_details[0]['school_name'] , strpos ( $school_details[0]['school_name'] , ",")+1, strlen($school_details[0]['school_name']) ),
									'school_count' => $school_count
									);
							}else{
								$session_data = array(
									'identity'             => $user->email,
									'username'             => $user->hs_name,
									'email'                => $user->email,
									'user_id'        	   => $user->_id->{'$id'},
									'old_last_login' 	   => $user->last_login,
									'company'        	   => $user->company,
									'company_display_name' => $company_name,
									'user_type'			   => "BCWELFARE_HS"
							);
							}
							
							//log_message("debug","school_details========================9303".print_r($session_data,true));
							
							$this->session->set_userdata('user',$session_data);
							// Clean login attempts, also update last login time
							$this->update_user_last_login($user->_id);
							$this->clear_login_attempts($identity);
			
							$cus_url = base_url().$user->company;
							$h = json_encode($session_data);
							$str = base64_encode($h);
							redirect($cus_url.'/index.php/auth/dashsession/'.$str);
							$this->trigger_events(array('post_login', 'post_login_successful'));
							$this->set_message('login_successful');
			
							return TRUE;
						}
						else
						{
							echo "INCORRECT_PASSWD";
							return FALSE;
						}
					}
					else
					{
						// ================BC Welfare DOCTORS==================================
					$userdocument = $this->mongo_db->select ( array (
							$this->identity_column,
							'_id',
							'username',
							'email',
							'password',
							'active',
							'last_login',
							'company_name',
							'subscribed_with' 
					) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['bc_welfare_doctors'] );
					//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
					
					if ($userdocument) {
						
						$user = ( object ) $userdocument [0];
						$password = $this->hash_password_bc_welfare_doctors_db ( $user->_id, $password );
						//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
						if ($password === TRUE) {
							// Not yet activated?
							if ($user->active == 0) {
								$this->trigger_events ( 'post_login_unsuccessful' );
								$this->set_error ( 'login_unsuccessful_not_active' );
								return FALSE;
							}
							
							//foreach ( $user->subscribed_with as $index => $comp_name ) {
							//	$company_name = $comp_name;
							//}
							
							// Set user session data
							$session_data = array (
									'identity' => $user->{$this->identity_column},
									'username' => $user->username,
									'email' => $user->email,
									'user_id' => $user->_id->{'$id'},
									'old_last_login' => $user->last_login,
									'company' => $user->company_name,
									'company_display_name' => $user->company_name,
									'user_type'			   => "BCWELFARE_DOCTOR",
							);
							$this->session->set_userdata ( 'user', $session_data );
							// Clean login attempts, also update last login time
							$this->update_user_last_login ( $user->_id );
							$this->clear_login_attempts ( $identity );
							
							$cus_url = base_url () . $user->company_name;
							$h = json_encode ( $session_data );
							$str = base64_encode ( $h );
							redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
							$this->trigger_events ( array (
									'post_login',
									'post_login_successful' 
							) );
							$this->set_message ( 'login_successful' );
							
							return TRUE;
						}
						else
						{
							echo "INCORRECT_PASSWD";
							return FALSE;
						}
					}
					else
					{
						// ================RHSO ADMIN logins ==================================
			$cuserdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company'
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['rhso_users'] );
			
			if ($cuserdocument) {
				
				$user = ( object ) $cuserdocument [0];
				//log_message('debug','device_loginnnnnnnnnnnnnnnnn'.print_r($user,true));
				$password = $this->hash_password_rhso_admins_db ( $user->_id, $password );
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company,
							'company_display_name' => $company_name,
							'user_type'			   => "PANACEA_RHSO" 
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}
			else{
				//================PANACEA Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['panacea_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_panacea_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "PANACEA_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
		else{
					//================L3 Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ('l3_admins' );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_l3_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "L3_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
		else{
					//================L3 Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ('tswreis_sports_admins' );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_tswreis_sports_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "TSWREIS_SPORTS_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
		else{
			//================TTWREIS Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_ttwreis_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "TTWREIS_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		} 
		else{
			//================BC Welfare Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['bc_welfare_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_bc_welfare_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "BCWELFARE_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
		else{

			//================TMREIS Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['tmreis_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_tmreis_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "TMREIS_ADMIN"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}
		else{
			$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['superiors'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_superiors ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "SUPERVISOR"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}else
		{
			// ================CRO ADMIN logins ==================================
			$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['cro_collection'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_cro_admins_db ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "PANACEA_RHSO"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				log_message('error','session_data====10191'.print_r($session_data,TRUE));
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}else{

			// ================RCO ADMIN logins ==================================
			$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['rco_collection'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_rco_admins_db ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'user_type' => "PANACEA_RHSO"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
			else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
		}else
		{
			// ================PANACEA Principals==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'contact_person_name',
					'company'
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['panacea_schools'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			 
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_panacea_pc_db ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->contact_person_name,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company,
							'company_display_name' => $user->company,
							'user_type'			   => "PANACEA_PC"
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else{
				// ================PANACEA CCUSER==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name'
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['panacea_cc'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			 
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_panacea_ccuser ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'user_type'			   => "TSWREIS_FO" //TSWREIS Field Officer
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else{
				// ================PANACEA CCUSER==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name'
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_cc'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			 
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_ttwreis_cc ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'user_type'			   => "TTWREIS_FO"  //TTWREIS Field Officers
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else{
				// ================BCWELFARE CCUSER==================================
			$userdocument = $this->mongo_db->select ( array (
					$this->identity_column,
					'_id',
					'username',
					'email',
					'password',
					'active',
					'last_login',
					'company_name'
			) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['bc_welfare_cc'] );
			//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
			 
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_bc_welfare_ccuser ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company_name,
							'user_type'			   => "BCWELFARE_FO"  //BC Welfare Field Officers
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company_name;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}else{
				// ================Psychologist CCUSER==================================
				$userdocument = $this->mongo_db->select ( array (
						$this->identity_column,
						'_id',
						'username',
						'email',
						'password',
						'active',
						'last_login',
						'company'
				) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( 'psycologist_users' );
				//log_message('debug','panaceaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($userdocument,true));
				 
			
			if ($userdocument) {
				
				$user = ( object ) $userdocument [0];
				$password = $this->hash_password_psychologist_user ( $user->_id, $password );
				//log_message('debug','1111111111111111111111111111111111111111111111111111111111111111'.print_r($password,true));
				if ($password === TRUE) {
					// Not yet activated?
					if ($user->active == 0) {
						$this->trigger_events ( 'post_login_unsuccessful' );
						$this->set_error ( 'login_unsuccessful_not_active' );
						return FALSE;
					}
					
					//foreach ( $user->subscribed_with as $index => $comp_name ) {
					//	$company_name = $comp_name;
					//}
					
					// Set user session data
					$session_data = array (
							'identity' => $user->{$this->identity_column},
							'username' => $user->username,
							'email' => $user->email,
							'user_id' => $user->_id->{'$id'},
							'old_last_login' => $user->last_login,
							'company' => $user->company,
							'user_type' => "PSYCHOLOGIST_PANACEA"  
					);
					$this->session->set_userdata ( 'user', $session_data );
					// Clean login attempts, also update last login time
					$this->update_user_last_login ( $user->_id );
					$this->clear_login_attempts ( $identity );
					
					$cus_url = base_url () . $user->company;
					$h = json_encode ( $session_data );
					$str = base64_encode ( $h );
					redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
					$this->trigger_events ( array (
							'post_login',
							'post_login_successful' 
					) );
					$this->set_message ( 'login_successful' );
					
					return TRUE;
				}
				else
				{
					echo "INCORRECT_PASSWD";
					return FALSE;
				}
			}


				else
					{
						echo "INCORRECT_CREDENTIALS";
						return FALSE;
					}
				}
				}
				}
				}
				}
				}
			}
			}
			}
				}
			}
		}
			}
			}
					}
					}
				}
			}
			}
			}
		}
		}
			
		}
	
	}
   
    // ---------------------------------------------------------------------------

	/**
	 * Helper : Device Logout
	 *
	 * @param  string  $identity  Email id ( identity field )
     *	 
	 * @author Selva 
	 */
	 
	 public function dashlogout($identity)
	 {
	    if (empty($identity))
   	    {
   		   $this->set_error('logout_unsuccessful');
   		   return FALSE;
   	    }
		
		$userdocument = $this->mongo_db
   	    ->where($this->identity_column, (string) $identity)
   	    ->limit(1)
   	    ->get($this->collections['users']);
		
		if(count($userdocument) === 1)
   	    {
		    $result = $this->mongo_db
   	        ->set(array('status'=>'offline'))
   	        ->where($this->identity_column, $identity)
   	        ->update($this->collections['users']);
			
			if($result)
			{
			   //delete the remember me cookies if they exist
				if (get_cookie('identity'))
				{
					delete_cookie('identity');
				}
				if (get_cookie('remember_code'))
				{
					delete_cookie('remember_code');
				}

				//Destroy the session
				$this->session->sess_destroy();

				//Recreate the session
				if (substr(CI_VERSION, 0, 1) == '2')
				{
					$this->session->sess_create();
				}

				$this->set_message('logout_successful');
				return TRUE;
			}
            else
            {
                return FALSE;
            }				
		}
	 }

    // ---------------------------------------------------------------------------

	/**
	 * Helper : MASTER APPLICATION CREDENTIALS
	 *
	 * @param  string  $device_unique_number  Device Unique Number
	 *  
	 * @author Selva 
	 */
	 
    public function master_login($device_unique_number)
	{
	   if (empty($device_unique_number))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$document = $this->mongo_db
			->select(array(),array('_id'))
			->where('device_unique_number',$device_unique_number)
			->limit(1)
			->get($this->collections['devices']);
			
		if(count($document) === 1)
        {
            return $document[0];
        }	
	
	}
	
	 // ---------------------------------------------------------------------------

	/**
	 * Helper : Update user details with device details ( during user self registration )
	 *
	 * @param  string  $device_unique_number  Device Unique Number
	 * @param  string  $email                 Email ID
	 * @param  string  $password              Password
	 * @param  string  $additional_data       User related data
	 *  
	 * @author Selva 
	 */
	 
	public function update_user_registration_details($device_unique_number,$email,$password,$additional_data)
	{
	    // Check if email already exists
		 if ($this->identity_column == 'email' && $this->email_check($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		} 
		
	    // IP address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password,$salt);
		
		$data = array(
		     'password'      => $password,
			 'email'         => $email,
			 'ip_address'    => $ip_address,
			 'registered_on' => date("Y-m-d"),
			 'last_login'    => date("Y-m-d H:i:s")
		);
		
		// Store salt in document?
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}
		
		$data = array_merge($additional_data,$data);
		
	    $updated = $this->mongo_db
			->where('device_unique_number',$device_unique_number)
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
	 * Helper : Fetch all support admin documents
	 *
	 * @return object
	 *
	 * @author Selva
	 */
	public function support_admins()
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

		// Execute and return the object itself for the sake of chaining!
		$this->response = $this->mongo_db->get($this->collections['support_admin']);
		return $this;
	}
	
	// ------------------------------------------------------------------------	
	/**
	 * Helper: Returns support admin object by its passed ID.
	 *
	 * @return object
	 */
	public function support_admin($id)
	{
		$this->trigger_events('support_admin');

		// Set query parameters
		$this->limit(1);
		$this->where('_id', new MongoId($id));

		// Build and execute the query
		$this->support_admins();

		return $this;
	}

	
	// ------------------------------------------------------------------------

	/**
	 * Inserts a user document into users collection.
	 *
	 * @return bool
	 */
	public function register_support_admin($username, $password, $email, $additional_data = array())
	{
		//$this->trigger_events('pre_register');
		$this->load->config('ion_auth', TRUE);
        $admin_manual_activation = $this->config->item('admin_email_activation','ion_auth');
		
		// Check if email already exists
		if ($this->identity_column == 'email' && $this->email_check_support_admin($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return FALSE;
		}

		// IP address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);

		// New support admin document
		$data = array(
			'username'   => $username,
			'password'   => $password,
			'email'      => $email,
			'ip_address' => $ip_address,
			'created_on' => time(),
			'last_login' => time(),
			'active'     => 1,
			'first_name' => $additional_data['first_name'],
			'last_name'  => $additional_data['last_name'],
			'company'    => $additional_data['company'],
			'phone'      => $additional_data['phone'],
			'level'      => implode(" " ,$additional_data['level'])
		);

		// Store salt in document?
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}
		
		$this->trigger_events('extra_set');
		// Insert new document and store the _id value
		$id = $this->mongo_db->insert($this->collections['support_admin'], $data);

		$this->trigger_events('post_register');

		// Return new document _id or FALSE on failure
		return isset($id) ? $id : FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Checks email.
	 *
	 * @return bool
	 */
	public function email_check_support_admin($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');
		$email = new MongoRegex('/^'.$email.'$/i');
		return count($this->mongo_db
			->where('email', $email)
			->get($this->collections['support_admin'])) > 0;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Deletes a support admin document by its ID.
	 *
	 * @return bool
	 *
	 * @author Selva
	 */
	public function delete_support_admin($id)
	{
		$this->trigger_events('pre_delete_support_admin');

		// Delete user document (groups association will also be deleted)
		$deleted = $this->mongo_db
			->where('_id', new MongoId($id))
			->delete($this->collections['support_admin']);

		if ( ! $deleted)
		{
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		return TRUE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Updates a support admin document.
	 *
	 * @return bool
	 */
	public function update_support_admin($id, array $data)
	{
		$this->trigger_events('pre_update_support_admin');

		// Get user document to update
		$user = $this->support_admin($id)->document();
		
		//log_message('debug','$user=====4892=====update_support_admin'.print_r($user,true));

		// If we're updating user document with a new identity
		// and the identity is not available to register, bam!
		if (array_key_exists($this->identity_column, $data) &&
			$this->identity_check_for_support_admin($data[$this->identity_column]) &&
			$user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->set_error('account_creation_duplicate_' . $this->identity_column);
			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Hash new password
		if (array_key_exists('password', $data))
		{
			if( ! empty($data['password']))
			{
				$data['password'] = $this->hash_password($data['password'], $user->salt);
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

		$updated = $this->mongo_db
			->where('_id', new MongoId($user->id))
			->set($data)
			->update($this->collections['support_admin']);

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
	 * Checks identity field.
	 *
	 * @return bool
	 */
	protected function identity_check_for_support_admin($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		return count($this->mongo_db
			->where($this->identity_column, $identity)
			->get($this->collections['support_admin'])) > 0;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Validates and removes activation code of support admin
	 *
	 * @author Selva
	 */
	public function activate_support_admin($id, $code = FALSE)
	{
		$this->trigger_events('pre_activate');
        
		// If activation code is set
		if ($code !== FALSE)
		{
			// Get identity value of the activation code
			$docs = $this->mongo_db
				->select($this->identity_column)
				->where('activation_code', $code)
				->limit(1)
				->get($this->collections['support_admin']);
				
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
				->update($this->collections['support_admin']);
		}
		// Activation code is not set
		else
		{
			$this->trigger_events('extra_where');
			$updated = $this->mongo_db
				->where('_id', new MongoId($id))
				->set(array('activation_code' => NULL, 'active' => 1))
				->update($this->collections['support_admin']);
		}


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
	 * Updates a support admin document with an activation code. ( deactivate the support admin )
	 *
	 * @author Selva
	 */
	public function deactivate_support_admin($id = NULL)
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

		$updated = $this->mongo_db
			->where('_id', new MongoId($id))
			->set(array('activation_code' => $activation_code, 'active' => 0))
			->update($this->collections['support_admin']);

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
	
	/**
	 * Helper : Takes a password and validates it against an entry in the customer collection.
	 *
	 * @param string $id         MongoID
	 * @param string $password   Password
	 *
	 * @author Vikas
	 */
	
	public function hash_password_ghmc_admin_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}
	
		$this->trigger_events('extra_where');
	
		$document = $this->mongo_db
		->select(array('password', 'salt'))
		->where('_id', new MongoId($id))
		->limit(1)
		->get($this->collections['ghmc_admin']);
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
	
	   // ---------------------------------------------------------------------------
   
   /**
    * Helper : Device Login
    *
    * @param  string  $identity              Email id ( identity field )
    * @param  string  $password              Password
    *
    * @author Vikas
    */
   
   public function dashlogin_patient($identity, $password)
   {
   	//log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn dashlogin_patient');
   	$this->trigger_events('pre_login');
   
   	$company_name = '';
   
   	if (empty($identity) || empty($password))
   	{
   		$this->set_error('login_unsuccessful');
   		return FALSE;
   	}
   	
   	//$this->trigger_events('extra_where');
   	$currentdate = date("Y-m-d");
   	$userdocument = $this->mongo_db
   	->whereLike('unique_id', $identity)
   	->limit(1)
   	->get($this->collections['form_users']);
   	
   	if($userdocument)
   	{
   
   		$user = (object) $userdocument[0];
   		$password = $this->hash_password_patient_db($user->_id, $password);
   		if ($password === TRUE)
   		{
   
   			// Set user session data
   			$session_data = array(
   					'identity'             => $user->unique_id,
   					'dob'             	   => $user->dob,
   					'patient_name'         => $user->patient_name,
   					'user_id'        	   => $user->_id->{'$id'},
   					'company'        	   => $user->company_name,
   					'mobile' 			   => $user->mobile,
   					'grouped_under'		   => $user->grouped_under,
   			);
   			$this->session->set_userdata('patient',$session_data);
   			// Clean login attempts, also update last login time
   			$this->update_user_last_login($user->_id);
   			$this->clear_login_attempts($identity);
   
   			$cus_url = base_url().$user->company_name;
   			$h = json_encode($session_data);
   			$str = base64_encode($h);
   			redirect($cus_url.'/index.php/auth/dashsession/'.$str);
   			$this->trigger_events(array('post_login', 'post_login_successful'));
   			$this->set_message('login_successful');

   			return TRUE;
   		}
   
   	}
   }
   
   	/**
	 * Helper : Device Login for healthcare
	 *
	 * @param string $identity
	 *        	Email id ( identity field )
	 * @param string $password
	 *        	Password
	 *        	
	 * @author Vikas
	 */
	public function dashlogin_healthcare($identity, $password) {
		$this->trigger_events ( 'pre_login' );
		
		$company_name = '';
		
		if (empty ( $identity ) || empty ( $password )) {
			$this->set_error ( 'login_unsuccessful' );
			return FALSE;
		}
		
		// $this->trigger_events('extra_where');
		$currentdate = date ( "Y-m-d" );
		
		//$userdocument = $this->mongo_db->whereLike ( 'unique_id', $identity )->limit ( 1 )->get ( $this->collections ['form_users'] );
		
		//================PANACEA Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['panacea_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_panacea_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'controller' => "healthcare_app"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
		}//================superiors logins====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['superiors'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_superiors ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'controller' => "healthcare_app"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
		}else {
			
			
			//================TTWREIS Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['ttwreis_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_ttwreis_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'controller' => "ttwreis_app"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
		}else{
			
			//================TMREIS Admin====================
		$userdocument = $this->mongo_db->select ( array (
										$this->identity_column,
										'_id',
										'username',
										'email',
										'password',
										'active',
										'last_login',
										'company_name',
										'plan_expiry',
										'registered_on',
										'plan' 
								) )->where ( $this->identity_column, ( string ) $identity )->limit ( 1 )->get ( $this->collections ['tmreis_admins'] );
		
		if ($userdocument) {
			
			$user = ( object ) $userdocument [0];
			$password = $this->hash_password_tmreis_admins ( $user->_id, $password );
			$password = $password;
			if ($password === TRUE) {
				
				$session_data = array (
						'identity' => $user->{$this->identity_column},
						'username' => $user->username,
						'email' => $user->email,
						'user_id' => $user->_id->{'$id'},
						'old_last_login' => $user->last_login,
						'company' => $user->company_name,
						'plan' => $user->plan,
						'registered' => $user->registered_on,
						'expiry' => $user->plan_expiry ,
						'controller' => "tmreis_app"
				);
				
				
				$this->session->set_userdata ( 'customer', $session_data );
				// Clean login attempts, also update last login time
				$this->update_user_last_login ( $user->_id );
				$this->clear_login_attempts ( $identity );
				
				$cus_url = base_url () . $user->company_name;
				$h = json_encode ( $session_data );
				$str = base64_encode ( $h );
				redirect ( $cus_url . '/index.php/auth/dashsession/' . $str );
				$this->trigger_events ( array (
						'post_login',
						'post_login_successful' 
				) );
				$this->set_message ( 'login_successful' );
				
				return TRUE;
			}
		}
			
		}
		}
	}
	
	/**
	 * Checks credentials and logs the passed user in if possible.
	 *
	 * @return bool
	 */
	public function ghmc_login($identity, $password)
	{
		if (empty($identity) || empty($password))
		{
			//$this->set_error('login_unsuccessful');
			return FALSE;
		}
	
		//$this->trigger_events('extra_where');
		$currentdate = date("Y-m-d");
		$document = $this->mongo_db
		->whereLike('unique_id', $identity)
		->limit(1)
		->get($this->collections['ghmc_admin']);
	
		// If customer document found
		if (count($document) === 1)
		{
			$user = (object) $document[0];
	
			$password = $this->hash_password_ghmc_admin_db($user->_id, $password);
	
			if ($password === TRUE)
			{
				// Set user session data
				$session_data = array(
						'identity'       =>  $user->unique_id,
						'unique_id'       => $user->unique_id,
						'user_id'        => $user->_id->{'$id'},
						'company'        => $user->company_name,
				);
	
				$this->input->set_cookie('customer', $session_data, 3600*2);
	
				$cus_url = base_url().$user->company_name;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				redirect($cus_url.'/index.php/auth/set_ghmc_session/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
				return TRUE;
			}
		}
		//$this->trigger_events('post_login_unsuccessful');
		//$this->set_error('login_unsuccessful');
		return FALSE;
	}
	
	/**
	 * Helper : Device Login
	 *
	 * @param  string  $identity              Email id ( identity field )
	 * @param  string  $password              Password
	 *
	 * @author Vikas
	 */
	
	public function dashlogin_ghmc($mobile)
	{
		//log_message('debug','innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn dashlogin_ghmc');
		$this->trigger_events('pre_login');
		 
		$company_name = '';
		 
		if (empty($mobile))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
		 
		//$this->trigger_events('extra_where');
		$currentdate = date("Y-m-d");
		$userdocument = $this->mongo_db
		->whereLike('mobile', $mobile)
		->limit(1)
		->get($this->collections['ghmc_users']);
		//log_message('debug','11111111111111111111111111111111111111111111111'.print_r($userdocument,true));
		if($userdocument)
		{
	
			$user = (object) $userdocument[0];
			//$password = $this->hash_password_patient_db($user->_id, $password);
			//if ($password === TRUE)
			{
					
				// Set user session data
				$session_data = array(
						'identity'             => $user->mobile,
						'dob'             	   => $user->dob,
						'patient_name'         => $user->name,
						'user_id'        	   => $user->_id->{'$id'},
						'company'        	   => $user->company_name,
						'mobile' 			   => $user->mobile,
						'grouped_under'		   => $user->grouped_under,
				);
				$this->session->set_userdata('customer',$session_data);
				// Clean login attempts, also update last login time
				//$this->update_user_last_login($user->_id);
				//$this->clear_login_attempts($identity);
					
				$cus_url = base_url().$user->company_name;
				$h = json_encode($session_data);
				$str = base64_encode($h);
				echo "login_successful";
				redirect($cus_url.'/index.php/auth/dashsession/'.$str);
				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');
				 
				return TRUE;
			}
		}else{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
	}
	 
}
// END Ion_auth_mongodb_model Class

/* End of file ion_auth_mongodb_model.php */
/* Location: ./application/modules/auth/models/ion_auth_mongodb_model.php */
