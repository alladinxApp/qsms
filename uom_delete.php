<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$uomcode = $_GET['uomcode'];
	$qry = "DELETE FROM tbl_uom WHERE uom_code = '$uomcode'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your UOM profile!");</script>';
	}else{
		echo '<script>alert("UOM profile successfully deleted.");</script>';
	}
	echo '<script>window.location="uom_list.php";</script>';
?>