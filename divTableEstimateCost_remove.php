<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$id = $_GET['id'];
	
	$qrymake = "DELETE FROM tbl_temp_estimate WHERE estimate_id = '$id' AND ses_id = '$ses_id'";
	$resmake = $dbo->query($qrymake);
	
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
	$numtemplabor = mysql_num_rows(mysql_query($qrytempjob));

	$qrytempparts = "SELECT * FROM v_temp_estimate_parts WHERE ses_id = '$ses_id'";
	$restempparts = $dbo->query($qrytempparts);
	$numtempparts = mysql_num_rows(mysql_query($qrytempparts));
	
	$qrytempaccessory = "SELECT * FROM v_temp_estimate_accessory WHERE ses_id = '$ses_id'";
	$restempaccessory = $dbo->query($qrytempaccessory);
	$numtemplubricants = mysql_num_rows(mysql_query($qrytempaccessory));
	
	$qrytempmaterial = "SELECT * FROM v_temp_estimate_material WHERE ses_id = '$ses_id'";
	$restempmaterial = $dbo->query($qrytempmaterial);
	$numtempmaterial = mysql_num_rows(mysql_query($qrytempmaterial));
	// END TEMPORARY ESTIMATE
	
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
				foreach($restempjob as $rowtempjob){
					if($rowtempjob['qty'] > 1){
						$qty = $rowtempjob['qty'] . "pcs";
					}else{
						$qty = $rowtempjob['qty'] . "pc";
					}
					echo $qty . ' ' . $rowtempjob['job'] . '<br />' . $rowtempjob['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempjob['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
					$subtotal += $rowtempjob['rate'];
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
					echo $qty . ' ' . $rowtempparts['parts'] . '<br />' . $rowtempparts['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempparts['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
					$subtotal += $rowtempparts['rate'];
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
					echo $qty . ' ' . $rowtempmaterial['material'] . '<br />' . $rowtempmaterial['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onClick="RemoveCost('. $rowtempmaterial['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
					$subtotal += $rowtempmaterial['rate'];
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
					echo $qty . ' ' . $rowtempaccessory['accessory'] . '<br />' . $rowtempaccessory['rate'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $rowtempaccessory['estimate_id'] .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" class="divCost">';
					$subtotal += $rowtempaccessory['rate'];
				}
			?>
		</div></td>
	</tr>
	<tr>
		<!--<td><div align="center"><select name="make" id="make" style="width: 120px;" onchange="return AddCost('make',this.value);">
			<option value=""></option>
			<? foreach($resmake as $rowmake){ ?>
			<option value="<?=$rowmake['make_id'];?>"><?=$rowmake['make'];?></option>
			<? } ?>
		</select></div></td>-->
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
			<td class="label">Senior ID:</td>
			<td class="input">
				<input type="text" name="seniorNo" readonly id="seniorNo" style="width: 200px; text-align: right;">
				<input type="checkbox" name="senior" id="senior" onClick="chkSenior(); getTotalAmount();" value="1" />
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="label">Sub Total:</td>
			<td class="input"><input type="text" name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" readonly style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? if($numtemplabor > 0){ ?>
		<tr>
			<td class="label">Labor Discount:</td>
			<td class="input"><input type="text" name="laborDiscount" id="laborDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			}
			if($numtempparts > 0){
		?>
		<tr>
			<td class="label">Parts Discount:</td>
			<td class="input"><input type="text" name="partsDiscount" id="partsDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($numtempmaterial > 0){
		?>
		<tr>
			<td class="label">Material Discount:</td>
			<td class="input"><input type="text" name="materialDiscount" id="materialDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? 
			} 
			if($numtemplubricants > 0){
		?>
		<tr>
			<td class="label">Lubricants Discount:</td>
			<td class="input"><input type="text" name="lubricantDiscount" id="lubricantDiscount" value="" onKeyup="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
			<td></td>
		</tr>
		<? } ?>
		<tr>
			<td class="label">Total Discounts:</td>
			<td class="input"><input type="text" name="discount" readonly id="discount" value="" onBlur="return getTotalAmount();" style="width: 200px; text-align: right;"></td>
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
			<td class="input"><input type="text" name="discounted_price" id="discounted_price" value="" readonly style="width: 200px; text-align: right;"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"><input type="text" name="vat" id="vat" value="<?=getVatValue();?>" readonly style="width: 50px; text-align: right;"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * getVatValue()) + $subtotal;?>
			<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($vatable,2);?>" readonly style="width: 200px; text-align: right;"></td>
		</tr>
	</table>
</fieldset>