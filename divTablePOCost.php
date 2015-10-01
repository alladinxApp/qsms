<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$type = $_GET['type'];
	$desc = $_GET['desc'];
	$amount = $_GET['amnt'];
	$rand = genRandomNumber(11);
	switch($type){
		case "make": 			
			$ins = "INSERT INTO tbl_temp_po_detail(ses_id,type,description,amount) VALUE('$ses_id','make','$desc','$amount')";
			
			$dbo->query($ins);
			break;
		case "job": 
			$ins = "INSERT INTO tbl_temp_po_detail(ses_id,type,description,amount) VALUE('$ses_id','job','$desc','$amount')";
			
			$dbo->query($ins);
			break;
		case "parts": 
			$ins = "INSERT INTO tbl_temp_po_detail(ses_id,type,description,amount) VALUE('$ses_id','parts','$desc','$amount')";
			
			$dbo->query($ins);
			break;
		case "material":
			$ins = "INSERT INTO tbl_temp_po_detail(ses_id,type,description,amount) VALUE('$ses_id','material','$desc','$amount')";
			
			$dbo->query($ins);
			break;
		case "accessory":
			$ins = "INSERT INTO tbl_temp_po_detail(ses_id,type,description,amount) VALUE('$ses_id','accessory','$desc','$amount')";
			
			$dbo->query($ins);
			break;
		default: break;
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
	
	$subtotal = 0;
?>
<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">COST:</p></legend>
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
		<th><div align="center"><span class="">Make amount</span></div></th>
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
			<td class="input"><input type="text" name="discount" id="discount" value="" onBlur="return getTotalAmount();" onkeypress="return isNumberKey(event);" style="width: 200px; text-align: right;"></td>
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
			<td class="label">Discounted Price:</td>
			<td class="input"><input type="text" name="discounted_price" id="discounted_price" value="" readonly style="width: 200px; text-align: right;"></td> 
			<td></td>
			<td><span class="label">VAT:</span></td>
			<td class="input"><input type="text" name="vat" id="vat" value="12%" readonly style="width: 50px; text-align: right;"></td>
			<td class="label">Total Amount:</td>
			<? $vatable = ($subtotal * 0.12) + $subtotal;?>
			<td class="input"><input type="text" name="totalamount" id="totalamount" value="<?=number_format($vatable,2);?>" readonly style="width: 200px; text-align: right;"></td>
		</tr>
	</table>
</fieldset>