<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$payment = mysql_real_escape_string(strtoupper($_POST['payment']));
		$newnum = getNewNum('PAYMENT');
					   
		$payment_insert = "INSERT INTO tbl_payment (payment_id, payment, payment_created) VALUES
		('".$newnum."',
		'".$payment."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'PAYMENT' ";
		
		$res = mysql_query($payment_insert) or die("INSERT PAYMENT ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your payment! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Payment successfully saved.");</script>';
		}
		echo '<script>window.location="payment_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  

</head>
<body>
	<form method="post" name="payment_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_payment" name="form_payment">
	<legend><p id="title">Payment Mode Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_payment_id">Payment Mode Code:</label>
			<td class ="input"><input type="text" name="payment_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_payment">Payment Description:</label>
			<td class ="input"><input type="text" name="payment" id="payment" value="" style="width:272px"></td>
		</tr>    
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="payment_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var payment = document.getElementById("payment");
			
			if(payment.value == ""){
				alert("Payment is required! Please enter payment.");
				payment.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>