<?php

/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load library. */

// include common files
include 'common.inc.php';

require_once('twitteroauth/twitteroauth.php');

session_start();

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && isset($_SESSION['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  session_destroy();
  header('Location: '.ROOT_PATH);
}

if(!empty($_REQUEST['oauth_verifier']) && !empty($_REQUEST['oauth_token'])){
    // We've got everything we need
	/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
	$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET_KEY, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
	
	/* Request access tokens from twitter */
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	
	if (200 == $connection->http_code) {
		$_SESSION['access_token'] = $access_token;
		header('Location: '.ROOT_PATH.'home.php');
	}
	print_r($_SESSION);die;
} else {
    // Something's missing, go back to index
    header('Location: '.ROOT_PATH);
}

?>