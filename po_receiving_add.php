<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	chkMenuAccess('po_receiving_add',$_SESSION['username'],'po_receiving_list.php');
	
	$estimaterefno = $_GET['estimaterefno'];	
	
	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$vehicleid = $rowestimate['vehicle_id'];
		$paymentmode = $rowestimate['payment_mode'];
		$discount = $rowestimate['discount'];
		$worefno = $rowestimate['wo_refno'];
		$porefno = $rowestimate['po_refno'];
	}
	
	if($porefno != '0'){
		echo '<script>window.location="po_receiving_view.php?estimaterefno='.$estimaterefno.'";</script>';
		exit();
	}
	
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
	
	$subtotal = 0;
	
	$qryrate = "SELECT * FROM v_make";
	$resmake = $dbo->query($qryrate);
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$payment = $_POST['paymentmode'];
		$subtotal = $_POST['subtotal'];
		$discount = $_POST['discount'];
		$vat = $_POST['vat'];
		$discounted_price = $_POST['discounted_price'];
		$total_amount = $_POST['totalamount'];
		$remarks = $_POST['remarks'];
		$file = $_FILES['txtattachment']['name'];
		$qry = null;

		$getqryservice = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
		$getresservice = $dbo->query($getqryservice);
		foreach($getresservice as $rowresservice){
			$worefno = $rowresservice['wo_refno'];
		}
		
		$getqrytemppodetail = "SELECT * FROM v_temp_po_detail WHERE ses_id = '$ses_id'";
		$getrestemppodetail = $dbo->query($getqrytemppodetail);
		
		$new_refno = getNewNum('PURCHASEORDER');
		
		if(!empty($file)){
			$upload_path = 'attachments/';
			$ext = substr($file, strpos($file,'.'), strlen($file)-1);
			$attachment = $new_refno.$ext;
			move_uploaded_file($_FILES['txtattachment']['tmp_name'],$upload_path . $attachment);
		}
		$qry .= "INSERT INTO tbl_po_master(estimate_refno,wo_refno,po_refno,attachment,transaction_date,payment_id,subtotal_amount,discount,discounted_price,vat,total_amount,remarks,created_by)
				VALUES('$estimaterefno','$worefno','$new_refno','$attachment','$today','$payment','$subtotal','$discount','$discounted_price','12','$total_amount','$remarks','asdf'); ";
		foreach($getrestemppodetail as $rowTEMPPODETAIL){
			$qry .= "INSERT INTO tbl_po_detail(po_refno,type,description,amount)
					VALUES('$new_refno','$rowTEMPPODETAIL[type]','$rowTEMPPODETAIL[description]','$rowTEMPPODETAIL[amount]'); ";
		}
		$qry .= "UPDATE tbl_service_master SET po_refno = '$new_refno' WHERE estimate_refno = '$estimaterefno'; ";
		$qry .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'PURCHASEORDER'; ";
		$qry .= "DELETE FROM tbl_temp_po_detail WHERE ses_id = '$ses_id'; ";

		$res = $dbo->query($qry);
		$msg = "created";
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process your transaction."); window.location="po_receiving_add.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}else{
			$msg1 .= "Purchase Order has been successfully " . $msg;
			echo '<script>alert("' . $msg1 . '"); window.location="po_receiving_view.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}
	}
	
	$qrypayment = "SELECT * FROM v_payment";
	$respayment = $dbo->query($qrypayment);
	
	// TEMPORARY COST
	$qrytempmake = "SELECT * FROM v_temp_po_detail_make WHERE ses_id = '$ses_id'";
	$restempmake = $dbo->query($qrytempmake);
	
	$qrytempjob = "SELECT * FROM v_temp_po_detail_job WHERE ses_id = '$ses_id'";
	$restempjob = $dbo->query($qrytempjob);
	
	$qrytempparts = "SELECT * FROM v_temp_po_detail_parts WHERE ses_id = '$ses_id'";
	$restempparts = $dbo->query($qrytempparts);
	
	$qrytempaccessory = "SELECT * FROM v_temp_po_detail_accessory WHERE ses_id = '$ses_id'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	
	$qrytempmaterial = "SELECT * FROM v_temp_po_detail_material WHERE ses_id = '$ses_id'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	// END TEMPORARY COST
