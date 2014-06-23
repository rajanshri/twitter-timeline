<?php
ob_start();

session_start();
	
include '../common.inc.php';
include '../library/JSON.php';

require_once('../twitteroauth/tmhOAuth.php');
require_once('../twitteroauth/tmhUtilities.php');

$data = array();
$html_content = '';
$ip=getenv('REMOTE_ADDR');
$today = time();

if(isset($_POST['screen_name']) && trim($_POST['screen_name']) != ''){
	$screen_name = trim($_POST['screen_name']);	
	
	$tmhOAuth = new tmhOAuth(array(
				'consumer_key' => TWITTER_CONSUMER_KEY,
				'consumer_secret' => TWITTER_CONSUMER_SECRET_KEY,
				'user_token' => TWITTER_ACCESS_TOKEN,
				'user_secret' => TWITTER_ACCESS_TOKEN_SECRET,
				'curl_ssl_verifypeer' => false
			));

	$code = $tmhOAuth->request(
						'GET', 
						$tmhOAuth->url('1.1/statuses/user_timeline'), 
						array(
							'screen_name' => $screen_name, 
							'count' => '10', 
							'include_rts' => true, 
							'include_entities' => true
							
						)
					);
	$response = $tmhOAuth->response['response'];
	$tweets = json_decode($response, true);
		
	if(isset($tweets) && count($tweets) > 0){
		foreach($tweets as $tweet){	
			$userid = $tweet['user']['id_str'];
			$username = $tweet['user']['screen_name'];
			$user_fullname = $tweet['user']['name'];
			$post_id = $tweet['id_str'];
			$tweet_text = $tweet['text'];
			$user_profile_image = $tweet['user']['profile_image_url_https'];
			$create_time = date('Y-m-d H:i:s', strtotime($tweet['created_at']));
			$timeDiff = $func->dateDiff($today, $tweet['created_at'], 1);
			
			$tweet_text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\./-]*(\?\S+)?)?)?)@', '<a target="blank" title="$1" href="$1">$1</a>', $tweet_text);
			$tweet_text = preg_replace("/#([0-9a-zA-Z_-]+)/", "<a target='blank' title='$1' href=\"http://twitter.com/search?q=%23$1\">#$1</a>", $tweet_text);
			$tweet_text = preg_replace("/@([0-9a-zA-Z_-]+)/", "<a target='blank' title='$1' href=\"http://twitter.com/$1\">@$1</a>", $tweet_text);	
			
			$html_content .= '<div class="slide">';
			$html_content .= '<div style="background-color:#CCC; height:140px; padding:5px;">';
			$html_content .= '<div style="width:100%; margin:3px 0; height:50px;">';
			$html_content .= '<div style="width:48px; margin:0 3px 0 0; float:left; display:inline;">';
			$html_content .= '<img src="'.$user_profile_image.'" alt=""  style="border:1px solid #000;" />';
			$html_content .= '</div>';
			$html_content .= '<div style="float:left; display:inline;">';
			$html_content .= $user_fullname.' - '.$timeDiff;
			$html_content .= '</div>';
			$html_content .= '</div>';
			$html_content .= '<div style="width:100%; margin:3px 0;">';
			$html_content .= $tweet_text;
			$html_content .= '</div>';
			$html_content .= '</div>';
			$html_content .= '</div>';
		}
		
		$data['ErrorCode'] = 0;
		$data['Content'] = $html_content;
	}else{
		$data['ErrorCode'] = 1;
	}
}else{
	$data['ErrorCode'] = 1;
}


die(json_encode($data));

?>