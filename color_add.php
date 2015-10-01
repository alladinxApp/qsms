<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$color = strtoupper($_POST['color']);
		$newnum = getNewNum('COLOR');
		$color_insert = "INSERT INTO tbl_color (color_id,color,color_created) VALUES
		('$newnum','$color','$today') ";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'COLOR' ";
		
		$res = mysql_query($color_insert) or die("INSERT COLOR ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your color! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Color successfully saved.");</script>';
		}
		echo '<script>window.location="color_list.php";</script>';
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
	<form method="post" name="color_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_color" name="form_color">
	<legend><p id="title">Vehicle Color Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_colorid">Color Code:</label>
			<td class ="input"><input type="text" name="color_id" id="color_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_color">Vehicle Color:</label></td>
			<td class ="input"><input type="text" name="color" id="color" value="" style="width:272px"> </td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="color_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var color = document.getElementById("color");
			
			if(color.value == ""){
				alert("Color is required! Please enter color.");
				color.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
