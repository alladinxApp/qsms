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
		$suppliercode = $row['supplier_code'];
		$deliverto = $row['deliver_to'];
		$deliveryaddress = $row['delivery_address'];
		$paymentterm = $row['payment_code'];
		$discount = $row['discount'];
		$subtotal = $row['sub_total'];
		$vat = $row['vat'];
		$totalamount = $row['total_amount'];
		$special = $row['special_instruction'];
		$status = $row['status'];
	}

	$readonly = null;
	$disabled = null;
	if($status > 0){
		$readonly = 'readonly';
		$disabled = 'disabled';
	}

	$nArrItems = null;
	foreach($result_po_dtl as $row){
		$itemcode = $row['item_code'];
		$itemdesc = $row['item_description'];
		$itemuom = $row['UOM'];
		$itemuomdesc = $row['UOM_desc'];
		$itemprice = $row['price'];
		$itemqty = $row['quantity'];
		$nArrItems .= $itemcode . ":" . $itemdesc . ":" . $itemuom . ":" . $itemuomdesc . ":" . $itemprice . ":" . $itemqty . "|";
	}

	if($num_po_dtl > 0){
		$nArrItems = rtrim($nArrItems,"|");
	}

	if (isset($_POST['update'])){
		$supplier = $_POST['supplier'];
		$deliverto = $_POST['deliver_to'];
		$deliveryaddress = $_POST['delivery_address'];
		$special = $_POST['special'];
		$paymentterms = $_POST['payment_terms'];
		$discount = $_POST['discount'];
		$subtotal = $_POST['subtotal'];
		$vat = $_POST['vat'];
		$totalamount = $_POST['total_amount'];
		$item = explode("|",$_POST['arrItems']);
		$status = $_POST['status'];

		$po_mst_upd = "UPDATE tbl_po_mst 
			SET supplier_code = '$supplier'
				,deliver_to = '$deliverto'
				,delivery_address = '$deliveryaddress'
				,payment_code = '$paymentterms'
				,discount = '$discount'
				,sub_total = '$subtotal'
				,vat = '$vat'
				,total_amount = '$totalamount'
				,special_instruction = '$special'
				,modified_date = '$today'
				,modified_by = '$_SESSION[username]'
				,status = '$status'
			WHERE po_reference_no = '$id'";
		
		$res = mysql_query($po_mst_upd) or die("UPDATE PO ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your RO! Please double check all the data and save.");</script>';
		}else{
			$cnt = 1;
			mysql_query("DELETE FROM tbl_po_dtl WHERE po_reference_no = '$id'");
			for($i=0;$i<count($item);$i++){
				$val = explode(":",$item[$i]);
				$qry_dtl = mysql_query("INSERT INTO tbl_po_dtl(po_reference_no,item_code,price,quantity,seqno) VALUES
					('".$id."',
					'".$val[0]."',
					'".$val[4]."',
					'".$val[5]."',
					'".$cnt."')");
				$cnt++;
			}

			$msg = 'PO successfully ';
			$url = 'po_list.php';
			switch($status){
				case "1": $msg .= 'approved.'; $url = 'po_edit.php?id='.$id; break;
				case "2": $msg .= 'disapproved.'; break;
				default: $msg .= 'updated'; break;
			}

			mysql_query($update_controlno);
			echo '<script>alert("' . $msg . '");</script>';
		}
		echo '<script>window.location="'.$url.'";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<script type="text/javascript">
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp = false;	
		try{
			xmlhttp = new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp = false;
				}
			}
		}
		return xmlhttp;
	}
	function CurrencyFormatted(fld){
		amount = document.getElementById(fld).value;
		var i = parseFloat(amount);
		if(isNaN(i)) { i = 0.00; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		i = parseInt((i + .005) * 100);
		i = i / 100;
		s = new String(i);
		if(s.indexOf('.') < 0) { s += '.00'; }
		if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
		s = minus + s;
		var t = CommaFormatted(s);
		document.getElementById(fld).value = t;
	}
	function CommaFormatted(amount){
		var delimiter = ","; // replace comma if desired
		amount = new String(amount);
		var a = amount.split('.',2)
		var d = a[1];
		var i = parseInt(a[0]);
		if(isNaN(i)) { return ''; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		var n = new String(i);
		var a = [];
		while(n.length > 3)
		{
			var nn = n.substr(n.length-3);
			a.unshift(nn);
			n = n.substr(0,n.length-3);
		}
		if(n.length > 0) { a.unshift(n); }
		n = a.join(delimiter);
		if(d.length < 1) { amount = n; }
		else { amount = n + '.' + d; }
		amount = minus + amount;
		return amount;
	}
	function IsNumeric(sText) {
		var ValidChars = "0123456789.,";
		var IsNumber=true;
		var Char;

		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1) {
				IsNumber = false;
			}
		}
		return IsNumber;
	}
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
		}
	}
	function addItem(){
		var id = document.getElementById("item").value;
		var qty = document.getElementById("qty").value;
		var arrItems = document.getElementById("arrItems").value;

		if(id == ""){
			alert("Please select Item to add!.");
			return false;
		}
		if(qty == ""){
			alert("Please enter quantity of item selected!.");
			return false;
		}

		var strURL = "divPOAddItem.php?id="+id+"&qty="+qty+"&items="+arrItems;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divPODtls').innerHTML = req.responseText;
						document.getElementById("qty").value = "";
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function removeItem(id){
		var arrItems = document.getElementById("arrItems").value;
		var strURL = "divPORemoveItem.php?id="+id+"&items="+arrItems;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divPODtls').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function discounted(){
		var subt = document.getElementById("subtotal").value;
		var disc = document.getElementById("discount").value;

		var discounted = (parseFloat(subt) - parseFloat(disc));

		var vat = (parseFloat(discounted) * 0.12);
		document.getElementById("vat").value = vat.toFixed(2);

		var total = (parseFloat(discounted) + parseFloat(vat));
		document.getElementById("total_amount").value = total.toFixed(2);
	}
</script>
<style type="text/css">
	div.PODtls { border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.PODtls table{ border: 1px solid #ccc; font-size: 12px; }
	div.PODtls table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }

	textarea#special { resize: none; }
	span#divPODtls table tr td input#discount,
	span#divPODtls table tr td input#subtotal,
	span#divPODtls table tr td input#vat,
	span#divPODtls table tr td input#total_amount
		{ text-align: right; }
</style>
<body>
	<? if($status == 1){ ?>
	<table>
		<tr>
			<td valign="middle">
				<a href="po_print.php?id=<?=$id;?>" target="_blank"><div style="width:100px; height:50px; text-align: center;"><img src="images/print_est.png" width="67" height="47" style="pointer: cursor; width: 67px;" border="0" /></div></a>
			</td>
		</tr>
	</table>
	<? } ?>

	<form method="post" name="parts_po" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_po" name="form_po">
	<legend>
	<p id="title">Create P.O.</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="po_no">PO Reference No:</label>
			<td class ="input"><input type="text" name="po_no" value="<?=$id;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="supplier">Supplier:</label>
			<td class ="input"><select name="supplier" id="supplier" <?=$disabled;?>>
				<option value="">-- Select Supplier --</option>
				<? 
					foreach($result_supplier as $row_supplier){
						$selected = null;
						if($row_supplier['supplier_code'] == $suppliercode){
							$selected = 'selected';
						}
				?>
					<option value="<?=$row_supplier['supplier_code'];?>" <?=$selected;?>><?=$row_supplier['supplier_name'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td class ="label"><label name="deliver_to">Deliver To:</label>
			<td class ="input"><input <?=$readonly;?> type="text" name="deliver_to" id="deliver_to" value="<?=$deliverto;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="delivery_address">Delivery Address:</label>
			<td class ="input"><input <?=$readonly;?> type="text" name="delivery_address" id="delivery_address" value="<?=$deliveryaddress;?>" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="total_amount">Special Instructions:</label>
			<td class ="input">
				<textarea name="special" id="special" <?=$readonly;?> cols="36" rows="5"><?=$special;?></textarea>
			</td>
		</tr>
	</table>
	</fieldset>
	<? if($status == 0){ ?>
	<div>
	<table>
		<tr>
			<td>Add Item: </td>
			<td><select name="item" id="item">
				<option value="">-- Select Item --</option>
				<? foreach($result_items as $row_items){ ?>
					<option value="<?=$row_items['item_code'];?>"><?=$row_items['item_description'];?> ( <?=$row_items['UOM_desc'];?> )</option>
				<? } ?>
			</select></td>
			<td class ="input"><input type="text" name="qty" id="qty" value="" style="width:100px"></td>
			<td><input type="button" value="" onClick="return addItem();" style="cursor: pointer;" /></td>
		</tr>
	</table>
	</div>
	<? } ?>
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
			<td align="center">
				<? if($status == 0){ ?><a href="#" onClick="removeItem('<?=$val[0];?>');"><img src="images/del_ico.png" width="15" /></a><? } ?>
			</td>
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
		<?
			$vat = ($subtotal * 0.12);
			$totalamnt = ($subtotal + $vat);
		?>
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
				<td class ="input"><input <?=$readonly;?> type="text" name="discount" id="discount" value="<?=number_format($discount,2);?>" onKeyPress="return IsNumeric(discount);" style="width:170px"></td>
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
			<? if($status == 0){ ?>
			<tr>
				<td class ="label"><label name="status">Status:</label>
				<td class ="input"><select name="status" id="status">
					<option value="0">Pending/Update</option>
					<option value="1">Approve</option>
					<option value="2">Cancel/Disapprove</option>
				</select></td>
			</tr>
			<? } ?>
		</table>
	</fieldset>
	<input type="hidden" name="arrItems" id="arrItems" value="<?=$nArrItems;?>" />
	</span>
	<? if($status == 0){ ?>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="po_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	<? } ?>
	</form>
	<script type="text/javascript">
		
		function ValidateMe(){
			var supplier = document.getElementById("supplier");
			var deliverto = document.getElementById("deliver_to");
			var deliveryaddress = document.getElementById("delivery_address");
			var paymentterms = document.getElementById("payment_terms");
			var items = document.getElementById("arrItems")
			
			if(supplier.value == ""){
				alert("Supplier is required! Please select supplier.");
				return false;
			}else if(deliverto.value == ""){
				alert("Deliver to is required! Please enter deliver to.");
				deliverto.focus();
				return false;
			}else if(deliveryaddress.value == ""){
				alert("Delivery address is required! Please enter delivery address.");
				deliveryaddress.focus();
				return false;
			}else if(paymentterms.value == ""){
				alert("Payment terms is required! Please select payment terms");
				return false;
			}else if(items.value == ""){
				alert("Item(s) is required! Please select items");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>