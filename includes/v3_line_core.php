<?php
	//core function for send line with service reply and push
	function sendMessage($header,$method,$service,$userId,$data){
		//$header = Content-Type: application/json', 'Authorization: Bearer [ACCESS_TOKEN]'
		//$method = POST, GET
		//$service:1 = reply, 2 = push
		//$userId = user line ใช้ในกรณี push
		//$data = ข้อมูลที่จะส่งออกไปรูปแบบ json
		$url = "";
		$post = json_encode($data);
		switch($service){
			case 1:
				$url = URL_REPLY;
				break;
			case 2:
				$url = URL_PUSH;
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