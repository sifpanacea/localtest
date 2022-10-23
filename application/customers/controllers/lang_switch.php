<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Lang_switch extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
		
		$this->config->load('config', TRUE);
    }
 
    function switchLanguage($language = "") {
		
        $language = ($language != "") ? $language : "english";
		
		$this->input->set_cookie('language', $language, 3600*2);
		$this->config->set_item('language', $language);
		//$language=$language;
		
		redirect('dashboard/to_dashboard');
    }
}