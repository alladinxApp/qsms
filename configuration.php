<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	chkMenuAccess('configuration',$_SESSION['username'],'settings.php');
	$sql_config = "SELECT * FROM v_configuration";
	$qry_config = mysql_query($sql_config);

	while($row = mysql_fetch_array($qry_config)){
		if($row['config_type'] == 'vat_value'){
			$vat_id = $row['id'];
			$vat_value = $row['value'];
		}
	}
	if(isset($_POST['update']) && !empty($_POST['update']) && $_POST['update'] == 1){
		
		$vat_val = $_POST['txtVat'];
		$sql_vat = "UPDATE tbl_configuration SET value = '$vat_val' WHERE id = '$vat_id'";
		$qry_vat = mysql_query($sql_vat);

		echo '<script>alert("Configuration successfully updated.");</script>';
		echo '<script>window.location="configuration.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />

</head>
<body>
	<form method="post" name="customer_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_customer" name="form_customer">
	<legend>
	<p id="title">Configuration</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_cust_id">Vat:</label>
			<td class ="input"><input type="text" name="txtVat" id="txtVat" value="<?=$vat_value;?>" style="width:272px"></td>
		</tr>
		
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="supplier_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	<input type="hidden" name="update" id="update" value="1" />
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var lastname = document.getElementById("lastname");
			var firstname = document.getElementById("firstname");
			var middlename = document.getElementById("middlename");
			var address = document.getElementById("address");
			var city = document.getElementById("city");
			var province = document.getElementById("province");
			var zipcode = document.getElementById("zipcode");
			var birthday = document.getElementById("birthday");
			var tin = document.getElementById("tin");
			var company = document.getElementById("company");
			var source = document.getElementById("source");
			var email = document.getElementById("email");
			var landline = document.getElementById("landline");
			var fax = document.getElementById("fax");
			var mobile = document.getElementById("mobile");
			
			if(lastname.value == ""){
				alert("Lastname is required! Please enter lastname.");
				lastname.focus();
				return false;
			}else if(firstname.value == ""){
				alert("Firstname is required! Please enter firstname.");
				firstname.focus();
				return false;
			}else if(middlename.value == ""){
				alert("Middlename is required! Please enter middlename.");
				middlename.focus();
				return false;
			}else if(address.value == ""){
				alert("Address is required! Please enter address.");
				address.focus();
				return false;
			}else if(city.value == ""){
				alert("City is required! Please enter city.");
				city.focus();
				return false;
			}else if(province.value == ""){
				alert("Province is required! Please enter province.");
				province.focus();
				return false;
			}else if(zipcode.value == ""){
				alert("Zip Code is required! Please enter zipcode.");
				zipcode.focus();
				return false;
			}else if(birthday.value == ""){
				alert("Birthday is required! Please enter birthday.");
				birthday.focus();
				return false;
			}else if(tin.value == ""){
				alert("TIN is required! Please enter tin.");
				tin.focus();
				return false;
			}else if(company.value == ""){
				alert("Company is required! Please enter company.");
				company.focus();
				return false;
			}else if(source.value == ""){
				alert("Lead Source is required! Please enter source.");
				source.focus();
				return false;
			}else if(email.value == ""){
				alert("Email is required! Please enter email.");
				email.focus();
				return false;
			}else if(landline.value == ""){
				alert("Landline is required! Please enter landline.");
				landline.focus();
				return false;
			}else if(fax.value == ""){
				alert("Fax is required! Please enter fax.");
				fax.focus();
				return false;
			}else if(mobile.value == ""){
				alert("Mobile is required! Please enter mobile.");
				mobile.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>