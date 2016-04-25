<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$qry_uom = "SELECT * FROM v_uom ORDER BY description";
	$result_uom = $dbo->query($qry_uom);

	$qry_item_type = "SELECT * FROM v_item_type ORDER BY description";
	$result_item_type = $dbo->query($qry_item_type);

	$itemcode = $_GET['itemcode'];
	$qry = "SELECT * FROM tbl_items WHERE item_code = '$itemcode'";
	$result = $dbo->query($qry);
	
	foreach($result as $row){
		$desc = $row['item_description'];
		$sapitemcode = $row['SAP_item_code'];
		$unitprice = $row['unit_price'];
		$uom = $row['UOM'];
		$lowstockvalue = $row['low_stock_value'];
		$lastunitprice = $row['last_unit_price'];
		$itemtype = $row['item_type'];
		$status = $row['status'];
	}
	
	if (isset($_POST['update'])){
		
		$desc = strtoupper($_POST['description']);
		$sapitemcode = strtoupper($_POST['sap_item_code']);
		$unitpice = str_replace(",","",$_POST['unit_price']);
		$uom = $_POST['uom'];
		$lowstockvalue = $_POST['low_stock_value'];
		$lastunitprice = str_replace(",","",$_POST['last_unit_price']);
		$itemtype = $_POST['item_type'];
		$status = $_POST['status'];
					   
		$uom_update = "UPDATE tbl_items SET 
			SAP_item_code = '$sapitemcode',
			item_description = '$desc',
			unit_price = '$unitprice',
			UOM = '$uom',
			low_stock_value = '$lowstockvalue',
			last_unit_price = '$lastunitprice',
			item_type = '$itemtype',
			status = '$status'
		WHERE item_code = '$itemcode'";

		$res = mysql_query($uom_update) or die("UPDATE UOM ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your Item profile! Please double check all the data and Re-update.");</script>';
		}else{
			echo '<script>alert("Item profile successfully update.");</script>';
		}
		echo '<script>window.location="items_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</head>
<script type="text/javascript">
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
	function getSRP(){
		var ret = 0;
		var srp = document.getElementById("access_srp").value;
		var discprice = document.getElementById("access_disc").value;

		var discounted = ((parseFloat(discprice) * 0.35) + parseFloat(discprice));
		
		if(srp < discounted){
			ret = 1;
		}

		return ret;
	}
</script>
<body>
	<form method="post" name="item_form" class="form" onsubmit="return ValidateMe();">
	<fieldset form="form_item" name="form_item">
	<legend><p id="title">Items Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="item_code">Item Code:</label>
			<td class ="input"><input type="text" name="item_code" value="<?=$itemcode;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="sap_item_code">SAP Item Code:</label>
			<td class ="input"><input value="<?=$sapitemcode;?>" type="text" name="sap_item_code" id="sap_item_code" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="description">Description:</label>
			<td class ="input"><input value="<?=$desc;?>" type="text" name="description" id="description" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="unit_price">Unit Price:</label>
			<td class ="input"><input value="<?=$unitprice;?>" type="text" name="unit_price" id="unit_price" value="" onBlur="return CurrencyFormatted('unit_price'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="uom">UOM:</label>
			<td class ="input"><select type="text" name="uom" id="uom">
				<option value="">select UOM</option>
				<? foreach($result_uom as $row_uom){ 
						$selected = null;
						if($row_uom['uom_code'] == $uom){
							$selected = 'selected';
						}
					?>
				<option value="<?=$row_uom['uom_code'];?>" <?=$selected;?>><?=$row_uom['description'];?></option>
				<? } ?>
			</select></td>
		</tr>    
		<tr>
			<td class ="label"><label name="low_stock_value">Low Stock Value:</label>
			<td class ="input"><input value="<?=$lowstockvalue;?>" type="text" name="low_stock_value" id="low_stock_value" onkeypress="return isNumberKey(event);" value="" style="width:272px"></td>
		</tr> 
		<tr>
			<td class ="label"><label name="last_unit_price">Last Unit Price:</label>
			<td class ="input"><input value="<?=$lastunitprice;?>" type="text" name="last_unit_price" id="last_unit_price" onBlur="return CurrencyFormatted('last_unit_price'); return IsNumeric(this.value);" value="" style="width:272px"></td>
		</tr>   
		<tr>
			<td class ="label"><label name="item_type">Item Type:</label>
			<td class ="input"><select type="text" name="item_type" id="item_type">
				<option value="">select Item Type</option>
				<? foreach($result_item_type as $row_item_type){ 
						$selected = null;
						if($row_item_type['item_type_code'] == $itemtype){
							$selected = 'selected';
						}
					?>
				<option value="<?=$row_item_type['item_type_code'];?>" <?=$selected;?>><?=$row_item_type['description'];?></option>
				<? } ?>
			</select></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href="items_list.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var sapitemcode = document.getElementById("sap_item_code");
			var desc = document.getElementById("description");
			var unitprice = document.getElementById("unit_price");
			var uom = document.getElementById("uom");
			var lowstockvalue = document.getElementById("low_stock_value");
			var itemtype = document.getElementById("item_type");
			
			if(sapitemcode.value == ""){
				alert("SAP Item Code is required! Please enter SAP Item Code.");
				sapitemcode.focus();
				return false;
			}else if(desc.value == ""){
				alert("Description is required! Please enter description.");
				desc.focus();
				return false;
			}else if(unitprice.value == ""){
				alert("Unit Price is required! Please enter Unit Price.");
				unitprice.focus();
				return false;
			}else if(uom.value == ""){
				alert("UOM is required! Please select UOM.");
				uom.focus();
				return false;
			}else if(lowstockvalue.value == ""){
				alert("Low Stock Value is required! Please enter Low Stock Value.");
				lowstockvalue.focus();
				return false;
			}else if(itemtype.value == ""){
				alert("Item Type is required! Please select Item Type.");
				itemtype.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>