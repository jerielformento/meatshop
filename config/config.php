  <?php

/* ---------------------------------------------------*
*		-- JF Framework v2.0 copyright 2017 --		  |
*-----------------------------------------------------|
*													  |
*	Name: Jeriel Formento							  |
*	Position: Web Developer							  |
*	Description: PHP Framework						  |
*													  |
* ----------------------------------------------------*/


/* uncomment below for error reporting */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT); ini_set('display_errors', 1); ini_set('html_errors', 1);
 
/* declare variables */

$app = array();

/* -- configuration settings -- */

$app['site'] = array(
	'id' => 1,
	'url' => 'http://' . $_SERVER['SERVER_NAME'] . '/',
	'title' => 'CDEP AES System',
	'page' => '/',
	'maintenance' => 0,  
	'admin_ip' => array('122.53.133.68','110.54.224.236'),
	'social_media_accounts' => array(
		'facebook' => '',
		'instagram' => '',
		'twitter' => ''
	)
); 

/* -- session config -- */ 
$app['session'] = array(
	'prefix' => 'cdepaessys_',
	'var' => array(
		'user_id' => 'uniqid',
        'username' => 'usrn',
		'priv_id' => 'privid',
		'ipaddress' => 'ipaddr',
		'pages_access' => 'pgs_acs',
		'nav_class' => 'sb_nav_class',
		'body_class' => 'sb_body_class',
		'sb_status' => 'sidebar_status'
	)
);

/* application global variable config */
$app['app'] = array(
	'version' => 'v4.0',
	'logstart' => 0,
	'logend' => 1,
	'in_status' => 1,
	'out_status' => 2
);

/* -- database connection -- 
**
    Config load from .env file
    You can check the .env.example file as reference
    Repository: https://github.com/vlucas/phpdotenv
*/

$app['connection'] = array(
	'host'=> $_ENV['DB_HOST'],
	'database' => $_ENV['DB_NAME'],
	'username' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PWD'],
	'prefix' => $_ENV['DB_TABLE_PREFIX']
);

/* $app['connection'] = array(
	'host'=> 'localhost',
	'database' => 'jl_frozen_sysdb_22',
	'username' => 'root',
	'password' => '',
	'prefix' => 'jfro_'
);   */

/* -- additional config -- */

$app['adconf'] = array(
	'defpwd' => 'P@$$w0rd123!',
	'notarchived' => 0,
	'archived' => 1,
	'defdate' => '0000-00-00',
	'mincharuname' => 6,
	'mincharpwd' => 7,
	
	'logtypein' => 0,
	'logtypeout' => 1,
	
	'mob' => '09',
	'moblen' => 11,
	'mobpref' => '+639',
	'mobpreflen' => 13,
	
	'client_whitelisted_ips' => array(
		
	),
	
	'timezones' => array(
		'def' => 0,
		'est' => 12,
		'dst' => 13
	),
	
	'appver' => 'v1.7'
    
);

$app['plugins'] = array(
    
);

$app['api'] = array(
	/* You can get one here: https://codepen.io/corenominal/full/rxOmMJ */
	'key' => array(
		'bdb3fe31-c030-4624-9e10-d2470c0e8655'
	)
);

/* -- pages routes settings -- */

$app['routes'] = array(

	// This is the default main page
	'client' => array(
		'path' => 'client',
	),

	'register' => array(
		'path' => 'register',
	),

	'products' => array(
		'path' => 'products',
	),

	'cart' => array(
		'path' => 'cart',
	),

	'checkout' => array(
		'path' => 'checkout',
	),

	// This is the default main page for Admin Login
	'default' => array(
		'path' => 'index',
	),

	'administrator'	=> array(
		'path' => 'administrator',
	),

	'dashboard'	=> array(
		'path' => 'dashboard',
	),

	'aproducts' => array(
		'path' => 'aproducts',
	),

	'login'	=> array(
		'path' => 'login',
	),

	'logout'	=> array(
		'path' => 'logout',
	),

	'users' => array(
		'path' => 'users',
	),

	/* admin pages */
    
    'admin' => array(
		'path' => 'admin',
	),
);

$app['public_routes'] = array(
	'profile' => array(
		'path' => 'profile',
	),
	
	'cron' => array(
		'path' => 'cron',
	),
	
	'api' => array(
		'path' => 'api',
	),
);

$app['api_routes'] = array(
	'cron' => array(
		'path' => 'cron',
	),
	
	'api' => array(
		'path' => 'api',
	),
);


$app['files_path'] = array(
	'view' => 'view',
	'controller' => 'controller',
	'model' => 'model',
	'system' => 'system',
	'error' => array(
		'system/template', // folder
		'error.php' // file
	),
	'maintenance' => array(
		'system/template',
		'maintenance.php'
	),
);

$app['error_file_path'] = array();

/* default template */
$app['template'] = array(
	'default' => array(
		'header' => 'header',
		'footer' => 'footer'
	),
	'login' => array(
		'header' => 'login_header',
		'footer' => 'login_footer'	
	),
	'client' => array(
		'header' => 'client_header',
		'footer' => 'client_footer'	
	),
	'nodes' => array(
		'header' => 'nd_header',
		'footer' => 'nd_footer'	
	),
);

/* php.ini settings */

$app['init'] = array(
	'default_timezone' => 'Asia/Manila',
	'est_timezone' => 'US/Eastern',
	'utc_timezone' => 'UTC',
	'upload_max_filesize' => '20M',
	'post_max_size' => '128M',
	'max_input_time' => 300,
	'max_execution_time' => 600,
	'memory_limit' => '2048M'
);


/* -- custom classes -- */

$app['custom_libs'] = array(
	'libs/plugins/User',
	'libs/plugins/LDAP',
	'libs/plugins/Validate',
    'libs/plugins/Form',
    
    /* Model Libraries */
    'model/UsersModel',
    'model/CoursesModel',
	'model/StudentsModel',
);
