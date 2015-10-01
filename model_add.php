<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$model = strtoupper($_POST['model']);
		$variant = $_POST['model'];
		$variantdesc = $_POST['variantdesc'];
		$newnum = getNewNum('MODEL');
		
		$model_insert = "INSERT INTO tbl_model (model_id, model, variant, variantdesc, model_created) VALUES
		('".$newnum."',
		'".$model."',
		'".$variant."',
		'".$variantdesc."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'MODEL' ";
		
		$res = mysql_query($model_insert) or die("INSERT MODEL ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your model! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Model successfully saved.");</script>';
		}
		echo '<script>window.location="model_list.php";</script>';
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
	<form method="post" name="model_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_model" name="form_model">
	<legend><p id="title">Vehicle Model Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_modelid">Vehicle Model Code:</label>
			<td class ="input"><input type="text" name="model_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_model">Vehicle Model:</label>
			<td class ="input"><input type="text" name="model" id="model" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><input type="text" name="variant" id="variant" value="" style="width:272px"> </td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_variantdesc">Description:</label></td>
			<td class ="input"><input type="text" name="variantdesc" id="variantdesc" value="" style="width:272px"> </td>
		</tr>
	</table>
	</fieldset>

	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="model_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var model = document.getElementById("model");
			var variant = document.getElementById("variant");
			var variantdesc = document.getElementById("variantdesc");
			
			if(model.value == ""){
				alert("Model is required! Please enter model.");
				model.focus();
				return false;
			}else if(variant.value == ""){
				alert("Variant is required! Please enter variant.");
				variant.focus();
				return false;
			}else if(variantdesc.value == ""){
				alert("Variant description is required! Please enter variant description.");
				variantdesc.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>