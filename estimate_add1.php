<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);
	
	$qryrate = "SELECT * FROM v_make";
	$resmake = $dbo->query($qryrate);
	
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
	
	$qrytempparts = "SELECT * FROM v_temp_estimate_parts WHERE ses_id = '$ses_id'";
	$restempparts = $dbo->query($qrytempparts);
	$numtempparts = mysql_num_rows(mysql_query($qrytempparts));
	
	$qrytempaccessory = "SELECT * FROM v_temp_estimate_accessory WHERE ses_id = '$ses_id'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	
	$qrytempmaterial = "SELECT * FROM v_temp_estimate_material WHERE ses_id = '$ses_id'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	$numtempmaterial = mysql_num_rows(mysql_query($qrytempmaterial));
	// END TEMPORARY ESTIMATE

	$qrypackage = "SELECT * FROM v_package_master ORDER BY package_name";
	$respackage = $dbo->query($qrypackage);
	
	$subtotal = 0;

	if(isset($_POST['save']) && !empty($_POST['save']) && $_POST['save'] == 1){
		$customer = $_POST['customer'];
		$address = $_POST['customer_address'];
		$contactno = $_POST['contactno'];
		$emailaddress = $_POST['emailaddress'];
		$plateno = $_POST['plateno'];
		$year = $_POST['year'];
		$make = $_POST['make'];
		$model = $_POST['model'];
		$color = $_POST['color'];
		$variant = $_POST['variant'];
		$engine = $_POST['engine'];
		$chassis = $_POST['chassis'];
		$serial = $_POST['serial'];
		$remarks = $_POST['txtremarks'];
		$payment = $_POST['paymentmode'];
		$subtotal = trim(str_replace(",","",$_POST['subtotal']));
		$discount = trim(str_replace(",","",$_POST['discount']));
		$discounted_price = trim(str_replace(",","",$_POST['discounted_price']));
		$vat = $_POST['vat'];
		$total_amount = trim(str_replace(",","",$_POST['totalamount']));
		
		if($_POST['txtrecommendation']){
			$recommendation = $_POST['txtrecommendation'];
		}else{
			$recommendation = null;
		}
		
		$qrytempestimate = "SELECT * FROM v_temp_estimate WHERE ses_id = '$ses_id'";
		$restempestimate = $dbo->query($qrytempestimate);
		
		$oe_no = getNewNum('ONLINE_ESTIMATE');
		
		$sql = null;
		
		$sql .= "INSERT INTO tbl_online_estimate_master
			(oe_id,transaction_date,customer,address,contactno,emailaddress,plateno,year,make,model,color,variant,engineno,chassisno,serialno,remarks,recommendation,payment_id,subtotal_amount,discount,discounted_price,vat,total_amount)
			VALUES('$oe_no','$today','$customer','$address','$contactno','$emailaddress','$plateno','$year','$make','$model','$color','$variant','$engine','$chassis','$serial','$remarks','$recommendation','$payment','$subtotal','$discount','$discounted_price','$vat','$total_amount'); ";
		
		foreach($restempestimate as $rowtempestimate){
			$sql .= "INSERT INTO tbl_online_estimate_detail
				(oe_id,type,id,amount)
				VALUES('$oe_no','$rowtempestimate[type]','$rowtempestimate[id]','$rowtempestimate[rate]'); ";
		}
		
		$sql .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'ONLINE_ESTIMATE'; ";
		
		$sql .= "DELETE FROM tbl_temp_estimate WHERE ses_id = '$ses_id'; ";
		
		$qry = $dbo->query($sql);
		
		if(!$qry){
			echo '<script>alert("There has been an error on saving your service! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Service successfully saved. Please print a copy, this will serve as your reference.");</script>';
		}
		echo '<script>window.open("online_estimate_print.php?oe_id=' . $oe_no . '");</script>';
		// echo '<script>window.location="estimate_add1.php";</script>';
	}
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
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
	function getTotalAmount(){
		var subtotal = document.getElementById("subtotal");
		var discount = document.getElementById("discount");
		var vat_val = '.' . document.getElementById("vatValue");
		var n = discount.value.indexOf("%");
		
		var discounted_price = document.getElementById("discounted_price");
		var totalamount = document.getElementById("totalamount");
		var amount = subtotal.value.replace(/,/g, '');
		
		if(amount <= 0){
			alert("Please create estimate cost first!");
			return false;
		}

		if(discount.value != ""){
			if(n >= 0){
				var str = discount.value.replace("%","");
				var disc = parseFloat(amount) * (parseFloat(str.replace(/,/g, '')) / 100);
				var disc_price = parseFloat(amount) - parseFloat(disc);
				amount = disc_price;
				discounted_price.value = amount;
				CurrencyFormatted('discounted_price');
			}else{
				var amount = parseFloat(amount) - parseFloat(discount.value.replace(/,/g, ''));
				discounted_price.value = amount;
				CurrencyFormatted('discounted_price');
			}
		}else{
			discounted_price.value = "";
		}
		if(isNaN(amount) == false){
			var vat = parseFloat(amount) * parseFloat(0.12);
			var vatable = parseFloat(amount) + parseFloat(vat);
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
</script>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<form method="post" class="form" onSubmit="return ValidateMe();">
	<p id="title">Estimate</p>

	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label"><label name="lbl_customer">Customer Name:</label></td>
			<td class ="input"><input type="text" name="customer" id="customer" value="" style="width:235px"></td>
			<td class ="label"><label name="lbl_customer">Contact No:</label></td>
			<td class ="input"><input type="text" name="contactno" id="contactno" value="" style="width:235px"></td>
		</tr>
		
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td class ="input"><input type="text" name="customer_address" id="customer_address" value="" style="width:235px"> </td>
			<td class ="label"><label name="lbl_custadd">Email Address:</label></td>
			<td class ="input"><input type="text" name="emailaddress" id="emailaddress" value="" style="width:235px"> </td>
		</tr>
	</table>
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
			<td class ="input"><span id="divPlateNos"><input type="text" name="plateno" id="plateno" value="" style="width:100px"></span></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><input type="text" name="year" id="year" value="" maxlength="4" style="width:100px">
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><input type="text" name="make" id="make" value="" style="width:118px"></td> 
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><input type="text" name="model" id="model" value="" style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><input type="text" name="color" id="color" value="" style="width:100px"></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><input type="text" name="variant" id="variant" value="" style="width:118px"></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engine" id="engine" value="" style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassis" id="chassis" value="" style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serial" id="serial" value="" style="width:118px"></td>
		</tr>
	</table>
	</span>
	</fieldset>
	<br />
	<table>
		<tr>
    		<td>Please select Package: 
    			<select name="txtpackage" id="txtpackage" onchange="return AddCost('package',this.value);">
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
			<!-- <th><div align="center"><span class="">Make Rate</span></div></th> -->
			<th><div align="center"><span class="">Job Cost</span></div></th>
			<th><div align="center"><span class="">Parts Cost</span></div></th>
			<th><div align="center"><span class="">Material Cost</span></div></th>
			<th><div align="center"><span class="">Accessory Cost</span></div></th>
		</tr>
		<tr>
			<!-- <td valign="top"><div align="center" class="divCost">
				<? 
					// foreach($restempmake as $rowtempmake){
					// 	echo $rowtempmake['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempmake['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
					// 	$subtotal += $rowtempmake['rate'];
					// }
				?>
			</div></td> -->
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempjob as $rowtempjob){
						echo $rowtempjob['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempjob['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempjob['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempparts as $rowtempparts){
						echo $rowtempparts['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempparts['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempparts['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmaterial as $rowtempmaterial){
						$type = 'material';
						echo $rowtempmaterial['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onClick="RemoveCost('. $rowtempmaterial['estimate_id'] .')"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempmaterial['rate'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempaccessory as $rowtempaccessory){
						echo $rowtempaccessory['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempaccessory['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempaccessory['rate'];
					}
				?>
			</div></td>
		</tr>
		<tr>
			<!-- <td><div align="center"><select name="make" id="make" style="width: 120px;" onchange="return AddCost('make',this.value);">
				<option value=""></option>
				<? //foreach($resmake as $rowmake){ ?>
				<option value="<? //=$rowmake['make_id'];?>"><? //=$rowmake['make'];?></option>
				<? //} ?>
			</select></div></td> -->
			<td><div align="center"><select name="job" id="job" style="width: 120px;" onchange="return AddCost('job',this.value);">
				<option value=""></option>
				<? foreach($resjob as $rowjob){ ?>
				<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="parts" id="parts" style="width: 120px;" onchange="return AddCost('parts',this.value);">
				<option value=""></option>
				<? foreach($resparts as $rowparts){ ?>
				<option value="<?=$rowparts['parts_id'];?>"><?=$rowparts['parts'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="material" id="material" style="width: 120px;" onchange="return AddCost('material',this.value);">
				<option value=""></option>
				<? foreach($resmaterial as $rowmaterial){ ?>
				<option value="<?=$rowmaterial['material_id'];?>"><?=$rowmaterial['material'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="accessory" id="accessory" style="width: 120px;" onchange="return AddCost('accessory',this.value);">
				<option value=""></option>
				<? foreach($resaccessory as $rowaccessory){ ?>
				<option value="<?=$rowaccessory['accessory_id'];?>"><?=$rowaccessory['accessory'];?></option>
				<? } ?>
			</select></div></td>
		</tr>
    </table>
	</div>
	</fieldset>
	<?  //if($numtempparts > 0 || $numtempmaterial > 0){ ?>
	<!-- <br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">RECOMMENDATION</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtrecommendation" id="txtrecommendation" rows="3" cols="104" style="resize: none;"></textarea></td>
		</tr>
	</table>
	</fieldset> -->
	<? //} ?>
	<!-- <br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td class="input"><textarea name="txtremarks" id="txtremarks" rows="3" cols="104" style="resize: none;"></textarea></td>
		</tr>
	</table>
	</fieldset> -->
	<br />
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label">Sub Total:</td>
			<td class="input"><input type="text" name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" readonly style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td class="input"><select name="paymentmode" id="paymentmode" style="width: 200px;">
				<option value=""></option>
				<? foreach($respayment as $rowpayment){ ?>
				<option value="<?=$rowpayment['payment_id'];?>"><?=$rowpayment['payment'];?></option>
				<? } ?>
			</select></td>
			<td></td>
		</tr>
		<tr>
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
		<input type="hidden" value="1" name="save" />
		<input type="hidden" value="<?=getVatValue();?>" name="vatValue" id="vatValue" />
		<input type="submit" value="" name="btnsave" style="cursor: pointer;" />
		<a href="#" onclick="return CancelEstimateCost();"><input type="button" value="" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var totalamnt = document.getElementById("totalamount").value;
			var paymentmode = document.getElementById("paymentmode").value;
			var cust = document.getElementById("customer");
			var custaddr = document.getElementById("customer_address");
			var contactno = document.getElementById("contactno");
			var emailaddress = document.getElementById("emailaddress");
			var plateno = document.getElementById("plateno");
			var year = document.getElementById("year");
			var make = document.getElementById("make");
			var model = document.getElementById("model");
			var color = document.getElementById("color");
			var variant = document.getElementById("variant");
			// var engine = document.getElementById("engine");
			// var chassis = document.getElementById("chassis");
			// var serial = document.getElementById("serial");
			
			if(cust.value == ""){
				alert("Please enter customer name!");
				cust.focus();
				return false;
			}else if(custaddr.value == ""){
				alert("Please enter customer address!");
				custaddr.focus();
				return false;
			}else if(contactno.value == ""){
				alert("Please enter customer contact no!");
				contactno.focus();
				return false;
			}else if(emailaddress.value == ""){
				alert("Please enter customer email address!");
				emailaddress.focus();
				return false;
			}else if(plateno.value == ""){
				alert("Please enter plate no!");
				plateno.focus();
				return false;
			}else if(year.value == ""){
				alert("Please enter year!");
				year.focus();
				return false;
			}else if(make.value == ""){
				alert("Please enter make!");
				make.focus();
				return false;
			}else if(model.value == ""){
				alert("Please enter model!");
				model.focus();
				return false;
			}else if(color.value == ""){
				alert("Please enter color!");
				color.focus();
				return false;
			}else if(variant.value == ""){
				alert("Please enter variant!");
				variant.focus();
				return false;
			// }else if(engine.value == ""){
			// 	alert("Please enter engine no!");
			// 	engine.focus();
			// 	return false;
			// }else if(chassis.value == ""){
			// 	alert("Please enter chassis no!");
			// 	chassis.focus();
			// 	return false;
			// }else if(serial.value == ""){
			// 	alert("Please enter serial no!");
			// 	serial.focus();
			// 	return false;
			}else if(totalamnt == "" || totalamnt == 0){
				alert("Please select estimates costs!");
				return false;
			// }else if(remarks == ""){
			// 	alert("Please enter remarks!");
			// 	return false;
			}else if(paymentmode == ""){
				alert("Please enter payment mode!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>