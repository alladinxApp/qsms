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
		$paymentterm = $row['payment_term'];
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
		$rrrefno = $row['rr_reference_no'];
		$rrdate = $row['rr_date'];
		$postrefno = $row['rr_post_reference_no'];
		$postdate = $row['rr_post_date'];

		$cvrefno = '[SYSTEM GENERATED]';
		if(!empty($row['cv_reference_no'])){
			$cvrefno = $row['cv_reference_no'];
		}

		$paymentdate = null;
		if(!empty($row['payment_date'])){
			$paymentdate = $row['payment_date'];
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
		$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . ":" . $itemrr . "|";
	}

	if($num_po_dtl > 0){
		$nArrItems = rtrim($nArrItems,"|");
	}

	if (isset($_POST['update'])){
		$newnum = getNewNum('CV_REFERENCE');

		switch($_POST['status']){
			case 1: // BILLED
					$po_mst_upd = "UPDATE tbl_po_mst 
						SET cv_reference_no = '$newnum'
							,payment_date = '$today'
							,billed_by = '$_SESSION[username]'
							,status = '12'
						WHERE po_reference_no = '$id'";

					$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'CV_REFERENCE' ";
					
					$res = mysql_query($po_mst_upd) or die("UPDATE Billing ".mysql_error());
					
					if(!$res){
						echo '<script>alert("There has been an error on billing your PO! Please double check all the data and save.");</script>';
					}else{
						mysql_query($update_controlno);
						echo '<script>alert("PO successfully billed.");</script>';
					}
					echo '<script>window.location="po_payment_edit.php?id='.$id.'";</script>';
				break;
			default:
					$po_mst_upd = "UPDATE tbl_po_mst 
							SET rr_post_reference_no = NULL
								,rr_post_date = NULL
								,status = '10'
							WHERE po_reference_no = '$id'";
					mysql_query($po_mst_upd);
					echo '<script>alert("RR successfully unposted.");</script>';
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
	span#divPODtls table tr td input#payterm,
	table tr td input#po_quantity,
	table tr td input#rr_quantity,
	table tr td input#difference
		{ text-align: right; }
</style>
<body>
	<? if(!empty($paymentdate)){ ?>
	<table>
		<tr>
			<td valign="middle">
				<a href="po_payment_print.php?porefno=<?=$id;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_CV.png" width="80" height="48" style="pointer: cursor; width: 67px;" border="0" /></div></a>
			</td>
		</tr>
	</table>
	<? } ?>
	<form method="post" name="parts_po" class="form">
	<fieldset form="form_po" name="form_po">
	<legend>
	<p id="title">PAYMENT</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="po_no">CV Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$cvrefno;?>" style="width:272px"></td>
			<? if(!empty($paymentdate)){ ?>
			<td class ="label"><label name="po_no">CV Date:</label>
			<td class ="input"><input type="text" readonly name="po_date" value="<?=dateFormat($paymentdate,"M d, Y");?>" style="width:272px"></td>
			<? } ?>
		</tr>
		<tr>
			<td class ="label"><label name="po_no">Post Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$postrefno;?>" style="width:272px"></td>
			<td class ="label"><label name="po_no">Post Date:</label>
			<td class ="input"><input type="text" readonly name="po_date" value="<?=dateFormat($postdate,"M d, Y");?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="po_no">RR Reference No:</label>
			<td class ="input"><input type="text" readonly name="rr_no" value="<?=$rrrefno;?>" style="width:272px"></td>
			<td class ="label"><label name="po_no">RR Date:</label>
			<td class ="input"><input type="text" readonly name="rr_date" value="<?=dateFormat($rrdate,"M d, Y");?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="po_no">PO Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$id;?>" style="width:272px"></td>
			<td class ="label"><label name="po_no">PO Date:</label>
			<td class ="input"><input type="text" readonly name="po_date" value="<?=dateFormat($podate,"M d, Y");?>" style="width:272px"></td>
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
			<td class ="label"><label name="rr_quantity">RR Qty:</label>
			<td class ="input"><input onKeyup="return getDifference();" type="text" readonly name="rr_quantity" id="rr_quantity" value="<?=$rrquantity;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="difference">Difference:</label>
			<td class ="input"><input readonly type="text" name="difference" id="difference" value="<?=$difference;?>" style="width:272px"></td>
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
			<th width="100">RECEIVED</th>
			<th width="100">VARIANCE</th>
			<th width="100">TOTAL</th>
		</tr>
		<? 
			$subtotal = 0;
			$totalqty = 0;
			$nArrItem = explode("|",$nArrItems); 
			for($i=0;$i<count($nArrItem);$i++){ 
				$val = explode(":",$nArrItem[$i]);

				$total = ($val[4] * $val[6]);
				$subtotal += $total;
				$totalqty += $val[5];
				$totalrrqty += $val[6];
				$var = ($val[5] - $val[6]);
				$totalvar += $var;
		?>
		<tr>
			<td><?=$val[0];?></td>
			<td><?=$val[1];?></td>
			<td align="center"><?=$val[3];?></td>
			<td align="right"><?=number_format($val[4],2);?></td>
			<td align="center"><?=$val[5];?></td>
			<td align="center"><?=$val[6];?></td>
			<td align="center"><?=$var;?></td>
			<td align="right"><?=number_format($total,2);?></td>
		</tr>
		<? } ?>
		<tr>
			<td colspan="10"><hr /></td>
		</tr>
		<tr>
			<td colspan="4" align="right"><b>TOTAL >>>>>>>>>></b></td>
			<td align="center"><b><?=$totalqty;?></b></td>
			<td align="center"><b><?=$totalrrqty;?></b></td>
			<td align="center"><b><?=$totalvar;?></b></td>
			<td align="right"><b><?=number_format($subtotal,2);?></b></td>
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
				<td class ="input"><input readonly type="text" name="payterm" id="payterm" value="<?=$paymentterm;?>" style="width:170px"></td>
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
			<? if($status == 10 || $status == 11){ ?>
			<tr>
				<td class ="label"><label name="status">Status:</label>
				<td class ="input"><select name="status" id="status">
					<option value="1">Billed</option>
					<option value="0">Unpost</option>
				</select></td>
			</tr>
			<? } ?>
		</table>
	</fieldset>
	</span>
	<? if(empty($paymentdate)){ ?>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="rr_list.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	<? } ?>
	</form>
</body>
</html>