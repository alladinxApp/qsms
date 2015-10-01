<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$material_id = $_GET['materialid'];
	
	$qry = "DELETE FROM tbl_material WHERE material_id = '$material_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your material!");</script>';
	}else{
		echo '<script>alert("Material successfully deleted.");</script>';
	}
	echo '<script>window.location="material_list.php";</script>';
?>