<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$payment_id = $_GET['paymentid'];
	
	$qry = "DELETE FROM tbl_payment WHERE payment_id = '$payment_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your payment!");</script>';
	}else{
		echo '<script>alert("Payment successfully deleted.");</script>';
	}
	echo '<script>window.location="payment_list.php";</script>';
?>