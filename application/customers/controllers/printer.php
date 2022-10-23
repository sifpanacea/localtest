<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Printer extends CI_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->helper('form','url');
		$this->load->helper('language');
		$this->load->library('session');
		$this->load->model('printer_model');
		
		$this->config->load('config', TRUE);
		
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
	}
	
	// -------------------------------------------------------------------------------

	/**
	 * Helper: pass application template,document,print template to print server 
	 *
	 * @param   string $id            Document id
	 * @param   string $app_id        Application id
	 * @param   string $page_         Page number
	 * @param   string $offset_       Offset
	 * @param   string $image_type    Type of the image ( with widgets or without widgets )
	 * @param   string $initial_stage Initial stage or not 
	 *  
	 * @author Vikas 
	 */

	function index($id,$app_id,$page_,$offset_,$image_type,$initial_stage)
	{
		$image_data_array = array();
		$event_or_feedback = false;
		$widget_labels     = array();
		$widget_name       = array();
		$photo_ele         = false;
		$mapper_document   = array();
		
	    $db_data = $this->printer_model->fetch_app_and_doc($id,$app_id,$initial_stage);
	    $template     = $db_data['application']['print_template'];
		$app_template = $db_data['application']['app_template'];

		foreach($app_template as $pageno => $pages)
		{
			log_message('debug','1111111111111111111111111111111111111111111111111111...........'.$pageno);
		       foreach($pages as $section => $sec)
				{
					log_message('debug','2222222222222222222222222222222222222222222222222222222..............'.$section);
					log_message('debug','2222222222222222222222222222222222222222222222222222222..............'.print_r($sec,true));
					unset($sec['dont_use_this_name']);
					foreach($sec as $elename => $element)
					{
					   $photo_ele = true;
					   $widget_label = 'page'.$pageno.'.'.$section.'.'.$elename;
					   array_push($widget_labels,$widget_label);
					   array_push($widget_name, $elename);
					   
					   if($initial_stage == "false")
					{
						log_message('debug','333333333333333333333333333333333333333333333333333333333333333333333333'.print_r($element['type'],true));
						if(($element['type']=="retriever"))
						{
						  $query_param     = $element['field_ref'];
						  $collection_name = $element['coll_ref'];
						  $to_be_retrieved = $element['retrieve_list'];
						  
						  $query_obj = explode("_",$query_param);
						  
						  $query_param = str_replace("_",".",$query_param);
						  $page = 'page'.$pageno;
						  
						  $query_value = $db_data['document']['doc_data']['widget_data'][$page][$section][$elename];
						  
						  //$query_value = "";
						  
						  $retrieval_list = array();
						  
						  foreach($to_be_retrieved as $index => $value)
						  {
							$value = str_replace("_",".",$value);
							$value = "doc_data.widget_data.".$value;
							array_push($retrieval_list,$value);
						  }
						  
						  log_message('debug','$retrieval_list=====90'.print_r($retrieval_list,true));
						  
						  //fetch document 
						  $mapper_document = $this->printer_model->fetch_retriever_data_model($query_param,$query_value,$retrieval_list,$collection_name);
						  
						  log_message('debug','=======================================955555555555555555'.print_r($mapper_document,true));
						}
					}
					}
				}
			}
		
		if($photo_ele)
		{
		    $photo_data = $this->printer_model->fetch_photo_element_data($id,$app_id,$widget_labels);
			
			foreach($photo_data as $index)
			{
				$photo_data = $this->printer_model->fetch_photo_element_data($id,$app_id,$widget_labels);
				
				foreach($photo_data as $index)
				{
				   foreach($index as $ele_name=> $file_data)
				   {
					   if(isset($file_data['file_path']) && !empty($file_data['file_path'])){
					   $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($file_data['file_path'], 2), 'rb' );
					   $data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($file_data['file_path'], 2)));
					   fclose( $fp );
					   $data_64 = base64_encode($data);
					   $photo[$ele_name] = array(
								'image_data'=>$data_64
								);
					}
				  }				
				 }
			 }
		
		}
		
        foreach($template as $pages => $page)
	    {	
            if ($page['file_path'] != "") 
			{
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].$page['file_path'], 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].$page['file_path']));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$pg = $pages-1;
				$image = array(
						'image_data'=>$data_64
						);

	         array_push($image_data_array,$image);
			}
			else
			{
				
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg', 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg'));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$pg = $pages-1;
				$image = array(
						'image_data'=>$data_64
						);

	         array_push($image_data_array,$image);
			}
		}
		
	   // $db_data['images']['image']     = $image_data_array;
	    $db_data['page']                = intval($page_);
        $db_data['itemPerPage']         = intval($offset_);
		if(isset($photo))
		{
		  $db_data['photo']               = $photo;
		}  
      
        if($image_type=="true")
        {
            $db_data['STROKE_WITH_WIDGETS'] = true;
        }
        else
        {
           $db_data['STROKE_WITH_WIDGETS'] = false;
        }
        if($initial_stage=="true")
       {
          $db_data['INITIAL_STAGE'] = true;
		  
       }
       else
       {
          $db_data['INITIAL_STAGE'] = false;
		  $db_data['mapper_doc']    = $mapper_document;
       }
       $db_data['EVENT_OR_FEEDBACK'] = $event_or_feedback;
       
        $db_data['permission']          = [];
        $data = json_encode($db_data);

	    /* $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg', 'rb' );
	    $data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg'));
	    fclose( $fp );
	    $data_64 = base64_encode($data);
	    $db_data['logo'] = $data_64; */
	    
	    if($db_data['application']['use_profile_header'] == 'yes'){
	    	if($initial_stage == "false"){
		    	$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['profile_settings']['logo']['file_path'], 2), 'rb' );
		    	$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['profile_settings']['logo']['file_path'], 2)));
		    	fclose( $fp );
		    	$data_64 = base64_encode($data);
		    	$db_data['logo'] = $data_64;
	    	}
	    }
		
		// CHART TEMPLATE
