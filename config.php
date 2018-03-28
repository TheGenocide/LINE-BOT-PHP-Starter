<?php
	/// การตั้งค่าเกี่ยวกับ bot ใน LINE Messaging API
	define('CHANNEL_ID','1568582442'); //Channel ID
	define('CHANNEL_SECRET','641262f118c98ac5f497e534bf12119e'); //Channel secret
	define('ACCESS_TOKEN','cJKdyIbmbzVAK91DtllUd4vBBOp7RgvaqxraUkBzRV9CaVgZAey+g4awFInI27+RlBTb9VLJsrpF328chNVoJEgzqRqK1ycKd6JYSXGDOcI8y3w2NLYXYzSnC2z2bcJgGiHzms4ZYHglml8/rDrHCwdB04t89/1O/w1cDnyilFU='); //Channel
	define('URL_REPLY','https://api.line.me/v2/bot/message/reply'); 
	define('URL_PUSH','https://api.line.me/v2/bot/message/push'); 
	define('URL_MULTICAST','https://api.line.me/v2/bot/message/multicast'); 
	
	// ปิดการแจ้ง error
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>