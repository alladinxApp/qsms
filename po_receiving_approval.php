<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$vehicleid = $rowestimate['vehicle_id'];
		$remarks = $rowestimate['remarks'];
		$paymentmode = $rowestimate['payment_mode'];
		$discount = $rowestimate['discount'];
		$porefno = $rowestimate['po_refno'];
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
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$getqryservice = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
		$getresservice = $dbo->query($getqryservice);
		foreach($getresservice as $rowresservice){
			$worefno = $rowresservice['wo_refno'];
			$transdate = $rowresservice['transaction_date'];
			$customerid = $rowresservice['customerid'];
			$vehicleid = $rowresservice['vehicle_id'];
			$paymentid = $rowresservice['payment_id'];
			$remarks = $rowresservice['remarks'];
			$technician = $rowresservice['technician'];
		}
		
		$new_refno = getNewNum('PURCHASEORDER');
		$qry .= "INSERT INTO tbl_po_master(estimate_refno,wo_refno,po_refno,transaction_date,customer_id,vehicle_id,payment,id,); ";
		$qry .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + $cnt) WHERE control_type = 'PURCHASEORDER'; ";
		
		$res = $dbo->query($qry);
		$msg = "approved";
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="po_receiving_approval.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}else{
			$msg1 .= "Service(s) has been successfully " . $msg;
			echo '<script>alert("' . $msg1 . '"); window.location="po_receiving_approval.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}
	}
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
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$porefno;?></td>
		</tr>
		<? } ?>
	</table>
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
	<legend>
	<p id="title">VEHICLE INFORMATION:</p></legend>
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
				<input type="">
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				
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
			<td align="left" style="font-weight: normal; font-size: 12px;"><?=$remarks;?></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label" width="100">Sub Total:</td>
			<td align="right" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=number_format($subtotal,2);?></td>
			<td width="50"></td>
			<td width="50"></td>
			<td width="100"></td>
			<td width="200"></td>
		</tr>
		<tr>
			<td class="label">Discounts:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$discount;?></td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$paymentmode;?></td>
		</tr>
		<? $discprice = $subtotal - $discount; ?>
		<tr>
			<td class="label">Discounted Price:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$discprice;?></td> 
			<td><span class="label">VAT:</span></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;">12%</td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * 0.12) + $subtotal;?>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=number_format($vatable,2);?></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<? if($porefno == '0'){ ?>
	<form method="Post" onSubmit="return ValidateMe();">
	<div>
		<table>
			<tr>
				<td>Select Option: </td>
				<td><select name="opt" id="opt" style="width: 200px;">
					<option value=""></option>
					<option value="1">Approve</option>
					<option value="2">Disapprove</option>
					<option value="3">Cancel</option>
				</select></td>
				<td><input type="submit" name="tbnsubmit" id="btnsubmit" value="" /></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" id="option" value="1" />
	</form>
	<? }else{ ?>
	<a href="po_receiving_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank"><img src="images/reports.jpg" border="0" style="pointer: cursor;" /></a>
	<? } ?>
	<script type="text/javascript">
		function ValidateMe(){
			var opt = document.getElementById("opt").value;
			
			if(opt == ""){
				alert("Please select your estimate status option!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>