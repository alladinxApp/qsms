<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$id = $_GET['id'];
	$items = $_GET['items'];

	$qry_payterms = "SELECT * FROM v_payment_term WHERE status = '1'";
	$result_payterms = $dbo->query($qry_payterms);

	$nArrItems = null;
	$item = explode("|",$items);
	for($i=0;$i<count($item);$i++){
		$val = explode(":",$item[$i]);
		$itemcode = $val[0];
		$itemdesc = $val[1];
		$itemuom = $val[2];
		$itemuomdesc = $val[3];
		$itemprice = $val[4];
		$itemqty = $val[5];

		if($val[0] != $id){
			$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . "|";
		}
	}

	$nArrItems = rtrim($nArrItems,"|");
?>

<span id="divPODtls">
<fieldset form="form_dtls" name="form_dtls">
<legend><p id="title">Details</p></legend>
	<div class="PODtls">
	<table>
		<tr>
			<th width="100">ITEM CODE</th>
			<th width="200">DESCRIPTION</th>
			<th width="100">UOM</th>
			<th width="100">PRICE</th>
			<th width="100">QUANTITY</th>
			<th width="100">TOTAL</th>
			<th width="50">&nbsp;</th>
		</tr>
		<? 
			$subtotal = 0;
			$totalqty = 0;
			$nArrItem = explode("|",$nArrItems);
			if($nArrItems != null || $nArrItems != ""){
			for($i=0;$i<count($nArrItem);$i++){ 
				$val = explode(":",$nArrItem[$i]);

				$total = ($val[4] * $val[5]);
				$subtotal += $total;
				$totalqty += $val[5];
		?>
		<tr>
			<td><?=$val[0];?></td>
			<td><?=$val[1];?></td>
			<td align="center"><?=$val[3];?></td>
			<td align="right"><?=number_format($val[4],2);?></td>
			<td align="center"><?=$val[5];?></td>
			<td align="right"><?=number_format($total,2);?></td>
			<td align="center"><a href="#" onClick="removeItem('<?=$val[0];?>');"><img src="images/del_ico.png" width="15" /></a></td>
		</tr>
		<? 
			}
		?>
		<hr />
		<tr>
			<td colspan="4" align="right"><b>TOTAL >>>>>>>>>></b></td>
			<td align="center"><b><?=$totalqty;?></b></td>
			<td align="right"><b><?=number_format($subtotal,2);?></b></td>
			<td>&nbsp;</td>
		</tr>
		<?
			$vat = ($subtotal * 0.12);
			$totalamnt = ($subtotal + $vat);
			}
		?>
	</table>
	</div>
</fieldset>

<fieldset form="form_payments" name="form_payments">
<legend><p id="title">Payments</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="payment_terms">Payment Terms:</label>
			<td class ="input"><select name="payment_terms" id="payment_terms">
				<option value="">-- Select Payment Terms --</option>
				<? foreach($result_payterms as $row_payterms){ ?>
					<option value="<?=$row_payterms['payment_term_code'];?>"><?=$row_payterms['description'];?></option>
				<? } ?>
			</select></td>
		</tr>    
		<tr>
			<td class ="label"><label name="discount">Discount:</label>
			<td class ="input"><input onKeyup="return discounted();" style="text-align: right;" type="text" name="discount" id="discount" value="" onkeypress="return IsNumeric(discount);" style="width:170px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="subtotal">Sub-Total:</label>
			<td class ="input"><input style="text-align: right;" type="text" readonly name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" style="width:170px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="vat">Vat:</label>
			<td class ="input"><input style="text-align: right;" type="text" readonly name="vat" id="vat" value="<?=number_format($vat,2);?>" style="width:170px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="total_amount">Total Amount:</label>
			<td class ="input"><input style="text-align: right;" type="text" readonly name="total_amount" id="total_amount" value="<?=number_format($totalamnt,2);?>" style="width:170px"></td>
		</tr>
	</table>
</fieldset>
<input type="hidden" name="arrItems" id="arrItems" value="<?=$nArrItems;?>" />
</span>