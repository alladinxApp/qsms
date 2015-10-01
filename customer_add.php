<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$salutation = $_POST['salutation'];
		$lastname = strtoupper($_POST['lastname']);
		$firstname = strtoupper($_POST['firstname']);
		$middlename = strtoupper($_POST['middlename']);
		$address = $_POST['address'];
		$city = $_POST['city'];
		$province = $_POST['province'];
		$zipcode = $_POST['zipcode'];
		$birthday = $_POST['birthday'];
		$gender = $_POST['gender'];
		$tin = $_POST['tin'];
		$company = $_POST['company'];
		$source = $_POST['source'];
		$email = $_POST['email'];
		$landline = $_POST['landline'];
		$fax = $_POST['fax'];
		$mobile = $_POST['mobile'];
		$newnum = getNewNum('CUSTOMER');
		
		$customer_insert = "INSERT INTO tbl_customer (cust_id, salutation, lastname, firstname, middlename, address, city, province, zipcode, birthday, gender, tin, company, source, email, landline, fax, mobile, cust_created) VALUES
		('".$newnum."',
		'".$salutation."',
		'".$lastname."',
		'".$firstname."',
		'".$middlename."',
		'".$address."',
		'".$city."',
		'".$province."',
		'".$zipcode."',
		'".$birthday."',
		'".$gender."',
		'".$tin."',
		'".$company."',
		'".$source."',
		'".$email."',
		'".$landline."',
		'".$fax."',
		'".$mobile."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'CUSTOMER' ";
		
		$res = mysql_query($customer_insert) or die("INSERT CUSTOMER ".mysql_error());

		if(!$res){
			echo '<script>alert("There has been an error on saving your customer profile! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Customer profile successfully saved.");</script>';
		}
		echo '<script>window.location="customer_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
<? require_once('inc/datepicker.php');?>
</head>
<body>
<form method="post" name="customer_form" class="form">
	<fieldset form="form_customer" name="form_customer">
	<legend><p id="title">Customer Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_cust_id">Customer Code:</label>
			<td class ="input"><input type="text" name="cust_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_salutation">Salutation:</label>
			<td class ="input"><select name="salutation" id="salutation" style="width:91px">
				<option value="Mr.">Mr.</option>
				<option value="Ms.">Ms.</option>
				<option value="Mrs.">Mrs.</option>
			</select></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_lastname">Last Name:</label>
			<td class ="input"><input type="text" name="lastname" id="lastname" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_firstname">First Name:</label>
			<td class ="input"><input type="text" name="firstname" id="firstname" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_middlename">Middle Name:</label>
			<td class ="input"><input type="text" name="middlename" id="middlename" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_address">Address:</label>
			<td class ="input"><input type="text" name="address" id="address" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_city">City:</label>
			<td class ="input"><input type="text" name="city" id="city" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_province">State Province:</label>
			<td class ="input"><input type="text" name="province" id="province" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_zipcode">Zip Code:</label>
			<td class ="input"><input type="text" name="zipcode" id="zipcode" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_birthday">Date of Birth:</label>
			<td><input type="text" name="birthday" id="birthday" value="" class="date-pick" readonly style="width: 100px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_gender">Gender:</label>
			<td class ="input"><select name="gender" id="gender" style="width:91px">
				<option value="Male">Male</option>
				<option value="Female">Female</option>
			</select></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_tin">Tax Identification Number:</label>
			<td class ="input"><input type="text" name="tin" id="tin" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_company">Company Name:</label>
			<td class ="input"><input type="text" name="company" id="company" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_source">Lead Source:</label>
			<td class ="input"><input type="text" name="source" id="source" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_email">Email Address:</label>
			<td class ="input"><input type="text" name="email" id="email" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_landline">Landline Number:</label>
			<td class ="input"><input type="text" name="landline" id="landline" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_fax">Fax Number:</label>
			<td class ="input"><input type="text" name="fax" id="fax" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_mobile">Mobile Number:</label>
			<td class ="input"><input type="text" name="mobile" id="mobile" value="" style="width:272px"></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="customer_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
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
			/*}else if(zipcode.value == ""){
				alert("Zip Code is required! Please enter zipcode.");
				zipcode.focus();
				return false;*/
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
			/*}else if(landline.value == ""){
				alert("Landline is required! Please enter landline.");
				landline.focus();
				return false;
			}else if(fax.value == ""){
				alert("Fax is required! Please enter fax.");
				fax.focus();
				return false;*/
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