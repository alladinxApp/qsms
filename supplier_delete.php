<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$suppliercode = $_GET['suppliercode'];
	$qry = "DELETE FROM tbl_suppliers WHERE supplier_code = '$suppliercode'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your supplier profile!");</script>';
	}else{
		echo '<script>alert("Supplier profile successfully deleted.");</script>';
	}
	echo '<script>window.location="supplier_list.php";</script>';
?>