<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		log_message('debug','in------------cust---------dashboard'.print_r($this->config->item('language'),true));
		//$language = $this->session->userdata("language");
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		log_message('debug','in------------cust---after----------------dashboard'.print_r($this->config->item('language'),true));
		
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('url');
		$this->load->helper('paas');
		$this->load->helper('file');
		$this->load->helper('language');
		$this->load->library('excel');
		$this->load->library('session');
		$this->load->library('paas_common_lib');
		$this->load->library('bhashsms');
		$this->load->model('template_model');
		//$this->config->load('config', TRUE);
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->lang->load('ion_auth');
		$this->identity_column = $this->config->item('identity', 'ion_auth');

	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Default page after login
	 *  
	 * @author Vikas 
	 */

	function index()
	{
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect(URC.'auth/login');
		}
		else
		{
			if(!$this->ion_auth->is_user())
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : "Logged In Successfully";
	
				$total_rows = $this->ion_auth->appcount();

                //---pagination--------//
	   	        $config = $this->paas_common_lib->set_paginate_options($total_rows,5);
	   	        $config['prefix'] = 'to_dashboard/';
	   	        
					
				//Initialize the pagination class
				$this->pagination->initialize($config);
					
				//control of number page
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
					
				//find all the categories with paginate and save it in array to past to the view
				$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);
					
				//create paginate큦 links
				$this->data['links'] = $this->pagination->create_links();
					
				//number page variable
				$this->data['page'] = $page;
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
	
				// other analytics values
				$data = $this->paas_common_lib->admin_dashboard_analytics_values();
				$this->data = array_merge($this->data,$data);

				$this->_render_page('admin/admin_dash', $this->data);
			}
			elseif($this->ion_auth->is_user() && !$this->ion_auth->is_plan_active())
			{
			    $this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect(URC.'auth/login');
			}
			elseif ($this->ion_auth->is_user() && $this->ion_auth->is_plan_active())
			{
				$this->session->set_flashdata('message', "Logged In Successfully");
				redirect('web/index', 'refresh');
			}
			else
			{
			
			}
		}
	}
 
    // ------------------------------------------------------------------------

	/**
	 * Helper: Navigate to dashboard
	 *  
	 * @author Vikas 
	 */

    function to_dashboard()
	{
	   	$this->check_for_admin();
	   	$this->check_for_plan('to_dashboard');

        $total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,5);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);

		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

        // other analytics values
		$data = $this->paas_common_lib->admin_dashboard_analytics_values();
		$this->data = array_merge($this->data,$data);	 

		$this->data['message'] = ''; 		

		$this->_render_page('admin/admin_dash', $this->data);
	   
	}
	 
	// ------------------------------------------------------------------------

	/**
	 * Helper: List groups of enterprise
	 *  
	 * @author Vikas 
	 */

    function groups() 
    {
        $this->check_for_admin();
        $this->check_for_plan('groups');
		    
		$user    = $this->session->userdata("customer");
	    $company = $user['company'];
		$this->load->model('Workflow_Model');
		
		$this->data['groups']  = $this->Workflow_Model->getgroups($company);
		$this->data['message'] = $this->ion_auth->messages();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_groups',$this->data);
			
    
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Enterprise admin profile
	 *  
	 * @author Vikas 
	 */

	function admin_profile()
	{
		$this->check_for_admin();
		$this->check_for_plan('admin_profile');
		
		
	    $total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		
        //create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

        //number page variable
		$this->data['page'] = $page;

		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
	  
	    $u = $this->session->userdata("customer");
	    $email = $u['email'];
	    $this->data['last_login'] = $u['old_last_login'];
		$this->data['email'] = $email;

	    $this->load->model('Workflow_Model');
	    $this->data['profile_data'] = $this->Workflow_Model->admin_profile_data($email);
	    $this->data['message'] = "Admin Profile";
        $this->data['myapps'] = $this->ion_auth->MYapps($config['per_page'], $page);
	   
	    $this->_render_page('admin/admin_dash_profile', $this->data);
	}

    // ------------------------------------------------------------------------

	/**
	 * Helper: Application create page
	 *  
	 * @author Selva ( Modified by Vikas) 
	 */

	function app_prop($template = FALSE,$updType = FALSE)
	{
	 	$this->check_for_admin();
	 	$this->check_for_plan('app_prop');
		
		$this->data['language'] = $this->input->cookie('language');
        $this->data['files'] = $this->template_model->galary_img();

	 	$this->data['title']    ="App Properties";
        $this->data['updType']  = 'create';
        $this->data['app_over'] = TRUE;
        $this->data['customer_details'] = $this->ion_auth->customer()->row();
         
        $temp = json_decode($template,false);

        $this->data['updType'] = 'create';
		if ($updType == 'edit')
		{
		    $this->data['template'] = $temp;
			$this->data['updType'] = 'edit';
	    }
	    elseif ($updType == 'use')
		{
			$this->data['template'] = $temp;
			$this->data['updType'] = '';
		}
		elseif ($updType == 'draft')
		{
			$this->data['template'] = $temp;
			$this->data['updType'] = 'draft';
		}

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		   
		//validate application creation limit
		if(!$this->ion_auth->check_app_limit())
		{
		   	$this->data['message'] = "Application design limit over!";
		   	$this->data['app_over'] = FALSE;
		}
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	    $this->_render_page('template/design_template-08', $this->data);
		      
	}

      //***************Applications***************START***************//

      // --------------------------------------------------------------------

	  /**
	  * Helper : Listing private apps 
	  *
	  * @author  Selva (Modified by Sekar)
	  *
	  * 
	  */

       function apps_allapps()
      {
	    $this->check_for_admin();
	    $this->check_for_plan('apps_allapps');

        $total_rows = $this->ion_auth->privateappscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['privateapps'] = $this->ion_auth->private_apps($config['per_page'], $page);
		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    $this->data['appcount'] = $total_rows;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_apps_new', $this->data);

     }

     // --------------------------------------------------------------------

	 /**
	  * Helper : Listing MY apps ( Apps created by the respective logged in enterprise admin)
	  *
	  * @author  Selva (Modified by Sekar)
	  *
	  * 
	  */

      function apps_myapps()
     {
		 $this->check_for_admin();
		 $this->check_for_plan('apps_myapps');

         $total_rows = $this->ion_auth->myappscount(); 

	     //---pagination--------//
		 $config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		 //Initialize the pagination class
		 $this->pagination->initialize($config);

		 //control of number page
		 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		 //find all the categories with paginate and save it in array to past to the view
		 $this->data['myapps'] = $this->ion_auth->MYapps($config['per_page'], $page);
		
		 //create paginate큦 links
		 $this->data['links'] = $this->pagination->create_links();

		 //number page variable
		 $this->data['page'] = $page;
			
		 $this->data['appcount'] = $total_rows; 

		 //set the flash data error message if there is one
		 $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		 
		 //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
				
		 $this->_render_page('admin/admin_dash_apps_myapps', $this->data);

      }

      // --------------------------------------------------------------------

	 /**
	  * Helper : Listing community apps 
	  *
	  * @author  Selva
	  *
	  * 
	  */

	  function apps_community()
	  {
		 $this->check_for_admin();
		 $this->check_for_plan('apps_community');
		
		 //set the flash data error message if there is one
		 $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		 //list the apps
		 $this->data['galleryapps'] = $this->ion_auth->gallery_apps();
		 
		 //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		 $this->_render_page('admin/admin_dash_apps_community', $this->data);
	
	  }

      // --------------------------------------------------------------------

	 /**
	  * Helper : Listing community apps ( By selected category)
	  *
	  * @author  Selva (Modified by Sekar)
	  *
	  * 
	  */

      function community_app_select($category)
      { 
	
	      $this->check_for_admin();
	      $this->check_for_plan('community_app_select');

          $total_rows = $this->ion_auth->communityappscount($category); 

          //---pagination--------//
	   	  $config = $this->paas_common_lib->set_paginate_options($total_rows,10);

          //Initialize the pagination class
		  $this->pagination->initialize($config);

		  //control of number page
		  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		

		  //find all the categories with paginate and save it in array to past to the view
		  $this->load->model('Workflow_Model');
		  $this->data['applist'] = $this->Workflow_Model->select_community_app($config['per_page'], $page, $category);
		  $this->data['message'] = $category." Apps";
		  $this->data['category'] = $category;
		
		  //create paginate큦 links
		  $this->data['links'] = $this->pagination->create_links();

		  //number page variable
		  $this->data['page'] = $page;

		  $this->data['appcount'] = $total_rows;
		  
		  //bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);

          $this->_render_page('admin/admin_dash_apps_community',$this->data);

        }

       
     // --------------------------------------------------------------------

	  /**
	  * Helper : Listing shared apps 
	  *
	  * @author  Sekar
	  *
	  * 
	  */

      function apps_shared()
      {
	     $this->check_for_admin();
	     $this->check_for_plan('apps_shared');

         $total_rows = $this->ion_auth->sharedappscount();

         //---pagination--------//
	   	 $config = $this->paas_common_lib->set_paginate_options($total_rows,10);

         //Initialize the pagination class
		 $this->pagination->initialize($config);

		 //control of number page
		 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;


		 //find all the categories with paginate and save it in array to past to the view
		 $this->data['sharedapps'] = $this->ion_auth->shared_apps($config['per_page'], $page);
		 //create paginate큦 links
		 $this->data['links'] = $this->pagination->create_links();

		 //number page variable
		 $this->data['page'] = $page;

		 $this->data['appcount'] = $total_rows;

	     //set the flash data error message if there is one
	     $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		 
		 //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
         $this->_render_page('admin/admin_dash_apps_shared', $this->data);

      }

     // ------------------------------------------------------------------------

	 /**
	 * Helper: Lists the apps saved as draft
	 *  
	 * @author Selva 
	 */
	 
	 function drafts()
	 {
		$this->check_for_admin();
		$this->check_for_plan('drafts');
		
	    $total_rows = $this->ion_auth->draftcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['draftapps'] = $this->ion_auth->get_draft_apps($config['per_page'],$page);
		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		$this->data['draftcount'] = $total_rows;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
	   
	    $this->_render_page('admin/admin_dash_apps_draft',$this->data);
	 }

      //***************Applications***************END***************//

      //***************Analytics***************START***************//

      // --------------------------------------------------------------------

	  /**
	  * Helper : Analytics by enterprise admin 
	  *
	  * @author  Vikas
	  *
	  * 
	  */

	  function query_app($id,$updType = FALSE)
     {
    	$this->check_for_admin();
    	$this->check_for_plan('query_app');
    	$this->query($this->ion_auth->get_app_temp($id),$updType);
     }

	 // --------------------------------------------------------------------

	/**
	* Helper : Analytics by enterprise admin 
	*
	* @author  Vikas ( Modified by Sekar)
	*
	* 
	*/

    function query($template = FALSE, $updType)
    {
    	$this->check_for_admin();
    	$this->check_for_plan('query');
       
        $total_rows = $this->ion_auth->appcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['apps'] =$this->ion_auth->paginate_all($config['per_page'], $page);

		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

        //other analytics values
		$data = $this->paas_common_lib->admin_dashboard_analytics_values();

		$this->data = array_merge($this->data,$data);

		if ($updType == 'query')
    	{
			$this->data['template'] = json_decode($template,false);
	    	$this->data['updType'] = 'query';
	    	$this->data['apps'] = $this->ion_auth->apps();
        }

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

    	$this->_render_page('admin/admin_dash', $this->data);
    			
    	
    }

    // --------------------------------------------------------------------

	/**
	* Helper : Save a pattern 
	*
	* @author  Selva 
	*
	* 
	*/

	function savepattern()
	{
	  	$this->check_for_admin();
	  	$this->check_for_plan('savepattern');
	  	
	    $pattern    = base64_decode($_POST['saved_query']);
	    $appid      = base64_decode($_POST['id']);
		$appname    = base64_decode($_POST['name']);
		$title      = $_POST['pattern_title'];
		$des        = $_POST['pattern_description'];
        $gtype      = $_POST['graphtyp'];
        $user 		= $this->session->userdata("customer");
		$user_email = $user['email'];
	  	
		$insertdata = array(
			'title'       => $title,
			'description' => $des,
			'app_id' 	  => $appid,
			'pattern'     => $pattern,
			'app_name'    => $appname,
			'graph_type'  => $gtype,
			'query_user'  => $user_email
		);

		$this->load->model('Workflow_Model');
		$saveddata = $this->Workflow_Model->save_analytics_pattern($insertdata);
	    $this->to_dashboard();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Delete saved query pattern
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function delete_saved_pattern($id)
	{
		$this->check_for_admin();
		$this->check_for_plan('delete_my_app');
	
		$this->ion_auth->delete_saved_pattern($id);
		
		redirect('dashboard/to_dashboard');
	}
  
    // --------------------------------------------------------------------

	/**
	* Helper : Retrieve the saved analytics pattern 
	*
	* @param  string  $id         Application id
	* @param  string  $collection Collection name
	*
	* @author  Selva 
	*
	* 
	*/

    function get_saved_pattern($id,$collection)
    {
  	  $this->check_for_admin();
  	  $this->check_for_plan('get_saved_pattern');
  	
      $this->load->model('Workflow_Model');
      $this->data['pattern'] = $this->Workflow_Model->get_saved_pattern($id);
   
      $pattern = $this->data['pattern']['pattern'];
   
      $patternquery = json_decode($pattern,false);
      $conarray = array();
      $ind = 0;
      foreach($patternquery as $conditon)
      {
    	 foreach($conditon as $fld)
    	 {
    		array_push($conarray,$fld);
         }

    	 if(isset($conarray[2])=="TRUE")
    	 {
    		$operator[$ind]=$conarray[2];
    	 }
			
    	 $result[$ind] = $this->ion_auth->query(strtolower($conarray[0]),$conarray[1],$collection);
    	 $ind ++;
    	 $conarray = array();
    		
       }
       
       $reid = 0;
       foreach ($operator as $logi)
       {
    	  if($logi == "AND")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{
    				$logic[$reid] = array_intersect($res,$res2);
    				
    			}
    		}
    	  }
    	  elseif($logi == "OR")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{
    				$logic[$reid] = array_merge($res,$res2);  
    			}
    		}
    	  }
    	  $reid++;
    	}
        $this->session->set_userdata("savedpattern",json_encode(array_unique($logic)));	
		$this->after_get_saved_pattern();
       		
    } 
  
    // --------------------------------------------------------------------

	/**
	* Helper : Render the retrieved saved analytics pattern
	*
	* @author  Selva 
	*
	* 
	*/

    function after_get_saved_pattern()
    {
  	   $result = $this->session->userdata("savedpattern");
       $order = array("[","{","}","]");
       $replace ='';
       $this->data['result12'] = str_replace($order,$replace,$result);
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
       $this->_render_page('admin/admin_dash_pattern_result',$this->data);
    }
	
	// --------------------------------------------------------------------
	
	/**
     * Helper : Search for analytics
     *
     * @author  Veera & Selva
     *
     *
     */
	function analytics()
	{
		// *******sample value for AND/OR ******//
		
		/* $pipeline = [array(
		'$match' => array('$or' => array(array("doc_data.widget_data.page3.Physical exam.Blood group" =>"O+ve"),array("doc_data.widget_data.page3.Physical exam.Pulse" =>"90")),'$and' => array(array("doc_data.widget_data.page3.Physical exam.Blood group" =>"O+ve"),array("doc_data.widget_data.page3.Physical exam.Pulse" =>"90")))
		)]; */
		
		$pipeline = [array(
		'$match' => array()
		)];
		
		$querystring = $_POST['strng'];
    	$appid       = $_POST['dataid'];
		$graph_type  = $_POST['graph_type'];
		$conditions  = json_decode($querystring,true);
    	$search_fields_or  = array();
    	$search_fields_and = array();
		$search_fields_ind = array();
    	$search_fields_sector = array();
		$sector_pipeline = array();
		$bar_xaxis = array();
		$bar_yaxis = array();
		$bar_value = array();
		$bar_chart = array();
		$bar_graph_result = array();
    	$inc_or  = 0;
    	$inc_and = 0;
		$inc_ind = 0;
		$response_data = array();
    	
		// decoding label name and building array //
		log_message("debug","conditons".print_r($conditions,true));
		log_message("debug","conditons".print_r($graph_type,true));
    			
		$cond_count = count($conditions);
	    if($graph_type == "pie")
		{
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$field = base64_decode($conditions[$ci]['labelname']);
			$logical = $conditions[$ci]['option'];
			if($ci == $cond_count - 1 && $ci > 0)
			{
				if($logical=="OR" || $logical=="AND")
				{
						$logical = $conditions[$ci-1]['option'];
				}
			}
			if($logical == "sector")
			{
					if(($conditions[$ci]['value'] != '') && (!is_array($conditions[$ci]['value'])))
					{
						$search_fields_sector = array("doc_data.widget_data.page"."$field" => $conditions[$ci]['value']); 
					}
					else if(($conditions[$ci]['value'] == '') && ($conditions[$ci]['greaterthan'] != '') || ($conditions[$ci]['lessthan'] != ''))
					{
						if(($conditions[$ci]['greaterthan'] != '') && ($conditions[$ci]['lessthan'] != ''))
						{
							$search_fields_sector = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan'], '$lt' => $conditions[$ci]['lessthan']));
						}
						else if($conditions[$ci]['greaterthan'] != '')
						{
							$search_fields_sector = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan']));
						}
						else if($conditions[$ci]['lessthan'] != '')
						{
							$search_fields_sector = array("doc_data.widget_data.page"."$field" => array('$lt' => $conditions[$ci]['lessthan']));
						}
					}
			}
			if($logical == "OR")
			{
				if(($conditions[$ci]['value'] != '') && (!is_array($conditions[$ci]['value'])))
				{
					$search_fields_or[$inc_or] = array("doc_data.widget_data.page"."$field" => $conditions[$ci]['value']);
					$inc_or++; 
				}
				else if(is_array($conditions[$ci]['value']))
				{
					$search_fields_or[$inc_or] = array("doc_data.widget_data.page"."$field" => array('$in'=>$conditions[$ci]['value']));
					$inc_or++;
				}
				
				else if(($conditions[$ci]['value'] == '') && ($conditions[$ci]['greaterthan'] != '') || ($conditions[$ci]['lessthan'] != ''))
				{
					if(($conditions[$ci]['greaterthan'] != '') && ($conditions[$ci]['lessthan'] != ''))
					{
						$search_fields_or[$inc_or] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan'], '$lt' => $conditions[$ci]['lessthan']));
					}
					else if($conditions[$ci]['greaterthan'] != '')
					{
						$search_fields_or[$inc_or] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan']));
					}
					else if($conditions[$ci]['lessthan'] != '')
					{
						$search_fields_or[$inc_or] = array("doc_data.widget_data.page"."$field" => array('$lt' => $conditions[$ci]['lessthan']));
					}
					
					$inc_or++; 
				}
			}
    		if($logical == "AND")
			{
				if(($conditions[$ci]['value'] != '') && (!is_array($conditions[$ci]['value'])))
				{
				    $response_data['query'][$inc_and]['field'] = $field;
					$search_fields_and[$inc_and] = array("doc_data.widget_data.page"."$field" => $conditions[$ci]['value']);
					$inc_and++;
				}
				else if(is_array($conditions[$ci]['value']))
				{
				    $response_data['query'][$inc_and]['field'] = $field;
					$search_fields_and[$inc_and] = array("doc_data.widget_data.page"."$field" => array('$in'=>$conditions[$ci]['value']));
					$inc_and++;
				}
				else if(($conditions[$ci]['value'] == '') && ($conditions[$ci]['greaterthan'] != '') || ($conditions[$ci]['lessthan'] != ''))
				{
					if(($conditions[$ci]['greaterthan'] != '') && ($conditions[$ci]['lessthan'] != ''))
					{
					    $response_data['query'][$inc_and]['field'] = $field;
						$search_fields_and[$inc_and] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan'], '$lt' => $conditions[$ci]['lessthan']));
					}
					else if($conditions[$ci]['greaterthan'] != '')
					{  
					    $response_data['query'][$inc_and]['field'] = $field;
						$search_fields_and[$inc_and] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan']));
					}
					else if($conditions[$ci]['lessthan'] != '')
					{
					    $response_data['query'][$inc_and]['field'] = $field;
						$search_fields_and[$inc_and] = array("doc_data.widget_data.page"."$field" => array('$lt' => $conditions[$ci]['lessthan']));
					}
					$inc_and++;
				}	
								
    		}
			if($logical == "individual")
			{
					 if(($conditions[$ci]['value'] != '') && (!is_array($conditions[$ci]['value'])))
					{
						$response_data['query'][$inc_ind]['field'] = $field;
						$search_fields_ind[$inc_ind] = array("doc_data.widget_data.page"."$field" => $conditions[$ci]['value']);
						$inc_ind++; 
					}
					else if(is_array($conditions[$ci]['value']))
					{
						$response_data['query'][$inc_ind]['field'] = $field;
						$search_fields_ind[$inc_ind] = array("doc_data.widget_data.page"."$field" => array('$in'=>$conditions[$ci]['value']));
						$inc_ind++;
					}
					
					else if(($conditions[$ci]['value'] == '') && ($conditions[$ci]['greaterthan'] != '') || ($conditions[$ci]['lessthan'] != ''))
					{
						if(($conditions[$ci]['greaterthan'] != '') && ($conditions[$ci]['lessthan'] != ''))
						{
							$response_data['query'][$inc_ind]['field'] = $field;
							$search_fields_ind[$inc_ind] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan'], '$lt' => $conditions[$ci]['lessthan']));
						}
						else if($conditions[$ci]['greaterthan'] != '')
						{
							$response_data['query'][$inc_ind]['field'] = $field;
							$search_fields_ind[$inc_ind] = array("doc_data.widget_data.page"."$field" => array('$gt' => $conditions[$ci]['greaterthan']));
						}
						else if($conditions[$ci]['lessthan'] != '')
						{
							$response_data['query'][$inc_ind]['field'] = $field;
							$search_fields_ind[$inc_ind] = array("doc_data.widget_data.page"."$field" => array('$lt' => $conditions[$ci]['lessthan']));
						}
						
						$inc_ind++; 
					} 
			}
    	}
		
		if(!empty($search_fields_sector))
		{
			$pipeline = [array(
				'$project' => array("doc_data.widget_data"=>true)),
				array('$match' => $search_fields_sector)
				];
				
			log_message("debug","pipeline=====pie===".print_r($pipeline,true));
				
			// Model request
			$db_response = $this->ion_auth->analyse($pipeline,$appid);
			$response_data['sector'] = $db_response;
		}
		// Check if AND / OR not empty //
		if(!empty($search_fields_or) && !empty($search_fields_and))
		{
			$merged_array = array();
			$merged_array = array_merge($merged_array,$search_fields_and);
			array_push($merged_array,$search_fields_sector);
			
			$pipeline = [array(
			'$project' => array("doc_data.widget_data"=>true)),
			array('$match'=> array('$or' => $search_fields_or,'$and' => $merged_array)
			)];
			
			// Model request
			$db_response = $this->ion_auth->analyse($pipeline,$appid);
			$response_data['query'] = $db_response;
		}
		else if(!empty($search_fields_or))
		{
			$pipeline = [array(
					'$project' => array("doc_data.widget_data"=>true)),
					array('$match' => array('$or' => $search_fields_or,'$and' => $search_fields_sector)
					)];
					
			// Model request
			$db_response = $this->ion_auth->analyse($pipeline,$appid);
			$response_data['or_query'] = $db_response;
		}
		else if(!empty($search_fields_and) && empty($search_fields_ind))
			{
				$merged_array = array();
				$merged_array = array_merge($merged_array,$search_fields_and);
				array_push($merged_array,$search_fields_sector);
					
				$pipeline = [array(
				'$project' => array("doc_data.widget_data"=>true)),
				array('$match' => array('$and' => $merged_array)
				)];
				
				log_message("debug","merged_array====AND====pie===".print_r($merged_array,true));
				log_message("debug","pipeline====AND====pie===".print_r($pipeline,true));
				
				// Model request
				$db_response = $this->ion_auth->analyse($pipeline,$appid);
				$response_data['query'] = $db_response;
			}
			else if(!empty($search_fields_ind))
			{
				$ind_count = count($search_fields_ind);
				
				for($ind_i = 0; $ind_i < $ind_count; $ind_i++)
				{
					$merged_array = array();
					$merged_array = array_merge($merged_array,$search_fields_and);
					array_push($merged_array,$search_fields_ind[$ind_i]);
					array_push($merged_array,$search_fields_sector);
					
					$pipeline = [array(
					'$project' => array("doc_data.widget_data"=>true)),
					array('$match' => array('$and' => $merged_array)
					)];
					
					log_message("debug","pipeline===IND==pie===".print_r($pipeline,true));
					
					// Model request
					$db_response = $this->ion_auth->analyse($pipeline,$appid);
					$response_data['query'][$ind_i]['value'] = $db_response;
				}
				
				
			}
			
			log_message("debug","response_data=====pie===".print_r($response_data,true));
			
			$data['docs'] = $response_data;
		
	}
	else if($graph_type == "bar")
	{
		$value_inc = 0;
		
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$field = base64_decode($conditions[$ci]['labelname']);
			$logical = $conditions[$ci]['option'];
			
			if($logical == "xaxis")
			{
				$bar_xax = "doc_data.widget_data.page"."$field"; 
				$bar_chart['xaxis']['label'] = $bar_xax;
			}
			else if($logical == "yaxis")
			{
				$bar_yax = "doc_data.widget_data.page"."$field"; 
				$bar_chart['yaxis']['label'] = $bar_yax;
			}
			else if($logical == "value")
			{
				/* if(is_array($conditions[$ci]['value']))
				//if(!isset($conditions[$ci]['comparison_opt']))
				{
					$bar_val = "doc_data.widget_data.page"."$field"; 
					$bar_chart['value'][$value_inc]['label'] = $bar_val;
					$bar_chart['value'][$value_inc]['value'] = $conditions[$ci]['value'];
					$value_inc++;
				}
				else
				{ */
					$bar_val = "doc_data.widget_data.page"."$field"; 
					$bar_chart['value'][$value_inc]['label']    = $bar_val;
					$bar_chart['value'][$value_inc]['field_name']    = $conditions[$ci]['field_name'];
					$bar_chart['value'][$value_inc]['value']    = $conditions[$ci]['value'];
					$bar_chart['value'][$value_inc]['operator'] = $conditions[$ci]['comparison_opt'];
					$value_inc++;
					
				//}
			}
			
			
			/* if($logical == "xaxis")
			{
			   $bar_xax = "doc_data.widget_data.page"."$field"; 
			   $bar_x_axis_res = $this->ion_auth->bar_chart_x_axis($bar_xax,$appid);
			   log_message("debug","bar_x_axis_res====xaxis".print_r($bar_x_axis_res,true));
			   $bar_x_axis_res_count = count($bar_x_axis_res);
			   for($xi=0; $xi < $bar_x_axis_res_count; $xi++)
			   {
				   $e_value = $bar_x_axis_res[$xi];
				   if(!empty($e_value))
				   {
					   $query = array($bar_xax => $e_value,);
					   $pipeline = [array(
			'$match' => array('$and' => $search_fields_and)
			)];
					   $val = $this->ion_auth->bar_chart_x($query,$appid);
					   log_message("debug","val====xaxis".print_r($val,true));
				   }
			   }
			   
			}
			else if($logical == "yaxis")
			{
			   $bar_yax = array("doc_data.widget_data.page"."$field");
			   $bar_y_axis_res = $this->ion_auth->bar_chart_y_axis($bar_xax,$appid);
			   log_message("debug","bar_y_axis_res====yaxis".print_r($bar_y_axis_res,true));
			} */
		}

		log_message('debug','bar_chart====222222222222222222222222222'.print_r($bar_chart,true));
        	
		$bar_x_axis_res = $this->ion_auth->bar_chart_x_axis($bar_chart['xaxis']['label'],$appid);
			   log_message("debug","bar_x_axis_res====xaxis".print_r($bar_x_axis_res,true));
			   $bar_x_axis_res_count = count($bar_x_axis_res);
			   for($xi=0; $xi < $bar_x_axis_res_count; $xi++)
			   {
				   $e_value = $bar_x_axis_res[$xi];
				   if(!empty($e_value))
				   {
					   //$query = array($bar_chart['xaxis']['label'] => $e_value,$bar_chart['value']['label'] => array('$in'=>$bar_chart['value']['value']));
					   $query = array();
					   $and_cond = array();
					   $and_cond[0] = array($bar_chart['xaxis']['label'] => $e_value);
					   $values_count = count($bar_chart['value']);
					   for($vi=0;$vi<$values_count;$vi++)
					   {
						  // To check all conditions at once
						 //$query[1] = array($bar_chart['value']['label'] => array('$in'=>$bar_chart['value']['value'][$vi]));
						 
						 if(is_array($bar_chart['value'][$vi]['value']))
						 {
							$inner_values_count = count($bar_chart['value'][$vi]['value']);
							$op_operator = $bar_chart['value'][$vi]['operator'];
							 for($innerval=0;$innerval<$inner_values_count;$innerval++)
					        {
								$query[$innerval] = array($bar_chart['value'][$vi]['label'] => $bar_chart['value'][$vi]['value'][$innerval]);
								log_message('debug','query====222222222222222222222222222===='.print_r($query,true));
				            }
							
				if($op_operator == "AND")
				{
					 array_push($query,$and_cond[0]);
					  $pipeline = [array(
					  '$project' => array("doc_data.widget_data"=>true)),
					array('$match' => array('$and'=> $query)
					)];
				}
				else if($op_operator == "OR")
				{
					$pipeline = [array(
					'$project' => array("doc_data.widget_data"=>true)),
				      array('$match' => array('$and' => $and_cond,'$or'=> $query)
				   )];
				}
							log_message('debug','pipeline====222222222222222222222222222===='.print_r($pipeline,true)); 
							
							 $val = $this->ion_auth->bar_chart_x($pipeline,$appid);
							 $bar_graph_result[$xi]['values'][$vi] = $val;
						     $bar_graph_result[$xi]['labels'][$vi] = $bar_chart['value'][$vi]['field_name'];
								
						}
						 else
						 {
							$condi_array = array();
							$cmp_operator = $bar_chart['value'][$vi]['operator'];
							switch($cmp_operator)
							{
								case 'greaterthan':
								$condi_array[0] = array($bar_chart['value'][$vi]['label'] => array('$gt'=>$bar_chart['value'][$vi]['value']));
								break;
								
								case 'lessthan':
								$condi_array[0] = array($bar_chart['value'][$vi]['label'] => array('$lt'=>$bar_chart['value'][$vi]['value']));
								break;
								
								case 'equalto':
								$condi_array[0] = array($bar_chart['value'][$vi]['label'] => $bar_chart['value'][$vi]['value']);
								break;
								
								case 'like':
								$condi_array[0] = array($bar_chart['value'][$vi]['label'] => array('$regex'=>$bar_chart['value'][$vi]['value'],'$options'=>'i'));
								break;
								
								case 'default':
								break;
							}
							
								log_message('debug','query====222222222222222222222222222===='.print_r($condi_array,true));
						    array_push($condi_array,$and_cond[0]);
						    $pipeline = [array(
							     '$project' => array("doc_data.widget_data"=>true)),
								array('$match' => array('$and' => $condi_array)
				            )];
							log_message('debug','pipeline====222222222222222222222222222===='.print_r($pipeline,true)); 
							
							 $val = $this->ion_auth->bar_chart_x($pipeline,$appid);
							 $bar_graph_result[$xi]['values'][$vi] = $val;
						     $bar_graph_result[$xi]['labels'][$vi] = $bar_chart['value'][$vi]['field_name']; 
						 }
						 
						  
							
						  
						   $bar_graph_result[$xi]['xaxis'] = $e_value;
						   $bar_graph_result[$xi]['yaxis'] = '';
						   
			               //$bar_graph_result[$xi]['values'][$bar_chart['value']['value'][$vi]]['number of students'] = $val;
						   log_message("debug","val====xaxis".print_r($val,true));
				   }
				   }
			   }
		

		       log_message("debug","bar_graph_result====xaxis".print_r($bar_graph_result,true));
		
		$data['docs'] = $bar_graph_result;
    	
    	$data['count'] = $this->ion_auth->query_app_count($appid);
	
		
	}
	else if($graph_type == "bmi")
	{
		$underweight = 0;
	    $normal 	 = 0;
	    $overweight  = 0;
	    $obese 		 = 0;
	    $not_calc    = 0;
			   
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$school_value      = $conditions[$ci]['school_value'];
    		$school_field_name = base64_decode($conditions[$ci]['school_field_name']);
    		$height_field_name = base64_decode($conditions[$ci]['height_field_name']);
    		$weight_field_name = base64_decode($conditions[$ci]['weight_field_name']);
			
			$height_field_array = explode('.',$height_field_name);
			$weight_field_array = explode('.',$weight_field_name);
			
			$school_field_name = "doc_data.widget_data.page".$school_field_name;
			$height_field_name = "doc_data.widget_data.page".$height_field_name;
			$weight_field_name = "doc_data.widget_data.page".$weight_field_name;
			
			$bmi_values = $this->ion_auth->fetch_values_for_bmi_chart($appid,$school_value,$school_field_name,$height_field_name,$weight_field_name);
			
			foreach($bmi_values as $index => $values)
			{
			   $widget_data = $values['doc_data']['widget_data'];
			  
			   if((isset($widget_data["page".$height_field_array[0]])) && (isset($widget_data["page".$height_field_array[0]][$height_field_array[1]])) && (isset($widget_data["page".$weight_field_array[0]])) && (isset($widget_data["page".$weight_field_array[0]][$weight_field_array[1]])))
			   {
				   $height = $widget_data["page".$height_field_array[0]][$height_field_array[1]][$height_field_array[2]];
				   
				   $weight = $widget_data["page".$weight_field_array[0]][$weight_field_array[1]][$weight_field_array[2]];
				   
				   $height = (int) $height;
				   $weight = (int) $weight;
					
				   if(($height > 0) && ($weight > 0))
			       {
					   $height = ($height/100);
					   $bmi    = ($weight / ($height * $height));
					   $bmi    = round($bmi,1);
				       
					   if (isset($bmi)) 
			           {				   
						 if ($bmi <= 18.5) 
						  { 
							 $underweight++;
						  } 
						  else if ($bmi >= 18.5 && $bmi <= 24.9) 
						  { 
							$normal++;
						  } 
						  else if ($bmi >= 25 && $bmi <= 29.9)
						  { 
							 $overweight++; 
						  }
						  else
						  {
							  $obese++;
						  }
			           }
					   else
					   {
							$not_calc++;
					   }
			  
				   }
				   else
				   {
					   $not_calc++;
				   }
			   }
			   else
			   {
				   $not_calc++;
			   }
			  
		    }
			
		}
		
        $data['docs']['underweight'] = $underweight;
		$data['docs']['normal']      = $normal;
		$data['docs']['overweight']  = $overweight;
		$data['docs']['obese']       = $obese;
		$data['docs']['notcalc']     = $not_calc;
		$data['docs']['schoolname']  = $school_value;
    	$data['count']               = count($bmi_values); 		
	}
	else if($graph_type == "age_height")
	{
		$graph_data  = array();
		for($val_sample=5;$val_sample<19;$val_sample++)
		{
			$graph_data[$val_sample]['normalheight'] = 0;
			$graph_data[$val_sample]['underheight'] = 0;
			$graph_data[$val_sample]['overheight'] = 0;
		}
	    $not_calc    = 0;
			   
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$school_value      = $conditions[$ci]['school_value'];
    		$school_field_name = base64_decode($conditions[$ci]['school_field_name']);
    		$height_field_name = base64_decode($conditions[$ci]['height_field_name']);
    		$dob_field_name    = base64_decode($conditions[$ci]['dob']);
			$m_date_field_name = base64_decode($conditions[$ci]['doe']);
			
			$height_field_array = explode('.',$height_field_name);
			$m_date_field_array = explode('.',$m_date_field_name);
			$dob_field_array    = explode('.',$dob_field_name);
			
			$school_field_name = "doc_data.widget_data.page".$school_field_name;
			$height_field_name = "doc_data.widget_data.page".$height_field_name;
			$dob_field_name    = "doc_data.widget_data.page".$dob_field_name;
			$m_date_field_name = "doc_data.widget_data.page".$m_date_field_name;
			
			$height_chart_values = $this->ion_auth->fetch_values_for_height_chart($appid,$school_value,$school_field_name,$height_field_name,$dob_field_name,$m_date_field_name);
			
			foreach($height_chart_values as $index => $values)
			{
			   $widget_data = $values['doc_data']['widget_data'];
			  
			   if((isset($widget_data["page".$height_field_array[0]])) && (isset($widget_data["page".$height_field_array[0]][$height_field_array[1]])) && (isset($widget_data["page".$dob_field_array[0]])) && (isset($widget_data["page".$dob_field_array[0]][$dob_field_array[1]])))
			   {
				   $height = $widget_data["page".$height_field_array[0]][$height_field_array[1]][$height_field_array[2]];
				   
				   $dob_ = $widget_data["page".$dob_field_array[0]][$dob_field_array[1]][$dob_field_array[2]];
				   
				   if(!empty($dob_))
				   {
				     $measured_date = $widget_data["page".$m_date_field_array[0]][$m_date_field_array[1]][$m_date_field_array[2]];
					 
				   if(empty($measured_date))
				   {
				     $measured_date = date('Y-m-d');
				   }
				   $height = (int) $height;
				   $timestamp_start = strtotime($dob_);
				   $timestamp_end   = strtotime($measured_date);
				   $difference = abs($timestamp_end - $timestamp_start); // that's it!
				   $age = floor($difference/(60*60*24*365));
				   
				   if(($height > 0) && ($age > 0))
			       {
					  if(!isset($graph_data[$age]['normalheight']))
						{
							$graph_data[$age]['normalheight'] = 0;
						}
						if(!isset($graph_data[$age]['underheight']))
						{
							$graph_data[$age]['underheight'] = 0;
						}
						if(!isset($graph_data[$age]['overheight']))
						{
							$graph_data[$age]['overheight'] = 0;
						}
						
					  switch($age)
					   {
						   case '5':
						   if($height == 108.4)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 108.4)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 108.4)
						   {
							    $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '6':
						   if($height == 114.6)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 114.6)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 114.6)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '7':
						   if($height == 120.6)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 120.6)
						   {
							  $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 120.6)
						   {
							    $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '8':
						   if($height == 126.4)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 126.4)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 126.4)
						   {
							    $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '9':
						   if($height == 132.2)
						   {
							  $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 132.2)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 132.2)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '10':
						   if($height == 138.3)
						   {
							  $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 138.3)
						   {
							  $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 138.3)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '11':
						   if($height == 142.0)
						   {
							  $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 142.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 142.0)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '12':
						   if($height == 148.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 148.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 148.0)
						   {
							    $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '13':
						   if($height == 150.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 150.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 150.0)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '14':
						   if($height == 155.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 155.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 155.0)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '15':
						   if($height == 161.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 161.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 161.0)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '16':
						   if($height == 162.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 162.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 162.0)
						   {
							     $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '17':
						   if($height == 163.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 163.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 163.0)
						   {
							    $graph_data[$age]['overheight'] += 1;
						   }
						   break;
						   
						   case '18':
						   if($height == 164.0)
						   {
							   $graph_data[$age]['normalheight'] += 1;
						   }
						   else if($height < 164.0)
						   {
							   $graph_data[$age]['underheight'] += 1;
						   }
						   else if($height > 164.0)
						   {
							   $graph_data[$age]['overheight'] += 1;
						   }
						   break; 
					   }
				   }
				   else
				   {
					   $not_calc++;
				   }
			   }
			   }
			   else
			   {
				   $not_calc++;
			   }
			  
		    }
			
		}
		
		$data['docs']['graphdata']   = $graph_data;
		$data['docs']['notcalc']      = $not_calc;
		$data['docs']['schoolname']   = $school_value;
    	$data['count']                = count($height_chart_values);
	}
	else if($graph_type == "age_weight")
	{
	    $not_calc    = 0;
		$graph_data  = array();
		for($val_sample=5;$val_sample<19;$val_sample++)
		{
			$graph_data[$val_sample]['normalweight'] = 0;
			$graph_data[$val_sample]['underweight'] = 0;
			$graph_data[$val_sample]['overweight'] = 0;
		}
		
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$school_value      = $conditions[$ci]['school_value'];
    		$school_field_name = base64_decode($conditions[$ci]['school_field_name']);
    		$weight_field_name = base64_decode($conditions[$ci]['weight_field_name']);
    		$dob_field_name    = base64_decode($conditions[$ci]['dob']);
			$m_date_field_name = base64_decode($conditions[$ci]['doe']);
			
			log_message('debug','$m_date_field_name=====1497'.print_r($m_date_field_name,true));
			log_message('debug','$dob_field_name=====1498'.print_r($dob_field_name,true));
			
			$dob_field_array     = explode('.',$dob_field_name);
			$weight_field_array  = explode('.',$weight_field_name);
			$m_date_field_array  = explode('.',$m_date_field_name);
			
			
			$school_field_name = "doc_data.widget_data.page".$school_field_name;
			$dob_field_name    = "doc_data.widget_data.page".$dob_field_name;
			$weight_field_name = "doc_data.widget_data.page".$weight_field_name;
			$m_date_field_name = "doc_data.widget_data.page".$m_date_field_name;
			
			$weight_chart_values = $this->ion_auth->fetch_values_for_weight_chart($appid,$school_value,$school_field_name,$dob_field_name,$m_date_field_name,$weight_field_name);
			
			foreach($weight_chart_values as $index => $values)
			{
			   $widget_data = $values['doc_data']['widget_data'];
			  
			  log_message('debug','$widget_data=====1527'.print_r($widget_data,true));
			  
			   if((isset($widget_data["page".$dob_field_array[0]])) && (isset($widget_data["page".$dob_field_array[0]][$dob_field_array[1]])) && (isset($widget_data["page".$weight_field_array[0]])) && (isset($widget_data["page".$weight_field_array[0]][$weight_field_array[1]])))
//				   && (isset($widget_data["page".$m_date_field_array[0]])) && (isset($widget_data["page".$m_date_field_array[0]][$m_date_field_array[1]])))
			   {
				   $dob_ = $widget_data["page".$dob_field_array[0]][$dob_field_array[1]][$dob_field_array[2]];
				   
				   if(!empty($dob_))
				   {
				   $weight = $widget_data["page".$weight_field_array[0]][$weight_field_array[1]][$weight_field_array[2]];
				  
				  $measured_date = $widget_data["page".$m_date_field_array[0]][$m_date_field_array[1]][$m_date_field_array[2]];
				   
				   if(empty($measured_date))
				   {
				     $measured_date = date('Y-m-d');
				   }
				   
				   $timestamp_start = strtotime($dob_);
				   $timestamp_end   = strtotime($measured_date);
				   $difference = abs($timestamp_end - $timestamp_start); // that's it!
				   $age = floor($difference/(60*60*24*365));
				   $weight = (int) $weight;
				   
				   
				   log_message('debug','$age=====1527'.print_r($age,true));
					
				   if(($weight > 0) && ($age > 0))
			       {
						if(!isset($graph_data[$age]['normalweight']))
						{
							$graph_data[$age]['normalweight'] = 0;
						}
						if(!isset($graph_data[$age]['underweight']))
						{
							$graph_data[$age]['underweight'] = 0;
						}
						if(!isset($graph_data[$age]['overweight']))
						{
							$graph_data[$age]['overweight'] = 0;
						}
					   switch($age)
					   {
						   case '5':
						   if($weight == 17.7)
						   {
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 17.7)
						   {
							  
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 17.7)
						   { 
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '6':
						   if($weight == 19.5)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 19.5)
						   {
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 19.5)
						   {
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '7':
						   if($weight == 21.8)
						   {
							  
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 21.8)
						   {
							  
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 21.8)
						   {
							   
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '8':
						   if($weight == 24.8)
						   {
							  
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 24.8)
						   {
							  
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 24.8)
						   {
							   
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '9':
						   if($weight == 28.5)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 28.5)
						   {
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 28.5)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '10':
						   if($weight == 32.5)
						   {
							  
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 32.5)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 32.5)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '11':
						   if($weight == 33.7)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 33.7)
						   {
							  
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 33.7)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '12':
						   if($weight == 38.7)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 38.7)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 38.7)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '13':
						   if($weight == 44.0)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 44.0)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 44.0)
						   {
							   
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '14':
						   if($weight == 48.0)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 48.0)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 48.0)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '15':
						   if($weight == 51.5)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 51.5)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 51.5)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '16':
						   if($weight == 53.0)
						   {
							   
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 53.0)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 53.0)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '17':
						   if($weight == 54.0)
						   {
							  
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 54.0)
						   {
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 54.0)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						   case '18':
						   if($weight == 54.4)
						   {
							  
							   $graph_data[$age]['normalweight'] += 1;
						   }
						   else if($weight < 54.4)
						   {
							   
							   $graph_data[$age]['underweight'] += 1;
						   }
						   else if($weight > 54.4)
						   {
							    
								$graph_data[$age]['overweight'] += 1;
						   }
						   break;
						   
						}
				   }
				   else
				   {
					   $not_calc++;
				   }
				   }
			   }
			   else
			   {
				   $not_calc++;
			 
			 }
			  
		    }
			
		}
		
		$data['docs']['graphdata']  = $graph_data;
		$data['docs']['notcalc']     = $not_calc;
		$data['docs']['schoolname']  = $school_value;
    	$data['count']               = count($weight_chart_values);
	}
	else if($graph_type == "detailed_graph")
	{
		// Variable declaration
		$others           	= 0;
		$anaemia          	= 0;
		$asthma           	= 0;
		$vit_def          	= 0;
		$thyroid          	= 0;
		$white_patches    	= 0;
		$urti   	      	= 0;
		$balanced_diet      = 0;
		$iron_diet          = 0;
		$calcium_diet	    = 0;
		$physical_activity  = 0;
		$breathing_ex 	    = 0;
		$new_ref_err 	    = 0;
		$glass_normal 		= 0;
		$glass_abnormal 	= 0;
		$bl_wax 		    = 0;
		$ul_wax 		    = 0;
		$stam         		= 0;
		$misarti     		= 0;
		$flu    			= 0;
		$ton_tie      		= 0;
		$inf 	  			= 0;
		$hea_loss    	    = 0;
		$ohi       			= 0;
		$carious_teeth      = 0;
		$flourosis          = 0;
		$orthodo      		= 0;
		$extraction   		= 0;
		$scaling_advice   	= 0;
		$scabis      		= 0;
		$stom_ache          = 0;
		$head_ache          = 0;
		
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$school_value      = $conditions[$ci]['school_value'];
    		$school_field_name = base64_decode($conditions[$ci]['school_field_name']);
			$school_field_name = "doc_data.widget_data.page".$school_field_name;
			
			$summary_values = $this->ion_auth->fetch_values_for_summary_chart($appid,$school_value,$school_field_name);
			
			foreach($summary_values as $index => $values)
			{
			   $widget_data = $values['doc_data']['widget_data'];
			   $eye_section_finished = "FALSE";
			   foreach($widget_data as $pageno => $section_details)
			   {
				   foreach($section_details as $section_name => $element_details)
				   {
					   $section_finished = "FALSE";
					   foreach($element_details as $element_name => $element_value)
					   {
						  // log_message("debug","element_name====xaxis".print_r($element_name,true));
						  // log_message("debug","element_value====xaxis".print_r($element_value,true));
						   switch($element_name)
						   {
							   case 'Check the box if normal else describe abnormalities':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neurologic':
									case 'H and N':
									case 'ENT':
									case 'Hips':
									case 'Lymphatic':
									case 'Heart':
									case 'Lungs':
									case 'Abdomen':
									case 'Genitalia':
									case 'Skin':
									if($section_finished == "FALSE")
								    {
									  $others++;
									  $section_finished  = "TRUE";
								    } 
									break;
								  }								  
							   }
							   break;
							   
							   case 'Ortho':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neck':
									case 'Shoulder':
									case 'Arms/Hands':
									case 'Hips':
									case 'Knees':
									case 'Feet':
									if($section_finished == "FALSE")
								    {
									  $others++;
									  $section_finished  = "TRUE";
								    } 
									break;
								  }								  
							   }
							   break;
							   
							   case 'Postural':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								 switch($element_value[$i])
								 {
                             
								    case 'Spinal Abnormality':
									case 'Mild':
									case 'Marked':
									case 'Moderate':
									if($section_finished == "FALSE")
								    {
									  $others++;
									  $section_finished  = "TRUE";
								    } 
									break;
								 }								  
							   }
							   break;

							   case 'Defects at Birth':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neural Tube Defect':
									case 'Down Syndrome':
									case 'Cleft Lip and Palate':
									case 'Talipes Club foot':
									case 'Developmental Dysplasia of Hip':
									case 'Congenital Cataract':
									case 'Congenital Deafness':
									case 'Congenital Heart Disease':
									case 'Retinopathy of Prematurity':
									if($section_finished == "FALSE")
								    {
									  $others++;
									  $section_finished  = "TRUE";
								    } 
									break; 
								  }								  
							   }
							   break;
							   
							   case 'Deficencies':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Anaemia':
									$anaemia++;
									break;
									
									case 'Vitamin Deficiency - Bcomplex':
									case 'Vitamin A Deficiency':
									case 'Vitamin D Deficiency':
									$vit_def++;
									$i = $cnt;
									break;
									
								  }								  
							   }
							   break;
							   
							   case 'Childhood Diseases':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Asthma':
									$asthma++;
									break;
									
									case 'Epilepsy':
									case 'Diabetes':
									if($section_finished == "FALSE")
								    {
									  $others++;
									  $section_finished  = "TRUE";
								    } 
									break;
									
								}								  
							   }
							   break;
							   
							   case 'Description':
							   $element_value = strtolower($element_value);
							   if (strpos($element_value, 'urti') !== false) {
									$urti++;
							   }
							   else if (strpos($element_value, 'thyroid') !== false) {
									$thyroid++;
							   }
							   else if ((strpos($element_value, 'white') !== false) || (strpos($element_value, 'patc') !== false)) {
									$white_patches++;
							   }
							   break;
							   
							   case 'Advice':
							    $element_value = strtolower($element_value);
							   if ((strpos($element_value, 'physical') !== false) || (strpos($element_value, 'phy') !== false)) {
									$physical_activity++;
								}
								else if ((strpos($element_value, 'breathing') !== false) || (strpos($element_value, 'breath') !== false)) {
									$breathing_ex++;
								}
								else if ((strpos($element_value, 'calcium') !== false) || (strpos(  $element_value, 'calc') !== false)) {
									$calcium_diet++;
								}
								else if ((strpos($element_value, 'iron') !== false)) {
									$iron_diet++;
								}
								else if ((strpos($element_value, 'balanced') !== false) || (strpos(  $element_value, 'balan') !== false)) {
									$balanced_diet++;
								}
							    else if ((strpos($element_value, 'stammering') !== false) || (strpos(  $element_value, 'stam') !== false)) {
									$stam++;
								}
								else if ((strpos($element_value, 'scab') !== false)) {
									$scabis++;
								}
								else if ((strpos($element_value, 'headache') !== false) || (strpos(  $element_value, 'head') !== false)) {
									$head_ache++;
								}
								else if ((strpos($element_value, 'stomachache') !== false) || (strpos(  $element_value, 'stomac') !== false)) {
									$stom_ache++;
								}
							   break;
							   
							   // Eye
							   case 'Left':
							   case 'Right':
							  if($eye_section_finished == "FALSE" && $section_name == "Without Glasses" && $element_value != "6/6" && $element_value != "")
							   {
                                   $new_ref_err++;
								   $eye_section_finished = "TRUE";
							   }
							   else if(($eye_section_finished == "FALSE") && ($section_name == "With Glasses") && ($element_value == "6/6"))
							   {
                                   $glass_normal++;
								   $eye_section_finished = "TRUE";
							   }
							   else if($eye_section_finished == "FALSE" && $section_name == "With Glasses" && $element_value != "6/6" && $element_value != "")
							   {
								  $glass_abnormal++;
								  $eye_section_finished = "TRUE";
							   }
							   break;
							   
							   case 'Description':
							   if($section_name == "Auditory Screening")
							   {
								    $element_value = strtolower($element_value);
								    if ((strpos($element_value, 'bi') !== false) || (strpos(  $element_value, 'bl') !== false) || (strpos($element_value, 'b/l') !== false) && (strpos($element_value, 'wax') !== false)) {
									   $bl_wax++;
								    }
									else if ((strpos($element_value, 'ul') !== false) || (strpos(  $element_value, 'unilateral') !== false) || (strpos(  $element_value, 'u/l') !== false) && (strpos($element_value, 'wax') !== false)) {
									   $ul_wax++;
								    }
									else if ((strpos($element_value, 'ton') !== false) || (strpos(  $element_value, 'tongue tie') !== false) || (strpos(  $element_value, 'tie') !== false)) {
									   $ton_tie++;
								    }
									else if ((strpos($element_value, 'h') !== false) || (strpos(  $element_value, 'hea') !== false) && (strpos(  $element_value, 'loss') !== false)) {
									   $hea_loss++;
								    }
									else if ((strpos($element_value, 'inf') !== false) || (strpos(  $element_value, 'infection') !== false)) {
									   $inf++;
								    }
							   }
							   break;
							   
							   case 'Speech Screening':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Fluency':
									$flu++;
									break;
									
									case 'Misarticulation':
									$misarti++;
									break;
									
								  }								  
							   }
							   break;
							   
							   // DENTAL CHECK UP
							   case 'Carious Teeth':
							   if($element_value == "Yes")
							   {
                                 $carious_teeth++;
							   }
							   break;
							   
							   case 'Flourosis':
							   if($element_value == "Yes")
							   {
                                 $flourosis++;
							   }
							   break;
							   
							   case 'Orthodontic Treatment':
							   if($element_value == "Yes")
							   {
                                 $orthodo++;
							   }
							   break;
							   
							   case 'Indication for extraction':
							   if($element_value == "Yes")
							   {
                                 $extraction++;
							   }
							   break;
							   
							   case 'Oral Hygiene':
							   if($element_value == "Poor")
							   {
								  $ohi++;
							   }
							   break;
							   
							   case 'Result':
							   if (strpos($element_value, 'scal') !== false) {
									$scaling_advice++;
								}
							   break;
							   
							}
					   }
				   }
			   }
			}
			  
			$data['docs']['Anaemia']            = $anaemia;
			$data['docs']['Vitamin Deficiency'] = $vit_def;
			$data['docs']['White patches on face']        = $white_patches;
			$data['docs']['Asthma']         = $asthma;
			$data['docs']['Scabis']            = $scabis;
			$data['docs']['Head Ache']            = $head_ache;
			$data['docs']['Stomach Ache']            = $stom_ache;
			$data['docs']['Adv Physical Activity']        = $physical_activity;
			$data['docs']['Adv Breathing Exercise']        = $breathing_ex;
			$data['docs']['Adv Calcium Diet']        = $calcium_diet;
			$data['docs']['Adv Iron Diet']        = $iron_diet;
			$data['docs']['Adv Balanced Diet']        = $balanced_diet;
			$data['docs']['URTI']        = $urti;
			$data['docs']['Thyroid']        = $thyroid;
			$data['docs']['Others']       = $others;
			$data['docs']['With Glass Normal']       = $glass_normal;
			$data['docs']['New Ref Errors']   = $new_ref_err;
			$data['docs']['With Glass Abnormal']     = $glass_abnormal;
			$data['docs']['Bilateral Wax']   = $bl_wax; 
			$data['docs']['Unilateral Wax']  = $ul_wax;
			$data['docs']['Stammering']     = $stam;
			$data['docs']['Misarticulation']          = $misarti;
			$data['docs']['Fluency']     		 = $flu;
			$data['docs']['Tongue Tie']         = $ton_tie;
			$data['docs']['Infection'] 			 = $inf;
			$data['docs']['Hearing Loss'] 			 = $hea_loss;
			$data['docs']['OHI'] 			 = $ohi;
			$data['docs']['Carious Tooth'] 			 = $carious_teeth;
			$data['docs']['Flourosis'] 			 = $flourosis;
			$data['docs']['Orthodonic'] 			 = $orthodo;
			$data['docs']['Extraction'] 			 = $extraction;
			$data['docs']['Scaling Advice'] = $scaling_advice;
			$data['docs']['schoolname']     = $school_value;
			$data['count']                  = count($summary_values);
		}	
	}
	else if($graph_type == "summary_graph")
	{
		// Variable declaration
		$general_problems  = 0;
		$ortho_problems    = 0;
		$vitamin_problems  = 0;
		$anaemia           = 0;
		$asthma            = 0;
		$diabetes          = 0;
		$eye_problems      = 0;
		$dental_problems   = 0;
		$ear_problems      = 0;
		$speech_problems   = 0;
		$nad               = 0;
		$postural_referal  = 0;
		$dental_referal    = 0;
		$section_finished  = "FALSE";
		$eye_section_finished = "FALSE";
		
		
		for($ci=0;$ci < $cond_count; $ci++)
    	{
    		$school_value      = $conditions[$ci]['school_value'];
    		$school_field_name = base64_decode($conditions[$ci]['school_field_name']);
			$school_field_name = "doc_data.widget_data.page".$school_field_name;
			
			$summary_values = $this->ion_auth->fetch_values_for_summary_chart($appid,$school_value,$school_field_name);
			
			foreach($summary_values as $index => $values)
			{
			   $widget_data = $values['doc_data']['widget_data'];
			   $eye_section_finished = "FALSE";
			   foreach($widget_data as $pageno => $section_details)
			   {
				   foreach($section_details as $section_name => $element_details)
				   {
					   $section_finished = "FALSE";
					   foreach($element_details as $element_name => $element_value)
					   {
						   switch($element_name)
						   {
                              // Doctor checkup							  
							  case 'Ortho':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neck':
									case 'Shoulder':
									case 'Arms/Hands':
									case 'Hips':
									case 'Knees':
									case 'Feet':
									//$ortho_problems++;
									if($section_finished == "FALSE")
								    {
									   $general_problems++;
									   $section_finished  = "TRUE";
								    } 
									break;
								  }								  
							   }
							   break;
							   
							   case 'Check the box if normal else describe abnormalities':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neurologic':
									case 'H and N':
									case 'ENT':
									case 'Hips':
									case 'Lymphatic':
									case 'Heart':
									case 'Lungs':
									case 'Abdomen':
									case 'Genitalia':
									case 'Skin':
									if($section_finished == "FALSE")
								    {
									   $general_problems++;
									   $section_finished  = "TRUE";
								    } 
									break;
								  }								  
							   }
							   break;
							   
							   case 'Postural':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                             
									case 'Spinal Abnormality':
									case 'Mild':
									case 'Marked':
									case 'Moderate':
									if($section_finished == "FALSE")
								    {
										$general_problems++;
										$section_finished  = "TRUE";
								    } 
									break;
									
									case 'Referral Made':
									$postural_referal++;
									break;
									
									
								  }								  
							   }
							   break;
							   
							   case 'Defects at Birth':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Neural Tube Defect':
									case 'Down Syndrome':
									case 'Cleft Lip and Palate':
									case 'Talipes Club foot':
									case 'Developmental Dysplasia of Hip':
									case 'Congenital Cataract':
									case 'Congenital Deafness':
									case 'Congenital Heart Disease':
									case 'Retinopathy of Prematurity':
									if($section_finished == "FALSE")
								    {
										$general_problems++;
										$section_finished  = "TRUE";
								    } 
									break;
								  }								  
							   }
							   break;
							   
							   case 'Deficencies':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Anaemia':
									$anaemia++;
									break;
									
									case 'Vitamin Deficiency - Bcomplex':
									case 'Vitamin A Deficiency':
									case 'Vitamin D Deficiency':
									$vitamin_problems++;
									$i = $cnt;
									break;
									
								  }								  
							   }
							   break;
							   
							   case 'Childhood Diseases':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Asthma':
									$asthma++;
									break;
									
									case 'Epilepsy':
									if($section_finished == "FALSE")
								    {
										$general_problems++;
										$section_finished  = "TRUE";
								    } 
									break;
									
									case 'Diabetes':
									//$diabetes++;
									if($section_finished == "FALSE")
								    {
										$general_problems++;
										$section_finished  = "TRUE";
								    }
									break;
									
								  }								  
							   }
							   break;
							   
							   case 'Speech Screening':
							   $cnt = count($element_value);
							   for($i = 0; $i < $cnt; $i++)
							   {
								  switch($element_value[$i])
								  {
                                    case 'Delay':
									case 'Misarticulation':
									$speech_problems++;
									break;
									
								  }								  
							   }
							   break;
							   
							   // Ear & Eye
							   case 'Left':
							   case 'Right':
							   if($section_name == "Auditory Screening" && $element_value == "Fail")
							   {
                                 $ear_problems++;
							   }
							   else if($eye_section_finished == "FALSE" && $section_name == "Without Glasses" && $element_value != "6/6" && $element_value != "")
							   {
								 $eye_problems++;
								 $eye_section_finished = "TRUE";
							   }
							   else if($eye_section_finished == "FALSE" && $section_name == "With Glasses" && $element_value != "6/6" && $element_value != "")
							   {
								 $eye_problems++;
								 $eye_section_finished = "TRUE";
							   }
							   break;
							   
							   case 'Colour Blindness':
							   if($eye_section_finished == "FALSE")
							   {
								   $cnt = count($element_value);
								   for($i = 0; $i < $cnt; $i++)
								   {
									  switch($element_value[$i])
									  {
										case 'Yes':
										$eye_problems++;
										$eye_section_finished = "TRUE";
										break;
									  }								  
								   }
							   }
							   break;
							   
							   // Dental
							   case 'Carious Teeth':
							   case 'Orthodontic Treatment':
							   case 'Flourosis':
							   case 'Indication for extraction':
							   if($element_value == "Yes")
							   {
                                 if($section_finished == "FALSE")
								  {
									$dental_problems++;
									$section_finished  = "TRUE";
								  }
								  
							  }
							   break;
							   
							   case 'Oral Hygiene':
							   if($element_value == "Poor")
							   {
								  if($section_finished == "FALSE")
								  {
									$dental_problems++;
									$section_finished  = "TRUE";
								  }
								  
							   }
							   break;
							   
							   case 'Referral Made':
							   $dental_referal++;
							   break;
							   
							}
					   }
				   }
			   }
			}
			
			$data['docs']['Anaemia']               = $anaemia;
			$data['docs']['Vitamin Deficiencies']  = $vitamin_problems;
			$data['docs']['Asthma']                = $asthma;
			//$data['docs']['Diabetes']            = $diabetes;
			$data['docs']['Eye defects']     	   = $eye_problems;
			//$data['docs']['ortho'] 			   = $ortho_problems;
			$data['docs']['Ear defects'] 		   = $ear_problems;
			$data['docs']['Speech defects']        = $speech_problems;
			$data['docs']['Dental defects']        = $dental_problems;
			$data['docs']['Other defects']         = $general_problems;
			$data['docs']['schoolname']            = $school_value;
			$data['count']                         = count($summary_values);
			
			log_message("debug","data====xaxis".print_r($data,true));
		}	
	}
	
		//sending results to ajax call in queryapp.js
    	$this->output->set_output(json_encode($data));
	}
  
    // --------------------------------------------------------------------
    
    /**
     * Helper : Search for analytics
     *
     * @author  Vikas
     *
     *
     */
    
    function searching()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('searching');
    
    	$querystring = $_POST['strng'];
    	$appid       = $_POST['dataid'];
    	
    	$conditons = json_decode($querystring,true);
    	log_message('debug','7144444444444444444444444444444444444444444444444444444444444444444'.print_r($conditons,true));
    	$search_fields = array();
    	$combined_search = array();
    	$inc = 0;
    	
    	foreach($conditons as $case)
    	{
    		//log_message('debug','71888888888888888888888888888888888888888888888888888888888888888'.print_r($case,true));
    		$field = base64_decode($case['labelname']);
    		$search_fields[$inc] = array("doc_data.widget_data.page"."$field" => $case['value']);
    		//log_message('debug','72222222222222222222222222222222222222222222222222222222222'.print_r($search_fields,true));
    		$inc++;
    	}
    	
    	log_message('debug','72777777777777777777777777777777777777777777777777777777777777777'.print_r($inc,true));
    	//$search_fields = array_merge($search_fields[0],$search_fields[1]);
    	//log_message('debug','722222222888888888888888888888888888888888888888888888888888888888888'.print_r($search_fields,true));
    	for($i=0;$i<$inc;$i++){
    		if($conditons[$i]['option'] == 'AND'){
    			if($i == $inc-1){
    				$combined_search = array_merge($search_fields[$i]);
    			}else{
    				$combined_search = array_merge($search_fields[$i],$search_fields[$i+1]);
    			}
    		}else{
    			if($i == $inc-1){
    				$doc[$i] = $this->ion_auth->query($search_fields[$i],$appid);
    			}else{
    				$doc[$i] = $this->ion_auth->query($search_fields[$i],$appid);
    				$doc[$i+1] = $this->ion_auth->query($search_fields[$i+1],$appid);
    			}
    		}
    		$doc[$i] = $this->ion_auth->query($combined_search,$appid);
    	}
    	
    	for($i=0;$i < $inc;$i++){
    		
    	}
    	//log_message('debug','7522222222222222222222222222222222222222222222222222222222222222222222'.print_r($doc,true));
    	
    	//log_message('debug','7677777777777777777777777777777777777777777777777777777'.print_r($final_doc,true));
    	$one_dimension = array_map("serialize", $doc);
    	$unique_one_dimension = array_unique($one_dimension);
    	$unique_multi_dimension = array_map("unserialize", $unique_one_dimension);
    	//$doc = array_unique($doc);
    	log_message('debug','7400000000000000000000000000000000000000000000000000000000000000000000'.print_r($unique_multi_dimension,true));
    	
    	
    	$data['docs'] = $unique_multi_dimension;
    	
    	$data['count'] = $this->ion_auth->query_app_count($appid);
    	
    	log_message('debug','7344444444444444444444444444444444444444444444444444444444444444444'.print_r($data,true));
    
    	$this->output->set_output(json_encode($data));
    }
    
    // --------------------------------------------------------------------

	/**
	* Helper : Search for analytics
	*
	* @author  Vikas 
	*
	* 
	*/

    function searching_old()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('searching');
    
    	$querystring = $_POST['strng'];
		$appid       = $_POST['dataid'];
		log_message('debug','7066666666666666666666666666666666666666666666666666666666666666666666666'.$appid);
    	$conditons = json_decode($querystring,false);
    	log_message('debug','7144444444444444444444444444444444444444444444444444444444444444444'.print_r($conditons,true));
    	$conarray = array();
    	$ind      = 0;
    	$logic	  = array();

    	foreach($conditons as $conditon)
    	{
    		foreach($conditon as $fld)
    		{
    			array_push($conarray,$fld);
    		}
    		
    		if(isset($conarray[2])=="TRUE")
    		{
    			$operator[$ind]=$conarray[2];
    		}
    		
    		$result[$ind] = $this->ion_auth->query(base64_decode($conarray[0]),$conarray[1],$appid);
    		$ind ++;
    		$conarray = array();
    		
    	}
    	
    	//log_message('debug','73666666666666666666666666666666666666666666666666666666666666666666666666666'.print_r($result,true));
    	//log_message('debug','7388888888888888888888888888888888888888888888888'.print_r($result,true));
    	log_message('debug','739999999999999999999999999999999999999999999999999999999999'.print_r($operator,true));

    	$reid = 0;
    	foreach ($operator as $logi)
    	{
    	  if($logi == "AND")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{log_message('debug','7499999999999999999999999999999999999999999999999999999999'.print_r($res,true));
    			log_message('debug','750000000000000000000000000000000000000000000000000000000000000000'.print_r($res2,true));
    				$logic[$reid] = array_intersect($res,$res2);
    				log_message('debug','75222222222222222222222222222222222222222222222222222222222'.print_r($logic,true));
    			}
    		}
    	  }
    	  elseif($logi == "OR")
    	  {
    		foreach($result[$reid] as $res)
    		{
    			foreach($result[$reid+1] as $res2)
    			{
    				$logic[$reid] = array_merge($res,$res2);  
    			}
    		}
    	 }
    	 $reid++;
    	}
    
        $this->output->set_output(json_encode(array_unique($logic)));
    }

    //***************Analytics***************END***************//

 	// --------------------------------------------------------------------

	/**
	* Helper : List users 
	*
	* @author  Vikas 
	*
	* 
	*/

    function user()
	{
		$this->check_for_admin();
    	$this->check_for_plan('user');

		//set the flash data error message if there is one
		$this->data['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		
		//list the users
		$this->data['users'] = $this->ion_auth->users()->result();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_users', $this->data);
	}

    // --------------------------------------------------------------------
    
	// --------------------------------------------------------------------
	
	/**
	 * Helper : List sub admin
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function sub_admin()
	{
		$this->check_for_admin();
		$this->check_for_plan('sub_admin');
		//set the flash data error message if there is one
		$this->data['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		//list the users
		$this->data['users'] = $this->ion_auth->sub_admin()->result();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_sub_admin', $this->data);
	}

	/**
	* Helper : Excel example
	*
	* @author  Selva 
	*
	* 
	*/

     function excel_example()
     {
	     $this->check_for_admin();
	     $this->check_for_plan('excel_example');
	
	     $sheet = new PHPExcel();
	     $sheet->getProperties()->setTitle('Attendance Report')->setDescription('Attendance Report');
	     $sheet->setActiveSheetIndex(0);
	     $this->load->model('Workflow_Model');
	     $data['users']=$this->Workflow_Model->users();
	     $col = 0;
	     foreach ($data['users'] as $field=>$value)
	     {
		    $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
		    $col++;
	     }
	     $row = 2;
	     foreach ($data['users'] as $data=>$val) 
	     {
		    $col = 0;
		    $sheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val['username']);
			$sheet->getActiveSheet()->getStyle('A6')->getFont()->setSize(20);
			$col++;
	        $row++;
	      }
	      $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
	      header('Content-Type: application/vnd.ms-excel');
	      header('Content-Disposition: attachment;filename="TLSTEC'.date('dMy').'.xls"');
	      header('Cache-Control: max-age=0');
	
	      $sheet_writer->save('php://output');
      }

     //************** Applications (share/unshare) ***************START***************//
	
	 // --------------------------------------------------------------------

	 /**
	 * Helper : Share an app as community app 
	 *
	 * @author  Selva 
	 *
	 * 
	 */

	 function share_app($appid)
	 {
		$this->check_for_admin();
		$this->check_for_plan('share_app');
		
		$userdetail = $this->ion_auth->customer()->row();
		$usercompany = $userdetail->company_name;
		$this->load->model('Workflow_Model');
        $this->Workflow_Model->share_app($appid,$usercompany);
        $this->dash_after_share();
       
	}
	
	// --------------------------------------------------------------------

	 /**
	 * Helper : Redirect to shared apps page after sharing an app as community app
	 *
	 * @author  Selva 
	 *
	 * 
	 */

	function dash_after_share()
	{
		$this->check_for_admin();
		$this->check_for_plan('dash_after_share');

		$total_rows = $this->ion_auth->sharedappscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;


		//find all the categories with paginate and save it in array to past to the view
		$this->data['sharedapps'] = $this->ion_auth->shared_apps($config['per_page'], $page);

		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

        $this->data['appcount'] = $total_rows;
        
		//number page variable
		$this->data['page']    = $page;
		$this->data['message'] = "App successfully shared";
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_apps_shared',$this->data);
	}
	
	// --------------------------------------------------------------------

	 /**
	 * Helper : Unshare an app as community app
	 *
	 * @author  Selva 
	 *
	 * 
	 */

	function unshare_app($appid)
	{
		$this->check_for_admin();
		$this->check_for_plan('unshare_app');
	
		$this->load->model('Workflow_Model');
		$this->Workflow_Model->unshare_app($appid);
		$this->dash_after_unshare();
	}
	
	// --------------------------------------------------------------------

	 /**
	 * Helper : Redirect to private apps page after unshare a community app
	 *
	 * @author  Selva 
	 *
	 * 
	 */

	function dash_after_unshare()
	{
		$this->check_for_admin();
		$this->check_for_plan('dash_after_unshare');
		
		$total_rows = $this->ion_auth->privateappscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['privateapps'] = $this->ion_auth->private_apps($config['per_page'], $page);
		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

        $this->data['appcount'] = $total_rows;

		//number page variable
		$this->data['page'] = $page;
		$this->data['message'] = "App successfully unshared";
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_apps_new',$this->data);
	}

	//************** Applications (share/unshare) ***************END***************//
	
	//************** Applications (Delete) ***************START***************//
	
	// --------------------------------------------------------------------

	/**
	* Helper : Delete app entry in user collection (including collections,mvc etc.,)
	*
	* @param  string  $appid  Application ID
	*
	* @author  Selva 
	*
	*/
	  
	function delete_user_app_collection($appid)
	{
	    $perstagedata    = array();
		$workflow_type   = array();
		$allstages       = array();

		//-------------------------------------------------------------------------------------------//
	
	    $this->load->model('Workflow_Model');
		$currentappdata = $this->Workflow_Model->get_app_for_delete($appid);
		
		foreach($currentappdata as $data)
		{
		  $application_name         = $data['app_name'];
		  $application_per_workflow = $data['workflow'];
		}
		
	     foreach($application_per_workflow as $worktemplate => $temp)
		{
			array_push($allstages,$worktemplate);
			array_push($perstagedata,$temp);
		}

		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
		
		  array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);
	         
		
		    ///////////////////////////////////////////////////////////////////////---single---////////////////////////////////////////////////////////////////////////////////////////////////////////////
		          
			    if($workflow_type[$i] == "single")
			    {
			       $this->delete_single_stage_user_app_collection($perstagedata[$i],$appid,$application_name);
			    }
		
		
		
            ////////////////////////////////////////////////////////////////////////////////---CONDITIONAL---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
			     if($workflow_type[$i] == "conditional")
			     {
			        $arrayCon = $perstagedata[$i];
			        $this->delete_conditional_stage_user_app_collection($arrayCon,$appid,$application_name);
			     }

		
		    ////////////////////////////////////////////////////////////////////////---PARALLEL---///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			    if($workflow_type[$i] == "parallel")
			    {
			       $arrayPar = $perstagedata[$i];
			       unset($arrayPar['Workflow_Type']);
			       $this->delete_parallel_stage_user_app_collection($arrayPar,$appid,$application_name);
			        	 
			    }

	  
	        }
			
	 }
	  
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Delete a specific app from user's application collection - SINGLE STAGE Processing
	 *
	 * @param  array  $perstagedata   			Single stage workflow json
	 * @param  string $appid          			Application ID
	 *
	 * @return void
	 *  
	 * @author Selva 
	 */

	function delete_single_stage_user_app_collection($perstagedata,$appid,$application_name)
	{
		$users      = array();
		$stype      = array();
		$stageindex = array();

		array_push($users,$perstagedata['UsersList']);
		array_push($stype,$perstagedata['Stage_Type']);
		array_push($stageindex,$perstagedata['index']);
	
		foreach($users[0] as $inneruser)
		{
			$this->load->model('Workflow_Model');
			$this->Workflow_Model->delete_user_applist($inneruser.'_applist',$appid);
			if($stype[0]=="device")
			{
				$this->Workflow_Model->delete_user_appcollection($inneruser,$appid,$application_name);
			}
			else if($stype[0]=="hydrid")
			{
				$this->Workflow_Model->delete_user_appcollection($inneruser,$appid,$application_name);
			}
			else
			{
				if($stageindex[0]==1)
				{
					$this->Workflow_Model->delete_user_web_appcollection($inneruser,$appid);
	            }
			}
	    }
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Deletes app entry in user's application collection - PARALLEL STAGE Processing
	 *
	 * @param  array  $arrayPar       Parallel stage workflow json
	 * @param  string $appid       	  Application ID
	 *  
	 * @author Selva 
	 */

	function delete_parallel_stage_user_app_collection($arrayPar,$appid,$application_name)
	{
		$arrayCon             = array();
		$branchs              = array();
		$parallelperstagedata = array();
		$parallelbranches     = array();
		$parallelbranchwtype  = array();
		$parallelstagedata    = array();
	
		foreach($arrayPar as $paral => $para)
		{
			array_push($branchs,$paral);
		}
	
		foreach ($branchs as $perbranch)
		{
			$parallelstagenames = array();
	
			if(isset($parallelperstagedata))
			{
				array_shift($parallelperstagedata);
			}
			if(isset($parallelbranches))
			{
				array_shift($parallelbranches);
			}
			if(isset($parallelbranchwtype))
			{
				array_shift($parallelbranchwtype);
			}

			foreach($arrayPar[$perbranch] as $branchname => $branch)
			{
				array_push($parallelbranches,$branchname);
				array_push($parallelperstagedata,$branch);
			}
	
			$parallelstagecount = count($parallelperstagedata);
	
			for($kk=0;$kk<$parallelstagecount;$kk++)
			{
				array_push($parallelbranchwtype,$parallelperstagedata[$kk]['Workflow_Type']);
				if($parallelbranchwtype[$kk]=="single")
				{
					if(isset($parallelperstagedata[$kk-1]))
					{
						unset($parallelperstagedata[$kk-1]);
					}
	
					$this->delete_single_stage_user_app_collection($parallelperstagedata[$kk],$appid,$application_name);
				}

	            if($parallelbranchwtype[$kk]=="parallel")
	            {
	
	               $arrayParnew = $parallelperstagedata[$kk];
	               unset($arrayParnew['Workflow_Type']);
	               $this->delete_parallel_stage_user_app_collection($arrayParnew,$appid,$application_name);
	             }

				if($parallelbranchwtype[$kk]=="conditional")
				{
				   $arrayCon = $parallelperstagedata[$kk];
				   $this->delete_conditional_stage_user_app_collection($arrayCon,$appid,$application_name);
	            }
	       }
	    }
	
	}
	
	// ---------------------------------------------------------------------------------------

	/**
	 * Helper: Deletes an app entry in user's application collection - CONDITIONAL STAGE Processing
	 *
	 * @param  array  $arrayCon       			Conditional stage workflow json
	 * @param  string $app_temp       			Application ID
	 *  
	 * @author Selva 
	 */

	function delete_conditional_stage_user_app_collection($arrayCon,$appid,$application_name)
	{
	  $arrayPar                           = array();
	  $approvedstage                      = array();
	  $disapprovedstage                   = array();
	  $conditionalperstagedata            = array();
	  $conditionalapprovedwtype           = array();
	  $conditionaldisapprovedwtype        = array();
	  $conditionaldisapprovedperstagedata = array();
	  $conditionalperstagedata            = array();
	  $conditionalstagedata               = array();
	  $disapprovedconditionalstagedata    = array();
	
	  array_push($approvedstage,$arrayCon['approved']);
	  array_push($disapprovedstage,$arrayCon['disapproved']);

	  //---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
	  foreach($approvedstage as $worktemplate => $temp)
	  {
	 	 foreach($temp as $stagename => $t)
	 	 {
			array_push($conditionalperstagedata,$t);
	     }
	  }
	  
	  $conditionalperstagedatacount = count($conditionalperstagedata);
	  for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
	  {
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);
			if($conditionalapprovedwtype[$ii]=="single")
			{		 
				$this->delete_single_stage_user_app_collection($conditionalperstagedata[$ii],$appid,$application_name);
			}
	
	        if($conditionalapprovedwtype[$ii]=="parallel")
			{
				$arrayPar = $conditionalperstagedata[$ii];
				unset($arrayPar['Workflow_Type']);
				$this->delete_parallel_stage_user_app_collection($arrayPar,$appid,$application_name);
			}

			if($conditionalapprovedwtype[$ii]=="conditional")
			{	
				$arrayConnew = $conditionalperstagedata[$ii];
				$this->delete_conditional_stage_user_app_collection($arrayConnew,$appid,$application_name);
	        }
	   }
	
	   //-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
	   foreach($disapprovedstage as $worktemplate => $temp)
	   {
			foreach($temp as $stagename => $t)
			{
				array_push($conditionaldisapprovedperstagedata,$t);
			}
	   }

	   $conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);

	   for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
	   {
	      	array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);
			if($conditionaldisapprovedwtype[$jj]=="single")
			{
				$this->delete_single_stage_user_app_collection($conditionaldisapprovedperstagedata[$jj],$appid,$application_name);
			}

			if($conditionaldisapprovedwtype[$jj]=="parallel")
			{
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar['Workflow_Type']);
				$this->delete_parallel_stage_user_app_collection($arrayPar,$appid,$application_name);
			}

			if($conditionaldisapprovedwtype[$jj]=="conditional")
			{
				$arrayConnew = $conditionaldisapprovedperstagedata[$jj];
				$this->delete_conditional_stage_user_app_collection($arrayConnew,$appid,$application_name);
			}
		}
	
	}

	// --------------------------------------------------------------------

	 /**
	 * Helper : Delete private apps (including collections,mvc etc.,)
	 *
	 * @author  Selva 
	 *
	 * 
	 */
	 function delete_app($id)
	 {
		$this->check_for_admin();
		$this->check_for_plan('delete_app');
		 
		$cusdetail = $this->ion_auth->customer()->row();
		$usercompany = $cusdetail->company_name;
		unlink(APPPATH."controllers/".$usercompany."/".$id."_con.php");
		unlink(APPPATH."models/".$usercompany."/".$id."_mod.php");
		$this->deleteAll(APPPATH."views/".$usercompany."/".$id."_con");	
		
		// delete in respective user collections
		$this->delete_user_app_collection($id);
		
		//delete the item
		if ($this->ion_auth->delete_app($id) == TRUE)
		{
			$this->data['message'] = lang('app_delete_successful');
		}
		else
		{
			$this->data['message'] = lang('app_delete_unsuccessful');
		}
	
		$total_rows = $this->ion_auth->privateappscount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['privateapps'] = $this->ion_auth->private_apps($config['per_page'], $page);
		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
    	redirect('dashboard/apps_allapps');
	
	  }
	
	  // --------------------------------------------------------------------

	  /**
	  * Helper : Delete MY apps (including collections,mvc etc.,)
	  *
	  * @author  Selva 
	  *
	  * 
	  */

	  function delete_my_app($id)
	  {
		$this->check_for_admin();
		$this->check_for_plan('delete_my_app');
		 
		$cusdetail = $this->ion_auth->customer()->row();
		$usercompany = $cusdetail->company_name;
		unlink(APPPATH."controllers/".$usercompany."/".$id."_con.php");
		unlink(APPPATH."models/".$usercompany."/".$id."_mod.php");
		$this->deleteAll(APPPATH."views/".$usercompany."/".$id."_con");	
		
		// delete in respective user collections
		$this->delete_user_app_collection($id);
		
		//delete the item
		if ($this->ion_auth->delete_app($id) == TRUE)
		{
			$this->data['message'] = lang('app_delete_successful');
		}
		else
		{
			$this->data['message'] = lang('app_delete_unsuccessful');
		}
	
		$total_rows = $this->ion_auth->myappscount(); 

	    //---pagination--------//
		$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['myapps'] = $this->ion_auth->MYapps($config['per_page'], $page);
		//create paginate큦 links
	 	$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	    $this->_render_page('admin/admin_dash_apps_myapps', $this->data);
	
	  }

      // --------------------------------------------------------------------

	  /**
	  * Helper : Delete Draft apps (including collections,mvc etc.,)
	  *
	  * @author  Selva 
	  *
	  * 
	  */

	  function delete_draft($id)
	 {
		$this->check_for_admin();
		$this->check_for_plan('delete_draft');
		
		$cusdetail = $this->ion_auth->customer()->row();
		$usercompany = $cusdetail->company_name;
		unlink(APPPATH."controllers/".$usercompany."/".$id."_con.php");
		unlink(APPPATH."models/".$usercompany."/".$id."_mod.php");
		$this->deleteAll(APPPATH."views/".$usercompany."/".$id."_con");	
		//delete the item
		if ($this->ion_auth->delete_app($id) == TRUE)
		{
			$this->data['message'] = lang('app_delete_successful');
		}
		else
		{
			$this->data['message'] = lang('app_delete_unsuccessful');
		}
	
		$total_rows = $this->ion_auth->draftcount();

        //---pagination--------//
	   	$config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		//find all the categories with paginate and save it in array to past to the view
		$this->data['draftapps'] = $this->ion_auth->get_draft_apps($config['per_page'],$page);
		//create paginate큦 links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
	    redirect('dashboard/drafts');
	
	 }
	
	 // --------------------------------------------------------------------

	  /**
	  * Helper : Delete Shared apps (including collections,mvc etc.,)
	  *
	  * @author  Selva 
	  *
	  * 
	  */

	  function delete_shared_app($id)
	  {
         $this->check_for_admin();
		 $this->check_for_plan('delete_shared_app');
		
	     $cusdetail = $this->ion_auth->customer()->row();
		 $usercompany = $cusdetail->company_name;

		 unlink(APPPATH."controllers/".$usercompany."/".$id."_con.php");
		 unlink(APPPATH."models/".$usercompany."/".$id."_mod.php");
		 $this->deleteAll(APPPATH."views/".$usercompany."/".$id."_con");

		 // delete in respective user collections
		 $this->delete_user_app_collection($id);
		
		 //delete the item
		 if ($this->ion_auth->delete_shared_app($id) == TRUE)
		 {
			$this->data['message'] = lang('app_delete_successful');
		 }
		 else
		 {
			$this->data['message'] = lang('app_delete_unsuccessful');
		 }
	
		 $total_rows = $this->ion_auth->sharedappscount();

         //---pagination--------//
	   	 $config = $this->paas_common_lib->set_paginate_options($total_rows,10);

		 //Initialize the pagination class
		 $this->pagination->initialize($config);

		 //control of number page
		 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

		 //find all the categories with paginate and save it in array to past to the view
		 $this->data['sharedapps'] = $this->ion_auth->shared_apps($config['per_page'], $page);

		 //create paginate큦 links
		 $this->data['links'] = $this->pagination->create_links();

		 //number page variable
		 $this->data['page'] = $page;
		 
		 //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

         $this->_render_page('admin/admin_dash_apps_shared', $this->data);
	   }
	
	   // --------------------------------------------------------------------

	   /**
	  * Delete sub-folders within a folder  
	  *
	  *
	  * @param	string	$directory  Path to delete a file
	  * @param	boolean	$empty     
	  * 
	  * @author  Unknown
	  */

	   function deleteAll($directory, $empty = false) 
	   {
		
		 $this->check_for_admin();
		 $this->check_for_plan('deleteAll');
		
		 if(substr($directory,-1) == "/") {
			$directory = substr($directory,0,-1);
		 }
	
		 if(!file_exists($directory) || !is_dir($directory)) {
			return false;
		 } elseif(!is_readable($directory)) {
			return false;
		 } else {
			$directoryHandle = opendir($directory);
	
			while ($contents = readdir($directoryHandle)) {
				if($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
	
					if(is_dir($path)) {
						$this->deleteAll($path);
					} else {
						unlink($path);
					}
				}
			}
	
			closedir($directoryHandle);
	
			if($empty == false) {
				if(!rmdir($directory)) {
					return false;
				}
			}
	
			return true;
		}
	  }
	
	  //************** Applications (Delete) ***************END***************//

	  // --------------------------------------------------------------------

	   /**
	   * Helper : Use an existing app to create a new one
	   *
	   * @author  Vikas 
	   *
	   * 
	   */

	   function use_app($id)
	   {
			$this->check_for_admin();
			$this->check_for_plan('use_app');
			
		    $template = $this->ion_auth->get_app_temp($id);
			$this->session->set_flashdata('template', $template);
			$this->session->set_flashdata('updType','use');
		    $this->design_template();
	   }
	
	   // --------------------------------------------------------------------

	   /**
	   * Helper : Load predefine lists page
	   *
	   * @author  Selva 
	   *
	   * 
	   */

	   function predefine_list()
	   {
		  $this->check_for_admin();
		  $this->check_for_plan('predefine_list');
		
	      $this->data['message'] = "Predefine list page";
		  $this->data['lists'] = $this->ion_auth->predefined_lists();
		  
		  //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		  $this->_render_page('admin/admin_dash_predefine_lists', $this->data);
	   }
	  
	   // --------------------------------------------------------------------

	   /**
	   * Helper : Get admin's enterprise name
	   *
	   * @author  Selva 
	   *
	   * 
	   */

       function get_company_name()
       {
          $this->load->model('Workflow_Model');
	      $data['users']=$this->Workflow_Model->company_name();
	      $company_name=array();
	      foreach ($data['users'] as $user)
	      {
		      $company_name = $user['company_name'];
	      }
	
	      $this->output->set_output(json_encode($company_name));
       }
   
      // --------------------------------------------------------------------

	   /**
	   * Helper : Message from admin
	   *
	   * @author  Selva 
	   *
	   * 
	   */

      function usermessage()
	 {
		$this->check_for_admin();
		$this->check_for_plan('usermessage');
		
	    $imageData = file_get_contents("php://input"); 
	  
	    if (isset($imageData))
	    {
			// Remove the headers (data:,) part.
			// A real application should use them according to needs such as to check image type
			$filteredData=substr($imageData, strpos($imageData, ',')+1);
			
			$this->_page_data = $filteredData;
			// Need to decode before saving since the data we received is already base64 encoded
			$unencodedData=base64_decode($filteredData);

			//echo 'unencodedData'.$unencodedData;

			// Save file. This example uses a hard coded filename for testing,
			// but a real application can specify filename in POST variable

			//finding highest value in _id field
			//$fileID = Veerasofts201421212421192_mod::high_id('veerasofts201421212421192');
			//$file = $fileID + 1;

			//Seprating image data
			$imgData=substr($imageData, 0, strpos($imageData, '&')-1);
			
			$array_data = json_decode($imageData, TRUE);
	
	        $this->load->model('Workflow_Model');
	        $this->Workflow_Model->user_message($array_data['sec']['Name'],$array_data);
        }
    }

     // --------------------------------------------------------------------

	   /**
	   * Helper : Admin's username
	   *
	   * @author  Selva 
	   *
	   * 
	   */

     function adminusername()
     {
           $user = $this->session->userdata("customer");
		 // log_message("error","usersssssssssssssss".print_r($user,true));
           $name = $user['username'];
           $this->output->set_output(json_encode($name));
     }
     
	 function company_logo()
	 {
	 	 
		 $uploaddir = APPHEADERFOLDER;
		 $appid   = $_POST['app_id'];
	     $file = $uploaddir.$appid.".png";

	     $file_path = UPLOADFOLDER.'public/app_header/';

         //create controller upload folder if not exists
	     if (!is_dir($uploaddir))
		 {
			mkdir($uploaddir,0777,TRUE);
		 }	
	     
	     if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $file)) 
			{ 
				echo APPHEADERLOGOPATH.$appid.".png";
			}
			else
			{
				echo "error";
			} 
	 }

	 private function set_app_header_logo_upload_options($appid)
	{	
		//upload an image options
		$config = array();

	    $config['upload_path'] 		= UPLOADFOLDER.'public/uploads/app_header/';
		$config['allowed_types'] 	= 'gif|jpg|png';
		$config['max_size'] 		= '2048';
		$config['file_name']		=  $appid;
		
        //create controller upload folder if not exists
		if (!is_dir($config['upload_path']))
		{
			mkdir(UPLOADFOLDER."public/uploads/app_header/",0777,TRUE);
		}
			
		return $config;
	}

     

	 function edit_profile_old($company)
	 {
		$this->check_for_admin();
		$this->check_for_plan('edit_profile');
		
		$this->data['title'] = "Edit Profile";

        //validate form input
		$this->form_validation->set_rules('company_name', $this->lang->line('edit_profile_company_name_label'), 'required|min_length[5]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('company_address', $this->lang->line('signup_customer_company_address'), 'required|xss_clean');
		$this->form_validation->set_rules('contact_person', $this->lang->line('edit_profile_contactp_label'), 'required|xss_clean');
		$this->form_validation->set_rules('mobile_number', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
		$this->form_validation->set_rules('company_website', $this->lang->line('signup_customer_company_website'), 'required|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
		   $uploaddir = PROFILEUPLOADFOLDER;
			  
			  if (!is_dir($uploaddir))
	          {
				 mkdir($uploaddir,0777,TRUE);
	          }
			  
			  // Logged In Admin Details
		      $loggedinuser  = $this->session->userdata("customer");
		      $loggedemail   = $loggedinuser['email'];
		   
		      /***** Profile Image *****/
              $file = $uploaddir.$loggedemail.".png"; 
			  
			  if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) 
              { 
				  // creating image thumbnail for header profile image
				  // Get the CodeIgniter super object
				  $CI =& get_instance();

				  // Path to image thumbnail
				  $image_thumb = $uploaddir.$loggedemail."_thumb.png";

				  // LOAD LIBRARY
				  $CI->load->library( 'image_lib' );

				  // CONFIGURE IMAGE LIBRARY
				  $config['image_library']    = 'gd2';
				  $config['source_image']     = $file;
				  $config['new_image']        = $image_thumb;
				  $config['maintain_ratio']   = false;
				  $config['height']           = 50;
				  $config['width']            = 50;
				  $CI->image_lib->initialize( $config );
				  $CI->image_lib->resize();
				  $CI->image_lib->clear();
              }
			  else
			  {
			    $this->session->set_flashdata('message', "Profile Image upload failed");
				redirect('dashboard/admin_profile');
			  }
			  
			  /***** Company Logo *****/
			  
			  // DEFAULT CONFIGURATIONS
			  $maxWidth  = 252;
		      $maxHeight = 52;
		   
		      $file = $uploaddir.TENANT."logo.png";
			  
              list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
			  
		      if ($width > $maxWidth || $height > $maxHeight)
              {
		         $this->session->set_flashdata('message', "Please upload company logo with pre-defined size");
				 redirect('dashboard/admin_profile');
		      }
			  else
			  {
			     if (move_uploaded_file($_FILES['logo']['tmp_name'], $file))
                 {
		            
                 }
                 else
                 {
				    $this->session->set_flashdata('message', "Logo Image upload failed");
				    redirect('dashboard/admin_profile');
           	     }
			  
			  }
		  
           
				  
            $data = array(
				'company_name'     => $this->input->post('company_name'),
				'company_address'  => $this->input->post('company_address'),
				'contact_person'   => $this->input->post('contact_person'),
				'mobile_number'    => $this->input->post('mobile_number'),
				'email'            => $this->input->post('email'),
				'company_website'  => $this->input->post('company_website'),
				'username'         => $this->input->post('username')
		   );

           if ($this->form_validation->run() === TRUE)
		   {	
				$customer = $this->ion_auth->customer()->row();
				$postcompany = $customer->company_name;
				$res = $this->ion_auth->admin_profile_update($postcompany,$data);
                if($res)
				{
                  $this->session->set_flashdata('message', "Profile updated");
				  redirect('dashboard/admin_profile');
				}
                else
               {
			      $this->session->set_flashdata('message', "Profile updated failed");
				  redirect('dashboard/admin_profile');
               }			   
			}
		}

		//display the edit user form
		$this->data['csrf'] = get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$user = $this->ion_auth->customer()->row();
		
		$this->data['company_name'] = array(
			'name'  => 'company_name',
			'id'    => 'company_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company_name', $user->company_name),
			'readonly'=> 'readonly',
		);
		$this->data['company_address'] = array(
			'name'  => 'company_address',
			'id'    => 'company_address',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company_address', $user->company_address),
		);
		$this->data['contact_person'] = array(
			'name'  => 'contact_person',
			'id'    => 'contact_person',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('contact_person', $user->contact_person),
		);
		$this->data['company_website'] = array(
			'name'  => 'company_website',
			'id'    => 'company_website',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company_website', $user->company_website),
		);
		
		$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'email',
				'value' => $this->form_validation->set_value('email', $user->email),
				'readonly'=> 'readonly',
			); 
		
		
		$this->data['mobile_number'] = array(
				'name'  => 'mobile_number',
				'id'    => 'mobile_number',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('mobile_number', $user->mobile_number),
			);
			
		$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username', $user->username),
			);	
			
		$this->data['file'] = array(
				'name'  => 'file',
				'id'    => 'file',
				'type'  => 'file',
				'value' => $this->form_validation->set_value('file'),
			);
			
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

		$this->_render_page('admin/admin_profile_edit', $this->data);
	}
	
	// --------------------------------------------------------------------

	/**
	  * Helper : Edit enterprise admin's profile
	  *
	  * @author  Selva 
	  * 
	*/
	  
	function edit_profile()
	{
		$this->check_for_admin();
		$this->check_for_plan('edit_profile');
		
		$this->data['title'] = "Edit Profile";

        //validate form input
		$this->form_validation->set_rules('company_name', $this->lang->line('edit_profile_company_name_label'), 'required|min_length[5]|max_length[25]|xss_clean');
		$this->form_validation->set_rules('company_address', $this->lang->line('signup_customer_company_address'), 'required|xss_clean');
		$this->form_validation->set_rules('contact_person', $this->lang->line('edit_profile_contactp_label'), 'required|xss_clean');
		$this->form_validation->set_rules('mobile_number', $this->lang->line('signup_customer_company_contact_mobile'), 'required|xss_clean');
		$this->form_validation->set_rules('company_website', $this->lang->line('signup_customer_company_website'), 'required|xss_clean');
		$this->form_validation->set_rules('username', $this->lang->line('signup_customer_username'), 'required|xss_clean');

		if ($this->form_validation->run() === TRUE)
		{
		   $data = array(
				'company_address'  => $this->input->post('company_address'),
				'contact_person'   => $this->input->post('contact_person'),
				'mobile_number'    => $this->input->post('mobile_number'),
				'company_website'  => $this->input->post('company_website'),
				'username'         => $this->input->post('username')
		   );
		   
		   if (isset($_FILES) && !empty($_FILES))
		   {
		      if($_FILES['file']['tmp_name']!='' || $_FILES['logo']['tmp_name']!='')
			  {
				  $uploaddir = PROFILEUPLOADFOLDER;
				  
				  if (!is_dir($uploaddir))
				  {
					 mkdir($uploaddir,0777,TRUE);
				  }
				  
				  // Logged In Admin Details
				  $loggedinuser  = $this->session->userdata("customer");
				  $loggedemail   = $loggedinuser['email'];
			   
				  /***** Profile Image *****/
				  $file = $uploaddir.$loggedemail.".png";
				  
				  if($_FILES['file']['tmp_name']!='')
			      {
					  if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) 
					  { 
						  // creating image thumbnail for header profile image
						  // Get the CodeIgniter super object
						  $CI =& get_instance();

						  // Path to image thumbnail
						  $image_thumb = $uploaddir.$loggedemail."_thumb.png";

						  // LOAD LIBRARY
						  $CI->load->library( 'image_lib' );

						  // CONFIGURE IMAGE LIBRARY
						  $config['image_library']    = 'gd2';
						  $config['source_image']     = $file;
						  $config['new_image']        = $image_thumb;
						  $config['maintain_ratio']   = false;
						  $config['height']           = 50;
						  $config['width']            = 50;
						  $CI->image_lib->initialize( $config );
						  $CI->image_lib->resize();
						  $CI->image_lib->clear();
					  }
					  else
					  {
						$this->session->set_flashdata('message', "Profile Image upload failed");
						redirect('dashboard/admin_profile');
					  }
				  }
				  /***** Company Logo *****/
				  
				  // DEFAULT CONFIGURATIONS
				  $maxWidth  = 252;
				  $maxHeight = 52;
			   
				  $file = $uploaddir.TENANT."logo.png";
				  
				  if($_FILES['logo']['tmp_name']!='')
			      {
					  list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
					  
					  if ($width > $maxWidth || $height > $maxHeight)
					  {
						 $this->session->set_flashdata('message', "Please upload company logo with pre-defined size");
						 redirect('dashboard/edit_profile');
					  }
					  else
					  {
						 if (move_uploaded_file($_FILES['logo']['tmp_name'], $file))
						 {
							
						 }
						 else
						 {
							$this->session->set_flashdata('message', "Logo Image upload failed");
							redirect('dashboard/admin_profile');
						 }
					  
					  }
				  }
			  }
			}
			  
			  $customer = $this->ion_auth->customer()->row();
			  $postcompany = $customer->company_name;
			  $this->ion_auth->admin_profile_update($postcompany,$data);
			  $this->session->set_flashdata('message', "Profile updated");
			  
			  // After Updating profile data, now update session data with new data
			  $document = $this->ion_auth->customer()->row();
			  $user     = (object) $document;
			  
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
				
			  $this->session->set_userdata("customer",$session_data);	
			  
			  redirect('dashboard/admin_profile'); 
           
		}		  
        else
        { 		

		    //display the edit user form
			$this->data['csrf'] = get_csrf_nonce();

			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			//pass the user to the view
			$user = $this->ion_auth->customer()->row();
		
			$this->data['company_name'] = array(
				'name'  => 'company_name',
				'id'    => 'company_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company_name', $user->display_company_name),
				'readonly'=> 'readonly',
			);
			$this->data['company_address'] = array(
				'name'  => 'company_address',
				'id'    => 'company_address',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company_address', $user->company_address),
			);
			$this->data['contact_person'] = array(
				'name'  => 'contact_person',
				'id'    => 'contact_person',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contact_person', $user->contact_person),
			);
			$this->data['company_website'] = array(
				'name'  => 'company_website',
				'id'    => 'company_website',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company_website', $user->company_website),
			);
		
			$this->data['email'] = array(
					'name'    => 'email',
					'id'      => 'email',
					'type'    => 'email',
					'value'   => $this->form_validation->set_value('email', $user->email),
					'readonly'=> 'readonly',
				); 
			
			
			$this->data['mobile_number'] = array(
					'name'  => 'mobile_number',
					'id'    => 'mobile_number',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('mobile_number', $user->mobile_number),
				);
				
			$this->data['username'] = array(
					'name'  => 'username',
					'id'    => 'username',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('username', $user->username),
				);	
			
			$this->data['file'] = array(
					'name'  => 'file',
					'id'    => 'file',
					'type'  => 'file',
					'value' => $this->form_validation->set_value('file'),
				);

		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

			$this->_render_page('admin/admin_profile_edit', $this->data); 
	    }
	}	

	//************** Upload (SQL,No-SQL,Document,Lists) ***************START***************//
	
	// --------------------------------------------------------------------

	/**
	* Helper : Load Import SQL page
	*
	* @author  Vikas 
	* 
	*/

	function sql_import()
	{
		$this->check_for_admin();
		$this->check_for_plan('sql_import');

		$this->data['message'] = FALSE;
		$this->data['apps'] = $this->ion_auth->apps();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_sql_import', $this->data);
	}

    // --------------------------------------------------------------------

	/**
	* Helper : Load Import No-SQL page
	*
	* @author  Vikas 
	* 
	*/

   	function nosql_import()
	{
		$this->check_for_admin();
		$this->check_for_plan('nosql_import');

		$this->data['message'] = FALSE;
		$this->data['apps'] = $this->ion_auth->apps();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_nosql_import', $this->data);
	}

    // --------------------------------------------------------------------

	/**
	* Helper : Load Import Document page
	*
	* @author  Vikas 
	* 
	*/

  	function document_import()
	{
		$this->check_for_admin();
		$this->check_for_plan('document_import');
		
		$this->data['apps'] = $this->ion_auth->apps();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_document_import', $this->data);
	}

    // --------------------------------------------------------------------

	/**
	* Helper : Upload SQL file
	*
	* @author  Vikas 
	* 
	*/

	function uploadSQL()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('uploadSQL');
    	
    	$appid   = $_POST['appid'];
    	$appname = $_POST['appname'];
    	 
    	
	    $uploaddir = UPLOADFOLDER;
		
		$config['upload_path'] 		= $uploaddir;
		$config['allowed_types'] 	= "json";
		$config['max_size']			= '0';
		$config['max_width']  		= '0';
		$config['max_height']  		= '0';
		$config['remove_spaces']  	= TRUE;
		$config['encrypt_name']  	= TRUE;
		 
		$this->load->library('upload', $config);
		$this->load->helper('file');
		
	    
	    if ($this->upload->do_upload("file")) 
	    {
	    	$updata = array('upload_data' => $this->upload->data());
	    	$data = read_file($updata['upload_data']['full_path']);
	    	$records = substr($data, strpos($data, '['),(strlen($data)));
	    	log_message('debug','SQL_IMPORT=====$RECORDS=====1603'.print_r($records,true));
	    	$array_record = json_decode($records,true);
	    	log_message('debug','SQL_IMPORT=====$array_record=====1605'.print_r($array_record,true));

	    	foreach($array_record as $single_rec)
	    	{
		    	$_id = get_unique_id();
		    	$id  = get_unique_id();
		    	
		    	$collection = $this->session->userdata("customer");
		    	$useremail  = str_replace("@","#",$collection['email']);
		    	
                $jsonarray['app_properties']['app_id'] = $appid;
                $jsonarray['app_properties']['app_name'] = $appname;
                $jsonarray['doc_data']['username'] = $useremail;
                $jsonarray['doc_data']['stage_name'] = 'SQL import';
                $jsonarray['doc_data']['current_stage_name'] = 'SQL import';
                $jsonarray['doc_data']['widget_data']['page1']['section'] = $single_rec;

				$jsonarray['doc_properties']['doc_id'] = $id;
				$jsonarray['doc_properties']['_version'] = 1;
				$jsonarray['doc_properties']['status'] = 1;
				$jsonarray['doc_properties']['owner'] = $collection['username'];

				$jsonarray['history'][0]['time'] = date('Y-m-d H:i:s');
				$jsonarray['history'][0]['current_stage_name'] = 'SQL import';
				$jsonarray['history'][0]['approval'] = 'true';
				$jsonarray['history'][0]['submitted_by'] = $useremail;

				$this->ion_auth->json_import($appid,$jsonarray);
				$this->ion_auth->json_import($appid.'_shadow',$jsonarray);
	    	}
	    	
	    	unlink($updata['upload_data']['full_path']);
	    	
	    	$this->data['message'] = "The file ".$updata['upload_data']['client_name']." uploaded successfully!";
    		//list the apps
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
    			
    		$this->_render_page('admin/admin_dash_sql_import', $this->data);
	    }
	    else
	    {
	    	$this->data['message'] = $this->upload->display_errors();
    		//list the apps
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);

    		$this->_render_page('admin/admin_dash_sql_import', $this->data);
	    }
    }
    
    // --------------------------------------------------------------------

	/**
	* Helper : Upload no-sql file
	*
	* @author  Vikas 
	* 
	*/

    function uploadNoSql()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('uploadNoSql');
    	
    	$appid   = $_POST['appid'];
    	$appname = $_POST['appname'];
    	 
    	$uploaddir = UPLOADFOLDER;
    	
    	$config['upload_path'] 		= $uploaddir;
    	$config['allowed_types'] 	= "json";
    	$config['max_size']			= '0';
    	$config['max_width']  		= '0';
    	$config['max_height']  		= '0';
    	$config['remove_spaces']  	= TRUE;
    	$config['encrypt_name']  	= TRUE;
    		
    	$this->load->library('upload', $config);
    	$this->load->helper('file');
    	
	    
	    if ($this->upload->do_upload("file")) 
	    { 
	    	
	    	$updata = array('upload_data' => $this->upload->data());
	    	$data = read_file($updata['upload_data']['full_path']);
	    	$records = '['.$data.']';
	    	$array_record = json_decode($records, true);
	    	
	    	foreach($array_record as $single_rec)
	    	{
		    	$_id = get_unique_id();
		    	$id  = get_unique_id();
		    	
		    	$collection = $this->session->userdata("customer");
		    	$useremail  = str_replace("@","#",$collection['email']);
		    	
                $jsonarray['app_properties']['app_id'] = $appid;
                $jsonarray['app_properties']['app_name'] = $appname;
                $jsonarray['doc_data']['username'] = $useremail;
                $jsonarray['doc_data']['stage_name'] = 'NoSQL import';
                $jsonarray['doc_data']['current_stage_name'] = 'NoSQL import';
                $jsonarray['doc_data']['widget_data']['page1']['section'] = $single_rec;

				$jsonarray['doc_properties']['doc_id'] = $id;
				$jsonarray['doc_properties']['_version'] = 1;
				$jsonarray['doc_properties']['status'] = 1;
				$jsonarray['doc_properties']['owner'] = $collection['username'];

				$jsonarray['history'][0]['time'] = date('Y-m-d H:i:s');
				$jsonarray['history'][0]['current_stage_name'] = 'NoSQL import';
				$jsonarray['history'][0]['approval'] = 'true';
				$jsonarray['history'][0]['submitted_by'] = $useremail;
		    	
		    	$this->ion_auth->json_import($appid,$jsonarray);
		    	$this->ion_auth->json_import($appid.'_shadow',$jsonarray);
	    	}
	    	
	    	unlink($updata['upload_data']['full_path']);
	    	
	    	$this->data['message'] = "The file ".$updata['upload_data']['client_name']." uploaded successfully!";
    		//list the apps
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
    			
    		$this->_render_page('admin/admin_dash_nosql_import', $this->data);
	    } 
	    else 
	    {
	    	$this->data['message'] = "Importing failed";
    		//list the apps
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
    			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
    		$this->_render_page('admin/admin_dash_nosql_import', $this->data);
		}
    }

    // --------------------------------------------------------------------

	/**
	* Helper : Upload single document
	*
	* @author  Vikas 
	* 
	*/

    function uploadSingle()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('uploadSingle');
    	
    	$appid   = $this->input->post('appid', TRUE);
    	$code    = $this->input->post('code', TRUE);
    	$appname = $this->input->post('appname', TRUE);
    	
    	$id = get_unique_id();
    	
    	$uploadfolder = UPLOADFOLDER .'public/uploads/'. $appid.'/files/';
    	
    	if(!file_exists($uploadfolder))
    	{
    		mkdir_safe($uploadfolder, DIR_WRITE_MODE, true);
    	}

    	$config['upload_path'] 		= $uploadfolder;
    	$config['allowed_types'] 	= "gif|jpeg|jpg|png|pdf";
    	$config['max_size']			= '0';
    	$config['max_width']  		= '0';
    	$config['max_height']  		= '0';
    	$config['remove_spaces']  	= TRUE;
    	$config['encrypt_name']  	= TRUE;
    	
    	$this->load->library('upload', $config);
    	
    	if ( ! $this->upload->do_upload("file"))
    	{
    		
    		$this->data['message'] = $this->upload->display_errors();
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
    	
    		$this->_render_page('admin/admin_dash_document_import', $this->data);
    	}
    	else
    	{
    		$uploaddata = $this->upload->data();
    		
    		$id = get_unique_id();
    	    $json = json_decode($code,true);
    		//$jsonarray['index'] = $json;
    		$jsonarray['app_id'] = $appid;
    		$collection = $this->session->userdata("customer");
    		$useremail  = str_replace("@","#",$collection['email']);

		    $filearray = array(
    				'file_client_name' => $uploaddata['client_name'],
    				'file_encrypted_name' => $uploaddata['file_name'],
    				'file_path'=>$uploaddata['file_relative_path'],
    				'file_size'=>$uploaddata['file_size']
    		);

            $jsonarray['app_properties']['app_id'] = $appid;
            $jsonarray['app_properties']['app_name'] = $appname;
            $jsonarray['doc_data']['username'] = $useremail;
            $jsonarray['doc_data']['stage_name'] = 'Single Document import';
            $jsonarray['doc_data']['current_stage_name'] = 'Single Document import';
            $jsonarray['doc_data']['widget_data']['page1']['section'] = $json;
            $jsonarray['doc_data']['widget_data']['page1']['section']['single_document'] = $filearray;


			$jsonarray['doc_properties']['doc_id'] = $id;
			$jsonarray['doc_properties']['_version'] = 1;
			$jsonarray['doc_properties']['status'] = 1;
			$jsonarray['doc_properties']['owner'] = $collection['username'];

			$jsonarray['history'][0]['time'] = date('Y-m-d H:i:s');
			$jsonarray['history'][0]['current_stage_name'] = 'Single Document import';
			$jsonarray['history'][0]['approval'] = 'true';
			$jsonarray['history'][0]['submitted_by'] = $useremail;
    		
    		
    		$this->ion_auth->doc_import($appid, $jsonarray);
    		$this->ion_auth->doc_import($appid.'_shadow', $jsonarray);
    		
    		$this->data['message'] = "The file ".$uploaddata['client_name']." uploaded successfully!";
    		$this->data['apps'] = $this->ion_auth->apps();
    		$this->data['numberofapps'] = count($this->ion_auth->apps());
    		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
    	
    		$this->_render_page('admin/admin_dash_document_import', $this->data);
    	}
    }
	
	// --------------------------------------------------------------------

	/**
	* Helper : Upload predefined lists (text,csv files)
	*
	* @author  Selva 
	* 
	*/

	function upload_list()
    {
    	$this->check_for_admin();
    	$this->check_for_plan('upload_list');
    	
	    $uploaddir = UPLOADFOLDER;
	    $file = $uploaddir.basename($_FILES['file']['name']);
	    $file_name= "paas_".$_FILES['file']['name'];
	    
	    if (move_uploaded_file($_FILES['file']['tmp_name'], $file))
	    {
	    	
	    	$fp = fopen( $file, 'rb' );
	    	$data = fread($fp,filesize($file));
	    	fclose( $fp );
	        $records    = substr($data, strpos($data, '['),strlen($data));
			$list_value = explode( ',', $records); 
			$list_name  = substr($_FILES['file']['name'], 0,strpos($_FILES['file']['name'], '.'));
			$collection = "lists";
	    	
			$this->ion_auth->list_import($collection,$list_name,$list_value);
	    	$this->data['lists'] = $this->ion_auth->predefined_lists();
			$this->data['message'] = "List import success";
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
	    	$this->_render_page('admin/admin_dash_predefine_lists', $this->data);
	    	
	    } 
	    else 
	    {
	    	
			 $this->data['message'] = "List import failed";
			 
			 //bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
			 $this->_render_page('admin/admin_dash_predefine_lists', $this->data);
	    }
    } 
	
	// --------------------------------------------------------------------

	/**
	* Helper : Create Predefined list
	*
	* @author  Selva 
	* 
	*/

	function create_list()
	{
		$this->check_for_admin();
		$this->check_for_plan('create_list');

		$this->form_validation->set_rules('list_name', $this->lang->line('template_app_name'), 'required|xss_clean');
		$this->form_validation->set_rules('list_values', $this->lang->line('app_description'), 'required|xss_clean');
		
		if ($this->form_validation->run() == true)
		{
		    $listname  = $this->input->post('list_name',TRUE);
			$listvalue = $this->input->post('list_values',TRUE);
			$listvalue = explode( ',', $listvalue); 
			
			$collection = "lists";
			$this->ion_auth->list_import($collection,$listname,$listvalue);
			$this->data['lists'] = $this->ion_auth->predefined_lists();
			$this->data['message'] = "List creation success";
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		    $this->_render_page('admin/admin_dash_predefine_lists', $this->data);
	    }
		else
		{
		    $this->data['message'] = "List creation failed";
			
			//bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		    $this->_render_page('admin/admin_dash_predefine_lists', $this->data);
		}
	}

	//************** Upload (SQL,No-SQL,Document,Lists) ***************END***************//
    
    // --------------------------------------------------------------------

	/**
	* Helper : Get application - edit/use part
	*
	* @author  Vikas 
	* 
	*/

	function get_app($id,$updType = FALSE)
    {
    	$this->check_for_admin();
    	$this->check_for_plan('get_app');
    	
    	//get the item
    	$template = $this->ion_auth->get_app_temp($id);
        $this->app_prop($template,$updType);
    }
	
	// --------------------------------------------------------------------

	/**
	* Helper : Get community app - edit/use part
	*
	* @author  Vikas 
	* 
	*/

	function get_community_app($id,$updType = FALSE)
    {
    	$this->check_for_admin();
    	$this->check_for_plan('get_community_app');
    	
    	 //get the item
    	$template = $this->ion_auth->get_community_app_temp($id);
    	$this->session->set_flashdata('template', $template);
    	$this->session->set_flashdata('updType', $updType);
        $this->app_prop($template,$updType);
    }

    // --------------------------------------------------------------------

	/**
	* Helper : Get documents count in a specified app
	*
	* @author  Vikas 
	* 
	*/

    function docss($appid)
    {
    	$docss = $this->ion_auth->count($appid);
    	if($docss == "")
    	{
    		$docss = 0;
    	}
    	$this->output->set_output($docss);
    }

	//***************Graphs***************START***************//

    // ------------------------------------------------------------------------

	/**
	 * Helper: Graphs 
	 * 
	 * 
	 * @author Sekar 
	 */

    function appgraph()
    {  
 	   $this->check_for_admin();
 	   $this->check_for_plan('appgraph');
 	   $time=date(" H:i:s", time());
	   $this->data = $this->ion_auth->graph_apps($time); 
    }

    //***************Graphs***************END***************//

    //***************Drop down notification***************START***************//
    
    // ------------------------------------------------------------------------

	/**
	 * Helper: Recently created application list
	 * 
	 * 
	 * @author Sekar 
	 */

	function app_history()
	{
	   $data = $this->ion_auth->app_history_model();
	   log_message('debug','app_histroy'.print_r($data,true));
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Recently submitted documents list
	 * 
	 * 
	 * @author Sekar 
	 */

	function docs_history()
	{
	   $data = $this->ion_auth->docs_history_model(); 
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Messages to enterprise admin from TLSTEC ( regarding offers etc.,)
	 * 
	 * 
	 * @author Sekar 
	 */

	function admin_messages()
	{
	   $data = $this->ion_auth->admin_message_model(); 
	   log_message('debug','app_histroy'.print_r($data,true));
	   $this->output->set_output(json_encode($data));
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Documents count
	 * 
	 * 
	 * @author Sekar 
	 */

	function docs_count()
	{
	   $data = $this->ion_auth->docs_count_model(); 
	   $this->output->set_output(json_encode($data));
	}

	//***************Drop down notification***************END***************//


	// ------------------------------------------------------------------------

	/**
	 * Helper: Change of plan
	 * 
	 * @param string $companyname Name of the company
	 * 
	 * @author Selva 
	 */

	
	 function plan_upgrade($companyname)
	 {

	 }

    // ------------------------------------------------------------------------

	/**
	 * Helper: Lists all users of the enterprise ( for app listing)
	 * 
	 * 
	 * @author Vikas 
	 */

	function pre_app_listing()
	{
		$this->check_for_admin();
		$this->check_for_plan('pre_app_listing');
		
		$user    = $this->session->userdata("customer");
		$company = $user['company'];
		$this->data['users'] = $this->ion_auth->users()->result();

		$this->load->model('Workflow_Model');
		$this->data['groups'] = $this->Workflow_Model->getgroups($company);
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_app_listing', $this->data);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Lists all apps assigned for that specific user
	 * 
	 * @param string $id
	 * 
	 * @author Vikas 
	 */

	function app_listing($id)
	{
		$this->check_for_admin();
		$this->check_for_plan('app_listing');
		
		$this->data['user']    = $id;
		$this->data['apps']    = $this->ion_auth->get_apps($id);
		$this->data['message'] = "Apps Listed";
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_app_listing',$this->data);
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Changes the status of app
	 *  
	 * @author Vikas 
	 */

	function app_status()
	{
		
		$this->check_for_admin();
		$this->check_for_plan('app_status');
		
		$_id    = $_POST['_id'];
		$user   = $_POST['user'];
		$status = $_POST['status'];
		
		if($status == 'new'){
			$status = 'processed';
		}else{
			$status = 'new';
		}
		
		$this->data['reply'] = $this->ion_auth->change_status($_id,$user,$status);
		
		if($this->data['reply'] == true){
			$this->data['message'] = "Changes done!";
		}else{
			$this->data['message'] = "No changes done!";
		}
		
		$this->output->set_output(json_encode($this->data));
	}

	//***************Application Specification***************START***************//
	
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetches application related details for showing application specification
	 * 
	 * @param  string  $appid  Application id
	 * 
	 * @author Selva 
	 */
	 
	function fetch_app_specification($appid)
	{
	   $this->check_for_admin();
	   $this->check_for_plan('fetch_app_specification');
		
	   $keys = array();
	   $data = $this->ion_auth->fetch_details_for_app_specification($appid);
      
	   foreach($data as $appdata)
	   {
	     $this->data['appname']            = $appdata['app_name'];
		 $this->data['appexpiry']          = $appdata['app_expiry'];
		 $this->data['appdescription']     = $appdata['app_description'];
		 $this->data['pages']              = $appdata['pages'];
		 $this->data['appcategory']        = $appdata['app_category'];
		 $this->data['createdby']          = $appdata['created_by'];
		 $this->data['version']            = $appdata['_version'];
		 $this->data['createdtime']        = $appdata['time'];
		 $this->data['apptype']            = $appdata['app_type'];
		 $workflow                         = $appdata['workflow'];
		 $this->data['application_header'] = $appdata['application_header'];
		 
		 if(isset($appdata['workflow']))
		 {
		    $workflow = $appdata['workflow'];
			$workflowspec = $this->process_workflow_for_specification($workflow);
		 }
		 
		 if(isset($appdata['sms_content']))
		 {
		    $sms_content = $appdata['sms_content'];
			$sms_content_spec = $this->process_sms_content_for_specification($sms_content);
		 }

         if(isset($appdata['print_template']))
		 {
		    $print_template_content = $appdata['print_template'];
			$print_template_content_spec = $this->process_print_template_content_for_specification($print_template_content);
		 }

          if(isset($appdata['notify_parameters']))
		 {
		    $notify_parameters = $appdata['notify_parameters'];
			$notify_parameters_spec = $this->process_notify_parameters_for_specification($notify_parameters);
		 }		 
	   }
	   
	   $workflowspec = $this->process_workflow_for_specification($workflow);
	   if(isset($workflowspec) && !empty($workflowspec))
	   {
	      $this->data['workflow'] = $workflowspec;
	   }
       else
       {
          $this->data['workflow'] = '';
       }

       // SMS
	   if(isset($sms_content_spec) && !empty($sms_content_spec))
	   {
	      $this->data['sms_content'] = $sms_content_spec;
	   }
       else
       {
          $this->data['sms_content'] = '';
       }

	   // PRINT TEMPLATE
       if(isset($print_template_content_spec) && !empty($print_template_content_spec))
	   {
	      $this->data['print_template'] = $print_template_content_spec;
	   }
       else
       {
          $this->data['print_template'] = '';
       }

       // NOTIFY PARAM
       if(isset($notify_parameters_spec) && !empty($notify_parameters_spec))
	   {
	      $this->data['notify_param'] = $notify_parameters_spec;
	   }
       else
       {
          $this->data['notify_param'] = '';
       }  	   
	   
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	   $this->_render_page('admin/admin_dash_app_specification', $this->data);
	
	}
	
    // ------------------------------------------------------------------------

	/**
	 * Helper: Fetches community application related details for showing application specification
	 *  
	 * @param  string  $appid  Application id
	 *
	 * @author Selva 
	 */
	 
	function fetch_community_app_specification($appid)
	{
	   $this->check_for_admin();
	   $this->check_for_plan('fetch_community_app_specification');
		
	   $keys = array();
	   $data = $this->ion_auth->fetch_details_for_community_app_specification($appid);
      
	   foreach($data as $appdata)
	   {
	     $this->data['appname']        = $appdata['app_name'];
		 $this->data['appexpiry']      = $appdata['app_expiry'];
		 $this->data['appdescription'] = $appdata['app_description'];
		 $this->data['appcategory']    = $appdata['app_category'];
		 $this->data['createdby']      = $appdata['shared_by'];
		 $this->data['createdtime']    = $appdata['app_created_on'];
		 $this->data['apptype']        = $appdata['app_type'];
	   }
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);

	   $this->_render_page('admin/admin_dash_community_app_specification', $this->data);
	
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Process workflow json for getting workflow specification
	 * 
	 * @param  array  $workflow  Workflow json
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	function process_workflow_for_specification($workflow)
	{
	 
		$perstagedata         = array();
		$stagedata            = array();
		$workflow_type        = array();
		$allstages            = array();
		$singlestagedata      = array();
		$parallelstagedata    = array();
		$conditionalstagedata = array();
	
		
		foreach($workflow as $temp => $t)
		{
		  array_push($allstages,$temp);
		  array_push($perstagedata,$t);
		}
		
		$count = count($perstagedata);
		for ($i = 0; $i < $count; $i++)
		{
		array_push($workflow_type,$perstagedata[$i]['Workflow_Type']);
		if($workflow_type[$i] == "single")
		{
			$singlestagedata = $this->get_single_stage_workflow_specification($perstagedata[$i],$allstages[$i]);
			array_push($stagedata,$singlestagedata);
		}
		
		if($workflow_type[$i] == "parallel")
		{
			$arrayPar = $perstagedata[$i];
			unset($arrayPar['Workflow_Type']);
			$parallelstagedata = $this->get_parallel_stage_workflow_specification($arrayPar);
			$stagedata = array_merge($stagedata,$parallelstagedata);
		
		}
		
		if($workflow_type[$i] == "conditional")
		{
			$arrayCon = $perstagedata[$i];
			$conditionalstagedata = $this->get_conditional_stage_workflow_specification($arrayCon);
			$stagedata = array_merge($stagedata,$conditionalstagedata);
		}
		
		}
		
		return $stagedata;
	
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Process sms json for getting sms specification
	 * 
	 * @param  array  $sms_content  SMS JSON
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	function process_sms_content_for_specification($sms_content)
	{
	   // Variable Declaration
       $sms_     = array();
       $sms_spec = array();	   
	   
       foreach($sms_content as $stage_name => $content)
	   {
          $sms['stage_name'] = $stage_name;
          $sms['message']    = $content['full_message'];
          array_push($sms_,$sms);		  
	   }
	   
	   $sms_spec = array_merge($sms_spec,$sms_);
	   return $sms_spec;	   
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Process print template json for getting print template specification
	 * 
	 * @param  array  $print_template_content  PRINT TEMPLATE JSON
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	function process_print_template_content_for_specification($print_template_content)
	{
	   // Variable Declaration
       $print_     = array();
       $print_spec = array();
	   
       foreach($print_template_content as $page_num => $content)
	   {
          $print['page_num'] = $page_num;
          $print['title']    = $content['file_title'];
		  $print['desc']     = $content['file_description'];
          array_push($print_,$print);		  
	   }
	   
	   $print_spec = array_merge($print_spec,$print_);
	   return $print_spec;	   
	}
	
	// --------------------------------------------------------------------------------

	/**
	 * Helper: Process notify param json for getting notification elements specification
	 * 
	 * @param  array  $notify_parameters  NOTIFICATION PARAM JSON
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
	function process_notify_parameters_for_specification($notify_parameters)
	{
	   // Variable Declaration
       $notify_     = array();
       $notify_spec = array();
	   
       foreach($notify_parameters as $index => $content)
	   {
          $notify['page_num'] = $content['page'];
          $notify['section']  = $content['section'];
		  $notify['field']    = $content['field'];
          array_push($notify_,$notify);		  
	   }
	   
	   $notify_spec = array_merge($notify_spec,$notify_);
	   return $notify_spec;	   
	}
    
	// ------------------------------------------------------------------------

	/**
	 * Helper: Processing workflow json for getting workflow specification - SINGLE Stage
	 * 
	 * @param  array  $arraypar  Parallel stage workflow json
	 * @param  string $stagename Name of the stage
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
    function get_single_stage_workflow_specification($perstagedata,$stagename)
	{
		$users              = array();
		$vp                 = array();
		$ep                 = array();
		$stype              = array();
		$singlestagedetails = array();
		
		array_push($users,$perstagedata['UsersList']);
		array_push($vp,$perstagedata['View_Permissions']);
		array_push($ep,$perstagedata['Edit_Permissions']);
		array_push($stype,$perstagedata['Stage_Type']);

		$singlestagedetails['View_Permissions'] = $vp[0];
		$singlestagedetails['Users']            = $users[0];
		$singlestagedetails['Edit_Permissions'] = $ep[0];
		$singlestagedetails['Stage']            = $stagename;
		$singlestagedetails['Stage_Type']       = $stype[0];
		$singlestagedetails['Print']            = $perstagedata['print'];
		$singlestagedetails['SMS']              = $perstagedata['sms'];
	  
		return $singlestagedetails;
	}

	// ------------------------------------------------------------------------

	/**
	 * Helper: Processing workflow json for getting workflow specification - PARALLEL Stage
	 * 
	 * @param  array $arraypar Parallel stage workflow json
	 *
	 * @return array
	 * 
	 * @author Selva 
	 */
	 
    function get_parallel_stage_workflow_specification($arrayPar)
	{
	  $arrayCon             = array();
	  $branchs              = array();
	  $parallelperstagedata = array();
	  $parallelbranches     = array();
	  $parallelbranchwtype  = array();
	  $parallelstagedata    = array();
	  $stagedata            = array();
	
	 foreach($arrayPar as $paral => $para)
	 {
		array_push($branchs,$paral);
	 }
	
	 foreach ($branchs as $perbranch)
	 {
	    $parallelstagenames = array();
	
	    if(isset($parallelperstagedata))
	    {
	    	log_message('debug','$parallelperstagedata=====2452=====dashboard'.print_r($parallelperstagedata,true));
			array_shift($parallelperstagedata);
			log_message('debug','$parallelperstagedata=====2454=====dashboard'.print_r($parallelperstagedata,true));
	    }

	    if(isset($parallelbranches))
	    {
			array_shift($parallelbranches);
	    }

	    /*if(isset($parallelbranchwtype))
	    {
			array_shift($parallelbranchwtype);
	    }*/

	    foreach($arrayPar[$perbranch] as $branchname => $branch)
	    {
			array_push($parallelbranches,$branchname);
			array_push($parallelperstagedata,$branch);
	    }
	
	    $parallelstagecount = count($parallelperstagedata);
	
	    for($kk=0;$kk<$parallelstagecount;$kk++)
	    {
	 		array_push($parallelbranchwtype,$parallelperstagedata[$kk]['Workflow_Type']);
	        if($parallelbranchwtype[$kk]=="single")
	        {
	           if(isset($parallelperstagedata[$kk-1]))
	           {
					unset($parallelperstagedata[$kk-1]);
	           }
	
	           if(isset($parallelstagenames[$kk-1]))
	           {
	                unset($parallelstagenames[$kk-1]);
	           }
	
	
	          foreach($arrayPar[$perbranch] as $branchname => $branch)
	          {
	              if($branch['Workflow_Type']=="single")
	              {
					  array_push($parallelstagenames,$branchname);
	              }
	          }
	
	          $singlestagedata = $this->get_single_stage_workflow_specification($parallelperstagedata[$kk],$parallelstagenames[$kk]);
	          array_push($stagedata,$singlestagedata);
	        }
	
	        if($parallelbranchwtype[$kk]=="parallel")
	        {
	          $arrayParnew = $parallelperstagedata[$kk];
	          unset($arrayParnew['Workflow_Type']);
	          $parallelstagedata = $this->get_parallel_stage_workflow_specification($arrayParnew);
	          $stagedata = array_merge($stagedata,$parallelstagedata);
	        }

	        if($parallelbranchwtype[$kk]=="conditional")
	        {
	          $arrayCon = $parallelperstagedata[$kk];
	          log_message('debug','$arrayCon=====2514=====dashboard'.print_r($arrayCon,true));
	          log_message('debug','$parallelbranchwtype=====2515=====dashboard'.print_r($parallelbranchwtype,true));
	          log_message('debug','$kk=====2516=====dashboard'.print_r($kk,true));
	          $conditionalstagedata = $this->get_conditional_stage_workflow_specification($arrayCon);
	          $stagedata = array_merge($stagedata,$conditionalstagedata);
            }
	    }
	}
	  return $stagedata;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Processing workflow json for getting workflow specification - Conditional Stage
	 * 
	 * @param  array $arrayCon Conditional stage workflow json
	 * 
	 * @return array
	 *
	 * @author Selva 
	 */
	 
	function get_conditional_stage_workflow_specification($arrayCon)
	{
		log_message('debug','$arrayCon=====2529'.print_r($arrayCon,true));

	  $arrayPar                           = array();
	  $stagedata                          = array();
	  $approvedstage                      = array();
	  $disapprovedstage                   = array();
	  $approvedstagenames                 = array();
      $conditionalstagedata               = array();
	  $disapprovedstagenames              = array();
	  $conditionalperstagedata            = array();
	  $conditionalperstagedata            = array();
	  $conditionalapprovedwtype           = array();
      $conditionaldisapprovedwtype        = array();
	  $disapprovedconditionalstagedata    = array();
	  $conditionaldisapprovedperstagedata = array();
	  
	
	  array_push($approvedstage,$arrayCon['approved']);
	  array_push($disapprovedstage,$arrayCon['disapproved']);

	  //---------------------------------------------------------------Approved----------------------------------------------------------------------------------//
	  foreach($approvedstage as $worktemplate => $temp)
	  {
	     foreach($temp as $stagename => $t)
	     {
	         array_push($conditionalperstagedata,$t);
	     }
	  }
	  $conditionalperstagedatacount = count($conditionalperstagedata);
	
	  for($ii = 0;$ii < $conditionalperstagedatacount; $ii++)
	  {
			array_push($conditionalapprovedwtype,$conditionalperstagedata[$ii]['Workflow_Type']);
	        if($conditionalapprovedwtype[$ii]=="single")
	        {
	           foreach($approvedstage as $worktemplate => $temp)
	           {
	              foreach($temp as $stagename => $t)
	              {
	                 if($t['Workflow_Type']=="single")
	                 {
	                    array_push($approvedstagenames,$stagename);
	                 }
	              }
	            }		 
	            $singlestagedata = $this->get_single_stage_workflow_specification($conditionalperstagedata[$ii],$approvedstagenames[$ii]);
	            array_push($stagedata,$singlestagedata);
	        }

	        if($conditionalapprovedwtype[$ii]=="parallel")
	        {
	            $arrayPar = $conditionalperstagedata[$ii];
	            unset($arrayPar['Workflow_Type']);
	            $approvedparallelstagedata = $this->get_parallel_stage_workflow_specification($arrayPar);
	            $stagedata = array_merge($stagedata,$approvedparallelstagedata);
	        }

	        if($conditionalapprovedwtype[$ii]=="conditional")
	        {
	            $arrayConnew = $conditionalperstagedata[$ii];
	            $approvedconditionalstagedata = $this->get_conditional_stage_workflow_specification($arrayConnew);
	            $stagedata = array_merge($stagedata,$approvedconditionalstagedata);
	        }
	  }
	
	  //-------------------------------------------------------------Disapproved-----------------------------------------------------------------------------------//
	  foreach($disapprovedstage as $worktemplate => $temp)
	  {
	     foreach($temp as $stagename => $t)
	     {
	         array_push($conditionaldisapprovedperstagedata,$t);
	     }
	  }
	  $conditionaldisapprovedstagescount = count($conditionaldisapprovedperstagedata);
	  for($jj = 0;$jj < $conditionaldisapprovedstagescount; $jj++)
	  {
	       array_push($conditionaldisapprovedwtype,$conditionaldisapprovedperstagedata[$jj]['Workflow_Type']);
	       if($conditionaldisapprovedwtype[$jj]=="single")
	       {
	          foreach($disapprovedstage as $worktemplate => $temp)
	          {
	             foreach($temp as $stagename => $t)
	             {
	                if($t['Workflow_Type']=="single")
	                {
						array_push($disapprovedstagenames,$stagename);
	                }
	             }
	           }
	           $conditionalstagedata = $this->get_single_stage_workflow_specification($conditionaldisapprovedperstagedata[$jj],$disapprovedstagenames[$jj]);
	           array_push($stagedata,$conditionalstagedata);
	        }

	        if($conditionaldisapprovedwtype[$jj]=="parallel")
	        {
				$arrayPar = $conditionaldisapprovedperstagedata[$jj];
				unset($arrayPar['Workflow_Type']);
				$disapprovedparallelstagedata = $this->get_parallel_stage_workflow_specification($arrayPar);
				$stagedata = array_merge($stagedata,$disapprovedparallelstagedata);
	        }

	        if($conditionaldisapprovedwtype[$jj]=="conditional")
	        {
	            $arrayConnew = $conditionaldisapprovedperstagedata[$jj];
	            $disapprovedconditionalstagedata = $this->get_conditional_stage_workflow_specification($arrayConnew);
	            $stagedata = array_merge($stagedata,$disapprovedconditionalstagedata);
	        }
	   }

	   return $stagedata;
	}

	//***************Application Specification***************END***************//
	
	// ------------------------------------------------------------------------

	/**
	 * Helper: Download Files securely
	 *  
	 * @author Vikas 
	 */

	 public function external_file_download_call()
	 {
		$path = $_GET['path'];
		$this->external_file_download($path);
	 }

	 public function secure_file_download($path)
	 {
	 	$path = str_replace('=','/',$path);
        $this->external_file_download($path);
	 }
	 
	 public function send_sms()
	 {
		
		$reply = $this->bhashsms->send_sms("9789779801","Demo Application 1 created... Download in your device !");
		echo $reply;
	 }
	 
	 
	 // reading the excel file for testing purpose temporary function by veera//
	function read_excel()
	{ 
		exit();
		$row_value = 0;
		$arr_count = 0;
		//$header_array = array("Admission No","Student name","Student Addres","Class","Section","Gender","Age","DOB","District","School Contact Number","Students Parent/Guardian Contact Number");
		
		$header_array = array("Admn.No","Student Name","Student Address","Class","Section","Gender","Age","DOB","District","School Contact Num","Parentes Ph.Num");
	
		$file = EXCEL.'/MBNR-Jadcherla MG.xlsx';
		//$file = 'E:/TLSTEC/Program Files/wamp/www/PaaS/bootstrap/dist/excel/Mahendrahills.xls';
		
		//load the excel library
		$this->load->library('excel');
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		//extract to a PHP readable array format
		/* log_message("debug","entered into read_excel cell_collection".print_r($cell_collection,true)); */
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			$arr_count++;
			
			if (in_array($data_value, $header_array)) {
				$header[$row][$column] = $data_value;
				$row_value = $row;
			}
			else if($row_value > 0)
			{
				log_message('debug','$header[$row_value][$column]======140'.print_r($header[$row_value][$column],true));
				if($header[$row_value][$column] == "Parentes Ph.Num")
				{					
					$data_value = substr($data_value,0,10);
				} 
				
				if($header[$row_value][$column] == "DOB")
				{
					//$data_value = PHPExcel_Style_NumberFormat::toFormattedString($data_value, "YYYY-MM-DD");
					$date = new DateTime($data_value);
                    $data_value= $date->format('Y-m-d');
				}
				$arr_data[$row][$header[$row_value][$column]] = $data_value;
				
			}
				
			}
			
			$doc_data = array();
			$form_data = array();
			$count = 0;
			
			for($j=$row_value+1;$j<count($arr_data);$j++){
				
			 
			$doc_data['widget_data']['page1']['Personal Information']['Name'] = $arr_data[$j]['Student Name'];
			$doc_data['widget_data']['page1']['Personal Information']['Photo'] = "";
			$doc_data['widget_data']['page1']['Personal Information']['Date of Birth'] = $arr_data[$j]['DOB'];
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['country_code'] = '91';
			$doc_data['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] = $arr_data[$j]['Parentes Ph.Num'];
			$doc_data['widget_data']['page2']['Personal Information']['AD No'] =(String) $arr_data[$j]['Admn.No'];
			$doc_data['widget_data']['page2']['Personal Information']['Class'] = $arr_data[$j]['Class'];
			$doc_data['widget_data']['page2']['Personal Information']['Section'] = $arr_data[$j]['Section'];
			$doc_data['widget_data']['page2']['Personal Information']['District'] = $arr_data[$j]['District'];
			$doc_data['widget_data']['page2']['Personal Information']['School Name'] = 'TSWRS-MG,JADCHERLA';
			$doc_data['widget_data']['page2']['Personal Information']['Father Name'] = '';
			$doc_data['widget_data']['page2']['Personal Information']['Date of Exam'] = '';
			$doc_data['widget_data']['page3'] = [];
			$doc_data['widget_data']['page4'] = [];
			$doc_data['widget_data']['page5'] = [];
			$doc_data['widget_data']['page6'] = [];
			$doc_data['widget_data']['page7'] = [];
			$doc_data['widget_data']['page8'] = [];
			$doc_data['widget_data']['page9'] = [];
			
			$doc_properties['doc_id'] = get_unique_id();
			$doc_properties['status'] = 1;
			$doc_properties['_version'] = 1;
			$doc_properties['doc_owner'] = "PANACEA";
			$doc_properties['unique_id'] = '';
			$doc_properties['doc_flow'] = "new";
			
			$history['last_stage']['current_stage'] = "stage1";
			$history['last_stage']['approval'] = "true";
			$history['last_stage']['submitted_by'] = "medusersw1#gmail.com";
			$history['last_stage']['time'] = date("Y-m-d H:i:s");
			
			$this->load->model('Workflow_Model');
			$this->Workflow_Model->insert_excel_data($doc_data,$history,$doc_properties);
			$count++;
			}
			
			//log_message("debug","entered into read_excel form_data".print_r($form_data,true));
	}
	
		function db_to_excel(){
	 	$striped_doc = array();
	 	$doc_mini = array();
	 	$docs = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.School Name" => "TSWRS-MUSHYD" ))->get("healthcare2016226112942701");//healthcare2016226112942701
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddd'.print_r(count($docs),true));
	 	foreach ($docs as $doc){
	 		foreach ($doc["doc_data"]["widget_data"] as $page_no => $page){
	 			$doc_mini[$page_no] = [];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page1"]["Personal Information"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page2"]["Personal Information"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page4"][];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page5"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page6"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page7"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page8"];
	 		//$doc_mini = $doc["doc_data"]["widget_data"]["page9"];
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
// 	 		$doc_mini = array_merge($doc_mini, $doc);
	 		//$doc_mini = array_merge($doc_mini, $page);
	 		foreach ($page as $sec_name => $sec){
	 			
	 			if(array_key_exists($sec_name, $doc_mini[$page_no])){
	 				//usort($sec, "cmp");
	 				ksort($sec);
	 				//log_message('debug','0000000000000000000000000000000000000000000000000'.print_r($sec,true));
	 				
	 				$doc_mini[$page_no][$sec_name] = array_merge($doc_mini[$page_no][$sec_name], $sec);
	 			
	 			}else{
	 				//usort($page, "cmp");
	 				array_multisort($page, SORT_ASC);
	 				//log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($page,true));
	 				
	 				$doc_mini[$page_no] = array_merge($doc_mini[$page_no], $page);
	 				
	 			}
	 			
	 		}
	 		
	 	}
	 	array_push($striped_doc, $doc_mini);
	 	}
	 	
	 	//log_message('debug','11111111111111111111111111111111111111111111'.print_r($striped_doc,true));
	 	//log_message('debug','2222222222222222222222222222222222222222222'.print_r(json_encode($striped_doc),true));
	 	
	 	//load the excel library
		$this->load->library('excel');
		
		//read file from path
		//$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Create new PHPExcel object
		echo date('H:i:s') . " Create new PHPExcel object\n";
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		echo date('H:i:s') . " Set properties\n";
		$objPHPExcel->getProperties()->setCreator("TLS Digital Technologies Pvt. Ltd.");
		$objPHPExcel->getProperties()->setLastModifiedBy("Vikas Singh Chouhan");
		$objPHPExcel->getProperties()->setTitle("Document collection");
		$objPHPExcel->getProperties()->setSubject("Document collection");
		$objPHPExcel->getProperties()->setDescription("Document collection of student health check up.");
		
		// Add some data
		echo date('H:i:s') . " Add some data\n";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student Health Check Up');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Page1');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Personal Information');
		$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'Photo');
		$objPHPExcel->getActiveSheet()->SetCellValue('C4', 'Mobile');
		$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'Date of Birth');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Page2');
		$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Personal Information');
		$objPHPExcel->getActiveSheet()->SetCellValue('E4', 'Class');
		$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Section');
		$objPHPExcel->getActiveSheet()->SetCellValue('G4', 'AD No');
		$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'School Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('I4', 'Father Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'Date of Exam');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Page3');
		$objPHPExcel->getActiveSheet()->SetCellValue('K3', 'Physical Exam');
		$objPHPExcel->getActiveSheet()->SetCellValue('K4', 'H B');
		$objPHPExcel->getActiveSheet()->SetCellValue('L4', 'Height cms');
		$objPHPExcel->getActiveSheet()->SetCellValue('M4', 'Weight kgs');
		$objPHPExcel->getActiveSheet()->SetCellValue('N4', 'BMI%');
		$objPHPExcel->getActiveSheet()->SetCellValue('O4', 'Pulse');
		$objPHPExcel->getActiveSheet()->SetCellValue('P4', 'B P');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q4', 'Blood Group');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('R2', 'Page4');
		$objPHPExcel->getActiveSheet()->SetCellValue('R3', 'Doctor Check Up');
		$objPHPExcel->getActiveSheet()->SetCellValue('R4', 'Ortho');
		$objPHPExcel->getActiveSheet()->SetCellValue('S4', 'Advice');
		$objPHPExcel->getActiveSheet()->SetCellValue('T4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('U4', 'Postural');
		$objPHPExcel->getActiveSheet()->SetCellValue('V4', 'Check the box if normal else describe abnormalities');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('W2', 'Page5');
		$objPHPExcel->getActiveSheet()->SetCellValue('W3', 'Doctor Check Up');
		$objPHPExcel->getActiveSheet()->SetCellValue('W4', 'Defects at Birth');
		$objPHPExcel->getActiveSheet()->SetCellValue('X4', 'Deficencies');
		$objPHPExcel->getActiveSheet()->SetCellValue('Y4', 'Childhood Diseases');
		$objPHPExcel->getActiveSheet()->SetCellValue('Z4', 'N A D');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AA2', 'Page6');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA3', 'Without Glasses');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AB4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC3', 'With Glasses');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AD4', 'Left');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AE2', 'Page7');
		$objPHPExcel->getActiveSheet()->SetCellValue('AE3', 'Colour Blindness');
		$objPHPExcel->getActiveSheet()->SetCellValue('AE4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AF4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AG4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('AH4', 'Referral Made');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AI2', 'Page8');
		$objPHPExcel->getActiveSheet()->SetCellValue('AI3', ' Auditory Screening');
		$objPHPExcel->getActiveSheet()->SetCellValue('AI4', 'Right');
		$objPHPExcel->getActiveSheet()->SetCellValue('AJ4', 'Left');
		$objPHPExcel->getActiveSheet()->SetCellValue('AK4', 'Speech Screening');
		$objPHPExcel->getActiveSheet()->SetCellValue('AL4', 'Referral Made');
		$objPHPExcel->getActiveSheet()->SetCellValue('AM4', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('AN4', 'D D and disablity');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('AO2', 'Page9');
		$objPHPExcel->getActiveSheet()->SetCellValue('AO3', 'Dental Check-up');
		$objPHPExcel->getActiveSheet()->SetCellValue('AO4', 'Oral Hygiene');
		$objPHPExcel->getActiveSheet()->SetCellValue('AP4', 'Carious Teeth');
		$objPHPExcel->getActiveSheet()->SetCellValue('AQ4', 'Flourosis');
		$objPHPExcel->getActiveSheet()->SetCellValue('AR4', 'Orthodontic Treatment');
		$objPHPExcel->getActiveSheet()->SetCellValue('AS4', 'Indication for extraction');
		$objPHPExcel->getActiveSheet()->SetCellValue('AT4', 'Result');
		$objPHPExcel->getActiveSheet()->SetCellValue('AU4', 'Referral Made');
		
		
		//====================values==================================================
		$row = 5;
		foreach ($docs as $doc)
		{
			//if($row <16){
			//foreach ($doc["doc_data"]["widget_data"] as $page_no => $page)
			{
				//foreach ($page as $sec_name => $sec)
				{
				//=======================================
				if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']))
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Name']);
					
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_path']))
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, URLCustomer.$doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Photo']['file_path']);
					
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Mobile']['mob_num']))
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Mobile']['mob_num']);
					
					if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Date of Birth']))
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $doc["doc_data"]["widget_data"]["page1"]["Personal Information"]['Date of Birth']);
				}
				
				
				if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Class']))
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Class']);
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Section']))
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Section']);
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['AD No']))
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['AD No']);
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['School Name']))
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['School Name']);
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Father Name']))
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Father Name']);
					if(isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Date of Exam']))
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row, $doc["doc_data"]["widget_data"]["page2"]["Personal Information"]['Date of Exam']);
				
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['H B']))
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['H B']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Height cms']))
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Height cms']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Weight kgs']))
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Weight kgs']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['BMI%']))
					$objPHPExcel->getActiveSheet()->SetCellValue('N'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['BMI%']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Pulse']))
					$objPHPExcel->getActiveSheet()->SetCellValue('O'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Pulse']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['B P']))
					$objPHPExcel->getActiveSheet()->SetCellValue('P'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['B P']);
					if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Blood Group']))
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$row, $doc["doc_data"]["widget_data"]["page3"]["Physical Exam"]['Blood Group']);
					
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']))
					$objPHPExcel->getActiveSheet()->SetCellValue('R'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Ortho']));
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']))
					$objPHPExcel->getActiveSheet()->SetCellValue('S'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Advice']);
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']))
					$objPHPExcel->getActiveSheet()->SetCellValue('T'.$row, $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Description']);
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']))
					$objPHPExcel->getActiveSheet()->SetCellValue('U'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Postural']));
					if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']) && is_array($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']))
					$objPHPExcel->getActiveSheet()->SetCellValue('V'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]['Check the box if normal else describe abnormalities']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]))
				{
					//log_message('debug','doctorrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr'.print_r($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth'],true));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']))
					$objPHPExcel->getActiveSheet()->SetCellValue('W'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Defects at Birth']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']))
					$objPHPExcel->getActiveSheet()->SetCellValue('X'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Deficencies']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']))
					$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['Childhood Diseases']));
					if(isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']) && is_array($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']))
					$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"]['N A D']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$row, $doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']);
					if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$row, $doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']);
					if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Right']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$row, $doc["doc_data"]["widget_data"]["page6"]["With Glasses"]['Right']);
					if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]['Left']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$row, $doc["doc_data"]["widget_data"]["page6"]["With Glasses"]['Left']);
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Right']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Right']);
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Left']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Left']);
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Description']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$row, $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Description']);
					if(isset($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page7"]["Colour Blindness"]['Referral Made']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Right']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Left']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Speech Screening']));
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Referral Made']));
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$row, $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['Description']);
					if(isset($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']) && is_array($doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AN'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page8"][" Auditory Screening"]['D D and disablity']));
				}
				
				if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]))
				{
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Oral Hygiene']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AO'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Oral Hygiene']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Carious Teeth']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AP'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Carious Teeth']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Flourosis']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Flourosis']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Orthodontic Treatment']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AR'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Orthodontic Treatment']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Indication for extraction']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AS'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Indication for extraction']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Result']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AT'.$row, $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Result']);
					if(isset($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']) && is_array($doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']))
					$objPHPExcel->getActiveSheet()->SetCellValue('AU'.$row, implode(", ", $doc["doc_data"]["widget_data"]["page9"]["Dental Check-up"]['Referral Made']));
				}
				
				//---------------------------------------
				}
			}
			
			if(!empty($doc["doc_data"]["external_attachments"])){
				log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($doc["doc_data"]["external_attachments"],true));
				$objPHPExcel->getActiveSheet()->SetCellValue('AV2', 'External Attachments');
				$i = 1;
				foreach($doc["doc_data"]["external_attachments"] as $attachment){
				$objPHPExcel->getActiveSheet()->SetCellValue('AV4', 'Attachment_'.$i);
				log_message('debug','attttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($attachment,true));
				if(isset($attachment['file_path']))
				$objPHPExcel->getActiveSheet()->SetCellValue('AV'.$row, URLCustomer.$attachment['file_path']);
				$i++;
				}
			}
			
			$row ++;
			//}else{
				//$this->to_dashboard();
			//}
		}
		
		// Save Excel 2007 file
		echo date('H:i:s') . " Write to Excel2007 format\n";
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(EXCEL."/document.xlsx");
		
		// Echo done
		echo date('H:i:s') . " Done writing file.\r\n";
		
		$this->secure_file_download(EXCEL."/document.xlsx");
		
		unlink(EXCEL."/document.xlsx");
		
		
	 	
	 	//$flatten_docs = new RecursiveIteratorIterator(new RecursiveArrayIterator($striped_doc));
	 	
	 	//log_message('debug','3333333333333333333333333333333333333333333333333'.print_r($flatten_docs,true));
	 	
