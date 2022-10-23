<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Template_model extends CI_Model 
{  


    function __construct() 
	{
        parent::__construct();
        $this->config->load('mongodb');
	    $this->_configvalue = $this->config->item('default');
	    // Initialize MongoDB database names
        $this->collections = $this->config->item('collections', 'ion_auth');
    }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Sekar
	 */

	function saveimage_url($filename,$targetFile,$thumb_path,$file_title,$file_description)
	{
		
		$availablecheck = $this->exists('templates',$filename);
         if($availablecheck == FALSE)
         {
		        $insertdata = array(
             	        'file_name' => $filename,
						'file_title' => $file_title,
						'file_description' => $file_description,
             	        'file_path' => $targetFile,
						'file_thumb_path'=>$thumb_path,
                 );
             $this->mongo_db->insert($this->collections['templates'],$insertdata);
		 }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Sekar
	 */

	function deleteimage_url($image_id)
	{
		$this->mongo_db->select(array(),array('_id','file_name'));
		$query=$this->mongo_db->getWhere('templates',array('file_name' => $image_id));
		unlink($query[0]['file_path']);
	    unlink($query[0]['file_thumb_path']);
		$this->mongo_db
			->where('file_name',$image_id)
			->delete($this->collections['templates']);
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Sekar
	 */

	function delete_source($image_id)
	{
		$this->mongo_db->select(array(),array('_id'));
		$query=$this->mongo_db->getWhere('templates',array('file_name' => $image_id));
		unlink($query);
	}

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Sekar
	 */

	function galary_update($limit, $page)
	{
		$offset = $limit * ( $page - 1);
		
		$this->mongo_db->orderBy(array('_id' => -1));
		$this->mongo_db->limit($limit);
		$this->mongo_db->offset($offset);
		$this->mongo_db->select(array('file_name','file_path','file_title','file_description'));
		$query=$this->mongo_db->get($this->collections['templates']);
		$obj = json_decode(json_encode($query), FALSE);
		return $obj;
		
	}

	// --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  array
	 *
	 * @author  Sekar
	 */

	function galary_img()
	{
		$this->mongo_db->orderBy(array('_id' => -1));
		$this->mongo_db->select(array('file_name','file_path','file_title','file_description'));
		$query=$this->mongo_db->get($this->collections['templates']);
		$obj = json_decode(json_encode($query), FALSE);
		return $obj;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  bool
	 *
	 * @author  Sekar
	 */

	public  function exists($collectionname,$filename)
    {
    	$query = $this->mongo_db->getWhere($collectionname, array('file_name' => $filename));
    	
		$result = json_decode(json_encode($query), FALSE);

    	if ($result)
    		return TRUE;
    	else
    		return FALSE;
    }

    // --------------------------------------------------------------------

	/**
	 * Helper : 
	 *	
	 *
	 * @return  int
	 *
	 * @author  Sekar
	 */

	function imagecount()
	{
		return $this->mongo_db->count($this->collections['templates']);
	}
   
}