<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$color_id = $_GET['colorid'];
	
	if (isset($_POST['update'])){
		$color=mysql_real_escape_string(strtoupper($_POST['color']));
					  
		$color_update = "UPDATE tbl_color SET 
		color='$color' where color_id='$color_id'  ";
						
									 
		$res = mysql_query($color_update) or die("UPDATE COLOR ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your color! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Color successfully updated.");</script>';
		}
		echo '<script>window.location="color_list.php";</script>';
		
	}
	
	$qryclr = "SELECT * FROM v_color WHERE color_id = '$color_id'";
	$resclr = $dbo->query($qryclr);
	
	foreach($resclr as $row){
		$color = $row['color'];
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
	<form method="post" name="color_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Vehicle Color Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_color_id">Color Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="color_id" value="<?=$color_id;?>" readonly style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_color">Vehicle Color:</label></td>
			<td class="input" colspan="3"><input type="text" name="color" id="color" value="<?=$color;?>" style="width:272px"></td>
		<tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="color_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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