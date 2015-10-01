<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$wocat_id = $_GET['wocatid'];
	
	$qry = "DELETE FROM tbl_wocat WHERE wocat_id = '$wocat_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your work order category!");</script>';
	}else{
		echo '<script>alert("Work order category successfully deleted.");</script>';
	}
	echo '<script>window.location="wocategory_list.php";</script>';
?>