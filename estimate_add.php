<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	chkMenuAccess('estimate_add',$_SESSION['username'],'estimate_list.php');
	if(isset($_GET['customerid']) && !empty($_GET['customerid'])){
		$customerid = $_GET['customerid'];
		$newVehicleLink = "?customerid=" . $_GET['customerid'];

		$qryplateno = new v_vehicleinfo;
		$resplateno = $dbo->query($qryplateno->Query("WHERE customer_id = '$customerid'"));
	}else
	if(isset($_GET['vehicleid']) && !empty($_GET['vehicleid'])){
		$vehicleid = $_GET['vehicleid'];
		$qryplateno = new v_vehicleinfo;
		$resplateno = $dbo->query($qryplateno->Query("WHERE vehicle_id = '$vehicleid'"));
	}else{
		$qry = new v_vehicleinfo;
		$resplatenos = $dbo->query($qry->Query("order by plate_no"));
	}
	
	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);
		
	$qryjob = "SELECT * FROM v_job";
	$resjob = $dbo->query($qryjob);
	
	$qryparts = "SELECT * FROM v_parts";
	$resparts = $dbo->query($qryparts);
	
	$qrymaterial = "SELECT * FROM v_material";
	$resmaterial = $dbo->query($qrymaterial);
	
	$qryaccessory = "SELECT * FROM v_accessory";
	$resaccessory = $dbo->query($qryaccessory);
	
	$qrypayment = "SELECT * FROM v_payment";
	$respayment = $dbo->query($qrypayment);
	
	// TEMPORARY ESTIMATE
	$qrytempmake = "SELECT * FROM v_temp_estimate_make WHERE ses_id = '$ses_id'";
	$restempmake = $dbo->query($qrytempmake);
	
	$qrytempjob = "SELECT * FROM v_temp_estimate_job WHERE ses_id = '$ses_id'";
	$restempjob = $dbo->query($qrytempjob);
	$numtemplabor = mysql_num_rows(mysql_query($qrytempjob));
	
	$qrytempparts = "SELECT * FROM v_temp_estimate_parts WHERE ses_id = '$ses_id'";
	$restempparts = $dbo->query($qrytempparts);
	$numtempparts = mysql_num_rows(mysql_query($qrytempparts));
	
	$qrytempaccessory = "SELECT * FROM v_temp_estimate_accessory WHERE ses_id = '$ses_id'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	$numtemplubricants = mysql_num_rows(mysql_query($qrytempaccessory));
	
	$qrytempmaterial = "SELECT * FROM v_temp_estimate_material WHERE ses_id = '$ses_id'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	$numtempmaterial = mysql_num_rows(mysql_query($qrytempmaterial));
	// END TEMPORARY ESTIMATE
	
	$subtotal = 0;

	$qrymdl = "SELECT * FROM v_model ORDER BY model";
	$resmdl = $dbo->query($qrymdl);
	
	$qryyr = "SELECT * FROM v_year ORDER BY year DESC";
	$resyr = $dbo->query($qryyr);
	
	$qrymke = "SELECT * FROM v_make ORDER BY make";
	$resmke = $dbo->query($qrymke);
	
	$qryclr = "SELECT * FROM v_color ORDER BY color";
	$resclr = $dbo->query($qryclr);

	$qrypackage = "SELECT * FROM v_package_master ORDER BY package_name";
	$respackage = $dbo->query($qrypackage);

	if(isset($_POST['save']) && !empty($_POST['save']) && $_POST['save'] == 1){
		$sql = null;
		if(isset($_POST['custType']) && $_POST['custType'] > 0){
			$customerid = $_POST['customer_id'];
		}else{
			$salutation = $_POST['salutation'];
			$lastname = $_POST['lastname'];
			$firstname = $_POST['firstname'];
			$middlename = $_POST['middlename'];
			$address = $_POST['address'];
			$customerid = getNewNum('CUSTOMER');

			$sql .= "INSERT INTO tbl_customer (cust_id, salutation, lastname, firstname, middlename, address, cust_created) VALUES
			('".$customerid."',
			'".$salutation."',
			'".$lastname."',
			'".$firstname."',
			'".$middlename."',
			'".$address."',
			'".$today."'
			); ";
			
			$sql .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'CUSTOMER'; ";
		}
		if(isset($_POST['vehicleType']) && $_POST['vehicleType'] > 0){
			$vehicleid = $_POST['plateno'];
		}else{
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
			$conductionsticker = $_POST['conductionsticker'];
			$vehicleid = getNewNum('VEHICLE');;

			$sql .= "INSERT INTO tbl_vehicleinfo (vehicle_id,customer_id, address, plate_no, year, make, model, color, variant, engine_no, chassis_no, serial_no, conduction_sticker) VALUES
			('".$vehicleid."',
			'".$customerid."',
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
			'".$conductionsticker."'); ";
			
			$sql .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'VEHICLE'; ";
		}
		// $payment = $_POST['paymentmode'];
		$remarks = $_POST['txtremarks'];
		$odometer = $_POST['odometer'];
		$subtotal = trim(str_replace(",","",$_POST['subtotal']));
		$discount = trim(str_replace(",","",$_POST['discount']));
		$discounted_price = trim(str_replace(",","",$_POST['discounted_price']));
		$vat = $_POST['vatValue'];
		$total_amount = trim(str_replace(",","",$_POST['totalamount']));
		$recommendation = null;
		$laborDiscount = 0;
		$partsDiscount = 0;
		$materialDiscount = 0;
		$lubricantDiscount = 0;
		$seniorCitizen = 0;

		if(!empty($_POST['seniorNo'])){
			$seniorCitizenNo = $_POST['seniorNo'];
		}else{
			$seniorCitizenNo = null;
		}
		
		if($_POST['txtrecommendation']){
			$recommendation = $_POST['txtrecommendation'];
		}

		if($_POST['laborDiscount']){
			$laborDiscount = $_POST['laborDiscount'];
		}

		if($_POST['partsDiscount']){
			$partsDiscount = $_POST['partsDiscount'];
		}

		if($_POST['materialDiscount']){
			$materialDiscount = $_POST['materialDiscount'];
		}

		if($_POST['lubricantDiscount']){
			$lubricantDiscount = $_POST['lubricantDiscount'];
		}

		if(isset($_POST['senior'])){
			$seniorCitizen = 1;
		}
		
		$qrytempestimate = "SELECT * FROM v_temp_estimate WHERE ses_id = '$ses_id'";
		$restempestimate = $dbo->query($qrytempestimate);
		
		$estimate_refno = getNewNum('ESTIMATEREFNO');
		
		$sql .= "INSERT INTO tbl_service_master
			(estimate_refno,odometer,transaction_date,customer_id,vehicle_id,payment_id,subtotal_amount,discount,discounted_price,vat,total_amount,created_by,remarks,recommendation,labor_discount,parts_discount,lubricant_discount,material_discount,senior_citizen,senior_citizen_no)
			VALUES('$estimate_refno','$odometer','$today','$customerid','$vehicleid','$payment','$subtotal','$discount','$discounted_price','$vat','$total_amount','$_SESSION[username]','$remarks','$recommendation','$laborDiscount','$partsDiscount','$lubricantDiscount','$materialDiscount','$seniorCitizen','$seniorCitizenNo'); ";
		
		foreach($restempestimate as $rowtempestimate){
			$sql .= " INSERT INTO tbl_service_detail
				(estimate_refno,type,id,amount,qty)
				VALUES('$estimate_refno','$rowtempestimate[type]','$rowtempestimate[id]','$rowtempestimate[rate]','$rowtempestimate[qty]'); ";

				// switch($rowtempestimate['type']){
				// 	case "parts":
				// 			$sqldeduct = "UPDATE tbl_parts SET part_onhand = (part_onhand - $rowtempestimate[qty]), parts_used = (parts_used + $rowtempestimate[qty]) where parts_id = '$rowtempestimate[id]'";
				// 		break;
				// 	case "material":
				// 			$sqldeduct = "UPDATE tbl_material SET material_onhand = (material_onhand - $rowtempestimate[qty]), material_used = (material_used + $rowtempestimate[qty]) where material_id = '$rowtempestimate[id]'";
				// 		break;
				// 	case "accessory":
				// 			$sqldeduct = "UPDATE tbl_accessory SET access_onhand = (access_onhand - $rowtempestimate[qty]), access_used = (access_used + $rowtempestimate[qty]) where accessory_id = '$rowtempestimate[id]'";
				// 		break;
				// 	default: break;
				// }
				// mysql_query($sqldeduct);
				// $sql .= $sqldeduct;
		}
		
		$sql .= " UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'ESTIMATEREFNO'; ";
		
		$sql .= " DELETE FROM tbl_temp_estimate WHERE ses_id = '$ses_id'; ";
		$qry = $dbo->query($sql);
		
		if(!$qry){
			echo '<script>alert("There has been an error on saving your service! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Service successfully saved.");</script>';
		}
		echo '<script>window.location="estimate_list.php";</script>';
	}
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
<? require_once('inc/datepicker.php');?>
</head>
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

	function CurrencyFormatted(fld){
		amount = document.getElementById(fld).value;
		var i = parseFloat(amount);
		if(isNaN(i)) { i = 0.00; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		i = parseInt((i + .005) * 100);
		i = i / 100;
		s = new String(i);
		if(s.indexOf('.') < 0) { s += '.00'; }
		if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
		s = minus + s;
		var t = CommaFormatted(s);
		document.getElementById(fld).value = t;
	}
	function CommaFormatted(amount){
		var delimiter = ","; // replace comma if desired
		amount = new String(amount);
		var a = amount.split('.',2)
		var d = a[1];
		var i = parseInt(a[0]);
		if(isNaN(i)) { return ''; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		var n = new String(i);
		var a = [];
		while(n.length > 3)
		{
			var nn = n.substr(n.length-3);
			a.unshift(nn);
			n = n.substr(0,n.length-3);
		}
		if(n.length > 0) { a.unshift(n); }
		n = a.join(delimiter);
		if(d.length < 1) { amount = n; }
		else { amount = n + '.' + d; }
		amount = minus + amount;
		return amount;
	}
	function IsNumeric(sText,fld) {
		var ValidChars = "0123456789.,";
		var IsNumber=true;
		var Char;

		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1) {
				var newtxt = sText.substring(0,sText.length - 1);
				// IsNumber = false;
			}
		}
		$("#"+fld).val(newtxt);
	}
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
		}
	}

	function getCustInfo(custid){
		var strURL = "divGetVehicleCustInfo.php?custid="+custid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divCustInfo').innerHTML = req.responseText;
						getPlateNos(custid);
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function getCustInfo1(vehicleid){
		var strURL = "divGetVehicleCustInfo1.php?vehicleid="+vehicleid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divCustInfo').innerHTML = req.responseText;
						getPlateNos(custid);
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function getPlateNos(custid){
		var strURL = "divPlateNos.php?custid="+custid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divCustomerVehicle').innerHTML = req.responseText;
						addNewVehicle(custid);
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function addNewVehicle(custid){
		var strURL = "divNewVehicleLink.php?customerid="+custid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('newVehicleLink').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function getCustVehicleInfo(vehicleid){
		var strURL = "divCustomerVehicle.php?vehicleid="+vehicleid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divCustomerVehicle').innerHTML = req.responseText;
						getCustInfo1(vehicleid);
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function AddCost(type,id){
		var strURL = "divTableEstimateCost.php?type="+type+"&id="+id;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divTableEstimateCost').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function RemoveCost(id){
		var strURL = "divTableEstimateCost_remove.php?&id="+id;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divTableEstimateCost').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function CancelEstimateCost(){
		var strURL = "divCancelEstimateCost.php";
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divTableEstimateCost').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function addNewCustomer(){
		addNewVehicle();
		var strURL = "divAddNewCustomer.php";
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
	
	function getTotalAmount(){
		var discount = getTotalDiscount();
		var subtotal = document.getElementById("subtotal");
		var vat_val = document.getElementById("vat").value;
		
		var discounted_price = document.getElementById("discounted_price");
		var totalamount = document.getElementById("totalamount");
		var amount = subtotal.value.replace(/,/g, '');

		if(amount <= 0){
			alert("Please create estimate cost first!");
			return false;
		}

		if(discount.value != ""){
		// 	if(n >= 0){
		// 		// var str = discount.value.replace("%","");
		// 		var disc = parseFloat(amount) * (parseFloat(str.replace(/,/g, '')) / 100);
		// 		var disc_price = parseFloat(amount) - parseFloat(disc);
		// 		amount = disc_price;
		// 		discounted_price.value = amount;
		// 		CurrencyFormatted('discounted_price');
		// 	}else{
		// 		var amount = parseFloat(amount) - parseFloat(discount.value.replace(/,/g, ''));
		// 		discounted_price.value = amount;
		// 		CurrencyFormatted('discounted_price');
		// 	}
		}else{
			discounted_price.value = "";
			discount = 0;
		}

		if(isNaN(amount) == false){
			discountedprice = (parseFloat(amount) - parseFloat(discount));

			var vat = parseFloat(discountedprice) * parseFloat(vat_val);

			if(document.estimate_form.senior.checked == true){
				var vatable = (parseFloat(discountedprice) + 0.00);
			}else{
				var vatable = (parseFloat(discountedprice) + parseFloat(vat));
			}
			
			totalamount.value = vatable;
			CurrencyFormatted('totalamount');
		}else{
			discounted_price.value = "";
			discount.value = "";
			alert("Incorrent amount!");
			discount.focus();
			return false;
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
	function chkSenior(){
		if(document.estimate_form.senior.checked){
			document.estimate_form.seniorNo.readOnly = false;
		}else{
			document.estimate_form.seniorNo.value = "";
			document.estimate_form.seniorNo.readOnly = true;
		}
	}
	function getTotalDiscount(){
		var lDiscount = $("#laborDiscount").val();
		var pDiscount = $("#partsDiscount").val();
		var mDiscount = $("#materialDiscount").val();
		var luDiscount = $("#lubricantDiscount").val();
		var subtotal = $("#subtotal").val();

		if(lDiscount == "" || lDiscount == null){
			lDiscount = 0;
		}
		if(pDiscount == "" || pDiscount == null){
			pDiscount = 0;
		}
		if(mDiscount == "" || mDiscount == null){
			mDiscount = 0;
		}
		if(luDiscount == "" || luDiscount == null){
			luDiscount = 0;
		}

		var totalDiscount = (parseFloat(lDiscount) + parseFloat(pDiscount) + parseFloat(mDiscount) + parseFloat(luDiscount));
		var discounted_price = (parseFloat(subtotal.replace(/,/g, '')) - totalDiscount);
		// $("#discount").val(totalDiscount);
		
		// var discounted_price = (parseFloat(subtotal) - parseFloat(totalDiscount));
		$("#discount").val(totalDiscount.toFixed(2));
		$("#discounted_price").val(discounted_price.toFixed(2));
		return totalDiscount.toFixed(2);
	}
</script>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<form method="post" name="estimate_form" class="form" onSubmit="return ValidateMe();">
	<p id="title">Estimate</p>

	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Customer Code:</label>
			<td class ="input">
			<select name="customer_id" id="customer_id" onChange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? 
					foreach($result as $row){ 
						if($row['cust_id'] == $customerid){
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
			<td class ="input">
			<select name="customer" id="customer" onChange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<?
					foreach($result1 as $row1){ 
						if($row1['cust_id'] == $customerid){
							$selected1 = 'selected';
						}else{
							$selected1 = null;
						}
				?>
				<option value="<?=$row1['cust_id'];?>" <?=$selected1;?>><?=$row1['lastname'] . ', ' . $row1['firstname'] . ' ' . $row1['middlename'];?></option>
				<? } ?>
			</select></td>
		</tr>
		
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td class ="input"><input type="text" name="customer_address" id="customer_address" value="<?=$custaddr;?>" readonly style="width:235px"> </td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td class ="label"><label name="lbl_customer"><a href="customer_add1.php"><img src="images/addC.png" width="122" height="28" alt="Add Customer"></a></label></td>
		</tr>
	</table>
	<input type="hidden" name="custType" id="custType" value="1" />
	</span>
	</fieldset>
	<br />
	<fieldset form="form_highschool" name="form_highschool">
	<legend>
	<p id="title">VEHICLE INFORMATION:</p></legend>
	<span id="divCustomerVehicle">
	<table>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><span id="divPlateNos">
				<? if(empty($customerid)){ ?>
				<select name="plateno" id="plateno" onChange="return getCustVehicleInfo(this.value);" style="width: 235px;">
					<option value=""></option>
					<? foreach($resplatenos as $row){ ?>
					<option value="<?=$row['vehicle_id'];?>"><?=$row['plate_no'];?></option>
					<? }?>
				</select>
				<? }else{ ?>
				<select name="plateno" id="plateno" onChange="return getCustVehicleInfo(this.value);" style="width: 235px;">
					<option value=""></option>
					<? 
						foreach($resplateno as $row){ 
							if($row['vehicle_id'] == $vehicleid){
								$selected = 'selected';
								$year = $row['year'];
								$make = $row['make'];
								$model = $row['model'];
								$color = $row['color'];
								$variant = $row['variant'];
								$engineno = $row['engine_no'];
								$chassisno = $row['chassis_no'];
								$serialno = $row['serial_no'];
							}else{
								$selected = null;
							}
					?>
					<option value="<?=$row['vehicle_id'];?>" <?=$selected;?>><?=$row['plate_no'];?></option>
					<? }?>
				</select>
				<? } ?>
			</span></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><select name="year" id="year" style="width: 100px;">
				<option value=""></option>
				<? 
					foreach($resyr as $rowyr){ 
						if($rowyr['year_id'] == $year){
							$selectedyr = 'selected';
						}else{
							$selectedyr = null;
						}
				?>
				<option value="<?=$rowyr['year_id'];?>" <?=$selectedyr;?>><?=$rowyr['year'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><select name="make" id="make" style="width: 150px;">
				<option value=""></option>
				<? 
					foreach($resmke as $rowmke){ 
						if($rowmke['make_id'] == $make){
							$selectedmke = 'selected';
						}else{
							$selectedmke = null;
						}
				?>
				<option value="<?=$rowmke['make_id'];?>" <?=$selectedmke;?>><?=$rowmke['make'];?></option>
				<? } ?>
			</select></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><select name="model" id="model" style="width: 235px;" onChange="getModelVariant(this.value);">
				<option value=""></option>
				<? 
					foreach($resmdl as $rowmdl){ 
						if($rowmdl['model_id'] == $model){
							$selectedmdl = 'selected';
						}else{
							$selectedmdl = null;
						}
				?>
				<option value="<?=$rowmdl['model_id'];?>" <?=$selectedmdl;?>><?=$rowmdl['model'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><select name="color" id="color" style="width: 100px;">
				<option value=""></option>
				<? 
					foreach($resclr as $rowclr){ 
						if($rowclr['color_id'] == $color){
							$selectedclr = 'selected';
						}else{
							$selectedclr = null;
						}
				?>
				<option value="<?=$rowclr['color_id'];?>" <?=$selectedclr;?>><?=$rowclr['color'];?></option>
				<? } ?>
			</select></td>
			<td></td>
				<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><span id="divModelVariant"><input type="text" name="variant" id="variant" readonly value="<?=$variant;?>" style="width:200px" id=""></span></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engine" id="engine" value="<?=$engineno;?>" style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassis" id="chassis" value="<?=$chassisno;?>" style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serial" id="serial" value="<?=$serialno;?>" style="width:118px" id=""></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
			<td class ="input"><input type="text" name="odometer" id="odometer" value="" style="width:235px" id=""></td>
			<td></td>
			<td></td>
			<td class ="label" style="text-align: left;"><span id="newVehicleLink">
			<label name="lbl_customer"><a href="vehicle_add1.php<?=$newVehicleLink;?>"><img src="images/addV.png" width="123" height="19" alt="Add Customer"></a></label></span></td>
		</tr>
	</table>
	<input type="hidden" name="vehicleType" id="vehicleType" value="1" />
	</span>
	</fieldset>
	<br />
	<table>
		<tr>
    		<td>Please select Package: 
    			<select name="txtpackage" id="txtpackage" onChange="return AddCost('package',this.value);">
    				<option value="">select Package</option>
    				<? foreach($respackage as $rowpackage){ ?>
    				<option value="<?=$rowpackage['package_id'];?>"><?=$rowpackage['package_name'];?></option>
    				<? } ?>
    			</select>
    		</td>
    	</tr>
	</table>
	<span id="divTableEstimateCost">
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">ESTIMATE COST:</p></legend>

	<div class="divTableEstimateCost">
    <table width="750" border="0">
		<tr>
			<!--<th><div align="center"><span class="">Make Rate</span></div></th>-->
			<th><div align="center"><span class="">Job Cost</span></div></th>
			<th><div align="center"><span class="">Parts Cost</span></div></th>
			<th><div align="center"><span class="">Material Cost</span></div></th>
			<th><div align="center"><span class="">Lubricants Cost</span></div></th>
		</tr>
		<tr>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempjob as $rowtempjob){
						if($rowtempjob['qty'] > 1){
							$qty = $rowtempjob['qty'] . "pcs";
						}else{
							$qty = $rowtempjob['qty'] . "pc";
						}
						echo $qty . ' ' . $rowtempjob['job'] . '<br />' . $rowtempjob['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempjob['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempjob['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempparts as $rowtempparts){
						if($rowtempparts['qty'] > 1){
							$qty = $rowtempparts['qty'] . "pcs";
						}else{
							$qty = $rowtempparts['qty'] . "pc";
						}
						echo $qty . ' ' . $rowtempparts['parts'] . '<br />' . $rowtempparts['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempparts['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempparts['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmaterial as $rowtempmaterial){
						$type = 'material';
						if($rowtempmaterial['qty'] > 1){
							$qty = $rowtempmaterial['qty'] . "pcs";
						}else{
							$qty = $rowtempmaterial['qty'] . "pc";
						}
						echo $qty . ' ' . $rowtempmaterial['material'] . '<br />' . $rowtempmaterial['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onClick="RemoveCost('. $rowtempmaterial['estimate_id'] .')"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempmaterial['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempaccessory as $rowtempaccessory){
						if($rowtempaccessory['qty'] > 1){
							$qty = $rowtempaccessory['qty'] . "pcs";
						}else{
							$qty = $rowtempaccessory['qty'] . "pc";
						}
						echo $qty . ' ' . $rowtempaccessory['accessory'] . '<br />' . $rowtempaccessory['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempaccessory['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempaccessory['rate'];
					}
				?>
			</div></td>
		</tr>
		<tr>
			<td><div align="center"><select name="job" id="job" style="width: 120px;" onChange="return AddCost('job',this.value);">
				<option value=""></option>
				<? foreach($resjob as $rowjob){ ?>
				<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="parts" id="parts" style="width: 120px;" onChange="return AddCost('parts',this.value);">
				<option value=""></option>
				<? foreach($resparts as $rowparts){ ?>
				<option value="<?=$rowparts['parts_id'];?>"><?=$rowparts['parts'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="material" id="material" style="width: 120px;" onChange="return AddCost('material',this.value);">
				<option value=""></option>
				<? foreach($resmaterial as $rowmaterial){ ?>
				<option value="<?=$rowmaterial['material_id'];?>"><?=$rowmaterial['material'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="accessory" id="accessory" style="width: 120px;" onChange="return AddCost('accessory',this.value);">
				<option value=""></option>
				<? foreach($resaccessory as $rowaccessory){ ?>
				<option value="<?=$rowaccessory['accessory_id'];?>"><?=$rowaccessory['accessory'];?></option>
				<? } ?>
			</select></div></td>
		</tr>
    </table>
	</div>
	</fieldset>
	<?  if($numtempparts > 0 || $numtempmaterial > 0){ ?>
	<br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">RECOMMENDATION</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtrecommendation" id="txtrecommendation" rows="3" cols="104" style="resize: none;"></textarea></td>
		</tr>
	</table>
	</fieldset>
	<? } ?>
	<br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtremarks" id="txtremarks" rows="3" cols="104" style="resize: none;"></textarea></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label">Senior ID:</td>
			<td class="input">
				<input type="text" name="seniorNo" readonly id="seniorNo" style="width: 200px; text-align: right;">
				<input type="checkbox" name="senior" id="senior" onClick="chkSenior(); getTotalAmount();" value="1" />
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Sub Total:</td>
			<td class="input"><input type="text" name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" readonly style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? if($numtemplabor > 0){ ?>
		<tr>
			<td class="label">Labor Discount:</td>
			<td class="input"><input type="text" name="laborDiscount" id="laborDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			}
			if($numtempparts > 0){
		?>
		<tr>
			<td class="label">Parts Discount:</td>
			<td class="input"><input type="text" name="partsDiscount" id="partsDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($numtempmaterial > 0){
		?>
		<tr>
			<td class="label">Material Discount:</td>
			<td class="input"><input type="text" name="materialDiscount" id="materialDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($numtemplubricants > 0){
		?>
		<tr>
			<td class="label">Lubricants Discount:</td>
			<td class="input"><input type="text" name="lubricantDiscount" id="lubricantDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? } ?>
		<tr>
			<td class="label">Total Discounts:</td>
			<td class="input"><input type="text" name="discount" readonly id="discount" value="" onBlur="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<!-- <tr>
			<td class="label">Payment Mode:</td>
			<td class="input"><select name="paymentmode" id="paymentmode" style="width: 200px;">
				<option value=""></option>
				<? foreach($respayment as $rowpayment){ ?>
				<option value="<?=$rowpayment['payment_id'];?>"><?=$rowpayment['payment'];?></option>
				<? } ?>
			</select></td>
			<td></td>
		</tr> -->
		<tr>
			<td class="label">Discounted Price:</td>
			<td class="input"><input type="text" name="discounted_price" id="discounted_price" value="" readonly style="width: 200px; text-align: right;"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"><input type="text" name="vat" id="vat" value="<?=getVatValue();?>" readonly style="width: 50px; text-align: right;"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * getVatValue()) + $subtotal;?>
			<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($vatable,2);?>" readonly style="width: 200px; text-align: right;"></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<p class="button">
		<input type="hidden" value="<?=getVatValue();?>" name="vatValue" id="vatValue" />
		<input type="hidden" value="1" name="save" />
		<input type="submit" value="" name="btnsave" style="cursor: pointer;" />
		<a href="#" onClick="return CancelEstimateCost();"><input type="button" value="" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">

		function ValidateMe(){
			var totalamnt = document.getElementById("totalamount").value;
			var remarks = document.getElementById("txtremarks").value;
			// var paymentmode = document.getElementById("paymentmode").value;
			var customerid = document.getElementById("customer_id").value;
			var plateno = document.getElementById("plateno").value;
			var odometer = document.getElementById("odometer");
			
			if(customerid == ""){
				alert("Please select customer by customer code/customer name!");
				return false;
			// }else if(plateno == ""){
			// 	alert("please select vehicle by plate no!");
			// 	return false;
			// }else if(odometer.value == ""){
			// 	alert("please enter vehicle odometer!");
			// 	odometer.focus();
			// 	return false;
			}else if(document.estimate_form.senior.checked == true){
				if(document.getElementById("seniorNo").value == ""){
					alert("Please enter senior citizen no!");
					return false;
				}
			}else if(isNaN(document.getElementById("discount").value) == true){
				alert("Please enter correct value of your discount!");
				return false;
			}else if(totalamnt == "" || totalamnt == 0){
				alert("Please select estimates costs!");
				return false;
			}else if(remarks == ""){
				alert("Please enter remarks!");
				return false;
			// }else if(paymentmode == ""){
			// 	alert("Please enter payment mode!");
			// 	return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>