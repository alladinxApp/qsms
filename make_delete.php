<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$make_id = $_GET['makeid'];
	
	$qry = "DELETE FROM tbl_make WHERE make_id = '$make_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your make!");</script>';
	}else{
		echo '<script>alert("Make successfully deleted.");</script>';
	}
	echo '<script>window.location="make_list.php";</script>';
?>