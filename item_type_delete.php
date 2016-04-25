<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$itemtypecode = $_GET['itemtypecode'];
	$qry = "DELETE FROM tbl_item_type WHERE item_type_code = '$itemtypecode'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your Item Type profile!");</script>';
	}else{
		echo '<script>alert("Item Type profile successfully deleted.");</script>';
	}
	echo '<script>window.location="item_type_list.php";</script>';
?>