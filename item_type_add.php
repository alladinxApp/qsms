<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$desc = $_POST['description'];
		$newnum = getNewNum('ITEM_TYPE');
		
		$item_type_insert = "INSERT INTO tbl_item_type (item_type_code,description,created_date) VALUES 
	 	('".$newnum."',
	 	'".$desc."',
	 	'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'ITEM_TYPE' ";
		
		$res = mysql_query($item_type_insert) or die("INSERT ITEM TYPE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your Item Type! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Item Type successfully saved.");</script>';
		}
		echo '<script>window.location="item_type_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<body>
	<form method="post" name="item_type_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_item_type" name="form_item_type">
	<legend><p id="title">Item Type Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="item_type_code">Item Type Code:</label>
			<td class ="input"><input type="text" name="item_type_code" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
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
