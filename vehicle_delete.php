<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$vehicle_id = $_GET['vehicleid'];
	$qry = "DELETE FROM tbl_vehicleinfo WHERE vehicle_id = '$vehicle_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your customers vehicle profile!");</script>';
	}else{
		echo '<script>alert("Customers vehicle profile successfully deleted.");</script>';
	}
	echo '<script>window.location="vehicle_list.php";</script>';
?>