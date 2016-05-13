<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$id = $_GET['id'];

	$qry_supplier = "SELECT * FROM v_suppliers WHERE status = '1'";
	$result_supplier = $dbo->query($qry_supplier);

	$qry_payterms = "SELECT * FROM v_payment_term WHERE status = '1'";
	$result_payterms = $dbo->query($qry_payterms);

	$qry_items = "SELECT * FROM v_items WHERE status = '1'";
	$result_items = $dbo->query($qry_items);

	$qry_po_mst = "SELECT * FROM v_po_mst WHERE po_reference_no = '$id'";
	$result_po_mst = $dbo->query($qry_po_mst);

	$qry_po_dtl = "SELECT * FROM v_po_dtl WHERE po_reference_no = '$id'";
	$result_po_dtl = $dbo->query($qry_po_dtl);
	$num_po_dtl = mysql_num_rows(mysql_query($qry_po_dtl));

	foreach($result_po_mst as $row){
		$podate = $row['po_date'];
		$suppliername = $row['supplier_name'];
		$deliverto = $row['deliver_to'];
		$deliveryaddress = $row['delivery_address'];
		$paymentterm = $row['payment_code'];
		$discount = $row['discount'];
		$subtotal = $row['sub_total'];
		$vat = $row['vat'];
		$totalamount = $row['total_amount'];
		$special = $row['special_instruction'];
		$status = $row['status'];
		$statusdesc = $row['status_desc'];
		$poquantity = $row['po_quantity'];
		$rrquantity = $row['rr_quantity'];
		$difference = $row['difference'];

		$rrrefno = '[SYSTEM GENERATED]';
		if(!empty($row['rr_reference_no'])){
			$rrrefno = $row['rr_reference_no'];
		}

		$rrdate = null;
		if(!empty($row['rr_date'])){
			$rrdate = $row['rr_date'];
		}
	}

	$nArrItems = null;
	foreach($result_po_dtl as $row){
		$itemcode = $row['item_code'];
		$itemdesc = $row['item_description'];
		$itemuom = $row['UOM'];
		$itemuomdesc = $row['UOM_desc'];
		$itemprice = $row['price'];
		$itemqty = $row['quantity'];
		$itemrr = $row['rr_quantity'];
		$itemrrttl = $row['rr_total'];
		$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . ":" . $itemrr . ":" . $itemrrttl . "|";
	}

	if($num_po_dtl > 0){
		$nArrItems = rtrim($nArrItems,"|");
	}

	if (isset($_POST['update'])){
		$difference = $_POST['difference'];
		$paymentterm = $_POST['payment_terms'];
		$item = explode(":",$_POST['txtItemsArr']);
		$newnum = getNewNum('RECEIVING_REPORT');

		switch($_POST['status']){
			case 1: // UPDATE
					$po_mst_upd = "UPDATE tbl_po_mst 
						SET difference = '$difference'
							,rr_reference_no = '$newnum'
							,rr_date = '$today'
							,received_by = '$_SESSION[username]'
							,received_date = '$today'
							,status = '10'
						WHERE po_reference_no = '$id'";

					$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'RECEIVING_REPORT' ";
					
					$res = mysql_query($po_mst_upd) or die("UPDATE RR ".mysql_error());
					
					if(!$res){
						echo '<script>alert("There has been an error on receiving your PO! Please double check all the data and save.");</script>';
					}else{
						for($i=0;$i<count($item);$i++){
							$itemcode = $item[$i];
							$qty = $_POST['txt'.$itemcode];
							$sql_dtl_upd = "UPDATE tbl_po_dtl SET rr_quantity = '$qty' WHERE po_reference_no = '$id' AND item_code = '$itemcode'";
							mysql_query($sql_dtl_upd);

							// UPDATE ITEM ON HAND FOR ACCESSORY/LUBRICANTS
							$sql_acc_upd = "UPDATE tbl_accessory SET access_onhand = (access_onhand + $qty) WHERE item_code = '$itemcode'";
							mysql_query($sql_acc_upd);

							// UPDATE ITEM ON HAND FOR MATERIALS
							$sql_mat_upd = "UPDATE tbl_material SET material_onhand = (material_onhand + $qty) WHERE item_code = '$itemcode'";
							mysql_query($sql_mat_upd);

							// UPDATE ITEM ON HAND FOR PARTS
							$sql_par_upd = "UPDATE tbl_parts SET part_onhand = (part_onhand + $qty) WHERE item_code = '$itemcode'";
							mysql_query($sql_par_upd);
						}
						mysql_query($update_controlno);
						echo '<script>alert("PO successfully received.");</script>';
					}
					echo '<script>window.location="rr_edit.php?id='.$id.'";</script>';
				break;
			default:
					for($i=0;$i<count($item);$i++){
						$itemcode = $item[$i];
						$qty = $_POST['txt'.$itemcode];
						$sql_dtl_upd = "UPDATE tbl_po_dtl SET rr_quantity = '$qty' WHERE po_reference_no = '$id' AND item_code = '$itemcode'";
						mysql_query($sql_dtl_upd);
					}
					echo '<script>alert("RR successfully updated.");</script>';
					echo '<script>window.location="rr_edit.php?id='.$id.'";</script>';
				break;
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
	div.PODtls { border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.PODtls table{ border: 1px solid #ccc; font-size: 12px; }
	div.PODtls table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }

	textarea#special { resize: none; }
	span#divPODtls table tr td input#discount,
	span#divPODtls table tr td input#subtotal,
	span#divPODtls table tr td input#vat,
	span#divPODtls table tr td input#total_amount,
	table tr td input#po_quantity,
	table tr td input#rr_quantity,
	table tr td input#difference,
	table tr td input#rr_qty,
	table tr td input#total_qty_rr,
	table tr td input#total_variance,
	table tr td input#total_rr
		{ text-align: right; }
</style>
<script type="text/javascript">
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
		}
	}
	function computeQtyRR(){
		var dtlcnt = document.getElementById("txtDtlCnt").value;
		var arrItems = document.getElementById("txtItemsArr").value;
		var item = arrItems.split(':');
		var total = 0;
		var totalvar = 0;
		var fld = 0;
		var totalrr = 0;
		for(var i=0;i<item.length;i++){
			var vari = 0;
			var rrttl = 0;
			if(document.getElementById("txt"+item[i]).value == ""){
			}else{
				if(!isNaN(document.getElementById("txt"+item[i]).value)){
					total = (parseFloat(total) + parseFloat(document.getElementById("txt"+item[i]).value));
					rrttl = (parseFloat(document.getElementById("txtprice"+item[i]).value) * parseFloat(document.getElementById("txt"+item[i]).value));
					
					vari = (parseFloat(document.getElementById("txt"+item[i]).value) - parseFloat(document.getElementById("txtpoed"+item[i]).value));
					document.getElementById("txtvar"+item[i]).value = vari;
					document.getElementById("txtrrtotal"+item[i]).value = rrttl;
					totalvar = (parseFloat(totalvar) + parseFloat(vari));
					
				}else{
					alert("please enter correct quantity!");
					document.getElementById("txt"+item[i]).value = 0;
					document.getElementById("txt"+item[i]).focus();

					total = (parseFloat(total) + 0.00);
					
					vari = (parseFloat(document.getElementById("txtpoed"+item[i]).value) - 0.00);
					document.getElementById("txtvar"+item[i]).value = vari;
					totalvar = (parseFloat(totalvar) + parseFloat(vari));
				}
			}
			totalrr = (parseFloat(totalrr) + parseFloat(rrttl));
		}
		
		document.getElementById("total_qty_rr").value = total;
		document.getElementById("total_variance").value = totalvar;
		document.getElementById("total_rr").value = totalrr;
	}
</script>
<body>
	<? if(!empty($rrdate)){ ?>
	<table>
		<tr>
			<td valign="middle">
				<a href="rr_print.php?porefno=<?=$id;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_RR.png" width="80" height="48" style="pointer: cursor; width: 67px;" border="0" /></div></a>
			</td>
		</tr>
	</table>
	<? } ?>

	<form method="post" name="parts_po" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_po" name="form_po">
	<legend>
	<p id="title">RECEIVING</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="po_no">RR Reference No:</label>
			<td class ="input"><input type="text" readonly name="rr_no" value="<?=$rrrefno;?>" style="width:272px"></td>
			<td class ="label"><label name="po_no">RR Date:</label>
			<td class ="input"><input type="text" readonly name="rr_date" value="<?=dateFormat($rrdate,"M d, Y");?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="po_no">PO Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$id;?>" style="width:272px"></td>
			<? if(!empty($podate)){ ?>
			<td class ="label"><label name="po_no">PO Date:</label>
			<td class ="input"><input type="text" readonly name="po_date" value="<?=dateFormat($podate,"M d, Y");?>" style="width:272px"></td>
			<? } ?>
		</tr>
		<tr>
			<td class ="label"><label name="supplier">Supplier:</label>
			<td class ="input"><input type="text" readonly name="supplier_name" value="<?=$suppliername;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="deliver_to">Deliver To:</label>
			<td class ="input"><input readonly type="text" name="deliver_to" id="deliver_to" value="<?=$deliverto;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="delivery_address">Delivery Address:</label>
			<td class ="input"><input readonly type="text" name="delivery_address" id="delivery_address" value="<?=$deliveryaddress;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="total_amount">Special Instructions:</label>
			<td class ="input">
				<textarea name="special" id="special" disabled cols="36" rows="5"><?=$special;?></textarea>
			</td>
		</tr>
		<!-- <tr>
			<td class ="label"><label name="po_quantity">PO Quantity:</label>
			<td class ="input"><input readonly type="text" name="po_quantity" id="po_quantity" value="<?=$poquantity;?>" style="width:272px"></td>
		</tr> -->
		<tr>
			<td class ="label"><label name="status">Status:</label>
			<td class ="input"><input readonly type="text" name="status" id="status" value="<?=$statusdesc;?>" style="width:272px"></td>
		</tr>
	</table>
	</fieldset>

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
			<th width="100">QTY RECEIVED</th>
			<th width="100">VARIANCE</th>
			<th width="100">TOTAL</th>
		</tr>
		<? 
			$subtotal = 0;
			$totalqty = 0;
			$cnt = 1;
			$arrItems = null;
			$nArrItem = explode("|",$nArrItems);
			$ttlrrqty = 0;
			$ttlvari = 0;
			for($i=0;$i<count($nArrItem);$i++){ 
				$val = explode(":",$nArrItem[$i]);

				$total = ($val[4] * $val[5]);
				$subtotal += $total;
				$totalqty += $val[5];

				$arrItems .= $val[0] . ":";

				if($val[6] == "" || $val[6] == 0){
					$rrqty = null;
				}
				
				$ttlrrqty += $val[6];
				
				$vari = ($val[5] - $val[6]);
				if($val[6] == 0){
					$vari = 0;
				}

				$ttlvari += $vari;
				$ttlrr += $val[7];
		?>
		<style type="text/css">
			table tr td input#txt<?=$val[0];?>,
			table tr td input#txtvar<?=$val[0];?>,
			table tr td input#txtrrtotal<?=$val[0];?>
				{ text-align: right; }
		</style>
		<tr>
			<td><?=$val[0];?></td>
			<td><?=$val[1];?></td>
			<td align="center"><?=$val[3];?></td>
			<td align="right"><?=number_format($val[4],2);?></td>
			<td align="center"><?=$val[5];?></td>
			<input type="hidden" name="txtpoed<?=$val[0];?>" id="txtpoed<?=$val[0];?>" value="<?=$val[5];?>" />
			<input type="hidden" name="txtprice<?=$val[0];?>" id="txtprice<?=$val[0];?>" value="<?=$val[4];?>" />
			<td align="center"><input type="text" name="txt<?=$val[0];?>" id="txt<?=$val[0];?>" value="<?=$val[6];?>" <?=$readonly;?> size="10" onBlur="return computeQtyRR();" /></td>
			<td align="center"><input type="text" name="txtvar<?=$val[0];?>" id="txtvar<?=$val[0];?>" value="<?=$vari;?>" <?=$readonly;?> size="10" readonly /></td>
			<td align="center"><input type="text" name="txtrrtotal<?=$val[0];?>" id="txtrrtotal<?=$val[0];?>" value="<?=$val[7];?>" <?=$readonly;?> size="10" readonly /></td>
			<!-- <td align="right"><? //=number_format($total,2);?></td> -->
		</tr>
		<? $cnt++; } $arrItems = rtrim($arrItems,":"); ?>
		<input type="hidden" name="txtDtlCnt" id="txtDtlCnt" value="<?=$cnt-1;?>" />
		<input type="hidden" name="txtItemsArr" id="txtItemsArr" value="<?=$arrItems;?>">
		<tr>
			<td colspan="10"><hr /></td>
		</tr>
		<tr>
			<td colspan="4" align="right"><b>TOTAL >>>>>>>>>></b></td>
			<td align="center"><b><?=$totalqty;?></b></td>
			<td align="center"><input type="text" readonly name="total_qty_rr" id="total_qty_rr" size="10" value="<?=$ttlrrqty;?>" /></td>
			<td align="center"><input type="text" readonly name="total_variance" id="total_variance" size="10" value="<?=$ttlvari;?>" /></td>
			<td align="center"><input type="text" readonly name="total_rr" id="total_rr" size="10" value="<?=$ttlrr;?>" /></td>
			<td>&nbsp;</td>
		</tr>
		</table>
		</div>
	</fieldset>
	
	<fieldset form="form_payments" name="form_payments">
	<legend><p id="title">Payments</p></legend>
		<table>
			<tr>
				<td class ="label"><label name="payment_terms">Payment Terms:</label>
				<td class ="input"><select <?=$disabled;?> name="payment_terms" id="payment_terms">
					<option value="">-- Select Payment Terms --</option>
					<? 
						foreach($result_payterms as $row_payterms){
							$selected = null;
							if($row_payterms['payment_term_code'] == $paymentterm){
								$selected = 'selected';
							}
					?>
						<option value="<?=$row_payterms['payment_term_code'];?>" <?=$selected;?>><?=$row_payterms['description'];?></option>
					<? } ?>
				</select></td>
			</tr>    
			<tr>
				<td class ="label"><label name="discount">Discount:</label>
				<td class ="input"><input readonly type="text" name="discount" id="discount" value="<?=number_format($discount,2);?>" onKeyPress="return IsNumeric(discount);" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="subtotal">Sub-Total:</label>
				<td class ="input"><input type="text" readonly name="subtotal" id="subtotal" value="<?=number_format($subtotal,2);?>" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="vat">Vat:</label>
				<td class ="input"><input type="text" readonly name="vat" id="vat" value="<?=number_format($vat,2);?>" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="total_amount">Total Amount:</label>
				<td class ="input"><input type="text" readonly name="total_amount" id="total_amount" value="<?=number_format($totalamount,2);?>" style="width:170px"></td>
			</tr>
			<? if($status == 1 || $status == 10){ ?>
			<tr>
				<td class ="label"><label name="status">Status:</label>
				<td class ="input"><select name="status" id="status">
					<option value="0">Update RR</option>
					<? if($status == 1){ ?><option value="1">Approve</option><? } ?>
				</select></td>
			</tr>
			<? } ?>
		</table>
	</fieldset>
	</span>
	<? if($status == 1 || $status == 10){ ?>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="rr_list.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	<? } ?>
	</form>
	<script type="text/javascript">
		
		function ValidateMe(){
			var rrqty = document.getElementById("rr_quantity");
			var paymentterms = document.getElementById("payment_terms");
			
			if(rrqty.value == ""){
				alert("RR Qty is required! Please enter RR quantity.");
				rrqty.focus();
				return false;
			}else if(paymentterms.value == ""){
				alert("Payment terms is required! Please select payment terms");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>