if(isset($db_data['document']))
		{
		if(is_array($db_data['document']['doc_data']['chart_data']) && !empty($db_data['document']['doc_data']['chart_data']))
		{
			if(is_array($db_data['document']['doc_data']['chart_data']['chart_image']) && !empty($db_data['document']['doc_data']['chart_data']['chart_image']))
			{
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['chart_data']['chart_image']['file_path'], 2), 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['chart_data']['chart_image']['file_path'], 2)));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$db_data['document']['doc_data']['chart_data']['chart_image'] = $data_64;
			}
	    }

	    
	    // TEMPLATE IMAGE
	    if(isset($db_data['document']['doc_data']['template_attachments']))
	    {
		    if(is_array($db_data['document']['doc_data']['template_attachments']) && !empty($db_data['document']['doc_data']['template_attachments']))
		    {
		    	
		    	if(is_array($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']) && !empty($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']))
		    	{
		    		$image_data_array = array();
		    		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']['file_path'], 2), 'rb' );
		    		$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']['file_path'], 2)));
		    		fclose( $fp );
		    		$data_64 = base64_encode($data);
		    		
		    		$image = array(
		    				'image_data'=>$data_64
		    		);
		    		
		    		array_push($image_data_array,$image);
		    	}
		    }
	    }
}
	    
	    $db_data['images']['image']     = $image_data_array;

        $data = json_encode($db_data);
		
		//log_message('debug','CURL_DATA=====PRINTER=====183'.print_r($data,true));
        
		//http----------------------------------------------------------------
		// $ch = curl_init("http://192.168.0.202:8005");
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//https---------------------------------------------------------------
		$ch = curl_init("https://52.221.254.36");
		curl_setopt($ch, CURLOPT_PORT , 8005);
		curl_setopt($ch, CURLOPT_VERBOSE, 0); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_c.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_k.pem');
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/ca.pem');
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
// 		log_message('debug','CURL_RESPONSE=====PRINTER=====207'.print_r($response,true));
// 		log_message('debug','CURL_ERROR=====PRINTER=====208'.print_r(curl_error($ch),true));
		
        curl_close($ch);
        $this->output->set_output($response);

	}
	
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Load event create page
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function user_form_printing_event($user_id,$event_id)
	{
		$user_id = base64_decode($user_id);

		$page_ = 1;
		$offset_ = 1;
		$image_type=true;
		$initial_stage=false;
		$event_or_feedback = true;
		
		//$this->check_for_admin();
		//$this->check_for_plan('user_form_printing');
		 
		$usercollection = $user_id.'_calendar_events';
		$event_print_details = $this->printer_model->get_user_calendar_event($usercollection,$event_id);
		
		$usercollection = $user_id.'_event_docs';
		$event_doc = $this->printer_model->fetch_doc_event($usercollection,$event_id);
		
		 
		$u = $this->session->userdata('customer');
		$usersession='event_requests';
		$event = $this->printer_model->get_event_details($event_print_details[0]['event_id'],$u['email']);
		log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($event,true));
		
		$image_data_array = array();
		$db_data['application']['_id'] = $event[0]['_id'];
		$db_data['application']['app_name'] = $event[0]['event_name'];
		$db_data['application']['app_template'] = $event[0]['event_template'];
		//$db_data['application']['print_template'] = $event[0]['print_template'];
		$db_data['widget_def'] = $this->printer_model->fetch_widget_def();
		
		$db_data['document'] = $event_doc;
		
