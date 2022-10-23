<?php
class Api_model extends CI_Model{

 
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		// Load MongoDB library,
		$this->load->library('mongo_db');
		$this->load->config('email');
		$this->load->config('ion_auth', TRUE);
		//$this->config->load('mongodb');
    }
	
	function get_srch($app_id)
	{
		$query = $this->mongo_db->get($app_id);
	
		$result = json_decode(json_encode($query), FALSE);
	
		return ($result);
	}
	
	function get_doc($app_id,$doc_id)
	{
		$val = intval($doc_id);
		$query = $this->mongo_db->where(array('doc_id' => $val))->get($app_id);

		return ($query[0]);
	}
	
	function get_app($app_id)
	{
		
		$query = $this->mongo_db->where(array('_id' => $app_id))->get($this->collections['records']);
	
		return ($query[0]);
	}
	
	function get_api_comp($id)
	{
		log_message('debug','wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww'.print_r($id,true));
		 $this->mongo_db->switchDatabase(COMMON_DB);
		
		$query = $this->mongo_db->where(array('_id' => new MongoId($id)))->get('api_details');
		log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		
		$this->mongo_db->switchDatabase(DNS_DB);
	
		return ($query[0]);
	}
	
	function api_insert($data,$coll)
	{
		
		$this->mongo_db->switchDatabase(COMMON_DB);
		$query = $this->mongo_db->insert($coll, $data);
		log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		log_message('debug','_______________configggggggggggggggggggggggggggggggggggggvalueeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'.print_r($this->configvalue['dsn'],true));
		$this->mongo_db->switchDatabase(DNS_DB);
		return ($query);
	}
	
	//this fuction is created to call by app models during user_doc notifications
	function create_pdf_mod($app_id, $doc_id){
	
	
		log_message('debug','uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'.$app_id.$doc_id);
	
		$val = intval($doc_id);
		$query = $this->mongo_db->where(array('doc_id' => $val))->get($app_id);
		
		$docs = $query[0];
	
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($docs,true));
		
		$query = $this->mongo_db->where(array('_id' => $app_id))->get($this->collections['records']);
	
		$app = $query[0];
	
		log_message('debug','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'.print_r($app,true));
	
		$workflow = $app['workflow'];
	
	   log_message('debug','workflowwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww'.print_r($workflow,true));

		foreach($workflow as $stages){
			$typeArr = $stages['Stage_Type'];
			log_message('debug','stageeeee-----------------------------------------------typeeeeeee------------apiiiiiiiiiiiiiii'.print_r($typeArr,true));
			if($typeArr == 'api'){
				$api = $stages;
				$comp_id = $stages['Comp_id'][0];
				$contact = $stages['Contact'][0];
				log_message('debug','apppppppppppppppppppppppppppppppppppppppppppiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii'.print_r($api,true));
	
			}
		}
	
		$template = $app['app_template'];
	
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($template,true));
	
		$fullsec = array();
		foreach($template as $pages){
			foreach($pages as $secname => $sec){
				//log_message('debug','sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss'.print_r($secname,true));
				//log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($api['View_Permissions'],true));
					
				if(in_array($secname, $api['View_Permissions'])){
					$fullsec[] = $secname;
					foreach ($sec as $elename => $ele){
						if($elename != 'dont_use_this_name'){
							$fullsec[] = $elename;
						}
					}
				}
			}
		}
	
	
		log_message('debug','ssssssssssseccccccccccccccccccccccccccccccccccccccccccccccccccccccc'.print_r($fullsec,true));
		$form = array();
		foreach ($docs as $name => $vale_pair){
			//log_message('debug','vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv'.print_r($vale_pair,true));
			if(!in_array($name, $fullsec)){
				unset($docs[$name]);
			}
		}
		log_message('debug','oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo'.print_r($docs,true));
		$table = "<table class='ftable' cellpadding='5' cellspacing='0'>";
		$td = "";
		foreach ($docs as $name => $value){
			$td = $td . "<tr><td>".$name."</td><td>".$value."</td></tr>";
		}
		$table = $table . $td . "</table>";
	
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($table,true));
	
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
	
		//$pdf->Output('My-File-Name.pdf', 'F');
	
	
		$filename= "{$app_id}_{$doc_id}.pdf";
		$filelocation = APIFOLDER.'/'.$app_id.'/'.$doc_id;
	
		$fileNL = $filelocation."\\".$filename;
	
		if(is_dir($filelocation)){
			$pdf->Output($fileNL,'F');
		}else{
			$this->mkdir_ext($filelocation,DIR_WRITE_MODE,true);
			
			$pdf->Output($fileNL,'F');
		}
	
		//$pdf->Output($filename,'D');
		$this->mongo_db->switchDatabase(COMMON_DB);
		$query = $this->mongo_db->where(array('_id' => new MongoId($comp_id)))->get('api_details');
		log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		$this->mongo_db->switchDatabase(DNS_DB);
		

		$api_company = $query[0];
		log_message('debug','aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'.print_r($api_company,true));
	
		$trans_id = TENANT.date("YmdHis").'_trans';//rand(99999999, 999999999);
	
		$data_array = array(
				'app_name' => $app['app_name'],
				'transaction_id' => $trans_id,
				'stage_name' => 'api',
				'app_id' => $app_id,
				'company' => TENANT,
				'contact' => $contact,
				'pdf_name' => $filename,
				'pdf_path' => URLCustomer.$filelocation,
				'status' => 'new'
		);
	
		log_message('debug','tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt'.print_r($data_array,true));
	    $this->mongo_db->switchDatabase(COMMON_DB);
		$query = $this->mongo_db->insert($api_company['collection'], $data_array);
		log_message('debug','qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'.print_r($query,true));
		$this->mongo_db->switchDatabase(DNS_DB);
	
	}
    
}