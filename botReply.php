<?php
	ob_start();
	include_once "includes/v3_line_config.php";
	include_once "includes/v3_line_core.php";
	
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
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {	
				// Get text sent		
				$tmp = explode(" ",(trim($event['message']['text'])));
				$cmd = strtolower(trim($tmp[0]));
				$val = trim($tmp[1]);
				// Get replyToken
				$replyToken = $event['replyToken'];
				$text = "";
				switch($cmd){
					case "@help":
						$text = "[system] คำสั่งที่สามารถใช้ได้มีดังนี้\n";
						$text .= "1. @reg ชื่อยูสเซอร์ในระบบ TURAC เพื่อทำการลงทะเบียนครั้งแรกสำหรับเข้าใช้งานระบบแจ้งเตือนทาง Line\n";
						$text .= "2. @unreg ชื่อยูสเซอร์ในระบบ TURAC เพื่อลบชื่อผู้ใช้นี้ออกจากระบบแจ้งเตือน\n";
						$text .= "3. @check ตรวจสอบสถานะการลงทะเบียนเพื่อรับการแจ้งเตือนผ่าน Line\n";
						$text .= "4. @userid เพื่อแสดง User id ของระบบ Line สำหรับนำไปลงทะเบียนด้วยตนเองผ่านทางสำนักวิจัย";
						break;
					case "@reg":
						$text = "[system] ระบบทำการลงทะเบียนยูสเซอร์ ".$val." เพื่อเข้าใช้งานระบบแจ้งเตือนสำเร็จ";
						break;
					case "@unreg":
						$text = "[system] ได้ทำการลบ user นี้ออกจากระบบแล้ว";
						break;
					case "@check":
						$text = "[system] ไม่พบยูสเซอร์นี้ในระบบโปรดทำการลงทะเบียนใหม่อีกครั้ง หรือติดต่อเจ้าหน้าที่สำนักวิจัย";
						break;
					case "@userid":
						$text = "[system] User id ระบบ Line ของท่านคือ ".$event['source']['userId'];
						break;
					default:
						$text = "[system] ไม่พบคำสั่งนี้ในระบบโปรดพิมพ์ @help เพื่อรับข้อมูลเพิ่มเติม";
						break;
				}
				
				// Build message to reply back
				$messages = ['type' => 'text','text' => $text];
				// Make a POST Request to Messaging API to reply to sender		 	
				$data = ['replyToken' => $replyToken,'messages' => [$messages],];			
				sendMessage($headers,"POST",1,'',$data);
			}	
		}
	}
	echo "BOT OK";
	ob_end_flush();
?>
