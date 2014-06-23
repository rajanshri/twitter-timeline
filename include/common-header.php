<?php

require_once './twitter-async/EpiCurl.php';
require_once './twitter-async/EpiOAuth.php';
require_once './twitter-async/EpiTwitter.php';

if(isset($_GET['oauth_token'])){

}else{
	$twitterObj = new EpiTwitter(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET_KEY);
	$twitter_login_url = $twitterObj->getAuthorizationUrl();
}


?>