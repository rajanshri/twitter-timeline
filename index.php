<?php
ob_start();

/* Start session and load library. */
session_start();
// include common files
include 'common.inc.php';

require_once('twitteroauth/twitteroauth.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET_KEY);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];



/* If last connection failed don't display authorization link. */
if($connection->http_code == 200){
	/* Build authorize URL and redirect user to Twitter. */
    $twitter_login_url = $connection->getAuthorizeURL($token);
}else{
	$twitter_login_url = '';
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<title><?php echo SITE_NAME; ?></title>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>style.css" />
	<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-supersized-3.2.7.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <!--[if lt IE 10]>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>styleIE.css" />
	<![endif]-->
</head>
<body >
	<div id="bodybg"></div>
    <div class="wrapper">
        <div class="container">
            <div class="sp-container">
            	<h1>&#126; <?php echo SITE_NAME; ?> &#126;</h1>
            
                <div class="sp-content">
                    <!-- LEFT TEXT -->
                    <div class="sp-wrap sp-left">
                        <h2>
                            <span class="sp-top">We're nearly there</span> 
                            <span class="sp-mid">twitter</span> 
                            <span class="sp-bottom">get your tweets</span>
                        </h2>
                    </div>
                    <!-- RIGHT TEXT -->
                    <div class="sp-wrap sp-right">
                        <h2>
                            <span class="sp-top">Not long now</span> 
                            <span class="sp-mid">feed! <i>...</i><i>...</i></span> 
                            <span class="sp-bottom">get follow tweets</span>
                        </h2>
                    </div>
                </div>
            	<!-- BIG TEXT AND LINK BUTTON -->
                <div class="sp-full">
                    <h2>Like to know when we're ready?</h2>
                    <!--<a href="#" data-reveal-id="myModal">Sign in with Twitter!</a>-->
                    <a href="<?php if(isset($twitter_login_url)){ echo $twitter_login_url; } ?>">Sign in with Twitter!</a>
                </div>
            </div>
        </div>	
    
    </div>
	
	<!-- YOUR BACKGROUND IMAGE SETTINGS -->
	<script type="text/javascript">
		jQuery(function($){
			$.supersized({
				transition : 0,
				slides : [
					{image : '<?php echo IMAGES_PATH; ?>4.jpg'}
				]
			});
		});
    </script>
</body>
</html>