<?php
	ob_start();
	include_once "modules/class.conn.php";
	include_once "includes/v3_config.inc.php";
	include_once "includes/v3_line_config.php";
	include_once "includes/v3_line_core.php";
	
	$conn = new DBConnect(DBBOT,DBBOT_USER,DBBOT_PW,My_config);
	$dbHandle = $conn->DBhandle();//connect sql
	
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
				$val = mysqli_real_escape_string($dbHandle,trim($tmp[1]));
				$userId = mysqli_real_escape_string($dbHandle,trim($event['source']['userId']));
				$sql = "";
				$sql2 = "";
				$messages = "";
				$data = "";
				// Get replyToken
				$replyToken = $event['replyToken'];
				$text = "";
				switch($cmd){
					case "@help":
						$text = "[system] คำสั่งที่สามารถใช้ได้มีดังนี้\n";
						$text .= "1. @reg ชื่อยูสเซอร์ในระบบ TURAC เพื่อทำการลงทะเบียนครั้งแรกสำหรับเข้าใช้งานระบบแจ้งเตือนทาง Line\n";
						$text .= "2. @unreg เพื่อลบชื่อผู้ใช้นี้ออกจากระบบแจ้งเตือน\n";
						$text .= "3. @check ตรวจสอบสถานะการลงทะเบียนเพื่อรับการแจ้งเตือนผ่าน Line\n";
						$text .= "4. @userid เพื่อแสดง User id ของระบบ Line สำหรับนำไปลงทะเบียนด้วยตนเองผ่านทางสำนักวิจัย";
						break;
					case "@reg":
						if($val <> ""){
							//ตรวจสอบ Line id ซ้ำในระบบ
							$sql = "SELECT * FROM ".My_config.".v3_userpriv WHERE sts <> '0' and line_id='$userId';";
							$conn->QuerySQL($sql);
							if($conn->NumSQL()>0){
								$text = "[system] ยูสเซอร์นี้ได้ลงทะเบียนในระบบแล้ว";
							}else{
								//ตรวจสอบ user ว่ามีในระบบ TURAC หรือไม่
								$sql = "SELECT * FROM ".My_config.".v3_userpriv WHERE sts <> '0' and Username='$val' order by userprivid DESC limit 1;";
								$conn->QuerySQL($sql);
								if($conn->NumSQL()<=0){
									$text = "[system] ไม่พบยูสเซอร์นี้ในระบบ TURAC โปรดลองใหม่อีกครั้งหรือติดต่อเจ้าหน้าที่สำนักวิจัย";
								}else{
									//บันทึก Line id เข้าสู่ระบบ TURAC
									$result = $conn->FetchSQL();
									$sql2 = "UPDATE ".My_config.".v3_userpriv SET line_id='$userId' WHERE Username='$val' and sts <> '0' and workFlg = 'O' LIMIT 1;";
									$conn->QuerySQL($sql2);
									$text = "[system] ระบบทำการลงทะเบียนยูสเซอร์ ".$result["Username"]." เพื่อเข้าใช้งานระบบแจ้งเตือนสำเร็จ";
								}
							}
						}else{
							$text = "[system] ไม่พบค่า ชื่อยูสเซอร์ในระบบ TURAC โปรดตรวจสอบการพิมพ์คำสั่งอีกครั้ง";
						}
						break;
					case "@unreg":
						//ตรวจสอบ Line id มีในระบบหรือไม่
						$sql = "SELECT * FROM ".My_config.".v3_userpriv WHERE sts <> '0' and line_id='$userId';";
						$conn->QuerySQL($sql);
						if($conn->NumSQL()>0){
							$sql2 = "UPDATE ".My_config.".v3_userpriv SET line_id='' WHERE line_id='$userId' and sts <> '0' LIMIT 1;";
							$conn->QuerySQL($sql2);
							$text = "[system] ได้ทำการลบ user นี้ออกจากระบบแจ้งเตือนแล้ว";
						}else{
							$text = "[system] user นี้ไม่มีในระบบแจ้งเตือน";
						}
						break;
					case "@check":
						//ตรวจสอบ Line id มีในระบบหรือไม่
						$sql = "SELECT * FROM ".My_config.".v3_userpriv WHERE sts <> '0' and line_id='$userId' order by userprivid DESC limit 1;";
						$conn->QuerySQL($sql);
						if($conn->NumSQL()>0){
							$result = $conn->FetchSQL();
							$text = "[system] ยูสเซอร์ ".$result["Username"]." ได้ลงทะเบียนในระบบแล้ว";
						}else{
							$text = "[system] ไม่พบยูสเซอร์นี้ในระบบโปรดทำการลงทะเบียนใหม่อีกครั้ง หรือติดต่อเจ้าหน้าที่สำนักวิจัย";
						}
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
				sendMessage($headers,"POST",1,$data);
			}	
		}
	}
	echo "BOT OK";
	$conn->CloseSQL();
	ob_end_flush();
?>
