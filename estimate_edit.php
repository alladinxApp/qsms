<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);

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

	$qrypackage = "SELECT * FROM v_package_master ORDER BY package_name";
	$respackage = $dbo->query($qrypackage);

	// TEMPORARY ESTIMATE
	// $qrytempmake = "SELECT * FROM v_service_detail_make WHERE estimate_refno = '$estimaterefno'";
	// $restempmake = $dbo->query($qrytempmake);
	
	$qrytempjob = "SELECT * FROM v_service_detail_job WHERE estimate_refno = '$estimaterefno'";
	$restempjob = $dbo->query($qrytempjob);
	$numparts = mysql_num_rows(mysql_query($qrytempjob));
	
	$qrytempparts = "SELECT * FROM v_service_detail_parts WHERE estimate_refno = '$estimaterefno'";
	$restempparts = $dbo->query($qrytempparts);
	$numparts = mysql_num_rows(mysql_query($qrytempparts));
	
	$qrytempaccessory = "SELECT * FROM v_service_detail_accessory WHERE estimate_refno = '$estimaterefno'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	$numlubricant = mysql_num_rows(mysql_query($qrytempaccessory));
	
	$qrytempmaterial = "SELECT * FROM v_service_detail_material WHERE estimate_refno = '$estimaterefno'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	$nummaterial = mysql_num_rows(mysql_query($qrytempmaterial));
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$vehicleid = $rowestimate['vehicle_id'];
		$odometer = $rowestimate['odometer'];
		$remarks = $rowestimate['remarks'];
		$paymentmode = $rowestimate['payment_mode'];
		$discount = $rowestimate['discount'];
		$worefno = $rowestimate['wo_refno'];
		$transstatus = $rowestimate['trans_status'];
		$subtotal = $rowestimate['subtotal_amount'];
		$total = $rowestimate['total_amount'];

		$labordiscount = $rowestimate['labor_discount'];
		$partsdiscount = $rowestimate['parts_discount'];
		$lubricantdiscount = $rowestimate['lubricant_discount'];
		$materialdiscount = $rowestimate['material_discount'];
		$seniorcitizen = $rowestimate['senior_citizen'];
		$seniorCitizenNo = $rowestimate['senior_citizen_no'];

		$discount = $rowestimate['discount'];
		$discprice = $rowestimate['discounted_price'];
		$odometer = $rowestimate['odometer'];
		$recommendation = $rowestimate['recommendation'];
		$remarks = $rowestimate['remarks'];
	}
	
	$isSenior = null;

	$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$custid'";
	$rescustomer = $dbo->query($qrycustomer);
	
	foreach($rescustomer as $rowcustomer){
		$address = $rowcustomer['address'] . ', ' . $rowcustomer['city'] . ', ' . $rowcustomer['province'];
	}
	
	$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicleid'";
	$resvehicle = $dbo->query($qryvehicle);
	
	foreach($resvehicle as $rowvehicle){
		$plateno = $rowvehicle['plate_no'];
		$makedesc = $rowvehicle['make_desc'];
		$yeardesc = $rowvehicle['year_desc'];
		$modeldesc = $rowvehicle['model_desc'];
		$colordesc = $rowvehicle['color_desc'];
		$variant = $rowvehicle['variant'];
		$engineno = $rowvehicle['engine_no'];
		$chassisno = $rowvehicle['chassis_no'];
		$serialno = $rowvehicle['serial_no'];
	}
	
	$qrycost_make = "SELECT * FROM v_service_detail_make WHERE estimate_refno = '$estimaterefno'";
	$rescost_make = $dbo->query($qrycost_make);

	$qrycost_accessory = "SELECT * FROM v_service_detail_accessory WHERE estimate_refno = '$estimaterefno'";
	$rescost_accessory = $dbo->query($qrycost_accessory);
	$numlubricants = mysql_num_rows(mysql_query($qrycost_accessory));
	
	$qrycost_job = "SELECT * FROM v_service_detail_job WHERE estimate_refno = '$estimaterefno'";
	$rescost_job = $dbo->query($qrycost_job);
	$numlabor = mysql_num_rows(mysql_query($qrycost_job));
	
	$qrycost_material = "SELECT * FROM v_service_detail_material WHERE estimate_refno = '$estimaterefno'";
	$rescost_material = $dbo->query($qrycost_material);
	$numtempmaterial = mysql_num_rows(mysql_query($qrycost_material));
	
	$qrycost_parts = "SELECT * FROM v_service_detail_parts WHERE estimate_refno = '$estimaterefno'";
	$rescost_parts = $dbo->query($qrycost_parts);
	$numparts = mysql_num_rows(mysql_query($qrycost_parts));
	
	$subtotal = 0;
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$qry = null;
		$odometer = $_POST['odometer'];
		$subtotal = trim(str_replace(",","",$_POST['subtotal']));
		$discount = trim(str_replace(",","",$_POST['discount']));
		$discounted_price = trim(str_replace(",","",$_POST['discounted_price']));
		$vat = $_POST['vat'];
		$total_amount = trim(str_replace(",","",$_POST['totalamount']));
		$recommendation = null;
		$laborDiscount = 0;
		$partsDiscount = 0;
		$materialDiscount = 0;
		$lubricantDiscount = 0;
		$seniorCitizen = 0;

		if($_POST['txtrecommendation']){
			$recommendation = $_POST['txtrecommendation'];
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

		if(!empty($_POST['seniorNo'])){
			$seniorCitizenNo = $_POST['seniorNo'];
		}else{
			$seniorCitizenNo = null;
		}

		switch($_POST['opt']){
			case 1: 
					$qry .= "UPDATE tbl_service_master SET subtotal_amount = '$subtotal',discount = '$discount',discounted_price = '$discounted_price',vat = '$vat',total_amount = '$total_amount',recommendation = '$recommendation',labor_discount = '$laborDiscount',parts_discount = '$partsDiscount',lubricant_discount = '$lubricantDiscount',material_discount = '$materialDiscount', senior_citizen = '$seniorCitizen', senior_citizen_no = '$seniorCitizenNo' WHERE estimate_refno = '$estimaterefno'; ";
					$res = $dbo->query($qry);
					$msg = "updated";
				break;
			case 2: 
					$qry .= "UPDATE tbl_service_master SET subtotal_amount = '$subtotal',discount = '$discount',discounted_price = '$discounted_price',vat = '$vat',total_amount = '$total_amount',recommendation = '$recommendation',labor_discount = '$laborDiscount',parts_discount = '$partsDiscount',lubricant_discount = '$lubricantDiscount',material_discount = '$materialDiscount', senior_citizen = '$seniorCitizen', senior_citizen_no = '$seniorCitizenNo', trans_status = '9' WHERE estimate_refno = '$estimaterefno'; ";
					$res = $dbo->query($qry);
					$msg = "approved";
				break;
			case 3: 
					$qry .= "UPDATE tbl_service_master SET trans_status = '3' WHERE estimate_refno = '$estimaterefno'; ";
					$res = $dbo->query($qry);
					$msg = "cancelled";
				break;
			
			default: break;
		}
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="estimate_approval.php";</script>';
			exit();
		}else{
			$msg1 .= "Service(s) has been successfully " . $msg;
			echo '<script>alert("' . $msg1 . '"); window.location="estimate_edit.php?estimaterefno=' . $estimaterefno . '";</script>';
		}
	}
