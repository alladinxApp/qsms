<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$itemtypecode = $_GET['itemtypecode'];
	$qry = "SELECT * FROM tbl_item_type WHERE item_type_code = '$itemtypecode'";
	$result = $dbo->query($qry);
	
	foreach($result as $row){
		$desc = $row['description'];
		$status = $row['status'];
	}
	
	if (isset($_POST['update'])){
		
		$desc = $_POST['description'];
		$status = $_POST['status'];
					   
		$item_type_update = "UPDATE tbl_item_type SET 
			description = '$desc',
			status = '$status'
		WHERE item_type_code = '$itemtypecode'";
		
		$res = mysql_query($item_type_update) or die("UPDATE ITEM TYPE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your Item Type profile! Please double check all the data and Re-update.");</script>';
		}else{
			echo '<script>alert("Item Type profile successfully update.");</script>';
		}
		echo '<script>window.location="item_type_list.php";</script>';
	}
?>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<body>
	<form method="post" name="item_type_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_item_type" name="form_item_type">
	<legend><p id="title">Item Type Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="item_type_code">Item Type Code:</label>
			<td class ="input"><input type="text" value="<?=$itemtypecode;?>" name="item_type_code" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="description">Description:</label>
			<td class ="input"><input type="text" value="<?=$desc;?>" name="description" id="description" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="status">Status:</label>
			<td class ="input"><select name="status" id="status">
				<option value="0" <? if($status == 0){ echo 'selected'; } ?>>Inactive</option>
				<option value="1" <? if($status == 1){ echo 'selected'; } ?>>Active</option>
			</select></td>
		</tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="uom_list.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
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