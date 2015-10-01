<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$cust_id = $_GET['custid'];
	$qry = "DELETE FROM tbl_customer WHERE cust_id = '$cust_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your customer profile!");</script>';
	}else{
		echo '<script>alert("Customer profile successfully deleted.");</script>';
	}
	echo '<script>window.location="customer_list.php";</script>';
?>