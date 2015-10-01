<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(isset($_POST['save']) && !empty($_POST['save']) && $_POST['save'] == 1){
		$customerid = $_POST['customer_id'];
		$vehicleid = $_POST['plateno'];
		$payment = $_POST['paymentmode'];
		$remarks = $_POST['txtremarks'];
		$subtotal = trim(str_replace(",","",$_POST['subtotal']));
		$discount = trim(str_replace(",","",$_POST['discount']));
		$discounted_price = trim(str_replace(",","",$_POST['discounted_price']));
		$vat = $_POST['vat'];
		$total_amount = trim(str_replace(",","",$_POST['totalamount']));
		
		$qrytempestimate = "SELECT * FROM v_temp_estimate WHERE ses_id = '$ses_id'";
		$restempestimate = $dbo->query($qrytempestimate);
		
		$estimate_refno = getNewNum('ESTIMATEREFNO');
		
		$sql = null;
		
		$sql .= "INSERT INTO tbl_service_master
			(estimate_refno,transaction_date,customer_id,vehicle_id,payment_id,subtotal_amount,discount,discounted_price,vat,total_amount,created_by,remarks)
			VALUES('$estimate_refno','$today','$customerid','$vehicleid','$payment','$subtotal','$discount','$discounted_price','$vat','$total_amount','$ses_UserID','$remarks'); ";
		
		foreach($restempestimate as $rowtempestimate){
			$sql .= "INSERT INTO tbl_service_detail
				(estimate_refno,type,id,amount)
				VALUES('$estimate_refno','$rowtempestimate[type]','$rowtempestimate[id]','$rowtempestimate[rate]'); ";
		}
		
		$sql .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'ESTIMATEREFNO'; ";
		
		$sql .= "DELETE FROM tbl_temp_estimate WHERE ses_id = '$ses_id'; ";
		
		$qry = $dbo->query($sql);
		
		if(!$qry){
			echo '<script>alert("There has been an error on saving your service! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Service successfully saved.");</script>';
		}
		echo '<script>window.location="estimate.php";</script>';
	}
	
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
	
	$qrytempaccessory = "SELECT * FROM v_temp_estimate_accessory WHERE ses_id = '$ses_id'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	
	$qrytempmaterial = "SELECT * FROM v_temp_estimate_material WHERE ses_id = '$ses_id'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	// END TEMPORARY ESTIMATE
	
	$subtotal = 0;
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
<title>Idle Time Prepartion</title>
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
	function getPlateNos(custid){
		var strURL = "divPlateNos.php?custid="+custid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divPlateNos').innerHTML = req.responseText;
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
	function getTotalAmount(){
		var subtotal = document.getElementById("subtotal");
		var discount = document.getElementById("discount");
		var discounted_price = document.getElementById("discounted_price");
		var vat = document.getElementById("vat");
		var totalamount = document.getElementById("totalamount");
		var amount = subtotal.value;
		
		if(amount <= 0){
			alert("Please create estimate cost first!");
			return false;
		}
		
		if(discount.value != ""){
			var amount = parseFloat(amount.replace(/,/g, '')) - parseFloat(discount.value.replace(/,/g, ''));
			discounted_price.value = amount;
			CurrencyFormatted('discounted_price');
		}else{
			discounted_price.value = "";
		}
		
		var vatable = (parseFloat(amount) * parseFloat(.12)) + parseFloat(amount);
		totalamount.value = vatable;
		CurrencyFormatted('totalamount');
	}
</script>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<table width="802" height="36">
	  <tr>
	    <td width="5" class ="label">        
	    <td width="245" class ="label"><label name="lbl_customer_id">ESTIMATE REF. NO.</label>
        <td width="10" class ="input">&nbsp;</td>
	    <td width="202"><span class="input">
	      <input type="text" name="input" value="" readonly style="width:200px">
	    </span></td>
	    <td width="103" class ="label"><label name="lbl_customer">WORK ORDER NO.</label></td>
	    <td width="209" class ="input"><input type="text" name="" value="" readonly style="width:200px"></td>
      </tr>
</table>
	<form method="post" name="educational_form" class="form" onSubmit="return ValidateMe();">
<p id="title">IDLE TRANSACTIONS</p>
<fieldset form="form_highschool" name="form_highschool">
	<legend>
	<p id="title">VEHICLE INFORMATION:</p></legend>
	<span id="divCustomerVehicle">
	<table>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><input type="text" name="model4" value="" readonly style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><input type="text" name="year" value="" readonly style="width:100px" id="">
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><input type="text" name="make" value="" readonly style="width:118px" id=""></td> 
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><input type="text" name="model" value="" readonly style="width:235px"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><input type="text" name="color" value="" readonly style="width:100px"></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><input type="text" name="variant" value="" readonly style="width:118px" id=""></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"><input type="text" name="variant" value="" readonly style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassis" value="" readonly style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="variant" value="" readonly style="width:118px" id=""></td>
		</tr>
	</table>
	</span>
</fieldset>
	<br />
<fieldset form="form_highschool" name="form_highschool">
	<legend>
	<p id="title">IDLE TYPE:</p></legend>
	<span id="divCustomerVehicle">
	<table width="317">
		<tr>
			<td width="59" class ="label"><label name="lbl_plateno">Idle Type:</label></td>
			<td width="235" class ="input"><select name="customer_id" id="customer_id" onChange="getCustInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? foreach($result as $row){ ?>
				<option value="<?=$row['cust_id'];?>"><?=$row['cust_id'];?></option>
				<? } ?>
			</select></td>
			<td width="7"></td>
		</tr>
	</table>
	</span>
    </fieldset>
	
    <br />
