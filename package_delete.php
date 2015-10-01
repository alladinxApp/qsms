<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$pac_id = $_GET['pacid'];
	
	$qry = "DELETE FROM tbl_package_master WHERE package_id = '$pac_id'";
	$res = mysql_query($qry);

	$qry1 = "DELETE FROM tbl_package_detail WHERE package_id = '$pac_id'";
	$res1 = mysql_query($qry1);
	
	if(!$res || !$res1){
		echo '<script>alert("There has been an error on deleting your package profile!"); window.location="package_list.php";</script>';
		exit();
	}else{
		echo '<script>alert("Package profile successfully deleted."); window.location="package_list.php";</script>';
		exit();
	}
?>