<?php  ini_set ( 'memory_limit', '1G' );
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class PaaS_common_lib 
{
	
	// --------------------------------------------------------------------

    /**
	* Constructor
	*
	*/

	public function __construct()
	{
		$this->ci = &get_instance();         // In custom libraries we need to get instance of ci to make use of ci core classes (here we use Loader class)
		
		$this->ci->load->library('ion_auth');
		$this->ci->load->library('session');
		$this->ci->load->helper('url');
		$this->ci->load->helper('paas');
		$this->ci->lang->load('auth');
	
	}

    
    // --------------------------------------------------------------------

	/**
	 * Generating pagination config values 
	 *
	 *
	 * @param	int	    $total_rows       Number of total rows
	 * @param	int	    $per_page         Per page count
	 *
	 * @return  array
	 *
	 * @author  Selva
	 */

     public function set_paginate_options($total_rows,$per_page)
     {
    	$config = array();

    	$config['base_url']         = site_url() .'/'.$this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2);
		$config['use_page_numbers'] = 'TRUE';
		$config['per_page']         = $per_page;
		$config['total_rows']       = $total_rows;
		$config['uri_segment']      = 3;
		$config['full_tag_open']    = '<div class="text-center"><ul class="pagination pagination-xs no-margin">';
		$config['full_tag_close']   = '</ul></div><!--pagination-->';
		$config['first_link']       = '&laquo; First';
		$config['first_tag_open']   = '<li class="prev page">';
		$config['first_tag_close']  = '</li>';
		$config['last_link']        = 'Last &raquo;';
		$config['last_tag_open']    = '<li class="next page">';
		$config['last_tag_close']   = '</li>';
		$config['next_link']        = 'Next &rarr;';
		$config['next_tag_open']    = '<li class="next page">';
		$config['next_tag_close']   = '</li>';
		$config['prev_link']        = '&larr; Previous';
		$config['prev_tag_open']    = '<li class="prev page">';
		$config['prev_tag_close']   = '</li>';
		$config['cur_tag_open']     = '<li class="active"><a href="">';
		$config['cur_tag_close']    = '</a></li>';
		$config['num_tag_open']     = '<li class="page">';
		$config['num_tag_close']    = '</li>';
		$choice = $config["total_rows"] / $config["per_page"];

        return $config;
     }

     // --------------------------------------------------------------------

	/**
	 * Helper : Fetching enterprise admin dashboard analytics values 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Selva
	 */

     public function admin_dashboard_analytics_values()
     {
     	 $data         = array();
     	 $pages_saved  = '';
		 $trees_saved  = '';

         //-----graphs-------//
		 $appgraph = $this->ci->ion_auth->graph_apps();
		 $docgraph = $this->ci->ion_auth->graph_docs();
		 
		 //-----plan details-------//
         $customer_details = $this->ci->session->userdata("customer");
		 $subscribed_plan  = $customer_details['plan'];
		 $expiryday        = strtotime($customer_details['expiry']);
		 $currentday       = strtotime(date("Y-m-d"));
		 $daysleft         = $expiryday - $currentday;
				
         //-----DB space details-------//
         $dbsizeinbytes = $this->ci->ion_auth->getdbsize();
         $dbsize        = db_size_convert($dbsizeinbytes);

         //-----pages,trees saved details-------//
         $page_details       = $this->ci->ion_auth->savedpappercount();
         $page_details_count = count($page_details);

		 for($ii=0;$ii<$page_details_count;$ii++)
		 {
		    $pages_saved  += $page_details[$ii]['pages_saved'];
			$trees_saved  += $page_details[$ii]['trees_saved'];
		 }

         //-----Enterprise customer details-----//
         $customer   = $this->ci->ion_auth->customer()->row();
         $customerid = $customer->id;

         //-----File system space details-------//
         $fsize    = foldersize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT);
         $filesize = db_size_convert($fsize);
		 
		 //----- Total Disk Space Used -----//
         $disk_space_used = $dbsize + $filesize ;
		 
		 log_message('debug','$disk_space_used=====139=='.print_r($dbsize,true));
		 log_message('debug','$disk_space_used=====140=='.print_r($filesize,true));
		 log_message('debug','$disk_space_used=====141=='.print_r($disk_space_used,true));
         
         //data
		 $data['appgraphs']          = implode(",", $appgraph);
		 $data['docgraphs']          = implode(",", $docgraph);
		 $data['numberofunfinished'] = $this->ci->ion_auth->unfinished_workflow();
		 $data['numberoffinished']   = $this->ci->ion_auth->finished_workflow();
		 $data['appscnt']            = $this->ci->ion_auth->apps();
		 
		 $data['docs']               = $this->ci->ion_auth->docs();
		 $data['analytics']          = $this->ci->ion_auth->savedpatterns();
		 $data['plan_details']       = $this->ci->ion_auth->plan_details($subscribed_plan);
		 $data['dayss']              = floor($daysleft/3600/24)."&nbsp;".$this->ci->lang->line('admin_dash_days');
		 $data['api']                = $this->ci->ion_auth->api($customerid);
		 $data['dbsize']             = $disk_space_used;
		 $data['papersavedcount']    = $pages_saved;
		 $data['treesavedcount']     = $trees_saved;
		 $data['updType']            = '';

		 return $data;
     }
     
     /**
      * Helper : Fetching enterprise admin dashboard analytics values
      *
      *
      * @return  array
      *
      * @author  Selva
      */
     
     public function sub_admin_dashboard_analytics_values()
     {
     	$data         = array();
     	$pages_saved  = '';
     	$trees_saved  = '';
     
     	//-----graphs-------//
     	$appgraph = $this->ci->ion_auth->graph_apps();
     	$docgraph = $this->ci->ion_auth->graph_docs();
     		
     	//-----plan details-------//
     	$customer_details = $this->ci->session->userdata("customer");
     	log_message('debug','cccccccccccccccccccccccccccccccccccccccccc'.print_r($customer_details,true));
     	$subscribed_plan  = $customer_details['plan'];
     	$expiryday        = strtotime($customer_details['expiry']);
     	$currentday       = strtotime(date("Y-m-d"));
     	$daysleft         = $expiryday - $currentday;
     
     	//-----DB space details-------//
     	$dbsizeinbytes = $this->ci->ion_auth->getdbsize();
     	$dbsize        = db_size_convert($dbsizeinbytes);
     
     	//-----pages,trees saved details-------//
     	$page_details       = $this->ci->ion_auth->savedpappercount();
     	$page_details_count = count($page_details);
     
     	for($ii=0;$ii<$page_details_count;$ii++)
     	{
     	$pages_saved  += $page_details[$ii]['pages_saved'];
     			$trees_saved  += $page_details[$ii]['trees_saved'];
     	}
     
     	//-----Enterprise customer details-----//
     	$customer   = $this->ci->ion_auth->sub_admins()->row();
     	$customerid = $customer->id;
     
     	//-----File system space details-------//
     	$fsize    = foldersize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT);
     	$filesize = db_size_convert($fsize);
     
     	//----- Total Disk Space Used -----//
     	$disk_space_used = $dbsize + $filesize ;
     	 
     	//data
     	$data['appgraphs']          = implode(",", $appgraph);
     	$data['docgraphs']          = implode(",", $docgraph);
     	$data['numberofunfinished'] = $this->ci->ion_auth->unfinished_workflow();
     		 $data['numberoffinished']   = $this->ci->ion_auth->finished_workflow();
     		 $data['appscnt']            = $this->ci->ion_auth->apps();
     		 $data['analytics_apps']     = $this->ci->ion_auth->apps_for_dashboard_analytics();
     		 $data['docs']               = $this->ci->ion_auth->docs();
     		 $data['analytics']          = $this->ci->ion_auth->savedpatterns();
     		 $data['plan_details']       = $this->ci->ion_auth->plan_details($subscribed_plan);
     		 $data['dayss']              = floor($daysleft/3600/24)."&nbsp;".$this->ci->lang->line('admin_dash_days');
     		 $data['api']                = $this->ci->ion_auth->api($customerid);
		 $data['dbsize']             = $disk_space_used;
     		 $data['papersavedcount']    = $pages_saved;
     		 $data['treesavedcount']     = $trees_saved;
     		 $data['updType']            = '';
     
     		 return $data;
     }
	 
	 /**
      * Helper : Fetching enterprise admin dashboard events and feedback bubble count values
      *
      *
      * @return  array
      *
      * @author  Vikas
      */
	 public function admin_bubble_count(){
		 
		 //bubble count for events and feedbacks
		$data['event_new_count']			= $this->ci->ion_auth->get_event_requests_count();
		$data['event_edit_count']			= $this->ci->ion_auth->get_event_requests_edit_count();
		$data['event_edit_count_total']		= $data['event_new_count']+$data['event_edit_count'];
		$data['feedback_new_count']			= $this->ci->ion_auth->get_feedback_requests_count();
		$data['feedback_edit_count']		= $this->ci->ion_auth->get_feedback_requests_edit_count();
		$data['feedback_edit_count_total']	= $data['feedback_new_count']+$data['feedback_edit_count'];
		
		return $data;
	 }
	 
	
}