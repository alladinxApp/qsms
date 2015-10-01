<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$idle_id = $_GET['idleid'];
	
	$qry = "DELETE FROM tbl_idle WHERE idle_id = '$idle_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your idle!");</script>';
	}else{
		echo '<script>alert("Idle successfully deleted.");</script>';
	}
	echo '<script>window.location="idle_list.php";</script>';
?>