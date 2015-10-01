<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}

	if (isset($_POST['save'])){
		$package = $_POST['package'];
		$newnum = getNewNum('PACKAGE');
		
		$employee_insert = "INSERT INTO tbl_package_master (package_id,package_name,created_by,created_date) VALUES
		('".$newnum."',
		'".$package."',
		'".$_SESSION['username']."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'PACKAGE' ";
		
		$res = mysql_query($employee_insert) or die("INSERT EMPLOYEE ".mysql_error());

		if(!$res){
			echo '<script>alert("There has been an error on saving your package profile! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Package profile successfully saved.");</script>';
		}
		echo '<script>window.location="package_list.php";</script>';

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
	<legend><p id="title">Package Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Package Code:</label>
			<td class ="input"><input type="text" name="pac_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_employee">Package Name:</label>
			<td class ="input"><input type="text" name="package" id="package" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_status">Status:</label>
			<td class="input"><input type="radio" name="status" id="status" value="Active" checked>Active <input type="radio" name="status" id="status" value="Inactive">Inactive</td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="package_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var package = document.getElementById("package");
			
			if(package.value == ""){
				alert("Package name is required! Please enter package name.");
				package.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>