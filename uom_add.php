<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$desc = $_POST['description'];
		$newnum = getNewNum('UOM');
		
		$uom_insert = "INSERT INTO tbl_uom (uom_code,description,created_date) VALUES 
	 	('".$newnum."',
	 	'".$desc."',
	 	'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'UOM' ";
		
		$res = mysql_query($uom_insert) or die("INSERT UOM ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your UOM! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("UOM successfully saved.");</script>';
		}
		echo '<script>window.location="uom_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<body>
	<form method="post" name="uom_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_uom" name="form_uom">
	<legend><p id="title">UOM Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="uom_code">UOM Code:</label>
			<td class ="input"><input type="text" name="uom_code" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="description">Description:</label>
			<td class ="input"><input type="text" name="description" id="description" value="" style="width:272px"></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="uom_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var desc = document.getElementById("description");
			
			if(desc.value == ""){
				alert("Description is required! Please enter description.");
				desc.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
