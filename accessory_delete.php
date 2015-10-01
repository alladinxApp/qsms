<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$accessory_id = $_GET['accessoryid'];
	
	$qry = "DELETE FROM tbl_accessory WHERE accessory_id = '$accessory_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your accessory!");</script>';
	}else{
		echo '<script>alert("Accessory successfully deleted.");</script>';
	}
	echo '<script>window.location="accessory_list.php";</script>';
?>