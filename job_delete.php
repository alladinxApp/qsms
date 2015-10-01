<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$job_id = $_GET['jobid'];
	
	$qry = "DELETE FROM tbl_job WHERE job_id = '$job_id'";
	$res = mysql_query($qry);
	
	if(!$res){
		echo '<script>alert("There has been an error on deleting your job!");</script>';
	}else{
		echo '<script>alert("Job successfully deleted.");</script>';
	}
	echo '<script>window.location="job_list.php";</script>';
?>