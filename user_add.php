<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}

	if (isset($_POST['save'])){
		$username = $_POST['txtusername'];
		$pass = strtoupper($_POST['txtpassword']);
		$password = generatePassword($pass);
		$name = strtoupper($_POST['txtname']);
		$image = $_FILES['txtuserimage']['name'];

		if(!empty($pass)){
			$pw = $password;
		}else{
			$pw = strtoupper($username);
		}
		if(!empty($image)){
			$upload_path = 'images/users/';
			$ext = substr($image, strpos($image,'.'), strlen($image)-1);
			if(file_exists($upload_path.$image)){
				unlink($upload_path.$image);
			}
			$img = $username.$ext;
			move_uploaded_file($_FILES['txtuserimage']['tmp_name'],$upload_path . $img);
		}else{
			$img = 'no-image.png';
		}
		
		$users_insert = "INSERT INTO tbl_users (username,password,name,image,user_created) VALUES
		('".$username."',
		'".$pw."',
		'".$name."',
		'".$img."',
		'".$today."')";
		
		$res = mysql_query($users_insert) or die("INSERT USERS ".mysql_error());

		if(!$res){
			echo '<script>alert("There has been an error on saving your users profile! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("User profile successfully saved.");</script>';
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
			<td class ="input"><input type="text" name="txtusername" id="txtusername" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Password:</label>
			<td class ="input"><input type="password" name="txtpassword" id="txtpassword" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Name:</label>
			<td class ="input"><input type="text" name="txtname" id="txtname" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_image">User Picture:</label>
			<td class ="input"><input type="file" name="txtuserimage" id="txtuserimage" value="" style="width:272px"></td>
		</tr>    
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="employee_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
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
