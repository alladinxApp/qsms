<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$parts = strtoupper($_POST['parts']);
		$parts_discount = str_replace(",","",$_POST['parts_discount']);
		$part_srp = str_replace(",","",$_POST['part_srp']);
		$part_onhand = $_POST['part_onhand'];
		$parts_lowstock = $_POST['parts_lowstock'];
		$partstatus = $_POST['partstatus'];
		$new_price_date	= date("Y-m-d h:i:s");
		$newnum = getNewNum('PARTS');
		
		$parts_insert = "INSERT INTO tbl_parts (parts_id, parts, parts_discount, part_srp, part_onhand, parts_lowstock, partstatus, part_created, new_price_date) VALUES
		('".$newnum."',
		'".$parts."',
		'".$parts_discount."',
		'".$part_srp."',
		'".$part_onhand."',
		'".$parts_lowstock."',
		'".$partstatus."',
		'".$today."',
		'".$new_price_date."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'PARTS' ";
		
		$res = mysql_query($parts_insert) or die("INSERT PARTS ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your parts! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Parts successfully saved.");</script>';
		}
		echo '<script>window.location="parts_list.php";</script>';
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
		alert(IsNumber);
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
		var srp = document.getElementById("part_srp").value;
		var discprice = document.getElementById("parts_discount").value;

		var discounted = ((parseFloat(discprice) * 0.35) + parseFloat(discprice));
		
		if(srp < discounted){
			ret = 1;
		}

		return ret;
	}
</script>
<body>
	<form method="post" name="parts_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_parts" name="form_parts">
	<legend><p id="title">Parts Item Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_parts_id">Parts Item Code:</label>
			<td class ="input"><input type="text" name="parts_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_parts">Parts Description:</label>
			<td class ="input"><input type="text" name="parts" id="parts" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_parts_discount">Discounted Price:</label>
			<td class ="input"><input type="text" name="parts_discount" id="parts_discount" value="" onBlur="return CurrencyFormatted('parts_discount'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_part_srp">Standard Retail Price:</label>
			<td class ="input"><input type="text" name="part_srp" id="part_srp" value="" onBlur="return CurrencyFormatted('part_srp'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_part_onhand">Stock On Hand:</label>
			<td class ="input"><input type="text" name="part_onhand" id="part_onhand" value="" onkeypress="return isNumberKey(event);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_parts_lowstock">Low Stock Quantity:</label>
			<td class ="input"><input type="text" name="parts_lowstock" id="parts_lowstock" value="" onkeypress="return isNumberKey(event);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_partstatus">Parts Status:</label>
			<td class="input"><input type="radio" name="partstatus" id="partstatus" value="Active" checked>Active <input type="radio" name="partstatus" id="partstatus" value="Inactive">Inactive</td>
		</tr>    
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="parts_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		
		function ValidateMe(){
			var parts = document.getElementById("parts");
			var parts_discount = document.getElementById("parts_discount");
			var part_srp = document.getElementById("part_srp");
			var part_onhand = document.getElementById("part_onhand");
			var parts_lowstock = document.getElementById("parts_lowstock");
			var partstatus = document.getElementById("partstatus");
			
			if(parts.value == ""){
				alert("Parts is required! Please enter parts.");
				parts.focus();
				return false;
			}else if(parts_discount.value == ""){
				alert("Parts discount is required! Please enter parts discount.");
				parts_discount.focus();
				return false;
			}else if(part_srp.value == ""){
				alert("Parts SRP is required! Please enter parts SRP.");
				part_srp.focus();
				return false;
			}else if(part_onhand.value == ""){
				alert("Parts on hand is required! Please enter parts on hand.");
				part_onhand.focus();
				return false;
			}else if(parts_lowstock.value == ""){
				alert("Parts low stock QTY is required! Please enter parts low stock QTY.");
				parts_lowstock.focus();
				return false;
			}else if(getSRP() > 0){
				alert("Please enter a valid Parts Standard Retail Price! \n formula: discounted price * 35% + discounted price.");
				part_srp.value = "";
				part_srp.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>