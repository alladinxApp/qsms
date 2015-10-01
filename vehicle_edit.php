<?php
	require_once("conf/db_connection.php");

	if(!isset($_SESSION)){
		session_start();
	}

	$vehicle_id = $_GET['vehicleid'];

	if (isset($_POST['update'])){
									
		$vehicle_id=$_POST['vehicle_id'];
		$plate_no=strtoupper($_POST['plateno']);
		$year=$_POST['year'];
		$make=$_POST['make'];
		$model=$_POST['model'];
		$color=$_POST['color'];
		$variant=$_POST['variant'];
		$engine_no=$_POST['engineno'];
		$chassis_no=$_POST['chassisno'];
		$serial_no=$_POST['serialno'];
		$conductionsticker=$_POST['conductionsticker'];
		
		$vehiclelist_update = "UPDATE tbl_vehicleinfo 
					SET plate_no = '$plate_no', 
						year = '$year', 
						make = '$make', 
						model = '$model', 
						color = '$color', 
						variant = '$variant', 
						engine_no = '$engine_no', 
						chassis_no = '$chassis_no', 
						serial_no = '$serial_no',
						conduction_sticker = '$conductionsticker'
					WHERE vehicle_id = '$vehicle_id'";

		$res = mysql_query($vehiclelist_update) or die("UPDATE VEHICLE LIST ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your customers vehicle profile!");</script>';
		}else{
			echo '<script>alert("Customers vehicle profile successfully updated.");</script>';
		}
		echo '<script>window.location="vehicle_list.php";</script>';
	}

	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);

	$query=" SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicle_id'";
	foreach ($dbo->query($query) as $row) {
		$vehicle_id = $row['vehicle_id'];
		$customer_id = $row['customer_id'];
		$customername = $row['customername'];
		$address = $row['address'];
		$plate_no = $row['plate_no'];
		$make = $row['make'];
		$year = $row['year'];
		$model = $row['model'];
		$color = $row['color'];
		$variant = $row['variant'];
		$description = $row['description'];
		$engine_no = $row['engine_no'];
		$chassis_no = $row['chassis_no'];
		$serial_no = $row['serial_no'];
		$conductionsticker = $row['conduction_sticker'];
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />

<!--DATEPICKER-->
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
</script>

</head>
<body>
	<form method="post" name="vehiclelist_form" class="form" onSubmit="return ValidateMe();">
	<p id="title">Customer Vehicle Maintenance</p>
	<fieldset form="form_customerinfo" name="form_customerinfo">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
		<td class ="label"><label name="lbl_customer_id">Customer Code:</label>
		<td class ="input"><input type="text" name="customer_id" id="customer_id"	value="<?=$customer_id;?>" readonly style="width:235px"></td>
		<td></td>
		<td class ="label"><label name="lbl_customer">Customer Name:</label></td>
		<td class ="input"><input type="text" name="customer" value="<?=$customername;?>" readonly style="width:235px"></td>
	</tr>
	
	<tr>
		<td class ="label"><label name="lbl_custadd">Address:</label></td>
		<td class ="input"><input type="text" name="customer_address" value="<?=$address;?>" readonly style="width:235px"> </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	</table>
	</span>
	</fieldset>
	
	<br />
	
	<fieldset form="form_vehicleinfo" name="form_vehicleinfo">
	<legend>
<	p id="title">VEHICLE INFORMATION:</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_vehicle_id">Vehicle Code:</label></td>
			<td class ="input"><input type="text" name="vehicle_id" id="vehicle_id" value="<?=$vehicle_id;?>" readonly style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_plateno">Conduction Sticker:</label></td>
			<td class ="input"><input type="text" name="conductionsticker" id="conductionsticker" value="<?=$conductionsticker;?>" style="width:100px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><input type="text" name="plateno" id="plateno" value="<?=$plate_no;?>" style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><input type="text" name="year" id="year" value="<?=$year;?>" style="width:100px" id="">
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><input type="text" name="make" id="make" value="<?=$make;?>" style="width:118px" id=""></td> 
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><input type="text" name="model" id="model" value="<?=$model;?>" style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><input type="text" name="color" id="color" value="<?=$color;?>" style="width:100px"></td>
			<td></td>
				<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><input type="text" name="variant" id="variant" value="<?=$variant;?>" style="width:118px" id=""></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_engine_no">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engineno" id="engineno" value="<?=$engine_no;?>" style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassisno" id="chassisno" value="<?=$chassis_no;?>" style="width:200px"></td>
			<td></td>
				<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serialno" id="serialno" value="<?=$serial_no;?>" style="width:118px" id=""></td>
		</tr>
	</table>
	</fieldset>	
	<p class="button">
		<input type="submit" value="" name="update" />
		<input type="reset" value="" />
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
			
			if(customer_id.value == ""){
				alert("Customer is required! Please select customer.");
				customer_id.focus();
				return false;
			}else if(plateno.value == ""){
				alert("Plate no is required! Please enter plate no.");
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