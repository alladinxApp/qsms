<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$parts_id = $_GET['partsid'];
	
	$qry = "DELETE FROM tbl_parts WHERE parts_id = '$parts_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your parts!");</script>';
	}else{
		echo '<script>alert("Parts successfully deleted.");</script>';
	}
	echo '<script>window.location="parts_list.php";</script>';
?>