<?php
	class DBConnect{
		private $HostConn;
		private $UserConn;
		private $PassConn;
		private $DBConn;
		private $DBHandle;
		private $DBResult;
		
		function __construct($dbhost,$dbuser,$dbpwd,$dbname){
			if(isset($dbhost)){
				$this->HostConn = $dbhost;
			}else{
				$this->HostConn = "localhost";
			}
			$this->UserConn = $dbuser;
			$this->PassConn = $dbpwd;
			$this->DBConn = $dbname;
			
			$this->DBHandle = mysqli_connect($this->HostConn,$this->UserConn,$this->PassConn,$this->DBConn) or die("Mysql Connect server error ".mysqli_connect_error());
			if($this->DBHandle){
				mysqli_query($this->DBHandle,"SET NAMES 'UTF8'");
				mysqli_query($this->DBHandle,"SET character_set_results='UTF8'");
				mysqli_query($this->DBHandle,"SET character_set_client='UTF8'");
				mysqli_query($this->DBHandle,"SET character_set_connection='UTF8'");
			}
		}
		
		function DBhandle() {
			return $this->DBHandle;
		}
		
		function Consts() {
			return ($this->DBHandle != ""?"1":"0");
		}
		
		
		function QuerySQL($query){
			$this->DBResult = mysqli_query($this->DBHandle,$query) or die("Query Error ".mysqli_error($this->DBHandle)); 
			return ($this->DBResult?true:false);	
		}
		
		function InsertIdSQL(){
			return mysqli_insert_id($this->DBHandle);
		}
	 
		function FetchSQL() {
			return @mysqli_fetch_array($this->DBResult,MYSQLI_ASSOC);
		}
	 
		function FetchAllSQL() {
			while ($row = @mysqli_fetch_array($this->DBResult,MYSQLI_ASSOC)) {
				$a_rs[] = $row;
			}
			@mysqli_free_result($this->DBResult);
			return $a_rs;
		}
		
		function FetchALLSQL_ASSOC() {
			while ($row = @mysqli_fetch_assoc($this->DBResult)) {
				$a_rs[] = $row;
			}
			@mysqli_free_result($this->DBResult);
			return $a_rs;
		}

		function NumSQL() {
			return mysqli_num_rows($this->DBResult);
		}

		function NumSQL2($query) {
			$this->DBResult = mysqli_query($this->DBHandle,$query) or die("Query Error ".mysqli_error($this->DBHandle)); 
			return mysqli_num_rows($this->DBResult);
		}
		
		function CloseSQL(){
			//if($this->DBResult) {
			//	mysqli_free_result($this->DBResult);
			//}
			mysqli_close($this->DBHandle);
		}
	}
?>