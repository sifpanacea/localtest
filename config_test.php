<?php
/* CORE APP config */
$config['app']['base_dir'] = $_SERVER['DOCUMENT_ROOT'];

$config['app']['tenant_folder'] = ''; //folder name
$config['app']['app_folder'] = 'PaaS'; //folder name
$config['app']['asset_folder'] = ''; //folder name with a trailing slash only if there is an asset folder else leave blank

$config['app']['system_path'] = $config['app']['base_dir'].'/'.$config['app']['app_folder'] .'/system/'; //will be sed by the CI index file
$config['app']['application_path'] = $config['app']['base_dir'].'/'.$config['app']['app_folder'] .'/application/admin'; //will be used by the CI index file
$config['app']['view_path'] = $config['app']['base_dir'].'/'.$config['app']['app_folder'] .'/application/admin';

$config['app']['https_type'] = 'https://';  
$config['app']['base_url'] = $config['app']['https_type'].$_SERVER["SERVER_NAME"].'/'.$config['app']['app_folder'];
$config['app']['tenant_url'] = $config['app']['https_type'].$_SERVER["SERVER_NAME"].'/'.$config['app']['tenant_folder'];
$config['app']['log_path'] = $config['app']['base_dir'].'/'.$config['app']['app_folder'] .'/application/admin/logs/';
///The above config variable are defined to create the basic paths and URLs required for the app to work, which are defined just below


/* Main Paths and urls */
if(! defined('APP_TENANTPATH')){
    define('APP_TENANTPATH', $config['app']['base_dir'].'/'.$config['app']['app_folder'].'/');//this will be used by ci_app config files to load this config file
    define('APP_TENANTURL', $config['app']['tenant_url']);//this the path that will show on the browser and will be used for accessing the controllers and methods
    define('APP_ASSETURL', $config['app']['base_url'].'/'.$config['app']['asset_folder']);//this will be required to access tenant specific assets
	} 

/* DB config */
$config['IP'] 						= 'mednote.in';
$config['IP_DB'] 					= '127.0.0.1';
$config['hostname'] 				= $config['IP_DB'].':27017/';
$config['dsn'] 						= $config['IP_DB'].':27017/alpha_common';
$config['common_db'] 				= $config['IP_DB'].':27017/alpha_common';
$config['mongo_username'] 			= 'admin';
$config['mongo_password'] 			= 'admin';
$config['mongo_persist']  			= TRUE;
$config['persist_key']	 			= 'ci_persist';
$config['replica_set']  			= FALSE;
$config['query_safety'] 			= 'w';
$config['suppress_connect_error'] 	= FALSE;
$config['host_db_flag']   			= FALSE; 

/* CI specific params*/
$config['ci']['db_cache'] 			= FALSE;
//include any other config files here which have configurations in $config variable
//like email.php etc
/* End of file config.php */