// 	 	$fp = fopen('G:/SkyDrive/PaaS/bootstrap/dist/test.csv', 'w');

// 		//foreach ($flatten_docs as $fields) 
// 		{
// 		    fputcsv($fp, $flatten_docs);
// 		}
		
// 		fclose($fp);
	 	
	 	//echo count($docs);
	 }
	 
	 function db_filter(){
	 	
	 	//$docs_count = $docs = $this->mongo_db->select(array("doc_data.widget_data.page2.Personal Information.School Name","doc_data.widget_data.page2.Personal Information.AD No"))->whereNotIn("doc_data.widget_data.page2.Personal Information.School Name", array('TSWRS-MUSHYD', 'TSWRS-MAHENDRA HILLS', 'TSWRS/JC(G)-JADCHERLA', 'TSWRS-MG,JADCHERLA'))->get("healthcare2016226112942701");
		
		//$docs_count = $docs = $this->mongo_db->select(array("doc_data.widget_data.page2.Personal Information.School Name","doc_data.widget_data.page2.Personal Information.AD No"))->where(array("doc_data.widget_data.page3" => array(),"doc_data.widget_data.page4" => array(),"doc_data.widget_data.page5" => array(),"doc_data.widget_data.page6" => array(),"doc_data.widget_data.page7" => array(),"doc_data.widget_data.page8" => array(),"doc_data.widget_data.page9" => array(), "doc_data.widget_data.page2.Personal Information.School Name" => 'TSWRS-MUSHYD'))->get("healthcare2016226112942701");
		
		$docs_count = $docs = $this->mongo_db->select(array("doc_data.widget_data.page2.Personal Information.School Name","doc_data.widget_data.page2.Personal Information.AD No"))->where(array("doc_data.widget_data.page3.Physical Exam.Height cms" => "", "doc_data.widget_data.page3.Physical Exam.Weight kgs" => "", "doc_data.widget_data.page2.Personal Information.School Name" => 'TSWRS-MUSHYD'))->get("healthcare2016226112942701");
		
		//$docs_count = $docs = $this->mongo_db->select(array("doc_data.widget_data.page2.Personal Information.School Name","doc_data.widget_data.page2.Personal Information.AD No"))->where(array("doc_data.widget_data.page9" => array(), "doc_data.widget_data.page2.Personal Information.School Name" => 'TSWRS-MUSHYD'))->get("healthcare2016226112942701");
		
		echo count($docs_count);
		$print_arr = [];
		foreach($docs_count as $doc)
		{
			array_push($print_arr, $doc["doc_data"]['widget_data']['page2']['Personal Information']['AD No']);
			//echo "============================================================";
		}
		echo print_r($print_arr,true);
		
	 	
	 	//log_message('debug','doccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r(count($docs_count),true));
	 	
	 	//log_message('debug','wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww'.print_r($docs_count,true));
	 	
	 	//$docs_count = $docs = $this->mongo_db->where("doc_data.widget_data.page1.Personal Information.Date of Birth", "")->get("healthcare2016226112942701");
	 	 
	 	//log_message('debug','doccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r(count($docs_count),true));
	 	 
	 	//log_message('debug','wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww'.print_r($docs_count,true));
	 	
// 	 	$docs = $this->mongo_db->where("doc_data.widget_data.page2.Personal Information.School Name", "")->get("healthcare2016226112942701");
	 	
// 	 	$docs = json_decode(json_encode($docs),true);
// 	 	$dup = 0;
// 	 	$dup_AD_no = [];
// 	 	foreach ($docs as $doc)
// 	 	{
// 	 		if(!isset($doc["device_properties"]))// && $doc["doc_data"]["widget_data"]['page2']['Personal Information']['Class'] == 'MPC')
// 	 		{
// 	 			//log_message('debug','docccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r(count($docs),true));
// 	 			$dup_doc_count = $this->mongo_db->where(array("doc_data.widget_data.page2.Personal Information.AD No" => $doc["doc_data"]["widget_data"]['page2']['Personal Information']['AD No']))->count("healthcare2016226112942701");
// 	 			if($dup_doc_count == 2)
// 	 			{
// 	 				$dup ++;
// 	 				array_push($dup_AD_no, $doc["doc_data"]["widget_data"]['page2']['Personal Information']['AD No']);
// 	 				$query = $this->mongo_db->where("_id", new MongoId($doc['_id']['$id']))->get("healthcare2016226112942701");
// 	 				$query = $this->mongo_db->insert("healthcare2016226112942701_dup",$query[0]);
// 	 				if($query)
// 	 					$query = $this->mongo_db->where("_id", new MongoId($doc['_id']['$id']))->delete("healthcare2016226112942701");
	 				
// 	 				//log_message('debug','docccccccccccc'.print_r($query,true));
// 	 			}
// 	 		}
// 	 	}
	 }
	 
	 // ------------------------------------------------------------------------

	/**
	 * Helper: Fetch applications ( for Retriever and Mapper fields )
	 *  
	 * @author Selva 
	 */
	 
	 public function fetch_applications()
	 {
	    $hybrid_data = array();
		$apptemplate = array();
		$widget_data = array();
		$applist     = array();
		$list        = array();
		
	    $data = $this->ion_auth->fetch_applications(); 
		
		foreach($data as $appdata)
		{
		   $list['appname']= $appdata['app_name'];
		   $list['appid']  = $appdata['_id'];
		   array_push($applist,$list);
		   array_push($apptemplate,$appdata['app_template']);
		}
		
		$hybrid_data['applist'] = $applist;
		
		$appcount = count($apptemplate);
		
		for($appi=0;$appi<$appcount;$appi++)
	    {
		   $pagenumber         = array();
		   $page_data          = array();
		   $sectionlist        = array();
		   
		   foreach ($apptemplate[$appi] as $pageno => $pages)
		   {
		      array_push($pagenumber,$pageno);
		   }
		   
		   $pagecount = count($pagenumber);
			  
		   for($i=1;$i<=$pagecount;$i++)
		   {
			 array_push($page_data,$apptemplate[$appi][$i]);
		   }
		  
		   $widget = array();
		   for($ii=0;$ii<$pagecount;$ii++)
           {
	         $pgno = $ii +1;
			 $previous_section = "";
			 foreach($page_data[$ii] as $section => $index_array)
	         {
			    if(!in_array($section,$sectionlist))
				{
			      array_push($sectionlist,$section);
				}
				
				if(!isset($widget[$section]))
				{
			      $widget[$section] = array();
				}
				
				unset($index_array['dont_use_this_name']);
				
				foreach($index_array as $index => $value)
				{
				    $ref_value = "page".$pgno."_".$section."_".$index;
					$exclude_list = array("retriever","mapper");
					if(!in_array($value['type'], $exclude_list))
					{          
					if(($value['type'] != "retriever") && ($value['type'] != "mapper"))
					{
				
				      if($value['key']=="TRUE")
					  {
						  if($section == $previous_section)
						  {
							$tem[$section][$index] = array(
							'element_def' =>$value,
							'element_ref' =>$ref_value);
							$widget[$section] = array_merge($widget[$section],$tem[$section]);
							unset($tem[$section]);
						  }
						  else
						  {
						   $widget[$section][$index] = array();
						   $widget[$section][$index]['element_def']  = $value;
						   $widget[$section][$index]['element_ref']  = $ref_value;
						  }
						   $previous_section = $section;
					  }
					}
				}
			 }
		  }
		  
		  $sectionlist = array_unique($sectionlist);
		  $hybrid_data['sectionlist'][$applist[$appi]['appid']] = $sectionlist;
		  $hybrid_data['appdetails'][$applist[$appi]['appid']]  = $widget;
		   
		}
			  
		
		
		$this->output->set_output(json_encode($hybrid_data));
	 }
	 }
	 
	 public function calculate_bmi()
	{
	  $ehr_list = $this->ion_auth->fetch_students_ehr_docs();
	  foreach($ehr_list as $index => $ehr_doc)
	  {
	     $bmi = "";
	     log_message('debug','$add_students_data_to_login_collection====1=='.print_r($ehr_doc,true));
	     foreach($ehr_doc['doc_data'] as $doc)
		 {
			$page1 = $doc['page1'];
			$page3 = $doc['page3'];
			
			$unique_id = $page1['Personal Information']['Hospital Unique ID'];
			
			$height = $page3['Physical Exam']['Height cms'];
			$weight = $page3['Physical Exam']['Weight kgs'];
			
			$height = (int) $height;
		    $weight = (int) $weight;
			
			log_message('debug','$add_students_data_to_login_collection====1=='.print_r($height,true));
			log_message('debug','$add_students_data_to_login_collection====1=='.print_r($weight,true));
					
		    if(($height > 0) && ($weight > 0))
		    {
			   $height = ($height/100);
			   $bmi    = ($weight / ($height * $height));
			   $bmi    = (int) $bmi;
			   $bmi    = round($bmi,1);
			   log_message('debug','$add_students_data_to_login_collection====1=='.print_r($bmi,true));
			}
		 }
		 
		 $this->ion_auth->calculate_bmi_model($unique_id,$bmi);
	   
	  }
	
	}
	
	public function calculate_age()
	{
		$ehr_list = $this->ion_auth->fetch_students_ehr_docs();
		foreach($ehr_list as $index => $ehr_doc)
	    {
	     $age = "";
	     
	     foreach($ehr_doc['doc_data'] as $doc)
		 {
			$page1 = $doc['page1'];
			
			$unique_id = $page1['Personal Information']['Hospital Unique ID'];
			$dob       = $page1['Personal Information']['Date of Birth'];
			$today     = date('Y-m-d');
			
			if($dob!='')
			{
				$from = date('Y-m-d',strtotime($dob));
				$from = new DateTime($from);
				
				$today = new DateTime($today);
				$age  = date_diff($from,$today);
				$age  = $age->y;
				log_message('debug','$add_students_data_to_login_collection====3=='.print_r($age,true));
			}
			
		 }
		 
		 $this->ion_auth->calculate_age_model($unique_id,$age);
		 
	   }
	}
}
