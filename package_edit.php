<?php
	require_once("conf/db_connection.php");

	if(!isset($_SESSION)){
		session_start();
	}

	$pac_id = $_GET['pacid'];
	$qry = "SELECT * FROM tbl_package_master WHERE package_id = '$pac_id'";
	$result = $dbo->query($qry);
	
	foreach($result as $row){
		$pac_id = $row['package_id'];
		$package = $row['package_name'];
		$status = $row['status'];
	}
	
	if (isset($_POST['update'])){
		$package = $_POST['package'];
		$status = $_POST['status'];
		
		$package_update = "UPDATE tbl_package_master
					SET package_name = '$package',
						status = '$status'
					WHERE package_id = '$pac_id'";
		
		$res = mysql_query($package_update) or die("UPDATE PACKAGE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your package profile! Please double check all the data and Re-update.");</script>';
		}else{
			echo '<script>alert("Package profile successfully update.");</script>';
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
				<td class ="input"><input type="text" name="pac_id" value="<?=$pac_id;?>" readonly style="width:272px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="lbl_employee">Package Name:</label>
				<td class ="input"><input type="text" name="package" id="package" value="<?=$package;?>" style="width:272px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="lbl_emp_status">Status:</label>
				<td class="input">
					<input type="radio" name="status" value="1" <? if($status == 1){ echo 'checked'; }?>>Active 
					<input type="radio" name="status" value="0" <? if($status == 0){ echo 'checked'; }?>>Inactive</td>
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