<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$color_id = $_GET['colorid'];
	
	$qry = "DELETE FROM tbl_color WHERE color_id = '$color_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your color!");</script>';
	}else{
		echo '<script>alert("Color successfully deleted.");</script>';
	}
	echo '<script>window.location="color_list.php";</script>';
?>