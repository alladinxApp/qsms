<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$payment_id =$_REQUEST['paymentid'];
	
	if (isset($_POST['update'])){
		$payment = mysql_real_escape_string(strtoupper($_POST['payment']));
									  
		$payment_update = "UPDATE tbl_payment SET 
				payment='$payment' WHERE payment_id = '$payment_id'";
						
									 
		$res = mysql_query($payment_update) or die("UPDATE PAYMENT TYPE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your payment! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Payment successfully updated.");</script>';
		}
		echo '<script>window.location="payment_list.php";</script>';
	}

	$qrypay = "SELECT * FROM v_payment WHERE payment_id  = '$payment_id'";
	$respay = $dbo->query($qrypay);
	
	foreach($respay as $row){
		$payment = $row['payment'] ;
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
	<form method="post" name="payent_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Idle Type Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_payment_id">Payment Mode Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="payment_id" value="<?=$payment_id;?>" readonly style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_payment">Payment Description:</label></td>
			<td class="input" colspan="3"><input type="text" name="payment" id="payment" value="<?=$payment;?>" style="width:272px"></td>
		<tr> 
    	
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="payment_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
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