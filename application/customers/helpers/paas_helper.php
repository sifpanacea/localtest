<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter PaaS Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TLSTEC Developer Team (Selva)
 */

// ------------------------------------------------------------------------


// ------------------------------------------------------------------------

/**
 * Helper : Create csrf code
 *
 * @access	public
 * @return	array
 */
 
if ( ! function_exists('get_csrf_nonce'))
{
    function get_csrf_nonce()
	{
		$CI = & get_instance();
        $CI->load->helper('string');
        $CI->load->library('session');

		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);

		$CI->session->set_userdata('csrfkey', $key);
		$CI->session->set_userdata('csrfvalue', $value);

		return array($key => $value);
	}
}

// ------------------------------------------------------------------------

/**
 * Helper : Validate csrf code
 *
 * @access	public
 * @return	boolean
 */

if ( ! function_exists('valid_csrf_nonce'))
{
    function valid_csrf_nonce()
	{
		$CI = & get_instance();
        $CI->load->library('session');

		if ($CI->input->post($CI->session->userdata('csrfkey')) !== FALSE &&
			$CI->input->post($CI->session->userdata('csrfkey')) == $CI->session->userdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}


// ------------------------------------------------------------------------

/**
 * Helper : Unset csrf userdata
 *
 * @access	public
 */

if ( ! function_exists('unset_csrf_userdata'))
{
    function unset_csrf_userdata()
	{
		$CI = & get_instance();
        $CI->load->library('session');

        $CI->session->unset_userdata('csrfkey');
		$CI->session->unset_userdata('csrfvalue');

	}
}

// ------------------------------------------------------------------------

/**
 * Helper : Render view
 *
 * @access	public
 * @return	boolean
 */

if ( ! function_exists('_render_page'))
{
   function _render_page($view, $data=null, $render=false)
	{
		$CI = & get_instance();

		$CI->viewdata = (empty($data)) ? $CI->data: $data;

		$view_html = $CI->load->view($view, $CI->viewdata, $render);

		if (!$render) return $view_html;
	}
}

// --------------------------------------------------------------------

/**
 * Helper : Checks for alpabhets only in given string
 *
 * @access	public
 * @return	boolean
 *
 * 
 * @author  Selva
 */

 if ( ! function_exists('check_string'))
{
	 function check_string($str)
	 {
    	 
    	 $CI = & get_instance();

    	 $CI->load->library('form_validation');

    	 //$result =  ctype_alpha(str_replace(' ','',$str)) ? true : false;

    	 $result =  str_replace(' ','',$str) ? true : false;

    	 if ($result)
		 {
			 return TRUE;
		 }
		 else
		 {
			$CI->form_validation->set_message('check_string', 'The %s field can not contain numbers');
			return FALSE;
		 }
    	
     }
 }

// --------------------------------------------------------------------

/**
* Helper : Function to generate unique id
* 
* @access  public
* @return  string
*
* @author  Vikas
*/

if ( ! function_exists('get_unique_id'))
{
    function get_unique_id()
	{
		$time   = microtime(true);
		$ip     = $_SERVER['REMOTE_ADDR'];
		$pid    = getmypid();
		$count1 = mt_rand(0, 9999999999);
		$count2 = mt_rand(0, 9999999999);
		
		$id = md5($time.$ip.$pid.$count1.$count2);
		return $id;
	}
}

// --------------------------------------------------------------------

/**
* Helper : Function to download files securely
*
* @param string  $path  File path  (optional)
* 
* @access public
*
* @author  Vikas
*/

if ( ! function_exists('external_file_download'))
{
	function external_file_download($path = FALSE)
	{
        if (file_exists($path))
		{
			$CI = & get_instance();
			$CI->load->helper('file');

			$file_mime = get_mime_by_extension($path);
			 
			if (empty($file_mime))
			{
				$file_mime = 'application/octet-stream';
			}
			 
			header('Content-Description: File Transfer');
			header('Content-Type: ' . $file_mime);
			header('Content-Disposition: inline; filename='.pathinfo($path, PATHINFO_BASENAME));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			echo read_file($path);
		} 
		else 
		{
			show_error('File not found', 404);
		}
	}
}

// --------------------------------------------------------------------

/**
* Helper : Function to create directory in PaaS
*
* @param string  $path                  File path
* @param int     $rights                rwe Permission for the file
* @param bool    $recursion             To create all the folders in the $path variable (optional)
* @param bool    $not_customer_creation To skip index and .htaccess file during customer creation, by default its TRUE
* 
* @access public
*
* @author Vikas
*/

if ( ! function_exists('mkdir_safe'))
{
	function mkdir_safe($path,$rights = DIR_READ_MODE,$recursion = FALSE, $not_customer_creation = TRUE)
	{
         if(@mkdir($path,$rights,$recursion))
         {
		
			if($not_customer_creation){
				$data = "";
				
				$data .= "
		           <html>
					<head>
						<title>403 Forbidden</title>
					</head>
					<body>
					
					<p>Directory access is forbidden.</p>
					
					</body>
					</html>
		           ";
				
				$file = fopen($path."/index.html" , "w");
				
				if ($file)
				{
					$result = fputs ($file, $data);
				}
					
				fclose ($file);
				
				$data = "";
				
				$data .= "
		            Options -Indexes
					#Turn on the Rewrite Engine
					RewriteEngine on
					#Allow my domain
					RewriteCond %{HTTP_REFERER} !^http://(www\.)?paas.com\.paas/.*$ [NC]
					#TEST HERE IF THE USER IS NOT A BLANK REFERRER
					#IF NOT BLOCK ACCESS TO IMAGES BY ENTERING FILEPATH
					RewriteCond %{HTTP_REFERER} ^$
					#File types to be blocked
					RewriteRule \.(jpg|jpeg|png|gif|pdf)$ - [NC,F,L]
		           ";
				
				$file = fopen($path."/.htaccess" , "w");
				
				if ($file)
				{
					$result = fputs ($file, $data);
				}
				
				fclose ($file);
			
			}
			
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
* Helper : Function to convert bytes to giga bytes
*
* @param int  $bytes  Size in bytes
* 
* @access public
*
* @return int 
*
* @author Selva
*/

if ( ! function_exists('db_size_convert'))
{
  function db_size_convert($bytes)
  {
	 //$bytes = intval($bytes);
	 $result = ($bytes /(1024*1024*1024)) ;
	 $result = strval(round($result, 2));
	 return $result;
  }	
}  

// --------------------------------------------------------------------

/**
* Helper : Function to calculate size of given folder (path)
*
* @param int  $path  Path of the folder
* 
* @access public
*
* @return int 
*
* @author Selva ( Credits goes to Original author )
*/

if ( ! function_exists('foldersize'))
{

function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }   
    }

    return $total_size;
} 

}

// ------------------------------------------------------------------------

/**
 * Preset Checkbox
 *
 * Let's you preset the selected value of a checkbox field via info from a database
 * and allows info the in the POST array to override.
 *
 * @param    string
 * @param    string
 * @param    string
 *
 * @access    public
 *
 * @return    string
 *
 * @author Selva ( Original author : Travis Cable aka Nexus Rex, Modified by Selva )
 */
if ( ! function_exists('pre_set_check_box_value'))
{
    function pre_set_check_box_value($field = '', $value = '', $preset_value = '')
    {
        if (in_array($value,$preset_value))
        {
            return set_checkbox($field, $value, TRUE);
        }
        else
        {
            return set_checkbox($field, $value);
        }
    }
} 

// ------------------------------------------------------------------------

/**
 * Preset Select
 *
 * Let's you preset the selected value of a checkbox field via info from a database
 * and allows info the in the POST array to override.
 *
 * @access    public
 * @param    string
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('pre_set_select_box'))
{
    function pre_set_select_box($field = '', $value = '', $preset_value = '')
    {
       if(in_array($value,$preset_value))
        {
            return set_select($field, $value, TRUE);
        }
        else
        {
            return set_select($field, $value);
        }
    }
}
   

// ------------------------------------------------------------------------

/* End of file PaaS_helper.php */
/* Location: ./system/helpers/PaaS_helper.php */