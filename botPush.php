<?php
	ob_start();
	include_once "modules/class.conn.php";
	include_once "includes/v3_config.inc.php";
	include_once "includes/v3_line_config.php";
	include_once "includes/v3_line_core.php";
	
	function linePush($user,$text){
		//$user ชื่อผู้ใช้ในระบบ
		//$text ข้อความที่ต้องการส่ง (ไม่เกิน 2000 ตัวอักษร)
		
		if($user <> "" && $text <> ""){
			$conn = new DBConnect(DBBOT,DBBOT_USER,DBBOT_PW,My_config);
			$dbHandle = $conn->DBhandle();//connect sql
			
			//Default Var
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . ACCESS_TOKEN);
			$sql = "";
			$user = mysqli_real_escape_string($dbHandle,trim($user));
			$text = mysqli_real_escape_string($dbHandle,trim($text));
			
			//ตรวจหา user ในระบบ
			$sql = "SELECT line_id FROM ".My_config.".v3_userpriv WHERE sts <> '0' and workFlg = 'O' and Username = '$user' LIMIT 1;";
			$conn->QuerySQL($sql);
			if($conn->NumSQL()>0){
				$result = $conn->FetchSQL();
				if($result["line_id"] <> ""){
					$messages = "";
					$data = "";
					$round = 0;
					$len = 0;
					$line_limit = 2000; //จำนวนตัวอักษรสูงสุดที่สามารถส่งได้ในแต่ละครั้ง
					
					$round = ceil($len/$line_limit);
					
					if($round == 1){
						// Build message to reply back
						$messages = ['type' => 'text','text' => $text];
						// Make a POST Request to Messaging API to reply to sender		 	
						$data = ['to' => $result["line_id"],'messages' => [$messages],];	
						sendMessage($headers,"POST",2,$data);						
					}else{
						//แบ่งการส่งตามจำนวนตัวอักษรสูงสุด
						$i = 0;
						$start = 0;
						$end = 0;
						while($i < $round){
							if($round == 1){ //หารอบแรก
								$start = 1;
								$end = $len;
							}else{
								$start = ($i * $line_limit) + 1;
								if($i == ($round - 1)){ //หารอบสุดท้าย
									$end = $len;
								}else{
									$end = (($i + 1) * $line_limit);
								}
							}
							
							// Build message to reply back
							$messages = ['type' => 'text','text' => substr($text,$start,$end)];
							// Make a POST Request to Messaging API to reply to sender		 	
							$data = ['to' => $result["line_id"],'messages' => [$messages],];	
							sendMessage($headers,"POST",2,$data);	
							$i++;
							sleep(2);
						}
					}
					return "ส่งสำเร็จ";
				}else{
					return "ไม่พบ Line id ของ user : $user";
				}
			}else{
				return "ไม่พบ user : $user นี้ในระบบ";
			}
			$conn->CloseSQL();
		}else{
			return "Missing parameter";
		}
	}
	ob_end_flush();
?>