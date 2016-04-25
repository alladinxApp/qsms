<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$paymenttermcode = $_GET['paymenttermcode'];
	$qry = "DELETE FROM tbl_payment_term WHERE payment_term_code = '$paymenttermcode'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your Payment Term profile!");</script>';
	}else{
		echo '<script>alert("Payment Term profile successfully deleted.");</script>';
	}
	echo '<script>window.location="payment_term_list.php";</script>';
?>