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
			
			//ตรวจหา user ในระบบ
			
			
			sendMessage($headers,"POST",2,'',$data);
			$conn->CloseSQL();
		}else{
			return "Missing parameter";
		}
	}
	ob_end_flush();
?>