// 		$template = $db_data['application']['print_template'];
		
// 		foreach($template as $pages => $page)
// 		{
// 			if ($page['file_path'] != "")
// 			{
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].$page['file_path'], 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].$page['file_path']));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
		
// 				array_push($image_data_array,$image);
// 			}
// 			else
// 			{
		
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg', 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg'));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
		
// 				array_push($image_data_array,$image);
// 			}
// 		}
		
		$db_data['images']['image']     = $image_data_array;
		$db_data['page']                = intval($page_);
		$db_data['itemPerPage']         = intval($offset_);
		
		if($image_type=="true")
		{
			$db_data['STROKE_WITH_WIDGETS'] = true;
		}
		else
		{
			$db_data['STROKE_WITH_WIDGETS'] = false;
		}
		if($initial_stage=="true")
		{
			$db_data['INITIAL_STAGE'] = true;
		}
		else
		{
			$db_data['INITIAL_STAGE'] = false;
		}
		$db_data['EVENT_OR_FEEDBACK'] = $event_or_feedback;
		
		$db_data['permission']          = [];
		$data = json_encode($db_data);
		
		/* $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg', 'rb' );
		$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg'));
		fclose( $fp );
		$data_64 = base64_encode($data);
		$db_data['logo'] = $data_64; */
		
		$data = json_encode($db_data);
		//log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r($db_data,true));
		//http----------------------------------------------------------------
		// $ch = curl_init("http://192.168.0.135:8005");
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//https---------------------------------------------------------------
		$ch = curl_init("https://52.221.254.36");
		curl_setopt($ch, CURLOPT_PORT , 8005);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_c.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_k.pem');
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/ca.pem');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r(curl_error($ch),true));
		curl_close($ch);
		
		
		$images = json_decode($response,false);
		
		$data = '<html><body>';
		
		foreach ($images as $imag_str)
		{
			foreach ($imag_str as $img)
			{
				$data=$data.'<img src="';
				$data=$data.$img->print_image;
				$data=$data.'"></img>';
			}
		}
		$data=$data.'</body></html>';
		//log_message('debug','imgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg'.print_r($response,true));
		//$this->output->set_output($data);
		$this->output->set_output($response);
		//redirect('printer/print_forsub_admin');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Load event create page
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function user_form_printing_feedback($user_id,$feedback_id)
	{
		$user_id = base64_decode($user_id);
	
		$page_ = 1;
		$offset_ = 1;
		$image_type=true;
		$initial_stage=false;
		$event_or_feedback = true;
	
		//$this->check_for_admin();
		//$this->check_for_plan('user_form_printing');
		log_message('debug','sendddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.$user_id.$feedback_id);
			
		$usercollection = $user_id.'_feedbacks';
		$feedback_print_details = $this->printer_model->get_user_feedback($usercollection,$feedback_id);
		log_message('debug','fffffffffffffffffffffffffffffffffffffffffffffffffffff'.print_r($feedback_print_details,true));
		
		$usercollection = $user_id.'_feedback_docs';
		$feedback_doc = $this->printer_model->fetch_doc_feedback($usercollection,$feedback_id);
		//log_message('debug','eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($feedback_doc,true));
			
		$u = $this->session->userdata('customer');
		//$username = str_replace("@","#",$u['email']);
		//$usersession=$username.'_created_feedbacks';
		$feedback = $this->printer_model->get_feedback_details($feedback_print_details[0]['feedback_id'],$u['email']);
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($feedback,true));
	
		$image_data_array = array();
		$db_data['application']['_id'] = $feedback[0]['_id'];
		$db_data['application']['app_name'] = $feedback[0]['feedback_name'];
		$db_data['application']['app_template'] = $feedback[0]['feedback_template'];
		//$db_data['application']['print_template'] = $feedback[0]['print_template'];
		$db_data['widget_def'] = $this->printer_model->fetch_widget_def();
	
		$db_data['document'] = $feedback_doc;
	
// 		$template = $db_data['application']['print_template'];
	
// 		foreach($template as $pages => $page)
// 		{
// 			if ($page['file_path'] != "")
// 			{
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].$page['file_path'], 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].$page['file_path']));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
	