?>
<html>
<head
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<? require_once('inc/datepicker.php'); ?>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
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
	function IsNumeric(sText) {
		var ValidChars = "0123456789.,";
		var IsNumber=true;
		var Char;

		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1) {
				IsNumber = false;
			}
		}
		return IsNumber;
	}
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
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
	function AddCost(type,id,estrefno){
		var strURL = "divTableEstimateCost1.php?estimate_refno="+estrefno+"&type="+type+"&id="+id;
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
	function RemoveCost(estrefno,id){
		var strURL = "divTableEstimateCost_remove1.php?estimate_refno="+estrefno+"&id="+id;
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
<body>
	<table>
		<tr>
			<td valign="middle">
				<a href="estimate_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_est.png" width="67" height="47" style="pointer: cursor; width: 67px;" border="0" /></div></a>
			</td>
		</tr>
	</table>

	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">REFERENCE NUMBER</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Estimate: </label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$estimaterefno;?></td>
		</tr>
		<? if($worefno != '0'){ ?>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Work Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$worefno;?></td>
		</tr>
		<? } ?>
	</table>
	</span>
	</fieldset>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Customer Code:</label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$custid;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_customer">Customer Name:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$custname;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$address;?></td>
		</tr>
	</table>
	</span>
	</fieldset>
	<br />
	<fieldset form="form_highschool" name="form_highschool">
	<legend><p id="title">VEHICLE INFORMATION:</p></legend>
	<span id="divCustomerVehicle">
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_plateno">Plate Number:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$plateno;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_year">Year:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$yeardesc;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_make">Make:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$makedesc;?></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$modeldesc;?></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$colordesc;?></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$variant;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine No:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$engineno;?></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis No</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$chassisno;?></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial No:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$serialno;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
			<td class ="input"><input type="text" name="odometer" id="odometer" value="<?=$odometer;?>" style="width:235px" id=""></td>
		</tr>
	</table>
	</span>
	</fieldset>
	<!-- <br />
	<table>
		<tr>
    		<td>Please select Package: 
    			<select name="txtpackage" id="txtpackage" onChange="return AddCost('package',this.value,'<?=$estimaterefno;?>');">
    				<option value="">select Package</option>
    				<? foreach($respackage as $rowpackage){ ?>
    				<option value="<?=$rowpackage['package_id'];?>"><?=$rowpackage['package_name'];?></option>
    				<? } ?>
    			</select>
    		</td>
    	</tr>
	</table> -->
	<br />
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
				
					echo $qty . ' ' . $rowtempjob['job_name'] . '<br />' . $rowtempjob['amount'];
				?>
					&nbsp;&nbsp;&nbsp;
					<!-- <a href="#" onClick="RemoveCost('<?=$rowtempjob[estimate_refno];?>','<?=$rowtempjob[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost"> -->
				<?
						$subtotal += $rowtempjob['amount'];
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
						echo $qty . ' ' . $rowtempparts['parts_name'] . '<br />' . $rowtempparts['amount'];
				?>
					&nbsp;&nbsp;&nbsp;
					<!-- <a href="#" onClick="RemoveCost('<?=$rowtempparts[estimate_refno];?>','<?=$rowtempparts[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost"> -->
				<?
						$subtotal += $rowtempparts['amount'];
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
						echo $qty . ' ' . $rowtempmaterial['material_name'] . '<br />' . $rowtempmaterial['amount'];
				?>
					&nbsp;&nbsp;&nbsp;
					<!-- <a href="#" onClick="RemoveCost('<?=$rowtempmaterial[estimate_refno];?>','<?=$rowtempmaterial[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost"> -->
				<?
						$subtotal += $rowtempmaterial['amount'];
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
						echo $qty . ' ' . $rowtempaccessory['accessory_name'] . '<br />' . $rowtempaccessory['amount'];
				?>
					&nbsp;&nbsp;&nbsp;
					<!-- <a href="#" onClick="RemoveCost('<?=$rowtempaccessory[estimate_refno];?>','<?=$rowtempaccessory[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost"> -->
				<?
						$subtotal += $rowtempaccessory['amount'];
					}
				?>
			</div></td>
		</tr>
		<!-- <tr>
			<td><div align="center"><select name="job" id="job" style="width: 120px;" onChange="return AddCost('job',this.value,'<?=$estimaterefno;?>');">
				<option value=""></option>
				<? foreach($resjob as $rowjob){ ?>
				<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="parts" id="parts" style="width: 120px;" onChange="return AddCost('parts',this.value,'<?=$estimaterefno;?>');">
				<option value=""></option>
				<? foreach($resparts as $rowparts){ ?>
				<option value="<?=$rowparts['parts_id'];?>"><?=$rowparts['parts'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="material" id="material" style="width: 120px;" onChange="return AddCost('material',this.value,'<?=$estimaterefno;?>');">
				<option value=""></option>
				<? foreach($resmaterial as $rowmaterial){ ?>
				<option value="<?=$rowmaterial['material_id'];?>"><?=$rowmaterial['material'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="accessory" id="accessory" style="width: 120px;" onChange="return AddCost('accessory',this.value,'<?=$estimaterefno;?>');">
				<option value=""></option>
				<? foreach($resaccessory as $rowaccessory){ ?>
				<option value="<?=$rowaccessory['accessory_id'];?>"><?=$rowaccessory['accessory'];?></option>
				<? } ?>
			</select></div></td>
		</tr> -->
    </table>
	</div>
	<form method="Post" name="estimate_form" onSubmit="return ValidateMe();">
	</fieldset>
	<?  if($numtempparts > 0 || $numtempmaterial > 0){ ?>
	<br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">RECOMMENDATION</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtrecommendation" id="txtrecommendation" rows="3" cols="104" style="resize: none;"><?=$recommendation;?></textarea></td>
		</tr>
	</table>
	</fieldset>
	<? } ?>
	<br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtremarks" id="txtremarks" rows="3" cols="104" style="resize: none;"><?=$remarks;?></textarea></td>
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
				<input type="text" name="seniorNo" <? if($seniorcitizen == 0){ echo 'readonly'; } ?> id="seniorNo" style="width: 200px; text-align: right;" value="<?=$seniorCitizenNo;?>">
				<input type="checkbox" name="senior" id="senior" <? if($seniorcitizen == 1){ echo 'checked'; } ?> onClick="chkSenior(); getTotalAmount();" value="1" />
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Sub Total:</td>
			<td class="input"><input type="text" name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" readonly style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? if($numlabor > 0){ ?>
		<tr>
			<td class="label">Labor Discount:</td>
			<td class="input"><input type="text" value="<?=$labordiscount;?>" name="laborDiscount" id="laborDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			}
			if($numparts > 0){
		?>
		<tr>
			<td class="label">Parts Discount:</td>
			<td class="input"><input type="text" value="<?=$partsdiscount;?>" name="partsDiscount" id="partsDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($nummaterial > 0){
		?>
		<tr>
			<td class="label">Material Discount:</td>
			<td class="input"><input type="text" value="<?=$materialdiscount;?>" name="materialDiscount" id="materialDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($numlubricant > 0){
		?>
		<tr>
			<td class="label">Lubricants Discount:</td>
			<td class="input"><input type="text" value="<?=$lubricantdiscount;?>" name="lubricantDiscount" id="lubricantDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? } ?>
		<tr>
			<td class="label">Total Discounts:</td>
			<td class="input"><input type="text" name="discount" readonly id="discount" value="<?=$discount;?>" onBlur="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
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
			<td class="input"><input type="text" name="discounted_price" id="discounted_price" value="<?=$discprice;?>" readonly style="width: 200px; text-align: right;"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"><input type="text" name="vat" id="vat" value="<?=getVatValue();?>" readonly style="width: 50px; text-align: right;"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * 0.12) + $subtotal;?>
			<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($total,2);?>" readonly style="width: 200px; text-align: right;"></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<? if($transstatus == 0){ ?>
	
	<div>
		<table>
			<tr>
				<td>Select Option: </td>
				<td><select name="opt" id="opt" style="width: 200px;">
					<option value="1">Update</option>
					<option value="2">Approve</option>
					<option value="3">Cancel</option>
				</select></td>
				<td><input type="submit" name="tbnsubmit" id="btnsubmit" value="" /></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" id="option" value="1" />
	</form>	
	<? } ?>
	<script type="text/javascript">

		function ValidateMe(){
			var totalamnt = document.getElementById("totalamount").value;
			var remarks = document.getElementById("txtremarks").value;
			// var paymentmode = document.getElementById("paymentmode").value;
			// var customerid = document.getElementById("customer_id").value;
			// var plateno = document.getElementById("plateno").value;
			var odometer = document.getElementById("odometer");
			
			// if(customerid == ""){
			// 	alert("Please select customer by customer code/customer name!");
			// 	return false;
			// }else 
			if(document.estimate_form.senior.checked == true){
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
			}else{
				return true;
			}
		}
	</script>
</body>
</html>