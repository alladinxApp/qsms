<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$year_id =$_GET['yearid'];

	$qryyr = "SELECT * FROM v_year WHERE year_id  = '$year_id'";
	$resyr = $dbo->query($qryyr);
	
	foreach($resyr as $row){
		$year = $row['year'] ;
	}
		
	if (isset($_POST['update'])){
		$year = mysql_real_escape_string($_POST['year']);
									  
		$year_update = "UPDATE tbl_year SET 
			year='$year' WHERE year_id = '$year_id'";

		$res = mysql_query($year_update) or die("UPDATE YEAR ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your year! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Year successfully updated.");</script>';
		}
		echo '<script>window.location="year_list.php";</script>';
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
	<form method="post" name="year_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Year Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_year_id">Year Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="year_id" value="<?=$year_id;?>" readonly style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_year">Year:</label></td>
			<td class="input" colspan="3"><input type="text" name="year" id="year" value="<?=$year;?>" style="width:272px"></td>
		</tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="year_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var year = document.getElementById("year");
			
			if(year.value == ""){
				alert("Year is required! Please enter year.");
				year.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>