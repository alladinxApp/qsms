<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qrychkestimate = "SELECT * FROM v_service_master_approved WHERE estimate_refno = '$estimaterefno'";
	$reschkestimate = mysql_query($qrychkestimate);
	$numchkestimate = mysql_num_rows($reschkestimate);
	while($rowchkestimate = mysql_fetch_array($reschkestimate)){
		$customerid = $rowchkestimate['customer_id'];
	}
	
	$qryservicedtl_make = "SELECT * FROM v_service_detail_make WHERE estimate_refno = '$estimaterefno'";
	$resservicedtl_make = $dbo->query($qryservicedtl_make);
	
	$qryservicedtl_job = "SELECT * FROM v_service_detail_job WHERE estimate_refno = '$estimaterefno'";
	$resservicedtl_job = $dbo->query($qryservicedtl_job);
	
	$qryservicedtl_material = "SELECT * FROM v_service_detail_material WHERE estimate_refno = '$estimaterefno'";
	$resservicedtl_material = $dbo->query($qryservicedtl_material);
	
	$qryservicedtl_parts = "SELECT * FROM v_service_detail_parts WHERE estimate_refno = '$estimaterefno'";
	$resservicedtl_parts = $dbo->query($qryservicedtl_parts);
	
	$qryservicedtl_accessory = "SELECT * FROM v_service_detail_accessory WHERE estimate_refno = '$estimaterefno'";
	$resservicedtl_accessory = $dbo->query($qryservicedtl_accessory);
	
	$qrytech = "SELECT * FROM v_employee WHERE emp_status = '1' ORDER BY employee";
	$restech = $dbo->query($qrytech);
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<script type="text/javascript">
	function CancelWorkOrder(){
		window.location="workorder_list.php";
	}
</script>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<form method="post" name="educational_form" class="form" onSubmit="return ValidateMe();">
	<p id="title">NEW WORK ORDER</p>

	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Customer Code:</label>
			<td class ="input" width="200"><span class=""><?=$customerid;?></td>
			<td width="20"></td>
			<td class ="label" width="100"><label name="lbl_customer">Customer Name:</label></td>
			<td class ="input" width="200"></td>
		</tr>
		
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td class ="input"></td>
			<td></td>
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
			<td class ="label" width="100"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input" width="100"></td>
			<td width="20"></td>
			<td class ="label" width="100"><label name="lbl_year">Year:</label></td>
			<td class ="input" width="100"></td>
			<td width="20"></td>
			<td class ="label" width="100"><label name="lbl_make">Make:</label></td>
			<td class ="input" width="100"></td> 
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"></td>
		</tr>
	</table>
	</span>
	</fieldset>
	<br />
	<span id="divTableEstimateCost">
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">ESTIMATE COST:</p></legend>
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
					foreach($resservicedtl_make as $rowtempmake){
						echo $rowtempmake['amount'];
						$subtotal += $rowtempmake['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($resservicedtl_job as $rowtempjob){
						echo $rowtempjob['amount'];
						$subtotal += $rowtempjob['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($resservicedtl_parts as $rowtempparts){
						echo $rowtempparts['amount'];
						$subtotal += $rowtempparts['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($resservicedtl_material as $rowtempmaterial){
						echo $rowtempmaterial['amount'];
						$subtotal += $rowtempmaterial['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($resservicedtl_accessory as $rowtempaccessory){
						echo $rowtempaccessory['amount'];
						$subtotal += $rowtempaccessory['amount'];
					}
				?>
			</div></td>
		</tr>
    </table>
	</div>
	</fieldset>
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td class="input"></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label">Sub Total:</td>
			<td class="input"></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Discounts:</td>
			<td class="input"></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td class="input"></td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Discounted Price:</td>
			<td class="input"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * 0.12) + $subtotal;?>
			<td class="input"></td>
		</tr>
	</table>
	</fieldset>
	</fieldset>
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">TECHNICIAN</p></legend>	
	<table>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Technician Code:</label>
			<td class ="input"><select name="empid" id="empid" onchange="getEmpInfo(this.value);" style="width: 235px;">
				<option value=""></option>
				<? foreach($restech as $rowtech){ ?>
				<option value="<?=$rowtech['emp_id'];?>"><?=$rowtech['employee'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_custadd">Technician Name:</label></td>
			<td class ="input"><input type="text" name="empname" id="empname" value="" readonly style="width:235px"> </td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="hidden" value="1" name="save" />
		<input type="submit" value="" name="btnsave" style="cursor: pointer;" />
		<a href="#" onclick="return CancelWorkOrder();"><input type="button" value="" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var totalamnt = document.getElementById("totalamount").value;
			var remarks = document.getElementById("txtremarks").value;
			var paymentmode = document.getElementById("paymentmode").value;
			var customerid = document.getElementById("customer_id").value;
			var plateno = document.getElementById("plateno").value;
			
			if(customerid == ""){
				alert("Please select customer by customer code/customer name!");
				return false;
			}else if(plateno == ""){
				alert("plateno select vehicle by plate no!");
				return false;
			}else if(totalamnt == "" || totalamnt == 0){
				alert("Please select estimates costs!");
				return false;
			}else if(remarks == ""){
				alert("Please enter remarks!");
				return false;
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