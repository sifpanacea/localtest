<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

       
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->model('api_model');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('paas');
		$this->load->library('PaaS_common_lib');
		$this->config->load('email');
		$this->load->library('mongo_db');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->helper('language');
	}

    // --------------------------------------------------------------------

	/**
	 * Helper :
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function index()
    {
	    if (!$this->ion_auth->logged_in())
	    {
		   redirect(URC.'auth/login');
	    }

		$this->data['message'] = "This is where you can connect with our 3rd party intgration";
		//list the apps
		$this->data['apps'] = $this->ion_auth->apps();
		$this->data['numberofuser'] = count($this->ion_auth->users()->result());
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
			
		$this->_render_page('admin/admin_dash_api', $this->data);
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

	function api_users($usage = FALSE)
    {
	
	   if($usage == FALSE)
	   {
			$this->data['usage'] = FALSE;
			$this->data['api_users'] = $this->ion_auth->api_users();
	   }
	   else
	   {
			$this->data['usage'] = TRUE;
			$this->data['api_users'] = $this->ion_auth->api_users();
	   }
	   
	   //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
	   $this->_render_page('admin/admin_dash_api_users', $this->data);
	
     }

     // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function pre_activate_api()
     {

		$this->data['api_users'] = $this->ion_auth->api_users();
		
		//bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
		$this->_render_page('admin/admin_pre_activate_de_api_users', $this->data);
     }

     // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function activate_api($id, $code=false)
     {
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate_api($id, $code);
		}
		else
		{
			$activation = $this->ion_auth->activate_api($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->api_users();
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}

      }

      // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

      function deactivate_api($id = NULL)
      {
	       $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;
	
	       $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
	       $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
	
		   if ($this->form_validation->run() == FALSE)
		   {
				// insert csrf check
				$this->data['csrf']     = get_csrf_nonce();
				$this->data['customer'] = $this->ion_auth->api_users($id);
				
				//bubble count for events and feedbacks
				$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
				$this->data = array_merge($this->data,$data_bubble_count);
				
				$this->_render_page('auth/deactivate_api_user', $this->data);
		    }
	        else
	        {
				// do we really want to deactivate?
				if ($this->input->post('confirm') == 'yes')
				{
					// do we have a valid request?
					if (valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
					{
						
		 				show_error($this->lang->line('error_csrf'));
					}

					// unset csrf userdata
				    unset_csrf_userdata();  // Using PaaS helper function

					// do we have the right userlevel?
					{
						$this->ion_auth->deactivate_api($id);
					}
				}
				
                $this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->api_users();
	         }
        }
        
    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function new_api()
     {
	     $this->data['customerslist'] = $this->ion_auth->api_users(FALSE,'new');
		 
		 //bubble count for events and feedbacks
		$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
		$this->data = array_merge($this->data,$data_bubble_count);
		
         $this->_render_page('admin/admin_new_customer', $this->data);
     }


     // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function first_time_activate($id)
     {
		$customer = $this->ion_auth->api_users($id);

		$fromaddress = $this->config->item('smtp_user');
		$this->email->set_newline("\r\n");
		$this->email->set_crlf( "\r\n" );
		$this->email->from($fromaddress,'TLSTEC');
		$this->email->to($customer['email']);
		$this->email->subject("Your account has been activated");
		$message = " Your 3rd party TLSTEC PaaS account has been activated by our customer, please integrate the PAAS client to start the API services.";
		$this->email->message($message);
	    $send = $this->email->send();
		if($send)
		{
			$this->data['message'] ="Account activated. Confirmation email sent successfully";
		}
		else
		{
			$this->data['message'] ="Account activated. Confirmation email not sent";
		}

		$this->email->print_debugger();

		$activation = $this->ion_auth->activate_api($id);

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$this->api_users();
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}

     }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function api_others()
    {

	  if (!$this->ion_auth->logged_in())
	  {
		 //redirect them to the login page
		 redirect(URC.'auth/login');
	  }

	  $imageData = file_get_contents('php://input');

	  if (isset($imageData))
	  {

	    // Remove the headers (data:,) part.
	    // A real application should use them according to needs such as to check image type
	    $filteredData=substr($imageData, strpos($imageData, ',')+1);

	    $this->_page_data = $filteredData;
	    // Need to decode before saving since the data we received is already base64 encoded
	    $unencodedData=base64_decode($filteredData);
	  }
    }

    // --------------------------------------------------------------------

	/**
	 * Helper : Username of the logged in admin
	 *
	 * @author  Vikas
	 *
	 * 
	 */

    function adminusername()
    {
       redirect("dashboard/adminusername");
    }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function api_dropbox()
     {
	    if (!$this->ion_auth->logged_in())
	    {
		   //redirect them to the login page
		   redirect(URC.'auth/login');
	    }

	    {

		  $this->data['message'] = "This is where you can connect to dropdox";
		  //list the apps
		  $this->data['apps'] = $this->ion_auth->apps();
		  $this->data['numberofuser'] = count($this->ion_auth->users()->result());
		  require_once(URL.'api/dropbox-php-sdk-1.1.3/lib/Dropbox/autoload.php');
		  
		  //bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		  $this->_render_page('admin/admin_dash_api_dropbox', $this->data);
	    }
      }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function api_paas()
     {
	     if (!$this->ion_auth->logged_in())
	     {
		    redirect(URC.'auth/login');
	     }

	     {

		   $this->data['message'] = "This is where you can connect to 3rd party intgrations";
		   //list the apps
		   $this->data['apps'] = $this->ion_auth->apps();
		   $this->data['numberofuser'] = count($this->ion_auth->users()->result());
		   
		   //bubble count for events and feedbacks
			$data_bubble_count = $this->paas_common_lib->admin_bubble_count();
			$this->data = array_merge($this->data,$data_bubble_count);
		
		   $this->_render_page('admin/admin_dash_api_paas', $this->data);
	      }
     }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */

     function get_doc($app_id)
     {
	       $docs = Api_model::get_srch($app_id);
	       $this->output->set_output(json_encode($docs));
     }

     // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *
	 * @author  Vikas
	 *
	 * 
	 */
	
     function create_pdf($app_id, $doc_id)
     {
        $docs = Api_model::get_doc($app_id,$doc_id);
	    $app  = Api_model::get_app($app_id);
	    $workflow = $app['workflow'];
	
	    foreach($workflow as $stages)
	    {
		  $typeArr = $stages['Stage_Type']; 
		  if($typeArr[0] == 'api')
		  {
			$api = $stages;
			$comp_id = $stages['Comp_id'][0];
			$contact = $stages['Contact'][0];
		  }
	    }
	
	    $template = $app['app_template'];
	
	    $fullsec = array();
	    foreach($template as $pages)
	    {
		  foreach($pages as $secname => $sec)
		  {
			 if(in_array($secname, $api['View_Permissions']))
			 {
				$fullsec[] = $secname;
				foreach ($sec as $elename => $ele)
				{
					if($elename != 'dont_use_this_name')
					{
					  $fullsec[] = $elename;
					}
				}
			 }
		  }
	    }
	
	
	    $form = array();
	    foreach ($docs as $name => $vale_pair)
	    {
		  if(!in_array($name, $fullsec))
		  {
			 unset($docs[$name]);
		  }
	    }

		$table = "<table class='ftable' cellpadding='5' cellspacing='0'>";
		$td = "";
		foreach ($docs as $name => $value)
		{
			$td = $td . "<tr><td>".$name."</td><td>".$value."</td></tr>";
		}
		$table = $table . $td . "</table>";
	
		$this->load->library('Pdf');
	
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('My Title');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
	
		// ---------------------------------------------------------
		
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
	
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 14, '', true);
	
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
	
		// set text shadow effect
		$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
	
		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('times', 'BI', 12);
	
	
		// set some text to print
		$txt1 = <<<EOD
		TLSTEC Forms
EOD;
	
	
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
	

		$filename= "{$app_id}_{$doc_id}.pdf";
		$filelocation = APIFOLDER.'/'.$app_id.'/'.$doc_id;
		
		$fileNL = $filelocation."\\".$filename;

		if(is_dir($filelocation))
		{
			$pdf->Output($fileNL,'F');
		}
		else
		{
			$this->mkdir_ext($filelocation,DIR_WRITE_MODE,true);
			$pdf->Output($fileNL,'F');
		}
	
	
	
	
		$api_company = Api_model::get_api_comp($comp_id);
		
		$trans_id = TENANT.date("YmdHis").'_trans';
		
		$data_array = array(
			'transaction_id' => $trans_id,
			'stage_name' => 'api',
			'app_id' => $app_id,
			'company' => TENANT,
			'contact' => "",
			'pdf_name' => $filename,
			'pdf_path' => URLCustomer.$filelocation,
			'status' => 'new'
		);
	
	
		Api_model::api_insert($data_array,$api_company['collection']);
	
    }
    
}
