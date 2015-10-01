<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$idle_name = strtoupper($_POST['idle_name']);
		$newnum = getNewNum('IDLE');
	   
		$idle_insert = "INSERT INTO tbl_idle (idle_id, idle_name, idle_created) VALUES
		('".$newnum."',
		'".$idle_name."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'IDLE' ";
		
		$res = mysql_query($idle_insert) or die("INSERT IDLE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your idle! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Idle successfully saved.");</script>';
		}
		echo '<script>window.location="idle_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  

</head>
<body>
	<form method="post" name="color_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_idle" name="form_idle">
	<legend><p id="title">Idle Type Masterfile</p></legend>
	<table>
	<tr>
	<td class ="label"><label name="lbl_idle_id">Idle Code:</label>
	<td class ="input"><input type="text" name="idle_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
	</tr>
	<tr>
	<td class ="label"><label name="lbl_idle_name">Idle Task Description:</label></td>
	<td class ="input"><input type="text" name="idle_name" id="idle_name" value="" style="width:272px"> </td>
	</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="idle_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var idle_name = document.getElementById("idle_name");
			
			if(idle_name.value == ""){
				alert("Idle task description is required! Please enter idle task description.");
				idle_name.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>