// 				array_push($image_data_array,$image);
// 			}
// 			else
// 			{
	
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg', 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg'));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
	
// 				array_push($image_data_array,$image);
// 			}
// 		}
	
		$db_data['images']['image']     = $image_data_array;
		$db_data['page']                = intval($page_);
		$db_data['itemPerPage']         = intval($offset_);
	
		if($image_type=="true")
		{
			$db_data['STROKE_WITH_WIDGETS'] = true;
		}
		else
		{
			$db_data['STROKE_WITH_WIDGETS'] = false;
		}
		if($initial_stage=="true")
		{
			$db_data['INITIAL_STAGE'] = true;
		}
		else
		{
			$db_data['INITIAL_STAGE'] = false;
		}
		$db_data['EVENT_OR_FEEDBACK'] = $event_or_feedback;
		$db_data['permission']          = [];
		$data = json_encode($db_data);
	
		/* $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg', 'rb' );
		$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg'));
		fclose( $fp );
		$data_64 = base64_encode($data);
		$db_data['logo'] = $data_64; */
	
		$data = json_encode($db_data);
		//log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r($db_data,true));
		//http----------------------------------------------------------------
		// $ch = curl_init("http://192.168.0.135:8005");
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		//https---------------------------------------------------------------
		$ch = curl_init("https://52.221.254.36");
		curl_setopt($ch, CURLOPT_PORT , 8005);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_c.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_k.pem');
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/ca.pem');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r(curl_error($ch),true));
		curl_close($ch);
	
	
		$images = json_decode($response,false);
	
		$data = '<html><body>';
	
		foreach ($images as $imag_str)
		{
			foreach ($imag_str as $img)
			{
				$data=$data.'<img src="';
				$data=$data.$img->print_image;
				$data=$data.'"></img>';
			}
		}
		$data=$data.'</body></html>';
		//log_message('debug','imgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg'.print_r($response,true));
		//$this->output->set_output($data);
		$this->output->set_output($response);
		//redirect('printer/print_forsub_admin');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper : Load event create page
	 *
	 * @author  Vikas
	 *
	 *
	 */
	
	function view_form_sub_admin($task,$_id)
	{	log_message('debug','iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiytfghvhgjv'.$task.$_id);
		$page_ = 1;
		$offset_ = 1;
		$image_type=true;
		$initial_stage=true;
		$event_or_feedback = true;
	
		//$this->check_for_admin();
		//$this->check_for_plan('user_form_printing');
	
		if($task == 'events'){
			$u = $this->session->userdata('customer');
			//$username = str_replace("@","#",$u['email']);
			//$usersession='event_requests';
			$event = $this->printer_model->get_event_details($_id,$u['email']);
			
			$db_data['application']['app_name'] = $event[0]['event_name'];
			$db_data['application']['app_template'] = $event[0]['event_template'];
		}else{
			$u = $this->session->userdata('customer');
			//$username = str_replace("@","#",$u['email']);
			//$usersession='feedback_requests';
			$event = $this->printer_model->get_feedback_details($_id,$u['email']);
			
			$db_data['application']['app_name'] = $event[0]['feedback_name'];
			$db_data['application']['app_template'] = $event[0]['feedback_template'];
		}	
		
	
	
		$image_data_array = array();
		$db_data['application']['_id'] = $event[0]['_id'];

		//$db_data['application']['print_template'] = $event[0]['print_template'];
		$db_data['widget_def'] = $this->printer_model->fetch_widget_def();
	
// 		$template = $db_data['application']['print_template'];
	
// 		foreach($template as $pages => $page)
// 		{
// 			if ($page['file_path'] != "")
// 			{
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].$page['file_path'], 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].$page['file_path']));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
	
// 				array_push($image_data_array,$image);
// 			}
// 			else
// 			{
	
