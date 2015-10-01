<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$worefno = $_GET['worefno'];
	
	$qryestimate = "UPDATE tbl_service_master SET trans_status = '3' WHERE wo_refno = '$worefno'";
	$resestimate = $dbo->query($qryestimate);
	
	echo '<script>alert("Work Order successfully canceled."); window.location="workorder_list.php";</script>';
	exit();
?>