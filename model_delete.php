<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$model_id = $_GET['modelid'];
	
	$qry = "DELETE FROM tbl_model WHERE model_id = '$model_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your model!");</script>';
	}else{
		echo '<script>alert("Model successfully deleted.");</script>';
	}
	echo '<script>window.location="model_list.php";</script>';
?>