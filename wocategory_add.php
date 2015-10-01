<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$wocat = strtoupper($_POST['wocat']);
		$newnum = getNewNum('WOCATEGORY');
					   
		$wocat_insert = "INSERT INTO tbl_wocat (wocat_id, wocat, wocat_created) VALUES
		('".$newnum."',
		'".$wocat."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'WOCATEGORY' ";
		
		$res = mysql_query($wocat_insert) or die("INSERT WOCAT ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your work order category! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Work order category successfully saved.");</script>';
		}
		echo '<script>window.location="wocategory_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<body>
	<form method="post" name="wocat_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_wocat" name="form_wocat">
	<legend><p id="title">Work Order Category Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_wocat_id">Work Order Category Code:</label>
			<td class ="input"><input type="text" name="wocat_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_wocat">Work Order Category:</label>
			<td class ="input"><input type="text" name="wocat" id="wocat" value="" style="width:272px"></td>
		</tr>    
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="wocategory_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var wocat = document.getElementById("wocat");
			
			if(wocat.value == ""){
				alert("Work order category is required! Please enter work order category.");
				wocat.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>