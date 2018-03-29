<?php
	ob_start();
	include_once "includes/v3_line_config.php";
	include_once "includes/v3_line_core.php";
	
	//Default Var
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . ACCESS_TOKEN);
	
	//////////////////////
	
	
	ob_end_flush();
?>