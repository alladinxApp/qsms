<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$material = strtoupper($_POST['material']);
		$material_disc = str_replace(",","",$_POST['material_disc']);
		$material_srp = str_replace(",","",$_POST['material_srp']);
		$material_onhand = $_POST['material_onhand'];
		$material_lowstock = $_POST['material_lowstock'];
		$material_status = $_POST['material_status'];
		$newnum = getNewNum('MATERIAL');
					   
		$material_insert = "INSERT INTO tbl_material (material_id, material, material_disc, material_srp, material_onhand, material_lowstock, material_status, material_created) VALUES
		('".$newnum."',
		'".$material."',
		'".$material_disc."',
		'".$material_srp."',
		'".$material_onhand."',
		'".$material_lowstock."',
		'".$material_status."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'MATERIAL' ";
		
		$res = mysql_query($material_insert) or die("INSERT MATERIAL ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your material! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Material successfully saved.");</script>';
		}
		echo '<script>window.location="material_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</script>

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
		var srp = document.getElementById("material_srp").value;
		var discprice = document.getElementById("material_disc").value;

		var discounted = ((parseFloat(discprice) * 0.35) + parseFloat(discprice));
		
		if(srp < discounted){
			ret = 1;
		}

		return ret;
	}
</script>
<body>
	<form method="post" name="material_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_material" name="form_material">
	<legend><p id="title">Materials Item Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_material_id">Material Item Code:</label>
			<td class ="input"><input type="text" name="material_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_material">Material Description:</label>
			<td class ="input"><input type="text" name="material" id="material" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_material_disc">Discounted Price:</label>
			<td class ="input"><input type="text" name="material_disc" id="material_disc" value="" onBlur="return CurrencyFormatted('material_disc'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_material_srp">Standard Retail Price:</label>
			<td class ="input"><input type="text" name="material_srp" id="material_srp" value="" onBlur="return CurrencyFormatted('material_srp'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_material_onhand">Stock On Hand:</label>
			<td class ="input"><input type="text" name="material_onhand" id="material_onhand" value="" onkeypress="return isNumberKey(event);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_material_lowstock">Low Stock Quantity:</label>
			<td class ="input"><input type="text" name="material_lowstock" id="material_lowstock" value="" onkeypress="return isNumberKey(event);" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_material_status">Material Status:</label>
			<td class="input"><input type="radio" name="material_status" id="material_status" value="Active" checked>Active <input type="radio" name="material_status" id="material_status" value="Inactive">Inactive</td>
		</tr>    
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="material_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
	<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var material = document.getElementById("material");
			var material_disc = document.getElementById("material_disc");
			var material_srp = document.getElementById("material_srp");
			var material_onhand = document.getElementById("material_onhand");
			var material_lowstock = document.getElementById("material_lowstock");
			
			if(material.value == ""){
				alert("Material is required! Please enter material.");
				material.focus();
				return false;
			}else if(material_disc.value == ""){
				alert("Material discount price is required! Please enter material discount price.");
				material_disc.focus();
				return false;
			}else if(material_srp.value == ""){
				alert("Material SRP is required! Please enter material SRP.");
				material_srp.focus();
				return false;
			}else if(material_onhand.value == ""){
				alert("Material on hand is required! Please enter material on hand.");
				material_onhand.focus();
				return false;
			}else if(material_lowstock.value == ""){
				alert("Material low stock QTY is required! Please enter material low stock QTY.");
				material_lowstock.focus();
				return false;
			}else if(getSRP() > 0){
				alert("Please enter a valid Material Standard Retail Price! \n formula: discounted price * 35% + discounted price.");
				material_srp.value = "";
				material_srp.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>