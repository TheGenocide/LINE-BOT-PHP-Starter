<?php
	include_once "includes/v3_line_config.php";
	
	//Default Var
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . ACCESS_TOKEN);
	
	// Get POST body content
	$content = file_get_contents('php://input');
	// Parse JSON
	$events = json_decode($content, true);
	// Validate parsed JSON data
	if (!is_null($events['events'])) {	
		// Loop through each event	
		foreach ($events['events'] as $event) {		
			// Reply only when message sent is in 'text' format		
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {			// Get text sent			
				$text = $event['message']['text']." User type : ".$event['source']['type']." And User id : ".$event['source']['userId'];			// Get replyToken			
				$replyToken = $event['replyToken'];			// Build message to reply back			
				$messages = ['type' => 'text','text' => $text];			// Make a POST Request to Messaging API to reply to sender		 	
				$data = ['replyToken' => $replyToken,'messages' => [$messages],];					
				sendMessage($headers,"POST",1,'',$data);
			}	
		}
	}
	echo "BOT OK";
	
	function sendMessage($header,$method,$service,$userId,$data){
		//$service:1 = reply
		//$service:2 = push
		$url = "";
		$post = json_encode($data);
		switch($service){
			case 1:
				$url = URL_REPLY;
				break;
			case 2:
				$url = URL_PUSE;
				break;
		}
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);			
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);			
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);			
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);			
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);			
		$result = curl_exec($ch);			
		curl_close($ch);			
		echo $result . "";	
	}
?>
