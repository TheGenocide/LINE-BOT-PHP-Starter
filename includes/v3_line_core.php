<?php
	//core function for send line with service reply and push
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