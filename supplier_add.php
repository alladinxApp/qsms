<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$sapsuppliercode = $_POST['sap_supplier_code'];
		$suppliername = $_POST['supplier_name'];
		$address = $_POST['address'];
		$contactperson = $_POST['contact_person'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$email = $_POST['email'];
		$tin = $_POST['TIN'];
		$isvat = $_POST['isVat'];
		$newnum = getNewNum('SUPPLIER');
		
		$supplier_insert = "INSERT INTO tbl_suppliers (supplier_code,SAP_supplier_code,supplier_name,address,contact_person,phone,fax,email,TIN,isVat,created_date,status) VALUES 
	 	('".$newnum."',
	 	'".$sapsuppliercode."',
	 	'".$suppliername."',
	 	'".$address."',
	 	'".$contactperson."',
	 	'".$phone."',
	 	'".$fax."',
	 	'".$email."',
	 	'".$TIN."',
	 	'".$isvat."',
	 	'".$today."','1')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'SUPPLIER' ";
		
		$res = mysql_query($supplier_insert) or die("INSERT SUPPLIER ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your supplier! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Supplier successfully saved.");</script>';
		}
		echo '<script>window.location="supplier_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<body>
	<form method="post" name="supplier_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_supplier" name="form_supplier">
	<legend><p id="title">Supplier Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="accessory_id">Supplier Code:</label>
			<td class ="input"><input type="text" name="accessory_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="sap_supplier_code">SAP Supplier Code:</label>
			<td class ="input"><input type="text" name="sap_supplier_code" id="sap_supplier_code" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="supplier_name">Supplier Name:</label>
			<td class ="input"><input type="text" name="supplier_name" id="supplier_name" value="" onBlur="return CurrencyFormatted('access_disc'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="address">Address:</label>
			<td class ="input"><input type="text" name="address" id="address" value="" onBlur="return CurrencyFormatted('access_srp'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="contact_person">Contact Person:</label>
			<td class ="input"><input type="text" name="contact_person" id="contact_person" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="phone">Phone:</label>
			<td class ="input"><input type="text" name="phone" id="phone" value="" style="width:272px"></td>
		</tr> 
		<tr>
			<td class ="label"><label name="v">Fax:</label>
			<td class ="input"><input type="text" name="fax" id="fax" value="" style="width:272px"></td>
		</tr> 
		<tr>
			<td class ="label"><label name="email">Email:</label>
			<td class ="input"><input type="text" name="email" id="email" value="" style="width:272px"></td>
		</tr>   
		<tr>
			<td class ="label"><label name="TIN">TIN:</label>
			<td class ="input"><input type="text" name="TIN" id="TIN" value="" style="width:272px"></td>
		</tr> 
		<tr>
			<td class ="label"><label name="isVat">is Vat:</label>
			<td class ="input"><select name="isVat" id="isVat">
				<option value="0">NO</option>
				<option value="1">YES</option>
			</select></td>
		</tr> 
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="accessory_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var sapsuppliercode = document.getElementById("sap_supplier_code");
			var suppliername = document.getElementById("supplier_name");
			var contactperson = document.getElementById("contact_person");
			var address = document.getElementById("address");
			var phone = document.getElementById("phone");
			var fax = document.getElementById("fax");
			var email = document.getElementById("email");
			var tin = document.getElementById("TIN");
			
			if(sapsuppliercode.value == ""){
				alert("Supplier Code is required! Please enter supplier code.");
				sapsuppliercode.focus();
				return false;
			}else if(suppliername.value == ""){
				alert("Supplier Name is required! Please enter supplier name.");
				suppliername.focus();
				return false;
			}else if(contactperson.value == ""){
				alert("Contact Person is required! Please enter contact person.");
				contactperson.focus();
				return false;
			}else if(address.value == ""){
				alert("Address is required! Please enter address.");
				address.focus();
				return false;
			}else if(phone.value == ""){
				alert("Phone is required! Please enter phone.");
				phone.focus();
				return false;
			}else if(fax.value == ""){
				alert("Fax is required! Please enter fax.");
				fax.focus();
				return false;
			}else if(email.value == ""){
				alert("Email is required! Please enter email.");
				email.focus();
				return false;
			}else if(tin.value == ""){
				alert("TIN is required! Please enter TIN.");
				tin.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
