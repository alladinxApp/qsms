<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$username = $_GET['username'];
	
	$chkqry = "SELECT * FROM v_users WHERE username = '$username'";
	$chkres = $dbo->query($chkqry);
	$cnt = 0;
	foreach($chkres as $chkrow){
		$image = $chkrow['image'];
		$cnt++;
	}

	if($cnt > 0){
		$qry = "DELETE FROM tbl_users WHERE username = '$username'";
		$res = $dbo->query($qry);

		$qryusraccss = "DELETE FROM tbl_user_access WHERE user_id = '$username'";
		$resusraccss = $dbo->query($qryusraccss);
		
		if(!$res){
			echo '<script>alert("There has been an error on deleting your users profile!"); window.location="users_list.php";</script>';
			exit();
		}else{
			if(file_exists('images/users/'.$image)){
				unlink('images/users/'.$image);
			}
			echo '<script>alert("User profile successfully deleted."); window.location="users_list.php";</script>';
			exit();
		}
	}else{
		echo '<script>alert("User already not existed!"); window.location="users_list.php";</script>';
		exit();
	}
?>