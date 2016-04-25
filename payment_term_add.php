<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$desc = $_POST['description'];
		$newnum = getNewNum('PAYMENT_TERM');
		
		$payment_term_insert = "INSERT INTO tbl_payment_term (payment_term_code,description,created_date) VALUES 
	 	('".$newnum."',
	 	'".$desc."',
	 	'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'PAYMENT_TERM' ";
		
		$res = mysql_query($payment_term_insert) or die("INSERT PAYMENT TERM ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your payment term! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Payment term successfully saved.");</script>';
		}
		echo '<script>window.location="payment_term_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<body>
	<form method="post" name="payment_term_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_payment_term" name="form_payment_term">
	<legend><p id="title">Payment Term Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="payment_term_code">Payment Term Code:</label>
			<td class ="input"><input type="text" name="payment_term_code" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="description">Description:</label>
			<td class ="input"><input type="text" name="description" id="description" value="" style="width:272px"></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="payment_term_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
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
