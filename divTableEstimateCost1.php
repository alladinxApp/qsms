<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$type = $_GET['type'];
	$id = $_GET['id'];
	$estimate_refno = $_GET['estimate_refno'];
	$rand = genRandomNumber(11);
	
	switch($type){
		case "make": 
				$qrymake = "SELECT * FROM v_make WHERE make_id = '$id'";
				$resmake = $dbo->query($qrymake);
				foreach($resmake as $rowmake){
					$rate = $rowmake['make_rate'];
				}
				
				$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				$qrychkestimate = mysql_query($sqlchkestimate);
				$cntchkestimate = mysql_num_rows($qrychkestimate);

				if($cntchkestimate > 0){
					$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				}else{
					$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$type','$id','$rate')";
				}

				$dbo->query($ins);
			break;
		case "job": 
				$qryjob = "SELECT * FROM v_job WHERE job_id = '$id'";
				$resjob = $dbo->query($qryjob);
				foreach($resjob as $rowjob){
					$rate = $rowjob['stdrate'];
				}
				
				$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				$qrychkestimate = mysql_query($sqlchkestimate);
				$cntchkestimate = mysql_num_rows($qrychkestimate);

				if($cntchkestimate > 0){
					$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				}else{
					$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$type','$id','$rate')";
				}

				$dbo->query($ins);
			break;
		case "parts": 
				$qryparts = "SELECT * FROM v_parts WHERE parts_id = '$id'";
				$resparts = $dbo->query($qryparts);
				foreach($resparts as $rowparts){
					$rate = $rowparts['part_srp'];
				}
				
				$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				$qrychkestimate = mysql_query($sqlchkestimate);
				$cntchkestimate = mysql_num_rows($qrychkestimate);

				if($cntchkestimate > 0){
					$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				}else{
					$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$type','$id','$rate')";
				}
				$dbo->query($ins);
			break;
		case "material":
				$qrymaterial = "SELECT * FROM v_material WHERE material_id = '$id'";
				$resmaterial = $dbo->query($qrymaterial);
				foreach($resmaterial as $rowmaterial){
					$rate = $rowmaterial['material_srp'];
				}
				
				$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				$qrychkestimate = mysql_query($sqlchkestimate);
				$cntchkestimate = mysql_num_rows($qrychkestimate);

				if($cntchkestimate > 0){
					$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				}else{
					$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$type','$id','$rate')";
				}

				$dbo->query($ins);
			break;
		case "accessory":
				$qryaccessory = "SELECT * FROM v_accessory WHERE accessory_id = '$id'";
				$resaccessory = $dbo->query($qryaccessory);
				foreach($resaccessory as $rowaccessory){
					$rate = $rowaccessory['access_srp'];
				}
				
				$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				$qrychkestimate = mysql_query($sqlchkestimate);
				$cntchkestimate = mysql_num_rows($qrychkestimate);
				
				if($cntchkestimate > 0){
					$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
				}else{
					$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$type','$id','$rate')";
				}

				$dbo->query($ins);
			break;
		case "package":
				$qrypackage = "SELECT * FROM v_package_detail WHERE package_id = '$id'";
				$respackage = $dbo->query($qrypackage);

				foreach($respackage as $rowpackage){
					$rand = genRandomNumber(11);
					$id = $rowpackage['id'];
					$ptype = $rowpackage['type'];

					$sqlchkestimate = "SELECT * FROM v_service_detail WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
					$qrychkestimate = mysql_query($sqlchkestimate);
					$cntchkestimate = mysql_num_rows($qrychkestimate);
					
					switch($ptype){
						case "job": $rate = $rowpackage['job_rate']; break;
						case "parts": $rate = $rowpackage['parts_rate']; break;
						case "material": $rate = $rowpackage['material_rate']; break;
						case "accessory": $rate = $rowpackage['accessory_rate']; break;
						default: break;
					}

					if($cntchkestimate > 0){
						$ins = "UPDATE tbl_service_detail SET qty = (qty + 1), amount = (amount + $rate) WHERE estimate_refno = '$estimate_refno' AND id = '$id'";
					}else{
						$ins = "INSERT INTO tbl_service_detail(estimate_refno,type,id,amount) VALUE('$estimate_refno','$ptype','$id','$rate')";
					}

					$dbo->query($ins);
				}
			break;

		default: break;
	}
	
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
	// $qrytempmake = "SELECT * FROM v_service_detail_make WHERE estimate_refno = '$estimaterefno'";
	// $restempmake = $dbo->query($qrytempmake);
	
	$qrytempjob = "SELECT * FROM v_service_detail_job WHERE estimate_refno = '$estimate_refno'";
	$restempjob = $dbo->query($qrytempjob);
	
	$qrytempparts = "SELECT * FROM v_service_detail_parts WHERE estimate_refno = '$estimate_refno'";
	$restempparts = $dbo->query($qrytempparts);
	$numtempparts = mysql_num_rows(mysql_query($qrytempparts));
	
	$qrytempaccessory = "SELECT * FROM v_service_detail_accessory WHERE estimate_refno = '$estimate_refno'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	
	$qrytempmaterial = "SELECT * FROM v_service_detail_material WHERE estimate_refno = '$estimate_refno'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	$numtempmaterial = mysql_num_rows(mysql_query($qrytempmaterial));
	// END TEMPORARY ESTIMATE

	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimate_refno'";
	$resestimate = $dbo->query($qryestimate);

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
		$discount = $rowestimate['discount'];
		$discprice = $rowestimate['discounted_price'];
		$odometer = $rowestimate['odometer'];
		$recommendation = $rowestimate['recommendation'];
		$remarks = $rowestimate['remarks'];
	}

	$subtotal = 0;
