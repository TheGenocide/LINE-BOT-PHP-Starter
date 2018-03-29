<?php
	include_once "botPush.php";
	
	$user = "R01233";
	//$text = "[ทดสอบแจ้งเตือน] ทดสอบแจ้งเตือน $user \n ==============";
	$text = "[ทดสอบแจ้งเตือน] ทดสอบแจ้งเตือน $user กำหนดส่งงานวันที 10/04/2018";
	echo linePush($user,$text);
?>