// 				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg', 'rb' );
// 				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg'));
// 				fclose( $fp );
// 				$data_64 = base64_encode($data);
// 				$pg = $pages-1;
// 				$image = array(
// 						'image_data'=>$data_64
// 				);
	
// 				array_push($image_data_array,$image);
// 			}
// 		}
	
		$db_data['images']['image']     = $image_data_array;
		$db_data['page']                = intval($page_);
		$db_data['itemPerPage']         = intval($offset_);
	
		if($image_type=="true")
		{
			$db_data['STROKE_WITH_WIDGETS'] = true;
		}
		else
		{
			$db_data['STROKE_WITH_WIDGETS'] = false;
		}
		if($initial_stage=="true")
		{
			$db_data['INITIAL_STAGE'] = true;
		}
		else
		{
			$db_data['INITIAL_STAGE'] = false;
		}
		$db_data['EVENT_OR_FEEDBACK'] = $event_or_feedback;
		
		$db_data['permission']          = [];
		$data = json_encode($db_data);
	
		/* $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg', 'rb' );
		$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg'));
		fclose( $fp ); 
		$data_64 = base64_encode($data);
		$db_data['logo'] = $data_64;*/
	
		$data = json_encode($db_data);
		//log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r($db_data,true));
		//http----------------------------------------------------------------
		// $ch = curl_init("http://192.168.0.135:8005");
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		//https---------------------------------------------------------------
		$ch = curl_init("https://52.221.254.36");
		curl_setopt($ch, CURLOPT_PORT , 8005);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_c.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_k.pem');
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/ca.pem');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//log_message('debug','curllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll'.print_r(curl_error($ch),true));
		curl_close($ch);
	
	
		$images = json_decode($response,false);
	
		$data = '<html><body>';
	
		foreach ($images as $imag_str)
		{
			foreach ($imag_str as $img)
			{
				$data=$data.'<img src="';
				$data=$data.$img->print_image;
				$data=$data.'"></img>';
			}
		}
		$data=$data.'</body></html>';
		//log_message('debug','imgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg'.print_r($response,true));
		//$this->output->set_output($data);
		$this->output->set_output($response);
	
		//redirect('printer/print_forsub_admin');
	}
	
	// -------------------------------------------------------------------------------
	
	/**
	 * Helper: pass application template,document,print template to print server
	 *
	 * @param   string $id            Document id
	 * @param   string $app_id        Application id
	 * @param   string $page_         Page number
	 * @param   string $offset_       Offset
	 * @param   string $image_type    Type of the image ( with widgets or without widgets )
	 * @param   string $initial_stage Initial stage or not
	 *
	 * @author Vikas
	 */
	
	function view_patient_app($id,$app_id,$page_ = 1,$offset_ = 200000,$image_type = "true",$initial_stage = "false")
	{
		log_message('debug','dddddddddddddddddddddddddddddddddddddddddddddd'.print_r($id,true));
		log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($app_id,true));
		$image_data_array = array();
		$event_or_feedback = false;
		$widget_labels     = array();
		$widget_name       = array();
		$photo_ele         = false;
	
		$db_data = $this->printer_model->fetch_app_and_doc($id,$app_id,$initial_stage);
		$template     = $db_data['application']['print_template'];
		$app_template = $db_data['application']['app_template'];
	
		foreach($app_template as $pageno => $pages)
		{
			foreach($pages as $section => $sec)
			{
				unset($sec['dont_use_this_name']);
				foreach($sec as $elename => $element)
				{
					if(($element['type']=="photo"))
					{
						$photo_ele = true;
						$widget_label = 'page'.$pageno.'.'.$section.'.'.$elename;
						array_push($widget_labels,$widget_label);
						array_push($widget_name, $elename);
					}
				}
			}
		}
	
		if($photo_ele)
		{
			$photo_data = $this->printer_model->fetch_photo_element_data($id,$app_id,$widget_labels);
				
			foreach($photo_data as $index)
			{
				$photo_data = $this->printer_model->fetch_photo_element_data($id,$app_id,$widget_labels);
	
				foreach($photo_data as $index)
				{
					foreach($index as $ele_name=> $file_data)
					{
						if(isset($file_data['file_path']) && !empty($file_data['file_path'])){
							$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($file_data['file_path'], 2), 'rb' );
							$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($file_data['file_path'], 2)));
							fclose( $fp );
							$data_64 = base64_encode($data);
							$photo[$ele_name] = array(
									'image_data'=>$data_64
							);
						}
					}
				}
			}
	
		}
	
		foreach($template as $pages => $page)
		{
			if ($page['file_path'] != "")
			{
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].$page['file_path'], 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].$page['file_path']));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$pg = $pages-1;
				$image = array(
						'image_data'=>$data_64
				);
	
				array_push($image_data_array,$image);
			}
			else
			{
	
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg', 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/blank_page.jpg'));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$pg = $pages-1;
				$image = array(
						'image_data'=>$data_64
				);
	
				array_push($image_data_array,$image);
			}
		}

		$db_data['page']                = intval($page_);
		$db_data['itemPerPage']         = intval($offset_);
		if(isset($photo))
		{
			$db_data['photo']               = $photo;
		}
	
		if($image_type=="true")
		{
			$db_data['STROKE_WITH_WIDGETS'] = true;
		}
		else
		{
			$db_data['STROKE_WITH_WIDGETS'] = false;
		}
		if($initial_stage=="true")
		{
			$db_data['INITIAL_STAGE'] = true;
		}
		else
		{
			$db_data['INITIAL_STAGE'] = false;
		}
		$db_data['EVENT_OR_FEEDBACK'] = $event_or_feedback;
		 
		$db_data['permission']          = [];
		$data = json_encode($db_data);
	
		/* $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg', 'rb' );
		 $data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/apollohospitals/aapple.jpg'));
		fclose( $fp );
		$data_64 = base64_encode($data);
		$db_data['logo'] = $data_64; */
		 
		if($db_data['application']['use_profile_header'] == 'yes'){
			if($initial_stage == "false"){
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['profile_settings']['logo']['file_path'], 2), 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['profile_settings']['logo']['file_path'], 2)));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$db_data['logo'] = $data_64;
			}
		}
	
		// CHART TEMPLATE
		if(is_array($db_data['document']['doc_data']['chart_data']) && !empty($db_data['document']['doc_data']['chart_data']))
		{
			if(is_array($db_data['document']['doc_data']['chart_data']['chart_image']) && !empty($db_data['document']['doc_data']['chart_data']['chart_image']))
			{
				$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['chart_data']['chart_image']['file_path'], 2), 'rb' );
				$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['chart_data']['chart_image']['file_path'], 2)));
				fclose( $fp );
				$data_64 = base64_encode($data);
				$db_data['document']['doc_data']['chart_data']['chart_image'] = $data_64;
			}
		}
		
		if(isset($db_data['document']['doc_data']['template_attachments']))
		    {
			    if(is_array($db_data['document']['doc_data']['template_attachments']) && !empty($db_data['document']['doc_data']['template_attachments']))
			    {
			    	
			    	if(is_array($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']) && !empty($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']))
			    	{
			    		$image_data_array = array();
			    		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']['file_path'], 2), 'rb' );
			    		$data = fread($fp,filesize($_SERVER['DOCUMENT_ROOT'].'/PaaS/'.TENANT.'/'.substr($db_data['document']['doc_data']['template_attachments']['TEMPLATE_IMAGE']['file_path'], 2)));
			    		fclose( $fp );
			    		$data_64 = base64_encode($data);
			    		
			    		$image = array(
			    				'image_data'=>$data_64
			    		);
			    		
			    		array_push($image_data_array,$image);
			    	}
			    }
		    }
	    
	    $db_data['images']['image']     = $image_data_array;
	
		$data = json_encode($db_data);
	
		//log_message('debug','CURL_DATA=====PRINTER=====183'.print_r($data,true));
	
		//http----------------------------------------------------------------
		// $ch = curl_init("http://192.168.0.202:8005");
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		//https---------------------------------------------------------------
		$ch = curl_init("https://52.221.254.36");
		curl_setopt($ch, CURLOPT_PORT , 8005);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_c.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/server_k.pem');
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/PaaS/tlstec_data/ssl/ca.pem');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		log_message('debug','CURL_RESPONSE=====PRINTER=====207'.print_r($response,true));
		//log_message('debug','CURL_ERROR=====PRINTER=====208'.print_r(curl_error($ch),true));
	
		curl_close($ch);
		$this->output->set_output($response);
	
	}
	
 }
