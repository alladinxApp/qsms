<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$year = $_POST['year'];
		$newnum = getNewNum('YEAR');
		$year_insert = "INSERT INTO tbl_year (year_id, year) VALUES
		('".$newnum."',
		'".$year."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'YEAR' ";
		
		$res = mysql_query($year_insert) or die("INSERT YEAR ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your year! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Year successfully saved.");</script>';
		}
		echo '<script>window.location="year_list.php";</script>';
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
	<form method="post" name="year_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_year" name="form_year">
	<legend><p id="title">Year Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_yearid">Year Code:</label>
			<td class ="input"><input type="text" name="year_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><input type="text" name="year" id="year" value="" style="width:272px"> </td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="year_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
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