?>
<html>
<head>
<title></title>
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
	function AddCost(){
		var type = document.getElementById("costtype").value;
		var desc = document.getElementById("txtdesc").value;
		var amnt = document.getElementById("txtAmount").value;
		
		if(desc == ""){
			alert("Please enter cost description!");
			return false;
		}else if(amnt == "" || amnt == 0){
			alert("Please enter amount!");
			return false;
		}
	
		var strURL = "divTablePOCost.php?type="+type+"&desc="+desc+"&amnt="+amnt;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divTableCost').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
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
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
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
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">REFERENCE NUMBER</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Estimate: </label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$estimaterefno;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Work Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$worefno;?></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
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
	</fieldset>
	<br />
	<fieldset form="form_highschool" name="form_highschool">
	<legend>
	<p id="title">VEHICLE INFORMATION:</p></legend>
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
	</table>
	</fieldset>
	<br />
	<form method="Post" onSubmit="return ValidateMe();" enctype="multipart/form-data">
	<span id="divTableCost">
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">COST:</p></legend>
	<table>
		<tr>
			<td class="label">Attachment: </td>
			<td><input type="file" name="txtattachment" id="txtattachment" /></td>
		</tr>
	</table>
	<table>
		<td class ="label">Cost Type: </td>
		<td class ="input"><select name="costtype" id="costtype">
			<option value="make">Make</option>
			<option value="job">Job</option>
			<option value="parts">Parts</option>
			<option value="material">Material</option>
			<option value="accessory">Accessory</option>
		</select></td>
		<td class="label">Description: </td>
		<td class="input"><input type="text" name="txtdesc" id="txtdesc" /></td>
		<td class="label">Amount: </td>
		<td class="input"><input type="text" name="txtAmount" id="txtAmount" /></td>
		<td class="input"><img src="images/save.jpg" onClick="AddCost();" style="cursor: pointer;" /></td>
	</table>
	
	<div class="divTableEstimateCost">
    <table width="750" border="0">
		<tr>
			<th><div align="center"><span class="">Make Rate</span></div></th>
			<th><div align="center"><span class="">Job Cost</span></div></th>
			<th><div align="center"><span class="">Parts Cost</span></div></th>
			<th><div align="center"><span class="">Material Cost</span></div></th>
			<th><div align="center"><span class="">Accessory Cost</span></div></th>
		</tr>
		<tr>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmake as $rowtempmake){
						echo $rowtempmake['amount'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempmake['description'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempmake['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempjob as $rowtempjob){
						echo $rowtempjob['amount'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempjob['description'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempjob['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempparts as $rowtempparts){
						echo $rowtempparts['amount'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempparts['description'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempparts['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmaterial as $rowtempmaterial){
						$type = 'material';
						echo $rowtempmaterial['amount'] . '&nbsp;&nbsp;&nbsp;<a href="#" onClick="RemoveCost('. $rowtempmaterial['description'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempmaterial['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempaccessory as $rowtempaccessory){
						echo $rowtempaccessory['amount'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempaccessory['description'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempaccessory['amount'];
					}
				?>
			</div></td>
		</tr>
    </table>
	</div>
	</fieldset>
	<br />
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td align="left" style="font-weight: normal; font-size: 12px;"><textarea cols="109" rows="3" name="remarks" id="remarks" style="resize: none;"><?=$po_remarks;?></textarea></td>
		</tr>
	</table>
	</fieldset>
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
			<td class="label">Discounts:</td>
			<td class="input"><input type="text" name="discount" id="discount" value="<?=$po_discount?>" onBlur="return getTotalAmount();" onkeypress="return isNumberKey(event);" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td class="input"><select name="paymentmode" id="paymentmode" style="width: 200px;">
				<option value=""></option>
				<? 
					foreach($respayment as $rowpayment){ 
						if($rowpayment['payment_id'] == $po_payment){
							$select = 'selected';
						}else{
							$select = null;
						}
				?>
				<option value="<?=$rowpayment['payment_id'];?>" <?=$select;?>><?=$rowpayment['payment'];?></option>
				<? } ?>
			</select></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Discounted Price:</td>
			<td class="input"><input type="text" name="discounted_price" id="discounted_price" value="<?=$po_discountedprice?>" readonly style="width: 200px; text-align: right;"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"><input type="text" name="vat" id="vat" value="12%" readonly style="width: 50px; text-align: right;"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($po_subtotalamount * 0.12) + $po_subtotalamount;?>
			<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($vatable,2);?>" readonly style="width: 200px; text-align: right;"></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<input type="submit" value="" />
	<input type="hidden" name="option" id="option" value="1" />
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var file = document.getElementById("txtattachment").value;
			var remarks = document.getElementById("remarks").value;
			var payment = document.getElementById("paymentmode").value;
			
			if(file == ""){
				alert("Please add your attachment!");
				return false;
			}else if(remarks == ""){
				alert("Please enter your remarks!");
				return false;
			}else if(payment == ""){
				alert("Please select payment mode!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>