?>
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
				<a href="#" onclick="RemoveCost('<?=$rowtempjob[estimate_refno];?>','<?=$rowtempjob[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">
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
				<a href="#" onclick="RemoveCost('<?=$rowtempparts[estimate_refno];?>','<?=$rowtempparts[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">
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
				<a href="#" onclick="RemoveCost('<?=$rowtempmaterial[estimate_refno];?>','<?=$rowtempmaterial[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">
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
				<a href="#" onclick="RemoveCost('<?=$rowtempaccessory[estimate_refno];?>','<?=$rowtempaccessory[id];?>');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">
			<?
					$subtotal += $rowtempaccessory['amount'];
				}
			?>
		</div></td>
	</tr>
	<tr>
		<td><div align="center"><select name="job" id="job" style="width: 120px;" onchange="return AddCost('job',this.value,'<?=$estimaterefno;?>');">
			<option value=""></option>
			<? foreach($resjob as $rowjob){ ?>
			<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
			<? } ?>
		</select></div></td>
		<td><div align="center"><select name="parts" id="parts" style="width: 120px;" onchange="return AddCost('parts',this.value,'<?=$estimaterefno;?>');">
			<option value=""></option>
			<? foreach($resparts as $rowparts){ ?>
			<option value="<?=$rowparts['parts_id'];?>"><?=$rowparts['parts'];?></option>
			<? } ?>
		</select></div></td>
		<td><div align="center"><select name="material" id="material" style="width: 120px;" onchange="return AddCost('material',this.value,'<?=$estimaterefno;?>');">
			<option value=""></option>
			<? foreach($resmaterial as $rowmaterial){ ?>
			<option value="<?=$rowmaterial['material_id'];?>"><?=$rowmaterial['material'];?></option>
			<? } ?>
		</select></div></td>
		<td><div align="center"><select name="accessory" id="accessory" style="width: 120px;" onchange="return AddCost('accessory',this.value,'<?=$estimaterefno;?>');">
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
		<td class="label">Sub Total:</td>
		<td class="input"><input type="text" name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" readonly style="width: 200px; text-align: right;"></td>
		<td></td>
	</tr>
	<tr>
		<td class="label">Discounts:</td>
		<td class="input"><input type="text" name="discount" id="discount" value="<?=$discount;?>" onBlur="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
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
		<td class="input"><input type="text" name="vat" id="vat" value="value="<?=getVatValue();?>"" readonly style="width: 50px; text-align: right;"></td>
		<td class="label">Total Amount:</td>
		<? $vatable = ($subtotal * 0.12) + $subtotal;?>
		<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($vatable,2);?>" readonly style="width: 200px; text-align: right;"></td>
	</tr>
</table>
</fieldset>