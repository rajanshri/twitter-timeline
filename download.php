<?php

ob_start();
// include common files
include 'common.inc.php';
include 'library/dompdf/dompdf_config.inc.php';

require_once('twitteroauth/tmhOAuth.php');
require_once('twitteroauth/tmhUtilities.php');

if(isset($_POST['login_user_screen_name']) && trim($_POST['login_user_screen_name']) != '' && isset($_POST['download_format']) && trim($_POST['download_format']) != ''){
	$login_user_screen_name = trim($_POST['login_user_screen_name']); 
	$download_format = trim($_POST['download_format']); 

	$tmhOAuth = new tmhOAuth(array(
					'consumer_key' => TWITTER_CONSUMER_KEY,
					'consumer_secret' => TWITTER_CONSUMER_SECRET_KEY,
					'user_token' => TWITTER_ACCESS_TOKEN,
					'user_secret' => TWITTER_ACCESS_TOKEN_SECRET,
					'curl_ssl_verifypeer' => false
				));
	
	$code = $tmhOAuth->request(
						'GET', 
						$tmhOAuth->url('1.1/statuses/home_timeline'), 
						array(
							'screen_name' => $login_user_screen_name, 
							'count' => '5', 
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
			
			$tweet_text = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet_text);
			$tweet_text = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet_text);
			$tweet_text = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"http://www.twitter.com/$1\">@$1</a>", $tweet_text);
			
			$all_tw_feeds[] = array('PostUserID'=>$userid, 'PostUsername'=>$username, 'PostUser'=>$user_fullname, 'PostID'=>$post_id, 'PostMessage'=>$tweet_text, 'PostUserImage'=>$user_profile_image, 'PostCreatedOn'=>$create_time);						
			
		}
				
		switch($download_format){
			case 'csv':
				$file_name = "all-tweets-details-of-".$login_user_screen_name.".csv";
				$csv_file = ABSOLUTE_CSV_DOWNLOAD_PATH.$file_name; 
			
				$cvsData = "User Name, Screen Name, Tweet, Create Date\r\r\n";
				
				if(isset($all_tw_feeds) && count($all_tw_feeds) > 0){
					foreach($all_tw_feeds as $feed){
						$cvsData .= str_replace(',',' ',stripslashes(trim($feed['PostUser']))). "," .str_replace(',',' ',stripslashes(trim($feed['PostUsername']))). "," .str_replace(',','- ',stripslashes(trim($feed['PostMessage']))). "," .$feed['PostCreatedOn']."\r\r\n";
						
					}
					
					if(file_exists($csv_file)){	
			
						$file_handling = fopen($csv_file, "w");
				
						if($file_handling){						
				
							fwrite($file_handling, "");							
				
							fwrite($file_handling, $cvsData);						
				
							fclose($file_handling);			 
				
						}
				
					}else{ 
				
						$file_handling = fopen($csv_file, "w");
				
						if($file_handling){			
							
							fwrite($file_handling, $cvsData);
							
							fclose($file_handling);			 
							
						}						
					}
					
					header('Content-Type: application/csv'); //Outputting the file as a csv file
				
					header('Content-Disposition: attachment; filename='.$file_name); //Defining the name of the file and suggesting the browser to offer a 'Save to disk ' option
				
					header('Pragma: no-cache');
				
					
				
					readfile($csv_file);
				
					exit;
				}
			break;
			
			case 'xls':
				$file_name = "all-tweets-details-of-".$login_user_screen_name.".xls";
				$download_file = ABSOLUTE_XLS_DOWNLOAD_PATH.$file_name; //"download/csv/".$file_name;//
				
				$downloadData = "";
				
				$downloadData .= "User Name\t";
				$downloadData .= "Screen Name\t";
				$downloadData .= "Tweet\t";
				$downloadData .= "Create Date\t";
		
				$downloadData.="\n";
				
				if(isset($all_tw_feeds) && count($all_tw_feeds) > 0){
					foreach($all_tw_feeds as $feed){
											
						$downloadData .= stripslashes(trim($feed['PostUser']))."\t";
						$downloadData .= stripslashes(trim($feed['PostUsername']))."\t";
						$downloadData .= stripslashes(trim($feed['PostMessage']))."\t";
						$downloadData .= $feed['PostCreatedOn']."\t";
						$downloadData.="\n";
					}
					
					if(file_exists($download_file)){	
			
						$file_handling = fopen($download_file, "w");
				
						if($file_handling){						
				
							fwrite($file_handling, "");	
							
							fwrite($file_handling, $downloadData);						
				
							fclose($file_handling);			 
				
						}
				
					}else{ 
				
						$file_handling = fopen($download_file, "w");
				
						if($file_handling){			
							
							fwrite($file_handling, $downloadData);	
							fclose($file_handling);			 
							
						}
						
					}
					
					header('Content-Type: application/vnd.ms-excel'); //Outputting the file as a csv file
				
					header('Content-Disposition: attachment; filename='.$file_name); //Defining the name of the file and suggesting the browser to offer a 'Save to disk ' option
				
					header('Pragma: no-cache');
				
					
				
					readfile($download_file);
				
					exit;
				}
			break;
			
			case 'xml':
				$file_name = "all-tweets-details-of-".$login_user_screen_name.".xml";
				$download_file = ABSOLUTE_XML_DOWNLOAD_PATH.$file_name; //"download/csv/".$file_name;//					
								
				if(isset($all_tw_feeds) && count($all_tw_feeds) > 0){
					$xml = new DOMDocument('1.0', 'UTF-8');
					$xml_twittertweet = $xml->createElement("twittertweet");
					$xml_twittertweet->setAttribute("version", '1');
					$xml_twittertweet->setAttribute("xmlns", 'http://xspf.org/ns/0/');
					$xml_tweetlist = $xml->createElement("tweetList");
					foreach($all_tw_feeds as $feed){
						
						$xml_tweets = $xml->createElement("tweets");
								
						$xml_tweets->appendChild($xml->createElement("UserName", trim($feed['PostUser'])));
						$xml_tweets->appendChild($xml->createElement("ScreenName", trim($feed['PostUsername'])));
						$xml_tweets->appendChild($xml->createElement("Tweet", trim($feed['PostMessage'])));
						$xml_tweets->appendChild($xml->createElement("CreateDate", $feed['PostCreatedOn']));
						$xml_tweetlist->appendChild($xml_tweets);
					}
					
					$xml_twittertweet->appendChild($xml_tweetlist);
					$xml->appendChild($xml_twittertweet);					
					
					
					$xml->saveXML();
					$xml->save($download_file);
					
					header('Content-Type: text/xml'); //Outputting the file as a csv file
				
					header('Content-Disposition: attachment; filename='.$file_name); //Defining the name of the file and suggesting the browser to offer a 'Save to disk ' option
				
					header('Pragma: no-cache');
				
					readfile($download_file);
				
					exit;
				}
			break;
			
			case 'pdf':
				$file_name = "all-tweets-details-of-".$login_user_screen_name.".pdf";
				$download_file = ABSOLUTE_PDF_DOWNLOAD_PATH.$file_name; 						
								
				if(isset($all_tw_feeds) && count($all_tw_feeds) > 0){
					$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
					$html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
					$html .= '<head>';
					$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
					$html .= '<title>Twitter Tweets</title>';
					$html .= '<style>* { font-family: "DejaVu Sans","ariblk", "monospace","Times-Roman"; } @page { margin: 0em; } </style>';
					$html .= '</head>';
					$html .= '<body bgcolor="#ffffff">';
					$html .= '<table id="Table_01" width="100%" border="1" cellpadding="0" cellspacing="0"  bgcolor="#ffffff">';
					$html .= '<tr>';
					$html .= '<td colspan="4" style="text-align:center; padding:10px; font-weight:bold; font-size:16px;">All Tweet Details<br/></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td style="padding:5px; text-align:left; font-weight:bold;">User Name</td><td style="padding:5px; text-align:left; font-weight:bold;">Screen Name</td><td style="padding:5px; text-align:left; font-weight:bold;">Tweet</td><td style="padding:5px; text-align:left; font-weight:bold;">Created Date</td>';
					$html .= '</tr>';
					
					foreach($all_tw_feeds as $feed){
						
						$html .= '<tr>';
						$html .= '<td style="padding:5px; text-align:left; width:18%;">'.trim($feed['PostUser']).'</td><td style="padding:5px; text-align:left; width:18%;">'.trim($feed['PostUsername']).'</td><td>'.trim($feed['PostMessage']).'</td><td style="padding:5px; text-align:left; width:15%;">'.trim($feed['PostCreatedOn']).'</td>';
						$html .= '</tr>';
					}
					
					$html .= '</table>';
					$html .= '</body>';
					$html .= '</html>';
					
					$dompdf = new DOMPDF();
					$dompdf->load_html($html);
					$dompdf->set_paper('a4','portrait');
					$dompdf->render();
					$dompdf->stream($file_name);
									
					exit;
				}
			break;
			
			case 'json':
				$file_name = "all-tweets-details-of-".$login_user_screen_name.".json";
				$download_file = ABSOLUTE_JSON_DOWNLOAD_PATH.$file_name; 
				
				$jsonData = array();			
				
				if(isset($all_tw_feeds) && count($all_tw_feeds) > 0){
					
					foreach($all_tw_feeds as $feed){
						$jsonData[] = array("User Name" => stripslashes(trim($feed['PostUser'])), "Screen Name" => stripslashes(trim($feed['PostUsername'])), "Tweet" => stripslashes(trim($feed['PostMessage'])), "Create Date" => $feed['PostCreatedOn']);						
					}
					
					if(file_exists($download_file)){	
			
						$file_handling = fopen($download_file, "w");
				
						if($file_handling){						
				
							fwrite($file_handling, "");						
				
							fwrite($file_handling, json_encode($jsonData));						
				
							fclose($file_handling);			 
				
						}
				
					}else{ 
				
						$file_handling = fopen($download_file, "w");
				
						if($file_handling){			
							
							fwrite($file_handling, json_encode($jsonData));								
							
							fclose($file_handling);			 
							
						}
						
					}
					
					header('Content-Type: application/json'); //Outputting the file as a csv file
				
					header('Content-Disposition: attachment; filename='.$file_name); //Defining the name of the file and suggesting the browser to offer a 'Save to disk ' option
				
					header('Pragma: no-cache');
				
					
				
					readfile($download_file);
				
					exit;
				}
			break;
		}
		
		
		
	}
}

?>