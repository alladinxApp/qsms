<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$customer_id = $_GET['customerid'];
	if(!isset($_SESSION)){
		session_start();
	}

	if (isset($_POST['save'])){
		$vehicle_id = $_POST['vehicle_id'];
		// $customer_id = $_POST['customer_id'];
		$address = $_POST['customer_address'];
		$plate_no = $_POST['plateno'];
		$year = $_POST['year'];
		$make = $_POST['make'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$variant = $_POST['variant'];
		$engine_no = $_POST['engineno'];
		$chassis_no = $_POST['chassisno'];
		$serial_no = $_POST['serialno'];
		$conductionsticker = $_POST['conductionsticker'];
		$newnum = getNewNum('VEHICLE');;

		$vehiclelist_insert = "INSERT INTO tbl_vehicleinfo (vehicle_id,customer_id, address, plate_no, year, make, model, color, variant, engine_no, chassis_no, serial_no, conduction_sticker) VALUES
		('".$newnum."',
		'".$customer_id."',
		'".$address."',
		'".$plate_no."',
		'".$year."',
		'".$make."',
		'".$model."',
		'".$color."',
		'".$variant."',
		'".$engine_no."',
		'".$chassis_no."',
		'".$serial_no."',
		'".$conductionsticker."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'VEHICLE' ";
		
		$res = mysql_query($vehiclelist_insert) or die("INSERT VEHICLE LIST ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your customers vehicle profile!");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Customers vehicle profile successfully saved.");</script>';
		}
		echo '<script>window.location="estimate_add.php?customerid='.$customerid.'&vehicleid='.$newnum.'";</script>';
	}

	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);
	
	$qrymdl = "SELECT * FROM v_model ORDER BY model";
	$resmdl = $dbo->query($qrymdl);
	
	$qryyr = "SELECT * FROM v_year ORDER BY year DESC";
	$resyr = $dbo->query($qryyr);
	
	$qrymke = "SELECT * FROM v_make ORDER BY make";
	$resmke = $dbo->query($qrymke);
	
	$qryclr = "SELECT * FROM v_color ORDER BY color";
	$resclr = $dbo->query($qryclr);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript">
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp = false;	
		try{
			xmlhttp = new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp = false;
				}
			}
		}
		return xmlhttp;
	}
	function getCustInfo(custid){
		var strURL = "divGetVehicleCustInfo.php?custid="+custid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divCustInfo').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function getModelVariant(modelid){
		var strURL = "divModelVariant.php?modelid="+modelid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divModelVariant').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
</script>

</head>
<body>
	<form method="post" name="vehiclelist_form" class="form" onSubmit="return ValidateMe();">
	<p id="title">Customer Vehicle Maintenance</p>
	<fieldset form="form_customerinfo" name="form_customerinfo">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr></tr>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Customer Code:</label>
			<td class ="input"><select readonly name="customer_id" id="customer_id" disabled onchange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? 
					foreach($result as $row){ 
						if($row['cust_id'] == $customer_id){
							$selected = 'selected';
							$custaddr = $row['address'] . ' ' . $row['city'] . ' ' . $row['province'];
						}else{
							$selected = null;
						}
				?>
				<option value="<?=$row['cust_id'];?>" <?=$selected;?>><?=$row['cust_id'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_customer">Customer Name:</label></td>
			<td class ="input"><select name="customer" id="customer" disabled onchange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? 
					foreach($result1 as $row1){ 
						if($row['cust_id'] == $customer_id){
							$selected = 'selected';
						}else{
							$selected = null;
						}
				?>
				<option value="<?=$row1['cust_id'];?>" <?=$selected;?>><?=$row1['lastname'] . ', ' . $row1['firstname'] . ' ' . $row1['middlename'];?></option>
				<? } ?>
			</select></td>
		</tr>
		
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td class ="input"><input type="text" name="customer_address" id="customer_address" value="<?=$custaddr;?>" readonly style="width:235px"> </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
	</span>
	</fieldset>
	<br />
	<fieldset form="form_vehicleinfo" name="form_vehicleinfo">
	<legend><p id="title">VEHICLE INFORMATION:</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_vehicle_id">Vehicle Code:</label></td>
			<td class ="input"><input type="text" name="vehicle_id" value="[SYSTEM GENERATED]" readonly style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_plateno">Conduction Sticker:</label></td>
			<td class ="input"><input type="text" name="conductionsticker" id="conductionsticker" value="" style="width:100px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><input type="text" name="plateno" id="plateno" value="" style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><select name="year" id="year" style="width: 100px;">
				<option value=""></option>
				<? foreach($resyr as $rowyr){ ?>
				<option value="<?=$rowyr['year_id'];?>"><?=$rowyr['year'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><select name="make" id="make" style="width: 150px;">
				<option value=""></option>
				<? foreach($resmke as $rowmke){ ?>
				<option value="<?=$rowmke['make_id'];?>"><?=$rowmke['make'];?></option>
				<? } ?>
			</select></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><select name="model" id="model" style="width: 235px;" onChange="getModelVariant(this.value);">
				<option value=""></option>
				<? foreach($resmdl as $rowmdl){ ?>
				<option value="<?=$rowmdl['model_id'];?>"><?=$rowmdl['model'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><select name="color" id="color" style="width: 100px;">
				<option value=""></option>
				<? foreach($resclr as $rowclr){ ?>
				<option value="<?=$rowclr['color_id'];?>"><?=$rowclr['color'];?></option>
				<? } ?>
			</select></td>
			<td></td>
				<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><span id="divModelVariant"><input type="text" name="variant" id="variant" readonly value="" style="width:200px" id=""></span></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_engine_no">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engineno" id="engineno" value="" style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassisno" id="chassisno" value="" style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serialno" id="serialno" value="" style="width:118px" id=""></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="vehicle_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var customer_id = document.getElementById("customer_id");
			var plateno = document.getElementById("plateno");
			var year = document.getElementById("year");
			var make = document.getElementById("make");
			var model = document.getElementById("model");
			var color = document.getElementById("color");
			var variant = document.getElementById("variant");
			var conductionsticker = document.getElementById("conductionsticker");
			
			if(customer_id.value == ""){
				alert("Customer is required! Please select customer.");
				customer_id.focus();
				return false;
			}else if(plateno.value == "" && conductionsticker.value == ""){
				alert("Plate no is required! Please enter plate no/conduction sticker.");
				plateno.focus();
				return false;
			}else if(year.value == ""){
				alert("Year is required! Please select year.");
				year.focus();
				return false;
			}else if(make.value == ""){
				alert("Make is required! Please select make.");
				make.focus();
				return false;
			}else if(model.value == ""){
				alert("Model is required! Please select model.");
				model.focus();
				return false;
			}else if(color.value == ""){
				alert("Color is required! Please select color.");
				color.focus();
				return false;
			}else if(variant.value == ""){
				alert("Variant is required! Please enter variant.");
				variant.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
