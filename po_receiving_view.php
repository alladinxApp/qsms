<?php
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qrychkestimate = "SELECT * FROM v_po_master WHERE estimate_refno = '$estimaterefno'";
	$reschkestimate = $dbo->query($qrychkestimate);
	$numchkestimate = count($reschkestimate);
	if($numchkestimate > 0){
		foreach($reschkestimate as $rowPOMST){
			$po_refno = $rowPOMST['po_refno'];
			$po_transdate = $rowPOMST['transaction_date'];
			$po_payment = $rowPOMST['payment_id'];
			$po_subtotalamount = $rowPOMST['subtotal_amount'];
			$po_discount = $rowPOMST['discount'];
			$po_discountedprice = $rowPOMST['discounted_price'];
			$po_vat = $rowPOMST['vat'];
			$po_totalamount = $rowPOMST['total_amount'];
			$po_remarks = $rowPOMST['remarks'];
		}
	}
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$opt = $_POST['opt'];
		
		$qry = null;
		$qry .= "UPDATE tbl_po_master SET trans_status = '0' WHERE po_refno = '$po_refno'; ";
		$qry .= "UPDATE tbl_service_master SET po_refno = '0' WHERE estimate_refno = '$estimaterefno'; ";
		$res = $dbo->query($qry);

		if(!$res){
			echo '<script>alert("There has been an error while processing your request! Please re-process your transaction later.");window.location="po_receiving_add.php?estimaterefno='.$estimaterefno.'";</script>';
		}else{
			echo '<script>alert("Purchase Order successfully cancelled.");window.location="po_receiving_list.php"</script>';
		}
	}
	
	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$vehicleid = $rowestimate['vehicle_id'];
		$paymentmode = $rowestimate['payment_mode'];
		$discount = $rowestimate['discount'];
		$worefno = $rowestimate['wo_refno'];
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
	
	$qrypayment = "SELECT * FROM v_payment WHERE payment_id = '$po_payment'";
	$respayment = $dbo->query($qrypayment);

	foreach($respayment as $rowPAYMENT){
		$po_paymentmode = $rowPAYMENT['payment'];
	}
	
	// TEMPORARY COST
	$qrytempmake = "SELECT * FROM v_po_detail_make WHERE po_refno = '$po_refno'";
	$restempmake = $dbo->query($qrytempmake);
	
	$qrytempjob = "SELECT * FROM v_po_detail_job WHERE po_refno = '$po_refno'";
	$restempjob = $dbo->query($qrytempjob);
	
	$qrytempparts = "SELECT * FROM v_po_detail_parts WHERE po_refno = '$po_refno'";
	$restempparts = $dbo->query($qrytempparts);
	
	$qrytempaccessory = "SELECT * FROM v_po_detail_accessory WHERE po_refno = '$po_refno'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	
	$qrytempmaterial = "SELECT * FROM v_po_detail_material WHERE po_refno = '$po_refno'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	// END TEMPORARY COST
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<table><tr><td><a href="po_receiving_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank"><img src="images/reports.jpg" border="0" style="pointer: cursor;" width="30" /></a></td></tr></table>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">REFERENCE NUMBER</p></legend>
	<span id="divCustInfo">
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Estimate: </label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$estimaterefno;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Work Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$worefno;?></td>
		</tr>
		<? if($porefno != '0'){ ?>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Purchase Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$po_refno;?></td>
		</tr>
		<? } ?>
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
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">COST:</p></legend>
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
						echo $rowtempmake['amount'] . '</div><div align="center" class="divCost">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempjob as $rowtempjob){
						echo $rowtempjob['amount'] . '</div><div align="center" class="divCost">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempparts as $rowtempparts){
						echo $rowtempparts['amount'] . '\</div><div align="center" class="divCost">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmaterial as $rowtempmaterial){
						$type = 'material';
						echo $rowtempmaterial['amount'] . '</div><div align="center" class="divCost">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempaccessory as $rowtempaccessory){
						echo $rowtempaccessory['amount'] . '</div><div align="center" class="divCost">';
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
			<td align="left" style="font-weight: normal; font-size: 12px;"><?=$po_remarks;?></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label" width="100">Sub Total:</td>
			<td class="input" width="250" style="font-weight: normal; font-size: 12px; border:1px solid #333; text-align: right;"><?=number_format($po_subtotalamount,2);?></td>
		</tr>
		<tr>
			<td class="label">Discounts:</td>
			<td class="input" style="font-weight: normal; font-size: 12px; border:1px solid #333; text-align: right;"><?=number_format($po_discount,2);?></td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td class="input" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$po_paymentmode;?></td>
		</tr>
		<tr>
			<td class="label">Discounted Price:</td>
			<td class="input" style="font-weight: normal; font-size: 12px; border:1px solid #333; text-align: right;"><?=number_format($po_discountedprice,2);?></td> 
		</tr>
		<tr>	
			<td class="label">VAT:</td>
			<td class="input" style="font-weight: normal; font-size: 12px; border:1px solid #333; text-align: right;">12%</td>
		</tr>
		<tr>
			<td class="label">Total Amount:</td>
			<td class="input" style="font-weight: normal; font-size: 12px; border:1px solid #333; text-align: right;"><?=number_format($po_totalamount,2);?></td>
		</tr>
	</table>
	</fieldset>
	<form method="Post" onSubmit="return ValidateMe();">
	<table>
		<tr>
			<td><select name="opt" id="opt">
				<option value="">Please select Option</option>
				<option value="1">Cancel PO</option>
			</select></td>
			<td><input type="submit" value="" /></td>
		</tr>
	</table>
	<input type="hidden" name="option" id="option" value="1" />
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var opt = document.getElementById("opt").value;
			
			if(opt == ""){
				alert("Please select option!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>