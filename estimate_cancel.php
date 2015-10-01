<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qryestimate = "UPDATE tbl_service_master SET trans_status = '3' WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	echo '<script>alert("Estimate successfully canceled."); window.location="estimate_list.php";</script>';
	exit();
?>