<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$idle_id =$_GET['idleid'];
	
	if (isset($_POST['update'])){
		$idle_id=strtoupper($_POST['idle_id']);
		$idle_name=mysql_real_escape_string($_POST['idle_name']);
									  
		$idle_update = "UPDATE tbl_idle SET 
				idle_name='$idle_name' WHERE idle_id='$idle_id'  ";			
									 
		$res = mysql_query($idle_update) or die("UPDATE IDLE TASK ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your idle! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Idle successfully updated.");</script>';
		}
		echo '<script>window.location="idle_list.php";</script>';
	}
	$qryidle = "SELECT * FROM v_idle WHERE idle_id  = '$idle_id'";
	$residle = $dbo->query($qryidle);
	
	foreach($residle as $row){
		$idle_name = $row['idle_name'] ;
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
	<form method="post" name="idle_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Idle Type Masterfile</p></legend>
	<br />
	<table>
	<tr>
	<td class="label"><label name="lbl_idle_id">Idle Code:</label></td>
	<td class="input" colspan="3"><input type="text" name="idle_id" value="<?=$idle_id;?>" readonly style="width:272px"></td>
	<td>
	</tr>
	
	<tr>
	<td class="label"><label name="lbl_idle">Idle Task Description:</label></td>
	<td class="input" colspan="3"><input type="text" name="idle_name" id="idle_name" value="<?=$idle_name;?>" style="width:272px"></td>
	<tr> 
    	
	</table>
	</fieldset>
	
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="idle_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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