<?php
	require_once("conf/db_connection.php");

	if(!isset($_SESSION)){
		session_start();
	}

	$emp_id = $_GET['empid'];
	$qry = "SELECT * FROM tbl_employee WHERE emp_id = '$emp_id'";
	$result = $dbo->query($qry);
	
	foreach($result as $row){
		$emp_id = $row['emp_id'];
		$employee = $row['employee'];
		$position = $row['position'];
		$emp_status = $row['emp_status'];
		$emp_image = $row['emp_image'];
	}
	
	if (isset($_POST['update'])){
		$employee = strtoupper($_POST['employee']);
		$position = $_POST['position'];
		$emp_status = $_POST['emp_status'];
		$image = $_FILES['emp_image']['name'];
		
		if(!empty($image)){
			$upload_path = 'images/employees/';
			$ext = substr($image, strpos($image,'.'), strlen($image)-1);
			if(file_exists($upload_path.$image)){
				unlink($upload_path.$image);
			}
			$image = $emp_id.$ext;
			$img = " emp_image = '$image',";
			move_uploaded_file($_FILES['emp_image']['tmp_name'],$upload_path . $image);
		}else{
			$img = 'no-image.png';
		}
		
		$employee_update = "UPDATE tbl_employee 
					SET employee = '$employee',
						position = '$position',
						". $img . "
						emp_status = '$emp_status'
					WHERE emp_id = '$emp_id'";
		
		$res = mysql_query($employee_update) or die("UPDATE EMPLOYEE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your employee profile! Please double check all the data and Re-update.");</script>';
		}else{
			echo '<script>alert("Employee profile successfully update.");</script>';
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
				<td class ="input"><input type="text" name="emp_id" value="<?=$emp_id;?>" readonly style="width:272px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="lbl_employee">Employee Name:</label>
				<td class ="input"><input type="text" name="employee" id="employee" value="<?=$employee;?>" style="width:272px"></td>
			</tr>
			<tr>
			<td class ="label"><label name="lbl_employee">Employee Position:</label>
			<td class ="input"><input type="text" name="position" id="position" value="<?=$position;?>" style="width:272px"></td>
		</tr>
			<tr>
				<td class ="label"><label name="lbl_emp_status">Employee Status:</label>
				<td class="input">
					<input type="radio" name="emp_status" value="1" <? if($emp_status == 1){ echo 'checked'; }?>>Active 
					<input type="radio" name="emp_status" value="0" <? if($emp_status == 0){ echo 'checked'; }?>>Inactive</td>
			</tr>    
			<tr>
				<td class ="label"><label name="lbl_emp_image">Employee Picture:</label>
				<td class ="input"><input type="file" name="emp_image" value="<?=$emp_image;?>" style="width:272px"></td>
			</tr>    

		</table>
		</fieldset>
		<p class="button">
			<input type="submit" value="" name="update" />
			<a href ="employee_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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