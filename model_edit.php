<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$model_id = $_GET['modelid'];
	
	$qrymdl = "SELECT * FROM v_model WHERE model_id = '$model_id'";
	$resmdl = $dbo->query($qrymdl);
	
	foreach($resmdl as $row){
		$model = $row['model'];
		$variant = $row['variant'];
		$variantdesc = $row['variantdesc'];
	}
	
	if (isset($_POST['update'])){
		$model=mysql_real_escape_string(strtoupper($_POST['model']));
		$variant=mysql_real_escape_string($_POST['variant']);
		$variantdesc=mysql_real_escape_string($_POST['variantdesc']);
					  
		$model_update = "UPDATE tbl_model SET 
				model='$model',
				variant='$variant', 
				variantdesc='$variantdesc' WHERE model_id='$model_id'  ";
						
									 
		$res = mysql_query($model_update) or die("UPDATE MODEL ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your model! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Model successfully updated.");</script>';
		}
		echo '<script>window.location="model_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms_edit.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>

</head>
<body>
	<form method="post" name="model_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Vehicle Model Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_madel_id">Vehicle Model Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="model_id" value="<?=$model_id;?>" style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_model">Vehicle Model:</label></td>
			<td class="input" colspan="3"><input type="text" name="model" value="<?=$model;?>" style="width:272px"></td>
		<tr> 
		
		<tr>
			<td class="label"><label name="lbl_variant">Variant:</label></td>
			<td class="input" colspan="3"><input type="text" name="variant" value="<?=$variant;?>" style="width:272px"></td>
		<tr> 
		
		<tr>
			<td class="label"><label name="lbl_variantdesc">Description:</label></td>
			<td class="input" colspan="3"><input type="text" name="variantdesc" value="<?=$variantdesc;?>" style="width:272px"></td>
		<tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="model_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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
