<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$id = $_GET['id'];

	$qry_po_mst = "SELECT * FROM v_po_mst WHERE po_reference_no = '$id'";
	$result_po_mst = $dbo->query($qry_po_mst);

	foreach($result_po_mst as $row){
		$porefno = $row['po_reference_no'];
		$status = $row['status'];
		$statusdesc = $row['status_desc'];
		$postrefno = $row['rr_post_reference_no'];
		$postdate = $row['rr_post_date'];
		$podate = $row['po_date'];
		$suppliername = $row['supplier_name'];
		$deliverto = $row['deliver_to'];
		$deliveryaddress = $row['delivery_address'];
		$paymentterm = $row['payment_term'];
		$special = $row['special_instruction'];

		$cvrefno = '[SYSTEM GENERATED]';
		if(!empty($row['cv_reference_no'])){
			$cvrefno = $row['cv_reference_no'];
		}

		$paymentdate = null;
		$statdesc = "POSTED";
		if(!empty($row['payment_date'])){
			$paymentdate = $row['payment_date'];
			$statdesc = "BILLED";
		}
	}

	$qry_rr_mst = "SELECT * FROM v_rr_mst WHERE po_reference_no = '$id'";
	$result_rr_mst = $dbo->query($qry_rr_mst);

	$ttldiscount = 0;
	$ttlsubtotal = 0;
	$ttlvat = 0;
	$ttltotal = 0;
	foreach($result_rr_mst as $row){
		$ttldiscount += $row['discount'];
		$ttlsubtotal += $row['sub_total'];
		$ttlvat += $row['vat'];
		$ttltotal += $row['total_amount'];
	}

	$qry_rr_dtl = "SELECT * FROM v_rr_dtl WHERE po_reference_no = '$id'";
	$result_rr_dtl = $dbo->query($qry_rr_dtl);
	$num_rr_dtl = mysql_num_rows(mysql_query($qry_rr_dtl));

	$nArrItems = null;
	foreach($result_rr_dtl as $row){
		$itemcode = $row['item_code'];
		$itemdesc = $row['item_description'];
		$itemuom = $row['UOM'];
		$itemuomdesc = $row['UOM_desc'];
		$itemprice = $row['price'];
		$itemqty = $row['quantity'];
		$itemrr = $row['rr_quantity'];
		$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . "|";
	}

	if($num_rr_dtl > 0){
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
						WHERE po_reference_no = '$id'";

					$rr_mst_upd = "UPDATE tbl_rr_mst 
						SET cv_reference_no = '$newnum'
							,payment_date = '$today'
							,billed_by = '$_SESSION[username]'
							,status = '10'
						WHERE po_reference_no = '$id' ";

					$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'CV_REFERENCE' ";
					
					$res = mysql_query($po_mst_upd) or die("UPDATE Billing ".mysql_error());
					mysql_query($rr_mst_upd);
					
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
							WHERE po_reference_no = '$id'";
					$rr_mst_upd = "UPDATE tbl_rr_mst 
							SET rr_post_reference_no = NULL
								,rr_post_date = NULL
								,status = '0'
							WHERE po_reference_no = '$id'";
					mysql_query($po_mst_upd);
					mysql_query($rr_mst_upd);
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
			<td class ="label"><label name="po_no">PO Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$porefno;?>" style="width:272px"></td>
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
		<tr>
			<td class ="label"><label name="status">Status:</label>
			<td class ="input"><input readonly type="text" name="status" id="status" value="<?=$statdesc;?>" style="width:272px"></td>
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
			<th width="100">TOTAL</th>
		</tr>
		<? 
			$subtotal = 0;
			$totalqty = 0;
			$nArrItem = explode("|",$nArrItems); 
			for($i=0;$i<count($nArrItem);$i++){ 
				$val = explode(":",$nArrItem[$i]);

				$total = ($val[4] * $val[5]);
				$subtotal += $total;
				$totalqty += $val[5];
				$totalrrqty += $val[6];
		?>
		<tr>
			<td><?=$val[0];?></td>
			<td><?=$val[1];?></td>
			<td align="center"><?=$val[3];?></td>
			<td align="right"><?=number_format($val[4],2);?></td>
			<td align="center"><?=$val[5];?></td>
			<td align="right"><?=number_format($total,2);?></td>
		</tr>
		<? } ?>
		<tr>
			<td colspan="10"><hr /></td>
		</tr>
		<tr>
			<td colspan="4" align="right"><b>TOTAL >>>>>>>>>></b></td>
			<td align="center"><b><?=$totalqty;?></b></td>
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
				<td class ="input"><input readonly type="text" name="discount" id="discount" value="<?=number_format($ttldiscount,2);?>" onKeyPress="return IsNumeric(discount);" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="subtotal">Sub-Total:</label>
				<td class ="input"><input type="text" readonly name="subtotal" id="subtotal" value="<?=number_format($ttlsubtotal,2);?>" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="vat">Vat:</label>
				<td class ="input"><input type="text" readonly name="vat" id="vat" value="<?=number_format($ttlvat,2);?>" style="width:170px"></td>
			</tr>
			<tr>
				<td class ="label"><label name="total_amount">Total Amount:</label>
				<td class ="input"><input type="text" readonly name="total_amount" id="total_amount" value="<?=number_format($ttltotal,2);?>" style="width:170px"></td>
			</tr>
			<? if(empty($paymentdate)){ ?>
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