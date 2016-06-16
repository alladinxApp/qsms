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

	$qry_po_mst = "SELECT * FROM v_po_mst WHERE cv_reference_no = '$id'";
	$result_po_mst = $dbo->query($qry_po_mst);

	foreach($result_po_mst as $row){
		$podate = $row['po_date'];
		$porefno = $row['po_reference_no'];
		$cvdate = $row['payment_date'];
		$suppliername = $row['supplier_name'];
		$deliverto = $row['deliver_to'];
		$deliveryaddress = $row['delivery_address'];
		$paymentterm = $row['payment_term'];
		$special = $row['special_instruction'];
		$billpostedDt = $row['bill_posted_date'];

		if(empty($billpostedDt)){
			$statusdesc = "BILLED";
		}else{
			$statusdesc = "CLOSED";
		}

		$cvrefno = $row['cv_reference_no'];
	}

	$qry_rr_mst = "SELECT * FROM v_rr_mst WHERE po_reference_no = '$porefno'";
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

	$qry_rr_dtl = "SELECT * FROM v_rr_dtl WHERE po_reference_no = '$porefno'";
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
		$itemrrttl = $row['rr_total'];
		$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . ":" . $itemrrttl . "|";
	}

	if($num_rr_dtl > 0){
		$nArrItems = rtrim($nArrItems,"|");
	}

	if (isset($_POST['txtPost']) && !empty($_POST['txtPost']) && $_POST['txtPost'] == 1){

		$po_mst_upd = "UPDATE tbl_po_mst 
			SET bill_posted_date = '$today'
				,bill_posted_by = '$_SESSION[username]'
			WHERE cv_reference_no = '$id'";
		
		$rr_mst_upd = "UPDATE tbl_rr_mst 
			SET bill_posted_date = '$today'
				,bill_posted_by = '$_SESSION[username]'
				,status = '100'
			WHERE cv_reference_no = '$id'";

		$res = mysql_query($po_mst_upd) or die("UPDATE Posting ".mysql_error());
		mysql_query($rr_mst_upd);
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your Posting! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Payment successfully posted.");</script>';
		}
		echo '<script>window.location="po_posting_edit.php?id='.$id.'";</script>';
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
<script type="text/javascript">
	function getDifference(){
		var poqty = document.getElementById("po_quantity").value;
		var rrqty = document.getElementById("rr_quantity").value;

		var diff = parseInt(rrqty - poqty);

		document.getElementById("difference").value = diff;
	}
</script>
<body>
	<table>
		<tr>
			<td valign="middle">
				<a href="po_posting_print.php?cvrefno=<?=$id;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_CV_post.png" width="80" height="48" style="pointer: cursor; width: 67px;" border="0" /></div></a>
			</td>
		</tr>
	</table>
	<form method="post" name="parts_po" class="form">
	<fieldset form="form_po" name="form_po">
	<legend>
	<p id="title">PAYMENT</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="po_no">CV Reference No:</label>
			<td class ="input"><input type="text" readonly name="po_no" value="<?=$id;?>" style="width:272px"></td>
			<td class ="label"><label name="po_no">CV Date:</label>
			<td class ="input"><input type="text" readonly name="po_date" value="<?=dateFormat($cvdate,"M d, Y");?>" style="width:272px"></td>
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
			<th width="100">TOTAL</th>
		</tr>
		<? 
			$subtotal = 0;
			$totalqty = 0;
			$nArrItem = explode("|",$nArrItems); 
			for($i=0;$i<count($nArrItem);$i++){ 
				$val = explode(":",$nArrItem[$i]);

				$total =$val[6];
				$subtotal += $total;
				$totalqty += $val[5];
				$ttlrr += $val[7];
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
		</table>
	</fieldset>
	</span>
	<? if(empty($billpostedDt)){ ?>
	<p class="button">
		<input type="submit" value="" name="update" />
		<input type="hidden" name="txtPost" id="txtPost" value="1" />
		<a href="rr_list.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	<? } ?>
	</form>
</body>
</html>