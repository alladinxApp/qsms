<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$emp_id = $_GET['empid'];
	
	$chkqry = "SELECT * FROM v_employee WHERE emp_id = '$emp_id'";
	$chkres = $dbo->query($chkqry);
	$cnt = 0;
	foreach($chkres as $chkrow){
		$image = $chkrow['emp_image'];
		$cnt++;
	}

	if($cnt > 0){
		$qry = "DELETE FROM tbl_employee WHERE emp_id = '$emp_id'";
		$res = mysql_query($qry);
		
		if(!$res){
			echo '<script>alert("There has been an error on deleting your employee profile!"); window.location="employee_list.php";</script>';
			exit();
		}else{
			if(file_exists('images/employees/'.$image)){
				unlink('images/employees/'.$image);
			}
			echo '<script>alert("Employee profile successfully deleted."); window.location="employee_list.php";</script>';
			exit();
		}
	}else{
		echo '<script>alert("Employee already not existed!"); window.location="employee_list.php";</script>';
		exit();
	}
?>