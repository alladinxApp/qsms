<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$itemcode = $_GET['itemcode'];
	$qry = "DELETE FROM tbl_items WHERE item_code = '$itemcode'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your Item profile!");</script>';
	}else{
		echo '<script>alert("Item profile successfully deleted.");</script>';
	}
	echo '<script>window.location="items_list.php";</script>';
?>