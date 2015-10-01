<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$estimaterefno = $_GET['estimaterefno'];
	
	$qryestimate = "SELECT * FROM v_online_estimate_master WHERE oe_id = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	foreach($resestimate as $rowestimate){
		$oe_id = $rowestimate['oe_id'];
		$transaction_date = $rowestimate['transaction_date'];
		$customer = $rowestimate['customer'];
		$address = $rowestimate['address'];
		$contactno = $rowestimate['contactno'];
		$emailaddress = $rowestimate['emailaddress'];
		$plateno = $rowestimate['plateno'];
		$year = $rowestimate['year'];
		$make = $rowestimate['make'];
		$model = $rowestimate['model'];
		$color = $rowestimate['color'];
		$variant = $rowestimate['variant'];
		$engineno = $rowestimate['engineno'];
		$chassisno = $rowestimate['chassisno'];
		$serialno = $rowestimate['serialno'];
		$status = $rowestimate['status'];
		$payment_mode = $rowestimate['payment_mode'];
		$subtotal_amount = $rowestimate['subtotal_amount'];
		$total_amount = $rowestimate['total_amount'];
		$vat = $rowestimate['vat'];
		$discount = $rowestimate['discount'];
		$discounted_price = $rowestimate['discounted_price'];
	}
	
	$sql_oedtl = "SELECT * FROM v_online_estimate_detail WHERE oe_id = '$oe_id'";
	$qry_oedtl = mysql_query($sql_oedtl);
	while($row_oedtl = mysql_fetch_array($qry_oedtl)){
		switch($row_oedtl['type']){
			case "job":
					$oedtl_job[] = array("oedid" => $row_oedtl['oed_id'],
									"id" => $row_oedtl['id'],
									"name" => $row_oedtl['itemname'],
									"amount" => $row_oedtl['amount'],
									"qty" => $row_oedtl['qty']); 
				break;
			case "parts":
					$oedtl_parts[] = array("oedid" => $row_oedtl['oed_id'],
									"id" => $row_oedtl['id'],
									"name" => $row_oedtl['itemname'],
									"amount" => $row_oedtl['amount'],
									"qty" => $row_oedtl['qty']);
				break;
			case "material":
					$oedtl_material[] = array("oedid" => $row_oedtl['oed_id'],
									"id" => $row_oedtl['id'],
									"name" => $row_oedtl['itemname'],
									"amount" => $row_oedtl['amount'],
									"qty" => $row_oedtl['qty']);
				break;
			case "accessory":
					$oedtl_accessory[] = array("oedid" => $row_oedtl['oed_id'],
									"id" => $row_oedtl['id'],
									"name" => $row_oedtl['itemname'],
									"amount" => $row_oedtl['amount'],
									"qty" => $row_oedtl['qty']);
				break;
			default: break;
		}
	}
	
	$subtotal = 0;
	
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$qry = null;
		switch($_POST['opt']){
			case 1: 
					$qry .= "UPDATE tbl_online_estimate_master SET status = '1' WHERE oe_id = '$oe_id'; ";
					$res = $dbo->query($qry);
					$msg = "approved";
				break;
			case 2: 
					$qry .= "UPDATE tbl_online_estimate_master SET status = '2' WHERE oe_id = '$oe_id'; ";
					$res = $dbo->query($qry);
					$msg = "cancelled";
				break;
			default: break;
		}
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="estimate_approval.php";</script>';
			exit();
		}else{
			$msg1 .= "Online Estimate(s) has been successfully " . $msg;
			echo '<script>alert("' . $msg1 . '"); window.location="online_estimate_list.php?estimaterefno=$oe_id";</script>';
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
<body>
	<? if($status == 1){ ?>
	<table><tr><td><a href="online_estimate_print.php?oe_id=<?=$oe_id;?>" target="_blank">
		<div style="width:100px; height:50px; text-align: center;"><img src="images/reports.jpg" border="0" style="pointer: cursor;" width="30" />
			<br />Print Online Estimate
		</div>
	</a></td></tr></table>
	<? } ?>
	<br />
	<fieldset form="form_refno" name="form_refno">
	<legend><p id="title">REFERENCE NUMBER</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Online Estimate: </label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$oe_id;?></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer">Customer Name:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$customer;?></td>
			<td class ="label" width="100"><label name="lbl_customer">Customer Contact No:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$contactno;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$address;?></td>
			<td class ="label"><label name="lbl_custadd">Email Address:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$emailaddress;?></td>
		</tr>
	</table>
	</fieldset>
	<br />
	<fieldset form="form_highschool" name="form_highschool">
	<legend><p id="title">VEHICLE INFORMATION:</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_plateno">Plate Number:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$plateno;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_year">Year:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$year;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_make">Make:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$make;?></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$model;?></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$color;?></td>
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
					for($i=0;$i<count($oedtl_job);$i++){
						echo $oedtl_job[$i]['name'] . '<br />' . $oedtl_job[$i]['amount'];
						$subtotal += $oedtl_job[$i]['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					for($i=0;$i<count($oedtl_parts);$i++){
						echo $oedtl_parts[$i]['name'] . '<br />' . $oedtl_parts[$i]['amount'];
						$subtotal += $oedtl_parts[$i]['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					for($i=0;$i<count($oedtl_material);$i++){
						echo $oedtl_material[$i]['name'] . '<br />' . $oedtl_material[$i]['amount'];
						$subtotal += $oedtl_material[$i]['amount'];
					}
				?>
			</div></td>
			<td valign="top"><div align="center" class="divCost">
				<? 
					for($i=0;$i<count($oedtl_accessory);$i++){
						echo $oedtl_accessory[$i]['name'] . '<br />' . $oedtl_accessory[$i]['amount'];
						$subtotal += $oedtl_accessory[$i]['amount'];
					}
				?>
			</div></td>
		</tr>
    </table>
	</div>
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
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$payment_mode;?></td>
		</tr>
		<tr>
			<td class="label">Discounted Price:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$discounted_price;?></td> 
			<td><span class="label">VAT:</span></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;">12%</td>
			<td class="label">Total Amount:</td>
			<td align="right" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=number_format($total_amount,2);?></td>
		</tr>
	</table>
	</fieldset>
	</span>
	<? if($status == 0){ ?>
	<form method="Post" onSubmit="return ValidateMe();">
	<div>
		<table>
			<tr>
				<td>Select Option: </td>
				<td><select name="opt" id="opt" style="width: 200px;">
					<option value="1">Approve</option>
					<option value="2">Cancel</option>
				</select></td>
				<td><input type="submit" name="tbnsubmit" id="btnsubmit" value="" /></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" id="option" value="1" />
	</form>	
	<? } ?>
</body>
</html>