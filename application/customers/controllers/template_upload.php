<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Template_upload extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('ion_auth');
		$this->load->library('mongo_db');
		$this->load->library('PaaS_common_lib');
		$this->load->model('template_model');
    }

	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load upload page for predefined templates
     *
     *
     * @return array
     *  
     * @author Sekar 
     */

	 function index()
	 {
		 $this->load->view('upload_form', array('error' => ' ' ));
	 }
	
	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Load predefined templates page
     *
     * 
     * @author Sekar 
     */

	function predefined_templates()
	{
        //pagination
        $total_rows = $this->template_model->imagecount();

		$config = $this->paas_common_lib->set_paginate_options($total_rows,4);

		//Initialize the pagination class
		$this->pagination->initialize($config);

		//control of number page
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		

		//find all the categories with paginate and save it in array to past to the view
		$this->data['files']=$this->template_model->galary_update($config['per_page'], $page);

		//create paginate´s links
		$this->data['links'] = $this->pagination->create_links();

		//number page variable
		$this->data['page'] = $page;
		
		$this->data['message']='';
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_dash_predefined_templates',$this->data);
	}

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: Upload templates
     *
     * 
     * @author Sekar 
     */

	function do_upload()
	{
		$config['upload_path']   = TEMPLATES;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	     = '100';
		$config['max_width']     = '1024';
		$config['max_height']    = '768';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('upload_success', $data);
		}
	}

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: Get the image thumbnails
     *
     * 
     * @author Sekar 
     */

	function image_thumb( $filename,$targetFile) 
	{
       // Get the CodeIgniter super object
	   $CI =& get_instance();

       // Path to image thumbnail
       $image_thumb ='thumb_'.$filename;

       if ( !file_exists( $image_thumb ) ) 
       {
          // LOAD LIBRARY
          $CI->load->library( 'image_lib' );

	      // CONFIGURE IMAGE LIBRARY
	      $config['image_library']    = 'gd2';
	      $config['source_image']     = $targetFile;
	      $config['new_image']        = $image_thumb;
	      $config['maintain_ratio']   = false;
	      $config['height']           = 100;
	      $config['width']            = 160;
	      $CI->image_lib->initialize( $config );
	      $CI->image_lib->resize();
	      $CI->image_lib->clear();
       }
	}

    // ---------------------------------------------------------------------------------------

     /**
     * Helper: Upload templates
     *
     *  
     * @author Sekar 
     */

	function upload()
	{
		log_message('debug','11111111111111111111111111111'.print_r($_FILES,true));
		$ds = DIRECTORY_SEPARATOR;  
 		$targetPath = TEMPLATES; 
		if (!empty($_FILES)) 
		{	$check_status=0;
			$file_title=$_POST['title'];
			$file_description=$_POST['description'];
			$tempFile = $_FILES['file']['tmp_name'];         
			
			$targetFile =  $targetPath.basename($_FILES['file']['name']);
			log_message('debug','tttttttttttttttttttttt'.print_r($targetFile,true));
			$thumb_path =  $targetPath.'thumb_'.basename($_FILES['file']['name']);
			$filename=$_FILES['file']['name'];
		    $check_status=move_uploaded_file($tempFile,$targetFile);
			log_message('debug','cccccccccccccccccccccccccccccccccccccc'.print_r($check_status,true));
			if($check_status==1)
			{
				$this->image_thumb($filename,$targetFile);
				$this->load->model('template_model');
				$this->template_model->saveimage_url($filename,$targetFile,$thumb_path,$file_title,$file_description);
			}
		}
	}

	// ---------------------------------------------------------------------------------------

     /**
     * Helper: Deletes the template
     *
     *  
     * @author Sekar 
     */

	function delete_image()
	{
		$image_id = $this->input->post('id');
		$this->template_model->deleteimage_url($image_id);	
	}

}
