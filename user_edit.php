<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}
	
	$username = $_GET['username'];
	$qry = "SELECT * FROM v_users WHERE username = '$username'";
	$result = $dbo->query($qry);
	
	foreach($result as $row){
		$name = $row['name'];
		$status = $row['user_status'];
		$user_type = $row['user_type'];
		$user_image = $row['image'];
	}
	
	if (isset($_POST['update'])){
		$pass = $_POST['txtpassword'];
		$password = generatePassword(strtoupper(trim($pass)));
		$name = strtoupper($_POST['txtname']);
		$status = $_POST['txtuserstatus'];
		//$usertype = $_POST['user_type'];
		$image = $_FILES['txtuserimage']['name'];
		
		if(!empty($pass)){
			$pw = " password = '$password',";
		}else{
			$pw = null;
		}
		if(!empty($image)){
			$upload_path = 'images/users/';
			$ext = substr($image, strpos($image,'.'), strlen($image)-1);
			if(file_exists($upload_path.$image)){
				unlink($upload_path.$image);
			}
			$image = $username.$ext;
			$img = " image = '$image',";
			move_uploaded_file($_FILES['txtuserimage']['tmp_name'],$upload_path . $image);
		}else{
			$img = " image = 'blank_person.jpg',";
		}
									   
		$userupdate = "UPDATE tbl_users 
					SET name = '$name',
						" . $pw . $img . "
						user_status = '$status'
					WHERE username = '$username'";
		
		$res = mysql_query($userupdate) or die("UPDATE USER ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your user profile! Please double check all the data and Re-update.");</script>';
		}else{
			echo '<script>alert("User profile successfully update.");</script>';
		}
		echo '<script>window.location="users_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</script>

</head>
<body>
	<form method="post" name="employee_form" class="form" onSubmit="return ValidateMe();" enctype="multipart/form-data">
	<fieldset form="form_employee" name="form_employee">
	<legend><p id="title">User Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Username:</label>
			<td class ="input"><input type="text" name="txtusername" id="txtusername" value="<?=$username;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Password:</label>
			<td class ="input"><input type="password" name="txtpassword" id="txtpassword" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Name:</label>
			<td class ="input"><input type="text" name="txtname" id="txtname" value="<?=$name;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_image">User Picture:</label>
			<td class ="input"><input type="file" name="txtuserimage" id="txtuserimage" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_image">User Status:</label>
			<td class ="input"><select name="txtuserstatus" id="txtuserstatus">
				<option value="1" <? if($status == 1){ echo 'selected'; }?>>Active</option>
				<option value="0" <? if($status == 2){ echo 'selected'; }?>>Inactive</option>
			</select></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="user_edit.php?username=<?=$username;?>"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var username = document.getElementById("username");
			var password = document.getElementById("password");
			var name = document.getElementById("name");
			
			if(username.value == ""){
				alert("Username is required! Please enter username.");
				username.focus();
				return false;
			}else if(password.value == ""){
				alert("Password is required! Please enter user password.");
				password.focus();
				return false;
			}else if(name.value == ""){
				alert("Name is required! Please enter name.");
				name.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
