<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Analytics_model extends CI_Model
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
    	$this->load->config('email');
		$this->collections = $this->config->item('collections','ion_auth');
    	$this->load->config('ion_auth', TRUE);
    	$this->load->config('mongodb',TRUE);
    	$this->load->library('session');
    	
    }
   
     /**
     * update the pages,trees details
     *
     * @param application id
     *
     * @access public
     * 
     */   
    public function update_pages_saved($appid)
	{
		$this->mongo_db->where(array('_id' => $appid));
		$this->mongo_db->select(array('pages'));
		$pages_with_id = $this->mongo_db->get($this->collections['records']);
		foreach($pages_with_id as $pagedata)
		{
			$pages = $pagedata['pages'];
		}
		$this->mongo_db->where(array('app_id' => $appid));
		$this->mongo_db->select(array('pages_saved'));
		$previouspages_with_id = $this->mongo_db->get($this->collections['analytics_data']);
		foreach($previouspages_with_id as $previouspagedata)
		{
			$previouspages = $previouspagedata['pages_saved'];
		}
		$newpagescount  = $previouspages + $pages;
		$newtreescount = round($newpagescount/8333);
		$this->mongo_db->where(array('app_id' => $appid))->set(array('pages_saved'=>$newpagescount,'trees_saved'=>$newtreescount))->update($this->collections['analytics_data']);
    } 
   
}