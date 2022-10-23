<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Temp_for_ppt extends CI_Controller {

    // --------------------------------------------------------------------

	/**
	 * __construct
	 *
	 * @author  Ben
	 *
	 * @return void 
	 */

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('config', TRUE);
		//$language = $this->session->userdata("language");
		$language = $this->input->cookie('language');
		$this->config->set_item('language', $language);
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->common_db = $this->config->item ( 'default' );
	}
	
	public function sample_ehr_case()
	{
		$this->mongo_db->switchDatabase ("healthcare");
		$query = $this->mongo_db->select ( array (
				'doc_data.widget_data',
				'doc_data.chart_data',
				'doc_data.external_attachments',
				'history' 
		) )->where ( "_id", new MongoID ( "585e5d07b31b6f220d7b23ca" ) )->get ( "healthcare2016226112942701" );
		if ($query) {
			$query_request = $this->mongo_db->orderBy(array("history.0.time"=> -1))->where ( "doc_data.widget_data.page1.Student Info.Unique ID", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->limit(45)->get ( "healthcare2016531124515424" );
			
			foreach($query_request as $docs => $doc_data){
				if(($doc_data["_id"]=="591ed403210552b23ce5dd45") || ($doc_data["_id"]=="591d6e14210552c63be5dd40") || ($doc_data["_id"]=="591d6e14210552c63be5dd40") || ($doc_data["_id"]=="591c0051210552016de5dd33") || ($doc_data["_id"]=="5920149a210552c26de5dd53") || ($doc_data["_id"]=="591ac269210552ae3be5dd3d") || ($doc_data["_id"]=="5919483921055290095cd1bb") || ($doc_data["_id"]=="5916ac87210552e3095cd1a8") || ($doc_data["_id"]=="5915840e210552e4095cd1ad") || ($doc_data["_id"]=="5910375a210552c86e5cd1ac") || ($doc_data["_id"]=="591033d52105523b6a5cd1a5") || ($doc_data["_id"]=="590d8084210552c2457b23d6") || ($doc_data["_id"]=="590c286221055222287b23d1") || ($doc_data["_id"]=="590aedcb210552121c7b23ce") || ($doc_data["_id"]=="59099cf92105528d687b23d5") || ($doc_data["_id"]=="590824ec21055226667b23c6") || ($doc_data["_id"]=="5906e402210552cb3db23c94") || ($doc_data["_id"]=="590451812105523522599537") || ($doc_data["_id"]=="5902fb8e210552aa0359953b") || ($doc_data["_id"]=="5901b77a210552a40359952b") || ($doc_data["_id"]=="59006648210552736559952b") || ($doc_data["_id"]=="58feefa8210552b41959953b") || ($doc_data["_id"]=="58fdc6f32105529414599539") || ($doc_data["_id"]=="58fb002a210552d27ca8781a") || ($doc_data["_id"]=="58fae768210552cf7ca87822") || ($doc_data["_id"]=="58f9a188210552216aa8781a") || ($doc_data["_id"]=="58f99d67210552916aa87814") || ($doc_data["_id"]=="58f461b72105522c7cd23576") || ($doc_data["_id"]=="58f1c337210552e647d23577") || ($doc_data["_id"]=="58f1bef12105529b3fd23579") || ($doc_data["_id"]=="58f1acdf210552713fd23580") || ($doc_data["_id"]=="58ef0ac4210552630ad2365b") || ($doc_data["_id"]=="58edfcc1210552e20bd23580") || ($doc_data["_id"]=="58ecaa5c210552d079d2357e") || ($doc_data["_id"]=="58eb231b2105523447d23576") || ($doc_data["_id"]=="58e9e5c8210552563ad23579") || ($doc_data["_id"]=="58ef4b19210552bb14d23576") || ($doc_data["_id"]=="58e9d269210552ca2d7b23e3")|| ($doc_data["_id"]=="58e8c5ba2105527d297b23cd")){
					unset($query_request[$docs]);
				}
			}
			
			if(count($query_request) > 0){
				foreach($query_request as $req_ind => $req){
					//echo print_r($req['doc_properties']['doc_id'],true);
					//echo "________";
					unset($query_request[$req_ind]['doc_data']["notes_data"]);
					$notes_data = $this->mongo_db->where ("req_doc_id", $req['doc_properties']['doc_id'])->get ( 'panacea_req_notes' );
					
					
					if(count($notes_data) > 0){
						$query_request[$req_ind]['doc_data']['notes_data'] = $notes_data[0]['notes_data'];
					}
				}//exit();
				
			}
			
			$query_notes = $this->mongo_db->orderBy(array('datetime' => 1))->where ( "uid", $query [0] ["doc_data"] ['widget_data'] ['page1'] ['Personal Information'] ['Hospital Unique ID'] )->get ( "panacea_ehr_notes" );
			
			$this->mongo_db->switchDatabase ( $this->common_db ['common_db'] );
			
			$school_details = $this->mongo_db->where ( "school_name", $query[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name'] )->get ( 'panacea_schools' );
			$query_hs = $this->mongo_db->where ( "school_code", $school_details[0]['school_code'] )->get ( 'panacea_health_supervisors' );
			
			$result ['screening'] = $query;
			$result ['request'] = $query_request;
			$result ['notes'] = $query_notes;
			$result ['hs'] = $query_hs[0];
		}
		
		
		
		$this->data['docs'] = $result['screening'];
		$this->data['docs_requests'] = $result['request'];
		$this->data['notes'] = $result['notes'];
		$this->data['hs'] = $result['hs'];
		 
		$this->data['docscount'] = count($this->data['docs']);
	
		$this->_render_page('temp_for_ppt/reports_display_ehr',$this->data);
	}
	 	
		
}

/* End of file signup.php */
/* Location: ./application/admin/controllers/patient_login.php */