<fieldset form="form_highschool" name="form_highschool">
	<legend>
  <p id="title">idle CLOCK :</p></legend>
	<span id="divCustomerVehicle">
	<table width="575">
		<tr>
			<td width="3" height="60" class ="label">&nbsp;</td>
			<td width="170" class ="input">CREATE A SYSTEM DATE HERE (MONTH DAY AND YEAR)</td>
			<td width="171"><span class="input">CREATE SYSTEM TIME HERE (CLOCK)</span></td>
			<td width="39">&nbsp;</td>
			<td width="168">&nbsp;</td>
		</tr>		
		<tr>
			<td height="57" class ="label">&nbsp;</td>
			<td class ="input"><img src="images/idle start.png" width="169" height="50"></td>
			<td><img src="images/idle end.png" alt="" width="169" height="50"></td>
			<td>&nbsp;</td>
			<td><div align="left"><a href="jobclock.php"><img src="images/home.jpg" width="55" height="55" align=""></a></div></td>
		</tr>
	</table>
	</span>
    </fieldset>	
    <br />
	</table>    </table>
		</span>
	</div>
</fieldset>
	<br />
	<span id="divTableEstimateCost">
<fieldset form="form_costestimate" name="form_costestimate">
	<legend>
  <p id="title">IDLE TIME HISTORY:</p></legend>
  <span id="divCustomerVehicle">
  <table width="565">
	  <tr>
		  <td width="49" height="22" class ="label"><label name="lbl_model">
		    <div align="center">No.</div>
		  </label></td>
		  <td width="141" class ="input"><div align="center"><span class="label">IDLE TYPE</span></div></td>
			
		  <td width="100" class ="label"><div align="center">START TIME</div></td>
		  <td width="97" class ="input"><div align="center"><span class="label">END TIME</span></div></td>
		  <td width="86"><span class="label">TOTAL HOURS</span></td>
          		  <td width="64" class ="input"><div align="center"><span class="label">REMARKS</span></div></td>
	  </tr>
      <tr>
		  <td height="27" class ="label">&nbsp;</td>
	    <td class ="input">&nbsp;</td>
		  <td></td>
		  <td class ="label">&nbsp;</td>
		  <td class ="input">&nbsp;</td>
		  <td width="64"></td>
    </tr>

	  <tr>
		  <td height="22" class ="label">&nbsp;</td>
	    <td class ="input">&nbsp;</td>
		  <td></td>
		  <td class ="label">TOTAL IDLE TIME:</td>
		  <td class ="input">&nbsp;</td>
		  <td></td>
	  </tr>
  </table>    </table>
  </div>
  </fieldset>
	<br />

      
	</table>
</fieldset>
	<br />
</body>
</html>