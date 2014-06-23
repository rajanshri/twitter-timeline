<?php

session_start();

// include common files
include 'common.inc.php';

require_once('twitteroauth/twitteroauth.php');

session_destroy();

$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET_KEY, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);

$connection->post('account/end_session');

header('Location: '.ROOT_PATH);
exit;

?>