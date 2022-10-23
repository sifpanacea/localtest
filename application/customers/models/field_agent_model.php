<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Field_agent_model extends CI_Model 
{
	public $controller_name;
	public $appName;

    function __construct() 
	{
        parent::__construct();
		
		$this->load->library('mongo_db');
		$this->load->library('session');
		
        // Initialize MongoDB database names
        $this->collections = $this->config->item('collections', 'ion_auth');
	    $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
 

    }
	
	private function init($data)
	{
		$this->controller_name			=	$data['controller_name'];
		$this->appName					=	$data['appName'];
	}
	
	function get_dup_docs($coll){
		$doc_matchs = [];
		$matched_doc_ids = [];
		$doc_count = $this->mongo_db->count($coll);
		
		for($doc_offset = 0 ; $doc_offset < $doc_count ; $doc_offset++){
			
			$doc = $this->get_a_doc($coll,$doc_offset);
			$doc_id = (string)$doc['_id'];
			
			if ($doc) {
				$doc_sech = $this->mongo_db->select(array("_id"))->where("doc_data.widget_data.page2.Personal Information.AD No" , $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'])->get($coll);
				$inner_doc_count = count($doc_sech);
				log_message('debug','111111111111111111111111111111111111'.print_r($inner_doc_count,true));
				if($inner_doc_count >1){
					log_message('debug','2222222222222222222222222222222222'.print_r($doc_id,true));
					if (!in_array($doc_id, $matched_doc_ids)) {
						log_message('debug','33333333333333333333333333333333333333333'.print_r($doc_matchs,true));
						for ($doc_pointer = 0 ; $doc_pointer < $inner_doc_count ; $doc_pointer++){
							$doc_pointer_id = (string)$doc_sech[$doc_pointer]['_id'];
							array_push($matched_doc_ids, $doc_pointer_id);
							//log_message('debug','44444444444444444444444444444444444444444444444'.print_r($matched_doc_ids,true));
						}
						
						$doc_record['doc_id'] = $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];
						$doc_record['matched_count'] = $inner_doc_count;
						$doc_record['document1'] = $doc_id;
						$doc_record['document2'] = (string)$doc_sech[1]['_id'];
						$doc_record['ad_no'] = $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'];
						array_push($doc_matchs, $doc_record);
						
					}
					
				}
					
					
			}else{
				
				//return false;
				
			}
		}
		return $doc_matchs;
		
	}
	
	private function get_a_doc($coll,$doc_offset){
		$query = $this->mongo_db->limit(1)->offset($doc_offset)->get($coll);
		log_message('debug','ooooooooooooooooooooooooooooooooooooooooooooooo');
		if (isset($query[0])) {
			return $query[0];
		}else{
			return false;
		}
		
	}
	
	function get_document($coll,$doc_id){
		$query = $this->mongo_db->where("_id", new MongoId($doc_id))->get($coll);
		
	
		if (isset($query[0])) {
			log_message('debug','fffffffffffffffffffffffffffffffffffffffffff');
			return json_decode(json_encode($query[0]),true);
		}else{
			return false;
		}
	
	}
	
	function get_all_docs_in_ad_no($coll,$ad_no){
		
		$query = $this->mongo_db->select(array("doc_data.widget_data"))->where("doc_data.widget_data.page2.Personal Information.AD No", $ad_no)->get($coll);
	
		if (isset($query)) {
			log_message('debug','fffffffffffffffffffffffffffffffffffffffffff');
			return json_decode(json_encode($query),true);
		}else{
			return false;
		}
	
	}
	
 
}


