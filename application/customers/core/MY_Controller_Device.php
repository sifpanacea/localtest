<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* File: application/core/MY_Controller.php */
	class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

         if ( ! $this->ion_auth->logged_in())
        {
             redirect(URC.'auth/login');
         }

    }
}

?>