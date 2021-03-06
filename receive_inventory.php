<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}

	 if (isset($_POST['save'])){
		$vehicle_id = $_POST['vehicle_id'];
		$customer_id = $_POST['customer_id'];
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
		$newnum = getNewNum('VEHICLE');;

		$vehiclelist_insert = "INSERT INTO tbl_vehicleinfo (vehicle_id,customer_id, address, plate_no, year, make, model, color, variant, engine_no, chassis_no, serial_no) VALUES
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
		'".$serial_no."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'VEHICLE' ";
		
		$res = mysql_query($vehiclelist_insert) or die("INSERT VEHICLE LIST ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your customers vehicle profile!");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Customers vehicle profile successfully saved.");</script>';
		}
		echo '<script>window.location="vehicle_list.php";</script>';
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
	<p id="title">RECEIVING OF SUPPLIES</p>
	<fieldset form="form_customerinfo" name="form_customerinfo">
	<legend>
	<p id="title">SUPPLIER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr></tr>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Supplier Code:</label>
			<td class ="input"><select name="customer_id" id="customer_id" onChange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? foreach($result as $row){ ?>
				<option value="<?=$row['cust_id'];?>"><?=$row['cust_id'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_customer">Supplier Name:</label></td>
			<td class ="input"><select name="customer" id="customer" onChange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? foreach($result1 as $row1){ ?>
				<option value="<?=$row1['cust_id'];?>"><?=$row1['lastname'] . ', ' . $row1['firstname'] . ' ' . $row1['middlename'];?></option>
				<? } ?>
			</select></td>
		</tr>
		
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td class ="input"><input type="text" name="customer_address" id="customer_address" value="" readonly style="width:235px"> </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
	</span>
	</fieldset>
	<br />
	<fieldset form="form_vehicleinfo" name="form_vehicleinfo">
	<legend><p id="title">RECEIVING INFORMATION:</p></legend>
	<table>
		<tr>
			<td height="26" class ="label"><label name="lbl_vehicle_id">RR No.:</label></td>
			<td class ="input"><input type="text" name="vehicle_id" value="[SYSTEM GENERATED]" readonly style="width:235px"></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Category:</label></td>
			<td class ="input"><select name="model" id="model" style="width: 235px;" onChange="getModelVariant(this.value);">
				<option value=""></option>
				<? foreach($resmdl as $rowmdl){ ?>
				<option value="<?=$rowmdl['model_id'];?>"><?=$rowmdl['model'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Item:</label></td>
			<td class ="input"><select name="color" id="color" style="width: 200px;">
				<option value=""></option>
				<? foreach($resclr as $rowclr){ ?>
				<option value="<?=$rowclr['color_id'];?>"><?=$rowclr['color'];?></option>
				<? } ?>
			</select></td>
			<td></td>
				<td class ="label"><label name="lbl_variant">Quantity:</label></td>
			<td class ="input"><input type="text" name="customer_address2" id="customer_address2" value="" readonly style="width:100px"></td> 
           <td></td>
				<td class ="label"><label name="lbl_variant">Amount:</label></td>
			<td class ="input"><input type="text" name="customer_address2" id="customer_address2" value="" readonly style="width:100px"></td> 
            <td></td>
            <td><img src="images/addicon.png" width="26" height="26"></td>
	  </tr>
      <tr>
			<td class ="label">&nbsp;</td>
			<td class ="input">&nbsp;</td>
			<td></td>
			<td class ="label">&nbsp;</td>
			<td class ="input">&nbsp;</td>
			<td></td>
				<td class ="label">&nbsp;</td>
			<td class ="input">&nbsp;</td> 
           <td></td>
				<td class ="label"><label name="lbl_variant">Total Amount:</label></td>
			<td class ="input"><input type="text" name="customer_address2" id="customer_address2" value="" readonly style="width:100px"></td> 
            <td></td>
            <td>&nbsp;</td>
	  </tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="receive_inventory.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
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
			var engineno = document.getElementById("engineno");
			var chassisno = document.getElementById("chassisno");
			var serialno = document.getElementById("serialno");
			
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
			}else if(engineno.value == ""){
				alert("Engine no is required! Please enter engine no.");
				engineno.focus();
				return false;
			}else if(chassisno.value == ""){
				alert("Chassis no is required! Please enter chassis no.");
				chassisno.focus();
				return false;
			}else if(serialno.value == ""){
				alert("Serial no is required! Please enter serial no.");
				serialno.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
