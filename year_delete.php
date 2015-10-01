<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$year_id = $_GET['yearid'];
	
	$qry = "DELETE FROM tbl_year WHERE year_id = '$year_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your year!");</script>';
	}else{
		echo '<script>alert("Year successfully deleted.");</script>';
	}
	echo '<script>window.location="year_list.php";</script>';
?>