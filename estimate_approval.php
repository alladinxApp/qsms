<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	$today = date("Y-m-d h:i:s");
	$estimaterefno = $_GET['estimaterefno'];
	
	$qryestimate = new v_service_master;
	$resestimate = $dbo->query($qryestimate->Query("WHERE estimate_refno = '$estimaterefno'"));
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$address = $rowestimate['cust_address'];
		$vehicleid = $rowestimate['vehicle_id'];
		$odometer = $rowestimate['odometer'];
		$remarks = $rowestimate['remarks'];
		$paymentmode = $rowestimate['payment_mode'];
		$discount = $rowestimate['discount'];
		$worefno = $rowestimate['wo_refno'];
		$transstatus = $rowestimate['trans_status'];
		$porefno = $rowestimate['po_refno'];
		$worefno = $rowestimate['wo_refno'];
		$subtotal = $rowestimate['subtotal_amount'];
		$total = $rowestimate['total_amount'];
		$labordiscount = $rowestimate['labor_discount'];
		$partsdiscount = $rowestimate['parts_discount'];
		$lubricantdiscount = $rowestimate['lubricant_discount'];
		$materialdiscount = $rowestimate['material_discount'];
		$seniorcitizen = $rowestimate['senior_citizen'];
		$seniorcitizendesc = $rowestimate['senior_citizen_desc'];
		$discount = $rowestimate['discount'];
		$discprice = $rowestimate['discounted_price'];

		$plateno = $rowestimate['plate_no'];
		$makedesc = $rowestimate['make_desc'];
		$yeardesc = $rowestimate['year_desc'];
		$modeldesc = $rowestimate['model_desc'];
		$colordesc = $rowestimate['color_desc'];
		$variant = $rowestimate['variant'];
		$engineno = $rowestimate['engine_no'];
		$chassisno = $rowestimate['chassis_no'];
		$serialno = $rowestimate['serial_no'];
	}
	
	$qrycost_accessory = new v_service_detail_accessory;
	$rescost_accessory = $dbo->query($qrycost_accessory->Query("WHERE estimate_refno = '$estimaterefno'"));
	$numrow1 = count($rescost_accessory);
	
	$qrycost_job = new v_service_detail_job;
	$rescost_job = $dbo->query($qrycost_job->Query("WHERE estimate_refno = '$estimaterefno'"));
	
	$qrycost_material = new v_service_detail_material;
	$rescost_material = $dbo->query($qrycost_material->Query("WHERE estimate_refno = '$estimaterefno'"));
	$numrow2 = count($rescost_material);
	
	$qrycost_parts = new v_service_detail_parts;
	$rescost_parts = $dbo->query($qrycost_parts->Query("WHERE estimate_refno = '$estimaterefno'"));
	$numrow = count($rescost_parts);
	
	$subtotal = 0;
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$qry = null;
		switch($_POST['opt']){
			case 1: 
					$new_refno = getNewNum('WORKORDER');
					$qry .= "UPDATE tbl_service_master SET wo_refno = '$new_refno',wo_trans_date = '$today', trans_status = '4' WHERE estimate_refno = '$estimaterefno'; ";
					$qry .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'WORKORDER'; ";
					
					// PARTS
					if($numrow > 0){
						// while($row = mysql_fetch_array($result)){
						foreach($rescost_parts as $row){
							$qty = $row['qty'];
							$starting = $row['part_onhand'];
							$ending = ($row['part_onhand'] - $qty);
							$qry .= "UPDATE tbl_parts SET part_onhand = (part_onhand - $qty), parts_used = (parts_used + $qty) WHERE parts_id = '$row[id]'; ";
							$qry .= "INSERT INTO tbl_po_inventory(item_code,beginning_balance,issued,issued_date,ending_balance,remarks,reference_no,item_type,created_date,created_by)
									VALUES('$row[item_code]','$starting','$qty','$today','$ending','$new_refno','$new_refno','parts','$today','$_SESSION[username]'); ";
						}
					}

					// ACCESSORY or LUBRICANTS
					if($numrow1 > 0){
						// while($row = mysql_fetch_array($result1)){
						foreach($rescost_accessory as $row){
							$qty = $row['qty'];
							$starting = $row['accessory_onhand'];
							$ending = ($row['accessory_onhand'] - $qty);
							$qry .= "UPDATE tbl_accessory SET access_onhand = (access_onhand - $qty), access_used = (access_used + $qty) WHERE accessory_id = '$row[id]'; ";
							$qry .= "INSERT INTO tbl_po_inventory(item_code,beginning_balance,issued,issued_date,ending_balance,remarks,reference_no,item_type,created_date,created_by)
									VALUES('$row[item_code]','$starting','$qty','$today','$ending','$new_refno','$new_refno','lubricants','$today','$_SESSION[username]'); ";
						}
					}

					// MATERIAL
					if($numrow2 > 0){
						// while($row = mysql_fetch_array($result2)){
						foreach($rescost_material as $row){
							$qty = $row['qty'];
							$starting = $row['material_onhand'];
							$ending = ($row['material_onhand'] - $qty);
							$qry .= "UPDATE tbl_material SET material_onhand = (material_onhand - $qty), material_used = (material_used + $qty) WHERE material_id = '$row[id]'; ";
							$qry .= "INSERT INTO tbl_po_inventory(item_code,beginning_balance,issued,issued_date,ending_balance,remarks,reference_no,item_type,created_date,created_by)
									VALUES('$row[item_code]','$starting','$qty','$today','$ending','$new_refno','$new_refno','materials','$today','$_SESSION[username]'); ";
						}
					}

					$res = $dbo->query($qry);
					$msg = "approved";
				break;
			case 2: 
					$qry .= "UPDATE tbl_service_master SET trans_status = '2' WHERE estimate_refno = '$estimaterefno'; ";
					$res = $dbo->query($qry);
					$msg = "disapproved";
				break;
			default: break;
		}
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="estimate_approval.php";</script>';
			exit();
		}else{
			$msg1 .= "Service(s) has been successfully " . $msg;
			if($_POST['opt'] == 1){
				echo '<script>alert("' . $msg1 . '"); window.location="estimate_approval.php?estimaterefno='.$estimaterefno.'";</script>';
			}else{
				echo '<script>alert("' . $msg1 . '"); window.location="estimate_for_approval.php";</script>';
			}
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
<? require_once('inc/datepicker.php'); ?>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<? if($transstatus == '4' || $transstatus == '9'){ ?>
	<table><tr><td valign="middle"><a href="estimate_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_est.png" width="67" height="47" style="pointer: cursor; width: 67px;" border="0" /></div></a></td></tr></table>
	<? } ?>
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
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$odometer;?></td>
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
			<!--<th><div align="center"><span class="">Make Rate</span></div></th>-->
			<th><div align="center"><span class="">Job Cost</span></div></th>
			<th><div align="center"><span class="">Parts Cost</span></div></th>
			<th><div align="center"><span class="">Material Cost</span></div></th>
			<th><div align="center"><span class="">Accessory Cost</span></div></th>
		</tr>
		<tr>
			<!--<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($restempmake as $rowtempmake){
						echo $rowtempmake['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempmake['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
						$subtotal += $rowtempmake['rate'];
					}
				?>
			</div></td>-->
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($rescost_job as $rowjob){
						echo $rowjob['job_name'] . '<br />' . $rowjob['amount'] . '<br />';
						$subtotal += $rowjob['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($rescost_parts as $rowparts){
						echo $rowparts['parts_name'] . '<br />' . $rowparts['amount'] . '<br />';
						$subtotal += $rowparts['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($rescost_material as $rowmaterial){
						$type = 'material';
						echo $rowmaterial['material_name'] . '<br />' . $rowmaterial['amount'] . '<br />';
						$subtotal += $rowmaterial['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					foreach($rescost_accessory as $rowaccessory){
						echo $rowaccessory['accessory_name'] . '<br />' . $rowaccessory['amount'] . '<br />';
						$subtotal += $rowaccessory['amount'];
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
			<td align="left" style="font-weight: normal; font-size: 12px;"><?=$remarks;?></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_totalcost" name="form_totalcost">
	<legend><p id="title">TOTAL COST</p></legend>	
	<table>
		<tr>
			<td class="label">Senior Citizen:</td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$seniorcitizendesc;?></td>
		</tr>
		<tr>
			<td class="label" width="100">Sub Total:</td>
			<td align="right" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=number_format($subtotal,2);?></td>
			<td width="50"></td>
			<td width="50"></td>
			<td width="100"></td>
			<td width="200"></td>
		</tr>
		<tr>
			<td class="label">Labor Discount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$labordiscount;?></td>
		</tr>
		<tr>
			<td class="label">Parts Discount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$partsdiscount;?></td>
		</tr>
		<tr>
			<td class="label">Lubricant Discount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$lubricantdiscount;?></td>
		</tr>
		<tr>
			<td class="label">Material Discount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$materialdiscount;?></td>
		</tr>
		<tr>
			<td class="label">Total Discounts:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$discount;?></td>
		</tr>
		<tr>
			<td class="label">Discounted Price:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$discprice;?></td> 
			<td><span class="label">VAT:</span></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;">12%</td>
			<td class="label">Total Amount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=number_format($total,2);?></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<? if($transstatus == '9'){ ?>
	<form method="Post" onSubmit="return ValidateMe();">
	<div>
		<table>
			<tr>
				<td>Select Option: </td>
				<td><select name="opt" id="opt" style="width: 200px;">
					<option value="1">Approve</option>
					<option value="2">Disapprove</option>
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