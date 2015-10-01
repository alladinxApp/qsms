<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$wocat_id = $_GET['wocatid'];
	
	if (isset($_POST['update'])){
		$wocat = mysql_real_escape_string(strtoupper($_POST['wocat']));
									  
		$wocat_update = "UPDATE tbl_wocat SET 
				wocat='$wocat' WHERE wocat_id='$wocat_id'  ";
						
		$res = mysql_query($wocat_update) or die("UPDATE WORK ORDER CATGEORY ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your work order category! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Work order category successfully updated.");</script>';
		}
		echo '<script>window.location="wocategory_list.php";</script>';
	}
	
	$qrywocat = "SELECT * FROM v_wocat WHERE wocat_id = '$wocat_id'";
	$reswocat = $dbo->query($qrywocat);
	
	foreach($reswocat as $row){
		$wocat=$row['wocat'] ;
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
	<form method="post" name="wocat_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Work Order Category Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_wocat_id">Work Order Category Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="wocat_id" value="<?=$wocat_id;?>" style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_wocat">Work Order Category:</label></td>
			<td class="input" colspan="3"><input type="text" name="wocat" id="wocat" value="<?=$wocat;?>" style="width:272px"></td>
		</tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="wocategory_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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