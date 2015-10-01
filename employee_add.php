<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}

	if (isset($_POST['save'])){
		$employee = strtoupper($_POST['employee']);
		$position = $_POST['position'];
		$image = $_FILES['emp_image']['name'];
		$newnum = getNewNum('EMPLOYEE');
		
		if(!empty($image)){
			$upload_path = 'images/employees/';
			$ext = substr($image, strpos($image,'.'), strlen($image)-1);
			if(file_exists($upload_path.$image)){
				unlink($upload_path.$image);
			}
			$img = $newnum.$ext;
			move_uploaded_file($_FILES['emp_image']['tmp_name'],$upload_path . $img);
		}else{
			$img = 'no-image.png';
		}
		
		$employee_insert = "INSERT INTO tbl_employee (emp_id,employee,position, emp_image, emp_created) VALUES
		('".$newnum."',
		'".$employee."',
		'".$position."',
		'".$img."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'EMPLOYEE' ";
		
		$res = mysql_query($employee_insert) or die("INSERT EMPLOYEE ".mysql_error());

		if(!$res){
			echo '<script>alert("There has been an error on saving your employee profile! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Employee profile successfully saved.");</script>';
		}
		echo '<script>window.location="employee_list.php";</script>';

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
	<legend><p id="title">Employee Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Employee Code:</label>
			<td class ="input"><input type="text" name="emp_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_employee">Employee Name:</label>
			<td class ="input"><input type="text" name="employee" id="employee" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_employee">Employee Position:</label>
			<td class ="input"><input type="text" name="position" id="position" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_status">Employee Status:</label>
			<td class="input"><input type="radio" name="emp_status" id="emp_status" value="Active" checked>Active <input type="radio" name="emp_status" id="emp_status" value="Inactive">Inactive</td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_emp_image">Employee Picture:</label>
			<td class ="input"><input type="file" name="emp_image" id="emp_image" value="" style="width:272px"></td>
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
			var employee = document.getElementById("employee");
			
			if(employee.value == ""){
				alert("Employee name is required! Please enter employee name.");
				employee.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
