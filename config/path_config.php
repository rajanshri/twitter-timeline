<?php
    
    define('ABSOLUTE_PATH', dirname(dirname(__FILE__))); //Add your domain absolute path
    defined('ROOT_PATH') || define('ROOT_PATH', 'http://domainname.com/'); //Add your domain name with full path
    defined('CSS_PATH') || define('CSS_PATH', ROOT_PATH . 'css/');
    defined('JS_PATH') || define('JS_PATH', ROOT_PATH . 'js/');
    defined('IMAGES_PATH') || define('IMAGES_PATH', ROOT_PATH . 'images/');
    defined('INCLUDE_PATH') || define('INCLUDE_PATH', ROOT_PATH . 'include/');
	
	define('SITE_NAME', 'Twitter Timeline');

	define('NO_OF_FOLLOWERS', 10);
	
	defined('TWITTER_CONSUMER_KEY') || define('TWITTER_CONSUMER_KEY', '2Jz0XXXXXXXXXXXXXXXXXXXaQ'); //Add your twitter consumer key
	defined('TWITTER_CONSUMER_SECRET_KEY') || define('TWITTER_CONSUMER_SECRET_KEY', 'aRB5iXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXlwwb'); //Add twitter consumer secret key
	defined('TWITTER_ACCESS_TOKEN') || define('TWITTER_ACCESS_TOKEN', '1XXXXXXXX5-9GeXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXAjH'); //Add twitter access token 
	defined('TWITTER_ACCESS_TOKEN_SECRET') || define('TWITTER_ACCESS_TOKEN_SECRET', 'UOKWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXqSz'); //Add twitter access token secret
	defined('TWITTER_OAUTH_CALLBACK') || define('TWITTER_OAUTH_CALLBACK', 'http://domainname.com/home.php'); //Add twitter call back page url of your site
	
	defined('ABSOLUTE_CSV_DOWNLOAD_PATH') || define('ABSOLUTE_CSV_DOWNLOAD_PATH', ABSOLUTE_PATH . '/download/csv/');
	defined('ABSOLUTE_XLS_DOWNLOAD_PATH') || define('ABSOLUTE_XLS_DOWNLOAD_PATH', ABSOLUTE_PATH . '/download/xls/');
	defined('ABSOLUTE_XML_DOWNLOAD_PATH') || define('ABSOLUTE_XML_DOWNLOAD_PATH', ABSOLUTE_PATH . '/download/xml/');
	defined('ABSOLUTE_PDF_DOWNLOAD_PATH') || define('ABSOLUTE_PDF_DOWNLOAD_PATH', ABSOLUTE_PATH . '/download/pdf/');
	defined('ABSOLUTE_JSON_DOWNLOAD_PATH') || define('ABSOLUTE_JSON_DOWNLOAD_PATH', ABSOLUTE_PATH . '/download/json/');

?>